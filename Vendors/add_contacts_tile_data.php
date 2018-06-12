<?php if (strpos($value_config, ','."Client Support Plan".',') !== FALSE) { ?>
	<h3>Individual Service Plan (ISP)</h3>
	<div class="form-group">
		<?php $display_contact = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Individual Support Plan/support_plan_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Medications Client Profile".',') !== FALSE) { ?>
	<h3>Medications</h3>
	<div class="form-group">
		<?php $display_contact = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Medication/medication_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Client Medical Charts".',') !== FALSE) { ?>
	<?php include_once '../Medical Charts/config.php';
	$display_contact = $contactid;
	$return_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab); ?>
	<h3>Bowel Movement</h3>
	<div class="form-group">
		<?php include_once('../Medical Charts/bowel_movement_list.php'); ?>
	</div>
	<h3>Seizure Record</h3>
	<div class="form-group">
		<?php include_once('../Medical Charts/seizure_record_list.php'); ?>
	</div>
	<h3>Daily Water Temp</h3>
	<div class="form-group">
		<?php include_once('../Medical Charts/daily_water_temp_list.php'); ?>
	</div>
	<h3>Blood Glucose</h3>
	<div class="form-group">
		<?php include_once('../Medical Charts/blood_glucose_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Client Daily Log Notes".',') !== FALSE) { ?>
	<h3>Daily Log Notes</h3>
	<div class="form-group">
		<?php $display_contact = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Daily Log Notes/log_note_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Client Activities Social Story".',') !== FALSE) { ?>
	<h3>Activities</h3>
	<div class="form-group">
		<?php include_once('../Social Story/config.php');
		$search_client = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Social Story/activities_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Client Communication Social Story".',') !== FALSE) { ?>
	<h3>Communication</h3>
	<div class="form-group">
		<?php include_once('../Social Story/config.php');
		$search_client = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Social Story/communication_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Client Routines Social Story".',') !== FALSE) { ?>
	<h3>Routines</h3>
	<div class="form-group">
		<?php include_once('../Social Story/config.php');
		$search_client = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Social Story/routines_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Client Protocols Social Story".',') !== FALSE) { ?>
	<h3>Protocols</h3>
	<div class="form-group">
		<?php include_once('../Social Story/config.php');
		$search_client = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Social Story/protocols_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Client Key Methodologies Social Story".',') !== FALSE) { ?>
	<h3>Key Methodologies</h3>
	<div class="form-group">
		<?php include_once('../Social Story/config.php');
		$search_client = $contactid;
		$from_url = urlencode(WEBSITE_URL.'/'.FOLDER_URL.'/add_contacts.php?category='.$url_category.'&contactid='.$contactid.'&subtab='.$subtab);
		include_once('../Social Story/key_methodologies_list.php'); ?>
	</div>
<?php } ?>
<?php if (strpos($value_config, ','."Patient Block Booking".',') !== FALSE) { ?>
    <h3><?= get_contact($dbc, $contactid) ?> Block Booking</h3>
	<div id="no-more-tables">
		<table border="1px" class="table table-bordered">
			<tr class="hidden-sm hidden-xs">
				<th>Appointment Date, Time &amp; Day</th>
				<th>Staff</th>
				<th>Injury</th>
				<th>Status</th>
			</tr>
			<?php $patient_bb = mysqli_query($dbc, "SELECT appoint_date, end_appoint_date, bookingid, injuryid, follow_up_call_status, therapistsid FROM booking WHERE deleted=0 AND patientid = '$contactid' AND '$contactid' != '' AND (str_to_date(substr(appoint_date,1,10),'%Y-%m-%d')) >= DATE(NOW()) ORDER BY appoint_date");
			while($row_bb = mysqli_fetch_array( $patient_bb ))
			{
				$appoint_date = explode(' ', $row_bb['appoint_date']);
				echo '<tr nobr="true">';
				echo '<td>'.$row_bb['appoint_date'].' : '.date("l", strtotime($appoint_date[0])).'</td>';
				echo '<td>'.get_contact($dbc, $row_bb['therapistsid']).'</td>';
				echo  '<td>' . get_all_from_injury($dbc, $row_bb['injuryid'], 'injury_name').' : '.get_all_from_injury($dbc, $row_bb['injuryid'], 'injury_type') . '</td>';
				echo  '<td>' . $row_bb['follow_up_call_status'] . '</td>';
				echo '</tr>';
			} ?>

		</table>
	</div>
<?php } ?>
<?php 
	if (strpos($value_config, ','."Medical Details Medications".',') !== FALSE) {
	    include('../Members/add_medications.php');
	}
?>
<?php
	if (strpos($value_config, ','."Checklist".',') !== FALSE && $subtab == 'Summary') {
		if (empty($_GET['view']) && empty($_GET['edit'])) {
		echo '<div class="pull-right not_filter">
	            <a href="'.$_SERVER['REQUEST_URI'].'&edit=NEW" class="btn brand-btn mobile-block gap-bottom pull-right">Add Checklist</a>
	            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a Checklist."><img src="'.WEBSITE_URL.'/img/info.png" width="20"></a></span>
	        </div>';
	    }
		echo '<div class="main-screen col-sm-9">';
		if (!empty($_GET['view'])) {
			include_once('view_checklist.php');
		} else if (!empty($_GET['edit'])) {
			include_once('edit_checklist.php');
		} else {
			include_once('list_checklists.php');
		}
		echo '</div>';
	}
