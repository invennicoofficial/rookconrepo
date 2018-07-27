<?php if($request_tab == 'new'): ?>
	<?php if(!empty($_POST['new_request'])) {
		$errors = '';
		$type = $_POST['type'];
		$customer = filter_var($_POST['customer'],FILTER_SANITIZE_STRING);
		$contactid = filter_var($_POST['contactid'],FILTER_SANITIZE_STRING);
		$date = date('Y-m-d');
		$incident_date = filter_var($_POST['incident_date'],FILTER_SANITIZE_STRING);
		$reported_date = date('Y-m-d h:i:s');
		$business = filter_var($_POST['business'],FILTER_SANITIZE_STRING);
		$businessid = filter_var($_POST['businessid'],FILTER_SANITIZE_STRING);
		$software_url = filter_var($_POST['software'],FILTER_SANITIZE_STRING);
		$email = filter_var($_POST['email'],FILTER_SANITIZE_STRING);
		$cc = filter_var($_POST['ccemail'],FILTER_SANITIZE_STRING);
		$heading = filter_var($_POST['heading'],FILTER_SANITIZE_STRING);
		$details = filter_var(htmlentities($_POST['details']),FILTER_SANITIZE_STRING);
		$plan = filter_var(htmlentities($_POST['plan']),FILTER_SANITIZE_STRING);
		$discovery = filter_var(htmlentities($_POST['discovery']),FILTER_SANITIZE_STRING);
		$action = filter_var(htmlentities($_POST['action']),FILTER_SANITIZE_STRING);
		$check = filter_var(htmlentities($_POST['check']),FILTER_SANITIZE_STRING);
		$adjustments = filter_var(htmlentities($_POST['adjustments']),FILTER_SANITIZE_STRING);
		$support_insert = "INSERT INTO `support` (`name`, `contactid`, `company_name`, `businessid`, `software_url`, `current_date`, `critical_incident`, `email`, `cc_email`, `heading`, `message`, `critical_plan`, `critical_discovery`, `critical_action`, `critical_check`, `critical_adjustments`, `support_type`)
			VALUES ('$customer', '$contactid', '$business', '$businessid', '$software_url', '$date', '$incident_date', '$email', '$cc', '$heading', '$details', '$plan', '$discovery', '$action', '$check', '$adjustments', '$type')";
		if(!mysqli_query($dbc_support, $support_insert)) {
			$errors .= "Error Saving Support Request: ".mysqli_error($dbc_support)."\n";
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
		foreach($_POST['links'] as $link) {
			if($link != '') {
				if(!mysqli_query($dbc_support, "INSERT INTO `support_uploads` (`supportid`, `document`, `created_by`) VALUES ('$supportid', '$link', '".get_contact($dbc, $_SESSION['contactid'])."')")) {
					$errors .= "Error Recording Attachment: ".mysqli_error($dbc_support)."\n";
				}
			}
		}

	    if (mysqli_affected_rows($dbc_support) == 1) {
			if($type == 'Support Request') {
				$subject = "Support Request from $business";
				$body = "A support request has been sent.<br />
					<h3>Date of Request: $date</h3>
					Name: $customer<br />
					Company: $business<br />
					Software URL: <a href='$software_url'>$software_url</a><br />
					Email: $email<br />
					CC: $cc<br />
					Heading: $heading<br />
					Details<hr>\n".html_entity_decode($details)."\n
					Please review it as soon as possible. It can be found <a href='https://ffm.rookconnect.com/Support/customer_support.php?tab=requests&type=requests#$supportid'>here</a>.";
				$cust_subject = 'Confirmation of Your Support Request';
				$cust_body = "Hello $customer,
					<p>Your support request has been received. The request is currently under review by our support team, and
					you will be contacted shortly. For your records, you will find a copy of your original request below.</p>
					<p>Thank you,<br />
					Fresh Focus Media Support Team</p>
					<p>----------------------BEGIN ORIGINAL MESSAGE-----------------------------</p>
					<h3>Date of Request: $date</h3>
					<p>Heading: $heading<br />
					Details<hr>
					".html_entity_decode($details);
			}
			else if($type == 'Critical Incident') {
				$subject = "Critical Incident from $business";
				$body = "A Critical Incident has been reported.<br />
					Who initiated the report: $customer<br />
					Company: $business<br />
					Software URL: <a href='$software_url'>$software_url</a><br />
					Date of Emergency: $incident_date<br />
					Issue<hr>\n".html_entity_decode($details)."\n
					Please review it as soon as possible. It can be found <a href='https://ffm.rookconnect.com/Support/customer_support.php?tab=requests&type=critical#$supportid'>here</a>.";
				$cust_subject = 'Confirmation of Your Critical Incident';
				$cust_body = "Hello $customer,
					<p>Your critical incident has been received. The request is currently under review by our support team, and
					you will be contacted shortly. For your records, you will find a copy of your original request below.</p>
					<p>Thank you,<br />
					Fresh Focus Media Support Team</p>
					<p>----------------------BEGIN ORIGINAL MESSAGE-----------------------------</p>
					<p>Date of Incident: $incident_date<br />
					Issue<hr>
					".html_entity_decode($details);
			}
			else if($type == 'Feedback') {
				$subject = "Feedback from $business";
				$body = "Feedback has been sent.<br />
					Name: $customer<br />
					Company: $business<br />
					Software URL: <a href='$software_url'>$software_url</a><br />
					Email: $email<br />
					CC: $cc<br />
					Heading: $heading<br />
					Details<hr>\n".html_entity_decode($details)."\n
					Please review it as soon as possible. It can be found <a href='https://ffm.rookconnect.com/Support/customer_support.php?tab=feedback&type=feedback#$supportid'>here</a>.";
				$cust_subject = 'Confirmation of Your Feedback';
				$cust_body = "Hello $customer,
					<p>Your feedback has been received. The feedback is currently under review by our support team, and you may be contacted
					for any further details. For your records, you will find a copy of your original request below.</p>
					<p>Thank you,<br />
					Fresh Focus Media Support Team</p>
					<p>----------------------BEGIN ORIGINAL MESSAGE-----------------------------</p>
					<p>Heading: $heading<br />
					Details<hr>
					".html_entity_decode($details);
			}
			
			// Email to FFM staff.
			/*$to = array_filter(['dayanapatel@freshfocusmedia.com',
				'kennethbond@freshfocusmedia.com',
				'jenniferhardy@freshfocusmedia.com',
				'jaylahiru@freshfocusmedia.com',
				'jonathanhurdman@freshfocusmedia.com',
				'kaylavaltins@freshfocusmedia.com']);*/
			$to = ['jonathanhurdman@freshfocusmedia.com'];
			foreach($to as $address) {
				try {
					send_email('info@rookconnect',$address,'','',$subject,$body,$email_attachments);
				} catch(Exception $e) { $errors .= "Error sending notification to $address.\n"; }
			}
			
			// Thank you Email to sender and CC email.
			$to = array_filter([$email,explode(',',$cc)]);
			foreach($to as $address) {
				try {
					send_email('info@rookconnect',$address,'','',$cust_subject,$cust_body,$email_attachments);
				} catch(Exception $e) { $errors .= "Error sending notification to $address.\n"; }
			}
		}
		
		if($errors != '') {
			echo "<script> alert('$errors'); </script>";
		}
		echo "<script> window.location.replace('?tab=requests&type=".($type=='Feedback' ? 'feedback' : ($type == 'Support Request' ? 'requests' : 'critical'))."'); </script>";
	}
	$new_type = (!empty($_GET['new_type']) ? $_GET['new_type'] : '');
	$source = (!empty($_GET['source']) ? $_GET['source'] : '');
	?>
	<script>
	$(document).on('change', 'select[name="type"]', function() { selectType(this.value); });
	$(document).on('change', 'select[name="set_staff[]"]', function() { assign_staff(this); });
	function add_uploader(button) {
		var clone = $('[name="documents[]"]').last().clone();
		clone.val('');
		$(button).before(clone);
	}
	function add_link(button) {
		var clone = $('[name="links[]"]').last().clone();
		clone.val('');
		$(button).before(clone);
		$('[name="links[]"]').last().focus();
	}
	function validate(form) {
		tinymce.triggerSave();
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
		$('.container form').hide().after('<h1>Submitting Request...</h1>');
	}
	function selectType(type) {
		if(type == 'Critical Incident') {
			if(!confirm("Critical Incidents should only be submitted for incidents that prevent your business from operating, moving forward with an important matter or for items that require drop everything attention. Submitting a critical incident creates a code red within our organization. Additional charges may be billed to a customer filling a Critical Report as these matters will take priority over all other matters. Critical Incidents will be reported on and tracked for quality assurance. Are you sure you want to create a Critical Incident? Click OK to create a Critical Incident, or cancel to select a Support Request instead.")) {
				type = 'Support Request';
			}
		}
		window.location.replace('?tab=requests&type=new&new_type='+type);
	}
	</script>
	<div class="notice double-gap-bottom popover-examples">
		<img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" style="width:3em;">
		<div style="float:right; width:calc(100% - 4em);"><span class="notice-name">Note:</span>
		<?= ($new_type == 'Feedback' ? 'We place the highest importance on developing and improving our solutions to help our customers run increasingly successful businesses with ongoing growth. Any feedback, criticism, want, need, etc. is appreciated and will be responded to. We look to our customers for feedback and ideas, and appreciate your business and support as we continue to improve.'
			: ($new_type == 'Support Request' ? 'Submitting new requests assures that our support staff are immediately alerted to your needs. Please provide as much detail as possible to ensure we have all the information we need to do the best possible work to support your needs.'
			: ($new_type == 'Critical Incident' ? 'Critical Incidents should only be submitted for incidents that prevent your business from operating, moving forward with an important matter or for items that require drop everything attention. Submitting a critical incident creates a code red within our organization. Additional charges may be billed to a customer filling a Critical Report as these matters will take priority over all other matters. Critical Incidents will be reported on and tracked for quality assurance.'
			: 'Please select a type.'))) ?></div>
		<div class="clearfix"></div>
	</div>
	<form class="form" method="POST" action="" enctype="multipart/form-data" onsubmit="return validate(this);">
		<h1 class="triple-pad-bottom">Submit Support Request</h1>
		
		<div class="form-group clearfix">
			<label class="col-sm-4 control-label" for="type">Request Type:</label>
			<div class="col-sm-8">
				<?php if(empty($source)) { ?>
					<select name="type" id="type" class="chosen-select-deselect form-control"><option></option>
						<option <?= ($new_type == 'feedback' ? 'selected' : '') ?> value="feedback">Feedback & Ideas</option>
						<option <?= ($new_type == 'Support Request' ? 'selected' : '') ?> value="Support Request">Support Request</option>
						<option <?= ($new_type == 'Critical Incident' ? 'selected' : '') ?> value="Critical Incident">Critical Incident</option>
					</select>
				<?php } else {
					echo $new_type;
				} ?>
			</div>
		</div>
		
		<?php if($new_type == 'Support Request'): ?>
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
				<label class="col-sm-4 control-label" for="heading">Support Request Heading:</label>
				<div class="col-sm-8">
					<select name="heading" id="heading" class="form-control chosen-select-deselect"><option></option>
						<option value="Creative Design">Creative Design</option>
						<option value="Digital Advertising - SEO and SEM">Digital Advertising (SEO &amp; SEM)</option>
						<option value="Hosting, Domains and Emails">Hosting, Domains &amp; Emails</option>
						<option value="Marketing Strategies">Marketing Strategies</option>
						<option value="Meeting Request">Meeting Request</option>
						<option value="New Idea">New Idea</option>
						<option value="New Software Functionality">New Software Functionality</option>
						<option value="Social Media and Blog Work">Social Media &amp; Blog Work</option>
						<option value="Software Revision - Bug">Software Revision (Bug)</option>
						<option value="Web Design">Web Design</option>
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
			<div class="form-group clearfix">
				<label class="col-sm-4 control-label">Attach Link:</label>
				<div class="col-sm-8">
					<input type="text" name="links[]" class="form-control">
					<button onclick="add_link(this); return false;" class="btn brand-btn pull-right">Add More Links</button>
				</div>
			</div>
		<?php elseif($new_type == 'Critical Incident'): ?>
			<input type="hidden" name="heading" value="Critical Incident">
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
			
			<!--<div class="form-group clearfix">
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
			</div>-->
				
			<div class="form-group clearfix">
				<label class="col-sm-4 control-label">Upload Documents:</label>
				<div class="col-sm-8">
					<input type="file" multiple name="documents[]" data-filename-placement="inside" class="form-control">
					<button onclick="add_uploader(this); return false;" class="btn brand-btn pull-right">Add More Documents</button>
				</div>
			</div>
			<div class="form-group clearfix">
				<label class="col-sm-4 control-label">Attach Link:</label>
				<div class="col-sm-8">
					<input type="text" name="links[]" class="form-control">
					<button onclick="add_link(this); return false;" class="btn brand-btn pull-right">Add More Links</button>
				</div>
			</div>
		<?php elseif($new_type == 'Feedback'): ?>
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
						<option value="Creative Design">Creative Design</option>
						<option value="Digital Advertising - SEO and SEM">Digital Advertising (SEO &amp; SEM)</option>
						<option value="Hosting, Domains and Emails">Hosting, Domains &amp; Emails</option>
						<option value="Marketing Strategies">Marketing Strategies</option>
						<option value="Meeting Request">Meeting Request</option>
						<option value="New Idea">New Idea</option>
						<option value="New Software Functionality">New Software Functionality</option>
						<option value="Social Media and Blog Work">Social Media &amp; Blog Work</option>
						<option value="Software Revision - Bug">Software Revision (Bug)</option>
						<option value="Web Design">Web Design</option>
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
			<div class="form-group clearfix">
				<label class="col-sm-4 control-label">Attach Link:</label>
				<div class="col-sm-8">
					<input type="text" name="links[]" class="form-control">
					<button onclick="add_link(this); return false;" class="btn brand-btn pull-right">Add More Links</button>
				</div>
			</div>
		<?php endif; ?>
		
		<button type="submit" name="new_request" value="new" class="btn brand-btn pull-right">Submit Request</button>
		<div class="clearfix"></div>
	</form>
<?php elseif($request_tab == 'closed'): ?>
	<div class="notice double-gap-bottom popover-examples">
		<img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" style="width:3em;">
		<div style="float:right; width:calc(100% - 4em);"><span class="notice-name">Note:</span>
		All completed Support Requests will be displayed here for two months. After two months completed requests are moved to the archive, still accessible upon request.</div>
		<div class="clearfix"></div>
	</div>
	<form name='search_form' method='POST' action=''>
	<?php $date = date('Y-m-d',strtotime('-2month'));
	$search_string = '';
	$search_cust = '';
	$search_start = '';
	$search_end = date('Y-m-d');
	$search_head = '';
	$search_details = '';
	if(!empty($_POST['search'])) {
		$search_cust = $_POST['search_cust'];
		$search_start = $_POST['search_start'];
		$search_end = $_POST['search_end'];
		$search_head = $_POST['search_head'];
		$search_details = $_POST['search_details'];
		
		$search_string = " AND `current_date` >= '$search_start' AND `current_date` <= '$search_end' AND `company_name` LIKE '$search_cust%' AND `heading` LIKE '%$search_head%' AND IFNULL(`message`,'') LIKE '%$search_details%'";
	} ?>
	<div class="search-group">
		<div class="form-group col-lg-9 col-md-8 col-sm-12 col-xs-12">
			<?php if($user_category == 'Staff') { ?>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="col-sm-4">
						<label for="site_name" class="control-label">Search By Customer:</label>
					</div>
					<div class="col-sm-8">
						<select data-placeholder="Select a Customer" name="search_cust" class="chosen-select-deselect form-control">
							<option></option>
							<?php $query = mysqli_query($dbc_support,"SELECT DISTINCT `company_name` FROM `support` WHERE `deleted`=1 ORDER BY `company_name`");
							while($custid = mysqli_fetch_array($query)['company_name']) { ?>
								<option <?php if ($custid == $search_cust) { echo " selected"; } ?> value='<?php echo  $custid; ?>' ><?= $custid ?></option><?php
							} ?>
						</select>
					</div>
				</div>
			<?php } ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search From Date:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="search_start" value="<?= $search_start ?>" class="form-control datepicker">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search To Date:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="search_end" value="<?= $search_end ?>" class="form-control datepicker">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search By Heading:</label>
				</div>
				<div class="col-sm-8">
						<select data-placeholder="Select a Heading" name="search_head" class="chosen-select-deselect form-control">
							<option></option>
							<?php $query = mysqli_query($dbc_support,"SELECT DISTINCT `heading` FROM `support` WHERE `deleted`=1 ORDER BY `heading`");
							while($heading = mysqli_fetch_array($query)['heading']) { ?>
								<option <?php if ($heading == $search_head) { echo " selected"; } ?> value='<?php echo  $heading; ?>' ><?= $heading ?></option><?php
							} ?>
						</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search By Details:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="search_details" value="<?= $search_details ?>" class="form-control">
				</div>
			</div>
		</div>
		<div class="form-group col-lg-3 col-md-4 col-sm-12 col-xs-12">
			<div style="display:inline-block; padding: 0 0.5em;">
				<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here after you have entered search criteria."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button type="submit" name="search" value="Search" class="btn brand-btn mobile-block">Search</button>
				<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click to refresh the page and see closed requests from the past two months."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href="" class="btn brand-btn mobile-block">Display All</a>
			</div>
		</div><!-- .form-group -->
		<div class="clearfix"></div>
	</div>
	</form>
	<?php $support_list = mysqli_query($dbc_support, "SELECT * FROM `support` WHERE (`businessid`='$user' OR '$user_category' IN (".STAFF_CATS.")) AND `deleted`=1 AND `archived_date` > '$date'".$search_string);
	if(mysqli_num_rows($support_list) > 0) { ?>
		<ul class="connectedChecklist">
			<li class="ui-state-default ui-state-disabled no-sort">Support Requests</li>
			<?php while($row = mysqli_fetch_array($support_list)) {
				echo '<li id="'.$row['supportid'].'" class="ui-state-default" style="'.($row['flag_colour'] == '' ? '' : 'background-color: #'.$row['flag_colour'].';').' border: solid #FF0000 2px; margin-bottom: 1em;">';
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
				echo '<br /><span class="display-field"><b>Date of Request: '.$row['current_date']."</b><br />Support Request #".$row['supportid']."<br />".$row['heading']."<hr>".html_entity_decode($row['message']).'</span>';
				$documents = mysqli_query($dbc, "SELECT * FROM support_uploads WHERE supportid='".$row['supportid']."'");
				while($doc = mysqli_fetch_array($documents)) {
					$link = $doc['document'];
					echo '<a href="'.$link.'">'.$link.' (Attached by '.$doc['created_by'].' on '.$doc['created_date'].')</a><br />';
				}
				echo '</span></li>';
			} ?>
		</ul>
	<?php } else {
		echo "<h3>No Support Requests Found</h3>";
	} ?>
<?php else: ?>
	<div class="notice double-gap-bottom popover-examples">
		<img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" style="width:3em;">
		<div style="float:right; width:calc(100% - 4em);"><span class="notice-name">Note:</span>
		All active <?= ($request_tab == 'feedback' ? 'Feedback & Ideas' : ($request_tab == 'requests' ? 'Support Requests' : 'Critical Incidents')) ?> display here. As we complete support requests, require your approval or simply want to provide you with work ready for your approval, all Active Requests will display here.</div>
		<div class="clearfix"></div>
	</div>
	
	<form name='search_form' method='POST' action=''>
	<?php $date = date('Y-m-d',strtotime('-2month'));
	$search_string = '';
	$search_cust = '';
	$search_start = '';
	$search_end = date('Y-m-d');
	$search_head = '';
	$search_details = '';
	if(!empty($_POST['search'])) {
		$search_cust = $_POST['search_cust'];
		$search_start = $_POST['search_start'];
		$search_end = $_POST['search_end'];
		$search_head = $_POST['search_head'];
		$search_details = $_POST['search_details'];
		
		$search_string = " AND `current_date` >= '$search_start' AND `current_date` <= '$search_end' AND `company_name` LIKE '$search_cust%' AND `heading` LIKE '%$search_head%' AND IFNULL(`message`,'') LIKE '%$search_details%'";
	} ?>
	<div class="search-group">
		<div class="form-group col-lg-9 col-md-8 col-sm-12 col-xs-12">
			<?php if($user_category == 'Staff') { ?>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="col-sm-4">
						<label for="site_name" class="control-label">Search By Customer:</label>
					</div>
					<div class="col-sm-8">
						<select data-placeholder="Select a Customer" name="search_cust" class="chosen-select-deselect form-control">
							<option></option>
							<?php $query = mysqli_query($dbc_support,"SELECT DISTINCT `company_name` FROM `support` WHERE `support_type`='".($request_tab == 'feedback' ? 'Feedback' : ($request_tab == 'requests' ? 'Support Request' : 'Critical Incident'))."' AND `deleted`=0 ORDER BY `company_name`");
							while($custid = mysqli_fetch_array($query)['company_name']) { ?>
								<option <?php if ($custid == $search_cust) { echo " selected"; } ?> value='<?php echo  $custid; ?>' ><?= $custid ?></option><?php
							} ?>
						</select>
					</div>
				</div>
			<?php } ?>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search From Date:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="search_start" value="<?= $search_start ?>" class="form-control datepicker">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search To Date:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="search_end" value="<?= $search_end ?>" class="form-control datepicker">
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search By Heading:</label>
				</div>
				<div class="col-sm-8">
						<select data-placeholder="Select a Heading" name="search_head" class="chosen-select-deselect form-control">
							<option></option>
							<?php $query = mysqli_query($dbc_support,"SELECT DISTINCT `heading` FROM `support` WHERE `support_type`='".($request_tab == 'feedback' ? 'Feedback' : ($request_tab == 'requests' ? 'Support Request' : 'Critical Incident'))."' AND `deleted`=0 ORDER BY `heading`");
							while($heading = mysqli_fetch_array($query)['heading']) { ?>
								<option <?php if ($heading == $search_head) { echo " selected"; } ?> value='<?php echo  $heading; ?>' ><?= $heading ?></option><?php
							} ?>
						</select>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="col-sm-4">
					<label for="site_name" class="control-label">Search By Details:</label>
				</div>
				<div class="col-sm-8">
					<input type="text" name="search_details" value="<?= $search_details ?>" class="form-control">
				</div>
			</div>
		</div>
		<div class="form-group col-lg-3 col-md-4 col-sm-12 col-xs-12">
			<div style="display:inline-block; padding: 0 0.5em;">
				<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click here after you have entered search criteria."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<button type="submit" name="search" value="Search" class="btn brand-btn mobile-block">Search</button>
				<span class="popover-examples list-inline"><a data-toggle="tooltip" data-placement="top" title="Click to refresh the page and see closed requests from the past two months."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
				<a href="" class="btn brand-btn mobile-block">Display All</a>
			</div>
		</div><!-- .form-group -->
		<div class="clearfix"></div>
	</div>
	</form>
	<a href="?tab=requests&type=new&new_type=<?=  ($request_tab == 'feedback' ? 'Feedback' : ($request_tab == 'requests' ? 'Support Request' : 'Critical Incident')) ?>&source=tab" class="btn brand-btn pull-right">
		Submit <?= ($request_tab == 'feedback' ? 'Feedback &amp; Ideas' : ($request_tab == 'requests' ? 'Support Request' : 'Critical Incident')) ?></a>
	<?php $support_list = mysqli_query($dbc_support, "SELECT * FROM `support` WHERE (`businessid`='$user' OR '$user_category' IN (".STAFF_CATS.")) AND `support_type`='".($request_tab == 'feedback' ? 'Feedback' : ($request_tab == 'requests' ? 'Support Request' : 'Critical Incident'))."' AND `deleted`=0".$search_string);
	$staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `first_name`, `last_name`, `contactid` FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND `deleted`=0 AND `status`>0"),MYSQLI_ASSOC));
	if(mysqli_num_rows($support_list) > 0) { ?>
		<ul class="connectedChecklist">
			<li class="ui-state-default ui-state-disabled no-sort">Support Requests</li>
			<?php while($row = mysqli_fetch_array($support_list)) {
				echo '<a name="'.$row['supportid'].'"></a><li id="'.$row['supportid'].'" class="ui-state-default" style="'.($row['flag_colour'] == '' ? '' : 'background-color: #'.$row['flag_colour'].';').' border: solid #FF0000 2px; margin-bottom: 1em;">';
				echo '<span>';
				echo '<span class="pull-right" style="cursor:pointer; display:inline-block; width:100%;" data-support="'.$row['supportid'].'">';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Flag This!" onclick="flag_item(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-flag-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Attach File" onclick="attach_file(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-attachment-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Reply" onclick="send_reply(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-reply-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Send Email" onclick="send_email(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-email-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				echo '<span style="display:inline-block; text-align:center; width:20%;" title="Archive Item" onclick="archive(this); return false;"><img src="'.WEBSITE_URL.'/img/icons/ROOK-trash-icon.png" style="height:1.5em;" onclick="return false;"></span>';
				if($user_category == 'Staff') {
					echo '<label class="col-sm-4 control-label">Staff Responsible:</label><div class="col-sm-8"><select name="set_staff[]" multiple data-placeholder="Select Staff to Assign" class="chosen-select-deselect form-control"><option></option>';
					foreach($staff_list as $id) {
						echo '<option '.(strpos(','.$row['assigned'].',', ','.$id.',') !== false ? 'selected' : '').' value="'.$id.'">'.get_contact($dbc, $id).'</option>';
					}
					echo '</select></div>';
					if($row['ticketid'] > 0) {
						echo '<a href="'.WEBSITE_URL.'/Ticket/index.php?edit='.$row['ticketid'].'&from='.urlencode(WEBSITE_URL.'/Support/customer_support.php?tab=requests&type='.$request_tab.'#'.$row['supportid']).'">Open '.TICKET_NOUN.' #'.$row['ticketid'].'</a>';
					} else {
						echo '<button value="'.$row['supportid'].'" class="btn brand-btn pull-right" onclick="create_ticket(this); return false;">Create and Edit '.TICKET_NOUN.'</button>';
					}
				}
				echo '</span>';
				echo '<input type="text" name="reply_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control" />';
				echo '<input type="text" name="checklist_time_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control timepicker" />';
				echo '<input type="text" name="reminder_'.$row['supportid'].'" style="display:none; margin-top: 2em;" class="form-control datepicker" />';
				echo '<input type="file" name="attach_'.$row['supportid'].'" style="display:none;" class="form-control" />';
				echo '<br /><span class="display-field"><b>Date of Request: '.$row['current_date']."</b><br />Support Request #".$row['supportid']."<br />".$row['heading']."<hr>".html_entity_decode($row['message']).'</span>';
				$documents = mysqli_query($dbc, "SELECT * FROM support_uploads WHERE supportid='".$row['supportid']."'");
				while($doc = mysqli_fetch_array($documents)) {
					$link = $doc['document'];
					echo '<a href="'.$link.'">'.$link.' (Attached by '.$doc['created_by'].' on '.$doc['created_date'].')</a><br />';
				}
				echo '</span></li>';
			} ?>
		</ul>
	<?php } else {
		echo "<h3>No ".($request_tab == 'feedback' ? 'Feedback & Ideas' : ($request_tab == 'requests' ? 'Support Requests' : 'Critical Incidents'))." Found</h3>";
	} ?>
<?php endif; ?>
<script>
function assign_staff(support) {
	support_id = $(support).parents('span').data('support');
	staff_id = $(support).val();
	$.ajax({
		method: 'POST',
		url: 'support_ajax.php?fill=assign',
		data: { id: support_id, staff: staff_id },
		complete: function(result) { console.log(result.responseText); }
	});
}
function send_email(support) {
	if(confirm("Please confirm that you wish to send an email to the <?= ($user_category == 'Staff' ? 'user' : 'support staff') ?>.")) {
		support_id = $(support).parents('span').data('support');
		$.ajax({
			method: 'POST',
			url: 'support_ajax.php?fill=email',
			data: { id: support_id, user_category: '<?= $user_category ?>' },
			complete: function(result) { console.log(result.responseText); }
		});
	}
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
function create_ticket(support) {
	$.ajax({
		method: "POST",
		url: "support_ajax.php?fill=create_ticket",
		data: { id: support.value },
		complete: function(result) {
			window.location.href = "../Ticket/index.php?edit="+result.responseText+"&from=<?= urlencode(WEBSITE_URL.'/Support/customer_support.php?tab=requests&type=active#') ?>"+support.value;
		}
	});
}
</script>