<?php include_once('../include.php');
$strict_view = strictview_visible_function($dbc, 'inventory');
$tile_security = get_security($dbc, 'inventory');
if($strict_view > 0) {
    $tile_security['edit'] = 0;
    $tile_security['config'] = 0;
}
if(isset($_GET['deleteid'])) {
	$deleteid = $_GET['deleteid'];
	$query_update_vendor = "UPDATE `order_lists` SET `deleted` = '1' WHERE `order_id` = '$deleteid'";
    $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
	 echo '<script type="text/javascript"> window.location.replace("order_lists.php"); </script>';
}

if (isset($_POST['add_list'])) {

    $title = filter_var(htmlentities($_POST['list_name']),FILTER_SANITIZE_STRING);
	$include_in_pos = filter_var($_POST['includeinpos'],FILTER_SANITIZE_STRING);
	$include_in_po = filter_var($_POST['includeinpo'],FILTER_SANITIZE_STRING);
	$include_in_so = filter_var($_POST['includeinso'],FILTER_SANITIZE_STRING);
	$po_pricing_select = filter_var($_POST['po_pricing_select'],FILTER_SANITIZE_STRING);
	$custom_pricing = NULL;
	if($po_pricing_select == 'Purchase Order Price (default)') {
		$custom_pricing = NULL;
	} else if ($po_pricing_select == 'Custom') {
		if($_POST['custom_pricing_10'] == '1') {
			$custom_pricing = '10';
		} else if($_POST['custom_pricing_20'] == '1') {
			$custom_pricing = '20';
		} else if($_POST['custom_pricing_any'] == '1') {
			if($_POST['custom_pricer'] !== '' && $_POST['custom_pricer'] !== NULL && is_numeric($_POST['custom_pricer'])) {
				$custom_pricing = $_POST['custom_pricer'];
			}
		}
	}
	if(isset($_POST['cross_software_lister'])) {
		$cross_software = implode(',', $_POST['cross_software_lister']);
	}
	$order_id = $_POST['order_id'];
    if(empty($_POST['order_id'])) {
        $query_insert_vendor = "INSERT INTO `order_lists` (`order_title`, `include_in_pos`, `tile`, `include_in_po`, `include_in_so`, `custom_pricing`, `cross_software`) VALUES ('$title', '$include_in_pos', 'Inventory', '$include_in_po', '$include_in_so', '$custom_pricing', '$cross_software')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor) or die(mysqli_error($dbc));
    } else {
        $productid = $_POST['productid'];
        $query_update_vendor = "UPDATE `order_lists` SET `order_title` = '$title', `cross_software` = '$cross_software', `custom_pricing` = '$custom_pricing', `include_in_pos` = '$include_in_pos',`include_in_so` = '$include_in_so', `include_in_po` = '$include_in_po' WHERE `order_id` = '$order_id'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
    }
    echo '<script type="text/javascript"> window.location.replace("order_lists.php"); </script>';
} ?>
<script type="text/javascript">
   $(document).ready(function () {

	$('input.po_price_includer').on('change', function() {
		if($(this).prop('checked') == true){
			$('.po_price_info_show_hide').show(500);
		} else {
			$('.po_price_info_show_hide').hide(500);
		}
	});

	$('input.purchase_order_includer').on('change', function() {
		var id = $(this).attr('id');
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = ''; }
		$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../ajax_all.php?fill=include_in_orders&type=po&name=orderlist&value="+value+"&status="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
		});
	});

	$('input.sales_order_includer').on('change', function() {
		var id = $(this).attr('id');
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = ''; }
		$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../ajax_all.php?fill=include_in_orders&type=so&name=orderlist&value="+value+"&status="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
		});
	});

	$('input.point_of_sale_includer').on('change', function() {
		var id = $(this).attr('id');
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = ''; }
		$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../ajax_all.php?fill=include_in_orders&type=pos&name=orderlist&value="+value+"&status="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
		}
		});
	});

	$(".po_pricing_select").change(function(){
		if($(this).val() == 'Purchase Order Price (default)') {
			$('.po_pricer_custom_pricing').hide(500);
		} else {
			$('.po_pricer_custom_pricing').show(500);
		}
	});

$('input.uncheck_me').on('change', function() {
    $('input.uncheck_me').not(this).prop('checked', false);
});

});

