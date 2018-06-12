<!-- Sales Order Preview Functions -->
<script>
    function updateItemDetails() {
        var subtotal = 0;
        var html = '';
        <?php foreach ($contact_categories as $contact_category) { ?>
            html = '<h5 style="display: inline;"><?= strtoupper($contact_category) ?> ORDER DETAILS</h5><br>';

            $('a#<?= $contact_category ?>').each(function() {
                var contactid = $(this).data('contactid');
                var contact_name = $(this).text().trim();
                html += '<b>'+contact_name+'</b>';

                var has_items = false;
                var html2 = '<table class="table" style="margin-bottom: 0;">';
                html2 += '<tr>';
                html2 += '<td style="border: none;"><b>Product</b></td>';
                html2 += '<td style="border: none;" width="10%"><b>Quantity</b></td>';
                html2 += '<td style="border: none;" width="15%"><b>Price</b></td>';
                html2 += '</tr>';
                $('.order_detail_contact[data-contactid="'+contactid+'"]').find('td[data-title="Quantity"]').each(function() {
                    if ($(this).find('[name="item_quantity[]"]').val() != '0' && isPositiveInteger($(this).find('[name="item_quantity[]"]').val())) {
                        row = $(this).closest('tr');
                        item_name = row.find('td[data-title="Category"]').text()+': '+row.find('td[data-title="Product"]').text();
                        item_quantity = row.find('td[data-title="Quantity"]').find('[name="item_quantity[]"]').val();
                        item_price = row.find('td[data-title="Price"]').text();
                        price = parseInt(item_quantity) * parseFloat(item_price);

                        html2 += '<tr>';
                        html2 += '<td style="border: none;">'+item_name+'</td>';
                        html2 += '<td style="border: none; text-align: center;">'+item_quantity+'</td>';
                        html2 += '<td style="border: none; text-align: right;">$'+price.toFixed(2)+'</td>';
                        html2 += '</tr>';
                        has_items = true;

                        subtotal += parseFloat(price.toFixed(2));
                    } else {
                        $(this).find('[name="item_quantity[]"]').val(0);
                    }
                });
                html2 += '</table>';
                if (has_items) {
                    html += html2;
                }
                html += '<br>';
            });
            $('.preview_<?= $contact_category ?>_details').html(html);
        <?php } ?>
        <?php if(empty($contact_categories)) { ?>
            html = '<h5 style="display: inline;"><?= strtoupper(SALES_ORDER_NOUN) ?> DETAILS</h5><br>';

            $('a#no_cat').each(function() {
                var contactid = $(this).data('contactid');
                var has_items = false;
                var html2 = '<table class="table" style="margin-bottom: 0;">';
                html2 += '<tr>';
                html2 += '<td style="border: none;"><b>Product</b></td>';
                html2 += '<td style="border: none;" width="10%"><b>Quantity</b></td>';
                html2 += '<td style="border: none;" width="15%"><b>Price</b></td>';
                html2 += '</tr>';
                $('.order_detail_contact[data-contactid="'+contactid+'"]').find('td[data-title="Quantity"]').each(function() {
                    if ($(this).find('[name="item_quantity[]"]').val() != '0' && isPositiveInteger($(this).find('[name="item_quantity[]"]').val())) {
                        row = $(this).closest('tr');
                        item_name = row.find('td[data-title="Category"]').text()+': '+row.find('td[data-title="Product"]').text();
                        item_quantity = row.find('td[data-title="Quantity"]').find('[name="item_quantity[]"]').val();
                        item_price = row.find('td[data-title="Price"]').text();
                        price = parseInt(item_quantity) * parseFloat(item_price);

                        html2 += '<tr>';
                        html2 += '<td style="border: none;">'+item_name+'</td>';
                        html2 += '<td style="border: none; text-align: center;">'+item_quantity+'</td>';
                        html2 += '<td style="border: none; text-align: right;">$'+price.toFixed(2)+'</td>';
                        html2 += '</tr>';
                        has_items = true;

                        subtotal += parseFloat(price.toFixed(2));
                    } else {
                        $(this).find('[name="item_quantity[]"]').val(0);
                    }
                });
                html2 += '</table>';
                if (has_items) {
                    html += html2;
                } else {
                    html += 'No Items Added.';
                }
            });
            $('.preview_nocat_details').html(html);
        <?php } ?>

        $('#subtotal').val(subtotal);
        <?php if($preview_type == 'order') { ?>
            updateOrderDetails();
        <?php } else if($preview_type == 'order_details') { ?>
            updateItemOrderDetails();
        <?php } ?>
    }

    function updateDetails(div) {
        var data = {};
        switch (div) {
            case 'preview_customer':
                var customerid = $('#task_businessid').val();
                data = { customerid: customerid };
                break;
            <?php foreach ($cat_config as $contact_cat) { ?>
                case 'preview_<?= $contact_cat['contact_category'] ?>_roster':
                    var customerid = $('#task_businessid').val();
                    data = { customerid: customerid };
                    break;
                case 'preview_<?= $contact_cat['contact_category'] ?>_order':
                    var sotid = $('#sotid').val();
                    data = { sotid: sotid };
                    break;
            <?php } ?>
            <?php if(empty($cat_config)) { ?>
                case 'preview_nocat_order':
                var sotid = $('#sotid').val();
                data = { sotid: sotid };
                break;
            <?php } ?>
            case 'preview_security':
            case 'preview_logo':
                var sotid = $('#sotid').val();
                data = { sotid: sotid };
            default:
                break;
        }
        $.ajax({
            type: "POST",
            url: "ajax.php?fill=previewDetails&div="+div,
            data: data,
            dataType: "html",
            success: function(response) {
                $("."+div).html(response);
            }
        });
    }

    function updateOrderDetails() {
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

        //Comments
        var comment = $('#comment').val();
        if(comment != '' && comment != undefined) {
            html += '<br><h5 style="display: inline">COMMENTS:</h5><br>';
            html += comment;
        }

        $('.preview_order_details').html(html);
    }

    function updateItemOrderDetails() {
        var subtotal = parseFloat($('#subtotal').val()).toFixed(2);
        var html = '';
        html += '<h5 style="display: inline;">ORDER DETAILS:</h5><br>';

        //Subtotal
        html += 'Subtotal:<span style="float: right;">$'+subtotal+'</span><br>';

        //Discount
        var discount_type = $('#discount_type').val();
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

        $('.preview_order_details').html(html);
    }

    function isPositiveInteger(str) {
        var n = Math.floor(Number(str));
        return String(n) === str && n >= 0;
    }
</script>