 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);

    $therapist = $_POST['therapist'];
    $reev_date = $_POST['reev_date'];
    $surgery_date = $_POST['surgery_date'];

    $initial_treatment = filter_var(htmlentities($_POST['initial_treatment']),FILTER_SANITIZE_STRING);
    $reev_treatment = filter_var(htmlentities($_POST['reev_treatment']),FILTER_SANITIZE_STRING);
    $xrays = filter_var(htmlentities($_POST['xrays']),FILTER_SANITIZE_STRING);
    $mri_us = filter_var(htmlentities($_POST['mri_us']),FILTER_SANITIZE_STRING);

    $query_insert_site = "INSERT INTO `patientform_prescribed_treatment_schedule` (`patientformid`, `today_date`, `patient`, `therapist`, `initial_treatment`, `reev_date`, `reev_treatment`, `surgery_date`, `xrays`, `mri_us`) VALUES	('$patientformid', '$today_date', '$patient', '$therapist', '$initial_treatment', '$reev_date', '$reev_treatment', '$surgery_date', '$xrays', '$mri_us')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'prescribed_treatment_schedule/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('prescribed_treatment_schedule_pdf.php');
    echo prescribed_treatment_schedule_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("prescribed_treatment_schedule/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
