<head>
	<link id="jquiCSS" rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css" type="text/css" media="all">
	<link href="css/demo.css" rel="stylesheet" /> 
	<link href="css/evol-colorpicker.min.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css?family=Abril+Fatface|Encode+Sans|Indie+Flower|Karla|Lora|Merriweather|Montserrat|Nunito|Raleway|Roboto|Saira+Extra+Condensed|Slabo+27px" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" type="text/javascript"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>
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
<ul class='sidebar hide-titles-mob col-sm-3' style='padding-left: 15px;'>
	<li><h4>Design Style</h4>
		<ul>
			<a href="?style_settings=design_styleA"><li class="<?= empty($_GET['style_settings']) || $_GET['style_settings'] == 'design_styleA' ? 'active blue' : '' ?>">Design Style A</li></a>
			<a href="?style_settings=design_styleB"><li class="<?= $_GET['style_settings'] == 'design_styleB' ? 'active blue' : '' ?>">Design Style B</li></a>
			<a href="?style_settings=design_styleC"><li class="<?= $_GET['style_settings'] == 'design_styleC' ? 'active blue' : '' ?>">Design Style C</li></a>
		</ul>
	</li>
	<?php if(!isset($_GET['preview'])): ?>
		<li><h4>Design Settings</h4>
			<ul>
				<a href="?settings=pdf_setting&style_settings=<?php echo $_GET['style_settings']; ?>"><li>PDF Settings<?php if($_GET['settings'] == 'pdf_setting'): ?><span style="margin-left:50px"><img src="../img/pdf_arrow.png"/> <?php endif; ?></span></li></a>
				<a href="?settings=pdf_header_setting&style_settings=<?php echo $_GET['style_settings']; ?>"><li class="<?= $_GET['settings'] == 'header' ? 'active blue' : '' ?>">Header<?php if($_GET['settings'] == 'pdf_header_setting'): ?><span style="margin-left:90px"><img src="../img/pdf_arrow.png"/> <?php endif; ?></span></li></a>
				<a href="?settings=pdf_main_setting&style_settings=<?php echo $_GET['style_settings']; ?>"><li class="<?= $_GET['settings'] == 'main_content' ? 'active blue' : '' ?>">Main Content<?php if($_GET['settings'] == 'pdf_main_setting'): ?><span style="margin-left:50px"><img src="../img/pdf_arrow.png"/> <?php endif; ?></span></li></a>
				<a href="?settings=pdf_footer_setting&style_settings=<?php echo $_GET['style_settings']; ?>"><li class="<?= $_GET['settings'] == 'footer' ? 'active blue' : '' ?>">Footer<?php if($_GET['settings'] == 'pdf_footer_setting'): ?><span style="margin-left:90px"><img src="../img/pdf_arrow.png"/> <?php endif; ?></span></li></a>
			</ul>
		</li>
	<?php endif; ?>
</ul>
<div class='col-sm-9 has-main-screen hide-titles-mob'>
	<div class='main-screen'>
		<?php 
			switch($_GET['settings']) {
				case 'pdf_setting':
					include('field_config_pdf_setting.php');
					break;
				case 'pdf_header_setting':
					include('field_config_pdf_header_setting.php');
					break;
				case 'pdf_main_setting':
					include('field_config_pdf_main_setting.php');
					break;
				case 'pdf_footer_setting':
					include('field_config_pdf_footer_setting.php');
					break;
			}
		?>
		<?php
			if(!isset($_GET['settings'])) {
				if($_GET['style_settings'] == 'design_styleA') 
					include('field_design_styleA.php');
				if($_GET['style_settings'] == 'design_styleB') 
					include('field_design_styleB.php');
				if($_GET['style_settings'] == 'design_styleC') 
					include('field_design_styleC.php');
			}
		?>
	</div>
</div>