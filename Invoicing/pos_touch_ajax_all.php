<?php
/*
 * AJAX for POS Touch
 * pos_touch.php
 */

include ('../database_connection.php');
include ('../function.php');
include ('../global.php');
if(session_status() == PHP_SESSION_NONE) {
	session_start(['cookie_lifetime' => 518400]);
	$_SERVER['page_load_info'] .= 'Session Started: '.number_format(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],5)."\n";
}
/* Customer selected */
if ( $_GET['fill'] == 'posTouchCustomerSelected' ) {
    $custid = $_GET['custid'];
	
	$results = mysqli_query ( $dbc, "INSERT INTO `pos_touch_temp_order` (`custid`) VALUES ('$custid')" );
	$orderid = mysqli_insert_id($dbc);

	// Set session to use on POS dashbaord
	$_SESSION['orderid'] = $orderid;
}


/* Add Inventory Item */
if ( $_GET['fill'] == 'posTouchAddInventoryPrice' ) {
    $orderid		= $_GET['orderid'];
	$invid 			= $_GET['invid'];
	$inv_pricing	= $_GET['inv_pricing'];
    $quantity 		= $_GET['quantity'];
    $gst 			= $_GET['gst'];
    $pst	 		= $_GET['pst'];
	$total			= 0.00;
	$name			= '';
	
	$results = mysqli_query ( $dbc, "SELECT `inventoryid`, `name`, `$inv_pricing` AS `inv_price` FROM `inventory` WHERE `inventoryid`='$invid'" ); 
	while ( $row=mysqli_fetch_assoc($results) ) {
		$total	= $quantity * $row['inv_price'];
		$name	= $row['name'];
	}
	
	if ( $orderid != 0 ) {
		// Update the order
		$row				= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT * FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" ) );
		$discount_percent	= ( empty($row['discount_percent']) || $row['discount_percent']==NULL ) ? 0 : $row['discount_percent'];
		$discount_amount	= ( empty($row['discount_amount']) || $row['discount_amount']==NULL ) ? 0 : $row['discount_amount'];
		
		if ( $discount_percent > 0 && $discount_amount > 0 ) {
			$discount_amount	= $row['discount_amount'] + ( $total * ( $discount_percent / 100 ) );
			$sub_total_before_discount = $row['sub_total_before_discount'] + $total;
			$sub_total			= number_format ( $sub_total_before_discount - $discount_amount, 2 );
		} else {
			$sub_total_before_discount = number_format ( $row['sub_total'] + $total, 2 );
			$sub_total = $sub_total_before_discount;
		}
		
		$gst_total			= $sub_total * ( $gst / 100 );
		$pst_total			= $sub_total * ( $pst / 100 );
		$total_tax			= number_format ( $gst_total + $pst_total, 2 );
		$order_total		= number_format ( $sub_total + $total_tax, 2 );
		
		$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `discount_amount`='$discount_amount', `sub_total_before_discount`='$sub_total_before_discount', `sub_total`='$sub_total', `gst_total`='$gst_total', `pst_total`='$pst_total', `total_tax`='$total_tax', `order_total`='$order_total' WHERE `orderid`='$orderid'" );
	
	} else {
		// First item. No discounts without adding an item first.
		$sub_total_before_discount = $total;
		$sub_total		= $sub_total_before_discount;
		$gst_total		= $total * ( $gst / 100 );
		$pst_total		= $total * ( $pst / 100 );
		$total_tax		= number_format ( $gst_total + $pst_total, 2 );
		$order_total	= number_format ( $total + $total_tax, 2 );
		
		$results = mysqli_query ( $dbc, "INSERT INTO `pos_touch_temp_order` (`sub_total_before_discount`, `sub_total`, `gst_total`, `pst_total`, `total_tax`, `order_total`) VALUES ('$sub_total_before_discount', '$sub_total', '$gst_total', '$pst_total', '$total_tax', '$order_total')" );
		$orderid = mysqli_insert_id($dbc);
	
		/* 
		 * Set session to use on POS dashbaord,
		 * if Customer display is disabled from Settings -> Choose Fields for POS.
		 * If Customer display is enabled, orderid is set in posTouchCustomerSelected.
		 */
		if ( !isset ( $_SESSION['orderid'] ) ) {
			$_SESSION['orderid'] = $orderid;
		}
	}
	
	// Insert to `pos_touch_temp_order_products`
	$results = mysqli_query ( $dbc, "INSERT INTO `pos_touch_temp_order_products` (`orderid`, `inventoryid`, `product_name`, `inventory_pricing`, `quantity`, `total`) VALUES ('$orderid', '$invid', '$name', '$inv_pricing', '$quantity', '$total')" );
}


