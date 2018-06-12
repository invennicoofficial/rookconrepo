<?php
/*
Dashboard
*/
include ('include.php');
error_reporting(0);
?>
<script type="text/javascript">

</script>


</head>
<body style="min-height:0px;">

<div class="container">
	<div class="row">
        <?php $user = $_GET['user'];
		echo "<h1>".$_GET['title']." Changes</h1>";
        $history = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `admin_history`, `tile_history` FROM `tile_security` WHERE `tile_name`='".$_GET['tile_name']."'"));
		if($user == 'admin')
		{
			echo "<h2>Admin Settings</h2>";
			echo "<p>".$history['admin_history']."</p>";
			echo "<h2>User Settings</h2>";
		}
		echo "<p>".$history['tile_history']."</p>";
        ?>
	</div>
</div>