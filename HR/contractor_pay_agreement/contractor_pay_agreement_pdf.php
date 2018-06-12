<?php
	function contractor_pay_agreement_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_contractor_pay_agreement` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_contractor_pay_agreement WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);

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
        $pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);
    } else {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    }
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, 40);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);

	$html = '<h2>Contractor Pay Agreement</h2>'; // Form nu heading

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Name</th>
            <td width="70%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Company</th>
            <td width="70%">'.$fields[1].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Address</th>
            <td width="70%">'.$fields[2].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Phone #</th>
            <td width="70%">'.$fields[3].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">WCB #</th>
            <td width="70%">'.$fields[4].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">WCB #</th>
            <td width="70%">'.$fields[5].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">WCB #</th>
            <td width="70%">'.$fields[6].'</td></tr>';
    }

	$html .= '</table>';


	$html .= '<h3>Rate Of Pay</h3>';

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Per Hour (regular time pay)</th>
            <td width="70%">'.$fields[7].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Per Hour (overtime pay)</th>
            <td width="70%">'.$fields[8].'&nbsp;&nbsp;Note: Overtime pay is paid after 8 regular hours per day, excluding travel.</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Truck classification</th>
            <td width="70%">'.$fields[9].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Rate of Pay (per hour)</th>
            <td width="70%">'.$fields[10].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">up to a daily maximum of</th>
            <td width="70%">'.$fields[11].'</td></tr>';
    }

	$html .= '<tr nobr="true"><td width="100%">The travel time rate for the above mentioned will be.<br> Note: Travel time is only paid from the Company yard to the job site and back.</td></tr>';

	if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Regular</th>
            <td width="70%">'.$fields[12].'&nbsp;per hour (regular rate x 0.5)</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Overtime</th>
            <td width="70%">'.$fields[13].'&nbsp;per hour (regular TT rate x 2)</td></tr>';
    }

	$html .= '</table>';


	if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
        $html .= '<br><h3>PAY OPTIONS - Please tick the pay option selected</h3>';

        $html .= '<br>Option #1 - Two Week Rotating';
        if ($fields[14]=='Option #1 - Two Week Rotating') {
                $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[15];

        $html .= '<br><br>- A 5% processing/handling fee will be charged and deducted off each invoice submitted to the Company for payment.<br>';

        $html .= "- Cutoff to coincide with the Company's payroll cutoff date.<br>";

        $html .= '- Invoice must be submitted within 2 days of cutoff for processing.<br>';
    }

	if (strpos(','.$form_config.',', ',fields16,') !== FALSE) {
        $html .= '<br>Option #2 - Month End';
        if ($fields[16]=='Option #2 - Month End') {
                $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[17];

        $html .= '<br><br>- Submit invoices at the end of each month to the Company for FULL payment by the end of the following month.<br>';

        $html .= 'Note: Option cannot be changed without written management approval.';
    }

	$html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="contractor_pay_agreement/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('contractor_pay_agreement/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("contractor_pay_agreement/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("contractor_pay_agreement/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>




