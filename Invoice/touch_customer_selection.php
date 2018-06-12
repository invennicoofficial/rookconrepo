<?php
/* ----- Customer Selection Dashboard ----- */
if ( $customer===TRUE ) {
	$customer_categories = get_config($dbc, 'invoice_purchase_contact');
	if($customer_categories == '') {
		$customer_categories = '%';
	}
	$customer_categories = explode(',',$customer_categories); ?>
	<script type="text/javascript">		
	$(document).on('change', 'select[name="contactid"]', function() { customerSelected(); });
	</script>
	
	<div class="col-sm-12 double-gap-top">
		<button id="select_customer" class="btn brand-btn btn-lg mobile-block" onclick="customerSelection(this);">SELECT <?= count($customer_categories) == 1 && $customer_categories[0] != '%' ? strtoupper($customer_categories[0]) : 'CUSTOMER' ?></button>
		<button id="create_customer" class="btn brand-btn btn-lg mobile-block" onclick="customerSelection(this);">ADD NEW <?= count($customer_categories) == 1 && $customer_categories[0] != '%' ? strtoupper($customer_categories[0]) : 'CUSTOMER' ?></button>
	</div>
	
	<div class="clearfix gap-bottom"></div>

	<!-- Select Customer -->
	<div id="select_customer_block" class="col-sm-12">
		<h3 class="col-sm-12">Select An Existing <?= count($customer_categories) == 1 && $customer_categories[0] != '%' ? $customer_categories[0] : 'Customer' ?></h3>
		<div class="col-sm-12 gap-top double-gap-bottom">
			<select id="customerid" name="contactid" data-placeholder="Select <?= count($customer_categories) == 1 && $customer_categories[0] != '%' ? $customer_categories[0] : 'Customer' ?>..." class="chosen-select-deselect form-control" width="380">
				<option value=""></option><?php foreach($customer_categories as $cust_cat) {
					$result = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `category` LIKE '$cust_cat' AND `deleted`=0 AND `status`>0"));
					foreach($result as $row) {
						echo "<option " . ( $contactid == $row[ 'contactid' ]  ? 'selected' : '') . " value=" . $row['contactid'] . ">" . $row['first_name'].' '.$row['last_name'] ."</option>";
					}
				} ?>
			</select>
		</div>
	</div>

	<!-- Create A New Customer -->
	<div id="create_customer_block" class="col-sm-12">
		<h3 class="col-sm-12">Create A New <?= count($customer_categories) == 1 && $customer_categories[0] != '%' ? $customer_categories[0] : 'Customer' ?></h3>
		<div class="col-sm-12 gap-top double-gap-bottom">
			<form id="create_new" action="" method="post" style="padding:0;">
				<div class="form-group new_client">
					<label for="cust_first" id="cust_first" class="col-sm-3 control-label">First Name:</label>
					<div class="col-sm-9"><input name="cust_first" id="customer_name1" type="text" class="form-control" /></div>
				</div>
				<div class="form-group new_client">
					<label for="cust_last" id="cust_last" class="col-sm-3 control-label">Last Name:</label>
					<div class="col-sm-9"><input name="cust_last" id="customer_name1" type="text" class="form-control" /></div>
				</div>
				<div class="form-group new_client">
					<label for="site_name" id="cusphone1" class="col-sm-3 control-label">Phone Number:</label>
					<div class="col-sm-9"><input name="cusphone" id="cusphone" type="text" class="form-control" /></div>
				</div>
				<div class="form-group new_client">
					<label for="site_name" id="cusemail1" class="col-sm-3 control-label">Email Address:</label>
					<div class="col-sm-9"><input name="email" id="cusemail" type="text" class="form-control" /></div>
				</div>
				<div class="form-group new_client double-gap-bottom">
					<label for="site_name" id="cusref1" class="col-sm-3 control-label">How Did They Hear About Us?</label>
					<div class="col-sm-9"><input name="reference" id="cusref" type="text" class="form-control" /></div>
				</div>
				<div class="col-sm-12"><button type="submit" name="add_new_cust" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button></div>
			</form>
		</div>
	</div><?php
	
	$customer = FALSE;
}
?>