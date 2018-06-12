<?php // Tile Configuration History
include ('../include.php');
checkAuthorised('security');
error_reporting(0);
?>
</head>
<body style="min-height:0px;">
	<div class="container">
		<div class="row">
			<?php echo "<h1>".$_GET['title']." Changes</h1>";
			echo "<p>".mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `tile_history` FROM `tile_security` WHERE `tile_name`='".$_GET['tile_name']."'"))['tile_history']."</p>"; ?>
		</div>
	</div>
</body>