<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'driver') {
    header("Location: ../driver_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Dashboard</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/home.css">
    
    <!-- tailwind -->
<script src="https://cdn.tailwindcss.com"></script>


</head>
<body class="bg-white">
    <div class="w3-main z-10 ">
        <div class=" text-black h-20 static border-none  ">
        <button class="w3-button w3-greyw3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
        
            <div class="w3-container flex static z-50 ml-56 " style="color: white;">

                <div class="flex-col text-black">
                    <div>
                        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['driver_name']); ?></h1>
                            <p>Your driver ID: <?php echo htmlspecialchars($_SESSION['driver_id']); ?></p>
                        </div>
                        
                            <div>
                            Track, manage and forecast your clients, schedules, and maintenance
                            </div>
                    </div>
            </div>
        </div>
    </div>


<div class="test z-50 flex justify-center ml-[200px]">

  <div class=" grid grid-cols-2 gap-5 mx-3 my-3" >
    <div class="bg-gray-300 rounded-sm min-h-[200px] min-w-[500px] border-4 border-black ">
      <div>MapBox</div>
      <div><!--MAPBOX--></div>
    </div>
    <div class="bg-gray-300 rounded-sm  border-4 border-black ">
      <div>Statistics</div>
      <div><!--MAPBOX--></div>
    </div>
    <div class="bg-gray-300 rounded-sm min-h-[200px] min-w-[500px] border-4 border-black ">
      <div>Schedules</div>
      <div><!--MAPBOX--></div>
    </div>
    <div class="bg-gray-300 rounded-sm  border-4 border-black ">
      <div>Vehicles In Use</div>
      <div><!--MAPBOX--></div>
    </div>
  

</div>

<form action="../mailer.php" method="POST">
        <button type="submit">Send Email</button>
        
    </form>


</body>

<footer>

</footer>
</html>

