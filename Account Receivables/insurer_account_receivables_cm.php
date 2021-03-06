<?php
/*
Client Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
checkAuthorised('accounts_receivables');

if (isset($_POST['submit_pay'])) {
    //$cm_insurerid = $_POST['cm_insurerid'];
    $deposit_number = $_POST['deposit_number'];
    $paid_date = $_POST['paid_date'];
    $paid_type = $_POST['paid_type'];
    $amount_paid = $_POST['amount_paid'];
    $history = '#'.$deposit_number.' : '.$paid_type.' : '.$paid_date.' : $'.$amount_paid.'<br>';

    foreach ($_POST['cm_insurerid'] as $id) {
        //$amount = $_POST['amount_'.$id];
		$query_update_in = "UPDATE `insurer_account_receivables_cm` SET `paid` = 'Yes', `deposit_number` = '$deposit_number', `paid_date` = '$paid_date', `paid_type` = '$paid_type', `amount_paid` = amount_paid + '$amount_paid', `amount_owing` = amount_owing - '$amount_paid', history=concat(ifnull(history,''), '$history') WHERE `cm_insurerid` = '$id'";
		$result_update_in = mysqli_query($dbc, $query_update_in);
    }

    $insurerpdf = $_POST['insurerpdf'];

    echo '<script type="text/javascript"> window.location.replace("insurer_account_receivables_cm.php?p3='.$insurerpdf.'"); </script>';
}

?>

<script type="text/javascript">
function waiting_on_collection(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "../ajax_all.php?fill=arcollection&invoiceinsurerid="+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
			//alert("Invoice moved to Collection");
			location.reload();
		}
	});
}
</script>
</head>
<body>
<?php include_once ('../navigation.php');
?>

<div class="container triple-pad-bottom">
    <div class="row">
        <div class="col-md-12">
        <h2>Insurer Accounts Receivable From Clinic Master</h2>
        
		<div class="tab-container"><?php
            if ( check_subtab_persmission( $dbc, 'accounts_receivables', ROLE, 'insurer_ar' ) === true ) { ?>
                <a href="insurer_account_receivables.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Insurer A/R</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Insurer A/R</button><?php
            }
            
            if ( check_subtab_persmission( $dbc, 'accounts_receivables', ROLE, 'patient_ar' ) === true ) { ?>
                <a href="patient_account_receivables.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Patient A/R</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Patient A/R</button><?php
            }
            
            if ( check_subtab_persmission( $dbc, 'accounts_receivables', ROLE, 'ui_invoice_report' ) === true ) { ?>
                <a href="ui_invoice_reports.php"><button type="button" class="btn brand-btn mobile-block mobile-100">UI Reports</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">UI Reports</button><?php
            }
            
            if ( check_subtab_persmission( $dbc, 'accounts_receivables', ROLE, 'insurer_ar_report' ) === true ) { ?>
                <a href="insurer_account_receivables_report.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Insurer Paid A/R Report</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Insurer Paid A/R Report</button><?php
            }
            
            if ( check_subtab_persmission( $dbc, 'accounts_receivables', ROLE, 'insurer_ar_cm' ) === true ) { ?>
                <a href="insurer_account_receivables_cm.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Insurer A/R Clinic Master</button></a><?php
            } else { ?>
                <button type="button" class="btn disabled-btn mobile-block mobile-100">Insurer A/R Clinic Master</button><?php
            } ?>
		</div>
        
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <div class="notice double-gap-bottom popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            Old data that was not transferable from Clinic Master to Clinic Ace.</div>
            <div class="clearfix"></div>
            </div>

            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            if(!empty($_GET['p3'])) {
                $insurer = $_GET['p3'];
            }
            if (isset($_POST['search_email_submit'])) {
                $insurer = $_POST['insurer'];
            }
            if (isset($_POST['search_email_all'])) {
                $insurer = '';
            }
            ?>

            <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">Insurer:</label>
                <div class="col-sm-8" style="width:20%;">
                    <select data-placeholder="Choose a Insurer..." name="insurer" class="chosen-select-deselect form-control" width="380">
                        <option value="">Display All</option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT insurer_name FROM insurer_account_receivables_cm");
                        while($row = mysqli_fetch_array($query)) {
                            if ($insurer == $row['insurer_name']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['insurer_name']."'>".$row['insurer_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>

            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            <button type="submit" name="search_email_all" value="Search" class="btn brand-btn mobile-block">Display Default</button>
            </div>

            <input type="hidden" name="insurerpdf" value="<?php echo $insurer; ?>">

            <!-- <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button> -->
            <br><br>

            <?php
                echo report_receivables($dbc, '', '', '', $insurer);
            ?>

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_receivables($dbc, $table_style, $table_row_style, $grand_total_style, $insurer) {
    //$report_data .= '<span class="pull-right"><input type="text" class="pull-right" name="deposit_number">&nbsp;Deposit / Cheque No.&nbsp;</span><br>';

    $report_data .= '<span class="pull-right">
     &nbsp;
    Amount Paid &nbsp;<input type="text" class="" required name="amount_paid">&nbsp;&nbsp;Payment Type &nbsp;<select name="paid_type" required class="form-control1" width="380">
        <option value="">Please Select</option>
        <option value="Transfer">Transfer</option>
        <option value="EFT">EFT</option>
        <option value="Cheque">Cheque</option>
    </select> &nbsp;
    Number &nbsp;<input type="text" class="" required name="deposit_number">&nbsp;&nbsp;';
    $report_data .= '&nbsp;Paid Date&nbsp;<input type="text" required class="datepicker" name="paid_date"></span>';

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th>Insurer</th>
    <th>Amount To Bill</th>
    <th>Amount Owing</th>
    <th>Amount Credit</th>
    <th>Amount Paid</th>
    <th>Paid History</th>
    <th>
		<span class="popover-examples list-inline" style="margin:0 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="Here is where you apply the payment after you select all of the associated invoices and input the deposit/cheque #."><img src="'. WEBSITE_URL .'/img/info-w.png" width="20"></a></span>
		<button type="submit" name="submit_pay" value="Submit" class="btn brand-btn">Pay</button>
	</th>
    </tr>';

    if($insurer != '') {
        $report_service = mysqli_query($dbc,"SELECT * FROM insurer_account_receivables_cm WHERE insurer_name='$insurer' ORDER BY insurer_name");
    } else {
        $report_service = mysqli_query($dbc,"SELECT * FROM insurer_account_receivables_cm WHERE (amount_to_bill > 0 OR amount_owing > 0 OR amount_credit > 0) ORDER BY insurer_name");
    }

    $amount_to_bill = 0;
    $amount_owing = 0;
    $amount_credit = 0;
    $amount_paid = 0;
    while($row_report = mysqli_fetch_array($report_service)) {
        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>'.$row_report['insurer_name'].'</td>';
        $report_data .= '<td>'.$row_report['amount_to_bill'].'</td>';
        $report_data .= '<td>'.$row_report['amount_owing'].'</td>';
        $report_data .= '<td>'.$row_report['amount_credit'].'</td>';
        $report_data .= '<td>'.$row_report['amount_paid'].'</td>';
        $report_data .= '<td>'.$row_report['history'].'</td>';
        //$report_data .= '<td>'.($row_report['amount_credit']-$row_report['amount_owing']).'</td>';

        //if($row_report['paid'] == 'Yes') {
        //    $report_data .= '<td>#'.$row_report['deposit_number'].' : '.$row_report['paid_type'].' : '.$row_report['paid_date'].'</td>';
        //} else {
            $report_data .= '<td><input type="checkbox" class="invoice" name="cm_insurerid[]" value="'.$row_report['cm_insurerid'].'" ></td>';
        //}

        //&nbsp;&nbsp;&nbsp;<input type="text" class="pull-right1" name="amount_'.$row_report['cm_insurerid'].'">

        $report_data .= '</tr>';
        $amount_to_bill += $row_report['amount_to_bill'];
        $amount_owing += $row_report['amount_owing'];
        $amount_credit += $row_report['amount_credit'];
        $amount_paid += $row_report['amount_paid'];
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td>'.number_format($amount_to_bill, 2).'</td><td>'.number_format($amount_owing, 2).'</td><td>'.number_format($amount_credit, 2).'</td><td>'.number_format($amount_paid, 2).'</td><td></td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    return $report_data;
}
?>