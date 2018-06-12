<?php
/*
Add Vendor
*/
include ('../include.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $businessid = $_POST['businessid'];

    $service_type = filter_var($_POST['service_type'],FILTER_SANITIZE_STRING);
    $service = filter_var($_POST['service'],FILTER_SANITIZE_STRING);
    $sub_heading = filter_var($_POST['sub_heading'],FILTER_SANITIZE_STRING);
    $heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
	$created_date = date('Y-m-d');
    $created_by = $_SESSION['contactid'];
    $phase = filter_var($_POST['phase'],FILTER_SANITIZE_STRING);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $query_insert_ca = "INSERT INTO `estimated_gantt_chart` (`businessid`, `service_type`, `service`, `sub_heading`, `heading`, `created_date`, `created_by`, `phase`, `start_date`, `end_date`) VALUES ('$businessid', '$service_type', '$service', '$sub_heading', '$heading', '$created_date', '$created_by', '$phase', '$start_date', '$end_date')";
    $result_insert_ca = mysqli_query($dbc, $query_insert_ca);

    echo '<script type="text/javascript"> alert("Estimated Gantt Chart related information Created"); window.location.replace("estimated_gantt_chart.php"); </script>';
}
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#form1").submit(function( event ) {
        var ticketid = $("#ticketid").val();
        if(ticketid == undefined) {
            var businessid = $("#businessid").val();
            var serviceid = $("#serviceid").val();
            var service_type = $("#service_type").val();
            var service_category = $("#service_category").val();

            var heading = $("input[name=heading]").val();

            if (businessid == '' || serviceid == '' || service_type == '' || service_category == '' || heading == '') {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        }
    });
});
</script>
</head>

<body>
<?php include_once ('../navigation.php');
checkAuthorised('gantt_chart');
?>
<div class="container">
  <div class="row">
    <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <h1>Gantt Chart</h1>
		<div class="gap-top double-gap-bottom"><a href="estimated_gantt_chart.php" class="btn config-btn">Back to Dashboard</a></div>
		
        <div class="panel-group" id="accordion2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse_td" >
                            Gantt Chart<span class="glyphicon glyphicon-plus"></span>
                        </a>
                    </h4>
                </div>

                <div id="collapse_td" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php
                            include ('add_estimated_gantt_chart_info.php');
                        ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-group">
			<p><span class="hp-red"><em>Required Fields *</em></span></p>
        </div>

        <div class="form-group">
            <div class="col-sm-6">
                <a href="estimated_gantt_chart.php" class="btn brand-btn btn-lg">Back</a>
				<!--<a href="#" class="btn brand-btn" onclick="history.go(-1);return false;">Back</a>-->
            </div>
            <div class="col-sm-6">
				<button type="submit" name="submit" value="submit" class="btn brand-btn btn-lg pull-right">Submit</button>
            </div>
        </div>

    </form>

  </div>
</div>
<?php include ('../footer.php'); ?>