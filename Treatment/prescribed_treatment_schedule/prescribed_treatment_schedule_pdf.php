<?php
function prescribed_treatment_schedule_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_prescribed_treatment_schedule WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

    $therapist = $get_field_level['therapist'];
    $reev_date = $get_field_level['reev_date'];
    $surgery_date = $get_field_level['surgery_date'];

    $initial_treatment = $get_field_level['initial_treatment'];
    $reev_treatment = $get_field_level['reev_treatment'];
    $xrays = $get_field_level['xrays'];
    $mri_us = $get_field_level['mri_us'];

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

	$html_weekly = "<h2>Prescribed Treatment Schedule</h2><br><b>Please pre-book there appointments with our receptionist prior to leaving today.</b><br><br>
    ".$therapist." will be monitoring ".$patient." closely, should the frequency of treatments need to be altered ".$therapist." will let you know. ".$therapist." 's schedule fills up quickly so please book all your appointments today to ensure there is no interruption in your treatment plan. This will also help you put your healing first when scheduling personal commitments. Thank you for choosing our clinic. ".$therapist." is looking forward to working with ".$patient.".";

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	    $html_weekly .= "<br><br><b>Patient : </b><br>".$patient;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	    $html_weekly .= "<br><br><b>Date : </b><br>".$today_date;
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
        $html_weekly .= "<br><br><b>Therapist : </b><br>".$therapist;
    }

    $html_weekly .= "<br><h3>Initital Personal Treatment Plan</h3>";
    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
        $html_weekly .= "<br><br><b>Initial Treatment Schedule:</b><br>".html_entity_decode($initial_treatment);

    }

    $html_weekly .= "<br><h3>Re-Evaluation</h3>";
    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
    $html_weekly .= "<br><br><b>Re-Evaluation Date:</b><br>".$reev_date;
    }

    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= "<br><br><b>Re-Evaluation Treatment Schedule:</b><br>".html_entity_decode($reev_treatment);
    }

    $html_weekly .= "<br><h3>Required Dates & Description</h3>";
    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
        $html_weekly .= "<br><br><b>Surgery Date:</b><br>".$surgery_date;
    }

    if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
        $html_weekly .= "<br><br><b>X-Rays:</b><br>".html_entity_decode($xrays);
    }

    if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
        $html_weekly .= "<br><br><b>MRI/US:</b><br>".html_entity_decode($mri_us);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('prescribed_treatment_schedule/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("prescribed_treatment_schedule/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>








