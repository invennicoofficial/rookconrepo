<?php include_once('../include.php');
$po_num_list = []; ?>
<div class="col-sm-12">
	<?php if(isset($_GET['co'])) {
		$co_num = filter_var($_GET['co'],FILTER_SANITIZE_STRING); ?>
		<h2>Customer Order #<?= $co_num ?></h2>
		<?php $po_numbers = $dbc->query("SELECT `po_num` FROM `ticket_attached` WHERE `deleted`=0 AND `src_table`='inventory' AND `position`='$co_num' AND IFNULL(`po_num`,'') != '' GROUP BY `po_num`");
		while($po_num = $po_numbers->fetch_assoc()) {
			$po_num_list[] = $po_num['po_num'];
		}
		$po_numbers = $dbc->query("SELECT `purchase_order` FROM `tickets` WHERE `deleted`=0 AND CONCAT('#*#',`customer_order_num`,'#*#') LIKE '%#*#$co_num#*#%'");
		while($po_num = $po_numbers->fetch_assoc()) {
			foreach(array_filter(explode('#*#',$po_num['purchase_order'])) as $po_num_item) {
				$po_num_list[] = $po_num_item;
			}
		}
		$po_num_list = array_unique($po_num_list);
		sort($po_num_list);
		foreach($po_num_list as $po_num) { ?>
			<h3>Purchase Order #<?= $po_num ?></h3>
		<?php }
	} else if(isset($_GET['po'])) {
		$po_num_list[] = filter_var($_GET['po'],FILTER_SANITIZE_STRING);
		foreach($po_num_list as $po_num) { ?>
			<h3>Purchase Order #<?= $po_num ?></h3>
			<?php $_GET['purchase_order'] = $po_num;
			$_GET['no_search'] = 'true';
			include('../Inventory/inventory_inc.php');
		}
	} ?>
</div>