<?php
/*
Dashboard
FFM
*/

?>



		<?php
        // Kelsey
		mysqli_query($dbc, "CREATE TABLE order_lists
(
inventoryid varchar(50000),
order_title varchar(500),
include_in_pos varchar(500),
include_in_po varchar(500),
include_in_so varchar(500),
deleted varchar(500)
)");

		mysqli_query($dbc, "alter table order_lists add column `order_id` int(10) unsigned primary KEY AUTO_INCREMENT");
		mysqli_query($dbc, "ALTER TABLE `order_lists` CHANGE `deleted` `deleted` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0'");
		mysqli_query($dbc, "ALTER TABLE `admin_tile_config` add column `ffmsupport` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `admin_tile_config` add column `archiveddata` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `tile_config` add column `ffmsupport` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `tile_config` add column `archiveddata` varchar(500)");
		
		//CREATE PRIVILEGES LOG
		mysqli_query($dbc, "CREATE TABLE security_privileges_log
(
tile varchar(50000),
level varchar(500),
privileges varchar(500),
contact varchar(500),
deleted varchar(500)
)");
mysqli_query($dbc, "alter table security_privileges_log add column `log_id` int(10) unsigned primary KEY AUTO_INCREMENT");
mysqli_query($dbc, "ALTER TABLE `security_privileges_log` add column `date_time` varchar(500)");
	mysqli_query($dbc, "ALTER TABLE `security_privileges_log` CHANGE `deleted` `deleted` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0'");

//CREATE IMPORT/EXPORT LOG
		mysqli_query($dbc, "CREATE TABLE import_export_log
(
`table_name` varchar(500),
`type` varchar(200),
`description` varchar(500),
`date_time` varchar(500),
`contact` varchar(500),
`deleted` varchar(500),
`log_id` int(10) unsigned primary KEY AUTO_INCREMENT
)");
	mysqli_query($dbc, "ALTER TABLE `import_export_log` CHANGE `deleted` `deleted` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0'");
		mysqli_query($dbc, "alter table order_lists add column `tile` varchar(155) NULL");
		mysqli_query($dbc, "ALTER TABLE `sales_order` add column `cross_software` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `sales_order` add column `software_author` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `purchase_orders` add column `cross_software` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `point_of_sell` add column `cross_software` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `sales_order` add column `cross_software_posid` varchar(500)");
		
		mysqli_query($dbc, "CREATE TABLE vendor_list_2 LIKE inventory");
		mysqli_query($dbc, "INSERT INTO vendor_list_2 SELECT * FROM vendor_price_list");
		
		mysqli_query($dbc, "ALTER TABLE `driving_log_timer` add column `inspection_mode` varchar(500)");
		
		
		
			$software_url = "http://" . $_SERVER['SERVER_NAME'];
			if(($software_url == 'http://www.washtechsoftware.com' || $software_url == 'http://washtech.freshfocuscrm.com' || $software_url == 'http://localhost')) { 
			}
			
			mysqli_query($dbc, "ALTER TABLE `driving_log` add column `audit_shift_time` varchar(500)");
			mysqli_query($dbc, "ALTER TABLE `driving_log` add column `audit_drive_time` varchar(500)");
			mysqli_query($dbc, "ALTER TABLE `driving_log` add column `audit_cycle_time` varchar(500)");
			mysqli_query($dbc, "ALTER TABLE `driving_log` add column `audit_off_duty` varchar(500)");
			mysqli_query($dbc, "ALTER TABLE `driving_log` add column `audit_drive_sixteen` varchar(500)");
			mysqli_query($dbc, "ALTER TABLE `driving_log` add column `audit_dismiss` varchar(500)");
			
		//CREATE IMPORT/EXPORT LOG
		mysqli_query($dbc, "CREATE TABLE user_settings
		(`contactid` varchar(500),
		`classic_menu_size` varchar(200),
		`dropdown_menu_size` varchar(200))");
		mysqli_query($dbc, "ALTER TABLE `user_settings` add column `tile_size` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `user_settings` add column `calendar_list_view` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `inventory` add column `digital_count_qty` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `inventory` add column `expected_inventory` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `driving_log` ADD `off_duty_since_last_login` VARCHAR(255) NOT NULL COMMENT 'This column is to indicate that this driving log is only for tracking the off duty hours since the user has last created a Driving Log.'");
		mysqli_query($dbc, "alter table order_lists add column `contactid` varchar(155) NULL COMMENT 'This column is for attaching businesses, vendors, or any other category of contact to the order list.'");
		mysqli_query($dbc, "alter table purchase_orders add column `spreadsheet_name` varchar(155) NULL COMMENT 'This column holds the name of the spreadsheet that was created with this purchase order (if a spreadsheet was created).'");
		
		
		//CREATE Bill of material LOG
		mysqli_query($dbc, "CREATE TABLE bill_of_material_log
					(
					`inventoryid` varchar(500),
					`pieces_of_inventoryid` varchar(200) NULL COMMENT 'This column tracks which materials (inventory) were used to create this new inventory item.',
					`date_time` varchar(500),
					`contact` varchar(500),
					`type` varchar(500),
					`deleted` varchar(500),
					`log_id` int(10) unsigned primary KEY AUTO_INCREMENT
					)");
		mysqli_query($dbc, "ALTER TABLE `purchase_orders` add column `cross_software` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `purchase_orders` add column `software_author` varchar(500)");
		mysqli_query($dbc, "ALTER TABLE `inventory_change_log` add column `location_of_change` varchar(500)");
		
		mysqli_query($dbc, "ALTER TABLE `user_settings` add column `newsboard_redirect` varchar(500)");
				mysqli_query($dbc, "ALTER TABLE `driving_log` add column `complete` varchar(500)");
				mysqli_query($dbc, "ALTER TABLE `driving_log_timer` CHANGE `amendments_status` `amendments_status` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'Approved'");
				mysqli_query($dbc, "ALTER TABLE `purchase_orders_product` add column `additional_qty_received` varchar(500)");
				
				mysqli_query($dbc, "ALTER TABLE `bill_of_material_log` add column `old_pieces_of_inventoryid` varchar(500)");
				
				mysqli_query($dbc, "ALTER TABLE `inventory` add column `digital_count_qty_multiple` varchar(500) COMMENT 'This column tracks multiple actual inventories of the same inventory item (useful when counting inventory in separate locations).'");
				mysqli_query($dbc, "ALTER TABLE inventory
DROP COLUMN digital_count_qty_multiple_location");
				mysqli_query($dbc, "ALTER TABLE `point_of_sell` add column `software_author` varchar(500) COMMENT 'This column is used to track which software created the POS (useful in cross-software functionality).'");
				mysqli_query($dbc, "ALTER TABLE `point_of_sell` add column `software_seller` varchar(500) COMMENT '(Useful in cross-software functionality.) If software A buys inventory from software B, this column stores Software B.'");
				mysqli_query($dbc, "ALTER TABLE `purchase_orders` add column `software_seller` varchar(500) COMMENT '(Useful in cross-software functionality.) If software A orders inventory from software B, this column stores Software B.'");
				mysqli_query($dbc, "ALTER TABLE `sales_order` add column `software_seller` varchar(500) COMMENT '(Useful in cross-software functionality.) If software A orders inventory from software B, this column stores Software B as a value.'");
				
				mysqli_query($dbc, "ALTER TABLE `purchase_orders` add column `cross_software_approval` varchar(500) COMMENT '(Useful in cross-software functionality.) If software A approves P.O. created by software B, software B will have access to receive/pay/complete the P.O.'");
				mysqli_query($dbc, "ALTER TABLE `purchase_orders` add column `cross_software_disapproval` varchar(500) COMMENT '(Useful in cross-software functionality.) If software A disapproves P.O. created by software B, software A can leave a message in this column as to why the P.O. got disapproved.'");
				
				mysqli_query($dbc, "ALTER TABLE `support` ADD `deleted` VARCHAR(25) NOT NULL DEFAULT '0'");
				
				
				
				$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='facebook_link'"));
    
	
	$facebook_link = 'https://www.facebook.com/FreshFocusMediaYYC';
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='facebook_link'"));
	if($get_config['configid'] > 0) {
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('facebook_link', '$facebook_link')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
	
	$google_link = 'https://plus.google.com/+Freshfocusmediayyc/posts';
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='google_link'"));
    if($get_config['configid'] > 0) {
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('google_link', '$google_link')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
	
	$twitter_link = 'https://twitter.com/freshfocusmedia';
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='twitter_link'"));
    if($get_config['configid'] > 0) {
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('twitter_link', '$twitter_link')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
	
	$linkedin_link = 'https://www.linkedin.com/company/fresh-focus-media';
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='linkedin_link'"));
    if($get_config['configid'] > 0) {
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('linkedin_link', '$linkedin_link')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
	
	
	mysqli_query($dbc, "ALTER TABLE `newsboard` ADD `cross_software_approval` VARCHAR(255)");
	mysqli_query($dbc, "ALTER TABLE `newsboard` ADD `cross_software` VARCHAR(255)");
	
	mysqli_query($dbc, "ALTER TABLE `order_lists` ADD `custom_pricing` VARCHAR(255)");
	mysqli_query($dbc, "ALTER TABLE `order_lists` ADD `cross_software` VARCHAR(255)");
	
    echo 'DB Data #2 Done!<br>
		Thank you for doing DB Data! <img src="img/happy.png" width="25px" class="wiggle-me">  <img src="img/icons/like.png" width="25px" class="wiggle-me"><br>';
        ?>
