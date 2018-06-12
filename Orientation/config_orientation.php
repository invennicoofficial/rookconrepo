<?php /* Orientation Settings */
include ('../include.php');
checkAuthorised('orientation');
if(isset($_POST['submit'])) {
	$contactid = filter_var($_POST['staff'], FILTER_SANITIZE_STRING);
	$start_date = filter_var($_POST['orientation_start'], FILTER_SANITIZE_STRING);
	$profile = filter_var(implode(',',$_POST['profile']),FILTER_SANITIZE_STRING);
	$hr = filter_var(implode(',',$_POST['hr']),FILTER_SANITIZE_STRING);
	$safety = filter_var(implode(',',$_POST['safety']),FILTER_SANITIZE_STRING);
	$sql_drop_prior = "UPDATE `orientation_staff` SET `completed`=1 WHERE `staffid`='$contactid'";
	$dbc->query($sql_drop_prior);
	$sql = "INSERT INTO `orientation_staff` (`staffid`,`start_date`,`profile`,`hr`,`safety`) VALUES ('$contactid','$start_date','$profile','$hr','$safety')";
	$dbc->query($sql);
}
?>
<script>
$(document).ready(function() {
	$('[name=staff]').change(function() {
		window.location.replace('?contactid='+$(this).val());
	});
});
</script>
</head>
<body>

<?php include_once ('../navigation.php');
$contactid = 0;
$start_date = '';
$profile_fields = '';
$hr = [];
$safety = [];

if(!empty($_GET['contactid'])) {
	$contactid = $_GET['contactid'];
	$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `orientation_staff` WHERE `staffid`='$contactid' AND `completed`=0 ORDER BY `orientationid` DESC"));
	$start_date = $row['start_date'];
	$profile_fields = ','.$row['profile'].',';
	$hr = explode(',',$row['hr']);
	$safety = explode(',',$row['safety']);
}
?>
<div class="container">
	<div class="row">
		<h1>Orientation Settings</h1>
		<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		
		<div class="clearfix"></div><br />
			<div class="panel-group" id="accordion2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_staff" >
								Assign Staff<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_staff" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="form-group clearfix">
								<label for="staff" class="col-sm-4 control-label text-right">Staff Member:</label>
								<div class="col-sm-8">
									<select name="staff" data-placeholder="Choose a Staff..." class="chosen-select-deselect form-control"><option></option>
										<?php $result = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status` > 0 AND `deleted`=0"),MYSQLI_ASSOC));
										foreach($result as $staff_id) {
											echo '<option '.($staff_id == $contactid ? "selected" : "").' value="'.$staff_id.'">'.get_contact($dbc,$staff_id)."</option>\n";
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group clearfix">
								<label for="orientation_start" class="col-sm-4 control-label text-right">Start Date:</label>
								<div class="col-sm-8">
									<input type="text" class="datepicker form-control" name="orientation_start" value="<?php echo $start_date; ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_profile" >
								Profile<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_profile" class="panel-collapse collapse">
						<div class="panel-body"><?= $profile_fields ?>
							<label class="col-sm-12">All of these fields are visible in the My Profile tile. Checking or unchecking them here will make them mandatory to complete the field before their orientation will be complete.</label>
							<?php $query_all_fields = "SELECT `subtab`, `accordion`, `contacts`, `order` FROM `field_config_contacts` WHERE `tab`='Profile' AND REPLACE(`contacts`,',','') != '' AND `subtab` != '' AND `subtab` != '**no_subtab**' ORDER BY IFNULL(`order`,`configcontactid`)";
							$result_all_fields = mysqli_query($dbc, $query_all_fields);
							$i = 0;
							while($row_fields = mysqli_fetch_assoc($result_all_fields)) {
								$i++; ?>
								<h3><?= ucwords($row_fields['accordion']) ?></h3>
								<?php foreach(explode(',', trim($row_fields['contacts'],',')) as $field): ?>
									<label class="form-checkbox"><input type="checkbox" name="profile[]" <?= strpos($profile_fields, ",$field,") !== FALSE ? 'checked' : '' ?> value="<?= $field ?>"><?= $field ?></label>
								<?php endforeach; ?>
								<hr>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_hr" >
								HR<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_hr" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group clearfix">
								<label for="hr[]" class="col-sm-4 control-label text-right">HR Forms and Manuals to Complete:<br />
								<small>You can select multiple by holding down Ctrl on the keyboard while clicking on an option.
								You can select several by clicking the first option, then holding down Shift while clicking the last option.
								You can also press Ctrl+A after clicking in the box to select all of the options.</small></label>
								<div class="col-sm-8">
									<select class="form-control" size="10" multiple name="hr[]">
										<?php $result = mysqli_query($dbc, "SELECT * FROM (SELECT `hrid` `id`, 'hr' `table`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `third_heading_number`, `third_heading` FROM `hr` WHERE `deleted`=0 AND IFNULL(`heading_number`,'') != '' UNION SELECT `manualtypeid` `id`, 'manuals' `table`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `third_heading_number`, `third_heading` FROM `manuals` WHERE `deleted`=0 AND IFNULL(`heading_number`,'') != '') `hr` ORDER BY `category`, LPAD(`heading_number`,100,0), LPAD(`sub_heading_number`,100,0), LPAD(`third_heading_number`,100,0)");
										while($row = mysqli_fetch_array($result)) {
											echo "<option ".(in_array($row['table'].':'.$row['id'],$hr) ? "selected " : "")."value='".$row['table'].':'.$row['id']."'>".$row['category']." - ".$row['heading']." ".(empty($row['third_heading_number']) ? $row['sub_heading_number']." ".$row['sub_heading'] : $row['third_heading_number'].' '.$row['third_heading'])."</option>";
										} ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_safety" >
								Safety<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_safety" class="panel-collapse collapse">
						<div class="panel-body">
							<div class="form-group clearfix">
								<label for="safety[]" class="col-sm-4 control-label text-right">Safety Forms and Manuals to Complete:<br />
								<small>You can select multiple by holding down Ctrl on the keyboard while clicking on an option.
								You can select several by clicking the first option, then holding down Shift while clicking the last option.
								You can also press Ctrl+A after clicking in the box to select all of the options.</small></label>
								<div class="col-sm-8">
									<select class="form-control" size="10" multiple name="safety[]">
										<?php $result = mysqli_query($dbc, "SELECT * FROM `safety` WHERE `deleted`=0 ORDER BY `category`, LPAD(`heading_number`,100,0), LPAD(`sub_heading_number`,100,0), LPAD(`third_heading_number`,100,0)");
										while($row = mysqli_fetch_array($result)) {
											echo "<option ".(in_array($row['safetyid'],$safety) ? "selected " : "")."value='".$row['safetyid']."'>".$row['category']." - ".$row['heading']." ".(empty($row['third_heading_number']) ? $row['sub_heading_number']." ".$row['sub_heading'] : $row['third_heading_number'].' '.$row['third_heading'])."</option>";
										} ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-4 clearfix">
					<a href="orientation.php" class="btn brand-btn btn-lg pull-left">Back</a>
				</div>
				<div class="col-sm-8">
					<button type='submit' name='submit' value='submit' class="btn brand-btn btn-lg pull-right">Submit</button>
				</div>
			</div>

		</form>
	</div>
</div>
<?php include ('../footer.php'); ?>