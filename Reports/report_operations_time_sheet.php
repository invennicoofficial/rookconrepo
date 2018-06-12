<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
include_once('../Timesheet/reporting_functions.php');
include_once('../Timesheet/config.php'); ?>

<script type="text/javascript">

</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container" id="timesheet_div">
	<div class="iframe_overlay" style="display:none; margin-top: -20px;margin-left:-15px;">
		<div class="iframe">
			<div class="iframe_loading">Loading...</div>
			<iframe name="timesheet_iframe" src=""></iframe>
		</div>
	</div>
    <div class="row">
        <div class="col-md-12">

        <?php echo reports_tiles($dbc);  ?>

        <br><br>

        <?php include('../Timesheet/reporting_content.php'); ?>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>