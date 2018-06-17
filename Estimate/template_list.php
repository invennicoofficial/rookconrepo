<script>
var contact_type = '';
$(document).ready(function() {
	$('.panel-heading').click(loadPanel);
});
function loadPanel() {
	$('.panel-body').html('Loading...');
	body = $(this).closest('.panel').find('.panel-body');
	$.ajax({
		url: 'template_edit.php?template='+$(body).data('id'),
		response: 'html',
		success: function(response) {
			$(body).html(response);
		}
	});
}
function removeTemplate(id) {
	$.ajax({
		url: 'estimates_ajax.php?action=save_template_field',
		method: 'POST',
		data: {
			table_name: 'estimate_templates',
			field_name: 'deleted',
			value: 1,
			template_id: id
		}
	});
}
</script>
<div id='template_accordion' class='sidebar show-on-mob panel-group block-panels col-xs-12'>
	<?php $template_list = mysqli_query($dbc, "SELECT `id`, `template_name` FROM `estimate_templates` WHERE `deleted`=0");
	while($template = mysqli_fetch_array($template_list)) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#template_accordion" href="#collapse_<?= $template['id'] ?>">
						<?= $template['template_name'] ?><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_<?= $template['id'] ?>" class="panel-collapse collapse">
				<div class="panel-body" data-id="<?= $template['id'] ?>">
					Loading...
				</div>
			</div>
		</div>
	<?php } ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#template_accordion" href="#collapse_new">
					Create New Template<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_new" class="panel-collapse collapse">
			<div class="panel-body" data-id="new">
				Loading...
			</div>
		</div>
	</div>
</div>

<div class="tile-sidebar hide-titles-mob standard-collapsible">
    <ul>
        <li>Scope Templates</li>
        <?php $template_list = mysqli_query($dbc, "SELECT `id`, `template_name` FROM `estimate_templates` WHERE `deleted`=0");
        while($template = mysqli_fetch_array($template_list)) { ?>
            <a href="?template=<?= $template['id'] ?>"><li class="<?= $_GET['template'] == $template['id'] ? 'active blue' : '' ?>"><?= $template['template_name'] ?><img src="../img/remove.png" class="pull-right inline-img" onclick="removeTemplate(<?= $template['id'] ?>); $(this).closest('a').hide(); return false;"></li></a>
        <?php } ?>
        <a href="?template=new"><li class="<?= $_GET['template'] == 'new' ? 'active blue' : '' ?>">Create New Template</li></a>
        <a href="?template=copy_new"><li class="<?= $_GET['template'] == 'copy_new' ? 'active blue' : '' ?>">Copy <?= ESTIMATE_TILE ?> into New Template</li></a>
    </ul>
</div>

<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen'>
		<?php if($_GET['template'] == 'copy_new') {
			include('template_copy.php');
		} else {
			include('template_edit.php');
		} ?>
	</div>
</div>