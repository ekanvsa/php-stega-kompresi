<!-- <!DOCTYPE html>
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
    <?php
    phpinfo();
    ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#imageForm').on('submit', function (event) {
        event.preventDefault();
        
        var formData = new FormData(this);
        console.log(formData)
        $.ajax({
            url: 'compress_process.php', // Ganti dengan path yang sesuai
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total * 100;
                        $('#progressBar').css('width', percentComplete + '%');
                        $('#progressBar').attr('aria-valuenow', percentComplete);
                        $('#progressStatus').text(Math.round(percentComplete) + '% selesai');
                    }
                }, false);
                return xhr;
            },
            beforeSend: function () {
                $('#progressContainer').show();
                $('#progressBar').css('width', '0%');
                $('#progressBar').attr('aria-valuenow', 0);
                $('#progressStatus').text('');
                $('#imageError').text('');
            },
            success: function (response) {
                $('#progressContainer').hide();
                if (response.status === 'success') {
                    $('#downloadLink').attr('href', response.downloadLink);
                    $('#downloadLink').show();
                } else {
                    $('#imageError').text(response.message);
                }
            },
            error: function () {
                $('#progressContainer').hide();
                $('#imageError').text('Terjadi kesalahan saat mengunggah gambar.');
            }
        });
    });
});
</script>

</body>

</html> -->
