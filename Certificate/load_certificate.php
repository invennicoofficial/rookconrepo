<?php include_once('../include.php');
checkAuthorised('certificate');
ob_clean();
$id = filter_var($_POST['certificate'],FILTER_SANITIZE_STRING);
$row = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `certificate` WHERE `certificateid`='$id'"));
$value_config = get_field_config($dbc, 'certificate_dashboard'); ?>
<div class="dashboard-item override-dashboard-item">
	<?php if (strpos($value_config, ','."Staff".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6">'.($row['contactid'] > 0 ? '<label class="col-sm-4">Staff:</label><label class="col-sm-8">'.get_staff($dbc, $row['contactid']).'</label>' : '<label class="col-sm-4">'.PROJECT_NOUN.':</label><label class="col-sm-8">#'.$row['projectid'].' '.get_project($dbc, $row['projectid'],'name').'</label>').'</div>';
	}
	if (strpos($value_config, ','."Certificate Code".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Certificate Code:</label><label class="col-sm-8">'.$row['certificate_code'].'</label></div>';
	}
	if (strpos($value_config, ','."Certificate Type".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Certificate Type:</label><label class="col-sm-8">'.$row['certificate_type'].'</label></div>';
	}
	if (strpos($value_config, ','."Category".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Category:</label><label class="col-sm-8">'.$row['category'].'</label></div>';
	}
	if (strpos($value_config, ','."Title".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Title:</label><label class="col-sm-8">'.$row['title'].'</label></div>';
	}
	if (strpos($value_config, ','."Issue Date".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Issue Date:</label><label class="col-sm-8">'.$row['issue_date'].'</label></div>';
	}
	if (strpos($value_config, ','."Expiry Date".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Expiry Date:</label><label class="col-sm-8">'.$row['expiry_date'].'</label></div>';
	}
	if (strpos($value_config, ','."Reminder Date".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Reminder Date:</label><label class="col-sm-8">'.$row['reminder_date'].'</label></div>';
	}
	if (strpos($value_config, ','."Uploader".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Documents:</label><label class="col-sm-8">';
		$attached = "SELECT * FROM certificate_uploads WHERE certificateid='$id' AND type = 'Document' ORDER BY certuploadid DESC";
		$attached = mysqli_query($dbc, $attached);
		if(mysqli_num_rows($attached) > 0) {
			while($doc = mysqli_fetch_array($attached)) {
				echo '<a href="download/'.$doc['document_link'].'" target="_blank">'.$doc['document_link'].'</a>';
			}
		}
		echo '</label></div>';
	}
	if (strpos($value_config, ','."Link".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Links:</label><label class="col-sm-8">';
		$attached = "SELECT * FROM certificate_uploads WHERE certificateid='$id' AND type = 'Link' ORDER BY certuploadid DESC";
		$attached = mysqli_query($dbc, $attached);
		if(mysqli_num_rows($attached) > 0) {
			while($link = mysqli_fetch_array($attached)) {
				echo '<a href="download/'.$link['document_link'].'" target="_blank">'.$link['document_link'].'</a>';
			}
		}
		echo '</label></div>';
	}
	if (strpos($value_config, ','."Heading".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Heading:</label><label class="col-sm-8">'.$row['heading'].'</label></div>';
	}
	if (strpos($value_config, ','."Name".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Name:</label><label class="col-sm-8">'.$row['name'].'</label></div>';
	}
	if (strpos($value_config, ','."Fee".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Fee:</label><label class="col-sm-8">'.$row['fee'].'</label></div>';
	}
	if (strpos($value_config, ','."Cost".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Cost:</label><label class="col-sm-8">'.$row['cost'].'</label></div>';
	}
	if (strpos($value_config, ','."Description".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Description:</label><label class="col-sm-8">'.html_entity_decode($row['description']).'</label></div>';
	}
	if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Quote Description:</label><label class="col-sm-8">'.html_entity_decode($row['quote_description']).'</label></div>';
	}
	if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Invoice Description:</label><label class="col-sm-8">'.html_entity_decode($row['invoice_description']).'</label></div>';
	}
	if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">'.TICKET_NOUN.' Description:</label><label class="col-sm-8">'.html_entity_decode($row['ticket_description']).'</label></div>';
	}
	if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Final Retail Price:</label><label class="col-sm-8">'.$row['final_retail_price'].'</label></div>';
	}
	if (strpos($value_config, ','."Admin Price".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Admin Price:</label><label class="col-sm-8">'.$row['admin_price'].'</label></div>';
	}
	if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Wholesale Price:</label><label class="col-sm-8">'.$row['wholesale_price'].'</label></div>';
	}
	if (strpos($value_config, ','."Commercial Price".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Commercial Price:</label><label class="col-sm-8">'.$row['commercial_price'].'</label></div>';
	}
	if (strpos($value_config, ','."Client Price".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Client Price:</label><label class="col-sm-8">'.$row['client_price'].'</label></div>';
	}
	if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Minimum Billable:</label><label class="col-sm-8">'.$row['minimum_billable'].'</label></div>';
	}
	if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Estimated Hours:</label><label class="col-sm-8">'.$row['estimated_hours'].'</label></div>';
	}
	if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Actual Hours:</label><label class="col-sm-8">'.$row['actual_hours'].'</label></div>';
	}
	if (strpos($value_config, ','."MSRP".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">MSRP:</label><label class="col-sm-8">'.$row['msrp'].'</label></div>';
	}
	if (strpos($value_config, ','."Status".',') !== FALSE) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Status:</label><label class="col-sm-8">'.($row['issue_date'] > date('Y-m-d') || in_array($row['issue_date'],['','0000-00-00']) ? 'Pending Completion' : ($row['expiry_date'] < date('Y-m-d') ? 'Expired' : ($row['reminder_date'] < date('Y-m-d') ? 'Expiry Pending' : 'Complete'))).'</label></div>';
	}
	if (vuaed_visible_function($dbc, 'certificate') > 0) {
		echo '<div class="col-lg-4 col-sm-6"><label class="col-sm-4">Function:</label><label class="col-sm-8">'.'<a href=\'?edit='.$id.'\'>Edit</a> | <a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&certificateid='.$id.'\' onclick="return confirm(\'Are you sure?\')">Archive</a>'.'</label></div>';
	} ?><div class="clearfix"></div></div>