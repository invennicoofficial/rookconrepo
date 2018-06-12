<?php if($access_all > 0) { ?>
	<div class="form-group">
		<label class="control-label col-sm-4">Police/Fire/EMS:</label>
		<div class="col-sm-8">
			<input type="text" name="police_contact" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $get_ticket['police_contact'] == '' ? '911' : $get_ticket['police_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Poison Control:</label>
		<div class="col-sm-8">
			<input type="text" name="poison_contact" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $get_ticket['poison_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Non-Emergency Contact:</label>
		<div class="col-sm-8">
			<input type="text" name="non_emergency_contact" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $get_ticket['non_emergency_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Emergency Contact:</label>
		<div class="col-sm-8">
			<input type="text" name="emergency_contact" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid" value="<?= $get_ticket['emergency_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Notes:</label>
		<div class="col-sm-12">
			<textarea name="emergency_notes" data-table="tickets" data-id="<?= $ticketid ?>" data-id-field="ticketid"><?= html_entity_decode($get_ticket['emergency_notes']) ?></textarea>
		</div>
	</div>
<?php } else { ?>
	<div class="form-group">
		<label class="control-label col-sm-4">Police/Fire/EMS:</label>
		<div class="col-sm-8">
			<input type="text" readonly value="<?= $get_ticket['police_contact'] == '' ? '911' : $get_ticket['police_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Poison Control:</label>
		<div class="col-sm-8">
			<input type="text" readonly value="<?= $get_ticket['poison_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Non-Emergency Contact:</label>
		<div class="col-sm-8">
			<input type="text" readonly value="<?= $get_ticket['non_emergency_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Emergency Contact:</label>
		<div class="col-sm-8">
			<input type="text" readonly value="<?= $get_ticket['emergency_contact'] ?>" class="form-control">
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="control-label col-sm-4">Notes:</label>
		<div class="col-sm-8">
			<?= html_entity_decode($get_ticket['emergency_notes']) ?>
		</div>
	</div>
<?php } ?>