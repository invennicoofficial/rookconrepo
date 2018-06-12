<?php
	function equipment_inspection_checklist_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_equipment_inspection_checklist WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';
	$get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_equipment_inspection_checklist` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");
    $contactid = $get_field_level['contactid'];
	$today_date = $get_field_level['today_date'];
	$make = $get_field_level['make'];
	$vehicle_type = $get_field_level['vehicle_type'];
	$serial_number = $get_field_level['serial_number'];
	$model = $get_field_level['model'];
	$kilometers = $get_field_level['kilometers'];
	$fields = $get_field_level['fields'];
	$fields_value = explode('**FFM**', $get_field_level['fields_value']);

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
    $pdf->SetFont('helvetica', '', 9);

	$html_weekly = '<h2>Equipment Inspection Checklist</h2>'; // Form nu heading

	    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Make</th><th>Vehicle Type</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$make.'</td><td>'.$vehicle_type.'</td></tr>';
    $html_weekly .= '</table><br><br>';
	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Serial Number</th><th>Model</th><th>Kilometers</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$serial_number.'</td><td>'.$model.'</td><td>'.$kilometers.'</td></tr>';
    $html_weekly .= '</table><br><br>';
	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Inspected By</th></tr>';
	$html_weekly .= '<tr nobr="true"><td>'.$contactid.'</td></tr>';
    $html_weekly .= '</table><br><br>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;"><tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th></th><th>Good</th><th>Bad</th><th>N/A</th><th>Remarks</th></tr>';
	if (strpos($form_config, ','."fields8".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Engine oil and filter</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field8_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field8_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field8_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[8].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Antifreeze</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field9_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field9_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field9_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[9].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields10".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Battery and tie down</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field10_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field10_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field10_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[10].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields11".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Battery cables</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field11_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field11_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field11_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[11].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields12".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Fan belts and Hoses</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field12_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field12_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field12_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[12].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields13".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Air filter and piping</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field13_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field13_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field13_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[13].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields14".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Exhaust System</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field14_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field14_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field14_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[14].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields15".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Fuel Tank and Lines</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field15_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field15_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field15_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[15].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields16".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Tires, Wheels, Tracks</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field16_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field16_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field16_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[16].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields17".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Service Brakes</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field17_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field17_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field17_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[17].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields18".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Parking Brakes</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field18_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field18_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field18_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[18].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields19".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Seat Belt</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field19_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field19_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field19_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[19].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields20".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Gauges and Alarms</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field20_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field20_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field20_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[20].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields21".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Heater, def., and a/c</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field21_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field21_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field21_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[21].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields22".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Horn, Lights</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field22_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field22_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field22_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[22].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields23".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Signal Lights</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field23_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field23_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field23_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[23].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields24".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Beacon</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field24_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field24_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field24_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[24].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields25".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Back-up Alarm</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field25_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field25_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field25_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[25].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields26".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Glass</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field26_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field26_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field26_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[26].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields27".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Window Wipers</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field27_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field27_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field27_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[27].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields28".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Mirrors</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field28_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field28_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field28_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[28].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields29".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Doors and Latches</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field29_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field29_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field29_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[29].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields30".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Body and Frame</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field30_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field30_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field30_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[30].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields31".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Steering System</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field31_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field31_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field31_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[31].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields32".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Operators Manual</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field32_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field32_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field32_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[32].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields33".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Grab Handles and Steps</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field33_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field33_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field33_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[33].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields34".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hood and Latches</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field34_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field34_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field34_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[34].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields35".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hydraulic Oil Levels</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field35_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field35_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field35_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[35].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields36".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hydraulic Cylinders</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field36_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field36_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field36_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[36].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields37".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hydraulic Hoses</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field37_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field37_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field37_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[37].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields38".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hydraulic Cylinder Pins</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field38_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field38_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field38_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[38].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields39".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Chains, straps, tie downs</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field39_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field39_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field39_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[39].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields40".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Air Leaks</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field40_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field40_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field40_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[40].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields41".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Oil Leaks</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field41_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field41_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field41_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[41].'</td>';
        $html_weekly .= '</tr>';
    }

	if (strpos($form_config, ','."fields42".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Fire Extinguisher</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field42_good,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field42_bad,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field42_na,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';

        $html_weekly .= '<td data-title="Email">'.$fields_value[42].'</td>';
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

        $html_weekly .= '<td data-title="Email"><img src="equipment_inspection_checklist/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    // avs_near_miss = form name
    $pdf->Output('equipment_inspection_checklist/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        // avs_near_miss = form name
        unlink("equipment_inspection_checklist/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>




