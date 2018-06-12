<?php
/*
Dashboard
*/
include ('../include.php');

error_reporting(0);
checkAuthorised('assets');

if (isset($_POST['add_tab'])) {
    $asset_tabs = filter_var($_POST['asset_tabs'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='asset_tabs'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$asset_tabs' WHERE name='asset_tabs'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('asset_tabs', '$asset_tabs')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_asset.php?type=tab"); </script>';
}

if (isset($_POST['inv_dashboard'])) {
    $tab_dashboard = filter_var($_POST['tab_dashboard'],FILTER_SANITIZE_STRING);
    $asset_dashboard = implode(',',$_POST['asset_dashboard']);
    if (strpos(','.$asset_dashboard.',',','.'Category'.',') === false) {
        $asset_dashboard = 'Category,'.$asset_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configassetid) AS configassetid FROM field_config_asset WHERE tab='$tab_dashboard' AND accordion IS NULL"));
    if($get_field_config['configassetid'] > 0) {
        $query_update_employee = "UPDATE `field_config_asset` SET asset_dashboard = '$asset_dashboard' WHERE tab='$tab_dashboard'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_asset` (`tab`, `asset_dashboard`) VALUES ('$tab_dashboard', '$asset_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_asset.php?type=dashboard&tab='.$tab_dashboard.'"); </script>';
}

if (isset($_POST['inv_field'])) {
    $tab_field = filter_var($_POST['tab_field'],FILTER_SANITIZE_STRING);
    $accordion = filter_var($_POST['accordion'],FILTER_SANITIZE_STRING);
    $asset = implode(',',$_POST['asset']);
    $order = filter_var($_POST['order'],FILTER_SANITIZE_STRING);

    if (strpos(','.$asset.',',','.'Category'.',') === false) {
        $asset = 'Category,'.$asset;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configassetid) AS configassetid FROM field_config_asset WHERE tab='$tab_field' AND accordion='$accordion'"));
    if($get_field_config['configassetid'] > 0) {
        $query_update_employee = "UPDATE `field_config_asset` SET asset = '$asset', `order` = '$order' WHERE tab='$tab_field' AND accordion='$accordion'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_asset` (`tab`, `accordion`, `asset`, `order`) VALUES ('$tab_field', '$accordion', '$asset', '$order')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_asset.php?type=field&tab='.$tab_field.'&accr='.$accordion.'"); </script>';
}

if (isset($_POST['general'])) {
    $asset_minbin_email = filter_var($_POST['asset_minbin_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='asset_minbin_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$asset_minbin_email' WHERE name='asset_minbin_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('asset_minbin_email', '$asset_minbin_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    $asset_order_list = filter_var($_POST['asset_order_list'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='asset_order_list'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$asset_order_list' WHERE name='asset_order_list'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('asset_order_list', '$asset_order_list')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_asset.php?type=general"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_dashboard").change(function() {
        window.location = 'field_config_asset.php?type=dashboard&tab='+this.value;
	});
	$("#tab_field").change(function() {
        window.location = 'field_config_asset.php?type=field&tab='+this.value;
	});

	$("#acc").change(function() {
        var tabs = $("#tab_field").val();
        window.location = 'field_config_asset.php?type=field&tab='+tabs+'&accr='+this.value;
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Assets</h1>
<div class="gap-top"><a href="asset.php?category=Top" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->
<br><br>
<form id="form1" name="form1" method="post"	action="field_config_asset.php" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <?php
    $invtype = $_GET['tab'];
    $accr = $_GET['accr'];
    $type = $_GET['type'];

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT asset FROM field_config_asset WHERE tab='$invtype' AND accordion='$accr'"));
    $asset_config = ','.$get_field_config['asset'].',';

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT asset_dashboard FROM field_config_asset WHERE tab='$invtype' AND accordion IS NULL"));
    $asset_dashboard_config = ','.$get_field_config['asset_dashboard'].',';

    $get_field_order = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(`order` SEPARATOR ',') AS all_order FROM field_config_asset WHERE tab='$invtype'"));

    $active_tab = '';
    $active_field = '';
    $active_dashboard = '';
    $active_general = '';

    if($_GET['type'] == 'tab') {
        $active_tab = 'active_tab';
    }
    if($_GET['type'] == 'field') {
        $active_field = 'active_tab';
    }
    if($_GET['type'] == 'dashboard') {
        $active_dashboard = 'active_tab';
    }
    if($_GET['type'] == 'general') {
        $active_general = 'active_tab';
    }

    echo "<a href='field_config_asset.php?type=tab'><button type='button' class='btn brand-btn mobile-block ".$active_tab."' >Tabs</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_asset.php?type=field'><button type='button' class='btn brand-btn mobile-block ".$active_field."' >Fields</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_asset.php?type=dashboard'><button type='button' class='btn brand-btn mobile-block ".$active_dashboard."' >Dashboard</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_asset.php?type=general'><button type='button' class='btn brand-btn mobile-block ".$active_general."' >General</button></a>&nbsp;&nbsp;";
    echo '<br><br><Br>';

    if($_GET['type'] == 'tab') {
        ?>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Add Tabs separated by a comma:</label>
            <div class="col-sm-8">
              <input name="asset_tabs" type="text" value="<?php echo get_config($dbc, 'asset_tabs'); ?>" class="form-control"/>
            </div>
        </div>

        <div class="pull-left">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <a href="asset.php?filter=Top" class="btn brand-btn btn-lg">Back</a>
            <!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
        </div>
         <div class="pull-right">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <button type="submit" name="add_tab" value="add_tab" class="btn brand-btn btn-lg">Submit</button>
        </div>
        <div class="clearfix"></div>
    <?php }

    if($_GET['type'] == 'field') {
        ?>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Tabs:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Vendor..." id="tab_field" name="tab_field" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $tabs = get_config($dbc, 'asset_tabs');
                    $each_tab = explode(',', $tabs);
                    foreach ($each_tab as $cat_tab) {
                        if ($invtype == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                  ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Accordion:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Accordion..." id="acc" name="accordion" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <option <?php if ($accr == "Description") { echo " selected"; } ?> value="Description"><?php echo get_field_config_asset($dbc, 'Description', 'order', $invtype); ?> : Description</option>
                  <option <?php if ($accr == "Unique Identifier") { echo " selected"; } ?> value="Unique Identifier"><?php echo get_field_config_asset($dbc, 'Unique Identifier', 'order', $invtype); ?> : Unique Identifier</option>
                  <option <?php if ($accr == "Product Cost") { echo " selected"; } ?> value="Product Cost"><?php echo get_field_config_asset($dbc, 'Product Cost', 'order', $invtype); ?> : Product Cost</option>
                  <option <?php if ($accr == "Purchase Info") { echo " selected"; } ?> value="Purchase Info"><?php echo get_field_config_asset($dbc, 'Purchase Info', 'order', $invtype); ?> : Purchase Info</option>
                  <option <?php if ($accr == "Shipping Receiving") { echo " selected"; } ?> value="Shipping Receiving"><?php echo get_field_config_asset($dbc, 'Shipping Receiving', 'order', $invtype); ?> : Shipping Receiving</option>
                  <option <?php if ($accr == "Pricing") { echo " selected"; } ?> value="Pricing"><?php echo get_field_config_asset($dbc, 'Pricing', 'order', $invtype); ?> : Pricing</option>
                  <option <?php if ($accr == "Markup") { echo " selected"; } ?> value="Markup"><?php echo get_field_config_asset($dbc, 'Markup', 'order', $invtype); ?> : Markup</option>
                  <option <?php if ($accr == "Stock") { echo " selected"; } ?> value="Stock"><?php echo get_field_config_asset($dbc, 'Stock', 'order', $invtype); ?> : Stock</option>
                  <option <?php if ($accr == "Location") { echo " selected"; } ?> value="Location"><?php echo get_field_config_asset($dbc, 'Location', 'order', $invtype); ?> : Location</option>
                  <option <?php if ($accr == "Dimensions") { echo " selected"; } ?> value="Dimensions"><?php echo get_field_config_asset($dbc, 'Dimensions', 'order', $invtype); ?> : Dimensions</option>
                  <option <?php if ($accr == "Alerts") { echo " selected"; } ?> value="Alerts"><?php echo get_field_config_asset($dbc, 'Alerts', 'order', $invtype); ?> : Alerts</option>
                  <option <?php if ($accr == "Time Allocation") { echo " selected"; } ?> value="Time Allocation"><?php echo get_field_config_asset($dbc, 'Time Allocation', 'order', $invtype); ?> : Time Allocation</option>
                  <option <?php if ($accr == "Admin Fees") { echo " selected"; } ?> value="Admin Fees"><?php echo get_field_config_asset($dbc, 'Admin Fees', 'order', $invtype); ?> : Admin Fees</option>
                  <option <?php if ($accr == "Quote") { echo " selected"; } ?> value="Quote"><?php echo get_field_config_asset($dbc, 'Quote', 'order', $invtype); ?> : Quote</option>
                  <option <?php if ($accr == "Status") { echo " selected"; } ?> value="Status"><?php echo get_field_config_asset($dbc, 'Status', 'order', $invtype); ?> : Status</option>
                  <option <?php if ($accr == "Display On Website") { echo " selected"; } ?> value="Display On Website"><?php echo get_field_config_asset($dbc, 'Display On Website', 'order', $invtype); ?> : Display On Website</option>
                  <option <?php if ($accr == "General") { echo " selected"; } ?> value="General"><?php echo get_field_config_asset($dbc, 'General', 'order', $invtype); ?> : General</option>
                  <option <?php if ($accr == "Rental") { echo " selected"; } ?> value="Rental"><?php echo get_field_config_asset($dbc, 'Rental', 'order', $invtype); ?> : Rental</option>
                  <option <?php if ($accr == "Day/Week/Month/Year") { echo " selected"; } ?> value="Day/Week/Month/Year"><?php echo get_field_config_asset($dbc, 'Day/Week/Month/Year', 'order', $invtype); ?> : Day/Week/Month/Year</option>
                  <option <?php if ($accr == "Vehicle") { echo " selected"; } ?> value="Vehicle"><?php echo get_field_config_asset($dbc, 'Vehicle', 'order', $invtype); ?> : Vehicle</option>
                </select>
                <select data-placeholder="Choose a order..." name="order" class="chosen-select-deselect form-control" width="380">
                    <option value=""></option>
                    <?php
                    for($m=1;$m<=30;$m++) { ?>
                        <option <?php if (get_field_config_asset($dbc, $accr, 'order', $invtype) == $m) { echo  'selected="selected"'; } ?>  <?php if (strpos(','.$get_field_order['all_order'].',', ','.$m.',') !== FALSE) { echo " disabled"; } ?> value="<?php echo $m;?>"><?php echo $m;?></option>
                    <?php }
                    ?>
                </select>
            </div>
        </div>

        <h3>Fields</h3>
        <div class="panel-group" id="accordion2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
                            Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_1" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Description
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Category
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Subcategory".',') !== FALSE) { echo " checked"; } ?> value="Subcategory" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Subcategory
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Name".',') !== FALSE) { echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Name
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Product Name".',') !== FALSE) { echo " checked"; } ?> value="Product Name" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Product Name
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Type".',') !== FALSE) { echo " checked"; } ?> value="Type" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Type

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                            Unique Identifier<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Code".',') !== FALSE) { echo " checked"; } ?> value="Code" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Code
                        <input type="checkbox" <?php if (strpos($asset_config, ','."ID #".',') !== FALSE) { echo " checked"; } ?> value="ID #" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;ID #
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Part #".',') !== FALSE) { echo " checked"; } ?> value="Part #" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Part #
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
                            Product Cost<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_3" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Cost
                        <input type="checkbox" <?php if (strpos($asset_config, ','."CDN Cost Per Unit".',') !== FALSE) { echo " checked"; } ?> value="CDN Cost Per Unit" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;CDN Cost Per Unit
                        <input type="checkbox" <?php if (strpos($asset_config, ','."USD Cost Per Unit".',') !== FALSE) { echo " checked"; } ?> value="USD Cost Per Unit" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;USD Cost Per Unit
                        <input type="checkbox" <?php if (strpos($asset_config, ','."COGS".',') !== FALSE) { echo " checked"; } ?> value="COGS" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;COGS GL Code
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Average Cost".',') !== FALSE) { echo " checked"; } ?> value="Average Cost" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Average Cost
                        <input type="checkbox" <?php if (strpos($asset_config, ','."USD Invoice".',') !== FALSE) { echo " checked"; } ?> value="USD Invoice" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;USD Invoice

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_4" >
                            Purchase Info<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_4" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Vendor
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Purchase Cost".',') !== FALSE) { echo " checked"; } ?> value="Purchase Cost" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Purchase Cost
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Date Of Purchase".',') !== FALSE) { echo " checked"; } ?> value="Date Of Purchase" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Date Of Purchase

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
                            Shipping & Receiving<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_5" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Shipping Rate".',') !== FALSE) { echo " checked"; } ?> value="Shipping Rate" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Shipping Rate
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Freight Charge".',') !== FALSE) { echo " checked"; } ?> value="Freight Charge" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Freight Charge
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Exchange Rate".',') !== FALSE) { echo " checked"; } ?> value="Exchange Rate" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Exchange Rate
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Exchange $".',') !== FALSE) { echo " checked"; } ?> value="Exchange $" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Exchange $

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_6" >
                            Pricing<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_6" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Sell Price".',') !== FALSE) { echo " checked"; } ?> value="Sell Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Sell Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Final Retail Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Wholesale Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Commercial Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Client Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Preferred Price".',') !== FALSE) { echo " checked"; } ?> value="Preferred Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Preferred Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Admin Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Web Price".',') !== FALSE) { echo " checked"; } ?> value="Web Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Web Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Commission Price".',') !== FALSE) { echo " checked"; } ?> value="Commission Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Commission Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."MSRP".',') !== FALSE) { echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;MSRP
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Unit Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Unit Cost

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
                            Markup<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_7" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Markup By $".',') !== FALSE) { echo " checked"; } ?> value="Markup By $" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Markup By $
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Markup By %".',') !== FALSE) { echo " checked"; } ?> value="Markup By %" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Markup By %

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
                            Stock<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_8" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Current Stock".',') !== FALSE) { echo " checked"; } ?> value="Current Stock" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Current Stock
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Current Asset".',') !== FALSE) { echo " checked"; } ?> value="Current Asset" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Current Asset
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Quantity
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Variance".',') !== FALSE) { echo " checked"; } ?> value="Variance" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;GL Code
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Write-offs".',') !== FALSE) { echo " checked"; } ?> value="Write-offs" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Write-offs

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
                            Location<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_9" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Location
                        <input type="checkbox" <?php if (strpos($asset_config, ','."LSD".',') !== FALSE) { echo " checked"; } ?> value="LSD" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;LSD

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_10" >
                            Dimensions<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_10" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Size".',') !== FALSE) { echo " checked"; } ?> value="Size" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Size
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Weight".',') !== FALSE) { echo " checked"; } ?> value="Weight" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Weight

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_11" >
                            Alerts<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_11" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Min Max".',') !== FALSE) { echo " checked"; } ?> value="Min Max" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Min Max
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Min Bin".',') !== FALSE) { echo " checked"; } ?> value="Min Bin" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Min Bin

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_12" >
                            Time Allocation<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_12" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Estimated Hours
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Actual Hours

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_13" >
                            Admin Fees<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_13" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Minimum Billable
                        <input type="checkbox" <?php if (strpos($asset_config, ','."GL Revenue".',') !== FALSE) { echo " checked"; } ?> value="GL Revenue" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;GL Revenue

                        <input type="checkbox" <?php if (strpos($asset_config, ','."GL Assets".',') !== FALSE) { echo " checked"; } ?> value="GL Assets" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;GL Assets

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_14" >
                            Quote<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_14" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Quote Description

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_15" >
                            Status<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_15" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Status

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_16" >
                            Display On Website<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_16" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Display On Website".',') !== FALSE) { echo " checked"; } ?> value="Display On Website" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Display On Website

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_17" >
                            General<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_17" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Notes
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Comments

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_18" >
                            Rental<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_18" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Rent Price".',') !== FALSE) { echo " checked"; } ?> value="Rent Price" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Rent Price
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Rental Days".',') !== FALSE) { echo " checked"; } ?> value="Rental Days" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Rental Days
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Rental Weeks".',') !== FALSE) { echo " checked"; } ?> value="Rental Weeks" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Rental Weeks
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Rental Months".',') !== FALSE) { echo " checked"; } ?> value="Rental Months" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Rental Months
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Rental Years".',') !== FALSE) { echo " checked"; } ?> value="Rental Years" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Rental Years
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Reminder/Alert".',') !== FALSE) { echo " checked"; } ?> value="Reminder/Alert" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Reminder/Alert

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_51" >
                            Day/Week/Month/Year<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_51" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."Daily".',') !== FALSE) { echo " checked"; } ?> value="Daily" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Daily
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Weekly".',') !== FALSE) { echo " checked"; } ?> value="Weekly" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Weekly
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Monthly".',') !== FALSE) { echo " checked"; } ?> value="Monthly" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Monthly
                        <input type="checkbox" <?php if (strpos($asset_config, ','."Annually".',') !== FALSE) { echo " checked"; } ?> value="Annually" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;Annually
                        <input type="checkbox" <?php if (strpos($asset_config, ','."#Of Days".',') !== FALSE) { echo " checked"; } ?> value="#Of Days" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;#Of Days
                        <input type="checkbox" <?php if (strpos($asset_config, ','."#Of Hours".',') !== FALSE) { echo " checked"; } ?> value="#Of Hours" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;#Of Hours

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_53" >
                            Vehicle<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_53" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_config, ','."#Of Kilometers".',') !== FALSE) { echo " checked"; } ?> value="#Of Kilometers" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;#Of Kilometers
                        <input type="checkbox" <?php if (strpos($asset_config, ','."#Of Miles".',') !== FALSE) { echo " checked"; } ?> value="#Of Miles" style="height: 20px; width: 20px;" name="asset[]">&nbsp;&nbsp;#Of Miles

                    </div>
                </div>
            </div>
        </div>

        <div class="pull-left">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <a href="asset.php?filter=Top" class="btn brand-btn btn-lg">Back</a>
            <!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
        </div>
         <div class="pull-right">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <button type="submit" name="inv_field" value="inv_field" class="btn brand-btn btn-lg">Submit</button>
        </div>
        <div class="clearfix"></div>

    <?php }
    ?>

    <?php if($_GET['type'] == 'dashboard') { ?>
        <div class="form-group">
            <label for="fax_number"	class="col-sm-4	control-label">Tabs:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose a Vendor..." id="tab_dashboard" name="tab_dashboard" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $tabs = get_config($dbc, 'asset_tabs');
                    $each_tab = explode(',', $tabs);
                    foreach ($each_tab as $cat_tab) {
                        if ($invtype == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                  ?>
                </select>
            </div>
        </div>

        <h3>Dashboard</h3>
        <div class="panel-group" id="accordion2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
                            Description<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_1" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Description
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Category
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Subcategory".',') !== FALSE) { echo " checked"; } ?> value="Subcategory" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Subcategory
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Name".',') !== FALSE) { echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Name
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Product Name".',') !== FALSE) { echo " checked"; } ?> value="Product Name" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Product Name
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Type".',') !== FALSE) { echo " checked"; } ?> value="Type" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Type

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
                            Unique Identifier<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_2" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Code".',') !== FALSE) { echo " checked"; } ?> value="Code" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Code
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."ID #".',') !== FALSE) { echo " checked"; } ?> value="ID #" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;ID #
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Part #".',') !== FALSE) { echo " checked"; } ?> value="Part #" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Part #
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
                            Product Cost<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_3" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Cost
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."CDN Cost Per Unit".',') !== FALSE) { echo " checked"; } ?> value="CDN Cost Per Unit" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;CDN Cost Per Unit
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."USD Cost Per Unit".',') !== FALSE) { echo " checked"; } ?> value="USD Cost Per Unit" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;USD Cost Per Unit
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."COGS".',') !== FALSE) { echo " checked"; } ?> value="COGS" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;COGS GL Code
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Average Cost".',') !== FALSE) { echo " checked"; } ?> value="Average Cost" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Average Cost
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."USD Invoice".',') !== FALSE) { echo " checked"; } ?> value="USD Invoice" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;USD Invoice

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_4" >
                            Purchase Info<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_4" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Vendor
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Purchase Cost".',') !== FALSE) { echo " checked"; } ?> value="Purchase Cost" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Purchase Cost
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Date Of Purchase".',') !== FALSE) { echo " checked"; } ?> value="Date Of Purchase" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Date Of Purchase

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
                            Shipping & Receiving<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_5" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Shipping Rate".',') !== FALSE) { echo " checked"; } ?> value="Shipping Rate" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Shipping Rate
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Freight Charge".',') !== FALSE) { echo " checked"; } ?> value="Freight Charge" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Freight Charge
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Exchange Rate".',') !== FALSE) { echo " checked"; } ?> value="Exchange Rate" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Exchange Rate
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Exchange $".',') !== FALSE) { echo " checked"; } ?> value="Exchange $" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Exchange $

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_6" >
                            Pricing<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_6" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Sell Price".',') !== FALSE) { echo " checked"; } ?> value="Sell Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Sell Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Final Retail Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Wholesale Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Commercial Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Client Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Preferred Price".',') !== FALSE) { echo " checked"; } ?> value="Preferred Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Preferred Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Admin Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Web Price".',') !== FALSE) { echo " checked"; } ?> value="Web Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Web Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Commission Price".',') !== FALSE) { echo " checked"; } ?> value="Commission Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Commission Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."MSRP".',') !== FALSE) { echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;MSRP
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Unit Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Unit Cost

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
                            Markup<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_7" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Markup By $".',') !== FALSE) { echo " checked"; } ?> value="Markup By $" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Markup By $
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Markup By %".',') !== FALSE) { echo " checked"; } ?> value="Markup By %" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Markup By %

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
                            Stock<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_8" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Current Stock".',') !== FALSE) { echo " checked"; } ?> value="Current Stock" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Current Stock
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Current Asset".',') !== FALSE) { echo " checked"; } ?> value="Current Asset" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Current Asset
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Quantity
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Variance".',') !== FALSE) { echo " checked"; } ?> value="Variance" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;GL Code
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Write-offs".',') !== FALSE) { echo " checked"; } ?> value="Write-offs" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Write-offs

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
                            Location<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_9" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Location
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."LSD".',') !== FALSE) { echo " checked"; } ?> value="LSD" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;LSD

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_10" >
                            Dimensions<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_10" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Size".',') !== FALSE) { echo " checked"; } ?> value="Size" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Size
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Weight".',') !== FALSE) { echo " checked"; } ?> value="Weight" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Weight

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_11" >
                            Alerts<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_11" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Min Max".',') !== FALSE) { echo " checked"; } ?> value="Min Max" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Min Max
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Min Bin".',') !== FALSE) { echo " checked"; } ?> value="Min Bin" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Min Bin

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_12" >
                            Time Allocation<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_12" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Estimated Hours
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Actual Hours

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_13" >
                            Admin Fees<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_13" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Minimum Billable
                        <input type="checkbox" <?php if (strpos($asset_config, ','."GL Revenue".',') !== FALSE) { echo " checked"; } ?> value="GL Revenue" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;GL Revenue

                        <input type="checkbox" <?php if (strpos($asset_config, ','."GL Assets".',') !== FALSE) { echo " checked"; } ?> value="GL Assets" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;GL Assets

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_14" >
                            Quote<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_14" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Quote Description

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_15" >
                            Status<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_15" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Status

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_16" >
                            Display On Website<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_16" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Display On Website".',') !== FALSE) { echo " checked"; } ?> value="Display On Website" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Display On Website

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_17" >
                            General<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_17" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Notes
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Comments

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_18" >
                            Rental<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_18" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Rent Price".',') !== FALSE) { echo " checked"; } ?> value="Rent Price" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Rent Price
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Rental Days".',') !== FALSE) { echo " checked"; } ?> value="Rental Days" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Rental Days
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Rental Weeks".',') !== FALSE) { echo " checked"; } ?> value="Rental Weeks" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Rental Weeks
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Rental Months".',') !== FALSE) { echo " checked"; } ?> value="Rental Months" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Rental Months
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Rental Years".',') !== FALSE) { echo " checked"; } ?> value="Rental Years" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Rental Years
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Reminder/Alert".',') !== FALSE) { echo " checked"; } ?> value="Reminder/Alert" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Reminder/Alert

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_51" >
                            Day/Week/Month/Year<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_51" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Daily".',') !== FALSE) { echo " checked"; } ?> value="Daily" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Daily
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Weekly".',') !== FALSE) { echo " checked"; } ?> value="Weekly" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Weekly
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Monthly".',') !== FALSE) { echo " checked"; } ?> value="Monthly" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Monthly
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."Annually".',') !== FALSE) { echo " checked"; } ?> value="Annually" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;Annually
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."#Of Days".',') !== FALSE) { echo " checked"; } ?> value="#Of Days" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;#Of Days
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."#Of Hours".',') !== FALSE) { echo " checked"; } ?> value="#Of Hours" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;#Of Hours

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_53" >
                            Vehicle<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_53" class="panel-collapse collapse">
                    <div class="panel-body">

                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."#Of Kilometers".',') !== FALSE) { echo " checked"; } ?> value="#Of Kilometers" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;#Of Kilometers
                        <input type="checkbox" <?php if (strpos($asset_dashboard_config, ','."#Of Miles".',') !== FALSE) { echo " checked"; } ?> value="#Of Miles" style="height: 20px; width: 20px;" name="asset_dashboard[]">&nbsp;&nbsp;#Of Miles

                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="pull-left">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <a href="asset.php?filter=Top" class="btn brand-btn btn-lg">Back</a>
            <!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
        </div>
         <div class="pull-right">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <button type="submit" name="inv_dashboard" value="inv_dashboard" class="btn brand-btn btn-lg">Submit</button>
        </div>
        <div class="clearfix"></div>

    <?php }

    if($_GET['type'] == 'general') { ?>

        <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Send Email for Min Bin</label>
        <div class="col-sm-8">
          <input name="asset_minbin_email" value="<?php echo get_config($dbc, 'asset_minbin_email'); ?>" type="text" class="form-control">
        </div>
        </div>
        <div class="form-group">
        <label for="company_name" class="col-sm-4 control-label">Use Asset Order List</label>
        <div class="col-sm-8">
			<?php $asset_order_list = get_config($dbc, 'asset_order_list'); ?>
          <label><input name="asset_order_list" value="1" <?= $asset_order_list > 0 ? 'checked' : '' ?> type="radio" class="form-control"> Yes</label>
          <label><input name="asset_order_list" value="0" <?= $asset_order_list > 0 ? '' : 'checked' ?> type="radio" class="form-control"> No</label>
        </div>
        </div>
        <div class="pull-left">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Clicking here will discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <a href="asset.php?filter=Top" class="btn brand-btn btn-lg">Back</a>
			<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
		</div>
		 <div class="pull-right">
            <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <button	type="submit" name="general" value="general" class="btn brand-btn btn-lg">Submit</button>
        </div>
        <div class="clearfix"></div>
    <?php } ?>

        <!--
        <div class="form-group">
            <div class="col-sm-4 clearfix">
                <a href="asset.php?filter=Top" class="btn config-btn pull-right">Back</a>
            </div>
            <div class="col-sm-8">
                <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
            </div>
        </div>
        -->

        

</form>
</div>
</div>
</div>
<?php include ('../footer.php'); ?>