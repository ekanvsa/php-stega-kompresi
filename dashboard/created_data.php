<?php
// Menghubungkan ke file database dan memulai sesi
include('../db.php');
session_start();

// Memeriksa apakah user sudah login, jika tidak, diarahkan ke halaman login
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
    exit();
}
// Menyimpan user ID dari sesi ke variabel $id
$id = $_SESSION['userId'];

// Mengatur buffer output agar tidak dikompresi
ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', false);
ini_set('implicit_flush', true);
ob_implicit_flush(true);

// Mendefinisikan kelas MyClass
class MyClass
{
    // Mendefinisikan properti MAX_BIT_LENGTH
    private $MAX_BIT_LENGTH;

    // Konstruktor untuk menginisialisasi MAX_BIT_LENGTH
    public function __construct($maxBitLength)
    {
        $this->MAX_BIT_LENGTH = $maxBitLength;
    }

    // Mengonversi panjang ke biner dengan panjang bit tertentu
    private function i2bin($length, $maxBitLength)
    {
        return str_pad(decbin($length), $maxBitLength, '0', STR_PAD_LEFT);
    }

    // Menampilkan representasi biner
    private function put_bits($bits)
    {
        echo "Binary representation: $bits\n";
    }

    // Memproses teks untuk mengonversi panjang teks ke biner dan menampilkannya
    public function processText($text)
    {
        $text_length = $this->i2bin(strlen($text), $this->MAX_BIT_LENGTH);
        $this->put_bits($text_length);
    }
}

// Fungsi untuk mengonversi pesan menjadi biner
function messageToBinary($message)
{
    $binaryMessage = '';
    $messageLength = strlen($message);

    // Mengonversi setiap karakter menjadi biner 8-bit
    for ($i = 0; $i < $messageLength; $i++) {
        $text = str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
        $binaryMessage .= $text;
    }

    // Menambahkan terminator biner '11111111' di akhir pesan
    $binaryMessage .= '11111111';

    return $binaryMessage;
}

// Fungsi untuk menghasilkan nomor acak dengan panjang tertentu
function generateRandomNumber($length = 6)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomNumber = '';

    // Membuat nomor acak dengan memilih karakter acak dari string characters
    for ($i = 0; $i < $length; $i++) {
        $randomNumber .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomNumber;
}

// Fungsi untuk menyembunyikan pesan dalam gambar menggunakan teknik LSB
function embedLSB($imagePath, $message)
{
    // Membuka gambar dari file
    $image = imagecreatefrompng($imagePath);

    if (!$image) {
        return ["status" => "error", "message" => "Gagal membuka gambar."];
    }

    // Menambahkan karakter null di akhir pesan dan mengonversi pesan menjadi biner
    $message .= chr(0);
    $messageBinary = '';
    for ($i = 0; $i < strlen($message); $i++) {
        $charBinary = str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
        $messageBinary .= $charBinary;
    }

    $messageLength = strlen($messageBinary);

    // Mendapatkan ukuran gambar
    $width = imagesx($image);
    $height = imagesy($image);
    $index = 0;
    $steps = [];

    // Menyembunyikan pesan ke dalam piksel gambar
    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($index < $messageLength) {
                $rgb = imagecolorat($image, $x, $y);
                $colors = imagecolorsforindex($image, $rgb);

                // Mengganti bit terakhir dari komponen biru dengan bit pesan
                $blue = ($colors['blue'] & 0xFE) | $messageBinary[$index];
                $color = imagecolorallocate($image, $colors['red'], $colors['green'], $blue);

                // Menyimpan warna baru ke dalam gambar
                imagesetpixel($image, $x, $y, $color);
                $index++;

                // Menyimpan langkah-langkah yang dilakukan dalam proses penyembunyian pesan
                $steps[] = [
                    "pixel" => ["x" => $x, "y" => $y],
                    "colors" => $colors,
                    "binary_message" => $messageBinary,
                    "index" => $index,
                ];
            }
        }
    }

    // Membuat nama file acak untuk gambar yang telah diproses
    $randomNumber = generateRandomNumber();
    $filename = 'hasil' . $randomNumber . '.png';
    $outputImagePath = 'hasil/' . $filename;
    // Menyimpan gambar yang telah diencode dengan pesan
    imagepng($image, $outputImagePath);
    imagedestroy($image);

    // Mengembalikan status dan path gambar yang telah diencode
    return ["status" => "success", "imagePath" => $outputImagePath, "steps" => $steps, "filename" => $filename];
}

// Memeriksa apakah metode request adalah POST dan ada file gambar yang diupload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image']['tmp_name'])) {
    // Menyimpan file gambar yang diupload
    $tempName = $_FILES['image']['tmp_name'];
    $randomDigit = generateRandomNumber();
    $filename = $randomDigit . $_FILES['image']['name'];
    $imagePath = 'uploads/' . basename($filename);
    move_uploaded_file($tempName, $imagePath);

    // Mendapatkan pesan dari input POST dan memanggil fungsi embedLSB untuk menyembunyikan pesan dalam gambar
    $message = $_POST['message'];
    $embedResult = embedLSB($imagePath, $message);

    // Memasukkan data pesan dan gambar ke dalam database jika proses penyembunyian berhasil
    if ($embedResult['status'] == "success") {
        $length = strlen($message);
        $stmt = $conn->prepare("INSERT INTO message (content, length) VALUES (?, ?)");
        if ($stmt === false) {
            die(json_encode(["status" => "error", "message" => "Error: " . $conn->error]));
        }
        $stmt->bind_param("si", $message, $length);

        if ($stmt->execute()) {
            $messageId = mysqli_insert_id($conn);

            $stmt2 = $conn->prepare("INSERT INTO image (fileNameAsli, messageId, fileNameHasil, userId) VALUES (?, ?, ?, ?)");
            if ($stmt2 === false) {
                die(json_encode(["status" => "error", "message" => "Error: " . $conn->error]));
            }
            $stmt2->bind_param("siss", $filename, $messageId, $embedResult['filename'], $id);

            if ($stmt2->execute()) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Berhasil input message dan gambar sudah diencode.",
                    "imagePath" => $embedResult['imagePath'],
                    "steps" => $embedResult['steps']
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
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
