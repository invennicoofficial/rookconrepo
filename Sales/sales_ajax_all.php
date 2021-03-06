<?php
// include ('../database_connection.php');
// include ('../function.php');
// include ('../global.php');
include('../include.php');
ob_clean();
date_default_timezone_set('America/Denver');


if($_GET['fill'] == 'assigncontact') {
	$businessid = $_GET['businessid'];

	$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE businessid = '$businessid'"),MYSQLI_ASSOC));
	echo '<option value=""></option>';
    echo "<option value = 'NEW'>New Contact</option>";
	foreach($query as $id) {
		if ( get_contact($dbc, $id) != '-' ) {
            echo "<option value='".$id."'>".get_contact($dbc, $id).'</option>';
        }
	}

    echo '**#**';

	/*
    $query = mysqli_query($dbc,"SELECT contactid, office_phone, cell_phone FROM contacts WHERE businessid = '$businessid'");
	echo '<option value="">Please Select</option>';
    echo "<option value = 'New Number'>New Number</option>";
	while($row = mysqli_fetch_array($query)) {
        if($row['office_phone'] != '') {
		    echo "<option value='".$row['office_phone']."'>".$row['office_phone'].'</option>';
        }
        if($row['cell_phone'] != '') {
		    echo "<option value='".$row['cell_phone']."'>".$row['cell_phone'].'</option>';
        }
	}
    */

    echo '**#**';

	/*
    $query = mysqli_query($dbc,"SELECT contactid, email_address FROM contacts WHERE businessid = '$businessid'");
	echo '<option value="">Please Select</option>';
    echo "<option value = 'New Email'>New Email</option>";
	while($row = mysqli_fetch_array($query)) {
        if($row['email_address'] != '') {
		    echo "<option value='".$row['email_address']."'>".$row['email_address'].'</option>';
        }
	}
    */

    echo '**#**';

    $get_est =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT estimateid FROM estimate WHERE	deleted=0 AND (status='Saved' OR status='Submitted') AND businessid='$businessid'"));
    if($get_est['estimateid'] == '') {
        echo 'No';
    } else {
        echo $get_est['estimateid'];
    }

    echo '**#**';

    $get_quote =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT estimateid FROM estimate WHERE	deleted=0 AND (status!='Saved' AND status!='Submitted' AND status!='Approved Quote') AND businessid='$businessid'"));
    if($get_quote['estimateid'] == '') {
        echo 'No';
    } else {
        echo $get_quote['estimateid'];
    }

}

if($_GET['fill'] == 'get_lead_source_contacts') {
	$businessid = $_GET['businessid'];
	$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `businessid`='$businessid' AND `deleted`=0 AND `status`>0"),MYSQLI_ASSOC));
	echo '<option value=""></option>';
	foreach($query as $id) {
		if ( get_contact($dbc, $id) != '-' ) {
            echo '<option value="'.$id.'">'.get_contact($dbc, $id).'</option>';
        }
	}
}

if($_GET['fill'] == 'assignphoneemail') {
	$contactid = $_GET['contactid'];

	$query = mysqli_query($dbc,"SELECT `c1`.`businessid`, (SELECT `c2`.`office_phone` FROM `contacts` `c2` WHERE `c1`.`businessid`=`c2`.`contactid`) `office_phone` FROM `contacts` `c1` WHERE `c1`.`contactid`='$contactid'");
	echo '<option value=""></option>';
    echo "<option value = 'NEW'>New Number</option>";
	while($row = mysqli_fetch_array($query)) {
        if($row['office_phone'] != '') {
		    echo "<option value='".$row['office_phone']."'>".decryptIt($row['office_phone']).'</option>';
        }
	}

    echo '**#**';

	$query = mysqli_query($dbc,"SELECT `c1`.`businessid`, (SELECT `c2`.`email_address` FROM `contacts` `c2` WHERE `c1`.`businessid`=`c2`.`contactid`) `email_address` FROM `contacts` `c1` WHERE `c1`.`contactid`='$contactid'");
	echo '<option value=""></option>';
    echo "<option value = 'New Email'>New Email</option>";
	while($row = mysqli_fetch_array($query)) {
        if(decryptIt($row['email_address']) != '') {
		    echo "<option value='".decryptIt($row['email_address'])."'>".decryptIt($row['email_address']).'</option>';
        }
	}
}

