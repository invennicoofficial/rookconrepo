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


echo "<br> ======Jenish's db changes Done======<br>";
?>
