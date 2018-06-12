<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('quote');
error_reporting(0);

if (isset($_POST['quote_email'])) {
    $estimateid = $_POST['quote_email'];

    $estimate_name = get_estimate($dbc, $estimateid, 'estimate_name');
    $contactid = $_SESSION['contactid'];
    echo insert_day_overview($dbc, $contactid, 'Quote', date('Y-m-d'), '', 'Sent Quote '.$estimate_name.' To Client');

    $clientid = get_estimate($dbc, $estimateid, 'clientid');
    $clientname = get_client($dbc, $clientid);
    $email = get_email($dbc, $clientid);

    $promo = html_entity_decode(get_config($dbc, 'send_quote_client_body'));
    $email_body = str_replace("[Client Name]", $clientname, $promo);

    $subject = get_config($dbc, 'send_quote_client_subject');
    $attachment = '';
    $attachment = WEBSITE_URL.'/Estimate/download/quote_'.$estimateid.'.pdf';

    send_email('', $email, '', '', $subject, $email_body, $attachment);

    $history = decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']).' Sent Quote on '.date('Y-m-d H:i:s').'<br>';

    $start_date = date('Y-m-d');
    $query_update_report = "UPDATE `estimate` SET `status` = 'Sent To Client', `history` = CONCAT(history,'$history'), `quote_send_date` = '$start_date' WHERE `estimateid` = '$estimateid'";
    $result_update_report = mysqli_query($dbc, $query_update_report);

    echo '<script type="text/javascript"> alert("Sent to Client."); window.location.replace("'.($current_file == 'cost_quote.php' ? 'cost_quote.php' : 'quotes.php').'"); </script>';
}

$current_file = basename($_SERVER['PHP_SELF']);
?>
<script type="text/javascript">
function followupDate(sel) {
	var action = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
    var fname = $("#fname").val();
    var lname = $("#lname").val();
    var contactid = $("#session_contactid").val();

	$.ajax({    //create an ajax request to load_page.php
		type: "GET",
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=quote_followup&id="+arr[1]+'&name='+action+'&fname='+fname+'&lname='+lname+'&contactid='+contactid,
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
		url: "<?php echo WEBSITE_URL;?>/ajax_all.php?fill=quote_status&estimateid="+arr[1]+'&status='+status+'&fname='+fname+'&lname='+lname+'&contactid='+contactid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            if(status == 'Approved Quote') {
                alert("Quote Approved and moved to Project.");
                window.location.replace("<?php echo WEBSITE_URL;?>/Project/project.php?type=Pending");
            } else if(status == 'Move To Estimate') {
                alert("Quote Move back to Estimate.");
                window.location.replace("<?php echo WEBSITE_URL;?>/Estimate/estimate.php");
            } else {
			    location.reload();
            }
		}
	});
}

