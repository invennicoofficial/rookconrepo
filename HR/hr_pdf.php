<?php $hrid = filter_var($_GET['hrid_pdf'], FILTER_SANITIZE_STRING);
$get_hr = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT `hr`.*, IFNULL(NULLIF(`hr`.`pdf_logo`,''),`defaults`.`pdf_logo`) `pdf_logo`, IFNULL(NULLIF(`hr`.`pdf_header`,''),`defaults`.`pdf_header`) `pdf_header`, IFNULL(NULLIF(`hr`.`pdf_footer`,''),`defaults`.`pdf_footer`) `pdf_footer`, IFNULL(NULLIF(`hr`.`email_subject`,''),`defaults`.`email_subject`) `email_subject`, IFNULL(NULLIF(`hr`.`email_message`,''),`defaults`.`email_message`) `email_message`, IFNULL(NULLIF(`hr`.`completed_recipient`,''),`defaults`.`completed_recipient`) `completed_recipient`, IFNULL(NULLIF(`hr`.`completed_subject`,''),`defaults`.`completed_subject`) `completed_subject`, IFNULL(NULLIF(`hr`.`completed_message`,''),`defaults`.`completed_message`) `completed_message`, IFNULL(NULLIF(`hr`.`approval_subject`,''),`defaults`.`approval_subject`) `approval_subject`, IFNULL(NULLIF(`hr`.`approval_message`,''),`defaults`.`approval_message`) `approval_message`, IFNULL(NULLIF(`hr`.`rejected_subject`,''),`defaults`.`rejected_subject`) `rejected_subject`, IFNULL(NULLIF(`hr`.`rejected_message`,''),`defaults`.`rejected_message`) `rejected_message`
	FROM `hr` LEFT JOIN `hr` `defaults` ON `defaults`.`form`='default_hr_form_settings' WHERE `hr`.`hrid`='$hrid'"));

$heading_number = $get_hr['heading_number'];
$sub_heading_number = $get_hr['sub_heading_number'];
$category = $get_hr['category'];
$heading = $get_hr['heading'];
$sub_heading = $get_hr['sub_heading'];
$description = $get_hr['hr_description'];
$third_heading_number = $get_hr['third_heading_number'];
$third_heading = $get_hr['third_heading'];
$manual_type = $get_hr['category'];

define('HEADER_LOGO', html_entity_decode($get_hr['pdf_logo']));
define('HEADER_TEXT', html_entity_decode($get_hr['pdf_header']));
define('FOOTER_TEXT', html_entity_decode($get_hr['pdf_footer']));

$pdf_html = '<form action="" method="POST" enctype="multipart/form-data"><h1>'.$manual_type.'</h1>';
$pdf_name = '';
if ($third_heading != '') {
	$pdf_html .= "<h3>$third_heading_number - $third_heading</h3>";
	$pdf_name = config_safe_str($third_heading_number.'-'.$third_heading);
} else if ($sub_heading != '') {
	$pdf_html .= "<h3>$sub_heading_number - $sub_heading</h3>";
	$pdf_name = config_safe_str($sub_heading_number.'-'.$sub_heading);
} else if ($heading != '') {
	$pdf_html .= "<h3>$heading_number - $heading</h3>";
	$pdf_name = config_safe_str($heading_number.'-'.$heading);
}

$pdf_html .= html_entity_decode($description);	

$links = mysqli_query($dbc, "SELECT upload, uploadid FROM hr_upload WHERE type='document' AND hrid='$hrid' AND `upload` != ''");
if(mysqli_num_rows($links) > 0) {
	$pdf_html .= "<h3>Documents</h3>\n<ul>";
	while($doc_link = mysqli_fetch_array($links)) {
		$pdf_html .= '<li><a href="'.WEBSITE_URL.'/HR/download/'.$doc_link['upload'].'" target="_blank">'.$doc_link['upload'].'</a></li>';
	}
	$pdf_html .= "</ul>";
}
$links = mysqli_query($dbc, "SELECT upload, uploadid FROM hr_upload WHERE type='link' AND hrid='$hrid' AND `upload` != ''");
if(mysqli_num_rows($links) > 0) {
	$pdf_html .= "<h3>Links</h3>\n<ul>";
	while($url_link = mysqli_fetch_array($links)) {
		$pdf_html .= '<li><a href="'.$url_link['upload'].'" target="_blank">'.$url_link['upload'].'</a></li>';
	}
	$pdf_html .= "</ul>";
}
$links = mysqli_query($dbc, "SELECT upload, uploadid FROM hr_upload WHERE type='video' AND hrid='$hrid' AND `upload` != ''");
if(mysqli_num_rows($links) > 0) {
	$pdf_html .= "<h3>Videos</h3>\n<ul>";
	while($video_link = mysqli_fetch_array($links)) {
		$pdf_html .= '<li><a href="'.WEBSITE_URL.'/HR/download/'.$video_link['upload'].'" target="_blank">'.$video_link['upload'].'</a></li>';
	}
	$pdf_html .= "</ul>";
}

