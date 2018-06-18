<?php include_once('../include.php'); ?>
<script type="text/javascript">
$(document).on('change','select[name="vendorid"]',function() { filterOrderForms(); });
$(document).on('change','select[name="vpl_name"]',function() { loadOrderForm(); });
function filterOrderForms() {
	var vendorid = $('[name="vendorid"]').val();
	if(vendorid != '') {
		$('.order_form').show();
		$('[name="order_forms"] option').hide();
		$('[name="order_forms"] option[data-vendorid='+vendorid+']').show();
		$('[name="order_forms"]').trigger('change.select2');
	} else {
		$('.order_form').hide();
	}
}
function loadOrderForm() {
	$('.order_form_details').html('Loading...').show();
	var vendorid = $('[name="vendorid"]').val();
	var vpl_name = $('[name="vpl_name"]').val();
	$.ajax({
		url: '../Vendor Price List/order_form.php?from_tile=estimates&vendorid='+vendorid+'&vpl_name='+vpl_name,
		method: 'GET',
		dataType: 'html',
		success:function(response) {
			$('.order_form_details').html(response).show();
		}
	});
}
</script>
<div class="form-group">
	<label class="col-sm-4">Vendor:</label>
	<div class="col-sm-8">
		<select name="vendorid" data-placeholder="Select a Vendor..." class="chosen-select-deselect form-control">
			<option></option>
			<?php $vendor_list = sort_contacts_query(mysqli_query($dbc, "SELECT `contacts`.`first_name`, `contacts`.`last_name`, `contacts`.`name`, `contacts`.`contactid` FROM `vendor_price_list` LEFT JOIN `contacts` ON `vendor_price_list`.`vendorid` = `contacts`.`contactid` AND `contacts`.`deleted` = 0 WHERE `vendor_price_list`.`deleted` = 0 AND IFNULL(`vpl_name`,'') != '' GROUP BY `vendor_price_list`.`vendorid`"));
			foreach($vendor_list as $vendor) {
				echo '<option value="'.$vendor['contactid'].'">'.$vendor['full_name'].'</option>';
			} ?>
		</select>
	</div>
</div>
<div class="form-group order_form" style="display:none;">
	<label class="col-sm-4">Order Form:</label>
	<div class="col-sm-8">
		<select name="vpl_name" data-placeholder="Select an Order Form..." class="chosen-select-deselect form-control">
			<option></option>
			<?php $order_forms = mysqli_query($dbc, "SELECT `vpl_name`, `vendorid` FROM `vendor_price_list` WHERE `deleted` = 0 AND IFNULL(`vpl_name`,'') != '' AND `vendorid` > 0 GROUP BY CONCAT(`vendorid`,`vpl_name`) ORDER BY `vpl_name`");
			while($order_form = mysqli_fetch_assoc($order_forms)) {
				echo '<option data-vendorid="'.$order_form['vendorid'].'" value="'.$order_form['vpl_name'].'">'.$order_form['vpl_name'].'</option>';
			} ?>
		</select>
	</div>
</div>
<div class="form-group order_form_details" style="display:none;">
</div>