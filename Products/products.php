<?php
/*
Customer Listing
*/
include ('../include.php');
?>
<script type="text/javascript">
$(document).ready(function() {
	$('input.purchase_order_includer').on('change', function() {
		var id = $(this).attr('id');
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = ''; }
		$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../ajax_all.php?fill=include_in_orders&type=po&name=product&value="+value+"&status="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
            location.reload();
		}
		});
	});

	$('input.inventory_includer').on('change', function() {
		var id = $(this).attr('id');
		if($(this).prop('checked') == true){
			var value = $(this).attr('value');
		} else { var value = 0; }
		$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../ajax_all.php?fill=include_in_orders&type=inventory&name=product&value="+value+"&status="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
            location.reload();
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
		url: "../ajax_all.php?fill=include_in_orders&type=so&name=product&value="+value+"&status="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
            location.reload();
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
		url: "../ajax_all.php?fill=include_in_orders&type=pos&name=product&value="+value+"&status="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
            location.reload();
		}
		});
	});
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('products');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

		<div class="col-sm-10"><h1>Products Dashboard</h1></div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'products') == 1) {
					echo '<a href="field_config_products.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
        </div>
		<div class="clearfix"></div>

		<div class="notice double-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			In this section your business can properly outline all product headings and descriptions for quotes, ticketing systems, work orders, etc. Assigning your businesses products by Type, Category and Heading will enable reporting per product. Price points for products are added in the rate card section and may or may not be visible here. Products added here will display in the rate card; products added to the rate card may or may not be visible here.</div>
			<div class="clearfix"></div>
		</div>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">

            <center>
            <div class="form-group">
                <label for="site_name" class="col-sm-5 control-label">Search By Any:</label>
                <div class="col-sm-6">
                <?php if(isset($_POST['search_vendor_submit'])) { ?>
                    <input type="text" name="search_vendor" class="form-control" value="<?php echo $_POST['search_vendor']?>">
                <?php } else { ?>
                    <input type="text" name="search_vendor" class="form-control">
                <?php } ?>
                </div>
            </div>

            &nbsp;<button type="submit" name="search_vendor_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
            <button type="submit" name="display_all_vendor" value="Display All" class="btn brand-btn mobile-block">Display All</button>
            </center>

                <?php
					if(vuaed_visible_function($dbc, 'products') == 1) {
						echo '<a href="add_products.php" class="btn brand-btn mobile-block pull-right double-gap-top">Add Product</a>'; ?>
						<span class="popover-examples double-gap-top pull-right pad-5"><a data-toggle="tooltip" data-placement="top" title="Click here to add a Product."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
						echo '<a href="add_inventory_multiple.php" class="btn brand-btn mobile-block pull-right double-gap-top">Add Multiple Products</a>'; ?>
						<span class="popover-examples double-gap-top pull-right pad-5"><a data-toggle="tooltip" data-placement="top" title="Click here to add more than one Product at a time by exporting a list."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
					}
                ?>

            <div id="no-more-tables">

            <?php
            //Search
            $vendor = '';
            if (isset($_POST['search_vendor_submit'])) {
                if (isset($_POST['search_vendor'])) {
                    $vendor = $_POST['search_vendor'];
                }
            }
            if (isset($_POST['display_all_vendor'])) {
                $vendor = '';
            }

            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            if($vendor != '') {
                $query_check_credentials = "SELECT * FROM products WHERE deleted = 0 AND (product_type LIKE '%" . $vendor . "%' OR category ='$vendor' OR heading LIKE '%" . $vendor . "%') LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM products WHERE deleted = 0 AND (product_type LIKE '%" . $vendor . "%' OR category ='$vendor' OR heading LIKE '%" . $vendor . "%')";
            } else {
                $query_check_credentials = "SELECT * FROM products WHERE deleted = 0 ORDER BY product_type LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM products WHERE deleted = 0 ORDER BY product_type";
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT products_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['products_dashboard'].',';

                // Added Pagination //
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>";
                    if (strpos($value_config, ','."Product Code".',') !== FALSE) {
                        echo '<th>Product Code</th>';
                    }
                    if (strpos($value_config, ','."Product Type".',') !== FALSE) {
                        echo '<th>Product Type</th>';
                    }
                    if (strpos($value_config, ','."Category".',') !== FALSE) {
                        echo '<th>Category</th>';
                    }
                    if (strpos($value_config, ','."Heading".',') !== FALSE) {
                        echo '<th>Heading</th>';
                    }
                    if (strpos($value_config, ','."Unit of Measure".',') !== FALSE) {
                        echo '<th>Unit of Measure</th>';
                    }
                    if (strpos($value_config, ','."Fee".',') !== FALSE) {
                        echo '<th>Fee</th>';
                    }
                    if (strpos($value_config, ','."Cost".',') !== FALSE) {
                        echo '<th>Cost</th>';
                    }
                    if (strpos($value_config, ','."Description".',') !== FALSE) {
                        echo '<th>Description</th>';
                    }
                    if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
                        echo '<th>Quote Description</th>';
                    }
                    if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                        echo '<th>Invoice Description</th>';
                    }
                    if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                        echo '<th>'.TICKET_NOUN.' Description</th>';
                    }
                    if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
                        echo '<th>Final Retail Price</th>';
                    }
                    if (strpos($value_config, ','."Admin Price".',') !== FALSE) {
                        echo '<th>Admin Price</th>';
                    }
                    if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) {
                        echo '<th>Wholesale Price</th>';
                    }
                    if (strpos($value_config, ','."Commercial Price".',') !== FALSE) {
                        echo '<th>Commercial Price</th>';
                    }
                    if (strpos($value_config, ','."Client Price".',') !== FALSE) {
                        echo '<th>Client Price</th>';
                    }
					if (strpos($value_config, ','."Purchase Order Price".',') !== FALSE) {
                        echo '<th>Purchase Order Price</th>';
                    }
					if (strpos($value_config, ','."Sales Order Price".',') !== FALSE) {
                        echo '<th>'.SALES_ORDER_NOUN.' Price</th>';
                    }
					if (strpos($value_config, ','."Include in Sales Orders".',') !== FALSE) {
                        echo '<th>Include in '.SALES_ORDER_TILE.'</th>';
                    }
					if (strpos($value_config, ','."Include in P.O.S.".',') !== FALSE) {
                        echo '<th>Include in Point of Sale</th>';
                    }
					if (strpos($value_config, ','."Include in Purchase Orders".',') !== FALSE) {
                        echo '<th>Include in Purchase Orders</th>';
                    }
					if (strpos($value_config, ','."Include in Inventory".',') !== FALSE) {
                        echo '<th>Include in Inventory</th>';
                    }
                    if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
                        echo '<th>Minimum Billable</th>';
                    }
                    if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
                        echo '<th>Hourly Rate</th>';
                    }
                    if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
                        echo '<th>Estimated Hours</th>';
                    }
                    if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
                        echo '<th>Actual Hours</th>';
                    }
                    if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                        echo '<th>MSRP</th>';
                    }
                    if (strpos($value_config, ','."Drum Unit Cost".',') !== FALSE) {
                        echo '<th>Drum Unit Cost</th>';
                    }

                    if (strpos($value_config, ','."Drum Unit Price".',') !== FALSE) {
                        echo '<th>Drum Unit Price</th>';
                    }
                    if (strpos($value_config, ','."Tote Unit Cost".',') !== FALSE) {
                        echo '<th>Tote Unit Cost</th>';
                    }
                    if (strpos($value_config, ','."Tote Unit Price".',') !== FALSE) {
                        echo '<th>Tote Unit Price</th>';
                    }

                    echo '<th>Function</th>';
                    echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                if (strpos($value_config, ','."Product Code".',') !== FALSE) {
                    echo '<td data-title="Product Code">' . $row['product_code'] . '</td>';
                }
                if (strpos($value_config, ','."Product Type".',') !== FALSE) {
       				echo '<td data-title="Product Type">' . $row['product_type'] . '</td>';
                }
                if (strpos($value_config, ','."Category".',') !== FALSE) {
                    echo '<td data-title="Category">' . $row['category'] . '</td>';
                }
                if (strpos($value_config, ','."Heading".',') !== FALSE) {
                    echo '<td data-title="Heading">' . $row['heading'] . '</td>';
                }
                if (strpos($value_config, ','."Unit of Measure".',') !== FALSE) {
                    echo '<td data-title="Unit of Measure">' . $row['name'] . '</td>';
                }
                if (strpos($value_config, ','."Fee".',') !== FALSE) {
                    echo '<td data-title="Fee">' . $row['fee'] . '</td>';
                }
                if (strpos($value_config, ','."Cost".',') !== FALSE) {
                    echo '<td data-title="Cost">' . $row['cost'] . '</td>';
                }
                if (strpos($value_config, ','."Description".',') !== FALSE) {
                    echo '<td data-title="Description">' . html_entity_decode($row['description']) . '</td>';
                }
                if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
                    echo '<td data-title="Quote Description">' . html_entity_decode($row['quote_description']) . '</td>';
                }
                if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                    echo '<td data-title="Invoice Description">' . html_entity_decode($row['invoice_description']) . '</td>';
                }
                if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                    echo '<td data-title="'.TICKET_NOUN.' Description">' . html_entity_decode($row['ticket_description']) . '</td>';
                }
                if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
                    echo '<td data-title="Final Retail Price">' . $row['final_retail_price'] . '</td>';
                }
                if (strpos($value_config, ','."Admin Price".',') !== FALSE) {
                    echo '<td data-title="Admin Price">' . $row['admin_price'] . '</td>';
                }
                if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) {
                    echo '<td data-title="Wholesale Price">' . $row['wholesale_price'] . '</td>';
                }
                if (strpos($value_config, ','."Commercial Price".',') !== FALSE) {
                    echo '<td data-title="Commercial Price">' . $row['commercial_price'] . '</td>';
                }
                if (strpos($value_config, ','."Client Price".',') !== FALSE) {
                    echo '<td data-title="Client Price">' . $row['client_price'] . '</td>';
                }
				if (strpos($value_config, ','."Purchase Order Price".',') !== FALSE) {
                    echo '<td data-title="Purchase Order Price">' . $row['purchase_order_price'] . '</td>';
                }
				if (strpos($value_config, ','."Sales Order Price".',') !== FALSE) {
                    echo '<td data-title="'.SALES_ORDER_NOUN.' Price">' . $row['sales_order_price'] . '</td>';
                }
				if (strpos($value_config, ','."Include in Sales Orders".',') !== FALSE) {
                        echo '<td data-title="Include in '.SALES_ORDER_TILE.'">';
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_so'] !== '' && $row['include_in_so'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['productid']; ?>'  name='' class='sales_order_includer' value='1'><br>
						<?php
						echo '</td>';
                    }
					if (strpos($value_config, ','."Include in P.O.S.".',') !== FALSE) {
                        echo '<td data-title="Include in P.O.S.">';
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_pos'] !== '' && $row['include_in_pos'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['productid']; ?>'  name='' class='point_of_sale_includer' value='1'><br>
						<?php
						echo '</td>';
                    }
					if (strpos($value_config, ','."Include in Purchase Orders".',') !== FALSE) {
                        echo '<td data-title="Include in Purchase Orders">';
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_po'] !== '' && $row['include_in_po'] !== NULL) { echo "checked"; } ?> id='<?PHP echo $row['productid']; ?>'  name='' class='purchase_order_includer' value='1'><br>
						<?php
						echo '</td>';
                    }
					if (strpos($value_config, ','."Include in Inventory".',') !== FALSE) {
                        echo '<td data-title="Include in Inventory">';
                        if($row['inventoryid'] == '') {
						?><input type='checkbox' style='width:20px; height:20px;' <?php if($row['include_in_inventory'] !== '' && $row['include_in_inventory'] !== NULL && $row['include_in_inventory'] == 1) { echo "checked"; } ?> id='<?PHP echo $row['productid']; ?>'  name='' class='inventory_includer' value='1'><br>
						<?php
                        } else {
                            echo 'Already Included';
                        }
						echo '</td>';                    }
                if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
                    echo '<td data-title="Minimum Billable">' . $row['minimum_billable'] . '</td>';
                }
                if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
                    echo '<td data-title="Hourly Rate">' . $row['hourly_rate'] . '</td>';
                }
                if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
                    echo '<td data-title="Estimated Hours">' . $row['estimated_hours'] . '</td>';
                }
                if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
                    echo '<td data-title="Actual Hours">' . $row['actual_hours'] . '</td>';
                }
                if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                    echo '<td data-title="MSRP">' . $row['msrp'] . '</td>';
                }

                if (strpos($value_config, ','."Drum Unit Cost".',') !== FALSE) {
                    echo '<td data-title="Drum Unit Cost">' . $row['drum_unit_cost'] . '</td>';
                }

                if (strpos($value_config, ','."Drum Unit Price".',') !== FALSE) {
                    echo '<td data-title="Drum Unit Price">' . $row['drum_unit_price'] . '</td>';
                }
                if (strpos($value_config, ','."Tote Unit Cost".',') !== FALSE) {
                    echo '<td data-title="Tote Unit Cost">' . $row['tote_unit_cost'] . '</td>';
                }
                if (strpos($value_config, ','."Tote Unit Price".',') !== FALSE) {
                    echo '<td data-title="Tote Unit Price">' . $row['tote_unit_price'] . '</td>';
                }

                echo '<td data-title="Function">';
                if($row['inventoryid'] == '') {
                if(vuaed_visible_function($dbc, 'products') == 1) {
                echo '<a href=\'add_products.php?productid='.$row['productid'].'\'>Edit</a> | ';
				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&productid='.$row['productid'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
                }
                }
                echo '</td>';

                echo "</tr>";
            }

            echo '</table></div>';

            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //
            if(vuaed_visible_function($dbc, 'products') == 1) {
            echo '<a href="add_products.php" class="btn brand-btn mobile-block pull-right">Add Product</a>'; ?>
			<span class="popover-examples pull-right pad-5"><a data-toggle="tooltip" data-placement="top" title="Click here to add a Product."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
            }

            ?>
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
