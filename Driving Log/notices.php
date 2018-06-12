	<?php 	
	if (isset($_POST['dismiss_notices'])) {
		$query_dimiss = mysqli_query($dbc, "UPDATE `driving_log` SET `audit_dismiss` = 1 WHERE `drivinglogid` = '".$_POST['drivinglogid']."'");
		$url = $_GET['from_url'];
    	echo '<script type="text/javascript">window.location.href = "'.$url.'";</script>';
	}

	$view_only_mode = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `driving_log_view_only_mode` WHERE `contactid` = '".$_SESSION['contactid']."'"))['view_only_mode'];
	include_once('view_only_mode.php');
/**
 * File Name : notices.php
 * Short description for file : This file essentially checks the current driving log for any rules that have been broken. If rules have been broken, they will be shown in the Notices window of the current driving log. These broken rules can also be seen by Admins or Super Admins in the Audit section of the Driving Logs (where they can dismiss the notices from the software). This file is attached to multiple pages on the Driving Log (mostly in the driving_log_ajax.php file).
 * Long description for file (if any)...
 *
 * @author Kelsey Nealon
*/
			$drivinglogid = $_GET['drivinglogid'];
			
			// GET TIME LEFT for Audits

			$col = "SELECT `reset_cycle` FROM driving_log_timer";
			$result123 = mysqli_query($dbc, $col);
			if (!$result123){
				$colcreate = "ALTER TABLE `driving_log_timer` ADD COLUMN `reset_cycle` VARCHAR(555) NULL";
				$result = mysqli_query($dbc, $colcreate);
			}
			
			$get_driver = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log WHERE drivinglogid='$drivinglogid'"));
			$driverid = $get_driver['driverid'];
			$cycler = $get_driver['cycle'];
			
			$get_time_left = "SELECT * FROM `driving_log` WHERE `driverid` = '$driverid' AND `cycle` = '$cycler' ORDER BY `drivinglogid` DESC";
			
			$result1 = mysqli_query($dbc, $get_time_left);
			$num_rows1 = mysqli_num_rows($result1);
			
			if($num_rows1 > 0) {
				
				$on_duty_time = '';
				$seconds = 0;
				$minutes = 0;
				$hours = 0;
				while($row1 = mysqli_fetch_array($result1)) {
					$drivinglogid = $row1['drivinglogid'];
					$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid DESC";
					$result2 = mysqli_query($dbc, $select_timers);
					$num_rows2 = mysqli_num_rows($result2);
					$is_reset = '';
					if($num_rows2 > 0) {
						while($row2 = mysqli_fetch_array($result2)) {
							if($row2['on_duty_timer'] !== '' && $row2['on_duty_timer'] !== NULL) {
								$reverse_explode = array_reverse(explode(':',$row2['on_duty_timer']));
								$i = 0;
								$len = count($reverse_explode);
								foreach( $reverse_explode as $time ) {
									if ($i == 0) {
										$seconds += $time;
									} else if ($i == $len - 1) {
										$hours += $time;
									} else {
										$minutes += $time;
									}
									$i++;
								}
							}
							if($row2['driving_timer'] !== '' && $row2['driving_timer'] !== NULL) {
								$reverse_explode = array_reverse(explode(':',$row2['driving_timer']));
								$i = 0;
								$len = count($reverse_explode);
								foreach( $reverse_explode as $time ) {
									if ($i == 0) {
										$seconds += $time;
									} else if ($i == $len - 1) {
										$hours += $time;
									} else {
										$minutes += $time;
									}
									$i++;
								}
							}
							if($row2['reset_cycle'] == 1) {
								$is_reset .='1';
								break;
							}
						}
					}
					if ($is_reset == '1') {
						break;
					}
					
				}
			}
			
			$minute_from_seconds = $seconds/60;
			$minute_add = floor($minute_from_seconds);
			$seconds_left = $minute_from_seconds - $minute_add;
			$seconds = $seconds_left*60;
			
			$minutes = $minutes + $minute_add;
			$minute_circle = $minutes + $minute_from_seconds;
			
			$hours_from_minutes = $minutes/60;
			$hour_circle = ((70-((($minutes + $minute_circle)/60) + $hours))/70);
			$hour_add = floor($hours_from_minutes);
			$minutes_left = $hours_from_minutes - $hour_add;
			$minutes = $minutes_left*60;
			
			$hours = $hours+$hour_add;
			// add if statement
			if($cycler == 'Cycle 1(7 days)') {	
				$hours_left = 70-$hours;
			} else {
				$hours_left = 120-$hours;
			}
			$minutes_left = 60-$minutes;
			if($seconds !== 0) {
				$minutes_left = $minutes_left-1;
				$hours_left = $hours_left-1;
			} else if ($minutes_left !== 0) {
				$hours_left = $hours_left-1;
			}
			$hours_left = sprintf("%02d", $hours_left);
			$minutes_left = sprintf("%02d", $minutes_left);
			$seconds_left = sprintf("%02d", 60-$seconds);
			if($cycler == 'Cycle 1(7 days)') {	
				if($hours_left == 69 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left = '70:00:00';
					$time_left_no_negtv = '120:00:00';
				} else if($hours_left < 0) {
					$time_left = '<span style="color:red">00:00:00</span>';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				} else {
					$time_left = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
			} else {
				if($hours_left == 119 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left = '120:00:00';
					$time_left_no_negtv = '120:00:00';
				} else if($hours_left < 0) {
					$time_left = '<span style="color:red">00:00:00</span>';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				} else {
					$time_left = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
			}
			// DONE GETTING CYCLE TIME
			// BEGIN SHIFT TIME
			$on_duty_time = '';
				$seconds = 0;
				$minutes = 0;
				$hours = 0;
			$drivinglogid = $_GET['drivinglogid'];
					$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid DESC";
					$result2 = mysqli_query($dbc, $select_timers);
					$num_rows2 = mysqli_num_rows($result2);
					$is_reset = '';
					if($num_rows2 > 0) {
						while($row2 = mysqli_fetch_array($result2)) {
							if($row2['on_duty_timer'] !== '' && $row2['on_duty_timer'] !== NULL) {
								$reverse_explode = array_reverse(explode(':',$row2['on_duty_timer']));
								$i = 0;
								$len = count($reverse_explode);
								foreach( $reverse_explode as $time ) {
									if ($i == 0) {
										$seconds += $time;
									} else if ($i == $len - 1) {
										$hours += $time;
									} else {
										$minutes += $time;
									}
									$i++;
								}
							}
							if($row2['driving_timer'] !== '' && $row2['driving_timer'] !== NULL) {
								$reverse_explode = array_reverse(explode(':',$row2['driving_timer']));
								$i = 0;
								$len = count($reverse_explode);
								foreach( $reverse_explode as $time ) {
									if ($i == 0) {
										$seconds += $time;
									} else if ($i == $len - 1) {
										$hours += $time;
									} else {
										$minutes += $time;
									}
									$i++;
								}
							}
						}
					}
			$minute_from_seconds = $seconds/60;
			$minute_add = floor($minute_from_seconds);
			$seconds_left = $minute_from_seconds - $minute_add;
			$seconds = $seconds_left*60;
			
			$minutes = $minutes + $minute_add;
			$minute_circle = $minutes + $minute_from_seconds;
			
			$hours_from_minutes = $minutes/60;
			$hour_circle_shift = ((14-((($minutes + $minute_circle)/60) + $hours))/14);
			$hour_add = floor($hours_from_minutes);
			$minutes_left = $hours_from_minutes - $hour_add;
			$minutes = $minutes_left*60;
			
			$hours = $hours+$hour_add;
			// add if statement
			
				$hours_left = 14-$hours;
			
			$minutes_left = 60-$minutes;
			if($seconds !== 0) {
				$minutes_left = $minutes_left-1;
				$hours_left = $hours_left-1;
			} else if ($minutes_left !== 0) {
				$hours_left = $hours_left-1;
			}
			$hours_left = sprintf("%02d", $hours_left);
			$minutes_left = sprintf("%02d", $minutes_left);
			$seconds_left = sprintf("%02d", 60-$seconds);
				if($hours_left == 13 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left_shift = '14:00:00';
					$time_left_shift_no_negtv = '14:00:00';
				} else if($hours_left < 0) {
					$time_left_shift = '<span style="color:red">00:00:00</span>';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_shift_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				} else {
					$time_left_shift = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_shift_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
					
			// END GETTING SHIFT TIME
			// BEGIN DRIVING TIME
			$on_duty_time = '';
				$seconds = 0;
				$minutes = 0;
				$hours = 0;		
					$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid DESC";
					$result34 = mysqli_query($dbc, $select_timers);
					$num_rows34 = mysqli_num_rows($result34);
					$is_reset = '';
					if($num_rows34 > 0) {
						while($row2 = mysqli_fetch_array($result34)) {
							if($row2['driving_timer'] !== '' && $row2['driving_timer'] !== NULL) {
								$reverse_explode = array_reverse(explode(':',$row2['driving_timer']));
								$i = 0;
								$len = count($reverse_explode);
								foreach( $reverse_explode as $time ) {
									if ($i == 0) {
										$seconds += $time;
									} else if ($i == $len - 1) {
										$hours += $time;
									} else {
										$minutes += $time;
									}
									$i++;
								}
							}
						}
					}
			$minute_from_seconds = $seconds/60;
			$minute_add = floor($minute_from_seconds);
			$seconds_left = $minute_from_seconds - $minute_add;
			$seconds = $seconds_left*60;
			
			$minutes = $minutes + $minute_add;
			$minute_circle = $minutes + $minute_from_seconds;
			
			$hours_from_minutes = $minutes/60;
			$hour_circle_drive = ((13-((($minutes + $minute_circle)/60) + $hours))/13);
			$hour_add = floor($hours_from_minutes);
			$minutes_left = $hours_from_minutes - $hour_add;
			$minutes = $minutes_left*60;
			
			$hours = $hours+$hour_add;
			// add if statement
			
				$hours_left = 13-$hours;
			
			$minutes_left = 60-$minutes;
			if($seconds !== 0) {
				$minutes_left = $minutes_left-1;
				$hours_left = $hours_left-1;
			} else if ($minutes_left !== 0) {
				$hours_left = $hours_left-1;
			}
			$hours_left = sprintf("%02d", $hours_left);
			$minutes_left = sprintf("%02d", $minutes_left);
			$seconds_left = sprintf("%02d", 60-$seconds);
			
				if($hours_left == 12 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left_drive = '13:00:00';
					$time_left_drive_no_negtv = '13:00:00';
				} else if($hours_left < 0) {
					$time_left_drive = '<span style="color:red">00:00:00</span>';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_drive_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				} else {
					$time_left_drive = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
					if($seconds_left == 60) {
						$seconds_left = '00';
					}
					$time_left_drive_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
			// END GETTING Driving TIME
			include('get_total_timer_times.php');
			$time_left_off_duty = $hours2;
			// CREATE AUDIT NOTICES 
			
					if($time_left_no_negtv < 0) {
						$time = str_replace('-', '', $time_left_no_negtv); 
						$query_update = "UPDATE `driving_log` SET audit_cycle_time='$time' WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					} else {
						$query_update = "UPDATE `driving_log` SET audit_cycle_time=NULL WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					}
					if($time_left_shift_no_negtv < 0) {
						$time = str_replace('-', '', $time_left_shift_no_negtv); 
						$query_update = "UPDATE `driving_log` SET audit_shift_time='$time' WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					} else {
						$query_update = "UPDATE `driving_log` SET audit_shift_time=NULL WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					}
					if($time_left_drive_no_negtv < 0) {
						$time = str_replace('-', '', $time_left_drive_no_negtv); 
						$query_update = "UPDATE `driving_log` SET audit_drive_time='$time' WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					} else {
						$query_update = "UPDATE `driving_log` SET audit_drive_time=NULL WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					}
					$time_left_audit_drive_sixteen = $hourst2+$minutest2;
					// if statement below makes sure that the off_duty audit only takes place when the user ends their driving log officially.
					if(isset($set_off_duty_audit) && $set_off_duty_audit == 'true') {
						if($time_left_off_duty < 10) {
							$time = str_replace('-', '', $off_h_time); 
							$query_update = "UPDATE `driving_log` SET audit_off_duty='$time' WHERE drivinglogid = '$drivinglogid'";
							$result_update = mysqli_query($dbc, $query_update);
						} else {
							$query_update = "UPDATE `driving_log` SET audit_off_duty=NULL WHERE drivinglogid = '$drivinglogid'";
							$result_update = mysqli_query($dbc, $query_update);
						}
					}
					if($time_left_audit_drive_sixteen > 0) {
						$time = str_replace('-', '', $total_audit_drive_time); 
						$query_update = "UPDATE `driving_log` SET audit_drive_sixteen='$time' WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					} else {
						$query_update = "UPDATE `driving_log` SET audit_drive_sixteen=NULL WHERE drivinglogid = '$drivinglogid'";
						$result_update = mysqli_query($dbc, $query_update);
					}
		
			// if(isset($_GET['showtime']) && $_GET['showtime'] == 'audit') {
		// SHOW IN DRIVE MODE NOTICE LOG	
			?>		<div class='hider' style='position:relative;width:100%;min-height:200px;top:0px; padding:10px;  color:black;'>
					<?php
					if($time_left_no_negtv < 0) {
						echo '<img src="../img/warning.png" style="width:25px"> <b>Cycle Time:</b> <span style="color:red; font-weight:bold;">'.str_replace('-', '', $time_left_no_negtv).'</span> over.'; 
					} else { echo '<img src="../img/checkmark.png" style="width:25px"> Cycle Time: No notices.'; }
					echo '<br><br>';
					if($time_left_shift_no_negtv < 0) {
						echo '<img src="../img/warning.png" style="width:25px"> <b>Shift Time:</b> <span style="color:red; font-weight:bold;">'.str_replace('-', '', $time_left_shift_no_negtv).'</span> over.'; 
					} else { echo '<img src="../img/checkmark.png" style="width:25px"> Shift Time: No notices.'; }
					echo '<br><br>';
					if($time_left_drive_no_negtv < 0) {
						echo '<img src="../img/warning.png" style="width:25px"> <b>Drive Time:</b> <span style="color:red; font-weight:bold;">'.str_replace('-', '', $time_left_drive_no_negtv).'</span> over.'; 
					} else { echo '<img src="../img/checkmark.png" style="width:25px"> Drive Time: No notices.'; }
					echo '<br><br>';
					if($time_left_off_duty < 10) {
						echo '<img src="../img/warning.png" style="width:25px"> <b>You have not had a minimum of 10 off-duty hours for today\'s shift:</b> You have only <span style="color:red; font-weight:bold;">'.str_replace('-', '', $off_h_time).' </span> hours out of 10.'; 
					} else { echo '<img src="../img/checkmark.png" style="width:25px"> You have had a minimum of 10 off-duty hours this shift.'; }
					echo '<br><br>';
					if($time_left_audit_drive_sixteen > 0) { 
						echo '<img src="../img/warning.png" style="width:25px"> <b>You have driven after 16 hours of total elapsed time in your work shift ('.$total_audit_drive_time.').</b>'; 
					} else {
						echo '<img src="../img/checkmark.png" style="width:25px"> You have not driven after 16 hours of total elapsed time in your work shift.';
					}

					if(isset($_GET['showtime']) && $_GET['showtime'] == 'audit') {
						echo '<div class="clearfix"></div><br />';
						echo '<form id="form1" name="form1" method="post" enctype="multipart/form-data" role="form">';
						echo '<input type="hidden" name="drivinglogid" value="'.$drivinglogid.'">';
						if ($view_only_mode != 1) {
							echo '<button type="submit" name="dismiss_notices" class="btn brand-btn">Dismiss Notices</button>';
						}
						echo '</form>';
					}
					
					/*
					if(isset($_GET['showtime']) && $_GET['showtime'] == 'audit' && isset($_GET['dismiss'])) { ?>
						<br><br>
						<span style='top:-5px; position:relative;font-weight:bold;'>Dismiss these notices: </span><input type="checkbox" onchange="tileConfig(this)" name="dismiss_notice" value="<?php echo $drivinglogid; ?>" <?php if($_GET['dismiss'] == '1') { echo 'checked'; } ?> class="dismiss_notice" style="height:20px;width:20px;">
						<center><a href="javascript: history.go(-1)" class="btn brand-btn">Close</a></center>
					<?php } else { ?>				
						<center><button type='button' class='closer btn brand-btn'  onclick="closethatthing()" style='position: relative; margin:auto; color: black;    opacity: 1;    border: 1px solid black;    padding: 10px;    background-color: grey;'>Close</button></center>
					<?php } */ ?>
				</div>
					
			<?php //} ?>
					
	<!-- // END TIME LEFT -->