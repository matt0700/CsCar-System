<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['driver_id'])) {
    header('Location: driver_login.php'); // Redirect if driver is not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $special_password = $_POST['special_password'];
    $driver_id = $_SESSION['driver_id']; // Fetch driver_id from session

    // Fetch the driver from the database
    $stmt = $connect->prepare("SELECT driver_id, special_password FROM drivers WHERE driver_id = ?");
    $stmt->bind_param('s', $driver_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $driver = $result->fetch_assoc();
        $hashed_password_from_db = $driver['special_password'];
    
        // Compare the entered special password with the hashed password from the database
        if (password_verify($special_password, $hashed_password_from_db)) {
            // Set session variables
            $_SESSION['user_type'] = 'driver';
            $_SESSION['driver_id'] = $driver['driver_id'];
            $_SESSION['driver_name'] = $driver['driver_name']; // Add other necessary variables
            $_SESSION['driver_status'] = $driver['driver_status'];
            
            // Redirect to driver's dashboard
            header('Location: driver/pages/index.php');
            exit();
        } else {
            header('Location: verify-special-password-driver.php?error=Invalid special password');
            exit();
        }
    } else {
        header('Location: verify-special-password-driver.php?error=Driver not found');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Special Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.1/mdb.min.css">
    <style>
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
            padding: 50px;
            width: auto;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: auto;
        }
    </style>
</head>
<body>
<div class="login-container">
    <form action="verify-special-password-driver.php" method="post" class="login-form">
        <h1 class="text-center mb-2 text-2xl"><strong>Verify Special Password</strong></h1>
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger text-center">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>
        <div class="form-group">
            <label for="special_password" class="align-center"></label>
            <input class="mb-2" type="password" id="special_password" name="special_password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Verify</button>
    </form>
</div>
</body>
</html>
