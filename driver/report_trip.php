<?php

$hostname = "localhost";
$username = "root";
$password = "";
$databaseName = "cscar_database";

// Establish MySQLi connection
$connect = mysqli_connect($hostname, $username, $password, $databaseName);

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

    switch ($plate_no) {
        case 'SAA-9865':
            $fuel_trip = $distance / 8.5; 
            break;
        
        case 'SAA-9866':
            $fuel_trip = $distance / 8.5; 
            break;
        
        case 'SFY-477':
            $fuel_trip = $distance / 8; 
            break;
        
        case 'SFY-488':
            $fuel_trip = $distance / 8;
            break;
        
        case 'SHZ-133':
            $fuel_trip = $distance /11;
            break;

        case 'SJH-967':
            $fuel_trip = $distance / 9.2; 
            break;
            
        case 'SJH-977':
            $fuel_trip = $distance / 11; 
            break;
            
        case 'SJP-285':
            $fuel_trip = $distance / 11; 
            break;
            
        case 'SJP-286':
            $fuel_trip = $distance / 11;
            break;
            
        case 'U9-D041':
            $fuel_trip = $distance /8;
            break;

        case 'Z4T-867':
            $fuel_trip = $distance /9;
            break;

         case 'Z5G-191':
             $fuel_trip = $distance / 11;
             break;
        

        // Add more cases as needed for each plate number
        default:
            // Default calculation if plate number doesn't match any case
            $fuel_trip = $distance / 20; // Example default calculation
            break;
    }

    // Insert into database
    $sql = "INSERT INTO mrot (plate_no, issue, mileage_trip, fuel_trip) VALUES ('$plate_no', '$issue', '$distance', '$fuel_trip')";
    $sql2 = "UPDATE vehicle_data SET mileage = mileage + '$distance' WHERE plate_no = '$plate_no'";
    

    if (mysqli_query($connect, $sql, $sql2)) {
        $message = "Record inserted successfully.";
    } else {
        $message = "Error inserting record: " . mysqli_error($connect);
    }
}
echo "<script>alert('$message'); window.location.href = 'pages/drivermap.php';</script>";

// Close connection
mysqli_close($connect);

?>

