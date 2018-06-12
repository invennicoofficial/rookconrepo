<?php
// List all function here
function reports_therapist($dbc) { ?>
    <?php
       // echo '<a href="config_reports.php" class="btn brand-btn mobile-block pull-right  config-btn">Tile Configurator</a><br><br>';
    ?>
    <?php
    $role = $_SESSION['role'];
    $active_daysheet = '';
    $active_history = '';
    $active_bb = '';
    $active_comp = '';
    $active_tb = '';

    $file_name = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
    if($file_name == 'report_daysheet.php') {
        $active_daysheet = 'active_tab';
    }
    if($file_name == 'report_patient_appoint_history.php') {
        $active_history = 'active_tab';
    }
    if($file_name == 'report_patient_block_booking.php') {
        $active_bb = 'active_tab';
    }
    if($file_name == 'report_compensation.php') {
        $active_comp = 'active_tab';
    }
    if($file_name == 'report_tally_board.php') {
        $active_tb = 'active_tab';
    }
    ?>

    <span>
		<span class="popover-examples list-inline" style="margin:0 0 0 5px;"><a data-toggle="tooltip" data-placement="top" title="Displays appointment information for the selected day."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
		if (check_subtab_persmission($dbc, 'therapist', ROLE, 'daysheet') === true) { ?>
            <a href='report_daysheet.php?type=Per'><button type="button" class="btn brand-btn mobile-block <?php echo $active_daysheet; ?>">Day Sheet</button></a><?php
        } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Day Sheet</button><?php
        } ?>
	</span>

    <span>
		<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Displays a full appointment history by patient for the selected day."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
		if (check_subtab_persmission($dbc, 'therapist', ROLE, 'history') === true) { ?>
            <a href='report_patient_appoint_history.php?type=Per'><button type="button" class="btn brand-btn mobile-block <?php echo $active_history; ?>">Patient History</button></a><?php
        } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Patient History</button><?php
        } ?>
	</span>

    <span>
		<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Displays patient future block booked appointments."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
		if (check_subtab_persmission($dbc, 'therapist', ROLE, 'block_booking') === true) { ?>
            <a href='report_patient_block_booking.php?type=Per'><button type="button" class="btn brand-btn mobile-block <?php echo $active_bb; ?>">Patient Block Booking</button></a><?php
        } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Patient Block Booking</button><?php
        } ?>
	</span>

    <span>
		<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Displays the entire history of compensation for the selected date range."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
		if (check_subtab_persmission($dbc, 'therapist', ROLE, 'compensation') === true) { ?>
            <a href='report_compensation.php?type=Per'><button type="button" class="btn brand-btn mobile-block <?php echo $active_comp; ?>" >Compensation</button></a><?php
        } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Compensation</button><?php
        } ?>
	</span>

    <span>
		<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Displays your total appointments for the selected date range."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
		if (check_subtab_persmission($dbc, 'therapist', ROLE, 'summary') === true) { ?>
            <a href='report_tally_board.php?type=Per'><button type="button" class="btn brand-btn mobile-block <?php echo $active_tb; ?>" >Appointment Summary</button></a><?php
        } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Appointment Summary</button><?php
        } ?>
	</span>

<?php
}