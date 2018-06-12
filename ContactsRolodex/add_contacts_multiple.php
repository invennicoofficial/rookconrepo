<?php
/*
Add	Contacts
*/
include ('../include.php');
error_reporting(0);
// ADD Contacts
if(isset($_POST["submitty"]))
{
	$file = $_FILES['file']['tmp_name'];
	// Open the file and grab the headers
	$handle = fopen($file, "r");
	$headers = fgetcsv( $handle, 1024, "," );

	// Read the file row by row
	while( ( $csv = fgetcsv( $handle, 1024, "," ) ) !== false )
	{
		$num = count($csv);
		// Assign each column in the current row to variables
		$values = [ 'category'=>'','businessid'=>'','name'=>'','company'=>'','business_address'=>'','office_phone'=>'','first_name'=>'','last_name'=>'','cell_phone'=>'','home_phone'=>'','fax'=>'','email_address'=>'','customer_address'=>'','position'=>'','mailing_address'=>'','postal_code'=>'','zip_code'=>'','city'=>'','province'=>'','country'=>'' ];
		for($i = 0; $i < $num; $i++)
			$values[$headers[$i]] = trim(mysqli_real_escape_string($dbc, htmlspecialchars_decode($csv[$i],ENT_NOQUOTES)));
        
        $values['name']          = ( !empty($values['name'])) ? encryptIt($values['name']) : null;
        $values['first_name']    = ( !empty($values['name'])) ? encryptIt($values['first_name']) : null;
        $values['last_name']     = ( !empty($values['name'])) ? encryptIt($values['last_name']) : null;
        $values['email_address'] = ( !empty($values['name'])) ? encryptIt($values['email_address']) : null;

		// Find corporation or create
        $siteid = null;
        $businessid = null;
        
        if ($values['category'] == 'Corporation') {
            $result_corporation = mysqli_query ( $dbc, "SELECT `contactid` FROM `contacts` WHERE TRIM(`name`)='{$values['name']}'" );
            
            if ( mysqli_num_rows($result_corporation) > 0 ) {
                // Corporation exists, so get the ID
				$siteid_get	= mysqli_fetch_array ( $result_corporation );
				$siteid		= $siteid_get['contactid'];
            } elseif ( $values['name'] != '' ) {
				// Corporation not available, so add it if there is a Corporation name
				$query_insert_corporation = "INSERT INTO `contacts` (`tile_name`, `category`, `name`, `business_address`, office_phone, `deleted`, `status`)
					VALUES ('".FOLDER_NAME."', '{$values['category']}', '{$values['name']}', '{$values['business_address']}', '{$values['office_phone']}', 0, 1)";
				$result_insert_corporation = mysqli_query ( $dbc, $query_insert_corporation );
				$siteid = mysqli_insert_id($dbc);
            }
        
        } elseif ($values['category'] == 'Business') {
            $result_business = mysqli_query ( $dbc, "SELECT `contactid` FROM `contacts` WHERE TRIM(`name`)='{$values['name']}'" );
            
            if ( mysqli_num_rows ( $result_business ) > 0 ) {
				// Business exists, so get the ID
				$businessid_get	= mysqli_fetch_array ( $result_business );
				$businessid		= $businessid_get['contactid'];
			} elseif ( $values['name'] != '' ) {
				// Business not available, so add it if there is a Business name
				$query_insert_business = "INSERT INTO `contacts` (`tile_name`, `category`, `name`, `business_address`, office_phone, `deleted`, `status`)
					VALUES ('".FOLDER_NAME."', 'Business', '{$values['name']}', '{$values['business_address']}', '{$values['office_phone']}', 0, 1)";
				$result_insert_business = mysqli_query ( $dbc, $query_insert_business );
				$businessid = mysqli_insert_id ( $dbc );
			}
        }

		// Insert the contact into the database
		$sql = "INSERT INTO `contacts` (`tile_name`, `category`, `businessid`, `siteid`, `first_name`, `last_name`, `office_phone`, `cell_phone`, `home_phone`, `fax`, `customer_address`, `email_address`, `position`, `mailing_address`, `postal_code`, `zip_code`, `city`, `province`, `country`, `deleted`, `status`)
			VALUES ('".FOLDER_NAME."', 'Customer', $businessid, '$siteid', '{$values['first_name']}', '{$values['last_name']}', '{$values['office_phone']}', '{$values['cell_phone']}', '{$values['home_phone']}', '{$values['fax']}', '{$values['customer_address']}', '{$values['email_address']}', '{$values['position']}', '{$values['mailing_address']}', '{$values['postal_code']}', '{$values['zip_code']}', '{$values['city']}', '{$values['province']}', '{$values['country']}', 0, 1)";
		$results_insert_contact	= mysqli_query ( $dbc, $sql );
	}

	fclose( $handle );
	echo '<script type="text/javascript"> alert("Successfully imported CSV file. Please check the Contacts dashboard to view your newly added contacts."); </script>';
}
// END ADD CONTACTS

