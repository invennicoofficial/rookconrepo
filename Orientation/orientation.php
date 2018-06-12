<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('orientation');
error_reporting(0);

$contactid = $_SESSION['contactid'];
$img_width = $img_height = 20;

if(isset($_POST['submit'])) {
	$_GET['contactid'] = $_SESSION['contactid'];
	$category = 'Staff';
	include('../Contacts/contact_field_arrays.php');
	include('../Contacts/contacts_save.php');
	$dbc->query("UPDATE `orientation_staff` SET `completed`=1 WHERE `staffid`='{$_SESSION['contactid']}'");
} ?>
<style>
.tbl-orient a {color:black;
			Font-weight:bold;
}
.tbl-orient a:hover {
	text-shadow:none;
	color:black;
	font-style:italic;
	Font-weight:bold;
}
.tbl-orient {
			background-color:#EFEFEF;
			border-radius: 5px;
			position:relative;
			margin:auto;
			color:black;
			Font-weight:bold;
			width:100%;
}

.tbl-orient td {
			border-bottom:1px solid #000146;
			padding:10px;
}
.tbl-orient .bord-right {
    border-right:1px solid #D34345;

}
.form-group:after {
	content: '';
	clear: both;
	display: block;
}
</style>
</head>
<body>

<?php include_once ('../navigation.php'); ?>

