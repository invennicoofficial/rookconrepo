<?php
/* Update Databases */

    //Dayana's Database Changes
    echo "Dayana's DB Changes:<br />\n";

mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `field_config_ticket_security` (
  `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT,
  `security_level` varchar(200) DEFAULT NULL,
  `subtabs_hidden` text,
  `subtabs_viewonly` text NOT NULL,
  `fields_hidden` text NOT NULL,
  `fields_viewonly` text NOT NULL,
  PRIMARY KEY (`fieldconfigid`)
)");

    echo "Dayana's DB Changes Done<br />\n";
?>