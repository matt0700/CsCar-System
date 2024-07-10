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
    <title>Main Dashboard</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/home.css">
    
    <!-- tailwind -->
<script src="https://cdn.tailwindcss.com"></script>


</head>


<style>

@media only screen and (max-width: 991px) {
  .w3-container{
  margin: 0px;
}
}


</style>



<body class="bg-white">
      <div class="w3-main ">
          <div class=" h-25 static border-none bg-slate-900">
              <button class="w3-button w3-grey w3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
                <div class="w3-container flex static ml-56" style="color: white;">
                    <div class="flex-col text-white" >
                        <div>
                            <h1>Welcome, <br><?php echo htmlspecialchars($full_name); ?>!</h1>
                        </div>

                    </div>
                 </div>
          </div>
                <div class="w3-container flex static ml-56" style="color: white;">
                   <div>
                     <div class="text-black">
                       <p>Track, manage and forecast your clients, schedules, and maintenance. </p>
                    </div>
                        <!-- <button onclick="getLocation()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Locate and Save Coordinates</button>
                            <form action="/CSCAR-System/mailer.php" method="POST" class="inline">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Send Email</button>
                            </form>
                            </div> -->

                        </div>
                    </div>
                </div>
          </div>
      </div>
  </body>

<footer>

</footer>
</html>



