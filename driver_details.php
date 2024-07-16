<?php
// Include database connection
include "connection.php";

if (isset($_GET['driverId'])) {
    $driverId = $_GET['driverId'];

    // Query to fetch driver details based on driver_id
    $sql = "SELECT * FROM drivers WHERE driver_id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $driverId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $driver = $result->fetch_assoc();

            // Prepare the driver details in HTML format
            $driverDetails = "<h3>Name: " . htmlspecialchars($driver['driver_name']) . "</h3>";
            $driverDetails .= "<p>Phone: " . htmlspecialchars($driver['driver_cellno']) . "</p>";
            $driverDetails .= "<p>Email: " . htmlspecialchars($driver['email']) . "</p>";

            // Output the driver details
            echo $driverDetails;
        } else {
            echo "<p>No driver found with ID: " . htmlspecialchars($driverId) . "</p>";
        }
    } else {
        echo "<p>Error executing query</p>";
    }

    $stmt->close();
} else {
    echo "<p>Driver ID not provided</p>";
}

// Close database connection
$connect->close();
?>
