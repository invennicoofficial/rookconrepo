<?php
    $pending_result = mysqli_query ( $dbc, "SELECT SUM(`total_price`) AS `total_price` FROM `purchase_orders` WHERE `contactid`='$contactid' AND `deleted`=0 AND `status`='Pending'" );
    $accounts_payable_result = mysqli_query ( $dbc, "SELECT SUM(`total_price`) AS `total_price` FROM `purchase_orders` WHERE `contactid`='$contactid' AND `deleted`=0 AND `status`='Paying'" );
    $completed_result = mysqli_query ( $dbc, "SELECT SUM(`total_price`) AS `total_price` FROM `purchase_orders` WHERE `contactid`='$contactid' AND `deleted`=0 AND `status`='Completed'" ); ?>
    
    <div class="row">
        <div class="col-sm-12 gap-left">
            <div class="col-sm-4"><?php
                if ( $pending_result > 0 ) {
                    $pending_amt = mysqli_fetch_assoc($pending_result);
                    $pending_amt = $pending_amt['total_price'];
                } else {
                    $pending_amt = 0;
                } ?>
                <b>Pending Order Total</b><br />
                $<?= number_format($pending_amt, 2); ?>
            </div>
            
            <div class="col-sm-4"><?php
                if ( $accounts_payable_result > 0 ) {
                    $payable_amt = mysqli_fetch_assoc($accounts_payable_result);
                    $payable_amt = $payable_amt['total_price'];
                } else {
                    $payable_amt = 0;
                } ?>
                <b>Accounts Payable Total</b><br />
                $<?= number_format($payable_amt, 2); ?>
            </div>
            
            <div class="col-sm-4"><?php
                if ( $completed_result > 0 ) {
                    $completed_amt = mysqli_fetch_assoc($completed_result);
                    $completed_amt = $completed_amt['total_price'];
                } else {
                    $completed_amt = 0;
                } ?>
                <b>Completed Order Total</b><br />
                $<?= number_format($completed_amt, 2); ?>
            </div>
        </div>
    </div>