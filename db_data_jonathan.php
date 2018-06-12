<?php
	//Jonathan's Database Changes
	echo "Jonathan's DB Changes:<br />\n";
	
	/*** USE THE FOLLOWING EXAMPLES: ***
	if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `table_name` (
		`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`deleted` TINYINT(1) NOT NULL DEFAULT 0
	)")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	}
	if(!mysqli_query($dbc, "ALTER TABLE `table_name` ADD `column` VARCHAR(40) DEFAULT '' AFTER `exist_column`")) {
		echo "Error: ".mysqli_error($dbc)."<br />\n";
	} */
	
	if($db_version_jonathan < 1) {
		// January 11, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `dimension_units` VARCHAR(10) DEFAULT '' AFTER `dimensions`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `container` TEXT AFTER `warehouse_location`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `text_templates` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`tile` VARCHAR(40),
			`tab` VARCHAR(40),
			`field` VARCHAR(40),
			`description` TEXT,
			`template` TEXT,
			`sort` INT(11) NOT NULL DEFAULT 0,
			`deleted` TINYINT(1) NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// January 15, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `ship_google_link` TEXT AFTER `ship_zip`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_cost` ADD `max_km` DECIMAL(10,2)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_cost` ADD `max_pieces` INT(11) UNSIGNED")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// January 17, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `province` VARCHAR(20) AFTER `city`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// January 18, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `services_cost` DECIMAL(10,2) AFTER `service_qty`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_cost_manual` TINYINT(1) NOT NULL DEFAULT 0 AFTER `services_cost`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `agentid` VARCHAR(100) AFTER `clientid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// January 23, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD `hours_type` VARCHAR(20) NOT NULL DEFAULT 'Regular Hrs.' AFTER `dayoff_type`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// January 24, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_description` ADD `stored_signature` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `client_name` TEXT AFTER `location_name`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `email` TEXT AFTER `details`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// January 25, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` CHANGE `dimensions` `dimensions` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` CHANGE `dimension_units` `dimension_units` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// January 26, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `country` VARCHAR(40) AFTER `postal_code`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 1, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` CHANGE `weight` `weight` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` CHANGE `weight_units` `weight_units` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 6, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `warehouse` TEXT AFTER `location`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 7, 2018
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_pdf` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`pdf_name` VARCHAR(40),
			`pages` TEXT,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_pdf_fields` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`pdf_type` VARCHAR(40),
			`field_name` VARCHAR(40),
			`field_label` VARCHAR(40),
			`page` INT(11) UNSIGNED,
			`x` INT(11) UNSIGNED,
			`y` INT(11) UNSIGNED,
			`width` INT(11) UNSIGNED,
			`height` INT(11) UNSIGNED,
			`default_value` TEXT,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_pdf_field_values` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`ticketid` INT(11) UNSIGNED NOT NULL,
			`pdf_type` VARCHAR(40),
			`field_name` VARCHAR(40),
			`field_value` TEXT
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 8, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `editable` TINYINT(1) NOT NULL DEFAULT 0 AFTER `admin_fee`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 12, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tasklist_time` ADD `src` VARCHAR(1) NOT NULL DEFAULT 'M' AFTER `work_time`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `eta` VARCHAR(40) AFTER `volume`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `field_config_project_admin` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name` VARCHAR(40),
			`contactid` TEXT,
			`action_items` TEXT,
			`region` TEXT,
			`classification` TEXT,
			`location` TEXT,
			`customer` TEXT,
			`staff` TEXT,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `approvals` TEXT AFTER `status`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `revision_required` TEXT AFTER `status`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 14, 2018
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=CONCAT(`tickets`,',Service Multiple,') WHERE (SELECT COUNT(*) FROM `general_configuration` WHERE `name`='DEFAULT_SERVICE_MULTIPLE')=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`,`value`) SELECT 'DEFAULT_SERVICE_MULTIPLE', 1 FROM (SELECT COUNT(*) `rows` FROM `general_configuration` WHERE `name`='DEFAULT_SERVICE_MULTIPLE') `num` WHERE `num`.`rows`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 15, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tasklist` ADD `approvals` TEXT AFTER `status`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tasklist` ADD `revision_required` TEXT AFTER `status`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `field_config_project_admin` ADD `precedence` TINYINT(1) DEFAULT 0 AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `field_config_project_admin` ADD `signature` TINYINT(1) DEFAULT 0 AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tasklist` ADD `approval_sign` TEXT AFTER `approvals`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `approval_sign` TEXT AFTER `approvals`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders` ADD `upload` TEXT AFTER `invoice_date`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 16, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `email_communicationid` INT(11) UNSIGNED AFTER `agendameetingid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `checklistnameid` INT(11) UNSIGNED AFTER `agendameetingid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `est_distance` DECIMAL(10,2) UNSIGNED AFTER `map_link`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `est_time` DECIMAL(10,2) UNSIGNED AFTER `map_link`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `completed_time` TIME AFTER `est_time`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `est_distance` DECIMAL(10,2) UNSIGNED AFTER `postal_code`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `est_time` DECIMAL(10,2) UNSIGNED AFTER `postal_code`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `completed_time` TIME AFTER `est_time`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 20, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `gross_units` VARCHAR(10) AFTER `weight_units`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `gross_weight` VARCHAR(10) AFTER `weight_units`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `net_units` VARCHAR(10) AFTER `weight_units`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `net_weight` VARCHAR(10) AFTER `weight_units`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf_fields` ADD `input_class` VARCHAR(20) DEFAULT '' AFTER `height`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf_fields` ADD `options` VARCHAR(20) DEFAULT '' AFTER `height`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf_fields` ADD `font_size` VARCHAR(10) DEFAULT '8' AFTER `height`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 21, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `billing_discount_type` VARCHAR(2) AFTER `service_cost_manual`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `billing_discount` DECIMAL(10,2) AFTER `service_cost_manual`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_discount_type` TEXT AFTER `service_qty`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_discount` TEXT AFTER `service_qty`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_timer` ADD `discount_type` VARCHAR(2) AFTER `timer`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_timer` ADD `discount` DECIMAL(10,2) AFTER `timer`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_time_list` ADD `discount_type` VARCHAR(2) AFTER `time_length`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_time_list` ADD `discount` DECIMAL(10,2) AFTER `time_length`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `discount_type` VARCHAR(2) AFTER `shift_start`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `discount` DECIMAL(10,2) AFTER `shift_start`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `product` VARCHAR(1) AFTER `status`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 22, 2018
		if(!mysqli_query($dbc, "INSERT INTO `positions` (`name`) SELECT `position` FROM `contacts` WHERE `deleted`=0 AND IFNULL(`position`,'') != '' AND `position` NOT IN (SELECT `name` FROM `positions` WHERE deleted=0) GROUP BY `position`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		foreach(explode('#*#',get_config($dbc,'ticket_roles')) as $position) {
			$position = explode('|',$position)[0];
			if(!mysqli_query($dbc, "INSERT INTO `positions` (`name`) SELECT `position` FROM (SELECT '$position' `position`) `positions` WHERE `position` NOT IN (SELECT `name` FROM `positions` WHERE `deleted`=0)")) {
				echo "Error: ".mysqli_error($dbc)."<br />\n";
			}
		}
		
		// February 23, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `positions_allowed` TEXT AFTER `position`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=REPLACE(`tickets`,'Detail Times','Detail Times,Detail Duration') WHERE `tickets` LIKE '%Detail Times%' AND (SELECT COUNT(*) FROM `general_configuration` WHERE `name`='DEFAULT_DURATION_ON')=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		} else {
			set_config($dbc, 'DEFAULT_DURATION_ON', 1);
		}
		if(!mysqli_query($dbc, "UPDATE `general_configuration` LEFT JOIN (SELECT COUNT(*) `rows` FROM `general_configuration` WHERE `name`='DEFAULT_TICKET_TYPE_DURATION_ON') `num` ON 1=1 SET `value`=REPLACE(`value`,'Detail Times','Detail Times,Detail Duration') WHERE `name` LIKE 'ticket_fields_%' AND `value` LIKE '%Detail Times%' AND `num`.`rows`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		} else {
			set_config($dbc, 'DEFAULT_TICKET_TYPE_DURATION_ON', 1);
		}
		
		// February 26, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `unlocked_tabs` TEXT AFTER `status_date`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// February 27, 2018
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `page_load_times` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`url` TEXT,
			`duration` DECIMAL(8,4),
			`date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`ip` VARCHAR(20),
			`user` VARCHAR(40)
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}

		// February 28, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tasklist` CHANGE `updated_date` `updated_date` TIMESTAMP")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `taskboard_seen` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`taskboardid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`seen_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `project_path_custom_milestones` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`pathid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`path_type` CHAR(1) NOT NULL DEFAULT 'I',
			`milestone` VARCHAR(40) DEFAULT '',
			`label` VARCHAR(40) DEFAULT NULL,
			`sort` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 1, 2018
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `taskboard_path_custom_milestones` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`taskboard` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`milestone` VARCHAR(40) DEFAULT '',
			`label` VARCHAR(40) DEFAULT NULL,
			`sort` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 2, 2018
		if(!mysqli_query($dbc, "UPDATE `general_configuration` SET `value`=TRIM(BOTH ',' FROM REPLACE(CONCAT(',',`value`,','),',Inventory,',',Inventory,Inventory General,Inventory Detail,Inventory Return,')) WHERE `name` LIKE 'ticket_sortorder%' AND `value` NOT LIKE '%Inventory General%'")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `general_configuration` SET `value`=TRIM(BOTH ',' FROM REPLACE(CONCAT(',',`value`,','),',Inventory Detail ',',Inventory Detail,Inventory Detail ')) WHERE `name` LIKE 'ticket_fields_%' AND `value` LIKE '%Inventory Detail %' AND `value` NOT LIKE '%Inventory Detail,%' AND `value` NOT LIKE '%Inventory General,%'")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=TRIM(BOTH ',' FROM REPLACE(CONCAT(',',`tickets`,','),',Inventory Detail ',',Inventory Detail,Inventory Detail ')) WHERE `tickets` LIKE '%Inventory Detail %' AND `tickets` NOT LIKE '%Inventory Detail,%' AND `tickets` NOT LIKE '%Inventory General,%'")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `general_configuration` SET `value`=TRIM(BOTH ',' FROM REPLACE(CONCAT(',',`value`,','),',Inventory General ',',Inventory General,Inventory General ')) WHERE `name` LIKE 'ticket_fields_%' AND `value` LIKE '%Inventory General %' AND `value` NOT LIKE '%Inventory General,%'")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=TRIM(BOTH ',' FROM REPLACE(CONCAT(',',`tickets`,','),',Inventory General ',',Inventory General,Inventory General ')) WHERE `tickets` LIKE '%Inventory General %' AND `tickets` NOT LIKE '%Inventory General,%'")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `general_configuration` SET `value`=TRIM(BOTH ',' FROM REPLACE(CONCAT(',',`value`,','),',Inventory Return ',',Inventory Return,Inventory Return ')) WHERE `name` LIKE 'ticket_fields_%' AND `value` LIKE '%Inventory Return %' AND `value` NOT LIKE '%Inventory Return,%'")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=TRIM(BOTH ',' FROM REPLACE(CONCAT(',',`tickets`,','),',Inventory Return ',',Inventory Return,Inventory Return ')) WHERE `tickets` LIKE '%Inventory Return %' AND `tickets` NOT LIKE '%Inventory Return,%'")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf_fields` CHANGE `options` `options` TEXT DEFAULT NULL")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 6, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tasklist` ADD `salesid` INT(11) UNSIGNED AFTER `clientid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tasklist` ADD `sales_milestone` VARCHAR(20) AFTER `salesid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `sales_milestone` VARCHAR(20) AFTER `clientid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `sales_milestone` VARCHAR(20) AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `taskboard_seen` ADD `tab` VARCHAR(20) AFTER `taskboardid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `sales` ADD `sales_path` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `sales_path` (
			`pathid` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`sales_path` TEXT DEFAULT NULL,
			`milestone` TEXT DEFAULT NULL,
			`timeline` TEXT DEFAULT NULL,
			`form` TEXT,
			`ticket` TEXT,
			`task` TEXT
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `sales_path_custom_milestones` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`salesid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`milestone` VARCHAR(40) DEFAULT '',
			`label` VARCHAR(40) DEFAULT NULL,
			`sort` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 7, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf_fields` ADD `sort` INT(11) UNSIGNED AFTER `default_value`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `scheduled_lock` TINYINT(1) UNSIGNED AFTER `to_do_start_time`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 8, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `created_by` INT(11) UNSIGNED AFTER `created_date`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_dates` ADD `contract_start_date` DATE AFTER `contract_end_date`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_cost` ADD `contract_dollar_value` DECIMAL(10,2)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `contract_allocated_hours` VARCHAR(20) AFTER `option_to_renew`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 9, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `safety` ADD `favourite` TEXT AFTER `assign_staff`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `safety` ADD `pinned` TEXT AFTER `assign_staff`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 14, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `service_comments` TEXT AFTER `services`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `business_site_sync` TEXT AFTER `business_street`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `mailing_site_sync` TEXT AFTER `ship_to_address`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `address_site_sync` TEXT AFTER `address`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `cor_certified` TINYINT(1) DEFAULT 0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `cor_number` VARCHAR(20) DEFAULT ''")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 16, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `page_load_times` ADD `info` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_cost` ADD `billable_hours` DECIMAL(10,2)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_cost` ADD `billable_dollars` DECIMAL(10,2)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `ticket_label` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `ticket_label_date` DATETIME")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 21, 2018
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `project_deliverables_output` (
			`id` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`userid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`output_type` VARCHAR(5) NOT NULL DEFAULT '',
			`ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`tasklistid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`recipient` VARCHAR(40) NOT NULL DEFAULT '',
			`subject` TEXT,
			`datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 22, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `volume` DECIMAL(10,2) AFTER `dimension_units`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `volume_units` VARCHAR(20) AFTER `volume`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf` ADD `target` VARCHAR(10) NOT NULL DEFAULT 'slider' AFTER `pages`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `table_locks` ADD `active` TINYINT(1) NOT NULL DEFAULT 1")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 23, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `purchase_order` VARCHAR(30) AFTER `salesorderid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `custom_field` TEXT AFTER `salesorderid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 26, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `services` ADD `service_image` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 27, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `leased` TINYINT(1) NOT NULL DEFAULT 0 AFTER `total_kilometres`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 28, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf` ADD `ticket_types` TEXT AFTER `target`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// March 29, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf` ADD `dashboard` TEXT AFTER `ticket_types`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 2, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `created_date` `created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 3, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `invoice` ADD `ticketid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `projectid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 5, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf_field_values` ADD `revision` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0 AFTER `pdf_type`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		// if(!mysqli_query($dbc, "UPDATE `ticket_pdf_field_values` `value` LEFT JOIN (SELECT `value`.`id`, COUNT(`fields`.`id`)+1 `rev` FROM `ticket_pdf_field_values` `value` LEFT JOIN `ticket_pdf_field_values` `fields` ON `value`.`id` > `fields`.`id` AND `value`.`ticketid`=`fields`.`ticketid` AND `value`.`pdf_type`=`fields`.`pdf_type` AND `value`.`field_name`=`fields`.`field_name` GROUP BY `value`.`id`) `revs` ON `value`.`id`=`revs`.`id` SET `value`.`revision`=`revs`.`rev`")) {
			// echo "Error: ".mysqli_error($dbc)."<br />\n";
		// }
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf` ADD `revisions` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `dashboard`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 9, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_cost` ADD `payment_frequency` VARCHAR(10) NOT NULL DEFAULT ''")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_cost` ADD `total_rate` DECIMAL(10,2) NOT NULL DEFAULT 0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_description` ADD `service_notes` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `orientation_staff` ADD `completed` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `orientation_staff` ADD `profile` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `orientation_staff` ADD `hr` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `orientation_staff` ADD `safety` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 12, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `pallet` VARCHAR(20) AFTER `exchange_cash`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 13, 2018
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `pick_lists` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name` VARCHAR(40) DEFAULT 'New List',
			`businessid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`signature` TEXT,
			`completed_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`created_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`completed` TINYINT(1) NOT NULL DEFAULT 0,
			`deleted` TINYINT(1) NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `pick_list_items` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`pick_list` INT(11) UNSIGNED NOT NULL,
			`inventoryid` INT(11) UNSIGNED NOT NULL,
			`quantity` INT(11) UNSIGNED NOT NULL,
			`filled` INT(11) UNSIGNED NOT NULL,
			`deleted` TINYINT(1) NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 17, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `coordinates` TEXT AFTER `map_link`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `status` VARCHAR(20) AFTER `complete`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_fuel_charge` VARCHAR(40) AFTER `service_time_estimate`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` ADD `assigned` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` ADD `timer_start` VARCHAR(10)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` ADD `timer` VARCHAR(10)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` ADD `start_time` VARCHAR(12)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` ADD `end_time` VARCHAR(12)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 18, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `wcb_status` TINYINT(1)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `vehicle_make` VARCHAR(20) NOT NULL DEFAULT ''")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `vehicle_licence_plate` VARCHAR(20) NOT NULL DEFAULT ''")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `vehicle_registration` VARCHAR(20) NOT NULL DEFAULT ''")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `contract_contacts` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `contract_worker_list` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `contract_abstract` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `contractor_licence` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `criminal_records` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `criminal_check_auth` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `bank_info` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `proof_of_registration` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `contractor_agreement` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `non_compete` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `non_solicitation` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `confidentiality` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `uniform_policy` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `lease_agreement` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `fuel_card_agreement` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `wcb_clearance` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `contract_insurance` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts_upload` ADD `rate_sheet` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 24, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `daysheet_notepad` CHANGE `timer_start` `timer_start` INT(11) UNSIGNED NOT NULL DEFAULT 0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `estimate_notes` ADD `include_pdf` TINYINT(1) NOT NULL DEFAULT 0 AFTER `assigned`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `page_numbers` VARCHAR(12)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `pdf_style` INT(11) UNSIGNED NOT NULL DEFAULT 0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `page_order` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `estimate_content_page` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`content` TEXT,
			`deleted` TINYINT(1) NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=CONCAT(`tickets`,',Business Set Delivery,') WHERE (SELECT COUNT(*) FROM `general_configuration` WHERE `name`='DEFAULT_BUSINESS_TICKET_DELIVERY')=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`,`value`) SELECT 'DEFAULT_BUSINESS_TICKET_DELIVERY', 1 FROM (SELECT COUNT(*) `rows` FROM `general_configuration` WHERE `name`='DEFAULT_BUSINESS_TICKET_DELIVERY') `num` WHERE `num`.`rows`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 26, 2018
		if(!mysqli_query($dbc, "UPDATE `general_configuration` `target` LEFT JOIN `general_configuration` `set` ON `set`.`name`='DEFAULT_NOTIFY_OPTIONS' SET `target`.`value`=REPLACE(`target`.`value`,'Notifications','Notifications,Notify Business,Notify Client,Notify Staff') WHERE `target`.`name` LIKE 'ticket_fields%' AND `target`.`value` LIKE '%Notifications%' AND `target`.`value` NOT LIKE '%Notify Staff%' AND `set`.`name` IS NULL")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=REPLACE(`tickets`,'Notifications','Notifications,Notify Business,Notify Client,Notify Staff') WHERE `tickets` LIKE '%Notifications%' AND `tickets` NOT LIKE '%Notify Staff%' AND (SELECT COUNT(*) FROM `general_configuration` WHERE `name`='DEFAULT_NOTIFY_OPTIONS')=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`,`value`) SELECT 'DEFAULT_NOTIFY_OPTIONS', 1 FROM (SELECT COUNT(*) `rows` FROM `general_configuration` WHERE `name`='DEFAULT_NOTIFY_OPTIONS') `num` WHERE `num`.`rows`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// April 27, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_notifications` ADD `attachment` TEXT AFTER `email_body`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// May 3, 2018
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets_dashboard`=CONCAT(`tickets_dashboard`,',Edit Archive,') WHERE (SELECT COUNT(*) FROM `general_configuration` WHERE `name`='DEFAULT_SHOW_EDIT')=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`,`value`) SELECT 'DEFAULT_SHOW_EDIT', 1 FROM (SELECT COUNT(*) `rows` FROM `general_configuration` WHERE `name`='DEFAULT_SHOW_EDIT') `num` WHERE `num`.`rows`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// May 4, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `inventory` ADD `assigned_qty` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `quantity`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// May 9, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `purchase_order` `purchase_order` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `customer_order_num` `customer_order_num` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		set_config($dbc, 'db_version_jonathan', 1);
	}
	
	if($db_version_jonathan < 2) {
		// May 14, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `po_num` VARCHAR(20) AFTER `piece_num`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// May 15, 2018
		if(!mysqli_query($dbc, "UPDATE `general_configuration` `target` LEFT JOIN `general_configuration` `set` ON `set`.`name`='DEFAULT_STAFF_TASK_ADD' SET `target`.`value`=REPLACE(`target`.`value`,'Staff Tasks','Staff Tasks,Ticket Tasks Add Button') WHERE `target`.`name` LIKE 'ticket_fields%' AND `target`.`value` LIKE '%Staff Tasks%' AND `target`.`value` NOT LIKE '%Ticket Tasks Add Button%' AND `set`.`name` IS NULL")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `field_config` SET `tickets`=REPLACE(`tickets`,'Staff Tasks','Staff Tasks,Ticket Tasks Add Button') WHERE `tickets` LIKE '%Staff Tasks%' AND `tickets` NOT LIKE '%Ticket Tasks Add Button%' AND (SELECT COUNT(*) FROM `general_configuration` WHERE `name`='DEFAULT_STAFF_TASK_ADD')=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`,`value`) SELECT 'DEFAULT_STAFF_TASK_ADD', 1 FROM (SELECT COUNT(*) `rows` FROM `general_configuration` WHERE `name`='DEFAULT_STAFF_TASK_ADD') `num` WHERE `num`.`rows`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// May 24, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `ref_card` TEXT AFTER `rate_card_name`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `rate_card` VARCHAR(20) AFTER `other_ind`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		set_config($dbc, 'db_version_jonathan', 2);
	}
	
	if($db_version_jonathan < 3) {
		// May 28, 2018
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `task_types` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`category` VARCHAR(40),
			`description` TEXT,
			`sort` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		$task_list = $dbc->query("SELECT `value` FROM `general_configuration` WHERE `name` LIKE 'ticket_%_staff_tasks'");
		while($task_groups = $task_list->fetch_array()) {print_r($task_groups);
			foreach(explode('#*#',$task_groups[0]) as $task_group) {print_r($task_group);
				$task_group = explode('*#*',$task_group);
				$category = $task_group[0];
				unset($task_group[0]);
				foreach($task_group as $task_name) {
					echo "INSERT INTO `task_types` (`category`,`description`) SELECT '$category', '$task_name' FROM (SELECT COUNT(*) rows FROM `task_types` WHERE `category`='$category' AND `description`='$task_name') num WHERE num.rows=0";
					$dbc->query("INSERT INTO `task_types` (`category`,`description`) SELECT '$category', '$task_name' FROM (SELECT COUNT(*) rows FROM `task_types` WHERE `category`='$category' AND `description`='$task_name') num WHERE num.rows=0");
				}
			}
		}
		$dbc->query("DELETE FROM `general_configuration` WHERE `name`='site_work_order_tasks' OR `name` LIKE 'ticket_%_staff_tasks'");
		set_config($dbc, 'db_version_jonathan', 3);
	}
	
	if($db_version_jonathan < 4) {
		// May 29, 2018
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `contact_order_numbers` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`category` VARCHAR(40),
			`detail` TEXT,
			`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `ref_contact` TEXT AFTER `siteid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders` ADD `name` TEXT AFTER `posid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders` ADD `invoice_number` TEXT AFTER `invoice_date`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders` ADD `mark_up_total` DECIMAL(10,2) AFTER `total_price`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders` ADD `mark_up` DECIMAL(10,2) AFTER `total_price`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders_product` ADD `tag` TEXT AFTER `misc_product`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders_product` ADD `grade` TEXT AFTER `misc_product`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders_product` ADD `detail` TEXT AFTER `misc_product`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `task_board` ADD `task_path_name` TEXT AFTER `task_path`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `project_path_name` TEXT AFTER `project_path`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `project` ADD `external_path_name` TEXT AFTER `external_path`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// June 1, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `discount` DECIMAL(10,2) AFTER `total_price`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `estimate` ADD `discount_type` VARCHAR(2) AFTER `discount`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `invoice` CHANGE `ticketid` `ticketid` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		set_config($dbc, 'db_version_jonathan', 4);
	}
	
	if($db_version_jonathan < 5) {
		// June 4, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `siteid` INT(11) UNSIGNED NULL AFTER `line_id`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `ticket_manifests` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`date` VARCHAR(10),
			`line_items` TEXT,
			`siteid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`contactid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`signature` TEXT,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// June 5, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `cust_est` VARCHAR(20) AFTER `eta`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `serviceid` TEXT AFTER `eta`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// June 6, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `uom` VARCHAR(20) AFTER `created_by`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `cost` DECIMAL(10,2) AFTER `created_by`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `profit` DECIMAL(10,2) AFTER `cost`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `margin` DECIMAL(10,2) AFTER `profit`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		set_config($dbc, 'db_version_jonathan', 5);
	}
	
	if($db_version_jonathan < 6) {
		// June 7, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `task_types` ADD `details` TEXT AFTER `description`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `task_types` ADD `qty` DECIMAL(10,2) AFTER `details`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders` ADD `siteid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `purchase_orders` ADD `businessid` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// June 8, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `field_po` CHANGE `qty` `qty` TEXT NULL DEFAULT NULL, CHANGE `desc` `desc` TEXT NULL DEFAULT NULL, CHANGE `grade` `grade` TEXT NULL DEFAULT NULL, CHANGE `tag` `tag` TEXT NULL DEFAULT NULL, CHANGE `detail` `detail` TEXT NULL DEFAULT NULL, CHANGE `price_per_unit` `price_per_unit` TEXT NULL DEFAULT NULL, CHANGE `each_cost` `each_cost` TEXT NULL DEFAULT NULL;")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `project_payments` (
			`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`projectid` INT(11) UNSIGNED NOT NULL DEFAULT 0,
			`due_date` VARCHAR(10),
			`date_paid` VARCHAR(10),
			`heading` TEXT,
			`amount` DECIMAL(10,2),
			`status` TEXT,
			`history` TEXT,
			`deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
		)")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_manifests` ADD `qty` TEXT AFTER `line_items`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` CHANGE `siteid` `siteid` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `contact_document` ADD `category` VARCHAR(20) AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		// June 11, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` CHANGE `hours_subsist` `hours_subsist` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `incident_report` CHANGE `completed_by` `completed_by` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `approved_by` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `status`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `editable` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `cost`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `admin_fee` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `cust_price`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `created_by` INT(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `sort_order`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `history` TEXT AFTER `created_by`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `incident_report` CHANGE `item_id` `item_id` TEXT")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `company_rate_card` (`description`,`item_id`,`rate_card_name`,`start_date`,`end_date`,`alert_date`,`alert_staff`,`tile_name`,`deleted`,`daily`,`hourly`,`cost`,`cust_price`,`uom`,`created_by`,`history`) SELECT `positions`.`name`, `pos_rates`.`position_id`, `rate`.`rate_card_name`, `pos_rates`.`start_date`, `pos_rates`.`end_date`, `pos_rates`.`alert_date`, `pos_rates`.`alert_staff`, 'Position' `tile_name`, `pos_rates`.`deleted`,`daily`,`hourly`, `cost`, `unit_price` `cust_price`, 'Each' `uom`, `pos_rates`.`created_by`,`pos_rates`.`history` FROM `position_rate_table` `pos_rates` LEFT JOIN `positions` ON `pos_rates`.`position_id`=`positions`.`position_id` LEFT JOIN (SELECT `rate_card_name` FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name` != '' GROUP BY `rate_card_name`) `rate` ON 1=1 WHERE `pos_rates`.`deleted`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `position_rate_table` SET `deleted`=1")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `company_rate_card` (`item_id`,`rate_card_name`,`start_date`,`end_date`,`alert_date`,`alert_staff`,`tile_name`,`deleted`,`daily`,`hourly`,`cost`,`cust_price`,`uom`,`created_by`,`history`) SELECT `equ_rates`.`equipment_id`, `rate`.`rate_card_name`, `equ_rates`.`start_date`, `equ_rates`.`end_date`, `equ_rates`.`alert_date`, `equ_rates`.`alert_staff`, 'Equipment' `tile_name`, `equ_rates`.`deleted`,`daily`,`hourly`, `cost`, `unit_price` `cust_price`, 'Each' `uom`,`created_by`,`history` FROM `equipment_rate_table` `equ_rates` LEFT JOIN (SELECT `rate_card_name` FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name` != '' GROUP BY `rate_card_name`) `rate` ON 1=1 WHERE `equ_rates`.`deleted`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `equipment_rate_table` SET `deleted`=1")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `company_rate_card` (`description`,`rate_card_name`,`start_date`,`end_date`,`alert_date`,`alert_staff`,`tile_name`,`deleted`,`daily`,`hourly`,`cost`,`cust_price`,`uom`,`created_by`,`history`) SELECT `equ_rates`.`category`, `rate`.`rate_card_name`, `equ_rates`.`start_date`, `equ_rates`.`end_date`, `equ_rates`.`alert_date`, `equ_rates`.`alert_staff`, 'Equipment' `tile_name`, `equ_rates`.`deleted`,`daily`,`hourly`, `cost`, `unit_price` `cust_price`, 'Each' `uom`,`created_by`,`history` FROM `category_rate_table` `equ_rates` LEFT JOIN (SELECT `rate_card_name` FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name` != '' GROUP BY `rate_card_name`) `rate` ON 1=1 WHERE `equ_rates`.`deleted`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `category_rate_table` SET `deleted`=1")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `company_rate_card` (`item_id`,`rate_card_name`,`start_date`,`end_date`,`alert_date`,`alert_staff`,`tile_name`,`deleted`,`daily`,`hourly`,`cost`,`cust_price`,`uom`) SELECT `staff_id`, `rate`.`rate_card_name`, `start_date`, `end_date`, `alert_date`, `alert_staff`, 'Staff' `tile_name`, `deleted`,`daily`,`hourly`, `cost`, `unit_price` `cust_price`, 'Each' `uom` FROM `staff_rate_table` LEFT JOIN (SELECT `rate_card_name` FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name` != '' GROUP BY `rate_card_name`) `rate` ON 1=1 WHERE `deleted`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `staff_rate_table` SET `deleted`=1")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `company_rate_card` (`item_id`,`rate_card_name`,`start_date`,`end_date`,`alert_date`,`alert_staff`,`tile_name`,`deleted`,`cost`,`profit`,`margin`,`cust_price`,`admin_fee`,`editable`,`uom`,`created_by`,`history`) SELECT `service_rates`.`serviceid`, `rate`.`rate_card_name`, `service_rates`.`start_date`, `service_rates`.`end_date`, `service_rates`.`alert_date`, `service_rates`.`alert_staff`, 'Services' `tile_name`, `service_rates`.`deleted`, `cost`,`profit`,`margin`, `service_rate` `cust_price`, `admin_fee` ``, `editable`, `uom`, `created_by`, `history` FROM `service_rate_card` `service_rates` LEFT JOIN (SELECT `rate_card_name` FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name` != '' GROUP BY `rate_card_name`) `rate` ON 1=1 WHERE `service_rates`.`deleted`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `service_rate_card` SET `deleted`=1")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "INSERT INTO `company_rate_card` (`item_id`,`rate_card_name`,`start_date`,`end_date`,`alert_date`,`alert_staff`,`tile_name`,`deleted`,`margin`,`profit`,`cost`,`cust_price`,`uom`,`created_by`,`history`) SELECT `tile_rates`.`src_id`, `rate`.`rate_card_name`, `tile_rates`.`start_date`, `tile_rates`.`end_date`, `tile_rates`.`alert_date`, `tile_rates`.`alert_staff`, `tile_name`, `tile_rates`.`deleted`,`profit_percent`,`profit_dollar`,`cost`, `price` `cust_price`, `uom`,`tile_rates`.`created_by`,`tile_rates`.`history` FROM `tile_rate_card` `tile_rates` LEFT JOIN (SELECT `rate_card_name` FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name` != '' GROUP BY `rate_card_name`) `rate` ON 1=1 WHERE `tile_rates`.`deleted`=0")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "UPDATE `tile_rate_card` SET `deleted`=1")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		set_config($dbc, 'db_version_jonathan', 6);
	}
	
	echo "Jonathan's DB Changes Done<br />\n";
?>