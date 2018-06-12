<?php
function driver_consent_form_pdf($dbc,$hrid, $fieldlevelriskid) {
    $tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $hr_description = $get_field_config['hr_description'];
    $config_extra_fields = explode('**FFM**',$get_field_config['config_extra_fields']);

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_driver_consent_form WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

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
        $pdf->SetMargins(PDF_MARGIN_LEFT, 55, PDF_MARGIN_RIGHT);
    } else {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
    }
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, 40);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);

    $html = '<div style="text-align:center"><img src="download/pdf-logo.png" width="150px" alt="pdf-logo"/></div>';

	$html .= '<div style="text-align:center"><h2> DRIVER&#39;S CONSENT FORM </h2></div> <br> ';
	
	$html .= '
	
		<table style="width:100%">
			<tr>
				<td style="width:15%"> I, </td>
				<td style="width:25%;text-align:center"> <b> '.$fields[0].'</b> </td>
				<td style="width:15%"> of </td>
				<td style="width:25%;text-align:center"> <b> '.$fields[1].'</b> </td>
			</tr>
			<tr>
				<td style="width:15%"> &nbsp; </td>
				<td style="width:25%;text-align:center"> (Name) </td>
				<td style="width:15%"> &nbsp; </td>
				<td style="width:25%;text-align:center"> (Address) </td>
			</tr>
		</table>
		<br>
		<p style=""> in the Province of '.$fields[11].' hereby consent to the disclosure of my driver&#39;s abstract/record, which is made from personal information in the Motor Vehicle Registry of the Province of </p>
		<table style="width:100%">
			<tr>
				<td style="width:80%"> to : <b> '.$fields[12].'</b> </td>
				<td style="width:10%;text-align:center"> &nbsp; </td>
			</tr>
			<tr>
				<td style="width:25%"> &nbsp; </td>
				<td style="width:65%;text-align:center"> <b> '.$fields[12].'</b> </td>
			</tr>
			<tr>
				<td style="width:25%;"> &nbsp; </td>
				<td style="width:65%;text-align:center"> (Name of person to whom the information is being disclosed) </td>
			</tr>
		</table>
		<br>
		<p style=""> Who may use this personal information for the following purpose(s): </p>
		<br>
		<table style="width:100%">
			<tr>
				<td style="width:100%;text-align:center"> <b> '.$fields[3].'</b> </td>
			</tr>
			<tr>
				<td style="width:100%;text-align:center"> (List specific purpose or purposes) </td>
			</tr>
		</table>
		<br><br>
		<div style="text-align:center">
<span style=""> DATED this </span> <span style="margin-left:30px; ; margin-right:30px;"><b> '.$fields[4].'</b>  </span> <span style=""> day of </span> <span style="margin-right:30px; margin-left:30px; "> <b>'.$fields[5].'</b> </span> <span style=""> ,20 </span> <span style="margin-right:30px; margin-left:30px; "> <b>'.$fields[6].'</b>  </span></div>

<br><br>

<table style="width:100%">
  <tr>
    <th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;">Signature</th>
    <th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;">Driver&#39;s License Number</th>
	<th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;">Witness Signature</th>
  </tr>
   <tr>
    <td style="border:1px solid black;border-collapse:collapse;padding:5px 10px;"><img src="driver_consent_form/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt=""></td>
    <td style="border:1px solid black;border-collapse:collapse;padding:5px 10px;">'.$fields[7].' </td>
	<td style="border:1px solid black;border-collapse:collapse;padding:5px 10px;">'.$fields[8].' </td>
  </tr>
  <tr>
    <th style="border:1px solid black;border-collapse:collapse;width:32%;padding:5px 10px;">Date</th>
    <th colspan="2" style="border:1px solid black;border-collapse:collapse;width:64%;padding:5px 10px;">Print Name of Witness</th>
  </tr>
   <tr>
    <td style="border:1px solid black;border-collapse:collapse;padding:5px 10px;;">'.$fields[9].' </td>
    <td colspan="2" style="border:1px solid black;border-collapse:collapse;padding:5px 10px;">'.$fields[10].' </td>
  </tr>
</table>';
    /*
    $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">Information which I have provided here is true and correct. I have read and understand the content of this policy.';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="employment_verification_letter/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';
    */

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('employment_verification_letter/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("employment_verification_letter/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("employment_verification_letter/download/hr_".$_SESSION['contactid'].".png");
    echo '';
}
?>