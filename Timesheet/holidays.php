<?php
include('../include.php');
?>
</head>
<body>
<?php 
include_once ('../navigation.php');
checkAuthorised('timesheet');
include 'config.php';

$value = $config['settings']['Choose Fields for Holidays Dashboard'];

?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">

        <h1 class="">Holidays Dashboard
        <?php
        if(config_visible_function_custom($dbc)) {
            echo '<a href="field_config.php?from_url=holidays.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>

        <form id="form1" name="form1" method="get" enctype="multipart/form-data" class="form-horizontal" role="form">

        <?php echo get_tabs('Holidays'); ?>
        <br><br>




        <?php
        if(vuaed_visible_function_custom($dbc)) {
            echo '<a href="add_holidays.php" class="btn brand-btn mobile-block pull-right">Add Holiday</a>';
        }
        ?>
        <br><br><br>

            <div id="no-more-tables">

            <?php    

            $tb_field = $value['config_field'];

            $query_check_credentials = 'SELECT * FROM holidays WHERE `deleted`=0 ORDER BY `date` ASC';

            $result = mysqli_query($dbc, $query_check_credentials);

            $num_rows = mysqli_num_rows($result);
            if($num_rows > 0) {

                $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$tb_field." FROM field_config"));
                $value_config = ','.$get_field_config[$tb_field].',';

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>";

                foreach($value['data'] as $tab_name => $tabs) {
                    foreach($tabs as $field) {
                        if (strpos($value_config, ','.$field[2].',') !== FALSE) {
                            echo '<th>'.$field[0].'</th>';
                        }
                    }
                }
                    echo '<th>Function</th>';
                echo "</tr>";
            } else {
                echo "<h2>No Record Found.</h2>";
            }
            while($row = mysqli_fetch_array( $result ))
            {
                echo "<tr>";
                $holidays_id = $row['holidays_id'];

                foreach($value['data'] as $tab_name => $tabs) {
                    foreach($tabs as $field) {
                        if (strpos($value_config, ','.$field[2].',') !== FALSE) {
                            echo '<td>';
                            echo ($field[0] == 'Paid' ? ($row[$field[2]] ? 'Paid' : 'Unpaid') : $row[$field[2]]);
                            echo '</td>';
                        }
                    }
                }

                echo '<td>';
                if(vuaed_visible_function_custom($dbc)) {
                echo '<a href=\'add_holidays.php?holidays_id='.$holidays_id.'\'>Edit</a> | ';
                echo '<a href=\'add_holidays.php?action=delete&holidays_id='.$holidays_id.'\' onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
                echo '</td>';

                echo "</tr>";
            }

            echo '</table></div>';
            if(vuaed_visible_function_custom($dbc)) {
                echo '<a href="add_holidays.php" class="btn brand-btn mobile-block pull-right">Add Holiday</a>';
            }

            ?>
        </form>

        </div>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
