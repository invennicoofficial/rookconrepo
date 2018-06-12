<style>
.popped-field {
	border: 1px solid rgb(221, 221, 221);
	font-size: 1.2em;
	max-width: 15em;
	padding: 1em;
}
.highlightCell {
	background-color: rgba(0,0,0,0.2);
}
a.shift {
	z-index: -1;
}
td {
	z-index: 1;
}
td:empty {
	z-index: 0;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $(window).resize(function() {
    	if ($(window).width() <= 479) {
    		$('.used-block').each(function() {
    			// $(this).css('margin-top', 'calc('+$(this).css('margin-top')+' + 0.1em)');
    		});
    	}
    }).resize();
});
</script>
<table class="table table_bordered <?= $appointment_calendar ?>" style="overflow-x: auto;">
	<?php foreach($calendar_table[0][0] as $calendar_row => $calendar_times) {
		if($calendar_row === 'warnings' || $calendar_row === 'reminders' || $calendar_row === 'notes') {
			echo '<tr class="calendar_notes_row">';
		} else {
			echo ($calendar_row === 'title' ? "<thead><tr style='position: absolute; z-index: 9;'>" : '<tr>');
		}
		foreach($calendar_table as $current_day => $calendar_col_date) {
			foreach($calendar_col_date as $contact_id => $calendar_col) {
	            if($calendar_row === 'title') {
					echo "<th data-contact='$contact_id' data-date='".$current_day."' data-row='title' style='".($contact_id > 0 ? 'border-left: 1px solid rgb(221, 221, 221); min-width: 15em; width: 50%;' : 'max-width: 7em; min-width: 7em; width: 7em;')."'>";
					echo ($current_day == 0 ? $calendar_col['title'] : ($_GET['view'] == 'daily' ? $calendar_col[$calendar_row] : date('l, F d', strtotime($current_day)).'<br>'.$calendar_col[$calendar_row]))."</th>";
				} else if ($contact_id > 0 && ($calendar_row === 'warnings' || $calendar_row === 'reminders' || $calendar_row === 'notes')) {
					echo "<td data-date='".$current_day."' data-calendartype='".$_GET['type']."' data-contact='$contact_id' style='position:relative; ".($contact_id > 0 ? 'border-left: 1px solid rgb(221, 221, 221); min-width: 15em; width: 50%;' : 'max-width: 7em; min-width: 7em; width: 7em;')."'><div class='calendar_notes' style='overflow-y: hidden;'>".$calendar_col[$calendar_row].'</div>';
					if($calendar_row == 'notes' && $contact_id != 0) {
						echo '<div class="calendar_notes_btn" style="text-align: right; position: relative;"><a class="edit_calendar_notes" href=""><sub>EDIT</sub></a></div>';
						echo '<div class="calendar_notes_edit" style="display:none;"><textarea style="resize: vertical;" class="noMceEditor form-control">'.html_entity_decode($calendar_col[$calendar_row]).'</textarea></div>';
					}
					echo '<a class="expand-div-link" href="" onclick="expandDiv(this); return false;"><div style="font-size: 1.5em; text-align: center;">...</div></a>';
				} else {
					$is_shift = '';
					if ($calendar_col[$calendar_row][1] == 'SHIFT') {
						$is_shift = ' background-color: #eee';
					}
					echo "<td data-date='".$current_day."' data-contact='$contact_id' data-time='$calendar_row' data-duration='".($day_period * 60)."' style='position:relative; ".($contact_id > 0 ? 'border-left: 1px solid rgb(221, 221, 221); min-width: 15em; width: 50%;' : 'max-width: 7em; min-width: 7em; width: 7em;').$is_shift."'>";
	                
	                if($contact_id > 0 && $calendar_col[$calendar_row] != '' && $calendar_col[$calendar_row] != $calendar_col[$calendar_row - 1]) {
	                    if($calendar_col[$calendar_row][0] == 'estimate') {
	                        $estimateid = $calendar_col[$calendar_row][1]['estimateid'];
	                        $estimate_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `estimate_name` FROM `estimate` WHERE `estimateid`='$estimateid'"));
	                        echo '<a href="'.WEBSITE_URL.'/Estimate/estimates.php?view='.$estimateid.'" onclick="overlayIFrameSlider('.WEBSITE_URL.'/Estimate/estimates.php?view='.$estimateid.'); return false;">Follow-Up: '. $estimate_name['estimate_name'] .'</a>';
	                    } /*else if ($calendar_col[$calendar_row][0] == 'shift') {
							$shift = $calendar_col[$calendar_row][1];
							$dayoff = $calendar_col[$calendar_row][2];
							$rows = 1;
							$shift_styling = '';
							$calendar_color = mysqli_fetch_array(mysqli_query($dbc, "SELECT `calendar_color` FROM `contacts` WHERE `contactid` = '".$shift['contactid']."'"))['calendar_color'];
							if (!empty($calendar_color)) {
								$shift_styling = ' background-color:'.$calendar_color.';';
							}
							if (!empty($shift)) {
								$rounded_starttime = strtotime($shift['starttime']) - (strtotime($shift['starttime']) % (60 * $day_period));
								$duration = (strtotime($shift['endtime']) - $rounded_starttime);
								if ($duration > $day_period * 60) {
									$rows = ceil($duration / ($day_period * 60));
								}
								$page_query['shiftid'] = $shift['shiftid'];
								echo "<a href='?".http_build_query($page_query)."'><div class='used-block' data-contact='$contact_id' data-blocks='$rows' data-row='$calendar_row' data-shift='".$shift['shiftid']."' ";
								echo "data-duration='$duration' style='height: calc(".(1.14*$rows)."em + ".(13*$rows)."px); margin: -0.3em 0 0 -0.7em; overflow-y: hidden; padding: 0.5em; position: absolute; width: 100%;".$shift_styling."'>";
								echo "<span class='shift' style='display: block; float: left; width: calc(100% - 2em);'>";
								echo "<b>".date('g:i a', strtotime($shift['starttime']))." - ".date('g:i a', strtotime($shift['endtime']))."</b><br />";
								echo "</span><img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='filter: brightness(200%); float: right; width: 2em;'></div></a>";
								unset($page_query['shiftid']);
							}
							
							if (!empty($dayoff)) {
								$rounded_starttime = strtotime($dayoff['starttime']) - (strtotime($dayoff['starttime']) % (60 * $day_period));
								$duration = (strtotime($dayoff['endtime']) - $rounded_starttime);
								if ($duration > $day_period * 60) {
									$rows = ceil($duration / ($day_period * 60));
								}
								$page_query['shiftid'] = $dayoff['shiftid'];
								echo "<a href='?".http_build_query($page_query)."'><div class='used-block' data-contact='$contact_id' data-blocks='$rows' data-row='$calendar_row' data-shift='".$dayoff['shiftid']."' ";
								echo "data-duration='$duration' style='height: calc(".(1.14*$rows)."em + ".(13*$rows)."px); margin: -0.3em 0 0 -0.7em; overflow-y: hidden; padding: 0.5em; position: absolute; width: 100%; background-color: #aaa;'>";
								echo "<span class='dayoff' style='display: block; float: left; width: calc(100% - 2em);'>";
								echo "<b>".date('g:i a', strtotime($dayoff['starttime']))." - ".date('g:i a', strtotime($dayoff['endtime']))."</b><br />";
								echo $dayoff['dayoff_type'];
								echo "</span><img class='drag-handle' src='".WEBSITE_URL."/img/icons/drag_handle.png' style='filter: brightness(200%); float: right; width: 2em;'></div></a>";
								unset($page_query['shiftid']);
							}
						} */
					} else if ($calendar_col[$calendar_row] != $calendar_col[$calendar_row - 1]) {
						echo $calendar_col[$calendar_row];
					}
					echo "</td>";
				}
				if($calendar_col[$calendar_row] != '' && $contact_id > 0 && $calendar_row != 'title') {
				}
			}
		}
		echo "</tr>".($calendar_row === 'title' ? "</thead>" : '');
	} ?>
</table>