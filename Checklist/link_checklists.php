<?php if($links_for == 'project') {
	$subtab_list = mysqli_query($dbc, "SELECT `checklist`.*, `checklist_subtab`.`name`, IFNULL(`project`.`project_name`,`client_project`.`project_name`) `final_project_name`, IFNULL(`project`.`projectid`,`client_project`.`projectid`) `final_projectid` FROM `checklist` LEFT JOIN `checklist_subtab` ON `checklist`.`subtabid`=`checklist_subtab`.`subtabid` LEFT JOIN `project` ON `checklist`.`projectid`=`project`.`projectid` LEFT JOIN `client_project` ON `checklist`.`client_projectid`=`client_project`.`projectid` WHERE (`assign_staff` LIKE '%,{$_SESSION['contactid']},%' OR `assign_staff`=',ALL,') AND `checklist`.`deleted`=0 ORDER BY `final_project_name`");
} else if($links_for == 'equipment') {
	$subtab_list = mysqli_query($dbc, "SELECT CONCAT('Unit #',`unit_number`,': ',`checklist_name`) `checklist_name`, `checklistid`, 'equipment' `checklist_type` FROM `item_checklist` LEFT JOIN `equipment` ON `item_checklist`.`item_id`=`equipment`.`equipmentid` WHERE `item_checklist`.`deleted`=0 AND `item_checklist`.`checklist_item`='equipment'");
} else if($links_for == 'inventory') {
	$subtab_list = mysqli_query($dbc, "SELECT CONCAT(`part_no`,': ',`name`,': ',`checklist_name`) `checklist_name`, `checklistid`, 'inventory' `checklist_type` FROM `item_checklist` LEFT JOIN `inventory` ON `item_checklist`.`item_id`=`inventory`.`inventoryid` WHERE `item_checklist`.`deleted`=0 AND `item_checklist`.`checklist_item`='inventory'");
} else {
	$subtab_list = mysqli_query($dbc, "SELECT `checklist`.*, `checklist_subtab`.`name` FROM `checklist` LEFT JOIN `checklist_subtab` ON `checklist`.`subtabid`=`checklist_subtab`.`subtabid` AND `checklist_subtab`.`deleted`=0 WHERE (`assign_staff` LIKE '%,{$_SESSION['contactid']},%' OR `assign_staff`=',ALL,') AND `checklist`.`deleted`=0");
}
while($checklist = mysqli_fetch_array($subtab_list)) {
	if(($links_for == 'favourites' && in_array($checklist['checklistid'],explode(',',$user_settings['checklist_fav']))) || 
		($links_for == 'private' && $checklist['assign_staff'] == ",{$_SESSION['contactid']},") ||
		($links_for == 'shared' && $checklist['assign_staff'] != ",{$_SESSION['contactid']},") ||
		($links_for == 'project' && ($checklist['client_projectid'] > 0 || $checklist['projectid'] > 0)) ||
		($links_for == 'company' && $checklist['assign_staff'] == ",ALL,") ||
		($links_for == 'ongoing' && $checklist['checklist_type'] == "ongoing") ||
		($links_for == 'daily' && $checklist['checklist_type'] == "daily") ||
		($links_for == 'weekly' && $checklist['checklist_type'] == "weekly") ||
		($links_for == 'equipment' && $checklist['checklist_type'] == "equipment") ||
		($links_for == 'inventory' && $checklist['checklist_type'] == "inventory") ||
		($links_for == 'monthly' && $checklist['checklist_type'] == "monthly") ||
		($links_for == $checklist['subtabid'])) {
		echo '<div class="show-on-mob" style="padding: 0.5em 2em; width: 100%;"><a href="checklist.php?'.(in_array($checklist['checklist_type'],['equipment','inventory']) ? 'item_view' : 'view').'='.$checklist['checklistid'].'">'.($links_for == 'project' ? 'Project #'.$checklist['final_projectid'].': '.$checklist['final_project_name'].': ' : '').$checklist['checklist_name'].'</a></div>';
	}
}