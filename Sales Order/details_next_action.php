<!-- Next Action -->
<?php
$statuses      = (!empty(get_config($dbc, 'sales_order_statuses'))) ? get_config($dbc, 'sales_order_statuses') : 'Opportunity,With Client,Fulfillment';
if(strpos(','.$statuses.',','Complete') === FALSE) {
    $statuses = trim($statuses,',').',Complete';
}
$status = explode(',',$statuses)[0];
if(!empty($get_sot['status'])) {
	$status = $get_sot['status'];
}
$next_actions  = (!empty(get_config($dbc, 'sales_order_next_actions'))) ? get_config($dbc, 'sales_order_next_actions') : 'Phone Call,Email';
?>
<div class="accordion-block-details padded" id="next_action">
    <div class="accordion-block-details-heading"><h4>Next Action</h4></div>
    <div class="row">
        <div class="row set-row-height">
	        <div class="col-xs-12 col-sm-3 gap-md-left-15"><?= SALES_ORDER_NOUN ?> Status:</div>
	        <div class="col-xs-12 col-sm-7">
	        	<select data-placeholder="Select a Status..." name="status" id="status" class="chosen-select-deselect form-control">
	        		<option></option><?php
	        		foreach (explode(',',$statuses) as $so_status) {
	        			echo '<option '.($status == $so_status ? 'selected' : '').' value="'.$so_status.'">'.$so_status.'</option>';
	        		} ?>
	        	</select>
            </div>
        </div>
    </div>

	<?php if (strpos($value_config, ',Next Action,') !== FALSE) { ?>
	    <div class="row">
	        <div class="row set-row-height">
		        <div class="col-xs-12 col-sm-3 gap-md-left-15">Next Action:</div>
		        <div class="col-xs-12 col-sm-7">
		        	<select data-placeholder="Select a Status..." name="next_action" id="next_action" class="chosen-select-deselect form-control">
		        		<option></option><?php
		        		foreach (explode(',',$next_actions) as $action) {
		        			echo '<option '.($next_action == $action ? 'selected' : '').' value="'.$action.'">'.$action.'</option>';
		        		} ?>
		        	</select>
	            </div>
	        </div>
	    </div>
    <?php } ?>
	<?php if (strpos($value_config, ',Next Action Follow Up Date,') !== FALSE) { ?>
	    <div class="row">
	        <div class="row set-row-height">
		        <div class="col-xs-12 col-sm-3 gap-md-left-15">Follow Up Date:</div>
		        <div class="col-xs-12 col-sm-7">
		        	<input type="text" name="next_action_date" id="next_action_date" class="form-control datepicker" value="<?= $next_action_date ?>">
	            </div>
	        </div>
	    </div>
    <?php } ?>
</div>