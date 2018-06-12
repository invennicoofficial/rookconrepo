<?php
	function on_the_job_training_record_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_on_the_job_training_record WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_on_the_job_training_record` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
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

	$html_weekly = '<h2>On The Job Training Record</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">Location</th><th width="30%">Date</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[0].'</td><td>'.$fields[1].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">Employee</th><th width="30%">Position</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[2].'</td><td>'.$fields[3].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Task to be performed</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[4].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="30%">Reason for training</th><th width="70%">Description</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[5].'</td><td>'.$fields[6].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Training provided</h4>' . html_entity_decode($desc);

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="70%">Trainer</th><th width="30%">Date training provided</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[7].'</td><td>'.$fields[8].'</td></tr>';

	$html_weekly .= '</table><br><br>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Task to be Evaluated</th><th width="15%">Satisfactory/Unsatisfactory</th><th width="35%">Comments</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>1.Did the employee describe the process for performing the task?  Interview or hands-on observation? (check one)</td><td>'.$fields[9].'</td><td>'.$fields[10].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Did the employee describe any unique hazards associated with the performance of the task?)</td><td>'.$fields[11].'</td><td>'.$fields[12].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Did the employee identify any unique personal protective equipment (i.e. gloves, respirator) required for this task, or any unique equipment (i.e. gas detector or tools) required for this task?</td><td>'.$fields[13].'</td><td>'.$fields[14].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Was the task performed in the correct sequence? (this may be N/A in some situations)</td><td>'.$fields[15].'</td><td>'.$fields[16].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Did the employee describe the significant details of the step clearly and the consequences if certain steps were not performed correctly?</td><td>'.$fields[17].'</td><td>'.$fields[18].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Was all personal protective equipment used appropriately and in accordance with company and legislated policies/regulations?</td><td>'.$fields[19].'</td><td>'.$fields[20].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Was PPE training (use/maintenance)  included appropriate to the task?</td><td>'.$fields[21].'</td><td>'.$fields[22].'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Did the practices you observed comply with all of the applicable procedures, work aids, codes of practice etc.?</td><td>'.$fields[23].'</td><td>'.$fields[24].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Comments</h4>' . html_entity_decode($desc1);

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Additional Training Requirements</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[25].'</td></tr>';

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

        $html_weekly .= '<td data-title="Email"><img src="on_the_job_training_record/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('on_the_job_training_record/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("on_the_job_training_record/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>











	//////////////////
    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="45%">General Factors</th><th width="10%">Accepted / Rejected</th><th width="45%">Supportive Details or Comments</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>Hardware: (includes snap hooks,carabiners, adjusters, keepers,thimbles, and Drings).Inspect fordamage, distortion, sharp edges, burrs,cracks, corrosion and proper operation.</td><td>'.$fields[7].'</td><td>'.html_entity_decode($desc1).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Webbing: Inspect for cuts, burns,tears, frays, excessive soiling, and discoloration.</td><td>'.$fields[8].'</td><td>'.html_entity_decode($desc2).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Stitching: inspect for pulled or cut stitches.</td><td>'.$fields[9].'</td><td>'.html_entity_decode($desc3).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Synthetic Rope: Inspect for pulled or cut yarns, burns, abrasion, knots, excessive soiling and discoloration.</td><td>'.$fields[10].'</td><td>'.html_entity_decode($desc4).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Wire Rope: Inspect for broken wires, corrosion, kinks, and separation of strands.</td><td>'.$fields[11].'</td><td>'.html_entity_decode($desc5).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Energy Absorbing Component:Inspect for elongation, tears, and excessive soiling.</td><td>'.$fields[12].'</td><td>'.html_entity_decode($desc6).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Labels: Inspect, make certain all labels are securely held in place and legible.</td><td>'.$fields[13].'</td><td>'.html_entity_decode($desc7).'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="20%">Overall Disposition</th><th width="80%">Description</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[14].'</td><td>'.$fields[15].'</td></tr>';

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

        $html_weekly .= '<td data-title="Email"><img src="on_the_job_training_record/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('on_the_job_training_record/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("on_the_job_training_record/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




