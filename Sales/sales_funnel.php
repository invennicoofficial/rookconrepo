<?php
/*
 * Sales Funnel (Sales tile landing page)
 */
include ('../include.php');
?>
</head>

<body><?php
	include_once ('../navigation.php');
checkAuthorised('sales'); ?>

	<div class="container triple-pad-bottom">
		<div class="row">
			<div class="col-md-12">
				<h1 class="single-pad-bottom">Sales Dashboard<?php
				if(config_visible_function($dbc, 'sales') == 1) {
					echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:0 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
				
				echo '<br /><br />';
				
				echo '<div class="mobile-100-container">';
					echo "<a href='sales_funnel.php'><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>Sales Funnel</button></a>&nbsp;&nbsp;";
					echo "<a href='sales.php?type=today'><button type='button' class='btn brand-btn mobile-block  mobile-100'>Today</button></a>&nbsp;&nbsp;";
					echo "<a href='sales.php?type=week'><button type='button' class='btn brand-btn mobile-block mobile-100'>This Week</button></a>&nbsp;&nbsp;";
					echo "<a href='sales.php?type=month'><button type='button' class='btn brand-btn mobile-block mobile-100'>This Month</button></a>&nbsp;&nbsp;";echo "<a href='sales.php?type=custom'><button type='button' class='btn brand-btn mobile-block mobile-100'>Custom</button></a>&nbsp;&nbsp;";
					echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Reports</button></a>&nbsp;&nbsp;";
					//echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block'>Lead Source Report</button></a>&nbsp;&nbsp;";
					//echo "<a href='sales_next_action_report.php'><button type='button' class='btn brand-btn mobile-block'>Next Action Report</button></a>&nbsp;&nbsp;";
				echo '</div>'; ?>
				</h1>
				
				<h2>Sales Funnel</h2>
				<br />
				<img src="../img/Sales-Funnel.png" alt="Sales Funnel" />
			</div><!-- .col-md-12 -->
		</div><!-- .row -->
	</div><!-- .container -->

<?php include ('../footer.php'); ?>