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

        <h1 class="double-pad-bottom">Patient Forms
        <?php
            echo '<a href="config_treatment.php" class=" mobile-block pull-right"><img style="width:50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?>
        </h1>

        <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT treatment FROM config_treatment"));
        $value_config = ','.$get_field_config['treatment'].',';
        ?>
        <?php //if (strpos($value_config, ','."Assessment".',') !== FALSE) { ?>
        <a href='Patient Forms/patientform.php'><button type="button" class="btn brand-btn mobile-block active_tab" >Forms</button></a>
        <?php //} ?>
        <?php if (strpos($value_config, ','."Assessment".',') !== FALSE) { ?>
        <a href='assessment.php'><button type="button" class="btn brand-btn mobile-block" >My Assessment</button></a>
        <?php } ?>
        <?php if (strpos($value_config, ','."Treatment".',') !== FALSE) { ?>
        <a href='treatment.php'><button type="button" class="btn brand-btn mobile-block" >Treatment</button></a>
        <?php } ?>
        <?php if (strpos($value_config, ','."Exercise Plan".',') !== FALSE) { ?>
        <a href='exercise_plan.php'><button type="button" class="btn brand-btn mobile-block" >Exercise Plan</button></a>
        <?php } ?>
        <?php if (strpos($value_config, ','."Treatment Plan".',') !== FALSE) { ?>
        <a href='treatment_plan.php'><button type="button" class="btn brand-btn mobile-block" >Treatment Plan</button></a>
        <?php } ?>
        <?php if (strpos($value_config, ','."Discharge".',') !== FALSE) { ?>
        <a href='discharge.php'><button type="button" class="btn brand-btn mobile-block" >Discharge</button></a>
        <?php } ?>
        <div class="table-responsive">

        <form name="form_patients" method="post" action="" class="form-inline" role="form">

            <?php
			echo '<a href="add_form.php" class="btn brand-btn pull-right">Add Form</a>';
            //$query_check_credentials = "SELECT * FROM treatment WHERE DATE(treatment_date) = DATE(NOW()) ORDER BY treatmentid DESC";

            $query_check_credentials = "SELECT * FROM assessment WHERE deleted=0 ORDER BY assessmentid DESC";

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {
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
				echo '<td><a href=\'add_assessment.php?assessmentid='.$row['assessmentid'].'\'>Edit</a></td>';
				echo "</tr>";
            }

            echo '</table></div>';
			echo '<a href="add_assessment.php" class="btn brand-btn pull-right">Add Assessment</a>';
            ?>
        </form>

        </div>

    </div>

<?php include ('../footer.php'); ?>