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
	$pdf->writeHTML("<h1><center>Alberta Accident Benefits</center></h1>
		<h2><center>Initial Claims Process</center></h2>
		<h3>Overview</h3>
		<p>If you have been injured in an automobile accident in Alberta, you are entitled to accident benefits coverage regardless of whether you were at fault for the accident. The benefits you receive depend on the type of injury you have:</p>
		<ul><li>If your injury is a sprain, strain or a whiplash associated with disorder I or II, your primary health care practitioner (chiropractor, medical doctor or physical therapist) does not have to seek approval of the insurer for payment for treatment of these injuries if you provide notice of your claim. Your primary health care practitioner will be able to bill the automobile insurer for all treatment services outlined in the “Diagnostic and Treatment Protocols” that are not covered by Alberta Health Care Insurance. These protocols have been developed in consultation with primary health care practitioners and are based on the best research and evidence currently available.</li>
		<li>For all other injuries, if you choose not to follow the diagnostic and treatment protocols, you will need to pay the health service provider for any services not covered by Alberta Health Care Insurance. You will be reimbursed for eligible expenses from your extended health care benefits (e.g., Blue Cross or similar employee benefits plan) and then by your automobile insurer.</li></ul>
		<h3>What to do if you are injured in an Automobile Accident:</h3>
		<ol><li><b>See a Primary Health Care Practitioner</b> (chiropractor, medical doctor, physical therapist) as soon as possible for an assessment of your injury and, if needed, treatment advice.</li>
		<li><b>File an injury accident report with the police.</b></li>
		<li><b>Complete the attached Notice of Loss and Proof of Claim Form (AB-1)</b>, retain a copy for your records and send the original signed form(s) to the insurance company. If you are unable to send the form within the following timeframes, submit it to your insurance company as soon as practicable and explain the reason for the delay.
		<ul><li>If your injury is diagnosed as a sprain, strain or whiplash associated disorder I or II, submit this form within 10 days of the accident so that you can access accident benefits described as the “Diagnostic and Treatment Protocols.”</li>
		<li>If you have other types of injuries, or you choose not to access the accident benefits described as the “Diagnostic and Treatment Protocols”, submit the form within 30 days of the accident.</li>
		<li>If a family member is fatally injured in the collision, you can access funeral, grief counselling and death benefits. This form should be submitted within 30 days of the accident.</li></ul></li>
		<li>You will be contacted about the benefits you are entitled to receive after the insurance company reviews your completed form.</li></ol>
		<p><b>If you have further questions about this form, the process, or your benefits, please contact your claims adjuster. If you do not know who your claims adjuster is, contact your insurer or the Insurance Bureau of Canada at 1-800-377-6378.</b></p>", true, false, true, false, '');

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
	$pdf->writeHTML("<h3><center>Important Notice Concerning Your Personal Information</center></h3>
		<p>The personal information you provide in forms AB-1, AB-1a (Claim for Disability Benefits) or AB-2 (Treatment Plan) is collected under the authority of the Insurance Act, Alberta’s Automobile Insurance Accident Benefits Regulation, Diagnostic and Treatment Protocols Regulation and all applicable privacy legislation.</p>
		<ul><li>Your primary health care practitioner or dentist will need to collect personal information from you and from other health service providers and will need to use and disclose your personal information to provide you with appropriate diagnosis, treatment and care.</li>
		<li>Your insurance company and its agents will need to collect, use and disclose personal information from you, your primary health care practitioner, and other health service providers concerning the accident, your injuries, any pre-existing conditions that may impede your recovery progress, the amount of treatment and care provided to you, and any assessments of your injuries and indications as to your treatment progress in order to facilitate contact with you, to determine your eligibility for accident and/or disability income benefits, and to administer your claim.</li></ul>
		<p>Under applicable privacy legislation, it is necessary to obtain your consent to authorize the sharing of your personal information as specified above. The legislation also regulates how primary health care practitioners, dentists, other health service providers, and insurance companies can use and disclose your information once they have it. Section 2 of form AB-1 will ask for your consent or that of your agent. Refusal to provide your authorization and consent could result in an inability to provide you with the treatment and care you require (if not covered by Alberta Health Care Insurance) and may result in an inability for your insurance company to process your claim, in whole or in part.</p>
		<p>Your primary health care practitioner, dentist or other health service provider and insurance company will retain and rely on a copy of your consent for the period of time that your treatment and care is ongoing and your claim is active. You may revoke your consent at any time in writing to your primary health care practitioner or dentist and your insurer or any other person to whom you give consent, subject to continuing legal obligations. If you have any questions concerning the collection, use or disclosure of your personal information, please ask your primary health care practitioner, dentist, or your insurance claims representative or adjuster.</p>", true, false, true, false, '');

	$html = '<form action="" method="POST" enctype="multipart/form-data">';

	$html .= '<table style="border:none; width:100%; border-collapse:separate; border-spacing:10px;">';
	$html .= '<tr><td colspan="2" style="text-align: right;"><big><b>Notice of Loss and Proof of Claim</b></big><br />
		<b>Form AB-1</b><br />
		<small>This form is effective on <b>November 20, 2004</b> for accidents that occur on or after <b>October 1, 2004</b></small></td></tr>';
	$html .= '<tr><td style="border-bottom:1px solid black;vertical-align: top; width:100%"></td></tr>';
	$html .= '<tr><td style=""></td></tr>';
	$html .= '<tr><td style="margin-right: 1em;border:1px solid black;vertical-align: top; width:33%">';
	$html .= '<h4 style="margin: 0; text-align: center;">Send this form to the<br>appropriate insurer:</h4>
		<p><b>Fax #:</b> <input type="text" value="'.$fields_value[1].'" name="ins_fax_number" style="border:none;" size="20"></p>';
	$html .= '</td><td style="width:67%;">';
	$html .= '<table callpadding="3" style="border:1px solid black; width:100%; margin:0;"><tbody>';
	$html .= '<tr><td colspan="2"><h4 style="margin:0;height:30px;text-align:center;">To be completed by Insurer</h4></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Claim Number</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[2].'" name="ins_claim_number" style="border:none;" size="32"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Insurance Company</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.get_client($dbc, $fields_value[3]).'" name="ins_company" style="border:none;" size="32"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Claim Representative</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[4].'" name="ins_claim_rep" style="border:none;" size="32"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Policy Number</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[5].'" name="ins_policy_number" style="border:none;" size="32"></td></tr>';
	$html .= '<tr><td style="font-weight:bold; width:40%; border-right:1px solid black; border-top:1px solid black;">Date of Accident<br>(DD-MM-YYYY)</td><td style="border-top:1px solid black; width:60%;"><input type="text" value="'.$fields_value[6].'" name="ins_accident_date" style="border:none;" size="32"></td></tr>';
	$html .= '</tbody></table></td></tr>';
	
	// Part 1
	$claimant = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='".$fields_value[7]."'"));
	$html .= '<tr><td colspan="2"><h4>Section 1: Claimant Information</h4><br />
		<span style="padding:5px;background-color:#000;color:#fff;">Part 1 - Claimant Information</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:30%; border:1px solid black;"><small>Last Name</small><br /><input type="text" name="claim_last" value="'.decryptIt($claimant['last_name']).'" size="25"></td>
		<td style="width:40%; border:1px solid black;"><small>First Name</small><br /><input type="text" name="claim_first" value="'.decryptIt($claimant['first_name']).'" size="30"></td>
		<td style="width:30%; border:1px solid black;"><small>Middle Name(s)</small><br /><input type="text" name="claim_middle" value="" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Address</small><br /><input type="text" name="claim_address" value="'.$claimant['mailing_address'].'" size="80"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>City, Town or Country</small><br /><input type="text" name="claim_city" value="'.$claimant['city'].'" size="30"></td>
		<td style="width:40%; border:1px solid black;"><small>Province</small><br /><input type="text" name="claim_province" value="'.$claimant['province'].'" size="30"></td>
		<td style="width:20%; border:1px solid black;"><small>Postal Code</small><br /><input type="text" name="claim_postal" value="'.$claimant['postal_code'].'" size="15"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:35%; border:1px solid black;"><small>Telephone Number (Home) (Include area code)</small><br /><input type="text" name="claim_phone_1" value="'.decryptIt($claimant['home_phone']).'" size="28"></td>
		<td style="width:35%; border:1px solid black;"><small>Telephone Number (Work) (Include area code)</small><br /><input type="text" name="claim_phone_2" value="'.decryptIt($claimant['office_phone']).'" size="28"></td>
		<td style="width:30%; border:1px solid black;"><small>Fax Number (Include area code)</small><br /><input type="text" name="claim_fax" value="'.$claimant['fax'].'" size="24"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:35%; border:1px solid black;"><small>Date Of Birth</small><br /><input type="text" name="claim_birth_date" value="'.$claimant['birth_date'].'" size="28"></td>
		<td style="width:35%; border:1px solid black;"><small>Gender<br /><input type="radio" name="claim_gender" '.($claimant['gender'] == 'Male' ? 'checked="checked"' : '').' value="male"> Male <input type="radio" name="claim_gender" '.($claimant['gender'] == 'Female' ? 'checked="checked"' : '').' value="female"> Female</small></td>
		<td style="width:30%; border:1px solid black;"><small>You can best be reached:<br /><input type="radio" name="claim_contact_method" '.($fields_value[8] == 'Telephone' ? 'checked="checked"' : '').' value="telephone"> By telephone <input type="radio" name="claim_contact_method" '.($fields_value[8] == 'Home' ? 'checked="checked"' : '').' value="home"> At home<br />
			<input type="radio" name="claim_contact_method" '.($fields_value[8] == 'Personal' ? 'checked="checked"' : '').' value="visit"> By personal visit <input type="radio" name="claim_contact_method" '.($fields_value[8] == 'Work' ? 'checked="checked"' : '').' value="work"> At work 
			<input type="radio" name="claim_contact_method" '.($fields_value[8] == 'Other' ? 'checked="checked"' : '').' value="other"> Other: <input type="text" name="claim_contact_method_other" value="'.$fields_value[9].'" size="8"></small></td></tr></table>';
	$html .= '<table style="width:100%; border:1px solid black;"><tr><td style="width:45%;"><small>When is the best time to reach you?</small><br /><input type="text" name="claim_time_contact" value="'.$fields_value[10].'" size="40"></td>
		<td style="width:55%;"><small>Day(s) of the week:</small><br /><input type="text" name="claim_days_contact" value="'.$fields_value[11].'" size="50"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:65%; border:1px solid black;"><small>Insurance Company</small><br /><input type="text" name="claim_insurance_company" value="'.get_client($dbc, $fields_value[12]).'" size="55"></td>
		<td style="width:35%; border:1px solid black;"><small>Policy Number</small><br /><input type="text" name="claim_policy_number" value="'.$fields_value[13].'" size="30"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:45%; border:1px solid black;"><small>Will this be an Alberta Workers’ Compensation Board Claim?<br />
			<input type="radio" name="claim_wcb" '.($fields_value[14] == 'Yes' ? 'checked="checked"' : '').' value="yes"> Yes<br /><input type="radio" name="claim_wcb" '.($fields_value[14] == 'No' ? 'checked="checked"' : '').' value="no"> No</small></td>
		<td style="width:55%; border:1px solid black;"><small>Are Extended Health Care Benefits Available? (e.g., Blue Cross or similar Employee<br />benefits plans)<br />
			<input type="radio" name="claim_wcb" '.($fields_value[15] == 'Yes' ? 'checked="checked"' : '').' value="yes"> Yes<br /><input type="radio" name="claim_wcb" '.($fields_value[15] == 'No' ? 'checked="checked"' : '').' value="no"> No<br />
			Details: <input type="text" name="claim_wcb_details" value="'.$fields_value[16].'" size="35"></small></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:65%; border:1px solid black;"><small>Are you currently employed or engaged in training activities?<br />
			<input type="radio" name="claim_employed" '.($fields_value[17] == 'Full' ? 'checked="checked"' : '').' value="Full"> Full Time
			<input type="radio" name="claim_employed" '.($fields_value[17] == 'Part' ? 'checked="checked"' : '').' value="Part"> Part Time
			<input type="radio" name="claim_employed" '.($fields_value[17] == 'Self' ? 'checked="checked"' : '').' value="Self"> Self-employed<br />
			<input type="radio" name="claim_employed" '.($fields_value[17] == 'Retired' ? 'checked="checked"' : '').' value="Retired"> Retired
			<input type="radio" name="claim_employed" '.($fields_value[17] == 'Student' ? 'checked="checked"' : '').' value="Student"> Student
			<input type="radio" name="claim_employed" '.($fields_value[17] == 'Unemployed' ? 'checked="checked"' : '').' value="Unemployed"> Not employed</small></td>
		<td style="width:35%; border:1px solid black;"><center><b>If you are making a claim<br />for disability benefits, please also<br />complete Form AB- 1a.</b></center></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 2
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 2 - Claimant\'s Authorized Representative</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:30%; border:1px solid black;"><small>Last Name</small><br /><input type="text" name="rep_last" value="'.$fields_value[18].'" size="25"></td>
		<td style="width:40%; border:1px solid black;"><small>First Name</small><br /><input type="text" name="rep_first" value="'.$fields_value[19].'" size="30"></td>
		<td style="width:30%; border:1px solid black;"><small>Middle Name(s)</small><br /><input type="text" name="rep_middle" value="'.$fields_value[20].'" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Address</small><br /><input type="text" name="rep_address" value="'.$fields_value[21].'" size="80"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>City, Town or Country</small><br /><input type="text" name="rep_city" value="'.$fields_value[22].'" size="30"></td>
		<td style="width:40%; border:1px solid black;"><small>Province</small><br /><input type="text" name="rep_province" value="'.$fields_value[23].'" size="30"></td>
		<td style="width:20%; border:1px solid black;"><small>Postal Code</small><br /><input type="text" name="rep_postal" value="'.$fields_value[24].'" size="15"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>Relationship with Claimant<br />
			<input type="radio" name="rep_relationship" '.($fields_value[25] == 'Parent' ? 'checked="checked"' : '').' value="Parent"> Parent<br />
			<input type="radio" name="rep_relationship" '.($fields_value[25] == 'Guardian' ? 'checked="checked"' : '').' value="Guardian"> Guardian<br />
			<input type="radio" name="rep_relationship" '.($fields_value[25] == 'Other' ? 'checked="checked"' : '').' value="Other"> Other: <input type="text" name="rep_relationship_other" value="'.$fields_value[55].'" size="40"></small></td>
		<td style="width:60%; border:1px solid black;"><small>Relevant Documentation Attached? If no, please authorize your representative by<br />completing Part 5 of this form.<br />
			<input type="radio" name="rep_documents" '.($fields_value[26] == 'Yes' ? 'checked="checked"' : '').' value="Yes"> Yes<br />
			<input type="radio" name="rep_documents" '.($fields_value[26] == 'No' ? 'checked="checked"' : '').' value="No"> No<br />
			<input type="radio" name="rep_documents" '.($fields_value[26] == 'NA' ? 'checked="checked"' : '').' value="NA"> Not Applicable</small></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:35%; border:1px solid black;"><small>Telephone Number (Home) (Include area code)</small><br /><input type="text" name="rep_phone_1" value="'.$fields_value[27].'" size="28"></td>
		<td style="width:35%; border:1px solid black;"><small>Telephone Number (Work) (Include area code)</small><br /><input type="text" name="rep_phone_2" value="'.$fields_value[28].'" size="28"></td>
		<td style="width:30%; border:1px solid black;"><small>Fax Number (Include area code)</small><br /><input type="text" name="rep_fax" value="'.$fields_value[29].'" size="24"></td></tr></table>';
	$html .= '</td></tr></table>';
	
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);
	$pdf->writeHTML($html, true, false, true, false, '');
	$html = '<table style="border:none; width:100%; border-collapse:separate; border-spacing:10px;">';
	
	// Part 3
	$html .= '<tr><td colspan="2"><span style="padding:5px;background-color:#000;color:#fff;">Part 3 – Claimant’s Accident Details (If more space is required please continue on back side of this page)</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>You were a:</small><br />
			<input type="radio" name="accident_involved" '.($fields_value[30] == 'Driver' ? 'checked="checked"' : '').' value="Driver"> Driver
			<input type="radio" name="accident_involved" '.($fields_value[30] == 'Passenger' ? 'checked="checked"' : '').' value="Passenger"> Passenger
			<input type="radio" name="accident_involved" '.($fields_value[30] == 'Pedestrian' ? 'checked="checked"' : '').' value="Pedestrian"> Pedestrian
			<input type="radio" name="accident_involved" '.($fields_value[30] == 'Other' ? 'checked="checked"' : '').' value="Other"> Other: <input type="text" name="accident_involved_other" value="'.$fields_value[31].'" size="50"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Location of Accident</small><br /><input type="text" name="accident_location" value="'.$fields_value[32].'" size="45"></td>
		<td style="width:30%; border:1px solid black;"><small>City, Town or County</small><br /><input type="text" name="accident_city" value="'.$fields_value[33].'" size="25"></td>
		<td style="width:20%; border:1px solid black;"><small>Province</small><br /><input type="text" name="accident_province" value="'.$fields_value[34].'" size="15"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:25%; border:1px solid black;"><small>Time of Accident</small><br /><input type="text" name="accident_time" value="'.$fields_value[35].'" size="25"></td>
		<td style="width:25%; border:1px solid black;"><small>Date of Accident</small><br /><input type="text" name="accident_date" value="'.$fields_value[36].'" size="25"></td>
		<td style="width:25%; border:1px solid black;"><small>Was Accident Reported to the Police?</small><br />
			<input type="radio" name="accident_reported" '.($fields_value[37] == 'Yes' ? 'checked="checked"' : '').' value="Yes"> Yes <input type="radio" name="accident_reported" '.($fields_value[37] == 'No' ? 'checked="checked"' : '').' value="No"> No </td>
		<td style="width:25%; border:1px solid black;"><small>Date Reported</small><br /><input type="text" name="accident_report_date" value="'.$fields_value[38].'" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Please provide a brief description of how the accident occurred and how you were injured:</small><br />
		<textarea name="accident_description" cols="90" rows="5">'.$fields_value[39].'</textarea><br /><br /><br /><br /></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>You were a:</small><br />
			<input type="radio" name="accident_visit" '.($fields_value[40] == 'Yes' ? 'checked="checked"' : '').' value="Yes"> Yes
			<input type="radio" name="accident_visit" '.($fields_value[40] == 'No' ? 'checked="checked"' : '').' value="No"> No
			<input type="radio" name="accident_visit" '.($fields_value[40] == 'Booked' ? 'checked="checked"' : '').' value="Booked"> Appointment booked for: <input type="text" name="accident_visit_date" value="'.$fields_value[41].'" size="50"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>You were a:</small><br />
			<input type="radio" name="accident_treatment" '.($fields_value[42] == 'Yes' ? 'checked="checked"' : '').' value="Yes"> Yes
			<input type="radio" name="accident_treatment" '.($fields_value[42] == 'No' ? 'checked="checked"' : '').' value="No"> No
			<input type="radio" name="accident_treatment" '.($fields_value[42] == 'Booked' ? 'checked="checked"' : '').' value="Booked"> Appointment booked for: <input type="text" name="accident_treatment_date" value="'.$fields_value[43].'" size="50"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>You were a:</small><br />
			<input type="radio" name="accident_previous" '.($fields_value[44] == 'Yes' ? 'checked="checked"' : '').' value="Yes"> Yes
			<input type="radio" name="accident_previous" '.($fields_value[44] == 'No' ? 'checked="checked"' : '').' value="No"> No</td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Please provide a brief description of your injuries and the symptoms that you are currently experiencing:</small><br />
		<textarea name="accident_injuries" cols="90" rows="5">'.$fields_value[45].'</textarea><br /><br /><br /><br /></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 4
	$provider = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='".$fields_value[46]."'"));
	$html .= '<tr><td colspan="2"><span style="display:block;padding:5px;background-color:#000;color:#fff;">Part 4 – Information of Health Provider Providing Ongoing Treatment and Care</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:70%; border:1px solid black;"><small>Name of Primary Health Care Practitioner or Dentist</small><br /><input type="text" name="provider_name" value="'.decryptIt($provider['first_name']).' '.decryptIt($provider['last_name']).'" size="30"></td>
		<td style="width:30%; border:1px solid black;"><small>Profession</small><br /><input type="text" name="provider_profession" value="'.$provider['position'].'" size="25"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:1px solid black;"><small>Address</small><br /><input type="text" name="provider_address" value="'.$provider['address'].'" size="80"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:40%; border:1px solid black;"><small>City, Town or Country</small><br /><input type="text" name="provider_city" value="'.$provider['city'].'" size="30"></td>
		<td style="width:40%; border:1px solid black;"><small>Province</small><br /><input type="text" name="provider_province" value="'.$provider['province'].'" size="30"></td>
		<td style="width:20%; border:1px solid black;"><small>Postal Code</small><br /><input type="text" name="provider_postal" value="'.$provider['postal_code'].'" size="15"></td></tr></table>';
	$html .= '<table style="width:100%;"><tr><td style="width:50%; border:1px solid black;"><small>Telephone Number (Include area code)</small><br /><input type="text" name="provider_phone" value="'.decryptIt($provider['office_phone']).'" size="28"></td>
		<td style="width:50%; border:1px solid black;"><small>Fax Number (Include area code)</small><br /><input type="text" name="provider_fax" value="'.$provider['fax'].'" size="24"></td></tr></table>';
	$html .= '</td></tr>';
	
	// Part 5
	$html .= '<tr><td colspan="2"><span style="display:block;padding:5px;background-color:#000;color:#fff;">Part 5 – Authority to Act on Claimants Behalf<br />(this section should be completed only when the claimant chooses not to act on his/her own behalf)</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:none;"><p>I, <input type="text" name="auth_claimant" value="'.get_contact($dbc, $fields_value[47]).'" size="30">, hereby authorize <input type="text" name="auth_rep" value="'.$fields_value[48].'" size="30"> to act as my representative concerning the treatment and care of my injury, the submission and ongoing handling of my claim for accident and/or disability income benefits and the collection, use and disclosure of information concerning my injury, diagnosis, assessment, treatment or care resulting from the automobile accident referred to in Section 1 of this form.</p>
		<p>I authorize my primary health care practitioner(s), dentist(s), other health service provider(s) and my insurance company, <input type="text" name="auth_insurance" value="'.get_client($dbc, $fields_value[49]).'" size="30"> and their agents, to collect relevant information concerning me and my accident from my representative as required. I further authorize primary health care practitioner(s), dentist(s), other health service provider(s) and my insurance company to disclose relevant information concerning my injury, diagnosis, assessment, treatment and care and my claim for accident and/or disability income benefits to my representative.</p>
		<p><br /><br />Signature of Claimant: <img src="wcb_ab_forms/download/ab1_claimant_'.$fieldlevelriskid.'.png" height="30" border="0" alt="" style="border-bottom:1px solid black;"><br />
			Date: <input type="text" name="auth_claim_sign_date" value="'.$fields_value[50].'" size="20"></p>
		<p><br /><br />Signature of Representative: <img src="wcb_ab_forms/download/ab1_rep_'.$fieldlevelriskid.'.png" height="30" border="0" alt="" style="border-bottom:1px solid black;"><br />
			Date: <input type="text" name="auth_rep_sign_date" value="'.$fields_value[51].'" size="20"></p></td></tr></table>';
	$html .= '</td></tr></table>';
	
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);
	$pdf->writeHTML($html, true, false, true, false, '');
	$html = '<table style="border:none; width:100%; border-collapse:separate; border-spacing:10px;">';
	
	// Part 6
	$html .= '<tr><td colspan="2"><span style="display:block;padding:5px;background-color:#000;color:#fff;">Part 6 – Certification and Consent to Share Information<br />(to be completed by claimant or their authorized representative)</span><br />';
	$html .= '<table style="width:100%;"><tr><td style="width:100%; border:none;"><p>I certify that the information provided is true and correct to the best of my knowledge.</p>
		<p>I authorize all assessing or treating Primary Health Care Practitioners, dentist(s) or other health service provider(s) to collect, use and disclose any relevant information concerning my injury, including diagnosis, assessment, treatment or care resulting from the automobile accident referred to in Section 1 herein, for the purpose of providing ongoing treatment and care.</p>
		<p>I further authorize all assessing or treating Primary Health Care Practitioners, dentist(s) or other health service providers to disclose my personal information to my insurance company, <input type="text" name="final_insurance" value="'.get_client($dbc, $fields_value[52]).'" size="30"> and their agents that is relevant for the purpose of determining my eligibility for accident and disability benefits as outlined on Form AB-1 and for the purpose of administering my claim.</p>
		<p>I further authorize my insurance company and its agents to collect, use and disclose relevant information concerning my injury, diagnosis, assessment, treatment or care received as a result of the automobile accident referred to in Section 1 herein, including a treatment plan and services provided, for the purpose of determining my eligibility for accident and disability benefits as outlined on Form AB-1 and administering my claim.</p>
		<p><input type="radio" name="consent" '.($fields_value[54] == 'claimant' ? 'checked="checked"' : '').' value="claimant"> I am the claimant or <input type="radio" name="consent" '.($fields_value[54] == 'representative' ? 'checked="checked"' : '').' value="representative"> I am the authorized representative of the claimant</p>
		<p><br /><br />Signature: <img src="wcb_ab_forms/download/ab1_final_'.$fieldlevelriskid.'.png" height="30" border="0" alt="" style="border-bottom:1px solid black;"> Date: <input type="text" name="final_sign_date" value="'.$fields_value[53].'" size="20"></p></td></tr></table>';
	$html .= '</td></tr>';
	
	// End of Form
	$html .= '</table></form>';
	
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);
	$pdf->writeHTML($html, true, false, true, false, '');

	$pdf->Output('wcb_ab_forms/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');
}
?>