<?php
// Include the database connection file
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $vehicle = $_POST['cars'];
    $issue = $_POST['issue'];
    $distance = 0.00; // Assuming distance is initially 0.00 km

    // Sanitize the table name (remove hyphens and replace them with underscores)
    $table_name = str_replace('-', '_', $vehicle);

    // Prepare the SQL statement
    $sql = "INSERT INTO $table_name (mileage_trip, fuel_trip, issue_trip) VALUES (?, ?, ?)";
    $stmt = $connect->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $connect->error);
    }
    $stmt->bind_param("ssd", $issue, $contact, $distance);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully in table $table_name";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $connect->close();
}
?>
