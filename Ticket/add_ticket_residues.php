<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Residue</h3>') ?>
<?php $residue_list = mysqli_query($dbc, "SELECT `ticket_attached`.`id`, `ticket_attached`.`item_id`, `ticket_attached`.`rate`, `ticket_attached`.`qty`, `ticket_attached`.`volume`, `ticket_attached`.`description`, `ticket_attached`.`status` FROM `ticket_attached` WHERE `ticket_attached`.`description` != '' AND `ticket_attached`.`src_table`='residue' AND `ticket_attached`.`ticketid`='$ticketid' AND `ticket_attached`.`ticketid` > 0 AND `ticket_attached`.`deleted`=0".$query_daily);
$residue = mysqli_fetch_assoc($residue_list);
do {
	if($access_all > 0) { ?>
		<div class="multi-block">
			<?php foreach($field_sort_order as $field_sort_field) { ?>
				<?php if(strpos($value_config,',Residue Type,') !== FALSE && $field_sort_field == 'Residue Type') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Type:</label>
						<div class="col-sm-7 select-div">
							<select name="item_id" data-table="ticket_attached" data-id="<?= $residue['id'] ?>" data-id-field="id" data-type="residue" data-type-field="src_table" class="chosen-select-deselect"><option></option>
								<?php $residue_descriptions = mysqli_query($dbc, "SELECT `description` FROM `ticket_attached` WHERE `deleted`=0 AND `src_table`='residue' GROUP BY `description` ORDER BY `description`");
								while($residue_description = mysqli_fetch_assoc($residue_descriptions)) { ?>
									<option <?= $residue['description'] == $residue_description['description'] ? 'selected' : '' ?> value="<?= $residue_description['description'] ?>"><?= $residue_description['description'] ?></option>
								<?php } ?>
								<option value="MANUAL">Add New</option>
							</select>
						</div>
						<div class="col-sm-7 manual-div" style="display:none;">
							<input name="description" data-table="ticket_attached" data-id="<?= $residue['id'] ?>" data-id-field="id" data-type="residue" data-type-field="src_table" class="form-control" value="<?= $residue['description'] ?>">
						</div>
						<div class="col-sm-1">
							<input type="hidden" name="deleted" data-table="ticket_attached" data-id="<?= $residue['id'] ?>" data-id-field="id" data-type="residue" data-type-field="src_table" value="0">
							<img class="inline-img pull-right" onclick="addMulti(this);" src="../img/icons/ROOK-add-icon.png">
							<img class="inline-img pull-right" onclick="remMulti(this);" src="../img/remove.png">
						</div>
						<div class="clearfix"></div>
					</div>
				<?php } ?>
				<?php if(strpos($value_config,',Residue Quantity,') !== FALSE && $field_sort_field == 'Residue Quantity') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Quantity:</label>
						<div class="col-sm-8">
							<input type="number" min=0 step="0.01" name="qty" data-table="ticket_attached" data-id="<?= $residue['id'] ?>" data-id-field="id" data-type="residue" data-type-field="src_table" class="form-control" value="<?= $residue['qty'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Residue Volume,') !== FALSE && $field_sort_field == 'Residue Volume') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Volume:</label>
						<div class="col-sm-8">
							<input type="number" min=0 step="0.01" name="volume" data-table="ticket_attached" data-id="<?= $residue['id'] ?>" data-id-field="id" data-type="residue" data-type-field="src_table" class="form-control" value="<?= $residue['volume'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Residue Rate,') !== FALSE && $field_sort_field == 'Residue Rate') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Unit Price:</label>
						<div class="col-sm-8">
							<input type="number" min=0 step="0.01" name="rate" data-table="ticket_attached" data-id="<?= $residue['id'] ?>" data-id-field="id" data-type="residue" data-type-field="src_table" class="form-control" value="<?= $residue['rate'] ?>">
						</div>
						<div class="clearfix"></div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php } else if($residue['description'] != '') { ?>
		<div class="multi-block">
			<?php foreach($field_sort_order as $field_sort_field) { ?>
				<?php if(strpos($value_config,',Residue Type,') !== FALSE && $field_sort_field == 'Residue Type') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Type:</label>
						<div class="col-sm-8"><?= $residue['description'] != '' ? $residue['description'] : $residue['name'] ?></div>
					</div>
					<?php $pdf_contents[] = ['Type', $residue['description'] != '' ? $residue['description'] : $residue['name']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Residue Quantity,') !== FALSE && $field_sort_field == 'Residue Quantity') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Quantity:</label>
						<div class="col-sm-8">
							<?= $residue['qty'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Quantity', $residue['qty']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Residue Quantity,') !== FALSE && $field_sort_field == 'Residue Quantity') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Volume:</label>
						<div class="col-sm-8">
							<?= $residue['volume'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Volume', $residue['volume']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Residue Rate,') !== FALSE && $field_sort_field == 'Residue Rate') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Unit Price:</label>
						<div class="col-sm-8">
							<?= $residue['rate'] ?>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php $pdf_contents[] = ['Unit Price', $residue['rate']]; ?>
				<?php } ?>
			<?php } ?>
		</div>
	<?php }
} while($residue = mysqli_fetch_assoc($residue_list)); ?>