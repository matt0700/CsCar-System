<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";



mysqli_close($connect); // Close connection after use
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
@media only screen and (max-width: 991px) {
  .w3-container{
  margin: 0px;
}
}
</style>

<body class="bg-white">
    <div class="w3-main">
        <div class=" h-25 static border-none bg-slate-900">
            <button class="w3-button w3-grey w3-xlarge w3-hide-large " onclick="w3_open()">&#9776;</button>
                <div class="w3-container flex static ml-56" style="color: white;">
                    <div class="flex-col text-white">
                        <div>
                            <h1>Reports</h1>
                        </div>
                    </div>
                </div>
        </div>
            <div class="w3-container flex static ml-56">
                <?php
                include '../connection.php';

                // Query to fetch all records from the mrot table
                $mrotSql = "SELECT mrot_id, plate_no, mileage_trip, fuel_trip, issue, submitted FROM mrot";
                $mrotStmt = $connect->prepare($mrotSql);
                $mrotStmt->execute();
                $mrotResult = $mrotStmt->get_result();

                echo "<div class='text-black rounded-md p-4 mb-4'>";
                echo "<h2 class='text-lg font-semibold text-gray-800'>MROT Records</h2>";
                echo "<table class='min-w-full bg-white'>";
                echo "<thead class='bg-gray-800 text-white'>";
                echo "<tr>";
                echo "<th class='w-1/6 px-4 py-2'>MROT ID</th>";
                echo "<th class='w-1/6 px-4 py-2'>Plate No</th>";
                echo "<th class='w-1/6 px-4 py-2'>Mileage Trip</th>";
                echo "<th class='w-1/6 px-4 py-2'>Fuel Trip</th>";
                echo "<th class='w-1/6 px-4 py-2'>Issue</th>";
                echo "<th class='w-1/6 px-4 py-2'>Submitted</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                if ($mrotResult->num_rows > 0) {
                    while ($row = $mrotResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['mrot_id']) . "</td>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['plate_no']) . "</td>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['mileage_trip']) . "</td>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['fuel_trip']) . "</td>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['issue']) . "</td>";
                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['submitted']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td class='border px-4 py-2 text-center' colspan='6'>No records found</td>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
                echo "</div>";

                $mrotStmt->close();
                $connect->close();
                ?>

            </div>
    </div>
</body>
</html>