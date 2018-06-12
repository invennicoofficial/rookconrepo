<?php
include ('../database_connection.php');
require_once('../function.php');
error_reporting(0);

if($_GET['fill'] == 'contact_category') {
    $category = $_GET['category'];
	$contact_list = explode(',',$_GET['contacts']);
	echo "<option></option>";
	echo "<option value='NEW_CONTACT'>Add New Contact</option>";
	if($category != '') {
		$cat_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT contactid, name, first_name, last_name, category FROM contacts WHERE category = '$category'"),MYSQLI_ASSOC));
		echo '<option value=""></option>';
		foreach($cat_list as $contact) {
			$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contactid`, `name`, `first_name`, `last_name` FROM `contacts` WHERE `contactid`='$contact'"));
			echo "<option ".(in_array($contact,$contact_list) ? 'selected' : '')." value='$contact'>".(!empty($row['name']) ? decryptIt($row['name']) : decryptIt($row['first_name']).' '.decryptIt($row['last_name']))."</option>";
		}
	} else {
		$cat = '';
		$cat_list = [];
		$category_group = [];
		
		$query = mysqli_query($dbc,"SELECT `contactid`, `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE deleted=0 AND status>0 ORDER BY `category`");
		while($row = mysqli_fetch_array($query)) {
			if($cat != $row['category']) {
				$cat_list[$cat] = sort_contacts_array($category_group);
				$cat = $row['category'];
				$category_group = [];
			}
			$category_group[] = [ 'contactid' => $row['contactid'], 'last_name' => $row['last_name'], 'first_name' => $row['first_name'] ];
		}
		$cat_list[$cat] = sort_contacts_array($category_group);
		foreach($cat_list as $cat => $id_list) {
			echo '<optgroup label="'.($cat == 'Business' && get_software_name() == 'breakthebarrier' ? BUSINESS_CAT : ($cat == 'Business' && $rookconnect == 'highland' ? 'Customer' : $cat)).'">';
			foreach($id_list as $contact) {
				$row = mysqli_fetch_array(mysqli_query($dbc, "SELECT `contactid`, `name`, `first_name`, `last_name` FROM `contacts` WHERE `contactid`='$contact'"));
				echo "<option ".(in_array($contact,$contact_list) ? 'selected' : '')." value='$contact'>".(!empty($row['name']) ? decryptIt($row['name']) : decryptIt($row['first_name']).' '.decryptIt($row['last_name']))."</option>";
			}
		}
	}
}
?>