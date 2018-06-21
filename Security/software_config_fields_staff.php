<?php error_reporting(0);
include_once('../include.php');
$security_level = $_GET['level'];
$security_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_staff_security` WHERE `security_level`='$security_level'"));
$subtabs_hidden = explode(',', $security_config['subtabs_hidden']);
$subtabs_viewonly = explode(',', $security_config['subtabs_viewonly']);
$fields_hidden = explode(',', $security_config['fields_hidden']);
$fields_viewonly = explode(',', $security_config['fields_viewonly']);
?>
<script>
function save_options(input) {
	if($(input).hasClass('contact_subtabs')) {
		if($(input).data('option') != 'disable') {
			$(input).closest('.subtab_block').find('.subtab_fields').show();
		} else {
			$(input).closest('.subtab_block').find('.subtab_fields').hide();
		}
	}
	security_level = $('[name="security_level"]').val();
	subtabs_hidden = $('.contact_subtabs').map(function() {
		if($(this).is(':checked') && $(this).data('option') == 'disable') {
			return this.value;
		}
	}).get();
	subtabs_viewonly = $('.contact_subtabs').map(function() {
		if($(this).is(':checked') && $(this).data('option') == 'viewonly') {
			return this.value;
		}
	}).get();
	fields_hidden = $('.contact_fields').map(function() {
		if($(this).is(':checked') && $(this).data('option') == 'disable') {
			return this.value;
		}
	}).get();
	fields_viewonly = $('.contact_fields').map(function() {
		if($(this).is(':checked') && $(this).data('option') == 'viewonly') {
			return this.value;
		}
	}).get();

	$.ajax({
		url: '../Security/security_ajax_all.php?fill=staff_security_settings',
		type: 'POST',
		data: { security_level: security_level, subtabs_hidden: subtabs_hidden, subtabs_viewonly: subtabs_viewonly, fields_hidden: fields_hidden, fields_viewonly: fields_viewonly },
		success: function(response) {
			// console.log(response);
		}
	});
}
function change_type() {
	security_level = $('[name="security_level"]').val();
	window.location.href = '?security_level='+security_level;
}
$(document).on('change', 'select[name="security_level"]', function() { change_type(); });
</script>
<form class="form-horizontal" style="background: none;">
    <div class="form-group block-group-noborder">
        <input type="hidden" name="security_level" value="<?= $security_level ?>">
        <?php $staff_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
        if(strpos($staff_tabs, ',Software ID,') === FALSE) {
            $staff_tabs .= 'Software ID,';
        }
        $staff_tabs = array_filter(explode(',',$staff_tabs));
        foreach($staff_tabs as $staff_tab) { ?>
            <div class="form-group subtab_block">
                <label class="col-sm-4 control-label"><?= $staff_tab ?>:</label>
                <div class="col-sm-8">
                    <label><input type="radio" name="subtab_<?= config_safe_str($staff_tab) ?>" data-option="edit" value="<?= $staff_tab ?>" <?= (!in_array($staff_tab, $subtabs_hidden) && !in_array($staff_tab, $subtabs_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">Editable</label>&nbsp;&nbsp;
                    <label><input type="radio" name="subtab_<?= config_safe_str($staff_tab) ?>" data-option="disable" value="<?= $staff_tab ?>" <?= (in_array($staff_tab, $subtabs_hidden) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">Disabled</label>&nbsp;&nbsp;
                    <label><input type="radio" name="subtab_<?= config_safe_str($staff_tab) ?>" data-option="viewonly" value="<?= $staff_tab ?>" <?= (in_array($staff_tab, $subtabs_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">View Only</label>&nbsp;&nbsp;
                    <div class="clearfix"></div>
                    <?php $field_config = mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tab`='Staff' AND IFNULL(`accordion`,'') != '' AND `subtab` = '".config_safe_str($staff_tab)."' AND IFNULL(`contacts`,'') != '' ORDER BY IFNULL(`order`,`configcontactid`)");
                    if(mysqli_num_rows($field_config) > 0) {?>
                        <div class="block-group subtab_fields" <?= (!in_array($staff_tab, $subtabs_hidden) ? '' : 'style="display:none;"') ?>>
                        <?php while($field_accordion = mysqli_fetch_assoc($field_config)) {
                            foreach(array_filter(explode(',', $field_accordion['contacts'])) as $field_option) { ?>
                                <div class="col-sm-6"><?= $field_option ?></div>
                                <div class="col-sm-2"><label class="form-checkbox"><input type="radio" name="field_<?= config_safe_str($field_option) ?>" data-option="edit" value="<?= $field_option ?>" <?= (!in_array($field_option, $fields_hidden) && !in_array($field_option, $fields_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_fields">Editable</label></div>
                                <div class="col-sm-2"><label class="form-checkbox"><input type="radio" name="field_<?= config_safe_str($field_option) ?>" data-option="disable" value="<?= $field_option ?>" <?= (in_array($field_option, $fields_hidden) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_fields">Disabled</label></div>
                                <div class="col-sm-2"><label class="form-checkbox"><input type="radio" name="field_<?= config_safe_str($field_option) ?>" data-option="viewonly" value="<?= $field_option ?>" <?= (in_array($field_option, $fields_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_fields">View Only</label></div>
                                <div class="clearfix"></div>
                            <?php }
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</form>