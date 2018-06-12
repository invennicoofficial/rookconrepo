<?php
function whiplash_associated_disorders_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_whiplash_associated_disorders WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];
    $pain = explode(',', $get_field_level['pain']);

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

	$html_weekly = '<h2>Patient Check List for Whiplash Associated Disorders </h2>';

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Date:</b><br>".$today_date;
    }

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Patient:</b><br>".$patient;
    }

	$html_weekly .= "<br><br>This data check list is intended as a guide to the assessment and treatment of a whiplash patient/claimant with Grade I or Grade II WAD injuries. The checklist is not an exhaustive list and does not take into consideration any non-WAD injuries.

<br><br>
<b>HISTORY (PATIENT/CLAIMANT TO COMPLETE)</b><br><br>
Symptom Checklist<br>For each symptom, rate severity on a scale of 0 to 10 where indicated 0 is No Pain - and 10 is pain as bad as it could be.";

    if (strpos($form_config, ','."fields4".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Neck or shoulder pain</b> : ".$pain[0].' / 10';
    }

    if (strpos($form_config, ','."fields5".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Upper or Mid-back pain</b> : ".$pain[1].' / 10';
    }
    if (strpos($form_config, ','."fields6".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Low Back Pain</b> : ".$pain[2].' / 10';
    }

    if (strpos($form_config, ','."fields7".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Headache</b> : ".$pain[3].' / 10';
    }

    if (strpos($form_config, ','."fields8".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain in Arm(s)</b> : ".$pain[4].' / 10';
    }

    if (strpos($form_config, ','."fields9".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain in Hand(s)</b> : ".$pain[5].' / 10';
    }

    if (strpos($form_config, ','."fields10".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain in Face or Jaw</b> : ".$pain[6].' / 10';
    }

    if (strpos($form_config, ','."fields11".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain in Leg(s)</b> : ".$pain[7].' / 10';
    }

    if (strpos($form_config, ','."fields12".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain in Foot/Feet</b> : ".$pain[8].' / 10';
    }
    if (strpos($form_config, ','."fields13".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Pain in Abdomen or Chest</b> : ".$pain[9].' / 10';
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('whiplash_associated_disorders/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    echo '';
}
?>








