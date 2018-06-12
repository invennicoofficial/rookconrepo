 <?php
    $today_date = date('Y-m-d');
    $patientid = filter_var($_POST['patientid'],FILTER_SANITIZE_STRING);
    $patient = get_contact($dbc, $patientid);

    $wcb = filter_var($_POST['wcb'],FILTER_SANITIZE_STRING);
    $health_card = filter_var($_POST['health_card'],FILTER_SANITIZE_STRING);
    $surname = filter_var($_POST['surname'],FILTER_SANITIZE_STRING);
    $first_name = filter_var($_POST['first_name'],FILTER_SANITIZE_STRING);
    $birth_date = filter_var($_POST['birth_date'],FILTER_SANITIZE_STRING);
    $employer = filter_var($_POST['employer'],FILTER_SANITIZE_STRING);
    $person_contacted = filter_var($_POST['person_contacted'],FILTER_SANITIZE_STRING);
    $supervisor = filter_var($_POST['supervisor'],FILTER_SANITIZE_STRING);
    $phone_number = filter_var($_POST['phone_number'],FILTER_SANITIZE_STRING);
    $date_contacted = filter_var($_POST['date_contacted'],FILTER_SANITIZE_STRING);

    $modified = filter_var($_POST['modified'],FILTER_SANITIZE_STRING);
    $alternate = filter_var($_POST['alternate'],FILTER_SANITIZE_STRING);
    $return = filter_var($_POST['return'],FILTER_SANITIZE_STRING);
    $physical = filter_var($_POST['physical'],FILTER_SANITIZE_STRING);
    $requested = filter_var($_POST['requested'],FILTER_SANITIZE_STRING);

    $goals = filter_var(htmlentities($_POST['goals']),FILTER_SANITIZE_STRING);

    $query_insert_site = "INSERT INTO `patientform_wcb_provider_employer_contact` (`patientformid`, `today_date`, `patient`, `wcb`, `health_card`, `surname`, `first_name`, `birth_date`, `employer`, `person_contacted`, `supervisor`, `phone_number`, `date_contacted`, `modified`, `alternate`, `return`, `physical`, `requested`, `goals`) VALUES	('$patientformid', '$today_date', '$patient', '$wcb', '$health_card', '$surname', '$first_name', '$birth_date', '$employer', '$person_contacted', '$supervisor', '$phone_number', '$date_contacted', '$modified', '$alternate', '$return', '$physical', '$requested', '$goals')";

    $result_insert_site	= mysqli_query($dbc, $query_insert_site);
    $fieldlevelriskid = mysqli_insert_id($dbc);

    $form_name = get_formid_from_patientform($dbc, $patientformid, 'form');
    $pdf_path = 'wcb_provider_employer_contact/download/patientform_'.$fieldlevelriskid.'.pdf';

    $query_insert_site = "INSERT INTO `patientform_pdf` (`patientformid`, `fieldlevelriskid`, `patientid`, `form_name`, `pdf_path`, `today_date`) VALUES	('$patientformid', '$fieldlevelriskid', '$patientid', '$form_name', '$pdf_path', '$today_date')";
    $result_insert_site	= mysqli_query($dbc, $query_insert_site);

    include ('wcb_provider_employer_contact_pdf.php');
    echo wcb_provider_employer_contact_pdf($dbc,$patientformid, $fieldlevelriskid);

    echo '<script type="text/javascript">
        window.location.replace("patientform.php?tab=Form");
        window.open("wcb_provider_employer_contact/download/patientform_'.$fieldlevelriskid.'.pdf", "fullscreen=yes"); </script>';
