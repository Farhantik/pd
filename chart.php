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

// Additional processing...
?>


<div class="container mt-5">
  <h2 class="text-center mb-4">Order Summary</h2>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Customer Name</th>
        <th>Dish</th>
        <th>Quantity</th>
        <th>Total Price (IDR)</th>
        <th>Date of Purchase</th>
        <th>Extras</th>
        <th>Actions</th> <!-- Added Actions column -->
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($order['menu_item']); ?></td>
            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
            <td>
              <?php
              $unitPrice = 50000; // Example price per unit
              $totalPrice = $unitPrice * $order['quantity'];
              echo number_format($totalPrice, 0, ',', '.');
              ?>
            </td>
            <td><?php echo htmlspecialchars($order['date_of_purchase']); ?></td>
            <td>
              <?php
              // Fixing the extras to properly display as a comma-separated string
              $extrasString = $order['extras'];
              $extrasArray = explode(',', trim($extrasString, '{}'));
              echo implode(', ', $extrasArray);
              ?>
            </td>
            <td>
              <a href="edit_orders.php?id=<?php echo $order['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">No orders found</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>