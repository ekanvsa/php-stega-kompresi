<?php
include('../db.php');

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
        echo "Binary representation: $bits\n";
    }

    public function processText($text) {
        $text_length = $this->i2bin(strlen($text), $this->MAX_BIT_LENGTH);
        $this->put_bits($text_length);
    }
}

function messageToBinary($message) {
    $binaryMessage = '';
    $messageLength = strlen($message);

    for ($i = 0; $i < $messageLength; $i++) {
        $text = str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
        $binaryMessage .= $text;
    }

    $binaryMessage .= '11111111';

    return $binaryMessage;
}

function embedLSB($imagePath, $message) {
    $image = imagecreatefrompng($imagePath);

    if (!$image) {
        die("Gagal membuka gambar.");
    }

    $message .= chr(0);
    $messageBinary = '';
    for ($i = 0; $i < strlen($message); $i++) {
        $charBinary = str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
        $messageBinary .= $charBinary;
    }

    $messageLength = strlen($messageBinary);

    $width = imagesx($image);
    $height = imagesy($image);
    $index = 0;
    $steps = [];

    for ($y = 0; $y < $height; $y++) {
        for ($x = 0; $x < $width; $x++) {
            if ($index < $messageLength) {
                $rgb = imagecolorat($image, $x, $y);
                $colors = imagecolorsforindex($image, $rgb);

                $blue = ($colors['blue'] & 0xFE) | $messageBinary[$index];
                $color = imagecolorallocate($image, $colors['red'], $colors['green'], $blue);

                imagesetpixel($image, $x, $y, $color);
                $index++;

                $steps[] = [
                    "pixel" => ["x" => $x, "y" => $y],
                    "colors" => $colors,
                    "binary_message" => $messageBinary,
                    "index" => $index,
                ];
            }
        }
    }

    $outputImagePath = 'uploads/hasil.png';
    imagepng($image, $outputImagePath);
    imagedestroy($image);

    return ["status" => "success", "imagePath" => $outputImagePath, "steps" => $steps];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image']['tmp_name'])) {
    $tempName = $_FILES['image']['tmp_name'];
    $filename = $_FILES['image']['name'];
    $imagePath = 'uploads/' . basename($filename);
    move_uploaded_file($tempName, $imagePath);

    $message = $_POST['message'];
    $embedResult = embedLSB($imagePath, $message);

    if ($embedResult['status'] == "success") {
        $length = strlen($message);
        $stmt = $conn->prepare("INSERT INTO message (content, length) VALUES (?, ?)");
        if ($stmt === false) {
            die("Error: " . $conn->error);
        }
        $stmt->bind_param("si", $message, $length);

        if ($stmt->execute()) {
            $messageId = mysqli_insert_id($conn);

            $stmt2 = $conn->prepare("INSERT INTO image (fileName, messageId) VALUES (?, ?)");
            if ($stmt2 === false) {
                die("Error: " . $conn->error);
            }
            $stmt2->bind_param("si", $filename, $messageId);

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
?>
