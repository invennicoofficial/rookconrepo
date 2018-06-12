<?php include('function.php');
$patient_list = mysqli_query($dbc, "SELECT `id`, `patient` FROM `mrbs_entry` WHERE `patient` LIKE '% %'");
while($row = mysqli_fetch_array($patient_list)) {
	$full_name = explode(' ', $row['patient']);
	$first_name = encryptIt($full_name[0]);
	$last_name = encryptIt($full_name[1]);
	$contact_matched = mysqli_query($dbc, "SELECT `contactid` FROM `contacts` WHERE `first_name`='$first_name' AND `last_name`='$last_name'");
	if(mysqli_num_rows($contact_matched) == 1) {
		$contactid = mysqli_fetch_array($contact_matched)['contactid'];
		mysqli_query($dbc, "UPDATE `mrbs_entry` SET `patient`='$contactid' WHERE `id`='".$row['id']."'");
	} else {
		//echo "Unable to find and match ".$row['patient']." into the database.<br />\n";
	}
}