<div class="notice double-gap-bottom popover-examples">
	<img src="<?= WEBSITE_URL; ?>/img/warning.png" class="wiggle-me" style="width:3em;">
	<div style="float:right; width:calc(100% - 4em);"><span class="notice-name">Note:</span>
	Critical Incidents are not submissions to be taken lightly for us providing a service and for you submitting a request. Submitting a critical incident creates a code red within our organization. Additional charges may be billed out to a customer filling a Critical Report as these matters will take priority over all other matters. Critical Incidents will be reported on and tracked for quality assurance. Critical Incidents should be reserved for incidents that prevent your business from operating, moving forward with an important matter or for items that require drop everything attention.</div>
	<div class="clearfix"></div>
</div>
<?php $request_tab = (!empty($_GET['type']) ? $_GET['type'] : 'active'); ?>
<a href="?tab=critical&type=new"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($request_tab == 'new' ? 'active_tab' : ''); ?>">New</button></a>
<a href="?tab=critical&type=active"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($request_tab == 'active' ? 'active_tab' : ''); ?>">Active</button></a>
<a href="?tab=critical&type=closed"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($request_tab == 'closed' ? 'active_tab' : ''); ?>">Closed</button></a>

<?php if($request_tab == 'new'): ?>
	<?php if(!empty($_POST['new_request'])) {
		$errors = '';
		$customer = filter_var($_POST['customer'],FILTER_SANITIZE_STRING);
		$contactid = filter_var($_POST['contactid'],FILTER_SANITIZE_STRING);
		$incident_date = filter_var($_POST['critical_incident'],FILTER_SANITIZE_STRING);
		$reported_date = date('Y-m-d h:i:s');
		$business = filter_var($_POST['business'],FILTER_SANITIZE_STRING);
		$businessid = filter_var($_POST['businessid'],FILTER_SANITIZE_STRING);
		$software_url = filter_var($_POST['software'],FILTER_SANITIZE_STRING);
		$email = filter_var($_POST['email'],FILTER_SANITIZE_STRING);
		$cc = filter_var($_POST['ccemail'],FILTER_SANITIZE_STRING);
		$issue = filter_var(htmlentities($_POST['details']),FILTER_SANITIZE_STRING);
		$plan = filter_var(htmlentities($_POST['plan']),FILTER_SANITIZE_STRING);
		$discovery = filter_var(htmlentities($_POST['discovery']),FILTER_SANITIZE_STRING);
		$action = filter_var(htmlentities($_POST['action']),FILTER_SANITIZE_STRING);
		$check = filter_var(htmlentities($_POST['check']),FILTER_SANITIZE_STRING);
		$adjustments = filter_var(htmlentities($_POST['adjustments']),FILTER_SANITIZE_STRING);
		if(!mysqli_query($dbc_support, "INSERT INTO `support` (`name`, `contactid`, `company_name`, `businessid`, `software_url`, `critical_incident`, `email`, `cc_email`, `message`, `critical_plan`, `critical_discovery`, `critical_action`, `critical_check`, `critical_adjustments`, `support_type`)
			VALUES ('$customer', '$contactid', '$business', '$businessid', '$software_url', '$incident_date', '$email', '$cc', '$issue', '$plan', '$discovery', '$action', '$check', '$adjustments', 'Critical Incident')")) {
			$errors .= "Error Saving Critical Incident: ".mysqli_error($dbc_support)."\n";
		}

		$supportid = mysqli_insert_id($dbc_support);
		$email_attachments = '';
		foreach($_FILES['documents']['name'] as $row => $filename) {
			if($filename != '') {
				if (!file_exists('download')) {
					mkdir('download', 0777, true);
				}
				$basefilename = $filename = preg_replace('/[^A-Za-z0-9\.]/','_',$filename);
				$i = 0;
				while(file_exists('download/'.$filename)) {
					$filename = preg_replace('/(\.[A-Za-z0-9]*)/', '('.++$i.')$1', $basefilename);
				}
				if(!move_uploaded_file($_FILES['documents']['tmp_name'][$row], 'download/'.$filename)) {
					$errors .= "Error Saving Attachment: ".$filename."\n";
				}
				$email_attachments .= 'download/'.$filename.'#FFM#';
				if(!mysqli_query($dbc_support, "INSERT INTO `support_uploads` (`supportid`, `document`, `created_by`) VALUES ('$supportid', '".WEBSITE_URL."/Support/download/$filename', '".get_contact($dbc, $_SESSION['contactid'])."')")) {
					$errors .= "Error Recording Attachment: ".mysqli_error($dbc_support)."\n";
				}
			}
		}

	    if (mysqli_affected_rows($dbc_support) == 1) {
			// Email to FFM staff.
			$subject = "Critical Incident from $business";
			$body = "A Critical Incident has been reported.<br />
				Who initiated the report: $customer<br />
				Company: $business<br />
				Software URL: <a href='$software_url'>$software_url</a><br />
				Date of Emergency: $incident_date<br />
				Issue<hr>\n".html_entity_decode($issue)."\n
				Please review it as soon as possible. It can be found <a href='https://ffm.rookconnect.com/Support/customer_support.php?tab=critical&type=active'>here</a>.";

			$to = array_filter(array_merge(['dayanapatel@freshfocusmedia.com',
				'kennethbond@freshfocusmedia.com',
				'jenniferhardy@freshfocusmedia.com',
				'jaylahiru@freshfocusmedia.com',
				'jonathanhurdman@freshfocusmedia.com',
				'kaylavaltins@freshfocusmedia.com'],explode(',',$cc)));
			foreach($to as $address) {
				try {
					send_email('info@rookconnect',$address,'','',$subject,$body,$email_attachments);
				} catch(Exception $e) { $errors .= "Error sending notification to $address.\n"; }
			}

			// Thank you Email to sender and CC email.
			if($email != '') {
				$subject = 'Confirmation of Your Critical Incident';
				$body = "Hello $customer,
					<p>Your critical incident has been received. The request is currently under review by our support team, and
					you will be contacted shortly. For your records, you will find a copy of your original request below.</p>
					<p>Thank you,<br />
					Fresh Focus Media Support Team</p>
					<p>----------------------BEGIN ORIGINAL MESSAGE-----------------------------</p>
					<p>Date of Incident: $incident_date<br />
					Issue<hr>
					".html_entity_decode($issue);

				try {
					send_email('info@rookconnect.com', $email, '', '', $subject, $body, $email_attachments);
				} catch(Exception $e) { $errors .= "Error sending notification to $address.\n"; }
			}
		}

		if($errors != '') {
			echo "<script> alert('$errors'); </script>";
		}
		echo "<script> window.location.replace('?tab=critical&type=active'); </script>";
	} ?>
	<script>
	function add_uploader(button) {
		var clone = $('[name="documents[]"]').last().clone();
		clone.val('');
		$(button).before(clone);
	}
	function validate(form) {
		if($('[name=email]').val() == '') {
			alert('Please provide your email address.');
			$(form).find('[name=email]').focus();
			return false;
		}
		if($('[name=details]').val() == '') {
			alert('Please provide details of the issue.');
			tinymce.execCommand('mceFocus',false,'details')
			return false;
		}
		$('.container').hide().after('<h1>Submitting Request...</h1>');
	}
	</script>
	<form class="form" method="POST" action="" enctype="multipart/form-data" onsubmit="return validate(this);">
		<center><a href="http://www.freshfocusmedia.com" target="_blank"><img width="240px" src="<?php echo WEBSITE_URL; ?>/img/ffm-logo-support.png"></a>
            <h1 class="double-pad-bottom">Contact Info</h1>
			<h4><a href="http://www.freshfocusmedia.com" target="_blank">www.freshfocusmedia.com</a><br>
			<a href="tel:1.888.380.9439">1.888.380.9439</a><br>
            <a href="https://www.google.ca/maps/place/7220+Fairmount+Dr+SE+%23200,+Calgary,+AB+T2H+0X7/@50.9885147,-114.0645471,17z/data=!4m5!3m4!1s0x537170e35467ff8b:0xed9a38d0eea428d5!8m2!3d50.9885755!4d-114.063324">Suite 200, 7220 Fairmount Dr<br />SE Calgary, AB T2H 0X7</a></h4>
		</center>
		<h1 class="triple-pad-bottom">Submit Critical Incident</h1>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="business">Business:</label>
			<div class="col-sm-8">
				<input type="text" name="business" id="business" value="<?= $user_name ?>" class="form-control">
				<input type="hidden" name="businessid" value="<?= $user ?>">
				<input type="hidden" name="software" value="<?= WEBSITE_URL ?>">
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="customer">Who initiated the Priority:</label>
			<div class="col-sm-8">
				<input type="text" readonly name="customer" id="customer" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>" class="form-control">
				<input type="hidden" name="contactid" value="<?= $_SESSION['contactid'] ?>">
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="email">Email<span class="text-red">*</span>:</label>
			<div class="col-sm-8">
				<input type="text" name="email" id="email" value="<?= get_email($dbc, $_SESSION['contactid']) ?>" class="form-control">
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="ccemail">CC Email:</label>
			<div class="col-sm-8">
				<input type="text" name="ccemail" id="ccemail" value="" class="form-control">
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="date">Date and Time of Incident:</label>
			<div class="col-sm-8">
				<input type="text" name="incident_date" id="incident_date" value="<?= date('Y-m-d') ?>" class="form-control">
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="date">Date and Time Reported to FFM:</label>
			<div class="col-sm-8">
				<input type="text" readonly name="date" id="date" value="<?= date('Y-m-d h:i') ?>" class="form-control">
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="details">Issue as it was explained<span class="text-red">*</span>:</label>
			<div class="col-sm-8">
				<textarea name="details" id="details" class="form-control"></textarea>
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="plan">Plan:</label>
			<div class="col-sm-8">
				<textarea name="plan" id="plan" class="form-control"></textarea>
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="discovery">Investigation / Discovery:</label>
			<div class="col-sm-8">
				<textarea name="discovery" id="discovery" class="form-control"></textarea>
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="action">Actions Taken (Deliverables &amp; Timelines):</label>
			<div class="col-sm-8">
				<textarea name="action" id="action" class="form-control"></textarea>
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="check">Check:</label>
			<div class="col-sm-8">
				<textarea name="check" id="check" class="form-control"></textarea>
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="adjustments">Further Adjustments:</label>
			<div class="col-sm-8">
				<textarea name="adjustments" id="adjustments" class="form-control"></textarea>
			</div>
		</div>

		<div class="form-group clearfix">
			<label class="col-sm-4 control-label">Upload Documents:</label>
			<div class="col-sm-8">
				<input type="file" multiple name="documents[]" data-filename-placement="inside" class="form-control">
				<button onclick="add_uploader(this); return false;" class="btn brand-btn pull-right">Add More Documents</button>
			</div>
		</div>

		<button type="submit" name="new_request" value="new" class="btn brand-btn pull-right">Submit Request</button>
	</form>
<?php elseif($request_tab == 'closed'): ?>
	<?php $date = date('Y-m-d',strtotime('-2month'));
    $date_of_archival = date('Y-m-d');
	$support_list = mysqli_query($dbc_support, "SELECT * FROM `support` WHERE `businessid`='$user' AND `support_type`='Critical Incident' AND `deleted`=1, `date_of_archival` = '$date_of_archival' AND `archived_date` > '$date'");
	if(mysqli_num_rows($support_list) > 0) { ?>
		<ul class="connectedChecklist">
			<li class="ui-state-default ui-state-disabled no-sort">Critical Incidents</li>
			<?php while($row = mysqli_fetch_array($support_list)) {
				echo '<li id="'.$row['supportid'].'" class="ui-state-default" '.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';
				echo '<span>';
				echo '<span class="pull-right" style="cursor:pointer; display:inline-block; width:100%;" data-support="'.$row['supportid'].'">';
				echo '<span style="display:inline-block; text-align:center; width:33%;" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:33%;" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:33%;" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '</span>';
				echo '<input type="text" name="reply_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
				echo '<input type="text" name="checklist_time_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
				echo '<input type="text" name="reminder_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
				echo '<input type="file" name="attach_'.$row['supportid'].'" style="display:none;" class="form-control" />';
				echo '<br /><span class="display-field" data-support="'.$row['supportid'].'">Critical Incident #'.$row['supportid']."<hr>";
				echo '<span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Issue as it was explained:'.html_entity_decode($row['message']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_plan\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Plan:'.html_entity_decode($row['critical_plan']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_discovery\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Investigation / Discovery:'.html_entity_decode($row['critical_discovery']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_action\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Action Taken (Deliverables &amp; Timeline):'.html_entity_decode($row['critical_action']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_check\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Check:'.html_entity_decode($row['critical_check']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_adjust\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Further Adjustments:'.html_entity_decode($row['critical_adjustments']);
				echo '</span>';
				$documents = mysqli_query($dbc, "SELECT * FROM support_uploads WHERE supportid='".$row['supportid']."'");
				while($doc = mysqli_fetch_array($documents)) {
					$link = $doc['document'];
					$filename = explode('/',$link);
					$filename = $filename[count($filename)-1];
					echo '<a href="'.$link.'">'.$filename.' (Uploaded by '.$doc['created_by'].' on '.$doc['created_date'].')</a><br />';
				}
				echo '</span></li>';
			} ?>
		</ul>
	<?php } else {
		echo "<h3>No Critical Incidents Found</h3>";
	} ?>
<?php else: ?>
	<?php $support_list = mysqli_query($dbc_support, "SELECT * FROM `support` WHERE `businessid`='$user' AND `support_type`='Critical Incident' AND `deleted`=0");
	if(mysqli_num_rows($support_list) > 0) { ?>
		<ul class="connectedChecklist">
			<li class="ui-state-default ui-state-disabled no-sort">Critical Incidents</li>
			<?php while($row = mysqli_fetch_array($support_list)) {
				echo '<li id="'.$row['supportid'].'" class="ui-state-default" '.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';
				echo '<span>';
				echo '<span class="pull-right" style="cursor:pointer; display:inline-block; width:100%;" data-support="'.$row['supportid'].'">';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Archive Item" onclick="archive(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '</span>';
				echo '<input type="text" name="reply_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
				echo '<input type="text" name="checklist_time_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
				echo '<input type="text" name="reminder_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
				echo '<input type="file" name="attach_'.$row['supportid'].'" style="display:none;" class="form-control" />';
				echo '<br /><span class="display-field" data-support="'.$row['supportid'].'">Critical Incident #'.$row['supportid']."<hr>";
				echo '<span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Issue as it was explained:'.html_entity_decode($row['message']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_plan\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Plan:'.html_entity_decode($row['critical_plan']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_discovery\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Investigation / Discovery:'.html_entity_decode($row['critical_discovery']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_action\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Action Taken (Deliverables &amp; Timeline):'.html_entity_decode($row['critical_action']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_check\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Check:'.html_entity_decode($row['critical_check']);
				echo '<br /><span style="display:inline-block; text-align:center; width:2em;" title="Add Details" onclick="send_reply(this,\'reply_adjust\'); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo 'Further Adjustments:'.html_entity_decode($row['critical_adjustments']);
				echo '</span>';
				$documents = mysqli_query($dbc, "SELECT * FROM support_uploads WHERE supportid='".$row['supportid']."'");
				while($doc = mysqli_fetch_array($documents)) {
					$link = $doc['document'];
					$filename = explode('/',$link);
					$filename = $filename[count($filename)-1];
					echo '<a href="'.$link.'">'.$filename.' (Uploaded by '.$doc['created_by'].' on '.$doc['created_date'].')</a><br />';
				}
				echo '</span></li>';
			} ?>
		</ul>
	<?php } else {
		echo "<h3>No Critical Incidents Found</h3>";
	} ?>
<?php endif; ?>
<script>
function send_email(support) {
	support_id = $(support).parents('span').data('support');
	$.ajax({
		method: 'POST',
		url: 'support_ajax.php?fill=email',
		data: { id: support_id },
		complete: function(result) { console.log(result.responseText); }
	});
}
function send_reply(support,target) {
	if(target == undefined) {
		target = 'reply';
	}
	support_id = $(support).parents('span').data('support');
	$('[name=reply_'+support_id+']').show().focus();
	$('[name=reply_'+support_id+']').keyup(function(e) {
		if(e.which == 13) {
			$(this).blur();
		}
	});
	$('[name=reply_'+support_id+']').blur(function() {
		$(this).hide();
		var reply = $(this).val().trim();
		$(this).val('');
		if(reply != '') {
			var today = new Date();
			var save_reply = reply + " (Note added by <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?> at "+today.toLocaleString()+")";
			$.ajax({
				method: 'POST',
				url: 'support_ajax.php?fill='+target,
				data: { id: support_id, reply: save_reply },
				complete: function(result) { window.location.reload(); }
			})
		}
	});
}
function attach_file(support) {
	support_id = $(support).parents('span').data('support');
	$('[name=attach_'+support_id+']').change(function() {
		var fileData = new FormData();
		fileData.append('file',$('[name=attach_'+support_id+']')[0].files[0]);
		$.ajax({
			contentType: false,
			processData: false,
			type: "POST",
			url: "support_ajax.php?fill=upload&id="+support_id,
			data: fileData,
			complete: function(result) {
				console.log(result.responseText);
				window.location.reload();
			}
		});
	});
	$('[name=attach_'+support_id+']').click();
}
function flag_item(support) {
	support_id = $(support).parents('span').data('support');
	$.ajax({
		method: "POST",
		url: "support_ajax.php?fill=flag",
		data: { id: support_id },
		complete: function(result) {
			console.log(result.responseText);
			$(support).closest('li').css('background-color',(result.responseText == '' ? '' : '#'+result.responseText));
		}
	});
}
function archive(support) {
	support_id = $(support).parents('span').data('support');
	if(confirm("Are you sure you want to mark this item as completed?")) {
		$.ajax({    //create an ajax request to load_page.php
			type: "GET",
			url: "support_ajax.php?fill=archive&supportid="+support_id,
			dataType: "html",   //expect html to be returned
			success: function(response){
				console.log(response.responseText);
				window.location.reload();
			}
		});
	}
}
</script>