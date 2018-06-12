<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
	if($_POST['new_location'] != '') {
		$location = filter_var($_POST['new_location'],FILTER_SANITIZE_STRING);
	} else {
		$location = filter_var($_POST['location'],FILTER_SANITIZE_STRING);
	}

    $businessid = $_POST['businessid'];
    $contactid = $_POST['contactid'];
    $job_desc = filter_var(htmlentities($_POST['job_desc']),FILTER_SANITIZE_STRING);
    $job_number = filter_var($_POST['job_number'],FILTER_SANITIZE_STRING);
    $afe_number = filter_var($_POST['afe_number'],FILTER_SANITIZE_STRING);
    $work_preformed = filter_var($_POST['work_preformed'],FILTER_SANITIZE_STRING);
    $short_desc = filter_var($_POST['short_desc'],FILTER_SANITIZE_STRING);

    if(empty($_POST['timetrackingid'])) {
        $query_insert_vendor = "INSERT INTO `time_tracking` (`businessid`, `contactid`, `location`, `job_number`, `afe_number`, `work_preformed`, `short_desc`, `job_desc`) VALUES ('$businessid', '$contactid', '$location', '$job_number', '$afe_number', '$work_preformed', '$short_desc', '$job_desc')";
        $result_insert_vendor = mysqli_query($dbc, $query_insert_vendor);
        $timetrackingid = mysqli_insert_id($dbc);

        for($i=0; $i<count($_POST['staffid']); $i++) {
            $staffid = $_POST['staffid'][$i];
            $position = $_POST['position'][$i];
            $reg_hours = $_POST['reg_hours'][$i];
            $reg_rate = $_POST['reg_rate'][$i];
            $ot_hours = $_POST['ot_hours'][$i];
            $ot_rate = $_POST['ot_rate'][$i];

            if($staffid != '') {
                $query_insert_invoice = "INSERT INTO `time_tracking_labour` (`timetrackingid`, `staffid`, `position`, `reg_hours`, `reg_rate`, `ot_hours`, `ot_rate`) VALUES ('$timetrackingid', '$staffid', '$position', '$reg_hours', '$reg_rate', '$ot_hours', '$ot_rate')";
                $results_are_in = mysqli_query($dbc, $query_insert_invoice);
            }
        }

        $url = 'Added';
    } else {
        $timetrackingid = $_POST['timetrackingid'];
        $query_update_vendor = "UPDATE `time_tracking` SET `businessid` = '$businessid', `contactid` = '$contactid',`location` = '$location', `job_number` = '$job_number', `afe_number` = '$afe_number', `work_preformed` = '$work_preformed', `short_desc` = '$short_desc', `job_desc` = '$job_desc' WHERE `timetrackingid` = '$timetrackingid'";
        $result_update_vendor = mysqli_query($dbc, $query_update_vendor);

        for($i=0; $i<count($_POST['timetrackinglabourid']); $i++) {
            $timetrackinglabourid = $_POST['timetrackinglabourid'][$i];
            $staffid = $_POST['staffid'][$i];
            $position = $_POST['position'][$i];
            $reg_hours = $_POST['reg_hours'][$i];
            $reg_rate = $_POST['reg_rate'][$i];
            $ot_hours = $_POST['ot_hours'][$i];
            $ot_rate = $_POST['ot_rate'][$i];
            $query_update_vendor = "UPDATE `time_tracking_labour` SET `staffid` = '$staffid', `position` = '$position',`reg_hours` = '$reg_hours', `reg_rate` = '$reg_rate', `ot_hours` = '$ot_hours', `ot_rate` = '$ot_rate' WHERE `timetrackinglabourid` = '$timetrackinglabourid'";
            $result_update_vendor = mysqli_query($dbc, $query_update_vendor);
        }

        $total_old_labour = count($_POST['timetrackinglabourid']);
        $total_new_labour = count($_POST['staffid']);

        for($i=$total_old_labour; $i<$total_new_labour; $i++) {
            $staffid = $_POST['staffid'][$i];
            $position = $_POST['position'][$i];
            $reg_hours = $_POST['reg_hours'][$i];
            $reg_rate = $_POST['reg_rate'][$i];
            $ot_hours = $_POST['ot_hours'][$i];
            $ot_rate = $_POST['ot_rate'][$i];

            if($staffid != '') {
                $query_insert_invoice = "INSERT INTO `time_tracking_labour` (`timetrackingid`, `staffid`, `position`, `reg_hours`, `reg_rate`, `ot_hours`, `ot_rate`) VALUES ('$timetrackingid', '$staffid', '$position', '$reg_hours', '$reg_rate', '$ot_hours', '$ot_rate')";
                $results_are_in = mysqli_query($dbc, $query_insert_invoice);
            }
        }
        $url = 'Updated';
    }

    echo '<script type="text/javascript"> window.location.replace("time_tracking.php"); </script>';

 //   mysqli_close($dbc);//Close the DB Connection
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var service_type = $("#service_type").val();
        var category = $("input[name=category]").val();
        var heading = $("input[name=heading]").val();
        if (service_type == '' || category == '' || heading == '' ) {
            alert("Please make sure you have filled in all of the required fields.");
            return false;
        }
    });



} );

