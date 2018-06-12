<?php include_once('../include.php');
checkAuthorised('sales_order');
$sotid = $_GET['sotid'];

if(isset($_POST['submit_customer'])) {
    $business_region         = filter_var($_POST['business_region'],FILTER_SANITIZE_STRING);
    $business_location       = filter_var($_POST['business_location'],FILTER_SANITIZE_STRING);
    $business_classification = filter_var($_POST['business_classification'],FILTER_SANITIZE_STRING);

    $customer_cat = filter_var($_POST['customer_cat'],FILTER_SANITIZE_STRING);
    $name = encryptIt(filter_var($_POST['new_name'],FILTER_SANITIZE_STRING));
    $first_name = encryptIt(filter_var($_POST['new_first_name'],FILTER_SANITIZE_STRING));
    $last_name = encryptIt(filter_var($_POST['new_last_name'],FILTER_SANITIZE_STRING));
    $address = filter_var($_POST['new_address'],FILTER_SANITIZE_STRING);
    $phone_number = encryptIt(filter_var($_POST['new_number'],FILTER_SANITIZE_STRING));
    $email_address = encryptIt(filter_var($_POST['new_email'],FILTER_SANITIZE_STRING));
    $payment_type = filter_var($_POST['new_payment_type'],FILTER_SANITIZE_STRING);
    $budget = filter_var($_POST['new_budget'],FILTER_SANITIZE_STRING);
    $preferred_booking_time = filter_var(htmlentities(nl2br($_POST['new_preferred_booking_time'])),FILTER_SANITIZE_STRING);
    $square_footage = filter_var($_POST['new_square_footage'],FILTER_SANITIZE_STRING);
    $num_bathrooms = filter_var($_POST['new_num_bathrooms'],FILTER_SANITIZE_STRING);
    $alarm = filter_var(htmlentities(nl2br($_POST['new_alarm'])),FILTER_SANITIZE_STRING);
    $pets = filter_var(htmlentities(nl2br($_POST['new_pets'])),FILTER_SANITIZE_STRING);
    $notification_type = filter_var($_POST['new_notification_type'],FILTER_SANITIZE_STRING);
    $booking_extra = filter_var(htmlentities(nl2br($_POST['new_extra'])),FILTER_SANITIZE_STRING);

    $query_insert = "INSERT INTO `contacts` (`category`, `name`, `first_name`, `last_name`, `address`, `office_phone`, `email_address`, `payment_type`, `budget`, `preferred_booking_time`, `location_square_footage`, `location_num_bathrooms`, `location_alarm`, `location_pets`, `notification_type`, `booking_extra`) VALUES ('$customer_cat', '$name', '$first_name', '$last_name', '$address', '$phone_number', '$email_address', '$payment_type', '$budget', '$preferred_booking_time', '$square_footage', '$num_bathrooms', '$alarm', '$pets', '$notification_type', '$booking_extra')";
    $result_insert = mysqli_query($dbc, $query_insert);
    $businessid = mysqli_insert_id($dbc);

    $query_update  = "UPDATE `contacts` SET `businessid`='$businessid', `region`='$business_region', `con_locations`='$business_location', `classification`='$business_classification'  WHERE `contactid`='$businessid'";
    $result_update = mysqli_query($dbc, $query_update);

    $option_html = '<option data-category="'.get_contact($dbc, $businessid, 'category').'" selected value="'. $businessid .'">'. (!empty(get_client($dbc, $businessid)) ? get_client($dbc, $businessid) : get_contact($dbc, $businessid)) .'</option>';

    echo '<script> window.parent.$("#task_businessid").append(\''.$option_html.'\'); window.parent.$("#task_businessid").trigger("change"); </script>';
}

$field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `field_config_so`"));
$customer_cat = $_GET['category'];
$customer_fields = ','.$field_config['customer_fields'].',';
?>

