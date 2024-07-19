<?php
// Include database connection
include "../connection.php";

if (isset($_GET['ruvNO'])) {
    $ruvNO = $_GET['ruvNO'];

    // Query to fetch RUV details
    $sql = "SELECT * FROM ruv_table WHERE ruvNO = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $ruvNO);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<div class='row'>";
        echo "<div class='col-md-6'>";
        echo "<p><strong>RUV NO:</strong> " . $row['ruvNO'] . "</p>";
        echo "<p><strong>Pickup Point:</strong> " . $row['pickup_point'] . "</p>";
        echo "<p><strong>Destination:</strong> " . $row['destination'] . "</p>";
        echo "<p><strong>Trip Date:</strong> " . $row['trip_date'] . "</p>";
        echo "<p><strong>Preferred Time:</strong> " . $row['pref_time'] . "</p>";
        echo "<p><strong>No. of Passengers:</strong> " . $row['no_passengers'] . "</p>";
        echo "<p><strong>ETA Destination:</strong> " . $row['eta_destination'] . "</p>";
        echo "</div>";
        echo "<div class='col-md-6'>";
        echo "<p><strong>Requesting Official:</strong> " . $row['req_official'] . "</p>";
        echo "<p><strong>Name of Passengers:</strong> " . $row['name_passengers'] . "</p>";
        echo "<p><strong>Reason:</strong> " . $row['reason'] . "</p>";
        echo "<p><strong>Email:</strong> " . $row['email'] . "</p>";
        echo "<p><strong>Submitted:</strong> " . $row['submitted'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['status'] . "</p>";
        echo "</div>";
        echo "</div>";
    } else {
        echo "<p>No details found for the given RUV number.</p>";
    }
    $stmt->close();
} else {
    echo "<p>RUV number not provided.</p>";
}

$connect->close();
?>
