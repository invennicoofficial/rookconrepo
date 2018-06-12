 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);
    $pain_scale = $_POST['pain_0'];
    $section1 = $_POST['section1'];
    $section2 = $_POST['section2'];
    $section3 = $_POST['section3'];
    $section4 = $_POST['section4'];
    $section5 = $_POST['section5'];
    $section6 = $_POST['section6'];
    $section7 = $_POST['section7'];
    $section8 = $_POST['section8'];
    $section9 = $_POST['section9'];
    $section10 = $_POST['section10'];
    $total_score = $_POST['total_score'];
    $signature = $_POST['output'];

    $query_insert_site = "INSERT INTO `patientform_neck_disability_questionnaire` (`patientformid`, `today_date`, `patient`, `pain_scale`, `section1`, `section2`, `section3`, `section4`, `section5`, `section6`, `section7`, `section8`, `section9`, `section10`, `total_score`) VALUES	('$patientformid', '$today_date', '$patient', '$pain_scale', '$section1', '$section2', '$section3', '$section4', '$section5', '$section6', '$section7', '$section8', '$section9', '$section10', '$total_score')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $img = sigJsonToImage($signature);
    imagepng($img, 'neck_disability_questionnaire/download/sign_'.$fieldlevelriskid.'.png');

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'neck_disability_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('neck_disability_questionnaire_pdf.php');
    echo neck_disability_questionnaire_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("neck_disability_questionnaire/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
