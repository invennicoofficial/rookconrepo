<?php include_once('../include.php');
include_once('../Ticket/field_list.php');
if(isset($_GET['ticketid']) && empty($ticketid)) {
	ob_clean();
	$ticketid = filter_var($_GET['ticketid'],FILTER_SANITIZE_STRING);
	$communication_type = filter_var($_GET['comm_type'],FILTER_SANITIZE_STRING);
	$communication_method = filter_var($_GET['comm_mode'],FILTER_SANITIZE_STRING);
}

if($communication_method == 'email') {
	$msgs = mysqli_query($dbc, "SELECT * FROM `email_communication` WHERE `ticketid`='$ticketid' AND `communication_type`='$communication_type' AND `deleted`=0");
}
$msg_count = mysqli_num_rows($msgs);
$msg_table = '';
if($ticketid > 0 && $msg_count > 0) {
	while($row = mysqli_fetch_array($msgs)) {
		$msg_table .= '<div class="note_block">';
		if($row['businessid'] > 0) {
			$msg_table .= BUSINESS_CAT.': <a href="../Contacts/contacts_inbox.php?edit='.$row['businessid'].'" onclick="overlayIFrameSlider(this.href+\'&fields=all_fields\',\'auto\',true,true); return false;">'.get_contact($dbc, $row['businessid'], 'name_company').'</a><br />';
		}
		$individuals = [];
		foreach(array_filter(explode(',',$row['contactid'])) as $row_contactid) {
			$individuals[] = '<a href="../Contacts/contacts_inbox.php?edit='.$row_contactid.'" onclick="overlayIFrameSlider(this.href+\'&fields=all_fields\',\'auto\',true,true); return false;">'.get_contact($dbc, $row_contactid, 'name_company').'</a>';
		}
		if(count($individuals) > 0) {
			$msg_table .= 'Individuals: '.implode(', ',$individuals).'<br />';
		}
		$msg_table .= profile_id($dbc, $row['created_by'],false);
		$msg_table .= '<div class="pull-right" style="width: calc(100% - 3.5em);">';
		$msg_table .= '<p><b>From: '.$row['from_name'].' &lt;'.$row['from_email'].'&gt;</b><br />';
		$msg_table .= '<b>To: '.implode('; ',array_filter(explode(',',$row['to_staff'].','.$row['to_contact'].','.$row['new_emailid']))).'</b><br />';
		$msg_table .= '<b>CC: '.implode('; ',array_filter(explode(',',$row['cc_staff'].','.$row['cc_contact']))).'</b>';
		$msg_table .= '<b>Subject: '.$row['subject'].'</b></p>';
		$msg_table .= html_entity_decode($row['email_body']);
		$msg_table .= "<em>Added by ".get_contact($dbc, $row['created_by'])." on ".$row['today_date']."</em>";
		$msg_table .= '</div><div class="clearfix"></div><hr></div>';
	}
}
$pdf_contents[] = [$communication_type.' Communication', $msg_table];
echo $msg_table;