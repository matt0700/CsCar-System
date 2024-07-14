<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";

$sql = "SELECT * FROM trips";
$result = $connect->query($sql);

// Query to fetch driver details
$driverQuery = "SELECT * FROM drivers WHERE driver_id = ?";
$stmt = $connect->prepare($driverQuery);
$stmt->bind_param("i", $driverId);

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

    <div class="bg-gray-100 p-10">
        <div class="test z-50 flex justify-center ml-[200px]">
            <?php
            if ($result->num_rows > 0) {
                echo "<table class='table-auto border-collapse border border-gray-400'>";
                echo "<thead><tr class='bg-gray-200'>";
                echo "<th class='border border-gray-400 px-4 py-2'>TRIP ID</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>RUV NO.</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>PLATE NO.</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>DRIVER ID</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>TRIP DATE</th>";
                echo "<th class='border border-gray-400 px-4 py-2'></th>";
                echo "<th class='border border-gray-400 px-4 py-2'></th>";
                echo "</tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . $row["trip_id"] . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . $row["ruvNO"] . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . $row["plate_no"] . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . $row["driver_id"] . "</td>";
                    echo "<td class='border border-gray-400 px-4 py-2'>" . $row["trip_date"] . "</td>";
                    // Button to view driver details in modal
                    echo "<td class='border border-gray-400 px-4 py-2'><button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#driverDetailsModal' data-driverid='" . $row['driver_id'] . "'>View Driver</button></td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p class='text-gray-500'>0 results</p>";
            }
            $connect->close();
            ?>
        </div>
    </div>

    <!-- Driver Details Modal -->
    <div class="modal fade" id="driverDetailsModal" tabindex="-1" aria-labelledby="driverDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="driverDetailsModalLabel">Driver Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Driver details will be loaded here via JavaScript -->
                </div>
                <form id="emailForm" enctype="multipart/form-data">
                    <input type="file" name="attachment" id="attachment" accept=".pdf" required>
                    <button type="button" class="btn btn-success" onclick="sendEmail()">Send Email</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        
        function sendEmail() {
        const form = document.getElementById('emailForm');
        const formData = new FormData(form);

        fetch('../send_email.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
        document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
            button.addEventListener('click', () => {
                const driverId = button.getAttribute('data-driverid');
                fetchDriverDetails(driverId);
            });
        });

        function fetchDriverDetails(driverId) {
            const modalBody = document.querySelector('#driverDetailsModal .modal-body');
            const url = `../driver_details.php?driverId=${driverId}`;

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
                    modalBody.innerHTML = '<p>Error fetching driver details.</p>';
                });
        }
    </script>

</body>
</html>
