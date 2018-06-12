<?php
include('../../include.php');
include_once('../../tcpdf/tcpdf.php');

$fields = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `business_card_template` WHERE `template` = 'template_a' AND `contact_category` = 'Staff'"))['fields'];
if(empty($fields)) {
	$fields = 'Full Name#**#ID Number#**#Position#**#Contact Image#**#Signature#**#Back Header*#*BACK HEADER#**#Back Description*#*Back Description#**#Back Website*#*BACK WEBSITE#**#Back Twitter Icon#**#Back Facebook Icon#**#Back Instagram Icon#**#Back Youtube Icon';
}
$fields = explode('#**#', $fields);

foreach ($fields as $key => $value) {
	$value_arr = explode('*#*', $value);
	if($value_arr[0] == 'Back Header') {
		$back_header = $value_arr[1];
	}
	if($value_arr[0] == 'Back Description') {
		$back_description = $value_arr[1];
	}
	if($value_arr[0] == 'Back Website') {
		$back_website = $value_arr[1];
	}
}

$logo = get_config($dbc, 'logo_upload');
if(!empty($logo)) {
	$logo = WEBSITE_URL.'/Settings/download/'.$logo;
} else {
	$logo = WEBSITE_URL.'/img/logo.png';
}
$logo = str_replace(' ','%20',$logo);
if(isset($_GET['preview_template'])) {
	$full_name = 'Full Name';
	$id_num = '12345';
	$position = 'Position';
	$contact_image = WEBSITE_URL.'/img/icons/user.png';
	$pdf_url = 'download/template_a.pdf';
} else {
	if(isset($_GET['contactid']) && !empty($_GET['contactid'])) {
		$contactid = $_GET['contactid'];	
	} else {
		$contactid = $_SESSION['contactid'];
	}
	$full_name = get_contact($dbc, $contactid);
	$id_num = $contactid;
	$position = get_contact($dbc, $contactid, 'position');
	$contact_image = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `contactimage` FROM `contacts_upload` WHERE `contactid` = '$contactid'"))['contactimage'];
	if(file_get_contents(WEBSITE_URL.'/Profile/download/'.$contact_image)) {
		$contact_image = WEBSITE_URL.'/Profile/download/'.$contact_image;
	} else if(file_get_contents(WEBSITE_URL.'/Staff/download/'.$contact_image)) {
		$contact_image = WEBSITE_URL.'/Staff/download/'.$contact_image;
	} else {
		$contact_image = WEBSITE_URL.'/img/icons/user.png';
	}
	$pdf_url = 'download/'.$contactid.'_'.date('Y-m-d').'.pdf';
}

$front_html = '';
if(in_array('Full Name',$fields)) {
	$front_html .= $full_name.'<br>';
}
if(in_array('ID Number',$fields)) {
	$front_html .= $id_num.'<br>';
}
if(in_array('Position', $fields)) {
	$front_html .= $position.'<br>';
}
$front_html = trim($front_html,'<br>');

$social_icons = '';
if(in_array('Back Twitter Icon', $fields)) {
	$social_icons .= '<img style="height: 25px;" src="'.WEBSITE_URL.'/img/social/twitter-circle.png">&nbsp;&nbsp;&nbsp;';
}
if(in_array('Back Facebook Icon', $fields)) {
	$social_icons .= '<img style="height: 25px;" src="'.WEBSITE_URL.'/img/social/facebook-circle.png">&nbsp;&nbsp;&nbsp;';
}
if(in_array('Back Instagram Icon', $fields)) {
	$social_icons .= '<img style="height: 25px;" src="'.WEBSITE_URL.'/img/social/instagram-circle.png">&nbsp;&nbsp;&nbsp;';
}
if(in_array('Back Youtube Icon', $fields)) {
	$social_icons .= '<img style="height: 25px;" src="'.WEBSITE_URL.'/img/social/youtube-circle.png">&nbsp;&nbsp;&nbsp;';
}
$social_icons = trim($social_icons,'&nbsp;&nbsp;&nbsp;');

class MYPDF extends TCPDF {
    public function Header()
    {

    }
	public function footer() {

	}
}

$multiplier = 25.4;
$width = 3.5 * $multiplier;
$height = 2 * $multiplier;
$orientation = $height > $width ? 'P' : 'L';
$pdf = new MYPDF($orientation, PDF_UNIT, array($width, $height), true, 'UTF-8', false);
$pdf->SetMargins(0, 0, 0);
$pdf->SetAutoPageBreak(false, 0);

//Business Card Front
$front = '';
$front .= '<table cellspacing="10">';
$front .= '<tr>';
$front .= '<td width="57%" style="font-size: 9px;"><img src="'.$logo.'" style="width: 72px; height: 72px;"><br><br>';
$front .= $front_html;
$front .= '</td>';
$front .= '<td width="90px" style="text-align: right;"><img src="'.(in_array('Contact Image', $fields) ? $contact_image : '').'" style="width: 90px; height: 90px; border: 1px solid black;"><br>';
if(in_array('Signature', $fields)) {
	$front .= '<div style="width: 90px; height: 20px; border: 1px solid black; text-align: center; color: #ccc;">SIGNATURE</div>';
}
$front .= '</td>';
$front .= '</tr>';
$front .= '</table>';

$pdf->AddPage();
$pdf->writeHTML(utf8_encode($front), true, false, true, false, '');

//Business Card Back
$back = '';
$back .= '<table cellspacing="5">';
$back .= '<tr height="70%"><td style="text-align: center;"><b style="font-size: 8px; color: #4d86c2;">'.$back_header.'</b>';
$back .= '<p style="font-size: 6px; text-align: center;">'.html_entity_decode($back_description).'</p>';
$back .= '<b style="font-size: 8px; color: #b1242d;">'.$back_website.'</b>';
$back .= '</td></tr>';
$back .= '<tr height="30%"><td style="text-align: center;">';
$back .= $social_icons;
$back .= '</td></tr>';
$back .= '</table>';

$pdf->AddPage();
$pdf->writeHTML(utf8_encode($back), true, false, true, false, '');

if(!file_exists('download')) {
    mkdir('download', 0777, true);
}
$pdf->Output($pdf_url, 'F');

echo '<script type="text/javascript"> window.location.href = "'.$pdf_url.'"; </script>';

?>