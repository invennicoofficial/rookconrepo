<?php
	/*
	 * Jenish's DB changes
	 */
	echo "Jenish's db changes:\n";
	
	/*if(!mysqli_query($dbc,"")) {
		echo "Error: ".mysqli_error($dbc)."\n";
	}*/
	
	mysqli_query($dbc, "ALTER TABLE `security_level` ADD `calllog` VARCHAR(100) DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `security_level` ADD `budget` VARCHAR(100) DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `security_level` ADD `calllog_history` TEXT NULL DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `security_level` ADD `budget_history` TEXT NULL DEFAULT NULL");
				
	mysqli_query($dbc, "ALTER TABLE `field_config` ADD `gao` varchar(500) DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `admin_tile_config` ADD `gao` varchar(500) DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `admin_tile_config` ADD `gao_history` TEXT DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `tile_config` ADD `gao` varchar(500) DEFAULT NULL");

	mysqli_query($dbc, "ALTER TABLE `tile_config` ADD `gao_history` TEXT DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `security_level` ADD `gao` varchar(500) DEFAULT NULL");
	
	mysqli_query($dbc, "ALTER TABLE `security_level` ADD `gao_history` varchar(500) DEFAULT NULL");
	
	mysqli_query($dbc, "CREATE TABLE `goals` ( `goalid` INT NOT NULL AUTO_INCREMENT , 
						`goal_heading` VARCHAR(200) NULL , `goal_setter` INT(11) NOT NULL , 
						`goal_set_for` INT(11) NOT NULL , 
						`goal_timeline` TEXT NOT NULL , 
						`start_date` DATE NOT NULL , 
						`end_date` DATE NOT NULL , 
						`reminder` DATE NOT NULL , 
						`goal` TEXT NOT NULL , PRIMARY KEY (`goalid`))
				");
	
	mysqli_query($dbc, "CREATE TABLE `goal_objectives` ( 
						`goal_objectivesid` INT(11) NOT NULL AUTO_INCREMENT , 
						`goalid` INT(11) NOT NULL , `objectives` TEXT NULL , 
						`actions` TEXT NULL , 
						PRIMARY KEY (`goal_objectivesid`)) 
				");
	
	mysqli_query($dbc, "ALTER TABLE `goals` ADD `type` varchar(15) DEFAULT NULL");

	echo "Jenish's db changes Done\n";
?>