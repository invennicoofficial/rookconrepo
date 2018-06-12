<?php
/* ----- Add Gift Card Dashboard ----- */
if ( $gf===TRUE ) { ?>
	<!-- Discount Percentage -->
	<div id="discount_percent_block" class="col-sm-12">
		<h3 class="col-sm-12 gap-bottom">Enter Gift Card Number</h3>
		<div class="col-sm-12">
			<input type="text" name="gf" id="gf" class="form-control double-gap-top" value="" />
			<div class="gap-top"></div>
			<button id="gf_ok" class="btn brand-btn btn-lg mobile-block" onclick="addGF(this);">OK</button>
		</div>
	</div><?php

	$gf = FALSE;
}
?>
