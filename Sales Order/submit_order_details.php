<?php
$sot = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_order_temp` WHERE `sotid` = '$sotid'"));
$so_type = $sot['sales_order_type'];
$sales_order_name = $sot['name'];
$primary_staff = $sot['primary_staff'];
$assign_staff = $sot['assign_staff'];
$customerid = $sot['customerid'];
$classification = $sot['classification'];
$business_contact = $sot['business_contact'];
$security_option = $sot['security_option'];
$discount_type = $sot['discount_type'];
$discount_value = $sot['discount_value'];
$delivery_type = $sot['delivery_type'];
$delivery_address = $sot['delivery_address'];
$contractorid = $sot['contractorid'];
$delivery_amount = $sot['delivery_amount'];
$assembly_amount = $sot['assembly_amount'];
$payment_type = $sot['payment_type'];
$deposit_paid = $sot['deposit_paid'];
$comment = $sot['comment'];
$ship_date = $sot['ship_date'];
$due_date = $sot['due_date'];
$frequency = $sot['frequency'];
$frequency_type = $sot['frequency_type'];
$next_action = $sot['next_action'];
$next_action_date = $sot['next_action_date'];
$templateid = $sot['templateid'];
$copied_sotid = $sot['copied_sotid'];

if(!empty($classification)) {
    $classification_query = " AND `classification` = '$classification'";
} else {
    $classification_query = "";
}

$history .= 'Submitted as Completed Order<br />';
$invoice_date   = date('Y-m-d');
$created_by     = $_SESSION['contactid'];

// GST PST
$get_pos_tax = get_config($dbc, 'sales_order_tax');
$pdf_tax = '';
$gst_total = 0;
$pst_total = 0;
if($get_pos_tax != '') {
    $pos_tax = explode('*#*',$get_pos_tax);

    $total_count = mb_substr_count($get_pos_tax,'*#*');
    for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
        $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);

        if (strcasecmp($pos_tax_name_rate[0], 'gst') == 0 && $pos_tax_name_rate[3] != 'Yes') {
            $gst_total = $gst_total + $pos_tax_name_rate[1];
        }

        if (strcasecmp($pos_tax_name_rate[0], 'pst') == 0 && $pos_tax_name_rate[3] != 'Yes') {
            $pst_total = $pst_total + $pos_tax_name_rate[1];
        }
    }
}

$subtotal = 0;

// Insert Sales Order
mysqli_query($dbc, "INSERT INTO `sales_order` (`invoice_date`, `created_by`) VALUES ('$invoice_date', '$created_by')");
$posid = mysqli_insert_id($dbc);

// Products
$product_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_product_temp` WHERE `parentsotid` = '$sotid'"),MYSQLI_ASSOC);
foreach ($product_list as $product) {
    mysqli_query($dbc, "INSERT INTO `sales_order_product` (`posid`, `inventoryid`, `type_category`, `contact_category`, `heading_name`, `templateid`, `copied_sotid`, `heading_sortorder`, `sortorder`, `time_estimate`) VALUES ('$posid', '".$product['item_type_id']."', '".$product['item_type']."', '".$product['contact_category']."', '".$product['heading_name']."', '".$product['templateid']."', '".$product['copied_sotid']."', '".$product['heading_sortorder']."', '".$product['sortorder']."', '$time_estimate')");
    $posproductid = mysqli_insert_id($dbc);

    $cat_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_so_contacts` WHERE `sales_order_type` = '$so_type'"),MYSQLI_ASSOC);
    if(!empty($cat_config)) {
        $contact_categories = [];
        foreach ($cat_config as $contact_cat) {
            $contact_categories[] = $contact_cat['contact_category'];
        }
        $contact_categories_query = " AND `category` IN ('".implode("','", $contact_categories)."')";
        $contact_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `deleted` = 0 AND `status` > 0".$contact_categories_query.$classification_query),MYSQLI_ASSOC));
        $contact_list_query = " AND `contactid` IN ('".implode("','", $contact_list)."')";
    } else {
        $contact_list_query = " AND `contactid` = '$customerid'";
    }

    $quantity_total = 0;
    $product_details_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_product_details_temp` WHERE `parentsotid` = '".$product['sotid']."'".$contact_list_query),MYSQLI_ASSOC);
    foreach ($product_details_list as $product_details) {
        if ($product_details['quantity'] > 0) {
            $quantity_total += $product_details['quantity'];
            mysqli_query($dbc, "INSERT INTO `sales_order_product_details` (`contactid`, `posproductid`, `quantity`) VALUES ('".$product_details['contactid']."', '".$posproductid."', '".$product_details['quantity']."')");
        }
    }
    $product_price = number_format($product['item_price'], 2, '.', '');
    $product_price_total = number_format(($quantity_total * $product_price), 2, '.', '');
    $gst_price = number_format($product_price_total * ($gst_total / 100), 2, '.', '');
    $pst_price = number_format($product_price_total * ($pst_total / 100), 2, '.', '');
    if ($quantity_total > 0) {
        mysqli_query($dbc, "UPDATE `sales_order_product` SET `price` = '$product_price', `quantity` = '$quantity_total', `gst` = '$gst_price', `pst` = '$pst_price' WHERE `posproductid` = '$posproductid'");
    } else {
        mysqli_query($dbc, "DELETE FROM `sales_order_product` WHERE `posproductid` = '$posproductid'");
    }
    $subtotal = $subtotal + $product_price_total;
}

