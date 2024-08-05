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
    <title>Vehicle Information</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/global.css">
    <style>

        .hidden {
            display: none;
        }
        @media only screen and (max-width: 991px) {
        .w3-container {
            margin: 0px;
        }
        .title{
            margin: 0px;
        }
        .test {
            margin: 0px;
        }

        .details{
            display: inline-block;
        }
        #modelValue, #fuelValue, #mileageValue, #seaterValue, #statusValue, #typeValue, #yearValue, #plateNoValue{
            margin: 0;
            padding: 10px;
        }
    }
    @media only screen and (max-width: 600px) {

        .grid-cols-3 {
        grid-template-columns: 1fr; 
    }

    .min-h-[620px] {
        min-height: auto;
    }

    .col-span-2 {
        grid-column: span 1; 
    }

    .details {
        grid-template-columns: 1fr; 
    }

    .text-3xl {
        font-size: 2rem; 
    }

    .mt-10 {
        margin-top: 1.5rem; 
    }

    .ml-8 {
        margin-left: 1rem; 
    }
    
    .vehicledisp {
        box-shadow: none;
    }
    
}

    </style>
</head>
<body class="bg-white" >
    <div class="w3-main z-10 ">
        <div class="bg-slate-900 text-white h-auto static border-none">
            <button class="w3-button w3-grey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
            
            <div class="w3-container flex transition-all " style="color: white;">
                <div class=" title flex-col  ml-[200px] text-5xl mt-3 mb-3 font-bold">
                    Vehicle Information
                </div>
            </div>
        </div>
            
        <div class="test z-50 ml-[200px]">
            <div class="grid grid-cols-3 gap-3 mx-3 my-3">
                <div class="vehicledisp rounded-sm min-h-[620px] max-w-[400px] border-2 shadow-2xl shadow-slate-300 ">
                    <div class="ml-4 mt-2 text-4xl font-extrabold">All Vehicles</div>
                    <?php foreach ($vehicle_data as $index => $vehicle): ?>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <button class=" ml-4 flex content-center items-center mt-4 hover:bg-gray-400 duration-300" onclick="showCarData(<?php echo $index; ?>)">
                                <img class="w-9 h-9" src="https://img.icons8.com/ios-filled/50/1A1A1A/car.png" alt="car"/>
                                <span class="ml-2 "><?php echo $vehicle['make_series_type']; ?></span>
                            </button>
                        </div>
                            <div class="flex items-center mt-3 mr-4">
                                <!-- <div class="px-1 py-1 bg-opacity-60 <?php echo ($vehicle['car_status'] == 'Available') ? 'bg-green-200 border-1 border-green-500': 'bg-red-200 border-1 border-red-500'; ?>">
                                    <div><?php echo $vehicle['car_status']; ?></div>
                                </div> -->
                                <form action="update_vehicle_status.php" method="post" class="ml-2">
                                <input type="hidden" name="plate_no" value="<?php echo $vehicle['plate_no']; ?>">
                                <select name="status" onchange="this.form.submit()" class="ml-2 <?php echo ($vehicle['car_status'] == 'Available') ? 'bg-green-200 border-green-500 rounded-lg p-2 ' : 'bg-red-200 border-red-500 rounded-lg p-2 '; ?>">
                                    <option value="Available" <?php echo ($vehicle['car_status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                    <option value="Unavailable" <?php echo ($vehicle['car_status'] == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                                </select>
                                </form>
                            </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="rounded-sm col-span-2  border-1 shadow-xl shadow-slate-300 transition-all">
                    <div class=" mt-3 ml-8 text-4xl font-extrabold">Vehicle Information</div>
                    <div class=" details p-4 grid grid-cols-2 gap-y-20 gap-x-24 ml-3">

                        <div class="flex-col">
                        <div id="modelLabel" class="  text-4xl font-bold">Model</div>
                        <div id="modelValue" class="  mt-6 text-3xl"></div>
                        </div>
                        <div class="flex-col" >
                        <div id="plateNoLabel" class="  text-4xl font-bold">Plate No.</div>
                        <div id="plateNoValue" class=" mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                        <div id="yearLabel" class="  text-4xl font-bold">Year</div>
                        <div id="yearValue" class=" mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col"> 
                        <div id="typeLabel" class="  text-4xl font-bold">Type</div>
                        <div id="typeValue" class="  mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                        <div id="statusLabel" class="  text-4xl font-bold">Status</div>
                        <div id="statusValue" class="  mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                        <div id="seaterLabel" class="  text-4xl font-bold">Seater</div>
                        <div id="seaterValue" class="  mt-6 text-3xl"></div>
                        </div>

                        <div class="flex--col">
                        <div id="mileageLabel" class="  text-4xl font-bold">Mileage</div>
                        <div id="mileageValue" class="  mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                        <div id="fuelLabel" class="  text-4xl font-bold">Recent Fuel Consumption</div>
                        <div id="fuelValue" class="  mt-6 text-3xl"></div>
                        </div>
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