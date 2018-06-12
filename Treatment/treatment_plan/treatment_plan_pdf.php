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
	$html .= '<tr><td colspan="2" style="text-align: right;"><big><b>Treatment Plan</b></big><br />
		<b>Form AB-2</b><br />
		<small>For accidents that occur on or after <b>October 1, 2004</b></small></td></tr>';
	$html .= '<tr><td style="margin-right: 1em;border:1px solid black;vertical-align: top; width:33%">';
	if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
		$html .= '<h4 style="margin: 0; text-align: center;">Send this form to the<br>appropriate insurer:</h4>
			<p><b>Fax #:</b> <input type="text" value="'.$fields_value[1].'" name="insurer_fax" style="border:none;" size="20"></p>';
	}
	$html .= '</td><td style="width:67%;">';
	$html .= '<table style="border:1px solid black; width:100%; margin:0;"><tbody>';
	$html .= '<tr><td colspan="2"><h4 style="margin:0;height:30px;text-align:center;">To be completed by Claimant / Representative<br>or a Primary Health Care Practitioner</h4></td></tr>';
	if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
		$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Insurance Company</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.get_client($dbc, $fields_value[2]).'" name="insurance_company" style="border:none;" size="32"></td></tr>';
	}
	if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
		$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Policy Number</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[3].'" name="policy_number" style="border:none;" size="32"></td></tr>';
	}
	if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
		$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Date of Accident<br>(DD-MM-YYYY)</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[4].'" name="accident_date" style="border:none;" size="32"></td></tr>';
	}
	$html .= '</tbody></table></td></tr>';
	
	// Part 1
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 1 - Claimant Information</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:30%; border:1px solid black;"><small>Last Name</small><br /><input type="text" name="client_last" value="'.get_contact($dbc, $fields_value[5], 'last_name').'" size="25"></td>
		<td style="width:40%; border:1px solid black;"><small>First Name</small><br /><input type="text" name="client_first" value="'.get_contact($dbc, $fields_value[5], 'first_name').'" size="30"></td>
		<td style="width:30%; border:1px solid black;"><small>Date of Birth (DD/MM/YYYY)</small><br /><input type="text" name="client_birth" value="'.get_contact($dbc, $fields_value[5], 'birth_date').'" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>Date of Accident (DD/MM/YYYY)</small><br /><input type="text" name="client_accident" value="'.$fields_value[6].'" size="30"></td>
		<td style="width:60%;"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 2
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 2 - Claimant\'s Authorized Representative</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:30%; border:1px solid black;"><small>Last Name</small><br /><input type="text" name="rep_last" value="'.get_contact($dbc, $fields_value[5], 'last_name').'" size="25"></td>
		<td style="width:40%; border:1px solid black;"><small>First Name</small><br /><input type="text" name="rep_first" value="'.get_contact($dbc, $fields_value[5], 'first_name').'" size="30"></td>
		<td style="width:30%; border:1px solid black;"><small>Middle Name(s)</small><br /><input type="text" name="rep_middle" value="'.get_contact($dbc, $fields_value[5], 'birth_date').'" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Address</small><br /><input type="text" name="rep_address" value="'.$fields_value[8].'" size="80"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>City, Town or Country</small><br /><input type="text" name="rep_city" value="'.$fields_value[9].'" size="30"></td>
		<td style="width:40%; border:1px solid black;"><small>Province</small><br /><input type="text" name="rep_province" value="'.$fields_value[10].'" size="30"></td>
		<td style="width:20%; border:1px solid black;"><small>Postal Code</small><br /><input type="text" name="rep_postal" value="'.$fields_value[11].'" size="15"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Relationship with Claimant</small><br /><input type="text" name="rep_relationship" value="'.$fields_value[12].'" size="80"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:35%; border:1px solid black;"><small>Telephone Number (Include area code)</small><br /><input type="text" name="rep_phone_1" value="'.$fields_value[13].'" size="28"></td>
		<td style="width:35%; border:1px solid black;"><small>Telephone Number (Include area code)</small><br /><input type="text" name="rep_phone_2" value="'.$fields_value[14].'" size="28"></td>
		<td style="width:30%; border:1px solid black;"><small>Fax Number (Include area code)</small><br /><input type="text" name="rep_fax" value="'.$fields_value[15].'" size="24"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 3
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 3 - Therapy Status Report <i>(To be completed by Primary Health Care Practitioner)</i></span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;">';
	if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
		$html .= '<p><b><u>Diagnosis</u></b><input type="text" name="diagnosis" value="'.html_entity_decode($fields_value[16]).'" size="75"></p>';
	}
	if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
		$html .= '<p><b><u>Key Subjective/Physical Examination Findings</u></b><br /><textarea name="diagnosis_findings" cols="85" rows="2">'.html_entity_decode($fields_value[17])."</textarea><br /></p>";
	}
	if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
		$html .= '<p><b><u>C/O</u></b><textarea name="diagnosis_co" cols="80" rows="4">'.html_entity_decode($fields_value[18])."</textarea><br /><br /><br /></p>";
	}
	if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
		$html .= '<p><b><u>O/E</u></b><textarea name="diagnosis_oe" cols="80" rows="4">'.html_entity_decode($fields_value[19])."</textarea><br /><br /><br /><br /></p>";
	}
	$html .= '</td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:30%; border:1px solid black;"><p style="text-align:center;"><small>Diagnosis</small></p>
			<small>Sprain</small><br />1 <input type="radio" name="diagnosis_sprain" value="1" '.($fields_value[20] == '1' ? 'checked="checked"' : '').'>
				2 <input type="radio" name="diagnosis_sprain" value="2" '.($fields_value[20] == '2' ? 'checked="checked"' : '').'>
				3 <input type="radio" name="diagnosis_sprain" value="3" '.($fields_value[20] == '3' ? 'checked="checked"' : '').'><br />
			<small>Strain</small><br />1 <input type="radio" name="diagnosis_strain" value="1" '.($fields_value[21] == '1' ? 'checked="checked"' : '').'>
				2 <input type="radio" name="diagnosis_strain" value="2" '.($fields_value[21] == '2' ? 'checked="checked"' : '').'>
				3 <input type="radio" name="diagnosis_strain" value="3" '.($fields_value[21] == '3' ? 'checked="checked"' : '').'><br />
			<small>WAD</small><br />1 <input type="radio" name="diagnosis_wad" value="1" '.($fields_value[22] == '1' ? 'checked="checked"' : '').'>
				2 <input type="radio" name="diagnosis_wad" value="2" '.($fields_value[22] == '2' ? 'checked="checked"' : '').'>
				3 <input type="radio" name="diagnosis_wad" value="3" '.($fields_value[22] == '3' ? 'checked="checked"' : '').'>
				4 <input type="radio" name="diagnosis_wad" value="4" '.($fields_value[22] == '4' ? 'checked="checked"' : '').'><br />
			<small>Other</small><input type="text" name="diagnosis_other" value="'.$fields_value[23].'" size="20"></td>
		<td style="width:40%; border:1px solid black;"><p style="text-align:center;"><small>ICD-10-CA Injury Code*</small></p>
			<textarea name="diagnosis_icd_code" cols="32" rows="6">'.html_entity_decode($fields_value[24]).'</textarea></td>
		<td style="width:30%; border:1px solid black;"><p style="text-align:center;"><small>Outcome measures</small></p>
			<small>Patient Specific functional Scale: <input type="text" name="outcome_functional" value="'.$fields_value[25].'" size="4"> / 30</small><br />
			<small>Neck Disability Index: <input type="text" name="outcome_neck" value="'.$fields_value[26].'" size="4"> / 50</small><br />
			<small>Roland Morris (back pain): <input type="text" name="outcome_morris" value="'.$fields_value[27].'" size="4"> / 24</small><br />
			<small>Static Endurance Text=</small><input type="text" name="outcome_endurance" value="'.$fields_value[28].'" size="12"><br />
			<small>Chin Tuck Head Lift=</small><input type="text" name="outcome_chin_tuck" value="'.$fields_value[29].'" size="13"><br />
			<small>Prone Plank=</small><input type="text" name="outcome_plank" value="'.$fields_value[30].'" size="17"><br /></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Is the claimant employed or engaged in training activities?</small><br />
		<input type="radio" name="diagnosis_employed" value="Full Time" '.($fields_value[31] == 'Full Time' ? 'checked="checked"' : '').'> Full Time
		<input type="radio" name="diagnosis_employed" value="Part Time" '.($fields_value[31] == 'Part Time' ? 'checked="checked"' : '').'> Part Time
		<input type="radio" name="diagnosis_employed" value="Seasonal" '.($fields_value[31] == 'Seasonal' ? 'checked="checked"' : '').'> Seasonal
		<input type="radio" name="diagnosis_employed" value="Self employed" '.($fields_value[31] == 'Self-employed' ? 'checked="checked"' : '').'> Self-employed
		<input type="radio" name="diagnosis_employed" value="Retired" '.($fields_value[31] == 'Retired' ? 'checked="checked"' : '').'> Retired
		<input type="radio" name="diagnosis_employed" value="Student" '.($fields_value[31] == 'Student' ? 'checked="checked"' : '').'> Student
		<input type="radio" name="diagnosis_employed" value="Not employed" '.($fields_value[31] == 'Not employed' ? 'checked="checked"' : '').'> Not employed</td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>*ICD-10-CA injury codes are only required for Sprains, Strains and WAD injuries.  It is recommended, not required, that ICD-10-CA injury codes be used for other injuries when practical.</small></td></tr></table>';
	$html .= '</td></tr>';
	$html .= '<tr><td colspan="2"><table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><p><small>Functional Goals (outcomes to be measured):</small></p>
		<p>1. <input type="checkbox" name="diagnosis_restore_rom" value="Restore ROM - self stretches & Manual Therapy" '.($fields_value[32] == '' ? '' : 'checked="checked"').'> Restore ROM - self stretches & Manual Therapy</p>
		<p>2. <input type="checkbox" name="diagnosis_restore_strength" value="Restore Strength - spinal exercises" '.($fields_value[33] == '' ? '' : 'checked="checked"').'> Restore Strength - spinal exercises</p>
		<p>3. <input type="checkbox" name="diagnosis_restore_endurance" value="Restore Endurance, Function for Work and Play" '.($fields_value[34] == '' ? '' : 'checked="checked"').'> Restore Endurance, Function for Work and Play</p></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Comments</small><br />
		<textarea name="diagnosis_comments" cols="85" rows="3">'.html_entity_decode($fields_value[35]).'</textarea><br /><br /></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Expected Number of Visits</small><br /><input type="text" name="diagnosis_visits" value="'.$fields_value[36].'" size="30"></td>
		<td style="width:50%; border:1px solid black;"><small>Date of expected treatment discharge (DD/MM/YYYY)</small><br /><input type="text" name="diagnosis_discharge" value="'.$fields_value[37].'" size="30"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><p><small>Do you expect these visits to be sufficient to meet functional goals:</small></p>
			<p><input type="radio" name="diagnosis_sufficient" value="Yes" '.($fields_value[38] == 'Yes' ? 'checked="checked"' : '').'> Yes<br />
			<input type="radio" name="diagnosis_sufficient" value="No" '.($fields_value[38] == 'No' ? 'checked="checked"' : '').'> No<br />
			<small>If No, please provide details of expected further assessment and treatment: </small><br /><input type="text" name="diagnosis_sufficient_no" value="'.$fields_value[39].'" size="30"></p></td>
		<td style="width:50%; border:1px solid black;"><p><small>Do you expect to reassess within three weeks due to alerting factors?</small></p>
			<p><input type="radio" name="diagnosis_reassess" value="Yes" '.($fields_value[40] == 'Yes' ? 'checked="checked"' : '').'> Yes<br />
			<input type="radio" name="diagnosis_reassess" value="No" '.($fields_value[40] == 'No' ? 'checked="checked"' : '').'> No<br />
			<small>If Yes, please describe: </small><input type="text" name="diagnosis_reassess_yes" value="'.$fields_value[41].'" size="25"></p></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 4
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 4 - Treatment (To be completed with reference to the Diagnostic and Treatment Protocols Regulation)</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Treatment Provided</small>
		<p><input type="checkbox" name="treatment_manual" value="Manual Therapy" '.($fields_value[42] == '' ? '' : 'checked="checked"').'> Manual Therapy<br />
		<input type="checkbox" name="treatment_exercise" value="Exercise Prescription" '.($fields_value[43] == '' ? '' : 'checked="checked"').'> Exercise Prescription<br />
		<input type="checkbox" name="treatment_local" value="Local Modalities" '.($fields_value[44] == '' ? '' : 'checked="checked"').'> Local Modalities</p></td>
		<td style="width:50%; border:1px solid black;">&nbsp;
		<p><input type="checkbox" name="treatment_education" value="Education" '.($fields_value[45] == '' ? '' : 'checked="checked"').'> Education<br />
		<input type="checkbox" name="treatment_acupuncture" value="Acupuncture IMS" '.($fields_value[46] == '' ? '' : 'checked="checked"').'> Acupuncture IMS<br />
		<input type="checkbox" name="treatment_massage" value="Massage Therapy" '.($fields_value[47] == '' ? '' : 'checked="checked"').'> Massage Therapy</p></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Do you expect the claimant to return to normal and essential activities?</small>
		<p><input type="radio" name="treatment_return" value="Yes" '.($fields_value[48] == 'Yes' ? 'checked="checked"' : '').'> Yes<br />
		<input type="radio" name="treatment_return" value="No" '.($fields_value[48] == 'No' ? 'checked="checked"' : '').'> No<br />
		<input type="radio" name="treatment_return" value="Unknown" '.($fields_value[48] == 'Unable to determine' ? 'checked="checked"' : '').'> Unable to determine</p>
			If Yes, date expected? <input type="text" name="treatment_return_date" value="'.$fields_value[49].'" size="30"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 5
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 5 - Primary Health Care Practitioner Information</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Name of Primary Health Care Practitioner</small><br /><input type="text" name="health_care_name" value="'.get_contact($dbc, $fields_value[50]).'" size="40"></td>
		<td style="width:50%; border:1px solid black;"><small>Profession</small><br />
			<small><input type="checkbox" name="health_care_doctor" value="Medical Doctor" '.($fields_value[51] == '' ? '' : 'checked="checked"').'> Medical Doctor
			<input type="checkbox" name="health_care_chiro" value="Chiropractor" '.($fields_value[52] == '' ? '' : 'checked="checked"').'> Chiropractor
			<input type="checkbox" name="health_care_therapist" value="Physical Therapist" '.($fields_value[53] == '' ? '' : 'checked="checked"').'> Physical Therapist</small></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Address</small><br /><input type="text" name="health_care_address" value="'.get_contact($dbc, $fields_value[54]).'" size="40"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>City, Town or Country</small><br /><input type="text" name="health_care_city" value="'.$fields_value[55].'" size="30"></td>
		<td style="width:40%; border:1px solid black;"><small>Province</small><br /><input type="text" name="health_care_province" value="'.$fields_value[56].'" size="30"></td>
		<td style="width:20%; border:1px solid black;"><small>Postal Code</small><br /><input type="text" name="health_care_postal" value="'.$fields_value[57].'" size="15"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Telephone Number (Include area code)</small><br /><input type="text" name="health_care_phone" value="'.$fields_value[58].'" size="40"></td>
		<td style="width:50%; border:1px solid black;"><small>Fax Number (Include area code)</small><br /><input type="text" name="health_care_fax" value="'.$fields_value[59].'" size="40"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 6
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 6 - Signature of Primary Health Care Practitioner</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;">Name (Please Print) <input type="text" name="primary_health_name" value="'.get_contact($dbc, $fields_value[60]).'" size="40"><br /><br /><br />
		Signature: <img src="treatment_plan/download/sign1_'.$fieldlevelriskid.'.png" height="30" border="0" alt="" style="border-bottom:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Date: <input type="text" name="primary_health_date" value="'.$fields_value[61].'" size="20"><br />&nbsp;</td></tr></table>';
	$html .= '</td></tr>';
	
	// Spacing
	$html .= '<tr><td colspan="2" style="height:100;"></td></tr>';
	
	// Part 7
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 7 - Choice in Following Diagnostic and Treatment Protocols</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Please state your preference of treatment within or not within the Diagnostic and Treatment Protocols:</small><br />
		<input type="radio" name="choice_choose_treatment" value="I choose to be treated within the Diagnostic and Treatment Protocols as indicated on Form AB-1"> I choose to be treated within the Diagnostic and Treatment Protocols as indicated on Form AB-1<br />
		<input type="radio" name="choice_choose_treatment" value="I choose not to be treated within the Diagnostic and Treatment Protocols"> I choose not to be treated within the Diagnostic and Treatment Protocols</td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><input type="radio" name="choice_claimant" value="I am the claimant"> I am the claimant<br />
		<input type="radio" name="choice_claimant" value="I am an authorized representative of the claimant"> I am the authorized representative of the claimant
		<p><small>I certify that the information provided is true and correct to the best of my knowledge. I confirm that I have consented to the collection, use and disclosure of my personal information for my treatment and care and determination of my eligibility for accident and/or disability income benefits as outline on Form AB-1.</small></p>
		Name (Please Print) <input type="text" name="choice_name" value="'.get_contact($dbc, $fields_value[62]).'" size="40"><br /><br /><br />
		Signature: <img src="treatment_plan/download/sign2_'.$fieldlevelriskid.'.png" height="30" border="0" alt="" style="border-bottom:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Date: <input type="text" name="choice_date" value="'.$fields_value[63].'" size="20"><br />&nbsp;</td></tr></table>';
	$html .= '</td></tr>';
	
	// End of Form
	$html .= '</table></form>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('treatment_plan/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');
}
?>