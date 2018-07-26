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

if(!mysqli_query($dbc, "ALTER TABLE `checklist_history` ADD PRIMARY KEY(`history_id`)")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `checklist_history` CHANGE `history_id` `history_id` INT(11) NOT NULL AUTO_INCREMENT")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

mysqli_query($dbc, "CREATE TABLE `sales_history` (
  `history_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `before_change` text,
  `contactid` int(11) NOT NULL)"
);

if(!mysqli_query($dbc, "ALTER TABLE `sales_history` ADD PRIMARY KEY(`history_id`)")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

if(!mysqli_query($dbc, "ALTER TABLE `sales_history` CHANGE `history_id` `history_id` INT(11) NOT NULL AUTO_INCREMENT")) {
  echo "Error: ".mysqli_error($dbc)."<br />\n";
}

echo "<br> ======Jenish's db changes Done======<br>";
?>
