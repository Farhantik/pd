<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
  header("Location: sign-in.php");
  exit;
}

require 'functions.php'; // Ensure the path is correct

// Fetch the order ID from the URL
$orderId = $_GET['id'] ?? null; // Get order ID from the URL

if ($orderId === null) {
  echo "<div class='alert alert-danger'>Order ID is missing.</div>";
  exit; // Stop execution if order ID is not provided
}

// Fetch the order details using the order ID
$order = getOrderById($orderId);
if ($order === false) {
  echo "<div class='alert alert-danger'>Order not found.</div>";
  exit; // Stop execution if the order cannot be found
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Assume $orderId is already retrieved from the URL
  $updateResult = updateOrder($_POST, $orderId);

  if (is_numeric($updateResult)) {
    echo "<div class='alert alert-success'>Order updated successfully. Affected rows: $updateResult</div>";
  } else {
    echo "<div class='alert alert-danger'>Failed to update order: $updateResult</div>";
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Order</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
  body {
    background-color: #010417;
    font-family: 'Roboto', sans-serif;
    color: #FFD700;
    padding: 40px;
  }

  h1 {
    color: #FFD700;
    text-align: center;
    margin-bottom: 40px;
  }

  .table-container {
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 50px;
  }

  .table thead th {
    background-color: #FFD700;
    color: black;
  }

  .table tbody tr {
    color: black;
  }

  .chart-container {
    width: 80%;
    margin: 0 auto;
  }

  canvas {
    background-color: #ffffff;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }


  .container {
    margin-top: 50px;
  }

  h2 {
    color: #333;
  }

  .btn-primary {
    background-color: #FFD700;
    border-color: #FFD700;
    color: #000;
  }

  .btn-primary:hover {
    background-color: #fff;
    color: #FFD700;
  }
</style>


<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="assets/img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-top mr-2">
      Restoran Padang
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="chart.php">Kembali</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <h2>Edit Order</h2>
    <form action="" method="POST">
      <div class="form-group">
        <label for="customerName">Customer Name</label>
        <input type="text" class="form-control" name="customer_name" id="customerName" value="<?php echo htmlspecialchars($order['customer_name']); ?>" required>
      </div>
      <div class="form-group">
        <label for="dishSelection">Select Dish</label>
        <select class="form-control" name="menu_item" required>
          <option value="" disabled>Select a Dish</option>
          <option value="Rendang" <?php echo ($order['menu_item'] === 'Rendang') ? 'selected' : ''; ?>>Rendang</option>
          <option value="Ayam Pop" <?php echo ($order['menu_item'] === 'Ayam Pop') ? 'selected' : ''; ?>>Ayam Pop</option>
          <option value="Gulai Ikan" <?php echo ($order['menu_item'] === 'Gulai Ikan') ? 'selected' : ''; ?>>Gulai Ikan</option>
          <option value="Sayur Nangka" <?php echo ($order['menu_item'] === 'Sayur Nangka') ? 'selected' : ''; ?>>Sayur Nangka</option>
          <option value="Gulai Tunjang" <?php echo ($order['menu_item'] === 'Gulai Tunjang') ? 'selected' : ''; ?>>Gulai Tunjang</option>
          <option value="Sate" <?php echo ($order['menu_item'] === 'Sate') ? 'selected' : ''; ?>>Sate</option>
        </select>
      </div>
      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" class="form-control" name="quantity" id="quantity" value="<?php echo htmlspecialchars($order['quantity']); ?>" min="1" required oninput="calculateTotal()">
      </div>
      <div class="form-group">
        <label for="totalPrice">Total Price (IDR)</label>
        <input type="number" class="form-control" name="total_price" id="totalPrice" value="<?php echo htmlspecialchars($order['total_price']); ?>" readonly>
      </div>
      <div class="form-group">
        <label for="dateOfPurchase">Date of Purchase</label>
        <input type="date" class="form-control" name="date_of_purchase" id="dateOfPurchase" value="<?php echo htmlspecialchars($order['date_of_purchase']); ?>" required>
      </div>
      <div class="form-group">
        <label>Extras</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="extras[]" value="Krispi" id="extraKrispi" <?php echo (in_array('Krispi', explode(',', $order['extras']))) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="extraKrispi">Krispi</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="extras[]" value="Sambal" id="extraSambal" <?php echo (in_array('Sambal', explode(',', $order['extras']))) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="extraSambal">Sambal</label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Update Order</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    function calculateTotal() {
      const quantity = document.getElementById('quantity').value;
      const totalPrice = quantity * 50000; // Adjust this based on your pricing logic
      document.getElementById('totalPrice').value = totalPrice;
    }
  </script>
</body>

</html>