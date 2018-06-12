<?php
	function vehicle_damage_report_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_vehicle_damage_report WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_vehicle_damage_report` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
    $fields = explode('**FFM**', $get_field_level['fields']);

	class MYPDF extends TCPDF {

        //Page header
        public function Header() {
            if(PDF_LOGO != '') {
                $image_file = 'download/'.PDF_LOGO;
                $this->Image($image_file, 10, 10, 30, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }

            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = '<p style="text-align:right;">'.PDF_HEADER.'</p>';
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
            $this->SetY(-30);
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $footer_text = PDF_FOOTER;
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    if(PDF_LOGO != '') {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 55, PDF_MARGIN_RIGHT);
    } else {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    }
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, 40);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);

	$html_weekly = '<h2>Vehicle Damage Report</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="25%">Date</th><th width="25%">Employee Name</th><th width="25%">Home phone</th><th width="25%">Cell Phone</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$fields[0].'</td><td>'.$fields[1].'</td><td>'.$fields[2].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="80%">Address</th><th width="20%">D.O.B.</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[3].'</td><td>'.$fields[4].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="25%">Vehicle Make</th><th width="25%">Model</th><th width="25%">Year</th><th width="25%">Color</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[5].'</td><td>'.$fields[6].'</td><td>'.$fields[7].'</td><td>'.$fields[8].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Driver License #</th><th width="40%">License Plate #</th><th width="20%">Province</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[9].'</td><td>'.$fields[10].'</td><td>'.$fields[11].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">VIN #</th><th width="50%">Damage Estimate: $</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[12].'</td><td>'.$fields[13].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Description of Damage</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[14].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Insurance Company</th><th width="40%">Policy #</th><th width="20%">Expiry Date</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[15].'</td><td>'.$fields[16].'</td><td>'.$fields[17].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Occurrence Date</th><th width="40%">Time</th><th width="20%">Date Reported</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[18].'</td><td>'.$fields[19].'</td><td>'.$fields[20].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Road Conditions</th><th width="50%">Weather Conditions</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[21].'</td><td>'.$fields[22].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Location of incident</th><th width="50%">Police/RCMP Collision Report #</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[23].'</td><td>'.$fields[24].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Collision involved</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[25].' '.$fields[26].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Any injuries as a result?</th><th width="50%">If yes, what and to whom</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[27].'</td><td>'.$fields[28].'</td></tr>';
    $html_weekly .= '</table>';

    $html_weekly .= '<h4>Other Driver</h4>';
	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Name</th><th width="25%">Home phone</th><th width="25%">Cell Phone</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[29].'</td><td>'.$fields[30].'</td><td>'.$fields[31].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Address</th><th width="50%">D.O.B.</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[32].'</td><td>'.$fields[33].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="25%">Vehicle Make</th><th width="25%">Model</th><th width="25%">Year</th><th width="25%">Color</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[34].'</td><td>'.$fields[35].'</td><td>'.$fields[36].'</td><td>'.$fields[37].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Driver License #</th><th width="40%">License Plate #</th><th width="20%">Province</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[38].'</td><td>'.$fields[39].'</td><td>'.$fields[40].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">VIN #</th><th width="50%">Damage Estimate: $</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[41].'</td><td>'.$fields[42].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Description of Damage</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[43].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Insurance Company</th><th width="40%">Policy #</th><th width="20%">Expiry Date</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[44].'</td><td>'.$fields[45].'</td><td>'.$fields[46].'</td></tr>';
    $html_weekly .= '</table>';

    $html_weekly .= '<h4>Witnesses</h4>';
	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Name</th><th>Address</th><th>Phone</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[47].'</td><td>'.$fields[48].'</td><td>'.$fields[49].'</td></tr>';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Name</th><th>Address</th><th>Phone</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[50].'</td><td>'.$fields[51].'</td><td>'.$fields[52].'</td></tr>';
    $html_weekly .= '</table>';

    $html_weekly .= '<h4>Description of events leading up to accident</h4>' . html_entity_decode($desc);

    $html_weekly .= 'I was ' . $fields[53];

    $html_weekly .= '<h4>Diagram of Accident scene</h4><img src="vehicle_damage_report/download/diagram_'.$safetyid.'.png" width="150" height="70" border="0" alt=""><br><br><br><br><br><br>';

	$sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';

        // avs_near_miss = form name

        $html_weekly .= '<td data-title="Email"><img src="vehicle_damage_report/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('vehicle_damage_report/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("vehicle_damage_report/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




