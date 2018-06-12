  <?php if (strpos($value_config, ','."Amount To Bill".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Amount To Bill:</label>
    <div class="col-sm-8">
      <input name="amount_to_bill" value="<?php echo $amount_to_bill; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Amount Owing".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Amount Owing:</label>
    <div class="col-sm-8">
      <input name="amount_owing" value="<?php echo $amount_owing; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php } ?>

  <?php if (strpos($value_config, ','."Amount Credit".',') !== FALSE) { ?>
  <div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Amount Credit:</label>
    <div class="col-sm-8">
      <input name="amount_credit" value="<?php echo $amount_credit; ?>" type="text" class="form-control">
    </div>
  </div>
  <?php }

    if (strpos($value_config, ','."Accounts Receivable/Credit on Account".',') !== FALSE) { ?>
	<h3>Patient : <?= $first_name ?> <?= $last_name ?></h3>
	<div class="form-group">
		<label for="company_name" class="col-sm-4 control-label">Amount Owing:</label>
		<div class="col-sm-8">
			<input name="amount_owing" value="<?php echo $amount_owing; ?>" type="text" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label for="company_name" class="col-sm-4 control-label">Amount Credit:</label>
		<div class="col-sm-8">
			<input name="amount_credit" value="<?php echo $amount_credit; ?>" type="text" class="form-control">
		</div>
	</div>

	<?php $packages_sold = mysqli_query($dbc, "SELECT `package_item_type`, `item_description`, `sold_date`, IFNULL(`used_date`,'On Account') `date_used` FROM `contact_package_sold` WHERE `contactid`='$contactid' AND `deleted`=0");
	if(mysqli_num_rows($packages_sold)) { ?>
		<div class="form-group">
			<label for="company_name" class="col-sm-4 control-label">Services and Inventory From Packages:</label>
			<div class="col-sm-8">
				<div id="no-more-tables">
					<table class="table table-bordered">
						<tr class="hidden-xs hidden-sm">
							<th>Type</th>
							<th>Description</th>
							<th>Date Sold</th>
							<th>Date Used</th>
						</tr>
						<?php while($package_line = mysqli_fetch_array($packages_sold)) { ?>
							<tr>
								<td data-title="Type"><?= $package_line['package_item_type'] ?></td>
								<td data-title="Description"><?= $package_line['item_description'] ?></td>
								<td data-title="Date Sold"><?= $package_line['sold_date'] ?></td>
								<td data-title="Date Used"><?= $package_line['date_used'] ?></td>
							</tr>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
    <?php
    }
    if (strpos($value_config, ','."Patient Accounts Receivable".',') !== FALSE) {

        $receivables = mysqli_query($dbc, "SELECT * FROM invoice_patient WHERE paid IN ('On Account','No') AND patientid = '$contactid' ORDER BY invoiceid");
        if(mysqli_num_rows($receivables) > 0) {
            echo '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
            echo '<tr>
                <th>Invoice#</th>
                <th>Invoice Date</th>
                <th>Amount Receivable</th>
                </tr>';
            while($invoice_receivable = mysqli_fetch_array($receivables)) {
                echo "<tr>
                    <td data-title='Invoice #'><a href='../Invoice/Download/invoice_".$invoice_receivable['invoiceid'].".pdf'>".$invoice_receivable['invoiceid']."</a></td>
                    <td data-title='Invoice Date'>".$invoice_receivable['invoice_date']."</td>
                    <td data-title='Amount Receivable'>".$invoice_receivable['patient_price']."</td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo "<h4>No Unpaid Invoices Found.</h4>";
        }

    }

    if (strpos($value_config, ','."Insurer Accounts Receivable for Patient".',') !== FALSE) {

        $receivables = mysqli_query($dbc, "SELECT * FROM invoice_insurer WHERE paid != 'Yes' AND `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE `patientid`='$contactid') ORDER BY invoiceid");
        if(mysqli_num_rows($receivables) > 0) {
            echo '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
            echo '<tr>
                <th>Invoice#</th>
                <th>Insurance Company</th>
                <th>Invoice Date</th>
                <th>Amount Receivable</th>
                </tr>';
            while($invoice_receivable = mysqli_fetch_array($receivables)) {
                echo "<tr>
                    <td data-title='Invoice #'><a href='../Invoice/Download/insuranceinvoice_".$invoice_receivable['insurerid']."_".$invoice_receivable['invoiceid'].".pdf'>".$invoice_receivable['invoiceid']."</a></td>
                    <td data-title='Insurance Company'>".get_client($dbc, $invoice_receivable['insurerid'])."</td>
                    <td data-title='Invoice Date'>".$invoice_receivable['invoice_date']."</td>
                    <td data-title='Amount Receivable'>".$invoice_receivable['insurer_price']."</td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo "<h4>No Unpaid Invoices Found.</h4>";
        }

    }

    if (strpos($value_config, ','."All Patient Invoices".',') !== FALSE) {

        $receivables = mysqli_query($dbc, "SELECT * FROM invoice_patient WHERE patientid = '$contactid' ORDER BY invoiceid");
        if(mysqli_num_rows($receivables) > 0) {
            echo '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
            echo '<tr>
                <th>Invoice#</th>
                <th>Invoice Date</th>
                <th>Invoice Amount</th>
                <th>Paid</th>
                </tr>';
            while($invoice_receivable = mysqli_fetch_array($receivables)) {
                echo "<tr>
                    <td data-title='Invoice #'><a href='../Invoice/Download/invoice_".$invoice_receivable['invoiceid'].".pdf'>".$invoice_receivable['invoiceid']."</a></td>
                    <td data-title='Invoice Date'>".$invoice_receivable['invoice_date']."</td>
                    <td data-title='Invoice Amount'>".$invoice_receivable['patient_price']."</td>
                    <td data-title='Paid'>".$invoice_receivable['paid']."</td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo "<h4>No Patient Invoices Found.</h4>";
        }

    }

    if (strpos($value_config, ','."All Insurer Invoices for Patient".',') !== FALSE) {

        $receivables = mysqli_query($dbc, "SELECT * FROM invoice_insurer WHERE `invoiceid` IN (SELECT `invoiceid` FROM `invoice` WHERE `patientid`='$contactid') ORDER BY invoiceid");
        if(mysqli_num_rows($receivables) > 0) {
            echo '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
            echo '<tr>
                <th>Invoice#</th>
                <th>Insurance Company</th>
                <th>Invoice Date</th>
                <th>Invoice Amount</th>
                <th>Paid</th>
                </tr>';
            while($invoice_receivable = mysqli_fetch_array($receivables)) {
                echo "<tr>
                    <td data-title='Invoice #'><a href='../Invoice/Download/insuranceinvoice_".$invoice_receivable['insurerid']."_".$invoice_receivable['invoiceid'].".pdf'>".$invoice_receivable['invoiceid']."</a></td>
                    <td data-title='Insurance Company'>".get_client($dbc, $invoice_receivable['insurerid'])."</td>
                    <td data-title='Invoice Date'>".$invoice_receivable['invoice_date']."</td>
                    <td data-title='Invoice Amount'>".$invoice_receivable['insurer_price']."</td>
                    <td data-title='Paid'>".$invoice_receivable['paid']."</td>
                </tr>";
            }
            echo '</table>';
        } else {
            echo "<h4>No Insurer Invoices Found.</h4>";
        }
    } ?>

        <?php if (strpos($value_config, ','."Total Monthly Rate".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Total Monthly Rate:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Total Monthly Rate".',') === false ? 'readonly' : ''); ?> name="total_monthly_rate" value="<?php echo $total_monthly_rate; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Total Annual Rate".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Total Annual Rate:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Total Annual Rate".',') === false ? 'readonly' : ''); ?> name="total_annual_rate" value="<?php echo $total_annual_rate; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Condo Fees".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Condo Fees:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Condo Fees".',') === false ? 'readonly' : ''); ?> name="condo_fees" value="<?php echo $condo_fees; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Deposit".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Deposit:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Deposit".',') === false ? 'readonly' : ''); ?> name="deposit" value="<?php echo $deposit; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Damage Deposit".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Damage Deposit:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Damage Deposit".',') === false ? 'readonly' : ''); ?> name="damage_deposit" value="<?php echo $damage_deposit; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Cost".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Cost:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Cost".',') === false ? 'readonly' : ''); ?> name="cost" value="<?php echo $cost; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Final Retail Price:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Final Retail Price".',') === false ? 'readonly' : ''); ?> name="final_retail_price" value="<?php echo $final_retail_price; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Admin Price".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Admin Price:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Admin Price".',') === false ? 'readonly' : ''); ?> name="admin_price" value="<?php echo $admin_price; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Wholesale Price:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Wholesale Price".',') === false ? 'readonly' : ''); ?> name="wholesale_price" value="<?php echo $wholesale_price; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Commercial Price".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Commercial Price:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Commercial Price".',') === false ? 'readonly' : ''); ?> name="commercial_price" value="<?php echo $commercial_price; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Client Price".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Client Price:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Client Price".',') === false ? 'readonly' : ''); ?> name="client_price" value="<?php echo $client_price; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Minimum Billable:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Minimum Billable".',') === false ? 'readonly' : ''); ?> name="minimum_billable" value="<?php echo $minimum_billable; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Estimated Hours:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Estimated Hours".',') === false ? 'readonly' : ''); ?> name="estimated_hours" value="<?php echo $estimated_hours; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Actual Hours".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Actual Hours:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Actual Hours".',') === false ? 'readonly' : ''); ?> name="actual_hours" value="<?php echo $actual_hours; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."MSRP".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">MSRP:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."MSRP".',') === false ? 'readonly' : ''); ?> name="msrp" value="<?php echo $msrp; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Hourly Rate:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Hourly Rate".',') === false ? 'readonly' : ''); ?> name="hourly_rate" value="<?php echo $hourly_rate; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Monthly Rate".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Monthly Rate:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Monthly Rate".',') === false ? 'readonly' : ''); ?> name="monthly_rate" value="<?php echo $monthly_rate; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Semi Monthly Rate".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Semi Monthly Rate:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Semi Monthly Rate".',') === false ? 'readonly' : ''); ?> name="semi_monthly_rate" value="<?php echo $semi_monthly_rate; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Daily Rate".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Daily Rate:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Daily Rate".',') === false ? 'readonly' : ''); ?> name="daily_rate" value="<?php echo $daily_rate; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."HR Rate Work".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">HR Rate Work:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."HR Rate Work".',') === false ? 'readonly' : ''); ?> name="hr_rate_work" value="<?php echo $hr_rate_work; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."HR Rate Travel".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">HR Rate Travel:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."HR Rate Travel".',') === false ? 'readonly' : ''); ?> name="hr_rate_travel" value="<?php echo $hr_rate_travel; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Field Day Cost".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Field Day Cost:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Field Day Cost".',') === false ? 'readonly' : ''); ?> name="field_day_cost" value="<?php echo $field_day_cost; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Field Day Billable".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Field Day Billable:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Field Day Billable".',') === false ? 'readonly' : ''); ?> name="field_day_billable" value="<?php echo $field_day_billable; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Probation Pay Rate".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Probation Pay Rate:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Probation Pay Rate".',') === false ? 'readonly' : ''); ?> name="probation_pay_rate" value="<?php echo $probation_pay_rate; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Base Pay".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Base Pay:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Base Pay".',') === false ? 'readonly' : ''); ?> name="base_pay" value="<?php echo $base_pay; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Performance Pay".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Performance Pay:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Performance Pay".',') === false ? 'readonly' : ''); ?> name="performance_pay" value="<?php echo $performance_pay; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Unit #".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Unit #:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Unit #".',') === false ? 'readonly' : ''); ?> name="unit_no" value="<?php echo $unit_no; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Base Rent".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Base Rent:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Base Rent".',') === false ? 'readonly' : ''); ?> name="base_rent" value="<?php echo $base_rent; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Base Rent/Sq. Ft.".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Base Rent/Sq. Ft.:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Base Rent/Sq. Ft.".',') === false ? 'readonly' : ''); ?> name="base_rent_sq_ft" value="<?php echo $base_rent_sq_ft; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."CAC".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">CAC:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."CAC".',') === false ? 'readonly' : ''); ?> name="cac" value="<?php echo $cac; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."CAC/Sq. Ft.".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">CAC/Sq. Ft.:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."CAC/Sq. Ft.".',') === false ? 'readonly' : ''); ?> name="cac_sq_ft" value="<?php echo $cac_sq_ft; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Property Tax".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Property Tax:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Property Tax".',') === false ? 'readonly' : ''); ?> name="property_tax" value="<?php echo $property_tax; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>

		<?php if (strpos($value_config, ','."Property Tax/Sq. Ft.".',') !== FALSE) { ?>
            <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">Property Tax/Sq. Ft.:</label>
            <div class="col-sm-8">
              <input <?php echo (strpos($edit_config, ','."Property Tax/Sq. Ft.".',') === false ? 'readonly' : ''); ?> name="property_tax_sq_ft" value="<?php echo $property_tax_sq_ft; ?>" type="text" class="form-control">
            </div>
            </div>
        <?php } ?>