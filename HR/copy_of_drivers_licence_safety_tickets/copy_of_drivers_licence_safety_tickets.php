<?php
/*
Add	Sheet
*/
include_once ('../database_connection.php');
error_reporting(0);
?>
</head>
<body>

<?php
$today_date = date('Y-m-d');
$contactid = $_SESSION['contactid'];
$fields = '';
$desc = '';

if(!empty($_GET['formid'])) {
    $formid = $_GET['formid'];
	echo '<input type="hidden" name="fieldlevelriskid" value="'.$formid.'">';
	//$get_field_level = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM hr_employee_driver_information_form WHERE fieldlevelriskid='$formid'"));
	//$today_date = $get_field_level['today_date'];
    //$contactid = $get_field_level['contactid'];
    //$fields = explode('**FFM**', $get_field_level['fields']);
	//$desc = $get_field_level['desc'];
}

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `hr` WHERE tab LIKE '$tab' AND form='$form'"));
$form_config = ','.$get_field_config['fields'].',';
?>

<script type="text/javascript">

function addLicense() {
    var clone = $('.additional_license').clone();
    clone.find('.form-control').val('');
    resetChosen(clone.find('select'));
    clone.find('.datepicker')
        .attr("id", "")
        .removeClass('hasDatepicker')
        .removeData('datepicker')
        .unbind()
        .datepicker({dateFormat: 'yy-mm-dd'});
    clone.removeClass('additional_license');
    $('#add_here_new_license').append(clone);
}

</script>

<h3>Information</h3>
<div class="additional_license">
<?php if (strpos($form_config, ','."fields1".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Driver's Licence:</label>
	<div class="col-sm-8">
		<select name="fields_0[]" class="chosen-select-deselect form-control" width="380">
			<option value=''></option>
			<option value="Driver's Licence">Driver's Licence</option>
			<option value='Safety Ticket'>Safety Ticket</option>
		</select>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields6".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Title:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_3[]" value="<?php echo $fields[3]; ?>" class="form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields2".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Description:</label>
	<div class="col-sm-8">
	<textarea name="desc[]" rows="3" cols="50" class="form-control"><?php echo $desc; ?></textarea>
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields3".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Issue Date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_1[]" value="<?php echo $fields[1]; ?>" class="datepicker form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields4".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Expiry Date:</label>
	<div class="col-sm-8">
	<input type="text" name="fields_2[]" value="<?php echo $fields[2]; ?>" class="datepicker form-control" />
	</div>
	</div>
<?php } ?>

<?php if (strpos($form_config, ','."fields5".',') !== FALSE) { ?>
	<div class="form-group">
	<label for="business_street" class="col-sm-4 control-label">Upload Document:</label>
	<div class="col-sm-8">
	<input name="doc_upload[]" type="file" data-filename-placement="inside" class="form-control" />
	</div>
	</div>
<?php } ?>
</div>

<?php if (strpos($form_config, ','."fields7".',') !== FALSE) { ?>
    <h3>Driver's License & Safety Tickets</h3>
	<?php
	$contactid = $_SESSION['contactid'];
	$query = mysqli_query($dbc,"SELECT * FROM hr_copy_of_drivers_licence_safety_tickets WHERE contactid='$contactid'");
	echo "<table class='table table-bordered'>";
	echo "<tr class='hidden-xs hidden-sm'>
	<th>Type</th>
	<th>Issue Date</th>
	<th>Expiry Date</th>
	<th>Description</th>
	<th>Doc</th>";
	while($row = mysqli_fetch_array($query)) {
		$fields = explode('**FFM**',$row['fields']);
		echo '<tr>';
		foreach($fields AS $each_field) {
			if($each_field != '') {
				echo '<td data-title="Email">' . $each_field . '</td>';
			}
		}
		echo '<td data-title="Email">' . html_entity_decode($row['desc']) . '</td>';
		echo '<td data-title="Email"><a href="copy_of_drivers_licence_safety_tickets/download/'.$row['document'].'" target="_blank">' . $row['document'] . '</a></td>';
		echo '</tr>';
	}
		echo '</table>';
		?>
<?php } ?>