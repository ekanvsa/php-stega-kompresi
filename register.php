<?php
include('db.php'); // Menghubungkan ke file database untuk mendapatkan koneksi

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Memeriksa apakah request yang diterima adalah POST
    $email = $_POST['email']; // Mengambil email dari data POST yang dikirimkan
    $username = $_POST['username']; // Mengambil username dari data POST yang dikirimkan
    $password = $_POST['password']; // Mengambil password dari data POST yang dikirimkan
    $confirm_password = $_POST['confirm-password']; // Mengambil konfirmasi password dari data POST yang dikirimkan

    // Memeriksa apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        echo "Passwords do not match!"; // Menampilkan pesan kesalahan jika password tidak cocok
        exit(); // Menghentikan eksekusi script jika password tidak cocok
    }

    // Menghash password dengan algoritma hashing default
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Menyiapkan statement SQL untuk memasukkan data pengguna ke tabel user
    $stmt = $conn->prepare("INSERT INTO user (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $username, $hashed_password); // Mengikat parameter email, username, dan hashed password ke statement SQL

    // Menjalankan statement SQL dan memeriksa hasilnya
    if ($stmt->execute()) {
        // Jika berhasil, mengarahkan pengguna ke halaman utama
        header("Location: index.php");
    } else {
        // Menampilkan pesan kesalahan jika terjadi error saat eksekusi statement
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Menutup statement untuk membebaskan sumber daya
    $conn->close(); // Menutup koneksi database untuk membebaskan sumber daya
}
?>
