<?php
include ('../database_connection.php');

$date = !empty($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$time = time();
if($_GET['fill'] == 'starttimer') {
	if(!empty($_GET['programid'])) {
		$query = "UPDATE `day_program` SET `date`='$date', `timer_started`='$time' WHERE `dayprogramid`='{$_GET['programid']}'";
		$result = mysqli_query($dbc, $query);
	}
	echo $date;
}
else if($_GET['fill'] == 'pausetimer') {
	if(!empty($_GET['programid'])) {
		$query = "UPDATE `day_program` SET `date`='$date', `timer`='{$_GET['timer_value']}', `timer_started`=0 WHERE `dayprogramid`='{$_GET['programid']}'";
		$result = mysqli_query($dbc, $query);
	}
	echo $date;
}
else if($_GET['fill'] == 'endtimer') {
	if(!empty($_GET['programid'])) {
		$query = "UPDATE `day_program` SET `date`='$date', `timer`='#{$_GET['timer_value']}#' WHERE `dayprogramid`='{$_GET['programid']}'";
		$result = mysqli_query($dbc, $query);
	}
}

/*if($_GET['fill'] == 'contact_category') {
    $category = $_GET['category'];
	$query = mysqli_query($dbc,"SELECT contactid, name, first_name, last_name FROM contacts WHERE category = '$category'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if(decryptIt($row['name']) != '') {
		    echo "<option value='".$row['contactid']."'>".decryptIt($row['name']).'</option>';
        } else {
		    echo "<option value='".$row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
	}
}*/

?>