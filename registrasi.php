<?php
require 'functions.php';

if (isset($_POST['registrasi'])) {
  if (registrasi($_POST) > 0) {
    echo "<script>
                alert('User baru berhasil ditambahkan. Silahkan login!');
                document.location.href = 'sign-in.php';
              </script>";
  } else {
    echo "<script>
                alert('User gagal ditambahkan!');
              </script>";
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Register for Restoran Padang">
  <title>Register</title>

  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap" rel="stylesheet">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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

    .tambahan a {
      color: gold;
      text-decoration: none;
      font-weight: bold;
    }

    .tambahan a:hover {
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
    <form action="" method="POST">
      <div class="logo">
        <img src="assets/img/logo.png" alt="Logo" width="150" height="150">
      </div>
      <h1 class="h3 mb-3 fw-normal">Register</h1>

      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingUsername" name="username" placeholder="Enter your username" required>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="floatingPassword" name="password1" placeholder="Enter your password" required>
      </div>
      <div class="form-floating mb-3">
        <input type="password" class="form-control" id="floatingPassword2" name="password2" placeholder="Confirm your password" required>
      </div>
      <div class="form-floating mb-3">
        <select class="form-control" id="floatingRole" name="role" required>
          <option value="">Select Role</option>
          <option value="admin">Admin</option>
          <option value="admin1">Admin1</option>
          <option value="admin2">Admin2</option>
          <option value="customer">Customer</option>
        </select>
      </div>

      <button class="w-100 btn btn-lg btn-login" type="submit" name="registrasi">Register</button>

      <hr>
      <div class="tambahan">
        <p>Already have an account? <a href="sign-in.php">Login here</a></p>
      </div>
    </form>
  </div>

  <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>