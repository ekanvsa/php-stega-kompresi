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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 800px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            color: #333;
        }

        .form-container {
            margin-bottom: 20px;
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
            max-width: 100%;
            display: block;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .step-container {
            max-height: 300px; /* Atur tinggi maksimum untuk konten yang dapat di-scroll */
            overflow-y: auto; /* Aktifkan scroll vertikal jika konten melebihi tinggi maksimum */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 20px;
        }

        .step {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .result-container {
            text-align: center;
            margin-top: 20px;
        }

        .result-image {
            max-width: 400px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4 text-center">Decode Gambar</h1>
        <div class="form-container">
            <form action="" method="post" enctype="multipart/form-data" id="decodeForm">
                <div class="mb-3">
                    <label for="image" class="form-label">Pilih Gambar:</label>
                    <input type="file" class="form-control" id="image" name="image" accept=".png" required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Decode</button>
            </form>
        </div>

        <?php
        // Fungsi untuk mengekstrak LSB dari file gambar PNG
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

            echo "<div class='step-container'>";

            for ($y = 0; $y < $height; $y++) {
                for ($x = 0; $x < $width; $x++) {
                    $rgb = imagecolorat($image, $x, $y);
                    $colors = imagecolorsforindex($image, $rgb);

                    // Ambil bit terakhir dari warna biru
                    $bitValue = $colors['blue'] & 1;
                    $messageBinary .= $bitValue;

                    // Tampilkan detail setiap langkah
                    echo "<div class='step'>";
                    echo "<p>Posisi Piksel: ($x, $y)</p>";
                    echo "<p>Nilai Biner Sebelum Ekstraksi: " . str_pad($messageBinary, 8, '0', STR_PAD_LEFT) . "</p>";

                    // Setiap 8 bit, konversi ke karakter
                    if (strlen($messageBinary) == 8) {
                        $decodedChar = chr(bindec($messageBinary));
                        $decodedMessage .= $decodedChar;

                        // Jika karakter null terminator ditemukan, hentikan proses decoding
                        if ($decodedChar === chr(0)) {
                            $decodedMessage = substr($decodedMessage, 0, -1);
                            imagedestroy($image);
                            echo "<p class='text-success mt-3'>Pesan berhasil diekstrak: " . htmlspecialchars($decodedMessage) . "</p>";
                            echo "</div>"; // tutup step
                            echo "</div>"; // tutup step-container
                            return $decodedMessage;
                        }

                        // Tampilkan karakter yang diekstrak
                        echo "<p>Karakter yang Diekstrak: $decodedChar</p>";

                        // Reset binary string
                        $messageBinary = '';
                    }

                    echo "</div>"; // tutup step
                }
            }

            echo "</div>"; // tutup step-container
            imagedestroy($image);
            return $decodedMessage;
        }

        // Proses ekstraksi LSB jika form disubmit
        if (isset($_POST['submit'])) {
            $imageFile = $_FILES['image']['tmp_name'];
            if ($imageFile && is_uploaded_file($imageFile)) {
                // Lakukan proses decode
                $extractedText = extractLSB($imageFile);

                // Menampilkan gambar yang telah diekstrak LSB-nya
                echo "<div class='result-container mt-4'>";
                echo "<h2>Gambar yang telah diekstrak LSB-nya:</h2>";
                echo "<img src='data:image/png;base64," . base64_encode(file_get_contents($imageFile)) . "' alt='Decoded Image' class='result-image'>";
                echo "</div>";
            } else {
                echo "<p class='text-danger mt-3'>Gagal mengunggah gambar.</p>";
            }
        }
        ?>

        <div class="mt-3 text-center">
            <a href="index.html" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
