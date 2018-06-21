<?php if(!isset($day_start)) {
	$day_start = "6:00 am";
}
if(!isset($day_end)) {
	$day_end = "8:00 pm";
}
if(!isset($day_period)) {
	$day_period = 15;
}
if(!isset($contact_id)) {
	$contact_id = $_SESSION['contactid'];
}
if(!isset($calendar_date)) {
	$calendar_date = date('Y-m-d');
}
if(!isset($column_id)) {
	$column_id = 0;
}
if(!isset($day_of_week)) {
	$day_of_week = date('l');
}

if(isset($_GET['shiftid'])) {
	// Shift Blocks

	//Populate the text for the column header
	$calendar_table[$calendar_date][$contact_id]['title'] = get_contact($dbc, $contact_id);
	$calendar_table[$calendar_date][$contact_id]['notes'] = '';

	//Pull all shifts for the current contact from the contacts_shifts table
	$all_contacts_shifts = "SELECT * FROM `contacts_shifts` WHERE `contactid` = '".$contact_id."' AND `startdate` <= '".date('Y-m-d', strtotime($calendar_date))."' AND (`enddate` >= '".date('Y-m-d', strtotime($calendar_date))."' OR `enddate`='0000-00-00') AND CONCAT(',', `repeat_days`, ',') LIKE '%,".$day_of_week.",%' AND `deleted` = 0 AND (`dayoff_type` = '' OR `dayoff_type` IS NULL)";
	$shifts = mysqli_fetch_all(mysqli_query($dbc, $all_contacts_shifts),MYSQLI_ASSOC);

	$all_contacts_daysoff = "SELECT * FROM `contacts_shifts` WHERE `contactid` = '".$contact_id."' AND `startdate` <= '".date('Y-m-d', strtotime($calendar_date))."' AND (`enddate` >= '".date('Y-m-d', strtotime($calendar_date))."' OR `enddate`='0000-00-00') AND `deleted` = 0 AND `dayoff_type` != '' AND `dayoff_type` IS NOT NULL AND (CONCAT(',', `repeat_days`, ',') LIKE '%,".$day_of_week.",%' OR `repeat_days` = '' OR `repeat_days` IS NULL)";
	$daysoff = mysqli_fetch_all(mysqli_query($dbc, $all_contacts_daysoff),MYSQLI_ASSOC);

	//Loop through each time on the calendar and populate it
	$current_row = date('H:i:s', strtotime($day_start));
	while(strtotime($current_row) <= strtotime($day_end)) {
		$current_day = '';
		$current_shift = '';
		$current_dayoff = '';
		foreach ($shifts as $shift) {
			if($current_shift == '' && $current_row <= date('H:i:s', strtotime($shift['starttime'])) && date('H:i:s', strtotime('+'.$day_period.' minutes', strtotime($current_row))) > date('H:i:s', strtotime($shift['starttime']))) {
				$current_shift = $shift;
			}
		}
		foreach ($daysoff as $dayoff) {
			if($current_dayoff == '' && $current_row <= date('H:i:s', strtotime($dayoff['starttime'])) && date('H:i:s', strtotime('+'.$day_period.' minutes', strtotime($current_row))) > date('H:i:s', strtotime($dayoff['starttime']))) {
				$current_dayoff = $dayoff;
			}
		}
		if (!empty($current_shift) || !empty($current_dayoff)) {
			$current_day = ['shift', $current_shift, $current_dayoff];
		}

		$calendar_table[$calendar_date][$contact_id][] = $current_day;
		$current_row = date('H:i:s', strtotime('+'.$day_period.' minutes', strtotime($current_row)));
		if(date('Y-m-d H:i:s', strtotime('+'.$day_period.' minutes', strtotime($current_row))) >= date('Y-m-d H:i:s', strtotime($day_end))) {
			break;
		}
	}


} else {
	// Contact Blocks - Estimates
	
	//Populate the text for the column header
	$calendar_table[$calendar_date][$contact_id]['title'] = get_contact($dbc, $contact_id);
	$calendar_table[$calendar_date][$contact_id]['notes'] = '';
    
    //pull all next actions from estimate_actions table
    $query_next_actions = "SELECT `ea`.* FROM `estimate_actions` AS `ea` JOIN `estimate` AS `e` ON (`ea`.`estimateid`=`e`.`estimateid`) WHERE FIND_IN_SET ('$contact_id', `e`.`assign_staffid`) AND `e`.`deleted`=0 AND FIND_IN_SET('$contact_id', `ea`.`contactid`) AND `ea`.`deleted`=0 AND `ea`.`due_date`='". date('Y-m-d', strtotime($calendar_date)) ."'";
    $next_actions = mysqli_fetch_all(mysqli_query($dbc, $query_next_actions),MYSQLI_ASSOC);

	//Loop through each time on the calendar and populate it
	//$day_start2 = "10:00:00";
    $current_row = date('H:i:s', strtotime($day_start));
	while(strtotime($current_row) <= strtotime($day_end)) {
		$current_estimate = '';
		//if (strtotime($current_row) >= strtotime($day_start2) ) {
            foreach($next_actions as $next_action) {
                if ( $current_estimate == '' && $calendar_date == date('Y-m-d', strtotime($next_action['due_date'])) ) {
                    $current_estimate = ['estimate', $next_action];
                    array_shift($next_actions);
					$calendar_table[$calendar_date][$contact_id]['total_estimates']++;
                }
            }
        //}
		$calendar_table[$calendar_date][$contact_id][] = $current_estimate;
		$current_row = date('H:i:s', strtotime('+'.$day_period.' minutes', strtotime($current_row)));
		if(date('Y-m-d H:i:s', strtotime('+'.$day_period.' minutes', strtotime($current_row))) >= date('Y-m-d H:i:s', strtotime($day_end))) {
			break;
		}
	}

	//Estimates that were not displayed
	$est_not_scheduled = [];
	foreach ($next_actions as $next_action) {
		$est_not_scheduled[] = $next_action['estimateid'];
		$calendar_table[$calendar_date][$contact_id]['total_estimates']++;
	}
	$est_notes[$contact_id] = $est_not_scheduled;

	//Add Notes
	if(!empty($est_notes[$contact_id])) {
		$est_urls = '';
		foreach($est_notes[$contact_id] as $estimateid) {
			$est_urls .= '<a href="'.WEBSITE_URL.'/Estimate/estimates.php?view='.$estimateid.'" onclick="overlayIFrameSlider('.WEBSITE_URL.'/Estimate/estimates.php?view='.$estimateid.'); return false;" style="color: black;">#'.$estimateid.'</a>, ';
		}
		$est_urls = rtrim($est_urls, ', ');
		$calendar_table[$calendar_date][$contact_id]['warnings'] .= "The following Estimates are either out of the Calendar time-frame, has a time conflict, or there are too many Estimates: ".$est_urls."<br>";
	}
}

