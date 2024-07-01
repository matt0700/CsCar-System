<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Management Dashboard</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/home.css">
    <!-- tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.css" type="text/css">
    <style>
        body { margin: 0; padding: 0; }
        #map { width: 100%; height: 100%; }
        #use-location-btn, #start-simulation-btn {
            position: relative;
            top: 10px;
            left: 10px;
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
    </style>
</head>
<body>
<div class="w3-main ml-10">
    <div class="w3-grey">
        <button class="w3-button w3-grey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
        <div class="w3-container flex" style="color: white;">
            Fleet Management
        </div>
    </div>
    <div class="test ml-[180px]">
        <div class="grid grid-cols-1 mx-2 my-2">
            <h1>MAP</h1>
            <div class="rounded-sm min-h-[500px] min-w-[100px] border-4 border-black">
                <div id="map"></div>
                <button id="use-location-btn">Use My Current Location</button>
            </div>
            <div></div>
        </div>
    </div>
</div>

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiZHVyYWUxMTIxIiwiYSI6ImNseHN1cDRjeDFxNmgycm9kaHdveGk0Ym8ifQ.QiSm1couKGgp_OQtmL_ELQ';

    let currentLocation = [121.0223, 14.6091]; // Default location (e.g., Manila, Philippines)
    let routeCoordinates;

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

        directions.setOrigin(center);
    }
</script>
<footer>
</footer>
</html>
