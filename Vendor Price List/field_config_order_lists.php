<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('vpl');
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
	$contactid = $_POST['contactid'];
	$order_id = $_POST['order_id'];
    if(empty($_POST['order_id'])) {
        $query_insert_vendor = "INSERT INTO `order_lists` (`order_title`, `tile`, `include_in_pos`, `include_in_po`, `include_in_so`, `contactid`) VALUES ('$title', 'VPL', '$include_in_pos', '$include_in_po', '$include_in_so', '$contactid')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
    } else {
        $productid = $_POST['productid'];
        $query_update_vendor = "UPDATE `order_lists` SET `order_title` = '$title', `include_in_pos` = '$include_in_pos',`include_in_so` = '$include_in_so', `include_in_po` = '$include_in_po', `contactid` = '$contactid' WHERE `order_id` = '$order_id'";
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

		if($(this).text() == 'Back to Dashboard') {
	       $(this).text('Create New List');
		} else {
		   $(this).text('Back to Dashboard');
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
});
$(document).on('change', 'select[name="contactid"]', function() { changeClient(this); });

function changeInv(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	if(status == 'Completed' || status == 'Approved' || status == 'Receiving') {
		$(sel).next().click();
	} else if(status == 'Archived') {
		 if (confirm('Are you sure?')) {

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "pos_ajax_all.php?fill=POSstatus&name="+arr[1]+'&status='+status,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
		}
	} else if(status == 'Pending') {
			$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "pos_ajax_all.php?fill=POSstatus&name="+arr[1]+'&status='+status,
				dataType: "html",   //expect html to be returned
				success: function(response){
					location.reload();
			}
		});
	}
}
</script>
</head>
<body>

