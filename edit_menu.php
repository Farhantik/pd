<?php
session_start();
require 'functions.php';

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
  header("Location: sign-in.php");
  exit;
}

// Get the menu item ID from the URL
$id_menu = $_GET['id'] ?? null;

// Fetch the menu item details
if ($id_menu !== null) {
  $item = getMenuItemById($id_menu);
} else {
  echo "<div class='alert alert-danger'>Menu item ID is missing.</div>";
  exit;
}

// Check if the menu item was found
if ($item === false) {
  echo "<div class='alert alert-danger'>Menu item not found.</div>";
  exit;
}

// Generate or retrieve the resi value
$resi = $item['resi'] ?? generateResiForMenuItem($id_menu);
if ($resi === null) {
  echo "<div class='alert alert-danger'>Resi value is missing or invalid.</div>";
  exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Validate and sanitize numeric inputs
  $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
  if ($price <= 0) {
    echo "<div class='alert alert-danger'>Please enter a valid price.</div>";
    exit;
  }

  // Validate stok_menu
  $stok_menu = in_array($_POST['stok_menu'], ['available', 'not_available']) ? $_POST['stok_menu'] : 'not_available';

  // Upload the image, default to old image URL if upload fails
  $image_url = upload();
  if ($image_url === false) {
    $image_url = $item['image_url'];
  }

  // Update the menu item
  $updateSuccess = updateMenuItem($id_menu, $_POST['menu_item'], $_POST['description'], $price, $stok_menu, $image_url, $resi);

  if ($updateSuccess) {
    // Redirect to index or another page after successful update
    header("Location: index.php"); // Change to your actual index page
    exit;
  } else {
    echo "<div class='alert alert-danger'>Failed to update menu item.</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Menu</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #010417;
      font-family: 'Roboto', sans-serif;
      color: #FFD700;
    }

    .container {
      background-color: #1a1a1a;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      margin: 40px auto;
      max-width: 600px;
    }

    h1 {
      color: #FFD700;
      text-align: center;
      margin-bottom: 30px;
    }

    label {
      color: #FFD700;
      margin-bottom: 10px;
      display: block;
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 2px solid #FFD700;
      background-color: #010417;
      color: #FFD700;
      border-radius: 5px;
      font-size: 16px;
    }

    input[type="file"] {
      padding: 3px;
    }

    button {
      background-color: #FFD700;
      color: #010417;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 18px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #ffdd33;
    }

    .nav-link {
      color: #FFD700 !important;
    }

    .nav-link:hover {
      color: #ffdd33 !important;
    }

    img {
      max-width: 200px;
      max-height: 200px;
      margin-bottom: 15px;
    }

    @media (max-width: 576px) {
      .container {
        margin: 20px;
        padding: 15px;
      }

      h1 {
        font-size: 1.5rem;
      }
    }
  </style>

</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="assets/img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top mr-2">
      Restoran Padang
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Kembali</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container mt-5">
    <h1>Edit Menu Item</h1>
    <form action="edit_menu.php?id=<?= htmlspecialchars($item['id_menu']); ?>" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="menu_item">Menu Item</label>
        <input type="text" class="form-control" id="menu_item" name="menu_item" value="<?= htmlspecialchars($item['menu_item']); ?>" required>
      </div>
      <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($item['description']); ?></textarea>
      </div>
      <div class="form-group">
        <label for="price">Price</label>
        <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= htmlspecialchars($item['price']); ?>" required>
      </div>
      <div class="form-group">
        <label for="stok_menu">Stok Menu</label>
        <select class="form-control" id="stok_menu" name="stok_menu" required>
          <option value="available" <?= $item['stok_menu'] == 'available' ? 'selected' : ''; ?>>Available</option>
          <option value="not_available" <?= $item['stok_menu'] == 'not_available' ? 'selected' : ''; ?>>Not Available</option>
        </select>
      </div>
      <div class="form-group">
        <label for="image_url">Current Image</label><br>
        <?php if (!empty($item['image_url'])): ?>
          <img src="uploads/<?= htmlspecialchars($item['image_url']); ?>" alt="Current Image">
        <?php else: ?>
          <p>No image uploaded.</p>
        <?php endif; ?>
      </div>
      <div class="form-group">
        <label for="image_url">Upload New Image</label>
        <input type="file" class="form-control-file" id="image_url" name="image_url" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Update Menu</button>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>