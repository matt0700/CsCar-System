<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
  <!-- MDB -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css">
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f0f0;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
      position: relative;
    }

    .background-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('background.png');
      background-size: cover;
      background-position: center;
      filter: brightness(50%);
      z-index: -1;
    }

    .container {
      text-align: center;
      position: relative;
      z-index: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      margin-top: -100px; /* Adjust as needed */
    }

    .logo-container {
      margin-bottom: 20px; /* Space between logo and form */
    }

    .logo-container img {
      width: 150px; /* Adjust the size of the logo */
    }

    .login-form {
      background-color: #ffffff;
      color: #333;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      padding: 30px;
      width: 100%;
      max-width: 350px; /* Adjust the width to match your design */
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
      font-size: 16px;
    }

    .eye-icon {
      cursor: pointer;
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
      color: #fff;
      padding: 12px 20px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      width: 100%;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<!-- Background image overlay -->
<div class="background-overlay"></div>

<!-- Container for logo and form -->
<div class="container">
  <!-- Logo -->
  <div class="logo-container">
    <img src="logo-min.png" alt="Logo">
  </div>

  <!-- Login Form -->
  <div class="login-form">
    <form action="login-check.php" method="post">
      <h1 class="text-center mb-4 text-2xl font-bold">Admin Login</h1>
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
      <button type="submit" class="btn-primary">Sign in</button>

      <!-- Hyperlinks -->
      <div class="flex justify-between mt-4">
        <a href="RUV.php" class="text-black hover:underline">Schedule RUV</a>
        <a href="driverlogin.php" class="text-black hover:underline">Driver Login</a>
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
