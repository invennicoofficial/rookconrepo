<?php
/*
equipments Listing
*/
include ('../include.php');
checkAuthorised('driving_log');
error_reporting(0);

$view_only_mode = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `driving_log_view_only_mode` WHERE `contactid` = '".$_SESSION['contactid']."'"))['view_only_mode'];
include_once('view_only_mode.php');

if(isset($_GET['driverid'])) {
	if(strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',admin,') !== false) { 
		$login = $_GET['driverid'];
	} else {
		header('Location: driving_log_tiles.php');
		die();
	}
} else {
	$login = $_SESSION['contactid'];
}
?>
<script src="../js/jquery.cookie.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
		$(".ser-sch").hide();
        $(".ser-sch-btn").click(function() {
            $(this).next().show();
			$('html, body').animate({ scrollTop: 0 }, 'fast');
        });

		$(".close-btn").click(function() {
            $(".ser-sch").hide();
        });
});
    function tileConfig(sel) {
        var dlogid = sel.value;
        if($(".dismiss_notice").is(":checked")) {
				var turn = '1';
		} else { var turn = ''; }

		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "driving_log_ajax_all.php?fill=auditdismiss&turn="+turn+"&dlogid="+dlogid,
			dataType: "html",   //expect html to be returned
			success: function(response){
			}
		});
    }
</script>
<style>
@media(max-width:767px) {
.margin-on-mobile {
		margin-top:10px;
}
}
</style>

</head>
<body>
<?php include_once ('../navigation.php');
$approvals = approval_visible_function($dbc, 'driving_log'); ?>

