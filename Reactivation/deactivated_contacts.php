<?php
/*
 * Send survey and offers to deactivated contacts
 */
include ('../include.php');
checkAuthorised('reactivation');
error_reporting(0);

$email_date = date('Y-m-d H:i:s');

if (isset($_POST['send_survey_email'])) {
    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $contactid = preg_replace('/[^0-9]/', '', $_POST['check_send_email'][$i]);
        $email = get_email($dbc, $contactid);
        
        $check = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `followupid` FROM `followup_deactivated_contacts` WHERE `contactid`='$contactid'"));
        if ( empty($check['followupid']) ) {
            $result = mysqli_query($dbc, "INSERT INTO `followup_deactivated_contacts`(`contactid`, `survey_sent_date`) VALUES('$contactid', '$email_date')");
        } else {
            $result = mysqli_query($dbc, "UPDATE `followup_deactivated_contacts` SET `survey_sent_date`='$email_date' WHERE `followupid`='{$check['followupid']}'");
        }
        
        $email_body = html_entity_decode(get_config($dbc, 'survey_deactivated_contact_body'));
        $email_body = str_replace("[Customer Name]", get_contact($dbc, $contactid), $email_body);
        $subject = get_config($dbc, 'survey_deactivated_contact_subject');
        
        if($email != '') {
            send_email([$_POST['email_sender_address']=>$_POST['email_sender_name']], $email, '', '', $subject, $email_body, '');
        }
    }
    echo '<script type="text/javascript"> alert("Survey email(s) sent to selected users."); window.location.replace("deactivated_contacts.php"); </script>';
}

if (isset($_POST['send_offer1_email'])) {
    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $contactid = preg_replace('/[^0-9]/', '', $_POST['check_send_email'][$i]);
        $email = get_email($dbc, $contactid);
        
        $check = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `followupid` FROM `followup_deactivated_contacts` WHERE `contactid`='$contactid'"));
        if ( empty($check['followupid']) ) {
            $result = mysqli_query($dbc, "INSERT INTO `followup_deactivated_contacts`(`contactid`, `offer1_sent_date`) VALUES('$contactid', '$email_date')");
        } else {
            $result = mysqli_query($dbc, "UPDATE `followup_deactivated_contacts` SET `offer1_sent_date`='$email_date' WHERE `followupid`='{$check['followupid']}'");
        }
        
        $email_body = html_entity_decode(get_config($dbc, 'offer1_deactivated_contact_body'));
        $email_body = str_replace("[Customer Name]", get_contact($dbc, $contactid), $email_body);
        $subject = get_config($dbc, 'offer1_deactivated_contact_subject');
        
        if($email != '') {
            send_email([$_POST['email_sender_address']=>$_POST['email_sender_name']], $email, '', '', $subject, $email_body, '');
        }
    }
    echo '<script type="text/javascript"> alert("Offer email(s) sent to selected users."); window.location.replace("deactivated_contacts.php"); </script>';
}

if (isset($_POST['send_offer2_email'])) {
    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $contactid = preg_replace('/[^0-9]/', '', $_POST['check_send_email'][$i]);
        $email = get_email($dbc, $contactid);
        
        $check = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `followupid` FROM `followup_deactivated_contacts` WHERE `contactid`='$contactid'"));
        if ( empty($check['followupid']) ) {
            $result = mysqli_query($dbc, "INSERT INTO `followup_deactivated_contacts`(`contactid`, `offer2_sent_date`) VALUES('$contactid', '$email_date')");
        } else {
            $result = mysqli_query($dbc, "UPDATE `followup_deactivated_contacts` SET `offer2_sent_date`='$email_date' WHERE `followupid`='{$check['followupid']}'");
        }
        
        $email_body = html_entity_decode(get_config($dbc, 'offer2_deactivated_contact_body'));
        $email_body = str_replace("[Customer Name]", get_contact($dbc, $contactid), $email_body);
        $subject = get_config($dbc, 'offer2_deactivated_contact_subject');
        
        if($email != '') {
            send_email([$_POST['email_sender_address']=>$_POST['email_sender_name']], $email, '', '', $subject, $email_body, '');
        }
    }
    echo '<script type="text/javascript"> alert("Offer email(s) sent to selected users."); window.location.replace("deactivated_contacts.php"); </script>';
}

