<?php include_once('../include.php');
checkAuthorised('staff');
if($position_id > 0 && isset($_POST['ratecardid'])) {
	$rate_cards_keep = [];
	foreach($_POST['ratecardid'] as $i => $ratecardid) {
		$row_i = filter_var($_POST['ratecard_row_i'][$i]);

		$start_date = filter_var($_POST['start_date'][$i],FILTER_SANITIZE_STRING);
		$end_date = filter_var($_POST['end_date'][$i],FILTER_SANITIZE_STRING);
		$alert_date = filter_var($_POST['alert_date'][$i],FILTER_SANITIZE_STRING);
		$alert_staff = filter_var(implode(',',$_POST['alert_staff_'.$row_i]),FILTER_SANITIZE_STRING);
		$annual = filter_var($_POST['annual'][$i],FILTER_SANITIZE_STRING);
		$monthly = filter_var($_POST['monthly'][$i],FILTER_SANITIZE_STRING);
		$semi_month = filter_var($_POST['semi_month'][$i],FILTER_SANITIZE_STRING);
		$weekly = filter_var($_POST['weekly'][$i],FILTER_SANITIZE_STRING);
		$daily = filter_var($_POST['daily'][$i],FILTER_SANITIZE_STRING);
		$hourly = filter_var($_POST['hourly'][$i],FILTER_SANITIZE_STRING);
		$hourly_work = filter_var($_POST['hourly_work'][$i],FILTER_SANITIZE_STRING);
		$hourly_travel = filter_var($_POST['hourly_travel'][$i],FILTER_SANITIZE_STRING);
		$field_day_actual = filter_var($_POST['field_day_actual'][$i],FILTER_SANITIZE_STRING);
		$field_day_bill = filter_var($_POST['field_day_bill'][$i],FILTER_SANITIZE_STRING);
		$cost = filter_var($_POST['cost'][$i],FILTER_SANITIZE_STRING);
		$price_admin = filter_var($_POST['price_admin'][$i],FILTER_SANITIZE_STRING);
		$price_wholesale = filter_var($_POST['price_wholesale'][$i],FILTER_SANITIZE_STRING);
		$price_commercial = filter_var($_POST['price_commercial'][$i],FILTER_SANITIZE_STRING);
		$price_client = filter_var($_POST['price_client'][$i],FILTER_SANITIZE_STRING);
		$minimum = filter_var($_POST['minimum'][$i],FILTER_SANITIZE_STRING);
		$unit_price = filter_var($_POST['unit_price'][$i],FILTER_SANITIZE_STRING);
		$unit_cost = filter_var($_POST['unit_cost'][$i],FILTER_SANITIZE_STRING);
		$rent_price = filter_var($_POST['rent_price'][$i],FILTER_SANITIZE_STRING);
		$rent_days = filter_var($_POST['rent_days'][$i],FILTER_SANITIZE_STRING);
		$rent_weeks = filter_var($_POST['rent_weeks'][$i],FILTER_SANITIZE_STRING);
		$rent_months = filter_var($_POST['rent_months'][$i],FILTER_SANITIZE_STRING);
		$rent_years = filter_var($_POST['rent_years'][$i],FILTER_SANITIZE_STRING);
		$num_days = filter_var($_POST['num_days'][$i],FILTER_SANITIZE_STRING);
		$num_hours = filter_var($_POST['num_hours'][$i],FILTER_SANITIZE_STRING);
		$num_kms = filter_var($_POST['num_kms'][$i],FILTER_SANITIZE_STRING);
		$num_miles = filter_var($_POST['num_miles'][$i],FILTER_SANITIZE_STRING);
		$fee = filter_var($_POST['fee'][$i],FILTER_SANITIZE_STRING);
		$hours_estimated = filter_var($_POST['hours_estimated'][$i],FILTER_SANITIZE_STRING);
		$hours_actual = filter_var($_POST['hours_actual'][$i],FILTER_SANITIZE_STRING);
		$service_code = filter_var($_POST['service_code'][$i],FILTER_SANITIZE_STRING);
		$description = filter_var($_POST['description'][$i],FILTER_SANITIZE_STRING);
		$history = 'Position rate card '.($ratecardid == '' ? 'Added' : 'Edited').' by '.get_contact($dbc, $_SESSION['contactid']).' on '.date('Y-m-d h:i:s');

		if(!empty($start_date.$end_date.$alert_date.$alert_staff.$annual.$monthly.$semi_month.$weekly.$daily.$hourly.$hourly_work.$hourly_travel.$field_day_actual.$field_day_bill.$cost.$price_admin.$price_wholesale.$price_commercial.$price_client.$minimum.$unit_price.$unit_cost.$rent_price.$rent_days.$rent_weeks.$rent_months.$rent_years.$num_days.$num_hours.$num_kms.$num_miles.$fee.$hours_estimated.$hours_actual.$service_code.$description)) {
			$sql = '';
			if($ratecardid == '') {
				$sql = "INSERT INTO `position_rate_table` (`position_id`,`start_date`,`end_date`,`annual`,`monthly`,`semi_month`,`weekly`,`daily`,`hourly`,`hourly_work`,`hourly_travel`,`field_day_actual`,`field_day_bill`,`cost`,`price_admin`,`price_wholesale`,`price_commercial`,`price_client`,`minimum`,`unit_price`,`unit_cost`,`rent_price`,`rent_days`,`rent_weeks`,`rent_months`,`rent_years`,`num_days`,`num_hours`,`num_kms`,`num_miles`,`fee`,`hours_estimated`,`hours_actual`,`service_code`,`description`,`history`,`created_by`,`alert_date`,`alert_staff`) VALUES
					($position_id,'$start_date','$end_date','$annual','$monthly','$semi_month','$weekly','$daily','$hourly','$hourly_work','$hourly_travel','$field_day_actual','$field_day_bill','$cost','$price_admin','$price_wholesale','$price_commercial','$price_client','$minimum','$unit_price','$unit_cost','$rent_price','$rent_days','$rent_weeks','$rent_months','$rent_years','$num_days','$num_hours','$num_kms','$num_miles','$fee','$hours_estimated','$hours_actual','$service_code','$description','$history','".$_SESSION['contactid']."','$alert_date','$alert_staff')";
				$result = mysqli_query($dbc, $sql);
				$ratecardid = mysqli_insert_id($dbc);
			}
			else {
				$sql = "UPDATE `position_rate_table` SET `position_id`=$position_id,`start_date`='$start_date',`end_date`='$end_date',`annual`='$annual',`monthly`='$monthly',`semi_month`='$semi_month',`weekly`='$weekly',`daily`='$daily',`hourly`='$hourly',`hourly_work`='$hourly_work',`hourly_travel`='$hourly_travel',`field_day_actual`='$field_day_actual',`field_day_bill`='$field_day_bill',`cost`='$cost',`price_admin`='$price_admin',`price_wholesale`='$price_wholesale',`price_commercial`='$price_commercial',`price_client`='$price_client',`minimum`='$minimum',`unit_price`='$unit_price',`unit_cost`='$unit_cost',`rent_price`='$rent_price',`rent_days`='$rent_days',`rent_weeks`='$rent_weeks',`rent_months`='$rent_months',`rent_years`='$rent_years',`num_days`='$num_days',`num_hours`='$num_hours',`num_kms`='$num_kms',`num_miles`='$num_miles',`fee`='$fee',`hours_estimated`='$hours_estimated',`hours_actual`='$hours_actual',`service_code`='$service_code',`description`='$description',`history`=IFNULL(CONCAT(`history`,'<br />\n','$history'),'$history'),`alert_date`='$alert_date',`alert_staff`='$alert_staff' WHERE `rate_id`='$ratecardid'";
				$result = mysqli_query($dbc, $sql);
			}
			$rate_cards_keep[] = $ratecardid;
		}
	}
	$rate_cards_keep = "'".implode("','", $rate_cards_keep)."'";
        $date_of_archival = date('Y-m-d');
	mysqli_query($dbc, "UPDATE `position_rate_table` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `position_id` = '$position_id' AND `rate_id` NOT IN ($rate_cards_keep)");
}