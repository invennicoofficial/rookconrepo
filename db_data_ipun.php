<?php
	/*
	Dashboard
	FFM
	*/
?>

<?php
	//Ipun
	mysqli_query($dbc, "CREATE TABLE `social_story_protocols` (
  `protocol_id` int(11) NOT NULL,
  `support_contact_category` varchar(100) DEFAULT NULL,
  `support_contact` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `seizure_protocol_details` text,
  `seizure_upload` varchar(1000) DEFAULT NULL,
  `slip_fall_protocol_details` text,
  `slip_fall_upload` varchar(1000) DEFAULT NULL,
  `transfer_protocol_details` text,
  `transfer_upload` varchar(1000) DEFAULT NULL,
  `toileting_protocol_details` text,
  `toileting_upload` varchar(1000) DEFAULT NULL,
  `bathing_protocol_details` text,
  `bathing_upload` varchar(1000) DEFAULT NULL,
  `gtube_protocol_details` text,
  `gtube_upload` varchar(1000) DEFAULT NULL,
  `oxygen_protocol_details` text,
  `oxygen_upload` varchar(1000) DEFAULT NULL,
  `notes_protocol_details` text,
  `note_upload` varchar(1000) DEFAULT NULL
)");
	
	mysqli_query($dbc, "ALTER TABLE `contacts_description`  ADD `medications_daily_log_notes` TEXT
NULL  AFTER `medical_details_first_aid_cpr`,  ADD
`medications_management_comments` TEXT NULL  AFTER
`medications_daily_log_notes`");
	
	mysqli_query($dbc, "ALTER TABLE `contacts_dates`  ADD `medications_completed_date` DATE NULL AFTER
`birth_date`,  ADD `medications_management_completed_date` DATE NULL AFTER
`medications_completed_date`;");

	mysqli_query($dbc, "ALTER TABLE `contacts`  ADD `medications_start_time` VARCHAR(255) NULL  AFTER
`specialists_country`,  ADD `medications_end_time` VARCHAR(255) NULL  AFTER
`medications_start_time`,  ADD `medications_completed_by` VARCHAR(255) NULL
AFTER `medications_end_time`,  ADD `medications_signature_box` VARCHAR(255)
NULL  AFTER `medications_completed_by`,  ADD
`medications_management_completed_by` VARCHAR(255) NULL  AFTER
`medications_signature_box`,  ADD `medications_management_signature_box`
VARCHAR(255) NULL  AFTER `medications_management_completed_by`");


  mysqli_query($dbc, "ALTER TABLE `contacts`  ADD `protocols_start_time` VARCHAR(255) NOT NULL
AFTER `medications_management_signature_box`,  ADD `protocols_end_time`
VARCHAR(255) NOT NULL  AFTER `protocols_start_time`,  ADD
`protocols_completed_by` VARCHAR(255) NOT NULL  AFTER `protocols_end_time`,
ADD `protocols_signature_box` VARCHAR(255) NOT NULL  AFTER
`protocols_completed_by`,  ADD `protocols_management_completed_by`
VARCHAR(255) NOT NULL  AFTER `protocols_signature_box`,  ADD
`protocols_management_signature_box` VARCHAR(255) NOT NULL  AFTER
`protocols_management_completed_by`");


  mysqli_query($dbc, "ALTER TABLE `contacts_dates`  ADD `protocols_completed_date` DATE NULL  AFTER
`medications_management_completed_date`,  ADD
`protocols_management_completed_date` DATE NULL  AFTER
`protocols_completed_date`");

  mysqli_query($dbc, "ALTER TABLE `contacts_description`  ADD `seizure_protocol_details` TEXT NULL
AFTER `medications_management_comments`,  ADD `slip_fall_protocol_details`
TEXT NULL  AFTER `seizure_protocol_details`,  ADD `transfer_protocol_details`
TEXT NULL  AFTER `slip_fall_protocol_details`,  ADD
`toileting_protocol_details` TEXT NULL  AFTER `transfer_protocol_details`,
ADD `bathing_protocol_details` TEXT NULL  AFTER `toileting_protocol_details`,
ADD `gtube_protocol_details` TEXT NULL  AFTER `bathing_protocol_details`,  ADD
`oxygen_protocol_details` TEXT NULL  AFTER `gtube_protocol_details`,  ADD
`protocols_daily_log_notes` TEXT NULL  AFTER `oxygen_protocol_details`,  ADD
`protocols_management_comments` TEXT NULL  AFTER `protocols_daily_log_notes`");


  mysqli_query($dbc, "ALTER TABLE `contacts_upload`  ADD `seizure_protocol_upload` VARCHAR(1000)
NULL  AFTER `medical_details_support_documents`,  ADD
`slip_fall_protocol_upload` VARCHAR(1000) NULL  AFTER
`seizure_protocol_upload`,  ADD `transfer_protocol_upload` VARCHAR(1000) NULL
AFTER `slip_fall_protocol_upload`,  ADD `toileting_protocol_upload`
VARCHAR(1000) NULL  AFTER `transfer_protocol_upload`,  ADD
`bathing_protocol_upload` VARCHAR(1000) NULL  AFTER
`toileting_protocol_upload`,  ADD `gtube_protocol_upload` VARCHAR(1000) NULL
AFTER `bathing_protocol_upload`,  ADD `oxygen_protocol_upload` VARCHAR(1000)
NULL  AFTER `gtube_protocol_upload`");

mysqli_query($dbc, "ALTER TABLE `field_config` ADD `key_methodologies` TEXT NULL  AFTER `fund_development_funding`");

mysqli_query($dbc, "ALTER TABLE `field_config` ADD `key_methodologies_dashboard` TEXT NULL AFTER `key_methodologies`");

mysqli_query($dbc, "ALTER TABLE `field_config` ADD `protocols` TEXT NULL  AFTER `key_methodologies_dashboard`");

mysqli_query($dbc, "ALTER TABLE `field_config` ADD `protocols_dashboard` TEXT NULL  AFTER `protocols`");

mysqli_query($dbc, "ALTER TABLE `field_config` ADD `routines` TEXT NULL  AFTER `protocols_dashboard`,  ADD `routines_dashboard` TEXT NULL  AFTER `routines`");

mysqli_query($dbc, "CREATE TABLE `social_story_routines` (
  `routine_id` int(11) NOT NULL,
  `support_contact_category` varchar(255) DEFAULT NULL,
  `support_contact` varchar(255) DEFAULT NULL,
  `morning_routine` varchar(255) DEFAULT NULL,
  `morning_routine_upload` varchar(255) DEFAULT NULL,
  `afternoon_routine` varchar(255) DEFAULT NULL,
  `afternoon_routine_upload` varchar(255) DEFAULT NULL,
  `evening_routine` varchar(255) DEFAULT NULL,
  `evening_routine_upload` varchar(255) DEFAULT NULL,
  `first_aid_cpr` varchar(255) DEFAULT NULL,
  `first_aid_cpr_upload` varchar(255) DEFAULT NULL,
  `toileting_routine` varchar(255) DEFAULT NULL,
  `toileting_routine_upload` varchar(255) DEFAULT NULL,
  `bathing_routine` varchar(255) DEFAULT NULL,
  `bathing_routine_upload` varchar(255) DEFAULT NULL,
  `feeding_routine` varchar(255) DEFAULT NULL,
  `feeding_routine_upload` varchar(255) DEFAULT NULL,
  `notes_details` varchar(255) DEFAULT NULL,
  `notes_upload` varchar(255) DEFAULT NULL
)");


mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `communication` TEXT NULL  AFTER `routines_dashboard`,  ADD `communication_dashboard` TEXT NULL  AFTER `communication`");

mysqli_query($dbc, "CREATE TABLE `social_story_communication` (
  `communication_id` int(11) NOT NULL,
  `support_contact_category` varchar(255) DEFAULT NULL,
  `support_contact` varchar(255) DEFAULT NULL,
  `methods_morning_routine` varchar(255) DEFAULT NULL,
  `methods_morning_routine_upload` varchar(255) DEFAULT NULL,
  `techniques_morning_routine` varchar(255) DEFAULT NULL,
  `techniques_morning_routine_upload` varchar(255) DEFAULT NULL,
  `capabilities_morning_routine` varchar(255) DEFAULT NULL,
  `capabilities_morning_routine_upload` varchar(255) DEFAULT NULL,
  `inabilities_morning_routine` varchar(255) DEFAULT NULL,
  `inabilities_morning_routine_upload` varchar(255) DEFAULT NULL,
  `strategies_morning_routine` varchar(255) DEFAULT NULL,
  `strategies_morning_routine_upload` varchar(255) DEFAULT NULL,
  `notes_details` varchar(255) DEFAULT NULL,
  `notes_upload` varchar(255) DEFAULT NULL
)");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `activities` TEXT NULL  AFTER `communication_dashboard`,  ADD `activities_dashboard` TEXT NULL  AFTER `activities`");

mysqli_query($dbc, "CREATE TABLE `social_story_activities` (
  `activities_id` int(11) NOT NULL,
  `support_contact_category` varchar(255) DEFAULT NULL,
  `support_contact` varchar(255) DEFAULT NULL,
  `likes_morning_routine` varchar(255) DEFAULT NULL,
  `likes_morning_routine_upload` varchar(255) DEFAULT NULL,
  `wants_morning_routine` varchar(255) DEFAULT NULL,
  `wants_morning_routine_upload` varchar(255) DEFAULT NULL,
  `dreams_morning_routine` varchar(255) DEFAULT NULL,
  `dreams_morning_routine_upload` varchar(255) DEFAULT NULL,
  `aspirations_morning_routine` varchar(255) DEFAULT NULL,
  `aspirations_morning_routine_upload` varchar(255) DEFAULT NULL,
  `at_their_best_morning_routine` varchar(255) DEFAULT NULL,
  `at_their_best_morning_routine_upload` varchar(255) DEFAULT NULL,
  `dislikes_morning_routine` varchar(255) DEFAULT NULL,
  `dislikes_morning_routine_upload` varchar(255) DEFAULT NULL,
  `stressors_morning_routine` varchar(255) DEFAULT NULL,
  `stressors_morning_routine_upload` varchar(255) DEFAULT NULL,
  `at_their_worst_morning_routine` varchar(255) DEFAULT NULL,
  `at_their_worst_morning_routine_upload` varchar(255) DEFAULT NULL,
  `triggers_morning_routine` varchar(255) DEFAULT NULL,
  `triggers_morning_routine_upload` varchar(255) DEFAULT NULL,
  `strategies_that_work_morning_routine` varchar(255) DEFAULT NULL,
  `strategies_that_work_morning_routine_upload` varchar(255) DEFAULT NULL,
  `strategies_that_dont_work_morning_routine` varchar(255) DEFAULT NULL,
  `strategies_that_dont_work_morning_routine_upload` varchar(255) DEFAULT NULL,
  `notes_details` varchar(255) DEFAULT NULL,
  `notes_upload` varchar(255) DEFAULT NULL
)");

mysqli_query($dbc, "ALTER TABLE `contacts`  ADD `routines_start_time` VARCHAR(255) NULL  AFTER `protocols_management_signature_box`,  ADD `routines_end_time` VARCHAR(255) NULL  AFTER `routines_start_time`,  ADD `routines_completed_by` VARCHAR(255) NULL  AFTER `routines_end_time`,  ADD `routines_signature_box` VARCHAR(255) NULL  AFTER `routines_completed_by`,  ADD `routines_management_completed_by` VARCHAR(255) NULL  AFTER `routines_signature_box`,  ADD `routines_management_signature_box` VARCHAR(255) NULL  AFTER `routines_management_completed_by`,  ADD `communication_start_time` VARCHAR(255) NULL  AFTER `routines_management_signature_box`,  ADD `communication_end_time` VARCHAR(255) NULL  AFTER `communication_start_time`,  ADD `communication_completed_by` VARCHAR(255) NULL  AFTER `communication_end_time`,  ADD `communication_signature_box` VARCHAR(255) NULL  AFTER `communication_completed_by`,  ADD `communication_management_completed_by` VARCHAR(255) NULL  AFTER `communication_signature_box`,  ADD `communication_management_signature_box` VARCHAR(255) NULL  AFTER `communication_management_completed_by`,  ADD `activities_start_time` VARCHAR(255) NULL  AFTER `communication_management_signature_box`,  ADD `activities_end_time` VARCHAR(255) NULL  AFTER `activities_start_time`,  ADD `activities_completed_by` VARCHAR(255) NULL  AFTER `activities_end_time`,  ADD `activities_signature_box` VARCHAR(255) NULL  AFTER `activities_completed_by`,  ADD `activities_management_completed_by` VARCHAR(255) NULL  AFTER `activities_signature_box`,  ADD `activities_management_signature_box` VARCHAR(255) NULL  AFTER `activities_management_completed_by`");

mysqli_query($dbc, "ALTER TABLE `contacts_description`  ADD `routines_daily_log_notes` TEXT NULL  AFTER `protocols_management_comments`,  ADD `routines_management_comments` TEXT NULL  AFTER `routines_daily_log_notes`,  ADD `communication_daily_log_notes` TEXT NULL  AFTER `routines_management_comments`,  ADD `communication_management_comments` TEXT NULL  AFTER `communication_daily_log_notes`,  ADD `activities_daily_log_notes` TEXT NULL  AFTER `communication_management_comments`,  ADD `activities_management_comments` TEXT NULL  AFTER `activities_daily_log_notes`");


mysqli_query($dbc, "ALTER TABLE `contacts_dates`  ADD `routines_completed_date` DATE NULL  AFTER `protocols_management_completed_date`,  ADD `routines_management_completed_date` DATE NULL  AFTER `routines_completed_date`,  ADD `communication_completed_date` DATE NULL  AFTER `routines_management_completed_date`,  ADD `communication_management_completed_date` DATE NULL  AFTER `communication_completed_date`,  ADD `activities_completed_date` DATE NULL  AFTER `communication_management_completed_date`,  ADD `activities_management_completed_date` DATE NULL  AFTER `activities_completed_date`");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `bowel_movement` TEXT NULL  AFTER `activities_dashboard`,  ADD `bowel_movement_dashboard` TEXT NULL  AFTER `bowel_movement`");

mysqli_query($dbc, "CREATE TABLE `bowel_movement` (
  `bowel_movement_id` int(11) NOT NULL,
  `client` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `bm` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `form` varchar(255) DEFAULT NULL,
  `note` text,
  `staff` varchar(255) DEFAULT NULL,
  `history` text
)");

mysqli_query($dbc, "ALTER TABLE `bowel_movement`
  ADD PRIMARY KEY (`bowel_movement_id`)");

mysqli_query($dbc, "ALTER TABLE `bowel_movement`
MODIFY `bowel_movement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `seizure_record` TEXT NULL  AFTER `bowel_movement_dashboard`,  ADD `seizure_record_dashboard` TEXT NULL  AFTER `seizure_record`");

mysqli_query($dbc, "CREATE TABLE `seizure_record` (
  `seizure_record_id` int(11) NOT NULL,
  `client` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` varchar(255) NOT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `form` varchar(255) DEFAULT NULL,
  `note` text,
  `staff` varchar(255) DEFAULT NULL,
  `history` text
)");

mysqli_query($dbc, "ALTER TABLE `seizure_record`
  ADD PRIMARY KEY (`seizure_record_id`)");

mysqli_query($dbc, "ALTER TABLE `seizure_record`
MODIFY `seizure_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `daily_water_temp` TEXT NULL  AFTER `seizure_record_dashboard`,  ADD `daily_water_temp_dashboard` TEXT NULL  AFTER `daily_water_temp`");

mysqli_query($dbc, "CREATE TABLE `daily_water_temp` (
  `daily_water_temp_id` int(11) NOT NULL,
  `client` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `water_temp` text,
  `note` text,
  `staff` varchar(255) DEFAULT NULL,
  `history` text
)");

mysqli_query($dbc, "ALTER TABLE `daily_water_temp`
  ADD PRIMARY KEY (`daily_water_temp_id`)");

mysqli_query($dbc, "ALTER TABLE `daily_water_temp`
MODIFY `daily_water_temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `blood_glucose` TEXT NULL  AFTER `daily_water_temp_dashboard`,  ADD `blood_glucose_dashboard` TEXT NULL  AFTER `blood_glucose`");

mysqli_query($dbc, "CREATE TABLE `blood_glucose` (
  `blood_glucose_id` int(11) NOT NULL,
  `client` varchar(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `bg` text,
  `note` text,
  `staff` varchar(255) DEFAULT NULL,
  `history` text
)");

mysqli_query($dbc, "ALTER TABLE `blood_glucose`
  ADD PRIMARY KEY (`blood_glucose_id`)");

mysqli_query($dbc, "ALTER TABLE `blood_glucose`
MODIFY `blood_glucose_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4");

mysqli_query($dbc, "ALTER TABLE `admin_tile_config`  ADD `charts` VARCHAR(500) NULL  AFTER `scrum`");

mysqli_query($dbc, "ALTER TABLE `tile_config`  ADD `charts` VARCHAR(500) NULL  AFTER `scrum`");

mysqli_query($dbc, "ALTER TABLE `fund_development_funder`
  ADD PRIMARY KEY (`fundersid`)");

mysqli_query($dbc, "ALTER TABLE `fund_development_funder`
  MODIFY `fundersid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2");

mysqli_query($dbc, "ALTER TABLE `fund_development_funder` ADD `uploads` TEXT NULL  AFTER `country`");

mysqli_query($dbc, "ALTER TABLE `social_story_protocols`  ADD `incident_upload` TEXT NULL  AFTER `note_upload`");

mysqli_query($dbc, "ALTER TABLE `social_story_protocols`  ADD `incident_widget` TEXT NULL  AFTER `incident_upload`");

mysqli_query($dbc, "ALTER TABLE `social_story_routines`  ADD `incident_upload` TEXT NULL  AFTER `notes_upload`,  ADD `incident_widget` TEXT NULL  AFTER `incident_upload`");

mysqli_query($dbc, "ALTER TABLE `social_story_communication`  ADD `incident_widget` TEXT NULL  AFTER `notes_upload`,  ADD `incident_upload` TEXT NULL  AFTER `incident_widget`");

mysqli_query($dbc, "ALTER TABLE `social_story_activities`  ADD `incident_widget` TEXT NULL  AFTER `notes_upload`,  ADD `incident_upload` TEXT NULL  AFTER `incident_widget`");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `daily_log_notes` TEXT NULL  AFTER `blood_glucose_dashboard`,  ADD `daily_log_notes_dashboard` TEXT NULL  AFTER `daily_log_notes`");

mysqli_query($dbc, "CREATE TABLE `daily_log_notes` (
  `note_id` int(11) NOT NULL,
  `notes` text,
  `staff` int(11) DEFAULT NULL,
  `completed_date` varchar(255) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `completed_by` varchar(255) DEFAULT NULL,
  `signature_box` text
)");

mysqli_query($dbc, "ALTER TABLE `daily_log_notes`
  ADD PRIMARY KEY (`note_id`)");

mysqli_query($dbc, "ALTER TABLE `daily_log_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2");

mysqli_query($dbc, "ALTER TABLE `admin_tile_config`  ADD `daily_log_notes` VARCHAR(500) NULL  AFTER `charts`");
mysqli_query($dbc, "ALTER TABLE `tile_config`  ADD `daily_log_notes` VARCHAR(500) NULL  AFTER `charts`");

mysqli_query($dbc, "ALTER TABLE `admin_tile_config`  ADD `timesheet` VARCHAR(500) NULL  AFTER `charts`");
mysqli_query($dbc, "ALTER TABLE `tile_config`  ADD `timesheet` VARCHAR(500) NULL  AFTER `charts`");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `time_cards` TEXT NULL  AFTER `daily_log_notes_dashboard`,  ADD `time_cards_dashboard` TEXT NULL  AFTER `time_cards`");

mysqli_query($dbc, "CREATE TABLE `time_cards` (
  `time_cards_id` int(11) NOT NULL,
  `business` varchar(255) DEFAULT NULL,
  `staff` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `type_of_time` varchar(255) DEFAULT NULL,
  `total_hrs` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");


mysqli_query($dbc, "ALTER TABLE `time_cards`
  ADD PRIMARY KEY (`time_cards_id`)");

mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `holidays` TEXT NULL  AFTER `time_cards_dashboard`,  ADD `holidays_dashboard` TEXT NULL  AFTER `holidays`");

mysqli_query($dbc, "CREATE TABLE `holidays` (
  `holidays_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");

mysqli_query($dbc, "ALTER TABLE `holidays`
  ADD PRIMARY KEY (`holidays_id`,`name`)");


mysqli_query($dbc, "ALTER TABLE `field_config`  ADD `pay_period` TEXT NULL  AFTER `holidays_dashboard`,  ADD `pay_period_dashboard` TEXT NULL  AFTER `pay_period`");

mysqli_query($dbc, "CREATE TABLE `pay_period` (
  `pay_period_id` int(11) NOT NULL,
  `staff` varchar(255) DEFAULT NULL,
  `staff_group` varchar(255) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL,
  `pay_period` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1");

mysqli_query($dbc, "ALTER TABLE `pay_period`
  ADD PRIMARY KEY (`pay_period_id`)");

mysqli_query($dbc, "ALTER TABLE `holidays`
  MODIFY `holidays_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2");

mysqli_query($dbc, "ALTER TABLE `pay_period`
  MODIFY `pay_period_id` int(11) NOT NULL AUTO_INCREMENT");

mysqli_query($dbc, "ALTER TABLE `time_cards`
  MODIFY `time_cards_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2");

	echo 'DB Data #3 Done<br />';
?>