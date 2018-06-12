<?php
	/*
	 * How To Guide Tile AJAX Calls
	 */

	include ('../database_connection.php');
	include ('../function.php');
	include ('../global.php');
    include ('../database_connection_htg.php');

	/* Get Subtab/Accordion Headings for the selected Tile */
	if ( $_GET['fill'] == 'get_subtabs' ) {
		$tile		= $_GET['tile'];
		$results	= mysqli_query ( $dbc_htg, "SELECT `guideid`, `subtab` FROM `how_to_guide` WHERE `tile`='$tile' AND `deleted`='0'" );

		echo '<option value=""></option>';

		while ( $row = mysqli_fetch_assoc($results) ) {
			echo '<option value="' . $row['guideid'] . '">' . $row['subtab'] . '</option>';
		}
		
		echo '<option value="new_heading">Add New Accordion Heading</option>';
	}

	
	/* Get content for selected Subtab/Accordion Heading */
	if ( $_GET['fill'] == 'get_content' ) {
		$guideid		= $_GET['guideid'];
		$results	= mysqli_query ( $dbc_htg, "SELECT `guideid`, `sort_order`, `description` FROM `how_to_guide` WHERE `guideid`='$guideid' AND `deleted`='0'" );
		
		if ( mysqli_num_rows($results) == 1 ) {
			$row = mysqli_fetch_assoc($results);
			echo $row['sort_order'] . '**' . html_entity_decode ( $row['description'] );
		}
	}

	/* Get sort order list of selected Tile */
	if ( $_GET['fill'] == 'get_sort_order' ) {
		$tile		= $_GET['tile'];
		$results	= mysqli_query ( $dbc_htg, "SELECT `guideid`, `sort_order` FROM `how_to_guide` WHERE `tile`='$tile' AND `deleted`='0'" );
		$count		= mysqli_num_rows($results);

		if ($count > 0 ) {
			echo '<option value=""></option>';
			
			$order_nums = '';
			while ( $row = mysqli_fetch_assoc($results) ) {
				$order_nums .= $row['sort_order'] . ',';
			}
			
			for ( $i=1; $i<=15; $i++ ) {
				if ( strpos($order_nums, (string)$i) !== FALSE ) {
					echo '<option disabled value="' . $i . '">' . $i . '</option>';
				} else {
					echo '<option value="' . $i . '">' . $i . '</option>';
				}
			}
		
		} else {
			echo '<option value=""></option>';
			for ( $i=1; $i<=15; $i++ ) {
				echo '<option value="' . $i . '">' . $i . '</option>';
			}
		}
	}
?>