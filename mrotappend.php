<?php
use \setasign\Fpdi\Fpdi;

require_once 'fpdf186/fpdf.php';
require_once 'fpdi2/src/autoload.php';

$con = new PDO("mysql:host=localhost;dbname=cscar_database", "root", "");

// Check if a plate_no is selected, otherwise show the form
if (!isset($_GET['plate_no'])) {
    // Fetch all available plate_no from the vehicle table for the dropdown menu
    $vehicleQuery = "SELECT plate_no FROM vehicle_data";
    $vehicleResult = $con->prepare($vehicleQuery);
    $vehicleResult->execute();

    // Start output buffering to capturQe the form
    ob_start();
    
    // Display a dropdown menu for selecting plate_no
    echo '<form method="GET" action="">
            <label for="plate_no">Select Plate Number:</label>
            <select name="plate_no" id="plate_no">';
    while ($vehicleRow = $vehicleResult->fetch()) {
        echo '<option value="' . $vehicleRow['plate_no'] . '">' . $vehicleRow['plate_no'] . '</option>';
    }
    echo '  </select>
            <input type="submit" value="Generate MROT">
          </form>';

    // Output the buffered content (the form)
    ob_end_flush();
} else {
    $plate_no = $_GET['plate_no'];

    // Create a new instance of Fpdi
    $pdf = new Fpdi();

    // Get the page count
    $pageCount = $pdf->setSourceFile('mrot.pdf');

    // Iterate through all pages
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        // Import a page
        $templateId = $pdf->importPage($pageNo);
        $pdf->AddPage();
        // Use the imported page and adjust the page size
        $pdf->useTemplate($templateId, ['adjustPageSize' => true]);
    }

    // Fetch data from mrot table
    $mrotQuery = "SELECT mileage_trip, fuel_trip, submitted FROM mrot WHERE plate_no = :plate_no";
    $mrotResult = $con->prepare($mrotQuery);
    $mrotResult->execute([':plate_no' => $plate_no]);

    // Fetch total mileage from vehicle table
    $vehicleQuery = "SELECT mileage FROM vehicle_data WHERE plate_no = :plate_no";
    $vehicleResult = $con->prepare($vehicleQuery);
    $vehicleResult->execute([':plate_no' => $plate_no]);
    $vehicleRow = $vehicleResult->fetch();

    // Initialize total variables
    $totalMileageTrip = 0;
    $totalFuelTrip = 0;

    // Set font to Arial, size 8
    $pdf->SetFont('Arial', '', 8);

    // Add the data to the PDF
    if ($mrotResult->rowCount() != 0) {
        $pdf->SetXY(40, 31); 
        $pdf->Write(10, $plate_no);
        $pdf->SetXY(25, 176);
        $pdf->Write(10, $vehicleRow['mileage']);

        // Initial Y positions for the data
        $mileageTripYPosition = 50;
        $fuelTripYPosition = 50;
        $submittedYPosition = 50;

        while ($mrotRow = $mrotResult->fetch()) {
            $pdf->SetXY(27, $mileageTripYPosition);
            $pdf->Write(10, $mrotRow['mileage_trip']);
            $mileageTripYPosition += 5; // Adjust as needed

            $pdf->SetXY(39, $fuelTripYPosition);
            $pdf->Write(10, $mrotRow['fuel_trip']);
            $fuelTripYPosition += 5; // Adjust as needed

            $pdf->SetXY(10, $submittedYPosition);
            $pdf->Write(10,$mrotRow['submitted']);
            $submittedYPosition += 5; // Adjust as needed

            // Accumulate totals
            $totalMileageTrip += $mrotRow['mileage_trip'];
            $totalFuelTrip += $mrotRow['fuel_trip'];
        }

        // Add totals to the PDF
        
        $pdf->SetXY(39, 176); // Adjust Y position as needed
        $pdf->Write(10, $totalFuelTrip);
    }

    // Output the PDF
    $pdf->Output();
}
?>