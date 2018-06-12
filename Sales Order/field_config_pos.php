<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
 * Sales Orders Settings
 */
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    //Logo
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $pos_logo = htmlspecialchars($_FILES["pos_logo"]["name"], ENT_QUOTES);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_logo'"));
    if($get_config['configid'] > 0) {
		if($pos_logo == '') {
			$logo_update = $_POST['logo_file'];
		} else {
			$logo_update = $pos_logo;
		}
		move_uploaded_file($_FILES["pos_logo"]["tmp_name"],"download/" . $logo_update);
        $query_update_employee = "UPDATE `general_configuration` SET value = '$logo_update' WHERE name='sales_order_logo'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        move_uploaded_file($_FILES["pos_logo"]["tmp_name"], "download/" . $_FILES["pos_logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_logo', '$pos_logo')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Logo

    //Design

    $pos_design = $_POST['pos_design'];
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_design'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$pos_design' WHERE name='sales_order_design'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_design', '$pos_design')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Design

    $pos = implode(',',$_POST['pos']);
    $pos_dashboard = implode(',',$_POST['pos_dashboard']);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET sales_order = '$pos', sales_order_dashboard = '$pos_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`sales_order`, `sales_order_dashboard`) VALUES ('$pos', '$pos_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    //Tax
    $pos_tax = '';
    for($i = 0; $i < count($_POST['pos_tax_name']); $i++) {
        if($_POST['pos_tax_name'][$i] != '') {
            //$column = $_POST['pos_tax_name'][$i];
            //$result_column = mysqli_query($dbc, "SHOW COLUMNS FROM `report_sales` LIKE '$column'");
            //$exists_column = (mysqli_num_rows($result_column))?TRUE:FALSE;

            //if($exists_column == '') {
            //    mysqli_query($dbc, "ALTER TABLE `report_sales` ADD `$column` VARCHAR(50) NULL");
            //}

            $pos_tax .= filter_var($_POST['pos_tax_name'][$i],FILTER_SANITIZE_STRING).'**'.$_POST['pos_tax_rate'][$i].'**'.$_POST['pos_tax_number'][$i].'**'.$_POST['pos_tax_exemption_'.$i].'*#*';
        }
    }

    $pos_tax = rtrim($pos_tax, "*#*'");
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_tax'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$pos_tax' WHERE name='sales_order_tax'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_tax', '$pos_tax')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Tax

    // invoice_outbound_email
    $invoice_outbound_email = filter_var($_POST['invoice_outbound_email'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_invoice_outbound_email'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$invoice_outbound_email' WHERE name='sales_order_invoice_outbound_email'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_invoice_outbound_email', '$invoice_outbound_email')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // invoice_outbound_email

	//Sales Order Statuses
    $sales_order_statuses = filter_var($_POST['sales_order_statuses'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='sales_order_statuses'"));
    if($get_config['configid'] > 0) {
        $query_update_config  = "UPDATE `general_configuration` SET `value`='$sales_order_statuses' WHERE `name`='sales_order_statuses'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_statuses', '$sales_order_statuses')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Sales Order Statuses

	//Sales Order Next Actions
    $sales_order_next_actions = filter_var($_POST['sales_order_next_actions'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='sales_order_next_actions'"));
    if($get_config['configid'] > 0) {
        $query_update_config  = "UPDATE `general_configuration` SET `value`='$sales_order_next_actions' WHERE `name`='sales_order_next_actions'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_next_actions', '$sales_order_next_actions')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Sales Order Next Actions

	// POS Tile Title
    $pos_tile_titler = filter_var($_POST['pos_tile_titler'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_tile_titler'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$pos_tile_titler' WHERE name='sales_order_tile_titler'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_tile_titler', '$pos_tile_titler')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // POS Tile Title

		// POS Archive After x Days
    $archive_after_num_days = filter_var($_POST['archive_after_num_days'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_archive_after_num_days'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$archive_after_num_days' WHERE name='sales_order_archive_after_num_days'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_archive_after_num_days', '$archive_after_num_days')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // POS Archive After x Days

    // payment_type
    $invoice_payment_types = filter_var($_POST['invoice_payment_types'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_invoice_payment_types'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = 'Pay Now,Net 30,Net 60,Net 90,Net 120,$invoice_payment_types' WHERE name='sales_order_invoice_payment_types'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_invoice_payment_types', 'Pay Now,Net 30,Net 60,Net 90,Net 120,$invoice_payment_types')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // payment_type

    // Footer
    $invoice_footer = filter_var($_POST['invoice_footer'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_invoice_footer'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$invoice_footer' WHERE name='sales_order_invoice_footer'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_invoice_footer', '$invoice_footer')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // Footer

    echo '<script type="text/javascript"> window.location.replace("field_config_pos.php"); </script>';

}
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#add_tax_button').on( 'click', function () {
        var clone = $('.additional_tax').clone();

        var numItems = $('.tax_exemption_div').length;
        clone.find('.tax_exemption').attr("name", "pos_tax_exemption_"+numItems);
        clone.find('.form-control').val('');
        clone.find('.rate').val('0');
        clone.removeClass("additional_tax");
        $('#add_here_new_tax').append(clone);
        return false;
    });
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1><?= SALES_ORDER_TILE ?></h1>
<div class="pad-left gap-top double-gap-bottom"><a href="index.php" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<div class="tab-container mobile-100-container double-gap-top double-gap-bottom">
	<div class="pull-left tab">
		<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to create your desired fields for <?= SALES_ORDER_NOUN ?>."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href='field_config_pos.php'><button type="button" class="btn brand-btn mobile-block active_tab">General</button></a>
	</div>
	<div class="pull-left tab">
		<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to create Promotional items."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<a href='field_config_promotion.php'><button type="button" class="btn brand-btn mobile-block">Promotion</button></a>
	</div>
    <div class="pull-left tab">
        <span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to create Staff Collaboration Groups."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="field_config_staff_groups.php"><button type="button" class="btn brand-btn mobile-block">Staff Collaboration Groups</button></a>
    </div>
	
	<div class="clearfix double-gap-bottom"></div>
</div>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_logo" >
                    Logo for <?= SALES_ORDER_NOUN ?> PDF<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_logo" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $pos_logo = get_config($dbc, 'sales_order_logo');
                ?>

                <div class="form-group">
                <label for="file[]" class="col-sm-4 control-label">Upload Logo
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
                </span>
                :</label>
                <div class="col-sm-8">
                <?php if($pos_logo != '') {
                    echo '<a href="download/'.$pos_logo.'" target="_blank">View</a>';
                    ?>
                    <input type="hidden" name="logo_file" value="<?php echo $pos_logo; ?>" />
                    <input name="pos_logo" type="file" data-filename-placement="inside" class="form-control" />
                  <?php } else { ?>
                  <input name="pos_logo" type="file" data-filename-placement="inside" class="form-control" />
                  <?php } ?>
                </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_design" >
                    Design of <?= SALES_ORDER_NOUN ?> PDF<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_design" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $pos_design = get_config($dbc, 'sales_order_design');
                ?>
                <label><input style="height: 30px; width: 30px;" class="tax_exemption" <?php if ($pos_design == '1') { echo 'checked'; } ?> type="radio" name="pos_design" value="1"></label>
                <a target="_blank" href="../img/invoice_design1.png"><img src="../img/invoice_design1.png" width="100" height="100" border="0" alt=""></a>

                &nbsp;&nbsp;&nbsp;

                <label><input style="height: 30px; width: 30px;" class="tax_exemption" <?php if ($pos_design == '2') { echo 'checked'; } ?> type="radio" name="pos_design" value="2"></label>
                <a target="_blank" href="../img/invoice_design2.png"><img src="../img/invoice_design2.png" width="100" height="100" border="0" alt=""></a>

				&nbsp;&nbsp;&nbsp;

                <label><input style="height: 30px; width: 30px;" class="tax_exemption" <?php if ($pos_design == '3') { echo 'checked'; } ?> type="radio" name="pos_design" value="3"></label>
                <a target="_blank" href="../img/invoice_design3.png"><img src="../img/invoice_design3.png" width="100" height="100" border="0" alt=""></a>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Creating a <?= SALES_ORDER_NOUN ?><span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales_order FROM field_config"));
                $value_config = ','.$get_field_config['sales_order'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Date".',') !== FALSE) { echo " checked"; } ?> value="Invoice Date" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Order Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Customer
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Product Pricing".',') !== FALSE) { echo " checked"; } ?> value="Product Pricing" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Pricing
                        </td>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Send Outbound Invoice".',') !== FALSE) { echo " checked"; } ?> value="Send Outbound Invoice" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Send Outbound <?= SALES_ORDER_NOUN ?>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Discount".',') !== FALSE) { echo " checked"; } ?> value="Discount" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Discount
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Delivery".',') !== FALSE) { echo " checked"; } ?> value="Delivery" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Delivery/Pickup
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Assembly".',') !== FALSE) { echo " checked"; } ?> value="Assembly" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Assembly
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Tax".',') !== FALSE) { echo " checked"; } ?> value="Tax" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Tax
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Total Price".',') !== FALSE) { echo " checked"; } ?> value="Total Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Total Price
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Payment Type".',') !== FALSE) { echo " checked"; } ?> value="Payment Type" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Payment Type
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Comment".',') !== FALSE) { echo " checked"; } ?> value="Comment" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Comment
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Tax Exemption".',') !== FALSE) { echo " checked"; } ?> value="Tax Exemption" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Tax Exemption
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Created/Sold By".',') !== FALSE) { echo " checked"; } ?> value="Created/Sold By" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Created/Sold By
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ship Date".',') !== FALSE) { echo " checked"; } ?> value="Ship Date" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Ship Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Products".',') !== FALSE) { echo " checked"; } ?> value="Products" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Inventory
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Misc Item".',') !== FALSE) { echo " checked"; } ?> value="Misc Item" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Misc Item
                        </td>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."servServices".',') !== FALSE) { echo " checked"; } ?> value="servServices" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Services
                        </td>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."prodProducts".',') !== FALSE) { echo " checked"; } ?> value="prodProducts" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Products
                        </td>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vplProducts".',') !== FALSE) { echo " checked"; } ?> value="vplProducts" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Vendor Price List
                        </td>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Deposit Paid".',') !== FALSE) { echo " checked"; } ?> value="Deposit Paid" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Deposit Paid
                        </td>

                    </tr>
					<tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Due Date".',') !== FALSE) { echo " checked"; } ?> value="Due Date" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Due Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Pricing by Line Item".',') !== FALSE) { echo " checked"; } ?> value="Pricing by Line Item" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Pricing by Line Item
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_product" >
                    Choose Fields for Inventory<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_product" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Category".',') !== FALSE) { echo " checked"; } ?> value="Category" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Part#".',') !== FALSE) { echo " checked"; } ?> value="Part#" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Part#
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Name".',') !== FALSE) { echo " checked"; } ?> value="Name" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Size and Color".',') !== FALSE) { echo " checked"; } ?> value="Size and Color" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Size and Color
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Price".',') !== FALSE) { echo " checked"; } ?> value="Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Quantity".',') !== FALSE) { echo " checked"; } ?> value="Quantity" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Quantity
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_service" >
                    Choose Fields for Services<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_service" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."servCategory".',') !== FALSE) { echo " checked"; } ?> value="servCategory" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."servHeading".',') !== FALSE) { echo " checked"; } ?> value="servHeading" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."servPrice".',') !== FALSE) { echo " checked"; } ?> value="servPrice" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."servQuantity".',') !== FALSE) { echo " checked"; } ?> value="servQuantity" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Quantity
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_productprod" >
                    Choose Fields for Products<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_productprod" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."prodCategory".',') !== FALSE) { echo " checked"; } ?> value="prodCategory" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."prodHeading".',') !== FALSE) { echo " checked"; } ?> value="prodHeading" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Heading
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."prodPrice".',') !== FALSE) { echo " checked"; } ?> value="prodPrice" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."prodQuantity".',') !== FALSE) { echo " checked"; } ?> value="prodQuantity" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Quantity
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_productvpl" >
                    Choose Fields for Vendor Price List<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_productvpl" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vplCategory".',') !== FALSE) { echo " checked"; } ?> value="vplCategory" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Category
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vplPart#".',') !== FALSE) { echo " checked"; } ?> value="vplPart#" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Part#
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vplName".',') !== FALSE) { echo " checked"; } ?> value="vplName" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Name
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vpl Size and Color".',') !== FALSE) { echo " checked"; } ?> value="vpl Size and Color" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Size and Color
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vplColor".',') !== FALSE) { echo " checked"; } ?> value="vplColor" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Color
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vplPrice".',') !== FALSE) { echo " checked"; } ?> value="vplPrice" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."vplQuantity".',') !== FALSE) { echo " checked"; } ?> value="vplQuantity" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Quantity
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>

    <?php if (strpos($value_config, ','."Product Pricing".',') !== FALSE) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pp" >
                    Choose Fields for Pricing<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_pp" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { echo " checked"; } ?> value="Client Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Client Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { echo " checked"; } ?> value="Admin Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Admin Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { echo " checked"; } ?> value="Commercial Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Commercial Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { echo " checked"; } ?> value="Wholesale Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Wholesale Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Final Retail Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Final Retail Price
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Preferred Price".',') !== FALSE) { echo " checked"; } ?> value="Preferred Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Preferred Price
                        </td>
                    </tr>
                    <tr>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Purchase Order Price".',') !== FALSE) { echo " checked"; } ?> value="Purchase Order Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Purchase Order Price
                        </td>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Sales Order Price".',') !== FALSE) { echo " checked"; } ?> value="Sales Order Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;<?= SALES_ORDER_NOUN ?> Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Web Price".',') !== FALSE) { echo " checked"; } ?> value="Web Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Web Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Drum Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Drum Unit Cost" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Drum Unit Cost
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Drum Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Drum Unit Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Drum Unit Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Tote Unit Cost".',') !== FALSE) { echo " checked"; } ?> value="Tote Unit Cost" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Tote Unit Cost
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Tote Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Tote Unit Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Tote Unit Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Suggested Retail Price".',') !== FALSE) { echo " checked"; } ?> value="Suggested Retail Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Suggested Retail Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Rush Price".',') !== FALSE) { echo " checked"; } ?> value="Rush Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Rush Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Unit Price".',') !== FALSE) { echo " checked"; } ?> value="Unit Price" style="height: 20px; width: 20px;" name="pos[]">&nbsp;&nbsp;Unit Price
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_dashboard" >
                    Choose Fields for <?= SALES_ORDER_NOUN ?> Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_dashboard" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales_order_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['sales_order_dashboard'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice #".',') !== FALSE) { echo " checked"; } ?> value="Invoice #" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;<?= SALES_ORDER_NOUN ?> #
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice Date".',') !== FALSE) { echo " checked"; } ?> value="Invoice Date" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Order Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Customer".',') !== FALSE) { echo " checked"; } ?> value="Customer" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Customer
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Total Price".',') !== FALSE) { echo " checked"; } ?> value="Total Price" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Total Price
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Payment Type".',') !== FALSE) { echo " checked"; } ?> value="Payment Type" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Payment Type
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Invoice PDF".',') !== FALSE) { echo " checked"; } ?> value="Invoice PDF" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;<?= SALES_ORDER_NOUN ?> PDF
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Comment".',') !== FALSE) { echo " checked"; } ?> value="Comment" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Comment
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Status".',') !== FALSE) { echo " checked"; } ?> value="Status" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Status
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Send to Client".',') !== FALSE) { echo " checked"; } ?> value="Send to Client" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Send to Client
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Delivery/Shipping Type".',') !== FALSE) { echo " checked"; } ?> value="Delivery/Shipping Type" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Delivery/Shipping Type
                        </td>

                    </tr>
					<tr>
						<td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Send to Anyone".',') !== FALSE) { echo " checked"; } ?> value="Send to Anyone" style="height: 20px; width: 20px;" name="pos_dashboard[]">&nbsp;&nbsp;Send to Anyone
                        </td>
					</tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_statuses" >
                   <?= SALES_ORDER_NOUN ?> Statuses<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_statuses" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-4">Add Statuses separated by a comma in the order you want them on the dashboard:</div>
                    <label for="office_country" class="col-sm-4 control-label">Sale Order Statuses:</label>
                    <div class="col-sm-8">
                        <input name="sales_order_statuses" value="<?= empty(get_config($dbc, 'sales_order_statuses')) ? 'Opportunity,With Client,Fulfillment' : get_config($dbc, 'sales_order_statuses'); ?>" type="text" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_next_actions" >
                   <?= SALES_ORDER_NOUN ?> Next Actions<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_next_actions" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-4">Add Next Actions separated by a comma:</div>
                    <label for="office_country" class="col-sm-4 control-label"><?= SALES_ORDER_NOUN ?> Next Actions:</label>
                    <div class="col-sm-8">
                        <input name="sales_order_next_actions" value="<?= empty(get_config($dbc, 'sales_order_next_actions')) ? 'Phone Call,Email' : get_config($dbc, 'sales_order_next_actions'); ?>" type="text" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>

	 <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_titler" >
                   Tile Title<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_titler" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Title of <?= SALES_ORDER_NOUN ?> Tile on the Home Screen:</label>
                    <div class="col-sm-8">
                      <input name="pos_tile_titler" value="<?php if(get_config($dbc, 'sales_order_tile_titler') == '' || get_config($dbc, 'sales_order_tile_titler') == NULL ) { echo "Sales Order"; } else { echo get_config($dbc, 'sales_order_tile_titler'); } ?>" type="text" class="form-control"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

	<div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_archiveinvoicer" >
                   Archiving <?= SALES_ORDER_TILE ?><span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_archiveinvoicer" class="panel-collapse collapse">
            <div class="panel-body">
				<?php include_once ('javascript_include.php'); ?>
                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Auto-Archive <?= SALES_ORDER_TILE ?> After X Days:</label>
                    <div class="col-sm-8">
					  Enabled:  <input onclick="handleClick(this);" <?php if(get_config($dbc, 'sales_order_archive_after_num_days') !== '' && get_config($dbc, 'sales_order_archive_after_num_days') !== NULL) { echo "checked"; } ?> type='radio' name='yesornoarchiveinvoice' class='yesornoarchiveinvoice' value='yes'>
					  Disabled: <input onclick="handleClick(this);" <?php if(get_config($dbc, 'sales_order_archive_after_num_days') == '' || get_config($dbc, 'sales_order_archive_after_num_days') == NULL) { echo "checked"; } ?> type='radio' name='yesornoarchiveinvoice' class='yesornoarchiveinvoice' value='no'>
                    </div>

                </div>

				<div class="form-group hide_numofdays" <?php if(get_config($dbc, 'sales_order_archive_after_num_days') == '' || get_config($dbc, 'sales_order_archive_after_num_days') == NULL) { echo "style='display:none;'"; } ?>>
                    <label for="office_country" class="col-sm-4 control-label hide_numofdays">Number of Days Until <?= SALES_ORDER_TILE ?> get Archived After Creation Date:</label>
                    <div class="col-sm-8 hide_numofdays">
					  <input name="archive_after_num_days" value="<?php echo get_config($dbc, 'sales_order_archive_after_num_days'); ?>" type="number" class="form-control hide_numofdays2"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pay" >
                   Payment Type Options<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_pay" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Payment Type Options:<br><em>(separate by a comma)</em></label>
                    <div class="col-sm-8">
					<?php
						$invoice_pay_typers = str_replace('Pay Now,Net 30,Net 60,Net 90,Net 120,', '', get_config($dbc, 'sales_order_invoice_payment_types'));
					?>
                      <input name="invoice_payment_types" value="<?php echo $invoice_pay_typers; ?>" type="text" class="form-control"/>
                    </div>
                </div>
				<div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Preset Payment Type Options:</label>
                    <div class="col-sm-8">
                      <ul><li>Pay Now</li>
					  <li>Net 30</li>
					  <li>Net 60</li>
					  <li>Net 90</li>
					  <li>Net 120</li>
					  </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_out" >
                   Outbound <?= SALES_ORDER_NOUN ?> Email<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_out" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">

                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Outbound <?= SALES_ORDER_NOUN ?> Email:<br><em>(separate by a comma)</em></label>
                    <div class="col-sm-8">
                      <input name="invoice_outbound_email" value="<?php echo get_config($dbc, 'sales_order_invoice_outbound_email'); ?>" type="text" class="form-control"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_tax" >
                    Set Tax Names & Rates<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_tax" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group clearfix">
                    <label class="col-sm-2 text-center">Name</label>
                    <label class="col-sm-2 text-center">Rate(%)<br><em>(add number without % sign)</em></label>
                    <label class="col-sm-2 text-center">Tax Number</label>
                    <label class="col-sm-2">Tax Exempt</label>
                </div>

                <?php
                $value_config = get_config($dbc, 'sales_order_tax');

                $pos_tax = explode('*#*',$value_config);

                $total_count = mb_substr_count($value_config,'*#*');
                for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
                    $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);
                ?>
                    <div class="clearfix"></div>
                    <div class="form-group clearfix">
                      <div class="col-sm-2">
                            <input name="pos_tax_name[]" value="<?php echo $pos_tax_name_rate[0];?>" type="text" class="form-control quantity" />
                        </div>
                        <div class="col-sm-2">
                            <input name="pos_tax_rate[]" value="<?php echo $pos_tax_name_rate[1]; ?>" type="text" class="form-control category" />
                        </div>
                        <div class="col-sm-2">
                            <input name="pos_tax_number[]" value="<?php echo $pos_tax_name_rate[2]; ?>" type="text" class="form-control category" />
                        </div>
                        <div class="col-sm-2">
                          <div class="radio tax_exemption_div">
                            <label><input class="tax_exemption" type="radio" <?php if ($pos_tax_name_rate[3] == 'Yes') { echo 'checked'; } ?> name="pos_tax_exemption_<?php echo $eq_loop;?>" value="Yes">Yes</label>
                            &nbsp; &nbsp;
                            <label><input class="tax_exemption" type="radio" <?php if ($pos_tax_name_rate[3] == 'No' || $pos_tax_name_rate[3] == '') { echo 'checked'; } ?> name="pos_tax_exemption_<?php echo $eq_loop;?>" value="No">No</label>
                          </div>
                        </div>

                    </div>
                <?php } ?>

                <div class="additional_tax">
                <div class="clearfix"></div>
                <div class="form-group clearfix" width="100%">
                    <div class="col-sm-2">
                        <input name="pos_tax_name[]" type="text" class="form-control price" />
                    </div>
                    <div class="col-sm-2">
                        <input name="pos_tax_rate[]" value="0" type="text" class="form-control rate" />
                    </div>
                    <div class="col-sm-2">
                        <input name="pos_tax_number[]" type="text" class="form-control" />
                    </div>
                    <div class="col-sm-2">
                      <div class="radio tax_exemption_div">
                        <label><input class="tax_exemption" type="radio" name="pos_tax_exemption_<?php echo $eq_loop;?>" value="Yes">Yes</label>
                        &nbsp; &nbsp;
                        <label><input class="tax_exemption" type="radio" name="pos_tax_exemption_<?php echo $eq_loop;?>" value="No">No</label>
                      </div>
                    </div>
                </div>

                </div>

                <div id="add_here_new_tax"></div>

                <div class="col-sm-8 col-sm-offset-4 triple-gap-bottom">
                    <button id="add_tax_button" class="btn brand-btn mobile-block">Add</button>
                </div>


            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_footer" >
                    Footer Text<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_footer" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label"><?= SALES_ORDER_NOUN ?> PDF Footer Text:<br><em>(e.g. - company name, address, phone, etc.)</em></label>
                    <div class="col-sm-8">
                      <input name="invoice_footer" value="<?php echo get_config($dbc, 'sales_order_invoice_footer'); ?>" type="text" class="form-control"/>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>

<div class="form-group">
    <div class="col-sm-6">
        <span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Clicking this will discard your <?= SALES_ORDER_NOUN ?> settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
        <a href="index.php" class="btn config-btn btn-lg">Back</a>
		<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
	</div>
	<div class="col-sm-6">
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
		<span class="popover-examples list-inline pull-right" style="margin:15px 3px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click this to finalize <?= SALES_ORDER_NOUN ?> settings."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>
<div class="pull-right gap-top gap-right gap-bottom">
    <a href="index.php" class="btn brand-btn">Cancel</a>
    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
</div>