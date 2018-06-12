<?php $pageNum = (!empty($_GET['page']) ? $_GET['page'] : 1);
$inv_cost_field = get_config($dbc,'inventory_cost');
for ($year = $startyear; $year <= $endyear; $year++) {
	$rowsPerPage = 25;
	$offset = ($pageNum - 1) * $rowsPerPage;
	$total_rows = "SELECT COUNT(DISTINCT `inventoryid`) numrows FROM (SELECT i.`inventoryid`, rs.`date_added` FROM `receive_shipment` rs LEFT JOIN `inventory` i ON rs.`inventoryid`=i.`inventoryid`) INVENTORY WHERE `date_added` >= '$search_start' AND `date_added` <= '$search_end'";
	$total_sql = "SELECT SUM(IF(`date_added` LIKE '".$year."-01%', `cost`*`quantity`, 0)) `JAN`,
		SUM(IF(`date_added` LIKE '".$year."-02%', `cost`*`quantity`, 0)) `FEB`,
		SUM(IF(`date_added` LIKE '".$year."-03%', `cost`*`quantity`, 0)) `MAR`,
		SUM(IF(`date_added` LIKE '".$year."-04%', `cost`*`quantity`, 0)) `APR`,
		SUM(IF(`date_added` LIKE '".$year."-05%', `cost`*`quantity`, 0)) `MAY`,
		SUM(IF(`date_added` LIKE '".$year."-06%', `cost`*`quantity`, 0)) `JUN`,
		SUM(IF(`date_added` LIKE '".$year."-07%', `cost`*`quantity`, 0)) `JUL`,
		SUM(IF(`date_added` LIKE '".$year."-08%', `cost`*`quantity`, 0)) `AUG`,
		SUM(IF(`date_added` LIKE '".$year."-09%', `cost`*`quantity`, 0)) `SEP`,
		SUM(IF(`date_added` LIKE '".$year."-10%', `cost`*`quantity`, 0)) `OCT`,
		SUM(IF(`date_added` LIKE '".$year."-11%', `cost`*`quantity`, 0)) `NOV`,
		SUM(IF(`date_added` LIKE '".$year."-12%', `cost`*`quantity`, 0)) `DEC`
		FROM (SELECT CONCAT(IF(i.`category` = '', '', CONCAT(i.`category`,': ')), i.`name`) name, i.`inventoryid`, rs.`quantity`, IF(i.`cost` = 0, `$inv_cost_field` cost,
			rs.`date_added` FROM `receive_shipment` rs LEFT JOIN `inventory` i ON rs.`inventoryid`=i.`inventoryid` WHERE `date_added` >= '$search_start' AND `date_added` <= '$search_end') inventory";
	$total_result = mysqli_fetch_array(mysqli_query($dbc, $total_sql));
	$totals = [ $total_result['JAN'], $total_result['FEB'], $total_result['MAR'], $total_result['APR'], $total_result['MAY'], $total_result['JUN'],
		$total_result['JUL'], $total_result['AUG'], $total_result['SEP'], $total_result['OCT'], $total_result['NOV'], $total_result['DEC'] ];
	$total_taxes = [ $total_result['JANTAX'], $total_result['FEBTAX'], $total_result['MARTAX'], $total_result['APRTAX'], $total_result['MAYTAX'], $total_result['JUNTAX'],
		$total_result['JULTAX'], $total_result['AUGTAX'], $total_result['SEPTAX'], $total_result['OCTTAX'], $total_result['NOVTAX'], $total_result['DECTAX'] ];
	$inventory_sql = "SELECT `name`,
		SUM(IF(`date_added` LIKE '".$year."-01%', `cost`*`quantity`, 0)) `JAN`,
		SUM(IF(`date_added` LIKE '".$year."-02%', `cost`*`quantity`, 0)) `FEB`,
		SUM(IF(`date_added` LIKE '".$year."-03%', `cost`*`quantity`, 0)) `MAR`,
		SUM(IF(`date_added` LIKE '".$year."-04%', `cost`*`quantity`, 0)) `APR`,
		SUM(IF(`date_added` LIKE '".$year."-05%', `cost`*`quantity`, 0)) `MAY`,
		SUM(IF(`date_added` LIKE '".$year."-06%', `cost`*`quantity`, 0)) `JUN`,
		SUM(IF(`date_added` LIKE '".$year."-07%', `cost`*`quantity`, 0)) `JUL`,
		SUM(IF(`date_added` LIKE '".$year."-08%', `cost`*`quantity`, 0)) `AUG`,
		SUM(IF(`date_added` LIKE '".$year."-09%', `cost`*`quantity`, 0)) `SEP`,
		SUM(IF(`date_added` LIKE '".$year."-10%', `cost`*`quantity`, 0)) `OCT`,
		SUM(IF(`date_added` LIKE '".$year."-11%', `cost`*`quantity`, 0)) `NOV`,
		SUM(IF(`date_added` LIKE '".$year."-12%', `cost`*`quantity`, 0)) `DEC`
		FROM (SELECT CONCAT(IF(i.`category` = '', '', CONCAT(i.`category`,': ')), i.`name`) name, i.`inventoryid`, rs.`quantity`, $inv_cost_field cost,
			rs.`date_added` FROM `receive_shipment` rs LEFT JOIN `inventory` i ON rs.`inventoryid`=i.`inventoryid` WHERE `date_added` >= '$search_start' AND `date_added` <= '$search_end') inventory
		GROUP BY `name`
		ORDER BY `name`, `date_added` LIMIT $offset, $rowsPerPage";
	$inventory = mysqli_query($dbc, $inventory_sql);

	echo display_pagination($dbc, $total_rows, $pageNum, $rowsPerPage); ?>
	<table class="table table-bordered">
		<thead>
			<tr class="hidden-xs hidden-sm">
				<th>Inventory</th>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					echo "<th style='width: 8em;'>".$dateObj->format('F')."</th>\n";
				} ?>
			</tr>
		</thead>
		<tbody>
			<?php $category = '';
			while($row = mysqli_fetch_array($inventory)) {
				echo "<tr>\n<td data-title='Inventory'>".$row['name']."</td>\n";
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$amt = $row[strtoupper($dateObj->format('M'))];
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>$".number_format($amt, 2, '.', ',')."</td>\n";
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
				<td colspan="10" style="border-right: none;">Total Inventory Costs for <?php echo $year == $startyear ? $search_start : $year.'-01-01'; ?> to <?php echo $year == $endyear ? $search_end : $year.'-12-31'; ?></td>
				<td data-title="Total" colspan="3" style="text-align: right; border-left: none;">$<?php echo number_format(array_sum($totals), 2, '.', ','); ?></td>
			</tr>
		</tbody>
	</table>
	<?php echo display_pagination($dbc, $total_rows, $pageNum, $rowsPerPage);
}
?>