<!-- Sales Order Templates -->
<?php
if($load_type == 'sales_order') {
	$section_item = 'Copied '.SALES_ORDER_NOUN;
	$section_title = 'Copy '.SALES_ORDER_NOUN;
	$section_load = 'Copy Items from '.SALES_ORDER_NOUN;
} else if($load_type == 'template') {
	$section_item = 'Template';
	$section_title = SALES_ORDER_NOUN.' Template';
	$section_load = 'Load Template';
}
?>
<script type="text/javascript">
	// function loadTemplate(sel) {
	// 	if (sel.value != '' && confirm('WARNING: Loading a Template into this Sales Order Form will overwrite all existing Items in this Sales Order Form. Are you sure you want to load this Template? Pressing OK will reload the page and load all the Items from the Template into this Sales Order Form.')) {
	// 		var templateid = sel.value;
	// 		var sotid = $('#sotid').val();
	// 		$.ajax({
	// 			type: 'GET',
	// 			url: 'ajax.php?fill=loadTemplate&templateid='+templateid+'&sotid='+sotid,
	// 			dataType: 'html',
	// 			success: function(response) {
	// 				$('#sotid').val(response);
	// 				$('#save_order').click();
	// 			}
	// 		});
	// 	}
	// }
	function removeTemplate(a, load_type) {
		var sotid = $('#sotid').val();
		var templateid = $(a).data('templateid');
		if(confirm('Are you sure you want to remove this <?= $section_item ?>?')) {
			$.ajax({
				url: '../Sales Order/ajax.php?fill=removeTemplateFromSO',
				method: 'POST',
				data: { sotid: sotid, templateid: templateid, load_type: load_type },
				success: function(response) {
					$('#save_order').click();
				}
			});
		}
	}
</script>
<div class="accordion-block-details padded" id="sales_order_<?= $load_type ?>">
    <div class="accordion-block-details-heading"><h4><?= $section_title ?></h4></div>
    <div class="row gap-md-left-15 set-row-height">
        <div class="col-sm-3 pad-5"><?= $section_load ?>:</div>
        <div class="col-sm-7">
        	<a href="" onclick="overlayIFrameSlider('<?= WEBSITE_URL ?>/Sales Order/load_template.php?sotid=<?= $_GET['sotid'] ?>&so_type=<?= $so_type ?>&load_type=<?= $load_type ?>', '75%', false, true, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;" class="btn brand-btn"><?= $section_load ?></a>
        </div>
        <div class="col-sm-1 pull-right">
            <a href="" onclick="overlayIFrameSlider('<?= WEBSITE_URL ?>/Sales Order/load_template.php?sotid=<?= $_GET['sotid'] ?>&so_type=<?= $so_type ?>&load_type=<?= $load_type ?>', '75%', false, true, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
    <?php if($load_type == 'template' && !empty($get_sot['templateid'])) { ?>
	    <div class="row gap-md-left-15 set-row-height gap-top">
	    	<?php $templateids = array_filter(array_unique(explode(',',$get_sot['templateid'])));
	    	foreach($templateids as $i => $templateid) { ?>
	    		<div class="col-sm-3"><?= $i == 0 ? 'Loaded Templates:' : '' ?></div>
		        <div class="col-sm-7">
		        	<?php $template_name = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `sales_order_template` WHERE `id` = '$templateid'"))['template_name'];
		        	echo $template_name; ?>
		        </div>
		        <div class="col-sm-1 pull-right">
		            <a href="" data-templateid="<?= $templateid ?>" onclick="removeTemplate(this, '<?= $load_type ?>'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
		        </div>
		        <div class="clearfix"></div>
			<?php } ?>
	    </div>
    <?php } else if($load_type == 'sales_order' && !empty($get_sot['copied_sotid'])) { ?>
	    <div class="row gap-md-left-15 set-row-height gap-top">
	    	<?php $copied_sotids = array_filter(array_unique(explode(',',$get_sot['copied_sotid'])));
	    	foreach($copied_sotids as $i => $copied_sotid) { ?>
	    		<div class="col-sm-3"><?= $i == 0 ? 'Copied '.SALES_ORDER_TILE.':' : '' ?></div>
		        <div class="col-sm-7">
		        	<?php $template_name = mysqli_fetch_array(mysqli_query($dbc, "SELECT * FROM `sales_order_temp` WHERE `sotid` = '$copied_sotid'"))['name'];
		        	echo $template_name; ?>
		        </div>
		        <div class="col-sm-1 pull-right">
		            <a href="" data-templateid="<?= $copied_sotid ?>" onclick="removeTemplate(this, '<?= $load_type ?>'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
		        </div>
		        <div class="clearfix"></div>
			<?php } ?>
	    </div>
    <?php } ?>
</div>