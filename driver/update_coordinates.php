<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'driver') {
    header("Location: ../driver_login.php");
    exit();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $driver_id = $_SESSION['driver_id'];

    // Database connection
    $username = "root";
    $password = "";
    $dbname = "cscar_database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE drivers SET latitude = ?, longitude = ? WHERE driver_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddi", $lat, $lng, $driver_id);

    if ($stmt->execute()) {
        echo "Coordinates saved successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
