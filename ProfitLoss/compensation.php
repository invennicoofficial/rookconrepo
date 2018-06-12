<?php include_once('../Reports/compensation_function.php');
$staff = mysqli_query($dbc, "SELECT `contactid`, `category`, `name`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status` > 0");
$contactlist = sort_contacts_array(mysqli_fetch_all($staff, MYSQLI_ASSOC));

for ($year = $startyear; $year <= $endyear; $year++) {
	$stat_holidays = [];
	foreach(mysqli_fetch_all(mysqli_query($dbc, "SELECT `date` FROM `holidays` WHERE `paid`=1 AND `deleted`=0")) as $stat_day) {
		$stat_holidays[] = $stat_day[0];
	}
	$stat_holidays = implode(',', $stat_holidays);
	//$stat_holidays = explode(',',get_config($dbc, 'stat_holiday'));
	$totals = [0,0,0,0,0,0,0,0,0,0,0,0]; ?>
	<table class="table table-bordered">
		<thead>
			<tr class="hidden-xs hidden-sm">
				<th>Staff Member</th>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					echo "<th style='width: 8em;'>".$dateObj->format('F')."</th>\n";
				} ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($contactlist as $contactid) {
				echo "<tr>\n<td data-title='Staff'>".get_contact($dbc, $contactid)."</td>\n";
				$startmonth = 0;
				$endmonth = 12;
				if($startyear == $year) {
					$startmonth = intval(explode('-', $search_start)[1]) - 1;
				}
				if($endyear == $year) {
					$endmonth = intval(explode('-', $search_end)[1]);
				}
				for($month = 0; $month < $startmonth; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>-</td>\n";
				}
				for($month = $startmonth; $month < $endmonth; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$date_part = $year."-".$dateObj->format('m');
					$starttimemonth = $date_part.'-01';
					if($startyear == $year && $month == $startmonth) {
						$starttimemonth = $search_start;
					}
					//$total_stat_holiday = $stat_days[$month];
					$endtimemonth = date('Y-m-t',strtotime($starttimemonth));
					if($endyear == $year && $month == $endmonth) {
						$endtimemonth = $search_end;
					}
					$amt = 0;
					$all_booking = 0;
					$vacation_pay_perc = 0;
					$vacation_pay = 0;
					$grand_stat_total = 0;
					$avg_per_day_stat = 0;
					if(strtotime($starttimemonth) <= strtotime('today')) {
						$starttime = $starttimemonth;
						$endtime = $endtimemonth;
						$invoicetype = "'New','Refund','Adjustment'";
						
						$table_style = $table_row_style = $grand_total_style = '';
						$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contacts`.contactid, `contacts`.scheduled_hours, `contacts`.`schedule_days`, `contacts`.category_contact, IFNULL(`base_pay`,'0*#*0') base_pay FROM contacts LEFT JOIN `compensation` ON `contacts`.`contactid`=`compensation`.`contactid` AND '$starttime' BETWEEN `compensation`.`start_date` AND `compensation`.`end_date` WHERE `contacts`.contactid='$contactid'"));
						$therapistid = $row['contactid'];
						$category_contact = $row['category_contact'];
						$schedule = $row['schedule_days'];
						$base_pay = explode('*#*',$row['base_pay']);
						
						
						//include ('../Reports/report_compensation_services.php');
						//include ('../Reports/report_compensation_preformance_logic.php');
						//include ('../Reports/report_compensation_inventory.php');

						foreach(explode(',',$stat_holidays) as $stat_day) {
							if($stat_day >= $starttime && $stat_day <= $endtime) {;
								$stat_day = strtotime($stat_day);
								$weekday = date('w',$stat_day);
								if($schedule == '' || in_array(date('w',$stat_day), explode(',',$schedule))) {
									$stat_start = date('Y-m-d',strtotime('-63 day',$stat_day));
									$stat_end = date('Y-m-d',strtotime('-1 day',$stat_day));
									include('../Reports/report_compensation_stat_holiday.php');
								}
							}
						}

						$vacation_pay = (($total_base_service+$total_base_inv)*$vacation_pay_perc)/100;
						$amt = $total_base_service+$total_base_inv+$avg_per_day_stat+$vacation_pay;
						$totals[$month] += $amt;
					}
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>$".number_format($amt, 2, '.', ',')."</td>\n";
				}
				for($month = $endmonth; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>-</td>\n";
				}
				echo "</tr>\n";
			} ?>
			<tr style="font-size: 1.25em; font-weight: bold;">
				<td>Monthly Total</td>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>$".number_format($totals[$month], 2, '.', ',')."</td>\n";
				} ?>
			</tr>
			<tr style="font-size: 1.5em; font-weight: bold;">
				<td colspan="10" style="border-right: none;">Total Compensation for <?php echo ($year == $startyear ? $starttime : $year.'-01-01').' to '.($year == $endyear ? $endtime : $year.'-12-31'); ?></td>
				<td data-title="Total" colspan="3" style="text-align: right; border-left: none;">$<?php echo number_format(array_sum($totals), 2, '.', ','); ?></td>
			</tr>
		</tbody>
	</table>
<?php } ?>