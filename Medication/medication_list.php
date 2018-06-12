	<?php
	$marsheet_medication_tile = get_config($dbc, 'marsheet_medication_tile');

	$search_staff = '';
	$search_client = '';
	$search_date = '';

	if(isset($_GET['search_staff']) && $_GET['search_staff']!='') {
		$search_staff = $_GET['search_staff'];    
	} 
	if(isset($_GET['search_client']) && $_GET['search_client']!='') {
		$search_client = $_GET['search_client'];    
	}
	if(isset($_GET['search_date']) && $_GET['search_date']!='') {
		$search_date = $_GET['search_date'];    
	}
	if(isset($display_contact)) {
		$search_client = $display_contact;
	} else { ?>
		<form id="form1" name="form1" method="get"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			  <label for="site_name" class="control-label">Search By Staff:</label>
			</div>
			  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				  <select data-placeholder="Select Staff" name="search_staff" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
				  <option value=""></option>
				  <?php
					$query = mysqli_query($dbc,"SELECT distinct(contactid) FROM medication WHERE deleted=0 AND contactid !='' order by contactid");
					while($row1 = mysqli_fetch_array($query)) {
					?><option <?php if ($row1['contactid'] == $search_staff) { echo " selected"; } ?> value='<?php echo  $row1['contactid']; ?>' ><?php echo get_staff($dbc, $row1['contactid']); ?></option>
				<?php	}
				?>
				</select>
			  </div>

			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			  <label for="site_name" class="control-label">Search By Client:</label>
			</div>
			  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				  <select data-placeholder="Select a Client" name="search_client" class="chosen-select-deselect form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
				  <option value=""></option>
				  <?php
					$query = mysqli_query($dbc,"SELECT distinct(clientid) FROM medication WHERE deleted=0 and clientid != '' order by clientid");
					while($row1 = mysqli_fetch_array($query)) {
					?><option <?php if ($row1['clientid'] == $search_client) { echo " selected"; } ?> value='<?php echo  $row1['clientid']; ?>' ><?php echo get_contact($dbc, $row1['clientid']); ?></option>
				<?php   }
				?>
				</select>
			  </div>

			<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
			  <label for="site_name" class="control-label">Search By Date:</label>
			</div>
			  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				  <input name="search_date" value="<?php echo $search_date; ?>" type="text" class="form-control datepicker">
			  </div>

			<div class="form-group">
			  <label for="site_name" class="col-sm-4 control-label"></label>
			  <div class="col-sm-8">
				<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
				<button type="button" onclick="window.location='medication.php'" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
			  </div>
			</div>
		</form>
	<?php }
	
	if(vuaed_visible_function($dbc, 'medication') == 1) {
		echo '<a href="../Medication/add_medication.php?from_url='.$from_url.'" class="btn brand-btn mobile-block pull-right">Add Medication</a>';
		if($marsheet_medication_tile == 1) {
			echo '<a href=\'../Medication/marsheet.php\' class="btn brand-btn mobile-block pull-right">MAR Sheet</a>';
		}
	}
	?>

<div id="no-more-tables">

<?php
$filter = '';
if($search_staff != '') {
	$filter .= ' AND contactid = "'.$search_staff.'"';
}
if($search_client != '') {
	$filter .= ' AND clientid = "'.$search_client.'"';
}
if($search_date != '') {
	//$filter .= '';
}

$query = 'SELECT * FROM medication WHERE deleted = 0 '.$filter;

$result = mysqli_query($dbc, $query);

