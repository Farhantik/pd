<?php

function koneksi()
{
  $host = 'localhost';
  $port = '5432';
  $dbname = 'Db_Restoran_Padang';
  $user = 'postgres';
  $password = 'admin';

  try {

    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $conn;
  } catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
  }
}

function query($query, $params = [])
{
  $conn = koneksi();
  $stmt = $conn->prepare($query);

  if ($params) {

    $stmt->execute($params);
  } else {
    $stmt->execute();
  }


  if ($stmt->rowCount() == 1) {
    return $stmt->fetch();
  }


  return $stmt->fetchAll();
}

function login($data)
{
  $conn = koneksi();

  $username = htmlspecialchars($data['username']);
  $password = htmlspecialchars($data['password']);


  $query = "SELECT * FROM users WHERE username = :username";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);


  if ($result) {
    if (password_verify($password, $result['password'])) {
      $_SESSION['login'] = true;


      if ($result['role'] === 'admin') {
        header("Location: index.php");
      } else if ($result['role'] === 'customer') {
        header("Location: customer.php");
      } else {

        header("Location: error.php");
      }
      exit;
    }
  }

  return [
    'error' => true,
    'pesan' => 'Username / Password Salah!'
  ];
}


function registrasi($data)
{
  $conn = koneksi();

  $username = htmlspecialchars(strtolower($data['username']));
  $password1 = htmlspecialchars($data['password1']);
  $password2 = htmlspecialchars($data['password2']);
  $role = htmlspecialchars($data['role']);


  if (empty($username) || empty($password1) || empty($password2) || empty($role)) {
    echo "<script>
            alert('Username, password, dan role tidak boleh kosong!');
            document.location.href = 'registrasi.php';
          </script>";
    return false;
  }

  $query = "SELECT * FROM users WHERE username = :username";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  if ($stmt->fetch()) {
    echo "<script>
            alert('Username sudah terdaftar!');
            document.location.href = 'registrasi.php';
          </script>";
    return false;
  }


  if ($password1 !== $password2) {
    echo "<script>
            alert('Konfirmasi password tidak sesuai!');
            document.location.href = 'registrasi.php';
          </script>";
    return false;
  }


  if (strlen($password1) < 5) {
    echo "<script>
            alert('Password terlalu pendek!');
            document.location.href = 'registrasi.php';
          </script>";
    return false;
  }


  $password_baru = password_hash($password1, PASSWORD_DEFAULT);
  $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password_baru);
  $stmt->bindParam(':role', $role);
  $stmt->execute();

  return $stmt->rowCount();
}

function submitOrder($customerName, $dishSelection, $extras, $quantity, $dateOfPurchase)
{
  $conn = koneksi();

  try {

    $stmt = $conn->prepare("INSERT INTO customers (name) VALUES (:name) RETURNING id");
    $stmt->bindParam(':name', $customerName);
    $stmt->execute();
    $customerId = $stmt->fetchColumn();


    $stmt = $conn->prepare("SELECT id, price FROM menu WHERE name = :dish_name");
    $stmt->bindParam(':dish_name', $dishSelection);
    $stmt->execute();
    $dish = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dish) {
      throw new Exception('Dish not found.');
    }

    $dishId = $dish['id'];
    $price = $dish['price'];
    $totalPrice = $price * $quantity;

    $stmt = $conn->prepare("INSERT INTO purchases (customer_id, dish_id, extras, quantity, total_price, date_of_purchase) VALUES (:customer_id, :dish_id, :extras, :quantity, :total_price, :date_of_purchase)");
    $stmt->bindParam(':customer_id', $customerId);
    $stmt->bindParam(':dish_id', $dishId);
    $stmt->bindParam(':extras', $extras);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':total_price', $totalPrice);
    $stmt->bindParam(':date_of_purchase', $dateOfPurchase);

    return $stmt->execute();
  } catch (PDOException $e) {

    error_log("Database error: " . $e->getMessage());
    return false;
  } catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    return false;
  }
}




function getMenuItems()
{
  $conn = koneksi();
  $query = "SELECT * FROM menu";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}






function upload()
{

  if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image_url']['tmp_name'];
    $fileName = $_FILES['image_url']['name'];
    $fileSize = $_FILES['image_url']['size'];
    $fileType = $_FILES['image_url']['type'];
    $fileNameComponents = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameComponents));


    $uploadFileDir = './uploads/';
    $newFileName = uniqid('', true) . '.' . $fileExtension;
    $destPath = $uploadFileDir . $newFileName;


    if (move_uploaded_file($fileTmpPath, $destPath)) {
      return $newFileName;
    } else {
      throw new Exception('Error moving the uploaded file.');
    }
  } else {
    throw new Exception('No file uploaded or there was an upload error.');
  }
}


