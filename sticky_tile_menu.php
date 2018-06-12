<?PHP
$tile_menu_toggler = $_SESSION['toggle_tile_menu'];
$tile_menu = $_SESSION['software_tile_menu_choice'];
if($tile_menu == 2) {
	?>
	<script>
		$( document ).ready(function() {
			$( ".menu_left" ).nextAll(".container:first").addClass( "shrinker" );
			if (window.matchMedia('(min-width: 768px)').matches) {
			$('.menu_left').height($(window).height());
				if($('.shrinker').height() < $('.menu_left').height()) {
					var menu_heighter = $('.menu_left').height();
					$(".shrinker").css({ height: ''+menu_heighter+'px' });
				}
			}
			var header     = $('header').height();
			var navbar = $('.navbar').height();
			var distance      = (navbar + header);
			if($(window).scrollTop() > topOfOthDiv) { //scrolled past the other div?
					if (window.matchMedia('(min-width: 768px)').matches) {
					$(".menu_left").css({ top: '0px' });
					$(".menu_left").css({ position: 'fixed' });
					}

				} else {
					if (window.matchMedia('(min-width: 768px)').matches) {
					$(".menu_left").css({ position: 'absolute' });
					$(".menu_left").css({ top: ''+distance+'px' });
					}
				}
			var header     = $('header').height();
			var navbar = $('.navbar').height();
			var distance      = (navbar + header);
			var topOfOthDiv = $(".navbar").offset().top;
			$(window).scroll(function() {
				if($(window).scrollTop() > topOfOthDiv) { //scrolled past the other div?
				if (window.matchMedia('(min-width: 768px)').matches) {
					$(".menu_left").css({ top: '0px' });
					$(".menu_left").css({ position: 'fixed' });
				}

				} else {
					if (window.matchMedia('(min-width: 768px)').matches) {
					$(".menu_left").css({ position: 'absolute' });
					$(".menu_left").css({ top: ''+distance+'px' });
					}
				}
			});

		window.onresize = function() {
			if (window.matchMedia('(min-width: 768px)').matches) {
			var header     = $('header').height();
			var navbar = $('.navbar').height();
			var distance      = (navbar + header);
			$(".menu_left").css({ position: 'absolute' });
			$(".menu_left").css({ top: ''+distance+'px' });
			$('.menu_left').height($(window).height());
			if($('.shrinker').height() < $('.menu_left').height()) {
					$('.shrinker').height($('.menu_left').height());
				}
			} else {
				$(".menu_left").css({ position: 'relative' });
				$(".menu_left").css({ top: '-20px' });
			}


			var topOfOthDiv = $(".navbar").offset().top;


		}

		<?php if($tile_menu_toggler == 'hide') { ?>
			$('.menu_left').hide();
			$('.menu_left').next().removeClass('shrinker');
			<?php } else { ?>
			if (window.matchMedia('(min-width: 768px)').matches) {
			$('.menu_left').show();
			}
			<?php } ?>

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
<?php $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM user_settings WHERE contactid='$contactidfortile'"));
	  $classicmenusize = $get_config['classic_menu_size'];
if($classicmenusize == '2') {	?>
<style>

.shrinker {
	display: inline-block !important;
	margin:auto;
}
.container {
  margin-right: auto;
  margin-left: auto;
  padding-left: 15px;
  padding-right: 15px; }
  .shrinker:before, .container:after {
	content: " ";
	display: table; }
  .shrinker:after {
	clear: both; }

		@media (max-width: 767px) {
	.shrinker {
		width: 100% !important;
		margin: auto;
		position: relative;
		}

	.menu_left {
		width: 80%;
		left:0px;
		margin-left:-40%;
		left:50%;
		border-left: 4px outset lightgrey;
		border-right: 4px outset lightgrey;
		overflow:auto;
		max-height:300px;
		position:relative;
		top:-20px;
		border-bottom: 4px outset lightgrey;
		margin-bottom:10px;
		background: rgb(89,106,114);
		background: -moz-linear-gradient(-45deg, rgba(89,106,114,1) 0%, rgba(89,106,114,1) 0%, rgba(206,220,231,1) 100%);
		background: -webkit-linear-gradient(-45deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
		background: linear-gradient(135deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#596a72', endColorstr='#cedce7',GradientType=1 );
	}
		}

	@media (min-width: 768px) {
	.shrinker {
	  width: 520px !important;
		margin: auto;
		position: relative;
		margin-top:0px;
		margin-left: 220px;
		} }

  @media (min-width: 992px) {
	.shrinker {
	  width: 740px !important;
		margin: auto;
		position: relative;
		margin-left: 220px;} }

 @media (min-width: 1100px) {
	.shrinker {
	  width: 840px !important;
		margin: auto;
		position: relative;
		margin-left: 220px;} }
  @media (min-width: 1200px) {
	.shrinker {
		width: 850px !important;
		margin: auto;
		position: relative;
		margin-left: 320px;
	} }
	@media (min-width: 1300px) {
	.shrinker {
		width: 950px !important;
	} }
  @media (min-width: 1400px) {
	.shrinker {
	  width: 1050px !important;
	    margin: auto;
		position: relative;
		margin-left: 320px;
	} }
	@media (min-width: 1520px) {
	.shrinker {
	  width: 1170px !important;
	    margin: auto;
		position: relative;
		margin-left: 320px;
	} }
  @media (min-width: 1650px) {
	.shrinker {
	  width: 100% !important;
		max-width: 1300px !important;
		margin: auto;
		position: relative;
		margin-left: 320px;
} }
@media (min-width: 1750px) {
	.shrinker {
		max-width: 1400px !important;
} }
@media (min-width: 1888px) {
	.shrinker {
		max-width: 1530px !important;
} }
		@media (min-width: 768px) {
.menu_left {
	width: 100%;
	left:0px;
	border-left: 4px outset lightgrey;
	border-right: 4px outset lightgrey;
	overflow:auto;
	position:fixed;
	max-height: 1000000px;
	border-bottom: 4px outset lightgrey;
	margin-bottom:10px;
	background: rgb(89,106,114);
    background: -moz-linear-gradient(-45deg, rgba(89,106,114,1) 0%, rgba(89,106,114,1) 0%, rgba(206,220,231,1) 100%);
    background: -webkit-linear-gradient(-45deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    background: linear-gradient(135deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#596a72', endColorstr='#cedce7',GradientType=1 );
}
		}
@media (min-width: 768px) {
.menu_left, .container_pusher {
	width: 200px;
} }
@media (min-width: 992px) {
.menu_left, .container_pusher {
	width: 200px;
} }
@media (min-width: 1200px) {
.menu_left, .container_pusher {
	width: 300px;
} }
@media (min-width: 1650px) {
.menu_left,.container_pusher {
				  width: 300px;
} }
.live-search-list {
	padding-top:50px;
	padding-bottom:100px;
}
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
.menu_left ul,.menu_left li {
	list-style:none;
	padding-left:0px;

	margin-bottom:10px;
}
 .menu_left li a{
	font-size:16px !important;
	color: white !important;
	text-decoration:none !important;
 }

  .menu_left li a:hover {
	color: white !important;
	text-decoration:underline !important;
 }
.menu_left li {
	padding:7px;
	border-bottom: 1px solid white;
}
@media (max-width:767px) {
	.menu_left {
		display:none;
	}
}
</style>
<?php } else { ?>
<style>

.shrinker {
	display: inline-block !important;
	margin:auto;
}
.container {
  margin-right: auto;
  margin-left: auto;
  padding-left: 15px;
  padding-right: 15px; }
  .shrinker:before, .container:after {
	content: " ";
	display: table; }
  .shrinker:after {
	clear: both; }

		@media (max-width: 767px) {
	.shrinker {
		width: 100% !important;
		margin: auto;
		position: relative;
		}

	.menu_left {
		width: 80%;
		left:0px;
		margin-left:-40%;
		left:50%;
		border-left: 4px outset lightgrey;
		border-right: 4px outset lightgrey;
		overflow:auto;
		max-height:300px;
		position:relative;
		font-size:13px;
		top:-20px;
		border-bottom: 4px outset lightgrey;
		margin-bottom:10px;
		background: rgb(89,106,114);
		background: -moz-linear-gradient(-45deg, rgba(89,106,114,1) 0%, rgba(89,106,114,1) 0%, rgba(206,220,231,1) 100%);
		background: -webkit-linear-gradient(-45deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
		background: linear-gradient(135deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#596a72', endColorstr='#cedce7',GradientType=1 );
	}
		}

	@media (min-width: 768px) {
	.shrinker {
	  width: 520px !important;
		margin: auto;
		position: relative;
		margin-top:0px;
		margin-left: 220px;
		} }

		@media (min-width: 868px) {
	.shrinker {
	  width: 640px !important;
		margin: auto;
		position: relative;
		margin-top:0px;
		margin-left: 210px;
		} }

  @media (min-width: 992px) {
	.shrinker {
	  width: 760px !important;
		margin: auto;
		position: relative;
		margin-left: 210px;} }
	 @media (min-width: 1100px) {
	.shrinker {
	  width: 870px !important;
		margin: auto;
		position: relative;
		margin-left: 210px;} }
  @media (min-width: 1200px) {
	.shrinker {
		/* width: 950px !important; */
        width: 1015px !important;
		margin: auto;
		position: relative;
		margin-left: 230px;
	} }
  @media (min-width: 1400px) {
	.shrinker {
	  width: 1150px !important;
	    margin: auto;
		position: relative;
		margin-left: 230px;
	} }
	 @media (min-width: 1500px) {
	.shrinker {
	  width: 1250px !important;
	} }
  @media (min-width: 1650px) {
	.shrinker {
	  width: 100% !important;
		max-width: 1400px !important;
		position: relative;
} }

  @media (min-width: 1888px) {
	.shrinker {
		/* max-width: 1630px !important; */
        max-width: 1510px !important;
} }
		@media (min-width: 768px) {
.menu_left {
	width: 100%;
	left:0px;
	border-left: 4px outset lightgrey;
	border-right: 4px outset lightgrey;
	overflow:auto;
	padding-left:9px;
	padding-right:9px;
	position:fixed;
	max-height: 1000000px;
	border-bottom: 4px outset lightgrey;
	margin-bottom:10px;
	background: rgb(89,106,114);
    background: -moz-linear-gradient(-45deg, rgba(89,106,114,1) 0%, rgba(89,106,114,1) 0%, rgba(206,220,231,1) 100%);
    background: -webkit-linear-gradient(-45deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    background: linear-gradient(135deg, rgba(89,106,114,1) 0%,rgba(89,106,114,1) 0%,rgba(206,220,231,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#596a72', endColorstr='#cedce7',GradientType=1 );
}
		}
@media (min-width: 768px) {
.menu_left, .container_pusher {
	width: 200px;
} }
@media (min-width: 992px) {
.menu_left, .container_pusher {
	width: 200px;
} }
@media (min-width: 1200px) {
.menu_left, .container_pusher {
	width: 225px;
} }
@media (min-width: 1650px) {
.menu_left,.container_pusher {
				  width: 225px;
} }
.live-search-list {
	padding-top:10px;
	padding-bottom:100px;
}
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
.menu_left ul,.menu_left li {
	list-style:none;
	padding-left:0px;

	margin-bottom:10px;
}
 .menu_left li a{
	font-size:13px !important;
	color: white !important;
	text-decoration:none !important;
 }

  .menu_left li a:hover {
	color: white !important;
	text-decoration:underline !important;
 }
.menu_left li {
	border-bottom: 1px solid white;
}
@media (max-width:767px) {
	.menu_left {
		display:none;
	}
}
</style>
<?php } ?>

	<div class="container menu_left" style='<?php if($tile_menu_toggler == 'hide') { echo 'display:none;'; } else { echo 'display:none;'; } ?> float:left;'>

		<ul class="live-search-list">
			<center>
			<input type='text' name='x' class='clickerhide form-control tile-search live-search-box'>
			<br>
			</center>
			<span style='position:relative; top:5px;'>
			<?php include('tiles.php'); ?>
			</span>
		</ul>
	</div>

<?php } ?>