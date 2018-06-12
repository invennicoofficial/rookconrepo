<?php
/*
Client Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
checkAuthorised('accounts_receivables');

if (isset($_POST['submit_pay'])) {
    $invoiceinsurerid = $_POST['invoiceinsurerid'];
    $deposit_number = $_POST['deposit_number'];
    $insurer_price = 0;
    $paid_date = date('Y-m-d');

    foreach ($_POST['invoiceinsurerid'] as $id => $value) {
		$query_update_in = "UPDATE `invoice_insurer` SET `paid` = 'Yes', `deposit_number` = '$deposit_number', `paid_date` = '$paid_date' WHERE `invoiceinsurerid` = '$value'";
		$result_update_in = mysqli_query($dbc, $query_update_in);

        $invoiceid = get_all_from_invoice_insurer($dbc, $value, 'invoiceid');

        $get_staff =	mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(invoiceinsurerid) AS total_invoiceinsurerid FROM	invoice_insurer WHERE invoiceid='$invoiceid' AND paid='Waiting on Insurer'"));

        if($get_staff['total_invoiceinsurerid'] == 0) {
		    $query_update_in = "UPDATE `invoice` SET `paid` = 'Yes' WHERE `invoiceid` = '$invoiceid'";
		    $result_update_in = mysqli_query($dbc, $query_update_in);
        }
        $insurer_price += get_all_from_invoice_insurer($dbc, $invoiceinsurerid, 'insurer_price');
    }

    /*
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(summaryid) AS summaryid FROM report_summary WHERE DATE(today_date) = '$paid_date'"));
    if($get_config['summaryid'] == 0) {
        $query_insert_summary = "INSERT INTO `report_summary` (`today_date`) VALUES ('$paid_date')";
        $result_insert_summary = mysqli_query($dbc, $query_insert_summary);
    }

    $query_update_summary = "UPDATE `report_summary` SET `daily_payment_amount` = `daily_payment_amount` + '$insurer_price', `Direct Deposit` = `Direct Deposit` + $insurer_price WHERE DATE(today_date) = DATE(NOW())";
    $result_update_summary = mysqli_query($dbc, $query_update_summary);
    */

    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];
    $insurerpdf = $_POST['insurerpdf'];
    $patientpdf = $_POST['patientpdf'];
    $invoice_nopdf = $_POST['invoice_nopdf'];
    $ui_nopdf = $_POST['ui_nopdf'];

    echo '<script type="text/javascript"> window.location.replace("account_receivables.php?p1='.$starttimepdf.'&p2='.$endtimepdf.'&p3='.$insurerpdf.'&p4='.$patientpdf.'&p5='.$invoice_nopdf.'&p6='.$ui_nopdf.'"); </script>';
}

if (isset($_POST['submit_patient'])) {
    $invoice = $_POST['invoice'];

    foreach ($_POST['invoice'] as $id => $value) {
		$query_update_in = "UPDATE `invoice` SET `paid` = 'Yes' WHERE `invoiceid` = '$value'";
		$result_update_in = mysqli_query($dbc, $query_update_in);
    }

    echo '<script type="text/javascript"> window.location.replace("account_receivables.php"); </script>';
}

