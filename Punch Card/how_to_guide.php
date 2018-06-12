<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('punch_card');
error_reporting(0);
?>
</head>
<body>

<?php include ('../navigation.php'); ?>
<div class="container">
	<div class="row">
		<h1>Time Clock</h1>
		<div class="pad-5 tab-container mobile-100-container">
			<div class="tab list-inline pull-left">
                <?php if ( check_subtab_persmission($dbc, 'punch_card', ROLE, 'how_to_guide') === TRUE ) { ?>
                    <a href="how_to_guide.php" class="btn brand-btn mobile-100 active_tab">How To Guide</a>
                <?php } else { ?>
                    <button class="btn disabled-btn mobile-100">How To Guide</button>
                <?php } ?>
            </div>
			<div class="tab list-inline pull-left">
                <?php if ( check_subtab_persmission($dbc, 'punch_card', ROLE, 'time_clock') === TRUE ) { ?>
                    <a href="punch_card.php" class="btn brand-btn mobile-100">Time Clock</a>
                <?php } else { ?>
                    <button class="btn disabled-btn mobile-100">Time Clock</button>
                <?php } ?>
            </div>
			<div class="clearfix"></div>
		</div>
		<div class=""><img src="download/ROOKConnect-TimeCard-Flow.png" alt="Time Clock How To Guide" /></div>
	</div>
</div>

<?php include ('../footer.php'); ?>