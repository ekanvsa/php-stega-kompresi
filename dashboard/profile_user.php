<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMm8T6E4szdc10U7Y67T6hE3xCJdyHvY52TSSY" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
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
            max-width: 900px;
            color: #333;
            flex: 1;
        }

        .sidebar {
            background-color: #343a40;
            padding: 15px;
            height: 100vh;
        }

        .sidebar .nav-link {
            color: #ffffff;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .sidebar .nav-link:hover {
            color: #adb5bd;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .content {
            padding: 20px;
        }

        .container-fluid {
            padding: 0;
            flex: 1;
        }

        footer {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-white mb-4">Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile_user.html"><i class="fas fa-user"></i> Profile User</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.html"><i class="fas fa-history"></i> Histori</a>
                    </li>
                </ul>
            </nav>
            <main class="col-md-9 col-lg-10 content">
                <div class="form-container">
                    <h1 class="mb-4">Profile User</h1>
                    <p class="lead">Informasi detail mengenai profile user akan ditampilkan di sini.</p>
                    <!-- Tambahkan konten profile user di sini -->
                </div>
            </main>
        </div>
    </div>
    
    <footer>
        &copy; 2024 Your Company. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
