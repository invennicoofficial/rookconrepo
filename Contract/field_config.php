<?php include_once('../include.php');
checkAuthorised('contracts');
switch($_GET['settings']) {
	case 'fields':
		$page_title = 'Contract Fields';
		break;
	default:
		$_GET['settings'] = 'tabs';
		$page_title = 'Contract Tabs';
		break;
} ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#mobile_tabs .panel-heading').click(loadPanel);
});
function loadPanel() {
	var panel = $(this).closest('.panel').find('.panel-body');
	panel.html('Loading...');
	$.ajax({
		url: panel.data('file-name'),
		method: 'POST',
		response: 'html',
		success: function(response) {
			panel.html(response);
		}
	});
}
</script>
<div class="show-on-mob panel-group block-panels col-xs-12 form-horizontal" style="background-color: #fff; padding: 0; margin-left: 5px; width: calc(100% - 10px);" id="mobile_tabs">
	<div class="panel panel-default">
		<div class="panel-heading mobile_load">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_mobile_tabs">
					Contract Tabs<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_mobile_tabs" class="panel-collapse collapse">
			<div class="panel-body" data-file-name="field_config_tabs.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading mobile_load">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#mobile_tabs" href="#collapse_mobile_fields">
					Contract Fields<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_mobile_fields" class="panel-collapse collapse">
			<div class="panel-body" data-file-name="field_config_fields.php">
				Loading...
			</div>
		</div>
	</div>
</div>
<div class="tile-sidebar sidebar sidebar-override hide-titles-mob standard-collapsible">
	<ul>
		<a href="?settings=tabs"><li class="<?= $_GET['settings'] == 'tabs' ? 'active blue' : ''  ?>">Contract Tabs</li></a>
		<a href="?settings=fields"><li class="<?= $_GET['settings'] == 'fields' ? 'active blue' : ''  ?>">Contract Fields</li></a>
	</ul>
</div>
<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen standard-body form-horizontal'>
		<div class="standard-body-title">
			<h3><?= $page_title ?></h3>
		</div>
		<div class="standard-body-content pad-top" style="padding: 5px;">
		<?php switch($_GET['settings']) {
			case 'fields':
				include('field_config_fields.php');
				break;
			default:
				include('field_config_tabs.php');
				break;
			} ?>
		</div>
	</div>
</div>