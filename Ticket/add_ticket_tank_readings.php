<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Tank Readings</h3>') ?>
<?php $reading_list = mysqli_query($dbc, "SELECT `ticket_attached`.* FROM `ticket_attached` WHERE `ticket_attached`.`src_table`='tank_readings' AND `ticket_attached`.`ticketid`='$ticketid' AND `ticket_attached`.`ticketid` > 0 AND `ticket_attached`.`deleted`=0".$query_daily);
$readings = mysqli_fetch_assoc($reading_list);
do {
	if($access_all > 0) { ?>
		<div class="multi-block">
			<?php foreach($field_sort_order as $field_sort_field) { ?>
				<?php if(strpos($value_config,',Tank Readings Tank #,') !== FALSE && $field_sort_field == 'Tank Readings Tank #') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Tank #:</label>
						<div class="col-sm-8">
							<input type="text" name="position" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="tank_readings" data-type-field="src_table" class="form-control" value="<?= $readings['position'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Opening,') !== FALSE && $field_sort_field == 'Tank Readings Opening') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Opening:</label>
						<div class="col-sm-8">
							<input type="text" name="qty" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="tank_readings" data-type-field="src_table" class="form-control" value="<?= $readings['qty'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Closing,') !== FALSE && $field_sort_field == 'Tank Readings Closing') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Closing:</label>
						<div class="col-sm-8">
							<input type="text" name="received" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="tank_readings" data-type-field="src_table" class="form-control" value="<?= $readings['received'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Watercut,') !== FALSE && $field_sort_field == 'Tank Readings Watercut') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Watercut (m3):</label>
						<div class="col-sm-8">
							<input type="text" name="backorder" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="tank_readings" data-type-field="src_table" class="form-control" value="<?= $readings['backorder'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Watercut,') !== FALSE && $field_sort_field == 'Tank Readings Watercut') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Oil (m3):</label>
						<div class="col-sm-8">
							<input type="text" name="discrepancy" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="tank_readings" data-type-field="src_table" class="form-control" value="<?= $readings['discrepancy'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php } else { ?>
		<div class="multi-block">
			<?php foreach($field_sort_order as $field_sort_field) { ?>
				<?php if(strpos($value_config,',Tank Readings Tank #,') !== FALSE && $field_sort_field == 'Tank Readings Tank #') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Tank #:</label>
						<div class="col-sm-8">
							<?= $readings['position'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Tank #', $readings['position']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Opening,') !== FALSE && $field_sort_field == 'Tank Readings Opening') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Opening:</label>
						<div class="col-sm-8">
							<?= $readings['qty'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Opening', $readings['qty']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Closing,') !== FALSE && $field_sort_field == 'Tank Readings Closing') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Closing:</label>
						<div class="col-sm-8">
							<?= $readings['received'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Closing', $readings['received']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Watercut,') !== FALSE && $field_sort_field == 'Tank Readings Watercut') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Watercut (m3):</label>
						<div class="col-sm-8">
							<?= $readings['backorder'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Watercut (m3)', $readings['backorder']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Tank Readings Oil,') !== FALSE && $field_sort_field == 'Tank Readings Oil') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Oil (m3):</label>
						<div class="col-sm-8">
							<?= $readings['discrepancy'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Oil (m3)', $readings['discrepancy']]; ?>
				<?php } ?>
			<?php } ?>
		</div>
	<?php }
} while($readings = mysqli_fetch_assoc($reading_list)); ?>