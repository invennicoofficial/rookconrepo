<?php include_once('../include.php');
if(FOLDER_NAME == 'posadvanced') {
    checkAuthorised('posadvanced');
} else {
    checkAuthorised('check_out');
}

if(isset($_POST['submit'])) {
	$amounts = $_POST['payment_amt'];
	$total_paid = array_sum($amounts);
	foreach($_POST['due_invoiceid'] as $invoiceid) {
		$invoiceid = filter_var($invoiceid, FILTER_SANITIZE_STRING);
		$due = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`invoice`.`final_price`) - SUM(IFNULL(`payments`.`total`,0)) `due`, SUM(`invoice`.`final_price`) - SUM(`payments`.`third_party`) `customer_portion` FROM `invoice` LEFT JOIN (SELECT `invoiceid`, SUM(`amount`) `total`, SUM(IF(`payer_id`=`contactid`,0,`amount`)) `third_party` FROM `invoice_payment` WHERE `deleted`=0 AND `paid`=1 GROUP BY `invoiceid`) `payments` ON `invoice`.`invoiceid`=`payments`.`invoiceid` AND `invoice`.`deleted`=0 WHERE '$invoiceid' IN (`invoice`.`invoiceid`,`invoice`.`invoiceid_src`)"));
		foreach($amounts as $i => $amount) {
			$method = filter_var($_POST['payment_type'][$i], FILTER_SANITIZE_STRING);
			$amt = 0;
			if(($amount > 0 && $amount > $due['due']) || ($amount < 0 && $amount < $due['due'])) {
				$amt = $due['due'];
			} else {
				$amt = $amount;
			}
			$amounts[$i] -= $amt;
			$amt = filter_var($amt, FILTER_SANITIZE_STRING);
			mysqli_query($dbc, "INSERT INTO `invoice_payment` (`invoiceid`, `contactid`, `payer_id`, `paid`, `payment_method`, `date_paid`, `amount`) SELECT `invoiceid`, `patientid`, `patientid`, 1, '$method', DATE(NOW()), '$amt' FROM `invoice` WHERE `invoiceid`='$invoiceid'");
		}
	}
} else {
	error_reporting(0);
	$invoiceid = filter_var($_GET['invoiceid'],FILTER_SANITIZE_STRING);
	$invoice = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `invoice` WHERE `invoiceid`='$invoiceid'"));
	$injury = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `patient_injury` WHERE `injuryid`='{$invoice['injuryid']}'"));
	$due = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(`invoice`.`final_price`) - SUM(IFNULL(`payments`.`total`,0)) `due`, SUM(`invoice`.`final_price`) - SUM(`payments`.`third_party`) `customer_portion` FROM `invoice` LEFT JOIN (SELECT `invoiceid`, SUM(`amount`) `total`, SUM(IF(`payer_id`=`contactid`,0,`amount`)) `third_party` FROM `invoice_payment` GROUP BY `invoiceid`) `payments` ON `invoice`.`invoiceid`=`payments`.`invoiceid` AND `invoice`.`deleted`=0 WHERE '$invoiceid' IN (`invoice`.`invoiceid`,`invoice`.`invoiceid_src`)"));
	$field_config = explode(',', get_config($dbc, 'invoice_fields'));
	$purchaser_categories = array_filter(array_unique(explode(',', get_config($dbc, 'invoice_purchase_contact'))));
	$payer_categories = array_filter(array_unique(explode(',', get_config($dbc, 'invoice_payer_contact')))); ?>
	<script>
	var inv_due = <?= $due['due'] ?>;
	var total_due = inv_due;
	$(document).ready(function() {
		$('input[type=checkbox]').change(function() {
			total_due = inv_due;
			var inv_list = '<br />';
			$('input[name="due_invoiceid[]"]:checked').each(function() {
				var due = parseFloat($(this).data('due'));
				total_due += due;
				inv_list += 'Invoice #'+this.value+' '+due.toFixed(2)+'<br />';
			});
			$('.due-total').text('$'+total_due.toFixed(2));
			$('.added-invoices').html(inv_list);
			calculate_balance();
		});
	});
	$(document).on('change', 'select[name="payment_type[]"]', function() { calculate_balance(); });
	function add_payment() {
		var group = $('.pay-group').last();
		var clone = group.clone();
		clone.find('input,select').val('');
		group.after(clone);
		resetChosen($('.chosen-select-deselect'));
	}
	function remove_payment(a) {
		if($('.pay-group').length <= 1) {
			add_payment();
		}
		$(a).closest('.pay-group').remove();
		calculate_balance();
	}
	function calculate_balance() {
		$('[name="payment_type[]"]').filter(function() { return this.value == ''; }).closest('.pay-group').find('[name="payment_amt[]"]').val(0);
		var payment_methods = $('[name="payment_type[]"]').filter(function() { return this.value != ''; });
		var amt_paid = 0;
		payment_methods.each(function() {
			amt_paid += +$(this).closest('.pay-group').find('[name="payment_amt[]"]').val();
		});
		amt_paid -= +payment_methods.last().closest('.pay-group').find('[name="payment_amt[]"]').val();
		payment_methods.last().closest('.pay-group').find('[name="payment_amt[]"]').val(total_due - amt_paid);
	}
	</script>
	<div class="main-screen full-width-screen form-horizontal"><form method="POST" action="">
		<input type="hidden" name="due_invoiceid[]" value="<?= $invoiceid ?>">
		<div style="background-color: #6DCFF6; padding: 1px 0;"><h3 style="color: #fff;"><?= (empty($current_tile_name) ? 'Check Out' : $current_tile_name) ?></h3></div>
		<div class="col-sm-6">
			<h3>Invoice #<?= $_GET['invoiceid'] ?></h3>
			<h4 <?= (in_array('invoice_date',$field_config) ? '' : 'style="display:none;"') ?>>Invoice Date: <label class="detail_invoice_date pull-right"><?= $invoice['invoice_date'] ?></label></h4>
			<h4 <?= (in_array('customer',$field_config) ? '' : 'style="display:none;"') ?>><?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?>: <label class="detail_patient_name pull-right"><?= get_contact($dbc, $invoice['patientid']) ?></label></h4>
			<h4 <?= (in_array('injury',$field_config) ? '' : 'style="display:none;"') ?>>Injury: <label class="detail_patient_injury pull-right"><?= $injury['injury_type'].': '.$injury['injury_name'].' ('.$injury['injury_date'].')' ?></label></h4>
			<h4 <?= (in_array('treatment',$field_config) ? '' : 'style="display:none;"') ?>>Treatment Plan: <label class="detail_patient_treatment pull-right"><?= $injury['treatment_plan'] ?></label></h4>
			<h4 <?= (in_array('staff',$field_config) ? '' : 'style="display:none;"') ?>>Staff: <label class="detail_staff_name pull-right"><?= get_contact($dbc, $invoice['therapistsid']) ?></label></h4>
			<?php if (in_array('services',$field_config)) { ?>
				<h4>Services</h4>
				<div class="service receipt"></div>
			<?php } ?>
			<?php if (in_array('inventory',$field_config)) { ?>
				<h4>Inventory</h4>
				<div class="inventory receipt"></div>
			<?php } ?>
			<?php if (in_array('packages',$field_config)) { ?>
				<h4>Packages</h4>
				<div class="package receipt"></div>
			<?php } ?>
			<?php if (in_array('products',$field_config)) { ?>
				<h4>Products</h4>
				<div class="product receipt"></div>
			<?php } ?>
			<?php if (in_array('misc_items',$field_config)) { ?>
				<h4>Miscellaneous Products</h4>
				<div class="misc_items receipt"></div>
			<?php } ?>
			<h4>Sub-Total: <label class="detail_sub_total_amt pull-right">$<?= number_format($invoice['total_price'],2) ?></label></h4>
			<?php $promotion = 0;
			$promotion_label = 'N/A';
			$promo = mysqli_query($dbc, "SELECT * FROM `promotion` WHERE `promotionid`='{$invoice['promotionid']}'");
			if($promo = mysqli_fetch_array($promo)) {
				$promotion = $promo['cost'];
				$promotion_label = $promo['heading'].' ($'.$promotion.')';
			} ?>
			<h4 <?= (in_array('promo',$field_config) ? '' : 'style="display:none;"') ?>>Promotion: <label class="detail_promo_amt pull-right"><?= $promotion_label ?></label></h4>
			<h4 <?= (in_array('delivery',$field_config) ? '' : 'style="display:none;"') ?>>Delivery: <label class="detail_shipping_amt pull-right">$<?= number_format($invoice['delivery'],2) ?></label></h4>
			<h4 <?= (in_array('delivery',$field_config) ? '' : 'style="display:none;"') ?>>Total before Tax: <label class="detail_mid_total_amt pull-right">$<?= number_format($invoice['total_price'] - $promotion + $invoice['delivery'],2) ?></label></h4>
			<h4>GST: <label class="detail_gst_amt pull-right">$<?= number_format($invoice['gst_amt'],2) ?></label></h4>
			<h4 <?= (in_array('tips',$field_config) ? '' : 'style="display:none;"') ?>>Gratuity: <label class="detail_gratuity_amt pull-right">$<?= number_format($invoice['gratuity'],2) ?></label></h4>
			<h4>Total: <label class="detail_total_amt pull-right">$<?= number_format($invoice['final_price'],2) ?></label></h4>
			<h4 style="<?= count($payer_categories) > 0 ? '' : 'display:none;' ?>"><?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?> Portion: <label class="detail_insurer_amt pull-right">$<?= number_format($due['customer_portion'],2) ?></label></h4>
			<?php $payments = mysqli_query($dbc, "SELECT `amount`, `payment_method`, `date_paid` FROM `invoice_payment` WHERE `invoiceid`='$invoiceid' AND `payer_id` = `contactid` AND `deleted`=0 AND `paid`=1");
			while($payment = mysqli_fetch_assoc($payments)) { ?>
				<h4>Payment by <?= $payment['payment_method'] ?> (<?= $payment['date_paid'] ?>): <label class="detail_total_amt pull-right">$<?= number_format($payment['amount'],2) ?></label></h4>
			<?php } ?>
		</div>
		<div class="col-sm-6">
			<h4>Payment<span class="pull-right due-total">$<?= number_format($due['due'],2) ?></span></h4>
			<span class="invoice-amt-details small pull-right">Invoice #<?= $_GET['invoiceid'] ?>: $<?= number_format($due['due'],2) ?><span class="added-invoices"></span></span>
			<ul class="chained-list">
				<div class="pay-group">
					<div class="form-group">
						<li><label class="col-sm-4">Payment Type:</label>
							<div class="col-sm-8 pull-right">
								<select class="chosen-select-deselect form-control" name="payment_type[]" data-placeholder="Select a Payment Type"><option></option>
									<?php foreach(explode(',',get_config($dbc, 'invoice_payment_types')) as $available_pay_method) { ?>
										<option value = '<?= $available_pay_method ?>'><?= $available_pay_method ?></option>
									<?php } ?>
								</select>
							</div>
						</li>
					</div>
					<div class="form-group">
						<li><label class="col-sm-4">Amount:</label>
							<div class="col-sm-8 pull-right"><input type="text" class="form-control" name="payment_amt[]" onchange="calculate_balance();"></div>
						</li>
					</div>
					<div class="form-group">
						<a href="" onclick="add_payment(); return false;" class="pull-right"><img class="inline-img" src="../img/icons/ROOK-add-icon.png"></a>
						<a href="" onclick="remove_payment(this); return false;" class="pull-right"><img class="inline-img" src="../img/remove.png"></a>
					</div>
				</div>
				<hr>
				<?php $outstanding = mysqli_query($dbc, "SELECT MIN(`invoice`.`invoiceid`) `invoice`, MIN(`invoice`.`invoice_date`) `date`, SUM(`invoice`.`final_price`) - SUM(IFNULL(`payments`.`total`,0)) `due`, SUM(`invoice`.`final_price`) - SUM(`payments`.`third_party`) `customer_portion` FROM `invoice` LEFT JOIN (SELECT `invoiceid`, SUM(`amount`) `total`, SUM(IF(`payer_id`=`contactid`,0,`amount`)) `third_party` FROM `invoice_payment` WHERE `deleted`=0 AND `paid`=1 GROUP BY `invoiceid`) `payments` ON `invoice`.`invoiceid`=`payments`.`invoiceid` AND `invoice`.`deleted`=0 WHERE '$invoiceid' NOT IN (`invoice`.`invoiceid`,`invoice`.`invoiceid_src`) AND `patientid`='{$invoice['patientid']}' GROUP BY IF(`invoice`.`invoiceid_src` > 0,`invoice`.`invoiceid_src`,`invoice`.`invoiceid`) HAVING `due` != 0");
				if(mysqli_num_rows($outstanding) > 0) { ?>
					<h4>Outstanding Invoices</h4>
					<?php while($outstanding_invoice = mysqli_fetch_assoc($outstanding)) { ?>
						<label class="form-checkbox">
							<input type="checkbox" name="due_invoiceid[]" data-due="<?= $outstanding_invoice['due'] ?>" value="<?= $outstanding_invoice['invoice'] ?>">
							Invoice #<?= $outstanding_invoice['invoice'] ?> <span class="blue-text">$<?= number_format($outstanding_invoice['due'],2) ?></span>
						</label>
					<?php }
				} ?>
			</ul>
		</div>
		<button class="btn brand-btn pull-right" name="submit" value="pay">Submit Payment</button>
		<a href="" class="btn brand-btn pull-right">Cancel</a>
		<div class="clearfix" style="margin-bottom: 1em;"></div>
	</form></div>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>