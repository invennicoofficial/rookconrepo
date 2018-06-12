<?php include_once('../include.php');
checkAuthorised('sales_order');

$field_config = mysqli_query($dbc, "SELECT * FROM `field_config_so`");

if(mysqli_num_rows($field_config) == 0) {
	$fields = 'Sales Order Template,Sales Order Name,Primary Staff,Assign Staff,Staff Collaboration Groups,Business Contact,Classification,Next Action,Next Action Follow Up Date,Logo,Custom Designs,Discount,Delivery,Assembly,Payment Type,Deposit Paid,Comment,Ship Date,Due Date,Notes';
	$product_types = 'Inventory,Vendor,Services';
	$dashboard_fields = 'Business Contact,Classification,Next Action,Next Action Follow Up Date';

	mysqli_query($dbc, "INSERT INTO `field_config_so` (`fields`, `dashboard_fields`, `product_fields`) VALUES ('$fields', '$dashboard_fields', '$product_types')");
}