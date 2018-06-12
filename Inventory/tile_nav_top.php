<?php 
$current_tab = basename($_SERVER['PHP_SELF'], '.php'); ?>
<div class="gap-top gap-bottom hide-titles-mob">
	<?php
	if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'inventory' ) === true) {
	    if(get_config($dbc, 'inventory_default_select_all') == 1) { ?>
	        <div class="pull-left tab"><a href='inventory.php?category=dispall_31V2irt2u3e5S3s1f2ADe3_31<?php echo $order_list;?>'><button type='button' class='btn brand-btn mobile-block <?= $current_tab == 'inventory' ? 'active_tab' : '' ?>' >Inventory</button></a></div><?php
	    } else { ?>
	        <div class="pull-left tab"><a href='inventory.php?category=Top<?php echo $order_list;?>'><button type='button' class='btn brand-btn mobile-block <?= $current_tab == 'inventory' ? 'active_tab' : '' ?>' >Inventory</button></a></div><?php
	    }
	}

	$inventory_setting  = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM inventory_setting WHERE inventorysettingid = 1"));
	$set_check_value    = $inventory_setting['value'];

	if (strpos($set_check_value, "rs") !== FALSE) {
	    if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'receive_shipment' ) === true) {
	        echo '<div class="pull-left tab"><a href="receive_shipment.php"><button type="button" class="btn brand-btn '.($current_tab == 'receive_shipment' ? 'active_tab' : '').'">Receive Shipment</button></a></div>';
	    }
	}

	if (strpos($set_check_value, ','."bom") !== FALSE || strpos($set_check_value, "bom") !== FALSE) {
	    if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'bill_of_material' ) === true) {
	        echo '<div class="pull-left tab"><a href="bill_of_material.php"><button type="button" class="btn brand-btn '.($current_tab == 'bill_of_material' ? 'active_tab' : '').'">Bill of Material</button></a></div>';
	    }
	}

	if (strpos($set_check_value, ','."bomc") !== FALSE || strpos($set_check_value, "bomc") !== FALSE) {
	    if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'bill_of_material_consumables' ) === true) {
	        echo '<div class="pull-left tab"><a href="bill_of_material_consumables.php"><button type="button" class="btn brand-btn '.($current_tab == 'bill_of_material_consumables' ? 'active_tab' : '').'">Bill of Material (Consumables)</button></a></div>';
	    }
	}

	if (strpos($set_check_value, ','."writeoff") !== FALSE || strpos($set_check_value, "writeoff") !== FALSE) {
	    if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'waste_write_off' ) === true) {
	        echo '<div class="pull-left tab"><a href="waste_write_off.php"><button type="button" class="btn brand-btn '.($current_tab == 'waste_write_off' ? 'active_tab' : '').'">Waste / Write-Off</button></a></div>';
	    }
	}

	if (strpos($set_check_value, ','."checklists") !== FALSE || strpos($set_check_value, "checklists") !== FALSE) {
	    if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'checklist' ) === true) {
	        echo '<div class="pull-left tab"><a href="inventory_checklist.php"><button type="button" class="btn brand-btn '.($current_tab == 'inventory_checklist' ? 'active_tab' : '').'">Checklists</button></a></div>';
	    }
	}

	if (strpos($set_check_value, ','."orderlists") !== FALSE || strpos($set_check_value, "orderlists") !== FALSE) {
	    if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'orderlists' ) === true) {
	        echo '<div class="pull-left tab"><a href="order_lists.php"><button type="button" class="btn brand-btn '.($current_tab == 'order_lists' ? 'active_tab' : '').'">Order Lists</button></a></div>';
	    }
	}

	if (strpos($set_check_value, ','."checklist_orders") !== FALSE || strpos($set_check_value, "checklist_orders") !== FALSE) {
		if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'checklist_orders' ) === true) {
			echo '<div class="pull-left tab"><a href="order_checklists.php"><button type="button" class="btn brand-btn '.($current_tab == 'order_checklists' ? 'active_tab' : '').'">Order Checklists</button></a></div>';
		}
	}
	?>
	<div class="clearfix"></div>
</div>