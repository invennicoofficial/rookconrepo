<?php
/* Update Databases */

    //Baldwin's Database Changes
    echo "Baldwin's DB Changes:<br />\n";

    //2018-01-09 - Ticket #5329 - Meetings Timer
    if(!mysqli_query($dbc, "CREATE TABLE `agenda_meeting_timer` (
        `timerid` int(11) NOT NULL,
        `agendameetingid` int(11) NOT NULL,
        `timer` time,
        `timer_type` varchar(100),
        `start_timer_time` varchar(100) DEFAULT 0,
        `start_time` varchar(50),
        `end_time` varchar(50),
        `created_by` int(10),
        `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `agenda_meeting_timer`
        ADD PRIMARY KEY (`timerid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `agenda_meeting_timer`
        MODIFY `timerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `agendameetingid` int(11) NOT NULL AFTER `ticketid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-09 - Ticket #5329 - Meetings Timer

    //2018-01-10 - Ticket #5645 - Staff Schedule Auto-Lock
    if(!mysqli_query($dbc, "CREATE TABLE `staff_schedule_autolock_reminders` (
        `reminderid` int(11) NOT NULL,
        `date` date NOT NULL,
        `sent` int(1) NOT NULL DEFAULT 1,
        `log` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `staff_schedule_autolock_reminders`
        ADD PRIMARY KEY (`reminderid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `staff_schedule_autolock_reminders`
        MODIFY `reminderid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-10 - Ticket #5645 - Staff Schedule Auto-Lock

    //2018-01-10 - Ticket #5638 - Notable Happenings Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `followup_reminder_sent` int(1) NOT NULL DEFAULT 0 AFTER `assign_followup`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `email_error_log` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `incident_report` ADD `project_type` varchar(500) NOT NULL AFTER `projectid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-10 - Ticket #5638 - Notable Happenings Revisions

    //2018-01-12 - Ticket #5675 - Dispatch Calendar Changes & Bug Fixes
    if(!mysqli_query($dbc, "ALTER TABLE `calendar_notes` ADD `is_equipment` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-12 - Ticket #5675 - Dispatch Calendar Changes & Bug Fixes

    //2018-01-15 - Ticket #5245 - Activity Log Export
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_ticket_log` (
        `fieldconfigid` int(11) NOT NULL,
        `template` varchar(500) NOT NULL,
        `header` text NOT NULL,
        `header_logo` varchar(500) NOT NULL,
        `footer` text NOT NULL,
        `fields` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_log`
        ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_log`
        MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-15 - Ticket #5245 - Activity Log Export

    //2018-01-16 - Ticket #5719 - Sales Orders Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so` ADD `auto_archive_days` int(11) NOT NULL DEFAULT 30")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so` ADD `default_template` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `status_date` date NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `status_date` date NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `frequency` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `frequency_type` varchar(255) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `frequency` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `frequency_type` varchar(255) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-16 - Ticket #5719 - Sales Orders Revisions

    //2018-01-16 - Ticket #5718 - Driving Log Mileage PDF
    if(!mysqli_query($dbc, "CREATE TABLE `driving_log_mileage_pdf_setting` (
        `pdfsettingid` int(11) NOT NULL,
        `pdf_logo` varchar(500) NOT NULL,
        `header_text` text NOT NULL,
        `header_align` varchar(200) NOT NULL,
        `header_font` varchar(200) NOT NULL,
        `header_size` int(11) NOT NULL,
        `header_color` varchar(200) NOT NULL,
        `footer_text` text NOT NULL,
        `footer_align` varchar(200) NOT NULL,
        `footer_font` varchar(200) NOT NULL,
        `footer_size` int(11) NOT NULL,
        `footer_color` varchar(200) NOT NULL,
        `body_font` varchar(200) NOT NULL,
        `body_size` int(11) NOT NULL,
        `body_color` varchar(200) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `driving_log_mileage_pdf_setting` ADD PRIMARY KEY (`pdfsettingid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `driving_log_mileage_pdf_setting` MODIFY `pdfsettingid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-16 - Ticket #5718 - Driving Log Mileage PDF

    //2018-01-16 - Ticket #5720 - Charts No Clients
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_custom_charts_settings` (
        `fieldconfigid` int(11) NOT NULL,
        `name` varchar(500) NOT NULL,
        `no_client` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts_settings` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts_settings` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_charts` ADD `no_client` int(1) NOT NULL DEFAULT 0 AFTER `checked_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-16 - Ticket #5720 - Charts No Clients

    //2018-01-17 - Ticket #5682 - Ticket Status Color Code
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_ticket_status_color` (
        `fieldconfigid` int(11) NOT NULL,
        `status` varchar(500) NOT NULL,
        `color` varchar(255) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_status_color` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_status_color` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-17 - Ticket #5682 - Ticket Status Color Code

    //2018-01-17 - Ticket #5729 - Rename/Hide Fields
    $updated_already = get_config($dbc, 'updated_ticket5729_profile_fields');
    if(empty($updated_already)) {
        $staff_field_subtabs = get_config($dbc, 'staff_field_subtabs');
        $staff_field_subtabs = explode(',',$staff_field_subtabs);
        $staff_field_subtabs[] = 'Goals and Objectives';
        $staff_field_subtabs = array_filter(array_unique($staff_field_subtabs));
        $staff_field_subtabs = ','.implode(',',$staff_field_subtabs).',';
        set_config($dbc, 'staff_field_subtabs', $staff_field_subtabs);
        set_config($dbc, 'updated_ticket5729_profile_fields', '1');
    }
    $updated_already = get_config($dbc, 'updated_ticket5729_ticket_fields');
    if(empty($updated_already)) {
        $ticket_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `general_configuration` WHERE `name` LIKE 'ticket_fields_%'"),MYSQLI_ASSOC);
        foreach ($ticket_types as $ticket_type) {
            $value_config = explode(',', $ticket_type['value']);
            if(in_array("Complete",$value_config)) {
                $value_config[] = "Complete Sign & Force Complete";
            }
            $value_config = implode(',', $value_config);
            set_config($dbc, $ticket_type['name'], $value_config);
        }
        $value_config = explode(',',get_field_config($dbc, 'tickets'));
        if(in_array("Complete",$value_config)) {
            $value_config[] = "Complete Sign & Force Complete";
        }
        $value_config = implode(',', $value_config);
        mysqli_query($dbc, "UPDATE `field_config` SET `tickets` = '$value_config'");

        set_config($dbc, 'updated_ticket5729_ticket_fields', '1');
    }
    //2018-01-17 - Ticket #5729 - Rename/Hide Fields

    //2018-01-17 - Ticket #5727 - Rename/Hide Fields
    $updated_already = get_config($dbc, 'updated_ticket5727_ticket_fields');
    if(empty($updated_already)) {
        $ticket_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `general_configuration` WHERE `name` LIKE 'ticket_fields_%'"),MYSQLI_ASSOC);
        foreach ($ticket_types as $ticket_type) {
            $value_config = explode(',', $ticket_type['value']);
            if(in_array("Details",$value_config)) {
                $value_config[] = "Detail Project";
            }
            $value_config = implode(',', $value_config);
            set_config($dbc, $ticket_type['name'], $value_config);
        }
        $value_config = explode(',',get_field_config($dbc, 'tickets'));
        if(in_array("Details",$value_config)) {
            $value_config[] = "Detail Project";
        }
        $value_config = implode(',', $value_config);
        mysqli_query($dbc, "UPDATE `field_config` SET `tickets` = '$value_config'");

        set_config($dbc, 'updated_ticket5727_ticket_fields', '1');
    }
    //2018-01-17 - Ticket #5727 - Rename/Hide Fields

    //2018-01-19 - Ticket #5259 - Mobile Calendar View
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD INDEX (`ticketid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-19 - Ticket #5259 - Mobile Calendar View

    //2018-01-22 - Ticket #5494 - Tickets Tile - Charts
    if(!mysqli_query($dbc, "ALTER TABLE `patientform_pdf` ADD `ticketid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-22 - Ticket #5494 - Tickets Tile - Charts

    //2018-01-23 - Ticket #5824 - Start Day Direct/Indirect Hours
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `day_tracking_type` varchar(255) NOT NULL AFTER `shift_tracked`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-23 - Ticket #5824 - Start Day Direct/Indirect Hours

    //2018-01-23 - Ticket #5751 - Start Day Clients
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `created_by` int(11) NOT NULL DEFAULT 0 AFTER `day_tracking_type`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `clientid` int(11) NOT NULL DEFAULT 0 AFTER `created_by`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-23 - Ticket #5751 - Start Day Clients

    //2018-01-24 - TIcket #5826 - Time Sheets Signature Box
    if(!mysqli_query($dbc, "CREATE TABLE `time_cards_signature` (
        `time_cards_signature_id` int(11) NOT NULL,
        `date` date NOT NULL,
        `contactid` int(11) NOT NULL,
        `signature` varchar(255) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards_signature` ADD PRIMARY KEY (`time_cards_signature_id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards_signature` MODIFY `time_cards_signature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-24 - TIcket #5826 - Time Sheets Signature Box

    //2018-01-25 - Ticket #5656 - Form Builder Contracts
    if(!mysqli_query($dbc, "CREATE TABLE `user_form_page` (
        `page_id` int(11) NOT NULL,
        `form_id` int(11) NOT NULL,
        `page` int(11) NOT NULL,
        `img` varchar(1000) NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_page` ADD PRIMARY KEY (`page_id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_page` MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `user_form_page_detail` (
        `page_detail_id` int(11) NOT NULL,
        `page_id` int(11) NOT NULL,
        `field_name` varchar(500) NOT NULL,
        `field_label` varchar(500) NOT NULL,
        `top` decimal(10,2) NOT NULL,
        `left` decimal(10,2) NOT NULL,
        `width` decimal(10,2) NOT NULL,
        `height` decimal(10,2) NOT NULL,
        `white_space` int(1) NOT NULL DEFAULT 0,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_page_detail` ADD PRIMARY KEY (`page_detail_id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_page_detail` MODIFY `page_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `page_by_page` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `hide_labels` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-25 - Ticket #5656 - Form Builder Contracts

    //2018-01-29 - Ticket #5415 - Employee Evaluation
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_head_styling` varchar(500) NOT NULL AFTER `default_head_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_foot_styling` varchar(500) NOT NULL AFTER `default_foot_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_body_heading_styling` varchar(500) NOT NULL AFTER `default_body_heading_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_body_styling` varchar(500) NOT NULL AFTER `default_body_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_user_forms` ADD `default_section_heading_styling` varchar(500) NOT NULL AFTER `default_section_heading_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `header_styling` varchar(500) NOT NULL AFTER `header_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `footer_styling` varchar(500) NOT NULL AFTER `footer_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `body_heading_styling` varchar(500) NOT NULL AFTER `body_heading_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `body_styling` varchar(500) NOT NULL AFTER `body_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `section_heading_styling` varchar(500) NOT NULL AFTER `section_heading_color`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-29 - Ticket #5415 - Employee Evaluation

    //2018-01-30 - Ticket #5950 - Dispatch Calendar Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `appt_calendar_locations` text AFTER `appt_calendar_regions`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `appt_calendar_classifications` text AFTER `appt_calendar_locations`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-01-30 - Ticket #5950 - Dispatch Calendar Revisions

    //2018-02-01 - Ticket #5897 - Client Info Tile
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_email_address` varchar(200) AFTER `email_address`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_address` varchar(255) AFTER `address`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_business_address` varchar(500) AFTER `business_address`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_city` varchar(100) AFTER `city`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_province` varchar(100) AFTER `province`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_country` varchar(100) AFTER `country`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_postal_code` varchar(20) AFTER `postal_code`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `second_google_maps_address` varchar(500) AFTER `google_maps_address`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_dates` ADD `contract_end_date` date")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-01 - Ticket #5897 - Client Info Tile

    //2018-02-05 - Ticket #6015 - More Dispatch Calendar & Other Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_security` ADD `classification_access` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-05 - Ticket #6015 - More Dispatch Calendar & Other Revisions

    //2018-02-06 - Ticket #5940 - Sort TIcket Fields Within Sections & Custom Accordions
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_ticket_fields` (
        `fieldconfigid` int(11) NOT NULL,
        `ticket_type` varchar(500) NOT NULL,
        `accordion` varchar(500) NOT NULL,
        `fields` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_fields` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_fields` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-06 - Ticket #5940 - Sort TIcket Fields Within Sections & Custom Accordions

    //2018-02-07 - Ticket #5286 - Custom Documents
    if(!mysqli_query($dbc, "CREATE TABLE `custom_documents` (
        `custom_documentsid` int(11) NOT NULL,
        `tab_name` varchar(500) NOT NULL,
        `custom_documents_code` varchar(200) DEFAULT NULL,
        `custom_documents_type` varchar(200) DEFAULT NULL,
        `category` varchar(200) DEFAULT NULL,
        `heading` varchar(200) DEFAULT NULL,
        `name` varchar(500) DEFAULT NULL,
        `title` varchar(1000) DEFAULT NULL,
        `fee` varchar(100) DEFAULT NULL,
        `description` text,
        `quote_description` text,
        `invoice_description` text,
        `ticket_description` text,
        `final_retail_price` varchar(50) DEFAULT NULL,
        `admin_price` varchar(50) DEFAULT NULL,
        `wholesale_price` varchar(50) DEFAULT NULL,
        `commercial_price` varchar(50) DEFAULT NULL,
        `custom_price` varchar(50) DEFAULT NULL,
        `minimum_billable` varchar(50) DEFAULT NULL,
        `estimated_hours` varchar(50) DEFAULT NULL,
        `actual_hours` varchar(50) DEFAULT NULL,
        `msrp` varchar(50) DEFAULT NULL,
        `unit_price` decimal(10,2) DEFAULT NULL,
        `unit_cost` decimal(10,2) DEFAULT NULL,
        `rent_price` decimal(10,2) DEFAULT NULL,
        `rental_days` varchar(50) DEFAULT NULL,
        `rental_weeks` varchar(50) DEFAULT NULL,
        `rental_months` varchar(50) DEFAULT NULL,
        `rental_years` varchar(50) DEFAULT NULL,
        `reminder_alert` varchar(50) DEFAULT NULL,
        `daily` varchar(50) DEFAULT NULL,
        `weekly` varchar(50) DEFAULT NULL,
        `monthly` varchar(50) DEFAULT NULL,
        `annually` varchar(50) DEFAULT NULL,
        `total_days` varchar(50) DEFAULT NULL,
        `total_hours` varchar(50) DEFAULT NULL,
        `total_km` varchar(50) DEFAULT NULL,
        `total_miles` varchar(50) DEFAULT NULL,
        `cost` varchar(20) DEFAULT NULL,
        `upload_date` date NOT NULL,
        `deleted` int(11) NOT NULL DEFAULT '0')")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_documents` ADD PRIMARY KEY (`custom_documentsid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_documents` MODIFY `custom_documentsid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `custom_documents_uploads` (
        `certuploadid` int(11) NOT NULL,
        `custom_documentsid` int(10) DEFAULT NULL,
        `type` varchar(100) DEFAULT NULL,
        `document_link` varchar(5000) DEFAULT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_documents_uploads` ADD PRIMARY KEY (`certuploadid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_documents_uploads` MODIFY `certuploadid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `field_config_custom_documents` (
        `fieldconfigid` int(11) NOT NULL,
        `tab_name` varchar(500) NOT NULL,
        `fields` text NOT NULL,
        `dashboard` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_documents` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_documents` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-07 - Ticket #5286 - Custom Documents

    //2018-02-08 - Ticket #6097 - Task Auto Archive Completed Tasks
    if(!mysqli_query($dbc, "ALTER TABLE `tasklist` ADD `status_date` date NOT NULL AFTER `status`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-08 - Ticket #6097 - Task Auto Archive Completed Tasks

    //2018-02-08 - Ticket #5909 - Form Builder Layout
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` ADD `form_layout` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-08 - Ticket #5909 - Form Builder Layout

    //2018-02-09 - Ticket #6090 - Ticket Higher Level Headings
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_ticket_headings` (
        `fieldconfigid` int(11) NOT NULL,
        `ticket_type` varchar(500) NOT NULL,
        `accordion` varchar(500) NOT NULL,
        `heading` varchar(500) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_headings` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_headings` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-09 - Ticket #6090 - Ticket Higher Level Headings

    //2018-02-12 - Ticket #6055 - Members ID Card
    $ffmticket6055_updated = get_config($dbc, 'ffmticket6055_updated');
    if(empty($ffmticket6055_updated)) {
        $value_config = ','.get_field_config($dbc, 'tickets').',';
        if(strpos($value_config, ',Members,') !== FALSE) {
            $value_config .= ',Members Profile,Members Parental Guardian Family Contact,Members Emergency Contact,Members Medical Details,Members Key Methodologies,Members Daily Log Notes';
            set_field_config($dbc, 'tickets', $value_config);
        }
        if(strpos($value_config, ',Wait List,') !== FALSE) {
            $value_config .= ',Wait List Members Medications,Wait List Members Guardians,Wait List Members Emergency Contacts,Wait List Members Key Methodologies,Wait List Members Daily Log Notes';
            set_field_config($dbc, 'tickets', $value_config);
        }
        foreach(array_filter(explode(',',get_config($dbc, 'ticket_tabs'))) as $ticket_tab) {
            $ticket_type = 'ticket_fields_'.config_safe_str($ticket_tab);
            $value_config = get_config($dbc, $ticket_type);
            if(strpos($value_config, ',Members,') !== FALSE) {
                $value_config .= ',Members,Members Profile,Members Parental Guardian Family Contact,Members Emergency Contact,Members Medical Details,Members Key Methodologies,Members Daily Log Notes';
                set_config($dbc, $ticket_type, $value_config);
            }
            if(strpos($value_config, ',Wait List,') !== FALSE) {
                $value_config .= ',Wait List Members Medications,Wait List Members Guardians,Wait List Members Emergency Contacts,Wait List Members Key Methodologies,Wait List Members Daily Log Notes';
                set_config($dbc, $ticket_type, $value_config);
            }
        }
        set_config($dbc, 'ffmticket6055_updated', 1);
    }
    //2018-02-12 - Ticket #6055 - Members ID Card

    //2018-02-12 - Ticket #6142 - Ticket Rename Accordions
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_ticket_accordion_names` (
        `fieldconfigid` int(11) NOT NULL,
        `ticket_type` varchar(500) NOT NULL,
        `accordion` varchar(500) NOT NULL,
        `accordion_name` varchar(500) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_accordion_names` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_accordion_names` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-12 - Ticket #6142 - Ticket Rename Accordions

    //2018-02-13 - Ticket #6143 - HR Recurring Due Dates
    if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `recurring_due_date` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `recurring_due_date_interval` int(11) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `recurring_due_date_type` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `recurring_due_date_reminder` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `hr` ADD `recurring_due_date_email` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `manuals` ADD `recurring_due_date` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `manuals` ADD `recurring_due_date_interval` int(11) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `manuals` ADD `recurring_due_date_type` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `manuals` ADD `recurring_due_date_reminder` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `manuals` ADD `recurring_due_date_email` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-13 - Ticket #6143 - HR Recurring Due Dates

    //2018-02-13 - Ticket #6123 - Tickets Create Settings For All Fields
    $ffmticket6123_updated = get_config($dbc, 'ffmticket6123_updated');
    if(empty($ffmticket6123_updated)) {
        $ticket_types = ['tickets'];
        foreach(array_filter(explode(',',get_config($dbc, 'ticket_tabs'))) as $ticket_tab) {
            $ticket_types[] = 'ticket_fields_'.config_safe_str($ticket_tab);
        }
        foreach($ticket_types as $ticket_type) {
            if($ticket_type == 'tickets') {
                $value_config = ','.get_field_config($dbc, 'tickets').',';
            } else {
                $value_config = ','.get_config($dbc, $ticket_type).',';
            }
            if(strpos($value_config, ',Location,') !== FALSE) {
                $value_config .= 'Location Site,Location Site Info,Location Notes,';
            }
            if(strpos($value_config, ',Ticket Details,') !== FALSE || strpos($value_config, ',Services,') !== FALSE) {
                $value_config .= 'Service Heading,Service Description,';
            }
            if(strpos($value_config, ',Equipment,') !== FALSE) {
                $value_config .= 'Equipment Category,Equipment Make,Equipment Model,Equipment Unit,Equipment Rate,Equipment Status,';
            }
            if(strpos($value_config, ',Materials,') !== FALSE) {
                $value_config .= 'Material Type,';
            }
            if(strpos($value_config, ',Inventory Basic') !== FALSE) {
                $value_config .= 'Inventory Basic Inventory,';
            }
            if(strpos($value_config, ',Deliverables') !== FALSE) {
                $value_config .= 'Deliverable Status,';
            }
            if(strpos($value_config, ',Cancellation') !== FALSE) {
                $value_config .= 'Cancellation Reason,';
            }
            if(strpos($value_config, ',Timer') !== FALSE) {
                $value_config .= 'Time Tracking Estimate Complete,Time Tracking Estimate QA,Time Tracking Time Allotted,Time Tracking Current Time,Time Tracking Timer,Time Tracking Timer Manual,';
            }
            $value_config = trim($value_config, ',');
            if($ticket_type == 'tickets') {
                set_field_config($dbc, 'tickets', $value_config);
            } else {
                set_config($dbc, $ticket_type, $value_config);
            }
        }
        set_config($dbc, 'ffmticket6123_updated', 1);
    }
    //2018-02-13 - Ticket #6123 - Tickets Create Settings For All Fields

    //2018-02-14 - Ticket #6015 - More Dispatch Calendar & Other Revisions
    if(!mysqli_query($dbc, "CREATE TABLE `contacts_last_active` (
        `contactlastactiveid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `last_active` datetime NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_last_active` ADD PRIMARY KEY (`contactlastactiveid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_last_active` MODIFY `contactlastactiveid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_security` ADD `equipment_access` text")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_security` ADD `profile_access` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-14 - Ticket #6015 - More Dispatch Calendar & Other Revisions

    //2018-02-15 - Ticket #6099 - Day Sheet Card View
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `daysheet_styling` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-15 - Ticket #6099 - Day Sheet Card View

    //2018-02-21 - Ticket #6254 - Calendar Speed
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `region` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `con_location` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `classification` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `last_updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `last_updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-21 - Ticket #6254 - Calendar Speed

    //2018-02-21 - Ticket #6365 - Dispatch Calendar Staff/Team Access
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_security` ADD `dispatch_staff_access` INT(1) NOT NULL DEFAULT 1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_security` ADD `dispatch_team_access` INT(1) NOT NULL DEFAULT 1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-21 - Ticket #6365 - Dispatch Calendar Staff/Team Access

    //2018-02-23 - Ticket #6124 - Intake Form Categories
    if(!mysqli_query($dbc, "ALTER TABLE `intake_forms` ADD `category` varchar(500) NOT NULL AFTER `user_form_id`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-23 - Ticket #6124 - Intake Form Categories

    //2018-02-26 - Ticket #6464 - Planner Changes
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `daysheet_rightside_views` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-26 - Ticket #6464 - Planner Changes

    //2018-02-27 - Ticket #6148 - Contracts Tile
    if(!mysqli_query($dbc, "ALTER TABLE `contracts` ADD `favourite` text NOT NULL AFTER `default_list`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contracts` ADD `pinned` text NOT NULL AFTER `favourite`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contracts` ADD `user_form_id` int(11) NOT NULL AFTER `pinned`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contracts` ADD `fields` text NOT NULL AFTER `third_heading`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contracts` ADD `fields` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contracts_completed` ADD `today_date` date NOT NULL AFTER `contract_file`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-02-27 - Ticket #6148 - Contracts Tile

    //2018-03-01 - Ticket #5949 - Form Builder Styling Options
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` ADD `pdf_align` varchar(500) NOT NULL DEFAULT 'left'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_form_fields` ADD `pdf_label` int(11) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-01 - Ticket #5949 - Form Builder Styling Options

    //2018-03-02 - Reload Cache
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `cache_last_reloaded` date NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-02 - Reload Cache

    //2018-03-02 - Project Form Builder Additions
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_project_form` (
        `id` int(11) NOT NULL,
        `project_type` varchar(500) NOT NULL,
        `project_heading` varchar(500) NOT NULL,
        `user_form_id` int(11) NOT NULL,
        `subtab_name` varchar(500) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_project_form`
        ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_project_form`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `project_form` (
        `id` int(11) NOT NULL,
        `project_form_id` int(11) NOT NULL,
        `projectid` int(11) NOT NULL,
        `pdf_id` int(11) NOT NULL,
        `pdf_path` varchar(500) NOT NULL,
        `today_date` date NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_form`
        ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_form`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-02 - Project Form Builder Additions

    //2018-03-05 - Time Sheets PDF Export/Approvals
    $updated_already = get_config($dbc, 'updated_ticket6362_timesheet_fields');
    if(empty($updated_already)) {
        set_config($dbc, 'timesheet_pdf_fields', 'Location,Manager Initials,Coordinator Initials');
        set_config($dbc, 'updated_ticket6362_timesheet_fields', 1);
    }

    if(!mysqli_query($dbc, "ALTER TABLE `field_config_supervisor` ADD `security_level_list` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-05 - Time Sheets PDF Export/Approvals

    //2018-03-06 - Sales Order Changes
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_so_security` (
        `fieldconfigid` int(11) NOT NULL,
        `security_level` varchar(500) NOT NULL,
        `access` varchar(500) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so_security`
        ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so_security`
        MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-06 - Sales Order Changes

    //2018-03-07 - Form Builder
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` CHANGE `header_logo` `header_logo` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_forms` CHANGE `footer_logo` `footer_logo` varchar(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-07 - Form Builder

    //2018-03-07 - Calendar Shift Changes
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD `last_updated_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-07 - Calendar Shift Changes

    //2018-03-08 - Ticket Slider View Option
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `calendar_ticket_slider` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `daysheet_ticket_slider` varchar(200) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-08 - Ticket Slider View Option

    //2018-03-08 - Intake Project Path
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `project_milestone` varchar(500) NOT NULL AFTER `projectid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `flag_colour` varchar(200) NOT NULL AFTER `project_milestone`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-08 - Intake Project Path

    //2018-03-09 - Ticket #6606 - Sales Order Changes
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so_contacts` ADD `sales_order_type` varchar(500) NOT NULL AFTER `fieldconfigid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `sales_order_type` varchar(500) NOT NULL AFTER `sotid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `sales_order_type` varchar(500) NOT NULL AFTER `posid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template` ADD `sales_order_type` varchar(500) NOT NULL AFTER `id`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-09 - Ticket #6606 - Sales Order Changes

    //2018-03-12 - Ticket #6606 - Sales Order More Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `templateid` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `templateid` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `templateid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product` ADD `templateid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_temp` ADD `copied_sotid` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order` ADD `copied_sotid` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `copied_sotid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product` ADD `copied_sotid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product` ADD `sortorder` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product` ADD `heading_sortorder` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `sortorder` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `heading_sortorder` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template_product` ADD `sortorder` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template_product` ADD `heading_sortorder` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `field_config_so_pdf` (
        `fieldconfigid` int(11) NOT NULL,
        `header_logo` varchar(500) NOT NULL,
        `header_logo_align` varchar(500) NOT NULL,
        `header_text` text NOT NULL,
        `header_align` varchar(500) NOT NULL,
        `footer_logo` varchar(500) NOT NULL,
        `footer_logo_align` varchar(500) NOT NULL,
        `footer_text` text NOT NULL,
        `footer_align` varchar(500) NOT NULL,
        `body_font` varchar(500) NOT NULL,
        `body_size` int(11) NOT NULL,
        `body_color` varchar(500) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so_pdf`
        ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_so_pdf`
        MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-12 - Ticket #6606 - Sales Order More Revisions

    //2018-03-13 - Ticket #6493 - Intake: Sales Process Path
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `salesid` int(11) NOT NULL AFTER `contactid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-13 - Ticket #6493 - Intake: Sales Process Path

    //2018-03-15 - TIcket #6666 - Shifts Report
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_contacts_shifts_pdf` (
        `fieldconfigid` int(11) NOT NULL,
        `header_logo` varchar(500) NOT NULL,
        `header_logo_align` varchar(500) NOT NULL,
        `header_text` text NOT NULL,
        `header_align` varchar(500) NOT NULL,
        `footer_logo` varchar(500) NOT NULL,
        `footer_logo_align` varchar(500) NOT NULL,
        `footer_text` text NOT NULL,
        `footer_align` varchar(500) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_shifts_pdf`
        ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_contacts_shifts_pdf`
        MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-15 - TIcket #6666 - Shifts Report

    //2018-03-16 - Ticket #6615 - Charts Tile Program/Site Name
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts_settings` ADD `client_category` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-16 - Ticket #6615 - Charts Tile Program/Site Name

    //2018-03-19 - Ticket #6468 - Tickets - Time On Site Estimate
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_time_estimate` VARCHAR(50) AFTER `service_qty`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-19 - Ticket #6468 - Tickets - Time On Site Estimate

    //2018-03-22 - Ticket #6629 - Intake Forms Attach To Contact/Project
    if(!mysqli_query($dbc, "ALTER TABLE `project_scope` ADD `intakeid` int(11) NOT NULL AFTER `salesorderline`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-22 - Ticket #6629 - Intake Forms Attach To Contact/Project

    //2018-03-27 - Ticket #6462 - Equipment Assignment Contractors
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_equip_assign` ADD `contractor_category` text AFTER `contact_category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_assignment_staff` ADD `contractor` int(1) NOT NULL DEFAULT 0 AFTER `contact_position`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-27 - Ticket #6462 - Equipment Assignment Contractors

    //2018-03-27 - Ticket #6868 - Sales Bug Fixes
    $updated_already = get_config($dbc, 'updated_ticket6868_sales_config');
    if(empty($updated_already)) {
        $field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT `sales` FROM `field_config`"))['sales'];
        if(strpos(','.$field_config.',', ',Lead Information,') !== FALSE) {
            $field_config .= ',Lead Information Lead Value';
        }
        mysqli_query($dbc, "UPDATE `field_config` SET `sales` = '".$field_config."'");
        set_config($dbc, 'updated_ticket6868_sales_config', 1);
    }

    //2018-03-27 - Ticket #6868 - Sales Bug Fixes

    //2018-03-28 - Ticket #6649 - CapitalP Tickets - Personnel
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `waste_manifest` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `ref_ticket` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `pressure_test` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `psv_set` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `purge_closed` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `checked_in` VARCHAR(10) DEFAULT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` ADD `checked_out` VARCHAR(10) DEFAULT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-28 - Ticket #6649 - CapitalP Tickets - Personnel

    //2018-03-29 - Ticket #6885 - Pink Wand Services
    if(!mysqli_query($dbc, "CREATE TABLE `services_service_templates` (
        `templateid` int(11) NOT NULL,
        `name` varchar(500) NOT NULL,
        `serviceid` text NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_service_templates`
        ADD PRIMARY KEY (`templateid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_service_templates`
        MODIFY `templateid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `discount_type` VARCHAR(10) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `discount_value` DECIMAL(10,2) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-03-29 - Ticket #6885 - Pink Wand Services

    //2018-04-02 - Ticket #6649 - Ticket Type Personnel
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_pdf_fields` CHANGE `field_label` `field_label` VARCHAR(100)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-02 - Ticket #6649 - Ticket Type Personnel

    //2018-04-02 - Ticket #6909 - Contact Services Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `service_templates` VARCHAR(200)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-02 - Ticket #6909 - Contact Services Revisions

    //2018-04-02 - Ticket #6700 - Notifications for New Ticket, Comment, Etc.
    if(!mysqli_query($dbc, "CREATE TABLE `journal_notifications` (
        `id` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `src_table` varchar(255) NOT NULL,
        `src_id` int(11) NOT NULL,
        `seen` int(1) NOT NULL DEFAULT 0,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `journal_notifications`
        ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `journal_notifications`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-02 - Ticket #6700 - Notifications for New Ticket, Comment, Etc.

    //2018-04-03 - Ticket #6934 - Services Number of Rooms
    if(!mysqli_query($dbc, "CREATE TABLE `contacts_services` (
        `contactserviceid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `serviceid` int(11) NOT NULL,
        `num_rooms` int(11) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_services`
        ADD PRIMARY KEY (`contactserviceid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_services`
        MODIFY `contactserviceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-03 - Ticket #6934 - Services Number of Rooms

    //2018-04-03 - Ticket #6470 - Project Custom Details
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_project_custom_details` (
        `fieldconfigid` int(11) NOT NULL,
        `type` varchar(500) NOT NULL,
        `tab` varchar(500) NOT NULL,
        `heading` varchar(500) NOT NULL,
        `fields` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_project_custom_details`
        ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_project_custom_details`
        MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `project_custom_details` (
        `id` int(11) NOT NULL,
        `projectid` int(11) NOT NULL,
        `tab` varchar(500) NOT NULL,
        `heading` varchar(500) NOT NULL,
        `field` varchar(500) NOT NULL,
        `field_type` varchar(500) NOT NULL,
        `value` text NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_custom_details`
        ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `project_custom_details`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-03 - Ticket #6470 - Project Custom Details

    //2018-04-04 - Ticket #6963 - Dispatch Calendar Bug Fixes
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `ticket_iframe_cache_last_reloaded` date NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-04 - Ticket #6963 - Dispatch Calendar Bug Fixes

    //2018-04-06 - Ticket #7014 - Intake Assign To Ticket
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `ticketid` int(11) NOT NULL AFTER `projectid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `intake` ADD `ticket_description` text NOT NULL AFTER `ticketid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-06 - Ticket #7014 - Intake Assign To Ticket

    //2018-04-06 - Ticket #6803 - Sales Order Service Estimated Hours
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product` ADD `time_estimate` varchar(500) NOT NULL AFTER `quantity`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_product_temp` ADD `time_estimate` varchar(500) NOT NULL AFTER `item_price`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `sales_order_template_product` ADD `time_estimate` varchar(500) NOT NULL AFTER `item_price`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-06 - Ticket #6803 - Sales Order Service Estimated Hours

    //2018-04-09 - Ticket #6708 - Calendar: Activity Booking
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `staff_capacity` int(11) NOT NULL AFTER `max_capacity`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-09 - Ticket #6708 - Calendar: Activity Booking

    //2018-04-10 - Ticket #6782 - Labour Tile Rate Cards
    if(!mysqli_query($dbc, "CREATE TABLE `tile_rate_card` (
        `ratecardid` int(11) NOT NULL,
        `tile_name` varchar(500) NOT NULL,
        `src_id` int(11) NOT NULL,
        `start_date` date NOT NULL,
        `end_date` date NOT NULL,
        `uom` varchar(500) NOT NULL,
        `cost` decimal(10,2) NOT NULL,
        `profit_percent` decimal(10,2) NOT NULL,
        `profit_dollar` decimal(10,2) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `history` text NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tile_rate_card`
        ADD PRIMARY KEY (`ratecardid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tile_rate_card`
        MODIFY `ratecardid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if(!mysqli_query($dbc, "CREATE TABLE `dashboard_permission_config` (
        `id` int(11) NOT NULL,
        `tile` varchar(100) NOT NULL,
        `security_level` varchar(100) NOT NULL,
        `field` varchar(100) NOT NULL,
        `status` varchar(100) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `dashboard_permission_config`
        ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `dashboard_permission_config`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $updated_already = get_config($dbc, 'updated_ticket5729_labour_ratecards');
    if(empty($updated_already)) {
        $labours = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `labour` WHERE `deleted` = 0"),MYSQLI_ASSOC);
        foreach($labours as $labour) {
            if(!empty($labour['cost'])) {
                mysqli_query($dbc, "INSERT INTO `tile_rate_card` (`tile_name`, `src_id`, `uom`, `cost`, `price`) VALUES ('labour', '{$labour['labourid']}', 'Cost', '{$labour['cost']}', '{$labour['cost']}')");
            }
            if(!empty($labour['hourly_rate'])) {
                mysqli_query($dbc, "INSERT INTO `tile_rate_card` (`tile_name`, `src_id`, `uom`, `cost`, `price`) VALUES ('labour', '{$labour['labourid']}', 'Hourly Rate', '{$labour['hourly_rate']}', '{$labour['hourly_rate']}')");
            }
            if(!empty($labour['daily_rate'])) {
                mysqli_query($dbc, "INSERT INTO `tile_rate_card` (`tile_name`, `src_id`, `uom`, `cost`, `price`) VALUES ('labour', '{$labour['labourid']}', 'Daily Rate', '{$labour['daily_rate']}', '{$labour['daily_rate']}')");
            }
        }
        set_config($dbc, 'updated_ticket5729_labour_ratecards', 1);
    }
    //2018-04-10 - Ticket #6782 - Labour Tile Rate Cards

    //2018-04-11 - CDS Changes
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_schedule` CHANGE `contactid` `contactid` VARCHAR(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-11 - CDS Changes

    //2018-04-12 - Ticket #6980 - Evergreen Work Orders
    $updated_already = get_config($dbc, 'updated_ticket6908_projecttabs');
    if(empty($updated_already)) {
        $project_tabs = get_config($dbc, 'project_tabs');
        if(empty($project_tabs)) {
            set_config($dbc, 'project_tabs', 'Client,SR&ED,Internal,R&D,Business Development,Process Development,Addendum,Addition,Marketing,Manufacturing,Assembly');
        }
        set_config($dbc, 'updated_ticket6908_projecttabs', 1);
    }
    //2018-04-12 - Ticket #6980 - Evergreen Work Orders

    //2018-04-12 - Ticket #7084 - Site Add Fields
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `key_number` VARCHAR(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `door_code_number` VARCHAR(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-12 - Ticket #7084 - Site Add Fields

    //2018-04-12 - Ticket #7085 - Customer Service Templates
    if(!mysqli_query($dbc, "ALTER TABLE `services_service_templates` ADD `contactid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-12 - Ticket #7085 - Customer Service Templates

    //2018-04-13 - Ticket #6651 - Capital Pressure Truck Pressure Ticket
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `tdg_doc_num` VARCHAR(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `vti_num` VARCHAR(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `banid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `vendorid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `consignorid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `class` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `subclass` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `unit` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `pg` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-13 - Ticket #6651 - Capital Pressure Truck Pressure Ticket

    //2018-04-16 - Ticket #6652 - Capital Pressure Tank Truck Ticket
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `residue` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `location_from` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD `location_to` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-16 - Ticket #6652 - Capital Pressure Tank Truck Ticket

    //2018-04-17 - Ticket #5682 - Ticket Delivery Type Color Code
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_ticket_delivery_color` (
        `fieldconfigid` int(11) NOT NULL,
        `delivery` varchar(500) NOT NULL,
        `color` varchar(255) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_delivery_color` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_delivery_color` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-17 - Ticket #5682 - Ticket Delivery Type Color Code

    //2018-04-18 - Ticket #7098 - MAR Sheet Changes
    if(!mysqli_query($dbc, "CREATE TABLE `marsheet_medication` (
        `marsheetmedicationid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `medicationid` int(11) NOT NULL,
        `route` varchar(200) NOT NULL,
        `dosage` varchar(200) NOT NULL,
        `instructions` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `marsheet_medication` ADD PRIMARY KEY (`marsheetmedicationid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `marsheet_medication` MODIFY `marsheetmedicationid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    $updated_already = get_config($dbc, 'updated_ticket7098_marsheetmeds');
    if(empty($updated_already)) {
        set_config($dbc, 'updated_ticket7098_marsheetmeds', 1);
        $marsheets = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `marsheet` WHERE `deleted` = 0 ORDER BY `marsheetid` DESC"),MYSQLI_ASSOC);
        foreach($marsheets as $marsheet) {
            if($marsheet['medicationid'] > 0) {
                $contactid = $marsheet['contactid'];
                $medicationid = $marsheet['medicationid'];
                $route = $marsheet['route'];
                $dosage = $marsheet['dosage'];
                $instructions = $marsheet['instructions'];
                mysqli_query($dbc, "INSERT INTO `marsheet_medication` (`contactid`, `medicationid`, `route`, `dosage`, `instructions`) SELECT '$contactid', '$medicationid', '$route', '$dosage', '$instructions' FROM (SELECT COUNT(`marsheetmedicationid`) rows FROM `marsheet_medication` WHERE `medicationid` = '$medicationid' AND `contactid` = '$contactid') num WHERE num.rows=0");
            }
        }
    }

    if(!mysqli_query($dbc, "ALTER TABLE `marsheet` CHANGE `medicationid` `medicationid` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-18 - Ticket #7098 - MAR Sheet Changes

    //2018-04-18 - Ticket #7063 - URSA Charts
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_charts_pdf_times` (
        `fieldconfigid` int(11) NOT NULL,
        `chart` varchar(500) NOT NULL,
        `label` varchar(500) NOT NULL,
        `start_time` varchar(255) NOT NULL,
        `end_time` varchar(255) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_charts_pdf_times` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_charts_pdf_times` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-18 - Ticket #7063 - URSA Charts

    //2018-04-23 - Ticket #7111 - Theme Settings
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_security_level_theme` (
        `fieldconfigid` int(11) NOT NULL,
        `security_level` varchar(500) NOT NULL,
        `theme` varchar(500) NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_security_level_theme` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_security_level_theme` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-23 - Ticket #7111 - Theme Settings

    //2018-04-27 - Ticket #7104 - Multiple Contact Categories
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `contacts_sync` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-27 - Ticket #7104 - Multiple Contact Categories

    //2018-04-27 - Ticket #7129 - Cleans Tile: Additional Fields
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `alarm_code_number` VARCHAR(500) NOT NULL AFTER `door_code_number`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-27 - Ticket #7129 - Cleans Tile: Additional Fields

    //2018-04-27 - Ticket #7126 - Tickets: Services Table Checklist
    if(!mysqli_query($dbc, "CREATE TABLE `ticket_service_checklist` (
        `id` int(11) NOT NULL,
        `ticketid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `serviceid` int(11) NOT NULL,
        `checked_date` date NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_service_checklist` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_service_checklist` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-04-27 - Ticket #7126 - Tickets: Services Table Checklist

    //2018-05-01 - Ticket #7227 - Tickets: Services Layout: Tickets
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_service_checklist` ADD `index` int(11) NOT NULL DEFAULT 1 AFTER `serviceid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-01 - Ticket #7227 - Tickets: Services Layout: Tickets

    //2018-05-01 - Ticket #7184 - Time Sheets
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `ticket_attached_id` int(11) NOT NULL AFTER `ticketid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-01 - Ticket #7184 - Time Sheets

    //2018-05-02 - Ticket #7228 - Tickets Action Mode
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_ticket_fields_action` (
        `fieldconfigid` int(11) NOT NULL,
        `ticket_type` varchar(500) NOT NULL,
        `accordion` varchar(500) NOT NULL,
        `fields` text NOT NULL)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_fields_action` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ticket_fields_action` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-02 - Ticket #7228 - Tickets Action Mode

    //2018-05-03 - Ticket #7288 - CapitalP Import Equipment
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `vehicle_access_code` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `cargo` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `lessor` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `group` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `use` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-03 - Ticket #7288 - CapitalP Import Equipment

    //2018-05-07 - Ticket #7315 - Pink Wand Services Changes
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_service_checklist` ADD `checked_by` int(11) NOT NULL AFTER `checked_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "CREATE TABLE `ticket_service_checklist_history` (
        `id` int(11) NOT NULL,
        `ticketid` int(11) NOT NULL,
        `serviceid` int(11) NOT NULL,
        `index` int(11) NOT NULL,
        `history` text NOT NULL,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_service_checklist_history` ADD PRIMARY KEY (`id`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `ticket_service_checklist_history` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `serviceid` `serviceid` varchar(1000)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` CHANGE `service_qty` `service_qty` varchar(1000)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    $updated_already = get_config($dbc, 'updated_ticket7315_ticketheading');
    if(empty($updated_already)) {
        $ticket_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `general_configuration` WHERE `name` LIKE 'ticket_fields_%'"),MYSQLI_ASSOC);
        foreach ($ticket_types as $ticket_type) {
            $value_config = ','.$ticket_type['value'].',';
            $value_config = str_replace(',Service Heading,',',Service Heading,Details Heading,',$value_config);
            $value_config = trim($value_config, ',');
            set_config($dbc, $ticket_type['name'], $value_config);
        }
        $value_config = ','.get_field_config($dbc, 'tickets').',';
        $value_config = str_replace(',Service Heading,',',Service Heading,Details Heading,',$value_config);
        $value_config = trim($value_config, ',');
        mysqli_query($dbc, "UPDATE `field_config` SET `tickets` = '$value_config'");

        set_config($dbc, 'updated_ticket7315_ticketheading', '1');

        $ticket_orders = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_ticket_fields` WHERE `fields` LIKE '%Service Heading%'"),MYSQLI_ASSOC);
        foreach($ticket_orders as $ticket_order) {
            $fields = ','.$ticket_order['fields'].',';
            $fields = str_replace(',Service Heading,',',Service Heading,Details Heading,',$fields);
            $fields = trim($fields, ',');
            mysqli_query($dbc, "UPDATE `field_config_ticket_fields` SET `fields` = '$fields' WHERE `fieldconfigid` = '".$ticket_order['fieldconfigid']."'");
        }
    }
    //2018-05-07 - Ticket #7315 - Pink Wand Services Changes

    //2018-05-09 - Ticket #7350 - Ticket Summary Hide Positions
    $updated_already = get_config($dbc, 'updated_ticket7350_hideposition');
    if(empty($updated_already)) {
        set_config($dbc, 'ticket_summary_hide_positions', 'Team Lead');
        set_config($dbc, 'updated_ticket7350_hideposition', 1);
    }
    //2018-05-09 - Ticket #7350 - Ticket Summary Hide Positions
    
    //2018-05-10 - Ticket #7338 - Rate Card Changes
    //position_rate_table
    if(!mysqli_query($dbc, "ALTER TABLE `position_rate_table` ADD `start_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `position_id`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `position_rate_table` ADD `end_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `start_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `position_rate_table` ADD `created_by` int(11) NOT NULL AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `position_rate_table` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `position_rate_table` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //staff_rate_table
    if(!mysqli_query($dbc, "ALTER TABLE `staff_rate_table` ADD `start_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `staff_id`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `staff_rate_table` ADD `end_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `start_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `staff_rate_table` ADD `created_by` int(11) NOT NULL AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `staff_rate_table` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `staff_rate_table` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //equipment_rate_table
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_rate_table` ADD `start_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `equipment_id`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_rate_table` ADD `end_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `start_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_rate_table` ADD `created_by` int(11) NOT NULL AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_rate_table` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `equipment_rate_table` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //category_rate_table
    if(!mysqli_query($dbc, "ALTER TABLE `category_rate_table` ADD `start_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `category`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `category_rate_table` ADD `end_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `start_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `category_rate_table` ADD `created_by` int(11) NOT NULL AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `category_rate_table` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `category_rate_table` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //tile_rate_card
    if(!mysqli_query($dbc, "ALTER TABLE `tile_rate_card` ADD `created_by` int(11) NOT NULL AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tile_rate_card` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `tile_rate_card` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-10 - Ticket #7338 - Rate Card Changes

    //2018-05-11 - Ticket #7373 - Cleans Revisions
    $updated_already = get_config($dbc, 'updated_ticket7373_email_complete');
    if(empty($updated_already)) {
        $ticket_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `general_configuration` WHERE `name` LIKE 'ticket_fields_%'"),MYSQLI_ASSOC);
        foreach ($ticket_types as $ticket_type) {
            $value_config = ','.$ticket_type['value'].',';
            $value_config = str_replace(',Complete,',',Complete,Complete Email Users On Complete,',$value_config);
            $value_config = trim($value_config, ',');
            set_config($dbc, $ticket_type['name'], $value_config);
        }
        $value_config = ','.get_field_config($dbc, 'tickets').',';
        $value_config = str_replace(',Complete,',',Complete,Complete Email Users On Complete,',$value_config);
        $value_config = trim($value_config, ',');
        mysqli_query($dbc, "UPDATE `field_config` SET `tickets` = '$value_config'");

        set_config($dbc, 'updated_ticket7373_email_complete', '1');
    }
    //2018-05-11 - Ticket #7373 - Cleans Revisions

    //2018-05-11 - Ticket #7338 - Rate Card Changes
    //service_rate_card
    if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `created_by` int(11) NOT NULL AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `service_rate_card` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //company_rate_card
    if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `start_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `rate_card_name`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `end_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `start_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `company_rate_card` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    //rate_card
    if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `start_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `rate_card_name`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `end_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `start_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `created_by` int(11) NOT NULL AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `alert_date` DATE NOT NULL DEFAULT '0000-00-00' AFTER `end_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `rate_card` ADD `alert_staff` text NOT NULL AFTER `alert_date`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_ratecard` ADD `dashboard_fields` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-11 - Ticket #7338 - Rate Card Changes

    //2018-05-15 - Ticket #7345 - Equipment Staff
    if(!mysqli_query($dbc, "ALTER TABLE `equipment` ADD `staffid` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-15 - Ticket #7345 - Equipment Staff

    //2018-05-17 - Ticket #7442 - Time Sheet Approval Changes
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `manager_approvals` text NOT NULL AFTER `clientid`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` ADD `coord_approvals` text NOT NULL AFTER `manager_approvals`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-17 - Ticket #7442 - Time Sheet Approval Changes

    //2018-05-18 - Ticket #7415 - Time Sheet
    $updated_already = get_config($dbc, 'updated_ticket7315_timesheet');
    if(empty($updated_already)) {
        $value_config = explode(',',get_field_config($dbc, 'time_cards'));
        $value_config[] = 'show_hours';
        $value_config = implode(',',$value_config);

        set_field_config($dbc, 'time_cards', $value_config);
        set_config($dbc, 'updated_ticket7315_timesheet', 1);
    }
    //2018-05-18 - Ticket #7415 - Time Sheet

    //2018-05-24 - Ticket #7421 - Calendar/Charts Changes
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_custom_charts_settings` ADD `add_comments` int(1) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

    if (!mysqli_query($dbc, "CREATE TABLE `custom_charts_comments` (
        `customchartcommid` int(11) NOT NULL,
        `chart_name` varchar(500) NOT NULL,
        `clientid` int(11) NOT NULL,
        `headingid` int(11) NOT NULL,
        `fieldid` int(11) NOT NULL,
        `year` int(11) NOT NULL,
        `month` int(11) NOT NULL,
        `day` int(11) NOT NULL,
        `staffid` int(11) NOT NULL,
        `comment` text NOT NULL,
        `time_stamp` datetime NOT NULL,
        `no_client` int(1) NOT NULL DEFAULT 0,
        `deleted` int(1) NOT NULL DEFAULT 0)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_charts_comments` ADD PRIMARY KEY (`customchartcommid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `custom_charts_comments` MODIFY `customchartcommid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-24 - Ticket #7421 - Calendar/Charts Changes

    //2018-05-28 - Ticket #7557 - Clients Tile Additions
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` CHANGE `assign_staff` `assign_staff` varchar(500) NOT NULL DEFAULT 0")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `property_type` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` CHANGE `contract_allocated_hours` `contract_allocated_hours` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `contract_allocated_hours_type` varchar(500) NOT NULL AFTER `contract_allocated_hours`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_description` ADD `property_instructions` text NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-28 - Ticket #7557 - Clients Tile Additions

    //2018-05-29 - Ticket #7462 - Calendar Additions
    if(!mysqli_query($dbc, "ALTER TABLE `user_settings` ADD `calendar_blocks_last_reloaded` datetime NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-29 - Ticket #7462 - Calendar Additions

    //2018-05-30 - Ticket #7488 - Contacts/Cleans Revisions
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `service_category` varchar(500) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `services_service_templates` ADD `service_category` varchar(500) NOT NULL AFTER `name`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-30 - Ticket #7488 - Contacts/Cleans Revisions

    //2018-05-31 - Ticket #5511 - Staff Security
    $updated_already = get_config($dbc, 'updated_ticket5511_staff_security');
    if(empty($updated_already)) {
        $security_privs = mysqli_query($dbc, "SELECT * FROM `security_privileges` WHERE `tile` = 'staff'");
        while($row = mysqli_fetch_assoc($security_privs)) {
            $privileges = !empty($row['privileges']) ? $row['privileges'] : '';
            if(strpos($privileges, '*view_use_add_edit_delete*') !== FALSE) {
                $privileges .= '*detailed_add*detailed_edit*detailed_archive*';
            }
            if(strpos($privileges, '*hide*') === FALSE) {
                $privileges .= '*detailed_view*detailed_dash*';
            }
            if($privileges != $row['privileges']) {
                mysqli_query($dbc, "UPDATE `security_privileges` SET `privileges` = '$privileges' WHERE `privilegesid` = '{$row['privilegesid']}'");
            }
        }
        set_config($dbc, 'updated_ticket5511_staff_security', 1);
    }
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_staff_security` (
        `fieldconfigid` int(11) NOT NULL,
        `security_level` varchar(200),
        `subtabs_hidden` text,
        `subtabs_viewonly` text,
        `fields_hidden` text,
        `fields_viewonly` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_staff_security` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_staff_security` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-05-31 - Ticket #5511 - Staff Security

    //2018-06-01 - Ticket #7618 - Contacts Tile: Sites
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `bottom_hole` text NOT NULL AFTER `lsd`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts` ADD `site_alias` text NOT NULL AFTER `bottom_hole`")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-01 - Ticket #7618 - Contacts Tile: Sites

    //2018-06-05 - Ticket #7446 - Contact Category Default Levels
    if(!mysqli_query($dbc, "CREATE TABLE `field_config_security_contact_categories` (
        `fieldconfigid` int(11) NOT NULL,
        `category` varchar(500),
        `role` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_security_contact_categories` ADD PRIMARY KEY (`fieldconfigid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_security_contact_categories` MODIFY `fieldconfigid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-05 - Ticket #7446 - Contact Category Default Levels

    //2018-06-05 - Ticket #7680 - Tickets Total Budget Time
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `total_budget_time` time")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-05 - Ticket #7680 - Tickets Total Budget Time

    //2018-06-06 - Ticket #7741 - Time Sheets
    if(!mysqli_query($dbc, "ALTER TABLE `time_cards` CHANGE `approv` `approv` VARCHAR(1) NOT NULL DEFAULT 'N'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-06 - Ticket #7741 - Time Sheets

    //2018-06-06 - Ticket #7460 - Sales Lead Source
    if(!mysqli_query($dbc, "ALTER TABLE `sales` CHANGE `sales` `approv` VARCHAR(1) NOT NULL DEFAULT 'N'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-06 - Ticket #7460 - Sales Lead Source

    //2018-06-07 - Ticket #7601 - Cleans Changes
    if(!mysqli_query($dbc, "ALTER TABLE `tickets` ADD `service_templateid` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-07 - Ticket #7601 - Cleans Changes

    //2018-06-11 - Ticket #7735 - Contacts Subtabs
    if(!mysqli_query($dbc, "CREATE TABLE `contacts_subtab` (
        `contactsubtabid` int(11) NOT NULL,
        `contactid` int(11) NOT NULL,
        `subtabs` text)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_subtab` ADD PRIMARY KEY (`contactsubtabid`)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `contacts_subtab` MODIFY `contactsubtabid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-11 - Ticket #7735 - Contacts Subtabs

    //2018-06-12 - Ticket #7815 - Agendas & Meetings Additions
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_agendas_meetings` ADD `default_business` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_agendas_meetings` ADD `default_contact` int(11) NOT NULL")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `field_config_agendas_meetings` ADD `subcommittee_types` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    if(!mysqli_query($dbc, "ALTER TABLE `agenda_meeting` ADD `subcommittee` varchar(500)")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
    //2018-06-12 - Ticket #7815 - Agendas & Meetings Additions

    echo "Baldwin's DB Changes Done<br />\n";
?>