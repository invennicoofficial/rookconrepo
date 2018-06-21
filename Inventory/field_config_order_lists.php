<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('inventory');
error_reporting(0);

if(isset($_GET['deleteid'])) {
	$deleteid = $_GET['deleteid'];
	$query_update_vendor = "UPDATE `order_lists` SET `deleted` = '1' WHERE `order_id` = '$deleteid'";
    $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
	 echo '<script type="text/javascript"> window.location.replace("field_config_order_lists.php"); </script>';
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
    } elseif ($po_pricing_select == 'Preferred Price') {
        $custom_pricing = 'preferred_price';
	} elseif ($po_pricing_select == 'Custom') {
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
    echo '<script type="text/javascript"> window.location.replace("field_config_order_lists.php"); </script>';
}
?>
<script type="text/javascript">
   $(document).ready(function () {

        $(".create_list").click(function() {
           $('.create_list_form').slideToggle();
		   $('.list_dashboard').toggle();

		if($(this).text() == '<-- Back') {
	       $(this).text('Create New List');
		} else {
		   $(this).text('<-- Back');
		}
        });

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
		var po_price = $(this).val();
        if ( po_price=='Purchase Order Price (default)' || po_price=='Preferred Price' ) {
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
</head>
<body>

<?php include ('../navigation.php');

		$include_in_po = '';
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
		}
?>

<div class="container">
<div class="row">
<h1>Inventory Settings</h1>
<?php if(get_config($dbc, 'inventory_default_select_all') == 1) { ?>
<div class="pad-left gap-top double-gap-bottom"><a href="inventory.php?category=dispall_31V2irt2u3e5S3s1f2ADe3_31" class="btn config-btn">Back to Dashboard</a></div>
<?php } else { ?>
<div class="pad-left gap-top double-gap-bottom"><a href="inventory.php?category=Top" class="btn config-btn">Back to Dashboard</a></div>
<?php } ?>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->

<form id="form1" name="form1" method="post"	action="field_config_order_lists.php" enctype="multipart/form-data" class="form-horizontal" role="form">
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
	echo "<a href='field_config_order_lists.php'><button type='button' class='btn brand-btn mobile-block active_tab' >Order Lists</button></a>&nbsp;&nbsp;";
	echo "<a href='field_config_inventory.php?type=digi_count'><button type='button' class='btn brand-btn mobile-block' >Digital Inventory Count</button></a>&nbsp;&nbsp;";
	echo "<a href='field_config_inventory.php?type=impexp'><button type='button' class='btn brand-btn mobile-block' >Import/Export</button></a>";

    echo '<br><br><Br>';
    ?>

    <h3>Order Lists</h3>
	<?php if(isset($_GET['order_id'])) { ?>
		<a href='field_config_order_lists.php'><span class='btn brand-btn mobile-block ' ><-- Back</span></a>
	<?php } else { ?>
		<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to create a new Order List."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
		<span class='btn brand-btn mobile-block create_list' >Create New List</span>
	<?php } ?>

	<div class='create_list_form' style='<?php if(isset($_GET['order_id'])) { } else { ?>display:none;<?php } ?> border: 10px outset darkgrey; background-color:lightgrey;border-radius:10px; padding: 10px; margin:15px;'>
	<form id="" name="x" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
		<input type='hidden' name='order_id' value='<?php if(isset($_GET['order_id'])) {
			echo $_GET['order_id'];
		} ?>'>
		<div class="form-group" id="new_product" style="">
			<label for="travel_task" class="col-sm-4 control-label">List Name:
			</label>
			<div class="col-sm-8">
				<input name="list_name" value="<?php echo $list_name; ?>" required type="text" class="form-control" />
			</div>
        </div>
		<div class="form-group" id="" style="">
			<label for="travel_task" class="col-sm-4 control-label">Include in Purchase Orders:
			</label>
			<div class="col-sm-8">
				<input style='width:20px;height:20px;' <?php if($include_in_po == '1') { echo 'checked'; } ?> name="includeinpo" value="1" type="checkbox" class="po_price_includer" />
			</div>
        </div>
		<div class="form-group" id="" style="">
			<label for="travel_task" class="col-sm-4 control-label">Include in <?= SALES_ORDER_TILE ?>:
			</label>
			<div class="col-sm-8">
				<input  style='width:20px;height:20px;' <?php if($include_in_so == '1') { echo 'checked'; } ?> name="includeinso" value="1" type="checkbox" class="" />
			</div>
        </div>
		<div class="form-group" id="" style="">
			<label for="travel_task" class="col-sm-4 control-label">Include in Point of Sale:
			</label>
			<div class="col-sm-8">
				<input style='width:20px;height:20px;' <?php if($include_in_pos == '1') { echo 'checked'; } ?> name="includeinpos" value="1" type="checkbox" class="" />
			</div>
        </div>
		<div class="po_price_info_show_hide" <?php if($include_in_po !== '1') { ?>style="display:none;"<?php } ?>>
			<div class="form-group po_pricer" id="" style="">
				<label for="travel_task" class="col-sm-4 control-label">Purchase Order Pricing:
				</label>
				<div class="col-sm-8">
					<select name="po_pricing_select"  class="chosen-select-deselect form-control po_pricing_select" width="380">
					  <option <?php if($custom_pricing == 'po_price' || $custom_pricing == '' || $custom_pricing == NULL) { echo 'selected'; } ?> value="Purchase Order Price (default)">Purchase Order Price (default)</option>
                      <option <?php if($custom_pricing == 'preferred_price') { echo 'selected'; } ?> value="Preferred Price">Preferred Price</option>
					  <option <?php if($custom_pricing !== 'po_price' && $custom_pricing !== '' && $custom_pricing !== NULL && $custom_pricing !== 'preferred_price') { echo 'selected'; } ?> value="Custom">Customized Pricing</option>
					</select>
				</div>
			</div>

			<div class="form-group po_pricer_custom_pricing" <?php echo ($custom_pricing == 'po_price' || $custom_pricing == '' || $custom_pricing == NULL || $custom_pricing == 'preferred_price') ? 'style="display:none;"' : ''; ?>>
				<label for="travel_task" class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Custom pricing is the CDN Cost Per Unit multipled by 100% + the percentage chose below."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Custom Purchase Order Pricing (%):
				</label>
				<div class="col-sm-8">
					<input style='width:20px;height:20px;' <?php if($custom_pricing_10 == '10') { echo 'checked'; } ?> name="custom_pricing_10" value="1" type="checkbox" class="uncheck_me" />
					<input disabled name="" value="<?php echo "10" ?>" type="text" class="form-control" style="display: inline-block; width: calc(100% - 25px);" />
					<input style='width:20px;height:20px;' <?php if($custom_pricing_20 == '20') { echo 'checked'; } ?> name="custom_pricing_20" value="1" type="checkbox" class="uncheck_me" />
					<input disabled name="" value="<?php echo "20"; ?>" type="text" class="form-control " style="display: inline-block; width: calc(100% - 25px);" />
					<input style='width:20px;height:20px;' <?php if($custom_pricing_any !== '20' && $custom_pricing_any !== '10' && $custom_pricing_any !== '' && $custom_pricing_any !== NULL) { echo 'checked'; } ?> name="custom_pricing_any" value="1" type="checkbox" class="uncheck_me" />
					<input name="custom_pricer" value="<?php echo $custom_pricing_any; ?>" type="text" class="form-control" style="display: inline-block; width: calc(100% - 25px);" />
				</div>
			</div>

			<!-- Cross Software Includer: Allows this Order List to show up on the Purchase Orders of other software, if attached. -->
			<?php if(isset($number_of_connections) && $number_of_connections > 0) { ?>
				<div class="form-group">
					<label for="travel_task" class="col-sm-4 control-label"><span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Choose which software you would like this Order List to show up on when making a Purchase Order."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span> Software Access:
					</label>
					<div class="col-sm-8">
					<?php $num_of_rows = 0;
						$pending_rows = 0;
						// **** NOTE: THE $number_of_connections variable is set only in the database_connection.php file. You must put this variable in manually for this to work. Please see SEA's database_connection.php file (sea.freshfocussoftware.com) in order to see how these variables are set up. If you are trying to copy this cross-software functionality, it is advised that you use the exact same format/variable names that SEA's database_connection.php file contains.
						foreach (range(1, $number_of_connections) as $i) {
							$name_of_the_software = ${'name_of_the_software_'.$i};
							$cslist = explode(',',$cross_software_list);
							if (in_array($i, $cslist)) {
							  $checker = "checked";
							} else {
							  $checker = "";
							}
							?><input style='width:20px;height:20px;' <?php echo $checker ?> name="cross_software_lister[]" value="<?php echo $i; ?>" type="checkbox" class="" /> <?php echo $name_of_the_software.'<br>';
						} ?>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="form-group" id="" style="">

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
		</form>
	</div>

			<br>

            <div id="no-more-tables" class='list_dashboard'  <?php if(isset($_GET['order_id'])) { ?>style='display:none;'<?php } ?>>

            <?php
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

            $result = mysqli_query($dbc, $query_check_credentials);
            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {

                echo "<table class='table table-bordered '>";
                echo "<tr class='hidden-xs hidden-sm'>";
                        echo '<th>List Name</th>';
                        echo '<th>Include in '.SALES_ORDER_TILE.'</th>';
                        echo '<th>Include in Point of Sale</th>';
                        echo '<th>Include in Purchase Orders</th>';
						echo '<th>Inventory</th>';
						echo '<th>Function</th>';
                    echo "</tr>";
            } else {
                echo "<h2 class ='list_dashboard'>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                    echo '<td data-title="List Name">' . $row['order_title'] . '</td>';

                        echo '<td data-title="Include in '.SALES_ORDER_TILE.'">';
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_so'] !== '' && $row['include_in_so'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['order_id']; ?>'  name='' class='sales_order_includer' value='1'><br>
						<?php
						echo '</td>';
                        echo '<td data-title="Include in P.O.S.">';
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_pos'] !== '' && $row['include_in_pos'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['order_id']; ?>'  name='' class='point_of_sale_includer' value='1'><br>
						<?php
						echo '</td>';
                        echo '<td data-title="Include in Purchase Orders">';
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_po'] !== '' && $row['include_in_po'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['order_id']; ?>'  name='' class='purchase_order_includer' value='1'><br>
						<?php
						echo '</td>';
						echo '<td data-title="Inventory">';
						?><a href='inventory.php?order_list=<?php echo $row['order_id']; ?>&category=Top'  style='width:100%;text-align:center;display: block;' target='_blank'>Add Inventory to List</a><hr style='margin:3px;padding:0; box-shadow: 1px 1px 1px #0e0e0e;'>
						<?php
						$inv = $row['inventoryid'];
						if($inv == '' || $inv == NULL) {
						$num_of_inv = '0';
						} else {
						$inv = explode(",", $inv);
						$num_of_inv = count($inv);
						}
						?>
						<a href='inventory.php?order_list=<?php echo $row['order_id']; ?>&currentlist&category=Top' style='width:100%;text-align:center;display: block;'  target='_blank'>View Current List of Inventory (<?php echo $num_of_inv; ?>)</a>
						<?php
						echo '</td>';
                echo '<td data-title="Function">';

                echo '<a href=\'field_config_order_lists.php?order_id='.$row['order_id'].'\'>Edit</a> | ';
				echo '<a href=\'field_config_order_lists.php?deleteid='.$row['order_id'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';

                echo '</td>';

                echo "</tr>";
            }

            echo '</table></div>';

			?>

    <br>

			<div class="form-group">
				<div class="col-sm-6">
					<?php if ( isset ( $_GET['order_id'] ) ) { ?>
						<a href="field_config_order_lists.php" class="btn config-btn btn-lg">Back</a>
					<?php } else { ?>
						<a href="inventory.php?category=Top" class="btn config-btn btn-lg">Back</a>
					<?php } ?>
				</div>
				<div class="col-sm-6"></div>
				<div class="clearfix"></div>
			</div>


</form>
</div>
</div>

<?php include ('../footer.php'); ?>