<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('material');

error_reporting(0);

if (isset($_POST['submit'])) {
    $material = implode(',',$_POST['material']);
    $material_dashboard = implode(',',$_POST['material_dashboard']);

    if (strpos(','.$material.',',','.'Category,Material Name'.',') === false) {
        $material = 'Category,Material Name,'.$material;
    }
    if (strpos(','.$material_dashboard.',',','.'Category,Material Name'.',') === false) {
        $material_dashboard = 'Category,Material Name,'.$material_dashboard;
    }

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET material = '$material', material_dashboard = '$material_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`material`, `material_dashboard`) VALUES ('$material', '$material_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $material_minbin_email = filter_var($_POST['material_minbin_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='material_minbin_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$material_minbin_email' WHERE name='material_minbin_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('material_minbin_email', '$material_minbin_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $material_order_list = filter_var($_POST['material_order_list'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='material_order_list'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$material_order_list' WHERE name='material_order_list'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('material_order_list', '$material_order_list')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    echo '<script type="text/javascript"> window.location.replace("field_config_material.php"); </script>';

}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">

<h1>Materials Settings</h1>
<div class="double-gap-top double-gap-bottom"><a href="material.php?filter=Top" class="btn config-btn gap-left">Back to Dashboard</a></div>
<!-- <a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a> -->

<form id="form1" name="form1" method="post"	action="field_config_material.php" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="These will show in your add Materials window."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Materials<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material FROM field_config"));
                $value_config = ','.$get_field_config['material'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Code".',') !== FALSE) { echo " checked"; } ?> value="Code" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Code
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Sub-Category".',') !== FALSE) { echo " checked"; } ?> value="Sub-Category" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Sub-Category
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Material Name".',') !== FALSE) { echo " checked"; } ?> value="Material Name" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Material Name
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Quote Description
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Vendor
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Width".',') !== FALSE) { echo " checked"; } ?> value="Width" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Width
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Length".',') !== FALSE) { echo " checked"; } ?> value="Length" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Length
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Units".',') !== FALSE) { echo " checked"; } ?> value="Units" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Units
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Weight".',') !== FALSE) { echo " checked"; } ?> value="Unit Weight" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Unit Weight
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Weight Per Feet".',') !== FALSE) { echo " checked"; } ?> value="Weight Per Feet" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Weight Per Foot
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Quantity
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Price".',') !== FALSE) { echo " checked"; } ?> value="Price" style="height: 20px; width: 20px;" name="material[]">&nbsp;&nbsp;Price
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="These will be the fields displayed on the Materials Dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
                    Choose Fields for Materials Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['material_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Code".',') !== FALSE) { echo " checked"; } ?> value="Code" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Code
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Sub-Category".',') !== FALSE) { echo " checked"; } ?> value="Sub-Category" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Sub-Category
                        </td>
                        <td>
                            <input type="checkbox" disabled <?php if (strpos($value_config, ','."Material Name".',') !== FALSE) { echo " checked"; } ?> value="Material Name" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Material Name
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quote Description".',') !== FALSE) { echo " checked"; } ?> value="Quote Description" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Quote Description
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Vendor
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Width".',') !== FALSE) { echo " checked"; } ?> value="Width" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Width
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Length".',') !== FALSE) { echo " checked"; } ?> value="Length" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Length
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Units".',') !== FALSE) { echo " checked"; } ?> value="Units" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Units
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Weight".',') !== FALSE) { echo " checked"; } ?> value="Unit Weight" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Unit Weight
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Weight Per Feet".',') !== FALSE) { echo " checked"; } ?> value="Weight Per Feet" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Weight Per Foot
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Quantity
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Price".',') !== FALSE) { echo " checked"; } ?> value="Price" style="height: 20px; width: 20px;" name="material_dashboard[]">&nbsp;&nbsp;Price
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add a verified email here. Once the inventory has reached the Min Bin, a notification will be sent."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_email" >
                    Send Email for Min Bin<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_email" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                <label for="company_name" class="col-sm-4 control-label"><h4></h4></label>
                <div class="col-sm-12">
					Send Email for Min Bin:<br />
					<br />
					<input name="material_minbin_email" value="<?php echo get_config($dbc, 'material_minbin_email'); ?>" type="text" class="form-control">
                </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Add a verified email here. Once the inventory has reached the Min Bin, a notification will be sent."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_order_lists" >
                    Order Lists<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_order_lists" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group">
					<label for="company_name" class="col-sm-4 control-label">Use Material Order Lists:</label>
					<div class="col-sm-8">
						<?php $material_order_list = get_config($dbc, 'material_order_list'); ?>
						<label><input name="material_order_list" value="1" <?= $material_order_list > 0 ? 'checked' : '' ?> type="radio" class="form-control"> Yes</label>
						<label><input name="material_order_list" value="0" <?= $material_order_list > 0 ? '' : 'checked' ?> type="radio" class="form-control"> No</label>
					</div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-6">
        <a href="material.php?filter=Top" class="btn config-btn btn-lg">Back</a>
		<!-- <a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a> -->
	</div>
	<div class="col-sm-6">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>