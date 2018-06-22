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

    //2018-06-18 - Ticket #7888 - Cleans
    $updated_already = get_config($dbc, 'updated_ticket7888_materials');
    if(empty($updated_already)) {
        $ticket_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `general_configuration` WHERE `name` LIKE 'ticket_fields_%'"),MYSQLI_ASSOC);
        foreach ($ticket_types as $ticket_type) {
            $value_config = ','.$ticket_type['value'].',';
            $value_config = str_replace(',Material Category,',',Material Category,Material Subcategory,',$value_config);
            $value_config = trim($value_config, ',');
            set_config($dbc, $ticket_type['name'], $value_config);
        }
        $value_config = ','.get_field_config($dbc, 'tickets').',';
        $value_config = str_replace(',Material Category,',',Material Category,Material Subcategory,',$value_config);
        $value_config = trim($value_config, ',');
        mysqli_query($dbc, "UPDATE `field_config` SET `tickets` = '$value_config'");

        set_config($dbc, 'updated_ticket7888_materials', '1');
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_templateid_loaded` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-18 - Ticket #7888 - Cleans

    //2018-06-19 - Ticket #7952 - Staff Subtabs & Fields
    $updated_already = get_config($dbc, 'updated_ticket7952_staff');
    if(empty($updated_already)) {
        include('Staff/field_list.php');
        $tabs = ['Profile','Staff'];
        foreach($tabs as $tab) {
            $new_fields = [];
            $staff_fields = mysqli_query($dbc, "SELECT * FROM `field_config_contacts` WHERE `tab` = '$tab' AND IFNULL(`accordion`,'') != '' AND IFNULL(`subtab`,'') != ''");
            while($row = mysqli_fetch_assoc($staff_fields)) {
                $value_config = array_filter(explode(',',$row['contacts']));
                foreach($value_config as $value) {
                    $field_found = false;
                    foreach($field_list as $label => $list) {
                        foreach($list as $subtab => $fields) {
                            foreach($fields as $field) {
                                if($value == $field) {
                                    $field_found = true;
                                    if($subtab == $row['subtab']) {
                                        if(!in_array($field, $new_fields[$subtab][$row['accordiion']])) {
                                            $new_fields[$subtab][$row['accordion']][] = $field;
                                        }
                                    } else {
                                        if(!in_array($field, $new_fields[$subtab][$label])) {
                                            $new_fields[$subtab][$label][] = $field;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if(!$field_found) {
                        if(!in_array($value, $new_fields['hidden']['hidden'])) {
                            $new_fields['hidden']['hidden'][] = $value;
                        }
                    }
                }
            }
            mysqli_query($dbc, "DELETE FROM `field_config_contacts` WHERE `tab` = '$tab' AND IFNULL(`accordion`,'') != '' AND IFNULL(`subtab`,'') != ''");
            foreach($new_fields as $subtab => $accordion) {
                foreach($accordion as $label => $fields) {
                    mysqli_query($dbc, "INSERT INTO `field_config_contacts` (`tab`, `subtab`, `accordion`, `contacts`) VALUES ('$tab', '$subtab', '$label', ',".implode(',', $fields).",')");
                }
            }
        }
        $staff_tabs = explode(',',get_config($dbc, 'staff_field_subtabs'));
        $staff_subtabs = array_column(mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `subtab` FROM `field_config_contacts` WHERE `tab` = 'Staff' AND IFNULL(`subtab`,'') != ''"),MYSQLI_ASSOC),'subtab');
        if(in_array('staff_bio',$staff_subtabs)) {
            $staff_tabs[] = 'Staff Bio';
        }
        if(in_array('health_concerns',$staff_tabs)) {
            $staff_tabs[] = 'Health Concerns';
        }
        if(in_array('allergies',$staff_tabs)) {
            $staff_tabs[] = 'Allergies';
        }
        if(in_array('company_benefits',$staff_tabs)) {
            $staff_tabs[] = 'Company Benefits';
        }
        $staff_tabs = implode(',',array_filter($staff_tabs));
        set_config($dbc, 'staff_field_subtabs', $staff_tabs);
        set_config($dbc, 'updated_ticket7952_staff', 1);
    }
    //2018-06-19 - Ticket #7952 - Staff Subtabs & Fields

    //2018-06-20 - TIcket #7967 - Multiple Sites
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `main_siteid` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-20 - TIcket #7967 - Multiple Sites

    //2018-06-21 - Ticket #8000 - HR Default Email
    $updated_already = get_config($dbc, 'updated_ticket8000_emails');
    if(empty($updated_already)) {
        $manual_emails = mysqli_query($dbc, "SELECT * FROM `general_configuration` WHERE `name` LIKE 'manual_%_email'");
        while($manual_email = mysqli_fetch_assoc($manual_emails)) {
            if($manual_email['value'] == 'dayanasanjay@yahoo.com') {
                set_config($dbc, $manual_email['name'], '');
            }
        }
        set_config($dbc, 'updated_ticket8000_emails', 1);
    }

    //2018-06-21 - Ticket #8000 - HR Default Email

    //2018-06-21 - Ticket #7736 - Shift Reports & My Shifts
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `attached_contacts` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_pdf` ADD `attached_contactid` int(11) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-21 - Ticket #7736 - Shift Reports & My Shifts

    echo "Baldwin's DB Changes Done<br />\n";
?>