// BEGIN EDIT contacts
if(isset($_POST["submitty2"])) {
	$i = 0;
	$file = $_FILES['file']['tmp_name'];
	$handle = fopen($file, "r");
	$c = 0;
    
    // Read CSV to array
    while (($row = fgetcsv($handle, 1024)) !== false) {
        if (empty($fields)) {
            $fields = $row;
            continue;
        }
        foreach ($row as $k=>$value) {
            $results[$i][$fields[$k]] = $value;
        }
        $i++;
    }
    
    foreach ($results as $row) {
        if ( $c==0 ) {
            $c++; //Skip the first row (headings)
        } else {
            $query = '';
            for ( $x=0; $x<count($fields); $x++ ) {
                if ($x==0) {
                    $x++; //Skip updating the `contactid`
                } else {
                    $row['name'] = ( !empty($row['name']) ) ? encryptIt($row['name']) : null;
                    $row['first_name'] = ( !empty($row['first_name']) ) ? encryptIt($row['first_name']) : null;
                    $row['last_name'] = ( !empty($row['last_name']) ) ? encryptIt($row['last_name']) : null;
                    $row['email_address'] = ( !empty($row['email_address']) ) ? encryptIt($row['email_address']) : null;
                    $query .= "`" . $fields[$x] . "`='" . $row[$fields[$x]] . "', ";
                }
            }
            
            $update = "UPDATE `contacts` SET ". rtrim($query, ", ") ." WHERE `contactid`='{$row['contactid']}'";
            $result_update = mysqli_query($dbc, $update);
        }
    }
    
    echo '<script type="text/javascript"> alert("CSV file was imported successfully. Please check the Contacts dashboard to view the latest updates.");</script>';
    
    /*
	while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
	    if($i == 0) {
			$i++;
		} else {
			$col0	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[0],ENT_NOQUOTES));
			$col1	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[1],ENT_NOQUOTES));
			$col2	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[2],ENT_NOQUOTES));
			$col3	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[3],ENT_NOQUOTES));
			$col4	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[4],ENT_NOQUOTES));
			$col5	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[5],ENT_NOQUOTES));
			$col6	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[6],ENT_NOQUOTES));
			$col7	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[7],ENT_NOQUOTES));
			$col8	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[8],ENT_NOQUOTES));
			$col9	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[9],ENT_NOQUOTES));
			$col10	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[10],ENT_NOQUOTES));
			$col11	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[11],ENT_NOQUOTES));
			$col12	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[12],ENT_NOQUOTES));
			$col13	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[13],ENT_NOQUOTES));
			$col14	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[14],ENT_NOQUOTES));
			$col15	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[15],ENT_NOQUOTES));
			$col16	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[16],ENT_NOQUOTES));
			$col17	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[17],ENT_NOQUOTES));
			$col18	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[18],ENT_NOQUOTES));
			$col19	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[19],ENT_NOQUOTES));
			$col20	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[20],ENT_NOQUOTES));
			$col21	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[21],ENT_NOQUOTES));
			$col22	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[22],ENT_NOQUOTES));
			$col23	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[23],ENT_NOQUOTES));
			$col24	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[24],ENT_NOQUOTES));
			$col25	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[25],ENT_NOQUOTES));
			$col26	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[26],ENT_NOQUOTES));
			$col27	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[27],ENT_NOQUOTES));
			$col28	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[28],ENT_NOQUOTES));
			$col29	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[29],ENT_NOQUOTES));
			$col30	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[30],ENT_NOQUOTES));
			$col31	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[31],ENT_NOQUOTES));
			$col32	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[32],ENT_NOQUOTES));
			$col33	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[33],ENT_NOQUOTES));
			$col34	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[34],ENT_NOQUOTES));
			$col35	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[35],ENT_NOQUOTES));
			$col36	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[36],ENT_NOQUOTES));
			$col37	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[37],ENT_NOQUOTES));
			$col38	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[38],ENT_NOQUOTES));
			$col39	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[39],ENT_NOQUOTES));
			$col40	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[40],ENT_NOQUOTES));
			$col41	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[41],ENT_NOQUOTES));
			$col42	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[42],ENT_NOQUOTES));
			$col43	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[43],ENT_NOQUOTES));
			$col44	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[44],ENT_NOQUOTES));
			$col45	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[45],ENT_NOQUOTES));
			$col46	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[46],ENT_NOQUOTES));
			$col47	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[47],ENT_NOQUOTES));
			$col48	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[48],ENT_NOQUOTES));
			$col49	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[49],ENT_NOQUOTES));
			$col50	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[50],ENT_NOQUOTES));
			$col51	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[51],ENT_NOQUOTES));
			$col52	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[52],ENT_NOQUOTES));
			$col53	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[53],ENT_NOQUOTES));
			$col54	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[54],ENT_NOQUOTES));
			$col55	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[55],ENT_NOQUOTES));
			$col56	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[56],ENT_NOQUOTES));
			$col57	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[57],ENT_NOQUOTES));
			$col58	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[58],ENT_NOQUOTES));
			$col59	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[59],ENT_NOQUOTES));
			$col60	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[60],ENT_NOQUOTES));
			$col61	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[61],ENT_NOQUOTES));
			$col62	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[62],ENT_NOQUOTES));
			$col63	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[63],ENT_NOQUOTES));
			$col64	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[64],ENT_NOQUOTES));
			$col65	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[65],ENT_NOQUOTES));
			$col66	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[66],ENT_NOQUOTES));
			$col67	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[67],ENT_NOQUOTES));
			$col68	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[68],ENT_NOQUOTES));
			$col69	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[69],ENT_NOQUOTES));
			$col70	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[70],ENT_NOQUOTES));
			$col71	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[71],ENT_NOQUOTES));
			$col72	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[72],ENT_NOQUOTES));
			$col73	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[73],ENT_NOQUOTES));
			$col74	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[74],ENT_NOQUOTES));
			$col75	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[75],ENT_NOQUOTES));
			$col76	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[76],ENT_NOQUOTES));
			$col77	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[77],ENT_NOQUOTES));
			$col78	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[78],ENT_NOQUOTES));
			$col79	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[78],ENT_NOQUOTES));
			$col80	= mysqli_real_escape_string($dbc, htmlspecialchars_decode($filesop[78],ENT_NOQUOTES));

			$sql = mysqli_query($dbc, 'SELECT * FROM contacts WHERE contactid = "'.$col0.'"');
			while($row = mysqli_fetch_assoc($sql)){
				if($row['contactid'] == $col0) {
					$HeadingsArray=array();
					foreach($row as $name => $value){
						$HeadingsArray[]=$value;
					}
				}
			}

			$i++;
			$query_insert_inventory = "UPDATE contacts SET
                `tile_name`             = '$col1',
                `category`              = '$col2',
                `businessid`            = '$col3',
                `siteid`                = '$col4',
                `name`                  = '$col5',
                `first_name`            = '$col6',
                `last_name`             = '$col7',
                `user_name`             = '$col8',
                `prefer_name`           = '$col9',
                `password`              = '$col0',
                `role`                  = '$col11',
                `classification`        = '$col2',
                `name_on_account`       = '$col13',
                `operating_as`          = '$col14',
                `emergency_contact`     = '$col15',
                `occupation`            = '$col16',
                `office_phone`          = '$col17',
                `cell_phone`            = '$col18',
                `email_address`         = '$col19',
                `office_email`          = '$col20',
                `company_email`         = '$col21',
                `website`               = '$col22',
                `customer_address`      = '$col23',
                `referred_by`           = '$col24',
                `company`               = '$col25',
                `position`              = '$col26',
                `title`                 = '$col27',
                `linkedin`              = '$col28',
                `facebook`              = '$col29',
                `twitter`               = '$col30',
                `employee_num`          = '$col31',
                `sin`                   = '$col32',
                `client_tax_exemption`  = '$col33',
                `tax_exemption_number`  = '$col34',
                `duns`                  = '$col35',    
                `cage`                  = '$col36',
                `self_identification`   = '$col37',
                `aish_card_no`          = '$col38',
                `license_plate_no`      = '$col39',
                `carfax`                = '$col40',
                `address`               = '$col41',
                `mailing_address`       = '$col42',
                `business_address`      = '$col43',
                `ship_to_address`       = '$col44',
                `postal_code`           = '$col45',
                `zip_code`              = '$col46',
                `city`                  = '$col47',
                `province`              = '$col48',
                `state`                 = '$col49',
                `country`               = '$col50',
                `ship_country`          = '$col51',
                `ship_city`             = '$col52',
                `ship_state`            = '$col53',
                `ship_zip`              = '$col54',
                `google_maps_address`   = '$col55',
                `city_part`             = '$col56',
                `rating`                = '$col57',
                `account_number`        = '$col58',
                `payment_type`          = '$col59',
                `payment_name`          = '$col60',
                `payment_address`       = '$col61',
                `payment_city`          = '$col62',
                `payment_state`         = '$col63',
                `payment_postal_code`   = '$col64',
                `payment_zip_code`      = '$col65',
                `gst_no`                = '$col66',
                `pst_no`                = '$col67',
                `vendor_gst_no`         = '$col68',
                `payment_information`   = '$col69',
                `pricing_level`         = '$col70',
                `unit_no`               = '$col71',
                `bay_no`                = '$col72',
                `option_to_renew`       = '$col73',
                `lease_term_no_of_years`= '$col74',
                `commercial_insurer`    = '$col75',
                `residential_insurer`   = '$col76',
                `wcb_no`                = '$col77',
                `calendar_color`        = '$col78',
                `deleted`               = '$col79',
                `semi_monthly_rate`     = '$col80',
                `horizontal_communication`= '$col81',
                `toggle_tile_menu`      = '$col82',
                `software_tile_menu_choice`= '$col83',
                `newsboard_menu_choice` = '$col84',
                `software_styler_choice`= '$col85',
                `safety_manual_view`    = '$col86',
                `status`                = '$col87',
                `pri_emergency_first_name`  = '$col88',
                `pri_emergency_last_name`   = '$col89',
                `pri_emergency_cell_phone`  = '$col90',
                `pri_emergency_home_phone`  = '$col91',
                `pri_emergency_email`       = '$col92',
                `pri_emergency_relation`    = '$col93',
                `sec_emergency_first_name`  = '$col94',
                `sec_emergency_last_name`   = '$col95',
                `sec_emergency_cell_phone`  = '$col96',
                `sec_emergency_home_phone`  = '$col97',
                `sec_emergency_email`       = '$col98',
                `sec_emergency_relation`    = '$col99',
                `cc_on_file`            = '$col100',
                `show_hide_user`        = '$col101',
                `bank_name`             = '$col102',
                `bank_transit`          = '$col103',
                `bank_institution_number`   = '$col104',
                `bank_account_number`   = '$col105',
                `health_care_num`       = '$col106',
                `health_concerns`       = '$col107',
                `health_emergency_procedure`    = '$col108',
                `health_medications`    = '$col109',
                `health_allergens`      = '$col110',
                `health_allergens_procedure`    = '$col111',
                `category_contact`      = '$col112',
                `gender`                = '$col113',
                `license`               = '$col114',
                `insurerid`             = '$col115',
                `hire_date`             = '$col116',
                `referred_by_name`      = '$col117',
                `credential`            = '$col118',
                `health_care_no`        = '$col119',
                `birth_date`            = '$col120',
                `scheduled_hours`       = '$col121',
                `schedule_days`         = '$col122',
                `correspondence_language`       = '$col123',
                `accepts_receive_emails`        = '$col124',
                `amount_to_bill`        = '$col125',
                `amount_owing`          = '$col126',
                `amount_credit`         = '$col127',
                `business_street`       = '$col128',
                `business_city`         = '$col129',
                `business_state`        = '$col130',    
                `business_country`      = '$col131',
                `business_zip`          = '$col132',
                `description`           = '$col133',
                `first_letter`          = '$col134',
                `maintenance`           = '$col135',
                `mva_forms`             = '$col136',
                `new_patient`           = '$col137',
                `updated_at`            = '$col138',
                `created_by`            = '$col139',
                `nick_name`             = '$col140',
                `profile_link`          = '$col141',
                `siteclientid`          = '$col142',
                `site_name`             = '$col143',
                `lsd`                   = '$col144',
                `display_name`          = '$col145',
                `package_balance`       = '$col146',
                `plan_acctno`           = '$col147',
                `prefix`                = '$col148',
                `staff_category`        = '$col149',
                `last_login`            = '$col150',
                `start_offline_time`    = '$col151',
                `end_offline_time`      = '$col152',
                `initials`              = '$col153',
                `school`                = '$col154',
                `hear_about`            = '$col155',
                `preferred_pronoun`     = '$col156'

                WHERE contactid= '$col0'";
			$result_insert_inventory = mysqli_query($dbc, $query_insert_inventory) or die(mysqli_error($dbc));


			$sql = mysqli_query($dbc, 'SELECT * FROM contacts WHERE contactid = "'.$col0.'"');
			while($row = mysqli_fetch_assoc($sql)){
				$x = 0;
				foreach($row as $name => $value){
					$name = $name;
					$xx = 0;
					foreach ($HeadingsArray as $original) {
						if($x == $xx) {
if($original != $value) {
	$update_log = $name.' was changed from "'.$original.'" to "'.$value.'" where Contact ID = '.$col0.'';
	$today_date = date('Y-m-d H:i:s', time());
	$contactid = $_SESSION['contactid'];
	$result = mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid= '$contactid'");
	while($row = mysqli_fetch_assoc($result)) {
		$name = decryptIt($row['first_name']).' '.decryptIt($row['last_name']).' ('.$row['contactid'].')';
	}
	$query_insert_customer = "INSERT INTO `import_export_log` (table_name, type, description, date_time, contact) VALUES ('Contacts', 'Edit', '$update_log', '$today_date', '$name')";
	$result_insert_customer = mysqli_query($dbc, $query_insert_customer);
}
						}
						$xx++;
					}
					$x++;
				}
			}
		}
	} */
    
}
// END EDIT CONTACTS

