<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  if (isset($data['id_menu'])) {
    $id_menu = $data['id_menu'];

    // Fetch the menu item to check its role
    try {
      $conn = koneksi(); // Establish database connection
      $stmt = $conn->prepare("SELECT role FROM menu WHERE id_menu = :id_menu");
      $stmt->bindParam(':id_menu', $id_menu);
      $stmt->execute();
      $menu_item = $stmt->fetch(PDO::FETCH_ASSOC);

      // Check if the menu item was found
      if (!$menu_item) {
        echo json_encode(['success' => false, 'message' => 'Menu item not found.']);
        exit;
      }

      // Check if the logged-in user is trying to delete their own data
      session_start(); // Start the session to access session variables
      if ($_SESSION['role'] === 'admin1' && $menu_item['role'] === 'admin') {
        echo json_encode(['success' => false, 'message' => 'Access denied: Admin cannot delete another admin\'s data.']);
        exit;
      }

      // Update the status to 0 (soft delete)
      $stmt = $conn->prepare("UPDATE menu SET status = 0 WHERE id_menu = :id_menu");
      $stmt->bindParam(':id_menu', $id_menu);
      $stmt->execute();

      echo json_encode(['success' => true]);
    } catch (PDOException $e) {
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
