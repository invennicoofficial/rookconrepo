<?php error_reporting(0);
include_once('../include.php'); ?>
<script>
$(document).ready(function() {
	$('[name=<?= FOLDER_NAME ?>_tabs],[type=radio],[type=checkbox]').change(save_fields);
	$('.block-group').sortable({
		connectWith: '.block-group',
		handle: '.drag-handle',
		items: '.tab-group',
		update: save_fields
	});
});
function save_fields() {
	var options = [];
	var business_category = '';
	$('[name=<?= FOLDER_NAME ?>_tabs]').each(function() {
		this.value = this.value.replace(',','');
		options.push(this.value);
		if($(this).closest('.tab-group').find('[type=radio]').is(':checked')) {
			business_category = this.value;
		}
	});
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= FOLDER_NAME ?>_tabs',
			value: options.join(','),
			business_category: business_category
		}
	});

	var multiple_categories = 0;
	if($('[name=<?= FOLDER_NAME ?>_multiple_categories]').is(':checked')) {
		multiple_categories = 1;
	}
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= FOLDER_NAME ?>_multiple_categories',
			value: multiple_categories
		}
	});
}
function add_option() {
	var row = $('.tab-group').last();
	var clone = row.clone();
	clone.find('input').val('');
	row.after(clone);
	$('[name=<?= FOLDER_NAME ?>_tabs]').off('change', save_fields).change(save_fields);
}
function remove_option(target) {
	if($('.tab-group').length <= 0) {
		add_option();
	}
	$(target).closest('.form-group').remove();
	save_fields();
}
</script>
<div class="standard-dashboard-body-title">
    <h3>Settings - Categories:</h3>
</div>
<div class="standard-dashboard-body-content">
    <div class="dashboard-item dashboard-item2 full-height">
        <div class="form-horizontal block-group block-group-noborder full-height">
            <label class="hide-titles-mob col-sm-4 pull-right"><span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Select the Category to which you can attach other contacts, such as business."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                Attach To Category</label><div class="clearfix"></div>
            <?php $tabs = explode(',',get_config($dbc, FOLDER_NAME.'_tabs'));
            $staff = array_search('Staff',$tabs);
            if($staff !== FALSE) {
                unset($tabs[$staff]);
            }
            foreach($tabs as $i => $tab) { ?>
                <div class="form-group tab-group">
                    <label class="col-sm-4">Contact Category:<br /><em>Category names cannot contain commas.</em></label>
                    <div class="col-sm-5">
                        <input name="<?= FOLDER_NAME ?>_tabs" type="text" value="<?= $tab ?>" class="form-control"/>
                    </div>
                    <div class="col-sm-1">
                        <label class="show-on-mob">Attach Contacts to This Category</label>
                        <input type="radio" name="business_category" <?= BUSINESS_CAT == $tab ? 'checked' : '' ?> value="<?= $i ?>">
                    </div>
                    <div class="col-sm-2">
                        <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="add_option();">
                        <img src="../img/remove.png" class="inline-img pull-right" onclick="remove_option(this);">
                        <img src="../img/icons/drag_handle.png" class="inline-img drag-handle pull-right">
                    </div>
                </div>
            <?php } ?>
            <hr>
            <div class="form-group">
            	<label class="col-sm-4 control-label">Allow Multiple Categories:</label>
            	<div class="col-sm-8">
            		<?php $multiple_categories = get_config($dbc, FOLDER_NAME.'_multiple_categories'); ?>
            		<label class="form-checkbox"><input type="checkbox" name="<?= FOLDER_NAME ?>_multiple_categories" value="1" <?= $multiple_categories == 1 ? 'checked' : '' ?>> Enable</label>
            	</div>
            </div>
        </div>
    </div><!-- .dashboard-item -->
</div><!-- .standard-dashboard-body-content -->