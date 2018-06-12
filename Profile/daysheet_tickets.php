<!-- Daysheet My Tickets-->
<?php

    if(!empty($_GET['date_display'])) {
        $ptd = $_GET['date_display'];
        if($_GET['date_display'] == 'weekly') {
            $where = 'yearweek(DATE(t.to_do_date), 1) = yearweek(curdate(), 1)';
        } else if($_GET['date_display'] == 'monthly') {
            $where = 'MONTH(t.to_do_date) = MONTH(CURRENT_DATE()) AND YEAR(t.to_do_date) = YEAR(CURRENT_DATE())';
        } else {
            $where = 'DATE(t.to_do_date) = DATE(NOW())';
        }
    }

    $ticket_tabs = [];
    foreach(array_filter(explode(',',get_config($dbc, 'ticket_tabs'))) as $ticket_tab) {
        $ticket_tabs[config_safe_str($ticket_tab)] = $ticket_tab;
    }
    $_GET['tile_name'] = @filter_var($_GET['tile_name'],FILTER_SANITIZE_STRING);
    $tile_security = get_security($dbc, ($_GET['tile_name'] == '' ? 'ticket' : 'ticket_type_'.$_GET['tile_name']));
    $ticket_type = isset($_GET['type']) ? filter_var($_GET['type'],FILTER_SANITIZE_STRING) : $_GET['tile_name'];
    $db_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `tickets_dashboard` FROM `field_config`"))['tickets_dashboard'];
    if($db_config == '') {
        $db_config = 'Business,Contact,Heading,Services,Status,Deliverable Date';
    }
    $db_config = explode(',',$db_config);
	$equipment = [];
	$equipment_ids = $dbc->query("SELECT `equipmentid` FROM `equipment_assignment_staff` LEFT JOIN `equipment_assignment` ON `equipment_assignment_staff`.`equipment_assignmentid`=`equipment_assignment`.`equipment_assignmentid` WHERE `equipment_assignment_staff`.`deleted`=0 AND `equipment_assignment`.`deleted`=0 AND `equipment_assignment_staff`.`contactid`='$contactid'");
	while($equipment[] = $equipment_ids->fetch_assoc()['equipmentid']) { }
	$equipment = implode(',',array_filter($equipment));
	if($equipment == '') {
		$equipment = 0;
	}
    $tickets_list = "SELECT t.*, c.name, s.location_name, s.client_name, s.id FROM tickets t LEFT JOIN contacts c ON t.businessid=c.contactid LEFT JOIN ticket_schedule s ON t.ticketid=s.ticketid and s.deleted=0 WHERE t.status != 'Archive' AND t.deleted = 0 $search_type AND (t.contactid LIKE '%," . $contactid . ",%' OR internal_qa_contactid LIKE '%," . $contactid . ",%' OR deliverable_contactid LIKE '%," . $contactid . ",%') AND ".$where." ORDER BY ticketid DESC";

    //$tickets_list = "SELECT t.*, c.name, s.location_name, s.client_name, s.id FROM tickets t LEFT JOIN contacts c ON t.businessid=c.contactid LEFT JOIN ticket_schedule s ON t.ticketid=s.ticketid and s.deleted=0 WHERE t.status != 'Archive' AND t.deleted = 0 $search_type AND (t.contactid LIKE '%," . $contactid . ",%' OR internal_qa_contactid LIKE '%," . $contactid . ",%' OR deliverable_contactid LIKE '%," . $contactid . ",%' OR '".$contactid."' = '' OR t.equipmentid IN ($equipment) OR s.equipmentid IN ($equipment)) ORDER BY ticketid DESC";

    $result = mysqli_query($dbc, $tickets_list) or die(mysqli_error($dbc));

    if(!$result) {
        echo "Search query is currently unavailable, please contact your server admin...";
    }
    $num_rows = mysqli_num_rows($result);
