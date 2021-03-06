<?php
/*
Customer Listing
*/
include ('../include.php');
?>
<script>
function setStatus(select) {
	$.post('incident_report_ajax.php?action=admin_status', { id: $(select).data('id'), status: select.value });
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('incident_report');
$view_sql = (search_visible_function($dbc,'incident_report') > 0 ? '' : " AND (CONCAT(',',`contactid`,',',`clientid`,',') LIKE '%,{$_SESSION['contactid']},%' OR `completed_by` = '{$_SESSION['contactid']}')");
$current_type = $_GET['type'];
if(!empty($current_type)) {
    $type_query = " AND `type` = '$current_type'";
}
$project_tabs = get_config($dbc, 'project_tabs');
if($project_tabs == '') {
    $project_tabs = 'Client,SR&ED,Internal,R&D,Business Development,Process Development,Addendum,Addition,Marketing,Manufacturing,Assembly';
}
$project_tabs = explode(',',$project_tabs);
$project_vars = [];
foreach($project_tabs as $item) {
    $project_vars[preg_replace('/[^a-z_]/','',str_replace(' ','_',strtolower($item)))] = $item;
}
$page_status = filter_var($_GET['status'],FILTER_SANITIZE_STRING);
?>
<div class="container">
    <div class='iframe_holder' style='display:none;'>
        <img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframe' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
        <span class='iframe_title' style='color:white; font-weight:bold; position: relative;top:58px; left: 20px; font-size: 30px;'></span>
        <iframe id="iframe_instead_of_window" style='width: 100%; overflow: hidden;' height="200px; border:0;" src=""></iframe>
    </div>
    <div class="row hide_on_iframe">
        <div class="main-screen">
            <div class="tile-header">
                <?php include('../Incident Report/tile_header.php'); ?>
            </div>

            <div class="tile-container" style="height: 100%;">
                <div class="collapsible tile-sidebar set-section-height hide-on-mobile">
                    <?php include('../Incident Report/tile_sidebar.php'); ?>
                </div>

                <div class="scale-to-fill tile-content set-section-height">
                    <div class="main-screen-white" style="height:calc(100vh - 20em); overflow-y: auto;">

                        <form name="form_sites" method="post" action="" class="form-inline" role="form">
                            <div id="no-more-tables">
                                <div class="preview-block">
                                    <div class="preview-block-header"><h4><?= empty($current_type) ? 'All '.INC_REP_TILE : $current_type ?></h4></div>
                                </div>
                            <?php
                            /* Pagination Counting */
                            $rowsPerPage = 25;
                            $pageNum = 1;

                            if(isset($_GET['page'])) {
                                $pageNum = $_GET['page'];
                            }

                            $offset = ($pageNum - 1) * $rowsPerPage;

                            if(!empty($_POST['search_incident_reports'])) {
                                $query_check_credentials = "SELECT * FROM incident_report WHERE (IFNULL(status,'') = '$page_status' OR ('$page_status' = 'Pending' AND IFNULL(`status`,'') = '')) AND `deleted`=0 $view_sql $type_query";
                            } else {
                                $query_check_credentials = "SELECT * FROM incident_report WHERE (IFNULL(status,'') = '$page_status' OR ('$page_status' = 'Pending' AND IFNULL(`status`,'') = '')) AND `deleted`=0 $view_sql $type_query LIMIT $offset, $rowsPerPage";
                                $query = "SELECT count(*) as numrows FROM incident_report WHERE (IFNULL(status,'') = '$page_status' OR ('$page_status' = 'Pending' AND IFNULL(`status`,'') = '')) AND `deleted`=0 $view_sql $type_query";
                            }

                            $result = mysqli_query($dbc, $query_check_credentials);

                            $num_rows = mysqli_num_rows($result);
                            if($num_rows > 0) {

                                // Added Pagination //
                                if(empty($_POST['search_incident_reports'])) {
                                    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                                }
                                // Pagination Finish //

                                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT incident_report_dashboard FROM field_config_incident_report"));
                                $value_config = ','.$get_field_config['incident_report_dashboard'].',';

                                echo "<table class='table table-bordered'>";
                                echo "<tr class='hidden-xs hidden-sm'>";
                                    if (strpos($value_config, ','."Program".',') !== FALSE) {
                                        echo '<th>Program</th>';
                                    }
                                    if (strpos($value_config, ','."Project Type".',') !== FALSE) {
                                        echo '<th>'.PROJECT_NOUN.' Type</th>';
                                    }
                                    if (strpos($value_config, ','."Project".',') !== FALSE) {
                                        echo '<th>'.PROJECT_NOUN.'</th>';
                                    }
                                    if (strpos($value_config, ','."Ticket".',') !== FALSE) {
                                        echo '<th>'.TICKET_NOUN.'</th>';
                                    }
                                    if (strpos($value_config, ','."Member".',') !== FALSE) {
                                        echo '<th>Member</th>';
                                    }
                                    if (strpos($value_config, ','."Client".',') !== FALSE) {
                                        echo '<th>Client</th>';
                                    }
                                    if (strpos($value_config, ','."Type".',') !== FALSE) {
                                        echo '<th>Type</th>';
                                    }
                                    if (strpos($value_config, ','."Staff".',') !== FALSE) {
                                        echo '<th>Staff</th>';
                                    }
                                    if (strpos($value_config, ','."Follow Up".',') !== FALSE) {
                                        echo '<th>Follow Up</th>';
                                    }
                                    if (strpos($value_config, ','."Date of Happening".',') !== FALSE) {
                                        echo '<th>Date of Happening</th>';
                                    }
                                    if (strpos($value_config, ','."Date of Incident".',') !== FALSE) {
                                        echo '<th>Date of Incident</th>';
                                    }
                                    if (strpos($value_config, ','."Date Created".',') !== FALSE) {
                                        echo '<th>Date Created</th>';
                                    }
                                    if (strpos($value_config, ','."Location".',') !== FALSE) {
                                        echo '<th>Location</th>';
                                    }
									if($page_status != 'Pending') {
										echo '<th>'.($page_status == 'Revision' ? 'In Revision' : ($page_status == 'Review' ? 'Under Review' : 'Approved')).'</th>';
									}
                                    if (strpos($value_config, ','."PDF".',') !== FALSE) {
                                        echo '<th>View</th>';
                                    }
                                    if(vuaed_visible_function($dbc, 'incident_report') == 1) {
                                        echo '<th>Function</th>';
                                    }
                                echo "</tr>";

                                while($row = mysqli_fetch_array( $result ))
                                {
                                    $contact_list = [];
                                    if ($row['contactid'] != '') {
                                        $contact_list[$row['contactid']] = get_staff($dbc, $row['contactid']);
                                    }
                                    $attendance_list = [];
                                    if ($row['attendance_staff'] != '') {
                                        $attendance_list = explode(',', $row['attendance_staff']);
                                    }
                                    foreach($attendance_list as $attendee) {
                                        $contact_list[] = $attendee;
                                    }
                                    if ($row['completed_by'] != '') {
                                        $contact_list[] = get_contact($dbc, $row['completed_by']);
                                    }
                                    $contact_list = array_unique($contact_list);

                                    foreach($contact_list as $contact_name) {
                                        $project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid` = '".$row['projectid']."'"));
                                        $project = get_project_label($dbc, $project);
                                        $ticket = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid` = '".$row['ticketid']."'"));
                                        $ticket = get_ticket_label($dbc, $ticket);
                                        $program = (!empty(get_client($dbc, $row['programid'])) ? get_client($dbc, $row['programid']) : get_contact($dbc, $row['programid']));
                                        $member_list = [];
										$status = $row['status'];
										
                                        foreach(explode(',',$row['memberid']) as $member) {
                                            if($member != '') {
                                                $member_list[] = !empty(get_client($dbc, $member)) ? get_client($dbc, $member) : get_contact($dbc, $member);
                                            }
                                        }
                                        $member_list = implode(', ',$member_list);
                                        $client_list = [];
                                        foreach(explode(',',$row['clientid']) as $client) {
                                            if($client != '') {
                                                $client_list[] = !empty(get_client($dbc, $client)) ? get_client($dbc, $client) : get_contact($dbc, $client);
                                            }
                                        }
                                        $client_list = implode(', ',$client_list);
                                        $project_type = $project_vars[$row['project_type']];

                                        $hidden_row = '';
                                        if(!empty($_POST['search_incident_reports'])) {
                                            $search_key = $_POST['search_incident_reports'];
                                            if(stripos($project, $search_key) === FALSE && stripos($ticket, $search_key) === FALSE && stripos($program, $search_key) === FALSE && stripos($member_list, $search_key) === FALSE && stripos($client_list, $search_key) === FALSE && stripos($contact_name, $search_key) === FALSE && stripos($project_type, $search_key) === FALSE) {
                                                $hidden_row = 'style="display: none;"';
                                            }
                                        }
                                        echo "<tr $hidden_row>";

                                        if (strpos($value_config, ','."Program".',') !== FALSE) {
                                            echo '<td data-title="Program">'.$program.'</td>';
                                        }
                                        if (strpos($value_config, ','."Project Type".',') !== FALSE) {
                                            echo '<td data-title="'.PROJECT_NOUN.' Noun">'.$project_type.'</td>';
                                        }
                                        if (strpos($value_config, ','."Project".',') !== FALSE) {
                                            echo '<td data-title="'.PROJECT_NOUN.'">'.$project.'</td>';
                                        }
                                        if (strpos($value_config, ','."Ticket".',') !== FALSE) {
                                            echo '<td data-title="'.TICKET_NOUN.'">'.$ticket.'</td>';
                                        }
                                        if (strpos($value_config, ','."Member".',') !== FALSE) {
                                            echo '<td data-title="Member">'.$member_list.'</td>';
                                        }
                                        if (strpos($value_config, ','."Client".',') !== FALSE) {
                                            echo '<td data-title="Client">'.$client_list.'</td>';
                                        }
                                        if (strpos($value_config, ','."Type".',') !== FALSE) {
                                            echo '<td data-title="Type">' . $row['type'] . '</td>';
                                        }
                                        if (strpos($value_config, ','."Staff".',') !== FALSE) {
                                            echo '<td data-title="Staff">' . $contact_name . '</td>';
                                        }
                                        if (strpos($value_config, ','."Follow Up".',') !== FALSE) {
                                            if($row['type'] == 'Near Miss') {
                                                echo '<td data-title="Follow Up">N/A</td>';
                                            } else {
                                                echo '<td data-title="Follow Up">' . $row['ir14'] . '</td>';
                                            }
                                        }
                                        if (strpos($value_config, ','."Date of Happening".',') !== FALSE) {
                                            echo '<td data-title="Date of Happening">' . $row['date_of_happening'] . '</td>';
                                        }
                                        if (strpos($value_config, ','."Date of Incident".',') !== FALSE) {
                                            echo '<td data-title="Date of Incident">' . $row['incident_date'] . '</td>';
                                        }
                                        if (strpos($value_config, ','."Date Created".',') !== FALSE) {
                                            echo '<td data-title="Date Created">' . $row['today_date'] . '</td>';
                                        }
                                        if (strpos($value_config, ','."Location".',') !== FALSE) {
                                            echo '<td data-title="Location">' . $row['location'] . '</td>';
                                        }
										if($page_status != 'Pending') {
											echo '<td data-title="'.($page_status == 'Revision' ? 'In Revision' : ($page_status == 'Review' ? 'Under Review' : 'Approved')).'">'.profile_id($dbc, $row['approved_by'], false).'</td>';
										}
                                        if (strpos($value_config, ','."PDF".',') !== FALSE) {
                                            $name_of_file = 'incident_report_'.$row['incidentreportid'].'.pdf';
											echo '<td data-title="PDF">'.(file_exists('download/'.$name_of_file) ? '<a href="download/'.$name_of_file.'" target="_blank" ><img src="'.WEBSITE_URL.'/img/pdf.png" width="16" height="16" border="0" alt="View">View</a>' : '');
                                            if ($row['revision_number'] > 0) {
                                                $revision_dates = explode('*#*', $row['revision_date']);
                                                for ($i = 0; $i < $row['revision_number']; $i++) {
                                                    $name_of_file = 'incident_report_'.$row['incidentreportid'].'_'.($i+1).'.pdf';
													if(file_exists('download/'.$name_of_file)) {
														echo '<br /><a href="download/'.$name_of_file.'" target="_blank" ><img src="'.WEBSITE_URL.'/img/pdf.png" width="16" height="16" border="0" alt="view">View R'.($i+1).': '.$revision_dates[$i].'</a>';
													}
                                                }
                                            }
                                            echo '</td>';
                                        }
										echo '<td data-title="Function">
											<select name="status" class="chosen-select-deselect" data-id="'.$row['incidentreportid'].'" onchange="setStatus(this);"><option />
												<option '.(empty($status) || $status == 'Pending' ? 'selected' : '').' value="Pending">Pending</option>
												<option '.($status == 'Revision' ? 'selected' : '').' value="Revision">In Revision</option>
												<option '.($status == 'Review' ? 'selected' : '').' value="Review">In Review</option>
												<option '.($status == 'Done' ? 'selected' : '').' value="Done">Approved</option>
											</select>
										</td>';

                                        echo "</tr>";
                                    }
                                }

                                echo '</table></div>';
                                // Added Pagination //
                                if(empty($_POST['search_incident_reports'])) {
                                    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                                }
                                // Pagination Finish //
                            } else {
                                echo "<h2>No Record Found.</h2>";
                            }

                            ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
