<?php
include	('database_connection.php');
include	('global.php');

$query_check_credentials = "SELECT fj.job_number, fj.jobid, cl.clientid, cl.client_name, fs.fsid  FROM clients cl, field_jobs fj, field_foreman_sheet fs WHERE fs.deleted = 0 AND fs.jobid = fj.jobid AND fj.clientid = cl.clientid AND supervisor_status = 'Pending' AND office_status IS NULL";
$result = mysqli_query($dbc, $query_check_credentials);

$fs = '';
$m = 0;
while($row = mysqli_fetch_array( $result )) {
    $jobid = $row['jobid'];
    $fsid = $row['fsid'];
    $fs .= $row['client_name']. " : ".$row['job_number']. " : <a target='_blank' href='".WEBSITE_URL."/add_field_foreman_sheet.php?jobid=".$jobid."&fsid=".$fsid."'>Click to Approve</a><br/>";
    $m++;
}

if($m > 0) {
    if (strpos($_SERVER['SERVER_NAME'], 'highlandprojectssoftware') !== false) {
        $to = 'jeffc@highlandprojects.com';
    } else {
        $to = 'info@freshfocusmedia.com';
    }
    $subject = 'Highland Projects - Foreman Sheets Pending Approval';

    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $message = '<html><body>';
    $message .= "Below Foreman Sheet(s) submitted for your approval.<br><br>";
    $message .= $fs;
    $message .= '</body></html>';
    mail($to, $subject, $message, $headers);
}
?>