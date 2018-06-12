<div class="standard-collapsible hide-titles-mob sidebar tile-sidebar sidebar-override inherit-height double-gap-top">
	<ul>
	<?php $pdf_styles = mysqli_query($dbc, "SELECT `pdfsettingid`,`style_name`,`style` FROM `estimate_pdf_setting` WHERE `estimateid` IS NULL AND `deleted`=0 ORDER BY `style_name`");
	if(empty($_GET['style'])) {
		$_GET['style'] = $estimate['pdf_style'];
	}
	while($pdf_style = mysqli_fetch_assoc($pdf_styles)) {
		if(empty($_GET['style'])) {
			$_GET['style'] = $pdf_style['pdfsettingid'];
		} ?>
		<a href="?edit=<?= $_GET['edit'] ?>&tab=preview&style=<?= $pdf_style['pdfsettingid'] ?>"><li class="<?= $_GET['style'] == $pdf_style['pdfsettingid'] ? 'active blue' : '' ?>"><?= $pdf_style['style_name'] == '' ? '(Untitled)' : $pdf_style['style_name'] ?></li></a>
		<?php if($pdf_style['pdfsettingid'] == $_GET['style']) {
			$_GET['style_template'] = $pdf_style['style'];
		}
	} ?>
	</ul>
</div>
<?php include('estimate_design_output.php'); ?>
<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen default_screen form-horizontal standard-body'>
		<div class="standard-body-title"><h3>Preview</h3></div>
		<div class="standard-dashboard-body-content pad-top pad-left pad-right">
			<a target="_blank" href="estimate_pdf_output.php?edit=<?= $_GET['edit'] ?>&style=<?= $_GET['style'] ?>" class="btn brand-btn pull-right">Download PDF</a>
			<?php if($settings['cover_text'] != '' || $settings['cover_logo'] != '') { ?>
				<div class="design_privew dashboard-item pull-centre" style="max-width:80em;width:100%;padding:<?= $top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin ?>px">
					<div style="position:relative;width:100%;padding-top:<?= $pdf_settings['page_ori'] == 'landscape' ? '77' : '130' ?>%;">
						<div style="height:100%;position:absolute;top:0;left:0;width:100%;">
							<?= $cover_page ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if($settings['toc_content'] != '') { ?>
				<div class="design_privew dashboard-item pull-centre" style="max-width:80em;width:100%;padding:<?= $top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin ?>px">
					<div style="position:relative;width:100%;padding-top:<?= $pdf_settings['page_ori'] == 'landscape' ? '77' : '130' ?>%;">
						<div style="height:100%;position:absolute;top:0;left:0;width:100%;">
							<?= $header_html.$toc_content.$footer_html ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if($settings['pages_text'] != '') { ?>
				<div class="design_privew dashboard-item pull-centre" style="max-width:80em;width:100%;padding:<?= $top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin ?>px">
					<div style="position:relative;width:100%;padding-top:<?= $pdf_settings['page_ori'] == 'landscape' ? '77' : '130' ?>%;">
						<div style="height:100%;position:absolute;top:0;left:0;width:100%;">
							<?= $header_html.$pages_content.$footer_html ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="design_privew dashboard-item pull-centre" style="max-width:80em;width:100%;padding:<?= $top_margin.'px '.$right_margin.'px '.$bottom_margin.'px '.$left_margin ?>px">
				<div style="position:relative;width:100%;padding-top:<?= $pdf_settings['page_ori'] == 'landscape' ? '77' : '130' ?>%;">
					<div style="height:100%;position:absolute;top:0;left:0;width:100%;">
						<?= $header_html.$html.$footer_html ?>
					</div>
				</div>
			</div>
			<a target="_blank" href="estimate_pdf_output.php?edit=<?= $_GET['edit'] ?>&style=<?= $_GET['style'] ?>" class="btn brand-btn pull-right">Download PDF</a>
		</div>
	</div>
</div>