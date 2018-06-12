<?php require_once('../include.php');
checkAuthorised('sales');

function invalid_id() { ?>
	<script>
		alert("Invalid Sales Lead, unable to save to Customers.");
		window.history.back();
	</script>
	exit();
<?php }

if(empty($_GET['leadid'])) {
	invalid_id();
} else {
	if($result = mysqli_query($dbc, "SELECT * FROM `sales` WHERE `salesid`='".$_GET['leadid']."'")) {
		$lead_info = mysqli_fetch_array($result);
	} else {
		inactive_id();
	}
	if($result = mysqli_query($dbc, "UPDATE `sales` SET `status`='Customers' WHERE `salesid`='".$lead_info['salesid']."'")) {
		$businessid = $lead_info['businessid'];
		$contactid = $lead_info['contactid'];
		$phone = $lead_info['primary_number'];
		$email = $lead_info['email_address'];
		if($contactid == 0) {
			$result = mysqli_query($dbc, "INSERT INTO `contacts` (`category`) VALUES ('Customers')");
			$lead_info['contactid'] = mysqli_insert_id($dbc);
		}
		mysqli_query($dbc, "UPDATE `contacts` SET `category`='Customers' WHERE `contactid`='".$lead_info['contactid']."'");
		echo "<h3>Sales Lead saved to Customers!</h3>";
		echo "<script>setTimeout(function() { window.location.replace('".WEBSITE_URL."/Contacts/contacts.php?category=Customers') }, 1000);</script>";
	} else {
		inactive_id();
	}
}