<?php include_once('../include.php');
$ticketid = $_GET['ticketid'] > 0 ? $_GET['ticketid'] : 0; ?>
<div class="col-sm-12">
	<h2>History</h2>
	<a class="pull-right" href="../blank_loading_page.php"><img class="slider-close" src="../img/icons/cancel.png"></a>
	<br /><br />
	<div class="clearfix"></div>
	<table class="table table-bordered">
		<tr>
			<th>Date</th>
			<th>User</th>
			<th>Description</th>
		</tr>
		<tr>
			<?php $ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `created_by`, `created_date` FROM `tickets` WHERE `ticketid`='$ticketid' AND `ticketid` > 0"));
			$name = get_contact($dbc, $ticket['created_by']);
			if($name == '' || $name == '-') {
				$name = 'Admin';
			} ?>
			<td data-title="Date"><?= $ticket['created_date'] ?></td>
			<td data-title="User"><?= $name ?></td>
			<td data-title="Description"><?= TICKET_NOUN ?> Created</td>
		</tr>
		<?php $result_tickets = mysqli_query($dbc, "SELECT * FROM ticket_history WHERE ticketid ='$ticketid' AND `ticketid` > 0 ORDER BY `date` ASC");
		while($history = mysqli_fetch_assoc($result_tickets)) {
			$name = get_contact($dbc, $history['userid']);
			if($name == '' || $name == '-') {
				$name = 'Admin';
			}
			if ( strpos($history['description'], 'sign_off_signature updated to')!==false ) {
				$description = substr($history['description'], 0, strpos($history['description'], 'sign_off_signature updated to')) . 'sign_off_signature updated';
			} else {
				$description = $history['description'];
			} ?>
			<tr>
				<td data-title="Date"><?= $history['date'] ?></td>
				<td data-title="User"><?= $name ?></td>
				<td data-title="Description"><?= html_entity_decode($description); ?></td>
			</tr>
		<?php } ?>
	</table>
</div>