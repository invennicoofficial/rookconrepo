<?php
/*
Referrals : Send Patient a Promotion if reffers someone.
*/
include ('../include.php');
checkAuthorised('reactivation');
error_reporting(0);
if (isset($_POST['send_follow_up_email'])) {
    $reactive_email_date = date('Y-m-d');

    for($i = 0; $i < count($_POST['check_send_email']); $i++) {
        $bookingid = $_POST['check_send_email'][$i];
        $patientid = get_patient_from_booking($dbc, $bookingid, 'patientid');
        $email = get_email($dbc, $patientid);

        $query_update_patient = "UPDATE `booking` SET `reactive_email_date` = '$reactive_email_date' WHERE `bookingid` = '$bookingid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_patient);

        $email_body = html_entity_decode(get_config($dbc, 'inactive_reactivation_body'));
        $subject = get_config($dbc, 'inactive_reactivation_subject');

        //Mail
        if($email != '') {
            send_email([$_POST['email_sender_address']=>$_POST['email_sender_name']], $email, '', '', $subject, $email_body, '');
        }
        //Mail
    }

    echo '<script type="text/javascript"> alert("Reactivation Email Sent."); window.location.replace("inactive_reactivation.php"); </script>';
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
        <form name="form_clients" method="post" action="" class="form-inline" role="form">

        <div class="row">
            <div class="col-xs-11"><h1 class="single-pad-bottom">Inactive Reactivation Dashboard</div>
            <div class="col-xs-1 double-gap-top"><?php
                echo '<a href="config_reactivation.php" class="mobile-block pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>'; ?>
            </div>
        </div>

        <?php
        $value_config = ','.get_config($dbc, 'active_reactivation').',';
        ?>

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
                    <a href='inactive_reactivation.php'><button type="button" class="btn brand-btn mobile-block active_tab">Inactive Reactivation</button></a><?php
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
                    <a href='deactivated_contacts.php'><button type="button" class="btn brand-btn mobile-block">Deactivated Contacts</button></a><?php
                } else { ?>
                    <button type="button" class="btn disabled-btn mobile-block">Deactivated Contacts</button><?php
                } ?>
            </div>
            <div class="clearfix"></div>
        </div><!-- .tab-container -->

        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        This tile is used to ensure customers are followed up with when they have missed appointments, and/or to check in with them after their treatments are completed based on specified time ranges. Provides the ability for the front desk to reconnect with customers for follow up by email after a specific time frame.</div>
        <div class="clearfix"></div>
        </div>

        <?php
        $month3 = '';
        $month6 = '';
        $year1 = '';

        if(!empty($_GET['time'])) {
            $time = $_GET['time'];
            if($time == '3m') {
                $month3 = 'active_tab';
                $sql_mintime = '3 MONTH';
                $sql_maxtime = '6 MONTH';
            }
            if($time == '6m') {
                $month6 = 'active_tab';
                $sql_mintime = '6 MONTH';
                $sql_maxtime = '1 YEAR';
            }
            if($time == '1y') {
                $year1 = 'active_tab';
                $sql_mintime = '1 YEAR';
                $sql_maxtime = '5 YEAR';
            }
        }
        ?>

        <?php if (strpos($value_config, ','."3 Months".',') !== FALSE) { ?>
        <a href='inactive_reactivation.php?time=3m'><button type="button" class="btn brand-btn mobile-block <?php echo $month3; ?>" >3 Months</button></a>
        <?php } ?>
        <?php if (strpos($value_config, ','."6 Months".',') !== FALSE) { ?>
        <a href='inactive_reactivation.php?time=6m'><button type="button" class="btn brand-btn mobile-block <?php echo $month6; ?>" >6 Months</button></a>
        <?php } ?>
        <?php if (strpos($value_config, ','."1 Year".',') !== FALSE) { ?>
        <a href='inactive_reactivation.php?time=1y'><button type="button" class="btn brand-btn mobile-block <?php echo $year1; ?>" >1 Year+</button></a>
        <?php } ?>

        <br><br>

        <?php
        $inactive_patient =	mysqli_query($dbc,"SELECT MAX((str_to_date(substr(b.appoint_date,1,10),'%Y-%m-%d'))) AS max_date1, b.therapistsid, b.patientid, b.bookingid, b.reactive_email_date FROM contacts c, booking b WHERE c.contactid = b.patientid AND c.status=0 GROUP BY patientid");

        echo '<span class="pull-right"><h4>Select All&nbsp;<input type="checkbox" id="select_all" class="form-control" style="width:25px;"/></h4></span>';

        echo "<table class='table table-bordered'>";
        echo '<tr>
        <th>Staff</th>
        <th>Patient</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Last Appointment Date</th>
        <th>Last Email Sent Date</th>
        <th>Send Email</th>';
        echo "</tr>";

        while($row = mysqli_fetch_array($inactive_patient)) {
            if((strtotime($row['max_date1']) < strtotime('-'.$sql_mintime.'')) && (strtotime($row['max_date1']) > strtotime('-'.$sql_maxtime.''))) {
                echo "<tr>";
                echo '<td>' . get_contact($dbc, $row['therapistsid']) . '</td>';
                echo '<td>' . get_contact($dbc, $row['patientid']) . '</td>';
                echo '<td>' . get_email($dbc, $row['patientid']) . '</td>';
                echo '<td>' . get_contact_phone($dbc, $row['patientid']) . '</td>';
                echo '<td>' . $row['max_date1'] . '</td>';
                echo '<td>' . $row['reactive_email_date'] . '</td>';

                $patient_email = get_email($dbc, $row['patientid']);
                if($patient_email == '-' || $patient_email == '') {
                    echo '<td>-</td>';
                } else {
                    echo '<td><input name="check_send_email[]" type="checkbox" value="'.$row['bookingid'].'" class="form-control check_send_email" style="width:25px;"/></td>';
                }
                echo "</tr>";
            }
        }
        ?>
        </table>
        <button type="submit" name="send_follow_up_email" value="Submit" class="btn brand-btn btn-lg pull-right">Send Email</button>
		<div class="form-group">
			<label class="col-sm-4">Email Sender Name:</label>
			<div class="col-sm-8">
				<input type="text" name="email_sender_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Email Sender Address:</label>
			<div class="col-sm-8">
				<input type="text" name="email_sender_address" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>">
			</div>
		</div>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>