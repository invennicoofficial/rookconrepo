<?php
/* ----- Main POS Dashboard ----- */
if ( $customer===FALSE && $discount===FALSE && $coupon===FALSE && $email_reciept===FALSE && $pay===FALSE && $complete===FALSE ) {
				
	echo '<div class="double-gap-bottom">';
		echo '
			<div class="col-sm-3">
				<a href="pos_touch.php?classification=inventory"><button class="btn brand-btn mobile-block touch-button ' . $inventory_active . '">INVENTORY</button></a>
			</div>';
		echo '
			<div class="col-sm-3">
				<a href="pos_touch.php?classification=products"><button class="btn brand-btn mobile-block touch-button ' . $products_active . '">PRODUCTS</button></a>
			</div>';
		/*
		echo '
			<div class="col-sm-3">
				<a href="pos_touch.php?classification=services"><button class="btn brand-btn mobile-block touch-button ' . $services_active . '">SERVICES</button></a>
			</div>';
		echo '
			<div class="col-sm-3">
				<a href="pos_touch.php?classification=misc"><button class="btn brand-btn mobile-block touch-button ' . $misc_active . '">MISCELLANEOUS</button></a>
			</div>';
		*/
		echo '
			<div class="col-sm-3">
				<a href="pos_touch.php?classification=held"><button class="btn brand-btn mobile-block touch-button ' . $held_active . '">HELD ORDERS</button></a>
			</div>';
	echo '</div>';
	echo '<div class="clearfix"></div>';
	
	/* Display Inventory */
	if ( $classification=='inventory' ) {
		if ( strpos ( $value_config, ',Products,') !== FALSE ) {
			
			echo '<div class="col-sm-12"><h3>INVENTORY</h3></div><div class="clearfix"></div>';
			
			// Inventory Pricing Dashboard
			if ( $inv_pricing == '' && $inv_category == '' && $invid == '' ) {
				echo '<div class="notice">';
					echo '<div class="col-sm-1"><img src="' . WEBSITE_URL . '/img/info.png" width="30" /></div>';
					echo '<div class="col-sm-11 pad-5">Make sure to turn on the Pricing option and required Pricing fields first from within Settings.</div>';
					echo '<div class="clearfix"></div>';
				echo '</div>';
				
				if ( strpos ( $value_config, ',Client Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=client_price">Client Price</a></div>';
				}
				if ( strpos ( $value_config, ',Admin Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=admin_price">Admin Price</a></div>';
				}
				if ( strpos ( $value_config, ',Commercial Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=commercial_price">Commercial Price</a></div>';
				}
				if ( strpos ( $value_config, ',Wholesale Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=wholesale_price">Wholesale Price</a></div>';
				}
				if ( strpos ( $value_config, ',Final Retail Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=final_retail_price">Final Retail Price</a></div>';
				}
				if ( strpos ( $value_config, ',Preferred Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=preferred_price">Preferred Price</a></div>';
				}
				if ( strpos ( $value_config, ',Web Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=web_price">Web Price</a></div>';
				}
				if ( strpos ( $value_config, ',Purchase Order Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=purchase_order_price">Purchase Order Price</a></div>';
				}
				if ( strpos ( $value_config, ',Sales Order Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=sales_order_price">'.SALES_ORDER_NOUN.' Price</a></div>';
				}
				if ( strpos ( $value_config, ',Drum Unit Cost,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=drum_unit_cost">Drum Unit Cost</a></div>';
				}
				if ( strpos ( $value_config, ',Drum Unit Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=drum_unit_price">Drum Unit Price</a></div>';
				}
				if ( strpos ( $value_config, ',Tote Unit Cost,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=tote_unit_cost">Tote Unit Cost</a></div>';
				}
				if ( strpos ( $value_config, ',Tote Unit Price,' ) !== FALSE ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=tote_unit_price">Tote Unit Price</a></div>';
				}
			}
			
			// Inventory Category Dashboard
			if ( $inv_pricing != '' && $inv_category == '' && $invid == '' ) {
				$query = mysqli_query($dbc, "SELECT DISTINCT `category` FROM `inventory` WHERE `deleted`=0 AND `category` IS NOT NULL AND `category`!='' ORDER BY `category`");
				while ( $row = mysqli_fetch_array($query) ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=' . $inv_pricing . '&inv_category=' . $row['category'] . '">' . $row['category'] . '</a></div>';
				}
			}
			
			// Inventory Name Dashboard
			if ( $inv_pricing != '' && $inv_category != '' && $invid == '' ) {
				$query = mysqli_query($dbc, "SELECT `inventoryid`, `part_no`, `name`, `$inv_pricing` AS `inv_pricing` FROM `inventory` WHERE `deleted`=0 AND (`$inv_pricing` IS NOT NULL OR `$inv_pricing`!=0) ORDER BY `name`");
				while ( $row = mysqli_fetch_array($query) ) {
					if ( !empty ( $row['name'] ) ) {
						$part_no	= ( !empty ( $row['part_no'] ) ) ? ' (' . $row['part_no'] . ')' : '';
						$inv_price	= ( !empty ( $row['inv_pricing'] ) ) ? '<br />$' . number_format ( $row['inv_pricing'], 2 ) : '';
						echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=inventory&inv_pricing=' . $inv_pricing . '&inv_category=' . $inv_category . '&invid=' . $row['inventoryid'] . '">' . $row['name'] . $part_no . $inv_price . '</a></div>';
					}
				}
			}
			
			// Inventory Quantity Dashboard
			if ( $inv_pricing != '' && $inv_category != '' && $invid != '' ) {
				$query = mysqli_query($dbc, "SELECT `inventoryid`, `$inv_pricing` FROM `inventory` WHERE `inventoryid`='$invid'");
				while ( $row = mysqli_fetch_array($query) ) {
					$inventoryid = $row['inventoryid'];
				} ?>
				
				<input type="hidden" name="inv_pricing" id="h_inv_pricing" value="<?php echo $inv_pricing; ?>" />
				<input type="number" name="quantity" id="quantity" class="form-control double-gap-top" value="1" min="1" />
				
				<div class="gap-top"></div>
				<button id="remove" class="btn brand-btn btn-lg mobile-block" onclick="changeQuantity(this);">-</button>
				<button id="add" class="btn brand-btn btn-lg mobile-block" onclick="changeQuantity(this);">+</button>
				<button id="invid_<?php echo $inventoryid; ?>" class="btn brand-btn btn-lg mobile-block" onclick="addInventory(this);">OK</button><?php
			}
			
		}
	}
		
	
	/* Display Products */
	if ( $classification=='products' ) {
		if ( strpos ( $value_config, ',prodProducts,') !== FALSE ) {
			
			echo '<div class="col-sm-12"><h3>PRODUCTS</h3></div><div class="clearfix"></div>';
			
			// Product Category Dashboard
			if ( $product == '' && $type == '' && $category == '' ) {
				$query = mysqli_query($dbc, "SELECT DISTINCT `category` FROM `products` WHERE `deleted`=0 ORDER BY `category`");
				while ( $row = mysqli_fetch_array($query) ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=products&category=' . $row['category'] . '">' . $row['category'] . '</a></div>';
				}
			}
			
			// Product Type Dashboard
			if ( $product == '' && $type == '' && $category != '' ) {
				$query = mysqli_query($dbc, "SELECT DISTINCT `product_type`, `category` FROM `products` WHERE `category` = '$category' AND `deleted`=0 ORDER BY `category`");
				while ( $row = mysqli_fetch_array($query) ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=products&category=' . $row['category'] . '&type=' . $row['product_type'] . '">' . $row['product_type'] . '</a></div>';
				}
			}
			
			// Products Dashboard
			if ( $product == '' && $type != '' && $category != '' ) {
				$query = mysqli_query($dbc, "SELECT DISTINCT `product_type`, `category`, `heading`, `final_retail_price` FROM `products` WHERE `category` = '$category' AND `product_type` = '$type' AND `deleted`=0 ORDER BY `category`");
				while ( $row = mysqli_fetch_array($query) ) {
					echo '<div class="dashboard link col-sm-4"><a href="pos_touch.php?classification=products&category=' . $row['category'] . '&type=' . $row['product_type'] . '&product=' . $row['heading'] . '">' . $row['heading'] . ' $' . $row['final_retail_price'] . '</a></div>';
				}
			}
			
			// Product Quantity Dashboard
			if ( $product != '' && $type != '' && $category != '' ) {
				$query = mysqli_query($dbc, "SELECT DISTINCT `productid`, `product_type`, `category`, `heading` FROM `products` WHERE `category` = '$category' AND `product_type` = '$type' AND `heading`='$product' AND `deleted`=0");
				while ( $row = mysqli_fetch_array($query) ) {
					$productid	= $row['productid'];
				} ?>
				
				<input type="number" name="quantity" id="quantity" class="form-control double-gap-top" value="1" min="1" />
				
				<div class="gap-top"></div>
				<button id="remove" class="btn brand-btn btn-lg mobile-block" onclick="changeQuantity(this);">-</button>
				<button id="add" class="btn brand-btn btn-lg mobile-block" onclick="changeQuantity(this);">+</button>
				<button id="prodid_<?php echo $productid; ?>" class="btn brand-btn btn-lg mobile-block" onclick="addProduct(this);">OK</button><?php
			}
			
		}
	}
	
	
	/* Display Services */
	if ( $classification=='services' ) {
		if ( strpos ( $value_config, ',servServices,') !== FALSE ) {
			
		}
	}
	
	
	/* Display Miscellaneous Items */
	if ( $classification=='misc' ) {
		if ( strpos ( $value_config, ',Misc Item,') !== FALSE ) {
			
		}
	}
	
	
	/* Held Orders */
	if ( $classification=='held' ) {
		$date			= date('Y-m-d');
		$held_result	= mysqli_query ( $dbc, "SELECT `orderid`, `order_total`, `date_time`, `comments` FROM `pos_touch_temp_order` WHERE `hold_order`=1 AND DATE(`date_time`)='$date'" );
		$num_rows		= mysqli_num_rows($held_result);
		
		if ( $num_rows>0 ) { ?>
			<h3 class="col-sm-12 double-gap-bottom">Held Orders</h3>
			<div class="col-sm-12">
			<table class="table table-bordered">
				<tr class="hidden-xs hidden-sm">
					<th>Order #</th>
					<th>Order Total</th>
					<th>Order Date &amp; Time</th>
					<th>Comments</th>
					<th>Function</th>
				</tr><?php
				
				while ( $row=mysqli_fetch_array($held_result) ) { ?>
					<tr>
						<td data-title="Order #"><?php echo $row['orderid']; ?></td>
						<td data-title="Order Total"><?php echo '$' . number_format ( $row['order_total'], 2 ); ?></td>
						<td data-title="Order Date &amp; Time"><?php echo $row['date_time']; ?></td>
						<td data-title="Comments"><?php echo $row['comments']; ?></td>
						<td><a id="serviceHeld_<?php echo $row['orderid']; ?>" href="" onclick="serviceHeldOrder(this);">Service</a> | <a id="cancelHeld_<?php echo $row['orderid']; ?>" href="" onclick="cancelHeldOrder(this);">Cancel</a></td>
					</tr><?php
				} ?>
			</table>
			</div><?php
			
		} else {
			echo '<h3 class="col-sm-12">No Record Found.</h3>';
		}
	}
}
?>