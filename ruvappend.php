<?php
use \setasign\Fpdi\Fpdi;

require_once 'fpdf186/fpdf.php';
require_once 'fpdi2/src/autoload.php';

$pdf = new Fpdi();

// get the page count
$pageCount = $pdf->setSourceFile('ruv_temp.pdf');

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
                $pdf->SetXY(55, 115); 
                $pdf->Write(20, $tripRow['plate_no']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(55, 119); 
                $pdf->Write(20, $tripRow['driver_name']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(134, 107);
                $pdf->Write(20, $tripRow['ruvNO']."-RUV-".$tripRow['trip_date']);

                // Display up to 8 names from the name_passengers field
                $names = explode(',', $tripRow['name_passengers']);
                $maxNames = 8;
                $x1 = 12;
                $x2 = 75; // X coordinate for the 5th name onwards
                $y = 61;
                $yIncrement = 5; // Adjust as necessary to avoid overlapping
                $namesToDisplay = array_slice($names, 0, $maxNames);

                foreach ($namesToDisplay as $index => $name) {
                    $pdf->SetFont('Arial');
                    $x = ($index < 4) ? $x1 : $x2; // Use different X coordinates
                    $pdf->SetXY($x, $y + (($index % 4) * $yIncrement)); // Adjust Y coordinate
                    $pdf->Write(20, trim($name));
                }

                $pdf->SetFont('Arial');
                $pdf->SetXY(83, 21);
                $pdf->Write(20, $tripRow['pickup_point']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(83, 26);
                $pdf->Write(20, $tripRow['destination']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(83, 24);
                $pdf->Write(4, $tripRow['trip_date']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(167, 21);
                $pdf->Write(20, $tripRow['pref_time']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(167, 28);
                $pdf->Write(20, $tripRow['eta_destination']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(15, 21);
                $pdf->Write(20, $tripRow['req_official']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(29, 50);
                $pdf->Write(20, $tripRow['reason']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(29, 44);
                $pdf->Write(20, $tripRow['submitted']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(55, 111);
                $pdf->Write(20, $tripRow['make_series_type']);

                $pdf->Output('', $tripRow['ruvNO'].'-RUV-'.$tripRow['trip_date'].'.pdf', false);
            }
        }
    }
}

$pdf->Output();
?>