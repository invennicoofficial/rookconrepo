<?php include_once('../include.php');
if(FOLDER_NAME == 'posadvanced') {
    checkAuthorised('posadvanced');
} else {
    checkAuthorised('check_out');
}
error_reporting(0);
$invoice_purchase = FOLDER_NAME.'_purchase_contact';
$invoice_payer = FOLDER_NAME.'_payer_contact'; ?>
<script>
$(document).ready(function() {
	$('input').change(saveCategories);
});
function saveCategories() {
	var purchasers = [];
	$('[name="<?= $invoice_purchase ?>[]"]:checked').each(function() {
		purchasers.push(this.value);
	});
	$.ajax({
		url: '../Invoice/invoice_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= $invoice_purchase ?>',
			value: purchasers
		}
	});
	var payers = [];
	$('[name="<?= $invoice_payer ?>[]"]:checked').each(function() {
		payers.push(this.value);
	});
	$.ajax({
		url: '../Invoice/invoice_ajax.php?action=general_config',
		method: 'POST',
		data: {
			name: '<?= $invoice_payer ?>',
			value: payers
		}
	});
}
</script>
<div class="block-group form-horizontal">
	<?php $category_list = explode(',',get_config($dbc, 'contacts_tabs')); ?>
	<h3>Purchasing Contact Categories</h3>
	<?php $invoice_purchase_contact = explode(',',get_config($dbc, $invoice_purchase));
	foreach($category_list as $contact_category) { ?>
		<label class="form-checkbox"><input <?= (in_array($contact_category,$invoice_purchase_contact) ? 'checked' : '') ?> type="checkbox" name="<?= $invoice_purchase ?>[]" value="<?= $contact_category ?>"> <?= $contact_category ?></label>
	<?php } ?>
	<h3>Third Party Payer Categories</h3>
	<?php $invoice_payer_contact = explode(',',get_config($dbc, $invoice_payer));
	foreach($category_list as $contact_category) { ?>
		<label class="form-checkbox"><input <?= (in_array($contact_category,$invoice_payer_contact) ? 'checked' : '') ?> type="checkbox" name="<?= $invoice_payer ?>[]" value="<?= $contact_category ?>"> <?= $contact_category ?></label>
	<?php } ?>
</div>
<?php if(basename($_SERVER['SCRIPT_FILENAME']) == 'field_config_contacts.php') { ?>
	<div style="display:none;"><?php include('../footer.php'); ?></div>
<?php } ?>