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

// Query to retrieve all rows from drivers
$sql = "SELECT * FROM drivers";
$result_drivers = $connect->query($sql);

if (!$result_drivers) {
    // Handle query error
    die("Query failed: " . mysqli_error($connect));
}

// Fetch all rows into an array
$drivers = [];
while ($row_driver = $result_drivers->fetch_assoc()) {
    $drivers[] = $row_driver;
}

$connect->close(); // Close connection after use
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivers</title>
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
    .shadowbox{
        box-shadow: none;
    }
    #nameValue, #cellphoneNoValue, #tripValue, #driverstatusValue, #latitudeValue, #lastupdateValue {
        margin: 0;
        padding: 10px; /* Optionally adjust padding if needed */
    }
    
}
</style>
        

    </style>
</head>
<body class="bg-white" >
    <div class="w3-main z-10 ">
        <div class=" bg-slate-900 text-white h-auto static border-none">
            <button class="w3-button w3-grey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
            
            <div class="w3-container flex transition-all " style="color: white;">
                <div class="title flex-col  ml-[200px] text-5xl mt-3 font-bold">
                    Driver Information
                </div>
                <div class="flex-col w3-display-topright w3-margin-right mx-2 my-2 z-50 ml-10">
                    
                    <div>
                        <?php echo $full_name; ?>
                    </div>
                    <div>
                        Admin
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="test z-50 ml-[200px]">
            <div class="grid grid-cols-3 gap-3 mx-3 my-3">
                <div class="shadowbox rounded-sm min-h-[620px] max-w-[400px] border-2 shadow-2xl shadow-slate-300 ">
                    <div class="ml-4 mt-2 text-3xl font-bold">Drivers</div>
                    <?php foreach ($drivers as $index => $driver): ?>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center ">
                            <button class=" ml-4 flex content-center items-center mt-3 hover:bg-gray-400 duration-300" onclick="showDriverData(<?php echo $index; ?>)">
                                <img class="w-8 h-8" src="https://img.icons8.com/ios-filled/50/1A1A1A/car.png" alt="car"/>
                                <span class="ml-2"><?php echo $driver['driver_name']; ?></span>
                            </button>
                        </div>
                        <div class="mt-3 mr-4 px-1 py-1 bg-opacity-60 <?php echo ($driver['driver_status'] == 'Available') ? 'bg-green-200 border-1 border-green-500' : 'bg-red-200 border-1 border-red-500'; ?>">
                            <div><?php echo $driver['driver_status']; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="rounded-sm col-span-2 max-h-[600px] border-1 shadow-xl shadow-slate-300 transition-all">
                    <div class=" mt-3 ml-8 text-4xl font-extrabold">Driver Information</div>
                    <div class=" details p-4 grid grid-cols-2 gap-y-36 gap-x-10 ml-3">

                        <div class="flex-col">
                        <div id="nameLabel" class="text-3xl font-bold">Name</div>
                        <div id="nameValue" class="mt-10 text-3xl"></div>
                        </div>

                        <div class="flex-col" >
                        <div id="cellphoneNoLabel" class="text-3xl font-bold">Cellphone No.</div>
                        <div id="cellphoneNoValue" class="mt-10 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                        <div id="tripLabel" class="text-3xl font-bold">Trip</div>
                        <div id="tripValue" class="mt-10 text-3xl"></div>
                        </div>

                        <div class="flex-col"> 
                        <div id="driverstatusLabel" class="text-3xl font-bold">Status</div>
                        <div id="driverstatusValue" class="mt-10 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                        <div id="latitudeLabel" class="text-3xl font-bold">Latitude</div>
                        <div id="latitudeValue" class="mt-10 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                        <div id="lastupdateLabel" class="text-3xl font-bold">Last Update</div>
                        <div id="lastupdateValue" class="mt-10 text-3xl"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
            var driverData = <?php echo json_encode($drivers); ?>;

            function showDriverData(index) {
                var driver = driverData[index];

                // Update labels and values
                document.getElementById("nameValue").textContent = driver.driver_name;
                document.getElementById("cellphoneNoValue").textContent = driver.driver_cellno;
                document.getElementById("tripValue").textContent = driver.trip;
                document.getElementById("driverstatusValue").textContent = driver.driver_status;
                document.getElementById("latitudeValue").textContent = driver.latitude;
                document.getElementById("lastupdateValue").textContent = driver.last_update;
                
            }

               
    
        </script>
    </body>
</html>