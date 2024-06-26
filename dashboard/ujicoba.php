<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kompresi Gambar</title>
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
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/browser-image-compression@latest/dist/browser-image-compression.js"></script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1 class="mb-4">Kompresi Gambar</h1>
            <form id="imageForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="image" class="form-label">Pilih Gambar:</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/png" required>
                    <div id="imageError" class="text-danger"></div>
                </div>
                <button type="submit" class="btn btn-primary">Kompresi</button>
            </form>
            <div id="progressContainer" class="mt-3" style="display: none;">
                <h3>Progress Kompresi:</h3>
                <div class="progress">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div id="progressStatus" class="mt-2"></div>
            </div>
        </div>
        <div class="mt-3 text-center">
            <a href="index.html" class="btn btn-secondary">Kembali</a>
            <a class="btn btn-primary" id="downloadLink" style="display: none;">Unduh</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/image-compressor.js/2.1.0/image-compressor.min.js"></script>
    <script>$(document).ready(function () {
    $("#imageForm").on("submit", function (event) {
        event.preventDefault();
        var fileInput = document.getElementById('image');
        var file = fileInput.files[0]; // Ambil file dari input
        var fileSize = file.size;
        var fileType = file.type;

        if (fileType !== 'image/png') {
            document.getElementById('imageError').innerHTML = 'Hanya file PNG yang diperbolehkan.';
            return;
        }

        if (fileSize > 5000000) {
            document.getElementById('imageError').innerHTML = 'Ukuran gambar terlalu besar.';
            return;
        }

        var options = {
            maxSizeMB: 5,
            maxWidthOrHeight: 800,
            useWebWorker: true,
            fileType: 'image/png'
        };

        $("#progressContainer").show();
        $("#progressStatus").text("Memulai proses kompresi...");

        // Perbaikan di sini: ganti fileInput menjadi file
        imageCompression(file, options)
            .then(function (compressedFile) {
                var formData = new FormData();
                formData.append('image', compressedFile);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'compress_process.php', true);

                xhr.upload.onprogress = function (event) {
                    if (event.lengthComputable) {
                        var percentComplete = Math.round((event.loaded / event.total) * 100);
                        $("#progressBar").width(percentComplete + "%");
                        $("#progressBar").attr("aria-valuenow", percentComplete);
                        $("#progressStatus").text("Proses kompresi: " + percentComplete + "%");
                    }
                };

                xhr.onload = function () {
                    console.log(xhr.status)
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        console.log(response);
                        if (response.status === 'success') {
                            var downloadLink = document.getElementById('downloadLink');
                            downloadLink.href = response.downloadLink;
                            downloadLink.style.display = 'block';
                            $("#progressBar").width("100%");
                            $("#progressBar").attr("aria-valuenow", 100);
                            $("#progressStatus").text("Kompresi selesai!");
                        } else {
                            console.error('Error:', response);
                            $("#progressStatus").text('Terjadi kesalahan saat mengunggah gambar.');
                        }
                    } else {
                        console.error('Terjadi kesalahan saat mengunggah gambar.');
                        $("#progressStatus").text('Terjadi kesalahan saat mengunggah gambar.');
                    }
                };
                xhr.send(formData);
            })
            .catch(function (error) {
                console.error(error.message);
                $("#progressStatus").text('Terjadi kesalahan: ' + error.message);
            });
    });
});

    </script>
</body>

</html>
