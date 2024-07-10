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
    <title>Main Dashboard</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/home.css">
    <!-- tailwind -->
<script src="https://cdn.tailwindcss.com"></script>


</head>
<body class="bg-white">
<div class="w3-main z-10 ">
<div class=" text-black h-20 static border-none  ">
  <button class="w3-button w3-greyw3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
  <div class="w3-container flex static z-50 ml-56 " style="color: white;">
    <div class="flex-col text-black">
    <div>
      <h1>Drivers</h1>
    </div>
    </div>
</div>
</div>
</div>


<body class="bg-gray-100 p-10">

<div class="test z-50 flex justify-center ml-[200px]">
<?php
if ($result->num_rows > 0) {
    // Output data of each row
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

  

</div>
</body>
<footer>

</footer>
</html>



