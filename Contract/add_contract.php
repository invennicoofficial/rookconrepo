<?php include_once('../include.php');
checkAuthorised('contracts');

if(isset($_POST['submit'])) {
	$contractid = $_POST['contractid'];
	$user_form = filter_var($_POST['user_form'],FILTER_SANITIZE_STRING);
	$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
	$heading_number = filter_var($_POST['heading_number'],FILTER_SANITIZE_STRING);
	$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	$sub_heading_number = filter_var($_POST['sub_heading_number'],FILTER_SANITIZE_STRING);
	$sub_heading = filter_var($_POST['sub_heading'],FILTER_SANITIZE_STRING);
	$third_heading_number = filter_var($_POST['third_heading_number'],FILTER_SANITIZE_STRING);
	$third_heading = filter_var($_POST['third_heading'],FILTER_SANITIZE_STRING);
	$fields = filter_var(implode(',',$_POST['contract_fields']),FILTER_SANITIZE_STRING);

	if($contractid > 0) {
		mysqli_query($dbc, "UPDATE `contracts` SET `user_form_id`='$user_form', `category`='$category', `heading_number`='$heading_number', `heading`='$heading', `sub_heading_number`='$sub_heading_number', `sub_heading`='$sub_heading', `third_heading_number`='$third_heading_number', `third_heading`='$third_heading', `fields`='$fields' WHERE `contractid` = '$contractid'");
	} else {
		mysqli_query($dbc, "INSERT INTO `contracts` (`user_form_id`, `category`, `heading_number`, `heading`, `sub_heading_number`, `sub_heading`, `third_heading_number`, `third_heading`, `fields`)
			VALUES ('$user_form', '$category', '$heading_number', '$heading', '$sub_heading_number', '$sub_heading', '$third_heading_number', '$third_heading', '$fields')");
		$contractid = mysqli_insert_id($dbc);
	}
	echo "<script> window.location.replace('?tab=".$_GET['tab']."'); </script>";
}

$field_config = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_contracts`"))['fields'];
if(empty($field_config)) {
	$field_config = 'Sub Section Heading,Third Tier Heading,Business';
}
$field_config = explode(',',$field_config);

$contractid = '';
if($_GET['add_contract'] > 0) {
	$contractid = $_GET['add_contract'];
	$contract = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contracts` WHERE `contractid` = '$contractid'"));
} ?>

<script type="text/javascript">
$(document).on('change','select[name="category"]',function() { changeCategory(this.value); });
$(document).on('change','select[name="heading_number"]',function() { changeSection(this.value); });
$(document).on('change','select[name="sub_heading_number"]',function() { changeSubSection(this.value); });
function changeCategory(category) {
	$.ajax({
		url: '../Contract/contract_ajax.php?action=set_category',
		method: 'POST',
		data: {
			category: category
		},
		success: function(response) {
			$('[name=heading_number]').html(response).trigger('change');
		}
	});
}
function changeSection(section) {
	$.ajax({
		url: '../Contract/contract_ajax.php?action=set_form_section',
		method: 'POST',
		data: {
			section: section,
			category: $('[name=category]').val()
		},
		success: function(response) {
			response = response.split('#*#');
			$('[name=heading]').val(response[0]);
			$('[name=sub_heading_number]').html(response[1]).trigger('change');
		}
	});
}
function changeSubSection(subsection) {
	$.ajax({
		url: '../Contract/contract_ajax.php?action=set_form_subsection',
		method: 'POST',
		data: {
			subsection: subsection,
			category: $('[name=category]').val()
		},
		success: function(response) {
			response = response.split('#*#');
			$('[name=sub_heading]').val(response[0]);
			$('[name=third_heading_number]').html(response[1]).trigger('change');
		}
	});
}
</script>

<div class="tile-sidebar sidebar sidebar-override hide-titles-mob standard-collapsible">
	<ul>
		<a href="?tab=<?= $_GET['tab'] ?>"><li>Back to Dashboard</li></a>
		<a href="" onclick="return false;"><li class="active blue">Contract Settings</li></a>
	</ul>
