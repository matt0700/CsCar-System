<?php
// Include database connection
include "connection.php";

if (isset($_GET['driverId']) && isset($_GET['ruvNO'])) {
    $driverId = $_GET['driverId'];
    $ruvNO = $_GET['ruvNO'];

    // Adjusted query to fetch both driver email and RUV email
    $sql = "
        SELECT d.driver_name, d.driver_cellno, d.email AS driver_email, r.email AS ruv_email
        FROM drivers d
        INNER JOIN trips t ON d.driver_id = t.driver_id
        INNER JOIN ruv_table r ON t.ruvNO = r.ruvNO
        WHERE d.driver_id = ? AND t.ruvNO = ?
    ";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("is", $driverId, $ruvNO);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Prepare the driver details and RUV email in HTML format
            $driverDetails = "<p>Name: " . htmlspecialchars($row['driver_name']) . "</p>";
            $driverDetails .= "<p>Phone: " . htmlspecialchars($row['driver_cellno']) . "</p>";
            $driverDetails .= "<p>Email: <button type='button' class='btn btn-primary' data-email='" . htmlspecialchars($row['driver_email']) . "' onclick='populateEmailFields(\"" . htmlspecialchars($row['driver_email']) . "\", \"driver\")'>" . htmlspecialchars($row['driver_email']) . "</button></p>";
            $driverDetails .= "<h2>Requester Details</h2>"; // Header for RUV email section
            $driverDetails .= "<p>Email: <button type='button' class='btn btn-primary' data-email='" . htmlspecialchars($row['ruv_email']) . "' onclick='populateEmailFields(\"" . htmlspecialchars($row['ruv_email']) . "\", \"ruv\")'>" . htmlspecialchars($row['ruv_email']) . "</button></p>";
            
            // Output the driver details
            echo $driverDetails;
        } else {
            echo "<p>No driver found with ID: " . htmlspecialchars($driverId) . " or RUV email not found for RUV NO: " . htmlspecialchars($ruvNO) . "</p>";
        }
    } else {
        echo "<p>Error executing query</p>";
    }

    $stmt->close();
} else {
    echo "<p>Driver ID or RUV NO not provided</p>";
}

// Close database connection
$connect->close();
?>
