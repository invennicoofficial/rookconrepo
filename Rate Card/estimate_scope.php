<?php $id = $_GET['rate'];
if(!($id > 0)) {
	$id = $_POST['id'];
}
if($id > 0) {
	$rate_card = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `rate_card_estimate_scopes` WHERE `id`='$id'"));
	$template = $rate_card['template_id'];
} else {
	$template = $_GET['template'];
} ?>
<script>
$(document).ready(function() {
	$('select,input').change(saveUpdate);
});
function saveUpdate() {
	saveField(this);
}
function saveField(target) {
	if($(target).data('table') != '') {
		$.ajax({
			url: 'ratecard_ajax.php?action=save_estimate_scope',
			method: 'POST',
			data: {
				table_name: $(target).data('table'),
				id: $(target).data('id'),
				line: $(target).data('line'),
				ratecard: '<?= $_GET['rate'] ?>',
				field_name: target.name,
				value: target.value
			},
			dataType: 'html',
			success: function(response) {
				if($(target).data('table') == 'rate_card_estimate_scopes' && response > 0) {
					$('[name=template_id]').data('id',response).change();
					window.location.replace('?type=estimate&template='+$('[name=template_id]').val()+'&rate='+response);
				} else if($(target).data('table') == 'rate_card_estimate_scope_lines') {
					$(target).addClass('blue-border');
					if(response > 0) {
						$(target).closest('tr').find('[data-line]').data('id',response);
						$(target).closest('tr').next('tr').find('[data-table][data-rate]').data('rate',response).filter('[name=rate_card_id]').val(response).change();
					}
				} else if($(target).data('table') == 'rate_card_breakdown') {
					var row = $(target).closest('tr');
					if(response > 0) {
						row.find('[data-id]').data('id',response);
					}
					var cost = row.find('[name=cost]').val();
					var qty = row.find('[name=quantity]').val();
					if(cost > 0 && qty > 0) {
						var total = Math.round(cost * qty * 100) / 100;
						var total_input = row.find('[name=total]');
						if(parseFloat(total_input.val()) != parseFloat(total)) {
							total_input.val(total.toFixed(2)).change();
							var totals = 0;
							$(row).closest('table').find('[name=total]').each(function() {
								totals += +this.value;
							});
							$(row).closest('table').closest('tr').prev('tr').find('[name=cost]').val(totals.toFixed(2)).change();
						}
					}
				}
			}
		});
	}
}
</script>
<div class="standard-dashboard-body-title"><h3>
<?php if($template > 0) {
	$template = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate_templates` WHERE `id`='$template'")); ?>
	<?= $template['template_name'] ?><input type="hidden" name="template_id" data-table="rate_card_estimate_scopes" data-id="<?= $id ?>" value="<?= $template['id'] ?>">:
	<span class="inline"><input type="text" name="rate_card_name" data-table="rate_card_estimate_scopes" data-id="<?= $id ?>" class="form-control" value="<?= $rate_card['rate_card_name'] ?>"></span> Rate Card</h3></div>
	<?php include('line_types.php'); ?>
	<?php if(count($regions) > 0) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Region</label>
			<div class="col-sm-8">
				<select name="region" class="chosen-select-deselect form-control" data-id="<?= $id ?>" data-table="rate_card_estimate_scopes">
					<option></option>
					<?php foreach($regions as $region) { ?>
						<option <?= $template['region'] == $region ? 'selected' : '' ?> value="<?= $region ?>"><?= $region ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php }
	if(count($locations) > 0) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Location</label>
			<div class="col-sm-8">
				<select name="location" class="chosen-select-deselect form-control" data-id="<?= $id ?>" data-table="rate_card_estimate_scopes">
					<option></option>
					<?php foreach($locations as $location) { ?>
						<option <?= $template['location'] == $location ? 'selected' : '' ?> value="<?= $location ?>"><?= $location ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php }
	if(count($classifications) > 0) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Classification</label>
			<div class="col-sm-8">
				<select name="classification" class="chosen-select-deselect form-control" data-id="<?= $id ?>" data-table="rate_card_estimate_scopes">
					<option></option>
					<?php foreach($classifications as $classification) { ?>
						<option <?= $template['classification'] == $classification ? 'selected' : '' ?> value="<?= $classification ?>"><?= $classification ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php } ?>
	<div id="no-more-tables" class="form-group">
		<script>
		$(document).ready(function() {
			$('input,select').off('change', update_field).change(update_field);
		});
		function update_field() {
			src = this;
			if(src.name == 'src_table') {
				if(src.value == 'miscellaneous') {
					$(src).closest('tr').find('.select_div').hide();
					$(src).closest('tr').find('[name=description]').show();
					$(src).closest('tr').find('.breakdown').show();
				} else {
					$(src).closest('tr').find('.breakdown').hide();
					$(src).closest('tr').find('[name=description]').hide();
					$(src).closest('tr').find('.select_div').show().find('option').each(function() {
						if((''+$(this).data('tile-name')).toLowerCase() == src.value.toLowerCase()) {
							$(this).show();
						} else {
							$(this).hide();
						}
					});
					$(src).closest('tr').find('.select_div select').trigger('change.select2');
				}
			}
			if($(src).data('table') != '') {
				if(src.name == 'margin') {
					var tr = $(src).closest('tr');
					var cost = tr.find('[name=cost]').val();
					var profit = tr.find('[name=profit]').val();
					tr.find('[name=cust_price]').val((cost * src.value / 100) + (+cost));
					tr.find('[name=profit]').val(tr.find('[name=cust_price]').val() - (+cost));
					if(profit != tr.find('[name=profit]').val()) {
						setTimeout(function() {
							tr.find('[name=profit]').change();
						}, 250);
					}
					setTimeout(function() {
						tr.find('[name=cust_price]').change();
					}, 250);
				} else if(src.name == 'profit') {
					var tr = $(src).closest('tr');
					var cost = tr.find('[name=cost]').val();
					var margin = tr.find('[name=margin]').val();
					tr.find('[name=cust_price]').val((+cost) + (+src.value));
					tr.find('[name=margin]').val(src.value / cost * 100);
					if(margin != tr.find('[name=margin]').val()) {
						setTimeout(function() {
							tr.find('[name=margin]').change();
						}, 250);
					}
					setTimeout(function() {
						tr.find('[name=cust_price]').change();
					}, 250);
				}
				saveField(this);
			}
		}
		function add_line(src) {
			var line = $(src).closest('tr');
			var clone = line.clone();
			clone.find('input,select').val('');
			clone.find('.breakdown').hide();
			clone.find('[name=description]').hide();
			clone.find('.select_div').show().find('option').hide();
			resetChosen(clone.find("select[class^=chosen]"));
			clone.find('[data-id]').data('id','');
			
			line.closest('table').append(clone);
			$('input,select').off('change', update_field).change(update_field);
		}
		function remove_line(a) {
			if($(a).closest('table').find('[name=src_table]').length <= 1) {
				add_line(a);
			}
			saveField(a);
			$(a).closest('tr').remove();
		}
		function add_heading() {
			var clone = $('table').last().clone();
			clone.find('input').val('');
			clone.data('heading','');
			
			$('table').last().after(clone);
			$('table').last().find('.line-handle').each(function() {
				remove_line(this);
			});
			$('input,select').off('change', update_field).change(update_field);
		}
		function remove_heading(src) {
			if($('table[data-heading]').length <= 1) {
				add_heading();
			}
			saveField(src);
			$(src).closest('table').remove();
		}
		function toggleBreakdown(a) {
			$(a).closest('tr').next('tr').toggle();
			$(a).text(a.text == '+ BREAKDOWN' ? '- BREAKDOWN' : '+ BREAKDOWN');
		}
		function addBreakdown(img) {
			var table = $(img).closest('table');
			var row = table.find('tr').last();
			var clone = row.clone();
			clone.find('input').val('').data('id','');
			row.after(clone);
			$('input,select').off('change', update_field).change(update_field);
		}
		function remBreakdown(img) {
			var table = $(img).closest('table');
			if(table.find('tr').length <= 2) {
				addBreakdown(img);
			}
			var row = $(img).closest('tr');
			row.find('[name=deleted]').val(1).change();
			row.remove();
		}
		</script>
		<?php $headings = mysqli_query($dbc, "SELECT `id`, `heading_name`, `sort_order` FROM `estimate_template_headings` WHERE `template_id`='{$template['id']}' AND `deleted`=0 UNION SELECT '', '', 999999999999 ORDER BY `sort_order`");
		while($heading = mysqli_fetch_array($headings)) { ?>
			<div class="sort_table">
				<table class="table table-bordered" data-heading="<?= $heading['id'] ?>">
					<tr>
						<td data-title="Heading Name" colspan="10">
							<input type="text" name="heading_name" value="<?= $heading['heading_name'] ?>" data-table="estimate_template_headings" class="form-control" style="display: inline; width: calc(100% - 2em);">
							<img src="../img/remove.png" style="width:1.5em;" class="pull-right heading-handle" onclick="remove_heading(this);" data-table="estimate_template_headings" name="deleted">
						</td>
					</tr>
					<tr class="hidden-sm hidden-xs">
						<th style="width:10%;">Tile</th>
						<th style="width:8%; text-align:center;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="This is the Unit of Measurement for the line item and rate."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> UOM</th>
						<th style="width:8%; text-align:center;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="View and set the quantity of each line item."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> Qty</th>
						<th style="width:19%;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="View and edit the description of each line item."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> Description</th>
						<th style="width:8%; text-align:center;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="View and edit the cost of each line item."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> Cost</th>
						<th style="width:8%; text-align:center;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="This is the profit margin of each line item."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> % Margin</th>
						<th style="width:8%; text-align:center;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="This is the percentage of profit from each line item."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> $ Profit</th>
						<th style="width:8%; text-align:center;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="This is the total of the cost plus the profit margin."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> Total</th>
						<th style="width:8%; text-align:center;"><div class="popover-examples" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="This is the amount the line item is being sold for."><img src="<?= WEBSITE_URL; ?>/img/info-w.png" width="18"></a></div> Final Retail Price</th>
						<th style="width:15%;"></th>
					</tr>
					<?php $lines = mysqli_query($dbc, "SELECT `id`, `description`, `src_table`, `src_id`, `qty`, `sort_order` FROM `estimate_template_lines` WHERE `heading_id`='{$heading['id']}' AND `deleted`=0 UNION SELECT '', '', '', '', '', 999999999999 ORDER BY `sort_order`");
					while($line = mysqli_fetch_array($lines)) {
						$rate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `rate_card_estimate_scope_lines` WHERE `line_id`='{$line['id']}' AND `rate_id`='$id'"));
						$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='{$line['src_table']}' AND `tile_name`!='miscellaneous' AND `item_id`='{$line['src_id']}' AND `deleted`=0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'clients') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='clients' AND `description`='".get_contact($dbc, $line['src_id'])."' AND `deleted`=0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'equipment') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='equipment' AND `description` IN (SELECT `unit_number` FROM `equipment` WHERE `equipmentid`='{$line['src_id']}') AND `deleted`=0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')
								UNION SELECT * FROM `equipment_rate_table` WHERE `deleted`=0 AND `equipment_id`='{$line['src_id']}' AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')
								UNION SELECT * FROM `category_rate_table` WHERE `deleted`=0 AND `category` IN (SELECT `category` FROM `equipment` WHERE `equipmentid`='{$line['src_id']}') AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'inventory') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='inventory' AND `description` IN (SELECT `product_name` FROM `inventory` WHERE `inventoryid`='{$line['src_id']}') AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'labour') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='labour' AND `description` IN (SELECT `heading` FROM `labour` WHERE `labourid`='{$line['src_id']}') AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'material') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='material' AND `description` IN (SELECT `name` FROM `material` WHERE `materialid`='{$line['src_id']}') AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'position') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='position' AND `description` IN (SELECT `name` FROM `positions` WHERE `position_id`='{$line['src_id']}' AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')) AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')
								UNION SELECT * FROM `position_rate_table` WHERE `position_id`='{$line['src_id']}' AND `deleted`=0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'products') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='products' AND `description` IN (SELECT CONCAT(`category`,' ',`heading`) FROM `products` WHERE `productid`='{$line['src_id']}') AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'services') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='services' AND `description` IN (SELECT CONCAT(`category`,' ',`heading`) FROM `services` WHERE `serviceid`='{$line['src_id']}') AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')
								UNION SELECT * FROM `service_rate_card` WHERE `serviceid`='{$line['src_id']}'");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'staff') {
							$comp_rate = mysqli_query($dbc, "SELECT * FROM `company_rate_card` WHERE LOWER(`tile_name`)='staff' AND `description`='".get_contact($dbc, $line['src_id'])."' AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')");
						} else if(mysqli_num_rows($comp_rate) == 0 && $line['src_table'] == 'vpl') {
							$comp_rate = mysqli_query($dbc, "");
						}
						$comp_rate = mysqli_fetch_array($comp_rate); ?>
						<tr>
							<td data-title="Tile"><select name="src_table" class="chosen-select-deselect form-control" data-id="<?= $line['id'] ?>" data-table="estimate_template_lines">
									<option></option>
									<?php foreach($tiles as $label => $tile_name) { ?>
										<option <?= $line['src_table'] == $tile_name ? 'selected' : '' ?> value="<?= $tile_name ?>"><?= $label ?></option>
									<?php } ?>
								</select></td>
							<td data-title="Unit of Measure"><input type="text" name="uom" class="form-control <?= $rate['uom'] != '' ? 'blue-border' : '' ?>" value="<?= $rate['uom'] != '' ? $rate['uom'] : $comp_rate['uom'] ?>" data-id="<?= $rate['id'] ?>" data-line="<?= $line['id'] ?>" data-table="rate_card_estimate_scope_lines"></td>
							<td data-title="Quantity"><input type="number" name="qty" class="form-control" data-id="<?= $rate['id'] ?>" data-table="estimate_template_lines" value="<?= $line['qty'] ?>" readonly></td>
							<td data-title="Description">
								<input type="text" name="description" class="form-control" value="<?= $line['description'] ?>" data-id="<?= $line['id'] ?>" data-table="estimate_template_lines" style="<?= $line['src_table'] == 'miscellaneous' ? '' : 'display: none;' ?>">
								<div class="select_div" <?= $line['src_table'] == 'miscellaneous' ? 'style="display: none;"' : '' ?>><select name="src_id" class="chosen-select-deselect form-control" data-id="<?= $line['id'] ?>" data-table="estimate_template_lines">
									<option></option>
									<?php foreach($src_options as $option) { ?>
										<option <?= $option['id'] == $line['src_id'] && strtolower($option['tile_name']) == strtolower($line['src_table']) ? 'selected' : (strtolower($option['tile_name']) == strtolower($line['src_table']) ? '' : 'style="display:none;"') ?> value="<?= $option['id'] ?>" data-tile-name="<?= $option['tile_name'] ?>"><?= $option['label'] ?></option>
									<?php } ?>
								</select></div>
							</td>
							<td data-title="Cost"><input type="text" name="cost" class="form-control <?= $rate['cost'] != 0 ? 'blue-border' : '' ?>" value="<?= $rate['cost'] != 0 ? $rate['cost'] : $comp_rate['cost'] ?>" data-id="<?= $rate['id'] ?>" data-line="<?= $line['id'] ?>" data-table="rate_card_estimate_scope_lines"></td>
							<td data-title="Margin"><input type="text" name="margin" class="form-control <?= $rate['margin'] != 0 ? 'blue-border' : '' ?>" value="<?= $rate['margin'] != 0 ? $rate['margin'] : $comp_rate['margin'] ?>" data-id="<?= $rate['id'] ?>" data-line="<?= $line['id'] ?>" data-table="rate_card_estimate_scope_lines"></td>
							<td data-title="Profit"><input type="text" name="profit" class="form-control <?= $rate['profit'] != 0 ? 'blue-border' : '' ?>" value="<?= $rate['profit'] != 0 ? $rate['profit'] : $comp_rate['profit'] ?>" data-id="<?= $rate['id'] ?>" data-line="<?= $line['id'] ?>" data-table="rate_card_estimate_scope_lines"></td>
							<td data-title="Total"><input type="text" name="cust_price" class="form-control <?= $rate['cust_price'] != 0 ? 'blue-border' : '' ?>" value="<?= $rate['cust_price'] != 0 ? $rate['cust_price'] : $comp_rate['cust_price'] ?>" data-id="<?= $rate['id'] ?>" data-line="<?= $line['id'] ?>" data-table="rate_card_estimate_scope_lines"></td>
							<td data-title="Final Retail Price"><input type="text" name="retail_rate" class="form-control <?= $rate['retail_rate'] != 0 ? 'blue-border' : '' ?>" value="<?= $rate['retail_rate'] != 0 ? $rate['retail_rate'] : $comp_rate['retail_rate'] ?>" data-id="<?= $rate['id'] ?>" data-line="<?= $line['id'] ?>" data-table="rate_card_estimate_scope_lines"></td>
							<td data-title="Function">
								<span class="popover-examples" style="margin:0 2px;"><a data-toggle="tooltip" data-placement="top" title="View the breakdown for this line item."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                                <a href="" class="breakdown active" <?= $line['src_table'] == 'miscellaneous' ? '' : 'style="display: none;"' ?> data-rate-id="<?= $line['id'] ?>" onclick="toggleBreakdown(this); return false;"><small>+ BREAKDOWN</small></a>
								<img src="../img/remove.png" style="height: 1em;" onclick="remove_line(this);" data-table="estimate_template_lines" data-id="<?= $line['id'] ?>" name="deleted">
								<img src="../img/icons/ROOK-add-icon.png" style="height: 1em;" onclick="add_line(this);">
								</td>
						</tr>
						<tr data-rate-id="<?= $rate['id'] ?>" style="display:none;">
							<td colspan="10" class="no-gap-pad">
								<table class="table table-bordered no-gap-pad">
									<tr class="hidden-sm hidden-xs">
										<th>Tile</th>
										<th>Description</th>
										<th>Quantity</th>
										<th>UOM</th>
										<th>Cost</th>
										<th>Total</th>
										<th></th>
									</tr>
									<?php $breakdown = mysqli_query($dbc, "SELECT * FROM `rate_card_breakdown` WHERE `rate_card_type`='scope' AND `rate_card_id`='".$rate['id']."'");
									$br_row = mysqli_fetch_assoc($breakdown);
									do { ?>
										<tr>
											<input type="hidden" name="rate_card_id" value="<?= $br_row['rate_card_id'] ?>" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown">
											<input type="hidden" name="deleted" value="<?= $br_row['deleted'] ?>" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown">
											<td data-title="Tile"><select name="src_table" class="chosen-select-deselect form-control" data-id="<?= $br_row['id'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown">
													<option></option>
													<?php foreach($tiles as $label => $tile_name) { ?>
														<option <?= $br_row['src_table'] == $tile_name ? 'selected' : '' ?> value="<?= $tile_name ?>"><?= $label ?></option>
													<?php } ?>
												</select></td>
											<td data-title="Description">
												<input type="text" class="form-control" name="description" value="<?= $br_row['description'] ?>" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown" style="<?= $br_row['src_table'] == 'miscellaneous' ? '' : 'display: none;' ?>">
												<div class="select_div" <?= $br_row['src_table'] == 'miscellaneous' ? 'style="display: none;"' : '' ?>><select name="src_id" class="chosen-select-deselect form-control" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown">
													<option></option>
													<?php foreach($src_options as $option) { ?>
														<option <?= $option['id'] == $br_row['src_id'] && strtolower($option['tile_name']) == strtolower($br_row['src_table']) ? 'selected' : (strtolower($option['tile_name']) == strtolower($br_row['src_table']) ? '' : 'style="display:none;"') ?> value="<?= $option['id'] ?>" data-tile-name="<?= $option['tile_name'] ?>"><?= $option['label'] ?></option>
													<?php } ?>
												</select></div></td>
											<td data-title="Quantity"><input type="number" min=0 step="1" class="form-control" name="quantity" value="<?= $br_row['quantity'] ?>" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown"></td>
											<td data-title="UOM"><input type="text" class="form-control" name="uom" value="<?= $br_row['uom'] ?>" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown"></td>
											<td data-title="Cost"><input type="number" min=0 step="0.01" class="form-control" name="cost" value="<?= $br_row['cost'] ?>" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown"></td>
											<td data-title="Total"><input type="number" min=0 step="0.01" class="form-control" name="total" value="<?= $br_row['total'] ?>" data-id="<?= $br_row['rcbid'] ?>" data-rate="<?= $rate['id'] ?>" data-table="rate_card_breakdown"></td>
											<td data-title="Function">
												<img class="inline-img" src="../img/icons/ROOK-add-icon.png" onclick="addBreakdown(this);">
												<img class="inline-img" src="../img/remove.png" onclick="remBreakdown(this);">
												</td>
										</tr>
									<?php } while($br_row = mysqli_fetch_assoc($breakdown)); ?>
								</table>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
		<?php } ?>
		<div>
            <span class="popover-examples" style="margin:0 2px;"><a data-toggle="tooltip" data-placement="top" title="Add another Rate Card heading."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <a href="" class="active" onclick="add_heading(); return false;"><img src="../img/icons/ROOK-add-icon.png" style="height: 1em;"> ADD A HEADING</a>
        </div>
	</div>
<?php } else { ?>
	<span class="inline"><select name="template_id" data-table="rate_card_estimate_scopes" class="chosen-select-deselect">
		<option></option>
		<?php $templates = mysqli_query($dbc, "SELECT `id`, `template_name` FROM `estimate_templates` WHERE `deleted`=0 ORDER BY `template_name`");
		while($template = mysqli_fetch_array($templates)) { ?>
			<option value="<?= $template['id'] ?>"><?= $template['template_name'] ?></option>
		<?php } ?>
	</select></span>: New Rate Card</h3>
<?php } ?>
