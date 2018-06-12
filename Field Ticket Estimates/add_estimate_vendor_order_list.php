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
		var estime_price = round2Fixed($(this).val());
		if(qty_get > 0 && estime_price > 0) {
			var total = qty_get*estime_price;
		} else {
			var total = 0;
		}
		total = Math.round(total * 100) / 100;
		$('#total_vndr_'+arr[1]).val(total);

		var grand_total = 0;
		$('.vestimate_total').each(function () {
			grand_total += +$(this).val() || 0;
		});
		grand_total = Math.round(grand_total * 100) / 100;
		$('#grand_vndr_total').val(grand_total);
		$('.vpl_summie').val(grand_total);

		var vendor_budget = $('[name="vendor_budget"]').val();
		if(vendor_budget >= grand_total) {
			<?php if(!isset($washtech_software_checker)) { ?>
				$('[name="grand_total"]').css("background-color", "#9CBA7F"); // Red
			<?php } ?>
		} else {
			$('[name="grand_total"]').css("background-color", "#ff9999"); // Green
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
		var qty_get = $('#vestimateprice_'+arr[2]).val();

		qty_get = round2Fixed(qty_get);
		var estime_price = round2Fixed($(this).val());

		if(qty_get > 0 && estime_price > 0) {
			var total = qty_get*estime_price;
		} else {
			var total = 0;
		}

		total = Math.round(total * 100) / 100;
		$('#total_vndr_'+arr[2]).val(total);

		var grand_total = 0;
		$('.vestimate_total').each(function () {
			grand_total += +$(this).val() || 0;
		});

		grand_total = Math.round(grand_total * 100) / 100;
		$('#grand_vndr_total').val(grand_total);
		$('.vpl_summie').val(grand_total);

		var vendor_budget = $('[name="vendor_budget"]').val();
		if(vendor_budget >= grand_total) {
			<?php if(!isset($washtech_software_checker)) { ?>
				$('[name="grand_total"]').css("background-color", "#9CBA7F"); // Red
			<?php } ?>
		} else {
			$('[name="grand_total"]').css("background-color", "#ff9999"); // Green
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

		echo '<br><br><div id="no-more-tables" style="height:1000px;"><table border="1" style="padding:2px;font-size:12px;text-align:center !important;"><tr class="hidden-xs hidden-sm" ><th style="text-align:center;">Category</th><th style="text-align:center;">Part #</th><th style="text-align:center;">Product</th><th style="text-align:center;">P.O. Price</th><th style="text-align:center;">Bid Price</th><th style="text-align:center;">Quantity</th><th style="text-align:center;">Totals</th></tr>';

		$get_vendor_pricelist = '';

		$final_total_vendor = 0;

			$query = mysqli_query ( $dbc, "SELECT inventoryid, category,part_no,name,purchase_order_price FROM vendor_price_list WHERE deleted = 0 order by category" );
			while ( $row = mysqli_fetch_array ( $query ) ) {
				echo "<tr class='vndrowshow vndrowhide_".$row['inventoryid']."'>";
				echo "<td data-title='Category'>".$row['category']."</td>";
				echo "<td data-title='Part Number'>".$row['part_no']."</td>";
				echo "<td data-title='Name'>".decryptIt($row['name'])."</td>";
				if($row['purchase_order_price'] > 0) {
					$popricer = $row['purchase_order_price'];
				} else {
					$popricer = 0;
				}
				echo "<td data-title='Price'>$".$popricer."</td>";
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
				echo '<td data-title="Estimated Price"><input name="vestimateprice[]" id="vestimateprice_'.$row['inventoryid'].'" value="'.$estim_price.'" type="text" class="form-control vndr_estime_price_changer" /></td>';
				echo '<td data-title="Quantity"><input '.$name_qtyer.' id="quantity_vndr_'.$row['inventoryid'].'" type="text" value="'.$qty_estim.'" class="form-control vndr_qty_changer" /><input name="vestimatevendorid[]" type="hidden" value="'.$row['inventoryid'].'" class="form-control" /></td>';
				echo '<td data-title="Total"><input name="total[]" readonly id="total_vndr_'.$row['inventoryid'].'" value="'.$total_estim.'" type="text" class="form-control vestimate_total" /></td>
				</tr>';
			}

		 ?>
		</table></div>

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
