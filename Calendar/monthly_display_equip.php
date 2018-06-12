<?php
$region_list = explode(',',get_config($dbc, '%_region', true));
$region_colours = explode(',',get_config($dbc, '%_region_colour', true));
if($_GET['mode'] == 'staff' || $_GET['mode'] == 'contractors') {
	$result = mysqli_query($dbc,"SELECT * FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `contactid` = '$contact_id'".$region_query);

	$old_staff = '';
	while($row = mysqli_fetch_array( $result )) {
	    $contactid = $row['contactid'];
	    $staff = get_staff($dbc, $contactid);
	    if(empty($row['calendar_color'])) {
	    	$row['calendar_color'] = '#6DCFF6';
	    }
	    $completed_tickets = 0;

		$teams = mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `teamid` SEPARATOR ',') as teams_list FROM `teams_staff` WHERE `contactid` = '$contactid' AND `deleted` = 0"));
		if(!empty($teams['teams_list'])) {
			$teams_query = 'OR `teamid` IN ('.$teams['teams_list'].')';
		} else {
			$teams_query = '';
		}
		$equip_assign = mysqli_fetch_array(mysqli_query($dbc, "SELECT ea.* FROM `equipment_assignment` ea LEFT JOIN `equipment_assignment_staff` eas ON ea.`equipment_assignmentid` = eas.`equipment_assignmentid` WHERE ea.`deleted` = 0 AND DATE(`start_date`) <= '$new_today_date' AND DATE(ea.`end_date`) >= '$new_today_date' AND CONCAT(',',ea.`hide_days`,',') NOT LIKE '%,$new_today_date,%' AND ((eas.`contactid` = '$contactid' AND eas.`deleted` = 0) $teams_query) ORDER BY ea.`start_date` DESC, ea.`end_date` ASC"));
		if(!empty($equip_assign)) {
			$equipment_assignmentid = $equip_assign['equipment_assignmentid'];
			$equipment_staff = mysqli_fetch_array(mysqli_query($dbc, "SELECT *, CONCAT(`category`, ' #', `unit_number`) label FROM `equipment` WHERE `equipmentid` = '".$equip_assign['equipmentid']."'"));
		    $query = $_GET;
		    unset($query['equipment_assignmentid']);
		    unset($query['teamid']);
		    unset($query['unbooked']);
		    $staff_name = $staff.'<br />('.($edit_access == 1 ? '<a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Calendar/equip_assign.php?equipment_assignmentid='.$equip_assign['equipment_assignmentid'].'&region='.$_GET['region'].'\'); return false;">' : '').$equipment_staff['label'].($edit_access == 1 ? '</a>' : '').')';
		} else {
			$equipment_assignmentid = '';
			$staff_name = $staff.'<br />(No Assignment)';
		}

	    if($wait_list == 'ticket') {
			$allowed_regions_query = " AND IFNULL(`tickets`.`region`,'') IN ('".implode("','", array_merge($allowed_regions,['']))."')";
			$allowed_locations_query = " AND IFNULL(`tickets`.`con_location`,'') IN ('".implode("','", array_merge($allowed_locations,['']))."')";
			$allowed_classifications_query = " AND IFNULL(`tickets`.`classification`,'') IN ('".implode("','", array_merge($allowed_classifications,['']))."')";
			$all_tickets_sql = "SELECT `tickets`.*, `ticket_schedule`.`id` `stop_id`, IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`) `to_do_date`, IFNULL(`ticket_schedule`.`to_do_start_time`,`tickets`.`to_do_start_time`) `to_do_start_time`, IFNULL(`ticket_schedule`.`scheduled_lock`,0) `scheduled_lock`, IFNULL(`ticket_schedule`.`to_do_end_time`,`tickets`.`to_do_end_time`) `to_do_end_time`, IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`) `equipmentid`, IFNULL(`ticket_schedule`.`equipment_assignmentid`,`tickets`.`equipment_assignmentid`) `equipment_assignmentid`, IFNULL(`ticket_schedule`.`teamid`,`tickets`.`teamid`) `teamid`, IFNULL(`ticket_schedule`.`contactid`,`tickets`.`contactid`) `contactid`, IF(`ticket_schedule`.`id` IS NULL,'ticket','ticket_schedule') `ticket_table`, IFNULL(`ticket_schedule`.`id`, 0) `ticket_scheduleid`, IFNULL(`ticket_schedule`.`last_updated_time`,`tickets`.`last_updated_time`) `last_updated_time`, CONCAT(' - ',IFNULL(NULLIF(`ticket_schedule`.`location_name`,''),`ticket_schedule`.`client_name`)) `location_description`, IFNULL(`ticket_schedule`.`scheduled_lock`,0) `scheduled_lock`, `ticket_schedule`.`type` `delivery_type`, IFNULL(`ticket_schedule`.`status`, `tickets`.`status`) `status`, `ticket_schedule`.`location_name`, `ticket_schedule`.`client_name` FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` AND `ticket_schedule`.`deleted`=0 WHERE ('".$new_today_date."' BETWEEN `tickets`.`to_do_date` AND `tickets`.`to_do_end_date` OR '".$new_today_date."' BETWEEN `ticket_schedule`.`to_do_date` AND IFNULL(`ticket_schedule`.`to_do_end_date`,`ticket_schedule`.`to_do_date`)) AND IFNULL(IFNULL(`ticket_schedule`.`to_do_start_time`,`tickets`.`to_do_start_time`),'') != '' AND (`tickets`.`contactid` LIKE '%,".$contactid.",%' OR `ticket_schedule`.`contactid` LIKE '%,$contactid,%') AND `tickets`.`deleted` = 0 AND `tickets`.`status` NOT IN ('Archive', 'Done')";
			$tickets = mysqli_fetch_all(mysqli_query($dbc, $all_tickets_sql),MYSQLI_ASSOC);

		    $num_rows = mysqli_num_rows($tickets);

		    if(!empty($tickets)) {
		    	if(!empty($equipment_assignmentid)) {
					$equipassignid_data = "data-equipassign='".$equipment_assignmentid."'";
		    	} else {
					$equipassignid_data = "";
		    	}
		    	$column .= '<div class="calendar_block calendarSortable" data-contact="'.$contactid.'" data-date="'.$new_today_date.'" '.$equipassignid_data.'>';
		        $column .= '<h4>'.$staff_name.'</h4>';
		        foreach ($tickets as $row_ticket) {
					$current_assignstaff = explode(',',$row_ticket['contactid']);
					$current_team = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `teams` WHERE `teamid` = '".$row_ticket['teamid']."'"));
					$current_team_contacts = '';
					foreach (explode('*#*', $current_team['contactid']) as $single_cat) {
						$cat_contacts = explode(',',$single_cat);
						foreach ($cat_contacts as $single_contact) {
							$current_assignstaff[] = $single_contact;
						}
					}
					$current_assignstaff = implode(',', $current_assignstaff);
					$status = $row_ticket['status'];
					if($calendar_checkmark_tickets == 1 && in_array($status, $calendar_checkmark_status)) {
						$checkmark_ticket = 'calendar-checkmark-ticket-month';
					} else {
						$checkmark_ticket = '';
					}
					$delivery_color = get_delivery_color($dbc, $row_ticket['delivery_type']);
					if($calendar_highlight_tickets == 1 && in_array($status, $calendar_checkmark_status)) {
						$ticket_styling = ' background-color:'.$calendar_completed_color[$status].';';
					} else if($calendar_highlight_incomplete_tickets == 1 && in_array($status, $calendar_incomplete_status)) {
						$ticket_styling = ' background-color:'.$calendar_incomplete_color[$status].';';
					} else if(!empty($delivery_color)) {
						$ticket_styling = ' background-color:'.$delivery_color.';';
					} else {
						$ticket_styling = ' background-color:'.$row['calendar_color'].';';
					}
					if(in_array($status, $calendar_checkmark_status)) {
						$completed_tickets++;
					}
					$status_icon = get_ticket_status_icon($dbc, $row_ticket['status']);
				    if(!empty($status_icon)) {
				        $icon_img = '';
				    	$icon_background = '';
				    	if($calendar_ticket_status_icon == 'background' && $status_icon != 'initials') {
			    			$icon_background = " background-image: url('".$status_icon."'); background-repeat: no-repeat; height: 100%; background-size: contain; background-position: center;";
				    	} else {
					    	if($status_icon == 'initials') {
								$icon_img = '<span class="id-circle-small pull-right" style="background-color: #6DCFF6; font-family: \'Open Sans\';">'.get_initials($row_ticket['status']).'</span>';
					    	} else {
						        $icon_img = '<img src="'.$status_icon.'" class="pull-right" style="max-height: 20px;">';
						    }
						}
				    } else {
				        $icon_img = '';
				    	$icon_background = '';
				    }
					$max_time = explode(':', $row_ticket['max_time']);
					$max_time_hour = $max_time[0];
					$max_time_minute = $max_time[1];
					$start_time = date('h:i a', strtotime($row_ticket['to_do_start_time']));
					if(!empty($row_ticket['to_do_end_time'])) {
						$end_time = date('h:i a', strtotime($row_ticket['to_do_end_time']));
					} else if (!empty($row_ticket['max_time']) && $row_ticket['max_time'] != '00:00:00') {
						$end_time = date('h:i a', strtotime('+'.$max_time_hour.' hours +'.$max_time_minute.' minutes', strtotime($start_time)));
					} else {
						$end_time = date('h:i a', strtotime('+'.($day_period * 2).' minutes', strtotime($start_time)));
					}
					$max_time = $row_ticket['max_time'];
					$column .= "<a class='sortable-blocks ".$checkmark_ticket."' href='' onclick='".($edit_access == 1 ? "overlayIFrameSlider(\"".WEBSITE_URL."/Ticket/index.php?calendar_view=true&edit=".$row_ticket['ticketid']."\");" : "")."return false;' style='display:block; margin: 0.5em; padding:5px; color:black; border-radius: 10px;".$ticket_styling.$icon_background."' data-ticket='".$row_ticket['ticketid']."' data-region='".$row_ticket['region']."' data-businessid='".$row_ticket['businessid']."' data-assignstaff='".$current_assignstaff."' data-teamid='".$row_ticket['teamid']."' data-status='".$row_ticket['status']."' data-currentstaff='".$contactid."' data-equipassign='".$equipment_assignmentid."' data-itemtype='ticket_equip' data-blocktype='dispatch_staff' data-tickettable='".$row_ticket['ticket_table']."' data-ticketscheduleid='".$row_ticket['ticket_scheduleid']."' data-timestamp='".date('Y-m-d H:i:s')."'>".$icon_img;
					if($ticket_status_color_code == 1 && !empty($ticket_status_color[$status])) {
						$column .= '<div class="ticket-status-color" style="background-color: '.$ticket_status_color[$status].';"></div>';
					}
					$column .= '<b>'.get_ticket_label($dbc, $row_ticket, null, null, $calendar_ticket_label).(empty($calendar_ticket_label) ? $row_ticket['location_description'] : '').($row_ticket['sub_label'] != '' ? '-'.$row_ticket['sub_label'] : '').'</b>'.($row_ticket['scheduled_lock'] > 0 ? '<img class="inline-img" title="Time is locked for this '.TICKET_NOUN.'" src="../img/icons/lock.png">' : '').
						(in_array('project',$calendar_ticket_card_fields) ? '<br />'.PROJECT_NOUN.' #'.$row_ticket['projectid'].' '.$row_ticket['project_name'].'<br />' : '').
						(in_array('customer',$calendar_ticket_card_fields) ? '<br />'.'Customer: '.get_contact($dbc, $row_ticket['businessid'], 'name') : '').
						(in_array('time',$calendar_ticket_card_fields) ? '<br />'."(".$max_time.") ".$start_time." - ".$end_time : '');
					if(in_array('available',$calendar_ticket_card_fields)) {
						if($row_ticket['pickup_start_available'].$row_ticket['pickup_end_available'] != '') {
							$column .= '<br />'."Available ";
							if($row_ticket['pickup_end_available'] == '') {
								$column .= "After ".$row_ticket['pickup_start_available'];
							} else if($row_ticket['pickup_start_available'] == '') {
								$column .= "Before ".$row_ticket['pickup_end_available'];
							} else {
								$column .= "Between ".$row_ticket['pickup_start_available']." and ".$row_ticket['pickup_end_available'];
							}
						}
					}
					$column .= (in_array('address',$calendar_ticket_card_fields) ? '<br />'.$row_ticket['pickup_name'].($row_ticket['pickup_name'] != '' ? '<br />' : ' ').$row_ticket['client_name'].($row_ticket['client_name'] != '' ? '<br />' : ' ').$row_ticket['pickup_address'].($row_ticket['pickup_address'] != '' ? '<br />' : ' ').$row_ticket['pickup_city'] : '');
					$column .= '<br />'."Status: ".$status."</b>";
					// $column .= TICKET_NOUN." #".$row_ticket['heading'].'<br />'.(!empty($row_ticket['businessid']) ? get_client($dbc,$row_ticket['businessid']).'<br />' : '').date('h:i a', strtotime($row_ticket['to_do_start_time']))." - ".date('h:i a', strtotime($row_ticket['to_do_end_time']));
					$column .= "</a>";
		        }
		        if($ticket_summary != '') {
		        	$column .= '<span>Completed '.$completed_tickets.' of '.count($tickets).' '.(count($tickets) == 1 ? TICKET_NOUN : TICKET_TILE).'</span>';
		        }
		        $column .= '</div>';
		    }
	    }
	}
} else {
	if(!empty($_POST['all_ea'])) {
		$all_ea = "'".implode("','",$_POST['all_ea'])."'";
		$all_contacts = [];
		$ea_equipmentids = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `equipmentid` FROM `equipment_assignment` WHERE `equipment_assignmentid` IN ($all_ea)"),MYSQLI_ASSOC);
		foreach ($ea_equipmentids as $ea_equipmentid) {
			$all_contacts[] = $ea_equipmentid['equipmentid'];
		}
		$all_contacts_query = "'".implode("','", $all_contacts)."'"; 
	}
	$equipment_category = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_equip_assign`"))['equipment_category'];
	if (!empty($equipment_category)) {
		$equipment_category = 'Truck';
	}
	$result = mysqli_query($dbc,"SELECT `equipmentid`, `unit_number`, `make`, `model`, `category`, CONCAT(`category`, ' #', `unit_number`) label FROM `equipment` WHERE `category`='".$equipment_category."' AND `deleted`=0 AND `equipmentid` = '$contact_id'");

	$old_staff = '';
	while($row = mysqli_fetch_array( $result )) {
		$completed_tickets = 0;
		// Equipment Blocks
		$equip_assign = mysqli_fetch_array(mysqli_query($dbc, "SELECT ea.*, e.*,ea.`notes` FROM `equipment_assignment` ea LEFT JOIN `equipment` e ON ea.`equipmentid` = e.`equipmentid` WHERE e.`equipmentid` = '".$row['equipmentid']."' AND ea.`deleted` = 0 AND DATE(`start_date`) <= '$new_today_date' AND DATE(ea.`end_date`) >= '$new_today_date' AND CONCAT(',',ea.`hide_days`,',') NOT LIKE '%,$new_today_date,%' ORDER BY ea.`start_date` DESC, ea.`end_date` ASC, e.`category`, e.`unit_number`"));
		if(!empty($equip_assign)) {
			$equipment_assignmentid = $equip_assign['equipment_assignmentid'];
			$team_name = '(Assigned)';
		    $query = $_GET;
		    unset($query['equipment_assignmentid']);
		    unset($query['teamid']);
		    unset($query['unbooked']);
			$team_name = '<a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Calendar/equip_assign.php?equipment_assignmentid='.$equip_assign['equipment_assignmentid'].'&region='.$_GET['region'].'\'); return false;">'.$row['label'].'<br />'.$team_name.'</a>';
		} else {
			$equipment_assignmentid = '';
			$team_name = '(No Team Assigned)';
		    $query = $_GET;
		    unset($query['equipment_assignmentid']);
		    unset($query['teamid']);
		    unset($query['unbooked']);
			$team_name = ($edit_access == 1 ? '<a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Calendar/equip_assign.php?equipment_assignmentid=NEW&equipmentid='.$row['equipmentid'].'&region='.$_GET['region'].'\'); return false;">' : '').$row['label'].'<br />'.$team_name.($edit_access == 1 ? '</a>' : '');
		}

	    if(empty($row['calendar_color'])) {
	    	$row['calendar_color'] = '#6DCFF6';
	    }

	    if($wait_list == 'ticket') {
			$allowed_regions_query = " AND IFNULL(`tickets`.`region`,'') IN ('".implode("','", array_merge($allowed_regions,['']))."')";
			$allowed_locations_query = " AND IFNULL(`tickets`.`con_location`,'') IN ('".implode("','", array_merge($allowed_locations,['']))."')";
			$allowed_classifications_query = " AND IFNULL(`tickets`.`classification`,'') IN ('".implode("','", array_merge($allowed_classifications,['']))."')";
			
			$all_tickets_sql = "SELECT `tickets`.*, IFNULL(`ticket_schedule`.`to_do_date`,`tickets`.`to_do_date`) `to_do_date`, IFNULL(`ticket_schedule`.`to_do_start_time`,`tickets`.`to_do_start_time`) `to_do_start_time`, IFNULL(`ticket_schedule`.`to_do_end_time`,`tickets`.`to_do_end_time`) `to_do_end_time`, IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`) `equipmentid`, IFNULL(`ticket_schedule`.`equipment_assignmentid`,`tickets`.`equipment_assignmentid`) `equipment_assignmentid`, IFNULL(`ticket_schedule`.`teamid`,`tickets`.`teamid`) `teamid`, IFNULL(`ticket_schedule`.`contactid`,`tickets`.`contactid`) `contactid`, IF(`ticket_schedule`.`id` IS NULL,'ticket','ticket_schedule') `ticket_table`, IFNULL(`ticket_schedule`.`id`, 0) `ticket_scheduleid`, IFNULL(`ticket_schedule`.`last_updated_time`,`tickets`.`last_updated_time`) `last_updated_time`, IFNULL(`ticket_schedule`.`scheduled_lock`,0) `scheduled_lock`, `ticket_schedule`.`type` `delivery_type`, IFNULL(`ticket_schedule`.`status`, `tickets`.`status`) `status` FROM `tickets` LEFT JOIN `ticket_schedule` ON `tickets`.`ticketid`=`ticket_schedule`.`ticketid` AND `ticket_schedule`.`deleted`=0 WHERE ('".$new_today_date."' BETWEEN `tickets`.`to_do_date` AND `tickets`.`to_do_end_date` OR '".$new_today_date."' BETWEEN `ticket_schedule`.`to_do_date` AND IFNULL(`ticket_schedule`.`to_do_end_date`,`ticket_schedule`.`to_do_date`)) AND IFNULL(IFNULL(`ticket_schedule`.`to_do_start_time`,`tickets`.`to_do_start_time`),'') != '' AND (IFNULL(`ticket_schedule`.`equipmentid`,`tickets`.`equipmentid`)='".$row['equipmentid']."') AND `tickets`.`deleted` = 0 AND `tickets`.`status` NOT IN ('Archive', 'Done')".$allowed_regions_query.$allowed_locations_query.$allowed_classifications_query;
			// $all_tickets_sql = "SELECT * FROM `tickets` WHERE '".$new_today_date."' BETWEEN `to_do_date` AND `to_do_end_date` AND `equipmentid` = '".$row['equipmentid']."' AND `deleted` = 0 AND `status` NOT IN ('Archive', 'Done')";
			$tickets = mysqli_fetch_all(mysqli_query($dbc, $all_tickets_sql),MYSQLI_ASSOC);

		    $num_rows = mysqli_num_rows($tickets);

		    if(!empty($tickets)) {
		    	if(!empty($equipment_assignmentid)) {
					$equipassignid_data = "data-equipassign='".$equipment_assignmentid."'";
		    	} else {
					$equipassignid_data = "";
		    	}
		    	$column .= '<div class="calendar_block calendarSortable" data-contact="'.$row['equipmentid'].'" data-date="'.$new_today_date.'" '.$equipassignid_data.'>';
		        $column .= '<h4>'.$team_name.'</h4>';
		        foreach ($tickets as $row_ticket) {
					$current_assignstaff = explode(',',$row_ticket['contactid']);
					$current_team = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `teams` WHERE `teamid` = '".$row_ticket['teamid']."'"));
					$current_team_contacts = '';
					foreach (explode('*#*', $current_team['contactid']) as $single_cat) {
						$cat_contacts = explode(',',$single_cat);
						foreach ($cat_contacts as $single_contact) {
							$current_assignstaff[] = $single_contact;
						}
					}
					if($row_ticket['region'] == '') {
						$row_ticket['region'] = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `equipment_assignment` WHERE `equipment_assignmentid` = '".$equipment_assignmentid."'"))['region'];
						if($row_ticket['region'] == '') {
							$row_ticket['region'] = explode('*#*', mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `equipment` WHERE `equipmentid` = '".$row_ticket['equipmentid']."'"))['region'])[0];
						}
					}
					if($row_ticket['region'] != '') {
						foreach($region_list as $region_line => $region_name) {
							if($region_name == $row_ticket['region']) {
								$row['calendar_color'] = $region_colours[$region_line];
							}
						}
					}
					$current_assignstaff = implode(',', $current_assignstaff);
					$status = $row_ticket['status'];
					if($calendar_checkmark_tickets == 1 && in_array($status, $calendar_checkmark_status)) {
						$checkmark_ticket = 'calendar-checkmark-ticket-month';
					} else {
						$checkmark_ticket = '';
					}
					$delivery_color = get_delivery_color($dbc, $row_ticket['delivery_type']);
					if($calendar_highlight_tickets == 1 && in_array($status, $calendar_checkmark_status)) {
						$ticket_styling = ' background-color:'.$calendar_completed_color[$status].';';
					} else if($calendar_highlight_incomplete_tickets == 1 && in_array($status, $calendar_incomplete_status)) {
						$ticket_styling = ' background-color:'.$calendar_incomplete_color[$status].';';
					} else if(!empty($delivery_color)) {
						$ticket_styling = ' background-color:'.$delivery_color.';';
					} else {
						$ticket_styling = ' background-color:'.$row['calendar_color'].';';
					}
					if(in_array($status, $calendar_checkmark_status)) {
						$completed_tickets++;
					}
					$status_icon = get_ticket_status_icon($dbc, $row_ticket['status']);
				    if(!empty($status_icon)) {
				        $icon_img = '';
				    	$icon_background = '';
				    	if($calendar_ticket_status_icon == 'background' && $status_icon != 'initials') {
			    			$icon_background = " background-image: url(\"".$status_icon."\"); background-repeat: no-repeat; height: 100%; background-size: contain; background-position: center;";
				    	} else {
					    	if($status_icon == 'initials') {
								$icon_img = '<span class="id-circle-small pull-right" style="background-color: #6DCFF6; font-family: \'Open Sans\';">'.get_initials($row_ticket['status']).'</span>';
					    	} else {
						        $icon_img = '<img src="'.$status_icon.'" class="pull-right" style="max-height: 20px;">';
						    }
						}
				    } else {
				        $icon_img = '';
				    	$icon_background = '';
				    }
					$max_time = explode(':', $row_ticket['max_time']);
					$max_time_hour = $max_time[0];
					$max_time_minute = $max_time[1];
					$start_time = date('h:i a', strtotime($row_ticket['to_do_start_time']));
					if(!empty($row_ticket['to_do_end_time'])) {
						$end_time = date('h:i a', strtotime($row_ticket['to_do_end_time']));
					} else if (!empty($row_ticket['max_time']) && $row_ticket['max_time'] != '00:00:00') {
						$end_time = date('h:i a', strtotime('+'.$max_time_hour.' hours +'.$max_time_minute.' minutes', strtotime($start_time)));
					} else {
						$end_time = date('h:i a', strtotime('+'.($day_period * 2).' minutes', strtotime($start_time)));
					}
					$max_time = $row_ticket['max_time'];
					$column .= "<a class='sortable-blocks ".$checkmark_ticket."' href='' onclick='".($edit_access == 1 ? "overlayIFrameSlider(\"".WEBSITE_URL."/Ticket/index.php?calendar_view=true&edit=".$row_ticket['ticketid']."\");" : "")."return false;' style='display:block; margin: 0.5em; padding:5px; color:black; border-radius: 10px;".$ticket_styling.$icon_background."' data-ticket='".$row_ticket['ticketid']."' data-region='".$row_ticket['region']."' data-businessid='".$row_ticket['businessid']."' data-assignstaff='".$current_assignstaff."' data-teamid='".$row_ticket['teamid']."' data-status='".$row_ticket['status']."' data-equipassign='".$equipment_assignmentid."' data-itemtype='ticket_equip' data-blocktype='dispatch_equip' data-tickettable='".$row_ticket['ticket_table']."' data-ticketscheduleid='".$row_ticket['ticket_scheduleid']."' data-timestamp='".date('Y-m-d H:i:s')."'>".$icon_img;
					if($ticket_status_color_code == 1 && !empty($ticket_status_color[$status])) {
						$column .= '<div class="ticket-status-color" style="background-color: '.$ticket_status_color[$status].';"></div>';
					}
					// $column .= TICKET_NOUN." #".$row_ticket['heading'].'<br />'.(!empty($row_ticket['businessid']) ? get_client($dbc,$row_ticket['businessid']).'<br />' : '').date('h:i a', strtotime($row_ticket['to_do_start_time']))." - ".date('h:i a', strtotime($row_ticket['to_do_end_time']));
					$column .= '<b>'.get_ticket_label($dbc, $row_ticket, null, null, $calendar_ticket_label).(empty($calendar_ticket_label) ? $row_ticket['location_description'] : '').($row_ticket['sub_label'] != '' ? '-'.$row_ticket['sub_label'] : '').'</b>'.($row_ticket['scheduled_lock'] > 0 ? '<img class="inline-img" title="Time is locked for this '.TICKET_NOUN.'" src="../img/icons/lock.png">' : '').
						(in_array('project',$calendar_ticket_card_fields) ? '<br />'.PROJECT_NOUN.' #'.$row_ticket['projectid'].' '.$row_ticket['project_name'].'<br />' : '').
						(in_array('customer',$calendar_ticket_card_fields) ? '<br />'.'Customer: '.get_contact($dbc, $row_ticket['businessid'], 'name') : '').
						(in_array('time',$calendar_ticket_card_fields) ? '<br />'.(!empty($max_time) && $max_time != '00:00:00' ? "(".$max_time.") " : '').$start_time." - ".$end_time : '');
					if(in_array('available',$calendar_ticket_card_fields)) {
						if($row_ticket['pickup_start_available'].$row_ticket['pickup_end_available'] != '') {
							$column .= '<br />'."Available ";
							if($row_ticket['pickup_end_available'] == '') {
								$column .= "After ".$row_ticket['pickup_start_available'];
							} else if($row_ticket['pickup_start_available'] == '') {
								$column .= "Before ".$row_ticket['pickup_end_available'];
							} else {
								$column .= "Between ".$row_ticket['pickup_start_available']." and ".$row_ticket['pickup_end_available'];
							}
						}
					}
					$column .= (in_array('address',$calendar_ticket_card_fields) ? '<br />'.$row_ticket['pickup_name'].($row_ticket['pickup_name'] != '' ? '<br />' : ' ').$row_ticket['client_name'].($row_ticket['client_name'] != '' ? '<br />' : ' ').$row_ticket['pickup_address'].($row_ticket['pickup_address'] != '' ? '<br />' : ' ').$row_ticket['pickup_city'] : '');
					$column .= '<br />'."Status: ".$status."</b>";
					$column .= "</a>";
		        }
		        if($ticket_summary != '') {
		        	$column .= '<span>Completed '.$completed_tickets.' of '.count($tickets).' '.(count($tickets) == 1 ? TICKET_NOUN : TICKET_TILE).'</span>';
		        }
		        $column .= '</div>';
		    }
	    }
	}
}
?>