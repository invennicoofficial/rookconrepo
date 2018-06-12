<?php include_once('include.php');
// These changes have not been made for Nose Creek -- Jonathan
	//July 5, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `appt_calendar_contacts` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 6, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `appt_calendar_equipment` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 11, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `holidays` ADD `paid` BOOLEAN NOT NULL DEFAULT 1")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `holidays` ADD `deleted` BOOLEAN NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	echo "Updating Stat Holidays to Timesheet Holidays...<br />\n";
	$stat_holidays = explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT `value` FROM `general_configuration` WHERE `name`='stat_holiday'"))['value']);
	foreach($stat_holidays as $holiday) {
		if($holiday != '') {
			if(!mysqli_query($dbc, "INSERT INTO `holidays` (`date`) SELECT '$holiday' FROM (SELECT COUNT(*) rows FROM `holidays` WHERE `date`='$holiday') num WHERE num.rows=0")) {
				echo "Error: ".mysqli_error($dbc)."<br />\n";
			}
		}
	}
	
	//July 13, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `contacts_security` (
		`security_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`region_access` TEXT
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `waitlist` ADD `end_wait_date` DATE AFTER `desired_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `waitlist` ADD `start_time` TIME AFTER `end_wait_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `waitlist` ADD `end_time` TIME AFTER `start_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `waitlist` ADD `available_days` TEXT AFTER `end_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `waitlist` ADD `injuryid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `patientid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `waitlist` ADD `appt_type` VARCHAR(4) NOT NULL DEFAULT '' AFTER `therapistsid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 17, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `checklist` ADD `alerts_enabled` TEXT AFTER `flag_colour`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `checklist_name` ADD `alerts_enabled` TEXT AFTER `flag_colour`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 18, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `table_locks` (
		`lock_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`table_name` VARCHAR(40) NOT NULL DEFAULT '',
		`tab_name` VARCHAR(40) NOT NULL DEFAULT '',
		`user_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`table_row_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`locked_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 25, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `google_plus` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `twitter`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `instagram` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `google_plus`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `pinterest` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `instagram`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `youtube` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `pinterest`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `blog` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `youtube`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `priority` VARCHAR(40) NOT NULL DEFAULT '' AFTER `classification`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `member_support_documents` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `client_support_documents`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 27, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `estimate_templates` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`template_name` VARCHAR(40) NOT NULL DEFAULT '',
		`region` VARCHAR(40) NOT NULL DEFAULT '',
		`location` VARCHAR(40) NOT NULL DEFAULT '',
		`classification` VARCHAR(40) NOT NULL DEFAULT '',
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `estimate_template_headings` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`template_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`heading_name` VARCHAR(40) NOT NULL DEFAULT '',
		`sort_order` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `estimate_template_lines` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`heading_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`description` VARCHAR(100) NOT NULL DEFAULT '',
		`src_table` VARCHAR(40) NOT NULL DEFAULT '',
		`src_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`qty` DECIMAL(10,4) NOT NULL DEFAULT 0,
		`sort_order` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 28, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `rate_card_estimate_scopes` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`template_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`rate_card_name` VARCHAR(40) NOT NULL DEFAULT '',
		`region` VARCHAR(40) NOT NULL DEFAULT '',
		`location` VARCHAR(40) NOT NULL DEFAULT '',
		`classification` VARCHAR(40) NOT NULL DEFAULT '',
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `rate_card_estimate_scope_lines` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`rate_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`line_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`uom` VARCHAR(50) NOT NULL DEFAULT '',
		`cost` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`cust_price` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`retail_rate` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`profit` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`margin` DECIMAL(8,4) NOT NULL DEFAULT 0,
		`daily` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`hourly` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `item_id` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `description`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//July 31, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `estimate_actions` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`estimateid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`action` VARCHAR(40) NOT NULL DEFAULT '',
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`due_date` DATE NOT NULL DEFAULT '0000-00-00',
		`created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_actions` ADD `completed` INT(1) NOT NULL DEFAULT 0 AFTER `created_by`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `estimate_notes` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`estimateid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`heading` VARCHAR(40) NOT NULL DEFAULT '',
		`notes` text,
		`note_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`assigned` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 1, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_document` ADD `link` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_document` ADD `label` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_document` ADD `created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_document` ADD `deleted` INT(1) NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 2, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `estimate_scope` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`estimateid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`templateid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`templateline` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`heading` VARCHAR(100) NOT NULL DEFAULT '',
		`description` VARCHAR(100) NOT NULL DEFAULT '',
		`src_table` VARCHAR(40) NOT NULL DEFAULT '',
		`src_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`rate_card` VARCHAR(20) NOT NULL DEFAULT '',
		`uom` VARCHAR(10) NOT NULL DEFAULT '',
		`qty` DECIMAL(10,4) NOT NULL DEFAULT 0,
		`cost` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`profit` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`margin` DECIMAL(8,4) NOT NULL DEFAULT 0,
		`price` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`retail` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`multiple` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`sort_order` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 4, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `favourite` INT(1) NOT NULL DEFAULT 0 AFTER `status`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `project_actions` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`action` VARCHAR(40) NOT NULL DEFAULT '',
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`due_date` DATE NOT NULL DEFAULT '0000-00-00',
		`created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`completed` INT(1) NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `siteid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 9, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `milestone` VARCHAR(100) NOT NULL DEFAULT '' AFTER `assign_work`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 10, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `sales` ADD `region` VARCHAR(100) NOT NULL DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `sales` ADD `location` VARCHAR(100) NOT NULL DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `sales` ADD `classification` VARCHAR(100) NOT NULL DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 11, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project_document` ADD `created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `link`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project_document` ADD `category` VARCHAR(100) NOT NULL DEFAULT '' AFTER `label`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `project_scope` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`estimateline` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`heading` VARCHAR(100) NOT NULL DEFAULT '',
		`description` VARCHAR(100) NOT NULL DEFAULT '',
		`src_table` VARCHAR(40) NOT NULL DEFAULT '',
		`src_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`rate_card` VARCHAR(20) NOT NULL DEFAULT '',
		`uom` VARCHAR(10) NOT NULL DEFAULT '',
		`qty` DECIMAL(10,4) NOT NULL DEFAULT 0,
		`cost` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`profit` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`margin` DECIMAL(8,4) NOT NULL DEFAULT 0,
		`price` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`retail` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`multiple` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`attach_type` VARCHAR(20) NOT NULL DEFAULT '',
		`attach_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`sort_order` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 14, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `project_billable` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`heading` VARCHAR(100) NOT NULL DEFAULT '',
		`description` VARCHAR(100) NOT NULL DEFAULT '',
		`billable_table` VARCHAR(40) NOT NULL DEFAULT '',
		`billable_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`uom` VARCHAR(10) NOT NULL DEFAULT '',
		`qty` DECIMAL(10,4) NOT NULL DEFAULT 0,
		`cost` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`profit` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`margin` DECIMAL(8,4) NOT NULL DEFAULT 0,
		`price` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`retail` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`bill_type` VARCHAR(20) NOT NULL DEFAULT '',
		`bill_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`sort_order` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice_lines` ADD `uom` VARCHAR(20) NOT NULL DEFAULT '' AFTER `unit_price`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice_lines` ADD `admin_fee` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `unit_price`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice_lines` ADD `compensation` INT(1) NOT NULL DEFAULT 1 AFTER `category`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice` ADD `tile_name` VARCHAR(40) NOT NULL DEFAULT 'invoice' AFTER `invoice_type`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 15, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project_manage_assign_to_timer` ADD `regular_hrs` TIME NOT NULL DEFAULT '00:00:00' AFTER `timer`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project_manage_assign_to_timer` ADD `overtime_hrs` TIME NOT NULL DEFAULT '00:00:00' AFTER `regular_hrs`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project_manage_assign_to_timer` ADD `travel_hrs` TIME NOT NULL DEFAULT '00:00:00' AFTER `overtime_hrs`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project_manage_assign_to_timer` ADD `subsist_hrs` TIME NOT NULL DEFAULT '00:00:00' AFTER `travel_hrs`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "UPDATE `project_manage_assign_to_timer` SET `regular_hrs`=`timer` WHERE `regular_hrs`='00:00:00' AND `timer`!=`regular_hrs` AND `timer` IS NOT NULL")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project_billable` ADD `is_billable` INT(1) NOT NULL DEFAULT '1' AFTER `sort_order`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice` ADD `projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `patientid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice` ADD `businessid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `injuryid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice_lines` ADD `heading` VARCHAR(100) NOT NULL DEFAULT '' AFTER `category`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 16, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `field_config_project` ADD `config_tabs` TEXT AFTER `config_fields`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `business`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `projectid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `timer_start` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `type_of_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `start_time` TIME NOT NULL DEFAULT '00:00' AFTER `timer_start`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `end_time` TIME NOT NULL DEFAULT '00:00' AFTER `start_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice` ADD `invoice_file` TEXT AFTER `invoice_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `invoice_payment` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`invoiceid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`line_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`payer_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`description` TEXT,
		`paid` INT(1) NOT NULL DEFAULT 0,
		`payment_method` VARCHAR(20) NOT NULL DEFAULT '',
		`deposit_number` VARCHAR(20) NOT NULL DEFAULT '',
		`date_paid` DATE NOT NULL DEFAULT '0000-00-00',
		`date_deposited` DATE NOT NULL DEFAULT '0000-00-00',
		`collection` INT(1) NOT NULL DEFAULT 0,
		`grouped_invoiceid` INT(11) DEFAULT NULL,
		`receipt_file` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 17, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `invoice_lines` ADD `tax_exempt` INT(1) NOT NULL DEFAULT 0 AFTER `uom`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice_lines` ADD `type` VARCHAR(10) NOT NULL DEFAULT 'General' AFTER `item_id`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `package` ADD `gst_exempt` INT(1) NOT NULL DEFAULT 0 AFTER `cost`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 21, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `invoice_payment` ADD `gst` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `payer_id`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	echo "Creating Payment Records for Invoices...<br />\n";
	include('db_update_invoices.php');
	
	//August 22, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `siteid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `clientid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `siteid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `clientid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `siteid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `clientid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `piece_work` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `service`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `location` VARCHAR(40) NOT NULL DEFAULT 0 AFTER `siteid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `location` VARCHAR(40) NOT NULL DEFAULT 0 AFTER `siteid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `notes` VARCHAR(40) NOT NULL DEFAULT 0 AFTER `assign_work`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `notes` VARCHAR(40) NOT NULL DEFAULT 0 AFTER `assign_work`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `address` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `location`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `address` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `location`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `google_maps` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `google_maps` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `site_location` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `google_maps`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `site_location` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `google_maps`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `lsd` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `site_location`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `lsd` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `site_location`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_attached` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`tile_name` VARCHAR(10) NOT NULL DEFAULT 'ticket',
		`ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`src_table` VARCHAR(40) NOT NULL DEFAULT '',
		`item_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`position` VARCHAR(40) NOT NULL DEFAULT '',
		`qty` DECIMAL(10,2) NOT NULL DEFAULT 1,
		`rate` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`status` VARCHAR(20) NOT NULL DEFAULT '',
		`contact_info` VARCHAR(40) NOT NULL DEFAULT '',
		`hours_estimated` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`timer_start` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`hours_tracked` DECIMAL(10,2) NOT NULL DEFAULT 0,
		`shift_start` TIME NOT NULL DEFAULT '08:00',
		`arrived` INT(1) NOT NULL DEFAULT 0,
		`signature` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `police_contact` VARCHAR(20) NOT NULL DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `police_contact` VARCHAR(20) NOT NULL DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `poison_contact` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `police_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `poison_contact` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `police_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `non_emergency_contact` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `poison_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `non_emergency_contact` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `poison_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `emergency_contact` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `non_emergency_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `emergency_contact` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `non_emergency_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `serviceid` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `service`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `serviceid` VARCHAR(100) NOT NULL DEFAULT 0 AFTER `service`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_estimate` VARCHAR(200) NOT NULL DEFAULT 0 AFTER `serviceid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `service_estimate` VARCHAR(200) NOT NULL DEFAULT 0 AFTER `serviceid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_purchase_orders` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`tile_name` VARCHAR(20) NOT NULL DEFAULT 'ticket',
		`tile_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`vendorid` int(11) NOT NULL DEFAULT '0',
		`type` varchar(50) NOT NULL DEFAULT '',
		`issue_date` date NOT NULL DEFAULT '0000-00-00',
		`description` text,
		`qty` text,
		`descript` text,
		`grade` text,
		`tag` text,
		`detail` text,
		`unit_price` text,
		`unit_total` text,
		`total_price` decimal(10,2) NOT NULL DEFAULT 0,
		`final_total` decimal(10,2) NOT NULL DEFAULT 0,
		`bill_to` varchar(50) NOT NULL DEFAULT '',
		`mark_up` int(11) NOT NULL DEFAULT 0,
		`tax` int(11) NOT NULL DEFAULT 0,
		`invoice` text,
		`invoice_number` varchar(20) NOT NULL DEFAULT '',
		`po_status` varchar(20) NOT NULL DEFAULT '',
		`revision` int(11) NOT NULL DEFAULT 0,
		`history` text,
		`deleted` int(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `workorder_purchase_orders` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`tile_name` VARCHAR(20) NOT NULL DEFAULT 'ticket',
		`tile_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`vendorid` int(11) NOT NULL DEFAULT '0',
		`type` varchar(50) NOT NULL DEFAULT '',
		`issue_date` date NOT NULL DEFAULT '0000-00-00',
		`description` text,
		`qty` text,
		`descript` text,
		`grade` text,
		`tag` text,
		`detail` text,
		`unit_price` text,
		`unit_total` text,
		`total_price` decimal(10,2) NOT NULL DEFAULT 0,
		`final_total` decimal(10,2) NOT NULL DEFAULT 0,
		`bill_to` varchar(50) NOT NULL DEFAULT '',
		`mark_up` int(11) NOT NULL DEFAULT 0,
		`tax` int(11) NOT NULL DEFAULT 0,
		`invoice` text,
		`invoice_number` varchar(20) NOT NULL DEFAULT '',
		`po_status` varchar(20) NOT NULL DEFAULT '',
		`revision` int(11) NOT NULL DEFAULT 0,
		`history` text,
		`deleted` int(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `end_time` TIME NOT NULL DEFAULT '00:00' AFTER `start_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `end_time` TIME NOT NULL DEFAULT '00:00' AFTER `start_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_comment` ADD `reference_contact` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `email_comment`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder_comment` ADD `reference_contact` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `email_comment`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `fee_name` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `end_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `fee_name` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `end_time`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `fee_details` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `fee_name`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `fee_details` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `fee_name`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `fee_amt` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `fee_details`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `fee_amt` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `fee_details`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}

	//August 24, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `line_id` INT(11) NOT NULL DEFAULT '0' AFTER `item_id`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `description` TEXT AFTER `status`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `po_id` VARCHAR(100) NOT NULL DEFAULT '' AFTER `status`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `fee_amt` DECIMAL(10,2) NOT NULL DEFAULT '' AFTER `status`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_checklist` (
		`checklistid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`ticketid` int(11) DEFAULT '0',
		`checklist` text,
		`checked` int(1) DEFAULT '0',
		`sort` int(11) DEFAULT '0',
		`flag_colour` varchar(6) DEFAULT '',
		`deleted` int(1) DEFAULT '0'
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_checklist_uploads` (
		`uploadid` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`checklistid` int(11) unsigned DEFAULT '0',
		`type` varchar(100) DEFAULT '',
		`link` text,
		`created_date` date DEFAULT '0000-00-00',
		`created_by` int(11) DEFAULT '0'
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `workorder_checklist` (
		`checklistid` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`workorderid` int(11) DEFAULT '0',
		`checklist` text,
		`checked` int(1) DEFAULT '0',
		`sort` int(11) DEFAULT '0',
		`flag_colour` varchar(6) DEFAULT '',
		`deleted` int(1) DEFAULT '0'
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `workorder_checklist_uploads` (
		`uploadid` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`checklistid` int(11) unsigned DEFAULT '0',
		`type` varchar(100) DEFAULT '',
		`link` text,
		`created_date` date DEFAULT '0000-00-00',
		`created_by` int(11) DEFAULT '0'
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 25, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_document` ADD `label` VARCHAR(100) NOT NULL DEFAULT '' AFTER `link`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder_document` ADD `label` VARCHAR(100) NOT NULL DEFAULT '' AFTER `link`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_document` ADD `deleted` INT(1) NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder_document` ADD `deleted` INT(1) NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 28, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `member_start_time` TIME NOT NULL DEFAULT '00:00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `member_end_time` TIME NOT NULL DEFAULT '00:00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `member_start_time` TIME NOT NULL DEFAULT '00:00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `member_end_time` TIME NOT NULL DEFAULT '00:00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `location_address` VARCHAR(40) NOT NULL DEFAULT '' AFTER `location`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `location_google` TEXT AFTER `location_address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `location_address` VARCHAR(40) NOT NULL DEFAULT '' AFTER `location`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `location_google` TEXT AFTER `location_address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `completed` INT(1) NOT NULL DEFAULT 0 AFTER `arrived`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder_attached` ADD `completed` INT(1) NOT NULL DEFAULT 0 AFTER `arrived`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `summary_notes` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `summary_notes` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `sign_off_id` INT(11) UNSIGNED NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `sign_off_id` INT(11) UNSIGNED NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `sign_off_signature` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `sign_off_signature` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 30, 2017
	if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=CONCAT(`tickets`,',Ticket Details,') WHERE `tickets` NOT LIKE '%,Ticket Details,%' AND `tickets` NOT LIKE '%,Services,%' AND `tickets` NOT LIKE '%,Staff,%'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//August 31, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `flag_colour` VARCHAR(6) NOT NULL DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `alerts_enabled` TEXT AFTER `flag_colour`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tasklist` ADD `alerts_enabled` TEXT AFTER `flag_colour`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `alerts_enabled` TEXT AFTER `flag_colour`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project_milestone_checklist` ADD `alerts_enabled` TEXT AFTER `flag_colour`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `page_options` (
		`optionid` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`php_self` VARCHAR(40) NOT NULL DEFAULT '',
		`scale_width` INT(3) NOT NULL DEFAULT 25
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 1, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `guardians_county` VARCHAR(40) NOT NULL DEFAULT '' AFTER `guardians_town`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `trustee_county` VARCHAR(40) NOT NULL DEFAULT '' AFTER `trustee_town`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `family_doctor_county` VARCHAR(40) NOT NULL DEFAULT '' AFTER `family_doctor_town`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_home_phone` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_cell_phone` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_work_phone` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_fax` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_address` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_postal_code` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_city` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_county` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_province` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `emergency_country` VARCHAR(40) NOT NULL DEFAULT '' AFTER `emergency_contact_number`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `emergency_contact_support_document` VARCHAR(1000)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `goal_support_document` VARCHAR(1000)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `medical_support_document` VARCHAR(1000)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_description` ADD `medical_details_goals` VARCHAR(1000)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_description` ADD `medical_details_goal_concerns` VARCHAR(1000)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_description` ADD `medical_details_goal_procedure` VARCHAR(1000)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `project_lead` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `clientid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `followup` DATE NOT NULL DEFAULT '0000-00-00' AFTER `project_lead`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	$actions = mysqli_query($dbc, "SELECT * FROM `project_actions` WHERE `id` IN (SELECT MAX(`id`) FROM `project_actions` GROUP BY `projectid`)");
	while($action = mysqli_fetch_assoc($actions)) {
		mysqli_query($dbc, "UPDATE `project` SET `project_lead`='{$action['contactid']}', `followup`='{$action['due_date']}' WHERE `projectid`='{$action['projectid']}' AND `project_lead`='0' AND `followup`='0000-00-00'");
	}
	
	//September 5, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project_detail` ADD `detail_detail` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 6, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `pickup_name` TEXT AFTER `postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `pickup_date` DATETIME AFTER `pickup_postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `pickup_link` TEXT AFTER `pickup_postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `pickup_order` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `pickup_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `dropoff_date` DATETIME AFTER `dropoff_postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `dropoff_link` TEXT AFTER `dropoff_postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `dropoff_order` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `dropoff_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	mysqli_query($dbc, "UPDATE `field_config` SET tickets = CONCAT(`tickets`,',Send Emails') WHERE `tickets` NOT LIKE '%Send Emails%' AND `tickets` NOT LIKE '%Complete%'");
	if(!mysqli_query($dbc, "ALTER TABLE `rate_card_breakdown` ADD `src_table` VARCHAR(40) NOT NULL DEFAULT 'miscellaneous' AFTER `description`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `rate_card_breakdown` ADD `src_id` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `src_table`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `afe_number` VARCHAR(20) DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `afe_number` VARCHAR(20) DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 7, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `expense` ADD `projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `work_order`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `order_checklists` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`tile_name` VARCHAR(20) NOT NULL DEFAULT 'inventory',
		`heading` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `order_checklist_lines` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`checklist_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`checklist` TEXT,
		`checked` INT(1) UNSIGNED NOT NULL DEFAULT 0,
		`sort_order` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`history` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 8, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `footer_text` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `footer_logo` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `footer_font_type` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `footer_font_size` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `footer_font` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `footer_font_colour` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `footer_alignment` TEXT AFTER `footer_font`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `header_font_type` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `header_font_size` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `header_font` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `header_font_colour` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `am_pdf_setting` ADD `heading_color` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 12, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `ticket_type` VARCHAR(40) NOT NULL DEFAULT '' AFTER `ticketid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `guardians_self` INT(1) NOT NULL DEFAULT 0 AFTER `guardians_family_guardian`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `postal_code` TEXT AFTER `lsd`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `city` TEXT AFTER `lsd`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pickup_name` TEXT AFTER `postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pickup_address` TEXT AFTER `pickup_name`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pickup_city` TEXT AFTER `pickup_address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pickup_postal_code` TEXT AFTER `pickup_city`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pickup_link` TEXT AFTER `pickup_postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pickup_date` DATETIME AFTER `pickup_link`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pickup_order` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `pickup_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `dropoff_name` TEXT AFTER `pickup_order`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `dropoff_address` TEXT AFTER `dropoff_name`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `dropoff_city` TEXT AFTER `dropoff_address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `dropoff_postal_code` TEXT AFTER `dropoff_city`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `dropoff_link` TEXT AFTER `dropoff_postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `dropoff_date` DATETIME AFTER `dropoff_link`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `dropoff_order` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `dropoff_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `salesorderid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `projectid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `heading_auto` INT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER `heading`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_qty` VARCHAR(40) NOT NULL DEFAULT 0 AFTER `serviceid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
    
    
    //Jay's DB changes not in Nose Creek
    //10 Aug 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `field_work_ticket` ADD `comments` TEXT NULL DEFAULT NULL AFTER `deleted`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    if ( !mysqli_query ( $dbc, "ALTER TABLE `field_invoice` ADD `comments` TEXT NULL DEFAULT NULL AFTER `deleted`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `field_config_vendors` ( `configvendorid` INT(11) NOT NULL AUTO_INCREMENT, `tab` VARCHAR(200) NULL DEFAULT NULL, `subtab` VARCHAR(200) NULL DEFAULT NULL, `accordion` VARCHAR(200) NULL DEFAULT NULL, `fields` TEXT NULL DEFAULT NULL, `dashboard` TEXT NULL DEFAULT NULL, `order` INT(10) NULL DEFAULT NULL, PRIMARY KEY (`configvendorid`));" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //11 Aug 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `inventory` ADD `clearance` INT(1) NOT NULL DEFAULT '0' AFTER `sale`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //15 Aug 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `invoice_insurer` ADD `new` INT(1) NOT NULL DEFAULT '0' AFTER `ui_invoiceid`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //25 Aug 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `vendor_price_list` ADD `drum_unit_cost` VARCHAR(20) NULL DEFAULT NULL AFTER `sales_order_price`, ADD `drum_unit_price` VARCHAR(20) NOT NULL AFTER `drum_unit_cost`, ADD `tote_unit_cost` VARCHAR(20) NOT NULL AFTER `drum_unit_price`, ADD `tote_unit_price` VARCHAR(20) NOT NULL AFTER `tote_unit_cost`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `sales_order_product_temp` (`sotid` INT(11) NOT NULL AUTO_INCREMENT, `sessionid` VARCHAR(100) NULL DEFAULT NULL, `contactid` INT(11) NULL DEFAULT NULL, `pricing` VARCHAR(50) NULL DEFAULT NULL, `item_type` VARCHAR(50) NULL DEFAULT NULL, `item_type_id` INT(11) NULL DEFAULT NULL, `item_category` VARCHAR(100) NULL DEFAULT NULL, `item_name` TEXT NULL DEFAULT NULL, `item_price` VARCHAR(50) NULL DEFAULT NULL, `quantity` INT(11) NULL DEFAULT NULL, PRIMARY KEY (`sotid`));") ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    if ( !mysqli_query ( $dbc, "ALTER TABLE `sales_order` ADD `next_action` VARCHAR(100) NULL DEFAULT NULL AFTER `software_seller`, ADD `next_action_date` DATE NULL DEFAULT NULL AFTER `next_action`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //22 Sep 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts` ADD `referred_contactid` TEXT NULL DEFAULT NULL AFTER `referred_by`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `sales_document` ADD `document_type` VARCHAR(100) NULL DEFAULT NULL AFTER `salesid`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `sales` ADD `deleted` INT NOT NULL DEFAULT '0' AFTER `classification`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //27 Sep 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `field_config` ADD `notes_dashboard` TEXT NULL AFTER `how_to_guide_dashboard`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //02 Oct 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `inventory` ADD `clearance_price` VARCHAR(50) NULL DEFAULT NULL AFTER `client_price`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //04 Oct 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `sales` CHANGE `businessid` `businessid` VARCHAR(50) NULL DEFAULT NULL;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `sales` CHANGE `contactid` `contactid` VARCHAR(50) NULL DEFAULT NULL;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `equipment_assignment` ADD `classification` VARCHAR(100) NULL DEFAULT NULL AFTER `region`, ADD `location` VARCHAR(100) NULL DEFAULT NULL AFTER `classification`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //10-Oct-17
    if ( !mysqli_query ( $dbc, "ALTER TABLE `pos_touch_temp_order_products` ADD `serviceid` INT(12) NULL DEFAULT NULL AFTER `productid`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //11-Oct-17
    if ( !mysqli_query ( $dbc, "ALTER TABLE `website_locations` CHANGE `latitude` `latitude` VARCHAR(50) NULL DEFAULT NULL;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `website_locations` CHANGE `longitude` `longitude` VARCHAR(50) NULL DEFAULT NULL;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //16 Oct 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_medical` ADD `guardians_type` VARCHAR(200) NULL DEFAULT NULL AFTER `insurance_client_id`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //23 Oct 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_description` ADD `food_preferences` TEXT NULL DEFAULT NULL AFTER `gtube_protocol_details`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_description` ADD `first_aid_cpr_details` TEXT NULL DEFAULT NULL AFTER `oxygen_protocol_details`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_description` ADD `day_program_address` TEXT NULL DEFAULT NULL AFTER `medical_details_goal_procedure`, ADD `day_program_phone` VARCHAR(20) NULL DEFAULT NULL AFTER `day_program_address`, ADD `day_program_key_worker` VARCHAR(100) NULL DEFAULT NULL AFTER `day_program_phone`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //25 Oct 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_medical` ADD `pdd_key_contact` TEXT NULL DEFAULT NULL AFTER `funding_pdd`, ADD `pdd_client_id` TEXT NULL DEFAULT NULL AFTER `pdd_key_contact`, ADD `pdd_phone` TEXT NULL DEFAULT NULL AFTER `pdd_client_id`, ADD `pdd_fax` TEXT NULL DEFAULT NULL AFTER `pdd_phone`, ADD `pdd_email` TEXT NULL DEFAULT NULL AFTER `pdd_fax`, ADD `pdd_aish_no` TEXT NULL DEFAULT NULL AFTER `pdd_email`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_description` ADD `src_details` TEXT NULL DEFAULT NULL AFTER `first_aid_cpr_details`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //26 Oct 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `pos_touch_temp_order_products` CHANGE `total` `total` FLOAT(15) NOT NULL;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //30 Oct 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `pos_touch_temp_order_products` ADD `staffid` INT(12) NULL DEFAULT NULL AFTER `orderid`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
    }
    //29 Nov 2017
    if ( !mysqli_query ( $dbc, "UPDATE `contacts` SET `software_tile_menu_choice`='';" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //3 Dec 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_description` ADD `day_program_name` TEXT NULL DEFAULT NULL AFTER `medical_details_goal_procedure`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    if ( !mysqli_query ( $dbc, "UPDATE `contacts_description` SET `day_program_address` = REPLACE(`day_program_address`, '&lt;p&gt;', '');" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    if ( !mysqli_query ( $dbc, "UPDATE `contacts_description` SET `day_program_address` = REPLACE(`day_program_address`, '&lt;/p&gt;', '');" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //6 Dec 2017
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `task_comments` (`taskcommid` INT(11) NOT NULL AUTO_INCREMENT, `tasklistid` INT(11) NOT NULL, `created_by` INT(11) NULL DEFAULT NULL, `created_date` DATE NOT NULL DEFAULT '0000-00-00', `comment` TEXT NULL DEFAULT NULL, `deleted` TINYINT(1) NOT NULL DEFAULT '0', PRIMARY KEY (`taskcommid`));" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //8 Dec 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `tickets` ADD `mdsr_child_name` VARCHAR(100) NULL DEFAULT NULL AFTER `cancellation`, ADD `mdsr_child_dob` DATE NULL DEFAULT NULL AFTER `mdsr_child_name`, ADD `mdsr_date_of_report` DATE NULL DEFAULT NULL AFTER `mdsr_child_dob`, ADD `mdsr_background_info` TEXT NULL DEFAULT NULL AFTER `mdsr_date_of_report`, ADD `mdsr_progress` TEXT NULL DEFAULT NULL AFTER `mdsr_background_info`, ADD `mdsr_clinical_impacts` TEXT NULL DEFAULT NULL AFTER `mdsr_progress`, ADD `mdsr_proposed_goal_areas` TEXT NULL DEFAULT NULL AFTER `mdsr_clinical_impacts`, ADD `mdsr_recommendations` TEXT NULL DEFAULT NULL AFTER `mdsr_proposed_goal_areas`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //11 Dec 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `tasklist` CHANGE `contactid` `contactid` VARCHAR(100) NULL DEFAULT NULL;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //15 Dec 2017
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `followup_notifications` ( `feedbackid` INT(11) NOT NULL AUTO_INCREMENT , `bookingid` INT(11) NOT NULL , `feedback_method` VARCHAR(100) NOT NULL , `feedback` VARCHAR(100) NOT NULL , `feedback_notes` TEXT NULL , `feedback_date` DATE NOT NULL , PRIMARY KEY (`feedbackid`));" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //22 Dec 2017
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `followup_deactivated_contacts` ( `followupid` INT(11) NOT NULL AUTO_INCREMENT , `contactid` INT(11) NOT NULL , `survey_sent_date` DATETIME NULL DEFAULT NULL, `offer1_sent_date` DATETIME NULL DEFAULT NULL COMMENT '3 Months', `offer2_sent_date` DATETIME NULL DEFAULT NULL COMMENT '6 Months' , `offer3_sent_date` DATETIME NULL DEFAULT NULL COMMENT '1 Year' , PRIMARY KEY (`followupid`));" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //02 Jan 2018
    if ( !mysqli_query ( $dbc, "ALTER TABLE `invoice` CHANGE `discount` `discount` DECIMAL(10,2) NULL DEFAULT '0.00';" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />";
	}
    //08 Jan 2018
    $query = mysqli_query($dbc, "SELECT `note` FROM `notes_setting` WHERE `tile`='sales' AND `subtab`='sales_reports'");
    if ( $query->num_rows == 0 ) {
        if ( !mysqli_query ( $dbc, "INSERT INTO `notes_setting` (`tile`, `subtab`, `note`) VALUES ('sales', 'sales_reports', 'Reporting is essential for all sales software; what\'s key about this software is that it reports in real time. As sales staff work through the process, reporting will automatically provide the resources you need to properly manage yourself and any team. Custom reports are available; please request through the support tile.');" ) ) {
            echo "Error: " . mysqli_error($dbc) . "<br />";
        }
    }
    
	
	//Jonathan's Changes not in Nose Creek
	
	//September 13, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `reminders` ADD `sender_name` VARCHAR(40) DEFAULT '' AFTER `sender`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 14, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `hours_subsist` INT(1) DEFAULT 0 AFTER `hours_estimated`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `hours_travel` DECIMAL(10,2) DEFAULT 0 AFTER `hours_estimated`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `hours_ot` DECIMAL(10,2) DEFAULT 0 AFTER `hours_estimated`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 15, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_freq` VARCHAR(20) NOT NULL DEFAULT '' AFTER `reviewer_id`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_start_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `invoice_freq`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_recip_name` VARCHAR(40) NOT NULL DEFAULT '' AFTER `invoice_start_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_recip_address` VARCHAR(40) NOT NULL DEFAULT '' AFTER `invoice_recip_name`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_sender` VARCHAR(40) NOT NULL DEFAULT '' AFTER `invoice_recip_address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_email` VARCHAR(40) NOT NULL DEFAULT '' AFTER `invoice_sender`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_subject` TEXT AFTER `invoice_email`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_body` TEXT AFTER `invoice_subject`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tile_dashboards` ADD `default_levels` TEXT AFTER `assigned_users`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 21, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `deleted` INT(1) NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `security_level_names` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`label` VARCHAR(40) NOT NULL DEFAULT '',
		`identifier` VARCHAR(40) NOT NULL DEFAULT '',
		`active` INT(1) NOT NULL DEFAULT 1,
		`history` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// -- Jonathan
	//September 25, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `footer_text` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `footer_logo` TEXT AFTER `pdf_logo`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `footer_font_type` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `footer_font_size` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `footer_font` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `footer_font_colour` TEXT AFTER `footer_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `footer_alignment` TEXT AFTER `footer_font`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `header_font_type` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `header_font_size` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `header_font` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `header_font_colour` TEXT AFTER `text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `heading_color` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `heading1` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `estimateid` INT(11) UNSIGNED DEFAULT NULL AFTER `style`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `witnessed` TEXT AFTER `signature`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 27, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `add_to_project` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `projectid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//September 28, 2017
	if(mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) FROM `security_privileges` WHERE `privileges` LIKE '%approvals%' AND `tile` IN ('budget','purchase_order','timesheet','estimate','sales','sales_order','project','shop_work_orders','site_work_orders','driving_log')"))[0] == 0) {
		mysqli_query($dbc, "UPDATE `security_privileges` SET `privileges`=CONCAT(`privileges`,'*approvals*') WHERE `privileges` LIKE '%view_use_add_edit_delete%' AND `tile` IN ('budget','purchase_order','timesheet','estimate','sales','sales_order','project','shop_work_orders','site_work_orders','driving_log')");
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `sales_order_notes` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`sales_order_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`note` TEXT,
		`email_comment` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `sales_order_history` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`sales_order_id` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`history` TEXT,
		`date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` CHANGE `favourite` `favourite` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_scope` ADD `detail` TEXT AFTER `description`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_scope` ADD `billing` VARCHAR(20) NOT NULL DEFAULT '' AFTER `detail`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `medication` ADD `dosage` VARCHAR(40) NOT NULL DEFAULT '' AFTER `description`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}

	//Baldwin's DB changes not in Nose Creek
	

    //Ticket #3881 - Calendar Team View
    //field_config_teams table
    if (!mysqli_query($dbc, "CREATE TABLE `field_config_teams` (
        `fieldconfigid` int(11) NOT NULL,
        `contact_category` text,
        `position_enabled` text,
        `team_fields` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_teams` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_teams` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //teams table
    if (!mysqli_query($dbc, "CREATE TABLE `teams` (
        `teamid` int(11) NOT NULL,
        `contact_position` text,
        `contactid` text,
        `region` varchar(100),
        `start_date` date,
        `end_date` date,
        `notes` text,
        `deleted` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams` ADD PRIMARY KEY (`teamid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams` MODIFY `teamid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3881 - Calendar Team View

    //Ticket #3885 - Calendar New Contact Fields (Region)
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `region` varchar(200) DEFAULT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3885 - Calendar New Contact Fields (Region)

    //Ticket #3882 - Calendar Truck Assignment
    //field_config_equip_assign table
    if (!mysqli_query($dbc, "CREATE TABLE `field_config_equip_assign` (
        `fieldconfigid` int(11) NOT NULL,
        `equipment_category` text,
        `contact_category` text,
        `position_enabled` text,
        `enabled_fields` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_equip_assign` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_equip_assign` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //equipment_assignment table
    if (!mysqli_query($dbc, "CREATE TABLE `equipment_assignment` (
        `equipment_assignmentid` int(11) NOT NULL,
        `equipmentid` int(11),
        `contact_position` text,
        `contactid` text,
        `teamid` int(11),
        `region` varchar(100),
        `start_date` date,
        `end_date` date,
        `notes` text,
        `deleted` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment` ADD PRIMARY KEY (`equipment_assignmentid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment` MODIFY `equipment_assignmentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3882 - Calendar Truck Assignment

    //Ticket #3884 - Calendar Work Orders
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `region` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `address` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `city` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `postal_code` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `pickup_address` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `pickup_city` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `pickup_postal_code` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `dropoff_address` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `dropoff_city` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `dropoff_postal_code` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `deliverable_type` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `deliverable_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `distance` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `num_items` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `item_description` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `exchange_product` int(1) DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `return_address` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `return_city` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `return_postal_code` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `oversized_item` int(1)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `measure_width` varchar(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `measure_height` varchar(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `measure_depth` varchar(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `description` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `assembly_required` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `estimated_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `assign_teamid` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` ADD `assign_equip_assignid` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3884 - Calendar Work Orders

    //Ticket #3747 - Work order Changes - Sign In, My Crew, Work Order Sites, Summary
    if(!mysqli_query($dbc, "ALTER TABLE `site_work_orders` ADD `site_summary_status` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3747 - Work order Changes - Sign In, My Crew, Work Order Sites, Summary

    //Ticket #3870 - Calendar Staff Shifts
    //field_config_contacts_shifts table
    if (!mysqli_query($dbc, "CREATE TABLE `field_config_contacts_shifts` (
        `fieldconfigid` int(11) NOT NULL,
        `dayoff_types` text,
        `enabled_fields` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_shifts` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_shifts` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //contacts_shifts table
    if (!mysqli_query($dbc, "CREATE TABLE `contacts_shifts` (
        `shiftid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `startdate` date,
        `enddate` date,
        `starttime` varchar(100),
        `endtime` varchar(100),
        `dayoff_type` varchar(200),
        `break_starttime` varchar(100),
        `break_endtime` varchar(100),
        `repeat_days` text,
        `notes` text,
        `deleted` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD PRIMARY KEY (`shiftid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` MODIFY `shiftid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3870 - Calendar Staff Shifts

    //Ticket #3721 - Calendar Appointments
    //field_config_calendar_booking table
    if (!mysqli_query($dbc, "CREATE TABLE `field_config_calendar_booking` (
        `fieldconfigid` int(11) NOT NULL,
        `status_types` text,
        `enabled_fields` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_calendar_booking` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_calendar_booking` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3721 - Calendar Appointments

    //Ticket #3867 - Incident Report Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_incident_report` ADD `keep_revisions` int(1) DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `revision_number` int(10)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `revision_date` varchar(400)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `site_number` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3867 - Incident Report Revisions

    //Ticket #3866 - Equipment Inspection Form Revisions
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_equipment_inspection` (
        `fieldconfigid` int(11) NOT NULL,
        `tab` varchar(200),
        `inspection_name` varchar(200),
        `inspection_checklist` text,
        `inspection_details` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_equipment_inspection` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_equipment_inspection` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_inspections` ADD `inspection_checklist` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3866 - Equipment Inspection Form Revisions

    //Ticket #3853 - Treatment Charts Changes
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_patientform` ADD `attach_contact_type` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `patientform_pdf` ADD `staffid` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `patientform_pdf` ADD `filled_date` date")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3853 - Treatment Charts Changes

    //Ticket #3976 - Calendar Changes After QAing
    //Appointments
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_calendar_booking` ADD `client_type` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `appt_calendar_teams` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //Work Orders
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` CHANGE `deliverable_time` `to_do_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` CHANGE `deliverable_type` `workorder_type` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` DROP `description`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `workorder` DROP `estimated_time`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //Equipment Assignment
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_equip_assign` ADD `client_type` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment` ADD `clientid` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `appt_calendar_clients` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //Regions
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `appt_calendar_regions` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3976 - Calendar Changes After QAing

    //Ticket #3849 - HR Tile Mix Forms And Manuals
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_hr_manuals` (
        `fieldconfigid` int(11) NOT NULL,
        `tab` varchar(200),
        `category` varchar(500),
        `fields` text,
        `max_section` varchar(10),
        `max_subsection` varchar(10),
        `max_thirdsection` varchar(10),
        `pdf_header` text,
        `pdf_footer` text,
        `send_email` varchar(200))")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_hr_manuals` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_hr_manuals` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3849 - HR Tile Mix Forms And Manuals

    //Ticket #4013 - Calendar Ticket View
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `to_do_start_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `to_do_end_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `internal_qa_start_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `internal_qa_end_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `deliverable_start_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `deliverable_end_time` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4013 - Calendar Ticket View

    //Ticket #3750 - Equipment Tile Additions and Permissions
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `assigned_region` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `assigned_location` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_inspections` ADD `timer` time")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3750 - Equipment Tile Additions and Permissions

    //Ticket #3948 - Form Builder Changes for Contracts
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` ADD `content` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3948 - Form Builder Changes for Contracts

    //Ticket #4024 - New Estimate Layout - Reports
    if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `status_date` date")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4024 - New Estimate Layout - Reports

    //Ticket #4052 - Client Info Tile - Strategies
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `strategies_communication` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `strategies_supports` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `strategies_likes` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `strategies_dislikes` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4052 - Client Info Tile - Strategies

    //Ticket $4051 - Client Info Tile - ISP/Medical Details
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `individual_support_plan` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_description` ADD `medical_details_goals_of_care` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `medical_details_goals_of_care` varchar(1000)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket $4051 - Client Info Tile - ISP/Medical Details

    //Ticket $4050 - Client Info Tile - Details
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `guardians_office_public_guardian` tinyint(4) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `guardians_personal_directive` tinyint(4) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `trustee_office_public_guardian` tinyint(4) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `trustee_personal_directive` tinyint(4) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket $4050 - Client Info Tile - Details
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `trustee_personal_directive` tinyint(4) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4119 - Projects/Tickets Fields    
    if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `afe_number` varchar(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_detail` ADD `detail_procedure_id` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_detail` ADD `detail_quote` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_detail` ADD `detail_dwg` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_detail` ADD `detail_quantity` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_detail` ADD `detail_sn` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_detail` ADD `detail_total_project_budget` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `effective_date` date DEFAULT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `time_clock_start_date` date DEFAULT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4119 - Projects/Tickets Fields

    //Ticket #3831 - Driving Log
    if (!mysqli_query($dbc, "CREATE TABLE `driving_log_time_off` (
        `timeoffid` int(11) NOT NULL,
        `start_date` date,
        `start_time` varchar(555),
        `end_date` date,
        `end_time` varchar(555),
        `main_office_addy` varchar(555),
        `home_terminal_addy` varchar(555),
        `driverid` int(11),
        `codriverid` int(11),
        `clientid` int(11))")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `driving_log_time_off` ADD PRIMARY KEY (`timeoffid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `driving_log_time_off` MODIFY `timeoffid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `driving_log` ADD `notes` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `driving_log_safety_inspect` ADD `safety_inspect_trailerid` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #3831 - Driving Log

    //Ticket #3548 - HR Record in Profile
    if(!mysqli_query($dbc, "ALTER TABLE `hr_attendance` ADD `assign_staffid` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    $all_staff = [];
    $all_staffids = mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` = 'Staff'");
    while($row = mysqli_fetch_array($all_staffids)) {
        $all_staff[$row['contactid']] = get_contact($dbc, $row['contactid']);
    }
    $all_hr_attendance = mysqli_query($dbc, "SELECT * FROM `hr_attendance`");
    while($row = mysqli_fetch_array($all_hr_attendance)) {
        foreach($all_staff as $id => $staff) {
            if(!empty($row['assign_staffid'])) {
                break;
            }
            if($row['assign_staff'] == $staff) {
                mysqli_query($dbc, "UPDATE `hr_attendance` SET `assign_staffid` = '".$id."' WHERE `hrattid` = '".$row['hrattid']."'");
            }
        }
    }
    //Ticket #3548 - HR Record in Profile

    //Ticket #4144 - Client Information Tile - New Layout
    $tile_names = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `tile_name` FROM `field_config_contacts`"),MYSQLI_ASSOC);
    foreach ($tile_names as $tile_name) {
        $tabs = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `tab` FROM `field_config_contacts` WHERE `tile_name` = '".$tile_name['tile_name']."'"),MYSQLI_ASSOC);
        foreach ($tabs as $tab) {
            $field_exists = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `field_config_contacts` WHERE `tile_name` = '".$tile_name['tile_name']."' AND `tab` = '".$tab['tab']."' AND `subtab` = '**no_subtab**'"))['num_rows'];
            if ($field_exists == 0) {
                $all_fields = '';
                $all_dashboard = '';
                $fields = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_contacts` WHERE `tile_name` = '".$tile_name['tile_name']."' AND `tab` = '".$tab['tab']."'"),MYSQLI_ASSOC);
                foreach ($fields as $field) {
                    $all_fields .= ','.$field['contacts'].',';
                    $all_fields = trim($all_fields, ',');
                    $all_dashboard .= ','.$field['contacts_dashboard'].',';
                    $all_dashboard = trim($all_dashboard, ',');
                }
                if(!mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tile_name`, `tab`, `subtab`, `contacts`, `contacts_dashboard`) VALUES ('".$tile_name['tile_name']."', '".$tab['tab']."', '**no_subtab**', '$all_fields', '$all_dashboard')")) {
                    echo "Error: ".mysqli_error($dbc)."<br />\n";
                }
            }
        }
    }
    //Ticket #4144 - Client Information Tile - New Layout

    //Ticket #3846 - HR Custom Tabs
    $hr_tabs = get_config($dbc, 'hr_tabs');
    if(empty($hr_tabs)) {
        if(!mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('hr_tabs', 'Form,Manual,Onboarding,Orientation')")) {
            echo "Error: ".mysqli_error($dbc)."<br />\n";
        }
    }
    //Ticket #3846 - HR Custom Tabs

    //Equipment - Region & Location
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `region` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` MODIFY `location` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_security` ADD `location_access` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Equipment - Region & Location

    //Region & Location
    // if(!mysqli_query($dbc, "CREATE TABLE `contacts_regions` (
    //     `regionid` int(11) NOT NULL,
    //     `tile_name` varchar(200),
    //     `name` varchar(200),
    //     `deleted` int(1) DEFAULT 0
    // )"));
    // if(!mysqli_query($dbc, "ALTER TABLE `contacts_regions` ADD PRIMARY KEY (`regionid`)")) {
    //     echo "Error: ".mysqli_error($dbc)."<br />\n";
    // }
    // if(!mysqli_query($dbc, "ALTER TABLE `contacts_regions` MODIFY `regionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
    //     echo "Error: ".mysqli_error($dbc)."<br />\n";
    // }

    // if(!mysqli_query($dbc, "CREATE TABLE `contacts_locations` (
    //     `locationid` int(11) NOT NULL,
    //     `tile_name` varchar(200),
    //     `name` varchar(200),
    //     `regionid` int(11) DEFAULT 0,
    //     `deleted` int(1) DEFAULT 0
    // )"));
    // if(!mysqli_query($dbc, "ALTER TABLE `contacts_locations` ADD PRIMARY KEY (`locationid`)")) {
    //     echo "Error: ".mysqli_error($dbc)."<br />\n";
    // }
    // if(!mysqli_query($dbc, "ALTER TABLE `contacts_locations` MODIFY `locationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
    //     echo "Error: ".mysqli_error($dbc)."<br />\n";
    // }
    //Region & Location

    //Ticket #4172 - Daysheet, Profile & Header
    if (!mysqli_query($dbc, "CREATE TABLE `daysheet_reminders` (
        `daysheetreminderid` int(11) NOT NULL,
        `reminderid` int(11),
        `contactid` int(11),
        `type` varchar(200),
        `date` date,
        `done` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daysheet_reminders` ADD PRIMARY KEY (`daysheetreminderid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daysheet_reminders` MODIFY `daysheetreminderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if (!mysqli_query($dbc, "CREATE TABLE `daysheet_notepad` (
        `daysheetnotepadid` int(11) NOT NULL,
        `contactid` int(11),
        `date` date,
        `notes` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` ADD PRIMARY KEY (`daysheetnotepadid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` MODIFY `daysheetnotepadid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4172 - Daysheet, Profile & Header

    //Ticket #4117 - Email Communication Sending Email
    if(!mysqli_query($dbc, "ALTER TABLE `email_communication` ADD `from_email` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `email_communication` ADD `from_name` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4117 - Email Communication Sending Email

    //Ticket #4206 - Info Gathering
    if(!mysqli_query($dbc, "ALTER TABLE `infogathering` ADD `user_form_id` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_infogathering` ADD `pdf_style` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //Info Gathering PDF Style Table
    if(!mysqli_query($dbc, "CREATE TABLE `infogathering_pdf_setting` (
        `pdfsettingid` int(11) NOT NULL,
        `style` varchar(30) NOT NULL,
        `file_name` text NOT NULL,
        `font_size` int(10) NOT NULL,
        `font_type` varchar(50) NOT NULL,
        `font` varchar(50) NOT NULL,
        `pdf_logo` text NOT NULL,
        `pdf_size` int(10) NOT NULL,
        `page_ori` varchar(50) NOT NULL,
        `units` int(10) NOT NULL,
        `left_margin` int(10) NOT NULL,
        `right_margin` int(10) NOT NULL,
        `top_margin` int(10) NOT NULL,
        `header_margin` int(10) NOT NULL,
        `bottom_margin` int(10) NOT NULL,
        `pdf_color` text,
        `setting_type` varchar(10) DEFAULT NULL,
        `text` text,
        `alignment` varchar(10) DEFAULT NULL,
        `font_body` text,
        `font_body_size` text,
        `font_body_type` varchar(10) DEFAULT NULL,
        `pdf_body_color` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `infogathering_pdf_setting` ADD PRIMARY KEY (`pdfsettingid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `infogathering_pdf_setting` MODIFY `pdfsettingid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4206 - Info Gathering

    //Ticket #4121 - Sales Order
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `sales_order_temp` (`sotid` INT(11) NOT NULL AUTO_INCREMENT, `sessionid` VARCHAR(100) NULL DEFAULT NULL, `customerid` INT(11) NULL DEFAULT NULL, `logo` VARCHAR(200) NULL DEFAULT NULL, `security_option` VARCHAR(200) NULL DEFAULT NULL, PRIMARY KEY (`sotid`));") ) {
        echo "Error: " . mysqli_error($dbc) . "<br />";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `contact_category` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `parentsotid` INT(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product` ADD `contact_category` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `inventory_pricing` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `vendor_pricing` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `inventory_pricing_team` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `vendor_pricing_team` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `discount_type` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `discount_value` DECIMAL(10,2)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `delivery_type` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `delivery_address` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `contractorid` INT(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `delivery_amount` DECIMAL(10,2)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `assembly_amount` DECIMAL(10,2)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `payment_type` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `deposit_paid` DECIMAL(10,2)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `comment` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `ship_date` DATE")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `due_date` DATE")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `deleted` INT(1) DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4121 - Sales Order

    //Ticket #4143 - Vendor Price List
    if(!mysqli_query($dbc, "ALTER TABLE `vendor_price_list` ADD `item_sku` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `vendor_price_list` ADD `color` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `vendor_price_list` ADD `suggested_retail_price` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `vendor_price_list` ADD `min_amount` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `vendor_price_list` ADD `max_amount` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `vendor_price_list` ADD `rush_price` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }


    if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `item_sku` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `color` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `suggested_retail_price` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `min_amount` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `max_amount` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `rush_price` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4143 - Vendor Price List

    //Ticket #4269 - Driving Log - View Only Mode
    if(!mysqli_query($dbc, "CREATE TABLE `driving_log_view_only_mode` (
        `viewonlymodeid` int(11) NOT NULL,
        `contactid` int(11) NULL,
        `view_only_mode` int(1) NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `driving_log_view_only_mode` ADD PRIMARY KEY (`viewonlymodeid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `driving_log_view_only_mode` MODIFY `viewonlymodeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4269 - Driving Log - View Only Mode

    //Ticket #4180 - Font Size/Type
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `font_type` VARCHAR(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `font_size` VARCHAR(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4180 - Font Size/Type

    //Ticket #4167 - Services Custom PDF Styling and Pulls
    if(!mysqli_query($dbc, "CREATE TABLE `services_templates` (
        `id` int(11) NOT NULL,
        `template_name` VARCHAR(200) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_templates` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_templates` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `services_templates_headings` (
        `id` int(11) NOT NULL,
        `template_id` int(11) NOT NULL DEFAULT 0,
        `heading_name` VARCHAR(200) NOT NULL,
        `sort_order` int(11) NOT NULL DEFAULT 0,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_templates_headings` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_templates_headings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //PDF Style Table
    if(!mysqli_query($dbc, "CREATE TABLE `services_pdf_setting` (
        `pdfsettingid` int(11) NOT NULL,
        `style` varchar(30) NOT NULL,
        `file_name` text NOT NULL,
        `font_size` int(10) NOT NULL,
        `font_type` varchar(50) NOT NULL,
        `font` varchar(50) NOT NULL,
        `pdf_logo` text NOT NULL,
        `pdf_size` int(10) NOT NULL,
        `page_ori` varchar(50) NOT NULL,
        `units` int(10) NOT NULL,
        `left_margin` int(10) NOT NULL,
        `right_margin` int(10) NOT NULL,
        `top_margin` int(10) NOT NULL,
        `header_margin` int(10) NOT NULL,
        `bottom_margin` int(10) NOT NULL,
        `pdf_color` text,
        `setting_type` varchar(10) DEFAULT NULL,
        `text` text,
        `alignment` varchar(10) DEFAULT NULL,
        `font_body` text,
        `font_body_size` text,
        `font_body_type` varchar(10) DEFAULT NULL,
        `pdf_body_color` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_pdf_setting` ADD PRIMARY KEY (`pdfsettingid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_pdf_setting` MODIFY `pdfsettingid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4167 - Services Custom PDF Styling and Pulls

    //Ticket #4174 - Form Builder Increase Label Length
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` MODIFY `label` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` ADD `styling` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4174 - Form Builder Increase Label Length

    //Ticket #4166 - Inventory Custom PDF Styling and Pulls
    if(!mysqli_query($dbc, "CREATE TABLE `inventory_templates` (
        `id` int(11) NOT NULL,
        `template_name` VARCHAR(200) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory_templates` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory_templates` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `inventory_templates_headings` (
        `id` int(11) NOT NULL,
        `template_id` int(11) NOT NULL DEFAULT 0,
        `heading_name` VARCHAR(200) NOT NULL,
        `sort_order` int(11) NOT NULL DEFAULT 0,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory_templates_headings` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory_templates_headings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //PDF Style Table
    if(!mysqli_query($dbc, "CREATE TABLE `inventory_pdf_setting` (
        `pdfsettingid` int(11) NOT NULL,
        `style` varchar(30) NOT NULL,
        `file_name` text NOT NULL,
        `font_size` int(10) NOT NULL,
        `font_type` varchar(50) NOT NULL,
        `font` varchar(50) NOT NULL,
        `pdf_logo` text NOT NULL,
        `pdf_size` int(10) NOT NULL,
        `page_ori` varchar(50) NOT NULL,
        `units` int(10) NOT NULL,
        `left_margin` int(10) NOT NULL,
        `right_margin` int(10) NOT NULL,
        `top_margin` int(10) NOT NULL,
        `header_margin` int(10) NOT NULL,
        `bottom_margin` int(10) NOT NULL,
        `pdf_color` text,
        `setting_type` varchar(10) DEFAULT NULL,
        `text` text,
        `alignment` varchar(10) DEFAULT NULL,
        `font_body` text,
        `font_body_size` text,
        `font_body_type` varchar(10) DEFAULT NULL,
        `pdf_body_color` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory_pdf_setting` ADD PRIMARY KEY (`pdfsettingid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `inventory_pdf_setting` MODIFY `pdfsettingid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `item_sku` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `color` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `suggested_retail_price` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `min_amount` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `max_amount` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `rush_price` VARCHAR(50)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `brand` VARCHAR(255)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `application` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `gauge` VARCHAR(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `length` VARCHAR(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `pressure` VARCHAR(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `featured` INT(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `new` INT(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `sale` INT(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `clearance` INT(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `products` ADD `wcb_price` DECIMAL(10,2)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4166 - Inventory Custom PDF Styling and Pulls

    //Ticket #4137 - Preset Avatars
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `preset_profile_picture` VARCHAR(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4137 - Preset Avatars

    //Ticket #4400 - Daysheet Changes
    if(!mysqli_query($dbc, "CREATE TABLE `checklist_actions` (
        `checklistactionid` int(11) NOT NULL,
        `checklistnameid` int(11) NOT NULL,
        `contactid` int(11),
        `action_date` date,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `checklist_actions` ADD PRIMARY KEY (`checklistactionid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `checklist_actions` MODIFY `checklistactionid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `daysheet_fields_config` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `daysheet_weekly_config` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `daysheet_button_config` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daysheet_reminders` ADD `deleted` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4400 - Daysheet Changes

    //Ticket #4445 - Calendar Notes
    if(!mysqli_query($dbc, "CREATE TABLE `calendar_notes` (
        `noteid` int(11) NOT NULL,
        `contactid` int(11),
        `date` date,
        `note` text,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `calendar_notes` ADD PRIMARY KEY (`noteid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `calendar_notes` MODIFY `noteid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4445 - Calendar Notes

    //Ticket #4343 - Sales Order - Copy Old Orders
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `sotid` int(11)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4343 - Sales Order - Copy Old Orders

    //Ticket #4234 - ID Card
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'staff_information', `order` = 1 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Staff Information' AND `subtab` = 'staff'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'staff_information', `order` = 2 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Staff Profile' AND `subtab` = 'profile'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'staff_information', `order` = 3 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Staff Bio' AND `subtab` = 'profile'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'staff_address', `order` = 4 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Staff Address' AND `subtab` = 'staff'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'employee_information', `order` = 5 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Employee Information' AND `subtab` = 'staff'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'driver_information', `order` = 6 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Driver Information' AND `subtab` = 'staff'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'direct_deposit_information', `order` = 7 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Direct Deposit Information' AND `subtab` = 'staff'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'software_id', `order` = 8 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Software Identity' AND `subtab` = 'profile'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'software_id', `order` = 9 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Software Access' AND `subtab` = 'profile'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'social_media', `order` = 10 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Social Media Links' AND `subtab` = 'profile'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'emergency', `order` = 11 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Primary Emergency Contact' AND `subtab` = 'emergency'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'emergency', `order` = 12 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Secondary Emergency Contact' AND `subtab` = 'emergency'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'health', `order` = 14 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Health Care' AND `subtab` = 'health'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'health', `order` = 15 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Health Concerns' AND `subtab` = 'health'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'health', `order` = 16 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Allergies' AND `subtab` = 'health'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'health', `order` = 17 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Company Benefits' AND `subtab` = 'health'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'schedule', `order` = 18 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Staff Information' AND `subtab` = 'schedule'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'hr', `order` = 19 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Forms' AND `subtab` = 'hr'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `field_config_contacts` SET `subtab` = 'hr', `order` = 20 WHERE (`tab` = 'Staff' OR `tab` = 'Profile') AND `accordion` = 'Manuals' AND `subtab` = 'hr'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $staff_field_subtabs = explode(',',get_config($dbc, 'staff_field_subtabs'));
    $new_staff_field_subtabs = '';
    if(in_array('Staff',$staff_field_subtabs)) {
        $new_staff_field_subtabs .= 'Staff Information,Staff Address,Employee Information,Driver Information,Direct Deposit Information,';
    }
    if(in_array('Profile',$staff_field_subtabs)) {
        $new_staff_field_subtabs .= 'Software ID,Social Media,';
    }
    if(in_array('Emergency',$staff_field_subtabs)) {
        $new_staff_field_subtabs .= 'Emergency,';
    }
    if(in_array('Health',$staff_field_subtabs)) {
        $new_staff_field_subtabs .= 'Health,';
    }
    if(in_array('Schedule',$staff_field_subtabs)) {
        $new_staff_field_subtabs .= 'Schedule,';
    }
    if(in_array('Certificates',$staff_field_subtabs)) {
        $new_staff_field_subtabs .= 'Certificates,';
    }
    if(in_array('HR',$staff_field_subtabs)) {
        $new_staff_field_subtabs .= 'HR,';
    }
    $new_staff_field_subtabs = rtrim($new_staff_field_subtabs,',');
    if(!mysqli_query($dbc, "UPDATE `general_configuration` SET `value` = '$new_staff_field_subtabs' WHERE `name` = 'staff_field_subtabs'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4234 - ID Card

    //Ticket #4342 - Sales Order Details
    if(!mysqli_query($dbc, "CREATE TABLE `sales_order_product_details_temp` (
        `sotid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `parentsotid` int(11) NOT NULL,
        `quantity` int(11),
        `last_updated_by` int(11))")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_details_temp` ADD PRIMARY KEY (`sotid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_details_temp` MODIFY `sotid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `sales_order_product_details` (
        `posproductdetailid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `productposid` int(11) NOT NULL,
        `quantity` int(11))")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_details` ADD PRIMARY KEY (`posproductdetailid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_details` MODIFY `posproductdetailid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `name` VARCHAR(500) AFTER `posid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `name` VARCHAR(500) AFTER `sotid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `name` VARCHAR(500) AFTER `posid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `name` VARCHAR(500) AFTER `sotid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `player_number` VARCHAR(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `business_contact` VARCHAR(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `business_contact` VARCHAR(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4342 - Sales Order Details

    //Ticket #4340 - Custom Upload Design
    if(!mysqli_query($dbc, "CREATE TABLE `sales_order_upload_temp` (
        `sotid` int(11) NOT NULL,
        `parentsotid` int(11) NOT NULL,
        `name` varchar(500),
        `file` varchar(500),
        `added_by` int(11))")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_upload_temp` ADD PRIMARY KEY (`sotid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_upload_temp` MODIFY `sotid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `sales_order_upload` (
        `posuploadid` int(11) NOT NULL,
        `posid` int(11) NOT NULL,
        `name` varchar(500),
        `file` varchar(500),
        `added_by` int(11))")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_upload` ADD PRIMARY KEY (`posuploadid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_upload` MODIFY `posuploadid` int(11) NOT NULL AUTO_INCREMENT")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //Ticket #4340 - Custom Upload Design

    //Ticket #4280 - Staff Export/Import
    $contact_medical_updates = ['health_care_num' => 'VARCHAR(255)',
        'pri_emergency_first_name' => 'VARCHAR(100)',
        'pri_emergency_last_name' => 'VARCHAR(100)',
        'pri_emergency_relation' => 'text',
        'pri_emergency_home_phone' => 'VARCHAR(50)',
        'pri_emergency_cell_phone' => 'VARCHAR(50)',
        'pri_emergency_email' => 'VARCHAR(200)',
        'sec_emergency_first_name' => 'VARCHAR(100)',
        'sec_emergency_last_name' => 'VARCHAR(100)',
        'sec_emergency_relation' => 'text',
        'sec_emergency_home_phone' => 'VARCHAR(50)',
        'sec_emergency_cell_phone' => 'VARCHAR(50)',
        'sec_emergency_email' => 'VARCHAR(200)',
        'health_concerns' => 'text',
        'health_emergency_procedure' => 'text',
        'health_medications' => 'text',
        'health_allergens' => 'text',
        'health_allergens_procedure' => 'text'];

    $contact_updates = ['bank_name' => 'VARCHAR(200)',
        'bank_institution_number' => 'INT(11)',
        'bank_transit' => 'INT(11)',
        'bank_account_number' => 'INT(11)'];

    foreach ($contact_medical_updates as $field => $datatype) {
        $sql = "UPDATE `contacts_medical` cm INNER JOIN `contacts` c ON cm.`contactid` = c.`contactid` SET cm.`".$field."` = c.`".$field."` WHERE cm.`".$field."` IS NULL OR cm.`".$field."` = ''";
        if(!mysqli_query($dbc, $sql)) {
            echo "Error: ".mysqli_error($dbc)."<br />\n";
        }
        $sql_column = "ALTER TABLE `contacts` CHANGE `".$field."` `".$field."_bak` ".$datatype;
        if(!mysqli_query($dbc, $sql_column)) {
            echo "Error: ".mysqli_error($dbc)."<br />\n";
        }
    }
    foreach ($contact_updates as $field => $datatype) {
        $sql = "UPDATE `contacts` c INNER JOIN `contacts_medical` cm ON c.`contactid` = cm.`contactid` SET c.`$field` = cm.`$field` WHERE c.`$field` IS NULL OR c.`$field` = ''";
        $sql_column = "ALTER TABLE `contacts_medical` CHANGE `".$field."` `".$field."_bak` ".$datatype;
        if(!mysqli_query($dbc, $sql_column)) {
            echo "Error: ".mysqli_error($dbc)."<br />\n";
        }
    }

    // foreach ($contact_medical_updates as $field => $datatype) {
    //     $sql_column = "ALTER TABLE `contacts` CHANGE `".$field."_bak` `".$field."` ".$datatype;
    //     if(!mysqli_query($dbc, $sql_column)) {
    //         echo "Error: ".mysqli_error($dbc)."<br />\n";
    //     }
    // }
    // foreach ($contact_updates as $field => $datatype) {
    //     $sql_column = "ALTER TABLE `contacts_medical` CHANGE `".$field."_bak` `".$field."` ".$datatype;
    //     if(!mysqli_query($dbc, $sql_column)) {
    //         echo "Error: ".mysqli_error($dbc)."<br />\n";
    //     }
    // }
    //Ticket #4280 - Staff Export/Import

    //2017-10-03 - Ticket #4598 - Sales Order Templates
    if (!mysqli_query($dbc, "CREATE TABLE `sales_order_template` (
        `id` int(11) NOT NULL,
        `template_name` varchar(200) NOT NULL,
        `region` varchar(200) NOT NULL,
        `location` varchar(200) NOT NULL,
        `classification` varchar(200) NOT NULL,
        `deleted` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if (!mysqli_query($dbc, "CREATE TABLE `sales_order_template_product` (
        `id` int(11) NOT NULL,
        `template_id` int(11) NOT NULL,
        `item_type` varchar(200) NOT NULL,
        `item_type_id` int(11) NOT NULL DEFAULT 0,
        `item_category` varchar(200) NOT NULL,
        `item_name` varchar(200) NOT NULL,
        `item_price` varchar(50) NOT NULL DEFAULT 0,
        `contact_category` varchar(200) NOT NULL,
        `heading_name` varchar(200) NOT NULL,
        `mandatory_quantity` int(11) NOT NULL DEFAULT 0,
        `deleted` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template_product` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template_product` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `heading_name` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `mandatory_quantity` int(11) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product` ADD `heading_name` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-03 - Ticket #4598 - Sales Order Templates

    //2017-10-04 - Ticket #4595 - Sales Order Changes
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `status` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `next_action` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `next_action_date` date NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `primary_staff` int(11) NOT NULL AFTER `name`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `assign_staff` varchar(200) NOT NULL AFTER `primary_staff`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `primary_staff` int(11) NOT NULL AFTER `invoice_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `assign_staff` varchar(200) NOT NULL AFTER `primary_staff`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-04 - Ticket #4595 - Sales Order Changes

	// -- Jonathan
	//October 2, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `location_notes` TEXT AFTER `lsd`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 3, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` CHANGE `is_favourite` `is_favourite` TEXT NULL")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 5, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `checklist_document` ADD `notes` TEXT NULL AFTER `link`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 10, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `emergency_notes` TEXT NULL AFTER `emergency_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `start_time` `start_time` VARCHAR(12) NOT NULL DEFAULT '00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `end_time` `end_time` VARCHAR(12) NOT NULL DEFAULT '00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `member_start_time` `member_start_time` VARCHAR(12) NOT NULL DEFAULT '00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `member_end_time` `member_end_time` VARCHAR(12) NOT NULL DEFAULT '00:00'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}

	// -- Baldwin 2017-10-18	
    //2017-10-10 - Ticket #4482 - Business Card PDFs
    if (!mysqli_query($dbc, "CREATE TABLE `business_card_template` (
        `id` int(11) NOT NULL,
        `template` varchar(200) NOT NULL,
        `contact_category` varchar(200) NOT NULL,
        `fields` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `business_card_template` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `business_card_template` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-10 - Ticket #4482 - Business Card PDFs

    //2017-10-11 - Ticket #4606 - Calendar Shifts Changes
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_shifts` ADD `contact_category` varchar(100) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD `clientid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD `repeat_type` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD `repeat_interval` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD `hide_days` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "UPDATE `contacts_shifts` SET `repeat_type` = 'weekly', `repeat_interval` = 1 WHERE IFNULL(`repeat_days`, '') != '' AND IFNULL(`repeat_type`,'') = ''")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-11 - Ticket #4606 - Calendar Shifts Changes

    //2017-10-12 = Ticket #4577 - Equipment Assignment Changes
    if(!mysqli_query($dbc, "CREATE TABLE `equipment_assignment_staff` (
        `id` int(11) NOT NULL,
        `equipment_assignmentid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `contact_position` varchar(200) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment_staff` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment_staff` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $equip_assign_list = mysqli_query($dbc, "SELECT * FROM `equipment_assignment`");
    $num_rows = mysqli_num_rows($equip_assign_list);

    if($num_rows > 0) {
        $get_field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"));
        if (!empty($get_field_config)) {
            $contact_category = explode(',', $get_field_config['contact_category']);
            $position_enabled = explode(',', $get_field_config['position_enabled']);
        }
        while ($row = mysqli_fetch_array($equip_assign_list)) {
            $num_rows = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `equipment_assignment_staff` WHERE `equipment_assignmentid` = '".$row['equipment_assignmentid']."'"))['num_rows'];

            if($num_rows == 0) {
                $contact_positions = explode(',',$row['contact_position']);
                $contactids = explode(',',$row['contactid']);
                for($i = 0; $i < count($contact_category); $i++) {
                    $contacts = explode('*#*', $contactids[$i]);
                    if($position_enabled[$i] == 1) {
                        $contact_position = $contact_positions[$i];
                    } else {
                        $contact_position = '';
                    }
                    foreach ($contacts as $contact) {
                        if(!empty($contact)) {
                            mysqli_query($dbc, "INSERT INTO `equipment_assignment_staff` (`equipment_assignmentid`, `contactid`, `contact_position`) VALUES ('".$row['equipment_assignmentid']."', '$contact', '$contact_position')");   
                        }
                    }
                }   
            }
        }
    }

    if(!mysqli_query($dbc, "UPDATE `field_config_equip_assign` SET `position_enabled` = IF(`position_enabled` LIKE '%1%', 1, 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_equip_assign` MODIFY `position_enabled` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment` DROP `contactid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment` DROP `contact_position`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `teams_staff` (
        `id` int(11) NOT NULL,
        `teamid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `contact_position` varchar(200) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams_staff` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams_staff` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $team_list = mysqli_query($dbc, "SELECT * FROM `teams`");
    $num_rows = mysqli_num_rows($team_list);

    if($num_rows > 0) {
        $get_field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_teams`"));
        if (!empty($get_field_config)) {
            $contact_category = explode(',', $get_field_config['contact_category']);
            $position_enabled = explode(',', $get_field_config['position_enabled']);
        }
        while ($row = mysqli_fetch_array($team_list)) {
            $num_rows = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `teams_staff` WHERE `teamid` = '".$row['teamid']."'"))['num_rows'];

            if($num_rows == 0) {
                $contact_positions = explode(',',$row['contact_position']);
                $contactids = explode(',',$row['contactid']);
                for($i = 0; $i < count($contact_category); $i++) {
                    $contacts = explode('*#*', $contactids[$i]);
                    if($position_enabled[$i] == 1) {
                        $contact_position = $contact_positions[$i];
                    } else {
                        $contact_position = '';
                    }
                    foreach ($contacts as $contact) {
                        if(!empty($contact)) {
                            mysqli_query($dbc, "INSERT INTO `teams_staff` (`teamid`, `contactid`, `contact_position`) VALUES ('".$row['teamid']."', '$contact', '$contact_position')");   
                        }
                    }
                }   
            }
        }
    }

    if(!mysqli_query($dbc, "UPDATE `field_config_teams` SET `position_enabled` = IF(`position_enabled` LIKE '%1%', 1, 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_teams` MODIFY `position_enabled` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams` DROP `contactid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams` DROP `contact_position`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams` ADD `location` varchar(100) AFTER `region`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `teams` ADD `classification` varchar(100) AFTER `location`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-12 = Ticket #4577 - Equipment Assignment Changes

    //2017-10-16 - Ticket #4344 - Sales Order Classifications
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `classification` varchar(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `classification` varchar(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_details` CHANGE `productposid` `posproductid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-16 - Ticket #4344 - Sales Order Classifications

    //2017-10-16 - Ticket #4715 - Custom Appointment Types
    if(!mysqli_query($dbc, "CREATE TABLE `appointment_type` (
        `id` int(11) NOT NULL,
        `name` varchar(200) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `appointment_type` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `appointment_type` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //Update 'type' column in the booking table to have a longer varchar length so we can update to the new appointment type ids
    if(!mysqli_query($dbc, "ALTER TABLE `booking` CHANGE `type` `type_old` varchar(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services` CHANGE `appointment_type` `appointment_type_old` varchar(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `booking` ADD `type` int(11) NOT NULL AFTER `type_old`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services` ADD `appointment_type` int(11) NOT NULL AFTER `appointment_type_old`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    for($i = 0; $i < 26; $i++) {
        $type = $alphabet[$i];
        $booking_type = '';
        if($type == 'A') {
            $booking_type = 'Private-PT-Assessment';
        }
        if($type == 'B') {
            $booking_type = 'Private-PT-Treatment';
        }
        if($type == 'C') {
            $booking_type = 'MVC-IN-PT-Assessment';
        }
        if($type == 'D') {
            $booking_type = 'MVC-IN-PT-Treatment';
        }
        if($type == 'E') {
            $booking_type = 'Break';
        }
        if($type == 'F') {
            $booking_type = 'MVC-OUT-PT-Assessment';
        }
        if($type == 'G') {
            $booking_type = 'MVC-OUT-PT-Treatment';
        }
        if($type == 'H') {
            $booking_type = 'WCB-PT-Assessment';
        }
        if($type == 'I') {
            $booking_type = 'Holiday';
        }
        if($type == 'J') {
            $booking_type = 'WCB-PT-Treatment';
        }
        if($type == 'K') {
            $booking_type = 'Private-MT';
        }
        if($type == 'L') {
            $booking_type = 'MVC-IN-MT';
        }
        if($type == 'M') {
            $booking_type = 'MVC-OUT-MT';
        }
        if($type == 'N') {
            $booking_type = 'AHS-PT-Assessment';
        }
        if($type == 'O') {
            $booking_type = 'AHS-PT-Treatment';
        }
        if($type == 'P') {
            $booking_type = '';
        }
        if($type == 'Q') {
            $booking_type = 'No Book Days';
        }
        if($type == 'R') {
            $booking_type = 'Vacation';
        }
        if($type == 'S') {
            $booking_type = 'Reassessment';
        }
        if($type == 'T') {
            $booking_type = 'Post-Reassessment';
        }
        if($type == 'U') {
            $booking_type = 'Private-MT-Assessment';
        }
        if($type == 'V') {
            $booking_type = 'Orthotics';
        }
        if($type == 'W') {
            $booking_type = 'Osteopathic-Assessment';
        }
        if($type == 'X') {
            $booking_type = 'Osteopathic-Treatment';
        }
        if($type == 'Y') {
            $booking_type = 'LT-Assessment';
        }
        if($type == 'Z') {
            $booking_type = 'LT-Treatment';
        }

        if($booking_type != '') {
            //Insert the existing booking type into the appointment_type table
            mysqli_query($dbc, "INSERT INTO `appointment_type` (`name`) SELECT '$booking_type' FROM (SELECT COUNT(*) rows FROM `appointment_type` WHERE `name` = '$booking_type') num WHERE num.rows = 0");
            $typeid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `appointment_type` WHERE `name` = '$booking_type'"))['id'];

            //Update type column of existing bookings to the new typeid
            mysqli_query($dbc, "UPDATE `booking` SET `type` = '$typeid' WHERE `type_old` = '$type'");

            //Update type column of existing services to the new typeid
            mysqli_query($dbc, "UPDATE `services` SET `appointment_type` = '$typeid' WHERE `appointment_type_old` = '$type'");
        }
    }
    //2017-10-16 - Ticket #4715 - Custom Appointment Types

    //2017-10-17 - Ticket #4495 - Sales Order Settings
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_so` (
        `fieldconfigid` int(11) NOT NULL,
        `fields` text NOT NULL,
        `dashboard_fields` text NOT NULL,
        `product_fields` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-17 - Ticket #4495 - Sales Order Settings

    //2017-10-19 - Ticket #4597 - Sales Order PDF
    if (!mysqli_query($dbc, "CREATE TABLE `sales_order_pdf` (
        `pdfid` int(11) NOT NULL,
        `soid` int(11) NOT NULL,
        `type` varchar(200) NOT NULL,
        `file_name` varchar(200) NOT NULL,
        `contactid` varchar(200) NOT NULL,
        `created_date` date NOT NULL,
        `deleted` int(1) DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_pdf` ADD PRIMARY KEY (`pdfid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_pdf` MODIFY `pdfid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-19 - Ticket #4597 - Sales Order PDF

    //2017-10-20 - Ticket #4757 - Dispatch Calendar Work Orders (Tickets)
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `equipmentid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `equipment_assignmentid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `teamid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `region` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-20 - Ticket #4757 - Dispatch Calendar Work Orders (Tickets)

    //2017-10-23 - Ticket #4682 - MAR Sheet
    if(!mysqli_query($dbc, "CREATE TABLE `marsheet` (
        `marsheetid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `medicationid` int(11) NOT NULL,
        `month` varchar(200) NOT NULL,
        `year` varchar(200) NOT NULL,
        `route` varchar(200) NOT NULL,
        `dosage` varchar(200) NOT NULL,
        `instructions` text NOT NULL,
        `medication_notes` text NOT NULL,
        `comment` text NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0
    )")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `marsheet` ADD PRIMARY KEY (`marsheetid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `marsheet` MODIFY `marsheetid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `marsheet_row` (
        `marsheetrowid`int(11) NOT NULL,
        `marsheetid` int(11) NOT NULL,
        `heading` varchar(200) NOT NULL,
        `day_1` varchar(50) NOT NULL,
        `day_2` varchar(50) NOT NULL,
        `day_3` varchar(50) NOT NULL,
        `day_4` varchar(50) NOT NULL,
        `day_5` varchar(50) NOT NULL,
        `day_6` varchar(50) NOT NULL,
        `day_7` varchar(50) NOT NULL,
        `day_8` varchar(50) NOT NULL,
        `day_9` varchar(50) NOT NULL,
        `day_10` varchar(50) NOT NULL,
        `day_11` varchar(50) NOT NULL,
        `day_12` varchar(50) NOT NULL,
        `day_13` varchar(50) NOT NULL,
        `day_14` varchar(50) NOT NULL,
        `day_15` varchar(50) NOT NULL,
        `day_16` varchar(50) NOT NULL,
        `day_17` varchar(50) NOT NULL,
        `day_18` varchar(50) NOT NULL,
        `day_19` varchar(50) NOT NULL,
        `day_20` varchar(50) NOT NULL,
        `day_21` varchar(50) NOT NULL,
        `day_22` varchar(50) NOT NULL,
        `day_23` varchar(50) NOT NULL,
        `day_24` varchar(50) NOT NULL,
        `day_25` varchar(50) NOT NULL,
        `day_26` varchar(50) NOT NULL,
        `day_27` varchar(50) NOT NULL,
        `day_28` varchar(50) NOT NULL,
        `day_29` varchar(50) NOT NULL,
        `day_30` varchar(50) NOT NULL,
        `day_31` varchar(50) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0
    )")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `marsheet_row` ADD PRIMARY KEY (`marsheetrowid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `marsheet_row` MODIFY `marsheetrowid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-23 - Ticket #4682 - MAR Sheet

    //2017-10-24 - Contacts_medical table extend lengths of varchars
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_home_phone` `guardians_home_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_cell_phone` `guardians_cell_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_fax` `guardians_fax` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_email_address` `guardians_email_address` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_zip_code` `guardians_zip_code` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_town` `guardians_town` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_county` `guardians_county` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_province` `guardians_province` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_country` `guardians_country` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `trustee_work_phone` `trustee_work_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `trustee_home_phone` `trustee_home_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `trustee_cell_phone` `trustee_cell_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `trustee_fax` `trustee_fax` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `trustee_email_address` `trustee_email_address` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `trustee_county` `trustee_county` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `family_doctor_work_phone` `family_doctor_work_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `family_doctor_home_phone` `family_doctor_home_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `family_doctor_cell_phone` `family_doctor_cell_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `family_doctor_fax` `family_doctor_fax` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `family_doctor_email_address` `family_doctor_email_address` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `family_doctor_county` `family_doctor_county` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `dentist_work_phone` `dentist_work_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `dentist_home_phone` `dentist_home_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `dentist_fax` `dentist_fax` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `dentist_cell_phone` `dentist_cell_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `dentist_email_address` `dentist_email_address` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `specialists_work_phone` `specialists_work_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `specialists_home_phone` `specialists_home_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `specialists_cell_phone` `specialists_cell_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `specialists_fax` `specialists_fax` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `specialists_email_address` `specialists_email_address` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_relationship` `guardians_relationship` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `guardians_siblings` `guardians_siblings` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_first_name` `emergency_first_name` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_last_name` `emergency_last_name` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_contact_number` `emergency_contact_number` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_country` `emergency_country` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_province` `emergency_province` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_county` `emergency_county` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_city` `emergency_city` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_postal_code` `emergency_postal_code` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_address` `emergency_address` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_fax` `emergency_fax` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_work_phone` `emergency_work_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_cell_phone` `emergency_cell_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_home_phone` `emergency_home_phone` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` CHANGE `emergency_relationship` `emergency_relationship` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //2017-10-24 - Contacts_medical table extend lengths of varchars
	
	//Jonathan's Changes not in nose creek
	//October 17, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `current_address` VARCHAR(40) DEFAULT '' AFTER `location`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `delivery_start_address` VARCHAR(40) DEFAULT '' AFTER `postal_code`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `delivery_end_address` VARCHAR(40) DEFAULT '' AFTER `dropoff_order`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `sign_name` VARCHAR(40) DEFAULT '' AFTER `completed`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `witness_name` VARCHAR(40) DEFAULT '' AFTER `signature`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` CHANGE `shift_start` `shift_start` VARCHAR(10) NOT NULL DEFAULT '08:00 am'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 18, 2017
	if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=CONCAT(`tickets`,',PI Pieces,') WHERE `tickets` LIKE '%PI Project%'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "UPDATE `general_configuration` SET `value`=CONCAT(`value`,',PI Pieces,') WHERE `value` LIKE '%PI Project%' AND `name` LIKE 'ticket_fields_%'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `task_available` TEXT AFTER `assign_work`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 19, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `date_stamp` VARCHAR(10) NOT NULL DEFAULT '' AFTER `hours_tracked`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 20, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `invoice_type` VARCHAR(4) NOT NULL DEFAULT 'NEW' AFTER `invoice_freq`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project` CHANGE `ratecardid` `ratecardid` VARCHAR(20) NOT NULL DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `checklist` ADD `ticketid` TEXT AFTER `projectid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `checklist_name` ADD `ticket_checked` TEXT AFTER `checked`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 23, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `style_name` VARCHAR(40) AFTER `pdfsettingid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `page_numbers` VARCHAR(12) AFTER `style`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `toc_content` TEXT AFTER `style`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_font_colour` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_font` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_font_type` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_font_size` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_alignment` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_logo` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_text` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_font_colour` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_font` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_font_type` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_font_size` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_alignment` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_logo` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_text` TEXT AFTER `pdf_body_color`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `pages_before_content` INT(1) NOT NULL DEFAULT 0 AFTER `pages_font_colour`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}

	//October 25, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `social_story_routines` ADD `bedtime_routine` TEXT AFTER `evening_routine_upload`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `social_story_routines` ADD `bedtime_routine_upload` VARCHAR(40) AFTER `bedtime_routine`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `social_story_routines` ADD `morning_snack` VARCHAR(40) AFTER `morning_routine`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `social_story_routines` ADD `afternoon_snack` VARCHAR(40) AFTER `afternoon_routine`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `social_story_routines` ADD `evening_snack` VARCHAR(40) AFTER `evening_routine`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `social_story_routines` ADD `bedtime_snack` VARCHAR(40) AFTER `bedtime_routine`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 30, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_timer` CHANGE `created_date` `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_time_list` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`time_type` VARCHAR(100) NOT NULL DEFAULT '',
		`time_length` TIME DEFAULT NULL,
		`created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `booking` ADD `serviceid` VARCHAR(80) NOT NULL DEFAULT '0' AFTER `type`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `booking` CHANGE `type` `type` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `booking` CHANGE `therapistsid` `therapistsid` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `booking` CHANGE `serviceid` `serviceid` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `booking` CHANGE `appoint_date` `appoint_date` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `booking` CHANGE `end_appoint_date` `end_appoint_date` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	//October 31, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `pos_touch_temp_order` ADD `promoid` INT(11) UNSIGNED DEFAULT NULL AFTER `coupon_value`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `pos_touch_temp_order` ADD `promo_value` VARCHAR(50) DEFAULT NULL AFTER `promoid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `pos_touch_temp_order_products` ADD `serviceid` INT(11) UNSIGNED DEFAULT NULL AFTER `staffid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `pos_touch_temp_order_products` ADD `packageid` INT(11) UNSIGNED DEFAULT NULL AFTER `staffid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `pos_touch_temp_order` ADD `bookingid` INT(11) UNSIGNED DEFAULT NULL AFTER `custid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `invoice` ADD `discount` DECIMAL(10,2) DEFAULT NULL AFTER `promotionid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}

	//Baldwin 2017-11-14
    //2017-10-24 - Ticket #4845 - Calendar Settings Changes
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `calendar_redirect` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-24 - Ticket #4845 - Calendar Settings Changes

    //2017-10-24 - Ticket #4860 - Calendar Appointment Changes
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_calendar_booking` ADD `new_client_fields` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-24 - Ticket #4860 - Calendar Appointment Changes

    //2017-10-31 - Ticket #4653 - Dispatch Calendar: Drag & Drop Assignment Details
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment` ADD `hide_days` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment` ADD `hide_staff` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-10-31 - Ticket #4653 - Dispatch Calendar: Drag & Drop Assignment Details

    //2017-11-02 - Ticket #4864 - AAFS Events Calendar
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `events_calendar_projects` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `attached_image` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `max_capacity` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-02 - Ticket #4864 - AAFS Events Calendar

    //2017-11-06 - TIcket #4441 - Day Overview
    if(!mysqli_query($dbc, "ALTER TABLE `day_overview` ADD `timestamp` datetime NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `day_overview` ADD `tableid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-06 - TIcket #4441 - Day Overview

    //2017-11-06 - Ticket #4434 - Form Builder Changes
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` ADD `mandatory` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-06 - Ticket #4434 - Form Builder Changes

    //2017-11-08 - Ticket #4971 - Hide Alert Icon Setting
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `alert_icon` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-08 - Ticket #4971 - Hide Alert Icon Setting

    //2017-11-09 - Ticket #4902 - Day Sheet Checklist Items
    if(!mysqli_query($dbc, "ALTER TABLE `checklist_actions` ADD `done` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `reminders` ADD `src_table` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `reminders` ADD `src_tableid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `reminders` ADD `done` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-09 - Ticket #4902 - Day Sheet Checklist Items

    //2017-11-09 - Ticket #5044 - Medical Charts PDF Styling
    if(!mysqli_query($dbc, "CREATE TABLE `medical_charts_pdf_setting` (
        `pdfsettingid` int(11) NOT NULL,
        `pdf_logo` varchar(500) NOT NULL,
        `header_text` text NOT NULL,
        `header_align` varchar(200) NOT NULL,
        `header_font` varchar(200) NOT NULL,
        `header_size` int(11) NOT NULL,
        `header_color` varchar(200) NOT NULL,
        `footer_text` text NOT NULL,
        `footer_align` varchar(200) NOT NULL,
        `footer_font` varchar(200) NOT NULL,
        `footer_size` int(11) NOT NULL,
        `footer_color` varchar(200) NOT NULL,
        `body_font` varchar(200) NOT NULL,
        `body_size` int(11) NOT NULL,
        `body_color` varchar(200) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `medical_charts_pdf_setting` ADD PRIMARY KEY (`pdfsettingid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `medical_charts_pdf_setting` MODIFY `pdfsettingid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-09 - Ticket #5044 - Medical Charts PDF Styling

    //2017-11-13 - Ticket #4892 - Add MAR Sheet to Medication Tile
    $med_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `medication`"),MYSQLI_ASSOC);
    foreach($med_list as $med) {
        if(!empty($med['contactid']) && empty($med['clientid'])) {
            $medid = $med['medicationid'];
            $staffid = $med['contactid'];
            $category = strtolower(get_contact($dbc, $staffid, 'category'));
            if($category != 'staff') {
                mysqli_query($dbc, "UPDATE `medication` SET `clientid` = '$staffid', `contactid` = '' WHERE `medicationid` = '$medid'");
            }
        }
    }
    //2017-11-13 - Ticket #4892 - Add MAR Sheet to Medication Tile

    //2017-11-16 - Ticket #4353 - Contacts Tile Additions
    if(!mysqli_query($dbc, "ALTER TABLE `contracts_completed` ADD `contractstaffid` int(11) NOT NULL AFTER `completedcontractid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-16 - Ticket #4353 - Contacts Tile Additions

    //2017-11-22 - Ticket #4596 - Sales Order Contact Categories
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_so_contacts` (
        `fieldconfigid` int(11) NOT NULL,
        `contact_category` varchar(200) NOT NULL,
        `fields` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so_contacts` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so_contacts` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-22 - Ticket #4596 - Sales Order Contact Categories

    //2017-11-22 - Ticket #5110 - Rate Card Changes
    if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `frequency_type` varchar(200) NOT NULL AFTER `rate_card_name`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `frequency_interval` int(11) NOT NULL AFTER `frequency_type`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-22 - Ticket #5110 - Rate Card Changes

    //2017-11-23 - Ticket #5114 - Contact Changes
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `phone_carrier` varchar(200) AFTER `primary_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `notification_type` varchar(200) AFTER `phone_carrier`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_contacts_security` (
        `fieldconfigid` int(11) NOT NULL,
        `tile_name` varchar(200) default 'contacts',
        `category` varchar(200),
        `security_level` varchar(200),
        `subtabs_hidden` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_security` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_security` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-23 - Ticket #5114 - Contact Changes

    //2017-11-29 - Ticket #5176 - Default Login Page
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `daysheet_redirect` VARCHAR(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-29 - Ticket #5176 - Default Login Page

    //2017-11-29 - Ticket #5117 - Ticket Notifications
    if(!mysqli_query($dbc, "CREATE TABLE `ticket_notifications` (
        `ticketnotificationid` int(11) NOT NULL,
        `ticketid` int(11) NOT NULL,
        `staffid` varchar(500) NOT NULL,
        `contactid` varchar(500) NOT NULL,
        `sender_name` varchar(500) NOT NULL,
        `sender_email` varchar(500) NOT NULL,
        `subject` text,
        `email_body` text,
        `status` varchar(250) NOT NULL DEFAULT 'Pending',
        `created_by` int(11) NOT NULL,
        `send_date` date NOT NULL,
        `follow_up_date` date NOT NULL,
        `log` text,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_notifications` ADD PRIMARY KEY (`ticketnotificationid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_notifications` MODIFY `ticketnotificationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `booking` ADD `notification_sent` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-29 - Ticket #5117 - Ticket Notifications

    //2017-11-29 - Ticket #5228 - Dispatch Calendar Changes
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `classification` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `classification` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `con_location` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-11-29 - Ticket #5228 - Dispatch Calendar Changes
	
	// Jonathan's Changes 2017-12-01
	// November 1, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `external_path` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `project_path`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `project_milestone_checklist` ADD `external` VARCHAR(100) DEFAULT NULL AFTER `milestone`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 3, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `certificate_uploads` ADD `deleted` BOOLEAN NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `completed_recipient` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `email_message`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `completed_subject` TEXT AFTER `completed_recipient`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `completed_message` TEXT AFTER `completed_subject`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `approval_subject` TEXT AFTER `completed_message`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `approval_message` TEXT AFTER `approval_subject`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `rejected_subject` TEXT AFTER `approval_message`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `rejected_message` TEXT AFTER `rejected_subject`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 6, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `my_certificate` ADD `deleted` BOOLEAN NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	$certs = mysqli_query($dbc, "SELECT * FROM `my_certificate` WHERE `deleted`=0");
	while($cert = mysqli_fetch_assoc($certs)) {
		rename('download/'.$cert['upload_document'], '../Certificate/download/'.$cert['upload_document']);
		mysqli_query($dbc, "INSERT INTO `certificate` (`contactid`, `certificate_type`, `description`, `issue_date`, `reminder_date`, `expiry_date`) VALUES ('".$cert['contactid']."', '".$cert['certificate_type']."', '".$cert['description']."', '".$cert['date_completion']."', '".$cert['followup_date']."', '".$cert['expiry_date']."')");
		mysqli_query($dbc, "INSERT INTO `certificate_uploads` (`certificateid`, `type`, `document_link`) VALUES ('".mysqli_insert_id($dbc)."', '', '".$cert['upload_document']."')");
	}
	if(!mysqli_query($dbc, "UPDATE `my_certificate` SET `deleted`=1")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `calendar_offline_edits` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`table_name` VARCHAR(40) DEFAULT NULL,
		`tableid` INT(11) DEFAULT NULL,
		`table_field` VARCHAR(40) DEFAULT NULL,
		`field_name` VARCHAR(40) DEFAULT NULL,
		`value` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 7, 2017
	if(mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) FROM `security_privileges` WHERE `privileges` LIKE '%search%' AND `tile` IN ('ticket')"))[0] == 0) {
		mysqli_query($dbc, "UPDATE `security_privileges` SET `privileges`=CONCAT(`privileges`,'*search*') WHERE `privileges` LIKE '%view_use_add_edit_delete%' AND `tile` IN ('ticket')");
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tile_dashboards` ADD `access_levels` TEXT AFTER `tile_sort`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `checked_in` VARCHAR(10) DEFAULT NULL AFTER `timer_start`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `checked_out` VARCHAR(10) DEFAULT NULL AFTER `checked_in`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 8, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_comment` CHANGE `email_comment` `email_comment` VARCHAR(80) DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_comment` CHANGE `reference_contact` `reference_contact` VARCHAR(80) DEFAULT ''")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `client_daily_log_notes` ADD `ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `client_id`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `client_daily_log_notes` ADD `created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `note_date`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 9, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `deleted` BOOLEAN NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_text_alignment` TEXT AFTER `cover_text`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `estimate_pdf_setting` ADD `cover_logo_height` TEXT AFTER `cover_logo`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tile_dashboards` ADD `restrict_levels` TEXT AFTER `default_levels`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_comment` ADD `deleted` BOOLEAN NOT NULL DEFAULT 0")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `hours_set` decimal(10,2) NOT NULL DEFAULT 0 AFTER `hours_estimated`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `highlight` BOOLEAN NOT NULL DEFAULT 0 AFTER `total_hrs`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "UPDATE `field_config` SET `time_cards`=CONCAT(`time_cards`,',reg_hrs,extra_hrs,relief_hrs,sleep_hrs,sick_hrs,sick_used,stat_hrs,stat_used,vaca_hrs,vaca_used,') WHERE `time_cards` NOT LIKE '%reg_hrs%'")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 13, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `notes` `notes` TEXT")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `manager_highlight` BOOLEAN NOT NULL DEFAULT 0 AFTER `highlight`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 15, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `password_update` BOOLEAN NOT NULL DEFAULT 0 AFTER `password`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `password_date` DATETIME AFTER `password`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `main_ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `ticketid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `sub_ticket` VARCHAR(40) NOT NULL DEFAULT '' AFTER `main_ticketid`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 20, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `notes` TEXT AFTER `description`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) FROM `security_privileges` WHERE `privileges` LIKE '%search%' AND `tile` IN ('incident_report')"))[0] == 0) {
		mysqli_query($dbc, "UPDATE `security_privileges` SET `privileges`=CONCAT(`privileges`,'*search*') WHERE `privileges` LIKE '%view_use_add_edit_delete%' AND `tile` IN ('incident_report')");
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_history` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`userid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`description` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "INSERT INTO `ticket_history` (`ticketid`, `description`) SELECT `ticketid`, `history` FROM `tickets` WHERE `history` IS NOT NULL AND `ticketid` NOT IN (SELECT `ticketid` FROM `ticket_history`)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 21, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `received` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `qty`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `used` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `received`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `discrepancy` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `received`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `weight` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `received`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `po_line` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `received`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `piece_num` VARCHAR(20) NOT NULL DEFAULT 0 AFTER `received`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `backorder` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `received`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `medication_history` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`medicationid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`userid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`description` TEXT,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 24, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `mileage` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`staffid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`start` DATETIME,
		`end` DATETIME,
		`category` VARCHAR(40) NOT NULL DEFAULT 'Uncategorized',
		`details` TEXT,
		`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`mileage` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
		`double_mileage` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
		`ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`taskid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`equipmentid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`project_checklistid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`checklistid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`expenseid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`meetingid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
		`deleted` INT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 28, 2017
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_first` TEXT AFTER `guardians_siblings`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_last` TEXT AFTER `siblings_first`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_cell` TEXT AFTER `siblings_last`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_home` TEXT AFTER `siblings_cell`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_address` TEXT AFTER `siblings_home`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_city` TEXT AFTER `siblings_address`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_province` TEXT AFTER `siblings_city`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_postal` TEXT AFTER `siblings_province`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `siblings_country` TEXT AFTER `siblings_postal`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `police_contact` VARCHAR(20) AFTER `display_name`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `poison_control` VARCHAR(20) AFTER `police_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `non_emergency` VARCHAR(20) AFTER `poison_control`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `site_emergency_contact` VARCHAR(20) AFTER `non_emergency`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `emergency_notes` TEXT AFTER `emergency_contact`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	
	// November 29, 2017
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `pdf_settings` (
		`pdfsettingid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`tile_name` VARCHAR(20) NOT NULL DEFAULT '',
		`style_name` VARCHAR(40) DEFAULT NULL,
		`style` VARCHAR(30) NOT NULL,
		`toc_content` TEXT,
		`page_numbers` VARCHAR(12) DEFAULT NULL,
		`file_name` TEXT NOT NULL,
		`font_size` INT(10) NOT NULL,
		`font_type` VARCHAR(50) NOT NULL,
		`font` VARCHAR(50) NOT NULL,
		`pdf_logo` TEXT NOT NULL,
		`pdf_size` INT(10) NOT NULL,
		`page_ori` VARCHAR(50) NOT NULL,
		`units` INT(10) NOT NULL,
		`left_margin` INT(10) NOT NULL,
		`right_margin` INT(10) NOT NULL,
		`top_margin` INT(10) NOT NULL,
		`header_margin` INT(10) NOT NULL,
		`bottom_margin` INT(10) NOT NULL,
		`pdf_color` TEXT,
		`setting_type` VARCHAR(10) DEFAULT NULL,
		`text` TEXT,
		`header_font_colour` TEXT,
		`header_font` TEXT,
		`header_font_size` TEXT,
		`header_font_type` TEXT,
		`footer_text` TEXT,
		`footer_font_colour` TEXT,
		`footer_font` TEXT,
		`footer_logo` TEXT,
		`footer_alignment` TEXT,
		`footer_font_size` TEXT,
		`footer_font_type` TEXT,
		`alignment` VARCHAR(10) DEFAULT NULL,
		`font_body` TEXT,
		`font_body_size` TEXT,
		`font_body_type` VARCHAR(10) DEFAULT NULL,
		`pdf_body_color` TEXT,
		`cover_text` TEXT,
		`cover_text_alignment` TEXT,
		`cover_logo` TEXT,
		`cover_logo_height` TEXT,
		`cover_alignment` TEXT,
		`cover_font_size` TEXT,
		`cover_font_type` TEXT,
		`cover_font` TEXT,
		`cover_font_colour` TEXT,
		`pages_text` TEXT,
		`pages_logo` TEXT,
		`pages_alignment` TEXT,
		`pages_font_size` TEXT,
		`pages_font_type` TEXT,
		`pages_font` TEXT,
		`pages_font_colour` TEXT,
		`pages_before_content` INT(1) NOT NULL DEFAULT '0',
		`heading_color` TEXT,
		`heading1` TEXT,
		`heading2` TEXT,
		`heading1_colour` TEXT,
		`heading2_colour` TEXT,
		`deleted` TINYINT(1) NOT NULL DEFAULT '0'
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}

	// -- Baldwin 2017-12-04
	
    //2017-12-01 - Ticket #5158 - Incident Reports Changes
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `completed_by` int(11) NOT NULL AFTER `type`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `ticketid` int(11) NOT NULL AFTER `completed_by`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `date_of_happening` date NOT NULL AFTER `ticketid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `date_of_report` date NOT NULL AFTER `date_of_happening`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `memberid` varchar(500) NOT NULL AFTER `clientid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `happening_lead_up` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `happening_follow_up` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `future_considerations` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $config_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_incident_report`"),MYSQLI_ASSOC);
    foreach ($config_list as $field_config) {
        $fields = $field_config['incident_report'];
        if(strpos(','.$fields.',', ','."Determine Causes".',') !== FALSE) {
            $fields .= ',Direct Indirect Root Causes';
        }
        mysqli_query($dbc, "UPDATE `field_config_incident_report` SET `incident_report` = '$fields' WHERE `fieldconfigid` = '".$field_config['fieldconfigid']."'");
    }
    //2017-12-01 - Ticket #5158 - Incident Reports Changes

    // -- Baldwin 2017-12-06
    
    //2017-12-04 - Ticket #5303 - Daily Water Temp (Business)
    if(!mysqli_query($dbc, "CREATE TABLE `daily_water_temp_bus` (
        `daily_water_temp_bus_id` int(11) NOT NULL,
        `business` varchar(255) DEFAULT NULL,
        `date` date DEFAULT NULL,
        `time` varchar(255) DEFAULT NULL,
        `location` varchar(255) DEFAULT NULL,
        `water_temp` text,
        `note` text,
        `ai_done` varchar(255) DEFAULT NULL,
        `staff` varchar(255) DEFAULT NULL,
        `history` text,
        `deleted` tinyint(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_water_temp_bus`
        ADD PRIMARY KEY (`daily_water_temp_bus_id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_water_temp_bus`
        MODIFY `daily_water_temp_bus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_water_temp_bus` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_water_temp_bus_dashboard` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-04 - Ticket #5303 - Daily Water Temp (Business)

    //2017-12-04 - Ticket #5299 - Daily Fridge Temp
    if(!mysqli_query($dbc, "CREATE TABLE `daily_fridge_temp` (
        `daily_fridge_temp_id` int(11) NOT NULL,
        `business` varchar(255) DEFAULT NULL,
        `date` date DEFAULT NULL,
        `time` varchar(255) DEFAULT NULL,
        `fridge` varchar(255) DEFAULT NULL,
        `temp` text,
        `note` text,
        `staff` varchar(255) DEFAULT NULL,
        `history` text,
        `deleted` tinyint(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_fridge_temp`
        ADD PRIMARY KEY (`daily_fridge_temp_id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_fridge_temp`
        MODIFY `daily_fridge_temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_fridge_temp` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_fridge_temp_dashboard` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-04 - Ticket #5299 - Daily Fridge Temp

    //2017-12-04 - Ticket #5299 - Daily Freezer Temp
    if(!mysqli_query($dbc, "CREATE TABLE `daily_freezer_temp` (
        `daily_freezer_temp_id` int(11) NOT NULL,
        `business` varchar(255) DEFAULT NULL,
        `date` date DEFAULT NULL,
        `time` varchar(255) DEFAULT NULL,
        `freezer` varchar(255) DEFAULT NULL,
        `temp` text,
        `note` text,
        `staff` varchar(255) DEFAULT NULL,
        `history` text,
        `deleted` tinyint(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_freezer_temp`
        ADD PRIMARY KEY (`daily_freezer_temp_id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_freezer_temp`
        MODIFY `daily_freezer_temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_freezer_temp` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_freezer_temp_dashboard` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-04 - Ticket #5299 - Daily Freezer Temp

    //2017-12-04 - Ticket #5299 - Daily Dishwasher Temp
    if(!mysqli_query($dbc, "CREATE TABLE `daily_dishwasher_temp` (
        `daily_dishwasher_temp_id` int(11) NOT NULL,
        `business` varchar(255) DEFAULT NULL,
        `date` date DEFAULT NULL,
        `time` varchar(255) DEFAULT NULL,
        `water_temp` text,
        `note` text,
        `staff` varchar(255) DEFAULT NULL,
        `history` text,
        `deleted` tinyint(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_dishwasher_temp`
        ADD PRIMARY KEY (`daily_dishwasher_temp_id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `daily_dishwasher_temp`
        MODIFY `daily_dishwasher_temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_dishwasher_temp` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `daily_dishwasher_temp_dashboard` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) SELECT 'charts_tile_charts','bowel_movement,seizure_record,daily_water_temp,blood_glucose,daily_water_temp_bus,daily_fridge_temp,daily_freezer_temp,daily_dishwasher_temp' FROM (SELECT COUNT(*) rows FROM `general_configuration` WHERE `name`='charts_tile_charts') num WHERE num.rows=0");
    //2017-12-04 - Ticket #5299 - Daily Dishwasher Temp

    //2017-12-05 - Ticket #5253 - Delete Ticket Time Estimate
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_time_list` ADD `deleted` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_time_list` ADD `deleted_by` int(11) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-05 - Ticket #5253 - Delete Ticket Time Estimate

    //2017-12-05 - Ticket #4799 - Form Builder Changes
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `header_align` varchar(200) NOT NULL AFTER `header_logo`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `header_font` varchar(200) NOT NULL AFTER `header_align`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `header_size` int(11) NOT NULL AFTER `header_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `header_color` varchar(200) NOT NULL AFTER `header_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `footer_align` varchar(200) NOT NULL AFTER `footer_logo`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `footer_font` varchar(200) NOT NULL AFTER `footer_align`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `footer_size` int(11) NOT NULL AFTER `footer_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `footer_color` varchar(200) NOT NULL AFTER `footer_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `body_heading_font` varchar(200) NOT NULL AFTER `footer_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `body_heading_size` varchar(200) NOT NULL AFTER `body_heading_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `body_heading_color` varchar(200) NOT NULL AFTER `body_heading_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `body_size` varchar(200) NOT NULL AFTER `body_heading_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `body_color` varchar(200) NOT NULL AFTER `body_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `advanced_styling` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` CHANGE `assigned_tile` `assigned_tile` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `subtab` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_head_align` varchar(200) NOT NULL AFTER `default_head_logo`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_head_font` varchar(200) NOT NULL AFTER `default_head_align`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_head_size` int(11) NOT NULL AFTER `default_head_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_head_color` varchar(200) NOT NULL AFTER `default_head_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_foot_align` varchar(200) NOT NULL AFTER `default_foot_logo`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_foot_font` varchar(200) NOT NULL AFTER `default_foot_align`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_foot_size` int(11) NOT NULL AFTER `default_foot_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_foot_color` varchar(200) NOT NULL AFTER `default_foot_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_body_heading_font` varchar(200) NOT NULL AFTER `default_foot_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_body_heading_size` varchar(200) NOT NULL AFTER `default_body_heading_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_body_heading_color` varchar(200) NOT NULL AFTER `default_body_heading_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_body_size` varchar(200) NOT NULL AFTER `default_body_heading_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_body_color` varchar(200) NOT NULL AFTER `default_body_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `subtabs` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `use_templates` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `is_template` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `section_heading_font` varchar(200) NOT NULL AFTER `body_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `section_heading_size` varchar(200) NOT NULL AFTER `section_heading_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `section_heading_color` varchar(200) NOT NULL AFTER `section_heading_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_section_heading_font` varchar(200) NOT NULL AFTER `default_body_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_section_heading_size` varchar(200) NOT NULL AFTER `default_section_heading_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_section_heading_color` varchar(200) NOT NULL AFTER `default_section_heading_size`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    mysqli_query($dbc, "UPDATE `user_forms` SET `advanced_styling` = 1 WHERE `contents` != ''");
    //2017-12-05 - Ticket #4799 - Form Builder Changes

    //2017-12-06 - Ticket #5297 - Incident Reports
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `multisign` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-06 - Ticket #5297 - Incident Reports

    //2017-12-06 - Ticket #5349 - Gift Cards
    if(!mysqli_query($dbc, "ALTER TABLE `pos_touch_temp_order` ADD `giftcardid` int(11) NOT NULL AFTER `coupon_value`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `pos_touch_temp_order` ADD `giftcard_value` varchar(50) NOT NULL AFTER `giftcardid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-06 - Ticket #5349 - Gift Cards

    //2017-12-08 - Ticket #5240 - Intake Forms
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` CHANGE `type` `type` VARCHAR(40) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `intake_field` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if (!mysqli_query($dbc, "CREATE TABLE `intake_forms` (
        `intakeformid` int(11) NOT NULL,
        `user_form_id` int(11) NOT NULL,
        `form_name` varchar(500) NOT NULL,
        `access_code` varchar(500) NOT NULL,
        `expiry_date` date,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `intake_forms` ADD PRIMARY KEY (`intakeformid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `intake_forms` MODIFY `intakeformid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `intakeformid` int(11) NOT NULL DEFAULT 0 AFTER `intakeid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `projectid` int(11) NOT NULL DEFAULT 0 AFTER `contactid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `pdf_id` int(11) NOT NULL DEFAULT 0 AFTER `contactid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `intake_software` text AFTER `intake_dashboard`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config` ADD `intake_software_dashboard` text AFTER `intake_software`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    $intake_software_dashboard = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `intake_software_dashboard` FROM `field_config`"))['intake_software_dashboard'];
    if(empty($intake_software_dashboard)) {
        mysqli_query($dbc, "UPDATE `field_config` SET `intake_software_dashboard` = 'Form ID,Form Name,Name,Email,Phone,Received Date,PDF Form,Assign,Create,Project,Archive'");
    }
    //2017-12-08 - Ticket #5240 - Intake Forms

    //2017-12-08 - Ticket #5265 - SRC Upload
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `src_upload` VARCHAR(1000) AFTER `oxygen_protocol_upload`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-08 - Ticket #5265 - SRC Upload
    
    //2017-12-12 - Ticket #5212 - Client Information Sheet Intake Form
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `preferred_contact_method` VARCHAR(250)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-12 - Ticket #5212 - Client Information Sheet Intake Form

    //2017-12-12 - Ticket #5311 - Client Information Changes
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `wcb_claim_number` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `wcb_accident_date` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-12 - Ticket #5311 - Client Information Changes

    //2017-12-12 - Ticket #5361 - POS Gift Cards
    if(!mysqli_query($dbc, "ALTER TABLE `invoice` ADD `giftcardid` int(11) NOT NULL DEFAULT 0 AFTER `promotionid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `pos_giftcards` ADD `used_value` float NOT NULL DEFAULT 0 AFTER `value`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-12 - Ticket #5361 - POS Gift Cards

    //2017-12-13 - Ticket #5439 - Notable Happenings
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_incident_report` CHANGE `incident_types` `incident_types` varchar(1000) DEFAULT 'Near Miss,Incident,Vehicle Accident'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_incident_report` CHANGE `row_type` `row_type` varchar(250) DEFAULT 'Near Miss,Incident,Vehicle Accident'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-13 - Ticket #5439 - Notable Happenings

    //2017-12-13 - Ticket #5283 - Pink Wand Sales Orders
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `budget` decimal(10,2) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `preferred_booking_time` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `booking_extra` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `location_square_footage` varchar(250) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `location_num_bathrooms` varchar(250) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `location_alarm` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `location_pets` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so` ADD `customer_category` varchar(250) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so` ADD `customer_fields` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    $field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_so`"));
    if(empty($field_config['customer_category'])) {
        mysqli_query($dbc, "UPDATE `field_config_so` SET `customer_category` = 'Business'");
    }
    if(empty($field_config['customer_fields'])) {
        mysqli_query($dbc, "UPDATE `field_config_so` SET `customer_fields` = 'Business Name,Region,Location,Classification,Phone Number,Email Address'");  
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so` ADD `customer_category` varchar(250) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `projectid` int(11) NOT NULL AFTER `contactid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_scope` ADD `salesorderline` int(11) NOT NULL AFTER `estimateline`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-13 - Ticket #5283 - Pink Wand Sales Orders

    //2017-12-14 - Ticket #5318 - Contacts Security
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_security` ADD `subtabs_viewonly` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_security` ADD `fields_hidden` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_security` ADD `fields_viewonly` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-14 - Ticket #5318 - Contacts Security

    //2017-12-15 - TIcket #5495 - Custom Monthly Charts
    if (!mysqli_query($dbc, "CREATE TABLE `field_config_custom_charts` (
        `fieldconfigid` int(11) NOT NULL,
        `name` varchar(500) NOT NULL,
        `heading` varchar(500) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if (!mysqli_query($dbc, "CREATE TABLE `field_config_custom_charts_lines` (
        `fieldconfigid` int(11) NOT NULL,
        `headingid` varchar(500) NOT NULL,
        `field` varchar(500) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts_lines` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts_lines` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if (!mysqli_query($dbc, "CREATE TABLE `custom_charts` (
        `customchartid` int(11) NOT NULL,
        `chart_name` varchar(500) NOT NULL,
        `clientid` int(11) NOT NULL,
        `headingid` int(11) NOT NULL,
        `fieldid` int(11) NOT NULL,
        `year` int(11) NOT NULL,
        `month` int(11) NOT NULL,
        `day` int(11) NOT NULL,
        `staffid` int(11) NOT NULL,
        `checked_date` date NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_charts` ADD PRIMARY KEY (`customchartid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_charts` MODIFY `customchartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-15 - TIcket #5495 - Custom Monthly Charts

    //2017-12-15 - Ticket #5496 - Shifts Align With Time Sheets
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `shift_tracked` int(1) NOT NULL DEFAULT 0 AFTER `day`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-15 - Ticket #5496 - Shifts Align With Time Sheets

    //Baldwin 2018-01-09

    //2017-12-19 - Ticket #5289 - WCB Forms
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_page_format` varchar(1000) NOT NULL DEFAULT 'Page [[CURRENT_PAGE]] of [[TOTAL_PAGE]]' AFTER `default_font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `page_format` varchar(1000) NOT NULL AFTER `font`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `header_skip_first_page` int(1) NOT NULL DEFAULT 0 AFTER `header_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-19 - Ticket #5289 - WCB Forms

    //2017-12-21 - Ticket #5401 - Client Info Tile Create Fields
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `strengths` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `interests` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_medical` ADD `strategies_required_accommodations` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-21 - Ticket #5401 - Client Info Tile Create Fields

    //2017-12-21 - Ticket #5320 - Notable Happenings
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_incident_report` CHANGE `incident_types` `incident_types` VARCHAR(2000) DEFAULT 'Near Miss,Incident,Vehicle Accident'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `projectid` int(11) NOT NULL AFTER `completed_by`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `programid` int(11) NOT NULL AFTER `clientid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-21 - Ticket #5320 - Notable Happenings

    //2017-12-22 - Ticket #5485 - Individual Support Plan
    $default_config = ['Day Program Support Team Primary Contact','Day Program Support Team Lead','Day Program Support Team Key Supports','Residential Support Team Primary Contact','Residential Support Team Lead','Residential Support Team Key Supports','Guardian Primary Contact','Guardian Secondary Contact','Guardian Alternates','Emergency Contacts','ISP Start Date','ISP Review Date','ISP End Date','Quality of Life Outcomes','Goals','Assessed Service Needs','Support Strategies','Support Objectives','SIS Activity Areas','Who is Responsible','Updates','ISP Notes','Service Individual','Day Program Support Team','Residential Support Team','Guardian','Dates & Timelines','ISP Details','CONFIG_UPDATED'];
    $hide_config = explode(',',mysqli_fetch_array(mysqli_query($dbc, "SELECT `individual_support_plan` FROM `field_config`"))['individual_support_plan']);
    if(!in_array('CONFIG_UPDATED',$hide_config)) {
        $default_config = array_diff($default_config, $hide_config);
        $default_config = implode(',',$default_config);
        mysqli_query($dbc, "UPDATE `field_config` SET `individual_support_plan` = '$default_config'");
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `support_contact_gender` varchar(255) NOT NULL AFTER `support_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `support_contact_school` varchar(255) NOT NULL AFTER `support_contact_gender`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `support_contact_grade` varchar(255) NOT NULL AFTER `support_contact_school`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `support_contact_diagnosis` varchar(1000) NOT NULL AFTER `support_contact_grade`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `support_contact_date_of_birth` date NOT NULL AFTER `support_contact_diagnosis`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `support_contact_other_supports` varchar(1000) NOT NULL AFTER `support_contact_date_of_birth`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daycoordinator_contact_category` varchar(255) NOT NULL AFTER `daykey_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daycoordinator_contact` varchar(40) NOT NULL AFTER `daycoordinator_contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daycoordinator_contact_hours` varchar(1000) NOT NULL AFTER `daycoordinator_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daycoordinator_contact_phone` varchar(1000) NOT NULL AFTER `daycoordinator_contact_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daycoordinator_contact_email` varchar(1000) NOT NULL AFTER `daycoordinator_contact_phone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daysl_contact_category` varchar(255) NOT NULL AFTER `daycoordinator_contact_email`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daysl_contact` varchar(40) NOT NULL AFTER `daysl_contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daysl_contact_hours` varchar(1000) NOT NULL AFTER `daysl_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daysl_contact_phone` varchar(1000) NOT NULL AFTER `daysl_contact_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daysl_contact_email` varchar(1000) NOT NULL AFTER `daysl_contact_phone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayot_contact_category` varchar(255) NOT NULL AFTER `daysl_contact_email`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayot_contact` varchar(40) NOT NULL AFTER `dayot_contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayot_contact_hours` varchar(1000) NOT NULL AFTER `dayot_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayot_contact_phone` varchar(1000) NOT NULL AFTER `dayot_contact_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayot_contact_email` varchar(1000) NOT NULL AFTER `dayot_contact_phone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daypp_contact_category` varchar(255) NOT NULL AFTER `dayot_contact_email`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daypp_contact` varchar(40) NOT NULL AFTER `daypp_contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daypp_contact_hours` varchar(1000) NOT NULL AFTER `daypp_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daypp_contact_phone` varchar(1000) NOT NULL AFTER `daypp_contact_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `daypp_contact_email` varchar(1000) NOT NULL AFTER `daypp_contact_phone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayphysio_contact_category` varchar(255) NOT NULL AFTER `daypp_contact_email`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayphysio_contact` varchar(40) NOT NULL AFTER `dayphysio_contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayphysio_contact_hours` varchar(1000) NOT NULL AFTER `dayphysio_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayphysio_contact_phone` varchar(1000) NOT NULL AFTER `dayphysio_contact_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayphysio_contact_email` varchar(1000) NOT NULL AFTER `dayphysio_contact_phone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayaide_contact_category` varchar(255) NOT NULL AFTER `dayphysio_contact_email`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayaide_contact` varchar(40) NOT NULL AFTER `dayaide_contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayaide_contact_hours` varchar(1000) NOT NULL AFTER `dayaide_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayaide_contact_phone` varchar(1000) NOT NULL AFTER `dayaide_contact_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayaide_contact_email` varchar(1000) NOT NULL AFTER `dayaide_contact_phone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayfscd_contact_category` varchar(255) NOT NULL AFTER `dayaide_contact_email`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayfscd_contact` varchar(40) NOT NULL AFTER `dayfscd_contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayfscd_contact_hours` varchar(1000) NOT NULL AFTER `dayfscd_contact`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayfscd_contact_phone` varchar(1000) NOT NULL AFTER `dayfscd_contact_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `dayfscd_contact_email` varchar(1000) NOT NULL AFTER `dayfscd_contact_phone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal1_date` date NOT NULL AFTER `isp_notes`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal1_outcomes` text NOT NULL AFTER `goal1_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal2_date` date NOT NULL AFTER `goal1_outcomes`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal2_outcomes` text NOT NULL AFTER `goal2_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal3_date` date NOT NULL AFTER `goal2_outcomes`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal3_outcomes` text NOT NULL AFTER `goal3_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal4_date` date NOT NULL AFTER `goal3_outcomes`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `goal4_outcomes` text NOT NULL AFTER `goal4_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `longterm_goal1_notes` text NOT NULL AFTER `goal4_outcomes`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_objective` varchar(40) NOT NULL AFTER `longterm_goal1_notes`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_child_date` date NOT NULL AFTER `rating_behaviour_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_child_rating` varchar(40) NOT NULL AFTER `rating_behaviour_child_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_family_date` date NOT NULL AFTER `rating_behaviour_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_family_rating` varchar(40) NOT NULL AFTER `rating_behaviour_family_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_targeted_date` date NOT NULL AFTER `rating_behaviour_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_targeted_rating` varchar(40) NOT NULL AFTER `rating_behaviour_targeted_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_strategies_individual` text NOT NULL AFTER `rating_behaviour_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_strategies_family` text NOT NULL AFTER `rating_behaviour_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_review_date` date NOT NULL AFTER `rating_behaviour_strategies_family`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_parent_update` text NOT NULL AFTER `rating_behaviour_review_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_therapist_update` text NOT NULL AFTER `rating_behaviour_parent_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_aide_update` text NOT NULL AFTER `rating_behaviour_therapist_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_next_step` text NOT NULL AFTER `rating_behaviour_aide_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_objective` varchar(40) NOT NULL AFTER `rating_behaviour_next_step`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_child_date` date NOT NULL AFTER `rating_comm_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_child_rating` varchar(40) NOT NULL AFTER `rating_comm_child_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_family_date` date NOT NULL AFTER `rating_comm_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_family_rating` varchar(40) NOT NULL AFTER `rating_comm_family_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_targeted_date` date NOT NULL AFTER `rating_comm_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_targeted_rating` varchar(40) NOT NULL AFTER `rating_comm_targeted_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_strategies_individual` text NOT NULL AFTER `rating_comm_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_strategies_family` text NOT NULL AFTER `rating_comm_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_review_date` date NOT NULL AFTER `rating_comm_strategies_family`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_parent_update` text NOT NULL AFTER `rating_comm_review_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_therapist_update` text NOT NULL AFTER `rating_comm_parent_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_aide_update` text NOT NULL AFTER `rating_comm_therapist_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_next_step` text NOT NULL AFTER `rating_comm_aide_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_objective` varchar(40) NOT NULL AFTER `rating_comm_next_step`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_child_date` date NOT NULL AFTER `rating_physical_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_child_rating` varchar(40) NOT NULL AFTER `rating_physical_child_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_family_date` date NOT NULL AFTER `rating_physical_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_family_rating` varchar(40) NOT NULL AFTER `rating_physical_family_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_targeted_date` date NOT NULL AFTER `rating_physical_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_targeted_rating` varchar(40) NOT NULL AFTER `rating_physical_targeted_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_strategies_individual` text NOT NULL AFTER `rating_physical_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_strategies_family` text NOT NULL AFTER `rating_physical_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_review_date` date NOT NULL AFTER `rating_physical_strategies_family`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_parent_update` text NOT NULL AFTER `rating_physical_review_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_therapist_update` text NOT NULL AFTER `rating_physical_parent_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_aide_update` text NOT NULL AFTER `rating_physical_therapist_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_next_step` text NOT NULL AFTER `rating_physical_aide_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_objective` varchar(40) NOT NULL AFTER `rating_physical_next_step`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_child_date` date NOT NULL AFTER `rating_cognitive_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_child_rating` varchar(40) NOT NULL AFTER `rating_cognitive_child_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_family_date` date NOT NULL AFTER `rating_cognitive_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_family_rating` varchar(40) NOT NULL AFTER `rating_cognitive_family_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_targeted_date` date NOT NULL AFTER `rating_cognitive_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_targeted_rating` varchar(40) NOT NULL AFTER `rating_cognitive_targeted_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_strategies_individual` text NOT NULL AFTER `rating_cognitive_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_strategies_family` text NOT NULL AFTER `rating_cognitive_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_review_date` date NOT NULL AFTER `rating_cognitive_strategies_family`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_parent_update` text NOT NULL AFTER `rating_cognitive_review_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_therapist_update` text NOT NULL AFTER `rating_cognitive_parent_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_aide_update` text NOT NULL AFTER `rating_cognitive_therapist_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_next_step` text NOT NULL AFTER `rating_cognitive_aide_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_objective` varchar(40) NOT NULL AFTER `rating_cognitive_next_step`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_child_date` date NOT NULL AFTER `rating_safety_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_child_rating` varchar(40) NOT NULL AFTER `rating_safety_child_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_family_date` date NOT NULL AFTER `rating_safety_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_family_rating` varchar(40) NOT NULL AFTER `rating_safety_family_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_targeted_date` date NOT NULL AFTER `rating_safety_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_targeted_rating` varchar(40) NOT NULL AFTER `rating_safety_targeted_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_strategies_individual` text NOT NULL AFTER `rating_safety_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_strategies_family` text NOT NULL AFTER `rating_safety_targeted_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_review_date` date NOT NULL AFTER `rating_safety_strategies_family`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_parent_update` text NOT NULL AFTER `rating_safety_review_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_therapist_update` text NOT NULL AFTER `rating_safety_parent_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_aide_update` text NOT NULL AFTER `rating_safety_therapist_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_next_step` text NOT NULL AFTER `rating_safety_aide_update`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_parent` varchar(1000) NOT NULL AFTER `rating_safety_next_step`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_parent_date` varchar(1000) NOT NULL AFTER `signatures_parent`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_coordinator` varchar(1000) NOT NULL AFTER `signatures_parent_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_coordinator_date` varchar(1000) NOT NULL AFTER `signatures_coordinator`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_sl` varchar(1000) NOT NULL AFTER `signatures_coordinator_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_sl_date` varchar(1000) NOT NULL AFTER `signatures_sl`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_ot` varchar(1000) NOT NULL AFTER `signatures_sl_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_ot_date` varchar(1000) NOT NULL AFTER `signatures_ot`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_pp` varchar(1000) NOT NULL AFTER `signatures_ot_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_pp_date` varchar(1000) NOT NULL AFTER `signatures_pp`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_physio` varchar(1000) NOT NULL AFTER `signatures_pp_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_physio_date` varchar(1000) NOT NULL AFTER `signatures_physio`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_aide` varchar(1000) NOT NULL AFTER `signatures_physio_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_aide_date` varchar(1000) NOT NULL AFTER `signatures_aide`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_child` text NOT NULL AFTER `rating_behaviour_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_family` text NOT NULL AFTER `rating_behaviour_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_behaviour_targeted` text NOT NULL AFTER `rating_behaviour_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_child` text NOT NULL AFTER `rating_comm_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_family` text NOT NULL AFTER `rating_comm_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_comm_targeted` text NOT NULL AFTER `rating_comm_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_child` text NOT NULL AFTER `rating_physical_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_family` text NOT NULL AFTER `rating_physical_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_physical_targeted` text NOT NULL AFTER `rating_physical_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_child` text NOT NULL AFTER `rating_cognitive_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_family` text NOT NULL AFTER `rating_cognitive_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_cognitive_targeted` text NOT NULL AFTER `rating_cognitive_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_child` text NOT NULL AFTER `rating_safety_objective`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_family` text NOT NULL AFTER `rating_safety_child_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `rating_safety_targeted` text NOT NULL AFTER `rating_safety_family_rating`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_parent_name` varchar(1000) NOT NULL AFTER `signatures_parent`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_coordinator_name` varchar(1000) NOT NULL AFTER `signatures_coordinator`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_sl_name` varchar(1000) NOT NULL AFTER `signatures_sl`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_ot_name` varchar(1000) NOT NULL AFTER `signatures_ot`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_pp_name` varchar(1000) NOT NULL AFTER `signatures_pp`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_physio_name` varchar(1000) NOT NULL AFTER `signatures_physio`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `individual_support_plan` ADD `signatures_aide_name` varchar(1000) NOT NULL AFTER `signatures_aide`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-22 - Ticket #5485 - Individual Support Plan

    //2017-12-22 - Ticket #5584 - WTS Import Work Orders
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `billed` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `date_of_entry` date NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `piece_type` varchar(255) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `warehouse_location` varchar(255) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `manifest_num` varchar(255) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `customer_order_num` varchar(255) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `dimensions` varchar(255) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2017-12-22 - Ticket #5584 - WTS Import Work Orders

    //2018-01-02 - Ticket #5206 - Performance Reviews
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` ADD `sublabel` text AFTER `label`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `performance_review` ADD `position` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `performance_review` ADD `user_form_id` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `performance_review` ADD `pdf_id` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `performance_review` ADD `deleted` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-02 - Ticket #5206 - Performance Reviews

    //2018-01-03 - Ticket #5327 - Incident Report Reminders
    if(!mysqli_query($dbc, "CREATE TABLE `incident_report_reminders` (
        `reminderid` int(11) NOT NULL,
        `ticketid` int(11) NOT NULL,
        `staffid` int(11) NOT NULL,
        `subject` text NOT NULL,
        `body` text NOT NULL,
        `sender_name` varchar(500) NOT NULL,
        `sender_email` varchar(500) NOT NULL,
        `second_reminder_email` varchar(500) NOT NULL,
        `second_reminder_date` date NOT NULL,
        `done` int(1) NOT NULL DEFAULT 0,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report_reminders`
        ADD PRIMARY KEY (`reminderid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report_reminders`
        MODIFY `reminderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report_reminders` ADD `log` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-03 - Ticket #5327 - Incident Report Reminders

    //2018-01-05 - Ticket #4599 - Projects Tile Information Gathering
    if(!mysqli_query($dbc, "ALTER TABLE `infogathering_pdf` ADD `projectid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `infogathering_pdf` ADD `businessid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `infogathering_pdf` ADD `staffid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `infogathering_pdf` ADD `pdf_path` varchar(500) NOT NULL AFTER `company`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_pdf` CHANGE `generated_file` `generated_file` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_pdf` CHANGE `scanned_file` `scanned_file` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-05 - Ticket #4599 - Projects Tile Information Gathering

    //2018-01-08 - Ticket #5351 - Import Pink Wand Staff Availability
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD `availability` varchar(500) NOT NULL AFTER repeat_interval")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` CHANGE `deleted` `deleted` int(1) NOT NULL DEFAULT 0 AFTER hide_days")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-08 - Ticket #5351 - Import Pink Wand Staff Availability