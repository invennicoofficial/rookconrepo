<?php
	// If you need to parse XLS files, include php-excel-reader
	require('php-excel-reader/excel_reader2.php');
	include_once('include.php');
	require('php-excel-reader/SpreadsheetReader.php');

	$Reader = new SpreadsheetReader('staffdata2017.xlsx');
	$importArray = array();
	$count = 0;
	$encryptArray = array(0, 1,2,3,7,8);
	foreach ($Reader as $row)
	{
		$rowCount = count($row);
		for($i = 0; $i < $rowCount; $i++) {
			if(in_array($i, $encryptArray))
				$row[$i] = encryptit($row[$i]); 
		}

		$importArray[] = $row;
		$count++;
	}

	array_shift($importArray);
	foreach($importArray as $import)
	{
		$birth_date = str_replace('","', ',', $import[17]);
		$birthdate_new = date("Y-m-d", strtotime($birth_date));
		$primary_contact = $import[24] . ' ' . $import[25];
		$emergency = $import[26];
		$password = encryptit($import[5] . "@123");
		try {
			$query_insert_inventory = "INSERT INTO `contacts` (`category`, `status`, `role`,`user_name`, `password`,`first_name`, `last_name`, `emergency_contact`, `cell_phone`, `home_phone`, `primary_contact`, `fax`, `email_address`, `office_email`, `customer_address`, `position`, `employee_num`, `sin`, `license_plate_no`, `mailing_address`, `postal_code`, `city`, `province`, `country`, `deleted`, `birth_date`, `tile_name`, `description`) 
			VALUES ('Staff', 1, 'staff', '$import[5]', '$password', '$import[0]', '$import[1]', '$emergency', '$import[2]', '$import[3]', '$primary_contact', '$import[4]', '$import[7]', '$import[8]', '$import[10]', '$import[9]', '$import[16]', '$import[15]', '$import[19]', '$import[10]', '$import[11]', '$import[12]', '$import[13]', '$import[14]', 0, '$birthdate_new', 'Contacts', '$import[29]')";

			$result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
			$contactid = mysqli_insert_id($dbc);
		}
		catch(Exception $e) {
			echo "Exception for $import[5] is " . $e;
		}

	}

	echo "Contacts Upload is done.";
?>
