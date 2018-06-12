<?php
include ('../include.php');
require_once('../fpdf181/fpdf.php');
require_once('../FPDI-1.6.1/fpdi.php');

$pdf = new FPDI();

$pdf->setSourceFile("AB_MVA_03.pdf");

$tplIdx = $pdf->importPage(1, '/MediaBox');
$pdf->addPage();
$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

//$pdf->SetFont('Arial');
$pdf->SetFont('helvetica', '', 8);

$pdf->SetTextColor(0, 0, 0);

$patientid = $_GET['patientid'];
$last_name = get_all_form_contact($dbc, $patientid, 'last_name');
$first_name = get_all_form_contact($dbc, $patientid, 'first_name');
$birth_date = get_all_form_contact($dbc, $patientid, 'birth_date');
$home_phone = get_all_form_contact($dbc, $patientid, 'home_phone');
$cell_phone = get_all_form_contact($dbc, $patientid, 'cell_phone');

$pdf->SetXY(39, 79);
$pdf->Write(0,$last_name);

$pdf->SetXY(97, 79);
$pdf->Write(0,$first_name);

$pdf->SetXY(160, 79);
$pdf->Write(0,$birth_date);

//$pdf->SetXY(12, 136);
//$pdf->Write(0,$cell_phone);

//$pdf->SetXY(70, 136);
//$pdf->Write(0,$home_phone);

$today_date = date('Y-m-d');
$mva_file_name = 'MVA3_'.$today_date.'_'.$patientid.'.pdf';

$pdf->Output('download/'.$mva_file_name, 'F');

$query_update_site = "UPDATE `contacts` SET mva_forms = CONCAT_WS(',',mva_forms, '$mva_file_name') WHERE `contactid` = '$patientid'";
$result_update_site	= mysqli_query($dbc, $query_update_site);

?>

<script type="text/javascript" language="Javascript">
window.location.replace("../Contacts/contacts.php?category=Active%20Patient&filter=Top");
window.open('download/<?php echo $mva_file_name;?>', 'fullscreen=yes');
</script>

