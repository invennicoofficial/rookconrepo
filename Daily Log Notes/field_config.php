<?php
include ('../include.php');
checkAuthorised('daily_log_notes');
include 'config.php';

if (isset($_POST['submit'])) {

    foreach($config['settings'] as $settings => $value) {
        if(isset($value['config_field'])) {
            $post_value = implode(',',$_POST[$value['config_field']]);
        }

        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(fieldconfigid) AS fieldconfigid FROM field_config"));
        if($get_field_config['fieldconfigid'] > 0) {
            $query_update = "UPDATE `field_config` SET ".$value['config_field']." = '".$post_value."' WHERE `fieldconfigid` = 1";
            $result_update = mysqli_query($dbc, $query_update);
        } else {
            $query_insert_config = "INSERT INTO `field_config` (`".$value['config_field']."`) VALUES ('".$post_value."')";
            $result_insert_config = mysqli_query($dbc, $query_insert_config);
        }
    }

    echo '<script type="text/javascript"> window.location.replace("field_config.php"); </script>';
}
?>
<style>
.config_ulli li {
    list-style: none;
    float: left;
    width: 20%;
}
</style>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<h1>Daily Log Notes</h1>
<div class="pad-left gap-top double-gap-bottom"><a href="index.php" class="btn config-btn">Back to Dashboard</a></div>

<form id="form1" name="form1" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">

<div class="panel-group" id="accordion2">
<?php
$k=0;
foreach($config['settings'] as $settings => $value) {
    if(isset($value['config_field'])) {
        $get_field_config = @mysqli_fetch_assoc(mysqli_query($dbc,"SELECT ".$value['config_field']." FROM field_config"));
        $value_config = ','.$get_field_config[$value['config_field']].',';
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_field<?php echo $k; ?>" >
                        <?php echo $settings; ?><span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>
            <div id="collapse_field<?php echo $k; ?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul class="config_ulli">
                        <?php
                        foreach($value['data'] as $tabs) {
                            foreach($tabs as $field) {
                         ?>
                        <li>
                            <input type="checkbox" <?php if (strpos($value_config, ','.$field[2].',') !== FALSE) { echo " checked"; } ?> value="<?php echo $field[2];?>" style="height: 20px; width: 20px;" name="<?php echo $value['config_field']; ?>[]">&nbsp;&nbsp;<?php echo $field[0]; ?>
                        </li>
                        <?php }
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
    <div class="col-sm-6"><a href="index.php" class="btn config-btn btn-lg">Back</a></div>
    <div class="col-sm-6"><button type="submit" name="submit" value="Submit" class="btn config-btn btn-lg pull-right">Submit</button></div>
	<div class="clearfix"></div>
</div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>