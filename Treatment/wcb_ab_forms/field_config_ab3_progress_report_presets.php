<script>
function add_line(button) {
	var field = $(button).prev('.form-group').find('[type=hidden]').val();
	var add_element = $('.form-group').filter(function() { return $(this).find('input[type=hidden][value="'+field+'"]').length > 0; }).first().clone();
	add_element.find('[name="preset_value[]"]').val('');
	add_element.find('[name="preset_id[]"]').val('');
	$(button).before(add_element);
}
</script>
<h4>Therapy Status Report Presets</h4>
<p>Diagnosis</p>
<?php $result = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='$form' AND `field`='fields13' UNION SELECT '', '', '', ''");
while($row = mysqli_fetch_array($result)) { ?>
	<div class="form-group"><input type="hidden" name="preset_id[]" value=""><input type="hidden" name="preset_field[]" value="fields12"><input type="text" name="preset_value[]" value="<?php echo html_entity_decode($row['preset_text']); ?>" class="form-control">
		<button onclick="$(this).closest('.form-group').remove();return false;" class="btn brand-btn">Delete</button></div>
<?php } ?>
<button class="btn brand-btn" onclick="add_line(this);return false;">Add Line</button>
<p>Key Subjective/Physical Examination Findings</p>
<?php $result = mysqli_query($dbc, "SELECT * FROM `field_config_treatment_presets` WHERE `form`='$form' AND `field`='fields14' UNION SELECT '', '', '', ''");
while($row = mysqli_fetch_array($result)) { ?>
	<div class="form-group"><input type="hidden" name="preset_id[]" value=""><input type="hidden" name="preset_field[]" value="fields13"><input type="text" name="preset_value[]" value="<?php echo html_entity_decode($row['preset_text']); ?>" class="form-control">
		<button onclick="$(this).closest('.form-group').remove();return false;" class="btn brand-btn">Delete</button></div>
<?php } ?>
<button class="btn brand-btn" onclick="add_line(this);return false;">Add Line</button>