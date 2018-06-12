<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(0);
checkAuthorised('check_in');

if (isset($_POST['submit'])) {

    $communication_check_in_way = $_POST['communication_check_in_way'];

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='communication_check_in_way'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$communication_check_in_way' WHERE name='communication_check_in_way'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('communication_check_in_way', '$communication_check_in_way')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $contactid = $_POST['contactid'];
    echo '<script type="text/javascript"> window.location.replace("config_checkin.php?id='.$contactid.'); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <input type="hidden" name="contactid" value="<?php echo $_GET['id'] ?>" />

        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_survey" >
                            Check In Communication Method<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_survey" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $communication_check_in_way = get_config($dbc, 'communication_check_in_way');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Method of Communication:</label>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose a Way..."  name="communication_check_in_way" class="chosen-select-deselect form-control" width="380">
                                <option value=""></option>
                                <option <?php if ($communication_check_in_way == "Email") { echo " selected"; } ?> value="Email">Email</option>
                            </select>
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="checkin.php?contactid=<?php echo $_GET['id']; ?>" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

</form>
</div>
</div>

<?php include ('../footer.php'); ?>