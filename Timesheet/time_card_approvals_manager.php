<?php include('../include.php');
include('../Calendar/calendar_functions_inc.php'); ?>
<script type="text/javascript" src="timesheet.js"></script>
<script type="text/javascript">
function useProfileSig(chk) {
	if($(chk).is(':checked')) {
		$('.profile_sig_box').show();
		$('.timesheet_sig_box').hide();
	} else {
		$('.profile_sig_box').hide();
		$('.timesheet_sig_box').show();
	}
}
</script>
</head>
<body>
<?php
include_once ('../navigation.php');
checkAuthorised('timesheet');
include 'config.php';
$value = $config['settings']['Choose Fields for Time Sheets Dashboard']; ?>

<script type="text/javascript">
$(document).ready(function() {
	$('.timesheet_form').submit(function() {
		$('[name="approve_date_id[]"]:not(:checked),[name^="approvedateid"]:not(:checked)').each(function() {
			var name = $(this).prop('name');
			$(this).after('<input type="hidden" name="'+name+'" value="UNCHECKED_PLACEHOLDER">');
		});
	});
});
function approveAll(chk, all = '') {
	if($(chk).is(':checked')) {
		if(all == 'ALL') {
			$('[name="approve_date_id[]"],[name^="approvedateid"],[name="approve_date[]"]').prop('checked', true);
		} else {
			$(chk).closest('table').find('[name="approve_date_id[]"],[name^="approvedateid"],[name="approve_date[]"]').prop('checked', true);
		}
	}
}
function viewTicket(a) {
	var ticketid = $(a).data('ticketid');
	overlayIFrameSlider('<?= WEBSITE_URL ?>/Ticket/edit_tickets.php?edit='+ticketid+'&calendar_view=true','auto',false,true, $('#timesheet_div').outerHeight());
}
function send_csv(a) {
	$('[name=import_csv_file]').change(function() {
		$(this).closest('form').submit();
	});
	$('[name=import_csv_file]').click();
}
</script>

