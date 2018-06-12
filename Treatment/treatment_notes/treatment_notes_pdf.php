<?php
function treatment_notes_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_treatment_notes WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

    $code = $get_field_level['code'];
    $doctor = $get_field_level['doctor'];
    $program = $get_field_level['program'];

    $work_status = $get_field_level['work_status'];
    $treatment_date = $get_field_level['treatment_date'];
    $physio = $get_field_level['physio'];
    $visit = $get_field_level['visit'];
    $desc = $get_field_level['desc'];

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

	$html_weekly = '<h2>Treatment Notes</h2>';

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Patient:</b><br>".$patient;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Date:</b><br>".$today_date;
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
        $html_weekly .= "<br><br><b>Diagnostic Code : </b>".$code;
    }

    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
    $html_weekly .= "<br><br><b>Referring Doctor : </b>".$doctor;

    }
    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
    $html_weekly .= "<br><br><b>Program : </b>".$program;
    }

    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= "<br><br><b>Work Status : </b>".$work_status;
    }

    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
        $html_weekly .= "<br><br><b>Treatment Date : </b>".$treatment_date;
    }

    if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
        $html_weekly .= "<br><br><b>Physio : </b>".$physio;
    }

    if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
        $html_weekly .= "<br><br><b>Visit # : </b>".$visit;
    }

    if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
        $html_weekly .= "<br><br><b>Treatment Description : </b><br>".html_entity_decode($desc);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('treatment_notes/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("treatment_notes/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>








