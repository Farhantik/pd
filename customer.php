<?php
require 'functions.php'; // Include the functions.php file

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect and sanitize input
  $customerName = $_POST['customerName'];
  $dishSelection = $_POST['dishSelection'];
  $quantity = intval($_POST['quantity']); // Convert to integer
  $totalPrice = floatval(str_replace(',', '', $_POST['totalPrice'])); // Ensure total price is a number
  $dateOfPurchase = $_POST['dateOfPurchase'];
  $extras = isset($_POST['extras']) ? $_POST['extras'] : []; // Capture extras as an array

  // Check required fields
  if (empty($customerName) || empty($dishSelection) || empty($quantity) || empty($dateOfPurchase)) {
    echo "Required fields are missing.";
    return; // Stop processing if fields are missing
  }

  // Convert extras array to PostgreSQL array format
  $extrasArray = !empty($extras) ? '{' . implode(',', $extras) . '}' : '{}';

  // Insert order into database
  if (insertOrder($customerName, $dishSelection, $quantity, $totalPrice, $dateOfPurchase, $extrasArray)) {
    echo "Order placed successfully!";
  } else {
    echo "Failed to place order.";
  }
}


$conn = koneksi();
var_dump($conn); // This should not be null

// Fetching all menu items for the main menu display
$menuItems = getAllMenuItems($conn); // Make sure to pass $conn


