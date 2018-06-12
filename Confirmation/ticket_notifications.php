<?php
/*
Referrals : Send Patient a Promotion if reffers someone.
*/
include ('../include.php');
checkAuthorised('confirmation');
error_reporting(0); ?>

<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).on('change', 'select[name="noti_status"]', function() { update_status(this); });
function update_status(sel) {
    var ticketnotificationid = $(sel).closest('tr').data('id');
    var status = $(sel).val();
    $.ajax({
        url: '../Confirmation/confirmation_ajax.php?fill=update_status',
        type: 'POST',
        data: {
            ticketnotificationid: ticketnotificationid,
            status: status
        },
        success: function(response) {

        }
    });
}
function update_send_date(input) {
    var ticketnotificationid = $(input).closest('tr').data('id');
    var send_date = $(input).val();
    $.ajax({
        url: '../Confirmation/confirmation_ajax.php?fill=update_send_date',
        type: 'POST',
        data: {
            ticketnotificationid: ticketnotificationid,
            send_date: send_date
        },
        success: function(response) {
            $(input).closest('tr').find('[name="noti_status"]').val('Pending').trigger('change.select2');
        }
    });
}
function update_followup_date(input) {
    var ticketnotificationid = $(input).closest('tr').data('id');
    var follow_up_date = $(input).val();
    $.ajax({
        url: '../Confirmation/confirmation_ajax.php?fill=update_followup_date',
        type: 'POST',
        data: {
            ticketnotificationid: ticketnotificationid,
            follow_up_date: follow_up_date
        },
        success: function(response) {

        }
    });
}
function delete_notification(link) {
    if(confirm('Are you sure you want to delete this notification?')) {
        var ticketnotificationid = $(link).closest('tr').data('id');
        $.ajax({
            url: '../Confirmation/confirmation_ajax.php?fill=delete_notification',
            type: 'POST',
            data: {
                ticketnotificationid: ticketnotificationid
            },
            success: function(response) {
                $(link).closest('tr').remove();
            }
        });
    }
}
function send_now(link) {
    if(confirm('Are you sure you want to send this notification?')) {
        var ticketnotificationid = $(link).closest('tr').data('id');
        $.ajax({
            url: '../Confirmation/confirmation_ajax.php?fill=send_notification',
            type: 'POST',
            data: {
                ticketnotificationid: ticketnotificationid
            },
            success: function(response) {
                alert(response);
                $(link).closest('tr').find('[name="noti_status"]').val('Sent');
            }
        });
    }
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

        <h1 class="single-pad-bottom">Ticket Notifications Dashboard
        <?php
            echo '<a href="config_confirmation.php" class="mobile-block pull-right"><img style="width:40px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>';
        ?>
        </h1>

        <?php
        $value_config = ','.get_config($dbc, 'email_confirmation').',';
        ?>
        <div><?php
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'appointments' ) === true ) { ?>
                <a href='email_confirmation.php'><button type="button" class="btn brand-btn mobile-block">Appointment Confirmations</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Appointment Confirmations</button></a><?php
            }
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'tickets' ) === true ) { ?>
                <a href='ticket_notifications.php'><button type="button" class="btn brand-btn mobile-block active_tab">Ticket Notifications</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Ticket Notifications</button></a><?php
            }
            if ( check_subtab_persmission( $dbc, 'confirmation', ROLE, 'followup' ) === true ) { ?>
                <a href='feedback_send_notifications.php'><button type="button" class="btn brand-btn mobile-block">Follow Up</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block">Follow Up</button></a><?php
            } ?>
        </div>

        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Send and track Ticket Notifications sent to Staff and Contacts. Update the Send Date column to resend the Notification to a different date. Notifications with a Status of Pending means it has not yet been sent. Notifications with a Status of Sent means it was already sent. Cancelled Status means the Notification is cancelled and will not be sent.</div>
        <div class="clearfix"></div>
        </div>

        <?php
        if (isset($_POST['search_email_submit'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
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
                <label class="col-sm-4 control-label">Date From:</label>
                <div class="col-sm-2"><input name="starttime" type="text" class="datepicker form-control" value="<?php echo $starttime; ?>"></div>
                <label class="col-sm-2 control-label">Date Until:</label>
                <div class="col-sm-2"><input name="endtime" type="text" class="datepicker form-control" value="<?php echo $endtime; ?>"></div>
                
                <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            </div>
        </center>

        <?php

        //$inactive_patient =	mysqli_query($dbc,"SELECT * FROM booking WHERE follow_up_call_status IN ('Booked Unconfirmed', 'Call Again Left Message', 'Call Again No Message', 'Reschedule Requested') AND type != 'I' AND type != 'E' AND type != 'P' AND type != 'Q' AND type != 'R' AND type != '' AND ((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) <= '".$endtime."') ORDER BY therapistsid, appoint_date");
        
        $inactive_patient =	mysqli_query($dbc, "SELECT * FROM `ticket_notifications` WHERE `send_date` BETWEEN '$starttime' AND '$endtime' AND `deleted` = 0");

        if ( $inactive_patient->num_rows > 0 ) {
            echo "<table class='table table-bordered'>";
            echo '<tr>
            <th>Staff</th>
            <th>Contacts</th>
            <th>Sender Name</th>
            <th>Sender Email</th>
            <th>Subject</th>
            <th>Body</th>
            <th>Status</th>
            <th>Send Date</th>
            <th>Follow Up Date</th>
            <th>Log</th>
            <th>Function</th>';
            echo "</tr>";

            while($row = mysqli_fetch_assoc($inactive_patient)) {
                $bookingid = $row['bookingid'];
                echo '<tr data-id="'.$row['ticketnotificationid'].'">';
                echo '<td data-title="Staff">';
                     $staff_list = [];
                    foreach (explode(',', $row['staffid']) as $noti_staffid) {
                        $staff_list[] = get_contact($dbc, $noti_staffid);
                    }
                    echo implode(', ', $staff_list);
                echo '</td>';
                echo '<td data-title="Contacts">';
                    $contacts_list = [];
                    foreach (explode(',', $row['contactid']) as $noti_contactid) {
                        $contacts_list[] = get_contact($dbc, $noti_contactid);
                    }
                    echo implode(', ', $contacts_list);
                echo '</td>';
                echo '<td data-title="Sender Name">'.$row['sender_name'].'</td>';
                echo '<td data-title="Sender Email">'.$row['sender_email'].'</td>';
                echo '<td data-title="Subject">'.$row['subject'].'</td>';
                echo '<td data-title="Body">'.html_entity_decode($row['email_body']).'</td>';
                echo '<td data-title="Status">'; ?>
                    <select name="noti_status" class="chosen-select-deselect form-control">
                        <option></option>
                        <option <?= $row['status'] == 'Pending' ? 'selected' : '' ?> value="Pending">Pending</option>
                        <option <?= $row['status'] == 'Sent' ? 'selected' : '' ?> value="Sent">Sent</option>
                        <option <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?> value="Cancelled">Cancelled</option>
                    </select>
                <?php echo '</td>';
                echo '<td data-title="Send Date"><input type="text" name="noti_send_date" class="form-control datepicker" value="'.$row['send_date'].'" onchange="update_send_date(this);"></td>';
                echo '<td data-title="Follow Up Date"><input type="text" name="noti_followup_date" class="form-control datepicker" value="'.$row['follow_up_date'].'" onchange="update_followup_date(this);"></td>';
                echo '<td data-title="Log">'.str_replace("\n", "<br>", $row['log']).'</td>';
                echo '<td data-title="Function"><a href="" onclick="send_now(this); return false;">Send Now</a> | <a href="" onclick="delete_notification(this); return false;">Delete</a>';
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