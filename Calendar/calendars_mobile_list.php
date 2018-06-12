<table class="calendar-mobile-list">
	<?php $cur_day_i = 0;
	for($cur_day = $first_day; strtotime($cur_day) <= strtotime($last_day); $cur_day = date('Y-m-d', strtotime($cur_day.'+ 1 day'))) {
		$list_details = [];
		$total_tickets = $calendar_table[$cur_day][$contact_id]['total_tickets'];
		$total_appt = $calendar_table[$cur_day][$contact_id]['total_appt'];
		$total_shifts = $calendar_table[$cur_day][$contact_id]['total_shifts'];
		$total_dayoff = $calendar_table[$cur_day][$contact_id]['total_dayoff'];
		$total_estimates = $calendar_table[$cur_day][$contact_id]['total_estimates'];
		if($total_tickets > 0) {
			$list_details[] = $total_tickets.' '.($total_tickets > 1 ? TICKET_TILE : TICKET_NOUN);
		}
		if($total_appt > 0) {
			$list_details[] = $total_appt.' '.($total_appt > 1 ? 'Appointments' : 'Appointment');
		}
		if($total_shifts > 0) {
			$list_details[] = $total_shifts.' '.($total_shifts > 1 ? 'Shifts' : 'Shift');
		}
		if($total_dayoff > 0) {
			$list_details[] = $total_dayoff.' '.($total_dayoff > 1 ? 'Days Off' : 'Day Off');
		}
		if($total_esimates > 0) {
			$list_details[] = $total_esimates.' '.($total_esimates > 1 ? 'Estimates' : 'Estimate');
		}
		if(!empty($list_details)) {
			$list_details = implode(' / ', $list_details);
		} else {
			$list_details = '';
		}
		?>
		<tr class="list-row" data-date="<?= $cur_day ?>" <?= date('Y-m-d') == $cur_day ? '' : 'style="display:none;"' ?> onclick="toggleMobileView(this);">
			<td><div class="pull-left"><span class="list-date"><?= date('jS', strtotime($cur_day)) ?></span><br><b><?= date('D', strtotime($cur_day)) ?></b></div>
			<div class="pull-right list-details"><?= $list_details ?></div></td>
		</tr>
	<?php } ?>
</table>