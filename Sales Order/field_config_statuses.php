<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
    $sales_order_statuses = filter_var(implode(',',$_POST['so_statuses']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(`configid`) AS `configid` FROM `general_configuration` WHERE `name`='sales_order_statuses'"));
    if($get_config['configid'] > 0) {
        $query_update_config  = "UPDATE `general_configuration` SET `value`='$sales_order_statuses' WHERE `name`='sales_order_statuses'";
        $result_update_config = mysqli_query($dbc, $query_update_config);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_statuses', '$sales_order_statuses')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
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
	<div class="gap-top">
		<?php $status_list = get_config($dbc, 'sales_order_statuses');
			if(empty($status_list)) {
				$status_list = 'Opportunity,With Client,Fulfillment';
			}
			$status_list = explode(',', $status_list);
			if(($key = array_search('Complete', $status_list)) !== false) {
			    unset($status_list[$key]);
			}
			foreach ($status_list as $status) { ?>
				<div class="status_div form-group">
					<label class="col-sm-4 control-label">Status:</label>
					<div class="col-sm-7">
						<input type="text" name="so_statuses[]" class="form-control" value="<?= $status ?>">
					</div>
					<div class="col-sm-1 pull-right">
	                    <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addStatus();">
	                    <img src="../img/remove.png" class="inline-img pull-right" onclick="deleteStatus(this);">
					</div>
				</div>
			<?php }
		?>
		<div class="form-group">
	        <label for="office_country" class="col-sm-4 control-label">Preset Status:</label>
	        <div class="col-sm-8" style="padding-top: 7px;">
	          <ul><li>Complete</li>
			  </ul>
	        </div>
	    </div>
	</div>
	<div class="pull-right gap-top gap-right gap-bottom">
	    <a href="index.php" class="btn brand-btn">Cancel</a>
	    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
	</div>
</form>