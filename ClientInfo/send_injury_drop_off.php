<?php
include ('../include.php');
checkAuthorised('client_info');

$patientid = $_GET['patientid'];
$type = $_GET['type'];

$email = get_email($dbc, $patientid);
if($type == 'Massage') {
    $email_body = html_entity_decode(get_config($dbc, 'massage_drop_off_analysis_body'));
    $subject = get_config($dbc, 'massage_drop_off_analysis_subject');

    send_email('', $email, '', '', $subject, $email_body, '');
}

if($type == 'Physiotherapy') {
    $email_body = html_entity_decode(get_config($dbc, 'physio_drop_off_analysis_body'));
    $subject = get_config($dbc, 'physio_drop_off_analysis_subject');

    send_email('', $email, '', '', $subject, $email_body, '');
}

header('Location: add_contact.php?type=patients&contactid='.$patientid);