<!-- Staff Information -->
<script type="text/javascript">
function addStaff(staffid = '') {
	var staff_block = $('.row_staff').last();
	var clone = staff_block.clone();

	clone.find('.form-control').val('');
	clone.find('[name="assign_staff[]"]').val(staffid);
    clone.find('.form-control').trigger('change.select2');
    resetChosen(clone.find('select'));
    staff_block.after(clone);
}
function deleteStaff(sel) {
	if($('.row_staff').length <= 1) {
		addStaff();
	}
	$(sel).closest('.row_staff').remove();
}
function assignStaffGroup(sel) {
	var staffids = $(sel).val().split(',');
	if (staffids.indexOf($('[name="primary_staff"]').find('option:selected').val()) != -1) {
			var index = staffids.indexOf($('[name="primary_staff"]').find('option:selected').val());
			staffids.splice(index, 1);
	}
	$('[name="assign_staff[]"]').each(function() {
		if (staffids.indexOf($(this).find('option:selected').val()) != -1) {
			var index = staffids.indexOf($(this).find('option:selected').val());
			staffids.splice(index, 1);
		}
	});
	staffids.forEach(function(staffid) {
		addStaff(staffid);
	});
}
</script>
<div class="accordion-block-details padded" id="staff_information">
    <div class="accordion-block-details-heading"><h4>Staff Information</h4></div>
    <?php if (strpos($value_config, ',Primary Staff,') !== FALSE) { ?>
	    <div class="row">
	        <div class="row set-row-height">
		        <div class="col-xs-12 col-sm-3 gap-md-left-15">Primary Staff:</div>
		        <div class="col-xs-12 col-sm-7">
	            	<select data-placeholder="Select a Staff..." name="primary_staff" id="primary_staff" class="chosen-select-deselect form-control">
	            		<option></option>
	            		<?php $staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
	            		foreach ($staff_list as $id) {
	            			if(empty($primary_staff)) {
	            				$primary_staff = $_SESSION['contactid'];
	            			}
	            			echo '<option '.($primary_staff == $id ? 'selected' : '').' value="'.$id.'">'.get_contact($dbc, $id).'</option>';
	            		} ?>
	            	</select>
	            </div>
	        </div>
	    </div><?php
	}
    if (strpos($value_config, ',Assign Staff,') !== FALSE) {
		$assign_staff = explode(',', $assign_staff);
		foreach ($assign_staff as $staffid) { ?>
		    <div class="row row_staff">
		        <div class="row set-row-height">
			        <div class="col-xs-12 col-sm-3 gap-md-left-15">Assign Staff:</div>
			        <div class="col-xs-12 col-sm-7">
		            	<select data-placeholder="Select a Staff..." name="assign_staff[]" class="chosen-select-deselect form-control">
		            		<option></option>
		            		<?php $staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
		            		foreach ($staff_list as $id) {
		            			echo '<option '.($staffid == $id ? 'selected' : '').' value="'.$id.'">'.get_contact($dbc, $id).'</option>';
		            		} ?>
		            	</select>
		            </div>
		            <div class="col-sm-1 pull-right">
		            	<a href="#" onclick="deleteStaff(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>&nbsp;&nbsp;<a href="#" class="add_staff" onclick="addStaff(); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
		            </div>
		        </div>
		    </div><?php
		}
	}
    if (strpos($value_config, ',Staff Collaboration Groups,') !== FALSE) {
		$staff_groups = get_config($dbc, 'sales_order_staff_groups');
		if (!empty($staff_groups)) {
			$staff_groups = explode('*#*', $staff_groups);
			foreach ($staff_groups as $staff_group) {
				$group_staff = explode(',',$staff_group);
				$group_name = $group_staff[0];
				unset($group_staff[0]);
				$group_name_text = 'Assign '.$group_name.': ';
				foreach ($group_staff as $id) {
					$group_name_text .= get_contact($dbc, $id).', ';
				}
				$group_name_text = rtrim($group_name_text, ', '); ?>
				<button onclick="assignStaffGroup(this); return false;" value="<?= implode(',', $group_staff) ?>" class="btn brand-btn pull-right"><?= $group_name_text ?></button>
				<div class="clearfix"></div>
			<?php }
		}
	} ?>
</div>