<?php
if (isset($_POST['contactid'])) {
	if($_POST['contactid'] != '') {
		$contacts_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` LEFT JOIN `contacts_security` ON `contacts`.`contactid`=`contacts_security`.`contactid` WHERE `contacts`.`contactid` = '{$_POST['contactid']}'"));
	}
	$errors = [];
	$successes = [];
	$user_name = filter_var($_POST['user_name'],FILTER_SANITIZE_STRING);
	$password = encryptIt($_POST['password']);
    $role = filter_var(','.trim(implode(',',$_POST['role']),',').',',FILTER_SANITIZE_STRING);
	$region_access = filter_var(implode('#*#', $_POST['region_access']),FILTER_SANITIZE_STRING);
	$location_access = filter_var(implode('#*#', $_POST['location_access']),FILTER_SANITIZE_STRING);
	$classification_access = filter_var(implode('#*#', $_POST['classification_access']),FILTER_SANITIZE_STRING);

    if(empty($_POST['contactid'])) {
        $query_insert_inventory = "INSERT INTO `contacts` (`category`, `user_name`, `password`, `password_date`, `role`, `show_hide_user`)
			VALUES 		('Staff', '$user_name', '$password', CURRENT_TIMESTAMP, '$role', '$show_hide_user')";

        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $contactid = mysqli_insert_id($dbc);
		$_GET['contactid'] = $contactid;
        $url = 'Added';
    } else {
        $contactid = $_POST['contactid'];
        $query_update_inventory = "UPDATE `contacts` SET `user_name` = '$user_name', `password` = '$password', `password_date`=CURRENT_TIMESTAMP, `password_update`=0, `role` = '$role' WHERE `contactid` = '$contactid'";
		//if(strpos($_SERVER['HTTP_HOST'],'highland') !== FALSE) {
			//send_email('', 'jonathanhurdman@freshfocusmedia.com', '', '', 'Security Level Change: Staff', get_contact($dbc, $_SESSION['contactid'])." changed the security level for $contactid to '$role'.<br />".WEBSITE_URL."<br />$query_update_inventory", '');
		//}
        $result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
		$_GET['contactid'] = $contactid;
		if(!empty($_POST['email_software_access'])) {
			$email_list = explode(',',$_POST['email_software_access']);
			$staff_name = get_staff($dbc, $contactid);
			$body = str_replace(['[STAFF_NAME]','[USERNAME]','[PASSWORD]'], [$staff_name, $user_name, $_POST['password']], $_POST['body']);
			foreach($email_list as $email) {
				try {
					send_email([$_POST['sender']=>$_POST['sender_name']], trim($email), '', '', $_POST['subject'], $body);
					$successes[] = "Username and Password successfully sent to ".$email.".";
					mysqli_query($dbc, "INSERT INTO `email_status` (`recipient`, `sender_name`, `sender_email`, `subject`, `body`, `description`) VALUES ('".filter_var($email,FILTER_SANITIZE_STRING)."', '".filter_var($_POST['sender_name'],FILTER_SANITIZE_STRING)."', '".filter_var($_POST['sender'],FILTER_SANITIZE_STRING)."', '".filter_var($_POST['subject'],FILTER_SANITIZE_STRING)."', '".filter_var($body,FILTER_SANITIZE_STRING)."', 'Username and Password Emailed out.')");
				} catch(Exception $e) {
					$errors[] = "Unable to send e-mail to $email, please try again later.<br />\n".$e->getMessage();
					mysqli_query($dbc, "INSERT INTO `email_status` (`recipient`, `sender_name`, `sender_email`, `subject`, `body`, `description`, `success`) VALUES ('".filter_var($email,FILTER_SANITIZE_STRING)."', '".filter_var($_POST['sender_name'],FILTER_SANITIZE_STRING)."', '".filter_var($_POST['sender'],FILTER_SANITIZE_STRING)."', '".filter_var($_POST['subject'],FILTER_SANITIZE_STRING)."', '".filter_var($body,FILTER_SANITIZE_STRING)."', '".filter_var("Failed to send Username and Password to recipient: ".$e->getMessage(),FILTER_SANITIZE_STRING)."', 0)");
				}
			}
		}

        $url = 'Updated';
    }
	
	// Update the contacts_security table
	mysqli_query($dbc, "INSERT INTO `contacts_security` (`contactid`) SELECT '$contactid' FROM (SELECT COUNT(*) rows FROM `contacts_security` WHERE `contactid`='$contactid') num WHERE num.rows = 0");
	mysqli_query($dbc, "UPDATE `contacts_security` SET `region_access`='$region_access' WHERE `contactid`='$contactid'");
	mysqli_query($dbc, "UPDATE `contacts_security` SET `location_access`='$location_access' WHERE `contactid`='$contactid'");
	mysqli_query($dbc, "UPDATE `contacts_security` SET `classification_access`='$classification_access' WHERE `contactid`='$contactid'");

	// Record the history of the change
	$contacts_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` LEFT JOIN `contacts_security` ON `contacts`.`contactid`=`contacts_security`.`contactid` WHERE `contacts`.`contactid` = '$contactid'"));
	$user = $_SESSION['first_name'].' '.$_SESSION['last_name'];
	$change_log = '';
	if($_POST['contactid'] != '') {
		foreach($contacts_after as $name => $value) {
			if(str_replace(['0000-00-00','0'], '', $contacts_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
				if($name == 'password') {
					$value = '************';
				}
				$change_log .= "$name set from '{$contacts_prior[$name]}' to '$value'.\n";
			}
		}
	}
	else {
		foreach($_POST as $name => $value) {
			if(trim($value) != '') {
				$change_log .= "$name set to '$value'.\n";
			}
		}
	}
	$change_log = filter_var($change_log,FILTER_SANITIZE_STRING);
	if(trim($change_log) != '') {
		$query = "INSERT INTO contacts_history (`updated_by`, `description`, `contactid`) VALUES ('$user', '$change_log', '$contactid')";
		mysqli_query($dbc, $query);
	}
	?>
	<script>
	var errors = '<?= implode("<br />",$errors) ?>';
	var successes = '<?= implode("<br />",$successes) ?>';
	$(document).ready(function() {
		if(errors != '') {
			$('.alert-danger').show().html(errors);
			setTimeout(function() { $('.alert-danger').fadeOut(); }, 10000);
		}
		if(successes != '') {
			$('.alert-success').show().html(successes);
			setTimeout(function() { $('.alert-success').fadeOut(); }, 10000);
		}
	});
	</script>
	<?php if(!empty($_POST['subtab'])) {
		$action_page = 'staff_edit.php?contactid='.$_GET['contactid'];
		if($_POST['subtab'] == 'software_access') {
			$action_page = 'edit_software_access.php?contactid='.$_GET['contactid'];
		} else if($_POST['subtab'] == 'certificates') {
			$action_page = 'certificate.php?contactid='.$_GET['contactid'];
		} else if($_POST['subtab'] == 'history') {
			$action_page = 'staff_history.php?contactid='.$_GET['contactid'];
		} else if($_POST['subtab'] == 'reminders') {
			$action_page = 'staff_reminder.php?contactid='.$_GET['contactid'];
		}?>
		<form action="<?php echo $action_page; ?>" method="post" id="change_page">
			<input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
		</form>
		<script type="text/javascript"> document.getElementById('change_page').submit(); </script>
	<?php }
}