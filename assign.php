<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'cscar_database';

// Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// Fetched unassigned Trips
$trips_sql = 'SELECT * FROM trips WHERE ruvNO NOT IN (SELECT ruvNO FROM trips)';
$trips_result = $conn->query($trips_sql);

if($trips_result->num_rows > 0){
    while ($trip = $trips_result->fetch_assoc()){
        // Find Available driver
        $driver_sql = "SELECT * FROM driver_info WHERE status = 'Available' LIMIT 1";
        $driver_result = $conn->query($driver_sql);
        $driver = $driver_result->fetch_assoc();

        // Find available vehicle that can accommodate number of passengers
        $vehicle_sql = "SELECT * FROM vehicle_data WHERE status= 'Available' AND seater >= " . $trip['no_passengers'] . " LIMIT 1";
        $vehicle_result = $conn->query($vehicle_sql);
        $vehicle = $vehicle_result->fetch_assoc();

        if ($driver && $vehicle){
            // Insert new trip
            $assign_sql = "INSERT INTO trips (ruvNO, driver_id, plate_no, trip_date) VALUES (" . $trip['ruvNO'] . ", 
            " . $driver['driver_id'] . ", " . $vehicle['plate_no'] . ", CURDATE())";
            $conn->query($assign_sql);

            // Update driver status
            $update_driver_sql = "UPDATE driver_info SET status = 'Unavailable' WHERE driver_id = " . $driver['driver_id'];
            $conn->query($update_driver_sql);

            // Update vehicle status
            $update_vehicle_sql = "UPDATE vehicle_data SET status = 'Unavailable' WHERE plate_no = " . $vehicle['plate_no'];
            $conn->query($update_vehicle_sql);
        
        } else {
            echo "No available driver or vehicle for trip " . $trip['ruvNO'] . " with " . $trip['no_passengers'] . " passengers.\n";
        }
    }
} else {
    echo "No unassigned trips found.\n";
}

$conn->close();

?>
