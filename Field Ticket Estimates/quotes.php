<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('field_ticket_estimates');
error_reporting(0);

if (isset($_POST['quote_email'])) {
    $estimateid = $_POST['quote_email'];

    $estimate_name = get_estimate($dbc, $estimateid, 'estimate_name');
    $contactid = $_SESSION['contactid'];
    echo insert_day_overview($dbc, $contactid, 'Cost Estimate', date('Y-m-d'), '', 'Sent Cost Estimate '.$estimate_name.' To Client');

    $clientid = get_estimate($dbc, $estimateid, 'clientid');
    $clientname = get_client($dbc, $clientid);
    $email = get_email($dbc, $clientid);
    // $email = 'dayanapatel@freshfocusmedia.com';

    $promo = html_entity_decode(get_config($dbc, 'send_quote_client_body'));
    $email_body = str_replace("[Client Name]", $clientname, $promo);

    $subject = get_config($dbc, 'send_quote_client_subject');
    $attachment = '';
    $attachment = WEBSITE_URL.'/Field Ticket Estimates/download/quote_'.$estimateid.'.pdf';

    send_email('', $email, '', '', $subject, $email_body, $attachment);

    $history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Sent Cost Estimate on '.date('Y-m-d H:i:s').'<br>';

    $start_date = date('Y-m-d');
    $query_update_report = "UPDATE `bid` SET `status` = 'Sent To Client', `history` = CONCAT(history,'$history'), `quote_send_date` = '$start_date' WHERE `estimateid` = '$estimateid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);

    echo '<script type="text/javascript"> alert("Sent to Client."); window.location.replace("quotes.php"); </script>';
}
?>
<script type="text/javascript">
$(document).on('change', 'select[name="status[]"]', function() { selectStatus(this); });
function followupDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    var fname = $("#fname").val();
    var lname = $("#lname").val();
    var contactid = $("#session_contactid").val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "estimate_ajax_all.php?fill=quote_followup&id="+arr[1]+'&name='+action+'&fname='+fname+'&lname='+lname+'&contactid='+contactid,
		dataType: "html",   //expect html to be returned
		success: function(response){
			location.reload();
		}
	});
}
function selectStatus(sel) {
	var status = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    var fname = $("#fname").val();
    var lname = $("#lname").val();
    var contactid = $("#session_contactid").val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "estimate_ajax_all.php?fill=quote_status&estimateid="+arr[1]+'&status='+status+'&fname='+fname+'&lname='+lname+'&contactid='+contactid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            if(status == 'Approved Quote') {
                alert("Cost Estimate Approved and move to Project.");
                window.location.replace("<?php echo WEBSITE_URL;?>/Project/project.php?type=Pending");
            } else if(status == 'Move To Estimate') {
                alert("Cost Estimate Move back to Bid.");
                window.location.replace("<?php echo WEBSITE_URL;?>/Field Ticket Estimates/estimate.php");
            } else {
			    location.reload();
            }
		}
	});
}

