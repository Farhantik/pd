<?php
session_start();
require 'functions.php';


if (!isset($_SESSION['login'])) {
  header("Location: sign-in.php");
  exit;
}


$conn = koneksi();

$menuItems = getAllMenuItems();
try {

  $query = "SELECT * FROM menu WHERE status = '1'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "<div class='alert alert-danger'>Database error: " . $e->getMessage() . "</div>";
  exit;
}


?>



<!DOCTYPE html>
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
      background: linear-gradient(to bottom, #000000, #000000);
      /* Gradasi hitam */
      color: #FFD700;
      /* Warna emas */
      font-family: 'Poppins', sans-serif;
    }

    .btn-primary {
      background-color: #FFD700;
      border-color: #FFD700;
      color: #000;
    }

    .btn-warning {
      background-color: #333;
      border-color: #000000;
      color: #FFD700;
    }

    .header-image img {
      border-bottom: 5px solid #FFD700;
      width: 100%;
      height: 100%;
      object-fit: cover;

      /* Menambahkan border emas */
    }


    .resi-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      color: #f7ff0f;
    }

    .resi-table th,
    .resi-table td {
      border: 1px solid #ffffff;
      padding: 8px;
      text-align: center;
    }

    .resi-table th {
      background-color: #000000ac;
    }

    .resi-table tr:nth-child(even) {
      background-color: #1f1f1f;
    }

    .navbar-logo {
      width: 40px;
      /* Atur lebar logo sesuai kebutuhan */
      height: auto;
      /* Menjaga proporsi gambar */
      margin-right: 10px;
      /* Jarak antara logo dan teks */
    }

    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: background-color 0.5s ease;
    }

    .nav-link {
      transition: color 0.3s ease;
    }

    .nav-link:hover {
      color: #FFD700;
      text-decoration: underline;
      /* Tambah garis bawah pada hover */
    }

    .navbar {
      background-color: rgba(0, 0, 0, 0.8);
      /* Sedikit transparan untuk tampilan modern */
      transition: background-color 0.5s ease;
    }


    .card {
      transition: all 0.3s ease;
      border-radius: 10px;
      overflow: hidden;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0px 10px 20px rgba(172, 166, 166, 0.3);
    }

    .card-img-top {
      border-bottom: 2px solid #FFD700;
      /* Warna emas pada gambar */
    }

    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:hover {
      background-color: #fff;
      color: #FFD700;
    }

    .btn-warning:hover {
      background-color: #FFD700;
      color: #000;
    }




    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #f2f2f2;
    }

    .table-striped tbody tr:hover {
      background-color: #eaeaea;
    }

    body {
      font-family: 'Poppins', sans-serif;
      color: #656262c2;
    }

    h2,
    h5 {
      color: #f7ff0f;
      text-align: center;
    }

    a,
    .btn {
      color: #fff;
    }

    .footer {
      background-color: #0000008c;
      padding: 20px;
      color: #FFD700;
      border-top: 5px solid #FFD700;
    }

    .footer a {
      color: #FFD700;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
      color: #fff;
    }

    .header-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      /* Gelap transparan */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .header-overlay h1 {
      font-size: 48px;
      font-weight: bold;
      color: #FFD700;
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
    }

    @media (max-width: 768px) {
      .table td img {
        width: 60px;
        height: auto;
      }

      .table th,
      .table td {
        font-size: 12px;
        padding: 10px;
      }

      .btn {
        font-size: 12px;
        padding: 5px 10px;
      }
    }
    
  </style>
</head>

