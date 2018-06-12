<head>
	<link id="jquiCSS" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css" type="text/css" media="all">
	<link href="css/demo.css" rel="stylesheet" /> 
	<link href="css/evol-colorpicker.min.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Encode+Sans|Indie+Flower|Karla|Lora|Merriweather|Montserrat|Nunito|Raleway|Roboto|Saira+Extra+Condensed|Slabo+27px" rel="stylesheet">

	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>-->
	<script src="js/evol-colorpicker.min.js" type="text/javascript"></script>
</head>
<script>

$(document).ready(function(){

	// Change theme
    $('.css').on('click', function(evt){
        var theme=this.innerHTML.toLowerCase().replace(' ', '-'),
            url='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/'+theme+'/jquery-ui.css';
        $('#jquiCSS').attr('href', url);
        $('.css').removeClass('sel');
        $(this).addClass('sel');
    });
	
	// Events demo
	function setColor(evt, color){
        if(color){
            $('#cpEvent').css('background-color', color);
        }
	}

	$('#cpBinding').colorpicker({
		color:'#ebf1dd',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
		.on('change.color', setColor)
		.on('mouseover.color', setColor);
	
	
	// Methods demo
	$('#getVal').on('click', function(){
		alert("getval");
		alert('Selected color = "' + $('#cp1').colorpicker("val") + '"');
	});
	$('#setVal').on('click', function(){
		alert("setval");
		$('#cp1').colorpicker("val",'#31859b');
	});
	$('#enable').on('click', function(){
		alert("enable");
		$('#cp1').colorpicker("enable");
	});
	$('#disable').on('click', function(){
		$('#cp1').colorpicker("disable");
	});
	$('#clear').on('click', function(){
		$('#cp1').colorpicker("clear");
	});
	$('#destroy1').on('click', function(){
		$('#cp1').colorpicker("destroy");
	});
	// Methods demo 2 (inline colorpicker)
	$('#getVal2').on('click', function(){
		alert('Selected color = "' + $('#cpInline').colorpicker("val") + '"');
	});
	$('#setVal2').on('click', function(){
		$('#cpInline').colorpicker("val", '#31859b');
	});
	$('#enable2').on('click', function(){
		$('#cpInline').colorpicker("enable");
	});
	$('#disable2').on('click', function(){
		$('#cpInline').colorpicker("disable");
	});
	$('#destroy2').on('click', function(){
		$('#cpInline').colorpicker("destroy");
	});
	
	// Instanciate colorpickers
	$('#cp1').colorpicker({
		color:'#ff9800',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	$('#cpBinding').colorpicker({
		color:'#ebf1dd'
	})
    $('#cpInline').colorpicker({color:'#92cddc'});
    $('#cpInline2').colorpicker({color:'#92cddc'});
	$('#cpInline3').colorpicker({color:'#92cddc'});

	// Custom theme palette
	$('#customTheme').colorpicker({
		color: '#f44336',
		customTheme: ['#f44336','#ff9800','#ffc107','#4caf50','#00bcd4','#3f51b5','#9c27b0', 'white', 'black']
	});
    $('#cpButton').colorpicker({showOn:'button'});
    $('#cpFocus').colorpicker({showOn:'focus'});
    $('#cpBoth').colorpicker();
    $('#cpOther').colorpicker({showOn:'none'});

	$('#show').on('click', function(evt){
		evt.stopImmediatePropagation();
		$('#cpOther').colorpicker("showPalette");
	});
	
	// With transparent color
	$('#transColor').colorpicker({
		transparentColor: true
	});

	// With hidden button
	$('#hideButton').colorpicker({
		hideButton: true
	});

	// No color indicator
	$('#noIndColor').colorpicker({
		displayIndicator: false
	});

	// French colorpicker
	$('#frenchColor').colorpicker({
		strings: "Couleurs de themes,Couleurs de base,Plus de couleurs,Moins de couleurs,Palette,Historique,Pas encore d'historique."
	});

	// Fix links
	$('a[href="#"]').attr('href', 'javascript:void(0)');

	

});
</script>

<script>
var contact_type = '';
$(document).ready(function() {
	$('.panel-heading').click(loadPanel);
});
function loadPanel() {
	$('.panel-body').html('Loading...');
	body = $(this).closest('.panel').find('.panel-body');
	$.ajax({
		url: '../Contacts/'+$(body).data('file'),
		data: { folder: '<?= FOLDER_NAME ?>', type: contact_type },
		method: 'POST',
		response: 'html',
		success: function(response) {
			$(body).html(response);
		}
	});
}
</script>
<div id='settings_accordions' class='sidebar show-on-mob panel-group block-panels col-xs-12'>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_subtab_regions">
					Regions<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_subtab_regions" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_regions.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_subtab_locations">
					Locations<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_subtab_locations" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_locations.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_subtab_classifications">
					Classifications<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_subtab_classifications" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_classifications.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_subtab_titles">
					Titles<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_subtab_titles" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_titles.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_subtab_fields">
					Fields<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_subtab_fields" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_fields.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_subtab_additions">
					Profile Additions<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_subtab_additions" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_additions.php">
				Loading...
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#settings_accordions" href="#collapse_subtab_import">
					Import / Export<span class="glyphicon glyphicon-plus"></span>
				</a>
			</h4>
		</div>

		<div id="collapse_subtab_import" class="panel-collapse collapse">
			<div class="panel-body" data-file="field_config_import.php">
				Loading...
			</div>
		</div>
	</div>
</div>
<?php
if(empty($_GET['style_settings'])) {
	$_GET['style_settings'] = 'design_styleA';
}
?>
<ul class='sidebar hide-titles-mob collapsible default-height'>
	<li><h4>PDF Design Styles</h4>
		<?php include('field_config_pdf_save.php'); ?>
		<ul>
			<?php $pdf_styles = mysqli_query($dbc, "SELECT `pdfsettingid`,`style_name` FROM `pdf_settings` WHERE `tile_name` = 'agenda_meeting' AND `deleted`=0 ORDER BY `style_name`");
			while($pdf_style = mysqli_fetch_assoc($pdf_styles)) { ?>
				<a href="?settings=pdf&style=<?= $pdf_style['pdfsettingid'] ?>&design=<?= $_GET['design'] ?>"><li class="<?= $_GET['style'] == $pdf_style['pdfsettingid'] ? 'active blue' : '' ?>"><?= $pdf_style['style_name'] == '' ? '(Untitled Template)' : $pdf_style['style_name'] ?><img data-id="<?= $pdf_style['pdfsettingid'] ?>" onclick="deleteStyle(this); return false;" class="inline-img pull-right" src="../img/remove.png"></li></a>
			<?php } ?>
			<a href="?settings=pdf&style=new&design=<?= $_GET['design'] ?>"><li class="<?= $_GET['style'] == 'new' ? 'active blue' : '' ?>">New Template</li></a>
		</ul>
	</li>
	<li><h4>PDF Design Settings</h4>
		<ul>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=name"><li class="<?= !isset($_GET['design']) || $_GET['design'] == 'name' ? 'active blue' : '' ?>">PDF Style Name</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=style"><li class="<?= $_GET['design'] == 'style' ? 'active blue' : '' ?>">Design Style</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=cover"><li class="<?= $_GET['design'] == 'cover' ? 'active blue' : '' ?>">Cover Page</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=toc"><li class="<?= $_GET['design'] == 'toc' ? 'active blue' : '' ?>">Table of Contents</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=numbers"><li class="<?= $_GET['design'] == 'numbers' ? 'active blue' : '' ?>">Page Numbers</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=pages"><li class="<?= $_GET['design'] == 'pages' ? 'active blue' : '' ?>">Add Page</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=pdf"><li class="<?= $_GET['design'] == 'pdf' ? 'active blue' : '' ?>">PDF Settings</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=header"><li class="<?= $_GET['design'] == 'header' ? 'active blue' : '' ?>">Header</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=content"><li class="<?= $_GET['design'] == 'content' ? 'active blue' : '' ?>">Main Content</li></a>
			<a href="?settings=pdf&style=<?= $_GET['style'] ?>&design=footer"><li class="<?= $_GET['design'] == 'footer' ? 'active blue' : '' ?>">Footer</li></a>
		</ul>
	</li>
</ul>
<div class='scale-to-fill has-main-screen hide-titles-mob'>
	<div class='main-screen default-height'>
		<form action="" class="form-horizontal" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="styleid" value="<?= $_GET['style'] ?>">
			<button class="btn brand-btn pull-right" type="submit" name="btn_<?= $_GET['design'] ?>" value="<?= $_GET['design'] ?>">Save Settings</button>
			<?php if($_GET['settings'] == 'pdf') {
				switch($_GET['design']) {
					case 'style':
						include('field_config_pdf_style_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="cover">Cover Page</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="name">PDF Style Name</button>';
						break;
					case 'cover':
						include('field_config_pdf_cover_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="toc">Table of Contents</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="style">Design Style</button>';
						break;
					case 'toc':
						include('field_config_pdf_toc_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="numbers">Page Numbers</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="cover">Cover Page</button>';
						break;
					case 'numbers':
						include('field_config_pdf_numbers_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pages">Add Page</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="toc">Table of Contents</button>';
						break;
					case 'pages':
						include('field_config_pdf_pages_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pdf">PDF Settings</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="numbers">Page Numbers</button>';
						break;
					case 'pdf':
						include('field_config_pdf_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="header">Header</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pages">Add Page</button>';
						break;
					case 'header':
						include('field_config_pdf_header_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="content">Main Content</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="pdf">PDF Settings</button>';
						break;
					case 'content':
						include('field_config_pdf_main_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="footer">Footer</button>';
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="header">Header</button>';
						break;
					case 'footer':
						include('field_config_pdf_footer_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="content">Main Content</button>';
						break;
					case 'name':
					default:
						include('field_config_pdf_name_setting.php');
						echo '<button class="btn brand-btn pull-right" type="submit" name="btn_'.$_GET['design'].'" value="style">Design Style</button>';
						break;
				}
			} else {
				include('field_config_agenda_meeting.php');
			} ?>
			<button class="btn brand-btn pull-right" type="submit" name="btn_<?= $_GET['design'] ?>" value="<?= $_GET['design'] ?>">Save Settings</button>
		</form>
		<div class="clearfix"></div>
		<?php if($_GET['settings'] == 'pdf' && $_GET['style'] > 0 && $_GET['design'] != 'name') {
			$settings = $pdf_settings; ?>
			<div class="design_privew dashboard-item pull-centre" style="max-width:80em;width:100%;padding:<?= $settings['top_margin'].'px '.$settings['right_margin'].'px '.$settings['bottom_margin'].'px '.$settings['left_margin'] ?>px">
				<div style="position:relative;width:100%;padding-top:<?= $pdf_settings['page_ori'] == 'landscape' ? '77' : '130' ?>%;">
					<div style="position:absolute;top:0;left:0;width:100%; height:100%;">
						<?php if($_GET['design'] == 'style') { ?>
							<div class="design_a" style="<?= $pdf_settings['style'] == 'a' ? '' : 'display:none;' ?>">
								<?php include('field_design_style_a.php');
								echo "</head><body>";
								echo $header_html;
								echo $html;
								echo $footer_html;
								echo "</body></html>"; ?>
							</div>
							<div class="design_b" style="<?= $pdf_settings['style'] == 'b' ? '' : 'display:none;' ?>">
								<?php include('field_design_style_b.php');
								echo "</head><body>";
								echo $header_html;
								echo $html;
								echo $footer_html;
								echo "</body></html>"; ?>
							</div>
							<div class="design_c" style="<?= $pdf_settings['style'] == 'c' ? '' : 'display:none;' ?>">
								<?php include('field_design_style_c.php');
								echo "</head><body>";
								echo $header_html;
								echo $html;
								echo $footer_html;
								echo "</body></html>"; ?>
							</div>
						<?php } else if($_GET['design'] == 'cover' && $pdf_settings['style'] == 'c') {
							include('field_design_style_c.php');
							echo $cover_page;
							echo $cover_footer;
						} else if($_GET['design'] == 'cover' && $pdf_settings['style'] == 'b') {
							include('field_design_style_b.php');
							echo $cover_page;
							echo $cover_footer;
						} else if($_GET['design'] == 'cover') {
							include('field_design_style_a.php');
							echo $cover_page;
							echo $cover_footer;
						} else if($_GET['design'] == 'toc' && $pdf_settings['style'] == 'c') {
							include('field_design_style_c.php');
							echo $header_html;
							echo $toc_content;
							echo $footer_html;
						} else if($_GET['design'] == 'toc' && $pdf_settings['style'] == 'b') {
							include('field_design_style_b.php');
							echo $header_html;
							echo $toc_content;
							echo $footer_html;
						} else if($_GET['design'] == 'toc') {
							include('field_design_style_a.php');
							echo $header_html;
							echo $toc_content;
							echo $footer_html;
						} else if($_GET['design'] == 'pages' && $pdf_settings['style'] == 'c') {
							include('field_design_style_c.php');
							echo $pages_content;
						} else if($_GET['design'] == 'pages' && $pdf_settings['style'] == 'b') {
							include('field_design_style_b.php');
							echo $pages_content;
						} else if($_GET['design'] == 'pages') {
							include('field_design_style_a.php');
							echo $pages_content;
						} else if($pdf_settings['style'] == 'c') {
							include('field_design_style_c.php');
							echo $header_html;
							echo $html;
							echo $footer_html;
						} else if($pdf_settings['style'] == 'b') {
							include('field_design_style_b.php');
							echo $header_html;
							echo $html;
							echo $footer_html;
						} else {
							include('field_design_style_a.php');
							echo $header_html;
							echo $html;
							echo $footer_html;
						} ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>