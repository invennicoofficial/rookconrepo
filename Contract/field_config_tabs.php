<?php include_once('../include.php');
checkAuthorised('contracts');
$contract_tabs = explode('#*#',mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs` FROM `field_config_contracts`"))['contract_tabs']); ?>

<script type="text/javascript">
$(document).ready(function() {
	$('.main-screen').sortable({
		handle: '.drag-handle',
		items: '.contract_tab',
		update: saveTabs
	});
	reloadOnChange();
});
function reloadOnChange() {
	$('.contract_tab input').change(saveTabs);
}
function saveTabs() {
	var contract_tabs = [];
	$('[name="contract_tabs"]').each(function() {
		contract_tabs.push($(this).val());
	});
	$.ajax({
		url: '../Contract/contract_ajax.php?action=settings_tabs',
		method: 'POST',
		data: { contract_tabs: contract_tabs },
		success: function() {

		}
	});
}
function addTab() {
	var block = $('.contract_tab').last();
	var clone = $(block).clone();

	$(clone).find('input').val('');
	$(block).after(clone);

	reloadOnChange();
}
function removeTab(img) {
	if($('.contract_tab').length <= 1) {
		addTab();
	}

	$(img).closest('.contract_tab').remove();
	saveTabs();
}
</script>

<?php foreach ($contract_tabs as $contract_tab) { ?>
	<div class="form-group contract_tab">
		<label class="col-sm-2 control-label">Tab:</label>
		<div class="col-sm-8">
			<input type="text" name="contract_tabs" class="form-control" value="<?= $contract_tab ?>">
		</div>
		<div class="col-sm-2">
			<img src="../img/icons/drag_handle.png" style="height: 1.5em; margin: 0 0.25em;" class="pull-right drag-handle">
			<img src="../img/icons/ROOK-add-icon.png" style="height: 1.5em; margin: 0 0.25em;" class="pull-right" onclick="addTab();">
			<img src="../img/remove.png" style="height: 1.5em; margin: 0 0.25em;" class="pull-right" onclick="removeTab(this);">
		</div>
		<div class="clearfix"></div>
	</div>
<?php } ?>