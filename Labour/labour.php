<?php
/*
Customer Listing
*/
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
checkAuthorised('labour');
$current_cat = $_GET['category'];
$search_query = '';
if(!empty($current_cat)) {
    $search_query .= " AND `labour_type` = '".$current_cat."'";
}
?>

<div class="container">
    <div class="row">
        <div class="main-screen">
            <div class="tile-header">
                <?php include('../Labour/tile_header.php'); ?>
            </div>

            <div class="tile-container" style="height: 100%;">

                <div class="collapsible tile-sidebar set-section-height">
                    <?php include('../Labour/tile_sidebar.php'); ?>
                </div>

                <div class="scale-to-fill tile-content set-section-height">
                    <div class="main-screen-white" style="height:calc(100vh - 20em); overflow-y: auto;">
                        <form name="form_sites" method="post" action="" class="form-inline double-gap-top" role="form">

                            <div id="no-more-tables">
                                <div class="preview-block">
                                    <div class="preview-block-header"><h4><?= empty($current_cat) ? 'All Labour' : $current_cat ?></h4></div>
                                </div>
                                <?php
                                //Search
                                if (isset($_POST['search_vendor_submit'])) {
                                    if (isset($_POST['search_vendor'])) {
                                        $vendor = $_POST['search_vendor'];
                                        $search_query .= " AND (`category` LIKE '%$vendor%' OR `heading` LIKE '%$vendor%')";
                                    }
                                }


                                /* Pagination Counting */
                                $rowsPerPage = 25;
                                $pageNum = 1;

                                if(isset($_GET['page'])) {
                                    $pageNum = $_GET['page'];
                                }

                                $offset = ($pageNum - 1) * $rowsPerPage;

                                $query_check_credentials = "SELECT * FROM labour WHERE deleted = 0 $search_query ORDER BY labour_type LIMIT $offset, $rowsPerPage";
                                $query = "SELECT count(*) as numrows FROM labour WHERE deleted = 0 $search_query ORDER BY labour_type";

                                $result = mysqli_query($dbc, $query_check_credentials);

                                $num_rows = mysqli_num_rows($result);
                                if($num_rows > 0) {
                                    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour_dashboard FROM field_config"));
                                    $value_config = ','.$get_field_config['labour_dashboard'].',';

                                    // Added Pagination //
                                    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                                    // Pagination Finish //

                                    echo "<table class='table table-bordered'>";
                                    echo "<tr class='hidden-xs hidden-sm'>";
                                        if (strpos($value_config, ','."Labour Code".',') !== FALSE) {
                                            echo '<th>Labour Code</th>';
                                        }
                                        if (strpos($value_config, ','."Labour Type".',') !== FALSE) {
                                            echo '<th>Labour Type</th>';
                                        }
                                        if (strpos($value_config, ','."Category".',') !== FALSE) {
                                            echo '<th>Category</th>';
                                        }
                                        if (strpos($value_config, ','."Heading".',') !== FALSE) {
                                            echo '<th>Heading</th>';
                                        }
                                        if (strpos($value_config, ','."Name".',') !== FALSE) {
                                            echo '<th>Name</th>';
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
                                        if (strpos($value_config, ','."Daily Rate".',') !== FALSE) {
                                            echo '<th>Daily Rate</th>';
                                        }
                                        if (strpos($value_config, ','."WCB".',') !== FALSE) {
                                            echo '<th>WCB</th>';
                                        }
                                        if (strpos($value_config, ','."Benefits".',') !== FALSE) {
                                            echo '<th>Benefits</th>';
                                        }
                                        if (strpos($value_config, ','."Salary".',') !== FALSE) {
                                            echo '<th>Salary</th>';
                                        }
                                        if (strpos($value_config, ','."Bonus".',') !== FALSE) {
                                            echo '<th>Bonus</th>';
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
                                        if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
                                            echo '<th>Hourly Rate</th>';
                                        }
                                        echo '<th>Function</th>';
                                        echo "</tr>";
                                } else {
                                    echo "<h2>No Record Found.</h2>";
                                }

                                while($row = mysqli_fetch_array( $result ))
                                {
                                    echo "<tr>";
                                    if (strpos($value_config, ','."Labour Code".',') !== FALSE) {
                                        echo '<td data-title="Code">' . $row['labour_code'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Labour Type".',') !== FALSE) {
                           				echo '<td data-title="Type">' . $row['labour_type'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Category".',') !== FALSE) {
                                        echo '<td data-title="Category">' . $row['category'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Heading".',') !== FALSE) {
                                        echo '<td data-title="Heading">' . $row['heading'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Name".',') !== FALSE) {
                                        echo '<td data-title="Name">' . $row['name'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Cost".',') !== FALSE) {
                                        echo '<td data-title="Cost">' . $row['cost'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Description".',') !== FALSE) {
                                        echo '<td data-title="Description">' . html_entity_decode($row['description']) . '</td>';
                                    }
                                    if (strpos($value_config, ','."Quote Description".',') !== FALSE) {
                                        echo '<td data-title="Quote Desc">' . html_entity_decode($row['quote_description']) . '</td>';
                                    }
                                    if (strpos($value_config, ','."Invoice Description".',') !== FALSE) {
                                        echo '<td data-title="Invoice Desc">' . html_entity_decode($row['invoice_description']) . '</td>';
                                    }
                                    if (strpos($value_config, ','."Ticket Description".',') !== FALSE) {
                                        echo '<td data-title="'.TICKET_NOUN.' Desc">' . html_entity_decode($row['ticket_description']) . '</td>';
                                    }
                                    if (strpos($value_config, ','."Daily Rate".',') !== FALSE) {
                                        echo '<td data-title="Daily Rate">' . $row['daily_rate'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."WCB".',') !== FALSE) {
                                        echo '<td data-title="WCB">' . $row['wcb'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Benefits".',') !== FALSE) {
                                        echo '<td data-title="Benefits">' . $row['benefits'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Salary".',') !== FALSE) {
                                        echo '<td data-title="Salary">' . $row['salary'] . '</td>';
                                    }
                                    if (strpos($value_config, ','."Bonus".',') !== FALSE) {
                                        echo '<td data-title="Bonus">' . $row['bonus'] . '</td>';
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
                                    if (strpos($value_config, ','."Hourly Rate".',') !== FALSE) {
                                        echo '<td data-title="Hr. Rate">' . $row['hourly_rate'] . '</td>';
                                    }
                                    echo '<td data-title="Function">';
                                    if(vuaed_visible_function($dbc, 'labour') == 1) {
                                    echo '<a href=\'add_labour.php?labourid='.$row['labourid'].'\'>Edit</a> | ';
                    				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&labourid='.$row['labourid'].'\' onclick="return confirm(\'Are you sure?\')">Archive</a>';
                                    }
                                    echo '</td>';

                                    echo "</tr>";
                                }

                                echo '</table></div>';
                                // Added Pagination //
                                    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                                    // Pagination Finish //
                                if(vuaed_visible_function($dbc, 'labour') == 1) {
                                echo '<a href="add_labour.php" class="btn brand-btn mobile-block pull-right">Add Labour</a>';
                                }

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