if (isset($_POST['send_offer3_email'])) {
    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $contactid = preg_replace('/[^0-9]/', '', $_POST['check_send_email'][$i]);
        $email = get_email($dbc, $contactid);
        
        $check = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `followupid` FROM `followup_deactivated_contacts` WHERE `contactid`='$contactid'"));
        if ( empty($check['followupid']) ) {
            $result = mysqli_query($dbc, "INSERT INTO `followup_deactivated_contacts`(`contactid`, `offer3_sent_date`) VALUES('$contactid', '$email_date')");
        } else {
            $result = mysqli_query($dbc, "UPDATE `followup_deactivated_contacts` SET `offer3_sent_date`='$email_date' WHERE `followupid`='{$check['followupid']}'");
        }
        
        $email_body = html_entity_decode(get_config($dbc, 'offer3_deactivated_contact_body'));
        $email_body = str_replace("[Customer Name]", get_contact($dbc, $contactid), $email_body);
        $subject = get_config($dbc, 'offer3_deactivated_contact_subject');
        
        if($email != '') {
            send_email([$_POST['email_sender_address']=>$_POST['email_sender_name']], $email, '', '', $subject, $email_body, '');
        }
    }
    echo '<script type="text/javascript"> alert("Offer email(s) sent to selected users."); window.location.replace("deactivated_contacts.php"); </script>';
}

$starttimepdf = $_POST['starttimepdf'];
$endtimepdf = $_POST['endtimepdf'];
$starttime = $starttimepdf;
$endtime = $endtimepdf; ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#select_all').click(function(event) {  //on click
        if(this.checked) { // check select status
            $('.check_send_email').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.check_send_email').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });
});
</script>
</head>