<body>
  <!-- Header with Image -->
  <div class="header-image">
    <img src="assets/img/header.png" alt="Header Image">
    <!-- <div class="header-overlay">
      <h1 class="text-white">Restoran Padang</h1>
    </div> -->
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
      <img src="assets/img/logo.png" alt="Restoran Padang Logo" class="navbar-logo">
      Restoran Padang
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#menu">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="laporan.html">Laporan</a>
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
  <div class="container mt-5">
    <div class="card shadow-lg rounded">
      <div class="card-header bg-dark text-white text-center py-3">
        <h2 class="mb-0">Menu Items</h2>
      </div>
      <div class="card-body p-4">
        <!-- Tambahkan table-responsive agar tabel dapat discroll di layar kecil -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered text-center" style="color: #000000;">
            <thead class="thead-light">
              <tr>
                <th>ID Menu</th>
                <th>Resi</th>
                <th>Menu Item</th>
                <th>Description</th>
                <th>Image</th>
                <th>Price</th>
                <th>Date Added</th>
                <th>Stock Menu</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($menuItems)): ?>
                <tr>
                  <td colspan="9" class="text-center text-muted">No menu items available</td>
                </tr>
              <?php else: ?>
                <?php foreach ($menuItems as $item): ?>
                  <tr id="menu-item-<?= htmlspecialchars($item['id_menu']); ?>">
                    <td><?= htmlspecialchars($item['id_menu']); ?></td>
                    <td><?= htmlspecialchars($item['resi']); ?></td>
                    <td><?= htmlspecialchars($item['menu_item']); ?></td>
                    <td><?= htmlspecialchars($item['description']); ?></td>
                    <td>
                      <img src="uploads/<?= htmlspecialchars($item['image_url']); ?>"
                        alt="<?= htmlspecialchars($item['menu_item']); ?>"
                        width="80" class="img-thumbnail">
                    </td>
                    <td>Rp <?= number_format($item['price'], 2, ',', '.'); ?></td>
                    <td><?= date('d-m-Y', strtotime($item['created_at'])); ?></td>
                    <td><?= htmlspecialchars($item['stok_menu']); ?></td>
                    <td>
                      <a href="edit_menu.php?id=<?= $item['id_menu']; ?>" class="btn btn-warning btn-sm">Edit</a>
                      <button class="btn btn-danger btn-sm" onclick="deleteMenu(<?= $item['id_menu']; ?>)">Delete</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer text-center py-3">
        <a href="tambah_menu.php" class="btn btn-primary">Add New Menu Item</a>
      </div>
    </div>
  </div>

  <script>
    function deleteMenu(id) {
      if (confirm('Are you sure you want to delete this menu item?')) {
        fetch('delete_menu.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              id_menu: id
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Remove the row from the table
              document.getElementById(`menu-item-${id}`).style.display = 'none';
              alert('Menu item successfully marked as deleted.');
            } else {
              alert('Error: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the menu item.');
          });
      }
    }
  </script>




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
                <h5 class="card-title"><?= htmlspecialchars($item['menu_item']); ?></h5>
                <p class="card-text"><?= htmlspecialchars($item['description']); ?></p>
                <a href="detail_menu.php?id=<?= htmlspecialchars($item['id_menu']); ?>" class="btn btn-primary">View Details</a>
                <a href="edit_menu.php?id=<?= htmlspecialchars($item['id_menu']); ?>" class="btn btn-warning">Edit Menu</a>
              </div>
            </div>

          </div>
        <?php endforeach; ?>

      </div>
    </div>
  </div>

  <!-- Order List Table -->
  <div class="card">
    <div class="card-header">
      <h2>Order List</h2>
    </div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Customer Name</th>
            <th>Dish</th>
            <th>Extras</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Azam</td>
            <td>Rendang</td>
            <td>Krispi, Sambal</td>
            <td>2</td>
            <td></td>
            <td>
              <a href="edit_order.html?order_id=1" class="btn btn-warning btn-sm">Edit</a>

            </td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Ipung</td>
            <td>Gulai Ikan</td>
            <td>Krispi, Sambal</td>
            <td>3</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">3</th>
            <td>Bramada</td>
            <td>Sate</td>
            <td>Krispi, Sambal</td>
            <td>9</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">4</th>
            <td>Ibnu</td>
            <td>Gulai Tunjang</td>
            <td>Krispi, Sambal</td>
            <td>4</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">5</th>
            <td>Micael</td>
            <td>Sayur Nangka</td>
            <td>Krispi</td>
            <td>5</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">6</th>
            <td>Sindiy</td>
            <td>Ayam Pop</td>
            <td>Krispi, Sambal</td>
            <td>2</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">7</th>
            <td>Sinta</td>
            <td>Gulai Ikan</td>
            <td>Krispi, Sambal</td>
            <td>6</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">8</th>
            <td>Fahkri</td>
            <td>Rendang</td>
            <td>Krispi, Sambal</td>
            <td>1</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">9</th>
            <td>Ardian</td>
            <td>Sate</td>
            <td>Krispi</td>
            <td>5</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>

          <tr>
            <th scope="row">10</th>
            <td>Budi</td>
            <td>Ayam Pop</td>
            <td>Sambal</td>
            <td>4</td>
            <td></td>
            <td>
              <button class="btn btn-warning btn-sm">Edit</button>


            </td>
          </tr>


          <!-- Add more rows as needed -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- Resi Table -->
  <div class="resi-container">
    <h2>Resi List</h2>
    <table class="resi-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Order ID</th>
          <th>Customer Name</th>
          <th>Dish</th>
          <th>Resi Number</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>ORD1234</td>
          <td>Azam</td>
          <td>Rendang</td>
          <td>056789754</td>
        </tr>
        <tr>
          <td>2</td>
          <td>ORD1235</td>
          <td>Ipung</td>
          <td>Gulai Ikan</td>
          <td>056789759</td>
        </tr>
        <tr>
          <td>3</td>
          <td>ORD1236</td>
          <td>Bramada</td>
          <td>Sate</td>
          <td>056789750</td>
        </tr>
        <tr>
          <td>4</td>
          <td>ORD1237</td>
          <td>Ibnu</td>
          <td>Gulai Tunjang</td>
          <td>056789794</td>
        </tr>
        <tr>
          <td>5</td>
          <td>ORD1238</td>
          <td>Micael</td>
          <td>Sayur Nangka</td>
          <td>056789757</td>
        </tr>
        <tr>
          <td>6</td>
          <td>ORD1239</td>
          <td>Sindiy</td>
          <td>Ayam Pop</td>
          <td>056789854</td>
        </tr>
        <tr>
          <td>7</td>
          <td>ORD1240</td>
          <td>Sinta</td>
          <td>Gulai Ikan</td>
          <td>056789704</td>
        </tr>
        <tr>
          <td>8</td>
          <td>ORD1241</td>
          <td>Fahkri</td>
          <td>Rendang</td>
          <td>056789799</td>
        </tr>
        <tr>
          <td>9</td>
          <td>ORD1242</td>
          <td>Ardian</td>
          <td>Sate</td>
          <td>056789700</td>
        </tr>
        <tr>
          <td>10</td>
          <td>ORD1243</td>
          <td>Budi</td>
          <td>Ayam Pop</td>
          <td>056789798</td>
        </tr>
        <!-- Add more rows as needed -->
      </tbody>
    </table>
  </div>

  <script src="https://unpkg.com/scrollreveal"></script>
  <script>
    ScrollReveal().reveal('h2, .card, .table', {
      distance: '50px',
      duration: 1000,
      easing: 'ease-in-out',
      origin: 'bottom',
      reset: true,
    });
  </script>







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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.9.2/dist/html2pdf.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>


</body>

</html>