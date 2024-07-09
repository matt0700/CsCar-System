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
                <div class="rounded-sm min-h-[600px] max-w-[400px] border-2 shadow-2xl shadow-slate-300 ">
                    <div class="ml-2">All Cars</div>
                    <?php foreach ($vehicle_data as $index => $vehicle): ?>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <button class="ml-2 flex content-center items-center mt-3 hover:bg-gray-400 duration-300" onclick="showCarData(<?php echo $index; ?>)">
                                <img class="w-8 h-8" src="https://img.icons8.com/ios-filled/50/1A1A1A/car.png" alt="car"/>
                                <span class="ml-2"><?php echo $vehicle['make_series_type']; ?></span>
                            </button>
                        </div>
                        <div class="mt-3 mr-4 px-1 py-1 bg-opacity-60 <?php echo ($vehicle['car_status'] == 'Available') ? 'bg-green-200 border-1 border-green-500' : 'bg-red-200 border-1 border-red-500'; ?>">
                            <div><?php echo $vehicle['car_status']; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="rounded-sm col-span-2 max-h-[600px] border-1 shadow-xl shadow-slate-300 transition-all">
                    <div class="ml-2 text-size-10">Car Information</div>
                    <div class=" p-4 grid grid-cols-4 gap-4 border-black border-1">
                        <div id="modelLabel">Model:</div>
                        <div id="modelValue"></div>
                        <div id="plateNoLabel">Plate No.:</div>
                        <div id="plateNoValue"></div>
                        <div id="yearLabel">Year:</div>
                        <div id="yearValue"></div>
                        <div id="typeLabel">Type:</div>
                        <div id="typeValue"></div>
                        <div id="statusLabel">Status:</div>
                        <div id="statusValue"></div>
                        <div id="seaterLabel">Seater:</div>
                        <div id="seaterValue"></div>
                        <div id="mileageLabel">Mileage:</div>
                        <div id="mileageValue"></div>
                        <div id="fuelLabel">Fuel Consumption:</div>
                        <div id="fuelValue"></div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
            var vehicleData = <?php echo json_encode($vehicle_data); ?>;

            function showCarData(index) {
                var vehicle = vehicleData[index];

                // Update labels and values
                document.getElementById("modelValue").textContent = vehicle.make_series_type;
                document.getElementById("plateNoValue").textContent = vehicle.plate_no;
                document.getElementById("yearValue").textContent = vehicle.model;
                document.getElementById("typeValue").textContent = vehicle.type;
                document.getElementById("statusValue").textContent = vehicle.car_status;
                document.getElementById("seaterValue").textContent = vehicle.seater;
                document.getElementById("mileageValue").textContent = vehicle.mileage;
                document.getElementById("fuelValue").textContent = vehicle.fuel_consump;
            }
        </script>
    </body>
</html>