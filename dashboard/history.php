<?php
session_start();
if (!isset($_SESSION['userId'])) {
  header("Location: ../login.php");
  exit();
}
$id = $_SESSION['userId'];
?>
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

    .dropdown-menu.show {
      display: block;
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown" aria-expanded="true" id="dropdownToggle">
              Steganografi LSB
            </a>
            <ul class="dropdown-menu" id="dropdownMenu">
              <li><a class="dropdown-item" href="idxencode.php">Encode</a></li>
              <li><a class="dropdown-item" href="idxdecode.php">Decode</a></li>
            </ul>
          </li>
          <li class="nav-item ">
            <a class="nav-link active" href="history.php">History</a>
          </li>
        </ul>
        <form class="d-flex bg-primary">
          <img class="img-profile rounded-circle" src="img/undraw_profile.svg" />
        </form>
      </div>
    </div>
  </nav>
  <div class="d-flex my-3 py-1 justify-content-center">
    <div class="card" style="width: 1000px;">
      <table class="table">
        <thead>
          <tr class=" text-center">
            <th scope="col">No</th>
            <th scope="col">File Encode</th>
            <th scope="col">File Hasil Encode</th>
            <th scope="col">Tanggal Encode</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php
          include('../db.php');

          // Set the number of records to display per page
          $limit = 10;

          // Get the current page number from the URL, default to page 1 if not set
          $page = isset($_GET['page']) ? $_GET['page'] : 1;

          // Calculate the starting record for the current page
          $start = ($page - 1) * $limit;

          // Query to get the total number of records
          $sql = "SELECT COUNT(*) AS total FROM image WHERE userId = ?";
          if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $total_records = $row['total'];
            $stmt->close();
          } else {
            echo "Error: " . $conn->error;
            exit();
          }

          // Calculate the total number of pages
          $total_pages = ceil($total_records / $limit);

          // Query to get data from image table with limit and offset
          $sql = "SELECT fileName, fileNameDecode, createdAt FROM image WHERE userId = ? LIMIT ?, ?";
          if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iii", $id, $start, $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              $no = $start + 1;
              // Output data of each row
              while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<th scope='row' class='text-center'>" . $no++ . "</th>";
                echo "<td>" . $row["fileName"] . "</td>";
                echo "<td class='text-center'>" . $row["fileNameDecode"] . "</td>";
                echo "<td class='text-center'>" . $row["createdAt"] . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='4' class='text-center'>No data found</td></tr>";
            }

            $stmt->close();
          } else {
            echo "Error: " . $conn->error;
            exit();
          }

          $conn->close();
          ?>
        </tbody>
      </table>
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1) : ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
          <?php endfor; ?>
          <?php if ($page < $total_pages) : ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
  <script>
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

  <div class="footer">
    <p>Copyright &copy; Eka Novita Sari 2024.</p>
  </div>
</body>

</html>