<?php include_once('database_connection.php');
if(!file_exists('Database Backups/invoice_extrap.sql')) {
	echo "Saving Current Tables.\n";
	$file = fopen('Database Backups/invoice_extrap.sql','w');
	$data = mysqli_query($dbc, "SELECT * FROM `invoice`");
	while($row = mysqli_fetch_assoc($data)) {
		$fields = [];
		$values = [];
		foreach($row as $field => $value) {
			if($field != 'invoiceid') {
				$fields[] = $field;
				$values[] = $value;
			}
		}
		$line = "INSERT INTO `invoice` (`".implode('`,`',$fields)."`) VALUES ('".implode("','",$values)."');\n";
		fwrite($file, $line);
	}
	$data = mysqli_query($dbc, "SELECT * FROM `invoice_insurer`");
	while($row = mysqli_fetch_assoc($data)) {
		$fields = [];
		$values = [];
		foreach($row as $field => $value) {
			if($field != 'invoiceinsurerid') {
				$fields[] = $field;
				$values[] = $value;
			}
		}
		$line = "INSERT INTO `invoice_insurer` (`".implode('`,`',$fields)."`) VALUES ('".implode("','",$values)."');\n";
		fwrite($file, $line);
	}
	$data = mysqli_query($dbc, "SELECT * FROM `invoice_patient`");
	while($row = mysqli_fetch_assoc($data)) {
		$fields = [];
		$values = [];
		foreach($row as $field => $value) {
			if($field != 'invoicepatientid') {
				$fields[] = $field;
				$values[] = $value;
			}
		}
		$line = "INSERT INTO `invoice_patient` (`".implode('`,`',$fields)."`) VALUES ('".implode("','",$values)."');\n";
		fwrite($file, $line);
	}
	fclose($file);
}
echo "Extrapolating Insurer Payments<br />\n";
$invoice_paid_amounts = [];
$insurer_invoices = mysqli_query($dbc, "SELECT `invoice`.`invoiceid`, `invoice`.`serviceid`, `invoice`.`fee`, `invoice`.`inventoryid`, `invoice`.`sell_price`, `invoice`.`total_price`, `invoice`.`gst_amt`, `invoice`.`final_price`, `invoice_insurer`.`invoiceinsurerid`, `invoice`.`invoice_date`, `invoice_insurer`.`insurer_price`, `invoice_insurer`.`injury_type`, `invoice_insurer`.`insurerid`, `invoice_insurer`.`paid`, `invoice_insurer`.`deposit_number`, `invoice_insurer`.`paid_type`, `invoice_insurer`.`paid_date`, `invoice_insurer`.`date_deposit`, `invoice_insurer`.`collection`, `invoice_insurer`.`ui_invoiceid` FROM `invoice` LEFT JOIN `invoice_insurer` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid` WHERE `invoice_insurer`.`service_category`='' AND `invoice_insurer`.`service_name`='' AND `invoice_insurer`.`product_name`=''");
while($invoice = mysqli_fetch_array($insurer_invoices)) {
	$services = explode(',',$invoice['serviceid']);
	$fees = explode(',',$invoice['fee']);
	foreach($services as $i => $serviceid) {
		if($serviceid == '') {
			unset($fees[$i]);
			unset($services[$i]);
		}
	}
	$products = explode(',',$invoice['inventoryid']);
	$prices = explode(',',$invoice['sell_price']);
	foreach($products as $i => $productid) {
		if($productid == '') {
			unset($prices[$i]);
			unset($products[$i]);
		}
	}
	if(count($products) + count($services) == 1) {
		$gst_total = $invoice['gst_amt'];
		$gst = 0;
		if($gst_total > 0) {
			$gst = round($invoice['insurer_price'] * 0.05,2);
		}
		foreach($services as $serviceid) {
			$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `heading`, `gst_exempt` FROM `services` WHERE `serviceid`='$serviceid' UNION SELECT 'Service', 'Unknown Service', 0"));
			if(!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `service_category`='".$service['category']."', `service_name`='".$service['heading']."', `gst_amt`='".$invoice['gst_amt']."' WHERE `invoiceinsurerid`='".$invoice['invoiceinsurerid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
		foreach($products as $productid) {
			$product = mysqli_fetch_array(mysqli_query($dbc, "SELECT `name` FROM `inventory` WHERE `inventoryid`='$productid' UNION SELECT 'Unknown Product'"));
			if(!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `product_name`='".$product['name']."', `gst_amt`='".$invoice['gst_amt']."' WHERE `invoiceinsurerid`='".$invoice['invoiceinsurerid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
	} else {
		$ins_or_update = 'UPDATE';
		$paid = $invoice['insurer_price'];
		$gst_total = $invoice['gst_amt'];
		foreach($products as $i => $productid) {
			$product_info = mysqli_fetch_array(mysqli_query($dbc, "SELECT `name` FROM `inventory` WHERE `inventoryid`='$productid' UNION SELECT 'Unknown Product'"));
			$price = $prices[$i];
			$gst = round($price * 0.05,2);
			$gst_total -= $gst;
			$current_amt = $price + $gst;
			if(($paid > 0 && $current_amt > $paid) || ($paid < 0 && $current_amt < $paid)) {
				$current_amt = $paid;
			}
			if($ins_or_update == 'INSERT') {
				if($current_amt != 0) {
					if(!mysqli_query($dbc, "INSERT INTO `invoice_insurer` (`invoiceid`, `injury_type`, `invoice_date`, `insurerid`, `insurer_price`, `service_category`, `service_name`, `product_name`, `paid`, `deposit_number`, `paid_type`, `paid_date`, `date_deposit`, `collection`, `ui_invoiceid`, `gst_amt`)
						VALUES ('".$invoice['invoiceid']."', '".$invoice['injury_type']."', '".$invoice['invoice_date']."', '".$invoice['insurerid']."', '".$current_amt."', '', '', '".$product_info['name']."', '".$invoice['paid']."', '".$invoice['deposit_number']."', '".$invoice['paid_type']."', '".$invoice['paid_date']."', '".$invoice['date_deposit']."', '".$invoice['collection']."', '".$invoice['ui_invoiceid']."', '$gst')")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
				}
			} else {
				if(!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `insurer_price`='$current_amt', `gst_amt`='$gst', `product_name`='".$product_info['name']."' WHERE `invoiceinsurerid`='".$invoice['invoiceinsurerid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
			}
			
			$paid -= $current_amt;
			$invoice_paid_amounts[$invoice['invoiceid']]['product'][$products[$i]] += $current_amt;
			
			//Next time for this invoice, insert a row instead of updating the current amount
			$ins_or_update = 'INSERT';
		}
		foreach($services as $i => $serviceid) {
			$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `heading`, `gst_exempt` FROM `services` WHERE `serviceid`='$serviceid' UNION SELECT 'Service', 'Unknown Service', 0"));
			$fee = $fees[$i];
			$gst = round(($service['gst_exempt'] == 1 ? 0 : $fee * 0.05),2);
			if($gst > $gst_total) {
				$gst = $gst_total;
			}
			$gst_total -= $gst;
			$price = $fee + $gst;
			if(($paid > 0 && $price > $paid) || ($paid < 0 && $price < $paid)) {
				$price = $paid;
			}
			if($ins_or_update == 'INSERT') {
				if($price != 0) {
					if(!mysqli_query($dbc, "INSERT INTO `invoice_insurer` (`invoiceid`, `injury_type`, `invoice_date`, `insurerid`, `insurer_price`, `service_category`, `service_name`, `product_name`, `paid`, `deposit_number`, `paid_type`, `paid_date`, `date_deposit`, `collection`, `ui_invoiceid`, `gst_amt`)
						VALUES ('".$invoice['invoiceid']."', '".$invoice['injury_type']."', '".$invoice['invoice_date']."', '".$invoice['insurerid']."', '".$price."', '".$service['category']."', '".$service['heading']."', '', '".$invoice['paid']."', '".$invoice['deposit_number']."', '".$invoice['paid_type']."', '".$invoice['paid_date']."', '".$invoice['date_deposit']."', '".$invoice['collection']."', '".$invoice['ui_invoiceid']."', '$gst')")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
				}
			} else {
				if(!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `insurer_price`='$price', `gst_amt`='$gst', `service_category`='".$service['category']."', `service_name`='".$service['heading']."' WHERE `invoiceinsurerid`='".$invoice['invoiceinsurerid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
			}
			
			$paid -= $price;
			$invoice_paid_amounts[$invoice['invoiceid']]['service'][$services[$i]] += $price;
			
			//Next time for this invoice, insert a row instead of updating the current amount
			$ins_or_update = 'INSERT';
		}
		if($paid != 0) {
			if(!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `insurer_price`=`insurer_price`+$paid WHERE `invoiceinsurerid`='".$invoice['invoiceinsurerid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
		if($gst_total != 0) {
			if(!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `gst_amt`=`gst_amt`+$gst_total WHERE `invoiceinsurerid`='".$invoice['invoiceinsurerid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
	}
}
$insurer_invoices = mysqli_query($dbc, "SELECT * FROM `invoice_insurer` WHERE `service_category`='' AND `service_name`='' AND `product_name`=''");
while($invoice = mysqli_fetch_array($insurer_invoices)) {
	$service_cat = $invoice['injury_type'];
	if($service_cat == '') {
		$service_cat = 'Unknown';
	}
	if(!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `service_category`='".$service_cat."' WHERE `invoiceinsurerid`='".$invoice['invoiceinsurerid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
}
echo "Insurer Payments Extrapolated!<br />\n";
echo "Extrapolating Patient Payments<br />\n";
$invoice_paid_amounts = [];
$patient_invoices = mysqli_query($dbc, "SELECT `invoice`.`invoiceid`, `invoice`.`serviceid`, `invoice`.`fee`, `invoice`.`inventoryid`, `invoice`.`sell_price`, `invoice`.`total_price`, `invoice`.`gst_amt`, `invoice`.`final_price`, `invoice_patient`.`invoicepatientid`, `invoice`.`invoice_date`, `invoice_patient`.`patient_price`, `invoice_patient`.`injury_type`, `invoice_patient`.`patientid`, `invoice_patient`.`paid`, `invoice_patient`.`deposit_number`, `invoice_patient`.`paid`, `invoice_patient`.`paid_date`, `invoice_patient`.`date_deposit`, `invoice_patient`.`collection`, `invoice_patient`.`ui_invoiceid` FROM `invoice` LEFT JOIN `invoice_patient` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid` WHERE `invoice_patient`.`service_category`='' AND `invoice_patient`.`service_name`='' AND `invoice_patient`.`product_name`=''");
while($invoice = mysqli_fetch_array($patient_invoices)) {
	$services = explode(',',$invoice['serviceid']);
	$fees = explode(',',$invoice['fee']);
	foreach($services as $i => $serviceid) {
		if($serviceid == '') {
			unset($fees[$i]);
			unset($services[$i]);
		}
	}
	$products = explode(',',$invoice['inventoryid']);
	$prices = explode(',',$invoice['sell_price']);
	foreach($products as $i => $productid) {
		if($productid == '') {
			unset($prices[$i]);
			unset($products[$i]);
		}
	}
	if(count($products) + count($services) == 1) {
		$gst_total = $invoice['gst_amt'];
		$gst = 0;
		if($gst_total > 0) {
			$gst = round($invoice['patient_price'] * 0.05,2);
		}
		foreach($services as $serviceid) {
			$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `heading`, `gst_exempt` FROM `services` WHERE `serviceid`='$serviceid' UNION SELECT 'Service', 'Unknown Service', 0"));
			if(!mysqli_query($dbc, "UPDATE `invoice_patient` SET `service_category`='".$service['category']."', `service_name`='".$service['heading']."', `gst_amt`='".$gst."' WHERE `invoicepatientid`='".$invoice['invoicepatientid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
		foreach($products as $productid) {
			$product = mysqli_fetch_array(mysqli_query($dbc, "SELECT `name` FROM `inventory` WHERE `inventoryid`='$productid' UNION SELECT 'Unknown Product'"));
			if(!mysqli_query($dbc, "UPDATE `invoice_patient` SET `product_name`='".$product['name']."', `gst_amt`='".$gst."' WHERE `invoicepatientid`='".$invoice['invoicepatientid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
	} else {
		$ins_or_update = 'UPDATE';
		$paid = $invoice['patient_price'];
		$gst_total = $invoice['gst_amt'];
		foreach($products as $i => $productid) {
			$product_info = mysqli_fetch_array(mysqli_query($dbc, "SELECT `name` FROM `inventory` WHERE `inventoryid`='$productid' UNION SELECT 'Unknown Product'"));
			$price = $prices[$i];
			$gst = $price * 0.05;
			$gst_total -= $gst;
			$current_amt = round($price + $gst,2);
			if(($paid > 0 && $current_amt > $paid) || ($paid < 0 && $current_amt < $paid)) {
				$current_amt = $paid;
			}
			if($ins_or_update == 'INSERT') {
				if($current_amt != 0) {
					if(!mysqli_query($dbc, "INSERT INTO `invoice_patient` (`invoiceid`, `injury_type`, `invoice_date`, `patientid`, `patient_price`, `service_category`, `service_name`, `product_name`, `paid`, `deposit_number`, `paid_date`, `collection`, `ui_invoiceid`, `gst_amt`)
						VALUES ('".$invoice['invoiceid']."', '".$invoice['injury_type']."', '".$invoice['invoice_date']."', '".$invoice['patientid']."', '".$current_amt."', '', '', '".$product_info['name']."', '".$invoice['paid']."', '".$invoice['deposit_number']."', '".$invoice['paid_date']."', '".$invoice['collection']."', '".$invoice['ui_invoiceid']."', '$gst')")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
				}
			} else {
				if(!mysqli_query($dbc, "UPDATE `invoice_patient` SET `patient_price`='$current_amt', `gst_amt`='$gst', `product_name`='".$product_info['name']."' WHERE `invoicepatientid`='".$invoice['invoicepatientid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
			}
			
			$paid -= $current_amt;
			$invoice_paid_amounts[$invoice['invoiceid']]['product'][$products[$i]] += $current_amt;
			
			//Next time for this invoice, insert a row instead of updating the current amount
			$ins_or_update = 'INSERT';
		}
		foreach($services as $i => $serviceid) {
			$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `heading`, `gst_exempt` FROM `services` WHERE `serviceid`='$serviceid' UNION SELECT 'Service', 'Unknown Service', 0"));
			$fee = $fees[$i];
			$gst = round(($service['gst_exempt'] == 1 ? 0 : $fee * 0.05),2);
			if($gst > $gst_total) {
				$gst = $gst_total;
			}
			$gst_total -= $gst;
			$price = $fee + $gst;
			if(($paid > 0 && $price > $paid) || ($paid < 0 && $price < $paid)) {
				$price = $paid;
			}
			if($ins_or_update == 'INSERT') {
				if($price != 0) {
					if(!mysqli_query($dbc, "INSERT INTO `invoice_patient` (`invoiceid`, `injury_type`, `invoice_date`, `patientid`, `patient_price`, `service_category`, `service_name`, `product_name`, `paid`, `deposit_number`, `paid_date`, `collection`, `ui_invoiceid`, `gst_amt`)
						VALUES ('".$invoice['invoiceid']."', '".$invoice['injury_type']."', '".$invoice['invoice_date']."', '".$invoice['patientid']."', '".$price."', '".$service['category']."', '".$service['heading']."', '', '".$invoice['paid']."', '".$invoice['deposit_number']."', '".$invoice['paid_date']."', '".$invoice['collection']."', '".$invoice['ui_invoiceid']."', '$gst')")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
				}
			} else {
				if(!mysqli_query($dbc, "UPDATE `invoice_patient` SET `patient_price`='$price', `gst_amt`='$gst', `service_category`='".$service['category']."', `service_name`='".$service['heading']."' WHERE `invoicepatientid`='".$invoice['invoicepatientid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
			}
			
			$paid -= $price;
			$invoice_paid_amounts[$invoice['invoiceid']]['service'][$services[$i]] += $price;
			
			//Next time for this invoice, insert a row instead of updating the current amount
			$ins_or_update = 'INSERT';
		}
		if($paid != 0) {
			if(!mysqli_query($dbc, "UPDATE `invoice_patient` SET `patient_price`=`patient_price`+$paid WHERE `invoicepatientid`='".$invoice['invoicepatientid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
		if($gst_total != 0) {
			if(!mysqli_query($dbc, "UPDATE `invoice_patient` SET `gst_amt`=`gst_amt`+$gst_total WHERE `invoicepatientid`='".$invoice['invoicepatientid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
		}
	}
}
$patient_invoices = mysqli_query($dbc, "SELECT * FROM `invoice_patient` WHERE `service_category`='' AND `service_name`='' AND `product_name`=''");
while($invoice = mysqli_fetch_array($patient_invoices)) {
	$service_cat = $invoice['injury_type'];
	if($service_cat == '') {
		$service_cat = 'Unknown';
	}
	if(!mysqli_query($dbc, "UPDATE `invoice_patient` SET `service_category`='".$service_cat."' WHERE `invoicepatientid`='".$invoice['invoicepatientid']."'")) { echo "Error: ".mysqli_error($dbc)."<br />\n"; }
}
echo "Patient Payments Extrapolated!<br />\n";

mysqli_query($dbc, "UPDATE `invoice_insurer` SET gst_amt = ROUND(gst_amt,2)");
mysqli_query($dbc, "UPDATE `invoice_patient` SET gst_amt = ROUND(gst_amt,2)");
$invoice_list = mysqli_query($dbc, "SELECT `invoice`.`invoiceid`, `invoice`.`gst_amt`, SUM(`invoice_pay`.`gst_amt`) gst_sum, SUM(IF(`product_name`='',1,0)) srv_num, SUM(IF(`product_name`='',0,1)) prod_num FROM `invoice` LEFT JOIN (SELECT `invoiceinsurerid` mainkey, `invoiceid`, `sub_total`, `gst_amt`, `insurer_price` final, `product_name` FROM `invoice_insurer` UNION SELECT `invoicepatientid` mainkey, `invoiceid`, `sub_total`, `gst_amt`, `patient_price` final, `product_name` FROM `invoice_patient`) `invoice_pay` ON `invoice`.`invoiceid`=`invoice_pay`.`invoiceid` GROUP BY `invoice`.`invoiceid`, `invoice`.`gst_amt` HAVING `invoice`.`gst_amt` != SUM(`invoice_pay`.`gst_amt`)");
while($invoice = mysqli_fetch_array($invoice_list)) {
	if($invoice['gst_amt'] == '0.00') {
		mysqli_query($dbc, "UPDATE `invoice_patient` SET `gst_amt` = 0 WHERE `invoiceid`='".$invoice['invoiceid']."'");
		mysqli_query($dbc, "UPDATE `invoice_insurer` SET `gst_amt` = 0 WHERE `invoiceid`='".$invoice['invoiceid']."'");
	} else if($invoice['srv_num'] > 0) {
		$gst = round(($invoice['gst_amt'] - $invoice['gst_sum']) / $invoice['srv_num'],2);
		mysqli_query($dbc, "UPDATE `invoice_patient` SET `gst_amt` = `gst_amt` + '$gst' WHERE `invoiceid`='".$invoice['invoiceid']."' AND `product_name`=''");
		mysqli_query($dbc, "UPDATE `invoice_insurer` SET `gst_amt` = `gst_amt` + '$gst' WHERE `invoiceid`='".$invoice['invoiceid']."' AND `product_name`=''");
	} else if($invoice['prod_num'] > 0) {
		$gst = round(($invoice['gst_amt'] - $invoice['gst_sum']) / $invoice['prod_num'],2);
		mysqli_query($dbc, "UPDATE `invoice_patient` SET `gst_amt` = `gst_amt` + '$gst' WHERE `invoiceid`='".$invoice['invoiceid']."' AND `product_name`!=''");
		mysqli_query($dbc, "UPDATE `invoice_insurer` SET `gst_amt` = `gst_amt` + '$gst' WHERE `invoiceid`='".$invoice['invoiceid']."' AND `product_name`!=''");
	}
}
$invoice_list = mysqli_query($dbc, "SELECT `invoice`.`invoiceid`, `invoice`.`gst_amt`, SUM(`invoice_pay`.`gst_amt`) gst_sum, SUM(IF(`product_name`='',1,0)) srv_num, SUM(IF(`product_name`='',0,1)) prod_num FROM `invoice` LEFT JOIN (SELECT `invoiceinsurerid` mainkey, `invoiceid`, `sub_total`, `gst_amt`, `insurer_price` final, `product_name` FROM `invoice_insurer` UNION SELECT `invoicepatientid` mainkey, `invoiceid`, `sub_total`, `gst_amt`, `patient_price` final, `product_name` FROM `invoice_patient`) `invoice_pay` ON `invoice`.`invoiceid`=`invoice_pay`.`invoiceid` GROUP BY `invoice`.`invoiceid`, `invoice`.`gst_amt` HAVING `invoice`.`gst_amt` != SUM(`invoice_pay`.`gst_amt`)");
while($invoice = mysqli_fetch_array($invoice_list)) {
	$gst_diff = round($invoice['gst_amt'] - $invoice['gst_sum'],2);
	$invoiceid = $invoice['invoiceid'];
	$source = mysqli_fetch_array(mysqli_query($dbc, "SELECT `invoiceinsurerid` mainkey, 'invoice_insurer' src_table, `invoiceid`, `sub_total`, `gst_amt`, `insurer_price` final, `product_name` FROM `invoice_insurer` WHERE `invoiceid`='$invoiceid' UNION SELECT `invoicepatientid` mainkey, 'invoice_patient' src_table, `invoiceid`, `sub_total`, `gst_amt`, `patient_price` final, `product_name` FROM `invoice_patient` WHERE `invoiceid`='$invoiceid'"));
	if($source['src_table'] == 'invoice_insurer') {
		mysqli_query($dbc, "UPDATE `invoice_insurer` SET `gst_amt`=`gst_amt`+'$gst_diff' WHERE `invoiceinsurerid`='".$source['mainkey']."'");
	} else {
		mysqli_query($dbc, "UPDATE `invoice_patient` SET `gst_amt`=`gst_amt`+'$gst_diff' WHERE `invoicepatientid`='".$source['mainkey']."'");
	}
}