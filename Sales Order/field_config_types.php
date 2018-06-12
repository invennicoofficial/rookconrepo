<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
    $sales_order_types = filter_var(implode(',',$_POST['so_types']),FILTER_SANITIZE_STRING);
    set_config($dbc, 'sales_order_types', $sales_order_types);
}
?>
<script type="text/javascript">
	function addType() {
		var block = $('.type_div').last();
		var clone = block.clone();

		clone.find('.form-control').val('');
		block.after(clone);
	}
	function deleteType(btn) {
		if($('.type_div').length <= 1) {
			addType();
		}
		$(btn).closest('.type_div').remove();
	}
</script>
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
	<div class="gap-top">
		<?php $type_list = get_config($dbc, 'sales_order_types');
			$type_list = explode(',', $type_list);
			foreach ($type_list as $type) { ?>
				<div class="type_div form-group">
					<label class="col-sm-4 control-label">Type:</label>
					<div class="col-sm-7">
						<input type="text" name="so_types[]" class="form-control" value="<?= $type ?>">
					</div>
					<div class="col-sm-1 pull-right">
	                    <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addType();">
	                    <img src="../img/remove.png" class="inline-img pull-right" onclick="deleteType(this);">
					</div>
				</div>
			<?php }
		?>
	</div>
	<div class="pull-right gap-top gap-right gap-bottom">
	    <a href="index.php" class="btn brand-btn">Cancel</a>
	    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
	</div>
</form>