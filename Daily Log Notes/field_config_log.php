<div class='tile-sidebar hide-titles-mob collapsible' style='padding-left: 15px;'>
	<ul>
		<a href="?settings=tabs"><li class="<?= $_GET['settings'] == 'tabs' ? 'active blue' : '' ?>">Tabs</li></a>
	</ul>
</div>
<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen form-horizontal'>
		<?php $categories = explode(',',get_config($dbc, 'log_note_categories'));
		$tab_mode = get_config($dbc, 'log_note_tabs'); ?>
		<script>
		$(document).ready(function() {
			$('input').change(saveTypes);
		});
		function saveTypes() {
			var categories = [];
			$('[name=categories]:checked').each(function() {
				categories.push(this.value);
			});
			var tab_mode = $('[name=tab_mode]:checked').val();
			$.ajax({
				url: 'log_note_ajax.php?action=settings_tabs',
				method: 'POST',
				data: {
					categories: categories,
					tab_mode: tab_mode
				},success:function(response) {}
			});
		}
		</script>
		<h3>Contact Categories</h3>
		<div class="form-group">
			<label class="col-sm-4">Contact Categories:<br /><em>Select the categories for which you wish to have Daily Log Notes</em></label>
			<div class="col-sm-8">
				<?php foreach(array_unique(explode(',',get_config($dbc, 'all_contact_tabs'))) as $category) { ?>
					<label class="form-checkbox"><input type="checkbox" <?= in_array($category,$categories) ? 'checked' : '' ?> name="categories" value="<?= $category ?>"><?= $category ?></label>
				<?php } ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Contact Select Mode:</label>
			<div class="col-sm-8">
				<label class="form-checkbox-any pad-horizontal"><input type="radio" <?= $tab_mode != 'dropdown' ? 'checked' : '' ?> name="tab_mode" value="tabs">View Contacts as Tabs</label>
				<label class="form-checkbox-any pad-horizontal"><input type="radio" <?= $tab_mode == 'dropdown' ? 'checked' : '' ?> name="tab_mode" value="dropdown">Use Dropdown to Select Contacts</label>
			</div>
		</div>
	</div>
</div>