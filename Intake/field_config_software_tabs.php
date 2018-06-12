<?php include_once('../include.php');
checkAuthorised('intake');
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) ) {
	$intake_software_tabs  = implode('*#*', $_POST['intake_software_tabs']);
	set_config($dbc, 'intake_software_tabs', $intake_software_tabs);

	echo '<script type="text/javascript"> window.location.replace("field_config.php?tab=software_tabs");</script>';
}
?>
	
<script>
	$(document).ready(function(){
		$("#selectall").change(function(){
		  $("input[name='intake_software_dashboard[]']").prop('checked', $(this).prop("checked"));
		});
	});
	function add_tab() {
		var block = $('.intake_tab_block').last();
		var clone = block.clone();
		clone.find('.form-control').val('');
		block.after(clone);
	}
	function remove_tab(img) {
		if($('.intake_tab_block').length <= 1) {
			add_tab();
		}
		$(img).closest('.intake_tab_block').remove();
	}
</script>

<form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form">

	<div class="form-group">
		<h4>Configure Form Categories</h4>
		<?php $form_categories = explode('*#*',get_config($dbc, 'intake_software_tabs'));
		foreach($form_categories as $form_cat) { ?>
			<div class="form-group intake_tab_block">
				<label class="control-label col-sm-4">Category:</label>
				<div class="col-sm-7">
					<input type="text" name="intake_software_tabs[]" class="form-control" value="<?= $form_cat ?>">
				</div>
				<div class="col-sm-1 pull-right">
					<img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="add_tab();">
					<img src="../img/remove.png" class="inline-img pull-right" onclick="remove_tab(this);">
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="form-group pull-right">
		<a href="intake.php" class="btn brand-btn">Back</a>
		<!--<a href="#" class="btn config-btn pull-right" onclick="history.go(-1);return false;">Back</a>-->
		<button	type="submit" name="submit"	value="Submit" class="btn brand-btn">Submit</button>
	</div>

</form>