<?php include_once('../include.php');
checkAuthorised('intake');

if($projectid > 0) {
	$max_sort = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT MAX(`sort_order`) max_sort FROM `project_scope` WHERE `projectid`='$projectid'"))['max_sort'];
	$user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
	mysqli_query($dbc, "INSERT INTO `project_history` (`updated_by`, `description`, `projectid`) VALUES ('$user', 'Intake #$intakeid Services attached to ".PROJECT_NOUN."', '$projectid')");

	foreach($intake_services as $serviceid => $price) {
		$max_sort++;
		mysqli_query($dbc, "INSERT INTO `project_scope` (`projectid`,`intakeid`,`heading`,`src_table`,`src_id`,`qty`,`cost`,`price`,`retail`,`sort_order`) VALUES ('$projectid','$intakeid','Intake #$intakeid','services','$serviceid','1','$price','$price','$price','$max_sort')");
	}
}