<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
    $invoice_payment_types = filter_var(implode(',',$_POST['so_payment_types']),FILTER_SANITIZE_STRING);
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_invoice_payment_types'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = 'Pay Now,Net 30,Net 60,Net 90,Net 120,$invoice_payment_types' WHERE name='sales_order_invoice_payment_types'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_invoice_payment_types', 'Pay Now,Net 30,Net 60,Net 90,Net 120,$invoice_payment_types')";
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
		<?php $payment_types = str_replace('Pay Now,Net 30,Net 60,Net 90,Net 120,', '', get_config($dbc, 'sales_order_invoice_payment_types'));
			if(empty($payment_types)) {
				$payment_types = '';
			}
			$payment_types = explode(',',$payment_types);
			foreach ($payment_types as $payment_type) { ?>
				<div class="status_div form-group">
					<label class="col-sm-4 control-label">Payment Type:</label>
					<div class="col-sm-7">
						<input type="text" name="so_payment_types[]" class="form-control" value="<?= $payment_type ?>">
					</div>
					<div class="col-sm-1 pull-right">
	                    <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addStatus();">
	                    <img src="../img/remove.png" class="inline-img pull-right" onclick="deleteStatus(this);">
					</div>
				</div>
			<?php }
		?>
		<div class="form-group">
	        <label for="office_country" class="col-sm-4 control-label">Preset Payment Type Options:</label>
	        <div class="col-sm-8" style="padding-top: 7px;">
	          <ul><li>Pay Now</li>
			  <li>Net 30</li>
			  <li>Net 60</li>
			  <li>Net 90</li>
			  <li>Net 120</li>
			  </ul>
	        </div>
	    </div>
	</div>
	<div class="pull-right gap-top gap-right gap-bottom">
	    <a href="index.php" class="btn brand-btn">Cancel</a>
	    <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
	</div>
</form>