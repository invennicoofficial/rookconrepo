 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);
    $therapist = $_POST['therapist'];
    $notes = filter_var(htmlentities($_POST['notes']),FILTER_SANITIZE_STRING);

    $query_insert_site = "INSERT INTO `patientform_massage_treatment_notes` (`patientformid`, `today_date`, `patient`, `therapist`, `notes`) VALUES	('$patientformid', '$today_date', '$patient', '$therapist', '$notes')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'massage_treatment_notes/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('massage_treatment_notes_pdf.php');
    echo massage_treatment_notes_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("massage_treatment_notes/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';