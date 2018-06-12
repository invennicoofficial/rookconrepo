<?php
function field_level_hazard_pdf($dbc,$manualtypeid) {
    $form_by = $_SESSION['contactid'];
    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM form_field_level_risk_assessment WHERE manualtypeid='$manualtypeid' AND contactid='$form_by' AND DATE(today_date) = CURDATE()"));

    $fieldlevelriskid = $get_field_level['fieldlevelriskid'];

    $result_update_employee = mysqli_query($dbc, "UPDATE `form_field_level_risk_assessment` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    $result_update_employee = mysqli_query($dbc, "UPDATE `manuals_staff` SET `done` = 1 WHERE manualtypeid='$manualtypeid' AND staffid='$form_by' AND DATE(today_date) = CURDATE()");

    $today_date = $get_field_level['today_date'];
    $jobid = $get_field_level['jobid'];
    $contactid = $get_field_level['contactid'];
    $location = $get_field_level['location'];
    $assessment_option = $get_field_level['assessment_option'];
    $working_alone = $get_field_level['working_alone'];
    $all_task = $get_field_level['all_task'];
    $job_complete = $get_field_level['job_complete'];
    $worker_name = $get_field_level['worker_name'];
    $foreman_name = $get_field_level['foreman_name'];

    class MYPDF extends TCPDF {

        //Page header
        public function Header() {
            /*$image_file = 'download/'.QUOTE_LOGO;
            $this->Image($image_file, 10, 10, 60, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->SetFont('helvetica', '', 8);
            $header_text = '';
            $this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);
            */
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

    $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);

    $html = '<h2>Field Level Risk Assessment</h2>';

    $html .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Job</th><th>Contact#</th><th>Job Location</th></tr>';
    $html .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$jobid.'</td>
            <td>'.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).'</td><td>'.$location.'</td></tr>';
    $html .= '</table><br><br>';

    $html .= '<b>Permits/Plans</b><br>';

    $html .= 'Hot Work/Cold Work';
    if (strpos(','.$assessment_option.',', ',assessment_option1,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Confined Space';
    if (strpos(','.$assessment_option.',', ',assessment_option2,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Demolition';
    if (strpos(','.$assessment_option.',', ',assessment_option3,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Ground Disturbance';
    if (strpos(','.$assessment_option.',', ',assessment_option108,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Excavation';
    if (strpos(','.$assessment_option.',', ',assessment_option4,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Lockout';
    if (strpos(','.$assessment_option.',', ',assessment_option5,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Critical Lift Plan';
    if (strpos(','.$assessment_option.',', ',assessment_option6,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fall Protection Plan';
    if (strpos(','.$assessment_option.',', ',assessment_option7,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br>';

    $html .= '<b>Permit Identified Hazards</b>';
    $html .= '<br>Hazards Detailed on Safe Work Permit';
    if (strpos(','.$assessment_option.',', ',assessment_option8,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hazards on Critical Lift Permit';
    if (strpos(','.$assessment_option.',', ',assessment_option9,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hazards on Electrical Permit';
    if (strpos(','.$assessment_option.',', ',assessment_option10,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hazards Identified for Confined Space Entry';
    if (strpos(','.$assessment_option.',', ',assessment_option11,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hazards on Confined Space Entry Permit';
    if (strpos(','.$assessment_option.',', ',assessment_option12,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hazards on Hot/Cold Work Permit';
    if (strpos(','.$assessment_option.',', ',assessment_option13,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hazards on Underground/ Excavation, Permit';
    if (strpos(','.$assessment_option.',', ',assessment_option14,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hazards on Line Opening Permit';
    if (strpos(','.$assessment_option.',', ',assessment_option15,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Emergency Equipment</b><br>';
    $html .= '<br>Fire Extinguisher';
    if (strpos(','.$assessment_option.',', ',assessment_option16,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Eyewash/Shower';
    if (strpos(','.$assessment_option.',', ',assessment_option17,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>All Conditions Met';
    if (strpos(','.$assessment_option.',', ',assessment_option109,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Extraction Equipment';
    if (strpos(','.$assessment_option.',', ',assessment_option18,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Permit Displayed';
    if (strpos(','.$assessment_option.',', ',assessment_option19,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Alarm#';

    $html .= '<br><br><b>Overhead Or Working At Height Hazards</b><br>';
    $html .= '<br>Harness Required/Appropriate Tie-off identified';
    if (strpos(','.$assessment_option.',', ',assessment_option20,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Others Working Overhead/Below';
    if (strpos(','.$assessment_option.',', ',assessment_option21,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hoisting or moving loads overhead';
    if (strpos(','.$assessment_option.',', ',assessment_option22,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Falls from Height';
    if (strpos(','.$assessment_option.',', ',assessment_option23,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hoisting or moving Loads Overhead/Around Task';
    if (strpos(','.$assessment_option.',', ',assessment_option24,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Use of Scaffolds';
    if (strpos(','.$assessment_option.',', ',assessment_option110,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Tasks Require You to Work Above Your Task';
    if (strpos(','.$assessment_option.',', ',assessment_option25,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Objects / Debris Falling from Above';
    if (strpos(','.$assessment_option.',', ',assessment_option26,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Overhead Power Line';
    if (strpos(','.$assessment_option.',', ',assessment_option27,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Equipment Hazards</b><br>';
    $html .= '<br>Operating Power Equipment';
    if (strpos(','.$assessment_option.',', ',assessment_option28,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Operating Motor Vehicle / Heavy Equipment';
    if (strpos(','.$assessment_option.',', ',assessment_option29,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Contact with/contact by';
    if (strpos(','.$assessment_option.',', ',assessment_option30,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Working with:';
    $html .= '<br>Saws';
    if (strpos(','.$assessment_option.',', ',assessment_option31,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Cutting Torch Equipment';
    if (strpos(','.$assessment_option.',', ',assessment_option32,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hand Tools';
    if (strpos(','.$assessment_option.',', ',assessment_option33,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Grinders';
    if (strpos(','.$assessment_option.',', ',assessment_option34,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Welding Machines';
    if (strpos(','.$assessment_option.',', ',assessment_option35,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Cranes';
    if (strpos(','.$assessment_option.',', ',assessment_option36,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Work Environment Hazards</b><br>';
    $html .= '<br>Weather Conditions';
    if (strpos(','.$assessment_option.',', ',assessment_option37,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Slips or Trips Possible';
    if (strpos(','.$assessment_option.',', ',assessment_option38,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Waste Material Generated Performing Task';
    if (strpos(','.$assessment_option.',', ',assessment_option39,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Limited Access / Egress';
    if (strpos(','.$assessment_option.',', ',assessment_option40,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Foreign Bodies in Eyes';
    if (strpos(','.$assessment_option.',', ',assessment_option41,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Exposure to Energized Electrical Systems';
    if (strpos(','.$assessment_option.',', ',assessment_option42,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Lighing Levels Too High/Too Low';
    if (strpos(','.$assessment_option.',', ',assessment_option43,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Position of Fingers / Hands - Pinch Points';
    if (strpos(','.$assessment_option.',', ',assessment_option44,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Exposure to:';
    $html .= '<br>Chemicals';
    if (strpos(','.$assessment_option.',', ',assessment_option45,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Dust/Particulates';
    if (strpos(','.$assessment_option.',', ',assessment_option46,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Extreme Heat/Cold';
    if (strpos(','.$assessment_option.',', ',assessment_option47,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Reactive Chemicals';
    if (strpos(','.$assessment_option.',', ',assessment_option48,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Sharp Objects / Edges';
    if (strpos(','.$assessment_option.',', ',assessment_option49,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Noise';
    if (strpos(','.$assessment_option.',', ',assessment_option50,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Odors';
    if (strpos(','.$assessment_option.',', ',assessment_option51,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Steam';
    if (strpos(','.$assessment_option.',', ',assessment_option52,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fogging of Monogoggles / Bye Protection';
    if (strpos(','.$assessment_option.',', ',assessment_option53,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Flammable gases / Atmospheric hazards';
    if (strpos(','.$assessment_option.',', ',assessment_option54,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Personal Limitations/Hazards</b><br>';
    $html .= '<br>Procedure Not Available for Task';
    if (strpos(','.$assessment_option.',', ',assessment_option55,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Confusing Instructions';
    if (strpos(','.$assessment_option.',', ',assessment_option56,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>No Training in Procedure / Task';
    if (strpos(','.$assessment_option.',', ',assessment_option57,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>No Training in Tools to be Used';
    if (strpos(','.$assessment_option.',', ',assessment_option58,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>First Time Performing This Task';
    if (strpos(','.$assessment_option.',', ',assessment_option59,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Mental Limitations / Distractions / Loss of Focus';
    if (strpos(','.$assessment_option.',', ',assessment_option60,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Not Physically Able to Perform Task';
    if (strpos(','.$assessment_option.',', ',assessment_option61,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Complacency';
    if (strpos(','.$assessment_option.',', ',assessment_option62,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Welding</b><br>';
    $html .= '<br>Shields';
    if (strpos(','.$assessment_option.',', ',assessment_option63,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fire Blankets';
    if (strpos(','.$assessment_option.',', ',assessment_option64,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fire Extinguisher';
    if (strpos(','.$assessment_option.',', ',assessment_option65,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Cylinder Secured / Secure Connections';
    if (strpos(','.$assessment_option.',', ',assessment_option66,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Cylinder Caps On';
    if (strpos(','.$assessment_option.',', ',assessment_option67,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Flashback Arrestor';
    if (strpos(','.$assessment_option.',', ',assessment_option68,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Combustibles Moved';
    if (strpos(','.$assessment_option.',', ',assessment_option69,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Sparks Contained';
    if (strpos(','.$assessment_option.',', ',assessment_option70,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Ground within 18 inch';
    if (strpos(','.$assessment_option.',', ',assessment_option71,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fire Watch / Spark Watch';
    if (strpos(','.$assessment_option.',', ',assessment_option72,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Physical Hazards</b><br>';
    $html .= '<br>Manual Lifting';
    $html .= '<br>Load Too Heavy / Awkward to Lift';
    if (strpos(','.$assessment_option.',', ',assessment_option73,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Over Reaching';
    if (strpos(','.$assessment_option.',', ',assessment_option74,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Prolonged / Extreme Bending';
    if (strpos(','.$assessment_option.',', ',assessment_option75,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Repetitive Motions';
    if (strpos(','.$assessment_option.',', ',assessment_option76,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Unstable Position';
    if (strpos(','.$assessment_option.',', ',assessment_option77,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Part(s) of Body in Line of Fire';
    if (strpos(','.$assessment_option.',', ',assessment_option78,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hands Not in Line of Sight';
    if (strpos(','.$assessment_option.',', ',assessment_option79,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Working in Tight Clearances';
    if (strpos(','.$assessment_option.',', ',assessment_option80,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Physical Limitation - Need Assistance';
    if (strpos(','.$assessment_option.',', ',assessment_option81,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Uncontrolled Release of Energy / Force';
    if (strpos(','.$assessment_option.',', ',assessment_option82,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fall Potential';
    if (strpos(','.$assessment_option.',', ',assessment_option83,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Personal Protective Equipment</b><br>';
    $html .= '<br>Work Gloves';
    if (strpos(','.$assessment_option.',', ',assessment_option84,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Chemical Gloves';
    if (strpos(','.$assessment_option.',', ',assessment_option85,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Kevlar Gloves';
    if (strpos(','.$assessment_option.',', ',assessment_option86,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Rain Gear';
    if (strpos(','.$assessment_option.',', ',assessment_option87,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Thermal Suits';
    if (strpos(','.$assessment_option.',', ',assessment_option88,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Rubber Boots';
    if (strpos(','.$assessment_option.',', ',assessment_option89,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Monogoggles/Faceshield';
    if (strpos(','.$assessment_option.',', ',assessment_option90,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Safety Glasses';
    if (strpos(','.$assessment_option.',', ',assessment_option91,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Respiratory Protection';
    if (strpos(','.$assessment_option.',', ',assessment_option92,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hearing Protection';
    if (strpos(','.$assessment_option.',', ',assessment_option93,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Safety Harness/Lanyard/Lifeline';
    if (strpos(','.$assessment_option.',', ',assessment_option94,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Head Protection';
    if (strpos(','.$assessment_option.',', ',assessment_option95,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Steel-toed Work Boots';
    if (strpos(','.$assessment_option.',', ',assessment_option96,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hi-Vis Vest';
    if (strpos(','.$assessment_option.',', ',assessment_option97,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fire Retardant Wear';
    if (strpos(','.$assessment_option.',', ',assessment_option98,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Walk Around/Inspection</b><br>';
    $html .= '<br>Leaks';
    if (strpos(','.$assessment_option.',', ',assessment_option99,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Oil';
    if (strpos(','.$assessment_option.',', ',assessment_option100,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Fuel';
    if (strpos(','.$assessment_option.',', ',assessment_option101,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Tires';
    if (strpos(','.$assessment_option.',', ',assessment_option102,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Lights';
    if (strpos(','.$assessment_option.',', ',assessment_option103,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Windows';
    if (strpos(','.$assessment_option.',', ',assessment_option104,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Hoses';
    if (strpos(','.$assessment_option.',', ',assessment_option105,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Alarms';
    if (strpos(','.$assessment_option.',', ',assessment_option106,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Bolts';
    if (strpos(','.$assessment_option.',', ',assessment_option107,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><b>Is this worker working alone?</b> : '.$working_alone;

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

    $html .= '<br><br><b>Cliean Up / Close Out- Job Completion</b>';
    $html .= '<br>Waste containers sealed, labeled and dated?';
    if (strpos(','.$job_complete.',', ',job_complete1,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>All tools / equipment removed from Task Location?';
    if (strpos(','.$job_complete.',', ',job_complete2,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }
    $html .= '<br>Task area cleaned up at end of job / shift?';
    if (strpos(','.$job_complete.',', ',job_complete3,') !== FALSE) {
        $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
    }

    $html .= '<br><br><h2>'.$worker_name.$foreman_name.'</h2>';
    $html .= '<br><img src="download/flha_'.$today_date.'_'.$manualtypeid.'_'.$contactid.'.png" width="190" height="80" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('download/hazard_'.$fieldlevelriskid.'.pdf', 'F');
    echo 'hi';
}

?>