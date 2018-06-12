<?php include_once('../include.php');
checkAuthorised('staff');
if($_POST['submit'] == 'import_contacts') {
	if(!empty($_FILES['file']['tmp_name'])) {
		$i = 0;
		$error = false;
		$staff = get_contact($dbc, $_SESSION['contactid']);
		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$headers = fgetcsv($handle, 0, ",");
		$c = 0;
		while(($row = fgetcsv($handle, 0, ",")) !== false)
		{
			$values = [];
			foreach($headers as $i => $col) {
				$values[filter_var($col,FILTER_SANITIZE_STRING)] = filter_var(htmlentities(isEncrypted($col) ? encryptIt($row[$i]) : $row[$i],FILTER_SANITIZE_STRING));
			}
			
			$contactid = $values['contactid'];
			if(empty($contactid)) {
				mysqli_query($dbc, "INSERT INTO `contacts` VALUES ()");
				$contactid = mysqli_insert_id($dbc);
				$values['contactid'] = $contactid;
			}
			$values['category'] = 'Staff';
			$values['tile_name'] = 'staff';
			mysqli_query($dbc, "INSERT INTO `contacts_cost` (`contactid`) SELECT '$contactid' FROM (SELECT COUNT(*) num FROM `contacts_cost` WHERE `contactid` = '$contactid') rows WHERE rows.num=0");
			mysqli_query($dbc, "INSERT INTO `contacts_dates` (`contactid`) SELECT '$contactid' FROM (SELECT COUNT(*) num FROM `contacts_dates` WHERE `contactid` = '$contactid') rows WHERE rows.num=0");
			mysqli_query($dbc, "INSERT INTO `contacts_description` (`contactid`) SELECT '$contactid' FROM (SELECT COUNT(*) num FROM `contacts_description` WHERE `contactid` = '$contactid') rows WHERE rows.num=0");
			mysqli_query($dbc, "INSERT INTO `contacts_medical` (`contactid`) SELECT '$contactid' FROM (SELECT COUNT(*) num FROM `contacts_medical` WHERE `contactid` = '$contactid') rows WHERE rows.num=0");

			if($contactid > 0) {
				$original = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `contacts` LEFT JOIN `contacts_cost` ON `contacts`.`contactid`=`contacts_cost`.`contactid` LEFT JOIN `contacts_dates` ON `contacts`.`contactid`=`contacts_dates`.`contactid` LEFT JOIN `contacts_description` ON `contacts`.`contactid`=`contacts_description`.`contactid` LEFT JOIN `contacts_medical` ON `contacts`.`contactid`=`contacts_medical`.`contactid` WHERE `contacts`.`contactid` = '$contactid'"));
				$updates = [];
				$history = '';
				foreach($values as $field => $value) {
					if($field != '' && $field != 'contactid') {
						$updates[] = "`$field`='$value'";
						if($value != $original[$field]) {
							if(isEncrypted($field) && $field != 'password') {
								$history .= "$field updated from ".decryptIt($original[$field])." to ".decryptIt($value)."<br />\n";
							} else {
								$history .= "$field updated from ".$original[$field]." to ".$value."<br />\n";
							}
						}
					}
				}
				$sql = "UPDATE `contacts` LEFT JOIN `contacts_cost` ON `contacts`.`contactid`=`contacts_cost`.`contactid` LEFT JOIN `contacts_dates` ON `contacts`.`contactid`=`contacts_dates`.`contactid` LEFT JOIN `contacts_description` ON `contacts`.`contactid`=`contacts_description`.`contactid` LEFT JOIN `contacts_medical` ON `contacts`.`contactid`=`contacts_medical`.`contactid` SET ".implode(',',$updates)." WHERE `contacts`.`contactid`='$contactid'";
				if(!mysqli_query($dbc, $sql)) {
					echo "Error on contactid $contactid: ".mysqli_error($dbc)."<br />\n";
					$error = true;
				} else {
					mysqli_query($dbc, "INSERT INTO `contacts_history` (`updated_by`, `description`, `contactid`) VALUES ('$staff', '".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."', '$contactid')");
					mysqli_query($dbc, "INSERT INTO `import_export_log` (table_name, type, description, date_time, contact) VALUES ('Contacts', 'Edit', '".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."', '$today_date', '".decryptIt($values['first_name']).' '.decryptIt($values['last_name'])." ($contactid)')");
				}
			}
		}
		fclose($handle);
		echo '<script type="text/javascript"> alert("'.($error ? 'Some rows had errors. Please review the notes and make any corrections to the data to upload the data.' : 'Successfully imported CSV file. Please check the dashboard to view your imported records.').'"); </script>';
	}
} ?>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="notice">Steps to Import Staff:<br><Br>
		<b>1.</b> Please download the Excel (CSV) file from the dashboard page.<br><br>
		<b>2.</b> Make your desired changes inside of the Excel file. To add new Staff, leave the first column (contactid) blank.<br>
		<span style='color:pink;'><img src='../img/warning.png' style='width:25px;'> NOTE</span>: Do not change/move/delete any of the column titles in the first row. Also, do not change the data in the first column (contactid) unless you are adding a new contact, or else the edits may not go through properly. <br><span style='color:lightgreen'><b>Hint:</b></span> press CTRL+F on your keyboard to find the fields you would like to populate; this will help you locate them faster.<br><br>
		<b>3.</b> After you are done editing the data, save your Excel (CSV) file, upload the CSV file below, and hit submit.<br><br>
		<b>4.</b> Please look for your edited Staff on the dashboard!<br><br>
		<input class="form-control" type="file" name="file" /><br />
	</div>
	<div class="pull-left">
		<span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href="staff.php?tab=active" class="btn brand-btn btn-lg">Back</a>
	</div>
	<div class="pull-right">
		<span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<button	type="submit" name="submit" value="import_contacts" class="btn brand-btn btn-lg">Submit</button>
	</div>
</form>