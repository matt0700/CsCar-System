<?php

include 'connection.php';
// Check connection
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $plate_no = $_POST['cars']; // Assuming 'cars' corresponds to 'plate_no'
    $issue = $_POST['issue'];
    $distance = $_POST['distance']; // Get distance value

    // Escape inputs to prevent SQL injection
    $plate_no = mysqli_real_escape_string($connect, $plate_no);
    $issue = mysqli_real_escape_string($connect, $issue);
    $distance = mysqli_real_escape_string($connect, $distance);

    // Insert into database
    $sql = "INSERT INTO mrot (plate_no, issue, mileage_trip, fuel_trip) VALUES ('$plate_no', '$issue', '$distance', '$distance')";

    if (mysqli_query($connect, $sql)) {
        $message = "Record inserted successfully.";
    } else {
        $message = "Error inserting record: " . mysqli_error($connect);
    }
}
echo "<script>alert('$message'); window.location.href = 'pages/drivermap.php';</script>";

// Close connection
mysqli_close($connect);

?>