// BEGIN EXPORT FROM EXPORT PAGE

if(isset($_POST["exporter"]))
{
		$category = $_POST['category_export'];
		$today_date = date('Y-m-d_h-i-s-a', time());
		$FileName = "exports/contacts_export_".$today_date.".csv";
		$file = fopen($FileName,"w");

		if ( $category=='3456780123456971230' ) {
            $sql = "SELECT * FROM `contacts`";
        } else {
            $sql = "SELECT * FROM `contacts` WHERE category='$category'";
        }
    	$result = mysqli_query($dbc, $sql);

		$headings = true;
		$HeadingsArray = array();

        while($row = mysqli_fetch_assoc($result)) {
			$valuesArray=array();
			foreach($row as $name => $value){
                echo $name;
                echo '<br>';
				if($headings) {
					$HeadingsArray[] = $name;
                }

                if($name == 'name' || $name == 'first_name' || $name == 'last_name' || $name == 'office_phone' || $name == 'cell_phone' || $name == 'home_phone' || $name == 'email_address' || $name == 'business_street' || $name == 'business_city' || $name == 'business_state' || $name == 'business_country' || $name == 'business_zip' || $name == 'health_care_no') {
                    $value = decryptIt($value);
                }

				$valuesArray[]=$value;
			}

            if($headings) {
				fputcsv($file, $HeadingsArray);
            }
			fputcsv($file,$valuesArray);
			$headings = false;
		}

		fclose($file);
		header("Location: $FileName");
		if($category == '3456780123456971230') {
			$update_log = 'All contacts were exported.';
		} else {
			$update_log = 'All contacts under the '.$category.' category were exported.';
		}

		$today_date = date('Y-m-d H:i:s', time());
		$contactid = $_SESSION['contactid'];
		$result = mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid= '$contactid'");
		while($row = mysqli_fetch_assoc($result)) {
			$name = decryptIt($row['first_name']).' '.decryptIt($row['last_name']).' ('.$row['contactid'].')';
		}
		$query_insert_customer = "INSERT INTO `import_export_log` (table_name, type, description, date_time, contact) VALUES ('Contacts', 'Export', '$update_log', '$today_date', '$name')";
		$result_insert_customer = mysqli_query($dbc, $query_insert_customer);
}

