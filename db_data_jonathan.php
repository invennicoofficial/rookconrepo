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
	
	if($db_version_jonathan < 7) {
		// June 16, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `notes` TEXT AFTER `order_number`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
    
		// June 18, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_manifests` ADD `revision` INT(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `signature`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `ticket_manifests` ADD `history` TEXT AFTER `signature`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
    
		// June 20, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `estimate_scope` ADD `pricing` VARCHAR(20) AFTER `price`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
    
		// June 25, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `ref_card` TEXT AFTER `rate_card_name`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		set_config($dbc, 'db_version_jonathan', 7);
	}
	
	if($db_version_jonathan < 8) {
		// July 4, 2018
		if(!mysqli_query($dbc, "ALTER TABLE `phone_communication` ADD `file` TEXT AFTER `comment`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		if(!mysqli_query($dbc, "ALTER TABLE `phone_communication` ADD `manual` VARCHAR(20) AFTER `contactid`")) {
			echo "Error: ".mysqli_error($dbc)."<br />\n";
		}
		
		set_config($dbc, 'db_version_jonathan', 7);
	}
	
	echo "Jonathan's DB Changes Done<br />\n";
?>