<?php include ('../navigation.php');

		$include_in_po = '';
        $list_name = '';
		$include_in_so = '';
		$include_in_pos = '';
		$vendorid_attached = '';

		if(!empty($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM order_lists WHERE order_id='$order_id'"));
			$include_in_po = $get_contact['include_in_po'];
			$include_in_so = $get_contact['include_in_so'];
			$include_in_pos = $get_contact['include_in_pos'];
            $list_name = $get_contact['order_title'];
			$vendorid_attached = $get_contact['contactid'];
		}
?>

<div class="container">
<div class="row">
<h1>Vendor Price List</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="inventory.php?category=All" class="btn config-btn">Back to Dashboard</a></div>
<!--<a href="#" class="btn config-btn" onclick="history.go(-1);return false;">Back</a>-->
<form id="form1" name="form1" method="post"	action="field_config_order_lists.php" enctype="multipart/form-data" class="form-horizontal" role="form">
    <?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT receive_shipment FROM field_config_inventory WHERE tab='receive_shipment' AND accordion='receive_shipment'"));
    $inventory_dashboard_config = ','.$get_field_config['receive_shipment'].',';

    $active_tab = '';
    $active_field = '';
    $active_dashboard = '';
    $active_general = '';
    $active_rs = '';

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
    if($_GET['type'] == 'rs') {
        $active_rs = 'active_tab';
    }
	?>

    <div class="tab-container mobile-100-container">
		<div class="pull-left tab">
			<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click to add your own Tabs."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="field_config_inventory.php?type=tab"><button type="button" class="btn brand-btn mobile-block <?= $active_tab; ?>">Tabs</button></a>
		</div>
		<div class="pull-left tab">
			<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click to add desired fields."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="field_config_inventory.php?type=field"><button type="button" class="btn brand-btn mobile-block <?= $active_field; ?>">Fields</button></a>
		</div>
		<div class="pull-left tab">
			<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click to add desired fields you would like to appear on you VPL dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="field_config_inventory.php?type=dashboard"><button type="button" class="btn brand-btn mobile-block <?= $active_dashboard; ?>">Dashboard</button></a>
		</div>
		<div class="pull-left tab">
			<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="More options here."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="field_config_inventory.php?type=general"><button type="button" class="btn brand-btn mobile-block <?= $active_general; ?>">General</button></a>
		</div>
		<div class="pull-left tab">
			<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Create new order lists here."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="field_config_order_lists.php"><button type="button" class="btn brand-btn mobile-block active_tab">Order Lists</button></a>
		</div>
		<div class="pull-left tab">
			<span class="popover-examples no-gap-pad"><a data-toggle="tooltip" data-placement="top" title="Click here to enable/disable the Import/Export functionality."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<a href="field_config_inventory.php?type=impexp"><button type="button" class="btn brand-btn mobile-block">Import/Export</button></a>
		</div>
		<div class="clearfix"></div>
    </div>

    <h3>Order Lists</h3>
	<?php if(isset($_GET['order_id'])) { ?><a href='field_config_order_lists.php'><span class='btn brand-btn mobile-block ' ><-- Back</span></a><?php } else { ?><span class='btn brand-btn mobile-block create_list' >Create New List</span>
	<?php } ?>

	<div class='create_list_form' style='<?php if(isset($_GET['order_id'])) { } else { ?>display:none;<?php } ?> border: 10px outset darkgrey; background-color:lightgrey;color:black;border-radius:10px; padding: 10px; margin:15px;'>
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
		<div class="form-group">
					<label for="travel_task" class="col-sm-3 control-label"><span class="popover-examples list-inline" style="margin:-5px 5px 0 0"><a data-toggle="tooltip" data-placement="top" title="This drop down menu displays all vendors from the Contacts tile."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>Vendor<span class="brand-color"></span>:<br><em><span id="tax_exemption_fillup"></em></span></label>
					<div class="col-sm-9">
						<select id="customerid" name="contactid" data-placeholder="Choose a Vendor..." class="chosen-select-deselect form-control" width="380">
							<option value=""></option>
							  <?php
								$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE (category='Vendors' or category='Vendor') AND deleted=0 ORDER BY IF(name RLIKE '^[a-z]', 1, 2)"),MYSQLI_ASSOC));
								foreach($query as $id) {
									$selected = '';
									$selected = $id == $vendorid_attached ? 'selected = "selected"' : '';
									echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
								}
							  ?>
						</select>
					</div>
				</div>
		<div class="form-group" id="" style="">
			<label for="travel_task" class="col-sm-4 control-label">Include in Purchase Orders:
			</label>
			<div class="col-sm-8">
				<input style='width:20px;height:20px;' <?php if($include_in_po == '1') { echo 'checked'; } ?> name="includeinpo" value="1" type="checkbox" class="" />
			</div>
        </div>
		<div class="form-group" id="" style="">
			<label for="travel_task" class="col-sm-4 control-label">Include in <?= SALES_ORDER_TILE ?>:
			</label>
			<div class="col-sm-8">
				<input  style='width:20px;height:20px;' <?php if($include_in_so == '1') { echo 'checked'; } ?> name="includeinso" value="1" type="checkbox" class="" />
			</div>
        </div>
		<!-- NOT YET DEVELOPED -->
		<!--<div class="form-group" id="" style="">
			<label for="travel_task" class="col-sm-4 control-label">Include in Point of Sale:
			</label>
			<div class="col-sm-8">
				<input style='width:20px;height:20px;' <?php //if($include_in_pos == '1') { echo 'checked'; } ?> name="includeinpos" value="1" type="checkbox" class="" />
			</div>
        </div>-->
		<div class="form-group" id="" style="">

		<div class="col-sm-8">
            <button type="submit" name="add_list" value="Submit" class="btn brand-btn btn-lg pull-right"><?php if(isset($_GET['order_id'])) { ?>Update List<?php } else { ?>Create List<?php } ?></button>
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
                $query_check_credentials = "SELECT * FROM order_lists WHERE deleted = 0 AND tile = 'VPL' AND (order_title LIKE '%" . $vendor . "%')";
            } else {
                $query_check_credentials = "SELECT * FROM order_lists WHERE deleted = 0 AND tile = 'VPL' ORDER BY order_title";
            }

            $result = mysqli_query($dbc, $query_check_credentials);
            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {

                echo "<table class='table table-bordered '>";
                echo "<tr class='hidden-xs hidden-sm'>";
                        echo '<th>List Name</th>';
                        echo '<th>Include in '.SALES_ORDER_TILE.'</th>';
                       // NOT YET DEVELOPED echo '<th>Include in Point of Sale</th>';
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
                        /* NOT YET DEVELOPED
						echo '<td data-title="Include in P.O.S.">';
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_pos'] !== '' && $row['include_in_pos'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['order_id']; ?>'  name='' class='point_of_sale_includer' value='1'><br>
						<?php
						echo '</td>'; */
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