// END EXPORT FROM EXPORT PAGE

// BEGIN EXPORT ALL FROM EDITOR
if(isset($_GET['exp'])) {
	$all_type = rtrim($type_url, ", ");
	$today_date = date('Y-m-d_h-i-s-a', time());
	$FileName = "exports/contacts_export_".$today_date.".csv";
	$file = fopen($FileName,"w");
	$sql = mysqli_query($dbc, 'SELECT contactid,category,businessid,name,first_name,last_name,user_name,password,role,classification,name_on_account,operating_as,profile_photo,emergency_contact,occupation,plan_acctno,office_phone,cell_phone,home_phone,fax,email_address,website,customer_address,referred_by,company,position,title,linkedin,twitter,client_tax_exemption,tax_exemption_number,duns,cage,self_identification,aish_card_no,license_plate_no,carfax,mailing_address,business_address,ship_to_address,postal_code,zip_code,city,province,state,country,ship_country,ship_city,ship_state,ship_zip,google_maps_address,city_part,account_number,payment_type,payment_name,payment_address,payment_city,payment_state,payment_postal_code,payment_zip_code,gst_no,pst_no,vendor_gst_no,payment_information,pricing_level,unit_no,bay_no,option_to_renew,lease_term_no_of_years,commercial_insurer,residential_insurer,wcb_no,deleted,horizontal_communication,toggle_tile_menu,software_tile_menu_choice,newsboard_menu_choice,software_styler_choice,safety_manual_view,calendar_color FROM `contacts`');
	$row = mysqli_fetch_assoc($sql);
	// Save headings alon
	$HeadingsArray=array();
	foreach($row as $name => $value){
		$HeadingsArray[]=$name;
	}
	fputcsv($file,$HeadingsArray);

	$sql = mysqli_query($dbc, 'SELECT contactid,category,businessid,name,first_name,last_name,user_name,password,role,classification,name_on_account,operating_as,profile_photo,emergency_contact,occupation,plan_acctno,office_phone,cell_phone,home_phone,fax,email_address,website,customer_address,referred_by,company,position,title,linkedin,twitter,client_tax_exemption,tax_exemption_number,duns,cage,self_identification,aish_card_no,license_plate_no,carfax,mailing_address,business_address,ship_to_address,postal_code,zip_code,city,province,state,country,ship_country,ship_city,ship_state,ship_zip,google_maps_address,city_part,account_number,payment_type,payment_name,payment_address,payment_city,payment_state,payment_postal_code,payment_zip_code,gst_no,pst_no,vendor_gst_no,payment_information,pricing_level,unit_no,bay_no,option_to_renew,lease_term_no_of_years,commercial_insurer,residential_insurer,wcb_no,deleted,horizontal_communication,toggle_tile_menu,software_tile_menu_choice,newsboard_menu_choice,software_styler_choice,safety_manual_view,calendar_color FROM contacts');

	// Save all records without headings
	while($row = mysqli_fetch_assoc($sql)){
		$valuesArray=array();
		foreach($row as $name => $value){
			$valuesArray[]=$value;
		}
		fputcsv($file,$valuesArray);
	}
	fclose($file);
	header("Location: $FileName");
	$update_log = 'All contacts were exported.';
	$today_date = date('Y-m-d H:i:s', time());
	$contactid = $_SESSION['contactid'];
	$result = mysqli_query($dbc, "SELECT * FROM contacts WHERE contactid= '$contactid'");
	while($row = mysqli_fetch_assoc($result)) {
		$name = decryptIt($row['first_name']).' '.decryptIt($row['last_name']).' ('.$row['contactid'].')';
	}
	$query_insert_customer = "INSERT INTO `import_export_log` (table_name, type, description, date_time, contact) VALUES ('Contacts', 'Export', '$update_log', '$today_date', '$name')";
	$result_insert_customer = mysqli_query($dbc, $query_insert_customer);
}
// END EXPORT
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#category").change(function() {
            if($( "#category option:selected" ).text() == 'Other') {
                    $( "#category_name" ).show();
            } else {
                $( "#category_name" ).hide();
            }
        });
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised();
?>
<div class="container">

