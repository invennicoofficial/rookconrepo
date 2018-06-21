<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('field_ticket_estimates');
include_once('../tcpdf/tcpdf.php');
error_reporting(0);

if((!empty($_GET['estimateid'])) && ($_GET['type'] == 'approve')) {

    $estimateid = $_GET['estimateid'];
    $get_estimate_data = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM bid WHERE `estimateid` = '$estimateid'"));
    $html = $get_estimate_data['estimate_data'];

    $estimate_name = $get_estimate_data['estimate_name'];
    $contactid = $_SESSION['contactid'];
    echo insert_day_overview($dbc, $contactid, 'Bid', date('Y-m-d'), '', 'Approved Bid '.$estimate_name);

    $history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Approved on '.date('Y-m-d H:i:s').'<br>';
    $query_update_report = "UPDATE `bid` SET `status` = 'Pending Quote', `history` = CONCAT(history,'$history') WHERE `estimateid` = '$estimateid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);

    $get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM field_config_bid"));
    $logo = $get_field_config['logo'];
    $quote_pdf_header = $get_field_config['quote_pdf_header'];
    $quote_pdf_footer = $get_field_config['quote_pdf_footer'];
    $quote_pdf_footer_logo = $get_field_config['quote_pdf_footer_logo'];

    $front_company_logo = $get_estimate_data['front_company_logo'];
    $front_client_info = $get_estimate_data['front_client_info'];
    $front_other_info = $get_estimate_data['front_other_info'];
    $front_client_logo = $get_estimate_data['front_client_logo'];
    $front_content_pages = $get_estimate_data['front_content_pages'];
    $last_content_pages = $get_estimate_data['last_content_pages'];

    DEFINE('HEADER_LOGO', $logo);
    DEFINE('FOOTER_LOGO', $quote_pdf_footer_logo);
	DEFINE('HEADER_TEXT', html_entity_decode($get_field_config['quote_pdf_header']));
    DEFINE('FOOTER_TEXT', html_entity_decode($get_field_config['quote_pdf_footer']));

    $quote_term_condition = get_config($dbc, 'quote_term_condition');
    DEFINE('TERM_CONDITION', $quote_term_condition);
    // PDF

    class MYPDF extends TCPDF {

        //Page header
        public function Header() {
            if($front_client_info != '') {
                if ($this->PageNo() > 1) {
                    if(HEADER_LOGO != '') {
                        $image_file = 'download/'.HEADER_LOGO;
                        $this->Image($image_file, 10, 5, 25, '', '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
                    }

                    if(HEADER_TEXT != '') {
                        $this->setCellHeightRatio(0.7);
                        $this->SetFont('helvetica', '', 8);
                        $footer_text = '<p style="text-align:right;">'.HEADER_TEXT.'</p>';
                        $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
                    }
                }
            } else {
                if(HEADER_LOGO != '') {
                    $image_file = 'download/'.HEADER_LOGO;
                    $this->Image($image_file, 10, 5, 25, '', '', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
                }

                if(HEADER_TEXT != '') {
                    $this->setCellHeightRatio(0.7);
                    $this->SetFont('helvetica', '', 8);
                    $footer_text = '<p style="text-align:right;">'.HEADER_TEXT.'</p>';
                    $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
                }
            }
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = '<p style="text-align:right;">'.$this->getAliasNumPage().'</p>';
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "R", true);

            if(FOOTER_TEXT != '') {
                $this->SetY(-30);
                $this->setCellHeightRatio(0.7);
                $this->SetFont('helvetica', '', 8);
                $footer_text = FOOTER_TEXT;
                $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
            }

            if(FOOTER_LOGO != '') {
                //$this->SetY(-30);
                $image_file = 'download/'.FOOTER_LOGO;
                $this->Image($image_file, 11, 275, 100, '', '', '', '', false, 300, 'C', false, false, 0, false, false, false);
            }

            // Position at 15 mm from bottom
            if(TERM_CONDITION != '') {
                $this->SetY(-30);
                $this->SetFont('helvetica', 'I', 8);
                $footer_text = TERM_CONDITION.'<br><br>';
                $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "L", true);
            }
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    //$pdf->setFooterData(array(0,640,0), array(0,640,1280));

    $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);

    if($front_client_info != '') {
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->setCellHeightRatio(1);
        $pdf_html = '';
        if($front_company_logo != '') {
        $pdf_html .= '<div style="text-align:center;"><img src="download/'.$front_company_logo.'" border="0" alt=""></div>';
        }
        if($front_client_logo != '') {
        $pdf_html .= '<div style="text-align:center;"><img src="download/'.$front_client_logo.'" border="0" alt=""></div>';
        }
        $pdf_html .= html_entity_decode($front_client_info).'<br>';
        $pdf_html .= html_entity_decode($front_other_info);
        $pdf->writeHTML($pdf_html, true, false, true, false, '');
        $pdf->lastPage();
    }

    if($front_content_pages != '') {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->setCellHeightRatio(1.75);
        $pdf_html = '';
        $pdf_html .= html_entity_decode($front_content_pages);
        $pdf->writeHTML($pdf_html, true, false, true, false, '');
        $pdf->lastPage();
    }

    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 8);
    $pdf->setCellHeightRatio(1.75);
    $pdf_html = '';
    $pdf_html .= html_entity_decode($html);
    $pdf->writeHTML($pdf_html, true, false, true, false, '');

    if($last_content_pages != '') {
        $pdf->lastPage();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 8);
        $pdf->setCellHeightRatio(1.75);
        $pdf_html = '';
        $pdf_html .= html_entity_decode($last_content_pages).'<br>';
        $pdf->writeHTML($pdf_html, true, false, true, false, '');
    }

    $pdf->Output('download/quote_'.$estimateid.'.pdf', 'F');

    $message = 'Bid Approved and move to Quote.';
    $url = '../Field Ticket Estimates/quotes.php';

    echo '<script type="text/javascript"> alert("'.$message.'"); window.location.replace("'.$url.'"); </script>';
}

