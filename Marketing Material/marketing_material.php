<?php
/*
Customer Listing
*/
include ('../include.php');
error_reporting(0);
?>
</head>
<script type="text/javascript" src="marketing_material.js"></script>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('marketing_material');
?>

<div class="container">
    <div class="row hide_on_iframe">
        <div class="main-screen">

	        <form name="form_sites" method="post" action="" class="form-inline" role="form">
	            <?php
	            $search_vendor = '';
	            $search_type = '';
	            $search_category = '';
	            if(isset($_POST['search_user_submit'])) {
	                $search_vendor = $_POST['search_vendor'];
	                $search_type = $_POST['search_type'];
	                $search_category = $_POST['search_category'];
	            }
	            if (isset($_POST['display_all_inventory'])) {
	            	$_POST['search_type'] = '';
	            	$_POST['search_category'] = '';
	                $search_vendor = '';
	                $search_type = '';
	                $search_category = '';
	            }

				if (isset($_POST['display_all_inventory']) || isset($_POST['search_user_submit']) || isset($_GET['page'])) {
					$show_class = '';
					$msg_class = '';
				} else {
					$show_class = 'table_hide_before_search';
					$msg_class = 'message_show_before_search';
				}

	            ?>
	            <!-- Tile Header -->
	            <div class="tile-header">
	                <div class="col-xs-12 col-sm-4">
	                    <h1>
	                        <span class="pull-left" style="margin-top: -5px;"><a href="marketing_material.php" class="default-color">Marketing Material</a></span>
	                        <span class="clearfix"></span>
	                    </h1>
	                </div>
	                <div class="col-xs-12 col-sm-8 text-right settings-block">
	                    <?php if ( config_visible_function ( $dbc, 'marketing_material' ) == 1 ) { ?>
	                        <div class="pull-right gap-left top-settings">
	                            <a href="field_config_marketing_material.php" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
	                            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
	                        </div><?php
	                    } ?>
						<?php if(isset($_POST['search_user_submit'])) { ?>
							<input placeholder="Search Marketing Material" type="text" name="search_vendor" class="form-control pull-left" value="<?php echo $_POST['search_vendor']; ?>" style="width: 40%;">
						<?php } else { ?>
							<input placeholder="Search Marketing Material" type="text" name="search_vendor" class="form-control pull-left" style="width: 40%;">
						<?php } ?>
						<button type="submit" name="search_user_submit" class="btn brand-btn pull-left" style="position: relative; left: 1em;">Filter</button>
						<button type="submit" name="display_all_inventory" class="btn brand-btn pull-left" style="position: relative; left: 1em;">Display All</button>
						<?php if(vuaed_visible_function($dbc, 'marketing_material') == 1) { ?>
	                    	<a href="add_marketing_material.php" class="btn brand-btn pull-right">Add Marketing Material</a>
	                    <?php } ?>
	                </div>
	                <div class="clearfix"></div>
	            </div><!-- .tile-header -->

	            <div class="tile-container">
	            	<!-- Notice --><?php
                    $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='mm_mm'"));
                    $note = $notes['note'];
                        
                    if ( !empty($note) ) { ?>
                        <div class="notice double-gap-bottom popover-examples">
                            <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
                            <div class="col-sm-11">
                                <span class="notice-name">NOTE:</span>
                                <?= $note; ?>
                            </div>
                            <div class="clearfix"></div>
                        </div><?php
                    } ?>

	            	<div class="collapsible tile-sidebar set-section-height">
	            		<?php include('tile_sidebar.php'); ?>
	            	</div>

	            	<div class="fill-to-gap tile-content set-section-height" style="padding: 0;">
	            		<div class="main-screen-details">
	            			<div class="sidebar" style="padding: 1em; margin: 0 auto; overflow-y: auto;">

				            <?php
								/* Pagination Counting */
								$rowsPerPage = 25;
								$pageNum = 1;

								if(isset($_GET['page'])) {
									$pageNum = $_GET['page'];
								}

								$offset = ($pageNum - 1) * $rowsPerPage;

								$query_search = '';
								if($search_type != '') {
									$query_search .= " AND `marketing_material_type` = '$search_type'";
								}
								if($search_category != '') {
									$query_search .= " AND `category` = '$search_category'";
								}
								if($search_vendor != '') {
									$query_search .= " AND (marketing_material_code LIKE '%" . $search_vendor . "%' OR marketing_material_type LIKE '%" . $search_vendor . "%' OR category LIKE '%" . $search_vendor . "%' OR heading LIKE '%" . $search_vendor . "%' OR name LIKE '%" . $search_vendor . "%' OR title LIKE '%" . $search_vendor . "%' OR fee LIKE '%" . $search_vendor . "%')";
								}
								$query_check_credentials = "SELECT * FROM marketing_material WHERE deleted = 0 $query_search LIMIT $offset, $rowsPerPage";
								$query = "SELECT count(*) as numrows FROM marketing_material WHERE deleted = 0 $query_search";

								$result = mysqli_query($dbc, $query_check_credentials);

								$num_rows = mysqli_num_rows($result);

								if($num_rows > 0) {
									$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT marketing_material_dashboard FROM field_config"));
									$value_config = ','.$get_field_config['marketing_material_dashboard'].',';

									//Added Pagination
									echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
									//Finish Pagination

									echo "<div style='display:none;padding:5px;' class='".$msg_class."'>You must search for something to see any results (or simply hit display all).</div><div class='".$show_class."'><div id=\"no-more-tables\"><table class='table table-bordered '>";
									echo "<tr class='hidden-xs hidden-sm'>";
										if (strpos($value_config, ','."Marketing Material Code".',') !== FALSE) {
											echo '<th>Marketing Material Code</th>';
										}
										if (strpos($value_config, ','."Marketing Material Type".',') !== FALSE) {
											echo '<th>Marketing Material Type</th>';
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
										if (strpos($value_config, ','."Send Email".',') !== FALSE) {
											echo '<th>Send</th>';
										}
										echo '<th>Function</th>';
										echo "</tr>";
								} else {
									echo "<h2>No Record Found.</h2>";
								}
								while($row = mysqli_fetch_array( $result ))
								{
									echo "<tr>";
									$marketing_materialid = $row['marketing_materialid'];
									if (strpos($value_config, ','."Marketing Material Code".',') !== FALSE) {
										echo '<td data-title="Code">' . $row['marketing_material_code'] . '</td>';
									}
									if (strpos($value_config, ','."Marketing Material Type".',') !== FALSE) {
										echo '<td data-title="Type">' . $row['marketing_material_type'] . '</td>';
									}
									if (strpos($value_config, ','."Category".',') !== FALSE) {
										echo '<td data-title="Category">' . $row['category'] . '</td>';
									}

									if (strpos($value_config, ','."Title".',') !== FALSE) {
										echo '<td data-title="Title">' . $row['title'] . '</td>';
									}
									if (strpos($value_config, ','."Uploader".',') !== FALSE) {
										echo '<td data-title="Upload">';
										$marketing_material_uploads1 = "SELECT * FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid' AND type = 'Document' ORDER BY certuploadid DESC";
										$result1 = mysqli_query($dbc, $marketing_material_uploads1);
										$num_rows1 = mysqli_num_rows($result1);
										if($num_rows1 > 0) {
											while($row1 = mysqli_fetch_array($result1)) {
												echo '<ul  style="list-style:none; margin:0; padding:0;">';
												echo '<li><a href="download/'.$row1['document_link'].'" target="_blank">'.$row1['document_link'].'</a></li>';
												echo '</ul>';
												if($num_rows1 > 1) {
												 echo '<hr style=" margin:0; padding:0;margin-bottom:3px;margin-top:3px;box-shadow:1px 1px 1px grey">';
												}
											}
										}
										echo '</td>';
									}
									if (strpos($value_config, ','."Link".',') !== FALSE) {
										echo '<td data-title="Link">';
										$marketing_material_uploads2 = "SELECT * FROM marketing_material_uploads WHERE marketing_materialid='$marketing_materialid' AND type = 'Link' ORDER BY certuploadid DESC";
										$result2 = mysqli_query($dbc, $marketing_material_uploads2);
										$num_rows2 = mysqli_num_rows($result2);
										if($num_rows2 > 0) {
											$link_no = 1;
											while($row2 = mysqli_fetch_array($result2)) {
												echo '<ul style="list-style:none; margin:0; padding:0;">';
												echo '<li><a target="_blank" href=\''.$row2['document_link'].'\'">Link '.$link_no.'</a></li>';
												echo '</ul>';
												if($num_rows2 > 1) {
												 echo '<hr style=" margin:0; padding:0;margin-bottom:3px;margin-top:3px;box-shadow:1px 1px 1px grey">';
												}
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
										echo '<td data-title="'.TICKET_NOUN.' Desc.">' . html_entity_decode($row['ticket_description']) . '</td>';
									}
									if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
										echo '<td data-title="Retail Price">' . $row['final_retail_price'] . '</td>';
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
									if (strpos($value_config, ','."Client Price".',') !== FALSE) {
										echo '<td data-title="Client Price">' . $row['client_price'] . '</td>';
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
				                    if (strpos($value_config, ','."Send Email".',') !== FALSE) {
				                        echo '<td data-title="Send Email"><a href=\'send_marketing_material.php?marketing_materialid='.$marketing_materialid.'\'>Send</a></td>';
				                    }

									echo '<td data-title="Function">';
									if(vuaed_visible_function($dbc, 'marketing_material') == 1) {
									echo '<a href=\'add_marketing_material.php?marketing_materialid='.$marketing_materialid.'\'>Edit</a> | ';
									echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&marketing_materialid='.$marketing_materialid.'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
									}
									echo '</td>';

									echo "</tr>";
								}

								echo '</table></div></div>';
								//Added Pagination
								echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
								//Finish Pagination
				            ?>
				            </div>
			            </div>
		            </div>
			    </div>
		        <div class="clearfix"></div>
		    </form>
	    </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
