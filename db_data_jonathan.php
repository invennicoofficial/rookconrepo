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
		// June 14, 2018
		
		set_config($dbc, 'db_version_jonathan', 6);
	}
	
	echo "Jonathan's DB Changes Done<br />\n";
?>