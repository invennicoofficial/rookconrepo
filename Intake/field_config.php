<?php
	/*
	 * Intake Settings Dashboard
	 */
	
	include ('../include.php');
	checkAuthorised('intake');
	error_reporting(0);

	switch($_GET['tab']) {
		case 'software_tabs':
			$body_title = 'Form Categories';
			$tab_note = 'Configure your settings for Software Intake Form Categories.';
			break;
		case 'software':
			$body_title = 'Forms Settings';
			$tab_note = 'Configure your settings for Software Intake Forms.';
			break;
		case 'software_forms':
			$body_title = 'Forms';
			$tab_note = 'Configure your Software Intake Forms here.';
			break;
		default:
			$body_title = 'Website Forms Settings';
			$tab_note = 'Configure your settings for Website Intake Forms.';
			break;
	}
	?>
</head>

<script type="text/javascript">
$(document).ready(function() {
	$('#intake_accordions .panel-heading').click(loadPanel);
	if($(window).width() > 767) {
		resizeScreen();
		$(window).resize(function() {
			resizeScreen();
		});
	}
});
function resizeScreen() {
	var view_height = $(window).height() > 800 ? $(window).height() : 800;
	$('#intake_div .scale-to-fill .main-screen,#intake_div .tile-sidebar,#intake_div .scale-to-fill.tile-content').height(view_height - $('#intake_div .scale-to-fill').offset().top - $('#footer').outerHeight());
}
function loadPanel() {
    $('#intake_accordions .panel-heading').closest('.panel').find('.panel-body').html('Loading...');
    var panel = $(this).closest('.panel').find('.panel-body');
    $(panel).html('Loading...');
    $.ajax({
        url: $(panel).data('file'),
        method: 'POST',
        response: 'html',
        success: function(response) {
            $(panel).html(response);
        }
    });
}
</script>

<body>
	<?php include ('../navigation.php'); ?>

	<div id="intake_div" class="container">
		<div class="row">
			<div class="main-screen">
				<div class="tile-header standard-header">
					<?php include('../Intake/tile_header.php'); ?>
				</div>

				<div id="intake_accordions" class="sidebar show-on-mob panel-group block-panels col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#intake_accordions" href="#collapse_web">
									Website Form Settings<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_web" class="panel-collapse collapse">
							<div class="panel-body" data-file="field_config_web.php">
								Loading...
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#intake_accordions" href="#collapse_software">
									Forms Settings<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_software" class="panel-collapse collapse">
							<div class="panel-body" data-file="field_config_software.php">
								Loading...
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#intake_accordions" href="#collapse_software_tabs">
									Form Categories<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_software_tabs" class="panel-collapse collapse">
							<div class="panel-body" data-file="field_config_software_tabs.php">
								Loading...
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#intake_accordions" href="#collapse_software_forms">
									Forms<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h4>
						</div>

						<div id="collapse_software_forms" class="panel-collapse collapse">
							<div class="panel-body" data-file="field_config_software_forms.php">
								Loading...
							</div>
						</div>
					</div>
				</div>

	            <div class="hide-titles-mob standard-collapsible tile-sidebar set-section-height">
	            	<ul>
	            		<a href="../Intake/intake.php"><li>Back to Dashboard</li></a>
	            		<a href="../Intake/field_config.php?tab=web"><li <?= empty($_GET['tab']) || $_GET['tab'] == 'web' ? 'class="active"' : '' ?>>Website Forms Settings</li></a>
	            		<a href="../Intake/field_config.php?tab=software"><li <?= $_GET['tab'] == 'software' ? 'class="active"' : '' ?>>Forms Settings</li></a>
	            		<a href="../Intake/field_config.php?tab=software_tabs"><li <?= $_GET['tab'] == 'software_tabs' ? 'class="active"' : '' ?>>Form Categories</li></a>
	            		<a href="../Intake/field_config.php?tab=software_forms"><li <?= $_GET['tab'] == 'software_forms' ? 'class="active"' : '' ?>>Forms</li></a>
	            	</ul>
	            </div>
				
	            <div class="hide-titles-mob scale-to-fill has-main-screen">
	            	<div class="main-screen standard-body form-horizontal">
		        		<div class="standard-body-title">
		        			<h3><?= $body_title ?></h3>
		        		</div>
	            		<div class="standard-body-content" style="padding: 0 0.5em;">
			            	<!-- Notice -->
			                <div class="notice gap-bottom gap-top popover-examples">
			                    <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
			                    <div class="col-sm-11"><span class="notice-name">NOTE:</span>
								<?php echo $tab_note; ?></div>
			                    <div class="clearfix"></div>
			                </div>
							<?php if(empty($_GET['tab']) || $_GET['tab'] == 'web') {
								include('../Intake/field_config_web.php');
							} else if($_GET['tab'] == 'software_tabs') {
								include('../Intake/field_config_software_tabs.php');
							} else if($_GET['tab'] == 'software') {
								include('../Intake/field_config_software.php');
							} else if($_GET['tab'] == 'software_forms') {
								include('../Intake/field_config_software_forms.php');
							} ?>
						</div>
					</div>
				</div>

			</div>
		</div><!-- .row -->
	</div><!-- .container -->

<?php include ('../footer.php'); ?>