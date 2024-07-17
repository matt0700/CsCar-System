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

$full_name = "Escarlet R. Conde"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
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
                        <div class=" title text-5xl mt-3 mb-3 font-bold">
                            Reports
                        </div>
                    </div>
                </div>
        </div>
            <div class="w3-container ml-56">
            <?php
                include '../connection.php';

                // Set the number of records per page
                $recordsPerPage = 10;

                // Get the current page number from the query string, defaulting to 1 if not set
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $offset = ($page - 1) * $recordsPerPage;

                // Get sorting parameters from the query string, defaulting to 'mrot_id' and 'asc'
                $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'mrot_id';
                $sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'asc';
                $validSortBy = ['mrot_id', 'plate_no', 'mileage_trip', 'fuel_trip', 'issue', 'submitted'];
                $sortBy = in_array($sortBy, $validSortBy) ? $sortBy : 'mrot_id';
                $sortOrder = ($sortOrder === 'asc' || $sortOrder === 'desc') ? $sortOrder : 'asc';

                // Query to fetch records with sorting and pagination
                $mrotSql = "SELECT mrot_id, plate_no, mileage_trip, fuel_trip, issue, submitted FROM mrot ORDER BY $sortBy $sortOrder LIMIT ? OFFSET ?";
                $mrotStmt = $connect->prepare($mrotSql);
                $mrotStmt->bind_param('ii', $recordsPerPage, $offset);
                $mrotStmt->execute();
                $mrotResult = $mrotStmt->get_result();

                // Query to count total number of records
                $countSql = "SELECT COUNT(*) AS total FROM mrot";
                $countStmt = $connect->prepare($countSql);
                $countStmt->execute();
                $countResult = $countStmt->get_result();
                $totalRecords = $countResult->fetch_assoc()['total'];
                $totalPages = ceil($totalRecords / $recordsPerPage);

                echo "<div class='text-black rounded-md p-4 mb-4'>";
                echo "<table class='min-w-full bg-white'>";
                echo "<thead class='bg-gray-800 text-white'>";
                echo "<tr>";
                echo "<th class='w-1/6 px-4 py-2 '><a href='?sortBy=mrot_id&sortOrder=" . ($sortOrder == 'asc' ? 'desc' : 'asc') . "' style='color: white; text-decoration: none;'>MROT ID</a></th>";
                echo "<th class='w-1/6 px-4 py-2 '><a href='?sortBy=plate_no&sortOrder=" . ($sortOrder == 'asc' ? 'desc' : 'asc') . "' style='color: white; text-decoration: none;'>Plate No</a></th>";
                echo "<th class='w-1/6 px-4 py-2'><a href='?sortBy=mileage_trip&sortOrder=" . ($sortOrder == 'asc' ? 'desc' : 'asc') . "' style='color: white; text-decoration: none;'>Mileage Trip</a></th>";
                echo "<th class='w-1/6 px-4 py-2'><a href='?sortBy=fuel_trip&sortOrder=" . ($sortOrder == 'asc' ? 'desc' : 'asc') . "' style='color: white; text-decoration: none;'>Fuel Trip</a></th>";
                echo "<th class='w-1/6 px-4 py-2'><a href='?sortBy=issue&sortOrder=" . ($sortOrder == 'asc' ? 'desc' : 'asc') . "' style='color: white; text-decoration: none;'>Issue</a></th>";
                echo "<th class='w-1/6 px-4 py-2'><a href='?sortBy=submitted&sortOrder=" . ($sortOrder == 'asc' ? 'desc' : 'asc') . "' style='color: white; text-decoration: none;'>Submitted</a></th>";
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

                // Pagination controls
                echo "<div class='mt-4'>";
                echo "<nav class='flex justify-center'>";
                echo "<ul class='pagination'>";
                for ($i = 1; $i <= $totalPages; $i++) {
                    $activeClass = $i == $page ? 'bg-blue-500 text-white' : 'bg-white text-blue-500';
                    echo "<li class='mx-1'>";
                    echo "<a class='px-3 py-1 border rounded $activeClass' href='?page=$i&sortBy=$sortBy&sortOrder=$sortOrder'>$i</a>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</nav>";
                echo "</div>";

                $mrotStmt->close();
                $countStmt->close();
                $connect->close();
                ?>


            </div>
    </div>
</body>
</html>