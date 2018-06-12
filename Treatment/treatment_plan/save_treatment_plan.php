 <?php
    $today_date = date('Y-m-d');

    $fields_value = '**FFM**';
    for($i=1; $i<=63; $i++) {
        $fields_value .= filter_var($_POST['fields_value_'.$i],FILTER_SANITIZE_STRING).'**FFM**';
    }

    $output = $_POST['output'];
    $sign2 = $_POST['sign2'];

    $patientid = $_POST['fields_value_5'];
    $patient = get_contact($dbc, $patientid);

    $query_insert_site = "INSERT INTO `patientform_treatment_plan` (`patientformid`, `today_date`, `fields_value`) VALUES	('$patientformid', '$today_date', '$fields_value')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'treatment_plan/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    $img = sigJsonToImage($output);
    imagepng($img, 'treatment_plan/download/sign1_'.$fieldlevelriskid.'.png');
    $img2 = sigJsonToImage($sign2);
    imagepng($img2, 'treatment_plan/download/sign2_'.$fieldlevelriskid.'.png');

    include ('treatment_plan_pdf.php');
    echo treatment_plan_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">window.location.replace("treatment_plan/download/patientform_'.$fieldlevelriskid.'.pdf");</script>';
