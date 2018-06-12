<?php
/*
Inventory Listing
*/
include ('../include.php');

?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('material');
$material_navigation_position = get_config($dbc, 'material_navigation_position');
?>
<div class="container">
	<div class="row">
        <div class="main-screen">
            <div class="tile-header">
                <div class="col-xs-12 col-sm-4">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="material.php" class="default-color">Materials</a></span>
                        <span class="clearfix"></span>
                    </h1>
                </div>
                <div class="col-xs-12 col-sm-8 text-right settings-block">
                    <?php
                    if(config_visible_function($dbc, 'material') == 1) { ?>
                        <div class="pull-right gap-left top-settings">
                            <a href="field_config.php" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
                            <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        </div><?php
                    }
                    if(vuaed_visible_function($dbc, 'material') == 1) {
                        echo '<div class="row gap-left gap-right">';
                            echo '<a href="add_material.php" class="btn brand-btn mobile-block gap-bottom pull-right">New Material</a>';
                            echo '<span class="popover-examples list-inline pull-right" style="margin:7px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here to add a new Material."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                        echo '</div>';
                    } ?>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="tile-container" style="height: 100%;">
                <?php if($material_navigation_position == 'top') {
                    include('../Material/tile_nav_top.php');
                } ?>

                <!-- Notice -->
                <div class="notice gap-bottom gap-top popover-examples">
                    <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                    <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                    This tile monitors all of your materials.</div>
                    <div class="clearfix"></div>
                </div>

                <?php if($material_navigation_position != 'top') { ?>
                    <div class="collapsible tile-sidebar set-section-height">
                        <?php include('../Material/tile_sidebar.php'); ?>
                    </div>
                <?php } ?>

                <div class="scale-to-fill tile-content set-section-height">
                    <div class="main-screen-white" style="height:calc(100vh - 20em); overflow-y: auto;">
                		<form name="form_sites" method="post" action="" class="form-horizontal gap-top" role="form">

                        <?php if($material_navigation_position == 'top') { ?>
                            <div class="pull-left tab gap-bottom"><a href="material.php?filter=Top"><button type="button" class="btn brand-btn <?= $_GET['filter'] == 'Top' ? 'active_tab' : '' ?>">Last 25 Added</button></a></div>
                            <?php $cat_list = mysqli_query($dbc,"SELECT distinct(category) FROM material where deleted = 0");
                            if(mysqli_num_rows($cat_list) > 0) { ?>
                                <?php while($cat_tab = mysqli_fetch_array($cat_list)) {
                                    if(!empty($cat_tab['category'])) { ?>
                                        <div class="pull-left tab gap-bottom"><a href="material.php?category=<?= $cat_tab['category'] ?>"><button type="button" class="btn brand-btn <?= $cat_tab['category'] == $_GET['category'] ? 'active_tab' : '' ?>"><?= $cat_tab['category'] ?></button></a></div>
                                    <?php }
                                } ?>
                            <?php }
                        } ?>
                        <div class="clearfix"></div>

                        <?php
                            echo display_filter('material.php');
                        ?>

                        <div class="form-group">
                            <!-- <label for="site_name" class="col-sm-1 control-label">Category:</label>
                            <div class="col-sm-8" style="width:25%">
                                <select name="search_category" class="chosen-select-deselect form-control"  width="380">
                                    <option value="" selected>Select a Category</option>
                                        <?php
                                            $query = mysqli_query($dbc,"SELECT distinct(category) FROM material where deleted = 0");
                                            while($row = mysqli_fetch_array($query)) {
                                                echo "<option value='". $row['category']."'>".$row['category'].'</option>';
                                        } ?>
                                </select>
                            </div>
                            &nbsp;&nbsp;&nbsp; -->
                            <div class="col-sm-8 col-sm-offset-4">
                                Search By Any:
                                <?php if(isset($_POST['search_material_submit'])) { ?>
                    				<input type="text" name="search_material" value="<?php echo $_POST['search_material']?>" class="form-control"  style="width: 20%; display: inline;">
                    			<?php } else { ?>
                    				<input type="text" name="search_material" class="form-control" style="width: 20%; display: inline;">
                    			<?php } ?>
                        		<span class="popover-examples list-inline" style="margin:0 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here once you have filled out the desired above fields."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                                <button type="submit" name="search_material_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
                        		<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Refreshes the page to display all Materials."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                                <button type="submit" name="display_all_material" value="Display All" class="btn brand-btn mobile-block">Display All</button>
                            </div>
                        </div>

                		<div id="no-more-tables">
                			<?php
                			// Display Pager
                			$material = '';
                			if (isset($_POST['search_material_submit'])) {
                				$material = $_POST['search_material'];
                                if (isset($_POST['search_material'])) {
                                    $material = $_POST['search_material'];
                                }
                                // if ($_POST['search_category'] != '') {
                                //     $material = $_POST['search_category'];
                                // }
                			}
                            if (isset($_GET['category'])) {
                                $category_query = " AND `category` = '".$_GET['category']."'";
                            }
                			if (isset($_POST['display_all_material'])) {
                				$material = '';
                			}

                			/* Pagination Counting */
                            $rowsPerPage = 25;
                            $pageNum = 1;

                            if(isset($_GET['page'])) {
                                $pageNum = $_GET['page'];
                            }

                            $offset = ($pageNum - 1) * $rowsPerPage;

                			if($material != '') {
                				$query_check_credentials = "SELECT * FROM material WHERE deleted=0 AND (name LIKE '%" . $material . "%' OR code LIKE '%" . $material . "%' OR category = '$material' OR sub_category LIKE '%" . $material . "%' OR description LIKE '%" . $material . "%' OR width LIKE '%" . $material . "%' OR length LIKE '%" . $material . "%' OR units LIKE '%" . $material . "%' $category_query) LIMIT $offset, $rowsPerPage";
                                $pageQuery = "SELECT count(*) as numrows FROM material WHERE deleted=0 AND (name LIKE '%" . $material . "%' OR code LIKE '%" . $material . "%' OR category = '$material' OR sub_category LIKE '%" . $material . "%' OR description LIKE '%" . $material . "%' OR width LIKE '%" . $material . "%' OR length LIKE '%" . $material . "%' OR units LIKE '%" . $material . "%' $category_query)";
                			} else {
                                if(isset($_GET['filter'])) { $url_search = $_GET['filter']; } else { $url_search = ''; }
                                if($url_search == 'Top') {
                                    $query_check_credentials = "SELECT * FROM material WHERE deleted = 0 $category_query ORDER BY materialid DESC LIMIT 25";
                                } else if($url_search == 'All') {
                                    $query_check_credentials = "SELECT * FROM material WHERE deleted = 0 $category_query ORDER BY code LIMIT $offset, $rowsPerPage";
                                    $pageQuery = "SELECT count(*) as numrows FROM material WHERE deleted = 0 ORDER BY code";
                                } else {
                                    $query_check_credentials = "SELECT * FROM material WHERE deleted = 0 AND code LIKE '" . $url_search . "%' $category_query ORDER BY code LIMIT $offset, $rowsPerPage";
                                    $pageQuery = "SELECT count(*) as numrows FROM material WHERE deleted = 0 AND code LIKE '" . $url_search . "%' $category_query ORDER BY code";
                                }

                				//$query_check_credentials = "SELECT * FROM material WHERE deleted=0 LIMIT $offset, $rowsPerPage";
                			}

                			$result = mysqli_query($dbc, $query_check_credentials);

                			$num_rows = mysqli_num_rows($result);
                			if($num_rows > 0) {
                                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material_dashboard FROM field_config"));
                                $value_config = ','.$get_field_config['material_dashboard'].',';

                                // Added Pagination //
                                if(isset($pageQuery)) {
                                    echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                                }
                                // Pagination Finish //

                			    echo "<table class='table table-bordered'>";
                			    echo "<tr class='hidden-xs hidden-sm'>";
                                    if (strpos($value_config, ','."Code".',') !== FALSE) {
                                        echo '<th>Code</th>';
                                    }
                                    if (strpos($value_config, ','."Category".',') !== FALSE) {
                                        echo '<th>Category</th>';
                                    }
                                    if (strpos($value_config, ','."Sub-Category".',') !== FALSE) {
                                        echo '<th>Sub-Category</th>';
                                    }

                                    if (strpos($value_config, ','."Material Name".',') !== FALSE) {
                                        echo '<th>Material Name</th>';
                                    }
                                    if (strpos($value_config, ','."Description".',') !== FALSE) {
                                        echo '<th>Description</th>';
                                    }
                                    if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
                                        echo '<th>Quote Description</th>';
                                    }
                					if (strpos($value_config, ','."Vendor".',') !== FALSE) {
                                        echo '<th>Vendor</th>';
                                    }
                                    if (strpos($value_config, ','."Width".',') !== FALSE) {
                                        echo '<th>Width</th>';
                                    }

                                    if (strpos($value_config, ','."Length".',') !== FALSE) {
                                        echo '<th>Length</th>';
                                    }
                                    if (strpos($value_config, ','."Units".',') !== FALSE) {
                                        echo '<th>Units</th>';
                                    }
                                    if (strpos($value_config, ','."Unit Weight".',') !== FALSE) {
                                        echo '<th>Unit Weight</th>';
                                    }
                                    if (strpos($value_config, ','."Weight Per Feet".',') !== FALSE) {
                                        echo '<th>Weight Per Foot</th>';
                                    }
                                    if (strpos($value_config, ','."Quantity".',') !== FALSE) {
                                        echo '<th>Quantity</th>';
                                    }
                                    if (strpos($value_config, ','."Price".',') !== FALSE) {
                                        echo '<th>Price</th>';
                                    }
                                    echo '<th>Function</th>';
                                    echo "</tr>";
                			} else{
                				echo "<h2>No Record Found.</h2>";
                			}
                			while($row = mysqli_fetch_array( $result ))
                			{
                				echo '<tr>';
                                if (strpos($value_config, ','."Code".',') !== FALSE) {
                       				echo '<td data-title="Code">' . $row['code'] . '</td>';
                                }
                                if (strpos($value_config, ','."Category".',') !== FALSE) {
                                    echo '<td data-title="Category">' . $row['category'] . '</td>';
                                }
                                if (strpos($value_config, ','."Sub-Category".',') !== FALSE) {
                                    echo '<td data-title="Sub Category">' . $row['sub_category'] . '</td>';
                                }

                                if (strpos($value_config, ','."Material Name".',') !== FALSE) {
                                    echo '<td data-title="Name">' . $row['name'] . '</td>';
                                }
                                if (strpos($value_config, ','."Description".',') !== FALSE) {
                                    echo '<td data-title="Description">' . html_entity_decode($row['description']) . '</td>';
                                }
                                if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
                                    echo '<td data-title="Quote Desc">' . html_entity_decode($row['quote_description']) . '</td>';
                                }
                                if (strpos($value_config, ','."Vendor".',') !== FALSE) {
                                    $vendorid = $row['vendorid'];
                                    $get_vendor = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT  name FROM contacts WHERE contactid='$vendorid'"));
                                    echo '<td data-title="Vendor">' . decryptIt($get_vendor['name']) . '</td>';
                                }

                                if (strpos($value_config, ','."Width".',') !== FALSE) {
                                    echo '<td data-title="Width">' . $row['width'] . '</td>';
                                }
                                if (strpos($value_config, ','."Length".',') !== FALSE) {
                                    echo '<td data-title="Length">' . $row['length'] . '</td>';
                                }
                                if (strpos($value_config, ','."Units".',') !== FALSE) {
                                    echo '<td data-title="Units">' . $row['units'] . '</td>';
                                }
                                if (strpos($value_config, ','."Unit Weight".',') !== FALSE) {
                                    echo '<td data-title="Unit Weight">' . $row['unit_weight'] . '</td>';
                                }
                                if (strpos($value_config, ','."Weight Per Feet".',') !== FALSE) {
                                    echo '<td data-title="Weight / Ft.">' . $row['weight_per_feet'] . '</td>';
                                }
                                if (strpos($value_config, ','."Quantity".',') !== FALSE) {
                                    echo '<td data-title="Quantity">' . $row['quantity'] . '</td>';
                                }
                                if (strpos($value_config, ','."Price".',') !== FALSE) {
                                    echo '<td data-title="Price">$' . $row['price'] . '</td>';
                                }
                                echo '<td data-title="Function">';
                                if(vuaed_visible_function($dbc, 'material') == 1) {
                				echo '<a href=\'add_material.php?materialid='.$row['materialid'].'\'>Edit</a> | ';
                				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&materialid='.$row['materialid'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
                                }
                				echo '</td>';

                				echo "</tr>";
                			}

                            echo '</table>';

                            // Added Pagination //
                            if(isset($pageQuery)) {
                               echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                            }
                            // Pagination Finish //

                            echo display_filter('material.php');

                			?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>

<?php include ('../footer.php'); ?>
