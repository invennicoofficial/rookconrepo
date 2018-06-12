<?php
function patient_functional_scale_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_patient_functional_scale WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

    $activity_1 = $get_field_level['activity_1'];
    $activity_2 = $get_field_level['activity_2'];
    $activity_3 = $get_field_level['activity_3'];

    $pain_0 = $get_field_level['pain_0'];
    $pain_1 = $get_field_level['pain_1'];
    $pain_2 = $get_field_level['pain_2'];

    $total_score = $get_field_level['total_score'];
    $mean_score = $get_field_level['mean_score'];

	class MYPDF extends TCPDF {
        public function Header() {
            if(PDF_LOGO != '') {
                $image_file = 'download/'.PDF_LOGO;
                $this->Image($image_file, 10, 10, 60, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
            $this->SetFont('helvetica', '', 8);
            $header_text = '';
            $this->writeHTMLCell(0, 0, '', '', $header_text, 0, 0, false, "L", "R",true);

        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
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
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

	$html_weekly = '<h2>Medical History Form</h2>';

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Patient:</b><br>".$patient;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Date:</b><br>".$today_date;
    }

    $html_weekly .= "<br><br>3 functional activities patient having trouble with because of your injury or condition.
    <br>On a scale of 0-10 list the amount of trouble patient have with each activity.
    <br>";

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
    $html_weekly .= "<br><br><b>Activity 1:</b><br>".$activity_1.' : '.$pain_0.' / 10';
    }
    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
    $html_weekly .= "<br><br><b>Activity 2:</b><br>".$activity_2.' : '.$pain_1.' / 10';
    }
    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
    $html_weekly .= "<br><br><b>Activity 3:</b><br>".$activity_3.' : '.$pain_2.' / 10';
    }

    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
    $html_weekly .= "<br><br><b>Total Score:</b><br>".$total_score.' / 30';
    }
    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
    $html_weekly .= "<br><br><b>Mean Score:</b><br>".$mean_score.' / 10';
    }

    if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
        $html_weekly .= '<br><br><img src="patient_functional_scale/download/sign_'.$fieldlevelriskid.'.png" width="150" height="70" border="0" alt="">';
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('patient_functional_scale/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("patient_functional_scale/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>