?>
    <div class="col-xs-12">
        <div class="weekly-div" style="overflow-y: hidden;">
            <?php if($num_rows > 0) {
                echo 'Displaying a total of '.$num_rows.' '.TICKET_TILE.'.';
                echo '<div id="no-more-tables"><table class="table table-bordered">';
                echo '<tr class="hidden-xs hidden-sm">
						<th>'.TICKET_NOUN.' #</th>
						'.(in_array('Project',$db_config) ? '<th>'.PROJECT_NOUN.' Information</th>' : '').'
						'.(in_array('Business',$db_config) || in_array('Business',$db_config) ?
							('<th>'.(in_array('Business',$db_config) ? 'Business<br>' : '').
							(in_array('Contact',$db_config) ? 'Contact' : '').'</th>') : '').'
						'.(in_array('Services',$db_config) ? '<th>Service</th>' : '').'
						'.(in_array('Heading',$db_config) ? '<th>'.TICKET_NOUN.' Heading</th>' : '').'
						'.(in_array('Staff',$db_config) ? '<th>Staff</th>' : '').'
						'.(in_array('Create Date',$db_config) ? '<th>Created Date</th>' : '').'
						'.(in_array('Ticket Date',$db_config) ? '<th>Date</th>' : '').'
						'.(in_array('Deliverable Date',$db_config) ? '<th>TO DO</th>
							<th>Internal QA</th>
							<th>Deliverable</th>' : '').'
						'.(in_array('Documents',$db_config) ? '<th>Documents</th>' : '').'
						'.(in_array('Status',$db_config) ? '<th>Current Status</th>' : '').'
                    </tr>';
                while($row = mysqli_fetch_array( $result )) {
                    echo '<tr>
                            <td data-title="'.TICKET_NOUN.' #">'.($tile_security['edit'] == 1 ? '<a href=\'../Ticket/index.php?edit='.$row['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'&stop='.$row['stop_id'].'\'>'.$row['ticketid'].'</a>' : $row['ticketid']).'</td>';
					if(in_array('Project',$db_config)) {
						echo '<td data-title="'.PROJECT_NOUN.' Information">'.($tile_security['edit'] == 1 ? '<a href=\'../Ticket/index.php?edit='.$row['ticketid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'\'>' : '');
							if($row['projectid'] > 0) {
								$project = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid`='".$row['projectid']."'"));
								echo PROJECT_NOUN.' #'.$project['projectid'].' '.$project['project_name'].'<br />'.$project_types[$project['projecttype']];
							} else {
								echo 'No '.PROJECT_NOUN;
							}
						echo ($tile_security['edit'] == 1 ? '</a>' : '').'</td>';
					}
                    if(in_array('Business',$db_config) || in_array('Business',$db_config)) {
                        echo '<td data-title="'.(in_array('Business',$db_config) ? 'Business ' : '').(in_array('Contact',$db_config) ? 'Contact' : '').'">';
                        echo (in_array('Business',$db_config) ? get_contact($dbc,$row['businessid'],'name').'<br />' : '');
                        if(in_array('Contact',$db_config)) {
                            foreach(array_filter(explode(',',$row['clientid'])) as $clientid) {
                                echo get_contact($dbc, $clientid).'<br />';
                            }
                        }
                        echo '</td>';
                    }
                    if(in_array('Services',$db_config)) {
                        echo '<td data-title="Service">';
                        foreach(array_filter(explode(',',$row['serviceid'])) as $service) {
                            $service = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `category`, `heading` FROM `services` WHERE `serviceid`='$service'"));
                            echo ($service['category'] == '' ? '' : $service['category'].': ').$service['heading'].'<br />';
                        }
                        echo '</td>';
                    }
                    echo (in_array('Heading',$db_config) ? '<td data-title="'.TICKET_NOUN.' Heading">'.$row['heading'].($row['location_name'] != '' ? ' - '.$row['location_name'] : ($row['client_name'] != '' ? ' - '.$row['client_name'] : '')).'</td>' : '');
                    if(in_array('Staff',$db_config)) {
                        echo '<td data-title="Staff">';
                        foreach(array_filter(explode(',',$row['contactid'])) as $staff) {
                            echo get_contact($dbc, $staff).'<br />';
                        }
                        foreach(array_filter(explode(',',$row['internal_qa_contactid'])) as $staff) {
                            echo get_contact($dbc, $staff).' (Internal QA)<br />';
                        }
                        foreach(array_filter(explode(',',$row['deliverable_contactid'])) as $staff) {
                            echo get_contact($dbc, $staff).' (Deliverable)<br />';
                        }
                        echo '</td>';
                    }
					if(in_array('Create Date',$db_config)) {
						echo '<td data-title="Date Created">'.$row['created_date'].'</td>';
					}
					if(in_array('Ticket Date',$db_config)) {
						echo '<td data-title="Date">';
							$dates = mysqli_query($dbc, "SELECT * FROM `ticket_schedule` WHERE IFNULL(`to_do_date`,'0000-00-00')!='0000-00-00' AND `ticketid`='".$row['ticketid']."'");
							if($dates->num_rows > 0) {
								while($date_row = $dates->fetch_assoc()) {
									switch($date_row['type']) {
										case 'origin': echo 'Shipment Date: '; break;
										case 'destination': echo 'Delivery Date: '; break;
										case '': break;
										default: echo $date_row['type'].': '; break;
									}
									echo $date_row['to_do_date']."<br />\n";
								}
							} else {
								echo $row['to_do_date'];
							}
						echo '</td>';
					}
                    if(in_array('Deliverable Date',$db_config)) {
                        echo '<td data-title="TO DO">'.($row['to_do_date'] == '' ? '' : $row['to_do_date'].'<br />');
                        foreach(array_filter(explode(',', $row['contactid'])) as $staff) {
                            echo get_contact($dbc, $staff).'<br />';
                        }
                        echo $row['max_time'].'</td>';
                        echo '<td data-title="Internal QA">'.($row['internal_qa_date'] == '' ? '' : $row['internal_qa_date'].'<br />');
                        foreach(array_filter(explode(',', $row['internal_qa_contactid'])) as $staff) {
                            echo get_contact($dbc, $staff).'<br />';
                        }
                        echo '</td>';
                        echo '<td data-title="Deliverable">'.($row['deliverable_date'] == '' ? '' : $row['deliverable_date'].'<br />');
                        foreach(array_filter(explode(',', $row['deliverable_contactid'])) as $staff) {
                            echo get_contact($dbc, $staff).'<br />';
                        }
                        echo '</td>';
                    }
                    if(in_array('Documents',$db_config)) {
                        echo '<td data-title="Documents">';
							$documents = mysqli_query($dbc, "SELECT IFNULL(NULLIF(`label`,''),`document`) `label`, CONCAT('download/',`document`) `link` FROM `ticket_document` WHERE `ticketid`='".$row['ticketid']."' AND `deleted`=0 AND IFNULL(`document`,'') != '' UNION
								SELECT CONCAT('Project: ',IFNULL(NULLIF(`label`,''),`upload`)) `label`, CONCAT('../Project/download',`upload`) `link` FROM `project_document` WHERE `projectid`='".$row['projectid']."' AND `deleted`=0 AND IFNULL(`upload`,'') != ''");
							while($document = $documents->fetch_assoc()) {
								echo '<a href="'.$document['link'].'">'.$document['label']."</a><br />\n";
							}
                        echo '</td>';
                    }
                    if(in_array('Status',$db_config)) {
                        echo '<td data-title="Current Status">';
                            echo $row['status'];
                        echo '</td>';
                    }

                    echo '</tr>';
                }

                echo '</table></div>';
            } else {
                echo "<h2>No Record Found.</h2>";
            } ?>
        </div>
    </div>