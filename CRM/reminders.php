<?php
/*
Referrals : Send Patient a Promotion if reffers someone.
*/
include ('../include.php');
checkAuthorised('crm');
include_once('../tcpdf/tcpdf.php');

error_reporting(0);

$from_date = '';
$to_date = '';
$gender = '';
$sr = '';
if (isset($_POST['search_client_submit'])) {
	$gender = filter_var($_POST['search_gender'],FILTER_SANITIZE_STRING);
	$from_date = filter_var($_POST['search_from_lastvisit'],FILTER_SANITIZE_STRING);
	$to_date = filter_var($_POST['search_to_lastvisit'],FILTER_SANITIZE_STRING);
	$sr = filter_var($_POST['search_sr'],FILTER_SANITIZE_STRING);
}
if (isset($_POST['display_all_client'])) {
	$from_date = '';
	$to_date = '';
	$gender = '';
	$sr = '';
}

/* Pagination Counting */
$rowsPerPage = 25;
$pageNum = 1;

if(isset($_GET['page'])) {
	$pageNum = $_GET['page'];
}

$offset = ($pageNum - 1) * $rowsPerPage;

$filter = '';
if($from_date != '') {
	$from_time = $from_date . ' 00:00:00';
	if($to_date != '') {
		$to_time = $to_date . ' 00:00:00';
	}
	else {
		$to_time = date("Y-m-d H:m:s");	
	}

	$filter .= "AND last_login BETWEEN '$from_time' AND '$to_time'";
}

if($gender != '') {
	$filter .= "AND gender='$gender'";
}

/*if($sr != '') {
	$sr .= "AND gender='$gender'";
}*/

$query_check_credentials = "SELECT * FROM contacts where last_login is not null AND last_name<>'' AND deleted=0 AND status = 1 $filter order by last_name desc";

$num_rows = mysqli_num_rows(mysqli_query($dbc, $query_check_credentials));
$result = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, $query_check_credentials), MYSQLI_ASSOC));

if (isset($_POST['print'])) {
	class MYPDF extends TCPDF {

        //Page header
         public function Header() {
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $this->writeHTMLCell(0, 0, 0 , 5, $footer_text, 0, 0, false, "R", true);
        }

        // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $footer_text = 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
            $this->SetY(-30);
            $this->setCellHeightRatio(0.7);
            $this->SetFont('helvetica', '', 9);
            $this->writeHTMLCell(0, 0, '', '', $footer_text, 0, 0, false, "C", true);
        }
    }

    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, false, false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));

    if(PDF_LOGO != '') {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
    } else {
        $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
    }
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, 30);

    $pdf->AddPage();
    $pdf->setCellHeightRatio(1.6);
    $pdf->SetFont('helvetica', '', 9);
	
	if($num_rows > 0) {
		$count = 0;
		$start = 0;
		$html = '<table style="width:100%">';
		foreach($result as $row) {
			if($start == 0) {
				$html .= '<tr>';
				$start = 1;
			}
			
			if($count != 0 && $count % 3 == 0) {
				$html .= '</tr>';
				$html .= '<tr>';
			}

			$html .= '<td>' . get_contact($dbc, $row, 'first_name') . ' ' . get_contact($dbc, $row, 'last_name') . '<br>' .
									get_contact($dbc, $row, 'email_address') . '<br>' . 
										get_contact($dbc, $row, 'mailing_address') . '<br>' . 
						'</td>';
			
			

			$count++;
		}

		$html .= '</tr></table>';
	}

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('download/contacts_print.pdf', 'F');
    echo '<script type="text/javascript" language="Javascript">
    window.open("download/contacts_print.pdf", "fullscreen=yes");
    </script>';

}

