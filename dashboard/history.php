<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encode Gambar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background-color: #f4f4f4;
            min-height: 100%;
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
            /* Set the max width for the encoded image */
            height: auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Eka Novitasari</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarScroll">
        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="idxencode.php">Encode</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="idxdecode.php">Decode</a>
          </li>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="index.php" role="button" data-bs-toggle="dropdown" aria-expanded="true">
              Steganografi LSB
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="idxencode.php">Encode</a></li>
              <li><a class="dropdown-item" href="idxdecode.php">Decode</a></li>
            </ul>
          </li> -->
          <li class="nav-item ">
            <a class="nav-link active" href="history.php">History</a>
          </li>
        </ul>
          <form class="d-flex bg-primary">
              <img class="img-profile rounded-circle" src="img/undraw_profile.svg"/>
          </form>
      </div>
    </div>
  </nav>

    <h1>History</h1>
</body>

</html>