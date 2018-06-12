<?php
/*
 * Website
 */
include ('../include.php');
error_reporting(0);
?>
</head>

<body>
<?php
	include_once ('../navigation.php');
    checkAuthorised('website');
?>

<div class="container triple-pad-bottom">
    <div class="row">
		<div class="col-md-12">
		
            <div class="col-sm-10"><h1>Website Dashboard</h1></div>
            <div class="col-sm-2 gap-top"><?php
                /*
                if ( config_visible_function ( $dbc, 'sales' ) == 1 ) {
                    echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
                    echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                } */?>
            </div>
            <div class="clearfix gap-bottom"></div>
        
            <div class="tab-container mobile-100-container gap-left">
                <div class="pull-left tab"><a href="website.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Home Page</button></a></div>
                <div class="pull-left tab"><a href="website_promotions.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Promotions Page</button></a></div>
            </div>
            <div class="clearfix gap-bottom"></div>
            <div class="tab-container mobile-100-container gap-left">
                <div class="pull-left tab"><a href="website.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Banners</button></a></div>
                <div class="pull-left tab"><a href="website_headings.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Block Headings</button></a></div>
                <div class="pull-left tab"><a href="website_videos.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Videos</button></a></div>
                <div class="pull-left tab"><a href="website_logos.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Brand Logos</button></a></div>
            </div>
            <div class="clearfix gap-bottom"></div>
		
            <div class="notice double-gap-bottom double-gap-top popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Changes you make here will be updated immmediately on the website.</div>
                <div class="clearfix"></div>
            </div>
            
            <div class="clearfix"></div>

            <h2 class="gap-left">Entertainment Videos</h2>
            <?php
                if ( $_SERVER['REQUEST_METHOD']=='POST' ) {
                    $video_title_1  = filter_var ( trim($_POST['video_title_1']), FILTER_SANITIZE_STRING );
                    $video_desc_1   = filter_var ( trim($_POST['video_desc_1']), FILTER_SANITIZE_STRING );
                    $video_link_1   = filter_var ( trim($_POST['video_link_1']), FILTER_SANITIZE_STRING );
                    $video_title_2  = filter_var ( trim($_POST['video_title_2']), FILTER_SANITIZE_STRING );
                    $video_desc_2   = filter_var ( trim($_POST['video_desc_2']), FILTER_SANITIZE_STRING );
                    $video_link_2   = filter_var ( trim($_POST['video_link_2']), FILTER_SANITIZE_STRING );
                    $video_title_3  = filter_var ( trim($_POST['video_title_3']), FILTER_SANITIZE_STRING );
                    $video_desc_3   = filter_var ( trim($_POST['video_desc_3']), FILTER_SANITIZE_STRING );
                    $video_link_3   = filter_var ( trim($_POST['video_link_3']), FILTER_SANITIZE_STRING );
                    $video_title_4  = filter_var ( trim($_POST['video_title_4']), FILTER_SANITIZE_STRING );
                    $video_desc_4   = filter_var ( trim($_POST['video_desc_4']), FILTER_SANITIZE_STRING );
                    $video_link_4   = filter_var ( trim($_POST['video_link_4']), FILTER_SANITIZE_STRING );
                    $video_title_5  = filter_var ( trim($_POST['video_title_5']), FILTER_SANITIZE_STRING );
                    $video_desc_5   = filter_var ( trim($_POST['video_desc_5']), FILTER_SANITIZE_STRING );
                    $video_link_5   = filter_var ( trim($_POST['video_link_5']), FILTER_SANITIZE_STRING );
                    
                    $video_1 = $video_2 = $video_3 = $video_4 = $video_5 = null;
                    
                    if ( !empty($video_title_1) && !empty($video_desc_1) && !empty($video_link_1) ) {
                        $video_1 = $video_title_1 . ',' . $video_desc_1 . ',' . $video_link_1;
                    }
                    if ( !empty($video_title_2) && !empty($video_desc_2) && !empty($video_link_2) ) {
                        $video_2 = $video_title_2 . ',' . $video_desc_2 . ',' . $video_link_2;
                    }
                    if ( !empty($video_title_3) && !empty($video_desc_3) && !empty($video_link_3) ) {
                        $video_3 = $video_title_3 . ',' . $video_desc_3 . ',' . $video_link_3;
                    }
                    if ( !empty($video_title_4) && !empty($video_desc_4) && !empty($video_link_4) ) {
                        $video_4 = $video_title_4 . ',' . $video_desc_4 . ',' . $video_link_4;
                    }
                    if ( !empty($video_title_5) && !empty($video_desc_5) && !empty($video_link_5) ) {
                        $video_5 = $video_title_5 . ',' . $video_desc_5 . ',' . $video_link_5;
                    }
                    
                    $result_videos = mysqli_query ( $dbc, "SELECT `webid` FROM `website`" );
                    
                    if ( mysqli_num_rows($result_videos) > 0 ) {
                        $query_videos = "UPDATE `website` SET `video_1`='$video_1', `video_2`='$video_2', `video_3`='$video_3', `video_4`='$video_4', `video_5`='$video_5' WHERE `webid`='1'";
                    } else {
                        $query_videos = "INSERT INTO `website` (`video_1`, `video_2`, `video_3`, `video_4`, `video_5`) VALUES ('$video_1', '$video_2', '$video_3', '$video_4', '$video_5')";
                    }
                    
                    $result = mysqli_query ( $dbc, $query_videos );
                    
                    if ( mysqli_affected_rows($dbc) > 0 ) {
                        echo '<div class="alert alert-success gap-left gap-right double-gap-top">Videos on the website updated.</div>';
                    } elseif ( mysqli_affected_rows($dbc) == 0 ) {
                        echo '<div class="alert alert-warning gap-left gap-right double-gap-top">Something went wrong. Videos on the website were not updated.</div>';
                    } else {
                        echo '<div class="alert alert-danger gap-left gap-right double-gap-top">Something went wrong. Videos on the website were not updated.</div>';
                    }
                }
            ?>
            <form method="post" action="" class="form-inline" role="form"><?php
                $result_videos = mysqli_query ( $dbc, "SELECT * FROM `website` WHERE `webid`=1" );
                
                $title_1 = $desc_1 = $link_1 = '';
                $title_2 = $desc_2 = $link_2 = '';
                $title_3 = $desc_3 = $link_3 = '';
                $title_4 = $desc_4 = $link_4 = '';
                $title_5 = $desc_5 = $link_5 = '';
                
                if ( mysqli_num_rows($result_videos) > 0 ) {
                    while ( $row_video=mysqli_fetch_assoc($result_videos) ) {
                        list( $title_1, $desc_1, $link_1 ) = explode ( ',', $row_video['video_1'] );
                        list( $title_2, $desc_2, $link_2 ) = explode ( ',', $row_video['video_2'] );
                        list( $title_3, $desc_3, $link_3 ) = explode ( ',', $row_video['video_3'] );
                        list( $title_4, $desc_4, $link_4 ) = explode ( ',', $row_video['video_4'] );
                        list( $title_5, $desc_5, $link_5 ) = explode ( ',', $row_video['video_5'] );
                    }
                }
                ?>
                <h3 class="double-gap-bottom">Video 1</h3>
                <div class="col-sm-4">Video Title</div>
                <div class="col-sm-8"><input type="text" name="video_title_1" class="form-control" value="<?= $title_1; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Video Description</div>
                <div class="col-sm-8"><input type="text" name="video_desc_1" class="form-control" value="<?= $desc_1; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">YouTube Video ID<br /><small>e.g. https://www.youtube.com/watch?v=<b>zB4I68XVPzQ</b></small></div>
                <div class="col-sm-8"><input type="text" name="video_link_1" class="form-control" value="<?= $link_1; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Video 2</h3>
                <div class="col-sm-4">Video Title</div>
                <div class="col-sm-8"><input type="text" name="video_title_2" class="form-control" value="<?= $title_2; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Video Description</div>
                <div class="col-sm-8"><input type="text" name="video_desc_2" class="form-control" value="<?= $desc_2; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">YouTube Video ID<br /><small>e.g. https://www.youtube.com/watch?v=<b>zB4I68XVPzQ</b></small></div>
                <div class="col-sm-8"><input type="text" name="video_link_2" class="form-control" value="<?= $link_2; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Video 3</h3>
                <div class="col-sm-4">Video Title</div>
                <div class="col-sm-8"><input type="text" name="video_title_3" class="form-control" value="<?= $title_3; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Video Description</div>
                <div class="col-sm-8"><input type="text" name="video_desc_3" class="form-control" value="<?= $desc_3; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">YouTube Video ID<br /><small>e.g. https://www.youtube.com/watch?v=<b>zB4I68XVPzQ</b></small></div>
                <div class="col-sm-8"><input type="text" name="video_link_3" class="form-control" value="<?= $link_3; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Video 4</h3>
                <div class="col-sm-4">Video Title</div>
                <div class="col-sm-8"><input type="text" name="video_title_4" class="form-control" value="<?= $title_4; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Video Description</div>
                <div class="col-sm-8"><input type="text" name="video_desc_4" class="form-control" value="<?= $desc_4; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">YouTube Video ID<br /><small>e.g. https://www.youtube.com/watch?v=<b>zB4I68XVPzQ</b></small></div>
                <div class="col-sm-8"><input type="text" name="video_link_4" class="form-control" value="<?= $link_4; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Video 5</h3>
                <div class="col-sm-4">Video Title</div>
                <div class="col-sm-8"><input type="text" name="video_title_5" class="form-control" value="<?= $title_5; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Video Description</div>
                <div class="col-sm-8"><input type="text" name="video_desc_5" class="form-control" value="<?= $desc_5; ?>" /></div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">YouTube Video ID<br /><small>e.g. https://www.youtube.com/watch?v=<b>zB4I68XVPzQ</b></small></div>
                <div class="col-sm-8"><input type="text" name="video_link_5" class="form-control" value="<?= $link_5; ?>" /></div>
                <div class="clearfix"></div>
                
                <div class="triple-gap-top">
                    <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                </div>
            </form>
        
    </div><!-- .col-md-12 -->
</div><!-- .row -->

<?php include ('../footer.php'); ?>
