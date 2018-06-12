<?php include_once('../include.php');
if(isset($_POST['load_template'])) {
	$contactid = $_POST['contactid'];
	$templateid = $_POST['templateid'];
	mysqli_query($dbc, "INSERT INTO `rate_card` (`clientid`,`rate_card_name`,`when_added`,`who_added`) SELECT '$contactid','".(!empty(get_client($dbc, $contactid)) ? get_client($dbc, $contactid) : get_contact($dbc, $contactid))."','".date('Y-m-d')."','".$_SESSION['contactid']."' FROM (SELECT COUNT(*) rows FROM `rate_card` WHERE `clientid` = '$contactid' AND `deleted` = 0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')) num WHERE num.rows=0");
	$rate_card = mysqli_query($dbc, "SELECT * FROM `rate_card` WHERE `clientid` ='$contactid' AND `deleted` = 0 AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')")->fetch_assoc();
	$services = explode('**',$rate_card['services']);
	foreach($_POST['serviceid'] as $serviceid) {
		$service_exists = false;
		foreach($services as $service_line) {
			if(explode('#',$service_line)[0] > 0 && explode('#',$service_line)[0] == $serviceid) {
				$service_exists = true;
				break;
			}
		}
		if(!$service_exists) {
			$services[] = $serviceid.'##';
		}
	}
	foreach($services as $key => $service_line) {
		if($service_line == '##' || empty($service_line)) {
			unset($services[$key]);
		}
	}
	$services = implode('**', $services).'**';
	mysqli_query($dbc, "UPDATE `rate_card` SET `services` = '$services' WHERE `ratecardid` = '".$rate_card['ratecardid']."'");

	$contact_service_templates = mysqli_fetch_array(mysqli_query($dbc, "SELECT `service_templates` FROM `contacts` WHERE `contactid` = '$contactid'"))['service_templates'];
	$contact_service_templates = explode(',', $contact_service_templates);
	if(!in_array($templateid, $contact_service_templates)) {
		$contact_service_templates[] = $templateid;
	}
	$contact_service_templates = implode(',', $contact_service_templates);
	mysqli_query($dbc, "UPDATE `contacts` SET `service_templates` = '$contact_service_templates' WHERE `contactid` = '$contactid'");

	echo '<script type="text/javascript"> window.parent.reload_service_table(); </script>';
}
?>
<script type="text/javascript">
function loadTemplate(sel) {
	$('.template_block').html('Loading...');
	var templateid = sel.value;
	if(templateid > 0) {
		$.ajax({
			type: 'GET',
			url: '../Contacts/load_service_template_inc.php?templateid='+templateid,
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
	<h3>Load Service Template</h3>
	<div class="block-group" style="height: calc(100% - 8em); overflow-y: auto;">
		<form class="form-horizontal" action="" method="post">
			<input type="hidden" name="contactid" value="<?= $_GET['contactid'] ?>">
			<div class="form-group">
				<label class="col-sm-3">Template:</label>
				<div class="col-sm-9">
					<select name="templateid" id="templateid" class="chosen-select-deselect" onchange="loadTemplate(this);">
						<option></option><?php
		        		$template_list = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `services_service_templates` WHERE `deleted` = 0 AND `contactid` = 0 ORDER BY `name`"),MYSQLI_ASSOC);
		        		foreach ($template_list as $template) {
		        			echo '<option value="'.$template['templateid'].'">'.$template['name'].'</option>';
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
			    <button type="submit" id="load_template" name="load_template" value="Submit" class="btn brand-btn" style="display: none;">Submit</button>
			</div>
		</form>
	</div>
</div>