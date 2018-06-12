<?php
if(isset($_POST['submit']) && $_POST['submit'] == 'templates') {
	$fields = $_POST['template_fields'];
	if(!empty($_POST['back_header'])) {
		$fields[] = 'Back Header*#*'.filter_var($_POST['back_header'],FILTER_SANITIZE_STRING);
	}
	if(!empty($_POST['back_description'])) {
		$fields[] = 'Back Description*#*'.filter_var(htmlentities(preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $_POST['back_description'], 1)),FILTER_SANITIZE_STRING);
	}
	if(!empty($_POST['back_website'])) {
		$fields[] = 'Back Website*#*'.filter_var($_POST['back_website'],FILTER_SANITIZE_STRING);
	}
	$fields = implode('#**#',$fields);

	$num_rows = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT COUNT(*) num_rows FROM `business_card_template` WHERE `contact_category` = 'Staff'"))['num_rows'];
	if($num_rows > 0) {
		mysqli_query($dbc, "UPDATE `business_card_template` SET `template` = 'template_a', `fields` = '$fields' WHERE `contact_category` = 'Staff'");
	} else {
		mysqli_query($dbc, "INSERT INTO `business_card_template` (`template`, `contact_category`, `fields`) VALUES ('template_a', 'Staff', '$fields')");
	}
}

$fields = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `business_card_template` WHERE `template` = '$template' AND `contact_category` = 'Staff'"))['fields'];
if(empty($fields)) {
	$fields = 'Full Name#**#ID Number#**#Position#**#Contact Image#**#Signature#**#Back Header*#*#**#Back Description*#*#**#Back Website*#*#**#Back Twitter Icon#**#Back Facebook Icon#**#Back Instagram Icon#**#Back Youtube Icon';
}
$fields = explode('#**#', $fields);

foreach ($fields as $key => $value) {
	$value_arr = explode('*#*', $value);
	if($value_arr[0] == 'Back Header') {
		$back_header = $value_arr[1];
	}
	if($value_arr[0] == 'Back Description') {
		$back_description = $value_arr[1];
	}
	if($value_arr[0] == 'Back Website') {
		$back_website = $value_arr[1];
	}
}
?>
<form class="form-horizontal" action="" method="POST">
	<h3>Front Fields</h3>
	<label class="form-checkbox"><input <?= in_array('Full Name',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Full Name">Full Name</label>
	<label class="form-checkbox"><input <?= in_array('ID Number',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="ID Number">ID Number</label>
	<label class="form-checkbox"><input <?= in_array('Position',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Position">Position</label>
	<label class="form-checkbox"><input <?= in_array('Contact Image',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Contact Image">Contact Image</label>
	<label class="form-checkbox"><input <?= in_array('Signature',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Signature">Signature</label>
	<h3>Back Fields</h3>
	<div class="form-group">
		<label class="col-sm-4 control-label">Back Header:</label>
		<div class="col-sm-8">
			<input type="text" name="back_header" class="form-control" value="<?= $back_header ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Back Description:</label>
		<div class="col-sm-8">
			<textarea name="back_description" class="form-control"><?= html_entity_decode($back_description) ?></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Back Website:</label>
		<div class="col-sm-8">
			<input type="text" name="back_website" class="form-control" value="<?= $back_website ?>">
		</div>
	</div>
	<label class="form-checkbox"><input <?= in_array('Back Twitter Icon',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Back Twitter Icon">Twitter Icon</label>
	<label class="form-checkbox"><input <?= in_array('Back Facebook Icon',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Back Facebook Icon">Facebook Icon</label>
	<label class="form-checkbox"><input <?= in_array('Back Instagram Icon',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Back Instagram Icon">Instagram Icon</label>
	<label class="form-checkbox"><input <?= in_array('Back YouTube Icon',$fields) ? 'checked' : '' ?> type="checkbox" name="template_fields[]" value="Back YouTube Icon">YouTube Icon</label>
	<div class="clearfix"></div>
	<button class="btn brand-btn pull-right" name="submit" value="templates">Submit</button>
</form>