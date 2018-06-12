<?php $pageNum = (!empty($_GET['page']) ? $_GET['page'] : 1);
$rowsPerPage = 25;
$offset = ($pageNum - 1) * $rowsPerPage;

//Create Temporary Table for Calculations
$table_name = 'revenue_profit_loss';
if(!mysqli_query($dbc, "CREATE TEMPORARY TABLE IF NOT EXISTS `$table_name` (`invoice_date` VARCHAR(12), `type` VARCHAR(12), `heading` VARCHAR(200), `category` VARCHAR(200), `total` DECIMAL(10,2))")) {
	echo mysqli_error($dbc);
}
//Load in the Point of Sale data
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) SELECT pos.`invoice_date`, posp.`type_category`,
	IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other')))) heading,
	'' category,
	(posp.`quantity` * posp.`price`) total
	FROM `point_of_sell_product` posp LEFT JOIN `point_of_sell` pos ON posp.`posid`=pos.`posid`
		LEFT JOIN `products` p ON posp.`type_category`='product' AND posp.`inventoryid`=p.`productid`
		LEFT JOIN `inventory` i ON posp.`type_category`='inventory' AND posp.`inventoryid`=i.`inventoryid`
		LEFT JOIN `services` s ON posp.`type_category`='service' AND posp.`inventoryid`=s.`serviceid`
	WHERE `invoice_date` >= '$search_start' AND `invoice_date` <= '$search_end' AND pos.`status` IN ('Completed')
	ORDER BY IF(IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other')))) = 'Other', 'ZZZ', IFNULL(i.`category`, IFNULL(p.`category`, IFNULL(s.`category`, IFNULL(i.`name`, 'Other'))))), `heading`");
//Load in the Check Out Invoices
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'service', '', `invoice_patient`.`service_category`, `invoice_patient`.`sub_total`
	FROM `invoice_patient` LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_patient`.`paid`!='On Account' AND `invoice_patient`.`paid`!='' AND `invoice_patient`.`paid` IS NOT NULL AND `invoice_patient`.`service_category` != ''");
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'service', '', `invoice_insurer`.`service_category`, `invoice_insurer`.`sub_total`
	FROM `invoice_insurer` LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_insurer`.`paid`='Yes' AND `invoice_insurer`.`service_category` != ''");
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'inventory', '', IFNULL(`inventory`.`category`,'Miscellaneous'), MAX(`invoice_patient`.`sub_total`)
	FROM `invoice_patient` LEFT JOIN `invoice_lines` ON `invoice_patient`.`invoiceid`=`invoice_lines`.`invoiceid` AND `invoice_patient`.`product_name`=`invoice_lines`.`description`
		LEFT JOIN `inventory` ON `invoice_lines`.`item_id`=`inventory`.`inventoryid` OR (`invoice_lines`.`item_id` IS NULL AND `invoice_patient`.`product_name`=`inventory`.`name`)
		LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_patient`.`paid`!='On Account' AND `invoice_patient`.`paid`!='' AND
		`invoice_patient`.`paid` IS NOT NULL AND `invoice_patient`.`service_category` = '' GROUP BY `invoice_patient`.`invoicepatientid`");
mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `category`, `heading`, `total`)
	SELECT `invoice`.`invoice_date`, 'inventory', '', IFNULL(`inventory`.`category`,'Miscellaneous'), MAX(`invoice_insurer`.`sub_total`)
	FROM `invoice_insurer` LEFT JOIN `invoice_lines` ON `invoice_insurer`.`invoiceid`=`invoice_lines`.`invoiceid` AND `invoice_insurer`.`product_name`=`invoice_lines`.`description`
		LEFT JOIN `inventory` ON `invoice_lines`.`item_id`=`inventory`.`inventoryid` OR (`invoice_lines`.`item_id` IS NULL AND `invoice_insurer`.`product_name`=`inventory`.`name`)
		LEFT JOIN `invoice` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
	WHERE `invoice`.`invoice_date` >= '$search_start' AND `invoice`.`invoice_date` <= '$search_end' AND `invoice_insurer`.`paid`='Yes' AND `invoice_insurer`.`service_category` = '' GROUP BY `invoice_insurer`.`invoiceinsurerid`");
/*$invoices = mysqli_query($dbc, "SELECT `invoice`.`service_date`, `invoice`.`serviceid`, `invoice`.`fee`, `inventoryid`, `invoice`.`sell_price`, `invoice`.`quantity`,
	IFNULL(`invoice_patient`.`patient_price` / `invoice`.`total_price`,0) `patient_ratio`, IFNULL(`invoice_insurer`.`insurer_price` / `invoice`.`total_price`,0) `ins_ratio`, IFNULL(`invoice_patient`.`paid`,'No') `patient_paid`, IFNULL(`invoice_insurer`.`paid`,'No') `ins_paid`
	FROM `invoice` LEFT JOIN `invoice_patient` ON `invoice`.`invoiceid`=`invoice_patient`.`invoiceid`
	LEFT JOIN `invoice_insurer` ON `invoice`.`invoiceid`=`invoice_insurer`.`invoiceid`
	WHERE `service_date` >= '$starttime' AND `service_date` <= '$endtime' AND `invoice`.`deleted`=0");
while($invoice = mysqli_fetch_array($invoices)) {
	$serviceid = explode(',',$invoice['serviceid']);
	$servicefee = explode(',',$invoice['fee']);
	foreach($serviceid as $i => $id) {
		if(!empty($id)) {
			$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `heading` FROM `services` WHERE `serviceid`='$id'"));
			if($invoice['patient_paid'] != 'No' && $invoice['patient_paid'] != 'On Account' && $invoice['patient_paid'] != '') {
				$amt = $servicefee[$i] * $invoice['patient_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['heading']."', '".$service['category']."', '".$amt."')");
			}
			if($invoice['insurer_paid'] == 'Yes') {
				$amt = $servicefee[$i] * $invoice['insurer_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['heading']."', '".$service['category']."', '".$amt."')");
			}
		}
	}
	$inventory = explode(',',$invoice['inventoryid']);
	$inventoryprice = explode(',',$invoice['sell_price']);
	$inventoryqty = explode(',',$invoice['quantity']);
	foreach($inventory as $i => $id) {
		if(!empty($id) && $inventoryqty[$i] > 0) {
			$inventory = mysqli_fetch_array(mysqli_query($dbc, "SELECT `category`, `name` FROM `inventory` WHERE `inventoryid`='$id'"));
			$total = $inventoryprice[$i] * $inventoryqty[$i];
			if($invoice['patient_paid'] != 'No' && $invoice['patient_paid'] != 'On Account') {
				$amt = $total * $invoice['patient_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['category']."', '".$service['heading']."', '".$amt."')");
			}
			if($invoice['insurer_paid'] == 'Yes') {
				$amt = $total * $invoice['insurer_ratio'];
				mysqli_query($dbc, "INSERT INTO `$table_name` (`invoice_date`, `type`, `heading`, `category`, `total`) VALUES ('".$invoice['service_date']."', 'service', '".$service['category']."', '".$service['heading']."', '".$amt."')");
			}
		}
	}
}*/

for ($year = $startyear; $year <= $endyear; $year++) {
	//Pull revenue amounts by category
	$total_rows = "SELECT COUNT(DISTINCT CONCAT(`type`,`category`,`heading`)) numrows FROM `$table_name`";
	$months = mysqli_fetch_array(mysqli_query($dbc, "SELECT `type`, `heading`, `category`,
		SUM(IF(`invoice_date` LIKE '".$year."-01%', `total`, 0)) `JAN`,
		SUM(IF(`invoice_date` LIKE '".$year."-02%', `total`, 0)) `FEB`,
		SUM(IF(`invoice_date` LIKE '".$year."-03%', `total`, 0)) `MAR`,
		SUM(IF(`invoice_date` LIKE '".$year."-04%', `total`, 0)) `APR`,
		SUM(IF(`invoice_date` LIKE '".$year."-05%', `total`, 0)) `MAY`,
		SUM(IF(`invoice_date` LIKE '".$year."-06%', `total`, 0)) `JUN`,
		SUM(IF(`invoice_date` LIKE '".$year."-07%', `total`, 0)) `JUL`,
		SUM(IF(`invoice_date` LIKE '".$year."-08%', `total`, 0)) `AUG`,
		SUM(IF(`invoice_date` LIKE '".$year."-09%', `total`, 0)) `SEP`,
		SUM(IF(`invoice_date` LIKE '".$year."-10%', `total`, 0)) `OCT`,
		SUM(IF(`invoice_date` LIKE '".$year."-11%', `total`, 0)) `NOV`,
		SUM(IF(`invoice_date` LIKE '".$year."-12%', `total`, 0)) `DEC`
		FROM `$table_name`"));
	$totals = [ $months['JAN'], $months['FEB'], $months['MAR'], $months['APR'], $months['MAY'], $months['JUN'], $months['JUL'], $months['AUG'], $months['SEP'], $months['OCT'], $months['NOV'], $months['DEC'] ];
	$revenue_sql = "SELECT LOWER(`type`) type, `heading`, `category`,
		SUM(IF(`invoice_date` LIKE '".$year."-01%', `total`, 0)) `JAN`,
		SUM(IF(`invoice_date` LIKE '".$year."-02%', `total`, 0)) `FEB`,
		SUM(IF(`invoice_date` LIKE '".$year."-03%', `total`, 0)) `MAR`,
		SUM(IF(`invoice_date` LIKE '".$year."-04%', `total`, 0)) `APR`,
		SUM(IF(`invoice_date` LIKE '".$year."-05%', `total`, 0)) `MAY`,
		SUM(IF(`invoice_date` LIKE '".$year."-06%', `total`, 0)) `JUN`,
		SUM(IF(`invoice_date` LIKE '".$year."-07%', `total`, 0)) `JUL`,
		SUM(IF(`invoice_date` LIKE '".$year."-08%', `total`, 0)) `AUG`,
		SUM(IF(`invoice_date` LIKE '".$year."-09%', `total`, 0)) `SEP`,
		SUM(IF(`invoice_date` LIKE '".$year."-10%', `total`, 0)) `OCT`,
		SUM(IF(`invoice_date` LIKE '".$year."-11%', `total`, 0)) `NOV`,
		SUM(IF(`invoice_date` LIKE '".$year."-12%', `total`, 0)) `DEC`
		FROM `$table_name`
		GROUP BY CONCAT(`type`,`category`,`heading`)
		ORDER BY `type`,`category`,`heading` LIMIT $offset, $rowsPerPage";
	$revenues = mysqli_query($dbc, $revenue_sql);

	echo display_pagination($dbc, $total_rows, $pageNum, $rowsPerPage); ?>
	<table class="table table-bordered">
		<thead>
			<tr class="hidden-xs hidden-sm">
				<th>Revenue Items</th>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					echo "<th style='width: 8em;'>".$dateObj->format('F')."</th>\n";
				} ?>
			</tr>
		</thead>
		<tbody>
			<?php $tile_name = '';
			$category = '';
			$startmonth = ($startyear == $year ? intval(explode('-', $search_start)[1]) - 1 : 0);
			$endmonth = ($endyear == $year ? intval(explode('-', $search_end)[1]) - 1 : 11);
			while($row = mysqli_fetch_array($revenues)) {
				if($tile_name != $row['type']) {
					$tile_name = $row['type'];
					echo "<tr><td colspan='13' style='font-size: 1.1em; font-weight: bold;'>".strtoupper($tile_name)."</td></tr>";
				}
				if($category != $row['category']) {
					$category = $row['category'];
					echo "<tr><td colspan='13' style='padding-left: 1em; font-size: 1.1em; font-weight: bold;'>".$category."</td></tr>";
				}
				echo "<tr>\n<td style='padding-left: 1em;' data-title='Revenue Item'>".$row['heading']."</td>\n";
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$amt = $row[strtoupper($dateObj->format('M'))];
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>&nbsp;&nbsp;".($month < $startmonth || $month > $endmonth ? '-' : '$'.number_format($amt, 2, '.', ','))."</td>\n";
				}
				echo "</tr>\n";
			}
			?>
			<tr style="font-weight: bold;">
				<td>Monthly Total</td>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>$".number_format($totals[$month], 2, '.', ',')."</td>\n";
				} ?>
			</tr>
			<tr style="font-size: 1.5em; font-weight: bold;">
				<td colspan="10" style="border-right: none;">Total Revenue for <?php echo $year == $startyear ? $search_start : $year.'-01-01'; ?> to <?php echo $year == $endyear ? $search_end : $year.'-12-31'; ?></td>
				<td data-title="Total" colspan="3" style="text-align: right; border-left: none;">$<?php echo number_format(array_sum($totals), 2, '.', ','); ?></td>
			</tr>
		</tbody>
	</table>
	<?php echo display_pagination($dbc, $total_rows, $pageNum, $rowsPerPage);
}

mysqli_query($dbc, "DROP TEMPORARY TABLE IF EXISTS `$table_name`"); ?>