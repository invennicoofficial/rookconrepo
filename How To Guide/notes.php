<?php
	/*
	 * How To Guide Dashboard
	 */
	error_reporting(0);
	if(!isset($_GET['from_manual']) && $_GET['from_manual'] != 1)
	include ('../include.php');
    include ('../database_connection_htg.php');
    $rookconnect = get_software_name();
?>

</head>
<body>

<?php
	include_once ('../navigation.php');
	checkAuthorised('how_to_guide');
?>

<div class="container">
	<div class="row">

        <div class="col-sm-10">
			<h1>How To Guide Dashboard</h1>
		</div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if ( config_visible_function ( $dbc, 'how_to_guide' ) == 1 ) {
					if(isset($_GET['maintype'])) {
						echo '<a href="'.WEBSITE_URL.'/How To Guide/field_config_guides.php?maintype=htg" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					}
					else {
						echo '<a href="field_config_guides.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
					}
					echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
				}
			?>
        </div>
		
		<div class="clearfix triple-gap-bottom"></div>
        
        <div class="tab-container">
            <div class="tab pull-left"><a href="guides_dashboard.php" class="btn brand-btn text-uppercase">How To Guides</a></div>
            <div class="tab pull-left"><a href="notes.php" class="btn active_tab text-uppercase">Notes</a></div>
            <div class="clearfix"></div>
        </div><?php
        
        if ( config_visible_function ( $dbc, 'how_to_guide' ) == 1 ) { ?>
            <div class="tab-container">
                <div class="tab pull-right"><a href="add_edit_guide.php?type=note" class="btn brand-btn">Add Note</a></div>
                <div class="clearfix"></div>
            </div><?php
        } ?>

        <div class="no-more-tables clearfix">
			<?php
				/* Pagination Counting */
				$rowsPerPage	= 25;
				$pageNum		= 1;

				if ( isset ( $_GET['page'] ) ) {
					$pageNum = $_GET['page'];
				}

				$offset = ($pageNum - 1) * $rowsPerPage;
				
				if ( isset ( $_GET['page'] ) ) {
					$query_general = "SELECT * FROM `notes` WHERE `deleted`=0 ORDER BY `tile` LIMIT $offset, $rowsPerPage";
					$query_pagination = "SELECT COUNT(`noteid`) AS numrows FROM `notes` WHERE `deleted`=0";
				} else {
					$query_general = "SELECT * FROM `notes` WHERE `deleted`=0 ORDER BY `tile` LIMIT $offset, $rowsPerPage";
					$query_pagination = "SELECT COUNT(`noteid`) AS numrows FROM `notes` WHERE `deleted`=0";
				}

				$results_general	= mysqli_query ( $dbc_htg, $query_general );
				$num_rows_general	= mysqli_num_rows ( $results_general );
				
				if ( $num_rows_general > 0 ) {

					// Add Pagination
					if ( isset ( $query_pagination ) ) {
						echo display_pagination($dbc_htg, $query_pagination, $pageNum, $rowsPerPage);
					}
					
					$get_field_config = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `notes_dashboard` FROM `field_config`" ) );
                    if(empty($get_field_config)) {
                        mysqli_query($dbc, "INSERT INTO `field_config` (`notes_dashboard`) VALUES (',Tile,Subtab,Description,Function,')");
                        $value_config = ',Tile,Subtab,Description,Function,';
                    } else {
                        $value_config = ',' . $get_field_config['notes_dashboard'] . ',';
                    }

					echo '<table class="table table-bordered">';
						echo '<tr class="hidden-xs hidden-sm">';
							if ( strpos ( $value_config, ',Tile,' ) !== FALSE ) {
								echo '<th width="15%">Tile</th>';
							}
							if ( strpos ( $value_config, ',Subtab,' ) !== FALSE) {
								echo '<th width="25%">Sub Tab</th>';
							}
							if ( strpos ( $value_config, ',Description,' ) !== FALSE) {
								echo '<th width="45%">Note</th>';
							}
							if ( strpos ( $value_config, ',Function,' ) !== FALSE) {
								echo '<th width="15%">Function</th>';
							}
						echo "</tr>";
					
						while ( $row = mysqli_fetch_assoc ( $results_general ) ) {
							echo "<tr>";
								if ( strpos ( $value_config, ',Tile,' ) !== FALSE ) {
									echo '<td data-title="Tile">' . $row['tile'] . '</td>';
								}
								if ( strpos ( $value_config, ',Subtab,' ) !== FALSE ) {
									echo '<td data-title="Sub Tab">' . $row['subtab'] . '</td>';
								}
								if ( strpos ( $value_config, ',Description,' ) !== FALSE ) {
									echo '<td data-title="Note">' . substr ( html_entity_decode ( $row['description'] ), 0, 120 ) . ' ...</td>';
								}
								if ( strpos ( $value_config, ',Function,' ) !== FALSE ) {
									echo '<td data-title="Description">';
										echo '<a href="add_edit_guide.php?type=note&noteid='. $row['noteid'] .'">Edit</a>';
										echo ' | ';
										echo '<a href="../delete_restore.php?action=delete&noteid='.$row['noteid'].'&page='.$pageNum.'" onclick="return confirm(\'Are you sure you want to delete?\')">Archive</a>';
									echo '</td>';
								}
							echo "</tr>";
						} //while
					echo '</table>';
					
					if ( config_visible_function ( $dbc, 'how_to_guide' ) == 1 ) {
                        echo '<a href="add_edit_guide.php?type=note" class="btn brand-btn mobile-block pull-right">Add Note</a>';
					}
					
					// Add Pagination
					if ( isset ( $query_pagination ) ) {
						echo display_pagination($dbc_htg, $query_pagination, $pageNum, $rowsPerPage);
					}
				
				} else {
					echo "<h2>No Records Found.</h2>";
				}
			?>
        </div><!-- .no-more-tables -->
		
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>