</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('time_tracking');
?>
<div class="container">
  <div class="row">

    <h1>Time Tracking</h1>

	<div class="pad-left gap-top double-gap-bottom"><a href="time_tracking.php" class="btn config-btn">Back to Dashboard</a></div>

    <form id="form1" name="form1" method="post"	action="add_time_tracking.php" enctype="multipart/form-data" class="form-horizontal" role="form">

    <?php
        $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT time_tracking FROM field_config"));
        $value_config = ','.$get_field_config['time_tracking'].',';

        $businessid = '';
        if(!empty($_GET['bid'])) {
            $businessid = $_GET['bid'];
        }
        $contactid = '';
        $location = '';
        $job_number = '';
        $afe_number = '';
        $work_preformed = '';
        $short_desc = '';
        $job_desc = '';
        $staffid = '';
        $position = '';
        $reg_hours = '';
        $reg_rate = '';
        $ot_hours = '';
        $ot_rate = '';

        if(!empty($_GET['timetrackingid'])) {

            $timetrackingid = $_GET['timetrackingid'];
            $get_contact = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM time_tracking WHERE timetrackingid='$timetrackingid'"));

            $businessid = $get_contact['businessid'];
            $contactid = $get_contact['contactid'];
            $location = $get_contact['location'];
            $job_number = $get_contact['job_number'];
            $afe_number = $get_contact['afe_number'];
            $work_preformed = $get_contact['work_preformed'];
            $short_desc = $get_contact['short_desc'];
            $job_desc = $get_contact['job_desc'];

            $get_labour = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM time_tracking_labour WHERE timetrackingid='$timetrackingid'"));

            $staffid = $get_labour['staffid'];
            $position = $get_labour['position'];
            $reg_hours = $get_labour['reg_hours'];
            $reg_rate = $get_labour['reg_rate'];
            $ot_hours = $get_labour['ot_hours'];
            $ot_rate = $get_labour['ot_rate'];
        ?>
        <input type="hidden" id="timetrackingid" name="timetrackingid" value="<?php echo $timetrackingid ?>" />
        <?php   }      ?>

    <div class="panel-group" id="accordion2">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_comm" >
                       Client<span class="glyphicon glyphicon-minus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_comm" class="panel-collapse collapse in">
                <div class="panel-body">
                 <?php include ('add_time_tracking_client.php'); ?>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_comm1" >
                       Job Info<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_comm1" class="panel-collapse collapse">
                <div class="panel-body">
                 <?php include ('add_time_tracking_job_info.php'); ?>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_comm2" >
                       Job Description<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_comm2" class="panel-collapse collapse">
                <div class="panel-body">
                 <?php include ('add_time_tracking_job_description.php'); ?>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_comm3" >
                       Labour<span class="glyphicon glyphicon-plus"></span>
                    </a>
                </h4>
            </div>

            <div id="collapse_comm3" class="panel-collapse collapse">
                <div class="panel-body">
                 <?php include ('add_time_tracking_labour.php'); ?>
                </div>
            </div>
        </div>

    </div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="time_tracking.php" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn btn-lg pull-right" onclick="history.go(-1);return false;">Back</a>-->
			</div>
			<div class="col-sm-6">
				<button type="submit" name="add_service" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
			</div>
        </div>
        
    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>