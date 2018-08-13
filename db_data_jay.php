<?php
	/*
	 * Jay's DB changes
	 */
	echo "<br /><br />\n\nJay's DB changes:<br />\n";
    
    // 17-Jul-2018
    if(!mysqli_query($dbc, "CREATE TABLE `newsboard_comments` ( `nbcommentid` INT(11) NOT NULL AUTO_INCREMENT, `newsboardid` INT(11) NOT NULL, `contactid` INT(11) NOT NULL, `created_date` DATE NOT NULL, `comment` TEXT NOT NULL, `deleted` INT(1) NOT NULL DEFAULT '0', PRIMARY KEY (`nbcommentid`));")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    // 30-Jul-2018
    if(!mysqli_query($dbc, "CREATE TABLE `local_software_guide` (`local_guideid` INT(11) NOT NULL AUTO_INCREMENT, `guideid` INT(11) NULL DEFAULT NULL, `additional_guide` TEXT NULL DEFAULT NULL, `deleted` SMALLINT(1) NOT NULL DEFAULT '0', PRIMARY KEY (`local_guideid`));")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    
    // 07-Aug-2018
    if(!mysqli_query($dbc, "ALTER TABLE `promotion` ADD `times_used` INT(11) NULL DEFAULT NULL AFTER `deleted`;")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
	
    echo "Jay's DB Changes Done<br /><br />\n";
?>