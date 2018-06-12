<?php

$company_software_name = $_POST['company_software_name'];
$get_so = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM sales_order WHERE posid='$poside'"));
$comment = $get_so['comment'];
$gst_total = $get_so['gst'];
$pst_total = $get_so['pst'];

    $discount_value = '0';
    if( $get_so['discount_value'] != 0) {
        $discount_type = $get_so['discount_type'];
        $discount_value = $get_so['discount_value'];
        if($discount_type == '%') {
            $d_value =  $discount_value.'%';
        }
        if($discount_type == '$') {
            $d_value =  '$'.$discount_value;
        }
    }
//invoice_date,contactid,productpricing,sub_total,discount_type,discount_value
if($purchaseorderconv == 1) {
	
	
	$query = mysqli_query($dbc,"SELECT * FROM sales_order WHERE posid='$poside'");
	while($row = mysqli_fetch_array($query)) {
		$invoice_date = $row['invoice_date'];
		$contactid  = $row['contactid'];
		$sub_total    = $row['sub_total'];
		$cross_software = $row['cross_software'];
		$discount_type    = $row['discount_type'];
		$discount_value    = $row['discount_value'];
		$delivery    = $row['delivery'];
		$delivery_type    = $row['delivery_type'];
		$contractorid    = $row['contractorid'];
		$assembly    = $row['assembly'];
		$total_before_tax    = $row['total_before_tax'];
		$client_tax_exemption    = $row['client_tax_exemption'];
		$tax_exemption_number    = $row['tax_exemption_number'];
		$total_price    = $row['total_price'];
		$payment_type    = $row['payment_type'];
		$created_by    = $row['created_by'];
		$comment = strip_tags(htmlspecialchars_decode($row['comment']));
		$ship_date    = $row['ship_date'];
		$status    = $row['status'];
		$status_history   = $row['status_history'];
		$deleted    = $row['deleted'];
		$deposit_paid    = $row['deposit_paid'];
		$updatedtotal    = $row['updatedtotal'];
		$due_date  = $row['due_date'];
		$zenornot = $row['cross_software'];
		if($zenornot == 'zen') {
			$dbcorcross = $dbczen;
		} else {
			$dbcorcross = $dbc;
		}
	}
		
		$sub_total = 0;
		
	$query = mysqli_query($dbc,"SELECT * FROM sales_order_product WHERE posid='$poside'");
	while($row = mysqli_fetch_array($query)) {
		$inventoryid = $row['inventoryid'];
		$misc_product = $row['misc_product'];
		$quantity = $row['quantity'];
		$type_category = $row['type_category'];
		
		
		
		if($type_category == 'inventory') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT * FROM inventory WHERE inventoryid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];	
		} else if($type_category == 'product') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT purchase_order_price FROM products WHERE productid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];
		} else if($type_category == 'service') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT purchase_order_price FROM services WHERE serviceid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];
		} else if($type_category == 'vpl') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT purchase_order_price FROM vendor_price_list WHERE inventoryid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];
		} else {
			$price = $row['price'];	
		}
		
		$gst = $row['gst'];
		$pst = $row['pst'];
		if($quantity == '' || $quantity == NULL) {
			$quantity = 1;
		}
		$sub_total += $quantity*$price;
		}
		$total_before_discount = 0;
		if($discount_type == '%') {
			$total_after_discount = $sub_total - ($sub_total*($discount_value*.01));
		} else if ($discount_type == '$') {
			$total_after_discount = $sub_total - $discount_value;
		} else  {
			$total_after_discount = $sub_total;
		}
		$total_before_tax = $total_after_discount+$delivery+$assembly;
		// GST PST
		$get_pos_tax = get_config($dbc, 'purchase_order_tax');
		$pdf_tax = '';
		$gst_total = 0;
		$pst_total = 0;
		if($get_pos_tax != '') {
			$pos_tax = explode('*#*',$get_pos_tax);

			$total_count = mb_substr_count($get_pos_tax,'*#*');
			for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
				$pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);

				if (strcasecmp($pos_tax_name_rate[0], 'gst') == 0) {
					$gst_total = number_format((($total_after_discount*$pos_tax_name_rate[1])/100), 2);
				}

				if (strcasecmp($pos_tax_name_rate[0], 'pst') == 0) {
					if($pos_tax_name_rate[3] == 'Yes' && $client_tax_exemption == 'Yes') {
						$pst_total = 0;
					} else {
						$pst_total = number_format((($total_after_discount*$pos_tax_name_rate[1])/100), 2);
					}
				}
			}
		}
		// GST PST
		$gst = $gst_total;
		$pst = $pst_total;
		$total_price = $total_after_discount+$delivery+$assembly+$pst_total+$gst_total;
		// TESTING PURPOSES : echo 'ST'.$sub_total.'<br>TBT'.$total_before_tax.'<br>TAD'.$total_after_discount.'<br>D'.$delivery.'<br>A'.$assembly.'<br>PST'.$pst_total.'<br>GST'.$gst_total;
		//echo '<br>T'.$total_price;
		$updatedtotal = $total_price - $deposit_paid;
	mysqli_query($dbc,"INSERT INTO purchase_orders 	(invoice_date,contactid,productpricing,sub_total,discount_type,discount_value,total_after_discount,gst,pst,delivery,delivery_type,contractorid,assembly,total_before_tax,client_tax_exemption,tax_exemption_number,total_price,payment_type,created_by,comment,ship_date,status,status_history,deleted,deposit_paid,updatedtotal,due_date,cross_software) VALUES ('$invoice_date','$contactid','$productpricing','$sub_total','$discount_type','$discount_value','$total_after_discount','$gst','$pst','$delivery','$delivery_type','$contractorid','$assembly','$total_before_tax','$client_tax_exemption','$tax_exemption_number','$total_price','$payment_type','$created_by','$comment','$ship_date','$status','$status_history','$deleted','$deposit_paid','$updatedtotal','$due_date','$cross_software') ") or die ('Un7able to execute query. '. mysqli_error($dbc));
	$posid = mysqli_insert_id($dbc);
	$query = mysqli_query($dbc,"SELECT * FROM sales_order_product WHERE posid='$poside'");
	while($row = mysqli_fetch_array($query)) {
		$inventoryid = $row['inventoryid'];
		$misc_product = $row['misc_product'];
		$quantity = $row['quantity'];
		$type_category = $row['type_category'];
		if($type_category == 'inventory') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT purchase_order_price FROM inventory WHERE inventoryid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];	
		} else if($type_category == 'product') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT purchase_order_price FROM products WHERE productid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];
		} else if($type_category == 'service') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT purchase_order_price FROM services WHERE serviceid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];
		} else if($type_category == 'vpl') {
			$get_so = mysqli_fetch_assoc(mysqli_query($dbcorcross,"SELECT purchase_order_price FROM vendor_price_list WHERE inventoryid='$inventoryid'"));
			$price = $get_so['purchase_order_price'];
		} else {
			$price = $row['price'];	
		}
		$gst = $row['gst'];
		$pst = $row['pst'];
		mysqli_query($dbc,"INSERT INTO purchase_orders_product (posid,inventoryid,misc_product,quantity,price,gst,pst,type_category) VALUES ('$posid','$inventoryid','$misc_product','$quantity','$price','$gst','$pst','$type_category')") or die ('Una1ble to execute query. '. mysqli_error($dbc));
	}
	
	include ('../Purchase Order/create_pos_pdf.php');
    $pos_design = get_config($dbc, 'purchase_order_design');
    if($pos_design == 1) {
        echo create_pos1_pdf($dbc,$posid,$d_value,$comment, $gst_total, $pst_total);
    }
    if($pos_design == 2) {
        echo create_pos2_pdf($dbc,$posid,$d_value,$comment, $gst_total, $pst_total);
    }
	if($pos_design == 3) {
        echo create_pos3_pdf($dbc,$posid,$d_value,$comment, $gst_total, $pst_total, $company_software_name);
    }
} else 

