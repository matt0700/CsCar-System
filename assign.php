<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cscar_database';

// Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetched unassigned Trips
$trips_sql = 'SELECT * FROM ruv_table WHERE ruvNO NOT IN (SELECT ruvNO FROM trips)';
$trips_result = $conn->query($trips_sql);

if ($trips_result->num_rows > 0) {
    while ($trip = $trips_result->fetch_assoc()) {
        // Find Available driver
        $driver_found = false;
        $driver_sql = "SELECT * FROM drivers WHERE driver_status = 'Available' LIMIT 1";
        $driver_result = $conn->query($driver_sql);

        if ($driver_result->num_rows > 0) {
            $driver = $driver_result->fetch_assoc();
            $driver_found = true;
        }

        // Attempt to find an available vehicle that can accommodate number of passengers
        $vehicle_found = false;
        $vehicle_sql = "SELECT * FROM vehicle_data WHERE car_status = 'Available' ORDER BY ABS(seater - ?) ASC LIMIT 1";
        $stmt = $conn->prepare($vehicle_sql);
        $stmt->bind_param("i", $trip['no_passengers']);
        $stmt->execute();
        $vehicle_result = $stmt->get_result();

        if ($vehicle_result->num_rows > 0) {
            $vehicle = $vehicle_result->fetch_assoc();
            $vehicle_found = true;
        }
        $stmt->close();

        if ($driver_found && $vehicle_found) {
            // Insert new trip
            $assign_sql = "INSERT INTO trips (ruvNO, driver_id, plate_no, trip_date) VALUES (?, ?, ?, CURDATE())";
            $stmt = $conn->prepare($assign_sql);
            $stmt->bind_param("iis", $trip['ruvNO'], $driver['driver_id'], $vehicle['plate_no']);
            $stmt->execute();
            $stmt->close();

            // Update driver status
            $update_driver_sql = "UPDATE drivers SET driver_status = 'Unavailable' WHERE driver_id = ?";
            $stmt = $conn->prepare($update_driver_sql);
            $stmt->bind_param("i", $driver['driver_id']); 
            $stmt->execute();
            $stmt->close();

            // Update vehicle status
            $update_vehicle_sql = "UPDATE vehicle_data SET car_status = 'Unavailable' WHERE plate_no = ?";
            $stmt = $conn->prepare($update_vehicle_sql);
            $stmt->bind_param("s", $vehicle['plate_no']);
            $stmt->execute();
            $stmt->close();
        
        } else {
            echo "No available driver or suitable vehicle for trip " . $trip['ruvNO'] . " with " . $trip['no_passengers'] . " passengers.\n";
        }
    }
} else {
    echo "No unassigned trips found.\n";
}

$conn->close();

?>
