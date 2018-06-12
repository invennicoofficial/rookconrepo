<!-- Sales Order Preview -->
<?php $cat_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_so_contacts` WHERE `sales_order_type` = '$so_type'"),MYSQLI_ASSOC); ?>
<script>
	$(document).ready(function() {
        $('.mob_preview_details').each(function() {
            mobUpdateDetails($(this).attr('id'));
        });

        // mobUpdateOrderDetails();
        // $('div#order_details').find('input,select').off('change').change(function() {
        //     mobUpdateOrderDetails();
        // });
    });

    function mobUpdateDetails(div) {
        div_ajax = div.substring(4);
        var data = {};
        switch (div) {
            case 'mob_preview_customer':
                var customerid = $('#task_businessid').val();
                data = { customerid: customerid };
                break;
            <?php foreach ($cat_config as $contact_cat) { ?>
                case 'mob_preview_<?= $contact_cat['contact_category'] ?>_roster':
                    var customerid = $('#task_businessid').val();
                    data = { customerid: customerid };
                    break;
                case 'mob_preview_<?= $contact_cat['contact_category'] ?>_order':
                    var sotid = $('#sotid').val();
                    data = { sotid: sotid };
                    break;
            <?php } ?>
            <?php if(empty($cat_config)) { ?>
                case 'mob_preview_nocat_order':
                var sotid = $('#sotid').val();
                data = { sotid: sotid };
                break;
            <?php } ?>
            case 'mob_preview_security':
            case 'mob_preview_logo':
                var sotid = $('#sotid').val();
                data = { sotid: sotid };
            default:
                break;
        }
        $.ajax({
            type: "POST",
            url: "ajax.php?fill=previewDetails&div="+div_ajax,
            data: data,
            dataType: "html",
            success: function(response) {
                $("#"+div).html(response);
            }
        });
    }

    function mobUpdateOrderDetails() {
        var subtotal = parseFloat($('#subtotal').val()).toFixed(2);
        var html = '';
        html += '<h5 style="display: inline;">ORDER DETAILS:</h5><br>';

        //Subtotal
        html += 'Subtotal:<span style="float: right;">$'+subtotal+'</span><br>';

        //Discount
        var discount_type = $('[name="discount_type"]:checked').val();
        var discount_value = parseFloat($('#discount_value').val()).toFixed(2);
        var discount_amount = 0;
        var total_after_discount = 0;
        if (discount_value > 0 && (discount_type == '%' || discount_type == '$')) {
            if (discount_type == '%') {
                discount_amount = parseFloat(subtotal * (discount_value / 100)).toFixed(2);
            } else if (discount_type == '$') {
                discount_amount = discount_value;
            }
            html += 'Discount:<span style="float:right;">-$'+discount_amount+'</span><br>';
            total_after_discount = parseFloat(subtotal - discount_amount).toFixed(2);
            html += 'Total After Discount:<span style="float:right;">$'+total_after_discount+'</span><br>';
        }

        //Delivery
        var delivery_type = $('#delivery_type').val();
        var delivery_amount = parseFloat($('#delivery_amount').val()).toFixed(2);
        if (delivery_type != 'Company Delivery' && delivery_amount > 0) {
                html += 'Delivery:<span style="float:right;">$'+delivery_amount+'</span><br>';
        } else {
            delivery_amount = 0;
        }

        //Assembly
        var assembly_amount = parseFloat($('#assembly_amount').val()).toFixed(2);
        if (assembly_amount > 0) {
            html += 'Assembly:<span style="float:right;">$'+assembly_amount+'</span><br>';
        } else {
            assembly_amount = 0;
        }

        //Total Before Tax
        var total_before_tax = (parseFloat(subtotal) - parseFloat(discount_amount) + parseFloat(delivery_amount) + parseFloat(assembly_amount)).toFixed(2);
        html += 'Total Before Tax:<span style="float:right;">$'+total_before_tax+'</span><br>';

        //GST
        var gst_rate = parseFloat($('#gst_rate').val());
        var gst_amount = 0;
        if (gst_rate > 0) {
            gst_amount = parseFloat(total_before_tax * gst_rate / 100).toFixed(2);
            html += 'GST:<span style="float:right;">$'+gst_amount+'</span><br>';
        }

        //PST
        var pst_rate = parseFloat($('#pst_rate').val());
        var pst_amount = 0;
        if (pst_rate > 0) {
            pst_amount = parseFloat(total_before_tax * pst_rate / 100).toFixed(2);
            html += 'PST:<span style="float:right;">$'+pst_amount+'</span><br>';
        }

        //Total Price
        var total_price = (parseFloat(total_before_tax) + parseFloat(gst_amount) + parseFloat(pst_amount)).toFixed(2);
        html += 'Total Price:<span style="float:right;">$'+total_price+'</span><br>';

        //Deposit Paid
        var deposit_paid = parseFloat($('#deposit_paid').val()).toFixed(2);
        if (deposit_paid > 0) {
            html += 'Deposit Paid:<span style="float:right;">$'+deposit_paid+'</span><br>';
        }

        $('#mob_preview_order_details').html(html);
    }
</script>

<div class="main-screen-white double-gap-top">
    <div class="preview-block-container">
        <div class="preview-block">
            <div class="preview-block-header">
                <h4><?= SALES_ORDER_NOUN ?></h4>
            </div>
            
            <div class="padded">
                <div id="mob_preview_customer" class="mob_preview_details"></div><br/>
                <?php if (strpos($value_config, ',Logo,') !== FALSE) { ?>
                    <div id="mob_preview_logo" class="mob_preview_details"></div><br/>
                <?php } ?>
                <?php foreach ($cat_config as $contact_cat) { ?>
                    <div id="mob_preview_<?= $contact_cat['contact_category'] ?>_roster" class="mob_preview_details"></div><br />
                    <div id="mob_preview_<?= $contact_cat['contact_category'] ?>_order" class="mob_preview_details"></div><br />
                <?php } ?>
                <?php if(empty($cat_config)) { ?>
                    <div id="mob_preview_nocat_order" class="mob_preview_details"></div><br />
                <?php } ?>
                <div id="mob_preview_security" class="mob_preview_details"></div>
                <!-- <div id="mob_preview_order_details"></div> -->
            </div>

        </div><!-- .preview-block -->
    </div><!-- .preview-block-container -->
</div><!-- .main-screen-white -->

<div class="clearfix"></div>