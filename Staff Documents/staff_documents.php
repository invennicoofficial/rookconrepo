<?php
/*
Customer Listing
*/
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('staff_documents');

//Archive 7 year old documents
$query = mysqli_query($dbc, "SELECT * FROM staff_documents");
while ($row = mysqli_fetch_array($query)) {
	$date_7_years = strtotime('+7 years', strtotime($row['upload_date']));
    $date_of_archival = date('Y-m-d');
	if (strtotime("now") >= $date_7_years) {
		$query_archive = mysqli_query($dbc, "UPDATE staff_documents SET deleted = 1, `date_of_archival` = '$date_of_archival' WHERE staff_documentsid = '" . $row['staff_documentsid'] . "'");
	}
}
?>

<div class="container triple-pad-bottom">
    <div class="row">

		<div class="col-sm-10">
			<h1>Staff Documents Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'staff_documents') == 1) {
					echo '<a href="field_config_staff_documents.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
		</div>
		<div class="clearfix double-gap-bottom"></div>

		<div class="notice double-gap-bottom popover-examples">
			<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			<div class="col-sm-11"><span class="notice-name">NOTE:</span>
			The distribution and access to the right staff documents is essential. In this section you can sort all staff documents by Type and by Category, helping your staff and business organize the necessities. Click the send button to instantly email any material to the right person.</div>
			<div class="clearfix"></div>
		</div>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <?php
				$search_vendor = '';
				$search_staff = '';
				$search_type = '';
				$search_category = '';
				if(isset($_POST['search_user_submit'])) {
					$search_vendor = $_POST['search_vendor'];
					$search_staff = $_POST['search_staff'];
					$search_type = $_POST['search_type'];
					$search_category = $_POST['search_category'];
				}
				if (isset($_POST['display_all_inventory'])) {
					$search_vendor = '';
					$search_staff = '';
					$search_type = '';
					$search_category = '';
				}
            ?>

			<div class="row padded">
                <div class="col-sm-3">
					<div class="col-sm-5 pad-5">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Type here to search within your Staff Documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<label for="search_vendor" class="control-label">Search By Any:</label>
					</div>
					<div class="col-sm-7">
						<?php if(isset($_POST['search_user_submit'])) { ?>
						<input type="text" name="search_vendor" class="form-control" value="<?php echo $_POST['search_vendor']?>">
						<?php } else { ?>
							<input type="text" name="search_vendor" class="form-control">
						<?php } ?>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="col-sm-5 pad-5">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Select from the drop down menu to search by staff within your Staff Documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<label for="site_name" class="control-label">Search By Staff:</label>
					</div>
					<div class="col-sm-7">
						<select data-placeholder="Select a Staff" name="search_staff" class="chosen-select-deselect form-control" width="380">
							<option value=""></option><?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY.""),MYSQLI_ASSOC));
							foreach($query as $id) {
		                        $query_staff = mysqli_fetch_array(mysqli_query($dbc, "SELECT status, deleted FROM contacts WHERE contactid='$id'"));
		                        $staff_status = '';
		                        if ($query_staff['deleted'] == '1') {
		                            $staff_status = ' (Archived)';
		                        } else if ($query_staff['status'] == '0') {
		                            $staff_status = ' (Suspended)';
		                        }
								$selected = '';
								$selected = $id == $search_staff ? 'selected = "selected"' : '';
								echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).$staff_status.'</option>';
							} ?>
						</select>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="col-sm-5 pad-5">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Select from the drop down menu to search by type within your Staff Documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<label for="site_name" class="control-label">Search By Type:</label>
					</div>
					<div class="col-sm-7">
						<select data-placeholder="Select a Type" name="search_type" class="chosen-select-deselect form-control" width="380">
							<option value=""></option><?php
							$query = mysqli_query($dbc,"SELECT distinct(staff_documents_type) FROM staff_documents WHERE deleted=0 order by staff_documents_type");
							while($row1 = mysqli_fetch_array($query)) {
								?><option <?php if ($row1['staff_documents_type'] == $search_type) { echo " selected"; } ?> value='<?php echo  $row1['staff_documents_type']; ?>' ><?php echo $row1['staff_documents_type']; ?></option><?php
							} ?>
						</select>
					</div>
				</div>

				<div class="col-sm-3">
					<div class="col-sm-6 pad-5">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Select from the drop down menu to search by category within your Staff Documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<label for="site_name" class="control-label">Search By Category:</label>
					</div>
					<div class="col-sm-6">
						<select data-placeholder="Select a Category" name="search_category" class="chosen-select-deselect form-control" width="380">
							<option value=""></option><?php
							$query = mysqli_query($dbc,"SELECT distinct(category) FROM staff_documents WHERE deleted=0");
							while($row2 = mysqli_fetch_array($query)) {
								?><option <?php if ($row2['category'] == $search_category) { echo " selected"; } ?> value='<?php echo  $row2['category']; ?>' ><?php echo $row2['category']; ?></option><?php
							} ?>
						</select>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>

            <div class="row gap-right">
				<div class="pull-right">
					<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click this once you have selected the above."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>

					<span class="popover-examples list-inline gap-left"><a data-toggle="tooltip" data-placement="top" title="This refreshes the page to view all Staff Documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
				</div>
			</div>

			<div class="clearfix triple-gap-bottom"></div>

            <?php if(vuaed_visible_function($dbc, 'staff_documents') == 1) { ?>
				<div class="double-gap-top">
					<br />
					<a href="add_staff_documents.php" class="btn brand-btn mobile-block pull-right">Add Staff Documents</a>
					<span class="popover-examples pad-5 pull-right"><a data-toggle="tooltip" data-placement="top" title="Click here to add documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				</div>

				<div class="clearfix triple-gap-bottom"></div>
			<?php } ?>

            <div id="no-more-tables">
				<?php

				/* Pagination Counting */
				$rowsPerPage = 25;
				$pageNum = 1;

				if(isset($_GET['page'])) {
					$pageNum = $_GET['page'];
				}

				$offset = ($pageNum - 1) * $rowsPerPage;

				$search_criteria = '';
				if($search_type != '') {
					$search_criteria .= " AND staff_documents_type = '$search_type'";
				}
				if($search_category != '') {
					$search_criteria .= " AND category = '$search_category'";
				}
				if($search_staff != '') {
					$search_criteria .= " AND contactid = '$search_staff'";
				}
				if($search_vendor != '') {
					$staff_list = [];
					$query_staff = mysqli_query($dbc, "SELECT contactid FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY."");
					while ($row = mysqli_fetch_array($query_staff)) {
						if (strpos(strtolower(get_contact($dbc, $row['contactid'])), strtolower($search_vendor)) !== FALSE) {
							array_push ($staff_list, $row['contactid']);
						}
					}
					$staff_list = implode("','", $staff_list);
					$search_criteria .= " AND (staff_documents_code LIKE '%" . $search_vendor . "%' OR staff_documents_type LIKE '%" . $search_vendor . "%' OR category LIKE '%" . $search_vendor . "%' OR heading LIKE '%" . $search_vendor . "%' OR name LIKE '%" . $search_vendor . "%' OR title LIKE '%" . $search_vendor . "%' OR fee LIKE '%" . $search_vendor . "%' OR contactid IN ('$staff_list'))";
				}
				$query_check_credentials = "SELECT * FROM staff_documents WHERE deleted = 0 " . $search_criteria . " LIMIT $offset, $rowsPerPage";
				$query = "SELECT count(*) as numrows FROM staff_documents WHERE deleted = 0 " . $search_criteria;

				$result = mysqli_query($dbc, $query_check_credentials);

				$num_rows = mysqli_num_rows($result);


				if($num_rows > 0) {
					$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT staff_documents_dashboard FROM field_config"));
					$value_config = ','.$get_field_config['staff_documents_dashboard'].',';

					// Add Pagintion //
					echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
					// Complete Pagination //

					echo "<table class='table table-bordered'>";
					echo "<tr class='hidden-sm hidden-xs'>";
						if (strpos($value_config, ','."Staff".',') !== FALSE) {
							echo '<th>Staff</th>';
						}
						if (strpos($value_config, ','."Staff Documents Code".',') !== FALSE) {
							echo '<th>Staff Documents Code</th>';
						}
						if (strpos($value_config, ','."Staff Documents Type".',') !== FALSE) {
							echo '<th>Staff Document Type</th>';
						}
						if (strpos($value_config, ','."Category".',') !== FALSE) {
							echo '<th>Category</th>';
						}
						if (strpos($value_config, ','."Title".',') !== FALSE) {
							echo '<th>Title</th>';
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
						if (strpos($value_config, ','."Staff Price".',') !== FALSE) {
							echo '<th>Staff Price</th>';
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
					$staff_documentsid = $row['staff_documentsid'];
					if (strpos($value_config, ','."Staff".',') !== FALSE) {
						echo '<td data-title="Staff"><a href="/Staff/staff_edit.php?contactid=' . $row['contactid'] . '&from=' . urlencode(WEBSITE_URL . $_SERVER['REQUEST_URI']) . '">' . get_contact($dbc, $row['contactid']) . '</a></td>';
					}
					if (strpos($value_config, ','."Staff Documents Code".',') !== FALSE) {
						echo '<td data-title="Doc. Code">' . $row['staff_documents_code'] . '</td>';
					}
					if (strpos($value_config, ','."Staff Documents Type".',') !== FALSE) {
						echo '<td data-title="Doc. Type">' . $row['staff_documents_type'] . '</td>';
					}
					if (strpos($value_config, ','."Category".',') !== FALSE) {
						echo '<td data-title="Category">' . $row['category'] . '</td>';
					}

					if (strpos($value_config, ','."Title".',') !== FALSE) {
						echo '<td data-title="Title">' . $row['title'] . '</td>';
					}
					if (strpos($value_config, ','."Uploader".',') !== FALSE) {
						echo '<td data-title="Upload">';
						$staff_documents_uploads1 = "SELECT * FROM staff_documents_uploads WHERE staff_documentsid='$staff_documentsid' AND type = 'Document' ORDER BY certuploadid DESC";
						$result1 = mysqli_query($dbc, $staff_documents_uploads1);
						$num_rows1 = mysqli_num_rows($result1);
						if($num_rows1 > 0) {
							while($row1 = mysqli_fetch_array($result1)) {
								if(strpos($row1['document_link'], ',') !== FALSE) {
									$new_document_link = str_replace(',','',$row1['document_link']);
									rename('../Staff Documents/download/'.$row1['document_link'], '../Staff Documents/download/'.$new_document_link);
									$row1['document_link'] = $new_document_link;
									mysqli_query($dbc, "UPDATE `staff_documents_uploads` SET `document_link` = '{$row1['document_link']}' WHERE `certuploadid` = '{$row1['certuploadid']}'");
								}
								echo '<ul>';
								echo '<li><a href="download/'.$row1['document_link'].'" target="_blank">'.$row1['document_link'].'</a></li>';
								echo '</ul>';
							}
						}
						echo '</td>';
					}
					if (strpos($value_config, ','."Link".',') !== FALSE) {
						echo '<td data-title="Link">';
						$staff_documents_uploads2 = "SELECT * FROM staff_documents_uploads WHERE staff_documentsid='$staff_documentsid' AND type = 'Link' ORDER BY certuploadid DESC";
						$result2 = mysqli_query($dbc, $staff_documents_uploads2);
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
						echo '<td data-title="Name">' . $row['name'] . '</td>';
					}
					if (strpos($value_config, ','."Fee".',') !== FALSE) {
						echo '<td data-title="Fee">' . $row['fee'] . '</td>';
					}
					if (strpos($value_config, ','."Cost".',') !== FALSE) {
						echo '<td data-title="Cost">' . $row['cost'] . '</td>';
					}
					if (strpos($value_config, ','."Description".',') !== FALSE) {
						echo '<td data-title="Description">' . html_entity_decode($row['description']) . '</td>';
					}
					if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
						echo '<td data-title="Quote Desc.">' . html_entity_decode($row['quote_description']) . '</td>';
					}
					if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
						echo '<td data-title="Invoice Desc.">' . html_entity_decode($row['invoice_description']) . '</td>';
					}
					if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
						echo '<td data-title="'.TICKET_NOUN.' Desc">' . html_entity_decode($row['ticket_description']) . '</td>';
					}
					if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
						echo '<td data-title="Retail">' . $row['final_retail_price'] . '</td>';
					}
					if (strpos($value_config, ','."Admin Price".',') !== FALSE) {
						echo '<td data-title="Admin Price">' . $row['admin_price'] . '</td>';
					}
					if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) {
						echo '<td data-title="Wholesale">' . $row['wholesale_price'] . '</td>';
					}
					if (strpos($value_config, ','."Commercial Price".',') !== FALSE) {
						echo '<td data-title="Comm. Price">' . $row['commercial_price'] . '</td>';
					}
					if (strpos($value_config, ','."Staff Price".',') !== FALSE) {
						echo '<td data-title="Staff Price">' . $row['staff_price'] . '</td>';
					}
					if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
						echo '<td data-title="Min. Billable">' . $row['minimum_billable'] . '</td>';
					}
					if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
						echo '<td data-title="Est. Hours">' . $row['estimated_hours'] . '</td>';
					}
					if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
						echo '<td data-title="Actual Hours">' . $row['actual_hours'] . '</td>';
					}
					if (strpos($value_config, ','."MSRP".',') !== FALSE) {
						echo '<td data-title="MSRP">' . $row['msrp'] . '</td>';
					}

					echo '<td data-title="Function">';
					if(vuaed_visible_function($dbc, 'staff_documents') == 1) {
					echo '<a href=\'add_staff_documents.php?staff_documentsid='.$staff_documentsid.'\'>Edit</a> | ';
					echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&staff_documentsid='.$staff_documentsid.'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
					}
					echo '</td>';

					echo "</tr>";
				}

				echo '</table>';

			echo '</div>';

            // Add Pagintion //
			echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
			// Complete Pagination //

			if(vuaed_visible_function($dbc, 'staff_documents') == 1) {
				echo '<div class="clearfix pull-right">'; ?>
					<span class="popover-examples pad-5"><a data-toggle="tooltip" data-placement="top" title="Click here to add documents."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
					echo '<a href="add_staff_documents.php" class="btn brand-btn mobile-block">Add Staff Documents</a>';
				echo '</div>';

				echo '<div class="clearfix"></div>';
			} ?>
        </form>

    </div>
</div>

<?php include ('../footer.php'); ?>