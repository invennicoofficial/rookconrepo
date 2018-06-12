<?php
/*
Dashboard
*/
include_once ('../include.php');
checkAuthorised('purchase_order');
error_reporting(0);

if (isset($_POST['submit'])) {
    //Logo
	if (!file_exists('download')) {
		mkdir('download', 0777, true);
	}
    $purchase_order_logo = htmlspecialchars($_FILES["purchase_order_logo"]["name"], ENT_QUOTES);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='purchase_order_logo'"));
    if($get_config['configid'] > 0) {
		if($purchase_order_logo == '') {
			$logo_update = htmlspecialchars($_POST['logo_file'], ENT_QUOTES);
		} else {
			$logo_update = $purchase_order_logo;
		}
		move_uploaded_file($_FILES["purchase_order_logo"]["tmp_name"],"download/" . $logo_update);
        $query_update_employee = "UPDATE `general_configuration` SET value = '$logo_update' WHERE name='purchase_order_logo'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        move_uploaded_file($_FILES["purchase_order_logo"]["tmp_name"], "download/" . $_FILES["purchase_order_logo"]["name"]) ;
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('purchase_order_logo', '$purchase_order_logo')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Logo

    $purchase_order = implode(',',$_POST['purchase_order']);
    $purchase_order_dashboard = implode(',',$_POST['purchase_order_dashboard']);
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
    if($get_field_config['fieldconfigid'] > 0) {
        $query_update_employee = "UPDATE `field_config` SET purchase_order = '$purchase_order', purchase_order_dashboard = '$purchase_order_dashboard' WHERE `fieldconfigid` = 1";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `field_config` (`purchase_order`, `purchase_order_dashboard`) VALUES ('$purchase_order', '$purchase_order_dashboard')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    //Tax
    $purchase_order_tax = '';
    for($i = 0; $i < count($_POST['purchase_order_tax_name']); $i++) {
        if($_POST['purchase_order_tax_name'][$i] != '') {
            $purchase_order_tax .= filter_var($_POST['purchase_order_tax_name'][$i],FILTER_SANITIZE_STRING).'**'.$_POST['purchase_order_tax_rate'][$i].'**'.$_POST['purchase_order_tax_number'][$i].'**'.$_POST['purchase_order_tax_exemption_'.$i].'*#*';
        }
    }

    $purchase_order_tax = rtrim($purchase_order_tax, "*#*'");
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='purchase_order_tax'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$purchase_order_tax' WHERE name='purchase_order_tax'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('purchase_order_tax', '$purchase_order_tax')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    //Tax

    // Address
    $purchase_order_company_address = filter_var(htmlentities($_POST['purchase_order_company_address']),FILTER_SANITIZE_STRING);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='purchase_order_company_address'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$purchase_order_company_address' WHERE name='purchase_order_company_address'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('purchase_order_company_address', '$purchase_order_company_address')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // Address

    // Footer
    $purchase_order_footer = filter_var($_POST['purchase_order_footer'],FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='purchase_order_footer'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$purchase_order_footer' WHERE name='purchase_order_footer'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('purchase_order_footer', '$purchase_order_footer')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
    // Footer

    echo '<script type="text/javascript"> window.location.replace("field_config_purchase_order.php"); </script>';

}
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#add_tax_button').on( 'click', function () {
        var clone = $('.additional_tax').clone();

        var numItems = $('.tax_exemption_div').length;
        clone.find('.tax_exemption').attr("name", "purchase_order_tax_exemption_"+numItems);
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
<a href="purchase_order.php" class="btn config-btn">Back</a>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->
<br><br>
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_logo" >
                    Logo for PO<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_logo" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                $purchase_order_logo = get_config($dbc, 'purchase_order_logo');
                ?>

                <div class="form-group">
                <label for="file[]" class="col-sm-4 control-label">Upload Logo
                <span class="popover-examples list-inline">&nbsp;
                <a href="#job_file" data-toggle="tooltip" data-placement="top" title="File name cannot contain apostrophes, quotations or commas"><img src="<?php echo WEBSITE_URL; ?>/img/info.png" width="20"></a>
                </span>
                :</label>
                <div class="col-sm-8">
                <?php if($purchase_order_logo != '') {
                    echo '<a href="download/'.$purchase_order_logo.'" target="_blank">View</a>';
                    ?>
                    <input type="hidden" name="logo_file" value="<?php echo $purchase_order_logo; ?>" />
                    <input name="purchase_order_logo" type="file" data-filename-placement="inside" class="form-control" />
                  <?php } else { ?>
                  <input name="purchase_order_logo" type="file" data-filename-placement="inside" class="form-control" />
                  <?php } ?>
                </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_pay" >
                   Header Information for PDF<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_pay" class="panel-collapse collapse">
            <div class="panel-body">

                <div class="form-group">
                    <label for="office_country" class="col-sm-4 control-label">Company Address, Phone, Email etc </em></label>
                    <div class="col-sm-8">
                        <textarea name="purchase_order_company_address" rows="3" cols="50" class="form-control"><?php echo get_config($dbc, 'purchase_order_company_address'); ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field" >
                    Choose Fields for Purchase Order<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_field" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT purchase_order FROM field_config"));
                $value_config = ','.$get_field_config['purchase_order'].',';
                ?>

                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Business
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Project".',') !== FALSE) { echo " checked"; } ?> value="Project" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;<?= PROJECT_NOUN ?>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket".',') !== FALSE) { echo " checked"; } ?> value="Ticket" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;<?= TICKET_NOUN ?>
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Work Order".',') !== FALSE) { echo " checked"; } ?> value="Work Order" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Work Order
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Vendor
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Issue Date".',') !== FALSE) { echo " checked"; } ?> value="Issue Date" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Issue Date
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Qty".',') !== FALSE) { echo " checked"; } ?> value="Qty" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Qty
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Desc".',') !== FALSE) { echo " checked"; } ?> value="Desc" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Desc
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Grade".',') !== FALSE) { echo " checked"; } ?> value="Grade" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Grade
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Tag".',') !== FALSE) { echo " checked"; } ?> value="Tag" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Tag
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Detail".',') !== FALSE) { echo " checked"; } ?> value="Detail" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Detail
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Price per unit($)".',') !== FALSE) { echo " checked"; } ?> value="Price per unit($)" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Price per unit($)
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost($)".',') !== FALSE) { echo " checked"; } ?> value="Cost($)" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Cost($)
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Description".',') !== FALSE) { echo " checked"; } ?> value="Description" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Description
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Cost".',') !== FALSE) { echo " checked"; } ?> value="Cost" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Cost
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Sales Tax %".',') !== FALSE) { echo " checked"; } ?> value="Sales Tax %" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Sales Tax %
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Total Cost".',') !== FALSE) { echo " checked"; } ?> value="Total Cost" style="height: 20px; width: 20px;" name="purchase_order[]">&nbsp;&nbsp;Total Cost
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
                    Choose Fields for PO Dashboard<span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>

        <div id="collapse_product" class="panel-collapse collapse">
            <div class="panel-body" id="no-more-tables">
                <?php
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT purchase_order_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['purchase_order_dashboard'].',';
                ?>
                <table border='2' cellpadding='10' class='table'>
                    <tr>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."PO#".',') !== FALSE) { echo " checked"; } ?> value="PO#" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;PO#
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Business".',') !== FALSE) { echo " checked"; } ?> value="Business" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Business
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Project".',') !== FALSE) { echo " checked"; } ?> value="Project" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Project
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Ticket".',') !== FALSE) { echo " checked"; } ?> value="Ticket" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Ticket
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Work Order".',') !== FALSE) { echo " checked"; } ?> value="Work Order" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Work Order
                        </td>

                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Issue Date".',') !== FALSE) { echo " checked"; } ?> value="Issue Date" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Issue Date
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Vendor".',') !== FALSE) { echo " checked"; } ?> value="Vendor" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Vendor
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."PDF".',') !== FALSE) { echo " checked"; } ?> value="PDF" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;PDF
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Created By".',') !== FALSE) { echo " checked"; } ?> value="Created By" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Created By
                        </td>
                        <td>
                            <input type="checkbox" <?php if (strpos($value_config, ','."Edited By".',') !== FALSE) { echo " checked"; } ?> value="Edited By" style="height: 20px; width: 20px;" name="purchase_order_dashboard[]">&nbsp;&nbsp;Edited By
                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>

    <?php
    $value_config = get_config($dbc, 'purchase_order_tax');
    ?>

    <!--
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
                </div>

                <?php
                $purchase_order_tax = explode('*#*',$value_config);

                $total_count = mb_substr_count($value_config,'*#*');
                for($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
                    $purchase_order_tax_name_rate = explode('**',$purchase_order_tax[$eq_loop]);
                ?>
                    <div class="clearfix"></div>
                    <div class="form-group clearfix">
                      <div class="col-sm-2">
                            <input name="purchase_order_tax_name[]" value="<?php echo $purchase_order_tax_name_rate[0];?>" type="text" class="form-control quantity" />
                        </div>
                        <div class="col-sm-2">
                            <input name="purchase_order_tax_rate[]" value="<?php echo $purchase_order_tax_name_rate[1]; ?>" type="text" class="form-control category" />
                        </div>
                        <div class="col-sm-2">
                            <input name="purchase_order_tax_number[]" value="<?php echo $purchase_order_tax_name_rate[2]; ?>" type="text" class="form-control category" />
                        </div>
                    </div>
                <?php } ?>

                <div class="additional_tax">
                <div class="clearfix"></div>
                <div class="form-group clearfix" width="100%">
                    <div class="col-sm-2">
                        <input name="purchase_order_tax_name[]" type="text" class="form-control price" />
                    </div>
                    <div class="col-sm-2">
                        <input name="purchase_order_tax_rate[]" value="0" type="text" class="form-control rate" />
                    </div>
                    <div class="col-sm-2">
                        <input name="purchase_order_tax_number[]" type="text" class="form-control" />
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
    -->

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
                    <label for="office_country" class="col-sm-4 control-label">Purchase Order PDF Footer Text:<br><em>(e.g. - company name, address, phone, etc.)</em></label>
                    <div class="col-sm-8">
                      <input name="purchase_order_footer" value="<?php echo get_config($dbc, 'purchase_order_footer'); ?>" type="text" class="form-control"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="form-group">
    <div class="col-sm-4 clearfix">
        <a href="purchase_order.php" class="btn config-btn btn-lg pull-right">Back</a>
		<!--<a href="#" class="btn config-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
        <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
    </div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>