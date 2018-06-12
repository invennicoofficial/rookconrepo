<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('orientation');
?>
</head>
<body>

<?php include_once ('../navigation.php');

?>
<div class="container">
	<div class="row">

    	<!-- Admin -->
			<div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="orientation.php?contactid=<?php echo $_SESSION['contactid']; ?>" >Orientation</a></div>

            <!-- <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=Test 1" >Test 1</a></div>

            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=hse" >HSE Orientation Shop Edition</a></div>-->

			<!-- <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=back_lifting" >Back and Lifting Safety</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=confined_space" >Confined Space Safety</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=cranes_slings" >Cranes and Slings Safety</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=fall_protection" >Fall Protection Safety</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=hearing_protection" >Hearing Protection</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=job_hazard" >Job Hazard Analysis</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=ladder" >Ladder Safety</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=personal_protective" >Personal Protective Equipment</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=respirator" >Respirator Safety</a></div>
            <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="training_quiz.php?training=slips_trips_falls" >Slips, Trips, and Falls</a></div>
			-->
	</div>
</div>

<?php include ('../footer.php'); ?>