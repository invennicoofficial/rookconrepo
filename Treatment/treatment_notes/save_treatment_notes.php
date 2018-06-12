 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);

    $code = filter_var($_POST['code'],FILTER_SANITIZE_STRING);
    $doctor = filter_var($_POST['doctor'],FILTER_SANITIZE_STRING);
    $program = filter_var($_POST['program'],FILTER_SANITIZE_STRING);

    $work_status = filter_var($_POST['work_status'],FILTER_SANITIZE_STRING);
    $treatment_date = filter_var($_POST['treatment_date'],FILTER_SANITIZE_STRING);
    $physio = filter_var($_POST['physio'],FILTER_SANITIZE_STRING);
    $visit = filter_var($_POST['visit'],FILTER_SANITIZE_STRING);
    $desc = filter_var(htmlentities($_POST['desc']),FILTER_SANITIZE_STRING);

    $query_insert_site = "INSERT INTO `patientform_treatment_notes` (`patientformid`, `today_date`, `patient`, `code`, `doctor`, `program`, `work_status`, `treatment_date`, `physio`, `visit`, `desc`) VALUES	('$patientformid', '$today_date', '$patient', '$code', '$doctor', '$program', '$work_status', '$treatment_date', '$physio', '$visit', '$desc')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'treatment_notes/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('treatment_notes_pdf.php');
    echo treatment_notes_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("treatment_notes/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
