<?php
include "connection.php";
$ruvNO = intval($_GET['ruvNO']);

$sql = "SELECT * FROM ruv_table WHERE ruvNO = $ruvNO";
$result = mysqli_query($connect, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "<div class='row'>";
    echo "<div class='col-md-6'>";
    echo "<p><strong>Pick-up Point:</strong> " . htmlspecialchars($row['pickup_point']) . "</p>";
    echo "<p><strong>Destination:</strong> " . htmlspecialchars($row['destination']) . "</p>";
    echo "<p><strong>Trip Date:</strong> " . htmlspecialchars($row['trip_date']) . "</p>";
    echo "<p><strong>Preferred Pick-up Time:</strong> " . htmlspecialchars($row['pref_time']) . "</p>";
    echo "<p><strong>No. of Passengers:</strong> " . htmlspecialchars($row['no_passengers']) . "</p>";
    echo "</div>";
    
    echo "<div class='col-md-6'>";
    echo "<p><strong>ETA Destination:</strong> " . htmlspecialchars($row['eta_destination']) . "</p>";
    echo "<p><strong>Requesting Official:</strong> " . htmlspecialchars($row['req_official']) . "</p>";
    echo "<p><strong>Name of Passengers:</strong> " . htmlspecialchars($row['name_passengers']) . "</p>";
    echo "<p><strong>Reason:</strong> " . htmlspecialchars($row['reason']) . "</p>";
    echo "</div>";
    
    echo "</div>";
    
} else {
    echo "No details found for RUV No: $ruvNO";
}

mysqli_close($connect);
?>
