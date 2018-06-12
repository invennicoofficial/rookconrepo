<?php
include	('../include.php');
error_reporting(0);

//Auto-Lock
$autolock = get_config($dbc, 'staff_schedule_autolock');
$autolock_dayofmonth = get_config($dbc, 'staff_schedule_autolock_dayofmonth');
$autolock_dayofmonth = $autolock_dayofmonth > date('t') ? date('t') : $autolock_dayofmonth;
$lock_date = date('Y-m-'.$autolock_dayofmonth);
$lock_date = date('Y-m-d', strtotime($lock_date));

$autolock_numdays = get_config($dbc, 'staff_schedule_autolock_numdays');
$autolock_numdays = $autolock_numdays > date('t') ? date('t') : $autolock_numdays;

$end_date = date('Y-m-'.$autolock_numdays);
$end_date = date('Y-m-d', strtotime($end_date));
$autolock_month = get_config($dbc, 'staff_schedule_autolock_month');
if($autolock_month > 0) {
	$end_date = date('Y-m-01');
	$end_date = date('Y-m-01', strtotime($end_date.' + '.$autolock_month.' months'));
	$autolock_numdays = get_config($dbc, 'staff_schedule_autolock_numdays');
	$autolock_numdays = $autolock_numdays > date('t', strtotime($end_date)) ? date('t', strtotime($end_date)) : $autolock_numdays;
	$end_date = date('Y-m-'.$autolock_numdays, strtotime($end_date));
}
$end_date = date('Y-m-d', strtotime($end_date));
$start_date = date('Y-m-01', strtotime($end_date));
$staff_schedule_lock_date = date('Y-m-d', strtotime($end_date.' + 1 day'));
if($autolock == 1) {
	if($autolock_dayofmonth == date('d')) {
		set_config($dbc, 'staff_schedule_lock_date', $staff_schedule_lock_date);
	}
}

//Auto-Lock Reminder Emails
$reminder_emails = get_config($dbc, 'staff_schedule_reminder_emails');
if($reminder_emails == 1) {
	$today_date = date('Y-m-d');
	$reminder_dates = get_config($dbc, 'staff_schedule_reminder_dates');
	if(!empty($reminder_dates)) {
		$reminder_dates = explode(',', $reminder_dates);
		$global_lock_date = $lock_date;
		$global_end_date = $end_date;
		foreach ($reminder_dates as $reminder_date) {
			$reminder_date = $reminder_date > date('t') ? date('t') : $reminder_date;
			if($reminder_date > $autolock_dayofmonth) {
				$lock_date = date('Y-m-01', strtotime($global_lock_date));
				$lock_date = date('Y-m-01', strtotime($lock_date.' + 1 month'));
				$autolock_dayofmonth = get_config($dbc, 'staff_schedule_autolock_dayofmonth');
				$autolock_dayofmonth = $autolock_dayofmonth > date('t') ? date('t') : $autolock_dayofmonth;
				$lock_date = date('Y-m-'.$autolock_dayofmonth, strtotime($lock_date));
				$lock_date = date('Y-m-d', strtotime($lock_date));

				$end_date = date('Y-m-01', strtotime($global_end_date));
				$end_date = date('Y-m-01', strtotime($end_date.' + 1 month'));
				$autolock_numdays = get_config($dbc, 'staff_schedule_autolock_numdays');
				$autolock_numdays = $autolock_numdays > date('t', strtotime($end_date)) ? date('t', strtotime($end_date)) : $autolock_numdays;
				$end_date = date('Y-m-'.$autolock_numdays, strtotime($end_date));
				$end_date = date('Y-m-d', strtotime($end_date));
				$start_date = date('Y-m-01', strtotime($end_date));
			}
			$reminder_date = date('Y-m-'.$reminder_date);
			$already_sent = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `staff_schedule_autolock_reminders` WHERE `date` = '$reminder_date'"))['sent'];
			if($reminder_date == $today_date && $already_sent != 1) {
				$from = get_config($dbc, 'staff_schedule_reminder_from');
				$subject = str_replace(['[STARTDATE]','[ENDDATE]','[LOCKDATE]'],[$start_date,$end_date,$lock_date],get_config($dbc, 'staff_schedule_reminder_subject'));
				$body = str_replace(['[STARTDATE]','[ENDDATE]','[LOCKDATE]'],[$start_date,$end_date,$lock_date],get_config($dbc, 'staff_schedule_reminder_body'));
				$staff_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `office_email` != '' AND `user_name` != '' AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status`>0 AND `deleted`=0"),MYSQLI_ASSOC);
				mysqli_query($dbc, "INSERT INTO `staff_schedule_autolock_reminders` (`date`, `sent`) VALUES ('$reminder_date', 1)");
				$log = '';
				foreach ($staff_list as $staff) {
					$email = decryptIt($staff['office_email']);
					try {
						send_email($from, $email, '', '', $subject, html_entity_decode($body), '');
					} catch(Exception $e) { 
						$log = "Unable to send e-mail to ".get_contact($dbc, $staff['contactid']).": ".$e->getMessage()."\n";
						mysqli_query($dbc, "UPDATE `staff_schedule_autolock_reminders` SET `log` = CONCAT(`log`, '$log') WHERE `date` = '$reminder_date'"); }
				}
			}
		}
	}
}