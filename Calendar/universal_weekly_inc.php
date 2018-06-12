<?php include_once('../include.php');
checkAuthorised('calendar_rook');
include_once('calendar_settings_inc.php');
include_once('calendar_functions_inc.php');
$calendar_start = $_GET['date'];
if($calendar_start == '') {
	$calendar_start = date('Y-m-d');
} else {
	$calendar_start = date('Y-m-d', strtotime($calendar_start));
}
$calendar_type = get_config($dbc, 'uni_wait_list');
$weekly_start = get_config($dbc, 'uni_weekly_start');
if($weekly_start == 'Sunday') {
	$weekly_start = 1;
} else {
	$weekly_start = 0;
}
$day = date('w', strtotime($calendar_start));
$week_start_date = date('F j', strtotime($calendar_start.' -'.($day - 1 + $weekly_start).' days'));
$week_end_date = date('F j, Y', strtotime($calendar_start.' -'.($day - 7 + $weekly_start).' days'));

$weekly_days = explode(',',get_config($dbc, 'uni_weekly_days'));
$all_contacts = array_filter(explode(',',get_user_settings()['appt_calendar_staff']));
if(!empty($_POST['all_contacts'])) {
	$all_contacts = $_POST['all_contacts'];
}
?>

<script src="appointments.js"></script>
<input type="hidden" name="calendar_date" value="<?= date('Y-m-d', strtotime($calendar_start)) ?>">
<?php $calendar_table = [];
$day_start = get_config($dbc, 'uni_day_start');
$day_end = get_config($dbc, 'uni_day_end');
$day_period = get_config($dbc, 'uni_increments');
$current_row = strtotime($day_start);
$appointment_calendar = 'weekly';
$calendar_table[0][0]['title'] = "Time";
if(get_config($dbc, 'uni_calendar_notes') == '1') { $calendar_table[0][0]['notes'] = "Notes"; }
if(get_config($dbc, 'uni_reminders') == '1') { $calendar_table[0][0]['reminders'] = "Reminders"; }
$calendar_table[0][0]['warnings'] = "Warnings";
while($current_row <= strtotime($day_end)) {
	$calendar_table[0][0][] = date('g:i a', $current_row);
	$current_row = strtotime('+'.$day_period.' minutes', $current_row);
}
$column_id = 0;
for($i = 1; $i <= 7; $i++) {
	foreach ($all_contacts as $contact_id) {
	    $calendar_date = date('Y-m-d', strtotime($calendar_start.' -'.($day - $i + $weekly_start).' days'));
	    $day_of_week = date('l', strtotime($calendar_date));
	    if(in_array($day_of_week, $weekly_days)) {
			$column_id++;
		    include('appointment_blocks.php');
		}
	}
}
include('appointment_display.php'); ?>