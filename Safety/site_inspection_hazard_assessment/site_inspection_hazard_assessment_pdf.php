<?php
function site_inspection_hazard_assessment_pdf($dbc,$safetyid, $fieldlevelriskid) {
	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM safety_site_inspection_hazard_assessment WHERE fieldlevelriskid='$fieldlevelriskid'"));

	$tab = get_safety($dbc, $safetyid, 'tab');
    $form = get_safety($dbc, $safetyid, 'form');
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_safety WHERE tab='$tab' AND form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_safety WHERE tab='$tab' AND form='$form'"));

    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);
	DEFINE('PDF_HEADER', html_entity_decode($get_field_config['pdf_header']));
    DEFINE('PDF_FOOTER', html_entity_decode($get_field_config['pdf_footer']));
	$result_update_employee = mysqli_query($dbc, "UPDATE `safety_site_inspection_hazard_assessment` SET `status` = 'Done' WHERE fieldlevelriskid='$fieldlevelriskid'");

	$today_date = $get_field_level['today_date'];
    $project_name = $get_field_level['project_name'];
    $employer = $get_field_level['employer'];
    $project_number = $get_field_level['project_number'];
    $contactid = $get_field_level['contactid'];
	$purpose = $get_field_level['purpose'];
    $purpose_other = $get_field_level['purpose_other'];
    $fields_value = explode('**FFM**', $get_field_level['fields_value']);
    $field_rating = explode(',', $get_field_level['field_rating']);
    $fields = $get_field_level['fields'];
    $overall_rating = $get_field_level['overall_rating'];
    $additional_comment = $get_field_level['additional_comment'];
    $date_comp = $get_field_level['date_comp'];

	class MYPDF extends TCPDF {
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

	$html_weekly = '<h2>Site Inspection Hazard Assessment</h2>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Date/Time</th><th>Inspection By</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$today_date.'</td><td>'.$contactid.'</td></tr>';
    $html_weekly .= '</table>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Project Name</th><th>Employer</th><th>Project Number</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$project_name.'</td><td>'.$employer.'</td><td>'.$project_number.'</td></tr>';
    $html_weekly .= '</table>';

	$html_weekly .= '<table border="1px" style="padding:3px; border:1px solid black;">
            <tr nobr="true" style="background-color:lightgrey; color:black;  width:22%;">
            <th>Purpose</th></tr>';
    $html_weekly .= '<tr nobr="true"><td>'.$purpose.' '.$purpose_other.'</td></tr>';
    $html_weekly .= '</table>';

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th width="30%;">Items</th>
        <th width="10%;">Acceptable</th>
        <th width="12%;">Unacceptable</th>
        <th width="31%;">Activity, Concern, Recommendation Oraction Required</th>
        <th width="17%;">Rating</th>
        </tr>';

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Project Management Compliance</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[7].'</td>';
        $html_weekly .= '</tr>';
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">O.H.S. Committee Minutes</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[8].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields9".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Supervision</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[9].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields10".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Safe Work Practices & Procedures</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[10].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields11".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Training of Workers / Records</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[11].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields12".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Postings / Safety Manuals / OH&S Regulations</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[12].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields13".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Personal Protection Equipment</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[13].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields14".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Tool Box Meetings / Minutes</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[14].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields15".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Ventilation / Environmental Conditions</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[15].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields16".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Respiratory Protection</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[16].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields17".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Lighting</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[17].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields18".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Toilet, Washing, Drinking & Eating Facilities</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[18].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields19".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Security / Fire Prevention</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[19].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields20".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">First Aid / Emergency Protocols</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[20].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields21".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Equipment Condition / Maintenance</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[21].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields22".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Inspections</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[22].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields23".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Machine Guarding</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[23].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields24".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Signs / Barricades</td>';
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
        $html_weekly .= '<td data-title="Email">'.$field_rating[24].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields25".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Handling of Loads</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field25_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field25_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[25].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[25].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields26".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Access / Egress</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field26_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field26_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[26].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[26].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields27".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Chemical Substances / W.H.M.I.S. / M.S.D.S. / Hazard Comm. Program</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field27_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field27_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[27].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[27].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields28".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Scaffolds</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field28_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field28_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[28].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[28].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields29".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Fall Protection</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field29_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field29_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[29].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[29].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields30".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Noise Awareness / Protection</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field30_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field30_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[30].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[30].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields31".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Work Clothing (& Accommodation if applicable)</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field31_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field31_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[31].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[31].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields32".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Housekeeping</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field32_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field32_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[32].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[32].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields33".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hoisting & Rigging</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field33_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field33_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[33].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[33].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields34".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Accident Reporting / Investigations</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field34_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field34_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[34].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[34].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields35".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Fire Extinguishers</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field35_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field35_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[35].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[35].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields36".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">BBS</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field36_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field36_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[36].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[36].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields37".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Orientations</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field37_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field37_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[37].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[37].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields38".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Hazard Assessments</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field38_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field38_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[38].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[38].'</td>';
        $html_weekly .= '</tr>';
    }
    if (strpos($form_config, ','."fields39".',') !== FALSE) {
        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">Others</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field39_acceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">';
        if (strpos(','.$fields.',', ',field39_unacceptable,') !== FALSE) {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= '</td>';
        $html_weekly .= '<td data-title="Email">'.$fields_value[39].'</td>';
        $html_weekly .= '<td data-title="Email">'.$field_rating[39].'</td>';
        $html_weekly .= '</tr>';
    }


    $html_weekly .= '</table>';
//


	$html_weekly .= "<h3>Overall Rating</h3>".$overall_rating;
	$html_weekly .= "<h3>Additional Comment</h3>".html_entity_decode($additional_comment);
	$html_weekly .= "<h3>Date Completed</h3>".$date_comp;

	$sa = mysqli_query($dbc, "SELECT * FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");

    $html_weekly .= '<br><br><table border="1px" style="padding:3px; border:1px solid black;">';
    $html_weekly .= '<tr nobr="true" style="background-color:lightgrey; color:black;">
        <th>Name</th>
        <th>Signature</th>
        <th>Date</th>
        </tr>';

    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        $staffcheck = $row_sa['staffcheck'];

        $html_weekly .= '<tr nobr="true">';
        $html_weekly .= '<td data-title="Email">' . $row_sa['assign_staff'] . '</td>';

        $html_weekly .= '<td data-title="Email"><img src="site_inspection_hazard_assessment/download/safety_'.$assign_staff_id.'.png" width="150" height="70" border="0" alt=""></td>';
        $html_weekly .= '<td data-title="Email">'.$staffcheck.'</td>';
        $html_weekly .= '</tr>';
    }
    $html_weekly .= '</table>';

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('site_inspection_hazard_assessment/download/hazard_'.$fieldlevelriskid.'.pdf', 'F');

    $sa = mysqli_query($dbc, "SELECT safetyattid FROM safety_attendance WHERE fieldlevelriskid = '$fieldlevelriskid' AND safetyid='$safetyid'");
    while($row_sa = mysqli_fetch_array( $sa )) {
        $assign_staff_id = $row_sa['safetyattid'];
        unlink("site_inspection_hazard_assessment/download/safety_".$assign_staff_id.".png");
    }
    echo '';
}
?>








