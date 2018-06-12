<?php
	/*
	 * How To Guide
	 */
	include ('../include.php');
?>
</head>

<body>
	<?php
		include_once ('../navigation.php');
checkAuthorised('sales');
	?>

	<div class="container triple-pad-bottom">
		<div class="row">
			<?php
				if ( config_visible_function ( $dbc, 'sales' ) == 1 ) {
					echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 10px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
			
			<h1 class="double-gap-bottom">How To Guide</h1><?php
            
            $result     = get_how_to_guide( $dbc, 'Sales'); // $dbc, $tile_name
            $num_rows   = mysqli_num_rows($result); ?>
            
			<?php				
				echo '<div class="mobile-100-container">';
					echo "<a href='lead_status_definitions.php'><button type='button' class='btn brand-btn mobile-block mobile-100 active_tab'>How to Guide</button></a>&nbsp;&nbsp;";
					echo "<a href='sales_pipeline.php?status='><button type='button' class='btn brand-btn mobile-block mobile-100'>Sales Pipeline</button></a>&nbsp;&nbsp;";
					echo "<a href='sales.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Schedule</button></a>&nbsp;&nbsp;";
					echo "<a href='sales_lead_source_report.php'><button type='button' class='btn brand-btn mobile-block mobile-100'>Reports</button></a>&nbsp;&nbsp;";
				echo '</div><br /><br />';
			?><?php
		
			if ( $num_rows > 0 ) { ?>
				<div class="panel-group" id="accordion_tabs"><?php
					$i = 1;
					while ( $row = mysqli_fetch_assoc ( $result ) ) { ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion_tabs" href="#collapse_<?= $i; ?>">
										<?php echo $row['subtab']; ?><span class="glyphicon glyphicon-plus"></span>
									</a>
								</h4>
							</div>
							<div id="collapse_<?= $i; ?>" class="panel-collapse collapse">
								<div class="panel-body"><?php
									if ( !empty ( $row['image'] ) ) {
										echo '<img src="../How To Guide/download/' . $row['image'] . '">';
									}
									echo html_entity_decode ( $row['description'] ); ?>
								</div>
							</div>
						</div><!-- .panel .panel-default --><?php
						
						$i++;
					} ?>
				</div><!-- .panel-group --><?php
			} else {
                echo '<div class="notice">No How To Guide available at this moment.</div>';
            } ?>
			
		</div><!-- .row -->
	</div><!-- .container -->

<?php include ('../footer.php'); ?>