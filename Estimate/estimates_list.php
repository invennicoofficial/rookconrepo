<?php error_reporting(0);
include_once('../include.php');
checkAuthorised('estimate');
$status = filter_var($_GET['status'], FILTER_SANITIZE_STRING);
$count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) FROM `estimate` WHERE '$status' IN (`status`,'all') AND `estimate`.`deleted`=0 AND (`status`!='$closed_status' OR `status_date` >= '$closed_date')"))[0]; ?>
<script>
var estLoaded = 10;
var max = 0<?= $count ?>;
var loadMore = true;
$(document).ready(function() {
	loadEstimates(0,estLoaded);
	$('.main-screen .main-screen').scroll(function() {
		if(this.scrollTop > this.scrollHeight - $(this).height() - 100 && estLoaded < max && loadMore) {
			loadMore = false;
			loadEstimates(estLoaded, estLoaded + 5);
			estLoaded += 5;
		}
	});
});
function loadEstimates(start, end) {
	$.ajax({
		url: 'estimates_list_load.php?status=<?= $_GET['status'] ?>&startdate=<?= $_GET['startdate'] ?>&enddate=<?= $_GET['enddate'] ?>&staffid=<?= $_GET['staffid'] ?>&start='+start+'&end='+end,
		method: 'POST',
		dataType: 'html',
		success: function(response) {
			$("select[class^=chosen]").removeClass("chzn-done").css("display", "block").next().remove();
			$('#display_screen').append(response);
			$('input,select').off('change', saveField).change(saveField);
			loadMore = true;
		}
	});
}
function saveField() {
	if($(this).data('table') != '') {
		$.ajax({
			url: 'estimates_ajax.php?action=estimate_fields',
			method: 'POST',
			data: {
				id: $(this).data('id'),
				id_field: $(this).data('identifier'),
				table: $(this).data('table'),
				field: this.name,
				value: this.value,
				estimate: $(this).data('estimate')
			},
			success: function(response) {
				console.log(response);
			}
		});
		$(this).blur();
	}
}
</script>
<?php if($_GET['startdate'] != '' || $_GET['enddate'] != '' || $_GET['staffid'] > 0) {
	echo "<h3>Estimates".($_GET['staffid'] > 0 ? ' for '.get_contact($dbc, $_GET['staffid']) : '').($_GET['startdate'] != '' ? ' from '.$_GET['startdate'] : '').($_GET['enddate'] != '' ? ' until '.$_GET['enddate'] : '')."</h3>";
} ?>
<div class="form-horizontal" id="display_screen">
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'estimates_list.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>