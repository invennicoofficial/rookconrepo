<?php
	function copy_of_drivers_licence_safety_tickets_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_employee_driver_information_form` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_copy_of_drivers_licence_safety_tickets WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = date('Y-m-d');
    $contactid = $_SESSION['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
    $field_0 = explode('*#*', $fields[0]);
    $field_1 = explode('*#*', $fields[1]);
    $field_2 = explode('*#*', $fields[2]);
    $field_3 = explode('*#*', $fields[3]);
	$desc = explode('**FFM**', $get_field_level['desc']);
    $uploads = explode('**FFM**', $get_field_level['document']);

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

	$html .= "<h2>Copy of Driver's Licence and Safety Tickets</h2>"; // Form nu heading

    for ($i = 0; $i < count($field_0); $i++) {
    	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

        if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
    	    $html .= '<tr nobr="true">
                <th width="30%" style="background-color:lightgrey; color:black;">Drivers Licence</th>
                <td width="70%">'.$field_0[$i].'</td></tr>';
        }

        if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
             $html .= '<tr nobr="true">
                <th width="30%" style="background-color:lightgrey; color:black;">Title</th>
                <td width="70%">'.$field_3[$i].'</td></tr>';
        }

        if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
    	    $html .= '<tr nobr="true">
                <th width="30%" style="background-color:lightgrey; color:black;">Description</th>
                <td width="70%">'.$desc[$i].'</td></tr>';
        }

        if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
    	    $html .= '<tr nobr="true">
                <th width="30%" style="background-color:lightgrey; color:black;">Issue Date</th>
                <td width="70%">'.$field_1[$i].'</td></tr>';
        }

        if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
    	    $html .= '<tr nobr="true">
                <th width="30%" style="background-color:lightgrey; color:black;">Expiry Date</th>
                <td width="70%">'.$field_2[$i].'</td></tr>';
        }

        if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
            $file_path = strtolower(pathinfo($uploads[$i])['extension']);

    	    $html .= '<tr nobr="true">
                <th width="30%" style="background-color:lightgrey; color:black;">Upload Document</th>
                <td width="70%"><a href="'.$uploads[$i].'">'.$uploads[$i].'</a>';
            if ($file_path == 'jpg' || $file_path == 'png' || $file_path == 'gif') {
                $html .= '<br /><img src="copy_of_drivers_licence_safety_tickets/download/'.$uploads[$i].'">';
            }
            $html .= '</td></tr>';
        }

    	$html .= '</table>';
        $html .= '<div height="10"></div>';
    }

	$html .= '<br><img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;I agree to terms & conditions.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).'</h3>';

    $html .= '<img src="copy_of_drivers_licence_safety_tickets/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('copy_of_drivers_licence_safety_tickets/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("copy_of_drivers_licence_safety_tickets/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("copy_of_drivers_licence_safety_tickets/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>