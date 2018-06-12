<?php
function monthly_office_safety_inspection_pdf($dbc,$safetyid, $fieldlevelriskid) {
    $form_by = $_SESSION['contactid'];

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_monthly_office_safety_inspection WHERE fieldlevelriskid='$fieldlevelriskid'"));

    $tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_monthly_office_safety_inspection` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $contactid = $get_field_level['contactid'];
    $location = $get_field_level['location'];
    $fields = $get_field_level['fields'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
	$all_task = $get_field_level['all_task'];

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

	$html_weekly = '<h2>Monthly Office Safety Inspections</h2>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date</th><th>Location</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$location.'</td></tr>';
    $html_weekly .= '</table>';

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black; width:100%">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th style="width:35%;">Items to watch for</th>
        <th style="width:12%;">Acceptable</th>
        <th style="width:12%;">Unacceptable</th>
        <th style="width:41%;">Comments</th>
        </tr>';

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Water/sanitation rest room facilities</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field7_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field7_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[7].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Fire escapes (doors) clear of obstructions</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field8_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field8_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[8].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Housekeeping (snow and ice at entrance)</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field9_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field9_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[9].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields10".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Safety Training</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field10_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field10_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[10].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields11".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Fire Protection Equipment (inspection)</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field11_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field11_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[11].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields12".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">First Aid contents(topped up)</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field12_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field12_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[12].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields13".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Lighting</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field13_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field13_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[13].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields14".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Aisle, Work Surfaces (room to drive machine)</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field14_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field14_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[14].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields15".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Emergency Procesure/floor plan</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field15_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field15_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[15].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields16".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Vehicles</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field16_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field16_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[16].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields17".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Personal Protective Equipment</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field17_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field17_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[17].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields18".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Safe Work Practice</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field18_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field18_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[18].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields19".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Job Procedure</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field19_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field19_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[19].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields20".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Maintenance (records)</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field20_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field20_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[20].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields21".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Flammable Liquid, Gas, Lables</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field21_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field21_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[21].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields22".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Storage Facilities/Areas (clean,organized)</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field22_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field22_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[22].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields23".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Warning Sign/Labels</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field23_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field23_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[23].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields24".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Locker/Lunch Room</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field24_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field24_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[24].'</td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Hazards Observed</th>
        <th>Priority</th>
        <th>Corrective Actions</th>
        <th>Date Complete</th>
        <th>By Whom</th>
        </tr>
    ';

    $all_task_each = explode('**##**',$all_task);

    $total_count = mb_substr_count($all_task,'**##**');
    if($total_count == 0) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">&nbsp;&nbsp;</td>';
        $html_weekly .= '<td data-title="Email">&nbsp;&nbsp;</td>';
        $html_weekly .= '<td data-title="Email">&nbsp;&nbsp;</td>';
        $html_weekly .= '<td data-title="Email">&nbsp;&nbsp;</td>';
        $html_weekly .= '</tr>';
    }
    for($client_loop=0; $client_loop<=$total_count; $client_loop++) {
        $task_item = explode('**',$all_task_each[$client_loop]);
        $task = $task_item[0];
        $hazard = $task_item[1];
        $level = $task_item[2];
        $level2 = $task_item[3];
        $level3 = $task_item[4];
        if($task != '') {
            $html_weekly .= '<tr nobr="true">';
            $html_weekly .= '<td data-title="Email">' . $task . '</td>';
            $html_weekly .= '<td data-title="Email">' . $hazard . '</td>';
            $html_weekly .= '<td data-title="Email">' . $level . '</td>';
            $html_weekly .= '<td data-title="Email">' . $level2 . '</td>';
            $html_weekly .= '<td data-title="Email">' . $level3 . '</td>';
            $html_weekly .= '</tr>';
        }
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

        $html_weekly .= '<td data-title="Email"><img src="monthly_office_safety_inspection/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('monthly_office_safety_inspection/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];

        unlink("monthly_office_safety_inspection/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>