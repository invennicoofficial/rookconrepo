<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Time Tracking</h3>') ?>
<div class="col-md-12">
	<?php foreach($field_sort_order as $field_sort_field) { ?>
		<?php if(strpos($value_config, ',Time Tracking Estimate Complete,') !== FALSE && $field_sort_field == 'Time Tracking Estimate Complete') { ?>
			<div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Add Estimated Time to Complete <?= TICKET_NOUN ?>:<br/><em>Estimated Time: <?= substr($get_ticket['max_time'],0,-3) ?></em></label>
				<div class="col-sm-8">
					<?php if($access_all === TRUE) { ?>
						<input type="text" name="max_time_add" value="00:00" class="timepicker-5 form-control">
						<input name="max_time" type="hidden" value="<?= $get_ticket['max_time'] ?>" data-table="tickets" data-id-field="ticketid" data-id="<?= $ticketid ?>" />
					<?php } else {
						echo $get_ticket['max_time'];
					} ?>
				</div>
			</div>
			<?php $pdf_contents[] = ['Estimated Time', $get_ticket['max_time']]; ?>
		<?php } ?>

		<?php if(strpos($value_config, ',Time Tracking Estimate QA,') !== FALSE && $field_sort_field == 'Time Tracking Estimate QA') { ?>
			<div class="form-group">
				<label for="site_name" class="col-sm-4 control-label">Add Estimated Time to QA <?= TICKET_NOUN ?>:<br/><em>Estimated Time: <?= substr($get_ticket['max_qa_time'],0,-3) ?></em></label>
				<div class="col-sm-8">
					<?php if($access_all === TRUE) { ?>
						<input type="text" name="max_qa_time_add" value="00:00" class="timepicker-5 form-control">
						<input name="max_qa_time" type="hidden" value="<?= $get_ticket['max_qa_time'] ?>" data-table="tickets" data-id-field="ticketid" data-id="<?= $ticketid ?>" />
					<?php } else {
						echo $get_ticket['max_qa_time'];
					} ?>
				</div>
			</div>
			<?php $pdf_contents[] = ['Estimated QA Time', $get_ticket['max_qa_time']]; ?>
		<?php } ?>

		<?php if(strpos($value_config, ',Time Tracking Time Allotted,') !== FALSE && $field_sort_field == 'Time Tracking Time Allotted') { ?>
			<?php if($allotted_hours = mysqli_fetch_array(mysqli_query($dbc, "SELECT `minimum_billable` FROM `services` WHERE `heading`='$sub_heading' AND `category`='$service' AND `service_type`='$service_type'"))['minimum_billable']) { ?>
				<div class="form-group">
					<label for="site_name" class="col-sm-4 control-label">Time Allotted:</label>
					<div class="col-sm-8">
						<?php echo ($allotted_hours != '' ? $allotted_hours : 'No Time Set'); ?>
					</div>
				</div>
			<?php } ?>
			<?php $pdf_contents[] = ['Time Allotted', ($allotted_hours != '' ? $allotted_hours : 'No Time Set')]; ?>
		<?php } ?>

		<?php if(strpos($value_config, ',Time Tracking Current Time,') !== FALSE && $field_sort_field == 'Time Tracking Current Time') { ?>
			<div class="tracked_time_div"><?php include('ticket_time_tracking.php'); ?></div>
		<?php } ?>

		<?php if(strpos($value_config, ',Time Tracking Timer,') !== FALSE && $field_sort_field == 'Time Tracking Timer') { ?>
			<div class="ticket_timer_div" style="<?= empty($_GET['ticketid']) && empty($_GET['edit']) ? 'display: none;' : '' ?>">
				<?php if($access_all === TRUE) { ?>
					<?php if(strpos($value_config, ',Time Tracking Timer Manual,') !== FALSE){ ?>
						<div class="form-group">
							<label for="site_name" class="col-sm-4 control-label">Manually Track Time:</label>
							<div class="col-sm-8">
								<input name="manual_time_track" type="text" value="00:00" class="form-control timepicker" />
								<input name="spent_time" type="hidden" value="<?= $get_ticket['spent_time'] ?>" data-table="tickets" data-id-field="ticketid" data-id="<?= $ticketid ?>" />
							</div>
						</div>
					<?php } ?>
					<?php include ('add_ticket_timer.php');
				} ?>
			</div>
		<?php } ?>
	<?php } ?>
</div>

<?php function AddPlayTime2($times) {
    $minutes = 0;
    foreach ($times as $time) {
        $minutes += $time;
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;
    return sprintf('%02d:%02d', $hours, $minutes);
}

function AddPlayTime($times) {
    // loop throught all the times
    foreach ($times as $time) {
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    // returns the time already formatted
    return sprintf('%02d Hour %02d Minute', $hours, $minutes);
    //return sprintf('%02d:%02d', $hours, $minutes);
}
