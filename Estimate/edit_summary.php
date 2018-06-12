<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
if(!isset($estimate)) {
	$estimateid = filter_var($_GET['edit'],FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
	$rates = [];
	$query = mysqli_query($dbc, "SELECT `heading` FROM `rate_card` WHERE `estimateid`='$estimateid' GROUP BY `rate_card`");
	if(mysqli_num_rows($query) > 0) {
		while($row = mysqli_fetch_array($query)) {
			$rates[bin2hex($row[0])] = explode(':',$row[0]);
		}
	} else {
		$rates[''] = '';
	}
	$current_rate = $_GET['rate'];
	$headings = [];
	$query = mysqli_query($dbc, "SELECT `heading` FROM `estimate_scope` WHERE `estimateid`='$estimateid' AND `rate_card`='".implode(':',$rates[$current_rate])."' AND `deleted`=0 GROUP BY `heading` ORDER BY MIN(`sort_order`)");
	while($row = mysqli_fetch_array($query)) {
		$headings[preg_replace('/[^a-z]*/','',strtolower($row[0]))] = $row[0];
	}
}
$summary = mysqli_fetch_array(mysqli_query($dbc, "SELECT SUM(`cost` * `qty`) total_cost, SUM(`retail`) total_price FROM `estimate_scope` WHERE `estimateid`='$estimateid' AND `rate_card`='".implode(':',$rates[$current_rate])."'"));
$profit = $summary['total_price'] - $summary['total_cost'];
$margin = $profit / $summary['total_cost'] * 100; ?>
<script>
var total_cost = '0<?= $summary['total_cost'] ?>';
var total_price = '0<?= $summary['total_price'] - ($summary['discount_type'] == '%' ? ($summary['discount'] * $summary['total_price'] / 100) : $summary['discount']) ?>';
</script>
<div class="form-horizontal col-sm-12" data-tab-name="summary">
	<h3>Summary</h3>
	<div class="form-group">
		<label class="col-sm-4">Discount:</label>
		<div class="col-sm-4" id="discount_num"><input type="number" step="any" name="discount" value="<?= $estimate['discount'] ?>" data-table="estimate" data-id="<?= $estimate['estimateid'] ?>" data-id-field="estimateid" class="form-control discount" placeholder="Discount"></div>
		<div class="col-sm-4" id="discount_type">
			<select name="discount_type" class="chosen-select-deselect discount_type" data-table="estimate" data-id="<?= $estimate['estimateid'] ?>" data-id-field="estimateid" data-placeholder="Discount Type"><option />
				<option <?= $estimate['discount_type'] == '%' ? '' : 'selected' ?> value="$">$ Discount</option>
				<option <?= $estimate['discount_type'] == '%' ? 'selected' : '' ?> value="%">% Discount</option>
			</select></div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Total Cost:</label>
		<div class="col-sm-8" id="total_cost">$<?= number_format($summary['total_cost'],2) ?></div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Total $ Profit:</label>
		<div class="col-sm-8" id="total_profit">$<?= number_format($profit,2) ?></div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Total % Margin:</label>
		<div class="col-sm-8" id="total_margin"><?= number_format($margin,2) ?>%</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Total Price:</label>
		<div class="col-sm-8" id="total_price">$<?= number_format($summary['total_price'],2) ?></div>
	</div>
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'edit_summary.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>