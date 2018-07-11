<?php
	/*
	 * Software Guide Tile AJAX Calls
	 */
    
     include_once('../include.php');
    include ('../database_connection_htg.php');
    ob_clean();
    
    /* Load panel on mobile. Called from guide_index.php */
    if ( $_GET['fill'] == 'load_panel' ) {
        $guide = preg_replace('/[^0-9]/', '', $_GET['guide']);
        $result = mysqli_query($dbc_htg, "SELECT `description` FROM `how_to_guide` WHERE `guideid`='$guide' AND `deleted`=0");
        if ( $result->num_rows > 0 ) {
            while ( $row=mysqli_fetch_assoc($result) ) {
                echo html_entity_decode($row['description']);
            }
        } else {
            echo 'No how to guide found.';
        }
    }
?>