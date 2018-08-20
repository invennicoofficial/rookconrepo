<!-- Next Actions -->
<div class="accordion-block-details padded" id="nextaction">
    <div class="accordion-block-details-heading"><h4>Next Actions</h4></div>
    
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Next Action:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Choose a Next Action..." data-table="sales" name="next_action" class="chosen-select-deselect form-control">
                <option value=""></option><?php
				$tabs = get_config($dbc, 'sales_next_action');
				
				foreach ( explode(',', $tabs) as $cat_tab ) {
					$selected = ( $next_action == $cat_tab ) ? 'selected="selected"' : '';
					echo '<option ' . $selected . ' value="' . $cat_tab . '">' . $cat_tab . '</option>';
				} ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">New Reminder:</div>
        <div class="col-xs-12 col-sm-5"><input data-table="sales" name="new_reminder" value="<?= $new_reminder; ?>" type="text" class="datepicker form-control" /></div>
        <div class="clearfix"></div>
    </div>
    
    <!-- Lead Status -->
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Lead Status:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Choose a Status..." data-table="sales" name="status" class="chosen-select-deselect form-control">
                <!-- <option value=""></option> --><?php
                $tabs = get_config ( $dbc, 'sales_lead_status' );
                foreach ( explode ( ',', $tabs ) as $cat_tab ) {
                    if(empty($status)) {
                        $status = $cat_tab;
                    }
                    $selected = ( $status == $cat_tab ) ? 'selected="selected"' : '';
                    echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
                } ?>
                <option <?= ($status == 'Customers' ? 'selected ' : ''); ?>value="Customers">Customer</option>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
    
</div><!-- .accordion-block-details -->