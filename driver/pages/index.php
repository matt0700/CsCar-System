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
        setInterval(updateLocation, 5000);
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
                            <form action="../update_status.php" method="post" class="ml-2">
                            <label for="status" class="mr-2 text-white">Status:</label>
                            <select name="status" onchange="this.form.submit()" class="text-black border border-gray-300 rounded-md px-2 py-1 m-2 focus:outline-none focus:border-blue-500">
                                <option value="Available" <?php echo ($_SESSION['driver_status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                <option value="Unavailable" <?php echo ($_SESSION['driver_status'] == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                            </select>
                            <input type="hidden" name="driver_id" value="<?php echo htmlspecialchars($_SESSION['driver_id']); ?>">
                        </form>
                        </div>
                    </div>
                 </div>
          </div>
          
        <div class="w3-container ml-56" style="color: white;">
            <div class="flex mt-3 mr-4">
                <div class="text-black">
                    <?php
                    // Include your connection file
                    include '../connection.php';

                    // Check if the form has been submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['trip_id'])) {
                        if (isset($_POST['confirm_end']) && $_POST['confirm_end'] == 'yes') {
                            // Update the status of the trip to 'done'
                            $update_sql = "UPDATE trips SET status = 'Done' WHERE trip_id = ?";
                            $update_stmt = $connect->prepare($update_sql);
                            $update_stmt->bind_param("i", $_POST['trip_id']);
                            $update_stmt->execute();
                            $update_stmt->close();

                            // Optionally, you can perform other actions after updating the status
                            // For example, you may want to send notifications or log the end of the trip
                            echo "<p>Trip ended successfully.</p>";
                        } else {
                            echo "<p>Trip end canceled.</p>";
                        }
                    }

                        // Query to fetch ongoing trips with RUV details for the current driver
                        $sql = "SELECT t.trip_id, t.ruvNO, t.plate_no, t.driver_id, t.trip_date, r.pickup_point, r.destination, r.name_passengers, r.email
                                FROM trips t
                                INNER JOIN ruv_table r ON t.ruvNO = r.ruvNO
                                WHERE t.driver_id = ? AND t.status = 'ongoing'";
                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param("i", $_SESSION['driver_id']);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            echo "<div class=' w-96'>";
                            echo "<h1>Ongoing Trips</h1>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='bg-gray-800 text-white rounded-md p-4 mb-4 grid grid-cols-2 gap-4'>";
                                echo "<div>";
                                echo "<p class='text-lg font-semibold  text-white mb-2'>Trip ID: <span class='text-warning'>" . $row['trip_id'] . "</span></p>";
                                echo "<p><strong>RUV No:</strong> " . $row['ruvNO'] . "</p>";
                                echo "<p><strong>Plate No:</strong> " . $row['plate_no'] . "</p>";
                                echo "<p><strong>Driver ID:</strong> " . $row['driver_id'] . "</p>";
                                echo "<p><strong>Passengers:</strong> " . $row['name_passengers'] . "</p>";

                                echo "</div>";
                                echo "<div>";
                                echo "<p><strong>Trip Date:</strong> " . $row['trip_date'] . "</p>";
                                echo "<p><strong>Pick-up Point:</strong> " . $row['pickup_point'] . "</p>";
                                echo "<p><strong>Destination:</strong> " . $row['destination'] . "</p>"; 
                                echo "<p><strong>Email:</strong> " . $row['email'] . "</p>"; 
                                echo "</div>";

                                // Form to end the trip with confirmation
                                echo "<form action='../feedback_email.php' method='post' onsubmit='return confirmEndTrip()'>";
                                echo "<input type='hidden' name='trip_id' value='" . $row['trip_id'] . "' />";
                                echo "<input type='hidden'   name='email' value='". $row['email'] . "' />";
                                echo "<input type='hidden' name='confirm_end' value='yes' />";
                                echo "<button type='submit' class='bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mt-2'>End Trip</button>";
                                echo "</form>";
                                
                                echo "</div>";
                                echo "</div>";
                                

                            }
                            
                        } else {
                            echo "<h1>No ongoing trips found.</h1>";
                            echo "</div>";
                        }

                        $stmt->close();
                        $connect->close();
                        ?>

                    <script>
                        function confirmEndTrip() {
                            return confirm("Are you sure you want to end this trip?");
                        }
                    </script>
                </div>
                <div class="m-auto">
                            <?php
                            include '../connection.php';
                            // Count the number of rows for the current driver_id excluding 'ongoing' trips
                            $countSql = "SELECT COUNT(*) AS count FROM trips WHERE driver_id = ? AND status != 'ongoing' AND status != 'done'";
                            $countStmt = $connect->prepare($countSql);
                            $countStmt->bind_param("i", $_SESSION['driver_id']);
                            $countStmt->execute();
                            $countResult = $countStmt->get_result();
                            $countRow = $countResult->fetch_assoc();
                            $count = $countRow['count'];

                            echo "<div class='bg-gray-800 rounded-md p-4 mb-4'>";
                            echo "<p class='text-lg font-semibold text-white'>Total Approved Trips" . ": <span class='text-warning'>" . $count . "</span></p>";
                            echo "<div class='flex justify-center'>";
                            echo "<a href='driversched.php' class='btn btn-primary mt-auto'>Check it here</a>";
                            echo "</div>";
                            ?>

                    <div>
        </div>

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

