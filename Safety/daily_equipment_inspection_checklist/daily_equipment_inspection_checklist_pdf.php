<?php
function daily_equipment_inspection_checklist_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

    $get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_daily_equipment_inspection_checklist WHERE fieldlevelriskid='$fieldlevelriskid'"));

    $tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
    $result_update_employee = mysqli_query($dbc, "UPDATE `safety_daily_equipment_inspection_checklist` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

    $today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $company = $get_field_level['company'];
    $inspection_time = $get_field_level['inspection_time'];
    $job_number = $get_field_level['job_number'];
    $model = $get_field_level['model'];
    $type = $get_field_level['type'];
    $check = $get_field_level['check'];
    $equ_unit = $get_field_level['equ_unit'];
    $odometer = $get_field_level['odometer'];
    $trip_type = $get_field_level['trip_type'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $remarks = $get_field_level['remarks'];
    $defect_status= $get_field_level['defect_status'];

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

    $html_weekly = '<h2>Daily Equipment Inspection Checklist</h2>';

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Inspection Date</th><th>Company</th><th>Inspection Time</th><th>Job#</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$company.'</td><td>'.$inspection_time.'</td><td>'.$job_number.'</td></tr>';
    $html_weekly .= '</table>';

    $html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Model</th><th>Type of Equipment</th><th>Check Item if ok</th><th>Eqipment unit#</th><th>Odometer-hours</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$model.'</td><td>'.$type.'</td><td>'.$check.'</td><td>'.$equ_unit.'</td><td>'.$odometer.'</td></tr>';
    $html_weekly .= '</table><br><br>';

    $html_weekly .= '<h3>Trip Type : '.$trip_type.'</h3>';

    $html_weekly .= '<h3>Equipment Check</h3>';

    if (strpos($form_config, ','."fields12".',') !== FALSE) {
        $html_weekly .= '<br><br>Oil';
        if (strpos(','.$fields.',', ','."fields12".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[12];
    }

    if (strpos(','.$form_config.',', ',fields13,') !== FALSE){
        $html_weekly .= '<br><br>Coolant-Red';
        if (strpos(','.$fields.',', ','."fields13".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[13];
    }

    if (strpos(','.$form_config.',', ',fields14,') !== FALSE){
        $html_weekly .= '<br><br>Collant Overflow';
        if (strpos(','.$fields.',', ','."fields14".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[14];
    }

    if (strpos(','.$form_config.',', ',fields15,') !== FALSE){
        $html_weekly .= '<br><br>Hyadrulic Oil';
        if (strpos(','.$fields.',', ','."fields15".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[15];
    }

    if (strpos(','.$form_config.',', ',fields16,') !== FALSE){
        $html_weekly .= '<br><br>Hydraulic Oil - Leaks';
        if (strpos(','.$fields.',', ','."fields16".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[16];
    }

    if (strpos(','.$form_config.',', ',fields17,') !== FALSE){
        $html_weekly .= '<br><br>Transmission Oil';
        if (strpos(','.$fields.',', ','."fields17".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[17];
    }

    if (strpos(','.$form_config.',', ',fields18,') !== FALSE){
        $html_weekly .= '<br><br>Air Filter';
        if (strpos(','.$fields.',', ','."fields18".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[18];
    }

    if (strpos(','.$form_config.',', ',fields19,') !== FALSE){
        $html_weekly .= '<br><br>Belts';
        if (strpos(','.$fields.',', ','."fields19".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[19];
    }

    if (strpos(','.$form_config.',', ',fields20,') !== FALSE){
        $html_weekly .= '<br><br>Track SAG';
        if (strpos(','.$fields.',', ','."fields20".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[20];
    }

    if (strpos(','.$form_config.',', ',fields21,') !== FALSE){
        $html_weekly .= '<br><br>Brake, Emergency';
        if (strpos(','.$fields.',', ','."fields21".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[21];
    }

    if (strpos(','.$form_config.',', ',fields22,') !== FALSE){
        $html_weekly .= '<br><br>Planetaries';
        if (strpos(','.$fields.',', ','."fields22".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[22];
    }

    if (strpos(','.$form_config.',', ',fields23,') !== FALSE){
        $html_weekly .= '<br><br>Break Pedal';
        if (strpos(','.$fields.',', ','."fields23".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[23];
    }

    if (strpos(','.$form_config.',', ',fields24,') !== FALSE){
        $html_weekly .= '<br><br>Hydraulic Break Fluid';
        if (strpos(','.$fields.',', ','."fields24".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[24];
    }

    if (strpos(','.$form_config.',', ',fields25,') !== FALSE){
        $html_weekly .= '<br><br>Parking Break';
        if (strpos(','.$fields.',', ','."fields25".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[25];
    }

    if (strpos(','.$form_config.',', ',fields26,') !== FALSE){
        $html_weekly .= '<br><br>Defroster and Heaters';
        if (strpos(','.$fields.',', ','."fields26".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[26];
    }

    if (strpos(','.$form_config.',', ',fields27,') !== FALSE){
        $html_weekly .= '<br><br>Emergency Equipment';
        if (strpos(','.$fields.',', ','."fields27".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[27];
    }

    if (strpos(','.$form_config.',', ',fields28,') !== FALSE){
        $html_weekly .= '<br><br>Engine';
        if (strpos(','.$fields.',', ','."fields28".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[28];
    }

    if (strpos(','.$form_config.',', ',fields29,') !== FALSE){
        $html_weekly .= '<br><br>Exhaust System';
        if (strpos(','.$fields.',', ','."fields29".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[29];
    }

    if (strpos(','.$form_config.',', ',fields30,') !== FALSE){
        $html_weekly .= '<br><br>Fire Extinguisher';
        if (strpos(','.$fields.',', ','."fields30".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[30];
    }

    if (strpos(','.$form_config.',', ',fields31,') !== FALSE){
        $html_weekly .= '<br><br>Fuel System';
        if (strpos(','.$fields.',', ','."fields31".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[31];
    }

    if (strpos(','.$form_config.',', ',fields32,') !== FALSE){
        $html_weekly .= '<br><br>Generator/Alternator';
        if (strpos(','.$fields.',', ','."fields32".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[32];
    }

    if (strpos(','.$form_config.',', ',fields33,') !== FALSE){
        $html_weekly .= '<br><br>Horn';
        if (strpos(','.$fields.',', ','."fields33".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[33];
    }

    if (strpos(','.$form_config.',', ',fields34,') !== FALSE){
        $html_weekly .= '<br><br>Lights and Reflectors';
        if (strpos(','.$fields.',', ','."fields34".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[34];
    }

    if (strpos(','.$form_config.',', ',fields35,') !== FALSE){
        $html_weekly .= '<br><br>Head-Stoplights';
        if (strpos(','.$fields.',', ','."fields35".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[35];
    }

    if (strpos(','.$form_config.',', ',fields36,') !== FALSE){
        $html_weekly .= '<br><br>Tail-Dash Lights';
        if (strpos(','.$fields.',', ','."fields36".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[36];
    }

    if (strpos(','.$form_config.',', ',fields37,') !== FALSE){
        $html_weekly .= '<br><br>Blade';
        if (strpos(','.$fields.',', ','."fields37".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[37];
    }

    if (strpos(','.$form_config.',', ',fields38,') !== FALSE){
        $html_weekly .= '<br><br>Bucket';
        if (strpos(','.$fields.',', ','."fields38".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[38];
    }

    if (strpos(','.$form_config.',', ',fields39,') !== FALSE){
        $html_weekly .= '<br><br>Body Damage';
        if (strpos(','.$fields.',', ','."fields39".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[39];
    }

    if (strpos(','.$form_config.',', ',fields40,') !== FALSE){
        $html_weekly .= '<br><br>Doors';
        if (strpos(','.$fields.',', ','."fields40".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[40];
    }

    if (strpos(','.$form_config.',', ',fields41,') !== FALSE){
        $html_weekly .= '<br><br>Mirrors (Adjustment and Condition)';
        if (strpos(','.$fields.',', ','."fields41".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[41];
    }

    if (strpos(','.$form_config.',', ',fields42,') !== FALSE){
        $html_weekly .= '<br><br>Oil Pressure';
        if (strpos(','.$fields.',', ','."fields42".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[42];
    }

    if (strpos(','.$form_config.',', ',fields43,') !== FALSE){
        $html_weekly .= '<br><br>Radiator';
        if (strpos(','.$fields.',', ','."fields43".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[43];
    }

    if (strpos(','.$form_config.',', ',fields44,') !== FALSE){
        $html_weekly .= "<br>Driver's Sheat belt and Seat Security";
        if (strpos(','.$fields.',', ','."fields44".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[44];
    }

    if (strpos(','.$form_config.',', ',fields45,') !== FALSE){
        $html_weekly .= '<br><br>Cutting edges';
        if (strpos(','.$fields.',', ','."fields45".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[45];
    }

    if (strpos(','.$form_config.',', ',fields46,') !== FALSE){
        $html_weekly .= '<br><br>Ripper Teeth';
        if (strpos(','.$fields.',', ','."fields46".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[46];
    }

    if (strpos(','.$form_config.',', ',fields47,') !== FALSE){
        $html_weekly .= '<br><br>Towing And Coupling Devices';
        if (strpos(','.$fields.',', ','."fields47".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[47];
    }

    if (strpos(','.$form_config.',', ',fields48,') !== FALSE){
        $html_weekly .= '<br><br>Windshield and Windows';
        if (strpos(','.$fields.',', ','."fields48".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[48];
    }

    if (strpos(','.$form_config.',', ',fields49,') !== FALSE){
        $html_weekly .= '<br><br>Windshield Washer and Wipers';
        if (strpos(','.$fields.',', ','."fields49".',') !== FALSE){
            $html_weekly .= '&nbsp;&nbsp;<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '&nbsp;&nbsp;'.$fields_value[49];
    }

    $html_weekly .= "<h3>Remarks</h3>".html_entity_decode($remarks);
    $html_weekly .= "<h3>Defect Status</h3>  ".$defect_status;

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
        $html_weekly .= '<td data-title="Email"><img src="daily_equipment_inspection_checklist/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');
    $pdf->Output('daily_equipment_inspection_checklist/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        unlink("daily_equipment_inspection_checklist/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>