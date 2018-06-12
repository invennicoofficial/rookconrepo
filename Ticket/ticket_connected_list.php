<?php include_once('../include.php');
if(!isset($ticketid) && isset($_GET['ticketid']) && basename($_SERVER['SCRIPT_FILENAME']) == 'ticket_connected_list.php') {
	ob_clean();
	$ticketid = filter_var($_GET['ticketid'],FILTER_SANITIZE_STRING);
}
$tickets = mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `main_ticketid` IN (SELECT `main_ticketid` FROM `tickets` WHERE `ticketid`='$ticketid' AND `main_ticketid` > 0) AND `deleted`=0");
if(mysqli_num_rows($tickets) > 0) { ?>
	<h4><?= TICKET_TILE ?> Connected to Current <?= TICKET_NOUN ?></h4>
	<table class='table table-bordered'>
	<tr class='hidden-sm hidden-xs'>
		<th><?= TICKET_NOUN ?> #</th>
		<th>Description</th>
	</tr>
	<?php while($row = mysqli_fetch_array($tickets)) {
		echo '<tr>';
		echo '<td data-title="'.TICKET_NOUN.' #"><a href="?ticketid='.$row['ticketid'].'">'.($row['main_ticketid'] > 0 ? $row['main_ticketid'].' '.$row['sub_ticket'] : $row['ticketid']).'</a></td>';
		echo '<td data-title="Description"><a href="?ticketid='.$row['ticketid'].'">'.get_ticket_label($dbc, $row).'</a></td>';
		echo '</tr>';
	}
} ?>