<div id="new_business" class="gap-left gap-right">
	<h3>Add Customer</h3>
	<form action="" method="post">
		<input type="hidden" name="customer_cat" value="<?= $_GET['category'] ?>">
	    <?php if(strpos($customer_fields, ',Business Name,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Name:</label>
	            <div class="col-sm-8"><input name="new_business" type="text" class="form-control" /></div>
	            <div class="clearfix"></div>
	        </div><?php
	    } ?>
	    
	    <?php if(strpos($customer_fields, ',First Name,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">First Name:</label>
	            <div class="col-sm-8"><input name="new_first_name" type="text" class="form-control" /></div>
	            <div class="clearfix"></div>
	        </div><?php
	    } ?>
	    
	    <?php if(strpos($customer_fields, ',Last Name,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Last Name:</label>
	            <div class="col-sm-8"><input name="new_last_name" type="text" class="form-control" /></div>
	            <div class="clearfix"></div>
	        </div><?php
	    } ?>
	    
	    <?php $get_regions = array_unique(array_filter(explode(',', mysqli_fetch_assoc(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') AS `regions_list` FROM `general_configuration` WHERE `name` LIKE '%_region'"))['regions_list'])));
	    if ( count($get_regions) > 0 && strpos($customer_fields, ',Region,') !== FALSE ) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Region:</label>
	            <div class="col-sm-8">
	                <select data-placeholder="Select a Region..." name="business_region" class="chosen-select-deselect">
	                    <option value=""></option><?php
	                    foreach ($get_regions as $cat_tab) {
	                        $selected = ( $business_region==$cat_tab ) ? 'selected="selected"' : '';
	                        echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
	                    } ?>
	                </select>
	            </div>
	            <div class="clearfix"></div>
	        </div><?php
	    } ?>
	    
	    <?php $get_locations = array_filter(array_unique(explode(',', mysqli_fetch_array(mysqli_query($dbc, "SELECT GROUP_CONCAT(DISTINCT `con_locations` SEPARATOR ',') FROM `field_config_contacts`"))[0])));
	    if ( count($get_locations) > 0 && strpos($customer_fields, ',Location,') !== FALSE ) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Location:</label>
	            <div class="col-sm-8">
	                <select data-placeholder="Select a Location..." name="business_location" class="chosen-select-deselect">
	                    <option value=""></option><?php
	                    foreach ($get_locations as $cat_tab) {
	                        $selected = ( $business_location==$cat_tab ) ? 'selected="selected"' : '';
	                        echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
	                    } ?>
	                </select>
	            </div>
	            <div class="clearfix"></div>
	        </div><?php
	    } ?>
	    
	    <?php $get_classifications = array_unique(array_filter(explode(',', mysqli_fetch_assoc(mysqli_query($dbc, "SELECT GROUP_CONCAT(`value` SEPARATOR ',') AS `classifications_list` FROM `general_configuration` WHERE `name` LIKE '%_classification'"))['classifications_list'])));
	    if ( count($get_classifications) > 0 && strpos($customer_fields, ',Classification,') !== FALSE ) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Classification:</label>
	            <div class="col-sm-8">
	                <select data-placeholder="Select a Classification..." name="business_classification" class="chosen-select-deselect">
	                    <option value=""></option><?php
	                    foreach ($get_classifications as $cat_tab) {
	                        $selected = ( $business_classification==$cat_tab ) ? 'selected="selected"' : '';
	                        echo '<option '. $selected .' value="'. $cat_tab .'">'. $cat_tab .'</option>';
	                    } ?>
	                </select>
	            </div>
	            <div class="clearfix"></div>
	        </div><?php
	    } ?>

	    <?php if(strpos($customer_fields, ',Address,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Address:</label>
	            <div class="col-sm-8"><input name="new_address" type="text" class="form-control" /></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Phone Number,') !== FALSE) { ?>
	        <div class="form-group" id="new_number">
	            <label class="col-sm-4 control-label">Phone Number:</label>
	            <div class="col-sm-8"><input name="new_number" type="text" class="form-control" /></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Email Address,') !== FALSE) { ?>
	        <div class="form-group" id="new_email">
	            <label class="col-sm-4 control-label">Email Address:</label>
	            <div class="col-sm-8"><input name="new_email" type="text" class="form-control" /></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Payment Type,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Preffered Payment Type:</label>
	            <div class="col-sm-8">
	                <select name="new_payment_type" data-placeholder="Choose a Type..." class="chosen-select-deselect form-control" width="380">
	                  <option value=""></option>
	                  <?php
	                    $tabs = get_config($dbc, 'sales_order_invoice_payment_types');
	                    $each_tab = explode(',', $tabs);
	                     if (is_array($each_tab) && count($each_tab) > 0) {
	                        foreach ($each_tab as $cat_tab) {
	                            echo "<option value='". $cat_tab."'>".$cat_tab.'</option>';
	                        }
	                     } else {
	                         echo "<option value='Pay Now'>Pay Now</option>";
	                         echo "<option value='Net 30'>Net 30</option>";
	                         echo "<option value='Net 60'>Net 60</option>";
	                         echo "<option value='Net 90'>Net 90</option>";
	                         echo "<option value='Net 120'>Net 120</option>";
	                     }
	                  ?>
	                </select>
	            </div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Budget,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Budget:</label>
	            <div class="col-sm-8"><input name="new_budget" type="number" class="form-control" step="0.01" /></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Preferred Booking Time,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Preferred Booking Time:</label>
	            <div class="col-sm-8"><textarea name="new_preferred_booking_time" class="form-control" style="resize: vertical; height: 5em;"></textarea></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Square Footage,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Square Footage:</label>
	            <div class="col-sm-8"><input name="new_square_footage" type="text" class="form-control" /></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Number of Bathrooms,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Number of Bathrooms:</label>
	            <div class="col-sm-8"><input name="new_num_bathrooms" type="text" class="form-control" step="0.01" /></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Alarm System Information,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Alarm System Information:</label>
	            <div class="col-sm-8"><textarea name="new_alarm" class="form-control" style="resize: vertical; height: 5em;"></textarea></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Pets,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Pets:</label>
	            <div class="col-sm-8"><textarea name="new_pets" class="form-control" style="resize: vertical; height: 5em;"></textarea></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Notification Type,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Notification Type:</label>
	            <div class="col-sm-8"><input name="new_notification_type" type="text" class="form-control" step="0.01" /></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>

	    <?php if(strpos($customer_fields, ',Extra Information,') !== FALSE) { ?>
	        <div class="form-group">
	            <label class="col-sm-4 control-label">Extra Information:</label>
	            <div class="col-sm-8"><textarea name="new_extra" class="form-control" style="resize: vertical; height: 5em;"></textarea></div>
	            <div class="clearfix"></div>
	        </div>
	    <?php } ?>
	    <div class="clearfix"></div>

	    <div class="form-group pull-right gap-top">
		    <a href="?" class="btn brand-btn">Cancel</a>
		    <button type="submit" name="submit_customer" value="Submit" class="btn brand-btn">Submit</button>
	    </div>
	</form>
</div><!-- #new_business -->