<div class="container">
	<div class="iframe_overlay" style="display:none;">
		<div class="iframe">
			<iframe src="../blank_loading_page.php"></iframe>
		</div>
	</div>
	<div class="row">
        <div class="col-md-12">
			<h2> Orientation Checklist<?php if(config_visible_function($dbc, 'orientation') == 1) {
				echo '<a href="config_orientation.php" class="mobile-block pull-right "><img style="width: 50px;" title="Orientation Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><span class="popover-examples list-inline"><a class="pull-right" style="margin:-5px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will affect this tile for a particular staff."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
			} ?></h2>
			<h4> Employee Name: <?php echo decryptIt($_SESSION['first_name']); ?> <?php echo decryptIt($_SESSION['last_name']); ?> </h4>
			
			<div class="panel-group" id="accordion2">
				<?php 
				$contactid = $_SESSION['contactid'];
				$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `orientation_staff` WHERE `staffid`='$contactid' AND `completed`=0 ORDER BY `orientationid` DESC"));
				$start_date = $row['start_date'];
				$profile_fields = ','.$row['profile'].',';
				$hr = explode(',',$row['hr']);
				$safety = explode(',',$row['safety']);
				if($profile_fields != ',,') {
					$profile_date = mysqli_fetch_array(mysqli_query($dbc, "SELECT IFNULL(MAX(`updated_at`),'never') updated FROM `contacts_history` WHERE `updated_by`='".decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name'])."' AND `contactid`='".$_SESSION['contactid']."'"));
					$profile_date = $profile_date['updated']; ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_profile" >
									Profile<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_profile" class="panel-collapse collapse">
							<div class="panel-body">
								<table class="tbl-orient">
									<tr>
										<td>
											<p><span class="popover-examples list-inline"><a class="pull-left" style="margin:-5px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="To update your profile, make changes in the fields below. These will only be saved once you click Submit. Some fields may not be editable, because they have not been enabled by your system administrator."><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
											Update My Profile</a> (last updated by user <?php echo $profile_date; ?>)
											<?php
											if($profile_date >= $start_date) {
												echo '<img src="'.WEBSITE_URL.'/img/checkmark.png" width="'.$img_width.'" height="'.$img_height.'" border="0" alt="Profile Update Complete" class="pull-right">';
											}
											?></p>
											<form action="" method="POST">
											<input type="hidden" name="contactid" value="<?php echo $_SESSION['contactid']; ?>">
											<div class="panel-group" id="profile_accordions">
												<?php $_GET['contactid'] = $_SESSION['contactid'];
												$category = 'Staff';
												include('../Contacts/contact_field_arrays.php');
												include('../Profile/profile_vars.php');
												$query_main = mysqli_query($dbc, "SELECT accordion, subtab, contacts FROM field_config_contacts WHERE tab='Staff' AND `accordion` IS NOT NULL AND `accordion` != '' AND `order` IS NOT NULL AND REPLACE(`contacts`,',','') != '' AND `subtab` IS NOT NULL
													ORDER BY CASE `subtab` WHEN 'staff_information' THEN 1 WHEN 'staff_address' THEN 2 WHEN 'employee_information' THEN 3 WHEN 'driver_information' THEN 4 WHEN 'direct_deposit_information' THEN 5 WHEN 'software_id' THEN 6 WHEN 'social_media' THEN 7 WHEN 'emergency' THEN 8 ELSE 9 END, `order`");
												$profile_i = 0;
												while($row_main = mysqli_fetch_array($query_main)) {
													$value_config = ','.$row_main['contacts'].',';
													$get_edits = mysqli_fetch_array(mysqli_query($dbc, "SELECT contacts FROM field_config_contacts WHERE tab='Profile' AND IFNULL(subtab,'')='".$row_main['subtab']."' AND accordion='".$row_main['accordion']."'"));
													$edit_config = ','.$get_edits['contacts'].','; ?>
													<div class="panel panel-default">
														<div class="panel-heading">
															<h4 class="panel-title">
																<a data-toggle="collapse" data-parent="#profile_accordions" href="#collapse_profile_<?php echo $profile_i; ?>" >
																	<?php echo $row_main['accordion']; ?><span class="glyphicon glyphicon-plus"></span>
																</a>
															</h4>
														</div>

														<div id="collapse_profile_<?php echo $profile_i; ?>" class="panel-collapse collapse">
															<div class="panel-body">
																<?php // error_reporting(E_ALL);
																include ('../Contacts/add_contacts_basic_info.php');
																include ('../Contacts/add_contacts_dates.php');
																include ('../Contacts/add_contacts_cost.php');
																include ('../Contacts/add_contacts_description.php');
																include ('../Contacts/add_contacts_upload.php'); ?>
															</div>
														</div>
													</div>
													<?php $profile_i++;
												} ?>
											</div>
											<button type='submit' name='submit' value='submit' class="btn brand-btn btn-lg pull-right">Submit</button>
											</form>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(count(array_filter($hr)) > 0) { ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_forms" >
									HR<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_forms" class="panel-collapse collapse">
							<div class="panel-body">
								<ul>
									<?php foreach($hr as $form) {
										$form = explode(':',$form);
										if($form[0] == 'hr' && $form[1] > 0) {
											$details = $dbc->query("SELECT * FROM `hr` WHERE `hrid`='{$form[1]}'")->fetch_assoc(); ?>
											<li><a href="" onclick="overlayIFrameSlider('http://local.rookconnect.com/HR/index.php?tile_name=hr&hr=<?= $form[1] ?>'); return false;">
												<?= $details['third_heading'] != '' ? $details['third_heading_number'].' '.$details['third_heading'] : ($details['sub_heading'] != '' ? $details['sub_heading_number'].' '.$details['sub_heading'] : $details['heading_number'].' '.$details['heading']) ?>
											</a></li>
										<?php } else if($form[0] == 'manuals' && $form[1] > 0) {
											$details = $dbc->query("SELECT * FROM `manuals` WHERE `manualtypeid`='{$form[1]}'")->fetch_assoc(); ?>
											<li><a href="" onclick="overlayIFrameSlider('http://local.rookconnect.com/HR/index.php?tile_name=hr&manual=<?= $form[1] ?>'); return false;">
												<?= $details['third_heading'] != '' ? $details['third_heading_number'].' '.$details['third_heading'] : ($details['sub_heading'] != '' ? $details['sub_heading_number'].' '.$details['sub_heading'] : $details['heading_number'].' '.$details['heading']) ?>
											</a></li>
										<?php } ?>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if(count(array_filter($safety)) > 0) { ?>
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
								<ul>
									<?php if($form > 0) {
										$details = $dbc->query("SELECT * FROM `safety` WHERE `safetyid`='$form'")->fetch_assoc(); ?>
										<li><a href="" onclick="overlayIFrameSlider('http://local.rookconnect.com/Safety/index.php?safetyid=<?= $form[1] ?>'); return false;">
											<?= $details['third_heading'] != '' ? $details['third_heading_number'].' '.$details['third_heading'] : ($details['sub_heading'] != '' ? $details['sub_heading_number'].' '.$details['sub_heading'] : $details['heading_number'].' '.$details['heading']) ?>
										</a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<!--<div class="form-group triple-pad-top triple-pad-bottom clearfix location">
				<label for="site_name" class="col-sm-4 control-label text-right"></label>
				<div class="col-sm-12">
					<?php //echo orientation_checklist($dbc, $_SESSION['contactid'], '55', '20', '20'); ?>
				</div>
			</div>-->
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>