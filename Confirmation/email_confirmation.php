<?php
/*
Referrals : Send Patient a Promotion if reffers someone.
*/
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);

if (isset($_POST['send_follow_up_email'])) {
    $confirmation_email_date = date('Y-m-d');
    $what = 0;

    /*
    for($i = 0; $i < count($_POST['reply_send_email']); $i++) {
        $bookingid = $_POST['reply_send_email'][$i];
        $query_update_patient = "UPDATE `booking` SET `confirmation_email_reply_date` = '$confirmation_email_date' WHERE `bookingid` = '$bookingid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_patient);
        $what = 1;
    }
    */

    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $what = 2;
        $bookingid = $_POST['check_send_email'][$i];
        $patientid = get_patient_from_booking($dbc, $bookingid, 'patientid');
        $therapistsid = get_patient_from_booking($dbc, $bookingid, 'therapistsid');

        $the_link = get_all_form_contact($dbc, $therapistsid, 'profile_link');

        $email = get_email($dbc, $patientid);

        $query_update_patient = "UPDATE `booking` SET `confirmation_email_date` = '$confirmation_email_date', `notification_sent` = 1 WHERE `bookingid` = '$bookingid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_patient);

        $email_body = $_POST['email_body'];
        $email_body = str_replace("[Therapist Name]", get_contact($dbc, $therapistsid), $email_body);
        $email_body = str_replace("[Customer Name]", get_contact($dbc, $patientid), $email_body);
        $email_body = str_replace("[Appointment Date]", get_patient_from_booking($dbc, $bookingid, 'appoint_date'), $email_body);

        $profile_link = '<a target="_blank" href="'.$the_link.'">Click Here</a>';
        $email_body = str_replace("[Staff Profile Link]", $profile_link, $email_body);

        $confirmation_link = '<a href="'.WEBSITE_URL.'/Confirmation/confirmation_booking.php?id='.$bookingid.'&status=Confirmed">Click to Confirm</a>';
        $email_body = str_replace("[Confirmation Link]", $confirmation_link, $email_body);

        $res_link = '<a href="'.WEBSITE_URL.'/Confirmation/confirmation_booking.php?id='.$bookingid.'&status=Reschedule">Click to Reschedule</a>';
        $email_body = str_replace("[Reschedule]", $res_link, $email_body);

        $cancel_link = '<a href="'.WEBSITE_URL.'/Confirmation/confirmation_booking.php?id='.$bookingid.'&status=Cancel">Click to Cancel</a>';
        $email_body = str_replace("[Cancel]", $cancel_link, $email_body);

        $subject = $_POST['email_subject'];

        //Mail
        if($email != '') {
			try {
				send_email([$_POST['email_sender']=>$_POST['email_name']], $email, '', '', $subject, $email_body, '');
			} catch (Exception $e) {
				echo "<script> alert('Unable to send email to $email, please try again later.'); </script>";
			}
        }
        //Mail
    }

    if($what == 2) {
        echo '<script type="text/javascript"> alert("Confirmation Email(s) Sent."); window.location.replace("email_confirmation.php"); </script>';
    }
    if($what == 1) {
        echo '<script type="text/javascript"> window.location.replace("email_confirmation.php"); </script>';
    }
}
?>
<script src="../js/bootstrap.min.js"></script>
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
$(document).on('change', 'select[name="follow_up_call_status[]"]', function() { selectStatus(this); });
function selectStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=bookingstatus&id="+arr[1]+'&name='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

        <form name="form_clients" method="post" action="" class="form-horizontal" role="form">

        <h1 class="single-pad-bottom">Appointment Confirmation Dashboard
        <?php
            echo '<a href="config_confirmation.php" class="mobile-block pull-right"><img style="width:40px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>';
        ?>
        </h1>

        <?php
        $value_config = ','.get_config($dbc, 'email_confirmation').',';
        ?>
        <div><?php
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'appointments' ) === true ) { ?>
                <a href='email_confirmation.php'><button type="button" class="btn brand-btn mobile-block active_tab">Appointment Confirmations</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Appointment Confirmations</button></a><?php
            }
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'tickets' ) === true ) { ?>
                <a href='ticket_notifications.php'><button type="button" class="btn brand-btn mobile-block">Ticket Notifications</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Ticket Notifications</button></a><?php
            }
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'followup' ) === true ) { ?>
                <a href='feedback_send_notifications.php'><button type="button" class="btn brand-btn mobile-block">Follow Up</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Follow Up</button></a><?php
            } ?>
        </div>
        <div class="gap-top">
			<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Track and send emails to customers for confirmation of their appointment(s)."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'email' ) === true ) { ?>
                <a href='email_confirmation.php'><button type="button" class="btn brand-btn mobile-block active_tab">Email Confirmation</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Email Confirmation</button></a><?php
            } ?>

			<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Track calls to customers for confirmation of their appointment(s)."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'call' ) === true ) { ?>
                <a href='call_confirmation.php'><button type="button" class="btn brand-btn mobile-block">Call Confirmation</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Call Confirmation</button></a><?php
            } ?>
		</div>

        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Send and track emails to customers for confirmation of their appointments.</div>
        <div class="clearfix"></div>
        </div>

        <?php
        if (isset($_POST['search_email_submit'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
            $therapist = $_POST['therapist'];
        }
        if($starttime == 0000-00-00) {
            $starttime = date('Y-m-d');
        }
        if($endtime == 0000-00-00) {
            $endtime = date('Y-m-d');
        }
        ?>
        <br>
        <center>
            <div class="form-group">
                <label class="col-sm-4 control-label">Appointment Date From:</label>
                <div class="col-sm-2"><input name="starttime" type="text" class="datepicker form-control" value="<?php echo $starttime; ?>"></div>
                <label class="col-sm-2 control-label">Appointment Date Until:</label>
                <div class="col-sm-2"><input name="endtime" type="text" class="datepicker form-control" value="<?php echo $endtime; ?>"></div>
                
                <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            </div>
        </center>

		<div class="form-group triple-gap-top">
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
				<input type="text" name="email_subject" class="form-control" value="<?= get_config($dbc, 'confirmation_email_subject') ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Body:</label>
			<div class="col-sm-8">
				<textarea name="email_body" class="form-control"><?= html_entity_decode(get_config($dbc, 'confirmation_email_body')) ?></textarea>
			</div>
		</div>
        <?php

        //$inactive_patient =	mysqli_query($dbc,"SELECT * FROM booking WHERE follow_up_call_status IN ('Booked Unconfirmed', 'Call Again Left Message', 'Call Again No Message', 'Reschedule Requested') AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."') ORDER BY therapistsid, appoint_date");
        
        $inactive_patient =	mysqli_query($dbc, "SELECT bookingid, therapistsid, patientid, appoint_date, follow_up_call_status, confirmation_email_date FROM booking WHERE follow_up_call_status IN ('Booked Unconfirmed', 'Booking Unconfirmed', 'Call Again Left Message', 'Call Again No Message', 'Reschedule Requested') AND type NOT IN ('Break', 'No Book Days', 'Vacation') AND DATE_FORMAT(appoint_date,'%Y-%m-%d') BETWEEN '".$starttime."' AND '".$endtime."' ORDER BY therapistsid, appoint_date");

        if ( $inactive_patient->num_rows > 0 ) {
            echo '
                <div class="pull-right text-right">
                    <h4>Select All&nbsp;<input type="checkbox" id="select_all" /></h4>
                    <button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn btn-lg pull-right">Send Email</button>
                </div>
                <div class="clearfix triple-gap-bottom"></div>';

            echo "<table class='table table-bordered'>";
            echo '<tr>
            <th>Staff</th>
            <th>Customer</th>
            <th>Contact Info</th>
            <th>Appointment Date</th>
            <th>Status</th>
            <th>Email Confirmation Sent Date</th>
            <th>Send Email</th>';
            echo "</tr>";

            while($row = mysqli_fetch_assoc($inactive_patient)) {
                $bookingid = $row['bookingid'];
                echo "<tr>";
                echo '<td>' . get_contact($dbc, $row['therapistsid']) . '</td>';
                echo '<td>' . get_contact($dbc, $row['patientid']) . '</td>';
                echo '<td>' . get_email($dbc, $row['patientid']) . '<br>';
                echo '' . get_contact_phone($dbc, $row['patientid']) . '</td>';
                echo '<td>' . $row['appoint_date'] . '</td>';

                $follow_up_call_status = $row['follow_up_call_status'];
                ?>
                <td data-title="Status">
                    <select data-placeholder="Choose a Status..." name="follow_up_call_status[]" id="status_<?php echo $row['bookingid']; ?>" class="chosen-select-deselect form-control input-sm">
                        <option value=""></option>
                        <option value="Booking Unconfirmed" <?php if ($follow_up_call_status == "Booking Unconfirmed") { echo " selected"; } ?> >Booking Unconfirmed</option>
                        <option value="Booking Confirmed" <?php if ($follow_up_call_status == "Booking Confirmed") { echo " selected"; } ?> >Booking Confirmed</option>
                        <option value="Arrived" <?php if ($follow_up_call_status == "Arrived") { echo " selected"; } ?> >Arrived</option>
                        <option value="Invoiced" <?php if ($follow_up_call_status == "Invoiced") { echo " selected"; } ?> >Invoiced</option>
                        <option value="Paid" <?php if ($follow_up_call_status == "Paid") { echo " selected"; } ?> >Paid</option>
                        <option value="Rescheduled" <?php if ($follow_up_call_status == "Rescheduled") { echo " selected"; } ?> >Rescheduled</option>
                        <option value="Late Cancellation / No-Show" <?php if ($follow_up_call_status == "Late Cancellation / No-Show") { echo " selected"; } ?> >Late Cancellation / No-Show</option>
                        <option value="Cancelled" <?php if ($follow_up_call_status == "Cancelled") { echo " selected"; } ?> >Cancelled</option>
                        <option value="Break" <?php if ($follow_up_call_status == "Break") { echo " selected"; } ?> >Break</option>
                        <option value="Meeting" <?php if ($follow_up_call_status == "Meeting") { echo " selected"; } ?> >Meeting</option>
                    </select>
                </td>
                <?php
                echo '<td>' . $row['confirmation_email_date']. '</td>';

                $patient_email = get_email($dbc, $row['patientid']);
                if($patient_email == '-' || $patient_email == '') {
                    echo '<td>No email</td>';
                } else if($row['confirmation_email_date'] != '') {
                    echo '<td>Sent</td>';
                } else {
                    echo '<td><input name="check_send_email[]" type="checkbox" value="'.$bookingid.'" class="form-control check_send_email" style="width:25px;"/></td>';
                }
                echo "</tr>";
            }
            echo '</table>';
            
        } else {
            echo '<h2>No records found.</h2>';
        }
        ?>

        </form>
        </div>
    </div>
</div>