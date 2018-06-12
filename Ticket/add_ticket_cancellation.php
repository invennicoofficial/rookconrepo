<?= !$custom_accordion ? (!empty($renamed_accordion) ? '<h3>'.$renamed_accordion.'</h3>' : '<h3>Cancellation</h3>') : '' ?>
<?php foreach($field_sort_order as $field_sort_field) {
	if(strpos($value_config, ','."Cancellation Reason".',') !== FALSE && $field_sort_field == 'Cancellation Reason') { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Cancellation Reason:</label>
			<div class="col-sm-8">
				<?php if($access_any > 0) { ?>
					<select name="cancellation" class="chosen-select-deselect" data-placeholder="Select Reason" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid"><option></option>
						<?php foreach(explode('#*#',get_config($dbc, 'ticket_cancellation_reasons')) as $reason) { ?>
							<option <?= $reason == $get_ticket['cancellation'] ? 'selected' : '' ?> value="<?= $reason ?>"><?= $reason ?></option>
						<?php } ?>
					</select>
				<?php } else {
					echo $get_ticket['cancellation'];
				} ?>
			</div>
		</div>
		<?php $pdf_contents[] = ['Cancellation Reason', $get_ticket['cancellation']]; ?>
	<?php } ?>
	<?php if(strpos($value_config, ','."Cancellation Notes".',') !== FALSE && $field_sort_field == 'Cancellation Notes') {
		$comment_type = 'cancel_reason';
		include('add_view_ticket_comment.php');
	}
} ?>