$(document).ready(function() {
	$('.iframe_open').click(function(){
			var id = $(this).attr('id');
		   $('#iframe_instead_of_window').attr('src', '<?php echo WEBSITE_URL; ?>/Estimate/estimate_history.php?estimateid='+id);
		   $('.iframe_title').text('Estimate History');
		   $('.iframe_holder').show();
		   $('.hide_on_iframe').hide();
	});

	$('.close_iframer').click(function(){
			$('.iframe_holder').hide();
			$('.hide_on_iframe').show();
	});

	$('iframe').load(function() {
		this.contentWindow.document.body.style.overflow = 'hidden';
		this.contentWindow.document.body.style.minHeight = '0';
		this.contentWindow.document.body.style.paddingBottom = '5em';
		this.style.height = (this.contentWindow.document.body.offsetHeight + 80) + 'px';
	});
});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class='iframe_holder' style='display:none;'>
		<img src='<?php echo WEBSITE_URL; ?>/img/icons/close.png' class='close_iframer' width="45px" style='position:relative; right: 10px; float:right; cursor:pointer;'>
		<span class='iframe_title' style='color:white; font-weight:bold; position: relative; left: 20px; font-size: 30px;'></span>
		<iframe id="iframe_instead_of_window" style="width: 100%; border:0; margin-top:-70px;" src=""></iframe>
	</div>
	<div class="row hide_on_iframe">
        <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

        <h1>Quotes Dashboard
        <?php
            //echo '<a href="field_config_quote.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a><br><br>';
        ?></h1>

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
            $query_check_credentials = "SELECT * FROM estimate WHERE estimateid = '$quoteid'";
        } elseif(!empty($_GET['businessid'])) {
            $businessid = $_GET['businessid'];
            $query_check_credentials = "SELECT r.*, c.name FROM estimate r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND r.businessid = '$businessid' AND (r.status!='Saved' AND r.status!='Submitted' AND r.status!='Approved Quote')";
        } else {
            $query_check_credentials = "SELECT r.*, c.name FROM estimate r, contacts c WHERE r.businessid = c.contactid AND r.deleted = 0 AND (r.status!='Saved' AND r.status!='Submitted' AND r.status!='Approved Quote') ORDER BY estimateid DESC";
        }

        $result = mysqli_query($dbc, $query_check_credentials);

        $base_field_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT config_fields_quote_dashboard FROM field_config_estimate WHERE `fieldconfigestimateid` = 1"));
        $config_fields_quote_dashboard = ','.$base_field_config['config_fields_quote_dashboard'].',';

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0) {
            echo '<div id="no-more-tables"><table class="table table-bordered">';
            echo '<tr class="hidden-xs hidden-sm">';
            if (strpos($config_fields_quote_dashboard, ','."Quote#".',') !== FALSE) {
            echo '<th>Quote #</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Client".',') !== FALSE) {
            echo '<th>Client</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Quote Name".',') !== FALSE) {
            echo '<th>Quote Name</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Total Cost".',') !== FALSE) {
            echo '<th>Total Cost</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Quote".',') !== FALSE) {
            echo '<th>Quote</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Follow up Date".',') !== FALSE) {
            echo '<th>Follow Up Date</th>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Status".',') !== FALSE) {
            echo '<th>
				<span class="popover-examples list-inline" style="margin:15px 2px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Select \'Move To Estimate\' in order to edit in the Estimates tile."><img src="' . WEBSITE_URL . '/img/info-w.png" width="20"></a></span>
				Status
				</th>';
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

            //echo '<td data-title="Serial Number">' . $row['name'] . '</td>';
            if (strpos($config_fields_quote_dashboard, ','."Quote Name".',') !== FALSE) {
            echo '<td data-title="Quote Name">' . $row['estimate_name'] . '</td>';
            }
            if (strpos($config_fields_quote_dashboard, ','."Total Cost".',') !== FALSE) {
            echo '<td data-title="Total Cost">$' . $row['total_price'] . '</td>';
            }

            if (strpos($config_fields_quote_dashboard, ','."Quote".',') !== FALSE) {
		    echo '<td data-title="Quote"><a href="'.WEBSITE_URL.'/Estimate/download/quote_'.$row['estimateid'].'.pdf" target="_blank" ><img src="'.WEBSITE_URL.'/img/pdf.png" width="16" height="16" border="0" alt="View">View</a></td>';
            }

            if (strpos($config_fields_quote_dashboard, ','."Follow up Date".',') !== FALSE) {
            echo '<td data-title="Follow Up Date"><input name="follow_up_date" type="text" id="followupdate_'.$row['estimateid'].'"  onchange="followupDate(this)" class="datepicker" value="'.$row['follow_up_date'].'"></td>';
            }

            if (strpos($config_fields_quote_dashboard, ','."Status".',') !== FALSE) {
            ?>
            <td data-title="Status">
            <div class="form-group">
                <select onchange="selectStatus(this)" id="status_<?php echo $row['estimateid']; ?>"  data-placeholder="Choose a Status..." name="status[]" class="chosen-select-deselect form-control input-sm">
                  <option value=""></option>
                  <option value="Pending Quote" <?php if($row['status'] == "Pending Quote") { echo " selected"; } ?> >Pending</option>
                  <option value="Under Review" <?php if($row['status'] == "Under Review")  { echo " selected"; } ?> >Under Review</option>
                  <option value="Move To Estimate" <?php if($row['status'] == "Move To Estimate")  { echo " selected"; } ?> >Move To Estimate</option>
                  <option value="Sent To Client" <?php if($row['status'] == "Sent To Client")  { echo " selected"; } ?> >Sent To Client</option>
                  <option value="Approved Quote" <?php if($row['status'] == "Approved Quote")  { echo " selected"; } ?> >Approved</option>
                  <option value="Delete" <?php if($row['status'] == "Delete")  { echo " selected"; } ?> >Archived</option>
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