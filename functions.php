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


  $query = "SELECT role, password FROM users WHERE username = :username";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);


  if ($result) {
    if (password_verify($password, $result['password'])) {
      $_SESSION['login'] = true;
      $_SESSION['role'] = $result['role'];

      // Debugging session after setting the role
      echo "<pre>";
      print_r($_SESSION); // Should include 'role'
      echo "</pre>";

      // Redirect based on role...
    }
  }

  // After validating user credentials
  if ($result) {
    if (password_verify($password, $result['password'])) {
      $_SESSION['login'] = true;
      $_SESSION['role'] = $result['role']; // Make sure this line is present

      // Redirect based on role
      if (in_array($result['role'], ['admin', 'admin1', 'admin2'])) {
        header("Location: index.php");
      } elseif ($result['role'] === 'customer') {
        header("Location: customer.php");
      } else {
        header("Location: error.php");
      }
      exit;
    }
  }
}

return [
  'error' => true,
  'pesan' => 'Username / Password Salah!'
];



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
function upload()
{
  $target_dir = "uploads/"; // Define your target directory

  // Check if the uploads directory exists, create if it doesn't
  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
  }

  $file_name = basename($_FILES["image"]["name"]);
  $target_file = $target_dir . $file_name;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Check if the file is an actual image
  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if ($check === false) {
    return "File is not an image.";
  }

  // Check if the file already exists and add a unique identifier (timestamp) if it does
  if (file_exists($target_file)) {
    $file_name = time() . "_" . $file_name;
    $target_file = $target_dir . $file_name;
  }

  // Check file size (optional)
  if ($_FILES["image"]["size"] > 500000) { // Set the size limit (e.g., 500KB)
    return "Sorry, your file is too large.";
  }

  // Allow only certain file formats (optional)
  $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
  if (!in_array($imageFileType, $allowed_types)) {
    return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  }

  // Try to upload the file
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    return $target_file; // Return the path to the uploaded file
  } else {
    return "Sorry, there was an error uploading your file.";
  }
}









function uploadImage()
{
  // Check if an image was uploaded without errors
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $targetDir = "uploads/"; // Directory to store images
    $fileName = basename($_FILES['image']['name']);
    $targetFilePath = $targetDir . $fileName;

    // Get the file extension
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow certain file formats (e.g., jpg, png, gif)
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array(strtolower($fileType), $allowedTypes)) {
      // Check file size (optional, e.g., max 5MB)
      if ($_FILES['image']['size'] < 5 * 1024 * 1024) {
        // Move the file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
          return $fileName; // Return the file name to store in DB
        } else {
          return 'Failed to upload image.';
        }
      } else {
        return 'File size exceeds limit.';
      }
    } else {
      return 'Invalid file format. Only JPG, PNG, and GIF are allowed.';
    }
  }
  return 'No image uploaded.';
}


