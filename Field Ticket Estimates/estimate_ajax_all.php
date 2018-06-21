<?php
include ('../database_connection.php');
include ('../function.php');

//Packages
if($_GET['fill'] == 'package_service_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT packageid, category FROM package WHERE service_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'package_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT packageid, heading FROM package WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['packageid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'package_head_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT package FROM rate_card WHERE ratecardid='$ratecardid'"));
        $package = explode('**', $get_rc['package']);

        foreach($package as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM package WHERE packageid='$value'"));
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1].'*'.$get_config['cost'];
}

//Promotion
if($_GET['fill'] == 'promotion_service_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT promotionid, category FROM promotion WHERE service_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'promotion_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT promotionid, heading FROM promotion WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['promotionid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'promotion_head_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT promotion FROM rate_card WHERE ratecardid='$ratecardid'"));
        $promotion = explode('**', $get_rc['promotion']);

        foreach($promotion as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM promotion WHERE promotionid='$value'"));
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1];
}



//Services
if($_GET['fill'] == 's_service_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE service_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 's_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['serviceid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 's_head_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT services FROM rate_card WHERE ratecardid='$ratecardid'"));
        $services = explode('**', $get_rc['services']);

        foreach($services as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM services WHERE serviceid='$value'"));
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1].'*'.$get_config['minimum_billable'].'*'.$get_config['estimated_hours'].'*'.$get_config['actual_hours'].'*'.$get_config['cost'];
}

//PRoducts
if($_GET['fill'] == 'p_product_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT distinct(category) FROM products WHERE product_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'p_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT productid, heading FROM products WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['productid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'p_head_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT products FROM rate_card WHERE ratecardid='$ratecardid'"));
        $products = explode('**', $get_rc['products']);

        foreach($products as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM products WHERE productid='$value'"));
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1].'*'.$get_config['minimum_billable'].'*'.$get_config['estimated_hours'].'*'.$get_config['actual_hours'].'*'.$get_config['cost'];
}

//SR&ED
if($_GET['fill'] == 'sred_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT distinct(category) FROM sred WHERE sred_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'sred_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT sredid, heading FROM sred WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['sredid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'sred_head_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sred FROM rate_card WHERE ratecardid='$ratecardid'"));
        $sred = explode('**', $get_rc['sred']);

        foreach($sred as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM sred WHERE sredid='$value'"));
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1];
}

//Labour
if($_GET['fill'] == 'labour_type_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT labourid, heading FROM labour WHERE labour_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['labourid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'l_head_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour FROM rate_card WHERE ratecardid='$ratecardid'"));
        $labour = explode('**', $get_rc['labour']);

        foreach($labour as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM labour WHERE labourid='$value'"));
    echo $get_config['hourly_rate'].'*'.$rate_card_price[1].'*'.$get_config['cost'];
}

//Custom
if($_GET['fill'] == 'custom_service_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT category FROM custom WHERE service_type = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'custom_cat_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT customid, heading FROM custom WHERE category = '$value'");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['customid']."'>".$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'custom_head_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT custom FROM rate_card WHERE ratecardid='$ratecardid'"));
        $custom = explode('**', $get_rc['custom']);

        foreach($custom as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM custom WHERE customid='$value'"));
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1];
}

//Staff
if($_GET['fill'] == 'st_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT staff FROM rate_card WHERE ratecardid='$ratecardid'"));
        $staff = explode('**', $get_rc['staff']);

        foreach($staff as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$value'"));
    echo $get_config['monthly_rate'].'*'.$get_config['semi_monthly_rate'].'*'.$get_config['daily_rate'].'*'.$get_config['hr_rate_work'].'*'.$get_config['hr_rate_travel'].'*'.$get_config['field_day_cost'].'*'.$get_config['field_day_billable'].'*'.$rate_card_price[1];
}

//Contractor
if($_GET['fill'] == 'cnt_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contractor FROM rate_card WHERE ratecardid='$ratecardid'"));
        $contractor = explode('**', $get_rc['contractor']);

        foreach($contractor as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$value'"));
    echo $get_config['monthly_rate'].'*'.$get_config['semi_monthly_rate'].'*'.$get_config['daily_rate'].'*'.$get_config['hr_rate_work'].'*'.$get_config['hr_rate_travel'].'*'.$get_config['field_day_cost'].'*'.$get_config['field_day_billable'].'*'.$rate_card_price[1];
}

//Client
if($_GET['fill'] == 'cl_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT client FROM rate_card WHERE ratecardid='$ratecardid'"));
        $client = explode('**', $get_rc['client']);

        foreach($client as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$value'"));
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1].'*';

    $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Client' AND deleted=0");
    while($row = mysqli_fetch_array($query)) {
        if ($value == $row['contactid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
        echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
    }
    echo "<option value=''></option>";

    echo '*';
    $query2 = mysqli_query($dbc,"SELECT contactid, first_name, last_name  FROM contacts WHERE category='Client' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
        if ($value == $row2['contactid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
        echo "<option ".$selected." value='". $row2['contactid']."'>".$row2['first_name'].' '.$row2['last_name'].'</option>';
    }
    echo "<option value=''></option>";
}

//Vendor
if($_GET['fill'] == 'vendor_config') {
    $value = $_GET['value'];
    $query = mysqli_query($dbc,"SELECT distinct(pricelist_name) FROM vendor_pricelist WHERE vendorid='$value'");
    echo "<option value=''></option>";
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['pricelist_name']."'>".$row['pricelist_name'].'</option>';
    }
}

if($_GET['fill'] == 'vpricelist_config') {
    $value = $_GET['value'];
    $query = mysqli_query($dbc,"SELECT distinct(category) FROM vendor_pricelist WHERE pricelist_name='$value'");
    echo "<option value=''></option>";
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['category']."'>".$row['category'].'</option>';
    }
}

if($_GET['fill'] == 'vcat_config') {
    $value = $_GET['value'];
    $pricelist = $_GET['pricelist'];
    $query = mysqli_query($dbc,"SELECT pricelistid, name FROM vendor_pricelist WHERE pricelist_name='$pricelist' AND category='$value'");
    echo "<option value=''></option>";
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['pricelistid']."'>".decryptIt($row['name']).'</option>';
    }
}

/* Get Category list
if ( $_GET[ 'fill' ] == 'get_category_list' ) {
    $invId	= trim( $_GET[ 'invId' ] );

	$query = mysqli_query ( $dbc, "SELECT category FROM vendor_price_list WHERE inventoryid='$invId' AND deleted = 0" );
	$category_list = "<option value=''></option>";
    while ( $row = mysqli_fetch_array( $query ) ) {
        $category_list .= "<option value='" . $row[ 'category' ] . "'>" . $row[ 'category' ] . '</option>';
    }
	echo $category_list;
}
*/

//Get Part # & Product name list
if ( $_GET[ 'fill' ] == 'get_parts_products' ) {
    $invId	= trim( $_GET[ 'invId' ] );

	$query = mysqli_query ( $dbc, "SELECT inventoryid, part_no FROM vendor_price_list WHERE inventoryid = '$invId' AND deleted = 0" );
	echo "<option value=''></option>";
    while ( $row = mysqli_fetch_array( $query ) ) {
        echo "<option value='" . $row[ 'inventoryid' ] . "'>" . $row[ 'part_no' ] . '</option>';
    }
	echo '**##**';

	$query = mysqli_query ( $dbc, "SELECT inventoryid, name FROM vendor_price_list WHERE inventoryid = '$invId' AND deleted = 0" );
	echo "<option value=''></option>";
    while ( $row = mysqli_fetch_array( $query ) ) {
        echo "<option value='" . $row[ 'inventoryid' ] . "'>" . $row[ 'name' ] . '</option>';
    }
}

//Get Purchase Order Price
if ( $_GET[ 'fill' ] == 'get_purchase_order_price' ) {
    $prodId	= trim( $_GET[ 'prodId' ] );

	$row = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT inventoryid, purchase_order_price FROM vendor_price_list WHERE inventoryid = '$prodId' AND deleted = 0" ) );
	echo $row[ 'purchase_order_price' ];
}

if($_GET['fill'] == 'vproduct_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT vendor FROM rate_card WHERE ratecardid='$ratecardid'"));
        $vendor = explode('**', $get_rc['vendor']);

        foreach($vendor as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }

    $query = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT cdn_cpu FROM vendor_pricelist WHERE pricelistid='$value'"));
    echo $query['cdn_cpu'].'*'.$rate_card_price[1];
}
//Customer
if($_GET['fill'] == 'cust_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT customer FROM rate_card WHERE ratecardid='$ratecardid'"));
        $customer = explode('**', $get_rc['customer']);

        foreach($customer as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM contacts WHERE contactid='$value'"));
    echo $get_config['final_retail_price'].'*FFM*'.$get_config['admin_price'].'*FFM*'.$get_config['wholesale_price'].'*FFM*'.$get_config['commercial_price'].'*FFM*'.$get_config['client_price'].'*FFM*'.$get_config['msrp'].'*FFM*'.$rate_card_price[1].'*FFM*';

    $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Customer' AND deleted=0");
    while($row = mysqli_fetch_array($query)) {
        if ($value == $row['contactid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
        echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
    }
    echo "<option value=''></option>";

    echo '*FFM*';
    $query2 = mysqli_query($dbc,"SELECT contactid, first_name, last_name  FROM contacts WHERE category='Customer' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
        if ($value == $row2['contactid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
        echo "<option ".$selected." value='". $row2['contactid']."'>".$row2['first_name'].' '.$row2['last_name'].'</option>';
    }
    echo "<option value=''></option>";
}

//Material
if($_GET['fill'] == 'material_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material FROM rate_card WHERE ratecardid='$ratecardid'"));
        $material = explode('**', $get_rc['material']);

        foreach($material as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM material WHERE materialid='$value'"));
    echo $get_config['name'].'*FFM*'.$get_config['width'].'*FFM*'.$get_config['length'].'*FFM*'.$get_config['units'].'*FFM*'.$get_config['unit_weight'].'*FFM*'.$get_config['weight_per_feet'].'*FFM*'.$get_config['price'].'*FFM*'.$rate_card_price[1].'*FFM*';
}

//Inventory
if($_GET['fill'] == 'in_cat_config') {
    $value = $_GET['value'];
    echo "<option value=''></option>";
    $query2 = mysqli_query($dbc,"SELECT category, inventoryid, name  FROM inventory WHERE category='$value' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
        echo "<option value='". $row2['inventoryid']."'>".$row2['name'].'</option>';
    }
}

if($_GET['fill'] == 'in_cat_config_partno') {
    $value = $_GET['value'];
    echo "<option value=''></option>";
    $query2 = mysqli_query($dbc,"SELECT category, inventoryid, part_no  FROM inventory WHERE category='$value' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
        echo "<option value='". $row2['inventoryid']."'>".$row2['part_no'].'</option>';
    }
}

if($_GET['fill'] == 'in_code_part_name_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT inventory FROM rate_card WHERE ratecardid='$ratecardid'"));
        $inventory = explode('**', $get_rc['inventory']);

        foreach($inventory as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM inventory WHERE inventoryid='$value'"));
    echo $get_config['final_retail_price'].'*FFM*'.$get_config['admin_price'].'*FFM*'.$get_config['wholesale_price'].'*FFM*'.$get_config['commercial_price'].'*FFM*'.$get_config['client_price'].'*FFM*'.$get_config['msrp'].'*FFM*'.$rate_card_price[1].'*FFM*'.$get_config['cost'];
}

if($_GET['fill'] == 'in_code_part_name_number') {
    $value = $_GET['value'];
    echo "<option value=''></option>";
    $query2 = mysqli_query($dbc,"SELECT category, inventoryid, part_no  FROM inventory WHERE inventoryid='$value' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
		$category_name = $row2['category'];
    }
	$query3 = mysqli_query($dbc,"SELECT category, inventoryid, part_no  FROM inventory WHERE category='$category_name' AND deleted=0");
    while($row3 = mysqli_fetch_array($query3)) {
		if($row3['inventoryid'] == $value) {
			$selected = 'selected';
		} else { $selected = ''; }
        echo "<option ".$selected." value='". $row3['inventoryid']."'>".$row3['part_no'].'</option>';
    }
}

if($_GET['fill'] == 'in_code_part_no_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT inventory FROM rate_card WHERE ratecardid='$ratecardid'"));
        $inventory = explode('**', $get_rc['inventory']);

        foreach($inventory as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM inventory WHERE inventoryid='$value'"));
    echo $get_config['final_retail_price'].'*FFM*'.$get_config['admin_price'].'*FFM*'.$get_config['wholesale_price'].'*FFM*'.$get_config['commercial_price'].'*FFM*'.$get_config['client_price'].'*FFM*'.$get_config['msrp'].'*FFM*'.$rate_card_price[1].'*FFM*';
}

if($_GET['fill'] == 'in_code_part_no_config_name') {
    $value = $_GET['value'];
    echo "<option value=''></option>";
    $query2 = mysqli_query($dbc,"SELECT category, inventoryid, name  FROM inventory WHERE inventoryid='$value' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
		$category_name = $row2['category'];
    }
	$query3 = mysqli_query($dbc,"SELECT category, inventoryid, name  FROM inventory WHERE category='$category_name' AND deleted=0");
    while($row3 = mysqli_fetch_array($query3)) {
		if($row3['inventoryid'] == $value) {
			$selected = 'selected';
		} else { $selected = ''; }
        echo "<option ".$selected." value='". $row3['inventoryid']."'>".$row3['name'].'</option>';
    }
}

//Equipment
if($_GET['fill'] == 'eq_cat_config') {
    $value = $_GET['value'];
    $query = mysqli_query($dbc,"SELECT category, equipmentid, unit_number, serial_number FROM equipment WHERE category='$value' AND deleted=0");
    echo "<option value=''></option>";
    while($row = mysqli_fetch_array($query)) {
        echo "<option value='". $row['equipmentid']."'>".$row['unit_number'].' : '.$row['serial_number'].'</option>';
    }
}

if($_GET['fill'] == 'eq_un_sn_config') {
    $value = $_GET['value'];
    $rate_card_price = 0;
    if(!empty($_GET['ratecardid'])) {
        $ratecardid = $_GET['ratecardid'];
        $get_rc = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT equipment FROM rate_card WHERE ratecardid='$ratecardid'"));
        $equipment = explode('**', $get_rc['equipment']);

        foreach($equipment as $pp){
            if (strpos('#'.$pp, '#'.$value.'#') !== false) {
                $rate_card_price = explode('#', $pp);
            }
        }
    }
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM equipment WHERE equipmentid='$value'"));
    echo $get_config['monthly_rate'].'*'.$get_config['semi_monthly_rate'].'*'.$get_config['daily_rate'].'*'.$get_config['hr_rate_work'].'*'.$get_config['hr_rate_travel'].'*'.$get_config['field_day_cost'].'*'.$get_config['field_day_billable'].'*'.$rate_card_price[1].'*'.$get_config['cost'];
}

//Expenses
if($_GET['fill'] == 'e_head_config') {
    $value = $_GET['value'];
	$query = mysqli_query($dbc,"SELECT distinct(amount) FROM expense WHERE staff = '$value'");
    $amount = 0;
	while($row = mysqli_fetch_array($query)) {
		$amount += $row['amount'];
	}

    echo $amount;
}

//Bid

if($_GET['fill'] == 'quote_followup') {
	$estimateid = $_GET['id'];
    $follow_up_date = $_GET['name'];
    $fname = $_GET['fname'];
    $lname = $_GET['lname'];
    $history = $fname.' '.$lname.' Set Follow up Date on '.date('Y-m-d H:i:s').'<br>';
	$query_update_project = "UPDATE `bid` SET follow_up_date='$follow_up_date', `history` = CONCAT(history,'$history') WHERE `estimateid` = '$estimateid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

    $estimate_name = get_estimate($dbc, $estimateid, 'estimate_name');
    $contactid = $_GET['contactid'];
    echo insert_day_overview($dbc, $contactid, 'Cost Estimate', date('Y-m-d'), '', 'Set Follow up Date '.$follow_up_date.' for  '.$estimate_name);
}

if($_GET['fill'] == 'quote_status') {
	$estimateid = $_GET['estimateid'];
    $status = $_GET['status'];

    $estimate_name = get_estimate($dbc, $estimateid, 'estimate_name');
    $contactid = $_GET['contactid'];
    echo insert_day_overview($dbc, $contactid, 'Cost Estimate', date('Y-m-d'), '', 'Set Status '.$status.' for  '.$estimate_name);

    $fname = $_GET['fname'];
    $lname = $_GET['lname'];
    $deleted=0;
    if($status == 'Archive/Delete') {
        $deleted = 1;
    }

    $history = $fname.' '.$lname.' Set Status to '.$status.' on '.date('Y-m-d H:i:s').'<br>';

    if($status == 'Move To Estimate') {
        $status = 'Submitted';
    }

	$query_update_project = "UPDATE `bid` SET deleted='$deleted', status='$status', `history` = CONCAT(history,'$history') WHERE `estimateid` = '$estimateid'";
	$result_update_project = mysqli_query($dbc, $query_update_project);

    $start_date = date('Y-m-d');

    if($status == 'Approved Quote') {
        $query_insert_invoice = "INSERT INTO `project` (`estimateid`, `businessid`, `clientid`, `projecttype`, `status`, `ratecardid`, `project_name`, `package`,`promotion`,`material`,`services`,`sred`,`labour`,`client`,`customer`,`inventory`, `equipment`, `staff`,`contractor`,`expense`,`vendor`,`custom`,`other_detail`, `created_date`, `start_date`, `approved_date`)
        SELECT  $estimateid,
                businessid,
                clientid,
                'client',
                'Approve as Project',
                ratecardid,
                estimate_name,
                package,
                promotion, material, services, sred, labour, client, customer, inventory, equipment, staff, contractor, expense, vendor, custom, other_detail, '$start_date', '$start_date', '$start_date'
        from estimate WHERE estimateid = '$estimateid'";
        $result_insert_invoice = mysqli_query($dbc, $query_insert_invoice);
        $projectid = mysqli_insert_id($dbc);

        $query_insert_detail = "INSERT INTO `project_detail` (`projectid`) VALUES ('$projectid')";
        $result_insert_detail = mysqli_query($dbc, $query_insert_detail);


	    $query_update_project = "UPDATE `temp_ticket` SET projectid='$projectid' WHERE `quoteid` = '$estimateid'";
	    $result_update_project = mysqli_query($dbc, $query_update_project);

        //$query_insert_customer = "INSERT INTO `project` (`estimateid`, `clientid`, `projecttype`, `status`) VALUES ('$estimateid', '$clientid', 'Client', 'Approved')";
        //$result_insert_customer = mysqli_query($dbc, $query_insert_customer);
    }
}

?>



