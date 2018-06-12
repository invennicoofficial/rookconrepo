<ul class="sidebar collapsible hide-titles-mob estimates-height">
	<?php include('field_config_pdf_save.php');
	if(empty($_GET['design'])) {
		$_GET['design'] = 'pdf';
	}
	if(empty($_GET['style'])) {
		$_GET['style'] = $pdf_settings['style'];
	} ?>
	<h4>Design Style</h4>
	<ul>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=design&style=a&design=<?= $_GET['design'] ?>"><li class="<?= $_GET['style'] == 'a' ? 'active blue' : '' ?>">Style A</li></a>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=design&style=b&design=<?= $_GET['design'] ?>"><li class="<?= $_GET['style'] == 'b' ? 'active blue' : '' ?>">Style B</li></a>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=design&style=c&design=<?= $_GET['design'] ?>"><li class="<?= $_GET['style'] == 'c' ? 'active blue' : '' ?>">Style C</li></a>
	</ul>
	<h4>Design Settings</h4>
	<ul>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=design&style=<?= $_GET['style'] ?>&design=pdf"><li class="<?= $_GET['design'] == 'pdf' ? 'active blue' : '' ?>">PDF Settings</li></a>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=design&style=<?= $_GET['style'] ?>&design=header"><li class="<?= $_GET['design'] == 'header' ? 'active blue' : '' ?>">Header</li></a>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=design&style=<?= $_GET['style'] ?>&design=content"><li class="<?= $_GET['design'] == 'content' ? 'active blue' : '' ?>">Main Content</li></a>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=design&style=<?= $_GET['style'] ?>&design=footer"><li class="<?= $_GET['design'] == 'footer' ? 'active blue' : '' ?>">Footer</li></a>
	</ul>
</ul>
<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen estimates-height'>
		<form action="" class="form-horizontal" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="style" value="<?= $_GET['style'] ?>">
			<div class="col-sm-3 show-on-mob">
				<a class="<?= $_GET['style'] == 'a' ? 'active' : '' ?> col-sm-4 block-item text-center" href="?settings=pdf&style=a&design=<?= $_GET['design'] ?>">A</a>
				<a class="<?= $_GET['style'] == 'b' ? 'active' : '' ?> col-sm-4 block-item text-center" href="?settings=pdf&style=b&design=<?= $_GET['design'] ?>">B</a>
				<a class="<?= $_GET['style'] == 'c' ? 'active' : '' ?> col-sm-4 block-item text-center" href="?settings=pdf&style=c&design=<?= $_GET['design'] ?>">C</a>
			</div>
			<button class="btn brand-btn pull-right" type="submit" name="<?= $_GET['design'] ?>" value="<?= $_GET['design'] ?>">Save Settings</button>
			<a class="btn brand-btn pull-right" onclick="previewChanges(); return false;">Preview</a>
			<?php if($_GET['design'] == 'pdf') {
				include('field_config_pdf_setting.php');
			} else if($_GET['design'] == 'header') {
				include('field_config_pdf_header_setting.php');
			} else if($_GET['design'] == 'content') {
				include('field_config_pdf_main_setting.php');
			} else if($_GET['design'] == 'footer') {
				include('field_config_pdf_footer_setting.php');
			} else {
				echo "<h2>Please select an option at the left.</h2>";
			} ?>
			<button class="btn brand-btn pull-right" type="submit" name="<?= $_GET['design'] ?>" value="<?= $_GET['design'] ?>">Save Settings</button>
			<a class="btn brand-btn pull-right" onclick="previewChanges(); return false;">Preview</a>
			<?php if($_GET['design'] == 'pdf') {
				echo '<button class="btn brand-btn pull-right" type="submit" name="'.$_GET['design'].'" value="header">Header</button>';
			} else if($_GET['design'] == 'header') {
				echo '<button class="btn brand-btn pull-right" type="submit" name="'.$_GET['design'].'" value="content">Main Content</button>';
				echo '<button class="btn brand-btn pull-right" type="submit" name="'.$_GET['design'].'" value="pdf">PDF Settings</button>';
			} else if($_GET['design'] == 'content') {
				echo '<button class="btn brand-btn pull-right" type="submit" name="'.$_GET['design'].'" value="footer">Footer</button>';
				echo '<button class="btn brand-btn pull-right" type="submit" name="'.$_GET['design'].'" value="header">Header</button>';
			} else if($_GET['design'] == 'footer') {
				echo '<button class="btn brand-btn pull-right" type="submit" name="'.$_GET['design'].'" value="content">Main Content</button>';
			} ?>
		</form>
		<script>
		var target = undefined;
		function previewChanges() {
			target = window.open('?tab=preview&style=<?= $_GET['style'] ?>&edit=<?= $estimateid ?>', 'preview_<?= $style_settings ?>');
			var test = setInterval(function() {
				if(target.document.readyState == 'complete') {
					clearInterval(test);
					$('input,select,textarea,hidden').change();
				}
			}, 250);
		}
		</script>
		<a href="?edit=<?= $estimateid ?>&tab=scope" class="btn brand-btn pull-right">Back to Scope</a>
	</div>
</div>