$num_rows = mysqli_num_rows($result);
if($num_rows > 0) {
	$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT medication_dashboard FROM field_config"));
	$value_config = ','.$get_field_config['medication_dashboard'].',';

	echo "<table class='table table-bordered'>";
	echo "<tr class='hidden-xs hidden-sm'>";
		if (strpos($value_config, ','."Staff".',') !== FALSE) {
			echo '<th>Staff&nbsp;&nbsp;<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="Staff administering the medication"><img src="../img/info.png" width="20"></a></span></th>';
		}
		if (strpos($value_config, ','."Client".',') !== FALSE) {
			echo '<th>Client&nbsp;&nbsp;<span class="popover-examples list-inline"><a href="#job" data-toggle="tooltip" data-placement="top" title="" data-original-title="Client who needs to receive medication"><img src="../img/info.png" width="20"></a></span></th>';
		}
		if (strpos($value_config, ','."Medication Type".',') !== FALSE) {
			echo '<th>Medication Type</th>';
		}
		if (strpos($value_config, ','."Category".',') !== FALSE) {
			echo '<th>Category</th>';
		}
		if (strpos($value_config, ','."Title".',') !== FALSE) {
			echo '<th>Title</th>';
		}
		if (strpos($value_config, ','."Delivery Method".',') !== FALSE) {
			echo '<th>Delivery Method&nbsp;<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="How should this medication be administered?"><img src="../img/info.png" width="20"></a></span></th>';
		}
		if (strpos($value_config, ','."Side Effects".',') !== FALSE) {
			echo '<th>Side Effects&nbsp;<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="Potential side effects of using this medication"><img src="../img/info.png" width="20"></a></span></th>';
		}
		if (strpos($value_config, ','."Administration Times".',') !== FALSE) {
			echo '<th>Administration Times&nbsp;<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="" data-original-title="Administration times for this medication"><img src="../img/info.png" width="20"></a></span></th>';
		}
		if (strpos($value_config, ','."Medication Code".',') !== FALSE) {
			echo '<th>Medication Code</th>';
		}
		if (strpos($value_config, ','."Uploader".',') !== FALSE) {
			echo '<th>Documents</th>';
		}
		if (strpos($value_config, ','."Link".',') !== FALSE) {
			echo '<th>Link</th>';
		}
		if (strpos($value_config, ','."Heading".',') !== FALSE) {
			echo '<th>Heading</th>';
		}
		if (strpos($value_config, ','."Name".',') !== FALSE) {
			echo '<th>Name</th>';
		}
		if (strpos($value_config, ','."Fee".',') !== FALSE) {
			echo '<th>Fee</th>';
		}
		if (strpos($value_config, ','."Cost".',') !== FALSE) {
			echo '<th>Cost</th>';
		}
		if (strpos($value_config, ','."Description".',') !== FALSE) {
			echo '<th>Description</th>';
		}
		if (strpos($value_config, ','."Dosage".',') !== FALSE) {
			echo '<th>Dosage</th>';
		}
		if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
			echo '<th>Quote Description</th>';
		}
		if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
			echo '<th>Invoice Description</th>';
		}
		if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
			echo '<th>'.TICKET_NOUN.' Description</th>';
		}
		if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
			echo '<th>Final Retail Price</th>';
		}
		if (strpos($value_config, ','."Admin Price".',') !== FALSE) {
			echo '<th>Admin Price</th>';
		}
		if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) {
			echo '<th>Wholesale Price</th>';
		}
		if (strpos($value_config, ','."Commercial Price".',') !== FALSE) {
			echo '<th>Commercial Price</th>';
		}
		if (strpos($value_config, ','."Client Price".',') !== FALSE) {
			echo '<th>Client Price</th>';
		}
		if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
			echo '<th>Minimum Billable</th>';
		}
		if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
			echo '<th>Estimated Hours</th>';
		}
		if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
			echo '<th>Actual Hours</th>';
		}
		if (strpos($value_config, ','."MSRP".',') !== FALSE) {
			echo '<th>MSRP</th>';
		}
		
		
		
		echo '<th>Function</th>';
		echo "</tr>";
} else {
	echo "<h2>No Record Found.</h2>";
}
while($row = mysqli_fetch_array( $result ))
{
	echo "<tr>";
	$medicationid = $row['medicationid'];
	if (strpos($value_config, ','."Staff".',') !== FALSE) {
		echo '<td data-title="Medication Type">' . get_contact($dbc, $row['contactid']) . '</td>';
	}
	if (strpos($value_config, ','."Client".',') !== FALSE) {
		echo '<td data-title="Code">' . get_contact($dbc, $row['clientid']) . '</td>';
	}
	if (strpos($value_config, ','."Medication Type".',') !== FALSE) {
		echo '<td data-title="Medication Type">' . $row['medication_type'] . '</td>';
	}
	if (strpos($value_config, ','."Category".',') !== FALSE) {
		echo '<td data-title="category">' . $row['category'] . '</td>';
	}
	if (strpos($value_config, ','."Title".',') !== FALSE) {
		echo '<td data-title="category">' . $row['title'] . '</td>';
	}
	if (strpos($value_config, ','."Delivery Method".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['delivery_method'] . '</td>';
	}
	if (strpos($value_config, ','."Side Effects".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['side_effects'] . '</td>';
	}
	if (strpos($value_config, ','."Administration Times".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['administration_times'] . '</td>';
	}
	if (strpos($value_config, ','."Medication Code".',') !== FALSE) {
		echo '<td data-title="Medication Type">' . $row['medication_code'] . '</td>';
	}

	if (strpos($value_config, ','."Uploader".',') !== FALSE) {
		echo '<td data-title="category">';
		$medication_uploads1 = "SELECT * FROM medication_uploads WHERE medicationid='$medicationid' AND type = 'Document' ORDER BY meduploadid DESC";
		$result1 = mysqli_query($dbc, $medication_uploads1);
		$num_rows1 = mysqli_num_rows($result1);
		if($num_rows1 > 0) {
			while($row1 = mysqli_fetch_array($result1)) {
				echo '<ul>';
				echo '<li><a href="download/'.$row1['document_link'].'" target="_blank">'.$row1['document_link'].'</a></li>';
				echo '</ul>';
			}
		}
		echo '</td>';
	}
	if (strpos($value_config, ','."Link".',') !== FALSE) {
		echo '<td data-title="category">';
		$medication_uploads2 = "SELECT * FROM medication_uploads WHERE medicationid='$medicationid' AND type = 'Link' ORDER BY meduploadid DESC";
		$result2 = mysqli_query($dbc, $medication_uploads2);
		$num_rows2 = mysqli_num_rows($result2);
		if($num_rows2 > 0) {
			$link_no = 1;
			while($row2 = mysqli_fetch_array($result2)) {
				echo '<ul>';
				echo '<li><a target="_blank" href=\''.$row2['document_link'].'\'">Link '.$link_no.'</a></li>';
				echo '</ul>';
				$link_no++;
			}
		}
		echo '</td>';
	}

	if (strpos($value_config, ','."Heading".',') !== FALSE) {
		echo '<td data-title="Heading">' . $row['heading'] . '</td>';
	}
	if (strpos($value_config, ','."Name".',') !== FALSE) {
		echo '<td data-title="Heading">' . $row['name'] . '</td>';
	}
	if (strpos($value_config, ','."Fee".',') !== FALSE) {
		echo '<td data-title="Heading">' . $row['fee'] . '</td>';
	}
	if (strpos($value_config, ','."Cost".',') !== FALSE) {
		echo '<td data-title="Cost">' . $row['cost'] . '</td>';
	}
	if (strpos($value_config, ','."Description".',') !== FALSE) {
		echo '<td data-title="description">' . html_entity_decode($row['description']) . '</td>';
	}
	if (strpos($value_config, ','."Dosage".',') !== FALSE) {
		echo '<td data-title="Dosage">' . $row['dosage'] . '</td>';
	}
	if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
		echo '<td data-title="quote_description">' . html_entity_decode($row['quote_description']) . '</td>';
	}
	if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
		echo '<td data-title="quote_description">' . html_entity_decode($row['invoice_description']) . '</td>';
	}
	if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
		echo '<td data-title="quote_description">' . html_entity_decode($row['ticket_description']) . '</td>';
	}
	if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['final_retail_price'] . '</td>';
	}
	if (strpos($value_config, ','."Admin Price".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['admin_price'] . '</td>';
	}
	if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['wholesale_price'] . '</td>';
	}
	if (strpos($value_config, ','."Commercial Price".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['commercial_price'] . '</td>';
	}
	if (strpos($value_config, ','."Client Price".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['client_price'] . '</td>';
	}
	if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['minimum_billable'] . '</td>';
	}
	if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['estimated_hours'] . '</td>';
	}
	if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['actual_hours'] . '</td>';
	}
	if (strpos($value_config, ','."MSRP".',') !== FALSE) {
		echo '<td data-title="Code">' . $row['msrp'] . '</td>';
	}
	echo '<td data-title="Function">';
	if(vuaed_visible_function($dbc, 'medication') == 1) {
		echo '<a href=\'../Medication/add_medication.php?medicationid='.$medicationid.'&from_url='.$from_url.'\'>Edit</a> | ';
		// echo '<a href=\'../Medication/add_medication.php?action=delete&medicationid='.$medicationid.'&from_url='.$from_url.'\' onclick="return confirm(\'Are you sure?\')">Delete</a> | ';
		echo '<a href=\'../Medication/add_medication.php?action=archive&medicationid='.$medicationid.'&from_url='.$from_url.'\' onclick="return confirm(\'Are you sure?\')">Archive</a> | ';
		echo '<a href=\'../Medication/history.php?medicationid='.$medicationid.'&from_url='.$from_url.'\'>History</a>';
		if($marsheet_medication_tile == 1) {
			echo ' | <a href=\'../Medication/marsheet.php?edit='.$row['clientid'].'\'>Client MAR Sheet</a>';
		}
	}
	echo '</td>';

	echo "</tr>";
}

echo '</table></div>';
if(vuaed_visible_function($dbc, 'medication') == 1) {
	echo '<a href="../Medication/add_medication.php?from_url='.$from_url.'" class="btn brand-btn mobile-block pull-right">Add Medication</a>';
	if($marsheet_medication_tile == 1) {
		echo '<a href=\'../Medication/marsheet.php\' class="btn brand-btn mobile-block pull-right">MAR Sheet</a>';
	}
}

?>