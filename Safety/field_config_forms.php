<script>
$(document).ready(function() {
	$('.form-group input').change(enableForm);
	$('.block-group input').change(saveFields);
});
function enableForm() {
	var i = $(this).data('i');
	if(this.checked) {
		$('#config_block_'+i).show();
	} else {
		$('#config_block_'+i).hide().find('[name=deleted]').val(1).change();
	}
}
function saveFields() {
	var value = this.value;
	if($(this).prop('type') == 'checkbox') {
		if(!$(this).is(':checked')) {
			value = '';
		}
	}
	$.ajax({
		url: 'safety_ajax.php?action=form_layout',
		method: 'POST',
		data: {
			safetyid: $(this).closest('.block-group').data('safetyid'),
			name: this.name,
			value: value
		}
	});
}
function get_form_config(block) {
	block.html('<h4><em>Loading...</em></h4>');
	if(!(block.data('safetyid') > 0)) {
		$.ajax({
			url: 'safety_ajax.php?action=form_layout',
			method: 'POST',
			data: {
				form: block.data('form-name'),
				user_form_id: block.data('userformid')
			},
			success: function(response) {
				if(response > 0) {
					block.data('safetyid',response);
				}
			}
		});
	}
	load_block(block);
}
function unload_block(link) {
	var block = $(link).closest('label').next('.block-group');
	$(link).remove();
	block.html('<a href="" onclick="get_form_config($(this).closest(\'.block-group\')); return false;">Configure Form</a>');
}
function load_block(block) {
	if(block.data('safetyid') > 0) {
		$.ajax({
			url: 'safety_config.php?safetyid='+block.data('safetyid'),
			method: 'POST',
			data: {
				form_name: block.data('form-name')
			},
			success: function(response) {
				block.prev('label').append(' <small><em><a href="" onclick="unload_block(this); return false;">Hide Form Settings</a></em></small>');
				block.html(response);
				initInputs('#'+block.attr('id'));
				$('#'+block.attr('id')).find('input,select,textarea').change(saveFields);
			}
		});
	} else {
		setTimeout(function() { load_block(block); }, 250);
	}
}
function changeCategory(category) {
	$.ajax({
		url: 'safety_ajax.php?action=set_category',
		method: 'POST',
		data: {
			category: category
		},
		success: function(response) {
			$('[name=heading_number]').html(response).trigger('change.select2');
		}
	});
}
function changeSection(section) {
	$.ajax({
		url: 'safety_ajax.php?action=set_form_section',
		method: 'POST',
		data: {
			section: section,
			category: $('[name=category]').val()
		},
		success: function(response) {
			response = response.split('#*#');
			$('[name=heading]').val(response[0]);
			$('[name=sub_heading_number]').html(response[1]).trigger('change.select2');
		}
	});
}
function changeSubSection(subsection) {
	$.ajax({
		url: 'safety_ajax.php?action=set_form_subsection',
		method: 'POST',
		data: {
			subsection: subsection,
			category: $('[name=category]').val()
		},
		success: function(response) {
			response = response.split('#*#');
			$('[name=sub_heading]').val(response[0]);
			$('[name=third_heading_number]').html(response[1]).trigger('change.select2');
		}
	});
}
</script>
<?php $safety_forms = [];
$safety_forms[] = ["Field Level Hazard Assessment"];
$safety_forms[] = ["Hydrera Site Specific Pre Job Safety Meeting Hazard Assessment"];
$safety_forms[] = ["Weekly Safety Meeting"];
$safety_forms[] = ["Tailgate Safety Meeting"];
$safety_forms[] = ["Toolbox Safety Meeting"];
$safety_forms[] = ["Daily Equipment Inspection Checklist"];
$safety_forms[] = ["AVS Hazard Identification"];
$safety_forms[] = ["AVS Near Miss"];
$safety_forms[] = ["Incident Investigation Report"];
$safety_forms[] = ["Follow Up Incident Report"];
$safety_forms[] = ["Pre Job Hazard Assessment"];
$safety_forms[] = ["Monthly Site Safety Inspections"];
$safety_forms[] = ["Monthly Office Safety Inspections"];
$safety_forms[] = ["Monthly Health and Safety Summary"];
$safety_forms[] = ["Trailer Inspection Checklist"];
$safety_forms[] = ["Employee Misconduct Form"];
$safety_forms[] = ["Site Inspection Hazard Assessment"];
$safety_forms[] = ["Weekly Planned Inspection Checklist"];
$safety_forms[] = ["Equipment Inspection Checklist"];
$safety_forms[] = ["Employee Equipment Training Record"];
$safety_forms[] = ["Vehicle Inspection Checklist"];
$safety_forms[] = ["Safety Meeting Minutes"];
$safety_forms[] = ["Vehicle Damage Report"];
$safety_forms[] = ["Fall Protection Plan"];
$safety_forms[] = ["Spill Incident Report"];
$safety_forms[] = ["General Site Safety Inspection"];
$safety_forms[] = ["Confined Space Entry Permit"];
$safety_forms[] = ["Lanyards Inspection Checklist Log"];
$safety_forms[] = ["On The Job Training Record"];
$safety_forms[] = ["General Office Safety Inspection"];
$safety_forms[] = ["Full Body Harness Inspection Checklist Log"];
$safety_forms[] = ["Confined Space Pre Entry Checklist"];
$safety_forms[] = ["Confined Space Entry Log"];
$safety_forms[] = ["Emergency Response Transportation Plan"];
$safety_forms[] = ["Hazard Id Report"];
$safety_forms[] = ["Dangerous Goods Shipping Document"];
$safety_forms[] = ["Safe Work Permit"];
$safety_forms[] = ["Journey Management - Trip Tracking Form"];
$query = mysqli_query ($dbc, "SELECT * FROM `user_forms` WHERE CONCAT(',',`assigned_tile`,',') LIKE '%,safety,%'");
while ($row = mysqli_fetch_array($query)) {
	$safety_forms[] = [$row['form_id'],$row['name']];
}

