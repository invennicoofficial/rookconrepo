<?php // Payment/Invoice Listing
include_once ('../include.php');
if(FOLDER_NAME == 'posadvanced') {
    checkAuthorised('posadvanced');
} else {
    checkAuthorised('check_out');
}
error_reporting(0); ?>
<script type="text/javascript" src="invoice.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.selectall').click(
		function() {
			if($('.selectall').hasClass("deselectall")) {
				$(".selectall").removeClass('deselectall');
				$('.pdf_send').prop('checked', false);
				$(".selectall").text('Select all');
				$('.selectall').prop('title', 'This will select all rows on the current page.');
			} else {
				$(".selectall").addClass('deselectall');
				$('.pdf_send').prop('checked', true);
				$(".selectall").text('Deselect all');
				$('.selectall').prop('title', 'This will deselect all rows on the current page.');
			}

			show_hide_email();
		}
	);

    $('.all_view').click(function(event) {  //on click
		var arr = $('.patientid_for_invoice').val().split('_');
        if(this.checked) { // check select status
            $('.privileges_view_'+arr[1]).each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        } else {
            $('.privileges_view_'+arr[1]).each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });

	$('.iframe_open').click(function(){
			var id = $(this).attr('id');
			var arr = id.split('_');
		    $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Contacts/add_contacts.php?category=Patient&contactid='+arr[0]);
		    $('.iframe_title').text('View Patient');
			$('.hide_on_iframe').hide(1000);
			$('.iframe_holder').show(1000);
	});
	$('.close_iframer').click(function(){
				$('.iframe_holder').hide(1000);
				$('.hide_on_iframe').show(1000);
				location.reload();
	});

});

function changeStatus(sel) {
	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "../Invoice/invoice_ajax.php?action=update_status&invoice="+$(sel).data('invoiceid')+"&status="+sel.value,
		dataType: "html",   //expect html to be returned
		success: function(response){
			window.location.reload();
		}
	});
}

