<?php
/* Update Databases */

    //Dayana's Database Changes
    echo "Dayana's DB Changes:<br />\n";

    if(!mysqli_query($dbc, "ALTER TABLE `project_path_milestone` ADD `default_path` INT(1) NOT NULL DEFAULT '0' AFTER `checklist`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE `name` = 'timesheet_reporting_styling'"));

    if($get_field_config['configid'] > 0) {
	    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='EGS' WHERE `name`='timesheet_reporting_styling'");
    } else {
        mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('timesheet_reporting_styling', 'EGS')");
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE `name` = 'timesheet_payroll_styling'"));

    if($get_field_config['configid'] > 0) {
	    mysqli_query($dbc, "UPDATE `general_configuration` SET `value`='EGS' WHERE `name`='timesheet_payroll_styling'");
    } else {
        mysqli_query($dbc, "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('timesheet_payroll_styling', 'EGS')");
    }


    mysqli_query($dbc, "ALTER TABLE `estimate_scope` ADD `scope_name` VARCHAR(255) NULL AFTER `templateline`");

    mysqli_query($dbc, "ALTER TABLE `project` ADD `project_colead` INT(11) NOT NULL DEFAULT '0' AFTER `project_lead`");

    mysqli_query($dbc, "ALTER TABLE `tickets` ADD `total_time` VARCHAR(255) NULL AFTER `serviceid`");

    mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `rate_card_holiday_pay` (
      `ratecardholidayid` int(11) NOT NULL AUTO_INCREMENT,
      `rate_type` varchar(500) DEFAULT NULL,
      `positionid` varchar(10) DEFAULT NULL,
      `staffid` varchar(10) DEFAULT NULL,
      `no_of_hours_paid` varchar(50) DEFAULT NULL,
      `who_added` int(10) NOT NULL,
      `when_added` varchar(50) NOT NULL,
      `deleted` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`ratecardholidayid`)
    )");

    echo "Dayana's DB Changes Done<br />\n";
?>