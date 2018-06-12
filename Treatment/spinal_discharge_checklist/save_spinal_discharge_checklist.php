 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);

    $rom = filter_var($_POST['rom'],FILTER_SANITIZE_STRING);
    $chin = filter_var($_POST['chin'],FILTER_SANITIZE_STRING);
    $prone = filter_var($_POST['prone'],FILTER_SANITIZE_STRING);

    $isometric_0 = filter_var($_POST['isometric_0'],FILTER_SANITIZE_STRING);
    $neck_strap = filter_var($_POST['neck_strap'],FILTER_SANITIZE_STRING);
    $body_parts = filter_var($_POST['body_parts'],FILTER_SANITIZE_STRING);
    $ndi = filter_var($_POST['ndi'],FILTER_SANITIZE_STRING);
    $psfs = filter_var($_POST['psfs'],FILTER_SANITIZE_STRING);
    $roland = filter_var($_POST['roland'],FILTER_SANITIZE_STRING);
    $pain_0 = filter_var($_POST['pain_0'],FILTER_SANITIZE_STRING);

    $goals = filter_var(htmlentities($_POST['goals']),FILTER_SANITIZE_STRING);
    $independence = filter_var(htmlentities($_POST['independence']),FILTER_SANITIZE_STRING);
    $testimonial = filter_var(htmlentities($_POST['testimonial']),FILTER_SANITIZE_STRING);

    $query_insert_site = "INSERT INTO `patientform_spinal_discharge_checklist` (`patientformid`, `today_date`, `patient`, `rom`, `chin`, `prone`, `isometric_0`, `neck_strap`, `body_parts`, `ndi`, `psfs`, `roland`, `pain_0`, `goals`, `independence`, `testimonial`) VALUES	('$patientformid', '$today_date', '$patient', '$rom', '$chin', '$prone', '$isometric_0', '$neck_strap', '$body_parts', '$ndi', '$psfs', '$roland', '$pain_0', '$goals', '$independence', '$testimonial')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'spinal_discharge_checklist/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('spinal_discharge_checklist_pdf.php');
    echo spinal_discharge_checklist_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("spinal_discharge_checklist/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
