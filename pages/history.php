<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";

// SQL query to fetch data from the trips table where status is 'done'
$sql = "SELECT trip_id, ruvNO, plate_no, driver_id, trip_date, status FROM trips WHERE status = 'done' OR status = 'denied'";
    $result = $connect->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/home.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Mapbox and Directions Plugin -->
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.js"></script>
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.3.1/mapbox-gl-directions.css" type="text/css">
</head>
<body>
<body class="bg-white">
      <div class="w3-main">
          <div class=" h-25 static border-none bg-slate-900">
              <button class="w3-button w3-grey w3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
                <div class="w3-container flex static ml-56" style="color: white;">
                    <div class="flex-col text-white" >
                        <div class="text-5xl mt-3 mb-3 font-bold">
                            History
                        </div>

                    </div>
                 </div>
          </div>
          <div class="w3-container flex static ml-56 mt-7">
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-200 ">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="border border-gray-300 px-4 py-2">Trip ID</th>
                        <th class="border border-gray-300 px-4 py-2">RUV NO</th>
                        <th class="border border-gray-300 px-4 py-2">Plate No</th>
                        <th class="border border-gray-300 px-4 py-2">Driver ID</th>
                        <th class="border border-gray-300 px-4 py-2">Trip Date</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='border border-gray-200'>";
                        echo "<td class='px-4 py-2'>" . $row["trip_id"] . "</td>";
                        echo "<td class='px-4 py-2'>" . $row["ruvNO"] . "</td>";
                        echo "<td class='px-4 py-2'>" . $row["plate_no"] . "</td>";
                        echo "<td class='px-4 py-2'>" . $row["driver_id"] . "</td>";
                        echo "<td class='px-4 py-2'>" . $row["trip_date"] . "</td>";
                        echo "<td class='px-4 py-2'>" . $row["status"] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
</div>
</body>

<footer>
</footer>
</html>
<?php
// Close connection
$connect->close();
?>
