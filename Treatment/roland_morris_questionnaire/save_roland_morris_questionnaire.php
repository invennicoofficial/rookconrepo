 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);
    $pain_scale = $_POST['pain_0'];
    $fields = implode(',',$_POST['fields']);
    $total_score = $_POST['total_score'];
    $signature = $_POST['output'];

    $query_insert_site = "INSERT INTO `patientform_roland_morris_questionnaire` (`patientformid`, `today_date`, `patient`, `pain_scale`, `fields`, `total_score`) VALUES	('$patientformid', '$today_date', '$patient', '$pain_scale', '$fields', '$total_score')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $img = sigJsonToImage($signature);
    imagepng($img, 'roland_morris_questionnaire/download/sign_'.$fieldlevelriskid.'.png');

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'roland_morris_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('roland_morris_questionnaire_pdf.php');
    echo roland_morris_questionnaire_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("roland_morris_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
