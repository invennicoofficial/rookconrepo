<?php
/*
 * Non Verbal Communication
 */
include ('../include.php');
checkAuthorised('non_verbal_communication');
?>
<script src="tts.js"></script>
</head>

<body>
<?php include_once ('../navigation.php'); ?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
            <h1>Emoji Comm</h1>
			
			<!--
            <div class="notice double-gap-bottom popover-examples">
				<div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
				<div class="col-sm-11"><span class="notice-name">NOTE:</span>
				The documents tile provides the ability for each company to securely store and sort documents as needed. Each company can create as many tile names as they see fit, and then add headings and documents to each of those tiles. There’s a limit of 20 documents that can be uploaded at one time, but no limit as to how much room is available for organizing your documents.</div>
				<div class="clearfix"></div>
			</div>
            -->
            
            <?php
                $valid_comms    = array('emotions', 'activities');
                $comm_url       = ( isset($_GET['comm']) ) ? strtolower(trim($_GET['comm'])) : 'emotions';
                $comm           = ( in_array($comm_url, $valid_comms) ) ? $comm_url : 'emotions';
            ?>
            
            <div class="tab-container"><?php
                if ( check_subtab_persmission( $dbc, 'non_verbal_communication', ROLE, 'emotions' ) === true ) { ?>
                    <div class="pull-left tab"><a href="?comm=emotions"><button type="button" class="btn brand-btn mobile-block <?= ($comm=='emotions') ? 'active_tab' : ''; ?>">Emotions</button></a></div><?php
                } else { ?>
                    <div class="pull-left tab"><button type="button" class="btn disabled-btn mobile-block">Emotions</button></div><?php
                }

                if ( check_subtab_persmission( $dbc, 'non_verbal_communication', ROLE, 'activities' ) === true ) { ?>
                    <div class="pull-left tab"><a href="?comm=activities"><button type="button" class="btn brand-btn mobile-block <?= ($comm=='activities') ? 'active_tab' : ''; ?>">Activities</button></a></div><?php
                } else { ?>
                    <div class="pull-left tab"><button type="button" class="btn disabled-btn mobile-block">Activities</button></div><?php
                } ?>
                
                <div class="clearfix double-gap-bottom"></div>
            </div><?php
            
            if ( $comm=='emotions' ) { ?>
                <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="communication.php?emoji=smileys"><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/smileys/happy.png" class="wiggle-me" width="120" /></a></div>
                <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="communication.php?emoji=boys"><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/boys/happy.png" class="wiggle-me" width="120" /></a></div>
                <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="communication.php?emoji=girls"><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/girls/happy.png" class="wiggle-me" width="120" /></a></div>
                <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="communication.php?emoji=cats"><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/cats/happy.png" class="wiggle-me" width="120" /></a></div>
                <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="communication.php?emoji=frogs"><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/frogs/happy.png" class="wiggle-me" width="120" /></a></div>
                <div class="dashboard link col-lg-3 col-md-4 col-sm-6 col-xs-12"><a href="communication.php?emoji=monkeys"><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/monkeys/happy.png" class="wiggle-me" width="120" /></a></div><?php
            
            } else { ?>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Archery','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/archery.png" class="wiggle-me" width="120" /><br />Archery<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Bowling','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/bowling.png" class="wiggle-me" width="120" /><br />Bowling<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Bus or Train','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/bus-train.png" class="wiggle-me" width="120" /><br />Bus / Train<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Computer','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/computer.png" class="wiggle-me" width="120" /><br />Computer<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Cooking','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/cooking.png" class="wiggle-me" width="120" /><br />Cooking<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Gym','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/gym.png" class="wiggle-me" width="120" /><br />Gym<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Laundry','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/laundry.png" class="wiggle-me" width="120" /><br />Laundry<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Library','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/library.png" class="wiggle-me" width="120" /><br />Library<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Movies','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/movies.png" class="wiggle-me" width="120" /><br />Movies<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Park','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/park.png" class="wiggle-me" width="120" /><br />Park<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Pool','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/pool.png" class="wiggle-me" width="120" /><br />Pool<br /><br /></a></div>
                <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Swimming','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/activities/swimming.png" class="wiggle-me" width="120" /><br />Swimming<br /><br /></a></div><?php
            } ?>
			
			<div class="clearfix double-gap-bottom"></div>

		</div>
	</div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>