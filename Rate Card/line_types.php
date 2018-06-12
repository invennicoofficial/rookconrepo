<?php $contact_tabs = explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') FROM `general_configuration` WHERE `name` IN ('clientinfo_tabs','contacts_tabs','contacts3_tabs','contactrolodex_tabs')"))[0]);
$tiles = [];
if(in_array('Clients',$contact_tabs)) { $tiles['Clients'] = 'clients'; }
if(in_array('Contractor',$contact_tabs)) { $tiles['Contractor'] = 'contractor'; }
if(in_array('Customer',$contact_tabs)) { $tiles['Customer'] = 'customer'; }
if(tile_enabled($dbc, 'equipment')) { $tiles['Equipment'] = 'equipment'; }
if(tile_enabled($dbc, 'inventory')) { $tiles['Inventory'] = 'inventory'; }
if(tile_enabled($dbc, 'labour')) { $tiles['Labour'] = 'labour'; }
if(tile_enabled($dbc, 'material')) { $tiles['Material'] = 'material'; }
$tiles['Position'] = 'position';
if(tile_enabled($dbc, 'products')) { $tiles['Products'] = 'products'; }
if(tile_enabled($dbc, 'services')) { $tiles['Services'] = 'services'; }
$tiles['Staff'] = 'staff';
if(tile_enabled($dbc, 'vpl')) { $tiles['Vendor Pricelist'] = 'vpl'; }
$tiles['Miscellaneous'] = 'miscellaneous';
$tiles['Note'] = 'notes';
$src_options = [];
foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name`, `name`, `category` FROM `contacts` WHERE `deleted`=0 AND `status` > 0 AND `category` IN ('Clients','Contractor','Customer',".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY.""), 'last_name') as $contact) {
	$src_options[] = [ 'id' => $contact['contactid'], 'label' => $contact['name'].($contact['name'] != '' && $contact['last_name'] != '' ? ' - ' : '').$contact['first_name'].' '.$contact['last_name'], 'category' => '', 'tile_name' => $contact['category'] ];
}
foreach([ "SELECT `equipmentid` id, CONCAT(IFNULL(`category`,''),': ',IFNULL(`make`,''),' ',IFNULL(`model`,''),' ',IFNULL(`label`,''),' ',IFNULL(`unit_number`,'')) label, `category` category, '' type, 'equipment' tile_name FROM `equipment` WHERE `deleted`=0 ORDER BY `category`, `make`, `model`, `label`, `unit_number`",
	"SELECT `inventoryid` id, CONCAT(IFNULL(`category`,''),': ',IFNULL(`product_name`,''),' ',IFNULL(`name`,''),' ',IFNULL(`part_no`,'')) label, `category` category, '' type, 'inventory' tile_name FROM `inventory` WHERE `deleted`=0 ORDER BY `category`, `product_name`",
	"SELECT `labourid` id, CONCAT(IFNULL(`labour_type`,''),' ',IFNULL(`category`,''),' ',IFNULL(`heading`,''),' ',IFNULL(`name`,'')) label, `category` category, '' type, 'labour' tile_name FROM `labour` WHERE `deleted`=0 ORDER BY `labour_type`, `category`, `heading`, `name`",
	"SELECT `materialid` id, CONCAT(IFNULL(`category`,''),' ',IFNULL(`sub_category`,''),' ',IFNULL(`name`,'')) label, `category` category, '' type, 'material' tile_name FROM `material` WHERE `deleted`=0 ORDER BY `category`, `sub_category`, `name`",
	"SELECT `position_id` id, `name` label, '' category, '' type, 'position' tile_name FROM `positions` WHERE `deleted`=0 ORDER BY `name`",
	"SELECT `productid` id, CONCAT(IFNULL(`category`,''),' ',IFNULL(`heading`,'')) label, `category` category, '' type, 'products' tile_name FROM `products` WHERE `deleted`=0 ORDER BY `category`, `heading`",
	"SELECT `serviceid` id, CONCAT(IFNULL(`category`,''),' ',IFNULL(`service_type`,''),' ',IFNULL(`heading`,'')) label, `category` category, `service_type` type, 'services' tile_name FROM `services` WHERE `deleted`=0 ORDER BY `category`, `heading`",
	"SELECT `inventoryid` id, CONCAT(IFNULL(`category`,''),' ',IFNULL(`product_name`,''),' ',IFNULL(`name`,'')) label, `category` category, '' type, 'vpl' tile_name FROM `vendor_price_list` WHERE `deleted`=0 ORDER BY `category`, `product_name`, `name`" ] as $sql) {
	$option_list = mysqli_query($dbc, $sql);
	while($option = mysqli_fetch_array($option_list)) {
		$src_options[] = $option;
	}
}
$regions = array_filter(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') FROM `general_configuration` WHERE `name` LIKE '%_region'"))[0]));
$locations = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `con_locations` SEPARATOR ',') FROM `field_config_contacts`"))[0])));
$classifications = array_filter(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') FROM `general_configuration` WHERE `name` LIKE '%_classification'"))[0])); ?>