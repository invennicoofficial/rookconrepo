 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);

    $activity_1 = filter_var($_POST['activity_1'],FILTER_SANITIZE_STRING);
    $activity_2 = filter_var($_POST['activity_2'],FILTER_SANITIZE_STRING);
    $activity_3 = filter_var($_POST['activity_3'],FILTER_SANITIZE_STRING);

    $pain_0 = $_POST['pain_0'];
    $pain_1 = $_POST['pain_1'];
    $pain_2 = $_POST['pain_2'];

    $total_score = $_POST['total_score'];
    $mean_score = $_POST['mean_score'];

    $signature = $_POST['output'];

    $query_insert_site = "INSERT INTO `patientform_patient_functional_scale` (`patientformid`, `today_date`, `patient`, `activity_1`, `activity_2`, `activity_3`, `pain_0`, `pain_1`, `pain_2`, `total_score`, `mean_score`) VALUES	('$patientformid', '$today_date', '$patient', '$activity_1', '$activity_2', '$activity_3', '$pain_0', '$pain_1', '$pain_2', '$total_score', '$mean_score')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $img = sigJsonToImage($signature);
    imagepng($img, 'patient_functional_scale/download/sign_'.$fieldlevelriskid.'.png');

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'patient_functional_scale/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('patient_functional_scale_pdf.php');
    echo patient_functional_scale_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("patient_functional_scale/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
