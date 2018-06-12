<?php if($access_complete) { ?>
	<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Work History</h3>') ?>
	<div id="no-more-tables">
		<table class="table table-bordered">
			<tr class="hidden-sm hidden-xs">
				<th>Date</th>
				<?php if(strpos($value_config, ',Work History Services,') !== FALSE) { ?>
					<th>Services</th>
				<?php } ?>
				<?php if(strpos($value_config, ',Work History Service Sub Totals,') !== FALSE) { ?>
					<th>Sub Totals per Service</th>
				<?php } ?>
				<?php if(strpos($value_config, ',Work History Staff Tasks,') !== FALSE) { ?>
					<th>Staff</th>
					<th>Task</th>
					<th>Hours</th>
				<?php } ?>
				<?php if(strpos($value_config, ',Work History Materials,') !== FALSE) { ?>
					<th>Materials</th>
				<?php } ?>
				<th>Total</th>
				<th>Notes</th>
			</tr>
			<?php $tickets = $dbc->query("SELECT * FROM (SELECT `tickets`.*,`tickets`.`to_do_date` `ticket_date` FROM `tickets` WHERE `tickets`.`deleted`=0 AND IFNULL(`tickets`.`to_do_date`,'') != '' UNION SELECT `tickets`.*, `ticket_attached`.`date_stamp` `ticket_date` FROM `tickets` RIGHT JOIN `ticket_attached` ON `tickets`.`ticketid` = `ticket_attached`.`ticketid` WHERE `tickets`.`deleted`=0 AND `ticket_attached`.`deleted`=0 AND `ticket_attached`.`src_table` LIKE 'Staff%' AND IFNULL(`ticket_attached`.`date_stamp`,'') != IFNULL(`tickets`.`to_do_date`,'') GROUP BY `ticket_attached`.`date_stamp`) `all_tickets` WHERE `ticketid`='$ticketid'");
			while($ticket = $tickets->fetch_assoc()) {
				$active = 0;
				$total_cost = 0.00;
				$services_cost = [];
				$services = [];
				$qty = explode(',',$ticket['service_qty']);
				foreach(explode(',',$ticket['serviceid']) as $i => $service) {
					if($service > 0) {
						$service = $dbc->query("SELECT `services`.`heading`, `rate`.`cust_price` FROM `services` LEFT JOIN `company_rate_card` `rate` ON `services`.`serviceid`=`rate`.`item_id` AND `rate`.`tile_name` LIKE 'Services' WHERE `services`.`serviceid`='$service'")->fetch_assoc();
						$services[] = $service['heading'].($qty[$i] > 0 ? ' x '.$qty[$i] : '');
						$services_cost[] = '$'.number_format($service['cust_price'],2);
					}
				} ?>
				<tr>
					<td data-title="Date"><?= $ticket['ticket_date'] ?><img class="inline-img pull-right cursor-hand" onclick="overlayIFrameSlider('edit_ticket_tab.php?tab=ticket_summary&ticketid=<?= $ticketid ?>&date=<?= $ticket['ticket_date'] ?>','auto',true,true);" src="../img/icons/eyeball.png"></td>
					<?php if(strpos($value_config, ',Work History Services,') !== FALSE) {
						$total_cost += number_format($ticket['services_cost'],2); ?>
						<td data-title="Services"><?= implode('<br />',$services) ?></td>
					<?php } ?>
					<?php if(strpos($value_config, ',Work History Service Sub Totals,') !== FALSE) { ?>
						<td data-title="Sub Totals per Service"><?= implode('<br />',$services_cost) ?></td>
					<?php } ?>
					<?php if(strpos($value_config, ',Work History Staff Tasks,') !== FALSE) {
						$staff_tasks_staff = [];
						$staff_tasks_task = [];
						$staff_tasks_hours = [];
						$sql = "SELECT * FROM `ticket_attached` WHERE `ticketid` = '{$ticket['ticketid']}' AND `deleted` = 0 AND `src_table` = 'Staff_Tasks' AND `date_stamp` = '{$ticket['ticket_date']}'";
						$query = mysqli_query($dbc, $sql);
						while($row = mysqli_fetch_assoc($query)) {
							$staff_tasks_staff[] = get_contact($dbc, $row['item_id']);
							$staff_tasks_task[] = $row['position'];
							$staff_tasks_hours[] = number_format($row['hours_tracked'],2);
						} ?>
						<td data-title="Staff"><?= implode("<br />", $staff_tasks_staff) ?></td>
						<td data-title="Task"><?= implode("<br />", $staff_tasks_task) ?></td>
						<td data-title="Hours"><?= implode("<br />", $staff_tasks_hours) ?></td>
					<?php } ?>
					<?php if(strpos($value_config, ',Work History Materials,') !== FALSE) {
						$materials = [];
						$sql = "SELECT * FROM `ticket_attached` WHERE `ticketid` = '{$ticket['ticketid']}' AND `deleted` = 0 AND `src_table` = 'material' AND `date_stamp` = '{$ticket['ticket_date']}'";
						$query = mysqli_query($dbc, $sql);
						while($row = mysqli_fetch_assoc($query)) {
							if($row['description'] != '') {
								$materials[] = $row['description'].': '.$row['qty'];
							} else {
								$materials[] = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `name` FROM `material` WHERE `materialid` = '{$row['item_id']}'"))['name'].': '.$row['qty'];
							}
						} ?>
						<td data-title="Materials"><?= implode("<br />", $materials) ?></td>
					<?php } ?>
					<td data-title="Total">$<?= number_format($total_cost,2); ?></td>
					<td data-title="Notes"><?php $notes = $dbc->query("SELECT * FROM `ticket_comment` WHERE `ticketid`='{$ticket['ticketid']}' AND `type`='administration_note' AND `deleted`=0") ?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>