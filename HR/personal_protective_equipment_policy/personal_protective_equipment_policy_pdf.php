<?php
function personal_protective_equipment_policy_pdf($dbc,$hrid, $fieldlevelriskid) {
    $tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $hr_description = $get_field_config['hr_description'];
    $config_extra_fields = explode('**FFM**',$get_field_config['config_extra_fields']);

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_personal_protective_equipment_policy WHERE fieldlevelriskid='$fieldlevelriskid'"));
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

    $html = '<center><h2>PERSONAL PROTECTIVE EQUIPMENT POLICY</h2><center>';

	$html = '<center><img src="download/pdf-logo.png" width="150px" width="150px" alt="pdf-logo"/><center>';

    $email_body = html_entity_decode($hr_description);

	$html .= $email_body;
	
    $html .= '<br> <br> <table style="width:120%">
	<tr>
		<td colspan="2"> &nbsp; </td>
	</tr>
	<tr>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature</td>
		<td>Date</td>
	</tr>
	<tr>
		<td><img src="personal_protective_equipment_policy/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt=""></td>
		<td>'.date("Y-m-d").'</td>
	</tr>
</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('personal_protective_equipment_policy/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("personal_protective_equipment_policy/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("personal_protective_equipment_policy/download/hr_".$_SESSION['contactid'].".png");
    echo '';
}
?>