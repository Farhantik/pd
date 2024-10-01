<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['login'])) {
  header("Location: sign-in.php");
  exit;
}

require 'functions.php'; // Ensure the path is correct

// Fetch orders
$orders = getOrder(); // This should work if $pdo is set up correctly
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Summary</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      background-color: #010417;
      font-family: 'Roboto', sans-serif;
      color: #FFD700;
      padding: 40px;
    }

    h2 {
      color: #FFD700;
      text-align: center;
      margin-bottom: 20px;
    }

    /* Grid layout */
    .order-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    /* Card styling */
    .card {
      background-color: #1a1a1a;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      color: #FFD700;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .card-header {
      background-color: #FFD700;
      color: black;
      padding: 10px;
      text-align: center;
      font-weight: bold;
    }

    .card-body {
      padding: 15px;
    }

    .card-body p {
      margin: 0;
      margin-bottom: 10px;
      color: #FFD700;
    }

    .icon {
      margin-right: 10px;
    }

    .card-footer {
      background-color: #2c2c2c;
      padding: 10px;
      text-align: center;
    }

    .btn {
      border-radius: 5px;
      padding: 10px 20px;
      margin: 5px;
      transition: background-color 0.3s, transform 0.3s;
    }

    .btn-warning {
      background-color: #FFC107;
      color: black;
    }

    .btn-warning:hover {
      background-color: #ffb300;
      color: white;
      transform: translateY(-2px);
    }

    .btn-primary {
      background-color: #007bff;
      color: white;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
    }

    /* Responsive grid layout */
    @media (max-width: 768px) {
      .order-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      }

      .btn {
        width: 100%;
        margin-bottom: 10px;
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
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="customer.php">Kembali</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <h2 class="text-center mb-4">Order Summary</h2>
    <div class="order-grid">
      <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
          <div class="card">
            <div class="card-header">
              <i class="fas fa-user icon"></i> Order for <?php echo htmlspecialchars($order['customer_name']); ?>
            </div>
            <div class="card-body">
              <p><i class="fas fa-utensils icon"></i><strong>Dish:</strong> <?php echo htmlspecialchars($order['menu_item']); ?></p>
              <p><i class="fas fa-sort-numeric-up icon"></i><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
              <p><i class="fas fa-money-bill icon"></i><strong>Total Price (IDR):</strong>
                <?php
                $unitPrice = 50000; // Example price per unit
                $totalPrice = $unitPrice * $order['quantity'];
                echo number_format($totalPrice, 0, ',', '.');
                ?>
              </p>
              <p><i class="fas fa-calendar-alt icon"></i><strong>Date of Purchase:</strong> <?php echo htmlspecialchars($order['date_of_purchase']); ?></p>
              <p><i class="fas fa-plus-circle icon"></i><strong>Extras:</strong>
                <?php
                $extrasString = $order['extras'];
                $extrasArray = explode(',', trim($extrasString, '{}'));
                echo !empty($extrasArray[0]) ? implode(', ', $extrasArray) : 'None';
                ?>
              </p>
            </div>
            <div class="card-footer">
              <a href="edit_orders.php?id=<?php echo $order['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
              <a href="payment.php?id=<?php echo $order['id']; ?>" class="btn btn-primary"><i class="fas fa-credit-card"></i> Pay</a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
          No orders found
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>