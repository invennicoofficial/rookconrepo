<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
$value_config = explode(',',mysqli_fetch_array(mysqli_query($dbc,"SELECT `config_fields` FROM `field_config_estimate`"))[0]); ?>
<script>
$(document).ready(function() {
	$('input').change(saveFields);
});
function saveFields() {
	var field_list = [];
	var i = 0;
	$('input:checked').each(function() {
		field_list.push(this.value);
	});
	var scope_fields = [];
	$('.sortable label').each(function() {
		if($(this).find('[type=checkbox]').is(':checked')) {
			scope_fields.push($(this).find('[type=checkbox]').val()+'***'+$(this).find('[type=text]').val());
		}
	});
	$.ajax({
		url: 'estimates_ajax.php?action=setting_fields',
		method: 'POST',
		data: {
			fields: field_list,
			scope: scope_fields
		}
	});
}
</script>
<h4>Fields to Display on <?= ESTIMATE_TILE ?></h4>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Business',$value_config)) { echo " checked"; } ?> value="Business" name="config_fields[]"> Business</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Contact',$value_config)) { echo " checked"; } ?> value="Contact" name="config_fields[]"> Contact</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Site',$value_config)) { echo " checked"; } ?> value="Site" name="config_fields[]"> Site</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('AFE',$value_config)) { echo " checked"; } ?> value="AFE" name="config_fields[]"> Customer AFE#</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Terms',$value_config)) { echo " checked"; } ?> value="Terms" name="config_fields[]"> Payment Terms</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Due',$value_config)) { echo " checked"; } ?> value="Due" name="config_fields[]"> Payment Due</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Multiples',$value_config)) { echo " checked"; } ?> value="Multiples" name="config_fields[]"> Multiples per Line</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Price Display',$value_config)) { echo " checked"; } ?> value="Price Display" name="config_fields[]"> Price Output Options</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Project Start',$value_config)) { echo " checked"; } ?> value="Project Start" name="config_fields[]"> Estimated Project Start Date</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Expiry',$value_config)) { echo " checked"; } ?> value="Expiry" name="config_fields[]"> <?= ESTIMATE_TILE ?> Expiry Date</label>
<?php include('arr_detail_types.php');
foreach($detail_types as $data => $info) { ?>
	<label class="form-checkbox"><input type="checkbox" <?php if (in_array($data,$value_config)) { echo " checked"; } ?> value="<?= $data ?>" name="config_fields[]"> Details: <?= $info[0] ?></label>
<?php } ?>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Notes',$value_config)) { echo " checked"; } ?> value="Notes" name="config_fields[]"> Add Notes</label>
<h4>Items to include in Scope</h4>
<?php include_once('../Rate Card/line_types.php');
foreach($tiles as $label => $name) { ?>
	<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Scope Item '.$name,$value_config) || !in_array_starts('Scope Item ',$value_config)) { echo " checked"; } ?> value="Scope Item <?= $name ?>" name="config_fields[]"> <?= $label ?></label>
<?php } ?>
<h4>Fields to Display in Overview</h4>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Overview Hours',$value_config)) { echo " checked"; } ?> value="Overview Hours" name="config_fields[]"> Total Hours</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Overview Items',$value_config)) { echo " checked"; } ?> value="Overview Items" name="config_fields[]"> Total Items</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Overview Services',$value_config)) { echo " checked"; } ?> value="Overview Services" name="config_fields[]"> Total Services</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Overview Other',$value_config)) { echo " checked"; } ?> value="Overview Other" name="config_fields[]"> Total Other</label>
<label class="form-checkbox"><input type="checkbox" <?php if (in_array('Overview All',$value_config)) { echo " checked"; } ?> value="Overview All" name="config_fields[]"> Total Quantity</label>
<h4>Fields to Display in Scope</h4>
These fields will affect the Rate Card, Estimates, and Projects. Move the fields around to change the display order.
<div class='sortable' style='border:solid 1px black;'>
	<style>
	.sortable label {
		background-color: RGBA(255,255,255,0.2);
		margin: 0.5em;
		min-width: 25em;
		padding: 0.5em;
	}
	.sortable label input[type=checkbox] {
		height: 1.5em;
		margin: 0.25em;
		width: 1.5em;
	}
	</style>
	<script>
	$(document).ready(function() {
		$('.sortable').sortable({
		  connectWith: '.sortable',
		  items: 'label',
		  update: saveFields
		});
		$('.sortable label').off('click').click(function() {
			if($(this).find('[type=checkbox]').is(':checked')) {
				$(this).find('[type=text]').removeAttr('disabled');
			} else {
				$(this).find('[type=text]').prop('disabled',true);
			}
		});
	});
	</script>
	<?php $estimate_field_order = get_config($dbc, 'estimate_field_order');
	if($estimate_field_order == '') {
		$estimate_field_order = $accordions[0];
		if($estimate_field_order == '') {
			$estimate_field_order = trim(str_replace([',itemtype,',',estimate,',',category,',',tile,',',breakdown,'], [',Item Type,',',Estimate Price,','','',''], get_config($dbc, 'company_rate_fields')),',');
			$estimate_field_order = explode(',', $estimate_field_order);
		} else {
			$estimate_field_order = explode(',', $estimate_field_order);
			unset($estimate_field_order[0]);
		}
	} else {
		$estimate_field_order = explode('#*#', $estimate_field_order);
	}
	if(in_array('Scope Detail',$value_config)) {
		$estimate_field_order[] = 'Detail***Scope Detail';
	}
	if(in_array('Scope Billing',$value_config)) {
		$estimate_field_order[] = 'Billing Frequency***Billing Frequency';
	}
	$estimate_field_order = array_map('ucwords',$estimate_field_order);
	$defaults = 'Type,Heading,Category***Category (If Applicable),Item Type***Item Level Type,Description,Detail,Billing Frequency,Item Type,Daily,Hourly,Customer Price,Dollaraving***$ Savings,Percentsaving***% Savings,UOM,Quantity,Cost,Margin,Profit,Estimate Price,Total,Total Multiple***Line Total X [COUNT]';
	foreach($estimate_field_order as $value) {
		$defaults = trim(str_replace(','.explode('***',$value)[0].',',',',','.$defaults.','),',');
	}
	$estimate_field_arr = array_filter(array_unique(array_merge($estimate_field_order,explode(',',$defaults))));
	foreach($estimate_field_arr as $field_order) {
		$data = explode('***', $field_order);
		$field = $data[0];
		$label = $data[1];
		echo '<label><input type="checkbox" '.(in_array($field_order,$estimate_field_order) ? 'checked' : '').' value="'.$field.'" name="estimate_field_name[]">';
		echo $field.': <input type="text" '.(in_array($field_order,$estimate_field_order) ? '' : 'disabled').' class="form-control" name="estimate_field_label[]" value="'.$label.'"></label>';
	} ?>
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_groups.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>