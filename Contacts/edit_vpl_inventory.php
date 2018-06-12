<?php
/*
 * Add/Edit Vendor Price List Inventory
 * Included in:
 *  - edit_addition_vendor_price_lists.php
 */

error_reporting(0);
include_once('../include.php');

if (isset($_POST['submit'])) {
    $code = filter_var($_POST['code'],FILTER_SANITIZE_STRING);
    $item_sku = filter_var($_POST['item_sku'],FILTER_SANITIZE_STRING);
    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);
    $comment = filter_var(htmlentities($_POST['comment']),FILTER_SANITIZE_STRING);
    $question = filter_var(htmlentities($_POST['question']),FILTER_SANITIZE_STRING);
    $request = filter_var(htmlentities($_POST['request']),FILTER_SANITIZE_STRING);
    $note = filter_var(htmlentities($_POST['note']),FILTER_SANITIZE_STRING);

    if($_POST['same_desc'] == 1) {
        $quote_description = $description;
    } else {
        $quote_description = filter_var(htmlentities($_POST['quote_description']),FILTER_SANITIZE_STRING);
    }

    $vendorid = $_POST['vendorid'];
    $display_website = $_POST['display_website'];

    if($_POST['size'] == 'Other') {
        $size = filter_var($_POST['size_name'],FILTER_SANITIZE_STRING);
    } else {
        $size = filter_var($_POST['size'],FILTER_SANITIZE_STRING);
    }
    if($_POST['weight'] == 'Other') {
        $weight = filter_var($_POST['weight_name'],FILTER_SANITIZE_STRING);
    } else {
        $weight = filter_var($_POST['weight'],FILTER_SANITIZE_STRING);
    }
    $type = filter_var($_POST['type'],FILTER_SANITIZE_STRING);
    $color = filter_var($_POST['color'],FILTER_SANITIZE_STRING);
    $date_of_purchase = filter_var($_POST['date_of_purchase'],FILTER_SANITIZE_STRING);
    $purchase_cost = filter_var($_POST['purchase_cost'],FILTER_SANITIZE_STRING);
    $sell_price = filter_var($_POST['sell_price'],FILTER_SANITIZE_STRING);
    $markup = filter_var($_POST['markup'],FILTER_SANITIZE_STRING);
    $freight_charge = filter_var($_POST['freight_charge'],FILTER_SANITIZE_STRING);
    $min_bin = filter_var($_POST['min_bin'],FILTER_SANITIZE_STRING);
    $current_stock = filter_var($_POST['current_stock'],FILTER_SANITIZE_STRING);

    $stocking_units = filter_var($_POST['stocking_units'],FILTER_SANITIZE_STRING);
    $selling_units  = filter_var($_POST['selling_units'],FILTER_SANITIZE_STRING);
    $buying_units = filter_var($_POST['buying_units'],FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'],FILTER_SANITIZE_STRING);
    $asset = filter_var($_POST['asset'],FILTER_SANITIZE_STRING);
    $revenue = filter_var($_POST['revenue'],FILTER_SANITIZE_STRING);
    $inv_variance = filter_var($_POST['inv_variance'],FILTER_SANITIZE_STRING);
    $web_price = filter_var($_POST['web_price'],FILTER_SANITIZE_STRING);
    $average_cost = filter_var($_POST['average_cost'],FILTER_SANITIZE_STRING);
    $preferred_price = filter_var($_POST['preferred_price'],FILTER_SANITIZE_STRING);

    $id_number = filter_var($_POST['id_number'],FILTER_SANITIZE_STRING);
    $operator = filter_var($_POST['operator'],FILTER_SANITIZE_STRING);
    $lsd = filter_var($_POST['lsd'],FILTER_SANITIZE_STRING);
    $quantity = filter_var($_POST['quantity'],FILTER_SANITIZE_STRING);

    $final_retail_price = filter_var($_POST['final_retail_price'],FILTER_SANITIZE_STRING);
    $admin_price = filter_var($_POST['admin_price'],FILTER_SANITIZE_STRING);
    $wholesale_price = filter_var($_POST['wholesale_price'],FILTER_SANITIZE_STRING);
    $commercial_price = filter_var($_POST['commercial_price'],FILTER_SANITIZE_STRING);
    $client_price = filter_var($_POST['client_price'],FILTER_SANITIZE_STRING);
    $purchase_order_price = filter_var($_POST['purchase_order_price'],FILTER_SANITIZE_STRING);
    $sales_order_price = filter_var($_POST['sales_order_price'],FILTER_SANITIZE_STRING);
    $minimum_billable = filter_var($_POST['minimum_billable'],FILTER_SANITIZE_STRING);
    $estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
    $actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
    $msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);
    $suggested_retail_price = filter_var($_POST['suggested_retail_price'],FILTER_SANITIZE_STRING);
    $rush_price = filter_var($_POST['rush_price'],FILTER_SANITIZE_STRING);

    $product_name = filter_var($_POST['product_name'],FILTER_SANITIZE_STRING);
    $cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
    $usd_cpu = filter_var($_POST['usd_cpu'],FILTER_SANITIZE_STRING);
    $commission_price = filter_var($_POST['commission_price'],FILTER_SANITIZE_STRING);
    $markup_perc = filter_var($_POST['markup_perc'],FILTER_SANITIZE_STRING);
    $current_inventory = filter_var($_POST['current_inventory'],FILTER_SANITIZE_STRING);
    $write_offs = filter_var($_POST['write_offs'],FILTER_SANITIZE_STRING);
    $min_max = filter_var($_POST['min_max'],FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'],FILTER_SANITIZE_STRING);
    $min_amount = filter_var($_POST['min_amount'],FILTER_SANITIZE_STRING);
    $max_amount = filter_var($_POST['max_amount'],FILTER_SANITIZE_STRING);

    $unit_price = filter_var($_POST['unit_price'],FILTER_SANITIZE_STRING);
    $unit_cost = filter_var($_POST['unit_cost'],FILTER_SANITIZE_STRING);
    $rent_price = filter_var($_POST['rent_price'],FILTER_SANITIZE_STRING);
    $rental_days = filter_var($_POST['rental_days'],FILTER_SANITIZE_STRING);
    $rental_weeks = filter_var($_POST['rental_weeks'],FILTER_SANITIZE_STRING);
    $rental_months = filter_var($_POST['rental_months'],FILTER_SANITIZE_STRING);
    $rental_years = filter_var($_POST['rental_years'],FILTER_SANITIZE_STRING);
    $reminder_alert = filter_var($_POST['reminder_alert'],FILTER_SANITIZE_STRING);
    $daily = filter_var($_POST['daily'],FILTER_SANITIZE_STRING);
    $weekly = filter_var($_POST['weekly'],FILTER_SANITIZE_STRING);
    $monthly = filter_var($_POST['monthly'],FILTER_SANITIZE_STRING);
    $annually = filter_var($_POST['annually'],FILTER_SANITIZE_STRING);
    $total_days = filter_var($_POST['total_days'],FILTER_SANITIZE_STRING);
    $total_hours = filter_var($_POST['total_hours'],FILTER_SANITIZE_STRING);
    $total_km = filter_var($_POST['total_km'],FILTER_SANITIZE_STRING);
    $total_miles = filter_var($_POST['total_miles'],FILTER_SANITIZE_STRING);
    $include_in_pos = filter_var($_POST['include_in_pos'],FILTER_SANITIZE_STRING);
    $include_in_po = filter_var($_POST['include_in_po'],FILTER_SANITIZE_STRING);
    $include_in_so = filter_var($_POST['include_in_so'],FILTER_SANITIZE_STRING);

    if($_POST['category'] == 'Other') {
        $category = filter_var($_POST['category_name'],FILTER_SANITIZE_STRING);
    } else {
        $category = filter_var($_POST['category'],FILTER_SANITIZE_STRING);
    }

    if($_POST['sub_category'] == 'Other') {
        $sub_category = filter_var($_POST['sub_category_name'],FILTER_SANITIZE_STRING);
    } else {
        $sub_category = filter_var($_POST['sub_category'],FILTER_SANITIZE_STRING);
    }

    $part_no = filter_var($_POST['part_no'],FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $usd_invoice = filter_var($_POST['usd_invoice'],FILTER_SANITIZE_STRING);
    $shipping_rate = filter_var($_POST['shipping_rate'],FILTER_SANITIZE_STRING);
    $shipping_cash = filter_var($_POST['shipping_cash'],FILTER_SANITIZE_STRING);
    $exchange_rate = filter_var($_POST['exchange_rate'],FILTER_SANITIZE_STRING);
    $exchange_cash = filter_var($_POST['exchange_cash'],FILTER_SANITIZE_STRING);
    $cdn_cpu = filter_var($_POST['cdn_cpu'],FILTER_SANITIZE_STRING);
    $cogs_total = filter_var($_POST['cogs_total'],FILTER_SANITIZE_STRING);
    
    if ( empty($name) ) {
        $name = $product_name;
    }

    if(empty($_POST['inventoryid'])) {

        $query_insert_inventory = "INSERT INTO `vendor_price_list` (`code`, `category`, `sub_category`, `part_no`, `description`, `comment`, `question`, `request`, `display_website`, `vendorid`, `size`, `weight`, `type`, `name`, `date_of_purchase`, `purchase_cost`, `sell_price`, `markup`, `freight_charge`, `min_bin`, `current_stock`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `purchase_order_price`, `sales_order_price`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `quote_description`, `usd_invoice`, `shipping_rate`, `shipping_cash`, `exchange_rate`, `exchange_cash`, `cdn_cpu`, `cogs_total`, `location`, `inv_variance`, `average_cost`, `asset`, `revenue`, `buying_units`, `selling_units`, `stocking_units`, `preferred_price`, `web_price`, `id_number`, `operator`, `lsd`, `quantity`, `product_name`, `cost`, `usd_cpu`, `commission_price`, `markup_perc`, `current_inventory`, `write_offs`, `min_max`, `status`, `note`, `unit_price`, `unit_cost`, `rent_price`, `rental_days`, `rental_weeks`, `rental_months`, `rental_years`, `reminder_alert`, `daily`,`weekly`, `monthly`, `annually`,  `total_days`, `total_hours`, `total_km`, `total_miles`, `include_in_so`,`include_in_po`,`include_in_pos`,`item_sku`,`color`,`suggested_retail_price`, `rush_price`, `min_amount`, `max_amount`
        ) VALUES ('$code', '$category', '$sub_category', '$part_no', '$description', '$comment', '$question', '$request', '$display_website', '$vendorid', '$size', '$weight', '$type', '$name', '$date_of_purchase', '$purchase_cost', '$sell_price', '$markup', '$freight_charge', '$min_bin', '$current_stock', '$final_retail_price', '$admin_price', '$wholesale_price', '$commercial_price', '$client_price', '$purchase_order_price', '$sales_order_price', '$minimum_billable', '$estimated_hours', '$actual_hours', '$msrp', '$quote_description', '$usd_invoice', '$shipping_rate', '$shipping_cash', '$exchange_rate', '$exchange_cash', '$cdn_cpu', '$cogs_total', '$location', '$inv_variance', '$average_cost', '$asset', '$revenue', '$buying_units', '$selling_units', '$stocking_units', '$preferred_price', '$web_price', '$id_number', '$operator', '$lsd', '$quantity', '$product_name', '$cost', '$usd_cpu', '$commission_price', '$markup_perc', '$current_inventory', '$write_offs', '$min_max', '$status', '$note', '$unit_price', '$unit_cost', '$rent_price', '$rental_days', '$rental_weeks', '$rental_months', '$rental_years', '$reminder_alert', '$daily', '$weekly', '$monthly', '$annually', '$total_days', '$total_hours', '$total_km', '$total_miles', '$include_in_so', '$include_in_po', '$include_in_pos', '$item_sku', '$color', '$suggested_retail_price', '$rush_price', '$min_amount', '$max_amount')";
        $result_insert_inventory = mysqli_query($dbc, $query_insert_inventory);
        $url = 'Added';

    } else {
        $inventoryid = $_POST['inventoryid'];

        $query_update_inventory = "UPDATE `vendor_price_list` SET `code` = '$code', `category` = '$category', `sub_category` = '$sub_category', `part_no` = '$part_no', `description` = '$description', `comment` = '$comment', `question` = '$question', `request` = '$request', `display_website` = '$display_website', `vendorid` = '$vendorid', `size` = '$size', `weight` = '$weight', `type` = '$type', `name` = '$name', `date_of_purchase` = '$date_of_purchase', `purchase_cost` = '$purchase_cost', `sell_price` = '$sell_price', `markup` = '$markup', `freight_charge` = '$freight_charge', `min_bin` = '$min_bin', `current_stock` = '$current_stock', `final_retail_price` = '$final_retail_price', `admin_price` = '$admin_price', `wholesale_price` = '$wholesale_price', `commercial_price` = '$commercial_price', `client_price` = '$client_price', `purchase_order_price` = '$purchase_order_price', `sales_order_price` = '$sales_order_price', `minimum_billable` = '$minimum_billable', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `quote_description` = '$quote_description', `usd_invoice` = '$usd_invoice', `shipping_rate` = '$shipping_rate', `shipping_cash` = '$shipping_cash', `exchange_rate` = '$exchange_rate', `exchange_cash` = '$exchange_cash', `cdn_cpu` = '$cdn_cpu', `cogs_total` = '$cogs_total', `location` = '$location', `inv_variance` = '$inv_variance', `average_cost` = '$average_cost', `asset` = '$asset', `revenue` = '$revenue', `buying_units` = '$buying_units', `selling_units` = '$selling_units', `stocking_units` = '$stocking_units', `preferred_price` = '$preferred_price', `web_price` = '$web_price', `id_number` = '$id_number', `operator` = '$operator', `lsd` = '$lsd', `quantity` = '$quantity', `product_name` = '$product_name', `cost` = '$cost', `usd_cpu` = '$usd_cpu', `commission_price` = '$commission_price', `markup_perc` = '$markup_perc', `current_inventory` = '$current_inventory', `write_offs` = '$write_offs', `min_max` = '$min_max', `status` = '$status', `note` = '$note', `unit_price` = '$unit_price', `unit_cost` = '$unit_cost', `rent_price` = '$rent_price', `rental_days` = '$rental_days', `rental_weeks` = '$rental_weeks', `rental_months` = '$rental_months', `rental_years` = '$rental_years', `reminder_alert` = '$reminder_alert', `daily` = '$daily', `weekly` = '$weekly', `monthly` = '$monthly', `annually` = '$annually', `total_days` = '$total_days', `total_hours` = '$total_hours', `total_km` = '$total_km', `total_miles` = '$total_miles', `include_in_so` = '$include_in_so', `include_in_po` = '$include_in_po', `include_in_pos` = '$include_in_pos', `item_sku` = '$item_sku', `color` = '$color', `suggested_retail_price` = '$suggested_retail_price', `rush_price` = '$rush_price', `min_amount` = '$min_amount', `max_amount` = '$max_amount' WHERE `inventoryid` = '$inventoryid'";

        $result_update_inventory = mysqli_query($dbc, $query_update_inventory);

        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("inventory.php?category='.$category.'"); </script>';
}

?>
<script type="text/javascript">
    $(document).ready(function () {

        $("#form1").submit(function( event ) {
            var category = $("[name=category]").last().val();
            var sub_category = $("#sub_category").val();

            var code = $("input[name=code]").val();
            var name = $("input[name=name]").val();
            var category_name = $("input[name=category_name]").val();
            var sub_category_name = $("input[name=sub_category_name]").val();

            if ((code != undefined && code == '') || (category != undefined && category == '') || (sub_category != undefined && sub_category == '') || (name != undefined && name == '')) {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
            if(((category != undefined) && (category == 'Other') && (category_name == '')) || ((sub_category != undefined) && (sub_category == 'Other') && (sub_category_name == ''))) {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        });

        $("#category").change(function() {
            if($( "#category option:selected" ).text() == 'Other') {
                    $( "#category_name" ).show();
            } else {
                $( "#category_name" ).hide();
            }
        });
        $("#size").change(function() {
            if($( "#size option:selected" ).text() == 'Other') {
                    $( "#size_name" ).show();
            } else {
                $( "#size_name" ).hide();
            }
        });
        $("#weight").change(function() {
            if($( "#weight option:selected" ).text() == 'Other') {
                    $( "#weight_name" ).show();
            } else {
                $( "#weight_name" ).hide();
            }
        });
        $("#sub_category").change(function() {
            if($( "#sub_category option:selected" ).text() == 'Other') {
                    $( "#sub_category_name" ).show();
            } else {
                $( "#sub_category_name" ).hide();
            }
        });
});
// $(document).on('change', 'select[name="sub_category"]', function() { selectSubCategory(this); });

</script>
</head>

<body><?php
    include_once ('../navigation.php');
    //checkAuthorised('vendor');
    $category = $_GET['category'];
    
    $code = '';
    $item_sku = '';
    $sub_category = '';
    $part_no = '';
    $description = '';
    $comment = '';
    $question = '';
    $request = '';
    $display_website = '';
    $vendorid = '';
    $size = '';
    $weight = '';
    $type = '';
    $color = '';
    $name = '';
    $date_of_purchase = '';
    $purchase_cost = '';
    $sell_price = '';
    $markup = '';
    $freight_charge = '';
    $min_bin = '';
    $current_stock = '';
    $final_retail_price = '';
    $admin_price = '';
    $wholesale_price = '';
    $commercial_price = '';
    $client_price = '';
    $purchase_order_price = '';
    $sales_order_price = '';
    $minimum_billable = '';
    $estimated_hours = '';
    $actual_hours = '';
    $msrp = '';
    $quote_description = '';

    $id_number = '';
    $operator = '';
    $lsd = '';
    $quantity = '';

    $usd_invoice = '';
    $shipping_rate = '';
    $shipping_cash = '';
    $exchange_rate = '';
    $exchange_cash = '';
    $cdn_cpu = '';
    $cogs_total = '';

    $stocking_units = '';
    $selling_units  = '';
    $buying_units = '';
    $location = '';
    $asset = '';
    $revenue = '';
    $inv_variance = '';
    $web_price = '';
    $average_cost = '';
    $preferred_price = '';
    $suggested_retail_price = '';
    $rush_price = '';

    $product_name = '';
    $cost = '';
    $usd_cpu = '';
    $commission_price = '';
    $markup_perc = '';
    $current_inventory = '';
    $write_offs = '';
    $min_max = '';
    $status = '';
    $note = '';
    $min_amount = '';
    $max_amount = '';

    $unit_price = '';
    $unit_cost = '';
    $rent_price = '';
    $rental_days = '';
    $rental_weeks = '';
    $rental_months = '';
    $rental_years = '';
    $reminder_alert = '';
    $daily = '';
    $weekly = '';
    $monthly = '';
    $annually = '';
    $total_days = '';
    $total_hours = '';
    $total_km = '';
    $total_miles = '';
    $include_in_po = '';
    $include_in_so = '';
    $include_in_pos = '';
    
    if ( !empty($_GET['inventoryid']) ) {
        $inventoryid = preg_replace('/[^0-9]/', '', $_GET['inventoryid']);
        $get_inventory = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM `vendor_price_list` WHERE `inventoryid`='$inventoryid'"));
        $code = $get_inventory['code'];
        $item_sku = $get_inventory['item_sku'];
        $category = $get_inventory['category'];
        $sub_category = $get_inventory['sub_category'];
        $part_no = $get_inventory['part_no'];
        $description = $get_inventory['description'];
        $comment = $get_inventory['comment'];
        $question = $get_inventory['question'];
        $request = $get_inventory['request'];
        $display_website = $get_inventory['display_website'];
        $vendorid = $get_inventory['vendorid'];
        $size = $get_inventory['size'];
        $color = $get_inventory['color'];
        $weight = $get_inventory['weight'];
        $type = $get_inventory['type'];
        $name = $get_inventory['name'];
        $date_of_purchase = $get_inventory['date_of_purchase'];
        $purchase_cost = $get_inventory['purchase_cost'];
        $sell_price = $get_inventory['sell_price'];
        $markup = $get_inventory['markup'];
        $freight_charge = $get_inventory['freight_charge'];
        $min_bin = $get_inventory['min_bin'];
        $current_stock = $get_inventory['current_stock'];
        $final_retail_price = $get_inventory['final_retail_price'];
        $admin_price = $get_inventory['admin_price'];
        $wholesale_price = $get_inventory['wholesale_price'];
        $commercial_price = $get_inventory['commercial_price'];
        $client_price = $get_inventory['client_price'];
        $purchase_order_price = $get_inventory['purchase_order_price'];
        $sales_order_price = $get_inventory['sales_order_price'];
        $minimum_billable = $get_inventory['minimum_billable'];
        $estimated_hours = $get_inventory['estimated_hours'];
        $actual_hours = $get_inventory['actual_hours'];
        $msrp = $get_inventory['msrp'];
        $quote_description = $get_inventory['quote_description'];

        $id_number = $get_inventory['id_number'];
        $operator = $get_inventory['operator'];
        $lsd = $get_inventory['lsd'];
        $quantity = $get_inventory['quantity'];

        $usd_invoice = $get_inventory['usd_invoice'];
        $shipping_rate = $get_inventory['shipping_rate'];
        $shipping_cash = $get_inventory['shipping_cash'];
        $exchange_rate = $get_inventory['exchange_rate'];
        $exchange_cash = $get_inventory['exchange_cash'];
        $cdn_cpu = $get_inventory['cdn_cpu'];
        $cogs_total = $get_inventory['cogs_total'];

        $stocking_units = $get_inventory['stocking_units'];
        $selling_units  = $get_inventory['selling_units'];
        $buying_units = $get_inventory['buying_units'];
        $location = $get_inventory['location'];
        $asset = $get_inventory['asset'];
        $revenue = $get_inventory['revenue'];
        $inv_variance = $get_inventory['inv_variance'];
        $web_price = $get_inventory['web_price'];
        $average_cost = $get_inventory['average_cost'];
        $preferred_price = $get_inventory['preferred_price'];
        $product_name = $get_inventory['product_name'];
        $cost = $get_inventory['cost'];
        $usd_cpu = $get_inventory['usd_cpu'];
        $commission_price = $get_inventory['commission_price'];
        $markup_perc = $get_inventory['markup_perc'];
        $current_inventory = $get_inventory['current_inventory'];
        $write_offs = $get_inventory['write_offs'];
        $min_max = $get_inventory['min_max'];
        $status = $get_inventory['status'];
        $note = $get_inventory['note'];
        $suggested_retail_price = $get_inventory['suggested_retail_price'];
        $rush_price = $get_inventory['rush_price'];
        $min_amount = $get_inventory['min_amount'];
        $max_amount = $get_inventory['max_amount'];

        $unit_price = $get_inventory['unit_price'];
        $unit_cost = $get_inventory['unit_cost'];
        $rent_price = $get_inventory['rent_price'];
        $rental_days = $get_inventory['rental_days'];
        $rental_weeks = $get_inventory['rental_weeks'];
        $rental_months = $get_inventory['rental_months'];
        $rental_years = $get_inventory['rental_years'];
        $reminder_alert = $get_inventory['reminder_alert'];
        $daily = $get_inventory['daily'];
        $weekly = $get_inventory['weekly'];
        $monthly = $get_inventory['monthly'];
        $annually = $get_inventory['annually'];
        $total_days = $get_inventory['total_days'];
        $total_hours = $get_inventory['total_hours'];
        $total_km = $get_inventory['total_km'];
        $total_miles = $get_inventory['total_miles'];
        $include_in_po = $get_inventory['include_in_po'];
        $include_in_so = $get_inventory['include_in_so'];
        $include_in_pos = $get_inventory['include_in_pos'];
        
        if ( empty($product_name) ) {
            $product_name = $name;
        }
    } ?>
    
    <div class="container">
        <div class="row">

            <form id="form1" name="form1" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
                <h3 class="inline pad-left"><?= !empty($_GET['inventoryid']) ? 'Edit' : 'Add' ?> Product</h3>
                <div class="pull-right pad-top"><a href=""><img src="../img/icons/ROOK-status-rejected.jpg" alt="Close" title="Close" class="inline-img" /></a></div>
                <div class="clearfix"></div><?php
                
                if ( !empty($inventoryid) ) { ?>
                    <input type="hidden" id="inventoryid" name="inventoryid" value="<?= $inventoryid ?>" /><?php
                } ?>
                <input type="hidden" id="category" name="category" value="<?= $category ?>" />

                <div id="accordion2" class="sidebar panel-group block-panels main-screen" style="background-color: #fff; padding: 0; margin-left: 0.5em; width: calc(100% - 1em);"><?php
                    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tile_name`='vendors' AND `subtab`='**no_subtab**'"));
                    $value_config = ','.$get_field_config['contacts'].','; ?>
                    
                    <?php if (strpos($value_config, ','."VPL Description".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_description">Description<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_description" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Name".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Name<span class="brand-color">*</span>:</label>
                                            <div class="col-sm-8"><input name="name" type="text" value="<?= $name; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Product Name".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Product Name<span class="brand-color">*</span>:</label>
                                            <div class="col-sm-8"><input name="product_name" type="text" value="<?= $product_name; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Category".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="travel_task" class="col-sm-4 control-label">Category<span class="brand-color">*</span>:</label>
                                            <div class="col-sm-8">
                                                <select id="category" name="category" class="chosen-select-deselect form-control" width="380">
                                                    <option></option><?php
                                                    $tabs = mysqli_query($dbc, "SELECT DISTINCT(`category`) FROM `vendor_price_list` WHERE `deleted`=0");
                                                    while ( $row=mysqli_fetch_assoc($tabs) ) {
                                                        $selected = ($category == $row['category']) ? 'selected="selected"' : '';
                                                        echo '<option '.$selected.' value="'. $row['category'] .'">'. $row['category'] .'</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Subcategory".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="travel_task" class="col-sm-4 control-label">Subcategory<span class="brand-color">*</span>:</label>
                                            <div class="col-sm-8">
                                                <select id="sub_category" name="sub_category" class="chosen-select-deselect form-control" width="380">
                                                    <option></option><?php
                                                    $tabs = mysqli_query($dbc, "SELECT DISTINCT(`sub_category`) FROM `vendor_price_list` WHERE `deleted`=0");
                                                    while ( $row=mysqli_fetch_assoc($tabs) ) {
                                                        $selected = ($sub_category == $row['sub_category']) ? 'selected="selected"' : '';
                                                        echo '<option '.$selected.' value="'.$row['sub_category'].'">'. $row['sub_category'] .'</option>';
                                                    } ?>
                                                    <option value="Other">Other</option>
                                              </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="travel_task" class="col-sm-4 control-label"></label>
                                            <div class="col-sm-8"><input name="sub_category_name" id="sub_category_name" type="text" class="form-control" style="display: none;"/></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Color".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="phone_number" class="col-sm-4 control-label">Color:</label>
                                            <div class="col-sm-8"><input name="color" type="text" value="<?= $color; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Type".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="phone_number" class="col-sm-4 control-label">Type:</label>
                                            <div class="col-sm-8">
                                                <select data-placeholder="Choose a Type..." id="type" name="type" class="chosen-select-deselect form-control" width="380">
                                                    <option></option>
                                                    <option <?php if ($type=='Project Inventory') echo 'selected="selected"';?> value="Project Inventory">Project Inventory</option>
                                                    <option <?php if ($type=='Consumables') echo 'selected="selected"';?> value="Consumables">Consumables</option>
                                                    <option <?php if ($type=='Inventory') echo 'selected="selected"';?> value="Inventory" >Inventory</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Description".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Description:</label>
                                            <div class="col-sm-8"><textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Unique Identifier".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_unique">Unique Identifier<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_unique" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Code".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Code<span class="brand-color">*</span>:</label>
                                            <div class="col-sm-8"><input name="code" type="text" value="<?= $code; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."ID #".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">ID #:</label>
                                            <div class="col-sm-8"><input name="id_number" type="text" value="<?= $id_number; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Item SKU".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Item SKU:</label>
                                            <div class="col-sm-8"><input name="item_sku" type="text" value="<?= $item_sku; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>

                                    <?php if (strpos($value_config, ','."Part #".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Part #:</label>
                                            <div class="col-sm-8"><input name="part_no" type="text" value="<?= $part_no; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Product Cost".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_product_cost">Product Cost<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_product_cost" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Average Cost".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Average Cost:</label>
                                            <div class="col-sm-8"><input name="average_cost" type="text" value="<?= $average_cost; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."CDN Cost Per Unit".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">CDN Cost Per Unit:</label>
                                            <div class="col-sm-8"><input name="cdn_cpu" type="text" id="cpu" value="<?= $cdn_cpu; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."COGS".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">COGS GL Code:</label>
                                            <div class="col-sm-8"><input name="cogs_total" type="text" id="cogs" value="<?= $cogs_total; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Cost".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Cost:</label>
                                            <div class="col-sm-8"><input name="cost" type="text" value="<?= $cost; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."USD Cost Per Unit".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">USD Cost Per Unit:</label>
                                            <div class="col-sm-8"><input name="usd_cpu" type="text" id="cpu" value="<?= $usd_cpu; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."USD Invoice".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">USD Invoice:</label>
                                            <div class="col-sm-8"><input name="usd_invoice" type="text" id="usdinvoice" value="<?= $usd_invoice; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Purchase Info".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_purchase_info">Purchase Info<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_purchase_info" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Date of Purchase".',') !== false) { ?>
                                        <div class="form-group clearfix completion_date">
                                            <label for="first_name" class="col-sm-4 control-label text-right">Date of Purchase:</label>
                                            <div class="col-sm-8"><input name="date_of_purchase" value="<?= $date_of_purchase; ?>" type="text" class="datepicker"></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Purchase Cost".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Purchase Cost:</label>
                                            <div class="col-sm-8"><input name="purchase_cost" type="text" value="<?= $purchase_cost; ?>" class="form-control"/></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Vendor".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Vendor:</label>
                                            <div class="col-sm-8">
                                                <select data-placeholder="Choose a Vendor..." id="vendor" name="vendorid" class="chosen-select-deselect form-control" width="380">
                                                    <option></option><?php
                                                    $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Vendor' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
                                                    foreach($query as $id) {
                                                        $selected = '';
                                                        $selected = $id == $vendorid ? 'selected = "selected"' : '';
                                                        echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Shipping Receiving".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_shipping_receiving">Shipping &amp; Receiving<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_shipping_receiving" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Exchange $".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Exchange $:</label>
                                            <div class="col-sm-8"><input name="exchange_cash" type="text" id="exchangecash" value="<?= $exchange_cash; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Exchange Rate".',') !== false) { ?>
                                        <div class="form-group">
                                        <label for="site_name" class="col-sm-4 control-label">Exchange Rate:</label>
                                        <div class="col-sm-8"><input name="exchange_rate" type="text" id="exchangerate" value="<?= $exchange_rate; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Freight Charge".',') !== false) { ?>
                                        <div class="form-group">
                                        <label for="fax_number" class="col-sm-4 control-label">Freight Charge:</label>
                                        <div class="col-sm-8"><input name="freight_charge" type="text" value="<?= $freight_charge; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Shipping Cash".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Shipping Cash:</label>
                                            <div class="col-sm-8"><input name="shipping_cash" type="text" value="<?= $shipping_cash; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Shipping Rate".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Shipping Rate:</label>
                                            <div class="col-sm-8"><input name="shipping_rate" type="text" id='ship_rate_' value="<?= $shipping_rate; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Pricing".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pricing">Pricing<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_pricing" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Admin Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
                                            <div class="col-sm-8"><input name="admin_price" value="<?= $admin_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Client Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
                                            <div class="col-sm-8"><input name="client_price" value="<?= $client_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Commercial Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
                                            <div class="col-sm-8"><input name="commercial_price" value="<?= $commercial_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Commission Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Commission Price:</label>
                                            <div class="col-sm-8"><input name="commission_price" type="text" id="commission_price" value="<?= $commission_price; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Final Retail Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
                                            <div class="col-sm-8"><input name="final_retail_price" value="<?= $final_retail_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."MSRP".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
                                            <div class="col-sm-8"><input name="msrp" value="<?= $msrp; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Preferred Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Preferred Price</label>
                                            <div class="col-sm-8"><input name="preferred_price" type="text" id="preferred_price" value="<?= $preferred_price; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Purchase Order Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Purchase Order Price:</label>
                                            <div class="col-sm-8"><input name="purchase_order_price" value="<?= $purchase_order_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Rush Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Rush Price:</label>
                                            <div class="col-sm-8"><input name="rush_price" value="<?= $rush_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Sales Order Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label"><?= SALES_ORDER_NOUN ?> Price:</label>
                                            <div class="col-sm-8"><input name="sales_order_price" value="<?= $sales_order_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Sell Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Sell Price:</label>
                                            <div class="col-sm-8"><input name="sell_price" type="text" value="<?= $sell_price; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Suggested Retail Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Suggested Retail Price:</label>
                                            <div class="col-sm-8"><input name="suggested_retail_price" value="<?= $suggested_retail_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Unit Cost".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Unit Cost:</label>
                                            <div class="col-sm-8"><input name="unit_cost" value="<?= $unit_cost; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Unit Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Unit Price:</label>
                                            <div class="col-sm-8"><input name="unit_price" value="<?= $unit_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Web Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Web Price:</label>
                                            <div class="col-sm-8"><input name="web_price" type="text" id="web_price" value="<?= $web_price; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Wholesale Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
                                            <div class="col-sm-8"><input name="wholesale_price" value="<?= $wholesale_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Markup".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_markup">Markup<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_markup" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Markup By $".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Markup By $:</label>
                                            <div class="col-sm-8"><input name="markup" type="text" value="<?= $markup; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Markup By %".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Markup By %:</label>
                                            <div class="col-sm-8"><input name="markup_perc" type="text" value="<?= $markup_perc; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Stock".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_stock">Stock<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_stock" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Buying Units".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Buying Units:</label>
                                            <div class="col-sm-8"><input name="buying_units" type="text" value="<?= $buying_units; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Current Inventory".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Current Inventory:</label>
                                            <div class="col-sm-8"><input name="current_inventory" type="text" value="<?= $current_inventory; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Current Stock".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Current Stock:</label>
                                            <div class="col-sm-8"><input name="current_stock" type="text" value="<?= $current_stock; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Variance".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">GL Code:</label>
                                            <div class="col-sm-8"><input name="inv_variance" type="text" id="inv_variance" value="<?= $inv_variance; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Quantity".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Quantity:</label>
                                            <div class="col-sm-8"><input name="quantity" type="text" value="<?= $quantity; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Selling Units".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Selling Units:</label>
                                            <div class="col-sm-8"><input name="selling_units" type="text" value="<?= $selling_units; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Stocking Units".',') !== false) { ?>
                                    <div class="form-group">
                                        <label for="site_name" class="col-sm-4 control-label">Stocking Units:</label>
                                        <div class="col-sm-8"><input name="stocking_units" type="text" value="<?= $stocking_units; ?>" class="form-control" /></div>
                                    </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Write-offs".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Write-offs:</label>
                                            <div class="col-sm-8"><input name="write_offs" type="text" value="<?= $write_offs; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Location".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_location">Location<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_location" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Location".',') !== false) { ?>
                                    <div class="form-group">
                                        <label for="site_name" class="col-sm-4 control-label">Location:</label>
                                        <div class="col-sm-8"><input name="location" type="text" value="<?= $location; ?>" class="form-control" /></div>
                                    </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."LSD".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">LSD:</label>
                                            <div class="col-sm-8"><input name="lsd" type="text" value="<?= $lsd; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Dimensions".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dimensions">Dimensions<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_dimensions" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Size".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="travel_task" class="col-sm-4 control-label">Size:</label>
                                            <div class="col-sm-8">
                                                <select id="size" name="size" class="chosen-select-deselect form-control" width="380">
                                                    <option></option><?php
                                                    $result3 = mysqli_query($dbc, "SELECT DISTINCT(`size`) FROM `vendor_price_list` WHERE `deleted`=0");
                                                    while ($row3=mysqli_fetch_assoc($result3) ) {
                                                        $selected = ($size == $row3['size']) ? 'selected="selected"' : '';
                                                        echo '<option '.$selected.' value="'.$row3['size'].'">'.$row3['size'].'</option>';
                                                    } ?>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="travel_task" class="col-sm-4 control-label"></label>
                                            <div class="col-sm-8"><input name="size_name" id="size_name" type="text" class="form-control" style="display: none;" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Weight".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="travel_task" class="col-sm-4 control-label">Weight:</label>
                                            <div class="col-sm-8">
                                                <select id="weight" name="weight" class="chosen-select-deselect form-control" width="380">
                                                    <option></option><?php
                                                    $result4 = mysqli_query($dbc, "SELECT DISTINCT(`weight`) FROM `vendor_price_list` WHERE `deleted`=0");
                                                    while ( $row4=mysqli_fetch_assoc($result4) ) {
                                                        $selected = ($weight == $row4['weight']) ? 'selected="selected"' : '';
                                                        echo '<option '.$selected.' value="'.$row4['weight'].'">'.$row4['weight'].'</option>';
                                                    } ?>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="travel_task" class="col-sm-4 control-label"></label>
                                            <div class="col-sm-8"><input name="weight_name" id="weight_name" type="text" class="form-control" style="display: none;" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Alerts".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_alerts">Alerts<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_alerts" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Min Bin".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Min Bin:</label>
                                            <div class="col-sm-8"><input name="min_bin" type="text" value="<?= $min_bin; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Min Max".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Min Max:</label>
                                            <div class="col-sm-8"><input name="min_max" type="text" value="<?= $min_max; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Time Allocation".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_time_allocation">Time Allocation<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_time_allocation" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Actual Hours".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
                                            <div class="col-sm-8"><input name="actual_hours" value="<?= $actual_hours; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Estimated Hours".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
                                            <div class="col-sm-8"><input name="estimated_hours" value="<?= $estimated_hours; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Admin Fees".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_admin_fees">Admin Fees<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_admin_fees" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."GL Assets".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">GL Assets:</label>
                                            <div class="col-sm-8"><input name="asset" type="text" value="<?= $asset; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."GL Revenue".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">GL Revenue:</label>
                                            <div class="col-sm-8"><input name="revenue" type="text" value="<?= $revenue; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Minimum Billable".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
                                            <div class="col-sm-8"><input name="minimum_billable" value="<?= $minimum_billable; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Quote".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_quote">Quote<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_quote" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Quote Description".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
                                            <div class="col-sm-8"><textarea name="quote_description" rows="5" cols="50" class="form-control"><?php echo $quote_description; ?></textarea></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Status".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_status">Status<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_status" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Status".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Status:</label>
                                            <div class="col-sm-8">
                                                <select data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
                                                    <option></option>
                                                    <option <?php if ($status == "Active") { echo " selected"; } ?> value="Active">Active</option>
                                                    <option <?php if ($status == "Inactive") { echo " selected"; } ?> value="Inactive">Inactive</option>
                                                    <option <?php if ($status == "In inventory") { echo " selected"; } ?> value="In inventory">In inventory</option>
                                                    <option <?php if ($status == "In transit from vendor") { echo " selected"; } ?> value="In transit from vendor">In transit from vendor</option>
                                                    <option <?php if ($status == "In transit between yards") { echo " selected"; } ?> value="In transit between yards">In transit between yards</option>
                                                    <option <?php if ($status == "Not confirmed in yard by inventory check") { echo " selected"; } ?> value="Not confirmed in yard by inventory check">Not confirmed in yard by inventory check</option>
                                                    <option <?php if ($status == "Assigned to job") { echo " selected"; } ?> value="Assigned to job">Assigned to job</option>
                                                    <option <?php if ($status == "In transit and assigned") { echo " selected"; } ?> value="In transit and assigned">In transit and assigned</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Display On Website".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_display_website">Display On Website<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_display_website" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Display On Website".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="site_name" class="col-sm-4 control-label">Display On Website:</label>
                                            <div class="col-sm-8">
                                                <label class="pad-right"><input type="radio" <?php if ($display_website == "Yes") { echo " checked"; } ?> name="display_website" value="Yes">Yes</label>
                                                <label class="pad-right"><input type="radio" <?php if ($display_website == "No") { echo " checked"; } ?> name="display_website" value="No">No</label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL General".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_general">General<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_general" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Comments".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Comment:</label>
                                            <div class="col-sm-8"><textarea name="comment" rows="5" cols="50" class="form-control"><?php echo $comment; ?></textarea></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Notes".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Notes:</label>
                                            <div class="col-sm-8"><textarea name="note" rows="5" cols="50" class="form-control"><?php echo $note; ?></textarea></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Rental".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_rental">Rental<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_rental" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Reminder/Alert".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Reminder/Alert:</label>
                                            <div class="col-sm-8"><input name="reminder_alert" value="<?= $reminder_alert; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Rent Price".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Rent Price:</label>
                                            <div class="col-sm-8"><input name="rent_price" value="<?= $rent_price; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Rental Days".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Rental Days:</label>
                                            <div class="col-sm-8"><input name="rental_days" value="<?= $rental_days; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Rental Weeks".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Rental Weeks:</label>
                                            <div class="col-sm-8"><input name="rental_weeks" value="<?= $rental_weeks; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Rental Months".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Rental Months:</label>
                                            <div class="col-sm-8"><input name="rental_months" value="<?= $rental_months; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Rental Years".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Rental Years:</label>
                                            <div class="col-sm-8"><input name="rental_years" value="<?= $rental_years; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Day/Week/Month/Year".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_day_week_month_year">Day/Week/Month/Year<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_day_week_month_year" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."#Of Hours".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">#Of Hours:</label>
                                            <div class="col-sm-8"><input name="total_hours" value="<?= $total_hours; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."#Of Days".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">#Of Days:</label>
                                            <div class="col-sm-8"><input name="total_days" value="<?= $total_days; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Daily".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Daily:</label>
                                            <div class="col-sm-8"><input name="daily" value="<?= $daily; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Weekly".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Weekly:</label>
                                            <div class="col-sm-8"><input name="weekly" value="<?= $weekly; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Monthly".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Monthly:</label>
                                            <div class="col-sm-8"><input name="monthly" value="<?= $monthly; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Annually".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Annually:</label>
                                            <div class="col-sm-8"><input name="annually" value="<?= $annually; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Vehicle".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_vehicle">Vehicle<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_vehicle" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."#Of Kilometers".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">#Of Kilometers:</label>
                                            <div class="col-sm-8"><input name="total_km" value="<?= $total_km; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."#Of Miles".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">#Of Miles:</label>
                                            <div class="col-sm-8"><input name="total_miles" value="<?= $total_miles; ?>" type="text" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Inclusion".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_inclusions">Inclusions<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_inclusions" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Include in P.O.S.".',') !== false) { ?>
                                    <div class="form-group">
                                        <label for="company_name" class="col-sm-4 control-label">Include in Point of Sale:</label>
                                            <div class="col-sm-8"><input type='checkbox' <?php if($include_in_pos !== '' && $include_in_pos !== NULL) { echo "checked"; } ?> name='include_in_pos' class='' value='1' /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Include in Purchase Orders".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Include in Purchase Orders:</label>
                                            <div class="col-sm-8"><input type='checkbox' <?php if($include_in_po !== '' && $include_in_po !== NULL) { echo "checked"; } ?> name='include_in_po' class='' value='1' /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Include in Sales Orders".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="company_name" class="col-sm-4 control-label">Include in <?= SALES_ORDER_TILE ?>:</label>
                                            <div class="col-sm-8"><input type='checkbox' <?php if($include_in_so !== '' && $include_in_so !== NULL) { echo "checked"; } ?> name='include_in_so' class='' value='1' /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                    
                    <?php if (strpos($value_config, ','."VPL Amount".',') !== false) { ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_amount">Amount<span class="glyphicon glyphicon-plus"></span></a>
                                </h4>
                            </div>
                            <div id="collapse_amount" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <?php if (strpos($value_config, ','."Min Amount".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Min Amount:</label>
                                            <div class="col-sm-8"><input name="min_amount" type="text" value="<?= $min_amount; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($value_config, ','."Max Amount".',') !== false) { ?>
                                        <div class="form-group">
                                            <label for="fax_number" class="col-sm-4 control-label">Max Amount:</label>
                                            <div class="col-sm-8"><input name="max_amount" type="text" value="<?= $max_amount; ?>" class="form-control" /></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div><!-- .panel .panel-default -->
                    <?php } ?>
                </div><!-- .panel-group -->

                <div class="form-group pull-right gap-top gap-right">
                    <a href="" class="btn brand-btn">Cancel</a>
                    <button type="submit" name="submit" value="Submit" class="btn brand-btn pull-right">Submit</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div><!-- .row -->
    </div><!-- .container -->

<?php include ('../footer.php'); ?>