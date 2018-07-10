<?php $guest_access = true;
include_once ('../include.php');
$details = json_decode(decryptIt($_GET['s']), TRUE);
$ticketid = filter_var($details['ticket'],FILTER_SANITIZE_STRING);
$stopid = filter_var($details['stop'],FILTER_SANITIZE_STRING); ?>
<script>
function loadStatus() {
	$.ajax({
		url: 'status_info.php?ticketid=<?= $ticketid ?>&stopid=<?= $stopid ?>',
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
<div class="container" style="height: 100vh; width: 100vw;">
	<div class="row">
		<div class="content_target"></div>
	</div>
</div>