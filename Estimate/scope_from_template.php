<?php include_once('../include.php');
checkAuthorised('estimate');
error_reporting(0);
if(!isset($estimate)) {
	$estimateid = filter_var($_GET['estimateid'],FILTER_SANITIZE_STRING);
	$estimate = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `estimate` WHERE `estimateid`='$estimateid'"));
} ?>
<script>
$(document).ready(function() {
	$('select[name="scope_template"]').change(filterRates);
	$('select[name="scope_rate"]').change(getScope);
});
function filterRates() {
	$('[name=scope_rate]').find('option').show();
	if($('[name=scope_template]').val() != '') {
		$('[name=scope_rate]').find('option').filter('[data-template]:not([data-template='+$('[name=scope_template]').val()+'])').hide();
	}
	$('[name=scope_rate]').trigger('change.select2');
	getScope();
}
function applyTemplate() {
	var rates = [];
	$('[name=scope_rate]').each(function() {
		rates.push(this.value);
	});
	var include = [0];
	$('[name=include]:checked').each(function() {
		include.push(this.value);
	});
	$.ajax({
		url: 'estimates_ajax.php?action=apply_template',
		method: 'POST',
		data: {
			template: $('[name=scope_template]').val(),
			rate: rates,
			include: include.join(','),
			target: <?= $estimateid ?>
		},
		success: function(response) {
			$(top.document).find('.iframe_overlay').hide();
			$(top.document).find('.iframe_overlay .iframe iframe').off('load').attr('src', '');
		}
	});
}
function addRate() {
	var row = $('[name=scope_rate]').last().closest('.form-group');
	var clone = row.clone();
	resetChosen(clone.find("select[class^=chosen]"));
	row.after(clone);
}
function removeRate(img) {
	if($('[name=scope_rate]').length <= 1) {
		addRate();
	}
	$(img).closest('.form-group').remove();
}
function getScope() {
	$.ajax({
		url: 'estimates_ajax.php?action=list_template_scope',
		method: 'POST',
		data: {
			template: $('[name=scope_template]').val()
		},
		success: function(response) {
			$('.scope_details').html(response);
		}
	});
}
</script>
<div class="col-sm-12">
	<h3>Templates<a href="" class="pull-right"><img class="inline-img" src="../img/icons/close.png"></a></h3>
	<div class="form-group">
		<label class="col-sm-4">Scope Template:</label>
		<div class="col-sm-8">
			<select name="scope_template" class="chosen-select-deselect">
				<option></option>
				<?php $templates = mysqli_query($dbc, "SELECT `id`, `template_name` FROM `estimate_templates` ORDER BY `template_name`");
				while($template = mysqli_fetch_array($templates)) { ?>
					<option value="<?= $template['id'] ?>"><?= $template['template_name'] ?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-4">Rate Card:</label>
		<div class="col-sm-7">
			<select name="scope_rate" class="chosen-select-deselect">
				<option></option>
				<?php $rates = mysqli_query($dbc, "SELECT MIN(`companyrcid`) id, CONCAT(`rate_card_name`,IF(`rate_card_types`!='',CONCAT(': ',`rate_card_types`),'')) rate_name FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name`!='' AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31') GROUP BY `rate_name` ORDER BY `rate_name`");
				while($rate = mysqli_fetch_array($rates)) { ?>
					<option value="COMPANY:<?= $rate['id'] ?>">Company Rate: <?= $rate['rate_name'] ?></option>
				<?php } ?>
				<?php $rates = mysqli_query($dbc, "SELECT `rate_card_estimate_scopes`.`id`, `rate_card_estimate_scopes`.`template_id`, CONCAT(`estimate_templates`.`template_name`,': ',`rate_card_estimate_scopes`.`rate_card_name`) rate_name FROM `rate_card_estimate_scopes` LEFT JOIN `estimate_templates` ON `rate_card_estimate_scopes`.`template_id`=`estimate_templates`.`id` WHERE `rate_card_estimate_scopes`.`deleted`=0 AND `estimate_templates`.`deleted`=0");
				while($rate = mysqli_fetch_array($rates)) { ?>
					<option data-template="<?= $rate['template_id'] ?>" value="SCOPE:<?= $rate['id'] ?>">Scope Rate: <?= $rate['rate_name'] ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-sm-1">
			<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="addRate();">
			<img src="../img/remove.png" class="inline-img pull-right" onclick="removeRate(this);">
		</div>
	</div>
	<div class="form-group">
		<button class="btn brand-btn pull-right" onclick="applyTemplate();">Add Selected Details to Scope</button>
	</div>
	<div class="scope_details col-sm-12" id="no-more-tables"></div>
	<div class="clearfix"></div>
	<div class="form-group">
		<button class="btn brand-btn pull-right" onclick="applyTemplate();">Add Selected Details to Scope</button>
	</div>
</div>