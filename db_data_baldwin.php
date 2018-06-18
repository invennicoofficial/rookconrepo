<?php
/* Update Databases */

    //Baldwin's Database Changes
    echo "Baldwin's DB Changes:<br />\n";
    
    //2018-06-15 - TIcket #7838 - Calendar Lock Icon
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `calendar_history` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `calendar_history` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-15 - TIcket #7838 - Calendar Lock Icon

    echo "Baldwin's DB Changes Done<br />\n";
?>