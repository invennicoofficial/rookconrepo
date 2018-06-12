<?php
function avs_near_miss_pdf($dbc,$hrid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_avs_near_miss WHERE fieldlevelriskid='$fieldlevelriskid'"));

    $tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_hr WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);

    $result_update_employee = mysqli_query($dbc, "UPDATE `hr_avs_near_miss` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    //$result_update_employee = mysqli_query($dbc, "UPDATE `hr_staff` SET `done` = 1 WHERE hrid='$hrid' AND staffid='$form_by' AND DATE(today_date) = CURDATE()");

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $location = $get_field_level['location'];
    $hazard_rating = $get_field_level['hazard_rating'];
    $action_timeline = $get_field_level['action_timeline'];
    $description = $get_field_level['description'];
    $action = $get_field_level['action'];
    $action_to = $get_field_level['action_to'];
    $est_comp = $get_field_level['est_comp'];
    $date_comp = $get_field_level['date_comp'];
    $analysis_to = $get_field_level['analysis_to'];

    class MYPDF extends TCPDF {

        //Page header
        public function Header() {
            if(PDF_LOGO != '') {
                $image_file = 'download/'.PDF_LOGO;
                $this->Image($image_file, 10, 10, 30, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->SetFont('helvetica', '', 8);
            $header_text = '';
            $this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);

        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
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
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);

    $html_weekly = '<h2>AVS Hazard Identification</h2>';

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Location</th><th>Reported By</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$location.'</td><td>'.$contactid.'</td></tr>';
    $html_weekly .= '</table><br><br>';

    $html_weekly .= "<b>Hazard Rating</b> : ".$hazard_rating;
    $html_weekly .= "<br><b>Action Timeline</b> : ".$action_timeline;

    $html_weekly .= "<br><br><b>Description of Unsafe Acts/Conditions/Practices</b><br>".html_entity_decode($description);
    $html_weekly .= "<br><br><b>Action To Be Taken</b><br>".html_entity_decode($action);

    $html_weekly .= "<br><b>Action Assigned to</b> : ".$action_to;
    $html_weekly .= "<br><b>Estimated Completion Date</b> : ".$est_comp;
    $html_weekly .= "<br><b>Date Completed</b> : ".$date_comp;
    $html_weekly .= "<br><b>Invenstigation/Root Cause Analysis Assigned To</b> : ".$analysis_to;

    $sa = mysqli_query($dbc, "SELECT * FROM hr_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND hrid='$hrid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        <th>Review</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['hrattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';
        $html_weekly .= '<td data-title="Email"><img src="avs_near_miss/download/hr_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '<td data-title="Email">'.$staffcheck.'</td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');
    $pdf->Output('avs_near_miss/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT hrattid FROM hr_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND hrid='$hrid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['hrattid'];
        unlink("avs_near_miss/download/hr_".$assign_staff_id.".png");
    }
    echo '';
}
?>