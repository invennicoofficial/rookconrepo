<?php
/*
New PAtient Hidtory list
*/
include ('../include.php');
?>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">

        <h1 class="double-pad-bottom">Forms</h1>

        <button type="button" class="btn brand-btn mobile-block active_tab" >New Patient History</button>

        <div class="table-responsive">

        <form name="form_patients" method="post" action="new_patient_history.php" class="form-inline" role="form">

            <div class="double-pad-bottom">
                <label for="search_site">Search:</label>
                <?php if(isset($_POST['search_patient_submit'])) { ?>
                    <input type="text" name="search_patient" value="<?php echo $_POST['search_patient']?>" class="form-control">
                <?php } else { ?>
                    <input type="text" name="search_patient" class="form-control">
                <?php } ?>
                <button type="submit" name="search_patient_submit" value="Search" class="btn brand-btn">Search</button>
                <button type="submit" name="display_all_patient" value="Display All" class="btn brand-btn">Display All</button>
            </div>
            <?php
			echo '<a href="new_patient_history_form.php" class="btn brand-btn pull-right">Add History</a>';
            ?>
            <?php
            // Display Pager

            $rowsPerPage = ITEMS_PER_PAGE;
            $pageNum = 1;

            if(isset($_GET['page'])) {
            	$pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

            $name = '';
            if (isset($_POST['search_patient_submit'])) {
                $name = $_POST['search_patient'];
            }
            if (isset($_POST['display_all_patient'])) {
                $name = '';
            }

            if(!isset($_POST['search_patient_submit'])) {
                $query_check_credentials = "SELECT * FROM new_patient_history_form ORDER BY formid DESC LIMIT $offset, $rowsPerPage";
            } else {
                $query_check_credentials = "SELECT * FROM new_patient_history_form WHERE name LIKE '%" . $name . "%' OR date LIKE '%" . $name . "%' OR long_injury LIKE '%" . $name . "%' OR score_pain LIKE '%" . $name . "%' OR pain_desc LIKE '%" . $name . "%'";
            }

            // how many rows we have in database
            $query = "SELECT COUNT(formid) AS numrows FROM new_patient_history_form";

            if(!isset($_POST['search_patient_submit'])) {
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            }

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                echo "<table border='2' cellpadding='10' class='table'>";
                echo "<tr>
                <th>Name</th>
				<th>Date</th>
				<th>Injury Time</th>
                <th>Pain Score</th>
                <th>Pain</th>
				<th>View</th>
                </tr>";
            } else {
            	echo "<h2>No Record Found.</h2>";
            }

            while($row = mysqli_fetch_array( $result ))
            {
            	echo "<tr>";
                echo '<td>'.get_contact($dbc, $row['patientid']). '</td>';
				//<a href="#"  onclick=" window.open(\''.WEBSITE_URL.'/Contact/add_contact.php?contactid='.$row['patientid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">'.get_contact($dbc, $row['patientid']). '</a>
				echo '<td>' . $row['date'] . '</td>';
				echo '<td>' . $row['long_injury'] . '</td>';
            	echo '<td>' . $row['score_pain'] . '</td>';
            	echo '<td>' . $row['pain_desc'] . '</td>';

                $name_of_file = 'Download/patient_history_'.$row['formid'].'.pdf';
                $md5 = md5_file($name_of_file);
                if($md5 == $row['history_form_md5']) {
                    echo '<td><a href="'.$name_of_file.'" target="_blank"> <img src="'.WEBSITE_URL.'/img/pdf.png" title="PDF"> </a></td>';
                } else {
                    echo '<td>(Error : File Change)</td>';
                }
				echo "</tr>";
            }

            echo '</table></div>';
			echo '<a href="new_patient_history_form.php" class="btn brand-btn pull-right">Add History</a>';

            if(!isset($_POST['search_patient_submit'])) {
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            }

            ?>
        </form>
            <a href="<?php echo WEBSITE_URL;?>/home.php" class="btn brand-btn">Back</a>

        </div>

    </div>

<?php include ('../footer.php'); ?>