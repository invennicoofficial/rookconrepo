<?php

// DO NOT DELETE/EDIT THIS CODE UNLESS NECESSARY. This code has been created when Kelsey Nealon discovered that some of the timers were turning up as negative values after amendments were made. (2016-07-12) 

					$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid DESC";
					$result2 = mysqli_query($dbc, $select_timers);
					$num_rows2 = mysqli_num_rows($result2);
					if($num_rows2 > 0) {
						while($row2 = mysqli_fetch_array($result2)) {
							$getdata = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM driving_log_timer WHERE timerid='".$row2['timerid']."'"));
							
							if($getdata['off_duty_timer'] != '') {
								$timer_data_negative = $getdata['off_duty_timer'];
								$column_namer = 'off_duty_timer';
							}
							if($getdata['sleeper_berth_timer'] != '') {
								$timer_data_negative = $getdata['sleeper_berth_timer'];
								$column_namer = 'sleeper_berth_timer';
							}
							if($getdata['driving_timer'] != '') {
								$timer_data_negative = $getdata['driving_timer'];
								$column_namer = 'driving_timer';
							}
							if($getdata['on_duty_timer'] != '') {
								$timer_data_negative = $getdata['on_duty_timer'];
								$column_namer = 'on_duty_timer';
							}
							if($timer_data_negative < 0) {
								$reverse_explode = array_reverse(explode(':',$timer_data_negative));
								
								$i = 0;
								$len = count($reverse_explode);
								
								foreach( $reverse_explode as $time ) {	
									if ($i == 0) {
										$seconds = $time;
									} else if ($i == $len - 1) {
										$hours = $time;
									} else {
										$minutes = $time;
									}
									$i++;
								}
								$hours = $hours+12;
								$new_update_time = $hours.':'.$minutes.':'.$seconds;
								$result_update_t = mysqli_query($dbc, "UPDATE `driving_log_timer` SET $column_namer = '$new_update_time' WHERE timerid='".$row2['timerid']."'");
							}
						}
					}
?>