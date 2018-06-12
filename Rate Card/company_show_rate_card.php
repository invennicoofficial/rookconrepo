<?php if($id = $_GET['id']) {
	$result = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE `rate_card_name` IN (SELECT `rate_card_name` FROM `company_rate_card` where `companyrcid`='$id')");
	$row_count = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);
	$rate_id = $row['companyrcid'];
	$rate_name = $row['rate_card_name'];
	$rate_tile = $row['tile_name'];
	$rate_type = $row['rate_card_types'];
	$rate_heading = $row['heading'];
	$rate_desc = $row['description'];
	$rate_uom = $row['uom'];
	$rate_cost = $row['cost'];
	$rate_cust = $row['cust_price'];
	$rate_profit = $row['profit'];
	$rate_margin = $row['margin'];
	$rate_total = $rate_profit / ($rate_margin / 100);
	$rate_quant = ($rate_total - $rate_profit) / $rate_cost;
	$rate_est = $rate_total / $rate_quant;
} ?>
	<br><br>
	<div class="form-group clearfix completion_date">
		<label for="first_name" class="col-sm-4 control-label text-right">Rate Card Name:</label>
		<div class="col-sm-8">
			<input name="rate_card_name" value="<?php echo $rate_name; ?>" type="text" class="form-control"></p>
		</div>
	</div>

	  <div class="form-group clearfix">
		<label class="col-sm-1 text-center"><h4>Rate Card(s)</h4></label>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-1 text-center">Tile Name</label>
			<label class="col-sm-1 text-center">Type</label>
			<label class="col-sm-1 text-center">Heading</label>
			<label class="col-sm-1 text-center">Description</label>
			<label class="col-sm-1 text-center">
				UOM <a tabindex="0" data-html="true" class="notify_hover" data-placement="bottom" role="button" data-toggle="popover" data-trigger="focus" style="color:black; !important; cursor:pointer;" data-content="Unit of Measure" data-original-title="" title=""><img src="<?= WEBSITE_URL; ?>/img/info.png" width="25px" class="wiggle-me" title="Unit of Measure"></a>
			</label>
			<label class="col-sm-1 text-center">Cost</label>
			<label class="col-sm-1 text-center">Estimate Price</label>
			<label class="col-sm-1 text-center">Customer Price</label>
			<label class="col-sm-1 text-center">Quantity</label>
			<label class="col-sm-1 text-center">Total</label>
			<label class="col-sm-1 text-center">$ Profit</label>
			<label class="col-sm-1 text-center">% Margin</label>
		</div>

		<?php for($i = 0; $i < $row_count; $i++) { ?>
			<div class="<?php echo ($i == 0 ? 'additional_positionprod' : ''); ?>">
			<div class="clearfix"></div>
			<div class="form-group clearfix" id="prodservices_0" width="100%">
				<input type="hidden" name="entry_id" value="<?php echo $rate_id; ?>">

				<div class="col-sm-1">
					<input type='text' name='tile_name' class='form-control' value='<?php echo $rate_tile; ?>'>
				</div>

				<div class="col-sm-1">
					<input type='text' name='rate_card_type' class='form-control' value='<?php echo $rate_type; ?>'>
				</div>

				<div class="col-sm-1">
					<input name="heading" type="text" class="form-control prodprice1" value="<?php echo $rate_heading; ?>" />
				</div>
				<div class="col-sm-1">
					<input name="description" type="text" class="form-control prodprice1" value="<?php echo $rate_desc; ?>" />
				</div>
				<div class="col-sm-1">
					<input name="uom" type="text" class="form-control prodprice1" value="<?php echo $rate_uom; ?>" />
				</div>
				<div class="col-sm-1">
					<input id="cost_<?php echo $i; ?>" name="cost" type="text" class="form-control prodprice1" value="<?php echo $rate_cost; ?>" />
				</div>
				<div class="col-sm-1">
					<input id="estimateprice_<?php echo $i; ?>" name="estimateprice" type="text" class="form-control prodprice1" value="<?php echo round($rate_est); ?>" />
				</div>
				<div class="col-sm-1">
					<input id="custprice_<?php echo $i; ?>" name="cust_price" type="text" class="form-control prodprice1" value="<?php echo round($rate_cust); ?>" />
				</div>
				<div class="col-sm-1">
					<input id="quantity_<?php echo $i; ?>" name="quantity" type="text" class="form-control prodprice1" value="<?php echo round($rate_quant); ?>" />
				</div>
				<div class="col-sm-1">
					<input id="total_<?php echo $i; ?>" name="total" type="text" class="form-control prodprice1" value="<?php echo round($rate_total,2); ?>" />
				</div>
				<div class="col-sm-1">
					<input id="profit_<?php echo $i; ?>" name="profit" type="text" class="form-control prodprice1" value="<?php echo round($rate_profit,2); ?>" />
				</div>
				<div class="col-sm-1">
					<input id="margin_<?php echo $i; ?>" name="margin" type="text" class="form-control prodprice1" value="<?php echo round($rate_margin,2); ?>" />
				</div>
			</div>
			</div>
			<?php
			if($rate_name != '') {
				$row = mysqli_fetch_array($result);
				$rate_id = $row['companyrcid'];
				$rate_name = $row['rate_card_name'];
				$rate_tile = $row['tile_name'];
				$rate_type = $row['rate_card_types'];
				$rate_heading = $row['heading'];
				$rate_desc = $row['description'];
				$rate_uom = $row['uom'];
				$rate_cost = $row['cost'];
				$rate_cust = $row['cust_price'];
				$rate_profit = $row['profit'];
				$rate_margin = $row['margin'];
				$rate_total = $rate_profit / ($rate_margin / 100);
				$rate_quant = ($rate_total - $rate_profit) / $rate_cost;
				$rate_est = $rate_total / $rate_quant;
			}
		} ?>

</div>
<div class="clearfix"></div>