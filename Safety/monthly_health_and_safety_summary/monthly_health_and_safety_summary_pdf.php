<?php

function monthly_health_and_safety_summary_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_monthly_health_and_safety_summary WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_monthly_health_and_safety_summary` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $period_ending = $get_field_level['period_ending'];
    $summary_type = $get_field_level['summary_type'];
    $workers = $get_field_level['workers'];
    $comp_orent = $get_field_level['comp_orent'];
    $toolbox_meeting = $get_field_level['toolbox_meeting'];
    $conducetd_number = $get_field_level['conducetd_number'];
    $per_attendance = $get_field_level['per_attendance'];
    $inspection_schd = $get_field_level['inspection_schd'];
    $comp_num = $get_field_level['comp_num'];
    $unsafe_acts = $get_field_level['unsafe_acts'];
    $corrected_num = $get_field_level['corrected_num'];
    $outstanding_num = $get_field_level['outstanding_num'];
    $incident_reported = $get_field_level['incident_reported'];
    $damage_only = $get_field_level['damage_only'];
    $injury_only = $get_field_level['injury_only'];
    $injuty_and_damage = $get_field_level['injuty_and_damage'];
    $vehicle_accident = $get_field_level['vehicle_accident'];
    $no_loss = $get_field_level['no_loss'];
    $comments = $get_field_level['comments'];

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

	$html_weekly = '<h2>Monthly Health and Safety Summary</h2>'; // Form nu heading

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
					 <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
					 <th>Today Date</th><th>Created By</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$contactid.'</td></tr>';
    $html_weekly .= '</table>';

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
					 <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
					 <th>For the Period Ending</th><th>Summary Type</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$period_ending.'</td><td>'.$summary_type.'</td></tr>';
    $html_weekly .= '</table>';

	$html_weekly .= "<h3>Number of workers hired</h3>".$workers;
    $html_weekly .= "<h3>Number completed orientations</h3>".$comp_orent;
    $html_weekly .= "<h3>Number of Tool Box Meetings scheduled</h3>".$toolbox_meeting;
    $html_weekly .= "<h3>Number conducted</h3>".$conducetd_number;
    $html_weekly .= "<h3>Percentage attendance</h3>".$per_attendance;
    $html_weekly .= "<h3>Number of Formal Inspections scheduled</h3>".$inspection_schd;
    $html_weekly .= "<h3>Number completed</h3>".$comp_num;
    $html_weekly .= "<h3>Total Unsafe Acts Identified</h3>".$unsafe_acts;
    $html_weekly .= "<h3>Number Corrected</h3>".$corrected_num;
    $html_weekly .= "<h3>Number Outstanding</h3>".$outstanding_num;
    $html_weekly .= "<h3>Number of Reported Incidents</h3>".$incident_reported;
    $html_weekly .= "<h3>Damage only</h3>".$damage_only;
    $html_weekly .= "<h3>Injury only</h3>".$injury_only;
    $html_weekly .= "<h3>Injury and damage</h3>".$injuty_and_damage;
    $html_weekly .= "<h3>Vehicle Accident</h3>".$vehicle_accident;
    $html_weekly .= "<h3>No-loss</h3>".$no_loss;
	$html_weekly .= "<h3>Comments</h3>".html_entity_decode($comments);

	$sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
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

        $html_weekly .= '<td data-title="Email"><img src="monthly_health_and_safety_summary/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('monthly_health_and_safety_summary/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("monthly_health_and_safety_summary/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>