function insertOrder($customerName, $dish, $quantity, $totalPrice, $dateOfPurchase, $extras)
{
  $conn = koneksi();


  $query = "INSERT INTO orders (customer_name, menu_item, quantity, total_price, date_of_purchase, extras) 
              VALUES (:customer_name, :menu_item, :quantity, :total_price, :date_of_purchase, :extras)";


  $stmt = $conn->prepare($query);


  $stmt->bindParam(':customer_name', $customerName);
  $stmt->bindParam(':menu_item', $dish);
  $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
  $stmt->bindParam(':total_price', $totalPrice);
  $stmt->bindParam(':date_of_purchase', $dateOfPurchase);
  $stmt->bindParam(':extras', $extras);


  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}






function getMenuItemByName($dish)
{
  $conn = koneksi();
  $sql = "SELECT * FROM menu WHERE menu_item = :dish LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute([':dish' => $dish]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
  $query = "SELECT * FROM menu WHERE status = 1";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Loop through and display the menu items
  foreach ($menuItems as $item) {
    // Display each menu item
  }
}


function getOrder()
{
  $conn = koneksi();
  $query = "SELECT * FROM orders";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getOrderById($id)
{
  $conn = koneksi();
  $stmt = "SELECT * FROM orders WHERE id = ?";
  $stmt = $conn->prepare($stmt);
  $stmt->execute([$id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}


function updateOrder($data, $id)
{
  // Establish database connection
  $conn = koneksi();
  if (!$conn) {
    return "Connection failed."; // Error message if connection fails
  }

  // Prepare and sanitize the data
  $customer_name = trim($data['customer_name']); // Trim whitespace
  $menu_item = trim($data['menu_item']);
  $quantity = (int)$data['quantity']; // Cast to integer
  $date_of_purchase = $data['date_of_purchase'];

  // Handle extras as a comma-separated string
  if (isset($data['extras']) && is_array($data['extras'])) {
    $extras = implode(", ", $data['extras']); // Convert array to string
  } else {
    $extras = ''; // No extras selected
  }

  // Prepare the SQL update statement
  $query = "UPDATE orders SET 
                customer_name = :customer_name, 
                menu_item = :menu_item, 
                quantity = :quantity, 
                date_of_purchase = :date_of_purchase, 
                extras = :extras 
              WHERE id = :id";

  $stmt = $conn->prepare($query);

  // Bind parameters to the query
  $stmt->bindParam(':customer_name', $customer_name);
  $stmt->bindParam(':menu_item', $menu_item);
  $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
  $stmt->bindParam(':date_of_purchase', $date_of_purchase);
  $stmt->bindParam(':extras', $extras);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);

  // Execute the statement
  if ($stmt->execute()) {
    return $stmt->rowCount(); // Return the number of affected rows
  } else {
    return $stmt->errorInfo(); // Return error information for debugging
  }
}



function insertMenu($data)
{
  $conn = koneksi();

  if (!$conn) {
    return false;
  }

  try {

    $conn->beginTransaction();
    $status = 1;

    $queryMenu = "INSERT INTO menu (menu_item, description, price, stok_menu, resi, image_url, created_at, status) 
                      VALUES (:menu_item, :description, :price, :stok_menu, :resi, :image_url, CURRENT_TIMESTAMP, :status)";
    $stmtMenu = $conn->prepare($queryMenu);


    $stmtMenu->bindParam(':menu_item', $data['menu_item']);
    $stmtMenu->bindParam(':description', $data['description']);
    $stmtMenu->bindParam(':price', $data['price']);
    $stmtMenu->bindParam(':stok_menu', $data['stok_menu']);
    $stmtMenu->bindParam(':resi', $data['resi'], PDO::PARAM_INT);
    $stmtMenu->bindParam(':image_url', $data['image_url']);
    $stmtMenu->bindParam(':status', $status);


    if (!$stmtMenu->execute()) {
      throw new PDOException("Insert to menu table failed");
    }


    $id_menu = $conn->lastInsertId();


    $status = 1;


    $checkQuery = "SELECT COUNT(*) FROM log_menu WHERE id_menu = :id_menu";
    $stmtCheck = $conn->prepare($checkQuery);
    $stmtCheck->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
    $stmtCheck->execute();

    $count = $stmtCheck->fetchColumn();


    if ($count == 0) {

      $queryLog_menu = "INSERT INTO log_menu (id_menu, menu_item, description, price, stok_menu, resi, image_url, created_at, status) 
                             VALUES (:id_menu, :menu_item, :description, :price, :stok_menu, :resi, :image_url, CURRENT_TIMESTAMP, :status)";

      $stmtLog_menu = $conn->prepare($queryLog_menu);


      $stmtLog_menu->bindParam(':id_menu', $id_menu);
      $stmtLog_menu->bindParam(':menu_item', $data['menu_item']);
      $stmtLog_menu->bindParam(':description', $data['description']);
      $stmtLog_menu->bindParam(':price', $data['price']);
      $stmtLog_menu->bindParam(':stok_menu', $data['stok_menu']);
      $stmtLog_menu->bindParam(':resi', $data['resi'], PDO::PARAM_INT);
      $stmtLog_menu->bindParam(':image_url', $data['image_url']);
      $stmtLog_menu->bindParam(':status', $status);


      $stmtLog_menu->execute();
    } else {
      echo "<script>alert('Data with id_menu $id_menu already exists in the history table. No new entry added.');</script>";
    }


    $conn->commit();

    return true;
  } catch (PDOException $e) {

    $conn->rollBack();
    echo "Error: " . $e->getMessage();
    return false;
  }
}







function insertLog_menu($conn, $id_menu, $data)
{
  $query = "INSERT INTO log_menu (id_menu, menu_item, description, price, stok_menu, resi, image_url, created_at, status) 
              VALUES (:id_menu, :menu_item, :description, :price, :stok_menu, :resi, :image_url, CURRENT_TIMESTAMP, :status)";

  $stmt = $conn->prepare($query);
  $stmt->execute([
    ':id_menu' => $id_menu,
    ':menu_item' => $data['menu_item'],
    ':description' => $data['description'],
    ':price' => $data['price'],
    ':stok_menu' => $data['stok_menu'],
    ':resi' => $data['resi'],
    ':image_url' => $data['image_url'],
    ':status' => 'insert'
  ]);
}

function deleteMenu($id_menu)
{
  $conn = koneksi();

  if (!$conn) {
    return ["stok_menu" => "error", "message" => "Connection failed."];
  }

  try {

    $query = "UPDATE log_menu SET status = 0 WHERE id_menu = :id_menu";
    $stmt = $conn->prepare($query);


    $stmt->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);


    if ($stmt->execute()) {
      return ["stok_menu" => "success", "message" => "Menu item successfully deleted."];
    } else {
      return ["stok_menu" => "error", "message" => "Failed to delete menu item."];
    }
  } catch (PDOException $e) {
    return ["stok_menu" => "error", "message" => "Error: " . $e->getMessage()];
  }
}



function updateMenuItem($id_menu, $menu_item, $description, $price, $stok_menu, $image_url, $resi)
{
  $conn = koneksi();

  try {
    $conn->beginTransaction();

    // Validate input fields
    if (empty($menu_item) || empty($description) || empty($price) || empty($stok_menu)) {
      throw new Exception("All fields must be filled out correctly.");
    }

    if (!is_numeric($price) || $price <= 0) {
      throw new Exception("Invalid price value.");
    }

    $validStockStatuses = ['available', 'not_available'];
    if (!in_array($stok_menu, $validStockStatuses)) {
      throw new Exception("Invalid stock status. Please choose either 'available' or 'not_available'.");
    }

    // Update the old menu item status to inactive (2)
    $update_query = "UPDATE menu SET status = :status WHERE id_menu = :id_menu";
    $stmt = $conn->prepare($update_query);
    $stmt->execute(['status' => 2, 'id_menu' => $id_menu]);

    if ($stmt->rowCount() === 0) {
      throw new Exception("No rows updated. Check if the id_menu exists.");
    }

    // Insert the new menu item
    $insert_query = "INSERT INTO menu (menu_item, description, price, status, stok_menu, image_url, resi)
                         VALUES (:menu_item, :description, :price, :status, :stok_menu, :image_url, :resi)";
    $stmt = $conn->prepare($insert_query);
    if (!$stmt->execute([
      'menu_item' => $menu_item,
      'description' => $description,
      'price' => $price,
      'status' => 1, // Active status for new item
      'stok_menu' => $stok_menu,
      'image_url' => $image_url,
      'resi' => $resi
    ])) {
      throw new Exception("Failed to insert new menu item.");
    }

    // Commit the transaction
    $conn->commit();
    return true; // Return true on success
  } catch (PDOException $e) {
    $conn->rollBack();
    echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
    return false; // Return false on database error
  } catch (Exception $e) {
    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    return false; // Return false on validation error
  } finally {
    $conn = null;
  }
}



function getAllMenuItems()
{
  // Include your PDO database connection
  global $conn;

  try {
    // SQL query to select all menu items with status 1 (active)
    // Explicitly cast status to integer if it's varchar in the database
    $sql = "SELECT id_menu, menu_item, description, image_url FROM menu WHERE status::integer = 1";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Execute the statement
    $stmt->execute();

    // Fetch all menu items as an associative array
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the result
    return $menuItems;
  } catch (PDOException $e) {
    // Handle any errors (you can log this or display a message)
    echo "Error: " . $e->getMessage();
    return [];
  }
}



function generateResiForMenuItem($id_menu)
{

  $timestamp = time();
  $uniqueResi = "RESI-" . $id_menu . "-" . $timestamp;

  return $uniqueResi;
}












function getMenuItemById($id)
{
  $conn = koneksi();


  $query = "SELECT * FROM menu WHERE id_menu = :id_menu";
  $stmt = $conn->prepare($query);


  $stmt->bindParam(':id_menu', $id, PDO::PARAM_INT);

  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
