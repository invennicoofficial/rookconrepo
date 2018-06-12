<?php if(isset($_POST['import_contacts'])) {
	if(!empty($_FILES['file']['tmp_name'])) {
		$i = 0;
		$error = false;
		$staff = get_contact($dbc, $_SESSION['contactid']);
		$file = $_FILES['file']['tmp_name'];
		$handle = fopen($file, "r");
		$headers = fgetcsv($handle, 0, ",");
		$c = 0;

		$tableid = trim($headers[0]);
		$tableid = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $tableid);
		if($tableid != 'contactid') {
			switch($tableid) {
				case 'individualsupportplanid':
					$tablename = 'individual_support_plan';
					$contactfield = 'support_contact';
					break;
				case 'medicationid':
					$tablename = 'medication';
					$contactfield = 'contactid';
					break;
				case 'bowel_movement_id':
					$tablename = 'bowel_movement';
					$contactfield = 'client';
					break;
				case 'seizure_record_id':
					$tablename = 'seizure_record';
					$contactfield = 'client';
					break;
				case 'daily_water_temp_id':
					$tablename = 'daily_water_temp';
					$contactfield = 'client';
					break;
				case 'blood_glucose_id':
					$tablename = 'blood_glucose';
					$contactfield = 'client';
					break;
				case 'activities_id':
					$tablename = 'social_story_activities';
					$contactfield = 'support_contact';
					break;
				case 'communication_id':
					$tablename = 'social_story_communication';
					$contactfield = 'support_contact';
					break;
				case 'protocol_id':
					$tablename = 'social_story_protocols';
					$contactfield = 'support_contact';
					break;
				case 'routine_id':
					$tablename = 'social_story_routines';
					$contactfield = 'support_contact';
					break;
				case 'keymethodologiesid':
					$tablename = 'key_methodologies';
					$contactfield = 'support_contact';
					break;
			}
			if (($key = array_search('contact_name', $headers)) !== false) {
				unset($headers[$key]);
			}
			while (($row = fgetcsv($handle, 0, ",")) !== false) {
				$values = [];
				foreach($headers as $i => $col) {
					$col = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col);
					$values[filter_var($col,FILTER_SANITIZE_STRING)] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', filter_var(htmlentities($row[$i])));
				}

				$curr_tableid = $values[$tableid];
				if(empty($curr_tableid)) {
					mysqli_query($dbc, "INSERT INTO `$tablename` (`$contactfield`) VALUES ('".$values[$contactfield]."')");
					$curr_tableid = mysqli_insert_id($dbc);
					$values[$tableid] = $curr_tableid;
				}
				$contactid = $values[$contactfield];

				if($curr_tableid > 0) {
					$original = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `$tablename` WHERE `$tableid` = '$curr_tableid'"));
					$updates = [];
					$history = '';
					foreach($values as $field => $value) {
						if($field == 'businessid_name') {
							$field = 'businessid';
							if($value != '') {
								$name = encryptIt($value);
								$business = $dbc->query("SELECT `contactid` FROM `contacts` WHERE `name`='$name' AND `deleted`=0 AND `status` > 0");
								if($business->num_rows > 0) {
									$value = $business->fetch_assoc()['contactid'];
								} else {
									$dbc->query("INSERT INTO `contacts` (`category`, `name`) VALUES ('".BUSINESS_CAT."','$name')");
									$value = $dbc->insert_id;
								}
							}
						} else if($field == 'siteid_name') {
							$field = 'siteid';
							if($value != '') {
								$site = $dbc->query("SELECT `contactid` FROM `contacts` WHERE (`site_name`='$value' OR `display_name`='$value') AND `deleted`=0 AND `status` > 0");
								if($site->num_rows > 0) {
									$value = $site->fetch_assoc()['contactid'];
								} else {
									$dbc->query("INSERT INTO `contacts` (`category`, `site_name`) VALUES ('".SITES_CAT."','$name')");
									$value = $dbc->insert_id;
								}
							}
						}
						if($field != '' && $field != $tableid && $field != $contactfield) {
							$updates[] = "`$field`='$value'";
							if($value != $original[$field]) {
								$history .= "$field updated from ".$original[$field]." to ".$value." in ".$tablename."<br />\n";
							}
						}
					}
					$sql = "UPDATE `$tablename` SET ".implode(',',$updates)." WHERE `$tableid` = '$curr_tableid'";
					if(!mysqli_query($dbc, $sql)) {
						echo "Error on contactid $contactid in $tablename ($tableid $curr_tableid): ".mysqli_error($dbc)."<br />\n";
						$error = true;
					} else {
						mysqli_query($dbc, "INSERT INTO `contacts_history` (`updated_by`, `description`, `contactid`) VALUES ('$staff', '".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."', '$contactid')");
						mysqli_query($dbc, "INSERT INTO `import_export_log` (table_name, type, description, date_time, contact) VALUES ('Contacts', 'Edit', '".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."', '$today_date', '".decryptIt($values['first_name']).' '.decryptIt($values['last_name'])." ($contactid)')");
					}
				}
			}
		} else {
			while (($row = fgetcsv($handle, 0, ",")) !== false) {
				$values = [];
				foreach($headers as $i => $col) {
					$col = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $col);
					$values[filter_var($col,FILTER_SANITIZE_STRING)] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', filter_var(htmlentities(isEncrypted($col) ? encryptIt($row[$i]) : $row[$i],FILTER_SANITIZE_STRING)));
				}
				
				$contactid = $values['contactid'];
				if(empty($contactid)) {
					mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`) VALUES ('".FOLDER_NAME."')");
					$contactid = mysqli_insert_id($dbc);
					$values['contactid'] = $contactid;
				}
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
							if($field == 'businessid_name') {
								$field = 'businessid';
								if($value != '') {
									$name = encryptIt($value);
									$business = $dbc->query("SELECT `contactid` FROM `contacts` WHERE `name`='$name' AND `deleted`=0 AND `status` > 0");
									if($business->num_rows > 0) {
										$value = $business->fetch_assoc()['contactid'];
									} else {
										$dbc->query("INSERT INTO `contacts` (`category`, `name`) VALUES ('".BUSINESS_CAT."','$name')");
										$value = $dbc->insert_id;
									}
								}
							} else if($field == 'siteid_name') {
								$field = 'siteid';
								if($value != '') {
									$site = $dbc->query("SELECT `contactid` FROM `contacts` WHERE (`site_name`='$value' OR `display_name`='$value') AND `deleted`=0 AND `status` > 0");
									if($site->num_rows > 0) {
										$value = $site->fetch_assoc()['contactid'];
									} else {
										$dbc->query("INSERT INTO `contacts` (`category`, `site_name`) VALUES ('".SITES_CAT."','$name')");
										$value = $dbc->insert_id;
									}
								}
							}
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
						echo "Error on contactid $contactid: ".mysqli_error($dbc)."<!--$sql--><br />\n";
						$error = true;
					} else {
						mysqli_query($dbc, "INSERT INTO `contacts_history` (`updated_by`, `description`, `contactid`) VALUES ('$staff', '".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."', '$contactid')");
						mysqli_query($dbc, "INSERT INTO `import_export_log` (table_name, type, description, date_time, contact) VALUES ('Contacts', 'Edit', '".filter_var(htmlentities($history),FILTER_SANITIZE_STRING)."', '$today_date', '".decryptIt($values['first_name']).' '.decryptIt($values['last_name'])." ($contactid)')");
					}
				}
			}
		}

		fclose($handle);
	    echo '<script type="text/javascript"> alert("'.($error ? 'Some rows had errors. Please review the notes and make any corrections to the data to upload the data.' : 'Successfully imported CSV file. Please check the dashboard to view your updated records.').'"); </script>';
	}
}
?>
<div class="standard-dashboard-body-title">
    <h3>Settings - Import Contacts:</h3>
</div>
<div class="standard-dashboard-body-content">
    <div class="dashboard-item dashboard-item2">
        <div class="form-horizontal block-group block-group-noborder">
            <form name="import" method="post" enctype="multipart/form-data">
                <div class="notice">Steps to Import Contacts in the <?= FOLDER_URL ?> tile:<br><Br>
                    <b>1.</b> Please download the Excel (CSV) file from the appropriate contact category from the dashboard page.<br>
                    <span style='color:pink;'><img src='../img/warning.png' style='width:25px;'> NOTE</span>: Do not change/move/delete any of the column titles in the first row, or else the edits may not go through properly. The software will determine what type of Import you are doing as long as the column titles are not changed.<br><span style='color:lightgreen'><b>Hint:</b></span> press CTRL+F on your keyboard to find the fields you would like to populate; this will help you locate them faster.<br><br>
                    <b>Importing Contact Information</b><br />
                    <b>2a.</b> Make your desired changes inside of the Excel file. To add new contacts, leave the first column (contactid) blank.<br><br>
                    <b>Importing Profile Additions</b><br />
                    <b>2b.</b> Make your desired changes inside of the Excel file. To add a new record for a contact, leave the first column (id) blank and copy the second column (contact id) into the new row. New contacts cannot be added from this type of Import. The column contact_name is only there for reference and will not update the actual Contact's name in this type of Import.<br><br>
                    <b>3.</b> After you are done editing the data, save your Excel (CSV) file, upload the CSV file below, and hit submit.<br><br>
                    <b>4.</b> Please look for your edited Contacts in the <?= FOLDER_URL ?> dashboard!<br><br>
                    <input class="form-control" type="file" name="file" /><br />
                </div>
                <div class="row double-padded">
                    <div class="col-sm-12">
                        <input class="btn brand-btn pull-right" type="submit" name="import_contacts" value="Submit" />
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div><!-- .dashboard-item -->
</div><!-- .standard-dashboard-body-content -->