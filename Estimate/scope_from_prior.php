<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
if(!isset($estimate)) {
	$estimateid = filter_var($_GET['estimateid'],FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
} ?>
<script>
$(document).on('change', 'select[name="prior_business"]', function() { filterPrior(); });
$(document).on('change', 'select[name="prior_contact"]', function() { filterPrior(); });
$(document).on('change', 'select[name="prior_site"]', function() { filterPrior(); });
$(document).on('change', 'select[name="prior_estimate"]', function() { getScope(); });
function filterPrior() {
	$('[name=prior_estimate]').find('option').show();
	if($('[name=prior_business]').val() != '') {
		$('[name=prior_estimate]').find('option').filter('[data-businessid]:not([data-businessid='+$('[name=prior_business]').val()+'])').hide();
	}
	if($('[name=prior_contact]').val() != '') {
		$('[name=prior_estimate]').find('option').filter('[data-clientid]:not([data-clientid='+$('[name=prior_contact]').val()+'])').hide();
	}
	if($('[name=prior_site]').val() != '') {
		$('[name=prior_estimate]').find('option').filter('[data-siteid]:not([data-siteid='+$('[name=prior_site]').val()+'])').hide();
	}
	$('[name=prior_estimate]').trigger('change.select2');
}
function getScope() {
	$.ajax({
		url: 'estimates_ajax.php?action=list_estimate_scope',
		method: 'POST',
		data: {
			src: $('[name=prior_estimate]').val()
		},
		success: function(response) {
			$('.scope_details').html(response);
		}
	});
}
function copyEstimate() {
	var include = [0];
	$('[name=include]:checked').each(function() {
		include.push(this.value);
	});
	$.ajax({
		url: 'estimates_ajax.php?action=copy_estimate',
		method: 'POST',
		data: {
			include: include.join(','),
			src: $('[name=prior_estimate]').val(),
			target: <?= $estimateid ?>
		},
		success: function(response) {
			$(top.document).find('.iframe_overlay').hide();
			$(top.document).find('.iframe_overlay .iframe iframe').off('load').attr('src', '');
		}
	});
}
</script>
<div class="col-sm-12">
	<h3>Previous Estimates<a href="" class="pull-right"><img class="inline-img" src="../img/icons/close.png"></a></h3>
	<?php if(in_array('Business',$config)) { ?>
		<div class="form-group">
			<label class="col-sm-4">Business:</label>
			<div class="col-sm-8">
				<select name="prior_business" class="chosen-select-deselect">
					<option></option>
					<?php $prior_bus = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `name` FROM `contacts` WHERE `contactid` IN (SELECT `businessid` FROM `estimate` WHERE `deleted`=0)"));
					foreach($prior_bus as $business) { ?>
						<option value="<?= $business['contactid'] ?>"><?= $business['name'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php } ?>
	<?php if(in_array('Contact',$config)) { ?>
		<div class="form-group">
			<label class="col-sm-4">Contact:</label>
			<div class="col-sm-8">
				<select name="prior_contact" class="chosen-select-deselect">
					<option></option>
					<?php $prior_contacts = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `contactid` IN (SELECT `clientid` FROM `estimate` WHERE `deleted`=0)"));
					foreach($prior_contacts as $contact) { ?>
						<option value="<?= $contact['contactid'] ?>"><?= $contact['first_name'].' '.$contact['last_name'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php } ?>
	<?php if(in_array('Site',$config)) { ?>
		<div class="form-group">
			<label class="col-sm-4">Site:</label>
			<div class="col-sm-8">
				<select name="prior_site" class="chosen-select-deselect">
					<option></option>
					<?php $prior_sites = sort_contacts_query(mysqli_query($dbc, "SELECT `contactid`, `site_name` FROM `contacts` WHERE `contactid` IN (SELECT `siteid` FROM `estimate` WHERE `deleted`=0)"));
					foreach($prior_sites as $site) { ?>
						<option value="<?= $site['contactid'] ?>"><?= $site['site_name'] ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	<?php } ?>
	<div class="form-group">
		<label class="col-sm-4">Estimate:</label>
		<div class="col-sm-8">
			<select name="prior_estimate" class="chosen-select-deselect">
				<option></option>
				<?php $priors = mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `deleted`=0");
				while($prior = mysqli_fetch_array($priors)) { ?>
					<option data-businessid="<?= $prior['businessid'] ?>" data-clientid="<?= $prior['clientid'] ?>" data-siteid="<?= $prior['siteid'] ?>" value="<?= $prior['estimateid'] ?>"><?= $prior['estimate_name'] ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<button class="btn brand-btn pull-right" onclick="copyEstimate();">Add Selected Details to Scope</button>
	</div>
	<div class="scope_details col-sm-12" id="no-more-tables"></div>
	<div class="clearfix"></div>
	<div class="form-group">
		<button class="btn brand-btn pull-right" onclick="copyEstimate();">Add Selected Details to Scope</button>
	</div>
</div>