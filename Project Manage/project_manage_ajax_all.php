<?php
include ('../database_connection.php');
include ('../function.php');
include ('../global.php');
date_default_timezone_set('America/Denver');

if($_GET['fill'] == 'projectname') {
	$businessid = $_GET['businessid'];

    $query = mysqli_query($dbc,"SELECT contactid, name, first_name, last_name, category FROM contacts WHERE businessid='$businessid'");
    echo '<option value="">Please Select</option>';
    while($row = mysqli_fetch_array($query)) {
        if($cat != $row['category']) {
            echo '<optgroup label="'.$row['category'].'">';
            $cat = $row['category'];
        }
        echo "<option value='". $row['contactid']."'>".$row['name'],' '.decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
    }

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT projectid, project_name FROM project WHERE clientid = '$businessid' OR businessid='$businessid'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['projectid']."'>".$row['project_name'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT ticketid, service_type, heading FROM tickets WHERE status!='Archive' AND businessid='$businessid'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['ticketid']."'>".$row['service_type'].' : '.$row['heading'].'</option>';
	}
}

if($_GET['fill'] == 'project_path_milestone') {
    $project_path = $_GET['project_path'];
	echo '<option value=""></option>';
    $each_tab = explode('#*#', get_project_path_milestone($dbc, $project_path, 'milestone'));
    $timeline = explode('#*#', get_project_path_milestone($dbc, $project_path, 'timeline'));
    $j=0;
    foreach ($each_tab as $cat_tab) {
        echo "<option value='". $cat_tab."'>".$cat_tab.' : '.$timeline[$j].'</option>';
        $j++;
    }
}

if($_GET['fill'] == 'ticketservice') {
	$service_type = $_GET['service_type'];
	$service_type = str_replace("__","&",$service_type);

	$query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE REPLACE(`service_type`, ' ', '') = '$service_type'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['category']."'>".$row['category'].'</option>';
	}
}

if($_GET['fill'] == 'ticketheading') {
	$service_type = $_GET['service_type'];
	$service_type = str_replace("__","&",$service_type);

	$service_category = $_GET['service_category'];
	$service_category = str_replace("__","&",$service_category);

	$query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE REPLACE(`service_type`, ' ', '') = '$service_type' AND REPLACE(`category`, ' ', '') = '$service_category'");
	echo '<option value="">Please Select</option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['serviceid']."'>".$row['heading'].'</option>';
	}
}

// Package etc

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
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1];
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
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1].'*'.$get_config['minimum_billable'].'*'.$get_config['estimated_hours'].'*'.$get_config['actual_hours'];
}

//Products
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
    echo $get_config['final_retail_price'].'*'.$get_config['admin_price'].'*'.$get_config['wholesale_price'].'*'.$get_config['commercial_price'].'*'.$get_config['client_price'].'*'.$get_config['msrp'].'*'.$rate_card_price[1].'*'.$get_config['minimum_billable'].'*'.$get_config['estimated_hours'].'*'.$get_config['actual_hours'];
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
    echo $get_config['hourly_rate'].'*'.$rate_card_price[1];
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
        echo "<option ".$selected." value='". $row['contactid']."'>".$row['name'].'</option>';
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
        echo "<option value='". $row['pricelistid']."'>".$row['name'].'</option>';
    }
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
        echo "<option ".$selected." value='". $row['contactid']."'>".$row['name'].'</option>';
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

if($_GET['fill'] == 'in_code_part_name_config_number') {
    $value = $_GET['value'];
    echo "<option value=''></option>";
	$query2 = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT category FROM inventory WHERE inventoryid='$value' AND deleted=0"));
	$categie = $query2['category'];
    $query2 = mysqli_query($dbc,"SELECT category, inventoryid, part_no  FROM inventory WHERE category='$categie' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
		if($value == $row2['inventoryid']) {
			$selected = 'selected';
		} else {
			$selected = '';
		}
        echo "<option ".$selected." value='". $row2['inventoryid']."'>".$row2['part_no'].'</option>';
    }
}