//Add notes
$contact_notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `calendar_notes` WHERE `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0"))['note'];
$calendar_table[$calendar_date][$contact_id]['notes'] = html_entity_decode($contact_notes);

//Add reminders
$reminderids = [];
//Reminders
$reminders_query = "SELECT * FROM `reminders` WHERE `reminder_date` = '$calendar_date' AND `contactid` = '$contact_id' AND `deleted` = 0";
$reminders_result = mysqli_fetch_all(mysqli_query($dbc, $reminders_query),MYSQLI_ASSOC);
foreach ($reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['reminderid']."', '".$contact_id."', 'reminder', '".$calendar_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['reminderid']."' AND `type` = 'reminder' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['reminderid']."' AND `type` = 'reminder' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$sales_reminders_query = "SELECT * FROM `sales` WHERE `new_reminder` = '$calendar_date' AND (`primary_staff` = '$contact_id' OR CONCAT(',',`share_lead`,',') LIKE '%,$contact_id,%')";
$sales_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $sales_reminders_query),MYSQLI_ASSOC);
foreach ($sales_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['salesid']."', '".$contact_id."', 'sales', '".$calendar_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['salesid']."' AND `type` = 'sales' AND `date` = '".$calendar_date."' AND `contactid` ='".$contact_id."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['salesid']."' AND `type` = 'sales' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$estimates_reminders_query = "SELECT `ea`.*, `e`.`estimate_name` FROM `estimate_actions` AS `ea` JOIN `estimate` AS `e` ON (`ea`.`estimateid`=`e`.`estimateid`) WHERE FIND_IN_SET ('$contact_id', `e`.`assign_staffid`) AND `e`.`deleted`=0 AND FIND_IN_SET('$contact_id', `ea`.`contactid`) AND `ea`.`deleted`=0 AND `ea`.`due_date`='". date('Y-m-d', strtotime($calendar_date)) ."'";
$estimates_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $estimates_reminders_query),MYSQLI_ASSOC);
foreach ($estimates_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['id']."', '".$contact_id."', 'estimate', '".$calendar_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'estimate' AND `date` = '".$calendar_date."' AND `contactid` ='".$contact_id."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'estimate' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$projects_reminders_query = "SELECT `pa`.*, `p`.`project_name` FROM `project_actions` AS `pa` JOIN `project` AS `p` ON (`pa`.`projectid`=`p`.`projectid`) WHERE FIND_IN_SET ('$contact_id', `pa`.`contactid`) AND `p`.`deleted` = 0 AND `pa`.`deleted` = 0 AND `pa`.`due_date` = '".date('Y-m-d', strtotime($calendar_date))."'";
$projects_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $projects_reminders_query),MYSQLI_ASSOC);
foreach ($projects_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['id']."', '".$contact_id."', 'project', '".$calendar_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'project' AND `date` = '".$calendar_date."' AND `contactid` ='".$contact_id."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['id']."' AND `type` = 'project' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}
$pfu_reminders_query = "SELECT * FROM `project` WHERE `followup` = '".$calendar_date."' AND `project_lead` = '".$contact_id."'";
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
        mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['projectid']."', '".$contact_id."', 'project_followup', '".$calendar_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['projectid']."' AND `type` = 'project_followup' AND `date` = '".$calendar_date."' AND `contactid` ='".$contact_id."' AND `deleted` = 0) num WHERE num.rows = 0");
        $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['projectid']."' AND `type` = 'project_followup' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0"))['daysheetreminderid'];
        $reminderids[] = $reminderid;
    }
}
$alerts_reminders_query = "SELECT * FROM `alerts` WHERE `alert_date` = '$calendar_date' AND `alert_user` = '$contact_id'";
$alerts_reminders_result = mysqli_fetch_all(mysqli_query($dbc, $alerts_reminders_query),MYSQLI_ASSOC);
foreach ($alerts_reminders_result as $reminder) {
    mysqli_query($dbc, "INSERT INTO `daysheet_reminders` (`reminderid`, `contactid`, `type`, `date`, `done`) SELECT '".$reminder['alertid']."', '".$contact_id."', 'alert', '".$calendar_date."', '0' FROM (SELECT COUNT(*) rows FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['alertid']."' AND `type` = 'alert' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0) num WHERE num.rows = 0");
    $reminderid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `daysheetreminderid` FROM `daysheet_reminders` WHERE `reminderid` = '".$reminder['alertid']."' AND `type` = 'alert' AND `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0"))['daysheetreminderid'];
    $reminderids[] = $reminderid;
}

