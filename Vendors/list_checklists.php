<?php if(empty($_GET['subtabid'])) {
	$_GET['subtabid'] = 'private';
}
if($_GET['subtabid'] > 0) {
	$subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_subtab` WHERE `subtabid` = '{$_GET['subtabid']}'"));
} else {
	$subtab = ['name'=>ucwords($_GET['subtabid'])];
}
switch($_GET['subtabid']) {
	default:
		echo "<h2 style='padding-left:1.5em;'>".get_contact($dbc, $_GET['contactid'])."'s Checklists".($_GET['subtabid'] > 0 ? "<span class='pull-right'><a href='?edittab=".$_GET['subtabid']."'><img src='".WEBSITE_URL."/img/icons/ROOK-edit-icon.png' style='height:1em;'></a></span>" : "")."</h2>";
}
$subtab_list = mysqli_query($dbc, "SELECT `checklist`.*, `checklist_subtab`.`name` FROM `checklist` LEFT JOIN `checklist_subtab` ON `checklist`.`subtabid`=`checklist_subtab`.`subtabid` AND `checklist_subtab`.`deleted`=0 WHERE `assign_staff` LIKE '%,{$_GET['contactid']},%' AND `checklist`.`deleted`=0");
echo "<ul class='option-list'>";
while($checklist = mysqli_fetch_array($subtab_list)) {
	$users = '';
	foreach(explode(',',$checklist['assign_staff']) as $id) {
		if($id > 0) {
			$users .= get_contact($dbc, $id).' ';
		}
	}
	$visibility = "hidden' style='display:none;";
	switch($_GET['subtabid']) {
	case 'favourites':
		if($checklist['assign_staff'] == ",{$_SESSION['contactid']}," && in_array($checklist['checklistid'],explode(',',$user_settings['checklist_fav']))) {
			$visibility = "visible";
		}
		break;
	case 'private':
		if($checklist['assign_staff'] == ",{$_GET['contactid']},") {
			$visibility = "visible";
		}
		break;
	case 'shared':
		if($checklist['assign_staff'] != ",{$_SESSION['contactid']},") {
			$visibility = "visible";
		}
		break;
	case 'project':
		if($checklist['client_projectid'] > 0 || $checklist['projectid'] > 0) {
			$visibility = "visible";
		}
		break;
	case 'company':
		if($checklist['assign_staff'] == "ALL") {
			$visibility = "visible";
		}
		break;
	case 'ongoing':
		if($checklist['checklist_type'] == "ongoing") {
			$visibility = "visible";
		}
		break;
	case 'daily':
		if($checklist['checklist_type'] == "daily") {
			$visibility = "visible";
		}
		break;
	case 'weekly':
		if($checklist['checklist_type'] == "weekly") {
			$visibility = "visible";
		}
		break;
	case 'monthly':
		if($checklist['checklist_type'] == "monthly") {
			$visibility = "visible";
		}
		break;
	case $checklist['subtabid']:
		$visibility = "visible";
		break;
	}
	echo "<a href='".$_SERVER['REQUEST_URI']."&view=".$checklist['checklistid']."' class='col-sm-6' data-subtab='{$checklist['name']}' data-users='$users' data-name='{$checklist['checklist_name']}' data-visible='$visibility'><li>";
	profile_id($dbc, $checklist['created_by']);
	echo $checklist['checklist_name']."</li></a>";
}
echo "</ul>";