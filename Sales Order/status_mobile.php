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
<div id="status_accordions" class="sidebar show-on-mob panel-group block-panels col-xs-12" <?= $_GET['p']=='preview' && !empty($_GET['id']) ? 'style="display: none;"' : '' ?>>
	<?php foreach(explode(',', $statuses) as $i => $status) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#status_accordions" href="#collapse_<?= $i ?>">
						<?= $status ?><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_<?= $i ?>" class="panel-collapse collapse">
				<div class="panel-body" data-file="status.php?s=<?= $status ?>">
					Loading...
				</div>
			</div>
		</div>
	<?php } ?>
</div><?php

if ( $_GET['p']=='preview' && !empty($_GET['id']) ) {
    include('preview.php');
}