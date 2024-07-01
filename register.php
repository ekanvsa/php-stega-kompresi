<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $username, $hashed_password);

    if ($stmt->execute()) {
        // $_SESSION['userId'] = $id;
        // $_SESSION['username'] = $username;
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
