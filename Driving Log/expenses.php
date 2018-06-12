<?php
/*
Customer Listing
*/
include ('../include.php');
checkAuthorised('driving_log');

?>

</head>
<body>
<?php include_once ('navigation.php');

?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <h1 class="double-pad-bottom">Expense Dashboard</h1>
        <form name="form_sites" method="post" action="" class="form-inline" role="form">
            <div class="single-pad-bottom">
                <label for="search_email">Search By Any:</label>
                <?php if(isset($_POST['search_email_submit'])) { ?>
                    <input type="text" name="search_email" value="<?php echo $_POST['search_email']?>" class="form-control">
                <?php } else { ?>
                    <input type="text" name="search_email" class="form-control">
                <?php } ?>

                <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
                <button type="submit" name="display_all_email" value="Display All" class="btn brand-btn mobile-block">Display All</button>
				<?php
				echo '<a href="add_expense.php" class="btn brand-btn mobile-block pull-right">Add Expense</a>';
				 ?>
            </div>
            <div id="no-more-tables">

            <?php
            // Display Pager

            $email = '';
            if (isset($_POST['search_email_submit'])) {
                $email = $_POST['search_email'];
            }
            if (isset($_POST['display_all_email'])) {
                $email = '';
            }

            $rowsPerPage = ITEMS_PER_PAGE;
            $pageNum = 1;

            if(isset($_GET['page'])) {
            	$pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            if($email != '') {
                $query_check_credentials = "SELECT ex.* FROM driving_log_expense ex, staff s WHERE ex.driverid = s.staffid AND (s.first_name LIKE '%" . $email . "%' OR s.last_name LIKE '%" . $email . "%' OR ex.fill_date LIKE '%" . $email . "%' OR ex.per_dium LIKE '%" . $email . "%' OR ex.final_total LIKE '%" . $email . "%')";
            } else {
                $query_check_credentials = "SELECT * FROM driving_log_expense ORDER BY expenseid DESC LIMIT $offset, $rowsPerPage";
            }

            // how many rows we have in database

            $query   = "SELECT COUNT(expenseid) AS numrows FROM driving_log_expense";

            if($email == '') {
                echo '<h1 class="single-pad-bottom">'.display_pagination($dbc, $query, $pageNum, $rowsPerPage).'</h1>';
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                <th>Expense#</th>
                <th>Driver Name</th>
                <th>Date</th>
                <th>Total Amount</th>
                <th>Document Uploaded</th>
                <th>Function</th>";
                echo "</tr>";
            } else{
            	echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
                $driverid = $row['driverid'];
                $get_employee = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM staff WHERE staffid = '$driverid'"));

            	echo "<tr>";
                echo '<td data-title="Email">' . $row['expenseid'] . '</td>';
            	echo '<td data-title="Name">' . decryptIt($get_employee['first_name']). ' '.decryptIt($get_employee['last_name']) . '</td>';
            	echo '<td data-title="Email">' . $row['fill_date'] . '</td>';
				echo '<td data-title="Customer">' . $row['final_total'] . '</td>';

                if($row['upload_document'] != '') {
                    echo '<td data-title="Function"><a target="_blank" href="download/expense/'.$row['upload_document'].'" >View</a>';
                } else {
                    echo '<td data-title="Email">-</td>';
                }
                $file_name = 'expense_'.$row['expenseid'].'.doc';
                echo '<td data-title="Function"><a href="download/expense/doc/'.$file_name.'" >Download</a> ';

            	echo "</tr>";
            }

            echo '</table></div>';
			echo '<a href="add_expense.php" class="btn brand-btn pull-right">Add Expense</a>';

            if($email == '') {
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            }
                //echo '<a href="driving_log_tiles.php" class="btn brand-btn">Back</a>';
				echo '<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>';

            ?>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>