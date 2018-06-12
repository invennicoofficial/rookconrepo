<?php
include ('../include.php');
checkAuthorised('contacts_rolodex');
ob_clean();
error_reporting(0);

$category = 'Business';
if(isset($_POST['category'])) {
	$category = $_POST['category'];
}

switch($_POST['target'])
{
	case 'businessid':
		$businesses = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE name != '' AND `category` = '$category' AND deleted=0"), MYSQLI_ASSOC));
		foreach($businesses as $id) {
			echo "<option value='".$id."'>".get_client($dbc, $id)."</option>";
        }
		break;
	case 'categories':
		$results = array_filter(explode(',',str_replace('Staff','',get_config($dbc, FOLDER_NAME.'_tabs'))));
		echo json_encode($results);
		break;
}
?>