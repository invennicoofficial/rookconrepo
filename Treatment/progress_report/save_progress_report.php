 <?php
    $today_date = date('Y-m-d');

    $fields_value = '**FFM**';
    for($i=1; $i<=33; $i++) {
        $fields_value .= filter_var($_POST['fields_value_'.$i],FILTER_SANITIZE_STRING).'**FFM**';
    }

    $output = $_POST['output'];

    $patientid = $_POST['fields_value_5'];
    $patient = get_contact($dbc, $patientid);

    $query_insert_site = "INSERT INTO `patientform_progress_report` (`patientformid`, `today_date`, `fields_value`) VALUES	('$patientformid', '$today_date', '$fields_value')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'progress_report/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    $img = sigJsonToImage($output);
    imagepng($img, 'progress_report/download/sign_'.$fieldlevelriskid.'.png');

    include ('progress_report_pdf.php');
    echo progress_report_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("progress_report/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
