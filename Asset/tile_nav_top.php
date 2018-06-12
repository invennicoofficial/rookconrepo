<?php $current_tab = basename($_SERVER['PHP_SELF'], '.php'); ?>
<div class="gap-top gap-bottom">
	<div class="pull-left tab"><a href="asset.php?category=Top"><button type="button" class="btn brand-btn mobile-block <?= $current_tab == 'asset' ? 'active_tab' : '' ?>">Assets</button></a></div><?php
	$asset_order_list = get_config($dbc, "asset_order_list");
	if($asset_order_list > 0) { ?>
		<div class="pull-left tab"><a href="order_list.php"><button type="button" class="btn brand-btn mobile-block <?= $current_tab == 'order_list' ? 'active_tab' : '' ?>">Order Lists</button></a></div>
	<?php }
	?>
	<div class="clearfix"></div>
</div>