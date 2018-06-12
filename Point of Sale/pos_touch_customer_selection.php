<?php
/* ----- Customer Selection Dashboard ----- */
if ( $customer===TRUE ) { ?>
	<script type="text/javascript">
	$(document).on('change', 'select[name="contactid"]', function() { customerSelected(); });
	</script>

	<div class="col-sm-12 double-gap-top">
		<button id="select_customer" class="btn brand-btn btn-lg mobile-block" onclick="customerSelection(this);">SELECT CUSTOMER</button>
		<button id="create_customer" class="btn brand-btn btn-lg mobile-block" onclick="customerSelection(this);">ADD NEW CUSTOMER</button>
	</div>
	
	<div class="clearfix gap-bottom"></div>

	<!-- Select Customer -->
	<div id="select_customer_block" class="col-sm-12">
		<h3 class="col-sm-12">Select An Existing Customer</h3>
		<div class="col-sm-12 gap-top double-gap-bottom">
			<select id="customerid" name="contactid" data-placeholder="Select Customer..." class="chosen-select-deselect form-control" width="380">
				<option value=""></option><?php
				$result = mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE (`category` NOT IN (".STAFF_CATS.") AND `category`!='Employee') AND `deleted`=0");
				while ( $row=mysqli_fetch_assoc($result) ) {
					if ( $contactid == $row[ 'contactid' ] ) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					echo "<option " . $selected . " value=" . $row['contactid'] . ">" . ( !empty($row['first_name']) ? decryptIt($row['first_name']) : '' ) .' '. ( !empty($row['last_name']) ? decryptIt($row['last_name']) : '' ) ."</option>";
				} ?>
			</select>
		</div>
	</div>

	<!-- Create A New Customer -->
	<div id="create_customer_block" class="col-sm-12">
		<h3 class="col-sm-12">Create A New Customer</h3>
		<div class="col-sm-12 gap-top double-gap-bottom">
			<form id="create_new" action="" method="post" style="padding:0;">
				<div class="form-group new_client">
					<label for="customer_name" id="customer_name" class="col-sm-3 control-label">Customer Name:</label>
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
				<div class="col-sm-12"><button type="submit" name="add_new_cust" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button></div>
			</form>
		</div>
	</div><?php
	
	$customer = FALSE;
}
?>