<div class="container" id="timesheet_div">
	<div class="iframe_overlay" style="display:none; margin-top: -20px;margin-left:-15px;">
		<div class="iframe">
			<div class="iframe_loading">Loading...</div>
			<iframe name="timesheet_iframe" src=""></iframe>
		</div>
	</div>
    <div class="row timesheet_div">
    	<input type="hidden" name="timesheet_time_format" value="<?= get_config($dbc, 'timesheet_time_format') ?>">
        <div class="col-md-12">

        <h1 class=""><?= !empty(get_config($dbc, 'timesheet_manager_approvals')) ? get_config($dbc, 'timesheet_manager_approvals') : 'Manager Approval' ?> Dashboard
        <?php
        if(config_visible_function_custom($dbc)) {
            echo '<a href="field_config.php?from_url=time_card_approvals_manager.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        <img class="no-toggle statusIcon pull-right no-margin inline-img small" title="" src="" data-original-title=""></h1>

        <form id="form1" name="form1" method="GET" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
			<input type="hidden" name="tab" value="<?= $_GET['tab'] ?>">
			<?php echo get_tabs('Manager Approvals', $_GET['tab'], array('db' => $dbc, 'field' => $value['config_field'])); ?>
        <br><br>
        <?php
            $search_site = '';
            $search_staff_list = '';
            $search_start_date = '';
            $search_end_date = '';
			$position = '';

            if(!empty($_GET['search_site'])) {
                $search_site = $_GET['search_site'];
            }
            if(!empty($_GET['search_staff'])) {
                $search_staff_list = $_GET['search_staff'];
            }
			if(!empty($_GET['search_start_date'])) {
				$search_start_date = $_GET['search_start_date'];
			}
			if(!empty($_GET['search_end_date'])) {
				$search_end_date = $_GET['search_end_date'];
			}
			$current_period = isset($_GET['pay_period']) ? $_GET['pay_period'] : -1;
			$_GET['pay_period'] = $current_period;
			include('pay_period_dates.php');

			$timesheet_security_roles = array_filter(explode(',',get_config($dbc, 'timesheet_security_roles')));
		    if(!empty($timesheet_security_roles)) {
		        $security_query = [];
		        foreach($timesheet_security_roles as $security_role) {
		            $security_query[] =  "CONCAT(',',`contacts`.`role`,',') LIKE '%,$security_role,%'";
		        }
		        $security_query = " AND (".implode(" OR ", $security_query).")";
		    }
			?>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                  <label for="site_name" class="control-label">Search By Staff:</label>
                </div>
                  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                      <select data-placeholder="Select Staff Members" multiple name="search_staff[]" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
                      <option value=""></option>
                      <option value="ALL_STAFF">Select All Staff</option>
                      <?php
                        $query = mysqli_query($dbc,"SELECT `supervisor`, `position`, `staff_list`, `security_level_list` FROM `field_config_supervisor` WHERE `supervisor`='".$_SESSION['contactid']."' OR (SELECT CONCAT(',',`staff_list`,',') FROM `field_config_supervisor` WHERE `supervisor`='".$_SESSION['contactid']."' AND `position` = 'Manager') LIKE CONCAT('%,',`supervisor`,',%')");
						$staff_members = [];
						if(mysqli_num_rows($query) > 0) {
							while($row1 = mysqli_fetch_array($query)) {
								if($row1['supervisor'] == $_SESSION['contactid']) {
									$position = $row1['position'];
								}
								$staff_members = array_unique(array_merge($staff_members, array_filter(explode(',',$row1['staff_list']))));
								$security_levels = array_filter(explode(',',$row1['security_level_list']));
								if(!empty($security_levels)) {
									foreach($security_levels as $security_level) {
										if(!empty($security_level)) {
											$staff_with_security = array_column(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted` = 0 AND `status` > 0 AND `contactid` IN (SELECT `staff` FROM `time_cards`) AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND CONCAT(',',`role`,',') LIKE '%,".$security_level.",%'")),'contactid');
											$staff_members = array_unique(array_merge($staff_members, array_filter($staff_with_security)));
										}
									}
								}
							}
							$staff_members_ids = $staff_members;
							$staff_members = [];
							foreach($staff_members_ids as $staff_members_id) {
								$staff_members[] = ['contactid' => $staff_members_id, 'full_name' => get_contact($dbc,$staff_members_id)];
							}
						} else {
							$staff_members = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted` = 0 AND `status` > 0 AND `contactid` IN (SELECT `staff` FROM `time_cards`) AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY.$security_query));
						}
						foreach($staff_members as $staff_id) { ?>
							<option <?php if (in_array($staff_id['contactid'], $search_staff_list) || in_array('ALL_STAFF',$search_staff_list)) { echo " selected"; } ?> value='<?php echo $staff_id['contactid']; ?>'><?php echo $staff_id['full_name']; ?></option>
							<?php if(in_array('ALL_STAFF',$search_staff_list)) {
								$search_staff_list[] = $staff_id['contactid'];
							}
						} ?>
                    </select>
                  </div>

        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                  <label for="site_name" class="control-label">Search By Site:</label>
                </div>
                  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                      <select data-placeholder="Select a Site" name="search_site" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
                      <option value=""></option>
                      <?php
                        $query = mysqli_query($dbc,"SELECT `contactid`, CONCAT(IFNULL(`site_name`,''),IF(IFNULL(`site_name`,'') != '' AND IFNULL(`display_name`,'') != '',': ',''),IFNULL(`display_name`,'')) display_name FROM `contacts` WHERE `category`='Sites' AND `deleted`=0 ORDER BY display_name");
                        while($row1 = mysqli_fetch_array($query)) {
                            if($row1['display_name'] != '') {
							?><option <?php if ($row1['contactid'] == $search_site) { echo " selected"; } ?> value='<?php echo  $row1['contactid']; ?>' ><?php echo $row1['display_name']; ?></option>
						<?php } } ?>
                    </select>
                  </div>
				  <div class="clearfix"></div>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                  <label for="site_name" class="control-label">Search By Start Date:</label>
                </div>
                  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                      <input style="width: 100%;" name="search_start_date" value="<?php echo $search_start_date; ?>" type="text" class="form-control datepicker">
                  </div>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                  <label for="site_name" class="control-label">Search By End Date:</label>
                </div>
                  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                      <input style="width: 100%;" name="search_end_date" value="<?php echo $search_end_date; ?>" type="text" class="form-control datepicker">
                  </div>


                <div class="form-group">
                  <label for="site_name" class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
					<?php if(count($search_staff_list) == 1 && $search_staff_list[0] != 'ALL_STAFF' && !empty($search_staff_list)) { ?>
						<a href="?tab=<?= $_GET['tab'] ?>&pay_period=<?= $current_period + 1 ?>&search_site=<?= $search_site ?>&search_staff[]=<?= $search_staff_list[0] ?>" name="display_all_inventory" class="btn brand-btn mobile-block pull-right">Next <?= $pay_period_label ?></a>
						<a href="?tab=<?= $_GET['tab'] ?>&pay_period=<?= $current_period - 1 ?>&search_site=<?= $search_site ?>&search_staff[]=<?= $search_staff_list[0] ?>" name="display_all_inventory" class="btn brand-btn mobile-block pull-right">Prior <?= $pay_period_label ?></a>
					<?php } ?>
                    <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
                    <button type="button" onclick="$('[name^=search_staff]').find('option').prop('selected',false); $('[name^=search_staff]').find('option[value=ALL_STAFF]').prop('selected',true).change(); $('[name=search_user_submit]').click(); return false;" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
                  </div>
                </div>
</form>
        <br><br><br>

			<?php if(get_config($dbc, 'timesheet_approval_import_export') == '1') { ?>
             <form id="form_csv" name="form_csv" action="time_cards_csv.php?import_csv=1&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
				<div class="pull-right">
					<a href="time_cards_csv.php?<?= http_build_query($_GET) ?>&approv=N&approv_type=Manager&export_csv=1" class="btn brand-btn pull-right">Export CSV</a>
					<a href="" class="btn brand-btn pull-right" onclick="send_csv(this); return false;">Import CSV</a>
					<input type="file" name="import_csv_file" style="display:none;">
				</div>
				<div class="clearfix"></div>
			</form>
			<?php } ?>

			 <form id="form1" name="form1" action="add_time_card_approvals.php?tab=<?= $_GET['tab'] ?>&pay_period=<?= $_GET['pay_period'] ?>&search_start_date=<?= $_GET['search_start_date'] ?>&search_end_date=<?= $_GET['search_end_date'] ?>&search_site=<?= $_GET['search_site'] ?>" method="POST" enctype="multipart/form-data" class="form-horizontal timesheet_form" role="form">

            <div id="no-more-tables">
            <?php $value_config = explode(',',get_field_config($dbc, 'time_cards'));
			if(!in_array('reg_hrs',$value_config) && !in_array('direct_hrs',$value_config) && !in_array('payable_hrs',$value_config)) {
				$value_config = array_merge($value_config,['reg_hrs','extra_hrs','relief_hrs','sleep_hrs','sick_hrs','sick_used','stat_hrs','stat_used','vaca_hrs','vaca_used']);
			} ?>

			<?php if(in_array('approve_all', $value_config)) { ?>
	            <div class="pull-right">
	            	<label><input type="checkbox" name="approve_all_staff" onclick="approveAll(this, 'ALL');"> Select All Approve Checkboxes</label>
	            </div>
	        	<div class="clearfix"></div>
			<?php } ?>

            <?php
            $grid = '';
            $printable_grid = '';

            $tb_field = $value['config_field'];
            if($search_staff_list != '') {
				foreach(array_filter(array_unique($search_staff_list), function($id) { return $id != 'ALL_STAFF'; }) as $search_staff) {
					if(count($search_staff_list) > 1) {
						echo "<h2>".get_contact($dbc, $search_staff)."</h2>";
					}
					$filter = ' AND (staff = "'.$search_staff.'")';
					if($search_site != '') {
						$filter .= ' AND (business = "'.$search_site.'")';
					}
					if($search_start_date != '') {
						$filter .= ' AND `date` >= "'.$search_start_date.'"';
					}
					if($search_end_date != '') {
						$filter .= ' AND `date` <= "'.$search_end_date.'"';
					}


					$query_check_credentials = 'SELECT * FROM time_cards WHERE approv = "N" AND `deleted`=0 '.$filter;

					$result = mysqli_query($dbc, $query_check_credentials);

					$schedule = mysqli_fetch_array(mysqli_query($dbc, "SELECT `scheduled_hours`, `schedule_days` FROM `contacts` WHERE `contactid`='$search_staff'"));
					$schedule_hrs = explode('*',$schedule['scheduled_hours']);
					$schedule_days = explode('*',$schedule['schedule_days']);
					$schedule_list = [0=>'---',1=>'---',2=>'---',3=>'---',4=>'---',5=>'---',6=>'---'];
					foreach($schedule_days as $key => $day_of_week) {
						$schedule_list[$day_of_week] = $schedule_hrs[$key];
					}

					$start_of_year = date('Y-01-01', strtotime($search_start_date));
					$sql = "SELECT IFNULL(SUM(IF(`type_of_time`='Sick Hrs.Taken',`total_hrs`,0)),0) SICK_HRS,
						IFNULL(SUM(IF(`type_of_time`='Stat Hrs.',`total_hrs`,0)),0) STAT_AVAIL,
						IFNULL(SUM(IF(`type_of_time`='Stat Hrs.Taken',`total_hrs`,0)),0) STAT_HRS,
						IFNULL(SUM(IF(`type_of_time`='Vac Hrs.',`total_hrs`,0)),0) VACA_AVAIL,
						IFNULL(SUM(IF(`type_of_time`='Vac Hrs.Taken',`total_hrs`,0)),0) VACA_HRS
						FROM `time_cards` WHERE `staff`='$search_staff' AND `date` < '$search_start_date' AND `date` >= '$start_of_year' AND `approv`='N' AND `deleted`=0";
					$year_to_date = mysqli_fetch_array(mysqli_query($dbc, $sql));

					$stat_hours = $year_to_date['STAT_AVAIL'];
					$stat_taken = $year_to_date['STAT_HRS'];
					$vacation_hours = $year_to_date['VACA_AVAIL'];
					$vacation_taken = $year_to_date['VACA_HRS'];
					$sick_taken = $year_to_date['SICK_HRS'];

					echo '<input type="hidden" name="supervisor_id" value="'.$_SESSION['contactid'].'">';
					echo '<input type="hidden" name="supervisor" value="'.$position.'">';
					echo '<input type="hidden" name="staff_id" value="'.$search_staff.'">';
					echo '<input type="hidden" name="site_id" value="'.$search_site.'">';
					$layout = get_config($dbc, 'timesheet_layout'); ?>
					<?php if(in_array($layout, ['', 'multi_line', 'position_dropdown', 'ticket_task'])): ?>
						<script>
						$(document).ready(function() {
							setAddRemove();
						});
						function setAddRemove() {
							$('.add-row').click(function() {
								var post_i = $('[name="post_i_counter"]').val();
								var line = $(this).closest('tr');
								destroyInputs('#no-more-tables');
								var new_line = line.clone();
								new_line.find('input').val('');
								new_line.find('span').remove();
								new_line.find('input').each(function() {
									var input_name = this.name;
									if(input_name.indexOf('_') != -1) {
										input_name = input_name.split('_');
										input_name.pop();
										input_name.push(post_i);
										input_name = input_name.join('_');
										$(this).attr('name',input_name);
									}
								});

								line.after(new_line);
								initInputs('#no-more-tables');
								$('[name="post_i_counter"]').val(parseInt(post_i)+1);
								setAddRemove();
							});
							$('.rem-row').click(function() {
								var line = $(this).closest('tr');
								line.after('<input type="hidden" name="delete_time_cards[]" value="'+$(line).find('[name^="timecardid"]').val()+'">');
								line.hide();
							});
						}
						$(document).ready(function() {
							initLines();
							checkTimeOverlaps();
						});
						$(document).on('change', '[name="start_time[]"],[name="end_time[]"]', function() { checkTimeOverlaps(); });
						function getTasks(sel) {
							var tasks = $(sel).find('option:selected').data('tasks');
							var tasks_sel = $(sel).closest('tr').find('[name="type_of_time[]"]');
							var tasks_html = '<option></option>';
							if(tasks != undefined) {
								tasks.forEach(function(task) {
									tasks_html += '<option value="'+task+'">'+task+'</option>';
								});
							}
							$(tasks_sel).html(tasks_html).trigger('change.select2');
							if($(sel).val() != undefined && $(sel).val() != '') {
								$(sel).closest('tr').find('.view_ticket').data('ticketid', $(sel).val()).show();
							} else {
								$(sel).closest('tr').find('.view_ticket').data('ticketid', '').hide();
							}
						}
						function checkDrivingTime(chk) {
							var block = $(chk).closest('tr');
							if($(chk).is(':checked')) {
								$(block).find('.ticket_task_td').each(function() {
									$(this).find('select').val('').trigger('change');
									$(this).addClass('readonly-block');
								});
							} else {
								$(block).find('.ticket_task_td').removeClass('readonly-block');
							}
						}
						function initLines() {
							$('.add-row').off('click').click(function() {
								var line = $(this).closest('tr');
								destroyInputs('#no-more-tables');
								var new_line = line.clone();
								new_line.find('input:not([name="date[]"],[name="staff[]"],[type="checkbox"])').val('');
								new_line.find('input.driving_time').prop('checked',false);
								new_line.find('.ticket_task_td').removeClass('readonly-block');
								new_line.find('select').val('');
								new_line.find('span').remove();
								line.after(new_line);
								initInputs('#no-more-tables');
								initLines();
							});
							$('.rem-row').off('click').click(function() {
								var line = $(this).closest('tr');
								line.find('[name^=deleted]').val(1).change();
								line.hide();
							});
							$('.comment-row').off('click').click(function() {
								var line = $(this).closest('tr');
								line.find('[name^=comment_box]').show().focus();
							});
						}
						function checkTimeOverlaps() {
							<?php if(in_array('time_overlaps',$value_config)) { ?>
								$('.timesheet_div table tr').css('background-color', '');
								var time_list = [];
								var date_list = [];
								$('.timesheet_div table').each(function() {
									$(this).find('tr').each(function() {
										var date = $(this).find('[name="date[]"]').val();
										if(time_list[date] == undefined) {
											time_list[date] = [];
										}
										if(date_list.indexOf(date) == -1) {
											date_list.push(date);
										}

										var start_time = '';
										var end_time = '';
										if($(this).find('[name="start_time[]"]').val() != undefined && $(this).find('[name="start_time[]"]').val() != '' && $(this).find('[name="end_time[]"]').val() != undefined && $(this).find('[name="end_time[]"]').val() != '') {
											time_list[date].push($(this));
										}
									});
								});
								date_list.forEach(function(date) {
									time_list[date].forEach(function(tr) {
										$(tr).data('current_row', 1);
										start_time = new Date(date+' '+$(tr).find('[name="start_time[]"]').val());
										end_time = new Date(date+' '+$(tr).find('[name="end_time[]"]').val());
										time_list[date].forEach(function(tr2) {
											if($(tr2).data('current_row') != 1) {
												start_time2 = new Date(date+' '+$(tr2).find('[name="start_time[]"]').val());
												end_time2 = new Date(date+' '+$(tr2).find('[name="end_time[]"]').val())
												if((start_time.getTime() > start_time2.getTime() && start_time.getTime() < end_time2.getTime()) || (end_time.getTime() > start_time2.getTime() && end_time.getTime() < end_time2.getTime())) {
													$(tr).css('background-color', 'red');
												}
											}
										});
										$(tr).data('current_row', 0);
									});
								});
							<?php } ?>
						}
						</script>
						<?php
						echo '<button type="submit" name="approv_db" value="approv_btn" class="btn brand-btn pull-right">Update and Approve Selected</button>';
						echo '<button type="submit" name="approv_db" value="update_btn" class="btn brand-btn pull-right">Update Hours</button>'; ?>

						<table class='table table-bordered'>
						<tr class='hidden-xs hidden-sm'>
							<td colspan="2">Balance Forward Y.T.D.</td>
							<?php if(in_array('schedule',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('scheduled',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('ticketid',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('start_time',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('end_time',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('start_time_editable',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('end_time_editable',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('total_tracked_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('planned_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('tracked_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('start_day_tile',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('total_tracked_time',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if($layout == 'ticket_task') { $total_colspan++; ?><th style='text-align:center; vertical-align:bottom; width:12em;'></th><th style='text-align:center; vertical-align:bottom; width:12em;'></th>
							<?php } else if($layout == 'position_dropdown') { ?><th style='text-align:center; vertical-align:bottom; width:12em;'></th><?php } ?>
							<?php if(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('start_day_tile_separate',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('extra_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('relief_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('sleep_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('training_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('sick_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('sick_used',$value_config)) { ?><td style='text-align:center;'><?php echo $sick_taken; ?></td><?php } ?>
							<?php if(in_array('stat_hrs',$value_config)) { ?><td style='text-align:center;'><?php echo $stat_hours; ?></td><?php } ?>
							<?php if(in_array('stat_used',$value_config)) { ?><td style='text-align:center;'><?php echo $stat_taken; ?></td><?php } ?>
							<?php if(in_array('vaca_hrs',$value_config)) { ?><td style='text-align:center;'><?php echo $vacation_hours; ?></td><?php } ?>
							<?php if(in_array('vaca_used',$value_config)) { ?><td style='text-align:center;'><?php echo $vacation_taken; ?></td><?php } ?>
							<?php if(in_array('breaks',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<?php if(in_array('view_ticket',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
							<td colspan="<?= in_array('comment_box',$value_config) ? 2 : 1 ?>"></td>
						</tr>
						<tr class='hidden-xs hidden-sm'>
							<th style='text-align:center; vertical-align:bottom; width:8em;'><div>Date</div></th>
							<?php if(in_array('schedule',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Schedule</div></th><?php } ?>
							<?php if(in_array('scheduled',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Scheduled Hours</div></th><?php } ?>
							<?php if(in_array('ticketid',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div><?= TICKET_NOUN ?></div></th><?php } ?>
							<?php if(in_array('show_hours',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Hours</div></th><?php } ?>
							<?php if(in_array('start_time',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Start<br />Time</div></th><?php } ?>
							<?php if(in_array('end_time',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>End<br />Time</div></th><?php } ?>
							<?php if(in_array('start_time_editable',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Start<br />Time</div></th><?php } ?>
							<?php if(in_array('end_time_editable',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>End<br />Time</div></th><?php } ?>
							<?php if(in_array('total_tracked_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Total Tracked<br />Hours</div></th><?php } ?>
							<?php if(in_array('planned_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Planned<br />Hours</div></th><?php } ?>
							<?php if(in_array('tracked_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Tracked<br />Hours</div></th><?php } ?>
							<?php if(in_array('start_day_tile',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div><?= $timesheet_start_tile ?></div></th><?php } ?>
							<?php if(in_array('total_tracked_time',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Total Tracked<br />Time</div></th><?php } ?>
							<?php if($layout == 'ticket_task') { $total_colspan++; ?>
								<th style='text-align:center; vertical-align:bottom; width:12em;'><div><?= TICKET_NOUN ?></div></th>
								<th style='text-align:center; vertical-align:bottom; width:12em;'><div>Task</div></th>
							<?php } else if($layout == 'position_dropdown') { ?>
								<th style='text-align:center; vertical-align:bottom; width:12em;'><div>Position</div></th>
							<?php } ?>
							<?php if(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div><?= in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular' ?><br />Hours</div></th><?php } ?>
							<?php if(in_array('start_day_tile_separate',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div><?= $timesheet_start_tile ?></div></th><?php } ?>
							<?php if(in_array('extra_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Extra<br />Hours</div></th><?php } ?>
							<?php if(in_array('relief_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Relief<br />Hours</div></th><?php } ?>
							<?php if(in_array('sleep_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Sleep<br />Hours</div></th><?php } ?>
							<?php if(in_array('training_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Training<br />Hours</div></th><?php } ?>
							<?php if(in_array('sick_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Sick Time<br />Adjustment</div></th><?php } ?>
							<?php if(in_array('sick_used',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Sick Hrs.<br />Taken</div></th><?php } ?>
							<?php if(in_array('stat_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Stat<br />Hours</div></th><?php } ?>
							<?php if(in_array('stat_used',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Stat. Hrs.<br />Taken</div></th><?php } ?>
							<?php if(in_array('vaca_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Vacation<br />Hours</div></th><?php } ?>
							<?php if(in_array('vaca_used',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Vacation<br />Hrs. Taken</div></th><?php } ?>
							<?php if(in_array('breaks',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Breaks</div></th><?php } ?>
							<?php if(in_array('view_ticket',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div><?= TICKET_NOUN ?></div></th><?php } ?>
							<?php if(in_array('comment_box',$value_config)) { ?><th style='text-align:center; vertical-align:bottom;'><div>Comments</div></th><?php } ?>
							<th style="width:<?= $timesheet_approval_initials == 1 ? '15em' : '6em' ?>;"><span class="popover-examples list-inline tooltip-navigation"><a style="top:0;" class="info_i_sm" data-toggle="tooltip" data-placement="top" title=""
								data-original-title="Check the boxes on multiple lines, then click Sign and click Approve."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Approve<?php if(in_array('approve_all', $value_config)) { ?><br><label><input type="checkbox" name="select_all_approve" onclick="approveAll(this);"> Select All<?php } ?></th>
						</tr>
						<?php $sql = "SELECT `time_cards_id`, `date`, SUM(IF(`type_of_time` NOT IN ('Extra Hrs.','Relief Hrs.','Sleep Hrs.','Sick Time Adj.','Sick Hrs.Taken','Stat Hrs.','Stat Hrs.Taken','Vac Hrs.','Vac Hrs.Taken','Break'),`total_hrs`,0)) REG_HRS,
							SUM(IF(`type_of_time`='Extra Hrs.',`total_hrs`,0)) EXTRA_HRS,
							SUM(IF(`type_of_time`='Relief Hrs.',`total_hrs`,0)) RELIEF_HRS, SUM(IF(`type_of_time`='Sleep Hrs.',`total_hrs`,0)) SLEEP_HRS,
							SUM(IF(`type_of_time`='Sick Time Adj.',`total_hrs`,0)) SICK_ADJ, SUM(IF(`type_of_time`='Sick Hrs.Taken',`total_hrs`,0)) SICK_HRS,
							SUM(IF(`type_of_time`='Stat Hrs.',`total_hrs`,0)) STAT_AVAIL, SUM(IF(`type_of_time`='Stat Hrs.Taken',`total_hrs`,0)) STAT_HRS,
							SUM(IF(`type_of_time`='Vac Hrs.',`total_hrs`,0)) VACA_AVAIL, SUM(IF(`type_of_time`='Vac Hrs.Taken',`total_hrs`,0)) VACA_HRS,
							SUM(`highlight`) HIGHLIGHT, SUM(`manager_highlight`) MANAGER,
							GROUP_CONCAT(DISTINCT `comment_box` SEPARATOR ', ') COMMENTS, SUM(`timer_tracked`) TRACKED_HRS, SUM(IF(`type_of_time`='Break',`total_hrs`,0)) BREAKS, `type_of_time`, `ticket_attached_id`, `manager_approvals`, `coord_approvals`, `manager_name`, `coordinator_name`, `ticketid`, `start_time`, `end_time`
							FROM `time_cards` WHERE `staff`='$search_staff' AND `date` >= '$search_start_date' AND `date` <= '$search_end_date' AND IFNULL(`business`,'') LIKE '%$search_site%' AND `approv`='N' AND `deleted`=0 GROUP BY `date`";
						$post_i = '';
						if($layout == 'multi_line' || $layout == 'position_dropdown' || $layout == 'ticket_task') {
							$sql .= ", `time_cards_id`";
							$post_i = 0;
						}
						$sql .= " ORDER BY `date`, IFNULL(DATE_FORMAT(CONCAT_WS(' ',DATE(NOW()),`start_time`),'%H:%i'),STR_TO_DATE(`start_time`,'%l:%i %p')) ASC, IFNULL(DATE_FORMAT(CONCAT_WS(' ',DATE(NOW()),`end_time`),'%H:%i'),STR_TO_DATE(`end_time`,'%l:%i %p')) ASC";
						$result = mysqli_query($dbc, $sql);
						$date = $search_start_date;
						$row = mysqli_fetch_array($result);
						$position_list = $_SERVER['DBC']->query("SELECT `position` FROM (SELECT `name` `position` FROM `positions` WHERE `deleted`=0 UNION SELECT `type_of_time` `position` FROM `time_cards` WHERE `deleted`=0) `list` WHERE IFNULL(`position`,'') != '' GROUP BY `position` ORDER BY `position`")->fetch_all();
						$ticket_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `deleted` = 0 AND `status` != 'Archive'"),MYSQLI_ASSOC);
						$task_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `task_types` WHERE `deleted` = 0 ORDER BY `category`"),MYSQLI_ASSOC);
						$total = ['REG'=>0,'EXTRA'=>0,'RELIEF'=>0,'SLEEP'=>0,'SICK_ADJ'=>0,'SICK'=>0,'STAT_AVAIL'=>0,'STAT'=>0,'VACA_AVAIL'=>0,'VACA'=>0,'BREAKS'=>0,'TRAINING'=>0,'DRIVE'=>0];
						while(strtotime($date) <= strtotime($search_end_date)) {
							$attached_ticketid = 0;
							$timecardid = 0;
							$driving_time = '';
							$time_type = '';
							$ticket_attached_id = 0;
							$approval_status = '';
							$approval_initials = '';
							$hl_colour = '';
							$start_time = '';
							$end_time = '';
							if($row['date'] == $date) {
								foreach($config['hours_types'] as $hours_type) {
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
									'SICK'=>$row['SICK_HRS'],'STAT_AVAIL'=>$row['STAT_AVAIL'],'STAT'=>$row['STAT_HRS'],'VACA_AVAIL'=>$row['VACA_AVAIL'],'VACA'=>$row['VACA_HRS'],'BREAKS'=>$row['BREAKS']];
								$comments = html_entity_decode($row['COMMENTS']);
								if(empty(strip_tags($comments))) {
									$comments = $timesheet_comment_placeholder;
								}
								foreach($total as $key => $value) {
									$total[$key] += $hrs[$key];
								}
								$timecardid = $row['time_cards_id'];
								if(empty($row['ticketid'])) {
									$driving_time = 'Driving Time';
								}
								$ticket_attached_id = $row['ticket_attached_id'];
								$attached_ticketid = $row['ticketid'];
								$time_type = $row['type_of_time'];
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
								if(in_array('start_day_tile_separate',$value_config) && !($row['ticketid'] > 0)) {
									$hrs['DRIVE'] = $hrs['REG'];
									$hrs['REG'] = 0;
									$total['REG'] -= $hrs['DRIVE'];
									$total['DRIVE'] += $hrs['DRIVE'];
								} else {
									$hrs['DRIVE'] = 0;
								}

								if($timesheet_approval_status_comments == 1) {
									if(!empty(trim($row['manager_approvals'],','))) {
										$approval_list = [];
										foreach(explode(',',$row['manager_approvals']) as $approval_manager) {
											if($approval_manager > 0) {
												$approval_list[] = get_contact($dbc, $approval_manager);
											}
										}
										$approval_status = 'Approved by '.implode(', ', $approval_list).'<br />';
									} else if(!empty($row['manager_name'])) {
										$approval_status = 'Approved by '.$row['manager_name'].'<br />';
									} else {
										$approval_status = 'Waiting for Approval<br />';
									}
								}
								$approval_initials = $row['manager_approvals'];

								$row = mysqli_fetch_array($result);
							} else {
								$hrs = ['REG'=>0,'EXTRA'=>0,'RELIEF'=>0,'SLEEP'=>0,'SICK_ADJ'=>0,'SICK'=>0,'STAT_AVAIL'=>0,'STAT'=>0,'VACA_AVAIL'=>0,'VACA'=>0,'BREAKS'=>0,'TRAINING'=>0,'DRIVE'=>0];
								$comments = '';
							}
							$hours = mysqli_fetch_array(mysqli_query($dbc, "SELECT IF(`dayoff_type` != '',`dayoff_type`,CONCAT(`starttime`,' - ',`endtime`)) FROM `contacts_shifts` WHERE `deleted`=0 AND `contactid`='$search_staff' AND '$date' BETWEEN `startdate` AND `enddate` ORDER BY `startdate` DESC"))[0];
							$day_of_week = date('l', $date);
							$shifts = checkShiftIntervals($dbc, $search_staff, $day_of_week, $date);
							if(!empty($shifts)) {
								$hours = '';
								foreach ($shifts as $shift) {
									$hours .= $shift['starttime'].' - '.$shift['endtime'].'<br>';
								}
							} else {
								$hours = $schedule_list[date('w',strtotime($date))];
							}
							//Planned & Tracked Hours
							$ticket_labels = get_ticket_labels($dbc, $date, $search_staff, $layout, $timecardid);
							$planned_hrs = get_ticket_planned_hrs($dbc, $date, $search_staff, $layout, $timecardid);
							$tracked_hrs = get_ticket_tracked_hrs($dbc, $date, $search_staff, $layout, $timecardid);
							$total_tracked_time = get_ticket_total_tracked_time($dbc, $date, $search_staff, $layout, $timecardid);
							$ticket_options = '';
							echo '<tr style="'.$hl_colour.'">'.
								($layout == 'multi_line' || $layout == 'ticket_task' || $layout == 'position_dropdown' ? '<input type="hidden" name="timecardid_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$timecardid.'"><input type="hidden" name="ticketattachedid_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$ticket_attached_id.'">' : '').
								'<td data-title="Date" style="text-align:center">'.$date.'</td>
								'.(in_array('ticketid',$value_config) ? '<td data-title="'.TICKET_NOUN.'">'.$ticket_labels.'</td>' : '').'
								'.(in_array('show_hours',$value_config) ? '<td data-title="Hours" style="text-align:center">'.$hours.'</td>' : '').'
								'.(in_array('start_time',$value_config) ? '<td data-title="Start Time" style="text-align:center">'.$start_time.'</td>' : '').'
								'.(in_array('end_time',$value_config) ? '<td data-title="End Time" style="text-align:center">'.$end_time.'</td>' : '').'
								'.(in_array('start_time_editable',$value_config) ? '<td data-title="Start Time" class="'.($show_separator==1 ? 'theme-color-border-bottom' : '').'"><input type="text" name="start_time_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" class="form-control datetimepicker" value="'.$start_time.'" '.(in_array('calculate_hours_start_end',$value_config) ? 'onchange="calculateHoursByStartEndTimes(this);"' : '').'></td>' : '<input type="hidden" name="start_time[]" value="'.$start_time.'">').'
								'.(in_array('end_time_editable',$value_config) ? '<td data-title="End Time" class="'.($show_separator==1 ? 'theme-color-border-bottom' : '').'"><input type="text" name="end_time_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" class="form-control datetimepicker" value="'.$end_time.'" '.(in_array('calculate_hours_start_end',$value_config) ? 'onchange="calculateHoursByStartEndTimes(this);"' : '').'></td>' : '<input type="hidden" name="end_time[]" value="'.$end_time.'">').'
								'.(in_array('total_tracked_hrs',$value_config) ? '<td data-title="Total Tracked Hours" style="text-align:center">'.(empty($hrs['TRACKED_HRS']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['TRACKED_HRS'],2) : time_decimal2time($hrs['TRACKED_HRS']))).'</td>' : '').'
								'.(in_array('planned_hrs',$value_config) ? '<td data-title="Planned Hours" style="text-align:center">'.$planned_hrs.'</td>' : '').'
								'.(in_array('tracked_hrs',$value_config) ? '<td data-title="Tracked Hours" style="text-align:center">'.$tracked_hrs.'</td>' : '').'
								'.(in_array('start_day_tile',$value_config) ? '<td data-title="'.$timesheet_start_tile.'" style="text-align: center;" class="'.($show_separator==1 ? 'theme-color-border-bottom' : '').'"><label><input type="checkbox" value="Driving Time" '.($driving_time == 'Driving Time' ? 'checked' : '').' class="driving_time" onchange="checkDrivingTime(this);"></label></span></td>' : '').'
								'.(in_array('total_tracked_time',$value_config) ? '<td data-title="Total Tracked Time" style="text-align:center">'.$total_tracked_time.'</td>' : '');
								if($layout == 'ticket_task') { ?>
									<td data-title="<?= TICKET_NOUN ?>" class="ticket_task_td <?= in_array('start_day_tile',$value_config) && $driving_time == 'Driving Time' ? 'readonly-block' : '' ?>"><select name="ticketid[]" class="chosen-select-deselect" data-placeholder="Select a <?= TICKET_NOUN ?>"" onchange="getTasks(this);"><option></option>
										<?php foreach($ticket_list as $ticket) { ?>
											<option data-tasks='<?= json_encode(explode(',', $ticket['task_available'])) ?>' <?= $ticket['ticketid'] == $attached_ticketid ? 'selected' : '' ?> value="<?= $ticket['ticketid'] ?>"><?= get_ticket_label($dbc, $ticket) ?></option>
										<?php } ?></select></td>
									<td data-title="Task" class="ticket_task_td <?= in_array('start_day_tile',$value_config) && $driving_time == 'Driving Time' ? 'readonly-block' : '' ?>"><select name="type_of_time[]" class="chosen-select-deselect" data-placeholder="Select a Task"><option></option>
										<?php foreach ($task_list as $task) { ?>
											<option <?= $time_type == $task['description'] ? 'selected' : '' ?> value="<?= $task['description'] ?>"><?= $task['description'] ?></option>
										<?php } ?>
									</select></td>
								<?php } else if($layout == 'position_dropdown') { ?>
									<td data-title="Position"><select name="type_of_time[]" class="chosen-select-deselect" data-placeholder="Select Position"><option />
										<?php foreach($position_list as $position) { ?>
											<option <?= $position[0] == $time_type ? 'selected' : '' ?> value="<?= $position[0] ?>"><?= $position[0] ?></option>
										<?php } ?></select></td>
								<?php }
								echo (in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<td data-title="'.(in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular').' Hours" style="text-align:center"><input type="text" name="regular_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['REG']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['REG'],2) : time_decimal2time($hrs['REG']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('start_day_tile_separate',$value_config) ? '<td data-title="'.$timesheet_start_tile.'" style="text-align:center"><input type="text" name="drive_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['DRIVE']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['DRIVE'],2) : time_decimal2time($hrs['DRIVE']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('extra_hrs',$value_config) ? '<td data-title="Extra Hours" style="text-align:center"><input type="text" name="extra_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['EXTRA']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['EXTRA'],2) : time_decimal2time($hrs['EXTRA']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('relief_hrs',$value_config) ? '<td data-title="Relief Hours" style="text-align:center"><input type="text" name="relief_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['RELIEF']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['RELIEF'],2) : time_decimal2time($hrs['RELIEF']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('sleep_hrs',$value_config) ? '<td data-title="Sleep Hours" style="text-align:center"><input type="text" name="sleep_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['SLEEP']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['SLEEP'],2) : time_decimal2time($hrs['SLEEP']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('training_hrs',$value_config) ? '<td data-title="Training Hours" style="text-align:center"><input type="text" name="training_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['TRAINING']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['TRAINING'],2) : time_decimal2time($hrs['TRAINING']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('sick_hrs',$value_config) ? '<td data-title="Sick Time Adjustment" style="text-align:center"><input type="text" name="sickadj_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['SICK_ADJ']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['SICK_ADJ'],2) : time_decimal2time($hrs['SICK_ADJ']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('sick_used',$value_config) ? '<td data-title="Sick Hours Taken" style="text-align:center"><input type="text" name="sick_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['SICK']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['SICK'],2) : time_decimal2time($hrs['SICK']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('stat_hrs',$value_config) ? '<td data-title="Stat Hours" style="text-align:center"><input type="text" name="statavail_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['STAT_AVAIL']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['STAT_AVAIL'],2) : time_decimal2time($hrs['STAT_AVAIL']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('stat_used',$value_config) ? '<td data-title="Stat Hours Taken" style="text-align:center"><input type="text" name="stat_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['STAT']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['STAT'],2) : time_decimal2time($hrs['STAT']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('vaca_hrs',$value_config) ? '<td data-title="Vacation Hours" style="text-align:center"><input type="text" name="vacavail_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['VACA_AVAIL']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['VACA_AVAIL'],2) : time_decimal2time($hrs['VACA_AVAIL']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('vaca_used',$value_config) ? '<td data-title="Vacation Hours Taken" style="text-align:center"><input type="text" name="vaca_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['VACA']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['VACA'],2) : time_decimal2time($hrs['VACA']))).'" class="form-control timepicker"></td>' : '').'
								'.(in_array('breaks',$value_config) ? '<td data-title="Breaks" style="text-align:center">'.(empty($hrs['BREAKS']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['BREAKS'],2) : time_decimal2time($hrs['BREAKS']))).'</td>' : '').'
								'.(in_array('view_ticket',$value_config) ? '<td data-title="'.TICKET_NOUN.'" style="text-align:center">'.(!empty($attached_ticketid) ? '<a href="" onclick="overlayIFrameSlider(\''.WEBSITE_URL.'/Ticket/edit_tickets.php?edit='.$attached_ticketid.'&calendar_view=true\',\'auto\',false,true, $(\'#timesheet_div\').outerHeight()); return false;" data-ticketid="'.$attached_ticketid.'" class="view_ticket" '.($attached_ticketid > 0 ? '' : 'style="display:none;"').'>View</a>' : '').'</td>' : '').'
								'.(in_array('comment_box',$value_config) ? '<td data-title="Comments">'.$approval_status.'<input type="text" name="comments_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$comments.'" class="form-control">'.($layout == 'multi_line' ? '<img class="inline-img add-row pull-right" src="../img/icons/ROOK-add-icon.png"><img class="inline-img rem-row pull-right" src="../img/remove.png">' : '').'</td>' : '').'
								<td data-title="Select to Approve">';
								if($layout == 'multi_line' || $layout == 'position_dropdown' || $layout == 'ticket_task') {
									if($timesheet_approval_initials == 1) {
										echo '<label '.(strpos(','.$approval_initials.',', ','.$_SESSION['contactid'].',') !== FALSE ? 'style="display:none;"' : '').'><input type="checkbox" name="approvedateid_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$timecardid.'" /></label>';
										foreach(explode(',', $approval_initials) as $approval_manager) {
											if($approval_manager > 0) {
												profile_id($dbc, $approval_manager);
											}
										}
									} else {
										echo '<label><input type="checkbox" name="approvedateid_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$timecardid.'" /></label>';
									}
								} else {
									if($timesheet_approval_initials == 1) {
										echo '<label '.(strpos(','.$approval_initials.',', ','.$_SESSION['contactid'].',') !== FALSE ? 'style="display:none;"' : '').'><input type="checkbox" name="approve_date[]" value="'.date('Y-m-d', strtotime($date)).'" /></label>';
										foreach(explode(',', $approval_initials) as $approval_manager) {
											if($approval_manager > 0) {
												profile_id($dbc, $approval_manager);
											}
										}
									} else {
										echo '<label><input type="checkbox" name="approve_date[]" value="'.date('Y-m-d', strtotime($date)).'" /></label>';
									}
								}
								echo '</td>
							</tr>';
							if(($layout != 'multi_line' && $layout != 'ticket_task' && $layout != 'position_dropdown') || $date != $row['date']) {
								$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
							}
							$post_i++;
						}
	                    echo '<input type="hidden" name="post_i_counter" value="'.$post_i.'">';
						$colspan = 2;
						if(in_array('ticketid',$value_config)) {
							$colspan++;
						}
						if(in_array('start_day_tile',$value_config)) {
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
						if(in_array('start_time_editable',$value_config)) {
							$colspan++;
						}
						if(in_array('end_time_editable',$value_config)) {
							$colspan++;
						}
						if(!in_array('show_hours',$value_config)) {
							$colspan--;
						}
						echo '<tr>
							<td data-title="" colspan="'.$colspan.'">Totals</td>
							'.(in_array('total_tracked_hrs',$value_config) ? '<td data-title="Total Tracked Hours">'.($timesheet_time_format == 'decimal' ? number_format($hrs['TRACKED_HRS'],2) : time_decimal2time($total['TRACKED_HRS'])).'</td>' : '').'
							'.($layout == 'ticket_task' ? '<td data-title=""></td><td data-title=""></td>' : '').'
							'.($layout == 'position_dropdown' ? '<td data-title=""></td>' : '').'
							'.(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<td data-title="'.(in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular').' Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['REG'],2) : time_decimal2time($total['REG'])).'</td>' : '').'
							'.(in_array('start_day_tile',$value_config) ? '<td data-title="Extra Hours">'.($timesheet_time_format == 'decimal' ? number_format($total['DRIVE'],2) : time_decimal2time($total['DRIVE'])).'</td>' : '').'
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
							<td data-title="" colspan="'.(in_array('comment_box',$value_config) ? 2 : 1).'"></td>
						</tr>';
						echo '<tr>
							<td colspan="'.$colspan.'">Year-to-date Totals</td>
							'.(in_array('total_tracked_hrs',$value_config) ? '<td data-title=""></td>' : '').'
							'.($layout == 'ticket_task' ? '<td data-title=""></td><td data-title=""></td>' : '').'
							'.($layout == 'position_dropdown' ? '<td data-title=""></td>' : '').'
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
							'.(in_array('breaks',$value_config) ? '<td data-title="Breaks"></td>' : '').'
							'.(in_array('view_ticket',$value_config) ? '<td data-title=""></td>' : '').'
							<td colspan="'.(in_array('comment_box',$value_config) ? 2 : 1).'"></td>
						</tr>'; ?>
						<?php while($row = mysqli_fetch_array( $result ))
						{
							$time_cards_id = $row['time_cards_id'];

							echo "<tr>";


							foreach($value['data'] as $tab_name => $tabs) {
								foreach($tabs as $field) {
									if (strpos($value_config, ','.$field[2].',') !== FALSE) {

										if($field[1] == 'tab') {
											// do nothing
										}elseif($field[2] == 'staff') {
											echo '<td>';
											echo get_staff($dbc, $row[$field[2]]);
											echo '</td>';
										}elseif($field[2] == 'business') {
											echo '<td>';
											echo get_client($dbc, $row[$field[2]]);
											echo '</td>';
										} else {
											echo '<td>';
											echo $row[$field[2]];
											echo '</td>';
										}

									}
								}
							}

							echo '<td style="text-align:center;"><input type="checkbox" name="element[]" value="'.$time_cards_id.'" /></td>';
							echo "</tr>";
						}

						echo '</table>';
						echo '<div class="form-group">
							<label for="'.$field[2].'" class="col-sm-2 control-label">Approval Signature: </label>
							<div class="col-sm-8">';
							$profile_sig = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts_description` WHERE `contactid` = '".$_SESSION['contactid']."'"))['stored_signature'];
							if(!empty($profile_sig)) {
								if(!file_exists('../Contacts/signatures/contact_sign_'.$_SESSION['contactid'].'.png')) {
									if(!file_exists('../Contacts/signatures')) {
										mkdir('../Contacts/signatures', 0777, true);
									}
									include_once('../phpsign/signature-to-image.php');
									$signature = sigJsonToImage(html_entity_decode($profile_sig));
									imagepng($signature, '../Contacts/signatures/contact_sign_'.$_SESSION['contactid'].'.png');
								}
								echo '<label class="form-checkbox"><input type="checkbox" name="use_profile_sig" value="1" onchange="useProfileSig(this);"> Use Profile Signature</input></label>';
								echo '<div class="profile_sig_box" style="display: none;"><img src="../Contacts/signatures/contact_sign_'.$_SESSION['contactid'].'.png"></div>';
							}
							echo '<div class="timesheet_sig_box">';
								include ('../phpsign/sign.php');
							echo '</div>';
						echo '</div>';
						echo '<div class="col-sm-2 pull-right">';
							echo '<button type="submit" name="approv_db" value="approv_btn" class="btn brand-btn pull-right">Update and Approve Selected</button>';
							echo '<button type="submit" name="approv_db" value="update_btn" class="btn brand-btn pull-right">Update Hours</button>';
						echo '</div></div>';
					elseif($layout == 'rate_card' || $layout == 'rate_card_tickets'):
						echo '<button type="submit" value="rate_approval" name="submit" class="btn brand-btn mobile-block pull-right">Update and Approve Selected</button>';
						echo '<button type="submit" value="rate_timesheet" name="submit" class="btn brand-btn mobile-block pull-right">Update Hours</button>';
						for($date = $search_start_date; strtotime($date) <= strtotime($search_end_date); $date = date("Y-m-d", strtotime("+1 day", strtotime($date)))) {
							if($layout == 'rate_card_tickets') {
								$ticket_sql = "SELECT `tickets`.*, `osbn`.`item_id` `osbn` FROM `tickets` LEFT JOIN `ticket_attached` `osbn` ON `tickets`.`ticketid`=`osbn`.`ticketid` AND `osbn`.`src_table`='Staff' AND `osbn`.`deleted`=0 AND `osbn`.`position`='Team Lead' WHERE `tickets`.`ticketid` IN (SELECT `ticketid` FROM `time_cards` WHERE `deleted`=0 AND `staff`='$search_staff' AND `date`='$date' UNION SELECT `ticketid` FROM `tickets` WHERE CONCAT(',',`contactid`,',') LIKE '%,$search_staff,%' AND (`to_do_date`='$date' OR '$date' BETWEEN `to_do_date` AND `to_do_end_date` OR `internal_qa_date`='$date' OR `deliverable_date`='$date') AND `deleted`=0)";
							} else {
								$ticket_sql = "SELECT 0 `ticketid`";
							}
							$work_hours_sql = "SELECT IFNULL(SUM(`total_hrs`),0) hours, `category`, `work_desc`, `hourly` FROM `staff_rate_table` staff LEFT JOIN `time_cards` sheet ON CONCAT(',',staff.`staff_id`,',') LIKE CONCAT('%,',sheet.`staff`,',%') AND sheet.`type_of_time`=staff.`work_desc` AND sheet.`date`='$date' WHERE CONCAT(',',staff.`staff_id`,',') LIKE '%,$search_staff,%' AND staff.`deleted`=0 AND sheet.`deleted`=0 AND DATE(NOW()) BETWEEN staff.`start_date` AND IFNULL(NULLIF(staff.`end_date`,'0000-00-00'),'9999-12-31') GROUP BY `category`, `work_desc` ORDER BY `category`, `work_desc`, `hourly`";
							$work_result = mysqli_query($dbc, $work_hours_sql);
							$day_of_week = date('l', strtotime($date));
							$shifts = checkShiftIntervals($dbc, $search_staff, $day_of_week, $date);
							if(!empty($shifts)) {
								$shift = '';
								foreach ($shifts as $shift_detail) {
									$shift .= $shift_detail['starttime'].' - '.$shift_detail['endtime'].'<br>';
								}
							} else {
								$shift = $schedule_list[date('w',strtotime($date))];
							}
							echo "<div class='form-group' style='border:solid black 1px; display:inline-block; margin:1em; width:25em;'>";
							echo "<div style='border:solid black 1px; padding:0.35em; width: 25em;'><div style='display:inline-block; width:12em;'>Date:</div><div style='display:inline-block; width:12em;'>";
							echo "<label style='width:12em;'>$date<input type='checkbox' name='approve_date[]' value='$date' class='pull-right' /></label></div>";
							if($shift != '') {
								echo "<div style='display:inline-block; width:12em;'>Hours:</div><div style='display:inline-block; width:12em;'>$shift</div>";
							}
							if($ticket['ticketid'] > 0) {
								echo "<div style='display:inline-block; width:12em;'>".TICKET_NOUN.":</div><div style='display:inline-block; width:16em;'>".get_ticket_label($dbc, $ticket).($ticket['osbn'] > 0 ? "<br />OSBN: ".get_contact($dbc, $ticket['osbn']) : '')."</div>";
							}
							echo "</div>";
							$category = '';
							while($hours = mysqli_fetch_array($work_result)) {
								if($hours['category'] != $category) {
									if($category != '') {
										echo "<div style='display:inline-block; width:12em;'>$category Description</div><div style='display:inline-block; width:12em;'><input type='hidden' name='comment_date[]' value='$date'><input type='hidden' name='comment_cat[]' value='$category'><input type='text' name='cat_comment[]' class='form-control'></div></div>";
									}
									$category = $hours['category'];
									echo "<div style='border:solid black 1px; padding:0.35em; width: 25em;'><div style='display:inline-block; width:12em;'>$category</div><div style='display:inline-block; text-align:center; width:6em;'>Hours</div><div style='display:inline-block; text-align:center; width:6em;'>Rate</div>";
								}
								echo "<div style='display:inline-block; width:12em;'>".$hours['work_desc']."</div><div style='display:inline-block; width:6em;'>";
								echo "<input type='hidden' name='hours_cat[]' value='$category'><input type='hidden' name='hours_type[]' value='".$hours['work_desc']."'><input type='hidden' name='hours_date[]' value='$date'>";
								echo "<input type='text' name='hours[]' class='form-control' value='".$hours['hours']."'></div><div style='display:inline-block; text-align:center; width:6em;'>$".$hours['hourly']."</div>";
								if($hours['comment_box'] != '' && in_array(['Comments','text','comment_box'],$config['settings']['Choose Fields for Time Sheets']['data']['General'])) {
									echo html_entity_decode($hours['comment_box']);
								}
							}
							if($category != '') {
								echo "<div style='display:inline-block; width:12em;'>$category Description</div><div style='display:inline-block; width:12em;'><input type='hidden' name='comment_date[]' value='$date'><input type='hidden' name='comment_cat[]' value='$category'><input type='text' name='cat_comment[]' class='form-control'></div></div>";
							}
							echo "</div>";
						}
						if(vuaed_visible_function_custom($dbc) && $search_staff != ''):
							echo '<div class="clearfix"></div>';
							echo '<button type="submit" value="rate_approval" name="submit" class="btn brand-btn mobile-block pull-right">Update and Approve Selected</button>';
							echo '<button type="submit" value="rate_timesheet" name="submit" class="btn brand-btn mobile-block pull-right">Update Hours</button>';
						endif;
					endif;
				}
            } else {
				echo "<h3>Please select a staff member.</h3>";
            } ?>
			</div>

        </div>
        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
