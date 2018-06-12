<?php
	function contractor_orientation_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_contractor_orientation` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_contractor_orientation WHERE fieldlevelriskid='$fieldlevelriskid'"));
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

	$html = '<h2>Contractor Orientation</h2>'; // Form nu heading

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
             <th width="30%" style="background-color:lightgrey; color:black;">Contractor\'s name</th>
            <td width="70%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Start date</th>
            <td width="70%">'.$fields[1].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Orientation date</th>
            <td width="70%">'.$fields[2].'</td></tr>';
    }

    $html .= '</table>';

	$html .= '<h3>Orientation</h3>';

	$html .= '<h4>It is the practice of the Company to have all contractors review the Health, Safety & Environment Orientation prior to commencing work.</h4>';

	if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	    $html .= $fields[3].' : I agree to adhere to the Company Health and Safety Program and commit to aiding the Company in its goal of achieving a safe work environment.<br>';
    }

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
	$html .= $fields[4].' : I have been advised of the Company Safe Work Practices and Safe Job Procedures and will participate in all ongoing Job Hazard Assessments.';
    }

	$html .= '<h3>Shop & Yard Orientation</h3>';

	if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
	$html .= $fields[5].' : I have been provided and reviewed a map (or had a tour of) the Company shop and yard, showing buildings, exits, fire extinguishers, first aid kits and muster point. I have reviewed and I am familiar with the emergency response plan for the shop and yard.';
    }


	if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
		$html .= '<h3>PPE Checklist</h3>';

		$html .= '<br>Hard Hat';
        if ($fields[6]=='Hard Hat') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[7];

		$html .= '<br>CSA Approved Safety Glasses';
        if ($fields[8]=='CSA Approved Safety Glasses') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[9];

		$html .= '<br>CSA Approved Steel Toed Boots';
        if ($fields[10]=='CSA Approved Steel Toed Boots') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[11];

		$html .= '<br>Welding Helmet';
        if ($fields[12]=='Welding Helmet') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[13];

		$html .= '<br>Hearing Protection';
        if ($fields[14]=='Hearing Protection') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[15];
    }


		$html .= '<h3>Incident Reporting</h3>';

		$html .= '<h4>In the event an incident occurs, all contractors are to report the incident immediately to the Company. Contractors involved in an incident or as witness to an incident will participate in the investigation along with the Company. This will help create a safer work environment for all workers involved.</h4>';

	if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
		$html .= '<h3>Safety Tickets & Qualification</h3>';

		$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

		$html .= '<tr nobr="true">
            <th width="50%" style="background-color:lightgrey; color:black;">Name</th><th width="25%" style="background-color:lightgrey; color:black;">Issue Date</th><th width="25%" style="background-color:lightgrey; color:black;">Expiry Date</th></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[16]=='First Aid') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'First Aid</td><td>'.$fields[17].'</td><td>'.$fields[18].'</td></tr>';


		$html .= '<tr nobr="true"><td>';
        if ($fields[19]=='H2S') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'H2S</td><td>'.$fields[20].'</td><td>'.$fields[21].'</td></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[22]=='CSTS') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'CSTS</td><td>'.$fields[23].'</td><td>'.$fields[24].'</td></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[25]=='Journeyman Certificate') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'Journeyman Certificate</td><td>'.$fields[26].'</td><td>'.$fields[27].'</td></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[28]=='Ground Disturbance') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'Ground Disturbance</td><td>'.$fields[29].'</td><td>'.$fields[30].'</td></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[31]=='B Pressure') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= '"B" Pressure</td><td>'.$fields[32].'</td><td>'.$fields[33].'</td></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[34]=='Other') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'Other</td><td>'.$fields[35].'</td><td>'.$fields[36].'</td></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[37]=='Other-1') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'Other - 1</td><td>'.$fields[38].'</td><td>'.$fields[39].'</td></tr>';

		$html .= '<tr nobr="true"><td>';
        if ($fields[40]=='Other-2') {
            $html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;';
        }
        $html .= 'Other - 2</td><td>'.$fields[41].'</td><td>'.$fields[42].'</td></tr>';

		$html .= '</table>';
    }


	if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
		$html .= '<h3>Provided Documents</h3>';

		$html .= '<br>WCB Clearance Letter (addressed to the Company)';
        if ($fields[43]=='WCB Clearance Letter (addressed to the Company)') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[44];

		$html .= '<br>WCB Experience Rating/Premium Rate Statement';
        if ($fields[45]=='WCB Experience Rating/Premium Rate Statement') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[46];

		$html .= '<br>Current Certificate of Insurance';
        if ($fields[47]=='Current Certificate of Insurance') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[48];

		$html .= '<h4>Note: The Company must have proof of WCB Coverage prior to you being able to work . WCB rates are always taken into consideration and evaluated prior to work being issued.</h4>';
    }

	$html .= '<h3><img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).'</h3>';

    $html .= '<img src="contractor_orientation/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('contractor_orientation/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("contractor_orientation/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("contractor_orientation/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>




