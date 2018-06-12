<?php $current_tab = basename($_SERVER['PHP_SELF'], '.php');
$current_cat = $_GET['category']; ?>
<div class="gap-top gap-bottom">
	<div class="pull-left tab"><a href="material.php?filter=Top"><button type="button" class="btn brand-btn mobile-block <?= $current_tab == 'material' ? 'active_tab' : '' ?>">Materials</button></a></div><?php
	$material_order_list = get_config($dbc, "material_order_list");
	if($material_order_list > 0) { ?>
		<div class="pull-left tab"><a href="order_list.php"><button type="button" class="btn brand-btn mobile-block <?= $current_tab == 'order_list' ? 'active_tab' : '' ?>">Order Lists</button></a></div>
	<?php }
	?>
	<div class="clearfix"></div>
</div>