<?php include_once('../include.php');
checkAuthorised('intake');

$intake_services = [];
if($intakeid > 0) {
	$intake = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `intake` WHERE `intakeid` = '$intakeid'"));
	$intakeform = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `intake_forms` WHERE `intakeformid` = '".$intake['intakeformid']."'"));
	$form_fields = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id` = '".$intakeform['user_form_id']."' AND `type` = 'SERVICES' AND `deleted` = 0 AND '".$intakeform['user_form_id']."' > 0"),MYSQLI_ASSOC);

	foreach($form_fields as $form_field) {
		$form_services = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `user_form_fields` WHERE `form_id` = '".$intakeform['user_form_id']."' AND `type` = 'OPTION' AND `name` = '".$form_field['name']."' AND `deleted` = 0"),MYSQLI_ASSOC);
		foreach($form_services as $form_service) {
			$form_data = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `user_form_data` WHERE `pdf_id` = '".$intake['pdf_id']."' AND `field_id` = '".$form_service['field_id']."'"));
			if($form_data['checked'] == 1) {
				$intake_services[$form_service['source_conditions']] = $form_data['value'];
			}
		}
	}
}