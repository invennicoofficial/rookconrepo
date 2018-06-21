<?php include('../include.php');
error_reporting(0);
ob_clean();

$action = $_GET['fill'];

if($action == 'businessid') {
	$business = $_POST['business'];
	
	$sites = '<option></option><option '.($business == 'NEW' ? 'selected' : '').' value="NEW">New Site</option>';
	$site = $_POST['site'];
	$site_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `site_name` name FROM `contacts` WHERE `category`='Sites' AND `businessid`='$business' AND `deleted`=0 AND `status`=1"),MYSQLI_ASSOC));
	foreach($site_list as $id) {
		$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contactid`, `site_name`, `google_maps_address`, `lsd` FROM `contacts` WHERE `contactid`='$id'"));
		$sites .= "<option data-name='".$row['site_name']."' data-google='".$row['google_maps_address']."' data-location='".$row['lsd']."' ".($id == $site ? 'selected' : '')." value='$id'>".$row['site_name']."</option>";
	}
	
	$contacts = '<option></option><option '.($business == 'NEW' ? 'selected' : '').' value="NEW">New Contact</option>';
	$contact = $_POST['contact'];
	$contact_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category` NOT IN ('Sites','Business','Staff') AND `businessid`='$business' AND `deleted`=0 AND `status`=1"),MYSQLI_ASSOC));
	foreach($contact_list as $id) {
		$contacts .= "<option ".($id == $contact ? 'selected' : '')." value='$id'>".get_contact($dbc, $id)."</option>";
	}
	
	echo $sites.'#*#'.$contacts;
}

else if($action == 'siteid') {
	$business = $_POST['business'];
	$site = $_POST['site'];
	$contacts = '<option></option><option '.($site == 'NEW' ? 'selected' : '').' value="NEW">New Contact</option>';
	$contact = $_POST['contact'];
	$contact_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` NOT IN ('Sites','Business','Staff') AND ((`businessid`='$business' AND `siteid`='0') OR `siteid`='$site' OR `businessid` IN (SELECT `businessid` FROM `contacts` WHERE `contactid`='$site')) AND `deleted`=0 AND `status`=1"),MYSQLI_ASSOC));
	foreach($contact_list as $id) {
		$contacts .= "<option ".($id == $contact ? 'selected' : '')." value='$id'>".get_contact($dbc, $id)."</option>";
	}
	
	echo $contacts;
}
else if($action == 'service_rates') {
	$heading = $_POST['service'];
	$category = $_POST['category'];
	$date = empty($_POST['date']) ? date('Y-m-d') : $_POST['date'];
	
	$result = mysqli_query($dbc, "SELECT `service_rate` FROM `service_rate_card` LEFT JOIN `services` ON `service_rate_card`.`serviceid`=`services`.`serviceid` WHERE `category`='$category' AND `heading`='$heading' AND `service_rate_card`.`deleted`=0 AND `services`.`deleted`=0 AND '$date' >= `service_rate_card`.`start_date` AND ('$date' <= `service_rate_card`.`end_date` OR `service_rate_card`.`end_date` IS NULL OR `service_rate_card`.`end_date` = '0000-00-00')");
	while($rate = mysqli_fetch_array($result)) {
		echo "<option value='".$rate['service_rate']."'>$".number_format($rate['service_rate'],2)."</option>\n";
	}
	echo "<option></option>\n";
}

if($_GET['fill'] == 'gochecklist') {
	$workorderid = $_GET['workorderid'];
	$checklist = filter_var($_GET['checklist'],FILTER_SANITIZE_STRING);
    $query_insert_ca = "INSERT INTO `site_work_checklist` (`workorderid`, `checklist`) VALUES ('$workorderid', '$checklist')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
	echo mysqli_insert_id($dbc);
}

if($_GET['fill'] == 'checked') {
	$checklistid = $_GET['id'];
	$query_update_project = "UPDATE `site_work_checklist` SET  checked='".$_GET['checked']."' WHERE `checklistid` = '$checklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}
else if($_GET['fill'] == 'add_checklist') {
	$query = mysqli_query($dbc, "INSERT INTO `site_work_checklist` (`workorderid`, `checklist`, `sort`)
		SELECT '".$_POST['workorderid']."', '".filter_var($_POST['new_item'],FILTER_SANITIZE_STRING)."', IFNULL(MAX(sort),0) + 1 FROM `site_work_checklist` WHERE `workorderid`='".$_POST['workorderid']."'");
	echo mysqli_insert_id($dbc);
}
else if($_GET['fill'] == 'delete_checklist') {
	$query = mysqli_query($dbc, "UPDATE `site_work_checklist` SET `deleted`=1 WHERE `checklistid`='".$_GET['id']."'");
}
else if($_GET['fill'] == 'checklist_flag') {
	$item_id = $_POST['id'];
	$colour = mysqli_fetch_array(mysqli_query($dbc, "SELECT `flag_colour` FROM `site_work_checklist` WHERE `checklistid` = '$item_id'"))['flag_colour'];
	$colour_list = explode('#*#', get_config($dbc, 'general_flag_colours'));
	$colour_key = key(preg_grep("/^$colour*#*.*/",$colour_list));
	$new_colour = explode('*#*',($colour_key === NULL ? $colour_list[0] : ($colour_key + 1 < count($colour_list) ? $colour_list[$colour_key + 1] : '')))[0];
	$result = mysqli_query($dbc, "UPDATE `site_work_checklist` SET `flag_colour`='$new_colour' WHERE `checklistid` = '$item_id'");
	echo $new_colour;
}
else if($_GET['fill'] == 'checklist_reply') {
	$checklistid = $_POST['id'];
	$reply = filter_var(htmlentities('<p>'.$_POST['reply'].'</p>'),FILTER_SANITIZE_STRING);
	$query = "UPDATE `site_work_checklist` SET  `checklist`=CONCAT(`checklist`,'$reply') WHERE `checklistid` = '$checklistid'";
	$result = mysqli_query($dbc, $query);
}
else if($_GET['fill'] == 'checklist_priority') {
	$workorderid = $_GET['workorderid'];
    $id = $_GET['id'];
    $prior = $_GET['afterid'];
    $prior_sort = mysqli_fetch_array(mysqli_query($dbc, "SELECT `sort`+1 FROM `site_work_checklist` WHERE `checklistid`='$prior'"))[0];
	$result = mysqli_query($dbc, "UPDATE `site_work_checklist` SET  `sort`=`sort`+1 WHERE `sort` >= '$prior_sort' AND `workorderid` = '$workorderid'");
	$result = mysqli_query($dbc, "UPDATE `site_work_checklist` SET  `sort`='$prior_sort' WHERE `checklistid` = '$id'");
}
else if($_GET['fill'] == 'checklist_upload') {
	$id = $_GET['id'];
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
	$basename = $_FILES['file']['name'];
	if($basename != '') {
		$basename = $filename = preg_replace('/[^A-Za-z0-9\.]/','_',$basename);
		$i = 0;
		while(file_exists('download/'.$filename)) {
			$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '('.++$i.')$1', $basename);
		}
		move_uploaded_file($_FILES['file']['tmp_name'], 'download/'.$filename);
		mysqli_query($dbc, "INSERT INTO `site_work_checklist_uploads` (`checklistid`, `type`, `link`, `created_date`, `created_by`) VALUES ('$id', 'Support Document', '$filename', '".date('Y/m/d')."', '".$_SESSION['contactid']."')");
		echo $filename;
	}
}
else if($_GET['fill'] == 'time_tracking') {
	$id = $_GET['id'];
	$summary = mysqli_fetch_array(mysqli_query($dbc, "SELECT `summary`, `summary_timer_start`, `id_label`, `siteid`, `staff_lead` FROM `site_work_orders` WHERE `workorderid`='$id'"));
	if($summary['summary_timer_start'] > 0) {
		mysqli_query($dbc, "UPDATE `site_work_orders` SET `summary_timer_start`='0' WHERE `workorderid`='$id'");
		$duration = time() - $summary['summary_timer_start'];
		$max_time = get_config($dbc, 'max_timer');
		if($duration > $max_time && !empty($max_time) && $max_time > 0) {
			$duration = $max_time;
		}
		$duration = round($duration / 3600, 3);
		$times = [];
		mysqli_query($dbc, "INSERT INTO `time_cards` (`staff`, `business`, `date`, `type_of_time`, `total_hrs`, `comment_box`) VALUES ('{$summary['staff_lead']}', '{$summary['siteid']}', '".date('Y-m-d')."', 'Regular Hrs.', '$duration', 'Site Work Order {$summary['id_label']}: Company Team Lead')");
		foreach(explode('#*#',$summary['summary']) as $line) {
			$line = explode('**#**',$line);
			if($line[4] > 0) {
				$times[] = $line[2];
			} else {
				$timer_diff = round(($line[3] - $summary['summary_timer_start']) / 3600, 3);
				$time_dur = $line[2] + ($timer_diff > 0 && $timer_diff < $duration ? $duration - $timer_diff : $duration);
				$times[] = $time_dur;
				mysqli_query($dbc, "INSERT INTO `time_cards` (`staff`, `business`, `date`, `type_of_time`, `total_hrs`, `comment_box`) VALUES ('{$line[0]}', '{$summary['siteid']}', '".date('Y-m-d')."', 'Regular Hrs.', '$time_dur', 'Site Work Order {$summary['id_label']}: {$line[1]}')");
			}
		}
		echo implode('#*#',$times);
	} else {
		mysqli_query($dbc, "UPDATE `site_work_orders` SET `summary_timer_start`='".time()."' WHERE `workorderid`='$id'");
		$times = [];
		foreach(explode('#*#',$summary['summary']) as $line) {
			$line = explode('**#**',$line);
			$crew = $line[0];
			if(empty($line[4])) {
				$other_work_orders = mysqli_query($dbc, "SELECT `workorderid`, `siteid`, `id_label`, `summary_timer_start`, `summary` FROM `site_work_orders` WHERE (`summary` LIKE '%#*#".$crew."**#**%' OR `summary` LIKE '".$crew."**#**%') AND `workorderid` != '$id'");
				while($crew_wo = mysqli_fetch_array($other_work_orders)) {
					$wo_summary = explode('#*#',$crew_wo['summary']);
					foreach($wo_summary as $wo_row => $wo_line) {
						$wo_line = explode('**#**', $wo_line);
						if($wo_line[0] == $crew && empty($wo_line[4])) {
							$start_time = ($wo_line[3] > $crew_wo['summary_time_start'] ? $wo_line[3] : $crew_wo['summary_time_start']);
							$duration = round((time() - $start_time) / 3600, 3);
							$max_time = get_config($dbc, 'max_timer');
							if($duration > $max_time && !empty($max_time) && $max_time > 0) {
								$duration = $max_time;
							}
							mysqli_query($dbc, "INSERT INTO `time_cards` (`staff`, `business`, `date`, `type_of_time`, `total_hrs`, `comment_box`) VALUES ('{$wo_line[0]}', '{$crew_wo['siteid']}', '".date('Y-m-d')."', 'Regular Hrs.', '$duration', 'Site Work Order {$crew_wo['id_label']}: {$wo_line[1]}')");
							$wo_line[2] += $duration;
							$wo_line[3] = 0;
							$wo_line[4] = 1;
							$wo_summary[$wo_row] = implode('**#**',$wo_line);
						}
					}
					$wo_summary = implode('#*#',$wo_summary);
					mysqli_query($dbc, "UPDATE `site_work_orders` SET `summary`='$wo_summary' WHERE `workorderid`='{$crew_wo['workorderid']}'");
				}
			}
			$times[] = $line[2];
		}
		echo implode('#*#',$times);
	}
}
if($_GET['fill'] == 'approve_site_summary') {
	$workorderid = $_POST['workorderid'];
	mysqli_query($dbc, "UPDATE `site_work_orders` SET `site_summary_status` = 'Approved' WHERE `workorderid` = '$workorderid'");

	echo $workorderid;
}