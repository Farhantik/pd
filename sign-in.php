<?php
session_start();
require 'functions.php';

// Jika pengguna sudah login, arahkan ke halaman customer
if (isset($_SESSION['login'])) {
  header("Location: customer.php");
  exit;
}

// Ketika tombol login ditekan
$login_error = '';
if (isset($_POST['login'])) {
  $login = login($_POST);

  if (isset($login['error']) && $login['error'] == true) {
    $login_error = $login['pesan']; // Menyimpan pesan error jika login gagal
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Sign In to Restoran Padang">
  <title>Sign In</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles -->
  <style>
    body {
      background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('assets/img/about-hero.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      font-family: 'Oswald', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-signin {
      background-color: rgba(0, 0, 0, 0.85);
      padding: 50px;
      border-radius: 15px;
      box-shadow: 0px 0px 20px rgba(255, 215, 0, 0.7);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }

    .form-signin img {
      filter: drop-shadow(2px 2px 10px gold);
      display: block;
      margin: 0 auto 20px;
    }

    .form-signin h1 {
      color: gold;
      margin-bottom: 30px;
    }

    .form-control {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
      border: 1px solid gold;
      padding: 10px;
    }

    .form-control:focus {
      border-color: gold;
      box-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
    }

    .btn-login {
      background-color: gold;
      border: none;
      color: black;
      font-weight: bold;
      padding: 10px;
    }

    .btn-login:hover {
      background-color: #d4af37;
      color: black;
    }

    .tambahan {
      margin-top: 20px;
    }

    .register-link {
      color: gold;
      text-decoration: none;
      font-weight: bold;
    }

    .register-link:hover {
      text-decoration: underline;
      color: white;
    }

    hr {
      border-top: 1px solid rgba(255, 215, 0, 0.5);
    }
  </style>
</head>

<body>
  <div class="form-signin">
    <form action="" method="POST"> <!-- Mengubah action agar form tetap di halaman yang sama -->
      <div class="logo">
        <img src="assets/img/logo.png" alt="Logo" width="120" height="120">
      </div>
      <h1 class="h3 mb-3 fw-normal">Sign In</h1>

      <?php if ($login_error) : ?>
        <div class="alert alert-danger" role="alert">
          <?= $login_error; ?>
        </div>
      <?php endif; ?>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Username" required>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
      </div>

      <button class="w-100 btn btn-lg btn-login" type="submit" name="login">Sign In</button>

      <hr>
      <div class="tambahan">
        <p>Don't have an account? <a href="registrasi.php" class="register-link">Register</a></p>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>