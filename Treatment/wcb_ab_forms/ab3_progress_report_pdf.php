<?php
function treatment_plan_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

    //$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_patientform WHERE form='Treatment Plan'"));
    //DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
    DEFINE('PDF_LOGO', '');

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
    $pdf->SetFont('times', '', 10);

	$html = '<form action="" method="POST" enctype="multipart/form-data">';

	$html .= '<table style="border:none; width:100%; border-collapse:separate; border-spacing: 0 10px;">';
	$html .= '<tr><td colspan="2" style="margin-right: 10px;border:1px solid black;vertical-align: top; width:50%">';
	$html .= '<h4 style="margin: 0; text-align: center;">Send this form to the<br>appropriate insurer:</h4>
		<center><b>Fax #:</b> <input type="text" value="'.$fields_value[1].'" name="insurance_fax" style="border:none;" size="20"><br />
		<input type="text" value="'.get_contact($dbc, $fields_value[2], 'name').'" name="insurance_company" style="border:none;" size="40"></center>';
	$html .= '</td><td style="width:50%;">';
	$html .= '<table style="border:1px solid black; width:100%; margin:0;">';
	$html .= '<tr><td colspan="2"><h4>Progress Report<br />(Form AB-3)</h4><p style="text-align: right;"><small>Use this form for accidents that occur on or after October 1, 2004</small></p></td></tr>';
	$html .= '<tr><td colspan="2" style="border-top:1px solid black;"><p style="margin:0;height:30px;text-align:center;"><b><small>This part to be completed by the claimant or their representative or a Primary Health Care Practitioner</small></b></p></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:35%; border-right:1px solid black; border-top:1px solid black;"><small>Insurance Company</small></td>
		<td style="border-top:1px solid black; width:65%;"><input type="text" value="'.get_contact($dbc, $fields_value[2], 'name').'" name="insurance_company" style="border:none;" size="25"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:35%; border-right:1px solid black; border-top:1px solid black;"><small>Policy Number</small></td>
		<td style="border-top:1px solid black; width:65%;"><input type="text" value="'.$fields_value[3].'" name="insurance_policy" style="border:none;" size="25"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:35%; border-right:1px solid black; border-top:1px solid black;"><small>Date of Accident<br>(DD-MM-YYYY)</small></td>
		<td style="border-top:1px solid black; width:65%;"><input type="text" value="'.$fields_value[4].'" name="insurance_accident" style="border:none;" size="25"></td></tr>';
	$html .= '</table></td></tr>';
	
	// Part 1
	$html .= '<tr><td style="width: 15%; border-top: 3px solid black;">Part 1<br /><b>Claimant<br />Information</b></td><td colspan="2" style="width:85%;">';
	$html .= '<table style="width:100%;"><tr><td style="width:35%; border:1px solid black;"><small>Last Name</small><br /><input type="text" name="client_last" value="'.get_contact($dbc, $fields_value[5], 'last_name').'" size="25"></td>
		<td style="width:35%; border:1px solid black;"><small>First Name</small><br /><input type="text" name="client_first" value="'.get_contact($dbc, $fields_value[5], 'first_name').'" size="25"></td>
		<td style="width:30%; border:1px solid black;"><small>Date of Birth (DD/MM/YYYY)</small><br /><input type="text" name="client_birth" value="'.get_contact($dbc, $fields_value[5], 'birth_date').'" size="20"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:35%; border:1px solid black;"><small>Date of Initial Assessment (DD/MM/YYYY)</small><br /><input type="text" name="client_accident" value="'.$fields_value[6].'" size="25"></td>
		<td style="width:65%;"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 2
	$html .= '<tr><td style="width: 15%; border-top: 3px solid black;">Part 2<br /><b>Information of<br />Primary Health<br />Care<br />Practitioner</b></td><td colspan="2" style="width:85%;">';
	$html .= '<table style="width:100%;"><tr><td style="width:70%; border:1px solid black;"><small>Name of Professional</small><br /><input type="text" name="health_care_name" value="'.get_contact($dbc, $fields_value[7]).'" size="50"></td>
		<td style="width:30%; border:1px solid black;"><small>Profession</small><br /><input type="text" name="health_care_profession" value="'.get_contact($dbc, $fields_value[7], 'position').'" size="20"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Address</small><br /><input type="text" name="health_care_address" value="'.$fields_value[8].'" size="75"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:60%; border:1px solid black;"><small>City, Town or Country</small><br /><input type="text" name="health_care_city" value="'.$fields_value[9].'" size="40"></td>
		<td style="width:25%; border:1px solid black;"><small>Province</small><br /><input type="text" name="health_care_province" value="'.$fields_value[10].'" size="15"></td>
		<td style="width:15%; border:1px solid black;"><small>Postal Code</small><br /><input type="text" name="health_care_postal" value="'.$fields_value[11].'" size="10"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:60%; border:1px solid black;"><small>Administrative Contact Name</small><br /><input type="text" name="health_care_admin" value="'.$fields_value[12].'" size="40"></td>
		<td style="width:40%; border:1px solid black;"><small>Facility Name</small><br /><input type="text" name="health_care_facility" value="'.$fields_value[13].'" size="30"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:60%; border:1px solid black;"><small>Telephone Number (Include area code)</small><br /><input type="text" name="health_care_phone" value="'.$fields_value[14].'" size="40"></td>
		<td style="width:40%; border:1px solid black;"><small>Fax Number (Include area code)</small><br /><input type="text" name="health_care_fax" value="'.$fields_value[15].'" size="30"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 3
	$html .= '<tr><td style="width: 15%; border-top: 3px solid black;">Part 3<br /><b>Therapy Status<br />Report</b></td><td colspan="2" style="width:85%;">';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;">';
	$html .= '<p>Diagnosis:<input type="text" name="diagnosis" value="'.html_entity_decode($fields_value[16]).'" size="65"></p>';
	$html .= '<p>Key Subjective and Physical Examination Findings:<br /><textarea name="diagnosis_findings" cols="75" rows="6">'.html_entity_decode($fields_value[17])."</textarea><br /><br /><br /><br /><br /></p>";
	$html .= '</td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;">';
	$html .= '<p><small>Functional Goals:</small><br />1. <textarea name="functional_goal_1" cols="35" rows="3">'.html_entity_decode($fields_value[18]).'</textarea><br /><br />';
	$html .= '<br />2. <textarea name="functional_goal_2" cols="35" rows="3">'.html_entity_decode($fields_value[19]).'</textarea><br /><br />';
	$html .= '<br />3. <textarea name="functional_goal_3" cols="35" rows="3">'.html_entity_decode($fields_value[20]).'</textarea><br /><br /></p></td><td style="width:50%; border:1px solid black;">';
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
	$html .= '<tr><td style="width: 15%; border-top: 3px solid black;">Part 4<br /><b>Signature of<br />Primary Health<br />Care<br />Practitioner</b></td><td colspan="2" style="width:85%;">';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;">Name (Please Print) <input type="text" name="primary_health_name" value="'.get_contact($dbc, $fields_value[23]).'" size="40"><br /><br /><br />
		Signature: <img src="wcb_ab_forms/download/ab3_sign_'.$fieldlevelriskid.'.png" height="30" border="0" alt="" style="border-bottom:1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Date: <input type="text" name="primary_health_date" value="'.$fields_value[24].'" size="20"><br />&nbsp;</td></tr></table>';
	$html .= '</td></tr>';
	
	// End of Form
	$html .= '</table></form>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('wcb_ab_forms/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');
}
?>