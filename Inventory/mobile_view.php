<?php
$strict_view = strictview_visible_function($dbc, 'inventory');
$tile_security = get_security($dbc, 'inventory');
if($strict_view > 0) {
    $tile_security['edit'] = 0;
    $tile_security['config'] = 0;
}
$match_business = '';
if(!empty(MATCH_CONTACTS)) {
	$match_business = " AND `tickets`.`businessid` IN (".MATCH_CONTACTS.")";
	$match_business_picklist = " AND `businessid` IN (".MATCH_CONTACTS.")";
} ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#mobile_tabs .panel-heading').click(loadPanel);
});
function loadPanel() {
    $('#mobile_tabs .panel-heading:not(.higher_level_heading)').closest('.panel').find('.panel-body').html('Loading...');
    if(!$(this).hasClass('higher_level_heading')) {
	    var panel = $(this).closest('.panel').find('.panel-body');
	    panel.html('Loading...');
	    $.ajax({
	        url: panel.data('file'),
	        method: 'POST',
	        response: 'html',
	        success: function(response) {
	            panel.html(response);
				$('.pagination_links a').click(pagination_load);
	        }
	    });
	}
}
function pagination_load() {
	var target = $(this).closest('.panel').find('.panel-body');
	$.ajax({
		url: this.href,
		method: 'POST',
		response: 'html',
		success: function(response) {
			target.html(response);
			$('.pagination_links a').click(pagination_load);
		}
	});
	return false;
}
</script>
<?php 
$inventory_setting  = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM inventory_setting WHERE inventorysettingid = 1"));
$set_check_value    = ','.$inventory_setting['value'].','; ?>
<div class="show-on-mob panel-group block-panels col-xs-12 form-horizontal" id="mobile_tabs">
	<div class="panel panel-default" style="background: white;">
		<div class="panel-heading higher_level_heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_inventory">
					Inventory<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_inventory" class="panel-collapse collapse">
            <div class="panel-body" style="padding: 0; margin: -1px;" id="collapse_inventory_body">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#collapse_inventory_body" href="#collapse_inventory_last25" class="double-pad-left">
								Last 25 Added<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_inventory_last25" class="panel-collapse collapse">
						<div class="panel-body" data-file="inventory_inc.php?category=Top">
							Loading...
						</div>
					</div>
				</div>
				<?php $tabs = get_config($dbc, 'inventory_tabs');
				$each_tab = explode('#*#', $tabs);
				foreach($each_tab as $cat_tab) {
					$url_tab = preg_replace('/[^a-z]/','',strtolower($cat_tab)); ?>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#collapse_inventory_body" href="#collapse_inventory_<?= $url_tab ?>" class="double-pad-left">
									<?= $cat_tab ?><span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_inventory_<?= $url_tab ?>" class="panel-collapse collapse">
							<div class="panel-body" data-file="inventory_inc.php?category=<?= $url_tab ?>">
								Loading...
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<?php if (strpos($set_check_value, "warehouse") !== FALSE && check_subtab_persmission( $dbc, 'inventory', ROLE, 'warehouse' ) === true) {
		$warehouse_name = '';
		$warehouses = '';
		$purchase_orders = '';
		$tickets = '';
		$i = 0;
		foreach(sort_contacts_query($dbc->query("SELECT `contacts`.`contactid`,`contacts`.`name`,IFNULL(IFNULL(NULLIF(`ticket_attached`.`po_num`,''),`tickets`.`purchase_order`),'') `display_name`,IFNULL(`tickets`.`ticket_label`,'') `ticket_label`,`tickets`.`ticketid`,COUNT(*) `inv_count` FROM `contacts` LEFT JOIN `ticket_schedule` ON `contacts`.`contactid`=`ticket_schedule`.`warehouse_location` LEFT JOIN `ticket_attached` ON `ticket_schedule`.`ticketid`=`ticket_attached`.`ticketid` LEFT JOIN `tickets` ON `ticket_attached`.`ticketid`=`tickets`.`ticketid` WHERE `ticket_schedule`.`warehouse_location` > 0 AND `ticket_attached`.`src_table` IN ('inventory_general') AND `tickets`.`deleted`=0 AND `ticket_attached`.`deleted`=0 AND `ticket_schedule`.`deleted`=0 $match_business GROUP BY `contacts`.`contactid`,`contacts`.`name`,`contacts`.`last_name`,`contacts`.`first_name`, IFNULL(NULLIF(`ticket_attached`.`po_num`,''),`tickets`.`purchase_order`) ORDER BY IFNULL(NULLIF(`ticket_attached`.`po_num`,''),`tickets`.`purchase_order`)")) as $warehouse) {
			if($warehouse_name != $warehouse['name'].' '.$warehouse['first_name'].' '.$warehouse['last_name']) {
				if($warehouse_name != '') {
					$purchase_orders .= '</div></div></div>';
					$tickets .= '</div></div></div>';
					$warehouses .= $purchase_orders.$tickets;
				}
				$warehouse_name = $warehouse['name'].' '.$warehouse['first_name'].' '.$warehouse['last_name'];
				$purchase_orders = '<div class="panel panel-default" style="background: white;">
					<div class="panel-heading higher_level_heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_warehouse_po_'.$i.'">
								'.$warehouse_name.' - Purchase Orders<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>
					<div id="collapse_warehouse_po_'.$i.'" class="panel-collapse collapse">
			            <div class="panel-body" style="padding: 0; margin: -1px;" id="collapse_warehouse_po_body_'.$i.'">';

				$tickets = '<div class="panel panel-default" style="background: white;">
					<div class="panel-heading higher_level_heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_warehouse_ticket_'.$i.'">
								'.$warehouse_name.' - '.TICKET_TILE.'<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>
					<div id="collapse_warehouse_ticket_'.$i.'" class="panel-collapse collapse">
			            <div class="panel-body" style="padding: 0; margin: -1px;" id="collapse_warehouse_ticket_body_'.$i.'">';
			}
			$purchase_orders .= '<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#collapse_warehouse_po_'.$i.'" href="#collapse_warehouse_po_'.$i.'_'.$warehouse['contactid'].'" class="double-pad-left">
								'.($warehouse['display_name'] != '' ? $warehouse['display_name'] : 'No Purchase Order #').'<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_warehouse_po_'.$i.'_'.$warehouse['contactid'].'" class="panel-collapse collapse">
						<div class="panel-body" data-file="warehouse_inc.php?warehouse='.$warehouse['contactid'].'&po='.$warehouse['display_name'].'">
							Loading...
						</div>
					</div>
				</div>';
			$tickets .= '<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#collapse_warehouse_ticket_'.$i.'" href="#collapse_warehouse_ticket_'.$i.'_'.$warehouse['contactid'].'" class="double-pad-left">
								'.($warehouse['display_name'] != '' ? $warehouse['ticket_label'] : 'No '.TICKET_NOUN).'<span class="glyphicon glyphicon-plus"></span>
							</a>
						</h4>
					</div>

					<div id="collapse_warehouse_ticket_'.$i.'_'.$warehouse['contactid'].'" class="panel-collapse collapse">
						<div class="panel-body" data-file="warehouse_inc.php?warehouse='.$warehouse['contactid'].'&ticket='.$warehouse['ticketid'].'">
							Loading...
						</div>
					</div>
				</div>';
			$i++;
		}
		if($warehouse_name != '') {
			$purchase_orders .= '</div></div></div>';
			$tickets .= '</div></div></div>';
			$warehouses .= $purchase_orders.$tickets;
		}
		echo $warehouses;
	} ?>

    <?php if (strpos($set_check_value, "pick_lists") !== FALSE && check_subtab_persmission( $dbc, 'inventory', ROLE, 'pick_list_create' ) === true) { ?>
		<div class="panel panel-default" style="background: white;">
			<div class="panel-heading higher_level_heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_picklist_manage">
						Manage <?= INVENTORY_NOUN ?> Pick Lists<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_picklist_manage" class="panel-collapse collapse">
	            <div class="panel-body" style="padding: 0; margin: -1px;" id="collapse_picklist_manage_body">
					<?php $pick_lists = $dbc->query("SELECT `id`, `name` FROM `pick_lists` WHERE `deleted`=0 $match_business_picklist ORDER BY `name`");
					while($list = $pick_lists->fetch_assoc()) { ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#collapse_picklist_manage_body" href="#collapse_picklist_manage_<?= $list['id'] ?>" class="double-pad-left">
										<?= $list['name'] ?><span class="glyphicon glyphicon-plus"></span>
									</a>
								</h4>
							</div>

							<div id="collapse_picklist_manage_<?= $list['id'] ?>" class="panel-collapse collapse">
								<div class="panel-body" data-file="pick_list_create_inc.php?edit=<?= $list['id'] ?>">
									Loading...
								</div>
							</div>
						</div>
					<?php } ?>
					<?php if($tile_security['edit'] > 0) { ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#collapse_picklist_manage_body" href="#collapse_picklist_manage_create" class="double-pad-left">
										Create New Pick List<span class="glyphicon glyphicon-plus"></span>
									</a>
								</h4>
							</div>

							<div id="collapse_picklist_manage_create" class="panel-collapse collapse">
								<div class="panel-body" data-file="pick_list_create_inc.php?edit=0">
									Loading...
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
    <?php } ?>

    <?php if (strpos($set_check_value, "pick_lists") !== FALSE && check_subtab_persmission( $dbc, 'inventory', ROLE, 'pick_list_fill' ) === true) { ?>
		<div class="panel panel-default" style="background: white;">
			<div class="panel-heading higher_level_heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_picklist_fill">
						Fill <?= INVENTORY_NOUN ?> Pick Lists<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_picklist_fill" class="panel-collapse collapse">
	            <div class="panel-body" style="padding: 0; margin: -1px;" id="collapse_picklist_fill_body">
					<?php $pick_lists = $dbc->query("SELECT `id`, `name` FROM `pick_lists` WHERE `deleted`=0 $match_business_picklist ORDER BY `name`");
					while($list = $pick_lists->fetch_assoc()) { ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#collapse_picklist_fill_body" href="#collapse_picklist_fill_<?= $list['id'] ?>" class="double-pad-left">
										<?= $list['name'] ?><span class="glyphicon glyphicon-plus"></span>
									</a>
								</h4>
							</div>

							<div id="collapse_picklist_fill_<?= $list['id'] ?>" class="panel-collapse collapse">
								<div class="panel-body" data-file="pick_list_fill_inc.php?edit=<?= $list['id'] ?>">
									Loading...
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
    <?php } ?>

    <?php if (strpos($set_check_value, "no_cost") !== FALSE) {
        if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'no_cost' ) === true) { ?>
			<div class="panel panel-default" style="background: white;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_no_cost">
							<?= INVENTORY_NOUN ?> Without a Cost<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_no_cost" class="panel-collapse collapse">
					<div class="panel-body" data-file="no_cost_inc.php">
						Loading...
					</div>
				</div>
			</div>
        <?php }
    } ?>

    <?php if (strpos($set_check_value, "rs") !== FALSE) {
        if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'receive_shipment' ) === true) { ?>
			<div class="panel panel-default" style="background: white;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_receive_shipment">
							Receive Shipment<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_receive_shipment" class="panel-collapse collapse">
					<div class="panel-body" data-file="receive_shipment_inc.php">
						Loading...
					</div>
				</div>
			</div>
        <?php }
    } ?>

    <?php if (strpos($set_check_value, ','."bom") !== FALSE || strpos($set_check_value, "bom") !== FALSE) {
        if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'bill_of_material' ) === true) { ?>
			<div class="panel panel-default" style="background: white;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_bill_of_material">
							Bill of Material<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_bill_of_material" class="panel-collapse collapse">
					<div class="panel-body" data-file="bill_of_material_inc.php">
						Loading...
					</div>
				</div>
			</div>
        <?php }
    } ?>

    <?php if (strpos($set_check_value, ','."bomc") !== FALSE || strpos($set_check_value, "bomc") !== FALSE) {
        if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'bill_of_material_consumables' ) === true) { ?>
			<div class="panel panel-default" style="background: white;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_bill_of_material_consumables">
							Bill of Material (Consumables)<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_bill_of_material_consumables" class="panel-collapse collapse">
					<div class="panel-body" data-file="bill_of_material_consumables_inc.php?from_type=mobile">
						Loading...
					</div>
				</div>
			</div>
        <?php }
    } ?>

    <?php if (strpos($set_check_value, ','."checklists") !== FALSE || strpos($set_check_value, "checklists") !== FALSE) {
        if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'checklist' ) === true) { ?>
			<div class="panel panel-default" style="background: white;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_checklists">
							Checklists<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_checklists" class="panel-collapse collapse">
					<div class="panel-body" data-file="inventory_checklist_inc.php">
						Loading...
					</div>
				</div>
			</div>
        <?php }
    } ?>

    <?php if (strpos($set_check_value, ','."orderlists") !== FALSE || strpos($set_check_value, "orderlists") !== FALSE) {
        if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'orderlists' ) === true) { ?>
			<div class="panel panel-default" style="background: white;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_orderlists">
							Order Lists<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_orderlists" class="panel-collapse collapse">
					<div class="panel-body" data-file="order_lists_inc.php">
						Loading...
					</div>
				</div>
			</div>
        <?php }
    } ?>

    <?php if (strpos($set_check_value, ','."checklist_orders") !== FALSE || strpos($set_check_value, "checklist_orders") !== FALSE) {
        if(check_subtab_persmission( $dbc, 'inventory', ROLE, 'checklist_orders' ) === true) { ?>
			<div class="panel panel-default" style="background: white;">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_checklist_orders">
							Order Checklists<span class="glyphicon glyphicon-plus"></span>
						</a>
					</h4>
				</div>

				<div id="collapse_checklist_orders" class="panel-collapse collapse">
					<div class="panel-body" data-file="order_checklist_display.php">
						Loading...
					</div>
				</div>
			</div>
        <?php }
    } ?>

</div>