<?php
if (isset($_POST['contactid'])) {
	if($_GET['edit_contact'] == 'true') {
		echo "<!--"; // Just hide the missing field warnings that will show up for submitted values
		if($_POST['contactid'] != '') {
			$contacts_prior = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '{$_POST['contactid']}'"));
		}
		$user_name = filter_var($_POST['user_name'],FILTER_SANITIZE_STRING);
		$password = filter_var(encryptIt($_POST['password']),FILTER_SANITIZE_STRING);

		$contactid = $_SESSION['contactid'];
		$query_update_inventory = "UPDATE `contacts` SET `user_name` = '$user_name', `password` = '$password' WHERE `contactid` = '$contactid'";

		$result_update_inventory	= mysqli_query($dbc, $query_update_inventory);
		$_GET['id'] = $contactid;

		$url = 'Updated';

		// Record the history of the change
		$contacts_after = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '$contactid'"));
		$user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
		$change_log = '';
		foreach($contacts_after as $name => $value) {
			if(str_replace(['0000-00-00','0'], '', $contacts_prior[$name]) != str_replace(['0000-00-00','0'], '', $value)) {
				if($name == 'password') {
					$value = '************';
				}
				$change_log .= "$name set from '{$contacts_prior[$name]}' to '$value'.\n";
			}
		}
		$change_log = filter_var($change_log,FILTER_SANITIZE_STRING);
		$query = "INSERT INTO contacts_history (`updated_by`, `description`, `contactid`) VALUES ('$user', '$change_log\nSet from the Profile tile.', '$contactid')";
		mysqli_query($dbc, $query);
		echo '-->'; 
	} ?>
	<?php if(!empty($_POST['subtab'])) {
		$action_page = 'my_profile.php?edit_contact='.$_GET['edit_contact'];
		if($_POST['subtab'] == 'certificates') {
			$action_page = 'my_certificate.php?edit_contact='.$_GET['edit_contact'];
		} else if($_POST['subtab'] == 'goals') {
			$action_page = 'gao_goal.php?edit_contact='.$_GET['edit_contact'];
		} else if($_POST['subtab'] == 'daysheet') {
			$action_page = 'daysheet.php';
		}?>
		<form action="<?php echo $action_page; ?>" method="post" id="change_page">
			<input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
		</form>
		<script type="text/javascript"> document.getElementById('change_page').submit(); </script>
	<?php }
}
