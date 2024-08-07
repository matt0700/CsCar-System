<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['driver_id'])) {
    header("Location: ../login.php");
    exit();
}

// Include database connection
include "../connection.php";

// Fetch the current vehicle plate number for the ongoing trip
$driver_id = $_SESSION['driver_id']; // Assuming you have the driver ID in session
$sql = "
    SELECT v.plate_no
    FROM trips t
    JOIN vehicle_data v ON t.plate_no = v.plate_no
    WHERE t.driver_id = ? AND t.status = 'Ongoing'
";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();

$plate_no = '';
if ($row = $result->fetch_assoc()) {
    $plate_no = $row['plate_no'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Mapbox and Directions Plugin -->
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.css" type="text/css">
    <style>
        body { margin: 0; padding: 0; }
        #map { width: 100%; height: 500px; }
        #use-location-btn, #start-simulation-btn {
            position: relative;
            top: 10px;
            z-index: 1;
            background-color: #0f172a;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            color: white;
        }
        #start-simulation-btn {
            top: 50px;
        }
        @media only screen and (max-width: 992px) {
        .w3-container{
        margin: 0px !important;
        }

        .test{
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
      <div class="w3-main">
          <div class=" h-25 static border-none bg-slate-900">
              <button class="w3-button w3-grey w3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
                <div class="w3-container flex static ml-56" style="color: white;">
                    <div class="text-5xl mt-3 mb-3 font-bold" >
                        <div>
                            MAP
                        </div>
                    </div>
                 </div>
          </div>

<div class="w3-main ml-10">
    <div class="test ml-[180px]">
        <div class="grid grid-cols-1 mx-2 my-2">
            <div class="rounded-sm border-4 border-black">
                <div id="map"></div>
            </div>
            <button id="use-location-btn" >Use My Current Location</button>
            <div class="mt-4">
                <form id="locate-driver-form" class="flex space-x-2">
                </form>
            </div>
                    <!-- Report Problem Form -->
                    <div class="bg-gray-200 rounded-md p-4 mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Trip Report</h3>
                                    <form action="../report_trip.php" method="post">
                                        <div>
                                            <label for="current_vehicle">Current Vehicle Plate Number:</label>
                                            <input type="text" id="current_vehicle" name="current_vehicle" value="<?php echo htmlspecialchars($plate_no); ?>" readonly>

                                                <div class="mt-2">
                                                    <input type="hidden" name="distance" id="distance_value">
                                                    <div id="distance_display">Total Distance: 0.00 km</div>
                                                </div>  
                                    
                                        <div class="mb-4">
                                            <label for="issue" class="block text-sm font-medium text-gray-700">Issue:</label>
                                            <textarea name="issue" id="issue" rows="3" class="form-textarea mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                        </div>
                                                <button type="submit" class="btn btn-primary" >Submit</button>
                                    </form>
                            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        mapboxgl.accessToken = 'pk.eyJ1IjoiZHVyYWUxMTIxIiwiYSI6ImNseHN1cDRjeDFxNmgycm9kaHdveGk0Ym8ifQ.QiSm1couKGgp_OQtmL_ELQ';

        let currentLocation = [121.0223, 14.6091]; // Default location (e.g., Manila, Philippines)
        let totalDistance = 0; // Initialize total distance variable

        navigator.geolocation.getCurrentPosition(successLocation, errorLocation, {
            enableHighAccuracy: true
        });

        function successLocation(position) {
            console.log('Geolocation success:', position);
            currentLocation = [position.coords.longitude, position.coords.latitude];
            setupMap(currentLocation);
        }

        function errorLocation() {
            console.log('Geolocation error');
            setupMap(currentLocation);
        }

        function setupMap(center) {
            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v12',
                center: center,
                zoom: 14
            });

            const directions = new MapboxDirections({
                accessToken: mapboxgl.accessToken,
                unit: 'metric',
                profile: 'mapbox/driving'
            });

            map.addControl(directions, 'top-left');

            map.addControl(new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true,
                showUserHeading: true
            }));

            document.getElementById('use-location-btn').addEventListener('click', function() {
                navigator.geolocation.getCurrentPosition(function(position) {
                    console.log('Using current location:', position);
                    currentLocation = [position.coords.longitude, position.coords.latitude];
                    directions.setOrigin(currentLocation);
                    map.flyTo({ center: currentLocation, zoom: 14 });
                }, errorLocation, {
                    enableHighAccuracy: true
                });
            });

            document.getElementById('locate-driver-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const lat = parseFloat(document.getElementById('latitude').value);
                const lng = parseFloat(document.getElementById('longitude').value);

                // Validate latitude and longitude
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    const newLocation = [lng, lat];
                    console.log('Locating driver at:', newLocation);
                    directions.setOrigin(newLocation);
                    map.flyTo({ center: newLocation, zoom: 14 });
                } else {
                    alert('Please enter valid coordinates (Latitude between -90 and 90, Longitude between -180 and 180)');
                }
            });

            // Listen to route events for distance calculation
            directions.on('route', function(event) {
                if (event.route && event.route.length > 0) {
                    const distance = event.route[0].distance / 1000; // distance in kilometers
                    updateDistanceDisplay(distance.toFixed(2)); // update display
                }
            });

            directions.setOrigin(center);
        }

        // Function to update distance display 
        function updateDistanceDisplay(distance) {
            let numString = distance;
            let numDouble = parseFloat(numString);
    
        // Update display
        document.getElementById('distance_display').innerText = `Total Distance: ${numDouble.toFixed(2)} km`;

        // Store value in hidden input field
        document.getElementById('distance_value').value = numDouble.toFixed(2); // Assuming 'distance_value' is the ID of your hidden input
    }
    });


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

<footer>
</footer>
</body>
</html>
