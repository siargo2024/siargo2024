<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header('location: dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="./libs/bootstrap.min.css" />
</head>

<body class="bg-dark bg-gradient">
  <div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
      <div class="col-6">
        <div class="card rounded-0 border border-0 mx-auto" style="width: 400px;">
          <div class="card-header bg-primary rounded-0 text-white">
            <h2 class="text-center p-2">Login</h2>
          </div>
          <div class="card-body p-4">
            <form id="login-form">
              <label for="" class="form-label">Email:</label>
              <input type="email" name="email" id="email" class="form-control mb-3" required>

              <label for="" class="form-label">Password</label>
              <input type="password" name="password" id="password" class="form-control mb-1" required>

              <a href="forgot.php">Forgot Password?</a>

              <input type="submit" id="login-btn" value="Login" class="btn btn-primary mt-2 w-100">
            </form>
            <a href="register.php" class="text-center d-block mt-2">Don't have an account yet?</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="./libs/bootstrap.bundle.min.js"></script>
  <script src="./libs/jquery.min.js"></script>
  <script src="./libs/sweetalert2.all.min.js"></script>
  <script>
    $(document).ready(function() {
      // Sweetalert2 function
      function swal(icon, title, text) {
        Swal.fire({
          icon,
          title,
          text
        })
      }

      // Login Ajax Request
      $('#login-btn').click(function(e) {
        if ($('#login-form')[0].checkValidity()) {
          e.preventDefault()

          $(this).attr('disabled', true)
          $(this).val('Logging in...')

          $.ajax({
            url: 'php/action.php',
            method: 'post',
            data: $('#login-form').serialize() + '&action=login',
            success: res => {
              console.log(res)
              if (res == '1') {
                window.location = 'dashboard.php'
              } else if (res == '2') {
                swal('error', 'Invalid credentials', '')
              }

              $('#login-btn').attr('disabled', false)
              $('#login-btn').val('Login')
            }
          })
        }
      })
    })
  </script>
</body>

</html>