foreach($safety_forms as $i => $form) {
	$form_name = isset($form[1]) ? $form[1] : $form[0];
	$user_form_id = isset($form[1]) ? $form[0] : '';
	$field_config = explode(',',get_config($dbc, 'safety_fields'));
	$safety = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM `safety` WHERE `form`='$form_name' AND `deleted`=0"));
	$fields = explode(',',$safety['fields']); ?>
	<div class="form-group">
		<label class="col-sm-4 control-label"><?= $form_name ?></label>
		<label class="form-checkbox-any-width col-sm-8"><input <?= $safety['safetyid'] > 0 ? 'checked' : '' ?> data-i="<?= $i ?>" type="checkbox">Enable<?= $safety['safetyid'] > 0 ? ': '.($safety['third_heading_number'] != '' ? $safety['third_heading_number'].' '.$safety['third_heading'] : ($safety['sub_heading_number'] != '' ? $safety['sub_heading_number'].' '.$safety['sub_heading'] : $safety['heading_number'].' '.$safety['heading'])).' ('.$safety['tab'].')' : '' ?></label>
		<div class="block-group col-sm-8 pull-right safety_form" id="config_block_<?= $i ?>" data-form-name="<?= $form_name ?>" data-userformid="<?= $user_form_id ?>" data-safetyid="<?= $safety['safetyid'] ?>" style="<?= $safety['safetyid'] > 0 ? '' : 'display:none;' ?>">
			<a href="" onclick="overlayIFrameSlider('safety_form.php?action=edit&safetyid=<?= $safety['safetyid'] ?>&tab=<?= $safety['tab'] ?>&form=<?= $form_name ?>', 'auto', false, false, 'auto', true); return false;">Configure Form</a>
			<input type="hidden" name="deleted" value="0">
		</div>
	</div>
<?php } ?>