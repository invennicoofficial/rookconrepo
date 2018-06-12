<?php $guest_access = true;
include_once ('../include.php');
$ticketid = filter_var($_GET['status'],FILTER_SANITIZE_STRING);
$get_ticket = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `ticketid`='$ticketid'"));
include_once ('../navigation.php'); ?>
<script>
function loadStatus() {
	$.ajax({
		url: 'status_info.php?ticketid=<?= $ticketid ?>',
		success: function(response) {
			$('.content_target').html(response);
			setTimeout(loadStatus, 30000);
		}
	});
}
$(document).ready(function() {
	loadStatus();
});
</script>
<div class="container">
	<div class="row">
		<h1><?= get_ticket_label($dbc, $get_ticket) ?> Status</h1>
		<div class="content_target"></div>
	</div>
</div>
<?php include('../footer.php'); ?>
