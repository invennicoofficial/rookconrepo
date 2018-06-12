<?php
include('config_ss.php');

if (isset($_POST['submit_type']) && $_POST['submit_type'] == 'individual_support_plan') {
    $support_contact_category = $_POST['support_contact_category'];
    $support_contact = $_POST['support_contact'];
    if($support_contact == 'NEW_CONTACT') {
        $first_name = explode(' ', $_POST['support_contact_new_contact'])[0];
        $last_name = filter_var(trim(str_replace($first_name,'',$_POST['support_contact_new_contact'])),FILTER_SANITIZE_STRING);
        $first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
        mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '$support_contact_category', '$first_name', '$last_name')");
        $support_contact = mysqli_insert_id($dbc);
    }
    $dayprimary_contact_category = implode(',',$_POST['dayprimary_contact_category']);
    $dayprimary_contact = implode(',',$_POST['dayprimary_contact']);
	foreach($_POST['dayprimary_contact_new_contact'] as $i => $full_name) {
		if($full_name != '') {
			$first_name = explode(' ', $full_name)[0];
			$last_name = filter_var(encryptIt(trim(str_replace($first_name,'',$full_name))),FILTER_SANITIZE_STRING);
			$first_name = filter_var(encryptIt(trim($first_name)),FILTER_SANITIZE_STRING);
			mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '".$_POST['dayprimary_contact_category'][$i]."', '$first_name', '$last_name')");
			$dayprimary_contact = preg_replace('/NEW_CONTACT/', mysqli_insert_id($dbc), $dayprimary_contact, 1);
		}
	}
    $daytl_contact_category = implode(',',$_POST['daytl_contact_category']);
    $daytl_contact = implode(',',$_POST['daytl_contact']);
	foreach($_POST['daytl_contact_new_contact'] as $i => $full_name) {
		if($full_name != '') {
			$first_name = explode(' ', $full_name)[0];
			$last_name = filter_var(encryptIt(trim(str_replace($first_name,'',$full_name))),FILTER_SANITIZE_STRING);
			$first_name = filter_var(encryptIt(trim($first_name)),FILTER_SANITIZE_STRING);
			mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '".$_POST['daytl_contact_category'][$i]."', '$first_name', '$last_name')");
			$daytl_contact = preg_replace('/NEW_CONTACT/', mysqli_insert_id($dbc), $daytl_contact, 1);
		}
	}
    $daykey_contact_category = implode(',',$_POST['daykey_contact_category']);
    $daykey_contact = implode(',',$_POST['daykey_contact']);
    if($daykey_contact == 'NEW_CONTACT') {
        $first_name = explode(' ', $_POST['daykey_contact_new_contact'])[0];
        $last_name = filter_var(trim(str_replace($first_name,'',$_POST['daykey_contact_new_contact'])),FILTER_SANITIZE_STRING);
        $first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
        mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '$daykey_contact_category', '$first_name', '$last_name')");
        $daykey_contact = mysqli_insert_id($dbc);
    }
    $resiprimary_contact_category = implode(',',$_POST['resiprimary_contact_category']);
    $resiprimary_contact = implode(',',$_POST['resiprimary_contact']);
	foreach($_POST['resiprimary_contact_new_contact'] as $i => $full_name) {
		if($full_name != '') {
			$first_name = explode(' ', $full_name)[0];
			$last_name = filter_var(encryptIt(trim(str_replace($first_name,'',$full_name))),FILTER_SANITIZE_STRING);
			$first_name = filter_var(encryptIt(trim($first_name)),FILTER_SANITIZE_STRING);
			mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '".$_POST['resiprimary_contact_category'][$i]."', '$first_name', '$last_name')");
			$resiprimary_contact = preg_replace('/NEW_CONTACT/', mysqli_insert_id($dbc), $resiprimary_contact, 1);
		}
	}
    $resitl_contact_category = implode(',',$_POST['resitl_contact_category']);
    $resitl_contact = implode(',',$_POST['resitl_contact']);
	foreach($_POST['resitl_contact_new_contact'] as $i => $full_name) {
		if($full_name != '') {
			$first_name = explode(' ', $full_name)[0];
			$last_name = filter_var(encryptIt(trim(str_replace($first_name,'',$full_name))),FILTER_SANITIZE_STRING);
			$first_name = filter_var(encryptIt(trim($first_name)),FILTER_SANITIZE_STRING);
			mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '".$_POST['resitl_contact_category'][$i]."', '$first_name', '$last_name')");
			$resitl_contact = preg_replace('/NEW_CONTACT/', mysqli_insert_id($dbc), $resitl_contact, 1);
		}
	}
    $resikey_contact_category = implode(',',$_POST['resikey_contact_category']);
    $resikey_contact = implode(',',$_POST['resikey_contact']);
    if($resikey_contact == 'NEW_CONTACT') {
        $first_name = explode(' ', $_POST['resikey_contact_new_contact'])[0];
        $last_name = filter_var(trim(str_replace($first_name,'',$_POST['resikey_contact_new_contact'])),FILTER_SANITIZE_STRING);
        $first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
        mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '$resikey_contact_category', '$first_name', '$last_name')");
        $resikey_contact = mysqli_insert_id($dbc);
    }
    $guardianprimary_contact_category = implode(',',$_POST['guardianprimary_contact_category']);
    $guardianprimary_contact = implode(',',$_POST['guardianprimary_contact']);
	foreach($_POST['guardianprimary_contact_new_contact'] as $i => $full_name) {
		if($full_name != '') {
			$first_name = explode(' ', $full_name)[0];
			$last_name = filter_var(encryptIt(trim(str_replace($first_name,'',$full_name))),FILTER_SANITIZE_STRING);
			$first_name = filter_var(encryptIt(trim($first_name)),FILTER_SANITIZE_STRING);
			mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '".$_POST['guardianprimary_contact_category'][$i]."', '$first_name', '$last_name')");
			$guardianprimary_contact = preg_replace('/NEW_CONTACT/', mysqli_insert_id($dbc), $guardianprimary_contact, 1);
		}
	}
    $guardiansecondary_contact_category = implode(',',$_POST['guardiansecondary_contact_category']);
    $guardiansecondary_contact = implode(',',$_POST['guardiansecondary_contact']);
	foreach($_POST['guardiansecondary_contact_new_contact'] as $i => $full_name) {
		if($full_name != '') {
			$first_name = explode(' ', $full_name)[0];
			$last_name = filter_var(encryptIt(trim(str_replace($first_name,'',$full_name))),FILTER_SANITIZE_STRING);
			$first_name = filter_var(encryptIt(trim($first_name)),FILTER_SANITIZE_STRING);
			mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '".$_POST['guardiansecondary_contact_category'][$i]."', '$first_name', '$last_name')");
			$guardiansecondary_contact = preg_replace('/NEW_CONTACT/', mysqli_insert_id($dbc), $guardiansecondary_contact, 1);
		}
	}
    $guardianalt_contact_category = implode(',',$_POST['guardianalt_contact_category']);
    $guardianalt_contact = implode(',',$_POST['guardianalt_contact']);
    if($guardianalt_contact == 'NEW_CONTACT') {
        $first_name = explode(' ', $_POST['guardianalt_contact_new_contact'])[0];
        $last_name = filter_var(trim(str_replace($first_name,'',$_POST['guardianalt_contact_new_contact'])),FILTER_SANITIZE_STRING);
        $first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
        mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '$guardianalt_contact_category', '$first_name', '$last_name')");
        $guardianalt_contact = mysqli_insert_id($dbc);
    }
    $eme_contact_category = $_POST['eme_contact_category'];
    $eme_contact = implode(',',$_POST['eme_contact']);
    if($eme_contact == 'NEW_CONTACT') {
        $first_name = explode(' ', $_POST['eme_contact_new_contact'])[0];
        $last_name = filter_var(trim(str_replace($first_name,'',$_POST['eme_contact_new_contact'])),FILTER_SANITIZE_STRING);
        $first_name = filter_var($first_name,FILTER_SANITIZE_STRING);
        mysqli_query($dbc, "INSERT INTO `contacts` (`tile_name`, `category`, `first_name`, `last_name`) VALUES ('".FOLDER_NAME."', '$eme_contact_category', '$first_name', '$last_name')");
        $eme_contact = mysqli_insert_id($dbc);
    }
    $isp_start_date = $_POST['isp_start_date'];
    $isp_review_date = $_POST['isp_review_date'];
    $isp_end_date = $_POST['isp_end_date'];

    if($_POST['isp_quality_name'] != '') {
        $isp_quality = filter_var($_POST['isp_quality_name'],FILTER_SANITIZE_STRING);
    } else {
        $isp_quality = filter_var($_POST['isp_quality'],FILTER_SANITIZE_STRING);
    }

    if($_POST['isp_goals_name'] != '') {
        $isp_goals = filter_var(implode('*#*', $_POST['isp_goals_name']),FILTER_SANITIZE_STRING);
    } else {
        $isp_goals = filter_var($_POST['isp_goals'],FILTER_SANITIZE_STRING);
    }

    $isp_needs = filter_var(htmlentities($_POST['isp_needs']),FILTER_SANITIZE_STRING);
    $isp_strategies = filter_var(htmlentities($_POST['isp_strategies']),FILTER_SANITIZE_STRING);
    $isp_objectives = filter_var(htmlentities($_POST['isp_objectives']),FILTER_SANITIZE_STRING);

    if($_POST['isp_sis_name'] != '') {
        $isp_sis = filter_var($_POST['isp_sis_name'],FILTER_SANITIZE_STRING);
    } else {
        $isp_sis = filter_var($_POST['isp_sis'],FILTER_SANITIZE_STRING);
    }

    $isp_detail_responsible_contact_category = $_POST['isp_detail_responsible_contact_category'];
    $isp_detail_responsible_contact = implode(',',$_POST['isp_detail_responsible_contact']);
    $isp_updates = filter_var(htmlentities($_POST['isp_updates']),FILTER_SANITIZE_STRING);
    $isp_notes = filter_var(htmlentities($_POST['isp_notes']),FILTER_SANITIZE_STRING);

    if(empty($_POST['individualsupportplanid'])) {
        $query_insert_vendor = "INSERT INTO `individual_support_plan` (`support_contact_category`, `support_contact`, `dayprimary_contact_category`, `dayprimary_contact`, `daytl_contact_category`, `daytl_contact`, `daykey_contact_category`, `daykey_contact`, `resiprimary_contact_category`, `resiprimary_contact`, `resitl_contact_category`, `resitl_contact`, `resikey_contact_category`, `resikey_contact`, `guardianprimary_contact_category`, `guardianprimary_contact`, `guardiansecondary_contact_category`, `guardiansecondary_contact`, `guardianalt_contact_category`, `guardianalt_contact`, `eme_contact_category`, `eme_contact`, `isp_start_date`, `isp_review_date`, `isp_end_date`, `isp_quality`, `isp_goals`, `isp_needs`, `isp_strategies`, `isp_objectives`, `isp_sis`, `isp_detail_responsible_contact_category`, `isp_detail_responsible_contact`, `isp_updates`, `isp_notes`) VALUES ('$support_contact_category', '$support_contact', '$dayprimary_contact_category', '$dayprimary_contact', '$daytl_contact_category', '$daytl_contact', '$daykey_contact_category', '$daykey_contact', '$resiprimary_contact_category', '$resiprimary_contact', '$resitl_contact_category', '$resitl_contact', '$resikey_contact_category', '$resikey_contact', '$guardianprimary_contact_category', '$guardianprimary_contact', '$guardiansecondary_contact_category', '$guardiansecondary_contact', '$guardianalt_contact_category', '$guardianalt_contact', '$eme_contact_category', '$eme_contact', '$isp_start_date', '$isp_review_date', '$isp_end_date', '$isp_quality', '$isp_goals', '$isp_needs', '$isp_strategies', '$isp_objectives', '$isp_sis', '$isp_detail_responsible_contact_category', '$isp_detail_responsible_contact', '$isp_updates', '$isp_notes')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $individualsupportplanid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $individualsupportplanid = $_POST['individualsupportplanid'];
        $query_update_vendor = "UPDATE `individual_support_plan` SET `support_contact_category` = '$support_contact_category', `support_contact` = '$support_contact', `dayprimary_contact_category` = '$dayprimary_contact_category', `dayprimary_contact` = '$dayprimary_contact', `daytl_contact_category` = '$daytl_contact_category', `daytl_contact` = '$daytl_contact', `daykey_contact_category` = '$daykey_contact_category', `daykey_contact` = '$daykey_contact', `resiprimary_contact_category` = '$resiprimary_contact_category', `resiprimary_contact` = '$resiprimary_contact', `resitl_contact_category` = '$resitl_contact_category', `resitl_contact` = '$resitl_contact', `resikey_contact_category` = '$resikey_contact_category', `resikey_contact` = '$resikey_contact', `guardianprimary_contact_category` = '$guardianprimary_contact_category', `guardianprimary_contact` = '$guardianprimary_contact', `guardiansecondary_contact_category` = '$guardiansecondary_contact_category', `guardiansecondary_contact` = '$guardiansecondary_contact', `guardianalt_contact_category` = '$guardianalt_contact_category', `guardianalt_contact` = '$guardianalt_contact', `eme_contact_category` = '$eme_contact_category', `eme_contact` = '$eme_contact', `isp_start_date` = '$isp_start_date', `isp_review_date` = '$isp_review_date', `isp_end_date` = '$isp_end_date', `isp_quality` = '$isp_quality', `isp_goals` = '$isp_goals', `isp_needs` = '$isp_needs', `isp_strategies` = '$isp_strategies', `isp_objectives` = '$isp_objectives', `isp_sis` = '$isp_sis', `isp_detail_responsible_contact_category` = '$isp_detail_responsible_contact_category', `isp_detail_responsible_contact` = '$isp_detail_responsible_contact', `isp_updates` = '$isp_updates', `isp_notes` = '$isp_notes' WHERE `individualsupportplanid` = '$individualsupportplanid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

 //   mysqli_close($dbc);//Close the DB Connection
} 

