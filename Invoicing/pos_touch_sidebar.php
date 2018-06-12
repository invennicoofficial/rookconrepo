<div id="sidebar" class="col-sm-4 triple-gap-top pad-right">
	<?php
		$discount_amount	= '';
		$sub_total			= '';
		$total_tax			= '';
		$order_total		= '';
		$product_name		= '';
		$quantity			= '';
		$total				= '';
		$remove				= '';
		$remove_icon		= '<img src="' . WEBSITE_URL . '/img/remove.png" width="15" />';
		
		if ( isset ($_SESSION['orderid']) && !empty($_SESSION['orderid']) ) {
			$orderid = $_SESSION['orderid'];
			
			$get_values = mysqli_query ( $dbc, "SELECT `o`.*, `p`.* FROM `pos_touch_temp_order` AS `o` INNER JOIN `pos_touch_temp_order_products` AS `p` ON (`o`.`orderid` = `p`.`orderid`) WHERE `o`.`orderid`='$orderid' ORDER BY `p`.`orderlistid`" );
			
			$product_row		= '';
			$get_inv_pricing	= '';
			
			while ( $row = mysqli_fetch_assoc($get_values) ) {
				$orderlistid		= $row['orderlistid'];
				$discount_amount	= number_format ( $row['discount_amount'], 2 );
				$sub_total_before_discount = number_format ( $row['sub_total_before_discount'], 2 );
				$couponid			= $row['couponid'];
				$coupon_value		= number_format ( $row['coupon_value'], 2 );
				$sub_total			= number_format ( $row['sub_total'], 2 );
				$gst_total			= ( !empty($row['gst_total']) ) ? number_format ( $row['gst_total'], 2 ) : '0.00';
				$pst_total			= ( !empty($row['pst_total']) ) ? number_format ( $row['pst_total'], 2 ) : '0.00';
				$total_tax			= number_format ( $row['total_tax'], 2 );
				$order_total		= number_format ( $row['order_total'], 2 );
				
				$product_name 		= $row['product_name'];
				$quantity			= $row['quantity'];
				$total				= number_format ( $row['total'], 2 );
				$remove 			= '<a id="' . $orderlistid . '" class="button" onclick="removeProduct(this);">' . $remove_icon . '</a>';
				
				$product_row		.= '<div>';
					$product_row	.= '<div class="col-sm-8">'							. $product_name	. '</div>';
					$product_row	.= '<div class="col-sm-1 right-align">'				. $quantity		. '</div>';
					$product_row	.= '<div class="col-sm-2 right-align">$'			. $total		. '</div>';
					$product_row	.= '<div class="col-sm-1 pull-right right-align">'	. $remove		. '</div>';
				$product_row		.= '</div>';
				$product_row		.= '<div class="clearfix"></div>';
				
				$get_inv_pricing	= $row['inventory_pricing'];
			}
		
		}
		
		$query_vars = '';
		if ( !empty($get_inv_pricing) ) {
			$query_vars = '?classification=inventory&inv_pricing=' . $get_inv_pricing;
		}
	?>
	
	<input type="hidden" id="h_checktotal" name="checktotal" value="<?php echo ( !empty($order_total) ) ? $order_total : '0'; ?>" />
	
	<div>
		<div class="col-sm-6"><a href="pos_touch.php<?php echo $query_vars; ?>"><button id="dashboard" class="btn brand-btn mobile-block touch-button">DASHBOARD</button></a></div>
		<div class="col-sm-6"><button id="back" class="btn brand-btn mobile-block touch-button">GO BACK</button></div>
	</div>
	
	<div class="double-gap-top">
		<div class="col-sm-8"><h2>ORDER TOTAL</h2></div>
		<div class="col-sm-4"><h2 id="order_total_top" class="pull-right">$<?php echo ( !empty($order_total) ) ? $order_total : '0.00'; ?></h2></div>
		<div class="clearfix"></div>
	</div>
	
	<div class="double-gap-top">
		<div><?php echo ( !empty($product_row) ) ? $product_row : ''; ?></div>
		<div class="clearfix"></div>
	</div>
	
	<?php if ( strpos ( $value_config, ',Touch Discount,') !== FALSE ) { ?>
		<div class="double-gap-top"><?php
			if ( empty($discount_amount) || $discount_amount=='0' ) { ?>
				<div class="col-sm-9">DISCOUNT:</div>
				<div class="col-sm-2 pull-right right-align" id="discount_amount">$0.00</div>
				<div class="col-sm-1"></div><?php
			} else { ?>
				<div class="col-sm-8">DISCOUNT:</div>
				<div class="col-sm-1"></div>
				<div class="col-sm-2 right-align" id="discount_amount">$<?php echo $discount_amount; ?></div>
				<div class="col-sm-1 pull-right right-align"><a class="button" onclick="removeDiscount();"><?php echo $remove_icon; ?></a></div><?php
			} ?>
			<div class="clearfix"></div>
		</div>
	<?php } ?>
	
	<?php if ( strpos ( $value_config, ',Coupon,') !== FALSE ) { ?>
		<div class="double-gap-top"><?php
			if ( empty($coupon_value) || $coupon_value=='0' ) { ?>
				<div class="col-sm-9">COUPON:</div>
				<div class="col-sm-2 pull-right right-align" id="coupon_value">$0.00</div>
				<div class="col-sm-1"></div><?php
			} else { ?>
				<div class="col-sm-8">COUPON:</div>
				<div class="col-sm-1"></div>
				<div class="col-sm-2 right-align" id="coupon_value">$<?php echo $coupon_value; ?></div>
				<div class="col-sm-1 pull-right right-align"><a class="button" id="coupon_<?php echo $couponid; ?>" onclick="removeCoupon(this);"><?php echo $remove_icon; ?></a></div><?php
			} ?>
			<div class="clearfix"></div>
		</div>
	<?php } ?>
	
	<div class="double-gap-top">
		<div class="col-sm-9">SUB TOTAL:</div>
		<div class="col-sm-2 pull-right right-align" id="sub_total">$<?php echo ( !empty($sub_total) ) ? $sub_total : '0.00'; ?></div>
		<div class="col-sm-1"></div>
		<div class="clearfix"></div>
	</div><?php
	
	if ( $gst_rate > 0 ) { ?>
		<div>
			<div class="col-sm-9">GST [<?php echo $gst_rate; ?>%]:</div>
			<div class="col-sm-2 pull-right right-align">$<?php echo ( !empty($gst_total) ) ? $gst_total : '0.00'; ?></div>
			<div class="col-sm-1"></div>
			<div class="clearfix"></div>
		</div><?php
	}
	
	if ( $pst_rate > 0 ) { ?>
		<div>
			<div class="col-sm-9">PST [<?php echo $pst_rate; ?>%]:</div>
			<div class="col-sm-2 pull-right right-align">$<?php echo ( !empty($pst_total) ) ? $pst_total : '0.00'; ?></div>
			<div class="col-sm-1"></div>
			<div class="clearfix"></div>
		</div><?php
	}?>
	
	<div>
		<div class="col-sm-9">TOTAL:</div>
		<div class="col-sm-2 pull-right right-align" id="order_total">$<?php echo ( !empty($order_total) ) ? $order_total : '0.00'; ?></div>
		<div class="col-sm-1"></div>
		<div class="clearfix"></div>
	</div>
	
	<div class="double-gap-top">
		<div class="col-sm-6"><button id="hold_order" class="btn brand-btn mobile-block touch-button" onclick="holdOrder(this);">HOLD ORDER</button></div>
		<div class="col-sm-6"><button id="cancel_order" class="btn brand-btn mobile-block touch-button" onclick="cancelOrder();">CANCEL ORDER</button></div>
		<!--<div class="col-sm-4"><a href="pos_touch.php"><button id="dashboard" class="btn brand-btn mobile-block touch-button">SEND ORDER</button></a></div>-->
		<div class="clearfix"></div>
	</div>
	
	<div class="triple-gap-top">
		<div class="col-sm-9"><?php
			if ( strpos ( $value_config, ',Add Item,') !== FALSE ) { ?>
				<div class="col-sm-3 touch-icons pull-left"><a href="pos_touch.php<?php echo $query_vars; ?>"><img src="<?php echo WEBSITE_URL; ?>/img/icons/pos-plus.png" width="40" /></a><br />ADD ITEM</div><?php
			}
			if ( strpos ( $value_config, ',Touch Discount,') !== FALSE ) { ?>
				<div class="col-sm-3 touch-icons pull-left"><a href="?discount=yes"><img src="<?php echo WEBSITE_URL; ?>/img/icons/pos-percentage.png" width="40" /></a><br />DISCOUNT</div><?php
			}
			if ( strpos ( $value_config, ',Coupon,') !== FALSE ) { ?>
				<div class="col-sm-3 touch-icons pull-left"><a href="javascript:void(0);" onclick="checkValidCouponClick();"><img src="<?php echo WEBSITE_URL; ?>/img/icons/pos-togo.png" width="40" /></a><br /><div class="indent-5">COUPON</div></div><?php
			}
			if ( strpos ( $value_config, ',Email Receipt,') !== FALSE ) { ?>
				<div class="col-sm-3 touch-icons pull-left"><a href="javascript:void(0);" onclick="checkValidEmailReceiptClick();"><img src="<?php echo WEBSITE_URL; ?>/img/icons/pos-email.png" width="40" /></a><br /><div class="indent-5">EMAIL</div></div><?php
			} ?>
			<!--
			<div class="col-sm-3 touch-icons"><a href=""><img src="<?php //echo WEBSITE_URL; ?>/img/icons/pos-togo.png" width="40" /></a><br /><div class="indent-5">TO GO</div></div>
			-->
			<!--
			<div class="col-sm-3 touch-icons"><a href=""><img src="<?php //echo WEBSITE_URL; ?>/img/icons/pos-print.png" width="40" /></a><br /><div class="indent-5">PRINT</div></div>
			-->
		</div>
		<div class="col-sm-3">
			<div class="col-sm-12 touch-icons pull-right"><a href="?pay=yes&pay_type=cash"><img src="<?php echo WEBSITE_URL; ?>/img/icons/pos-pay.png" width="40" /></a><br /><div class="indent-5">PAY</div></div>
		</div>
	</div>
			
	<div class="clearfix"></div>
	
	<div class="double-gap-top">
		<div class="col-sm-9"><?php
			if ( strpos ( $value_config, ',Write-Off,') !== FALSE ) { ?>
				<div class="col-sm-3 touch-icons pull-left"><a href=""><img src="<?php echo WEBSITE_URL; ?>/img/icons/pos-togo.png" width="40" /></a><br /><div class="indent-5">WRITE-OFF</div></div><?php
			}
			if ( strpos ( $value_config, ',Bill of Materials,') !== FALSE ) { ?>
				<div class="col-sm-3 touch-icons pull-left"><a href="<?php echo WEBSITE_URL; ?>/Inventory/bill_of_material_consumables.php"><img src="<?php echo WEBSITE_URL; ?>/img/icons/pos-bom.png" width="40" title="Bill of Materials (Consumables)" /></a><br /><div class="indent-5">BOM</div></div><?php
			} ?>
		</div>
		<div class="col-sm-3"></div>
	</div>
	
	<div class="clearfix"></div>
</div><!-- #sidebar -->