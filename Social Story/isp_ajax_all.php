<?php
include ('../database_connection.php');
include ('../function.php');

if($_GET['fill'] == 'contact_category') {
    $category = $_GET['category'];
	$query = mysqli_query($dbc,"SELECT contactid, name, first_name, last_name FROM contacts WHERE category = '$category'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if(decryptIt($row['name']) != '') {
		    echo "<option value='".$row['contactid']."'>".decryptIt($row['name']).'</option>';
        } else {
		    echo "<option value='".$row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
        }
	}
}

?>