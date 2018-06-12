<?php
	function full_body_harness_inspection_checklist_log_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_full_body_harness_inspection_checklist_log WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_full_body_harness_inspection_checklist_log` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$desc4 = $get_field_level['desc4'];
	$desc5 = $get_field_level['desc5'];
	$desc6 = $get_field_level['desc6'];
	$desc7 = $get_field_level['desc7'];
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

	$html_weekly = '<h2>Full Body Harness Inspection Checklist Log</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="34%">Date</th><th width="33%">Harness Model</th><th width="33%">Manufacture Date</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$fields[1].'</td><td>'.$fields[2].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="34%">Serial Number:</th><th width="33%">Lot Number</th><th width="33%">Purchase Date:</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[3].'</td><td>'.$fields[4].'</td><td>'.$fields[6].'</td></tr>';

	$html_weekly .= '</table>';

	$html_weekly .= '<h4>Comments</h4>' . html_entity_decode($desc);

	//////////////////
    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="45%">General Factors</th><th width="10%">Accepted / Rejected</th><th width="45%">Supportive Details or Comments</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>Hardware: (includes D-rings, buckles, keepers, and back pads) Inspect for damage, distortion, sharp edges, burrs, cracks and corrosion.</td><td>'.$fields[7].'</td><td>'.html_entity_decode($desc1).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Webbing: Inspect for cuts, burns, tears, abrasion, frays, excessive soiling, and discoloration. </td><td>'.$fields[8].'</td><td>'.html_entity_decode($desc2).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Stitching: inspect for pulled or cut stitches.</td><td>'.$fields[9].'</td><td>'.html_entity_decode($desc3).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>Labels: Inspect, make certain all labels are securely held in place and legible.</td><td>'.$fields[10].'</td><td>'.html_entity_decode($desc4).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[16].'</td><td>'.$fields[11].'</td><td>'.html_entity_decode($desc5).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[17].'</td><td>'.$fields[12].'</td><td>'.html_entity_decode($desc6).'</td></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[18].'</td><td>'.$fields[13].'</td><td>'.html_entity_decode($desc7).'</td></tr>';

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

        $html_weekly .= '<td data-title="Email"><img src="full_body_harness_inspection_checklist_log/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('full_body_harness_inspection_checklist_log/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("full_body_harness_inspection_checklist_log/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




