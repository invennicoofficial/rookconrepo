<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('staff');
error_reporting(0);
?>
<script type="text/javascript">

</script>


</head>
<body style="min-height:0px;">

<div class="container">
	<div class="row">
        <?php
		$id = intval($_GET['id']);
		$sql = "SELECT name, history FROM positions WHERE position_id='$id'";
        $history = mysqli_fetch_assoc(mysqli_query($dbc, $sql));
		echo "<h1>{$history['name']} Changes</h1>";
		echo "<p>".$history['history']."</p>";
        ?>
	</div>
</div>