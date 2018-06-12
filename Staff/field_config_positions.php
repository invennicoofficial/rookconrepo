<?php include_once('../include.php');
checkAuthorised('staff');
$result = mysqli_query($dbc, "SELECT DISTINCT `name` FROM `positions` WHERE `deleted`=0");
$positions = [];
while($row = mysqli_fetch_array($result)) {
	$positions[] = $row['name'];
} ?>
<script>
$(document).ready(function() {
	$('input[name=positions]').change(function() {
		var positions = [];
		$('input[name=positions]:checked').each(function() {
			positions.push(this.value);
		});
		$.ajax({
			url: 'staff_ajax.php?action=positions',
			method: 'POST',
			data: {
				positions: positions
			},
			success: function(response) {
				if(response != '') {
					alert(response);
				}
			}
		});
	});
	$('input[name=db_config],input[name=field_config]').change(function() {
		var db_config = [];
		$('input[name=db_config]:checked').each(function() {
			db_config.push(this.value);
		});
		var field_config = [];
		$('input[name=field_config]:checked').each(function() {
			field_config.push(this.value);
		});
		$.ajax({
			url: 'staff_ajax.php?action=positions_fields',
			method: 'POST',
			data: {
				db_config: db_config,
				field_config: field_config
			},
			success: function(response) {
				
			}
		});
	});
});
</script>
<h3>Default Positions</h3>
Select a position here to add from the default positions. This will not remove existing positions.<br />
<label class="form-checkbox"><input type="checkbox" value="Manager" <?= in_array("Manager", $positions) ? "checked" : "" ?> name="positions">Manager</label>
<label class="form-checkbox"><input type="checkbox" value="Supervisor" <?= in_array("Supervisor", $positions) ? "checked" : "" ?> name="positions">Supervisor</label>
<label class="form-checkbox"><input type="checkbox" value="CRW 40" <?= in_array("CRW 40", $positions) ? "checked" : "" ?> name="positions">CRW 40</label>
<label class="form-checkbox"><input type="checkbox" value="CRW 50" <?= in_array("CRW 50", $positions) ? "checked" : "" ?> name="positions">CRW 50</label>
<label class="form-checkbox"><input type="checkbox" value="CRW 20" <?= in_array("CRW 20", $positions) ? "checked" : "" ?> name="positions">CRW 20</label>
<label class="form-checkbox"><input type="checkbox" value="AON 40" <?= in_array("AON 40", $positions) ? "checked" : "" ?> name="positions">AON 40</label>
<label class="form-checkbox"><input type="checkbox" value="AON 30" <?= in_array("AON 30", $positions) ? "checked" : "" ?> name="positions">AON 30</label>
<label class="form-checkbox"><input type="checkbox" value="Subsistence Pay" <?= in_array("Subsistence Pay", $positions) ? "checked" : "" ?> name="positions">Subsistence Pay</label>
<label class="form-checkbox"><input type="checkbox" value="Relief" <?= in_array("Relief", $positions) ? "checked" : "" ?> name="positions">Relief</label>

<h3>Dashboard Fields</h3>
<?php $db_config = explode(',',get_config($dbc, 'positions_db_config')); ?>
<label class="form-checkbox"><input type="checkbox" name="db_config" value="Rate Card" <?= in_array('Rate Card', $db_config) ? 'checked' : '' ?>><span class="popover-examples"><a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="The user will need View Access to the Rate Card tile to view the Rate Card section, and Edit Access to the Rate Card tile to edit the Rate Card section."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span> Rate Card</label>
<label class="form-checkbox"><input type="checkbox" name="db_config" value="Rate Card Price" <?= in_array('Rate Card Price', $db_config) ? 'checked' : '' ?>>Rate Card Price</label>

<h3>Positions Fields</h3>
<?php $field_config = explode(',',get_config($dbc, 'positions_field_config')); ?>
<label class="form-checkbox"><input type="checkbox" name="field_config" value="Rate Card" <?= in_array('Rate Card', $field_config) ? 'checked' : '' ?>><span class="popover-examples"><a data-toggle="tooltip" data-placement="bottom" title="" data-original-title="The user will need View Access to the Rate Card tile to view the Rate Card section, and Edit Access to the Rate Card tile to edit the Rate Card section."><img src="<?= WEBSITE_URL ?>/img/info.png" width="20"></a></span> Rate Card</label>