<?php
	function employee_information_form_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_employee_information_form` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_information_form WHERE fieldlevelriskid='$fieldlevelriskid'"));
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

	$html = '<h2>Employee Information Form</h2>'; // Form nu heading

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Name</th>
            <td width="70%">'.$fields[1].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields0,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Date of Birth</th>
            <td width="70%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Address</th>
            <td width="70%">'.$fields[2].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Home Phone</th>
            <td width="70%">'.$fields[3].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Cell Phone</th>
            <td width="70%">'.$fields[4].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">SIN</th>
            <td width="70%">'.$fields[5].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Health Care Card Number</th>
            <td width="70%">'.$fields[6].'</td></tr>';
    }

	$html .= '</table>';

	$html .= '<br><table border="1px" style="padding:3px; border:1px solid black;">';
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Emergency Contacts</th>
            <td width="70%">Name : '.$fields[7].'<br>Phone : '.$fields[8].'<br>Relationship : '.$fields[9].'<br>Name : '.$fields[10].'<br>Phone : '.$fields[11].'<br>Relationship : '.$fields[12].'</td></tr>';
	$html .= '</table>';

	$html .= '<br><table border="1px" style="padding:3px; border:1px solid black;">';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Please list any relevant medical conditions</th>
            <td width="70%">'.html_entity_decode($fields[14]).'</td></tr>';

	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Are you on any medication?</th>
            <td width="70%">'.html_entity_decode($fields[16]).'You must notify Human Resources should your medication list change in the future (so proper care can be given in the event of an emergency).</td></tr>';

	$html .= '</table>';
//
    $html .= '<br><img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="employee_information_form/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('employee_information_form/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("employee_information_form/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("employee_information_form/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>




