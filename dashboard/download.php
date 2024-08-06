<?php
// Periksa apakah parameter 'image' ada dalam string query URL
if(isset($_GET['image'])) {
    // Ambil jalur gambar dari parameter 'image'
    $imagePath = $_GET['image'];
    
    // Set header content type untuk menunjukkan bahwa respons adalah aliran biner
    header('Content-Type: application/octet-stream');
    
    // Set header content disposition untuk menentukan bahwa konten harus diunduh
    // Nama file dalam header akan sama dengan nilai dari parameter 'image'
    header('Content-Disposition: attachment; filename="' . $imagePath . '"');
    
    // Baca file dan tulis ke buffer output
    readfile($imagePath);
    
    // Hentikan eksekusi skrip
    exit;
}
?>
