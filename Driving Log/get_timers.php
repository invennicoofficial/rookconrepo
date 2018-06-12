<?php
// GET TOTAL TIMER TIMES FOR IMAGE //
$select_timers = "SELECT * FROM driving_log_timer WHERE drivinglogid = '$drivinglogid' ORDER BY timerid DESC";
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
				// …
				$i++;

			}
		}
		if($row2['driving_timer'] !== '' && $row2['driving_timer'] !== NULL) {
			$reverse_explode = array_reverse(explode(':',$row2['driving_timer']));

			$i = 0;
			$len = count($reverse_explode);

			foreach( $reverse_explode as $time ) {


				if ($i == 0) {
					$_driveseconds += $time;
				} else if ($i == $len - 1) {
					$_drivehours += $time;
				} else {
					$_driveminutes += $time;
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
				} else if ($i == $len - 1) {
					$_offhours += $time;
				} else {
					$_offminutes += $time;
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
				} else if ($i == $len - 1) {
					$sleephours += $time;
				} else {
					$sleepminutes += $time;
				}
				// …
				$i++;

			}
		}

	}
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
	$total_t_time = '24:00:00';
} else {
	$total_t_time = $hourst.':'.$minutest.':'.$secondst;
}

$total_time_tabler = '<table cellpadding="3"><tr><td> </td></tr><tr><td>'.$off_h_time.'</td></tr><tr><td>'.$sleep_h_time.'</td></tr><tr><td>'.$drive_h_time.'</td></tr><tr><td>'.$on_h_time.'</td></tr>';
// <tr><td style="border-top:2px solid black;">'.$total_t_time.'</td></tr>
$total_time_tabler .= '</table>';


// END OF GETTING TOTAL TIMER TIMES FOR IMAGE //
?>