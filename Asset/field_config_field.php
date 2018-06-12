<?phps
if (isset($_POST['submit'])) {
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
}
$invtype = $_GET['tab'];
$accr = $_GET['accr'];
$type = $_GET['type'];

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT asset FROM field_config_asset WHERE tab='$invtype' AND accordion='$accr'"));
$asset_config = ','.$get_field_config['asset'].',';

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT asset_dashboard FROM field_config_asset WHERE tab='$invtype' AND accordion IS NULL"));
$asset_dashboard_config = ','.$get_field_config['asset_dashboard'].',';

$get_field_order = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(`order` SEPARATOR ',') AS all_order FROM field_config_asset WHERE tab='$invtype'"));
?>

<div class="gap-top">
    <div class="form-group">
        <label for="fax_number" class="col-sm-4 control-label">Tabs:</label>
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
        <label for="fax_number" class="col-sm-4 control-label">Accordion:</label>
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

    <div class="clearfix"></div>

</div>