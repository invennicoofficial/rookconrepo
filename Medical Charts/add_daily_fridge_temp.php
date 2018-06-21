<?php
include ('../include.php');
include 'config.php';
$from_url = (!empty($_GET['from_url']) ? $_GET['from_url'] : 'daily_fridge_temp.php');

$value = $config['settings']['Choose Fields for Daily Fridge Temp'];

if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    $date_of_archival = date('Y-m-d');
    mysqli_query($dbc, "UPDATE daily_fridge_temp set deleted = 1, `date_of_archival` = '$date_of_archival' WHERE daily_fridge_temp_id=".$_GET['daily_fridge_temp_id']);

    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';
}

if (isset($_POST['submit'])) {

    $inputs = get_post_inputs($value['data']);
    $files = get_post_uploads($value['data']);
    move_files($files);

    if(empty($_POST['daily_fridge_temp_id'])) {
        $query_insert_vendor = prepare_insert($inputs, 'daily_fridge_temp');
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $daily_fridge_temp = mysqli_insert_id($dbc);
        $url = 'Added';
    } else {
        $daily_fridge_temp_id = $_POST['daily_fridge_temp_id'];
        $query_update_vendor = prepare_update($inputs, 'daily_fridge_temp', 'daily_fridge_temp_id', $daily_fridge_temp_id);
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        $url = 'Updated';
    }

    if (!file_exists('download')) {
        mkdir('download', 0777, true);
    }

    echo '<script type="text/javascript"> window.location.replace("'.$from_url.'"); </script>';

}
?>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('charts');
?>
<div class="container">
  <div class="row">

    <h1>Daily Fridge Temp</h1>
	<div class="gap-left gap-top double-gap-bottom"><a href="<?= $from_url ?>" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php

        $inputs = get_all_inputs($value['data']);

        foreach($inputs as $input) {
            $$input = '';
        }

        if(!empty($_GET['daily_fridge_temp_id'])) {

            $daily_fridge_temp_id = $_GET['daily_fridge_temp_id'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM daily_fridge_temp WHERE daily_fridge_temp_id='$daily_fridge_temp_id'"));

            foreach($inputs as $input) {
                $$input = $get_contact[$input];
            }

        ?>
        <input type="hidden" id="daily_fridge_temp_id" name="daily_fridge_temp_id" value="<?php echo $daily_fridge_temp_id ?>" />
        <?php   }      ?>



<div class="panel-group" id="accordion2">
<?php
$k=0;
if(isset($value['config_field'])) {
    $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
    $value_config = ','.$get_field_config[$value['config_field']].',';
    foreach($value['data'] as $tab_name => $tabs) {
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field<?php echo $k; ?>" >
                    <?php echo $tab_name; ?><span class="glyphicon glyphicon-plus"></span>
                </a>
            </h4>
        </div>
        <div id="collapse_field<?php echo $k; ?>" class="panel-collapse collapse">
            <div class="panel-body">
                    <?php
                        foreach($tabs as $field) {
                            if (strpos($value_config, ','.$field[2].',') !== FALSE) {
                                echo get_field($field, @$$field[2], $dbc);
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
       $k++;
    }

}


?>
</div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6"><a href="<?= $from_url ?>" class="btn brand-btn btn-lg">Back</a></div>
			<div class="col-sm-6"><button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button></div>
			<div class="clearfix"></div>
        </div>



    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>
