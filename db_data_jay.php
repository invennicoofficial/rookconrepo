<?php
	/*
	 * Jay's DB changes
	 */
	echo "<br /><br />\n\nJay's DB changes:<br />\n";
    
    //11 Dec 2017
    if ( !mysqli_query ( $dbc, "ALTER TABLE `tickets` ADD `details_where` TEXT NULL DEFAULT NULL AFTER `assign_work`, ADD `details_who` TEXT NULL DEFAULT NULL AFTER `details_where`, ADD `details_why` TEXT NULL DEFAULT NULL AFTER `details_who`, ADD `details_what` TEXT NULL DEFAULT NULL AFTER `details_why`, ADD `details_position` TEXT NULL DEFAULT NULL AFTER `details_what`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    
    //20 Feb 2018
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts` ADD `preferred_payment` VARCHAR(50) NULL AFTER `budget`, ADD `name_of_drivers_license` VARCHAR(50) NULL AFTER `interests`, ADD `drivers_license_number` VARCHAR(50) NULL AFTER `name_of_drivers_license`, ADD `drivers_license` VARCHAR(50) NULL AFTER `drivers_license_number`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts` ADD `background_check` INT(1) NULL DEFAULT '0' AFTER `drivers_license`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_description` ADD `drivers_abstract` TEXT NULL AFTER `day_program_key_worker`;" ) ) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    
    //26 Feb 2018
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `task_additional_milestones` (`amid` INT(12) NOT NULL AUTO_INCREMENT, `task_board_id` INT(12) NOT NULL, `milestone` VARCHAR(100) NOT NULL, PRIMARY KEY (`amid`));" )) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    if ( !mysqli_query ( $dbc, "ALTER TABLE `inventory` ADD `name_on_website` TEXT NULL AFTER `name`;" )) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    
    //16 Mar 2018
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts_dates` ADD `remax_start_date` DATE NULL DEFAULT NULL AFTER `contract_start_date`;" )) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    if ( !mysqli_query ( $dbc, "ALTER TABLE `contacts` ADD `agent_id` INT NULL DEFAULT NULL AFTER `sin`;" )) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    
    //26 Mar 2018
    if ( !mysqli_query ( $dbc, "CREATE TABLE IF NOT EXISTS `site_visitors` ( `visitorid` INT(12) NOT NULL AUTO_INCREMENT , `contactid` INT(12) NULL DEFAULT NULL , `session_id` VARCHAR(50) NULL DEFAULT NULL , `visit_date` DATETIME NULL DEFAULT NULL , `ip` VARCHAR(50) NULL DEFAULT NULL , `country` VARCHAR(50) NULL DEFAULT NULL , `province_state` VARCHAR(50) NULL DEFAULT NULL , `city` VARCHAR(50) NULL DEFAULT NULL , PRIMARY KEY (`visitorid`));" )) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    
    //04 May 2018
    if ( !mysqli_query ( $dbc, "ALTER TABLE `inventory` ADD `distributor_price` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `clearance_price`;" )) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    
    //14 May 2018
    if ( !mysqli_query ( $dbc, "ALTER TABLE `inventory` ADD `gtin` VARCHAR(50) NULL DEFAULT NULL AFTER `code`;" )) {
		echo "Error: " . mysqli_error($dbc) . "<br />\n";
	}
    
    //15 May 2018
    set_config($dbc, 'services_default_image', 'fresh-focus-logo-dark.png');
    
	echo "Jay's DB Changes Done<br /><br />\n";
?>