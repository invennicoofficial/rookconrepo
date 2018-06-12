<?php
	function employee_self_evaluation_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_employee_self_evaluation` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_self_evaluation WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];
	$desc2 = $get_field_level['desc2'];
	$desc3 = $get_field_level['desc3'];
	$desc4 = $get_field_level['desc4'];
	$desc5 = $get_field_level['desc5'];
	$desc6 = $get_field_level['desc6'];
	$desc7 = $get_field_level['desc7'];
	$desc8 = $get_field_level['desc8'];
	$desc9 = $get_field_level['desc9'];
	$desc10 = $get_field_level['desc10'];

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

	$html = '<h2>Employee Self Evaluation</h2>'; // Form nu heading

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Employee Name</th>
            <td width="70%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Job Title</th>
            <td width="70%">'.$fields[1].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Manager</th>
            <td width="70%">'.$fields[2].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Date</th>
            <td width="70%">'.$fields[3].'</td></tr>';
    }

	$html .= '</table>';

	$html .= '<h2>Goals</h2>';

	$html .= '<br><table border="1px" style="padding:3px; border:1px solid black;">';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Describe the goals you had set out to accomplish for this time period:</th>
            <td width="70%">'.html_entity_decode($desc).'</td></tr>';


	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Which goals did you accomplish?</th>
            <td width="70%">'.html_entity_decode($desc1).'</td></tr>';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Which goals were not accomplished and why?</th>
            <td width="70%">'.html_entity_decode($desc2).'</td></tr>';


	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">What other objectives did you meet beyond your stated goals?</th>
            <td width="70%">'.html_entity_decode($desc3).'</td></tr>';


	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">What achievements are you most proud of?</th>
            <td width="70%">'.html_entity_decode($desc4).'</td></tr>';

	$html .= '</table>';

	$html .= '<h2>Expectations</h2>';
	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">What are your goals for the next evaluation period? Please be clear and concise.</th>
            <td width="70%">'.html_entity_decode($desc5).'</td></tr>';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">What can your supervisor do to help you achieve your future goals?</th>
            <td width="70%">'.html_entity_decode($desc6).'</td></tr>';

	$html .= '</table>';

	$html .= '<h2>The Company</h2>';
	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">What as a company do we do well?</th>
            <td width="70%">'.html_entity_decode($desc7).'</td></tr>';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">What as a company could we improve on?</th>
            <td width="70%">'.html_entity_decode($desc8).'</td></tr>';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">How could you assist with this improvement?</th>
            <td width="70%">'.html_entity_decode($desc9).'</td></tr>';

	$html .= '</table>';

	$html .= '<h2>Additional Comments</h2>'.html_entity_decode($desc10);

    $html .= '<br><img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="employee_self_evaluation/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('employee_self_evaluation/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("employee_self_evaluation/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("employee_self_evaluation/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>





