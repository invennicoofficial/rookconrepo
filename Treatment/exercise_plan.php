<?php
/*
New PAtient Hidtory list
*/
include ('../include.php');
checkAuthorised('treatment_charts');
?>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">

        <h1 class="double-pad-bottom">Exercise Plan Dashboard
        <?php
            echo '<a href="config_treatment.php" class=" mobile-block pull-right"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?>
        </h1>

        <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT treatment FROM config_treatment"));
        $value_config = ','.$get_field_config['treatment'].',';
        ?>

		<div class="mobile-100-container">
			<span class="nav-subtab">
				<?php //if (strpos($value_config, ','."Assessment".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the treatment forms are stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='patientform.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Forms</button></a>
				<?php //} ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Assessment".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the assessment information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='assessment.php'><button type="button" class="btn brand-btn mobile-block mobile-100">My Assessment</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Treatment".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the treatment information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='treatment.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Treatment</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Exercise Plan".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the exercise plan information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='exercise_plan.php'><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Exercise Plan</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Treatment Plan".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the treatment plan information is stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='treatment_plan.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Treatment Plan</button></a>
				<?php } ?>
			</span>
			<span class="nav-subtab">
				<?php if (strpos($value_config, ','."Discharge".',') !== FALSE) { ?>
					<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="This is where the discharge results are stored."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					<a href='discharge.php'><button type="button" class="btn brand-btn mobile-block mobile-100">Discharge</button></a>
				<?php } ?>
			</span>
		</div>

		<br clear="all" />

        <div class="table-responsive">

        <form name="form_patients" method="post" action="" class="form-inline" role="form">

            <?php
            /* Pagination Counting */
            $rowsPerPage = 25;
            $pageNum = 1;

            if(isset($_GET['page'])) {
                $pageNum = $_GET['page'];
            }

            $offset = ($pageNum - 1) * $rowsPerPage;

			echo '<a href="add_exercise_plan.php" class="btn brand-btn pull-right">Add Exercise Plan</a>';
            //$query_check_credentials = "SELECT * FROM treatment WHERE DATE(treatment_date) = DATE(NOW()) ORDER BY treatmentid DESC";

            $query_check_credentials = "SELECT * FROM treatment_exercise_plan WHERE deleted=0 ORDER BY treatmentexerciseid DESC LIMIT $offset, $rowsPerPage";
            $query = "SELECT count(*) as numrows FROM treatment_exercise_plan WHERE deleted=0 ORDER BY treatmentexerciseid DESC";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
                // Added Pagination //
                echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            } else {
            	echo "<h2>No Record Found.</h2>";
            }
            $status_loop = '';
            while($row = mysqli_fetch_array( $result ))
            {
                if($row['therapistsid'] != $status_loop) {
                    echo "<table border='2' cellpadding='10' class='table'>";
                    echo "<tr>
                    <th>Patient</th>
                    <th>Injury</th>
                    <th>Date Last Updated</th>
                    <th>Function</th>
                    </tr>";
                    echo '<h3>' . get_contact($dbc, $row['therapistsid']) . '</h3>';
                    $status_loop = $row['therapistsid'];
                }

            	echo "<tr>";
                echo '<td>'.get_contact($dbc, $row['patientid']). '</td>';
                //<a href="#"  onclick=" window.open(\''.WEBSITE_URL.'/Contact/add_contact.php?contactid='.$row['patientid'].'\', \'newwindow\', \'width=900, height=900\'); return false;">'.get_contact($dbc, $row['patientid']). '</a>
				echo '<td>' . get_all_from_injury($dbc, $row['injuryid'], 'injury_name').' - '.                  get_all_from_injury($dbc, $row['injuryid'], 'injury_type').' : '.
                    get_all_from_injury($dbc, $row['injuryid'], 'injury_date'). '</td>';
                echo '<td>' . $row['updated_at']. '</td>';
				echo '<td><a href=\'add_exercise_plan.php?treatmentexerciseid='.$row['treatmentexerciseid'].'\'>Edit</a></td>';
				echo "</tr>";
            }

            echo '</table></div>';
            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            echo "<br><br>";

			echo '<a href="add_exercise_plan.php" class="btn brand-btn pull-right">Add Exercise Plan</a>';
            ?>
        </form>

        </div>

    </div>

<?php include ('../footer.php'); ?>
