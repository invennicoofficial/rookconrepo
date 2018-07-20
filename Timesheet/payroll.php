<?php include('../include.php');
include_once('../Timesheet/reporting_functions.php');
include('../Calendar/calendar_functions_inc.php'); ?>
<script type="text/javascript" src="timesheet.js"></script>
<script type="text/javascript">
$(document).on('change', 'select[name="search_staff[]"]', function() { filterStaff(this); });
//$(document).on('change', 'select[name="search_group"]', function() { filterStaff(this); });
//$(document).on('change', 'select[name="search_security"]', function() { filterStaff(this); });
function filterStaff(sel) {
  var staff_sel = $('select[name="search_staff[]"');
  if(sel.name == "search_staff[]") {
    if($(staff_sel).val().indexOf('ALL') > -1) {
      $(staff_sel).find('option').prop('selected', false);
      $(staff_sel).find('option').filter(function() { return $(this).val() > 0 && $(this).data('status') > 0; }).prop('selected', true);
      $(staff_sel).trigger('change.select2');
    }
  } else if(sel.name == "search_group") {
    if($(sel).val() != '') {
      var staff = $(sel).find('option:selected').data('staff');
      if(staff.length == 0) {
        staff = [''];
      }
      $(staff_sel).find('option').prop('selected', false);
      staff.forEach(function(staffid) {
        $(staff_sel).find('option').filter(function() { return $(this).val() == staffid }).prop('selected', true);
        $(staff_sel).trigger('change.select2');
      });
    }
  } else if(sel.name == "search_security") {
    if($(sel).val() != '') {
      var security_level = $(sel).val();
      $(staff_sel).find('option').prop('selected', false);
      $(staff_sel).find('option').each(function() {
        if($(this).val() > 0) {
          var security_levels = ','+$(this).data('security-level')+',';
          if(security_levels.indexOf(security_level) > -1) {
            $(this).prop('selected', true);
          }
        }
      });
      $(staff_sel).trigger('change.select2');
    }
  }
}
function unapproveTimeSheet(a) {
    var staff = $(a).data('staff');
    var type = $(a).data('type');
    var date = $(a).data('date');
    var id = $(a).data('timesheetid');
    if(confirm('Are you sure you want to unapprove this time?')) {
        $.ajax({
            url: '../Timesheet/time_cards_ajax.php?action=unapprove_time',
            method: 'POST',
            data: { staff: staff, type: type, date: date, id: id },
            success: function(response) {
                console.log(response);
                $(a).closest('tr').remove();
            }
        });
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
function viewTicket(a) {
	var ticketid = $(a).data('ticketid');
	overlayIFrameSlider('<?= WEBSITE_URL ?>/Ticket/edit_tickets.php?edit='+ticketid+'&calendar_view=true','auto',false,true, $('#timesheet_div').outerHeight());
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

        <h1 class="">Payroll Dashboard
        <?php
        if(config_visible_function_custom($dbc)) {
            echo '<a href="field_config.php?from_url=payroll.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        <img class="no-toggle statusIcon pull-right no-margin inline-img small" title="" src="" data-original-title=""></h1>

        <form id="form1" name="form1" method="GET" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <?php echo get_tabs('Payroll', 'Custom', array('db' => $dbc, 'field' => $value['config_field'])); ?>
            <br><br>
            <?php
                $timesheet_payroll_styling = get_config($dbc,'timesheet_payroll_styling');

                $highlight = get_config($dbc, 'timesheet_highlight');
                $mg_highlight = get_config($dbc, 'timesheet_manager');
                $search_site = '';
                $search_staff_list = '';
                $search_start_date = date('Y-m-01');
                $search_end_date = date('Y-m-d');
                $position = '';

                if(!empty($_GET['search_site'])) {
                    $search_site = $_GET['search_site'];
                }
                if(!empty($_GET['search_staff'])) {
                    if($timesheet_payroll_styling == 'EGS') {
                        $search_staff_list = implode(',',$_GET['search_staff']);
                    } else {
                        $search_staff_list = array_filter($_GET['search_staff']);
                    }
                }

                if(!empty($_GET['search_start_date'])) {
                    $search_start_date = $_GET['search_start_date'];
                }
                if(!empty($_GET['search_end_date'])) {
                    $search_end_date = $_GET['search_end_date'];
                }
                $timesheet_comment_placeholder = get_config($dbc, 'timesheet_comment_placeholder');
                $timesheet_start_tile = get_config($dbc, 'timesheet_start_tile');
                $timesheet_time_format = get_config($dbc, 'timesheet_time_format');
                $timesheet_rounding = get_config($dbc, 'timesheet_rounding');
                $timesheet_rounded_increment = get_config($_SERVER['DBC'], 'timesheet_rounded_increment') / 60;
                $current_period = !empty($_GET['pay_period']) || $_GET['pay_period'] == 0 ? $_GET['pay_period'] : -1;
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
                <label class="control-label">Search By Staff:</label>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8 col-xs-8">
                <?php if($timesheet_payroll_styling == 'EGS') { ?>
                    <select multiple data-placeholder="Select Staff Members" name="search_staff[]" class="chosen-select-deselect form-control">
                        <option></option>
                        <option <?= 'ALL' == $search_staff_list ? 'selected' : '' ?> value="ALL">All Staff</option>
						<?php $query = sort_contacts_query(mysqli_query($dbc,"SELECT distinct(`time_cards`.`staff`), `contacts`.`contactid`, `contacts`.`first_name`, `contacts`.`last_name`, `contacts`.`status` FROM `time_cards` LEFT JOIN `contacts` ON `contacts`.`contactid` = `time_cards`.`staff` WHERE `time_cards`.`staff` > 0 AND `contacts`.`deleted`=0".$security_query));
						foreach($query as $staff_row) { ?>
							<option data-security-level='<?= $staff_row['role'] ?>' data-status="<?= $staff_row['status'] ?>" <?php if (strpos(','.$search_staff.',', ','.$staff_row['contactid'].',') !== FALSE) { echo " selected"; } ?> value='<?php echo  $staff_row['contactid']; ?>' ><?php echo $staff_row['full_name']; ?></option><?php
						} ?>
                    </select>
                    
                <?php } else { ?>
                    <select data-placeholder="Select Staff Members" multiple name="search_staff[]" class="chosen-select-deselect form-control" onchange="$('[name=search_start_date]').val('');$('[name=search_end_date]').val('');">
                        <option value=""></option>
                        <option value="ALL_STAFF">Select All Staff</option><?php
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
                                            $staff_with_security = array_column(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted` = 0 AND `status` > 0 AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND CONCAT(',',`role`,',') LIKE '%,".$security_level.",%'")),'contactid');
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
                            $staff_members = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted` = 0 AND `status` > 0 AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY.$security_query));
                        }
                        foreach($staff_members as $staff_id) { ?>
                            <option <?php if (in_array($staff_id['contactid'], $search_staff_list) || in_array('ALL_STAFF',$search_staff_list)) { echo " selected"; } ?> value='<?php echo $staff_id['contactid']; ?>'><?php echo $staff_id['full_name']; ?></option><?php
                            if(in_array('ALL_STAFF',$search_staff_list)) {
                                $search_staff_list[] = $staff_id['contactid'];
                            }
                        } ?>
                    </select>

                <?php } ?>
            </div>
            
            <?php if($timesheet_payroll_styling == 'Default') { ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                    <label for="site_name" class="control-label">Search By Site:</label>
                </div>
                <div class="col-lg-4 col-md-9 col-sm-8 col-xs-8">
                    <select data-placeholder="Select a Site" name="search_site" class="chosen-select-deselect form-control">
                        <option value=""></option><?php
                        $query = mysqli_query($dbc,"SELECT `contactid`, CONCAT(IFNULL(`site_name`,''),IF(IFNULL(`site_name`,'') != '' AND IFNULL(`display_name`,'') != '',': ',''),IFNULL(`display_name`,'')) display_name FROM `contacts` WHERE `category`='Sites' AND `deleted`=0");
                        while($row1 = mysqli_fetch_array($query)) { ?>
                            <option <?php if ($row1['contactid'] == $search_site) { echo " selected"; } ?> value='<?php echo  $row1['contactid']; ?>' ><?php echo $row1['display_name']; ?></option><?php
                        } ?>
                    </select>
                </div>
            <?php } ?>
            
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                    <label for="site_name" class="control-label">Search By Start Date:</label>
                </div>
                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                    <input name="search_start_date" value="<?php echo $search_start_date; ?>" type="text" class="form-control datepicker">
                </div>
                
                <div class="clearfix visible-xs"></div>

                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                    <label for="site_name" class="control-label">Search By End Date:</label>
                </div>
                <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                    <input name="search_end_date" value="<?php echo $search_end_date; ?>" type="text" class="form-control datepicker">
                </div>
            </div>
            
            <div class="form-group gap-top">
                <div class="text-right"><?php
                    if(count($_GET['search_staff']) == 1 && $_GET['search_staff'][0] != 'ALL_STAFF') { ?>
                        <a href="?pay_period=<?= $current_period + 1 ?>&search_site=<?= $search_site ?>&search_staff[]=<?= $_GET['search_staff'][0] ?>&see_staff=<?= $_GET['see_staff'] ?>" name="display_all_inventory" class="btn brand-btn mobile-block pull-right">Next Pay Period</a>
                        <a href="?pay_period=<?= $current_period - 1 ?>&search_site=<?= $search_site ?>&search_staff[]=<?= $_GET['search_staff'][0] ?>&see_staff=<?= $_GET['see_staff'] ?>" name="display_all_inventory" class="btn brand-btn mobile-block pull-right">Prior Pay Period</a><?php
                    } ?>
                    <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
                    <button type="button" onclick="$('[name^=search_staff]').find('option').prop('selected',false); $('[name^=search_staff]').find('option[value=<?= $timesheet_payroll_styling == 'EGS' ? 'ALL' : 'ALL_STAFF' ?>]').prop('selected',true).change(); $('[name=search_user_submit]').click(); return false;" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button><?php
                    
                    if($timesheet_payroll_styling == 'EGS') { ?>
                        <a target="_blank" href="<?= WEBSITE_URL ?>/Timesheet/reporting.php?export=pdf_egs&search_staff=<?php echo $search_staff_list; ?>&search_start_date=<?php echo $search_start_date; ?>&search_end_date=<?php echo $search_end_date; ?>&search_position=<?php echo $search_position; ?>&search_project=<?php echo $search_project; ?>&search_ticket=<?php echo $search_ticket; ?>&tab=payroll" title="PDF"><img src="<?php echo WEBSITE_URL; ?>/img/pdf.png" style="height:100%; margin:0;" /></a><?php
                    } ?>
                </div>
            </div>
        </form>

    <form id="form1" name="form1" action="add_time_card_approvals.php?pay_period=<?= $_GET['pay_period'] ?>&search_start_date=<?= $_GET['search_start_date'] ?>&search_end_date=<?= $_GET['search_end_date'] ?>&search_site=<?= $_GET['search_site'] ?>" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">

    <div id="no-more-tables">

    <?php
    if($timesheet_payroll_styling == 'EGS') {
        if(!empty($_GET['see_staff'])) {
            echo get_egs_hours_report($dbc, $search_staff_list,$search_start_date, $search_end_date,$_GET['see_staff'], '', 'payroll');
        } else {
            echo get_egs_main_hours_report($dbc, $search_staff_list, $search_start_date, $search_end_date, '', 'payroll');
        }
    } else {

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


                $query_check_credentials = 'SELECT * FROM time_cards WHERE (approv = "Y" OR approv = "P") AND `deleted`=0 '.$filter;

                $result = mysqli_query($dbc, $query_check_credentials);
                $value_config = explode(',',get_field_config($dbc, 'time_cards'));
                if(!in_array('reg_hrs',$value_config) && !in_array('direct_hrs',$value_config) && !in_array('payable_hrs',$value_config)) {
                    $value_config = array_merge($value_config,['reg_hrs','extra_hrs','relief_hrs','sleep_hrs','sick_hrs','sick_used','stat_hrs','stat_used','vaca_hrs','vaca_used']);
                }
                $timesheet_payroll_fields = ','.get_config($dbc, 'timesheet_payroll_fields').',';
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
                    FROM `time_cards` WHERE `staff`='$search_staff' AND `date` < '$search_start_date' AND `date` >= '$start_of_year' AND (`approv`='Y' OR `approv`='P') AND `deleted`=0";
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
                <?php if($layout == '' || $layout == 'multi_line'):
                    echo '<button type="submit" name="approv_db" value="update_btn" class="btn brand-btn pull-right">Update Hours</button>';
                    echo '<button type="submit" name="approv_db" value="paid_btn" class="btn brand-btn pull-right">Update and Mark Selected Paid</button>'; ?>

                    <table class='table table-bordered'>
                    <tr class='hidden-xs hidden-sm'>
                        <td colspan="2">Balance Forward Y.T.D.</td>
                        <?php if(in_array('ticketid',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('total_tracked_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('start_time',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('end_time',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('planned_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('tracked_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('total_tracked_time',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(in_array('start_day_tile',$value_config)) { ?><td style='text-align:center;'></td><?php } ?>
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
                        <?php if(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE) { ?><td style='text-align:center;'></td><?php } ?>
                        <?php if(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE) { ?><td style='text-align:center;'></td><?php } ?>
                        <td colspan="<?in_array('comment_box',$value_config) ? 2 : 1 ?>"></td>
                    </tr>
                    <tr class='hidden-xs hidden-sm'>
                        <th style='text-align:center; vertical-align:bottom; width:8em;'><div>Date</div></th>
                        <?php if(in_array('ticketid',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div><?= TICKET_NOUN ?></div></th><?php } ?>
                        <?php if(in_array('show_hours',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Hours</div></th><?php } ?>
                        <?php if(in_array('total_tracked_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Total Tracked<br />Hours</div></th><?php } ?>
                        <?php if(in_array('start_time',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Start<br />Time</div></th><?php } ?>
                        <?php if(in_array('end_time',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>End<br />Time</div></th><?php } ?>
                        <?php if(in_array('planned_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Planned<br />Hours</div></th><?php } ?>
                        <?php if(in_array('tracked_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Tracked<br />Hours</div></th><?php } ?>
                        <?php if(in_array('total_tracked_time',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Total Tracked<br />Time</div></th><?php } ?>
                        <?php if(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div><?= in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular' ?><br />Hours</div></th><?php } ?>
                        <?php if(in_array('start_day_tile',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div><?= $timesheet_start_tile ?></div></th><?php } ?>
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
                        <?php if(in_array('breaks',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Breaks</th><?php } ?>
                        <?php if(in_array('view_ticket',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div><?= TICKET_NOUN ?></div></th><?php } ?>
                        <?php if(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Expenses Owed</div></th><?php } ?>
                        <?php if(strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Mileage</div></th><?php } ?>
                        <?php if(strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Mileage Rate</div></th><?php } ?>
                        <?php if(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE) { ?><th style='text-align:center; vertical-align:bottom; width:2em;'><div>Mileage Total</div></th><?php } ?>
                        <?php if(in_array('comment_box',$value_config)) { ?><th style='text-align:center; vertical-align:bottom;'><div>Comments</div></th><?php } ?>
                        <th style="width:6em;"><span class="popover-examples list-inline tooltip-navigation"><a style="top:0;" class="info_i_sm" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Check the boxes on multiple lines, then click Sign and click Mark Paid."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Paid</th>
                    </tr>
                    <?php $sql = "SELECT `time_cards_id`, `date`, SUM(IF(`type_of_time` NOT IN ('Extra Hrs.','Relief Hrs.','Sleep Hrs.','Sick Time Adj.','Sick Hrs.Taken','Stat Hrs.','Stat Hrs.Taken','Vac Hrs.','Vac Hrs.Taken','Break'),`total_hrs`,0)) REG_HRS,
                        SUM(IF(`type_of_time`='Extra Hrs.',`total_hrs`,0)) EXTRA_HRS,
                        SUM(IF(`type_of_time`='Relief Hrs.',`total_hrs`,0)) RELIEF_HRS, SUM(IF(`type_of_time`='Sleep Hrs.',`total_hrs`,0)) SLEEP_HRS,
                        SUM(IF(`type_of_time`='Sick Time Adj.',`total_hrs`,0)) SICK_ADJ, SUM(IF(`type_of_time`='Sick Hrs.Taken',`total_hrs`,0)) SICK_HRS,
                        SUM(IF(`type_of_time`='Stat Hrs.',`total_hrs`,0)) STAT_AVAIL, SUM(IF(`type_of_time`='Stat Hrs.Taken',`total_hrs`,0)) STAT_HRS,
                        SUM(IF(`type_of_time`='Vac Hrs.',`total_hrs`,0)) VACA_AVAIL, SUM(IF(`type_of_time`='Vac Hrs.Taken',`total_hrs`,0)) VACA_HRS,
                        SUM(`highlight`) HIGHLIGHT, SUM(`manager_highlight`) MANAGER,
                        GROUP_CONCAT(DISTINCT `comment_box` SEPARATOR ', ') COMMENTS, SUM(`timer_tracked`) TRACKED_HRS, SUM(IF(`type_of_time`='Break',`total_hrs`,0)) BREAKS, `ticket_attached_id`, `ticketid`, `start_time`, `end_time`, `approv`
                        FROM `time_cards` WHERE `staff`='$search_staff' AND `date` >= '$search_start_date' AND `date` <= '$search_end_date' AND IFNULL(`business`,'') LIKE '%$search_site%' AND (`approv`='Y' OR `approv`='P') AND `deleted`=0 GROUP BY `date`";
                    $post_i = '';
                    if($layout == 'multi_line') {
                        $sql .= ", `time_cards_id`";
                        $post_i = 0;
                    }
                    $sql .= " ORDER BY `date`, IFNULL(STR_TO_DATE(`start_time`, '%l:%i %p'),STR_TO_DATE(`start_time`, '%H:%i')) ASC, IFNULL(STR_TO_DATE(`end_time`, '%l:%i %p'),STR_TO_DATE(`end_time`, '%H:%i')) ASC";
                    $result = mysqli_query($dbc, $sql);
                    $date = $search_start_date;
                    $mileage_total = 0;
                    $mileage_rate_total = 0;
                    $mileage_cost_total = 0;
                    $row = mysqli_fetch_array($result);
                    $total = ['REG'=>0,'EXTRA'=>0,'RELIEF'=>0,'SLEEP'=>0,'SICK_ADJ'=>0,'SICK'=>0,'STAT_AVAIL'=>0,'STAT'=>0,'VACA_AVAIL'=>0,'VACA'=>0,'BREAKS'=>0,'TRAINING'=>0,'DRIVE'=>0];
                    while(strtotime($date) <= strtotime($search_end_date)) {
                        $attached_ticketid = 0;
                        $timecardid = 0;
                        $ticket_attached_id = 0;
                        $hl_colour = '';
                        $start_time = '';
                        $end_time = '';
                        $approv = '';
                        $mileage = 0;
                        $mileage_rate = 0;
                        $mileage_cost = 0;
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
                            $ticket_attached_id = $row['ticket_attached_id'];
                            $attached_ticketid = $row['ticketid'];
                            $start_time = $row['start_time'];
                            $end_time = $row['end_time'];
                            $approv = $row['approv'];

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

                            //Mileage
                            $mileage_start = $date.' 00:00:00';
                            $mileage_end = $date.' 23:59:59';
                            $mileage = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`mileage`) `mileage_total` FROM `mileage` WHERE `deleted` = 0 AND `staffid` = '$search_staff' AND `ticketid` = '$attached_ticketid' AND '$attached_ticketid' > 0 AND (`start` BETWEEN '$mileage_start' AND '$mileage_end' OR `end` BETWEEN '$mileage_start' AND '$mileage_end')"))['mileage_total'];
                            $mileage_total += $mileage;

                            //Mileage Rate
                            $mileage_customer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `clientid` FROM `tickets` WHERE `ticketid` = '$attached_ticketid' AND '$attached_ticketid' > 0"))['clientid'];
                            $mileage_rate = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `mileage` `price` FROM `rate_card` WHERE `clientid` = '$mileage_customer' AND '$mileage_customer' > 0 AND `deleted` = 0 AND `on_off` = 1 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31') UNION
                                SELECT `cust_price` `price` FROM `company_rate_card` WHERE LOWER(`tile_name`)='mileage' AND `deleted`=0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')"))['price'];
                            $mileage_rate_total += $mileage_rate;

                            //Mileage Calculated Cost
                            $mileage_cost = $mileage * $mileage_rate;
                            $mileage_cost_total += $mileage_cost;
                            
                            $row = mysqli_fetch_array($result);
                        } else {
                            $hrs = ['REG'=>0,'EXTRA'=>0,'RELIEF'=>0,'SLEEP'=>0,'SICK_ADJ'=>0,'SICK'=>0,'STAT_AVAIL'=>0,'STAT'=>0,'VACA_AVAIL'=>0,'VACA'=>0,'BREAKS'=>0,'TRAINING'=>0];
                            $comments = '';
                            $mileage = 0;
                            $mileage_rate = 0;
                            $mileage_cost = 0;
                        }
                        $expenses_owed = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`total`) `expenses_owed` FROM `expense` WHERE `deleted` = 0 AND `staff` = '$search_staff' AND `status` = 'Approved' AND `approval_date` = '$date'"))['expenses_owed'];
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
                        echo '<tr style="'.$hl_colour.'">'.
                            ($layout == 'multi_line' ? '<input type="hidden" name="timecardid_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$timecardid.'"><input type="hidden" name="ticketattachedid_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$ticket_attached_id.'">' : '').
                            '<td data-title="Date" style="text-align:center">'.$date.'</td>
                            '.(in_array('ticketid',$value_config) ? '<td data-title="'.TICKET_NOUN.'">'.$ticket_labels.'</td>' : '').'
                            '.(in_array('show_hours',$value_config) ? '<td data-title="Hours" style="text-align:center">'.$hours.'</td>' : '').'
                            '.(in_array('total_tracked_hrs',$value_config) ? '<td data-title="Total Tracked Hours" style="text-align:center">'.(empty($hrs['TRACKED_HRS']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['TRACKED_HRS'],2) : time_decimal2time($hrs['TRACKED_HRS']))).'</td>' : '').'
                            '.(in_array('start_time',$value_config) ? '<td data-title="Start Time" style="text-align:center">'.$start_time.'</td>' : '').'
                            '.(in_array('end_time',$value_config) ? '<td data-title="End Time" style="text-align:center">'.$end_time.'</td>' : '').'
                            '.(in_array('planned_hrs',$value_config) ? '<td data-title="Planned Hours" style="text-align:center">'.$planned_hrs.'</td>' : '').'
                            '.(in_array('tracked_hrs',$value_config) ? '<td data-title="Tracked Hours" style="text-align:center">'.$tracked_hrs.'</td>' : '').'
                            '.(in_array('total_tracked_time',$value_config) ? '<td data-title="Total Tracked Time" style="text-align:center">'.$total_tracked_time.'</td>' : '').'
                            '.(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config) ? '<td data-title="'.(in_array('payable_hrs',$value_config) ? 'Payable' : 'Regular').' Hours" style="text-align:center"><input type="text" name="regular_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['REG']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['REG'],2) : time_decimal2time($hrs['REG']))).'" class="form-control timepicker"></td>' : '').'
                            '.(in_array('start_day_tile',$value_config) ? '<td data-title="'.$timesheet_start_tile.'" style="text-align:center"><input type="text" name="drive_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.(empty($hrs['DRIVE']) ? '' : ($timesheet_time_format == 'decimal' ? number_format($hrs['DRIVE'],2) : time_decimal2time($hrs['DRIVE']))).'" class="form-control timepicker"></td>' : '').'
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
                            '.(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<td data-title="Expenses Owed">$'.($expenses_owed > 0 ? number_format($expenses_owed,2) : '0.00').'</td>' : '').'
                            '.(strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE ? '<td data-title="Mileage">'.($mileage > 0 ? number_format($mileage,2) : '0.00').'</td>' : '').'
                            '.(strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE ? '<td data-title="Mileage Rate">$'.($mileage_rate > 0 ? number_format($mileage_rate,2) : '0.00').'</td>' : '').'
                            '.(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE ? '<td data-title="Mileage Total">$'.($mileage_cost > 0 ? number_format($mileage_cost,2) : '0.00').'</td>' : '').'
                            '.(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE ? '<td data-title="Comments"><input type="text" name="comments_'.date('Y_m_d', strtotime($date)).'_'.$post_i.'" value="'.$comments.'" class="form-control"></td>' : '').'
                            <td data-title="Select to Mark Paid"><label '.($approv == 'P' ? 'class="readonly-block"' : '').'>';
                            if($layout == 'multi_line') {
                                echo '<input type="checkbox" name="approvedateid[]" value="'.$timecardid.'" '.($approv == 'P' ? 'checked readonly' : '').' /></td>';
                            } else {
                                echo '<input type="checkbox" name="approve_date[]" value="'.date('Y-m-d', strtotime($date)).'" '.($approv == 'P' ? 'checked readonly' : '').' /></td>';
                            }
                        echo '</label></tr>';
                        if($layout != 'multi_line' || $date != $row['date']) {
                            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        }
                        $post_i++;
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
                    $expenses_owed = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`total`) `expenses_owed` FROM `expense` WHERE `deleted` = 0 AND `staff` = '$search_staff' AND `status` = 'Approved' AND `approval_date` BETWEEN '$search_start_date' AND '$search_end_date'"))['expenses_owed'];
                    echo '<tr>
                        <td data-title="" colspan="'.$colspan.'">Totals</td>
                        '.(in_array('total_tracked_hrs',$value_config) ? '<td data-title="Total Tracked Hours">'.($timesheet_time_format == 'decimal' ? number_format($hrs['TRACKED_HRS'],2) : time_decimal2time($total['TRACKED_HRS'])).'</td>' : '').'
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
                        '.(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<td data-title="Total Expenses Owed">$'.($expenses_owed > 0 ? number_format($expenses_owed,2) : '0.00').'</td>' : '').'
                        '.(strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE ? '<td data-title="Total Mileage">'.($mileage_total > 0 ? number_format($mileage_total,2) : '0.00').'</td>' : '').'
                        '.(strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE ? '<td data-title="Total Mileage Rate">$'.($mileage_rate_total > 0 ? number_format($mileage_rate_total,2) : '0.00').'</td>' : '').'
                        '.(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE ? '<td data-title="Total Mileage Cost">$'.($mileage_cost_total > 0 ? number_format($mileage_cost_total,2) : '0.00').'</td>' : '').'
                        <td data-title="" colspan="'.(in_array('comment_box',$value_config) ? 2 : 1).'"></td>
                    </tr>';
                    echo '<tr>
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
                        '.(in_array('breaks',$value_config) ? '<td data-title="Breaks"></td>' : '').'
                        '.(in_array('view_ticket',$value_config) ? '<td data-title=""></td>' : '').'
                        '.(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE ? '<td data-title=""></td>' : '').'
                        '.(strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE ? '<td data-title=""></td>' : '').'
                        '.(strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE ? '<td data-title=""></td>' : '').'
                        '.(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE ? '<td data-title=""></td>' : '').'
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
                        <label for="'.$field[2].'" class="col-sm-2 control-label">Payroll Signature: </label>
                        <div class="col-sm-8">';
                        include ('../phpsign/sign.php');
                    echo '</div>';
                    echo '<div class="col-sm-2 pull-right">';
                        echo '<button type="submit" name="approv_db" value="update_btn" class="btn brand-btn pull-right">Update Hours</button>';
                        echo '<button type="submit" name="approv_db" value="paid_btn" class="btn brand-btn pull-right">Update and Mark Selected Paid</button>';
                    echo '</div></div>';
                elseif($layout == 'position_dropdown' || $layout == 'ticket_task'):
                    echo '<button type="submit" name="approve_position" value="update_btn" class="btn brand-btn pull-right">Update Hours</button>';
                    echo '<button type="submit" name="approve_position" value="paid_btn" class="btn brand-btn pull-right">Update and Mark Selected Paid</button>'; ?>
                    <script>
                    $(document).ready(function() {
                        checkTimeOverlaps();
                        initLines();
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
                    <div id="no-more-tables">
                        <table class='table table-bordered'>
                            <tr class='hidden-xs hidden-sm'>
                                <th style='text-align:center; vertical-align:bottom; width:<?= (in_array('editable_dates',$value_config) ? '15em;' : '7em;') ?>'><div>Date</div></th>
                                <?php $total_colspan = 2; ?>
                                <?php if(in_array('schedule',$value_config)) { $total_colspan++ ?><th style='text-align:center; vertical-align:bottom; width:9em;'><div>Schedule</div></th><?php } ?>
                                <?php if(in_array('scheduled',$value_config)) { $total_colspan++ ?><th style='text-align:center; vertical-align:bottom; width:10em;'><div>Scheduled Hours</div></th><?php } ?>
                                <?php if(in_array('start_time',$value_config)) { $total_colspan++ ?><th style='text-align:center; vertical-align:bottom; width:10em;'><div>Start Time</div></th><?php } ?>
                                <?php if(in_array('end_time',$value_config)) { $total_colspan++ ?><th style='text-align:center; vertical-align:bottom; width:10em;'><div>End Time</div></th><?php } ?>
                                <?php if(in_array('start_time_editable',$value_config)) { $total_colspan++; ?><th style='text-align:center; vertical-align:bottom; width:10em;'><div>Start Time</div></th><?php } ?>
                                <?php if(in_array('end_time_editable',$value_config)) { $total_colspan++; ?><th style='text-align:center; vertical-align:bottom; width:10em;'><div>End Time</div></th><?php } ?>
                                <?php if(in_array('start_day_tile',$value_config)) { $total_colspan++; ?><th style='text-align:center; vertical-align:bottom; width:10em;'><div><?= $timesheet_start_tile ?></div></th><?php } ?>
                                <?php if($layout == 'ticket_task') { $total_colspan++ ?>
                                    <th style='text-align:center; vertical-align:bottom; width:12em;'><div><?= TICKET_NOUN ?></div></th>
                                    <th style='text-align:center; vertical-align:bottom; width:12em;'><div>Task</div></th>
                                <?php } else { ?>
                                    <th style='text-align:center; vertical-align:bottom; width:12em;'><div>Position</div></th>
                                <?php } ?>
                                <?php if(in_array('total_tracked_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:6em;'><div>Time Tracked</div></th><?php } ?>
                                <th style='text-align:center; vertical-align:bottom; width:6em;'><div>Hours</div></th>
                                <?php if(in_array('vaca_hrs',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:6em;'><div>Vacation Hours</div></th><?php } ?>
                                <?php if(in_array('view_ticket',$value_config)) { ?><th style='text-align:center; vertical-align:bottom; width:6em;'><div><?= TICKET_NOUN ?></div></th><?php } ?>
                                <?php if(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE) { ?>
                                    <th style='text-align:center; vertical-align:bottom; width:6em;'><div>Expenses Owed</div></td>
                                <?php } ?>
                                <?php if(in_array('comment_box',$value_config)) { ?><th style='text-align:center; vertical-align:bottom;'><div>Comments</div></th><?php } ?>
                                <th style='text-align:center; vertical-align:bottom; width:6em;'><div>Paid</div></th>
                            </tr>
                            <?php $position_list = $_SERVER['DBC']->query("SELECT `position` FROM (SELECT `name` `position` FROM `positions` WHERE `deleted`=0 UNION SELECT `type_of_time` `position` FROM `time_cards` WHERE `deleted`=0) `list` WHERE IFNULL(`position`,'') != '' GROUP BY `position` ORDER BY `position`")->fetch_all();
                            $ticket_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `deleted` = 0 AND `status` != 'Archive'"),MYSQLI_ASSOC);
                            $total = 0;
                            $total_vac = 0;
                            $limits = "AND `staff`='$search_staff' AND `approv`='Y'";
                            if($search_site > 0) {
                                $limits .= " AND IFNULL(`business`,'') LIKE '%$search_site%'";
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
                                    $timecardid = $row['id'];
                                    if(empty($row['ticketid'])) {
                                        $driving_time = 'Driving Time';
                                    }
                                    $show_separator = 0;
                                } else {
                                    $row = '';
                                    $comments = '';
                                    $show_separator = 1;
                                }
                                $expenses_owed = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`total`) `expenses_owed` FROM `expense` WHERE `deleted` = 0 AND `staff` = '$search_staff' AND `status` = 'Approved' AND `approval_date` = '$date'"))['expenses_owed'];
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
                                } ?>
                                <tr style="<?= $hl_colour ?>" class="<?= $show_separator==1 ? 'theme-color-border-bottom' : '' ?>">
                                    <input type="hidden" name="time_cards_id[]" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="date[]" value="<?= empty($row['date']) ? $date : $row['date'] ?>">
                                    <input type="hidden" name="staff[]" value="<?= empty($row['staff']) ? $search_staff : $row['staff'] ?>">
                                    <td data-title="Date"><?= (in_array('editable_dates',$value_config) ? '<input type="text" name="date_editable[]" value="'.$date.'" class="form-control datepicker">' : $date) ?></td>
                                    <?php if(in_array('schedule',$value_config)) { ?><td data-title="Schedule"><?= $hours ?></td><?php } ?>
                                    <?php if(in_array('scheduled',$value_config)) { ?><td data-title="Scheduled Hours"></td><?php } ?>
                                    <?php if(in_array('start_time',$value_config)) { ?><td data-title="Start Time"><?= $row['start_time'] ?></td><?php } ?>
                                    <?php if(in_array('end_time',$value_config)) { ?><td data-title="End Time"><?= $row['end_time'] ?></td><?php } ?>
                                    <?php if(in_array('start_time_editable',$value_config)) { ?><td data-title="Start Time"><input type="text" name="start_time[]" class="form-control datetimepicker" value="<?= $row['start_time'] ?>" <?= in_array('calculate_hours_start_end',$value_config) ? 'onchange="calculateHoursByStartEndTimes(this);"' : '' ?>></td><?php } else { ?><input type="hidden" name="start_time[]" value="<?= $row['start_time'] ?>"><?php } ?>
                                    <?php if(in_array('end_time_editable',$value_config)) { ?><td data-title="End Time"><input type="text" name="end_time[]" class="form-control datetimepicker" value="<?= $row['end_time'] ?>" <?= in_array('calculate_hours_start_end',$value_config) ? 'onchange="calculateHoursByStartEndTimes(this);"' : '' ?>></td><?php } else { ?><input type="hidden" name="end_time[]" value="<?= $row['end_time'] ?>"><?php } ?>
                                    <?php if(in_array('start_day_tile',$value_config)) { ?><td data-title="<?= $timesheet_start_tile ?>" style="text-align: center;"><label><input type="checkbox" value="Driving Time" <?= $driving_time == 'Driving Time' ? 'checked' : '' ?> class="driving_time" onchange="checkDrivingTime(this);"></label></span></td><?php } ?>
                                    <?php if($layout == 'ticket_task') { ?>
                                        <td data-title="<?= TICKET_NOUN ?>" class="ticket_task_td <?= in_array('start_day_tile',$value_config) && $driving_time == 'Driving Time' ? 'readonly-block' : '' ?>"><select name="ticketid[]" class="chosen-select-deselect" data-placeholder="Select a <?= TICKET_NOUN ?>"" onchange="getTasks(this);"><option></option>
                                            <?php foreach($ticket_list as $ticket) { ?>
                                                <option data-tasks='<?= json_encode(explode(',', $ticket['task_available'])) ?>' <?= $ticket['ticketid'] == $row['ticketid'] ? 'selected' : '' ?> value="<?= $ticket['ticketid'] ?>"><?= get_ticket_label($dbc, $ticket) ?></option>
                                            <?php } ?></select></td>
                                        <td data-title="Task" class="ticket_task_td <?= in_array('start_day_tile',$value_config) && $driving_time == 'Driving Time' ? 'readonly-block' : '' ?>"><select name="type_of_time[]" class="chosen-select-deselect" data-placeholder="Select a Task"><option></option>
                                            <?php $task_list = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid` = '".$row['ticketid']."'"))['task_available'];
                                            foreach (explode(',',$task_list) as $task) { ?>
                                                <option <?= $row['type_of_time'] == $task ? 'selected' : '' ?> value="<?= $task ?>"><?= $task ?></option>
                                            <?php } ?>
                                        </select></td>
                                    <?php } else { ?>
                                        <td data-title="Position"><select name="type_of_time" class="chosen-select-deselect" data-placeholder="Select Position"><option />
                                            <?php foreach($position_list as $position) { ?>
                                                <option <?= $position[0] == $row['type_of_time'] ? 'selected' : '' ?> value="<?= $position[0] ?>"><?= $position[0] ?></option>
                                            <?php } ?></select></td>
                                    <?php } ?>
                                    <?php if(in_array('total_tracked_hrs',$value_config)) { ?><td data-title="Time Tracked"><?= $row['timer'] ?></td><?php } ?>
                                    <td data-title="Hours"><input type="text" name="total_hrs[]" value="<?= (empty($row['hours']) || $row['type_of_time'] == 'Vac Hrs.' ? '' : ($timesheet_time_format == 'decimal' ? number_format($row['hours'],2) : time_decimal2time($row['hours']))) ?>" class="form-control <?= ($security['edit'] > 0 ? 'timepicker"' : '" readonly') ?>"></td>
                                    <?php if(in_array('vaca_hrs',$value_config)) { ?><td data-title="Vacation Hours"><input type="text" name="total_hrs_vac[]" value="<?= (empty($row['hours']) || $row['type_of_time'] != 'Vac Hrs.' ? '' : ($timesheet_time_format == 'decimal' ? number_format($row['hours'],2) : time_decimal2time($row['hours']))) ?>" class="form-control <?= ($security['edit'] > 0 ? 'timepicker"' : '" readonly') ?>"></td><?php } ?>
                                    <?php if(in_array('view_ticket',$value_config)) { ?><td data-title="<?= TICKET_NOUN ?>" style="text-align: center;"><a href="" onclick="viewTicket(this); return false;" data-ticketid="<?= $row['ticketid'] ?>" class="view_ticket" <?= $row['ticketid'] > 0 ? '' : 'style="display:none;"' ?>>View</a></td><?php } ?>
                                    <?php if(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE) { ?>
                                        <td data-title="Expenses Owed">$<?= $expenses_owed > 0 ? number_format($expenses_owed,2) : '0.00' ?></td>
                                    <?php } ?>
                                    <?php if(in_array('comment_box',$value_config)) { ?><td data-title="Comments"><span><?= $comments ?></span><img class="inline-img comment-row pull-right" src="../img/icons/ROOK-reply-icon.png"><input type="text" class="form-control" name="comment_box[]" value="<?= $row['COMMENTS'] ?>" style="display:none;"></td><?php } ?>
                                    <td data-title="Select to Mark Paid"><input type="checkbox" name="approve_date[]" value="<?= date('Y-m-d', strtotime($date)) ?>" /></td>
                                </tr>
                                <?php if($date != $row['date']) {
                                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                                }
                            } ?>
                            <tr>
                                <td data-title="" colspan="<?= $total_colspan ?>">Totals</td>
                                <?php if(in_array('schedule',$value_config)) { ?><td></td><?php } ?>
                                <?php if(in_array('scheduled',$value_config)) { ?><td></td><?php } ?>
                                <?php if(in_array('total_tracked_hrs',$value_config)) { ?><td></td><?php } ?>
                                <td data-title="Total Hours"><?= ($timesheet_time_format == 'decimal' ? number_format($total,2) : time_decimal2time($total)) ?></td>
                                <?php if(in_array('vaca_hrs',$value_config)) { ?><td><?= ($timesheet_time_format == 'decimal' ? number_format($total_vac,2) : time_decimal2time($total_vac)) ?></td><?php } ?>
                                <?php if(in_array('view_ticket',$value_config)) { ?><td></td><?php } ?>
                                <?php if(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE) {
                                    $expenses_owed = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`total`) `expenses_owed` FROM `expense` WHERE `deleted` = 0 AND `staff` = '$search_staff' AND `status` = 'Approved' AND `approval_date` BETWEEN '$search_start_date' AND '$search_end_date'"))['expenses_owed']; ?>
                                    <td data-title="Total Expenses Owed">$<?= $expenses_owed > 0 ? number_format($expenses_owed,2) : '0.00' ?></td>
                                <?php } ?>
								<?php if(in_array('comment_box',$value_config)) { ?><td></td><?php } ?><td></td>
                            </tr>
                        </table>

                        <?php $tb_field = $value['config_field'];
                        echo '<button type="submit" name="approve_position" value="update_btn" class="btn brand-btn pull-right">Update Hours</button>';
                        echo '<button type="submit" name="approve_position" value="paid_btn" class="btn brand-btn pull-right">Update and Mark Selected Paid</button>';
                    echo '</div>';
                elseif($layout == 'rate_card' || $layout == 'rate_card_tickets'):
                    echo '<button type="submit" value="rate_approval" name="submit" class="btn brand-btn mobile-block pull-right">Update and Mark Selected Paid</button>';
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
                        echo '<button type="submit" value="rate_approval" name="submit" class="btn brand-btn mobile-block pull-right">Update and Mark Selected Paid</button>';
                        echo '<button type="submit" value="rate_timesheet" name="submit" class="btn brand-btn mobile-block pull-right">Update Hours</button>';
                    endif;
                endif;
            }
        } else {
            echo "<h3>Please select a staff member.</h3>";
        }
    }

    ?>
    </div>

</div>
</form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