if (isset($_POST['export'])) {
	if($num_rows > 0) {
		// output headers so that the file is downloaded rather than displayed
		ob_end_clean();
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=reminders_contact.csv');
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		// output the column headings
		fputcsv($output, array('First Name', 'Last Name', 'Email', 'Cell Phone', 'Last Visit', 'Gender','Mailing Address'));
		foreach($result as $row)
		{
			$first_name = get_contact($dbc, $row, 'first_name');
			$last_name = get_contact($dbc, $row, 'last_name');
			$email = get_contact($dbc, $row, 'email_address');
			$cell_phone = get_contact($dbc, $row, 'cell_phone');
			$last_login = get_contact($dbc, $row, 'last_login');
			$gender = get_contact($dbc, $row, 'gender');
			$postal_code = get_contact($dbc, $row, 'postal_code');
			$mailing_address = get_contact($dbc, $row, 'mailing_address');
			fputcsv($output, array($first_name, $last_name, $email, $cell_phone, $last_login, $gender, $mailing_address));	
		}

		fclose($output);
		exit();
		
	}
}
?>
<script type="text/javascript">
$(document).ready(function(){
$('.iframe_open').click(function(){
		  if($(this).hasClass("adder")) {
		    $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/CRM/add_referral.php');
		    $('.iframe_title').text('Add New Referral');
		  } else {
			var id = $(this).attr('id');
		    $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Booking/add_booking.php?referralid='+id);
		    $('.iframe_title').text('Add Booking');
		  }
			$('.iframe_holder').show(1000);
			$('.hide_on_iframe').hide(1000);
	});

	$('.close_iframer').click(function(){
		var result = confirm("Are you sure you want to close this window?");
		if (result) {
			$('.iframe_holder').hide(1000);
			$('.hide_on_iframe').show(1000);
			location.reload();
		}
	});
});
function followupDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=referral_followup&id="+arr[1]+'&name='+action,
		dataType: "html",   //expect html to be returned
		success: function(response){
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
    <div class='iframe_holder' style='display:none;'>

		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right;top:58px; cursor:pointer;'>
		<span class='iframe_title' style='font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style='width: 100%;' height="1000px; border:0;" src=""></iframe>
    </div>
	<div class="row hide_on_iframe">
        <div class="col-md-12">

        <div class="row">
            <div class="col-xs-11"><h1 class="single-pad-bottom">CRM Dashboard</h1></div>
            <div class="col-xs-1 double-gap-top"><?php
                echo '<a href="config_crm.php?category=referral" class="mobile-block pull-right"><img style="width:45px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me" /></a>'; ?>
            </div>
        </div>

        <?php $value_config = ','.get_config($dbc, 'crm_dashboard').','; ?>

        <div class="tab-container gap-top"><?php
            if (strpos($value_config, ',Referrals,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'referrals' ) === true ) { ?>
                        <a href='referral.php'><button type="button" class="btn brand-btn mobile-block">Referrals</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Referrals</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Recommendations,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Track the Referrals you receive."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'recommendations' ) === true ) { ?>
                        <a href='recommendations.php'><button type="button" class="btn brand-btn mobile-block">Recommendations</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Recommendations</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Surveys,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Send out Surveys to customers."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'surveys' ) === true ) { ?>
                        <a href='survey.php'><button type="button" class="btn brand-btn mobile-block">Surveys</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Surveys</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Testimonials,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Record and track Testimonials."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'testimonials' ) === true ) { ?>
                        <a href='testimonials.php'><button type="button" class="btn brand-btn mobile-block">Testimonials</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Testimonials</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Birthday & Promotion,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Track Birthdays and General Promotions."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'birthdays_promotions' ) === true ) { ?>
                        <a href='birthday_promo.php'><button type="button" class="btn brand-btn mobile-block">Birthdays &amp; Promotions</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Birthdays &amp; Promotions</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',6 Month Follow Up Email,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Check in on/follow up with customers after 6 months to see how they are doing and potentially book future appointments."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'follow_up_email' ) === true ) { ?>
                        <a href='6month_follow_up_email.php'><button type="button" class="btn brand-btn mobile-block gap_left">6 Month Follow Up Email</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">6 Month Follow Up Email</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Newsletter,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Newsletter"><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'newsletter' ) === true ) { ?>
                        <a href='newsletter.php'><button type="button" class="btn brand-btn mobile-block gap_left">Newsletter</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Newsletter</button></a><?php
                    } ?>
                </div><?php
            }
            if (strpos($value_config, ',Reminders,') !== false) { ?>
                <div class="tab pull-left">
                    <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Reminders"><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span><?php
                    if ( check_subtab_persmission( $dbc, 'crm', ROLE, 'reminders' ) === true ) { ?>
                        <a href='reminders.php'><button type="button" class="btn brand-btn mobile-block gap_left active_tab">Reminders</button></a><?php
                    } else { ?>
                        <button type="button" class="btn disabled-btn mobile-block">Reminders</button></a><?php
                    } ?>
                </div><?php
            } ?>

            <!--<?php if (strpos($value_config, ',Confirmation Email,') !== false) { ?>
            <span>
                <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Send and track confirmation emails one month ahead."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a href='confirmation_email.php'><button type="button" class="btn brand-btn mobile-block">Confirmation Email</button></a>
            </span>
            <?php } ?>

            <?php if (strpos($value_config, ',Reminder Email,') !== false) { ?>
            <span>
                <span class="popover-examples list-inline" style="margin:0;"><a data-toggle="tooltip" data-placement="top" title="Send and track appointment reminder emails."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                <a href='reminder_email.php'><button type="button" class="btn brand-btn mobile-block">Reminder Email</button></a>
            </span>
            <?php } ?>-->
            <div class="clearfix"></div>
        </div><!-- .tab-container -->

        <form name="form_clients" method="post" action="" class="form-inline" role="form">
        <div class="notice double-gap-bottom popover-examples">
        <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
        <div class="col-sm-11"><span class="notice-name">NOTE:</span>
        Design what you want your reminder email to look like and send it out by email.</div>
        <div class="clearfix"></div>
        </div>
		<div>
			<label for="gender" class="col-sm-2 control-label">Search By Gender:</label>
			<div class="col-sm-3">
				<select class="form-control"  name="search_gender">
				<?php if(isset($_POST['search_client_submit']) && $_POST['search_gender'] != '') { ?>
						<?php if($_POST['search_gender'] == 'male'): ?>
							<option selected value="male">Male</option>
						<?php else: ?>
							<option value="male">Male</option>
						<?php endif; ?>
						<?php if($_POST['search_gender'] == 'female'): ?>
							<option selected value="female">Female</option>
						<?php else: ?>
							<option value="female">Female</option>
						<?php endif; ?>
						<?php if($_POST['search_gender'] == 'other'): ?>
							<option selected value="other">Other</option>
						<?php else: ?>
							<option value="other">Other</option>
						<?php endif; ?>
				<?php } else { ?>
						<option value="">Select a Gender</option>
						<option value="male">Male</option>
						<option value="female">Female</option>
						<option value="other">Other</option>
				<?php } ?>				
			</select>
			</div>
			<span class="col-sm-1"></span>
			<label for="service_received" class="col-sm-2 control-label">Search By Service Received:</label>
			<div class="col-sm-4">
				<?php if(isset($_POST['search_client_submit'])) { ?>
					<input type="text" name="search_sr" value="<?php echo $_POST['search_sr']?>" class="form-control">
				<?php } else { ?>
					<input type="text" name="search_sr" class="form-control">
				<?php } ?>
			</div>
		</div>
		<br><br><br>
		<div>
			<label for="last_visit" class="col-sm-2 control-label">Search By Last Visit:</label>
			<label for="last_visit_from" class="col-sm-1 control-label">From:</label>
			<div class="col-sm-2">
				<?php if(isset($_POST['search_client_submit'])) { ?>
					<input type="text" name="search_from_lastvisit" class="datepicker form-control" value="<?php echo $_POST['search_from_lastvisit']?>">
				<?php } else { ?>
					<input type="date" name="search_from_lastvisit" value="" class="datepicker form-control">
				<?php } ?>
			</div>
			<span class="col-sm-1"></span>
			<label for="postal_code" class="col-sm-2 control-label">Postal Code:</label>
			<div class="col-sm-4">
				<?php if(isset($_POST['search_client_submit'])) { ?>
					<input type="text" name="search_postal_code" class="form-control" value="<?php echo $_POST['search_postal_code']?>">
				<?php } else { ?>
					<input type="text" name="search_postal_code" value="" class="form-control">
				<?php } ?>
			</div>

		</div>
		<br><br>
		<div>
			<span class="col-sm-2"></span>
			<label for="last_visit_to" class="col-sm-1 control-label">To:</label>
			<div class="col-sm-2">
				<?php if(isset($_POST['search_client_submit'])) { ?>
					<input type="text" name="search_to_lastvisit" class="datepicker form-control" value="<?php echo $_POST['search_to_lastvisit']?>">
				<?php } else { ?>
					<input type="date" name="search_to_lastvisit" value="" class="datepicker form-control">
				<?php } ?>
			</div>
		</div>
			<br><br><br>
			<center>
			<button type="submit" name="search_client_submit" value="Search" class="btn brand-btn mobile-block">Search</button>
			<button type="submit" name="display_all_client" value="Display All" class="btn brand-btn mobile-block">Display All</button>
			</center>
            <div id="no-more-tables">
            <button type="submit" name="export" value="export" class="btn brand-btn mobile-block pull-right">Export</button>
			
			<button style="margin-right:10px" type="submit" name="print" value="print" class="btn brand-btn mobile-block pull-right">Print</button>
			</form>
			<?php
			$query_check_credentials = "SELECT * FROM contacts where last_login is not null AND last_name<>'' AND deleted=0 AND status = 1 $filter order by last_name desc LIMIT $offset, $rowsPerPage";
			$pageQuery = "SELECT count(*) as numrows FROM contacts where last_login IS NOT NULL AND last_name<>'' AND deleted=0 AND status = 1 $filter";

            $num_rows = mysqli_num_rows(mysqli_query($dbc, $query_check_credentials));
			$result = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, $query_check_credentials), MYSQLI_ASSOC));
            if($num_rows > 0) {
                // Added Pagination //
                echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
                // Pagination Finish //

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Cell Phone</th>
					<th>Last Visit</th>
					<th>Gender</th>
					<th>Postal Code</th>
                    ";
                echo "</tr>";
            } else {
            	echo "<h2>No Record Found.</h2>";
            }

            foreach($result as $row)
            {
                echo "<tr>";
                echo '<td data-title="Code">' . get_contact($dbc, $row, 'first_name') . '</td>';
                echo '<td data-title="Code">' . get_contact($dbc, $row, 'last_name'). '</td>';
                echo '<td data-title="Code">' . get_contact($dbc, $row, 'email_address') . '</td>';
                echo '<td data-title="Code">' . get_contact($dbc, $row, 'cell_phone') . '</td>';
				echo '<td data-title="Code">' . get_contact($dbc, $row, 'last_login') . '</td>';
				echo '<td data-title="Code">' . get_contact($dbc, $row, 'gender') . '</td>';
				echo '<td data-title="Code">' . get_contact($dbc, $row, 'postal_code') . '</td>';
                echo "</tr>";
            }

            echo '</table></div>';

            // Added Pagination //
            echo display_pagination($dbc, $pageQuery, $pageNum, $rowsPerPage);
            // Pagination Finish //

            ?>
        </div>
    </div>
</div>
<?php include ('../footer.php'); ?>
