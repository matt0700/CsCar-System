<?php

include 'connection.php';

$trips_sql = 'SELECT * FROM ruv_table WHERE ruvNO NOT IN (SELECT ruvNO FROM trips)';
$trips_result = $connect->query($trips_sql);

if ($trips_result->num_rows > 0) {
    while ($trip = $trips_result->fetch_assoc()) {
        // Find Available driver
        $driver_sql = "SELECT * FROM drivers WHERE driver_status = 'Available' LIMIT 1";
        $driver_result = $connect->query($driver_sql);

        if ($driver_result->num_rows > 0) {
            $driver = $driver_result->fetch_assoc();

            // Attempt to find an available vehicle that can accommodate number of passengers
            $vehicle_found = false;
            $vehicle_sql = "SELECT * FROM vehicle_data WHERE car_status = 'Available' AND seater >= ? LIMIT 1";
            $stmt = $connect->prepare($vehicle_sql);
            $stmt->bind_param("i", $trip['no_passengers']);
            $stmt->execute();
            $vehicle_result = $stmt->get_result();

            while ($vehicle = $vehicle_result->fetch_assoc()) {
                // Check if vehicle can accommodate passengers
                if ($vehicle['seater'] >= $trip['no_passengers']) {
                    $vehicle_found = true;
                    break;
                }
            }
            $stmt->close();

            if ($vehicle_found) {
                // Insert new trip
                $assign_sql = "INSERT INTO trips (ruvNO, driver_id, plate_no, trip_date) VALUES (?, ?, ?, CURDATE())";
                $stmt = $connect->prepare($assign_sql);
                $stmt->bind_param("iis", $trip['ruvNO'], $driver['driver_id'], $vehicle['plate_no']);
                $stmt->execute();
                $stmt->close();

                // Update driver status
                $update_driver_sql = "UPDATE drivers SET driver_status = 'Unavailable' WHERE driver_id = ?";
                $stmt = $connect->prepare($update_driver_sql);
                $stmt->bind_param("i", $driver['driver_id']);
                $stmt->execute();
                $stmt->close();

                // Update vehicle status
                $update_vehicle_sql = "UPDATE vehicle_data SET car_status = 'Unavailable' WHERE plate_no = ?";
                $stmt = $connect->prepare($update_vehicle_sql);
                $stmt->bind_param("s", $vehicle['plate_no']);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<script>alert('No suitable vehicle available for trip " . $trip['ruvNO'] . " with " . $trip['no_passengers'] . " passengers.');</script>";
                echo "<script>window.location.href = 'pages/index.php';</script>";
            }
        } else {
            echo "<script>alert('No available driver for trip " . $trip['ruvNO'] . " with " . $trip['no_passengers'] . " passengers.');</script>";
            echo "<script>window.location.href = 'pages/index.php';</script>";
        }
    }
} else {
    echo "<script>alert('No unassigned trips found.');</script>";
    echo "<script>window.location.href = 'pages/index.php';</script>";
}

$conn->close();

?>
