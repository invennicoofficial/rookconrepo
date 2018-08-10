<?php
	/*
	 * Software Notes Dashboard
	 */
	error_reporting(0);
	include ('../include.php');
    include ('check_security.php');
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
			<h1>All Software Notes Dashboard</h1>
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
            <div class="tab pull-left"><a href="guides_dashboard.php" class="btn brand-btn text-uppercase">Software Guides</a></div><?php
            if ( $rookconnect=='rook' || $rookconnect=='localhost' ) { ?>
                <div class="tab pull-left"><a href="notes.php" class="btn brand-btn active_tab text-uppercase">Notes</a></div><?php
            } ?>
            <div class="clearfix"></div>
        </div><?php
        
        if ( config_visible_function ( $dbc, 'how_to_guide' ) == 1 ) { ?>
            <div class="tab-container"><?php
                if(isset($_GET['maintype'])) {
                    echo '<div class="tab pull-right"><a href="'.WEBSITE_URL.'/How To Guide/add_edit_guide.php?maintype='.$_GET['maintype'].'" class="btn brand-btn mobile-block">Add Software Note</a></div>';
                }
                else {
                    echo '<div class="tab pull-right"><a href="add_edit_guide.php" class="btn brand-btn">Add Software Note</a></div>';
                } ?>
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
                
				$query = "SELECT * FROM `notes` WHERE `deleted`=0 ORDER BY `tile`, `subtab` LIMIT $offset, $rowsPerPage";
				$query_pagination = "SELECT COUNT(`noteid`) AS `numrows` FROM `notes` WHERE `deleted`=0 ORDER BY `tile`, `subtab`";

				$result = mysqli_query ($dbc_htg, $query);
				
				if ( $result->num_rows > 0 ) {

					// Add Pagination
					if ( isset ( $query_pagination ) ) {
						echo display_pagination($dbc_htg, $query_pagination, $pageNum, $rowsPerPage);
					}
                    
					echo '<table class="table table-bordered">';
						echo '<tr class="hidden-xs hidden-sm">';
                            echo '<th width="20%">Tile</th>';
                            echo '<th width="20%">Sub Tab</th>';
                            echo '<th width="50%">Note</th>';
                            echo '<th width="10%">Function</th>';
						echo "</tr>";
					
						while ( $row = mysqli_fetch_assoc($results) ) {
							echo '<tr>';
                                echo '<td data-title="Tile">' . $row['tile'] . '</td>';
                                echo '<td data-title="Sub Tab">' . $row['subtab'] . '</td>';
                                echo '<td data-title="Order" align="center">' . $row['sort_order'] . '</td>';
								if ( $rookconnect=='rook' || $rookconnect=='localhost' ) {
									echo '<td data-title="Function">';
										echo '<a href="view_guide.php?guideid='.$row['guideid'].'&page='.$pageNum.'">View</a> | ';
                                        echo '<a href="add_edit_guide.php?guideid='. $row['guideid'] .'">Edit</a> | ';
										echo '<a href="../delete_restore.php?action=delete&guideid='.$row['guideid'].'&page='.$pageNum.'" onclick="return confirm(\'Are you sure you want to delete?\')">Archive</a>';
									echo '</td>';
								} else {
                                    echo '<td data-title="Function">';
										echo '<a href="view_guide.php?guideid='.$row['guideid'].'&page='.$pageNum.'">View</a>';
									echo '</td>';
                                }
							echo '</tr>';
						} //while
					echo '</table>';
					
					if ( config_visible_function ( $dbc, 'how_to_guide' ) == 1 ) {
						echo '<a href="add_edit_guide.php" class="btn brand-btn mobile-block pull-right">Add Software Note</a>';
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