<?php
include_once('../include.php');
include_once('../Ticket/field_list.php');
if(isset($_GET['new_custom_field'])) {
	$tab = $_GET['ticket_type'];
	$sort_field = '';
	$field_sort_order = '';
	$fields_not_included = [];
	foreach ($custom_accordion_list as $custom_field => $custom_field_label) {
		if(!in_array($custom_field, $field_sort_order)) {
			$fields_not_included[] = $custom_field;
		}
	}
} else {
	$field_sort_order = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_ticket_fields` WHERE `ticket_type` = '".(empty($tab) ? 'tickets' : 'tickets_'.$tab)."' AND `accordion` = '".$sort_field."'"))['fields'];
	$field_sort_order = explode(',', $field_sort_order);
	if($action_mode) {
		$field_sort_order_action = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_ticket_fields_action` WHERE `ticket_type` = '".(empty($tab) ? 'tickets' : 'tickets_'.$tab)."' AND `accordion` = '".$sort_field."'"))['fields'];
		$field_sort_order_action = explode(',', $field_sort_order_action);
	} else {
		$field_sort_order_action = $field_sort_order;
	}
	$fields_not_included = [];
	foreach ($custom_accordion_list as $custom_field => $custom_field_label) {
		if(!in_array($custom_field, $field_sort_order)) {
			$fields_not_included[] = $custom_field;
		}
	}
} ?>
<div class="form-group sort_order_accordion" data-accordion="<?= $sort_field ?>">
	<div class="col-sm-4">
		<?php if(!$action_mode) { ?><img src="../img/remove.png" class="inline-img" onclick="removeCustomAccordion(this);"><?php } ?><input type="text" placeholder="Accordion Name" name="custom_accordion" value="<?= explode('FFMCUST_',$sort_field)[1] ?>" onchange="updateCustomAccordion(this);" class="form-control inline" <?php if($action_mode) { echo 'disabled'; } ?>>
	</div>
	<div class="col-sm-8">
		<label class="form-checkbox"><input type="checkbox" <?= in_array($sort_field, $all_config) ? 'checked disabled' : (in_array($sort_field, $value_config) ? "checked" : '') ?> value="<?= $sort_field ?>" name="tickets[]"> Enable</label>
		<div class="block-group">
			<div class="fields_sortable_custom">
			<?php foreach ($field_sort_order as $field_sort_field) {
				if($field_sort_field != '') { ?>
					<label class="form-checkbox sort_order_field"><input type="checkbox" <?= in_array($field_sort_field, $field_sort_order_action) ? 'checked' : '' ?> value="<?= $field_sort_field ?>" name="custom_fields[]" onchange="sortFieldsCustom($(this).closest('.sort_order_accordion'));"> <?= $custom_accordion_list[$field_sort_field] ?></label>
				<?php }
			} ?>
			<?php if(!$action_mode) {
				foreach ($fields_not_included as $field_sort_field) {
					if($field_sort_field != '') { ?>
						<label class="form-checkbox sort_order_field"><input type="checkbox" value="<?= $field_sort_field ?>" name="custom_fields[]" onchange="sortFieldsCustom($(this).closest('.sort_order_accordion'));"> <?= $custom_accordion_list[$field_sort_field] ?></label>
					<?php }
				}
			} ?>
			</div>
		</div>
	</div>
</div>