<div class="container">
	<div class="row">
    <?php
        if (!empty($_GET['safetyinspectid'])) {
            echo '<div class="col-sm-12">';
            echo '<h1 style="display: inline;">Safety Checklist</h1>';
            echo '<div class="pull-right">View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-'.($view_only_mode == 1 ? '7' : '6').'.png" style="height: 2em;"></a></div>';
            echo '<div class="gap-top triple-gap-bottom"><a href="'.$_GET['from_url'].'" class="btn config-btn">Back</a></div>';
            include ('driving_log_checklist.php');
            echo '</div>';
        } else if ($_GET['showtime'] == 'audit') {
            echo '<div class="col-sm-12">';
            echo '<h1 style="display: inline;">Notices</h1>';
            echo '<div class="pull-right">View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-'.($view_only_mode == 1 ? '7' : '6').'.png" style="height: 2em;"></a></div>';
            echo '<div class="gap-top triple-gap-bottom"><a href="'.$_GET['from_url'].'" class="btn config-btn">Back</a></div>';
            include ('notices.php');
            echo '</div>';
        } else { ?>
        <div class="col-md-12">
        <h1 style="display: inline;">Driving Log</h1>
        <div class="pull-right">View Only: <a href="" class="view_only_button"><img src="../img/icons/switch-<?= $view_only_mode == 1 ? '7' : '6' ?>.png" style="height: 2em;"></a></div>

		<div class="gap-top triple-gap-bottom"><a href="driving_log_tiles.php" class="btn config-btn">Back to Dashboard</a></div>
        <form name="form_sites" method="post" action="" class="form-inline" role="form">
			<?php
                if (strpos(','.ROLE.',',',Field Operations Management,') !== false) {
                    //echo '<a href="add_driving_log.php" class="btn brand-btn mobile-block gap-bottom pull-right">Start Driving Log</a>';
                }
                
 				$vendor = '';	
				$search_for_audit_type = '';
                if (strpos(','.ROLE.',',',Executive,') !== false || strpos(','.ROLE.',',',Human Resources,') !== false || strpos(','.ROLE.',',',Accounting,') !== false || strpos(','.ROLE.',',',Field Operations Management,') !== false || strpos(','.ROLE.',',',super,') !== false || strpos(','.ROLE.',',',admin,') !== false) { ?>
                    <div class="form-group col-sm-12" style='top:-10px; position:relative;'>
                        <?php if(!isset($_GET['driverid'])) { ?>
						<label for="search_vendor" class="control-label col-sm-2">Search by Driver & Co-Driver:</label>
						<div class='col-sm-4'>
                            <select data-placeholder="Select Driver/Co-Driver" name="search_vendor" class="chosen-select-deselect form-control">
                                <option></option>
                                <?php
                                $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
                                foreach($query as $id) {
                                    $selected = '';
                                    $selected = $id == $_POST['search_vendor'] ? 'selected = "selected"' : '';
                                    echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
                                }
                                ?>
                            </select>
						</div>
						<?php } 
                            $starttimesql_timeoff = '';
							$starttimesql = '';
							$starttime = '';
							$endtime = '';
							
						if (isset($_POST['search_vendor_submit'])) {
							if (isset($_POST['search_vendor'])) {
								$vendor = filter_var($_POST['search_vendor'],FILTER_SANITIZE_STRING);
							}
						}
						
						if (isset($_POST['search_vendor_submit'])) {
							$starttime = $_POST['starttime'];
							$endtime = $_POST['endtime'];
							if(!isset($_GET['pendingstatus'])) {
								if($starttime !== '' && $endtime !== ''){
									$starttimesql = " AND (start_date >= '".$starttime."' AND start_date <= '".$endtime."') ";
								} else if($starttime !== '' && $endtime == ''){
									$starttimesql = " AND (start_date >= '".$starttime."') ";
								} else if($starttime !== '' && $endtime == ''){
									$starttimesql = " AND (start_date <= '".$endtime."') ";
								}
							} else {
								if($starttime !== '' && $endtime !== ''){
									$starttimesql = " AND (dl.start_date >= '".$starttime."' AND dl.start_date <= '".$endtime."') ";
								} else if($starttime !== '' && $endtime == ''){
									$starttimesql = " AND (dl.start_date >= '".$starttime."') ";
								} else if($starttime !== '' && $endtime == ''){
									$starttimesql = " AND (dl.start_date <= '".$endtime."') ";
								}
							}

                            if($starttime !== '' && $endtime !== ''){
                                $starttimesql_timeoff = " AND ((start_date >= '".$starttime."' AND start_date <= '".$endtime."') OR (end_date <= '".$starttime."' AND end_date >= '".$endtime."')) ";
                            } else if($starttime !== '' && $endtime == ''){
                                $starttimesql_timeoff = " AND (start_date >= '".$starttime."' OR end_date <= '".$starttime."') ";
                            } else if($starttime !== '' && $endtime == ''){
                                $starttimesql_timeoff = " AND (start_date <= '".$endtime."' OR end_date >= '".$endtime."') ";
                            }
						}
						 ?>
						
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
							<label for="site_name" class="col-sm-4 control-label" style='text-align:right;width:100%;'>From:</label>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-9">
							<input name="starttime" type="text" class="datepicker form-control" value="<?php echo $starttime; ?>"></p>
						</div>

						<!-- end time -->
						<div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
							<label for="site_name" class="col-sm-4 control-label" style='text-align:right;width:100%;'>Until:</label>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-9">
							<input name="endtime" type="text" class="datepicker form-control" value="<?php echo $endtime; ?>"></p>
						</div>
					
						<?php 
						if(isset($_GET['audit'])) { ?>
						<label for="search_vendor" class="control-label col-sm-2" style='max-width:100px;'>Audit Type:</label>
						<div class='col-sm-4'>
							<select name="search_audit" class="chosen-select-deselect form-control category_actual">
							  <option value="" selected>Filter by Audit Type</option>
							  <?php
								$active_c = '';
								$active_s = '';
								$active_d = '';
								$active_o = '';
								$active_da = '';
								if(isset($_POST['search_vendor_submit'])) {
									if($_POST['search_audit'] == 'Cycle Time') {
										$active_c = 'selected';
									} else if($_POST['search_audit'] == 'Shift Time') {
										$active_s = 'selected';
									} else if($_POST['search_audit'] == 'Drive Time') {
										$active_d = 'selected';
									} else if($_POST['search_audit'] == 'Off-Duty Time') {
										$active_o = 'selected';
									} else if($_POST['search_audit'] == 'Driving After 16 Hours') {
										$active_da = 'selected';
									}
								}
									echo "<option value='Cycle Time' ".$active_c." >Cycle Time</option>";
									echo "<option value='Drive Time' ".$active_d." >Drive Time</option>";
									echo "<option value='Driving After 16 Hours' ".$active_da." >Driving After 16 Hours</option>";
									echo "<option value='Off-Duty Time' ".$active_o." >Off-Duty Time</option>";
									echo "<option value='Shift Time' ".$active_s." >Shift Time</option>";
							  ?>
							</select>
						</div>
						<?php } ?><div class="clearfix" style='margin:10px;'>
					</div>
						<div class="form-group  class='col-sm-10 col-sm-offset-1 margin-on-mobile" >
                        <button type="submit" name="search_vendor_submit" value="Search" class="btn brand-btn">Search</button>
                   
                        <button type="submit" name="display_all_vendor" value="Display All" class="btn brand-btn">Display All</button>
                    </div>
                    </div>
                
                    
                <?php
                    if (isset($_POST['search_vendor_submit'])) {
                        if (isset($_POST['search_vendor'])) {
                            $vendor = filter_var($_POST['search_vendor'],FILTER_SANITIZE_STRING);
                        }
						
						if (isset($_POST['search_audit']) && $_POST['search_audit'] != '') {
                            $search_for_audit_type = $_POST['search_audit'];
							if($search_for_audit_type == 'Cycle Time') {
								$search_for_audit_type = 'audit_cycle_time IS NOT NULL';
							} else if($search_for_audit_type == 'Shift Time') {
								$search_for_audit_type = 'audit_shift_time IS NOT NULL';
							} else if($search_for_audit_type == 'Drive Time') {
								$search_for_audit_type = 'audit_drive_time IS NOT NULL';
							} else if($search_for_audit_type == 'Off-Duty Time') {
								$search_for_audit_type = 'audit_off_duty IS NOT NULL';
							} else if($search_for_audit_type == 'Driving After 16 Hours') {
								$search_for_audit_type = 'audit_drive_sixteen IS NOT NULL';
							} else {
								$search_for_audit_type = '';
							}
                        }
                    }
                    if (isset($_POST['display_all_vendor'])) {
                        $vendor = '';
						$search_for_audit_type = '';
                    }
                }
				$date = date('Y/m/d', time());
				$date = strtotime($date .' -4 months');
				$cut_off_date = date('Y-m-d', $date);
                if($vendor != '') {
                    
                            $query_check_credentials = "SELECT * FROM driving_log WHERE (start_date BETWEEN CURDATE() - INTERVAL 21 DAY AND CURDATE()) AND (driverid = '$vendor' OR codriverid = '$vendor') ".$starttimesql." ORDER BY drivinglogid DESC";
                    
                            $query_time_off = "SELECT * FROM driving_log_time_off WHERE (start_date BETWEEN CURDATE() - INTERVAL 21 DAY AND CURDATE()) AND (driverid = '$vendor' OR codriverid = '$vendor') ".$starttimesql_timeoff." ORDER BY start_date DESC";
					
                } else if (!isset($_GET['driverid'])){
					
							$query_check_credentials = "SELECT * FROM driving_log WHERE (start_date BETWEEN CURDATE() - INTERVAL 21 DAY AND CURDATE()) AND (driverid = '$login' OR codriverid = '$login') ".$starttimesql." ORDER BY drivinglogid DESC";
                    
                            $query_time_off = "SELECT * FROM driving_log_time_off WHERE (start_date BETWEEN CURDATE() - INTERVAL 21 DAY AND CURDATE()) AND (driverid = '$login' OR codriverid = '$login') ".$starttimesql_timeoff." ORDER BY start_date DESC";
                
				} else if(isset($_GET['pendingstatus'])) {
					
							$query_check_credentials = "SELECT dl.*, t.* FROM driving_log dl, driving_log_timer t WHERE (dl.drivinglogid = t.drivinglogid) AND (dl.driverid = '$login') AND dl.start_date >= '".$cut_off_date."' ".$starttimesql." AND t.amendments_status = 'Pending' GROUP BY dl.drivinglogid ORDER BY dl.drivinglogid DESC";
				
				} else if(isset($_GET['audit'])) {
					if(isset($_POST['search_audit']) && $search_for_audit_type != '') {
						
							$query_check_credentials = "SELECT * FROM driving_log WHERE (driverid = '$login' OR codriverid = '$login') AND start_date >= '".$cut_off_date."' ".$starttimesql." AND (".$search_for_audit_type.") ORDER BY drivinglogid DESC";
						
					} else {
					
							$query_check_credentials = "SELECT * FROM driving_log WHERE (driverid = '$login' OR codriverid = '$login') AND start_date >= '".$cut_off_date."' ".$starttimesql." AND ((audit_shift_time IS NOT NULL) OR (audit_drive_time IS NOT NULL) OR (audit_cycle_time IS NOT NULL) OR (audit_off_duty IS NOT NULL) OR (audit_drive_sixteen IS NOT NULL)) GROUP BY drivinglogid DESC";
					
					}
				} else {
							$query_check_credentials = "SELECT * FROM driving_log WHERE (driverid = '$login') AND start_date >= '".$cut_off_date."' ".$starttimesql." ORDER BY drivinglogid DESC";

                            $query_time_off = "SELECT * FROM driving_log_time_off WHERE (driverid = '$login') AND start_date >= '".$cut_off_date."' ".$starttimesql_timeoff." ORDER BY start_date DESC";

				}
                $result = mysqli_query($dbc, $query_check_credentials)  or die(mysqli_error($dbc));
                $num_rows = mysqli_num_rows($result);
                
                echo '<div id="no-more-tables">';
                if($num_rows > 0) {
					if (isset($_GET['driverid'])){
						if(isset($_GET['audit'])) {
							if(isset($_POST['search_audit'])) {
								echo 'Displaying Driving Logs with Auditing Notices for the following driver: '.get_staff($dbc, $_GET['driverid']).'.<br><br>';
							} else {
								echo 'Displaying all Driving Logs with Auditing Notices for the following driver: '.get_staff($dbc, $_GET['driverid']).'.<br><br>';
							}
						} else if (isset($_GET['pendingstatus'])) {
							echo 'Displaying all Driving Logs with Pending Statuses for the following driver: '.get_staff($dbc, $_GET['driverid']).'.<br><br>';
						}
					}
                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                        <th>Log#</th>
                        <th>Driver</th>
                        <th>Co-Driver</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Trailer</th>
                        <th>Start Date</th>
                        <th>End Date</th>
						";
						
				   echo "<th>Total KM</th>
                        <th>Safety Inspect</th>";
                        // if (strpos(','.ROLE.',',',Field Operations Management,') !== false ) {
                            echo "<th>Process</th>";
                        // }
						if (isset($_GET['driverid'])) {
                            echo "<th>Pending Statuses</th>";
							echo "<th>Auditing</th>";
                        }
                        echo "<th>Admendments/<br>Graph</th>";
                        echo "<th>Notes</th>
                        </tr>";
                } else {
                    echo "<h2>No Record Found.</h2>";
                }
                while($row = mysqli_fetch_array( $result ))
                {
                    $drivinglogid = $row['drivinglogid'];

                    $get_checklists = mysqli_fetch_all(mysqli_query($dbc,"SELECT * FROM driving_log_safety_inspect WHERE drivinglogid='$drivinglogid'"),MYSQLI_ASSOC);

                    echo '<tr>';
                    echo '<td data-title="Log #">' . $row['drivinglogid'] . '</td>';
                    echo '<td data-title="Driver">' . get_staff($dbc, $row['driverid']) . '</td>';

                    echo '<td data-title="Co-Driver">' . get_staff($dbc, $row['codriverid']) . '</td>';

                    echo '<td data-title="Customer">' .  get_client($dbc, $row['clientid']) . '</td>';

                    if (count($get_checklists) > 1) {
                        echo '<td data-title="Vehicle">';
                        foreach ($get_checklists as $checklist) {
                            echo '#'.get_equipment_field($dbc, $checklist['safety_inspect_vehicleid'], 'unit_number').'<br />';
                        }
                        echo '</td>';
                        echo '<td data-title="Trailer">';
                        foreach ($get_checklists as $checklist) {
                            echo '#'.get_equipment_field($dbc, $checklist['safety_inspect_trailerid'], 'unit_number').'<br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td data-title="Vehicle">#' . get_equipment_field($dbc, $row['vehicleid'], 'unit_number') . '</td>';
                        echo '<td data-title="Trailer">#' . get_equipment_field($dbc, $row['trailerid'], 'unit_number') . '</td>';
                    }

                    echo '<td data-title="Start Date">' . $row['start_date'] . '</td>';
                    echo '<td data-title="End Date">' . $row['end_date'] . '</td>';

                    if($row['end_date'] != '') {
                        echo '<td data-title="Total KM">';
                        foreach ($get_checklists as $checklist) {
                            $total_km = $checklist['final_odo_kms'] - $checklist['begin_odo_kms'];
                            echo $checklist['final_odo_kms'] - $checklist['begin_odo_kms'].'<br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td>-</td>';
                    }

                    if($row['vehicleid'] != 0) {
                        echo '<td data-title="Safety Inspect">';
                        foreach ($get_checklists as $checklist) {
                            echo '<a href="?safetyinspectid='.$checklist['safetyinspectid'].'&from_url='.$_SERVER['REQUEST_URI'].'">View</a><br />';
                        }
                        echo '</td>';
                    } else {
                        echo '<td data-title="Safety Inspect">-</td>';
                    }

                    $last_timer_value = $row['last_timer_value'];
                    $hash_url = '';
                    if($last_timer_value != '0') {
                        $timer_name = explode('*#*',$last_timer_value);
                        $hash_url = '#'.$timer_name[0];
                    }
                    // if (strpos(','.ROLE.',',',Field Operations Management,') !== false) {
                        echo '<td data-title="Process">';
                        if($row['status'] != 'Done') {
                            if($row['end_date'] == '') {
                                echo '<a href=\'add_driving_log.php?timer=on&drivinglogid='.$row['drivinglogid'].'\'>Timer</a>';
                            } else {
                                if($row['status'] == 'Pending') {
                                    echo '<a href=\'amendments.php?graph=off&drivinglogid='.$row['drivinglogid'].'\'>End Of Day</a>';
                                }
                            }
                        }
                        echo '</td>';
                    // }
					if (isset($_GET['driverid'])) {
						$audit = '';
						$pending = '';
						if(isset($_GET['audit'])) {
							$audit = '&audit=true';
						} else if (isset($_GET['pendingstatus'])) {
							$pending = '&pendingstatus=true';
						}
                        echo '<td data-title="Statuses">';
								$query_check_credentialsx = "SELECT * FROM driving_log_timer WHERE drivinglogid = '".$row['drivinglogid']."' AND amendments_status = 'Pending' ORDER BY level";
								$resultx = mysqli_query($dbc, $query_check_credentialsx);
								$num_rowsx = mysqli_num_rows($resultx);
								if($num_rowsx > 0 && $approvals > 0) {
								    echo '<img src="../img/warning.png" style="width:25px"> <span style="font-weight:bold; color:red;font-size:20px;">(<a style="font-weight:bold; color:red;" href="amendments.php?graph=off&drivinglogid='.$row['drivinglogid'].'&admin_view=true">'.$num_rowsx.'</a>)</span>';
								} else {
                                    echo '('.$num_rowsx.')';
								}
                        echo '</td>';
						echo '<td data-title="Auditing">';
								$query_check_credentialsx = "SELECT * FROM driving_log WHERE drivinglogid = '".$row['drivinglogid']."' AND ((audit_shift_time IS NOT NULL) OR (audit_drive_time IS NOT NULL) OR (audit_cycle_time IS NOT NULL) OR (audit_off_duty IS NOT NULL) OR (audit_drive_sixteen IS NOT NULL)) GROUP BY drivinglogid";
								$resultx = mysqli_query($dbc, $query_check_credentialsx);
								$num_rowsx = mysqli_num_rows($resultx);
								if($num_rowsx > 0 && $approvals > 0) {
									while($row22 = mysqli_fetch_array( $resultx ))
									{
										$num_of_audits = 0;
										if($row22['audit_shift_time'] !== NULL) {
											$num_of_audits++;
										}
										if($row22['audit_drive_time'] !== NULL) {
											$num_of_audits++;
										}
										if($row22['audit_cycle_time'] !== NULL) {
											$num_of_audits++;
										}
										if($row22['audit_off_duty'] !== NULL) {
											$num_of_audits++;
										}
										if($row22['audit_drive_sixteen'] !== NULL) {
											$num_of_audits++;
										}
										if($row22['audit_dismiss'] !== NULL && $row22['audit_dismiss'] !== '') {
											echo '<img src="../img/checkmark.png" style="width:25px"> <span style="font-size:20px;">(<a style="font-weight:bold;color:inherit" href="driving_log.php?driverid='.$_GET['driverid'].'&drivinglogid='.$row['drivinglogid'].$audit.$pending.'&showtime=audit&dismiss='.$row['audit_dismiss'].'&from_url='.$_SERVER['REQUEST_URI'].'">'.$num_of_audits.'</a>)</span>';
										} else {
											echo '<img src="../img/warning.png" style="width:25px"> <span style="font-weight:bold; color:red;font-size:20px;">(<a style="font-weight:bold; color:red;" href="driving_log.php?driverid='.$_GET['driverid'].'&drivinglogid='.$row['drivinglogid'].$audit.$pending.'&showtime=audit&dismiss='.$row['audit_dismiss'].'&from_url='.$_SERVER['REQUEST_URI'].'">'.$num_of_audits.'</a>)</span>';
										}
									}
								} else {
                                    echo '('.$num_rowsx.')';
								}
                        echo '</td>';
					}

                    echo '<td data-title="Amendments">';
                    if($row['end_date'] != '') {
                        if($row['status'] != 'Done') {
                            // if (strpos(','.ROLE.',',',Field Operations Management,') !== false) {
                                echo '<a href=\'amendments.php?graph=off&drivinglogid='.$row['drivinglogid'].'\'>View</a>';
                            // }
                        } else {
                            echo '<a href=\'amendments.php?graph=on&drivinglogid='.$row['drivinglogid'].'\'>View</a>';
                            if (vuaed_visible_function($dbc, 'driving_log') == 1 && $view_only_mode != 1) {
                                echo ' | <a href="amendments.php?graph=off&drivinglogid='.$row['drivinglogid'].'">Edit</a>';
                            }
                        }
                    } else {
                        echo '-';
                    }
                    echo '</td>';
                    echo '<td data-title="Notes">'.html_entity_decode($row['notes']).'</td>';
                    //echo '<td data-title="Make">' . $vehicle . '</td>';

                    echo "</tr>";
                }

                echo '</table></div>';

                //LOGGED TIME OFF
                $result = mysqli_query($dbc, $query_time_off)  or die(mysqli_error($dbc));
                $num_rows = mysqli_num_rows($result);
                if($num_rows > 0) {
                    echo '<div id="no-more-tables">';
                    echo '<h3>Logged Time Off</h3>';
                    echo "<table class='table table-bordered'>";
                    echo "<tr class='hidden-xs hidden-sm'>
                            <th>Driver</th>
                            <th>Co-Driver</th>
                            <th>Customer</th>
                            <th>Start Date</th>
                            <th>End Date</th>";
                            if ($view_only_mode != 1) {
                                echo "<th>Edit</th>";
                            }
                    echo "</tr>";
                    while($row = mysqli_fetch_array( $result ))
                    {
                        $timeoffid = $row['timeoffid'];

                        echo '<tr>';
                        echo '<td data-title="Driver">' . get_staff($dbc, $row['driverid']) . '</td>';

                        echo '<td data-title="Co-Driver">' . get_staff($dbc, $row['codriverid']) . '</td>';

                        echo '<td data-title="Customer">' .  get_client($dbc, $row['clientid']) . '</td>';

                        echo '<td data-title="Start Date">' . $row['start_date'].' '.$row['start_time'] . '</td>';
                        echo '<td data-title="End Date">' . $row['end_date'].' '.$row['end_time'] . '</td>';
                        if ($view_only_mode != 1) {
                            echo '<td data-title="Edit"><a href="add_driving_log_time_off.php?timeoffid='.$timeoffid.'&from_url='.$_SERVER['REQUEST_URI'].'">Edit</a></td>';
                        }

                        echo "</tr>";
                    }
                    echo '</table></div>';
                }
                if (strpos(','.ROLE.',',',Field Operations Management,') !== false) {
                    //echo '<a href="add_driving_log.php" class="btn brand-btn pull-right">Start Driving Log</a>';
                }
				if (!isset($_GET['driverid'])){
                echo '<a href="driving_log_tiles.php" class="btn brand-btn">Back</a>';
				} else {  echo '<a href="driving_log_admin.php" class="btn brand-btn">Back</a>'; }
							if(isset($_GET['showtime'])) {
								$drivinglogid = $_GET['drivinglogid'];
								include('notices.php');
							}
                ?>
        </form>

	    </div>
    <?php } ?>
    </div>
</div>
<?php include ('../footer.php'); ?>