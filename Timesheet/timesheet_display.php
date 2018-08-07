<?php /* Display Time Sheet Block
This will display all of the fields for the Time Sheet with Editable Fields.
This file can be included on each of the pages, so that they display correctly and consistently.
Approval pages and Payroll pages have some additional fields that need to display.

Included in:
    time_cards.php
    time_card_approvals_coordinator.php
    time_card_approvals_manager.php
    payroll.php

Change Log:
    2018-08-07 Created Page
*/

// Get settings
$current_page = basename($_SERVER['SCRIPT_FILENAME']);
$layout = get_config($dbc, 'timesheet_layout');
$value_config = explode(',',get_field_config($dbc, 'time_cards'));
if(!in_array('reg_hrs',$value_config) && !in_array('direct_hrs',$value_config) && !in_array('payable_hrs',$value_config)) {
    $value_config = array_merge($value_config,['reg_hrs','extra_hrs','relief_hrs','sleep_hrs','sick_hrs','sick_used','stat_hrs','stat_used','vaca_hrs','vaca_used']);
}
$timesheet_payroll_fields = ($current_page == 'payroll.php' ? ','.get_config($dbc, 'timesheet_payroll_fields').',' : ',,');
$timesheet_comment_placeholder = get_config($dbc, 'timesheet_comment_placeholder');
$timesheet_approval_initials = get_config($dbc, 'timesheet_approval_initials');
$timesheet_approval_status_comments = get_config($dbc, 'timesheet_approval_status_comments');
$timesheet_start_tile = get_config($dbc, 'timesheet_start_tile');
$timesheet_time_format = get_config($dbc, 'timesheet_time_format');
$timesheet_rounding = get_config($dbc, 'timesheet_rounding');
$timesheet_rounded_increment = get_config($_SERVER['DBC'], 'timesheet_rounded_increment') / 60;
$highlight = get_config($dbc, 'timesheet_highlight');
$mg_highlight = get_config($dbc, 'timesheet_manager');
$submit_mode = get_config($dbc, 'timesheet_submit_mode');


// Create Table ?>
<table class='table table-bordered'>
<tr class='hidden-xs hidden-sm'>
    <td colspan="<?= 1 + (in_array('schedule',$value_config) ? 1 : 0) + (in_array('scheduled',$value_config) ? 1 : 0) + (in_array('ticketid',$value_config) ? 1 : 0) + (in_array('show_hours',$value_config) ? 1 : 0)
        + (in_array('total_tracked_hrs',$value_config) && in_array($layout,['', 'multi_line']) ? 1 : 0) + (in_array('start_time',$value_config) ? 1 : 0) + (in_array('end_time',$value_config) ? 1 : 0)
        + (in_array('start_time_editable',$value_config) ? 1 : 0) + (in_array('end_time_editable',$value_config) ? 1 : 0) + (in_array('planned_hrs',$value_config) ? 1 : 0)
        + (in_array('tracked_hrs',$value_config) ? 1 : 0) + (in_array('total_tracked_time',$value_config) ? 1 : 0) + (in_array('start_day_tile',$value_config) ? 1 : 0) + ($layout == 'ticket_task')
        + ($layout == 'position_dropdown') + (in_array('total_tracked_hrs',$value_config) && in_array($layout,['position_dropdown', 'ticket_task']) ? 1 : 0)
        + (in_array($layout,['position_dropdown', 'ticket_task']) ? 1 : 0) ?>">Balance Forward Y.T.D.</td>
    <?php if(in_array('reg_hrs',$value_config) || in_array('payable_hrs',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('start_day_tile_separate',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('extra_hrs',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('relief_hrs',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('sleep_hrs',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('training_hrs',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('sick_hrs',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('sick_used',$value_config)) { ?><th style='text-align:center;'><?= $sick_taken; ?></th><?php } ?>
    <?php if(in_array('stat_hrs',$value_config)) { ?><th style='text-align:center;'><?= $stat_hours; ?></th><?php } ?>
    <?php if(in_array('stat_used',$value_config)) { ?><th style='text-align:center;'><?= $stat_taken; ?></th><?php } ?>
    <?php if(in_array('vaca_hrs',$value_config)) { ?><th style='text-align:center;'><?= $vacation_hours; ?></th><?php } ?>
    <?php if(in_array('vaca_used',$value_config)) { ?><th style='text-align:center;'><?= $vacation_taken; ?></th><?php } ?>
    <?php if(in_array('breaks',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(in_array('view_ticket',$value_config)) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(strpos($timesheet_payroll_fields, ',Expenses Owed,') !== FALSE) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(strpos($timesheet_payroll_fields, ',Mileage,') !== FALSE) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(strpos($timesheet_payroll_fields, ',Mileage Rate,') !== FALSE) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE) { ?><th style='text-align:center;'></th><?php } ?>
    <?php if(strpos($timesheet_payroll_fields, ',Mileage Total,') !== FALSE) { ?><td style='text-align:center;'></td><?php } ?>
    <td colspan="<?= in_array('comment_box',$value_config) ? 2 : 1 ?>"></td>
</tr>