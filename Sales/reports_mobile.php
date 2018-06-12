<script>
$(document).ready(function() {
	$('.panel-heading').click(function(){loadPanel('', this)});
});
function loadPanel(url, heading) {
	$('.panel-body').html('Loading...');
	body = $(heading).closest('.panel').find('.panel-body');
    
    if (url=='') {
        url = $(body).data('file');
    }
	
    $.ajax({
		url: url,
		method: 'POST',
		response: 'html',
		success: function(response) {
			$(body).html(response);
            $(body).find('.mobile-anchor').click(function() {
                var body = $(this).closest('.preview-block-details');
                var staff = $(body).find('[name="search_user"]').val();
                var business = $(body).find('[name="search_business"]').val();
                var starttime = $(body).find('[name="starttime"]').val();
                var endtime = $(body).find('[name="endtime"]').val();
                var newurl = url.split('?')[0]+'?staff='+staff+'&business='+business+'&starttime='+starttime+'&endtime='+endtime;
                loadPanel(newurl, '.panel-heading');
                return false;
            });
		}
	});
}
</script>

<h3>Reports</h3>
<div id="reports_accordions" class="sidebar show-on-mob panel-group block-panels col-xs-12"><?php
    $reports = 'summary,leadsource,nextaction,leadspipeline,wonlost';
    
    foreach(explode(',', $reports) as $i => $report) {
        if ($report=='summary') {
            $report_title = 'Monthly Summary Report';
        } elseif ($report=='leadsource') {
            $report_title = 'Lead Source Report';
        } elseif ($report=='nextaction') {
            $report_title = 'Next Action Report';
        } elseif ($report=='leadspipeline') {
            $report_title = 'Leads Added To Pipeline';
        } elseif ($report=='wonlost') {
            $report_title = 'Total Won/Lost';
        } ?>
		
        <div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#reports_accordions" href="#collapse_<?= $i ?>">
						<?= $report_title ?><span class="glyphicon glyphicon-plus"></span>
					</a>
				</h4>
			</div>

			<div id="collapse_<?= $i ?>" class="panel-collapse collapse">
				<div class="panel-body" data-file="report_<?= $report ?>.php">
					Loading...
				</div>
			</div>
		</div><?php
    } ?>
</div>