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

    $full_name = "Escarlet R. Conde"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules</title>
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
        <div class="bg-slate-900 text-white h-20 static border-none">
            <button class="w3-button w3-grey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
            <div class="w3-container flex static z-50 ml-56" style="color: white;">
                <div class="flex-col">
                    <div class="text-5xl mt-3 mb-3 font-bold">
                        Approved Trips
                    </div>
                    <div class="flex-col w3-display-topright w3-margin-right mx-2 my-2 z-50 ml-10">
                    
                    <div class="mr-4 mt-2">
                        <?php echo $full_name; ?>
                    </div>
                    <div class="mr-4">
                        Admin
                    </div>
                    
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class=" p-10">
        <div class="test z-50 flex justify-center ml-[200px]">
            <?php
            if ($result->num_rows > 0) {
                echo "<table class='table-auto border-collapse border border-gray-400 text-center'>";
                echo "<thead><tr class='bg-gray-800 text-white'>";
                echo "<th class='border border-gray-400 px-4 py-2'>TRIP ID</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>RUV NO.</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>PLATE NO.</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>DRIVER ID</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>TRIP DATE</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>RUV</th>";
                echo "<th class='border border-gray-400 px-4 py-2'>TRIP TICKET</th>";
                echo "<th class='border border-gray-400 px-4 py-2'></th>";
                echo "</tr></thead><tbody>";

                while ($row = $result->fetch_assoc()) {
                    $trip_id = $row["trip_id"];
                    $ruvNO = $row["ruvNO"];
                    $plate_no = $row["plate_no"];
                    $driver_id = $row["driver_id"];
                    $trip_date = $row["trip_date"];
                    $status = $row["status"]; // Assuming 'status' is a column in your database table

                    // Check if the trip status is not 'done' or 'ongoing'
                    if ($status != 'done' && $status != 'ongoing') {
                        // Assuming ruvpdf.php generates a PDF and returns a URL to the file
                        $file_url = generateRuvPdf($trip_id); // Update this function to match your actual implementation
                        $trip_url = generateTripPdf($trip_id);    

                        echo "<tr>";
                        echo "<td class='border border-gray-400 px-4 py-2'>$trip_id</td>";
                        echo "<td class='border border-gray-400 px-4 py-2'>$ruvNO</td>";
                        echo "<td class='border border-gray-400 px-4 py-2'>$plate_no</td>";
                        echo "<td class='border border-gray-400 px-4 py-2'>$driver_id</td>";
                        echo "<td class='border border-gray-400 px-4 py-2'>$trip_date</td>";
                        echo "<td class='border border-gray-400 px-4 py-2'><a href='$file_url' target='_blank'>Download File</a></td>";
                        echo "<td class='border border-gray-400 px-4 py-2'><a href='$trip_url' target='_blank'>Download File</a></td>";
                        echo "<td class='border border-gray-400 px-4 py-2'><button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#driverDetailsModal' data-driverid='$driver_id'>View Driver</button></td>";
                        echo "</tr>";
                    }
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
                <div class="modal-body">
                <form id="emailForm" enctype="multipart/form-data" class="d-flex  flex-column">
                    <input type="hidden" name="driverId" id="driverId">
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter recipient's email" required>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" name="attachment1" id="attachment1" accept=".pdf" required>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" name="attachment2" id="attachment2" accept=".pdf">
                    </div>
                    <div class="mb-3">
                        <button type="button" onclick="sendEmail();" class="btn btn-success">Send Email</button>
                    </div>
                </form>
                </div>
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
                alert('Email Sent!'); // Display alert based on response from send_email.php

                // Optionally, refresh the page or perform other actions
                window.location.reload();
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

    
    <?php
// Function to generate PDF and return the file URL
function generateRuvPdf($trip_id) {
    // Your logic to generate the PDF and save it to a file
    // Return the URL to the generated PDF file
    return "../ruvappend.php?trip_id=$trip_id";
}

// Function to generate PDF and return the file URL
function generateTripPdf($trip_id) {
    // Your logic to generate the PDF and save it to a file
    // Return the URL to the generated PDF file
    return "../ticketappend.php?trip_id=$trip_id";
}
?>

</body>
</html>
