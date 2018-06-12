<script>
$(document).ready(function() {
	$('[name$=_summary]').change(calc_summary_total);
	calc_summary_total();
});

function calc_summary_total() {
	var total_cost = 0;
	var total_price = 0;
	var total_usd = 0;
	$('[name$=_summary_cost]').each(function() {
		total_cost += parseFloat(0+$(this).val().replace(/[^0-9.]/g,''));
	});
	$('[name$=_summary]').each(function() {
		$('[name='+$(this).attr('name')+'_cad]').val($(this).val());
		total_price += parseFloat(0+$(this).val().replace(/[^0-9.]/g,''));
	});
	$('[name$=_summary_usd]').each(function() {
		if($(this).val() == '') {
			$(this).val('$0.00');
		}
		total_usd += parseFloat(0+$(this).val().replace(/[^0-9.]/g,''));
	});
	$('[name^=summary_total]').val('$'+round2Fixed(total_price));
	$('[name=summary_total_usd]').val('$'+round2Fixed(total_usd));
	$('[name=summary_total_cost]').val('$'+round2Fixed(total_cost));
	$('[name=summary_total_profit]').val('$'+round2Fixed(total_price - total_cost));
	if(total_cost != total_price) {
		$('[name=summary_total_margin]').val(round2Fixed((total_price - total_cost) / total_price * 100)+'%');
	}
	else {
		$('[name=summary_total_margin]').val('N/A');
	}
	$('.column-total').hide();
	if(total_usd > 0) {
		$('.column-total[data-title!="Total"]').show();
	}
	else {
		$('.column-total[data-title="Total"]').show();
	}
}
</script>
<div class="no-more-tables">
	<table class="table table-bordered" width="100%">
		<thead>
			<tr>
				<th>Type</th>
				<th>Total Cost</th>
				<th>Total Profit</th>
				<th>Total % Margin</th>
				<th class='column-total' data-title="Total CAD">Total CAD</th>
				<th class='column-total' data-title="Total USD">Total USD</th>
				<th class='column-total' data-title="Total">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php if (strpos($value_config, ','."Package".',') !== FALSE) {
				$total_price += 0;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Package:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="package_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="package_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="package_summary" value="" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Promotion".',') !== FALSE) {
				$total_price += 0;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Promotion:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="promotion_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="promotion_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="promotion_summary" value="" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Custom".',') !== FALSE) {
				$total_price += 0;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Custom:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="custom_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="custom_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="custom_summary" value="" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Material".',') !== FALSE) {
				$total_price += 0;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Material:</td>
					<td data-title="Cost"><input type="text" name="material_summary_cost" class="form-control"></td>
					<td data-title="Profit"><input type="text" name="material_summary_profit" class="form-control"></td>
					<td data-title="Margin"><input type="text" name="material_summary_margin" class="form-control"></td>
					<td class='column-total' data-title="Total CAD"><input name="material_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="material_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="material_summary" value="" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Labour".',') !== FALSE) {
				$total_price += $final_total_labour;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Labour:</td>
					<td data-title="Cost"><input type="text" name="labour_summary_cost" class="form-control"></td>
					<td data-title="Profit"><input type="text" name="labour_summary_profit" class="form-control"></td>
					<td data-title="Margin"><input type="text" name="labour_summary_margin" class="form-control"></td>
					<td class='column-total' data-title="Total CAD"><input name="labour_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="labour_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="labour_summary" value="$<?php echo $final_total_labour;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Services".',') !== FALSE) {
				$total_price += $final_total_services;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Services:</td>
					<td data-title="Cost"><input type="text" name="services_summary_cost" class="form-control"></td>
					<td data-title="Profit"><input type="text" name="services_summary_profit" class="form-control"></td>
					<td data-title="Margin"><input type="text" name="services_summary_margin" class="form-control"></td>
					<td class='column-total' data-title="Total CAD"><input name="services_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="services_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="services_summary" value="$<?php echo $final_total_services;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Products".',') !== FALSE) {
				$total_price += $final_total_products;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Products:</td>
					<td data-title="Cost"><input type="text" name="products_summary_cost" class="form-control"></td>
					<td data-title="Profit"><input type="text" name="products_summary_profit" class="form-control"></td>
					<td data-title="Margin"><input type="text" name="products_summary_margin" class="form-control"></td>
					<td class='column-total' data-title="Total CAD"><input name="products_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="products_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="products_summary" value="$<?php echo $final_total_products;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."SRED".',') !== FALSE) {
				$total_price += $final_total_sred;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">SR&ED:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="sred_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="sred_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="sred_summary" value="$<?php echo $final_total_sred;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Staff".',') !== FALSE) {
				$total_price += $final_total_staff;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Staff:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="staff_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="staff_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="staff_summary" value="$<?php echo $final_total_staff;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Contractor".',') !== FALSE) {
				$total_price += $final_total_contractor;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Contractor:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="contractor_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="contractor_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="contractor_summary" value="$<?php echo $final_total_contractor;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Clients".',') !== FALSE) {
				$total_price += $final_total_clients;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Clients:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="clients_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="clients_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="clients_summary" value="$<?php echo $final_total_clients;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Vendor Pricelist".',') !== FALSE) {
				$total_price += $final_total_vendor;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Vendor Pricelist:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="vendor_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="vendor_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="vendor_summary" value="$<?php echo $final_total_vendor;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Customer".',') !== FALSE) {
				$total_price += $final_total_customer;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Customer:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="customer_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="customer_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="customer_summary" value="$<?php echo $final_total_customer;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Inventory".',') !== FALSE) {
				$total_price += $final_total_inventory;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Inventory:</td>
					<td data-title="Cost"><input type="text" name="inventory_summary_cost" class="form-control"></td>
					<td data-title="Profit"><input type="text" name="inventory_summary_profit" class="form-control"></td>
					<td data-title="Margin"><input type="text" name="inventory_summary_margin" class="form-control"></td>
					<td class='column-total' data-title="Total CAD"><input name="inventory_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="inventory_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="inventory_summary" value="$<?php echo $final_total_inventory;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Equipment".',') !== FALSE) {
				$total_price += $final_total_equipment;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Equipment:</td>
					<td data-title="Cost"><input type="text" name="equipment_summary_cost" class="form-control"></td>
					<td data-title="Profit"><input type="text" name="equipment_summary_profit" class="form-control"></td>
					<td data-title="Margin"><input type="text" name="equipment_summary_margin" class="form-control"></td>
					<td class='column-total' data-title="Total CAD"><input name="equipment_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="equipment_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="equipment_summary" value="$<?php echo $final_total_equipment;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Expenses".',') !== FALSE) {
				$total_price += $final_total_expense;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Expenses:</td>
					<td data-title="Cost"><input type="text" name="expense_summary_cost" class="form-control"></td>
					<td data-title="Profit"><input type="text" name="expense_summary_profit" class="form-control"></td>
					<td data-title="Margin"><input type="text" name="expense_summary_margin" class="form-control"></td>
					<td class='column-total' data-title="Total CAD"><input name="expense_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="expense_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="expense_summary" value="<?php echo $final_total_expense;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php if (strpos($value_config, ','."Other".',') !== FALSE) {
				$total_price += $final_total_other;
				$total_price_usd += 0;
				$total_cost += 0; ?>
				<tr>
					<td data-title="Type">Other:</td>
					<td data-title="Cost"></td>
					<td data-title="Profit"></td>
					<td data-title="Margin"></td>
					<td class='column-total' data-title="Total CAD"><input name="other_summary_cad" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total USD"><input name="other_summary_usd" value="" type="text" class="form-control"></td>
					<td class='column-total' data-title="Total"><input name="other_summary" value="<?php echo $final_total_other;?>" type="text" class="form-control"></td>
				</tr>
			<?php } ?>
			<?php foreach($accordions as $accordion):
				if($accordion != '') {
					$config_arr = explode(',',$accordion);
					$name = $config_arr[0];
					$id = str_replace(' ','',strtolower($name));
					$total_price += ${'final_total_'.$id};
					$total_price_usd += 0;
					$total_cost += 0; ?>
						<tr>
							<td data-title="Type"><?php echo $name; ?>:</td>
							<td data-title="Cost"><input type="text" name="<?php echo $id; ?>_summary_cost" class="form-control"></td>
							<td data-title="Profit"><input type="text" name="<?php echo $id; ?>_summary_profit" class="form-control"></td>
							<td data-title="Margin"><input type="text" name="<?php echo $id; ?>_summary_margin" class="form-control"></td>
							<td class='column-total' data-title="Total CAD"><input name="<?php echo $id; ?>_summary_cad" value="" type="text" class="form-control"></td>
							<td class='column-total' data-title="Total USD"><input name="<?php echo $id; ?>_summary_usd" value="" type="text" class="form-control"></td>
							<td class='column-total' data-title="Total"><input name="<?php echo $id; ?>_summary" value="$<?php echo ${'final_total_'.$id};?>" type="text" class="form-control"></td>
						</tr>
				<?php } ?>
			<?php endforeach; ?>
			<tr>
				<td data-title="Type"><b>Grand Total:</b></td>
				<td data-title="Cost"><input name="summary_total_cost" value="$<?php echo $total_cost;?>" type="text" class="form-control"></td>
				<td data-title="Profit"><input name="summary_total_profit" value="$<?php echo $total_cost - $total_cost;?>" type="text" class="form-control"></td>
				<td data-title="Margin"><input name="summary_total_margin" value="<?php echo round(($total_cost - $total_cost) / $total_cost * 100, 2);?>%" type="text" class="form-control"></td>
				<td class='column-total' data-title="Total CAD"><input name="summary_total_cad" value="$<?php echo $total_cost - $total_price_usd;?>" type="text" class="form-control"></td>
				<td class='column-total' data-title="Total USD"><input name="summary_total_usd" value="$<?php echo $total_price_usd;?>" type="text" class="form-control"></td>
				<td class='column-total' data-title="Total"><input name="summary_total" value="$<?php echo $total_price;?>" type="text" class="form-control"></td>
			</tr>
			<tr class="usdinfo" style="display:none;">
				<td data-title="Type"><b>Conversion Ratio:</b></td>
			</tr>
		</tbody>
	</table>
</div>