<?php require_once('../include.php');
error_reporting(0);
ob_clean();

if($_GET['data'] == 'CONTACTS') {
	$bid = $_GET['bid'];
	$cid = explode(',',$_GET['cid']);
	echo "<option></option>\n";
	echo "<option".(strpos($bid,"New Business") !== FALSE ? ' selected' : '')." value='New Contact'>New Contact</option>\n";
	$contact_db = mysqli_query($dbc, "SELECT contactid, businessid, name, first_name, last_name, category FROM contacts WHERE (',$bid,') LIKE CONCAT('%,',IFNULL(`businessid`,0),',%') AND `deleted`=0 AND `status`=1 ORDER BY businessid");
	$businessid = '';
	while($row = mysqli_fetch_array($contact_db)) {
		if($businessid != $row['businessid']) {
			$cat_list[$businessid] = sort_contacts_array($this_list);
			$businessid = $row['businessid'];
			$this_list = [];
		}
		$this_list[] = [ 'contactid' => $row['contactid'], 'name' => $row['name'], 'last_name' => $row['last_name'], 'first_name' => $row['first_name'] ];
	}
	$cat_list[$businessid] = sort_contacts_array($this_list);
	$this_list = [];
	foreach($cat_list as $businessid => $id_list) {
		echo '<optgroup label="'.get_client($dbc, $businessid).'">';
		foreach($id_list as $id) {
			$names = mysqli_fetch_array(mysqli_query($dbc, "SELECT `name`, `first_name`, `last_name` FROM `contacts` WHERE `contactid`='$id'"));
			echo "<option ".($businesscontactid == $id ? 'selected' : '')." value='".$id."'>".($names['name'] != '' ? decryptIt($names['name']).': ' : '').decryptIt($names['first_name'])." ".decryptIt($names['last_name']).'</option>';
		}
	}
}
else if($_GET['data'] == 'REQUEST') {
	$bid = $_GET['bid'];
	$cid = $_GET['cid'];
	echo "<option></option>\n";
	$contact_db = mysqli_query($dbc, "SELECT c.`contactid`, c.`first_name`, c.`last_name`, b.`name` FROM `contacts` c LEFT JOIN `contacts` b on c.`businessid`=b.`contactid` WHERE ((',$bid,') LIKE CONCAT('%,',c.`businessid`,',%') OR c.`category` IN (".STAFF_CATS.") AND IF((FIND_IN_SET(".implode(", c.`staff_category`) > 0 OR FIND_IN_SET(", explode(',',STAFF_CATS_HIDE)).", c.`staff_category`) > 0) AND ((IF(FIND_IN_SET(".implode(", c.`staff_category`) > 0,1,0) + IF(FIND_IN_SET(", explode(',',STAFF_CATS_HIDE)).", c.`staff_category`) > 0,1,0) - (CHAR_LENGTH(c.`staff_category`) - CHAR_LENGTH(REPLACE(c.`staff_category`, ',', '')) + 1)) = 0),1,0) = 0) AND c.`deleted`=0 AND c.`status`=1 ORDER BY b.`name`, c.`category`");
	$business = '';
	while($row = mysqli_fetch_assoc($contact_db)) {
		if($business != decryptIt($row['name'])) {
			$business = decryptIt($row['name']);
			echo '<optgroup label="'.decryptIt($row['name'])."\">\n";
		}
		echo "<option ".($row['contactid'] == $cid ? 'selected' : '')." value='".$row['contactid']."'>".decryptIt($row['first_name'])." ".decryptIt($row['last_name'])."</option>\n";
	}
}
else if($_GET['data'] == 'PROJECTS') {
	$bid = $_GET['bid'];
	$pid = explode(',',$_GET['pid']);
	echo "<option></option>\n";
	$contact_db = mysqli_query($dbc, "SELECT * FROM (SELECT projectid, project_name FROM project WHERE `deleted`=0 AND (`businessid`='$bid' OR '$bid' = '') UNION SELECT CONCAT('C',`projectid`), CONCAT('Client Project: ',`project_name`) FROM `client_project` WHERE `deleted`=0 AND (`clientid`='$bid' OR '$bid'='')) PROJECTS ORDER BY project_name");
	if(mysqli_num_rows($contact_db) > 0) {
		while($row = mysqli_fetch_assoc($contact_db)) {
			echo "<option ".(in_array($row['projectid'],$pid) ? 'selected' : '')." value='".$row['projectid']."'>".$row['project_name']."</option>\n";
		}
	} else {
		echo "<option>No projects exist for the selected business.</option>";
	}
}
else if($_GET['data'] == 'EMAIL') {
	$bid = $_GET['bid'];
	$eid = explode(',',$_GET['eid']);
	echo "<option></option>\n";
	$cat = '';
	$cat_list = [];
	$this_list = [];
	$query = mysqli_query($dbc,"SELECT contactid, first_name, last_name, category, email_address FROM contacts WHERE (businessid='$bid' OR '$bid'='') AND `deleted`=0 AND `status`=1 AND `category` NOT IN ('Business',".STAFF_CATS.",'Sites') ORDER BY category");
	while($row = mysqli_fetch_array($query)) {
		if($cat != $row['category']) {
			$cat_list[$cat] = sort_contacts_array($this_list);
			$cat = $row['category'];
			$this_list = [];
		}
		if($row['email_address'] != '') {
			$this_list[] = [ 'contactid' => $row['contactid'], 'last_name' => $row['last_name'], 'first_name' => $row['first_name'] ];
		}
	}
	$cat_list[$cat] = sort_contacts_array($this_list);
	foreach($cat_list as $cat => $id_list) {
		echo '<optgroup label="'.$cat.'">';
		foreach($id_list as $id) {
			$email = get_email($dbc, $id);
			$name = get_contact($dbc, $id);
			echo "<option data-id='".$id."' ".(in_array($email,$eid) ? 'selected' : '')." value='".$email."'>".$name.' : '.$email.'</option>';
		}
	}
}
else if($_GET['fill'] == 'startmeetingtimer') {
	$agendameetingid = $_GET['agendameetingid'];
	$start_time = time();
	$start_timer_time = date('g:i A');
	$created_date = date('Y-m-d H:i:s');
	$created_by = $_GET['login_contactid'];

	$query_insert_timer = "INSERT INTO `agenda_meeting_timer` (`agendameetingid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`) VALUES ('$agendameetingid', 'Meeting', '$start_timer_time', '$created_date', '$created_by', '$start_time')";
	$result_insert_timer = mysqli_query($dbc, $query_insert_timer);
}
else if($_GET['fill'] == 'pausemeetingtimer') {
	$agendameetingid = $_GET['agendameetingid'];
	$timer = $_GET['timer_value'];
	$start_time = time();
	$created_date = date('Y-m-d H:i:s');
	$created_by = $_GET['login_contactid'];
	$end_time = date('g:i A');

	if($timer != '0' && $timer != '00:00:00' && $timer != '') {
		$query_update_timer = "UPDATE `agenda_meeting_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `agendameetingid` = '$agendameetingid' AND `timer_type` = 'Meeting' AND `end_time` IS NULL";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);
		echo insert_day_overview($dbc, $created_by, 'Meeting', date('Y-m-d'), '', "Updated Meeting #$agendameetingid - Added Time : $timer");

        $query_insert_timer = "INSERT INTO `agenda_meeting_timer` (`agendameetingid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`) VALUES ('$agendameetingid', 'Break', '$end_time', '$created_date', '$created_by', '$start_time')";
        $query_insert_timer = mysqli_query($dbc, $query_insert_timer);
	}
}
else if($_GET['fill'] == 'stopmeetingtimer') {
	$agendameetingid = $_GET['agendameetingid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d H:i:s');
    $created_by = $_GET['login_contactid'];
    $end_time = date('g:i A');

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
		//Insert into Time Sheets
		$timers = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT MIN(`start_timer_time`) min_timer FROM `agenda_meeting_timer` WHERE `agendameetingid` = '$agendameetingid' AND `start_timer_time` > 0"));
		if($timers['min_timer'] > 0) {
			$agendameeting = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `agenda_meeting` WHERE `agendameetingid` = '$agendameetingid'"));
			$attendees = $agendameeting['businesscontactid'].','.$agendameeting['companycontactid'];
			$attendees = array_filter(array_unique(explode(',',$attendees)));
			$total_hrs = strtotime(date('Y-m-d H:i:s')) - $timers['min_timer'];
			$total_hrs = $total_hrs / 3600;
			foreach ($attendees as $attendee) {
				$query_insert_timer = "INSERT INTO `time_cards` (`agendameetingid`, `staff`, `date`, `type_of_time`, `total_hrs`, `timer_tracked`) VALUES ('$agendameetingid', '$attendee', '".date('Y-m-d')."', 'Regular Hrs.', '$total_hrs', '$total_hrs')";
				$result_insert_timer = mysqli_query($dbc, $query_insert_timer);
			}	
		}

		//Stop Timers
        $query_update_timer = "UPDATE `agenda_meeting_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `agendameetingid` = '$agendameetingid' AND end_time IS NULL";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);
        $query_update_timer = "UPDATE `agenda_meeting_timer` SET `start_timer_time`='0' WHERE `agendameetingid` = '$agendameetingid' AND `start_timer_time` > 0";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);
		echo insert_day_overview($dbc, $created_by, 'Meeting', date('Y-m-d'), '', "Updated Meeting #$agendameetingid - Added Time : $timer");
    }
}
else if($_GET['fill'] == 'resumemeetingtimer') {
	$agendameetingid = $_GET['agendameetingid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d H:i:s');
    $created_by = $_GET['login_contactid'];
    $end_time = date('g:i A');

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_timer = "UPDATE `agenda_meeting_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `agendameetingid` = '$agendameetingid' AND created_by='$created_by' AND timer_type='Break' AND end_time IS NULL";
        $result_update_timer = mysqli_query($dbc, $query_update_timer);

        $query_insert_timer = "INSERT INTO `agenda_meeting_timer` (`agendameetingid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`) VALUES ('$agendameetingid', 'Meeting', '$end_time', '$created_date', '$created_by', '$start_time')";
        $query_insert_timer = mysqli_query($dbc, $query_insert_timer);
    }
}