<?php
include ('../database_connection.php');
include ('../function.php');
include ('../global.php');
error_reporting(0);


if($_GET['fill'] == 'cross_software_approval') {
	$id = $_GET['status'];
	$dbc_conn = $_GET['dbc'];
	$dbc_cross = ${'dbc_cross_'.$dbc_conn}; 
	if(isset($_GET['disapprove'])) {
		$message = $_GET['name'];
		mysqli_query($dbc_cross,"UPDATE `newsboard` SET cross_software_approval = 'disapproved' WHERE newsboardid='$id'") or die(mysqli_error($dbc_cross)); 
	} else {
		mysqli_query($dbc_cross,"UPDATE `newsboard` SET cross_software_approval = '1' WHERE newsboardid='$id'") or die(mysqli_error($dbc_cross)); 
	}
}

?>