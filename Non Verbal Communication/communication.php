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
			
			<div class="pad-left double-gap-top double-gap-bottom"><a href="<?= WEBSITE_URL; ?>/Non Verbal Communication/" class="btn config-btn">Back to Dashboard</a></div>
            
            <?php
                $valid_emojis   = array('smileys', 'boys', 'cats', 'frogs', 'girls', 'monkeys');
                $emoji_url      = ( isset($_GET['emoji']) ) ? strtolower(trim($_GET['emoji'])) : '';
                $emoji          = ( in_array($emoji_url, $valid_emojis) ) ? $emoji_url : 'frogs';
            ?>
            
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am happy','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/happy.png" class="wiggle-me" width="120" /><br />Happy<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am laughing','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/laughing.png" class="wiggle-me" width="120" /><br />Laughing<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Cool','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/cool.png" class="wiggle-me" width="120" /><br />Cool<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am tired','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/tired.png" class="wiggle-me" width="120" /><br />Tired<br /><br /></a></div>
            <div class="clearfix"></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am sad','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/sad.png" class="wiggle-me" width="120" /><br />Sad<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am upset','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/upset.png" class="wiggle-me" width="120" /><br />Upset<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am mad','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/mad.png" class="wiggle-me" width="120" /><br />Mad<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am scared','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/<?= $emoji; ?>/scared.png" class="wiggle-me" width="120" /><br />Scared<br /><br /></a></div>
            <div class="clearfix"></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('Yes','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/yes.png" class="wiggle-me" width="120" /><br />Yes<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('No','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/no.png" class="wiggle-me" width="120" /><br />No<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am hungry','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/hungry.png" class="wiggle-me" width="120" /><br />Hungry<br /><br /></a></div>
            <div class="dashboard link col-md-3 col-sm-6 col-xs-12" onClick="playTTS('I am thirsty','225')"><a><img src="<?= WEBSITE_URL; ?>/img/non_verbal_communication/thirsty.png" class="wiggle-me" width="120" /><br />Thirsty<br /><br /></a></div>
			
			<div class="clearfix double-gap-bottom"></div>

		</div>
	</div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>