<?php
/*
Add	Inventory
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit_orderform'])) {
    $vendorid = filter_var($_POST['vendorid'],FILTER_SANITIZE_STRING);
    $vpl_name = filter_var($_POST['vpl_name'],FILTER_SANITIZE_STRING);
    $projectid = filter_var($_POST['projectid'],FILTER_SANITIZE_STRING);
    $ticketid = filter_var($_POST['ticketid'],FILTER_SANITIZE_STRING);
    $businessid = filter_var($_POST['businessid'],FILTER_SANITIZE_STRING);
    $productpricing = filter_var($_POST['productpricing'],FILTER_SANITIZE_STRING);
    $subtotal = filter_var($_POST['subtotal'],FILTER_SANITIZE_STRING);
    $tax_price = filter_var($_POST['tax_price'],FILTER_SANITIZE_STRING);
    $total_before_tax = filter_var($_POST['total_before_tax'],FILTER_SANITIZE_STRING);
    $total_price = filter_var($_POST['total_price'],FILTER_SANITIZE_STRING);

    $query_insert = "INSERT INTO `purchase_orders` (`invoice_date`, `contactid`, `productpricing`, `projectid`, `ticketid`, `businessid`, `status`, `sub_total`, `total_before_tax`, `gst`, `total_price`, `vpl_name`, `created_by`) VALUES ('".date('Y-m-d')."', '$vendorid', '$productpricing', '$projectid', '$ticketid', '$businessid', 'Pending', '$subtotal', '$total_before_tax', '$tax_price', '$total_price', '$vpl_name', '".$_SESSION['contactid']."')";
    mysqli_query($dbc, $query_insert);
    $posid = mysqli_insert_id($dbc);

    if($posid > 0) {
        foreach($_POST['inventoryid'] as $i => $inventoryid) {
            $price = $_POST['vpl_price'][$i];
            $quantity = $_POST['vpl_quantity'][$i];

            if($inventoryid > 0 && $quantity > 0) {
                $query_insert = "INSERT INTO `purchase_orders_product` (`posid`, `inventoryid`, `quantity`, `price`, `type_category`) VALUES ('$posid', '$inventoryid', '$quantity', '$price', 'vpl')";
                mysqli_query($dbc, $query_insert);
            }
        }

        include('../Vendor Price List/order_form_pdf.php');
    }
} else if (isset($_POST['submit_orderform_po'])) {
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT purchase_order FROM field_config"));
    $value_config = ','.$get_field_config['purchase_order'].',';
    $vplcount = $_POST['vplcount'];
    foreach($_POST['inventoryid'] as $i => $inventoryid) {
        $price = $_POST['vpl_price'][$i];
        $quantity = $_POST['vpl_quantity'][$i];

        if($inventoryid > 0 && $quantity > 0) {
            $item = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `vendor_price_list` WHERE `inventoryid` = '$inventoryid'"));

            $html = '<div class="form-group clearfix" id="vplservices_'.$vplcount.'" width="100%;">';
            if (strpos($value_config, ','."vplCategory".',') !== FALSE) {
                $html .= '<div class="col-sm-1 expand-mobile vpltype"  style="width:20%; display:inline-block; position:relative;" id="vplcategory_'.$vplcount.'">
                    <label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="vplcategory_dd_'.$vplcount.'" name="vplcategory[]" class="chosen-select-deselect form-control vplcategory">
                        <option value="'.$item['category'].'" selected>'.$item['category'].'</option>
                    </select>
                </div>';
            }

            if (strpos($value_config, ','."vplPart#".',') !== FALSE) {
                $html .= '<div class="col-sm-1 expand-mobile"  style="width:20%; display:inline-block; position:relative;" id="vplpart_'.$vplcount.'">
                    <label for="company_name" class="col-sm-4 show-on-mob control-label">Part #:</label>
                    <select data-placeholder="Choose a Part#..." id="vplpart_dd_'.$vplcount.'" name="vplpart_no[]" class="chosen-select-deselect form-control vplpart">
                        <option value="'.$item['part_no'].'" selected>'.$item['part_no'].'</option>
                    </select>
                </div>';
            }

            if (strpos($value_config, ','."vplName".',') !== FALSE) {
                $html .= '<div class="col-sm-3 expand-mobile" id="vplproduct_'.$vplcount.'" style="width:20%; position:relative; display:inline-block;">
                    <label for="company_name" class="col-sm-4 show-on-mob control-label">Product:</label>
                        <select data-placeholder="Choose a Product..." name="vplinventoryid[]" id="vplproduct_dd_'.$vplcount.'" class="chosen-select-deselect form-control vplproduct" style="position:relative;">
                            <option value="'.$inventoryid.'" selected>'.$item['name'].'</option>
                        </select>
                </div>';
            }

            if (strpos($value_config, ','."vplPrice".',') !== FALSE) {
                $html .= '<div class="col-sm-1 expand-mobile" id="vplprice_'.$vplcount.'" style="width:10%; position:relative; display:inline-block;">
                    <label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input data-placeholder="Choose a Product..." name="vplprice[]" id="vplprice_dd_'.$vplcount.'" value="'.$price.'" onkeyup="countPOSTotal(this);" type="text" class="form-control vplprice" />
                </div>';
            }

            if (strpos($value_config, ','."vplQuantity".',') !== FALSE) {
                $html .= '<div class="col-sm-3 expand-mobile vplqt" id="vplqty_'.$vplcount.'" style="width:10%; position:relative; display:inline-block;">
                    <label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input data-placeholder="Choose a Product..." name="vplquantity[]" id="vplqty_dd_'.$vplcount.'" onkeyup="numericFilter(this); countPOSTotal(this);" value="'.$quantity.'" type="text" class="form-control vplquantity" />
                </div>';
            }
            
            $html .= '<div class="col-sm-1 m-top-mbl" >
                <a href="#" onclick="seleteService(this,\'vplservices_\',\'vplproduct_dd_\'); return false;" id="vpldeleteservices_'.$vplcount.'" class="btn brand-btn">Delete</a>
            </div>';

            $html .= '</div>';

            ?>
            <script type="text/javascript">
                window.parent.destroyInputs('#add_here_new_positionvpl');
                window.parent.$('#add_here_new_positionvpl').append('<?= trim(json_encode($html, JSON_HEX_APOS),'"') ?>');
                window.parent.initInputs('#add_here_new_positionvpl');
            </script>
            <?php $vplcount++;
        }
    } ?>
    <script type="text/javascript">
        window.parent.vplcount = <?= $vplcount ?>;
    </script>
<?php }

$page_title = "Order Form";
if($_GET['vendorid'] > 0) {
    $page_title .= ": ".(!empty(get_client($dbc, $_GET['vendorid'])) ? get_client($dbc, $_GET['vendorid']) : get_contact($dbc, $_GET['vendorid']));
}
if(!empty($_GET['vpl_name'])) {
    $page_title .= " - ".$_GET['vpl_name'];
}
?>
<script type="text/javascript">
$(document).on('change', 'select#productpricing', function() { loadPricingDetails(); });
$(document).ready(function() {
    $("#default_tax").show();
    $("#enter_tax").hide();

    $("input[name='select_tax']").change(function(){
        if ($(this).val() == '0') {
            $("#enter_tax").hide();
            $("#default_tax").show();
        } else {
            $("#default_tax").hide();
            $("#enter_tax").show();
        }
    });

    $('input[name="vpl_quantity[]"]').change(function() { calculateTotals(); });
    $('select#productpricing option').each(function() {
        if($(this).val() != '') {
            $(this).prop('selected', true);
            return;
        }
    });
    $('select#productpricing').change();
});
function loadPricingDetails() {
    var pricing = $('#productpricing').val();
    var vendorid = $('[name="vendorid"]').val();
    var vpl_name = $('[name="vpl_name"]').val();
    $.ajax({
        url: '../Vendor Price List/inventory_ajax_all.php?fill=get_vpl_pricing&pricing='+pricing+'&vendorid='+vendorid+'&vpl_name='+vpl_name,
        method: 'GET',
        success: function(response) {
            if(response != '') {
                var pricing_list = JSON.parse(response);
                $('tr.order_item').each(function() {
                    var inventoryid = $(this).find('[name="inventoryid[]"]').val();
                    if(pricing_list[inventoryid] != undefined) {
                        $(this).find('[name="vpl_price[]"]').val(pricing_list[inventoryid]);
                    } else {
                        $(this).find('[name="vpl_price[]"]').val('');
                    }
                });
            }
            calculateTotals();
        }
    });
}
function calculateTotals() {
    var subtotal = 0;
    $('tr.order_item').each(function() {
        var price = $(this).find('[name="vpl_price[]"]').val();
        var quantity = $(this).find('[name="vpl_quantity[]"]').val();
        if(price > 0 && quantity > 0) {
            subtotal += (price*quantity);
        }
    });

    var total_before_tax = subtotal;

    var tax_type = $("input[name='select_tax']:checked").val();
    var tax_rate = 0;

    if (tax_type == "1") {
        tax_rate = $("#tax_rate2").val();
    } else {
        tax_rate = $("#tax_rate").val();
    }

    var tax_price = 0;
    if(tax_rate > 0) {
        var tax_price = (subtotal*tax_rate)/100;
    }

    var total_price = tax_price + subtotal;

    $('[name="subtotal"]').val(parseFloat(subtotal).toFixed(2));
    $('[name="total_before_tax"]').val(parseFloat(total_before_tax).toFixed(2));
    $('[name="tax_price"]').val(parseFloat(tax_price).toFixed(2));
    $('[name="total_price"]').val(parseFloat(total_price).toFixed(2));
}
</script>

<?php $value_config = ','.get_config($dbc, 'vpl_orderforms_fields').','; ?>

<?php if($_GET['from_tile'] != 'estimates') { ?>
<div class="standard-body-title">
	<h3><?= $page_title ?></h3>
</div>

<div class="standard-body-content pad-10">
	<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <input type="hidden" name="vendorid" value="<?= $_GET['vendorid'] ?>">
        <input type="hidden" name="vpl_name" value="<?= $_GET['vpl_name'] ?>">
        <div class="form-group">
            <label class="col-sm-4">Vendor:</label>
            <div class="col-sm-8">
                <?= (!empty(get_client($dbc, $_GET['vendorid'])) ? get_client($dbc, $_GET['vendorid']) : get_contact($dbc, $_GET['vendorid'])) ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4">Vendor Price List:</label>
            <div class="col-sm-8">
                <?= $_GET['vpl_name'] ?>
            </div>
        </div>

        <?php if($_GET['from_tile'] != 'purchase_orders') { ?>
            <?php if(strpos($value_config, ',Project,') !== FALSE) { ?>
                <div class="form-group">
                    <label for="site_name" class="col-sm-4"><?= PROJECT_NOUN ?>:</label>
                    <div class="col-sm-8">
                        <select id="projectid" name="projectid" data-placeholder="Choose <?= PROJECT_NOUN ?>..." class="chosen-select-deselect form-control" width="380">
                            <option value=""></option><?php
                            $result = mysqli_query($dbc, "SELECT * FROM `project` WHERE `deleted`=0 ORDER BY `project_name`");
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option data-business='".$row['businessid']."' data-site='".$row['siteid']."' value='" . $row['projectid'] . "'>" . get_project_label($dbc,$row) . "</option>";
                            } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <?php if(strpos($value_config, ',Ticket,') !== FALSE) { ?>
                <div class="form-group">
                    <label for="site_name" class="col-sm-4"><?= TICKET_NOUN ?>:</label>
                    <div class="col-sm-8">
                        <select id="ticketid" name="ticketid" data-placeholder="Choose <?= TICKET_NOUN ?>..." class="chosen-select-deselect form-control" width="380">
                            <option value=""></option><?php
                            $result = mysqli_query($dbc, "SELECT * FROM `tickets` WHERE `deleted`=0 AND `status`!='Archive' ORDER BY `created_date` DESC");
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option data-business='".$row['businessid']."' data-site='".$row['siteid']."' data-project='".$row['projectid']."' value='" . $row['ticketid'] . "'>" .get_ticket_label($dbc,$row) . "</option>";
                            } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <?php if(strpos($value_config, ',Business,') !== FALSE) { ?>
                <div class="form-group">
                    <label for="site_name" class="col-sm-4"><?= BUSINESS_CAT ?>:</label>
                    <div class="col-sm-8">
                        <select name="businessid" data-placeholder="Choose <?= BUSINESS_CAT ?>..." class="chosen-select-deselect form-control" width="380">
                            <option value=""></option><?php
                            foreach(sort_contacts_query($dbc->query("SELECT `contactid`, `name`, `last_name`, `first_name` FROM `contacts` WHERE `category`='".BUSINESS_CAT."' AND `deleted`=0 AND `status` > 0")) as $row) {
                                echo "<option value='" . $row['contactid'] . "'>".$row['full_name']."</option>";
                            } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group">
                <label for="site_name" class="col-sm-4">Select Tax:</label>
                <div class="col-sm-8">
                    <div class="col-sm-6">
                        <input name="select_tax" value="0" type="radio" class="" checked="checked" style="float:left; margin-right:10px; width:auto;" />
                        <div>Standard Tax</div>
                    </div>
                    <div class="col-sm-6">
                        <input name="select_tax" value="1" type="radio" class="" style="float:left; margin-right:10px; width:auto;" />
                        <div>Enter Tax</div>
                    </div>
                </div>
            </div>

            <div id="default_tax">
                <?php $pos_tax_value    = get_config($dbc, 'purchase_order_tax');
                $pos_tax        = explode('*#*',$pos_tax_value);

                $total_count    = mb_substr_count($pos_tax_value,'*#*');
                $tax_rate       = 0;
                $tax_exe        = 0;

                for ($eq_loop=0; $eq_loop<=$total_count; $eq_loop++) {
                    $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]);
                    $tax_rate += $pos_tax_name_rate[1]; ?>

                    <div class="clearfix"></div>

                    <div class="form-group">
                        <label for="site_name" class="col-sm-4"><?php echo $pos_tax_name_rate[0];?> (%):<br><em>[<?php echo $pos_tax_name_rate[2];?>]</em></label>
                        <div class="col-sm-8">
                            <input name="pos_tax" value='<?php echo $pos_tax_name_rate[1];?>' type="text" class="form-control pos_tax" readonly />
                        </div>
                    </div><?php

                    if ($pos_tax_name_rate[3] == 'Yes') {
                        DEFINE('TAX_EXEMPTION', $pos_tax_name_rate[0]); ?>
                        <input id="not_count_pos_tax" value='<?php echo $pos_tax_name_rate[1];?>' type="hidden" />
                        <input id="not_count_pos_tax_number" value='<?php echo $pos_tax_name_rate[2];?>' type="hidden" /><?php
                        $tax_exe = 1;
                    }
                }

                if ($tax_exe == 0) { ?>
                    <input id="yes_tax_exemption" value='0' type="hidden" /><?php
                } else { ?>
                    <input id="yes_tax_exemption" value='1' type="hidden" /><?php
                }

                echo '<input type="hidden" name="tax_rate" id="tax_rate" value="'.$tax_rate.'" />'; ?>
            </div>
            <div id="enter_tax">
                <label for="pos_tax2" class="col-sm-4">Tax (%)</label>
                <div class="col-sm-8">
                    <input name="pos_tax2" type="text" class="form-control pos_tax" onkeyup="updateTax();"  /><br />
                    <input type="hidden" name="tax_rate2" id="tax_rate2" />
                </div>
            </div>
        <?php } else { ?>
            <input type="hidden" name="vplcount" value="<?= $_GET['vplcount'] ?>">
        <?php } ?>
<?php } ?>
        <div class="form-group">
            <label class="col-sm-4">Product Pricing:</label>
            <div class="col-sm-8">
                <select data-placeholder="Choose Pricing..." id="productpricing" name="productpricing" class="chosen-select-deselect form-control">
                    <option></option>
                    <?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
                        <option value="client_price">Client Price</option><?php
                    }
                    if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
                        <option value="admin_price">Admin Price</option><?php
                    }
                    if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
                        <option value="commercial_price">Commercial Price</option><?php
                    }
                    if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
                        <option value="wholesale_price">Wholesale Price</option><?php
                    }
                    if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
                        <option value="final_retail_price">Final Retail Price</option><?php
                    }
                    if (strpos($value_config, ','."Preferred Price".',') !== FALSE) { ?>
                        <option value="preferred_price">Preferred Price</option><?php
                    }
                    if (strpos($value_config, ','."Web Price".',') !== FALSE) { ?>
                        <option value="web_price">Web Price</option><?php
                    }
                    if (strpos($value_config, ','."Purchase Order Price".',') !== FALSE) { ?>
                        <option value="purchase_order_price">Purchase Order Price</option><?php
                    }
                    if (strpos($value_config, ','."Sales Order Price".',') !== FALSE) { ?>
                        <option value="sales_order_price"><?= SALES_ORDER_NOUN ?> Price</option><?php
                    }
                    if (strpos($value_config, ','."Drum Unit Cost".',') !== FALSE) { ?>
                        <option value="drum_unit_cost">Drum Unit Cost</option><?php
                    }
                    if (strpos($value_config, ','."Drum Unit Price".',') !== FALSE) { ?>
                        <option value="drum_unit_price">Drum Unit Price</option><?php
                    }
                    if (strpos($value_config, ','."Tote Unit Cost".',') !== FALSE) { ?>
                        <option value="tote_unit_cost">Tote Unit Cost</option><?php
                    }
                    if (strpos($value_config, ','."Tote Unit Price".',') !== FALSE) { ?>
                        <option value="tote_unit_price">Tote Unit Price</option><?php
                    }
                    if (strpos($value_config, ','."Average Cost".',') !== FALSE) { ?>
                        <option value="average_cost">Average Cost</option><?php
                    }
                    if (strpos($value_config, ','."USD Cost Per Unit".',') !== FALSE) { ?>
                        <option value="usd_cpu">USD Cost Per Unit</option><?php
                    } ?>
                </select>
            </div>
        </div>

        <h4>Vendor Price List Items</h4>
        <div id="no-more-tables">
            <div class="panel-group" id="accordion2">
                <?php $vpl_cats = mysqli_query($dbc, "SELECT DISTINCT `category` FROM `vendor_price_list` WHERE `deleted` = 0 AND `vendorid` = '".$_GET['vendorid']."' AND `vpl_name` = '".$_GET['vpl_name']."' ORDER BY `category`, `sub_category`");
                while($vpl_cat = mysqli_fetch_assoc($vpl_cats)) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading no_load">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?= config_safe_str($vpl_cat['category']) ?>">
                                    <?= empty($vpl_cat['category']) ? 'No Category' : $vpl_cat['category'] ?><span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </h4>
                        </div>

                        <div id="collapse_<?= config_safe_str($vpl_cat['category']) ?>" class="panel-collapse collapse">
                            <div class="panel-body">

                                <table class="table table-bordered">
                                    <tr class="hidden-xs">
                                        <?php if(strpos($value_config, ',Category,') !== FALSE) { ?>
                                            <th>Category</th>
                                        <?php } ?>
                                        <?php if(strpos($value_config, ',Part #,') !== FALSE) { ?>
                                            <th>Part #</th>
                                        <?php } ?>
                                        <?php if(strpos($value_config, ',Name,') !== FALSE) { ?>
                                            <th>Name</th>
                                        <?php } ?>
                                        <?php if(strpos($value_config, ',Price,') !== FALSE) { ?>
                                            <th>Price</th>
                                        <?php } ?>
                                        <?php if(strpos($value_config, ',Quantity,') !== FALSE) { ?>
                                            <th>Quantity</th>
                                        <?php } ?>
                                    </tr>
                                    <?php $vpl_list = mysqli_query($dbc, "SELECT * FROM `vendor_price_list` WHERE `deleted` = 0 AND `vendorid` = '".$_GET['vendorid']."' AND `vpl_name` = '".$_GET['vpl_name']."' AND `category` = '".$vpl_cat['category']."'");
                                    while($row = mysqli_fetch_assoc($vpl_list)) { ?>
                                        <tr class="order_item">
                                            <input type="hidden" name="inventoryid[]" value="<?= $row['inventoryid'] ?>">
                                            <?php if(strpos($value_config, ',Category,') !== FALSE) { ?>
                                                <td data-title="Category"><?= $row['category'] ?></td>
                                            <?php } ?>
                                            <?php if(strpos($value_config, ',Part #,') !== FALSE) { ?>
                                                <td data-title="Part #"><?= $row['part_no'] ?></td>
                                            <?php } ?>
                                            <?php if(strpos($value_config, ',Name,') !== FALSE) { ?>
                                                <td data-title="Name"><?= $row['name'] ?></td>
                                            <?php } ?>
                                            <?php if(strpos($value_config, ',Price,') !== FALSE) { ?>
                                                <td data-title="Price"><input type="text" name="vpl_price[]" value="" class="form-control" readonly></td>
                                            <?php } ?>
                                            <?php if(strpos($value_config, ',Quantity,') !== FALSE) { ?>
                                                <td data-title="Quantity"><input type="number" name="vpl_quantity[]" value="" min="0" class="form-control"></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

<?php if($_GET['from_tile'] != 'estimates') { ?>
        <?php if($_GET['from_tile'] != 'purchase_orders') { ?>
            <div class="form-group">
                <label class="col-sm-4">Subtotal:</label>
                <div class="col-sm-8">
                    <input type="text" name="subtotal" value="" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Total Before Tax:</label>
                <div class="col-sm-8">
                    <input type="text" name="total_before_tax" value="" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Tax Price:</label>
                <div class="col-sm-8">
                    <input type="text" name="tax_price" value="" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Total Price:</label>
                <div class="col-sm-8">
                    <input type="text" name="total_price" value="" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group pull-right">
                <button type="submit" name="submit_orderform" value="submit_orderform" class="btn brand-btn">Submit</button>
            </div>
        <?php } else { ?>
            <div class="form-group pull-right">
                <button type="submit" name="submit_orderform_po" value="submit_orderform_po" class="btn brand-btn">Submit</button>
            </div>
        <?php } ?>
    </form>
    <div class="clearfix"></div>
</div>
<?php } ?>