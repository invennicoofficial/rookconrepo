<?php
include ('../database_connection.php');
include ('../function.php');
include ('../global.php');

if($_GET['fill'] == 'prodselectCategory') {
    $name = $_GET['name'];

	$query = mysqli_query($dbc,"SELECT inventoryid, name FROM inventory WHERE category='$name' AND deleted=0");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['inventoryid']."'>".$row['name'].'</option>';
	}
}

if($_GET['fill'] == 'prodselectName') {
    $name = $_GET['name'];

	$query = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM inventory WHERE inventoryid = '$name'"));
	echo $query['sell_price'].'*#*'.$query['final_retail_price'].'*#*'.$query['unit_price'].'*#*'.$query['wholesale_price'].'*#*'.$query['commercial_price'].'*#*'.$query['client_price'].'*#*'.$query['preferred_price'].'*#*'.$query['admin_price'].'*#*'.$query['web_price'].'*#*'.$query['commission_price'];
}
if($_GET['fill'] == 'include_in_orders') {
    $id = $_GET['status'];
	$type = $_GET['type'];
	$function = $_GET['function'];
	$name = $_GET['name'];
	$value = $_GET['value'];
	if($type == 'inventoryorderlist') {
		
		$get_driver = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM order_lists WHERE order_id='$value'"));
		$inventoryidorder = $get_driver['inventoryid'];
		
		if($function == 'delete') { 
		
		$parts = explode(',', $inventoryidorder);
		$item = $id;
		while(($i = array_search($item, $parts)) !== false) {
			unset($parts[$i]);
		}

		$inventoryid = implode(',', $parts);
		$query_update_employee = "UPDATE `order_lists` SET inventoryid = '$inventoryid' WHERE order_id = '$value'";
		$result_update_employee = mysqli_query($dbc, $query_update_employee);
		} else {
			//Add
			
			if($inventoryidorder == '' || $inventoryidorder == NULL) {
			} else {
				$id = $inventoryidorder.','.$id;
			}
			$query_update_employee = "UPDATE `order_lists` SET inventoryid = '$id' WHERE order_id = '$value'";
			$result_update_employee = mysqli_query($dbc, $query_update_employee);
		}
		echo $query_update_employee;
	}
}

if($_GET['fill'] == 'show_impexp_vpl') {
	$value = $_GET['value'];
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='show_impexp_vpl'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$value' WHERE name='show_impexp_vpl'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('show_impexp_vpl', '$value')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}

if($_GET['fill'] == 'show_category_dropdown') {
	$value = $_GET['value'];
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='show_category_dropdown_vpl'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$value' WHERE name='show_category_dropdown_vpl'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('show_category_dropdown_vpl', '$value')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>