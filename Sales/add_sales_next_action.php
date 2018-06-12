<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Next Action:</label>
    <div class="col-sm-8">
        <select data-placeholder="Choose a Next Action..." name="next_action" class="chosen-select-deselect form-control" width="380">
			<option value=""></option>
			<!--
			<option <?php //if ($next_action == "Email") { echo " selected"; } ?> value="Email">Email</option>
			<option <?php //if ($next_action == "Follow Up") { echo " selected"; } ?> value="Follow Up">Follow Up</option>
			<option <?php //if ($next_action == "Phone Call") { echo " selected"; } ?> value="Phone Call">Phone Call</option>
			<option <?php //if ($next_action == "Initial Meeting") { echo " selected"; } ?> value="Initial Meeting">Initial Meeting</option>
			<option <?php //if ($next_action == "Meeting") { echo " selected"; } ?> value="Meeting">Meeting</option>
			<option <?php //if ($next_action == "Presentation") { echo " selected"; } ?> value="Presentation">Presentation</option>
			<option <?php //if ($next_action == "Estimate") { echo " selected"; } ?> value="Estimate">Estimate</option>
			<option <?php //if ($next_action == "Quote Sent") { echo " selected"; } ?> value="Quote Sent">Quote Sent</option>
			<option <?php //if ($next_action == "Closing Meeting") { echo " selected"; } ?> value="Closing Meeting">Closing Meeting</option>
			<option <?php //if ($next_action == "Waiting") { echo " selected"; } ?> value="Waiting">Waiting</option>
			-->
			<?php
				$tabs		= get_config($dbc, 'sales_next_action');
				$each_tab	= explode(',', $tabs);
				
				foreach ( $each_tab as $cat_tab ) {
					$selected = ( $next_action == $cat_tab ) ? 'selected="selected"' : '';
					echo '<option ' . $selected . ' value="' . $cat_tab . '">' . $cat_tab . '</option>';
				}
			?>
        </select>
    </div>
</div>

  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">New Reminder:</label>
    <div class="col-sm-8">
      <input name="new_reminder" value="<?php echo $new_reminder; ?>" type="text" class="datepicker">
    </div>
  </div>