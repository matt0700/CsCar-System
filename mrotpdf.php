<?php
try {
    $con = new PDO("mysql:host=localhost;dbname=cscar_database","root","");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming you join ruv_table and trip_table on some common column, e.g., 'common_column'
    $query = "SELECT  mrot.mrot_id FROM mrot";
              
        
    
    $result = $con->prepare($query);
    $result->execute();

    if($result->rowCount()){
        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            ?>
            <tr>
                <td>
                    <a href="mrotappend.php?trip_id=<?php echo $row['mrot_id']; ?>">view online</a>
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