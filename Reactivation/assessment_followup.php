<?php
/*
 * Assessment Follow-up
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
function set_followup(dropdown) {
	$.ajax({
		method: 'POST',
		url: '../ajax_all.php?fill=assessment_followup',
		data: { booking: $(dropdown).data('id'), followup: dropdown.value },
		success: function() {
		}
	});
}
$(document).on('change', 'select.set_followup_onchange', function() { set_followup(this); });
</script>
</head>
<body>
<?php include_once ('../navigation.php'); ?>

<div class="container triple-pad-bottom">
    <div class='iframe_holder' style='display:none;'>
		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
    
	<div class="row hide_on_iframe">
        <div class="col-md-12">
            
            <div class="row">
                <div class="col-xs-11"><h1 class="single-pad-bottom">Assessment Follow-Up Dashboard</h1></div>
                <div class="col-xs-1 double-gap-top"><?php
                    echo '<a href="config_reactivation.php"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
                </div>
            </div>

            <?php $value_config = ','.get_config($dbc, 'active_reactivation').','; ?>

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
                        <a href="assessment_followup.php"><button type="button" class="btn brand-btn mobile-block active_tab gap-left">Assessment Follow-Up</button></a><?php
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
            </div><!-- .tab-container --><?php
            
            $starttime_reset = false;
            
            if (isset($_POST['search_submit'])) {
                $starttime = $_POST['starttime'];
                $therapist = $_POST['therapist'];
                $patient   = $_POST['patient'];
            } else {
                $starttime = date('Y-m-d');
                $starttime_reset = true;
                $therapist = $_SESSION['contactid'];
                $patient   = '';
            }
            
            if (isset($_POST['display_all'])) {
                $starttime = date('Y-m-d');
                $starttime_reset = true;
                $therapist = $_SESSION['contactid'];
                $patient   = '';
            }

            if($starttime == 0000-00-00) {
                $starttime_reset = true;
                $starttime = date('Y-m-d');
            }
            
            $query_mod_starttime = ( $starttime_reset || $starttime==date('Y-m-d') ) ? "`booking`.`appoint_date`<'$starttime' AND" : "DATE(`booking`.`appoint_date`)='$starttime' AND";
            $query_mod_therapist = ( !empty($therapist) ) ? "`booking`.`therapistsid`='$therapist' AND" : "";
            $query_mod_patient   = ( !empty($patient) ) ? "`booking`.`patientid`='$patient' AND" : ""; ?>

            <form name="form_search" method="post" action="" class="form-horizontal" role="form">
                <div class="form-group double-gap-top">
                    <div class="col-sm-4">
                        <div class="col-sm-5 pad-5 text-right">Assessment Date:</div>
                        <div class="col-sm-7"><input name="starttime" type="text" class="datepicker form-control" value="<?= $starttime; ?>" /></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-3 pad-5 text-right">Patient:</div>
                        <div class="col-sm-9">
                            <select data-placeholder="Select a Staff..." name="patient" class="chosen-select-deselect form-control1" width="380">
                                <option value="">Select All</option><?php
                                $query = mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category`='Patient' AND `deleted`=0");
                                while ( $row=mysqli_fetch_array($query) ) {
                                    $selected = ( $patient==$row['contactid'] ) ? 'selected="selected"' : '';
                                    echo '<option '. $selected .' value="'. $row['contactid'] .'">'. decryptIt($row['first_name']) .' '. decryptIt($row['last_name']) .'</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-3 pad-5 text-right">Staff:</div>
                        <div class="col-sm-9">
                            <select data-placeholder="Select a Staff..." name="therapist" class="chosen-select-deselect form-control1" width="380">
                                <option value="">Select All</option><?php
                                foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status`=1 AND `deleted`=0")) as $row) {
                                    $selected = ( $therapist==$row['contactid'] ) ? 'selected="selected"' : '';
                                    echo '<option '. $selected .' value="'. $row['contactid'] .'">'. $row['first_name'] .' '. $row['last_name'] .'</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <button type="submit" name="search_submit" value="Search" class="btn brand-btn mobile-block pull-right">Search</button>
                        <button type="submit" name="display_all" value="Display All" class="btn brand-btn mobile-block pull-right">Display All</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
            
            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>" />
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>" />
            <input type="hidden" name="therapistpdf" value="<?php echo $therapist; ?>" /><?php
            
            $report_data = '<h3>Assessment Follow Ups: '.get_contact($dbc, $therapist).': '.$starttime.'</h3>';

            //$report_validation = mysqli_query($dbc, "SELECT `contacts`.`first_name`, `contacts`.`last_name`, `contacts`.`contactid`, `patient_injury`.`injury_name`, `patient_injury`.`injury_type`, `booking`.`appoint_date`, `booking`.`assessment_followup_date`, `booking`.`bookingid` FROM `booking` LEFT JOIN `contacts` ON `booking`.`patientid`=`contacts`.`contactid` LEFT JOIN `patient_injury` ON `booking`.`injuryid`=`patient_injury`.`injuryid` WHERE `booking`.`therapistsid`='$therapist' AND `booking`.`type` IN ('A','C','F','H','N','U') AND `assessment_followup_date` IS NULL AND `booking`.`deleted`=0 AND `booking`.`appoint_date` < '$starttime' AND `contacts`.`deleted`=0 ORDER BY `appoint_date` ASC");
            
            $report_validation = mysqli_query($dbc, "SELECT `contacts`.`first_name`, `contacts`.`last_name`, `contacts`.`contactid`, `patient_injury`.`injury_name`, `patient_injury`.`injury_type`, `booking`.`appoint_date`, `booking`.`assessment_followup_date`, `booking`.`bookingid` FROM `booking` LEFT JOIN `contacts` ON `booking`.`patientid`=`contacts`.`contactid` LEFT JOIN `patient_injury` ON `booking`.`injuryid`=`patient_injury`.`injuryid` WHERE ". $query_mod_therapist ." `booking`.`type` IN ('A','C','F','H','N','U') AND `assessment_followup_date` IS NULL AND ". $query_mod_patient ." `booking`.`deleted`=0 AND ". $query_mod_starttime ." `contacts`.`deleted`=0 ORDER BY `appoint_date` ASC");
            
            if ( $report_validation->num_rows > 0 ) {
                $data = 0;
                $html_table = '';
                $screen_mode = true;

                while ( $row_report=mysqli_fetch_array($report_validation) ) {
                    $data = 1;

                    $html_table .= '<tr nobr="true">';
                        $html_table .= '<td><a href="../Contacts/add_contacts.php?category=Patient&contactid='.$row_report['contactid'].'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'">'.decryptIt($row_report['first_name']).' '.decryptIt($row_report['last_name']). '</a></td>';
                        $html_table .= '<td>' . get_contact_phone($dbc, $row_report['contactid']) . '</td>';
                        $html_table .= '<td>' . $row_report['injury_name'].' : '.$row_report['injury_type'] . '</td>';
                        $html_table .= '<td>' . $row_report['appoint_date'] . '</td>';
                        $html_table .= '<td>'.($screen_mode ? '<select class="chosen-select form-control set_followup_onchange" data-id="'.$row_report['bookingid'].'"><option></option>
                                <option value="Complete">Follow Up Completed</option>
                                <option '.($row_report['assessment_followup_date'] == null ? 'selected' : '').' value="Incomplete">Not Complete</option>
                            </select>' : 'Not Complete').'</td>';
                    $html_table .= '</tr>';
                }

                if ( $data==1 ) {
                    $report_data .= '<table border="1px" class="table table-bordered">';
                        $report_data .= '
                            <tr nobr="true">
                                <th style="width:25%">Patient</th>
                                <th style="width:20%">Telephone</th>
                                <th style="width:20%">Injury</th>
                                <th style="width:20%">Appointment Date</th>
                                <th style="width:15%">Follow Up Status</th>
                            </tr>';
                    $report_data .= $html_table . '</table>';
                } else {
                    $report_data .= "No Assessment Follow Ups Outstanding";
                }

                echo $report_data;
            } else {
                echo '<h3>No records found.</h3>';
            } ?>
                
        </div><!-- .col-md-12 -->
    </div><!-- .hide_on_iframe -->
</div>
<?php include ('../footer.php'); ?>