<?= (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Pressure</h3>') ?>
<?php if($access_all > 0) { ?>
	<?php foreach($field_sort_order as $field_sort_field) { ?>
		<?php if(strpos($value_config,',Pressure Pressure Test,') !== FALSE && $field_sort_field == 'Pressure Pressure Test') { ?>
			<div class="form-group">
				<label class="control-label col-sm-4">Pressure Test:</label>
				<div class="col-sm-8">
					<input type="text" name="pressure_test" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control" value="<?= $get_ticket['pressure_test'] ?>">
				</div>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
		<?php if(strpos($value_config,',Pressure PSV SET,') !== FALSE && $field_sort_field == 'Pressure PSV SET') { ?>
			<div class="form-group">
				<label class="control-label col-sm-4">PSV SET:</label>
				<div class="col-sm-8">
					<input type="text" name="psv_set" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control" value="<?= $get_ticket['psv_set'] ?>">
				</div>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
		<?php if(strpos($value_config,',Pressure Purge Closed,') !== FALSE && $field_sort_field == 'Pressure Purge Closed') { ?>
			<div class="form-group">
				<label class="control-label col-sm-4">Purge Closed:</label>
				<div class="col-sm-8">
					<label class="form-checkbox"><input type="checkbox" name="purge_closed" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" class="form-control" value="1" <?= $get_ticket['purge_closed'] == 1 ? 'checked' : '' ?>> Yes</label>
				</div>
			</div>
			<div class="clearfix"></div>
		<?php } ?>
	<?php } ?>
<?php } else { ?>
	<?php foreach($field_sort_order as $field_sort_field) { ?>
		<?php if(strpos($value_config,',Pressure Pressure Test,') !== FALSE && $field_sort_field == 'Pressure Pressure Test') { ?>
			<div class="form-group">
				<label class="control-label col-sm-4">Pressure Test:</label>
				<div class="col-sm-8">
					<?= $get_ticket['pressure_test'] ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php $pdf_contents[] = ['Pressure Test', $get_ticket['pressure_test']]; ?>
		<?php } ?>
		<?php if(strpos($value_config,',Pressure PSV SET,') !== FALSE && $field_sort_field == 'Pressure PSV SET') { ?>
			<div class="form-group">
				<label class="control-label col-sm-4">PSV SET:</label>
				<div class="col-sm-8">
					<?= $get_ticket['psv_set'] ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php $pdf_contents[] = ['PSV SET', $get_ticket['psv_set']]; ?>
		<?php } ?>
		<?php if(strpos($value_config,',Pressure Purge Closed,') !== FALSE && $field_sort_field == 'Pressure Purge Closed') { ?>
			<div class="form-group">
				<label class="control-label col-sm-4">Purge Closed:</label>
				<div class="col-sm-8">
					<?= $get_ticket['purge_closed'] == 1 ? 'Yes' : '' ?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php $pdf_contents[] = ['Purge Closed', $get_ticket['purge_closed'] == 1 ? 'Yes' : '']; ?>
		<?php } ?>
	<?php } ?>
<?php }