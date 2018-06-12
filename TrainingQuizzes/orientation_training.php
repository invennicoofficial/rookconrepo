<?php
/*
Dashboard FFM
*/
include ('../include.php');
checkAuthorised('training_quiz');
?>
</head>
<body>

<?php include_once ('../navigation.php');

?>
<div class="container">
	<div class="row">

            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=whmis1" >WHMIS 1</a></div>

            <!--
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=whmis2" >WHMIS 2</a></div>
            -->

			<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training.php?type=result">Training Result</a></div>

	</div>
</div>

<?php include ('../footer.php'); ?>