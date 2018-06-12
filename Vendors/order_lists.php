<?php

if(isset($_GET['deleteid'])) {
	$deleteid = $_GET['deleteid'];
	$query_update_vendor = "UPDATE `order_lists` SET `deleted` = '1' WHERE `order_id` = '$deleteid'";
    $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
	 echo '<script type="text/javascript"> window.location.replace("order_lists.php"); </script>';
}

/* if (isset($_POST['add_list'])) {
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

    echo '<script type="text/javascript">window.location.replace("order_lists.php"); </script>';
} */
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

<?php
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

<div><?php
    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `receive_shipment` FROM `field_config_inventory` WHERE tab='receive_shipment' AND accordion='receive_shipment'"));
    $inventory_dashboard_config = ','.$get_field_config['receive_shipment'].',';
    
    if(isset($_GET['order_id'])) { ?>
        <a href='order_lists.php'><span class='btn brand-btn mobile-block'><-- Back</span></a><?php
    } else { ?>
        <span class='btn brand-btn mobile-block create_list'>Create New List</span><?php
    } ?>

	<div class='create_list_form' style='<?php if(isset($_GET['order_id'])) { } else { ?>display:none;<?php } ?> border: 10px outset darkgrey; background-color:lightgrey;color:black;border-radius:10px; padding: 10px; margin:15px;'>
        <!--<form id="" name="x" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">-->
            <input type='hidden' name='order_id' value='<?php if(isset($_GET['order_id'])) { echo $_GET['order_id']; } ?>' />
            <input type="hidden" name="contactid" value="<?= $vendorid_attached; ?>" />
            <div class="form-group" id="new_product" style="">
                <label for="travel_task" class="col-sm-4 control-label">List Name:</label>
                <div class="col-sm-8"><input name="list_name" value="<?php echo $list_name; ?>" required type="text" class="form-control" /></div>
            </div>
            <div class="form-group" id="" style="">
                <label for="travel_task" class="col-sm-4 control-label">Include in Purchase Orders:</label>
                <div class="col-sm-8"><input style='width:20px;height:20px;' <?php if($include_in_po == '1') { echo 'checked'; } ?> name="includeinpo" value="1" type="checkbox" class="" /></div>
            </div>
            <div class="form-group" id="" style="">
                <label for="travel_task" class="col-sm-4 control-label">Include in <?= SALES_ORDER_TILE ?>:</label>
                <div class="col-sm-8"><input  style='width:20px;height:20px;' <?php if($include_in_so == '1') { echo 'checked'; } ?> name="includeinso" value="1" type="checkbox" class="" /></div>
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
        <!--</form>-->
    </div><!-- .create_list_form -->

    <br>

    <div id="no-more-tables" class='list_dashboard' <?php if(isset($_GET['order_id'])) { ?>style='display:none;'<?php } ?>><?php
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

            while ( $row=mysqli_fetch_array($result) ) {
                echo "<tr>";
                    echo '<td data-title="List Name">' . $row['order_title'] . '</td>';
                    echo '<td data-title="Include in '.SALES_ORDER_TILE.'">'; ?>
                        <input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_so'] !== '' && $row['include_in_so'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['order_id']; ?>'  name='' class='sales_order_includer' value='1'><br><?php
                    echo '</td>';
                    /* NOT YET DEVELOPED
                    echo '<td data-title="Include in P.O.S.">';
                    ?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_pos'] !== '' && $row['include_in_pos'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['order_id']; ?>'  name='' class='point_of_sale_includer' value='1'><br>
                    <?php
                    echo '</td>'; */
                    echo '<td data-title="Include in Purchase Orders">'; ?>
                        <input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_po'] !== '' && $row['include_in_po'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['order_id']; ?>'  name='' class='purchase_order_includer' value='1'><br><?php
                    echo '</td>';
                    echo '<td data-title="Inventory">'; ?>
                        <a href='inventory.php?order_list=<?php echo $row['order_id']; ?>&category=Top&contactid=<?= $contactid; ?>'  style='width:100%;text-align:center;display: block;' target='_blank'>Add Inventory to List</a><hr style='margin:3px;padding:0; box-shadow: 1px 1px 1px #0e0e0e;'><?php
                        $inv = $row['inventoryid'];
                        if($inv == '' || $inv == NULL) {
                            $num_of_inv = '0';
                        } else {
                            $inv = explode(",", $inv);
                            $num_of_inv = count($inv);
                        } ?>
                        <a href='inventory.php?order_list=<?php echo $row['order_id']; ?>&currentlist&category=Top&contactid=<?= $contactid; ?>' style='width:100%;text-align:center;display: block;'  target='_blank'>View Current List of Inventory (<?php echo $num_of_inv; ?>)</a><?php
                    echo '</td>';
                    echo '<td data-title="Function">';
                        echo '<a href=\'order_lists.php?order_id='.$row['order_id'].'\'>Edit</a> | ';
                        echo '<a href=\'order_lists.php?deleteid='.$row['order_id'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                    echo '</td>';
                echo "</tr>";
            }
            
            echo '</table>';
        
        } else {
            echo "<h2 class='list_dashboard'>No Record Found.</h2>";
        } ?>
    </div>

</div>