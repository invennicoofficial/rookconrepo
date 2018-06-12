<?php
$get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM manuals WHERE manualtypeid='$manualtypeid'"));

$heading_number = $get_contact['heading_number'];
$sub_heading_number = $get_contact['sub_heading_number'];
$category = $get_contact['category'];
$heading = $get_contact['heading'];
$sub_heading = $get_contact['sub_heading'];
$description = $get_contact['description'];
$third_heading_number = $get_contact['third_heading_number'];
$third_heading = $get_contact['third_heading'];
$form_name = $get_contact['form_name'];

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_manuals"));
if($type == 'policy_procedures') {
	$manual_type = 'Policies & Procedures';
	$value_config = ','.$get_field_config['policy_procedures'].',';
	define('HEADER_TEXT', html_entity_decode(get_config($dbc, "manual_policy_header")));
	define('FOOTER_TEXT', html_entity_decode(get_config($dbc, "manual_policy_footer")));
}
if($type == 'operations_manual') {
	$manual_type = 'Operations Manual';
	$value_config = ','.$get_field_config['operations_manual'].',';
	define('HEADER_TEXT', html_entity_decode(get_config($dbc, "manual_operations_header")));
	define('FOOTER_TEXT', html_entity_decode(get_config($dbc, "manual_operations_footer")));
}
if($type == 'emp_handbook') {
	$manual_type = 'Employee Handbook';
	$value_config = ','.$get_field_config['emp_handbook'].',';
	define('HEADER_TEXT', html_entity_decode(get_config($dbc, "manual_handbook_header")));
	define('FOOTER_TEXT', html_entity_decode(get_config($dbc, "manual_handbook_footer")));
}
if($type == 'guide') {
	$manual_type = 'How to Guide';
	$value_config = ','.$get_field_config['guide'].',';
	define('HEADER_TEXT', html_entity_decode(get_config($dbc, "manual_guide_header")));
	define('FOOTER_TEXT', html_entity_decode(get_config($dbc, "manual_guide_footer")));
}
if($type == 'safety') {
	$manual_type = 'Safety';
	$value_config = ','.$get_field_config['safety'].',';
	define('HEADER_TEXT', html_entity_decode(get_config($dbc, "manual_safety_header")));
	define('FOOTER_TEXT', html_entity_decode(get_config($dbc, "manual_safety_footer")));
}
if($type != 'policy_procedures' && $type != 'operations_manual' && $type != 'emp_handbook' && $type != 'guide' && $type != 'safety') {
	$get_field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_hr_manuals` WHERE `tab` = '$type' AND `category` = '$category'"));
	$manual_type = $get_field_config['tab'];
	$value_config = ','.$get_field_config['fields'].',';
	define('HEADER_TEXT', html_entity_decode($get_field_config['pdf_header']));
	define('FOOTER_TEXT', html_entity_decode($get_field_config['pdf_footer']));
}

$pdf_html = '<form action="" method="POST" enctype="multipart/form-data"><h1>'.$manual_type.'</h1>';
if (strpos($value_config, ','."Topic (Sub Tab)".',') !== FALSE) {
   $pdf_html .= "<h3>Topic (Sub Tab): $category</h3>";
}
if (strpos($value_config, ','."Section Heading".',') !== FALSE) {
	$pdf_html .= "<h3>Section Heading: $heading_number - $heading</h3>";
}
if (strpos($value_config, ','."Sub Section Heading".',') !== FALSE) {
	$pdf_html .= "<h3>Sub Section Heading: $sub_heading_number - $sub_heading</h3>";
}
if (strpos($value_config, ','."Third Tier Heading".',') !== FALSE) {
	$pdf_html .= "<h3>Third Tier Heading: $third_heading_number - $third_heading</h3>";
}
if (strpos($value_config, ','."Detail".',') !== FALSE) {
	$pdf_html .= "<h3>Details</h3>";
	$pdf_html .= html_entity_decode($description);	
}
if (strpos($value_config, ','."Document".',') !== FALSE) {
	$links = mysqli_query($dbc, "SELECT upload, uploadid FROM manuals_upload WHERE type='document' AND manualtypeid='$manualtypeid' AND `upload` != ''");
	if(mysqli_num_rows($links) > 0) {
		$pdf_html .= "<h3>Documents</h3>\n<ul>";
		while($doc_link = mysqli_fetch_array($links)) {
			$pdf_html .= '<li><a href="'.WEBSITE_URL.'/Manuals/download/'.$doc_link['upload'].'" target="_blank">'.$doc_link['upload'].'</a></li>';
		}
		$pdf_html .= "</ul>";
	}
}
if (strpos($value_config, ','."Link".',') !== FALSE) {
	$links = mysqli_query($dbc, "SELECT upload, uploadid FROM manuals_upload WHERE type='link' AND manualtypeid='$manualtypeid' AND `upload` != ''");
	if(mysqli_num_rows($links) > 0) {
		$pdf_html .= "<h3>Links</h3>\n<ul>";
		while($url_link = mysqli_fetch_array($links)) {
			$pdf_html .= '<li><a href="'.$url_link['upload'].'" target="_blank">'.$url_link['upload'].'</a></li>';
		}
		$pdf_html .= "</ul>";
	}
}
if (strpos($value_config, ','."Videos".',') !== FALSE) {
	$links = mysqli_query($dbc, "SELECT upload, uploadid FROM manuals_upload WHERE type='video' AND manualtypeid='$manualtypeid' AND `upload` != ''");
	if(mysqli_num_rows($links) > 0) {
		$pdf_html .= "<h3>Videos</h3>\n<ul>";
		while($video_link = mysqli_fetch_array($links)) {
			$pdf_html .= '<li><a href="'.WEBSITE_URL.'/Manuals/download/'.$video_link['upload'].'" target="_blank">'.$video_link['upload'].'</a></li>';
		}
		$pdf_html .= "</ul>";
	}
}
if (strpos($value_config, ','."Comments".',') !== FALSE && $comment != '') {
	$pdf_html .= "<h3>Comments</h3>";
	$pdf_html .= '<table width="100%"><tr><td>'.html_entity_decode($comment).'</td></tr></table>';
}
else if (strpos($value_config, ','."Comments".',') !== FALSE && $action == 'pdf') {
	$pdf_html .= "<h3>Comments</h3>";
	$pdf_html .= '<table width="100%"><tr><td><textarea name="comment" rows="5" cols="90">'.html_entity_decode($comment).'</textarea></td></tr><tr><td>/td></tr></table><div style="clear:both;"></div><br />&nbsp;';
}
if (strpos($value_config, ','."Signature box".',') !== FALSE) {
	$pdf_html .= "<h3>Signed</h3>";
	if(!empty($manual_id)) {
		$pdf_html .= '<table width="100%" cellspacing="5"><tr><td width="40%" style="border-bottom: 1px solid black">';
		$pdf_html .= (file_exists('download/sign_'.$manual_id.'.png') ? '<img src="download/sign_'.$manual_id.'.png" height="30" border="0" alt="">' : '').'</td>';
		$pdf_html .= '<td width="40%" style="border-bottom: 1px solid black"><br /><br />'.get_contact($dbc, $staffid).'</td><td width="20%" style="border-bottom: 1px solid black"><br /><br />'.$today_date.'</td></tr>';
		$pdf_html .= '<tr><td>(Sign Here)</td><td>Print Name</td><td>Today\'s Date</td></tr></table>';
	} else {
		$pdf_html .= '<table width="100%" cellspacing="5"><tr><td width="40%" style="border-bottom: 1px solid black"><br /><br /><br /></td>';
		$pdf_html .= '<td width="40%" style="border-bottom: 1px solid black"></td><td width="20%" style="border-bottom: 1px solid black"></td></tr>';
		$pdf_html .= '<tr><td>(Sign Here)</td><td>Print Name</td><td>Today\'s Date</td></tr></table>';
	}
}
$pdf_html .= "</form>";

class MYPDF extends TCPDF {
	public function Header() {
		$this->SetFont('helvetica', '', 8);
		$header_text = HEADER_TEXT;
		$this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);
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
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($pdf_html, true, false, true, false, '');
$pdf->Output($pdf_path, 'F');
 ?>