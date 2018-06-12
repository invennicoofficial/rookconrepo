<?php
/*
Inventory Listing
*/
include ('../include.php');

?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised();
?>
<div class="container">
	<div class="row">

        <h1 class="single-pad-bottom">Contacts
        <?php
        if(config_visible_function($dbc, 'contacts') == 1) {
            echo '<a href="field_config_contacts.php?type=tab" class="mobile-block pull-right"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a><br><br>';
        }
        ?>
        </h1>

		<form name="form_sites" method="post" action="" class="form-inline" role="form">

            <?php
                $category = $_GET['category'];
                $tabs = get_config($dbc, 'contacts_tabs');
                $each_tab = explode(',', $tabs);

                $active_all = '';
                if(empty($_GET['category']) || $_GET['category'] == 'Top') {
                    $active_all = 'active_tab';
                }

                foreach ($each_tab as $cat_tab) {
                    $active_daily = '';
                    if((!empty($_GET['category'])) && ($_GET['category'] == $cat_tab)) {
                        $active_daily = 'active_tab';
                    }
                    echo "<a href='contacts.php?category=".$cat_tab."&filter=Top'><button type='button' class='btn brand-btn mobile-block ".$active_daily."' >".$cat_tab."</button></a>&nbsp;&nbsp;";
                }

                //echo display_filter('contacts.php');
            ?>
            <br><br>
			<label for="search_contacts">Search By Any:</label>
			<?php if(isset($_POST['search_contacts_submit'])) { ?>
				<input type="text" name="search_contacts" value="<?php echo $_POST['search_contacts']?>" class="form-control">
			<?php } else { ?>
				<input type="text" name="search_contacts" class="form-control">
			<?php } ?>
            <!--
            <div class="form-group gap-right">
                <label for="search_category" class="control-label">Search By Category:</label>

                <select name="search_category" class="form-control col-6">
                  <option value="" selected>Select</option>
                  <?php
                    $tabs = get_config($dbc, 'contacts_tabs');
                    $each_tab = explode(',', $tabs);
                    foreach ($each_tab as $cat_tab) {
                        if ($invtype == $cat_tab) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $cat_tab."'>".$cat_tab.'</option>';
                    }
                  ?>
                </select>
            </div>
            -->
			<button type="submit" name="search_contacts_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
			<button type="submit" name="display_all_contacts" value="Display All" class="btn brand-btn mobile-block">Display All</button>
            <?php
            if(vuaed_visible_function($dbc, 'contacts') == 1) {
			echo '<a href="add_contacts.php?category='.$category.'" class="btn brand-btn mobile-block gap-bottom pull-right">Add '.$category.'</a>';
                        }
            ?>
		<div id="no-more-tables">
			<?php
			// Display Pager
			$contacts = '';
			if (isset($_POST['search_contacts_submit'])) {
				$contacts = $_POST['search_contacts'];
                if (isset($_POST['search_contacts'])) {
                    $contacts = $_POST['search_contacts'];
                }
                if (isset($_POST['search_category'])) {
					if ($_POST['search_category'] != '') {
						$contacts = $_POST['search_category'];
					}
				}
			}
			if (isset($_POST['display_all_contacts'])) {
				$contacts = '';
			}

            echo display_filter_param('contacts.php?category='.$category);

			if($contacts != '') {
				$query_check_credentials = "SELECT c.*, cc.*,cd.*,cdes.* FROM contacts c, contacts_cost cc, contacts_dates cd, contacts_description cdes WHERE (deleted = 0 AND category='$category' AND c.contactid=cc.contactid AND c.contactid=cd.contactid AND c.contactid=cdes.contactid) AND (c.name LIKE '%" . $contacts . "%' OR c.first_name LIKE '%" . $contacts . "%' OR c.last_name LIKE '%" . $contacts . "%' OR c.role LIKE '%" . $contacts . "%' OR c.email_address LIKE '%" . $contacts . "%' OR c.category = '$contacts' OR c.office_phone LIKE '%" . $contacts . "%') ORDER BY c.name, c.last_name ASC";

			} else {
                $category = $_GET['category'];

                if(isset($_GET['filter'])) { $url_search = $_GET['filter']; } else { $url_search = ''; }
                if($url_search == 'Top') {
                    $query_check_credentials = "SELECT c.*, cc.*,cd.*,cdes.* FROM contacts c, contacts_cost cc, contacts_dates cd, contacts_description cdes WHERE deleted = 0 AND category='$category' AND c.contactid=cc.contactid AND c.contactid=cd.contactid AND c.contactid=cdes.contactid ORDER BY c.name, c.last_name ASC LIMIT 10";
                } else if($url_search == 'All') {
                    $query_check_credentials = "SELECT c.*, cc.*,cd.*,cdes.* FROM contacts c, contacts_cost cc, contacts_dates cd, contacts_description cdes WHERE deleted = 0 AND category='$category' AND c.contactid=cc.contactid AND c.contactid=cd.contactid AND c.contactid=cdes.contactid ORDER BY c.name, c.last_name ASC";
                } else {
                    $query_check_credentials = "SELECT c.*, cc.*,cd.*,cdes.* FROM contacts c, contacts_cost cc, contacts_dates cd, contacts_description cdes WHERE deleted = 0 AND category='$category' AND c.contactid=cc.contactid AND c.contactid=cd.contactid AND c.contactid=cdes.contactid AND (name LIKE '" . $url_search . "%' OR first_name LIKE '" . $url_search . "%') ORDER BY c.name, c.last_name ASC";
                }

                //echo $query_check_credentials;

				//$query_check_credentials = "SELECT * FROM contacts WHERE deleted=0 LIMIT $offset, $rowsPerPage";

                /*
                if((empty($_GET['category'])) || ($_GET['category'] == 'Top')) {
                    $query_check_credentials = "SELECT * FROM contacts WHERE deleted = 0 ORDER BY inventoryid DESC LIMIT 25";
                } else {
                    $category = $_GET['category'];
                    $query_check_credentials = "SELECT c.*, cc.*,cd.*,cdes.* FROM contacts c, contacts_cost cc, contacts_dates cd, contacts_description cdes WHERE deleted = 0 AND category='$category' AND c.contactid=cc.contactid AND c.contactid=cd.contactid AND c.contactid=cdes.contactid";
                }
                */
			}

			$result = mysqli_query($dbc, $query_check_credentials) or die(mysqli_error($dbc));

			$num_rows = mysqli_num_rows($result);
			if($num_rows > 0) {

                if(empty($_GET['category']) || $_GET['category'] == 'Top') {
                    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contacts_dashboard FROM field_config_contacts WHERE contacts_dashboard IS NOT NULL"));
                    $value_config = ','.$get_field_config['contacts_dashboard'].',';
                } else {
                    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contacts_dashboard FROM field_config_contacts WHERE tab='$category' AND accordion IS NULL"));
                    $value_config = ','.$get_field_config['contacts_dashboard'].',';
                }

			    echo "<table class='table table-bordered'>";
			    echo "<tr class='hidden-xs hidden-sm'>";
                    include ('contacts_header.php');
                    echo '<th>Function</th>';
                    echo "</tr>";
			} else{
				echo "<h2>No Record Found.</h2>";
			}
			while($row = mysqli_fetch_array( $result ))
			{

				echo '<tr>';

                include ('contacts_data.php');

                echo '<td data-title="Function">';
                if(vuaed_visible_function($dbc, 'contacts') == 1) {
				echo '<a href=\'add_contacts.php?category='.$_GET['category'].'&contactid='.$row['contactid'].'\'>Edit</a> | ';
				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&contactid='.$row['contactid'].'&category='.$row['category'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
				echo '</td>';

				echo "</tr>";
			}

            echo '</table>';
            if(vuaed_visible_function($dbc, 'inventory') == 1) {
			echo '<a href="add_contacts.php?category='.$category.'" class="btn brand-btn mobile-block gap-bottom pull-right">Add Contact</a>';
            }

            echo display_filter_param('contacts.php?category='.$category);

			?>

		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>