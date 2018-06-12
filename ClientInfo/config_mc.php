<?php
error_reporting(0);
global $config;

$config['tile_name'] = 'Medical Charts';

$config['tabs'] = array (
    'Bowel Movement' => 'bowel_movement',
    'Seizure Record' => 'seizure_record',
    'Daily Water Temp' => 'daily_water_temp',
    'Blood Glucose' => 'blood_glucose'
);

/* Bowel Movement */
$config['settings']['Choose Fields for Bowel Movement']['config_field'] = 'bowel_movement';
$config['settings']['Choose Fields for Bowel Movement']['data'] = array(
	'Bowel Movement' => array(
			array('Client', 'dropdown', 'client', 'bowel_movement_'),
			array('Date', 'date', 'date', 'bowel_movement_'),
			array('Time', 'text', 'time', 'bowel_movement_'),
			array('BM', 'dropdown', 'bm', 'bowel_movement_'),
			array('Size', 'dropdown', 'size', 'bowel_movement_'),
			array('Form', 'dropdown', 'form', 'bowel_movement_'),
			array('Note', 'textarea', 'note', 'bowel_movement_'),
			array('Staff', 'dropdown', 'staff', 'bowel_movement_'),
			array('History', 'textarea', 'history', 'bowel_movement_'),
		)
);

$config['settings']['Choose Fields for Bowel Movement Dashboard']['config_field'] = 'bowel_movement_dashboard';
$config['settings']['Choose Fields for Bowel Movement Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'text', 'date'),
			array('Time', 'text', 'time'),
			array('BM', 'dropdown', 'bm'),
			array('Size', 'dropdown', 'size'),
			array('Form', 'dropdown', 'form'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		),
);

/* Seizure Record */
$config['settings']['Choose Fields for Seizure Record']['config_field'] = 'seizure_record';
$config['settings']['Choose Fields for Seizure Record']['data'] = array(
	'Seizure Record' => array(
			array('Client', 'dropdown', 'client', 'seizure_record_'),
			array('Date', 'date', 'date', 'seizure_record_'),
			array('Start Time', 'text', 'start_time', 'seizure_record_'),
			array('End Time', 'text', 'end_time', 'seizure_record_'),
			array('Type of Seizure', 'dropdown', 'form', 'seizure_record_'),
			array('Note', 'textarea', 'note', 'seizure_record_'),
			array('Staff', 'dropdown', 'staff', 'seizure_record_'),
			array('History', 'textarea', 'history', 'seizure_record_'),
		)
);

$config['settings']['Choose Fields for Seizure Record Dashboard']['config_field'] = 'seizure_record_dashboard';
$config['settings']['Choose Fields for Seizure Record Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Start Time', 'text', 'start_time'),
			array('End Time', 'text', 'end_time'),
			array('Type of Seizure', 'dropdown', 'form'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);

/* Daily Water Temp */
$config['settings']['Choose Fields for Daily Water Temp']['config_field'] = 'daily_water_temp';
$config['settings']['Choose Fields for Daily Water Temp']['data'] = array(
	'Daily Water Temp' => array(
			array('Client', 'dropdown', 'client', 'daily_water_temp_'),
			array('Date', 'date', 'date', 'daily_water_temp_'),
			array('Time', 'text', 'time', 'daily_water_temp_'),
			array('Water Temp', 'text', 'water_temp', 'daily_water_temp_'),
			array('Note', 'textarea', 'note', 'daily_water_temp_'),
			array('Staff', 'dropdown', 'staff', 'daily_water_temp_'),
			array('History', 'textarea', 'history', 'daily_water_temp_'),
		)
);

$config['settings']['Choose Fields for Daily Water Temp Dashboard']['config_field'] = 'daily_water_temp_dashboard';
$config['settings']['Choose Fields for Daily Water Temp Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('Water Temp', 'text', 'water_temp'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);


/* Blood Glucose */
$config['settings']['Choose Fields for Blood Glucose']['config_field'] = 'blood_glucose';
$config['settings']['Choose Fields for Blood Glucose']['data'] = array(
	'Blood Glucose' => array(
			array('Client', 'dropdown', 'client', 'blood_glucose_'),
			array('Date', 'date', 'date', 'blood_glucose_'),
			array('Time', 'text', 'time', 'blood_glucose_'),
			array('BG', 'text', 'bg', 'blood_glucose_'),
			array('Note', 'textarea', 'note', 'blood_glucose_'),
			array('Staff', 'dropdown', 'staff', 'blood_glucose_'),
			array('History', 'textarea', 'history', 'blood_glucose_'),
		)
);

$config['settings']['Choose Fields for Blood Glucose Dashboard']['config_field'] = 'blood_glucose_dashboard';
$config['settings']['Choose Fields for Blood Glucose Dashboard']['data'] = array(
	'General' => array(
			array('Client', 'dropdown', 'client'),
			array('Date', 'date', 'date'),
			array('Time', 'text', 'time'),
			array('BG', 'text', 'bg'),
			array('Note', 'textarea', 'note'),
			array('Staff', 'dropdown', 'staff'),
			array('History', 'textarea', 'history'),
		)
);