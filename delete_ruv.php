<?php
include "connection.php"; // Make sure the path is correct
$ruvNO = intval($_GET['ruvNO']);

$sql = "DELETE FROM ruv_table WHERE ruvNO = $ruvNO";
if (mysqli_query($connect, $sql)) {
    echo "success";
} else {
    echo "error";
}

mysqli_close($connect);
?>
