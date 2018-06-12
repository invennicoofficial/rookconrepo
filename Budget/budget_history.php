<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('budget');
error_reporting(0);
?>
</head>

	<body>
<div class="container">
	<div class="row">
        <h1><?php echo "Budget" ?> History</h1>
        <?php
			$budgetid = $_GET['budgetid'];
			$result_est = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT history FROM budget WHERE budgetid ='$budgetid'"));
			echo $result_est['history'];
        ?>
	</div>
</div>