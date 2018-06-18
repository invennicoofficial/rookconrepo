<?php include_once('../include.php'); ?>
<?php $position_list = $dbc->query("SELECT `positions`.`position_id`,`positions`.`name`, `company_rate_card`.`cust_price`,`company_rate_card`.`cost` FROM `positions` LEFT JOIN `company_rate_card` ON `positions`.`position_id`=`company_rate_card`.`item_id` AND `company_rate_card`.`tile_name`='Position' AND `company_rate_card`.`deleted`=0 WHERE `positions`.`deleted`=0 GROUP BY `positions`.`position_id`");
echo '<div class="form-group hide-titles-mob">
	<div class="col-sm-8">Position</div>
	<div class="col-sm-2">Rate</div>
	<div class="col-sm-2">Quantity</div>
</div>';
while($position = $position_list->fetch_assoc()) {
	echo '<div class="form-group">
		<div class="col-sm-8"><label class="show-on-mob">Position:</label><input type="hidden" name="position_id[]" value="'.$position['position_id'].'">'.$position['name'].'</div>
		<div class="col-sm-2"><label class="show-on-mob">Rate:</label><input type="hidden" name="cost[]" value="'.$position['cost'].'"><input type="number" readonly class="form-control" name="price[]" value="'.$position['cust_price'].'"></div>
		<div class="col-sm-2"><label class="show-on-mob">Qty:</label><input type="number" class="form-control" name="qty[]" value=""></div>
	</div>';
} ?>