<?php
/*
Dashboard
*/
include ('../include.php');
checkAuthorised('infogathering');
include ('manual_checklist.php');
error_reporting(0);
?>

</head>
<script type="text/javascript" src="infogathering.js"></script>
<body>

<?php include_once ('../navigation.php');

?>

<div class="container">
    <div class="row hide_on_iframe">
        <div class="main-screen">

	        <!-- Tile Header -->
	        <div class="tile-header">
	            <div class="col-xs-12 col-sm-4">
	                <h1>
	                    <span class="pull-left" style="margin-top: -5px;"><a href="infogathering.php?tab=Form" class="default-color">Information Gathering</a></span>
	                    <span class="clearfix"></span>
	                </h1>
	            </div>
	            <div class="col-xs-12 col-sm-8 text-right settings-block">
	                <?php if ( config_visible_function ( $dbc, 'profile' ) == 1 ) { ?>
	                    <div class="pull-right gap-left top-settings">
	                        <a href="field_config_infogathering.php" class="mobile-block pull-right "><img title="Tile Settings" src="<?= WEBSITE_URL; ?>/img/icons/settings-4.png" class="settings-classic wiggle-me" width="30"></a>
	                    </div>
	                    <a href="field_config_style.php" class="btn brand-btn pull-right">PDF Style</a>
					<?php } ?>
					<?php if ( check_subtab_persmission($dbc, 'infogathering', ROLE, 'reporting') === TRUE ) { ?>
					<a href="manual_reporting.php?type=infogathering" class="btn brand-btn pull-right">Reporting</a>
					<?php } ?>
					<?php if(vuaed_visible_function($dbc, 'infogathering') == 1) { ?>
	                	<a href="add_manual.php?type=infogathering" class="btn brand-btn pull-right">Add Information Gathering</a>
	                <?php } ?>
	            </div>
	            <div class="clearfix"></div>
	        </div><!-- .tile-header -->

            <div class="tile-container">
            	<!-- Notice --><?php
                $notes = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT note FROM notes_setting WHERE subtab='ig_information_gathering'"));
                $note = $notes['note'];
                    
                if ( !empty($note) ) { ?>
                    <div class="notice double-gap-bottom popover-examples">
                        <div class="col-sm-1 notice-icon"><img src="../img/info.png" class="wiggle-me" width="25"></div>
                        <div class="col-sm-11">
                            <span class="notice-name">NOTE:</span>
                            <?= $note; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div><?php
                } ?>

            	<div class="collapsible tile-sidebar set-section-height">
            		<?php include('tile_sidebar.php'); ?>
            	</div>

            	<div class="fill-to-gap tile-content set-section-height" style="padding: 0;">
            		<div class="main-screen-details">
            			<div class="sidebar" style="padding: 1em; margin: 0 auto; overflow-y: auto;">
			                <?php
			                if(!empty($_GET['category'])) {
			                    echo manual_checklist($dbc, '35', '20', '20', $_GET['category']);
			                }
			                ?>
		        		</div>
		        	</div>
		        </div>
			</div>
		    <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php include ('../footer.php'); ?>
