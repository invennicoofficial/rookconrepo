<?php $toc_content = $pdf_settings['toc_content'];
if(str_replace(',','',$toc_content) == '') {
	$toc_content = 'cover,headings,pages';
}
$toc_content = explode(',',$toc_content); ?>
<h3>Table of Contents Settings</h3>
<div class="dashboard-item">
	<div class="form-group">
		<label class="col-sm-3 control-label">Include Cover Page:</label>
		<div class="col-sm-9">
			<label class="form-checkbox"><input type="checkbox" name="toc_content[]" <?= in_array('cover',$toc_content) ? 'checked' : '' ?> value="cover"> Show</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Include Headings:</label>
		<div class="col-sm-9">
			<label class="form-checkbox"><input type="checkbox" name="toc_content[]" <?= in_array('headings',$toc_content) ? 'checked' : '' ?> value="headings"> Show</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Include Additional Pages:</label>
		<div class="col-sm-9">
			<label class="form-checkbox"><input type="checkbox" name="toc_content[]" <?= in_array('pages',$toc_content) ? 'checked' : '' ?> value="pages"> Show</label>
		</div>
	</div>
</div>