// Force the buffer to be appended into $pdf_html from the following include files
ob_start();
$tab = '%';
$form = $get_hr['form'];
$no_sign_pads = true;
if ($get_hr['user_form_id'] > 0) {
	include ('user_forms.php');
} else if ($get_hr['form'] == 'Employee Information Form') {
	include ('employee_information_form/employee_information_form.php');
} else if ($get_hr['form'] == 'Employee Driver Information Form') {
	include ('employee_driver_information_form/employee_driver_information_form.php');
} else if ($get_hr['form'] == 'Time Off Request') {
	include ('time_off_request/time_off_request.php');
} else if ($get_hr['form'] == 'Confidential Information') {
	include ('confidential_information/confidential_information.php');
} else if ($get_hr['form'] == 'Work Hours Policy') {
	include ('work_hours_policy/work_hours_policy.php');
} else if ($get_hr['form'] == 'Direct Deposit Information') {
	include ('direct_deposit_information/direct_deposit_information.php');
} else if ($get_hr['form'] == 'Employee Substance Abuse Policy') {
	include ('employee_substance_abuse_policy/employee_substance_abuse_policy.php');
} else if ($get_hr['form'] == 'Employee Right to Refuse Unsafe Work') {
	include ('employee_right_to_refuse_unsafe_work/employee_right_to_refuse_unsafe_work.php');
} else if ($get_hr['form'] == 'Shop Yard and Office Orientation') {
	include ('employee_shop_yard_office_orientation/employee_shop_yard_office_orientation.php');
} else if ($get_hr['form'] == "Copy of Drivers Licence and Safety Tickets") {
	include ('copy_of_drivers_licence_safety_tickets/copy_of_drivers_licence_safety_tickets.php');
} else if ($get_hr['form'] == 'PPE Requirements') {
	include ('ppe_requirements/ppe_requirements.php');
} else if ($get_hr['form'] == 'Verbal Training in Emergency Response Plan') {
	include ('verbal_training_in_emergency_response_plan/verbal_training_in_emergency_response_plan.php');
} else if ($get_hr['form'] == 'Eligibility for General Holidays and General Holiday Pay') {
	include ('eligibility_for_general_holidays_general_holiday_pay/eligibility_for_general_holidays_general_holiday_pay.php');
} else if ($get_hr['form'] == 'Maternity Leave and Parental Leave') {
	include ('maternity_leave_parental_leave/maternity_leave_parental_leave.php');
} else if ($get_hr['form'] == 'Employment Verification Letter') {
	include ('employment_verification_letter/employment_verification_letter.php');
} else if ($get_hr['form'] == 'Background Check Authorization') {
	include ('background_check_authorization/background_check_authorization.php');
} else if ($get_hr['form'] == 'Disclosure of Outside Clients') {
	include ('disclosure_of_outside_clients/disclosure_of_outside_clients.php');
} else if ($get_hr['form'] == 'Employment Agreement') {
	include ('employment_agreement/employment_agreement.php');
} else if ($get_hr['form'] == 'Independent Contractor Agreement') {
	include ('independent_contractor_agreement/independent_contractor_agreement.php');
} else if ($get_hr['form'] == 'Letter of Offer') {
	include ('letter_of_offer/letter_of_offer.php');
} else if ($get_hr['form'] == 'Employee Non-Disclosure Agreement') {
	include ('employee_nondisclosure_agreement/employee_nondisclosure_agreement.php');
} else if ($get_hr['form'] == 'Employee Self Evaluation') {
	include ('employee_self_evaluation/employee_self_evaluation.php');
} else if ($get_hr['form'] == 'HR Complaint') {
	include ('hr_complaint/hr_complaint.php');
} else if ($get_hr['form'] == 'Exit Interview') {
	include ('exit_interview/exit_interview.php');
} else if ($get_hr['form'] == 'Employee Expense Reimbursement') {
	include ('employee_expense_reimbursement/employee_expense_reimbursement.php');
} else if ($get_hr['form'] == 'Absence Report') {
	include ('absence_report/absence_report.php');
} else if ($get_hr['form'] == 'Employee Accident Report Form') {
	include ('employee_accident_report_form/employee_accident_report_form.php');
} else if ($get_hr['form'] == 'Trucking Information') {
	include ('trucking_information/trucking_information.php');
} else if ($get_hr['form'] == 'Contractor Orientation') {
	include ('contractor_orientation/contractor_orientation.php');
} else if ($get_hr['form'] == 'Contract Welder Inspection Checklist') {
	include ('contract_welder_inspection_checklist/contract_welder_inspection_checklist.php');
} else if ($get_hr['form'] == 'Contractor Pay Agreement') {
	include ('contractor_pay_agreement/contractor_pay_agreement.php');
} else if ($get_hr['form'] == 'Employee Holiday Request Form') {
	include ('employee_holiday_request_form/employee_holiday_request_form.php');
} else if ($get_hr['form'] == 'Employee Coaching Form') {
	include ('employee_coaching_form/employee_coaching_form.php');
} else if ($get_hr['form'] == '2016 Alberta Personal Tax Credits Return') {
	include ('2016_alberta_personal/2016_alberta_personal.php');
} else if ($get_hr['form'] == '2016 Personal Tax Credits Return') {
	include ('tax_credit_1/2016_alberta_personal.php');
} else if ($get_hr['form'] == 'Driver Abstract Statement of Intent') {
	include ('driver_abstract_statement_of_intent/driver_abstract_statement_of_intent.php');
} else if ($get_hr['form'] == 'PERSONAL PROTECTIVE EQUIPMENT POLICY') {
	include ('personal_protective_equipment_policy/personal_protective_equipment_policy.php');
} else if ($get_hr['form'] == 'DRIVER CONSENT FORM') {
	include ('driver_consent_form/driver_consent_form.php');
} else if ($get_hr['form'] == 'Policy and Procedure Notice of Understanding and Intent') {
	include ('policy_and_procedure_notice_of_understanding_and_intent/policy_and_procedure_notice_of_understanding_and_intent.php');
} else if ($get_hr['form'] == 'Employee Personal and Emergency Information') {
	include ('employee_personal_and_emergency_information/employee_personal_and_emergency_information.php');
} else if ($get_hr['form'] == 'Employment Agreement Evergreen') {
	include ('employment_agreement_evergreen/employment_agreement.php');
} else if ($get_hr['form'] == 'Police Information Check') {
	include ('police_information_check/police_information_check.php');
}
$pdf_html .= ob_get_clean();

