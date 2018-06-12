<script>
$(document).ready(function() {
	$('[name="companyrccat[]"]').each(function() {
		filter_categories(this);
	});
});
$(document).on('change', 'select[name="companyrcid[]"]', function() { load_types(this); });

function filter_categories(sel) {
	category = [];
	$(sel).find('option:selected').each(function() {
		category.push(this.value);
	});
	var filter_select = $('[name="companyrcid[]"]');
	var current_val = filter_select.val();
	filter_select.find('option').each(function() {
		var cat = $(this).data('category');
		if(cat == undefined || category.length == 0 || $.inArray(cat, category) >= 0) {
			$(this).show();
		} else {
			$(this).hide();
			if(this.value == current_val) {
				filter_select.val('');
			}
		}
	});
	filter_select.trigger('change.select2');
}

function add_rate(btn) {
	var group = $(btn).closest('div').find('.form-group').first().clone();
	group.find('select').val('');
	group.find('[name="ratecardtype[]"]').empty();
	resetChosen(group.find('select'));
	$(btn).before(group);
}

function load_types(sel) {
	var id = sel.value;
	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=rate_card_types&rate_card="+id,
		dataType: "html",   //expect html to be returned
		success: function(response){
			$(sel).closest('.form-group').find('[name="ratecardtype[]"]').html(response);
			$(sel).closest('.form-group').find('[name="ratecardtype[]"]').trigger("change.select2");
		}
	});
}
</script>
<?php $rate_card_tabs = ','.get_config($dbc, 'rate_card_tabs').',';
if(strpos($rate_card_tabs, ',company,') !== false):
	echo "<div>";
	$company_rate_card_list = explode(',',$companyrcid);
	$rate_card_type_list = explode(',',$ratecardtypes);
	foreach($company_rate_card_list as $rate_card_row => $rate_card_id) { ?>
		<div class="form-group clearfix">
			<!--<?php if(strpos(get_config($dbc,'company_rate_fields'),',category,') !== FALSE): ?>
				<label for="first_name" class="col-sm-4 control-label text-right">Company Rate Category:</label>
				<div class="col-sm-8">
					<select name="companyrccat[]" <?php echo $disable_rc; ?> data-placeholder="Select a Rate Card" onchange="filter_categories(this);" class="chosen-select-deselect form-control" width="380">
						<option value=''></option>
						<?php
						$query = mysqli_query($dbc,"SELECT companyrcid, IFNULL(`rate_categories`,'') rate_categories FROM company_rate_card WHERE `rate_card_name` != '' AND `deleted`=0 GROUP BY rate_categories");
						while($row = mysqli_fetch_array($query)) {
							echo "<option ".($company_rate_categories == $row['rate_categories'] ? 'selected' : '')." value='". $row['rate_categories']."'>".$row['rate_categories'].'</option>';
						}
						?>
					</select>
				</div>
			<?php endif; ?>-->
			<label for="first_name" class="col-sm-4 control-label text-right">Company Rate Card:</label>
			<div class="col-sm-8">
				<select name="companyrcid[]" <?php echo $disable_rc; ?> data-placeholder="Select a Rate Card" class="chosen-select-deselect form-control" width="380">
					<option value=''></option>
					<?php
					$query = mysqli_query($dbc,"SELECT companyrcid, rate_card_name, IFNULL(`rate_categories`,'') rate_categories FROM company_rate_card WHERE `rate_card_name` != '' AND `deleted`=0 GROUP BY rate_card_name, rate_categories");
					while($row = mysqli_fetch_array($query)) {
						echo "<option data-category='".$row['rate_categories']."' ".($company_rate_card_name == $row['rate_card_name'] ? 'selected' : '')." value='". $row['companyrcid']."'>".($row['rate_categories'] != '' ? $row['rate_categories'].': ' : '').$row['rate_card_name'].'</option>';
					}
					?>
				</select>
			</div>
			<label for="first_name" class="col-sm-4 control-label text-right">Rate Card Types:</label>
			<div class="col-sm-8">
				<select name="ratecardtype[]" <?php echo $disable_rc; ?> data-placeholder="Select a Rate Card Type" class="chosen-select-deselect form-control" width="380">
					<option value=' '></option>
					<?php if($rate_card_id != '') {
						$query = mysqli_query($dbc,"SELECT DISTINCT `rate_card_types` FROM `company_rate_card` WHERE `deleted`=0");
						while($row = mysqli_fetch_array($query)) {
							echo "<option ".(trim($row['rate_card_types']) == trim($rate_card_type_list[$rate_card_row]) ? 'selected' : '')." value='". $row['rate_card_types']."'>".$row['rate_card_types'].'</option>';
						}
					} ?>
				</select>
			</div>
		</div>
		<?php if($disable_rc == 'disabled') {
			echo "<input type='hidden' name='companyrcid[]' value='$rate_card_id'>";
			echo "<input type='hidden' name='ratecardtype[]' value='".trim($rate_card_type_list[$rate_card_row])."'>";
		} ?>
	<?php } ?>
	<button class="btn brand-btn pull-right" onclick="add_rate(this); return false;">Add</button>
	<div class="clearfix"></div>
	</div>
<?php endif; ?>

<?php if(strpos($rate_card_tabs, ',customer,') !== false): ?>
	<div class="form-group clearfix">
		<label for="first_name" class="col-sm-4 control-label text-right">Cutomer Specific Rate Card:</label>
		<div class="col-sm-8">
			<select name="ratecardid" <?php echo $disable_rc; ?> id="ratecardid" data-placeholder="Select a Customer Rate Card..." class="chosen-select-deselect form-control" width="380">
				<option value=''></option>
				<?php
				$query = mysqli_query($dbc,"SELECT ratecardid, rate_card_name FROM rate_card WHERE on_off=1 AND `deleted`=0 ORDER BY rate_card_name");
				while($row = mysqli_fetch_array($query)) {
					if ($ratecardid == $row['ratecardid']) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					echo "<option ".$selected." value='". $row['ratecardid']."'>".$row['rate_card_name'].'</option>';
				}
				?>
			</select>
		</div>
	</div>
<?php endif; ?>