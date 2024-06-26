<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decode Pesan Rahasia dari Gambar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background-color: #f4f4f4;
            min-height: 100vh;
        }

        .form-container {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            margin: 20px auto;
            max-width: 600px;
            color: #333;
        }

        .form-label {
            color: #007bff;
            font-size: 20px;
        }

        .form-control {
            font-size: 16px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 18px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .decoded-message {
            margin-top: 20px;
            font-size: 18px;
        }

        .decoded-image {
            margin-top: 20px;
            max-width: 400px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h1 class="mb-4">Decode Gambar</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="image" class="form-label">Pilih Gambar:</label>
                    <input type="file" class="form-control" id="image" name="image" accept=".png" required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Decode</button>
            </form>
        </div>
        <div class="mt-3 text-center">
            <?php
            // Fungsi untuk mengekstrak LSB dari file gambar JPG
            function extractLSB($imagePath)
            {
                $image = imagecreatefrompng($imagePath);

                if (!$image) {
                    die("Gagal membuka gambar.");
                }

                $width = imagesx($image);
                $height = imagesy($image);
                $messageBinary = '';
                $decodedMessage = '';

                for ($y = 0; $y < $height; $y++) {
                    for ($x = 0; $x < $width; $x++) {
                        $rgb = imagecolorat($image, $x, $y);
                        $colors = imagecolorsforindex($image, $rgb);

                        // Ambil bit terakhir dari warna biru
                        $messageBinary .= $colors['blue'] & 1;

                        // Setiap 8 bit, konversi ke karakter
                        if (strlen($messageBinary) == 8) {
                            $decodedMessage .= chr(bindec($messageBinary));

                            // Jika karakter null terminator ditemukan, hentikan proses decoding
                            if (substr($decodedMessage, -1) === chr(0)) {
                                $decodedMessage = substr($decodedMessage, 0, -1);
                                imagedestroy($image);
                                return $decodedMessage;
                            }

                            // Reset binary string
                            $messageBinary = '';
                        }
                    }
                }

                imagedestroy($image);
                return $decodedMessage;
            }

            // Proses ekstraksi LSB jika form disubmit
            if (isset($_POST['submit'])) {
                $imageFile = $_FILES['image']['tmp_name'];
                if ($imageFile && is_uploaded_file($imageFile)) {
                    $extractedText = extractLSB($imageFile);

                    // Menampilkan hasil ekstraksi
                    echo "<h2 class='mt-4'>Hasil Decode Pesan Rahasia:</h2>";
                    echo "<p class='decoded-message'>" . $extractedText . "</p>";

                    // Menampilkan gambar yang telah diekstrak LSB-nya
                    echo "<div class='mt-4'>";
                    echo "<h2>Gambar yang telah diekstrak LSB-nya:</h2>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode(file_get_contents($imageFile)) . "' alt='Decoded Image' class='decoded-image'>";
                    echo "</div>";

                    // Menampilkan link untuk mengunduh gambar
                    $imageName = basename($_FILES['image']['name']);
                    echo "<div class='mt-3'>";
                    echo "<a href='download.php?image=" . urlencode($imageName) . "' class='btn btn-primary'>Download Gambar</a>";
                    echo "</div>";
                } else {
                    echo "<p class='text-danger'>Gagal mengunggah gambar.</p>";
                }
            }
            ?>
        </div>
        <div class="mt-3 text-center">
            <a href="index.html" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>