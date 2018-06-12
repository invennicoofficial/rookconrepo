<?php
/*
Add	Asset
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $code = filter_var($_POST['code'],FILTER_SANITIZE_STRING);
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

    $vendorid =	$_POST['vendorid'];
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
    $type =	filter_var($_POST['type'],FILTER_SANITIZE_STRING);
    $date_of_purchase =	filter_var($_POST['date_of_purchase'],FILTER_SANITIZE_STRING);
    $purchase_cost =	filter_var($_POST['purchase_cost'],FILTER_SANITIZE_STRING);
    $sell_price =	filter_var($_POST['sell_price'],FILTER_SANITIZE_STRING);
    $markup =	filter_var($_POST['markup'],FILTER_SANITIZE_STRING);
    $freight_charge =	filter_var($_POST['freight_charge'],FILTER_SANITIZE_STRING);
    $min_bin =	filter_var($_POST['min_bin'],FILTER_SANITIZE_STRING);
    $current_stock =	filter_var($_POST['current_stock'],FILTER_SANITIZE_STRING);

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
    $minimum_billable = filter_var($_POST['minimum_billable'],FILTER_SANITIZE_STRING);
    $estimated_hours = filter_var($_POST['estimated_hours'],FILTER_SANITIZE_STRING);
    $actual_hours = filter_var($_POST['actual_hours'],FILTER_SANITIZE_STRING);
    $msrp = filter_var($_POST['msrp'],FILTER_SANITIZE_STRING);

    $product_name = filter_var($_POST['product_name'],FILTER_SANITIZE_STRING);
    $cost = filter_var($_POST['cost'],FILTER_SANITIZE_STRING);
    $usd_cpu = filter_var($_POST['usd_cpu'],FILTER_SANITIZE_STRING);
    $commission_price = filter_var($_POST['commission_price'],FILTER_SANITIZE_STRING);
    $markup_perc = filter_var($_POST['markup_perc'],FILTER_SANITIZE_STRING);
    $current_asset = filter_var($_POST['current_asset'],FILTER_SANITIZE_STRING);
    $write_offs = filter_var($_POST['write_offs'],FILTER_SANITIZE_STRING);
    $min_max = filter_var($_POST['min_max'],FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'],FILTER_SANITIZE_STRING);

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
    $name =	filter_var($_POST['name'],FILTER_SANITIZE_STRING);
    $usd_invoice = filter_var($_POST['usd_invoice'],FILTER_SANITIZE_STRING);
    $shipping_rate =	filter_var($_POST['shipping_rate'],FILTER_SANITIZE_STRING);
    $shipping_cash =	filter_var($_POST['shipping_cash'],FILTER_SANITIZE_STRING);
    $exchange_rate =	filter_var($_POST['exchange_rate'],FILTER_SANITIZE_STRING);
    $exchange_cash =	filter_var($_POST['exchange_cash'],FILTER_SANITIZE_STRING);
    $cdn_cpu =	filter_var($_POST['cdn_cpu'],FILTER_SANITIZE_STRING);
    $cogs_total =	filter_var($_POST['cogs_total'],FILTER_SANITIZE_STRING);

    if(empty($_POST['assetid'])) {

        $query_insert_asset = "INSERT INTO `asset` (`code`, `category`, `sub_category`, `part_no`,	`description`, `comment`, `question`, `request`, `display_website`, `vendorid`, `size`, `weight`, `type`, `name`, `date_of_purchase`, `purchase_cost`, `sell_price`, `markup`, `freight_charge`, `min_bin`, `current_stock`, `final_retail_price`, `admin_price`, `wholesale_price`, `commercial_price`, `client_price`, `minimum_billable`, `estimated_hours`, `actual_hours`, `msrp`, `quote_description`, `usd_invoice`, `shipping_rate`, `shipping_cash`, `exchange_rate`, `exchange_cash`, `cdn_cpu`, `cogs_total`, `location`, `inv_variance`, `average_cost`, `asset`, `revenue`, `buying_units`, `selling_units`, `stocking_units`, `preferred_price`, `web_price`, `id_number`, `operator`, `lsd`, `quantity`, `product_name`, `cost`, `usd_cpu`, `commission_price`, `markup_perc`, `current_asset`, `write_offs`, `min_max`, `status`, `note`, `unit_price`, `unit_cost`, `rent_price`, `rental_days`, `rental_weeks`, `rental_months`, `rental_years`, `reminder_alert`, `daily`,`weekly`, `monthly`, `annually`,  `total_days`, `total_hours`, `total_km`, `total_miles`
        ) VALUES ('$code', '$category', '$sub_category', '$part_no', '$description', '$comment', '$question', '$request', '$display_website', '$vendorid', '$size', '$weight', '$type', '$name', '$date_of_purchase', '$purchase_cost', '$sell_price', '$markup', '$freight_charge', '$min_bin', '$current_stock', '$final_retail_price', '$admin_price', '$wholesale_price', '$commercial_price', '$client_price', '$minimum_billable', '$estimated_hours', '$actual_hours', '$msrp', '$quote_description', '$usd_invoice', '$shipping_rate', '$shipping_cash', '$exchange_rate', '$exchange_cash', '$cdn_cpu', '$cogs_total', '$location', '$inv_variance', '$average_cost', '$asset', '$revenue', '$buying_units', '$selling_units', '$stocking_units', '$preferred_price', '$web_price', '$id_number', '$operator', '$lsd', '$quantity', '$product_name', '$cost', '$usd_cpu', '$commission_price', '$markup_perc', '$current_asset', '$write_offs', '$min_max', '$status', '$note', '$unit_price', '$unit_cost', '$rent_price', '$rental_days', '$rental_weeks', '$rental_months', '$rental_years', '$reminder_alert', '$daily', '$weekly', '$monthly', '$annually', '$total_days', '$total_hours', '$total_km', '$total_miles')";
        $result_insert_asset = mysqli_query($dbc, $query_insert_asset);
        $url = 'Added';

    } else {
        $assetid = $_POST['assetid'];
        $query_update_asset = "UPDATE `asset` SET `code` = '$code', `category` = '$category', `sub_category` = '$sub_category', `part_no` = '$part_no', `description`	= '$description', `comment`	= '$comment', `question`	= '$question', `request`	= '$request', `display_website` = '$display_website', `vendorid` = '$vendorid', `size` = '$size', `weight` = '$weight', `type`	= '$type', `name` = '$name', `date_of_purchase` = '$date_of_purchase', `purchase_cost` = '$purchase_cost', `sell_price` = '$sell_price', `markup` = '$markup', `freight_charge` = '$freight_charge', `min_bin` = '$min_bin', `current_stock` = '$current_stock', `final_retail_price` = '$final_retail_price', `admin_price` = '$admin_price', `wholesale_price` = '$wholesale_price', `commercial_price` = '$commercial_price', `client_price` = '$client_price', `minimum_billable` = '$minimum_billable', `estimated_hours` = '$estimated_hours', `actual_hours` = '$actual_hours', `msrp` = '$msrp', `quote_description` = '$quote_description', `usd_invoice` = '$usd_invoice', `shipping_rate` = '$shipping_rate', `shipping_cash` = '$shipping_cash', `exchange_rate` = '$exchange_rate', `exchange_cash` = '$exchange_cash', `cdn_cpu` = '$cdn_cpu', `cogs_total` = '$cogs_total', `location` = '$location', `inv_variance` = '$inv_variance', `average_cost` = '$average_cost', `asset` = '$asset', `revenue` = '$revenue', `buying_units` = '$buying_units', `selling_units` = '$selling_units', `stocking_units` = '$stocking_units', `preferred_price` = '$preferred_price', `web_price` = '$web_price', `id_number` = '$id_number', `operator` = '$operator', `lsd` = '$lsd', `quantity` = '$quantity', `product_name` = '$product_name', `cost` = '$cost', `usd_cpu` = '$usd_cpu', `commission_price` = '$commission_price', `markup_perc` = '$markup_perc', `current_asset` = '$current_asset', `write_offs` = '$write_offs', `min_max` = '$min_max', `status` = '$status', `note` = '$note', `unit_price` = '$unit_price', `unit_cost` = '$unit_cost', `rent_price` = '$rent_price', `rental_days` = '$rental_days', `rental_weeks` = '$rental_weeks', `rental_months` = '$rental_months', `rental_years` = '$rental_years', `reminder_alert` = '$reminder_alert', `daily` = '$daily', `weekly` = '$weekly', `monthly` = '$monthly', `annually` = '$annually', `total_days` = '$total_days', `total_hours` = '$total_hours', `total_km` = '$total_km', `total_miles` = '$total_miles' WHERE `assetid` = '$assetid'";

        $result_update_asset	= mysqli_query($dbc, $query_update_asset);
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("asset.php?category='.$category.'"); </script>';

   // mysqli_close($dbc); //Close the DB Connection
}

?>
<script type="text/javascript">
    $(document).ready(function () {

        $("#form1").submit(function( event ) {
            var category = $("#category").val();
            var sub_category = $("#sub_category").val();

            var code = $("input[name=code]").val();
            var name = $("input[name=name]").val();
            var category_name = $("input[name=category_name]").val();
            var sub_category_name = $("input[name=sub_category_name]").val();

            if (code == '' || category == '' || sub_category == '' || name == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
            if(((category == 'Other') && (category_name == '')) || ((sub_category == 'Other') && (sub_category_name == ''))) {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        });

        // $("#category").change(function() {
        //     if($( "#category option:selected" ).text() == 'Other') {
        //             $( "#category_name" ).show();
        //     } else {
        //         $( "#category_name" ).hide();
        //     }
        // });
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
$(document).on('change', 'select[name="sub_category"]', function() { selectSubCategory(this); });
$(document).on('change', 'select[name="category"]', function() { selectCategory(this); });
function selectCategory(sel) {
    window.location.href = '?category='+sel.value;
}

</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('assets');
$category = $_GET['category'];
?>
<div class="container">
  <div class="row">

		<h1>Add An Asset</h1>
		<div class="gap-top double-gap-bottom"><a href="asset.php?category=<?php echo $category; ?>" class="btn config-btn">Back to Dashboard</a></div>

		<form id="form1" name="form1" method="post"	action="add_asset.php" enctype="multipart/form-data" class="form-horizontal" role="form">

		<?php
        $code = '';
		$sub_category = '';
        $part_no = '';
		$description =	'';
        $comment = '';
        $question = '';
        $request = '';
        $display_website = '';
        $vendorid = '';
		$size =	'';
        $weight = '';
		$type	= '';
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
        $shipping_rate =	'';
        $shipping_cash =	'';
        $exchange_rate =	'';
        $exchange_cash =	'';
        $cdn_cpu =	'';
        $cogs_total =	'';

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

        $product_name = '';
        $cost = '';
        $usd_cpu = '';
        $commission_price = '';
        $markup_perc = '';
        $current_asset = '';
        $write_offs = '';
        $min_max = '';
        $status = '';
        $note = '';

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

		if(!empty($_GET['assetid']))	{

			$assetid = $_GET['assetid'];
			$get_asset =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM	asset WHERE	assetid='$assetid'"));
            $code = $get_asset['code'];
            $category = $get_asset['category'];
            $sub_category	= $get_asset['sub_category'];
            $part_no = $get_asset['part_no'];
            $description =	$get_asset['description'];
            $comment = $get_asset['comment'];
            $question = $get_asset['question'];
            $request = $get_asset['request'];
            $display_website = $get_asset['display_website'];
            $vendorid =	$get_asset['vendorid'];
            $size = $get_asset['size'];
            $weight = $get_asset['weight'];
            $type =	$get_asset['type'];
            $name =	$get_asset['name'];
            $date_of_purchase =	$get_asset['date_of_purchase'];
            $purchase_cost =	$get_asset['purchase_cost'];
            $sell_price =	$get_asset['sell_price'];
            $markup =	$get_asset['markup'];
            $freight_charge =	$get_asset['freight_charge'];
            $min_bin =	$get_asset['min_bin'];
            $current_stock =	$get_asset['current_stock'];
            $final_retail_price = $get_asset['final_retail_price'];
            $admin_price = $get_asset['admin_price'];
            $wholesale_price = $get_asset['wholesale_price'];
            $commercial_price = $get_asset['commercial_price'];
            $client_price = $get_asset['client_price'];
            $minimum_billable = $get_asset['minimum_billable'];
            $estimated_hours = $get_asset['estimated_hours'];
            $actual_hours = $get_asset['actual_hours'];
            $msrp = $get_asset['msrp'];
            $quote_description = $get_asset['quote_description'];

            $id_number = $get_asset['id_number'];
            $operator = $get_asset['operator'];
            $lsd = $get_asset['lsd'];
            $quantity = $get_asset['quantity'];

            $usd_invoice = $get_asset['usd_invoice'];
            $shipping_rate =	$get_asset['shipping_rate'];
            $shipping_cash =	$get_asset['shipping_cash'];
            $exchange_rate =	$get_asset['exchange_rate'];
            $exchange_cash =	$get_asset['exchange_cash'];
            $cdn_cpu =	$get_asset['cdn_cpu'];
            $cogs_total =	$get_asset['cogs_total'];

            $stocking_units = $get_asset['stocking_units'];
            $selling_units  = $get_asset['selling_units'];
            $buying_units = $get_asset['buying_units'];
            $location = $get_asset['location'];
            $asset = $get_asset['asset'];
            $revenue = $get_asset['revenue'];
            $inv_variance = $get_asset['inv_variance'];
            $web_price = $get_asset['web_price'];
            $average_cost = $get_asset['average_cost'];
            $preferred_price = $get_asset['preferred_price'];
            $product_name = $get_asset['product_name'];
            $cost = $get_asset['cost'];
            $usd_cpu = $get_asset['usd_cpu'];
            $commission_price = $get_asset['commission_price'];
            $markup_perc = $get_asset['markup_perc'];
            $current_asset = $get_asset['current_asset'];
            $write_offs = $get_asset['write_offs'];
            $min_max = $get_asset['min_max'];
            $status = $get_asset['status'];
            $note = $get_asset['note'];

            $unit_price = $get_asset['unit_price'];
            $unit_cost = $get_asset['unit_cost'];
            $rent_price = $get_asset['rent_price'];
            $rental_days = $get_asset['rental_days'];
            $rental_weeks = $get_asset['rental_weeks'];
            $rental_months = $get_asset['rental_months'];
            $rental_years = $get_asset['rental_years'];
            $reminder_alert = $get_asset['reminder_alert'];
            $daily = $get_asset['daily'];
            $weekly = $get_asset['weekly'];
            $monthly = $get_asset['monthly'];
            $annually = $get_asset['annually'];
            $total_days = $get_asset['total_days'];
            $total_hours = $get_asset['total_hours'];
            $total_km = $get_asset['total_km'];
            $total_miles = $get_asset['total_miles'];

		?>
		<input type="hidden" id="assetid"	name="assetid" value="<?php echo $assetid ?>" />
		<?php	} ?>
		<!-- <input type="hidden" id="category"	name="category" value="<?php echo $category ?>" /> -->

        <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_category" >
                        Category<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_category" class="panel-collapse collapse in">
                <div class="panel-body">
                    <label class="col-sm-4 control-label">Category:</label>
                    <div class="col-sm-8">
                        <select name="category" id="category" class="chosen-select-deselect form-control">
                            <option></option>
                            <?php $categories = explode(',', get_config($dbc, 'asset_tabs'));
                            foreach ($categories as $asset_tab) {
                                echo '<option value="'.$asset_tab.'" '.($category == $asset_tab ? 'selected' : '').'>'.$asset_tab.'</option>';
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $query = mysqli_query($dbc,"SELECT accordion FROM field_config_asset WHERE	tab='$category' AND accordion IS NOT NULL AND `order` IS NOT NULL ORDER BY `order`");

        $j=0;
        if(!empty($category)) {
            while($row = mysqli_fetch_array($query)) {
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?php echo $j;?>" >
                            <?php echo $row['accordion']; ?><span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_<?php echo $j;?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                        $accordion = $row['accordion'];

                        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT asset FROM field_config_asset WHERE tab='$category' AND accordion='$accordion'"));
                        $value_config = ','.$get_field_config['asset'].',';

                        ?>

                        <?php if (strpos($value_config, ','."Description".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Description:</label>
                        <div class="col-sm-8">
                            <textarea name="description" rows="5" cols="50" class="form-control"><?php echo $description; ?></textarea>
                        </div>
                        </div>
                        <?php } ?>

                        <!--
                        <?php if (strpos($value_config, ','."Category".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label">Category<span class="brand-color">*</span>:</label>
                        <div class="col-sm-8">
                          <select id="category" disabled name="category" class="chosen-select-deselect1 form-control" width="380">
                          <option value=''></option>
                            <option <?php if ($category == "Liners") { echo " selected"; } ?> value = 'Liners'>Liners</option>
                            <option <?php if ($category == "Geotextiles") { echo " selected"; } ?> value = 'Geotextiles'>Geotextiles</option>
                            <option <?php if ($category == "Lids") { echo " selected"; } ?> value = 'Lids'>Lids</option>
                            <option <?php if ($category == "Birdnets") { echo " selected"; } ?> value = 'Birdnets'>Birdnets</option>
                              <?php
                                $result = mysqli_query($dbc, "SELECT distinct(category) FROM asset WHERE category NOT IN ('Liners','Geotextiles','Lids','Birdnets') order by category");
                                while($row = mysqli_fetch_assoc($result)) {
                                    if ($category == $row['category']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value = '".$row['category']."'>".$row['category']."</option>";
                                }
                              ?>
                              <option value = 'Other'>Other</option>
                          </select>
                        </div>
                        </div>
                        -->

                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input name="category_name" id="category_name" type="text" class="form-control" style="display: none;"/>
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Subcategory".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label">Subcategory<span class="brand-color">*</span>:</label>
                        <div class="col-sm-8">
                          <select id="sub_category" name="sub_category" class="chosen-select-deselect form-control" width="380">
                          <option value=''></option>
                            <option <?php if ($sub_category == "15P 40 mil") { echo " selected"; } ?> value = '15P 40 mil'>15P 40 mil</option>
                            <option <?php if ($sub_category == "15P 30 mil") { echo " selected"; } ?> value = '15P 30 mil'>15P 30 mil</option>
                            <option <?php if ($sub_category == "13P 40 mil") { echo " selected"; } ?> value = '13P 40 mil'>13P 40 mil</option>
                            <option <?php if ($sub_category == "13P 30 mil") { echo " selected"; } ?> value = '13P 30 mil'>13P 30 mil</option>
                            <option <?php if ($sub_category == "10P 40 mil") { echo " selected"; } ?> value = '10P 40 mil'>10P 40 mil</option>
                            <option <?php if ($sub_category == "10P 30 mil") { echo " selected"; } ?> value = '10P 30 mil'>10P 30 mil</option>
                            <option <?php if ($sub_category == "15P 10oz") { echo " selected"; } ?> value = '15P 10oz'>15P 10oz</option>
                            <option <?php if ($sub_category == "15P 6oz") { echo " selected"; } ?> value = '15P 6oz'>15P 6oz</option>
                            <option <?php if ($sub_category == "13P 10oz") { echo " selected"; } ?> value = '13P 10oz'>13P 10oz</option>
                            <option <?php if ($sub_category == "13P 6oz") { echo " selected"; } ?> value = '13P 6oz'>13P 6oz</option>
                            <option <?php if ($sub_category == "10P 10oz") { echo " selected"; } ?> value = '10P 10oz'>10P 10oz</option>
                            <option <?php if ($sub_category == "10P 6oz") { echo " selected"; } ?> value = '10P 6oz'>10P 6oz</option>
                            <option <?php if ($sub_category == "15P") { echo " selected"; } ?> value = '15P'>15P</option>
                            <option <?php if ($sub_category == "13P") { echo " selected"; } ?> value = '13P'>13P</option>
                            <option <?php if ($sub_category == "10P") { echo " selected"; } ?> value = '10P'>10P</option>
                              <?php
                                $result = mysqli_query($dbc, "SELECT distinct(sub_category) FROM asset WHERE sub_category NOT IN ('15P 40 mil','15P 30 mil','13P 40 mil','13P 30 mil','10P 40 mil','10P 30 mil','15P 10oz','15P 6oz','13P 10oz','13P 6oz','10P 10oz','10P 6oz','15P','13P','10P') order by sub_category");
                                while($row = mysqli_fetch_assoc($result)) {
                                    if ($sub_category == $row['sub_category']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value = '".$row['sub_category']."'>".$row['sub_category']."</option>";
                                }
                              ?>
                              <option value = 'Other'>Other</option>
                          </select>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input name="sub_category_name" id="sub_category_name" type="text" class="form-control" style="display: none;"/>
                        </div>
                        </div>
                        <?php } ?>


                        <?php if (strpos($value_config, ','."Name".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Name<span class="brand-color">*</span>:</label>
                        <div class="col-sm-8">
                          <input name="name" type="text" value="<?php echo $name; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Product Name".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Product Name<span class="brand-color">*</span>:</label>
                        <div class="col-sm-8">
                          <input name="product_name" type="text" value="<?php echo $product_name; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Type".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="phone_number" class="col-sm-4 control-label">Type:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a Type..." id="type" name="type" class="chosen-select-deselect form-control" width="380">
                              <option value=""></option>
                              <option <?php if ($type=='Project Asset') echo 'selected="selected"';?> value="Project Asset">Project Asset</option>
                              <option <?php if ($type=='Consumables') echo 'selected="selected"';?> value="Consumables">Consumables</option>
                              <option <?php if ($type=='Asset') echo 'selected="selected"';?> value="Asset" >Asset</option>
                            </select>
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Code".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Code<span class="brand-color">*</span>:</label>
                        <div class="col-sm-8">
                          <input name="code" type="text" value="<?php echo $code; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."ID #".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">ID #:</label>
                        <div class="col-sm-8">
                          <input name="id_number" type="text" value="<?php echo $id_number; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Part #".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Part #:</label>
                        <div class="col-sm-8">
                            <input name="part_no" type="text" value="<?php echo $part_no; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>


                        <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Cost:</label>
                        <div class="col-sm-8">
                          <input name="cost" type="text" value="<?php echo $cost; ?>" class="form-control"/>
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."CDN Cost Per Unit".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">CDN Cost Per Unit:</label>
                        <div class="col-sm-8">
                          <input name="cdn_cpu" type="text" id="cpu"	value="<?php echo $cdn_cpu; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."USD Cost Per Unit".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">USD Cost Per Unit:</label>
                        <div class="col-sm-8">
                          <input name="usd_cpu" type="text" id="cpu"	value="<?php echo $usd_cpu; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."COGS".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">COGS GL Code:</label>
                        <div class="col-sm-8">
                          <input name="cogs_total" type="text"	id="cogs" value="<?php echo $cogs_total; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Average Cost".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Average Cost:</label>
                        <div class="col-sm-8">
                          <input name="average_cost" type="text" value="<?php echo $average_cost; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."USD Invoice".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">USD Invoice:</label>
                        <div class="col-sm-8">
                          <input name="usd_invoice" type="text" id="usdinvoice" value="<?php echo $usd_invoice; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                          <?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { ?>
                          <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">Vendor:</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Choose a Vendor..." id="vendor" name="vendorid" class="chosen-select-deselect form-control" width="380">
                                  <option value=""></option>
    							  <?php
    								$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Vendor' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
    								foreach($query as $id) {
    									$selected = '';
    									$selected = $id == $vendorid ? 'selected = "selected"' : '';
    									echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id,'name').'</option>';
    								}
    							?>
                                </select>
                            </div>
                          </div>
                          <?php } ?>

                          <?php if (strpos($value_config, ','."Purchase Cost".',') !== FALSE) { ?>
                          <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">Purchase Cost:</label>
                            <div class="col-sm-8">
                              <input name="purchase_cost" type="text" value="<?php echo $purchase_cost; ?>" class="form-control"/>
                            </div>
                          </div>
                          <?php } ?>

                        <?php if (strpos($value_config, ','."Date of Purchase".',') !== FALSE) { ?>
                        <div class="form-group clearfix completion_date">
                            <label for="first_name" class="col-sm-4 control-label text-right">Date of Purchase:</label>
                            <div class="col-sm-8">
                                <input name="date_of_purchase" value="<?php echo $date_of_purchase; ?>" type="text" class="datepicker"></p>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Shipping Rate".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Shipping Rate:</label>
                        <div class="col-sm-8">
                          <input name="shipping_rate" type="text" id='ship_rate_'	value="<?php echo $shipping_rate; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Freight Charge".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Freight Charge:</label>
                        <div class="col-sm-8">
                          <input name="freight_charge" type="text" value="<?php echo $freight_charge; ?>" class="form-control"/>
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Exchange Rate".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Exchange Rate:</label>
                        <div class="col-sm-8">
                          <input name="exchange_rate" type="text" id="exchangerate"	value="<?php echo $exchange_rate; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Exchange $".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Exchange $:</label>
                        <div class="col-sm-8">
                          <input name="exchange_cash" type="text"	id="exchangecash" value="<?php echo $exchange_cash; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Sell Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Sell Price:</label>
                        <div class="col-sm-8">
                          <input name="sell_price" type="text" value="<?php echo $sell_price; ?>" class="form-control"/>
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
                        <div class="col-sm-8">
                          <input name="final_retail_price" value="<?php echo $final_retail_price; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
                        <div class="col-sm-8">
                          <input name="wholesale_price" value="<?php echo $wholesale_price; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
                        <div class="col-sm-8">
                          <input name="commercial_price" value="<?php echo $commercial_price; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
                        <div class="col-sm-8">
                          <input name="client_price" value="<?php echo $client_price; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Preferred Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Preferred Price</label>
                        <div class="col-sm-8">
                          <input name="preferred_price" type="text" id="preferred_price" value="<?php echo $preferred_price; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
                        <div class="col-sm-8">
                          <input name="admin_price" value="<?php echo $admin_price; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Web Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Web Price:</label>
                        <div class="col-sm-8">
                          <input name="web_price" type="text" id="web_price" value="<?php echo $web_price; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Commission Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Commission Price:</label>
                        <div class="col-sm-8">
                          <input name="commission_price" type="text" id="commission_price" value="<?php echo $commission_price; ?>" class="form-control" />
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."MSRP".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
                        <div class="col-sm-8">
                          <input name="msrp" value="<?php echo $msrp; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Unit Price:</label>
                        <div class="col-sm-8">
                          <input name="unit_price" value="<?php echo $unit_price; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                        <?php if (strpos($value_config, ','."Unit Cost".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Unit Cost:</label>
                        <div class="col-sm-8">
                          <input name="unit_cost" value="<?php echo $unit_cost; ?>" type="text" class="form-control">
                        </div>
                        </div>
                        <?php } ?>

                            <?php if (strpos($value_config, ','."Markup By $".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">Markup By $:</label>
                            <div class="col-sm-8">
                              <input name="markup" type="text" value="<?php echo $markup; ?>" class="form-control"/>
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."Markup By %".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">Markup By %:</label>
                            <div class="col-sm-8">
                              <input name="markup_perc" type="text" value="<?php echo $markup_perc; ?>" class="form-control"/>
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."GL Revenue".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">GL Revenue:</label>
                            <div class="col-sm-8">
                              <input name="revenue" type="text" value="<?php echo $revenue; ?>" class="form-control"/>
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."GL Assets".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">GL Assets:</label>
                            <div class="col-sm-8">
                              <input name="asset" type="text" value="<?php echo $asset; ?>" class="form-control"/>
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."Current Stock".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">Current Stock:</label>
                            <div class="col-sm-8">
                              <input name="current_stock" type="text" value="<?php echo $current_stock; ?>" class="form-control"/>
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."Current Asset".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="fax_number"	class="col-sm-4	control-label">Current Asset:</label>
                            <div class="col-sm-8">
                              <input name="current_asset" type="text" value="<?php echo $current_asset; ?>" class="form-control"/>
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="site_name" class="col-sm-4 control-label">Quantity:</label>
                            <div class="col-sm-8">
                              <input name="quantity" type="text" value="<?php echo $quantity; ?>" class="form-control" />
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."Variance".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="site_name" class="col-sm-4 control-label">GL Code:</label>
                            <div class="col-sm-8">
                              <input name="inv_variance" type="text" id="inv_variance" value="<?php echo $inv_variance; ?>" class="form-control" />
                            </div>
                            </div>
                            <?php } ?>

                            <?php if (strpos($value_config, ','."Write-offs".',') !== FALSE) { ?>
                            <div class="form-group">
                            <label for="site_name" class="col-sm-4 control-label">Write-offs:</label>
                            <div class="col-sm-8">
                              <input name="write_offs" type="text" value="<?php echo $write_offs; ?>" class="form-control" />
                            </div>
                            </div>
                            <?php } ?>

                      <?php if (strpos($value_config, ','."Location".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Location:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a Location..." name="location" class="chosen-select-deselect form-control" width="380">
                              <option value=""></option>
                              <option <?php if ($location == "Sundre") { echo " selected"; } ?> value="Sundre">Sundre</option>
                              <option <?php if ($location == "Edson") { echo " selected"; } ?> value="Edson">Edson</option>
                              <option <?php if ($location == "Grande Prairie") { echo " selected"; } ?> value="Grande Prairie">Grande Prairie</option>
                              <option <?php if ($location == "Other") { echo " selected"; } ?> value="Other">Other</option>
                            </select>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."LSD".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">LSD:</label>
                        <div class="col-sm-8">
                          <input name="lsd" type="text" value="<?php echo $lsd; ?>" class="form-control" />
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Size".',') !== FALSE) {
                      ?>
                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label">Size:</label>
                        <div class="col-sm-8">
                          <select id="size" name="size" class="chosen-select-deselect form-control" width="380">
                          <option value=''></option>
                            <option <?php if ($size == "198&#39; x 198&#39;") { echo " selected"; } ?> value = "198' x 198'">198' x 198'</option>
                            <option <?php if ($size == "176&#39; x 176&#39;") { echo " selected"; } ?> value = "176' x 176'">176' x 176'</option>
                            <option <?php if ($size == "144&#39; x 144&#39;") { echo " selected"; } ?> value = "144' x 144'">144' x 144'</option>
                            <option <?php if ($size == "168&#39; x 168&#39;") { echo " selected"; } ?> value = "168' x 168'">168' x 168'</option>
                            <option <?php if ($size == "145&#39; x 145&#39;") { echo " selected"; } ?> value = "145' x 145'">145' x 145'</option>
                            <option <?php if ($size == "116&#39; x 116&#39;") { echo " selected"; } ?> value = "116' x 116'">116' x 116'</option>
                            <option <?php if ($size == "157&#39;") { echo " selected"; } ?> value = "157'">157'</option>
                            <option <?php if ($size == "136&#39;") { echo " selected"; } ?> value = "136'">136'</option>
                            <option <?php if ($size == "105&#39;") { echo " selected"; } ?> value = "105'">105'</option>
                            <option <?php if ($size == "165&#39;") { echo " selected"; } ?> value = "165'">165'</option>
                            <option <?php if ($size == "145&#39;") { echo " selected"; } ?> value = "145'">145'</option>
                            <option <?php if ($size == "115&#39;") { echo " selected"; } ?> value = "115'">115'</option>
                              <?php
                                $result = mysqli_query($dbc, "SELECT distinct(size) FROM asset WHERE size NOT IN ('176&#39; x 176&#39;','198&#39; x 198&#39;','144&#39; x 144&#39;','168&#39; x 168&#39;','145&#39; x 145&#39;','116&#39; x 116&#39;','157&#39;','136&#39;','105&#39;','165&#39;','145&#39;','115&#39;')");
                                while($row = mysqli_fetch_assoc($result)) {
                                    if ($size == $row['size']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value = '".$row['size']."'>".$row['size']."</option>";
                                }
                              ?>
                              <option value = 'Other'>Other</option>
                          </select>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input name="size_name" id="size_name" type="text" class="form-control" style="display: none;"/>
                        </div>
                        </div>

                      <?php } ?>

                      <?php if (strpos($value_config, ','."Weight".',') !== FALSE) { ?>
                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label">Weight:</label>
                        <div class="col-sm-8">
                          <select id="weight" name="weight" class="chosen-select-deselect form-control" width="380">
                          <option value=''></option>
                            <option <?php if ($weight == "7000lbs") { echo " selected"; } ?> value = "7000lbs">7000lbs</option>
                            <option <?php if ($weight == "5075lbs") { echo " selected"; } ?> value = "5075lbs">5075lbs</option>
                            <option <?php if ($weight == "5560lbs") { echo " selected"; } ?> value = "5560lbs">5560lbs</option>
                            <option <?php if ($weight == "4030lbs") { echo " selected"; } ?> value = "4030lbs">4030lbs</option>
                            <option <?php if ($weight == "3890lbs") { echo " selected"; } ?> value = "3890lbs">3890lbs</option>
                            <option <?php if ($weight == "2842lbs") { echo " selected"; } ?> value = "2842lbs">2842lbs</option>
                            <option <?php if ($weight == "2070lbs") { echo " selected"; } ?> value = "2070lbs">2070lbs</option>
                            <option <?php if ($weight == "1200lbs") { echo " selected"; } ?> value = "1200lbs">1200lbs</option>
                            <option <?php if ($weight == "1600lbs") { echo " selected"; } ?> value = "1600lbs">1600lbs</option>
                            <option <?php if ($weight == "TBC") { echo " selected"; } ?> value = "TBC">TBC</option>
                            <option <?php if ($weight == "1005lbs") { echo " selected"; } ?> value = "1005lbs">1005lbs</option>
                            <option <?php if ($weight == "1100lbs") { echo " selected"; } ?> value = "1100lbs">1100lbs</option>
                            <option <?php if ($weight == "750lbs") { echo " selected"; } ?> value = "750lbs">750lbs</option>
                            <option <?php if ($weight == "500lbs") { echo " selected"; } ?> value = "500lbs">500lbs</option>
                              <?php
                                $result = mysqli_query($dbc, "SELECT distinct(weight) FROM asset WHERE weight NOT IN ('7000lbs', '5075lbs','5560lbs','4030lbs','3890lbs','2842lbs','2070lbs','1200lbs','1600lbs','TBC','1005lbs','1100lbs','750lbs','500lbs')");
                                while($row = mysqli_fetch_assoc($result)) {
                                    if ($weight == $row['weight']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo "<option ".$selected." value = '".$row['weight']."'>".$row['weight']."</option>";
                                }
                              ?>
                              <option value = 'Other'>Other</option>
                          </select>
                        </div>
                        </div>

                        <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                            <input name="weight_name" id="weight_name" type="text" class="form-control" style="display: none;"/>
                        </div>
                        </div>

                      <?php } ?>

                       <?php if (strpos($value_config, ','."Min Max".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Min Max:</label>
                        <div class="col-sm-8">
                          <input name="min_max" type="text" value="<?php echo $min_max; ?>" class="form-control"/>
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Min Bin".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Min Bin:</label>
                        <div class="col-sm-8">
                          <input name="min_bin" type="text" value="<?php echo $min_bin; ?>" class="form-control"/>
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
                        <div class="col-sm-8">
                          <input name="estimated_hours" value="<?php echo $estimated_hours; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
                        <div class="col-sm-8">
                          <input name="actual_hours" value="<?php echo $actual_hours; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
                        <div class="col-sm-8">
                          <input name="minimum_billable" value="<?php echo $minimum_billable; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                       <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { ?>
                       <div class="form-group">
                        <label for="first_name[]" class="col-sm-4 control-label">Quote Description:</label>
                        <div class="col-sm-8">
                          <textarea name="quote_description" rows="5" cols="50" class="form-control"><?php echo $quote_description; ?></textarea>
                        </div>
                      </div>
                      <?php } ?>

                       <?php if (strpos($value_config, ','."Status".',') !== FALSE) { ?>
                       <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Status:</label>
                        <div class="col-sm-8">

                            <select data-placeholder="Choose a Status..." name="status" class="chosen-select-deselect form-control" width="380">
                              <option value=""></option>
                              <option <?php if ($status == "Active") { echo " selected"; } ?> value="Active">Active</option>
                              <option <?php if ($status == "Inactive") { echo " selected"; } ?> value="Inactive">Inactive</option>
                              <option <?php if ($status == "In asset") { echo " selected"; } ?> value="In asset">In asset</option>
                              <option <?php if ($status == "In transit from vendor") { echo " selected"; } ?> value="In transit from vendor">In transit from vendor</option>
                              <option <?php if ($status == "In transit between yards") { echo " selected"; } ?> value="In transit between yards">In transit between yards</option>
                              <option <?php if ($status == "Not confirmed in yard by asset check") { echo " selected"; } ?> value="Not confirmed in yard by asset check">Not confirmed in yard by asset check</option>
                              <option <?php if ($status == "Assigned to job") { echo " selected"; } ?> value="Assigned to job">Assigned to job</option>
                              <option <?php if ($status == "In transit and assigned") { echo " selected"; } ?> value="In transit and assigned">In transit and assigned</option>
                            </select>
                        </div>
                      </div>
                      <?php } ?>

                       <?php if (strpos($value_config, ','."Display on Website".',') !== FALSE) { ?>
                       <div class="form-group">
                        <label for="site_name" class="col-sm-4 control-label">Display on Website:</label>
                        <div class="col-sm-8">
                            <label class="pad-right"><input type="radio" <?php if ($display_website == "Yes") { echo " checked"; } ?> name="display_website" value="Yes">Yes</label>
                            <label class="pad-right"><input type="radio" <?php if ($display_website == "No") { echo " checked"; } ?> name="display_website" value="No">No</label>
                        </div>
                      </div>
                      <?php } ?>

                       <?php if (strpos($value_config, ','."Notes".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Notes:</label>
                        <div class="col-sm-8">
                            <textarea name="note" rows="5" cols="50" class="form-control"><?php echo $note; ?></textarea>
                        </div>
                      </div>
                      <?php } ?>

                       <?php if (strpos($value_config, ','."Comments".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Comment:</label>
                        <div class="col-sm-8">
                            <textarea name="comment" rows="5" cols="50" class="form-control"><?php echo $comment; ?></textarea>
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Rent Price".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Rent Price:</label>
                        <div class="col-sm-8">
                          <input name="rent_price" value="<?php echo $rent_price; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>


                      <?php if (strpos($value_config, ','."Rental Days".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Rental Days:</label>
                        <div class="col-sm-8">
                          <input name="rental_days" value="<?php echo $rental_days; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Rental Weeks".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Rental Weeks:</label>
                        <div class="col-sm-8">
                          <input name="rental_weeks" value="<?php echo $rental_weeks; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."Rental Months".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Rental Months:</label>
                        <div class="col-sm-8">
                          <input name="rental_months" value="<?php echo $rental_months; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."Rental Years".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Rental Years:</label>
                        <div class="col-sm-8">
                          <input name="rental_years" value="<?php echo $rental_years; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."Reminder/Alert".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Reminder/Alert:</label>
                        <div class="col-sm-8">
                          <input name="reminder_alert" value="<?php echo $reminder_alert; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Daily".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Daily:</label>
                        <div class="col-sm-8">
                          <input name="daily" value="<?php echo $daily; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."Weekly".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Weekly:</label>
                        <div class="col-sm-8">
                          <input name="weekly" value="<?php echo $weekly; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."Monthly".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Monthly:</label>
                        <div class="col-sm-8">
                          <input name="monthly" value="<?php echo $monthly; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."Annually".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">Annually:</label>
                        <div class="col-sm-8">
                          <input name="annually" value="<?php echo $annually; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."#Of Days".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">#Of Days:</label>
                        <div class="col-sm-8">
                          <input name="total_days" value="<?php echo $total_days; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."#Of Hours".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">#Of Hours:</label>
                        <div class="col-sm-8">
                          <input name="total_hours" value="<?php echo $total_hours; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                      <?php if (strpos($value_config, ','."#Of Kilometers".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">#Of Kilometers:</label>
                        <div class="col-sm-8">
                          <input name="total_km" value="<?php echo $total_km; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>
                      <?php if (strpos($value_config, ','."#Of Miles".',') !== FALSE) { ?>
                      <div class="form-group">
                        <label for="company_name" class="col-sm-4 control-label">#Of Miles:</label>
                        <div class="col-sm-8">
                          <input name="total_miles" value="<?php echo $total_miles; ?>" type="text" class="form-control">
                        </div>
                      </div>
                      <?php } ?>

                    </div>
                </div>
            </div>
            <?php $j++; }} ?>

        </div>

		<div class="form-group">
			<p><span class="brand-color"><em>Required Fields *</em></span></p>
		</div>

		  <div class="form-group">
			<div class="col-sm-6">
				<a href="asset.php?category=<?php echo $category; ?>" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button	type="submit" name="submit"	value="Submit" class="btn brand-btn btn-lg	pull-right">Submit</button>
			</div>
		  </div>

        

		</form>

	</div>
  </div>

<?php include ('../footer.php'); ?>
