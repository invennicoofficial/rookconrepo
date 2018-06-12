<?php $return_count = $dbc->query("SELECT COUNT(*) `rows` FROM `ticket_attached` WHERE `ticketid`='$ticketid' AND `deleted`=0 AND `src_table`='inventory_return'")->fetch_assoc()['rows'];
$general_list = $dbc->query("SELECT `id`, `piece_type`, `piece_num` FROM `ticket_attached` WHERE `src_table`='inventory_general' AND `deleted`=0 AND `ticketid`='$ticketid'");
$general_item = $general_list->fetch_assoc(); ?>
<h3><?= (!empty($renamed_accordion) ? $renamed_accordion.' ' : 'Return Information ') ?>
	<?php if(strpos($value_config,',Inventory Return Same,') !== FALSE) { ?>
		<label class="form-checkbox smaller pull-right">
		<?php if($access_all > 0) { ?>
			<input type="checkbox" <?= $return_count > 1 ? '' : 'checked' ?> onchange="if(this.checked) { $('.multi_return').hide(); } else {  $('.multi_return').show(); }"> Same Information for All Cargo</label>
		<?php } else if(!($return_count > 1)) { ?>
			Same Information for All Cargo
		<?php } ?>
	<?php } ?></h3>
<?php $first_item = true;
do {
	if(!($general_item['piece_num'] > 0)) {
		$general_item['piece_num'] = 1;
	}
	$include_blanks = true;
	for($i = 0; $i < $general_item['piece_num']; $i++) {
		echo '<h4 class="multi_return" '.(strpos($value_config,',Inventory Return Same,') !== FALSE && $return_count > 1 ? '' : 'style="display:none;"').'>Return - '.$general_item['piece_type']." #".($i+1)."</h4>";
		$return_inv_list = mysqli_query($dbc, "SELECT `ticket_attached`.`id`, `ticket_attached`.`item_id`, `ticket_attached`.`rate`, `ticket_attached`.`qty`, `ticket_attached`.`received`, `ticket_attached`.`used`, `ticket_attached`.`description`, `ticket_attached`.`status`, `ticket_attached`.`po_line`, `ticket_attached`.`line_id`, `ticket_attached`.`piece_num`, `ticket_attached`.`piece_type`, `ticket_attached`.`used`, `ticket_attached`.`weight`, `ticket_attached`.`weight_units`, `ticket_attached`.`dimensions`, `ticket_attached`.`dimension_units`, `ticket_attached`.`discrepancy`, `ticket_attached`.`backorder`, `ticket_attached`.`position`, `ticket_attached`.`notes`, `ticket_attached`.`contact_info`, `inventory`.`category`, `inventory`.`sub_category` FROM `ticket_attached` LEFT JOIN `inventory` ON `ticket_attached`.`item_id`=`inventory`.`inventoryid` WHERE `ticket_attached`.`src_table`='inventory_return' AND `ticket_attached`.`ticketid`='$ticketid' AND `ticket_attached`.`ticketid` > 0 AND `ticket_attached`.`deleted`=0 AND `ticket_attached`.`piece_num`=".$i." AND `ticket_attached`.`line_id` IN ('".$general_item['id']."'".($include_blanks ? ",'0'" : '').")");
		$return_inventory = $return_inv_list->fetch_assoc();
		do { ?>
			<div class="multi-block <?= $first_item ? '' : 'multi_return' ?>" <?= $first_item || (strpos($value_config,',Inventory Return Same,') !== FALSE && $return_count > 1) ? '' : 'style="display:none;"' ?>>
				<?php if($access_all > 0) { ?>
					<?php foreach($field_sort_order as $field_sort_field) { ?>
						<?php if(strpos($value_config,',Inventory Return Item,') !== FALSE && $field_sort_field == 'Inventory Return Item') { ?>
							<div class="form-group">
								<label class="control-label col-sm-4">Is this a Return Item?</label>
								<div class="col-sm-8">
									<label class="form-checkbox"><input type="radio" name="position" <?= $return_inventory['position'] == 'Y' ? 'checked' : '' ?> data-table="ticket_attached" data-id="<?= $return_inventory['id'] ?>" data-id-field="id" data-type="inventory_return" data-type-field="src_table" class="form-control" value="Y"> Yes</label>
									<label class="form-checkbox"><input type="radio" name="position" <?= $return_inventory['position'] == 'Y' ? '' : 'checked' ?> data-table="ticket_attached" data-id="<?= $return_inventory['id'] ?>" data-id-field="id" data-type="inventory_return" data-type-field="src_table" class="form-control" value="N"> No</label>
								</div>
							</div>
							<div class="clearfix"></div>
						<?php } ?>
						<?php if(strpos($value_config,',Inventory Return Details,') !== FALSE && $field_sort_field == 'Inventory Return Details') { ?>
							<div class="form-group">
								<label class="control-label col-sm-4">Return Information:</label>
								<div class="col-sm-12">
									<textarea name="notes" data-table="ticket_attached" data-id="<?= $return_inventory['id'] ?>" data-id-field="id" data-type="inventory_return" data-type-field="src_table"><?= html_entity_decode($return_inventory['notes']) ?></textarea>
								</div>
							</div>
							<div class="clearfix"></div>
						<?php } ?>
						<?php if(strpos($value_config,',Inventory Return ATA,') !== FALSE && $field_sort_field == 'Inventory Return ATA') { ?>
							<div class="form-group">
								<label class="control-label col-sm-4">ATA Carnet:</label>
								<div class="col-sm-8">
									<input type="text" name="description" data-table="ticket_attached" data-id="<?= $return_inventory['id'] ?>" data-id-field="id" data-type="inventory_return" data-type-field="src_table" class="form-control" value="<?= $return_inventory['description'] ?>" placeholder="Temporary import and export...">
								</div>
							</div>
							<div class="clearfix"></div>
						<?php } ?>
					<?php } ?>
				<?php } else { ?>
					<?php foreach($field_sort_order as $field_sort_field) { ?>
						<?php if(strpos($value_config,',Inventory Return Item,') !== FALSE && $field_sort_field == 'Inventory Return Item') { ?>
							<div class="form-group">
								<label class="control-label col-sm-4">Is this a Return Item?</label>
								<div class="col-sm-8">
									<?= $return_inventory['position'] == 'Y' ? 'Yes' : 'No' ?>
								</div>
							</div>
							<div class="clearfix"></div>
							<?php $pdf_contents[] = ['Is this a Return Item?', $return_inventory['position'] == 'Y' ? 'Yes' : 'No']; ?>
						<?php } ?>
						<?php if(strpos($value_config,',Inventory Return Details,') !== FALSE && $field_sort_field == 'Inventory Return Details') { ?>
							<div class="form-group">
								<label class="control-label col-sm-4">Return Information:</label>
								<div class="col-sm-8">
									<?= html_entity_decode($return_inventory['notes']) ?>
								</div>
							</div>
							<div class="clearfix"></div>
							<?php $pdf_contents[] = ['Return Information', html_entity_decode($return_inventory['notes'])]; ?>
						<?php } ?>
						<?php if(strpos($value_config,',Inventory Return ATA,') !== FALSE && $field_sort_field == 'Inventory Return ATA') { ?>
							<div class="form-group">
								<label class="control-label col-sm-4">ATA Carnet:</label>
								<div class="col-sm-8">
									<?= $return_inventory['description'] ?>
								</div>
							</div>
							<div class="clearfix"></div>
							<?php $pdf_contents[] = ['ATA Carnet', $return_inventory['description']]; ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } while($return_inventory = $return_inv_list->fetch_assoc());
		$include_blanks = false;
		$first_item = false;
	}
} while($general_item = $general_list->fetch_assoc()); ?>