<!-- Order Details Content. Included in order_details.php -->
<?php
$lead_created_by        = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']);
$primary_staff          = $_SESSION['contactid'];
$share_lead             = '';
$businessid             = '';
$contactid              = '';
$primary_number         = '';
$email_address          = '';
$lead_value             = '';
$estimated_close_date   = '';
$serviceid              = '';
$productid              = '';
$marketingmaterialid    = '';
$lead_source            = '';
$next_action            = '';
$new_reminder           = '';
$status                 = '';

if ( !empty($sotid) ) {
    $get_sot = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `sales_order_temp` WHERE `sotid` = '$sotid'"));

    $so_type = $get_sot['so_type'];
    $customerid = $get_sot['customerid'];
    $security_option = $get_sot['security_option'];
    $discount_type = $get_sot['discount_type'];
    $discount_value = $get_sot['discount_value'];
    $delivery_type = $get_sot['delivery_type'];
    $delivery_address = $get_sot['delivery_address'];
    $contractorid = $get_sot['contractirid'];
    $delivery_amount = $get_sot['delivery_amount'];
    $assembly_amount = $get_sot['assembly_amount'];
    $payment_type = $get_sot['payment_type'];
    $deposit_paid = $get_sot['deposit_paid'];
    $comment = $get_sot['comment'];
    $ship_date = $get_sot['ship_date'];
    $due_date = $get_sot['due_date'];
}
?>

<input type="hidden" id="sotid" name="sotid" value="<?= $sotid ?>">

<div class="standard-body main-screen-white" style="padding-left: 0; padding-right: 0; border: none;">
    <div class="standard-body-title">
        <h3 id="order_details_header">Order Details</h4>
    </div>

    <div class="standard-body-content">
        <?php if (strpos($value_config, ',Custom Designs,') !== FALSE) { ?>
            <div class="order_detail_contact padded" data-contactid="custom_designs" style="display:none;">
                <?php include('details_design.php'); ?>
            </div>
        <?php } ?>

        <?php include('order_details_content_details.php'); ?>

        <?php if (strpos($value_config, ',Notes,') !== FALSE) { ?>
            <div id="add_note">
    			<div class="form-group">
    				<label class="col-sm-4">Note:<br /><em>Send this comment <input type="checkbox" name="send_note_email" value="send" onchange="show_email_fields();"></em></label>
    				<div class="col-sm-8">
    					<textarea name="note_text"></textarea>
    				</div>
    			</div>
    			<div class="note-email" style="display:none;">
    				<div class="form-group">
    					<label class="col-sm-4">Send Email To:</label>
    					<div class="col-sm-8">
    						<select class="chosen-select-deselect" data-placeholder="Select a recipient" name="note_email_to"><option></option>
    							<?php foreach(sort_contacts_query(mysqli_query($dbc, "SELECT `last_name`, `first_name`, `category`, `contactid` FROM `contacts` WHERE (`category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." OR (`businessid`='$businessid' AND `businessid` > 0)) AND `status`>0 AND `deleted`=0")) as $recipient) { ?>
    								<option value="<?= $recipient['contactid'] ?>"><?= $recipient['first_name'].' '.$recipient['last_name'] ?></option>
    							<?php } ?>
    						</select>
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-4">Sending Email Name:</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>" name="note_email_name">
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-4">Sending Email Address:</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" value="<?= get_email($dbc, $_SESSION['contactid']) ?>" name="note_email_address">
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-4">Email Subject:</label>
    					<div class="col-sm-8">
    						<input type="text" class="form-control" value="Note regarding <?= SALES_ORDER_NOUN ?>" name="note_email_subject">
    					</div>
    				</div>
    				<div class="form-group">
    					<label class="col-sm-4">Email Body:</label>
    					<div class="col-sm-8">
    						<textarea name="note_email_body">
    							<p>A note has been added for you on a <?= SALES_ORDER_NOUN ?>:<br />[REFERENCE]</p>
    							<p>Please <a href="<?= WEBSITE_URL ?>/Sales Order/order_details.php?sotid=<?= $sotid ?>">click here</a> to review the <?= SALES_ORDER_NOUN ?>.</p>
    						</textarea>
    					</div>
    				</div>
    			</div>
            </div>
			<script>
			function show_email_fields() {
				if($('[name=send_note_email]').is(':checked')) {
					$('.note-email').show();
				} else {
					$('.note-email').hide();
				}
			}
			</script>
        <?php } ?>
    </div>
</div><!-- .main-screen-white -->