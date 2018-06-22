<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
$estimateid = filter_var($_GET['financials'],FILTER_SANITIZE_STRING);
$estimate = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
$scope = mysqli_query($dbc, "SELECT * FROM `estimate_scope` WHERE `estimateid`='$estimateid' AND `deleted`=0 ORDER BY `sort_order`");
$total_cost = $total_price = 0; ?>
<script>
$(document).ready(function(){
    //$('.financials').height($('#estimates_main').height() - $('.tile-header').height() - $('.tile-navbar').height() );
    /* var financialsHeight = $('#estimates_main').height() - $('.tile-header').height() - 25;
    $('.financials').each(function() { $(this).attr('style',$(this).attr('style')+';height:'+financialsHeight+'px !important;'); }) */
    $(window).resize(function() {
        available = Math.floor($(window).innerHeight() - $('.main-screen').offset().top - $('footer:visible').outerHeight() - 80);
		if(available > 300) {
            $('.financials').each(function() { $(this).attr('style',$(this).attr('style')+';height:'+available+'px !important;'); })
		}
	}).resize();
});
</script>
<div id="no-more-tables" class="col-sm-12 fit-to-screen-full financials">
	<div class='by-item'>
		<h4>Profit & Loss by Item for <a href="?view=<?= $estimate['estimateid'] ?>"><?= $estimate['estimate_name'] != '' ? $estimate['estimate_name'] : ESTIMATE_TILE.' #'.$estimateid ?><img class="inline-img" src="../img/icons/ROOK-edit-icon.png"></a>
			<button class="btn brand-btn pull-right" onclick="$('.by-item,.by-heading').toggle(); return false;">View by Heading</button></h4>
		<table class="table table-bordered">
			<tr class="hidden-xs hidden-sm">
                <th>Scope Name</th>
				<th>Heading</th>
				<th>Type</th>
				<th>U of M</th>
				<th>Quantity</th>
				<th>Cost</th>
				<th>% Margin</th>
				<th>$ Profit</th>
				<th><?= ESTIMATE_TILE ?> Price</th>
				<th>Total</th>
			</tr>
			<?php while($scope_line = mysqli_fetch_assoc($scope)) {
				$scope_description = $scope_line['description'];
				if($scope_line['src_table'] == 'equipment' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT CONCAT(IFNULL(`category`,''),': ',IFNULL(`make`,''),' ',IFNULL(`model`,''),' ',IFNULL(`label`,''),' ',IFNULL(`unit_number`,'')) label FROM `equipment` WHERE `equipmentid`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] == 'inventory' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT CONCAT(IFNULL(`category`,''),': ',IFNULL(`product_name`,''),' ',IFNULL(`name`,''),' ',IFNULL(`part_no`,'')) label FROM `inventory` WHERE `inventoryid`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] == 'labour' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT CONCAT(IFNULL(`labour_type`,''),' ',IFNULL(`category`,''),' ',IFNULL(`heading`,''),' ',IFNULL(`name`,'')) label FROM `labour` WHERE `labourid`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] == 'material' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT CONCAT(IFNULL(`category`,''),' ',IFNULL(`sub_category`,''),' ',IFNULL(`name`,'')) label FROM `material` WHERE `materialid`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] == 'position' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `name` label FROM `positions` WHERE `position_id`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] == 'products' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT CONCAT(IFNULL(`category`,''),' ',IFNULL(`heading`,'')) label FROM `products` WHERE `productid`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] == 'services' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT CONCAT(IFNULL(`category`,''),' ',IFNULL(`heading`,'')) label FROM `services` WHERE `serviceid`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] == 'vpl' && $scope_line['src_id'] > 0) {
					$scope_description = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT CONCAT(IFNULL(`category`,''),' ',IFNULL(`product_name`,'')) label FROM `vendor_price_list` WHERE `inventoryid`='{$scope_line['src_id']}'"))['label'];
				} else if($scope_line['src_table'] != 'miscellaneous' && $scope_line['src_id'] > 0) {
					$scope_description = get_contact($dbc, $scope_line['src_id']);
				} ?>
				<tr>
                    <td data-title="Heading"><?= $scope_line['scope_name'] ?></td>
					<td data-title="Heading"><?= $scope_line['heading'] ?></td>
					<td data-title="Description"><?= $scope_description ?></td>
					<td data-title="U of M"><?= $scope_line['uom'] ?></td>
					<td data-title="Quantity" align="right"><?= number_format($scope_line['qty'],0) ?></td>
					<td data-title="Unit Cost" align="right">$<?= number_format($scope_line['cost'], 2) ?></td>
					<td data-title="Margin" align="right"><?= number_format($scope_line['margin'], 2) ?>%</td>
					<td data-title="Profit" align="right">$<?= number_format($scope_line['profit'], 2) ?></td>
					<td data-title="Estimate Price" align="right">$<?= number_format($scope_line['price'], 2) ?></td>
					<td data-title="Total">$<?= number_format($scope_line['retail'], 2) ?></td>
				</tr>
				<?php $total_cost += $scope_line['cost'] * $scope_line['qty'];
				$total_price += $scope_line['retail'];
			} ?>
			<tr style="font-weight:bold;">
				<td data-title="" colspan="5">Total <?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?> Price</td>
				<td data-title="Total Cost" align="right">$<?= number_format($total_cost,2) ?></td>
				<td data-title="Average Margin" align="right"><?= number_format(($total_cost > 0 ? ($total_price - $total_cost) / $total_cost * 100 : 0),2) ?>%</td>
				<td data-title="Total Profit" align="right">$<?= number_format($total_price - $total_cost,2) ?></td>
				<td data-title=""></td>
				<td data-title="Total" align="right">$<?= number_format($total_price,2) ?></td>
			</tr>
		</table>
	</div>
	<div class='by-heading' style="display:none;">
		<h4>Profit & Loss by Heading for <a href="?view=<?= $estimate['estimateid'] ?>"><?= $estimate['estimate_name'] != '' ? $estimate['estimate_name'] : ESTIMATE_TILE.' #'.$estimateid ?><img class="inline-img" src="../img/icons/ROOK-edit-icon.png"></a>
			<button class="btn brand-btn pull-right" onclick="$('.by-item,.by-heading').toggle(); return false;">View by Item</button></h4>
		<table class="table table-bordered">
			<tr class="hidden-xs hidden-sm">
				<th>Heading</th>
				<th>Cost</th>
				<th>% Margin</th>
				<th>$ Profit</th>
				<th>Total</th>
			</tr>
			<?php $scope_headings = mysqli_query($dbc, "SELECT `heading`, SUM(`cost` * `qty`) total_cost, SUM(`retail`) total_price FROM `estimate_scope` WHERE `estimateid`='$estimateid' AND `deleted`=0 GROUP BY `heading` ORDER BY `sort_order`");
			while($scope_heading = mysqli_fetch_assoc($scope_headings)) { ?>
				<tr>
					<td data-title="Heading"><?= $scope_heading['heading'] ?></td>
					<td data-title="Heading Cost">$<?= number_format($scope_heading['total_cost'],2) ?></td>
					<td data-title="Margin"><?= number_format(($scope_heading['total_cost'] > 0 ? ($scope_heading['total_price'] - $scope_heading['total_cost']) / $scope_heading['total_cost'] * 100 : 0),2) ?>%</td>
					<td data-title="Profit">$<?= number_format($scope_heading['total_price'] - $scope_heading['total_cost'],2) ?></td>
					<td data-title="Total">$<?= $scope_heading['total_price'] ?></td>
				</tr>
				<?php $total_cost += $scope_heading['total_cost'];
				$total_price += $scope_heading['total_price'];
			} ?>
			<tr>
				<td data-title="">Total <?php if (PROJECT_TILE=='Projects') { echo "Project"; } else { echo PROJECT_TILE; } ?> Price</td>
				<td data-title="Total Cost">$<?= number_format($total_cost,2) ?></td>
				<td data-title="Average Margin"><?= number_format(($total_cost > 0 ? ($total_price - $total_cost) / $total_cost * 100 : 0),2) ?>%</td>
				<td data-title="Total Profit">$<?= number_format($total_price - $total_cost,2) ?></td>
				<td data-title="Total">$<?= number_format($total_price,2) ?></td>
			</tr>
		</table>
	</div>
</div>
<div class="clearfix"></div>