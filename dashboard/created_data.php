<?php
include('../db.php');

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

    for ($i = 0; $i < $messageLength; $i++) {
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

    // Mengembalikan path gambar yang telah disisipkan pesan
    return ["status" => "success", "imagePath" => $outputImagePath];
}

// Mengecek apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image']['tmp_name'])) {
    $tempName = $_FILES['image']['tmp_name'];
    $filename = $_FILES['image']['name'];
    $imagePath = 'uploads/' . basename($filename);
    // Memindahkan file gambar yang diupload ke direktori 'uploads'
    move_uploaded_file($tempName, $imagePath);

    // Menyisipkan pesan ke dalam gambar
    $message = $_POST['message'];
    $embedResult = embedLSB($imagePath, $message);

    if ($embedResult['status'] == "success") {
        // Simpan pesan dan panjang pesan ke database
        $length = strlen($message);
        $stmt = $conn->prepare("INSERT INTO message (content, length) VALUES (?, ?)");
        if ($stmt === false) {
            die("Error: " . $conn->error);
        }
        $stmt->bind_param("si", $message, $length);

        if ($stmt->execute()) {
            // Dapatkan messageId yang baru saja di-generate
            $messageId = mysqli_insert_id($conn);

            // Simpan nama file gambar dan messageId ke dalam tabel image
            $stmt2 = $conn->prepare("INSERT INTO image (fileName, messageId) VALUES (?, ?)");
            if ($stmt2 === false) {
                die("Error: " . $conn->error);
            }
            $stmt2->bind_param("si", $filename, $messageId);

            if ($stmt2->execute()) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Berhasil input message dan gambar sudah diencode.",
                    "imagePath" => $embedResult['imagePath']
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Error: " . $stmt2->error
                ]);
            }

            $stmt2->close();
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error: " . $stmt->error
            ]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode($embedResult);
    }
} else {
    // Jika permintaan tidak valid, kirim pesan error dalam format JSON
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
