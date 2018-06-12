<?php
require_once($software_url."/include.php");

$query = mysqli_query($dbc,"SELECT safetyid, form, assign_staff FROM safety WHERE deleted=0 AND DATE(NOW()) < DATE(deadline)");
while($row = mysqli_fetch_array($query)) {
    $safetyid = $row['safetyid'];

    $assign_staff = explode(',', $row['assign_staff']);
    foreach($assign_staff as $staff)  {
        if($staff != '') {
            $to = get_email($dbc, $staff);
            $subject = 'Safety Form Assigned to you';
            $message = "Please login with software and click on below link.<br><br>";
            $message .= 'Safety : <a target="_blank" href="'.WEBSITE_URL.'/Safety/add_manual.php?safetyid='.$safetyid.'&action=view">Click Here</a><br>';
            if($to != '') {
                send_email('', $to, '', '', $subject, $message, '');
            }
        }
    }
}
echo 'Email Sent';
?>
