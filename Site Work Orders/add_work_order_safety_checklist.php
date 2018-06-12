<script>
$(document).ready(function() {
	$('.iframe').click(function() {
		$('.safety_links').hide();
		$('#safety_iframe').off('load').load(function() {
			$('.safety_iframe').show();
			$(this).height($(this).contents().height());
			$(this).contents().find('a[href*=#collapse_]').click(function() {
				window.setTimeout(function(a) {
					$(window.top.document).find('#safety_iframe').height($('#safety_iframe').contents().height());
				}, 500);
			});
			$('#safety_iframe').off('load');
			$('#safety_iframe').load(function() {
				close_safety();
			});
		});
		$('#safety_iframe').attr('src', $(this).data('href'));
	});
});
	
function close_safety() {
	$('.safety_iframe').hide();
	$('.safety_links').show();
	$('#safety_iframe').src = '';
}
</script>
<div class="safety_iframe" style="display:none;">
	<img src="<?php echo WEBSITE_URL; ?>/img/icons/close.png" onclick="close_safety();" width="45px" style="position:relative; right: 1em; top:1em; float:right; cursor:pointer;">
	<span class="iframe_title" style="color:white; font-weight:bold; position: relative; left:1em; top:0.25em; font-size:3em;">Safety Manual</span>
	<iframe id="safety_iframe" style="border: 1em solid gray; border-top: 5em solid gray; margin-top: -4em; width: 100%;" src=""></iframe>
</div>
<div class="safety_links">
	<h3>Safety Checklist</h3>
	<p>The following items need to be completed for this Work Order:</p>
	<?php include_once('../Safety/manual_checklist.php');
	manual_checklist($dbc, '35', '20', '20', 'ALL', 'ALL', "AND (CONCAT(',',`assign_sites`,',') LIKE '%,ALL,%' OR CONCAT(',',`assign_sites`,',') LIKE '%,$siteid,%' OR CONCAT(',',`assign_work_orders`,',') LIKE '%,ALL,%' OR CONCAT(',',`assign_work_orders`,',') LIKE '%,$workorderid,%')", 'iframe'); ?>
</div>