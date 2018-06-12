<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('project_workflow');
error_reporting(0);
?>
<script type="text/javascript">

</script>

</head>
<body>

<div class="container">
	<div class="row">

        <h1><?php echo $_GET['projectname']; ?> History</h1>

        <?php
        $projectid = $_GET['projectid'];
        $result_est = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT history FROM project_manage WHERE projectmanageid='$projectid'"));

        echo $result_est['history'];

        ?>

	</div>
</div>
