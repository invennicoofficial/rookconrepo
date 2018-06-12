<?php //Cycle through each invoice and create line entries if they do not have any line records associated with them
$invoices = mysqli_query($dbc, "SELECT * FROM `invoice` WHERE `invoiceid` NOT IN (SELECT `invoiceid` FROM `invoice_lines`)");
while($invoice = mysqli_fetch_assoc($invoices)) {
	$insurer_pay_records = mysqli_query($dbc, "SELECT * FROM `invoice_insurer` WHERE `invoiceid`='{$invoice['invoiceid']}'");
	$patient_pay_records = mysqli_query($dbc, "SELECT * FROM `invoice_patient` WHERE `invoiceid`='{$invoice['invoiceid']}'");
	$insurer_payments = explode(',',$invoice['insurance_payment']);
	$insurer_payer = explode(',',$invoice['insurerid']);
	$services = explode(',',$invoice['serviceid']);
	$service_count = count(array_filter($services));
	foreach($services as $i => $service) {
		if($service > 0) {
			$fee = explode(',',$invoice['fee'])[$i];
			$admin_fee = explode(',',$invoice['admin_fee'])[$i];
			$comp = (mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) comp FROM `invoice_compensation` WHERE `invoiceid`='{$invoice['invoiceid']}' AND `serviceid`='$service'"))[0] > 0 ? 1 : 0);
			$description = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`,`heading`,`gst_exempt` FROM `services` WHERE `serviceid`='$service'"));
			$gst = ($description['gst_exempt'] == 1 ? 0 : $fee * 0.05);
			$exempt = $description['gst_exempt'];
			$total = $fee + $gst;
			$category = $description['category'];
			$description = $description['heading'];
			mysqli_query($dbc, "INSERT INTO `invoice_lines` (`invoiceid`, `category`, `item_id`, `heading`, `description`, `compensation`, `quantity`, `unit_price`, `admin_fee`, `sub_total`, `gst`, `tax_exempt`, `total`) SELECT '{$invoice['invoiceid']}', 'service', '$service', '$category', '$description', '$comp', 1, '$fee', '$admin_fee', '$fee', '$gst', '$exempt', '$total' FROM (SELECT COUNT(*) rows FROM `invoice_lines` WHERE `invoiceid`='{$invoice['invoiceid']}' AND `category`='service') num WHERE num.rows < $service_count");
			$line = mysqli_insert_id($dbc);
		}
	}
	
	$inventorys = explode(',',$invoice['inventoryid']);
	$inventory_count = count(array_filter($inventorys));
	foreach($inventorys as $i => $inventory) {
		if($inventory > 0) {
			$price = explode(',',$invoice['sell_price'])[$i];
			$type = explode(',',$invoice['invtype'])[$i];
			$qty = explode(',',$invoice['quantity'])[$i];
			$unit = $qty > 0 ? $price / $qty : 0;
			$description = mysqli_fetch_array(mysqli_query($dbc, "SELECT `product_name`,`gst_exempt` FROM `inventory` WHERE `inventoryid`='$inventory'"));
			$gst = ($description['gst_exempt'] == 1 ? 0 : $fee * 0.05);
			$exempt = $description['gst_exempt'];
			$total = $fee + $gst;
			$description = $description['product_name'];
			mysqli_query($dbc, "INSERT INTO `invoice_lines` (`invoiceid`, `category`, `item_id`, `description`, `quantity`, `unit_price`, `sub_total`, `gst`, `tax_exempt`, `total`) SELECT '{$invoice['invoiceid']}', 'inventory', '$inventory', '$description', '$qty', '$unit', '$price', '$gst', '$exempt', '$total' FROM (SELECT COUNT(*) rows FROM `invoice_lines` WHERE `invoiceid`='{$invoice['invoiceid']}' AND `category`='inventory') num WHERE num.rows < $inventory_count");
			$line = mysqli_insert_id($dbc);
		}
	}
	
	$packages = explode(',',$invoice['packageid']);
	$package_count = count(array_filter($packages));
	foreach($packages as $i => $package) {
		if($package > 0) {
			$price = explode(',',$invoice['sell_price'])[$i];
			$type = explode(',',$invoice['invtype'])[$i];
			$qty = explode(',',$invoice['quantity'])[$i];
			$unit = $qty > 0 ? $price / $qty : 0;
			$description = mysqli_fetch_array(mysqli_query($dbc, "SELECT `product_name`,`gst_exempt` FROM `package` WHERE `packageid`='$package'"));
			$gst = ($description['gst_exempt'] == 1 ? 0 : $fee * 0.05);
			$exempt = $description['gst_exempt'];
			$total = $fee + $gst;
			$heading = $description['category'];
			$description = $description['heading'];
			mysqli_query($dbc, "INSERT INTO `invoice_lines` (`invoiceid`, `category`, `item_id`, `heading`, `description`, `quantity`, `unit_price`, `sub_total`, `gst`, `tax_exempt`, `total`) SELECT '{$invoice['invoiceid']}', 'package', '$package', '$heading', '$description', '$qty', '$unit', '$price', '$gst', '$exempt', '$total' FROM (SELECT COUNT(*) rows FROM `invoice_lines` WHERE `invoiceid`='{$invoice['invoiceid']}' AND `category`='package') num WHERE num.rows < $package_count");
			$line = mysqli_insert_id($dbc);
		}
	}
}

//Create Payment lines for every payment that has already been completed for patients, and every entry for insurers
mysqli_query($dbc, "INSERT INTO `invoice_payment` (`invoiceid`, `contactid`, `payer_id`, `gst`, `amount`, `paid`, `deposit_number`, `date_paid`, `collection`, `grouped_invoiceid`, `receipt_file`) SELECT `invoiceid`, `patientid`, `patientid`, `gst_amt`, `patient_price`, 1, `deposit_number`, `paid_date`, `collection`, `ui_invoiceid`, `receipt_file` FROM `invoice_patient` WHERE `paid` != 'On Account' AND `invoiceid` NOT IN (SELECT `invoiceid` FROM `invoice_payment` WHERE `payer_id`=`contactid`)");
mysqli_query($dbc, "INSERT INTO `invoice_payment` (`invoiceid`, `line_id`, `contactid`, `payer_id`, `gst`, `amount`, `paid`, `payment_method`, `deposit_number`, `date_paid`, `date_deposited`, `collection`, `grouped_invoiceid`) SELECT `invoice_insurer`.`invoiceid`, `invoice_lines`.`line_id`, `invoice`.`patientid`, `invoice_insurer`.`insurerid`, `invoice_insurer`.`gst_amt`, `invoice_insurer`.`insurer_price`, `invoice_insurer`.`paid`='Yes', `invoice_insurer`.`paid_type`, `invoice_insurer`.`deposit_number`, `invoice_insurer`.`paid_date`, `invoice_insurer`.`date_deposit`, `invoice_insurer`.`collection`, `invoice_insurer`.`ui_invoiceid` FROM `invoice_insurer` LEFT JOIN `invoice` ON `invoice_insurer`.`invoiceid`=`invoice`.`invoiceid` LEFT JOIN `invoice_lines` ON `invoice_lines`.`invoiceid`=`invoice`.`invoiceid` LEFT JOIN `services` ON `invoice_lines`.`category`='service' AND `invoice_lines`.`item_id`=`services`.`serviceid` AND `invoice_insurer`.`service_name` = `services`.`heading` LEFT JOIN `inventory` ON `invoice_lines`.`category`='inventory' AND `invoice_lines`.`item_id`=`inventory`.`inventoryid` AND `invoice_insurer`.`product_name` = `inventory`.`product_name` LEFT JOIN `package` ON `invoice_lines`.`category`='package' AND `invoice_lines`.`item_id`=`package`.`packageid` AND `invoice_insurer`.`service_name` = `package`.`heading` WHERE (`services`.`serviceid` IS NOT NULL OR `inventory`.`inventoryid` IS NOT NULL OR `package`.`packageid` IS NOT NULL) AND `invoice_insurer`.`invoiceid` NOT IN (SELECT `invoiceid` FROM `invoice_payment` WHERE `payer_id` != `contactid`)");