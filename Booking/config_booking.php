<?php
/*
Dashboard
*/
include ('../include.php');
error_reporting(0);
checkAuthorised('booking');

if (isset($_POST['submit'])) {

    $minumum_block_booking_appointments = $_POST['minumum_block_booking_appointments'];

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='minumum_block_booking_appointments'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$minumum_block_booking_appointments' WHERE name='minumum_block_booking_appointments'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('minumum_block_booking_appointments', '$minumum_block_booking_appointments')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $followup_call_set_before = $_POST['followup_call_set_before'];

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='followup_call_set_before'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$followup_call_set_before' WHERE name='followup_call_set_before'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('followup_call_set_before', '$followup_call_set_before')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }

    $contactid = $_POST['contactid'];
    echo '<script type="text/javascript"> window.location.replace("config_checkin.php?contactid='.$contactid.'); </script>';
}
?>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
<div class="row">
<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">
        <input type="hidden" name="contactid" value="<?php echo $_GET['contactid'] ?>" />

        <div class="panel-group" id="accordion">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_survey" >
                            Block Booking<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_survey" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $minumum_block_booking_appointments = get_config($dbc, 'minumum_block_booking_appointments');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Minimum Block Booking Appointments:</label>
                        <div class="col-sm-8">
                            <input name="minumum_block_booking_appointments" value="<?php echo $minumum_block_booking_appointments; ?>" type="text" class="form-control">
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="booking.php?contactid=<?php echo $_GET['contactid']; ?>" class="btn config-btn pull-right">Back</a>
                            </div>
                            <div class="col-sm-8">
                                <button	type="submit" name="submit"	value="Submit" class="btn config-btn btn-lg	pull-right">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_follow" >
                            Booking Follow Up<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_follow" class="panel-collapse collapse">
                    <div class="panel-body">

                       <?php
                        $followup_call_set_before = get_config($dbc, 'followup_call_set_before');
                       ?>

                      <div class="form-group">
                        <label for="fax_number"	class="col-sm-4	control-label">Follow Up Date (# Of Days Before Appointment)<br><em>e.g. - 1/2/3</em>:</label>
                        <div class="col-sm-8">
                            <input name="followup_call_set_before" value="<?php echo $followup_call_set_before; ?>" type="text" class="form-control">
                        </div>
                      </div>

                        <div class="form-group">
                            <div class="col-sm-4 clearfix">
                                <a href="booking.php?contactid=<?php echo $_GET['contactid']; ?>" class="btn config-btn pull-right">Back</a>
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