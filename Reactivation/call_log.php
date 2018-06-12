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
        $injuryid = get_patient_from_booking($dbc, $bookingid, 'injuryid');
        $injury_type = get_all_from_injury($dbc, $injuryid, 'injury_type');
        $email = get_email($dbc, $patientid);

        $query_update_patient = "UPDATE `booking` SET `reactive_email_date` = '$reactive_email_date' WHERE `bookingid` = '$bookingid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_patient);

        if (strpos($injury_type, 'Physical') !== false) {
            $email_body = html_entity_decode(get_config($dbc, 'physio_drop_off_analysis_body'));
            $subject = get_config($dbc, 'physio_drop_off_analysis_subject');
        } else {
            $email_body = html_entity_decode(get_config($dbc, 'massage_drop_off_analysis_body'));
            $subject = get_config($dbc, 'massage_drop_off_analysis_subject');
        }

        //Mail
        if($email != '') {
            send_email('', $email, '', '', $subject, $email_body, '');
        }
        //Mail
    }

    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];
    $therapistpdf = $_POST['therapistpdf'];

    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    $therapist = $therapistpdf;

    echo '<script type="text/javascript"> alert("Reactivation Email Sent."); window.location.replace("active_reactivation.php"); </script>';

    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
    $therapist = $therapistpdf;

}
?>
<script type="text/javascript">
$(document).ready(function() {
	$('.iframe_open').click(function(){
			var id = $(this).attr('id');
			var arr = id.split('_');
		    $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/notes.php?contactid='+arr[0]);
		    $('.iframe_title').text('View Patient');
			$('.hide_on_iframe').hide(1000);
			$('.iframe_holder').show(1000);
	});

	$('.close_iframer').click(function(){
				$('.iframe_holder').hide(1000);
				$('.hide_on_iframe').show(1000);
				location.reload();
	});
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">

        <div class="col-md-12">
        <form name="form_clients" method="post" action="" class="form-horizontal" role="form">

        <div class="row">
            <div class="col-xs-11"><h1 class="single-pad-bottom">Active Reactivation Dashboard</div>
            <div class="col-xs-1 double-gap-top"><?php
                echo '<a href="config_reactivation.php" class="mobile-block pull-right"><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
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
                    <a href='call_log.php'><button type="button" class="btn brand-btn mobile-block active_tab">Cold Call</button></a><?php
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
        Calls made by a Staff to restore a customer to Active Reactivation are stored here.</div>
        <div class="clearfix"></div>
        </div>

        <?php
        if (isset($_POST['search_email_submit'])) {
            $starttime = $_POST['starttime'];
            $endtime = $_POST['endtime'];
            $therapist = $_POST['therapist'];
        }
        if($starttime == 0000-00-00) {
            $starttime = date('Y-m-d', strtotime('-7 days'));
        }
        if($endtime == 0000-00-00) {
            $endtime = date('Y-m-d');
        }
        ?>
        <center><div class="form-group">
            Injury Created Date From: <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
            &nbsp;&nbsp;&nbsp;
            Injury Created Date Until: <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>">
            &nbsp;&nbsp;&nbsp;
            Staff:
            <select data-placeholder="Select a Staff..." name="therapist" class="chosen-select-deselect form-control1" width="380">
                <option value="">Select All</option>
                <?php foreach(sort_contacts_query(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status` > 0 AND deleted=0")) as $row) {
                    echo "<option ".($therapist == $row['contactid'] ? 'selected' : '')." value='". $row['contactid']."'>".$row['first_name'].' '.$row['last_name'].'</option>';
                }
                ?>
            </select>
            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            </div>
        </center>

        <br>
        <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
        <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">
        <input type="hidden" name="therapistpdf" value="<?php echo $therapist; ?>">

        <br><br>

        <?php

        if($therapist == '') {
            $result = mysqli_query($dbc, "SELECT * FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status` > 0 AND deleted=0");
        } else {
            $result = mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid='$therapist'");
        }

        while($row = mysqli_fetch_array($result)) {
            $therapistid = $row['contactid'];

            $report_validation = mysqli_query($dbc,"SELECT pi.injuryid, pi.injury_name, pi.injury_type, pi.contactid FROM patient_injury pi, contacts c WHERE pi.injury_therapistsid = '$therapistid' AND ((str_to_date(substr(pi.today_date,1,10),'%Y-%m-%d')) >= '".$starttime."' AND (str_to_date(substr(pi.today_date,1,10),'%Y-%m-%d')) <= '".$endtime."') AND pi.contactid = c.contactid AND pi.discharge_date IS NULL ORDER BY c.first_letter");

            $data = 0;
            $html_table = '';

            while($row_report = mysqli_fetch_array($report_validation)) {
                $injuryid = $row_report['injuryid'];

                $get_visit =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(bookingid) AS total_booking FROM	booking WHERE injuryid='$injuryid' AND (follow_up_call_status = 'Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status = 'Paid' OR follow_up_call_status = 'Invoiced')"));

                //if($get_visit['total_booking'] != 0) {

                    $get_arrived_first =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT MIN((str_to_date(substr(appoint_date,1,10),'%Y-%m-%d'))) AS first_arrived FROM booking WHERE injuryid='$injuryid' AND (follow_up_call_status = 'Arrived' OR follow_up_call_status='Completed' OR follow_up_call_status = 'Paid' OR follow_up_call_status = 'Invoiced')"));

                    $get_arrived =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT MAX((str_to_date(substr(b.appoint_date,1,10),'%Y-%m-%d'))) AS last_arrived, b.injuryid, b.patientid, b.therapistsid, b.bookingid, b.reactive_email_date FROM patient_injury pi, booking b WHERE pi.injuryid = b.injuryid AND pi.injuryid='$injuryid'"));

                    if(strtotime($get_arrived['last_arrived']) < strtotime(date('Y-m-d', strtotime('-7 days')))) {
                        $data = 1;

                        $html_table .= '<tr nobr="true">';
                        $html_table .= '<td>' . get_contact($dbc, $row_report['contactid']) . '</td>';
                        $html_table .= '<td>' . get_contact_phone($dbc, $row_report['contactid']) . '</td>';
                        $html_table .= '<td>' . get_email($dbc, $row_report['contactid']) . '</td>';

                        $html_table .= '<td>' . $row_report['injury_name'].' : '.$row_report['injury_type'] . '</td>';
                        //$html_table .= '<td>' . $get_visit['total_booking'] . '</td>';
                        $html_table .= '<td>' . $get_arrived_first['first_arrived'] . '</td>';
                        $html_table .= '<td>' . $get_arrived['last_arrived'] . '</td>';

                        $contactid = $row_report['contactid'];
                        $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(noteid) AS total_comment FROM notes WHERE contactid='$contactid' AND from_page='Reactivation'"));
                        $total_comment = $get_config['total_comment'];

					    $html_table .= '<td><a class="iframe_open" id="'.$row_report['contactid'].'">Add/View ('.$total_comment.')</a></td>';


                        //$html_table .= '<td><a href="../notes.php?id='..'">View' . $get_arrived['reactive_email_date'] . '</a></td>';
                        $html_table .= '</tr>';
                    }
                //}
            }

            if($data == 1) {
                $report_data .= '<h4>'.decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</h4><br>';
                $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
                $report_data .= '<tr style="'.$table_row_style.'" nobr="true">
                <th style="width:8%">Client</th>
                <th style="width:12%">Telephone</th>
                <th style="width:12%">Email</th>
                <th style="width:30%">Injury</th>
                <th style="width:8%">First Date Arrived</th>
                <th style="width:8%">Latest Booking</th>
                <th>Notes</th>';
                $report_data .= "</tr>";

                $report_data .= $html_table;

                $report_data .= '</table>';
            }
        }

        echo $report_data;
        ?>

        </form>
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>