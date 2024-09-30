<?php

require_once 'functions.php';

try {
  $conn = koneksi();
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $menu_item = filter_input(INPUT_POST, 'menu_item', FILTER_SANITIZE_STRING);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
  $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
  $stok_menu = filter_input(INPUT_POST, 'stok_menu', FILTER_SANITIZE_STRING);

  if (!$menu_item || !$description || $price === false || !$stok_menu) {
    throw new Exception("Invalid input data.");
  }


  $resi = mt_rand(100000, 999999);


  $image_url = upload();
  if (!$image_url) {
    throw new Exception("Failed to upload image.");
  }


  $data = [
    'menu_item' => $menu_item,
    'description' => $description,
    'price' => round($price, 2),
    'stok_menu' => $stok_menu,
    'resi' => $resi,
    'image_url' => $image_url,
  ];


  $conn->beginTransaction();
  try {
    $id_menu = insertMenu($data);


    $log_menuData = [
      'id_menu' => $id_menu,
      'resi' => $resi,
      'menu_item' => $menu_item,
      'description' => $description,
      'price' => round($price, 2),
      'stok_menu' => $stok_menu,
      'image_url' => $image_url,
      'status' => 1
    ];


    $conn->commit();
    echo "<script>alert('Menu and history successfully added!');</script>";
  } catch (Exception $e) {
    $conn->rollBack();
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
  }
}
?>

<!-- Form tambah menu -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <title>Tambah Menu</title>

  <style>
    body {
      background-color: #010417;
      font-family: 'Roboto', sans-serif;
      color: #FFD700;
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background-color: #1a1a1a;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      width: 100%;
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
  <div class="container">
    <h1>Tambah Menu</h1>

    <!-- Form untuk menambah menu baru -->
    <form action="tambah_menu.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="menu_item">Nama Menu:</label>
        <input type="text" name="menu_item" id="menu_item" required>
      </div>

      <div class="form-group">
        <label for="description">Deskripsi:</label>
        <textarea name="description" id="description" required></textarea>
      </div>

      <div class="form-group">
        <label for="price">Harga:</label>
        <input type="number" step="0.01" name="price" id="price" required>
      </div>

      <div class="form-group">
        <label for="stok_menu">Stok Menu:</label>
        <select name="stok_menu" id="stok_menu" required>
          <option value="available">Tersedia</option>
          <option value="unavailable">Tidak Tersedia</option>
        </select>
      </div>

      <div class="form-group">
        <label for="image_url">Upload Gambar:</label>
        <input type="file" name="image_url" id="image_url" required>
      </div>

      <button type="submit">Tambah Menu</button>
    </form>
  </div>

</body>

</html>