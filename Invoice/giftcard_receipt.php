<?php
/*
 * Invoice Format for Receipt Printers
 * Copied from pos_invoice_2.php
 */
include_once ('../include.php');
if(FOLDER_NAME == 'posadvanced') {
    checkAuthorised('posadvanced');
} else {
    checkAuthorised('check_out');
}
include_once('../tcpdf/tcpdf.php');
ob_clean();
$giftcard_id = filter_var($_GET['giftcard'],FILTER_SANITIZE_STRING);
$get_gf = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `pos_giftcards` WHERE `posgiftcardsid`='$giftcard_id'"));
$pos_logo = get_config($dbc, 'pos_logo');
if(file_get_contents('../Invoice/download/'. $pos_logo)) {
	$pos_logo = '../Invoice/download/'. $pos_logo;
} else if(file_get_contents('../Point of Sale/download/'. $pos_logo)) {
	$pos_logo = '../Point of Sale/download/'. $pos_logo;
} else {
	$pos_logo = '../POSAdvanced/download/'. $pos_logo;
}
$invoice_footer = html_entity_decode(get_config($dbc, 'invoice_footer'));

DEFINE('POS_LOGO', $pos_logo);
DEFINE('INVOICE_FOOTER', $invoice_footer);
DEFINE('INVOICE_DATE', $get_gf['issue_date']);
DEFINE('SALESPERSON', decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']));

// PDF
class MYPDF extends TCPDF {
	//Page header
	public function Header() {}
    protected $last_page_flag = false;
    public function Close() {
        $this->last_page_flag = true;
        parent::Close();
    }


	// Page footer
	public function Footer() {
        $this->SetY(-10);
		$this->SetFont('helvetica', 'I', 7);
        if ($this->last_page_flag) {
            $footer_text = INVOICE_FOOTER;
        } else {
            $footer_text = '';
        }
        $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, 0, false, "C", true);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A7', true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
$pdf->setFooterData(array(0,64,0), array(0,64,128));

//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(5, 5, 5);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, 0);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8);

$html = '';

$html .= '<p style="text-align:center;"><img src="'.POS_LOGO.'" width="100" /><br /><br />Gift Card #: '. $giftcard_id .'<br />Date: '. INVOICE_DATE .'</p>';
//$html .= '<br /><br /><br /><p style="text-align:center;">'. ( (!empty($customer['name'])) ? decryptIt($customer['name']) . '<br />' : '' ) . decryptIt($customer['first_name']) .' '. decryptIt($customer['last_name']) .'<br />'. ( (!empty($customer['mailing_address'])) ? $customer['mailing_address'] . '<br />' : '' ) . ( (!empty($customer['city'])) ? $customer['city'] . '<br />' : '' ) . ( (!empty($customer['postal_code'])) ? $customer['postal_code'] . '<br />' : '' ) . ( (!empty($customer['cell_phone'])) ? decryptIt($customer['cell_phone']) . '<br />' : '' ) . ( (!empty($customer['email_address'])) ? ecryptIt($customer['email_address']) : '' ) . '</p>';


//START GIFT CARDS
$html .= '<table border="0" cellpadding="2">
	<tr>
		<td>Gift Card #'.$giftcard_id.'</td>
		<td style="text-align:right;">$'. number_format($get_gf['value'],2) .'</td>
	</tr>';
// END GIFT CARDS

$html .= '<tr>
		<td style="text-align:right;" width="75%"><strong>Total</strong></td>
		<td border="1" width="25%" style="text-align:right;">$'. number_format($get_gf['value'],2) .'</td>
	</tr>
</table>';


if ( !file_exists('download') ) {
	mkdir('download', 0777, true);
}

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('download/gf_'.$giftcard_id.'.pdf');