//If reminders not found, mark it as deleted
$reminderids = "'".implode("','",$reminderids)."'";
mysqli_query($dbc, "UPDATE `daysheet_reminders` SET `deleted` = 1 WHERE `daysheetreminderid` NOT IN (".$reminderids.") AND `date` = '".$calendar_date."' AND `date` >= '".date('Y-m-d')."' AND `contactid` = '".$contact_id."' AND `done` = 0 AND `deleted` = 0");

//Display Reminders
$reminders_list = mysqli_query($dbc, "SELECT * FROM `daysheet_reminders` WHERE `date` = '".$calendar_date."' AND `contactid` = '".$contact_id."' AND `deleted` = 0 AND `done` = 0");
$num_rows = mysqli_num_rows($reminders_list);
if ($num_rows > 0) {
	$calendar_table[$calendar_date][$contact_id]['reminders'] ='';
	foreach ($reminders_list as $daysheet_reminder) {
	    if ($daysheet_reminder['type'] == 'reminder') {
	        $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `reminders` WHERE `reminderid` = '".$daysheet_reminder['reminderid']."'"));
	        $calendar_table[$calendar_date][$contact_id]['reminders'] .= $reminder['subject'].'<br>';
	    } else if ($daysheet_reminder['type'] == 'sales') {
	        $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales` WHERE `salesid` = '".$daysheet_reminder['reminderid']."'"));
	        $calendar_table[$calendar_date][$contact_id]['reminders'] .= '<a href="../Sales/sale.php?p=preview&id='.$reminder['salesid'].'">Follow Up Sales: Sales #'.$reminder['salesid'].'</a><br>';
	    } else if ($daysheet_reminder['type'] == 'estimate') {
	        $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `ea`.*, `e`.`estimate_name` FROM `estimate_actions` AS `ea` JOIN `estimate` AS `e` ON (`ea`.`estimateid`=`e`.`estimateid`) WHERE `ea`.`id` = '".$daysheet_reminder['reminderid']."'"));
	        $calendar_table[$calendar_date][$contact_id]['reminders'] .= '<a href="../Estimate/estimates.php?view='.$reminder['estimateid'].'">Follow Up Estimate: '.$reminder['estimate_name'].'</a><br>';
	    } else if ($daysheet_reminder['type'] == 'project') {
	        $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `pa`.*, `p`.`project_name` FROM `project_actions` AS `pa` JOIN `project` AS `p` ON (`pa`.`projectid`=`p`.`projectid`) WHERE `pa`.`id` = '".$daysheet_reminder['reminderid']."'"));
	        $calendar_table[$calendar_date][$contact_id]['reminders'] .= '<a href="../Project/projects.php?edit='.$reminder['projectid'].'">Follow Up Project: '.$reminder['project_name'].'</a><br>';
	    } else if ($daysheet_reminder['type'] == 'project_followup') {
	        $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid` = '".$daysheet_reminder['reminderid']."'"));
	        $calendar_table[$calendar_date][$contact_id]['reminders'] .= '<a href="../Project/projects.php?edit='.$reminder['projectid'].'">Follow Up Project: '.$reminder['project_name'].'</a><br>';
	    } else if ($daysheet_reminder['type'] == 'alert') {
	        $reminder = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `alerts` WHERE `alertid` = '".$daysheet_reminder['reminderid']."'"));
	        $calendar_table[$calendar_date][$contact_id]['reminders'] .= '<a href="'.$reminder['alert_link'].'">Alert: '.$reminder['alert_text'].' - '.$reminder['alert_link'].'</a><br>';
	    }
	}
	$calendar_table[$calendar_date][$contact_id]['reminders'] = rtrim($calendar_table[$calendar_date][$contact_id]['reminders'], '<br>');
}