<?php
/*
Dashboard
*/
include_once ('../include.php');
include_once('../tcpdf/tcpdf.php');
?>
<script type="text/javascript">
</script>

<form name="form_jobs" enctype="multipart/form-data" method="post" action="field_jobs.php" class="form-inline" role="form">
    <div id="no-more-tables">
        <h1 class="double-pad-bottom">Purchase Order
        <?php
        if(config_visible_function($dbc, 'purchase_order') == 1) {
            echo '<a href="?settings=fields" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        echo '<a href="?tab=create_po" class="btn brand-btn pull-right">Create PO</a>';
        echo '</h1>';

        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT purchase_order_dashboard FROM field_config"));
        $value_config = ','.$get_field_config['purchase_order_dashboard'].',';

        /* Pagination Counting */
        $rowsPerPage = 25;
        $pageNum = 1;

        if(isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;
            
        $query_check_credentials = "SELECT *  FROM purchase_order WHERE deleted=0 LIMIT $offset, $rowsPerPage";
        $query = "SELECT count(*) as numrows FROM purchase_order WHERE deleted=0";
        
        $result = mysqli_query($dbc, $query_check_credentials);
        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            
        // Added Pagination //
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        // Pagination Finish //
                
        echo "<table class='table table-bordered'>";
        echo "<tr class='hidden-xs hidden-sm'>";
            if (strpos($value_config, ','."PO#".',') !== FALSE) {
                echo "<th>PO#</th>";
            }
            if (strpos($value_config, ','."Business".',') !== FALSE) {
                echo "<th>Business</th>";
            }
            if (strpos($value_config, ','."Project".',') !== FALSE) {
                echo "<th>Project</th>";
            }
            if (strpos($value_config, ','."Ticket".',') !== FALSE) {
                echo "<th>".TICKET_NOUN."</th>";
            }
            if (strpos($value_config, ','."Work Order".',') !== FALSE) {
                echo "<th>Work Order</th>";
            }
            if (strpos($value_config, ','."Issue Date".',') !== FALSE) {
                echo "<th>Issue Date</th>";
            }
            if (strpos($value_config, ','."Vendor".',') !== FALSE) {
                echo "<th>Vendor</th>";
            }
            if (strpos($value_config, ','."PDF".',') !== FALSE) {
                echo "<th>PDF</th>";
            }
            if (strpos($value_config, ','."Created By".',') !== FALSE) {
                echo "<th>Created By</th>";
            }
            if (strpos($value_config, ','."Edited By".',') !== FALSE) {
                echo "<th>Edited By</th>";
            }
            echo "<th>Function</th>";
            echo "</tr>";
        } else {
            echo "<h2>No Record Found.</h2>";
        }
        while($row = mysqli_fetch_array( $result ))
        {
            $jobid = $row['jobid'];
            echo '<tr>';
            if (strpos($value_config, ','."PO#".',') !== FALSE) {
            echo '<td data-title="PO#">' . $row['fieldpoid']. '</td>';
            }
            if (strpos($value_config, ','."Business".',') !== FALSE) {
            echo '<td data-title="Business">' . get_client($dbc, $row['businessid']) . '</td>';
            }
            if (strpos($value_config, ','."Project".',') !== FALSE) {
            echo '<td data-title="Project">' . get_project($dbc, $row['projectid'], 'project_name') . '</td>';
            echo '<td data-title="Project">' . get_client_project($dbc, $row['client_projectid'], 'project_name') . '</td>';
            }
            if (strpos($value_config, ','."Ticket".',') !== FALSE) {
            echo '<td data-title="'.TICKET_NOUN.'">' . get_tickets($dbc, $row['ticketid'], 'service_type').' : '.get_tickets($dbc, $row['ticketid'], 'heading') . '</td>';
            }
            if (strpos($value_config, ','."Work Order".',') !== FALSE) {
            echo '<td data-title="Work Order">' . get_workorder($dbc, $row['workorderid'], 'service_type').' : '.get_tickets($dbc, $row['ticketid'], 'heading') . '</td>';
            }
            if (strpos($value_config, ','."Issue Date".',') !== FALSE) {
            echo '<td data-title="Issue Date">' . $row['issue_date']. '</td>';
            }
            if (strpos($value_config, ','."Vendor".',') !== FALSE) {
            echo '<td data-title="Vendor">' . get_client($dbc, $row['vendorid']) . '</td>';
            }
            if (strpos($value_config, ','."PDF".',') !== FALSE) {
            $name_of_file = 'download/po_'.$row['fieldpoid'].'.pdf';
            echo '<td data-title="PDF"><a href='.$name_of_file.' target="_blank">View</a></td>';
            }
            if (strpos($value_config, ','."Created By".',') !== FALSE) {
            echo '<td data-title="Created By">' . $row['created_by']. '</td>';
            }
            if (strpos($value_config, ','."Edited By".',') !== FALSE) {
            echo '<td data-title="Edited By">' . $row['edited_by']. '</td>';
            }
            //echo '<td data-title="Status">' . $row['status']. '</td>';

            echo '<td data-title="Function"><a href=\'add_purchase_order.php?fieldpoid='.$row['fieldpoid'].'\'>Edit</a>';
            echo '</td>';

            echo "</tr>";
        }

        echo '</table></div>';
        
        // Added Pagination //
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        // Pagination Finish //
        
        
        echo '<a href="add_purchase_order.php" class="btn brand-btn pull-right">Create PO</a>';
        if($po_name_search == '') {
            //echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        }
        ?>

</form>
</div>
</div>
<?php include ('../footer.php'); ?>
