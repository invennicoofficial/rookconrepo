<?php
function field_level_hazard_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
    //$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_field_level_risk_assessment WHERE safetyid='$safetyid' AND contactid='$form_by' AND DATE(today_date) = CURDATE()"));

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_field_level_risk_assessment WHERE fieldlevelriskid='$fieldlevelriskid'"));

    //$fieldlevelriskid = $get_field_level['fieldlevelriskid'];

    $tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

    $result_update_employee = mysqli_query($dbc, "UPDATE `safety_field_level_risk_assessment` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    //$result_update_employee = mysqli_query($dbc, "UPDATE `safety_staff` SET `done` = 1 WHERE safetyid='$safetyid' AND staffid='$form_by' AND DATE(today_date) = CURDATE()");

    $today_date = $get_field_level['today_date'];
    $jobid = $get_field_level['jobid'];
    $contactid = $get_field_level['contactid'];
    $location = explode('*#*',$get_field_level['location']);
    $location = implode('<br>',$location);
    $fields = $get_field_level['fields'];
    $working_alone = $get_field_level['working_alone'];
    $all_task = $get_field_level['all_task'];
    $job_complete = $get_field_level['job_complete'];
    $worker_name = $get_field_level['worker_name'];
    $foreman_name = $get_field_level['foreman_name'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $job_complete_value = explode('**FFM**', $get_field_level['job_complete_value']);
    $crew_leader = $get_field_level['crew_leader'];
    $workers_on_crew = explode(',',$get_field_level['workers_on_crew']);

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
    //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, 40);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);

    $html = '<h2>Field Level Risk Assessment</h2>';

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Job</th><th>Contact#</th><th>Job Location</th></tr>';
    $html .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$jobid.'</td>
            <td>'.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).'</td><td>'.$location.'</td></tr>';
    $html .= '</table>';

    $html .= '<h3>Permits/Plans</h3>';

    $html .= '<br>Hot Work/Cold Work';
    if (strpos(','.$fields.',', ',fields1,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[1];

    $html .= '<br>Confined Space';
    if (strpos(','.$fields.',', ',fields2,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[2];
    $html .= '<br>Demolition';
    if (strpos(','.$fields.',', ',fields3,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[3];
    $html .= '<br>Ground Disturbance';
    if (strpos(','.$fields.',', ',fields108,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[108];
    $html .= '<br>Excavation';
    if (strpos(','.$fields.',', ',fields4,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[4];
    $html .= '<br>Lockout';
    if (strpos(','.$fields.',', ',fields5,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[5];
    $html .= '<br>Critical Lift Plan';
    if (strpos(','.$fields.',', ',fields6,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[6];
    $html .= '<br>Fall Protection Plan';
    if (strpos(','.$fields.',', ',fields7,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[7];
    $html .= '<br>Road Closure Permit';
    if (strpos(','.$fields.',', ',fields162,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[161];
    $html .= '<br>Locates Expiration';
    if (strpos(','.$fields.',', ',fields162,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[162];

    $html .= '';

    $html .= '<h3>Permit Identified Hazards</h3>';
    $html .= '<br>Hazards Detailed on Safe Work Permit';
    if (strpos(','.$fields.',', ',fields8,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[8];
    $html .= '<br>Hazards on Critical Lift Permit';
    if (strpos(','.$fields.',', ',fields9,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[9];
    $html .= '<br>Hazards on Electrical Permit';
    if (strpos(','.$fields.',', ',fields10,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[10];
    $html .= '<br>Hazards Identified for Confined Space Entry';
    if (strpos(','.$fields.',', ',fields11,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[11];
    $html .= '<br>Hazards on Confined Space Entry Permit';
    if (strpos(','.$fields.',', ',fields12,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[12];
    $html .= '<br>Hazards on Hot/Cold Work Permit';
    if (strpos(','.$fields.',', ',fields13,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[13];
    $html .= '<br>Hazards on Underground/ Excavation, Permit';
    if (strpos(','.$fields.',', ',fields14,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[14];
    $html .= '<br>Hazards on Line Opening Permit';
    if (strpos(','.$fields.',', ',fields15,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[15];

    $html .= '<h3>Emergency Equipment</h3>';
    $html .= '<br>Fire Extinguisher';
    if (strpos(','.$fields.',', ',fields16,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[16];
    $html .= '<br>Eyewash/Shower';
    if (strpos(','.$fields.',', ',fields17,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[17];
    $html .= '<br>All Conditions Met';
    if (strpos(','.$fields.',', ',fields109,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[109];
    $html .= '<br>Extraction Equipment';
    if (strpos(','.$fields.',', ',fields18,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[18];
    $html .= '<br>Permit Displayed';
    if (strpos(','.$fields.',', ',fields19,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[19];
    $html .= '<br>Alarm#';
    $html .= '<br>First Aid Kit';
    if (strpos(','.$fields.',', ',fields157,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[157];
    $html .= '<br>Spill Kit';
    if (strpos(','.$fields.',', ',fields158,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[158];
    $html .= '<br>Road Flares';
    if (strpos(','.$fields.',', ',fields159,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[159];
    $html .= '<br>Location of Emergency Equipment';
    if (strpos(','.$fields.',', ',fields160,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[160];

    $html .= '<h3>Overhead Or Working At Height Hazards</h3>';
    $html .= '<br>Harness Required/Appropriate Tie-off identified';
    if (strpos(','.$fields.',', ',fields20,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[20];
    $html .= '<br>Others Working Overhead/Below';
    if (strpos(','.$fields.',', ',fields21,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[21];
    $html .= '<br>Hoisting or Moving Loads Overhead';
    if (strpos(','.$fields.',', ',fields22,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[22];
    $html .= '<br>Falls From Height';
    if (strpos(','.$fields.',', ',fields23,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[23];
    $html .= '<br>Hoisting or Moving Loads Overhead/Around Task';
    if (strpos(','.$fields.',', ',fields24,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[24];
    $html .= '<br>Use of Scaffolds';
    if (strpos(','.$fields.',', ',fields110,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[110];
    $html .= '<br>Tasks Requiring You to Work Above Your Task';
    if (strpos(','.$fields.',', ',fields25,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[25];
    $html .= '<br>Objects / Debris Falling From Above';
    if (strpos(','.$fields.',', ',fields26,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[26];
    $html .= '<br>Overhead Power Line';
    if (strpos(','.$fields.',', ',fields27,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[27];

    $html .= '<h3>Equipment Hazards</h3>';
    $html .= '<br>Operating Power Equipment';
    if (strpos(','.$fields.',', ',fields28,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[28];
    $html .= '<br>Operating Motor Vehicle / Heavy Equipment';
    if (strpos(','.$fields.',', ',fields29,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[29];
    $html .= '<br>Contract With / Contract By';
    if (strpos(','.$fields.',', ',fields30,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[30];
    $html .= '<br>Working with:';
    $html .= '<br>Saws';
    if (strpos(','.$fields.',', ',fields31,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[31];
    $html .= '<br>Cutting Torch Equipment';
    if (strpos(','.$fields.',', ',fields32,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[32];
    $html .= '<br>Hand Tools';
    if (strpos(','.$fields.',', ',fields33,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[33];
    $html .= '<br>Grinders';
    if (strpos(','.$fields.',', ',fields34,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[34];
    $html .= '<br>Welding Machines';
    if (strpos(','.$fields.',', ',fields35,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[35];
    $html .= '<br>Cranes';
    if (strpos(','.$fields.',', ',fields36,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[36];

    $html .= '<h3>Work Environment Hazards</h3>';
    $html .= '<br>Weather Conditions';
    if (strpos(','.$fields.',', ',fields37,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[37];
    $html .= '<br>Slips or Trips Possible';
    if (strpos(','.$fields.',', ',fields38,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[38];
    $html .= '<br>Waste Material Generated Performing Task';
    if (strpos(','.$fields.',', ',fields39,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[39];
    $html .= '<br>Limited Access / Egress';
    if (strpos(','.$fields.',', ',fields40,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[40];
    $html .= '<br>Foreign Bodies in Eyes';
    if (strpos(','.$fields.',', ',fields41,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[41];
    $html .= '<br>Exposure to Energized Electrical Systems';
    if (strpos(','.$fields.',', ',fields42,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[42];
    $html .= '<br>Lighing Levels Too High/Too Low';
    if (strpos(','.$fields.',', ',fields43,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[43];
    $html .= '<br>Position of Fingers / Hands - Pinch Points';
    if (strpos(','.$fields.',', ',fields44,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[44];
    $html .= '<br>Exposure to:';
    $html .= '<br>Chemicals';
    if (strpos(','.$fields.',', ',fields45,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[45];
    $html .= '<br>Dust/Particulates';
    if (strpos(','.$fields.',', ',fields46,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[46];
    $html .= '<br>Extreme Heat/Cold';
    if (strpos(','.$fields.',', ',fields47,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[47];
    $html .= '<br>Reactive Chemicals';
    if (strpos(','.$fields.',', ',fields48,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[48];
    $html .= '<br>Sharp Objects / Edges';
    if (strpos(','.$fields.',', ',fields49,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[49];
    $html .= '<br>Noise';
    if (strpos(','.$fields.',', ',fields50,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[50];
    $html .= '<br>Odors';
    if (strpos(','.$fields.',', ',fields51,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[51];
    $html .= '<br>Steam';
    if (strpos(','.$fields.',', ',fields52,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[52];
    $html .= '<br>Fogging of Monogoggles / Eye Protection';
    if (strpos(','.$fields.',', ',fields53,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[53];
    $html .= '<br>Flammable Gases / Atmospheric Hazards';
    if (strpos(','.$fields.',', ',fields54,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[54];

    $html .= '<h3>Personal Limitations/Hazards</h3>';
    $html .= '<br>Procedure Not Available for Task';
    if (strpos(','.$fields.',', ',fields55,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[55];
    $html .= '<br>Confusing Instructions';
    if (strpos(','.$fields.',', ',fields56,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[56];
    $html .= '<br>No Training in Procedure / Task';
    if (strpos(','.$fields.',', ',fields57,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[57];
    $html .= '<br>No Training in Tools to be Used';
    if (strpos(','.$fields.',', ',fields58,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[58];
    $html .= '<br>First Time Performing This Task';
    if (strpos(','.$fields.',', ',fields59,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[59];
    $html .= '<br>Mental Limitations / Distractions / Loss of Focus';
    if (strpos(','.$fields.',', ',fields60,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[60];
    $html .= '<br>Not Physically Able to Perform Task';
    if (strpos(','.$fields.',', ',fields61,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[61];
    $html .= '<br>Complacency';
    if (strpos(','.$fields.',', ',fields62,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[62];

    $html .= '<h3>Welding</h3>';
    $html .= '<br>Shields';
    if (strpos(','.$fields.',', ',fields63,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[63];
    $html .= '<br>Fire Blankets';
    if (strpos(','.$fields.',', ',fields64,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[64];
    $html .= '<br>Fire Extinguisher';
    if (strpos(','.$fields.',', ',fields65,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[65];
    $html .= '<br>Cylinder Secured / Secure Connections';
    if (strpos(','.$fields.',', ',fields66,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[66];
    $html .= '<br>Cylinder Caps On';
    if (strpos(','.$fields.',', ',fields67,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[67];
    $html .= '<br>Flashback Arrestor';
    if (strpos(','.$fields.',', ',fields68,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[68];
    $html .= '<br>Combustibles Moved';
    if (strpos(','.$fields.',', ',fields69,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[69];
    $html .= '<br>Sparks Contained';
    if (strpos(','.$fields.',', ',fields70,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[70];
    $html .= '<br>Ground Within 18 Inches';
    if (strpos(','.$fields.',', ',fields71,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[71];
    $html .= '<br>Fire Watch / Spark Watch';
    if (strpos(','.$fields.',', ',fields72,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[72];

    $html .= '<h3>Physical Hazards</h3>';
    $html .= '<br>Manual Lifting';
    $html .= '<br>Load Too Heavy / Awkward To Lift';
    if (strpos(','.$fields.',', ',fields73,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[73];
    $html .= '<br>Over Reaching';
    if (strpos(','.$fields.',', ',fields74,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[74];
    $html .= '<br>Prolonged / Extreme Bending';
    if (strpos(','.$fields.',', ',fields75,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[75];
    $html .= '<br>Repetitive Motions';
    if (strpos(','.$fields.',', ',fields76,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[76];
    $html .= '<br>Unstable Position';
    if (strpos(','.$fields.',', ',fields77,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[77];
    $html .= '<br>Part(s) of Body in Line of Fire';
    if (strpos(','.$fields.',', ',fields78,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[78];
    $html .= '<br>Hands Not in Line of Sight';
    if (strpos(','.$fields.',', ',fields79,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[79];
    $html .= '<br>Working in Tight Clearances';
    if (strpos(','.$fields.',', ',fields80,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[80];
    $html .= '<br>Physical Limitation - Need Assistance';
    if (strpos(','.$fields.',', ',fields81,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[81];
    $html .= '<br>Uncontrolled Release of Energy / Force';
    if (strpos(','.$fields.',', ',fields82,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[82];
    $html .= '<br>Fall Potential';
    if (strpos(','.$fields.',', ',fields83,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[83];

    $html .= '<h3>Common Hazards</h3>';
    $html .= '<br>Overhead Powerlines';
    if (strpos(','.$fields.',', ',fields122,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[122];
    $html .= '<br>Underground Hazards (Gas Lines)';
    if (strpos(','.$fields.',', ',fields123,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[123];
    $html .= '<br>Traffic';
    if (strpos(','.$fields.',', ',fields124,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[124];
    $html .= '<br>Pedestrians';
    if (strpos(','.$fields.',', ',fields125,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[125];
    $html .= '<br>Open Excavation';
    if (strpos(','.$fields.',', ',fields126,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[126];
    $html .= '<br>Working Around Extreme Heat';
    if (strpos(','.$fields.',', ',fields127,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[127];
    $html .= '<br>Heavy Lifting';
    if (strpos(','.$fields.',', ',fields128,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[128];
    $html .= '<br>Working Alone';
    if (strpos(','.$fields.',', ',fields129,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[129];
    $html .= '<br>Weather (heat, rain, snow)';
    if (strpos(','.$fields.',', ',fields130,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[130];
    $html .= '<br>Noise';
    if (strpos(','.$fields.',', ',fields131,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[131];
    $html .= '<br>Working From Heights';
    if (strpos(','.$fields.',', ',fields132,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[132];
    $html .= '<br>Dust, Gases, Fumes';
    if (strpos(','.$fields.',', ',fields133,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[133];
    $html .= '<br>Spraying Chemicals';
    if (strpos(','.$fields.',', ',fields134,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[134];
    $html .= '<br>Faulty Equipment';
    if (strpos(','.$fields.',', ',fields135,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[135];
    $html .= '<br>Branches Hitting Face and Eyes';
    if (strpos(','.$fields.',', ',fields136,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[136];
    $html .= '<br>Slips, Trips, and Falls';
    if (strpos(','.$fields.',', ',fields137,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[137];
    $html .= '<br>Hypothermia/ Frostbite';
    if (strpos(','.$fields.',', ',fields164,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[164];
    $html .= '<br>Poor Lighting';
    if (strpos(','.$fields.',', ',fields165,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[165];
    $html .= '<br>Ergonomic Strain (shoveling)';
    if (strpos(','.$fields.',', ',fields166,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[166];
    $html .= '<br>Other Hazards';
    if (strpos(','.$fields.',', ',fields138,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[138];

    $html .= '<h3>Job Scope</h3>';
    $html .= '<br>Mowing';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Line Painting';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Construction/ Hardscaping';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Irrigation Start Up/ Breakdown';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Irrigation Repair';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Pesticide Spraying';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Summer Maintenance';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Spring Clean Up';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Fall Clean Up';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Power Washing/ Sanding';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Indoor';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Tree Planting/ Removal';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Pruning';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Watering';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Parkade Scrubbing';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Street Sweeping';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Other Job Scope';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];

    $html .= '<h3>Personal Protective Equipment</h3>';
    $html .= '<br>Work Gloves';
    if (strpos(','.$fields.',', ',fields84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[84];
    $html .= '<br>Chemical Gloves';
    if (strpos(','.$fields.',', ',fields85,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[85];
    $html .= '<br>Kevlar Gloves';
    if (strpos(','.$fields.',', ',fields86,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[86];
    $html .= '<br>Rain Gear';
    if (strpos(','.$fields.',', ',fields87,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[87];
    $html .= '<br>Thermal Suits';
    if (strpos(','.$fields.',', ',fields88,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[88];
    $html .= '<br>Rubber Boots';
    if (strpos(','.$fields.',', ',fields89,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[89];
    $html .= '<br>Monogoggles/Faceshield';
    if (strpos(','.$fields.',', ',fields90,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[90];
    $html .= '<br>Safety Glasses';
    if (strpos(','.$fields.',', ',fields91,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[91];
    $html .= '<br>Respiratory Protection';
    if (strpos(','.$fields.',', ',fields92,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[92];
    $html .= '<br>Hearing Protection';
    if (strpos(','.$fields.',', ',fields93,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[93];
    $html .= '<br>Safety Harness/Lanyard/Lifeline';
    if (strpos(','.$fields.',', ',fields94,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[94];
    $html .= '<br>Head Protection';
    if (strpos(','.$fields.',', ',fields95,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[95];
    $html .= '<br>Steel-toed Work Boots';
    if (strpos(','.$fields.',', ',fields96,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[96];
    $html .= '<br>Hi-Vis Vest';
    if (strpos(','.$fields.',', ',fields97,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[97];
    $html .= '<br>Fire Retardant Wear';
    if (strpos(','.$fields.',', ',fields98,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[98];
    $html .= '<br>Cut Proof Gloves/ Clothing';
    if (strpos(','.$fields.',', ',fields156,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[156];

    $html .= '<h3>Walk Around/Inspection</h3>';
    $html .= '<br>Leaks';
    if (strpos(','.$fields.',', ',fields99,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[99];
    $html .= '<br>Oil';
    if (strpos(','.$fields.',', ',fields100,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[100];
    $html .= '<br>Fuel';
    if (strpos(','.$fields.',', ',fields101,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[101];
    $html .= '<br>Tires';
    if (strpos(','.$fields.',', ',fields102,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[102];
    $html .= '<br>Lights';
    if (strpos(','.$fields.',', ',fields103,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[103];
    $html .= '<br>Windows';
    if (strpos(','.$fields.',', ',fields104,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[104];
    $html .= '<br>Hoses';
    if (strpos(','.$fields.',', ',fields105,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[105];
    $html .= '<br>Alarms';
    if (strpos(','.$fields.',', ',fields106,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[106];
    $html .= '<br>Bolts';
    if (strpos(','.$fields.',', ',fields107,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$fields_value[107];

    $html .= '<h3>Is this worker working alone?</h3> '.$working_alone;

    $html .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Task</th>
        <th>Hazard</th>
        <th>Hazard Level</th>
        <th>Plans To Eliminate/Control Risk</th></tr>
    ';

    $all_task_each = explode('**##**',$all_task);

    $total_count = mb_substr_count($all_task,'**##**');
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

    $html .= '<h3>Cliean Up / Close Out- Job Completion</h3>';
    $html .= '<br>Waste containers sealed, labeled and dated?';
    if (strpos(','.$job_complete.',', ',job_complete1,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$job_complete_value[1];
    $html .= '<br>All tools / equipment removed from Task Location?';
    if (strpos(','.$job_complete.',', ',job_complete2,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$job_complete_value[2];
    $html .= '<br>Task area cleaned up at end of job / shift?';
    if (strpos(','.$job_complete.',', ',job_complete3,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '&nbsp;&nbsp;'.$job_complete_value[3];
	
    if (strpos(','.$fields.',', ',fields114,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt=""> Designated First Aider: '.$fields_value[114].'<br />';
    }
    if (strpos(','.$fields.',', ',fields115,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt=""> Single Driver: '.$fields_value[115].'<br />';
    }
    if (strpos(','.$fields.',', ',fields116,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt=""> Comments / Notes: '.$fields_value[116].'<br />';
    }
    if (!empty($crew_leader) || !empty($workers_on_crew)) {
        $html .= '<h3>Signatures (Workers on Crew)</h3>';
        $html .= 'Crew Leader: '.$crew_leader.'<br />';
        $html .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
        $html .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th>Name</th>
            <th>Signature</th>
            </tr>';
        foreach ($workers_on_crew as $worker) {
            $html .= '<tr nobr="true">';
            $html .= '<td data-title="Name">' . $worker . '</td>';
            $html .= '<td data-title="Signature"><img src="field_level_hazard_assessment/download/safety_worker_' . $worker . '.png" width="150" height="70" border="0" alt=""></td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
    }

    $sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html .= '<h3>Signature</h3>';
    $html .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html .= '<tr nobr="true">';
        $html .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';
        $html .= '<td data-title="Email"><img src="field_level_hazard_assessment/download/safety_'.$assign_staff_id.'_'.$fieldlevelriskid.'.png" width="150" height="70" border="0" alt=""></td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('field_level_hazard_assessment/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        unlink("field_level_hazard_assessment/download/safety_".$assign_staff_id.'_'.$fieldlevelriskid.".png");
    }
    foreach ($workers_on_crew as $worker) {
        unlink("field_level_hazard_assessment/download/safety_worker_".$worker.".png");
    }
    echo '';
}

?>