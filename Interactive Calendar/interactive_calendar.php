<?php
/*
Dashboard
FFM
*/
include ('../include.php');
checkAuthorised('interactive_calendar');
error_reporting(0);
?>
<style>
.drop-area {background: #D8F9D3;max-height:50px;padding:3px;}
span.drop-text{color:#999;text-align:center;}
</style>
<script>
function dragent(current) {
	jQuery(current).css('background', '#BBD5B8');
}

function dragov(e) {
	e.preventDefault();
}

function dropof(current,e) {
	jQuery(current).css('background', '#D8F9D3');
	e.preventDefault();
	var image = e.dataTransfer.files;
	var currentname = jQuery(current).attr('name');
	createFormData(image,current.id,currentname);
}

function createFormData(image,tempid,name) {
	var formImage = new FormData();
	formImage.append('userImage', image[0]);
	formImage.append('appendid', tempid);
	formImage.append('datename', name);
	uploadFormData(formImage,tempid,name);
}

function uploadFormData(formData,tempid,datename) {
	jQuery.ajax({
	url: "upload.php",
	type: "POST",
	data: formData,
	contentType:false,
	cache: false,
	processData: false,
	success: function(data){
		jQuery('#'+tempid).html('<span class="drop-text">Drag and Drop Images Here</span>');
		jQuery('#'+tempid).css('margin-bottom','20px');
		jQuery('#img-'+datename).remove();
		jQuery('#'+tempid).after(data);
	}
});
}
</script>
<!-- <link rel="stylesheet" href="calendar.css" type="text/css"> -->
<style>
.today-btn {
  color: #fafafa;
  background: green;
  border: 2px solid #fafafa; }
.ui-disabled  { pointer-events: none !important; }
</style>
<script type="text/javascript" src="calendar.js"></script>
<script>
$(document).ready(function() {

});
</script>
</head>
<body>

<?php include ('../navigation.php'); ?>

<div class="container">
	<div class="row">
		<?php if(!isset($_GET['view'])): ?>
			<?php $_GET['view'] = 'weekly'; ?>
		<?php endif; ?>

		<?php if($_GET['view'] == 'weekly'): ?>
			<?php $active_weekly = 'active_tab'; ?>
		<?php endif; ?>
		<?php if($_GET['view'] == 'monthly'): ?>
			<?php $active_monthly = 'active_tab'; ?>
		<?php endif; ?>
		<?php if($_GET['view'] == 'custom'): ?>
			<?php $active_custom = 'active_tab'; ?>
		<?php endif; ?>
		<?php if($_GET['view'] == '30day'): ?>
			<?php $active_30day = 'active_tab'; ?>
		<?php endif; ?>

		<a href='?type=ticket&view=weekly'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_weekly; ?>" >Week</button></a>
		<a href='?type=ticket&view=monthly'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_monthly; ?>" >Month</button></a>
		<a href='?type=ticket&view=custom'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_custom; ?>" >Custom</button></a>
		<a href='?type=ticket&view=30day'><button type="button" class="btn brand-btn mobile-block mobile-100 <?php echo $active_30day; ?>" >30 Days</button></a>

		<?php if($_GET['view'] == 'weekly'): ?>
			<?php include("weekly.php"); ?>
		<?php endif; ?>
		<?php if($_GET['view'] == 'monthly'): ?>
			<?php include("monthly.php"); ?>
		<?php endif; ?>
		<?php if($_GET['view'] == 'custom'): ?>
			<?php include("custom.php"); ?>
		<?php endif; ?>
		<?php if($_GET['view'] == '30day'): ?>
			<?php include("30day.php"); ?>
		<?php endif; ?>

	</div>
</div>

<?php include ('../footer.php'); ?>