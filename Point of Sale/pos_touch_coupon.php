<?php
/* ----- Add Coupon Dashboard ----- */
if ( $coupon===TRUE ) { ?>
	<div class="col-sm-12"><?php
		$today		= date('Y-m-d');
		$result		= mysqli_query( $dbc, "SELECT * FROM `pos_touch_coupons` WHERE `expiry_date`>='$today' AND `deleted`=0" );
		$num_rows	= mysqli_num_rows($result);
		
		if ( $num_rows > 0 ) {
			$get_field_config	= mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `pos_dashboard` FROM `field_config`" ) );
			$value_config		= ',' . $get_field_config['pos_dashboard'] . ',';
			
			echo '<table class="table table-bordered">';
				echo '<tr class="hidden-xs hidden-sm">';
					if ( strpos($value_config, ',Coupon ID,') !== FALSE ) {
						echo '<th width="14%">Coupon ID</th>';
					}
					if ( strpos($value_config, ',Coupon Title,') !== FALSE ) {
						echo '<th width="40%">Title</th>';
					}
					if ( strpos($value_config, ',Discount,') !== FALSE ) {
						echo '<th width="22%">Coupon Value</th>';
					}
					if ( strpos($value_config, ',Function,') !== FALSE ) {
						echo '<th width="24%">Function</th>';
					}
				echo '</tr>';

				while ( $row = mysqli_fetch_array($result) ) {
					echo '<tr>';
						if ( strpos($value_config, ',Coupon ID,') !== FALSE ) {
							echo '<td data-title="Coupon ID">' . $row['couponid'] . '</td>';
						}
						if ( strpos($value_config, ',Coupon Title,') !== FALSE ) {
							echo '<td data-title="Title">' . $row['title'] . '</td>';
						}
						if (strpos($value_config, ',Discount,') !== FALSE ) {
							if ( $row['discount_type']=='$' ) {
								$coupon_discount = '$' . number_format( $row['discount'], 2 );
							} else {
								$coupon_discount = $row['discount'] . '%';
							}
							echo '<td data-title="Coupon Value">' . $coupon_discount . '</td>';
						}
						if ( strpos($value_config, ',Function,') !== FALSE ) {
							echo '
								<td data-title="Function">
									<button id="coupon_' . $row['couponid'] . '" class="btn brand-btn mobile-block touch-button" onclick="addCoupon(this);">ADD COUPON</button>
								</td>';
						}
					echo '</tr>';
				}
			echo '</table>';
		} ?>
	</div><?php
	
	$coupon = FALSE;
}
?>