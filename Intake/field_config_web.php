<?php include_once('../include.php');
checkAuthorised('intake');
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) ) {
	$intake_dashboard  = implode(',', $_POST['intake_dashboard']);
    $intake_dashboard .= ','.$_POST['intake_assign'];
    $intake_dashboard .= ','.$_POST['intake_create'];
    $intake_dashboard .= ','.$_POST['intake_project'];
    $intake_dashboard .= ','.$_POST['intake_ticket'];
    $intake_dashboard .= ','.$_POST['intake_sales'];
    $intake_dashboard .= ','.$_POST['intake_archive'];
	
	$query_update	= "UPDATE `field_config` SET `intake_dashboard` = '{$intake_dashboard}' WHERE `fieldconfigid` = 1";
	$result_update	= mysqli_query($dbc, $query_update);

	set_config($dbc, 'intake_web_default_cat', $_POST['intake_web_default_cat']);

	echo '<script type="text/javascript"> window.location.replace("field_config.php?tab=web");</script>';
}
?>

<script>
	$(document).ready(function(){
		$("#selectall").change(function(){
		  $("input[name='intake_dashboard[]']").prop('checked', $(this).prop("checked"));
		});
	});
</script>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="form-group">
		<h4>Choose Fields for Intake Forms Dashboard</h4>
		<?php $get_field_config	= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `intake_dashboard` FROM `field_config` WHERE `fieldconfigid`=1" ) );
		$value_config		= ',' . $get_field_config['intake_dashboard'] . ','; ?>
		
		<div id="no-more-tables">
			<input type="checkbox" id="selectall" />&nbsp;&nbsp;Select All<br />
			<br />
			<table border="2" cellpadding="10" class="table">
				<tr>
					<td>
						<input type="checkbox" <?php if (strpos($value_config, ',Form ID,') !== false) { echo " checked"; } ?> value="Form ID" name="intake_dashboard[]">&nbsp;&nbsp;Form ID
					</td>
					<td>
						<input type="checkbox" <?php if (strpos($value_config, ',Category,') !== false) { echo " checked"; } ?> value="Category" name="intake_dashboard[]">&nbsp;&nbsp;Category
					</td>
					<td>
						<input type="checkbox" <?php if (strpos($value_config, ',Name,') !== false) { echo " checked"; } ?> value="Name" name="intake_dashboard[]">&nbsp;&nbsp;Name
					</td>
					<td>
						<input type="checkbox" <?php if (strpos($value_config, ',Email,') !== false) { echo " checked"; } ?> value="Email" name="intake_dashboard[]">&nbsp;&nbsp;Email
					</td>
                </tr>
                <tr>
					<td>
						<input type="checkbox" <?php if (strpos($value_config, ',Phone,') !== false) { echo " checked"; } ?> value="Phone" name="intake_dashboard[]">&nbsp;&nbsp;Phone
					</td>
					<td>
						<input type="checkbox" <?php if (strpos($value_config, ',Received Date,') !== false) { echo " checked"; } ?> value="Received Date" name="intake_dashboard[]">&nbsp;&nbsp;Received Date
					</td>
					<td>
						<input type="checkbox" <?php if (strpos($value_config, ',PDF Form,') !== false) { echo " checked"; } ?> value="PDF Form" name="intake_dashboard[]">&nbsp;&nbsp;PDF Form
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="form-group">
	<h4>Choose Functions for Intake Forms</h4>
	<?php $get_field_config	= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `intake_dashboard` FROM `field_config` WHERE `fieldconfigid`=1" ) );
		$value_config		= ',' . $get_field_config['intake_dashboard'] . ',';
		$intake_web_default_cat = get_config($dbc, 'intake_web_default_cat'); ?>
        
        <div class="form-group">
        	<label class="col-sm-4 control-label">Default Contact Category:</label>
        	<div class="col-sm-8">
        		<select name="intake_web_default_cat" data-placeholder="Select a Category..." class="chosen-select-deselect form-control">
        			<option></option>
        			<?php $contact_cats = array_column(mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `category` FROM `contacts` WHERE `deleted` = 0 AND `status` = 1 ORDER BY `category`"),MYSQLI_ASSOC),'category');
        				foreach($contact_cats as $contact_cat) {
        					echo '<option value="'.$contact_cat.'" '.($contact_cat == $intake_web_default_cat ? 'selected' : '').'>'.$contact_cat.'</option>';
        				}
        			?>
        		</select>
        	</div>
        </div>

		<div class="form-group">
			<label class="col-sm-4 control-label">Assign Intake Form To Contact:</label>
			<div class="col-sm-8">
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Assign,') !== false || preg_match('(,Hide Assign,|,New Injury Existing Patient,)', $value_config) === 0 ) { echo " checked"; } ?> value="Assign" name="intake_assign">Assign To A Profile</label>
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',New Injury Existing Patient,') !== false ) { echo " checked"; } ?> value="New Injury Existing Patient" name="intake_assign">New Injury Existing Profile</label>
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Assign,') !== false ) { echo " checked"; } ?> value="Hide Assign" name="intake_assign">Hide</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Create New Contact With Intake Form:</label>
			<div class="col-sm-8">
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Create,') !== false || preg_match('(,New Injury New Patient,|,Hide Create,)', $value_config) === 0 ) { echo " checked"; } ?> value="Create" name="intake_create">Create New Profile</label>
				<label class="form-checkbox"><input type="radio" <?php if (strpos($value_config, ',New Injury New Patient,') !== false) { echo " checked"; } ?> value="New Injury New Patient" name="intake_create">New Injury New Profile</label>
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Create,') !== false ) { echo " checked"; } ?> value="Hide Create" name="intake_create">Hide</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Create New <?= PROJECT_NOUN ?>:</label>
			<div class="col-sm-8">
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Project,') !== false || preg_match('(,Assign Project,|,Hide Project,)', $value_config) === 0 ) { echo " checked"; } ?> value="Project" name="intake_project">Create New <?= PROJECT_NOUN ?></label>
				<!--<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Assign Project,') !== false ) { echo " checked"; } ?> value="Assign Project" name="intake_project">Assign to Project</label>-->
				<br />
                <label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Project,') !== false ) { echo " checked"; } ?> value="Hide Project" name="intake_project">Hide</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Create New <?= TICKET_NOUN ?>:</label>
			<div class="col-sm-8">
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Ticket,') !== false || preg_match('(,Assign Ticket,|,Hide Ticket,)', $value_config) === 0 ) { echo " checked"; } ?> value="Ticket" name="intake_ticket">Create New <?= TICKET_NOUN ?></label>
				<!--<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Assign Project,') !== false ) { echo " checked"; } ?> value="Assign Project" name="intake_project">Assign to Project</label>-->
				<br />
                <label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Ticket,') !== false ) { echo " checked"; } ?> value="Hide Ticket" name="intake_ticket">Hide</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Create New <?= SALES_NOUN ?>:</label>
			<div class="col-sm-8">
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Sales Lead,') !== false || preg_match('(,Assign Sales Lead,|,Hide Sales Lead,)', $value_config) === 0 ) { echo " checked"; } ?> value="Sales Lead" name="intake_sales">Create New <?= SALES_NOUN ?></label>
				<!--<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Assign Project,') !== false ) { echo " checked"; } ?> value="Assign Project" name="intake_project">Assign to Project</label>-->
				<br />
                <label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Sales Lead,') !== false ) { echo " checked"; } ?> value="Hide Sales Lead" name="intake_sales">Hide</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Archive Submission:</label>
			<div class="col-sm-8">
				<label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Project,') !== false || preg_match('(,Archive Submission,|,Hide Archive,)', $value_config) === 0 ) { echo " checked"; } ?> value="Archive" name="intake_archive">Archive</label>
				<br />
                <label class="form-checkbox"><input type="radio" <?php if ( strpos($value_config, ',Hide Archive,') !== false ) { echo " checked"; } ?> value="Hide Archive" name="intake_archive">Hide</label>
			</div>
		</div>
	</div>

	<div class="form-group pull-right">
		<a href="intake.php" class="btn brand-btn">Back</a>
		<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
		<button	type="submit" name="submit"	value="Submit" class="btn brand-btn">Submit</button>
	</div>

</form>