<script type="text/javascript">
$(document).on('change', 'select.service_cat_onchange', function() { filterServices(); });
$(document).on('change', 'select.inventory_cat_onchange', function() { filterInventory(); });
$(document).on('change', 'select[name="part_no"]', function() { $(this).closest('.line-group').find('[name=item_id]').val(this.value).trigger('change.select2').change(); });
$(document).on('change', 'select.package_cat_onchange', function() { filterPackages(); });
$(document).on('change', 'select.product_cat_onchange', function() { filterProducts(); });
</script>
<!-- Details -->
<div data-tab-name='details' data-locked='' class="form-horizontal">
	<h4>Details</h4>
	<?php if(count($purchaser_categories) > 1) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Category:</label>
			<div class="col-sm-8">
				<select name="category" class="form-control chosen-select-deselect"><option></option>
					<?php foreach ($purchaser_categories as $cat_tab) {
						echo "<option ".($contact['category'] == $cat_tab ? 'selected' : '')." value='". $cat_tab."'>".$cat_tab.'</option>';
					}
					if(!in_array($contact['category'], $purchaser_categories)) {
						echo "<option selected value='". $contact['category']."'>".$contact['category'].'</option>';
					} ?>
				</select>
			</div>
		</div>
	<?php } ?>
	<div class="form-group">
		<label class="col-sm-4 control-label"><?= count($purchaser_categories) > 1 ? 'Contact' : $purchaser_categories[0] ?>:</label>
		<div class="col-sm-8">
			<select name="patientid" data-field="patientid" data-table="invoice" class="form-control chosen-select-deselect"><option></option>
			<?php $contact_categories = "'".implode("','", $purchaser_categories)."'";
				$each_contact = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN ($contact_categories) AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
				foreach ($each_contact as $id) {
					echo "<option ".(get_contact($dbc, $id, 'category') != $contact['category'] ? 'style="display:none;"' : '')." data-category='".get_contact($dbc, $id, 'category')."' ".($contact['contactid'] == $id ? 'selected' : '')." value='".$id."'>".get_contact($dbc, $id)."</option>";
				}
				if(!in_array($contact['contactid'], $each_contact)) {
					echo "<option selected data-category='".$contact['category']."' value='". $contact['contactid']."'>".$contact['contactid'].'</option>';
				}
			?>
			</select>
		</div>
	</div>
	<?php if (in_array('injury',$field_config)) { ?>
	<div class="form-group">
		<label class="col-sm-4 control-label">Injury:</label>
		<div class="col-sm-8">
			<select name="injuryid" data-field="injuryid" data-table="invoice" class="form-control chosen-select-deselect"><option></option>
			<?php $each_injury = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `patient_injury` WHERE `contactid` = '".$contact['contactid']."' AND discharge_date IS NULL AND deleted = 0"),MYSQLI_ASSOC);
				foreach ($each_injury as $this_injury) {
					$total_injury = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(`bookingid`) as total_injury FROM `booking` WHERE `injuryid` = '".$this_injury['injuryid']."'"));

					$treatment_plan = get_all_from_injury($dbc, $this_injury['injuryid'], 'treatment_plan');
					$final_treatment_done = '';
					if ($treatment_plan != '') {
						$final_treatment_done = ' : '.($total_injury['total_injury']+1).'/'.$treatment_plan;
					}

					echo "<option ".($invoice['injuryid'] == $this_injury['injuryid'] ? 'selected' : '')." value='".$this_injury['injuryid']."'>".$this_injury['injury_type'].' : '.$this_injury['injury_name'].' : '.$this_injury['injury_date'].$final_treatment_done."</option>";
				}
			?>
			</select>
		</div>
	</div>
	<?php } ?>
	<?php if (in_array('treatment',$field_config)) { ?>
	<div class="form-group">
		<label class="col-sm-4 control-label">Treatment Plan:</label>
		<div class="col-sm-8">
			<select name="treatment_plan" data-field="treatment_plan" data-table="patient_injury" class="form-control chosen-select-deselect"><option></option>
				<option <?php if ($injury['treatment_plan'] == "3") { echo " selected"; } ?> value = '3'>3</option>
				<option <?php if ($injury['treatment_plan'] == "4") { echo " selected"; } ?> value = '4'>4</option>
				<option <?php if ($injury['treatment_plan'] == "5") { echo " selected"; } ?> value = '5'>5</option>
				<option <?php if ($injury['treatment_plan'] == "6") { echo " selected"; } ?> value = '6'>6</option>
				<option <?php if ($injury['treatment_plan'] == "7") { echo " selected"; } ?> value = '7'>7</option>
				<option <?php if ($injury['treatment_plan'] == "12") { echo " selected"; } ?>  value = '12'>12</option>
				<option <?php if ($injury['treatment_plan'] == "21") { echo " selected"; } ?> value = '21'>21</option>
			</select>
		</div>
	</div>
	<?php } ?>
	<?php if (in_array('staff',$field_config)) { ?>
	<div class="form-group">
		<label class="col-sm-4 control-label">Staff:</label>
		<div class="col-sm-8">
			<select name="therapistsid" data-field="therapistsid" data-table="invoice" class="form-control chosen-select-deselect"><option></option>
			<?php $each_staff = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
			foreach ($each_staff as $id) {
				echo "<option ".($invoice['therapistsid'] == $id ? 'selected' : '')." value='".$id."'>".get_contact($dbc, $id)."</option>";
			}
			?>
			</select>
		</div>
	</div>
	<?php } ?>
	<?php if (in_array('appt_type',$field_config)) { ?>
	<div class="form-group">
		<label class="col-sm-4 control-label">Appointment Type:</label>
		<div class="col-sm-8">
			<select name="app_type" data-field="type" data-table="booking" class="form-control chosen-select-deselect"><option></option>
	            <?php $appointment_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `appointment_type` WHERE `deleted` = 0"),MYSQLI_ASSOC);
	            foreach ($appointment_types as $appointment_type) {
	                echo '<option '.($type == $appointment_type['id'] ? 'selected' : '').' value="'.$appointment_type['id'].'">'.$appointment_type['name'].'</option>';
	            } ?>
			</select>
		</div>
	</div>
	<?php } ?>
	<?php if (in_array('service_date',$field_config)) { ?>
	<div class="form-group">
		<label class="col-sm-4 control-label">Service Date:</label>
		<div class="col-sm-8">
			<input type="text" name="service_date" data-field="service_date" data-table="invoice" class="form-control datepicker" value="<?= $invoice['service_date'] ?>">
		</div>
	</div>
	<?php } ?>
	<?php if (in_array('pricing',$field_config)) { ?>
		<div class="form-group">
			<label class="col-sm-4 control-label">Product Pricing:</label>
			<div class="col-sm-8">
				<select name="pricing" data-placeholder="Select Pricing" data-field="therapistsid" data-table="invoice" class="chosen-select-deselect"><option></option>
					<?php if(in_array('price_client', $field_config)) { ?><option <?= ($pricing == 'client_price' ? 'selected' : '') ?> value="client_price">Client Price</option><?php } ?>
					<?php if(in_array('price_admin', $field_config)) { ?><option <?= ($pricing == 'admin_price' ? 'selected' : '') ?> value="admin_price">Admin Price</option><?php } ?>
					<?php if(in_array('price_commercial', $field_config)) { ?><option <?= ($pricing == 'commercial_price' ? 'selected' : '') ?> value="commercial_price">Commercial Price</option><?php } ?>
					<?php if(in_array('price_wholesale', $field_config)) { ?><option <?= ($pricing == 'wholesale_price' ? 'selected' : '') ?> value="wholesale_price">Wholesale Price</option><?php } ?>
					<?php if(in_array('price_retail', $field_config)) { ?><option <?= ($pricing == 'final_retail_price' || $pricing == '' ? 'selected' : '') ?> value="final_retail_price">Final Retail Price</option><?php } ?>
					<?php if(in_array('price_preferred', $field_config)) { ?><option <?= ($pricing == 'preferred_price' ? 'selected' : '') ?> value="preferred_price">Preferred Price</option><?php } ?>
					<?php if(in_array('price_po', $field_config)) { ?><option <?= ($pricing == 'purchase_order_price' ? 'selected' : '') ?> value="purchase_order_price">Purchase Order Price</option><?php } ?>
					<?php if(in_array('price_sales', $field_config)) { ?><option <?= ($pricing == 'sales_order_price' ? 'selected' : '') ?> value="sales_order_price"><?= SALES_ORDER_NOUN ?> Price</option><?php } ?>
					<?php if(in_array('price_web', $field_config)) { ?><option <?= ($pricing == 'web_price' ? 'selected' : '') ?> value="web_price">Web Price</option><?php } ?>
				</select>
			</div>
		</div>
	<?php } else { ?>
		<input type="hidden" name="pricing" value="final_retail_price">
	<?php } ?>
	<?php if (in_array('pay_mode',$field_config)) { ?>
	<div class="form-group">
		<label class="col-sm-4 control-label">Payment Method:</label>
		<div class="col-sm-8">
			<select name="paid" data-field="paid" data-table="invoice" class="form-control chosen-select-deselect"><option></option>
			<option <?php if ($invoice['paid']=='Yes') echo 'selected="selected"';?>  value="Yes"><?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?> Invoice : <?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?> is paying full amount on checkout.</option>
			<option <?php if ($invoice['paid']=='Waiting on Insurer') echo 'selected="selected"';?> value="Waiting on Insurer">Waiting on <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?> : Clinic is waiting on <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?> to pay full amount.</option>
			<option <?php if ($invoice['paid']=='No') echo 'selected="selected"';?>  value="No">Partially Paid : The invoice is being paid partially by <?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?> and partially by <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>.</option>
			<option <?php if ($invoice['paid']=='On Account') echo 'selected="selected"';?> value="On Account">A/R On Account : <?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?> will pay invoice in future. Must choose Payment Type as Apply A/R to Account.</option>
			<option <?php if ($invoice['paid']=='Credit On Account') echo 'selected="selected"';?> value="Credit On Account">Credit On Account : <?= count($purchaser_categories) > 1 ? 'Customer' : $purchaser_categories[0] ?> is appyling credit to profile.</option>
			</select>
		</div>
	</div>
	<?php } ?>
