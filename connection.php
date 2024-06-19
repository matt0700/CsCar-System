<?php

  $req_official = $_POST['req_official'];
  $no_passengers = $_POST['no_passengers'];
  $name_passengers = $_POST['name_passengers'];
  $pickup_point = $_POST['pickup_point'];
  $destination = $_POST['destination'];
  $trip_date = $_POST['trip_date'];
  $pref_time = $_POST['pref_time'];
  $eta_destination = $_POST['eta_destination'];
  $reason = $_POST['reason'];

  $conn = new mysqli('localhost','root','','cscar_database');
  if ($conn->connect_error){
    die('Connection Failed: '.$conn->connect_error);
  }else{
    $stmt = $conn->prepare("insert into ruv_table(req_official,no_passengers,name_passengers,pickup_point,destination,trip_date,pref_time,eta_destination,reason)
        values(?,?,?,?,?,?,?,?,?)");
    $stmt ->bind_param('ssisiissss',$req_official,$no_passengers,$name_passengers,$pickup_point,$destination,$trip_date,$pref_time,$eta_destination,$reason);
    $stmt->execute();
    echo "RUV submitted";
    $stmt->close();
    $conn->close();
  }

?>




<!--$query = "INSERT INTO ruv_table VALUES('','$req_official','$no_passengers','$name_passengers','$pickup_point',' $destination','$trip_date','$pref_time','$eta_destination','$reason')";
  mysqli_query($conn,$query);
  echo
  "
  <script> alert('RUV has been submitted!);(</script>

  ";-->