<?php $pageNum = (!empty($_GET['page']) ? $_GET['page'] : 1);
for ($year = $startyear; $year <= $endyear; $year++) {
	$rowsPerPage = 25;
	$offset = ($pageNum - 1) * $rowsPerPage;
	$total_rows = "SELECT COUNT(*) numrows FROM (SELECT staff heading FROM expense WHERE ex_date >= '$search_start' AND ex_date <= '$search_end' GROUP BY staff UNION
		SELECT budget_categoryid FROM budget_expense WHERE expense_date >= '$search_start' AND expense_date <= '$search_end' GROUP BY budget_categoryid) expenses";
	$total_sql = "SELECT SUM(IF(`expense_date` LIKE '".$year."-01%', `total`, 0)) `JAN`,
		SUM(IF(`expense_date` LIKE '".$year."-02%', `total`, 0)) `FEB`,
		SUM(IF(`expense_date` LIKE '".$year."-03%', `total`, 0)) `MAR`,
		SUM(IF(`expense_date` LIKE '".$year."-04%', `total`, 0)) `APR`,
		SUM(IF(`expense_date` LIKE '".$year."-05%', `total`, 0)) `MAY`,
		SUM(IF(`expense_date` LIKE '".$year."-06%', `total`, 0)) `JUN`,
		SUM(IF(`expense_date` LIKE '".$year."-07%', `total`, 0)) `JUL`,
		SUM(IF(`expense_date` LIKE '".$year."-08%', `total`, 0)) `AUG`,
		SUM(IF(`expense_date` LIKE '".$year."-09%', `total`, 0)) `SEP`,
		SUM(IF(`expense_date` LIKE '".$year."-10%', `total`, 0)) `OCT`,
		SUM(IF(`expense_date` LIKE '".$year."-11%', `total`, 0)) `NOV`,
		SUM(IF(`expense_date` LIKE '".$year."-12%', `total`, 0)) `DEC`,
		SUM(IF(`expense_date` LIKE '".$year."-01%', `tax`, 0)) `JANTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-02%', `tax`, 0)) `FEBTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-03%', `tax`, 0)) `MARTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-04%', `tax`, 0)) `APRTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-05%', `tax`, 0)) `MAYTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-06%', `tax`, 0)) `JUNTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-07%', `tax`, 0)) `JULTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-08%', `tax`, 0)) `AUGTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-09%', `tax`, 0)) `SEPTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-10%', `tax`, 0)) `OCTTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-11%', `tax`, 0)) `NOVTAX`,
		SUM(IF(`expense_date` LIKE '".$year."-12%', `tax`, 0)) `DECTAX`
		FROM (SELECT 'EXPENSE' `source`, 'Staff Expense' `category`, staff `heading`, ex_date expense_date, gst tax, amount total FROM expense UNION
			SELECT 'BUDGET' `source`, category `category`, expense `heading`, expense_date, tax, actual_amount total FROM budget_expense LEFT JOIN budget_category ON budget_expense.budget_categoryid=budget_category.budget_categoryid) expenses
		WHERE `expense_date` >= '$search_start' AND `expense_date` <= '$search_end'";
	$total_result = mysqli_fetch_array(mysqli_query($dbc, $total_sql));
	$totals = [ $total_result['JAN'], $total_result['FEB'], $total_result['MAR'], $total_result['APR'], $total_result['MAY'], $total_result['JUN'],
		$total_result['JUL'], $total_result['AUG'], $total_result['SEP'], $total_result['OCT'], $total_result['NOV'], $total_result['DEC'] ];
	$total_taxes = [ $total_result['JANTAX'], $total_result['FEBTAX'], $total_result['MARTAX'], $total_result['APRTAX'], $total_result['MAYTAX'], $total_result['JUNTAX'],
		$total_result['JULTAX'], $total_result['AUGTAX'], $total_result['SEPTAX'], $total_result['OCTTAX'], $total_result['NOVTAX'], $total_result['DECTAX'] ];
	$expenses_sql = "SELECT source, heading, category, tab,
		SUM(IF(`expense_date` LIKE '".$year."-01%', `total`-`tax`, 0)) `JAN`,
		SUM(IF(`expense_date` LIKE '".$year."-02%', `total`-`tax`, 0)) `FEB`,
		SUM(IF(`expense_date` LIKE '".$year."-03%', `total`-`tax`, 0)) `MAR`,
		SUM(IF(`expense_date` LIKE '".$year."-04%', `total`-`tax`, 0)) `APR`,
		SUM(IF(`expense_date` LIKE '".$year."-05%', `total`-`tax`, 0)) `MAY`,
		SUM(IF(`expense_date` LIKE '".$year."-06%', `total`-`tax`, 0)) `JUN`,
		SUM(IF(`expense_date` LIKE '".$year."-07%', `total`-`tax`, 0)) `JUL`,
		SUM(IF(`expense_date` LIKE '".$year."-08%', `total`-`tax`, 0)) `AUG`,
		SUM(IF(`expense_date` LIKE '".$year."-09%', `total`-`tax`, 0)) `SEP`,
		SUM(IF(`expense_date` LIKE '".$year."-10%', `total`-`tax`, 0)) `OCT`,
		SUM(IF(`expense_date` LIKE '".$year."-11%', `total`-`tax`, 0)) `NOV`,
		SUM(IF(`expense_date` LIKE '".$year."-12%', `total`-`tax`, 0)) `DEC`
		FROM (SELECT 'EXPENSE' source, `expense_for` tab, CONCAT(IFNULL(ec,''), ': ', expense.category) category, concat(IFNULL(gl,''), ': ', expense.title) heading, ex_date expense_date, gst tax, amount total FROM expense LEFT JOIN expense_categories ON expense.category=expense_categories.category AND expense.title=expense_categories.heading UNION
			SELECT `budget`.`budget_name` `source`, `budget`.`budgetid` tab, CONCAT(IFNULL(ec,''), ': ', category) category, CONCAT(IFNULL(gl,''), ': ', expense) heading, expense_date, tax, actual_amount total FROM budget_expense LEFT JOIN budget_category ON budget_expense.budget_categoryid=budget_category.budget_categoryid LEFT JOIN `budget` ON `budget_category`.`budgetid`=`budget`.`budgetid`) expenses
		WHERE `expense_date` LIKE '$search_start' AND `expense_date` <= '$search_end'
		GROUP BY `source`, `tab`, `category`, `heading`
		ORDER BY `source`, `tab`, `category`, `heading`, `expense_date` LIMIT $offset, $rowsPerPage";
	$expenses = mysqli_query($dbc, $expenses_sql);

	echo display_pagination($dbc, $total_rows, $pageNum, $rowsPerPage); ?>
	<table class="table table-bordered">
		<thead>
			<tr class="hidden-xs hidden-sm">
				<th>Expense</th>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj   = DateTime::createFromFormat('!m', $month+1);
					echo "<th style='width: 8em;'>".$dateObj->format('F')."</th>\n";
				} ?>
			</tr>
		</thead>
		<tbody>
			<?php $category = $source = '';
			while($row = mysqli_fetch_array($expenses)) {
				if($source != $row['source'].$row['tab']) {
					$source = $row['source'].$row['tab'];
					if($row['source'] != 'EXPENSE') {
						echo "<tr><td colspan='13' style='font-size: 1.1em; font-weight: bold;'><a href='".WEBSITE_URL."/Budget/budget_expense.php?budgetid=".$row['tab']."&from_url=".urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI'])."'>".$row['source']."</a></td></tr>";
					}
				}
				if($category != $row['category']) {
					$category = $row['category'];
					echo "<tr><td colspan='13' style='font-size: 1.1em; font-weight: bold;'>".$category."</td></tr>";
				}
				echo "<tr>\n<td data-title='Expense'>".($row['source'] == 'STAFF'?get_contact($dbc,$row['heading']):$row['heading'])."</td>\n";
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					$amt = $row[strtoupper($dateObj->format('M'))];
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>$".number_format($amt, 2, '.', ',')."</td>\n";
				}
				echo "</tr>\n";
			}
			?>
			<tr>
				<td>Sales Tax Total</td>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					echo "<td data-title='".$dateObj->format('F')." Sales Tax' style='text-align: right;'>$".number_format($total_taxes[$month], 2, '.', ',')."</td>\n";
				} ?>
			</tr>
			<tr style="font-weight: bold;">
				<td>Monthly Total</td>
				<?php
				for($month = 0; $month < 12; $month++) {
					$dateObj = DateTime::createFromFormat('!m', $month+1);
					echo "<td data-title='".$dateObj->format('F')."' style='text-align: right;'>$".number_format($totals[$month], 2, '.', ',')."</td>\n";
				} ?>
			</tr>
			<tr style="font-size: 1.5em; font-weight: bold;">
				<td colspan="10" style="border-right: none;">Total Expenses for <?php echo $year == $startyear ? $search_start : $year.'-01-01'; ?> to <?php echo $year == $endyear ? $search_end : $year.'-12-31'; ?></td>
				<td data-title="Total" colspan="3" style="text-align: right; border-left: none;">$<?php echo number_format(array_sum($totals), 2, '.', ','); ?></td>
			</tr>
		</tbody>
	</table>
	<?php echo display_pagination($dbc, $total_rows, $pageNum, $rowsPerPage);
}
?>