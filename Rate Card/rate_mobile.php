<script>
$(document).ready(function() {
	$('.panel-heading').click(loadPanel);
	$('[name=new_rate_type]').change(loadPanel);
});
function loadPanel() {
	var target = $(this).closest('.panel').find('.panel-body');
	if($(this).is('select')) {
		if(this.value == '') {
			$('#new_rate_div').html('Please select a type of rate card.');
		} else {
			$.ajax({
				url: 'edit_rate_card.php',
				method: 'POST',
				data: {
					type: this.value,
					id: 'new'
				},
				dataType: 'html',
				success: function(response) {
					$('#new_rate_div').html(response);
				}
			});
		}
	} else if($(target).data('id') > 0) {
		$.ajax({
			url: 'edit_rate_card.php',
			method: 'POST',
			data: {
				type: $(target).data('type'),
				id: $(target).data('id')
			},
			dataType: 'html',
			success: function(response) {
				$(target).html(response);
			}
		});
	}
}
</script>
<div id='rate_accordion' class='sidebar show-on-mob panel-group block-panels col-xs-12'>
	<?php $rate_list = mysqli_query($dbc, "SELECT `rate_card_name` name, MIN(`companyrcid`) id, 'Company' type, 1 sort FROM `company_rate_card` WHERE `deleted`=0 AND `rate_card_name`!='' GROUP BY `rate_card_name` UNION
		SELECT `rate_card_name` name, `ratecardid` id, 'Customer' type, 2 sort FROM `rate_card` WHERE `deleted`=0 AND `rate_card_name` != '' UNION
		SELECT `rate_card_name` name, `id`, 'Scope Templates' type, 3 sort FROM `rate_card_estimate_scopes` WHERE `deleted`=0 UNION
		SELECT `positions`.`name`, `rate_id` id, 'Positions' type, 4 sort FROM `position_rate_table` LEFT JOIN `positions` ON `position_rate_table`.`position_id`=`positions`.`position_id` WHERE `position_rate_table`.`deleted`=0 AND `positions`.`deleted`=0 UNION
		SELECT '' name, 'staff' id, 'Staff' type, 5 sort UNION
		SELECT CONCAT(`equipment`.`category`,': ',`equipment`.`unit_number`) name, `rate_id` id, 'Equipment' type, 6 sort FROM `equipment_rate_table` LEFT JOIN `equipment` ON `equipment_rate_table`.`equipment_id`=`equipment`.`equipmentid` WHERE `equipment_rate_table`.`deleted`=0 AND `equipment`.`deleted`=0 UNION
		SELECT `category` name, `rate_id` id, 'Equipment Category' type, 7 sort FROM `category_rate_table` WHERE `deleted`=0 UNION
		SELECT CONCAT(`services`.`category`,': ',`services`.`heading`) name, `serviceratecardid` id, 'Services' type, 8 sort FROM `service_rate_card` LEFT JOIN `services` ON `service_rate_card`.`serviceid`=`services`.`serviceid` WHERE `service_rate_card`.`deleted`=0 AND `services`.`deleted`=0	 ORDER BY sort, name");
	while($rate = mysqli_fetch_array($rate_list)) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#rate_accordion" href="#collapse_<?= $rate['id'] ?>">
						<?= $rate['type'].': '.$rate['name'] ?><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_<?= $rate['id'] ?>" class="panel-collapse collapse">
				<div class="panel-body" data-type="<?= $rate['type'] ?>" data-id="<?= $rate['id'] ?>">
					Loading...
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#rate_accordion" href="#collapse_new">
					New Rate Card<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_new" class="panel-collapse collapse">
			<div class="panel-body" data-id="new">
				<label class="col-sm-4 control-label">New Rate Type</label>
				<div class="col-sm-8">
					<select name="new_rate_type" data-placeholder="Select a Rate Type" class="chosen-select-deselect"><option></option>
						<?php if(in_array('company',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'company')) { ?><option value="company">Company</option><?php } ?>
						<?php if(in_array('customer',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'customer')) { ?><option value="customer">Customer</option><?php } ?>
						<?php if(in_array('estimate',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'estimate')) { ?><option value="estimate">Scope Templates</option><?php } ?>
						<?php if(in_array('position',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'position')) { ?><option value="position">Position</option><?php } ?>
						<?php if(in_array('staff',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'staff')) { ?><option value="staff">Staff</option><?php } ?>
						<?php if(in_array('equipment',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'equipment')) { ?><option value="equipment">Equipment</option><?php } ?>
						<?php if(in_array('category',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'category')) { ?><option value="category">Category</option><?php } ?>
						<?php if(in_array('services',$tab_list) && check_subtab_persmission($dbc, 'rate_card', ROLE, 'services')) { ?><option value="services">Services</option><?php } ?>
					</select>
				</div>
				<div id="new_rate_div">Please select a type of rate card.</div>
			</div>
		</div>
	</div>
</div>