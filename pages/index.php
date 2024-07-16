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

$sql = "SELECT * FROM ruv_table";
$result = $connect->query($sql);

mysqli_close($connect); // Close connection after use
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
                <div class="flex text-white">
                    <div class="text-5xl mt-3 mb-3 font-bold">
                        Welcome, <?php echo htmlspecialchars($full_name); ?>!
                    </div>
                </div>
            </div>
        </div>
            <div class="w3-container flex static ml-56" style="color: white;">
                    <div class="p-6 rounded-lg shadow-lg text-black w-full max-w-7xl m-2">
                        <h2 class="text-2xl font-bold mb-6">Pending RUV requests</h2>
                            <p>NOTE: You can only accept or disapprove requests that are on the top of the list</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-gray-800 text-white">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2 border">RUV No</th>
                                                <th class="px-4 py-2 border">Pick-up Point</th>
                                                <th class="px-4 py-2 border">Destination</th>
                                                <th class="px-4 py-2 border">Date Submitted</th>
                                                <th class="px-4 py-2 border">Details</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Check if status is not "Approved"
                                                        if ($row['status'] !== 'Approved') {
                                                            echo "<tr>";
                                                            echo "<td class='border px-4 py-2'>" . $row['ruvNO'] . "</td>";
                                                            echo "<td class='border px-4 py-2'>" . $row['pickup_point'] . "</td>";
                                                            echo "<td class='border px-4 py-2'>" . $row['destination'] . "</td>";
                                                            echo "<td class='border px-4 py-2'>" . $row['submitted'] . "</td>";
                                                            echo "<td class='border px-4 py-2'><button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#detailsModal' data-ruvno='" . $row['ruvNO'] . "'>Details</button></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4' class='border px-4 py-2'>No pending requests found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                    </table>
                                </div>
                    </div>

                    <?php
                        include '../connection.php';
                        // Count the number of available drivers in the drivers table
                        $driverCountSql = "SELECT COUNT(*) AS count FROM drivers WHERE driver_status = 'Available'";
                        $driverCountStmt = $connect->prepare($driverCountSql);
                        $driverCountStmt->execute();
                        $driverCountResult = $driverCountStmt->get_result();
                        $driverCountRow = $driverCountResult->fetch_assoc();
                        $driverCount = $driverCountRow['count'];

                            // Count the number of available vehicles in the vehicles table
                            $vehicleCountSql = "SELECT COUNT(*) AS count FROM vehicle_data WHERE car_status = 'Available'";
                            $vehicleCountStmt = $connect->prepare($vehicleCountSql);
                            $vehicleCountStmt->execute();
                            $vehicleCountResult = $vehicleCountStmt->get_result();
                            $vehicleCountRow = $vehicleCountResult->fetch_assoc();
                            $vehicleCount = $vehicleCountRow['count'];

                                // Count the number of available vehicles in the vehicles table
                                $reportCountSql = "SELECT COUNT(*) AS count FROM mrot";
                                $reportCountStmt = $connect->prepare($reportCountSql);
                                $reportCountStmt->execute();
                                $reportCountResult = $reportCountStmt->get_result();
                                $reportCountRow = $reportCountResult->fetch_assoc();
                                $reportCount = $reportCountRow['count'];                        

                                    echo "<div class='flex flex-col m-2'>";
                                        echo "<div class='bg-gray-800 rounded-md p-4 mb-4 m-2'>";
                                        echo "<p class='text-lg font-semibold text-white'>Available Drivers" . ": <span class='text-warning'>" . $driverCount . "</span></p>";
                                        echo "<a href='driver.php' class='btn btn-primary mt-auto'>Check it here</a>";
                                        echo "</div>";

                                            echo "<div class='bg-gray-800 rounded-md p-4 mb-4 m-2'>";
                                            echo "<p class='text-lg font-semibold text-white'>Available Vehicles" . ": <span class='text-warning'>" . $vehicleCount . "</span></p>";
                                            echo "<a href='vehicle.php' class='btn btn-primary mt-auto'>Check it here</a>";
                                            echo "</div>";

                                                echo "<div class='bg-gray-800 rounded-md p-4 mb-4 m-2'>";
                                                echo "<p class='text-lg font-semibold text-white'>Reports" . ": <span class='text-warning'>" . $reportCount . "</span></p>";
                                                echo "<a href='reports.php' class='btn btn-primary mt-auto'>Check it here</a>";
                                                echo "</div>";

                                    echo "</div>";
                        ?>
                        
                            <!-- Details Modal -->
                            <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-black" id="detailsModalLabel">RUV Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-black ">
                                            <!-- Details will be loaded here via JavaScript -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" id="disapproveButton">Disapprove</button>
                                            <button type="button" class="btn btn-success" id="approveButton">Approve</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
          </div>                                     
                    <script>
                        document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
                            button.addEventListener('click', () => {
                                const ruvNo = button.getAttribute('data-ruvno');
                                
                                // Fetch RUV details for the specific ruvNO
                                fetch(`../ruv_details.php?ruvNO=${ruvNo}`)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.text();
                                    })
                                    .then(data => {
                                        document.querySelector('#detailsModal .modal-body').innerHTML = data;
                                        
                                        // Check if the current row is the first row in the table
                                        const isFirstRow = button.closest('tr') === document.querySelector('tbody tr:first-child');

                                        // Set up approve and disapprove button actions
                                        const approveButton = document.getElementById('approveButton');
                                        const disapproveButton = document.getElementById('disapproveButton');

                                        if (isFirstRow) {
                                            // Enable buttons and set click actions
                                            approveButton.disabled = false;
                                            disapproveButton.disabled = false;

                                            approveButton.onclick = () => {
                                                fetch(`../assign.php?ruvNO=${ruvNo}`)
                                                    .then(response => response.text())
                                                    .then(data => {
                                                        return fetch(`../mailer.php?ruvNO=${ruvNo}`);
                                                    })
                                                    .then(response => response.text())
                                                    .then(data => {
                                                        alert('Assignment and Email Sent Successfully!'); // Display alert based on response from mailer.php
                                                        // navigate to a new location after both requests complete
                                                        window.location.reload();
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                    });
                                            };

                                            disapproveButton.onclick = () => {
                                                fetch(`../delete_ruv.php?ruvNO=${ruvNo}`)
                                                    .then(response => {
                                                        if (!response.ok) {
                                                            throw new Error('Network response was not ok');
                                                        }
                                                        return response.text();
                                                    })
                                                    .then(deleteResponse => {
                                                        alert('RUV Disapproved.');
                                                        window.location.reload();
                                                    })
                                                    .catch(error => {
                                                        console.error('Error:', error);
                                                        alert('Failed to delete RUV.');
                                                    });
                                            };
                                        } else {
                                            // Disable buttons if not the first row
                                            approveButton.disabled = true;
                                            disapproveButton.disabled = true;

                                            // Clear onclick actions
                                            approveButton.onclick = null;
                                            disapproveButton.onclick = null;
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Failed to fetch RUV details.');
                                    });
                            });
                        });
                    </script>
    </div>
</body>
</html>