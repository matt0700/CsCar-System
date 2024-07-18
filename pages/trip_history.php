    <?php
    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: ../login.php");
        exit();
    }
    // Include database connection
    include "../connection.php";

    // Set the number of records per page
    $recordsPerPage = 10;

    // Get the current page number from the query string, defaulting to 1 if not set
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $recordsPerPage;

    // Get sorting parameters from the query string, defaulting to 'trip_id' and 'asc'
    $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'trip_id';
    $sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'asc';
    $validSortBy = ['trip_id', 'ruvNO', 'driver_name', 'make_series_type', 'trip_date', 'status'];
    $sortBy = in_array($sortBy, $validSortBy) ? $sortBy : 'trip_id';
    $sortOrder = ($sortOrder === 'asc' || $sortOrder === 'desc') ? $sortOrder : 'asc';

    // Query to fetch records with sorting and pagination, joining drivers and trips tables
    $sql = "SELECT t.trip_id, t.ruvNO, d.driver_name, tr.make_series_type, t.trip_date, t.status, t.deny_reason 
    FROM trips t
    LEFT JOIN drivers d ON t.driver_id = d.driver_id
    LEFT JOIN vehicle_data tr ON t.plate_no = tr.plate_no
    WHERE t.status IN ('Done', 'Denied')
    ORDER BY $sortBy $sortOrder
    LIMIT ? OFFSET ?";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param('ii', $recordsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Query to count total number of records
    $countSql = "SELECT COUNT(*) as total FROM trips WHERE status IN ('Done', 'Denied')";
    $countStmt = $connect->prepare($countSql);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRecords = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);
    ?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trip History</title>
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



    <body class="bg-white">
        <div class="w3-main">
            <div class="h-25 static border-none bg-slate-900">
                <button class="w3-button w3-grey w3-xlarge w3-hide-large" onclick="w3_open()">&#9776;</button>
                <div class="w3-container flex static ml-56" style="color: white;">
                    <div class="flex-col text-white">
                        <div class="text-5xl mt-3 mb-3 font-bold">
                            Trip History
                        </div>
                    </div>
                </div>
            </div>

            <div class="w3-container ml-56 mt-7">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-800 text-white">
                                <th class="border border-gray-300 px-4 py-2"><a href="?sortBy=trip_id&sortOrder=<?php echo ($sortOrder == 'asc' ? 'desc' : 'asc'); ?>" style="color: white; text-decoration: none;">Trip ID</a></th>
                                <th class="border border-gray-300 px-4 py-2"><a href="?sortBy=ruvNO&sortOrder=<?php echo ($sortOrder == 'asc' ? 'desc' : 'asc'); ?>" style="color: white; text-decoration: none;">RUV NO</a></th>
                                <th class="border border-gray-300 px-4 py-2"><a href="?sortBy=driver_name&sortOrder=<?php echo ($sortOrder == 'asc' ? 'desc' : 'asc'); ?>" style="color: white; text-decoration: none;">Driver Name</a></th>
                                <th class="border border-gray-300 px-4 py-2"><a href="?sortBy=make_series_type&sortOrder=<?php echo ($sortOrder == 'asc' ? 'desc' : 'asc'); ?>" style="color: white; text-decoration: none;">Vehicle Name</a></th>
                                <th class="border border-gray-300 px-4 py-2"><a href="?sortBy=trip_date&sortOrder=<?php echo ($sortOrder == 'asc' ? 'desc' : 'asc'); ?>" style="color: white; text-decoration: none;">Trip Date</a></th>
                                <th class="border border-gray-300 px-4 py-2"><a href="?sortBy=status&sortOrder=<?php echo ($sortOrder == 'asc' ? 'desc' : 'asc'); ?>" style="color: white; text-decoration: none;">Status</a></th>
                                <th class="border border-gray-300 px-4 py-2"><a href="?sortBy=deny_reason&sortOrder=<?php echo ($sortOrder == 'asc' ? 'desc' : 'asc'); ?>" style="color: white; text-decoration: none;">Deny Reason</a></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='border border-gray-200'>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["trip_id"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["ruvNO"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["driver_name"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["make_series_type"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["trip_date"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["status"]) . "</td>";
                                echo "<td class='px-4 py-2'>" . htmlspecialchars($row["deny_reason"]) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <nav class="flex justify-center">
                        <ul class="pagination">
                            <?php
                            for ($i = 1; $i <= $totalPages; $i++) {
                                $activeClass = $i == $page ? 'bg-blue-500 text-white' : 'bg-white text-blue-500';
                                echo "<li class='mx-1'>";
                                echo "<a class='px-3 py-1 border rounded $activeClass' href='?page=$i'>$i</a>";
                                echo "</li>";
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </body>
    </html>

    <?php
    // Close statement and connection
    $stmt->close();
    $countStmt->close();
    $connect->close();
    ?>




