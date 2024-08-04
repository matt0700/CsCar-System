<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['driver_id'])) {
    header("Location: ../login.php");
    exit();
}


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
        $update_sql = "UPDATE trips SET status = 'ongoing' WHERE ruvNO = ?";
        $update_stmt = $connect->prepare($update_sql);
        $update_stmt->bind_param("i", $_POST['ruvNO']);
        $update_stmt->execute();
        $update_stmt->close();
        echo "<p>Trip accepted successfully.</p>";
    }

        // Check if the driver has any ongoing trips
        $checkOngoingSql = "SELECT COUNT(*) AS ongoing_count FROM trips WHERE status = 'ongoing' AND driver_id = ?";
        $checkOngoingStmt = $connect->prepare($checkOngoingSql);
        $checkOngoingStmt->bind_param("i", $driverId);
        $checkOngoingStmt->execute();
        $checkOngoingResult = $checkOngoingStmt->get_result();
        $ongoingCount = $checkOngoingResult->fetch_assoc()['ongoing_count'];
        $checkOngoingStmt->close();
        ?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Trips</title>
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

    @media only screen and (max-width: 992px) {
        .w3-container {
            margin: 0px !important;
        }
        .table-container {
            overflow-x: auto;
            margin: 0px;
        }
        .content {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
    }
    
</style>


</head>
<body class="bg-white">
    <div class="w3-main z-10">
        <div class=" h-25 static border-none bg-slate-900">
              <button class="w3-button w3-grey w3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
                <div class="w3-container flex static ml-56" style="color: white;">
                    <div class="text-5xl mt-3 mb-3 font-bold" >
                        <div>
                            Assigned Trips
                        </div>
                    </div>
                 </div>
        </div>
   

    <div class="p-10">
        <div class="table-container flex justify-center ml-[200px]">
            <?php
            include '../connection.php';

            // Query to fetch trips excluding 'ongoing'
            $sql = "SELECT * FROM trips WHERE driver_id = ? AND status != 'Ongoing' AND status != 'Done' AND status != 'Denied' AND status != 'Pending'";
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
                    $trip_id = $row["trip_id"];
                    $ruvNO = $row["ruvNO"];
                    $plate_no = $row["plate_no"];
                    $driver_id = $row["driver_id"];
                    $trip_date = $row["trip_date"];
                    $status = $row["status"];
                
                    echo "<tr>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . htmlspecialchars($trip_id) . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . htmlspecialchars($ruvNO) . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . htmlspecialchars($plate_no) . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . htmlspecialchars($driver_id) . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . htmlspecialchars($trip_date) . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>";
                    echo "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#ruvDetailsModal' data-ruvno='" . htmlspecialchars($row['ruvNO']) . "'>View RUV</button>";
                    echo "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>";
                    echo "<form action='../accept_trip.php' method='post' onsubmit='return confirmTrip()'>";
                    echo "<input type='hidden' name='trip_id' value='" . htmlspecialchars($row['trip_id']) . "' />";
                    echo "<input type='hidden' name='confirm_accept' value='yes' />";
                    

                    if ($ongoingCount > 0) {
                        echo "<button type='submit' class='btn btn-primary' onclick='document.body.style.cursor=\"wait\";' disabled>Accept Trip</button>";
                        echo "<span class='text-red-500 ml-2'>Ongoing trip in progress</span>";
                    } else {
                        echo "<button type='submit' class='btn btn-primary' onclick='document.body.style.cursor=\"wait\";'>Accept Trip</button>";
                    }
                
                    echo "<input type='hidden' name='deny_reason' value=''>";
                    echo "<button type='button' class='btn btn-danger' onclick='denyTrip()'>Deny Trip</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
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
                document.body.style.cursor = 'wait';
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
    const timestamp = new Date().toISOString(); // ISO format date string

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../update_coordinates.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);
        }
    };
    xhr.send("lat=" + encodeURIComponent(lat) + "&lng=" + encodeURIComponent(lng) + "&timestamp=" + encodeURIComponent(timestamp));
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

// Update location every 5 seconds
setInterval(updateLocation, 5000);
    </script>

</body>
</html>