function show_hide_email() {
	var status = $('[name="pdf_send[]"]:checked').length;
	if(status > 0) {
		$('[name=send_email_div]').show();
	} else {
		$('[name=send_email_div]').hide();
	}
}
</script>
<form name="invoice" method="post" action="" class="form-horizontal" role="form">
	<?php $value_config = array_filter(explode(',',get_config($dbc, FOLDER_NAME.'_search_fields')));
	$purchaser_categories = array_filter(array_unique(explode(',', get_config($dbc, 'invoice_purchase_contact'))));
	$rowsPerPage = 10;
	$pageNum = (isset($_GET['page']) ? $_GET['page'] : 1);
	$offset = ($pageNum - 1) * $rowsPerPage;
	$invoices = "`status` NOT IN ('Void','Archived')";
	$search_invoice = 0;
	$search_contact = 0;
	$search_delivery = '';
	$search_from = date('Y-m-d');
	$search_to = date('Y-m-d');
	if (isset($_POST['search_invoice_submit'])) {
		if($_POST['invoiceid'] != '') {
		   $search_invoice = $_POST['invoiceid'];
		   $invoices .= " AND `invoiceid`='$search_invoice'";
		}
		if($_POST['contactid'] != '') {
		   $search_contact = $_POST['contactid'];
		   $invoices .= " AND `patientid`='$search_contact'";
		}
		if($_POST['type'] != '') {
		   $search_delivery = $_POST['type'];
		   $invoices .= " AND `delivery_type`='$search_delivery'";
		}
	   $search_from = $_POST['search_from'];
	   $search_to = $_POST['search_to'];
	}
	if($search_from != '') {
	   $invoices .= " AND `invoice_date`>='$search_from'";
	}
	if($search_to != '') {
	   $invoices .= " AND `invoice_date`<='$search_to'";
	} ?>
	<div class="col-sm-12">
		<?php if(count($value_config) == 0 || in_array('Invoice #', $value_config)) { ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						Search By Invoice #:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="contactid" placeholder="Enter Invoice #..." class="form-control">
				</div>
			</div>
		<?php } ?>
		<?php if(count($value_config) == 0 || in_array('Contact Name', $value_config)) { ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						Search By <?= count($purchaser_categories) > 1 ? 'Contact' : $purchaser_categories[0] ?>:</label>
				</div>
				<div class="col-sm-8">
					<select name="contactid" data-placeholder="Select <?= count($purchaser_categories) > 1 ? 'Contact' : $purchaser_categories[0] ?>..." class="chosen-select-deselect form-control width-me">
						<option value=''></option>
						<?php
						$result = sort_contacts_query(mysqli_query($dbc, "SELECT contactid, first_name, last_name FROM contacts WHERE `contactid` IN (SELECT `patientid` FROM `invoice`) AND `deleted`=0 AND `status`>0"));
						foreach($result as $row) {
							echo "<option ".($search_contact == $row['contactid'] ? 'selected' : '')." value = '".$row['contactid']."'>".$row['first_name'].' '.$row['last_name']."</option>";
						}
					   ?>
					</select>
				</div>
			</div>
		<?php } ?>
		<?php if(count($value_config) == 0 || in_array('Delivery Type', $value_config)) { ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						Search By Delivery/Shipping Type:</label>
				</div>
				<div class="col-sm-8">
					<select name="type" data-placeholder="Select Delivery/Shipping Type..." class="chosen-select-deselect form-control width-me">
						<option value=''></option>
						<option <?= ($search_delivery == 'Pick-Up' ? 'selected' : '') ?> value="Pick-Up">Pick-Up</option>
						<option <?= ($search_delivery == 'Company Delivery' ? 'selected' : '') ?> value="Company Delivery">Company Delivery</option>
						<option <?= ($search_delivery == 'Drop Ship' ? 'selected' : '') ?> value="Drop Ship">Drop Ship</option>
						<option <?= ($search_delivery == 'Shipping' ? 'selected' : '') ?> value="Shipping">Shipping</option>
						<option <?= ($search_delivery == 'Shipping on Customer Account' ? 'selected' : '') ?> value="Shipping on Customer Account">Shipping on Customer Account</option>
					</select>
				</div>
			</div>
		<?php } ?>
		<?php if(count($value_config) == 0 || in_array('Invoice Date', $value_config)) { ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						Search From Date:</label>
				</div>
				<div class="col-sm-8">
					<input name="search_from" type="text" class="datepicker form-control" value="<?= $search_from ?>">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">
						Search To Date:</label>
				</div>
				<div class="col-sm-8">
					<input name="search_to" type="text" class="datepicker form-control" value="<?= $search_to ?>">
				</div>
			</div>
		<?php } ?>
		<button type="submit" class="btn brand-btn" name="search_invoice_submit" value="search">Search</button>
		<a class="btn brand-btn" href="">Display All</a>
	</div>
	<div class="clearfix"></div>
	<?php $invoice_list = mysqli_query($dbc, "SELECT * FROM `invoice` WHERE $invoices AND `invoice_type`='New' AND `deleted`=0 ORDER BY `invoiceid` DESC LIMIT $offset, $rowsPerPage");
	if(mysqli_num_rows($invoice_list) > 0) {
		echo display_pagination($dbc, "SELECT COUNT(*) numrows FROM `invoice` WHERE $invoices AND `deleted`=0", $pageNum, $rowsPerPage);
		while($invoice = mysqli_fetch_array($invoice_list)) { ?>
			<div class="dashboard-item">
				<h4><a href="?edit=<?= $projectid ?>&tab=billing_details&billing=<?= $invoice['invoiceid'] ?>">Invoice #<?= $invoice['invoiceid'] ?></a>
					<div class="toggle-switch form-group pull-right"><input type="hidden" name="paid" data-table="invoice" data-id-field="invoiceid" data-id="<?= $invoice['invoiceid'] ?>" value="<?= $invoice['paid'] ?>">Paid: 
						<img src="<?= WEBSITE_URL ?>/img/icons/switch-6.png" style="height: 2em; <?= $invoice['paid'] == 'Yes' ? 'display: none;' : '' ?>">
						<img src="<?= WEBSITE_URL ?>/img/icons/switch-7.png" style="height: 2em; <?= $invoice['paid'] == 'Yes' ? '' : 'display: none;' ?>"></div></h4>
				<div class="col-sm-6">
					<?php if($invoice['businessid'] > 0) { ?>
						<?php $business = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='{$invoice['businessid']}'")); ?>
						<div class="form-group">
							<label class="col-sm-4"><?= $business['category'] ?>:</label>
							<div class="col-sm-8">
								<?php if($business['name'] != '') {
									echo decryptIt($business['name']);
								} else {
									echo decryptIt($business['first_name']).' '.decryptIt($business['last_name']);
								} ?>
							</div>
						</div>
					<?php } ?>
					<?php $client = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid`='{$invoice['patientid']}'")); ?>
					<div class="form-group">
						<label class="col-sm-4"><?= $client['category'] ?>:</label>
						<div class="col-sm-8">
							<?php if($client['first_name'] != '') {
								echo decryptIt($client['first_name']).' '.decryptIt($client['last_name']);
							} else {
								echo decryptIt($client['name']);
							} ?>
						</div>
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label class="col-sm-4">Total Due:</label>
					<div class="col-sm-8">
						$<?= number_format($invoice['final_price'],2) ?>
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label class="col-sm-4">Invoice Date:</label>
					<div class="col-sm-8">
						<?= $invoice['invoice_date'] ?>
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label class="col-sm-4">PDF:</label>
					<div class="col-sm-8">
						<?php if(file_exists('../Invoice/Download/invoice_'.$invoiceid.'.pdf')) { ?>
							<a href="<?= WEBSITE_URL ?>/Invoice/Download/invoice_<?= $invoice['invoiceid'] ?>.pdf">Invoice <img class="inline-img" src="<?= WEBSITE_URL ?>/img/pdf.png"></a>
							<a href="<?= WEBSITE_URL ?>/Invoice/patient_invoice_pdf.php?action=build&invoiceid=<?= $invoice['invoiceid'] ?>" class="smaller">(Generate)</a>
						<?php } else { ?>
							<a href="<?= WEBSITE_URL ?>/Invoice/patient_invoice_pdf.php?action=build&invoiceid=<?= $invoice['invoiceid'] ?>">Generate Invoice <img class="inline-img" src="<?= WEBSITE_URL ?>/img/pdf.png"></a>
						<?php } ?><br />
						<?php $payments = mysqli_query($dbc, "SELECT * FROM `invoice_payment` WHERE `invoiceid`='{$invoice['invoiceid']}' AND `payer_id`=`contactid` AND `paid` > 0 AND `deleted`=0");
						while($payment = mysqli_fetch_array($payments)) {
							if(file_exists('../Invoice/Download/receipt_'.$payment['id'].'.pdf')) { ?>
								<a href="../Invoice/Download/receipt_<?= $payment['id'] ?>.pdf">Receipt #<?= $payment['id'] ?></a>
							<?php } else { ?>
								<a href="../Invoice/payment_receipt_pdf.php?action=build&payment=<?= $payment['id'] ?>.pdf">Receipt #<?= $payment['id'] ?></a>
							<?php }
						}
						if(file_exists('../Invoice/Download/patientreceipt_'.$invoice['invoiceid'].'.pdf')) { ?>
							<a href="../Invoice/Download/patientreceipt_<?= $invoice['invoiceid'] ?>.pdf">Patient Receipt</a>
						<?php } ?>
					</div>
				</div>
				<div class="form-group col-sm-6">
					<label class="col-sm-4">Status:</label>
					<div class="col-sm-8">
						<select name="status" data-id-field="invoiceid" data-id="<?= $invoice['invoiceid'] ?>" data-table="invoice" data-placeholder="Select a status" class="chosen-select-deselect form-control">
							<option value=""></option>
							<option value="Sent to Customer" <?php if ($invoice['status'] == "Sent to Customer") { echo " selected"; } ?> >Sent to Customer</option>
							<option value="Posted" <?php if ($invoice['status'] == "Posted") { echo " selected"; } ?> >Posted</option>
							<option value="Posted Past Due" <?php if ($invoice['status'] == "Posted Past Due") { echo " selected"; } ?> >Posted Past Due</option>
							<option value="Completed" <?php if ($invoice['status'] == "Completed") { echo " selected"; } ?> >Completed</option>
							<option value="Void" <?php if ($invoice['status'] == "Void") { echo " selected"; } ?> >Void</option>
							<option value="Archived" <?php if ($invoice['status'] == "Archived") { echo " selected"; } ?> >Archive</option>
						</select>
					</div>
				</div>
				<?php if(file_exists(WEBSITE_URL.'/Invoice/Download/invoice_'.$invoiceid.'.pdf')) { ?>
					<a href="<?= WEBSITE_URL ?>/Invoice/Download/invoice_<?= $invoice['invoiceid'] ?>.pdf" class="btn brand-btn pull-right">Export PDF</a>
				<?php } else { ?>
					<a href="<?= WEBSITE_URL ?>/Invoice/patient_invoice_pdf.php?action=build&invoiceid=<?= $invoice['invoiceid'] ?>" class="btn brand-btn pull-right">Export PDF</a>
				<?php } ?>
				<div class="clearfix"></div>
			</div>
		<?php }
		echo display_pagination($dbc, "SELECT COUNT(*) numrows FROM `invoice` WHERE $invoices AND `deleted`=0", $pageNum, $rowsPerPage);
	} else {
		echo "<h2>No ".$tab_label." Found</h2>";
	} ?>
	
	<div name="send_email_div" class="form-horizontal" style="display:none;">
		<div class="form-group">
			<label class="col-sm-4 control-label">Sending Email Address</label>
			<div class="col-sm-8"><input type="text" class="form-control" name="sender" value="<?php echo get_email($dbc, $_SESSION['contactid']); ?>"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label" for="customer">Send to Customer</label>
			<div class="col-sm-8"><input type="checkbox" checked class="" id="customer" name="customer" value="customer" style="height:1.5em;width:1.5em;"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Additional Recipient Email Addresses<br /><em>(separate multiple emails using a comma and no spaces)</em></label>
			<div class="col-sm-8"><input type="text" class="form-control" name="recipient" value=""></div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Subject</label>
			<div class="col-sm-8"><input type="text" class="form-control" name="subject" value="See the attached Invoice"></div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label">Email Body</label>
			<div class="col-sm-8"><textarea name="body">Please see the attached PDF(s) below.</textarea></div>
		</div>
		<button class="btn brand-btn pull-right" type="submit" name="send_email" value="send">Send Email</button>
	</div>

</form>