<?php
	function confined_space_entry_pre_entry_checklist_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_confined_space_entry_pre_entry_checklist WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_confined_space_entry_pre_entry_checklist` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
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

	$html_weekly = '<h2>Confined Space Entry Pre Entry Checklist</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="34%">Date</th><th width="33%">Job Number</th><th width="33%">Safety Watch</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$fields[1].'</td><td>'.$fields[2].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Client</th><th width="50%">Client Rep.</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[3].'</td><td>'.$fields[4].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Location</th><th width="50%">Supervisor</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[5].'</td><td>'.$fields[6].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Confined Space Description</h4>' . html_entity_decode($desc);

	//////////////////
    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="45%">Item To Be Checked</th><th width="10%">Yes:N/A</th><th width="20%">Checked By</th><th width="10%">Time</th><th width="15%">Description</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>Safety Watch has been designated (Named above on this document)</td><td>'.$fields[7].'</td><td>'.$fields[8].'</td><td>'.$fields[9].'</td><td>'.$fields[10].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Safety Watch has reviewed the Confined Space Code Of Practice.</td><td>'.$fields[11].'</td><td>'.$fields[12].'</td><td>'.$fields[13].'</td><td>'.$fields[14].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Confined Space Permit has been completed.</td><td>'.$fields[15].'</td><td>'.$fields[16].'</td><td>'.$fields[17].'</td><td>'.$fields[18].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Rescue Plan is documented and rescue equipment is in place.</td><td>'.$fields[19].'</td><td>'.$fields[20].'</td><td>'.$fields[21].'</td><td>'.$fields[22].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Known hazards are identified and mitigated. (Wrapex Hazard Assessment)</td><td>'.$fields[23].'</td><td>'.$fields[24].'</td><td>'.$fields[25].'</td><td>'.$fields[26].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Confined space is clearly marked.</td><td>'.$fields[27].'</td><td>'.$fields[28].'</td><td>'.$fields[29].'</td><td>'.$fields[30].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Contents (or previous contents) of confined space are identified.</td><td>'.$fields[31].'</td><td>'.$fields[32].'</td><td>'.$fields[33].'</td><td>'.$fields[34].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Confined space has been / is being ventilated.</td><td>'.$fields[35].'</td><td>'.$fields[36].'</td><td>'.$fields[37].'</td><td>'.$fields[38].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Confined space atmosphere is being continuously monitored.</td><td>'.$fields[39].'</td><td>'.$fields[40].'</td><td>'.$fields[41].'</td><td>'.$fields[42].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Atmospheric testing equipment has been function tested. (bump test)</td><td>'.$fields[43].'</td><td>'.$fields[44].'</td><td>'.$fields[45].'</td><td>'.$fields[46].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Equipment affecting the confined space is locked out and tagged.</td><td>'.$fields[47].'</td><td>'.$fields[48].'</td><td>'.$fields[49].'</td><td>'.$fields[50].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Piping into or affecting the confined space is isolated to zero energy. (Blanking & Blinding SWP followed)</td><td>'.$fields[51].'</td><td>'.$fields[52].'</td><td>'.$fields[53].'</td><td>'.$fields[54].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Physical check for zero energy. Verified by (name)</td><td>'.$fields[55].'</td><td>'.$fields[56].'</td><td>'.$fields[57].'</td><td>'.$fields[58].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Communications system between entrants and safety watch is in place and tested.</td><td>'.$fields[59].'</td><td>'.$fields[60].'</td><td>'.$fields[61].'</td><td>'.$fields[62].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Entry workers training qualifications (competencies) reviewed.</td><td>'.$fields[63].'</td><td>'.$fields[64].'</td><td>'.$fields[65].'</td><td>'.$fields[66].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Hot Work hazard assessment has been completed.</td><td>'.$fields[67].'</td><td>'.$fields[68].'</td><td>'.$fields[69].'</td><td>'.$fields[70].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Supplied air (SCBA or SABA) is available for workers.</td><td>'.$fields[71].'</td><td>'.$fields[72].'</td><td>'.$fields[73].'</td><td>'.$fields[74].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Safety ropes and harnesses are available for entry workers.</td><td>'.$fields[75].'</td><td>'.$fields[76].'</td><td>'.$fields[77].'</td><td>'.$fields[78].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Is electrical equipment over 12 volts ground-fault protected?</td><td>'.$fields[79].'</td><td>'.$fields[80].'</td><td>'.$fields[81].'</td><td>'.$fields[82].'</td></tr>';

	$html_weekly .= '</table>';



	$html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Time</th>
                    <th>Test Time</th>
                    <th>Tested By</th>
                    <th>Lel %</th>
					<th>H2s %</th>
					<th>Oxygen %</th>
					<th>Description</th>';

    $all_task_each = explode('**##**',$all_task);

    $total_count = mb_substr_count($all_task,'**##**');
    for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
                    $task_item = explode('**',$all_task_each[$client_loop]);
                    $task = $task_item[0];
                    $hazard = $task_item[1];
                    $level = $task_item[2];
                    $plan = $task_item[3];
					$plan1 = $task_item[4];
					$plan1 = $task_item[5];
                    if($task != '') {
                        $html_weekly .= '<tr>';
                        $html_weekly .= '<td data-title="Email">' . $task . '</td>';
                        $html_weekly .= '<td data-title="Email">' . $hazard . '</td>';
                        $html_weekly .= '<td data-title="Email">' . $level . '</td>';
                        $html_weekly .= '<td data-title="Email">' . $plan . '</td>';
						$html_weekly .= '<td data-title="Email">' . $plan1 . '</td>';
						$html_weekly .= '<td data-title="Email">' . $plan2 . '</td>';
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

        $html_weekly .= '<td data-title="Email"><img src="confined_space_entry_pre_entry_checklist/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('confined_space_entry_pre_entry_checklist/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("confined_space_entry_pre_entry_checklist/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




