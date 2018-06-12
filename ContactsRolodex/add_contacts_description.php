		    <?php if (strpos($value_config, ','."BIO".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">BIO:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."BIO".',') === false ? 'readonly ' : ''); ?>name="bio" rows="5" cols="50" class="form-control"><?php echo $bio; ?></textarea>
				</div>
				</div>
			<?php } ?>

            <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Quote Description".',') === false ? 'readonly ' : ''); ?>name="quote_description" rows="5" cols="50" class="form-control"><?php echo $quote_description; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Description:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Description".',') === false ? 'readonly ' : ''); ?>name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Property Information".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Property Information:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Property Information".',') === false ? 'readonly ' : ''); ?>name="property_information" rows="5" cols="50" class="form-control"><?php echo $property_information; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."General Comments".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">General Comments:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."General Comments".',') === false ? 'readonly ' : ''); ?>name="general_comments" rows="5" cols="50" class="form-control"><?php echo $general_comments; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Comments".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Comments:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Comments".',') === false ? 'readonly ' : ''); ?>name="comments" rows="5" cols="50" class="form-control"><?php echo $comments; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Notes".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Notes:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Notes".',') === false ? 'readonly ' : ''); ?>name="notes" rows="5" cols="50" class="form-control"><?php echo $notes; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details Diagnosis".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_diagnosis" class="col-sm-4 control-label">Diagnosis:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Medical Details Diagnosis".',') === false ? 'readonly ' : ''); ?>name="medical_details_diagnosis" rows="5" cols="50" class="form-control"><?php echo $medical_details_diagnosis; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details Allergies".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_allergies" class="col-sm-4 control-label">Allergies:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Medical Details Allergies".',') === false ? 'readonly ' : ''); ?>name="medical_details_allergies" rows="5" cols="50" class="form-control"><?php echo $medical_details_allergies; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details Equipment".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_equipment" class="col-sm-4 control-label">Equipment:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Medical Details Equipment".',') === false ? 'readonly ' : ''); ?>name="medical_details_equipment" rows="5" cols="50" class="form-control"><?php echo $medical_details_equipment; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details First Aid/CPR".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_first_aid_cpr" class="col-sm-4 control-label">First Aid/CPR:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Medical Details First Aid/CPR".',') === false ? 'readonly ' : ''); ?>name="medical_details_first_aid_cpr" rows="5" cols="50" class="form-control"><?php echo $medical_details_first_aid_cpr; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medications Daily Log Notes".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medications_daily_log_notes" class="col-sm-4 control-label">Daily Log Notes:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Medications Daily Log Notes".',') === false ? 'readonly ' : ''); ?>name="medications_daily_log_notes" rows="5" cols="50" class="form-control"><?php echo $medications_daily_log_notes; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medications Management Comments".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medications_management_comments" class="col-sm-4 control-label">Management Comments:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Medications Management Comments".',') === false ? 'readonly ' : ''); ?>name="medications_management_comments" rows="5" cols="50" class="form-control"><?php echo $medications_management_comments; ?></textarea>
				</div>
				</div>
			<?php } ?>


			<?php if (strpos($value_config, ','."Seizure Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="seizure_protocol_details" class="col-sm-4 control-label">Seizure Protocol Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Seizure Protocol Details".',') === false ? 'readonly ' : ''); ?>name="seizure_protocol_details" rows="5" cols="50" class="form-control"><?php echo $seizure_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>	

			<?php if (strpos($value_config, ','."Slip Fall Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="slip_fall_protocol_details" class="col-sm-4 control-label">Slip Fall Protocol Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Slip Fall Protocol Details".',') === false ? 'readonly ' : ''); ?>name="slip_fall_protocol_details" rows="5" cols="50" class="form-control"><?php echo $slip_fall_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Transfer Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="transfer_protocol_details" class="col-sm-4 control-label">Transfer Protocol Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Transfer Protocol Details".',') === false ? 'readonly ' : ''); ?>name="transfer_protocol_details" rows="5" cols="50" class="form-control"><?php echo $transfer_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Toileting Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="toileting_protocol_details" class="col-sm-4 control-label">Toileting Protocol Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Toileting Protocol Details".',') === false ? 'readonly ' : ''); ?>name="toileting_protocol_details" rows="5" cols="50" class="form-control"><?php echo $toileting_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Bathing Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="bathing_protocol_details" class="col-sm-4 control-label">Bathing Protocol Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Bathing Protocol Details".',') === false ? 'readonly ' : ''); ?>name="bathing_protocol_details" rows="5" cols="50" class="form-control"><?php echo $bathing_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."G-Tube Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="gtube_protocol_details" class="col-sm-4 control-label">G-Tube Protocol Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."G-Tube Protocol Details".',') === false ? 'readonly ' : ''); ?>name="gtube_protocol_details" rows="5" cols="50" class="form-control"><?php echo $gtube_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Oxygen Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="oxygen_protocol_details" class="col-sm-4 control-label">Oxygen Protocol Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Oxygen Protocol Details".',') === false ? 'readonly ' : ''); ?>name="oxygen_protocol_details" rows="5" cols="50" class="form-control"><?php echo $oxygen_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>

  <?php
  $html = array(
    'protocols_daily_log_notes' => 'Protocols Daily Log Notes',
    'protocols_management_comments' => 'Protocols Management Comments',

	'routines_daily_log_notes' => 'Routines Daily Log Notes',
    'routines_management_comments' => 'Routines Management Comments',

	'communication_daily_log_notes' => 'Communication Daily Log Notes',
    'communication_management_comments' => 'Communication Management Comments',

	'activities_daily_log_notes' => 'Activities Daily Log Notes',
    'activities_management_comments' => 'Activities Management Comments',

    );
  ?>
<?php foreach($html as $field => $title) { ?>

			<?php if (strpos($value_config, ','.$title.',') !== FALSE) { ?>
				<div class="form-group">
				<label for="<?php echo $field; ?>" class="col-sm-4 control-label"><?php echo $title; ?></label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','.$title.',') === false ? 'readonly ' : ''); ?>name="<?php echo $field; ?>" rows="5" cols="50" class="form-control"><?php echo $$field; ?></textarea>
				</div>
				</div>
			<?php } ?>

<?php } ?>			








