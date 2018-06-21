<?php
include ('../database_connection.php');
include ('../global.php');
include ('../function.php');
include ('../phpmailer.php');

if($_GET['fill'] == 'checklist') {
    $id = $_GET['checklistid'];
    $checked = $_GET['checked'];
    $updated_by = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
    $updated_date = date('Y-m-d');

	$query_update_project = "UPDATE `checklist_name` SET  `checked`='$checked', `updated_date`='$updated_date', `time_checked`=CURRENT_TIMESTAMP, `updated_by`='$updated_by'  WHERE `checklistnameid` = '$id'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

    $checklistid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` WHERE `checklistnameid`='$id'"))['checklistid'];

    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];
    $checklist_name = $get_subtab['checklist_name'];
    $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Updated Checklist Items in <b>'.$checklist_name.'</b> on '.date('Y-m-d');
    $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
}

if($_GET['fill'] == 'checklist_priority') {
    $checklistnameid = $_GET['checklistnameid'];
    $after_checklistnameid = $_GET['after_checklistnameid'];
    $checklistnameid_pri = get_checklist_name($dbc, $after_checklistnameid, 'priority')+1;
    $checklistid = get_checklist_name($dbc, $checklistnameid, 'checklistid');

	$query_update_project = "UPDATE `checklist_name` SET  `priority`=`priority`+1 WHERE `priority` >= '$checklistnameid_pri' AND `checklistid` = '$checklistid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

	$query_update_project = "UPDATE `checklist_name` SET  `priority`='$checklistnameid_pri' WHERE `checklistnameid` = '$checklistnameid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

}

if($_GET['fill'] == 'projectname') {
	$businessid = $_GET['businessid'];

    $query = mysqli_query($dbc,"SELECT projectid, project_name FROM project WHERE businessid = '$businessid' and deleted=0");
    echo '<option value="">Please Select</option>';
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='".$row['projectid']."'>".$row['project_name']."</option>\n";
    }
}

if($_GET['fill'] == 'add_checklist') {

    $checklistid = $_GET['checklistid'];
    $checklist = $_GET['checklist'];

    $contactid = $_SESSION['contactid'];

	$checklist = str_replace("FFMEND","&",$checklist);
    $checklist = str_replace("FFMSPACE"," ",$checklist);
    $checklist = str_replace("FFMHASH","#",$checklist);

    $checklist = filter_var($checklist,FILTER_SANITIZE_STRING);
    $checklist_name = get_checklist($dbc, $checklistid, 'checklist_name');

    $get_staff =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT MAX(priority) AS total_checklistnameid FROM	checklist_name WHERE checklistid='$checklistid'"));
    $max_checklist = $get_staff['total_checklistnameid']+1;

    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];

    if($checklist != '') {
        $query_insert_log = "INSERT INTO `checklist_name` (`checklistid`, `checklist`, `priority`) VALUES ('$checklistid', '$checklist', '$max_checklist')";
        $result_insert_log = mysqli_query($dbc, $query_insert_log);

        $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Added Checklist Item in <b>'.$checklist_name.'</b> on '.date('Y-m-d');
        $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
        $result_insert_ca = mysqli_query($dbc, $query_insert_ca);

    }
}

if($_GET['fill'] == 'delete_checklist') {
	$id = $_GET['checklistid'];
	$query = "UPDATE `checklist_name` SET `deleted`=1 WHERE `checklistnameid`=$id";
	$result = mysqli_query($dbc,$query);

    $checklistid = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` WHERE `checklistnameid`='$id'"))['checklistid'];

    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];
    $checklist_name = $get_subtab['checklist_name'];
    $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Deleted Checklist Item in <b>'.$checklist_name.'</b> on '.date('Y-m-d');
    $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
}
if($_GET['fill'] == 'checklistreply') {
	$id = $_POST['id'];
	$reply = filter_var(htmlentities('<p>'.$_POST['reply'].'</p>'),FILTER_SANITIZE_STRING);
	$query = "UPDATE `checklist_name` SET `checklist`=CONCAT(`checklist`,'$reply') WHERE `checklistnameid`='$id'";
	$result = mysqli_query($dbc,$query);
    
    $item_query = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` WHERE `checklistnameid`='$id'"));
    $checklistid = $item_query['checklistid'];
    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];
    $checklist_name = $get_subtab['checklist_name'];
    $item_name = explode('&lt;p&gt;', $item_query['checklist']);

    $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Replied to Checklist Item <b>'.$item_name[0].'</b> on '.date('Y-m-d');
    $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
}
if($_GET['fill'] == 'checklistalert') {
	$item_id = $_POST['id'];
	$type = $_POST['type'];
	$user = $_POST['user'];
	if($type == 'checklist') {
		$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistnameid='$item_id'"));
		$id = $result['checklistid'];
	}
	else {
		$id = $item_id;
	}
	$link = WEBSITE_URL."/Checklist/my_checklist.php?checklistid=".$id;
	$text = "Checklist";
	$date = date('Y/m/d');
    foreach ((array)$user as $singleuser) {
        $sql = mysqli_query($dbc, "INSERT INTO `alerts` (`alert_date`, `alert_link`, `alert_text`, `alert_user`) VALUES ('$date', '$link', '$text', '$singleuser')");
    }
    
    $item_query = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` WHERE `checklistnameid`='$item_id'"));
    $checklistid = $item_query['checklistid'];
    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];
    $checklist_name = $get_subtab['checklist_name'];
    $item_name = explode('&lt;p&gt;', $item_query['checklist']);

    $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Sent Alert in Checklist Item <b>'.$item_name[0].'</b> on '.date('Y-m-d');
    $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
}
if($_GET['fill'] == 'checklistemail') {
	$item_id = $_POST['id'];
	$type = $_POST['type'];
	$user = $_POST['user'];
	$subject = '';
	$title = '';
	if($type == 'checklist') {
		$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistnameid='$item_id'"));
		$id = $result['checklistid'];
		$title = explode('<p>',html_entity_decode($result['checklist']))[0];
		$subject = "A reminder about the $title on the checklist";
	}
	else {
		$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM checklist WHERE checklistid = '$item_id'"));
		$id = $item_id;
		$title = $result['checklist_name'];
		$subject = "A reminder about the $title checklist";
	}
    foreach ((array)$user as $singleuser) {
        $contacts = mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='$singleuser'");
        while($row = mysqli_fetch_array($contacts)) {
            $email_address = get_email($dbc, $row['contactid']);
            if(trim($email_address) != '') {
                $body = "Hi ".decryptIt($row['first_name'])."<br />\n<br />
                    This is a reminder about the $title on the checklist.<br />\n<br />
                    <a href='".WEBSITE_URL."/Checklist/checklist.php?checklistid=$id'>Click here</a> to see the checklist.";
                send_email('', $email_address, '', '', $subject, $body, '');
            }
        }
    }
    
    $item_query = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` WHERE `checklistnameid`='$item_id'"));
    $checklistid = $item_query['checklistid'];
    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];
    $checklist_name = $get_subtab['checklist_name'];
    $item_name = explode('&lt;p&gt;', $item_query['checklist']);

    $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Sent Email in Checklist Item <b>'.$item_name[0].'</b> on '.date('Y-m-d');
    $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
}
if($_GET['fill'] == 'checklistreminder') {
	$item_id = $_POST['id'];
	$sender = get_email($dbc, $_SESSION['contactid']);
	$date = $_POST['schedule'];
	$type = $_POST['type'];
	$to = $_POST['user'];
	$subject = '';
	$title = '';
	if($type == 'checklist') {
		$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM checklist_name WHERE checklistnameid='$item_id'"));
		$id = $result['checklistid'];
		$title = explode('<p>',html_entity_decode($result['checklist']))[0];
		$subject = "A reminder about the $title on the checklist";
		$body = htmlentities("This is a reminder about the $title on the checklist.<br />\n<br />
			<a href=\"".WEBSITE_URL."/Checklist/checklist.php?checklistid=$id\">Click here</a> to see the checklist.");
	}
	else {
		$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM checklist WHERE checklistid = '$item_id'"));
		$id = $item_id;
		$title = $result['checklist_name'];
		$subject = "A reminder about the $title checklist";
		$body = htmlentities("This is a reminder about the $title checklist.<br />\n<br />
			<a href=\"".WEBSITE_URL."/Checklist/checklist.php?checklistid=$id\">Click here</a> to see the checklist.");
	}
    foreach ((array)$to as $singleto) {
        $result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_time`, `reminder_type`, `subject`, `body`, `sender`)
        VALUES ('$singleto', '$date', '08:00:00', 'QUICK', '$subject', '$body', '$sender')");
    }
    
    $item_query = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` WHERE `checklistnameid`='$item_id'"));
    $checklistid = $item_query['checklistid'];
    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];
    $checklist_name = $get_subtab['checklist_name'];
    $item_name = explode('&lt;p&gt;', $item_query['checklist']);

    $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Scheduled Reminder in Checklist Item <b>'.$item_name[0].'</b> on '.date('Y-m-d');
    $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
	
}
if($_GET['fill'] == 'checklistflag') {
	$item_id = $_POST['id'];
	$type = $_POST['type'];
	if($type == 'checklist') {
		$colour = mysqli_fetch_array(mysqli_query($dbc, "SELECT `flag_colour` FROM checklist_name WHERE checklistnameid = '$item_id'"))['flag_colour'];
		$colour_list = explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT `flag_colours` FROM `field_config_checklist`"))['flag_colours']);
		$colour_key = array_search($colour, $colour_list);
		$new_colour = ($colour_key === FALSE ? $colour_list[0] : ($colour_key + 1 < count($colour_list) ? $colour_list[$colour_key + 1] : ''));
		$result = mysqli_query($dbc, "UPDATE `checklist_name` SET `flag_colour`='$new_colour' WHERE `checklistnameid` = '$item_id'");
		echo $new_colour;
	}
	else {
		$colour = mysqli_fetch_array(mysqli_query($dbc, "SELECT `flag_colour` FROM checklist WHERE checklistid = '$item_id'"))['flag_colour'];
		$colour_list = explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT `flag_colours` FROM `field_config_checklist`"))['flag_colours']);
		$colour_key = array_search($colour, $colour_list);
		$new_colour = ($colour_key === FALSE ? $colour_list[0] : ($colour_key + 1 < count($colour_list) ? $colour_list[$colour_key + 1] : ''));
		$result = mysqli_query($dbc, "UPDATE `checklist` SET `flag_colour`='$new_colour' WHERE `checklistid` = '$item_id'");
		echo $new_colour;
	}
}
if($_GET['fill'] == 'checklist_upload') {
	$id = $_GET['id'];
	$type = $_GET['type'];
	$filename = htmlspecialchars($_FILES['file']['name'], ENT_QUOTES);
	$file = $_FILES['file']['tmp_name'];
    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
	move_uploaded_file($file, "download/".$filename);
	if($type == 'checklist') {
		$query_insert = "INSERT INTO `checklist_name_document` (`checklistnameid`, `type`, `document`, `created_date`, `created_by`) VALUES ('$id', 'Support Document', '$filename', '".date('Y/m/d')."', '".$_SESSION['contactid']."')";
		$result_insert = mysqli_query($dbc, $query_insert);
	}
	else if($type == 'checklist_board') {
		$query_insert = "INSERT INTO `checklist_document` (`checklistid`, `type`, `document`, `created_date`, `created_by`) VALUES ('$id', 'Support Document', '$filename', '".date('Y/m/d')."', '".$_SESSION['contactid']."')";
		$result_insert = mysqli_query($dbc, $query_insert);

	}
    
    $item_query = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist_name` WHERE `checklistnameid`='$id'"));
    $checklistid = $item_query['checklistid'];
    $get_subtab = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `checklist` WHERE `checklistid` = '$checklistid'"));
    $subtabid = $get_subtab['subtabid'];
    $checklist_name = $get_subtab['checklist_name'];
    $item_name = explode('&lt;p&gt;', $item_query['checklist']);

    $report = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Added File in Checklist Item <b>'.$item_name[0].'</b> on '.date('Y-m-d');
    $query_insert_ca = "INSERT INTO `checklist_report` (`report`, `user`, `date`, `checklist_name`, `subtab_name`, `checklist_type`, `checklistid`, `subtabid`) VALUES ('$report', '".decryptIt($_SESSION['first_name'])." ".decryptIt($_SESSION['last_name'])."', '".date('Y-m-d')."', '$checklist_name', '', '', '$checklistid', '$subtabid')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);
}
if($_GET['fill'] == 'checklist_quick_time') {
	$checklistid = $_POST['id'];
	$time = $_POST['time'];
	$query_time = "INSERT INTO `checklist_name_time` (`checklist_id`, `work_time`, `contactid`, `timer_date`) VALUES ('$checklistid', '$time', '".$_SESSION['contactid']."', '".date('Y-m-d')."')";
	$result = mysqli_query($dbc, $query_time);
	insert_day_overview($dbc, $_SESSION['contactid'], 'Checklist', date('Y-m-d'), '', "Updated Checklist Item #$checklistid - Added Time : $time");
}
if($_GET['fill'] == 'subtab_change') {
    $subtabid = $_GET['subtabid'];
    $query_subtab = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `checklist_subtab` WHERE `subtabid` = '$subtabid'"));
    $subtab_shared = $query_subtab['shared'];

    echo "<option value=''></option>";
    echo "<option value='ALL'>Share with Everyone</option>";
    
    $query_retrieve_subtabs = mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name`, `category`, `email_address` FROM `contacts` WHERE `category` = 'Staff' AND `deleted` = 0 ORDER BY `category`");
    // while($row = mysqli_fetch_array($query_retrieve_subtabs)) {
    //     echo "<option ".((strpos($subtab_shared, ','.$row['contactid'].',') !== false) ? 'selected' : '')." value='".$row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name'])."</option>";
    // }
    $result_retrieve_subtabs = sort_contacts_array(mysqli_fetch_all($query_retrieve_subtabs, MYSQLI_ASSOC));

    foreach($result_retrieve_subtabs as $row) {
        echo "<option ".((strpos($subtab_shared, ','.$row.',') !== false) ? 'selected' : '')." value='".$row."'>".get_contact($dbc, $row)."</option>";
    }
}
?>