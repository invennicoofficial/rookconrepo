
<?php function get_hours_report($dbc, $staff, $search_start_date, $search_end_date, $search_position, $search_project, $search_ticket, $report_format = '', $hours_types) {
  $filter_query = '';
  if(!empty($search_project)) {
    $filter_query .= " AND `projectid` = '$search_project'";
  }
  if(!empty($search_ticket)) {
    $filter_query .= " AND `ticketid` = '$search_ticket'";
  }

	if($staff == '') {
    if(empty($search_project) && empty($search_ticket) && empty($search_position)) {
  		return '<h4>Please select a staff member.</h4>';
    } else {
      $filter_position_query = '';

      if(!empty($search_position)) {
        $tickets_sql = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT(`ticketid`) FROM `ticket_attached` WHERE `deleted` = 0 AND `position` = '$search_position' AND (`src_table` = 'Staff_tasks' OR `src_table` = 'Staff')"),MYSQLI_ASSOC);
        $tickets_position = [];
        foreach ($tickets_sql as $ticket_sql) {
          $tickets_position[] = $ticket_sql['ticketid'];
        }
        $tickets_position = "'".implode("'",$tickets_position)."'";
        $filter_position_query = " AND `ticketid` IN ($tickets_position)";
      }
      $staff_with_filters = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `staff` FROM `time_cards` WHERE `date` < '$search_start_date' AND `date` >= '$start_of_year' AND `deleted`=0 $filter_query $filter_position_query"),MYSQLI_ASSOC);
      foreach($staff_with_filters as $search_staff) {
        $staff_list[] = ['contactid'=>$search_staff['staff'],'first_name'=>'','last_name'=>get_contact($dbc, $search_staff['staff'])];
      }
    }
	// } else if($staff == 'ALL') {
	// 	$staff_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category`='Staff' AND `deleted`=0 AND `status` > 0 AND `contactid` IN (SELECT `staff` FROM `time_cards` WHERE `deleted`=0)"));
	// } else if($staff > 0) {
	// 	$staff_list = [['contactid'=>$staff,'first_name'=>'','last_name'=>get_contact($dbc,$staff)]];
	// }
  } else {
    $staff_list = [];
    foreach (explode(',',$staff) as $search_staff) {
      if($search_staff > 0) {
        $staff_list[] = ['contactid'=>$search_staff,'first_name'=>'','last_name'=>get_contact($dbc, $search_staff)];
      }
    }
  }
	include_once('../Calendar/calendar_functions_inc.php');
	$layout = get_config($dbc, 'timesheet_layout');
	$highlight = get_config($dbc, 'timesheet_highlight');
	$mg_highlight = get_config($dbc, 'timesheet_manager');
	$submit_mode = get_config($dbc, 'timesheet_submit_mode');
	$value_config = explode(',',get_field_config($dbc, 'time_cards'));
	$timesheet_comment_placeholder = get_config($dbc, 'timesheet_comment_placeholder');
	$timesheet_start_tile = get_config($dbc, 'timesheet_start_tile');
	$timesheet_rounding = get_config($dbc, 'timesheet_rounding');
	$timesheet_rounded_increment = get_config($_SERVER['DBC'], 'timesheet_rounded_increment') / 60;
	if(!in_array('reg_hrs',$value_config) && !in_array('direct_hrs',$value_config) && !in_array('payable_hrs',$value_config)) {
		$value_config = array_merge($value_config,['reg_hrs','extra_hrs','relief_hrs','sleep_hrs','sick_hrs','sick_used','stat_hrs','stat_used','vaca_hrs','vaca_used']);
	}
	$report = '';
	if($report_format == 'to_array') {
		$report_output = [];
	}
	$timesheet_time_format = get_config($dbc, 'timesheet_time_format');

	foreach($staff_list as $staff) {
    $search_staff = $staff['contactid'];

    $filter_position_query = '';
    if(!empty($search_position)) {
      $tickets_sql = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT(`ticketid`) FROM `ticket_attached` WHERE `deleted` = 0 AND `position` = '$search_position' AND (`src_table` = 'Staff_tasks' OR `src_table` = 'Staff') AND `item_id` = '$search_staff'"),MYSQLI_ASSOC);
      $tickets_position = [];
      foreach ($tickets_sql as $ticket_sql) {
        $tickets_position[] = $ticket_sql['ticketid'];
      }
      $tickets_position = "'".implode("'",$tickets_position)."'";
      $filter_position_query = " AND `ticketid` IN ($tickets_position)";
    }

		$report .= '<h3>'.$staff['first_name'].' '.$staff['last_name'].'</h3>';
		$schedule = mysqli_fetch_array(mysqli_query($dbc, "SELECT `scheduled_hours`, `schedule_days` FROM `contacts` WHERE `contactid`='$search_staff'"));
		$schedule_hrs = explode('*',$schedule['scheduled_hours']);
		$schedule_days = explode(',',$schedule['schedule_days']);
		$schedule_list = [0=>'---',1=>'---',2=>'---',3=>'---',4=>'---',5=>'---',6=>'---'];
		foreach($schedule_days as $key => $day_of_week) {
			$schedule_list[$day_of_week] = $schedule_hrs[$day_of_week];
		}

		$start_of_year = date('Y-01-01', strtotime($search_start_date));
		$sql = "SELECT IFNULL(SUM(IF(`type_of_time`='Sick Hrs.Taken',`total_hrs`,0)),0) SICK_HRS,
			IFNULL(SUM(IF(`type_of_time`='Stat Hrs.',`total_hrs`,0)),0) STAT_AVAIL,
			IFNULL(SUM(IF(`type_of_time`='Stat Hrs.Taken',`total_hrs`,0)),0) STAT_HRS,
			IFNULL(SUM(IF(`type_of_time`='Vac Hrs.',`total_hrs`,0)),0) VACA_AVAIL,
			IFNULL(SUM(IF(`type_of_time`='Vac Hrs.Taken',`total_hrs`,0)),0) VACA_HRS
			FROM `time_cards` WHERE `staff`='$search_staff' AND `date` < '$search_start_date' AND `date` >= '$start_of_year' AND `deleted`=0 $filter_query $filter_position_query";
		$year_to_date = mysqli_fetch_array(mysqli_query($dbc, $sql));

		$stat_hours = $year_to_date['STAT_AVAIL'];
		$stat_taken = $year_to_date['STAT_HRS'];
		$vacation_hours = $year_to_date['VACA_AVAIL'];
		$vacation_taken = $year_to_date['VACA_HRS'];
		$sick_taken = $year_to_date['SICK_HRS'];

		if($layout == '' || $layout == 'multi_line'):
			$report .= '<table class="table table-bordered" style="width:100%;">
				<tr class="hidden-xs hidden-sm">
					<td colspan="2">Balance Forward Y.T.D.</td>
					'.(in_array('ticketid',$value_config) ? '<td></td>' : '').'
					'.(in_array('total_tracked_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('start_time',$value_config) ? '<td></td>' : '').'
					'.(in_array('end_time',$value_config) ? '<td></td>' : '').'
					'.(in_array('planned_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('tracked_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('total_tracked_time',$value_config) ? '<td></td>' : '').'
					'.(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('extra_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('start_day_tile',$value_config) ? '<td></td>' : '').'
					'.(in_array('relief_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('sleep_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('training_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('sick_hrs',$value_config) ? '<td></td>' : '').'
					'.(in_array('sick_used',$value_config) ? '<td>'.$sick_taken.'</td>' : '').'
					'.(in_array('stat_hrs',$value_config) ? '<td>'.$stat_hours.'</td>' : '').'
					'.(in_array('stat_used',$value_config) ? '<td>'.$stat_taken.'</td>' : '').'
					'.(in_array('vaca_hrs',$value_config) ? '<td>'.$vacation_hours.'</td>' : '').'
					'.(in_array('vaca_used',$value_config) ? '<td>'.$vacation_taken.'</td>' : '').'
					'.(in_array('breaks',$value_config) ? '<td></td>' : '').'
					'.(in_array('view_ticket',$value_config) && $report_format != 'to_array' ? '<td></td>' : '').'
					'.(in_array('show_hours',$value_config) ? '<td></td>' : '').'
				</tr>
				<tr class="hidden-xs hidden-sm">
					<th><div>Date</div></th>
					'.(in_array('ticketid',$value_config) ? '<th><div>'.TICKET_NOUN.'</div></th>' : '').'
					'.(in_array('show_hours',$value_config) ? '<th><div>Hours</div></th>' : '').'
					'.(in_array('total_tracked_hrs',$value_config) ? '<th><div>Total Tracked<br />Hours</div></th>' : '').'
					'.(in_array('start_time',$value_config) ? '<th><div>Start<br />Time</div></th>' : '').'
					'.(in_array('end_time',$value_config) ? '<th><div>End<br />Time</div></th>' : '').'
					'.(in_array('planned_hrs',$value_config) ? '<th><div>Planned<br />Hours</div></th>' : '').'
					'.(in_array('tracked_hrs',$value_config) ? '<th><div>Tracked<br />Hours</div></th>' : '').'
					'.(in_array('total_tracked_time',$value_config) ? '<th><div>Total Tracked<br />Time</div></th>' : '').'
					'.(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<th><div>'.(in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular').'<br />Hours</div></th>' : '').'
					'.(in_array('start_day_tile',$value_config) ? '<th><div>'.$timesheet_start_tile.'</div></th>' : '').'
					'.(in_array('extra_hrs',$value_config) ? '<th><div>Extra<br />Hours</div></th>' : '').'
					'.(in_array('relief_hrs',$value_config) ? '<th><div>Relief<br />Hours</div></th>' : '').'
					'.(in_array('sleep_hrs',$value_config) ? '<th><div>Sleep<br />Hours</div></th>' : '').'
					'.(in_array('training_hrs',$value_config) ? '<th><div>Training<br />Hours</div></th>' : '').'
					'.(in_array('sick_hrs',$value_config) ? '<th><div>Sick Time<br />Adjustment</div></th>' : '').'
					'.(in_array('sick_used',$value_config) ? '<th><div>Sick Hrs.<br />Taken</div></th>' : '').'
					'.(in_array('stat_hrs',$value_config) ? '<th><div>Stat<br />Hours</div></th>' : '').'
					'.(in_array('stat_used',$value_config) ? '<th><div>Stat. Hrs.<br />Taken</div></th>' : '').'
					'.(in_array('vaca_hrs',$value_config) ? '<th><div>Vacation<br />Hours</div></th>' : '').'
					'.(in_array('vaca_used',$value_config) ? '<th><div>Vacation<br />Hrs. Taken</div></th>' : '').'
					'.(in_array('breaks',$value_config) ? '<th><div>Breaks</div></th>' : '').'
					'.(in_array('view_ticket',$value_config) && $report_format != 'to_array' ? '<th><div>'.TICKET_NOUN.'</div></th>' : '').'
					<th style="text-align:center; vertical-align:bottom;"><div>Comments</div></th>
				</tr>';

				if ( empty($search_site) ) {
					$sql = "SELECT `time_cards_id`, `date`, SUM(IF(`type_of_time` NOT IN ('Extra Hrs.','Relief Hrs.','Sleep Hrs.','Sick Time Adj.','Sick Hrs.Taken','Stat Hrs.','Stat Hrs.Taken','Vac Hrs.','Vac Hrs.Taken'),`total_hrs`,0)) REG_HRS,
					SUM(IF(`type_of_time`='Extra Hrs.',`total_hrs`,0)) EXTRA_HRS,
					SUM(IF(`type_of_time`='Relief Hrs.',`total_hrs`,0)) RELIEF_HRS, SUM(IF(`type_of_time`='Sleep Hrs.',`total_hrs`,0)) SLEEP_HRS,
					SUM(IF(`type_of_time`='Sick Time Adj.',`total_hrs`,0)) SICK_ADJ, SUM(IF(`type_of_time`='Sick Hrs.Taken',`total_hrs`,0)) SICK_HRS,
					SUM(IF(`type_of_time`='Stat Hrs.',`total_hrs`,0)) STAT_AVAIL, SUM(IF(`type_of_time`='Stat Hrs.Taken',`total_hrs`,0)) STAT_HRS,
					SUM(IF(`type_of_time`='Vac Hrs.',`total_hrs`,0)) VACA_AVAIL, SUM(IF(`type_of_time`='Vac Hrs.Taken',`total_hrs`,0)) VACA_HRS,
					SUM(`highlight`) HIGHLIGHT, SUM(`manager_highlight`) MANAGER,
					GROUP_CONCAT(`comment_box` SEPARATOR ', ') COMMENTS,
					SUM(`timer_tracked`) TRACKED_HRS, SUM(IF(`type_of_time`='Break',`total_hrs`,0)) BREAKS, `ticket_attached_id`, `ticketid`, `start_time`, `end_time` FROM `time_cards` WHERE `staff`='$search_staff' AND `date` >= '$search_start_date' AND `date` <= '$search_end_date' AND `deleted`=0 $filter_query $filter_position_query GROUP BY `date`";
				} else {
					$sql = "SELECT `time_cards_id`, `date`, SUM(IF(`type_of_time` NOT IN ('Extra Hrs.','Relief Hrs.','Sleep Hrs.','Sick Time Adj.','Sick Hrs.Taken','Stat Hrs.','Stat Hrs.Taken','Vac Hrs.','Vac Hrs.Taken'),`total_hrs`,0)) REG_HRS,
					SUM(IF(`type_of_time`='Extra Hrs.',`total_hrs`,0)) EXTRA_HRS,
					SUM(IF(`type_of_time`='Relief Hrs.',`total_hrs`,0)) RELIEF_HRS, SUM(IF(`type_of_time`='Sleep Hrs.',`total_hrs`,0)) SLEEP_HRS,
					SUM(IF(`type_of_time`='Sick Time Adj.',`total_hrs`,0)) SICK_ADJ, SUM(IF(`type_of_time`='Sick Hrs.Taken',`total_hrs`,0)) SICK_HRS,
					SUM(IF(`type_of_time`='Stat Hrs.',`total_hrs`,0)) STAT_AVAIL, SUM(IF(`type_of_time`='Stat Hrs.Taken',`total_hrs`,0)) STAT_HRS,
					SUM(IF(`type_of_time`='Vac Hrs.',`total_hrs`,0)) VACA_AVAIL, SUM(IF(`type_of_time`='Vac Hrs.Taken',`total_hrs`,0)) VACA_HRS,
					SUM(`highlight`) HIGHLIGHT, SUM(`manager_highlight`) MANAGER,
					GROUP_CONCAT(`comment_box` SEPARATOR ', ') COMMENTS,
					SUM(`timer_tracked`) TRACKED_HRS, SUM(IF(`type_of_time`='Break',`total_hrs`,0)) BREAKS, `ticket_attached_id`, `ticketid`, `start_time`, `end_time` FROM `time_cards` WHERE `staff`='$search_staff' AND `date` >= '$search_start_date' AND `date` <= '$search_end_date' AND IFNULL(`business`,'') LIKE '%$search_site%' AND `deleted`=0 $filter_query $filter_position_query GROUP BY `date`";
				}
				if($layout == 'multi_line') {
					$sql .= ", `time_cards_id`";
				}
				$sql .= " ORDER BY `date`, IFNULL(DATE_FORMAT(CONCAT_WS(' ',DATE(NOW()),`start_time`),'%H:%i'),STR_TO_DATE(`start_time`,'%l:%i %p')) ASC, IFNULL(DATE_FORMAT(CONCAT_WS(' ',DATE(NOW()),`end_time`),'%H:%i'),STR_TO_DATE(`end_time`,'%l:%i %p')) ASC";
				$result = mysqli_query($dbc, $sql);
				$date = $search_start_date;
				$row = mysqli_fetch_array($result);
				$total = ['REG'=>0,'EXTRA'=>0,'RELIEF'=>0,'SLEEP'=>0,'SICK_ADJ'=>0,'SICK'=>0,'STAT_AVAIL'=>0,'STAT'=>0,'VACA_AVAIL'=>0,'VACA'=>0,'TRACKED_HRS'=>0,'BREAKS'=>0,'TRAINING'=>0,'DRIVE'=>0];
				while(strtotime($date) <= strtotime($search_end_date)) {
					$attached_ticketid = 0;
					$timecardid = 0;
					$ticket_attached_id = 0;
					$hl_colour = '';
					$start_time = '';
					$end_time = '';
					if($row['date'] == $date) {
						foreach($hours_types as $hours_type) {
							if($row[$hours_type] > 0) {
								switch($timesheet_rounding) {
									case 'up':
										$row[$hours_type] = ceil($row[$hours_type] / $timesheet_rounded_increment) * $timesheet_rounded_increment;
										break;
									case 'down':
										$row[$hours_type] = floor($row[$hours_type] / $timesheet_rounded_increment) * $timesheet_rounded_increment;
										break;
									case 'nearest':
										$row[$hours_type] = round($row[$hours_type] / $timesheet_rounded_increment) * $timesheet_rounded_increment;
										break;
								}
							}
						}
						$hl_colour = ($row['MANAGER'] > 0 && $mg_highlight != '#000000' && $mg_highlight != '' ? 'background-color:'.$mg_highlight.';' : ($row['HIGHLIGHT'] > 0 && $highlight != '#000000' && $highlight != '' ? 'background-color:'.$highlight.';' : ''));
						$hrs = ['REG'=>$row['REG_HRS'],'EXTRA'=>$row['EXTRA_HRS'],'RELIEF'=>$row['RELIEF_HRS'],'SLEEP'=>$row['SLEEP_HRS'],'SICK_ADJ'=>$row['SICK_ADJ'],
							'SICK'=>$row['SICK_HRS'],'STAT_AVAIL'=>$row['STAT_AVAIL'],'STAT'=>$row['STAT_HRS'],'VACA_AVAIL'=>$row['VACA_AVAIL'],'VACA'=>$row['VACA_HRS'],'TRACKED_HRS'=>$row['TRACKED_HRS'],'BREAKS'=>$row['BREAKS']];
						$comments = html_entity_decode($row['COMMENTS']);
						if(empty(strip_tags($comments))) {
							$comments = $timesheet_comment_placeholder;
						}

						foreach($total as $key => $value) {
							$total[$key] += $hrs[$key];
						}
						$timecardid = $row['time_cards_id'];
						$ticket_attached_id = $row['ticket_attached_id'];
						$attached_ticketid = $row['ticketid'];
						$start_time = !empty($row['start_time']) ? date('h:i a', strtotime($row['start_time'])) : '';
						$end_time = !empty($row['end_time']) ? date('h:i a', strtotime($row['end_time'])) : '';

						if(in_array('training_hrs',$value_config) && $timecardid > 0) {
							if(is_training_hrs($dbc, $timecardid)) {
								$hrs['TRAINING'] = $hrs['REG'];
								$hrs['REG'] = 0;
								$total['REG'] -= $hrs['TRAINING'];
								$total['TRAINING'] += $hrs['TRAINING'];
							} else {
								$hrs['TRAINING'] = 0;
							}
						} else {
							$hrs['TRAINING'] = 0;
						}
						if(in_array('start_day_tile',$value_config) && !($row['ticketid'] > 0)) {
							$hrs['DRIVE'] = $hrs['REG'];
							$hrs['REG'] = 0;
							$total['REG'] -= $hrs['DRIVE'];
							$total['DRIVE'] += $hrs['DRIVE'];
						} else {
							$hrs['DRIVE'] = 0;
						}

						$row = mysqli_fetch_array($result);
					} else {
						$hrs = ['REG'=>0,'EXTRA'=>0,'RELIEF'=>0,'SLEEP'=>0,'SICK_ADJ'=>0,'SICK'=>0,'STAT_AVAIL'=>0,'STAT'=>0,'VACA_AVAIL'=>0,'VACA'=>0,'TRACKED_HRS'=>0,'BREAKS'=>0,'TRAINING'=>0,'DRIVE'=>0];
						$comments = '';
					}
					// $hours = mysqli_fetch_array(mysqli_query($dbc, "SELECT IF(`dayoff_type` != '',`dayoff_type`,CONCAT(`starttime`,' - ',`endtime`)) FROM `contacts_shifts` WHERE `deleted`=0 AND `contactid`='$search_staff' AND '$date' BETWEEN `startdate` AND `enddate` ORDER BY `startdate` DESC"))[0];
					$day_of_week = date('l', strtotime($date));
					$shifts = checkShiftIntervals($dbc, $search_staff, $day_of_week, $date, 'all');
					if(!empty($shifts)) {
						$hours = '';
						$hours_off = '';
						foreach ($shifts as $shift) {
							$hours .= $shift['starttime'].' - '.$shift['endtime'].'<br>';
							$hours_off = $shift['dayoff_type'] == '' ? $hours_off : $shift['dayoff_type'];

						}
						$hours = $hours_off == '' ? $hours : $hours_off;
					} else {
						$hours = $schedule_list[date('w',strtotime($date))];
					}
					$mod = '';
					if($date < $last_period) {
						$mod = 'readonly';
					}
					//Planned & Tracked Hours
					$ticket_labels = get_ticket_labels($dbc, $date, $search_staff, $layout, $timecardid);
					$planned_hrs = get_ticket_planned_hrs($dbc, $date, $search_staff, $layout, $timecardid);
					$tracked_hrs = get_ticket_tracked_hrs($dbc, $date, $search_staff, $layout, $timecardid);
					$total_tracked_time = get_ticket_total_tracked_time($dbc, $date, $search_staff, $layout, $timecardid);
					$report .= '<tr style="'.$hl_colour.'">'.
						'<td data-title="Date">'.$date.'</td>
						'.(in_array('ticketid',$value_config) ? '<td data-title="'.TICKET_NOUN.'">'.$ticket_labels.'</td>' : '').'
						'.(in_array('show_hours',$value_config) ? '<td data-title="Hours">'.$hours.'</td>' : '').'
						'.(in_array('total_tracked_hrs',$value_config) ? '<td data-title="Total Tracked Hours">'.(empty($hrs['TRACKED_HRS']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['TRACKED_HRS'],2) : time_decimal2time($hrs['TRACKED_HRS']))).'</td>' : '').'
						'.(in_array('start_time',$value_config) ? '<td data-title="Start Time" style="text-align:center">'.$start_time.'</td>' : '').'
						'.(in_array('end_time',$value_config) ? '<td data-title="End Time" style="text-align:center">'.$end_time.'</td>' : '').'
						'.(in_array('planned_hrs',$value_config) ? '<td data-title="Planned Hours">'.$planned_hrs.'</td>' : '').'
						'.(in_array('tracked_hrs',$value_config) ? '<td data-title="Tracked Hours">'.$tracked_hrs.'</td>' : '').'
						'.(in_array('total_tracked_time',$value_config) ? '<td data-title="Total Tracked Time">'.$total_tracked_time.'</td>' : '').'
						'.(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<td data-title="'.(in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular').' Hours">'.(empty($hrs['REG']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['REG'],2) : time_decimal2time($hrs['REG']))).'</td>' : '').'
						'.(in_array('start_day_tile',$value_config) ? '<td data-title="'.$timesheet_start_tile.'">'.(empty($hrs['DRIVE']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['DRIVE'],2) : time_decimal2time($hrs['DRIVE']))).'</td>' : '').'
						'.(in_array('extra_hrs',$value_config) ? '<td data-title="Extra Hours">'.(empty($hrs['EXTRA']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['EXTRA'],2) : time_decimal2time($hrs['EXTRA']))).'</td>' : '').'
						'.(in_array('relief_hrs',$value_config) ? '<td data-title="Relief Hours">'.(empty($hrs['RELIEF']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['RELIEF'],2) : time_decimal2time($hrs['RELIEF']))).'</td>' : '').'
						'.(in_array('sleep_hrs',$value_config) ? '<td data-title="Sleep Hours">'.(empty($hrs['SLEEP']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['SLEEP'],2) : time_decimal2time($hrs['SLEEP']))).'</td>' : '').'
						'.(in_array('training_hrs',$value_config) ? '<td data-title="Training Hours">'.(empty($hrs['TRAINING']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['TRAINING'],2) : time_decimal2time($hrs['TRAINING']))).'</td>' : '').'
						'.(in_array('sick_hrs',$value_config) ? '<td data-title="Sick Time Adjustment">'.(empty($hrs['SICK_ADJ']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['SICK_ADJ'],2) : time_decimal2time($hrs['SICK_ADJ']))).'</td>' : '').'
						'.(in_array('sick_used',$value_config) ? '<td data-title="Sick Hours Taken">'.(empty($hrs['SICK']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['SICK'],2) : time_decimal2time($hrs['SICK']))).'</td>' : '').'
						'.(in_array('stat_hrs',$value_config) ? '<td data-title="Stat Hours">'.(empty($hrs['STAT_AVAIL']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['STAT_AVAIL'],2) : time_decimal2time($hrs['STAT_AVAIL']))).'</td>' : '').'
						'.(in_array('stat_used',$value_config) ? '<td data-title="Stat Hours Taken">'.(empty($hrs['STAT']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['STAT'],2) : time_decimal2time($hrs['STAT']))).'</td>' : '').'
						'.(in_array('vaca_hrs',$value_config) ? '<td data-title="Vacation Hours">'.(empty($hrs['VACA_AVAIL']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['VACA_AVAIL'],2) : time_decimal2time($hrs['VACA_AVAIL']))).'</td>' : '').'
						'.(in_array('vaca_used',$value_config) ? '<td data-title="Vacation Hours Taken">'.(empty($hrs['VACA']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['VACA'],2) : time_decimal2time($hrs['VACA']))).'</td>' : '').'
						'.(in_array('breaks',$value_config) ? '<td data-title="Breaks">'.(empty($hrs['BREAKS']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['BREAKS'],2) : time_decimal2time($hrs['BREAKS']))).'</td>' : '').'
						'.(in_array('view_ticket',$value_config) && $report_format != 'to_array' ? '<td data-title="'.TICKET_NOUN.'" style="text-align:center">'.(!empty($attached_ticketid) ? '<a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Ticket/edit_tickets.php?edit='.$attached_ticketid.'&calendar_view=true\',\'auto\',false,true, $(\'#timesheet_div\').outerHeight()); return false;" data-ticketid="'.$attached_ticketid.'" class="view_ticket" '.($attached_ticketid > 0 ? '' : 'style="display:none;"').'>View</a>' : '').'</td>' : '').'
						<td data-title="Comments"><span>'.$comments.'</span></td>'.
					'</tr>';
					if($layout != 'multi_line' || $date != $row['date']) {
						$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
					}
				}
				$colspan = 2;
				if(in_array('ticketid',$value_config)) {
					$colspan++;
				}
				if(in_array('planned_hrs',$value_config)) {
					$colspan++;
				}
				if(in_array('tracked_hrs',$value_config)) {
					$colspan++;
				}
				if(in_array('total_tracked_time',$value_config)) {
					$colspan++;
				}
				if(in_array('start_time',$value_config)) {
					$colspan++;
				}
				if(in_array('end_time',$value_config)) {
					$colspan++;
				}
				if(!in_array('show_hours',$value_config)) {
					$colspan--;
				}
				$report .= '<tr>
					<td data-title="" colspan="'.$colspan.'">Totals</td>
					'.(in_array('total_tracked_hrs',$value_config) ? '<td data-title="Total Tracked Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['TRACKED_HRS'],2) : time_decimal2time($total['TRACKED_HRS'])).'</td>' : '').'
					'.(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<td data-title="'.(in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular').' Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['REG'],2) : time_decimal2time($total['REG'])).'</td>' : '').'
					'.(in_array('start_day_tile',$value_config) ? '<td data-title="'.$timesheet_start_tile.'">'.($timesheet_time_format == 'decimal' ? number_format($total['DRIVE'],2) : time_decimal2time($total['DRIVE'])).'</td>' : '').'
					'.(in_array('extra_hrs',$value_config) ? '<td data-title="Extra Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['EXTRA'],2) : time_decimal2time($total['EXTRA'])).'</td>' : '').'
					'.(in_array('relief_hrs',$value_config) ? '<td data-title="Relief Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['RELIEF'],2) : time_decimal2time($total['RELIEF'])).'</td>' : '').'
					'.(in_array('sleep_hrs',$value_config) ? '<td data-title="Sleep Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['SLEEP'],2) : time_decimal2time($total['SLEEP'])).'</td>' : '').'
					'.(in_array('training_hrs',$value_config) ? '<td data-title="Training Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['TRAINING'],2) : time_decimal2time($total['TRAINING'])).'</td>' : '').'
					'.(in_array('sick_hrs',$value_config) ? '<td data-title="Sick Time Adjustment">'.($timesheet_time_format == 'decimal' ? number_format($total['SICK_ADJ'],2) : time_decimal2time($total['SICK_ADJ'])).'</td>' : '').'
					'.(in_array('sick_used',$value_config) ? '<td data-title="Sick Hours Taken">'.($timesheet_time_format == 'decimal' ? number_format($total['SICK'],2) : time_decimal2time($total['SICK'])).'</td>' : '').'
					'.(in_array('stat_hrs',$value_config) ? '<td data-title="Stat Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['STAT_AVAIL'],2) : time_decimal2time($total['STAT_AVAIL'])).'</td>' : '').'
					'.(in_array('stat_used',$value_config) ? '<td data-title="Stat Hours Taken">'.($timesheet_time_format == 'decimal' ? number_format($total['STAT'],2) : time_decimal2time($total['STAT'])).'</td>' : '').'
					'.(in_array('vaca_hrs',$value_config) ? '<td data-title="Vacation Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['VACA_AVAIL'],2) : time_decimal2time($total['VACA_AVAIL'])).'</td>' : '').'
					'.(in_array('vaca_used',$value_config) ? '<td data-title="Vacation Hours Taken">'.($timesheet_time_format == 'decimal' ? number_format($total['VACA'],2) : time_decimal2time($total['VACA'])).'</td>' : '').'
					'.(in_array('breaks',$value_config) ? '<td data-title="Breaks">'.($timesheet_time_format == 'decimal' ? number_format($total['BREAKS'],2) : time_decimal2time($total['BREAKS'])).'</td>' : '').'
					'.(in_array('view_ticket',$value_config) ? '<td data-title=""></td>' : '').'
					<td data-title=""></td>
				</tr>';
				$report .= '<tr>
					<td colspan="'.$colspan.'">Year-to-date Totals</td>
					'.(in_array('total_tracked_hrs',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('start_day_tile',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('extra_hrs',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('relief_hrs',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('sleep_hrs',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('training_hrs',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('sick_hrs',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('sick_hrs',$value_config) ? '<td data-title="Sick Hours Taken">'.($timesheet_time_format == 'decimal' ? number_format($total['SICK']+$sick_taken,2) : time_decimal2time($total['SICK']+$sick_taken)).'</td>' : '').'
					'.(in_array('stat_hrs',$value_config) ? '<td data-title="Stat Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['STAT_AVAIL']+$stat_hours,2) : time_decimal2time($total['STAT_AVAIL']+$stat_hours)).'</td>' : '').'
					'.(in_array('stat_used',$value_config) ? '<td data-title="Stat Hours Taken">'.($timesheet_time_format == 'decimal' ? number_format($total['STAT']+$stat_taken,2) : time_decimal2time($total['STAT']+$stat_taken)).'</td>' : '').'
					'.(in_array('vaca_hrs',$value_config) ? '<td data-title="Vacation Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['VACA_AVAIL']+$vacation_hours,2) : time_decimal2time($total['VACA_AVAIL']+$vacation_hours)).'</td>' : '').'
					'.(in_array('vaca_used',$value_config) ? '<td data-title="Vacation Hours Taken">'.($timesheet_time_format == 'decimal' ? number_format($total['VACA']+$vacation_taken,2) : time_decimal2time($total['VACA']+$vacation_taken)).'</td>' : '').'
					'.(in_array('breaks',$value_config) ? '<td data-title=""></td>' : '').'
					'.(in_array('view_ticket',$value_config) ? '<td data-title=""></td>' : '').'
					<td></td>
				</tr>
			</table>';

		elseif($layout == 'position_dropdown' || $layout == 'ticket_task'):
			$total_colspan = 2;
			$report .= '<table class="table table-bordered">
					<tr class="hidden-xs hidden-sm">
						<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:7em;"' : '').'><div>Date</div></th>
						'.(in_array("schedule",$value_config) ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:9em;"' : '').'><div>Schedule</div></th>' : '').'
						'.(in_array("scheduled",$value_config) ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:10em;"' : '').'><div>Scheduled Hours</div></th>' : '').'
						'.(in_array("start_time",$value_config) || in_array("start_time_editable",$value_config) ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:10em;"' : '').'><div>Start Time</div></th>' : '').'
						'.(in_array("end_time",$value_config) || in_array("end_time_editable",$value_config) ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:10em;"' : '').'><div>End Time</div></th>' : '').'
						'.(in_array("start_day_tile",$value_config) ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:10em;"' : '').'><div>'.$timesheet_start_tile.'</div></th>' : '');
			if($layout == 'ticket_task') {
				$report .= '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:12em;"' : '').'><div>'.TICKET_NOUN.'</div></th>';
				$report .= '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:12em;"' : '').'><div>Task</div></th>';
			} else {
				$report .= '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:12em;"' : '').'><div>Position</div></th>';
			}
			$report .= (in_array("total_tracked_hrs",$value_config) ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:6em;"' : '').'><div>Time Tracked</div></th>' : '').'
						<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:6em;"' : '').'><div>Hours</div></th>
						'.(in_array("vaca_hrs",$value_config) ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:10em;"' : '').'><div>Vacation Hours</div></th>' : '').'
						'.(in_array("view_ticket",$value_config) && $report_format != 'to_array' ? '<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom; width:10em;"' : '').'><div>'.TICKET_NOUN.'</div></th>' : '').'
						<th '.($report_format != 'to_array' ? 'style="text-align:center; vertical-align:bottom;"' : '').'><div>Comments</div></th>
					</tr>';
					$position_list = $_SERVER['DBC']->query("SELECT `position` FROM (SELECT `name` `position` FROM `positions` WHERE `deleted`=0 UNION SELECT `type_of_time` `position` FROM `time_cards` WHERE `deleted`=0) `list` WHERE IFNULL(`position`,'') != '' GROUP BY `position` ORDER BY `position`")->fetch_all();
					$total = 0;
					$total_vac = 0;
					$limits = "AND `staff`='$search_staff'";
					if($search_site > 0) {
						$limits .= " AND `business` LIKE '%$search_site%'";
					}
					$result = get_time_sheet($search_start_date, $search_end_date, $limits, ', `staff`, `date`, `time_cards_id`');
					$date = $search_start_date;
					$i = 0;
					while(strtotime($date) <= strtotime($search_end_date)) {
						$timecardid = 0;
						$driving_time = '';
						$hl_colour = '';
						if($result[$i]['date'] == $date) {
							$row = $result[$i++];
							$hl_colour = ($row['MANAGER'] > 0 && $mg_highlight != '#000000' && $mg_highlight != '' ? 'background-color:'.$mg_highlight.';' : ($row['HIGHLIGHT'] > 0 && $highlight != '#000000' && $highlight != '' ? 'background-color:'.$highlight.';' : ''));
							$comments = '';
							if(in_array('project',$value_config)) {
								foreach(explode(',',$row['PROJECTS']) as $projectid) {
									if($projectid > 0) {
										$comments .= get_project_label($dbc, mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid`='$projectid'"))).'<br />';
									}
								}
							}
							if(in_array('search_client',$value_config)) {
								foreach(explode(',',$row['CLIENTS']) as $clientid) {
									if($clientid > 0) {
										$comments .= get_contact($dbc, $clientid).'<br />';
									}
								}
							}
							$comments .= html_entity_decode($row['COMMENTS']);
							if(empty(strip_tags($comments))) {
								$comments = $timesheet_comment_placeholder;
							}
							if($row['type_of_time'] == 'Vac Hrs.') {
								$total_vac += $row['hours'];
							} else {
								$total += $row['hours'];
							}
							$timecardid = $row['time_cards_id'];
							if(empty($row['ticketid'])) {
								$driving_time = 'Driving Time';
							}
							$show_separator = 0;
						} else {
							$row = '';
							$comments = '';
							$show_separator = 1;
						}
						$day_of_week = date('l', strtotime($date));
						$shifts = checkShiftIntervals($dbc, $search_staff, $day_of_week, $date, 'all');
						if(!empty($shifts)) {
							$hours = '';
							$hours_off = '';
							foreach ($shifts as $shift) {
								$hours .= $shift['starttime'].' - '.$shift['endtime'].'<br>';
								$hours_off = $shift['dayoff_type'] == '' ? $hours_off : $shift['dayoff_type'];

							}
							$hours = $hours_off == '' ? $hours : $hours_off;
						} else {
							$hours = $schedule_list[date('w',strtotime($date))];
						}
						$mod = '';
						if($date < $last_period) {
							$mod = 'readonly';
						}
						$report .= '<tr style="'.$hl_colour.'" class="'.($show_separator==1 ? 'theme-color-border-bottom' : '').'">
							<td data-title="Date">'.$date.'</td>
							'.(in_array('schedule',$value_config) ? '<td data-title="Schedule">'.$hours.'</td>' : '').'
							'.(in_array('scheduled',$value_config) ? '<td data-title="Scheduled Hours"></td>' : '').'
							'.(in_array('start_time',$value_config) || in_array("start_time_editable",$value_config) ? '<td data-title="Start Time">'.$row['start_time'].'</td>' : '').'
							'.(in_array('end_time',$value_config) || in_array("end_time_editable",$value_config) ? '<td data-title="End Time">'.$row['end_time'].'</td>' : '').'
							'.(in_array('start_day_tile',$value_config) ? '<td data-title="'.$timesheet_start_tile.'">'.$driving_time.'</td>' : '');
						if($layout == 'ticket_task') {
							$report .= '<td data-title="'.TICKET_NOUN.'">'.get_ticket_label($dbc, mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid` = '".$row['ticketid']."'"))).'</td>';
							$report .= '<td data-title="Task">'.$row['type_of_time'].'</td>';
						} else {
							$report .= '<td data-title="Position">'.$row['type_of_time'].'</td>';
						}
						$report .= (in_array('total_tracked_hrs',$value_config) ? '<td data-title="Time Tracked">'.$row['timer'].'</td>' : '').'
							<td data-title="Hours">'.(empty($row['hours']) || $row['type_of_time'] == 'Vac Hrs.' ? '' : ($timesheet_time_format == 'decimal' ? number_format($row['hours'],2) : time_decimal2time($row['hours']))).'</td>
							'.(in_array('vaca_hrs',$value_config) ? '<td data-title="Vacation Hours">'.(empty($row['hours']) || $row['type_of_time'] != 'Vac Hrs.' ? '' : ($timesheet_time_format == 'decimal' ? number_format($row['hours'],2) : time_decimal2time($row['hours']))).'</td>' : '').'
							'.(in_array('view_ticket',$value_config) && $report_format != 'to_array' ? '<td data-title="'.TICKET_NOUN.'"><a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Ticket/edit_tickets.php?edit='.$row['ticketid'].'&calendar_view=true\',\'auto\',false,true, $(\'#timesheet_div\').outerHeight()); return false;" data-ticketid="'.$row['ticketid'].'" class="view_ticket" '.($row['ticketid'] > 0 ? '' : 'style="display:none;"').'>View</a></td>' : '').'
							<td data-title="Comments"><span>'.$comments.'</span></td>
						</tr>';
						if($date != $row['date']) {
							$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
						}
					}
					$report .= '<tr>
						<td data-title="" colspan="'.($layout == 'ticket_task' ? '3' : '2').'">Totals</td>
						'.(in_array('schedule',$value_config) ? '<td></td>' : '').'
						'.(in_array('scheduled',$value_config) ? '<td></td>' : '').'
						'.(in_array('start_time',$value_config) || in_array("start_time_editable",$value_config) ? '<td></td>' : '').'
						'.(in_array('end_time',$value_config) || in_array("end_time_editable",$value_config) ? '<td></td>' : '').'
						'.(in_array('start_day_tile',$value_config) ? '<td></td>' : '').'
						'.(in_array('total_tracked_hrs',$value_config) ? '<td></td>' : '').'
						<td data-title="Total Hours">'.($timesheet_time_format == 'decimal' ? number_format($total,2) : time_decimal2time($total)).'</td>
						'.(in_array('vaca_hrs',$value_config) ? '<td>'.($timesheet_time_format == 'decimal' ? number_format($total_vac,2) : time_decimal2time($total_vac)).'</td>' : '').'
						'.(in_array('view_ticket',$value_config) ? '<td></td>' : '').'
						<td></td>
					</tr>
				</table>';

			$tb_field = $value['config_field'];
		elseif($layout == 'table_add_button'):
			$report .= '<table class="table table-bordered">
					<tr class="hidden-sm hidden-xs">
						<th>Date</th>
						<th>Staff</th>
						<th>Hours</th>
						<th>Type</th>
					</tr>';
					$time_cards = mysqli_query($dbc, "SELECT * FROM `time_cards` WHERE `staff`='$search_staff' AND `date` >= '$search_start_date' AND `date` <= '$search_end_date' AND `business` LIKE '%$search_site%' AND `deleted`=0 $filter_query $filter_position_query");
					while($time_card = mysqli_fetch_assoc($time_cards)) {
						$report .= '<tr class="hidden-sm hidden-xs">
							<td data-title="Date">'.$time_card['date'].'</td>
							<td data-title="Staff">'. get_contact($dbc, $time_card['staff']).'</td>
							<td data-title="Hours">'.$time_card['total_hours'].'</td>
							<td data-title="Type">'.$time_card['type_of_time'].'</td>
						</tr>';
					}
				$report .= '</table>';
		elseif($layout == 'rate_card' || $layout == 'rate_card_tickets'):/*
			$desc_inc = 0;
			for($date = $search_start_date; strtotime($date) <= strtotime($search_end_date); $date = date("Y-m-d", strtotime("+1 day", strtotime($date)))) {
				if($layout == 'rate_card_tickets') {
					$ticket_sql = "SELECT `tickets`.*, `osbn`.`item_id` `osbn` FROM `tickets` LEFT JOIN `ticket_attached` `osbn` ON `tickets`.`ticketid`=`osbn`.`ticketid` AND `osbn`.`src_table`='Staff' AND `osbn`.`deleted`=0 AND `osbn`.`position`='Team Lead' WHERE `tickets`.`ticketid` IN (SELECT `ticketid` FROM `time_cards` WHERE `deleted`=0 AND `staff`='$search_staff' AND `date`='$date' UNION SELECT `ticketid` FROM `tickets` WHERE CONCAT(',',`contactid`,',') LIKE '%,$search_staff,%' AND (`to_do_date`='$date' OR '$date' BETWEEN `to_do_date` AND `to_do_end_date` OR `internal_qa_date`='$date' OR `deliverable_date`='$date') AND `deleted`=0)";
				} else {
					$ticket_sql = "SELECT 0 `ticketid`";
				}
				$ticket_query = mysqli_query($dbc, $ticket_sql);
				$ticket = mysqli_fetch_assoc($ticket_query);
				do {
					$daily_total = 0;
					$cat_total = 0;
					$work_hours_sql = "SELECT IFNULL(SUM(`total_hrs`),0) hours, `category`, `work_desc`, `hourly`, `daily`, `color_code`, `location`, `customer`, `day`, `travel_range_1`, `travel_range_5`, `travel_range_1_5`, `comment_box` FROM `staff_rate_table` staff LEFT JOIN `time_cards` sheet ON CONCAT(',',staff.`staff_id`,',') LIKE CONCAT('%,',sheet.`staff`,',%') AND sheet.`type_of_time`=staff.`work_desc` AND sheet.`date`='$date' AND sheet.`deleted`=0 WHERE CONCAT(',',staff.`staff_id`,',') LIKE '%,$search_staff,%' AND staff.`deleted`=0 GROUP BY `category`, `work_desc` ORDER BY `category`, `sort_order`, `work_desc`, `hourly`";
					$work_result = mysqli_query($dbc, $work_hours_sql);
					$location = mysqli_fetch_array($work_result)['location'];
					$customer = mysqli_fetch_array($work_result)['customer'];
					$work_result = mysqli_query($dbc, $work_hours_sql);
					$day_of_week = date('l', strtotime($date));
					$shifts = checkShiftIntervals($dbc, $search_staff, $day_of_week, $date);
					if(!empty($shifts)) {
						$shift = '';
						$hours_off = '';
						foreach ($shifts as $shift_detail) {
							$shift .= $shift_detail['starttime'].' - '.$shift_detail['endtime'].'<br>';
							$hours_off = $shift['dayoff_type'] == '' ? $hours_off : $shift['dayoff_type'];

						}
						$shift = $hours_off == '' ? $shift : $hours_off;
					} else {
						$shift = $schedule_list[date('w',strtotime($date))];
					}
					$report .= "<div class='form-group' style='border:solid black 1px; display:inline-block; margin:1em; width:30em;'>";
					$report .= "<div style='border:solid black 1px; padding:0.25em; width: 30em;'><div style='display:inline-block; width:12em;'>Date:</div><div style='display:inline-block; width:16em;'>$date</div>";
					if($shift != '') {
						$report .= "<div style='display:inline-block; width:12em;'>Hours:</div><div style='display:inline-block; width:16em;'>$shift</div>";
					}
					if($ticket['ticketid'] > 0) {
						$report .= "<div style='display:inline-block; width:12em;'>".TICKET_NOUN.":</div><div style='display:inline-block; width:16em;'>".get_ticket_label($dbc, $ticket).($ticket['osbn'] > 0 ? "<br />OSBN: ".get_contact($dbc, $ticket['osbn']) : '')."</div>";
					}
					$report .= "<div style='display:inline-block; width:11.7em;'>Customer:</div>";
					?>
					<div style='display:inline-block; width:16em;'>
						<input type='hidden' name='customer_date[]' value='<?php echo $date; ?>'>
						<select data-placeholder="Choose a Customer..." name="customer[]" class="chosen-select-deselect form-control">
							<option value=""></option>
							<?php
								$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, name, first_name, last_name FROM contacts WHERE category='Business' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
								foreach($query as $id) {
									$selected = '';
									$selected = $id == $customer ? 'selected = "selected"' : '';
									echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id,'name').'</option>';
								}
							?>
						</select>
					</div>
					<?php
					echo "<div style='display:inline-block; width:12em;'>Location:</div><div style='display:inline-block; width:16em;'><input type='hidden' name='location_date[]' value='$date'><input type='text' name='location[]' class='form-control'  value='$location'></div></div>";
					$category = '';
					while($hours = mysqli_fetch_array($work_result)) {
						if($hours['category'] != $category) {
							if($category != '') {
								echo "<div style='display:inline-block; width:12em;'>$category Total</div><div style='display:inline-block; text-align:right; width:16em;' class='cat-total'>$".number_format($cat_total,2)."</div>";
								echo "<div style='display:inline-block;  vertical-align:top; width:12em;'><img class='inline-img smaller' data-target='#desc_".$desc_inc."' onclick='expandArea(this);' src='../img/icons/ROOK-edit-icon.png'>$category Description</div><input type='hidden' name='comment_date[]' value='$date'><input type='hidden' name='comment_cat[]' value='$category'><input type='hidden' name='cat_comment[]' id='desc_".$desc_inc."' value='".$comment_box."'><div class='comment_box_text' style='display:inline-block; width:16em;'>".$comment_box;
								echo "<p style='display:none;'><a class='pull-right' href='' data-target='#desc_".$desc_inc."' onclick='expandArea(this)'>Edit Description</a></p></div></div>";
								$desc_inc++;
							}
							$category = $hours['category'];
							$cat_total = 0;
							echo "<div style='border:solid black 1px; padding:0.25em; width: 30em;' class='category-block'><div style='display:inline-block; width:12em;'>$category</div>
							<div style='display:inline-block; text-align:center; width:4em;'>Day</div><div style='display:inline-block; text-align:center; width:4em;'>Hours</div><div style='display:inline-block; text-align:center; width:4em;'>Rate</div><div style='display:inline-block; text-align:center; width:4em;'>Total</div>";
						}
						echo "<div style='background-color:".$hours['color_code'].";'>";
						echo "<div style='display:inline-block; width:12em;'>".$hours['work_desc']."</div><div style='display:inline-block; width:4em;'>";
						if ($hours['daily'] > 0) {
							$checked = '';
							if ($hours['day'] == 1) {
								$checked = 'checked';
							}
							echo "<input type='hidden' name='day_cat[]' value='$category'><input type='hidden' name='day_type[]' value='".$hours['work_desc']."'><input type='hidden' name='day_date[]' value='$date'><input type='hidden' name='day[]' value='1'>";
							echo "<input type='checkbox' data-rate='".$hours['daily']."' ".$checked." style='margin-left: 2em;' name='day_checkbox[]' value='1'></div>";
							echo "<div style='display:inline-block; text-align:right; width:4em;'><input type='text' name='' data-rate='".$hours['hourly']."' class='form-control' disabled value=''></div>";
							echo "<div style='display:inline-block; text-align:right; width:4em;' class='row-rate'>$".$hours['daily']."</div>";
							echo "<div style='display:inline-block; text-align:right; width:4em;' class='row-total'>$";
							if ($checked == 'checked') {
								echo $hours['daily'];
							} else {
								echo '0.00';
							}
							echo "</div></div>";
							if ($checked != '') {
								$cat_total += $hours['daily'];
								$daily_total += $hours['daily'];
							}
						} else {
							echo "<input type='hidden' name='hours_cat[]' value='$category'><input type='hidden' name='hours_type[]' value='".$hours['work_desc']."'><input type='hidden' name='hours_date[]' value='$date'>";
							echo "</div>";
							$hourly_rate = $hours['hourly'];
							if ($hours['travel_range_1'] > 0 || $hours['travel_range_5'] > 0 || $hours['travel_range_1_5'] > 0) {
								if ($hours['hours'] >= 5) {
									$hourly_rate = $hours['travel_range_5'];
								} else if ($hours['hours'] < 5 && $hours['hours'] >= 1) {
									$hourly_rate = $hours['travel_range_1_5'];
								} else {
									$hourly_rate = $hours['travel_range_1'];
								}
								echo "<div style='display:inline-block; text-align:right; width:4em;'><input type='text' ".($security['edit'] > 0 ? '' : 'readonly')." name='hours[]' data-rate='".$hourly_rate."' data-rate-travel1='".$hours['travel_range_1']."' data-rate-travel5='".$hours['travel_range_5']."' data-rate-travel15='".$hours['travel_range_1_5']."' class='form-control' value='".$hours['hours']."'></div>";
							} else {
								echo "<div style='display:inline-block; text-align:right; width:4em;'><input type='text' ".($security['edit'] > 0 ? '' : 'readonly')." name='hours[]' data-rate='".$hourly_rate."' class='form-control' value='".$hours['hours']."'></div>";
							}
							echo "<div style='display:inline-block; text-align:right; width:4em;' class='row-rate'>$".$hourly_rate."</div>";
							echo "<div style='display:inline-block; text-align:right; width:4em;' class='row-total'>$".number_format($hourly_rate * $hours['hours'],2)."</div>";
							if($hours['comment_box'] != '' && in_array(['Comments','text','comment_box'],$config['settings']['Choose Fields for Time Sheets']['data']['General'])) {
								echo html_entity_decode($hours['comment_box']);
							}
							echo "</div>";
							$cat_total += $hours['hours'] * $hourly_rate;
							$daily_total += $hours['hours'] * $hourly_rate;
						}
						$comment_box = $hours['comment_box'];
					}
					if($category != '') {
						echo "<div style='display:inline-block; width:12em;'>$category Total</div><div style='display:inline-block; text-align:right; width:16em;' class='cat-total'>$".number_format($cat_total,2)."</div>";
						echo "<div style='display:inline-block; vertical-align:top; width:12em;'>$category Description</div><input type='hidden' name='comment_date[]' value='$date'><input type='hidden' name='comment_cat[]' value='$category'><input type='hidden' name='cat_comment[]' id='desc_".$desc_inc."' value='".$comment_box."'><div class='comment_box_text' style='display:inline-block; width:16em;'>".$comment_box;
						echo "<p><a class='pull-right' href='#desc_".$desc_inc."' onclick='expandArea(this)'>Edit Description</a></p></div></div>";
						$desc_inc++;
					}
					echo "<div style='border:solid black 1px; padding:0.25em; width:30em;'><div style='display:inline-block; width:12em;'>Daily Total</div><div style='display:inline-block; text-align:right; width:16em;' class='day-total'>$".number_format($daily_total,2)."</div></div></div>";
				} while($ticket = mysqli_fetch_assoc($ticket_query));
			}*/
		endif;
		if($report_format == 'to_array') {
			$report_output[] = $report;
			$report = '';
		}
	}
	if($report_format == 'to_array') {
		return $report_output;
	}
	return $report;
} ?>

<?php function get_egs_hours_report($dbc, $see_staff, $search_start_date, $search_end_date,$staff,$report_format, $tab) {
	$value_config = explode(',',get_field_config($dbc, 'time_cards'));
	$timesheet_payroll_fields = '';
	$timesheet_time_format = get_config($dbc, 'timesheet_time_format');
	$timesheet_payroll_layout = get_config($dbc, 'timesheet_payroll_layout');
	$timesheet_payroll_overtime = get_config($dbc, 'timesheet_payroll_overtime');
	$timesheet_payroll_doubletime = get_config($dbc, 'timesheet_payroll_doubletime');
	$total_columns = 5;
	if($tab == 'payroll') {
		$timesheet_payroll_fields = ','.get_config($dbc, 'timesheet_payroll_fields').',';
		$total_columns += count(array_filter(array_unique(explode(',',$timesheet_payroll_fields))));
	}
	if(in_array('view_ticket',$value_config)) {
		$total_columns++;
	}
	$col_width = 100 / $total_columns;

    $pass_var = '';
    foreach (explode(',',$see_staff) as $search_staff_pass) {
        $pass_var .= 'search_staff%5B%5D='.$search_staff_pass.'&';
    }

    if($report_format != 'report') {
        echo '<a href="payroll.php?'.$pass_var.'search_start_date='.$search_start_date.'&search_end_date='.$search_end_date.'&search_user_submit=Search" name="display_all_inventory" class="btn brand-btn mobile-block pull-right">Go Back</a>';
    }

    if($staff == '') {
  		return '<h4>Please select a staff member.</h4>';
    } else {
        $staff_list = [];
        foreach (explode(',',$staff) as $search_staff) {
            if($search_staff > 0) {
                $staff_list[] = ['contactid'=>$search_staff,'first_name'=>'','last_name'=>get_contact($dbc, $search_staff)];
            }
        }
    }

    $report = '';
	if($report_format == 'to_array') {
		$report_output = [];
	}

	foreach($staff_list as $staff) {
        $search_staff = $staff['contactid'];

		$report .= '<div class="clearfix"></div><br style="display:none;" /><h3 class="triple-gap-top">'.$staff['first_name'].' '.$staff['last_name'].'</h3>';

		$start_of_year = date('Y-01-01', strtotime($search_start_date));
        $total_colspan = 2;
        $report .= '<table cellpadding="3" border="0" class="table table-bordered" style="text-align:left; border:1px solid #ddd;">
                <tr class="hidden-xs hidden-sm">
                    <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Date</div></th>'.
                    (in_array('total_tracked_hrs',$value_config) ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Tracked Hours</div></th>' : '').
                    (in_array('view_ticket',$value_config) ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>'.TICKET_NOUN.'</div></th>' : '').
	                (strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Expenses Owed</div></th>' : '').
	                (strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Mileage</div></th>' : '').
	                (strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Mileage Rate</div></th>' : '').
	                (strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Mileage Total</div></th>' : '').
                    '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Reg. Time</div></th>
                    <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Over Time</div></th>
                    <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Double Time</div></th>
                    <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Total</div></th>
                    '.($tab == 'payroll' && $report_format != 'to_array' ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Unapprove</div></th>' : '').'
                </tr>';
                $total = 0;
                $total_reg = 0;
                $total_overtime = 0;
                $total_doubletime = 0;
                $mileage_total = 0;
                $mileage_rate_total = 0;
                $mileage_cost_total = 0;
                $limits = "AND `staff`='$search_staff'";
                if($search_site > 0) {
                    $limits .= " AND `business` LIKE '%$search_site%'";
                }
                if($tab == 'payroll') {
                	$limits .= " AND (`approv` = 'Y' OR `approv` = 'P')";
                }
                $limits .= " AND `type_of_time` != 'Vac Hrs.'";
                $result = get_time_sheet($search_start_date, $search_end_date, $limits, ', `staff`, `date`, `time_cards_id`');
                $date = $search_start_date;
                $i = 0;
                while(strtotime($date) <= strtotime($search_end_date)) {
                	$milage = 0;
                	$mileage_rate = 0;
                	$mileage_cost = 0;
                    if($result[$i]['date'] == $date) {
                        $row = $result[$i++];
                        $row['row_hours'] = $row['hours'];
                        $total_reg += $row['hours'];
                        $total += $row['hours'];

                        //Mileage
                        $mileage_start = $date.' 00:00:00';
                        $mileage_end = $date.' 23:59:59';
                        $mileage = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`mileage`) `mileage_total` FROM `mileage` WHERE `deleted` = 0 AND `staffid` = '$search_staff' AND `ticketid` = '".$row['ticketid']."' AND '".$row['ticketid']."' > 0 AND (`start` BETWEEN '$mileage_start' AND '$mileage_end' OR `end` BETWEEN '$mileage_start' AND '$mileage_end')"))['mileage_total'];
                        $mileage_total += $mileage;

                        //Mileage Rate
                        $mileage_customer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `clientid` FROM `tickets` WHERE `ticketid` = '".$row['ticketid']."' AND '".$row['ticketid']."'"))['clientid'];
                        $mileage_rate = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `mileage` `price` FROM `rate_card` WHERE `clientid` = '$mileage_customer' AND '$mileage_customer' > 0 AND `deleted` = 0 AND `on_off` = 1 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31') UNION
                            SELECT `cust_price` `price` FROM `company_rate_card` WHERE LOWER(`tile_name`)='mileage' AND `deleted`=0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')"))['price'];
                        $mileage_rate_total += $mileage_rate;

                        //Mileage Calculated Cost
                        $mileage_cost = $mileage * $mileage_rate;
                        $mileage_cost_total += $mileage_cost;
                    } else {
                        $row = '';
                        $mileage = 0;
                        $mileage_rate = 0;
                        $mileage_cost = 0;
                    }
                    $ticketids = [$row['ticketid']];
                    $mileages = [];
                    $mileage_rates = [];
                    $mileage_costs = [];
                    if($mileage > 0) {
	                    $mileages[] = $mileage;
	                    $mileage_rates[] = $mileage_rate;
	                    $mileage_costs[] = $mileage_cost;
	                }
                    if($timesheet_payroll_layout == 'group_days') {
                    	$multidays = false;
                    	while($result[$i]['date'] == $date) {
                    		$row['hours'] += $result[$i]['hours'];
                    		$row['row_hours'] += $result[$i]['hours'];
                    		$total += $result[$i]['hours'];
                    		$total_reg += $result[$i]['hours'];
                    		$ticketids[] = $result[$i]['ticketid'];
                    		$multidays = true;

	                        //Mileage
	                        $mileage_start = $date.' 00:00:00';
	                        $mileage_end = $date.' 23:59:59';
	                        $mileage = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`mileage`) `mileage_total` FROM `mileage` WHERE `deleted` = 0 AND `staffid` = '$search_staff' AND `ticketid` = '".$result[$i]['ticketid']."' AND '".$result[$i]['ticketid']."' > 0 AND (`start` BETWEEN '$mileage_start' AND '$mileage_end' OR `end` BETWEEN '$mileage_start' AND '$mileage_end')"))['mileage_total'];
	                        $mileage_total += $mileage;

	                        //Mileage Rate
	                        $mileage_customer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `clientid` FROM `tickets` WHERE `ticketid` = '".$result[$i]['ticketid']."' AND '".$result[$i]['ticketid']."'"))['clientid'];
	                        $mileage_rate = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `mileage` `price` FROM `rate_card` WHERE `clientid` = '$mileage_customer' AND '$mileage_customer' > 0 AND `deleted` = 0 AND `on_off` = 1 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31') UNION
	                            SELECT `cust_price` `price` FROM `company_rate_card` WHERE LOWER(`tile_name`)='mileage' AND `deleted`=0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')"))['price'];
	                        $mileage_rate_total += $mileage_rate;

	                        //Mileage Calculated Cost
	                        $mileage_cost = $mileage * $mileage_rate;
	                        $mileage_cost_total += $mileage_cost;

	                        if($mileage > 0) {
			                    $mileages[] = $mileage;
			                    $mileage_rates[] = $mileage_rate;
			                    $mileage_costs[] = $mileage_cost;
			                }

                    		$i++;
                    	}
                    }
                    if(empty($mileages)) {
                    	$mileages = [''];
                    	$milage_rates = [''];
                    	$mileage_costs = [''];
                    }
	                if($timesheet_payroll_doubletime > 0 && $row['hours'] > $timesheet_payroll_doubletime) {
	                	$row['doubletime_hours'] = $row['hours'] - $timesheet_payroll_doubletime;
	                	$row['hours'] -= $row['doubletime_hours'];
	                	$total_reg -= $row['doubletime_hours'];
	                	$total_doubletime += $row['doubletime_hours'];
	                }
	                if($timesheet_payroll_overtime > 0 && $row['hours'] > $timesheet_payroll_overtime) {
	                	$row['overtime_hours'] = $row['hours'] - $timesheet_payroll_overtime;
	                	$row['hours'] -= $row['overtime_hours'];
	                	$total_reg -= $row['overtime_hours'];
	                	$total_overtime += $row['overtime_hours'];
	                }
                    $ticketids = array_filter(array_unique($ticketids));

	                $expenses_owed = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`total`) `expenses_owed` FROM `expense` WHERE `deleted` = 0 AND `staff` = '$search_staff' AND `status` = 'Approved' AND `approval_date` = '$date'"))['expenses_owed'];
                    if($row['hours'] > 0 || ($expenses_owed > 0 && strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE)) {
                    	$view_ticket = [];
                    	foreach($ticketids as $ticketid) {
	                    	if($ticketid > 0) {
	                    		$ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid` = '{$ticketid}'"));
	                    		if($report_format == 'report') {
	                    			$view_ticket[] = get_ticket_label($dbc, $ticket);
	                    		} else {
		                    		$view_ticket[] = '<a href="" onclick="viewTicket(this); return false;">'.get_ticket_label($dbc, $ticket).'</a>';
		                    	}
	                    	}
	                    }
	                    $mileage_html = [];
	                    $mileage_rate_html = [];
	                    $mileage_cost_html = [];
	                    foreach($mileages as $mileage_i => $mileage) {
	                    	$mileage_html[] = !empty($mileage) ? number_format($mileage,2) : '0.00';
	                    	$mileage_rate_html[] = '$'.(!empty($mileage_rates[$mileage_i]) ? number_format($mileage_rates[$mileage_i],2) : '0.00');
	                    	$mileage_cost_html[] = '$'.(!empty($mileage_costs[$mileage_i]) ? number_format($mileage_costs[$mileage_i],2) : '0.00');
	                    }
	                    $mileage_html = implode('<br>',$mileage_html);
	                    $mileage_rate_html = implode('<br>',$mileage_rate_html);
	                    $mileage_cost_html = implode('<br>',$mileage_cost_html);
	                    $view_ticket = implode('<br>',$view_ticket);
                        $report .= '<tr>
                            <td  style=" border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Date">'.$date.'</td>'.
                            (in_array('view_ticket',$value_config) ? '<td style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="'.TICKET_NOUN.'">'.$view_ticket.'</td>' : '').
			                (strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Expenses Owed">$'.($expenses_owed > 0 ? number_format($expenses_owed,2) : '0.00').'</td>' : '').
			                (strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Mileage">'.$mileage_html.'</td>' : '').
			                (strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Mileage Rate">'.$mileage_rate_html.'</td>' : '').
			                (strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Mileage Total">'.$mileage_cost_html.'</td>' : '');

                        $report .= (in_array('total_tracked_hrs',$value_config) ? '<td style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Time Tracked">'.$row['timer'].'</td>' : '').'
                            <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Hours">'.(empty($row['hours']) ? ($timesheet_time_format == 'decimal' ? '0.00' : '0:00') : ($timesheet_time_format == 'decimal' ? number_format($row['hours'],2) : time_decimal2time($row['hours']))).' h</td>
                            <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Over Time">'.(empty($row['overtime_hours']) ? ($timesheet_time_format == 'decimal' ? '0.00' : '0:00') : ($timesheet_time_format == 'decimal' ? number_format($row['overtime_hours'],2) : time_decimal2time($row['overtime_hours']))).' h</td>
                            <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Double Time">'.(empty($row['doubletime_hours']) ? ($timesheet_time_format == 'decimal' ? '0.00' : '0:00') : ($timesheet_time_format == 'decimal' ? number_format($row['doubletime_hours'],2) : time_decimal2time($row['doubletime_hours']))).' h</td>
                            <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total">'.(empty($row['row_hours']) ? ($timesheet_time_format == 'decimal' ? '0.00' : '0:00') : ($timesheet_time_format == 'decimal' ? number_format($row['row_hours'],2) : time_decimal2time($row['row_hours']))).' h</td>';
                            if($tab == 'payroll' && $report_format != 'to_array') {
		                    	$report .= '<td align="center" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Unapprove">';
		                    	$report .= '<a href="" onclick="unapproveTimeSheet(this); return false;"'.($timesheet_payroll_layout ? ' data-type="day" data-date="'.$date.'"' : ' data-type="id" data-timesheetid="'.$row['id'].'"').' data-staff="'.$search_staff.'">Unapprove</a>';
		                    	$report .= '</td>';
		                    }
                        $report .= '</tr>';
                    }
                    if($date != $row['date']) {
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    }

                }

                $expenses_owed = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`total`) `expenses_owed` FROM `expense` WHERE `deleted` = 0 AND `staff` = '$search_staff' AND `status` = 'Approved' AND `approval_date` BETWEEN '$search_start_date' AND '$search_end_date'"))['expenses_owed'];

                $report .= '<tr>
                    <td style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title="">Totals</td>'.
                    (in_array('total_tracked_hrs',$value_config) ? '<td style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title=""></td>' : '' ).
                    (in_array('view_ticket',$value_config) ? '<td style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title=""></td>' : '' ).
	                (strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Expenses Owed">$'.($expenses_owed > 0 ? number_format($expenses_owed,2) : '0.00').'</td>' : '').
	                (strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Mileage">'.($mileage_total > 0 ? number_format($mileage_total,2) : '0.00').'</td>' : '').
	                (strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Mileage Rate">$'.($mileage_rate_total > 0 ? number_format($mileage_rate_total,2) : '0.00').'</td>' : '').
	                (strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Mileage Cost">$'.($mileage_cost_total > 0 ? number_format($mileage_cost_total,2) : '0.00').'</td>' : '').
                    '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title="Total Regular">'.($timesheet_time_format == 'decimal' ? number_format($total_reg,2) : time_decimal2time($total_reg)).' h</td>
                    <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title="Total Over Time">'.($timesheet_time_format == 'decimal' ? number_format($total_overtime,2) : time_decimal2time($total_overtime)).' h</td>
                    <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data="Total Double Time">'.($timesheet_time_format == 'decimal' ? number_format($total_doubletime,2) : time_decimal2time($total_doubletime)).' h</td>
                    <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title="Total Hours">'.($timesheet_time_format == 'decimal' ? number_format($total,2) : time_decimal2time($total)).' h</td>
                    '.($tab == 'payroll' && $report_format != 'to_array' ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title=""></td>' : '').'
                </tr>
            </table>';

        $tb_field = $value['config_field'];

		if($report_format == 'to_array') {
			$report_output[] = $report;
			$report = '';
		}
	}
	if($report_format == 'to_array') {
		return $report_output;
	}
	return $report;
} ?>

<?php function get_egs_main_hours_report($dbc, $staff, $search_start_date, $search_end_date, $report_format = '', $tab) {
	$timesheet_payroll_fields = '';
	$timesheet_time_format = get_config($dbc, 'timesheet_time_format');
	$timesheet_payroll_layout = get_config($dbc, 'timesheet_payroll_layout');
	$timesheet_payroll_overtime = get_config($dbc, 'timesheet_payroll_overtime');
	$timesheet_payroll_doubletime = get_config($dbc, 'timesheet_payroll_doubletime');
	$total_columns = 5;
	if($tab == 'payroll') {
		$timesheet_payroll_fields = ','.get_config($dbc, 'timesheet_payroll_fields').',';
		$total_columns += count(array_diff(array_filter(array_unique(explode(',',$timesheet_payroll_fields))),['Mileage','Mileage Rate','Mileage Total']));
	}
	$col_width = 100 / $total_columns;

    if($staff == '') {
  		return '<h4>Please select a staff member.</h4>';
    } else {
        $staff_list = [];
        if(!is_array($staff)) {
        	$staff = explode(',',$staff);
        }
        foreach ($staff as $search_staff) {
            if($search_staff > 0) {
                $staff_list[] = ['contactid'=>$search_staff,'first_name'=>'','last_name'=>get_contact($dbc, $search_staff)];
            }
        }
    }

    $report = '';
	if($report_format == 'to_array') {
		$report_output = [];
	}

    $report .= '<table cellpadding="3" border="0" class="table table-bordered" style="text-align:left; border:1px solid #ddd;">
            <tr class="hidden-xs hidden-sm">
                <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Staff</div></th>'.
                (strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Total Expenses Owed</div></th>' : '').
                '<th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Total Reg. Time</div></th>
                <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Total Over Time</div></th>
                <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Total Double Time</div></th>
                <th style="border-right: 1px solid #ddd; text-align:center; width:'.$col_width.'%;font-weight:bold;"><div>Total Time</div></th>
            </tr>';

	foreach($staff_list as $staff) {
        $search_staff = $staff['contactid'];

		$start_of_year = date('Y-01-01', strtotime($search_start_date));
        $total_colspan = 2;

                $total = 0;
                $total_reg = 0;
                $total_overtime = 0;
                $total_doubletime = 0;
                $limits = "AND `staff`='$search_staff'";
                if($search_site > 0) {
                    $limits .= " AND `business` LIKE '%$search_site%'";
                }
                if($tab == 'payroll') {
                	$limits .= " AND (`approv` = 'Y' OR `approv` = 'P')";
                }
                $limits .= " AND `type_of_time` != 'Vac Hrs.'";
                $result = get_time_sheet($search_start_date, $search_end_date, $limits, ', `staff`, `date`, `time_cards_id`');
                $date = $search_start_date;
                $i = 0;
                while(strtotime($date) <= strtotime($search_end_date)) {
                    if($result[$i]['date'] == $date) {
                        $row = $result[$i++];
                        $total_reg += $row['hours'];
                        $total += $row['hours'];
                    } else {
                        $row = '';
                    }

                    if($timesheet_payroll_layout == 'group_days') {
                    	while($result[$i]['date'] == $date) {
                    		$row['hours'] += $result[$i]['hours'];
                    		$row['row_hours'] += $result[$i]['hours'];
                    		$total += $result[$i]['hours'];
                    		$total_reg += $result[$i]['hours'];
                    		$ticketids[] = $result[$i]['ticketid'];
                    		$multidays = true;
                    		$i++;
                    	}
                    }
	                if($timesheet_payroll_doubletime > 0 && $row['hours'] > $timesheet_payroll_doubletime) {
	                	$row['doubletime_hours'] = $row['hours'] - $timesheet_payroll_doubletime;
	                	$row['hours'] -= $row['doubletime_hours'];
	                	$total_reg -= $row['doubletime_hours'];
	                	$total_doubletime += $row['doubletime_hours'];
	                }
	                if($timesheet_payroll_overtime > 0 && $row['hours'] > $timesheet_payroll_overtime) {
	                	$row['overtime_hours'] = $row['hours'] - $timesheet_payroll_overtime;
	                	$row['hours'] -= $row['overtime_hours'];
	                	$total_reg -= $row['overtime_hours'];
	                	$total_overtime += $row['overtime_hours'];
	                }

                    if($date != $row['date']) {
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    }

                }

                $base_url = $_SERVER[REQUEST_URI];

                $expenses_owed = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`total`) `expenses_owed` FROM `expense` WHERE `deleted` = 0 AND `staff` = '$search_staff' AND `status` = 'Approved' AND `approval_date` BETWEEN '$search_start_date' AND '$search_end_date'"))['expenses_owed'];

                if($total > 0) {
                $report .= '<tr>
                    <td style="border-top:1px solid #ddd; border-right:1px solid #ddd;font-weight:bold;" data-title="Staff"><a href="'.$base_url.'&see_staff='.$search_staff.'">'.$staff['first_name'].' '.$staff['last_name'].'</a></td>'.
	                (strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Expenses Owed">$'.($expenses_owed > 0 ? number_format($expenses_owed,2) : '0.00').'</td>' : '').
	                '<td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Reg.">'.($timesheet_time_format == 'decimal' ? number_format($total_reg,2) : time_decimal2time($total_reg)).' h</td>
                    <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Over">'.($timesheet_time_format == 'decimal' ? number_format($total_overtime,2) : time_decimal2time($total_overtime)).' h</td>
                    <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Double">'.($timesheet_time_format == 'decimal' ? number_format($total_doubletime,2) : time_decimal2time($total_doubletime)).' h</td>
                    <td align="right" style="border-top:1px solid #ddd; border-right:1px solid #ddd;" data-title="Total Time">'.($timesheet_time_format == 'decimal' ? number_format($total,2) : time_decimal2time($total)).' h</td>
                </tr>
            ';
            }

        $tb_field = $value['config_field'];

		if($report_format == 'to_array') {
			$report_output[] = $report;
			$report = '';
		}
	}

    $report .= '</table>';

	if($report_format == 'to_array') {
		return $report_output;
	}
	return $report;
} ?>