$(document).ready(function() {

$('.iframe_open').click(function(){
		var id = $(this).attr('id');
	   $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Field Ticket Estimates/estimate_history.php?estimateid='+id);
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

        <h1>Cost Estimate
        <?php
            //echo '<a href="field_config_quote.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?></h1>
        
        <?php if ( check_subtab_persmission($dbc, 'field_ticket_estimates', ROLE, 'bid') === TRUE ) { ?>
            <a href="estimate.php"><button type="button" class="btn brand-btn mobile-block">Bid</button></a>&nbsp;&nbsp;
        <?php } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Bid</button></a>&nbsp;&nbsp;
        <?php } ?>
        
        <?php if ( check_subtab_persmission($dbc, 'field_ticket_estimates', ROLE, 'cost_estimate') === TRUE ) { ?>
            <a href="quotes.php"><button type="button" class="btn brand-btn mobile-block active_tab">Cost Estimate</button></a>&nbsp;&nbsp;
        <?php } else { ?>
            <button type="button" class="btn disabled-btn mobile-block">Cost Estimate</button></a>&nbsp;&nbsp;
        <?php } ?>

        <br><br>

        <span class="pull-right">
        <img src="../img/block/blue.png" width="32" height="32" border="0" alt="">&nbsp;Follow Up Today
        <img src="../img/block/red.png" width="32" height="32" border="0" alt=""> Past Due&nbsp;&nbsp;
        </span>
        <input type="hidden" id="fname" value="<?php echo decryptIt($_SESSION['first_name']); ?>" />
        <input type="hidden" id="lname" value="<?php echo decryptIt($_SESSION['last_name']); ?>" />
        <input type="hidden" id="session_contactid" value="<?php echo $_SESSION['contactid']; ?>" />

        <?php
        if(!empty($_GET['quoteid'])) {
            $quoteid = $_GET['quoteid'];
            $query_check_credentials = "SELECT * FROM bid WHERE estimateid = '$quoteid'";
        } elseif(!empty($_GET['businessid'])) {
            $businessid = $_GET['businessid'];
            $query_check_credentials = "SELECT r.*, c.name FROM bid r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND r.businessid = '$businessid' AND (r.status!='Saved' AND r.status!='Submitted' AND r.status!='Approved Quote')";
        } else {
            $query_check_credentials = "SELECT r.*, c.name FROM bid r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND (r.status!='Saved' AND r.status!='Submitted' AND r.status!='Approved Quote') ORDER BY estimateid DESC";
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $base_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT config_fields_quote_dashboard FROM field_config_bid WHERE `fieldconfigestimateid` = 1"));
        $config_fields_quote_dashboard = ','.$base_field_config['config_fields_quote_dashboard'].',';

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo '<div id="no-more-tables"><table class="table table-bordered">';
            echo '<tr class="hidden-xs hidden-sm">';
            if (strpos($config_fields_quote_dashboard, ','."Quote#".',') !== FALSE) {
            echo '<th>Cost Estimate #</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Client".',') !== FALSE) {
            echo '<th>Client</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Quote Name".',') !== FALSE) {
            echo '<th>Cost Estimate Name</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Total Cost".',') !== FALSE) {
            echo '<th>Total Cost</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Quote".',') !== FALSE) {
            echo '<th>Cost Estimate</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Follow up Date".',') !== FALSE) {
            echo '<th>Follow Up Date</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Status".',') !== FALSE) {
            echo '<th>Status</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Send To Client".',') !== FALSE) {
            echo '<th>Send To Client</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."History".',') !== FALSE) {
            echo '<th>History</th>';
            }
            echo '</tr>';
        } else {
            echo "<h2>No Record Found.</h2>";
        }
        while($row = mysqli_fetch_array( $result )) {

            $back = '';
            if($row['follow_up_date'] == date('Y-m-d')) {
                $back = 'style="background-color: rgba(0,0,255,0.7);"';
            }
            if(($row['follow_up_date'] < date('Y-m-d')) && ($row['follow_up_date'] != '')) {
                $back = 'style="background-color: rgba(255,0,0,0.6);"';
            }
            echo '<tr '.$back.'>';

            if (strpos($config_fields_quote_dashboard, ','."Quote#".',') !== FALSE) {
            echo '<td data-title="Quote #">' . $row['estimateid'] . '</td>';
            }

            $clientid = $row['clientid'];
            $businessid = $row['businessid'];
            if($businessid ==  '' || $businessid ==  0) {
                $businessid = get_contact($dbc, $clientid, 'businessid');
            }

            if (strpos($config_fields_quote_dashboard, ','."Client".',') !== FALSE) {
            echo '<td data-title="Client">' . get_contact($dbc, $businessid, 'name').'<br>'.get_contact($dbc, $clientid, 'first_name').' '.get_contact($dbc, $clientid, 'last_name') . '</td>';
            }

            //echo '<td data-title="Serial Number">' . decryptIt($row['name']) . '</td>';
            if (strpos($config_fields_quote_dashboard, ','."Quote Name".',') !== FALSE) {
            echo '<td data-title="Quote Name">' . $row['estimate_name'] . '</td>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Total Cost".',') !== FALSE) {
            echo '<td data-title="Total Cost">$' . $row['total_price'] . '</td>';
            }

            if (strpos($config_fields_quote_dashboard, ','."Quote".',') !== FALSE) {
		    echo '<td data-title="Quote"><a href="'.WEBSITE_URL.'/Field Ticket Estimates/download/quote_'.$row['estimateid'].'.pdf" target="_blank" ><img src="'.WEBSITE_URL.'/img/pdf.png" width="16" height="16" border="0" alt="View">View</a></td>';
            }

            if (strpos($config_fields_quote_dashboard, ','."Follow up Date".',') !== FALSE) {
            echo '<td data-title="Follow Up Date"><input name="follow_up_date" type="text" id="followupdate_'.$row['estimateid'].'"  onchange="followupDate(this)" class="datepicker" value="'.$row['follow_up_date'].'"></td>';
            }

            if (strpos($config_fields_quote_dashboard, ','."Status".',') !== FALSE) {
            ?>
            <td data-title="Status">
            <div class="form-group">
                <select id="status_<?php echo $row['estimateid']; ?>"  data-placeholder="Choose a Status..." name="status[]" class="chosen-select-deselect form-control input-sm">
                  <option value=""></option>
                  <option value="Pending Quote" <?php if($row['status'] == "Pending Quote") { echo " selected"; } ?> >Pending</option>
                  <option value="Under Review" <?php if($row['status'] == "Under Review")  { echo " selected"; } ?> >Under Review</option>
                  <option value="Move To Estimate" <?php if($row['status'] == "Move To Estimate")  { echo " selected"; } ?> >Move To Bid</option>
                  <option value="Sent To Client" <?php if($row['status'] == "Sent To Client")  { echo " selected"; } ?> >Sent To Client</option>
                  <option value="Approved Quote" <?php if($row['status'] == "Approved Quote")  { echo " selected"; } ?> >Approved</option>
                  <option value="Delete" <?php if($row['status'] == "Delete")  { echo " selected"; } ?> >Deleted</option>
                </select>
              </div>
            </div>
            <?php
            if($row['status'] == "Sent To Client") {
                echo $row['quote_send_date'];
            }
            ?>
            </td>
            <?php
            }

			//echo '<td data-title="Function"><a href=\'estimate.php?estimateid='.$row['estimateid'].'&status=Approve\'>Approve</a> | <a href=\'estimate.php?estimateid='.$row['estimateid'].'&status=Denied\'>Denied</a></td>';

            if (strpos($config_fields_quote_dashboard, ','."Send To Client".',') !== FALSE) {
            echo '<td data-title="Send To Client"><button type="submit" name="quote_email" value="'.$row['estimateid'].'" class="btn brand-btn">Send</button></td>';
            }

            if (strpos($config_fields_quote_dashboard, ','."History".',') !== FALSE) {
            echo '<td data-title="History">';
			echo '<span class="iframe_open" id="'.$row['estimateid'].'">View All</span></td>';
            }

            echo "</tr>";
        }

        echo '</table></div>';
        ?>
        </form>
	</div>
</div>

<?php include ('../footer.php'); ?>