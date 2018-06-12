<?php
include ('../database_connection.php');
include ('../function.php');
include ('../global.php');
error_reporting(0);
if($_GET['fill'] == 'posPromotion') {
    $pro = $_GET['pro'];
    $qty = $_GET['qty'];
    $productPrice = $_GET['productPrice'];

    $pos_promotion = explode('*#*',get_config($dbc, 'sales_order_promotion'));
    $total_promo_count = mb_substr_count(get_config($dbc, 'sales_order_promotion'),'*#*');
    $pos_promo_inventory = array();
    for($eq_loop=0; $eq_loop<=$total_promo_count; $eq_loop++) {
        $pos_promotion_data = explode('**',$pos_promotion[$eq_loop]);

        if($pos_promotion_data[0] == $pro) {
            if($qty >= $pos_promotion_data[1]) {
                $pricing =  $pos_promotion_data[2];
                echo get_inventory($dbc, $pro, $pricing);
            } else {
                echo get_inventory($dbc, $pro, $productPrice);
            }
        }
    }
}

if($_GET['fill'] == 'POSclient') {
    $contactid = $_GET['clientid'];
    $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT contactid, pricing_level, client_tax_exemption, tax_exemption_number  FROM contacts WHERE contactid='$contactid'"));
    $ship_address = get_ship_address($dbc, $contactid);
    echo $result['pricing_level'].'**'.$result['client_tax_exemption'].'**'.$result['tax_exemption_number'].'**'.$ship_address;
}

