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
    <link
      href="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css"
      rel="stylesheet"
    />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.css" type="text/css">


</head>

<style>
#map {
        /* position: static;
        top: 0;
        bottom: 0; */
        width: 100%;
        height: 100%;
      }
</style>
<body>
<div class="w3-main">
<div class="w3-grey">
  <button class="w3-button w3-greyw3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
  
  <div class="w3-container flex  " style="color: white;">
    Fleet Management
  </div>
</div>



<div class="test">

  <div class=" grid grid-cols-1 gap-5 mx-3 my-3" >
    <h1>MAP</h1>
    <div class="rounded-sm min-h-[500px] min-w-full border-4 border-black ">
    <div id="map"></div>
    </div>

    <div></div>

    
  </div>
</div>
  
 
</div>
</body>

<script>
      mapboxgl.accessToken = 'pk.eyJ1IjoiZHVyYWUxMTIxIiwiYSI6ImNseHN1cDRjeDFxNmgycm9kaHdveGk0Ym8ifQ.QiSm1couKGgp_OQtmL_ELQ';

      navigator.geolocation.getCurrentPosition(successLocation, errorLocation, {
        enableHighAccuracy: true,
        trackUserLocation: true,
        showUserHeading: true
      });

      function successLocation(position) {
        console.log(position);
        setupMap([position.coords.longitude, position.coords.latitude]);
      }

      function errorLocation() {
        setupMap([121.0223, 14.6091]);
      }

      function setupMap(center) {
        const map = new mapboxgl.Map({
          container: 'map',
          style: 'mapbox://styles/mapbox/streets-v12',
          center: center,
          zoom: 10
        });

          map.addControl(
            new MapboxDirections({
              accessToken: mapboxgl.accessToken,
            }),
            'top-left'
          );

            map.addControl(
              new mapboxgl.GeolocateControl({
                positionOptions: {
                  enableHighAccuracy: true
                },
                trackUserLocation: true,
                showUserHeading: true
              })
            );
      }
    </script>
<footer>

</footer>
</html>



