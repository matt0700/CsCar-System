<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
  <!-- MDB -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css">
  <!-- Tailwind CSS (for eye icon animation) -->
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
  </style>
</head>
<body>

  <div class="flex items-center content-center">
  <img class="mx-24 w-50 h-50" src="logo-min.png">

<div class=" flex-col login-container">
  <form action="login-check.php" method="post" class="login-form">
    <h1 class="text-center mb-4">CSCAR LOGIN</h1>
    <?php
      // Check if logout was successful
      if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
          echo '<div class="alert alert-success text-center">Logout successful!</div>';
      }

      // Check if there is any other error message
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
    <div>
      <a href="RUV.php" class="text-black hover:underline no-underline">Schedule an RUV</a>
    </div> 
        <div>
          <a href="driverlogin.php" class="text-black hover:underline no-underline">Click here to driver login</a>
        </div>


  </form>

</div>


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
