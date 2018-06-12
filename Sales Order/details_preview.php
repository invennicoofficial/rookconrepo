<!-- Sales Order Preview -->
<?php $cat_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_so_contacts` WHERE `sales_order_type` = '$so_type'"),MYSQLI_ASSOC);
$contact_categories = [];
foreach ($cat_config as $contact_cat) {
    $contact_categories[] = $contact_cat['contact_category'];
} ?>
<script>
	$(window).load(function() {
        $('[name="item_quantity[]"]').off('change', updateItemDetails).change(updateItemDetails);
        $('.preview_details').each(function() {
            updateDetails($(this).attr('id'));
        });

        updateItemDetails();
        updateOrderDetails();
        $('div#order_details').find('input,select,textarea').off('change').change(function() {
            updateOrderDetails();
        });
    });
</script>
<?php $preview_type = 'order';
include('details_preview_functions.php'); ?>

<?php
    // GST PST
    $get_pos_tax = get_config($dbc, 'sales_order_tax');
    $gst_total = 0;
    $pst_total = 0;
    if($get_pos_tax != '') {
        $pos_tax = explode('*#*',$get_pos_tax);

        $total_count = mb_substr_count($get_pos_tax,'*#*');
        for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
            $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);

            if (strcasecmp($pos_tax_name_rate[0], 'gst') == 0 && $pos_tax_name_rate[3] != 'Yes') {
                $gst_total = $gst_total + $pos_tax_name_rate[1];
            }

            if (strcasecmp($pos_tax_name_rate[0], 'pst') == 0 && $pos_tax_name_rate[3] != 'Yes') {
                $pst_total = $pst_total + $pos_tax_name_rate[1];
            }
        }
    }
    $subtotal = 0;
    // Products
    $product_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_product_temp` WHERE `parentsotid` = '$sotid'"),MYSQLI_ASSOC);
    foreach ($product_list as $product) {
        $product_price = number_format($product['item_price'], 2);
        $product_details_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `sales_order_product_details_temp` WHERE `parentsotid` ='".$product['sotid']."'"),MYSQLI_ASSOC);
        foreach ($product_details_list as $product_details) {
            $subtotal = $subtotal + ($product_price * $product_details['quantity']);
        }
    }
    $subtotal = number_format($subtotal, 2);
?>

<input type="hidden" id="subtotal" name="subtotal" value="<?= $subtotal ?>">
<input type="hidden" id="gst_rate" name="gst_rate" value="<?= $gst_total ?>">
<input type="hidden" id="pst_rate" name="pst_rate" value="<?= $pst_total ?>">

<div class="main-screen-white double-gap-top" style="border: 1px solid #ACA9A9">
    <div class="preview-block-container">
        <div class="preview-block">
            <div class="preview-block-header">
                <h4><?= SALES_ORDER_NOUN ?></h4>
            </div>
            
            <div class="padded">
                <div id="preview_customer" class="preview_details preview_customer"></div><br/>
                <?php if (strpos($value_config, ',Logo,') !== FALSE) { ?>
                    <div id="preview_logo" class="preview_details preview_logo"></div><br/>
                <?php } ?>
                <?php foreach ($cat_config as $contact_cat) { ?>
                    <div id="preview_<?= $contact_cat['contact_category'] ?>_roster" class="preview_details preview_<?= $contact_cat['contact_category'] ?>_roster"></div><br />
                    <div id="preview_<?= $contact_cat['contact_category'] ?>_order" class="preview_details preview_<?= $contact_cat['contact_category'] ?>_order"></div><br />
                <?php } ?>
                <?php if(empty($cat_config)) { ?>
                    <!-- <div id="preview_nocat_order" class="preview_details"></div><br /> -->
                <?php } ?>
                <?php foreach ($contact_categories as $contact_category) { ?>
                    <div class="preview_<?= $contact_category ?>_details"></div><br>
                <?php } ?>
                <?php if(empty($contact_categories)) { ?>
                    <div class="preview_nocat_details"></div><br>
                <?php } ?>
                <div id="preview_security" class="preview_details preview_security"></div>
                <div id="preview_order_details" class="preview_order_details"></div>
            </div>

        </div><!-- .preview-block -->
    </div><!-- .preview-block-container -->
</div><!-- .main-screen-white -->

<div class="clearfix"></div>