if($_GET['fill'] == 'posFromCategory') {
    $name = $_GET['name'];

	$query = mysqli_query($dbc,"SELECT inventoryid, part_no, name FROM inventory WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['inventoryid']."'>".$row['part_no'].'</option>';
	}
    echo '*#*';
	$query = mysqli_query($dbc,"SELECT inventoryid, part_no, name FROM inventory WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['inventoryid']."'>".decryptIt($row['name']).'</option>';
	}
}
// VPL
if($_GET['fill'] == 'posFromCategoryvpl') {
    $name = $_GET['name'];

	$query = mysqli_query($dbc,"SELECT inventoryid, part_no, name FROM vendor_price_list WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['inventoryid']."'>".$row['part_no'].'</option>';
	}
    echo '*#*';
	$query = mysqli_query($dbc,"SELECT inventoryid, part_no, name FROM vendor_price_list WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['inventoryid']."'>".decryptIt($row['name']).'</option>';
	}
}
// SERVICES
if($_GET['fill'] == 'posFromCategoryserv') {
    $name = $_GET['name'];

	$query = mysqli_query($dbc,"SELECT serviceid, service_type, heading FROM services WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['serviceid']."'>".$row['service_type'].'</option>';
	}
    echo '*#*';

	$query = mysqli_query($dbc,"SELECT serviceid, service_type, heading FROM services WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['serviceid']."'>".$row['heading'].'</option>';
	}
}
// PRODUCTS
if($_GET['fill'] == 'posFromCategoryprod') {
    $name = $_GET['name'];

	$query = mysqli_query($dbc,"SELECT productid, product_type, heading FROM products WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['productid']."'>".$row['product_type'].'</option>';
	}
    echo '*#*';
	$query = mysqli_query($dbc,"SELECT productid, product_type, heading FROM products WHERE category='$name' AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
		echo "<option value='".$row['productid']."'>".$row['heading'].'</option>';
	}
}
// PRODUCTS VV
if($_GET['fill'] == 'posUpProductFromProductprod') {
    $inventoryid = $_GET['inventoryid'];
    $productPrice = $_GET['productPrice'];
    //$category = $_GET['category'];

        $category = get_products($dbc, $inventoryid, 'category');

	$query = mysqli_query($dbc,"SELECT distinct(category) FROM products WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	$result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM products WHERE productid='$inventoryid' AND include_in_so != ''"));
	while($row = mysqli_fetch_array($query)) {
        if ($result['category'] == $row['category']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['category']."'>".$row['category'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT productid, product_type FROM products WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['productid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['productid']."'>".$row['product_type'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT productid, heading FROM products WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['productid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['productid']."'>".$row['heading'].'</option>';
	}

    echo '**##**';

    $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT $productPrice FROM products WHERE productid='$inventoryid' AND include_in_so != ''"));
	if($result[$productPrice] == '' || $result[$productPrice] == NULL ) {
		echo '0';
	} else {
		echo $result[$productPrice];
	}

}
// FOR SERVICES VV
if($_GET['fill'] == 'posUpProductFromProductserv') {
    $inventoryid = $_GET['inventoryid'];
    $productPrice = $_GET['productPrice'];
   // $category = $_GET['category'];

        $category = get_services($dbc, $inventoryid, 'category');

	$query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	$result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT category FROM services WHERE serviceid='$inventoryid' AND include_in_so != ''"));
	while($row = mysqli_fetch_array($query)) {
        if ($result['category'] == $row['category']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['category']."'>".$row['category'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT serviceid, service_type FROM services WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['serviceid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['serviceid']."'>".$row['service_type'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['serviceid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['serviceid']."'>".$row['heading'].'</option>';
	}

    echo '**##**';

    $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT $productPrice FROM services WHERE serviceid='$inventoryid' AND include_in_so != ''"));
	if($result[$productPrice] == '' || $result[$productPrice] == NULL ) {
		echo '0';
	} else {
		echo $result[$productPrice];
	}
}
// FOR INVENTORY VV
if($_GET['fill'] == 'posUpProductFromProduct') {
    $inventoryid = $_GET['inventoryid'];
    $productPrice = $_GET['productPrice'];
	$type = $_GET['type'];
    //$category = $_GET['category'];
if($type == 'original') {
        $category = get_inventory($dbc, $inventoryid, 'category');

	$query = mysqli_query($dbc,"SELECT distinct(category) FROM inventory WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($category == $row['category']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['category']."'>".$row['category'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM inventory WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['inventoryid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['inventoryid']."'>".$row['part_no'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT inventoryid, name FROM inventory WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['inventoryid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['inventoryid']."'>".decryptIt($row['name']).'</option>';
	}

    echo '**##**';

    $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT $productPrice FROM inventory WHERE inventoryid='$inventoryid' AND include_in_so != ''"));
	if($result[$productPrice] == '' || $result[$productPrice] == NULL ) {
		echo '0';
	} else {
		echo $result[$productPrice];
	}
	
} else if($type == 'orderlist') {
	$result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT sales_order_price FROM inventory WHERE inventoryid='$inventoryid' AND include_in_so != ''"));
	if($result['sales_order_price'] == '' || $result[$productPrice] == NULL ) {
		echo '0';
	} else {
		echo $result['sales_order_price'];
	}
}

}

// FOR VPL VV
if($_GET['fill'] == 'posUpProductFromProductvpl') {
    $inventoryid = $_GET['inventoryid'];
    $productPrice = $_GET['productPrice'];
    //$category = $_GET['category'];
        $category = get_vpl($dbc, $inventoryid, 'category');
	$query = mysqli_query($dbc,"SELECT distinct(category) FROM vendor_price_list WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($category == $row['category']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option class='".$category."' ".$selected." value='".$row['category']."'>".$row['category'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM vendor_price_list WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['inventoryid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['inventoryid']."'>".$row['part_no'].'</option>';
	}

    echo '**##**';

	$query = mysqli_query($dbc,"SELECT inventoryid, name FROM vendor_price_list WHERE deleted=0 AND include_in_so != ''");
	echo '<option value=""></option>';
	while($row = mysqli_fetch_array($query)) {
        if ($inventoryid == $row['inventoryid']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }
		echo "<option ".$selected." value='".$row['inventoryid']."'>".decryptIt($row['name']).'</option>';
	}

    echo '**##**';

    $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT $productPrice FROM vendor_price_list WHERE inventoryid='$inventoryid' AND include_in_so != ''"));
	if($result[$productPrice] == '' || $result[$productPrice] == NULL ) {
		echo '0';
	} else {
		echo $result[$productPrice];
	}

}

///
if($_GET['fill'] == 'POSstatus') {
    $name = $_GET['name'];
    $status = $_GET['status'];
    $status_history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Changed to '.$status.' on '.date('Y-m-d');
	if($status == 'Archived') {
		$status_history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Changed to Archived on '.date('Y-m-d');
		$query_update = "UPDATE `sales_order` SET deleted = '1', status_history = '$status_history' WHERE posid='$name'";
	} else {
		$query_update = "UPDATE `sales_order` SET status = '$status', status_history = '$status_history' WHERE posid='$name'";
	}
    $result_update = mysqli_query($dbc, $query_update);
}

if($_GET['fill'] == 'customer') {
    $name = $_GET['name'];
    $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM customer WHERE customerid='$name'"));
	echo $result['phone'].'#$#'.$result['customer'].'#$#'.$result['email'].'#$#'.$result['reference'].'#$#'.$result['pricing'];
}

if($_GET['fill'] == 'pay_po') {
    $val = $_GET['val'];
	$id = $_GET['id'];
	$query_update = "UPDATE `sales_order_product` SET total_paid = '$val' WHERE posproductid='$id'";
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_update));
}

if($_GET['fill'] == 'rec_po') {
    $val = $_GET['val'];
	$id = $_GET['id'];
	$query_update = "UPDATE `sales_order_product` SET qty_received = '$val' WHERE posproductid='$id'";
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_update));
}

if($_GET['fill'] == 'pay_gst') {
    $val = $_GET['val'];
	$id = $_GET['id'];
	$query_update = "UPDATE `sales_order` SET gst_paid = '$val' WHERE posid='$id'";
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_update));
}

if($_GET['fill'] == 'pay_pst') {
    $val = $_GET['val'];
	$id = $_GET['id'];
	$query_update = "UPDATE `sales_order` SET gst_paid = '$val' WHERE posid='$id'";
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_update));
}

if($_GET['fill'] == 'pay_del') {
    $val = $_GET['val'];
	$id = $_GET['id'];
	$query_update = "UPDATE `sales_order` SET delivery_paid = '$val' WHERE posid='$id'";
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_update));
}
if($_GET['fill'] == 'pay_asse') {
    $val = $_GET['val'];
	$id = $_GET['id'];
	$query_update = "UPDATE `sales_order` SET assembly_paid = '$val' WHERE posid='$id'";
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_update));
}
if($_GET['fill'] == 'change_id') {
    $val = $_GET['val'];
	$id = $_GET['id'];
	$query_update = "UPDATE `sales_order` SET new_id_number = '$val' WHERE posid='$id'";
    $result = mysqli_fetch_assoc(mysqli_query($dbc, $query_update));

}
if($_GET['fill'] == 'posUpProductPriceFromProductprod') {
    $productid = $_GET['productid'];
    $productPrice = $_GET['productPrice'];
    $result = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT $productPrice  FROM products WHERE productid='$productid'"));
    echo $result[$productPrice];
}
?>