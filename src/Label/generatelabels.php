<?php
define('FPDF_FONTPATH',"./font/");
require 'vendor/autoload.php';
require_once('tfpdf.php');
require('PDF_Label.php');

// Set alternate county here
$alternate_county = 'Botetourt,VA';

// Set QSOs per label
$qso_per_label = 7;

$pdf = new PDF_Label('5263');
$pdf->AddPage();
$fontName = 'DejaVuSans';
$pdf->AddFont($fontName,'','DejaVuSansMono.ttf',true);
$pdf->AddFont($fontName,'B','DejaVuSansMono.ttf',true);
$pdf->SetFont($fontName,'',6);
$con = mysqli_connect("localhost","USERNAME","PASSWORD","DATABASE");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql = "SELECT c.COL_PRIMARY_KEY, c.COL_CALL, c.COL_TIME_ON, c.COL_MODE, c.COL_BAND, c.COL_RST_SENT, p.station_gridsquare, p.station_cnty, p.state, p.station_id FROM TABLE_HRD_CONTACTS_V01 c INNER JOIN station_profile p ON c.station_id=p.station_id WHERE c.COL_QSL_SENT LIKE 'R' ORDER BY c.COL_CALL,c.COL_TIME_ON;";
$result = $con->query($sql);

function finalizeData($pdf, $current_callsign, &$preliminaryData, $qso_per_label, &$hasNonAlternateCounty) {
    $tableData = [];
    $count_qso = 0;

    foreach ($preliminaryData as $key => $row) {
        $rowData = [
            'Date/Time UTC' => $row['Date/Time UTC'],
            'Band' => $row['Band'],
            'Mode' => $row['Mode'],
            'RST' => $row['RST'],
        ];
        if ($hasNonAlternateCounty) {
            $rowData['My Grid'] = $row['My Grid'];
            $rowData['My County'] = $row['My County'];
        }
        $tableData[] = $rowData;
        $count_qso++;

        if($count_qso == $qso_per_label){
            generateLabel($pdf, $current_callsign, $tableData);
            $tableData = []; // reset the data
            $count_qso = 0;  // reset the counter
        }
        unset($preliminaryData[$key]);
    }
    // generate label for remaining QSOs
    if($count_qso > 0){
        generateLabel($pdf, $current_callsign, $tableData);
        $preliminaryData = []; // reset the data
    }
}

function generateLabel($pdf, $current_callsign, $tableData){
    $builder = new \AsciiTable\Builder();
    $builder->addRows($tableData);
    $text = "Confirming QSO with ";
    $text .= $current_callsign;
    $text .= "\n";
    $text .= $builder->renderTable();
    $text .= "\nThanks for the QSO";
    $pdf->Add_Label($text);
}

if ($result->num_rows > 0) {
    $current_callsign = '';
    $preliminaryData = [];
    $hasNonAlternateCounty = false;

    // output data of each row
    while($row = $result->fetch_assoc()) {
        if ($row['COL_CALL'] !== $current_callsign) {
            while (!empty($preliminaryData)) {
                finalizeData($pdf, $current_callsign, $preliminaryData, $qso_per_label, $hasNonAlternateCounty);
            }
            $current_callsign = $row['COL_CALL'];
            $hasNonAlternateCounty = false;
        }

        $time = strtotime($row["COL_TIME_ON"]);
        $myFormatForView = date("Y-m-y H:i", $time);

        $band = $row['COL_BAND'];
        $mode = $row['COL_MODE'];
        $rst = $row['COL_RST_SENT'];
        $grid = $row['station_gridsquare'];
        $county = $row['station_cnty'] . ',' . $row['state'];

        if ($county !== $alternate_county) {
            $hasNonAlternateCounty = true;
        }

        // Add the row of data to the preliminary data
        $preliminaryData[] = [
            'Date/Time UTC' => $myFormatForView,
            'Band' => $band,
            'Mode' => $mode,
            'RST' => $rst,
            'My Grid' => $grid,
            'My County' => $county,
        ];
    }

    while (!empty($preliminaryData)) {
        finalizeData($pdf, $current_callsign, $preliminaryData, $qso_per_label, $hasNonAlternateCounty);
    }
} else {
    echo "0 results";
}

$pdf->Output();

?>
