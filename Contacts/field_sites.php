<?php
/*
Sites Listing
*/
$tab_result = mysqli_fetch_array(mysqli_query($dbc, "select value from general_configuration where name='field_job_tabs'"));
$tab_config = $tab_result['value'];
$dashboard_result = mysqli_fetch_array(mysqli_query($dbc, "select dashboard_list from field_config_field_jobs where tab='sites'"));
$dashboard_config = $dashboard_result['dashboard_list'];
if(str_replace(',','',$dashboard_config) == '') {
	$dashboard_config = ',site_name,';
}
$level = trim(ROLE,',');
?>
</head>
<body>
<?php include_once ('../navigation.php');

?>

<div class="container">
	<div class="row">
		<div class="col-md-12">

        <form name="form_clients" method="post" action="" class="form-inline" role="form">

            <center>
            <div class="form-group">
                <label for="site_name" class="col-sm-5 control-label">Search By Any:</label>
                <div class="col-sm-6">
                    <?php if(isset($_POST['search_site_name_submit'])) { ?>
                        <input type="text" name="search_site_name" value="<?php echo $_POST['search_site_name']?>" class="form-control"  style='max-width:200px;'>
                    <?php } else { ?>
                        <input type="text" name="search_site_name" class="form-control"  style='max-width:200px;'>
                    <?php } ?>
                </div>
            </div>
            &nbsp;
				<button type="submit" name="search_site_name_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
			    <button type="submit" name="display_all_site_name" value="Display All" class="btn brand-btn mobile-block">Display All</button>
            </center>

			<?php
			echo '<a href="add_field_site.php" class="btn brand-btn mobile-block pull-right">Add Site</a>';
			?>

		<div class='no-more-tables'>
			<?php
            $site_name = '';
            if (isset($_POST['search_site_name_submit'])) {
                $site_name = $_POST['search_site_name'];
            }
            if (isset($_POST['display_all_site_name'])) {
                $site_name = '';
            }

            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            if($site_name != '') {
                $query_check_credentials = "SELECT s.* FROM field_sites s, contacts c WHERE s.deleted = 0 AND c.contactid = s.clientid AND (c.name LIKE '%" . $site_name . "%' OR s.site_name LIKE '%" . $site_name . "%' OR s.domain_name LIKE '%" . $site_name . "%' OR s.display_name LIKE '%" . $site_name . "%' OR s.phone_number LIKE '%" . $site_name . "%' OR s.fax_number LIKE '%" . $site_name . "%' OR s.mail_street LIKE '%" . $site_name . "%' OR s.mail_country LIKE '%" . $site_name . "%' OR s.mail_city LIKE '%" . $site_name . "%' OR s.mail_state LIKE '%" . $site_name . "%' OR s.mail_zip LIKE '%" . $site_name . "%') LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(s.clientid) as numrows FROM field_sites s, contacts c WHERE s.deleted = 0 AND c.contactid = s.clientid AND (c.name LIKE '%" . $site_name . "%' OR s.site_name LIKE '%" . $site_name . "%' OR s.domain_name LIKE '%" . $site_name . "%' OR s.display_name LIKE '%" . $site_name . "%' OR s.phone_number LIKE '%" . $site_name . "%' OR s.fax_number LIKE '%" . $site_name . "%' OR s.mail_street LIKE '%" . $site_name . "%' OR s.mail_country LIKE '%" . $site_name . "%' OR s.mail_city LIKE '%" . $site_name . "%' OR s.mail_state LIKE '%" . $site_name . "%' OR s.mail_zip LIKE '%" . $site_name . "%')";
            } else {
                $query_check_credentials = "SELECT * FROM field_sites WHERE deleted = 0 LIMIT $offset, $rowsPerPage";
                $query = "SELECT count(*) as numrows FROM field_sites WHERE deleted = 0";
            }

			$result = mysqli_query($dbc, $query_check_credentials);

			$num_rows = mysqli_num_rows($result);
			if($num_rows > 0) {

            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

			echo "<table class='table table-bordered'>";
			echo "<tr class='hidden-xs hidden-sm'>
					".(strpos($dashboard_config,',site_name,') !== false ? "<th>Site Name</th>" : "")."
					".(strpos($dashboard_config,',customer,') !== false ? "<th>Customer</th>" : "")."
                    ".(strpos($dashboard_config,',website,') !== false ? "<th>Website</th>" : "")."
                    ".(strpos($dashboard_config,',display,') !== false ? "<th>Display Name</th>" : "")."
					".(strpos($dashboard_config,',address,') !== false ? "<th>Address</th>" : "")."
					".(strpos($dashboard_config,',phone,') !== false ? "<th>Phone Number</th>" : "")."
					".(strpos($dashboard_config,',fax,') !== false ? "<th>Fax Number</th>" : "")."
                    <th>Function</th>
					</tr>";
			} else{
				echo "<h2>No Record Found.</h2>";
			}
			while($row = mysqli_fetch_array( $result ))
			{
				echo '<tr>';
				if(strpos($dashboard_config,',site_name,') !== false) {
					echo '<td data-title="Site Name"><a href=\'add_field_site.php?siteid='.$row['siteid'].'\'>' . $row['site_name'] . '</a></td>';
				}
				if(strpos($dashboard_config,',customer,') !== false) {
					echo '<td data-title="Customer">'.get_client($dbc, $row['clientid']).'</td>';
				}
                if(strpos($dashboard_config,',website,') !== false) {
					echo '<td data-title="Website">' . $row['domain_name'] . '</td>';
				}
                if(strpos($dashboard_config,',display,') !== false) {
					echo '<td data-title="Display Name">' . $row['display_name'] . '</td>';
				}
				if(strpos($dashboard_config,',address,') !== false) {
					echo '<td data-title="Address">' . $row['office_street']. ' '.$row['office_country'] . ' '.$row['office_city']. ' '.$row['office_state']. ' '.$row['office_zip']. '</td>';
				}
				if(strpos($dashboard_config,',phone,') !== false) {
					echo '<td data-title="Phone Number">' . $row['phone_number'] . '</td>';
				}
				if(strpos($dashboard_config,',fax,') !== false) {
					echo '<td data-title="Fax Number">' . $row['fax_number'] . '</td>';
				}

                echo '<td data-title="Function">';
				echo '<a href=\'add_field_site.php?siteid='.$row['siteid'].'\'>Edit</a> | ';
				echo '<a href=\'../delete_restore.php?action=delete&contact_fieldsiteid='.$row['siteid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
				echo '</td>';

				echo "</tr>";
			}

            echo '</table></div>';

            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

			echo '<a href="add_field_site.php" class="btn brand-btn mobile-block pull-right">Add Site</a>';
			?>
		</form>
		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>
