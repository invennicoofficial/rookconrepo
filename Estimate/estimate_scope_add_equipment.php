<?php include_once('../include.php'); ?>
<script type="text/javascript">
$(document).on('change','select[name="category"]',function() { filterInventory(); });
function filterInventory() {
	$('.inventory_options').html('Loading...').show();
	var category = $('[name="category"]').val();
	$.ajax({
		url: 'estimates_ajax.php?action=equip_list&category='+category,
		method: 'GET',
		dataType: 'html',
		success:function(response) {
			$('.inventory_options').html(response).show();
		}
	});
}
</script>
<div class="form-group">
	<label class="col-sm-4">Category:</label>
	<div class="col-sm-8">
		<select name="category" data-placeholder="Select a Category..." class="chosen-select-deselect form-control">
			<option></option>
			<?php $categories = $dbc->query("SELECT `category` FROM `equipment` WHERE `deleted`=0 GROUP BY `category`");
			while($category = $categories->fetch_assoc()) { ?>
				<option value="<?= $category['category'] ?>"><?= $category['category'] ?></option>
			<?php } ?>
		</select>
	</div>
</div>
<div class="form-group inventory_options" style="display:none;">
</div>