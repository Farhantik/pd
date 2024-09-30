<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  $data = json_decode(file_get_contents('php://input'), true);

  if (isset($data['id_menu'])) {
    $id_menu = $data['id_menu'];

    // Update the role  0 
    try {
      $conn = koneksi(); // Establish database connection
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
