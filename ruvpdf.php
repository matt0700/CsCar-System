<?php
try {
    $con = new PDO("mysql:host=localhost;dbname=cscar_database","root","");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming you join ruv_table and trip_table on some common column, e.g., 'common_column'
    $query = "SELECT  trips.trip_id FROM trips";
              
        
    
    $result = $con->prepare($query);
    $result->execute();

    if($result->rowCount()){
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            ?>
            <tr>
                <td>
                    <a href="ruvappend.php?trip_id=<?php echo $row['trip_id']; ?>">view online</a>
                </td>
                
            </tr>
            <tr>
                <td>
                    <a href="ticketappend.php?trip_id=<?php echo $row['trip_id']; ?>">view trip ticket</a>
                </td>
                
            </tr>
            <?php
        }
    } else {
        echo "<br><br>Data Not Found";
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
