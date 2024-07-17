<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['driver_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include "../connection.php";

// Get the logged-in driver's ID
$driverId = $_SESSION['driver_id'];

// Query to fetch trips for the logged-in driver
$sql = "SELECT * FROM trips WHERE driver_id = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $driverId);
$stmt->execute();
$result = $stmt->get_result();


    // Check if the form has been submitted (Accept Trip button clicked)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_trip']) && isset($_POST['ruvNO'])) {
        // Update the status of the trip to 'ongoing'
        $update_sql = "UPDATE trips SET status = 'ongoing' WHERE ruvNO = ?";
        $update_stmt = $connect->prepare($update_sql);
        $update_stmt->bind_param("i", $_POST['ruvNO']);
        $update_stmt->execute();
        $update_stmt->close();

        // Optionally, you can perform other actions after updating the status
        // For example, you may want to send notifications or log the acceptance of the trip
        echo "<p>Trip accepted successfully.</p>";
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

    <style>
        .modal-body p {
            margin-bottom: 5px;
        }
    </style>

</head>
<body class="bg-white">
    <div class="w3-main z-10">
        <div class="text-black h-20 static border-none">
            <button class="w3-button w3-greyw3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
            <div class="w3-container flex static z-50 ml-56" style="color: white;">
                <div class="flex-col text-black">
                    <div>
                        <h1>Approved Trips</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class=" p-10">
    <div class="flex justify-center ml-[200px]">
        <?php       
        include '../connection.php';

        // Query to fetch trips excluding 'ongoing'
        $sql = "SELECT * FROM trips WHERE driver_id = ? AND status != 'ongoing' AND status != 'done' AND status != 'denied'";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $_SESSION['driver_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table class='table-auto border-collapse border border-gray-400'>";
            echo "<thead>";
            echo "<tr class='bg-gray-200'>";
            echo "<th class='border border-gray-400 px-4 py-2'>TRIP ID</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>RUV NO.</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>PLATE NO.</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>DRIVER ID</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>APPROVED DATE</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>INFORMATION</th>";
            echo "<th class='border border-gray-400 px-4 py-2'></th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                // Check if the trip status is 'ongoing'; if yes, skip displaying it
                if ($row['status'] == 'ongoing') {
                    continue;
                }
                echo "<tr>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["trip_id"] . "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["ruvNO"] . "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["plate_no"] . "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["driver_id"] . "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["trip_date"] . "</td>";
                // Button to view ruv details in modal
                echo "<td class='border border-gray-400 px-4 py-2'>";
                echo "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#ruvDetailsModal' data-ruvno='" . $row['ruvNO'] . "'>View RUV</button>";
                echo "</td>";
                // Button to accept trip
                echo "<td class='border border-gray-400 px-4 py-2'>";
                echo "<form action='../accept_trip.php' method='post' onsubmit='return confirmTrip()'>";
                echo "<input type='hidden' name='trip_id' value='" . $row['trip_id'] . "' />";
                echo "<input type='hidden' name='confirm_accept' value='yes' />";
                echo "<button type='submit' class='btn btn-primary '>Accept Trip</button>";

                echo "<input type='hidden' name='deny_reason' value=''>";
                echo "<button type='button' class='btn btn-danger' onclick='denyTrip()'>Deny Trip</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p class='text-gray-500'>0 results</p>";
        }

        $stmt->close();
        $connect->close();
        ?>
    </div>
</div>


    <!-- RUV Details Modal -->
    <div class="modal fade" id="ruvDetailsModal" tabindex="-1" aria-labelledby="ruvDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ruvDetailsModalLabel">RUV Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- RUV details will be loaded here via JavaScript -->
                </div>
                <div class="modal-footer">
            </div>
            </div>
        </div>
    </div>

    <script>


        function confirmTrip() {
            return confirm("Are you sure you want to confirm this trip?");
        }
        
        function denyTrip() {
            var reason = prompt("Please enter the reason for denying the trip:");
            if (reason) {
                document.getElementsByName('confirm_accept')[0].value = 'no';
                document.getElementsByName('deny_reason')[0].value = reason;
                document.forms[0].submit();
            } else {
                alert('You must provide a reason for denying the trip.');
            }
        }

        document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
            button.addEventListener('click', () => {
                const ruvNO = button.getAttribute('data-ruvno');
                fetchRuvDetails(ruvNO);
            });
        });

        function fetchRuvDetails(ruvNO) {
            const modalBody = document.querySelector('#ruvDetailsModal .modal-body');
            const url = `../ruv_details.php?ruvNO=${ruvNO}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<p>Error fetching RUV details.</p>';
                });
        }


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

</body>
</html>
