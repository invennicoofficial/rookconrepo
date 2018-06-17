<?php
include_once('../include.php');
checkAuthorised('vpl');
if (isset($_POST['inv_dashboard'])) {
    $tab_dashboard = filter_var($_POST['tab_dashboard'],FILTER_SANITIZE_STRING);
    $inventory_dashboard = implode(',',$_POST['inventory_dashboard']);
    //if (strpos(','.$inventory_dashboard.',',','.'Category'.',') === false) {
    //    $inventory_dashboard = 'Category,'.$inventory_dashboard;
    //}

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configinvid) AS configinvid FROM field_config_vpl WHERE tab='$tab_dashboard' AND accordion IS NULL"));
    if($get_field_config['configinvid'] > 0) {
        $query_update_employee = "UPDATE `field_config_vpl` SET inventory_dashboard = '$inventory_dashboard' WHERE tab='$tab_dashboard'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_vpl` (`tab`, `inventory_dashboard`) VALUES ('$tab_dashboard', '$inventory_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#tab_dashboard").change(function() {
        window.location = '?settings=vpl_dashboard&tab='+this.value;
	});
	$("#tab_field").change(function() {
        window.location = '?settings=vpl_fields&tab='+this.value;
	});

	$("#acc").change(function() {
        var tabs = $("#tab_field").val();
        window.location = '?settings=vpl_fields&tab='+tabs+'&accr='+this.value;
	});
	$('input.show_category_dropdown').on('change', function() {
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = ''; }
		$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../Vendor Price List/inventory_ajax_all.php?fill=show_category_dropdown&value="+value,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
		});
	});
	$('input.show_impexp_vpl').on('change', function() {
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = ''; }
		$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../Vendor Price List/inventory_ajax_all.php?fill=show_impexp_vpl&value="+value,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
		});
	});

});
</script>
<?php 
$invtype = $_GET['tab'];
$accr = $_GET['accr'];
$type = $_GET['type'];

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT inventory FROM field_config_vpl WHERE tab='$invtype' AND accordion='$accr'"));
$inventory_config = ','.$get_field_config['inventory'].',';

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT inventory_dashboard FROM field_config_vpl WHERE tab='$invtype' AND accordion IS NULL"));
$inventory_dashboard_config = ','.$get_field_config['inventory_dashboard'].',';

$get_field_order = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(`order` SEPARATOR ',') AS all_order FROM field_config_vpl WHERE tab='$invtype'")); ?>
<div class="standard-body-title">
    <h3>Vendor Price List Settings - Dashboard</h3>
