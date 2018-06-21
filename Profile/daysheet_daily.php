<!-- Daysheet Daily Overview -->
<?php
$reminderids = [];
// Retrieve Data and Populate Daysheet Tables
//Reminders
$reminders_query = "SELECT * FROM `reminders` WHERE `reminder_date` = '$daily_date' AND `contactid` = '$contactid' AND `deleted` = 0";
$reminders_result = mysqli_fetch_all(mysqli_query($dbc, $reminders_query),MYSQLI_ASSOC);
foreach ($reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['reminderid']."', '".$contactid."', 'reminder', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['reminderid']."' AND `type` = 'reminder' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['reminderid']."' AND `type` = 'reminder' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
    if($reminder['done'] == 1) {
        mysqli_query($dbc, "UPDATE `daysheet_reminders` SET `done` = 1 WHERE `daysheetreminderid` = '$reminderid'");
    }
}
$sales_reminders_query = "SELECT * FROM `sales` WHERE `new_reminder` = '$daily_date' AND (`primary_staff` = '$contactid' OR CONCAT(',',`share_lead`,',') LIKE '%,$contactid,%')";
$sales_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $sales_reminders_query),MYSQLI_ASSOC);
foreach ($sales_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['salesid']."', '".$contactid."', 'sales', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['salesid']."' AND `type` = 'sales' AND `date` = '".$daily_date."' AND `contactid` ='".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['salesid']."' AND `type` = 'sales' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$so_reminders_query = "SELECT * FROM `sales_order` WHERE `next_action_date` = '$daily_date' AND (`primary_staff` = '$contactid' OR CONCAT(',',`assign_staff`,',') LIKE '%,$contactid,%')";
$so_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $so_reminders_query),MYSQLI_ASSOC);
foreach ($so_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['posid']."', '".$contactid."', 'sales_order', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['posid']."' AND `type` = 'sales_order' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['posid']."' AND `type` = 'sales_order' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$sot_reminders_query = "SELECT * FROM `sales_order_temp` WHERE `next_action_date` = '$daily_date' AND (`primary_staff` = '$contactid' OR CONCAT(',',`assign_staff`,',') LIKE '%,$contactid,%') AND `deleted` = 0";
$sot_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $sot_reminders_query),MYSQLI_ASSOC);
foreach ($sot_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['sotid']."', '".$contactid."', 'sales_order_temp', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['sotid']."' AND `type` = 'sales_order_temp' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['sotid']."' AND `type` = 'sales_order_temp' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$estimates_reminders_query = "SELECT `ea`.*, `e`.`estimate_name` FROM `estimate_actions` AS `ea` JOIN `estimate` AS `e` ON (`ea`.`estimateid`=`e`.`estimateid`) WHERE FIND_IN_SET ('$contactid', `e`.`assign_staffid`) AND `e`.`deleted`=0 AND FIND_IN_SET('$contactid', `ea`.`contactid`) AND `ea`.`deleted`=0 AND `ea`.`due_date`='". date('Y-m-d', strtotime($daily_date)) ."'";
$estimates_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $estimates_reminders_query),MYSQLI_ASSOC);
foreach ($estimates_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['id']."', '".$contactid."', 'estimate', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'estimate' AND `date` = '".$daily_date."' AND `contactid` ='".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'estimate' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$projects_reminders_query = "SELECT `pa`.*, `p`.`project_name` FROM `project_actions` AS `pa` JOIN `project` AS `p` ON (`pa`.`projectid`=`p`.`projectid`) WHERE FIND_IN_SET ('$contactid', `pa`.`contactid`) AND `p`.`deleted` = 0 AND `pa`.`deleted` = 0 AND `pa`.`due_date` = '".date('Y-m-d', strtotime($daily_date))."'";
$projects_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $projects_reminders_query),MYSQLI_ASSOC);
foreach ($projects_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['id']."', '".$contactid."', 'project', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'project' AND `date` = '".$daily_date."' AND `contactid` ='".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'project' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$pfu_reminders_query = "SELECT * FROM `project` WHERE `followup` = '".$daily_date."' AND `project_lead` = '".$contactid."'";
$pfu_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $pfu_reminders_query),MYSQLI_ASSOC);
foreach ($pfu_reminders_result as $key => $reminder) {
    $project_exists = false;
    foreach ($projects_reminders_result as $project_action) {
        if ($project_action['projectid'] == $reminder['projectid']) {
            $project_exists = true;
            unset($pfu_reminders_result[$key]);
        }
    }
    if (!$project_exists) {
        mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['projectid']."', '".$contactid."', 'project_followup', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['projectid']."' AND `type` = 'project_followup' AND `date` = '".$daily_date."' AND `contactid` ='".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
        $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['projectid']."' AND `type` = 'project_followup' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
        $reminderids[] = $reminderid;
    }
}
$cert_reminders_query = "SELECT * FROM `certificate` WHERE `reminder_date` = '$daily_date' AND `contactid` = '$contactid'";
$cert_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $cert_reminders_query),MYSQLI_ASSOC);
foreach ($cert_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['certificateid']."', '".$contactid."', 'certificate', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['certificateid']."' AND `type` = 'certificate' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['certificateid']."' AND `type` = 'certificate' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$alerts_reminders_query = "SELECT * FROM `alerts` WHERE `alert_date` = '$daily_date' AND `alert_user` = '$contactid'";
$alerts_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $alerts_reminders_query),MYSQLI_ASSOC);
foreach ($alerts_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['alertid']."', '".$contactid."', 'alert', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['alertid']."' AND `type` = 'alert' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['alertid']."' AND `type` = 'alert' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$inc_rep_reminders_query = "SELECT * FROM `incident_report` WHERE `ir14` = '$daily_date' AND `assign_followup` = '$contactid' AND `followup_done` = 0 AND `deleted` = 0";
$inc_rep_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $inc_rep_reminders_query),MYSQLI_ASSOC);
foreach ($inc_rep_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['incidentreportid']."', '".$contactid."', 'incident_report', '".$daily_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['incidentreportid']."' AND `type` = 'incident_report' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['incidentreportid']."' AND `type` = 'incident_report' AND `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}

//If reminders not found, mark it as deleted
$reminderids = "'".implode("','",$reminderids)."'";
        $date_of_archival = date('Y-m-d');
mysqli_query($dbc, "UPDATE `daysheet_reminders` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `daysheetreminderid` NOT IN (".$reminderids.") AND `date` = '".$daily_date."' AND `date` >= '".date('Y-m-d')."' AND `contactid` = '".$contactid."' AND `done` = 0 AND `deleted` = 0");

//Tickets
$equipment = [];
$equipment_ids = $dbc->query("SELECT `equipmentid` FROM `equipment_assignment_staff` LEFT JOIN `equipment_assignment` ON `equipment_assignment_staff`.`equipment_assignmentid`=`equipment_assignment`.`equipment_assignmentid` WHERE `equipment_assignment_staff`.`deleted`=0 AND `equipment_assignment`.`deleted`=0 AND `equipment_assignment_staff`.`contactid`='$contactid' AND DATE(`equipment_assignment`.`start_date`) <= '$daily_date' AND DATE(`equipment_assignment`.`end_date`) >= '$daily_date'");
while($equipment[] = $equipment_ids->fetch_assoc()['equipmentid']) { }
$equipment = implode(',',array_filter($equipment));
if($equipment == '') {
	$equipment = 0;
}
if(strtotime($daily_date.' 23:59:59') < time() && get_config($dbc, 'timesheet_hide_past_days') == '1' && $dbc->query("SELECT COUNT(*) `count` FROM `time_cards` WHERE `date`='$daily_date' AND `staff`='{$_SESSION['contactid']}' AND `end_time` IS NULL AND `start_time` IS NOT NULL")->fetch_assoc()['count'] == 0) {
	$filtered_tickets = " AND 1=0 ";
}
$combine_category = get_config($dbc, 'daysheet_ticket_combine_contact_type');
$tickets_query = "SELECT `tickets`.*, IF(`ticket_schedule`.`id` IS NULL,'ticket','ticket_schedule') `ticket_table`, IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`) `to_do_date`, CONCAT('<br>',IFNULL(NULLIF(`ticket_schedule`.`location_name`,''),`ticket_schedule`.`client_name`)) `location_description`, `ticket_schedule`.`id` `stop_id`, `ticket_schedule`.`eta`, `ticket_schedule`.`client_name`, IFNULL(`ticket_schedule`.`address`, `tickets`.`address`) `address`, `ticket_schedule`.`type` `delivery_type`, IFNULL(`ticket_schedule`.`to_do_start_time`, IFNULL(NULLIF(`tickets`.`start_time`,'00:00'),`tickets`.`to_do_start_time`)) `to_do_start_time`, CONCAT(`start_available`,' - ',`end_available`) `availability`, IFNULL(`ticket_schedule`.`status`, `tickets`.`status`) `status`, IFNULL(`ticket_schedule`.`map_link`,`tickets`.`google_maps`) `map_link`, `ticket_schedule`.`notes` `delivery_notes`, `tickets`.`siteid` FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` AND `ticket_schedule`.`deleted`=0 WHERE ((internal_qa_date = '".$daily_date."' AND CONCAT(',',IFNULL(`internal_qa_contactid`,''),',') LIKE '%,".$contactid.",%') OR (`deliverable_date` = '".$daily_date."' AND CONCAT(',',IFNULL(`deliverable_contactid`,''),',') LIKE '%,".$contactid.",%') OR ((`tickets`.`to_do_date` = '".$daily_date."' OR '".$daily_date."' BETWEEN `tickets`.`to_do_date` AND `tickets`.`to_do_end_date` OR `ticket_schedule`.`to_do_date`='".$daily_date."' OR '".$daily_date."' BETWEEN `ticket_schedule`.`to_do_date` AND IFNULL(`ticket_schedule`.`to_do_end_date`,`ticket_schedule`.`to_do_date`)) AND ((CONCAT(',',IFNULL(`tickets`.`contactid`,''),',',IFNULL(`ticket_schedule`.`contactid`,''),',') LIKE '%,".$contactid.",%') OR (`tickets`.`equipmentid` IN ($equipment) AND `tickets`.`equipmentid` > 0) OR (`ticket_schedule`.`equipmentid` IN ($equipment) AND `ticket_schedule`.`equipmentid` > 0)))) ".(in_array('Combine Warehouse Stops',$daysheet_ticket_fields) ? "AND IFNULL(NULLIF(CONCAT(IFNULL(`ticket_schedule`.`address`,''),IFNULL(`ticket_schedule`.`city`,'')),''),CONCAT(IFNULL(`tickets`.`address`,''),IFNULL(`tickets`.`city`,''))) NOT IN (SELECT CONCAT(IFNULL(`ship_to_address`,''),IFNULL(`ship_city`,'')) FROM `contacts` WHERE `category`='".$combine_category."')" : '')." $filtered_tickets AND `tickets`.`deleted` = 0 ORDER BY ".(in_array('Sort Completed to End',$daysheet_ticket_fields) ? "IFNULL(`ticket_schedule`.`status`,`tickets`.`status`)='$completed_ticket_status', " : '')."IFNULL(NULLIF(`ticket_schedule`.`to_do_start_time`,''),IFNULL(NULLIF(`tickets`.`start_time`,'00:00'),`tickets`.`to_do_start_time`)) ASC";
$tickets_result = mysqli_fetch_all(mysqli_query($dbc, $tickets_query),MYSQLI_ASSOC);

//Tasks
$tasks_query = "SELECT * FROM `tasklist` WHERE `contactid` = '".$contactid."' AND `task_tododate` = '".$daily_date."' AND `deleted` = 0";
$tasks_result = mysqli_fetch_all(mysqli_query($dbc, $tasks_query),MYSQLI_ASSOC);

//Checklists
// $user_fav_checklists = get_user_settings()['checklist_fav'];
// $day_of_week = date('w', strtotime($daily_date));
// $day_of_month = date('j', strtotime($daily_date));
// $user_fav_checklists = "'".implode("','", array_filter(explode(',',$user_fav_checklists)))."'";
// $checklists_query = "SELECT * FROM `checklist` WHERE `checklistid` IN ($user_fav_checklists) AND (`assign_staff` LIKE '%,$contactid,%' OR `assign_staff`=',ALL,') AND (`checklist_type` = 'daily' OR (`checklist_type` = 'weekly' AND `reset_day` = '$day_of_week') OR (`checklist_type` = 'monthly' AND `reset_day` = '$day_of_month') OR (`checklist_type` = 'ongoing')) AND `deleted`=0";
// $checklists_result = mysqli_fetch_all(mysqli_query($dbc, $checklists_query),MYSQLI_ASSOC);
$checklists_query = "SELECT * FROM `checklist_actions` WHERE `contactid` = '".$contactid."' AND `action_date` = '".$daily_date."' AND `deleted` = 0";
$checklists_result = mysqli_fetch_all(mysqli_query($dbc, $checklists_query),MYSQLI_ASSOC);
?>
<script type="text/javascript">
$(document).ready(function () {
    $('input[name="daysheet_reminder"]').on('click', function() {
        var daysheet_reminder = $(this);
        var daysheetreminderid = this.value;
        var done = 0;
        if ($(this).is(':checked')) {
            done = 1;
        }
        $.ajax({
            url: '../Profile/profile_ajax.php?fill=daysheet_reminders',
            method: 'POST',
            data: {
                daysheetreminderid: daysheetreminderid,
                done: done
            },
            success: function(response) {
                if (done == 1) {
                    daysheet_reminder.closest('p,.daysheet_row').find('span').css('text-decoration', 'line-through');
                } else {
                    daysheet_reminder.closest('p,.daysheet_row').find('span').css('text-decoration', 'none');
                }
            }
        });
    });
});
</script>

<?php if (in_array('Reminders', $daysheet_fields_config)) {
    $reminders_list = mysqli_query($dbc, "SELECT * FROM `daysheet_reminders` WHERE `date` = '".$daily_date."' AND `contactid` = '".$contactid."' AND `deleted` = 0");
    $num_rows = mysqli_num_rows($reminders_list); ?>
    <h4 style="font-weight: normal;">Reminders</h4>
    <?php if ($num_rows > 0) {
        if($daysheet_styling != 'card') {
            echo '<ul id="reminders_daily">';
        }
        foreach ($reminders_list as $daysheet_reminder) {
            $reminder_label = '';
            if ($daysheet_reminder['type'] == 'reminder') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `reminders` WHERE `reminderid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_url = get_reminder_url($dbc, $reminder);
                if(!empty($reminder_url)) {
                    $reminder_label = '<a href="'.$reminder_url.'">'.$reminder['subject'].'</a>';
                } else {
                    $reminder_label = '<div class="daysheet-span">'.$reminder['subject'].'</div>';
                }
            } else if ($daysheet_reminder['type'] == 'sales') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales` WHERE `salesid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Sales/sale.php?p=preview&id='.$reminder['salesid'].'" style="color: black;">Follow Up Sales: Sales #'.$reminder['salesid'].'</a>';
            } else if ($daysheet_reminder['type'] == 'sales_order') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_order` WHERE `posid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Sales Order/index.php?p=preview&id='.$reminder['posid'].'" style="color: black;">Follow Up '.SALES_ORDER_NOUN.': '.($reminder['name'] != '' ? $reminder['name'] : SALES_ORDER_NOUN.' #'.$reminder['posid']).'</a>';
            } else if ($daysheet_reminder['type'] == 'sales_order_temp') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_order_temp` WHERE `sotid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Sales Order/order.php?p=details&sotid='.$reminder['sotid'].'" style="color: black;">Follow Up '.SALES_ORDER_NOUN.': '.($reminder['name'] != '' ? $reminder['name'] : SALES_ORDER_NOUN.' Form #'.$reminder['sotid']).'</a>';
            } else if ($daysheet_reminder['type'] == 'estimate') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `ea`.*, `e`.`estimate_name` FROM `estimate_actions` AS `ea` JOIN `estimate` AS `e` ON (`ea`.`estimateid`=`e`.`estimateid`) WHERE `ea`.`id` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Estimate/estimates.php?view='.$reminder['estimateid'].'" style="color: black;">Follow Up Estimate: '.$reminder['estimate_name'].'</a>';
            } else if ($daysheet_reminder['type'] == 'project') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `pa`.*, `p`.`project_name` FROM `project_actions` AS `pa` JOIN `project` AS `p` ON (`pa`.`projectid`=`p`.`projectid`) WHERE `pa`.`id` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Project/projects.php?edit='.$reminder['projectid'].'" style="color: black;">Follow Up Project: '.$reminder['project_name'].'</a>';
            } else if ($daysheet_reminder['type'] == 'project_followup') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Project/projects.php?edit='.$reminder['projectid'].'" style="color: black;">Follow Up Project: '.$reminder['project_name'].'</a>';
            } else if ($daysheet_reminder['type'] == 'certificate') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `certificate` WHERE `certificateid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Certificate/index.php?edit='.$reminder['certificateid'].'" style="color: black;">Certificate Reminder: '.$reminder['title'].'</a>';
            } else if ($daysheet_reminder['type'] == 'alert') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `alerts` WHERE `alertid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="'.$reminder['alert_link'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" style="color: black;">Alert: '.$reminder['alert_text'].' - '.$reminder['alert_link'].'</a>';
            } else if ($daysheet_reminder['type'] == 'incident_report') {
                $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `incident_report` WHERE `incidentreportid` = '".$daysheet_reminder['reminderid']."'"));
                $reminder_label = '<a href="../Incident Report/add_incident_report.php?incidentreportid='.$reminder['incidentreportid'].'" style="color: black;">Follow Up '.INC_REP_NOUN.': '.$reminder['type'].' #'.$reminder['incidentreportid'].'</a>';
            }
            if(!empty($reminder_label)) {
                if($daysheet_styling == 'card') {
                    echo '<div class="col-xs-12 daysheet_row"><div class="col-xs-2" style="max-width: 35px;"><input style="position: relative; vertical-align: middle; top: 10px; height: 20px; width: 20px;" type="checkbox" name="daysheet_reminder" value="'.$daysheet_reminder['daysheetreminderid'].'" '.($daysheet_reminder['done'] == 1 ? 'checked="checked"' : '').'></div><div class="col-xs-10 block-group-daysheet"><span '.($daysheet_reminder['done'] == 1 ? 'style="text-decoration: line-through;"' : '').'>'.$reminder_label.'</span></div></div>';
                } else {
                    echo '<p style="font-weight: normal;"><input style="position: relative; vertical-align: middle; top: -0.25em;" class="form-checkbox" type="checkbox" name="daysheet_reminder" value="'.$daysheet_reminder['daysheetreminderid'].'" '.($daysheet_reminder['done'] == 1 ? 'checked="checked"' : '').'>&nbsp;&nbsp;<span '.($daysheet_reminder['done'] == 1 ? 'style="text-decoration: line-through;"' : '').'>'.$reminder_label.'</span></p>';
                }
            }
        }
        if($daysheet_styling != 'card') {
            echo '</ul>';
        }
    } else {
        echo '<ul id="reminders_daily">';
        echo 'No records found.';
        echo '</ul>';
    } ?>
    <div class="clearfix"></div>
    <hr>
<?php } ?>

<?php if (in_array('Tickets', $daysheet_fields_config)) { ?>
    <h4 style="font-weight: normal;"><?= TICKET_TILE ?></h4>
    <?php $no_tickets = true;
    $combined_tickets_shown = [];
	if(in_array('Combine Warehouse Stops', $daysheet_ticket_fields)) {
		$combined_query = "SELECT `tickets`.*, IF(`ticket_schedule`.`id` IS NULL,'ticket','ticket_schedule') `ticket_table`, IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`) `to_do_date`, CONCAT('<br>',IFNULL(NULLIF(`ticket_schedule`.`location_name`,''),`ticket_schedule`.`client_name`)) `location_description`, `ticket_schedule`.`id` `stop_id`, `ticket_schedule`.`eta`, `ticket_schedule`.`client_name`, IFNULL(`ticket_schedule`.`address`, `tickets`.`address`) `address`, `ticket_schedule`.`type` `delivery_type`, IFNULL(`ticket_schedule`.`to_do_start_time`, IFNULL(NULLIF(`tickets`.`start_time`,'00:00'),`tickets`.`to_do_start_time`)) `to_do_start_time`, CONCAT(`start_available`,' - ',`end_available`) `availability`, `ticket_schedule`.`status` `schedule_status` FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` AND `ticket_schedule`.`deleted`=0 WHERE ((internal_qa_date = '".$daily_date."' AND CONCAT(',',IFNULL(`internal_qa_contactid`,''),',') LIKE '%,".$contactid.",%') OR (`deliverable_date` = '".$daily_date."' AND CONCAT(',',IFNULL(`deliverable_contactid`,''),',') LIKE '%,".$contactid.",%') OR ((`tickets`.`to_do_date` = '".$daily_date."' OR '".$daily_date."' BETWEEN `tickets`.`to_do_date` AND `tickets`.`to_do_end_date` OR `ticket_schedule`.`to_do_date`='".$daily_date."' OR '".$daily_date."' BETWEEN `ticket_schedule`.`to_do_date` AND IFNULL(`ticket_schedule`.`to_do_end_date`,`ticket_schedule`.`to_do_date`)) AND ((CONCAT(',',IFNULL(`tickets`.`contactid`,''),',',IFNULL(`ticket_schedule`.`contactid`,''),',') LIKE '%,".$contactid.",%') OR (`tickets`.`equipmentid` IN ($equipment) AND `tickets`.`equipmentid` > 0) OR (`ticket_schedule`.`equipmentid` IN ($equipment) AND `ticket_schedule`.`equipmentid` > 0)))) AND IFNULL(NULLIF(CONCAT(IFNULL(`ticket_schedule`.`address`,''),IFNULL(`ticket_schedule`.`city`,'')),''),CONCAT(IFNULL(`tickets`.`address`,''),IFNULL(`tickets`.`city`,''))) IN (SELECT CONCAT(IFNULL(`address`,''),IFNULL(`city`,'')) FROM `contacts` WHERE `category`='".$combine_category."') $filtered_tickets AND `tickets`.`deleted` = 0 ORDER BY IFNULL(`ticket_schedule`.`address`,`tickets`.`address`), ".(in_array('Sort Completed to End',$daysheet_ticket_fields) ? "IFNULL(`ticket_schedule`.`status`,`tickets`.`status`)='$completed_ticket_status', " : '')."IFNULL(NULLIF(`ticket_schedule`.`to_do_start_time`,''),IFNULL(NULLIF(`tickets`.`start_time`,'00:00'),`tickets`.`to_do_start_time`)) ASC";
		$combined_result = mysqli_fetch_all(mysqli_query($dbc, $combined_query),MYSQLI_ASSOC);
		if(!empty($combined_result)) {
			if($daysheet_styling != 'card') {
				echo '<ul id="tickets_daily">';
			} else {
                echo '<div class="block-group-daysheet">';
            }
			$address = '';
			foreach ($combined_result as $ticket) {
				$new_delivery_color = get_delivery_color($dbc, $ticket['delivery_type']);
				if($new_delivery_color != $delivery_color) {
					$delivery_color = $new_delivery_color;
					if(!empty($delivery_color)) {
						$delivery_style = 'style="background-color: '.$delivery_color.';"';
					} else {
						$delivery_style = '';
					}
					if($daysheet_styling == 'card') {
						echo '<div class="block-group-daysheet" '.$delivery_style.'>';
					} else {
						echo '<li>';
					}
				}

				if($address != $ticket['address']) {
					$address = $ticket['address'];
					echo '<h4 class="pad-5">'.$address.'</h4>';
				}
				$label = $ticket['client_name'].' - '.$ticket['ticket_label'];
				echo '<a href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'&stop='.$ticket['stop_id'].'&action_mode='.$ticket_action_mode.'" class="inline" onclick="overlayIFrameSlider(this.href+\'&calendar_view=true\'); return false;" '.$opacity_styling.'>'.$icon_img.$label.'</a>';

				if(in_array('Combined Details with Confirm',$daysheet_ticket_fields)) {
					echo '<label class="form-checkbox any-width pull-right"><input type="checkbox" '.($ticket['schedule_status'] == $completed_ticket_status || $ticket['status'] == $completed_ticket_status ? 'disabled checked' : '').' onclick="if(confirm(\'By checking off this box, you are agreeing it has been loaded onto your truck\')) { setStatus(\''.$ticket['ticketid'].'\',\''.$ticket['stop_id'].'\',\''.$completed_ticket_status.'\') } else { return false; }">'.$completed_ticket_status.'</label>';
				}
				echo '<div class="clearfix"></div>';
                $combined_tickets_shown[] = $ticket['stop_id'];
			}

			if($daysheet_styling == 'card') {
				echo '</div>';
			} else {
				echo '</li>';
			}
			if($daysheet_styling != 'card') {
				echo '</ul>';
			}
			$no_tickets = false;
		}
	}
	if (!empty($tickets_result)) {
        if($daysheet_styling != 'card') {
            echo '<ul id="tickets_daily">';
        }
        foreach ($tickets_result as $ticket) {
            if(!($ticket['stop_id'] > 0) || !in_array($ticket['stop_id'], $combined_tickets_shown)) {
                $delivery_color = get_delivery_color($dbc, $ticket['delivery_type']);
                if(!empty($delivery_color)) {
                    $delivery_style = 'style="background-color: '.$delivery_color.';"';
                } else {
                    $delivery_style = '';
                }
                if($daysheet_styling == 'card') {
                    echo '<div class="block-group-daysheet" '.$delivery_style.'>';
                } else {
                    echo '<li>';
                }

                $label = daysheet_ticket_label($dbc, $daysheet_ticket_fields, $ticket, $completed_ticket_status);
                $status_icon = get_ticket_status_icon($dbc, $ticket['status']);
                if(!empty($status_icon)) {
                    if($status_icon == 'initials') {
                        $icon_img = '<span class="id-circle-large pull-right" style="background-color: #6DCFF6; font-family: \'Open Sans\';">'.get_initials($ticket['status']).'</span>';
                    } else {
                        $icon_img = '<img src="'.$status_icon.'" class="pull-right" style="max-height: 30px;">';
                    }
                } else {
                    $icon_img = '';
                }

                echo '<a href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$ticket['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'&stop='.$ticket['stop_id'].'&action_mode='.$ticket_action_mode.'" onclick="overlayIFrameSlider(this.href+\'&calendar_view=true\'); return false;" '.$opacity_styling.'>'.$icon_img.$label.'</a>';
                echo '<div class="clearfix"></div>';

                if($daysheet_styling == 'card') {
                    echo '</div>';
                } else {
                    echo '</li>';
                }
            }
        }
        if($daysheet_styling != 'card') {
            echo '</ul>';
        }
    } else if($no_tickets) {
        echo '<ul id="tickets_daily">';
        echo 'No records found.';
        echo '</ul>';
    } ?>
    </ul>
    <hr>
<?php } ?>

<?php if (in_array('Tasks', $daysheet_fields_config)) { ?>
    <h4 style="font-weight: normal;">Tasks</h4>
    <?php if (!empty($tasks_result)) {
        if($daysheet_styling != 'card') {
            echo '<ul id="tasks_daily">';
        }
        foreach ($tasks_result as $task) {
            if($daysheet_styling == 'card') {
                echo '<div class="block-group-daysheet">';
            }
			$label = ($task['businessid'] > 0 ? get_contact($dbc, $task['businessid'], 'name').', ' : '').($task['projectid'] > 0 ? PROJECT_NOUN.' #'.$task['projectid'].' '.get_project($dbc,$task['projectid'],'project_name') : '');
            echo '<a href="../Tasks/add_task.php?tasklistid='.$task['tasklistid'].'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" ><span style="color: black;">'.($label != '' ? $label.'<br />' : '').$task['task_milestone_timeline'].' - '.get_contact($dbc, $task['businessid'], 'name').' - '.$task['heading'].'</span></a>';
            if($daysheet_styling == 'card') {
                echo '</div>';
            }
        }
        if($daysheet_styling != 'card') {
            echo '</ul>';
        }
    } else {
        echo '<ul id="tasks_daily">';
        echo 'No records found.';
        echo '</ul>';
    } ?>
    <hr>
<?php } ?>

<?php if (in_array('Checklists', $daysheet_fields_config)) { ?>
    <?php include('daysheet_checklist_functions.php'); ?>
    <h4 style="font-weight: normal;">Checklists</h4>
    <?php
    if (!empty($checklists_result)) {
        if($daysheet_styling != 'card') {
            echo '<ul id="checklists_daily">';
        }
        foreach ($checklists_result as $checklist_action) {
            if($daysheet_styling == 'card') {
                echo '<div class="block-group-daysheet">';
            } else {
                echo '<li>';
            }

            $checklist_name = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` AS `cn` LEFT JOIN `checklist` AS `c`ON `c`.`checklistid` = `cn`.`checklistid` WHERE `checklistnameid` = '".$checklist_action['checklistnameid']."'"));
			$label = ($checklist_name['businessid'] > 0 ? get_contact($dbc, $checklist_name['businessid'], 'name').', ' : '').($checklist_name['projectid'] > 0 ? PROJECT_NOUN.' #'.$checklist_name['projectid'].' '.get_project($dbc,$checklist_name['projectid'],'project_name') : '');
            echo '<a href="../Checklist/checklist.php?view='.$checklist_name['checklistid'].'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" style="color: black;'.($checklist_action['done'] == 1 ? 'text-decoration: line-through;' : '').'">'.($label != '' ? $label.'<br />' : '').'Checklist: '.$checklist_name['checklist_name'].' - Item: '.explode('&lt;p&gt;', $checklist_name['checklist'])[0].'</a>';
            if($daysheet_styling == 'card') {
                echo '</div>';
            } else {
                echo '</li>';
            }
        }
        if($daysheet_styling != 'card') {
            echo '</ul>';
        }
    } else {
        echo '<ul id="checklists_daily">';
        echo 'No records found.';
        echo '</ul>';
    } ?>
    <hr>
<?php } ?>