<body>
<?php include_once ('../navigation.php'); ?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">
        <form name="form_clients" method="post" action="" class="form-horizontal" role="form">

        <div class="row">
            <div class="col-xs-11"><h1 class="single-pad-bottom">Deactivated Contacts Dashboard</div>
            <div class="col-xs-1 double-gap-top"><?php
                echo '<a href="config_reactivation.php" class="mobile-block pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>';?>
            </div>
        </div>

        <div class="mobile-100-container tab-container">
            <div class="tab pull-left">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Reactivating a current patient."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                if (check_subtab_persmission($dbc, 'reactivation', ROLE, 'active') === TRUE) { ?>
                    <a href='active_reactivation.php'><button type="button" class="btn brand-btn mobile-block">Active Reactivation</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Active Reactivation</button><?php
                } ?>
            </div>
            <div class="tab pull-left">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Reactivating a non-current patient."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                if (check_subtab_persmission($dbc, 'reactivation', ROLE, 'inactive') === TRUE) { ?>
                    <a href='inactive_reactivation.php'><button type="button" class="btn brand-btn mobile-block">Inactive Reactivation</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Inactive Reactivation</button><?php
                } ?>
            </div>
            <div class="tab pull-left">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Follow-up with patients that cancelled appointments."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                if (check_subtab_persmission($dbc, 'reactivation', ROLE, 'cancellations') === TRUE) { ?>
                    <a href='cancelled_appt.php'><button type="button" class="btn brand-btn mobile-block">Cancellations</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Cancellations</button><?php
                } ?>
            </div>
            <div class="tab pull-left">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Record and track calls for reactivation."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                if (check_subtab_persmission($dbc, 'reactivation', ROLE, 'cold_call') === TRUE) { ?>
                    <a href='call_log.php'><button type="button" class="btn brand-btn mobile-block">Cold Call</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Cold Call</button><?php
                } ?>
            </div>
            <div class="tab pull-left"><?php
                if (check_subtab_persmission($dbc, 'reactivation', ROLE, 'assessment_followup') === TRUE) { ?>
                    <a href="assessment_followup.php"><button type="button" class="btn brand-btn mobile-block gap-left">Assessment Follow-Up</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Assessment Follow-Up</button><?php
                } ?>
            </div>
            <div class="tab pull-left">
                <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Options for deactivated contacts."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                if (check_subtab_persmission($dbc, 'reactivation', ROLE, 'deactivated_contacts') === TRUE) { ?>
                    <a href='deactivated_contacts.php'><button type="button" class="btn brand-btn mobile-block active_tab">Deactivated Contacts</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Deactivated Contacts</button><?php
                } ?>
            </div>
            <div class="clearfix"></div>
        </div><!-- .tab-container -->

        <div class="notice double-gap-bottom popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            This tile is used to ensure customers are followed up with when they have missed appointments, and/or to check in with them after their treatments are completed based on specified time ranges.<br />
            Deactivated Contacts displays a list of contacts deactivated from the Contacts tile.</div>
            <div class="clearfix"></div>
        </div><?php
        
        if (isset($_POST['search_email_submit'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
            $therapist = $_POST['therapist'];
        }
        if($starttime == 0000-00-00) {
            $starttime = date('Y-m-01');
        }
        if($endtime == 0000-00-00) {
            $endtime = date('Y-m-d');
        } ?>

        <center>
            <div class="form-group">
                <label class="col-sm-4 control-label">Deactivated Date From:</label>
                <div class="col-sm-2"><input type="text" name="starttime" class="form-control datepicker" value="<?= $starttime; ?>" /></div>
                <label class="col-sm-2 control-label">Deactivated Date Until:</label>
                <div class="col-sm-2"><input type="text" name="endtime" class="form-control datepicker" value="<?= $endtime; ?>" /></div>
                <div class="col-sm-2"><button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button></div>
            </div>
        </center>
        
        <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>" />
        <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>" />
        
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Sender Name:</label>
			<div class="col-sm-8"><input type="text" name="email_sender_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>" /></div>
        </div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Sender Address:</label>
			<div class="col-sm-8"><input type="text" name="email_sender_address" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>" /></div>
        </div>
        <div class="form-group">
            <div class="pull-right gap-left">Select All&nbsp;&nbsp;<input type="checkbox" id="select_all" /></div>
            <button type="submit" name="send_offer3_email" value="Submit" class="btn brand-btn pull-right">Email Offer 3</button>
            <button type="submit" name="send_offer2_email" value="Submit" class="btn brand-btn pull-right">Email Offer 2</button>
            <button type="submit" name="send_offer1_email" value="Submit" class="btn brand-btn pull-right">Email Offer 1</button>
            <button type="submit" name="send_survey_email" value="Submit" class="btn brand-btn pull-right">Email Survey</button>
        </div><?php
        
        $result = mysqli_query($dbc, "SELECT `c`.`contactid`, `h`.`updated_at`, `f`.`survey_sent_date`, `f`.`offer1_sent_date`, `f`.`offer2_sent_date`, `f`.`offer3_sent_date` FROM `contacts` `c` LEFT JOIN `contacts_history` `h` ON (`h`.`contactid`=`c`.`contactid`) LEFT JOIN `followup_deactivated_contacts` `f` ON (`f`.`contactid`=`c`.`contactid`) WHERE `c`.`status`='0' AND `h`.`description` LIKE '%Inactive' AND DATE_FORMAT(`h`.`updated_at`,'%Y-%m-%d') BETWEEN '$starttime' AND '$endtime'");
        
        if ( $result->num_rows > 0 ) { ?>
            <div id="no-more-tables">
                <table class="table table-bordered">
                    <tr>
                        <th>Customer</th>
                        <th>Contact Info</th>
                        <th>Deactivated Date</th>
                        <th>Survey Sent Date</th>
                        <th>Offer 1 Sent Date</th>
                        <th>Offer 2 Sent Date</th>
                        <th>Offer 3 Sent Date</th>
                        <th>Send Email</th>
                    </tr><?php
            
                    while ( $row=mysqli_fetch_array($result) ) {
                        $customer_email = get_email($dbc, $row['contactid']); ?>
                        <tr>
                            <td><?= get_contact($dbc, $row['contactid']) ?></td>
                            <td><?= ( !empty($customer_email) ? get_email($dbc, $row['contactid']) : 'No Email') .'<br />'. get_contact_phone($dbc, $row['contactid']) ?></td>
                            <td><?= date('Y-m-d', strtotime($row['updated_at'])) ?></td>
                            <td><?= !empty($row['survey_sent_date']) ? date('Y-m-d', strtotime($row['survey_sent_date'])) : '-' ?></td>
                            <td><?= !empty($row['offer1_sent_date']) ? date('Y-m-d', strtotime($row['offer1_sent_date'])) : '-' ?></td>
                            <td><?= !empty($row['offer2_sent_date']) ? date('Y-m-d', strtotime($row['offer2_sent_date'])) : '-' ?></td>
                            <td><?= !empty($row['offer3_sent_date']) ? date('Y-m-d', strtotime($row['offer3_sent_date'])) : '-' ?></td>
                            <td><?php
                                if ( !empty($customer_email) ) { ?>
                                    <input name="check_send_email[]" type="checkbox" value="<?= $row['contactid'] ?>" class="form-control check_send_email" /><?php
                                } else {
                                    echo 'No Email';
                                } ?>
                            </td>
                        </tr><?php
                    } ?>
                </table>
            </div><!-- #no-more-tables --><?php
        } ?>

        </form>
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>