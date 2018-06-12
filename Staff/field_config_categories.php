<?php include_once('../include.php');
checkAuthorised('staff'); ?>
<script>
$(document).ready(function() {
	$('[name=con_categories],[name=con_categories_hide],[name=assignable]').off('change',saveCats).change(saveCats);
});
function saveCats() {
	var cats = [];
	$('[name=con_categories]').each(function() {
		cats.push(this.value);
	});
	var cats_hide = [];
	$('[name=con_categories_hide]').each(function() {
		if($(this).is(':checked')) {
			cats_hide.push($(this).closest('.categories').find('[name="con_categories"]').val());
		}
	});
	var assign = [];
	$('[name=assignable] option:selected').each(function() {
		assign.push(this.value);
	});
	$.post('staff_ajax.php?action=staff_categories', {
		categories: cats.join(','),
		categories_hide: cats_hide.join(','),
		assignable: assign.join(',')
	});
}
function addCat() {
	var cat = $('.categories').last().clone();
	cat.find('input').val('').prop('checked', false);
	$('.categories').last().after(cat);
	$('[name=con_categories],[name=con_categories_hide]').off('change',saveCats).change(saveCats);
}
function remCat(img) {
	if($('.categories').length == 1) {
		addCat();
	}
	$(img).closest('.categories').remove();
	saveCats();
}
</script>
<div class="form-group">
	<label class="col-sm-4 control-label">Add categories in the order you want them on the category listing:</label>
	<div class="col-sm-8">
		<?php $get_config_values = array_filter(explode(',',str_replace(',,',',',str_replace('Staff','',mysqli_fetch_assoc(mysqli_query($dbc,"SELECT categories FROM field_config_contacts WHERE tab='Staff' AND `categories` IS NOT NULL"))['categories']))));
		if(empty($get_config_values)) {
			$get_config_values = [''];
		}
		$staff_categories_hide = ','.get_config($dbc, 'staff_categories_hide').',';
		foreach($get_config_values as $category) { ?>
			<div class="form-group categories">
				<div class="col-sm-7"><input name="con_categories" type="text" value="<?=$category ?>" class="form-control"/></div>
				<div class="col-sm-3"><label class="form-checkbox"><input type="checkbox" name="con_categories_hide" value="<?= $category ?>" <?= strpos($staff_categories_hide, ','.$category.',') !== FALSE ? 'checked' : '' ?>>Hide From Staff Lists</label></div>
				<div class="col-sm-2">
					<img class="inline-img pull-right" src="../img/icons/ROOK-add-icon.png" onclick="addCat();">
					<img class="inline-img pull-right" src="../img/remove.png" onclick="remCat(this);">
				</div>
			</div>
		<?php } ?>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Other Contact Categories that can be assigned as Staff:</label>
	<div class="col-sm-8">
		<?php $get_categories = explode(',',get_config($dbc, 'staff_assign_categories')); ?>
		<select name="assignable" multiple data-placeholder="Select Categories" class="chosen-select-deselect"><option />
			<?php $category_list = explode(',',get_config($dbc, 'all_contact_tabs'));
			foreach($category_list as $category) { ?>
				<option <?= in_array($category, $get_categories) ? 'selected' : '' ?> value="<?= $category ?>"><?= $category ?></option>
			<?php } ?>
		</select>
	</div>
</div>