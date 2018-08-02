<?php
/*
Client Listing
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);
checkAuthorised('accounts_receivables');

if (isset($_POST['submit_patient'])) {
    $invoicepatientid = $_POST['invoicepatientid'];
    $payment_type = $_POST['payment_type'];
    $paid_date = date('Y-m-d');
	$payment_receipt = "Download/ar_receipt_".preg_replace('/[^a-z]/','',strtolower($payment_type))."_".date('Y_m_d_H_i_s').".pdf";
	$patient_ids = [];
	$invoice = [];

    foreach ($_POST['invoicepatientid'] as $id => $value) {
		$query_update_in = "UPDATE `invoice_patient` SET `paid` = '$payment_type', `paid_date` = '$paid_date', `receipt_file`=CONCAT(IFNULL(CONCAT(`receipt_file`,'#*#'),''),'$payment_receipt') WHERE `invoicepatientid` = '$value'";
		$result_update_in = mysqli_query($dbc, $query_update_in);
		$invoice_info = mysqli_fetch_array(mysqli_query($dbc, "SELECT `invoice`.`patientid`, `invoice`.`invoice_date`, `invoice`.`invoiceid`, `invoice`.`therapistsid`, `invoice_patient`.`patient_price`, `invoice_patient`.`sub_total`, `invoice_patient`.`gst_amt` FROM `invoice_patient` LEFT JOIN `invoice` ON `invoice_patient`.`invoiceid`=`invoice`.`invoiceid` WHERE `invoice_patient`.`invoicepatientid`='$value'"));
		$query_update_in = "UPDATE `invoice` SET `patient_payment_receipt` = '1' WHERE `invoiceid` = '".$invoice_info['invoiceid']."'";
		$result_update_in = mysqli_query($dbc, $query_update_in);
		$patientid = $invoice_info['patientid'];
		$patient_ids[] = $invoice_info['patientid'];
		$therapist_info = '';
		if($invoice_info['therapistsid'] > 0) {
			$therapist_row = mysqli_fetch_array(mysqli_query($dbc, "SELECT `first_name`, `last_name`, `credential`, `license` FROM `contacts` WHERE `contactid`='".$invoice_info['therapistsid']."'"));
			$therapist_info .= decryptIt($therapist_row['first_name']).' '.decryptIt($therapist_row['last_name']);
			$therapist_info .= ($therapist_row['credential'] != '' ? ': '.$therapist_row['credential'] : '');
			$therapist_info .= ($therapist_row['license'] != '' ? ';<br />'.$therapist_row['license'] : '');
		}
		$invoice[] = [$invoice_info['invoice_date'],$invoice_info['invoiceid'],$therapist_info,$invoice_info['patient_price'],$invoice_info['sub_total'],$invoice_info['gst_amt']];
    }
	
	$therapistsid = get_all_from_invoice($dbc, $invoiceid, 'therapistsid');
    $service_date = get_all_from_invoice($dbc, $invoiceid, 'service_date');

    $staff = get_contact($dbc, $therapistsid);

	$next_booking = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `booking` WHERE `appoint_date` > NOW() AND `deleted`=0 AND `patientid`='".$get_invoice['patientid']."' ORDER BY `appoint_date` ASC"));
	if($next_booking['bookingid'] > 0) {
		$footer_text = '<p style="color: #37C6F4; font-size: 14; font-weight: bold; text-align: center;">Your next appointment is '.date('d/m/y',strtotime($next_booking['appoint_date']))." at ".date('G:ia',strtotime($next_booking['appoint_date'])).'</p>';
	}
	$footer_text .= html_entity_decode(get_config($dbc, 'invoice_footer'));
    DEFINE('INVOICE_LOGO', get_config($dbc, 'invoice_logo'));
    DEFINE('INVOICE_HEADER', html_entity_decode(get_config($dbc, 'invoice_header')));
    DEFINE('INVOICE_FOOTER', $footer_text);

    //Patient Invoice
	if(!class_exists('PATIENTPDF')) {
		class PATIENTPDF extends TCPDF {

			//Page header
			public function Header() {
				if(INVOICE_LOGO != '') {
					$image_file = '../Invoice/download/'.INVOICE_LOGO;
					$this->Image($image_file, 10, 10, '', 25, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
				}
				$this->setCellHeightRatio(0.7);
				$this->SetFont('helvetica', '', 8);
				$footer_text = '<p style="text-align:right;">'.INVOICE_HEADER.'</p>';
				$this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
			}

			// Page footer
			public function Footer() {
				// Position at 30 mm from bottom
				$this->SetY(-30);
				// Set font
				$this->SetFont('helvetica', 'I', 10);
				// Page number
				$footer_text = INVOICE_FOOTER;
				$this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
			}
		}
	}

	$pdf = new PATIENTPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 9);

	$html = '<h2>Payment of Accounts Receivable</h2>
	<table style="border: none;" cellspacing="20"><tr><td style="color: #46A251; width: 20%;"><p>Payment Date:</p></td>
		<td style="width: 20%;"><p>'.$paid_date.'</p></td>
		<td style="color: #46A251; width: 30%;">Client Information:</td><td style="width: 30%;">';

	foreach(array_unique($patient_ids) as $contactid) {
		if($contactid == 0) {
			$non_patient = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `invoice_nonpatient` WHERE `invoiceid`='".$get_invoice['invoiceid']."'"));
			$html .= '<p>'.$non_patient['first_name'].' '.$non_patient['last_name'].'<br/>'.$non_patient['email'].'</p>';
		} else {
			$html .= '<p>'.get_contact($dbc, $contactid).'<br/>'.get_address($dbc, $contactid).'</p>';
		}
	}
	$html .= '</td></tr>';
	$html .= '<tr style="background-color: #37C6F4; border: solid black 1px;"><td>Invoice Date</td><td>Invoice Number</td><td>Provider Name & Registration Information</td><td>Invoice Amount</td></tr>';
	$total_amt = 0;
	$sub_total = 0;
	$tax_amt = 0;
	
	foreach($invoice as $ar_line) {
		$html .= '<tr style="border: solid black 1px;"><td>'.$ar_line[0].'</td><td>'.$ar_line[1].'</td><td>'.$ar_line[2].'</td><td>$'.number_format($ar_line[3],2).'</td></tr>';
		$total_amt += $ar_line[3];
		$sub_total += $ar_line[4];
		$tax_amt += $ar_line[5];
	}
	
	$html .= '<tr><td></td><td></td><td>Total Due by Client:</td><td>$'.number_format($sub_total,2).'</td></tr>';
    //Tax
    $get_pos_tax = get_config($dbc, 'invoice_tax');
    if($get_pos_tax != '') {
		$total_tax_rate = 0;
		foreach(explode('*#*',$get_pos_tax) as $pos_tax) {
			$total_tax_rate += explode('**',$pos_tax)[1];
		}
		foreach(explode('*#*',$get_pos_tax) as $pos_tax) {
			if($pos_tax != '') {
				$pos_tax_name_rate = explode('**',$pos_tax);
				$html .= '<tr><td></td><td></td><td>'.$pos_tax_name_rate[0].'  ['.$pos_tax_name_rate[2].']:</td><td>$'.number_format($tax_amt * $pos_tax_name_rate[1] / $total_tax_rate,2).'</td></tr>';
			}
		}
    }
	$html .= '<tr><td></td><td></td><td style="color: #37C6F4; font-weight: bold;">TOTAL AMOUNT OWING:</td><td style="color: #37C6F4; font-weight: bold;">$'.number_format($total_amt,2).'</td></tr>';
	$html .= '<tr><td></td><td></td><td style="color: #37C6F4; font-weight: bold;">PAYMENT BY:</td><td style="color: #37C6F4; font-weight: bold;">'.$payment_type.' (-$'.number_format($total_amt,2).')</td></tr>';
	$html .= '<tr><td></td><td></td><td style="color: #37C6F4; font-weight: bold;">BALANCE:</td><td style="color: #37C6F4; font-weight: bold;">$0.00</td></tr></table>';

	$pdf->writeHTML(utf8_encode($html), true, false, true, false, '');
	$pdf->Output('../Invoice/'.$payment_receipt, 'F');

    $query_update_invoice = "UPDATE `contacts` SET `amount_credit` = amount_credit + '$total_amt' WHERE `contactid` = '$patientid'";
    $result_update_invoice = mysqli_query($dbc, $query_update_invoice);

    $first_name = get_all_form_contact($dbc, $patientid, 'first_name');
    $table_name = strtolower($first_name[0]);

    $result_insert_vendor = mysqli_query($dbc, "UPDATE `contacts_fn_".$table_name."` SET `amount_credit` = amount_credit + '$total_amt' WHERE `contactid` = '$patientid'");

    $last_name = get_all_form_contact($dbc, $patientid, 'last_name');
    $table_name = strtolower($last_name[0]);

    $result_insert_vendor = mysqli_query($dbc, "UPDATE `contacts_ln_".$table_name."` SET `amount_credit` = amount_credit + '$total_amt' WHERE `contactid` = '$patientid'");

    $starttimepdf = $_POST['starttimepdf'];
    $endtimepdf = $_POST['endtimepdf'];
    $patientpdf = $_POST['patientpdf'];
    $invoice_nopdf = $_POST['invoice_nopdf'];

    echo '<script type="text/javascript"> alert("Invoice Successfully Paid"); window.location.replace("patient_account_receivables.php?p1='.$starttimepdf.'&p2='.$endtimepdf.'&p4='.$patientpdf.'&p5='.$invoice_nopdf.'"); </script>';
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
			alert("Invoice moved to Collection");
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
        <h2>Patient Accounts Receivable</h2>
        
		<?php include('tile_tabs.php'); ?>
        
        <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

            <div class="notice double-gap-bottom popover-examples">
            <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
            <div class="col-sm-11"><span class="notice-name">NOTE:</span>
            Displays patient specific receivables within the selected dates.</div>
            <div class="clearfix"></div>
            </div>

            <input type="hidden" name="report_type" value="<?php echo $_GET['type']; ?>">
            <input type="hidden" name="category" value="<?php echo $_GET['category']; ?>">

            <?php
            if(!empty($_GET['p1'])) {
                $starttime = $_GET['p1'];
                $endtime = $_GET['p2'];
                $patient = $_GET['p3'];
                $invoice_no = $_GET['p5'];
            }
            if (isset($_POST['search_email_submit'])) {
                $starttime = $_POST['starttime'];
                $endtime = $_POST['endtime'];
                $patient = $_POST['patient'];
                $invoice_no = $_POST['invoice_no'];
            }
            if (isset($_POST['search_email_all'])) {
                $starttime = date('Y-m-d');
                $endtime = date('Y-m-d');
                $patient = '';
                $invoice_no = '';
            }
            if($starttime == 0000-00-00) {
                $starttime = date('Y-m-d');
            }

            if($endtime == 0000-00-00) {
                $endtime = date('Y-m-d');
            }

            if(!empty($_GET['from'])) {
                $starttime = $_GET['from'];
                $endtime = $_GET['until'];
                $patient = $_GET['patientid'];
            }
            ?>

			<br /><br />

			<div class="form-group">

                <label for="site_name" class="col-sm-1 control-label">
					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Search for invoice(s) by patient name."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					Patient:
				</label>
                <div class="col-sm-8" style="width:auto">
                    <select data-placeholder="Choose a Patient..." name="patient" class="chosen-select-deselect form-control" width="380">
                        <option value="">Display All</option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(patientid) FROM invoice_patient WHERE paid IN ('On Account','No') ORDER BY patientid");
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

				From:
                    <input name="starttime" type="text" class="datepicker" value="<?php echo $starttime; ?>">
                Until:
                    <input name="endtime" type="text" class="datepicker" value="<?php echo $endtime; ?>">

					<span class="popover-examples list-inline" style="margin:0 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Search by invoice # directly. You must enter a complete value."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
					Invoice #:
                    <input name="invoice_no" type="text" class="form-control1" value="<?php echo $invoice_no; ?>">

            <button type="submit" name="search_email_submit" value="Search" class="btn brand-btn mobile-block">Submit</button>
			<span class="popover-examples list-inline" style="margin:0 0 0 10px;"><a data-toggle="tooltip" data-placement="top" title="Select this to remove all of the search filters you've applied. It will revert back to today's invoices."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
            <button type="submit" name="search_email_all" value="Search" class="btn brand-btn mobile-block">Display Default</button>
            </div>

            <input type="hidden" name="starttimepdf" value="<?php echo $starttime; ?>">
            <input type="hidden" name="endtimepdf" value="<?php echo $endtime; ?>">
            <input type="hidden" name="patientpdf" value="<?php echo $patient; ?>">
            <input type="hidden" name="invoice_nopdf" value="<?php echo $invoice_no; ?>">

            <?php
                echo report_receivables($dbc, $starttime, $endtime, '', '', '', $patient, $invoice_no);
            ?>

        </form>

        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>

<?php
function report_receivables($dbc, $starttime, $endtime, $table_style, $table_row_style, $grand_total_style, $patient, $invoice_no) {

    $report_data .= '<span class="pull-right">Pay By
      <select id="payment_type" name="payment_type" data-placeholder="Choose a Type..." class="chosen-select-deselect1 form-control" width="380">
            <option value=""></option>
            <option value = "Master Card">MasterCard</option>
            <option value = "Visa">Visa</option>
            <option value = "Debit Card">Debit Card</option>
            <option value = "Cash">Cash</option>
            <option value = "Cheque">Cheque</option>
            <option value = "Amex">Amex</option>
            <option value = "Gift Certificate Redeem">Gift Certificate Redeem</option>
            <option value = "Pro-Bono">Pro-Bono</option>
      </select>
    </span><br>';

    if($patient != '') {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_patient ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.paid IN ('On Account','No') AND ii.invoiceid = i.invoiceid AND i.patientid = '$patient' ORDER BY ii.invoiceid");
    } else if($invoice_no != '') {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_patient ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.paid IN ('On Account','No') AND ii.invoiceid = i.invoiceid AND i.invoiceid='$invoice_no' ORDER BY ii.invoiceid");
    } else {
        $report_service = mysqli_query($dbc,"SELECT ii.*, i.service_date FROM invoice_patient ii, invoice i WHERE (DATE(ii.invoice_date) >= '".$starttime."' AND DATE(ii.invoice_date) <= '".$endtime."') AND ii.invoiceid = i.invoiceid AND ii.paid IN ('On Account','No') ORDER BY ii.invoiceid");
    }

    $report_data .= '<table border="1px" class="table table-bordered" style="'.$table_style.'">';
    $report_data .= '<tr style="'.$table_row_style.'">
    <th>Invoice#</th>
    <th>Service Date</th>
    <th>Invoice Date</th>
    <th>Patient</th>
    <th>Amount Receivable</th>
    <th>
		<span class="popover-examples list-inline" style="margin:0 0 0 0;"><a data-toggle="tooltip" data-placement="top" title="Here is where you apply the payment after you select all of the associated invoices and input the deposit/cheque #."><img src="'. WEBSITE_URL .'/img/info-w.png" width="20"></a></span>
		<button type="submit" name="submit_patient" value="Submit" class="btn brand-btn">Pay</button>
	</th>
    </tr>';

    $amt_to_bill = 0;
    while($row_report = mysqli_fetch_array($report_service)) {

        $invoiceid = $row_report['invoiceid'];
        $payment_type = ltrim($row_report['payment_type'],'#*#');

        $report_data .= '<tr nobr="true">';
        $report_data .= '<td>#'.$invoiceid.'</td>';
        $report_data .= '<td>'.$row_report['invoice_date'].'</td>';
        $report_data .= '<td>'.$row_report['service_date'].'</td>';
        $report_data .= '<td>'.get_contact($dbc, $row_report['patientid']).'</td>';
        $report_data .= '<td>'.$row_report['patient_price'].'</td>';
        $report_data .= '<td><input type="checkbox" class="invoice" name="invoicepatientid[]" value="'.$row_report['invoicepatientid'].'"></td>';

        $report_data .= '</tr>';
        $amt_to_bill += $row_report['patient_price'];
    }
    $report_data .= '<tr nobr="true">';
    $report_data .= '<td>Total</td><td></td><td></td><td></td><td>'.number_format($amt_to_bill, 2).'</td><td></td>';
    $report_data .= "</tr>";
    $report_data .= '</table><br>';

    if(!empty($_GET['from'])) {
        if($_GET['report'] == 'ar_aging') {
            $report_data .= '<div class="pad-left gap-top double-gap-bottom"><a href="../Reports/report_ar_aging_summary.php?type=ar" class="btn config-btn">Back to Receivables</a></div>';
        } else {
            $report_data .= '<div class="pad-left gap-top double-gap-bottom"><a href="../Reports/report_receivables_patient_summary.php?type=ar" class="btn config-btn">Back to Receivables</a></div>';
        }
    }

    return $report_data;
}
?>