/* Add product */
if ( $_GET['fill'] == 'posTouchAddPrice' ) {
    $orderid	= $_GET['orderid'];
	$name		= $_GET['name'];
	$prodid 	= $_GET['prodid'];
	$price		= $_GET['price'];
    $quantity 	= $_GET['quantity'];
    $gst 		= $_GET['gst'];
    $pst	 	= $_GET['pst'];
	
	$get_price = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `final_retail_price` FROM `products` WHERE `productid`='$prodid'" ) );
	$price		= $get_price['final_retail_price'];
	$total		= $price * $quantity;
	
	if ( $orderid != 0 ) {
		// Update the order
		$row				= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT * FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" ) );
		$discount_percent	= ( empty($row['discount_percent']) || $row['discount_percent']==NULL ) ? 0 : $row['discount_percent'];
		$discount_amount	= ( empty($row['discount_amount']) || $row['discount_amount']==NULL ) ? 0 : $row['discount_amount'];
		
		if ( $discount_percent > 0 && $discount_amount > 0 ) {
			$discount_amount	= $row['discount_amount'] + ( $total * ( $discount_percent / 100 ) );
			$sub_total_before_discount = $row['sub_total_before_discount'] + $total;
			$sub_total			= number_format ( $sub_total_before_discount - $discount_amount, 2 );
		} else {
			$sub_total_before_discount = number_format ( $row['sub_total'] + $total, 2 );
			$sub_total = $sub_total_before_discount;
		}
		
		$gst_total			= $sub_total * ( $gst / 100 );
		$pst_total			= $sub_total * ( $pst / 100 );
		$total_tax			= number_format ( $gst_total + $pst_total, 2 );
		$order_total		= number_format ( $sub_total + $total_tax, 2 );
		
		$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `discount_amount`='$discount_amount', `sub_total_before_discount`='$sub_total_before_discount', `sub_total`='$sub_total', `gst_total`='$gst_total', `pst_total`='$pst_total', `total_tax`='$total_tax', `order_total`='$order_total' WHERE `orderid`='$orderid'" );
	
	} else {
		// First item. No discounts without adding an item first.
		$sub_total_before_discount = $total;
		$sub_total		= $sub_total_before_discount;
		$gst_total		= $total * ( $gst / 100 );
		$pst_total		= $total * ( $pst / 100 );
		$total_tax		= number_format ( $gst_total + $pst_total, 2 );
		$order_total	= number_format ( $total + $total_tax, 2 );
		
		$results = mysqli_query ( $dbc, "INSERT INTO `pos_touch_temp_order` (`sub_total_before_discount`, `sub_total`, `gst_total`, `pst_total`, `total_tax`, `order_total`) VALUES ('$sub_total_before_discount', '$sub_total', '$gst_total', '$pst_total', '$total_tax', '$order_total')" );
		$orderid = mysqli_insert_id($dbc);
		
		// Set session to use on POS dashbaord
		$_SESSION['orderid'] = $orderid;
	}
	
	// Insert to `pos_touch_temp_order_products`
	$results = mysqli_query ( $dbc, "INSERT INTO `pos_touch_temp_order_products` (`orderid`, `productid`, `product_name`, `quantity`, `total`) VALUES ('$orderid', '$prodid', '$name', '$quantity', '$total')" );
}


