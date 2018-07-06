<?php
/* 
 * Software Guide
 * This is what the software users will refer when they want to see how a Tile or Sub Tab works
 */

error_reporting(0);
include_once('../include.php');
include ('../database_connection_htg.php');
?>
<script>
    $(document).ready(function() {
        $(window).resize(function() {
            $('.main-screen').css('padding-bottom',0);
            if($('.main-screen .main-screen').is(':visible')) {
                var available_height = window.innerHeight - $(footer).outerHeight() - $('.sidebar:visible').offset().top;
                if(available_height > 200) {
                    $('.main-screen .main-screen').outerHeight(available_height).css('overflow-y','auto');
                    $('.sidebar').outerHeight(available_height).css('overflow-y','auto');
                    $('.search-results').outerHeight(available_height).css('overflow-y','auto');
                }
                var sidebar_height = $('.tile-sidebar').outerHeight(true);
            }
        }).resize();
        
        $('.panel-heading.mobile_load').click(loadPanel);
        
        $('.search-text').keypress(function(e) {
            if (e.which==13) {
                var search = this.value;
                window.location.replace('index.php?s='+search);
            }
        });
    });
    
    function loadPanel() {
        var panel = $(this).closest('.panel').find('.panel-body');
        var guide = panel.data('guide');
        panel.html('');
        
        $.ajax({
            url: 'guide_ajax_all.php?fill=load_panel&guide='+guide,
            method: 'GET',
            response: 'html',
            success: function(response) {
                panel.html(response);
            }
        });
    }
</script>
</head>

<body>
<?php
    include_once ('../navigation.php');
    checkAuthorised('software_guide');
