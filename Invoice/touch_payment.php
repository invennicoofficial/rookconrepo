<?php
/* ----- Payment Dashboard ----- */
if ( $pay===TRUE ) { ?>
	<div class="double-gap-top">
		<div class="col-sm-12">
			<a href="?pay=yes&pay_type=cash"><button id="pay_cash" class="btn brand-btn btn-lg mobile-block <?php echo ( $pay_type=='cash' ) ? 'active_tab' : ''; ?>" onclick="">CASH</button></a>
			<a href="?pay=yes&pay_type=debit"><button id="pay_debit" class="btn brand-btn btn-lg mobile-block <?php echo ( $pay_type=='debit' ) ? 'active_tab' : ''; ?>" onclick="">DEBIT</button></a>
			<a href="?pay=yes&pay_type=credit"><button id="pay_credit" class="btn brand-btn btn-lg mobile-block <?php echo ( $pay_type=='credit' ) ? 'active_tab' : ''; ?>" onclick="">CREDIT</button></a>
		</div><?php
		
		if ( $pay_type == 'cash' ) { ?>
			<div class="col-sm-12 triple-gap-top">
				<div class="col-sm-3">BALANCE DUE ($)</div>
				<div class="col-sm-3">AMOUNT TENDERED ($)</div>
				<div class="col-sm-3">CHANGE ($)</div>
			</div>
			<div class="col-sm-12 gap-top"><?php
                $order_total = !empty($order_total) ? str_replace(',', '', $order_total) : '0.00'; ?>
				<div class="col-sm-3"><input type="text" name="amount_due" class="form-control" id="amount_due" value="<?= $order_total; ?>" readonly /></div>
				<div class="col-sm-3"><input type="text" name="amount_tentered" class="form-control" id="amount_tentered" value="<?= $order_total; ?>" /></div>
				<div class="col-sm-3"><input type="text" name="amount_change" class="form-control" id="amount_change" readonly /></div>
			</div>
			<div class="col-sm-12">
				<div class="col-sm-3"></div>
				<div class="col-sm-6 triple-gap-top">
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="7">7</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="8">8</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="9">9</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#amount_tentered').val(round2Fixed(parseFloat($('#amount_tentered').val())+10))">$10</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#amount_tentered').val(round2Fixed(parseFloat($('#amount_tentered').val())+1))">$1</button></div>
					<div class="clearfix gap-bottom"></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="4">4</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="5">5</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="6">6</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#amount_tentered').val(round2Fixed(parseFloat($('#amount_tentered').val())+20))">$20</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#amount_tentered').val(round2Fixed(parseFloat($('#amount_tentered').val())+2))">$2</button></div>
					<div class="clearfix gap-bottom"></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="1">1</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="2">2</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="3">3</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#amount_tentered').val(round2Fixed(parseFloat($('#amount_tentered').val())+50))">$50</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="$('#amount_tentered').val(round2Fixed(parseFloat($('#amount_tentered').val())+5))">$5</button></div>
					<div class="clearfix gap-bottom"></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad" onclick="$('#amount_tentered').val('0.00')">C</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number="0">0</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad number" data-number=".">.</button></div>
					<div class="col-sm-2"><button class="btn brand-btn btn-lg numpad-1x" onclick="calculateChange();">&crarr;</button></div>
					<div class="clearfix gap-bottom"></div>
				</div>
			</div>
			
			<div class="col-sm-12" id="calc_done">
				<div class="col-sm-3"></div>
				<div class="col-sm-6 double-gap-top offset-left-5"><a href="?complete=yes&payment_type=<?php echo $pay_type; ?>"><button class="btn brand-btn btn-lg">DONE</button></a></div>
			</div><?php
		}
		
		if ( $pay_type == 'debit' ) { ?>
			<div class="col-sm-12 triple-gap-top">
				<div class="col-sm-3">BALANCE DUE ($)</div>
			</div>
			<div class="col-sm-12 gap-top">
				<div class="col-sm-3"><input type="text" name="amount_due" class="form-control" id="amount_due" value="<?php echo ( !empty($order_total) ) ? $order_total : '0.00'; ?>" readonly /></div>
			</div>
			
			<div class="col-sm-12">
				<div class="col-sm-6 double-gap-top"><a href="?complete=yes&payment_type=<?php echo $pay_type; ?>"><button class="btn brand-btn btn-lg">DONE</button></a></div>
			</div><?php
		}
		
		if ( $pay_type == 'credit' ) { ?>
			<div class="col-sm-12 triple-gap-top">
				<div class="col-sm-3">BALANCE DUE ($)</div>
			</div>
			<div class="col-sm-12 gap-top">
				<div class="col-sm-3"><input type="text" name="amount_due" class="form-control" id="amount_due" value="<?php echo ( !empty($order_total) ) ? $order_total : '0.00'; ?>" readonly /></div>
			</div>
			
			<div class="col-sm-12 double-gap-top">
				<div class="col-sm-3"><button id="visa" class="btn brand-btn mobile-block touch-button">VISA</button></div>
				<div class="col-sm-3"><button id="master" class="btn brand-btn mobile-block touch-button">MASTERCARD</button></div>
				<div class="col-sm-3"><button id="amex" class="btn brand-btn mobile-block touch-button">AMERICAN EXPRESS</button></div>
			</div>
			
			<div class="col-sm-12" id="credit_done">
				<div class="col-sm-6 double-gap-top"><a id="credit_url" href=""><button class="btn brand-btn btn-lg">DONE</button></a></div>
			</div><?php
		} ?>
	</div><?php
	
	$pay = FALSE;
}
?>