?>
<?php if (strpos($value_config, ','."Account Statement".',') !== FALSE) { ?>
	<script>
	function update_statement_table() {
		var injuryid = $('[name=statement_search_injury]').val();
		var from_date = $('[name=statement_search_from]').val();
		var to_date = $('[name=statement_search_to]').val();
		var options = $('[name="statement_options[]"]:checked').map( function() { return this.value; }).get().join(",");
		$.ajax({
			url: '../Contacts/add_contact_ajax.php?fill=statement',
			method: 'POST',
			data: { contact: '<?= $contactid ?>', injury: injuryid, from: from_date, to: to_date, option_list: options },
			success: function(response) {
				$('#statement_table_body').html(response);
			}
		});
	}
	function reset_statement_table() {
		$('[name=statement_search_injury] option:selected').removeAttr('selected');
		$('[name=statement_search_injury]').trigger('change.select2');
		$('[name=statement_search_from]').val('');
		$('[name=statement_search_to]').val('');
		$('[name="statement_options[]"]:checked').removeAttr('checked');
		$('[name="statement_options[]"][value="outstanding"]').prop('checked','checked');
		$('[name="statement_options[]"][value="paid"]').prop('checked','checked');
		$('[name="statement_options[]"][value="payments"]').prop('checked','checked');
		update_statement_table();
	}
	function print_statement_table() {
		var injuryid = $('[name=statement_search_injury]').val();
		var from_date = $('[name=statement_search_from]').val();
		var to_date = $('[name=statement_search_to]').val();
		var options = $('[name="statement_options[]"]:checked').map( function() { return this.value; }).get().join(",");
		$.ajax({
			url: '../Contacts/add_contact_ajax.php?fill=statement_pdf',
			method: 'POST',
			data: { contact: '<?= $contactid ?>', injury: injuryid, from: from_date, to: to_date, option_list: options },
			success: function(response) {
				window.open(response);
			}
		});
	}
	$(document).ready(function() {
		update_statement_table();
	});
	</script>
    <h3><?= get_contact($dbc, $contactid) ?> Account Statement</h3>
	<div class="search-group">
		<div class="form-group col-lg-9 col-md-8 col-sm-12 col-xs-12">
			<!--<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to search by Injury."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						Search By Injury:</label>
				</div>
				<div class="col-sm-8">
					<select data-placeholder="Select an Injury" name="statement_search_injury" class="chosen-select-deselect form-control">
						<option></option>
						<?php $query = mysqli_query($dbc,"SELECT * FROM `patient_injury` WHERE `contactid`='$contactid' AND '$contactid' != '' AND `deleted`=0");
						while($statement_injury = mysqli_fetch_array($query)) { ?>
							<option value='<?= $statement_injury['injuryid'] ?>' ><?= $statement_injury['injury_type'].': '.$statement_injury['injury_name'].' ('.$statement_injury['injury_date'].')' ?></option><?php
						} ?>
					</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see select the start date for the statement."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						Search From Date:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="statement_search_from" class="form-control datepicker" value="">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to see select the end date for the statement."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						Search To Date:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="statement_search_to" class="form-control datepicker" value="">
				</div>
			</div>-->
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Check the options to display for the statement."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						Include Invoices:</label>
				</div>
				<div class="col-sm-8">
					<label class="form-checkbox"><input type="checkbox" name="statement_options[]" value="saved"> Include Saved (Unbilled) Invoices</label>
					<label class="form-checkbox"><input type="checkbox" checked name="statement_options[]" value="outstanding"> Include Oustanding Invoices</label>
					<label class="form-checkbox"><input type="checkbox" checked name="statement_options[]" value="paid"> Include Paid Invoices</label>
					<label class="form-checkbox"><input type="checkbox" checked name="statement_options[]" value="payments"> Show Payment Transactions</label>
					<label class="form-checkbox"><input type="checkbox" checked name="statement_options[]" value="insurer"> Show Insurer Transactions</label>
					<!--<label class="form-checkbox"><input type="checkbox" name="statement_options[]" value="last_statement"> Only Show Invoices Since Last PDF Statement</label>-->
				</div>
			</div>
		</div>
		<div class="form-group col-lg-3 col-md-4 col-sm-12 col-xs-12">
			<div style="display:inline-block; padding: 0 0.5em;">
				<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here after you have selected your options."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button name="statement_update" value="Search" class="btn brand-btn mobile-block" onclick="update_statement_table(); return false;">Search</button>
				<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click to refresh the table and display the full invoice."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button name="statement_reset" value="Display All" class="btn brand-btn mobile-block" onclick="reset_statement_table(); return false;">Display All</button><br />
				<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click to download the PDF of the Account Statement."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button name="statement_pdf" value="PDF" class="btn brand-btn mobile-block" onclick="print_statement_table(); return false;">Print Statement</button>
			</div>
		</div><!-- .form-group -->
		<div class="clearfix"></div>
	</div>
	<div id="no-more-tables">
		<table border="1px" class="table table-bordered">
			<tr class="hidden-sm hidden-xs">
				<th>Transaction Date</th>
				<th>Staff</th>
				<th>Injury</th>
				<th>Services</th>
				<th><?= $category ?></th>
				<th>Payer</th>
				<th>Payment</th>
				<th>Balance</th>
			</tr>
			<tbody id="statement_table_body">
			</tbody>
		</table>
	</div>
<?php } ?>