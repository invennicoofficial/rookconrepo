<?php include_once('../include.php');
checkAuthorised('form_builder');
include('../Contacts/edit_fields.php');
if(!empty($_GET['tile_name']) && !empty($_GET['category'])) {
	$tile_name = $_GET['tile_name'];
	$contact_cat = $_GET['category'];
} else {
	$tile_name = $field_info['source_table'];
	$contact_cat = $field_info['source_conditions'];
}

$contact_fields = mysqli_fetch_all(mysqli_query($dbc, "SELECT `source_conditions` FROM `user_form_fields` WHERE `form_id`='$form_id' AND `type`='OPTION' AND `name`='".$field_info['name']."' AND '".$field_info['type']."' IN ('CONTACTINFO') AND `deleted`=0"),MYSQLI_ASSOC);
$enabled_fields = [];
foreach($contact_fields as $contact_field) {
	$enabled_fields[] = $contact_field['source_conditions'];
}

if(!empty($tile_name) && !empty($contact_cat)) { ?>
	<div class="block-group">
		<?php $contact_field_config = explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tile_name`='$tile_name' AND `tab`='$contact_cat' AND `subtab` = '**no_subtab**'"))[0]);
		foreach($tab_list as $tab_label => $tab_data) {
			if(in_array_any($tab_data[1],$contact_field_config)) {
				foreach($tab_data[1] as $key => $field_option) {
					if(in_array($field_option,$contact_field_config) && is_string($key) && $key != 'contactid' && $key != 'category') { ?>
						<label class="form-checkbox"><input data-label="<?= $field_option ?>" type="checkbox" name="contactinfo_fields[]" value="<?= $key ?>" <?= in_array($key, $enabled_fields) ? 'checked' : '' ?>> <?= $field_option ?></label>
					<?php }
				}
			}
		} ?>
	</div>
<?php } else {
	echo 'No Contact Category Chosen.';
}