<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Getting started with the Mapbox Directions API</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
    <link
      href="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css"
      rel="stylesheet"
    />
    <style>
      body {
        margin: 0;
        padding: 0;
      }

      #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
                // add the JavaScript here
                mapboxgl.accessToken = 'pk.eyJ1IjoiZHVyYWUxMTIxIiwiYSI6ImNseHN1cDRjeDFxNmgycm9kaHdveGk0Ym8ifQ.QiSm1couKGgp_OQtmL_ELQ';
            const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [121.09787, 14.69267], // starting position
            zoom: 17
            });


            const start =  [121.09787, 14.69267];
    </script>
  </body>
</html>