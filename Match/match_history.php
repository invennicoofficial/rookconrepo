<?php
/*
Match History
*/
include_once ('../include.php');
checkAuthorised('match');
error_reporting(0);
?>
<script type="text/javascript">

</script>

</head>
<body>

<div class="container">
	<div class="row">
        <?php $matchid = $_GET['matchid'];
        $result_est = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT IFNULL(history,'There is no history recorded for this project.') history FROM match_contact WHERE matchid='$matchid'")); ?>
		
        <h1><?php echo "Match #".$matchid; ?> History</h1>

        <?php echo $result_est['history']; ?>
	</div>
</div>