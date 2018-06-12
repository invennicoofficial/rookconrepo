<?php
function weekly_safety_meeting_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

    $get_weekly = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_weekly_safety_meeting WHERE fieldlevelriskid='$fieldlevelriskid'"));

    $tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
    $result_update_employee = mysqli_query($dbc, "UPDATE `safety_weekly_safety_meeting` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    //$result_update_employee = mysqli_query($dbc, "UPDATE `safety_staff` SET `done` = 1 WHERE safetyid='$safetyid' AND staffid='$form_by' AND DATE(today_date) = CURDATE()");

    $today_date = $get_weekly['today_date'];
    $location = $get_weekly['location'];
    $contactid = $get_weekly['contactid'];
    $issue_discussion = $get_weekly['issue_discussion'];
    $follow_up = $get_weekly['follow_up'];

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

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    $html_weekly = '<h2>Weekly Safety Meeting</h2>';

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Location</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$location.'</td></tr>';
    $html_weekly .= '</table>';

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
        $html_weekly .= "<h3>Issues discussed and employee's suggestions</h3>".html_entity_decode($issue_discussion);
    }

    if (strpos($form_config, ','."fields4".',') !== FALSE) {
        $html_weekly .= '<h3>Items to be followed up on</h3>'.html_entity_decode($follow_up);
    }

    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        <th>P.P.E</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';
        $html_weekly .= '<td data-title="Email"><img src="weekly_safety_meeting/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '<td data-title="Email">'.$staffcheck.'</td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');
    $pdf->Output('weekly_safety_meeting/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        unlink("weekly_safety_meeting/download/safety_".$assign_staff_id.".png");
    }
    echo 'hello';
}
?>