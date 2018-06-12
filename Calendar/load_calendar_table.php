<?php $edit_access = vuaed_visible_function($dbc, 'calendar_rook');
if(!isset($day_start)) {
	$day_start = "6:00 am";
}
if(!isset($day_end)) {
	$day_end = "8:00 pm";
}
if(!isset($day_period)) {
	$day_period = 15;
}
if(!isset($contact_id)) {
	$contact_id = $_SESSION['contactid'];
}
if(!isset($calendar_date)) {
	$calendar_date = date('Y-m-d');
}
if(!isset($column_id)) {
	$column_id = 0;
}
if(!isset($day_of_week)) {
	$day_of_week = date('l');
}

if($ticket_summary == 1) {
	$calendar_table[0][0]['ticket_summary'] = TICKET_NOUN.' Summary';
}