</div>
<div class='scale-to-fill has-main-screen' style="padding: 0;">
	<div class='main-screen standard-body form-horizontal'>
		<div class="standard-body-title">
			<h3><?= $contractid > 0 ? 'Edit' : 'Add' ?> Contract<?= !empty($contract['sub_heading']) ? ': '.$contract['sub_heading'] : '' ?></h3>
		</div>
		<div class="standard-body-content pad-top" style="padding: 5px;">
			<form action="" method="POST" class="form-horizontal" enctype="multipart/form-data">
				<input type="hidden" name="contractid" value="<?= $contractid ?>">
				<div class="form-group">
					<label class="col-sm-4 control-label">Form:</label>
					<div class="col-sm-8">
						<select name="user_form" data-placeholder="Select a Form" class="chosen-select-deselect form-control">
							<option></option>
							<?php $query = mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE '%,contracts,%' AND `deleted` = 0 ORDER BY `name`");;
							while ($row = mysqli_fetch_array($query)) { ?>
								<option <?php if ($contract['user_form_id'] == $row['form_id']) { echo " selected" ; } ?> value="<?php echo $row['form_id']; ?>"><?php echo $row['name']; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Category:</label>
					<div class="col-sm-8">
						<select name="category" data-placeholder="Select a Category" class="chosen-select-deselect form-control">
							<option></option>
							<?php $contract_tabs = explode('#*#',mysqli_fetch_array(mysqli_query($dbc, "SELECT `contract_tabs` FROM `field_config_contracts`"))['contract_tabs']);
							foreach ($contract_tabs as $contract_tab) { ?>
								<option <?= $contract_tab == $contract['category'] ? 'selected' : '' ?> value="<?= $contract_tab ?>"><?= $contract_tab ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Section:</label>
					<div class="col-sm-8">
						<select name="heading_number" data-placeholder="Select Section" class="chosen-select-deselect form-control">
							<option></option>
							<?php $heading_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading_number` FROM `contracts` WHERE LPAD(`heading_number`, 100, 0) IN (SELECT MAX(LPAD(`heading_number`, 100, 0)) FROM `contracts` WHERE `deleted`=0 AND `category`='".$contract['category']."') GROUP BY `heading_number`"))['heading_number'] + 5;
							for($i = 1; $i <= $heading_count; $i++) {
								$heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading` FROM `contracts` WHERE `heading_number`='$i' AND `category`='".$contract['category']."' AND `deleted`=0"))['heading']; ?>
								<option <?= $contract['heading_number'] == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i.' '.$heading ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Section Heading:</label>
					<div class="col-sm-8">
						<input type="text" name="heading" value="<?= $contract['heading'] ?>" class="form-control">
					</div>
				</div>
				<?php if(in_array('Sub Section Heading',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Sub Section:</label>
						<div class="col-sm-8">
							<select name="sub_heading_number" data-placeholder="Select Sub Section" class="chosen-select-deselect form-control">
								<option></option>
								<?php $heading_number = empty($contract['heading_number']) ? '1' : $contract['heading_number'];
								$heading_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `heading_number`, `sub_heading_number` FROM `contracts` WHERE LPAD(`sub_heading_number`, 100, 0) IN (SELECT MAX(LPAD(`sub_heading_number`, 100, 0)) FROM `contracts` WHERE `deleted`=0 AND `category`='".$contract['category']."' AND `heading_number`='$heading_number') GROUP BY `sub_heading_number`"));
								$heading_count = substr($heading_count['sub_heading_number'],strlen($heading_count['heading_number']) + 1) + 5;
								for($i = 1; $i <= $heading_count; $i++) {
									$heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `sub_heading` FROM `contracts` WHERE `sub_heading_number`='".$heading_number.'.'.$i."' AND `category`='".$contract['category']."' AND `deleted`=0"))['sub_heading']; ?>
									<option <?= $contract['sub_heading_number'] === $heading_number.'.'.$i ? 'selected' : '' ?> value="<?= $heading_number.'.'.$i ?>"><?= $heading_number.'.'.$i.' '.$heading ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Sub Section Heading:</label>
						<div class="col-sm-8">
							<input type="text" name="sub_heading" value="<?= $contract['sub_heading'] ?>" class="form-control">
						</div>
					</div>
				<?php } ?>
				<?php if(in_array('Third Tier Heading',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Third Tier Section:</label>
						<div class="col-sm-8">
							<select name="third_heading_number" data-placeholder="Select Third Tier Section" class="chosen-select-deselect form-control">
								<option></option>
								<?php $heading_number = empty($contract['sub_heading_number']) ? '1.1' : $contract['sub_heading_number'];
								$heading_count = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `third_heading_number`, `sub_heading_number` FROM `contracts` WHERE LPAD(`third_heading_number`, 100, 0) IN (SELECT MAX(LPAD(`third_heading_number`, 100, 0)) FROM `contracts` WHERE `deleted`=0 AND `category`='".$contract['category']."' AND `heading_number`='$heading_number') GROUP BY `third_heading_number`"));
								$heading_count = substr($heading_count['third_heading_number'],strlen($heading_count['sub_heading_number']) + 1) + 5;
								for($i = 1; $i <= $heading_count; $i++) {
									$heading = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `third_heading_number` FROM `contracts` WHERE `third_heading_number`='".$heading_number.'.'.$i."' AND `category`='".$contract['category']."' AND `deleted`=0"))['sub_heading']; ?>
									<option <?= $contract['third_heading_number'] === $heading_number.'.'.$i ? 'selected' : '' ?> value="<?= $heading_number.'.'.$i ?>"><?= $heading_number.'.'.$i.' '.$heading ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Third Tier Section Heading:</label>
						<div class="col-sm-8">
							<input type="text" name="third_heading" value="<?= $contract['third_heading'] ?>" class="form-control">
						</div>
					</div>
				<?php } ?>
				<?php if(in_array_any(['Business'],$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Contract Fields:</label>
						<div class="col-sm-8">
							<?php if(in_array('Business',$field_config)) { ?><label class="form-checkbox"><input type="checkbox" name="contract_fields[]" <?= in_array('Business',$fields) ? 'checked' : '' ?> value="Business"> Business</label><?php } ?>
						</div>
					</div>
				<?php } ?>
				<button class="pull-right btn brand-btn" name="submit" value="">Submit</button>
				<a href="?tab=<?= $_GET['tab'] ?>" class="pull-right btn brand-btn">Back</a>
			</form>
		</div>
	</div>
</div>