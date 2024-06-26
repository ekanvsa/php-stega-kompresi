<?php
// Mengatur pengaturan output buffering dan kompresi
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);
ini_set('implicit_flush', true);
ob_implicit_flush(true);

class MyClass {
    private $MAX_BIT_LENGTH;

    public function __construct($maxBitLength) {
        $this->MAX_BIT_LENGTH = $maxBitLength;
    }

    private function i2bin($length, $maxBitLength) {
        return str_pad(decbin($length), $maxBitLength, '0', STR_PAD_LEFT);
    }

    private function put_bits($bits) {
        // Implementasi sederhana untuk menampilkan bits
        echo "Binary representation: $bits\n";
    }

    public function processText($text) {
        $text_length = $this->i2bin(strlen($text), $this->MAX_BIT_LENGTH);
        $this->put_bits($text_length);
    }
}


// Fungsi untuk mengubah pesan menjadi biner
function messageToBinary($message) {
    $binaryMessage = '';
    $messageLength = strlen($message);

    // Menambahkan panjang pesan sebagai metadata (opsional)
    // $binaryMessage .= str_pad(decbin($messageLength), 32, '0', STR_PAD_LEFT);

    for ($i = 0; $i < $messageLength; $i++) {
        // Mengubah setiap karakter dalam pesan menjadi representasi biner 8-bit
        $text = str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
        $binaryMessage .= $text;
    }

    // Menambahkan End of Message (EOM) untuk menandai akhir pesan
    $binaryMessage .= '11111111'; // Contoh penambahan EOM dengan 8 bit 1

    return $binaryMessage;
}


// Fungsi untuk menyisipkan pesan ke dalam gambar menggunakan teknik LSB
function embedLSB($imagePath, $message) {
    $image = imagecreatefrompng($imagePath);

    if (!$image) {
        die("Gagal membuka gambar.");
    }

    // Konversi pesan ke biner
    $message .= chr(0); // Tambahkan null terminator di akhir pesan
    $messageBinary = '';
    for ($i = 0; $i < strlen($message); $i++) {
        $charBinary = str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
        $messageBinary .= $charBinary;
    }

    $messageLength = strlen($messageBinary);

    // Sisipkan pesan ke dalam gambar
    $width = imagesx($image);
    $height = imagesy($image);
    $index = 0;

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($index < $messageLength) {
                $rgb = imagecolorat($image, $x, $y);
                $colors = imagecolorsforindex($image, $rgb);

                // Ubah bit terakhir dari warna biru dengan bit pesan
                $blue = ($colors['blue'] & 0xFE) | $messageBinary[$index];
                $color = imagecolorallocate($image, $colors['red'], $colors['green'], $blue);

                imagesetpixel($image, $x, $y, $color);
                $index++;
            }
        }
    }

    $outputImagePath = 'uploads/hasil.png';
    imagepng($image, $outputImagePath);
    imagedestroy($image);

    // Mengirimkan respons sukses dalam format JSON
    echo json_encode(["status" => "success", "imagePath" => $outputImagePath]);
}

// Mengecek apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image']['tmp_name'])) {
    $tempName = $_FILES['image']['tmp_name'];
    $imagePath = 'uploads/' . basename($_FILES['image']['name']);
    // Memindahkan file gambar yang diupload ke direktori 'uploads'
    move_uploaded_file($tempName, $imagePath);

    // Menyisipkan pesan ke dalam gambar
    $message = $_POST['message'];
    embedLSB($imagePath, $message);
} else {
    // Jika permintaan tidak valid, kirim pesan error dalam format JSON
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
