<?php
/*
Confirm booking once Patient confirm by Email reply/Phone/direct Click link from email.
*/
include ('../include.php');
checkAuthorised('crm');
error_reporting(0);

if (isset($_GET['id'])) {
    $bookingid = $_GET['id'];
    $confirmation_email_reply_date = date('Y-m-d');
    $follow_up_call_status = 'Booked Confirmed';
    $query_update_patient = "UPDATE `booking` SET `confirmation_email_reply_date` = '$confirmation_email_reply_date', `follow_up_call_status` = '$follow_up_call_status' WHERE `bookingid` = '$bookingid'";
    $result_update_vendor = mysqli_query($dbc, $query_update_patient);

    $calid = get_calid_from_bookingid($dbc, $bookingid);
    $query_update_cal = "UPDATE `mrbs_entry` SET `patientstatus` = '$follow_up_call_status' WHERE `id` = '$calid'";
    $result_update_cal = mysqli_query($dbc, $query_update_cal);

    echo '<script type="text/javascript"> window.location.replace("feedback_survey_thankyou.php"); </script>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
    <link href="<?php echo WEBSITE_URL;?>/img/favicon.ico" rel="shortcut icon">
    <link href="<?php echo WEBSITE_URL;?>/img/apple-touch-icon.png" rel="apple-touch-icon-precomposed">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>

</body>
</html>