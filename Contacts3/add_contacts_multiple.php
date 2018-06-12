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

		// Find the business ID, or create it if needed
		$businessid = null;
		if($values['businessid'] != '') {
			$businessid = $values['businessid'];
		} else {
			$result_business = mysqli_query ( $dbc, "SELECT `contactid` FROM `contacts` WHERE TRIM(`name`)='{$values['name']}'" );

			if ( mysqli_num_rows ( $result_business ) > 0 ) {
				// Business exists, so get the ID
				$businessid_get	= mysqli_fetch_array ( $result_business );
				$businessid		= $businessid_get['contactid'];
			} else if ( $values['name'] != '' ) {
				// Business not available, so add it if there is a Business name
				$query_insert_business = "INSERT INTO `contacts` (`tile_name`, `category`, `name`, `business_address`, office_phone, `deleted`, `status`)
					VALUES ('".FOLDER_NAME."', 'Business', '{$values['name']}', '{$values['business_address']}', '{$values['office_phone']}', 0, 1)";
				$result_insert_business = mysqli_query ( $dbc, $query_insert_business );
				$businessid = mysqli_insert_id ( $dbc );
			}
		}

		// Insert the contact into the database
		$sql = "INSERT INTO `contacts` (`category`, `businessid`, `first_name`, `last_name`, `office_phone`, `cell_phone`, `home_phone`, `fax`, `customer_address`, `email_address`, `position`, `mailing_address`, `postal_code`, `zip_code`, `city`, `province`, `country`, `deleted`, `status`)
			VALUES ('Customer', $businessid, '{$values['first_name']}', '{$values['last_name']}', '{$values['office_phone']}', '{$values['cell_phone']}', '{$values['home_phone']}', '{$values['fax']}', '{$values['customer_address']}', '{$values['email_address']}', '{$values['position']}', '{$values['mailing_address']}', '{$values['postal_code']}', '{$values['zip_code']}', '{$values['city']}', '{$values['province']}', '{$values['country']}', 0, 1)";
		$results_insert_contact	= mysqli_query ( $dbc, $sql );
	}

	fclose( $handle );
	echo '<script type="text/javascript"> alert("Successfully imported CSV file. Please check the Contacts dashboard to view your newly added contacts."); </script>';
}
// END ADD CONTACTS
// BEGIN EDIT contacts
if(isset($_POST["submitty2"]))
{
	$i = 0;
	$file = $_FILES['file']['tmp_name'];
	$handle = fopen($file, "r");
	$c = 0;
	while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
	{
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
			$query_insert_inventory = "UPDATE contacts SET category = '$col1',businessid = '$col2',name = '$col3',first_name = '$col4',last_name = '$col5',user_name = '$col6',password = '$col7',role = ',$col8,',classification = '$col9',name_on_account = '$col10',operating_as = '$col11',profile_photo = '$col12',emergency_contact = '$col13',occupation = '$col14',plan_acctno = '$col15',office_phone = '$col16',cell_phone = '$col17',home_phone = '$col18',fax = '$col19',email_address = '$col20',website = '$col21',customer_address = '$col22',referred_by = '$col23',company = '$col24',position = '$col25',title = '$col26',linkedin = '$col27',twitter = '$col28',client_tax_exemption = '$col29',tax_exemption_number = '$col30',duns = '$col31',cage = '$col32',self_identification = '$col33',aish_card_no = '$col34',license_plate_no = '$col35',carfax = '$col36',mailing_address = '$col37',business_address = '$col38',ship_to_address = '$col39',postal_code = '$col40',zip_code = '$col41',city = '$col42',province = '$col43',state = '$col44',country = '$col45',ship_country = '$col46',ship_city = '$col47',ship_state = '$col48',ship_zip = '$col49',google_maps_address = '$col50',city_part = '$col51',account_number = '$col52',payment_type = '$col53',payment_name = '$col54',payment_address = '$col55',payment_city = '$col56',payment_state = '$col57',payment_postal_code = '$col58',payment_zip_code = '$col59',gst_no = '$col60',pst_no = '$col61',vendor_gst_no = '$col62',payment_information = '$col63',pricing_level = '$col64',unit_no = '$col65',bay_no = '$col66',option_to_renew = '$col67',lease_term_no_of_years = '$col68',commercial_insurer = '$col69',residential_insurer = '$col70',wcb_no = '$col71',deleted = '$col72',horizontal_communication = '$col73',toggle_tile_menu = '$col74',software_tile_menu_choice = '$col75',newsboard_menu_choice = '$col76',software_styler_choice = '$col77',safety_manual_view = '$col78',calendar_color = '$col79' WHERE contactid= '$col0'";
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
	}
	    echo '<script type="text/javascript"> alert("Successfully imported CSV file. Please check the Contacts dashboard to view your freshly edited items."); </script>';
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
					<span style='color:pink;'><img src='../img/warning.png' style='width:25px;'> NOTE</span>: Do not change/move/delete any of the column titles in the first row.<br> <span style='color:lightgreen'><b>Hint</b>:</span> press CTRL+F on your keyboard to find the fields you would like to populate; this will help you locate them faster. <br><br>
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
					<b>1.</b> Please download the following Excel (CSV) file, which will be the current list of all of your Contacts: <a href='add_contacts_multiple.php?type=edit&exp=true' style='color:white; text-decoration:underline;'>Export Contacts</a><br>
					<span style='color:lightgreen'><b>Hint:</b></span> if you would like to edit a specific category from Contacts, export the Excel (CSV) file from this page: <a href='add_contacts_multiple.php?type=export' target="_BLANK" style='color:white; text-decoration:underline;'>Export Specific Contacts</a>.<br><br>
					<b>2.</b> Make your desired changes inside of the Excel file.<br>
					<span style='color:pink;'><img src='../img/warning.png' style='width:25px;'> NOTE</span>: Do not change/move/delete any of the column titles in the first row. Also, do not change the data in the first column (contactid), or else the edits may not go through properly. <br><span style='color:lightgreen'><b>Hint:</b></span> press CTRL+F on your keyboard to find the fields you would like to populate; this will help you locate them faster.<br><br>
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
							<option value="3456780123456971230">All Categories</option>
							<?php
							/*
                            while($row = mysqli_fetch_assoc($sql)){
								echo '<option value="'.$row['category'].'">'.$row['category'].'</option>';
							}
                            */
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

            echo '</table>'; } ?>

		<a href="contacts.php?category=Top" class="btn brand-btn btn-lg">Back</a>
		<!--<a href="#" class="btn brand-btn btn-lg" onclick="history.go(-1);return false;">Back</a>-->

  </div>
<?php include ('../footer.php'); ?>