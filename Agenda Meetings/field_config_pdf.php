<form action="" class="form-horizontal" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="styleid" value="<?= $_GET['style'] ?>">
	<button class="btn brand-btn pull-right" type="submit" name="btn_<?= $_GET['design'] ?>" value="<?= $_GET['design'] ?>">Save Settings</button>
	<?php if($_GET['design'] == 'name') {
		include('field_config_pdf_name_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="style">Design Style</button>';
	} else if($_GET['design'] == 'style') {
		include('field_config_pdf_style_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="cover">Cover Page</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="name">PDF Style Name</button>';
	} else if($_GET['design'] == 'cover') {
		include('field_config_pdf_cover_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="toc">Table of Contents</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="style">Design Style</button>';
	} else if($_GET['design'] == 'toc') {
		include('field_config_pdf_toc_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="numbers">Page Numbers</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="cover">Cover Page</button>';
	} else if($_GET['design'] == 'numbers') {
		include('field_config_pdf_numbers_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pages">Add Page</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="toc">Table of Contents</button>';
	} else if($_GET['design'] == 'pages') {
		include('field_config_pdf_pages_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pdf">PDF Settings</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="numbers">Page Numbers</button>';
	} else if($_GET['design'] == 'pdf') {
		include('field_config_pdf_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="header">Header</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pages">Add Page</button>';
	} else if($_GET['design'] == 'header') {
		include('field_config_pdf_header_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="content">Main Content</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pdf">PDF Settings</button>';
	} else if($_GET['design'] == 'content') {
		include('field_config_pdf_main_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="footer">Footer</button>';
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="header">Header</button>';
	} else if($_GET['design'] == 'footer') {
		include('field_config_pdf_footer_setting.php');
		echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="content">Main Content</button>';
	} else {
		echo "<h2>Please select an option at the left.</h2>";
	} ?>
	<button class="btn brand-btn pull-right" type="submit" name="btn_<?= $_GET['design'] ?>" value="<?= $_GET['design'] ?>">Save Settings</button>
</form>
<div class="clearfix"></div>
<?php if($_GET['design'] != 'name') {
	$settings = $pdf_settings; ?>
	<div class="design_privew dashboard-item pull-centre" style="max-width:80em;width:100%;padding:<?= $settings['top_margin'].'px '.$settings['right_margin'].'px '.$settings['bottom_margin'].'px '.$settings['left_margin'] ?>px">
		<div style="position:relative;width:100%;padding-top:<?= $pdf_settings['page_ori'] == 'landscape' ? '77' : '130' ?>%;">
			<div style="position:absolute;top:0;left:0;width:100%; height:100%;">
				<?php if($_GET['design'] == 'style') { ?>
					<div class="design_a" style="<?= $pdf_settings['style'] == 'a' ? '' : 'display:none;' ?>">
						<?php include('design_styleA.php');
						echo "</head><body>";
						echo $header_html;
						echo $html;
						echo $footer_html;
						echo "</body></html>"; ?>
					</div>
					<div class="design_b" style="<?= $pdf_settings['style'] == 'b' ? '' : 'display:none;' ?>">
						<?php include('design_styleB.php');
						echo "</head><body>";
						echo $header_html;
						echo $html;
						echo $footer_html;
						echo "</body></html>"; ?>
					</div>
					<div class="design_c" style="<?= $pdf_settings['style'] == 'c' ? '' : 'display:none;' ?>">
						<?php include('design_styleC.php');
						echo "</head><body>";
						echo $header_html;
						echo $html;
						echo $footer_html;
						echo "</body></html>"; ?>
					</div>
				<?php } else if($_GET['design'] == 'cover' && $pdf_settings['style'] == 'c') {
					include('design_styleC.php');
					echo $cover_page;
					echo $cover_footer;
				} else if($_GET['design'] == 'cover' && $pdf_settings['style'] == 'b') {
					include('design_styleB.php');
					echo $cover_page;
					echo $cover_footer;
				} else if($_GET['design'] == 'cover') {
					include('design_styleA.php');
					echo $cover_page;
					echo $cover_footer;
				} else if($_GET['design'] == 'toc' && $pdf_settings['style'] == 'c') {
					include('design_styleC.php');
					echo $header_html;
					echo $toc_content;
					echo $footer_html;
				} else if($_GET['design'] == 'toc' && $pdf_settings['style'] == 'b') {
					include('design_styleB.php');
					echo $header_html;
					echo $toc_content;
					echo $footer_html;
				} else if($_GET['design'] == 'toc') {
					include('design_styleA.php');
					echo $header_html;
					echo $toc_content;
					echo $footer_html;
				} else if($_GET['design'] == 'pages' && $pdf_settings['style'] == 'c') {
					include('design_styleC.php');
					echo $pages_content;
				} else if($_GET['design'] == 'pages' && $pdf_settings['style'] == 'b') {
					include('design_styleB.php');
					echo $pages_content;
				} else if($_GET['design'] == 'pages') {
					include('design_styleA.php');
					echo $pages_content;
				} else if($pdf_settings['style'] == 'c') {
					include('design_styleC.php');
					echo $header_html;
					echo $html;
					echo $footer_html;
				} else if($pdf_settings['style'] == 'b') {
					include('design_styleB.php');
					echo $header_html;
					echo $html;
					echo $footer_html;
				} else {
					include('design_styleA.php');
					echo $header_html;
					echo $html;
					echo $footer_html;
				} ?>
			</div>
		</div>
	</div>
<?php } ?>
<script>
$(document).ready(function() {
	$('ul.sidebar').scrollTop($('h4[name=design_settings]').offset().top)
});
</script>