<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forget Password</title>
  <link rel="stylesheet" href="./libs/bootstrap.min.css"/>
</head>
<body class="bg-dark bg-gradient">
  <div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
      <div class="col-12">
        <div class="card rounded-0 border border-0 mx-auto" style="width: 400px;">
          <div class="card-header bg-primary rounded-0 text-white">
            <h2 class="text-center p-2">Forgot Password</h2>
          </div>
          <div class="card-body p-4">
            <form id="forgot-pass-form">
              <label for="" class="form-label">Email:</label>
              <input type="email" name="email" id="email" class="form-control mb-1" required>

              <input type="submit" id="forgot-pass-btn" value="Send Reset Password Link" class="btn btn-primary mt-2 w-100">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="./libs/bootstrap.bundle.min.js"></script>
  <script src="./libs/jquery.min.js"></script>
  <script src="./libs/sweetalert2.all.min.js"></script>
</body>
</html>