<?php $accordions = explode('#*#',mysqli_fetch_array(mysqli_query($dbc,"SELECT `custom_accordions` FROM `field_config_estimate`"))['custom_accordions']);
foreach($accordions as $accordion):
	$name = explode(',',$accordion)[0];
	$id = str_replace(' ','',strtolower($name));
	if($name != '') { ?>
		<div class="panel panel-default" style="<?php echo (strpos($estimateConfigValue, ','.$id.',') === FALSE && $estimateTab != '') ? "display:none;" : ""; ?>">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?php echo $id; ?>" ><?php echo $estimateTab.' '.$name; ?><span class="glyphicon glyphicon-plus"></span></a>
				</h4>
			</div>

			<div id="collapse_<?php echo $id; ?>" class="panel-collapse collapse">
				<div class="panel-body">
					<script>
					$(document).ready(function() {
						if($('.order_list_<?php echo $id; ?>').length > 0) {
							$('.all_<?php echo $id; ?>').hide();
							$('.<?php echo $id; ?>_heading').hide();

							$('.order_list_<?php echo $id; ?>').on( 'click', function () {
								var pro_type = $(this).attr("id");

								$('.all_<?php echo $id; ?>').hide();
								$('.'+pro_type).show();
								$('.universal_<?php echo $id; ?>').show();
								$('.<?php echo $id; ?>_heading').show();

								$('.order_list_<?php echo $id; ?>').removeClass('active_tab');
								$(this).addClass('active_tab');
							});
							
							if($('.order_list_<?php echo $id; ?>').length == 1 && '<?php echo $load_tab; ?>' != 'Master') {
								$('.order_list_<?php echo $id; ?>').click();
							}
						}

						//Services
						var add_new_<?php echo $id; ?> = 1;
						$('#delete<?php echo $id; ?>_0').hide();
						$('#add_row_<?php echo $id; ?>').on( 'click', function () {
							$('#delete<?php echo $id; ?>_0').show();
							var clone = $('.additional_<?php echo $id; ?>').clone();
							clone.find('.form-control').val('');

							clone.find('#<?php echo $id; ?>_0').attr('id', '<?php echo $id; ?>_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>heading_0').attr('id', '<?php echo $id; ?>heading_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>hr_0').attr('id', '<?php echo $id; ?>hr_'+add_new_<?php echo $id; ?>);
							clone.find('#plc_0').attr('id', 'plc_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>finalprice_0').attr('id', '<?php echo $id; ?>finalprice_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>estimateprice_0').attr('id', '<?php echo $id; ?>estimateprice_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>estimateqty_0').attr('id', '<?php echo $id; ?>estimateqty_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>profit_0').attr('id', '<?php echo $id; ?>profit_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>profitmargin_0').attr('id', '<?php echo $id; ?>profitmargin_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>estimateunit_0').attr('id', '<?php echo $id; ?>estimateunit_'+add_new_<?php echo $id; ?>);
							clone.find('#<?php echo $id; ?>estimatetotal_0').attr('id', '<?php echo $id; ?>estimatetotal_'+add_new_<?php echo $id; ?>);

							clone.find('#<?php echo $id; ?>full_0').attr('id', '<?php echo $id; ?>full_'+add_new_<?php echo $id; ?>);
							clone.find('#delete<?php echo $id; ?>_0').attr('id', 'delete<?php echo $id; ?>_'+add_new_<?php echo $id; ?>);
							$('#delete<?php echo $id; ?>_0').hide();

							clone.removeClass("additional_<?php echo $id; ?>");
							$('#add_here_new_<?php echo $id; ?>').append(clone);

							resetChosen($("#<?php echo $id; ?>_"+add_new_<?php echo $id; ?>));
							resetChosen($("#<?php echo $id; ?>heading_"+add_new_<?php echo $id; ?>));

							add_new_<?php echo $id; ?>++;
							change<?php echo $id; ?>Total();

							return false;
						});
						
						var add_new_<?php echo $id; ?>_misc = 1;
						$('#delete<?php echo $id; ?>misc_0').hide();
						$('#add_row_<?php echo $id; ?>_misc').on( 'click', function () {

							$('#delete<?php echo $id; ?>misc_0').show();
							var clone_misc = $('.additional_<?php echo $id; ?>_misc').clone();
							clone_misc.find('.form-control').val('');
							clone_misc.attr('id', '<?php echo $id; ?>misc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>id_misc_0').attr('id', '<?php echo $id; ?>id_misc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>type_misc_0').attr('id', '<?php echo $id; ?>type_misc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>disc_misc_0').attr('id', '<?php echo $id; ?>disc_misc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>uom_misc_0').attr('id', '<?php echo $id; ?>uom_misc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>headmisc_0').attr('id', '<?php echo $id; ?>headmisc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>costmisc_0').attr('id', '<?php echo $id; ?>costmisc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>qtymisc_0').attr('id', '<?php echo $id; ?>qtymisc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>totalmisc_0').attr('id', '<?php echo $id; ?>totalmisc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>estimatepricemisc_0').attr('id', '<?php echo $id; ?>estimatepricemisc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>marginmisc_0').attr('id', '<?php echo $id; ?>marginmisc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>profitmisc_0').attr('id', '<?php echo $id; ?>profitmisc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#<?php echo $id; ?>misc_0').attr('id', '<?php echo $id; ?>misc_'+add_new_<?php echo $id; ?>_misc);
							clone_misc.find('#delete<?php echo $id; ?>misc_0').attr('id', 'delete<?php echo $id; ?>misc_'+add_new_<?php echo $id; ?>_misc);
							$('#delete<?php echo $id; ?>misc_0').hide();

							clone_misc.removeClass("additional_<?php echo $id; ?>_misc");

							$('#add_here_new_<?php echo $id; ?>_misc').append(clone_misc);

							add_new_<?php echo $id; ?>_misc++;
							change<?php echo $id; ?>Total();

							return false;
						});
						
						change<?php echo $id; ?>Total();
						$('[name*=<?php echo $id; ?>][readonly][value=""]').removeAttr('readonly');
					});

					//Services
					function select<?php echo $id; ?>(sel) {
						var stage = sel.value;
						var typeId = sel.id;
						var arr = typeId.split('_');
						$.ajax({
							type: "GET",
							url: "estimate_ajax_all.php?fill=<?php echo $id; ?>_type_config&value="+stage,
							dataType: "html",   //expect html to be returned
							success: function(response){
								$("#<?php echo $id; ?>heading_"+arr[1]).html(response);
								$("#<?php echo $id; ?>heading_"+arr[1]).trigger("change.select2");
							}
						});
						change<?php echo $id; ?>Total();
					}

					function select<?php echo $id; ?>Heading(sel) {
						var stage = sel.value;
						var typeId = sel.id;
						var arr = typeId.split('_');
						var ratecardid = $("#hidden_ratecardid").val();

						$.ajax({
							type: "GET",
							url: "estimate_ajax_all.php?fill=l_head_config&value="+stage+"&ratecardid="+ratecardid,
							dataType: "html",   //expect html to be returned
							success: function(response){
								var result = response.split('*');
								$("#<?php echo $id; ?>hr_"+arr[1]).val(result[0]);
								$("#<?php echo $id; ?>finalprice_"+arr[1]).val(result[1]);
								$("#plc_"+arr[1]).val(result[2]);

							}
						});
						change<?php echo $id; ?>Total();
					}
					function count<?php echo $id; ?>(txb) {
						if(txb != 'delete') {
							var get_id = txb.id;

							var split_id = get_id.split('_');
							var lbqty = $('#<?php echo $id; ?>estimateqty_'+split_id[1]).val();
							if(lbqty == null || lbqty == '') {
								lbqty = 1;
							}

							document.getElementById('<?php echo $id; ?>estimatetotal_'+split_id[1]).value = parseFloat($('#<?php echo $id; ?>estimateprice_'+split_id[1]).val() * lbqty).toFixed(2);
						}

						
						var sum_fee = 0;
						$('[name="<?php echo $id; ?>estimatetotal[]"]').each(function () {
							sum_fee += Number($(this).val());
						});
						$('[name="<?php echo $id; ?>totalmisc[]"]').each(function () {
							sum_fee += Number($(this).val());
						});
						$('[name="<?php echo $id; ?>totalmisc_display[]"]').each(function () {
							sum_fee += Number($(this).val());
						});
						
						$('[name="<?php echo $id; ?>_total"]').val('$'+round2Fixed(sum_fee));
						$('[name="<?php echo $id; ?>_summary"]').val('$'+round2Fixed(sum_fee)).change();

						var <?php echo $id; ?>_budget = $('[name="<?php echo $id; ?>_budget"]').val();
						if(<?php echo $id; ?>_budget >= sum_fee) {
							$('[name="<?php echo $id; ?>_total"]').css("background-color", "#9CBA7F"); // Red
						} else {
							$('[name="<?php echo $id; ?>_total"]').css("background-color", "#ff9999"); // Green
						}
						change<?php echo $id; ?>Total();
					}

					function fillmargincrc<?php echo $id; ?>value(est) {
						var idarray = est.id.split("_");
						var profitid = 'crc_<?php echo $id; ?>_profit_' + idarray[3];
						var profitmarginid = 'crc_<?php echo $id; ?>_margin_' + idarray[3];
						var pcid = 'crc_<?php echo $id; ?>_cost_' + idarray[3];
						var pcvalue = jQuery('#'+pcid).val();
						var pestimatevalue = est.value;
						var qty = jQuery('#crc_<?php echo $id; ?>_qty_' + idarray[3]).val();
						if(qty == '' || qty == null) {
							jQuery('#crc_<?php echo $id; ?>_qty_' + idarray[3]).val(1);
							qty = 1;
						}
						if(parseFloat(pestimatevalue) < parseFloat(pcvalue)) {
							jQuery('#'+profitid).val('');
							jQuery('#'+profitmarginid).val('');
						}
						else if(typeof pcvalue != 'undefined' && pcvalue != null && pcvalue != '' && pestimatevalue != null && pestimatevalue != '') {
							var deltavalue = (pestimatevalue - pcvalue) * qty;
							var deltaper = (deltavalue / (pestimatevalue * qty)) * 100;
							if(deltavalue > 0) {
								jQuery('#'+profitid).val(deltavalue.toFixed(2));
								jQuery('#'+profitmarginid).val(deltaper.toFixed(2));
							}
						}
						
						change<?php echo $id; ?>Total();
					}

					function qtychangecrcvalue<?php echo $id; ?>(qty) {
						var idarray = qty.id.split("_");
						var profitid = 'crc_<?php echo $id; ?>_profit_' + idarray[3];
						var profitmarginid = 'crc_<?php echo $id; ?>_margin_' + idarray[3];
						var pestimateid = 'crc_<?php echo $id; ?>_custprice_' + idarray[3];
						var pcid = 'crc_<?php echo $id; ?>_cost_' + idarray[3];
						if($('#'+pestimateid).val() == '') {
							$('#'+pestimateid).val($('#'+pcid).val());
						}
						var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
						var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
						jQuery('#'+profitid).val(del.toFixed(2));
						jQuery('#'+profitmarginid).val(delper.toFixed(2));
						change<?php echo $id; ?>Total();
					}

					function fill<?php echo $id; ?>marginvalue(est) {
						var idarray = est.id.split("_");
						var profitid = '<?php echo $id; ?>profit_' + idarray[1];
						var profitmarginid = '<?php echo $id; ?>profitmargin_' + idarray[1];
						var pcid = 'plc_' + idarray[1];
						var pcvalue = jQuery('#'+pcid).val();
						var pestimatevalue = est.value;
						var qty = jQuery('#<?php echo $id; ?>estimateqty_' + idarray[1]).val();
						if(qty == '' || qty == null) {
							jQuery('#<?php echo $id; ?>estimateqty_' + idarray[1]).val(1);
							qty = 1;
						}
						if(parseFloat(pestimatevalue) < parseFloat(pcvalue)) {
							jQuery('#'+profitid).val('');
							jQuery('#'+profitmarginid).val('');
						}
						else if(typeof pcvalue != 'undefined' && pcvalue != null && pcvalue != '' && pestimatevalue != null && pestimatevalue != '') {
							var deltavalue = (pestimatevalue - pcvalue) * qty;
							var deltaper = (deltavalue / (pestimatevalue * qty)) * 100;
							if(deltavalue > 0) {
								jQuery('#'+profitid).val(deltavalue.toFixed(2));
								jQuery('#'+profitmarginid).val(deltaper.toFixed(2));
							}
						}

						change<?php echo $id; ?>Total();
					}

					function qtychange<?php echo $id; ?>value(qty) {
						var idarray = qty.id.split("_");
						var profitid = '<?php echo $id; ?>profit_' + idarray[1];
						var profitmarginid = '<?php echo $id; ?>profitmargin_' + idarray[1];
						var pestimateid = '<?php echo $id; ?>estimateprice_' + idarray[1];
						var pcid = 'plc_' + idarray[1];
						var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
						var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
						jQuery('#'+profitid).val(del.toFixed(2));
						jQuery('#'+profitmarginid).val(delper.toFixed(2));
						change<?php echo $id; ?>Total();
					}

					function qtychangemiscvalue<?php echo $id; ?>(qty) {
						var idarray = qty.id.split("_");
						var profitid = '<?php echo $id; ?>profitmisc_' + idarray[1];
						var profitmarginid = '<?php echo $id; ?>marginmisc_' + idarray[1];
						var pestimateid = '<?php echo $id; ?>estimatepricemisc_' + idarray[1];
						var pcid = '<?php echo $id; ?>costmisc_' + idarray[1];
						var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
						var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
						jQuery('#'+profitid).val(del.toFixed(2));
						jQuery('#'+profitmarginid).val(delper.toFixed(2));
						change<?php echo $id; ?>Total();
					}

					function fillmarginmiscvalue<?php echo $id; ?>(est) {
						var idarray = est.id.split("_");
						var profitid = '<?php echo $id; ?>profitmisc_' + idarray[1];
						var profitmarginid = '<?php echo $id; ?>marginmisc_' + idarray[1];
						var pcid = '<?php echo $id; ?>costmisc_' + idarray[1];
						var pcvalue = jQuery('#'+pcid).val();
						var pestimatevalue = est.value;
						var qty = jQuery('#<?php echo $id; ?>qtymisc_' + idarray[1]).val();
						if(qty == '' || qty == null) {
							jQuery('#<?php echo $id; ?>qtymisc_' + idarray[1]).val(1);
							qty = 1;
						}
						if(parseFloat(pestimatevalue) < parseFloat(pcvalue)) {
							jQuery('#'+profitid).val('');
							jQuery('#'+profitmarginid).val('');
						}
						else if(typeof pcvalue != 'undefined' && pcvalue != null && pcvalue != '' && pestimatevalue != null && pestimatevalue != '') {
							var deltavalue = (pestimatevalue - pcvalue) * qty;
							var deltaper = (deltavalue / (pestimatevalue * qty)) * 100;
							if(deltavalue > 0) {
								jQuery('#'+profitid).val(deltavalue.toFixed(2));
								jQuery('#'+profitmarginid).val(deltaper.toFixed(2));
							}
						}
						change<?php echo $id; ?>Total();
					}

					function change<?php echo $id; ?>Total() {
						var sum_cost = 0;
						var sum_total = 0;
						
						$('[name="<?php echo $id; ?>totalmisc[]"]').each(function(key) {
							qty = +$($('[name="<?php echo $id; ?>qtymisc[]"]')[key]).val() || 0
							sum_cost += (+$($('[name="<?php echo $id; ?>costmisc[]"]')[key]).val() || 0) * qty;
							sum_total += +$(this).val() || 0;
						});
						$('[name="<?php echo $id; ?>totalmisc_display[]"]').each(function(key) {
							qty = +$($('[name="<?php echo $id; ?>qtymisc_display[]"]')[key]).val() || 0
							sum_cost += (+$($('[name="<?php echo $id; ?>costmisc_display[]"]')[key]).val() || 0) * qty;
							sum_total += +$(this).val() || 0;
						});
						$('[name^=crc_<?php echo $id; ?>_total_]').each(function(key) {
							qty = +$($('[name^=crc_<?php echo $id; ?>_qty_]')[key]).val() || 0
							sum_cost += (+$($('[name^=crc_<?php echo $id; ?>_cost_]')[key]).val() || 0) * qty;
							sum_total += +$(this).val() || 0;
						});
						
						sum_profit = sum_total - sum_cost;
						var per_profit_margin = (sum_profit / sum_total) * 100;
						if(isNaN(per_profit_margin)) {
							per_profit_margin = 'N/A';
						}
						else {
							per_profit_margin = round2Fixed(per_profit_margin) + '%';
						}

						jQuery('#<?php echo $id; ?>_profit').val('$'+round2Fixed(sum_profit));
						jQuery('#<?php echo $id; ?>_profit_margin').val(per_profit_margin);
						jQuery('#<?php echo $id; ?>_cost').val('$'+round2Fixed(sum_total - sum_profit));
						jQuery('[name=<?php echo $id; ?>_total]').val('$'+round2Fixed(sum_total));
						jQuery('[name=<?php echo $id; ?>_summary_profit]').val('$'+round2Fixed(sum_profit));
						jQuery('[name=<?php echo $id; ?>_summary_margin]').val(per_profit_margin);
						jQuery('[name=<?php echo $id; ?>_summary_cost]').val('$'+round2Fixed(sum_total - sum_profit));
						jQuery('[name=<?php echo $id; ?>_summary]').val('$'+round2Fixed(sum_total)).change();
						
						var <?php echo $id; ?>_budget = $('[name="<?php echo $id; ?>_budget"]').val();
						if(<?php echo $id; ?>_budget >= sum_total) {
							$('[name="<?php echo $id; ?>_total"]').css("background-color", "#9CBA7F"); // Red
						} else {
							$('[name="<?php echo $id; ?>_total"]').css("background-color", "#ff9999"); // Green
						}
					}

					function countRCTotal<?php echo $id; ?>(sel) {
						var stage = sel.value;
						var typeId = sel.id;

						var arr = typeId.split('_');
						var del = (jQuery('#crc_<?php echo $id; ?>_custprice_'+arr[3]).val() * jQuery('#crc_<?php echo $id; ?>_qty_'+arr[3]).val());
						jQuery('#crc_<?php echo $id; ?>_total_'+arr[3]).val(round2Fixed(del));

						var sum_fee = 0;
						var crc_sum_fee = 0;
						$('[name="<?php echo $id; ?>estimatetotal[]"]').each(function () {
							sum_fee += +$(this).val() || 0;
						});
						for(var loop = 0; loop < 500; loop++) {
							if(typeof $('[name="crc_<?php echo $id; ?>_total_'+loop+'"]').val() !='undefined')
							{
								crc_sum_fee += +$('[name="crc_<?php echo $id; ?>_total_'+loop+'"]').val();
							}
							else {
								break;
							}
						}

						sum_fee += +crc_sum_fee;

						$('[name="<?php echo $id; ?>_total"]').val(round2Fixed(sum_fee));

						change<?php echo $id; ?>Total();
					}
					function countMisc<?php echo $id; ?>(txb)
					{
						var get_id = txb.id;

						var split_id = get_id.split('_');
						if(split_id[0] == '<?php echo $id; ?>estimatepricemisc') {
							var estqty = $('#<?php echo $id; ?>qtymisc_'+split_id[1]).val();
							if(estqty == null || estqty == '') {
								estqty = 1;
								document.getElementById('<?php echo $id; ?>qtymisc_'+split_id[1]).value = 1;
							}

							document.getElementById('<?php echo $id; ?>totalmisc_'+split_id[1]).value = parseFloat($('#<?php echo $id; ?>estimatepricemisc_'+split_id[1]).val() * estqty);
						}
						
						if(split_id[0] == '<?php echo $id; ?>qtymisc') {
							var estqty = txb.value;
							if(estqty == null || estqty == '') {
								estqty = 1;
								document.getElementById('<?php echo $id; ?>qtymisc_'+split_id[1]).value = 1;
							}

							document.getElementById('<?php echo $id; ?>totalmisc_'+split_id[1]).value = parseFloat($('#<?php echo $id; ?>estimatepricemisc_'+split_id[1]).val() * estqty);
						}
						change<?php echo $id; ?>Total();
					}
					function changeProfit<?php echo $id; ?>Price(profit)
					{
						var get_id = profit.id;
						var split_id = get_id.split('_');
						var qty = jQuery('#<?php echo $id; ?>estimateqty_' + split_id[1]).val();
						pcost = '<?php echo $id; ?>c_' + split_id[1];
						pestimateid = '<?php echo $id; ?>estimateprice_' + split_id[1];
						ptotal = '<?php echo $id; ?>totalmisc_' + split_id[1];
						profitid = '<?php echo $id; ?>profit_' + split_id[1];
						marginid = '<?php echo $id; ?>profitmargin_' + split_id[1];
						var estimateValue = 0;
						if(jQuery('#'+pcost).val() != '') {
							if(split_id[0] == '<?php echo $id; ?>profit')
							{
								estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
								estimateTotal = estimateValue * qty;
								estimateMargin = profit.value / (estimateValue * qty) * 100;
								jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
								jQuery('#'+ptotal).val(estimateTotal.toFixed(2));
								jQuery('#'+marginid).val(estimateMargin.toFixed(2));
							}
							
							if(split_id[0] == '<?php echo $id; ?>profitmargin')
							{
								estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
								estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
								estimateTotal = estimateValue * qty;
								jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
								jQuery('#'+ptotal).val(estimateTotal.toFixed(2));
								jQuery('#'+profitid).val(estimateProfit.toFixed(2));
							}
						}
						
						change<?php echo $id; ?>Total();
					}

					function changeProfit<?php echo $id; ?>RCPrice(profit)
					{
						var get_id = profit.id;
						var split_id = get_id.split('_');
						var qty = jQuery('#crc_<?php echo $id; ?>_qty_' + split_id[3]).val();
						pcost = 'crc_<?php echo $id; ?>_cost_' + split_id[3];
						pestimateid = 'crc_<?php echo $id; ?>_custprice_' + split_id[3];
						ptotal = 'crc_<?php echo $id; ?>_total_' + split_id[3];
						profitid = 'crc_<?php echo $id; ?>_profit_' + split_id[3];
						marginid = 'crc_<?php echo $id; ?>_margin_' + split_id[3];
						var estimateValue = 0;
						if(jQuery('#'+pcost).val() != '') {
							if(split_id[2] == 'profit')
							{
								estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
								estimateTotal = estimateValue * qty;
								estimateMargin = profit.value / (estimateValue * qty) * 100;
								jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
								jQuery('#'+ptotal).val(estimateTotal.toFixed(2));
								jQuery('#'+marginid).val(estimateMargin.toFixed(2));
								
							}
							
							if(split_id[2] == 'margin')
							{
								estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
								estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
								estimateTotal = estimateValue * qty;
								jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
								jQuery('#'+ptotal).val(estimateTotal.toFixed(2));
								jQuery('#'+profitid).val(estimateProfit.toFixed(2));
							}
						}
						
						change<?php echo $id; ?>Total();
					}

					function changeProfit<?php echo $id; ?>MiscPrice(profit)
					{
						var get_id = profit.id;
						var split_id = get_id.split('_');
						var qty = jQuery('#<?php echo $id; ?>qtymisc_' + split_id[1]).val();
						pcost = '<?php echo $id; ?>costmisc_' + split_id[1];
						pestimateid = '<?php echo $id; ?>estimatepricemisc_' + split_id[1];
						ptotal = '<?php echo $id; ?>totalmisc_' + split_id[1];
						profitid = '<?php echo $id; ?>profitmisc_' + split_id[1];
						marginid = '<?php echo $id; ?>marginmisc_' + split_id[1];
						var estimateValue = 0;
						if(jQuery('#'+pcost).val() != '') {
							if(split_id[0] == '<?php echo $id; ?>profitmisc')
							{
								estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
								estimateTotal = estimateValue * qty;
								estimateMargin = profit.value / (estimateValue * qty) * 100;
								jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
								jQuery('#'+ptotal).val(estimateTotal.toFixed(2));
								jQuery('#'+marginid).val(estimateMargin.toFixed(2));
							}
							
							if(split_id[0] == '<?php echo $id; ?>marginmisc')
							{
								estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
								estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
								estimateTotal = estimateValue * qty;
								jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
								jQuery('#'+ptotal).val(estimateTotal.toFixed(2));
								jQuery('#'+profitid).val(estimateProfit.toFixed(2));
							}
						}
						
						change<?php echo $id; ?>Total();
					}
					</script>
					<div class="form-group">
						<div class="col-sm-12">
							<?php foreach(explode(',',$companyrcid) as $cri => $crid) {
								$crname = mysqli_fetch_array(mysqli_query($dbc, "SELECT `rate_card_name` FROM `company_rate_card` WHERE `companyrcid`='$crid'"))['rate_card_name'];
								$crtype = explode(',',$ratecardtypes)[$cri];
								$crlabel = (empty($crname) ? '' : $crname.' - ').$crtype;
								$no_space_rate_card_types = str_replace(' ', '', $crlabel);
								$no_space_rate_card_types = str_replace('&', '', $no_space_rate_card_types);
								$no_space_rate_card_types = str_replace('-', '', $no_space_rate_card_types); ?>
								<a id="<?php echo $no_space_rate_card_types.$id; ?>" class="btn brand-btn order_list_<?php echo $id; ?> mobile-100" ><?php echo trim($crlabel,'- '); ?></a>
							<?php }
							if(count(explode(',',$companyrcid)) > 0) { ?>
								<div class="form-group clearfix <?php echo $id; ?>_heading hide-titles-mob">
									<?php $columns = count($field_order) + 1;
									foreach($field_order as $field_data):
										$data = explode('***',$field_data);
										if($data[1] == '') {
											$data[1] = $data[0];
										}
										switch($data[0]) {
											case 'Description':
												echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
												break;
											case 'Type':
											case 'Heading':
											case 'UOM':
											case 'Quantity':
											case 'Cost':
											case 'Margin':
											case 'Price':
											case 'Profit':
											case 'Total':
												echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
												break;
										}
									endforeach; ?>
								</div>
							<?php } ?>
							<?php foreach(explode(',',$companyrcid) as $cri => $crid) {
								$crname = mysqli_fetch_array(mysqli_query($dbc, "SELECT `rate_card_name` FROM `company_rate_card` WHERE `companyrcid`='$crid'"))['rate_card_name'];
								$crtype = explode(',',$ratecardtypes)[$cri];
								$crlabel = (empty($crname) ? '' : $crname.' - ').$crtype;
								$no_space_rate_card_types = str_replace(' ', '', $crlabel);
								$no_space_rate_card_types = str_replace('&', '', $no_space_rate_card_types);
								$no_space_rate_card_types = str_replace('-', '', $no_space_rate_card_types);
								$rc = 0;
								$query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE rate_card_name IN ('$crname','') AND ',$company_rate_categories,' LIKE CONCAT('%,',IFNULL(`rate_categories`,''),',%') AND tile_name='$id' AND (',$crtype,' LIKE CONCAT('%,',`rate_card_types`,',%') OR (rate_card_name='' AND `rate_card_types`='')) AND `deleted`=0 AND (DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31') OR `rate_card_name`='')");

								$columns = count($config_arr) + 1;
								while($row_rc = mysqli_fetch_array($query_rc)) {
									$rate_card_type = $row_rc['rate_card_types'];
									$rate_categories = $row_rc['rate_categories'];

									$row_companyrcid = $row_rc['companyrcid'];

									$estimate_company_rate_card = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM cost_estimate_company_rate_card WHERE companyrcid='$row_companyrcid' AND estimateid='$estimateid'"));
									$crc_type = $row_rc['rate_card_types'] == '' ? $estimate_company_rate_card['rc_type'] : $row_rc['rate_card_types'];
									$crc_heading = htmlspecialchars($row_rc['heading']) == '' ? $estimate_company_rate_card['heading'] : htmlspecialchars($row_rc['heading']);
									$crc_description = $row_rc['description'] == '' ? $estimate_company_rate_card['description'] : $row_rc['description'];
									$crc_uom = $row_rc['uom'] == '' ? $estimate_company_rate_card['uom'] : $row_rc['uom'];
									$crc_qty = $estimate_company_rate_card['qty'] == 0 ? '' : $estimate_company_rate_card['qty'];
									$crc_cost = $row_rc['cost'] == '' ? $estimate_company_rate_card['cost'] : $row_rc['cost'];
									$crc_margin = $estimate_company_rate_card['margin'];
									$crc_price = $estimate_company_rate_card['cust_price'];
									$crc_profit = $estimate_company_rate_card['profit'];
									$crc_total = $estimate_company_rate_card['rc_total'];

									?>

									<div class="form-group clearfix all_<?php echo $id; ?> <?php echo $no_space_rate_card_types.$id; ?> rc_est_<?php echo $id; ?>_<?php echo $rc; ?>" width="100%">
										<input type="hidden" name="crc_<?php echo $id; ?>_companyrcid_<?php echo $rc; ?>" value="<?php echo $row_rc['companyrcid']; ?>" />
										<?php $columns = count($field_order) + 1;
										foreach($field_order as $field_data):
											$data = explode('***',$field_data);
											if($data[1] == '') {
												$data[1] = $data[0];
											}
											echo "<div class=\"col-sm-2\" data-columns='".$columns."' data-width='".($data[0] == 'Description' ? 2 : 1)."'>";
											switch($data[0]) {
												case 'Description':
													echo '<label class="col-sm-4 show-on-mob" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
													break;
												case 'Type':
												case 'Heading':
												case 'UOM':
												case 'Quantity':
												case 'Cost':
												case 'Margin':
												case 'Price':
												case 'Profit':
												case 'Total':
													echo '<label class="col-sm-2 show-on-mob" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
													break;
											}
											switch($data[0]) {
												case 'Type': echo '<input value= "'.$crc_type.'" readonly="" name="crc_'.$id.'_type_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Heading': echo '<input value= "'.$crc_heading.'" readonly="" name="crc_'.$id.'_heading_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Description': echo '<input value= "'.$crc_description.'" readonly="" name="crc_'.$id.'_description_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="2" />'; break;
												case 'UOM': echo '<input value= "'.$crc_uom.'" readonly="" name="crc_'.$id.'_uom_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Quantity': echo '<input name="crc_'.$id.'_qty_'.$rc.'" value= "'.$crc_qty.'" type="text" onchange="qtychangecrcvalue'.$id.'(this); countRCTotal'.$id.'(this)" id="crc_'.$id.'_qty_'.$rc.'" class="form-control crc_'.$id.'_qty" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Cost': echo '<input value= "'.$crc_cost.'" readonly name="crc_'.$id.'_cost_'.$rc.'" id="crc_'.$id.'_cost_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Margin': echo '<input name="crc_'.$id.'_margin_'.$rc.'" onchange="changeProfit'.$id.'RCPrice(this);" value= "'.$crc_margin.'"  id="crc_'.$id.'_margin_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Price': echo '<input value= "'.$crc_price.'" onchange="fillmargincrc'.$id.'value(this); countRCTotal'.$id.'(this)" name="crc_'.$id.'_cust_price_'.$rc.'" type="text" id="crc_'.$id.'_custprice_'.$rc.'" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Profit': echo '<input name="crc_'.$id.'_profit_'.$rc.'" onchange="changeProfit'.$id.'RCPrice(this);" value= "'.$crc_profit.'"  id="crc_'.$id.'_profit_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
												case 'Total': echo '<input name="crc_'.$id.'_total_'.$rc.'" value= "'.$crc_total.'"  id="crc_'.$id.'_total_'.$rc.'" type="text" class="form-control" data-columns="'.$columns.'" data-width="1" />'; break;
											}
											echo "</div>";
										endforeach; ?>

										<?php if(!in_array_starts('Type', $field_order)): ?>
											<input value= "<?php echo $crc_type; ?>" name="crc_<?php echo $id; ?>_type_<?php echo $rc; ?>" type="text" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Heading', $field_order)): ?>
											<input value= "<?php echo $crc_heading; ?>" name="crc_<?php echo $id; ?>_heading_<?php echo $rc; ?>" type="text" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Description', $field_order)): ?>
											<input value= "<?php echo $crc_description; ?>" name="crc_<?php echo $id; ?>_description_<?php echo $rc; ?>" type="text" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('UOM', $field_order)): ?>
											<input value= "<?php echo $crc_uom; ?>" name="crc_<?php echo $id; ?>_uom_<?php echo $rc; ?>" type="text" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Quantity', $field_order)): ?>
											<input name="crc_<?php echo $id; ?>_qty_<?php echo $rc; ?>" value= "<?php echo $crc_qty; ?>" type="text" id="crc_<?php echo $id; ?>_qty_<?php echo $rc;?>" class="crc_<?php echo $id; ?>_qty" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Cost', $field_order)): ?>
											<input value= "<?php echo $crc_cost; ?>" name="crc_<?php echo $id; ?>_cost_<?php echo $rc; ?>" id="crc_<?php echo $id; ?>_cost_<?php echo $rc; ?>" type="text" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Margin', $field_order)): ?>
											<input name="crc_<?php echo $id; ?>_margin_<?php echo $rc; ?>" value="<?php echo $crc_margin; ?>"  id="crc_<?php echo $id; ?>_margin_<?php echo $rc;?>" type="text" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Price', $field_order)): ?>
											<input value= "<?php echo $crc_price; ?>" name="crc_<?php echo $id; ?>_cust_price_<?php echo $rc; ?>" type="text" id="crc_<?php echo $id; ?>_custprice_<?php echo $rc;?>" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Profit', $field_order)): ?>
											<input name="crc_<?php echo $id; ?>_profit_<?php echo $rc; ?>" value= "<?php echo $crc_profit; ?>"  id="crc_<?php echo $id; ?>_profit_<?php echo $rc;?>" type="text" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Total', $field_order)): ?>
											<input name="crc_<?php echo $id; ?>_total_<?php echo $rc; ?>" value="<?php echo $crc_total; ?>"  id="crc_<?php echo $id; ?>_total_<?php echo $rc;?>" type="text" style="display:none;" />
										<?php endif; ?>
									</div>

									<?php
									$rc++;
									${'final_total_'.$id} += $crc_total;
									${'final_total_'.$id.'_profit'} += $crc_profit;
									${'final_total_'.$id.'_margin'} += $crc_margin;
									${'final_total_'.$id.'_cost'} += $crc_total - $crc_profit;
								}
							} ?>
							<div class="form-group clearfix" style="margin-left:5px">
								<h3>Misc Items</h3>
								<div class="form-group clearfix hide-titles-mob">
									<?php $columns = count($field_order) + 1;
									foreach($field_order as $field_data):
										$data = explode('***',$field_data);
										if($data[1] == '') {
											$data[1] = $data[0];
										}
										switch($data[0]) {
											case 'Description':
												echo '<label class="col-sm-4 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
												break;
											case 'Type':
											case 'Heading':
											case 'UOM':
											case 'Quantity':
											case 'Cost':
											case 'Margin':
											case 'Price':
											case 'Profit':
											case 'Total':
												echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
												break;
										}
									endforeach; ?>
								</div>
								<div class="form-group clearfix additional_<?= $id ?>_misc" id="<?php echo $id; ?>misc_0">
									<?php foreach($field_order as $field_data):
										$data = explode('***',$field_data);
										if($data[1] == '') {
											$data[1] = $data[0];
										}
										echo "<div class=\"col-sm-2\" data-columns='".$columns."' data-width='".($data[0] == 'Description' ? 2 : 1)."'>";
										switch($data[0]) {
											case 'Description':
												echo '<label class="col-sm-4 show-on-mob" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
												break;
											case 'Type':
											case 'Heading':
											case 'UOM':
											case 'Quantity':
											case 'Cost':
											case 'Margin':
											case 'Price':
											case 'Profit':
											case 'Total':
												echo '<label class="col-sm-2 show-on-mob" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
												break;
										}
										switch($data[0]) {
											case 'Type': echo '<input name="'.$id.'type_misc[]" id="'.$id.'type_misc_0" type="text" class="form-control" />'; break;
											case 'Heading': echo '<input name="'.$id.'headmisc[]" id="'.$id.'headmisc_0" type="text" class="form-control" />'; break;
											case 'Description': echo '<input name="'.$id.'disc_misc[]" id="'.$id.'disc_misc_0" type="text" class="form-control" />'; break;
											case 'UOM': echo '<input name="'.$id.'uom_misc[]" id="'.$id.'uom_misc_0" type="text" class="form-control" />'; break;
											case 'Quantity': echo '<input name="'.$id.'qtymisc[]" id="'.$id.'qtymisc_0" type="text" class="form-control" onchange="countMisc'.$id.'(this); qtychangemiscvalue'.$id.'(this);" />'; break;
											case 'Cost': echo '<input name="'.$id.'costmisc[]" id="'.$id.'costmisc_0" type="text" class="form-control" />'; break;
											case 'Margin': echo '<input name="'.$id.'marginmisc[]" id="'.$id.'marginmisc_0" type="text" onchange="changeProfit'.$id.'MiscPrice(this);" class="form-control" />'; break;
											case 'Price': echo '<input name="'.$id.'estimatepricemisc[]" id="'.$id.'estimatepricemisc_0" type="text" class="form-control" onchange="countMisc'.$id.'(this); fillmarginmiscvalue'.$id.'(this);" />'; break;
											case 'Profit': echo '<input name="'.$id.'profitmisc[]" id="'.$id.'profitmisc_0" type="text" onchange="changeProfit'.$id.'MiscPrice(this);" class="form-control" />'; break;
											case 'Total': echo '<input name="'.$id.'totalmisc[]" id="'.$id.'totalmisc_0" type="text" class="form-control" />'; break;
										}
										echo "</div>";
									endforeach; ?>

									<?php if(!in_array_starts('Type', $field_order)): ?>
										<input name="<?php echo $id; ?>type_misc[]" id="<?php echo $id; ?>type_misc_0" type="text" class="form-control" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Heading', $field_order)): ?>
										<input name="<?php echo $id; ?>headmisc[]" id="<?php echo $id; ?>headmisc_0" type="text" class="form-control" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Description', $field_order)): ?>
										<input name="<?php echo $id; ?>disc_misc[]" id="<?php echo $id; ?>disc_misc_0" type="text" class="form-control" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('UOM', $field_order)): ?>
										<input name="<?php echo $id; ?>uom_misc[]" id="<?php echo $id; ?>uom_misc_0" type="text" class="form-control" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Quantity', $field_order)): ?>
										<input name="<?php echo $id; ?>qtymisc[]" id="<?php echo $id; ?>qtymisc_0" type="text" class="form-control" onchange="countMisc<?php echo $id; ?>(this); qtychangemiscvalue<?php echo $id; ?>(this);" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Cost', $field_order)): ?>
										<input name="<?php echo $id; ?>costmisc[]" id="<?php echo $id; ?>costmisc_0" type="text" class="form-control" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Margin', $field_order)): ?>
										<input name="<?php echo $id; ?>marginmisc[]" id="<?php echo $id; ?>marginmisc_0" type="text" onchange="changeProfit<?php echo $id; ?>MiscPrice(this);" class="form-control" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Price', $field_order)): ?>
										<input name="<?php echo $id; ?>estimatepricemisc[]" id="<?php echo $id; ?>estimatepricemisc_0" type="text" class="form-control" onchange="countMisc<?php echo $id; ?>(this); fillmarginmiscvalue<?php echo $id; ?>(this);" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Profit', $field_order)): ?>
										<input name="<?php echo $id; ?>profitmisc[]" id="<?php echo $id; ?>profitmisc_0" type="text" onchange="changeProfit<?php echo $id; ?>MiscPrice(this);" class="form-control" style="display:none;" />
									<?php endif; ?>
									<?php if(!in_array_starts('Total', $field_order)): ?>
										<input name="<?php echo $id; ?>totalmisc[]" id="<?php echo $id; ?>totalmisc_0" type="text" class="form-control" style="display:none;" />
									<?php endif; ?>
									
									<div class="col-sm-1" >
										<a href="#" onclick="deleteEstimate(this,'<?php echo $id; ?>misc_','<?php echo $id; ?>headmisc_'); return false;" id="delete<?php echo $id; ?>misc_0" class="btn brand-btn">Delete</a>
									</div>
								</div>
								
								<div id="add_here_new_<?php echo $id; ?>_misc"></div>
								
								<div class="form-group triple-gapped clearfix">
									<div class="col-sm-offset-4 col-sm-8">
										<button id="add_row_<?php echo $id; ?>_misc" class="btn brand-btn pull-left">Add Row</button>
									</div>
								</div>
								
								<br>
								<?php
								$query_misc_rc = mysqli_query($dbc,"SELECT * FROM estimate_misc WHERE accordion='$id' AND estimateid=" . $_GET['estimateid']);
								//exit;

								$misc_num_rows = mysqli_num_rows($query_misc_rc);
								if($misc_num_rows > 0) { ?>
									<div class="form-group clearfix products_misc_heading hide-titles-mob">
										<?php foreach($field_order as $field_data):
											$data = explode('***',$field_data);
											if($data[1] == '') {
												$data[1] = $data[0];
											}
											switch($data[0]) {
												case 'Description':
													echo '<label class="col-sm-4 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
													break;
												case 'Type':
												case 'Heading':
												case 'UOM':
												case 'Quantity':
												case 'Cost':
												case 'Margin':
												case 'Price':
												case 'Profit':
												case 'Total':
													echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
													break;
											}
										endforeach; ?>
									</div>
									<?php
								}
								
								$misc_rc = 0;
								while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) { ?>
									<div class="form-group clearfix">
										<?php foreach($field_order as $field_data):
											$data = explode('***',$field_data);
											if($data[1] == '') {
												$data[1] = $data[0];
											}
											echo "<div class=\"col-sm-2\" data-columns='".$columns."' data-width='".($data[0] == 'Description' ? 2 : 1)."'>";
											switch($data[0]) {
												case 'Description':
													echo '<label class="col-sm-4 show-on-mob" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
													break;
												case 'Type':
												case 'Heading':
												case 'UOM':
												case 'Quantity':
												case 'Cost':
												case 'Margin':
												case 'Price':
												case 'Profit':
												case 'Total':
													echo '<label class="col-sm-2 show-on-mob" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
													break;
											}
											switch($data[0]) {
												case 'Type': echo '<input name="'.$id.'type_misc_display[]" id="'.$id.'type_misc" value="'.$misc_row_rc['type'].'" readonly type="text" class="form-control" />'; break;
												case 'Heading': echo '<input name="'.$id.'headmisc_display[]" id="'.$id.'headmisc" value="'.$misc_row_rc['heading'].'" type="text" readonly class="form-control" />'; break;
												case 'Description': echo '<input name="'.$id.'disc_misc_display[]" id="'.$id.'disc_misc" type="text" value="'.$misc_row_rc['description'].'" readonly class="form-control" />'; break;
												case 'UOM': echo '<input name="'.$id.'uom_misc_display[]" id="'.$id.'uom_misc" type="text" value="'.$misc_row_rc['uom'].'" readonly class="form-control" />'; break;
												case 'Quantity': echo '<input name="'.$id.'qtymisc_display[]" id="'.$id.'qtymisc" type="text" readonly class="form-control" value="'.$misc_row_rc['qty'].'" onchange="countMisc'.$id.'(this);" />'; break;
												case 'Cost': echo '<input name="'.$id.'costmisc_display[]" id="'.$id.'costmisc" type="text" value="'.$misc_row_rc['cost'].'" readonly class="form-control" />'; break;
												case 'Margin': echo '<input name="'.$id.'marginmisc_display[]" id="'.$id.'marginmisc" value="'.$misc_row_rc['margin'].'" readonly type="text" class="form-control" />'; break;
												case 'Price': echo '<input name="'.$id.'estimatepricemisc_display[]" id="'.$id.'estimatepricemisc" readonly value="'.$misc_row_rc['estimate_price'].'" type="text" class="form-control" />'; break;
												case 'Profit': echo '<input name="'.$id.'profitmisc_display[]" id="'.$id.'profitmisc" value="'.$misc_row_rc['profit'].'" readonly type="text" class="form-control" />'; break;
												case 'Total': echo '<input name="'.$id.'totalmisc_display[]" id="'.$id.'totalmisc" value="'.$misc_row_rc['total'].'" readonly type="text" class="form-control" />'; break;
											}
											echo "</div>";
										endforeach; ?>

										<?php if(!in_array_starts('Type', $field_order)): ?>
											<input name="<?php echo $id; ?>type_misc_display[]" id="<?php echo $id; ?>type_misc" value="<?php echo $misc_row_rc['type'] ?>" readonly type="text" class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Heading', $field_order)): ?>
											<input name="<?php echo $id; ?>headmisc_display[]" id="<?php echo $id; ?>headmisc" value="<?php echo $misc_row_rc['heading'] ?>" type="text" readonly class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Description', $field_order)): ?>
											<input name="<?php echo $id; ?>disc_misc_display[]" id="<?php echo $id; ?>disc_misc" type="text" value="<?php echo $misc_row_rc['description'] ?>" readonly class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('UOM', $field_order)): ?>
											<input name="<?php echo $id; ?>uom_misc_display[]" id="<?php echo $id; ?>uom_misc" type="text" value="<?php echo $misc_row_rc['uom'] ?>" readonly class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Quantity', $field_order)): ?>
											<input name="<?php echo $id; ?>qtymisc_display[]" id="<?php echo $id; ?>qtymisc" type="text" readonly class="form-control" value="<?php echo $misc_row_rc['qty'] ?>" onchange="countMisc<?php echo $id; ?>(this);" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Cost', $field_order)): ?>
											<input name="<?php echo $id; ?>costmisc_display[]" id="<?php echo $id; ?>costmisc" type="text" value="<?php echo $misc_row_rc['cost'] ?>" readonly class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Margin', $field_order)): ?>
											<input name="<?php echo $id; ?>marginmisc_display[]" id="<?php echo $id; ?>marginmisc" value="<?php echo $misc_row_rc['margin'] ?>" readonly type="text" class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Price', $field_order)): ?>
											<input name="<?php echo $id; ?>estimatepricemisc_display[]" id="<?php echo $id; ?>estimatepricemisc" readonly value="<?php echo $misc_row_rc['estimate_price'] ?>" type="text" class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Profit', $field_order)): ?>
											<input name="<?php echo $id; ?>profitmisc_display[]" id="<?php echo $id; ?>profitmisc" value="<?php echo $misc_row_rc['profit'] ?>" readonly type="text" class="form-control" style="display:none;" />
										<?php endif; ?>
										<?php if(!in_array_starts('Total', $field_order)): ?>
											<input name="<?php echo $id; ?>totalmisc_display[]" id="<?php echo $id; ?>totalmisc" value="<?php echo $misc_row_rc['total'] ?>" readonly type="text" class="form-control" style="display:none;" />
										<?php endif; ?>
									</div>
									<?php
									$misc_rc++;
									${'final_total_misc_'.$id} += $misc_row_rc['total'];
								}
								?>
							</div>
						</div>
					</div>

					<input type="hidden" name="total_rc_<?php echo $id; ?>" value="<?php echo $rc; ?>" />

					<div class="form-group">
						<label for="company_name" class="col-sm-4 control-label">Total $ Cost: </label>
						<div class="col-sm-8">
						  <input name="<?php echo $id; ?>_cost" id="<?php echo $id; ?>_cost" value="<?php echo ${'final_total_'.$id.'_cost'}; ?>" readonly="" type="text" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
						<div class="col-sm-8">
							<input name="<?php echo $id; ?>_profit" id="<?php echo $id; ?>_profit" value="<?php echo ${'final_total_'.$id.'_profit'}; ?>" readonly="" type="text" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
						<div class="col-sm-8">
							<input name="<?php echo $id; ?>_profit_margin" id="<?php echo $id; ?>_profit_margin" value="<?php echo ${'final_total_'.$id.'_margin'}; ?>"  value="" readonly="" type="text" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
						<div class="col-sm-8">
						  <input name="<?php echo $id; ?>_total" value="<?php echo ${'final_total_'.$id} + ${'final_total_misc_'.$id};?>" type="text" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php endforeach; ?>