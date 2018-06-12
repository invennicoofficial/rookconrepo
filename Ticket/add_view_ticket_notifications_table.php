<?php include_once('../include.php');

if(basename($_SERVER['SCRIPT_FILENAME']) == 'add_view_ticket_notifications_table.php') {
	$ticketid = $_GET['ticketid'];
} ?>
<table class="table table-bordered">
	<tr>
		<th>Staff</th>
		<th>Contacts</th>
		<th>Sender Name</th>
		<th>Sender Email</th>
		<th>Status</th>
		<th>Send Date</th>
		<th>Follow Up Date</th>
		<th>Log</th>
	</tr>
	<?php $noti_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `ticket_notifications` WHERE `ticketid` = '$ticketid' AND `deleted` = 0"),MYSQLI_ASSOC);
	foreach ($noti_list as $noti) { ?>
		<tr>
			<td data-title="Staff"><?php $staffs_list = [];
				foreach (explode(',', $noti['staffid']) as $noti_staffid) {
					$staffs_list[] = get_contact($dbc, $noti_staffid);
				}
				echo implode(', ', $staffs_list); ?>
			</td>
			<td data-title="Contacts"><?php $contacts_list = [];
				foreach (explode(',', $noti['contactid']) as $noti_contactid) {
					$contacts_list[] = get_contact($dbc, $noti_contactid);
				}
				echo implode(', ', $contacts_list); ?>
			</td>
			<td data-title="Sender Name"><?= $noti['sender_name'] ?></td>
			<td data-title="Sender Email"><?= $noti['sender_email'] ?></td>
			<td data-title="Status"><?= $noti['status'] ?></td>
			<td data-title="Send Date"><?= $noti['send_date'] ?></td>
			<td data-title="Follow Up Date"><?= $noti['follow_up_date'] ?></td>
			<td data-title="Log"><?= str_replace("\n", "<br>", $noti['log']) ?></td>
		</tr>
	<?php } ?>
</table>