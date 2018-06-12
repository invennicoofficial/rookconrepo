<script>
$(document).ready(function() {
	//Vendor

	$('.order_list_all').on( 'click', function () {
        $('.vndrowshow').show(500);
		$('.order_list_get').removeClass('active_tab');
		$('.order_list_all').addClass('active_tab');
    });

	$('.order_list_get').on( 'click', function () {
        var inventory_list = $(this).next().val();
		var array = inventory_list.split(",");
		$('.vndrowshow').hide(500);
		for (i=0;i<array.length;i++){
			 $('.vndrowhide_'+array[i]).show(500);
		}
		$('.order_list_get').removeClass('active_tab');
		$('.order_list_all').removeClass('active_tab');
		$(this).addClass('active_tab');
    });

$(".vndr_estime_price_changer").focusout(function() {
	var thisval = parseFloat($(this).val());
	if(!isNaN(thisval)) {
		// ESTIMATE PRICE CHANGE

		var act_inv = $(this).attr('id');
		var arr = act_inv.split('_');
		var qty_get = $('#quantity_vndr_'+arr[1]).val();
		qty_get = round2Fixed(qty_get);
		var estime_price = $(this).val();
		var usd = '';
		var estime_price_num = estime_price.replace(' USD','');
		if(estime_price_num != estime_price) {
			usd = ' USD';
		}
		estime_price_num = round2Fixed(estime_price_num);
		if(qty_get > 0 && estime_price_num > 0) {
			var total = qty_get*estime_price_num;
		} else {
			var total = 0;
		}
		total = Math.round(total * 100) / 100;
		$('#total_vndr_'+arr[1]).val(round2Fixed(total));

		var grand_total = 0;
		var grand_total_usd = 0;
		$('.vestimate_total').each(function () {
			if($(this).val().search('USD') > 0) {
				grand_total_usd += +$(this).val().replace(' USD','') || 0;
			}
			else {
				grand_total += +$(this).val() || 0;
			}
		});
		
		grand_total = Math.round(grand_total * 100) / 100;
		grand_total_usd = Math.round(grand_total_usd * 100) / 100;
		$('[name^=grand_total').val('$'+round2Fixed(grand_total));
		$('[name=grand_total]').closest('.form-group').show();
		$('[name^=grand_total_').closest('.form-group').hide();
		$('[name^=vendor_summary]').val('$'+round2Fixed(grand_total)).change();
		$('[name=vendor_summary_usd]').val('$'+round2Fixed(grand_total_usd));
		if(grand_total_usd > 0) {
			$('[name=grand_total_usd]').val('$'+round2Fixed(grand_total_usd));
			$('[name=grand_total]').closest('.form-group').hide();
			$('[name^=grand_total_').closest('.form-group').show();
		}
	$('[name=vendor_summary]').change();
		var vendor_budget = $('[name="vendor_budget"]').val();
		if(vendor_budget >= grand_total) {
			<?php if(!isset($washtech_software_checker)) { ?>
				$('[name^="grand_total"]').css("background-color", "#9CBA7F"); // Red
			<?php } ?>
		} else {
			$('[name^="grand_total"]').css("background-color", "#ff9999"); // Green
		}
	}
});

$(".vndr_qty_changer").focusout(function() {
	var thisval = parseFloat($(this).val());
	if(!isNaN(thisval) && thisval > 0) {
		// ESTIMATE PRICE CHANGE
		$(this).attr('name', 'vestimateqty[]');
		var act_inv = $(this).attr('id');
		var arr = act_inv.split('_');
		var qty_get = $(this).val();

		qty_get = round2Fixed(qty_get);
		var estime_price = $('#vestimateprice_'+arr[2]).val();
		var usd = '';
		var estime_price_num = estime_price.replace(' USD','');
		if(estime_price_num != estime_price) {
			usd = ' USD';
		}
		estime_price_num = round2Fixed(estime_price_num);

		if(qty_get > 0 && estime_price_num > 0) {
			var total = qty_get*estime_price_num;
		} else {
			var total = 0;
		}

		total = Math.round(total * 100) / 100;
		$('#total_vndr_'+arr[2]).val(round2Fixed(total+usd));

		var grand_total = 0;
		var grand_total_usd = 0;
		$('.vestimate_total').each(function () {
			if($(this).val().search('USD') > 0) {
				grand_total_usd += +$(this).val().replace(' USD','') || 0;
			}
			else {
				grand_total += +$(this).val() || 0;
			}
		});

		grand_total = round2Fixed(grand_total * 100 / 100);
		grand_total_usd = round2Fixed(grand_total_usd * 100 / 100);
		$('[name^=grand_total').val('$'+round2Fixed(grand_total));
		$('[name=grand_total]').closest('.form-group').show();
		$('[name^=grand_total_').closest('.form-group').hide();
		$('[name^=vendor_summary]').val('$'+round2Fixed(grand_total)).change();
		$('[name=vendor_summary_usd]').val('$'+round2Fixed(grand_total_usd));
		if(grand_total_usd > 0) {
			$('[name=grand_total_usd]').val('$'+round2Fixed(grand_total_usd));
			$('[name=grand_total]').closest('.form-group').hide();
			$('[name^=grand_total_').closest('.form-group').show();
		}
		$('[name=vendor_summary]').change();
		var vendor_budget = $('[name="vendor_budget"]').val();
		if(vendor_budget >= grand_total) {
			<?php if(!isset($washtech_software_checker)) { ?>
				$('[name^="grand_total"]').css("background-color", "#9CBA7F"); // Red
			<?php } ?>
		} else {
			$('[name^="grand_total"]').css("background-color", "#ff9999"); // Green
		}
	} else {
		$(this).removeAttr('name');
	}
});

});

