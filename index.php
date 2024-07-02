<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <style>
        body {
            background-image: url('dashboard/img/bg2e.jpg');
            background-size: cover;
            background-repeat: no-repeat;
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
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2 id="form-title">Login</h2>
                <p id="toggle-message">Belum punya akun? <a href="./registerScreen.php">Register</a></p>
            </div>
            <form id="form" method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" id="submit-button">Login</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>Copyright &copy; Eka Novita Sari 2024.</p>
    </div>
</body>

</html>