<?php error_reporting(0);
include_once('../include.php'); ?>
<script>
$(document).ready(function() {
	$('[name$=classification],[name$=class_regions]').change(save_fields);
});
function save_fields() {
	var options = [];
	$('[name=<?= FOLDER_NAME ?>_classification]').each(function() {
		this.value = this.value.replace(',','');
		options.push(this.value);
	});
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= FOLDER_NAME ?>_classification',
			value: options.join(',')
		}
	});
	var options = [];
	$('[name=<?= FOLDER_NAME ?>_class_regions]').each(function() {
		this.value = this.value.replace(',','');
		options.push(this.value);
	});
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= FOLDER_NAME ?>_class_regions',
			value: options.join(',')
		}
	});
	upload_logo();
}
function add_option() {
	destroyInputs($('.classification-group'));
	var row = $('.classification-group').last();
	var clone = row.clone();
	clone.find('input').val('');
	clone.find('select').val('');
	row.after(clone);
	initInputs('.classification-group');
	$('[name$=classification],[name$=class_regions]').off('change', save_fields).change(save_fields);
}
function remove_option(target) {
	if($('.classification-group').length <= 0) {
		add_option();
	}
	$(target).closest('.form-group').remove();
	save_fields();
}
function upload_logo(file) {
	var options = new FormData();
	var counter = 0;
	$('[name="class_logos"]').each(function() {
		if($(this).val() != '' && $(this).val() != undefined) {
			options.append(counter, $(this).val());
		} else if($(this).closest('.logo_block').find('[name="class_logos_upload"]')[0].files[0] != undefined) {
			options.append(counter, $(this).closest('.logo_block').find('[name="class_logos_upload"]')[0].files[0]);
		} else {
			options.append(counter, '');
		}
		counter++;
	});
	options.append('counter', counter);
	options.append('name', '<?= FOLDER_NAME ?>_class_logos');
	options.append('folder_name', '<?= FOLDER_URL ?>');
	$.ajax({
		processData: false,
		contentType: false,
		url: '../Contacts/contacts_ajax.php?action=classification_logos',
		method: 'POST',
		data: options,
		success: function(response) {
			$('[name="class_logos_upload"]').val('');
			if(response != '' && response != undefined) {
				$(file).closest('.logo_block').find('[name="class_logos"]').val(response);
				$(file).closest('.logo_block').find('a.logo_url').attr('href', response);
				$(file).closest('.logo_block').find('.logo_exists').show();
				$(file).closest('.logo_block').find('.logo_upload').hide();
			}
		}
	})
}
function delete_logo(link) {
	var logo_block = $(link).closest('.logo_block');
	$(logo_block).find('[name="class_logos"]').val('');
	$(logo_block).find('.logo_exists').hide();
	$(logo_block).find('.logo_upload').show();
	upload_logo();
}
</script>
<div class="standard-dashboard-body-title">
    <h3>Settings - Classifications:</h3>
</div>
<div class="standard-dashboard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
        <div class="form-horizontal block-group block-group-noborder">
            <div class="hide-titles-mobile">
                <label class="col-sm-4">Classification<br /><em>Cannot contain commas</em></label>
                <label class="col-sm-4">Region<br /><em>Optional: Set a classification to be only for one region</em></label>
                <label class="col-sm-3">Logo</label>
                <div class="clearfix"></div>
            </div>
            <?php $regions = explode(',',get_config($dbc, '%_region', true));
            $classifications = explode(',',get_config($dbc, FOLDER_NAME.'_classification'));
            $class_regions = explode(',',get_config($dbc, FOLDER_NAME.'_class_regions'));
            $class_logos = explode('*#*',get_config($dbc, FOLDER_NAME.'_class_logos'));
            foreach($classifications as $i => $classification) { ?>
                <div class="form-group classification-group">
                    <div class="col-sm-4">
                        <label class="show-on-mob">Classification:<br /><em>Classification names cannot contain commas.</em></label>
                        <input name="<?= FOLDER_NAME ?>_classification" type="text" value="<?= $classification ?>" class="form-control"/>
                    </div>
                    <div class="col-sm-4">
                        <label class="show-on-mob">Region:<br /><em>Optional: You can set a classification to be only for one region.</em></label>
                        <select name="<?= FOLDER_NAME ?>_class_regions" data-placeholder="Select a Region (Optional)" value="<?= $class_regions[$i] ?>" class="chosen-select-deselect">
                            <option <?= $class_regions[$i] == 'ALL' || $class_regions[$i] == '' ? 'selected' : '' ?> value="ALL">All Regions</option>
                            <?php foreach($regions as $region) { ?>
                                <option <?= $class_regions[$i] == $region ? 'selected' : '' ?> value="<?= $region ?>"><?= $region ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-3 logo_block">
                        <input type="hidden" name="class_logos" value="<?= $class_logos[$i] ?>">
                        <label class="show-on-mob">Logo:</label>
                        <div class="logo_exists" <?= empty($class_logos[$i]) ? 'style="display:none;"' : '' ?>>
                            <a href="<?= $class_logos[$i] ?>" target="_blank" class="logo_url">View</a> | <a href="" onclick="delete_logo(this); return false;">Delete</a>
                        </div>
                        <div class="logo_upload" <?= !empty($class_logos[$i]) ? 'style="display:none;"' : '' ?>>
                            <input type="file" name="class_logos_upload" class="form-control" onchange="upload_logo(this); save_fields();">
                        </div>
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