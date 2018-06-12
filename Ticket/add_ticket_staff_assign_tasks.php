<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>All Tasks</h3>') ?>
<?php $main_tasks = explode(',',$get_ticket['task_available']);
$groups = $dbc->query("SELECT `category` FROM `task_types` WHERE `deleted`=0 GROUP BY `category` ORDER BY MIN(`sort`), MIN(`id`)");
while($task_group_name = $groups->fetch_assoc()['category']) {
	if(strpos($value_config,',Ticket Tasks Ticket Type,') === FALSE || config_safe_str($task_group_name) == $ticket_type) {
		echo "<h4>".$task_group_name."</h4>";
		$task_list = $dbc->query("SELECT `id`, `description` FROM `task_types` WHERE `deleted`=0 AND `category`='$task_group_name' ORDER BY `sort`, `id`");
		while($task = $task_list->fetch_assoc()['description']) { ?>
			<label class="form-checkbox"><input type="checkbox" <?= in_array($task,$main_tasks) ? 'checked' : '' ?> name="task_available" data-concat="," data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $task ?>" data-table="tickets"> <?= $task ?></label>
		<?php }
	}
}
$project_tasks = mysqli_query($dbc, "SELECT `description`, `src_table`, `src_id` FROM `project_scope` WHERE `projectid`='".$get_ticket['projectid']."' AND (`description` != '' OR (`src_table`='services' AND `src_id` > 0))");
while($project_task = mysqli_fetch_assoc($project_tasks)) {
	if($project_task['src_id'] > 0) {
		$main_tasks[] = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading` FROM `services` WHERE `serviceid`='".$project_task['src_id']."'"))['heading'];
	} else {
		$main_tasks[] = $project_task['description'];
	}
}
$main_tasks = array_filter($main_tasks);
foreach($main_tasks as $i => $task_name) {
	$main_tasks[$i] = html_entity_decode($task_name);
} ?>
<?php $task_groups = explode('#*#',get_config($dbc, 'ticket_'.$ticket_type.'_staff_tasks'));
foreach(explode('#*#', get_config($dbc, 'ticket_ALL_staff_tasks')) as $i => $task_group) {
	$task_groups[$i] = implode('*#*',array_filter(array_unique(explode('*#*',$task_group.'*#*'.$task_groups[$i]))));
}
$task_groups = implode('#*#',$task_groups);
if($task_groups == '') {
	$task_groups = get_config($dbc, 'site_work_order_tasks');
}
foreach(explode('#*#', $task_groups) as $task_group) {
	$task_group = explode('*#*', $task_group);
	if((strpos($value_config,',Ticket Tasks Ticket Type,') !== FALSE && config_safe_str($task_group[0]) == $ticket_type) || strpos($value_config,',Ticket Tasks Ticket Type,') === FALSE) {
		echo "<h4>".$task_group[0]."</h4>";
		unset($task_group[0]);
		foreach($task_group as $task) { ?>
			<label class="form-checkbox"><input type="checkbox" <?= in_array($task,$main_tasks) ? 'checked' : '' ?> name="task_available" data-concat="," data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $task ?>" data-table="tickets"> <?= $task ?></label>
		<?php }
	}
} ?>