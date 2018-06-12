<?php if($field_option == 'BIO') { ?>
	<label class="col-sm-4 control-label">BIO:</label>
	<div class="col-sm-8">
		<textarea name="bio" data-field="bio" data-table="contacts_description" class="form-control"><?= $contact['bio'] ?></textarea>
	</div>
<?php } else if($field_option == 'Quote Description') { ?>
	<label class="col-sm-4 control-label">Quote Description:</label>
	<div class="col-sm-8">
		<textarea name="quote_description" data-field="quote_description" data-table="contacts_description" class="form-control"><?= $contact['quote_description'] ?></textarea>
	</div>
<?php } else if($field_option == 'Description') { ?>
	<label class="col-sm-4 control-label">Description:</label>
	<div class="col-sm-8">
		<textarea name="description" data-field="description" data-table="contacts" class="form-control"><?= $contact['description'] ?></textarea>
	</div>
<?php } else if($field_option == 'Property Information') { ?>
	<label class="col-sm-4 control-label">Property Information:</label>
	<div class="col-sm-8">
		<textarea name="property_information" data-field="property_information" data-table="contacts_description" class="form-control"><?= $contact['property_information'] ?></textarea>
	</div>
<?php } else if($field_option == 'General Comments') { ?>
	<label class="col-sm-4 control-label">General Comments:</label>
	<div class="col-sm-8">
		<textarea name="general_comments" data-field="general_comments" data-table="contacts_description" class="form-control"><?= $contact['general_comments'] ?></textarea>
	</div>
<?php } else if($field_option == 'Service Notes') { ?>
	<label class="col-sm-4 control-label">Service Notes:</label>
	<div class="col-sm-8">
		<textarea name="service_notes" data-field="service_notes" data-table="contacts_description" class="form-control"><?= $contact['service_notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Comments') { ?>
	<label class="col-sm-4 control-label">Comments:</label>
	<div class="col-sm-8">
		<textarea name="comments" data-field="comments" data-table="contacts_description" class="form-control"><?= $contact['comments'] ?></textarea>
	</div>
<?php } else if($field_option == 'Notes') { ?>
	<label class="col-sm-4 control-label">Notes:</label>
	<div class="col-sm-8">
		<textarea name="notes" data-field="notes" data-table="contacts_description" class="form-control"><?= $contact['notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Emergency Notes') { ?>
	<label class="col-sm-4 control-label">Notes:</label>
	<div class="col-sm-8">
		<textarea name="notes" data-field="emergency_notes" data-table="contacts" class="form-control"><?= $contact['emergency_notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Details Diagnosis') { ?>
	<label class="col-sm-4 control-label">Diagnosis:</label>
	<div class="col-sm-8">
		<textarea name="medical_details_diagnosis" data-field="medical_details_diagnosis" data-table="contacts_description" class="form-control"><?= $contact['medical_details_diagnosis'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Details Allergies') { ?>
	<label class="col-sm-4 control-label">Allergies:</label>
	<div class="col-sm-8">
		<textarea name="medical_details_allergies" data-field="medical_details_allergies" data-table="contacts_description" class="form-control"><?= $contact['medical_details_allergies'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Details Equipment') { ?>
	<label class="col-sm-4 control-label">Equipment:</label>
	<div class="col-sm-8">
		<textarea name="medical_details_equipment" data-field="medical_details_equipment" data-table="contacts_description" class="form-control"><?= $contact['medical_details_equipment'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Details Goals') { ?>
	<label class="col-sm-4 control-label">Goals of Care:</label>
	<div class="col-sm-8">
		<textarea name="medical_details_goals" data-field="medical_details_goals" data-table="contacts_description" class="form-control"><?= $contact['medical_details_goals'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Goals') { ?>
	<label class="col-sm-4 control-label">Health Concerns:</label>
	<div class="col-sm-8">
		<textarea name="medical_details_goal_concerns" data-field="medical_details_goal_concerns" data-table="contacts_description" class="form-control"><?= $contact['medical_details_goal_concerns'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Goals Procedures') { ?>
	<label class="col-sm-4 control-label">Support Procedures:</label>
	<div class="col-sm-8">
		<textarea name="medical_details_goal_procedure" data-field="medical_details_goal_procedure" data-table="contacts_description" class="form-control"><?= $contact['medical_details_goal_procedure'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Details First Aid/CPR') { ?>
	<label class="col-sm-4 control-label">First Aid/CPR:</label>
	<div class="col-sm-8">
		<textarea name="medical_details_first_aid_cpr" data-field="medical_details_first_aid_cpr" data-table="contacts_description" class="form-control"><?= $contact['medical_details_first_aid_cpr'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medical Details Seizure') { ?>
	<label class="col-sm-4 control-label">Seizure Details:</label>
	<div class="col-sm-8">
		<textarea name="seizure_protocol_details" data-field="seizure_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['seizure_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medications Daily Log Notes') { ?>
	<label class="col-sm-4 control-label">Daily Log Notes:</label>
	<div class="col-sm-8">
		<textarea name="medications_daily_log_notes" data-field="medications_daily_log_notes" data-table="contacts_description" class="form-control"><?= $contact['medications_daily_log_notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Medications Management Comments') { ?>
	<label class="col-sm-4 control-label">Management Comments:</label>
	<div class="col-sm-8">
		<textarea name="medications_management_comments" data-field="medications_management_comments" data-table="contacts_description" class="form-control"><?= $contact['medications_management_comments'] ?></textarea>
	</div>
<?php } else if($field_option == 'Seizure Protocol Details') { ?>
	<label class="col-sm-4 control-label">Seizure Protocol Details:</label>
	<div class="col-sm-8">
		<textarea name="seizure_protocol_details" data-field="seizure_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['seizure_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'Slip Fall Protocol Details') { ?>
	<label class="col-sm-4 control-label">Slip Fall Protocol Details:</label>
	<div class="col-sm-8">
		<textarea name="slip_fall_protocol_details" data-field="slip_fall_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['slip_fall_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'Transfer Protocol Details') { ?>
	<label class="col-sm-4 control-label">Transfer Protocol Details:</label>
	<div class="col-sm-8">
		<textarea name="transfer_protocol_details" data-field="transfer_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['transfer_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'Toileting Protocol Details') { ?>
	<label class="col-sm-4 control-label">Toileting Protocol Details:</label>
	<div class="col-sm-8">
		<textarea name="toileting_protocol_details" data-field="toileting_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['toileting_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'Bathing Protocol Details') { ?>
	<label class="col-sm-4 control-label">Bathing Protocol Details:</label>
	<div class="col-sm-8">
		<textarea name="bathing_protocol_details" data-field="bathing_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['bathing_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'G-Tube Protocol Details') { ?>
	<label class="col-sm-4 control-label">G-Tube Protocol Details:</label>
	<div class="col-sm-8">
		<textarea name="gtube_protocol_details" data-field="gtube_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['gtube_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'Food Preferences') { ?>
	<label class="col-sm-4 control-label">Food Preferences:</label>
	<div class="col-sm-8">
		<textarea name="food_preferences" data-field="food_preferences" data-table="contacts_description" class="form-control"><?= $contact['food_preferences'] ?></textarea>
	</div>
<?php } else if($field_option == 'Oxygen Protocol Details') { ?>
	<label class="col-sm-4 control-label">Oxygen Protocol Details:</label>
	<div class="col-sm-8">
		<textarea name="oxygen_protocol_details" data-field="oxygen_protocol_details" data-table="contacts_description" class="form-control"><?= $contact['oxygen_protocol_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'First Aid CPR Details') { ?>
	<label class="col-sm-4 control-label">First Aid/CPR Details:</label>
	<div class="col-sm-8">
		<textarea name="first_aid_cpr_details" data-field="first_aid_cpr_details" data-table="contacts_description" class="form-control"><?= $contact['first_aid_cpr_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'SRC Details') { ?>
	<label class="col-sm-4 control-label">SRC:</label>
	<div class="col-sm-8">
		<textarea name="src_details" data-field="src_details" data-table="contacts_description" class="form-control"><?= $contact['src_details'] ?></textarea>
	</div>
<?php } else if($field_option == 'Protocols Daily Log Notes') { ?>
	<label class="col-sm-4 control-label">Protocols Daily Log Notes:</label>
	<div class="col-sm-8">
		<textarea name="protocols_daily_log_notes" data-field="protocols_daily_log_notes" data-table="contacts_description" class="form-control"><?= $contact['protocols_daily_log_notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Protocols Management Comments') { ?>
	<label class="col-sm-4 control-label">Protocols Management Comments:</label>
	<div class="col-sm-8">
		<textarea name="protocols_management_comments" data-field="protocols_management_comments" data-table="contacts_description" class="form-control"><?= $contact['protocols_management_comments'] ?></textarea>
	</div>
<?php } else if($field_option == 'Routines Daily Log Notes') { ?>
	<label class="col-sm-4 control-label">Routines Daily Log Notes:</label>
	<div class="col-sm-8">
		<textarea name="routines_daily_log_notes" data-field="routines_daily_log_notes" data-table="contacts_description" class="form-control"><?= $contact['routines_daily_log_notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Routines Management Comments') { ?>
	<label class="col-sm-4 control-label">Routines Management Comments:</label>
	<div class="col-sm-8">
		<textarea name="routines_management_comments" data-field="routines_management_comments" data-table="contacts_description" class="form-control"><?= $contact['routines_management_comments'] ?></textarea>
	</div>
<?php } else if($field_option == 'Communication Daily Log Notes') { ?>
	<label class="col-sm-4 control-label">Communication Daily Log Notes:</label>
	<div class="col-sm-8">
		<textarea name="communication_daily_log_notes" data-field="communication_daily_log_notes" data-table="contacts_description" class="form-control"><?= $contact['communication_daily_log_notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Communication Management Comments') { ?>
	<label class="col-sm-4 control-label">Communication Management Comments:</label>
	<div class="col-sm-8">
		<textarea name="communication_management_comments" data-field="communication_management_comments" data-table="contacts_description" class="form-control"><?= $contact['communication_management_comments'] ?></textarea>
	</div>
<?php } else if($field_option == 'Activities Daily Log Notes') { ?>
	<label class="col-sm-4 control-label">Activities Daily Log Notes:</label>
	<div class="col-sm-8">
		<textarea name="activities_daily_log_notes" data-field="activities_daily_log_notes" data-table="contacts_description" class="form-control"><?= $contact['activities_daily_log_notes'] ?></textarea>
	</div>
<?php } else if($field_option == 'Activities Management Comments') { ?>
	<label class="col-sm-4 control-label">Activities Management Comments:</label>
	<div class="col-sm-8">
		<textarea name="activities_management_comments" data-field="activities_management_comments" data-table="contacts_description" class="form-control"><?= $contact['activities_management_comments'] ?></textarea>
	</div>
<?php } else if($field_option == 'Health Concerns') { ?>
	<label class="col-sm-4 control-label">Do you have any health concerns that you want to make the company aware of?</label>
	<div class="col-sm-8">
		<textarea name="health_concerns" data-field="health_concerns" data-table="contacts_medical" class="form-control"><?= html_entity_decode($contact['health_concerns']) ?></textarea>
	</div>
<?php } else if($field_option == 'Emergency Procedure') { ?>
	<label class="col-sm-4 control-label">Do you have any special emergency health procedures that you want the company to be aware of?</label>
	<div class="col-sm-8">
		<textarea name="health_emergency_procedure" data-field="health_emergency_procedure" data-table="contacts_medical" class="form-control"><?= html_entity_decode($contact['health_emergency_procedure']) ?></textarea>
	</div>
<?php } else if($field_option == 'Medications') { ?>
	<label class="col-sm-4 control-label">Are you on any medications you want to make the company aware of?</label>
	<div class="col-sm-8">
		<textarea name="health_medications" data-field="health_medications" data-table="contacts_medical" class="form-control"><?= html_entity_decode($contact['health_medications']) ?></textarea>
	</div>
<?php } else if($field_option == 'Allergies') { ?>
	<label class="col-sm-4 control-label">Do you have any allergies you wish to make the company aware of?</label>
	<div class="col-sm-8">
		<textarea name="health_allergens" data-field="health_allergens" data-table="contacts_medical" class="form-control"><?= html_entity_decode($contact['health_allergens']) ?></textarea>
	</div>
<?php } else if($field_option == 'Allergy Procedure') { ?>
	<label class="col-sm-4 control-label">Do you have any special procedures you want to make the company aware of should you require aid?</label>
	<div class="col-sm-8">
		<textarea name="health_allergens_procedure" data-field="health_allergens_procedure" data-table="contacts_medical" class="form-control"><?= html_entity_decode($contact['health_allergens_procedure']) ?></textarea>
	</div>
<?php } else if($field_option == 'Signature') { ?>
	<label class="col-sm-4 control-label">Signature</label>
	<div class="col-sm-8">
		<div class="sign_view" <?= $contact['stored_signature'] != '' ? '' : 'style="display:none;"' ?>>
			<?php if($contactid > 0 && $contact['stored_signature'] != '') {
				if(!file_exists('../Contacts/signatures')) {
					mkdir('../Contacts/signatures', 0777, true);
				}
				include_once('../phpsign/signature-to-image.php');
				$signature = sigJsonToImage(html_entity_decode($value));
				imagepng($signature, '../Contacts/signatures/contact_sign_'.$contactid.'.png'); ?>
				<img src="../Contacts/signatures/contact_sign_<?= $contactid ?>.png">
			<?php } ?>
		</div>
		<div class="sign_new" <?= $contact['stored_signature'] != '' ? 'style="display:none;"' : '' ?>>
			<?php $output_name = 'stored_signature';
			$sign_output_options = 'data-field="stored_signature" data-table="contacts_description"';
			include('../phpsign/sign_multiple.php'); ?>
		</div>
	</div>
<?php } else if($field_option == '***') { ?>
	<label class="col-sm-4 control-label">***:</label>
	<div class="col-sm-8">
		<textarea name="***" data-field="***" data-table="contacts" class="form-control"><?= html_entity_decode($contact['***']) ?></textarea>
	</div>
<?php } else if($field_option == 'Drivers Abstract') { ?>
	<label class="col-sm-4 control-label">Drivers Abstract:</label>
	<div class="col-sm-8">
		<textarea name="drivers_abstract" data-field="drivers_abstract" data-table="contacts_description" class="form-control"><?= html_entity_decode($contact['drivers_abstract']) ?></textarea>
	</div>
<?php } else if($field_option == 'Property Instructions') { ?>
	<label class="col-sm-4 control-label">Property Instructions:</label>
	<div class="col-sm-8">
		<textarea name="property_instructions" data-field="property_instructions" data-table="contacts_description" class="form-control"><?= html_entity_decode($contact['property_instructions']) ?></textarea>
	</div>
<?php }