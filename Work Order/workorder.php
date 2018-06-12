<?php
/*
Dashboard
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

if(!empty($_GET['quoteid'])) {
    $quoteid = $_GET['quoteid'];
    $status = $_GET['status'];
    $query_update_report = "UPDATE `quote` SET `status` = '$status' WHERE `quoteid` = '$quoteid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);
    echo '<script type="text/javascript"> window.location.replace("quotes.php"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#search_user").change(function() {
        var search_user = $("#search_user").val();
        window.location = 'workorder.php?contactid='+search_user;
	});
});
</script>
<style>

</style>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row hide_on_iframe">
        <?php
        $url = 'workorder.php';
        if(!empty($_GET['contactid'])) {
            //$url = 'workorder.php?contactid='.$_GET['contactid'];
        }
        ?>
	    <form id="form1" name="form1" method="post"	action="<?php echo $url; ?>" enctype="multipart/form-data" class="form-horizontal" role="form">

        <h1>Work Orders
        <?php
            if(empty($_GET['contactid'])) {
                $contactid = $_SESSION['contactid'];
            } else {
                $contactid = $_GET['contactid'];
            }

            if(empty($_GET['pid'])) {
                if(config_visible_function($dbc, 'work_order') == 1) {
                    echo '<a href="field_config_workorder.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
                }
            }

            if(isset($_POST['search_user_submit'])) {
                $search_user = $_POST['search_user'];
            } else {
                $search_user = $contactid;
            }
        ?>
        </h1>
        <?php
        if(empty($_GET['pid'])) { ?>
        <div class="form-group">
		  <label for="site_name" class="col-sm-4 control-label">Search By Staff:</label>
		  <div class="col-sm-8">
              <select data-placeholder="Pick a User" name="search_user" id="search_user" class="chosen-select-deselect1 form-control" style="width: 20%;float: left;margin-right: 10px;" width="380">
              <option value=""></option>
			  <?php
				$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Staff' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
				foreach($query as $id) {
					$selected = '';
					$selected = $id == $search_user ? 'selected = "selected"' : '';
					echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
				}
			  ?>
            </select>
            <!-- <button type="submit" name="search_user_submit" value="Search" class="btn brand-btn mobile-block">Search</button> -->
		  </div>
		</div>
        <?php } ?>

        <?php
        if(empty($_GET['pid'])) {
            if(vuaed_visible_function($dbc, 'work_order') == 1) {

                echo '<div class="mobile-100-container"><a href="add_workorder.php" class="btn brand-btn mobile-block pull-right mobile-100-pull-right">Add Work Order</a></div>';
            }
        }
        ?>
        <?php
        //if(!empty($_GET['contactid'])) {
            //$projectid = $_GET['projectid'];
            //$query_check_credentials = "SELECT r.*, c.name, s.first_name, s.last_name FROM workorder r, contacts c , staff s WHERE r.clientid = c.contactid AND r.contactid = s.contactid AND r.projectid = '$projectid' ORDER BY workorderid DESC";
        //} else {


        /* Pagination Counting */
        $rowsPerPage = 25;
        $pageNum = 1;

        if(isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;

        if(!empty($_GET['pid'])) {
            $projectid = $_GET['pid'];
            $query_check_credentials = "SELECT * FROM workorder WHERE projectid = '$projectid' ORDER BY workorderid DESC LIMIT $offset, $rowsPerPage";
            $query = "SELECT count(*) as numrows FROM workorder WHERE projectid = '$projectid' ORDER BY workorderid DESC";
        } else {
            $query_check_credentials = "SELECT * FROM workorder WHERE contactid LIKE '%," . $search_user . ",%' AND status != 'Done' ORDER BY workorderid DESC LIMIT $offset, $rowsPerPage";
            $query = "SELECT count(*) as numrows FROM workorder WHERE contactid LIKE '%," . $search_user . ",%' AND status != 'Done' ORDER BY workorderid DESC";
        }

        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT workorder_dashboard FROM field_config"));
        $value_config = ','.$get_field_config['workorder_dashboard'].',';

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {

            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

            echo '<table class="table table-bordered">';
            echo '<tr class="hidden-xs hidden-sm">';
            if (strpos($value_config, ','."Work Order#".',') !== FALSE) {
                echo '<th>Work Order#</th>';
            }
            if (strpos($value_config, ','."Job#".',') !== FALSE) {
                echo '<th>Job#</th>';
            }
            if (strpos($value_config, ','."Heading".',') !== FALSE) {
                echo '<th>Heading</th>';
            }
            if (strpos($value_config, ','."TO DO Date".',') !== FALSE) {
                echo '<th>TO DO Date</th>';
            }
            if (strpos($value_config, ','."Deliverable Date".',') !== FALSE) {
                echo '<th>Deliverable Date</th>';
            }
            if (strpos($value_config, ','."Assigned To".',') !== FALSE) {
                echo '<th>Assigned To</th>';
            }
            if (strpos($value_config, ','."Status".',') !== FALSE) {
                echo '<th>Status</th>';
            }
                echo '<th>Function</th>';
                echo '</tr>';
        } else {
            echo "<h2>No Record Found.</h2>";
        }
        while($row = mysqli_fetch_array( $result )) {
            echo '<tr>';
            $clientid = $row['clientid'];
            $contactid = $row['contactid'];
            $workorderid = $row['workorderid'];

            $businessid = $row['businessid'];
            if($businessid ==  '' || $businessid ==  0) {
                $businessid = get_contact($dbc, $clientid, 'businessid');
            }

            if (strpos($value_config, ','."Work Order#".',') !== FALSE) {
                if((vuaed_visible_function($dbc, 'work_order') == 1) && (empty($_GET['contactid']))) {
                    echo '<td data-title="Work Order#"><a href=\'add_workorder.php?workorderid='.$row['workorderid'].'\'>' . $workorderid . '</a></td>';
                } else {
                    echo '<td data-title="Unit Number">' . $workorderid . '</td>';
                }
            }
            if (strpos($value_config, ','."Job#".',') !== FALSE) {
                //echo '<td data-title="Unit Number">' . $row['projectid'] . '</td>';
                if(!empty($_GET['pid'])) {
                    echo '<td data-title="Unit Number">' . $row['projectid'] . '</td>';
                } else {
                    //echo '<td data-title="Job#"><a href="#"  onclick="wwindow.open(\''.WEBSITE_URL.'/Work Order/workorder.php?pid='.$row['projectid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">' . $row['projectid'] . '</a></td>';
					echo '<td data-title="Job#"><a href="workorder.php?pid='.$row['projectid'].'&from='.urlencode(WEBSITE_URL.$_SERVER['REQUEST_URI']).'">' . $row['projectid'] . '</a></td>';

                }
            }
            //echo '<td data-title="Client">' . get_contact($dbc, $businessid, 'name').'<br>'.get_contact($dbc, $clientid, 'first_name').' '.get_contact($dbc, $clientid, 'last_name') . '</td>';
            //echo '<td data-title="Unit Number">' . $row['service'].'<br>'.$row['service_type'] . '</td>';

            if (strpos($value_config, ','."Heading".',') !== FALSE) {
                echo '<td data-title="Heading">' . $row['heading'] . '</td>';
            }

            if (strpos($value_config, ','."TO DO Date".',') !== FALSE) {
                echo '<td data-title="TO DO Date">' . $row['to_do_date'] . '</td>';
            }

            //echo '<td data-title="Unit Number">' . $row['internal_qa_date'] . '</td>';
            if (strpos($value_config, ','."Deliverable Date".',') !== FALSE) {
                echo '<td data-title="Deliverable Date">' . $row['deliverable_date'] . '</td>';
            }

            $to = explode(',', $row['contactid']);
            $staff = '';
            foreach($to as $category => $value)  {
                if($value != '') {
                    $staff .= get_staff($dbc, $value).'<br>';
                }
            }

            if (strpos($value_config, ','."Assigned To".',') !== FALSE) {
                 echo '<td data-title="Assigned To">' . $staff . '</td>';
            }
            if (strpos($value_config, ','."Status".',') !== FALSE) {
                echo '<td data-title="Status">' . $row['status'] . '</td>';
            }

            if(empty($_GET['pid'])) {
                echo '<td data-title="Function">';
                if(vuaed_visible_function($dbc, 'work_order') == 1) {
                    //if(empty($_GET['contactid'])) {
                        echo '<a href=\'add_workorder.php?workorderid='.$row['workorderid'].'\'>Edit</a> | ';
                    //} else {
                        echo '<a href=\'add_workorder.php?workorderid='.$row['workorderid'].'&contactid='.$_SESSION['contactid'].'\'>Go</a>';
                    //}
                    //echo '<a href=\'delete_restore.php?action=delete&workorderid='.$row['workorderid'].'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
                echo '</td>';
            } else {
                echo '<td data-title="Function"><a href=\'add_workorder.php?workorderid='.$row['workorderid'].'\'>View</a></td>';
            }

            echo "</tr>";
        }

        echo '</table>';

        // Added Pagination //
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        // Pagination Finish //

        ?>
        </form>
	</div>
</div>

<?php include ('../footer.php'); ?>
