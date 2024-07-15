<?php
use \setasign\Fpdi\Fpdi;

require_once 'fpdf186/fpdf.php';
require_once 'fpdi2/src/autoload.php';

$pdf = new Fpdi();

// get the page count
$pageCount = $pdf->setSourceFile('trip_ticket.pdf');

// iterate through all pages
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    // import a page
    $templateId = $pdf->importPage($pageNo);
    $pdf->AddPage();
    // use the imported page and adjust the page size
    $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

    $con = new PDO("mysql:host=localhost;dbname=cscar_database", "root", "");

    // Fetch data from trips table
    if (isset($_GET['trip_id'])) {
        $trip_id = $_GET['trip_id'];
        $tripquery = "SELECT trips.*, vehicle_data.make_series_type, drivers.driver_name, ruv_table.submitted,ruv_table.name_passengers,ruv_table.pickup_point,ruv_table.destination,ruv_table.trip_date,ruv_table.pref_time,ruv_table.eta_destination,ruv_table.req_official,ruv_table.reason FROM trips 
                      JOIN drivers ON trips.driver_id = drivers.driver_id 
                      JOIN ruv_table ON trips.ruvNO = ruv_table.ruvNO 
                      JOIN vehicle_data ON trips.plate_no = vehicle_data.plate_no
                      WHERE trips.trip_id = :trip_id";
        $tripresult = $con->prepare($tripquery);
        $tripresult->execute([':trip_id' => $trip_id]);

        if ($tripresult->rowCount() != 0) {
            while ($tripRow = $tripresult->fetch()) {
                $pdf->SetFont('Arial');
                $pdf->SetXY(100, 31); 
                $pdf->Write(20, $tripRow['plate_no']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(100, 126); 
                $pdf->Write(20, $tripRow['driver_name']);


                $pdf->SetFont('Arial');
                $pdf->SetXY(154, 33);
                $pdf->Write(4, $tripRow['trip_date']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(29, 84);
                $pdf->Write(20, $tripRow['reason']);

                $pdf->Output('',$tripRow['ruvNO'].'-TT-'.$tripRow['trip_date'].'.pdf', false);


                
            }
        }
    }
}

$pdf->Output();
?>