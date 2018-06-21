<?php
$starttime = date('Y-m-d');
$endtime = date('Y-m-d');
$table_row_style = '';
$projectid = '';
$afe_number = '';
$location = '';
$table_style = '';

if(isset($_GET['approval'])) {
    mysqli_query($dbc, "UPDATE `project_manage_assign_to_timer` SET `status`=1, `deleted`=0 WHERE `assigntotimerid`=" . $_GET['timerid']);
}
if(isset($_GET['pending'])) {
    mysqli_query($dbc,"UPDATE `project_manage_assign_to_timer` SET `status` = 0 WHERE `assigntotimerid`=" . $_GET['timerid']);
}
if(isset($_GET['delete'])) {
    $date_of_archival = date('Y-m-d');
    mysqli_query($dbc,"UPDATE `project_manage_assign_to_timer` SET `deleted`=1, `date_of_archival` = '$date_of_archival' WHERE `assigntotimerid`=" . $_GET['timerid']);
}

$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
    $pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;
$limit = '';
if(empty($filename)) {
	$limit = "LIMIT $offset, $rowsPerPage";
}

if(isset($_GET['tab'])) {
    if($dropdownworkdate == '' and $dropdownstaff != '' and $dropdownworkorder =='') {
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `created_by`='$dropdownstaff' AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";

        }   else{
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `timer_type`='Work' AND `created_by`='$dropdownstaff' ORDER BY `created_date` DESC";
        }
    }
    else if($dropdownworkdate == '' and $dropdownstaff == '' and $dropdownworkorder !='') {
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `timer_task`='$dropdownworkorder' AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `timer_task`='$dropdownworkorder' AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
        else {
            $result = mysqli_query($dbc,"SELECT `pmatt`.* FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC";
            /*
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `timer_task`='$dropdownworkorder' AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `timer_task`='$dropdownworkorder' AND `timer_type`='Work' ORDER BY `created_date` DESC";
            */
        }
    }
    else if($dropdownworkdate != '' and $dropdownstaff == '' and $dropdownworkorder =='') {
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
			$sql = "SELECT `created_by`";
			for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
				$sql .= ", SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`timer`),0))) `".$i."_timer`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`regular_hrs`),0))) `".$i."_reg`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`overtime_hrs`),0))) `".$i."_ot`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`travel_hrs`),0))) `".$i."_trav`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`subsist_hrs`),0))) `".$i."_sub`";
			}
			$sql .= ", SEC_TO_TIME(SUM(TIME_TO_SEC(`timer`))) total_timer, SEC_TO_TIME(SUM(TIME_TO_SEC(`regular_hrs`))) total_reg, SEC_TO_TIME(SUM(TIME_TO_SEC(`overtime_hrs`))) total_ot, SEC_TO_TIME(SUM(TIME_TO_SEC(`travel_hrs`))) total_trav, SEC_TO_TIME(SUM(TIME_TO_SEC(`subsist_hrs`))) total_sub";
			$sql .= " FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `status` = 1 AND `timer_type`='Work' GROUP BY `created_by` ORDER BY `created_date` DESC $limit";
            $result = mysqli_query($dbc,$sql);
            $query = "SELECT count(DISTINCT `created_by`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
        else {
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
   }
    else if($dropdownworkdate == '' and $dropdownstaff != '' and $dropdownworkorder !='') {
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' AND `timer_task`='$dropdownworkorder' AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' AND `timer_task`='$dropdownworkorder' and `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
        else {
            $result = mysqli_query($dbc,"SELECT `pmatt`.* FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE `pmatt`.`created_by`='$dropdownstaff' AND `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE `pmatt`.`created_by`='$dropdownstaff' AND `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC";
            /*
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' AND `timer_task`='$dropdownworkorder' AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' AND `timer_task`='$dropdownworkorder' AND `timer_type`='Work' ORDER BY `created_date` DESC";
            */
        }
    }
    else if($dropdownworkdate != '' and $dropdownstaff != '' and $dropdownworkorder =='') {
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
			$sql = "SELECT `created_by`";
			for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
				$sql .= ", SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`timer`),0))) `".$i."_timer`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`regular_hrs`),0))) `".$i."_reg`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`overtime_hrs`),0))) `".$i."_ot`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`travel_hrs`),0))) `".$i."_trav`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`subsist_hrs`),0))) `".$i."_sub`";
			}
			$sql .= ", SEC_TO_TIME(SUM(TIME_TO_SEC(`timer`))) total_timer, SEC_TO_TIME(SUM(TIME_TO_SEC(`regular_hrs`))) total_reg, SEC_TO_TIME(SUM(TIME_TO_SEC(`overtime_hrs`))) total_ot, SEC_TO_TIME(SUM(TIME_TO_SEC(`travel_hrs`))) total_trav, SEC_TO_TIME(SUM(TIME_TO_SEC(`subsist_hrs`))) total_sub";
			$sql .= " FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `created_by`='$dropdownstaff' AND `status` = 1 AND `timer_type`='Work' GROUP BY `created_by` ORDER BY `created_date` DESC $limit";
            $result = mysqli_query($dbc,$sql);
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `created_by`='$dropdownstaff' and `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
        else {
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `created_by`='$dropdownstaff' AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `created_by`='$dropdownstaff' AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
    }
    else if($dropdownworkdate != '' and $dropdownstaff == '' and $dropdownworkorder !='') {
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
			$sql = "SELECT `created_by`";
			for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
				$sql .= ", SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`timer`),0))) `".$i."_timer`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`regular_hrs`),0))) `".$i."_reg`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`overtime_hrs`),0))) `".$i."_ot`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`travel_hrs`),0))) `".$i."_trav`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`subsist_hrs`),0))) `".$i."_sub`";
			}
			$sql .= ", SEC_TO_TIME(SUM(TIME_TO_SEC(`timer`))) total_timer, SEC_TO_TIME(SUM(TIME_TO_SEC(`regular_hrs`))) total_reg, SEC_TO_TIME(SUM(TIME_TO_SEC(`overtime_hrs`))) total_ot, SEC_TO_TIME(SUM(TIME_TO_SEC(`travel_hrs`))) total_trav, SEC_TO_TIME(SUM(TIME_TO_SEC(`subsist_hrs`))) total_sub";
			$sql .= " FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `timer_task`='$dropdownworkorder' AND `status` = 1 AND `timer_type`='Work' GROUP BY `created_by` ORDER BY `created_date` DESC $limit";
            $result = mysqli_query($dbc,$sql);
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownworkdate' AND `timer_task`='$dropdownworkorder' and `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
        else {
            $result = mysqli_query($dbc,"SELECT `pmatt`.* FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE (`pmatt`.`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `pmatt`.`timer_task`='$dropdownworkorder' AND `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE (`pmatt`.`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `pmatt`.`timer_task`='$dropdownworkorder' AND `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC";
            /*
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `timer_task`='$dropdownworkorder' AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownworkdate' AND `timer_task`='$dropdownworkorder' AND `timer_type`='Work' ORDER BY `created_date` DESC";
            */
        }
    }
    else if($dropdownworkdate != '' and $dropdownstaff != '' and $dropdownworkorder !='') {
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
			$sql = "SELECT `created_by`";
			for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
				$sql .= ", SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`timer`),0))) `".$i."_timer`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`regular_hrs`),0))) `".$i."_reg`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`overtime_hrs`),0))) `".$i."_ot`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`travel_hrs`),0))) `".$i."_trav`, SEC_TO_TIME(SUM(IF(`created_date`='$i',TIME_TO_SEC(`subsist_hrs`),0))) `".$i."_sub`";
			}
			$sql .= ", SEC_TO_TIME(SUM(TIME_TO_SEC(`timer`))) total_timer, SEC_TO_TIME(SUM(TIME_TO_SEC(`regular_hrs`))) total_reg, SEC_TO_TIME(SUM(TIME_TO_SEC(`overtime_hrs`))) total_ot, SEC_TO_TIME(SUM(TIME_TO_SEC(`travel_hrs`))) total_trav, SEC_TO_TIME(SUM(TIME_TO_SEC(`subsist_hrs`))) total_sub";
			$sql .= " FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `timer_task`='$dropdownworkorder' AND `created_by`='$dropdownstaff' AND `status` = 1 AND `timer_type`='Work' GROUP BY `created_by` ORDER BY `created_date` DESC $limit";
            $result = mysqli_query($dbc,$sql);
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' and `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
        else {
            $result = mysqli_query($dbc,"SELECT `pmatt`.* FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE (`pmatt`.`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `pmatt`.`timer_task`='$dropdownworkorder' AND `pmatt`.`created_by`='$dropdownstaff' AND `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` AS `pmatt` JOIN `project_manage` AS `pm` ON (`pmatt`.`projectmanageid`=`pm`.`projectmanageid`) WHERE (`pmatt`.`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `pmatt`.`timer_task`='$dropdownworkorder' AND `pmatt`.`created_by`='$dropdownstaff' AND `pm`.`unique_id`='$dropdownworkorder' AND `pmatt`.`timer_type`='Work' ORDER BY `pmatt`.`created_date` DESC";
            /*
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND (`created_date` BETWEEN '$dropdownworkdate' AND '$dropdownworkenddate') AND `timer_task`='$dropdownworkorder' AND `created_by`='$dropdownstaff' AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `created_by`='$dropdownstaff' AND `timer_type`='Work' ORDER BY `created_date` DESC";
            */
        }
    }
    else{
        if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
        else {
            $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND IFNULL(`status`,0) = 0 AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
            $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND IFNULL(`status`,0) = 0 AND `timer_type`='Work' ORDER BY `created_date` DESC";
        }
    }
}
else {
    if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
        $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
        $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND `status` = 1 AND `timer_type`='Work' ORDER BY `created_date` DESC";
    }
    else {
        $result = mysqli_query($dbc,"SELECT * FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND IFNULL(`status`,0) = 0 AND `timer_type`='Work' ORDER BY `created_date` DESC $limit");
        $query = "SELECT count(`assigntotimerid`) numrows FROM `project_manage_assign_to_timer` WHERE `deleted` = 0 AND IFNULL(`status`,0) = 0 AND `timer_type`='Work'";
    }
}

