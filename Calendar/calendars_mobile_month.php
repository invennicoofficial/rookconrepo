<?php
$headings = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
$all_calendar_dates = [];
?>
<table class="calendar-mobile-month">
	<tr>
		<?php foreach($headings as $heading) { ?>
			<th><?= $heading ?></th>
		<?php } ?>
	</tr>
	<?php $cur_day_i = 0;
	for($cur_day = $first_day; strtotime($cur_day) <= strtotime($last_day); $cur_day = date('Y-m-d', strtotime($cur_day.'+ 1 day'))) {
		$all_calendar_dates[] = $cur_day;
		if($cur_day_i == 0) {
			echo '<tr>';
		}
		if(date('n', strtotime($cur_day)) != $calendar_month || $cur_day_i == 0 || $cur_day_i == 6) {
			$td_color = '#aaaaaa';
		} else {
			$td_color = '#000000';
		}
		echo '<td style="color: '.$td_color.';" data-date="'.$cur_day.'" onclick="toggleMobileView(this);"><span class="calendar-mobile-date'.(date('Y-m-d') == $cur_day ? ' active' : '').'">'.date('j', strtotime($cur_day)).'</span></td>';
		$cur_day_i++;
		if($cur_day_i == 7) {
			echo '</tr>';
			$cur_day_i = 0;
		}
	} ?>
</table>
<input type="hidden" name="calendar_dates_mobile" value="<?= json_encode($all_calendar_dates) ?>">