<?php
include('../include.php');
checkAuthorised('interactive_calendar');
$appendid = $_POST['appendid'];
$datename = $_POST['datename'];
$appendidarray = explode('-', $appendid);
$contactid = $_SESSION['contactid'];
$filename = $contactid . '-' . $appendidarray[2] . '-' . $appendidarray[3];
if(is_array($_FILES)) {
if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
$sourcePath = $_FILES['userImage']['tmp_name'];
$ext = pathinfo($_FILES['userImage']['name'], PATHINFO_EXTENSION);
$filename .= '.'.$ext;
$targetPath = "images/".$filename;
if(move_uploaded_file($sourcePath,$targetPath)) {
	$result = mysqli_query($dbc,"SELECT intercalendarid from interactive_calendar where intercalendarid = '$datename'");
	$row = mysqli_fetch_array($result);
	$existingcount = count($row);
	if($existingcount > 0) {
		$intercalendarid = $row['intercalendarid'];
		if($appendidarray[2] == 'morning')
			$query_update_vendor = "UPDATE `interactive_calendar` SET `morning_image` = '$filename' WHERE `intercalendarid` = '$intercalendarid'";
		if($appendidarray[2] == 'lunch')
			$query_update_vendor = "UPDATE `interactive_calendar` SET `lunch_image` = '$filename' WHERE `intercalendarid` = '$intercalendarid'";
		if($appendidarray[2] == 'afternoon')
			$query_update_vendor = "UPDATE `interactive_calendar` SET `afternoon_image` = '$filename' WHERE `intercalendarid` = '$intercalendarid'";
		if($appendidarray[2] == 'dinner')
			$query_update_vendor = "UPDATE `interactive_calendar` SET `dinner_image` = '$filename' WHERE `intercalendarid` = '$intercalendarid'";
		if($appendidarray[2] == 'evening')
			$query_update_vendor = "UPDATE `interactive_calendar` SET `evening_image` = '$filename' WHERE `intercalendarid` = '$intercalendarid'";
		$result_update_vendor = mysqli_query($dbc, $query_update_vendor);
	}
	else {
		$query_insert_vendor = "INSERT INTO `interactive_calendar` (`activity_date`,`$appendidarray[2]_image`) VALUES ('$datename', '$filename')";
		$result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
		$intercalendarid = mysqli_insert_id($dbc);
	}

?>
<img id="img-<?php echo $intercalendarid; ?>" src="<?php echo $targetPath; ?>" width="250" height="250" hspace=15 />
<?php
}
}
}
?>