// Subtotal
$subtotal = number_format($subtotal, 2, '.', '');

// Total After Discount
if ($discount_type == '%' && $discount_value > 0) {
    $total_after_discount = number_format($subtotal - ($discount_type * $discount_value / 100), 2, '.', '');
} else if ($discount_type == '$' && $discount_value > 0) {
    $total_after_discount = number_format($subtotal - $discount_value, 2, '.', '');
} else {
    $total_after_discount = number_format($subtotal, 2, '.', '');
}

// Total Before Tax
$total_before_tax = number_format($total_after_discount + ($delivery_amount > 0 ? $delivery_amount : 0) + ($assembly_amount > 0 ? $assembly_amount : 0), 2, '.', '');

// Taxes
$total_gst = number_format($total_before_tax * ($gst_total / 100), 2, '.', '');
$total_pst = number_format($total_before_tax * ($pst_total / 100), 2, '.', '');

// Total Price
$total_price = number_format($total_before_tax + $total_gst + $total_pst, 2, '.', '');

// Update Sales Order
mysqli_query($dbc, "UPDATE `sales_order` SET `sales_order_type` = '$so_type', `name` = '$sales_order_name', `primary_staff` = '$primary_staff', `assign_staff` = '$assign_staff', `invoice_date` = '$invoice_date', `contactid` = '$customerid', `classification` = '$classification', `business_contact` = '$business_contact', `sub_total` = '$subtotal', `total_after_discount` = '$total_after_discount', `gst` = '$total_gst', `pst` = '$total_pst', `total_before_tax` = '$total_before_tax', `total_price` = '$total_price', `discount_type` = '$discount_type', `discount_value` = '$discount_value', `delivery_type` = '$delivery_type', `delivery_address` = '$delivery_address', `contractorid` = '$contractorid', `delivery` = '$delivery_amount', `assembly` = '$assembly_amount', `payment_type` = '$payment_type', `deposit_paid` = '$deposit_paid', `comment` = '$comment', `ship_date` = '$ship_date', `due_date` = '$due_date', `frequency` = '$frequency', `frequency_type` = '$frequency_type', `status` = 'Complete', `sotid` = '$sotid', `next_action` = '$next_action', `next_action_date` = '$next_action_date', `templateid` = '$templateid', `copied_sotid` = '$copied_sotid' WHERE `posid` = '$posid'");

//Update the Contact's Payment Frequency from the Sales Order
$dbc->query("INSERT INTO `contacts_cost` (`contactid`) SELECT `table`.`id` FROM (SELECT '$customerid' `id`) `table` LEFT JOIN `contacts_cost` ON `contacts_cost`.`contactid`=`table`.`id` WHERE `contacts_cost`.`contactid` IS NULL AND `table`.`id` > 0 HAVING COUNT(`contacts_cost`.`contactid`)=0");
$contact_freq = $frequency;
switch($frequency_type) {
	case 'Days': $contact_freq .= ':CUSTDY'; break;
	case 'Weeks': $contact_freq .= ':CUSTWK'; break;
	case 'Months': $contact_freq .= ':CUSTMN'; break;
}
$dbc->query("UPDATE `contacts_cost` SET `payment_frequency`='$contact_freq' WHERE `contactid`='$customerid'");

include('../Sales Order/check_customer_rate_card.php');

// Add Uploads
$get_uploads = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_upload_temp` WHERE `parentsotid` = '$sotid'"),MYSQLI_ASSOC);
foreach ($get_uploads as $get_upload) {
    $name = $get_upload['name'];
    $file = $get_upload['file'];
    $added_by = $get_upload['added_by'];

    mysqli_query($dbc, "INSERT INTO `sales_order_upload` (`posid`, `name`, `file`, `added_by`) VALUES ('$posid', '$name', '$file', '$added_by')");
}

// Set Sales Order Temp Deleted to 1
mysqli_query($dbc, "UPDATE `sales_order_temp` SET `deleted` = 1 WHERE `sotid` = '$sotid'");

$redirect_url = 'index.php';