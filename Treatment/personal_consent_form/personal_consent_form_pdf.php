<?php
function personal_consent_form_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_personal_consent_form WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

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

	$html_weekly = '<h2>Personal Consent Form</h2>';

	$html_weekly .= "<br><br><br><br>We are committed to protecting the privacy of our patients' personal information and to utilizing all personal information in a responsible and professional manner. This
document summarizes some of the personal information that we collect. Use of personal information when permitted or required by law.
<br><br>
Contact and or medical information is disclosed to the Calgary Health Region, Motor Vehicle Insurance Companies, Workers Compensation Board, on behalf of the patient to discuss treatment. I hereby authorize ".get_config($dbc, 'company_name')." to release or obtain information to or from my insurance company, family member, employer, doctor medical records department, lawyer or representative regarding my ability to return to normal activity or work.
<br><br>
We collect information from our patients about their health history, family health history, physical condition, and previous physical therapy treatments. (Collectively referred to as Medical Information). Patients' Medical Information is collected and used for the purpose of diagnosing musculoskeletal conditions and providing physical therapy treatment.
<br><br>
Physical Therapists are regulated by the Alberta College of Physical Therapists of Alberta which may inspect our records and interview our staff as part of its regulatory activities in the public interest.";

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	$html_weekly .= "<br><br>I <b>".$patient."</b> Have read the <b>Patient Missed Appointment Policy and Personal consent form</b> and understand these policies.";
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	$html_weekly .= "<br><br><b>Date:</b><br>".$today_date;
    }
    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('personal_consent_form/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    echo '';
}
?>








