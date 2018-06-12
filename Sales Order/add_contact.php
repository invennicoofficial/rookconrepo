<?php include_once('../include.php');
checkAuthorised('sales_order');
$sotid = $_GET['sotid'];

if(isset($_POST['submit_contact'])) {
	$businessid = $_POST['contact_businessid'];
    $first_name = encryptIt($_POST['new_contact_first_name']);
    $last_name = encryptIt($_POST['new_contact_last_name']);
    $phone_number = encryptIt($_POST['new_contact_phone_number']);
    $email_address = encryptIt($_POST['new_contact_email_address']);

    $query_insert = "INSERT INTO `contacts` (`category`, `first_name`, `last_name`, `office_phone`, `email_address`, `businessid`) VALUES ('', '$first_name', '$last_name', '$phone_number', '$email_address', '$businessid')";
    $result_insert = mysqli_query($dbc, $query_insert);
    $business_contact = mysqli_insert_id($dbc);

    $option_html = '<option selected value="'. $business_contact .'">'. get_contact($dbc, $business_contact) .'</option>';

    echo '<script> window.parent.$("#current_business_contact").append(\''.$option_html.'\'); window.parent.$("#current_business_contact").trigger("change");
    	window.parent.$("#current_business_contact").removeAttr("id"); </script>';
}
?>

<div class="new_contact gap-left gap-right">
	<h3>Add Contact</h3>
	<form action="" method="post">
		<input type="hidden" name="contact_businessid" value="<?= $_GET['businessid'] ?>">
	    <div class="form-group">
	        <label class="col-sm-4 control-label">New Contact First Name:</label>
	        <div class="col-xs-12 col-sm-8"><input name="new_contact_first_name" type="text" class="form-control" /></div>
            <div class="clearfix"></div>
	    </div>
	    <div class="form-group">
	        <label class="col-sm-4 control-label">New Contact Last Name:</label>
	        <div class="col-xs-12 col-sm-8"><input name="new_contact_last_name" type="text" class="form-control" /></div>
            <div class="clearfix"></div>
	    </div>
	    <div class="form-group">
	        <label class="col-sm-4 control-label">Phone Number:</label>
	        <div class="col-xs-12 col-sm-8"><input name="new_contact_phone_number type="text" class="form-control" /></div>
            <div class="clearfix"></div>
	    </div>
	    <div class="form-group">
	        <label class="col-sm-4 control-label">Email Address:</label>
	        <div class="col-xs-12 col-sm-8"><input name="new_contact_email_address type="text" class="form-control" /></div>
            <div class="clearfix"></div>
	    </div>
	    <div class="clearfix"></div>

	    <div class="form-group pull-right gap-top">
		    <a href="?" class="btn brand-btn">Cancel</a>
		    <button type="submit" name="submit_contact" value="Submit" class="btn brand-btn">Submit</button>
	    </div>
	</form>
</div>
