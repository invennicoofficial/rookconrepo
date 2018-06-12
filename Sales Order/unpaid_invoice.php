<?php
error_reporting(0);
/*
Payment/Invoice Listing SEA
*/
include ('../include.php');
include_once('../tcpdf/tcpdf.php');

if (isset($_POST['send_drive_logs'])) {
error_reporting(0);	
	$email_list = $_POST['email_list'];
    if ($email_list !== '' || $_POST['pdf_send'] !== null) {
		
			$emails_arr = explode( ',', $email_list );
			
			foreach( $emails_arr as $email )
			{
				if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL) === false) {
					
				} else {
					 echo '<script type="text/javascript"> alert("One or more of the email addresses you have provided is not a proper email address."); 
							window.location.replace("unpaid_invoice.php"); </script>';
							exit();
				}
			}
		//EMAIL
	$to_email = $email_list;
	
	$to = explode(',', $to_email);
	$subject =SALES_ORDER_NOUN." PDF(s)"; 
	$message = "Please see the attached PDF(s) below.";
	
	 $meeting_attachment = '';
        foreach($_POST['pdf_send'] as $drivinglogid) {
            if($drivinglogid != '') {
                $meeting_attachment .= 'download/invoice_'.$drivinglogid.'.pdf*#FFM#*';
            }
        }
		send_email('', $to, '', '', $subject, $message, $meeting_attachment);
	

    echo '<script type="text/javascript"> alert("PDF(s) sent to '.$email_list.'."); 
	window.location.replace("unpaid_invoice.php"); </script>';
	} else {
	echo '<script type="text/javascript"> alert("Please enter at least 1 email address, or make sure you have selected at least one PDF to send."); 
	window.location.replace("unpaid_invoice.php"); </script>';
	}
}

?><style>.selectbutton {
	cursor: pointer;
	text-decoration: underline;
}
@media (min-width: 801px) {
	.sel2 {
		display:none;
	}
}
	</style>
	<?php

$get_invoice =	mysqli_query($dbc,"SELECT posid FROM  sales_order  WHERE `invoice_date` + INTERVAL 30 DAY < NOW() AND status!='Completed'");
$num_rows = mysqli_num_rows($get_invoice);
if($num_rows > 0) {
    while($row = mysqli_fetch_array( $get_invoice )) {
        $posid = $row['posid'];
		//$query_update_project = "UPDATE `sales_order` SET status = 'Posted Past Due' WHERE `posid` = '$posid'";
		//$result_update_project = mysqli_query($dbc, $query_update_project);
    }
}
?>
<script type="text/javascript">

$(document).ready(function() {
	
	$('.selectall').click(
        function() {
			if($('.selectall').hasClass("deselectall")) {
				$(".selectall").removeClass('deselectall');
				$('.pdf_send').prop('checked', false);
				$(".selectall").text('Select all');
				$('.selectall').prop('title', 'This will select all rows on the current page.');
			} else {
				$(".selectall").addClass('deselectall');
				$('.pdf_send').prop('checked', true);
				$(".selectall").text('Deselect all');
				$('.selectall').prop('title', 'This will deselect all rows on the current page.');
			}
			
		});
	$('.change_id').focusout(function() {
			var val = $(this).val();
			var id = $(this)[0].id;
			$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "pos_ajax_all.php?fill=change_id&val="+val+'&id='+id,
				dataType: "html",   //expect html to be returned
				success: function(response){
				}
			});
		});
});

function changePOSStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "pos_ajax_all.php?fill=POSstatus&name="+arr[1]+'&status='+status,
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
checkAuthorised('sales_order');
?>
<div class="container triple-pad-bottom">
    <div class="row">
		<h1 class="double-pad-bottom">Unpaid Orders
        <?php
        if(config_visible_function($dbc, 'sales_order') == 1) {
            echo '<a href="field_config_pos.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        }
        ?>
        </h1>
		<?php 
		$numodays = '';
		$get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_archive_after_num_days'"));
					if($get_config['configid'] > 0) {
						$get_num_of_days = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT value FROM	general_configuration WHERE	name='sales_order_archive_after_num_days'"));
						$numodays = $get_num_of_days['value'];
						
					}
					
		?>
        <div class='mobile-100-container'><?php
		if(vuaed_visible_function($dbc, 'sales_order') == 1) { ?>
        <a href='add_point_of_sell.php'><button type="button" class="btn brand-btn mobile-block mobile-100" >Create an Order</button></a>
        <?php } ?>
		<a href='pending.php'><button type="button" class="btn brand-btn mobile-block mobile-100 " >Pending Orders</button></a>
		<a href='receiving.php'><button type="button" class="btn brand-btn mobile-block  mobile-100" >Receiving</button></a>
        <a href='unpaid_invoice.php'><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab" >Accounts Payable</button></a>
		<a href='complete.php'><button type="button" class="btn brand-btn mobile-block mobile-100" >Completed <?= SALES_ORDER_TILE ?></button></a>
		</div>

        <form name="invoice_table" method="post" action="" class="form-inline" role="form">

            <div class="single-pad-bottom">
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3">
                <label for="search_site" style='width:100%; text-align:right;'>Search By Any:</label>
				</div>
                <?php
                $invoice_name = '';
				$searchbycustomer = '';
				$searchbyany = '';
                $search = '';
				$searchbytype = '';
                $contactid = '';
                $type = '';
                if (isset($_POST['search_invoice_submit'])) {
					if($_POST['search_invoice'] !== '') {
						$invoice_name = $_POST['search_invoice'];
						$searchbyany = "AND (inv.posid = '$invoice_name' OR c.name = '$invoice_name' OR inv.delivery_type = '$invoice_name' OR inv.total_price LIKE '%" . $invoice_name . "%' OR inv.payment_type LIKE '%" . $invoice_name . "%' OR inv.invoice_date LIKE '%" . $invoice_name . "%' OR inv.status LIKE '%" . $invoice_name . "%' OR inv.comment LIKE '%" . $invoice_name . "%') ";
					}
                    if($_POST['contactid'] != '') {
                       $searchbycustomer = " AND c.name = '".$_POST['contactid']."' ";
                    }
                    if($_POST['type'] != '') {
                       $searchbytype = " AND inv.delivery_type = '".$_POST['type']."' ";
                    }
                    $search = $_POST['search_invoice'];
                    $contactid = $_POST['contactid'];
                    $type = $_POST['type'];
                }
                ?><div class="col-lg-6 col-md-6 col-sm-9 col-xs-9" style='margin-bottom:10px;'>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <input  type="text" name="search_invoice" value="<?php echo $search; ?>" class="form-control">
					</div>
				</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style='margin-bottom:10px;'>
                    <select name="contactid" data-placeholder="Choose Customer..." class="chosen-select-deselect form-control width-me" width="380">
                    <option value=''>Choose a Customer</option>
                        <?php
                        $result = mysqli_query($dbc, "SELECT contactid, name FROM contacts WHERE category='Customer' or category='Business' or category = 'Client' order by name");
                        while($row = mysqli_fetch_assoc($result)) {
                            if ($contactid == decryptIt($row['name'])) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value = '".decryptIt($row['name'])."'>".decryptIt($row['name'])."</option>";
                        }
                       ?>
                    </select>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                    <select style="max-width:260px;" name="type" data-placeholder="Choose Delivery/Shipping Type..." class="chosen-select-deselect form-control width-me" width="380">
                        <option value=''>Choose Delivery/Shipping Type</option>
                        <option <?php if ($type == "Pick-Up") { echo " selected"; } ?>  value="Pick-Up">Pick-Up</option>
                        <option <?php if ($type == "Company Delivery") { echo " selected"; } ?>  value="Company Delivery">Company Delivery</option>
                        <option <?php if ($type == "Drop Ship") { echo " selected"; } ?>  value="Drop Ship">Drop Ship</option>
                        <option <?php if ($type == "Shipping") { echo " selected"; } ?>  value="Shipping">Shipping</option>
                    </select>
					</div>
					<?php
					$starttimesql = '';
					$starttime = '';
					$endtime = '';
                if (isset($_POST['search_invoice_submit'])) {
                    $starttime = $_POST['starttime'];
					
                    $endtime = $_POST['endtime'];
					if(($starttime !== '' && $endtime !== '')){
						$starttimesql = " AND (inv.invoice_date >= '".$starttime."' AND inv.invoice_date <= '".$endtime."') ";
					} else if(($starttime !== '' && $endtime == '')){
						$starttimesql = " AND (inv.invoice_date >= '".$starttime."') ";
					} else if(($starttime == '' && $endtime !== '')){
						$starttimesql = " AND (inv.invoice_date <= '".$endtime."') ";
					}
                }
                ?>
				<div class="clearfix" style='margin:10px;'>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
                    <label for="site_name" class="col-sm-4 control-label" style='text-align:right;width:100%;'>From:</label>
				</div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-9">
                        <input name="starttime" type="text" class="datepicker form-control" value="<?php echo $starttime; ?>"></p>
                    </div>

                <!-- end time -->
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
                    <label for="site_name" class="col-sm-4 control-label" style='text-align:right;width:100%;'>Until:</label>
                    </div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-9">
                        <input name="endtime" type="text" class="datepicker form-control" value="<?php echo $endtime; ?>"></p>
                    </div>
					<div class="col-xs-3"></div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-9">
                <button type="submit" name="search_invoice_submit" value="Search" class="btn brand-btn">Search</button>
				
                <button type="submit" name="display_all_invoice" value="Display All" class="btn brand-btn">Display All</button>
				</div>
				<?php
				$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sales_order_dashboard FROM field_config"));
                $value_config = ','.$get_field_config['sales_order_dashboard'].',';
				if (strpos($value_config, ','."Send to Anyone".',') !== FALSE) { ?>
				<div class="clearfix" style='margin:10px;'>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" style='margin-bottom:10px;'>
				<label for="search_vendor" class="control-label" style='width:100%; text-align:right;'>Emails (Separated by a Comma):</label>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-4 col-xs-8" style='margin-bottom:10px;'>
				<input id='roll-input' type='text'  name='email_list' placeholder='Enter emails here...' class='form-control email_driving_logs'>
				</div>
				<div class="col-lg-1 col-md-3 col-sm-2 col-xs-4" style='margin-bottom:10px;'>
				<button onClick="return empty()" type='submit' name='send_drive_logs' class='btn brand-btn dl_send_butt'>Send PDF(s)</button>
				</div>
				<div class="col-lg-1 col-md-3 col-sm-4 col-xs-4">
				<div class='selectall selectbutton sel2' title='This will select all PDFs on the current page.'>Select All</div>
				</div>
				<div class="clearfix" style='margin:10px;'>
				</div>
				<?php } ?>
				<div id='no-more-tables'>
            <?php
            if (isset($_POST['display_all_invoice'])) {
                $invoice_name = '';
            }
			if (isset($_POST['search_invoice_submit'])) {
                $query_check_credentialss = "SELECT inv.*, c.* FROM sales_order inv,  contacts c WHERE inv.contactid = c.contactid AND inv.deleted = 0 AND inv.status = 'Paying' ".$searchbyany." ".$searchbycustomer." ".$searchbytype." ".$starttimesql." ORDER BY inv.posid DESC";
            } else {
                $query_check_credentialss = "SELECT * FROM sales_order WHERE deleted = 0 AND status = 'Paying' ORDER BY posid DESC";
            }

            // how many rows we have in database
            $queryy = "SELECT COUNT(posid) AS numrows FROM sales_order";

            if($invoice_name == '') {
               // echo '<h1 class="single-pad-bottom">'.display_pagination($dbc, $queryy, $pageNumm, $rowsPerPagee).'</h1>';
            }

            $resultt = mysqli_query($dbc, $query_check_credentialss);

            $num_rowss = mysqli_num_rows($resultt);
            if($num_rowss > 0) {
                

                echo "<table class='table table-bordered'>";
                echo "<tr class='hidden-xs hidden-sm'>";
                    if (strpos($value_config, ','."Invoice #".',') !== FALSE) {
                        echo '<th>Invoice #</th>';
                    }
                    if (strpos($value_config, ','."Invoice Date".',') !== FALSE) {
                        echo '<th>Invoice Date</th>';
                    }
                    if (strpos($value_config, ','."Customer".',') !== FALSE) {
                        echo '<th>Customer</th>';
                    }
                    if (strpos($value_config, ','."Total Price".',') !== FALSE) {
                        echo '<th>Total Price</th>';
                    }
                    echo '<th>Due Date</th>';
                    if (strpos($value_config, ','."Invoice PDF".',') !== FALSE) {
                        echo '<th>Invoice PDF</th>';
                    }
                    if (strpos($value_config, ','."Comment".',') !== FALSE) {
                        echo '<th>Comment</th>';
                    }
                    if (strpos($value_config, ','."Status".',') !== FALSE) {
                        echo '<th>Status</th>';
                    }
					echo '<th>Pay For Items</th>';
					if (strpos($value_config, ','."Send to Anyone".',') !== FALSE) {
                      ?><th>Email PDF<br><div class='selectall selectbutton' title='This will select all PDFs on the current page.'>Select All</div></th><?php
                    }
                echo "</tr>";
            } else{
                echo "<h2>No Record Found.</h2>";
            }

            while($roww = mysqli_fetch_array( $resultt ))
            {
                $style2 = '';
				if($numodays > 0) {
					$cutoffdater = date('Y-m-d', strtotime($roww['invoice_date']. ' + '.$numodays.' days'));
					$date = date('Y/m/d', time());
					if (new DateTime($date) >= new DateTime($cutoffdater)) {
						$posid = $roww['posid'];
						$query_update_employee = "UPDATE `point_of_sell` SET deleted = '1' WHERE posid='$posid'";
						$result_update_employee = mysqli_query($dbc, $query_update_employee);
						$style2 = 'display:none;';
					}
				}
                $style = '';
                if($roww['status'] == 'Posted Past Due') {
                    $style = 'color:green;';
                }
                if($roww['status'] == 'Void') {
                    $style = 'color:red;';
                }
                $contactid = $roww['contactid'];
                echo "<tr style='".$style.$style2."'>";
                $customer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT name, first_name, last_name FROM contacts WHERE contactid='$contactid'"));

                if (strpos($value_config, ','."Invoice #".',') !== FALSE) {
                    $new_id = $roww['new_id_number'];
					if($new_id == '' || $new_id == NULL) {
						$new_id = $roww['posid'];
					}
					
                    echo '<td data-title="S.O. #"><input type="text" id="'.$roww['posid'].'" value="'.$new_id.'" class="change_id form-control" style="max-width:130px;"</td>';
                }
                if (strpos($value_config, ','."Invoice Date".',') !== FALSE) {
                    echo '<td data-title="Invoice Date">' . $roww['invoice_date'] . '</td>';
                }
                if (strpos($value_config, ','."Customer".',') !== FALSE) {
                    echo '<td data-title="Customer">' . $customer['name'] . '</td>';
                }
                if (strpos($value_config, ','."Total Price".',') !== FALSE) {
                    echo '<td data-title="Total Price">' . $roww['total_price'] . '</td>';
                }
                echo '<td data-title="Due Date">' . date('Y-m-d', strtotime($roww['invoice_date'] . "+30 days")) . '</td>';
                if (strpos($value_config, ','."Invoice PDF".',') !== FALSE) {
                    echo '<td data-title="Invoice PDF"><a target="_blank" href="download/invoice_'.$roww['posid'].'.pdf">PDF <img src="'.WEBSITE_URL.'/img/pdf.png" title="PDF"></a></td>';
                }
                if (strpos($value_config, ','."Comment".',') !== FALSE) {
                    echo '<td data-title="Comment">' . $roww['comment'] . '</td>';
                }
                if (strpos($value_config, ','."Status".',') !== FALSE) {
                    echo '<td data-title="Status">';
                    ?>
                    <select name="status[]" onchange="changePOSStatus(this)" id="status_<?php echo $roww['posid']; ?>" class="chosen-select-deselect1 form-control" width="380">
                        <option value=""></option>
                        <option value="Pending" <?php if ($roww['status'] == "Pending") { echo " selected"; } ?> >Pending</option>
                        <option value="Receiving" <?php if ($roww['status'] == "Receiving") { echo " selected"; } ?> >Receiving</option>
                        <option value="Paying" <?php if ($roww['status'] == "Paying") { echo " selected"; } ?> >Paying</option>
                        <option value="Completed" <?php if ($roww['status'] == "Completed") { echo " selected"; } ?> >Complete</option>
                    </select>
                <?php
                    echo '</td>';
                    }
				 echo '<td data-title="Pay For Items"><a href="receive_pay.php?posid='.$roww['posid'].'&type=pay">Pay For Items</a>';
                 echo '</td>';
				if (strpos($value_config, ','."Send to Anyone".',') !== FALSE) {
				echo '<td data-title="Email PDF">';
					?><input style="height: 25px; width: 25px;" type='checkbox' name='pdf_send[]' class='pdf_send' value='<?php echo $roww['posid']; ?>'>
					<?php
                    //echo '<a href=\'driving_log_14days.php?email=send&drivinglogid='.$row['drivinglogid'].'\'>Email</a>';
                    echo '</td>';
				}
                echo "</tr>";
            }

            echo '</table></div></div>';

            if($invoice_name == '') {
                //echo display_pagination($dbc, $queryy, $pageNumm, $rowsPerPagee);
            }

            ?>
			
        </form>

	</div>
</div>
<?php include ('../footer.php'); ?>
