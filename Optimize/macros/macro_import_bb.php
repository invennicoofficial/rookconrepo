<?php // CDS Best Buy Format Macro
include_once ('include.php');
error_reporting(0);

if(isset($_POST['upload_file']) && !empty($_FILES['csv_file']['tmp_name'])) {
	$file_name = $_FILES['csv_file']['tmp_name'];
	$delimiter = filter_var($_POST['delimiter'],FILTER_SANITIZE_STRING);
	$businessid = filter_var($_POST['businessid'],FILTER_SANITIZE_STRING);
	$business = $dbc->query("SELECT * FROM `contacts` WHERE `contactid`='$businessid'")->fetch_assoc();
	$business_name = decryptIt($business['name']);
	$region = $business['region'];
	$classification = $business['classification'];
	$ticket_type = filter_var($_POST['ticket_type'],FILTER_SANITIZE_STRING);
	if($delimiter == 'comma') {
		$delimiter = ", ";
	} else {
		$delimiter = "\n";
	}
	$handle = fopen($file_name, 'r');
	$headers = fgetcsv($handle, 2048, ",");

	$new_values = [];

	while (($csv = fgetcsv($handle, 2048, ",")) !== FALSE) {
		$num = count($csv);
		$values = [];
		for($i = 0; $i < $num; $i++) {
			$values[$headers[$i]] = trim($csv[$i]);
		}
		$new_values[$values['Invoice_Number']]['date'] = date('Y-m-d',strtotime($values['Origin_Date']));
		$new_values[$values['Invoice_Number']]['address'] = filter_var($values['Destination_Street'],FILTER_SANITIZE_STRING);
		$new_values[$values['Invoice_Number']]['city'] = filter_var($values['Destination_City'],FILTER_SANITIZE_STRING);
		$new_values[$values['Invoice_Number']]['customer_name'] = filter_var($values['Destination_Customer_First_Name'].' '.$values['Destination_Customer_Last_Name'],FILTER_SANITIZE_STRING);
		$new_values[$values['Invoice_Number']]['phone'] = filter_var($values['Destination_Phone_Number'],FILTER_SANITIZE_STRING);
		if(!empty($values['Destination_Second_Phone_Number'])) {
			$new_values[$values['Invoice_Number']]['phone'] .= ', '.filter_var($values['Destination_Second_Phone_Number'],FILTER_SANITIZE_STRING);
		}
		$new_values[$values['Invoice_Number']]['sku'] .= htmlentities(filter_var($delimiter.$values['SKU_Description'],FILTER_SANITIZE_STRING));
		$new_values[$values['Invoice_Number']]['comments'] = htmlentities(filter_var($values['Stop_Instruction'],FILTER_SANITIZE_STRING));
	}
	fclose($handle);

	if (!file_exists('cds_exports')) {
		mkdir('cds_exports', 0777, true);
	}
	$new_csv = ['Client', 'Date', 'Invoice Number', 'Best Buy', 'Warehouse', 'Address', 'City/Town', 'Customer Name', 'Phone Number', 'SKU Information', 'Comments'];
	$date = date('Y-m-d');
	foreach ($new_values as $key => $value) {
		if(!empty($value['date']) && !empty($value['customer_name']) && !empty($value['address']) && !empty($value['city']) && !empty($value['phone']) && !empty(strip_tags(html_entity_decode($value['sku'])))) {
			$date = $value['date'];
			$dbc->query("INSERT INTO `tickets` (`ticket_type`,`businessid`,`region`,`classification`, `salesorderid`) VALUES ('$ticket_type','$businessid','$region','$classification','$key')");
			$ticketid = $dbc->insert_id;
			$dbc->query("INSERT INTO `ticket_schedule` (`ticketid`,`to_do_date`,`client_name`,`address`,`city`,`details`,`order_number`,`notes`) VALUES ('$ticketid','".$value['date']."','".$value['customer_name']."','".$value['address']."','".$value['city']."','".$value['phone']."','".$key."','&lt;p&gt;".$value['sku']."&lt;/p&gt;&lt;p&gt;".$value['comments']."&lt;/p&gt;')");
			$dbc->query("INSERT INTO `ticket_history` (`ticketid`,`userid`,`src`,`description`) VALUES ('$ticketid',".$_SESSION['contactid'].",'optimizer','Best Buy macro imported ".TICKET_NOUN." $ticketid')");
		}
	}
	echo "<script>window.location.replace('?tab=assign&date=$date&region=$region&classification=$classification');</script>";
}
?>

<h1>Best Buy Macro</h1>

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
	<ol>
		<li>Upload your CSV file using the File Uploader.</li>
		<li>Specify whether to combine the SKU Descriptions using a comma or separated by new line. (NOTE: New line sometimes doesn't display properly depending on the CSV viewer you are using. If this doesn't work, use the comma separator).</li>
		<li>Select the business to which the deliveries will be attached.</li>
		<li>Press the Submit button to run the macro and generate the new CSV file.</li>
		<br>
		<p>
			<label class="form-checkbox"><input type="radio" name="delimiter" value="newline" checked>New Line</label>
			<label class="form-checkbox"><input type="radio" name="delimiter" value="comma">Comma</label><br>
			<select class="chosen-select-deselect" data-placeholder="Select <?= BUSINESS_CAT ?>" name="businessid"><option />
				<?php foreach(sort_contacts_query($dbc->query("SELECT `name`, `contactid` FROM `contacts` WHERE `category`='".BUSINESS_CAT."' AND `deleted`=0 AND `status` > 0")) as $business) { ?>
					<option value="<?= $business['contactid'] ?>"><?= $business['name'] ?></option>
				<?php } ?>
			</select>
			<input type="file" name="csv_file">
			<input type="hidden" name="ticket_type" value="<?php foreach($macro_list as $macro) {
				if($macro[0] == $_GET['macro']) {
					echo $macro[1];
				}
			} ?>">
			<input type="submit" name="upload_file" value="Submit" class="btn brand-btn">
		</p>
	</ol>
</form>