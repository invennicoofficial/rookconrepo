<?php
include ('../database_connection.php');
date_default_timezone_set('America/Denver');

if($_GET['fill'] == 'projectname') {
	$clientid = $_GET['clientid'];

	$query = mysqli_query($dbc,"SELECT projectid, project_name FROM project WHERE businessid = '$clientid'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['projectid']."'>".$row['project_name'].'</option>';
	}
}

if($_GET['fill'] == 'workorderervice') {
	$service_type = $_GET['service_type'];
	$service_type = str_replace("__","&",$service_type);

	$query = mysqli_query($dbc,"SELECT serviceid, category, heading FROM services WHERE REPLACE(`service_type`, ' ', '') = '$service_type'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['serviceid']."'>".$row['category'].' : '.$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'workorderheading') {
	$service_type = $_GET['service_type'];
	$service_type = str_replace("__","&",$service_type);

	$subservice = $_GET['service'];
	$subservice = str_replace("__","&",$subservice);

	$query = mysqli_query($dbc,"SELECT distinct(heading) FROM services WHERE REPLACE(`service_type`, ' ', '') = '$service_type' AND REPLACE(`category`, ' ', '') = '$subservice'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['heading']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'tasklistreassignstaff') {
	$tasklistid = $_GET['tasklistid'];
	$contactid = $_GET['contactid'];
	$query_update_project = "UPDATE `tasklist` SET  contactid='$contactid' WHERE `tasklistid` = '$tasklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'tasklistreassignstatus') {
	$tasklistid = $_GET['tasklistid'];
	$status = $_GET['status'];
	$query_update_project = "UPDATE `tasklist` SET  status='$status' WHERE `tasklistid` = '$tasklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'tasklistaddtime') {
	$tasklistid = $_GET['tasklistid'];
	$work_time = $_GET['worktime'];
	$query_update_project = "UPDATE `tasklist` SET  work_time='$work_time' WHERE `tasklistid` = '$tasklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'gochecklist') {
	$workorderid = $_GET['workorderid'];
	$checklist = filter_var($_GET['checklist'],FILTER_SANITIZE_STRING);
    echo $query_insert_ca = "INSERT INTO `workorder_checklist` (`workorderid`, `checklist`) VALUES ('$workorderid', '$checklist')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
}

if($_GET['fill'] == 'checklistdone') {
	$workorderchecklistid = $_GET['workorderchecklistid'];
	$query_update_project = "UPDATE `workorder_checklist` SET  checked=1 WHERE `workorderchecklistid` = '$workorderchecklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'startworkordertimer') {
	$workorderid = $_GET['workorderid'];
    $start_time = time();
	//$query_update_project = "UPDATE `workorder` SET start_time='$start_time' WHERE `workorderid` = '$workorderid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);

    $start_timer_time = date('g:i A');
	$created_date = date('Y-m-d');
    $created_by = $_GET['timer_contactid'];
    $timer_task = $_GET['timer_task'];

    $query_insert_client_doc = "INSERT INTO `workorder_timer` (`workorderid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`, `timer_task`) VALUES ('$workorderid', 'Work', '$start_timer_time', '$created_date', '$created_by', '$start_time', '$timer_task')";
    $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
}

if($_GET['fill'] == 'pauseworkordertimer') {
	$workorderid = $_GET['workorderid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    $created_by = $_GET['timer_contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `workorder_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `workorderid` = '$workorderid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Work' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);

        $query_insert_client_doc = "INSERT INTO `workorder_timer` (`workorderid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`, `timer_task`) VALUES ('$workorderid', 'Break', '$end_time', '$created_date', '$created_by', '$start_time', '$timer_task')";
        $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

        //$query_update_workorder = "UPDATE `workorder` SET `start_time` = '0' WHERE `workorderid` = '$workorderid'";
        //$result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }

	//$query_update_project = "UPDATE `workorder` SET start_time='$start_time' WHERE `workorderid` = '$workorderid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'endworkordertimer') {
	$workorderid = $_GET['workorderid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    $created_by = $_GET['timer_contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `workorder_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `workorderid` = '$workorderid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Break' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }
}

if($_GET['fill'] == 'enddayworkordertimer') {
	$workorderid = $_GET['workorderid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    $created_by = $_GET['timer_contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `workorder_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `workorderid` = '$workorderid' AND created_by='$created_by' AND created_date='$created_date' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }
}

if($_GET['fill'] == 'resumeworkordertimer') {
	$workorderid = $_GET['workorderid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    $created_by = $_GET['timer_contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `workorder_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `workorderid` = '$workorderid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Break' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);

        $query_insert_client_doc = "INSERT INTO `workorder_timer` (`workorderid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`, `timer_task`) VALUES ('$workorderid', 'Work', '$end_time', '$created_date', '$created_by', '$start_time', '$timer_task')";
        $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

        //$query_update_workorder = "UPDATE `workorder` SET `start_time` = '0' WHERE `workorderid` = '$workorderid'";
        //$result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }

	//$query_update_project = "UPDATE `workorder` SET start_time='$start_time' WHERE `workorderid` = '$workorderid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);
}
if($_GET['action'] == 'update_fields') {
	$table_name = filter_var($_POST['table'],FILTER_SANITIZE_STRING);
	$field_name = filter_var($_POST['field'],FILTER_SANITIZE_STRING);
	$value = filter_var(htmlentities($_POST['value']),FILTER_SANITIZE_STRING);
	$id = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
	$id_field = filter_var($_POST['id_field'],FILTER_SANITIZE_STRING);
	$workorderid = filter_var($_POST['workorderid'],FILTER_SANITIZE_STRING);
	$type = filter_var($_POST['type'],FILTER_SANITIZE_STRING);
	$type_field = filter_var($_POST['type_field'],FILTER_SANITIZE_STRING);
	$attach = filter_var($_POST['attach'],FILTER_SANITIZE_STRING);
	$attach_field = filter_var($_POST['attach_field'],FILTER_SANITIZE_STRING);
	
	if(!($id > 0)) {
		mysqli_query($dbc, "INSERT INTO `$table_name` (`workorderid`) VALUES ('$workorderid')");
		$id = mysqli_insert_id($dbc);
		mysqli_query($dbc, "UPDATE `$table_name` SET `created_by`='{$_SESSION['contactid']}', `created_date`=DATE(NOW()) WHERE `$id_field`='$id'");
		if($type != '') {
			if($type_field == '') {
				$type_field = 'type';
			}
			mysqli_query($dbc, "UPDATE `$table_name` SET `$type_field`='$type' WHERE `$id_field`='$id'");
		}
		if($attach != '') {
			if($attach_field == '') {
				$attach_field = 'attach';
			}
			mysqli_query($dbc, "UPDATE `$table_name` SET `$attach_field`='$attach' WHERE `$id_field`='$id'");
		}
		echo $id;
		if($table_name == 'workorder') {
	        insert_day_overview($dbc, $created_by, 'Work Order', date('Y-m-d'), '', 'Created Work Order #'.$id, $id);
		}
	}
	
	mysqli_query($dbc, "UPDATE `$table_name` SET `$field_name`='$value' WHERE `$id_field`='$id'");

	//Insert into day overview if last edit was wiithin 15 minutes
	$day_overview_last = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `timestamp` FROM `day_overview` WHERE `type` = 'Work Order' AND `tableid` = '$workorderid' AND `contactid` = '".$_SESSION['contactid']."' ORDER BY `timestamp` DESC"));
	$timestamp_now = date('Y-m-d h:i:s');
	$timediff = strtotime($timestamp_now) - strtotime($day_overview_last['timestamp']);
	if($timediff > 900 && !empty($workorderid)) {
        insert_day_overview($dbc, $created_by, 'Work Order', date('Y-m-d'), '', 'Updated Work Order #'.$workorderid, $workorderid);
	}
} else if($_GET['action'] == 'add_file') {
	$table_name = filter_var($_POST['table'],FILTER_SANITIZE_STRING);
	$field_name = filter_var($_POST['field'],FILTER_SANITIZE_STRING);
	$workorderid = filter_var($_POST['workorder'],FILTER_SANITIZE_STRING);
	foreach($_FILES['files']['name'] as $file => $basename) {
		$basename = filter_var($basename,FILTER_SANITIZE_STRING);
		$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '$1', preg_replace('/[^\.A-Za-z0-9]/','',$basename));
		$i = 0;
		while(file_exists('download/'.$filename)) {
			$filename = preg_replace('/(\.[A-Za-z0-9]*)/', ' ('.++$i.')$1', preg_replace('/[^\.A-Za-z0-9]/','',$basename));
		}
		move_uploaded_file($_FILES['files']['tmp_name'][$file],'download/'.$filename);
		mysqli_query($dbc, "INSERT INTO `$table_name` (`workorderid`,`document`,`label`,`created_by`,`created_date`) VALUES ('$workorderid','$filename','$basename','".$_SESSION['contactid']."',DATE(NOW()))");
	}
} else if($_GET['action'] == 'send_email') {
	$table_name = filter_var($_POST['table'],FILTER_SANITIZE_STRING);
	$field_src = filter_var($_POST['field'],FILTER_SANITIZE_STRING);
	$id_field = filter_var($_POST['id_field'],FILTER_SANITIZE_STRING);
	$id = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
	$recipient = filter_var($_POST['recipient'],FILTER_SANITIZE_STRING);
	$sender = filter_var($_POST['sender'],FILTER_SANITIZE_STRING);
	$subject = filter_var($_POST['subject'],FILTER_SANITIZE_STRING);
	$body = filter_var(htmlentities($_POST['body']),FILTER_SANITIZE_STRING);
	
	$value = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `$table_name` WHERE `$id_field`='$id'"));
	$body = str_replace(['[REFERENCE]','[WORKORDERID]','[CLIENT]','[HEADING]','[STATUS]'], [$value[$field_src],$value['workorderid'],get_client($dbc,$value['businessid']),$value['heading'],$value['status']],$body);
	$address = get_email($dbc, $recipient);
	try {
		send_email($sender, $address, '', '', $subject, $body, '');
	} catch(Exception $e) { echo "Unable to send e-mail: ".$e->getMessage(); }
} ?>