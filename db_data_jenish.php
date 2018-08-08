<?php
/*
 * Jenish's DB changes
 */
echo "====== Jenish's db changes: ======\n";

/*if(!mysqli_query($dbc,"")) {
	echo "Error: ".mysqli_error($dbc)."\n";
}*/

/****************** Adding indexing for Ticket tables *********************/


if(!mysqli_query($dbc, "ALTER TABLE `contacts_history` ADD `before_change` TEXT AFTER `description`")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

mysqli_query($dbc, "CREATE TABLE `checklist_history` (
  `history_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `before_change` text,
  `contactid` int(11) NOT NULL)"
);

if(!mysqli_query($dbc, "ALTER TABLE `checklist_history` CHANGE `history_id` `history_id` INT(11) NOT NULL AUTO_INCREMENT")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `sales_history` ADD `before_change` TEXT AFTER `history`")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `sales_history` ADD `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `salesid`")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `sales_history` ADD `updated_by` TEXT AFTER `history`")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `hr_history` (
  `history_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `before_change` text,
  `contactid` int(11) NOT NULL)"
);

if(!mysqli_query($dbc, "ALTER TABLE `hr_history` ADD PRIMARY KEY(`history_id`)")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `hr_history` CHANGE `history_id` `history_id` INT(11) NOT NULL AUTO_INCREMENT")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `pos_history` (
  `history_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `before_change` text,
  `contactid` int(11) NOT NULL)"
);

if(!mysqli_query($dbc, "ALTER TABLE `pos_history` ADD PRIMARY KEY(`history_id`)")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `pos_history` CHANGE `history_id` `history_id` INT(11) NOT NULL AUTO_INCREMENT")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

mysqli_query($dbc, "ALTER TABLE `contacts` ADD INDEX `scrum_query` (`deleted`,`status`,`category`)");

mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `inventory_history` (
  `history_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `before_change` text,
  `contactid` int(11) NOT NULL)"
);

if(!mysqli_query($dbc, "ALTER TABLE `inventory_history` ADD PRIMARY KEY(`history_id`)")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `inventory_history` CHANGE `history_id` `history_id` INT(11) NOT NULL AUTO_INCREMENT")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

mysqli_query($dbc, "CREATE TABLE IF NOT EXISTS `security_history` (
  `history_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `before_change` text,
  `contactid` int(11) NOT NULL)"
);

if(!mysqli_query($dbc, "ALTER TABLE `security_history` ADD PRIMARY KEY(`history_id`)")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `security_history` CHANGE `history_id` `history_id` INT(11) NOT NULL AUTO_INCREMENT")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

echo "<br> ======Jenish's db changes Done======<br>";
?>
