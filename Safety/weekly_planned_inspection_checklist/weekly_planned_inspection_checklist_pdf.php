<?php
	function weekly_planned_inspection_checklist_pdf($dbc,$safetyid, $fieldlevelriskid) {

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_weekly_planned_inspection_checklist WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
    DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_weekly_planned_inspection_checklist` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $area_inspected = $get_field_level['area_inspected'];
    $permit_type = $get_field_level['permit_type'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $permit_comments = $get_field_level['permit_comments'];
    $personal_comments = $get_field_level['personal_comments'];
    $scaffold_comments = $get_field_level['scaffold_comments'];
    $stbsh_comment = $get_field_level['stbsh_comment'];
    $housekeeping_comments = $get_field_level['housekeeping_comments'];
    $tool_comments = $get_field_level['tool_comments'];
    $mobile_equipment_comments = $get_field_level['mobile_equipment_comments'];
    $miscellaneous_comments = $get_field_level['miscellaneous_comments'];
    $general_comments = $get_field_level['general_comments'];

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

	$html_weekly = '<h2>Weekly Planned Inspection Checklist</h2>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Area Inspected</th><th>Permit type</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$area_inspected.'</td><td>'.$permit_type.'</td></tr>';
    $html_weekly .= '</table>';

	$html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:65%;">Permit Detail</th><th style="width:5%;">Yes</th><th style="width:5%;">No</th><th style="width:25%;">Comment</th></tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Does the permit adequately describe the work and the associated area hazards?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field1_yes,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field1_no,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[1].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are the appropriate controls in place for those hazards?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field2_yes,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field2_no,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[2].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are permits posted or located at the job site?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field3_yes,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field3_no,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[3].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are the personnel using the specified PPE?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field4_yes,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field4_no,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[4].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Is the specified fire protection readily accessible?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field5_yes,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field5_no,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[5].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Does the number of personnel identified on the permit match the number working on the job?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field6_yes,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field6_no,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[6].'</td>';
        $html_weekly .= '</tr>';
    $html_weekly .= '</table>';

	$html_weekly .= "<h3>Permit Comments</h3>".html_entity_decode($permit_comments);

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:55%;">Personnel Detail</th><th style="width:5%;">N/A</th><th style="width:5%;">Poor</th><th style="width:6%;">Good</th><th style="width:6%;">Excellent</th><th style="width:25%;">Comment</th></tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td colspan="6"><h3>As a minimum, are personnel using the following PPE:</h3></td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Hardhat?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field7_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field7_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field7_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field7_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[7].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Safety glasses with side shields or goggles?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field8_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field8_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field8_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field8_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[8].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Hearing protection?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field9_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field9_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field9_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field9_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[9].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Steel-toed boots or shoes?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field10_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field10_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field10_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field10_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[10].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Long sleeves?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field11_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field11_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field11_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field11_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[11].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Gloves (appropriate for the task?)</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field12_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field12_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field12_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field12_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[12].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Fire retardant clothing where required?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field13_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field13_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field13_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field13_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[13].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td colspan="6"><h3>Is specialized PPE required for the task and being used properly:</h3></td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Fall Protection</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field14_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field14_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field14_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field14_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[14].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Respiratory Protection</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field15_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field15_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field15_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field15_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[15].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Other:</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field16_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field16_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field16_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field16_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[16].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Do personnel know where the nearest EMP and Assembly Area are?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field17_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field17_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field17_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field17_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[17].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Do personnel know whom to contact in the event of an emergency?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field18_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field18_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field18_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field18_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[18].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Do personnel know whom to contact to report a safety concern/issue?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field19_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field19_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field19_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field19_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[19].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Do personnel know where the procedure is located for the task they are performing?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field20_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field20_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field20_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field20_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[20].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are personnel ensuring they are out of the line of fire - safe positions, no pinch points, not in danger of overreaching, falling, sliding, etc.</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field21_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field21_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field21_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field21_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[21].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are personnel focused on the task at hand - eyes and mind on tasks, good view of work</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field22_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field22_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field22_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field22_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[22].'</td>';
        $html_weekly .= '</tr>';

    $html_weekly .= '</table>';

	$html_weekly .= "<h3>Personnel Comments</h3>".html_entity_decode($personal_comments);

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:55%;">Scaffold Detail</th><th style="width:5%;">N/A</th><th style="width:5%;">Poor</th><th style="width:6%;">Good</th><th style="width:6%;">Excellent</th><th style="width:25%;">Comment</th></tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Is an updated tag (21 days max.) in place at each entrance to a scaffold structure?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field23_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field23_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field23_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field23_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[23].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are the scaffold platforms clear of tools, materials, debris, and other tripping hazards?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field24_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field24_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field24_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field24_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[24].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are 4-inch toe boards installed on the scaffold platform?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field25_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field25_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field25_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field25_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[25].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are guardrails in place, or other precautions taken for all scaffold platforms that are greater than 10 feet (3m) above the ground?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field26_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field26_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field26_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field26_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[26].'</td>';
        $html_weekly .= '</tr>';

    $html_weekly .= '</table>';


	$html_weekly .= "<h3>Scaffold Comments</h3>".html_entity_decode($scaffold_comments);

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:55%;">Signs/Tags/Barricades/Screens/Hoardings Detail</th><th style="width:5%;">N/A</th><th style="width:5%;">Poor</th><th style="width:6%;">Good</th><th style="width:6%;">Excellent</th><th style="width:25%;">Comment</th></tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are confined space entry points closed off with appropriate signage when a "Confined Space Monitor" is not present?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field27_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field27_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field27_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field27_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[27].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are overhead hazards and hazardous areas identified using "caution" or "Do Not Enter" flagging and is the flagging tagged to identify the hazards?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field28_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field28_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field28_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field28_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[28].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Hearing protection signs posted?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field29_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field29_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field29_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field29_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[29].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are barricades being utilized if required?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field30_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field30_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field30_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field30_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[30].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are screens, blankets, hoardings being used to protect other personnel where welding, grinding activities are taking place?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field31_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field31_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field31_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field31_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[31].'</td>';
        $html_weekly .= '</tr>';

    $html_weekly .= '</table>';


	$html_weekly .= "<h3>Signs/Tags/Barricades/Screens/Hoardings Comments</h3>".html_entity_decode($stbsh_comment);

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:55%;">Housekeeping Detail</th><th style="width:5%;">N/A</th><th style="width:5%;">Poor</th><th style="width:6%;">Good</th><th style="width:6%;">Excellent</th><th style="width:25%;">Comment</th></tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are aisles, stairways, and doorways free of clutter?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field32_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field32_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field32_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field32_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[32].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Is unrestricted access provided to all safety showers, PPE, fire-fighting equipment and emergency exits?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field33_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field33_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field33_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field33_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[33].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are floors free of grease, oil, and other slipping hazards?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field34_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field34_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field34_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field34_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[34].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Has all garbage been placed in appropriate bins and have the bins been emptied as required?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field35_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field35_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field35_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field35_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[35].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Have all unused hoses been removed?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field36_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field36_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field36_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field36_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[36].'</td>';
        $html_weekly .= '</tr>';


	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are hoses, ext. cords, and welder cables routed to minimize tripping hazards?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field37_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field37_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field37_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field37_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[37].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Is lighting adequate and functioning?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field38_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field38_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field38_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field38_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[38].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are ladder access gates in place and closed?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field39_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field39_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field39_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field39_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[39].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are portable ladders properly positioned and tied off? (1:4)</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field40_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field40_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field40_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field40_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[40].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are ladders being used properly?  (not standing on top platform or top 2 rungs)</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field41_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field41_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field41_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field41_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[41].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are ladders protruding 1m above intended landing point?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field42_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field42_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field42_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field42_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[42].'</td>';
        $html_weekly .= '</tr>';

    $html_weekly .= '</table>';

	$html_weekly .= "<h3>Housekeeping Comments</h3>".html_entity_decode($housekeeping_comments);

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:55%;">Tools Detail</th><th style="width:5%;">N/A</th><th style="width:5%;">Poor</th><th style="width:6%;">Good</th><th style="width:6%;">Excellent</th><th style="width:25%;">Comment</th></tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are tools in good working condition and free of defects (i.e.: frayed cords, broken housings, cracks in metal)?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field43_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field43_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field43_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field43_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[43].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are the required guards in place and safety precautions being followed?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field44_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field44_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field44_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field44_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[44].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are the tools being used for their intended purpose?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field45_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field45_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field45_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field45_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[45].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are air hoses equipped with Chicago couplings, pins and whipchecks?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field46_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field46_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field46_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field46_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[46].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are welding machines grounded?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field47_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field47_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field47_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field47_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[47].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>If welding machines are not in use are valves closed and regulators and hoses removed?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field48_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field48_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field48_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field48_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[48].'</td>';
        $html_weekly .= '</tr>';

    $html_weekly .= '</table>';


	$html_weekly .= "<h3>Tools Comments</h3>".html_entity_decode($tool_comments);

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:55%;">Mobile Equipment Detail</th><th style="width:5%;">N/A</th><th style="width:5%;">Poor</th><th style="width:6%;">Good</th><th style="width:6%;">Excellent</th><th style="width:25%;">Comment</th></tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Is proper equipment being used for the task? (Crane, Forklift, AWP, etc)</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field49_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field49_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field49_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field49_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[49].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>If AWP is being used are occupants tied off? Properly?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field50_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field50_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field50_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field50_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[50].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Has the equipment been inspected prior to use?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field51_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field51_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field51_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field51_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[51].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Is the equipment in good condition? (cab housekeeping, lights, horn, backup alarm, fire extinguisher)</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field52_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field52_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field52_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field52_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[52].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Is a spotter required and being used for this job?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field53_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field53_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field53_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field53_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[53].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>If lifts are being done are proper procedures being used? (signaler/s identified by armbands, area flagged off and/or horns being used to alert others)</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field54_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field54_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field54_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field54_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[54].'</td>';
        $html_weekly .= '</tr>';

    $html_weekly .= '</table>';

	$html_weekly .= "<h3>Mobile Equipment Comments</h3>".html_entity_decode($mobile_equipment_comments);

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
        <tr nobr="true" style="background-color:lightgrey; color:black;">
            <th style="width:55%;">Miscellaneous Detail</th><th style="width:5%;">N/A</th><th style="width:5%;">Poor</th><th style="width:6%;">Good</th><th style="width:6%;">Excellent</th><th style="width:25%;">Comment</th></tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td colspan="6"><h3>Fire Extinguishers</h3></td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are fire extinguishers in proper places - as required for the job?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field55_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field55_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field55_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field55_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[55].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are the right type of fire extinguishers in place?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field56_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field56_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field56_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field56_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[56].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Are they in good condition, working order?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field57_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field57_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field57_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field57_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[57].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Have they been recently inspected?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field58_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field58_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field58_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field58_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[58].'</td>';
        $html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td colspan="6"><h3>FLRA:</h3></td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Have personnel done their own FLRA?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field59_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field59_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field59_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field59_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[59].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Does FLRA accurately describe work being performed?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field60_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field60_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field60_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field60_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[60].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Have all of the workers been part of the development and/or signed off on the FLRA?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field61_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field61_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field61_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field61_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[61].'</td>';
        $html_weekly .= '</tr>';

	    $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td>Have all the hazards been identified on the FLRA?</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field62_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field62_poor,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field62_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td>';
        if (strpos(','.$fields.',', ',field62_exe,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[62].'</td>';
        $html_weekly .= '</tr>';

    $html_weekly .= '</table>';

	$html_weekly .= "<h3>Miscellaneous Comments</h3>".html_entity_decode($miscellaneous_comments);
	$html_weekly .= "<h3>Additional Comments</h3>".html_entity_decode($general_comments);

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
        $html_weekly .= '<td>' . $row_sa['assign_staff'] . '</td>';

        // avs_near_miss = form name

        $html_weekly .= '<td><img src="weekly_planned_inspection_checklist/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('weekly_planned_inspection_checklist/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("weekly_planned_inspection_checklist/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>