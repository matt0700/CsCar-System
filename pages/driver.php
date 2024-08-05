<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ol3/3.20.1/ol.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ol3/3.20.1/ol.js"></script>

    <style>
        .hidden {
            display: none;
        }
        @media only screen and (max-width: 991px) {
        .w3-container {
            margin: 0px;
        }
        .title {
            margin: 0px;
        }
        .test {
            margin: 0px;
        }
        .details {
            display: inline-block;
        }
        #modelValue, #fuelValue, #mileageValue, #seaterValue, #statusValue, #typeValue, #yearValue, #plateNoValue {
            margin: 0;
            padding: 10px;
        }
        .table-loc{
            overflow-x: auto !important; /* Allow horizontal scroll for overflow content */

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
        .shadowbox {
            box-shadow: none;
        }
        #nameValue, #cellphoneNoValue, #tripValue, #driverstatusValue, #latitudeValue, #lastupdateValue {
            margin: 0;
            padding: 10px; /* Optionally adjust padding if needed */
        }
        .table-loc{
            overflow-x: auto !important; /* Allow horizontal scroll for overflow content */

        }
    }
    </style>
</head>
<body class="bg-white">
    <div class="w3-main ">
        <div class=" h-25 static border-none bg-slate-900">
            <button class="w3-button w3-grey w3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
            <div class="w3-container flex static ml-56" style="color: white;">
                <div class="flex text-white">
                    <div class="text-5xl mt-3 mb-3 font-bold">
                            Driver Information
                    </div>
                </div>
            </div>
        </div>

        <div class="test z-50 ml-[200px]">
            <div class="grid grid-cols-3 gap-3 mx-3 my-3">
                <div class="shadowbox rounded-sm min-h-[620px] max-w-[400px] border-2 shadow-2xl shadow-slate-300">
                    <div class="ml-4 mt-2 text-4xl font-extrabold">Driver ID</div>
                    <?php foreach ($drivers as $index => $driver): ?>
                    <div class="flex justify-between items-center">
                        <div class="details flex items-center">
                            <button class="ml-4 flex content-center items-center mt-4 hover:bg-gray-400 duration-300" onclick="showDriverData(<?php echo $index; ?>)">
                                <img class="w-9 h-9" src="https://img.icons8.com/ios-filled/50/1A1A1A/car.png" alt="car"/>
                                <span class="ml-2 text-lg"><?php echo $driver['driver_id']; ?></span>
                            </button>
                        </div>
                        <div class="flex items-center mt-3 mr-4">
                            <!-- <div class="px-1 py-1 bg-opacity-60 <?php echo ($driver['driver_status'] == 'Available') ? 'bg-green-200 border-1 border-green-500' : 'bg-red-200 border-1 border-red-500'; ?>">
                                    <div><?php echo $driver['driver_status']; ?></div>
                                </div> -->
                        <form action="update_driver_status.php" method="post" class="ml-2">
                        <input type="hidden" name="driver_id" value="<?php echo $driver['driver_id']; ?>">
                        <select name="status" onchange="this.form.submit()" class="ml-2 <?php echo ($driver['driver_status'] == 'Available') ? 'p-2 bg-green-200 border-green-500 rounded-lg' : ' p-2 bg-red-200 border-red-500 rounded-lg'; ?>">
                                    <option value="Available" <?php echo ($driver['driver_status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                    <option value="Unavailable" <?php echo ($driver['driver_status'] == 'Unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                                </select>
                        </form>

                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="table-loc rounded-sm col-span-2 border-1 shadow-xl shadow-slate-300 transition-all">
                    <div class="mt-3 ml-8 text-4xl font-extrabold">Driver Information</div>
                    <div class="details p-4 grid grid-cols-2 gap-y-20 gap-x-24 ml-3">
                        <div class="flex-col">
                            <div id="nameLabel" class="text-4xl font-bold">Name</div>
                            <div id="nameValue" class="mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                            <div id="cellphoneNoLabel" class="text-4xl font-bold">Cellphone No.</div>
                            <div id="cellphoneNoValue" class="mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col hidden" id="latitudeSection" style="display: none;">
                            <div id="latitudeLabel" class="text-4xl font-bold">Latitude</div>
                            <div id="latitudeValue" class="mt-6 text-3xl"></div>
                        </div>



                        <div class="flex-col hidden" id="longitudeSection" style="display: none;">
                            <div id="longitudeLabel" class="text-4xl font-bold">Longitude</div>
                            <div id="longitudeValue" class="mt-6 text-3xl"></div>
                        </div>

                        
                        <div class="flex-col"> 
                            <div id="driverstatusLabel" class="text-4xl font-bold">Status</div>
                            <div id="driverstatusValue" class="mt-6 text-3xl"></div>
                        </div>

        
                        <div class="flex-col">
                            <div id="lastupdateLabel" class="text-4xl font-bold">Last Update</div>
                            <div id="lastupdateValue" class="mt-6 text-3xl"></div>
                        </div>

                        <div class="flex-col">
                            <div id="latitudeLabel" class="text-4xl font-bold">Last Known Location</div>
                            <div id="miniMap" style="width: 500px; height: 250px; "></div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script>
        var map = new ol.Map({
            target: 'miniMap',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([0, 0]), // Default center
                zoom: 2
            })
        });

        function updateMap(latitude, longitude) {
    var coordinates = ol.proj.fromLonLat([longitude, latitude]);

    // Set the view to the new coordinates
    map.getView().setCenter(coordinates);
    map.getView().setZoom(18);

    // Define the custom marker style
    var markerStyle = new ol.style.Style({
        image: new ol.style.Icon({
            src: 'https://img.icons8.com/?size=100&id=13800&format=png&color=000000', // URL of the custom marker image
            scale: 0.3 // Adjust the scale to fit your icon size
        })
    });

    // Create a marker feature with the custom style
    var marker = new ol.Feature({
        geometry: new ol.geom.Point(coordinates)
    });

    marker.setStyle(markerStyle);

    // Create a vector source and layer
    var vectorSource = new ol.source.Vector({
        features: [marker]
    });

    var markerVectorLayer = new ol.layer.Vector({
        source: vectorSource
    });

    // Clear existing layers and add the new marker layer
    map.getLayers().forEach(function(layer) {
        if (layer instanceof ol.layer.Vector) {
            map.removeLayer(layer);
        }
    });

    map.addLayer(markerVectorLayer);
}
            var driverData = <?php echo json_encode($drivers); ?>;

            function showDriverData(index) {
                var driver = driverData[index];

                // Update labels and values
                document.getElementById("nameValue").textContent = driver.driver_name;
                document.getElementById("cellphoneNoValue").textContent = driver.driver_cellno;
                document.getElementById("driverstatusValue").textContent = driver.driver_status;
                document.getElementById("lastupdateValue").textContent = driver.last_update;
    
                // Show the latitude and longitude sections
                document.getElementById("latitudeSection").classList.remove("hidden");
                document.getElementById("longitudeSection").classList.remove("hidden");


                updateMap(parseFloat(driver.latitude), parseFloat(driver.longitude));

            }
        </script>
    </body>
</html>
