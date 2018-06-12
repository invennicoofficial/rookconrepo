<?php $pr_fields = ','.get_config($dbc, 'performance_review_fields').',';
$pr_positions = ','.get_config($dbc, 'performance_review_positions').',';
$pr_forms = ','.get_config($dbc, 'performance_review_forms').','; ?>
<script>
$(document).ready(function() {
	$('input').change(saveFields);
});
function saveFields() {
	var pr_fields = [];
	$('[name="pr_fields[]"]:checked').each(function() {
		pr_fields.push(this.value);
	});
	var pr_positions = [];
	$('[name="pr_positions[]"]:checked').each(function() {
		pr_positions.push(this.value);
	});
	var pr_forms = [];
	$('[name="pr_forms[]"]:checked').each(function() {
		pr_forms.push(this.value);
	});
	$.ajax({
		url: 'hr_ajax.php?action=pr_settings',
		method: 'POST',
		data: {
			pr_fields: pr_fields,
			pr_positions: pr_positions,
			pr_forms: pr_forms
		}
	});
}
</script>
<div class="block-group">
	<h1>Performance Reviews</h1>
	<div class="form-group">
		<label class="control-label col-sm-4">Enable Performance Reviews:</label>
		<div class="col-sm-8">
			<label class="form-checkbox"><input type="checkbox" name="pr_fields[]" class="form-control" value="Enable Performance Reviews" <?= strpos($pr_fields, ',Enable Performance Reviews,') !== FALSE ? 'checked' : '' ?>></label>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Use As Tile:</label>
		<div class="col-sm-8">
			<label class="form-checkbox"><input type="checkbox" name="pr_fields[]" class="form-control" value="Use As Tile" <?= strpos($pr_fields, ',Use As Tile,') !== FALSE ? 'checked' : '' ?>></label>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Positions:</label>
		<div class="col-sm-8">
			<div class="block-group">
				<?php $positions = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `positions` WHERE `deleted` = 0"),MYSQLI_ASSOC);
				foreach ($positions as $position) { ?>
					<label class="form-checkbox"><input type="checkbox" name="pr_positions[]" class="form-control" value="<?= $position['name'] ?>" <?= strpos($pr_positions, ','.$position['name'].',') !== FALSE ? 'checked' : '' ?>> <?= $position['name'] ?></label>
				<?php } ?>
			</div>
		</div>
	</div>

	<h2>Forms</h2>
	<?php $user_forms = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE '%,performance_review,%' AND `deleted` = 0 AND `is_template` = 0"),MYSQLI_ASSOC);
		if(!empty($user_forms)) { 
			foreach ($user_forms as $user_form) { ?>
				<div class="form-group">
					<label class="control-label col-sm-4"><?= $user_form['name'] ?>:</label>
					<div class="col-sm-8">
						<label class="form-checkbox"><input type="checkbox" name="pr_forms[]" class="form-control" value="<?= $user_form['form_id'] ?>" <?= strpos($pr_forms, ','.$user_form['form_id'].',') !== FALSE ? 'checked' : '' ?>> Enable</label>
					</div>
				</div>
			<?php }
		} else {
			echo '<h4>No Performance Review Forms Found.</h4>';
		} ?>
</div>