<?php
/*
Customer Listing
*/
include_once ('../include.php');
error_reporting(0);
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('certificate');
if(!isset($_GET['staff_status'])) {
	$staff_status = 1;
} else {
	$staff_status = $_GET['staff_status'];
}
$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT certificate, certificate_dashboard FROM field_config"));
$field_config = $get_field_config['certificate'];
$staff_label = [];
if(strpos($field_config,'Staff') !== FALSE) {
	$staff_label[] = 'Staff';
}
if(strpos($field_config,'Project') !== FALSE || strpos($field_config,'Jobs') !== FALSE) {
	$staff_label[] = 'Project';
}
$staff_label = implode(' / ',$staff_label);
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">

        <h1 class="">Certificates Dashboard
        
		<?php
        if(config_visible_function($dbc, 'certificate') == 1) {
			echo '<a href="field_config_certificate.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
            echo '<span class="popover-examples list-inline pull-right"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span><br><br>';
        }
        ?>
        </h1>
		
		<p>
            <?php if ( check_subtab_persmission($dbc, 'certificate', ROLE, 'cert_active') === TRUE ) { ?>
                <a href="certificate.php?staff_status=1"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= $staff_status ? 'active_tab' : '' ?>">Active <?= $staff_label ?></button></a>
            <?php } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Active <?= $staff_label ?></button>
            <?php } ?>
            
            <?php if ( check_subtab_persmission($dbc, 'certificate', ROLE, 'cert_suspended') === TRUE ) { ?>
                <a href="certificate.php?staff_status=0"><button type="button" class="btn brand-btn mobile-block mobile-100 <?= $staff_status ? '' : 'active_tab' ?>">Suspended <?= $staff_label ?></button></a>
            <?php } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Suspended <?= $staff_label ?></button>
            <?php } ?>
            
            <?php if ( check_subtab_persmission($dbc, 'certificate', ROLE, 'cert_followup') === TRUE ) { ?>    
                <a href="certificate_followup.php?staff_status=1"><button type="button" class="btn brand-btn mobile-block mobile-100">Follow Up</button></a>
            <?php } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Follow Up</button>
            <?php } ?>
            
            <?php if ( check_subtab_persmission($dbc, 'certificate', ROLE, 'cert_reporting') === TRUE ) { ?>
                <a href="certificate_reporting.php?staff_status=1"><button type="button" class="btn brand-btn mobile-block mobile-100">Reporting</button></a>
            <?php } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Reporting</button>
            <?php } ?>
        </p>

        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <?php
            $search_vendor = '';
            $search_type = '';
            $search_category = '';
            $search_staff = '';
            $search_expiry_start = '';
            $search_expiry_end = '';
            
            /* Get search parameters */
            if ( isset($_GET['s']) && !empty($_GET['s']) ) {
                $search_params = str_replace(' ', '+', $_GET['s']);
                $search_params = decryptIt($search_params);
                list($search_vendor, $search_type, $search_category, $search_staff, $search_expiry_start, $search_expiry_end) = explode(',', $search_params);
            }
            
            if(isset($_POST['search_user_submit']) || isset($_GET['s'])) {
                if ( isset($_POST['search_user_submit']) ) {
                    $search_vendor = $_POST['search_vendor'];
                }
					if($search_vendor !== '') {
						$search_any = "AND ((c.name LIKE '%" . $search_vendor . "%' OR c.first_name LIKE '%" . $search_vendor . "%' OR c.last_name LIKE '%" . $search_vendor . "%' OR c.email_address LIKE '%" . $search_vendor . "%' OR c.office_phone LIKE '%" . $search_vendor . "%') OR (ce.certificate_code LIKE '%" . $search_vendor . "%' OR ce.certificate_type LIKE '%" . $search_vendor . "%' OR ce.category LIKE '%" . $search_vendor . "%' OR ce.heading LIKE '%" . $search_vendor . "%' OR ce.name LIKE '%" . $search_vendor . "%' OR ce.title LIKE '%" . $search_vendor . "%' OR ce.fee LIKE '%" . $search_vendor . "%'))";
					} else {
						$search_any = "";
					}
                if ( isset($_POST['search_user_submit']) ) {
                    $search_type = $_POST['search_type'];
                }
					if($search_type !== '') {

						$search_type2 = "AND ce.certificate_type ='$search_type'";
					} else {
						$search_type2 = '';
					}
				if ( isset($_POST['search_user_submit']) ) {
                    $search_category = $_POST['search_category'];
                }
					if($search_category !== '') {

						$search_category2 = "AND ce.category ='$search_category'";
					} else {
						$search_category2 = '';
					}
				if ( isset($_POST['search_user_submit']) ) {
                    $search_staff = $_POST['search_staff'];
                }
					if($search_staff !== '') {
						$staff_query = "AND (ce.contactid = '$search_staff' OR ce.jobid='$search_staff' OR ce.client_projectid='$search_staff' OR ce.projectid='$search_staff')";
					} else {
						$staff_query = '';
					}
				if ( isset($_POST['search_user_submit']) ) {
                    $search_expiry_start = $_POST['search_expiry_start'];
                    $search_expiry_end = $_POST['search_expiry_end'];
                }
					if($search_expiry_start !== '') {
						$expiry_query = "AND ce.expiry_date >= '$search_expiry_start'";
					} else {
						$expiry_query = '';
					}
					if($search_expiry_end !== '') {
						$expiry_query .= "AND ce.expiry_date <= '$search_expiry_end'";
					} else {
						$expiry_query .= '';
					}
                    
                $hash = $search_vendor.','.$search_type.','.$search_category.','.$search_staff.','.$search_expiry_start.','.$search_expiry_end;
                $hash = encryptIt($hash);
            
            } else {
                $hash = '';
            }
            
            if (isset($_POST['display_all_inventory'])) {
                $search_vendor = '';
                $search_type = '';
                $search_category = '';
				$search_staff = '';
				$search_expiry_start = '';
				$search_expiry_end = '';
            }
            ?>
			
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
              <label for="search_staff"><?= $staff_label ?>:</label>
			</div>
			  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
				  <select data-placeholder="Select a <?= $staff_label ?>" name="search_staff" class="chosen-select-deselect form-control" width="380">
				  <option value=""></option>
				  <?php
					$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT distinct cert.`contactid`, c.`last_name`, c.`first_name` FROM `certificate` cert LEFT JOIN `contacts` c ON cert.`contactid`=c.`contactid` WHERE cert.`deleted`=0 AND c.`status`='$staff_status' UNION
						SELECT distinct cert.`projectid`, p.`project_name`, '' FROM `certificate` cert LEFT JOIN `project` p ON cert.`projectid`=p.`projectid` WHERE cert.`deleted`=0 AND (p.`status`='Approve as Project')='$staff_status' UNION
						SELECT distinct cert.`projectid`, p.`project_name`, '' FROM `certificate` cert LEFT JOIN `jobs` p ON cert.`projectid`=p.`projectid` WHERE cert.`deleted`=0 AND (p.`status`='Approve as Project')='$staff_status' UNION
						SELECT distinct cert.`projectid`, p.`project_name`, '' FROM `certificate` cert LEFT JOIN `client_project` p ON cert.`projectid`=p.`projectid` WHERE cert.`deleted`=0 AND (p.`status`='Approve as Project')='$staff_status'"),MYSQLI_ASSOC));
					foreach($query as $staff_id) {
					?><option <?php if ($staff_id == $search_staff) { echo " selected"; } ?> value='<?=  $staff_id ?>' ><?php echo get_contact($dbc, $staff_id); ?></option>
				<?php	}
				?>
				</select>
			  </div>

            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
              <label for="site_name">Type:</label>
			</div>
              <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
                  <select data-placeholder="Select a Type" name="search_type" class="chosen-select-deselect form-control" width="380">
                  <option value=""></option>
                  <?php
                    $query = mysqli_query($dbc,"SELECT distinct(certificate_type) FROM certificate WHERE deleted=0 order by certificate_type");
                    while($row1 = mysqli_fetch_array($query)) {
                    ?><option <?php if ($row1['certificate_type'] == $search_type) { echo " selected"; } ?> value='<?php echo  $row1['certificate_type']; ?>' ><?php echo $row1['certificate_type']; ?></option>
                <?php	}
                ?>
                </select>
              </div>
			
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4"><label>Expiry Date Start:</label></div>
				<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
					<input type="text" name="search_expiry_start" class="form-control datepicker" value="<?php echo $search_expiry_start; ?>">
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4"><label>Expiry Date End:</label></div>
				<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
					<input type="text" name="search_expiry_end" class="form-control datepicker" value="<?php echo $search_expiry_end; ?>">
				</div>
			
			<?php $value_config = ','.$get_field_config['certificate_dashboard'].','; ?>
			<div <?php if (strpos($value_config, ','."Category".',') == FALSE) { echo "style='display:none;'"; } ?>>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
				  <label for="site_name">Category:</label>
				</div>
				  <div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
					  <select data-placeholder="Pick a Category" name="search_category" class="chosen-select-deselect form-control" width="380">
					  <option value=""></option>
					  <?php
						$query = mysqli_query($dbc,"SELECT distinct(category) FROM certificate WHERE deleted=0 order by category");
						while($row2 = mysqli_fetch_array($query)) {
						?><option <?php if ($row2['category'] == $search_category) { echo " selected"; } ?> value='<?php echo  $row2['category']; ?>' ><?php echo $row2['category']; ?></option>
					<?php	}
					?>
					</select>
				  </div>
			</div>
				
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                <label for="search_vendor">Search by All:</label>
			</div>
			<div class="col-lg-4 col-md-3 col-sm-8 col-xs-8">
			<?php if($search_vendor !== '') { ?>
				<input type="text" name="search_vendor" class="form-control" value="<?php echo $_POST['search_vendor']?>">
			<?php } else { ?>
				<input type="text" name="search_vendor" class="form-control">
			<?php } ?>
			</div>

            <div class="form-group col-sm-8 pull-right">
                <div>
					<button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block pull-right">Search</button>
					<span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 15px;"><a data-toggle="tooltip" data-placement="top" title="Click this once you have selected the above."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				</div>
				<div>
					<button type="submit" name="display_all_inventory" value="Display All" class="btn brand-btn mobile-block pull-right">Display All</button>
					<span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="This refreshes the page to view all certificates."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				</div>
            </div>
			<div class="clearfix"></div>

            <br><br>

            <?php
            if(vuaed_visible_function($dbc, 'certificate') == 1) {
                echo '<a href="add_certificate.php" class="btn brand-btn mobile-block pull-right">Add Certificate</a>';
				echo '<span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add Certificates."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
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

            if($search_type !== '' || $search_category !== '' || $search_vendor !== '' || $search_staff !== '' || $search_expiry_start !== '' || $search_expiry_end !== '' ) {
                $query_check_credentials = "SELECT c.*, ce.* FROM contacts c, certificate ce WHERE (ce.deleted = 0 AND c.contactid=ce.contactid) AND (".($staff_status == 1 ? "c.`status`='1'" : "IFNULL(c.`status`,0)='0' OR IFNULL(c.`deleted`,1)=1").") $search_any $search_category2 $search_type2 $staff_query $expiry_query ORDER BY expiry_date ASC, c.name, c.last_name ASC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(c.contactid) as numrows FROM contacts c, certificate ce WHERE (ce.deleted = 0 AND c.contactid=ce.contactid) AND (".($staff_status == 1 ? "c.`status`='1'" : "IFNULL(c.`status`,0)='0' OR IFNULL(c.`deleted`,1)=1").") $search_any $search_category2 $search_type2 $staff_query $expiry_query ORDER BY c.name, c.last_name ASC";
            } else {
                $query_check_credentials = "SELECT certificate.*, CONCAT(IFNULL(`project`.`project_name`,''),IFNULL(`jobs`.`project_name`,''),IFNULL(`client_project`.`project_name`,'')) p_name FROM certificate LEFT JOIN `contacts` ON `certificate`.`contactid`=`contacts`.`contactid` AND `contacts`.`status`='1' AND `contacts`.`deleted`=0 LEFT JOIN `project` ON `certificate`.`projectid`=`project`.`projectid` AND `project`.`deleted`=0 AND `project`.`status`='Approve as Project' LEFT JOIN `client_project` ON `certificate`.`client_projectid`=`client_project`.`projectid` AND `client_project`.`deleted`=0 AND `client_project`.`status`='Approve as Project' LEFT JOIN `jobs` ON `certificate`.`projectid`=`jobs`.`projectid` AND `jobs`.`deleted`=0 AND `jobs`.`status`='Approve as Project' WHERE `certificate`.deleted = 0 AND (`contacts`.`status`".($staff_status == 1 ? "='1'" : " IS NULL")." OR `project`.`status`".($staff_status == 1 ? " IS NOT NULL" : " IS NULL")." OR `client_project`.`status`".($staff_status == 1 ? " IS NOT NULL" : " IS NULL")." OR `jobs`.`status`".($staff_status == 1 ? " IS NOT NULL" : " IS NULL").") ORDER BY `certificate`.expiry_date ASC LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM certificate LEFT JOIN `contacts` ON `certificate`.`contactid`=`contacts`.`contactid` AND `contacts`.`status`='1' AND `contacts`.`deleted`=0 LEFT JOIN `project` ON `certificate`.`projectid`=`project`.`projectid` AND `project`.`deleted`=0 AND `project`.`status`='Approve as Project' LEFT JOIN `client_project` ON `certificate`.`client_projectid`=`client_project`.`projectid` AND `client_project`.`deleted`=0 AND `client_project`.`status`='Approve as Project' LEFT JOIN `jobs` ON `certificate`.`projectid`=`jobs`.`projectid` AND `jobs`.`deleted`=0 AND `jobs`.`status`='Approve as Project' WHERE `certificate`.deleted = 0 AND (`contacts`.`status`".($staff_status == 1 ? "='1'" : " IS NULL")." OR `project`.`status`".($staff_status == 1 ? " IS NOT NULL" : " IS NULL")." OR `client_project`.`status`".($staff_status == 1 ? " IS NOT NULL" : " IS NULL")." OR `jobs`.`status`".($staff_status == 1 ? " IS NOT NULL" : " IS NULL").")";
            }
            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            
            if($num_rows > 0) {
                
                //Added Pagination
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                //Finish Pagination
                
                $value_config = ','.$get_field_config['certificate_dashboard'].',';

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-sm hidden-xs'>";
                    if (strpos($value_config, ','."Staff".',') !== FALSE) {
                        echo '<th>'.$staff_label.'</th>';
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
					
				while($row = mysqli_fetch_array( $result ))
				{
					$style = '';
					if(date('Y-m-d') == $row['reminder_date']) {
						$style = 'style = color:red;';
					}
					echo "<tr ".$style.">";
					$certificateid = $row['certificateid'];
					if (strpos($value_config, ','."Staff".',') !== FALSE) {
						echo '<td data-title="'.$staff_label.'">' . (empty($row['p_name']) ? get_staff($dbc, $row['contactid']) : 'Project #'.(empty($row['client_projectid']) ? (empty($row['projectid']) ? $row['jobid'] : $row['projectid']) : $row['client_projectid']).': '.$row['p_name']) . '</td>';
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
					echo '<a href=\'add_certificate.php?certificateid='.$certificateid.'\'>Edit</a> | ';
					echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&certificateid='.$certificateid.'&s='.$hash.'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
					}
					echo '</td>';

					echo "</tr>";
				}

				echo '</table></div>';
				//Added Pagination
				echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
				//Finish Pagination
            } else {
                echo "<h2>No Record Found.</h2>";
            }
            if(vuaed_visible_function($dbc, 'certificate') == 1) {
            echo '<a href="add_certificate.php" class="btn brand-btn mobile-block pull-right">Add Certificate</a>';
			echo '<span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add Certificates."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
            }

            ?>
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
