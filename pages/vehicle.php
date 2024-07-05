<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";

// Prepare query to retrieve user information
$stmt = $connect->prepare("SELECT ui.Ln, ui.Fn, ui.Mn
                          FROM users u
                          JOIN information ui ON u.user_id = ui.user_id
                          WHERE u.username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result_user = $stmt->get_result();

if (!$result_user) {
    // Handle query error
    die("Query failed: " . mysqli_error($connect));
}

// Check if user information exists
if ($result_user->num_rows > 0) {
    // Fetch user information
    $row_user = $result_user->fetch_assoc();
    $last_name = $row_user['Ln'];
    $first_name = $row_user['Fn'];
    $middle_name = $row_user['Mn']; 

    $full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;
    
} else {
    // Handle case where user information is not found
    die("User information not found.");
}

// Query to retrieve all rows from vehicle_data
$sql = "SELECT * FROM vehicle_data";
$result_vehicle = $connect->query($sql);

if (!$result_vehicle) {
    // Handle query error
    die("Query failed: " . mysqli_error($connect));
}

// Fetch all rows into an array
$vehicle_data = [];
while ($row_vehicle = $result_vehicle->fetch_assoc()) {
    $vehicle_data[] = $row_vehicle;
}

$connect->close(); // Close connection after use
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Overview</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/global.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-white">
    <div class="w3-main z-10">
        <div class="text-black h-20 static border-none">
            <button class="w3-button w3-grey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
            
            <div class="w3-container flex" style="color: white;">
                <div class="flex-col text-black ml-[200px]">
                    Vehicle Overview
                </div>
                <div class="flex w3-display-topright w3-margin-right mx-2 my-2 text-black z-50 ml-10">
                    <div><button class="p"><img class="w-3 h-3 mr-2" src="https://img.icons8.com/ios-filled/50/1A1A1A/appointment-reminders--v1.png"></button></div>
                    <div>
                        <?php echo $full_name; ?>
                    </div>
                    <div><button class="w3-dropdown-click w3-bar-item w3-button w3-medium" onclick="w3_close()"><img class="w-3 h-3" src="https://img.icons8.com/ios-filled/50/1A1A1A/menu--v1.png"></button></div>
                </div>
            </div>
        </div>

        <div class="test z-50 ml-[200px]">
            <div class="grid grid-cols-3 gap-3 mx-3 my-3">
                <div class="bg-white rounded-sm min-h-[500px] min-w-[200px] border-4 border-black">
                    <div class="ml-2">All Cars</div>
                    <?php foreach ($vehicle_data as $index => $vehicle): ?>
                        <div>
                            <button class="bg-blue-400" onclick="showCarData(<?php echo $index; ?>)">
                                <div><?php echo $vehicle['make_series_type']; ?></div>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bg-white rounded-sm col-span-2 max-h-[200px] border-4 border-black">
                    <div class="ml-2">Car Information</div>
                    <?php foreach ($vehicle_data as $index => $vehicle): ?>
                        <div id="car<?php echo $index + 1; ?>data" class="hidden">
                            <table class="table-auto border-collapse border border-gray-400">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="border border-gray-400 px-4 py-2">Plate No.</th>
                                        <th class="border border-gray-400 px-4 py-2">Model</th>
                                        <th class="border border-gray-400 px-4 py-2">Type</th>
                                        <th class="border border-gray-400 px-4 py-2">Make Series Type</th>
                                        <th class="border border-gray-400 px-4 py-2">Seater</th>
                                        <th class="border border-gray-400 px-4 py-2">Mileage</th>
                                        <th class="border border-gray-400 px-4 py-2">Fuel Consumption</th>
                                        <th class="border border-gray-400 px-4 py-2">Car Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['plate_no']; ?></td>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['model']; ?></td>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['type']; ?></td>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['make_series_type']; ?></td>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['seater']; ?></td>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['mileage']; ?></td>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['fuel_consump']; ?></td>
                                        <td class="border border-gray-400 px-4 py-2"><?php echo $vehicle['car_status']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="bg-white- rounded-sm col-span-1 gap-3 mx-3 my-64">
                    <div class="ml-2">Recent Trip</div>
                    <div><!-- MAPBOX --></div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
            function showCarData(index) {
                // Hide all car data divs
                var carDataDivs = document.querySelectorAll("[id^='car']");
                carDataDivs.forEach(function(div) {
                    div.classList.add("hidden");
                });

                // Show selected car data div
                var carDataToShow = document.getElementById("car" + (index + 1) + "data");
                carDataToShow.classList.remove("hidden");
            }
        </script>
    </body>
</html>



