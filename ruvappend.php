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
    
    

    $con = new PDO("mysql:host=localhost;dbname=cscar_database","root","");
    if (isset($_GET['ruvNO']))
    {
        $ruvNO = $_GET['ruvNO'];
        $query = "SELECT * FROM ruv_table WHERE ruvNO='$ruvNO'";
        $result = $con->prepare($query);
        $result->execute();
        if($result->rowCount()!=0)
        {
            while($ruvNO = $result->fetch())
            {
                $pdf->SetFont('Arial');
                $pdf->SetXY(83,21);
                $pdf->Write(20,$ruvNO['pickup_point']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(83,26);
                $pdf->Write(20,$ruvNO['destination']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(173,11);
                $pdf->Write(4,$ruvNO['trip_date']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(83,24);
                $pdf->Write(4,$ruvNO['trip_date']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(167,21);
                $pdf->Write(20,$ruvNO['pref_time']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(167,28);
                $pdf->Write(20,$ruvNO['eta_destination']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(15,21);
                $pdf->Write(20,$ruvNO['req_official']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(12,61);
                $pdf->Write(20,$ruvNO['name_passengers']);

                $pdf->SetFont('Arial');
                $pdf->SetXY(29,50);
                $pdf->Write(20,$ruvNO['reason']);
                
                
               
               
                

            }

        }

        
    }



    }

    $pdf->Output();






?>


/* $pdf->AddPage();
    $pdf->SetFont("Arial","B",16);
    $pdf->setTextColor(252,3,3);
    $pdf->Cell(200,20,"Betlog", "0","1","C");

    $pdf->setLeftMargin(30);
    $pdf->setTextColor(0,0,0);

    $pdf->Cell(20,10,"No","1","0","C");
    $pdf->Cell(20,10,"Name","1","0","C");
    $pdf->Cell(20,10,"Age","1","0","C");
    $pdf->Cell(20,10,"Salary","1","1","C");*/