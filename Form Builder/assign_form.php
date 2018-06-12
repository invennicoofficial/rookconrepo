<?php if(!empty($_POST['assign'])) {
	$form_id = $_POST['form_id'];
	$user_id = $_POST['user_id'];
	$email_address = filter_var($_POST['email_address'],FILTER_SANITIZE_STRING);
	$due_date = filter_var($_POST['due_date'],FILTER_SANITIZE_STRING);
	$assigned_by = $_SESSION['contactid'];
	
	mysqli_query($dbc, "INSERT INTO `user_form_assign` (`form_id`, `assigned_by`, `user_id`, `email_address`, `due_date`)
		VALUES ('$form_id', '$assigned_by', '$user_id', '$email_address', '$due_date')");
	
	$form = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `user_forms` WHERE `form_id`='$form_id'"));
	$subject = $_POST['email_subject'];
	$email = ($user_id > 0 ? get_email($dbc, $user_id) : '');
	if($email == '') {
		$email = $email_address;
	}
	
	if($user_id > 0) {
		$body = "<p>The {$form['name']} form has been assigned to you to complete. Please log in to the software and complete the form. You are assigned to complete this form by $due_date.</p>
			<p>Click <a href='".WEBSITE_URL."/Form Builder/formbuilder.php?tab=generate_form&id=$form_id'>here</a> to view the form.</p>";
	} else {
		$body = "<p>You have been asked to complete a {$form['name']} form by $due_date. You can access the form <a href='".WEBSITE_URL."/Form Builder/formbuilder.php?tab=external_form&id=$form_id'>here</a>.</p>";
	}
	
	foreach(explode(',',$email) as $email) {
		try {
			send_email([$_POST['email_src'] => $_POST['email_name']], trim($email), '', '', $subject, $body, '');
		} catch(Exception $e) {
			echo "<script> alert('Unable to send an email to ".trim($email).". Please check the email address, and try again later. If the problem persists, please contact Fresh Focus Media.'); </script>";
		}
	}
	
	echo "<script> window.location.replace('?'); </script>";
}
$form_id = filter_var($_GET['id'],FILTER_SANITIZE_STRING); ?>
<form name="assign_form" method="post" action="" class="form-horizontal" role="form">
	<input type="hidden" name="form_id" value="<?= $form_id ?>">
	<div class="form-group">
		<label class="col-sm-4 control-label">Assigned User:</label>
		<div class="col-sm-8">
			<select name="user_id" class="form-control chosen-select-deselect"><option></option>
				<?php $user_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted`=0 AND `status`>0 AND `show_hide_user`=1"),MYSQLI_ASSOC));
				foreach($user_list as $id) { ?>
					<option value="<?= $id ?>"><?= get_contact($dbc, $id) ?></option>
				<?php } ?>
				<option value="0">External User</option>
			</select>
		</div>
	</div><div class="clearfix"></div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Manual Email Address(es):<br /><em>You can add multiple email addresses separated by a comma</em></label>
		<div class="col-sm-8">
			<input type="text" name="email_address" value="" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Due Date:</label>
		<div class="col-sm-8">
			<input type="text" name="due_date" value="<?= date('Y-m-d', strtotime('+1month')) ?>" class="form-control datepicker">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Email Sender's Name:</label>
		<div class="col-sm-8">
			<input type="text" name="email_name" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Email Sender's Address:</label>
		<div class="col-sm-8">
			<input type="text" name="email_src" value="<?= get_email($dbc, $_SESSION['contactid']) ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4 control-label">Email Subject:</label>
		<div class="col-sm-8">
			<input type="text" name="email_subject" value="Form Request: Please Complete <?= mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `name` FROM `user_forms` WHERE `form_id`='$form_id'"))['name'] ?>" class="form-control">
		</div>
	</div>
	<a href="?" class="btn brand-btn pull-left">Back</a>
	<button type="submit" name="assign" value="assign" class="btn brand-btn pull-right">Submit</button>
</form>