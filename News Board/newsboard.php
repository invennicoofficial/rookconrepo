<?php
/*
Customer Listing
*/
include ('../include.php');
?>
<script>
    function submitForm(thisForm) {
        if (!$('input[name="search_user_submit"]').length) {
            var input = $("<input>")
                        .attr("type", "hidden")
                        .attr("name", "search_user_submit").val("1");
            $('[name=form_sites]').append($(input));
        }

        $('[name=form_sites]').submit();
    }
    $(document).on('change', 'select[name="search_type"]', function() { submitForm(); });
    $(document).on('change', 'select[name="search_category"]', function() { submitForm(); });
</script>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('newsboard');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

		<h1 class=""><span class="popover-examples list-inline" style="margin:0 5px 0 12px;"><a data-toggle="tooltip" data-placement="top" title="A place where you can post internal messages/events for the entire company."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="30"></a></span>News Boards
        <?php
        if(config_visible_function($dbc, 'newsboard') == 1) {
            echo '<a href="field_config_newsboard.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><span class="popover-examples list-inline"><a class="pull-right" style="margin:-5px 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span><br><br>';
        }
        ?>
		<div style="text-align:right;">
			<p>
				<?php $base_url = WEBSITE_URL; ?>
				<a href="<?php echo $base_url . '/newsboard.php'; ?>"> 
					<img src="<?php echo WEBSITE_URL; ?>/img/icons/switch-6.png" width="50px" class="switch_info_off1">
					<img src="<?php echo WEBSITE_URL; ?>/img/icons/switch-7.png" class="switch_info_on1" style='display:none;' width="50px">
				</a>
			</p>
		</div>
        </h1>

		<?php

		// Pending cross-software P.O.'
		if(vuaed_visible_function($dbc, 'newsboard') == 1) {
			$num_of_rows = 0;
			$pending_rows = 0;
			// **** NOTE: THE $number_of_connections variable is set only in the database_connection.php file. You must put this variable in manually for this to work. Please see of SEA's database_connection.php files (try sea.freshfocussoftware.com) in order to see how these variables are set up. If you are trying to copy this cross-software functionality, it is advised that you use the exact same format/variable names that SEA's database_connection.php file contains.
			if(isset($number_of_connections) && $number_of_connections > 0) {
				foreach (range(1, $number_of_connections) as $i) {
					$dbc_cross = ${'dbc_cross_'.$i};
					$check_po_query = "SELECT * FROM newsboard WHERE cross_software != '' AND cross_software IS NOT NULL AND deleted = 0 ORDER BY newsboardid DESC";
					$resulx = mysqli_query($dbc_cross, $check_po_query) or die(mysqli_error($dbc_cross));
					$num_rowss = mysqli_num_rows($resulx);
					if($num_rowss > 0) {
						$num_of_rows = $num_of_rows+$num_rowss;
					}
					 while($rowie = mysqli_fetch_array( $resulx )) {
						 if($rowie['cross_software_approval'] == '' || $rowie['cross_software_approval'] == NULL) {
							 $pending_rows++;
						 }
					 }
				}
				if($num_of_rows > 0) {
					if($pending_rows > 0) {
						$pending_alert = "(".$pending_rows." Pending Approval)";
					} else {
						$pending_alert = "";
					}
					?><div class='mobile-100-container' ><a href='cross_software_pending.php'><button type="button" class="btn brand-btn mobile-block mobile-100" >Remote Newsboard Posts <?php echo $pending_alert; ?></button></a></div><br><br><?php
				}
			}
		}
		// END of Pending Cross-Software P.O.

		?>

        <form name="form_sites" method="post" action="" class="form-inline" style="overflow:visible;" role="form">
            <?php
            $search_vendor = '';
            $search_type = '';
            $search_category = '';
            if(isset($_POST['search_user_submit'])) {
                $search_vendor = $_POST['search_vendor'];
					if($search_vendor !== '') {
						$search_any = "AND ((c.name LIKE '%" . $search_vendor . "%' OR c.first_name LIKE '%" . $search_vendor . "%' OR c.last_name LIKE '%" . $search_vendor . "%' OR c.email_address LIKE '%" . $search_vendor . "%' OR c.office_phone LIKE '%" . $search_vendor . "%') OR (ce.newsboard_code LIKE '%" . $search_vendor . "%' OR ce.newsboard_type LIKE '%" . $search_vendor . "%' OR ce.category LIKE '%" . $search_vendor . "%' OR ce.heading LIKE '%" . $search_vendor . "%' OR ce.name LIKE '%" . $search_vendor . "%' OR ce.title LIKE '%" . $search_vendor . "%' OR ce.fee LIKE '%" . $search_vendor . "%'))";
					} else {
						$search_any = "";
					}
                $search_type = $_POST['search_type'];
					if($search_type !== '') {

						$search_type2 = "AND ce.newsboard_type ='$search_type'";
					} else {
						$search_type2 = '';
					}
				$search_category = $_POST['search_category'];
					if($search_category !== '') {

						$search_category2 = "AND ce.category ='$search_category'";
					} else {
						$search_category2 = '';
					}
            }
            if (isset($_POST['display_all_inventory'])) {
                $search_vendor = '';
                $search_type = '';
                $search_category = '';
            }
            ?>
            <div class="form-group">
                <label for="search_vendor" class="col-sm-2 control-label">Search By Any:</label>
                <div class="col-sm-8">
                <?php if($search_vendor !== '') { ?>
                    <input type="text" name="search_vendor" class="form-control" value="<?php echo $_POST['search_vendor']?>" onchange="submitForm()">
                <?php } else { ?>
                    <input type="text" name="search_vendor" class="form-control" onchange="submitForm()">
                <?php } ?>
                </div>
            </div>

            <div class="form-group">
              <label for="site_name" class="col-sm-4 control-label">Search By Type:</label>
              <div class="col-sm-8">
                  <select data-placeholder="Pick a Type" name="search_type" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(newsboard_type) FROM newsboard WHERE deleted=0 order by newsboard_type");
                    while($row1 = mysqli_fetch_array($query)) {
                    ?><option <?php if ($row1['newsboard_type'] == $search_type) { echo " selected"; } ?> value='<?php echo  $row1['newsboard_type']; ?>' ><?php echo $row1['newsboard_type']; ?></option>
                <?php	}
                ?>
                </select>
              </div>
            </div>
			<?php $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT newsboard_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['newsboard_dashboard'].','; ?>
            <div class="form-group" <?php if (strpos($value_config, ','."Category".',') == FALSE) { echo "style='display:none;'"; } ?>>
              <label for="site_name" class="col-sm-4 control-label">Search By Category:</label>
              <div class="col-sm-8">
                  <select data-placeholder="Pick a Category" name="search_category" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(category) FROM newsboard WHERE deleted=0 order by category");
                    while($row2 = mysqli_fetch_array($query)) {
                    ?><option <?php if ($row2['category'] == $search_category) { echo " selected"; } ?> value='<?php echo  $row2['category']; ?>' ><?php echo $row2['category']; ?></option>
                <?php	}
                ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-12">
                <!--<span class="popover-examples list-inline" style="margin:0 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Remember to fill in one of the fields before clicking Search."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button>-->

				<span class="popover-examples list-inline" style="margin:0 2px 0 15px;"><a data-toggle="tooltip" data-placement="top" title="Refreshes the page to display all of the News Boards."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block">Display All</button>
              </div>
            </div>

            <br><br>

            <?php
            if(vuaed_visible_function($dbc, 'newsboard') == 1) {
                echo '<a href="add_newsboard.php" class="btn brand-btn mobile-block pull-right">Add News Board Post</a><span class="popover-examples list-inline pull-right" style="margin:7px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click to add a News Board post."><img src="'. WEBSITE_URL .'/img/info.png" width="20"></a></span><br /><br /><br />';
            }
            ?>

            <div id="no-more-tables">

            <?php

            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            if($search_type !== '' || $search_category !== '' || $search_vendor !== '' ) {
                $query_check_credentials = "SELECT c.*, ce.* FROM contacts c, newsboard ce WHERE (ce.deleted = 0 AND c.contactid=ce.contactid) $search_any $search_category2 $search_type2 ORDER BY c.name, c.last_name ASC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(c.contactid) as numrows FROM contacts c, newsboard ce WHERE (ce.deleted = 0 AND c.contactid=ce.contactid) $search_any $search_category2 $search_type2 ORDER BY c.name, c.last_name ASC";
            } else {
                $query_check_credentials = "SELECT * FROM newsboard WHERE deleted = 0 ORDER BY newsboardid DESC";
                $query = "SELECT count(*) as numrows FROM newsboard WHERE deleted = 0";
            }
            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {


                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT newsboard_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['newsboard_dashboard'].',';

                // Added Pagination //
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-sm hidden-xs'>";
                    if (strpos($value_config, ','."Staff".',') !== FALSE) {
                        echo '<th>Staff</th>';
                    }
                    if (strpos($value_config, ','."News Board Code".',') !== FALSE) {
                        echo '<th>News Board Code</th>';
                    }
                    if (strpos($value_config, ','."News Board Type".',') !== FALSE) {
                        echo '<th>News Board Type</th>';
                    }
                    if (strpos($value_config, ','."Category".',') !== FALSE) {
                        echo '<th>Category</th>';
                    }
                    if (strpos($value_config, ','."Title".',') !== FALSE) {
                        echo '<th>Title</th>';
                    }

                    if (strpos($value_config, ','."Issue Date".',') !== FALSE) {
                        echo '<th>Issue Date</th>';
                    }
                    if (strpos($value_config, ','."Expiry Date".',') !== FALSE) {
                        echo '<th>Expiry Date</th>';
                    }
                    if (strpos($value_config, ','."Reminder Date".',') !== FALSE) {
                        echo '<th>Reminder Date</th>';
                    }

                    if (strpos($value_config, ','."Uploader".',') !== FALSE) {
                        echo '<th>Header Image</th>';
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
					if(isset($dbczen) && (isset($sea_software_dbc))) {
						echo '<th>Status</th>';
					}
                    echo '<th>Function</th>';
                    echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }
            while($row = mysqli_fetch_array( $result ))
            {
                $style = '';
                if(date('Y-m-d') == $row['reminder_date']) {
                    $style = 'style = color:red;';
                }
                echo "<tr ".$style.">";
                $newsboardid = $row['newsboardid'];
                if (strpos($value_config, ','."Staff".',') !== FALSE) {
                    echo '<td data-title="Staff">' . get_staff($dbc, $row['contactid']) . '</td>';
                }
                if (strpos($value_config, ','."News Board Code".',') !== FALSE) {
                    echo '<td data-title="News Board Code">' . $row['newsboard_code'] . '</td>';
                }
                if (strpos($value_config, ','."News Board Type".',') !== FALSE) {
       				echo '<td data-title="News Board Type">' . $row['newsboard_type'] . '</td>';
                }
                if (strpos($value_config, ','."Category".',') !== FALSE) {
                    echo '<td data-title="Category">' . $row['category'] . '</td>';
                }

                if (strpos($value_config, ','."Title".',') !== FALSE) {
                    echo '<td data-title="Title">' . $row['title'] . '</td>';
                }

                if (strpos($value_config, ','."Issue Date".',') !== FALSE) {
                    echo '<td data-title="Issue Date">' . $row['issue_date'] . '</td>';
                }
                if (strpos($value_config, ','."Expiry Date".',') !== FALSE) {
                    echo '<td data-title="Expiry Date">' . $row['expiry_date'] . '</td>';
                }
                if (strpos($value_config, ','."Reminder Date".',') !== FALSE) {
                    echo '<td data-title="Reminder Date">' . $row['reminder_date'] . '</td>';
                }

                if (strpos($value_config, ','."Uploader".',') !== FALSE) {
                    echo '<td data-title="Documents">';
                    $newsboard_uploads1 = "SELECT * FROM newsboard_uploads WHERE newsboardid='$newsboardid' AND type = 'Document' ORDER BY certuploadid DESC";
                    $result1 = mysqli_query($dbc, $newsboard_uploads1);
                    $num_rows1 = mysqli_num_rows($result1);
                    if($num_rows1 > 0) {
						while($row1 = mysqli_fetch_array($result1)) {
							//echo '<a href="download/'.$row1['document_link'].'" target="_blank">'.$row1['document_link'].'</a><br />';
							$image = ( substr ( $row1['document_link'], 0, 7 ) == 'http://' ) ? $row1['document_link'] : 'download/' . $row1['document_link'];
							echo '<a href="' . $image . '" target="_blank">' . $row1['document_link'] . '</a><br />';
                        }
                    }
                    echo '</td>';
                }
                if (strpos($value_config, ','."Link".',') !== FALSE) {
                    echo '<td data-title="Link">';
                    $newsboard_uploads2 = "SELECT * FROM newsboard_uploads WHERE newsboardid='$newsboardid' AND type = 'Link' ORDER BY certuploadid DESC";
                    $result2 = mysqli_query($dbc, $newsboard_uploads2);
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
                    echo '<td data-title="Name">' . decryptIt($row['name']) . '</td>';
                }
                if (strpos($value_config, ','."Fee".',') !== FALSE) {
                    echo '<td data-title="Fee">' . $row['fee'] . '</td>';
                }
                if (strpos($value_config, ','."Cost".',') !== FALSE) {
                    echo '<td data-title="Cost">' . $row['cost'] . '</td>';
                }
                if (strpos($value_config, ','."Description".',') !== FALSE) {
                    $desc = html_entity_decode ( $row['description'] );
					echo '<td data-title="Description">' . limit_text( $desc, 80 ) . '</td>';
                }
                if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
                    echo '<td data-title="Quote Description">' . html_entity_decode($row['quote_description']) . '</td>';
                }
                if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                    echo '<td data-title="Invoice Description">' . html_entity_decode($row['invoice_description']) . '</td>';
                }
                if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                    echo '<td data-title="'.TICKET_NOUN.' Description">' . html_entity_decode($row['ticket_description']) . '</td>';
                }
                if (strpos($value_config, ','."Final Retail Price".',') !== FALSE) {
                    echo '<td data-title="Final Retail Price">' . $row['final_retail_price'] . '</td>';
                }
                if (strpos($value_config, ','."Admin Price".',') !== FALSE) {
                    echo '<td data-title="Admin Price">' . $row['admin_price'] . '</td>';
                }
                if (strpos($value_config, ','."Wholesale Price".',') !== FALSE) {
                    echo '<td data-title="Wholesale Price">' . $row['wholesale_price'] . '</td>';
                }
                if (strpos($value_config, ','."Commercial Price".',') !== FALSE) {
                    echo '<td data-title="Commercial Price">' . $row['commercial_price'] . '</td>';
                }
                if (strpos($value_config, ','."Client Price".',') !== FALSE) {
                    echo '<td data-title="Client Price">' . $row['client_price'] . '</td>';
                }
                if (strpos($value_config, ','."Minimum Billable".',') !== FALSE) {
                    echo '<td data-title="Minimum Billable">' . $row['minimum_billable'] . '</td>';
                }
                if (strpos($value_config, ','."Estimated Hours".',') !== FALSE) {
                    echo '<td data-title="Estimated Hours">' . $row['estimated_hours'] . '</td>';
                }
                if (strpos($value_config, ','."Actual Hours".',') !== FALSE) {
                    echo '<td data-title="Actual Hours">' . $row['actual_hours'] . '</td>';
                }
                if (strpos($value_config, ','."MSRP".',') !== FALSE) {
                    echo '<td data-title="MRSP">' . $row['msrp'] . '</td>';
                }
				if(isset($dbczen) && (isset($sea_software_dbc))) {
						echo '<td data-title="Status">';
						if ($row['cross_software_approval'] !== "" && $row['cross_software_approval'] !== NULL && $row['cross_software_approval'] !== 'disapproved') { ?>
						Approved <img src="../img/checkmark.png" width="25px" class="wiggle-me">
						<?php
						} else if($row['cross_software_approval'] == "disapproved") {
							echo 'Disapproved <img src="../img/icons/forbidden.png" width="25px" class="wiggle-me">';
						} else {
							echo 'Awaiting approval <img src="../img/icons/locked-2.png" width="25px" class="wiggle-me">';
						}
						echo '</td>';
				}
                echo '<td data-title="Function">';
                if(vuaed_visible_function($dbc, 'newsboard') == 1) {
                echo '<a href=\'add_newsboard.php?newsboardid='.$newsboardid.'\'>Edit</a> | ';
				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&newsboardid='.$newsboardid.'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
                }
                echo '</td>';

                echo "</tr>";
            }

            echo '</table></div>';

            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

            if(vuaed_visible_function($dbc, 'newsboard') == 1) {
            echo '<a href="add_newsboard.php" class="btn brand-btn mobile-block pull-right">Add News Board Post</a><span class="popover-examples list-inline pull-right" style="margin:7px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click to add a News Board post."><img src="'. WEBSITE_URL .'/img/info.png" width="20"></a></span>';
            }

            ?>
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
