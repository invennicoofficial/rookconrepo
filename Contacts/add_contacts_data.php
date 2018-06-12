<?php if (strpos($value_config, ','."Project".',') !== FALSE) { ?>
	<h4><?= PROJECT_TILE ?></h4>
	<?php $result = mysqli_query($dbc, "SELECT * FROM `projects` WHERE `project_lead`='".$contactid."' AND `deleted`=0");
	$project_security = get_security($dbc, 'project');
	if(mysqli_num_rows($result) > 0) { ?>
		<table class="table table-bordered">
			<tr>
				<th><?= PROJECT_NOUN ?></th>
			</tr>
			<?php while($row = mysqli_fetch_assoc($result)) { ?>
				<tr>
					<td data-title="<?= PROJECT_NOUN ?>">
						<?= ($project_security['edit'] > 0 ? '<a href="../Project/project.php?edit='.$row['projectid'].'">' : '').PROJECT_NOUN.' #'.$row['projectid'].' '.$row['project_name'].($project_security['edit'] > 0 ? '</a>' : '') ?>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } else {
		echo "<h3>No ".PROJECT_TILE." Found</h3>";
	}
} else if (strpos($value_config, ','."Ticket".',') !== FALSE) { ?>
	<h4><?= TICKET_TILE ?></h4>
	<?php $result = mysqli_query($dbc, "SELECT * FROM `tickets` WHERE (`ticketid` IN (SELECT `ticketid` FROM `ticket_attached` WHERE `src_table` IN ('Staff', 'Members','Clients') AND `item_id`='".$contactid."') OR CONCAT(',',`contactid`,',') LIKE '%,".$contactid.",%' OR CONCAT(',',`internal_qa_contactid`,',') LIKE '%,".$contactid.",%' OR CONCAT(',',`deliverable_contactid`,',') LIKE '%,".$contactid.",%') AND `deleted`=0");
	if(mysqli_num_rows($result) > 0) {
		$db_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `tickets_dashboard` FROM `field_config`"))['tickets_dashboard'];
		if($db_config == '') {
			$db_config = 'Business,Contact,Heading,Services,Status,Deliverable Date';
		}
		$db_config = explode(',',$db_config);
		$ticket_status_list = explode(',',get_config($dbc, 'ticket_status'));
		$project_types = [];
		foreach(explode(',',get_config($dbc, 'project_tabs')) as $type_name) {
			$project_types[preg_replace('/[^a-z_]/','',str_replace(' ','_',strtolower($type_name)))] = $type_name;
		}
		$tile_security = get_security($dbc, 'ticket');
		include('../Ticket/ticket_table.php');
	} else {
		echo "<h3>No ".TICKET_TILE." Found</h3>";
	}
} ?>