<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
$style = $_GET['style'];
$estimateid = $_GET['edit'];
//$config_sql = "SELECT * FROM `estimate_pdf_setting` WHERE (`style` = '$style' OR '$style' = '') AND (`estimateid`='$estimateid' OR `estimateid` IS NULL) ORDER BY `estimateid` DESC, `style` ASC";
$config_sql = "SELECT * FROM `estimate_pdf_setting` WHERE `pdfsettingid`='$style' AND (`estimateid`='$estimateid' OR `estimateid` IS NULL) ORDER BY `estimateid` DESC, `style` ASC";
$settings = mysqli_fetch_assoc(mysqli_query($dbc, $config_sql));
$estimate = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
if(empty($_GET['style'])) {
	$_GET['style'] = $settings['style'];
}
switch($_GET['style']) {
	case 'c': include('design_styleC.php'); break;
	case 'b': include('design_styleB.php'); break;
	default: include('design_styleA.php'); break;
}
if (!file_exists('download')) {
    mkdir('download', 0777, true);
}
include_once('../tcpdf/tcpdf.php');
DEFINE('HEADER',$header_html);
DEFINE('FOOTER',$footer_html);
DEFINE('PDF_TOP_MARGIN',$top_margin);
DEFINE('PDF_BOTTOM_MARGIN',-$bottom_margin-5);
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// Position at 15 mm from top
		$this->SetY(PDF_TOP_MARGIN);
		$header = str_replace('[PAGE #]', $this->getAliasNumPage(), HEADER);
		$this->writeHTMLCell(0, 0, '', '', $header, 0, 0, false, "L", "R",true);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(PDF_BOTTOM_MARGIN);
		//$footer = str_replace('[PAGE #]', $this->getAliasNumPage(), FOOTER);
        $footer = str_replace('[PAGE #]', $this->PageNo(), FOOTER);
		$this->writeHTMLCell(220, 0, '', '', $footer, 0, 0, false, "L", false);
	}
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
$pdf->setFooterData(array(0,64,0), array(0,64,128));

$pdf->SetMargins($left_margin, $top_margin + ($pdf_logo == '' ? 15 : 35), $right_margin);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

if($settings['cover_text'] != '' || $settings['cover_logo'] != '') {
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	$pdf->AddPage();
	$pdf->writeHTML($cover_page, true, false, true, false, '');
	$pdf->setPrintHeader();
	$pdf->setPrintFooter();
}

if($settings['toc_content'] != '') {
	$pdf->AddPage();
	$pdf->writeHTML($toc_content, true, false, true, false, '');
}

if($settings['pages_text'] != '') {
	$pdf->AddPage();
	$pdf->writeHTML($pages_content, true, false, true, false, '');
}

$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('download/'.$file_name, 'F');

if(!empty($_GET['email'])) {
    $send_email_list = '';
    $send_email_list .= get_estimate($dbc, $estimateid, 'assign_staffid');
    $send_email_list .= ','.get_estimate($dbc, $estimateid, 'clientid');
    $email_list = explode(',',$send_email_list);
    foreach($email_list as $each_email) {
        $mail_send = get_email($dbc, $each_email);
        if($mail_send != '') {
            $meeting_attachment = 'download/'.$file_name;
            send_email('', $mail_send, '', '', 'Review '.ESTIMATE_TILE, 'Please find attached estimate for your review', $meeting_attachment);
        }
    }

    ?>
    <script>
    alert('Estimate Email Sent.');
    </script>
    <?php
}

?>
<script>
window.location.replace('download/<?= $file_name ?>');
</script>

