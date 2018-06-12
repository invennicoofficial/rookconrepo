<?php error_reporting(0);
include_once('../include.php'); ?>
<script>
$(document).ready(function() {
	$('input').change(save_fields);
});
function save_fields() {
	var options = [];
	var colours = [];
	$('[name=<?= FOLDER_NAME ?>_region]').each(function() {
		this.value = this.value.replace(',','');
		options.push(this.value);
		colours.push($(this).closest('.region-group').find('[name=colour]').val());
	});
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= FOLDER_NAME ?>_region',
			value: options.join(',')
		}
	});
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= FOLDER_NAME ?>_region_colour',
			value: colours.join(',')
		}
	});
}
function add_option() {
	var row = $('.region-group').last();
	var clone = row.clone();
	clone.find('[type=text]').val('');
	clone.find('[type=color]').val('#D7FFFF');
	row.after(clone);
	$('[name=<?= FOLDER_NAME ?>_region]').off('change', save_fields).change(save_fields);
}
function remove_option(target) {
	if($('.region-group').length <= 0) {
		add_option();
	}
	$(target).closest('.form-group').remove();
	save_fields();
}
</script>
<div class="standard-dashboard-body-title">
    <h3>Settings - Regions:</h3>
</div>
<div class="standard-dashboard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
        <div class="form-horizontal block-group block-group-noborder">
            <?php $regions = explode(',',get_config($dbc, FOLDER_NAME.'_region'));
            $colours = explode(',',get_config($dbc, FOLDER_NAME.'_region_colour'));
            foreach($regions as $i => $region) { ?>
                <div class="form-group region-group">
                    <label class="col-sm-4">Region:<br /><em>Region names cannot contain commas.</em></label>
                    <div class="col-sm-5">
                        <input name="<?= FOLDER_NAME ?>_region" type="text" value="<?= $region ?>" class="form-control"/>
                    </div>
                    <div class="col-sm-2">
                        <input name="colour" type="color" value="<?= empty($colours[$i]) ? '#D7FFFF' : $colours[$i] ?>" class="form-control"/>
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