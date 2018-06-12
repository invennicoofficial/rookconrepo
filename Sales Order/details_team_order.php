<!-- Team Order -->
<?php 
$contact_category = 'Team';
$item_from_types = explode(',',$field_config['product_fields']); ?>
<script>
    $(document).ready(function() {
    });
    //Delete
    function deleteRow(sel, hide) {
        var typeId = sel.id;
        var arr    = typeId.split('_');
        var sotid  = arr[1];
        $('#'+hide+arr[1]).hide();
        $.ajax({
            type: "GET",
            url: "ajax.php?fill=removeItem&sotid="+sotid,
            dataType: "html",
            success: function(response) {
            }
        });
    }
    
    //Edit Heading
    function editHeading(sel) {
        var row = $(sel).closest('.heading_row');
        row.find('.heading_row_text').hide();
        row.find('.heading_row_edit').show();
    }

    //Edit Heading Display Quantity
    function displayMandatoryQuantity(sel) {
        if ($(sel).is(':checked')) {
            $(sel).closest('.heading_row_edit').find('.mandatory_quantity').show();
        } else {
            $(sel).closest('.heading_row_edit').find('.mandatory_quantity').hide();
        }
    }

    //Save Heading
    function saveHeading(sel) {
        var sotid = $('#sotid').val();
        var item_type = $(sel).data('category');
        var contact_category = $(sel).data('contact-category');
        var old_heading_name = $(sel).data('heading-name');
        var row = $(sel).closest('.heading_details');
        var heading_name = row.find('[name="heading_name"]').val();
        var mandatory_checkbox = 0;
        if (row.find('[name="mandatory_checkbox"]').is(':checked')) {
            mandatory_checkbox = 1;
        }
        var mandatory_quantity = row.find('[name="mandatory_quantity"]').val();
        $.ajax({
            type: "POST",
            url: "ajax.php?from_type=sot&fill=updateHeading&sotid="+sotid+"&item_type="+item_type+"&contact_category="+contact_category+"&old_heading_name="+old_heading_name+"&mandatory_checkbox="+mandatory_checkbox+"&mandatory_quantity="+mandatory_quantity+"&heading_name="+heading_name,
            dataType: "html",
            success: function(response) {
                $(sel).attr('data-heading-name', heading_name);
                $(sel).closest('.heading_row').find('.heading_row_text').show().find('b').text(response);
                $(sel).closest('.heading_row').find('.heading_row_edit').hide();
            }
        })
    }

    //Edit Price
    function editPrice(sel) {
        var row = $(sel).closest('.row');
        row.find('.price_text').hide();
        row.find('.price_input').show().find('input').focus();
    }

    //Update Price
    function updatePrice(sel) {
        var main_sotid = $('#sotid').val();
        var row = $(sel).closest('.row');
        var sotid = row.attr('id').split('_')[1];
        var price = $(sel).val();
        $.ajax({
            url: 'ajax.php?fill=updateProductPrice&main_sotid='+main_sotid+'&sotid='+sotid+'&price='+price,
            type: "GET",
            dataType: "html",
            success: function(response) {
                row.find('.price_text_number').text(response);
                $(sel).val(response);
                row.find('.price_text').show();
                row.find('.price_input').hide();
            }
        });
    }
</script>

