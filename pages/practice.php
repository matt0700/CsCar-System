<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";

$sql = "SELECT * FROM drivers";
$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Display Text on Click</title>
<style>
    body {
        font-family: Arial, sans-serif;
        text-align: center;
        margin-top: 50px;
    }
    .hidden {
        display: none;
    }
</style>
</head>
<body>
    <h2>Click the button to display data</h2>
    <button onclick="displayText()">Click Me</button>
    <div id="driverData" class="hidden"> 
        <?php
        if ($result->num_rows > 0) {
            echo "<table class='table-auto border-collapse border border-gray-400'>";
            echo "<thead><tr class='bg-gray-200'>";
            echo "<th class='border border-gray-400 px-4 py-2'>ID</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>NAME</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>Cellphone Number</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>Trip</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>Driver Status</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>Latitude</th>";
            echo "<th class='border border-gray-400 px-4 py-2'>Last Update</th>";
            echo "</tr></thead><tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["driver_id"]. "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["driver_name"]. "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["driver_cellno"]. "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["trip"]. "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["driver_status"]. "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["latitude"]. " , " . $row["longitude"]. "</td>";
                echo "<td class='border border-gray-400 px-4 py-2'>" . $row["last_update"]."</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='text-gray-500'>0 results</p>";
        }
        $connect->close();
        ?>
    </div>

    <script>
        function displayText() {
            var driverDataDiv = document.getElementById("driverData");
            driverDataDiv.classList.remove("hidden");
        }
    </script>
</body>
</html>