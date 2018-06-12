<?php
/*
Customer Listing
*/
if(!isset($_GET['mobile_view'])) {
    include_once ('../include.php');
} else {
    include_once ('../database_connection.php');
    include_once ('../global.php');
    include_once ('../function.php');
    include_once ('../output_functions.php');
    include_once ('../email.php');
    include_once ('../user_font_settings.php');
}
error_reporting(0);
if(isset($_POST['contactid'])) {
    if(!empty($_POST['subtab'])) {
        $action_page = 'staff_edit.php?contactid='.$_GET['contactid'];
        if($_POST['subtab'] == 'software_access') {
            $action_page = 'edit_software_access.php?contactid='.$_GET['contactid'];
        } else if($_POST['subtab'] == 'certificates') {
            $action_page = 'certificate.php?contactid='.$_GET['contactid'];
        } else if($_POST['subtab'] == 'history') {
            $action_page = 'staff_history.php?contactid='.$_GET['contactid'];
        } else if($_POST['subtab'] == 'reminders') {
            $action_page = 'staff_reminder.php?contactid='.$_GET['contactid'];
        } else if($_POST['subtab'] == 'schedule') {
            $action_page = 'staff_schedule.php?contactid='.$_GET['contactid'];
        }?>
        <form action="<?php echo $action_page; ?>" method="post" id="change_page">
            <input type="hidden" name="subtab" value="<?php echo $_POST['subtab']; ?>">
        </form>
        <script type="text/javascript"> document.getElementById('change_page').submit(); </script>
    <?php }
}
?>
</head>
<script type="text/javascript" src="staff.js"></script>
<body>
<?php if(!isset($_GET['mobile_view'])) { include_once ('../navigation.php'); }
checkAuthorised('staff');
$contactid = isset($_GET['contactid']) ? $_GET['contactid'] : (isset($_GET['id']) ? $_GET['id'] : $_SESSION['contactid']);
$field_tabs = ','.get_config($dbc, 'staff_field_subtabs').',';
$subtab = 'certificate';
// if (!empty($_POST['subtab'])) {
//     $subtab = $_POST['subtab'];
// }
$from_url = 'staff.php?tab=active';
if (!empty($_GET['from'])) {
    $from_url = $_GET['from'];
}
if(!empty($_GET['from_url'])) {
    $from_url = $_GET['from_url'];
}
if(!empty($_POST['from_url'])) {
    $from_url = $_POST['from_url'];
}
?>

<div id="staff_div" class="container">
    <?php if(!isset($_GET['mobile_view'])) { include('mobile_view.php'); } ?>
    <div class="row hide-titles-mob">
        <!-- <div id="no-more-tables" class="main-screen contacts-list"> -->
        <div class="main-screen">
            <!-- Tile Header -->
            <div class="tile-header standard-header">
                <div class="col-sm-12">
                    <h1>
                        <span class="pull-left" style="margin-top: -5px;"><a href="<?= $from_url ?>" class="default-color">Staff</a>: <?= $contactid > 0 ? get_contact($dbc, $contactid) : 'Add New' ?></span>
                        <?php if ( config_visible_function ( $dbc, 'staff' ) == 1 ) { ?>
                            <div class="pull-right gap-left top-settings">
                                <a href="staff.php?settings=dashboard" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
                                <span class="popover-examples list-inline pull-right" style="margin:5px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                            </div><?php
                        } ?>
                        <?php if(vuaed_visible_function($dbc, 'staff') > 0) { ?>
                            <a href="staff_edit.php" class="btn brand-btn pull-right">New Staff</a>
                        <?php } ?>
                        <span class="clearfix"></span>
                        <div class="alert alert-danger text-sm text-center" style="display:none;"></div>
                        <div class="alert alert-success text-sm text-center" style="display:none;"></div>
                    </h1>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

            <form id="form1" name="form1" method="post" action="certificate.php?contactid=<?= $_GET['contactid'] ?>" enctype="multipart/form-data" class="form-horizontal" role="form">

                <!-- Sidebar -->
                <div class="standard-collapsible tile-sidebar set-section-height">
                    <?php include('tile_sidebar.php'); ?>
                </div><!-- .tile-sidebar -->

                <!-- Main Screen -->
                <div class="has-main-screen scale-to-fill tile-content set-section-height" style="padding: 0; overflow-y: auto;">
                    <div class="main-screen-details main-screen override-main-screen <?= $subtab != 'id_card' ? 'standard-body' : '' ?>" style="height: inherit;">
                        <div class='standard-body-title'>
                            <h3><?= $sidebar_fields[$subtab][1]; ?></h3>
                        </div>
                        <div class='standard-body-dashboard-content pad-top pad-left pad-right'>
                            <?php
                            if(vuaed_visible_function($dbc, 'certificate') == 1) {
                                echo '<a href="../Certificate/add_certificate.php?staffid='.$contactid.'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" class="btn brand-btn mobile-block pull-right">Add Certificate</a>';
                                echo '<div class="clearfix"></div>';
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
                                            echo '<li><a href="../Certificate/download/'.$row1['document_link'].'" target="_blank">'.$row1['document_link'].'</a></li>';
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
                                echo '<a href=\'../Certificate/add_certificate.php?certificateid='.$certificateid.'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'\'>Edit</a> | ';
                				echo '<a href=\''.WEBSITE_URL.'/delete_restore.php?action=delete&certificateid='.$certificateid.'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                                }
                                echo '</td>';

                                echo "</tr>";
                            }

                            echo '</table></div>';
                            //Added Pagination
                            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                            //Finish Pagination
                            if(vuaed_visible_function($dbc, 'certificate') == 1) {
                            echo '<a href="../Certificate/add_certificate.php?staffid='.$contactid.'&from_url='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'" class="btn brand-btn mobile-block pull-right">Add Certificate</a>';
                            }

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
