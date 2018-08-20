<!-- Lead Status -->
<div class="accordion-block-details padded" id="leadstatus">
    <div class="accordion-block-details-heading"><h4>Lead Status</h4></div>
    
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