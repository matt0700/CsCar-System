<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
// Include database connection
include "../connection.php";

// Set default values for pagination and sorting
$limit = 10; // Number of entries per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'ruvNO';
$sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] == 'desc' ? 'desc' : 'asc';

// Count total records
$countSql = "SELECT COUNT(*) as total FROM ruv_table WHERE status IN ('Approved', 'Denied')";
$countResult = $connect->query($countSql);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch data with pagination and sorting, filtering by status
$sql = "SELECT * FROM ruv_table WHERE status IN ('Approved', 'Denied') ORDER BY $sortBy $sortOrder LIMIT $limit OFFSET $offset";
$result = $connect->query($sql);

mysqli_close($connect); // Close connection after use
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUV History</title>
    <?php include '../snippets/header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/global.css">
    <link rel="stylesheet" href="../assets/home.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media only screen and (max-width: 991px) {
            .w3-container {
                margin: 0px;
            }
        }
        tr a {
            color: white !important;
            text-decoration: none !important;
        }
    </style>
</head>

<body class="bg-white">
    <div class="w3-main">
        <div class="h-25 static border-none bg-slate-900">
            <button class="w3-button w3-grey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
            <div class="w3-container flex static ml-56" style="color: white;">
                <div class="flex text-white">
                    <div class="text-5xl mt-3 mb-3 font-bold">
                        RUV history
                    </div>
                </div>
            </div>
        </div>

        <div class="w3-container flex static ml-56" style="color: white;">
            <div class="p-6 rounded-lg text-black w-full max-w-7xl m-2">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-black">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-2 border"><a href="?sortBy=ruvNO&sortOrder=<?= $sortOrder == 'asc' ? 'desc' : 'asc' ?>">RUV No</a></th>
                                <th class="px-4 py-2 border"><a href="?sortBy=pickup_point&sortOrder=<?= $sortOrder == 'asc' ? 'desc' : 'asc' ?>">Pick-up Point</a></th>
                                <th class="px-4 py-2 border"><a href="?sortBy=destination&sortOrder=<?= $sortOrder == 'asc' ? 'desc' : 'asc' ?>">Destination</a></th>
                                <th class="px-4 py-2 border"><a href="?sortBy=submitted&sortOrder=<?= $sortOrder == 'asc' ? 'desc' : 'asc' ?>">Date Submitted</a></th>
                                <th class="px-4 py-2 border"><a href="?sortBy=status&sortOrder=<?= $sortOrder == 'asc' ? 'desc' : 'asc' ?>">Status</a></th>
                                <th class="px-4 py-2 border"><a href="?sortBy=pref_time&sortOrder=<?= $sortOrder == 'asc' ? 'desc' : 'asc' ?>">Pick up time</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='border px-4 py-2'>" . $row['ruvNO'] . "</td>";
                                    echo "<td class='border px-4 py-2'>" . $row['pickup_point'] . "</td>";
                                    echo "<td class='border px-4 py-2'>" . $row['destination'] . "</td>";
                                    echo "<td class='border px-4 py-2'>" . $row['submitted'] . "</td>";
                                    echo "<td class='border px-4 py-2'>" . $row['status'] . "</td>";
                                    echo "<td class='border px-4 py-2'>" . $row['pref_time'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='border px-4 py-2'>No pending requests found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mt-4">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&sortBy=<?= $sortBy ?>&sortOrder=<?= $sortOrder ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
