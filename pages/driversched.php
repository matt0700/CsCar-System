<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../functions.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

include "../connection.php";

$sql = "SELECT t.trip_id, t.ruvNO, v.make_series_type, d.driver_name, t.trip_date
        FROM trips t
        INNER JOIN vehicle_data v ON t.plate_no = v.plate_no
        INNER JOIN drivers d ON t.driver_id = d.driver_id
        WHERE t.status = 'Pending'";

$result = $connect->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_trip']) && isset($_POST['trip_id'])) {
    $tripId = $_POST['trip_id'];

    // Update trip status
    $update_sql = "UPDATE trips SET status = 'Ongoing' WHERE trip_id = ?";
    $update_stmt = $connect->prepare($update_sql);
    $update_stmt->bind_param("i", $tripId);
    $update_stmt->execute();
    $update_stmt->close();

    // Get RUV details for email
    $ruvSql = "SELECT * FROM ruv_table WHERE ruvNO = (SELECT ruvNO FROM trips WHERE trip_id = ?)";
    $ruvStmt = $connect->prepare($ruvSql);
    $ruvStmt->bind_param("i", $tripId);
    $ruvStmt->execute();
    $ruvResult = $ruvStmt->get_result();
    $ruvData = $ruvResult->fetch_assoc();
    $ruvStmt->close();

    // Get Driver details
    $driverSql = "SELECT d.driver_name, d.driver_cellno, d.email FROM drivers d INNER JOIN trips t ON d.driver_id = t.driver_id WHERE t.trip_id = ?";
    $driverStmt = $connect->prepare($driverSql);
    $driverStmt->bind_param("i", $tripId);
    $driverStmt->execute();
    $driverResult = $driverStmt->get_result();
    $driverData = $driverResult->fetch_assoc();
    $driverStmt->close();

    // Emails and messages
    $toEmails = [
        $ruvData['email'],  // Requester email
        isset($driverData['email']) ? $driverData['email'] : ''  // Driver email
    ];

    $messages = [
        [
            'subject' => "Great News! Your Trip Request Has Been Accepted",
            'message' => "Hey there,\n\nWe are excited to inform you that your trip request has been approved! \n\n"
                        . "Here are the details of your upcoming trip:\n"
                        . "🚗 Pickup Point: " . $ruvData['pickup_point'] . "\n"
                        . "📍 Destination: " . $ruvData['destination'] . "\n"
                        . "📅 Trip Date: " . $ruvData['trip_date'] . "\n"
                        . "⏰ Preferred Time: " . $ruvData['pref_time'] . "\n\n"
                        . "Driver Information:\n"
                        . "👤 Name: " . $driverData['driver_name'] . "\n"
                        . "📞 Contact: " . $driverData['driver_cellno'] . "\n\n"
                        . "If you have any questions or need to make changes to your trip, please do not hesitate to contact us.\n\n"
                        . "Thank you for choosing our service. We look forward to serving you!\n\nBest regards,\nCSCAR"
        ],
        [
            'subject' => "Trip Confirmation Details",
            'message' => "Hello " . $driverData['driver_name'] . ",\n\n"
                        . "Your trip has been confirmed and is now ongoing.\n\n"
                        . "Here are the details:\n"
                        . "🚗 Pickup Point: " . $ruvData['pickup_point'] . "\n"
                        . "📍 Destination: " . $ruvData['destination'] . "\n"
                        . "📅 Trip Date: " . $ruvData['trip_date'] . "\n"
                        . "⏰ Preferred Time: " . $ruvData['pref_time'] . "\n\n"
                        . "If you have any questions or need further information, please let us know.\n\n"
                        . "Thank you for your cooperation.\n\nBest regards,\nCSCAR"
        ]
    ];

    sendEmail($toEmails, $messages);
}

$connect->close();
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
                    <div class="text-5xl mt-3 mb-3 font-bold">
                        Confirmed Trips
                    </div>
                 </div>
        </div>
        <div class="table-container p-10 ml-[200px]">
            <?php
            if ($result->num_rows > 0) {
                echo "<table class='table table-bordered'>";
                echo "<thead class='table-dark'>";
                echo "<tr>";
                echo "<th>TRIP ID</th>";
                echo "<th>RUV NO.</th>";
                echo "<th>VEHICLE</th>";
                echo "<th>DRIVER NAME</th>";
                echo "<th>APPROVED DATE</th>";
                echo "<th>INFORMATION</th>";
                echo "<th>ACTION</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["trip_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ruvNO"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["make_series_type"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["driver_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["trip_date"]) . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#ruvDetailsModal' data-ruvno='" . htmlspecialchars($row['ruvNO']) . "'>View RUV</button>";
                    echo "</td>";
                    echo "<td>";
                    echo "<form action='' method='post' onsubmit='return confirmTrip()'>";
                    echo "<input type='hidden' name='trip_id' value='" . htmlspecialchars($row['trip_id']) . "' />";
                    echo "<button type='submit' class='btn btn-success' name='confirm_trip' onclick='document.body.style.cursor=\"wait\";'>Confirm Trip</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p>No trips found.</p>";
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
    document.addEventListener('DOMContentLoaded', function() {
        var ruvDetailsModal = document.getElementById('ruvDetailsModal');
        ruvDetailsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var ruvNo = button.getAttribute('data-ruvno');
            var modalBody = ruvDetailsModal.querySelector('.modal-body');

            fetch('../driver/ruv_details.php?ruvNO=' + encodeURIComponent(ruvNo))
                .then(response => response.text())
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching RUV details:', error);
                });
        });
    });
    </script>
</body>
</html>