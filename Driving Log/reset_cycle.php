<?php
// "I advise whoever is looking at this code not to include it anywhere else besides the driving_log_timer.php page, or else you may get errors." - Kelsey Nealon

/**
 * File Name : reset_cycle.php
 * Short description for file : This file essentially resets the Cycle Time (found in the Cycle Time window on the current driving log's timer page) if the driver has had enough off-duty time.
 * Long description for file (if any)...
 *
 * @author Kelsey Nealon
*/


// {RESET CYCLE IF GREATER THAN OR EQUAL TO 7 DAYS (OR 14 DAYS FOR 14 DAY CYCLE) SINCE LAST CYCLE RESET
			
			$col = "SELECT `reset_cycle` FROM driving_log_timer";
			$result = mysqli_query($dbc, $col);
			if (!$result){
				$colcreate = "ALTER TABLE `driving_log_timer` ADD COLUMN `reset_cycle` VARCHAR(555) NULL";
				$result = mysqli_query($dbc, $colcreate);
			}
			$drivinglogid = $_GET['drivinglogid'];
			$get_driver = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log WHERE drivinglogid='$drivinglogid'"));
			$driverid = $get_driver['driverid'];
			$cycler = $get_driver['cycle'];
			if($cycler == 'Cycle 1(7 days)') {	
				$daysadder = 7;
			} else {
				$daysadder = 14;
			}
			$get_time_left = "SELECT * FROM `driving_log` WHERE `driverid` = '$driverid' AND `cycle` = '$cycler' ORDER BY `drivinglogid` DESC";
			
			$result1 = mysqli_query($dbc, $get_time_left);
			$num_rows1 = mysqli_num_rows($result1);
			$count_dl_date = 0;
			if($num_rows1 > 0) {
				$on_duty_time = '';
				$seconds = 0;
				$minutes = 0;
				$hours = 0;
				while($row1 = mysqli_fetch_array($result1)) {
					$drivinglogid = $row1['drivinglogid'];
					$starterdater = $row1['start_date'];
					$enderdater = $row1['end_date'];
					$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid DESC";
					$result2 = mysqli_query($dbc, $select_timers);
					$num_rows2 = mysqli_num_rows($result2);
					$is_reset = '';
					$count_dl_date++;
					if($num_rows2 > 0) {
						$timer_counter = 0;
						while($row2 = mysqli_fetch_array($result2)) {
							$timer_counter++;
								if($row2['reset_cycle'] == 1) {
									if($enderdater == '' || $enderdater == NULL) {
										$date = date('Y/m/d', time());
										$weekfromdate = date('Y/m/d', strtotime($starterdater) + (86400 * $daysadder));
										if (new DateTime($date) >= new DateTime($weekfromdate)) {
											$get_time_left2 = "SELECT * FROM `driving_log` WHERE `driverid` = '$driverid' AND `cycle` = '$cycler' ORDER BY `drivinglogid` DESC";
											$result12 = mysqli_query($dbc, $get_time_left2);
											while($rowx = mysqli_fetch_array($result12)) {
												$dlogider = $row1['drivinglogid'];
												$select_timersz = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$dlogider' ORDER BY timerid DESC";
												$result22 = mysqli_query($dbc, $select_timersz);
												while($row2z = mysqli_fetch_array($result22)) {
													$timerider = $row2z['timerid'];	
													$result_update_t = mysqli_query($dbc, "UPDATE `driving_log_timer` SET reset_cycle='1' WHERE timerid='$timerider'");
													break;
												}
											}
										}
									} else {
											$date = date('Y/m/d', time());
											$weekfromdate = date('Y/m/d', strtotime($enderdater) + (86400 * $daysadder));
											if (new DateTime($date) >= new DateTime($weekfromdate)) {
												$get_time_left2 = "SELECT * FROM `driving_log` WHERE `driverid` = '$driverid' AND `cycle` = '$cycler' ORDER BY `drivinglogid` DESC";
												$result12 = mysqli_query($dbc, $get_time_left2);
												while($rowx = mysqli_fetch_array($result12)) {
													$dlogider = $row1['drivinglogid'];
													$select_timersz = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$dlogider' ORDER BY timerid DESC";
													$result22 = mysqli_query($dbc, $select_timersz);
													while($row2z = mysqli_fetch_array($result22)) {
														$timerider = $row2z['timerid'];	
														$result_update_t = mysqli_query($dbc, "UPDATE `driving_log_timer` SET reset_cycle='1' WHERE timerid='$timerider'");
														break;
													}
												}
											}
									}
									$is_reset .='1';
									//break;
								} else {
									if($timer_counter == $num_rows2) {
										if($num_rows1 == $count_dl_date) {
											if($enderdater == '' || $enderdater == NULL) {
													$date = date('Y/m/d', time());
													$weekfromdate = date('Y/m/d', strtotime($starterdater) + (86400 * $daysadder));
													if (new DateTime($date) >= new DateTime($weekfromdate)) {
														$get_time_left2 = "SELECT * FROM `driving_log` WHERE `driverid` = '$driverid' AND `cycle` = '$cycler' ORDER BY `drivinglogid` DESC";
														$result12 = mysqli_query($dbc, $get_time_left2);
														while($rowx = mysqli_fetch_array($result12)) {
															$dlogider = $row1['drivinglogid'];
															$select_timersz = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$dlogider' ORDER BY timerid DESC";
															$result22 = mysqli_query($dbc, $select_timersz);
															while($row2z = mysqli_fetch_array($result22)) {
																$timerider = $row2z['timerid'];	
																$result_update_t = mysqli_query($dbc, "UPDATE `driving_log_timer` SET reset_cycle='1' WHERE timerid='$timerider'");
																break;
															}
														}
													}
											} else {
													$date = date('Y/m/d', time());
													$weekfromdate = date('Y/m/d', strtotime($enderdater) + (86400 * $daysadder));
													
													if (new DateTime($date) >= new DateTime($weekfromdate)) {
														$get_time_left2 = "SELECT * FROM `driving_log` WHERE `driverid` = '$driverid' AND `cycle` = '$cycler' ORDER BY `drivinglogid` DESC";
														$result12 = mysqli_query($dbc, $get_time_left2);
														while($rowx = mysqli_fetch_array($result12)) {
															$dlogider = $row1['drivinglogid'];
															$select_timersz = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$dlogider' ORDER BY timerid DESC";
															$result22 = mysqli_query($dbc, $select_timersz);
															while($row2z = mysqli_fetch_array($result22)) {
																$timerider = $row2z['timerid'];	
																$result_update_t = mysqli_query($dbc, "UPDATE `driving_log_timer` SET reset_cycle='1' WHERE timerid='$timerider'");
																break;
															}
														}
													}
											}
										}
									}
								}
							}
						}
					}
				}
			// END RESET CYCLE IF GREATER THAN OR EQUAL TO 7 DAYS (OR 14 DAYS FOR 14 DAY CYCLE) SINCE LAST CYCLE RESET}
?>