<!-- TILE MENUS -->
<?php
include_once('mobile_detect.php');
$mobile_detect1 = new Mobile_Detect();
?>

<?php 
$tile_menu_toggler = $_SESSION['toggle_tile_menu'];
$tile_menu = $_SESSION['software_tile_menu_choice'];
if($tile_menu == 1) { 
// DROP DOWN MENU CODE
	  $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM user_settings WHERE contactid='$contactidfortile'"));
	  $dropdown_menu_size = $get_config['dropdown_menu_size'];
	  if($dropdown_menu_size == '2') {	?>
<style>
.tile-search {
    background: white url("<?php echo WEBSITE_URL; ?>/img/Magnifying_glass_icon.png") no-repeat left;
	display:inline-block; 
	background-size: 20px 20px;
	max-width:90%; 
	color:black;
	padding-left:30px;
	background-position: 5px; 
	margin:5px;
	margin:auto;
}
.tile-searcher {
	width:40px;
	display:inline-block;
	float:left;
	margin:5px;
	cursor: pointer;
}
.menu-box-dropper {
	position:absolute;
	max-width:300px;
	min-width:300px;
	border-left: 4px outset lightgrey;
	border-right: 4px outset lightgrey;
	overflow:auto;
	z-index:99999;
	border-bottom: 4px outset lightgrey;
	display:none;
	margin-bottom:10px;
	top:55px;
	padding-top:10px;
	background: rgb(89,106,114);
    background: -moz-linear-gradient(-45deg, rgba(89,106,114,1) 0%, rgba(89,106,114,1) 0%, rgba(206,220,231,1) 100%);
    background: -webkit-linear-gradient(-45deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    background: linear-gradient(135deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#596a72', endColorstr='#cedce7',GradientType=1 );
}
.menu-box-dropper ul,.menu-box-dropper li {
	list-style:none;
	padding-left:0px;
	
	margin-bottom:10px;
}
 .menu-box-dropper li a{
	font-size:16px !important;
 }
.menu-box-dropper li {
	padding:7px;
	border-bottom: 1px solid white;
}

	</style> 
	<?php } else { ?>
	<style>
.tile-search {
    background: white url("<?php echo WEBSITE_URL; ?>/img/Magnifying_glass_icon.png") no-repeat left;
	display:inline-block; 
	background-size: 20px 20px;
	max-width:90%; 
	font-size:13px;
	color:black;
	padding-left:30px;
	background-position: 5px; 
	margin:5px;
	height:25px;
	margin:auto;
}
.tile-searcher {
	width:40px;
	display:inline-block;
	float:left;
	margin:5px;
	cursor: pointer;
}
.menu-box-dropper {
	position:absolute;
	max-width:200px;
	min-width:200px;
	border-left: 4px outset lightgrey;
	border-right: 4px outset lightgrey;
	overflow:auto;
	z-index:99999;
	border-bottom: 4px outset lightgrey;
	display:none;
	margin-bottom:10px;
	top:55px;
	padding-top:5px;
	background: rgb(89,106,114);
    background: -moz-linear-gradient(-45deg, rgba(89,106,114,1) 0%, rgba(89,106,114,1) 0%, rgba(206,220,231,1) 100%);
    background: -webkit-linear-gradient(-45deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    background: linear-gradient(135deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#596a72', endColorstr='#cedce7',GradientType=1 );
}
.menu-box-dropper ul,.menu-box-dropper li {
	list-style:none;
	padding-left:0px;
	
	margin-bottom:3px;
}
 .menu-box-dropper li a{
	font-size:13px !important;
	color: white !important;
	font-weight:normal !important;
 }
 .menu-box-dropper li a:hover{
	 text-decoration:underline !important;
	color: white !important;
 }
.menu-box-dropper li {
	padding-left:5px;
	padding-right: 5px;
	border-bottom: 1px solid white;
}
</style>
<?php } ?>
<script>
$( document ).ready(function() {

$("body").click(function(e){
  //you can then check what has been clicked
  var target = $(e.target);  
  if (target.is(".menu-box-dropper")) { }
}); 

$('.tile-searcher').click(function(){
	
    $('.menu-box-dropper').slideToggle();
	$('.tile-search').focus();

});

});

$(document).mouseup(function (e)
{
    var container = $(".menu-box-dropper");
	var menu_threelines = $(".tile-searcher");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
		if (!menu_threelines.is(e.target) // if the target of the click isn't the container...
        && menu_threelines.has(e.target).length === 0) // ... nor a descendant of the container
		{
			container.hide(500);
		}
	}
});

  jQuery(document).ready(function($){

$('.live-search-list li').each(function(){
$(this).attr('data-search-term', $(this).text().toLowerCase());
});

$('.live-search-box').on('keyup', function(){

var searchTerm = $(this).val().toLowerCase();

    $('.live-search-list li').each(function(){

        if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
            $(this).show();
			
        } else {
            $(this).hide();
        }

    });

});

});

</script>

	<img src='<?php echo WEBSITE_URL; ?>/img/menu_threelines.png' class='tile-searcher hide-on-mobile'>
	<div class='clickerhide menu-box-dropper brand-nav '>
	<center>
	<input type='text' name='x' class='clickerhide form-control tile-search live-search-box'>
	</center>
	<ul style='margin-top:5px;' class="live-search-list">
		<?php include('tiles.php'); ?>
	</ul>
	</div>
<?php

// END DROP DOWN MENU CODE
// BEGIN STICKY MENU
 } else if ($tile_menu == 2) { ?>
 

<script>
$( document ).ready(function() {

$('.tile-searcher').click(function(){
	if($('.menu_left').css('display') == 'none')
{
	$(".menu_left").css({ display: 'inline-block' });
	$(".menu_left").next().addClass('shrinker');
	var contactid = $('.contactidgetter').val();
	$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "<?php echo WEBSITE_URL; ?>/ajax_all.php?fill=toggle_tile_menu&val=&contactid="+contactid,
				dataType: "html",   //expect html to be returned
				success: function(response){
				}
			});		
} else {
	$(".menu_left").css({ display: 'none' });
	$(".menu_left").next().removeClass('shrinker');
	var contactid = $('.contactidgetter').val();
	$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "<?php echo WEBSITE_URL; ?>/ajax_all.php?fill=toggle_tile_menu&val=hide&contactid="+contactid,
				dataType: "html",   //expect html to be returned
				success: function(response){
				}
	});		
}
	
});

if (window.matchMedia('(max-width: 767px)').matches) {
	var contactid = $('.contactidgetter').val();
	
	$.ajax({    //create an ajax request to load_page.php
				type: "GET",
				url: "<?php echo WEBSITE_URL; ?>/ajax_all.php?fill=toggle_tile_menu&val=hide&contactid="+contactid,
				dataType: "html",   //expect html to be returned
				success: function(response){
				}
	});	
	setTimeout(
  function() 
  {
    $(".menu_left").hide();
  }, 10);
}

});

</script>

	<?PHP
	$contactidfortile = $_SESSION['contactid'];
	?>
	<input type='hidden' class='contactidgetter' value='<?php echo $contactidfortile; ?>'>
	<img src='<?php echo WEBSITE_URL; ?>/img/menu_threelines.png' class='tile-searcher'>

<?php

// END STICKY MENU
	
 } ?>


