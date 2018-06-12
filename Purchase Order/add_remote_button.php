<?php
		// Pending cross-software P.O.'
		if(vuaed_visible_function($dbc, 'purchase_order') == 1) { 
			$num_of_rows = 0;
			$pending_rows = 0;
			// **** NOTE: THE $number_of_connections variable is set only in the database_connection.php file. You must put this variable in manually for this to work. Please see one of SEA's database_connection.php files in order to see how these variables are set up. If you are trying to copy this cross-software functionality, it is advised that you use the exact same format/variable names that SEA's database_connection.php file contains.
			if(isset($number_of_connections) && $number_of_connections > 0) {
				foreach (range(1, $number_of_connections) as $i) {
					$dbc_cross = ${'dbc_cross_'.$i}; 
					$check_po_query = "SELECT * FROM purchase_orders WHERE cross_software != '' AND cross_software IS NOT NULL AND software_seller = 'main' AND deleted = 0";
					$resulx = mysqli_query($dbc_cross, $check_po_query) or die(mysqli_error($dbc_cross));
					$num_rowss = mysqli_num_rows($resulx);
					if($num_rowss > 0) {
						$num_of_rows = $num_of_rows+$num_rowss;
					}
					 while($rowie = mysqli_fetch_array( $resulx )) {
						 if($rowie['cross_software_approval'] == '' || $rowie['cross_software_approval'] == NULL) {
							 $pending_rows++;
						 }
					 }
				}
				if($num_of_rows > 0) {
					if($pending_rows > 0) {
						$pending_alert = "(".$pending_rows." Pending Approval)";
					} else {
						$pending_alert = "";
					}
					
					$active_tab = ( strpos(strtolower($_SERVER["SCRIPT_NAME"]), 'cross_software_pending.php') ) ? 'active_tab' : '';
					
					?>
					<div class="pull-left tab">
						<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="These are the uncompleted Purchase Orders created by Remote Software."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
						<?php if ( check_subtab_persmission($dbc, 'purchase_order', ROLE, 'remote') === TRUE ) { ?>
							<a href='cross_software_pending.php'><button type="button" class="btn brand-btn mobile-block mobile-100 <?= $active_tab; ?>">Remote Purchase Orders</button></a>
						<?php } else { ?>
							<button type="button" class="btn disabled-btn mobile-block mobile-100">Remote Purchase Orders</button>
						<?php } ?>
					</div><?php
				}
			}
		}
		// END of Pending Cross-Software P.O.
        ?>
