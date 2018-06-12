<?php
function hydrera_site_specificpre_job_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_site_specificpre_job WHERE fieldlevelriskid='$fieldlevelriskid'"));

    //$fieldlevelriskid = $get_field_level['fieldlevelriskid'];

    $tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
    $result_update_employee = mysqli_query($dbc, "UPDATE `safety_site_specificpre_job` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    //$result_update_employee = mysqli_query($dbc, "UPDATE `safety_staff` SET `done` = 1 WHERE safetyid='$safetyid' AND staffid='$form_by' AND DATE(today_date) = CURDATE()");

    $today_date = $get_field_level['today_date'];
    $siteid = $get_field_level['siteid'];
    $contactid = $get_field_level['contactid'];
    $company = $get_field_level['company'];
    $job_desc = $get_field_level['job_desc'];
    $lsd = $get_field_level['lsd'];
    $all_task = $get_field_level['all_task'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $form_time = $get_field_level['form_time'];
    $location = $get_field_level['location'];
    $safety_topic = $get_field_level['safety_topic'];
    $concerns = $get_field_level['concerns'];

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

    $html = '<h2>Site Specific Pre Job Safety Meeting / Hazard Assessment</h2>';

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Start Site#</th></tr>';
    $html .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$siteid.'</td></tr>';
    $html .= '</table>';

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Company</th><th>Job Description</th><th>LSD</th><th>Contact</th></tr>';
    $html .= '<tr nobr="true"><td>'.$company.'</td><td>'.$job_desc.'</td>
            <td>'.$lsd.'</td><td>'.$contactid.'</td></tr>';
    $html .= '</table>';

    $html .= '<h3>Safety Checklist</h3>';

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
        $html .= '<br>Equipment operation : '.$fields_value[0];
        if (strpos(','.$fields.',', ',fields7,') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields8".',') !== FALSE) {
        $html .= '<br>Wildlife : '.$fields_value[1];
        if (strpos(','.$fields.',', ',fields8,') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields9".',') !== FALSE) {
        $html .= '<br>Equipment backing : '.$fields_value[2];
        if (strpos(','.$fields.',', ',fields9,') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields10".',') !== FALSE) {
        $html .= '<br>Awareness of others : '.$fields_value[3];
        if (strpos(','.$fields.',', ',fields10,') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields11".',') !== FALSE) {
        $html .= '<br>Overhead work : '.$fields_value[4];
        if (strpos(','.$fields.',', ','."fields11".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields12".',') !== FALSE) {
        $html .= '<br>Compressed gas cylinder : '.$fields_value[5];
        if (strpos(','.$fields.',', ','."fields12".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields13".',') !== FALSE) {
        $html .= '<br>cranes/hoisting : '.$fields_value[6];
        if (strpos(','.$fields.',', ','."fields13".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields14".',') !== FALSE) {
        $html .= '<br>Driving habit : '.$fields_value[7];
        if (strpos(','.$fields.',', ','."fields14".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields15".',') !== FALSE) {
        $html .= '<br>Electrical hazards : '.$fields_value[8];
        if (strpos(','.$fields.',', ','."fields15".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields16".',') !== FALSE) {
        $html .= '<br>Housekeeping/insp. : '.$fields_value[9];
        if (strpos(','.$fields.',', ','."fields16".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields17".',') !== FALSE) {
        $html .= '<br>Good communication : '.$fields_value[10];
        if (strpos(','.$fields.',', ','."fields17".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields18".',') !== FALSE) {
        $html .= '<br>Working at heights : '.$fields_value[11];
        if (strpos(','.$fields.',', ','."fields18".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields19".',') !== FALSE) {
        $html .= '<br>Ignition sources : '.$fields_value[12];
        if (strpos(','.$fields.',', ','."fields19".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields20".',') !== FALSE) {
        $html .= '<br>Wind Direction : '.$fields_value[13];
        if (strpos(','.$fields.',', ','."fields20".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields21".',') !== FALSE) {
        $html .= '<br>Excavation : '.$fields_value[14];
        if (strpos(','.$fields.',', ','."fields21".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields22".',') !== FALSE) {
        $html .= '<br>Ongoing Hazard Management : '.$fields_value[15];
        if (strpos(','.$fields.',', ','."fields22".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields23".',') !== FALSE) {
        $html .= '<br>Cuts/Sharps : '.$fields_value[16];
        if (strpos(','.$fields.',', ','."fields23".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields24".',') !== FALSE) {
        $html .= '<br>Manual Lifting : '.$fields_value[17];
        if (strpos(','.$fields.',', ','."fields24".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields25".',') !== FALSE) {
        $html .= '<br>Mechanical lifting : '.$fields_value[18];
        if (strpos(','.$fields.',', ','."fields25".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields26".',') !== FALSE) {
        $html .= '<br>Slip/trips/falls : '.$fields_value[19];
        if (strpos(','.$fields.',', ','."fields26".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields27".',') !== FALSE) {
        $html .= '<br>Working Alone : '.$fields_value[20];
        if (strpos(','.$fields.',', ','."fields27".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields28".',') !== FALSE) {
        $html .= '<br>Overhead lines : '.$fields_value[21];
        if (strpos(','.$fields.',', ','."fields28".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields29".',') !== FALSE) {
        $html .= '<br>Pinch points / crushing : '.$fields_value[22];
        if (strpos(','.$fields.',', ','."fields29".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields30".',') !== FALSE) {
        $html .= '<br>Rigging/ropes/slings/cable : '.$fields_value[23];
        if (strpos(','.$fields.',', ','."fields30".',') !== FALSE) {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }

    $html .= '';

    $html .= '<h3>Safety Equipment / PPE</h3>';

    if (strpos($form_config, ','."fields31".',') !== FALSE) {
        $html .= '<br>Tag lines : '.$fields_value[24];
        if (strpos(','.$fields.',', ','."fields31".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields32".',') !== FALSE) {
        $html .= '<br>Shoring : '.$fields_value[25];
        if (strpos(','.$fields.',', ','."fields32".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields33".',') !== FALSE) {
        $html .= '<br>Gloves : '.$fields_value[26];
        if (strpos(','.$fields.',', ','."fields33".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields34".',') !== FALSE) {
        $html .= '<br>Fire extinguishers : '.$fields_value[27];
        if (strpos(','.$fields.',', ','."fields34".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields35".',') !== FALSE) {
        $html .= '<br>Foot protection : '.$fields_value[28];
        if (strpos(','.$fields.',', ','."fields35".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields36".',') !== FALSE) {
        $html .= '<br>Warning signs : '.$fields_value[29];
        if (strpos(','.$fields.',', ','."fields36".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields37".',') !== FALSE) {
        $html .= '<br>Hard hat : '.$fields_value[30];
        if (strpos(','.$fields.',', ','."fields37".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields38".',') !== FALSE) {
        $html .= '<br>Full body clothing(FRC) : '.$fields_value[31];
        if (strpos(','.$fields.',', ','."fields38".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields39".',') !== FALSE) {
        $html .= '<br>Fall arrest protection system : '.$fields_value[32];
        if (strpos(','.$fields.',', ','."fields39".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields40".',') !== FALSE) {
        $html .= '<br>Eye protection : '.$fields_value[33];
        if (strpos(','.$fields.',', ','."fields40".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields41".',') !== FALSE) {
        $html .= '<br>Ground distrubance : '.$fields_value[34];
        if (strpos(','.$fields.',', ','."fields41".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields42".',') !== FALSE) {
        $html .= '<br>SCBA (H2S site) : '.$fields_value[35];
        if (strpos(','.$fields.',', ','."fields42".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }

    $html .= '';

    $html .= '<h3>Procedures / Checklists</h3>';

    if (strpos($form_config, ','."fields43".',') !== FALSE) {
        $html .= '<br>Change managemet : '.$fields_value[36];
        if (strpos(','.$fields.',', ','."fields43".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields44".',') !== FALSE) {
        $html .= '<br>Confined space : '.$fields_value[37];
        if (strpos(','.$fields.',', ','."fields44".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields45".',') !== FALSE) {
        $html .= '<br>Emergency response/Muster Pt. : '.$fields_value[38];
        if (strpos(','.$fields.',', ','."fields45".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields46".',') !== FALSE) {
        $html .= '<br>Incident reporting : '.$fields_value[39];
        if (strpos(','.$fields.',', ','."fields46".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields47".',') !== FALSE) {
        $html .= '<br>Towing vehicles : '.$fields_value[40];
        if (strpos(','.$fields.',', ','."fields47".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields48".',') !== FALSE) {
        $html .= '<br>Lockout / tag out : '.$fields_value[41];
        if (strpos(','.$fields.',', ','."fields48".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields49".',') !== FALSE) {
        $html .= '<br>H2S : '.$fields_value[42];
        if (strpos(','.$fields.',', ','."fields49".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields50".',') !== FALSE) {
        $html .= '<br>Orientations : '.$fields_value[43];
        if (strpos(','.$fields.',', ','."fields50".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields51".',') !== FALSE) {
        $html .= '<br>Scaffold inspection : '.$fields_value[44];
        if (strpos(','.$fields.',', ','."fields51".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }
    if (strpos($form_config, ','."fields52".',') !== FALSE) {
        $html .= '<br>Smoking : '.$fields_value[45];
        if (strpos(','.$fields.',', ','."fields52".',') !== FALSE){
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
    }

    if (strpos($form_config, ','."fields53".',') !== FALSE) {
        $html .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
        $html .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Hazard</th>
            <th>Control</th>
            <th>Type Of Control Measure</th>
            <th>Risk</th></tr>
        ';

        $all_task_each = explode('**##**',$all_task);

        $total_count = mb_substr_count($all_task,'**##**');
        if($total_count == 0) {
            $html .= '<tr nobr="true">';
            $html .= '<td data-title="Email">&nbsp;&nbsp;</td>';
            $html .= '<td data-title="Email">&nbsp;&nbsp;</td>';
            $html .= '<td data-title="Email">&nbsp;&nbsp;</td>';
            $html .= '<td data-title="Email">&nbsp;&nbsp;</td>';
            $html .= '</tr>';
        }
        for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
            $task_item = explode('**',$all_task_each[$client_loop]);
            $task = $task_item[0];
            $hazard = $task_item[1];
            $level = $task_item[2];
            $plan = $task_item[3];
            if($task != '') {
                $html .= '<tr nobr="true">';
                $html .= '<td data-title="Email">' . $task . '</td>';
                $html .= '<td data-title="Email">' . $hazard . '</td>';
                $html .= '<td data-title="Email">' . $level . '</td>';
                $html .= '<td data-title="Email">' . $plan . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
    }

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Time</th><th>Location / Job #</th></tr>';
    $html .= '<tr nobr="true"><td>'.$form_time.'</td><td>'.$location.'</td></tr>';
    $html .= '<tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;"><th>Safety Topic</th><th>Concerns</th></tr><tr nobr="true"><td>'.$safety_topic.'</td><td>'.$concerns.'</td></tr></table>';

    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>';
        if (strpos($form_config, ','."fields54".',') !== FALSE) {
        $html .= '<th>Orient</th>';
        }
        if (strpos($form_config, ','."fields55".',') !== FALSE) {
        $html .= '<th>H2S</th>';
        }
        if (strpos($form_config, ','."fields56".',') !== FALSE) {
        $html .= '<th>1st Aid</th>';
        }
        if (strpos($form_config, ','."fields57".',') !== FALSE) {
        $html .= '<th>TDG</th>';
        }
        if (strpos($form_config, ','."fields58".',') !== FALSE) {
        $html .= '<th>Confined Space</th>';
        }
        if (strpos($form_config, ','."fields59".',') !== FALSE) {
        $html .= '<th>WHMIS</th>';
        }
        if (strpos($form_config, ','."fields60".',') !== FALSE) {
        $html .= '<th>Gr.Dis.</th>';
        }
        if (strpos($form_config, ','."fields61".',') !== FALSE) {
        $html .= '<th>PST</th>';
        }
        $html .= '</tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html .= '<tr nobr="true">';
        $html .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';
        $html .= '<td data-title="Email"><img src="hydrera_site_specificpre_job/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        if (strpos($form_config, ','."fields54".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck1".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        if (strpos($form_config, ','."fields55".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck2".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        if (strpos($form_config, ','."fields56".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck3".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        if (strpos($form_config, ','."fields57".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck4".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        if (strpos($form_config, ','."fields58".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck5".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        if (strpos($form_config, ','."fields59".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck6".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        if (strpos($form_config, ','."fields60".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck7".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        if (strpos($form_config, ','."fields61".',') !== FALSE) {
            if (strpos(','.$staffcheck.',', ','."staffcheck8".',') !== FALSE){
                $html .= '<td data-title="Email"><img src="../img/checkmark.png" width="10" height="10" border="0" alt=""></td>';
            } else {
                $html .= '<td data-title="Email">&nbsp;</td>';
            }
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('hydrera_site_specificpre_job/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        unlink("hydrera_site_specificpre_job/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}

?>