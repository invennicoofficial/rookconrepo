<!-- Order Details -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#delivery_type').on('change', function() {
            var delivery_type = $(this).val();
            if(delivery_type == 'Drop Ship' || delivery_type == 'Shipping') {
                $("#contractor").show();
                $("#delivery_address").show();
                $("#delivery_div").show();
            } else if(delivery_type == 'Company Delivery') {
                $("#contractor").hide();
                $("#delivery_div").hide();
                $("#delivery_address").show();
            } else {
                $("#contractor").hide();
                $("#delivery_address").hide();
                $("#delivery_div").show();
            }
        });
    });
</script>

<div class="accordion-block-details padded" id="order_details">
    <div class="accordion-block-details-heading"><h4>Order Details</h4></div>
    
    <!-- Discount -->
    <?php if (strpos($value_config, ','."Discount".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Discount Type:</div>
        <div class="col-sm-7">
            <label class="double-pad-right"><input type="radio" style="height:20px;width:20px;  margin-right:20px;" name="discount_type" value="%" <?= $discount_type == '%' ? 'checked' : '' ?>>%</label>
            <label class="pad-right"><input type="radio" style="height:20px;width:20px; margin-right:20px;" name="discount_type" value="$" <?= $discount_type == '$' ? 'checked' : '' ?>>$</label>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Discount Value:</div>
        <div class="col-sm-7">
            <input name="discount_value" id="discount_value" value="<?= $discount_value ?>" type="number" step="0.01" class="form-control" />
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Delivery -->
    <?php if (strpos($value_config, ','."Delivery".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Delivery Type:</div>
        <div class="col-sm-7">
            <select data-placeholder="Choose a Type..." name="delivery_type" id="delivery_type" class="chosen-select-deselect form-control product" style="position:relative;">
                <option value=""></option>
                <option <?= $delivery_type == "Pick-Up" ? 'selected' : '' ?> value="Pick-Up">Pick-Up</option>
                <option <?= $delivery_type == "Company Delivery" ? 'selected' : '' ?> value="Company Delivery">Company Delivery</option>
                <option <?= $delivery_type == "Drop Ship" ? 'selected' : '' ?> value="Drop Ship">Drop Ship</option>
                <option <?= $delivery_type == "Shipping" ? 'selected' : '' ?> value="Shipping">Shipping</option>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row set-row-height" style="display: none;" id="delivery_address">
        <div class="col-sm-3 gap-md-left-15">Delivery Address:</div>
        <div class="col-sm-7">
            <input name="delivery_address" id="delivery_address_fillup" type="text" class="form-control" />
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row set-row-height" style="display: none;" id="contractor">
        <div class="col-sm-3 gap-md-left-15">Contractor:</div>
        <div class="col-sm-7">
            <select name="contractorid" data-placeholder="Choose Contractor..." class="chosen-select-deselect form-control" width="380">
                <option value=''></option>
               <?php
                    $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Contractor' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
                    foreach($query as $id) {
                        echo "<option ".($contractorid == $id ? 'selected' : '')."value='". $id."'>".get_contact($dbc, $id).'</option>';
                    }
                  ?>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row set-row-height" id="delivery_div">
        <div class="col-sm-3 gap-md-left-15">Delivery/Shipping Amount:</div>
        <div class="col-sm-7">
            <input name="delivery_amount" id="delivery_amount" value="<?= $delivery_amount ?>" type="number" step="0.01" class="form-control" />
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Assembly -->
    <?php if (strpos($value_config, ','."Assembly".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Assembly Amount:</div>
        <div class="col-sm-7">
            <input name="assembly_amount" id="assembly_amount" value="<?= $assembly_amount ?>" type="number" step="0.01" class="form-control" />
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Payment Type -->
    <?php if (strpos($value_config, ','."Payment Type".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Payment Type:</div>
        <div class="col-sm-7">
            <select id="payment_type" name="payment_type" data-placeholder="Choose a Type..." class="chosen-select-deselect form-control" width="380">
              <option value=""></option>
              <?php
                $tabs = get_config($dbc, 'sales_order_invoice_payment_types');
                $each_tab = explode(',', $tabs);
                 if (is_array($each_tab) && count($each_tab) > 0) {
                    foreach ($each_tab as $cat_tab) {
                        if ($payment_type == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                 } else {
                     echo "<option ".($payment_type == 'Pay Now' ? 'selected' : '')." value='Pay Now'>Pay Now</option>";
                     echo "<option ".($payment_type == 'Net 30' ? 'selected' : '')." value='Net 30'>Net 30</option>";
                     echo "<option ".($payment_type == 'Net 60' ? 'selected' : '')." value='Net 60'>Net 60</option>";
                     echo "<option ".($payment_type == 'Net 90' ? 'selected' : '')." value='Net 90'>Net 90</option>";
                     echo "<option ".($payment_type == 'Net 120' ? 'selected' : '')." value='Net 120'>Net 120</option>";
                 }
              ?>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Deposit Paid -->
    <?php if (strpos($value_config, ','."Deposit Paid".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Deposit Paid:</div>
        <div class="col-sm-7">
            <input name="deposit_paid" id="deposit_paid" value="<?= $deposit_paid ?>" type="number" step="0.01" class="form-control" />
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Comment -->
    <?php if (strpos($value_config, ','."Comment".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Add Comments to Order:</div>
        <div class="col-sm-7">
            <textarea name="comment" class="form-control"><?= html_entity_decode($comment) ?></textarea>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Ship Date -->
    <?php if (strpos($value_config, ','."Ship Date".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Ship Date:</div>
        <div class="col-sm-7">
            <input name="ship_date" id="ship_date" value="<?= $ship_date ?>" type="text" class="form-control datepicker" />
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Due Date -->
    <?php if (strpos($value_config, ','."Due Date".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Due Date:</div>
        <div class="col-sm-7">
            <input name="due_date" id="due_date" value="<?= $due_date ?>" type="text" class="form-control datepicker" />
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

    <!-- Frequency -->
    <?php if (strpos($value_config, ','."Frequency".',') !== FALSE) { ?>
    <div class="row set-row-height">
        <div class="col-sm-3 gap-md-left-15">Frequency:</div>
        <div class="col-sm-2">
            <input name="frequency" id="frequency" value="<?= $frequency ?>" type="number" class="form-control" min="0" placeholder="# of" />
        </div>
        <div class="col-sm-5">
            <select name="frequency_type" id="frequency_type" data-placeholder="Select a Type..." class="chosen-select-deselect inline">
                <option></option>
                <option value="Days" <?= $frequency_type == 'Days' ? 'selected' : '' ?>>Days</option>
                <option value="Weeks" <?= $frequency_type == 'Weeks' ? 'selected' : '' ?>>Weeks</option>
                <option value="Months" <?= $frequency_type == 'Months' ? 'selected' : '' ?>>Months</option>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php } ?>

</div>