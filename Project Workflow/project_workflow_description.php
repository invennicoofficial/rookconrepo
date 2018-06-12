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

        <h1><?php echo $_GET['projectname']; ?> Description</h1>

        <?php
        $projectid = $_GET['projectid'];
        $result_est = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT description FROM project_manage_detail WHERE projectmanageid='$projectid' ORDER BY detailid DESC"));

        echo html_entity_decode($result_est['description']);

        ?>

	</div>
</div>