<?php
	$active_add = 'active_tab';
	$active_edit = '';
	$active_export = '';
	$active_log = '';
	$type_get = '';
	$title = '';
	if(isset($_GET['type'])) {
		$type_get = $_GET['type'];
		if($type_get == 'add') {
			$active_add = 'active_tab';
			$title = 'Add Multiple Contacts';
		} else if($type_get == 'edit') {
			$active_edit = 'active_tab';
			$active_add = '';
			$title = 'Edit Multiple Contacts';
		} else if($type_get == 'export') {
			$active_export = 'active_tab';
			$active_add = '';
			$title = 'Export Contacts';
		} else if($type_get == 'log') {
			$active_log = 'active_tab';
			$active_add = '';
			$title = 'Contacts History';
		}
	}

	echo '<h1>' . $title . '</h1>';
	echo '<div class="gap-top double-gap-bottom"><a href="contacts.php?category=&filter=Top" class="btn config-btn">Back to Dashboard</a></div>';

	echo "<div class='mobile-100-container double-gap-bottom'>";
		echo "<a href='add_contacts_multiple.php?type=add'><button type='button' class='btn brand-btn mobile-100 mobile-block ".$active_add."' >Add Multiple</button></a>&nbsp;&nbsp;";
		echo "<a href='add_contacts_multiple.php?type=edit'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_edit."' >Edit Multiple</button></a>&nbsp;&nbsp;";
		echo "<a href='add_contacts_multiple.php?type=export'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_export."' >Export</button></a>&nbsp;&nbsp;";
		echo "<a href='add_contacts_multiple.php?type=log'><button type='button' class='btn brand-btn mobile-block mobile-100 ".$active_log."' >History</button></a>&nbsp;&nbsp;";
	echo '</div>'; ?>

	<?php if($type_get == '' || $type_get == 'add') { ?>
		<div class="row add">
			<form name="import" method="post" enctype="multipart/form-data">
				<div class="notice">Steps to Upload Multiple Items into the Contacts tile:<br><Br>
					<b>1.</b> Please download the following Excel(CSV) file to use as a template: <a href='Add_Multiple_Contacts.csv' style='color:white; text-decoration:underline !important;'>Add_Multiple_Contacts.csv</a>.<br><br>
					<b>2.</b> Fill in the rows (starting from row 2). Please note that each row you fill out will become a separate Contact in the Contacts tile.<br>
					<img src='../img/warning.png' style='width:25px;'> NOTE: Do not change/move/delete any of the column titles in the first row.<br>
                    <span class='small'><b>Hint</b>: Press CTRL+F on your keyboard to find the fields you would like to populate; this will help you locate them faster.</span><br><br>
					<b>3.</b> After you are done filling out your data, save the Excel (CSV) file, upload the CSV file below, and hit submit.<br><br>
					<b>4.</b> Please look for your newly added Contacts in the Contacts dashboard!<br><br>
					<input class="form-control" type="file" name="file" /><br />
				</div>
				<div class="row double-padded">
					<div class="col-sm-6">
						<a href="contacts.php?category=&filter=Top" class="btn brand-btn btn-lg">Back</a>
						<!--<a href="contacts.php?category=&filter=Top" class="btn brand-btn btn-lg pull-right">Back</a>-->
					</div>
					<div class="col-sm-6">
						<input class="btn brand-btn btn-lg pull-right" type="submit" name="submitty" value="Submit" />
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
		</div>

	<?php } else if ($type_get == 'edit') { ?>
		<div class="row edit">
			<form name="import" method="post" enctype="multipart/form-data">
				<div class="notice">Steps to Edit Multiple Items in the Contacts tile:<br><Br>
					<!--
                    <b>1.</b> Please download the following Excel (CSV) file, which will be the current list of all of your Contacts: <a href='add_contacts_multiple.php?type=edit&exp=true' style='color:white; text-decoration:underline;'>Export Contacts</a><br>
					<span style='color:lightgreen'><b>Hint:</b></span> if you would like to edit a specific category from Contacts, export the Excel (CSV) file from this page: <a href='add_contacts_multiple.php?type=export' target="_BLANK" style='color:white; text-decoration:underline;'>Export Specific Contacts</a>.<br><br>
                    -->
                    <b>1.</b> Please export the contacts in Excel (CSV) from: <a href='add_contacts_multiple.php?type=export' style='color:white; text-decoration:underline;'>Export Contacts</a><br>
					<span class="small"><b>Hint:</b> If you would like to edit a specific category from Contacts, select the Contact category to export.</span><br><br>
					<b>2.</b> Make your desired changes inside of the Excel file.<br>
					<img src='../img/warning.png' style='width:25px;'> NOTE</span>: Do not change/move/delete any of the column titles in the first row. Also, do not change the data in the first column (contactid), or else the edits may not go through properly.<br>
                    <span class="small"><b>Hint:</b> press CTRL+F on your keyboard to find the fields you would like to populate; this will help you locate them faster.</span><br><br>
					<b>3.</b> After you are done editing the data, save your Excel (CSV) file, upload the CSV file below, and hit submit.<br><br>
					<b>4.</b> Please look for your edited Contacts in the Contacts dashboard!<br><br>
					<input class="form-control" type="file" name="file" /><br />
				</div>
				<div class="row double-padded">
					<div class="col-sm-6">
						<a href="contacts.php?category=Top"	class="btn brand-btn btn-lg">Back</a>
						<!--<a href="#"	class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a>-->
					</div>
					<div class="col-sm-6">
						<input class="btn brand-btn btn-lg pull-right" type="submit" name="submitty2" value="Submit" />
					</div>
					<div class="clearfix"></div>
				</div>
			</form>
		</div>

	<?php } else if ($type_get == 'export') { ?>
		<div class="row export">
			<form name="import" method="post" enctype="multipart/form-data">
				<div class="notice">
					<div class="col-sm-4 pad-5"><?php
						//$sql = mysqli_query($dbc, 'SELECT * FROM contacts WHERE deleted = 0 GROUP BY category');
                        $tabs = str_replace(',,',',',str_replace('Staff','',get_config($dbc, FOLDER_NAME.'_tabs')));
                        $each_tab = explode(',', $tabs);
                        sort($each_tab); ?>
						<label for="travel_task" class="control-label pull-right">
							<span class="popover-examples hide-on-mobile"><a data-toggle="tooltip" data-placement="top" title="Select which category you would like to export, or select All Categories to export every contact that you have."><img src="../img/info.png" width="20"></a></span>
							Category:
						</label>
					</div>
					<div class="col-sm-4">
						<select name="category_export" class="chosen-select-deselect form-control" width="380">
							<option value="3456780123456971230">All Categories</option><?php
							/*
                            while($row = mysqli_fetch_assoc($sql)){
								echo '<option value="'.$row['category'].'">'.$row['category'].'</option>';
							} */
                            foreach ( $each_tab as $tab ) { ?>
                                <option value="<?= $tab ?>"><?= $tab ?></option><?php
                            } ?>
						</select>
					</div>
					<div class="col-sm-4">
						<button class="btn brand-btn" type="submit" name="exporter" value="Export" />Export Contacts</button>
					</div>
					<div class="clearfix"></div>
				</div>

				<div class="row double-padded">
					<a href="contacts.php?category=Top" class="btn brand-btn btn-lg">Back</a>
					<!--<a href="#" class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a>-->
				</div>
			</form>
		</div>

	<?php } else if ($type_get == 'log') {
		$query_check_credentials = "SELECT * FROM import_export_log WHERE deleted = 0 AND table_name = 'Contacts' ORDER BY date_time DESC LIMIT 10000";
		$gettotalrows = "SELECT * FROM import_export_log WHERE deleted = 0 AND table_name = 'Contacts'";
            $result = mysqli_query($dbc, $query_check_credentials);
			$xxres = mysqli_query($dbc, $gettotalrows);
            $num_rows = mysqli_num_rows($result);
			  $num_rowst = mysqli_num_rows($xxres);
            if($num_rows > 0) {
				echo "Currently displaying the last $num_rows rows (out of a total of $num_rowst rows).<br><br>";
                echo "<table class='table table-bordered '>";
                echo "<tr class='hidden-xs hidden-sm'>";
                        echo '<th>Type</th>';
                        echo '<th>Description</th>';
                        echo '<th>Date/Time</th>';
                        echo '<th>Author</th>';
                    echo "</tr>";
            } else {
                echo "<h2 class ='list_dashboard'>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
						echo '<td data-title="Type">' . $row['type'] . '</td>';
                        echo '<td data-title="Description">' . $row['description'] . '</td>';
						$time = substr($row['date_time'], strpos($row['date_time'], ' '));
						$time = date("g:i a", strtotime($time));
						$arr = explode(' ',trim($row['date_time']));
						echo '<td data-title="Date & Time">'.$arr[0].' at '.$time. '</td>';
						echo '<td data-title="Author">' . $row['contact'] . '</td>';
                echo "</tr>";
            }

            echo '</table>'; ?>
            
            <a href="contacts.php?category=Top" class="btn brand-btn btn-lg">Back</a>
        <?php } ?>

		
		<!--<a href="#" class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a>-->
		
  </div>
<?php include ('../footer.php'); ?>