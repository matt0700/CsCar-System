<?php
include "connection.php"; // Make sure the path is correct

$ruvNO = intval($_GET['ruvNO']);

// Update the status to 'denied' instead of deleting the row
$sql = "UPDATE ruv_table SET status = 'denied' WHERE ruvNO = $ruvNO";
if (mysqli_query($connect, $sql)) {
    echo "success";
} else {
    echo "error";
}

mysqli_close($connect);
?>
