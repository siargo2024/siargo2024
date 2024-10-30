<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="./libs/bootstrap.min.css"/>
</head>
<body class="bg-dark bg-gradient">
  <div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
      <div class="col-12">
        <div class="card rounded-0 border border-0 mx-auto" style="width: 400px;">
          <div class="card-header bg-primary rounded-0 text-white">
            <h2 class="text-center p-2">Register</h2>
          </div>
          <div class="card-body p-4">
            <form id="register-form">
              <label for="" class="form-label">Email:</label>
              <input type="email" name="email" id="email" class="form-control mb-3" required>
              
              <label for="" class="form-label">Password</label>
              <input type="password" name="password" id="password" class="form-control mb-3" required>
              
              <label for="" class="form-label">Confirm Password</label>
              <input type="password" name="cpassword" id="cpassword" class="form-control mb-3" required>

              <input type="submit" id="register-btn" value="Register" class="btn btn-primary w-100">
            </form>
            <a href="index.php" class="text-center d-block mt-2">Already have an account?</a>
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
        Swal.fire({ icon, title, text })
      }

      $('#register-btn').click(function (e) {
        if ($('#register-form')[0].checkValidity()) {
          e.preventDefault()

          $(this).attr('disabled', true)
          $(this).val('Registering...')

          $.ajax({
            url: 'php/action.php',
            method: 'post',
            data: $('#register-form').serialize() + '&action=register',
            success: res => {
              // console.log(res)
              if (res == '1') {
                swal('error', 'Passwords mismatched', '')
              } else if (res == '2') {
                swal('error', 'Email exists', '')
              } else if (res == '3') {
                swal('success', 'Successful!', 'Registered successfully')
                $('#register-form')[0].reset()
              }

              $('#register-btn').attr('disabled', false)
              $('#register-btn').val('Register')
            }
          })
        }
      })

    })
  </script>
</body>
</html>