<?php
$role = '';
$user_name = '';
$password = '';
$show_hide_user = 0;
if (!empty($_POST['subtab'])) {
    $subtab = $_POST['subtab'];
} else {
	$subtab = 'software_access';
}

if(!empty($_GET['contactid']))	{
	$contactid = $_GET['contactid'];
	$get_contact =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	contacts WHERE	contactid='$contactid'"));

	$user_name = $get_contact['user_name'];
	$password = decryptIt($get_contact['password']);
	$role = $get_contact['role'];
	$show_hide_user = $get_contact['show_hide_user'];
}
if(strpos(','.ROLE.',',',super,') === false) {
	foreach($role as $role_current)
	$security_sql = "SELECT COUNT(*) numrows FROM `subtab_config` WHERE `tile`='staff' AND `subtab`='software_access_".$role_current."' AND ',".trim(ROLE,',').",' LIKE CONCAT('%,',`security_level`,',%') ORDER BY IF(`status` like '%turn_off%', 0, 1)";
	$security_result = mysqli_fetch_array(mysqli_query($dbc, $security_sql));
	if($security_result['numrows'] == 0) {
		mysqli_query($dbc, "INSERT INTO `subtab_config` (`tile`, `subtab`, `security_level`, `status`) VALUES ('staff', 'software_access_".$role_current."', '".trim(ROLE,',')."', '*turn_off*')");
	}
}
foreach($role as $role_current) {
	if(check_subtab_persmission($dbc, 'staff', ROLE, 'software_access_'.$role_current) == false || strpos(WEBSITE_URL, '.clinicace.') !== FALSE) {
		exit('<h2>Access denied, please contact your system administrator</h2>');
	}
}
$field_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
$contact_security = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts_security` WHERE `contactid`='$contactid'"));
?>

<?php $value_config = ',Role,User Name,Password,Show/Hide User,';
$edit_config = $value_config;
if($_SESSION['contactid'] == $contactid) {
	$edit_config = ',User Name,Password,Show/Hide User,';
}
?>
<h4>Software Access</h4>

<?php 

include ('../Contacts/add_contacts_basic_info.php');
$contact_regions = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') FROM `general_configuration` WHERE `name` LIKE '%_region'"))[0])));
if(count($contact_regions) > 0) {
	echo '<div class="form-group"><label class="col-sm-4 control-label"><span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Select which regions are allowed."><img src="../img/info.png" width="20"></a></span> Regions Allowed:</label><div class="col-sm-8">';
	$allowed_regions = array_filter(explode('#*#', $contact_security['region_access']));
	if(count($allowed_regions) == 0) {
		$allowed_regions = $contact_regions;
	}
	foreach($contact_regions as $region_name) { ?>
		<label class="form-checkbox"><input type="checkbox" name="region_access[]" <?= in_array($region_name, $allowed_regions) ? 'checked' : '' ?> value="<?= $region_name ?>"><?= $region_name ?></label>
	<?php }
	echo '</div></div>';
}
$contact_locations = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `con_locations` SEPARATOR ',') FROM `field_config_contacts`"))[0])));
if(count($contact_locations) > 0) {
	echo '<div class="form-group"><label class="col-sm-4 control-label"><span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Select which locations are allowed."><img src="../img/info.png" width="20"></a></span> Locations Allowed:</label><div class="col-sm-8">';
	$allowed_locations = array_filter(explode('#*#', $contact_security['location_access']));
	if(count($allowed_locations) == 0) {
		$allowed_locations = $contact_locations;
	}
	foreach($contact_locations as $location_name) {
		$location_arr = explode('*#*', $location_name); ?>
		<label class="form-checkbox"><input type="checkbox" name="location_access[]" <?= in_array($location_name, $allowed_locations) ? 'checked' : '' ?> value="<?= $location_name ?>"><?= $location_arr[0] ?></label>
	<?php }
	echo '</div></div>';
}
$contact_classifications = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') FROM `general_configuration` WHERE `name` LIKE '%_classification'"))[0])));
if(count($contact_classifications) > 0) {
	echo '<div class="form-group"><label class="col-sm-4 control-label"><span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Select which classifications are allowed."><img src="../img/info.png" width="20"></a></span> Classifications Allowed:</label><div class="col-sm-8">';
	$allowed_classifications = array_filter(explode('#*#', $contact_security['classification_access']));
	if(count($allowed_classifications) == 0) {
		$allowed_classifications = $contact_classifications;
	}
	foreach($contact_classifications as $classification_name) { ?>
		<label class="form-checkbox"><input type="checkbox" name="classification_access[]" <?= in_array($classification_name, $allowed_classifications) ? 'checked' : '' ?> value="<?= $classification_name ?>" data-field="classification_access" data-table="contacts_security" data-delimiter="#*#" data-include="checkedonly"><?= $classification_name ?></label>
	<?php }
	echo '</div></div>';
} ?>
<input type="hidden" name="edit_software_access" value="1">
<h4>Email Software Access</h4>

<div class="form-group">
	<label for="company_name" class="col-sm-4 control-label">
        <span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Input recipient (<?= strtolower(FOLDER_NAME) ?>) email address."><img src="../img/info.png" width="20"></a></span>
        Recipient Email Addresses:<br />
		<small>The email will send once you enter the username and password in the email body and click submit. To enter multiple email addresses, separate each email address with a comma.</small></label>
	<div class="col-sm-8">
		<input name="email_software_access" value="" type="text" class="form-control">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
        <span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Input sender's name."><img src="../img/info.png" width="20"></a></span>
        Email Sender's Name:
    </label>
	<div class="col-sm-8">
		<input type="text" name="sender_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
        <span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Input sender's email address."><img src="../img/info.png" width="20"></a></span>
        Email Sender's Address:
    </label>
	<div class="col-sm-8">
		<input type="text" name="sender" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
        <span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Input email subject."><img src="../img/info.png" width="20"></a></span>
        Email Subject:
    </label>
	<div class="col-sm-8">
		<input type="text" name="subject" class="form-control" value="New User Account Created">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">
        <span class="popover-examples"><a data-toggle="tooltip" data-placement="top" title="Input <?= strtolower(FOLDER_NAME) ?> name, username and password inside the provided brackets, or create your own email message."><img src="../img/info.png" width="20"></a></span>
        Email Body:<br /><em>The following text will be replaced in the actual email as follows:<br />
		[STAFF_NAME]: Staff Full Name<br />
		[USERNAME]: Software Username<br />
		[PASSWORD]: Software Password</em></label>
	<div class="col-sm-8">
		<?= get_text_templates($dbc, 'staff', 'software_id', 'email_body'); ?>
		<textarea name="body" class="form-control">Hello [STAFF_NAME],<br /><br />
		The administrator for <a href="<?php echo WEBSITE_URL; ?>"><?php echo WEBSITE_URL; ?></a> has created a user account for you. You will need the following information to log in:<br /><br />
		Username: [USERNAME]<br />
		Password: [PASSWORD]<br /><br />
		Thank you.</textarea>
	</div>
</div>