//Services
if($_GET['fill'] == 's_service_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT `serviceid`, `heading` FROM `services` WHERE `service_type`='$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['serviceid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 's_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT DISTINCT(`service_type`) FROM `services` WHERE `category`='$value' AND `deleted`=0 ORDER BY `service_type`");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['service_type']."'>".$row['service_type'].'</option>';
	}
}

if($_GET['fill'] == 'material_heading') {
    $marketing_materialid = $_GET['maketingid'];
	$query = mysqli_query($dbc,"SELECT document_link FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid' AND type = 'Document'");
	while($row = mysqli_fetch_array($query)) {
		echo '<a href="'.WEBSITE_URL.'/Marketing Material/download/'.$row['document_link'].'" target="_blank">'.$row['document_link'].'</a>';
	}
}


//Product
if($_GET['fill'] == 'p_product_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT distinct(category) FROM products WHERE product_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'p_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT productid, heading FROM products WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['productid']."'>".$row['heading'].'</option>';
	}
}

//MArketing Material
if($_GET['fill'] == 'm_material_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT distinct(category) FROM marketing_material WHERE marketing_material_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'm_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT marketing_materialid, heading FROM marketing_material WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['marketing_materialid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'sales_status') {
	$salesid = $_GET['salesid'];
	$status = $_GET['status'];
	$query_update_project = "UPDATE `sales` SET  status='$status' WHERE `salesid` = '$salesid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'sales_action') {
	$salesid = $_GET['salesid'];
	$action = $_GET['action'];
	$query_update_project = "UPDATE `sales` SET  next_action='$action' WHERE `salesid` = '$salesid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
}
if($_GET['fill'] == 'sales_reminder') {
	$salesid = $_GET['salesid'];
	$reminder = $_GET['reminder'];
	$query_update_project = "UPDATE `sales` SET  new_reminder='$reminder' WHERE `salesid` = '$salesid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);
	$sales = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `sales` WHERE `salesid`='$salesid'"));

	if($reminder != '' && $reminder != '0000-00-00') {
		$body = filter_var(htmlentities('This is a reminder about a sales lead that needs to be followed up with.<br />
			The scheduled next action is: '.$sales['next_action'].'<br />
			Click <a href="'.WEBSITE_URL.'/Sales/add_sales.php?salesid='.$salesid.'">here</a> to review the lead.'), FILTER_SANITIZE_STRING);
		$verify = "sales#*#next_action#*#salesid#*#".$salesid."#*#".$sales['next_action'];
        mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `contactid` = '$primary_staff' AND `src_table` = 'sales' AND `src_tableid` = '$salesid'");
		$reminder_result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_type`, `subject`, `body`, `src_table`, `src_tableid`)
			VALUES ('$primary_staff', '$new_reminder', 'Sales Reminder', 'Reminder of Sales Lead', '$body', 'sales', '$salesid')");
	}
}

if ( $_GET['fill']=='changeLeadStatus' ) {
    $salesid = $_GET['salesid'];
    $status  = $_GET['status'];
    $result_update = mysqli_query ( $dbc, "UPDATE `sales` SET `status`='{$status}' WHERE `salesid`='{$salesid}'" );

    //Convert Sales Lead to a Customer
    $status_won = get_config($dbc, 'lead_status_won');

    if ( $status_won==$status ) {
        $lead_convert_to = get_config($dbc, 'lead_convert_to');
        if ( empty($lead_convert_to) ) {
            $lead_convert_to = 'Customers';
        }
        $contactid = get_field_value('contactid', 'sales', 'salesid', $salesid);
        foreach ( array_filter(explode(',',$contactid)) as $cid ) {
            mysqli_query($dbc, "UPDATE contacts SET category='$lead_convert_to' WHERE contactid='$cid'");
        }
    }
}

if ( $_GET['fill']=='changeCustCat' ) {
    $salesid = $_GET['salesid'];
    $html = '';

    //Convert Sales Lead to a Customer
    $lead_convert_to = get_config($dbc, 'lead_convert_to');
    if ( empty($lead_convert_to) ) {
        $lead_convert_to = 'Customers';
    }
    $contactid = get_field_value('contactid', 'sales', 'salesid', $salesid);

    foreach ( array_filter(explode(',',$contactid)) as $cid ) {
        //mysqli_query($dbc, "UPDATE contacts SET category='$lead_convert_to' WHERE contactid='$cid'");
    }
}

if ( $_GET['fill']=='changeLeadNextAction' ) {
    $salesid       = $_GET['salesid'];
    $nextaction    = $_GET['nextaction'];
    $result_update = mysqli_query ( $dbc, "UPDATE `sales` SET `next_action`='{$nextaction}' WHERE `salesid`='{$salesid}'" );
}

if ( $_GET['fill']=='changeLeadFollowUpDate' ) {
    $salesid       = $_GET['salesid'];
    $followupdate  = $_GET['followupdate'];
    $result_update = mysqli_query ( $dbc, "UPDATE `sales` SET `new_reminder`='{$followupdate}' WHERE `salesid`='{$salesid}'" );
}

if ( $_GET['fill']=='archive_sales_lead' ) {
    $date_of_archival = date('Y-m-d');
    $salesid       = $_GET['salesid'];
    $result_update = mysqli_query ( $dbc, "UPDATE `sales` SET `deleted`=1, `date_of_archival` = '$date_of_archival' WHERE `salesid`='{$salesid}'" );
}

if ( $_GET['fill']=='saveNote' ) {
    $salesid = $_GET['salesid'];
    $note    = filter_var(htmlentities($_GET['note']), FILTER_SANITIZE_STRING);
    mysqli_query ( $dbc, "INSERT INTO `sales_notes` (`salesid`, `comment`) VALUES('$salesid', '$note')" );
}

if ( $_GET['fill']=='updateSalesMilestone') {
	$id = $_POST['id'];
	$id_field = $_POST['id_field'];
	$table = $_POST['table'];
	$milestone = $_POST['milestone'];
	mysqli_query($dbc, "UPDATE `$table` SET `sales_milestone` = '$milestone' WHERE `$id_field` = '$id'");
}

if ( $_GET['action']=='milestone_edit') {
	if($_POST['id'] > 0) {
		$id = $_POST['id'];
		$field = filter_var($_POST['field'],FILTER_SANITIZE_STRING);
		$value = filter_var($_POST['value'],FILTER_SANITIZE_STRING);
		$table = filter_var($_POST['table'],FILTER_SANITIZE_STRING);
		$dbc->query("UPDATE `$table` SET `$field`='$value' WHERE `id`='$id'");
	} else if($_POST['field'] == 'sort') {
		$salesid = filter_var($_POST['salesid'],FILTER_SANITIZE_STRING);
		$field = filter_var($_POST['field'],FILTER_SANITIZE_STRING);
		$value = filter_var($_POST['value'],FILTER_SANITIZE_STRING);
		$table = filter_var($_POST['table'],FILTER_SANITIZE_STRING);
		$dbc->query("INSERT INTO `$table` (`$field`, `salesid`) VALUES ('$value','$salesid')");
		$id = $dbc->insert_id;
		echo $id;
		$dbc->query("UPDATE `$table` SET `milestone`='milestone.$id', `label`='New Milestone' WHERE `id`='$id'");
	}
}

//Intake quick action
if ( $_GET['fill']=='intakeFlagItem' ) {
	$intakeid = $_POST['id'];

	$colours = explode(',', get_config($dbc, "ticket_colour_flags"));
	$labels = explode('#*#', get_config($dbc, "ticket_colour_flag_names"));

	$value = mysqli_fetch_array(mysqli_query($dbc, "SELECT `flag_colour` FROM `intake` WHERE `intakeid` = '$intakeid'"))['flag_colour'];

	$colour_key = array_search($value, $colours);
	$new_colour = ($colour_key === FALSE ? $colours[0] : ($colour_key + 1 < count($colours) ? $colours[$colour_key + 1] : ''));
	$label = ($colour_key === FALSE ? $labels[0] : ($colour_key + 1 < count($colours) ? $labels[$colour_key + 1] : ''));
	echo $new_colour;
	mysqli_query($dbc, "UPDATE `intake` SET `flag_colour`='$new_colour' WHERE `intakeid`='$intakeid'");
}
if ( $_GET['fill']=='intakeEmail' ) {
	$salesid = $_POST['salesid'];
	$intakeid = $_POST['id'];

	$sender = get_email($dbc, $_SESSION['contactid']);
	$result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `intake` WHERE `intakeid`='$intakeid'"));
	$milestone = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_path_custom_milestones` WHERE `salesid` = '$salesid' AND `milestone` = '".$result['sales_milestone']."'"))['label'];
	$subject = "A reminder about Intake #".$intakeid." in Sales #".$salesid."  $milestone";
	$id = $result['projectid'];
	foreach($_POST['value'] as $user) {
		$user = get_email($dbc,$user);
		$body = "This is a reminder about Intake #".$intakeid." in Sales #".$salesid." $milestone<br />\n<br />
			<a href='".WEBSITE_URL."/Sales/sales.php?p=preview&id=$salesid'>Click here</a> to see the Sales.<br />\n";
		send_email($sender, $user, '', '', $subject, $body, '');
	}
}
if ( $_GET['fill']=='intakeReminder') {
	$salesid = $_POST['salesid'];
	$intakeid = $_POST['id'];
	$value = $_POST['value'];

	$sender = get_email($dbc, $_SESSION['contactid']);
	$result = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `intake` WHERE `intakeid` = '$intakeid'"));
	$id = $result['intakeid'];
	$milestone = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_path_custom_milestones` WHERE `salesid` = '$salesid' AND `milestone` = '".$result['sales_milestone']."'"))['label'];
	$subject = "A reminder about Intake #".$intakeid." in Sales #".$salesid."  $milestone";
	foreach($_POST['users'] as $i => $user) {
		$user = filter_var($user,FILTER_SANITIZE_STRING);
		$contacts = mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='$user'");
		$body = filter_var(htmlentities("This is a reminder about Intake #".$intakeid." in Sales #".$salesid." $milestone<br />\n<br />
			<a href='".WEBSITE_URL."/Sales/sales.php?p=preview&id=$salesid'>Click here</a> to see the Sales.<br />\n"), FILTER_SANITIZE_STRING);
		mysqli_query($dbc, "UPDATE `reminders` SET `done` = 1 WHERE `contactid` = '$user' AND `src_table` = 'sales' AND `src_tableid` = '$salesid' AND `src_table` != '' AND `src_table` IS NOT NULL");
		$result = mysqli_query($dbc, "INSERT INTO `reminders` (`contactid`, `reminder_date`, `reminder_time`, `reminder_type`, `subject`, `body`, `sender`, `src_table`, `src_tableid`)
			VALUES ('$user', '$value', '08:00:00', 'QUICK', '$subject', '$body', '$sender', 'sales', '$salesid')");
	}
}
if ( $_GET['fill']=='intakeArchive' ) {
	$intakeid = $_POST['id'];
        $date_of_archival = date('Y-m-d');
	echo "UPDATE `intake` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `intakeid` = '$intakeid'";
	mysqli_query($dbc, "UPDATE `intake` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `intakeid` = '$intakeid'");
}
?>