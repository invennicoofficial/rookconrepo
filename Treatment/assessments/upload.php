<?php    
require('../../database_connection.php');
$uploads = 'downloads/';
$img = $_POST['img'];
$patient = $_POST['patient'];
$therapist = $_POST['therapist'];
$img = str_replace('data:image/png;base64,', '', $img);
$result = mysqli_query($dbc, "INSERT INTO `assessment` (`therapistsid`,`patientid`) VALUES ('$therapist','$patient')");
$assess_id = mysqli_insert_id($dbc);
$result = mysqli_query($dbc, "INSERT INTO `patientform_assessment_pdf` (`form_name`,`assessmentid`) VALUES ('Body Targeted Assessment Form','$assess_id')");
$pdfid = mysqli_insert_id($dbc);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = 'assess_'.$pdfid.'_'.date('Y-m-d');
if(!file_exists($uploads)) {
	mkdir($uploads, 0777, true);
}
$success = file_put_contents($uploads.$file.'.png', $data);
print $success ? $uploads.$file.'.png' : 'ERROR';
?> 