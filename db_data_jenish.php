<?php
/*
 * Jenish's DB changes
 */
echo "====== Jenish's db changes: ======\n";

/*if(!mysqli_query($dbc,"")) {
	echo "Error: ".mysqli_error($dbc)."\n";
}*/

/****************** Adding indexing for Ticket tables *********************/

//Ticket Table
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `contactid` (`contactid`)");
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `businessid` (`businessid`)");
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `contact_business` (`contactid`,`businessid`)");
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `contact_client` (`contactid`,`clientid`)");
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `client_contact_business` (`contactid`,`businessid`,`clientid`)");
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `clientid` (`clientid`)");
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `projectid` (`projectid`)");
mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `businessid` (`siteid`)");

//Ticket Timer
mysqli_query($dbc, "ALTER TABLE `ticket_timer` ADD INDEX `ticketid` (`ticketid`)");
mysqli_query($dbc, "ALTER TABLE `ticket_timer` ADD INDEX `created_by` (`created_by`)");
mysqli_query($dbc, "ALTER TABLE `ticket_timer` ADD INDEX `timer_type` (`timer_type`)");

//Ticket History
mysqli_query($dbc, "ALTER TABLE `ticket_history` ADD INDEX `userid` (`userid`)");
mysqli_query($dbc, "ALTER TABLE `ticket_history` ADD INDEX `ticketid` (`ticketid`)");


/****************** Adding indexing for Email Communication tables *********************/
//email_communication table
mysqli_query($dbc, "ALTER TABLE `email_communication` ADD INDEX `contactid` (`contactid`)");
mysqli_query($dbc, "ALTER TABLE `email_communication` ADD INDEX `businessid` (`businessid`)");
mysqli_query($dbc, "ALTER TABLE `email_communication` ADD INDEX `contact_business` (`contactid`,`businessid`)");

/****************** Adding indexing for Project tables *********************/
//project table
mysqli_query($dbc, "ALTER TABLE `project` ADD INDEX `contactid` (`contactid`)");
mysqli_query($dbc, "ALTER TABLE `project` ADD INDEX `businessid` (`businessid`)");
mysqli_query($dbc, "ALTER TABLE `project` ADD INDEX `clientid` (`clientid`)");
mysqli_query($dbc, "ALTER TABLE `project` ADD INDEX `reviewer_id` (`reviewer_id`)");

//project_billable table
mysqli_query($dbc, "ALTER TABLE `project_billable` ADD INDEX `projectid` (`projectid`)");

//project_details table
mysqli_query($dbc, "ALTER TABLE `project_detail` ADD INDEX `projectid` (`projectid`)");

//project history
mysqli_query($dbc, "ALTER TABLE `project_history` ADD INDEX `projectid` (`projectid`)");

//project manage
mysqli_query($dbc, "ALTER TABLE `project_manage` ADD INDEX `businessid` (`businessid`)");
mysqli_query($dbc, "ALTER TABLE `project_manage` ADD INDEX `contactid` (`contactid`)");
mysqli_query($dbc, "ALTER TABLE `project_manage` ADD INDEX `contact_business` (`contactid`,`businessid`)");

mysqli_query($dbc, "ALTER TABLE `project_manage_assign_to_timer` ADD INDEX `projectmanageid` (`projectmanageid`)");

mysqli_query($dbc, "ALTER TABLE `project_milestone_checklist` ADD INDEX `projectid` (`projectid`)");

mysqli_query($dbc, "ALTER TABLE `project_path_custom_milestones` ADD INDEX `projectid` (`projectid`)");

mysqli_query($dbc, "ALTER TABLE `project_scope` ADD INDEX `projectid` (`projectid`)");

mysqli_query($dbc, "ALTER TABLE `client_project_milestone_checklist` ADD INDEX `projectid` (`projectid`)");

mysqli_query($dbc, "ALTER TABLE `client_project` ADD INDEX `clientid` (`clientid`)");

mysqli_query($dbc, "ALTER TABLE `project` ADD INDEX `deleted` (`deleted`)");

mysqli_query($dbc, "ALTER TABLE `project` ADD INDEX `project_lead` (`project_lead`)");

mysqli_query($dbc, "ALTER TABLE `project` ADD INDEX `status` (`status`)");

mysqli_query($dbc, "ALTER TABLE `contacts` ADD INDEX `deleted` (`deleted`)");

mysqli_query($dbc, "ALTER TABLE `contacts` ADD INDEX `category` (`category`)");

mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD INDEX `deleted` (`deleted`)");

mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `status` (`status`)");

mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `deleted` (`deleted`)");

mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `calendar_query` (`internal_qa_date`,`deliverable_date`,`to_do_date`,`to_do_end_date`,`internal_qa_contactid`,`deliverable_contactid`)");

mysqli_query($dbc, "DROP INDEX today_date_2 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_3 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_4 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_5 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_6 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_7 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_8 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_9 ON booking");
mysqli_query($dbc, "DROP INDEX today_date_10 ON booking");

mysqli_query($dbc, "ALTER TABLE `booking` ADD INDEX `calendar_query` (`patientid`,`follow_up_call_status`,`appoint_date`,`end_appoint_date`)");

mysqli_query($dbc, "ALTER TABLE `booking` ADD INDEX `deleted` (`deleted`)");

mysqli_query($dbc, "ALTER TABLE `teams_staff` ADD INDEX `teamid` (`teamid`)");

mysqli_query($dbc, "ALTER TABLE `teams_staff` ADD INDEX `deleted` (`deleted`)");

mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD INDEX `deleted` (`deleted`)");

mysqli_query($dbc, "ALTER TABLE `contacts_shifts` ADD INDEX `calendar_query` (`contactid`,`startdate`,`enddate`,`repeat_days`,`dayoff_type`)");

mysqli_query($dbc, "ALTER TABLE `ticket_timer` ADD INDEX `created_by` (`created_by`)");

mysqli_query($dbc, "ALTER TABLE `ticket_attached` ADD INDEX `navigation_query` (`arrived`,`completed`,`deleted`,`src_table`,`item_id`)");

mysqli_query($dbc, "ALTER TABLE `tickets` ADD INDEX `main_ticketid` (`main_ticketid`)");




echo "<br> ======Jenish's db changes Done======<br>";
?>
