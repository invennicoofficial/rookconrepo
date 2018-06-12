<?php 
// THIS CODE ALLOWS YOU TO GET THE TOTAL SUM OF ALL OF YOUR TIMER'S FOR THE CURRENT DRIVING LOG. SEE BOTTOM OF PAGE FOR THE VARIABLE THAT DISPLAYS THE TOTAL TIMER SUM.
		$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid ASC";
		$seconds = 0;
		$minutes = 0;
		$hours = 0;
		
		$_offseconds = 0;
		$_offminutes = 0;
		$_offhours = 0;
		
		$_driveseconds = 0;
		$_driveminutes = 0;
		$_drivehours = 0;
		
		$sleepseconds = 0;
		$sleepminutes = 0;
		$sleephours = 0;
		if (isset($check_24_time)) {
			$reverse_explode = array_reverse(explode(':',$check_24_time));
				$i = 0;
				$len = count($reverse_explode);
				foreach( $reverse_explode as $time ) {
					if ($i == 0) {
						$total_seconds_audit = $time;
					} else if ($i == $len - 1) {
						$total_hours_audit = $time;
					} else {
						$total_minutes_audit = $time;
					}
					$i++;	
				}
		} else {
			$total_seconds_audit = 0;
			$total_hours_audit = 0;
			$total_minutes_audit = 0;
		}
		$total_check_24_hour_time = $total_hours_audit.':'.$total_minutes_audit.':'.$total_seconds_audit;
		
		$_driveseconds_audit = 0;
		$_driveminutes_audit = 0;
		$_drivehours_audit = 0;
		
		$hourst = 0;
		$secondst = 0;
		$minutest = 0;
		
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
							$total_seconds_audit += $time;
						} else if ($i == $len - 1) {
							$hours += $time;
							$total_hours_audit += $time;
						} else {
							$minutes += $time;
							$total_minutes_audit += $time;
						}
						// …
						$i++;
						
					}
				}
				if($row2['off_duty_timer'] !== '' && $row2['off_duty_timer'] !== NULL) {
					$reverse_explode = array_reverse(explode(':',$row2['off_duty_timer']));
					
					$i = 0;
					$len = count($reverse_explode);
					
					foreach( $reverse_explode as $time ) {
						
						
						if ($i == 0) {
							$_offseconds += $time;
							$total_seconds_audit += $time;
						} else if ($i == $len - 1) {
							$_offhours += $time;
							$total_hours_audit += $time;
						} else {
							$_offminutes += $time;
							$total_minutes_audit += $time;
						}
						// …
						$i++;
						
					}
				}
				
				if($row2['sleeper_berth_timer'] !== '' && $row2['sleeper_berth_timer'] !== NULL) {
					$reverse_explode = array_reverse(explode(':',$row2['sleeper_berth_timer']));
					
					$i = 0;
					$len = count($reverse_explode);
					
					foreach( $reverse_explode as $time ) {
						
						
						if ($i == 0) {
							$sleepseconds += $time;
							$total_seconds_audit += $time;
						} else if ($i == $len - 1) {
							$sleephours += $time;
							$total_hours_audit += $time;
						} else {
							$sleepminutes += $time;
							$total_minutes_audit += $time;
						}
						// …
						$i++;
						
					}
				}
				
				if($row2['driving_timer'] !== '' && $row2['driving_timer'] !== NULL) {
					$reverse_explode = array_reverse(explode(':',$row2['driving_timer']));
					
					$i = 0;
					$len = count($reverse_explode);
					
					foreach( $reverse_explode as $time ) {
						
						if($hourst >= 16) {
							if ($i == 0) {
								$_driveseconds += $time;
								$_driveseconds_audit += $time;
								$total_seconds_audit += $time;
							} else if ($i == $len - 1) {
								$_drivehours += $time;
								$_drivehours_audit += $time;
								$total_hours_audit += $time;
							} else {
								$_driveminutes += $time;
								$_driveminutes_audit += $time;
								$total_minutes_audit += $time;
							}
							$i++;
							
							$audit_minute_from_seconds = $total_seconds_audit/60;
							$minute_addt = floor($audit_minute_from_seconds);
							$seconds_leftt = $audit_minute_from_seconds - $minute_addt;
							$secondst = $seconds_leftt*60;
							if(strlen($secondst) < 2) {
								$secondst = '0'.$secondst; 
							}
							$minutest = $total_minutes_audit + $minute_addt;
							$hours_from_minutest = $minutest/60;
							$hour_addt = floor($hours_from_minutest);
							$minutes_leftt = $hours_from_minutest - $hour_addt;
							$minutest = $minutes_leftt*60;
							$hourst = $total_hours_audit+$hour_addt;
							if(strlen($hourst) < 2) {
								$hourst = '0'.$hourst; 
							}
							$total_time_222 = $hourst.':'.$minutest.':'.$secondst;
							
						} else {
							if ($i == 0) {
								$_driveseconds += $time;
								$total_seconds_audit += $time;
							} else if ($i == $len - 1) {
								$_drivehours += $time;
								$total_hours_audit += $time;
							} else {
								$_driveminutes += $time;
								$total_minutes_audit += $time;
							}
							$i++;
							
							$audit_minute_from_seconds = $total_seconds_audit/60;
							$minute_addt = floor($audit_minute_from_seconds);
							$seconds_leftt = $audit_minute_from_seconds - $minute_addt;
							$secondst = $seconds_leftt*60;
							if(strlen($secondst) < 2) {
								$secondst = '0'.$secondst; 
							}
							$minutest = $total_minutes_audit + $minute_addt;
							$hours_from_minutest = $minutest/60;
							$hour_addt = floor($hours_from_minutest);
							$minutes_leftt = $hours_from_minutest - $hour_addt;
							$minutest = $minutes_leftt*60;
							$hourst = $total_hours_audit+$hour_addt;
							if(strlen($hourst) < 2) {
								$hourst = '0'.$hourst; 
							}
							$total_time_222 = $hourst.':'.$minutest.':'.$secondst;
							//CHECK if Driver was driving before 16 hours total, but then passed over 16 hours while the timer was still running.
							if($hourst >= 16) {
								$hour_diff = $hourst - 16;
								$_drivehours_audit += $hour_diff;
								$_driveminutes_audit += $minutest;
							}
						}
					}
				} else {
					$audit_minute_from_seconds = $total_seconds_audit/60;
					$minute_addt = floor($audit_minute_from_seconds);
					$seconds_leftt = $audit_minute_from_seconds - $minute_addt;
					$secondst = $seconds_leftt*60;
					if(strlen($secondst) < 2) {
						$secondst = '0'.$secondst; 
					}
					$minutest = $total_minutes_audit + $minute_addt;
					$hours_from_minutest = $minutest/60;
					$hour_addt = floor($hours_from_minutest);
					$minutes_leftt = $hours_from_minutest - $hour_addt;
					$minutest = $minutes_leftt*60;
					$hourst = $total_hours_audit+$hour_addt;
					if(strlen($hourst) < 2) {
						$hourst = '0'.$hourst; 
					}
					// THE VARIABLE BELOW ($TOTAL_TIME_222) WILL DISPLAY THE TOTAL TIMER TIMES SUMMED TOGETHER
					$total_time_222 = $hourst.':'.$minutest.':'.$secondst;
				}
				$audit_minute_from_seconds2 = $_driveseconds_audit/60;
				$minute_addt2 = floor($audit_minute_from_seconds2);
				$seconds_leftt2 = $audit_minute_from_seconds2 - $minute_addt2;
				$secondst2 = $seconds_leftt2*60;
				if(strlen($secondst2) < 2) {
					$secondst2 = '0'.$secondst2; 
				}
				$minutest2 = $_driveminutes_audit + $minute_addt2;
				$hours_from_minutest2 = $minutest2/60;
				$hour_addt2 = floor($hours_from_minutest2);
				$minutes_leftt2 = $hours_from_minutest2 - $hour_addt2;
				$minutest2 = $minutes_leftt2*60;
				if(strlen($minutest2) < 2) {
					$minutest2 = '0'.$minutest2; 
				}
				$hourst2 = $_drivehours_audit+$hour_addt2;
				if(strlen($hourst2) < 2) {
					$hourst2 = '0'.$hourst2; 
				}
				$total_audit_drive_time = $hourst2.':'.$minutest2.':'.$secondst2;
			}
		}
		// Functionality to make sure Driving Log does not go over 24 hours
		if(($hourst == 24 && ($minutest > 0 || $secondst > 0)) || $hourst > 24) {
				
				$hoursover = $hourst - 24;
				$howmuchover = $hoursover.':'.$minutest.':'.$secondst;
				
				$reverse_explode = array_reverse(explode(':',$total_check_24_hour_time));
						$i = 0;
						$len = count($reverse_explode);
						foreach( $reverse_explode as $time ) {
							if ($i == 0) {
								$secondxs = $time;
							} else if ($i == $len - 1) {
								$hourxs = $time;
							} else {
								$minutexs = $time;
							}
							$i++;
						}
					$seconds_diff = $secondxs - $secondst;
					if ($seconds_diff < 0) {
						$minutexs - 1;
						$seconds_diff = 60 + $seconds_diff;
					}
					$minute_diff = $minutexs - $minutest;
					if ($minute_diff < 0) {
						$hourxs - 1;
						$minute_diff = 60 + $minute_diff;
					}
					$hour_diff = $hourxs - $hoursover;
					
					if(strlen($minute_diff) < 2) {
						$minute_diff = '0'.$minute_diff; 
					}
					if(strlen($hour_diff) < 2) {
						$hour_diff = '0'.$hour_diff; 
					}
					if(strlen($seconds_diff) < 2) {
						$seconds_diff = '0'.$seconds_diff; 
					}
				$under_24hour_time = $hour_diff .':'. $minute_diff.':'. $seconds_diff;
			}
		
		$minute_from_seconds1 = $sleepseconds/60;
		$minute_from_seconds2 = $_offseconds/60;
		$minute_from_seconds3 = $_driveseconds/60;
		$minute_from_seconds4 = $seconds/60;
		
		$minute_from_seconds_t = $minute_from_seconds1+$minute_from_seconds2+$minute_from_seconds3+$minute_from_seconds4;
		
		$minute_add1 = floor($minute_from_seconds1);
		$minute_add2 = floor($minute_from_seconds2);
		$minute_add3 = floor($minute_from_seconds3);
		$minute_add4 = floor($minute_from_seconds4);
		$minute_addt = floor($minute_from_seconds_t);
		
		$seconds_left1 = $minute_from_seconds1 - $minute_add1;
		$seconds_left2 = $minute_from_seconds2 - $minute_add2;
		$seconds_left3 = $minute_from_seconds3 - $minute_add3;
		$seconds_left4 = $minute_from_seconds4 - $minute_add4;
		$seconds_leftt = $minute_from_seconds_t - $minute_addt;
		$seconds1 = $seconds_left1*60;
		$seconds2 = $seconds_left2*60;
		$seconds3 = $seconds_left3*60;
		$seconds4 = $seconds_left4*60;
		$secondst = $seconds_leftt*60;
		
		if(strlen($seconds1) < 2) {
			$seconds1 = '0'.$seconds1; 
		}
		if(strlen($seconds2) < 2) {
			$seconds2 = '0'.$seconds2; 
		}
		if(strlen($seconds3) < 2) {
			$seconds3 = '0'.$seconds3; 
		}
		if(strlen($seconds4) < 2) {
			$seconds4 = '0'.$seconds4; 
		}
		if(strlen($secondst) < 2) {
			$secondst = '0'.$secondst; 
		}
		
		$minutes1 = $sleepminutes + $minute_add1;
		$minutes2 = $_offminutes + $minute_add2;
		$minutes3 = $_driveminutes + $minute_add3;
		$minutes4 = $minutes + $minute_add4;
		$minutest = $sleepminutes +$_offminutes +$_driveminutes +$minutes+$minute_addt;
		
		$hours_from_minutes1 = $minutes1/60;
		$hours_from_minutes2 = $minutes2/60;
		$hours_from_minutes3 = $minutes3/60;
		$hours_from_minutes4 = $minutes4/60;
		$hours_from_minutest = $minutest/60;
		
		$hour_add1 = floor($hours_from_minutes1);
		$hour_add2 = floor($hours_from_minutes2);
		$hour_add3 = floor($hours_from_minutes3);
		$hour_add4 = floor($hours_from_minutes4);
		$hour_addt = floor($hours_from_minutest);
		
		$minutes_left1 = $hours_from_minutes1 - $hour_add1;
		$minutes_left2 = $hours_from_minutes2 - $hour_add2;
		$minutes_left3 = $hours_from_minutes3 - $hour_add3;
		$minutes_left4 = $hours_from_minutes4 - $hour_add4;
		$minutes_leftt = $hours_from_minutest - $hour_addt;
		$minutes1 = $minutes_left1*60;
		$minutes2 = $minutes_left2*60;
		$minutes3 = $minutes_left3*60;
		$minutes4 = $minutes_left4*60;
		$minutest = $minutes_leftt*60;
		
		if(strlen($minutes1) < 2) {
			$minutes1 = '0'.$minutes1; 
		}
		if(strlen($minutes2) < 2) {
			$minutes2 = '0'.$minutes2; 
		}
		if(strlen($minutes3) < 2) {
			$minutes3 = '0'.$minutes3; 
		}
		if(strlen($minutes4) < 2) {
			$minutes4 = '0'.$minutes4; 
		}
		if(strlen($minutest) < 2) {
			$minutest = '0'.$minutest; 
		}
		
		$hours1 = $sleephours+$hour_add1;
		$hours2 = $_offhours+$hour_add2;
		$hours3 = $_drivehours+$hour_add3;
		$hours4 = $hours+$hour_add4;
		$hourst = $sleephours+$_offhours+$_drivehours+$hours+$hour_addt;
		
		if(strlen($hours1) < 2) {
			$hours1 = '0'.$hours1; 
		}
		if(strlen($hours2) < 2) {
			$hours2 = '0'.$hours2; 
		}
		if(strlen($hours3) < 2) {
			$hours3 = '0'.$hours3; 
		}
		if(strlen($hours4) < 2) {
			$hours4 = '0'.$hours4; 
		}
		if(strlen($hourst) < 2) {
			$hourst = '0'.$hourst; 
		}
		
		$sleep_h_time = $hours1.':'.$minutes1;
		$off_h_time = $hours2.':'.$minutes2;
		$drive_h_time = $hours3.':'.$minutes3;
		$on_h_time = $hours4.':'.$minutes4;
		
		if($hourst >= 24 && ($minutest >0 || $secondst > 0)) {
			$total_t_time = '24:00';
		} else {
			$total_t_time = $hourst.':'.$minutest;
		}
		
		// THE VARIABLE BELOW ($TOTAL_TIME_222) WILL DISPLAY THE TOTAL TIMER TIMES SUMMED TOGETHER
			//		$total_time_222
		?>