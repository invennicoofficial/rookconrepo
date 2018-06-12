<script>
$(document).ready(function() {
	$('#settings_accordions .panel-heading').click(loadPanel);
});
function loadPanel() {
	$('#settings_accordions .panel-body').html('Loading...');
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
<?php if(!empty($_GET['settings']) && $security['config'] > 0) { ?>
	<div id='settings_accordions' class='sidebar show-on-mob panel-group block-panels col-xs-12'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_tabs">
						HR Categories<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_tabs" class="panel-collapse collapse">
				<div class="panel-body" data-file="field_config_tabs.php">
					Loading...
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_fields">
						Fields<span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_fields" class="panel-collapse collapse">
				<div class="panel-body" data-file="field_config_fields.php">
					Loading...
				</div>
			</div>
		</div>
	</div>
<?php } else if(isset($_GET['fill'])) { ?>
	
<?php } else if(isset($_GET['edit'])) { ?>
	
<?php } else if(isset($_GET['reports'])) { ?>
	
<?php } else { ?>
	
<?php } ?>