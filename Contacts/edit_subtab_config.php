<?php if($field_option == 'Visibility Options') { ?>
	<label class="col-sm-4 control-label">Subtab Visibility Options:</label>
	<div class="col-sm-8">
		<?php foreach($field_config as $field_name) {
			if(substr($field_name, 0, 4) == 'acc_' && $edit_access > 0) {
				foreach($tab_list as $tab_label => $tab_data) {
					if(!check_subtab_persmission($dbc, $security_folder, ROLE, $tab_data[0])) {
						unset($tab_list[$tab_label]);
					} else if($field_name == 'acc_'.$tab_data[0] && $tab_label != 'Checklist' && !in_array($tab_data[0], $subtabs_hidden)) { ?>
						<label class="form-checkbox"><input type="checkbox" name="subtabs[]" data-table="contacts_subtab" data-field="subtabs" value="<?= $tab_data[0] ?>" <?= in_array($tab_data[0], $contact_subtabs) || empty($contact_subtabs) ? 'checked' : '' ?>> <?= $tab_label ?></label>
					<?php }
				}
			}
		} ?>
		<?php foreach($tab_list as $tab_label => $tab_data) {
			if(in_array_any($tab_data[1],$field_config) && !in_array('acc_'.$tab_data[0],$field_config) && $tab_data[0] != 'sibling_information' && $tab_label != 'Checklist' && $edit_access > 0 && !in_array($tab_data[0], $subtabs_hidden)) { ?>
					<label class="form-checkbox"><input type="checkbox" name="subtabs[]" data-table="contacts_subtab" data-field="subtabs" value="<?= $tab_data[0] ?>" <?= in_array($tab_data[0], $contact_subtabs) || empty($contact_subtabs) ? 'checked' : '' ?>> <?= $tab_label ?></label>
			<?php }
		} ?>
	</div>
<?php } ?>