</div>

<!-- Services -->
<?php if(in_array('services',$field_config)) { ?>
	<div data-tab-name='services' data-locked='' class="form-horizontal">
		<h4>Services</h4>
		<?php $services = mysqli_query($dbc, "SELECT `category`, `item_id`, `type`, `description`, `heading`, `compensation`, SUM(`quantity`) quantity, `unit_price`, `admin_fee`, `uom`, `tax_exempt`, SUM(`sub_total`) sub_total, SUM(`pst`) pst, SUM(`gst`) gst, SUM(`total`) total FROM `invoice_lines` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `deleted`=0 AND `category`='service' GROUP BY `category`, `item_id`, `type`, `description`, `heading`, `compensation`, `unit_price`, `admin_fee`, `uom`, `tax_exempt`");
		$service = mysqli_fetch_array($services);
		do {
			$service_info = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `serviceid`, `category`, `appointment_type`, `heading` FROM `services` WHERE `serviceid`='{$service['item_id']}'")); ?>
			<div class="line-group service">
				<?php if (in_array('service_cat',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Service Category:</label>
						<div class="col-sm-8">
							<select class="form-control chosen-select-deselect service_cat_onchange" name="line_category"><option></option>
							<?php $query = mysqli_query($dbc,"SELECT category, GROUP_CONCAT(DISTINCT(appointment_type)) appointment_type FROM services WHERE deleted=0 AND (appointment_type='' OR appointment_type='$app_type' OR '$app_type'='') GROUP BY `category`");
							while($row = mysqli_fetch_array($query)) {
								echo "<option data-appt-type=',".$row['appointment_type'].",' ".($row['category'] == $service_info['category'] ? 'selected' : '')." value='". $row['category']."'>".$row['category'].'</option>';
							} ?>
							</select>
						</div>
					</div>
				<?php } ?>
				<?php if (in_array('service_head',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Service Name:</label>
						<div class="col-sm-8">
							<select name="item_id" data-category="service" data-field="item_id" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" class="chosen-select-deselect form-control">
								<option value=""></option>
								<?php $query = mysqli_query($dbc,"SELECT s.serviceid, s.heading, r.cust_price, r.admin_fee, s.gst_exempt, s.appointment_type, s.category FROM services s LEFT JOIN company_rate_card r ON s.serviceid=r.item_id AND r.tile_name LIKE 'Services' WHERE (s.appointment_type = '' OR s.appointment_type='".$app_type."' OR '$app_type'='') AND '".($invoice['invoice_date'] != '' ? $invoice['invoice_date'] : date('Y-m-d'))."' >= r.start_date AND ('".($invoice['invoice_date'] != '' ? $invoice['invoice_date'] : date('Y-m-d'))."' <= r.end_date OR IFNULL(r.end_date,'0000-00-00') = '0000-00-00')");
								while($row = mysqli_fetch_array($query)) {
									echo "<option data-appt-type=',".$row['appointment_type'].",' data-fee='".$row['cust_price']."' data-category='".$row['category']."' data-admin='".$row['admin_fee']."' data-gst-exempt='".$row['gst_exempt']."' ".($service['item_id'] == $row['serviceid'] ? 'selected' : '')." value='". $row['serviceid']."'>".$row['heading'].'</option>';
								} ?>
							</select>
						</div>
					</div>
				<?php } ?>
				<?php if (in_array('service_price',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Fee:</label>
						<div class="col-sm-8">
							<input name="unit_price" data-field="unit_price" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" readonly type="number" step="any" value="<?= $service['unit_price'] ?>" class="form-control fee" />
							<input name="tax_exempt" data-field="tax_exempt" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" type="hidden" value="<?= $service['tax_exempt'] ?>" />
							<input name="quantity" data-field="quantity" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" type="hidden" value="1" />
							<input name="sub_total" data-field="sub_total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" type="hidden" value="<?= $service['sub_total'] ?>" />
							<input name="gst" data-field="gst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" type="hidden" value="<?= $service['gst'] ?>" />
							<input name="pst" data-field="pst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" type="hidden" value="<?= $service['pst'] ?>" />
							<input name="total" data-field="total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" type="hidden" value="<?= $service['total'] ?>" />
							<input name="admin_fee" data-field="admin_fee" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $service['line_id'] ?>" type="hidden" value="<?= $service['admin_fee'] ?>" />
						</div>
					</div>
				<?php } ?>
				<?php if(count($payer_categories) > 0) {
					$third_party_payments = mysqli_query($dbc, "SELECT * FROM `invoice_payment` LEFT JOIN `contacts` ON `invoice_payment`.`payer_id`=`contacts`.`contactid` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) `line_id`='".$service['line_id']."' AND `invoice_payment`.`payer_id` != `invoice_payment`.`contactid` AND `invoice_payment`.`deleted`=0");
					$payment = mysqli_fetch_array($third_party_payments);
					do { ?>
						<div class="payment-line">
							<?php if(count($payer_categories) > 1) { ?>
								<div class="form-group">
									<label class="col-sm-4 control-label">Third Party Category:</label>
									<div class="col-sm-8">
										<select name="third_category" class="form-control chosen-select-deselect"><option></option>
											<?php foreach ($payer_categories as $cat_tab) {
												echo "<option ".($payment['category'] == $cat_tab ? 'selected' : '')." value='". $cat_tab."'>".$cat_tab.'</option>';
											}
											if(!in_array($payment['category'], $payer_categories)) {
												echo "<option selected value='". $payment['category']."'>".$payment['category'].'</option>';
											} ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<select name="payer_id" data-field="payer_id" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $service['line_id'] ?>" class="form-control chosen-select-deselect">
										<option></option>
										<?php $contact_categories = "'".implode("','", $payer_categories)."'";
										$contact_found = false;
										$each_contact = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE `category` IN ($contact_categories) AND `deleted` = 0 AND `status` > 0"));
										foreach ($each_contact as $payer) {
											echo "<option ".($payer['category'] != $payment['category'] && $payment['category'] != '' ? 'style="display:none;"' : '')." data-category='".$payer['category']."' ".($payment['payer_id'] == $payer['contactid'] ? 'selected' : '')." value='".$payer['contactid']."'>".$payer['name']."</option>";
											$contact_found = ($contact_found || $payment['payer_id'] == $payer['contactid']);
										}
										if(!$contact_found) {
											echo "<option selected data-category='".$payment['category']."' value='". $payment['payer_id']."'>".get_client($dbc, $payment['payer_id']).'</option>';
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Paid by <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<input type="number" step="0.01" name="amount" onchange="$(this).data('manual',1);" data-manual="<?= $payment['payer_id'] > 0 && $payment['amount'] != $service['total'] ? 1 : 0 ?>" data-field="amount" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $service['line_id'] ?>" value="<?= $payment['amount'] ?>" class="form-control">
								</div>
							</div>
						</div>
					<?php } while($payment = mysqli_fetch_array($third_party_payments));
				} ?>
				<div class="form-group">
					<img src="<?= WEBSITE_URL ?>/img/plus.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" onclick="addRow('service');">
					<img src="<?= WEBSITE_URL ?>/img/remove.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" data-table="invoice_lines" data-field="deleted" data-id="<?= $service['line_id'] ?>" data-id-field="line_id" data-value="1" data-category="service" onclick="remLine(this);">
				</div>
			</div>
		<?php } while($service = mysqli_fetch_array($services)); ?>
	</div>
<?php } ?>

<!-- Inventory -->
<?php if(in_array('inventory',$field_config)) { ?>
	<div data-tab-name='inventory' data-locked='' class="form-horizontal">
		<h4>Inventory</h4>
		<?php $inventory_lines = mysqli_query($dbc, "SELECT `category`, `item_id`, `type`, `description`, `heading`, `compensation`, SUM(`quantity`) quantity, `unit_price`, `admin_fee`, `uom`, `tax_exempt`, SUM(`sub_total`) sub_total, SUM(`pst`) pst, SUM(`gst`) gst, SUM(`total`) total FROM `invoice_lines` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `deleted`=0 AND `category`='inventory' GROUP BY `category`, `item_id`, `type`, `description`, `heading`, `compensation`, `unit_price`, `admin_fee`, `uom`, `tax_exempt`");
		$inventory = mysqli_fetch_array($inventory_lines);
		do {
			$inv_info = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `category`, `part_no`, `client_price`, `admin_price`, `commercial_price`, `wholesale_price`, `final_retail_price`, `preferred_price`, `purchase_order_price`, `sales_order_price`, `web_price`, `wcb_price`, `gst_exempt` FROM `inventory` WHERE `inventoryid`='{$inventory['item_id']}'")); ?>
			<div class="line-group inventory">
				<?php if (in_array('inventory_cat',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Inventory Category:</label>
						<div class="col-sm-8">
							<select name="line_category" class="chosen-select-deselect form-control inventory_cat_onchange">
								<option value=""></option>
								<?php $query = mysqli_query($dbc,"SELECT `category` FROM inventory WHERE deleted=0 GROUP BY `category` ORDER BY `category`");
								while($row = mysqli_fetch_array($query)) {
									echo "<option ".($row['category'] == $inv_info['category'] ? 'selected' : '')." value='". $row['category']."'>".$row['category'].'</option>';
								} ?>
							</select>
						</div>
					</div>
				<?php } ?>
				<?php if (in_array('inventory_part',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Inventory Part #:</label>
						<div class="col-sm-8">
							<select name="part_no" class="chosen-select-deselect form-control">
								<option value=""></option>
								<?php $query = mysqli_query($dbc,"SELECT `category`, `part_no`, `inventory_id` FROM inventory WHERE deleted=0 ORDER BY `part_no`");
								while($row = mysqli_fetch_array($query)) {
									echo "<option data-category='".$row['category']."' ".($row['inventoryid'] == $inventory['item_id'] ? 'selected' : '')." value='". $row['inventoryid']."'>".$row['part_no'].'</option>';
								} ?>
							</select>
						</div>
					</div>
				<?php } ?>
				<div class="form-group">
					<label class="col-sm-4 control-label">Inventory Name:</label>
					<div class="col-sm-8">
						<select name="item_id" name="item_id" data-category="inventory" data-field="item_id" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" class="chosen-select-deselect form-control">
							<option value=""></option>
							<?php $query = mysqli_query($dbc,"SELECT `inventoryid`, `category`, `part_no`, `name`, `gst_exempt`, `client_price`, `admin_price`, `commercial_price`, `wholesale_price`, `final_retail_price`, `preferred_price`, `purchase_order_price`, `sales_order_price`, `web_price`, `wcb_price` FROM inventory WHERE deleted=0 ORDER BY name");
							while($row = mysqli_fetch_array($query)) {
								echo "<option data-category='".$row['category']."' ".($row['inventoryid'] == $inventory['item_id'] ? 'selected' : '')." data-gst-exempt='".$row['gst_exempt']."' data-client_price='".$row['client_price']."' data-admin_price='".$row['admin_price']."' data-commercial_price='".$row['commercial_price']."' data-wholesale_price='".$row['wholesale_price']."' data-final_retail_price='".$row['final_retail_price']."' data-preferred_price='".$row['preferred_price']."' data-purchase_order_price='".$row['purchase_order_price']."' data-sales_order_price='".$row['sales_order_price']."' data-web_price='".$row['web_price']."' data-wcb_price='".$row['wcb_price']."' value='". $row['inventoryid']."'>".$row['name'].'</option>';
							} ?>
						</select>
					</div>
				</div>
				<?php if (in_array('inventory_type',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Type:</label>
						<div class="col-sm-8">
							<select name="type" data-category="inventory" data-field="type" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" class="chosen-select-deselect form-control">
								<option <?= ($inventory['type'] == 'General' ? "selected" : '') ?> value="General">General</option>
								<option <?= ($inventory['type'] == 'WCB' ? "selected" : (strpos($this_injury['injury_type'],'WCB') === false && $this_injury['injury_type'] != '' ? "disabled" : '')) ?> value="WCB">WCB</option>
								<option <?= ($inventory['type'] == 'MVA' ? "selected" : (strpos($this_injury['injury_type'],'MVA') === false && $this_injury['injury_type'] != '' ? "disabled" : '')) ?> value="MVA">MVA</option>
							</select>
						</div>
					</div>
				<?php } ?>
				<?php if (in_array('inventory_price',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Unit Price:</label>
						<div class="col-sm-8">
							<input name="unit_price" data-category="inventory" data-field="unit_price" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" value="<?= $inventory['unit_price'] ?>" type="number" step="0.01" readonly class="form-control" />
						</div>
					</div>
				<?php } ?>
				<div class="form-group">
					<label class="col-sm-4 control-label">Quantity:</label>
					<div class="col-sm-8">
						<input name="quantity" data-field="quantity" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" value="<?= $inventory['quantity'] ?>" type="number" min="0" step="any" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Total:</label>
					<div class="col-sm-8">
						<input name="sub_total" data-field="sub_total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" readonly type="number" step="any" value="<?= $inventory['sub_total'] ?>" class="form-control" />
						<input name="tax_exempt" data-field="tax_exempt" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" type="hidden" value="<?= $inventory['tax_exempt'] ?>" />
							<input name="pst" data-field="pst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" type="hidden" value="<?= $inventory['pst'] ?>" />
							<input name="gst" data-field="gst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" type="hidden" value="<?= $inventory['gst'] ?>" />
							<input name="total" data-field="total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $inventory['line_id'] ?>" type="hidden" value="<?= $inventory['total'] ?>" />
					</div>
				</div>
				<?php if(count($payer_categories) > 0) {
					$third_party_payments = mysqli_query($dbc, "SELECT * FROM `invoice_payment` LEFT JOIN `contacts` ON `invoice_payment`.`payer_id`=`contacts`.`contactid` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `line_id`='".$inventory['line_id']."' AND `invoice_payment`.`payer_id` != `invoice_payment`.`contactid` AND `invoice_payment`.`deleted`=0");
					$payment = mysqli_fetch_array($third_party_payments);
					do { ?>
						<div class="payment-line">
							<?php if(count($payer_categories) > 1) { ?>
								<div class="form-group">
									<label class="col-sm-4 control-label">Third Party Category:</label>
									<div class="col-sm-8">
										<select name="third_category" class="form-control chosen-select-deselect"><option></option>
											<?php foreach ($payer_categories as $cat_tab) {
												echo "<option ".($payment['category'] == $cat_tab ? 'selected' : '')." value='". $cat_tab."'>".$cat_tab.'</option>';
											}
											if(!in_array($payment['category'], $payer_categories)) {
												echo "<option selected value='". $payment['category']."'>".$payment['category'].'</option>';
											} ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<select name="payer_id" data-field="payer_id" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $inventory['line_id'] ?>" class="form-control chosen-select-deselect">
										<option></option>
										<?php $contact_categories = "'".implode("','", $payer_categories)."'";
										$contact_found = false;
										$each_contact = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE `category` IN ($contact_categories) AND `deleted` = 0 AND `status` > 0"));
										foreach ($each_contact as $payer) {
											echo "<option ".($payer['category'] != $payment['category'] && $payment['category'] != '' ? 'style="display:none;"' : '')." data-category='".$payer['category']."' ".($payment['payer_id'] == $payer['contactid'] ? 'selected' : '')." value='".$payer['contactid']."'>".$payer['name']."</option>";
											$contact_found = ($contact_found || $payment['payer_id'] == $payer['contactid']);
										}
										if(!$contact_found) {
											echo "<option selected data-category='".$payment['category']."' value='". $payment['payer_id']."'>".get_client($dbc, $payment['payer_id']).'</option>';
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Paid by <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<input type="number" step="0.01" name="amount" onchange="$(this).data('manual',1);" data-manual="<?= $payment['payer_id'] > 0 && $payment['amount'] != $inventory['total'] ? 1 : 0 ?>" data-field="amount" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $inventory['line_id'] ?>" value="<?= $payment['amount'] ?>" class="form-control">
								</div>
							</div>
						</div>
					<?php } while($payment = mysqli_fetch_array($third_party_payments));
				} ?>
				<div class="form-group">
					<img src="<?= WEBSITE_URL ?>/img/plus.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" onclick="addRow('inventory');">
					<img src="<?= WEBSITE_URL ?>/img/remove.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" data-table="invoice_lines" data-field="deleted" data-id="<?= $inventory['line_id'] ?>" data-id-field="line_id" data-value="1" data-category="service" onclick="remLine(this);">
				</div>
			</div>
		<?php } while($inventory = mysqli_fetch_array($inventory_lines)); ?>
	</div>
<?php } ?>

<!-- Packages -->
<?php if(in_array('packages',$field_config)) { ?>
	<div data-tab-name='packages' data-locked='' class="form-horizontal">
		<h4>Packages</h4>
		<?php $packages = mysqli_query($dbc, "SELECT `category`, `item_id`, `type`, `description`, `heading`, `compensation`, SUM(`quantity`) quantity, `unit_price`, `admin_fee`, `uom`, `tax_exempt`, SUM(`sub_total`) sub_total, SUM(`pst`) pst, SUM(`gst`) gst, SUM(`total`) total FROM `invoice_lines` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `deleted`=0 AND `category`='package' GROUP BY `category`, `item_id`, `type`, `description`, `heading`, `compensation`, `unit_price`, `admin_fee`, `uom`, `tax_exempt`");
		$package = mysqli_fetch_array($packages);
		do {
			$package_info = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `category`, `heading`, `client_price`, `admin_price`, `commercial_price`, `wholesale_price`, `final_retail_price`, `gst_exempt` FROM `package` WHERE `packageid`='{$package['item_id']}'")); ?>
			<div class="line-group package">
				<div class="form-group">
					<label class="col-sm-4 control-label">Package Category:</label>
					<div class="col-sm-8">
						<select name="line_category" class="chosen-select-deselect form-control package_cat_onchange">
							<option value=""></option>
							<?php $query = mysqli_query($dbc,"SELECT `category` FROM package WHERE deleted=0 GROUP BY `category` ORDER BY `category`");
							while($row = mysqli_fetch_array($query)) {
								echo "<option ".($row['category'] == $package_info['category'] ? 'selected' : '')." value='". $row['category']."'>".$row['category'].'</option>';
							} ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Package Name:</label>
					<div class="col-sm-8">
						<select name="item_id" name="item_id" data-category="package" data-field="item_id" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" class="chosen-select-deselect form-control">
							<option value=""></option>
							<?php $query = mysqli_query($dbc,"SELECT `packageid`, `category`, `heading`, `client_price`, `admin_price`, `commercial_price`, `wholesale_price`, `final_retail_price`, `cost`, `gst_exempt` FROM `package` WHERE deleted=0 ORDER BY `heading`");
							while($row = mysqli_fetch_array($query)) {
								echo "<option data-category='".$row['category']."' ".($row['packageid'] == $package['item_id'] ? 'selected' : '')." data-gst-exempt='".$row['gst_exempt']."' data-cost='".$row['cost']."' data-client_price='".$row['client_price']."' data-admin_price='".$row['admin_price']."' data-commercial_price='".$row['commercial_price']."' data-wholesale_price='".$row['wholesale_price']."' data-final_retail_price='".$row['final_retail_price']."' value='". $row['packageid']."'>".$row['heading'].'</option>';
							} ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Fee:</label>
					<div class="col-sm-8">
						<input name="unit_price" data-field="unit_price" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" readonly type="number" step="any" value="<?= $package['unit_price'] ?>" class="form-control" />
						<input name="sub_total" data-field="sub_total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" readonly type="hidden" value="<?= $package['sub_total'] ?>" />
						<input name="quantity" data-field="quantity" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" readonly type="hidden" value="<?= $package['quantity'] ?>" />
						<input name="tax_exempt" data-field="tax_exempt" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" type="hidden" value="<?= $package['tax_exempt'] ?>" />
						<input name="pst" data-field="pst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" type="hidden" value="<?= $package['pst'] ?>" />
						<input name="gst" data-field="gst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" type="hidden" value="<?= $package['gst'] ?>" />
						<input name="total" data-field="total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $package['line_id'] ?>" type="hidden" value="<?= $package['total'] ?>" />
					</div>
				</div>
				<?php if(count($payer_categories) > 0) {
					$third_party_payments = mysqli_query($dbc, "SELECT * FROM `invoice_payment` LEFT JOIN `contacts` ON `invoice_payment`.`payer_id`=`contacts`.`contactid` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `line_id`='".$package['line_id']."' AND `invoice_payment`.`payer_id` != `invoice_payment`.`contactid` AND `invoice_payment`.`deleted`=0");
					$payment = mysqli_fetch_array($third_party_payments);
					do { ?>
						<div class="payment-line">
							<?php if(count($payer_categories) > 1) { ?>
								<div class="form-group">
									<label class="col-sm-4 control-label">Third Party Category:</label>
									<div class="col-sm-8">
										<select name="third_category" class="form-control chosen-select-deselect"><option></option>
											<?php foreach ($payer_categories as $cat_tab) {
												echo "<option ".($payment['category'] == $cat_tab ? 'selected' : '')." value='". $cat_tab."'>".$cat_tab.'</option>';
											}
											if(!in_array($payment['category'], $payer_categories)) {
												echo "<option selected value='". $payment['category']."'>".$payment['category'].'</option>';
											} ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<select name="payer_id" data-field="payer_id" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $package['line_id'] ?>" class="form-control chosen-select-deselect">
										<option></option>
										<?php $contact_categories = "'".implode("','", $payer_categories)."'";
										$contact_found = false;
										$each_contact = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE `category` IN ($contact_categories) AND `deleted` = 0 AND `status` > 0"));
										foreach ($each_contact as $payer) {
											echo "<option ".($payer['category'] != $payment['category'] && $payment['category'] != '' ? 'style="display:none;"' : '')." data-category='".$payer['category']."' ".($payment['payer_id'] == $payer['contactid'] ? 'selected' : '')." value='".$payer['contactid']."'>".$payer['name']."</option>";
											$contact_found = ($contact_found || $payment['payer_id'] == $payer['contactid']);
										}
										if(!$contact_found) {
											echo "<option selected data-category='".$payment['category']."' value='". $payment['payer_id']."'>".get_client($dbc, $payment['payer_id']).'</option>';
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Paid by <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<input type="number" step="0.01" name="amount" onchange="$(this).data('manual',1);" data-manual="<?= $payment['payer_id'] > 0 && $payment['amount'] != $package['total'] ? 1 : 0 ?>" data-field="amount" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $package['line_id'] ?>" value="<?= $payment['amount'] ?>" class="form-control">
								</div>
							</div>
						</div>
					<?php } while($payment = mysqli_fetch_array($third_party_payments));
				} ?>
				<div class="form-group">
					<img src="<?= WEBSITE_URL ?>/img/plus.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" onclick="addRow('package');">
					<img src="<?= WEBSITE_URL ?>/img/remove.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" data-table="invoice_lines" data-field="deleted" data-id="<?= $package['line_id'] ?>" data-id-field="line_id" data-value="1" data-category="service" onclick="remLine(this);">
				</div>
			</div>
		<?php } while($package = mysqli_fetch_array($packages)); ?>
	</div>
<?php } ?>

<!-- Products -->
<?php if(in_array('products',$field_config)) { ?>
	<div data-tab-name='products' data-locked='' class="form-horizontal">
		<h4>Products</h4>
		<?php $products = mysqli_query($dbc, "SELECT `category`, `item_id`, `type`, `description`, `heading`, `compensation`, SUM(`quantity`) quantity, `unit_price`, `admin_fee`, `uom`, `tax_exempt`, SUM(`sub_total`) sub_total, SUM(`pst`) pst, SUM(`gst`) gst, SUM(`total`) total FROM `invoice_lines` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `deleted`=0 AND `category`='product' GROUP BY `category`, `item_id`, `type`, `description`, `heading`, `compensation`, `unit_price`, `admin_fee`, `uom`, `tax_exempt`");
		$product = mysqli_fetch_array($products);
		do {
			$product_info = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `productid`, `category`, `product_type`, `heading`, `gst_exempt`, `client_price`, `admin_price`, `commercial_price`, `wholesale_price`, `final_retail_price`, `preferred_price`, `purchase_order_price`, `sales_order_price`, `web_price` FROM `products` WHERE `productid`='{$product['item_id']}'")); ?>
			<div class="line-group product">
				<?php if (in_array('product_cat',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Product Category:</label>
						<div class="col-sm-8">
							<select name="line_category" class="chosen-select-deselect form-control product_cat_onchange">
								<option value=""></option>
								<?php $query = mysqli_query($dbc,"SELECT `category` FROM `products` WHERE deleted=0 GROUP BY `category` ORDER BY `category`");
								while($row = mysqli_fetch_array($query)) {
									echo "<option ".($row['category'] == $product_info['category'] ? 'selected' : '')." value='". $row['category']."'>".$row['category'].'</option>';
								} ?>
							</select>
						</div>
					</div>
				<?php } ?>
				<div class="form-group">
					<label class="col-sm-4 control-label">Product:</label>
					<div class="col-sm-8">
						<select name="item_id" name="item_id" data-category="product" data-field="item_id" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" class="chosen-select-deselect form-control">
							<option value=""></option>
							<?php $query = mysqli_query($dbc,"SELECT `productid`, `category`, `product_type`, `heading`, `gst_exempt`, `client_price`, `admin_price`, `commercial_price`, `wholesale_price`, `final_retail_price`, `preferred_price`, `purchase_order_price`, `sales_order_price`, `web_price` FROM `products` WHERE `deleted`=0 ORDER BY `heading`, `product_type`");
							while($row = mysqli_fetch_array($query)) {
								echo "<option data-category='".$row['category']."' ".($row['productid'] == $product['item_id'] ? 'selected' : '')." data-gst-exempt='".$row['gst_exempt']."' data-client_price='".$row['client_price']."' data-admin_price='".$row['admin_price']."' data-commercial_price='".$row['commercial_price']."' data-wholesale_price='".$row['wholesale_price']."' data-final_retail_price='".$row['final_retail_price']."' data-preferred_price='".$row['preferred_price']."' data-purchase_order_price='".$row['purchase_order_price']."' data-sales_order_price='".$row['sales_order_price']."' data-web_price='".$row['web_price']."' data-wcb_price='".$row['wcb_price']."' value='". $row['productid']."'>".$row['heading'].' '.$row['product_type'].'</option>';
							} ?>
						</select>
					</div>
				</div>
				<?php if (in_array('product_price',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Unit Price:</label>
						<div class="col-sm-8">
							<input name="unit_price" data-category="product" data-field="unit_price" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" value="<?= $product['unit_price'] ?>" type="number" step="0.01" readonly class="form-control" />
						</div>
					</div>
				<?php } ?>
				<?php if (in_array('product_qty',$field_config)) { ?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Quantity:</label>
						<div class="col-sm-8">
							<input name="quantity" data-field="quantity" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" value="<?= $product['quantity'] ?>" type="number" min="0" step="any" class="form-control" />
						</div>
					</div>
				<?php } else { ?>
					<input name="quantity" value="1" type="hidden" />
				<?php } ?>
				<div class="form-group">
					<label class="col-sm-4 control-label">Total:</label>
					<div class="col-sm-8">
						<input name="sub_total" data-field="sub_total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" readonly type="number" step="any" value="<?= $product['sub_total'] ?>" class="form-control" />
						<input name="tax_exempt" data-field="tax_exempt" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" type="hidden" value="<?= $product['tax_exempt'] ?>" />
							<input name="pst" data-field="pst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" type="hidden" value="<?= $product['pst'] ?>" />
							<input name="gst" data-field="gst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" type="hidden" value="<?= $product['gst'] ?>" />
							<input name="total" data-field="total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $product['line_id'] ?>" type="hidden" value="<?= $product['total'] ?>" />
					</div>
				</div>
				<?php if(count($payer_categories) > 0) {
					$third_party_payments = mysqli_query($dbc, "SELECT * FROM `invoice_payment` LEFT JOIN `contacts` ON `invoice_payment`.`payer_id`=`contacts`.`contactid` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `line_id`='".$product['line_id']."' AND `invoice_payment`.`payer_id` != `invoice_payment`.`contactid` AND `invoice_payment`.`deleted`=0");
					$payment = mysqli_fetch_array($third_party_payments);
					do { ?>
						<div class="payment-line">
							<?php if(count($payer_categories) > 1) { ?>
								<div class="form-group">
									<label class="col-sm-4 control-label">Third Party Category:</label>
									<div class="col-sm-8">
										<select name="third_category" class="form-control chosen-select-deselect"><option></option>
											<?php foreach ($payer_categories as $cat_tab) {
												echo "<option ".($payment['category'] == $cat_tab ? 'selected' : '')." value='". $cat_tab."'>".$cat_tab.'</option>';
											}
											if(!in_array($payment['category'], $payer_categories)) {
												echo "<option selected value='". $payment['category']."'>".$payment['category'].'</option>';
											} ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<select name="payer_id" data-field="payer_id" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $product['line_id'] ?>" class="form-control chosen-select-deselect">
										<option></option>
										<?php $contact_categories = "'".implode("','", $payer_categories)."'";
										$contact_found = false;
										$each_contact = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE `category` IN ($contact_categories) AND `deleted` = 0 AND `status` = 1"));
										foreach ($each_contact as $payer) {
											echo "<option ".($payer['category'] != $payment['category'] && $payment['category'] != '' ? 'style="display:none;"' : '')." data-category='".$payer['category']."' ".($payment['payer_id'] == $payer['contactid'] ? 'selected' : '')." value='".$payer['contactid']."'>".$payer['name']."</option>";
											$contact_found = ($contact_found || $payment['payer_id'] == $payer['contactid']);
										}
										if(!$contact_found) {
											echo "<option selected data-category='".$payment['category']."' value='". $payment['payer_id']."'>".get_client($dbc, $payment['payer_id']).'</option>';
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Paid by <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<input type="number" step="0.01" name="amount" onchange="$(this).data('manual',1);" data-manual="<?= $payment['payer_id'] > 0 && $payment['amount'] != $product['total'] ? 1 : 0 ?>" data-field="amount" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $product['line_id'] ?>" value="<?= $payment['amount'] ?>" class="form-control">
								</div>
							</div>
						</div>
					<?php } while($payment = mysqli_fetch_array($third_party_payments));
				} ?>
				<div class="form-group">
					<img src="<?= WEBSITE_URL ?>/img/plus.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" onclick="addRow('product');">
					<img src="<?= WEBSITE_URL ?>/img/remove.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" data-table="invoice_lines" data-field="deleted" data-id="<?= $product['line_id'] ?>" data-id-field="line_id" data-value="1" data-category="service" onclick="remLine(this);">
				</div>
			</div>
		<?php } while($product = mysqli_fetch_array($products)); ?>
	</div>
<?php } ?>

<!-- Miscellaneous -->
<?php if(in_array('misc_items',$field_config)) { ?>
	<div data-tab-name='misc_items' data-locked='' class="form-horizontal">
		<h4>Miscellaneous Items</h4>
		<?php $miscs = mysqli_query($dbc, "SELECT `category`, `item_id`, `type`, `description`, `heading`, `compensation`, SUM(`quantity`) quantity, `unit_price`, `admin_fee`, `uom`, `tax_exempt`, SUM(`sub_total`) sub_total, SUM(`pst`) pst, SUM(`gst`) gst, SUM(`total`) total FROM `invoice_lines` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `deleted`=0 AND `category`='misc product' GROUP BY `category`, `item_id`, `type`, `description`, `heading`, `compensation`, `unit_price`, `admin_fee`, `uom`, `tax_exempt`");
		$misc = mysqli_fetch_array($miscs);
		do { ?>
			<div class="line-group misc">
				<div class="form-group">
					<label class="col-sm-4 control-label">Description:</label>
					<div class="col-sm-8">
						<input type="text" name="description" data-category="misc product" data-field="description" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" value="<?= $misc['description'] ?>" class="form-control">
						<input type="hidden" name="item_id" data-category="misc product" data-field="item_id" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" value="0" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Unit Price:</label>
					<div class="col-sm-8">
						<input name="unit_price" data-category="misc product" data-field="unit_price" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" value="<?= $misc['unit_price'] ?>" type="number" step="0.01" min=0 class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Quantity:</label>
					<div class="col-sm-8">
						<input name="quantity" data-field="quantity" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" value="<?= $misc['quantity'] ?>" type="number" min="0" step="any" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Total:</label>
					<div class="col-sm-8">
						<input name="sub_total" data-field="sub_total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" readonly type="number" step="any" value="<?= $misc['sub_total'] ?>" class="form-control" />
						<input name="tax_exempt" data-field="tax_exempt" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" type="hidden" value="<?= $misc['tax_exempt'] ?>" />
						<input name="pst" data-field="pst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" type="hidden" value="<?= $misc['pst'] ?>" />
						<input name="gst" data-field="gst" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" type="hidden" value="<?= $misc['gst'] ?>" />
						<input name="total" data-field="total" data-table="invoice_lines" data-id-field="line_id" data-id="<?= $misc['line_id'] ?>" type="hidden" value="<?= $misc['total'] ?>" />
					</div>
				</div>
				<?php if(count($payer_categories) > 0) {
					$third_party_payments = mysqli_query($dbc, "SELECT * FROM `invoice_payment` LEFT JOIN `contacts` ON `invoice_payment`.`payer_id`=`contacts`.`contactid` WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE '$invoiceid' IN (`invoiceid`,`invoiceid_src`)) AND `line_id`='".$misc['line_id']."' AND `invoice_payment`.`payer_id` != `invoice_payment`.`contactid` AND `invoice_payment`.`deleted`=0");
					$payment = mysqli_fetch_array($third_party_payments);
					do { ?>
						<div class="payment-line">
							<?php if(count($payer_categories) > 1) { ?>
								<div class="form-group">
									<label class="col-sm-4 control-label">Third Party Category:</label>
									<div class="col-sm-8">
										<select name="third_category" class="form-control chosen-select-deselect"><option></option>
											<?php foreach ($payer_categories as $cat_tab) {
												echo "<option ".($payment['category'] == $cat_tab ? 'selected' : '')." value='". $cat_tab."'>".$cat_tab.'</option>';
											}
											if(!in_array($payment['category'], $payer_categories)) {
												echo "<option selected value='". $payment['category']."'>".$payment['category'].'</option>';
											} ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<select name="payer_id" data-field="payer_id" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $misc['line_id'] ?>" class="form-control chosen-select-deselect">
										<option></option>
										<?php $contact_categories = "'".implode("','", $payer_categories)."'";
										$contact_found = false;
										$each_contact = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `name`, `first_name`, `last_name`, `category` FROM `contacts` WHERE `category` IN ($contact_categories) AND `deleted` = 0 AND `status` = 1"));
										foreach ($each_contact as $payer) {
											echo "<option ".($payer['category'] != $payment['category'] && $payment['category'] != '' ? 'style="display:none;"' : '')." data-category='".$payer['category']."' ".($payment['payer_id'] == $payer['contactid'] ? 'selected' : '')." value='".$payer['contactid']."'>".$payer['name']."</option>";
											$contact_found = ($contact_found || $payment['payer_id'] == $payer['contactid']);
										}
										if(!$contact_found) {
											echo "<option selected data-category='".$payment['category']."' value='". $payment['payer_id']."'>".get_client($dbc, $payment['payer_id']).'</option>';
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Paid by <?= count($payer_categories) > 1 ? 'Third Party' : $payer_categories[0] ?>:</label>
								<div class="col-sm-8">
									<input type="number" step="0.01" name="amount" onchange="$(this).data('manual',1);" data-manual="<?= $payment['payer_id'] > 0 && $payment['amount'] != $misc['total'] ? 1 : 0 ?>" data-field="amount" data-table="invoice_payment" data-id-field="id" data-id="<?= $payment['id'] ?>" data-attach-field="line_id" data-attach-id="<?= $misc['line_id'] ?>" value="<?= $payment['amount'] ?>" class="form-control">
								</div>
							</div>
						</div>
					<?php } while($payment = mysqli_fetch_array($third_party_payments));
				} ?>
				<div class="form-group">
					<img src="<?= WEBSITE_URL ?>/img/plus.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" onclick="addRow('misc');">
					<img src="<?= WEBSITE_URL ?>/img/remove.png" style="height: 1.5em; margin: 0.25em; width: 1.5em;" class="pull-right" data-table="invoice_lines" data-field="deleted" data-id="<?= $misc['line_id'] ?>" data-id-field="line_id" data-value="1" data-category="service" onclick="remLine(this);">
				</div>
			</div>
		<?php } while($misc = mysqli_fetch_array($miscs)); ?>
	</div>
<?php } ?>

<!-- Promotion -->
<?php if (in_array('promo',$field_config)) { ?>
	<div data-tab-name='promo' data-locked='' class="form-horizontal form-group">
		<div class="form-group">
			<label class="col-sm-4 control-label">Promotion:</label>
			<div class="col-sm-8">
				<select name="promotionid" data-field="promotionid" data-table="invoice" class="form-control chosen-select-deselect"><option></option>
					<?php $promotions = mysqli_query($dbc, "SELECT `promotionid`, `heading`, `cost` FROM `promotion` WHERE IFNULL(`expiry_date`,'9999-99-99') > NOW() AND deleted = 0");
					while($promotion = mysqli_fetch_assoc($promotions)) {
						echo "<option ".($invoice['promotionid'] == $promotion['promotionid'] ? 'selected' : '')." data-cost='".$promotion['cost']."' value='".$promotion['promotionid']."'>".$promotion['heading']."</option>";
					} ?>
				</select>
			</div>
		</div>
	</div>
<?php } ?>

<!-- Gratuity -->
<?php if (in_array('tips',$field_config)) { ?>
	<div data-tab-name='tips' data-locked='' class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-4 control-label">Gratuity:</label>
			<div class="col-sm-8">
				<input type="number" min="0" step="0.01" name="gratuity" data-field="gratuity" data-table="invoice" value="<?= $invoice['gratuity'] ?>" class="form-control">
			</div>
		</div>
	</div>
<?php } ?>

<!-- Delivery -->
<?php if (in_array('delivery',$field_config)) { ?>
	<div data-tab-name='delivery' data-locked='' class="form-horizontal">
		<div class="form-group" <?= (in_array('delivery',$field_config) ? '' : 'style="display:none;"') ?>>
			<label for="site_name" class="col-sm-4 control-label">
				<span class="popover-examples list-inline">
					<a href="#job_file" data-toggle="tooltip" data-placement="top" title="Select the delivery method chosen by the <?= count($purchaser_config) > 1 ? 'Customer' : $purchaser_config[0] ?>."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
				</span>Delivery Option:</label>
			<div class="col-sm-8">
				<select name="delivery_type" data-field="delivery_type" data-table="invoice" class="form-control chosen-select-deselect"><option></option>
					<option <?= ($invoice['delivery_type'] == 'Pick-Up' ? 'selected' : '') ?> value="Pick-Up">Pick-Up</option>
					<option <?= ($invoice['delivery_type'] == 'Company Delivery' ? 'selected' : '') ?> value="Company Delivery">Company Delivery</option>
					<option <?= ($invoice['delivery_type'] == 'Drop Ship' ? 'selected' : '') ?> value="Drop Ship">Drop Ship</option>
					<option <?= ($invoice['delivery_type'] == 'Shipping' ? 'selected' : '') ?> value="Shipping">Shipping</option>
					<option <?= ($invoice['delivery_type'] == 'Shipping on Customer Account' ? 'selected' : '') ?> value="Shipping on Customer Account">Shipping on Customer Account</option>
				</select>
			</div>
		</div>

		<div class="form-group confirm_delivery" <?= (($invoice['delivery_type'] == 'Drop Ship' || $invoice['delivery_type'] == 'Shipping' || $invoice['delivery_type'] == 'Company Delivery') ? '' : 'style="display:none;"') ?>>
			<label for="site_name" class="col-sm-4 control-label">
				<span class="popover-examples list-inline">
					<a href="#job_file" data-toggle="tooltip" data-placement="top" title="Update the address for delivery. If it is wrong, you will need to update it on the <?= count($purchaser_config) > 1 ? 'Customer' : $purchaser_config[0] ?> profile. You can also enter a one-time shipping address."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
				</span>Confirm Delivery Address:</label>
			<div class="col-sm-8">
				<input name="delivery_address" data-field="delivery_address" data-table="invoice" type="text" class="form-control" value="<?= $invoice['delivery_address'] ?>" />
			</div>
		</div>

		<div class="form-group deliver_contractor" <?= (($invoice['delivery_type'] == 'Drop Ship' || $invoice['delivery_type'] == 'Shipping') ? '' : 'style="display:none;"') ?>>
			<label for="site_name" class="col-sm-4 control-label">
				<span class="popover-examples list-inline">
					<a href="#job_file" data-toggle="tooltip" data-placement="top" title="Select the contractor that will handle the delivery."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
				</span>Delivery Contractor:</label>
			<div class="col-sm-8">
				<select name="contractorid" data-field="contractorid" data-table="invoice" class="form-control chosen-select-deselect"><option></option>
					<?php $contractors = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `last_name`, `first_name`, `name` FROM `contacts` WHERE `category` LIKE 'Contractor%' AND `deleted`=0 AND `status`=1"));
					foreach($contractors as $contractor) {
						echo "<option ".($contractor['contactid'] == $invoice['contractorid'] ? 'selected' : '')." value='". $contractor['contactid']."'>".($contractor['name'] != '' ? $contractor['name'] : $contractor['first_name'].' '.$contractor['last_name']).'</option>';
					} ?>
				</select>
			</div>
		</div>

		<div class="form-group ship_amt" <?= (($invoice['delivery_type'] == '' || $invoice['delivery_type'] == 'Pick-Up') ? 'style="display:none;"' : '') ?>>
			<label for="site_name" class="col-sm-4 control-label">
				<span class="popover-examples list-inline">
					<a href="#job_file" data-toggle="tooltip" data-placement="top" title="Enter the cost of shipping."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
				</span>Delivery/Shipping Amount:</label>
			<div class="col-sm-8">
				<input name="delivery" data-field="delivery" data-table="invoice" type="number" min="0" step="0.01" class="form-control" value="<?= $invoice['delivery'] ?>" />
			</div>
		</div>

		<div class="form-group" <?= (in_array('ship_date',$field_config) ? '' : 'style="display:none;"') ?>>
			<label for="site_name" class="col-sm-4 control-label">
				<span class="popover-examples list-inline">
					<a href="#job_file" data-toggle="tooltip" data-placement="top" title="Enter the date by which the order will ship."><img src="<?php echo WEBSITE_URL;?>/img/info.png" width="20"></a>
				</span>Ship Date:</label>
			<div class="col-sm-8">
				<input name="ship_date" data-field="ship_date" data-table="invoice" type="text" class="form-control datepicker" value="<?= $invoice['ship_date'] ?>" />
			</div>
		</div>
	</div>
<?php } ?>

<!-- Next Appointment -->
<?php if (in_array('next_appt',$field_config)) { ?>
	<div data-tab-name='next_appt' data-locked='' class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-4 control-label">Book Next Appointment:</label>
			<div class="col-sm-8">
				<button class="btn brand-btn" onclick="$('.next_appt').toggle(); return false;">Add Appointment</button>
			</div>
			<div class="next_appt" style="display:none;">
				<div class="form-group">
					<label class="col-sm-4 control-label">Start Date & Time:</label>
					<div class="col-sm-8">
						<input type="text" name="next_appt_start" class="datetimepicker form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">End Date & Time:</label>
					<div class="col-sm-8">
						<input type="text" name="next_appt_end" class="datetimepicker form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Appointment Type:</label>
					<div class="col-sm-8">
						<select class="chosen-select-deselect" name="next_appt_type"><option></option>
                            <?php $appointment_types = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `appointment_type` WHERE `deleted` = 0"),MYSQLI_ASSOC);
                            foreach ($appointment_types as $appointment_type) {
                                echo '<option value="'.$appointment_type['id'].'">'.$appointment_type['name'].'</option>';
                            } ?>
						</select>
					</div>
				</div>
				<button class="btn brand-btn pull-right" onclick="bookAppt(); return false;">Book Appointment</button>
			</div>
		</div>
	</div>
<?php } ?>

<!-- Survey -->
<?php if (in_array('survey',$field_config)) { ?>
	<div data-tab-name='survey' data-locked='' class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-4 control-label">Send Survey:</label>
			<div class="col-sm-8">
				<?php $query = mysqli_query($dbc,"SELECT surveyid, name, service FROM crm_feedback_survey_form WHERE deleted=0");
				while($row = mysqli_fetch_array($query)) {
					echo "<button class='btn brand-btn' onclick='sendSurvey(".$row['surveyid']."); return false;'>Send ".$row['name'].': '.$row['service'].'</button>';
				} ?>
			</div>
		</div>
	</div>
<?php } ?>

<!-- Request Recommendation -->
<?php if (in_array('request_recommend',$field_config)) { ?>
	<div data-tab-name='request_recommend' data-locked='' class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-4 control-label">Request Recommendation Report:</label>
			<div class="col-sm-8">
				<button class='btn brand-btn' onclick='sendSurvey("recommendation"); return false;'>Request Recommendation Report</button>
			</div>
		</div>
	</div>
<?php } ?>

<!-- Follow Up Email -->
<?php if (in_array('followup',$field_config)) { ?>
	<div data-tab-name='followup' data-locked='' class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-4 control-label">Send Follow Up Email After Assessment:</label>
			<div class="col-sm-8">
				<button class='btn brand-btn' onclick='sendSurvey("massage"); return false;'>Send Massage Follow Up Email</button>
				<button class='btn brand-btn' onclick='sendSurvey("physio"); return false;'>Send Physiotherapy Follow Up Email</button>
			</div>
		</div>
	</div>
<?php } ?>

<!-- Comments -->
<?php if (in_array('comment',$field_config)) { ?>
	<div data-tab-name='comment' data-locked='' class="form-horizontal">
		<div class="form-group">
			<label class="col-sm-4 control-label">Comments:</label>
			<div class="col-sm-8">
				<textarea name="comment" data-field="comment" data-table="invoice"><?= $invoice['comment'] ?></textarea>
			</div>
		</div>
	</div>
<?php } ?>