</script>
<?php $include_in_po = '';
$list_name = '';
$include_in_so = '';
$include_in_pos = '';
$custom_pricing = '';
$custom_pricing_10 = '';
$custom_pricing_20 = '';
$custom_pricing_any = '';
$cross_software_list = '';
if(!empty($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM order_lists WHERE order_id='$order_id'"));
	$include_in_po = $get_contact['include_in_po'];
	$include_in_so = $get_contact['include_in_so'];
	$include_in_pos = $get_contact['include_in_pos'];
    $list_name = $get_contact['order_title'];
	$custom_pricing = $get_contact['custom_pricing'];
	if($custom_pricing == '10') {
		$custom_pricing_10 = $get_contact['custom_pricing'];
	} else if ($custom_pricing == '20') {
		$custom_pricing_20 = $get_contact['custom_pricing'];
	} else if($custom_pricing !== '20' && $custom_pricing !== '10' && $custom_pricing !== '' && $custom_pricing !== NULL) {
		$custom_pricing_any = $get_contact['custom_pricing'];
	}
	$cross_software_list = $get_contact['cross_software'];
} ?>
<form id="form1" name="form1" method="post"	action="order_lists.php" enctype="multipart/form-data" class="form-horizontal double-gap-top" role="form">
    <?php if ( isset ( $_GET['order_id'] ) ) { ?>
        <a href="order_lists.php"><span class="btn brand-btn mobile-block"><-- Back</span></a>
    <?php } ?>
    
    <div class="create_list_form" style="<?php echo ( isset($_GET['order_id']) ) ? '' : 'display:none;'; ?> border:10px outset darkgrey; background-color:lightgrey; border-radius:10px; padding:10px; margin:15px;">
        <form id="" name="x" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <input type="hidden" name="order_id" value="<?php echo ( isset($_GET['order_id']) ) ? $_GET['order_id'] : ''; ?>" />
            <div class="form-group" id="new_product">
                <label for="travel_task" class="col-sm-4 control-label">List Name:</label>
                <div class="col-sm-8 <?= !($tile_security['edit'] > 0) ? 'readonly-block' : '' ?>"><input name="list_name" value="<?php echo $list_name; ?>" required type="text" class="form-control" /></div>
            </div>
            <div class="form-group">
                <label for="travel_task" class="col-sm-4 control-label">Include in Purchase Orders:</label>
                <div class="col-sm-8 <?= !($tile_security['edit'] > 0) ? 'readonly-block' : '' ?>"><input <?php if($include_in_po == '1') { echo 'checked'; } ?> name="includeinpo" value="1" type="checkbox" class="po_price_includer" /></div>
            </div>
            <div class="form-group">
                <label for="travel_task" class="col-sm-4 control-label">Include in <?= SALES_ORDER_TILE ?>:</label>
                <div class="col-sm-8 <?= !($tile_security['edit'] > 0) ? 'readonly-block' : '' ?>"><input <?php if($include_in_so == '1') { echo 'checked'; } ?> name="includeinso" value="1" type="checkbox" class="" /></div>
            </div>
            <div class="form-group">
                <label for="travel_task" class="col-sm-4 control-label">Include in Point of Sale:</label>
                <div class="col-sm-8 <?= !($tile_security['edit'] > 0) ? 'readonly-block' : '' ?>"><input <?php if($include_in_pos == '1') { echo 'checked'; } ?> name="includeinpos" value="1" type="checkbox" class="" /></div>
            </div>
            <div class="po_price_info_show_hide" <?php if($include_in_po !== '1') { ?>style="display:none;"<?php } ?>>
                <div class="form-group po_pricer" id="" style="">
                    <label for="travel_task" class="col-sm-4 control-label">Purchase Order Pricing:</label>
                    <div class="col-sm-8 <?= !($tile_security['edit'] > 0) ? 'readonly-block' : '' ?>">
                        <select name="po_pricing_select"  class="chosen-select-deselect form-control po_pricing_select" width="380">
                            <option <?php if($custom_pricing == 'po_price' || $custom_pricing == '' || $custom_pricing == NULL) { echo 'selected'; } ?> value="Purchase Order Price (default)">Purchase Order Price (default)</option>
                            <option <?php if($custom_pricing !== 'po_price' && $custom_pricing !== '' && $custom_pricing !== NULL) { echo 'selected'; } ?> value="Custom">Customized Pricing</option>
                        </select>
                    </div>
                </div>

                <div class="form-group po_pricer_custom_pricing" <?php if($custom_pricing == 'po_price' || $custom_pricing == '' || $custom_pricing == NULL) { ?> style="display:none;" <?php } ?>>
                    <label for="travel_task" class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Custom pricing is the CDN Cost Per Unit multipled by 100% + the percentage chose below."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Custom Purchase Order Pricing (%):</label>
                    <div class="col-sm-8 <?= !($tile_security['edit'] > 0) ? 'readonly-block' : '' ?>">
                        <input <?php if($custom_pricing_10 == '10') { echo 'checked'; } ?> name="custom_pricing_10" value="1" type="checkbox" class="uncheck_me" />
                        <input disabled name="" value="<?php echo "10" ?>" type="text" class="form-control" style="display: inline-block; width: calc(100% - 25px);" />
                        <input <?php if($custom_pricing_20 == '20') { echo 'checked'; } ?> name="custom_pricing_20" value="1" type="checkbox" class="uncheck_me" />
                        <input disabled name="" value="<?php echo "20"; ?>" type="text" class="form-control " style="display: inline-block; width: calc(100% - 25px);" />
                        <input <?php if($custom_pricing_any !== '20' && $custom_pricing_any !== '10' && $custom_pricing_any !== '' && $custom_pricing_any !== NULL) { echo 'checked'; } ?> name="custom_pricing_any" value="1" type="checkbox" class="uncheck_me" />
                        <input name="custom_pricer" value="<?php echo $custom_pricing_any; ?>" type="text" class="form-control" style="display:inline-block; width:calc(100% - 25px);" />
                    </div>
                </div>

                <!-- Cross Software Includer: Allows this Order List to show up on the Purchase Orders of other software, if attached. -->
                <?php if ( isset($number_of_connections) && $number_of_connections > 0 ) { ?>
                    <div class="form-group">
                        <label for="travel_task" class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Choose which software you would like this Order List to show up on when making a Purchase Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Software Access:</label>
                        <div class="col-sm-8 <?= !($tile_security['edit'] > 0) ? 'readonly-block' : '' ?>"><?php
                            $num_of_rows    = 0;
                            $pending_rows   = 0;
                            // **** NOTE: THE $number_of_connections variable is set only in the database_connection.php file. You must put this variable in manually for this to work. Please see SEA's database_connection.php file (sea.freshfocussoftware.com) in order to see how these variables are set up. If you are trying to copy this cross-software functionality, it is advised that you use the exact same format/variable names that SEA's database_connection.php file contains.
                            foreach ( range(1, $number_of_connections) as $i ) {
                                $name_of_the_software = ${'name_of_the_software_'.$i};
                                $cslist = explode(',',$cross_software_list);
                                if (in_array($i, $cslist)) {
                                    $checker = "checked";
                                } else {
                                    $checker = "";
                                } ?>
                                <input <?php echo $checker ?> name="cross_software_lister[]" value="<?php echo $i; ?>" type="checkbox" class="" /> <?php echo $name_of_the_software.'<br>';
                            } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            
            <?php if($tile_security['edit'] > 0) { ?>
                <div class="form-group">
                    <div class="col-sm-8 pull-right">
                        <?php if(isset($_GET['order_id'])) { ?>
                            <button type="submit" name="add_list" value="Submit" class="btn brand-btn btn-lg pull-right">Update List</button>
                            <span class="popover-examples list-inline pull-right" style="margin:12px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to update this Order List."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <?php } else { ?>
                            <button type="submit" name="add_list" value="Submit" class="btn brand-btn btn-lg pull-right">Create List</button>
                            <span class="popover-examples list-inline pull-right" style="margin:12px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to submit this Order List."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </form>
    </div>
    
    <div id="no-more-tables" class="list_dashboard" <?php echo ( isset ($_GET['order_id']) ) ? 'style="display:none;"' : ''; ?>><?php
        //Search -- NOT YET DEVELOPED
        $vendor = '';
        if (isset($_POST['search_vendor_submit'])) {
            if (isset($_POST['search_vendor'])) {
                $vendor = $_POST['search_vendor'];
            }
        }
        if (isset($_POST['display_all_vendor'])) {
            $vendor = '';
        }

        if($vendor != '') {
            $query_check_credentials = "SELECT * FROM order_lists WHERE deleted = 0 AND tile = 'Inventory' AND (order_title LIKE '%" . $vendor . "%')";
        } else {
            $query_check_credentials = "SELECT * FROM order_lists WHERE deleted = 0 AND tile = 'Inventory' ORDER BY order_title";
        }

        $result     = mysqli_query($dbc, $query_check_credentials);
        $num_rows   = mysqli_num_rows($result);
        
        if ( $num_rows > 0 ) {
            echo '<table class="table table-bordered">';
            echo '<tr class="hidden-xs hidden-sm">';
                    echo '<th>List Name</th>';
                    echo '<th>Include in '.SALES_ORDER_TILE.'</th>';
                    echo '<th>Include in Point of Sale</th>';
                    echo '<th>Include in Purchase Orders</th>';
                    echo '<th>Inventory</th>';
                    if($tile_security['edit'] > 0) {
                        echo '<th>Function</th>';
                    }
                echo '</tr>';
        } else {
            echo '<h2 class="list_dashboard">No Record Found.</h2>';
        }

        while($row = mysqli_fetch_array( $result )) {
            echo '<tr>';
                echo '<td data-title="List Name" style="vertical-align:middle">' . $row['order_title'] . '</td>';
                echo '<td data-title="Include in '.SALES_ORDER_TILE.'" align="center" style="vertical-align:middle">'; ?>
                    <input type="checkbox" <?php if ( $row['include_in_so'] !== '' && $row['include_in_so'] !== NULL ) { echo "checked"; } ?> id="<?php echo $row['order_id']; ?>"  name="" class="sales_order_includer" value="1" <?= !($tile_security['edit'] > 0) ? 'readonly disabled' : '' ?> /><?php
                echo '</td>';
                echo '<td data-title="Include in P.O.S." align="center" style="vertical-align:middle">'; ?>
                    <input type="checkbox" <?php if ( $row['include_in_pos'] !== '' && $row['include_in_pos'] !== NULL ) { echo "checked"; } ?> id="<?php echo $row['order_id']; ?>" name="" class="point_of_sale_includer" value="1" <?= !($tile_security['edit'] > 0) ? 'readonly disabled' : '' ?> /><?php
                echo '</td>';
                echo '<td data-title="Include in Purchase Orders" align="center" style="vertical-align:middle">'; ?>
                    <input type="checkbox" <?php if ( $row['include_in_po'] !== '' && $row['include_in_po'] !== NULL ) { echo "checked"; } ?> id="<?php echo $row['order_id']; ?>" name="" class="purchase_order_includer" value="1" <?= !($tile_security['edit'] > 0) ? 'readonly disabled' : '' ?> /><?php
                echo '</td>';
                echo '<td data-title="Inventory">'; ?>
                    <?php if($tile_security['edit'] > 0) { ?>
                        <a href="inventory.php?order_list=<?php echo $row['order_id']; ?>&category=Top" style="width:100%; text-align:center; display:block;" target="_blank">Add Inventory to List</a>
                        <hr style="margin:3px; padding:0; box-shadow:1px 1px 1px #0e0e0e;" />
                    <?php }
                    $inv = $row['inventoryid'];
                    if ( $inv == '' || $inv == NULL ) {
                        $num_of_inv = '0';
                    } else {
                        $inv = explode(",", $inv);
                        $num_of_inv = count($inv);
                    } ?>
                    <a href="inventory.php?order_list=<?php echo $row['order_id']; ?>&currentlist&category=Top" style="width:100%; text-align:center; display:block;" target="_blank">View Current List of Inventory (<?php echo $num_of_inv; ?>)</a><?php
                echo '</td>';
                if($tile_security['edit'] > 0) {
                    echo '<td data-title="Function" style="vertical-align:middle">';
                        echo '<a href=\'order_lists.php?order_id='.$row['order_id'].'\'>Edit</a> | ';
                        echo '<a href=\'order_lists.php?deleteid='.$row['order_id'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
                    echo '</td>';
                }
            echo "</tr>";
        }
        
        echo '</table></div>'; ?>
    </div>
</form>