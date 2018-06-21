<?php //Assign a contract to be completed by a Staff and Contact
include_once('../include.php');
checkAuthorised('contracts');
error_reporting(0);
if(!empty($_GET['unassign'])) {
	$assignid = $_GET['assignid'];
	$query = mysqli_query($dbc, "UPDATE `contracts_staff` SET `deleted`=1 WHERE `contractstaffid`='$assignid'");
	echo "<script> window.location.replace('follow_up.php'); </script>";
}
else if(!empty($_POST['assign_contract'])) {
	$contractid = $_POST['assign_contract'];
	$category = get_contract($dbc, $contractid, 'category');
	$recipient = $_POST['email_recipient'];
	$businessid = $_POST['businessid'];
	$contactid = implode(',',$_POST['contactid']);
	$due_date = $_POST['due_date'];
	$assignid = $_POST['assignid'];
	if(empty($assignid)) {
		$query = "INSERT INTO `contracts_staff` (`contractid`, `recipient`, `contactid`, `businessid`, `due_date`) VALUES ('$contractid', '$recipient', '$contactid', '$businessid', '$due_date')";
		$assignid = mysqli_insert_id($dbc);
	} else {
		$query = "UPDATE `contracts_staff` SET `contractid`='$contractid', `recipient`='$recipient', `contactid`='$contactid', `businessid`='$businessid', `due_date`='$due_date' WHERE `contractstaffid`='$assignid'";
	}
	$result = mysqli_query($dbc, $query);
	
	$subject = $_POST['email_subject'];
	$body = str_replace(['[SIGN_OFF]','[ASSIGNID]'], [$due_date,$assignid], $_POST['email_body']);
	
	try {
		send_email([$_POST['email_sender']=>$_POST['email_name']], array_filter(explode(',',$recipient)), '', '', $subject, $body, '');
	} catch(Exception $e) { }
		
	$reminder_date = filter_var($_POST['reminder_date'],FILTER_SANITIZE_STRING);
	$remind_recipient = filter_var($_POST['reminder_recipient'],FILTER_SANITIZE_STRING);
	$subject = filter_var($_POST['reminder_subject'],FILTER_SANITIZE_STRING);
	$sender = filter_var($_POST['reminder_sender'],FILTER_SANITIZE_STRING);
	$sender_name = filter_var($_POST['reminder_name'],FILTER_SANITIZE_STRING);
	$body = str_replace(['[SIGN_OFF]','[ASSIGNID]'], [$due_date,$assignid], filter_var(htmlentities($_POST['reminder_body']),FILTER_SANITIZE_STRING));
	$verify = "contracts_staff#*#done#*#contractstaffid#*#".$assignid."#*#1";
	$reminder_sql = mysqli_query($dbc, "INSERT INTO `reminders` (`recipient`, `reminder_date`, `reminder_type`, `verify`, `subject`, `body`, `sender`, `sender_name`)
		VALUES ('$remind_recipient', '$reminder_date', 'Contract', '$verify', '$subject', '$body', '$sender', '$sender_name')");
	
	echo "<script> window.location.replace('contracts.php?tab=$category'); </script>";
} ?>
<script>
$(document).ready(function() {
	$('[name=email_recipient]').change(function() {
		var old_email = $(this).data('old_value');
		var new_email = this.value;
		var old_recip = $('[name=reminder_recipient]').val();
		if(old_email == old_recip) {
			$('[name=reminder_recipient]').val(new_email);
		}
		$(this).data('old_value',new_email);
	});
});
$(document).on('change', 'select[name="businessid"]', function() { loadContacts(this.value); });

function loadContacts(business) {
	$.ajax({
		url: 'contracts_ajax.php?fill=business_contacts&businessid='+business,
		method: 'GET',
		complete: function(result) {
			$('[name="contactid[]"]').empty().html(result.responseText).trigger('change.select2');
		}
	});
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
$contractid = $_GET['contractid'];
$contract = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contracts` WHERE `contractid`='$contractid'"));
$assignid = '';
$recipient = '';
$businessid = 0;
$contactid = 0;
$due_date = '';
if(!empty($_GET['contractstaffid'])) {
	$assignid = $_GET['contractstaffid'];
	$assigned = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contracts_staff` WHERE `contractstaffid`='".$assignid."'"));
	$recipient = $assigned['recipient'];
	$businessid = $assigned['businessid'];
	$contactid = $assigned['contactid'];
	$due_date = $assigned['due_date'];
}
$category = $contract['category'];
$contract_name = $contract['contract_name']; ?>
<div class="container">
	<div class="row">
		<h1>Assign a <?= $contract_name ?> Contract to be Completed</h1>
		<a href="contracts.php?tab=<?= $category ?>" class="btn brand-btn pull-left">Back to Dashboard</a>
		<div class="clearfix"></div><br />
		<form id="form" name="form" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
			<input type="hidden" name="assignid" value="<?= $assignid ?>">
			<div class="panel-group" id="accordion2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_list" >Assign Contract<span class="glyphicon glyphicon-plus"></span></a>
						</h4>
					</div>

					<div id="collapse_list" class="panel-collapse collapse">
						<div class="panel-body">
							<?php if($contract['category'] == 'Customer') { ?>
								<div class="form-group">
									<label class="col-sm-4 control-label">Customer:</label>
									<div class="col-sm-8">
										<select data-placeholder="Select a Customer" name="businessid" class="chosen-select-deselect form-control" width="380">
											<option value=""></option>
											<?php $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name, category, status FROM contacts WHERE deleted=0 AND category='Business'"),MYSQLI_ASSOC));
											foreach($query as $id) { ?>
												<option <?php echo ($businessid == $id ? 'selected' : ''); ?> value='<?= $id ?>' ><?= get_client($dbc, $id) ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label">Contact Name:</label>
									<div class="col-sm-8">
										<select data-placeholder="Select Contact(s)" name="contactid[]" multiple class="chosen-select-deselect form-control" width="380">
										  <option value=""></option>
										  <?php
											$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT DISTINCT `contactid`, `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE `deleted`=0 AND `businessid`='$businessid'"),MYSQLI_ASSOC));
											$category = '';
											foreach($query as $id) {
												$contact = mysqli_fetch_array(mysqli_query($dbc, "SELECT `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE `contactid`='$id'"));
												if($category != $contact['category']) {
													$category = $contact['category'];
													echo "<optgroup label='$category'>\n";
												}
												$name = get_client($dbc, $id);
												if($name == '') {
													$name = get_contact($dbc, $id);
												} ?>
												<option <?php echo ($contactid == $id ? 'selected' : ''); ?> value='<?php echo $id; ?>' ><?php echo $name; ?></option>
											<?php }
										  ?>
										</select>
									</div>
								</div>
							<?php } else { ?>
								<?php $cat_count = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) count FROM `contacts` WHERE `category`='".$contract['category']."'"))['count']; ?>
								<div class="form-group">
									<label class="col-sm-4 control-label">Assign to <?= ($cat_count > 0 ? $contract['category'] : 'Contact') ?>:</label>
									<div class="col-sm-8">
										<select data-placeholder="Select <?= ($cat_count > 0 ? $contract['category'] : 'Contact(s)') ?>" name="contactid[]" multiple class="chosen-select-deselect form-control" width="380">
										  <option value=""></option>
										  <?php
											$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT DISTINCT `contactid`, `name`, `first_name`, `last_name` FROM `contacts` WHERE `deleted`=0".($cat_count > 0 ? " AND `category`='".$contract['category']."'" : "")),MYSQLI_ASSOC));
											foreach($query as $id) {
												$name = get_client($dbc, $id);
												if($name == '') {
													$name = get_contact($dbc, $id);
												} ?>
												<option <?php echo ($contactid == $id ? 'selected' : ''); ?> value='<?php echo $id; ?>' ><?php echo $name; ?></option>
											<?php }
										  ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Estimated Sign Off Date:</label>
								<div class="col-sm-8">
									<input type="text" name="due_date" class="form-control datepicker" value="<?= $due_date ?>" placeholder="Select a Due Date">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_email" >Send Email<span class="glyphicon glyphicon-plus"></span></a>
						</h4>
					</div>

					<div id="collapse_email" class="panel-collapse collapse">
						<div class="panel-body">
							<?php $sender = get_contact($dbc, $_SESSION['contactid'], 'email_address');
							$subject = 'Contract to be Executed';
							$body = '<p>Please complete the following contract by [SIGN_OFF].</p>
								<p>Contract: <a target="_blank" href="'.WEBSITE_URL.'/Contracts/fill_contract.php?contractid='.$contractid.'&assignid=[ASSIGNID]">'.$contract_name.'</a></p>'; ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Sending Email Name:</label>
								<div class="col-sm-8">
									<input type="text" name="email_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Sending Email Address:</label>
								<div class="col-sm-8">
									<input type="text" name="email_sender" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Recipient Email Address(es):<br /><em><small>Separate multiple email addresses with a comma</small></em></label>
								<div class="col-sm-8">
									<input type="text" name="email_recipient" class="form-control" value="<?php echo $recipient; ?>" data-old_value="<?php echo $recipient; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Email Subject:</label>
								<div class="col-sm-8">
									<input type="text" name="email_subject" class="form-control" value="<?php echo $subject; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Email Body:</label>
								<div class="col-sm-8">
									<textarea name="email_body" class="form-control"><?php echo $body; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<a href="contracts.php?tab=<?= $category ?>" class="btn brand-btn pull-left">Back</a>
								<button type="submit" name="assign_contract" value="<?= $contractid ?>" class="btn brand-btn pull-right">Submit</button>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_remind" >Schedule Reminder<span class="glyphicon glyphicon-plus"></span></a>
						</h4>
					</div>

					<div id="collapse_remind" class="panel-collapse collapse">
						<div class="panel-body">
							<?php $sender = get_email($dbc, $_SESSION['contactid']);
							$subject = 'Reminder of Contract to be Executed';
							$body = '<p>Please complete the following contract by [SIGN_OFF].</p>
								<p>Contract: <a target="_blank" href="'.WEBSITE_URL.'/Contracts/fill_contract.php?contractid='.$contractid.'&assignid=[ASSIGNID]">'.$contract_name.'</a></p>'; ?>
							<div class="form-group">
								<label class="col-sm-4 control-label">Reminder Date:</label>
								<div class="col-sm-8">
									<input type="text" name="reminder_date" class="form-control datepicker" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Sending Email Name:</label>
								<div class="col-sm-8">
									<input type="text" name="reminder_name" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Sending Email Address:</label>
								<div class="col-sm-8">
									<input type="text" name="reminder_sender" class="form-control" value="<?php echo $sender; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Recipient Email Address(es):<br /><em><small>Separate multiple email addresses with a comma</small></em></label>
								<div class="col-sm-8">
									<input type="text" name="reminder_recipient" class="form-control" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Email Subject:</label>
								<div class="col-sm-8">
									<input type="text" name="reminder_subject" class="form-control" value="<?php echo $subject; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Email Body:</label>
								<div class="col-sm-8">
									<textarea name="reminder_body" class="form-control"><?php echo $body; ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<span class="popover-examples list-inline pull-left"><a data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href="contracts.php?tab=<?= $category ?>" class="btn brand-btn btn-lg pull-left">Back</a>
				<button type="submit" name="assign_contract" value="<?= $contractid ?>" class="btn brand-btn btn-lg pull-right">Submit</button>
				<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to send your contract."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			</div>
		</form>
	</div>
</div>
<?php include('../footer.php'); ?>