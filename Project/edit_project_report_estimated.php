<?php include_once('../include.php');
error_reporting(0);
if(!isset($security)) {
	$security = get_security($dbc, $tile);
	$strict_view = strictview_visible_function($dbc, 'project');
	if($strict_view > 0) {
		$security['edit'] = 0;
		$security['config'] = 0;
	}
}
if(!isset($project)) {
	$projectid = filter_var($_GET['projectid'],FILTER_SANITIZE_STRING);
	$project = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `project` WHERE `projectid`='$projectid'"));
}
$value_config = array_filter(array_unique(array_merge(explode(',',mysqli_fetch_array(mysqli_query($dbc,"SELECT `config_fields` FROM field_config_project WHERE type='$projecttype'"))[0]),explode(',',mysqli_fetch_array(mysqli_query($dbc,"SELECT `config_fields` FROM field_config_project WHERE type='ALL'"))[0]))));
$result = mysqli_query($dbc, "SELECT `times`.`time`, `times`.`type`, `times`.`label`, `tickets`.* FROM (SELECT '' `label`, `tickets`.`ticketid`, 'Completion Estimate' `type`, IFNULL(`total_time`,`max_time`) `time` FROM `tickets` LEFT JOIN (SELECT `ticketid`, SEC_TO_TIME(SUM(TIME_TO_SEC(`time_length`))) `total_time` FROM `ticket_time_list` WHERE `deleted`=0 AND `time_type` IN ('Completion Estimate') GROUP BY `ticketid`, `time_type`) `time` ON `tickets`.`ticketid`=`time`.`ticketid` WHERE `deleted`=0 AND IFNULL(`max_time`,'00:00:00') != '00:00:00' AND `projectid`='$projectid' UNION
	SELECT '' `label`, `tickets`.`ticketid`, 'QA Estimate' `type`, IFNULL(`total_time`,`max_qa_time`) `time` FROM `tickets` LEFT JOIN (SELECT `ticketid`, SEC_TO_TIME(SUM(TIME_TO_SEC(`time_length`))) `total_time` FROM `ticket_time_list` WHERE `deleted`=0 AND `time_type` IN ('QA Estimate') GROUP BY `ticketid`, `time_type`) `time` ON `tickets`.`ticketid`=`time`.`ticketid` WHERE `deleted`=0 AND IFNULL(`max_qa_time`,'00:00:00') != '00:00:00' AND `projectid`='$projectid' UNION
	SELECT `date_of_meeting` `label`, 0 `ticketid`, `type`, SEC_TO_TIME(TIME_TO_SEC(STR_TO_DATE(`end_time_of_meeting`,'%h:%i %p')) - TIME_TO_SEC(STR_TO_DATE(`time_of_meeting`,'%h:%i %p'))) `time` FROM `agenda_meeting` WHERE `projectid`='6') `times` LEFT JOIN `tickets` ON `tickets`.`ticketid`=`times`.`ticketid` ORDER BY `times`.`ticketid`, `times`.`type`");
echo '<h3>Estimated Time</h3>';
if(mysqli_num_rows($result) > 0) {
    echo '<div id="no-more-tables"><table class="table table-bordered">';
    echo '<tr class="hidden-xs hidden-sm">
        <th>Item</th>
        <th>Type</th>
        <th>Time</th>
        </tr>';
	$total_time = 0;
	while($row = mysqli_fetch_array( $result )) {
		echo '<tr>';
		
		$time_length = date('G:i',strtotime(date('Y-m-d ').$row['time']));
		$seconds = explode(':',$row['time']);
		$total_time += ($seconds[0] * 3600) + ($seconds[1] * 60) + $seconds[2];

		echo '<td data-title="'.($row['label'] != '' ? 'Date' : TICKET_NOUN).'">' . ($row['label'] != '' ? $row['label'] : '<a href="" onclick="overlayIFrameSlider(\'../Ticket/index.php?edit='.$row['srcid'].'\'); return false;">'.get_ticket_label($dbc,$row).'</a>') . '</td>';
		echo '<td data-title="Type">' . $row['type'] . '</td>';
		echo '<td data-title="Time">' . $row['time'] . '</td>';

		echo "</tr>";
	}
    echo '<tr>
        <td colspan="2">Total Estimated Time</td>
        <td data-title="Total Estimated Time">'.floor($total_time/3600).':'.sprintf("%02d", floor($total_time/60)%60).':'.sprintf("%02d", $total_time%60).'</td>
        </tr>';

	echo '</table></div>';
} else {
    echo "<h2>No Estimated Time Found.</h2>";
}
include('next_buttons.php'); ?>