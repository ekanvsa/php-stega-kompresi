<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encode Gambar</title>
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

    .step-container {
      max-height: 300px;
      overflow-y: auto;
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
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="container">
    <div class="form-container">
      <h1 class="mb-4">Encode Gambar</h1>
      <form id="encodeForm" enctype="multipart/form-data" method="post" action="created_data.php">
        <div class="mb-3">
          <label for="image" class="form-label">Pilih Gambar:</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/png" required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Pesan Rahasia:</label>
          <input type="text" class="form-control" id="message" name="message" placeholder="Masukkan pesan rahasia" required>
        </div>
        <button type="submit" class="btn btn-primary">Encode</button>
      </form>
      <div id="result" class="mt-3"></div>
      <div id="steps" class="step-container"></div>
      <div class="mt-3 text-center">
        <a href="index.html" class="btn btn-primary">Home</a>
        <a href="idxdecode.php" class="btn btn-success">Decode</a>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#encodeForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            let res = JSON.parse(response);
            if (res.status === 'success') {
              $('#result').html(`
                <div class="alert alert-success" role="alert">
                  ${res.message}
                  <br>
                  <img src="${res.imagePath}" class="img-fluid mt-3" alt="Encoded Image">
                  <br>
                  <a href="${res.imagePath}" download="encoded_image.png" class="btn btn-success mt-3">Download Gambar</a>
                </div>
              `);
              let stepsHTML = '';
              res.steps.forEach((step, index) => {
                stepsHTML += `
                  <div class="step">
                    <h5>Step ${index + 1}</h5>
                    <p>Pixel (${step.pixel.x}, ${step.pixel.y})</p>
                    <p>Colors: R: ${step.colors.red}, G: ${step.colors.green}, B: ${step.colors.blue}</p>
                    <p>Binary message: ${step.binary_message}</p>
                    <p>Index: ${step.index}</p>
                  </div>
                `;
              });
              $('#steps').html(stepsHTML);
            } else {
              $('#result').html(`<div class="alert alert-danger" role="alert">${res.message}</div>`);
            }
          },
          error: function() {
            $('#result').html(`<div class="alert alert-danger" role="alert">Terjadi kesalahan. Silakan coba lagi.</div>`);
          }
        });
      });
    });
  </script>
</body>

</html>