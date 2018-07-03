<?php
/*
Inventory Listing
*/
include ('../include.php');
checkAuthorised('scrum');
if(empty($_GET['tab'])) {
	$_GET['tab'] = 'notes';
} ?>
<script>
<?php if(!IFRAME_PAGE) { ?>
	$(document).ready(function() {
		$(window).resize(function() {
			$('.main-screen').css('padding-bottom',0);
			if($('.main-screen .main-screen').not('.show-on-mob .main-screen').is(':visible')) {
				<?php if(isset($_GET['edit']) && $ticket_layout == 'Accordions') { ?>
					var available_height = window.innerHeight - $('footer:visible').outerHeight() - $('.standard-body').offset().top;
				<?php } else { ?>
					var available_height = window.innerHeight - $('footer:visible').outerHeight() - $('.sidebar:visible').offset().top;
				<?php } ?>
				if(available_height > 200) {
					$('.main-screen .main-screen').outerHeight(available_height).css('overflow-y','auto');
					$('.sidebar').outerHeight(available_height).css('overflow-y','auto');
				}
			}
			if($('.scrum_tickets ul').is(':visible')) {
				var height = $('.sidebar').offset().top + $('.sidebar').innerHeight() - $('.scrum_tickets').offset().top - 87;
				$('.scrum_tickets ul').css('display','inline-block').css('overflow-y','auto').outerHeight(height);
			}
		}).resize();
	});
<?php } ?>
function submitForm(thisForm) {
	if (!$('input[name="search_user_submit"]').length) {
		var input = $("<input>")
					.attr("type", "hidden")
					.attr("name", "search_user_submit").val("1");
		$('[name=form_sites]').append($(input));
	}

	$('[name=form_sites]').submit();
}
</script>
</head>
<body>
<?php include_once ('../navigation.php'); ?>
<div class="container">
	<div class="iframe_overlay" style="display:none;">
		<div class="iframe">
			<div class="iframe_loading">Loading...</div>
			<iframe name="ticket_iframe" src=""></iframe>
		</div>
	</div>
	<div class="row">
		<div class="main-screen">
			<div class="tile-header standard-header" style="<?= IFRAME_PAGE ? 'display:none;' : '' ?>">
				<div class="pull-right settings-block">&nbsp;</div>
				<div class="scale-to-fill">
					<h1 class="gap-left"><a href="?">Scrum</a><img class="no-toggle statusIcon pull-right no-margin inline-img small" title="" src="" data-original-title=""></h1>
				</div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

			<div class="clearfix"></div>
			<?php IF(!IFRAME_PAGE) { ?>
				<div class="tile-sidebar sidebar sidebar-override hide-titles-mob standard-collapsible">
					<ul>
						<!--<li class="standard-sidebar-searchbox"><input type="text" class="form-control search_list" placeholder="Search Scrum"></li>-->
						<a href="?tab=notes"><li class="<?= $_GET['tab'] == 'notes' ? 'active blue' : '' ?>">Notes</li></a>
						<a href="?tab=personal"><li class="<?= $_GET['tab'] == 'personal' ? 'active blue' : '' ?>"><?= TICKET_TILE ?></li></a>
						<a href="?tab=company"><li class="<?= $_GET['tab'] == 'company' ? 'active blue' : '' ?>">Company <?= TICKET_TILE ?></li></a>
					</ul>
				</div>
			<?php } ?>
			<div class="scale-to-fill has-main-screen">
				<div class="main-screen standard-body form-horizontal">
					<?php include('scrum_display.php'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include_once('../footer.php'); ?>