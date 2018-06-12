<?php include('../include.php');
checkAuthorised('incident_report');

$incidentreportid = $_GET['incidentreportid'];
$ticketid = $_GET['ticketid'];
$staffid = $_SESSION['contactid'];
$name_of_file = 'incident_report_'.$incidentreportid.'.pdf';
mysqli_query($dbc, "UPDATE `incident_report_reminders` SET `done` = 1 WHERE `staffid` = '$staffid' AND `ticketid` = '$ticketid'");
echo '<script type="text/javascript"> window.location.href = "'.WEBSITE_URL.'/Incident Report/download/'.$name_of_file.'"; </script>';