/* Remove a product */
if ( $_GET['fill'] == 'posTouchRemoveProduct' ) {
    $orderlistid	= $_GET['orderlistid'];
    $gst 			= $_GET['gst'];
    $pst	 		= $_GET['pst'];
	
	$row = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT o.*, p.* FROM `pos_touch_temp_order` AS o INNER JOIN `pos_touch_temp_order_products` AS p ON (o.`orderid` = p.`orderid`) WHERE p.`orderlistid`='$orderlistid'" ) );
	
	$orderid			= $row['orderid'];
	$discount_percent	= ( empty($row['discount_percent']) || $row['discount_percent']==NULL ) ? 0 : $row['discount_percent'];
	$discount_amount	= ( empty($row['discount_amount']) || $row['discount_amount']==NULL ) ? 0 : $row['discount_amount'];
	
	if ( $discount_percent > 0 && $discount_amount > 0 ) {
		$removed_discount	= $row['total'] * ( $discount_percent / 100 );
		$discount_amount	= $row['discount_amount'] - $removed_discount;
		$sub_total_before_discount = $row['sub_total_before_discount'] - $row['total'];
		$sub_total			= $sub_total_before_discount - $discount_amount;
	} else {
		$sub_total_before_discount = number_format ( $row['sub_total_before_discount'] - $row['total'], 2 );
		$sub_total			= $sub_total_before_discount;
	}
	
	$gst_total			= $sub_total * ( $gst / 100 );
	$pst_total			= $sub_total * ( $pst / 100 );
	$total_tax			= number_format ( $gst_total + $pst_total, 2 );
	$order_total		= number_format ( $sub_total + $total_tax, 2 );
	
	// Update temporary order
	$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `discount_amount`='$discount_amount', `sub_total_before_discount`='$sub_total_before_discount', `sub_total`='$sub_total', `gst_total`='$gst_total', `pst_total`='$pst_total', `total_tax`='$total_tax', `order_total`='$order_total' WHERE `orderid`='$orderid'" );
	
	if ( mysqli_affected_rows ($dbc) ) {
		// Delete product from temporary order products list
		$results = mysqli_query ( $dbc, "DELETE FROM `pos_touch_temp_order_products` WHERE `orderlistid`=$orderlistid" );
		
		// Reset `pos_touch_temp_order` values to 0, if there are no products in `pos_touch_temp_order_products`
		$results = mysqli_query ( $dbc, "SELECT * FROM `pos_touch_temp_order_products` WHERE `orderid`='$orderid'" );
		$num_rows = mysqli_num_rows($results);
		if ( $num_rows == 0 ) {
			$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `discount_percent`=NULL, `discount_amount`=NULL, `sub_total_before_discount`='0', `sub_total`='0', `gst_total`='0', `pst_total`='0', `total_tax`='0', `order_total`='0' WHERE `orderid`='$orderid'" );
		}
		
	} else {
		echo 'pos_touch_temp_order was not updated';
	}
}


/* Add discount */
if ( $_GET['fill'] == 'posTouchAddDiscount' ) {
    $orderid	= $_GET['orderid'];
	$disc_type	= $_GET['disc_type'];
	$discount	= $_GET['discount'];
	$gst		= $_GET['gst'];
	$pst		= $_GET['pst'];
	
	$row = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT * FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" ) );
	
	if ( $disc_type=='%' ) {
		$discount_amount = $row['sub_total'] * ( $discount / 100 );
	}
	if ( $disc_type=='$' ) {
		$discount_amount = $discount;
	}
	
	$sub_total			= $row['sub_total'] - $discount_amount;
	$gst_total			= $sub_total * ( $gst / 100 );
	$pst_total			= $sub_total * ( $pst / 100 );
	$total_tax			= $gst_total + $pst_total;
	$order_total		= $sub_total + $total_tax;
	
	// Update temporary order
	$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `discount_percent`='$discount', `discount_amount`='$discount_amount', `sub_total`='$sub_total', `gst_total`='$gst_total', `pst_total`='$pst_total', `total_tax`='$total_tax', `order_total`='$order_total' WHERE `orderid`='$orderid'" );
}


/* Remove discount */
if ( $_GET['fill'] == 'posTouchRemoveDiscount' ) {
    $orderid	= $_GET['orderid'];
	$gst		= $_GET['gst'];
	$pst		= $_GET['pst'];
	
	$row = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT * FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" ) );
	
	$sub_total			= $row['sub_total'] + $row['discount_amount'];
	$gst_total			= $sub_total * ( $gst / 100 );
	$pst_total			= $sub_total * ( $pst / 100 );
	$total_tax			= $gst_total + $pst_total;
	$order_total		= $sub_total + $total_tax;
	
	// Update temporary order
	$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `discount_percent`=NULL, `discount_amount`=NULL, `sub_total`='$sub_total', `gst_total`='$gst_total', `pst_total`='$pst_total', `total_tax`='$total_tax', `order_total`='$order_total' WHERE `orderid`='$orderid'" );
}


