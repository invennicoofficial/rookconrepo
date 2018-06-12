<?php // Security Level History
include ('../include.php');
checkAuthorised('security');
error_reporting(0);
?>
</head>
<body style="min-height:0px;">
	<div class="container">
		<div class="row">
			<?php
			$level = $_GET['level'];
			$title = $_GET['title'];
			echo "<h1>$title Changes</h1>";
			$sql = "SELECT `history` FROM `security_level_names` WHERE `identifier`='$level'";
			$history = mysqli_fetch_assoc(mysqli_query($dbc, $sql));
			echo "<p>".$history['history']."</p>";
			?>
		</div>
	</div>
</body>