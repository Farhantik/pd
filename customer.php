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


?>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restoran Padang</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Playfair+Display:wght@700&display=swap"
    rel="stylesheet">
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
          <a class="nav-link" href="#about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#menu">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#order">Order</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#contact">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="chart.php">Chart</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            Account
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </nav>

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
              <p>Welcome to <strong>Restoran Padang</strong>! We proudly present authentic Padang cuisine that brings
                the rich traditional flavors of Indonesia right to your table. Our restaurant is dedicated to providing
                a culinary experience that not only satisfies your taste buds but also gives you a glimpse into the rich
                heritage of Padang, Sumatra.</p>
              <div class="d-flex align-items-center mb-4">
                <img src="assets/img/chef-icon.png" alt="Chef Icon" class="icon mr-3">
                <p>At Restoran Padang, we use only the freshest ingredients and traditional recipes passed down through
                  generations. Our menu features a variety of dishes slow-cooked and seasoned to perfection. From the
                  renowned Rendang to the delicious Ayam Pop, every dish is prepared with care and attention to detail.
                </p>
              </div>
              <p>Our mission is to create a warm and inviting atmosphere where you can enjoy a meal with family and
                friends while experiencing the true essence of Padang cuisine. Whether you're a local or visiting from
                afar, we invite you to savor our culinary delights and create unforgettable memories.</p>
            </div>
          </div>
          <!-- Right Column: Statistics -->
          <div class="col-lg-6 mb-4">
            <div class="about-stats">
              <div class="stat">
                <h3>50+</h3>
                <p>Delicious Dishes</p>
              </div>
              <div class="stat">
                <h3>10+</h3>
                <p>Years of Experience</p>
              </div>
              <div class="stat">
                <h3>1000+</h3>
                <p>Happy Customers</p>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mt-5">
          <a href="#contact" class="btn btn-primary">Contact Us</a>
        </div>
      </div>
    </div>


    <!-- Menu Section -->
    <div id="menu" class="text-center">
      <h2 class="mb-4 pt-5">Our Menu</h2>
      <div class="row">
        <!-- Example Menu Item -->
        <div class="col-md-4 mb-3">
          <div class="card shadow">
            <a href="detail_rendang.html">
              <img src="assets/img/rendang.png" class="card-img-top" alt="Rendang">
            </a>
            <div class="card-body">
              <h5 class="card-title">Rendang</h5>
              <p class="card-text">Daging sapi yang dimasak perlahan dengan saus kelapa yang kaya rasa dan pedas.</p>
              <a href="detail_rendang.html" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <!-- Example Menu Item -->
        <div class="col-md-4 mb-3">
          <div class="card shadow">
            <a href="detail_ayam_pop.html">
              <img src="assets/img/ayam pop.png" class="card-img-top" alt="Ayam_Pop">
            </a>
            <div class="card-body">
              <h5 class="card-title">Ayam Pop</h5>
              <p class="card-text">Ayam lembut yang direndam dalam bumbu tradisional dan digoreng dengan sempurna.</p>
              <a href="detail_ayam_pop.html" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <!-- Example Menu Item -->
        <div class="col-md-4 mb-3">
          <div class="card shadow">
            <a href="detail_gulai_ikan.html">
              <img src="assets/img/Resep-Gulai-Ikan.png" class="card-img-top" alt="Gulai_Ikan">
            </a>
            <div class="card-body">
              <h5 class="card-title">Gulai Ikan</h5>
              <p class="card-text">Ikan yang dimasak dengan saus santan yang pedas dan tajam dengan mengunakan berbagai
                rempah.</p>
              <a href="detail_gulai_ikan.html" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <!-- Example Menu Item -->
        <div class="col-md-4 mb-3">
          <div class="card shadow">
            <a href="detail_sayur_nangka.html">
              <img src="assets/img/sayur nangka.png" class="card-img-top" alt="Sayur_Nangka">
            </a>
            <div class="card-body">
              <h5 class="card-title">Sayur Nangka</h5>
              <p class="card-text">Nangka muda yang dimasak dengan santan dan rempah-rempah.</p>
              <a href="detail_sayur_nangka.html" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <!-- Example Menu Item -->
        <div class="col-md-4 mb-3">
          <div class="card shadow">
            <a href="detail_gulai_tunjang.html">
              <img src="assets/img/gulai tunjang.png" class="card-img-top" alt="Gulai_Tunjang">
            </a>
            <div class="card-body">
              <h5 class="card-title">Gulai Tunjang</h5>
              <p class="card-text">Ini adalah gulai atau kari yang terbuat dari daging sapi (biasanya bagian kaki).</p>
              <a href="detail_gulai_tunjang.html" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>

        <!-- Example Menu Item -->
        <div class="col-md-4 mb-3">
          <div class="card shadow">
            <a href="detail_sate.html">
              <img src="assets/img/sate.png" class="card-img-top" alt="Sate">
            </a>
            <div class="card-body">
              <h5 class="card-title">Sate</h5>
              <p class="card-text">Sate ini terbuat dari daging sapi yang dipotong kecil-kecil.</p>
              <a href="detail_sate.html" class="btn btn-primary">View Details</a>
            </div>
          </div>
        </div>
        <!-- Add other menu items here -->
      </div>
    </div>







    <div id="order" class="order-section">
      <h2 class="text-center mb-4">Place Your Order</h2>
      <form id="orderForm" method="POST" enctype="multipart/form-data">
        <div id="dishContainer">
          <div class="dish-item">
            <div class="form-group">
              <label for="customerName" class="form-label">Customer Name</label>
              <input type="text" class="form-control form-control-lg" name="customerName" id="customerName" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
              <label for="dishSelection" class="form-label">Select Dish</label>
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
            <div class="form-group">
              <label for="quantity" class="form-label">Quantity</label>
              <input type="number" class="form-control form-control-lg quantity" name="quantity" id="quantity" min="1" value="1" required>
            </div>
            <div class="form-group">
              <label for="totalPrice" class="form-label">Total Price (IDR)</label>
              <input type="text" class="form-control form-control-lg" name="totalPrice" id="totalPrice" readonly>
            </div>
            <div class="form-group">
              <label for="dateOfPurchase" class="form-label">Date of Purchase</label>
              <input type="date" class="form-control form-control-lg" name="dateOfPurchase" id="dateOfPurchase" required>
            </div>
            <div class="form-group">
              <label for="extras" class="form-label">Extras</label>
              <div class="form-check">
                <input class="form-check-input extra" type="checkbox" name="extras[]" value="Krispi">
                <label class="form-check-label">Krispi</label>
              </div>
              <div class="form-check">
                <input class="form-check-input extra" type="checkbox" name="extras[]" value="Sambal">
                <label class="form-check-label">Sambal</label>
              </div>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg px-5">Submit Order</button>
      </form>
    </div>


    <!-- Contact Section -->
    <div id="contact" class="contact-section mt-5">
      <h2 class="text-center mb-4">Contact Us</h2>
      <p class="text-center">For reservations or inquiries, please email us at <a
          href="mailto:info@restoranpadang.com">info@restoranpadang.com</a> or call us at +62 123 456 789.</p>
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
</body>

</html>