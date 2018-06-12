<?php
/*
Dashboard
*/
include_once ('../include.php');
error_reporting(0);
?>
<script type="text/javascript">

</script>

</head>
<body>

<div class="container">
	<div class="row">
        <?php $projectid = $_GET['projectid'];
        $result_est = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT IFNULL(history,'There is no history recorded for this project.') history FROM project WHERE projectid='$projectid'")); ?>
		
        <h1><?php echo (JOBS_TILE=='Projects' ? "Project" : JOBS_TILE)." #".$projectid; ?> History</h1>

        <?php echo $result_est['history']; ?>
	</div>
</div>