<?php
	function safe_work_permit_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_safe_work_permit WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_safe_work_permit` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $fields = explode('**FFM**', $get_field_level['fields']);
	$desc = $get_field_level['desc'];
	$desc1 = $get_field_level['desc1'];


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

	$html_weekly = '<h2>Safe Work Permit</h2>'; // Form nu heading

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Date</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[0].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="100%">Permit Type</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[1].'<br>'.$fields[2].'</td></tr>';

    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">PERMIT #</th><th width="50%">Location</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[3].'</td><td>'.$fields[4].'</td></tr>';
	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Issued by</th><th width="50%">Phone</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[5].'</td><td>'.$fields[6].'</td></tr>';
	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Issued to</th><th width="50%">Phone</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$fields[7].'</td><td>'.$fields[8].'</td></tr>';

	$html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="50%">Contractor</th><th width="25%"># of Workers</th><th width="25%"># of Vehicles</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$fields[9].'</td><td>'.$fields[10].'</td><td>'.$fields[11].'</td></tr>';

	$html_weekly .= '</table>';

    $html_weekly .= '<h4>Scope of work/Comments</h4>' . html_entity_decode($desc);

	$html_weekly .= '<h4>Hazards</h4>';

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Flammable Gas';
        if ($fields[12]=='Flammable Gas') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[13];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Flammable Liquid';
        if ($fields[14]=='Flammable Liquid') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[15];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Pressure';
        if ($fields[16]=='Pressure') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[17];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Hydrogen Sulfide';
        if ($fields[18]=='Hydrogen Sulfide') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[19];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>NORM/Radiation';
        if ($fields[20]=='NORM/Radiation') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[21];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Chemicals';
        if ($fields[22]=='Chemicals') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[23];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Asbestos';
        if ($fields[24]=='Asbestos') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[25];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Organic Vapours';
        if ($fields[26]=='Organic Vapours') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[27];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Iron Sulphides';
        if ($fields[28]=='Iron Sulphides') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[29];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Rotating Equipment';
        if ($fields[30]=='Rotating Equipment') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[31];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Hot/Cold Piping';
        if ($fields[32]=='Hot/Cold Piping') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[33];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Electrical Equipment';
        if ($fields[34]=='Electrical Equipment') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[35];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Noise';
        if ($fields[36]=='Noise') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[37];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Driving';
        if ($fields[38]=='Driving') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[39];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Working at Heights';
        if ($fields[40]=='Working at Heights') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[41];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Working Alone';
        if ($fields[42]=='Working Alone') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[43];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Overhead Hazards';
        if ($fields[44]=='Overhead Hazards') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[45];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Ground Disturbance';
        if ($fields[46]=='Ground Disturbance') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[47];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Confined Space';
        if ($fields[48]=='Confined Space') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[49];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Combustibles';
        if ($fields[50]=='Combustibles') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[51];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Lifting';
        if ($fields[52]=='Lifting') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[53];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Underground Facilities';
        if ($fields[54]=='Underground Facilities') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[55];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Others';
        if ($fields[56]=='Others') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[57];
    //}


	$html_weekly .= '<h3>Control Measures</h3>';

	$html_weekly .= '<h4>Communication</h4>';

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Pre Job Safety Meeting';
        if ($fields[58]=='Pre Job Safety Meeting') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[59];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>MSDS present and reviewed';
        if ($fields[60]=='MSDS present and reviewed') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[61];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Radio/Communication present';
        if ($fields[62]=='Radio/Communication present') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[63];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Code pf Practice Reviewed';
        if ($fields[64]=='Code pf Practice Reviewed') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[65];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>SWP Reviewed';
        if ($fields[66]=='SWP Reviewed') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[67];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Additional Hazard Assessment';
        if ($fields[68]=='Additional Hazard Assessment') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[69];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>One Call Notification';
        if ($fields[70]=='One Call Notification') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[71];
    //}


	$html_weekly .= '<h4>Erp</h4>';

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Fire Extinguishers';
        if ($fields[72]=='Fire Extinguishers') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[73];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>ERP Plan';
        if ($fields[74]=='ERP Plan') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[75];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Safety Watch Required';
        if ($fields[76]=='Safety Watch Required') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[77];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Muster Point Identified';
        if ($fields[78]=='Muster Point Identified') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[79];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>First Aid Plan';
        if ($fields[80]=='First Aid Plan') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[81];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Spill Controls';
        if ($fields[82]=='Spill Controls') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[83];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Line Strike procedures';
        if ($fields[84]=='Line Strike procedures') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[85];
    //}

	$html_weekly .= '<h4>SWP and Training</h4>';


	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Atmospheric Testing';
        if ($fields[86]=='Atmospheric Testing') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[87];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Safety Watch';
        if ($fields[88]=='Safety Watch') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[89];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Continuous Atmospheric Testing';
        if ($fields[90]=='Continuous Atmospheric Testing') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[91];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Equipment De-energized';
        if ($fields[92]=='Equipment De-energized') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[93];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Lock Out/Tag Out Performed';
        if ($fields[94]=='Lock Out/Tag Out Performed') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[95];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Ground Disturbance Procedure/Training';
        if ($fields[96]=='Ground Disturbance Procedure/Training') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[97];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Confined Space Procedure/Training';
        if ($fields[98]=='Confined Space Procedure/Training') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[99];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Venting/Purging';
        if ($fields[100]=='Venting/Purging') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[101];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Working Alone Procedure';
        if ($fields[102]=='Working Alone Procedure') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[103];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Blanks/Blinds Installed';
        if ($fields[104]=='Blanks/Blinds Installed') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[105];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Work are flagged off/Signage';
        if ($fields[106]=='Work are flagged off/Signage') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[107];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Cathodic Protection';
        if ($fields[108]=='Cathodic Protection') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[109];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Working at Heights';
        if ($fields[110]=='Working at Heights') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[111];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Site Orientation';
        if ($fields[112]=='Site Orientation') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[113];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Equipment Use/Competent Operator';
        if ($fields[114]=='Equipment Use/Competent Operator') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[115];
    //}


	$html_weekly .= '<h4>Safety & PPE</h4>';

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Hearing Protection';
        if ($fields[116]=='Hearing Protection') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[117];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Personal Atmospheric Monitor';
        if ($fields[118]=='Personal Atmospheric Monitor') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[119];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Supplied Breathing Air';
        if ($fields[120]=='Supplied Breathing Air') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[121];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Harness and Lifeline';
        if ($fields[122]=='Harness and Lifeline') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[123];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Fall Protection Harness';
        if ($fields[124]=='Fall Protection Harness') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[125];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Scaffolding';
        if ($fields[126]=='Scaffolding') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[127];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Respirator/Fit Test';
        if ($fields[128]=='Respirator/Fit Test') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[129];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>First Aid Facilities/Eye Wash';
        if ($fields[130]=='First Aid Facilities/Eye Wash') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[131];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Eye/Face Protection';
        if ($fields[132]=='Eye/Face Protection') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[133];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Fire Retardant Clothing';
        if ($fields[134]=='Fire Retardant Clothing') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[135];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Bonding/Grounding';
        if ($fields[136]=='Bonding/Grounding') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[137];
    //}

	$html_weekly .= '<h4>Other</h4>' . html_entity_decode($desc1);

	$html_weekly .= '<h4>Ground Disturbance</h4>';

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Crossing Agreement';
        if ($fields[138]=='Crossing Agreement') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[139];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Alberta One Call Notified';
        if ($fields[140]=='Alberta One Call Notified') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[141];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Land Owner Notified';
        if ($fields[142]=='Land Owner Notified') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[143];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>All Facilities Owners Notified';
        if ($fields[144]=='All Facilities Owners Notified') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[145];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Any Signs of New Ground Disturbance?';
        if ($fields[146]=='Any Signs of New Ground Disturbance?') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[147];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Hand Exposure Required';
        if ($fields[148]=='Hand Exposure Required') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[149];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Code of Practice On-Site';
        if ($fields[150]=='Code of Practice On-Site') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[151];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Copy of The Pipeline Act On-Site';
        if ($fields[152]=='Copy of The Pipeline Act On-Site') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[153];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Supervisor Has GD Level II Training';
        if ($fields[154]=='Supervisor Has GD Level II Training') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[155];
    //}

	//if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= '<br>Back Fill Inspections Complete Before Final Sign off';
        if ($fields[156]=='Back Fill Inspections Complete Before Final Sign off') {
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '  '.$fields[157];
    //}

	$html_weekly .= '<h4>Validation</h4>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
            <th width="25%">Issue Date</th><th width="25%">Time</th><th width="25%">Expires</th><th width="25%">Time</th></tr>';

	$html_weekly .= '<tr nobr="true"><td>'.$fields[158].'</td><td>'.$fields[159].'</td><td>'.$fields[160].'</td><td>'.$fields[161].'</td></tr>';

	$html_weekly .= '</table><br>';


	$sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';

        // avs_near_miss = form name

        $html_weekly .= '<td data-title="Email"><img src="safe_work_permit/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('safe_work_permit/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("safe_work_permit/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




