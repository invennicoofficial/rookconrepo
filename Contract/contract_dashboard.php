<?php include_once('../include.php');
checkAuthorised('contracts'); ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#mobile_tabs .panel-heading').click(loadPanel);
});
function markFavourite(img) {
	$(img).find('.fave').toggle();
	$.ajax({
		url: '../Contract/contract_ajax.php?action=mark_favourite&user=<?= $_SESSION['contactid'] ?>&id='+$(img).data('id')
	});
}
function markPinned(img) {
	$(img).closest('.pull-right').find('.pinned').toggle().find('select').off('change',savePinned).on('change',savePinned);
	$(img).closest('.pull-right').css('width',$(img).find('.pinned').is(':visible') ? '50em' : '20em');
	$(window).resize();
}
function savePinned() {
	$.ajax({
		url: '../Contract/contract_ajax.php?action=mark_pinned',
		method: 'POST',
		data: {
			users: $(this).val(),
			id: $(this).data('id')
		}
	});
	$(this).closest('.pinned').hide();
	$(this).closest('.pull-right').css('width','auto');
	$(window).resize();
}
function archive(id) {
	if(confirm('Are you sure you want to archive this Contract?')) {
		$.ajax({
			url: '../Contract/contract_ajax.php?action=archive',
			method: 'POST',
			data: {
				id: id
			},
			success: function(response) {
				// console.log(response);
				window.location.reload();
			}
		});
	}
	return false;
}
function loadPanel() {
	var panel = $(this).closest('.panel').find('.panel-body');
	panel.html('Loading...');
	$.ajax({
		url: panel.data('file-name'),
		method: 'POST',
		response: 'html',
		success: function(response) {
			panel.html(response);
		}
	});
}
</script>

<div class="show-on-mob panel-group block-panels col-xs-12 form-horizontal" style="background-color: #fff; padding: 0; margin-left: 5px; width: calc(100% - 10px);" id="mobile_tabs">
	<?php $mobile_i = 0;
	foreach($contract_tabs as $contract_tab) { ?>
		<div class="panel panel-default">
			<div class="panel-heading mobile_load">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_mobile_<?= $mobile_i ?>">
						<?= $contract_tab ?><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_mobile_<?= $mobile_i ?>" class="panel-collapse collapse">
				<div class="panel-body" data-file-name="contract_dashboard_list.php?tab=<?= $contract_tab ?>">
					Loading...
				</div>
			</div>
		</div>
		<?php $mobile_i++;
	} ?>
	<div class="panel panel-default">
		<div class="panel-heading mobile_load">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_mobile_<?= $mobile_i ?>">
					Reporting<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_mobile_<?= $mobile_i ?>" class="panel-collapse collapse">
			<div class="panel-body" data-file-name="reports.php">
				Loading...
			</div>
		</div>
	</div>
</div>

<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen standard-body form-horizontal'>
		<div class="standard-body-title">
			<h3><?= $_GET['tab'] ?></h3>
		</div>
		<div class="standard-body-content pad-top" style="padding: 5px;">
			<?php include('../Contract/contract_dashboard_list.php'); ?>
		</div>
	</div>
</div>