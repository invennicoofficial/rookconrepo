<?php // Contacts View
error_reporting(0);
include_once('../include.php'); ?>
</head>
<body>
<?php 
checkAuthorised('estimate');
$edit_access = vuaed_visible_function($dbc, 'estimate');
$config_access = config_visible_function($dbc, 'estimate');
include_once ('../navigation.php'); ?>
<div class="container">
	<div class="row">
		<div class="main-screen" style="background-color: #fff; border-width: 0; height: auto; margin-top: -20px;">
			<h3 style="margin-top: 0; padding: 0.25em;"><a href="?">Estimate</a><?php if($config_access > 0) {
				echo "<div class='pull-right' style='height: 1.35em; width: 1.35em;'><a href='?style_settings=design_styleA'><img src='".WEBSITE_URL."/img/icons/settings-4.png' class='settings-classic wiggle-me' style='height: 100%;'></a></div>";
			} ?>
			</h3>
			<div class="clearfix"></div>
			<div style="height:40px; background-color:#3ac4f2;">
				<h4 style="margin-left:30px;padding-top:10px">
					<a href="#"><font color="white">Details</font></a>
					<a href="#"><font color="white"><span style="padding-left:30px">Scope</span></font></a>
					<?php if(!isset($_GET['preview'])): ?>
						<a href="#"><font color="white"><span style="padding-left:30px"><b>Design</b></span></font></a>
					<?php else: ?>
						<a href="?style_settings=design_styleA"><font color="white"><span style="padding-left:30px">Design</span></font></a>
					<?php endif; ?>
					<?php if(isset($_GET['preview'])): ?>
						<a href="#"><font color="white"><span style="padding-left:30px"><b>Preview</b></span></font></a>
					<?php else: ?>
						<a href="?style_settings=design_styleA&preview=design_styleA"><font color="white"><span style="padding-left:30px">Preview</span></font></a>
					<?php endif; ?>
				</h4>
			</div>
			<?php
			if(!empty($_GET['style_settings']) && $config_access > 0) {
				include('field_config.php');
			} ?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include('../footer.php'); ?>