</script>
<?php
$get_field_config_vendors = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT vendors FROM field_config"));
$field_config_vendors = ','.$get_field_config_vendors['vendors'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
		<div class='mobile-100-container'>
        <a class="btn brand-btn order_list_all active_tab mobile-100">Show All</a>
		<?php
		$querxy = mysqli_query ( $dbc, "SELECT * FROM order_lists WHERE deleted = 0 AND tile = 'VPL' order by order_title" );
		while ( $row = mysqli_fetch_array ( $querxy ) ) { ?>
			<a class="btn brand-btn order_list_get mobile-100" ><?php echo $row['order_title']; ?></a>
			<input type='hidden' value="<?php echo $row['inventoryid']; ?>">
		<?php }
		echo '</div>';

		echo '<br><br><div id="no-more-tables" style="width:100%;"><table border="1" style="padding:2px;font-size:12px;text-align:center !important; width: 100%;">
			<tr class="hidden-xs hidden-sm" ><th style="text-align:center;">Category</th>
				<th style="text-align:center;">Part #</th>
				<th style="text-align:center;">Product</th>
				<th style="text-align:center;">P.O. Price</th>
				<th style="text-align:center;">Estimate Price</th>
				<th style="text-align:center;">Quantity</th>
				<th style="text-align:center;">Total</th>';
			if(in_array_starts('Total Multiple', $field_order)) {
				echo '<th style="text-align:center;">Total X 1</th>';
			}
			echo '</tr>';

		$get_vendor_pricelist = '';

		$final_total_vendor = 0;

			$query = mysqli_query ( $dbc, "SELECT inventoryid, category,part_no,name,purchase_order_price,cdn_cpu,usd_cpu FROM vendor_price_list WHERE deleted = 0 order by category" );
			while ( $row = mysqli_fetch_array ( $query ) ) {
				echo "<tr class='vndrowshow vndrowhide_".$row['inventoryid']."'>";
				echo "<td data-title='Category'>".$row['category']."</td>";
				echo "<td data-title='Part Number'>".$row['part_no']."</td>";
				echo "<td data-title='Name'>".$row['name']."</td>";
				if($row['purchase_order_price'] > 0) {
					$popricer = $row['purchase_order_price'];
				} else if($row['cdn_cpu'] > 0) {
					$popricer = $row['cdn_cpu'];
				} else if($row['usd_cpu'] > 0) {
					$popricer = $row['usd_cpu'].' USD';
				} else {
					$popricer = 0;
				}
				echo "<td data-title='P.O. Price'>$".$popricer."</td>";
				$vendor = $get_contact['vendor'];
				$each_data = explode('**',$vendor);
				$inventoryidx = '';
				$estim_price = '';
				$qty_estim = '';
				$total_estim = '';
				foreach($each_data as $id_all) {
					if($id_all != '') {
						$data_all = explode('#',$id_all);
						if($data_all[0]==$row['inventoryid']) {
							$inventoryidx = $data_all[0];
							$estim_price = $data_all[1];
							$qty_estim = $data_all[2];
							if($estim_price > 0 && $qty_estim > 0 ) {
								$total_estim = $qty_estim*$estim_price;
								$final_total_vendor += $total_estim;
							}
						}
					}
				}
				if($estim_price == '') {
					$estim_price = $popricer;
				}
				if($qty_estim > 0) {
					$name_qtyer = 'name="vestimateqty[]"';
				} else { $name_qtyer = ''; }
				echo '<td data-title="Estimate Price"><input name="vestimateprice[]" id="vestimateprice_'.$row['inventoryid'].'" value="'.$estim_price.'" type="text" class="form-control vndr_estime_price_changer" /></td>';
				echo '<td data-title="Quantity"><input '.$name_qtyer.' id="quantity_vndr_'.$row['inventoryid'].'" type="text" value="'.$qty_estim.'" class="form-control vndr_qty_changer" /><input name="vestimatevendorid[]" type="hidden" value="'.$row['inventoryid'].'" class="form-control" /></td>';
				echo '<td data-title="Total"><input name="total[]" readonly id="total_vndr_'.$row['inventoryid'].'" value="'.$total_estim.'" type="text" class="form-control vestimate_total" /></td>';
				if(in_array_starts('Total Multiple', $field_order)) {
					echo '<td data-title="Total Multiple"><input type="text" name="vestimatetotalmulti[]" id="total_multiple_vndr_'.$row['inventoryid'].'" class="form-control" /></td>';
				}
				echo '</tr>';
			}

		 ?>
		</table></div>

    </div>

	<div class="form-group" style="display:none;">
		<label for="grand_total" class="col-sm-4 control-label">Total USD Applied:</label>
		<div class="col-sm-8">
			<input name="grand_total_usd" id="grand_vndr_total_usd" type="text" value="<?php echo $final_total_vendor; ?>" class="form-control" />
		</div>
	</div>
	<div class="form-group" style="display:none;">
		<label for="grand_total" class="col-sm-4 control-label">Total CAD Applied:</label>
		<div class="col-sm-8">
			<input name="grand_total_cad" id="grand_vndr_total" type="text" value="<?php echo $final_total_vendor; ?>" class="form-control" />
		</div>
	</div>
	<div class="form-group">
		<label for="grand_total" class="col-sm-4 control-label">Total Applied:</label>
		<div class="col-sm-8">
			<input name="grand_total" id="grand_vndr_total" type="text" value="<?php echo $final_total_vendor; ?>" class="form-control" />
		</div>
	</div>
</div>
<!--
-- Commented for #974
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="vendor_budget" value="<?php //echo $budget_price[7]; ?>" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="vendor_total" value="<?php //echo $final_total_vendor;?>" type="text" class="form-control">
    </div>
</div>
-->