<div class="accordion-block-details padded" id="team_order">
    <div class="accordion-block-details-heading"><h4>Team Order Details</h4></div>
    <?php foreach ($item_from_types as $item_from) { ?>
        <div class="row">
            <div class="row double-gap-top"><div class="col-sm-12 default-color"><h4><?= $item_from ?></h4></div></div>
            <div class="row set-row-height">
                <div class="col-sm-3 gap-md-left-15 pad-top-5">Add Item From:</div>
                <div class="col-sm-7"><a href="javascript:void(0);" class="btn brand-btn iframe_open" data-category="<?= $item_from ?>" data-title="Select items from <?= $item_from ?>" data-contact-category="<?= $contact_category ?>"><?= $item_from ?></a></div>
            </div><?php
            $headings = mysqli_fetch_all(mysqli_query($dbc, "SELECT DISTINCT `heading_name`, `mandatory_quantity` FROM `sales_order_product_temp` WHERE `parentsotid` = '$sotid' AND `item_type` = '$item_from' AND `contact_category` = '$contact_category'"),MYSQLI_ASSOC);

            foreach ($headings as $heading) {
                $heading_name = $heading['heading_name'];
                $mandatory_quantity = $heading['mandatory_quantity'];
                $query  = "SELECT * FROM `sales_order_product_temp` WHERE `parentsotid`='$sotid' AND `item_type`='$item_from' AND `contact_category` = '$contact_category' AND `heading_name` = '$heading_name'";
                $result = mysqli_query($dbc, $query);

                if ( $result->num_rows > 0 ) { ?>
                    <div class="heading_row">
                        <div class="row heading_row_text"><div class="col-sm-12 default-color"><b><?= $heading_name.($mandatory_quantity > 0 ? ' (Mandatory Quantity: '.$mandatory_quantity.')' : '') ?></b> <a href="" onclick="editHeading(this); return false;"><span style="font-size: x-small; color: #888;">EDIT HEADING</span></a></div></div>
                        <div class="heading_row_edit" style="display: none;">
                            <div class="row">
                                <div class="col-sm-3"><b>Heading Name</b></div>
                                <div class="col-sm-2" style="text-align: center;"><b>Mandatory?</b></div>
                                <div class="col-sm-3 mandatory_quantity" <?= ($mandatory_quantity == 0 ? 'style="display: none;"' : '') ?>><b>Mandatory Quantity</b></div>
                                <div class="col-sm-2"></div>
                            </div>
                            <div class="row heading_details">
                                <div class="col-sm-3"><input type="text" name="heading_name" value="<?= $heading_name ?>" class="form-control"></div>
                                <div class="col-sm-2" style="text-align: center;"><input type="checkbox" name="mandatory_checkbox" value="1" <?= ($mandatory_quantity > 0 ? 'checked="checked"' : '') ?> style="transform: scale(1.5);" onclick="displayMandatoryQuantity(this);"></div>
                                <div class="col-sm-3 mandatory_quantity" <?= ($mandatory_quantity == 0 ? 'style="display: none;"' : '') ?>><input type="number" name="mandatory_quantity" value="<?= $mandatory_quantity ?>" class="form-control"></div>
                                <div class="col-sm-2"><button onclick="saveHeading(this); return false;" class="btn brand-btn" data-category="<?= $item_from ?>" data-contact-category="<?= $contact_category ?>" data-heading-name="<?= $heading_name ?>">Save</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden-xs">
                        <div class="col-sm-4"><b>Category</b></div>
                        <div class="col-sm-5"><b><?= $item_from == 'Services' ? 'Service' : 'Product' ?></b></div>
                        <div class="col-sm-2"><b>Price</b></div>
                        <!-- <div class="col-sm-2"><b>Quantity</b></div> -->
                        <div class="col-sm-1"></div>
                    </div><?php
                    
                    while ( $row=mysqli_fetch_assoc($result) ) { ?>
                        <div class="row pad-top-5" id="row_<?= $row["sotid"]; ?>">
                            <div class="visible-xs-block col-xs-4"><b>Category: </b></div><div class="col-xs-9 col-sm-4"><?= $row['item_category']; ?></div>
                            <div class="visible-xs-block col-xs-4"><b>Product: </b></div><div class="col-sm-5"><?= $row['item_name']; ?></div>
                            <div class="visible-xs-block col-xs-4"><b>Price: </b></div><div class="col-sm-2"><div class="price_text"><span class="price_text_number"><?= $row['item_price']; ?></span> <a href="" onclick="editPrice(this); return false;"><span style="font-size: x-small; color: #888;">EDIT</span></a></div><div class="price_input" style="display:none;"><input type="number" name="item_price_input" value="<?= $row['item_price']; ?>" class="form-control" onfocusout="updatePrice(this);" step="0.01"></div></div>
                            <!-- <div class="visible-xs-block col-xs-3"><b>Quantity: </b></div><div class="col-sm-2"><?= $row['quantity']; ?></div> -->
                            <a href="javascript:void(0);" onclick="deleteRow(this, 'row_'); return false;" id="delete_<?= $row['sotid']; ?>" class="col-sm-1 pull-right"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                        </div><?php
                    }
                }
            } ?>
        </div>
        <hr>
    <?php } ?>
</div>