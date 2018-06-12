<?php
	function employee_equipment_training_record_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_employee_equipment_training_record WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_employee_equipment_training_record` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
	$hire_date = $get_field_level['hire_date'];
	$fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $fields_date = explode(',', $get_field_level['fields_date']);

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

	$html_weekly = '<h2>Employee Equipment Training Record</h2>'; // Form nu heading

	    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Hire Date</th><th>Employee Name</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$hire_date.'</td><td>'.$contactid.'</td></tr>';
    $html_weekly .= '</table>';

	$html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;"><tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th width="20%;">Machine Type</th><th width="12%;">Date</th><th width="13%;">Competent</th><th width="13%;">Noncompetent</th><th width="42%;">INIT</th></tr>';

	if (strpos($form_config, ','."fields4".',') !== FALSE) {

		// Niche ni 3 line heading ma j lakhvi. subheading na hoy to na lakhvi.

		$html_weekly .= '<tr nobr="true">';
		$html_weekly .= '<td colspan="5"><h3>Excavator</h3></td>';
		$html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hoe Pack</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[1].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field1_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field1_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[1].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields4".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Breaker</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[2].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field2_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field2_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[2].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields5".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Grader</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[3].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field3_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field3_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[3].'</td>';

        $html_weekly .= '</tr>';
    }


 ////////////////////////////////

 if (strpos($form_config, ','."fields6".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
		$html_weekly .= '<td colspan="5"><h3>Loaders</h3></td>';
		$html_weekly .= '</tr>';

		$html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Track Loader</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[4].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field4_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field4_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[4].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields6".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Wheel Loader</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[5].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field5_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field5_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[5].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields6".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Skid Steer</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[6].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field6_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field6_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[6].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields7".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Water Truck</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[7].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field7_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field7_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[7].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields8".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Tractor</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[8].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field8_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field8_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[8].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields9".',') !== FALSE) {

		$html_weekly .= '<tr nobr="true">';
		$html_weekly .= '<td colspan="5"><h3>Trailers</h3></td>';
		$html_weekly .= '</tr>';

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Low Boy</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[9].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field9_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field9_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[9].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields9".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Jeep</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[10].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field10_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field10_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[10].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields9".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Tandem Dump</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[11].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field11_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field11_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[11].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields9".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hyd 8 Wheel</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[12].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field12_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field12_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[12].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields10".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Dozer</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[13].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field13_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field13_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[13].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields11".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Roller</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[14].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field14_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field14_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[14].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields12".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Overhead Crane</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[15].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field15_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field15_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[15].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields13".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Lathe</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[16].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field16_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field16_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[16].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields14".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Grinders</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[17].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field17_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field17_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[17].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields15".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Drills</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[18].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field18_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field18_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[18].'</td>';

        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields16".',') !== FALSE) {

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Concrete Cutter</td>';
		$html_weekly .= '<td data-title="Email">'.$fields_date[19].'</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field19_competent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field19_noncompetent,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[19].'</td>';

        $html_weekly .= '</tr>';
    }

    $html_weekly .= '</table>';

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

        $html_weekly .= '<td data-title="Email"><img src="employee_equipment_training_record/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('employee_equipment_training_record/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("employee_equipment_training_record/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




