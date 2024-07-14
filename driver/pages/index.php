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

<!-- Geolocation and AJAX script -->
<!-- <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Send coordinates to PHP script
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../update_coordinates.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert("Coordinates saved successfully.");
                }
            };
            xhr.send("lat=" + lat + "&lng=" + lng);
        }
    </script> -->
    <script>
        function updateLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(sendLocationToServer, showError);
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        }

        function sendLocationToServer(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../update_coordinates.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };
            xhr.send("lat=" + lat + "&lng=" + lng);
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    console.error("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    console.error("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    console.error("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    console.error("An unknown error occurred.");
                    break;
            }
        }

        // Update location every 30 seconds
        setInterval(updateLocation, 30000);
    </script>

</head>

<style>
@media only screen and (max-width: 768px) {
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
                            <h1>Welcome, <br><?php echo htmlspecialchars($_SESSION['driver_name']); ?></h1>
                            <p>Your driver ID: <?php echo htmlspecialchars($_SESSION['driver_id']); ?></p>
                        </div>
                    </div>
                 </div>
          </div>
          <div class="w3-container flex static ml-56" style="color: white;">
                                <div>
                                <div class="text-black">
                                  <p>Track, manage and forecast your clients, schedules, and maintenance. </p>
                                  </div>



                            <div class="flex items-center mt-3 mr-4">
                                <form action="../update_status.php" method="post" class="ml-2">
                                <label for="status" class="mr-2 text-black">Status:</label>
                                    <select name="status" onchange="this.form.submit()" class="text-black border border-gray-300 rounded-md px-2 py-1 m-2 focus:outline-none focus:border-blue-500">
                                        <option value="Available" <?php echo ($_SESSION['driver_status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="Unavailable" <?php echo ($_SESSION['driver_status'] == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                                    </select>
                                    <input type="hidden" name="driver_id" value="<?php echo htmlspecialchars($_SESSION['driver_id']); ?>">
                                </form>
</div>
<div>
<button onclick="getLocation()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Locate and Save Coordinates</button>
                                    <form action="/CSCAR-System/mailer.php" method="POST" class="inline">
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Send Email</button>
                                    </form>
                                </div> 
</body>
<script>
    // Toggle dropdown menu
    document.getElementById('menu-button').addEventListener('click', function() {
        const dropdown = document.querySelector('.origin-top-right');
        dropdown.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        const button = document.getElementById('menu-button');
        const dropdown = document.querySelector('.origin-top-right');
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
<footer>

</footer>
</html>

