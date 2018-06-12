 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);

    $pain = '';
    for($i=0; $i<=9; $i++) {
        $pain .= $_POST['pain_'.$i].',';
    }

    $query_insert_site = "INSERT INTO `patientform_whiplash_associated_disorders` (`patientformid`, `today_date`, `patient`, `pain`) VALUES	('$patientformid', '$today_date', '$patient', '$pain')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'whiplash_associated_disorders/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('whiplash_associated_disorders_pdf.php');
    echo whiplash_associated_disorders_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("whiplash_associated_disorders/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
