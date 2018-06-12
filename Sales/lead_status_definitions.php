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
				<?php
					if ( config_visible_function ( $dbc, 'sales' ) == 1 ) {
						echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
						echo '<span class="popover-examples list-inline pull-right" style="margin:15px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
					}
				?>
				
				<h1 class="single-pad-bottom">Sales Dashboard</h1>
				<?php				
					echo '<div class="mobile-100-container">';
						if ( check_subtab_persmission($dbc, 'sales', ROLE, 'how_to_guide') === TRUE ) {
							echo "<a href='lead_status_definitions.php'><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>How to Guide</button></a>&nbsp;&nbsp;";
						} else {
							echo "<button type='button' class='btn disabled-btn mobile-block mobile-100 active_tab'>How to Guide</button>&nbsp;&nbsp;";
						}
						
						if ( check_subtab_persmission($dbc, 'sales', ROLE, 'sales_pipeline') === TRUE ) {
							echo "<a href='sales_pipeline.php?status='><button type='button' class='btn brand-btn mobile-block mobile-100'>Sales Pipeline</button></a>&nbsp;&nbsp;";
						} else {
							echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Sales Pipeline</button>&nbsp;&nbsp;";
						}
						
						if ( check_subtab_persmission($dbc, 'sales', ROLE, 'schedule') === TRUE ) {
							echo "<a href='sales.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Schedule</button></a>&nbsp;&nbsp;";
						} else {
							echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Schedule</button>&nbsp;&nbsp;";
						}
						
						if ( check_subtab_persmission($dbc, 'sales', ROLE, 'reports') === TRUE ) {
							echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Reports</button></a>&nbsp;&nbsp;";
						} else {
							echo "<button type='button' class='btn disabled-btn mobile-block mobile-100'>Reports</button>&nbsp;&nbsp;";
						}
						
						echo '<br /><br />';
						
						echo "<a href='lead_status_definitions.php'><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>Lead Status Definitions</button></a>&nbsp;&nbsp;";
					echo '</div>';
				?>
				
				<h2>Lead Status Definitions</h2>
				<br />
				<img src="../img/Sales-Funnel.png" alt="Sales Funnel" />
			</div><!-- .col-md-12 -->
		</div><!-- .row -->
	</div><!-- .container -->

<?php include ('../footer.php'); ?>