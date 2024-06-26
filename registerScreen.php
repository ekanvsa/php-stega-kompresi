<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2 id="form-title">Register</h2>
                <p>Sudah punya akun? <a href="./index.php">Login</a></p>
            </div>
            <form id="form" method="POST" action="register.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                </div>
                <button type="submit" id="submit-button">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
