<?php
$con = new PDO("mysql:host=localhost;dbname=cscar_database","root","");
$query = "SELECT ruvNO FROM ruv_table";
$result = $con->prepare($query);
$result->execute();
if($result->rowCount()){
    while($ruvNO = $result->fetch())
    {
        ?>
        <tr>
            <td>
                <a href="ruvappend.php?ruvNO=<?php echo $ruvNO['ruvNO'];
                ?>">view online</a>
            </td>
            <td>
                <a href="ruvappend.php?ruvNO=<?php echo $ruvNO['ruvNO'];
                ?>">Download Now</a>
            </td>
        </tr>
        <?php
    }
}else{
    echo "<br><br>Data Not Found";
}
?>
