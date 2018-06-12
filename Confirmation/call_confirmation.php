<?php
/*
Referrals : Send Patient a Promotion if reffers someone.
*/
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0);

if (!empty($_GET['bookingid'])) {
    $follow_up_call_date = date('Y-m-d');
    $bookingid = $_GET['bookingid'];
    $status = 'Booked Unconfirmed';

    $query_update_patient = "UPDATE `booking` SET `follow_up_call_date` = '$follow_up_call_date', `follow_up_call_status` = '$status' WHERE `bookingid` = '$bookingid'";
    $result_update_vendor = mysqli_query($dbc, $query_update_patient);

    $timeline = $_GET['timeline'];

    echo '<script type="text/javascript"> alert("Called Patient. Now Change Status as per Call Answer."); window.location.replace("call_confirmation.php?time='.$timeline.'"); </script>';
}
?>
<script type="text/javascript">
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

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <form name="form_clients" method="post" action="" class="form-horizontal" role="form">

        <h1 class="single-pad-bottom">Call Confirmation Dashboard
        <?php
            echo '<a href="config_confirmation.php" class="mobile-block pull-right"><img style="width:40px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>';
        ?>
        </h1>

        <?php
        $value_config = ','.get_config($dbc, 'call_confirmation').',';
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
                    <a href='email_confirmation.php'><button type="button" class="btn brand-btn mobile-block" >Email Confirmation</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Email Confirmation</button></a><?php
                } ?>

			<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Track calls to customers for confirmation of their appointment(s)."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'call' ) === true ) { ?>
                    <a href='call_confirmation.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Call Confirmation</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Call Confirmation</button></a><?php
                } ?>
		</div>

        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Track phone calls made to customers for confirmation of their appointments.</div>
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
            Appointment Date From:
                <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">

            Appointment Date Until:
                <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>">

        <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
        </div></center>
        <?php

        $inactive_patient =	mysqli_query($dbc,"SELECT * FROM booking WHERE follow_up_call_status IN ('Booking Unconfirmed', 'Call Again Left Message', 'Call Again No Message', 'Reschedule Requested') AND type NOT IN ('Break', 'No Book Days', 'Vacation') AND DATE_FORMAT(appoint_date,'%Y-%m-%d') BETWEEN '".$starttime."' AND '".$endtime."' ORDER BY therapistsid, appoint_date");

        if ( $inactive_patient->num_rows > 0 ) {
            echo "<table class='table table-bordered'>";
            echo '<tr>
            <th>Staff</th>
            <th>Customer</th>
            <th>Contact Info</th>
            <th>Appointment</th>
            <th>Status</th>
            <th>Email Confirmation Sent Date</th>
            <th>Call Date</th>
            <th>Customer Called</th>';
            echo "</tr>";

            while($row = mysqli_fetch_array($inactive_patient)) {
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
                        <option value="Rescheduled" <?php if ($follow_up_call_status == "Rescheduled") { echo " selected"; } ?> >Rescheduled</option>
                        <option value="Reschedule Requested" <?php if ($follow_up_call_status == "Reschedule Requested") { echo " selected"; } ?> >Reschedule Requested</option>
                        <option value="Late Cancellation / No-Show" <?php if ($follow_up_call_status == "Late Cancellation / No-Show") { echo " selected"; } ?> >Late Cancellation / No-Show</option>
                        <option value="Call Again Left Message" <?php if ($follow_up_call_status == "Call Again Left Message") { echo " selected"; } ?> >Call Again Left Message</option>
                        <option value="Call Again No Message" <?php if ($follow_up_call_status == "Call Again No Message") { echo " selected"; } ?> >Call Again No Message</option>
                        <option value="Cancelled" <?php if ($follow_up_call_status == "Cancelled") { echo " selected"; } ?> >Cancelled</option>
                    </select>
                </td>
                <?php
                echo '<td>' . $row['confirmation_email_date']. '</td>';
                echo '<td>' . $row['follow_up_call_date']. '</td>';

                echo '<td> <span class="popover-examples no-gap-pad"> <a data-toggle="tooltip" data-placement="top" title="Click the star when you have called the customer, it will change color to gold and display the date the customer was called."><img src="../img/info.png" width="20"></a> </span>';
                if($row['follow_up_call_date'] == '' || $row['follow_up_call_date'] == '0000-00-00' || $follow_up_call_status == "Call Again Left Message" || $follow_up_call_status == "Call Again No Message") {
                    echo '<a href="call_confirmation.php?bookingid='.$row['bookingid'].'&timeline='.$_GET['time'].'"><img src="'.WEBSITE_URL.'/img/blank_star.png" width="32" height="32" border="0" alt=""></a>';
                } else {
                    echo '<img src="'.WEBSITE_URL.'/img/filled_star.png" width="32" height="32" border="0" alt="">';
                }
                echo '</td>';

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
<?php include ('../footer.php'); ?>