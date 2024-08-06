// Menambahkan event listener ke elemen dengan ID 'toggle-link'
document.getElementById('toggle-link').addEventListener('click', function (e) {
    e.preventDefault(); // Mencegah tindakan default dari tautan (link) agar tidak mengikuti href-nya

    // Mendapatkan elemen-elemen yang akan dimodifikasi
    var formTitle = document.getElementById('form-title'); // Mengambil elemen dengan ID 'form-title'
    var toggleMessage = document.getElementById('toggle-message'); // Mengambil elemen dengan ID 'toggle-message'
    var submitButton = document.getElementById('submit-button'); // Mengambil elemen dengan ID 'submit-button'
    var confirmPasswordGroup = document.getElementById('confirm-password-group'); // Mengambil elemen dengan ID 'confirm-password-group'

    // Memeriksa teks saat ini dari elemen 'formTitle'
    if (formTitle.textContent === 'Register') {
        // Jika teks saat ini adalah 'Register', lakukan perubahan berikut
        formTitle.textContent = 'Register'; // Mengatur teks judul formulir ke 'Register'
        toggleMessage.innerHTML = 'Sudah punya akun? <a href="#" id="toggle-link">Login</a>'; // Mengubah pesan toggle untuk menunjukkan tautan ke halaman Login
        submitButton.textContent = 'Register'; // Mengatur teks tombol kirim menjadi 'Register'
        confirmPasswordGroup.style.display = 'block'; // Menampilkan grup konfirmasi password
        document.getElementById('form').action = 'register.php'; // Mengubah atribut action dari formulir menjadi 'register.php'
    } else {
        // Jika teks saat ini bukan 'Register', lakukan perubahan berikut
        formTitle.textContent = 'Login'; // Mengatur teks judul formulir ke 'Login'
        toggleMessage.innerHTML = 'Belum punya akun? <a href="#" id="toggle-link">Register</a>'; // Mengubah pesan toggle untuk menunjukkan tautan ke halaman Register
        submitButton.textContent = 'Login'; // Mengatur teks tombol kirim menjadi 'Login'
        confirmPasswordGroup.style.display = 'none'; // Menyembunyikan grup konfirmasi password
        document.getElementById('form').action = 'login.php'; // Mengubah atribut action dari formulir menjadi 'login.php'
    }
});
