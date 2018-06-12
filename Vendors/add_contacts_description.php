		    <?php if (strpos($value_config, ','."BIO".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">BIO:</label>
				<div class="col-sm-8">
				<textarea name="bio" rows="5" cols="50" <?php echo (strpos($edit_config, ','."BIO".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $bio; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."BIO".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($bio); ?></div>
				</div>
				</div>
			<?php } ?>

            <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
				<div class="col-sm-8">
				<textarea name="quote_description" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Quote Description".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $quote_description; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Quote Description".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($quote_description); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Description:</label>
				<div class="col-sm-8">
				<textarea name="description" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Description".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $description; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Description".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($description); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Property Information".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Property Information:</label>
				<div class="col-sm-8">
				<textarea name="property_information" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Property Information".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $property_information; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Property Information".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($property_information); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."General Comments".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">General Comments:</label>
				<div class="col-sm-8">
				<textarea name="general_comments" rows="5" cols="50" <?php echo (strpos($edit_config, ','."General Comments".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $general_comments; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."General Comments".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($general_comments); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Comments".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Comments:</label>
				<div class="col-sm-8">
				<textarea name="comments" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Comments".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $comments; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Comments".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($comments); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Notes".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="first_name[]" class="col-sm-4 control-label">Notes:</label>
				<div class="col-sm-8">
				<textarea name="notes" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Notes".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $notes; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Notes".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($notes); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details Diagnosis".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_diagnosis" class="col-sm-4 control-label">Diagnosis:</label>
				<div class="col-sm-8">
				<textarea name="medical_details_diagnosis" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Medical Details Diagnosis".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $medical_details_diagnosis; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Medical Details Diagnosis".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($medical_details_diagnosis); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details Allergies".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_allergies" class="col-sm-4 control-label">Allergies:</label>
				<div class="col-sm-8">
				<textarea name="medical_details_allergies" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Medical Details Allergies".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $medical_details_allergies; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Medical Details Allergies".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($medical_details_allergies); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details Equipment".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_equipment" class="col-sm-4 control-label">Equipment:</label>
				<div class="col-sm-8">
				<textarea name="medical_details_equipment" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Medical Details Equipment".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $medical_details_equipment; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Medical Details Equipment".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($medical_details_equipment); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details First Aid/CPR".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medical_details_first_aid_cpr" class="col-sm-4 control-label">First Aid/CPR:</label>
				<div class="col-sm-8">
				<textarea name="medical_details_first_aid_cpr" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Medical Details First Aid/CPR".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $medical_details_first_aid_cpr; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Medical Details First Aid/CPR".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($medical_details_first_aid_cpr); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medical Details Seizure".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="seizure_protocol_details" class="col-sm-4 control-label">Seizure Details:</label>
				<div class="col-sm-8">
				<textarea <?php echo (strpos($edit_config, ','."Medical Details Seizure".',') === false ? 'readonly ' : ''); ?>name="seizure_protocol_details" rows="5" cols="50" class="form-control"><?php echo $seizure_protocol_details; ?></textarea>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medications Daily Log Notes".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medications_daily_log_notes" class="col-sm-4 control-label">Daily Log Notes:</label>
				<div class="col-sm-8">
				<textarea name="medications_daily_log_notes" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Medications Daily Log Notes".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $medications_daily_log_notes; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Medications Daily Log Notes".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($medications_daily_log_notes); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Medications Management Comments".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="medications_management_comments" class="col-sm-4 control-label">Management Comments:</label>
				<div class="col-sm-8">
				<textarea name="medications_management_comments" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Medications Management Comments".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $medications_management_comments; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Medications Management Comments".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($medications_management_comments); ?></div>
				</div>
				</div>
			<?php } ?>


			<?php if (strpos($value_config, ','."Seizure Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="seizure_protocol_details" class="col-sm-4 control-label">Seizure Protocol Details:</label>
				<div class="col-sm-8">
				<textarea name="seizure_protocol_details" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Seizure Protocol Details".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $seizure_protocol_details; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Seizure Protocol Details".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($seizure_protocol_details); ?></div>
				</div>
				</div>
			<?php } ?>	

			<?php if (strpos($value_config, ','."Slip Fall Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="slip_fall_protocol_details" class="col-sm-4 control-label">Slip Fall Protocol Details:</label>
				<div class="col-sm-8">
				<textarea name="slip_fall_protocol_details" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Slip Fall Protocol Details".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $slip_fall_protocol_details; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Slip Fall Protocol Details".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($slip_fall_protocol_details); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Transfer Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="transfer_protocol_details" class="col-sm-4 control-label">Transfer Protocol Details:</label>
				<div class="col-sm-8">
				<textarea name="transfer_protocol_details" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Transfer Protocol Details".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $transfer_protocol_details; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Transfer Protocol Details".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($transfer_protocol_details); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Toileting Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="toileting_protocol_details" class="col-sm-4 control-label">Toileting Protocol Details:</label>
				<div class="col-sm-8">
				<textarea name="toileting_protocol_details" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Toileting Protocol Details".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $toileting_protocol_details; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Toileting Protocol Details".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($toileting_protocol_details); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Bathing Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="bathing_protocol_details" class="col-sm-4 control-label">Bathing Protocol Details:</label>
				<div class="col-sm-8">
				<textarea name="bathing_protocol_details" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Bathing Protocol Details".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $bathing_protocol_details; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Bathing Protocol Details".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($bathing_protocol_details); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."G-Tube Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="gtube_protocol_details" class="col-sm-4 control-label">G-Tube Protocol Details:</label>
				<div class="col-sm-8">
				<textarea name="gtube_protocol_details" rows="5" cols="50" <?php echo (strpos($edit_config, ','."G-Tube Protocol Details".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $gtube_protocol_details; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."G-Tube Protocol Details".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($gtube_protocol_details); ?></div>
				</div>
				</div>
			<?php } ?>

			<?php if (strpos($value_config, ','."Oxygen Protocol Details".',') !== FALSE) { ?>
				<div class="form-group">
				<label for="oxygen_protocol_details" class="col-sm-4 control-label">Oxygen Protocol Details:</label>
				<div class="col-sm-8">
				<textarea name="oxygen_protocol_details" rows="5" cols="50" <?php echo (strpos($edit_config, ','."Oxygen Protocol Details".',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $oxygen_protocol_details; ?></textarea>
				<div <?php echo (strpos($edit_config, ','."Oxygen Protocol Details".',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($oxygen_protocol_details); ?></div>
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
				<textarea name="$field" rows="5" cols="50" <?php echo (strpos($edit_config, ','.$title.',') === false ? 'class="form-control noMceEditor" readonly style="display:none;"' : 'class="form-control"'); ?>><?php echo $$field; ?></textarea>
				<div <?php echo (strpos($edit_config, ','.$title.',') === false ? 'class="col-sm-12"' : 'style="display:none;"'); ?>><?php echo html_entity_decode($$field); ?></div>
				</div>
				</div>
			<?php } ?>

<?php } ?>			








