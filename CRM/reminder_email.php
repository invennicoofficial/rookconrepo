<?php
/*
Send Reminder for booking/treatment.
*/
error_reporting(0);

if (isset($_POST['send_follow_up_email'])) {
    $reminder_email_date = date('Y-m-d');

    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $bookingid = $_POST['check_send_email'][$i];
        $patientid = get_patient_from_booking($dbc, $bookingid, 'patientid');
        $email = get_email($dbc, $patientid);

        $query_update_patient = "UPDATE `booking` SET `reminder_email_date` = '$reminder_email_date' WHERE `bookingid` = '$bookingid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_patient);

        $reminder = $_POST['email_body'];
        $email_body = str_replace("[Customer Name]", get_contact($dbc, $patientid), $reminder);
        $email_body = str_replace("[Appointment Date]", get_patient_from_booking($dbc, $bookingid, 'appoint_date'), $email_body);
        $subject = $_POST['email_subject'];

        //Mail
		try {
			send_email([$_POST['email_sender']=>$_POST['email_name']], $email, '', '', $subject, $email_body, '');
		} catch (Exception $e) {
			echo "<script> alert('Unable to send email to $email, please try again later.'); </script>";
		}
        //Mail
    }

    echo '<script type="text/javascript"> alert("Reminder Email Sent."); window.location.replace("reminder_email.php"); </script>';
}
?>
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
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <h1 class="single-pad-bottom">
		<?php
            echo '<a href="'.WEBSITE_URL.'/CRM/config_crm.php?category=reminder&maintype='.$_GET['maintype'].'" class="btn mobile-block pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?>
        </h1>

        <?php
        $value_config = ','.get_config($dbc, 'crm_dashboard').',';
        ?>

        <!--<?php if (strpos($value_config, ','."Referrals".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='referral.php'><button type="button" class="btn brand-btn mobile-block">Referrals</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Recommendations".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='recommendations.php'><button type="button" class="btn brand-btn mobile-block">Recommendations</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Surveys".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Send out Surveys to customers."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='survey.php'><button type="button" class="btn brand-btn mobile-block active_tab">Surveys</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Testimonials".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Record and track Testimonials."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='testimonials.php'><button type="button" class="btn brand-btn mobile-block">Testimonials</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Birthday & Promotion".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Track Birthdays and General Promotions."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='birthday_promo.php'><button type="button" class="btn brand-btn mobile-block">Birthdays &amp; Promotions</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."6 Month Follow Up Email".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Check in on/follow up with customers after 6 months to see how they are doing and potentially book future appointments."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='6month_follow_up_email.php'><button type="button" class="btn brand-btn mobile-block gap_left">6 Month Follow Up Email</button></a>
		</span>
        <?php } ?>-->

        <!--<?php if (strpos($value_config, ','."Confirmation Email".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Send and track confirmation emails one month ahead."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='confirmation_email.php'><button type="button" class="btn brand-btn mobile-block">Confirmation Email</button></a>
		</span>
        <?php } ?>

        <?php if (strpos($value_config, ','."Reminder Email".',') !== FALSE) { ?>
		<span>
			<span class="popover-examples list-inline" style="margin:0 0 0 3px;"><a data-toggle="tooltip" data-placement="top" title="Send and track appointment reminder emails."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href='reminder_email.php'><button type="button" class="btn brand-btn mobile-block">Reminder Email</button></a>
		</span>
        <?php } ?>-->

        <!--<br><br>-->
        <span class="popover-examples list-inline pull-right">
            <a href="#job_file" data-toggle="tooltip" data-placement="top" title="Once the reminder has been sent, it will be hidden."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="25"></a>
        </span>

        <form name="form_clients" method="post" action="" class="form-horizontal" role="form">
        <?php
        $reminder_email_send_before = get_config($dbc, 'reminder_email_send_before');

        echo '<h3>Appointments between '.date('Y-m-d').' AND '.date('Y-m-d', strtotime("+".$reminder_email_send_before."s")).'</h3>';

        echo '<span class="pull-right"><h4>Select All&nbsp;<input type="checkbox" id="select_all" class="form-control" style="width:25px;"/></h4></span>';

        $result =	mysqli_query($dbc,"SELECT * FROM booking WHERE reminder_email_date IS NULL AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) BETWEEN CURDATE() AND CURDATE() + INTERVAL $reminder_email_send_before ORDER BY `appoint_date`");

        echo "<table class='table table-bordered'>";
        echo '<tr><th>Customer</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Appointment Date</th>
        <th>Reminder Sent Date</th>
        <th>Send Email</th>';
        echo "</tr>";

        while($row = mysqli_fetch_array($result)) {
            $bookingid = $row['bookingid'];
            $patient_email = get_email($dbc, $row['patientid']);
            echo "<tr>";
            echo '<td>' . get_contact($dbc, $row['patientid']) . '</td>';
            echo '<td>' . get_email($dbc, $row['patientid']) . '</td>';
            echo '<td>' . get_contact_phone($dbc, $row['patientid']) . '</td>';
            echo '<td>' . $row['appoint_date'] . '</td>';
            echo '<td>' . $row['reminder_email_date']. '</td>';

            if($patient_email == '-' || $patient_email == '') {
                echo '<td>-</td>';
            } else {
                echo '<td><input name="check_send_email[]" type="checkbox" value="'.$bookingid.'" class="form-control check_send_email" style="width:25px;"/></td>';
            }

            echo "</tr>";
        }
        echo '</table>';
        ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Name:</label>
			<div class="col-sm-8">
				<input type="text" name="email_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Address:</label>
			<div class="col-sm-8">
				<input type="text" name="email_sender" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Subject:</label>
			<div class="col-sm-8">
				<input type="text" name="email_subject" class="form-control" value="<?= get_config($dbc, 'reminder_email_subject') ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Body:</label>
			<div class="col-sm-8">
				<textarea name="email_body" class="form-control"><?= html_entity_decode(get_config($dbc, 'reminder_email_body')) ?></textarea>
			</div>
		</div>
        <button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>