<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('estimate');
error_reporting(0);
?>
<script type="text/javascript">

</script>

</head>
<body>

<div class="container">
	<div class="row">

        <h1><?= ESTIMATE_TILE ?> History</h1>

        <?php
        $estimateid = $_GET['estimateid'];
        $result_est = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT history FROM estimate WHERE estimateid='$estimateid'"));

        echo $result_est['history'];

        ?>

	</div>
</div>