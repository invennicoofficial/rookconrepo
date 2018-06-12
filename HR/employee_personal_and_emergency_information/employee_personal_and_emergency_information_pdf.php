<?php
function employee_personal_and_emergency_information_pdf($dbc,$hrid, $fieldlevelriskid) {
    $tab = get_hr($dbc, $hrid, 'tab');
    $form = get_hr($dbc, $hrid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_hr WHERE tab='$tab' AND form='$form'"));
    $hr_description = $get_field_config['hr_description'];
    $config_extra_fields = explode('**FFM**',$get_field_config['config_extra_fields']);

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_personal_and_emergency_information WHERE fieldlevelriskid='$fieldlevelriskid'"));
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


    $html = '<h2>Employee Personal and Emergency Information Form</h2>';
    
	$html .= '
			<table style="border:1px solid black;border-collapse:collapse;width:100%">
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Full Name</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[0] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Date of Birth</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[1] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">SIN</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[2] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Health Care #, Province</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[3] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Driver&#39;s License #</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[4] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Email</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[5] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Trade</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[6] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Address, City, Province, Postal Code</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[7] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Phone #</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[8] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Hire Date</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[9] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Certificates/ Training</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[10] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Allergies (including food and medications)</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[11] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Substance, Reaction, Remedy</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[12] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Specific Medical Problems (Diabetes, Epilepsy, Heart Condition)</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[13] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Regular Prescriptions</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[14] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Name, Dose, Frequency</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[15] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Emergency Contact</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[16] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Name, Relationship, Address, City, Phone #</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[17] . '</td>
				</tr>
				<tr>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">Family Doctor, phone #</td>
					<td style="border:1px solid black;border-collapse:collapse;width:50%">' . $fields[18] . '</td>
				</tr>
			</table><br><br>';


	$html .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">Information which I have provided here is true and correct. I have read and understand the content of this policy. <br>';

    $html .= 'Date : '.date('Y-m-d').'<br>';
    $html .= 'Person : '.decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);

    $html .= '<br> <img src="employee_personal_and_emergency_information/download/hr_'.$_SESSION['contactid'].'.png" width="150" height="70" border="0" alt="">';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('employee_personal_and_emergency_information/download/hr_'.$fieldlevelriskid.'.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">window.location.replace("?tile_name='.$tile.'");
    window.open("employee_personal_and_emergency_information/download/hr_'.$fieldlevelriskid.'.pdf", "fullscreen=yes");
    </script>';

    unlink("employee_personal_and_emergency_information/download/hr_".$_SESSION['contactid'].".png");
    echo '';
}
?>