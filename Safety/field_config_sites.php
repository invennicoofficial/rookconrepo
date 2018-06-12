<script>
$(document).ready(function() {
	$('[name=sites]').change(saveSites);
	sortSites();
});
function addSite() {
	var src = $('[name=sites]:visible').last().closest('.form-group');
	var clone = src.clone();
	clone.find('input').val('');
	src.after(clone);
	clone.find('input').focus();
	$('[name=sites]').off('change',saveSites).change(saveSites);
	sortSites();
}
function remSite(field) {
	if($('[name=sites]:visible').length == 1) {
		addSite();
	}
	$(field).closest('.form-group').remove();
	saveSites();
}
function saveSites() {
	tabs = [];
	$('[name=sites]').each(function() { tabs.push(this.value); });
	$.post('safety_ajax.php?action=setting_sites', { sites: tabs });
}
function sortSites() {
	$('.block-group').sortable({
		handle: '.drag-handle',
		items: '.form-group',
		update: saveSites
	});
}
</script>
<h3>Add Safety Sites</h3>
<div class="block-group">
	<?php if(empty($site_list)) {
		$site_list = [''];
	}
	foreach($site_list as $site_name) { ?>
		<div class="form-group">
			<label class="col-sm-4">Safety Site Names (cannot contain commas):</label>
			<div class="col-sm-7">
				<input type="text" name="sites" value="<?= $site_name ?>" class="form-control">
			</div>
			<div class="col-sm-1">
				<img class="inline-img pull-right drag-handle" src="../img/icons/drag_handle.png">
				<img class="inline-img pull-right" src="../img/icons/ROOK-add-icon.png" onclick="addSite();">
				<img class="inline-img pull-right" src="../img/remove.png" onclick="remSite(this);">
			</div>
		</div>
	<?php } ?>
</div>