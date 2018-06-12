<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Monitor Readings</h3>') ?>
<?php $reading_list = mysqli_query($dbc, "SELECT `ticket_attached`.* FROM `ticket_attached` WHERE `ticket_attached`.`src_table`='readings' AND `ticket_attached`.`ticketid`='$ticketid' AND `ticket_attached`.`ticketid` > 0 AND `ticket_attached`.`deleted`=0".$query_daily);
$readings = mysqli_fetch_assoc($reading_list);
do {
	if($access_all > 0) { ?>
		<div class="multi-block">
			<?php foreach($field_sort_order as $field_sort_field) { ?>
				<?php if(strpos($value_config,',Readings CO,') !== FALSE && $field_sort_field == 'Readings CO') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">CO Level:</label>
						<div class="col-sm-8">
							<input type="text" name="qty" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="readings" data-type-field="src_table" class="form-control" value="<?= $readings['qty'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Readings O2,') !== FALSE && $field_sort_field == 'Readings O2') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">O2 Level:</label>
						<div class="col-sm-8">
							<input type="text" name="received" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="readings" data-type-field="src_table" class="form-control" value="<?= $readings['received'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Readings LEL,') !== FALSE && $field_sort_field == 'Readings LEL') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">LEL Level:</label>
						<div class="col-sm-8">
							<input type="text" name="backorder" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="readings" data-type-field="src_table" class="form-control" value="<?= $readings['backorder'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Readings H2S,') !== FALSE && $field_sort_field == 'Readings H2S') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">H2S Level:</label>
						<div class="col-sm-8">
							<input type="text" name="discrepancy" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="readings" data-type-field="src_table" class="form-control" value="<?= $readings['discrepancy'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Readings Bump,') !== FALSE && $field_sort_field == 'Readings Bump') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Bump Test:</label>
						<div class="col-sm-8">
							<input type="text" name="rate" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="readings" data-type-field="src_table" class="form-control" value="<?= $readings['rate'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Readings Arrival,') !== FALSE && $field_sort_field == 'Readings Arrival') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Arrival Time:</label>
						<div class="col-sm-8">
							<input type="text" name="checked_in" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="readings" data-type-field="src_table" class="form-control datetimepicker" value="<?= $readings['checked_in'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
				<?php if(strpos($value_config,',Readings Departure,') !== FALSE && $field_sort_field == 'Readings Departure') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Departure Time:</label>
						<div class="col-sm-8">
							<input type="text" name="checked_out" data-table="ticket_attached" data-id="<?= $readings['id'] ?>" data-id-field="id" data-type="readings" data-type-field="src_table" class="form-control datetimepicker" value="<?= $readings['checked_out'] ?>">
						</div>
					</div>
					<div class="clearfix"></div>
				<?php } ?>
			<?php } ?>
		</div>
	<?php } else { ?>
		<div class="multi-block">
			<?php foreach($field_sort_order as $field_sort_field) { ?>
				<?php if(strpos($value_config,',Readings CO,') !== FALSE && $field_sort_field == 'Readings CO') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">CO Level:</label>
						<div class="col-sm-8">
							<?= $readings['qty'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['CO Level', $readings['qty']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Readings O2,') !== FALSE && $field_sort_field == 'Readings O2') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">O2 Level:</label>
						<div class="col-sm-8">
							<?= $readings['received'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['O2 Level', $readings['received']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Readings LEL,') !== FALSE && $field_sort_field == 'Readings LEL') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">LEL Level:</label>
						<div class="col-sm-8">
							<?= $readings['backorder'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['LEL Level', $readings['backorder']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Readings H2S,') !== FALSE && $field_sort_field == 'Readings H2S') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">H2S Level:</label>
						<div class="col-sm-8">
							<?= $readings['discrepancy'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['H2S Level', $readings['discrepancy']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Readings Bump,') !== FALSE && $field_sort_field == 'Readings Bump') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Bump Test:</label>
						<div class="col-sm-8">
							<?= $readings['rate'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Bump Test', $readings['rate']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Readings Arrival,') !== FALSE && $field_sort_field == 'Readings Arrival') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Arrival Time:</label>
						<div class="col-sm-8">
							<?= $readings['checked_in'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Arrival Time', $readings['checked_in']]; ?>
				<?php } ?>
				<?php if(strpos($value_config,',Readings Departure,') !== FALSE && $field_sort_field == 'Readings Departure') { ?>
					<div class="form-group">
						<label class="control-label col-sm-4">Departure Time:</label>
						<div class="col-sm-8">
							<?= $readings['checked_out'] ?>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php $pdf_contents[] = ['Departure Time', $readings['checked_out']]; ?>
				<?php } ?>
			<?php } ?>
		</div>
	<?php }
} while($readings = mysqli_fetch_assoc($reading_list)); ?>