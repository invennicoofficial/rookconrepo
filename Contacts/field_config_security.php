<?php error_reporting(0);
include_once('../include.php');
include_once('../Contacts/edit_fields.php');
$folder = FOLDER_NAME;
if(!empty($_POST['folder'])) {
	$folder = $_POST['folder'];
}
$current_type = $_GET['type'];
$security_level = $_GET['security_level'];
if(!empty($_POST['type'])) {
	$contact_type_arr = explode('*#*', $_POST['type']);
	$current_type = $contact_type_arr[0];
	$security_level = $contact_type_arr[1];
}
$field_config = explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tile_name`='".FOLDER_NAME."' AND `tab`='$current_type' AND `subtab` = '**no_subtab**'"))[0] . ',' . mysqli_fetch_array(mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tile_name`='".FOLDER_NAME."' AND `tab`='$current_type' AND `subtab` = 'additions'"))[0]);
$security_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_contacts_security` WHERE `tile_name`='".FOLDER_NAME."' AND `category`='$current_type' AND `security_level`='$security_level'"));
$subtabs_hidden = explode(',', $security_config['subtabs_hidden']);
$subtabs_viewonly = explode(',', $security_config['subtabs_viewonly']);
$fields_hidden = explode(',', $security_config['fields_hidden']);
$fields_viewonly = explode(',', $security_config['fields_viewonly']);
$profile_access = $security_config['profile_access'];
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
	contact_type = $('[name="contact_type"]').val();
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
	profile_access = $('[name="profile_access"]:checked').val();
	$.ajax({
		url: '../Contacts/contacts_ajax.php?action=contacts_security_settings',
		type: 'POST',
		data: { tile_name: '<?= FOLDER_NAME ?>', category: contact_type, security_level: security_level, subtabs_hidden: subtabs_hidden, subtabs_viewonly: subtabs_viewonly, fields_hidden: fields_hidden, fields_viewonly: fields_viewonly, profile_access: profile_access },
		success: function(response) {
			console.log(response);
		}
	});
}
function change_type() {
	contact_type = $('[name="contact_type"]').val();
	security_level = $('[name="security_level"]').val();
	<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_security.php') { ?>
		contact_type = $('[name="contact_type"]').val()+'*#*'+$('[name="security_level"]').val();
		loadPanel();
	<?php } else { ?>
		window.location.href = 'contacts_inbox.php?settings=security&type='+contact_type+'&security_level='+security_level;
	<?php } ?>
}
$(document).on('change', 'select[name="contact_type"]', function() { change_type(); });
$(document).on('change', 'select[name="security_level"]', function() { change_type(); });
</script>
<div class="standard-dashboard-body-title">
    <h3>Settings - Security:</h3>
</div>
<div class="standard-dashboard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
        <form class="form-horizontal">
        <div class="form-group block-group block-group-noborder">
            <div class="form-group">
                <label class="col-sm-4 control-label">Contact Type:</label>
                <div class="col-sm-8">
                    <select name="contact_type" data-placeholder="Select a Contact Type" class="chosen-select-deselect">
                        <?php $contact_types = explode(',', get_config($dbc, $folder."_tabs"));
                        $staff = array_search('Staff',$contact_types);
                        if($staff !== FALSE) {
                            unset($contact_types[$staff]);
                        }
                        foreach($contact_types as $type_name) {
                            if($current_type == '') {
                                $current_type = $type_name;
                            }
                            echo "<option ".($current_type == $type_name ? 'selected' : '')." value='$type_name'>$type_name</option>";
                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Security Level:</label>
                <div class="col-sm-8">
                    <select name="security_level" data-placeholder="Select a Security Level" class="chosen-select-deselect">
                        <?php $on_security = get_security_levels($dbc);
                        foreach($on_security as $security_name => $value) {
                            if($security_level == '') {
                                $security_level = $value;
                            }
                            echo "<option ".($security_level == $value ? 'selected' : '')." value='$value'>$security_name</option>";
                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Profile Access:</label>
                <div class="col-sm-8">
                    <label><input type="radio" name="profile_access" value="" <?= (empty($profile_access) ? 'checked' : '') ?> onchange="save_options(this);">View ID Card Only</label>&nbsp;&nbsp;
                    <label><input type="radio" name="profile_access" value="enable" <?= ($profile_access == 'enable' ? 'checked' : '') ?> onchange="save_options(this);">Enable</label>&nbsp;&nbsp;
                    <label><input type="radio" name="profile_access" value="disable" <?= ($profile_access == 'disable' ? 'checked' : '') ?> onchange="save_options(this);">Disable</label>&nbsp;&nbsp;
                </div>
            </div>
            <?php foreach($field_config as $field_name) {
                if(substr($field_name, 0, 4) == 'acc_') {
                    foreach($tab_list as $tab_label => $tab_data) {
                        if(($field_name == 'acc_'.$tab_data[0] || ($field_name == 'acc_guardian_information' && $tab_data[0] == 'sibling_information')) && $tab_label != 'Checklist') { ?>
                            <div class="form-group subtab_block">
                                <label class="col-sm-4 control-label"><?= $tab_label ?>:</label>
                                <div class="col-sm-8">
                                    <label><input type="radio" name="subtab_<?= $tab_data[0] ?>" data-option="edit" value="<?= $tab_data[0] ?>" <?= (!in_array($tab_data[0], $subtabs_hidden) && !in_array($tab_data[0], $subtabs_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">Editable</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="subtab_<?= $tab_data[0] ?>" data-option="disable" value="<?= $tab_data[0] ?>" <?= (in_array($tab_data[0], $subtabs_hidden) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">Disabled</label>&nbsp;&nbsp;
                                    <label><input type="radio" name="subtab_<?= $tab_data[0] ?>" data-option="viewonly" value="<?= $tab_data[0] ?>" <?= (in_array($tab_data[0], $subtabs_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">View Only</label>&nbsp;&nbsp;
                                    <div class="clearfix"></div>
                                    <div class="block-group subtab_fields" <?= (!in_array($tab_data[0], $subtabs_hidden) ? '' : 'style="display:none;"') ?>>
                                        <?php foreach($field_config as $field_option) {
                                            if(in_array($field_option,$tab_data[1])) { ?>
                                                <div class="col-sm-6"><?= $field_option ?></div>
                                                <div class="col-sm-2"><label class="form-checkbox"><input type="radio" name="field_<?= $field_option ?>" data-option="edit" value="<?= $field_option ?>" <?= (!in_array($field_option, $fields_hidden) && !in_array($field_option, $fields_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_fields">Editable</label></div>
                                                <div class="col-sm-2"><label class="form-checkbox"><input type="radio" name="field_<?= $field_option ?>" data-option="disable" value="<?= $field_option ?>" <?= (in_array($field_option, $fields_hidden) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_fields">Disabled</label></div>
                                                <div class="col-sm-2"><label class="form-checkbox"><input type="radio" name="field_<?= $field_option ?>" data-option="viewonly" value="<?= $field_option ?>" <?= (in_array($field_option, $fields_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_fields">View Only</label></div>
                                                <div class="clearfix"></div>
                                            <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }
                }
            }
            foreach($tab_list as $tab_label => $tab_data) {
                if(in_array_any($tab_data[1],$field_config) && !in_array('acc_'.$tab_data[0],$field_config) && $tab_data[0] != 'sibling_information' && $tab_label != 'Checklist') { ?>
                    <div class="form-group subtab_block">
                        <label class="col-sm-4 control-label"><?= $tab_label ?>:</label>
                        <div class="col-sm-8">
                            <label><input type="radio" name="subtab_<?= $tab_data[0] ?>" data-option="edit" value="<?= $tab_data[0] ?>" <?= (!in_array($tab_data[0], $subtabs_hidden) && !in_array($tab_data[0], $subtabs_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">Editable</label>&nbsp;&nbsp;
                            <label><input type="radio" name="subtab_<?= $tab_data[0] ?>" data-option="disable" value="<?= $tab_data[0] ?>" <?= (in_array($tab_data[0], $subtabs_hidden) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">Disabled</label>&nbsp;&nbsp;
                            <label><input type="radio" name="subtab_<?= $tab_data[0] ?>" data-option="viewonly" value="<?= $tab_data[0] ?>" <?= (in_array($tab_data[0], $subtabs_viewonly) ? 'checked' : '') ?> onchange="save_options(this);" class="contact_subtabs">View Only</label>&nbsp;&nbsp;
                            <div class="clearfix"></div>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
        </form>
    </div><!-- .dashboard-item -->
</div><!-- .standard-dashboard-body-content -->
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_additions.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>