<?php
/*
Add	Contacts
*/
include ('../include.php');
error_reporting(0);
// ADD Contacts
if(isset($_POST["submitty"]))
{
	$file = $_FILES['file']['tmp_name'];
	// Open the file and grab the headers
	$handle = fopen($file, "r");
	$headers = fgetcsv( $handle, 1024, "," );

	// Read the file row by row
	while( ( $csv = fgetcsv( $handle, 1024, "," ) ) !== false )
	{
		$num = count($csv);
		// Assign each column in the current row to variables
		$values = [ 'category'=>'Sites','businessid'=>'','name'=>'','company'=>'','business_address'=>'','office_phone'=>'','first_name'=>'','last_name'=>'','cell_phone'=>'','home_phone'=>'','fax'=>'','email_address'=>'','customer_address'=>'','position'=>'','mailing_address'=>'','postal_code'=>'','zip_code'=>'','city'=>'','province'=>'','country'=>'' ];
		for($i = 0; $i < $num; $i++)
			$values[$headers[$i]] = trim(mysqli_real_escape_string($dbc, htmlspecialchars_decode($csv[$i],ENT_NOQUOTES)));

		// Find the business ID, or create it if needed
		$businessid = null;
		if($values['businessid'] != '') {
			$businessid = $values['businessid'];
		} else {
			$result_business = mysqli_query ( $dbc, "SELECT `contactid` FROM `contacts` WHERE TRIM(`name`)='{$values['name']}'" );

			if ( mysqli_num_rows ( $result_business ) > 0 ) {
				// Business exists, so get the ID
				$businessid_get	= mysqli_fetch_array ( $result_business );
				$businessid		= $businessid_get['contactid'];
			} else if ( $values['name'] != '' ) {
				// Business not available, so add it if there is a Business name
				$query_insert_business = "INSERT INTO `contacts` (`category`, `name`, `business_address`, office_phone, `deleted`, `status`)
					VALUES ('Business', '{$values['name']}', '{$values['business_address']}', '{$values['office_phone']}', 0, 1)";
				$result_insert_business = mysqli_query ( $dbc, $query_insert_business );
				$businessid = mysqli_insert_id ( $dbc );
			}
		}

		// Insert the contact into the database
		$sql = "INSERT INTO `contacts` (`category`, `businessid`, `first_name`, `last_name`, `office_phone`, `cell_phone`, `home_phone`, `fax`, `customer_address`, `email_address`, `position`, `mailing_address`, `postal_code`, `zip_code`, `city`, `province`, `country`, `deleted`, `status`)
			VALUES ('Customer', $businessid, '{$values['first_name']}', '{$values['last_name']}', '{$values['office_phone']}', '{$values['cell_phone']}', '{$values['home_phone']}', '{$values['fax']}', '{$values['customer_address']}', '{$values['email_address']}', '{$values['position']}', '{$values['mailing_address']}', '{$values['postal_code']}', '{$values['zip_code']}', '{$values['city']}', '{$values['province']}', '{$values['country']}', 0, 1)";
		$results_insert_contact	= mysqli_query ( $dbc, $sql );
	}

	fclose( $handle );
	echo '<script type="text/javascript"> alert("Successfully imported CSV file. Please check the Contacts dashboard to view your newly added contacts."); </script>';
}
// END ADD CONTACTS
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#category").change(function() {
            if($( "#category option:selected" ).text() == 'Other') {
                    $( "#category_name" ).show();
            } else {
                $( "#category_name" ).hide();
            }
        });
});
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('site_work_orders');
?>
<div class="container">
	<h1>Import Multiple Sites</h1>
	<div class="gap-top double-gap-bottom"><a href="site_work_orders.php?tab=sites" class="btn brand-btn">Back to Dashboard</a></div>

	<div class="row add">
		<form name="import" method="post" enctype="multipart/form-data">
			<div class="notice">Steps to Upload Multiple Items into the Contacts tile:<br><Br>
				<b>1.</b> Please download the following Excel(CSV) file to use as a template: <a href='Add_Multiple_Contacts.csv' style='color:white; text-decoration:underline !important;'>Add_Multiple_Contacts.csv</a>.<br><br>
				<b>2.</b> Fill in the rows (starting from row 2). Please note that each row you fill out will become a separate Contact in the Contacts tile.<br>
				<span style='color:pink;'><img src='../img/warning.png' style='width:25px;'> NOTE</span>: Do not change/move/delete any of the column titles in the first row.<br> <span style='color:lightgreen'><b>Hint</b>:</span> press CTRL+F on your keyboard to find the fields you would like to populate; this will help you locate them faster. <br><br>
				<b>3.</b> After you are done filling out your data, save the Excel (CSV) file, upload the CSV file below, and hit submit.<br><br>
				<b>4.</b> Please look for your newly added Contacts in the Contacts dashboard!<br><br>
				<input class="form-control" type="file" name="file" /><br />
			</div>
			<div class="row double-padded">
				<div class="col-sm-6">
					<a href="site_work_orders.php?tab=sites" class="btn brand-btn btn-lg">Back</a>
				</div>
				<div class="col-sm-6">
					<input class="btn brand-btn btn-lg pull-right" type="submit" name="submitty" value="Submit" />
				</div>
				<div class="clearfix"></div>
			</div>
		</form>
	</div>

  </div>
<?php include ('../footer.php'); ?>