if(empty($filename)) {
	echo '<a href="'.WEBSITE_URL.'/Project Workflow/add_project_manage.php?tile='.$tile.'&tab='.$tab_url.'&from_url='.urlencode($current_url).'" class="btn brand-btn mobile-block gap-bottom pull-right">Add Shop Time Sheet</a>';
	if(isset($_GET['tab']) && $_GET['tab'] == 'Payroll') {
		echo '<a href="'.WEBSITE_URL.'/Shop Time Sheet/payroll_csv.php?tab=Payroll&staff='.$dropdownstaff.'&workorder='.$dropdownworkorder.'&start='.$dropdownworkdate.'&end='.$dropdownworkenddate.'" class="btn brand-btn mobile-block gap-bottom pull-right">Export to CSV</a>';
	}
}
$num_rows = mysqli_num_rows($result);

if($num_rows > 0 && !empty($filename)) {
	if(!file_exists('download')) {
		mkdir('download', 0777);
	}
	$main_file = $filename;
	$i = 0;
	while(file_exists($filename)) {
		$filename = str_replace('.csv',' '.++$i.'.csv',$main_file);
	}
	$file = fopen($filename,'w');
	if(!$file) {
		echo "Unable to create Payroll CSV $filename!";
	}
	else
	{
		$headings = [];
		if (strpos($value_config, ','."Business".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$headings[] = 'Business Contact';
		}
		if (strpos($value_config, ','."Staff Name".',') !== FALSE) {
			$headings[] = 'Staff Name';
		}
		if (strpos($value_config, ','."Task Start Date".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$headings[] = 'Date Worked';
		}
		if (strpos($value_config, ','."Task".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$headings[] = 'Task';
		}
		if (strpos($value_config, ','."Task Worked".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$headings[] = 'Task#';
		}
		if (strpos($value_config, ','."Task Start Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$headings[] = 'Task Start Time';
		}
		if (strpos($value_config, ','."Task End Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$headings[] = 'Task End Time';
		}
		$task_time_label = '';
		if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
			$task_time_label .= ' Total -';
		}
		if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
			$task_time_label .= ' Regular -';
		}
		if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
			$task_time_label .= ' Overtime -';
		}
		if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
			$task_time_label .= ' Travel -';
		}
		if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
			$task_time_label .= ' Subsistence -';
		}
		if($task_time_label != '') {
			if($dropdownworkdate != '') {
				for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
					$headings[] = "Task Time ($i):".substr($task_time_label,0,-2);
				}
				$headings[] = "Total Time:".substr($task_time_label,0,-2);
			} else {
				$headings[] = "Task Time:".substr($task_time_label,0,-2);
			}
		}
		if (strpos($value_config, ','."Task Status".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$headings[] = 'Task Status';
		}
		fputcsv($file, $headings);
		while($row = mysqli_fetch_array( $result ))
		{
			$values = [];
			if (strpos($value_config, ','."Business".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
				$projectmanageid = $row['projectmanageid'];
				$businessid = get_project_manage($dbc, $projectmanageid, 'businessid');
				$contactid = get_project_manage($dbc, $projectmanageid, 'contactid');
				$values[] = get_contact($dbc, $businessid, 'name').($contactid > 0 ? ' - '.get_staff($dbc, $contactid) : '');
			}
			if (strpos($value_config, ','."Staff Name".',') !== FALSE) {
				$staffid=$row['created_by'];
				$staff = get_contact($dbc, $staffid);
				$values[] = trim($staff);
			}
			if (strpos($value_config, ','."Task Start Date".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
				$values[] = $row['created_date'];
			}
			if (strpos($value_config, ','."Task".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
				$values[] = ($row['timer_task'] != 'undefined' ? $row['timer_task'] : '');
			}
			if (strpos($value_config, ','."Task Start Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
				$values[] = $row['start_time'];
			}
			if (strpos($value_config, ','."Task End Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
				$values[] = $row['end_time'];
			}
			if($dropdownworkdate == '') {
				$task_time_value = '';
				if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
					$subresult = mysqli_query($dbc,"SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( `timer` ) ) ) AS all_timer FROM `project_manage_assign_to_timer` WHERE `created_by` = ' " . $row['created_by'] . " ' and `timer_task` = '".$row['timer_task']."' GROUP BY `projectmanageid`, `timer_task`");
					$subrow = mysqli_fetch_array($subresult);
					$task_time_value .= $row['timer'].' -';
				}
				if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
					$task_time_value .= $row['regular_hrs'].' -';
				}
				if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
					$task_time_value .= $row['overtime_hrs'].' -';
				}
				if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
					$task_time_value .= $row['travel_hrs'].' -';
				}
				if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
					$task_time_value .= $row['subsist_hrs'].' -';
				}
				$values[] = substr($task_time_value,0,-2);
			} else {
				for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
					$task_time_value = '';
					if($row[$i.'_timer'] != '00:00:00') {
						if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
							$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_timer'])).' -';
						}
						if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
							$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_reg'])).' -';
						}
						if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
							$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_ot'])).' -';
						}
						if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
							$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_trav'])).' -';
						}
						if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
							$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_sub'])).' -';
						}
					} else {
						$task_time_value = '-';
					}
					$values[] = substr($task_time_value,0,-2);
				}
				$task_time_value = '';
				if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
					$task_time_value .= ' '.$row['total_timer'].' -';
				}
				if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
					$task_time_value .= ' '.$row['total_reg'].' -';
				}
				if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
					$task_time_value .= ' '.$row['total_ot'].' -';
				}
				if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
					$task_time_value .= ' '.$row['total_trav'].' -';
				}
				if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
					$task_time_value .= ' '.$row['total_sub'].' -';
				}
				$values[] = substr($task_time_value,0,-2);
			}
			$flag=0;
			if (strpos($value_config, ','."Task Status".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
				if($row['timer_type'] == 'Break' || $row['timer_task'] == '' || $row['timer_task'] == null)
				{
					$values[] = 'Break';
				}
				elseif($row['end_time'] != '' && $row['end_time'] != null) {
					$values[] = 'Done';
				}
				elseif($row['timer_type'] == '')
				{
					$values[] = 'On Break';
				}
				else {
					$subresult = mysqli_query($dbc,"SELECT timer_type FROM `project_manage_assign_to_timer` WHERE `assigntotimerid` = ' " . $row['assigntotimerid'] . " ' and timer_type IN('On Break', 'Break')");
					$subrow = mysqli_fetch_array($subresult);
					if(isset($subrow['timer_type']) && $subrow['timer_type'] != '')
						$values[] = 'On Break';
					elseif($row['timer_type'] == 'Work') {
						$flag=1;
						$values[] = 'Doing Today';
					}
				}
			}
			fputcsv($file,$values);
		}
		fclose($file);
		header("Location: $filename");
	}
}
else if($num_rows > 0) {
    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
    echo "<table class='table table-bordered'>";
    echo "<tr class='hidden-xs hidden-sm'>";
    if (strpos($value_config, ','."Business".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Business Contact</th>';
    }
    if (strpos($value_config, ','."Staff Name".',') !== FALSE) {
        echo '<th>Staff Name</th>';
    }
	if (strpos($value_config, ','."Task Start Date".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Date Worked</th>';
    }
	if (strpos($value_config, ','."Task".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Task</th>';
    }
	if (strpos($value_config, ','."Task Worked".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Task#</th>';
    }
    if (strpos($value_config, ','."Task Start Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Task Start Time</th>';
    }
	if (strpos($value_config, ','."Task End Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Task End Time</th>';
    }
	$task_time_label = '';
    if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
        $task_time_label .= ' Total -';
    }
	if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
        $task_time_label .= ' Regular -';
	}
	if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
        $task_time_label .= ' Overtime -';
	}
	if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
        $task_time_label .= ' Travel -';
	}
	if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
        $task_time_label .= ' Subsistence -';
	}
	if($task_time_label != '') {
		if($dropdownworkdate != '') {
			for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
				echo '<th>Task Time ('.$i.')<br />'.substr($task_time_label,0,-2).'</th>';
			}
				echo '<th>Total Time<br />'.substr($task_time_label,0,-2).'</th>';
		} else {
			echo '<th>Task Time<br />'.substr($task_time_label,0,-2).'</th>';
		}
	}
    if (strpos($value_config, ','."Task Status".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Task Status</th>';
    }
    if (strpos($value_config, ','."Task Approval".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
        echo '<th>Task Approval</th>';
    }
    echo "</tr>";

	while($row = mysqli_fetch_array( $result ))
	{
		echo "<tr>";
		if (strpos($value_config, ','."Business".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$projectmanageid = $row['projectmanageid'];
			$businessid = get_project_manage($dbc, $projectmanageid, 'businessid');
			$contactid = get_project_manage($dbc, $projectmanageid, 'contactid');
			echo '<td data-title="Notes">' . get_contact($dbc, $businessid, 'name').'<br>'.get_staff($dbc, $contactid) . '</td>';
		}
		if (strpos($value_config, ','."Staff Name".',') !== FALSE) {
			$staffid=$row['created_by'];
			echo '<td data-title="staff-name">'.get_contact($dbc, $staffid).'</td>';
		}
		if (strpos($value_config, ','."Task Start Date".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			if($row['created_date'] != null && $row['created_date'] != '')
				echo '<td data-title="task_start_date">'.$row['created_date'].'</td>';
			else
				echo '<td data-title="task_start_date"></td>';
		}
		if (strpos($value_config, ','."Task".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			if($row['timer_task'] != null && $row['timer_task'] != '')
				echo '<td data-title="task">'.($row['timer_task'] != 'undefined' ? $row['timer_task'] : '').'</td>';
			else
				echo '<td data-title="task"></td>';
		}
		if (strpos($value_config, ','."Task Start Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			if($row['start_time'] != null && $row['start_time'] != '')
				echo '<td data-title="task_start_time">'.$row['start_time'].'</td>';
			else
				echo '<td data-title="task_start_time"></td>';
		}
		if (strpos($value_config, ','."Task End Time".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			if($row['end_time'] != null && $row['end_time'] != '')
				echo '<td data-title="task_end_time">'.$row['end_time'].'</td>';
			else
				echo '<td data-title="task_end_time"></td>';
		}
		if($dropdownworkdate == '') {
			$task_time_value = '';
			if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
				$subresult = mysqli_query($dbc,"SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( `timer` ) ) ) AS all_timer FROM `project_manage_assign_to_timer` WHERE `created_by` = ' " . $row['created_by'] . " ' and `timer_task` = '".$row['timer_task']."' GROUP BY `projectmanageid`, `timer_task`");
				$subrow = mysqli_fetch_array($subresult);
				$task_time_value .= ' '.$row['timer'].' -';
			}
			if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['regular_hrs'].' -';
			}
			if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['overtime_hrs'].' -';
			}
			if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['travel_hrs'].' -';
			}
			if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['subsist_hrs'].' -';
			}
			echo '<td data-title="task_time_durations">'.substr($task_time_value,0,-2).'</td>';
		} else {
			for($i = $dropdownworkdate; $i <= $dropdownworkenddate; $i = date('Y-m-d',strtotime('+1 day',strtotime($i)))) {
				$task_time_value = '';
				if($row[$i.'_timer'] != '00:00:00') {
					if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
						$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_timer'])).' -';
					}
					if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
						$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_reg'])).' -';
					}
					if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
						$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_ot'])).' -';
					}
					if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
						$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_trav'])).' -';
					}
					if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
						$task_time_value .= ' '.date('G:i',strtotime($row[$i.'_sub'])).' -';
					}
				} else {
					$task_time_value = '-';
				}
				echo '<td data-title="task_time_durations">'.substr($task_time_value,0,-2).'</td>';
			}
			$task_time_value = '';
			if (strpos($value_config, ','."Total Task Time".',') !== FALSE) {
				$task_time_value .= ' '.$row['total_timer'].' -';
			}
			if (strpos($value_config, ','."Regular Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['total_reg'].' -';
			}
			if (strpos($value_config, ','."Overtime Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['total_ot'].' -';
			}
			if (strpos($value_config, ','."Travel Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['total_trav'].' -';
			}
			if (strpos($value_config, ','."Subsist Hours".',') !== FALSE) {
				$task_time_value .= ' '.$row['total_sub'].' -';
			}
			echo '<td data-title="task_time_durations">'.substr($task_time_value,0,-2).'</td>';
		}
		$flag=0;
		if (strpos($value_config, ','."Task Status".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			if($row['timer_type'] == 'Break' || $row['timer_task'] == '' || $row['timer_task'] == null)
			{
				echo '<td data-title="task_status">Break</td>';
			}
			elseif($row['end_time'] != '' && $row['end_time'] != null) {
				echo '<td data-title="task_status">Done</td>';
			}
			elseif($row['timer_type'] == '')
			{
				echo '<td data-title="task_status">On Break</td>';
			}
			else {
				$subresult = mysqli_query($dbc,"SELECT timer_type FROM `project_manage_assign_to_timer` WHERE `assigntotimerid` = ' " . $row['assigntotimerid'] . " ' and timer_type IN('On Break', 'Break')");
				$subrow = mysqli_fetch_array($subresult);
				if(isset($subrow['timer_type']) && $subrow['timer_type'] != '')
					echo '<td data-title="task_status">On Break</td>';
				elseif($row['timer_type'] == 'Work' && $row['end_time'] != '') {
					echo '<td data-title="task_status">Done</td>';
				} elseif($row['timer_type'] == 'Work') {
					$flag=1;
					echo '<td data-title="task_status">Doing</td>';
				}
			}


		}
		if (strpos($value_config, ','."Task Approval".',') !== FALSE && ($dropdownworkdate == '' || $_GET['tab'] != 'Payroll')) {
			$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			if($_GET['tab'] == 'Payroll') {
				$pending = '';
			}
			elseif($row['status'] == 0 || $row['status'] == '')
				$pending = 'Pending / ';
			else {
				$url = substr($url, 0, strpos($url, 'tab=')) . "tab=".$_GET['tab'];
				$pending = '<a href="'.$url.'&pending=0&timerid='.$row["assigntotimerid"].'">Pending</a> / ';
			}

			if($flag == 1) {
				$approved='';
			}
			else if($row['status'] == 1)
				$approved = 'Approved / ';
			else {
				$url = substr($url, 0, strpos($url, 'tab=')) . "tab=".$_GET['tab'];
				$approved = '<a href="'.$url.'&approval=1&timerid='.$row["assigntotimerid"].'">Approve</a> / ';
			}

			$url = substr($url, 0, strpos($url, 'tab=')) . "tab=".$_GET['tab'];
			$edit = '<a href="add_project_manage.php?tile=Shop Work Orders&tab=Shop Time Clock&tab_from_tile_view=Shop Time Sheets&timerid='.$row['assigntotimerid'].'">Edit</a> / ';
			$delete =  '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&assigntotimerid='.$row['assigntotimerid'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';

			echo '<td data-title="task_approval">'.$pending . $approved. $edit. $delete.'</td>';
		}
		echo "</tr>";
	}
    echo '</table>';
    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
} else {
    echo "<h2>No Record Found.</h2>";
}


