<?php
include ('../include.php');
require_once('../fpdf181/fpdf.php');
require_once('../FPDI-1.6.1/fpdi.php');

$pdf = new FPDI();

$pdf->setSourceFile("AB_MVA_01.pdf");

$tplIdx = $pdf->importPage(1, '/MediaBox');
$pdf->addPage();
$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

$tplIdx = $pdf->importPage(2, '/MediaBox');
$pdf->addPage();
$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

// Page 3
$tplIdx = $pdf->importPage(3, '/MediaBox');
$pdf->addPage();
$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

$pdf->SetFont('helvetica', '', 8);

$pdf->SetTextColor(0, 0, 0);

$patientid = $_GET['patientid'];
$last_name = get_all_form_contact($dbc, $patientid, 'last_name');
$first_name = get_all_form_contact($dbc, $patientid, 'first_name');
$business_street = get_all_form_contact($dbc, $patientid, 'business_street');
$business_city = get_all_form_contact($dbc, $patientid, 'business_city');
$business_state = get_all_form_contact($dbc, $patientid, 'business_state');
$business_country = get_all_form_contact($dbc, $patientid, 'business_country');
$business_zip = get_all_form_contact($dbc, $patientid, 'business_zip');
$home_phone = get_all_form_contact($dbc, $patientid, 'home_phone');
$cell_phone = get_all_form_contact($dbc, $patientid, 'cell_phone');
$fax = get_all_form_contact($dbc, $patientid, 'fax');
$birth_date = get_all_form_contact($dbc, $patientid, 'birth_date');
$gender = get_all_form_contact($dbc, $patientid, 'gender');

$pdf->SetXY(12, 94);
$pdf->Write(0,$last_name);

$pdf->SetXY(84, 94);
$pdf->Write(0,$first_name);

//$pdf->SetXY(154, 94);
//$pdf->Write(0,$birth_date);

$pdf->SetXY(12, 101);
$pdf->Write(0,$business_street);

$pdf->SetXY(12, 109);
$pdf->Write(0,$business_city);

$pdf->SetXY(40, 109);
$pdf->Write(0,$business_country);

$pdf->SetXY(110, 109);
$pdf->Write(0,$business_state);

$pdf->SetXY(160, 109);
$pdf->Write(0,$business_zip);

$pdf->SetXY(12, 117);
$pdf->Write(0,$home_phone);

$pdf->SetXY(78, 117);
$pdf->Write(0,$cell_phone);

$pdf->SetXY(143, 117);
$pdf->Write(0,$fax);

$pdf->SetXY(12, 125);
$pdf->Write(0,$birth_date);

if($gender == 'Male') {
    $pdf->SetXY(52.5, 125);
    $pdf->Write(0,'X');
} else if($gender == 'Female') {
    $pdf->SetXY(65, 125.5);
    $pdf->Write(0,'X');
}
// Page 3

$tplIdx = $pdf->importPage(4, '/MediaBox');
$pdf->addPage();
$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

$tplIdx = $pdf->importPage(5, '/MediaBox');
$pdf->addPage();
$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

$today_date = date('Y-m-d');
$mva_file_name = 'MVA1_'.$today_date.'_'.$patientid.'.pdf';

$pdf->Output('download/'.$mva_file_name, 'F');

$query_update_site = "UPDATE `contacts` SET mva_forms = CONCAT_WS(',',mva_forms, '$mva_file_name') WHERE `contactid` = '$patientid'";
$result_update_site	= mysqli_query($dbc, $query_update_site);

?>
<script type="text/javascript" language="Javascript">
window.location.replace("../Contacts/contacts.php?category=Active%20Patient&filter=Top");
window.open('download/<?php echo $mva_file_name;?>', 'fullscreen=yes');
</script>

