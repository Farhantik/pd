<?php
include 'functions.php'; // Memanggil file functions.php

// Mengambil id_menu dari URL
$id_menu = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Ganti dengan metode yang sesuai untuk mendapatkan ID
$menuItem = getMenuItemById($id_menu); // Mengambil data menu berdasarkan id_menu
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rendang - Restoran Padang</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f9f9f9;
      position: relative;
      overflow-x: hidden;
    }

    /* Background Animation */
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 800px;
      background: linear-gradient(135deg, rgba(255, 223, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%);
      z-index: -1;
      animation: gradientBG 10s ease-in-out infinite alternate;
    }

    @keyframes gradientBG {
      0% {
        background: linear-gradient(135deg, rgba(255, 223, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%);
      }

      100% {
        background: linear-gradient(135deg, rgba(255, 223, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 100%);
      }
    }

    /* Navbar */
    .navbar {
      background-color: #1c1c1c;
      padding: 15px;
    }

    .navbar-brand {
      font-weight: bold;
      color: gold !important;
      font-size: 26px;
      letter-spacing: 1px;
      text-transform: uppercase;
      transition: color 0.3s ease;
    }

    .navbar-brand:hover {
      color: #e6b800 !important;
    }

    .nav-link {
      color: white !important;
      font-size: 16px;
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: gold !important;
    }

    /* Card */
    .card {
      border: none;
      border-radius: 20px;
      background-color: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-top: 40px;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .card-body h2 {
      color: #1c1c1c;
      font-weight: 700;
      font-size: 30px;
      text-align: center;
      margin-bottom: 20px;
      text-transform: uppercase;
    }

    .card-body p {
      font-size: 18px;
      color: #555;
      line-height: 1.6;
      text-align: justify;
    }

    .btn-primary {
      background-color: gold;
      border: none;
      color: black;
      font-weight: bold;
      padding: 12px 25px;
      border-radius: 50px;
      display: block;
      margin: 0 auto;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #e6b800;
      transform: scale(1.05);
    }

    /* Image */
    .img-fluid {
      border-radius: 20px 0 0 20px;
      transition: transform 0.3s ease, filter 0.3s ease;
    }

    .img-fluid:hover {
      transform: scale(1.1);
      filter: brightness(1.1);
    }

    /* Shadow Effect */
    .shadow {
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .img-fluid {
        border-radius: 20px 20px 0 0;
      }

      .card-body {
        padding: 30px;
      }
    }

    /* Floating Animation */
    @keyframes float {
      0% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-10px);
      }

      100% {
        transform: translateY(0);
      }
    }

    .btn-primary {
      animation: float 5s ease-in-out infinite;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-utensils"></i> Restoran Padang
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="customer.php#menu">
            <i class="fas fa-book-open"></i> Menu
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Content -->
  <div class="container mt-5 pt-5">
    <?php if ($menuItem): ?>
      <div class="card shadow mb-4">
        <div class="row no-gutters">
          <div class="col-md-6">
            <img src="uploads/<?= htmlspecialchars($menuItem['image_url']); ?>" class="img-fluid" alt="<?= htmlspecialchars($menuItem['menu_item']); ?>">
          </div>
          <div class="col-md-6">
            <div class="card-body">
              <h2 class="card-title"><?= htmlspecialchars($menuItem['menu_item']); ?></h2>
              <p class="card-text"><?= htmlspecialchars($menuItem['description']); ?></p>
              <p class="card-text"><strong>Harga:</strong> Rp. <?= number_format($menuItem['price'], 2, ',', '.'); ?></p>
              <a href="customer.php#order" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Order Now
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="alert alert-warning" role="alert">
        Menu item not found.
      </div>
    <?php endif; ?>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>