function insertMenu($data)
{
  global $conn; // Ensure you have access to your DB connection

  // Prepare the SQL statement, now including `status`
  $stmt = $conn->prepare("INSERT INTO menu (menu_item, description, price, stok_menu, resi, image_url, role, status) 
                          VALUES (:menu_item, :description, :price, :stok_menu, :resi, :image_url, :role, :status)");

  // Bind parameters to the statement
  $stmt->bindParam(':menu_item', $data['menu_item']);
  $stmt->bindParam(':description', $data['description']);
  $stmt->bindParam(':price', $data['price']);
  $stmt->bindParam(':stok_menu', $data['stok_menu']);
  $stmt->bindParam(':resi', $data['resi']);
  $stmt->bindParam(':image_url', $data['image_url']); // Bind image URL
  $stmt->bindParam(':role', $data['role']); // Bind role
  $stmt->bindParam(':status', $data['status']); // Bind status

  try {
    // Execute the statement
    $stmt->execute();

    // Return the last inserted ID
    return $conn->lastInsertId();
  } catch (PDOException $e) {
    // Handle error (optional: log the error or show a user-friendly message)
    echo "<div class='alert alert-danger'>Error inserting menu item: " . htmlspecialchars($e->getMessage()) . "</div>";
    return false; // or throw an exception if preferred
  }
}


function insertLogMenu($data)
{
  global $conn; // Ensure you have access to your DB connection

  $stmt = $conn->prepare("INSERT INTO log_menu (id_menu, resi, menu_item, description, price, stok_menu, image_url, status) 
                            VALUES (:id_menu, :resi, :menu_item, :description, :price, :stok_menu, :image_url, :status)");

  $stmt->bindParam(':id_menu', $data['id_menu']);
  $stmt->bindParam(':resi', $data['resi']);
  $stmt->bindParam(':menu_item', $data['menu_item']);
  $stmt->bindParam(':description', $data['description']);
  $stmt->bindParam(':price', $data['price']);
  $stmt->bindParam(':stok_menu', $data['stok_menu']);
  $stmt->bindParam(':image_url', $data['image_url']); // Bind image URL
  $stmt->bindParam(':status', $data['status']);

  $stmt->execute();
}


function insertLog_menu($data) // Removed the $conn parameter as it's already global
{
  global $conn;

  $query = "INSERT INTO log_menu (id_menu, menu_item, description, price, stok_menu, resi, image_url, created_at, status) 
              VALUES (:id_menu, :menu_item, :description, :price, :stok_menu, :resi, :image_url, CURRENT_TIMESTAMP, :status)";

  $stmt = $conn->prepare($query);

  // Bind parameters using the data array
  $stmt->bindParam(':id_menu', $data['id_menu']);
  $stmt->bindParam(':menu_item', $data['menu_item']);
  $stmt->bindParam(':description', $data['description']);
  $stmt->bindParam(':price', $data['price']);
  $stmt->bindParam(':stok_menu', $data['stok_menu']);
  $stmt->bindParam(':resi', $data['resi']);
  $stmt->bindParam(':image_url', $data['image_url']);
  $status = 'insert'; // Assuming you want to set the status to 'insert'
  $stmt->bindParam(':status', $status);

  try {
    $stmt->execute();
  } catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error inserting log menu: " . htmlspecialchars($e->getMessage()) . "</div>";
  }
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



function updateMenuItem($id_menu, $menu_item, $description, $price, $stok_menu, $image_url, $resi, $role)
{
  $conn = koneksi();

  try {
    // Begin transaction
    $conn->beginTransaction();

    // Validate input fields
    if (empty($menu_item) || empty($description) || !is_numeric($price) || empty($stok_menu) || empty($role)) {
      throw new Exception("All fields must be filled out correctly.");
    }

    // Ensure the price is a positive number
    if ($price <= 0) {
      throw new Exception("Invalid price value. Price must be a positive number.");
    }

    // Validate stock status
    $validStockStatuses = ['available', 'not_available'];
    if (!in_array($stok_menu, $validStockStatuses)) {
      throw new Exception("Invalid stock status. Please choose either 'available' or 'not_available'.");
    }

    // Step 1: Update the current menu item status to inactive (status = 2)
    $update_query = "UPDATE menu SET status = :status WHERE id_menu = :id_menu";
    $stmt = $conn->prepare($update_query);
    $stmt->execute(['status' => 2, 'id_menu' => $id_menu]);

    // Check if any rows were updated
    if ($stmt->rowCount() === 0) {
      throw new Exception("No rows updated. Please check if the menu item ID exists.");
    }

    // Step 2: Insert a new record for the updated menu item including the role
    $insert_query = "INSERT INTO menu (menu_item, description, price, status, stok_menu, image_url, resi, role)
                         VALUES (:menu_item, :description, :price, :status, :stok_menu, :image_url, :resi, :role)";
    $stmt = $conn->prepare($insert_query);

    // Execute the insert statement
    if (!$stmt->execute([
      'menu_item'   => $menu_item,
      'description' => $description,
      'price'       => $price,
      'status'      => 1, // Active status for the new item
      'stok_menu'   => $stok_menu,
      'image_url'   => $image_url, // Use the new image URL
      'resi'        => $resi,
      'role'        => $role // Include the role parameter
    ])) {
      throw new Exception("Failed to insert the new menu item.");
    }

    // Commit the transaction if everything went fine
    $conn->commit();
    return true; // Return true on success

  } catch (PDOException $e) {
    // Roll back the transaction if a database error occurs
    $conn->rollBack();
    echo "<div class='alert alert-danger'>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
    return false;
  } catch (Exception $e) {
    // Handle other exceptions (like validation errors)
    echo "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
    return false;
  } finally {
    // Close the database connection
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
