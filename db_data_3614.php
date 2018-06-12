<?php
/* Update Databases */
include ('database_connection.php');
error_reporting(0);

set_time_limit (600);

echo "Profit & Loss - Ticket #3614<br /><br />";

echo "Update Service Categories<br /><br />";
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Private Physio' WHERE `category` = 'Physical Therapy'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Private Massage' WHERE `category` = 'Massage Therapy'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Alberta Health Service (AHS) Physio' WHERE `heading` LIKE 'AHS%' AND `category` != 'Reports'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Cancellation Fees' WHERE `category` = 'Cancellation Fee'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Motor Vehicle Collision (MVA/MVC) Physio' WHERE `heading` LIKE 'MVC Physical%' AND `category` != 'Reports'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Motor Vehicle Collision (MVA/MVC) Massage' WHERE `heading` LIKE 'MVC Massage%' AND `category` != 'Reports'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Worker Compensation Board (WCB) Physio' WHERE `heading` LIKE 'WCB Physical%' AND `category` != 'Reports'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }
if (!mysqli_query($dbc, "UPDATE `services` SET `category` = 'Legal/Reports' WHERE `category` = 'Reports'")) {
        echo "Error: ".mysqli_error($dbc)."<br />\n";
    }

echo "Update Invoice Patients<br />";
$invoice_patients = mysqli_query($dbc, "SELECT * FROM `invoice_patient` WHERE `service_category` != ''");

while ($row = mysqli_fetch_array($invoice_patients)) {
	$invoicepatientid = $row['invoicepatientid'];
	$invoiceid = $row['invoiceid'];

	$invoice = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `invoice` WHERE `invoiceid` = '$invoiceid'"));

	$serviceid = '';
	$service_cat = '';
	$serviceids = explode(',', trim($invoice['serviceid'],','));
	foreach ($serviceids as $key => $row_service) {
		$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$row_service'"));
		if ($service['category'] == 'Legal/Reports') {
			unset($serviceids[$key]);
		}
	}
	$serviceid = $serviceids[0];
	$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$serviceid'"));
	$service_cat = $service['category'];
	$mutliples = false;
	foreach ($serviceids as $row_service) {
		$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$serviceid'"));
		$row_service_cat = $service['category'];
		if ($service_cat != $row_service_cat) {
			echo "<br />Multiple Services<br />";
			echo "- Invoice Patient ID: " . $row['invoicepatientid'] . "<br />";
			echo "- Invoice ID: " . $row['invoiceid'] . "<br />";
			echo "- Service ID: " . $invoice['serviceid'] . "<br />";
			$multiples = true;
		}
	}

	if (!$multiples) {
		$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `services` WHERE `serviceid` = '$serviceid'"));
		if ($service['num_rows'] == 0 && $serviceid != '') {
			echo "<br />Service Does Not Exist<br />";
			echo "- Invoice Patient ID: " . $row['invoicepatientid'] . "<br />";
			echo "- Invoice ID: " . $row['invoiceid'] . "<br />";
			echo "- Service ID: " . $serviceid . "<br />";
		} else if ($serviceid != '') {
			if (!mysqli_query($dbc, "UPDATE `invoice_patient` SET `service_category` = '$service_cat' WHERE `invoicepatientid` = '$invoicepatientid'")) {
        		echo "Error: ".mysqli_error($dbc)."<br />\n";
			}
		}
	}
}

echo "<br />Update Invoice Insurers<br />";
$invoice_insurers = mysqli_query($dbc, "SELECT * FROM `invoice_insurer` WHERE `service_category` != ''");

while ($row = mysqli_fetch_array($invoice_insurers)) {
	$invoiceinsurerid = $row['invoiceinsurerid'];
	$invoiceid = $row['invoiceid'];

	$invoice = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `invoice` WHERE `invoiceid` = '$invoiceid'"));

	$serviceid = '';
	$service_cat = '';
	$serviceids = explode(',', trim($invoice['serviceid'],','));
	foreach ($serviceids as $key => $row_service) {
		$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$row_service'"));
		if ($service['category'] == 'Legal/Reports') {
			unset($serviceids[$key]);
		}
	}
	$serviceid = $serviceids[0];
	$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$serviceid'"));
	$service_cat = $service['category'];
	$mutliples = false;
	foreach ($serviceids as $row_service) {
		$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services` WHERE `serviceid` = '$serviceid'"));
		$row_service_cat = $service['category'];
		if ($service_cat != $row_service_cat) {
			echo "<br />Multiple Services<br />";
			echo "- Invoice Insurer ID: " . $row['invoiceinsurerid'] . "<br />";
			echo "- Invoice ID: " . $row['invoiceid'] . "<br />";
			echo "- Service ID: " . $invoice['serviceid'] . "<br />";
			$multiples = true;
		}
	}

	if (!$multiples) {
		$service = mysqli_fetch_array(mysqli_query($dbc, "SELECT COUNT(*) as num_rows FROM `services` WHERE `serviceid` = '$serviceid'"));
		if ($service['num_rows'] == 0 && $serviceid != '') {
			echo "<br />Service Does Not Exist<br />";
			echo "- Invoice Insurer ID: " . $row['invoiceinsurerid'] . "<br />";
			echo "- Invoice ID: " . $row['invoiceid'] . "<br />";
			echo "- Service ID: " . $serviceid . "<br />";
		} else if ($serviceid != '') {
			if (!mysqli_query($dbc, "UPDATE `invoice_insurer` SET `service_category` = '$service_cat' WHERE `invoiceinsurerid` = '$invoiceinsurerid'")) {
        		echo "Error: ".mysqli_error($dbc)."<br />\n";
			}
		}
	}
}

echo "End Profit & Loss - Ticket #3614<br /><br />";
?>