if (!empty($_POST['medication_type']) && !empty($_POST['category']) && !empty($_POST['title'])) {
    $medicationcontactid = $_POST['medicationcontactid'];
    $clientid = $_POST['clientid'];

    $administration_times = filter_var($_POST['administration_times'],FILTER_SANITIZE_STRING);
    $side_effects = filter_var($_POST['side_effects'],FILTER_SANITIZE_STRING);
    $delivery_method = filter_var($_POST['delivery_method'],FILTER_SANITIZE_STRING);

    if($_POST['new_medication'] != '') {
        $medication_type = filter_var($_POST['new_medication'],FILTER_SANITIZE_STRING);
    } else {
        $medication_type = filter_var($_POST['medication_type'],FILTER_SANITIZE_STRING);
    }

    if($_POST['new_category'] != '') {
        $category = filter_var($_POST['new_category'],FILTER_SANITIZE_STRING);
    } else {
        $category = filter_var($_POST['med_category'],FILTER_SANITIZE_STRING);
    }
    $invoice_description = filter_var(htmlentities($_POST['invoice_description']),FILTER_SANITIZE_STRING);
    $ticket_description = filter_var(htmlentities($_POST['ticket_description']),FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $fee = filter_var($_POST['fee'],FILTER_SANITIZE_STRING);
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);

    $medication_code = filter_var($_POST['medication_code'],FILTER_SANITIZE_STRING);
    //$category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
    $cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
    //$description = filter_var($_POST['description'],FILTER_SANITIZE_STRING);

    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);

    if($_POST['same_desc'] == 1) {
        $quote_description = $description;
    } else {
        $quote_description = filter_var(htmlentities($_POST['quote_description']),FILTER_SANITIZE_STRING);
    }
    $final_retail_price = filter_var($_POST['final_retail_price'],FILTER_SANITIZE_STRING);
    $admin_price = filter_var($_POST['admin_price'],FILTER_SANITIZE_STRING);
    $wholesale_price = filter_var($_POST['wholesale_price'],FILTER_SANITIZE_STRING);
    $commercial_price = filter_var($_POST['commercial_price'],FILTER_SANITIZE_STRING);
    $client_price = filter_var($_POST['client_price'],FILTER_SANITIZE_STRING);
    $minimum_billable = filter_var($_POST['minimum_billable'],FILTER_SANITIZE_STRING);
    $estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
    $actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
    $msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);

    $unit_price = filter_var($_POST['unit_price'],FILTER_SANITIZE_STRING);
    $unit_cost = filter_var($_POST['unit_cost'],FILTER_SANITIZE_STRING);
    $rent_price = filter_var($_POST['rent_price'],FILTER_SANITIZE_STRING);
    $rental_days = filter_var($_POST['rental_days'],FILTER_SANITIZE_STRING);
    $rental_weeks = filter_var($_POST['rental_weeks'],FILTER_SANITIZE_STRING);
    $rental_months = filter_var($_POST['rental_months'],FILTER_SANITIZE_STRING);
    $rental_years = filter_var($_POST['rental_years'],FILTER_SANITIZE_STRING);
    $reminder_alert = filter_var($_POST['reminder_alert'],FILTER_SANITIZE_STRING);
    $daily = filter_var($_POST['daily'],FILTER_SANITIZE_STRING);
    $weekly = filter_var($_POST['weekly'],FILTER_SANITIZE_STRING);
    $monthly = filter_var($_POST['monthly'],FILTER_SANITIZE_STRING);
    $annually = filter_var($_POST['annually'],FILTER_SANITIZE_STRING);
    $total_days = filter_var($_POST['total_days'],FILTER_SANITIZE_STRING);
    $total_hours = filter_var($_POST['total_hours'],FILTER_SANITIZE_STRING);
    $total_km = filter_var($_POST['total_km'],FILTER_SANITIZE_STRING);
    $total_miles = filter_var($_POST['total_miles'],FILTER_SANITIZE_STRING);
    
    $start_date = filter_var($_POST['start_date'],FILTER_SANITIZE_STRING);
    $end_date = filter_var($_POST['end_date'],FILTER_SANITIZE_STRING);
    $reminder_date = filter_var($_POST['reminder_date'],FILTER_SANITIZE_STRING);

    if(empty($_POST['medicationid'])) {
        $query_insert_vendor = "INSERT INTO `medication` (`contactid`,`clientid`, `medication_type`, `category`, `medication_code`, `heading`, `cost`, `description`, `quote_description`, `invoice_description`, `ticket_description`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `name`, `title`,  `fee`, `unit_price`, `unit_cost`, `rent_price`, `rental_days`, `rental_weeks`, `rental_months`, `rental_years`, `reminder_alert`, `daily`,`weekly`, `monthly`, `annually`,  `total_days`, `total_hours`, `total_km`, `total_miles`, `administration_times`, `side_effects`, `delivery_method`, `start_date`, `end_date`, `reminder_date`) VALUES ('$medicationcontactid', '$clientid', '$medication_type', '$category', '$medication_code', '$heading', '$cost', '$description', '$quote_description', '$invoice_description', '$ticket_description', '$final_retail_price', '$admin_price', '$wholesale_price', '$commercial_price', '$client_price', '$minimum_billable', '$estimated_hours', '$actual_hours', '$msrp', '$name', '$title', '$fee', '$unit_price', '$unit_cost', '$rent_price', '$rental_days', '$rental_weeks', '$rental_months', '$rental_years', '$reminder_alert', '$daily', '$weekly', '$monthly', '$annually', '$total_days', '$total_hours', '$total_km', '$total_miles', '$administration_times', '$side_effects', '$delivery_method', '$start_date', '$end_date', '$reminder_date')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $medicationid = mysqli_insert_id($dbc);
        $url = 'Added';

        $user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO `medication_history` (`user`,`datetime`,`operation`,`medicationid`) VALUES ('$user', '$date', 'Insert', '$medicationid')";
        $result = mysqli_query($dbc, $query);
    } else {
        $medicationid = $_POST['medicationid'];
        $query_update_vendor = "UPDATE `medication` SET `contactid` = '$medicationcontactid', `clientid` = '$clientid', `medication_type` = '$medication_type', `category` = '$category',`medication_code` = '$medication_code', `heading` = '$heading', `cost` = '$cost', `description` = '$description', `quote_description` = '$quote_description', `invoice_description` = '$invoice_description', `ticket_description` = '$ticket_description', `final_retail_price` = '$final_retail_price', `admin_price` = '$admin_price', `wholesale_price` = '$wholesale_price', `commercial_price` = '$commercial_price', `client_price` = '$client_price', `minimum_billable` = '$minimum_billable', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `name` = '$name', `title` = '$title', `fee` = '$fee', `unit_price` = '$unit_price', `unit_cost` = '$unit_cost', `rent_price` = '$rent_price', `rental_days` = '$rental_days', `rental_weeks` = '$rental_weeks', `rental_months` = '$rental_months', `rental_years` = '$rental_years', `reminder_alert` = '$reminder_alert', `daily` = '$daily', `weekly` = '$weekly', `monthly` = '$monthly', `annually` = '$annually', `total_days` = '$total_days', `total_hours` = '$total_hours', `total_km` = '$total_km', `total_miles` = '$total_miles', `administration_times` = '$administration_times', `side_effects` = '$side_effects', `delivery_method` = '$delivery_method', `start_date` = '$start_date', `end_date` = '$end_date', `reminder_date` = '$reminder_date' WHERE `medicationid` = '$medicationid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';

        $user = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO `medication_history` (`user`,`datetime`,`operation`,`medicationid`) VALUES ('$user', '$date', 'Update', '$medicationid')";
        $result = mysqli_query($dbc, $query);
    }

    if (!file_exists('download/medications')) {
        mkdir('download/medications', 0777, true);
    }

    for($i = 0; $i < count($_FILES['upload_document']['name']); $i++) {
        $document = htmlspecialchars($_FILES["upload_document"]["name"][$i], ENT_QUOTES);

        move_uploaded_file($_FILES["upload_document"]["tmp_name"][$i], "download/medications/".$_FILES["upload_document"]["name"][$i]) ;

        if($document != '') {
            $query_insert_client_doc = "INSERT INTO `medication_uploads` (`medicationid`, `type`, `document_link`) VALUES ('$medicationid', 'Document', '$document')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

    for($i = 0; $i < count($_POST['support_link']); $i++) {
        $support_link = $_POST['support_link'][$i];

        if($support_link != '') {
            $query_insert_client_doc = "INSERT INTO `medication_uploads` (`medicationid`, `type`, `document_link`) VALUES ('$medicationid', 'Link', '$support_link')";
            $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
        }
    }

 //   mysqli_close($dbc);//Close the DB Connection
}

if (isset($_POST['submit_type']) && $_POST['submit_type'] == 'key_methodologies') {
    $value = $config['settings']['Choose Fields for Key Methodologies'];

    global $config;
    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[2].'_hidden'];
                    }
                }
            } elseif($field[1] == 'widget') {
                $inputs[$field[2]] = serialize($_POST[$field[2]]);
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    if(empty($_POST['keymethodologiesid'])) {
        $columns = implode(", ",array_keys($inputs));
        $values = '';
        foreach($inputs as $tmp) {
            $values .= "'".$tmp."', ";
        }
        $values = trim($values,', ');
        $sql = "INSERT INTO `key_methodologies` ($columns) VALUES ($values)";

        $query_insert_vendor = $sql;
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $keymethodologiesid = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $keymethodologiesid = $_POST['keymethodologiesid'];

        $fields = array();
        foreach($inputs as $field => $val) {
            $fields[] = "$field = '$val'";
        }
        $sql = "UPDATE `key_methodologies` SET " . join(', ', $fields) . " WHERE `keymethodologiesid` = '".$keymethodologiesid."'";

        $query_update_vendor = $sql;
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

}

if (isset($_POST['submit_type']) && $_POST['submit_type'] == 'protocols') {
    $value = $config['settings']['Choose Fields for Protocols'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[2].'_hidden'];
                    }
                }
            } elseif($field[1] == 'widget') {
                $inputs[$field[2]] = serialize($_POST[$field[2]]);
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(empty($_POST['protocol_id'])) {
        $columns = implode(", ",array_keys($inputs));
        $values = '';
        foreach($inputs as $tmp) {
            $values .= "'".$tmp."', ";
        }
        $values = trim($values,', ');
        $sql = "INSERT INTO `social_story_protocols` ($columns) VALUES ($values)";

        $query_insert_vendor = $sql;
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $protocol_id = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $protocol_id = $_POST['protocol_id'];

        $fields = array();
        foreach($inputs as $field => $val) {
            $fields[] = "$field = '$val'";
        }
        $sql = "UPDATE `social_story_protocols` SET " . join(', ', $fields) . " WHERE `protocol_id` = '$protocol_id'";

        $query_update_vendor = $sql;
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

}

if (isset($_POST['submit_type']) && $_POST['submit_type'] == 'routines') {
    $value = $config['settings']['Choose Fields for Routines'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[2].'_hidden'];
                    }
                }
            } elseif($field[1] == 'widget') {
                $inputs[$field[2]] = serialize($_POST[$field[2]]);
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(empty($_POST['routine_id'])) {
        $columns = implode(", ",array_keys($inputs));
        $values = '';
        foreach($inputs as $tmp) {
            $values .= "'".$tmp."', ";
        }
        $values = trim($values,', ');
        $sql = "INSERT INTO `social_story_routines` ($columns) VALUES ($values)";

        $query_insert_vendor = $sql;
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $routine_id = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $routine_id = $_POST['routine_id'];

        $fields = array();
        foreach($inputs as $field => $val) {
            $fields[] = "$field = '$val'";
        }
        $sql = "UPDATE `social_story_routines` SET " . join(', ', $fields) . " WHERE `routine_id` = '$routine_id'";

        $query_update_vendor = $sql;
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

}

if (isset($_POST['submit_type']) && $_POST['submit_type'] == 'communication') {
    $value = $config['settings']['Choose Fields for Communication'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[2].'_hidden'];
                    }
                }
            } elseif($field[1] == 'widget') {
                $inputs[$field[2]] = serialize($_POST[$field[2]]);
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(empty($_POST['communication_id'])) {
        $columns = implode(", ",array_keys($inputs));
        $values = '';
        foreach($inputs as $tmp) {
            $values .= "'".$tmp."', ";
        }
        $values = trim($values,', ');
        $sql = "INSERT INTO `social_story_communication` ($columns) VALUES ($values)";

        $query_insert_vendor = $sql;
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $communication_id = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $communication_id = $_POST['communication_id'];

        $fields = array();
        foreach($inputs as $field => $val) {
            $fields[] = "$field = '$val'";
        }
        $sql = "UPDATE `social_story_communication` SET " . join(', ', $fields) . " WHERE `communication_id` = '$communication_id'";

        $query_update_vendor = $sql;
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
}

if (isset($_POST['submit_type']) && $_POST['submit_type'] == 'activities') {
    $value = $config['settings']['Choose Fields for Activities'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[2].'_hidden'];
                    }
                }
            } elseif($field[1] == 'widget') {
                $inputs[$field[2]] = serialize($_POST[$field[2]]);
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(empty($_POST['activities_id'])) {
        $columns = implode(", ",array_keys($inputs));
        $values = '';
        foreach($inputs as $tmp) {
            $values .= "'".$tmp."', ";
        }
        $values = trim($values,', ');
        $sql = "INSERT INTO `social_story_activities` ($columns) VALUES ($values)";

        $query_insert_vendor = $sql;
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $activities_id = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $activities_id = $_POST['activities_id'];

        $fields = array();
        foreach($inputs as $field => $val) {
            $fields[] = "$field = '$val'";
        }
        $sql = "UPDATE `social_story_activities` SET " . join(', ', $fields) . " WHERE `activities_id` = '$activities_id'";

        $query_update_vendor = $sql;
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

}

if (isset($_POST['submit_type']) && $_POST['submit_type'] == 'medical_charts') {
    include ('config_mc.php');
    //Bowel Movement
    $value = $config['settings']['Choose Fields for Bowel Movement'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[3].$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[3].$field[2].'_hidden'];
                    }
                }
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[3].$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(!empty($_POST['bowel_movement_date'])) {
        if(empty($_POST['bowel_movement_id'])) {
            $columns = implode(", ",array_keys($inputs));
            $values = '';
            foreach($inputs as $tmp) {
                $values .= "'".$tmp."', ";
            }
            $values = trim($values,', ');
            $sql = "INSERT INTO `bowel_movement` ($columns) VALUES ($values)";

            $query_insert_vendor = $sql;
            $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
            $bowel_movement_id = mysqli_insert_id($dbc);
            $url = 'Added';
        } else {
            $bowel_movement_id = $_POST['bowel_movement_id'];

            $fields = array();
            foreach($inputs as $field => $val) {
                $fields[] = "$field = '$val'";
            }
            $sql = "UPDATE `bowel_movement` SET " . join(', ', $fields) . " WHERE `bowel_movement_id` = '$bowel_movement_id'";

            $query_update_vendor = $sql;
            $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
            $url = 'Updated';
        }
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    //Bowel Movement

    //Seizure Record
    $value = $config['settings']['Choose Fields for Seizure Record'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[3].$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[3].$field[2].'_hidden'];
                    }
                }
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[3].$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(!empty($_POST['seizure_record_date'])) {
        if(empty($_POST['seizure_record_id'])) {
            $columns = implode(", ",array_keys($inputs));
            $values = '';
            foreach($inputs as $tmp) {
                $values .= "'".$tmp."', ";
            }
            $values = trim($values,', ');
            $sql = "INSERT INTO `seizure_record` ($columns) VALUES ($values)";

            $query_insert_vendor = $sql;
            $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
            $seizure_record_id = mysqli_insert_id($dbc);
            $url = 'Added';
        } else {
            $seizure_record_id = $_POST['seizure_record_id'];

            $fields = array();
            foreach($inputs as $field => $val) {
                $fields[] = "$field = '$val'";
            }
            $sql = "UPDATE `seizure_record` SET " . join(', ', $fields) . " WHERE `seizure_record_id` = '$seizure_record_id'";

            $query_update_vendor = $sql;
            $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
            $url = 'Updated';
        }
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    //Seizure Record

    //Daily Water Temp
    $value = $config['settings']['Choose Fields for Daily Water Temp'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[3].$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[3].$field[2].'_hidden'];
                    }
                }
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[3].$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(!empty($_POST['daily_water_temp_date'])) {
        if(empty($_POST['daily_water_temp_id'])) {
            $columns = implode(", ",array_keys($inputs));
            $values = '';
            foreach($inputs as $tmp) {
                $values .= "'".$tmp."', ";
            }
            $values = trim($values,', ');
            $sql = "INSERT INTO `daily_water_temp` ($columns) VALUES ($values)";

            $query_insert_vendor = $sql;
            $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
            $daily_water_temp_id = mysqli_insert_id($dbc);
            $url = 'Added';
        } else {
            $daily_water_temp_id = $_POST['daily_water_temp_id'];

            $fields = array();
            foreach($inputs as $field => $val) {
                $fields[] = "$field = '$val'";
            }
            $sql = "UPDATE `daily_water_temp` SET " . join(', ', $fields) . " WHERE `daily_water_temp_id` = '$daily_water_temp_id'";

            $query_update_vendor = $sql;
            $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
            $url = 'Updated';
        }
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    //Daily Water Temp

    //Blood Glucose
    $value = $config['settings']['Choose Fields for Blood Glucose'];

    global $config;

    $inputs = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $inputs[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
                if($inputs[$field[2]] == '') {
                    if(isset($_POST[$field[3].$field[2].'_hidden'])) {
                        $inputs[$field[2]] = $_POST[$field[3].$field[2].'_hidden'];
                    }
                }
            } else {
                $inputs[$field[2]] = filter_var(htmlentities($_POST[$field[3].$field[2]], FILTER_SANITIZE_STRING));
            }
        }
    }

    $files = array();
    foreach($value['data'] as $tabs) {
        foreach($tabs as $field) {
            if($field[1] == 'upload') {
                $files[$field[2]] = $_FILES[$field[3].$field[2]]["name"];
            }
        }
    }
    foreach($files as $file => $name) {
        move_uploaded_file($_FILES[$file]["tmp_name"], "download/". $_FILES[$file]["name"]);
    }

    if(!empty($_POST['blood_glucose_date'])) {
        if(empty($_POST['blood_glucose_id'])) {
            $columns = implode(", ",array_keys($inputs));
            $values = '';
            foreach($inputs as $tmp) {
                $values .= "'".$tmp."', ";
            }
            $values = trim($values,', ');
            $sql = "INSERT INTO `blood_glucose` ($columns) VALUES ($values)";

            $query_insert_vendor = $sql;
            $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
            $blood_glucose_id = mysqli_insert_id($dbc);
            $url = 'Added';
        } else {
            $blood_glucose_id = $_POST['blood_glucose_id'];

            $fields = array();
            foreach($inputs as $field => $val) {
                $fields[] = "$field = '$val'";
            }
            $sql = "UPDATE `blood_glucose` SET " . join(', ', $fields) . " WHERE `blood_glucose_id` = '$blood_glucose_id'";

            $query_update_vendor = $sql;
            $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
            $url = 'Updated';
        }
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }
    //Blood Glucose

    //Height & Weight
    $clientid = $_POST['client'];
    $client_height = filter_var($_POST['medchart_client_height'],FILTER_SANITIZE_STRING);
    $client_weight = filter_var($_POST['medchart_client_weight'],FILTER_SANITIZE_STRING);
    $get_cost = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(contactmedicalid) AS contactmedicalid FROM contacts_medical WHERE contactid='$clientid'"));
    if($get_cost['contactmedicalid'] > 0) {
        $query_update_cost = "UPDATE `contacts_medical` SET `client_height` = '$client_height', `client_weight` = '$client_weight' WHERE `contactid` = '$clientid'";
        $result_update_cost = mysqli_query($dbc, $query_update_cost);
    } else {
        $query_insert_cost = "INSERT INTO `contacts_medical` (`contactid`, `client_height`, `client_weight`) VALUES ('$clientid', '$client_height', '$client_weight')";
        $result_insert_cost = mysqli_query($dbc, $query_insert_cost);
    }
    //Height & Weight
}