</div>
<div class="standard-body-content full-height">
    <div class="dashboard-item dashboard-item2 full-height">
		<form id="form1" name="form1" method="post"	enctype="multipart/form-data" class="form-horizontal" role="form">
	        <div class="form-group">
	            <label for="fax_number"	class="col-sm-4	control-label">Tabs:</label>
	            <div class="col-sm-8">
	                <select data-placeholder="Choose a Vendor..." id="tab_dashboard" name="tab_dashboard" class="chosen-select-deselect form-control" width="380">
	                  <option value=""></option>
	                  <?php
	                    $tabs = get_config($dbc, 'vpl_tabs');
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
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
	                            Description<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_1" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Vendor
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."VPL Name".',') !== FALSE) { echo " checked"; } ?> value="VPL Name" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Vendor Price List Name
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Description
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Category
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Subcategory".',') !== FALSE) { echo " checked"; } ?> value="Subcategory" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Subcategory
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Name".',') !== FALSE) { echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Name
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Product Name".',') !== FALSE) { echo " checked"; } ?> value="Product Name" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Product Name
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Type".',') !== FALSE) { echo " checked"; } ?> value="Type" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Type
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Color".',') !== FALSE) { echo " checked"; } ?> value="Color" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Color

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_2" >
	                            Unique Identifier<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_2" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Code".',') !== FALSE) { echo " checked"; } ?> value="Code" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Code
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."ID #".',') !== FALSE) { echo " checked"; } ?> value="ID #" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;ID #
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Item SKU".',') !== FALSE) { echo " checked"; } ?> value="Item SKU" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Item SKU
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Part #".',') !== FALSE) { echo " checked"; } ?> value="Part #" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Part #
	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_3" >
	                            Product Cost<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_3" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Cost
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."CDN Cost Per Unit".',') !== FALSE) { echo " checked"; } ?> value="CDN Cost Per Unit" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;CDN Cost Per Unit
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."USD Cost Per Unit".',') !== FALSE) { echo " checked"; } ?> value="USD Cost Per Unit" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;USD Cost Per Unit
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."COGS".',') !== FALSE) { echo " checked"; } ?> value="COGS" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;COGS GL Code
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Average Cost".',') !== FALSE) { echo " checked"; } ?> value="Average Cost" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Average Cost
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."USD Invoice".',') !== FALSE) { echo " checked"; } ?> value="USD Invoice" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;USD Invoice

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_4" >
	                            Purchase Info<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_4" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Purchase Cost".',') !== FALSE) { echo " checked"; } ?> value="Purchase Cost" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Purchase Cost
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Date Of Purchase".',') !== FALSE) { echo " checked"; } ?> value="Date Of Purchase" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Date Of Purchase

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_5" >
	                            Shipping & Receiving<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_5" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Shipping Rate".',') !== FALSE) { echo " checked"; } ?> value="Shipping Rate" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Shipping Rate
	                        <input type="checkbox" <?php if (strpos($inventory_config, ','."Shipping Cash".',') !== FALSE) { echo " checked"; } ?> value="Shipping Cash" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Shipping Cash
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Freight Charge".',') !== FALSE) { echo " checked"; } ?> value="Freight Charge" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Freight Charge
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Exchange Rate".',') !== FALSE) { echo " checked"; } ?> value="Exchange Rate" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Exchange Rate
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Exchange $".',') !== FALSE) { echo " checked"; } ?> value="Exchange $" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Exchange $

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_6" >
	                            Pricing<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_6" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Sell Price".',') !== FALSE) { echo " checked"; } ?> value="Sell Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Sell Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Final Retail Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Wholesale Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Commercial Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Client Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Preferred Price".',') !== FALSE) { echo " checked"; } ?> value="Preferred Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Preferred Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Admin Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Web Price".',') !== FALSE) { echo " checked"; } ?> value="Web Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Web Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Commission Price".',') !== FALSE) { echo " checked"; } ?> value="Commission Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Commission Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."MSRP".',') !== FALSE) { echo " checked"; } ?> value="MSRP" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;MSRP
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Unit Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Unit Cost" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Unit Cost
							<input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Purchase Order Price".',') !== FALSE) { echo " checked"; } ?> value="Purchase Order Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Purchase Order Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Sales Order Price".',') !== FALSE) { echo " checked"; } ?> value="Sales Order Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;<?= SALES_ORDER_NOUN ?> Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Suggested Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Suggested Retail Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Suggested Retail Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Rush Price".',') !== FALSE) { echo " checked"; } ?> value="Rush Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Rush Price

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_7" >
	                            Markup<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_7" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Markup By $".',') !== FALSE) { echo " checked"; } ?> value="Markup By $" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Markup By $
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Markup By %".',') !== FALSE) { echo " checked"; } ?> value="Markup By %" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Markup By %

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_8" >
	                            Stock<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_8" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Current Stock".',') !== FALSE) { echo " checked"; } ?> value="Current Stock" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Current Stock
	                        <!-- Taken out to remove confusion between quantity and current inventory <input type="checkbox" <?php //if (strpos($inventory_dashboard_config, ','."Current Inventory".',') !== FALSE) { echo " checked"; } ?> value="Current Inventory" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Current Inventory-->
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Quantity
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Variance".',') !== FALSE) { echo " checked"; } ?> value="Variance" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;GL Code
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Write-offs".',') !== FALSE) { echo " checked"; } ?> value="Write-offs" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Write-offs

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Buying Units".',') !== FALSE) { echo " checked"; } ?> value="Buying Units" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Buying Units
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Selling Units".',') !== FALSE) { echo " checked"; } ?> value="Selling Units" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Selling Units
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Stocking Units".',') !== FALSE) { echo " checked"; } ?> value="Stocking Units" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Stocking Units

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_9" >
	                            Location<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_9" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Location".',') !== FALSE) { echo " checked"; } ?> value="Location" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Location
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."LSD".',') !== FALSE) { echo " checked"; } ?> value="LSD" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;LSD

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_10" >
	                            Dimensions<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_10" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Size".',') !== FALSE) { echo " checked"; } ?> value="Size" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Size
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Weight".',') !== FALSE) { echo " checked"; } ?> value="Weight" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Weight

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_11" >
	                            Alerts<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_11" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Min Max".',') !== FALSE) { echo " checked"; } ?> value="Min Max" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Min Max
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Min Bin".',') !== FALSE) { echo " checked"; } ?> value="Min Bin" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Min Bin

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_12" >
	                            Time Allocation<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_12" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Estimated Hours".',') !== FALSE) { echo " checked"; } ?> value="Estimated Hours" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Estimated Hours
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Actual Hours".',') !== FALSE) { echo " checked"; } ?> value="Actual Hours" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Actual Hours

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_13" >
	                            Admin Fees<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_13" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Minimum Billable".',') !== FALSE) { echo " checked"; } ?> value="Minimum Billable" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Minimum Billable
	                        <input type="checkbox" <?php if (strpos($inventory_config, ','."GL Revenue".',') !== FALSE) { echo " checked"; } ?> value="GL Revenue" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;GL Revenue

	                        <input type="checkbox" <?php if (strpos($inventory_config, ','."GL Assets".',') !== FALSE) { echo " checked"; } ?> value="GL Assets" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;GL Assets

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_14" >
	                            Quote<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_14" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Quote Description

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_15" >
	                            Status<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_15" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Status

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_16" >
	                            Display On Website<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_16" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Display On Website".',') !== FALSE) { echo " checked"; } ?> value="Display On Website" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Display On Website

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_17" >
	                            General<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_17" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Notes".',') !== FALSE) { echo " checked"; } ?> value="Notes" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Notes
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Comments".',') !== FALSE) { echo " checked"; } ?> value="Comments" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Comments

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_18" >
	                            Rental<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_18" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Rent Price".',') !== FALSE) { echo " checked"; } ?> value="Rent Price" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Rent Price
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Rental Days".',') !== FALSE) { echo " checked"; } ?> value="Rental Days" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Rental Days
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Rental Weeks".',') !== FALSE) { echo " checked"; } ?> value="Rental Weeks" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Rental Weeks
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Rental Months".',') !== FALSE) { echo " checked"; } ?> value="Rental Months" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Rental Months
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Rental Years".',') !== FALSE) { echo " checked"; } ?> value="Rental Years" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Rental Years
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Reminder/Alert".',') !== FALSE) { echo " checked"; } ?> value="Reminder/Alert" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Reminder/Alert

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_51" >
	                            Day/Week/Month/Year<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_51" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Daily".',') !== FALSE) { echo " checked"; } ?> value="Daily" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Daily
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Weekly".',') !== FALSE) { echo " checked"; } ?> value="Weekly" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Weekly
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Monthly".',') !== FALSE) { echo " checked"; } ?> value="Monthly" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Monthly
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Annually".',') !== FALSE) { echo " checked"; } ?> value="Annually" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Annually
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."#Of Days".',') !== FALSE) { echo " checked"; } ?> value="#Of Days" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;#Of Days
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."#Of Hours".',') !== FALSE) { echo " checked"; } ?> value="#Of Hours" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;#Of Hours

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_53" >
	                            Vehicle<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_53" class="panel-collapse collapse">
	                    <div class="panel-body">

	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."#Of Kilometers".',') !== FALSE) { echo " checked"; } ?> value="#Of Kilometers" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;#Of Kilometers
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."#Of Miles".',') !== FALSE) { echo " checked"; } ?> value="#Of Miles" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;#Of Miles

	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_ordersandpos" >
	                            Inclusion<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_ordersandpos" class="panel-collapse collapse">
	                    <div class="panel-body">
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Include in P.O.S.".',') !== FALSE) {
	                            echo " checked"; } ?> value="Include in P.O.S." style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Include in Point of Sale
	                            <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Include in Purchase Orders".',') !== FALSE) {
	                            echo " checked"; } ?> value="Include in Purchase Orders" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Include in Purchase Orders
	                            <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Include in Sales Orders".',') !== FALSE) {
	                            echo " checked"; } ?> value="Include in Sales Orders" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Include in <?= SALES_ORDER_TILE ?>
	                    </div>
	                </div>
	            </div>

	            <div class="panel panel-default">
	                <div class="panel-heading no_load">
	                    <h4 class="panel-title">
	                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_amount" >
	                            Amount<span class="glyphicon glyphicon-plus"></span>
	                        </a>
	                    </h4>
	                </div>

	                <div id="collapse_amount" class="panel-collapse collapse">
	                    <div class="panel-body">
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Min Amount".',') !== FALSE) { echo " checked"; } ?> value="Min Amount" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Min Amount
	                        <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Max Amount".',') !== FALSE) { echo " checked"; } ?> value="Max Amount" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Max Amount
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="form-group pull-right">
	                <a href="?" class="btn brand-btn">Back</a>
	                <button	type="submit" name="inv_dashboard" value="inv_field" class="btn brand-btn">Submit</button>
	            </div>
	        </div>

			<div class="clearfix"></div>
        </form>
    </div>
</div>