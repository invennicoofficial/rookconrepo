<?php
	function absence_report_pdf($dbc,$hrid, $fieldlevelriskid) {

	$tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));

	//$result_update_employee = mysqli_query($dbc, "UPDATE `hr_absence_report` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_absence_report WHERE fieldlevelriskid='$fieldlevelriskid'"));
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

	$html = '<h2>Absence Report</h2>'; // Form nu heading

	$html .= '<table border="1px" style="padding:3px; border:1px solid black;">';

    if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Employee name</th>
            <td width="70%">'.$fields[0].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Position</th>
            <td width="70%">'.$fields[1].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Supervisor</th>
            <td width="70%">'.$fields[2].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Occurrence date(s)</th>
            <td width="70%">'.$fields[3].'</td></tr>';
    }

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
	$html .= '<tr nobr="true">
            <th width="30%" style="background-color:lightgrey; color:black;">Report date</th>
            <td width="70%">'.$fields[4].'</td></tr>';
    }

	$html .= '</table>';

	if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
		$html .= '<h4>Check reason for absence from work</h4>';

		$html .= '<br>Personal Illness';
        if ($fields[5]=='Personal Illness') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[6];

		$html .= '<br>Medical Reason/Medical Appointment';
        if ($fields[7]=='Medical Reason/Medical Appointment') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[8];

		$html .= '<br>Accident/Injury (outside of job)';
        if ($fields[9]=='Accident/Injury (outside of job)') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[10];

		$html .= '<br>Accident/Injury (job-related)';
        if ($fields[11]=='Accident/Injury (job-related)') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[12];

		$html .= '<br>Personal Reasons';
        if ($fields[13]=='Personal Reasons') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[14];

		$html .= '<br>Family Illness';
        if ($fields[15]=='Family Illness') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[16];

		$html .= '<br>Death in Family';
        if ($fields[17]=='Death in Family') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[18];

		$html .= '<br>Vacation';
        if ($fields[19]=='Vacation') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[20];

		$html .= '<br>Court Summons';
        if ($fields[21]=='Court Summons') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[22];

		$html .= '<br>Weather/Natural Disaster';
        if ($fields[23]=='Weather/Natural Disaster') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[24];

		$html .= '<br>Jury Duty';
        if ($fields[25]=='Jury Duty') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[26];

		$html .= '<br>Military Service';
        if ($fields[27]=='Military Service') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[28];

		$html .= '<br>Disciplinary Action';
        if ($fields[29]=='Disciplinary Action') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[30];

		$html .= '<br>Unknown';
        if ($fields[31]=='Unknown') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[32];

		$html .= '<br>Other';
        if ($fields[33]=='Other') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[34];

	}

	if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
		$html .= '<h4>Notification received from</h4>';

		$html .= '<br>Employee';
        if ($fields[35]=='Employee') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[36];

		$html .= '<br>Relative';
        if ($fields[37]=='Relative') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[38];

		$html .= '<br>Doctor';
        if ($fields[39]=='Doctor') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[40];

		$html .= '<br>Other';
        if ($fields[41]=='Other') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[42];

	}

	if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
		$html .= '<h4>Notification by</h4>';

		$html .= '<br>Telephone';
        if ($fields[43]=='Telephone') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[44];

		$html .= '<br>Email';
        if ($fields[45]=='Email') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[46];

		$html .= '<br>Text';
        if ($fields[47]=='Text') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[48];

		$html .= '<br>Writing';
        if ($fields[49]=='Writing') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[50];


		$html .= '<br>Other';
        if ($fields[51]=='Other') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[52];

	}

	if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
		$html .= '<h4>Action taken</h4>';

		$html .= '<br>Salary/Wage Deduction';
        if ($fields[53]=='Salary/Wage Deduction') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[54];

		$html .= '<br>None';
        if ($fields[55]=='None') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[56];

		$html .= '<br>Disciplinary Action';
        if ($fields[57]=='Disciplinary Action') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[58];

		$html .= '<br>Makeup Time';
        if ($fields[59]=='Makeup Time') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[60];


		$html .= '<br>Other';
        if ($fields[61]=='Other') {
            $html .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html .= '  '.$fields[62];

	}

	$html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">&nbsp;&nbsp;Information which I have provided here is true and correct. I have read and understand the content of this policy.<br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<img src="absence_report/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('absence_report/download/hr_'.$fieldlevelriskid.'.pdf', 'F');

    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("absence_report/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("absence_report/download/hr_".$_SESSION['contactid'].".png");

    echo '';
}
?>