?>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran Padang</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #010417;
      /* Lebih gelap, hitam */
      margin: 0;
      padding: 0;
    }

    .header-image {
      width: 100%;
      height: 400px;
      position: relative;
      overflow: hidden;
    }

    .header-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(50%);
      /* Lebih gelap untuk kontras dengan teks emas */
    }

    .header-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #FFD700;
      /* Warna emas */
      text-align: center;
      font-size: 3rem;
      font-weight: 700;
    }

    .navbar {
      background-color: #000;
    }

    .navbar-nav .nav-link {
      color: #FFD700 !important;
      /* Warna emas */
    }

    .navbar-nav .nav-link:hover {
      color: #ffffff !important;
    }

    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
      height: 250px;
      object-fit: cover;
      color: #FFD700;
    }

    .card-body {
      background-color: #333333;
      /* Lebih gelap untuk konsistensi */
      color: #FFD700;
      /* Emas */
      padding: 1.5rem;
    }

    .btn-primary {
      background-color: #FFD700;
      /* Emas */
      border: none;
      color: #000;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 50px;
    }

    .btn-primary:hover {
      background-color: #f7c600;
      color: #000;
    }

    .order-form {
      background-color: #1f1f1f;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .order-form h2 {
      color: #FFD700;
      font-family: 'Playfair Display', serif;
    }

    .contact-section {
      background-color: #000;
      color: #FFD700;
      /* Emas */
      padding: 40px 20px;
      border-radius: 15px;
    }

    .contact-section a {
      color: #FFD700;
      text-decoration: underline;
    }

    .section-padding {
      padding: 60px 0;
    }

    .parallax1 {
      background-image: url('assets/img/about-hero.jpg');
      background-attachment: fixed;
      background-size: cover;
      background-position: center;
      height: 800px;
      color: #FFD700;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 2rem;
      font-weight: 700;
    }

    .parallax2 {
      background-image: url('assets/img/about-hero.jpg');
      background-attachment: fixed;
      background-size: cover;
      background-position: center;
      height: 800px;
      color: #FFD700;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 2rem;
      font-weight: 700;
    }

    .parallax {
      background-image: url('assets/img/parallax-background.jpg');
      background-attachment: fixed;
      background-size: cover;
      background-position: center;
      height: 800px;
      color: #FFD700;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 2rem;
      font-weight: 700;

      .old-standard-tt-regular {
        font-family: "Old Standard TT", serif;
        font-weight: 400;
        font-style: normal;
      }
    }

    .hero-image {
      background: url('assets/img/parallax-background.jpg') no-repeat center center;
      background-size: cover;
      height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    .hero-overlay {
      background: rgba(0, 0, 0, 0.6);
      /* Lebih gelap untuk kontras */
      padding: 20px;
      border-radius: 10px;
      text-align: center;
    }

    .hero-overlay h1 {
      font-size: 3rem;
      margin-bottom: 10px;
      color: #FFD700;
      /* Emas */
    }

    .hero-overlay p {
      font-size: 1.5rem;
      color: #FFD700;
      /* Emas */
    }

    .about-section {
      padding: 60px 20px;
      background-color: #212121;
      color: #FFD700;
      /* Emas */
    }

    .about-content {
      padding: 20px;
      background-color: #333333;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .about-content h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      margin-bottom: 20px;
      color: #FFD700;
      /* Emas */
    }

    .about-content p {
      font-size: 1.1rem;
      line-height: 1.6;
    }

    .icon {
      width: 60px;
      height: 60px;
    }

    .about-stats {
      display: flex;
      justify-content: space-between;
      text-align: center;
      margin-top: 20px;
    }

    .stat {
      flex: 1;
      margin: 0 15px;
      background-color: #3c3c3c;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .stat h3 {
      font-size: 3rem;
      margin-bottom: 10px;
      color: #FFD700;
      /* Emas */
    }

    .stat p {
      font-size: 1.2rem;
    }

    .order-section {
      background-color: #464646;
      color: #FFD700;
      /* Emas */
      padding: 60px 0;
    }

    .order-card {
      background-color: #333333;
      color: #FFD700;
      /* Emas */
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 25px rgba(192, 192, 192, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .order-card:hover {
      transform: scale(1.02);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .form-label {
      font-size: 1.2rem;
      font-weight: bold;
      color: #FFD700;
      /* Emas */
    }

    .form-control-lg {
      height: 45px;
      border-radius: 50px;
      border: 1px solid #646464;
      padding: 10px 20px;
      font-size: 1.1rem;
    }

    .form-check-label {
      font-size: 1.1rem;
      margin-left: 10px;
      color: #FFD700;
      /* Emas */
    }

    .footer {
      background-color: #000;
      color: #FFD700;
      /* Emas */
      padding: 20px 0;
      text-align: center;
    }

    .footer a {
      color: #FFD700;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    .about-section {
      padding: 60px 0;
    }

    .about-content {
      margin-bottom: 30px;
    }

    .about-stats {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }

    .stat {
      flex: 1 1 30%;
      /* Make each stat take up to 30% of the width */
      min-width: 200px;
      /* Ensure a minimum width for better responsiveness */
      text-align: center;
      margin: 15px;
    }

    .stat h3 {
      font-size: 2rem;
      /* Adjust font size for statistics */
      color: #FFD700;
      /* Optional: change color */
    }

    .stat p {
      font-size: 1.2rem;
      /* Adjust font size for the description */
      color: #FFD700
        /* Optional: change color */
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .about-content {
        margin-bottom: 40px;
      }

      .stat {
        flex: 1 1 100%;
        /* Stack stats on smaller screens */
      }
    }

    .order-section {
      background-color: transparent;
      /* Light background for contrast */
      padding: 40px 0;
      /* Add padding to the section */
    }

    .card {
      border-radius: 15px;
      /* Rounded corners for the card */
    }

    .card-body {
      background: transparent;
      /* Gradient background */
    }

    .form-label {
      font-weight: bold;
      /* Bold labels for better readability */
      color: #333;
      /* Dark color for labels */
    }

    .btn-primary {
      background-color: #FFD700;
      /* Gold color for buttons */
      border: none;
      /* Remove border */
    }

    .btn-primary:hover {
      background-color: #ffc107;
      /* Darker shade on hover */
      transition: background-color 0.3s ease;
      /* Smooth transition */
    }

    .form-control {
      border-radius: 10px;
      /* Rounded corners for input fields */
    }

    .form-check-input {
      border-radius: 5px;
      /* Rounded corners for checkboxes */
    }

    @media (max-width: 768px) {
      .order-section {
        padding: 20px 0;
        /* Reduce padding on smaller screens */
      }

      .btn-lg {
        width: 100%;
        /* Full width buttons on small screens */
      }
    }
  </style>

</head>

<body>
  <!-- Header with Image -->
  <!-- <div class="header-image">
  <img src="assets/img/parallax-background.jpg" alt="Header Image">

</div> -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="assets/img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top mr-2">
      Restoran Padang
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#about"><i class="fas fa-info-circle mr-1"></i>About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#menu"><i class="fas fa-utensils mr-1"></i>Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#order"><i class="fas fa-shopping-cart mr-1"></i>Order</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#contact"><i class="fas fa-envelope mr-1"></i>Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="chart.php"><i class="fas fa-chart-bar mr-1"></i>Chart</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user mr-1"></i>Account
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>




  <div class="parallax1">
    <div>
      <h1>Selamat datang di Restoran Padang Merdeka <br> Perjalanan Kuliner Melalui Cita Rasa Asli Indonesia.</h1>

    </div>
  </div>

  <!-- Hero Section -->
  <div class="parallax">
    <div>
      <h1>Discover Authentic Padang Cuisine</h1>
      <a href="#menu" class="btn btn-primary">Explore Our Menu</a>
    </div>
  </div>
  <div class="container section-padding">
    <!-- About Us Section -->
    <div id="about" class="about-section">
      <div class="container">
        <div class="row align-items-center">
          <!-- Left Column: Text Content -->
          <div class="col-lg-6 mb-4">
            <div class="about-content">
              <h2>Our Story</h2>
              <p>Welcome to <strong>Restoran Padang</strong>! We proudly present authentic Padang cuisine that brings the rich traditional flavors of Indonesia right to your table. Our restaurant is dedicated to providing a culinary experience that not only satisfies your taste buds but also gives you a glimpse into the rich heritage of Padang, Sumatra.</p>
              <div class="d-flex align-items-center mb-4">
                <img src="assets/img/chef-icon.png" alt="Chef Icon" class="icon mr-3">
                <p>At Restoran Padang, we use only the freshest ingredients and traditional recipes passed down through generations. Our menu features a variety of dishes slow-cooked and seasoned to perfection. From the renowned Rendang to the delicious Ayam Pop, every dish is prepared with care and attention to detail.</p>
              </div>
              <p>Our mission is to create a warm and inviting atmosphere where you can enjoy a meal with family and friends while experiencing the true essence of Padang cuisine. Whether you're a local or visiting from afar, we invite you to savor our culinary delights and create unforgettable memories.</p>
            </div>
          </div>
          <!-- Right Column: Statistics -->
          <div class="col-lg-6 mb-4">
            <div class="about-stats">
              <div class="stat d-flex align-items-center mb-3">
                <i class="fas fa-utensils fa-3x mr-3"></i>
                <div>
                  <h3>50+</h3>
                  <p>Delicious Dishes</p>
                </div>
              </div>
              <div class="stat d-flex align-items-center mb-3">
                <i class="fas fa-clock fa-3x mr-3"></i>
                <div>
                  <h3>10+</h3>
                  <p>Years of Experience</p>
                </div>
              </div>
              <div class="stat d-flex align-items-center">
                <i class="fas fa-smile fa-3x mr-3"></i>
                <div>
                  <h3>1000+</h3>
                  <p>Happy Customers</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mt-5">
          <a href="#contact" class="btn btn-primary">Contact Us</a>
        </div>
      </div>
    </div>
  </div>


  <div class="container mt-5">
    <div id="menu" class="mt-5">
      <h2 class="text-center mb-4 pt-5">Our Menu</h2>
      <div class="row">

        <!-- Loop through each menu item -->
        <?php foreach ($menuItems as $item): ?>
          <div class="col-md-4 mb-3">
            <div class="card shadow">

              <!-- Image Section -->
              <a href="detail_menu.php?id=<?= htmlspecialchars($item['id_menu']); ?>">
                <img src="uploads/<?= htmlspecialchars($item['image_url']); ?>"
                  alt="<?= htmlspecialchars($item['menu_item']); ?>"
                  class="card-img-top" style="max-height: 200px; object-fit: cover;">
              </a>

              <div class="card-body shadow">
                <h5 class="card-title">
                  <i class="fas fa-utensils"></i> <?= htmlspecialchars($item['menu_item']); ?>
                </h5>
                <p class="card-text">
                  <i class="fas fa-info-circle"></i> <?= htmlspecialchars($item['description']); ?>
                </p>
                <a href="detail_menu.php?id=<?= htmlspecialchars($item['id_menu']); ?>" class="btn btn-primary">
                  <i class="fas fa-eye"></i> View Details
                </a>
                <a href="edit_menu.php?id=<?= htmlspecialchars($item['id_menu']); ?>" class="btn btn-warning">
                  <i class="fas fa-edit"></i> Edit Menu
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

      </div>
    </div>
  </div>


  <div class="parallax2">
    <div id="order" class="order-section">
      <h2 class="text-center mb-4">Place Your Order</h2>
      <form id="orderForm" method="POST" enctype="multipart/form-data">
        <div id="dishContainer" class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 mb-4">
              <div class="card shadow-sm">
                <div class="card-body">
                  <div class="row">
                    <!-- Customer Name -->
                    <div class="col-md-6 mb-4">
                      <div class="form-group">
                        <label for="customerName" class="form-label">
                          <i class="fas fa-user"></i> Customer Name
                        </label>
                        <input type="text" class="form-control form-control-lg" name="customerName" id="customerName" placeholder="Enter your name" required>
                      </div>
                    </div>

                    <!-- Select Dish -->
                    <div class="col-md-6 mb-4">
                      <div class="form-group">
                        <label for="dishSelection" class="form-label">
                          <i class="fas fa-utensils"></i> Select Dish
                        </label>
                        <select class="form-control form-control-lg" name="dishSelection" id="dishSelection" required>
                          <option value="">-- Select a Dish --</option>
                          <option value="Rendang" data-price="50000">Rendang</option>
                          <option value="Ayam Pop" data-price="45000">Ayam Pop</option>
                          <option value="Gulai Ikan" data-price="40000">Gulai Ikan</option>
                          <option value="Sayur Nangka" data-price="30000">Sayur Nangka</option>
                          <option value="Gulai Tunjang" data-price="55000">Gulai Tunjang</option>
                          <option value="Sate" data-price="35000">Sate</option>
                        </select>
                      </div>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-6 mb-4">
                      <div class="form-group">
                        <label for="quantity" class="form-label">
                          <i class="fas fa-sort-numeric-up-alt"></i> Quantity
                        </label>
                        <input type="number" class="form-control form-control-lg quantity" name="quantity" id="quantity" min="1" value="1" required>
                      </div>
                    </div>

                    <!-- Total Price -->
                    <div class="col-md-6 mb-4">
                      <div class="form-group">
                        <label for="totalPrice" class="form-label">
                          <i class="fas fa-money-bill-wave"></i> Total Price (IDR)
                        </label>
                        <input type="text" class="form-control form-control-lg" name="totalPrice" id="totalPrice" readonly>
                      </div>
                    </div>

                    <!-- Date of Purchase -->
                    <div class="col-md-6 mb-4">
                      <div class="form-group">
                        <label for="dateOfPurchase" class="form-label">
                          <i class="fas fa-calendar-alt"></i> Date of Purchase
                        </label>
                        <input type="date" class="form-control form-control-lg" name="dateOfPurchase" id="dateOfPurchase" required>
                      </div>
                    </div>

                    <!-- Extras -->
                    <div class="col-md-6 mb-4">
                      <div class="form-group">
                        <label for="extras" class="form-label">
                          <i class="fas fa-plus-circle"></i> Extras
                        </label>
                        <div class="form-check">
                          <input class="form-check-input extra" type="checkbox" name="extras[]" value="Krispi" id="extraKrispi">
                          <label class="form-check-label" for="extraKrispi">Krispi</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input extra" type="checkbox" name="extras[]" value="Sambal" id="extraSambal">
                          <label class="form-check-label" for="extraSambal">Sambal</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5 mt-3">
                      <i class="fas fa-check"></i> Submit Order
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>






  <!-- Contact Section -->
  <div id="contact" class="contact-section mt-5">
    <h2 class="text-center mb-4">Contact Us</h2>
    <p class="text-center">
    For reservations or inquiries, please email us at
      <i class="fas fa-envelope"></i>
     
      <a href="mailto:info@restoranpadang.com">info@restoranpadang.com</a>
    </p>
    <p class="text-center">
      <i class="fas fa-phone"></i>
      or call us at +62 123 456 789.
    </p>

    <!-- Social Media Links -->
    <div class="text-center mt-4">
      <h5>Follow Us:</h5>
      <a href="https://www.instagram.com/yourusername" target="_blank" class="mx-2">
        <i class="fab fa-instagram fa-2x"></i>
      </a>
      <a href="https://www.tiktok.com/@yourusername" target="_blank" class="mx-2">
        <i class="fab fa-tiktok fa-2x"></i>
      </a>
      <a href="https://x.com/yourusername" target="_blank" class="mx-2">
        <i class="fab fa-x fa-2x"></i>
      </a>
    </div>
  </div>


  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2024 Restoran Padang. All rights reserved.</p>
      <p>123 Food Street, Culinary City, CO 12345 | <a href="mailto:info@restoranpadang.com">info@restoranpadang.com</a>
      </p>
      <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const dishContainer = document.getElementById('dishContainer');
      const addDishButton = document.getElementById('addDish');
      const orderForm = document.getElementById('orderForm');

      function createDishItem() {
        const dishItem = document.createElement('div');
        dishItem.classList.add('dish-item', 'mb-4');
        dishItem.innerHTML = `
                    <div class="form-group">
                        <label for="dishSelection" class="form-label">Select Dish</label>
                        <select class="form-control form-control-lg" name="dishes[0][name]" required>
                            <option value="">-- Select a Dish --</option>
                            <option value="Rendang">Rendang</option>
                            <option value="Ayam Pop">Ayam Pop</option>
                            <option value="Gulai Ikan">Gulai Ikan</option>
                            <option value="Sayur Nangka">Sayur Nangka</option>
                            <option value="Gulai Tunjang">Gulai Tunjang</option>
                            <option value="Sate">Sate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="extras" class="form-label">Extras</label>
                        <div class="form-check">
                            <input class="form-check-input extra" type="checkbox" name="dishes[0][extras][]" value="Krispi">
                            <label class="form-check-label">Krispi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input extra" type="checkbox" name="dishes[0][extras][]" value="Sambal">
                            <label class="form-check-label">Sambal</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control form-control-lg quantity" name="dishes[0][quantity]" min="1" value="1" required>
                    </div>
                `;
        return dishItem;
      }

      function updateDishIndices() {
        const dishItems = dishContainer.querySelectorAll('.dish-item');
        dishItems.forEach((item, index) => {
          item.querySelectorAll('[name^="dishes["]').forEach(element => {
            element.name = element.name.replace(/dishes\[\d+\]/, `dishes[${index}]`);
          });
        });
      }

      addDishButton.addEventListener('click', function() {
        dishContainer.appendChild(createDishItem());
        updateDishIndices();
      });

      // Add the first dish item by default
      dishContainer.appendChild(createDishItem());
    });

    // Function to calculate total price
    function calculateTotalPrice() {
      const dishSelect = document.getElementById('dishSelection');
      const quantityInput = document.getElementById('quantity');
      const totalPriceInput = document.getElementById('totalPrice');

      const selectedOption = dishSelect.options[dishSelect.selectedIndex];
      const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
      const quantity = parseInt(quantityInput.value) || 0;

      const totalPrice = price * quantity;
      totalPriceInput.value = totalPrice.toLocaleString(); // Format to include commas
    }

    // Event listeners for changes in dish selection and quantity
    document.getElementById('dishSelection').addEventListener('change', calculateTotalPrice);
    document.getElementById('quantity').addEventListener('input', calculateTotalPrice);

    // Initial calculation to set default value
    calculateTotalPrice();
  </script>

  <script>
    document.getElementById('dishSelection').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
      const quantity = parseInt(document.getElementById('quantity').value);
      const totalPrice = price * quantity;

      document.getElementById('totalPrice').value = totalPrice.toLocaleString(); // Format as currency
    });

    document.getElementById('quantity').addEventListener('input', function() {
      const selectedOption = document.getElementById('dishSelection').options[document.getElementById('dishSelection').selectedIndex];
      const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
      const quantity = parseInt(this.value);
      const totalPrice = price * quantity;

      document.getElementById('totalPrice').value = totalPrice.toLocaleString(); // Format as currency
    });
  </script>

</body>

</html>