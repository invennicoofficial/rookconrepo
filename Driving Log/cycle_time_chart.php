	<?php 		
			$drivinglogid = $_GET['drivinglogid'];

			$current_time_hours = floor($time_seconds / 3600);
			$current_time_minutes = floor($time_seconds / 60 % 60);
			$current_time_seconds = floor($time_seconds % 60);
			
			// GET TIME LEFT for CYCLE TIME

			$col = "SELECT `reset_cycle` FROM driving_log_timer";
			$result = mysqli_query($dbc, $col);
			if (!$result){
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
				if(isset($_GET['showtime']) && $_GET['showtime'] == 'cycle' && (isset($_GET['timertype']) && ($_GET['timertype'] == 'on_duty_timer' || $_GET['timertype'] == 'driving_timer'))) { 
							$reverse_explode = array_reverse(explode(':',$_GET['timer_val']));
							$i = 0;
							$len = count($reverse_explode);
							foreach( $reverse_explode as $time ) {
								if ($i == 0) {
									$seconds = $time;
								} else if ($i == $len - 1) {
									if($hours > 0) {
										$hours += $time;
									}
								} else {
									$minutes = $time;
								}
								$i++;
							}
				 } else {
					$seconds = 0;
					$minutes = 0;
					$hours = 0;
				}
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
										if($hours > 0) {
											$hours += $time;
										}
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
										if($hours > 0) {
											$hours += $time;
										}
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

			if ($timer_name[0] == 'on_duty_timer' || $timer_name[0] == 'driving_timer') {
				$hours_left = sprintf("%02d", $hours_left - $current_time_hours);
				$minutes_left = sprintf("%02d", $minutes_left - $current_time_minutes);
				$seconds_left = sprintf("%02d", $seconds_left - $current_time_seconds);
			}
			if($cycler == 'Cycle 1(7 days)') {	
				if($hours_left == 69 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left = '70:00:00';
				} else if($hours_left < 0) {
					$time_left = '<span style="color:red">00:00:00</span>';
				} else {
					$time_left = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
			} else {
				if($hours_left == 119 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left = '120:00:00';
				} else if($hours_left < 0) {
					$time_left = '<span style="color:red">00:00:00</span>';
				} else {
					$time_left = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
			}
			// DONE GETTING CYCLE TIME
			// BEGIN SHIFT TIME
			$on_duty_time = '';
				if(isset($_GET['showtime']) && $_GET['showtime'] == 'cycle' && (isset($_GET['timertype']) && ($_GET['timertype'] == 'on_duty_timer' || $_GET['timertype'] == 'driving_timer'))) { 
							$reverse_explode = array_reverse(explode(':',$_GET['timer_val']));
							$i = 0;
							$len = count($reverse_explode);
							foreach( $reverse_explode as $time ) {
								if ($i == 0) {
									$seconds = $time;
								} else if ($i == $len - 1) {
									if($hours > 0) {
											$hours += $time;
										}
								} else {
									$minutes = $time;
								}
								$i++;
							}
				 } else {
					$seconds = 0;
					$minutes = 0;
					$hours = 0;
				}
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
										if($hours > 0) {
											$hours += $time;
										}
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
										if($hours > 0) {
											$hours += $time;
										}
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

			if($timer_name[0] == 'on_duty_timer') {
				$hours_left = sprintf("%02d", $hours_left - $current_time_hours);
				$minutes_left = sprintf("%02d", $minutes_left - $current_time_minutes);
				$seconds_left = sprintf("%02d", $seconds_left - $current_time_seconds);
			}
				if($hours_left == 13 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left_shift = '14:00:00';
					$time_left_shift_no_negtv = '14:00:00';
				} else if($hours_left < 0) {
					$time_left_shift = '<span style="color:red">00:00:00</span>';
					$time_left_shift_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				} else {
					$time_left_shift = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
					$time_left_shift_no_negtv = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
					
			// END GETTING SHIFT TIME
			// BEGIN DRIVING TIME
			$on_duty_time = '';
				if(isset($_GET['showtime']) && $_GET['showtime'] == 'cycle' && (isset($_GET['timertype']) && ($_GET['timertype'] == 'driving_timer'))) { 
							$reverse_explode = array_reverse(explode(':',$_GET['timer_val']));
							$i = 0;
							$len = count($reverse_explode);
							foreach( $reverse_explode as $time ) {
								if ($i == 0) {
									$seconds = $time;
								} else if ($i == $len - 1) {
									if($hours > 0) {
											$hours += $time;
										}
								} else {
									$minutes = $time;
								}
								$i++;
							}
				 } else {
					$seconds = 0;
					$minutes = 0;
					$hours = 0;
				}
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
										if($hours > 0) {
											$hours += $time;
										}
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

			if ($timer_name[0] == 'driving_timer') {
				$hours_left = sprintf("%02d", $hours_left - $current_time_hours);
				$minutes_left = sprintf("%02d", $minutes_left - $current_time_minutes);
				$seconds_left = sprintf("%02d", $seconds_left - $current_time_seconds);
			}
				if($hours_left == 12 && $minutes_left == 59 && $seconds_left == 60) {
					$time_left_drive = '13:00:00';
				} else if($hours_left < 0) {
					$time_left_drive = '<span style="color:red">00:00:00</span>';
				} else {
					$time_left_drive = ''.$hours_left.':'.$minutes_left.':'.$seconds_left.'';
				}
			// END GETTING Driving TIME
			?>
			<!-- <div class='hider' style='height:100%; position:absolute; top:10px; width:100%; color:black;'> -->

			<span style='position:absolute;top:60px;left:120px;padding:10px; z-index:99;width:100%;'>You have <?php echo $time_left; ?> of Cycle Time left.</span>
			    
			<?php if(floor($hour_circle*100) == 100) {
				?><span style='    position: absolute;
    z-index: 999;font-size: 25px;font-weight: bold;color: blue;top:60px;left:29px;'>100%</span>
	<?php
			} else {
			?>
			<span style='    position: absolute;
    z-index: 999;font-size: 30px;font-weight: bold;color: blue;top:59px;left:31px;'><?php if(floor($hour_circle*100) < 0) { echo "00"; } else { echo sprintf("%02d", floor($hour_circle*100)); } ?>%</span>
			<?php } ?>
			<!-- SHIFT TIME -->
			<span style='position:absolute; padding:10px; z-index:99;width:100%; top: 190px; left:120px;'>You have <?php echo $time_left_shift; ?> of Shift Time left.</span>
			<?php if(floor($hour_circle_shift*100) == 100) {
				?><span style='    position: absolute;
    z-index: 999;font-size: 25px;font-weight: bold;color: yellow;top:191px;left:29px;'>100%</span>
	<?php
			} else {
			?>
			<span style='    position: absolute;
    z-index: 999;font-size: 30px;font-weight: bold;color: yellow;top:190px;left:31px;'><?php if(floor($hour_circle_shift*100) < 0) { echo "00"; } else { echo sprintf("%02d", floor($hour_circle_shift*100)); } ?>%</span>
			<?php } ?>
			<!-- DRIVE TIME -->
			<span style='position:absolute; padding:10px; z-index:99;width:100%; top: 320px; left:120px;'>You have <?php echo $time_left_drive; ?> of Drive Time left.</span>
			<?php if(floor($hour_circle_drive*100) == 100) { 
				?><span style='    position: absolute;
    z-index: 999;font-size: 25px;font-weight: bold;color: red;top:322px;left:29px;'>100%</span>
	<?php } else { ?> 
			<span style='    position: absolute;
    z-index: 999;font-size: 30px;font-weight: bold;color: red;top:320px;left:31px;'><?php if(floor($hour_circle_drive*100) < 0) { echo "00"; } else { echo sprintf("%02d", floor($hour_circle_drive*100)); } ?>%</span>
			<?php } ?>
			
					<canvas id="myCanvas" width="300" height="500" style='position:relative; top:20px;'>
				Your browser does not support the HTML5 canvas tag.</canvas>

				<script>
					window.onload = function(){<!-- w w  w  .  ja  va 2 s . c o m-->
					var canvas = document.getElementById("myCanvas");
					var context = canvas.getContext("2d");
					//      x    y   rad sAng eAng antiC  line    fill
					drawArc(60,  60, 40, 0,   <?php echo $hour_circle; ?>*2, false, "blue", "lightgrey");
					drawArc(60, 190, 40, 0,   <?php echo $hour_circle_shift; ?>*2, false,  "yellow","lightgrey");
					drawArc(60, 320, 40, 0, <?php echo $hour_circle_drive; ?>*2, false, "red",  "lightgrey" );

					function drawArc(xPos, yPos,
					radius,
					startAngle, endAngle,
					anticlockwise,
					lineColor, fillColor)
					{
					//var startAngle = startAngle * (Math.PI/180);
					//var endAngle   = endAngle   * (Math.PI/180);

					var startAngle = startAngle * (Math.PI);
					var endAngle   = endAngle   * (Math.PI);

					var radius = radius;

					context.strokeStyle = lineColor;
					context.fillStyle   = "transparent";
					context.lineWidth   = 8;

					context.beginPath();
					context.arc(xPos, yPos,
					radius,
					startAngle, endAngle,
					anticlockwise);
					context.fill();
					context.stroke();
					}

					};
					</script>
					<!-- </div> -->
					
	<!-- // END TIME LEFT -->