?>
<div class="container">
	<div class="iframe_overlay" style="display:none;">
		<div class="iframe">
			<div class="iframe_loading">Loading...</div>
			<iframe src=""></iframe>
		</div>
	</div>
	<div class="row">
		<div class="main-screen">
            
            <!-- Tile Header -->
            <div class="tile-header double-pad-bottom">
                <div class="col-xs-12">
                    <h1><a href="index.php"><span class="pull-left">Software Guide</span></a></h1>
                </div>
                <div class="clearfix"></div>
            </div><!-- .tile-header -->

            <?php //Mobile view. Show on mobile. Hide on desktop. ?>
            <div id="guide_accordions" class="sidebar show-on-mob panel-group block-panels col-xs-12 form-horizontal"><?php
                $tiles = mysqli_query($dbc_htg, "SELECT `tile` FROM `how_to_guide` GROUP BY `tile` ORDER BY `tile`");
                if ( $tiles->num_rows > 0 ) {
                    foreach ( $tiles as $tile ) {
                        $guide = mysqli_query($dbc_htg, "SELECT `guideid`, `tile`, `subtab` FROM `how_to_guide` WHERE `tile`='{$tile['tile']}' AND `deleted`=0 ORDER BY `sort_order`");
                        if ( $guide->num_rows > 0 ) {
                            while ( $row=mysqli_fetch_assoc($guide) ) {
                                $tab_id = $row['guideid']; ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading mobile_load">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#guide_accordions" href="#collapse_<?= $tab_id ?>">
                                                <?= $row['tile'] . ': ' . $row['subtab']; ?><span class="glyphicon glyphicon-plus"></span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_<?= $tab_id ?>" class="panel-collapse collapse">
                                        <div class="panel-body" data-guide="<?= $row['guideid'] ?>">
                                            Loading...
                                        </div>
                                    </div>
                                </div><?php
                            }
                        }
                    }
                } ?>
            </div><!-- #guide_accordions -->

            <?php //Desktop view. Desktop sidebar. ?>
            <div class="collapsible hide-titles-mob sidebar tile-sidebar sidebar-override inherit-height double-gap-top" style="min-width:275px;">
                <ul>
                    <li class="search-box"><input type="text" class="search-text form-control" placeholder="Search Software Guide" /></li><?php
                    $tiles = mysqli_query($dbc_htg, "SELECT `tile` FROM `how_to_guide` GROUP BY `tile` ORDER BY `tile`");
                    if ( $tiles->num_rows > 0 ) {
                        foreach ( $tiles as $tile ) {
                            $guide = mysqli_query($dbc_htg, "SELECT `guideid`, `tile`, `subtab` FROM `how_to_guide` WHERE `tile`='{$tile['tile']}' AND `deleted`=0 ORDER BY `sort_order`");
                            echo '<li class="collapsed cursor-hand" data-toggle="collapse" data-target="#collapse_'. strtolower($tile['tile']) .'">'. $tile['tile'] . '<span class="arrow"></span>';
                            if ( $guide->num_rows > 0 ) {
                                echo '<ul id="collapse_'. strtolower($tile['tile']) .'" class="collapse">';
                                    $url_tile = trim(filter_var($_GET['tile'], FILTER_SANITIZE_STRING ));
                                    $url_guideid = preg_replace('/[^0-9]/', '', $_GET['guide']);
                                    while ( $row=mysqli_fetch_assoc($guide) ) {
                                        if ( $url_tile==$row['tile'] ) { ?>
                                            <script>
                                                $(document).ready(function() {
                                                    $('#collapse_<?= strtolower($tile['tile']) ?>').collapse('show');
                                                });
                                            </script><?php
                                        }
                                        echo '<li class="'.( $url_guideid==$row['guideid'] ? 'active' : '' ).'"><a href="?guide='.$row['guideid'].'&tile='.$row['tile'].'">'. $row['subtab'] .'</a></li>';
                                    }
                                echo '</ul>';
                            }
                            echo '</li>';
                        }
                    } else {
                        echo '<li>No software guides available</li>';
                    } ?>
                </ul>
            </div><!-- .sidebar -->

            <?php //Desktop view. Desktop content. ?>
            <div class="scale-to-fill has-main-screen hide-titles-mob double-gap-top" style="margin-bottom:-20px;">
                <div class="main-screen form-horizontal">
                    <div class="dashboard-container no-overflow-x double-pad-bottom"><?php
                        $search_term = filter_var($_GET['s'], FILTER_SANITIZE_STRING);
                        if ( !empty($search_term) ) {
                            echo '<h2>Search: '. $search_term .' </h2>';
                            $search = mysqli_query($dbc_htg, "SELECT `guideid`, `tile`, `subtab` FROM `how_to_guide` WHERE `tile` LIKE '$search_term%' OR `subtab` LIKE '%$search_term%' OR `description` LIKE '%$search_term%' AND `deleted`=0");
                            if ( $search->num_rows > 0 ) {
                                echo '<ul>';
                                    while ( $row=mysqli_fetch_assoc($search) ) {
                                        echo '<li><a href="?guide='.$row['guideid'].'&tile='.$row['tile'].'">'. $row['tile'] . ': ' . $row['subtab'] .'</a></li>';
                                    }
                                echo '</ul>';
                            } else {
                                echo 'Nothing found for ' . $search_term;
                            }
                        
                        } else {
                            $guideid = preg_replace('/[^0-9]/', '', $_GET['guide']);
                            if ( !empty($guideid) ) {
                                $guide = mysqli_query($dbc_htg, "SELECT `tile`, `subtab`, `description` FROM `how_to_guide` WHERE `guideid`='$guideid' AND `deleted`=0");
                                if ( $guide->num_rows > 0 ) {
                                    while ( $row=mysqli_fetch_assoc($guide) ) {
                                        echo '<h2>'. $row['tile'] . ': '. $row['subtab'] .'</h2>';
                                        echo html_entity_decode($row['description']);
                                    }
                                } else {
                                    echo '<h4>Requested software guide is not available at this time. Please check back later for updates.</h4>';
                                }
                            } else {
                                $index_tiles = mysqli_query($dbc_htg, "SELECT `tile` FROM `how_to_guide` GROUP BY `tile` ORDER BY `tile`");
                                echo '<h2>Index</h2>';
                                echo '<ul class="guide-index">';
                                    if ( $index_tiles->num_rows > 0 ) {
                                        foreach ( $index_tiles as $index_tile ) {
                                            $index_subtabs = mysqli_query($dbc_htg, "SELECT `guideid`, `tile`, `subtab` FROM `how_to_guide` WHERE `tile`='{$index_tile['tile']}' AND `deleted`=0 ORDER BY `sort_order`");
                                            echo '<li>Tile: '. $index_tile['tile'];
                                            if ( $index_subtabs->num_rows > 0 ) {
                                                echo '<ul>';
                                                    while ( $row_subtab=mysqli_fetch_assoc($index_subtabs) ) {
                                                        echo '<li><a href="?guide='.$row_subtab['guideid'].'&tile='.$row_subtab['tile'].'">'. $row_subtab['subtab'] .'</a></li>';
                                                    }
                                                echo '</ul>';
                                            }
                                            echo '</li>';
                                        }
                                    }
                                echo '</ul>';
                            }
                        }?>
                    </div>
                </div>
            </div><!-- .has-main-screen -->
            
		</div><!-- .main-screen -->
	</div><!-- .row -->
</div><!-- .container -->

<div class="clearfix"></div>

<?php include('../footer.php'); ?>