if((!empty($_GET['estimateid'])) && ($_GET['type'] == 'reject')) {
    $estimateid = $_GET['estimateid'];
        $date_of_archival = date('Y-m-d');
    $query_update_report = "UPDATE `bid` SET `deleted` = 1, `date_of_archival` = '$date_of_archival' WHERE `estimateid` = '$estimateid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);

    $estimate_name = get_estimate($dbc, $estimateid, 'estimate_name');
    $contactid = $_SESSION['contactid'];
    echo insert_day_overview($dbc, $contactid, 'Bid', date('Y-m-d'), '', 'Rejected Bid '.$estimate_name);

    $message = 'Bid Rejected and Removed from Bid.';
    $url = 'estimate.php';
    echo '<script type="text/javascript"> alert("'.$message.'"); window.location.replace("'.$url.'"); </script>';
}

if((!empty($_GET['estimateid'])) && (!empty($_GET['status']))) {
    $estimateid = $_GET['estimateid'];
    $status = $_GET['status'];
    $query_update_report = "UPDATE `bid` SET `status` = '$status' WHERE `estimateid` = '$estimateid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);

    if($status == 'Approve') {
        echo '<script type="text/javascript"> alert("Bid Approved and Move to Project."); window.location.replace("estimate.php"); </script>';
    } else {
        echo '<script type="text/javascript"> alert("Bid Denied and Removed from Bid."); window.location.replace("estimate.php"); </script>';
    }
}
?>
<script type="text/javascript">
$(document).ready(function() {

$('.iframe_open').click(function(){
		var id = $(this).attr('id');
	   $('#iframe_instead_of_window').attr('src', 'estimate_history.php?estimateid='+id);
	   $('.iframe_title').text('Bid History');
	   $('.iframe_holder').show();
	   $('.hide_on_iframe').hide();
});

$('.close_iframer').click(function(){
	var result = confirm("Are you sure you want to close this window?");
	if (result) {
		$('.iframe_holder').hide();
		$('.hide_on_iframe').show();
	}
});

});
</script>

