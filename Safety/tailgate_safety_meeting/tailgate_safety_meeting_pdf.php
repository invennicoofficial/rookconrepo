<?php
function tailgate_safety_meeting_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

    $get_weekly = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_tailgate_safety_meeting WHERE fieldlevelriskid='$fieldlevelriskid'"));

    $tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_pdf_logo['fields'].',';

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
    $result_update_employee = mysqli_query($dbc, "UPDATE `safety_tailgate_safety_meeting` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    //$result_update_employee = mysqli_query($dbc, "UPDATE `safety_staff` SET `done` = 1 WHERE safetyid='$safetyid' AND staffid='$form_by' AND DATE(today_date) = CURDATE()");

    $today_date = $get_weekly['today_date'];
    $project_number = $get_weekly['project_number'];
    $contactid = $get_weekly['contactid'];
    $location = $get_weekly['location'];
    $supervisor = $get_weekly['supervisor'];
    $item_discussed = $get_weekly['item_discussed'];

    $desc = $get_weekly['desc'];
    $all_task = $get_weekly['all_task'];
    $fields = explode('**FFM**', $get_weekly['fields']);

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

    $html_weekly = '<h2>Tailgate Safety Meeting</h2>';

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos($form_config, ','."fields1".',') !== FALSE) {
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Date/Time</th></tr>
            <tr nobr="true"><td>'.$today_date.'</td>
            </tr>';
    }

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="20%">Project/Job Number</th>
            <th width="40%">Location of Work</th>
            <th width="40%">Supervisor</th></tr>
            <tr nobr="true"><td>'.$project_number.'</td><td>'.$location.'</td><td>'.$supervisor.'</td>
            </tr>';
    }

    if (strpos($form_config, ','."fields6".',') !== FALSE) {
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="30%">Meeting Leader</th>
            <th width="30%">Work Site</th>
            <th width="40%">Location (bldg., area etc.)</th></tr>
            <tr nobr="true"><td>'.$fields[0].'</td><td>'.$fields[1].'</td><td>'.$location.'</td>
            </tr>';
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Brief Work Description</th>
            <th width="50%">SWP #</th>
            </tr>
            <tr nobr="true"><td>'.$fields[2].'</td><td>'.$fields[3].'</td>
            </tr>';
    }

    $html_weekly .= '</table>';

    if (strpos($form_config, ','."fields10".',') !== FALSE) {

        $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';

        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
                <th width="48%">Name</th><th width="12%">Yes:No:N/A</th><th width="40%">Description</th></tr>';
        $html_weekly .= '<tr nobr="true"><td>Safe Work Practices & Procedures reviewed</td><td>'.$fields[4].'</td><td>'.$fields[5].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Permits obtained & reviewed</td><td>'.$fields[6].'</td><td>'.$fields[7].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Housekeeping requirements reviewed</td><td>'.$fields[8].'</td><td>'.$fields[9].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Isolation required (Locked & Tagged)</td><td>'.$fields[10].'</td><td>'.$fields[11].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Overhead hazards identified</td><td>'.$fields[12].'</td><td>'.$fields[13].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Hoisting requirements reviewed</td><td>'.$fields[14].'</td><td>'.$fields[15].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Barriers & signage reviewed</td><td>'.$fields[16].'</td><td>'.$fields[17].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>MSDS reviewed</td><td>'.$fields[18].'</td><td>'.$fields[19].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Respiratory protection reviewed</td><td>'.$fields[20].'</td><td>'.$fields[21].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>New worker training required</td><td>'.$fields[22].'</td><td>'.$fields[23].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Other PPE requirements reviewed</td><td>'.$fields[24].'</td><td>'.$fields[25].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Proper tools available / good condition</td><td>'.$fields[26].'</td><td>'.$fields[27].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Extreme weather conditions a factor</td><td>'.$fields[28].'</td><td>'.$fields[29].'</td></tr>';
        $html_weekly .= '<tr nobr="true"><td>Environmental impacts / waste disposal</td><td>'.$fields[30].'</td><td>'.$fields[31].'</td></tr>';
        $html_weekly .= '</table>';
    }

    if (strpos($form_config, ','."fields5".',') !== FALSE) {
        $html_weekly .= "<h3>Items Discussed</h3>".html_entity_decode($item_discussed);
    }

    if (strpos($form_config, ','."fields11".',') !== FALSE) {
        $html_weekly .= "<h3>Comments</h3>".html_entity_decode($desc);
    }

    if (strpos($form_config, ','."fields12".',') !== FALSE) {
        $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
        $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Time</th>
                        <th>Priority</th>
                        <th>Tasks</th>
                        <th>Hazards</th>
                        <th>Controls</th>
                        <th>Risk Level</th></tr>';

        $all_task_each = explode('**##**',$all_task);

        $total_count = mb_substr_count($all_task,'**##**');
        for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
            $task_item = explode('**',$all_task_each[$client_loop]);
            $task = $task_item[0];
            $hazard = $task_item[1];
            $level = $task_item[2];
            $plan = $task_item[3];
            $plan1 = $task_item[4];
            if($task != '') {
                $html_weekly .= '<tr>';
                $html_weekly .= '<td data-title="Email">' . $task . '</td>';
                $html_weekly .= '<td data-title="Email">' . $hazard . '</td>';
                $html_weekly .= '<td data-title="Email">' . $level . '</td>';
                $html_weekly .= '<td data-title="Email">' . $plan . '</td>';
                $html_weekly .= '<td data-title="Email">' . $plan1 . '</td>';
                $html_weekly .= '</tr>';
            }
        }
        $html_weekly .= '</table>';
    }

    if (strpos($form_config, ','."fields13".',') !== FALSE) {
        $html_weekly .= "<br>Location of Emergency Assembly Area : ".$fields[32];
    }
    if (strpos($form_config, ','."fields14".',') !== FALSE) {
        $html_weekly .= "<br>Emergency Response Plan Reviewed : ".$fields[33];
    }
    if (strpos($form_config, ','."fields15".',') !== FALSE) {
        $html_weekly .= "<br>Have all personnel received orientation to the work area : ".$fields[34];
    }

    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        <th>Employer/Initial</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';
        $html_weekly .= '<td data-title="Email"><img src="tailgate_safety_meeting/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '<td data-title="Email">'.$staffcheck.'</td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');
    $pdf->Output('tailgate_safety_meeting/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        unlink("tailgate_safety_meeting/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>