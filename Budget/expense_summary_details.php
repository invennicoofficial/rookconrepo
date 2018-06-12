<?php 
if(!empty($_POST['save'])) {
	foreach($_POST['id'] as $row => $id) {
		$daily = $_POST['daily'][$row];
		$weekly = $_POST['weekly'][$row];
		$monthly = $_POST['monthly'][$row];
		$q1 = $_POST['q1'][$row];
		$q2 = $_POST['q2'][$row];
		$q3 = $_POST['q3'][$row];
		$q4 = $_POST['q4'][$row];
		$annual = $_POST['annual'][$row];
		$sql_update = "UPDATE `budget_category` SET `daily`='$daily', `weekly`='$weekly', `monthly`='$monthly', `q1`='$q1', `q2`='$q2', `q3`='$q3', `q4`='$q4', `annual`='$annual' WHERE `budget_categoryid`='$id'";
		$result = mysqli_query($dbc, $sql_update);
	}
}
?>
<form method="POST" action="">
	<button class="btn brand-btn pull-right" type="submit" name="save" value="<?php echo $budgetid; ?>">Save</button>
	<?php $budgetid = $_GET['budgetid']; ?>
	<?php $select_query = "SELECT budget_name from budget where budgetid = $budgetid";
		  $result = mysqli_fetch_assoc(mysqli_query($dbc, $select_query)); ?>
	<center><h1> AFE#<?php echo $budgetid . ' - ' . $result['budget_name']; ?></h1></center>
	<h1>Expenses</h1>
	<!--<script>
	var tableOffset = $("#table-1").offset().top;
	var $header = $("#table-1 > thead").clone();
	var $fixedHeader = $("#header-fixed").append($header);

	$(window).bind("scroll", function() {
		var offset = $(this).scrollTop();

		if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
			$fixedHeader.show();
		}
		else if (offset < tableOffset) {
			$fixedHeader.hide();
		}
	});
	</script>
	<style>
	#header-fixed {
		position: fixed;
		top: 0px; display:none;
		background-color:white;
	}
	</style>-->
	<?php $select_query = "SELECT * from budget_category where budgetid = $budgetid order by category";
	  $result = mysqli_query($dbc, $select_query); ?>
	<!--<table id="header-fixed"></table>-->
	<table id="table-1" class="table table-bordered">
		<thead>
			<tr class="hidden-xs hidden-sm">
				<th>&nbsp;</th>
				<th colspan="8"><center>Estimated</center></th>
				<th>Actual</th>
			</tr>
			
			<tr class="hidden-xs hidden-sm">
				<th>&nbsp;</th>
				<th>Daily</th>
				<th>Weekly</th>
				<th>Monthly</th>
				<th>Q1</th>
				<th>Q2</th>
				<th>Q3</th>
				<th>Q4</th>
				<th>Annual</th>
			</tr>
		</thead>
		<tbody>
			<?php $old_category = ''; $new_category = ''; $count = 0; ?>
			<?php 
				$dailyTotal = 0;
				$weeklyTotal = 0;
				$monthlyTotal = 0;
				$q1Total = 0;
				$q2Total = 0;;
				$q3Total = 0;
				$q4Total = 0;
				$annualTotal = 0;
				$total = 0;
				$actual = 0;
				$actualTotal = 0;
				$actualFinalTotal = 0;
			?>
			<?php  while($row = mysqli_fetch_array( $result )) { ?>
				<?php 
					$old_category = $new_category;
					$new_category = $row['category']; 
				?>
				<?php
					$budget_categoryid = $row['budget_categoryid'];
					$actual_query = "select sum(actual_amount) as actual from budget_expense where budget_categoryid = $budget_categoryid"; 
					$actual_result = mysqli_fetch_assoc(mysqli_query($dbc, $actual_query));
					$actual_amount = $actual_result['actual'];
				?>
				<?php if(trim($old_category) != trim($new_category)): ?>
					<?php if($count != 0): ?>
						<tr>
							<td><b><?php echo "Total"; ?></b></td>
							<td> $<?php echo $dailyTotal; ?></td>
							<td> $<?php echo $weeklyTotal; ?></td>
							<td> $<?php echo $monthlyTotal; ?></td>
							<td> $<?php echo $q1Total; ?></td>
							<td> $<?php echo $q2Total; ?></td>
							<td> $<?php echo $q3Total; ?></td>
							<td> $<?php echo $q4Total; ?></td>
							<td> $<?php echo $annualTotal; ?></td>
							<td><?php if($actualTotal) echo '$' . $actualTotal; ?></td>
						</tr>
						<tr><td colspan="10">&nbsp;</td></tr>
						<tr class="hidden-xs hidden-sm">
							<th>&nbsp;</th>
							<th>Daily</th>
							<th>Weekly</th>
							<th>Monthly</th>
							<th>Q1</th>
							<th>Q2</th>
							<th>Q3</th>
							<th>Q4</th>
							<th>Annual</th>
						</tr>
						<?php 
							$total += $dailyTotal;
							$total += $weeklyTotal;
							$total += $monthlyTotal;
							$total += $q1Total;
							$total += $q2Total;
							$total += $q3Total;
							$total += $q4Total;
							$total += $annualTotal;
							$actualFinalTotal += $actualTotal;
							
							$dailyTotal = 0;
							$weeklyTotal = 0;
							$monthlyTotal = 0;
							$q1Total = 0;
							$q2Total = 0;;
							$q3Total = 0;
							$q4Total = 0;
							$annualTotal = 0;
							$actualTotal = 0;
						?>
					<?php endif; ?>
					<tr>
						<td colspan="10"><h4><b><?php echo 'EC ' . $row['EC'] . ': ' . $row['category']; ?><b></h4></td>
					</tr>
				<?php endif; ?>
				<tr>
					<td><?php echo 'GL ' . $row['GL'] . ': ' .  $row['expense']; ?></td><input type="hidden" name="id[]" value="<?php echo $row['budget_categoryid']; ?>">
					<td>$<input type="text" name="daily[]" class="form-control" value="<?php echo $row['daily']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td>$<input type="text" name="weekly[]" class="form-control" value="<?php echo $row['weekly']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td>$<input type="text" name="monthly[]" class="form-control" value="<?php echo $row['monthly']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td>$<input type="text" name="q1[]" class="form-control" value="<?php echo $row['q1']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td>$<input type="text" name="q2[]" class="form-control" value="<?php echo $row['q2']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td>$<input type="text" name="q3[]" class="form-control" value="<?php echo $row['q3']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td>$<input type="text" name="q4[]" class="form-control" value="<?php echo $row['q4']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td>$<input type="text" name="annual[]" class="form-control" value="<?php echo $row['annual']; ?>" style="width:calc(100% - 1em); display:inline-block;"></td>
					<td><?php if($actual_amount) echo '$' . $actual_amount; ?></td>
				</tr>
				<?php 
					$dailyTotal += $row['daily'];
					$weeklyTotal += $row['weekly'];
					$monthlyTotal += $row['monthly'];
					$q1Total += $row['q1'];
					$q2Total += $row['q2'];
					$q3Total += $row['q3'];
					$q4Total += $row['q4'];
					$annualTotal += $row['annual'];
					$actualTotal += $actual_amount;
					

				?>
			<?php $count++; } ?>
			<?php 
				$total += $dailyTotal;
				$total += $weeklyTotal;
				$total += $monthlyTotal;
				$total += $q1Total;
				$total += $q2Total;
				$total += $q3Total;
				$total += $q4Total;
				$total += $annualTotal;
				$actualFinalTotal += $actualTotal;
			?>
			<tr>
				<td><b><?php echo "Total"; ?></b></td>
				<td> $<?php echo $dailyTotal; ?></td>
				<td> $<?php echo $weeklyTotal; ?></td>
				<td> $<?php echo $monthlyTotal; ?></td>
				<td> $<?php echo $q1Total; ?></td>
				<td> $<?php echo $q2Total; ?></td>
				<td> $<?php echo $q3Total; ?></td>
				<td> $<?php echo $q4Total; ?></td>
				<td> $<?php echo $annualTotal; ?></td>
				<td><?php if($actualTotal) echo '$' . $actualTotal; ?></td>
			</tr>
			<tr><td colspan="10">&nbsp;</td></tr>
			
			<tr>
				<td><b>Total Expense</b></td>
				<td colspan="8" style="text-align:right"><b>$<?php echo $total; ?></b></td>
				<td><b>$<?php echo $actualFinalTotal; ?></b></td>
			</tr>
		</tbody>
	</table>
	<button class="btn brand-btn pull-right" type="submit" name="save" value="<?php echo $budgetid; ?>">Save</button>
</form>