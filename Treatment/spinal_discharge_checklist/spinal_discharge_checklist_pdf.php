<?php
function spinal_discharge_checklist_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_spinal_discharge_checklist WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

    $rom = $get_field_level['rom'];
    $chin = $get_field_level['chin'];
    $prone = $get_field_level['prone'];

    $isometric_0 = $get_field_level['isometric_0'];
    $neck_strap = $get_field_level['neck_strap'];
    $body_parts = $get_field_level['body_parts'];
    $ndi = $get_field_level['ndi'];
    $psfs = $get_field_level['psfs'];
    $roland = $get_field_level['roland'];
    $pain_0 = $get_field_level['pain_0'];

    $goals = $get_field_level['goals'];
    $independence = $get_field_level['independence'];
    $testimonial = $get_field_level['testimonial'];

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

	$html_weekly = '<h2>Spinal Discharge Criteria</h2>';

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Patient:</b><br>".$patient;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Date:</b><br>".$today_date;
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
        $html_weekly .= "<br><br>";
        if($rom == 'rom') {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= "&nbsp;<b>ROM</b> - Ideally full to 90%";
    }
    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
    $html_weekly .= "<p><b>Strength and endurance : </b></p><br>
     - Chin Tuck Head Lift = 89 seconds Female; 2:30 for Male : ".$chin."<br>
     - Prone Plank = 1 min for Females; 2 min for Males : ".$prone;

    }
    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
    $html_weekly .= "<br><br><b>Resisted isometric at : </b> 0,45,90 degrees Gr : ".$isometric_0.' / 5';
    }

    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= "<br><br>";
        if($neck_strap == 'neck_strap') {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= "&nbsp;Able to do 3 sets of reps on Neck strap work: Red to green tubing.";
    }

    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
        $html_weekly .= "<br><br>";
        if($body_parts == 'body_parts') {
            $html_weekly .= '<img src="../img/checkmark.png" width="10" height="10" border="0" alt="">';
        }
        $html_weekly .= "&nbsp;Other body parts; 80% of normal strength";
    }

    if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
        $html_weekly .= "<br><br><b>Outcome measures</b><br>
        - NDI 10/50, (5/50 indicates no disability) : ".$ndi."<br>
        - PSFS 5/30 : ".$psfs."<br>
        - Roland Morris 5/24 : ".$roland;
    }

    if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
    $html_weekly .= "<br><br><b>Pain : </b> Visual Analog Scale = 2 or less than 10 : ".$pain_0.' / 10';
    }

    if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
    $html_weekly .= "<br><br><b>Client goals met? : </b><br>".html_entity_decode($goals);
    }

    if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
    $html_weekly .= "<br><br><b>Independence : </b><br>".html_entity_decode($independence);
    }

    if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
    $html_weekly .= "<br><br><b>Testimonial : </b><br>".html_entity_decode($testimonial);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('spinal_discharge_checklist/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("spinal_discharge_checklist/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>








