<?php function time_off_request_pdf($dbc,$hrid, $fieldlevelriskid, $url_redirect) {
	include_once('../tcpdf/tcpdf.php');
	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_time_off_request` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_time_off_request WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];

	class MYPDF extends TCPDF {

        //Page header
        public function Header() {
            if(PDF_LOGO != '' && file_get_contents('../HR/download/'.PDF_LOGO)) {
                $image_file = '../HR/download/'.PDF_LOGO;
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

	$html = '<h2>Time Off Request</h2>'; // Form nu heading

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Type of Absence Requested</th>
            <td width="70%">'.$fields[0].'<br>'.$fields[1].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Dates of Absence</th>
            <td width="70%">Start Date : '.$fields[2].'<br>End Date : '.$fields[3].'</td></tr>';
    }

	$html .= '</table>';

	$html .= '<br><table border="1px" style="padding:3px; border:1px solid black;">';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Reason for Absence</th>
            <td width="70%">'.html_entity_decode($desc).'</td></tr>';

	$html .= '</table>';
//
    $html .= 'You must submit requests for absences two days prior to the first day you will be absent. Absences for 5 or more days require a minimum of 1 month notice prior to the first day you will be absent.</h4><br><img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;I hereby confirm to the best of my knowledge that all of the information above is true and correct.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="../HR/time_off_request/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('../HR/time_off_request/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
	if($url_redirect != 'N/A') {
		echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
		window.open("../HR/time_off_request/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
		</script>';
	}

    unlink("../HR/time_off_request/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>




