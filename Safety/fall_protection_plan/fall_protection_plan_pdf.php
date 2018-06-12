<?php
	function fall_protection_plan_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_fall_protection_plan WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_fall_protection_plan` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);

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

	$html_weekly = '<h2>Fall Protection Plan</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="25%">Date</th><th width="25%">Job #</th><th width="25%">Worksite Location</th><th width="25%">Permit #</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$fields[1].'</td><td>'.$fields[2].'</td><td>'.$fields[3].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="20%">Client</th><th width="80%">Scope of Work</th></tr>
            <tr nobr="true"><td>'.$fields[4].'</td><td>'.$fields[5].'</td></tr>
            </table>';

    $html_weekly .= '<h4>Fall Hazards</h4>';

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Sharp Edges';
        if ($fields[6]=='Sharp Edges') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[1];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Unguarded Edges';
        if ($fields[7]=='Unguarded Edges') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[2];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Missing Guard Rails';
        if ($fields[8]=='Missing Guard Rails') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[3];
    //}


    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Obstruction Below';
        if ($fields[9]=='Obstruction Below') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[4];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Slippery Surfaces';
        if ($fields[10]=='Slippery Surfaces') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[5];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Ice';
        if ($fields[11]=='Ice') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[6];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Open Holes in Work Surface';
        if ($fields[12]=='Open Holes in Work Surface') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[7];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Wind Hazards';
        if ($fields[13]=='Wind Hazards') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[8];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Trip Hazards';
        if ($fields[14]=='Trip Hazards') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[9];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Loose Equipment or Tools';
        if ($fields[15]=='Loose Equipment or Tools') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[10];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Moving Equipment';
        if ($fields[16]=='Moving Equipment') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[11];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Other';
        if ($fields[17]=='Other') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= ' '.$fields[18];
        $html_weekly .= ' '.$fields_value[12];
    //}

    $html_weekly .= '<h4>Control Measures</h4>';

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Fall Arrest System';
        if ($fields[19]=='Fall Arrest System') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[13];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Travel Restraint System';
        if ($fields[20]=='Travel Restraint System') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[14];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Temporary Guard Rail';
        if ($fields[21]=='Temporary Guard Rail') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[15];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Temporary Open Covers';
        if ($fields[22]=='Temporary Open Covers') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[16];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Taglines for lowering equipment';
        if ($fields[23]=='Taglines for lowering equipment') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[17];
    //}
    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Man Basket';
        if ($fields[24]=='Man Basket') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[18];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Scaffolding';
        if ($fields[25]=='Scaffolding') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[19];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Man-lift';
        if ($fields[26]=='Man-lift') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[20];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Control Zone';
        if ($fields[27]=='Control Zone') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[21];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Tool Lanyards';
        if ($fields[28]=='Tool Lanyards') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[22];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Debris Netting';
        if ($fields[29]=='Debris Netting') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[23];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Lock Out / Tag Out';
        if ($fields[30]=='Lock Out / Tag Out') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[24];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Other';
        if ($fields[31]=='Other') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= ' '.$fields[32];
        $html_weekly .= '  '.$fields_value[25];
    //}

    $html_weekly .= '<h4>Equipment Inspection</h4>';
    $html_weekly .= "Has all personal fall protection equipment been inspected (pre-use) as per the manufacturer's specifications? : ".$fields[33].'<br>'.$fields[34];

    $html_weekly .= '<h4>Rescue Plan</h4>';
    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Man-lift';
        if ($fields[35]=='Man-lift') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[26];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Ladders';
        if ($fields[36]=='Ladders') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[27];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>On-site Rescue (Emergency Response Crew)';
        if ($fields[37]=='On-site Rescue (Emergency Response Crew)') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[28];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Local Emergency Response Available (Within 15 min.)';
        if ($fields[38]=='Local Emergency Response Available (Within 15 min.)') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[29];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>First Aid Attendants';
        if ($fields[39]=='First Aid Attendants') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[30];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Method of Transportation Available';
        if ($fields[40]=='Method of Transportation Available') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields_value[31];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Emergency Phone Contact #1';
        if ($fields[41]=='Emergency Phone Contact #1') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= ' '.$fields[42];
    //}

    //if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Emergency Phone Contact #2';
        if ($fields[42]=='Emergency Phone Contact #2') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= ' '.$fields[44];
    //}

    $html_weekly .= "<br><br>Has all emergency and rescue equipment been inspected (prior to work commencement) as per the manufacturer's specifications? : ".$fields[45].'<br>'.$fields[46];
    $html_weekly .= "<br>Have are all workers being trained in the safe use of fall protection equipment? : ".$fields[47].'<br>'.$fields[49];
    $html_weekly .= "<br>Have all affected workers been made aware of this plan? : ".$fields[50].'<br>'.$fields[52];

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

        $html_weekly .= '<td data-title="Email"><img src="fall_protection_plan/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('fall_protection_plan/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("fall_protection_plan/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




