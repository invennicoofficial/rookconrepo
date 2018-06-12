<?php
	function emergency_response_transportation_plan_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_emergency_response_transportation_plan WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_emergency_response_transportation_plan` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc'];
	$desc2 = $get_field_level['desc'];
	$desc3 = $get_field_level['desc'];
	$desc4 = $get_field_level['desc'];
	$all_task = $get_field_level['all_task'];
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

	$html_weekly = '<h2>Emergency Response Transportation Plan</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Date</th><th width="50%">Office Contact</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$fields[1].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Client</th><th width="50%">Type of Work</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[2].'</td><td>'.$fields[3].'</td></tr>';

	$html_weekly .= '</table>';



	$html_weekly .= '<h4>Location</h4>' . html_entity_decode($desc);

	$html_weekly .= '<h4>File Number</h4>' . html_entity_decode($desc1);


	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Estimated Travel Time (one-way)</th><th width="50%">Estimated duration of work</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[4].'</td><td>'.$fields[5].'</td></tr>';

	$html_weekly .= '</table>';



	$html_weekly .= '<h4>Route(s) to site(s)</h4>' . html_entity_decode($desc2);

	$html_weekly .= '<h4>Call-In Time(S)</h4>' . html_entity_decode($desc3);

    $html_weekly .= "Are you staying out of town for the night? : ".$fields[6].'<br>If yes, where : '.$fields[7];


	$html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="40%">Vehicle make/model</th><th width="20%">Color</th><th width="40%">License Plate#</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[8].'</td><td>'.$fields[9].'</td><td>'.$fields[10].'</td></tr>';

	$html_weekly .= '</table>';

    $html_weekly .= "<br>Vehicle Type : ".$fields[11]."  ".$fields[12];

	$html_weekly .= "<br><br>Is there cellular coverage in the area? : ".$fields[13]." : ".$fields[14]."<br>Are you taking another means of communication? : ".$fields[15]." : ".$fields[16];

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Cell Phone #';
        if ($fields[17]=='Cell Phone #') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[18];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Truck Phone #';
        if ($fields[19]=='Truck Phone #') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[20];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Satellite Phone #';
        if ($fields[21]=='Satellite Phone #') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[22];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Other #';
        if ($fields[23]=='Other #') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[24];
    //}


	$html_weekly .= '<h4>Safety Equipment(check all that are available in the vehicle)</h4>';

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>AB#2 First aid kit';
        if ($fields[25]=='AB#2 First aid kit') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[26];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Fire extinguisher';
        if ($fields[27]=='Fire extinguisher') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[28];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Roadside flare kit';
        if ($fields[29]=='Roadside flare kit') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[30];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Tow rope / chains';
        if ($fields[31]=='Tow rope / chains') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[32];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Survival kit';
        if ($fields[33]=='Survival kit') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[34];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>GPS unit';
        if ($fields[35]=='GPS unit') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[36];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Bear spray/bangers';
        if ($fields[37]=='Bear spray/bangers') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[38];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Emergency phone list';
        if ($fields[39]=='Emergency phone list') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[40];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Other';
        if ($fields[41]=='Other') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[42];
    //}

	$html_weekly .= '<h4>Emergency Transportation</h4>';
	$html_weekly .= '<br>If you are within 40 minutes of the nearest medical facility, call 911 or local emergency services.';
	$html_weekly .= '<br>Provide emergency personnel with your exact location, the nature of the injury, and the condition of the victim.';
	$html_weekly .= '<br>Contact your supervisor to inform him or her of the situation and that emergency assistance has been summoned.';
	$html_weekly .= '<br>If your are more than 40 minutes from the nearest medical facility, contact your supervisor to arrange alternative transportation (air ambulance, other emergency services).';
	$html_weekly .= '<br>Keep the victim comfortable as best you can while awaiting medical assistance.';
	$html_weekly .= '<br>Go with or follow medical personnel to the medical facility.  You will be the liaison between the victim, the medics and the office.';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Risk Category</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[43].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
					<th>Time of Call-in</th>
                    <th>Initials</th>
                    <th>Changes to Plan?</th>';

    $all_task_each = explode('**##**',$all_task);

    $total_count = mb_substr_count($all_task,'**##**');
    for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
                    $plan = $task_item[2];
					$hazard1 = $task_item[3];
                    if($task != '') {
                        $html_weekly .= '<tr>';
                        $html_weekly .= '<td data-title="Email">' . $task . '</td>';
                        $html_weekly .= '<td data-title="Email">' . $hazard . '</td>';
                        $html_weekly .= '<td data-title="Email">' . $plan." : ".$hazard1. '</td>';
                        $html_weekly .= '</tr>';
                    }
                }
    $html_weekly .= '</table>';

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

        $html_weekly .= '<td data-title="Email"><img src="emergency_response_transportation_plan/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('emergency_response_transportation_plan/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("emergency_response_transportation_plan/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>
