if($pointofsaleconv == 1) {
	$query = mysqli_query($dbc,"SELECT * FROM sales_order WHERE posid='$poside'");
	while($row = mysqli_fetch_array($query)) {
		$invoice_date = $row['invoice_date'];
		$contactid  = $row['contactid'];
		$sub_total    = $row['sub_total'];
		$discount_type    = $row['discount_type'];
		$discount_value    = $row['discount_value'];
		$total_after_discount    = $row['total_after_discount'];
		$gst    = $row['gst'];
		$cross_software = $row['cross_software'];
		$pst    = $row['pst'];
		$delivery    = $row['delivery'];
		$delivery_type    = $row['delivery_type'];
		$contractorid    = $row['contractorid'];
		$assembly    = $row['assembly'];
		$total_before_tax    = $row['total_before_tax'];
		$client_tax_exemption    = $row['client_tax_exemption'];
		$tax_exemption_number    = $row['tax_exemption_number'];
		$total_price    = $row['total_price'];
		$payment_type    = $row['payment_type'];
		$created_by    = $row['created_by'];
		$comment = strip_tags(htmlspecialchars_decode($row['comment']));
		$ship_date    = $row['ship_date'];
		$status    = $row['status'];
		$status_history   = $row['status_history'];
		$deleted    = $row['deleted'];
		$deposit_paid    = $row['deposit_paid'];
		$updatedtotal    = $row['updatedtotal'];
		$due_date  = $row['due_date'];
	}
		
		
	mysqli_query($dbc,"INSERT INTO point_of_sell (invoice_date,contactid,productpricing,sub_total,discount_type,discount_value,total_after_discount,gst,pst,delivery,delivery_type,contractorid,assembly,total_before_tax,client_tax_exemption,tax_exemption_number,total_price,payment_type,created_by,comment,ship_date,status,status_history,deleted,deposit_paid,updatedtotal,due_date,cross_software) VALUES ('$invoice_date','$contactid','$productpricing','$sub_total','$discount_type','$discount_value','$total_after_discount','$gst','$pst','$delivery','$delivery_type','$contractorid','$assembly','$total_before_tax','$client_tax_exemption','$tax_exemption_number','$total_price','$payment_type','$created_by','$comment','$ship_date','$status','$status_history','$deleted','$deposit_paid','$updatedtotal','$due_date','$cross_software') ") or die ('Unab2le to execute query. '. mysqli_error($dbc));
	$posid = mysqli_insert_id($dbc);
	mysqli_query($dbc,"UPDATE `point_of_sell` SET status = 'Completed' WHERE posid='$posid'");
	
	$query = mysqli_query($dbc,"SELECT * FROM sales_order_product WHERE posid='$poside'");
	while($row = mysqli_fetch_array($query)) {
		$inventoryid = $row['inventoryid'];
		$misc_product = $row['misc_product'];
		$quantity = $row['quantity'];
		$price = $row['price'];
		$gst = $row['gst'];
		$pst = $row['pst'];
		$type_category = $row['type_category'];
		mysqli_query($dbc,"INSERT INTO point_of_sell_product (posid,inventoryid,misc_product,quantity,price,gst,pst,type_category) VALUES ('$posid','$inventoryid','$misc_product','$quantity','$price','$gst','$pst','$type_category')") or die ('Una3ble to execute query. '. mysqli_error($dbc));
	}
	
	
	include ('../Point of Sale/create_pos_pdf_so.php');
    $pos_design = get_config($dbc, 'pos_design');
    if($pos_design == 1) {
        echo create_pos1_so_pdf($dbc,$posid,$d_value,$comment, $gst_total, $pst_total);
    }
    if($pos_design == 2) {
        echo create_pos2_so_pdf($dbc,$posid,$d_value,$comment, $gst_total, $pst_total);
    }
	if($pos_design == 3) {
        echo create_pos3_so_pdf($dbc,$posid,$d_value,$comment, $gst_total, $pst_total, $company_software_name);
    }
}

