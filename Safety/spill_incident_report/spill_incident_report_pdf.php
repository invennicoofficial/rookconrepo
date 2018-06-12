<?php
	function spill_incident_report_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_spill_incident_report WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_spill_incident_report` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$$desc = $get_field_level['$desc'];
	$$desc1 = $get_field_level['$desc1'];
	$$desc2 = $get_field_level['$desc2'];
	$$desc3 = $get_field_level['$desc3'];
	$$desc4 = $get_field_level['$desc4'];
	$$desc5 = $get_field_level['$desc5'];
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

	$html_weekly = '<h2>Spill Incident Report</h2>'; // Form nu heading

	$html_weekly .= 'Date : '.$today_date.'<br>
	Facility location and type : '.$fields[1].'<br>
	Individual Reporting the spill / incident : '.$fields[2].'<br>
	Company representative contacted : '.$fields[3].'<br>
	Date and time : '.$fields[4].'<br>
	Customer representative contacted : '.$fields[5].'<br>
	Date and time : '.$fields[6].'<br>';

    $html_weekly .= "<br>Regulatory agency notified (name, date, time)<br>".html_entity_decode($desc);
	$html_weekly .= "<br>Was the facility Emergency Response Plan activated? Provide details<br>".html_entity_decode($desc1);

	$html_weekly .= 'Date and time of spill / incident : '.$fields[7].'<br>';

	$html_weekly .= '<h4>Type of spill/Incident</h4>';

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Gas / Vapour';
        if ($fields[8]=='Gas / Vapour') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[9];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Liquid';
        if ($fields[10]=='Liquid') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[11];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Sludge';
        if ($fields[12]=='Sludge') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[13];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Solid';
        if ($fields[14]=='Solid') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[15];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Other';
        if ($fields[16]=='Other') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[17];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Fire';
        if ($fields[18]=='Fire') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[19];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Explosion';
        if ($fields[20]=='Explosion') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[21];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Combination / multiple release';
        if ($fields[22]=='Combination / multiple release') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[23];
    //}

	$html_weekly .= "Specify location of spill / incident (include where it traveled) : ".$fields[24].'<br>'.$fields[25];

	$html_weekly .= 'Duration of  spill / incident (min. / hrs. / days) : '.$fields[26].'<br>';
	$html_weekly .= 'Estimated volume of material lost : '.$fields[27].'<br>';

	$html_weekly .= "<br>Cause(s) of spill / incident<br>".html_entity_decode($desc2);
	$html_weekly .= "<br>Action taken to contain the spill<br>".html_entity_decode($desc3);
	$html_weekly .= "<br>Action taken to clean up the spill<br>".html_entity_decode($desc4);
	$html_weekly .= "<br>Follow up action required (if applicable)<br>".html_entity_decode($desc5);

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

        $html_weekly .= '<td data-title="Email"><img src="spill_incident_report/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('spill_incident_report/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("spill_incident_report/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




