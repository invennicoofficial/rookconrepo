<?php // Agenda Meeting View
error_reporting(0);
include_once('../include.php'); ?>
</head>
<body>
<?php 
checkAuthorised('agenda_meeting');
$security = get_security($dbc, 'agenda_meeting');
include_once ('../navigation.php'); ?>
<div class="container">
		<div class="main-screen" style="background-color: #fff; border-width: 0; margin-top: -20px;">
			<h3 style="margin-top: 0; padding: 0.25em;"><a href="?">Agenda & Meeting</a><?php if($security['config'] > 0) {
				echo "<div class='pull-right' style='height: 1.35em; width: 1.35em;'><a href='?settings=pdf'><img src='".WEBSITE_URL."/img/icons/settings-4.png' class='settings-classic wiggle-me' style='height: 100%;'></a></div>";
			} ?>
			</h3>
			<div class="clearfix"></div>
			<?php if(!empty($_GET['settings']) && $security['config'] > 0) {
				include('field_config.php');
			} ?>
		</div>
</div>
<div class="clearfix"></div>
<?php include('../footer.php'); ?>