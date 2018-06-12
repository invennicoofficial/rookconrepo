<?php
include('../include.php');
?>
</head>
<body>
<?php $detect = new Mobile_Detect;
$is_mobile = ( $detect->isMobile() ) ? true : false;
$charts_tile_charts = explode(',', get_config($dbc, 'charts_tile_charts'))[0];
$custom_monthly_charts = explode(',', get_config($dbc, 'custom_monthly_charts'))[0];

if($is_mobile) {
	include_once ('../navigation.php');
checkAuthorised('charts');
	include 'config.php'; ?>

	<div class="container triple-pad-bottom">
	    <div class="row">
	        <div class="col-md-12">

		        <h1 class="">Dashboard
		        <?php
		        if(config_visible_function_custom($dbc)) {
		            echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
		        }
		        ?>
		        </h1>
		        <div class="double-gap-top">
		        	<?php foreach ($config['tabs'] as $title => $url) {
						if(strpos($charts_tile_charts, $url) !== FALSE) { ?>
			        		<div class="col-xs-10">
				        		<a href="<?= $url ?>.php" class="btn brand-btn col-xs-12"><?= $title ?></a>
				        	</div>
				        	<div class="col-xs-2">
				        		<a href="add_<?= $url ?>.php?from_url=index.php"><img src="<?= WEBSITE_URL ?>/img/icons/ROOK-add-icon.png" class="pull-right" style="width: 2em;"></a>
				        	</div>
			        	<?php }
			        } ?>
		        	<?php foreach ($custom_monthly_charts as $custom_monthly_chart) {
						if(!empty($custom_monthly_chart)) {?>
			        		<div class="col-xs-12">
			        			<a href="custom_chart.php?type=<?= $custom_monthly_chart ?>" class="btn brand-btn col-xs-12"><?= $custom_monthly_chart ?></a>
			        		</div>
			        	<?php }
			        } ?>
		        </div>
	        </div>

	        </div>
	    </div>
	</div>
	<?php include ('../footer.php'); ?>
<?php } else if(!empty($charts_tile_charts)) {
	$url = $charts_tile_charts.'.php';
	header("Location: ".$url);
} else if(!empty($custom_monthly_charts)) {
	$url = 'custom_chart.php?type='.$custom_monthly_charts;
	header("Location: ".$url);
} else {
	include_once ('../navigation.php');
	checkAuthorised();
	include 'config.php'; ?>

	<div class="container triple-pad-bottom">
	    <div class="row">
	        <div class="col-md-12">

		        <h1 class="">Dashboard
		        <?php
		        if(config_visible_function_custom($dbc)) {
		            echo '<a href="field_config.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
		        }
		        ?>
		        </h1>
		        <br><br>
		        <h3>No Charts Enabled.</h3>
	        </div>

	        </div>
	    </div>
	</div>
	<?php include ('../footer.php'); ?>
<?php } ?>