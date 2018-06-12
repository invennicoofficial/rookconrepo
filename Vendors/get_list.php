<?php
include ('../include.php');
checkAuthorised('vendors');
ob_clean();
error_reporting(0);

switch($_POST['target'])
{
	case 'businessid':
		$businesses = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE name != '' AND `category` = 'Business' AND deleted=0"), MYSQLI_ASSOC));
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