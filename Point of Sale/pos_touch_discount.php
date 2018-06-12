<?php
/* ----- Add Discount Dashboard ----- */
if ( $discount===TRUE ) { ?>
	<div class="col-sm-12">
		<div class="col-sm-3"><button class="btn brand-btn btn-lg" id="disc_percent" onclick="selectDiscountType(this);">Discount (%)</button></div>
		<div class="col-sm-3"><button class="btn brand-btn btn-lg" id="disc_value" onclick="selectDiscountType(this);">Discount ($)</button></div>
	</div>
	
	<div class="clearfix gap-bottom"></div>

	<!-- Discount Dollar Value -->
	<div id="discount_value_block" class="col-sm-12">
		<h3 class="col-sm-12">Enter Discount Value</h3>
		<div class="col-sm-12 gap-top double-gap-bottom">
			<div class="col-sm-4"><input type="text" name="discount_value" class="form-control" id="discount_value" /></div>
		</div>
		<div class="col-sm-6">
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="7">7</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="8">8</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="9">9</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#discount_value').val('5.00')">$5</button></div>
			<div class="clearfix gap-bottom"></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="4">4</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="5">5</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="6">6</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#discount_value').val('10.00')">$10</button></div>
			<div class="clearfix gap-bottom"></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="1">1</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="2">2</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="3">3</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#discount_value').val('20.00')">$20</button></div>
			<div class="clearfix gap-bottom"></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad" onclick="$('#discount_value').val('')">C</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="0">0</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number=".">.</button></div>
			<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" id="add_discount_value" onclick="addDiscount(this);">&crarr;</button></div>
			<div class="clearfix"></div>
		</div>
	</div>
	
	<!-- Discount Percentage -->
	<div id="discount_percent_block" class="col-sm-12">
		<h3 class="col-sm-12 double-gap-bottom">Select or Enter Discount Percentage</h3>
		<div>
			<div class="col-sm-2"><button id="discount_5" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">5%</button></div>
			<div class="col-sm-2"><button id="discount_10" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">10%</button></div>
			<div class="col-sm-2"><button id="discount_15" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">15%</button></div>
			<div class="col-sm-2"><button id="discount_20" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">20%</button></div>
			<div class="col-sm-2"><button id="discount_25" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">25%</button></div>
			<div class="col-sm-2"><button id="discount_30" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">30%</button></div>
			<div class="clearfix"></div>
		</div>
		<div class="double-gap-top">
			<div class="col-sm-2"><button id="discount_35" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">35%</button></div>
			<div class="col-sm-2"><button id="discount_40" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">40%</button></div>
			<div class="col-sm-2"><button id="discount_45" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">45%</button></div>
			<div class="col-sm-2"><button id="discount_50" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">50%</button></div>
			<div class="clearfix"></div>
		</div>
		<div class="double-gap-top col-sm-12">
			<input type="number" name="discount" id="discount" class="form-control double-gap-top" value="1" min="1" />
			<div class="gap-top"></div>
			<button id="decrease" class="btn brand-btn btn-lg mobile-block" onclick="changeDiscount(this);">-</button>
			<button id="increase" class="btn brand-btn btn-lg mobile-block" onclick="changeDiscount(this);">+</button>
			<button id="discount_ok" class="btn brand-btn btn-lg mobile-block" onclick="addDiscount(this);">OK</button>
		</div>
	</div><?php
	
	$discount = FALSE;
}
?>