/* Add coupon */
if ( $_GET['fill'] == 'posTouchAddCoupon' ) {
    $orderid	= $_GET['orderid'];
    $couponid	= $_GET['couponid'];
	$gst		= $_GET['gst'];
	$pst		= $_GET['pst'];
	
	$row_coupon		= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `discount_type`, `discount` FROM `pos_touch_coupons` WHERE `couponid`='$couponid'" ) );
	$discount_type	= $row_coupon['discount_type'];
	$discount		= $row_coupon['discount'];
	
	$row = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT * FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" ) );
	
	// We take `sub_total` instead of `sub_total_before_discount` because a discount can be added separately
	if ( $discount_type=='%' ) {
		$coupon_value = $row['sub_total'] * ( $discount / 100 );
	}
	if ( $discount_type=='$' ) {
		$coupon_value = $discount;
	}
	
	$sub_total			= $row['sub_total'] - $coupon_value;
	$gst_total			= $sub_total * ( $gst / 100 );
	$pst_total			= $sub_total * ( $pst / 100 );
	$total_tax			= $gst_total + $pst_total;
	$order_total		= $sub_total + $total_tax;
	
	// Update temporary order
	$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `couponid`='$couponid', `coupon_value`='$coupon_value', `sub_total`='$sub_total', `gst_total`='$gst_total', `pst_total`='$pst_total', `total_tax`='$total_tax', `order_total`='$order_total' WHERE `orderid`='$orderid'" );
	
	// Update coupon used times
	$results = mysqli_query ( $dbc, "UPDATE `pos_touch_coupons` SET `used_times`=`used_times`+1 WHERE `couponid`='$couponid'" );
}


/* Remove coupon */
if ( $_GET['fill'] == 'posTouchRemoveCoupon' ) {
    $orderid	= $_GET['orderid'];
    $couponid	= $_GET['couponid'];
	$gst		= $_GET['gst'];
	$pst		= $_GET['pst'];
	
	$row = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT * FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" ) );
	
	$sub_total			= $row['sub_total'] + $row['coupon_value'];
	$gst_total			= $sub_total * ( $gst / 100 );
	$pst_total			= $sub_total * ( $pst / 100 );
	$total_tax			= $gst_total + $pst_total;
	$order_total		= $sub_total + $total_tax;
	
	// Update temporary order
	$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `couponid`=0, `coupon_value`='0', `sub_total`='$sub_total', `gst_total`='$gst_total', `pst_total`='$pst_total', `total_tax`='$total_tax', `order_total`='$order_total' WHERE `orderid`='$orderid'" );
	
	// Update coupon used times
	$results = mysqli_query ( $dbc, "UPDATE `pos_touch_coupons` SET `used_times`=`used_times`-1 WHERE `couponid`='$couponid'" );
}


/* Hold order */
if ( $_GET['fill'] == 'posTouchHoldOrder' ) {
	$orderid	= $_GET['orderid'];
	$comments	= $_GET['comments'];
	$order_date	= date('Y-m-d H:i:s');
	
	if ( isset($_SESSION['orderid']) && !empty($orderid) ) {
		$results = mysqli_query ( $dbc, "UPDATE `pos_touch_temp_order` SET `hold_order`=1, `date_time`='$order_date', `comments`='$comments' WHERE `orderid`='$orderid'" );
		
		if ( mysqli_affected_rows ($dbc) ) {
			unset ( $_SESSION['orderid'] );
		}
	}
}


/* Service held order */
if ( $_GET['fill'] == 'posTouchServiceHeldOrder' ) {
	$orderid = $_GET['orderid'];
	
	if ( !empty($orderid) ) {
		$_SESSION['orderid'] = $orderid;
	}
}


/* Cancel held order */
if ( $_GET['fill'] == 'posTouchCancelHeldOrder' ) {
	$orderid = $_GET['orderid'];
	
	if ( !empty($orderid) ) {
		$results = mysqli_query ( $dbc, "DELETE FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" );
		$results = mysqli_query ( $dbc, "DELETE FROM `pos_touch_temp_order_products` WHERE `orderid`='$orderid'" );
	}
}


/* Cancel or Complete order */
if ( $_GET['fill'] == 'posTouchCancelOrder' ) {
	
	if ( isset($_SESSION['orderid']) ) {
		$orderid = $_SESSION['orderid'];
		$results = mysqli_query ( $dbc, "DELETE FROM `pos_touch_temp_order` WHERE `orderid`='$orderid'" );
		$results = mysqli_query ( $dbc, "DELETE FROM `pos_touch_temp_order_products` WHERE `orderid`='$orderid'" );
		unset ( $_SESSION['orderid'] );
	}
}


/* Email Receipt */
if ( $_GET['fill'] == 'posTouchEmailReceipt' ) {
	$posid		= $_GET['posid'];
	$attachment	= $_GET['attachment'];
	$to_email	= $_GET['to_email'];
	$to_client	= $_GET['to_client'];
	
	if ( !empty($to_client) ) {
		$to_email = get_email($dbc, $to_client);
	}
	
	if ( !empty($to_email) && !empty($attachment) ) {
		send_email('', $to_email, '', '', 'Sales Invoice', 'Please find the invoice attached.', $attachment);
	}
}
?>