if($_GET['fill'] == 'in_code_part_no_config_name') {
    $value = $_GET['value'];
    echo "<option value=''></option>";
	$query2 = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT category FROM inventory WHERE inventoryid='$value' AND deleted=0"));
	$categie = $query2['category'];
    $query2 = mysqli_query($dbc,"SELECT category, inventoryid, name  FROM inventory WHERE category='$categie' AND deleted=0");
    while($row2 = mysqli_fetch_array($query2)) {
		if($value == $row2['inventoryid']) {
			$selected = 'selected';
		} else {
			$selected = '';
		}
        echo "<option ".$selected." value='". $row2['inventoryid']."'>".$row2['name'].'</option>';
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
    echo $get_config['final_retail_price'].'*FFM*'.$get_config['admin_price'].'*FFM*'.$get_config['wholesale_price'].'*FFM*'.$get_config['commercial_price'].'*FFM*'.$get_config['client_price'].'*FFM*'.$get_config['msrp'].'*FFM*';
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
    echo $get_config['final_retail_price'].'*FFM*'.$get_config['admin_price'].'*FFM*'.$get_config['wholesale_price'].'*FFM*'.$get_config['commercial_price'].'*FFM*'.$get_config['client_price'].'*FFM*'.$get_config['msrp'].'*FFM*';
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
    echo $get_config['monthly_rate'].'*'.$get_config['semi_monthly_rate'].'*'.$get_config['daily_rate'].'*'.$get_config['hr_rate_work'].'*'.$get_config['hr_rate_travel'].'*'.$get_config['field_day_cost'].'*'.$get_config['field_day_billable'].'*'.$rate_card_price[1];
}

if($_GET['fill'] == 'starttickettimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $start_time = time();
	//$query_update_project = "UPDATE `tickets` SET start_time='$start_time' WHERE `projectmanageid` = '$projectmanageid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);

    $start_timer_time = date('g:i A');
	$created_date = date('Y-m-d');
    $created_by = $_SESSION['contactid'];

    //$created_by = $_GET['login_contactid'];

    $query_insert_client_doc = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`) VALUES ('$projectmanageid', 'Work', '$start_timer_time', '$created_date', '$created_by', '$start_time')";
    $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
}

if($_GET['fill'] == 'pausetickettimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    //$created_by = $_GET['login_contactid'];
    $created_by = $_SESSION['contactid'];
    $end_time = date('g:i A');
    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_ticket = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Work' AND end_time IS NULL";
        $result_update_ticket = mysqli_query($dbc, $query_update_ticket);

        $query_insert_client_doc = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`) VALUES ('$projectmanageid', 'Break', '$end_time', '$created_date', '$created_by', '$start_time')";
        $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

        //$query_update_ticket = "UPDATE `tickets` SET `start_time` = '0' WHERE `projectmanageid` = '$projectmanageid'";
        //$result_update_ticket = mysqli_query($dbc, $query_update_ticket);
    }

	//$query_update_project = "UPDATE `tickets` SET start_time='$start_time' WHERE `projectmanageid` = '$projectmanageid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'resumetickettimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    //$created_by = $_GET['login_contactid'];
    $created_by = $_SESSION['contactid'];
    $end_time = date('g:i A');
    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_ticket = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Break' AND end_time IS NULL";
        $result_update_ticket = mysqli_query($dbc, $query_update_ticket);

        $query_insert_client_doc = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`) VALUES ('$projectmanageid', 'Work', '$end_time', '$created_date', '$created_by', '$start_time')";
        $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

        //$query_update_ticket = "UPDATE `tickets` SET `start_time` = '0' WHERE `projectmanageid` = '$projectmanageid'";
        //$result_update_ticket = mysqli_query($dbc, $query_update_ticket);
    }

	//$query_update_project = "UPDATE `tickets` SET start_time='$start_time' WHERE `projectmanageid` = '$projectmanageid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'startworkordertimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $start_time = time();
	//$query_update_project = "UPDATE `workorder` SET start_time='$start_time' WHERE `projectmanageid` = '$projectmanageid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);

    $start_timer_time = date('g:i A');
	$created_date = date('Y-m-d');
    //$created_by = $_GET['timer_contactid'];
    $created_by = $_SESSION['contactid'];
    $timer_task = $_GET['timer_task'];

    $query_insert_client_doc = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`, `timer_task`) VALUES ('$projectmanageid', 'Work', '$start_timer_time', '$created_date', '$created_by', '$start_time', '$timer_task')";
    $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);
}

if($_GET['fill'] == 'pauseworkordertimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    //$created_by = $_GET['timer_contactid'];
    $created_by = $_SESSION['contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Work' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);

        $query_insert_client_doc = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`, `timer_task`) VALUES ('$projectmanageid', 'Break', '$end_time', '$created_date', '$created_by', '$start_time', '$timer_task')";
        $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

        //$query_update_workorder = "UPDATE `workorder` SET `start_time` = '0' WHERE `projectmanageid` = '$projectmanageid'";
        //$result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }

	//$query_update_project = "UPDATE `workorder` SET start_time='$start_time' WHERE `projectmanageid` = '$projectmanageid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);
}

if($_GET['fill'] == 'endworkordertimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    //$created_by = $_GET['timer_contactid'];
    $created_by = $_SESSION['contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Break' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }
}

if($_GET['fill'] == 'enddayworkordertimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    //$created_by = $_GET['timer_contactid'];
    $created_by = $_SESSION['contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$created_date' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }
}

if($_GET['fill'] == 'resumeworkordertimer') {
	$projectmanageid = $_GET['projectmanageid'];
    $timer = $_GET['timer_value'];
    $start_time = time();
    $created_date = date('Y-m-d');
    //$created_by = $_GET['timer_contactid'];
    $created_by = $_SESSION['contactid'];
    $end_time = date('g:i A');
    $timer_task = $_GET['timer_task'];

    if($timer != '0' && $timer != '00:00:00' && $timer != '') {
        $query_update_workorder = "UPDATE `project_manage_assign_to_timer` SET `end_time` = '$end_time', `timer` = '$timer' WHERE `projectmanageid` = '$projectmanageid' AND created_by='$created_by' AND created_date='$created_date' AND timer_type='Break' AND end_time IS NULL";
        $result_update_workorder = mysqli_query($dbc, $query_update_workorder);

        $query_insert_client_doc = "INSERT INTO `project_manage_assign_to_timer` (`projectmanageid`, `timer_type`, `start_time`, `created_date`, `created_by`, `start_timer_time`, `timer_task`) VALUES ('$projectmanageid', 'Work', '$end_time', '$created_date', '$created_by', '$start_time', '$timer_task')";
        $result_insert_client_doc = mysqli_query($dbc, $query_insert_client_doc);

        //$query_update_workorder = "UPDATE `workorder` SET `start_time` = '0' WHERE `projectmanageid` = '$projectmanageid'";
        //$result_update_workorder = mysqli_query($dbc, $query_update_workorder);
    }

	//$query_update_project = "UPDATE `workorder` SET start_time='$start_time' WHERE `projectmanageid` = '$projectmanageid'";
	//$result_update_project = mysqli_query($dbc, $query_update_project);
}
?>