<?php
function progress_report_pdf($dbc,$patientformid, $fieldlevelriskid) {

    $form = get_patientform($dbc, $patientformid, 'form');

    $get_pdf_logo = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT pdf_logo FROM field_config_patientform WHERE form='$form'"));
    DEFINE('PDF_LOGO', $get_pdf_logo['pdf_logo']);

	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_patientform WHERE form='$form'"));
    $form_config = ','.$get_field_config['fields'].',';

	$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM patientform_progress_report WHERE fieldlevelriskid='$fieldlevelriskid'"));
	$today_date = $get_field_level['today_date'];
	$fields_value = explode('**FFM**', $get_field_level['fields_value']);

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

	$html_weekly = '<h2>Treatment Plan</h2>';

if (strpos(','.$form_config.',', ',fields1,') !== FALSE) {
$html_weekly .= "<h4>Send this form to the appropriate insurer: </h4>
Fax # : ".$fields_value[1];
}

$html_weekly .= "<h4>To be completed by Claimant / Representative or a Primary Health Care Practitioner</h4><br><br>";
if (strpos(','.$form_config.',', ',fields2,') !== FALSE) {
$html_weekly .= "Insurance Company : ".get_all_form_contact($dbc, $fields_value[2], 'name')."<br><br>";
}
if (strpos(','.$form_config.',', ',fields3,') !== FALSE) {
$html_weekly .= "Policy Number : ".$fields_value[3]."<br><br>";
}
if (strpos(','.$form_config.',', ',fields4,') !== FALSE) {
$html_weekly .= "Date of Accident : ".$fields_value[4];
}

//2
$html_weekly .= "<h4>Part 1 - Claimant Information</h4><br><br>";
if (strpos(','.$form_config.',', ',fields5,') !== FALSE) {
$html_weekly .= "Name : ".get_contact($dbc, $fields_value[5])."<br><br>";
$html_weekly .= "Date of Birth : ".get_all_form_contact($dbc, $fields_value[5], 'birth_date')."<br><br>";
}
if (strpos(','.$form_config.',', ',fields6,') !== FALSE) {
$html_weekly .= "Date of Initial Assessment : ".$fields_value[6]."<br><br>";
}
$html_weekly .= "<h4>Part 2 - Information of Primary Health Care Practitioner</h4><br><br>";
if (strpos(','.$form_config.',', ',fields7,') !== FALSE) {
$html_weekly .= "Name of Professional : ".get_contact($dbc, $fields_value[7])."<br><br>";
}
if (strpos(','.$form_config.',', ',fields8,') !== FALSE) {
$html_weekly .= "Profession : ".$fields_value[8]."<br><br>";
}
if (strpos(','.$form_config.',', ',fields9,') !== FALSE) {
$html_weekly .= "Address : ".$fields_value[9]."<br><br>";
$html_weekly .= "City, Town or County : ".$fields_value[10]."<br><br>";
$html_weekly .= "Province : ".$fields_value[11]."<br><br>";
$html_weekly .= "Postal Code : ".$fields_value[12]."<br><br>";
}
if (strpos(','.$form_config.',', ',fields10,') !== FALSE) {
$html_weekly .= "Administrative Contact Name : ".$fields_value[13]."<br><br>";
}
if (strpos(','.$form_config.',', ',fields11,') !== FALSE) {
$html_weekly .= "Facility Name : ".$fields_value[14]."<br><br>";
}
if (strpos(','.$form_config.',', ',fields12,') !== FALSE) {
$html_weekly .= "Telephone Number (Include area code)  : ".$fields_value[15]."<br><br>";
}
if (strpos(','.$form_config.',', ',fields13,') !== FALSE) {
$html_weekly .= "Fax Number (Include area code) : ".$fields_value[16]."<br><br>";
}

//3
$html_weekly .= "<h4>Part 3 - Therapy Status Report (To be completed by Primary Health Care Practitioner)</h4><br><br>";
if (strpos(','.$form_config.',', ',fields14,') !== FALSE) {
$html_weekly .= "Diagnosis WAD  : ".html_entity_decode($fields_value[17])."<br><br>";
}
if (strpos(','.$form_config.',', ',fields15,') !== FALSE) {
$html_weekly .= "Key Subjective/Physical Examination Findings  : ".html_entity_decode($fields_value[18])."<br><br>";
}
if (strpos(','.$form_config.',', ',fields16,') !== FALSE) {
$html_weekly .= "C/O   : ".html_entity_decode($fields_value[19])."<br><br>";
}
if (strpos(','.$form_config.',', ',fields17,') !== FALSE) {
$html_weekly .= "O/E  : ".html_entity_decode($fields_value[20])."<br><br>";
}
if (strpos(','.$form_config.',', ',fields18,') !== FALSE) {
$html_weekly .= "<h4>Functional Goals : </h4>
1.ROM : ".$fields_value[21]."
<br>
2.Strength : ".$fields_value[22]."
<br>
3.Endurance : ".$fields_value[23]."<br><br>";
}

if (strpos(','.$form_config.',', ',fields19,') !== FALSE) {
$html_weekly .= "Progress towards goals: <br>".$fields_value[24]."<br>".$fields_value[25]."<br>".$fields_value[26]."<br>".$fields_value[27]."<br>".$fields_value[28]."<br>".$fields_value[29]."<br>".$fields_value[30]."<br><br>";
}

$html_weekly .= "<h4>Part 6 - Signature of Primary Health Care Practitioner</h4><br><br>";
if (strpos(','.$form_config.',', ',fields20,') !== FALSE) {
$html_weekly .= "Name (Please Print) : ".get_contact($dbc, $fields_value[31]);
}
if (strpos(','.$form_config.',', ',fields21,') !== FALSE) {
$html_weekly .= '<br><img src="progress_report/download/sign_'.$fieldlevelriskid.'.png" width="150" height="70" border="0" alt=""><br><br>';
}
if (strpos(','.$form_config.',', ',fields22,') !== FALSE) {
$html_weekly .= "Date : ".$fields_value[32]."<br><br>";
}
//

if (strpos(','.$form_config.',', ',fields23,') !== FALSE) {
$html_weekly .= "Treatment Plan : ".html_entity_decode($fields_value[33])."<br><br>";
}

$pdf->writeHTML($html_weekly, true, false, true, false, '');

$pdf->Output('progress_report/download/patientform_'.$fieldlevelriskid.'.pdf', 'F');

echo '';
}
?>