<div class="form-group">
    <label for="fax_number"	class="col-sm-4	control-label">Lead Status:</label>
    <div class="col-sm-8">
		<!--
		<select data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
			<option value=""></option>
			<option <?php //if ($status == "Pending") { echo " selected"; } ?> value="Pending">Pending</option>
			<option <?php //if ($status == "Prospect") { echo " selected"; } ?> value="Prospect">Prospect</option>
			<option <?php //if ($status == "Qualification") { echo " selected"; } ?> value="Qualification">Qualification</option>
			<option <?php //if ($status == "Needs Analysis") { echo " selected"; } ?> value="Needs Analysis">Needs Analysis</option>
			<option <?php //if ($status == "Propose Quote") { echo " selected"; } ?> value="Propose Quote">Propose Quote</option>
			<option <?php //if ($status == "Negotiations") { echo " selected"; } ?> value="Negotiations">Negotiations</option>
			<option <?php //if ($status == "Won") { echo " selected"; } ?> value="Won">Won</option>
			<option <?php //if ($status == "Lost") { echo " selected"; } ?> value="Lost">Lost</option>
			<option <?php //if ($status == "Abandoned") { echo " selected"; } ?> value="Abandoned">Abandoned</option>
			<option <?php //if ($status == "Future Review") { echo " selected"; } ?> value="Future Review">Future Review</option>
			<?php
			/*
			$tabs = get_config($dbc, 'sales_lead_status');
			$each_tab = explode(',', $tabs);
			foreach ($each_tab as $cat_tab) {
				if ($status == $cat_tab) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
			}
			*/
			?>
        </select>
		-->
		
		<select data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
			<option value=""></option>
			<?php
				$tabs		= get_config ( $dbc, 'sales_lead_status' );
				$each_tab	= explode ( ',', $tabs );
				foreach ( $each_tab as $cat_tab ) {
					$selected = ( $status == $cat_tab ) ? 'selected="selected"' : ''; echo $each_tab . $cat_tab;
					echo '<option ' . $selected . ' value="' . $cat_tab . '">' . $cat_tab . '</option>';
				}
			?>
			<option <?php echo ($status == 'Customers' ? 'selected ' : ''); ?>value="Customers">Customer</option>
		</select>
    </div>
</div>

