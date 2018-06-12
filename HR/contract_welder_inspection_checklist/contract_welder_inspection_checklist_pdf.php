<?php
	function contract_welder_inspection_checklist_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_contract_welder_inspection_checklist` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_contract_welder_inspection_checklist WHERE fieldlevelriskid='$fieldlevelriskid'"));
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

	$html .= '<h2>Contract Welder Inspection Checklist</h2>'; // Form nu heading

	$html .= '<h3>Note: This is only to be completed if the contractor is a welder</h3>';

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Inspected by</th>
            <td width="50%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Welder</th>
            <td width="50%">'.$fields[1].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Licence #</th>
            <td width="50%">'.$fields[2].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Make/Color/Unit #</th>
            <td width="50%">'.$fields[3].'</td></tr>';
    }

	$html .= '</table>';


	$html .= '<h3>Checklist</h3>';


	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';


	if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Vehicle registration and insurance is valid?</th>
            <td width="50%">'.$fields[4].'&nbsp;'.$fields[5].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Grinders and buffers have adequate guards/handles?</th>
            <td width="50%">'.$fields[6].'&nbsp;'.$fields[7].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Face shields used by grinder operators?</th>
            <td width="50%">'.$fields[8].'&nbsp;'.$fields[9].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Disk RPM rating matches the grinder/buffer rating?</th>
            <td width="50%">'.$fields[10].'&nbsp;'.$fields[11].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Fire extinguishers have valid inspection certification?</th>
            <td width="50%">'.$fields[12].'&nbsp;'.$fields[13].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Eye protection worn at all times?</th>
            <td width="50%">'.$fields[14].'&nbsp;'.$fields[15].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Hard hats worn when possible and where needed?</th>
            <td width="50%">'.$fields[16].'&nbsp;'.$fields[17].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Oxy/Acetylene torch has flame arresters on regulator end?</th>
            <td width="50%">'.$fields[18].'&nbsp;'.$fields[19].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Oxy/Acetylene hoses and fittings in good condition?</th>
            <td width="50%">'.$fields[20].'&nbsp;'.$fields[21].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Oxy/Acetylene bottles secured?</th>
            <td width="50%">'.$fields[22].'&nbsp;'.$fields[23].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Welding cables/electrical cords in good condition?</th>
            <td width="50%">'.$fields[24].'&nbsp;'.$fields[25].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields16,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">TDG place cards on bottle cabinet?</th>
            <td width="50%">'.$fields[26].'&nbsp;'.$fields[27].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields17,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Positive air shutoff on diesel trucks and welders?</th>
            <td width="50%">'.$fields[28].'&nbsp;'.$fields[29].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields18,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">First aid kit in truck?</th>
            <td width="50%">'.$fields[30].'&nbsp;'.$fields[31].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields19,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Fire extinguisher in truck?</th>
            <td width="50%">'.$fields[32].'&nbsp;'.$fields[33].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields20,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Overall condition of unit?</th>
            <td width="50%">'.$fields[34].'&nbsp;'.$fields[35].'</td></tr>';
    }

	if (strpos(','.$form_config.',', ',fields21,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Corrective Action (if required) to be completed by</th>
            <td width="50%">'.$fields[36].'</td></tr>';
    }


	$html .= '</table>';

	$html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="contract_welder_inspection_checklist/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('contract_welder_inspection_checklist/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("contract_welder_inspection_checklist/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("contract_welder_inspection_checklist/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>






