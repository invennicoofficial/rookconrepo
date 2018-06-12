<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
	foreach($_POST['security_level'] as $i => $level) {
		$access = $_POST['security_access'][$i];
		mysqli_query($dbc, "INSERT INTO `field_config_so_security` (`security_level`) SELECT '$level' FROM (SELECT COUNT(*) rows FROM `field_config_so_security` WHERE `security_level` = '$level') num WHERE num.rows=0");
		mysqli_query($dbc, "UPDATE `field_config_so_security` SET `access` = '$access' WHERE `security_level` = '$level'");
	}
}
?>
<script type="text/javascript">
	function addStatus() {
		var block = $('.status_div').last();
		var clone = block.clone();

		clone.find('.form-control').val('');
		block.after(clone);
	}
	function deleteStatus(btn) {
		if($('.status_div').length <= 1) {
			addStatus();
		}
		$(btn).closest('.status_div').remove();
	}
</script>
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="notice double-gap-bottom popover-examples">
	    <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
	    <div class="col-sm-11">
	        <span class="notice-name">NOTE:</span>
	        Configure what Forms the logged-in user can access based on their Security Level. Only Security Levels that have the <?= SALES_ORDER_NOUN ?> tile enabled will be visible in this page. If you do not see a Security Level here, please make sure the tile is enabled for that Security Level from the Security tile.</div>
	    <div class="clearfix"></div>
	</div>
	<div id="no-more-tables" class="gap-top">
		<table class="table table-bordered">
			<tr class="hide-titles-mob">
				<th>Security Level</th>
				<th>All Access</th>
				<th>Forms Assigned to User Only</th>
			</tr>
			<?php $i = 0;
			$security_levels = get_security_levels($dbc);
			foreach($security_levels as $level_label => $security_level) {
				if(tile_visible($dbc, 'sales_order', $security_level)) {
					$security_access = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `field_config_so_security` WHERE `security_level` = '$security_level'"))['access'];
					if(empty($security_access)) {
						$security_access = 'ALL';
					} ?>
					<tr>
						<input type="hidden" name="security_level[<?= $i ?>]" value="<?= $security_level ?>">
						<td data-title="Security Level"><?= $level_label ?></td>
						<td data-title="All Access">
							<label class="form-checkbox"><input type="radio" name="security_access[<?= $i ?>]" value="ALL" <?= $security_access == 'ALL' ? 'checked' : '' ?>> All Access</label>
						</td>
						<td data-title="Forms Assigned to User Only">
							<label class="form-checkbox"><input type="radio" name="security_access[<?= $i ?>]" value="Assigned Only" <?= $security_access == 'Assigned Only' ? 'checked' : '' ?>> Forms Assigned to User Only</label>
						</td>
					</tr>
					<?php $i++;
				}
			} ?>
		</table>
	</div>
	<div class="pull-right gap-top gap-right gap-bottom">
	    <a href="index.php" class="btn brand-btn">Cancel</a>
	    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
	</div>
</form>