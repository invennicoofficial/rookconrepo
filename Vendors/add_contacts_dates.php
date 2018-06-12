            <?php if (strpos($value_config, ','."Contact Since".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Contact Since:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Contact Since".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="contact_since" value="<?php echo $contact_since; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Date of Last Contact".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Date of Last Contact:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Date of Last Contact".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="date_of_last_contact" value="<?php echo $date_of_last_contact; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Start Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Start Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Start Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="start_date" value="<?php echo $start_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Expiry Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Expiry Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Expiry Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="expiry_date" value="<?php echo $expiry_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Renewal Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Renewal Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Renewal Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="renewal_date" value="<?php echo $renewal_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Lease Term Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Lease Term Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Lease Term Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="lease_term_date" value="<?php echo $lease_term_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Date Contract Signed".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Date Contract Signed:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Date Contract Signed".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="date_contract_signed" value="<?php echo $date_contract_signed; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Option to Renew Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Option to Renew Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Option to Renew Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="option_to_renew_date" value="<?php echo $option_to_renew_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Rate Increase Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Rate Increase Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Rate Increase Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="rate_increase_date" value="<?php echo $rate_increase_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Insurance Expiry Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Insurance Expiry Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Insurance Expiry Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="insurance_expiry_date" value="<?php echo $insurance_expiry_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Account Expiry Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Account Expiry Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Account Expiry Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="account_expiry_date" value="<?php echo $account_expiry_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Hire Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Hire Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Hire Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="hire_date" value="<?php echo $hire_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Probation End Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Probation End Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Probation End Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="probation_end_date" value="<?php echo $probation_end_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Probation Expiry Reminder Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Probation Expiry Reminder Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Probation Expiry Reminder Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="probation_expiry_reminder_date" value="<?php echo $probation_expiry_reminder_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

			<?php if (strpos($value_config, ','."Birth Date".',') !== FALSE) { ?>
				<div class="form-group clearfix completion_date">
                <label for="first_name" class="col-sm-4 control-label text-right">Birth Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Birth Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="birth_date" value="<?php echo $birth_date; ?>" type="text"></p>
                </div>
				</div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Company Benefit Start Date".',') !== FALSE) { ?>
                <div class="form-group clearfix completion_date">
                <label for="company_benefit_start_date" class="col-sm-4 control-label text-right">Company Benefit Start Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Company Benefit Start Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="company_benefit_start_date" value="<?php echo $company_benefit_start_date; ?>" type="text"></p>
                </div>
                </div>
            <?php } ?>

            <?php if (strpos($value_config, ','."Medications Completed Date".',') !== FALSE) { ?>
                <div class="form-group clearfix">
                <label for="medications_completed_date" class="col-sm-4 control-label text-right">Completed Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Medications Completed Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="medications_completed_date" value="<?php echo $medications_completed_date; ?>" type="text">
                </div>
                </div>
            <?php } ?>
            

            <?php if (strpos($value_config, ','."Medications Management Completed Date".',') !== FALSE) { ?>
                <div class="form-group clearfix">
                <label for="medications_management_completed_date" class="col-sm-4 control-label text-right">Management Completed Date:</label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','."Medications Management Completed Date".',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="medications_management_completed_date" value="<?php echo $medications_management_completed_date; ?>" type="text">
                </div>
                </div>
            <?php } ?>


  <?php
  $html = array(
    'protocols_completed_date' => 'Protocols Completed Date',
    'protocols_management_completed_date' => 'Protocols Management Completed Date',

    'routines_completed_date' => 'Routines Completed Date',
    'routines_management_completed_date' => 'Routines Management Completed Date',

    'communication_completed_date' => 'Communication Completed Date',
    'communication_management_completed_date' => 'Communication Management Completed Date',

    'activities_completed_date' => 'Activities Completed Date',
    'activities_management_completed_date' => 'Activities Management Completed Date',

    );
  ?>

<?php foreach($html as $field => $title) { ?>

            <?php if (strpos($value_config, ','.$title.',') !== FALSE) { ?>
                <div class="form-group clearfix">
                <label for="<?php echo $field; ?>" class="col-sm-4 control-label text-right"><?php echo $title; ?></label>
                <div class="col-sm-8">
                <input <?php echo (strpos($edit_config, ','.$title.',') === false ? 'readonly class="form-control"' : 'class="form-control datepicker"'); ?> name="<?php echo $field; ?>" value="<?php echo $$field; ?>" type="text">
                </div>
                </div>
            <?php } ?>
            
<?php } ?>

