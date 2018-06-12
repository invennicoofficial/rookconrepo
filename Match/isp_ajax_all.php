<?php
include_once('../include.php');
ob_clean();

if($_GET['fill'] == 'contact_category') {
    $category = filter_var($_GET['category'],FILTER_SANITIZE_STRING);
	$query = sort_contacts_query(mysqli_query($dbc,"SELECT contactid, name, first_name, last_name FROM contacts WHERE category = '$category' AND `deleted`=0 AND `status`=1"));
	echo '<option value=""></option>';
	foreach($query as $row) {
		echo "<option value='".$row['contactid']."'>".$row['full_name'].'</option>';
	}
}

?>