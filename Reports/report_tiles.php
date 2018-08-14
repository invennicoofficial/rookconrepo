<?php
/*
Client Listing
*/
include ('../include.php');
checkAuthorised('report');
error_reporting(0);
mysqli_query($dbc, "DELETE FROM `invoice_compensation` WHERE `invoiceid` NOT IN (SELECT `invoiceid` FROM `invoice`)");
if($_GET['mobile_view'] == 'true') {
        echo reports_tiles_content($dbc);
} else { ?>
	<script type="text/javascript">
	$(document).ready(function() {
		if($(window).width() > 768) {
			$(window).resize(function() {
				var view_height = $(window).height() - ($('#reports_div .scale-to-fill.has-main-screen').offset().top + $('#footer:visible').outerHeight()) - 1;
				$('.tile-sidebar,.main-screen.standard-body').height(view_height);
			}).resize();
		} else {
            <?php if ( isset($_GET['landing']) && $_GET['landing']=='true' ) { ?>
                var type = '<?= $_GET['type'] ?>';
                var report = '<?= $_GET['report'] ?>';
                //var default_report = '<?= '../'.$_SERVER['REQUEST_URI'] ?>';
                loadDefaultReport(type, report);
            <?php } ?>
        }
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
	function loadDefaultReport(type, report) {
		var panel = $('#collapse_'+type+' .panel-body');
        panel.closest('.panel-collapse').addClass('in');
        panel.closest('.panel').find('.panel-heading').addClass('active');
        panel.closest('.panel').find('.collapsed').removeClass('collapsed');
        
		panel.html('Loading...');
		$.ajax({
			url: panel.data('file-name')+'&report='+report,
			method: 'POST',
			response: 'html',
			success: function(response) {
				panel.html(response);
			}
		});
	}
	</script>
	</head>
	<body>
	<?php include_once ('../navigation.php');
	?>

	<div class="container" id="reports_div">
	    <div class="iframe_overlay" style="display:none;">
			<div class="iframe">
				<div class="iframe_loading">Loading...</div>
				<iframe name="edit_board" src=""></iframe>
			</div>
		</div>
	    <div class="row">
	        <?php echo reports_tiles($dbc); ?>
	    </div>
	</div>
	<?php include ('../footer.php'); ?>
<?php } ?>
