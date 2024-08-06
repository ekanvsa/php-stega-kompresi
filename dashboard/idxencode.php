<?php
// Memulai sesi PHP
session_start();

// Memeriksa apakah user sudah login, jika tidak, diarahkan ke halaman login
if (!isset($_SESSION['userId'])) {
  header("Location: ../login.php");
  exit();
}

// Menyimpan user ID dari sesi ke variabel $id
$id = $_SESSION['userId'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <!-- Menetapkan karakter encoding dan pengaturan tampilan -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encode Gambar</title>
  <!-- Menghubungkan ke Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <style>
    /* CSS untuk styling halaman */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Georgia', serif;
      background-color: #f4f4f4;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .form-container {
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      margin: 20px auto;
      max-width: 600px;
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

    .step-container {
      max-width: 600px;
      max-height: 300px;
      overflow-y: auto;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-top: 20px;
      margin-left: 20px;
    }

    .step {
      max-width: fit-content;
      margin-bottom: 20px;
      padding: 10px;
      background-color: #f9f9f9;
      border-radius: 8px;
    }

    .horizontal-layout {
      display: flex;
      flex-direction: row;
      gap: 20px;
    }

    .encoded-image {
      max-width: 100px;
      height: auto;
    }

    .dropdown-menu.show {
      display: block;
    }

    .footer {
      position: relative;
      margin-top: auto;
      left: 0;
      bottom: 0;
      width: 100%;
      color: black;
      text-align: center;
      padding: 10px 0;
    }
  </style>
  <!-- Menghubungkan ke jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <style>
    /* CSS tambahan untuk background */
    body {
      background-image: url('img/bg2e.jpg');
      background-size: cover;
      background-repeat: no-repeat;
    }
  </style>
  <!-- Navbar dengan Bootstrap -->
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
            <a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="dropdownToggle">
              Steganografi LSB
            </a>
            <ul class="dropdown-menu" id="dropdownMenu">
              <li><a class="dropdown-item" href="idxencode.php">Encode</a></li>
              <li><a class="dropdown-item" href="idxdecode.php">Decode</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="history.php">History</a>
          </li>
        </ul>
        <form class="d-flex bg-primary">
          <img class="img-profile rounded-circle" src="img/undraw_profile.svg" />
        </form>
      </div>
    </div>
  </nav>

  <!-- Kontainer utama -->
  <div class="container horizontal-layout">
    <div class="form-container">
      <h1 class="mb-4">Encode Gambar</h1>
      <!-- Form untuk upload gambar dan pesan -->
      <form id="encodeForm" enctype="multipart/form-data" method="post" action="created_data.php">
        <div class="mb-3">
          <label for="image" class="form-label">Pilih Gambar:</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/png" required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Pesan Rahasia:</label>
          <input type="text" class="form-control" id="message" name="message" placeholder="Masukkan pesan rahasia" required>
        </div>
        <button type="submit" class="btn btn-primary">Encode</button>
      </form>
    </div>
    <div>
      <div id="result" class="mt-3"></div>
      <div id="steps" class="step-container"></div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Menggunakan jQuery untuk menangani pengiriman form secara asinkron
      $('#encodeForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        // Mengirim data form ke server menggunakan AJAX
        $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            let res = JSON.parse(response);
            if (res.status === 'success') {
              $('#result').html(`
                <div class="alert alert-success"  role="alert">
                  ${res.message}
                  <br>
                  <img src="${res.imagePath}" class="img-fluid mt-3 encoded-image" alt="Encoded Image">
                  <br>
                  <a href="${res.imagePath}" download="encoded_image.png" class="btn btn-success mt-3">Download Gambar</a>
                </div>
              `);
              let stepsHTML = '';
              // Menampilkan langkah-langkah penyembunyian pesan dalam gambar
              res.steps.forEach((step, index) => {
                stepsHTML += `
                  <div class="step">
                  <div class="card">
                    <h5>Step ${index + 1}</h5>
                    <p>Pixel (${step.pixel.x}, ${step.pixel.y})</p>
                    <p>Colors: R: ${step.colors.red}, G: ${step.colors.green}, B: ${step.colors.blue}</p>
                    <p>Binary message: ${step.binary_message}</p>
                    <p>Index: ${step.index}</p>
                  </div>
                  </div>
                `;
              });
              $('#steps').html(stepsHTML);
            } else {
              $('#result').html(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
            }
          },
          error: function() {
            $('#result').html(`<div class="alert alert-danger" role="alert">Terjadi kesalahan. Silakan coba lagi.</div>`);
          }
        });
      });
    });

    // Menggunakan JavaScript untuk menangani dropdown menu
    document.addEventListener('DOMContentLoaded', function() {
      const dropdownToggle = document.getElementById('dropdownToggle');
      const dropdownMenu = document.getElementById('dropdownMenu');

      dropdownToggle.addEventListener('click', function(event) {
        // Toggle the dropdown menu
        const isActive = dropdownMenu.classList.toggle('show');
        dropdownToggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');
      });
    });
  </script>
  <!-- Footer -->
  <div class="footer">
    <p>Copyright &copy; Eka Novita Sari 2024.</p>
  </div>
</body>

</html>
