<script>
$(document).ready(function() {
	$('.panel-heading').click(loadPanel);
});
function loadPanel() {
	$('.panel-body').html('Loading...');
	body = $(this).closest('.panel').find('.panel-body');
	$.ajax({
		url: $(body).data('file'),
		method: 'POST',
		response: 'html',
		success: function(response) {
			$(body).html(response);
		}
	});
}
</script>
<div id='settings_accordions' class='sidebar show-on-mob panel-group block-panels col-xs-12'>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_contacts">
					Contact Categories<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_contacts" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_contacts.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_fields">
					Activate Fields<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_fields" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_fields.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_pdf">
					PDF Settings<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_pdf" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_pdf.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_options">
					Options<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_options" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_options.php">
				Loading...
			</div>
		</div>
	</div>
</div>
<ul class='sidebar hide-titles-mob col-sm-3' style='padding-left: 15px;'>
	<a href="?settings=contacts"><li class="<?= empty($_GET['settings']) || $_GET['settings'] == 'contacts' ? 'active blue' : '' ?>">Contact Categories</li></a>
	<a href="?settings=fields"><li class="<?= $_GET['settings'] == 'fields' ? 'active blue' : '' ?>">Activate Fields</li></a>
	<a href="?settings=pdf"><li class="<?= $_GET['settings'] == 'pdf' ? 'active blue' : '' ?>">PDF Settings</li></a>
	<a href="?settings=options"><li class="<?= $_GET['settings'] == 'options' ? 'active blue' : '' ?>">Options</li></a>
</ul>
<div class='col-sm-9 has-main-screen hide-titles-mob'>
	<div class='main-screen'>
		<?php switch($_GET['settings']) {
		case 'fields':
			include('field_config_fields.php');
			break;
		case 'options':
			include('field_config_options.php');
			break;
		case 'pdf':
			include('field_config_pdf.php');
			break;
		case 'contacts':
		default:
			include('field_config_contacts.php');
			break;
		} ?>
	</div>
</div>