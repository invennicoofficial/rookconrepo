<?php
function wcb_provider_employer_contact_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_field_config['pdf_logo']);
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_wcb_provider_employer_contact WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$patient = $get_field_level['patient'];

    $wcb = $get_field_level['wcb'];
    $health_card = $get_field_level['health_card'];
    $surname = $get_field_level['surname'];
    $first_name = $get_field_level['first_name'];
    $birth_date = $get_field_level['birth_date'];
    $employer = $get_field_level['employer'];
    $person_contacted = $get_field_level['person_contacted'];
    $supervisor = $get_field_level['supervisor'];
    $phone_number = $get_field_level['phone_number'];
    $date_contacted = $get_field_level['date_contacted'];
    $modified = $get_field_level['modified'];
    $alternate = $get_field_level['alternate'];
    $return = $get_field_level['return'];
    $physical = $get_field_level['physical'];
    $requested = $get_field_level['requested'];
    $goals = $get_field_level['goals'];

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

	$html_weekly = '<h2>WCB Provider Employed Contact</h2>';

    if (strpos($form_config, ','."fields2".',') !== FALSE) {
	    $html_weekly .= "<br><br><b>Patient : </b><br>".$patient;
    }

    if (strpos($form_config, ','."fields3".',') !== FALSE) {
	    $html_weekly .= "<br><br><b>Date : </b><br>".$today_date;
    }

    if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
        $html_weekly .= "<br><br><b>WCB Claim Number : </b>".$wcb;
    }
    if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
        $html_weekly .= "<br><br><b>Personal Health Care Number : </b>".$health_card;
    }
    if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
        $html_weekly .= "<br><br><b>Worker's Surname : </b>".$surname;
    }

    if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
        $html_weekly .= "<br><br><b>First Name : </b>".$first_name;
    }

    if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
        $html_weekly .= "<br><br><b>Date of Birth : </b>".$birth_date;
    }

    $html_weekly .= "<br><h3>Employer Contact Information</h3>";

    if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
        $html_weekly .= "<br><b>Employer Name : </b>".$employer;
    }

    if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
    $html_weekly .= "<br><br><b>Person Contacted : </b>".$person_contacted;
    }

    if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
    $html_weekly .= "<br><br><b>Supervisor : </b>".$supervisor;
    }

    if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
    $html_weekly .= "<br><br><b>Phone Number : </b>".$phone_number;
    }

    if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
    $html_weekly .= "<br><br><b>Date Contacted : </b>".$date_contacted;
    }

    if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
    $html_weekly .= "<br><br><b>Are modified duties available? : </b>".$modified;
    }

    if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
    $html_weekly .= "<br><br><b>Alternate duties available? : </b>".$alternate;
    }

    if (strpos(','.$form_config.',', ',fields16,') !== FALSE) {
    $html_weekly .= "<br><br><b>Is a return to work schedule available? : </b>".$return;
    }

    if (strpos(','.$form_config.',', ',fields17,') !== FALSE) {
    $html_weekly .= "<br><br><b>Is a physical Job demand analysis available?  : </b>".$physical;
    }

    if (strpos(','.$form_config.',', ',fields18,') !== FALSE) {
    $html_weekly .= "<br><br><b>Has it been requested? : </b>".$requested;
    }

    if (strpos(','.$form_config.',', ',fields19,') !== FALSE) {
    $html_weekly .= "<br><br><b>Additional Comments : </b><br>".html_entity_decode($goals);
    }

    $pdf->writeHTML($html_weekly, true, false, true, false, '');

    $pdf->Output('wcb_provider_employer_contact/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

    unlink("wcb_provider_employer_contact/download/sign_".$fieldlevelriskid.".png");
    echo '';
}
?>








