<?php
session_start(); // Memulai sesi pengguna untuk melacak sesi login

include('db.php'); // Menghubungkan ke file database untuk mendapatkan koneksi

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Memeriksa apakah request yang diterima adalah POST
    $email = $_POST['email']; // Mengambil email dari data POST yang dikirimkan
    $password = $_POST['password']; // Mengambil password dari data POST yang dikirimkan

    // Menyiapkan statement SQL untuk memilih detail pengguna berdasarkan email
    $stmt = $conn->prepare("SELECT userId, username, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email); // Mengikat parameter email ke statement SQL
    $stmt->execute(); // Menjalankan statement SQL
    $stmt->store_result(); // Menyimpan hasil query untuk digunakan di bind_result
    $stmt->bind_result($id, $username, $hashed_password); // Mengikat hasil query ke variabel
    $stmt->fetch(); // Mengambil hasil query

    // Memeriksa apakah ada hasil yang ditemukan dan apakah password yang dimasukkan sesuai dengan hash password di database
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['userId'] = $id; // Menyimpan ID pengguna di sesi
        $_SESSION['username'] = $username; // Menyimpan nama pengguna di sesi
        header("Location: dashboard/index.php"); // Mengarahkan pengguna ke halaman dashboard setelah login berhasil
    } else {
        echo "Invalid email or password."; // Menampilkan pesan jika email atau password tidak valid
    }

    $stmt->close(); // Menutup statement untuk membebaskan sumber daya
    $conn->close(); // Menutup koneksi database untuk membebaskan sumber daya
}
?>
