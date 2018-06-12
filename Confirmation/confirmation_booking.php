<?php
/*
 * Confirm booking once Patient confirm by Email reply/Phone/direct Click link from email.
 */
$guest_access = true;
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);

if (isset($_GET['id'])) {
    $bookingid = preg_replace('/[^0-9]/', '', $_GET['id']);
    $status = $_GET['status'];
    $confirmation_email_reply_date = date('Y-m-d');

    if($status == 'Confirmed') {
        $follow_up_call_status = 'Booking Confirmed';
    }
    if($status == 'Reschedule') {
        $follow_up_call_status = 'Reschedule Requested';
    }
    if($status == 'Cancel') {
        $follow_up_call_status = 'Cancelled';
    }

    $query_update_patient = "UPDATE `booking` SET `confirmation_email_reply_date` = '$confirmation_email_reply_date', `follow_up_call_status` = '$follow_up_call_status' WHERE `bookingid` = '$bookingid'";
    $result_update_vendor = mysqli_query($dbc, $query_update_patient);

    $calid = get_calid_from_bookingid($dbc, $bookingid);
    $query_update_cal = "UPDATE `mrbs_entry` SET `patientstatus` = '$follow_up_call_status' WHERE `id` = '$calid'";
    $result_update_cal = mysqli_query($dbc, $query_update_cal);
    
    //Mail
    $email = '';
    $name = 'Appointment Updated';
    $subject = 'Appointment Updated';
    $email_body = '';
    
    $appoint_date = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `appoint_date` FROM `booking` WHERE `bookingid`='$bookingid'"));
    
    $result_emailbody = mysqli_query($dbc, "SELECT `first_name`, `last_name` FROM `contacts` WHERE `contactid` IN (SELECT `patientid` FROM `booking` WHERE `bookingid`='$bookingid')");
    if ( $result_emailbody->num_rows > 0 ) {
        while ( $row_emailbody=mysqli_fetch_assoc($result_emailbody) ) {
            $subject = 'Appointment Updated for ' . decryptIt($row['first_name']) .' '.  decryptIt($row['last_name']);
            $email_body = '<h3>Appointment Updated</h3>';
            $email_body .= '<i>'. $confirmation_email_reply_date .'</i><br /><br />';
            $email_body .= '<b>Name:</b> ' . decryptIt($row['first_name']) .' '.  decryptIt($row['last_name']) . '<br />';
            $email_body .= '<b>Appointment Date/Time:</b> ' . date('Y-m-d H:i', strtotime($appoint_date['appoint_date'])) . '<br />';
            $email_body .= '<b>Appointment Status:</b> ' . $follow_up_call_status;
        }
    }
    
    $result = mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE `name`='main_email_address'");
    if ( $result->num_rows > 0 ) {
        while ( $row=mysqli_fetch_assoc($result) ) {
            $email = $row['value'];
        }
    }
    
    $result = mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE `name`='main_email_name'");
    if ( $result->num_rows > 0 ) {
        while ( $row=mysqli_fetch_assoc($result) ) {
            $name = $row['value'];
        }
    }
    
    if ( $email != '' ) {
        echo $email . '<br />';
        echo $subject . '<br />';
        echo $email_body . '<br />';
        try {
            send_email([$name=>$email], $email, '', '', $subject, $email_body, '');
        } catch (Exception $e) {
            echo "<script> alert('Unable to send email to $email, please try again later.'); </script>";
        }
    }

    echo '<script type="text/javascript">window.location.replace("'.WEBSITE_URL.'/Confirmation/thankyou.php");</script>';

} else {
    echo '<script type="text/javascript">window.location.replace("'.WEBSITE_URL.'");</script>';
}
?>