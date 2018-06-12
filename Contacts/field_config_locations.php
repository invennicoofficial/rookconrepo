<?php error_reporting(0);
include_once('../include.php'); ?>
<script>
$(document).ready(function() {
	$('[name=con_locations]').change(save_fields);
});
function save_fields() {
	var options = [];
	$('[name=con_locations]').each(function() {
		this.value = this.value.replace(',','');
		options.push(this.value);
	});
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=contact_configs',
		method: 'POST',
		data: {
			tile: '<?= FOLDER_NAME ?>',
			name: 'con_locations',
			value: options.join(',')
		}
	});
}
function add_option() {
	var row = $('.location-group').last();
	var clone = row.clone();
	clone.find('input').val('');
	row.after(clone);
	$('[name=con_locations]').off('change', save_fields).change(save_fields);
}
function remove_option(target) {
	if($('.location-group').length <= 0) {
		add_option();
	}
	$(target).closest('.form-group').remove();
	save_fields();
}
</script>
<div class="standard-dashboard-body-title">
    <h3>Settings - Locations:</h3>
</div>
<div class="standard-dashboard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
        <div class="form-horizontal block-group block-group-noborder">
            <?php $con_locations = explode(',',mysqli_fetch_array(mysqli_query($dbc, "SELECT con_locations FROM field_config_contacts WHERE `con_locations` IS NOT NULL AND `tile_name`='".FOLDER_NAME."'"))[0]);
            foreach($con_locations as $location) { ?>
                <div class="form-group location-group">
                    <label class="col-sm-4">Location:<br /><em>Location names cannot contain commas.</em></label>
                    <div class="col-sm-7">
                        <input name="con_locations" type="text" value="<?= $location ?>" class="form-control"/>
                    </div>
                    <div class="col-sm-1">
                        <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="add_option();">
                        <img src="../img/remove.png" class="inline-img pull-right" onclick="remove_option(this);">
                    </div>
                </div>
            <?php } ?>
        </div>
    </div><!-- .dashboard-item -->
</div><!-- .standard-dashboard-body-content -->