</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">
        <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <div class="col-sm-10"><h1>Bid</h1></div>
		<div class="col-sm-2 double-gap-top">
			<?php
				if(config_visible_function($dbc, 'estimate') == 1) {
					echo '<a href="field_config_estimate.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
				}
			?>
		</div>
		<div class="clearfix"></div>

        <?php if ( check_subtab_persmission($dbc, 'field_ticket_estimates', ROLE, 'bid') === TRUE ) { ?>
            <a href="estimate.php"><button type="button" class="btn brand-btn mobile-block active_tab mobile-100">Bid</button></a>&nbsp;&nbsp;
        <?php } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Bid</button></a>&nbsp;&nbsp;
        <?php } ?>

        <?php if ( check_subtab_persmission($dbc, 'field_ticket_estimates', ROLE, 'cost_estimate') === TRUE ) { ?>
            <a href="quotes.php"><button type="button" class="btn brand-btn mobile-100 mobile-block">Cost Estimate</button></a>&nbsp;&nbsp;
        <?php } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Cost Estimate</button></a>&nbsp;&nbsp;
        <?php } ?>

		<div class='mobile-100-container'>
		<br>
        <?php
        if(vuaed_visible_function($dbc, 'estimate') == 1) {
			echo '<a href="add_estimate.php" class="btn brand-btn pull-right mobile-100-pull-right">Add Bid</a>';
        }
		echo '</div>';

        // Pagination Counting //
        $rowsPerPage = 25;
        $pageNum = 1;

        if(isset($_GET['page'])) {
            $pageNum = $_GET['page'];
        }

        $offset = ($pageNum - 1) * $rowsPerPage;

        if(!empty($_GET['businessid'])) {
            $businessid = $_GET['businessid'];
            $query_check_credentials = "SELECT r.*, c.name FROM bid r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND r.businessid = '$businessid' LIMIT $offset, $rowsPerPage";
            $query = "SELECT count(c.name) as numrows FROM bid r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND r.businessid = '$businessid'";
        } else {
            $query_check_credentials = "SELECT r.*, c.name FROM bid r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND (r.status='Saved' OR r.status='Submitted') ORDER BY estimateid DESC LIMIT $offset, $rowsPerPage";
            $query = "SELECT count(c.name) as numrows FROM bid r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND (r.status='Saved' OR r.status='Submitted') ORDER BY estimateid DESC";
        }


        $base_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT config_fields_dashboard FROM field_config_bid WHERE `fieldconfigestimateid` = 1 LIMIT $offset, $rowsPerPage"));


        $config_fields_dashboard = ','.$base_field_config['config_fields_dashboard'].',';

        $result = mysqli_query($dbc, $query_check_credentials);

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            // Added Pagination //
            echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
            // Pagination Finish //

            echo '<div id=\'no-more-tables\'><table class="table table-bordered">';
            echo '<tr class="hidden-xs hidden-sm">';
            if (strpos($config_fields_dashboard, ','."Estimate#".',') !== FALSE) {
            echo '<th>Bid #</th>';
            }
            if (strpos($config_fields_dashboard, ','."Business".',') !== FALSE) {
            echo '<th>Business</th>';
            }
            if (strpos($config_fields_dashboard, ','."Estimate Name".',') !== FALSE) {
            echo '<th>Bid Name<br>Created Date</th>';
            }
            if (strpos($config_fields_dashboard, ','."Total Cost".',') !== FALSE) {
            echo '<th>Total Cost</th>';
            }
            if (strpos($config_fields_dashboard, ','."Notes".',') !== FALSE) {
            echo '<th>Notes</th>';
            }
            if (strpos($config_fields_dashboard, ','."Financial Summary".',') !== FALSE) {
            echo '<th>Financial Summary</th>';
            }
            if (strpos($config_fields_dashboard, ','."Review Quote".',') !== FALSE) {
            echo '<th>Review Quote</th>';
            }
            if (strpos($config_fields_dashboard, ','."Status".',') !== FALSE) {
            echo '<th>Status</th>';
            }
            if (strpos($config_fields_dashboard, ','."History".',') !== FALSE) {
            echo '<th>History</th>';
            }
            echo '</tr>';
        } else {
            echo "<h2>No Record Found.</h2>";
        }
        while($row = mysqli_fetch_array( $result )) {
            echo '<tr>';

            if (strpos($config_fields_dashboard, ','."Estimate#".',') !== FALSE) {
            echo '<td data-title="Estimate #"">' . $row['estimateid'] . '</td>';
            }

            $clientid = $row['clientid'];
            $businessid = $row['businessid'];
            if($businessid ==  '' || $businessid ==  0) {
                $businessid = get_contact($dbc, $clientid, 'businessid');
            }

            if (strpos($config_fields_dashboard, ','."Business".',') !== FALSE) {
            echo '<td data-title="Client">' . get_contact($dbc, $businessid, 'name').'<br>'.get_contact($dbc, $clientid, 'first_name').' '.get_contact($dbc, $clientid, 'last_name') . '</td>';
            }

            //echo '<td data-title="Serial Number">' . decryptIt($row['name']) .'</td>';
            if (strpos($config_fields_dashboard, ','."Estimate Name".',') !== FALSE) {
            echo '<td data-title="Estimate Details">' . $row['estimate_name'] . '<br>'.$row['start_date']. '</td>';
            }

            if (strpos($config_fields_dashboard, ','."Total Cost".',') !== FALSE) {
            echo '<td data-title="Total Cost">$' . $row['total_price'] . '</td>';
            }

            if (strpos($config_fields_dashboard, ','."Notes".',') !== FALSE) {
            echo '<td data-title="Notes"><a href=\'add_estimate.php?estimateid='.$row['estimateid'].'&note=add_view\'>Add/View</a></td>';
            }

            if (strpos($config_fields_dashboard, ','."Financial Summary".',') !== FALSE) {
            echo '<td data-title="Financial Summary">';
            echo '<a href=\'edit_estimate.php?estimateid='.$row['estimateid'].'&type=profit_loss\'>View</a>';
            echo '</td>';
            }

            //echo '<td data-title="Function">';
            //echo '<a href=\'edit_estimate.php?estimateid='.$row['estimateid'].'&type=budget\'>Budget</a>';
            //echo '</td>';

            if (strpos($config_fields_dashboard, ','."Review Quote".',') !== FALSE) {
            echo '<td data-title="Review Quote">';
            echo '<a href=\'quote_front_page.php?estimateid='.$row['estimateid'].'\'>Review</a>';
            //echo '<a href=\'edit_estimate.php?estimateid='.$row['estimateid'].'&type=summary\'>Summary</a>';
            echo '</td>';
            }

            echo '<input type="hidden" name="estimateid_dashboard" value="'.$row['estimateid'].'" />';

            if (strpos($config_fields_dashboard, ','."Status".',') !== FALSE) {
            echo '<td data-title="Status">';
            if(vuaed_visible_function($dbc, 'estimate') == 1) {
                echo '<a href=\'add_estimate.php?estimateid='.$row['estimateid'].'\'>Edit</a> | ';
            }

            echo '<a href=\'estimate.php?estimateid='.$row['estimateid'].'&type=approve\'>Approve</a> | <a href=\'estimate.php?estimateid='.$row['estimateid'].'&type=reject\' onclick="return confirm(\'Are you sure you wish to reject this estimate?\')">Reject</a>';
            echo '</td>';
            }

		    //echo '<td data-title="Inspection PDF"><a href="download/estimate_'.$row['estimateid'].'.pdf" target="_blank" ><img src="'.WEBSITE_URL.'/img/pdf.png" width="16" height="16" border="0" alt="View">View</a></td>';

			//echo '<td data-title="Function"><a href=\'estimate.php?estimateid='.$row['estimateid'].'&status=Approve\'>Approve</a> | <a href=\'estimate.php?estimateid='.$row['estimateid'].'&status=Denied\'>Denied</a></td>';

            if (strpos($config_fields_dashboard, ','."History".',') !== FALSE) {
            echo '<td data-title="History">';
            echo '<span class="iframe_open" id="'.$row['estimateid'].'" style="cursor:pointer">View All</span></td>';
			//echo '<a href="#"  onclick="wwindow.open(\'estimate_history.php?estimateid='.$row['estimateid'].'\', \'newwindow\', \'width=500, height=600\'); return false;">View All</a></td>';
            }

            echo "</tr>";
        }

        echo '</table>';

        // Added Pagination //
        echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
        // Pagination Finish //

        if(vuaed_visible_function($dbc, 'estimate') == 1) {
	    echo '<a href="add_estimate.php" class="btn brand-btn pull-right">Add Bid</a>';
        }

        ?>
		</div>
        </form>
	</div>
</div>

<?php include ('../footer.php'); ?>
