<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('field_ticket_estimates');
error_reporting(0);
?>
<script type="text/javascript">

</script>

</head>
<body>

<div class="container">
	<div class="row">

        <h1>Bid History</h1>

        <?php
        $estimateid = $_GET['estimateid'];
        $result_est = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT history FROM bid WHERE estimateid='$estimateid'"));

        echo $result_est['history'];

        ?>

	</div>
</div>