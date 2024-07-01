<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}
$username = $_SESSION['username'];

// Proses logout
if (isset($_POST['logout'])) {
    session_destroy(); // Hapus semua data sesi
    header("Location: ../index.php"); // Redirect ke halaman login setelah logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Georgia', serif;
      background-color: #f4f4f4;
      min-height: 100vh;
    }

    .form-container {
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      margin: 20px auto;
      max-width: 900px;
      color: #333;
    }

    .form-label {
      color: #007bff;
      font-size: 20px;
    }

    .form-control {
      font-size: 16px;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      font-size: 18px;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      color: black;
      text-align: center;
      padding: 10px 0;
    }
  </style>
</head>
<body>
  <style>
    body {
      background-image: url('img/bg2.jpg');
      background-size: cover;
      background-repeat: no-repeat;
    }
  </style>
  <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">STEGA LSB</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarScroll">
        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="true">
              Steganografi LSB
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="idxencode.php">Encode</a></li>
              <li><a class="dropdown-item" href="idxdecode.php">Decode</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="history.php">History</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  
  <div class="container">
    <div class="d-flex my-3 py-1 justify-content-center">
      <div class="card" style="width: 500px;">
        <div class="d-flex justify-content-center mt-2">
          <img class="img-profile rounded-circle" src="img/undraw_profile.svg" style="width: 300px; width: 300px;" />
        </div>
        <div class="d-flex justify-content-center">
          <h1><?php echo htmlspecialchars($username); ?></h1>
        </div>
        <div class="d-flex justify-content-center mt-2 mb-5">
          <form method="post" action="">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <div class="footer">
    <p>Copyright &copy; Eka Novita Sari 2024.</p>
  </div>
</body>
</html>
