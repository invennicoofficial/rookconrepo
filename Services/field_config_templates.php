<?php
/*
 * Services Tile Main Page
 */
error_reporting(0);
include ('../include.php');
?>
<script type="text/javascript">
$(document).ready(function() {

});
</script>
</head>

<body>
<?php
	include_once ('../navigation.php');
checkAuthorised('services');
?>

<div class="container">
    <div class="row">
		<div class="main-screen"><?php
            include('tile_header.php'); ?>
            
            <div class="tile-container">
                <?php include('template_list.php'); ?>
            </div><!-- .tile-container -->

        </div><!-- .main-screen -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>