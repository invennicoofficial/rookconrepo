<?php
function employment_agreement_pdf($dbc,$hrid, $fieldlevelriskid) {
    $tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $hr_description = $get_field_config['hr_description'];
    $config_extra_fields = explode('**FFM**',$get_field_config['config_extra_fields']);

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employment_agreement WHERE fieldlevelriskid='$fieldlevelriskid'"));
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

    $html = '<div style="text-align:center"><img src="download/pdf-logo.png" width="150px" width="150px" alt="pdf-logo"/></div>';

	$html .= '<div style="text-align:center"><h2> Employment Agreement </h2></div> <br> <br> ';

	$html .= 'I, <span style=""> <b> ' . $fields[0] . '</b> , acknowledge and agree to the terms and conditions specified below which will form part of, but not be limited to, my employment obligations to Evergreen Services Inc.
			<br><br>	
			I acknowledge and agree: <br>
			1. To be subject to substance abuse tests according to the specifications under the Substance Abuse Policy.<br><br>
			 
			2. To follow all Evergreen Services Inc Policies, Practices, and Procedures and to take the mandatory training that Evergreen Services Inc provides before operating equipment or tools.<br><br>
			 
			3. To be subject to Evergreen Services Discipline Policy and accept that cases of discipline will be handled according to Evergreen Services Inc&#39;s management&#39;s discretion.<br><br>
			 
			4. If, from the time of rehire after breach of the Substance Abuse Policy, I test positive, my employment will be terminated immediately and I will not be entitled to the same courtesies granted by Evergreen Services Inc. under the Substance Abuse Policy for first time violations; more particularly, Company provided rehabilitation.  Further, I will not be eligible for rehire in the future under any circumstances whatsoever and termination in such case will be final; and <br><br>
			 
			5. By dating and signing in the appropriate location below.<br>

			<br><br>

			Date : '.date("Y-m-d").'<br><br>';


	$html .= '<br> <br> <table style="width:120%">
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Employee</td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manager</td>
				</tr>
				<tr>
					<td><img src="employment_agreement/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt=""></td>
					<td><img src="employment_agreement/download/hr_'.$_SESSION['contactid'].'_sign2_.png" width="150" height="70" border="0" alt=""></td>
				</tr>
			</table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('employment_agreement/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("employment_agreement/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("employment_agreement/download/hr_".$_SESSION['contactid'].".png");
	unlink("employment_agreement/download/hr_".$_SESSION['contactid']."_sign2_.png");
    echo '';
}
?>