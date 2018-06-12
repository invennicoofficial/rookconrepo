<?php
function treatment_plan_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

    //$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_patientform WHERE form='Treatment Plan'"));
    //DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
    DEFINE('PDF_LOGO', '');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_treatment_plan WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$fields_value = explode('**FFM**', $get_field_level['fields_value']);

	class MYPDF extends TCPDF {
        public function Header() {
            if(PDF_LOGO != '') {
                $image_file = 'download/'.PDF_LOGO;
                $this->Image($image_file, 10, 10, 60, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
    $pdf->SetFont('helvetica', '', 10);

	$html = '<form action="" method="POST" enctype="multipart/form-data">';

	$html .= '<table style="border:none; width:100%; border-collapse:separate; border-spacing:10px;">';
	$html .= '<tr><td colspan="2" style="text-align: right;"><big><b>Concluding Report</b></big><br />
		<b>Form AB-4</b><br />
		<small>For accidents that occur on or after <b>October 1, 2004</b></small></td></tr>';
	$html .= '<tr><td style="margin-right: 1em;border:1px solid black;vertical-align: top; width:33%">';
	$html .= '<h4 style="margin: 0; text-align: center;">Send this form to the<br>appropriate insurer:</h4>
			<p><b>Fax #:</b> <input type="text" value="'.$fields_value[1].'" name="insurer_fax" style="border:none;" size="20"></p>';
	$html .= '</td><td style="width:67%;">';
	$html .= '<table style="border:1px solid black; width:100%; margin:0;"><tbody>';
	$html .= '<tr><td colspan="2"><h4 style="margin:0;height:30px;text-align:center;">To be completed by Claimant / Representative<br>or a Primary Health Care Practitioner</h4></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Insurance Company</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.get_all_form_contact($dbc, $fields_value[2], 'name').'" name="insurance_company" style="border:none;" size="32"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Policy Number</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[3].'" name="policy_number" style="border:none;" size="32"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Date of Accident<br>(DD-MM-YYYY)</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[4].'" name="accident_date" style="border:none;" size="32"></td></tr>';
	$html .= '</tbody></table></td></tr>';
	
	// Part 1
	$html .= '<tr><td colspan="2">&nbsp;<br /><span style="padding:5px;background-color:#000;color:#fff;">Part 1 - Claimant Information</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:30%; border:1px solid black;"><small>Last Name</small><br /><input type="text" name="client_last" value="'.get_contact($dbc, $fields_value[5], 'last_name').'" size="25"></td>
		<td style="width:40%; border:1px solid black;"><small>First Name</small><br /><input type="text" name="client_first" value="'.get_contact($dbc, $fields_value[5], 'first_name').'" size="30"></td>
		<td style="width:30%; border:1px solid black;"><small>Date of Birth (DD/MM/YYYY)</small><br /><input type="text" name="client_birth" value="'.get_all_form_contact($dbc, $fields_value[5], 'birth_date').'" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>Date of Initial Assessment (DD/MM/YYYY)</small><br /><input type="text" name="client_accident" value="'.$fields_value[6].'" size="30"></td>
		<td style="width:60%;"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 2
	$html .= '<tr><td colspan="2">&nbsp;<br /><span style="padding:5px;background-color:#000;color:#fff;">Part 2 - Information of Primary Health Care Practitioner</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:70%; border:1px solid black;"><small>Name of Professional</small><br /><input type="text" name="pro_name" value="'.get_contact($dbc, $fields_value[7]).'" size="55"></td>
		<td style="width:30%; border:1px solid black;"><small>Profession</small><br /><input type="text" name="profession" value="'.get_contact($dbc, $fields_value[7], 'position').'" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Address</small><br /><input type="text" name="rep_address" value="'.$fields_value[8].'" size="80"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>City, Town or Country</small><br /><input type="text" name="rep_city" value="'.$fields_value[9].'" size="30"></td>
		<td style="width:40%; border:1px solid black;"><small>Province</small><br /><input type="text" name="rep_province" value="'.$fields_value[10].'" size="30"></td>
		<td style="width:20%; border:1px solid black;"><small>Postal Code</small><br /><input type="text" name="rep_postal" value="'.$fields_value[11].'" size="15"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Scheduling Contact Name</small><br /><input type="text" name="schedule_contact" value="'.$fields_value[12].'" size="40"></td>
		<td style="width:50%; border:1px solid black;"><small>Facility Name</small><br /><input type="text" name="facility_name" value="'.$fields_value[13].'" size="40"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Telephone Number (include area code)</small><br /><input type="text" name="health_telephone" value="'.$fields_value[14].'" size="40"></td>
		<td style="width:50%; border:1px solid black;"><small>Fax Number (include area code)</small><br /><input type="text" name="health_fax_number" value="'.$fields_value[15].'" size="40"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 3
	$html .= '<tr><td colspan="2">&nbsp;<br /><span style="padding:5px;background-color:#000;color:#fff;">Part 3 - Assessment Status</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;">';
	$html .= '<p><small>Diagnosis:</small><br /><textarea name="initial_diagnosis" cols="85" rows="3">'.htmlentities(strip_tags(html_entity_decode($fields_value[16]))).'</textarea><br /><br /></p></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;">';
	$html .= '<p><small>Key Subjective/Physical Examination Findings at the last visit:</small><br /><textarea name="last_findings" cols="85" rows="6">'.htmlentities(strip_tags(html_entity_decode($fields_value[17]))).'</textarea><br /><br /><br /><br /><br /></p></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;">';
	$html .= '<p><small>Functional Goals:</small><br />1. <textarea name="functional_goal_1" cols="40" rows="2">'.$fields_value[18].'</textarea><br />';
	$html .= '<br />2. <textarea name="functional_goal_2" cols="40" rows="2">'.$fields_value[19].'</textarea><br />';
	$html .= '<br />3. <textarea name="functional_goal_3" cols="40" rows="2">'.$fields_value[20].'</textarea><br /></p></td><td style="width:50%; border:1px solid black;">';
	$html .= '<p><small>Progress towards goals:<br />';
	$html .= '<input type="radio" name="goal_progress" value="Regressed" '.($fields_value[21] == 'Regressed' ? 'checked="checked"' : '').'> Regressed<br />
				<input type="radio" name="goal_progress" value="Minimally" '.($fields_value[21] == 'Improved Minimally' ? 'checked="checked"' : '').'> Improved Minimally<br />
				<input type="radio" name="goal_progress" value="Significantly" '.($fields_value[21] == 'Improved Significantly' ? 'checked="checked"' : '').'> Improved Significantly<br />
				<input type="radio" name="goal_progress" value="Resolved" '.($fields_value[21] == 'Resolved' ? 'checked="checked"' : '').'> Resolved<br />
				<input type="radio" name="goal_progress" value="Plateaued" '.($fields_value[21] == 'Plateaued' ? 'checked="checked"' : '').'> Plateaued<br />
				<input type="radio" name="goal_progress" value="Other" '.($fields_value[21] == 'Other' ? 'checked="checked"' : '').'> Other (please describe)<br />
				<input type="text" name="diagnosis_other" value="'.$fields_value[22].'" size="40"></small></p></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 4
	$html .= '<tr><td colspan="2">&nbsp;<br /><span style="padding:5px;background-color:#000;color:#fff;">Part 4 - Treatment Summary</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:25%; border:1px solid black;"><small>Total Number of Treatments</small><br />
		<input type="text" name="number_treatments" value="'.$fields_value[23].'" size="20"></td>
		<td style="width:25%; border:1px solid black;"><small>Date of First Visit</small><br />
		<input type="text" name="first_visit_date" value="'.$fields_value[24].'" size="20"></td>
		<td style="width:25%; border:1px solid black;"><small>Date of Last isit</small><br />
		<input type="text" name="last_visit_date" value="'.$fields_value[25].'" size="20"></td>
		<td style="width:25%; border:1px solid black;"><small>Total Cancelled/Missed Visits</small><br />
		<input type="text" name="missed_visits" value="'.$fields_value[26].'" size="20"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 5
	$html .= '<tr nobr="true"><td colspan="2">&nbsp;<br /><span style="padding:5px;background-color:#000;color:#fff;">Part 5 - Reason for Discharge or need for ongoing Treatment</span><br />';
	$html .= '<table style="width:100%; border:1px solid black;">';
	$html .= '<tr><td style="width:20%;"><small><input type="radio" name="discharge_reason" value="Full" '.($fields_value[27] == '' ? 'checked="checked"' : '').'> Full Recovery<br />
		<input type="radio" name="discharge_reason" value="Partial" '.($fields_value[27] == 'Partial Recovery' ? 'checked="checked"' : '').'> Partial Recovery<br />
		<input type="radio" name="discharge_reason" value="Plateaued" '.($fields_value[27] == 'Plateaued' ? 'checked="checked"' : '').'> Plateaued<br />
		<input type="radio" name="discharge_reason" value="No" '.($fields_value[27] == 'No Progress' ? 'checked="checked"' : '').'> No Progress
		</small></td>';
	$html .= '<td style="width:30%;"><small><input type="radio" name="discharge_transfer" value="Transferred" '.($fields_value[27] == 'Transferred to another treatment site' ? 'checked="checked"' : '').'> Transferred to another treatment site<br />
		<input type="radio" name="discharge_transfer" value="NonAttendance" '.($fields_value[27] == 'Non-Attendance' ? 'checked="checked"' : '').'> Non-Attendance<br />
		<input type="radio" name="discharge_transfer" value="Compliance" '.($fields_value[27] == 'Poor Compliance' ? 'checked="checked"' : '').'> Poor Compliance<br />
		<input type="radio" name="discharge_transfer" value="Contact" '.($fields_value[27] == 'No Contact' ? 'checked="checked"' : '').'> No Contact
		</small></td>';
	$html .= '<td style="width:50%;"><small>
		<input type="radio" name="discharge_transfer_other" value="Other" '.($fields_value[27] == 'Other' ? 'checked="checked"' : '').'> Other<br />
		<textarea name="discharge_reason_other" cols="45" rows="3">'.$fields_value[28].'</textarea><br /><br /></small></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 6
	$html .= '<tr><td colspan="2">&nbsp;<br /><span style="padding:5px;background-color:#000;color:#fff;">Part 6 - Discharge Status</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:25%; border:1px solid black;"><small>Is the claimant now working?<br />
		<input type="radio" name="discharge_working" value="Yes" '.($fields_value[29] == 'Yes' ? 'checked="checked"' : '').'> Yes<br />
		<input type="radio" name="discharge_working" value="No" '.($fields_value[29] == 'No' ? 'checked="checked"' : '').'> No<br />
		<input type="radio" name="discharge_working" value="Unknown" '.($fields_value[29] == 'Unknown' ? 'checked="checked"' : '').'> Unknown</small></td>
		<td style="width:40%; border:1px solid black;"><small>Are they employed or engaged in training activities?</small><br />
			<table style="width:100%;"><tr><td style="width:60%"><small><input type="radio" name="discharge_employed" value="Full" '.($fields_value[30] == 'Full Time' ? 'checked="checked"' : '').'> Full Time<br />
				<input type="radio" name="discharge_employed" value="Part" '.($fields_value[30] == 'Part Time' ? 'checked="checked"' : '').'> Part Time<br />
				<input type="radio" name="discharge_employed" value="Seasonal" '.($fields_value[30] == 'Seasonal' ? 'checked="checked"' : '').'> Seasonal<br />
				<input type="radio" name="discharge_employed" value="SelfEmployed" '.($fields_value[30] == 'Self-Employed' ? 'checked="checked"' : '').'> Self-Employed</small></td>
			<td style="width:40%"><small><input type="radio" name="discharge_employed" value="Retired" '.($fields_value[30] == 'Retired' ? 'checked="checked"' : '').'> Retired<br />
				<input type="radio" name="discharge_employed" value="Student" '.($fields_value[30] == 'Student' ? 'checked="checked"' : '').'> Student<br />
				<input type="radio" name="discharge_employed" value="Unemployed" '.($fields_value[30] == 'Not Employed' ? 'checked="checked"' : '').'> Not Employed</small></td></tr></table></td>
		<td style="width:35%; border:1px solid black;"><small>Work or Training Restrictions?</small><br />
			<table style="width:100%;"><tr><td style="width:25%"><small><input type="radio" name="discharge_restricted" value="None" '.($fields_value[31] == 'None' ? 'checked="checked"' : '').'> None<br />
				<input type="radio" name="discharge_restricted" value="Yes" '.($fields_value[31] != 'None' ? 'checked="checked"' : '').'> Yes</small></td>
			<td style="width:75%"><small>If Yes,<br />
				<input type="radio" name="discharge_restriction" value="Temporary" '.($fields_value[31] == 'Temporary Restriction' ? 'checked="checked"' : '').'> Temporary Restriction<br />
				<input type="radio" name="discharge_restriction" value="Permanent" '.($fields_value[31] == 'Permanent Restriction' ? 'checked="checked"' : '').'> Permanent Restriction</small></td></tr></table></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Has the claimant returned to a pre-accident level of activity outside work?<br />
		<input type="radio" name="discharge_pre_level" value="Yes" '.($fields_value[32] == 'Yes' ? 'checked="checked"' : '').'> Yes<br />
		<input type="radio" name="discharge_pre_level" value="No" '.($fields_value[32] == 'No' ? 'checked="checked"' : '').'> No</small></td>
		<td style="width:50%; border:1px solid black;"><table style="width:100%"><tr><td style="width:60%"><small>Did you refer the claimant to any other<br />health care provider(s)?<br />
			<input type="radio" name="discharge_refer" value="Yes" '.($fields_value[33] == 'Yes' ? 'checked="checked"' : '').'> Yes<br />
			<input type="radio" name="discharge_refer" value="No" '.($fields_value[33] == 'No' ? 'checked="checked"' : '').'> No</small></td>
		<td style="width:40%"><small>If yes, who?<br /><textarea name="refer_to_who" cols="25" rows="3">'.$fields_value[34].'</textarea><br /><br /></small></td></tr></table></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Discharge comments (residual symptoms, signs, prognosis, details of exercise program, etc.):</small><br />
		<textarea name="discharge_comments" cols="85" rows="6">'.htmlentities(strip_tags(html_entity_decode($fields_value[35]))).'</textarea><br /><br /><br /><br /><br /></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 7
	$html .= '<tr><td colspan="2">&nbsp;<br /><span style="padding:5px;background-color:#000;color:#fff;">Part 7 - Signature of Primary Health Care Practitioner</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;">Name (Please Print) <input type="text" name="primary_health_name" value="'.get_contact($dbc, $fields_value[36]).'" size="40"><br /><br /><br />
		Signature: <img src="wcb_ab_forms/download/sign1_'.$fieldlevelriskid.'.png" height="30" border="0" alt="" style="border-bottom:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Date: <input type="text" name="primary_health_date" value="'.$fields_value[37].'" size="20"><br />&nbsp;</td></tr></table>';
	$html .= '</td></tr>';
	
	// End of Form
	$html .= '</table></form>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('wcb_ab_forms/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');
}
?>