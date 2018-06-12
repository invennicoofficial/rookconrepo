<?php
/* ----- Email Receipt Dashboard ----- */
if ( $email_reciept===TRUE ) {
	$posid		= $_GET['posid'];
	$customerid	= $_GET['customerid'];
	$attachment	= 'download/invoice_' . $posid . '.pdf'; ?>
	
	<input type="hidden" name="h_posid" id="h_posid" value="<?php echo $posid; ?>" />
	<input type="hidden" name="h_custid" id="h_custid" value="<?php echo $customerid; ?>" />
	<input type="hidden" name="h_attachment" id="h_attachment" value="<?php echo $attachment; ?>" />
	
	<div class="col-sm-12 double-gap-top"><?php
		if ( empty($customerid) ) { ?>
			<button id="email_select_customer" class="btn brand-btn btn-lg mobile-block" onclick="emailReceiptSelection(this);">SELECT CUSTOMER</button>
			<button id="email_create_customer" class="btn brand-btn btn-lg mobile-block" onclick="emailReceiptSelection(this);">ADD NEW CUSTOMER</button><?php
		} else { ?>
			<button id="email_selected_button" class="btn brand-btn btn-lg mobile-block" onclick="emailReceipt(this);">EMAIL RECEIPT</button><?php
		} ?>
		<button id="email_only" class="btn brand-btn btn-lg mobile-block" onclick="emailReceiptSelection(this);">ENTER EMAIL</button>
		<button id="email_dont" class="btn brand-btn btn-lg mobile-block" onclick="emailReceiptSelection(this);">DON'T EMAIL</button>
	</div><div class="clearfix gap-bottom"></div><?php

	if ( empty($customerid) ) { ?>
		<!-- Select Customer -->
		<div id="email_select_customer_block" class="col-sm-12">
			<h3 class="col-sm-12">Select An Existing Customer</h3>
			<div class="col-sm-12 gap-top double-gap-bottom">
				<select id="customerid" name="contactid" data-placeholder="Select Customer..." class="chosen-select-deselect form-control" width="380">
					<option value=""></option><?php
					$result = mysqli_query($dbc, "SELECT contactid, name FROM contacts WHERE (category NOT IN (".STAFF_CATS.") AND category != 'Employee') AND deleted=0 ORDER BY IF(name RLIKE '^[a-z]', 1, 2), name");
					while ( $row=mysqli_fetch_assoc($result) ) {
						if ( $contactid == $row[ 'contactid' ] ) {
							$selected = 'selected="selected"';
						} else {
							$selected = '';
						}
						echo "<option " . $selected . " value=" . $row['contactid'] . ">" . decryptIt($row['name']) . "</option>";
					} ?>
				</select>
			</div>
			<div class="col-sm-12"><button id="email_existing_button" class="btn brand-btn btn-lg mobile-block pull-right" onclick="emailReceipt(this);">EMAIL RECEIPT</button></div>
		</div>
	
		<!-- Create A New Customer -->
		<div id="email_create_customer_block" class="col-sm-12">
			<h3 class="col-sm-12">Create A New Customer</h3>
			<div class="col-sm-12 gap-top double-gap-bottom">
				<form id="create_new" action="" method="post" style="padding:0;">
					<div class="form-group new_client">
						<label for="travel_task" id="customer_name" class="col-sm-3 control-label">Customer Name:</label>
						<div class="col-sm-9"><input name="customer_name" id="customer_name1" type="text" class="form-control" /></div>
					</div>
					<div class="form-group new_client">
						<label for="site_name" id="cusphone1" class="col-sm-3 control-label">Customer Phone:</label>
						<div class="col-sm-9"><input name="cusphone" id="cusphone" type="text" class="form-control" /></div>
					</div>
					<div class="form-group new_client">
						<label for="site_name" id="cusemail1" class="col-sm-3 control-label">Customer Email:</label>
						<div class="col-sm-9"><input name="email" id="cusemail" type="text" class="form-control" /></div>
					</div>
					<div class="form-group new_client double-gap-bottom">
						<label for="site_name" id="cusref1" class="col-sm-3 control-label">How Did They Hear About Us?</label>
						<div class="col-sm-9"><input name="reference" id="cusref" type="text" class="form-control" /></div>
					</div>
					<div class="col-sm-12"><button type="submit" name="submit_new_cust" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button></div>
				</form>
			</div>
		</div><?php
	
	} ?>

	<!-- Email Only -->
	<div id="email_only_block" class="col-sm-12">
		<h3 class="col-sm-12">Email Reciept Without Customer Details</h3>
		<div class="col-sm-12 gap-top double-gap-bottom"><input type="email" name="to_email" id="to_email" class="form-control" /></div>
		<div class="col-sm-12"><button id="email_only_button" class="btn brand-btn btn-lg mobile-block pull-right" onclick="emailReceipt(this);">EMAIL RECEIPT</button></div>
	</div><?php
	
	$email_reciept = FALSE;
}
?>