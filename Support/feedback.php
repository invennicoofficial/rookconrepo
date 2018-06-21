<div class="notice double-gap-bottom popover-examples">
	<img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" style="width:3em;">
	<div style="float:right; width:calc(100% - 4em);"><span class="notice-name">Note:</span>
	Nothing is more essential that ongoing business growth and nothing is more important to us that developing and improving our solutions to help our customers run increasingly successful businesses. Any feedback, criticism, want, need, etc. is appreciated and will be responded to. We look to our customers for feedback and ideas and appreciate your business and support as we continue to improve.</div>
	<div class="clearfix"></div>
</div>
<?php $request_tab = (!empty($_GET['type']) ? $_GET['type'] : 'active'); ?>
<a href="?tab=feedback&type=new"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($request_tab == 'new' ? 'active_tab' : ''); ?>">New</button></a>
<a href="?tab=feedback&type=active"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($request_tab == 'active' ? 'active_tab' : ''); ?>">Active</button></a>
<a href="?tab=feedback&type=closed"><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo ($request_tab == 'closed' ? 'active_tab' : ''); ?>">Closed</button></a>

<?php if($request_tab == 'new'): ?>
	<?php if(!empty($_POST['new_request'])) {
		$errors = '';
		$customer = filter_var($_POST['customer'],FILTER_SANITIZE_STRING);
		$contactid = filter_var($_POST['contactid'],FILTER_SANITIZE_STRING);
		$date = date('Y-m-d');
		$business = filter_var($_POST['business'],FILTER_SANITIZE_STRING);
		$businessid = filter_var($_POST['businessid'],FILTER_SANITIZE_STRING);
		$software_url = filter_var($_POST['software'],FILTER_SANITIZE_STRING);
		$email = filter_var($_POST['email'],FILTER_SANITIZE_STRING);
		$cc = filter_var($_POST['ccemail'],FILTER_SANITIZE_STRING);
		$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
		$details = filter_var(htmlentities($_POST['details']),FILTER_SANITIZE_STRING);
		if(!mysqli_query($dbc_support, "INSERT INTO `support` (`name`, `contactid`, `company_name`, `businessid`, `software_url`, `current_date`, `email`, `cc_email`, `heading`, `message`, `support_type`)
			VALUES ('$customer', '$contactid', '$business', '$businessid', '$software_url', '$date', '$email', '$cc', '$heading', '$details', 'Feedback')")) {
			$errors .= "Error Saving Feedback: ".mysqli_error($dbc_support)."\n";
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
			$subject = "Feedback from $business";
			$body = "Feedback has been sent.<br />
				Name: $customer<br />
				Company: $business<br />
				Software URL: <a href='$software_url'>$software_url</a><br />
				Email: $email<br />
				CC: $cc<br />
				Heading: $heading<br />
				Details<hr>\n".html_entity_decode($details)."\n
				Please review it as soon as possible. It can be found <a href='https://ffm.rookconnect.com/Support/customer_support.php?tab=feedback&type=active'>here</a>.";

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
				$subject = 'Confirmation of Your Feedback';
				$body = "Hello $customer,
					<p>Your feedback has been received. The feedback is currently under review by our support team, and you may be contacted
					for any further details. For your records, you will find a copy of your original request below.</p>
					<p>Thank you,<br />
					Fresh Focus Media Support Team</p>
					<p>----------------------BEGIN ORIGINAL MESSAGE-----------------------------</p>
					<p>Heading: $heading<br />
					Details<hr>
					".html_entity_decode($details);
			
				try {
					send_email('info@rookconnect.com', $email, '', '', $subject, $body, $email_attachments);
				} catch(Exception $e) { $errors .= "Error sending notification to $address.\n"; }
			}
		}
		
		if($errors != '') {
			echo "<script> alert('$errors'); </script>";
		}
		echo "<script> window.location.replace('?tab=feedback&type=active'); </script>";
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
			alert('Please provide details.');
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
		<h1 class="triple-pad-bottom">Submit Feedback</h1>
		
		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="customer">Customer:</label>
			<div class="col-sm-8">
				<input type="text" name="customer" id="customer" value="<?= get_contact($dbc, $_SESSION['contactid']) ?>" class="form-control">
				<input type="hidden" name="contactid" value="<?= $_SESSION['contactid'] ?>">
			</div>
		</div>
		
		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="date">Date:</label>
			<div class="col-sm-8">
				<input type="text" readonly name="date" id="date" value="<?= date('Y-m-d') ?>" class="form-control">
			</div>
		</div>
		
		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="business">Business:</label>
			<div class="col-sm-8">
				<input type="text" name="business" id="business" value="<?= $user_name ?>" class="form-control">
				<input type="hidden" name="businessid" value="<?= $user ?>">
				<input type="hidden" name="software" value="<?= WEBSITE_URL ?>">
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
			<label class="col-sm-4 control-label" for="heading">Feedback Heading:</label>
			<div class="col-sm-8">
				<select name="heading" id="heading" class="form-control chosen-select-deselect"><option></option>
					<option value="Marketing Strategies">Marketing Strategies</option>
					<option value="Creative Design">Creative Design</option>
					<option value="Hosting, Domains and Emails">Hosting, Domains &amp; Emails</option>
					<option value="Web Design">Web Design</option>
					<option value="Digital Advertising - SEO and SEM">Digital Advertising (SEO &amp; SEM)</option>
					<option value="Social Media and Blog Work">Social Media &amp; Blog Work</option>
					<option value="New Software Functionality">New Software Functionality</option>
					<option value="Software Revision - Bug">Software Revision (Bug)</option>
					<option value="Meeting Request">Meeting Request</option>
					<option value="New Idea">New Idea</option>
				</select>
			</div>
		</div>
		
		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="details">Details<span class="text-red">*</span>:</label>
			<div class="col-sm-8">
				<textarea name="details" id="details" class="form-control"></textarea>
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
	$support_list = mysqli_query($dbc_support, "SELECT * FROM `support` WHERE `businessid`='$user' AND `support_type`='Feedback' AND `deleted`=1 AND `archived_date` > '$date'");
	if(mysqli_num_rows($support_list) > 0) { ?>
		<ul class="connectedChecklist">
			<li class="ui-state-default ui-state-disabled no-sort">Feedback</li>
			<?php while($row = mysqli_fetch_array($support_list)) {
				echo '<li id="'.$row['supportid'].'" class="ui-state-default" '.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';
				echo '<span>';
				echo '<span class="pull-right" style="cursor:pointer; display:inline-block; width:100%;" data-support="'.$row['supportid'].'">';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Reply" onclick="send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:25%;" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '</span>';
				echo '<input type="text" name="reply_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
				echo '<input type="text" name="checklist_time_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
				echo '<input type="text" name="reminder_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
				echo '<input type="file" name="attach_'.$row['supportid'].'" style="display:none;" class="form-control" />';
				echo '<br /><span class="display-field">Feedback #'.$row['supportid']."<br />".$row['heading']."<hr>".html_entity_decode($row['message']).'</span>';
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
		echo "<h3>No Feedback Found</h3>";
	} ?>
<?php else: ?>
	<?php $support_list = mysqli_query($dbc_support, "SELECT * FROM `support` WHERE `businessid`='$user' AND `support_type`='Feedback' AND `deleted`=0");
	if(mysqli_num_rows($support_list) > 0) { ?>
		<ul class="connectedChecklist">
			<li class="ui-state-default ui-state-disabled no-sort">Feedback</li>
			<?php while($row = mysqli_fetch_array($support_list)) {
				echo '<li id="'.$row['supportid'].'" class="ui-state-default" '.($row['flag_colour'] == '' ? '' : 'style="background-color: #'.$row['flag_colour'].';"').'>';
				echo '<span>';
				echo '<span class="pull-right" style="cursor:pointer; display:inline-block; width:100%;" data-support="'.$row['supportid'].'">';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Reply" onclick="send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Archive Item" onclick="archive(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '</span>';
				echo '<input type="text" name="reply_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
				echo '<input type="text" name="checklist_time_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
				echo '<input type="text" name="reminder_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
				echo '<input type="file" name="attach_'.$row['supportid'].'" style="display:none;" class="form-control" />';
				echo '<br /><span class="display-field">Feedback #'.$row['supportid']."<br />".$row['heading']."<hr>".html_entity_decode($row['message']).'</span>';
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
		echo "<h3>No Feedback Found</h3>";
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
function send_reply(support) {
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
			var save_reply = reply + " (Reply added by <?php echo decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?> at "+today.toLocaleString()+")";
			$.ajax({
				method: 'POST',
				url: 'support_ajax.php?fill=reply',
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