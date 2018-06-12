<?php
/*
Customer Listing
*/
include_once ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised();
$contactid = $_SESSION['contactid'];
$subtab = 'certificates';
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <div class="col-sm-10">
			<h1>My Certificates</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'certificate') == 1) {
					echo '<a href="field_config_certificate.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				}
			?>
        </div>
		
		<div class="clearfix"></div>
		
		<div class="tab-container mobile-100-container double-gap-bottom">
			<a href="my_profile.php?subtab=staff"><button class="btn brand-btn mobile-100 <?php echo $subtab == 'staff' ? 'active_tab' : ''; ?>">Staff</button></a>
			<a href="my_profile.php?subtab=profile"><button class="btn brand-btn mobile-100 <?php echo $subtab == 'profile' ? 'active_tab' : ''; ?>">Profile</button></a>
			<a href="my_profile.php?subtab=emergency"><button class="btn brand-btn mobile-100 <?php echo $subtab == 'emergency' ? 'active_tab' : ''; ?>">Emergency</button></a>
			<a href="my_profile.php?subtab=health"><button class="btn brand-btn mobile-100 <?php echo $subtab == 'health' ? 'active_tab' : ''; ?>">Health & Safety</button></a>
			<a href="certificate.php"><button class="btn brand-btn mobile-100 <?php echo $subtab == 'certificates' ? 'active_tab' : ''; ?>">Certificates</button></a>
			<!--<div class="pad-left gap-top" style="padding-bottom: 1em;"><a href="/home.php" class="btn config-btn">Back</a></div>-->
		</div>

            <?php
            if(vuaed_visible_function($dbc, 'certificate') == 1) {
                echo '<a href="../Certificate/add_certificate.php" class="btn brand-btn mobile-block pull-right">Add Certificate</a>';
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

			$query_check_credentials = "SELECT * FROM certificate WHERE deleted = 0 AND `contactid`='$contactid' ORDER BY expiry_date ASC LIMIT $offset, $rowsPerPage";
			$query = "SELECT count(*) as numrows FROM certificate WHERE deleted = 0 AND `contactid`='$contactid'";
            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            
            if($num_rows > 0) {
                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT certificate_dashboard FROM field_config"));
                
                //Added Pagination
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                //Finish Pagination
                
                $value_config = ','.$get_field_config['certificate_dashboard'].',';

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-sm hidden-xs'>";
                    if (strpos($value_config, ','."Staff".',') !== FALSE) {
                        echo '<th>Staff</th>';
                    }
                    if (strpos($value_config, ','."Certificate Code".',') !== FALSE) {
                        echo '<th>Certificate Code</th>';
                    }
                    if (strpos($value_config, ','."Certificate Type".',') !== FALSE) {
                        echo '<th>Certificate Type</th>';
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
                $certificateid = $row['certificateid'];
                if (strpos($value_config, ','."Staff".',') !== FALSE) {
                    echo '<td data-title="Staff">' . get_staff($dbc, $row['contactid']) . '</td>';
                }
                if (strpos($value_config, ','."Certificate Code".',') !== FALSE) {
                    echo '<td data-title="Certificate Code">' . $row['certificate_code'] . '</td>';
                }
                if (strpos($value_config, ','."Certificate Type".',') !== FALSE) {
       				echo '<td data-title="Certificate Type">' . $row['certificate_type'] . '</td>';
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
                    $certificate_uploads1 = "SELECT * FROM certificate_uploads WHERE certificateid='$certificateid' AND type = 'Document' ORDER BY certuploadid DESC";
                    $result1 = mysqli_query($dbc, $certificate_uploads1);
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
                    echo '<td data-title="Link">';
                    $certificate_uploads2 = "SELECT * FROM certificate_uploads WHERE certificateid='$certificateid' AND type = 'Link' ORDER BY certuploadid DESC";
                    $result2 = mysqli_query($dbc, $certificate_uploads2);
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

                echo '<td data-title="Function">';
                if(vuaed_visible_function($dbc, 'certificate') == 1) {
                echo '<a href=\'../Certificate/add_certificate.php?certificateid='.$certificateid.'\'>Edit</a> | ';
				echo '<a href=\''.WEBSITE_URL.'/Certificate/delete_restore.php?action=delete&certificateid='.$certificateid.'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
                echo '</td>';

                echo "</tr>";
            }

            echo '</table></div>';
            //Added Pagination
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            //Finish Pagination
            if(vuaed_visible_function($dbc, 'certificate') == 1) {
            echo '<a href="../Certificate/add_certificate.php" class="btn brand-btn mobile-100 mobile-block pull-right">Add Certificate</a>';
            }

            ?>
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
