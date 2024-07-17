<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Driver Login</title>
  <!-- Include necessary stylesheets and scripts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f0f0;
      font-family: 'Roboto', sans-serif;
    }

    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-form {
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 300px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .eye-icon {
      cursor: pointer;
    }

    @media only screen and (max-width: 600px) {
      body {
        background-color: lightblue;
      }
    }
  </style>
</head>
<body>
  

<img class="absolute top-0 left-0 right-0 bottom-0 m-auto w-2/4 z-0" src="logo-min.png" alt="Logo">
<div class="login-container">
  <form action="driver_login_check.php" method="post" class="login-form z-1 m-auto">
  <h1 class="text-center mb-4 text-2xl"><strong>Driver Login</strong></h1>
    <?php
      if (isset($_GET['error'])) {
          echo '<div class="alert alert-danger text-center">' . htmlspecialchars($_GET['error']) . '</div>';
      }
    ?>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <div class="relative">
        <input type="password" id="password" name="password" required>
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 eye-icon" id="eyeIcon">
          <i class="fas fa-eye"></i>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Sign in</button>

          <!-- HYPERLINKS -->
        <div class="flex justify-between mt-4">
          <div>
            <a href="RUV.php" class="text-black hover:underline">Schedule RUV</a>
          </div>
              <div>
                <a href="login.php" class="text-black hover:underline">Admin Login</a>
              </div>
        </div> 
  </form>
</div>

<!-- Script for eye icon toggle -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    eyeIcon.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      eyeIcon.querySelector('i').classList.toggle('fa-eye-slash');
    });
  });
</script>

</body>
</html>