if($zenearthconv == 1) {
	$query = mysqli_query($dbc,"SELECT * FROM sales_order WHERE posid='$poside'");
	while($row = mysqli_fetch_array($query)) {
		$invoice_date = $row['invoice_date'];
		$contactid  = $row['contactid'];
		$sub_total    = $row['sub_total'];
		$discount_type    = $row['discount_type'];
		$discount_value    = $row['discount_value'];
		$total_after_discount    = $row['total_after_discount'];
		$gst    = $row['gst'];
		$pst    = $row['pst'];
		$delivery    = $row['delivery'];
		$software_author = $row['software_author'];
		$delivery_type    = $row['delivery_type'];
		$contractorid    = $row['contractorid'];
		$assembly    = $row['assembly'];
		$total_before_tax    = $row['total_before_tax'];
		$cross_software = $row['cross_software'];
		$client_tax_exemption    = $row['client_tax_exemption'];
		$tax_exemption_number    = $row['tax_exemption_number'];
		$total_price    = $row['total_price'];
		$payment_type    = $row['payment_type'];
		$created_by    = $row['created_by'];
		$comment = strip_tags(htmlspecialchars_decode($row['comment']));
		$ship_date    = $row['ship_date'];
		$status    = $row['status'];
		$status_history   = $row['status_history'];
		$deleted    = $row['deleted'];
		$deposit_paid    = $row['deposit_paid'];
		$updatedtotal    = $row['updatedtotal'];
		$due_date  = $row['due_date'];
	}
		
		
	mysqli_query($dbczen,"INSERT INTO sales_order (invoice_date,contactid,productpricing,sub_total,discount_type,discount_value,total_after_discount,gst,pst,delivery,delivery_type,contractorid,assembly,total_before_tax,client_tax_exemption,tax_exemption_number,total_price,payment_type,created_by,comment,ship_date,status,status_history,deleted,deposit_paid,updatedtotal,due_date,cross_software, cross_software_posid, software_author) VALUES ('$invoice_date','$contactid','$productpricing','$sub_total','$discount_type','$discount_value','$total_after_discount','$gst','$pst','$delivery','$delivery_type','$contractorid','$assembly','$total_before_tax','$client_tax_exemption','$tax_exemption_number','$total_price','$payment_type','$created_by','$comment','$ship_date','$status','$status_history','$deleted','$deposit_paid','$updatedtotal','$due_date','$cross_software', '$poside', '$software_author') ") or die ('Un4able to execute query. '. mysqli_error($dbczen));
	$posid = mysqli_insert_id($dbczen);
	
	$query = mysqli_query($dbc,"SELECT * FROM sales_order_product WHERE posid='$poside'");
	while($row = mysqli_fetch_array($query)) {
		$inventoryid = $row['inventoryid'];
		$misc_product = $row['misc_product'];
		$quantity = $row['quantity'];
		$price = $row['price'];
		$gst = $row['gst'];
		$pst = $row['pst'];
		$type_category = $row['type_category'];
		mysqli_query($dbczen,"INSERT INTO sales_order_product (posid,inventoryid,misc_product,quantity,price,gst,pst,type_category) VALUES ('$posid','$inventoryid','$misc_product','$quantity','$price','$gst','$pst','$type_category')") or die ('Una5ble to execute query. '. mysqli_error($dbczen));
	}
}

?>