if (isset($_POST['printpdf'])) {
    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];

    DEFINE('START_DATE', $starttimepdf);
    DEFINE('END_DATE', $endtimepdf);

	class MYPDF extends TCPDF {

		public function Header() {
			$image_file = WEBSITE_URL.'/img/Clinic-Ace-Logo-Final-250px.png';
			$this->SetFont('helvetica', '', 13);
            $this->Image($image_file, 0, 10, 60, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
            $footer_text = 'View Receivables <b>'.START_DATE.'</b> To <b>'.END_DATE.'</b>';
            $this->writeHTMLCell(0, 0, 0 , 35, $footer_text, 0, 0, false, "R", true);
		}

		// Page footer
		public function Footer() {
			// Position at 15 mm from bottom
			$this->SetY(-15);
			$this->SetFont('helvetica', '', 9);
			$footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
			$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
		}
	}

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->AddPage('L', 'LETTER');
    $pdf->SetFont('helvetica', '', 9);

    $html .= report_receivables($dbc, $starttimepdf, $endtimepdf, 'padding:3px; border:1px solid black;', '', '','', '');

    $today_date = date('Y-m-d');
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('Download/receivables_'.$today_date.'.pdf', 'F');
    ?>

	<script type="text/javascript" language="Javascript">
	window.open('Download/receivables_<?php echo $today_date;?>.pdf', 'fullscreen=yes');
	</script>
    <?php
    $starttime = $starttimepdf;
    $endtime = $endtimepdf;
} ?>

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
        <h2>Accounts Receivable</h2>
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            if(!empty($_GET['p1'])) {
                $starttime = $_GET['p1'];
                $endtime = $_GET['p2'];
                $insurer = $_GET['p3'];
                $patient = $_GET['p3'];
                $invoice_no = $_GET['p5'];
                $ui_no = $_GET['p6'];
            }
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
                $insurer = $_POST['insurer'];
                $patient = $_POST['patient'];
                $invoice_no = $_POST['invoice_no'];
                $ui_no = $_POST['ui_no'];
            }
            if (isset($_POST['search_email_all'])) {
                $starttime = date('Y-m-d');
                $endtime = date('Y-m-d');
                $insurer = '';
                $patient = '';
                $invoice_no = '';
                $ui_no = '';
            }
            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-d');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }
            ?>
            <div class="form-group">
                <label for="site_name" class="col-sm-4 control-label">
					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Here is where you select the date range of the invoice. The date range must be large enough so that the invoice will populate."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					From:
				</label>
                <div class="col-sm-8">
                    <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
                </div>
            </div>

              <!-- end time -->
            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">Until:</label>
                <div class="col-sm-8" style="width:auto">
                    <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>"></p>
                </div>
            </div>

            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">
					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Search for invoice(s) by insurer."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					Insurer:
				</label>
                <div class="col-sm-8" style="width:20%;">
                    <select data-placeholder="Choose a Insurer..." name="insurer" class="chosen-select-deselect form-control" width="380">
                        <option value="">Display All</option>
						<?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Insurer' AND deleted=0 AND `status`=1"),MYSQLI_ASSOC));
							foreach($query as $id) {
								$selected = '';
								$selected = $id == $insurer ? 'selected = "selected"' : '';
								echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id,'name').'</option>';
							}
						?>
                    </select>
                </div>
            </div>

            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">
					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Search for invoice(s) by patient name."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					Patient:
				</label>
                <div class="col-sm-8" style="width:20%;">
                    <select data-placeholder="Choose a Patient..." name="patient" class="chosen-select-deselect form-control" width="380">
                        <option value="">Display All</option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(patientid) FROM invoice WHERE paid='Waiting on Insurer' ORDER BY patientid");
                        while($row = mysqli_fetch_array($query)) {
                            if ($patient == $row['patientid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['patientid']."'>".get_contact($dbc, $row['patientid']).'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">
					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Search by invoice # directly. You must enter a complete value."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					Invoice#:
				</label>
                <div class="col-sm-8" style="width:auto">
                    <input name="invoice_no" type="text" class="form-control" value="<?php echo $invoice_no; ?>"></p>
                </div>
            </div>

            <div class="form-group until">
                <label for="site_name" class="col-sm-4 control-label">
					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Search by the generated UI #."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					UI#:
				</label>
                <div class="col-sm-8" style="width:auto">
                    <input name="ui_no" type="text" class="form-control" value="<?php echo $ui_no; ?>"></p>
                </div>
            </div>

            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
            <span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Select this to remove all of the search filters you've applied. It will revert back to today's invoices."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
			<button type="submit" name="search_email_all" value="Search" class="btn brand-btn mobile-block">Display Default</button>
            <br>

            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">
            <input type="hidden" name="insurerpdf" value="<?php echo $insurer; ?>">
            <input type="hidden" name="patientpdf" value="<?php echo $patient; ?>">
            <input type="hidden" name="invoice_nopdf" value="<?php echo $invoice_no; ?>">
            <input type="hidden" name="ui_nopdf" value="<?php echo $ui_no; ?>">

            <!-- <button type="submit" name="printpdf" value="Print Report" class="btn brand-btn pull-right">Print Report</button> -->
            <br><br>

            <?php
                echo report_receivables($dbc, $starttime, $endtime, '', '', '', $insurer, $patient, $invoice_no, $ui_no);
            ?>

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_receivables($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style, $insurer, $patient, $invoice_no, $ui_no) {
    $report_data .= '<span class="pull-right"><input type="text" class="pull-right" name="deposit_number">&nbsp;Deposit / Cheque No.&nbsp;</span><br>';

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th>Invoice#</th>
    <th>Service Date</th>
    <th>Invoice Date</th>
    <th>Insurer</th>
    <th>Amount Receivable</th>
    <th>
		<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Here is where you can send invoices to collections by selecting the checkbox. Once you have selected it, it will remain marked as in collection. To remove it, you must pay the invoice."><img src="'. WEBSITE_URL .'/img/info-w.png" width="20"></a></span>
		Collection
	</th>
    <th>
		<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Here is where you apply the payment after you select all of the associated invoices and input the deposit/cheque #."><img src="'. WEBSITE_URL .'/img/info-w.png" width="20"></a></span>
		<button type="submit" name="submit_pay" value="Submit" class="btn brand-btn">Pay</button>
	</th>
    </tr>';

    if($insurer != '') {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_insurer ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.insurerid='$insurer' AND ii.invoiceid = i.invoiceid AND ii.paid='Waiting on Insurer' ORDER BY ii.invoiceid");
    } else if($patient != '') {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_insurer ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.paid='Waiting on Insurer' AND ii.invoiceid = i.invoiceid AND i.patientid = '$patient' ORDER BY ii.invoiceid");
    } else if($invoice_no != '') {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_insurer ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.paid='Waiting on Insurer' AND ii.invoiceid = i.invoiceid AND i.invoiceid='$invoice_no' ORDER BY ii.invoiceid");
    } else if($ui_no != '') {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_insurer ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.paid='Waiting on Insurer' AND ii.invoiceid = i.invoiceid AND ii.ui_invoiceid='$ui_no' ORDER BY ii.invoiceid");
    } else {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_insurer ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.invoiceid = i.invoiceid AND ii.paid='Waiting on Insurer' ORDER BY ii.invoiceid");
    }

    $amt_to_bill = 0;
    while($row_report = mysqli_fetch_array($report_service)) {
        $insurer_price = $row_report['insurer_price'];
        $invoiceid = $row_report['invoiceid'];
        $patientid = get_all_from_invoice($dbc, $invoiceid, 'patientid');
        $insurerid = rtrim($row_report['insurerid'],',');

        $each_insurance_payment = explode('#*#', $insurance_payment);
        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>#'.$invoiceid.' : '.get_contact($dbc, $patientid).'</td>';
        $report_data .= '<td>'.$row_report['invoice_date'].'</td>';
        $report_data .= '<td>'.$row_report['service_date'].'</td>';
        $report_data .= '<td>'.get_all_form_contact($dbc, $insurerid, 'name').'</td>';
        $report_data .= '<td>'.$insurer_price.'</td>';

        $coll_checked = '';
        if($row_report['collection'] == 1) {
            $coll_checked = ' checked disabled';
        }
        $report_data .=  '<td><input type="checkbox" '. $coll_checked.' onchange="waiting_on_collection(this)" value="'.$row_report['invoiceinsurerid'].'"></td>';

        $report_data .= '<td><input type="checkbox" class="invoice" name="invoiceinsurerid[]" value="'.$row_report['invoiceinsurerid'].'" ></td>';

        $report_data .= '</tr>';
        $amt_to_bill += $insurer_price;
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td></td><td>'.number_format($amt_to_bill, 2).'</td><td></td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th>Invoice#</th>
    <th>Service Date</th>
    <th>Invoice Date</th>
    <th>Patient</th>
    <th>Amount Receivable</th>
    <th>
		<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Here is where you apply the payment after you select all of the associated invoices and input the deposit/cheque #."><img src="'. WEBSITE_URL .'/img/info-w.png" width="20"></a></span>
		<button type="submit" name="submit_patient" value="Submit" class="btn brand-btn">Pay</button>
	</th>
    </tr>';

    $report_service = mysqli_query($dbc,"SELECT invoiceid, patientid, payment_type, service_date, invoice_date, final_price FROM invoice WHERE (DATE(invoice_date) >= '".$starttime."' AND DATE(invoice_date) <= '".$endtime."') AND paid='No' AND final_price IS NOT NULL ORDER BY patientid");

    $amt_to_bill = 0;
    while($row_report = mysqli_fetch_array($report_service)) {

        $invoiceid = $row_report['invoiceid'];
        $payment_type = ltrim($row_report['payment_type'],'#*#');

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>#'.$invoiceid.'</td>';
        $report_data .= '<td>'.$row_report['invoice_date'].'</td>';
        $report_data .= '<td>'.$row_report['service_date'].'</td>';
        $report_data .= '<td>'.get_contact($dbc, $row_report['patientid']).'</td>';
        $report_data .= '<td>'.$row_report['final_price'].'</td>';
        $report_data .= '<td><input type="checkbox" class="invoice" name="invoice[]" value="'.$row_report['invoiceid'].'"></td>';

        $report_data .= '</tr>';
        $amt_to_bill += $row_report['final_price'];
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td></td><td>'.number_format($amt_to_bill, 2).'</td><td></td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    return $report_data;
}
?>