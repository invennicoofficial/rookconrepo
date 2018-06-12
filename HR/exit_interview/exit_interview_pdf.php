<?php
	function exit_interview_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_exit_interview` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_exit_interview WHERE fieldlevelriskid='$fieldlevelriskid'"));
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

	$html = '<h2>Exit_Interview</h2>'; // Form nu heading

	$html .= '<h3>This interview is confidential and will not be kept on your file. It will not be used when providing references nor will it affect your potential to return to the company. The questions being asked are used to help us become a better employer.</h3>';

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Employee Name</th>
            <td width="70%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Date of Exit</th>
            <td width="70%">'.$fields[1].'</td></tr>';
    }

	$html .= '</table><br>';

	if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
		$html .= '<h4>1. What is the main reason you decided to leave your job?</h4>'.html_entity_decode($fields[2]);
	}

	if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
		$html .= '<h4>2. What did you like best about your job/working for the Company?</h4>'.html_entity_decode($fields[3]);
	}

	if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
		$html .= '<h4>3. What did you like least about your job/working for the Company?</h4>'.html_entity_decode($fields[4]);
	}

	if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
		$html .= '<h4>4. How did you feel about the way you were managed daily/weekly/monthly? Did you feel your manager was available when you needed them?</h4>'.html_entity_decode($fields[5]);
	}

	if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
		$html .= '<h4>5. How did you feel about the level of supervision and feedback you received?</h4>'.html_entity_decode($fields[6]);
	}

	if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
		$html .= '<h4>6. Did you feel the expectations and demands that were placed on you were fair and manageable? Were you capable of taking on more responsibility and held back in any way?</h4>'.html_entity_decode($fields[7]);
	}

	if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
		$html .= '<h4>7. How did you feel about your coworkers, the office environment, and morale? Did you feel like a valued and included member of the team?</h4>'.html_entity_decode($fields[8]);
	}

	if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
		$html .= '<h4>8. What could the Company do to increase morale and employee happiness to retain its best employees?</h4>'.html_entity_decode($fields[9]);
	}

	if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
		$html .= '<h4>9. According to you who was a strong leader? Did the staff feel they were approachable?</h4>'.html_entity_decode($fields[10]);
	}

	if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
		$html .= '<h4>10. Did you feel that the management team communicated well with the staff? Were goals and objectives to be met clearly outlined?</h4>'.html_entity_decode($fields[11]);
	}

	if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
		$html .= '<h4>11. Do you feel that the company will continue to be successful in the future? Why or why not?</h4>'.html_entity_decode($fields[13]);
	}

	if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
		$html .= '<h4>12. What would you suggest we do to make the company a better place to work?</h4>'.html_entity_decode($fields[14]);
	}

	if (strpos(','.$form_config.',', ',fields16,') !== FALSE) {
		$html .= '<h4>13. Would you work for the company again in the future?</h4>'.html_entity_decode($fields[15]);
	}

	if (strpos(','.$form_config.',', ',fields17,') !== FALSE) {
		$html .= '<h4>14. Do you have any other comments or insights about your time at the company?</h4>'.html_entity_decode($fields[16]);
	}

	$html .= 'Thank you for your honesty and opinions!<br><br>';

    $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="exit_interview/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('exit_interview/download/hr_'.$fieldlevelriskid.'.pdf', 'F');

    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("exit_interview/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("exit_interview/download/hr_".$fieldlevelriskid.".png");

    echo '';
}
?>




