<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('inventory');
error_reporting(0);

if (isset($_POST['inv_dashboard'])) {
    $inventory_dashboard = implode(',',$_POST['inventory_dashboard']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configinvid) AS configinvid FROM field_config_inventory WHERE tab='receive_shipment' AND accordion='receive_shipment'"));
    if($get_field_config['configinvid'] > 0) {
        $query_update_employee = "UPDATE `field_config_inventory` SET `receive_shipment` = '$inventory_dashboard' WHERE tab='receive_shipment' AND accordion='receive_shipment'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config_inventory` (`tab`, `accordion`, `receive_shipment`) VALUES ('receive_shipment', 'receive_shipment', '$inventory_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    echo '<script type="text/javascript"> window.location.replace("field_config_receive_shipment.php"); </script>';
}
?>
<script type="text/javascript">
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Inventory Settings</h1>
<?php if(get_config($dbc, 'inventory_default_select_all') == 1) { ?>
<div class="gap-left gap-top double-gap-bottom"><a href="inventory.php?category=dispall_31V2irt2u3e5S3s1f2ADe3_31" class="btn config-btn">Back to Dashboard</a></div>
<?php } else { ?>
<div class="gap-left double-gap-bottom"><a href="inventory.php?category=Top" class="btn config-btn">Back to Dashboard</a></div>
<?php } ?><!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<!-- <div class="panel-group" id="accordion2"> -->

    <?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT receive_shipment FROM field_config_inventory WHERE tab='receive_shipment' AND accordion='receive_shipment'"));
    $inventory_dashboard_config = ','.$get_field_config['receive_shipment'].',';

    $active_tab = '';
    $active_field = '';
    $active_dashboard_tab = '';
    $active_dashboard = '';
    $active_general = '';
    $active_rs = '';

    if($_GET['type'] == 'tab') {
        $active_tab = 'active_tab';
    }
    if($_GET['type'] == 'field') {
        $active_field = 'active_tab';
    }
	if($_GET['type'] == 'dashboard_tab') {
        $active_dashboard_tab = 'active_tab';
    }
    if($_GET['type'] == 'dashboard') {
        $active_dashboard = 'active_tab';
    }
    if($_GET['type'] == 'general') {
        $active_general = 'active_tab';
    }
    if($_GET['type'] == 'rs') {
        $active_rs = 'active_tab';
    }

    echo "<a href='field_config_inventory.php?type=tab'><button type='button' class='btn brand-btn mobile-block ".$active_tab."' >Tabs</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_inventory.php?type=field'><button type='button' class='btn brand-btn mobile-block ".$active_field."' >Fields</button></a>&nbsp;&nbsp;";
	echo "<a href='field_config_inventory.php?type=dashboard_tab'><button type='button' class='btn brand-btn mobile-block ".$active_dashboard_tab."' >Dashboard Sub Tabs</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_inventory.php?type=dashboard'><button type='button' class='btn brand-btn mobile-block ".$active_dashboard."' >Dashboard</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_inventory.php?type=general'><button type='button' class='btn brand-btn mobile-block ".$active_general."' >General</button></a>&nbsp;&nbsp;";
    echo "<a href='field_config_receive_shipment.php?type=rs'><button type='button' class='btn brand-btn mobile-block ".$active_rs."' >Receive Shipment</button></a>&nbsp;&nbsp;";
	//echo "<a href='field_config_order_lists.php'><button type='button' class='btn brand-btn mobile-block' >Order Lists</button></a>&nbsp;&nbsp;";
	echo "<a href='field_config_inventory.php?type=digi_count'><button type='button' class='btn brand-btn mobile-block' >Digital Inventory Count</button></a>&nbsp;&nbsp;";
	echo "<a href='field_config_inventory.php?type=impexp'><button type='button' class='btn brand-btn mobile-block' >Import/Export</button></a>";

    echo '<br><br><Br>';
    ?>

    <div class="panel-group" id="accordion2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add/remove fields."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_1" >
                        Receive Shipment <span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_1" class="panel-collapse collapse">
                <div class="panel-body">

                    <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Inventory".',') !== FALSE) { echo " checked"; } ?> value="Inventory" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Inventory
                    <input type="checkbox" <?php if (strpos($inventory_dashboard_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="inventory_dashboard[]">&nbsp;&nbsp;Quantity
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

                </div>
            </div>
        </div>

    </div>

    <br>

    <div class="form-group">
        <div class="col-sm-6">
            <!--<a href="inventory.php?category=Top" class="btn brand-btn pull-right">Back</a>-->
			<?php if(get_config($dbc, 'inventory_default_select_all') == 1) { ?>
			<div class="double-gap-bottom"><a href="inventory.php?category=dispall_31V2irt2u3e5S3s1f2ADe3_31" class="btn brand-btn btn-lg">Back</a></div>
			<?php } else { ?>
			<div class="double-gap-bottom"><a href="inventory.php?category=Top" class="btn brand-btn btn-lg">Back</a></div>
			<?php } ?>
		</div>
        <div class="col-sm-6">
            <button	type="submit" name="inv_dashboard"	value="inv_dashboard" class="btn brand-btn btn-lg pull-right">Submit</button>
        </div>
    </div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>