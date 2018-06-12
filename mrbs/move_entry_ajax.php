<?php 
require "defaultincludes.inc";
require_once "mrbs_sql.inc";

if($_GET['action'] == 'day_move') {
	$calid = $_POST['id'];
	$roomid = $_POST['roomid'];
	$time = $_POST['time'];
	$hour = floor($time / 3600);
	$minute = floor(($time % 3600) / 60);
	$seconds = $time % 60;
	
	$current_time = sql_row_keyed(sql_query("SELECT `appoint_date`, `end_appoint_date` FROM `booking` WHERE `calid`='$calid'"),0);
	$date = substr($current_time['appoint_date'],0,10);
	$duration = strtotime($current_time['end_appoint_date']) - strtotime($current_time['appoint_date']);
	$start_date = $date.' '.sprintf("%02d", $hour).':'.sprintf("%02d", $minute).':'.sprintf("%02d", $seconds);
	$end_date = date('Y-m-d H:i:s', strtotime($start_date) + $duration);
	
	$therapist_name = sql_row_keyed(sql_query("SELECT `room_name` FROM `mrbs_room` WHERE `id`='$roomid'"),0)['room_name'];
	$therapist_list = sql_query("SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category`='Staff'");
	$therapistid = 0;
	$i = 0;
	while($therapistid == 0 && $therapist_row = sql_row_keyed($therapist_list, $i++)) {
		if($therapist_name == decryptIt($therapist_row['first_name']).' '.decryptIt($therapist_row['last_name'])) {
			$therapistid = $therapist_row['contactid'];
		}
	}
	
	$sql = "UPDATE `mrbs_entry` SET `start_time`='".strtotime($start_date)."', `end_time`='".strtotime($end_date)."', `room_id`='$roomid' WHERE `id`='$calid'";
	sql_command($sql);
	$sql = "UPDATE `booking` SET `appoint_date`='$start_date', `end_appoint_date`='$end_date', `therapistsid`='$therapistid' WHERE `calid`='$calid'";
	sql_command($sql);
} else if($_GET['action'] == 'week_move') {
	$calid = $_POST['id'];
	$date = explode('-',$_POST['date']);
	$date = $date[0].'-'.sprintf("%02d",$date[1]).'-'.sprintf("%02d",$date[2]);
	$time = $_POST['time'];
	$hour = floor($time / 3600);
	$minute = floor(($time % 3600) / 60);
	$seconds = $time % 60;
	
	$current_time = sql_row_keyed(sql_query("SELECT `appoint_date`, `end_appoint_date` FROM `booking` WHERE `calid`='$calid'"),0);
	$duration = strtotime($current_time['end_appoint_date']) - strtotime($current_time['appoint_date']);
	$start_date = $date.' '.sprintf("%02d", $hour).':'.sprintf("%02d", $minute).':'.sprintf("%02d", $seconds);
	$end_date = date('Y-m-d H:i:s', strtotime($start_date) + $duration);
	
	$sql = "UPDATE `mrbs_entry` SET `start_time`='".strtotime($start_date)."', `end_time`='".strtotime($end_date)."' WHERE `id`='$calid'";
	sql_command($sql);
	$sql = "UPDATE `booking` SET `appoint_date`='$start_date', `end_appoint_date`='$end_date' WHERE `calid`='$calid'";
	sql_command($sql);
}