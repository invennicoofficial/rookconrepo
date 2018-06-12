<?php

function safety_meeting_minutes_pdf($dbc,$safetyid, $fieldlevelriskid) {

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_safety_meeting_minutes WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_safety_meeting_minutes` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$absent = $get_field_level['absent'];
	$follow_up_action = $get_field_level['follow_up_action'];
	$corrective_actions = $get_field_level['corrective_actions'];
	$vehicle_logs = $get_field_level['vehicle_logs'];
	$vehicle_update = $get_field_level['vehicle_update'];
	$training = $get_field_level['training'];
	$driving = $get_field_level['driving'];
	$safety_concerns = $get_field_level['safety_concerns'];
	$discussion_items = $get_field_level['discussion_items'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$all_task = $get_field_level['all_task'];

	class MYPDF extends TCPDF {

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

	$html_weekly = '<h2>Safety Meeting Minutes</h2>'; // Form nu heading

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Date</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td></tr>';
    $html_weekly .= '</table>';
    }

    if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Location</th><th>Meeting Leader</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$fields[0].'</td><td>'.$fields[1].'</td></tr>';
    $html_weekly .= '</table>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Contact</th><th>Absent</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$contactid.'</td><td>'.$absent.'</td></tr>';
    $html_weekly .= '</table>';
    }

    if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
	$html_weekly .= "<h3>Introduction Of Guests / New Personnel</h3>".html_entity_decode($desc);
    }
    if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
	$html_weekly .= "<h3>Review Minutes</h3>".$fields[2];
    }
    if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
	$html_weekly .= "<h3>Incident Review</h3>".html_entity_decode($desc1);
    }
    if (strpos(','.$form_config.',', ',fields16,') !== FALSE) {
	$html_weekly .= "<h3>Standards, Policies & Procedures</h3>".html_entity_decode($desc2);
    }
    if (strpos(','.$form_config.',', ',fields17,') !== FALSE) {
	$html_weekly .= "<h3>New Business</h3>".html_entity_decode($desc3);
    }
    if (strpos(','.$form_config.',', ',fields18,') !== FALSE) {
        $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                        <th>Item Description</th>
                    <th>Assigned To</th>
                    <th>Completion</th></tr>';

        $all_task_each = explode('**##**',$all_task);

        $total_count = mb_substr_count($all_task,'**##**');
        for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
            $task_item = explode('**',$all_task_each[$client_loop]);
            $task = $task_item[0];
            $hazard = $task_item[1];
            $plan = $task_item[2];

            if($task != '') {
                $html_weekly .= '<tr>';
                $html_weekly .= '<td data-title="Email">' . $task . '</td>';
                $html_weekly .= '<td data-title="Email">' . $hazard . '</td>';
                $html_weekly .= '<td data-title="Email">' . $plan . '</td>';
                $html_weekly .= '</tr>';
            }
        }
        $html_weekly .= '</table>';

    }
    if (strpos(','.$form_config.',', ',fields19,') !== FALSE) {
	$html_weekly .= "<h3>Meeting Adjourned</h3>".$fields[3];
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html_weekly .= "<h3>Follow Up Action Items</h3>".html_entity_decode($follow_up_action);
    }
    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	$html_weekly .= "<h3>Corrective Actions</h3>".html_entity_decode($corrective_actions);
    }
    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
	$html_weekly .= "<h3>Vehicle Logs</h3>".html_entity_decode($vehicle_logs);
    }
    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
	$html_weekly .= "<h3>Vehicle Update</h3>".html_entity_decode($vehicle_update);
    }
    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
	$html_weekly .= "<h3>Training</h3>".html_entity_decode($training);
    }
    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
	$html_weekly .= "<h3>Driving</h3>".html_entity_decode($driving);
    }
    if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
	$html_weekly .= "<h3>Safety Concerns</h3>".html_entity_decode($safety_concerns);
    }
    if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
	$html_weekly .= "<h3>Discussion Items</h3>".html_entity_decode($discussion_items);
    }

	$sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';

        // avs_near_miss = form name

        $html_weekly .= '<td data-title="Email"><img src="safety_meeting_minutes/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('safety_meeting_minutes/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("safety_meeting_minutes/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>








