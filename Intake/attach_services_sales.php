<?php include_once('../include.php');
checkAuthorised('intake');

if($salesid > 0) {
	$sales = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `sales` WHERE `salesid` = '$salesid'"));
	$sales_services = explode(',',$sales['serviceid']);
	$intake_services = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id` = '$user_form_id' AND `type` = 'SERVICES' AND `deleted` = 0"),MYSQLI_ASSOC);
	foreach ($intake_services as $intake_service) {
	    $form_services = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id`='$user_form_id' AND `type`='OPTION' AND `name`='".$intake_service['name']."' AND '".$intake_service['type']."' IN ('SERVICES') AND `deleted`=0 ORDER BY `sort_order`"),MYSQLI_ASSOC);
	    foreach($form_services as $form_service) {
	        $service_added = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `user_form_data` WHERE `pdf_id` = '$pdf_id' AND `field_id` = '{$form_service['field_id']}' ORDER BY `data_Id` DESC"))['checked'];
	        if($service_added == 1) {
	            $sales_services[] = $form_service['source_conditions'];
	        }
	    }
	}
	$sales_services = implode(',',$sales_services);
	mysqli_query($dbc, "UPDATE `sales` SET `serviceid` = '$sales_services' WHERE `salesid` = '$salesid'");
}