if (!empty($comment)) {
	$pdf_html .= "<h3>Comments</h3>";
	$pdf_html .= '<table width="100%"><tr><td>'.html_entity_decode($comment).'</td></tr><tr><td>/td></tr></table><div style="clear:both;"></div><br />&nbsp;';
}
$pdf_html .= "<h3>Signed</h3>";
if(!empty($hrid) && file_exists('download/sign_'.$hrid.'.png')) {
	$pdf_html .= '<table width="100%" cellspacing="5"><tr><td width="40%" style="border-bottom: 1px solid black">';
	$pdf_html .= (file_exists('download/sign_'.$hrid.'.png') ? '<img src="download/sign_'.$hrid.'.png" height="30" border="0" alt="">' : '').'</td>';
	$pdf_html .= '<td width="40%" style="border-bottom: 1px solid black"><br /><br />'.get_contact($dbc, $staffid).'</td><td width="20%" style="border-bottom: 1px solid black"><br /><br />'.$today_date.'</td></tr>';
	$pdf_html .= '<tr><td>(Sign Here)</td><td>Print Name</td><td>Today\'s Date</td></tr></table>';
} else {
	$pdf_html .= '<table width="100%" cellspacing="5"><tr><td width="40%" style="border-bottom: 1px solid black"><br /><br /><br /></td>';
	$pdf_html .= '<td width="40%" style="border-bottom: 1px solid black"></td><td width="20%" style="border-bottom: 1px solid black"></td></tr>';
	$pdf_html .= '<tr><td>(Sign Here)</td><td>Print Name</td><td>Today\'s Date</td></tr></table>';
}
$pdf_html .= "</form>";
$pdf_html = str_replace(["'",'class="form-control"','class="datepicker"'],['"','size="40"','size="40"'],$pdf_html);

include_once('../tcpdf/tcpdf.php');
class MYPDF extends TCPDF {
	public function Header() {
		$this->SetFont('helvetica', '', 8);
		$this->writeHTMLCell(0, 0, '', '', HEADER_TEXT, 0, 0, false, "L", "R",true);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		$this->SetFont('helvetica', 'I', 8);
		$footer_text = FOOTER_TEXT.'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
		$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
	}
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
$pdf->setFooterData(array(0,64,0), array(0,64,128));
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8);
$pdf->setY(10);
$pdf->writeHTML(HEADER_TEXT);
$margin_top = $pdf->GetY();
$pdf->DeletePage(1);
$pdf->SetMargins(PDF_MARGIN_LEFT, $margin_top, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf_path = 'download/'.$pdf_name.'_'.date('Y_m_d').'.pdf';
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($pdf_html, true, false, true, false, '');
$pdf->Output($pdf_path, 'F');
track_download($dbc, 'hr', $hrid, WEBSITE_URL.'/HR/'.$pdf_path, 'PDF of HR Form generated by Rook Connect.');
if(!empty($hrid)) { ?>
	<script> window.location.replace('<?= $pdf_path ?>'); </script>
<?php } ?>