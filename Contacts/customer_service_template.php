<?php include_once('../include.php');
if(isset($_POST['save_template'])) {
	$contactid = $_POST['contactid'];
	$templateid = $_POST['templateid'];
	$template_name = $_POST['template_name'];
	$customer_serviceids = implode(',', $_POST['customer_serviceid']);
	$template = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services_service_templates` WHERE `templateid` = '$customer_template'"));
	if($template['contactid'] > 0 && $templateid > 0) {
		mysqli_query($dbc, "UPDATE `services_service_templates` SET `name` = '$template_name', `serviceid` = '$customer_serviceids' WHERE `templateid` = '$templateid'");
	} else {
		$old_templateid = $templateid;
		mysqli_query($dbc, "INSERT INTO `services_service_templates` (`contactid`, `name`, `serviceid`) VALUES ('$contactid', '$template_name', '$customer_serviceids')");
		$templateid = mysqli_insert_id($dbc);
		$customer_templates = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '$contactid'"))['service_templates'];
		$customer_templates = explode(',',$customer_templates);
		if($old_templateid > 0) {
			foreach($customer_templates as $key => $customer_templateid) {
				if($customer_templateid == $old_templateid) {
					$customer_templates[$key] = $templateid;
					break;
				}
			}
		} else {
			$customer_templates[] = $templateid;
		}
		$customer_templates = implode(',',$customer_templates);
		mysqli_query($dbc, "UPDATE `contacts` SET `service_templates` = '$customer_templates' WHERE `contactid` = '$contactid'");
	}
	echo '<script type="text/javascript"> window.parent.reload_service_table(); </script>';
}
?>
<script type="text/javascript">
function loadTemplate(sel) {
	$('.template_block').html('Loading...');
	var templateid = sel.value;
	var contactid = $('[name="contactid"]').val();
	if(templateid > 0 || templateid == 'ADD_NEW') {
		$.ajax({
			type: 'GET',
			url: '../Contacts/customer_service_template_inc.php?templateid='+templateid+'&contactid='+contactid,
			dataType: 'html',
			success: function(response) {
				destroyInputs();
				$('.template_block').html(response);
				$('#load_template').show();
				initInputs();
			}
		});
	} else {
		$('.template_block').html('No Template Selected.');
		$('#load_template').hide();
	}
}
</script>

<div class="padded">
	<h3>Customer Service Templates</h3>
	<div class="block-group" style="height: calc(100% - 8em); overflow-y: auto;">
		<form class="form-horizontal" action="" method="post">
			<input type="hidden" name="contactid" value="<?= $_GET['contactid'] ?>">
			<div class="form-group">
				<label class="col-sm-3">Template:</label>
				<div class="col-sm-9">
					<select name="templateid" id="templateid" class="chosen-select-deselect" onchange="loadTemplate(this);">
						<option></option>
						<option value="ADD_NEW">Add New Template</option>
						<?php $customer_templates = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `contactid` = '{$_GET['contactid']}'"))['service_templates'];
						if(!empty($customer_templates)) {
							foreach(explode(',',$customer_templates) as $customer_template) {
								$customer_template = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `services_service_templates` WHERE `templateid` = '$customer_template'"));
								if(!empty($customer_template)) { ?>
									<option value="<?= $customer_template['templateid'] ?>"><?= $customer_template['name'] ?></option>
								<?php }
							}
						} ?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="template_block">
				No Template Selected.
			</div>
			<div class="clearfix"></div>
			<div class="pull-right gap-top gap-right">
			    <a href="?" class="btn brand-btn">Cancel</a>
			    <button type="submit" id="load_template" name="save_template" value="Submit" class="btn brand-btn" style="display: none;">Submit</button>
			</div>
		</form>
	</div>
</div>