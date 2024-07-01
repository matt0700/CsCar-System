<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";

// Sanitize username input
$username = mysqli_real_escape_string($connect, $_SESSION['username']);

// Query to retrieve user information
$sql = "SELECT ui.Ln, ui.Fn, ui.Mn
        FROM users u
        JOIN information ui ON u.user_id = ui.user_id
        WHERE u.username = '$username'";

$result = mysqli_query($connect, $sql);

if (!$result) {
    // Handle query error
    die("Query failed: " . mysqli_error($connect));
}

// Check if user information exists
if (mysqli_num_rows($result) > 0) {
    // Fetch user information
    $row = mysqli_fetch_assoc($result);
    $last_name = $row['Ln'];
    $first_name = $row['Fn'];
    $middle_name = $row['Mn']; 

    $full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
    
} else {
    // Handle case where user information is not found
    die("User information not found.");
}

mysqli_close($connect); // Close connection after use
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Overview</title>
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
  
  <div class="w3-container flex" style="color: white;">

<div class="flex-col text-black ml-[200px]">
Vehicle Overview

<!--Search INput next na gawin-->

</div>

<div class="flex  w3-display-topright w3-margin-right mx-2 my-2 text-black z-50 ml-10">
            <div><button class="p"><img class="w-3 h-3 mr-2 " src="https://img.icons8.com/ios-filled/50/1A1A1A/appointment-reminders--v1.png"></button></div>
            <div>
            <div>Escarlet Conde</div> <!--ADMIN NAME-->
            </div>
            <div><button class=" w3-dropdown-click w3-bar-item w3-button w3-medium " onclick="w3_close()"><img class="w-3 h-3 " src="https://img.icons8.com/ios-filled/50/1A1A1A/menu--v1.png"></button></div> <!--LOG OUT-->
        </div>
</div>
</div>
</div>


<div class="test z-50   ml-[200px]">

  <div class=" grid grid-cols-3 gap-3 mx-3 my-3" >

    <div class="bg-white rounded-sm min-h-[500px] border-4 border-black ">
      <div class="ml-2">All Cars</div>
        <div>
          <div class="flex justify-between">
            <div>Name</div>
            <div>Car ID</div>
          </div>
        </div>
    </div>
 
    <div class="bg-white- rounded-sm col-span-2 max-h-[200px]  border-4 border-black ">
      <div class="ml-2">Car Information</div>
      <div class=" grid grid-cols-1 gap-3 mx-10 my-64 " >

      
        <div class="bg-white- rounded-sm min-h-[200px]  border-4 border-black ">
          <div class="ml-2">Recent Trip</div>
          <div><!--MAPBOX--></div>
        </div>
      <div><!--MAPBOX--></div>
    </div>

      

  </div>
  </div>
</div>


</body>
<footer>

</footer>
</html>



