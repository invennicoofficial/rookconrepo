<?php
/*
 * Website
 */
//header('Location: website_videos.php');

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
                <div class="pull-left tab"><a href="website.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Banners</button></a></div>
                <div class="pull-left tab"><a href="website_headings.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Block Headings</button></a></div>
                <div class="pull-left tab"><a href="website_videos.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Videos</button></a></div>
                <div class="pull-left tab"><a href="website_logos.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Brand Logos</button></a></div>
            </div>
            <div class="clearfix gap-bottom"></div>
		
            <div class="notice double-gap-bottom double-gap-top popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11"><span class="notice-name">NOTE:</span>
                Changes you make here will be updated immmediately on the website. Recommended logo image size is 1640x425 pixels.</div>
                <div class="clearfix"></div>
            </div>
            
            <div class="clearfix"></div>

            <h2 class="gap-left">Banners</h2>
            <?php
                if ( $_SERVER['REQUEST_METHOD']=='POST' ) {
                    
                    if ( !file_exists ( 'download' ) ) {
                        mkdir ( 'download', 0777, true );
                    }
                    
                    if ( $_FILES['banner_image_1']['size'] != 0 && $_FILES['banner_image_1']['error'] == 0 ) {
                        $banner_1 = getimagesize ( $_FILES['banner_image_1']['tmp_name'] );
                        if ( $banner_1 ) {
                            move_uploaded_file ( $_FILES['banner_image_1']['tmp_name'], 'download/'.$_FILES['banner_image_1']['name'] );
                            $banner_image_1 = $_FILES['banner_image_1']['name'];
                        }
                    }
                    $banner_image_1 = ( !empty($banner_image_1) ) ? $banner_image_1 : $_POST['banner_image_1_current'];
                    $banner_link_1  = ( !empty($_POST['banner_link_1']) ) ? filter_var ( trim($_POST['banner_link_1']), FILTER_SANITIZE_STRING ) : '#';
                    
                    if ( $_FILES['banner_image_2']['size'] != 0 && $_FILES['banner_image_2']['error'] == 0 ) {
                        $banner_2 = getimagesize ( $_FILES['banner_image_2']['tmp_name'] );
                        if ( $banner_2 ) {
                            move_uploaded_file ( $_FILES['banner_image_2']['tmp_name'], 'download/'.$_FILES['banner_image_2']['name'] );
                            $banner_image_2 = $_FILES['banner_image_2']['name'];
                        }
                    }
                    $banner_image_2 = ( !empty($banner_image_2) ) ? $banner_image_2 : $_POST['banner_image_2_current'];
                    $banner_link_2  = ( !empty($_POST['banner_link_2']) ) ? filter_var ( trim($_POST['banner_link_2']), FILTER_SANITIZE_STRING ) : '#';
                    
                    if ( $_FILES['banner_image_3']['size'] != 0 && $_FILES['banner_image_3']['error'] == 0 ) {
                        $banner_3 = getimagesize ( $_FILES['banner_image_3']['tmp_name'] );
                        if ( $banner_3 ) {
                            move_uploaded_file ( $_FILES['banner_image_3']['tmp_name'], 'download/'.$_FILES['banner_image_3']['name'] );
                            $banner_image_3 = $_FILES['banner_image_3']['name'];
                        }
                    }
                    $banner_image_3 = ( !empty($banner_image_3) ) ? $banner_image_3 : $_POST['banner_image_3_current'];
                    $banner_link_3  = ( !empty($_POST['banner_link_3']) ) ? filter_var ( trim($_POST['banner_link_3']), FILTER_SANITIZE_STRING ) : '#';
                    
                    if ( $_FILES['banner_image_4']['size'] != 0 && $_FILES['banner_image_4']['error'] == 0 ) {
                        $banner_4 = getimagesize ( $_FILES['banner_image_4']['tmp_name'] );
                        if ( $banner_4 ) {
                            move_uploaded_file ( $_FILES['banner_image_4']['tmp_name'], 'download/'.$_FILES['banner_image_4']['name'] );
                            $banner_image_4 = $_FILES['banner_image_4']['name'];
                        }
                    }
                    $banner_image_4 = ( !empty($banner_image_4) ) ? $banner_image_4 : $_POST['banner_image_4_current'];
                    $banner_link_4  = ( !empty($_POST['banner_link_4']) ) ? filter_var ( trim($_POST['banner_link_4']), FILTER_SANITIZE_STRING ) : '#';
                    
                    if ( $_FILES['banner_image_5']['size'] != 0 && $_FILES['banner_image_5']['error'] == 0 ) {
                        $banner_5 = getimagesize ( $_FILES['banner_image_5']['tmp_name'] );
                        if ( $banner_5 ) {
                            move_uploaded_file ( $_FILES['banner_image_5']['tmp_name'], 'download/'.$_FILES['banner_image_5']['name'] );
                            $banner_image_5 = $_FILES['banner_image_5']['name'];
                        }
                    }
                    $banner_image_5 = ( !empty($banner_image_5) ) ? $banner_image_5 : $_POST['banner_image_5_current'];
                    $banner_link_5  = ( !empty($_POST['banner_link_5']) ) ? filter_var ( trim($_POST['banner_link_5']), FILTER_SANITIZE_STRING ) : '#';
                    
                    $banner_1 = $banner_2 = $banner_3 = $banner_4 = $banner_5 = null;
                    
                    if ( !empty($banner_image_1) && !empty($banner_link_1) ) {
                        $banner_1 = $banner_image_1 . ',' . $banner_link_1;
                    }
                    if ( !empty($banner_image_2) && !empty($banner_link_2) ) {
                        $banner_2 = $banner_image_2 . ',' . $banner_link_2;
                    }
                    if ( !empty($banner_image_3) && !empty($banner_link_3) ) {
                        $banner_3 = $banner_image_3 . ',' . $banner_link_3;
                    }
                    if ( !empty($banner_image_4) && !empty($banner_link_4) ) {
                        $banner_4 = $banner_image_4 . ',' . $banner_link_4;
                    }
                    if ( !empty($banner_image_5) && !empty($banner_link_5) ) {
                        $banner_5 = $banner_image_5 . ',' . $banner_link_5;
                    }
                    
                    $result_banners = mysqli_query ( $dbc, "SELECT `webid` FROM `website`" );
                    
                    if ( mysqli_num_rows($result_banners) > 0 ) {
                        $query_banners = "UPDATE `website` SET `banner_1`='$banner_1', `banner_2`='$banner_2', `banner_3`='$banner_3', `banner_4`='$banner_4', `banner_5`='$banner_5' WHERE `webid`='1'";
                    } else {
                        $query_banners = "INSERT INTO `website` (`banner_1`, `banner_2`, `banner_3`, `banner_4`, `banner_5`) VALUES ('$banner_1', '$banner_2', '$banner_3', '$banner_4', '$banner_5')";
                    }
                    
                    $result = mysqli_query ( $dbc, $query_banners );
                    
                    if ( mysqli_affected_rows($dbc) > 0 ) {
                        echo '<div class="alert alert-success gap-left gap-right double-gap-top">Banners on the website updated.</div>';
                    } elseif ( mysqli_affected_rows($dbc) == 0 ) {
                        echo '<div class="alert alert-warning gap-left gap-right double-gap-top">Something went wrong. Banners on the website were not updated.</div>';
                    } else {
                        echo '<div class="alert alert-danger gap-left gap-right double-gap-top">Something went wrong. Banners on the website were not updated.</div>';
                    }
                }
            ?>
            <form method="post" action="" enctype="multipart/form-data" class="form-inline" role="form"><?php
                $result_banners = mysqli_query ( $dbc, "SELECT `banner_1`, `banner_2`, `banner_3`, `banner_4`, `banner_5` FROM `website` WHERE `webid`=1" );
                
                $banner_image_1 = $banner_link_1 = '';
                $banner_image_2 = $banner_link_2 = '';
                $banner_image_3 = $banner_link_3 = '';
                $banner_image_4 = $banner_link_4 = '';
                $banner_image_5 = $banner_link_5 = '';
                
                if ( mysqli_num_rows($result_banners) > 0 ) {
                    while ( $row_banner=mysqli_fetch_assoc($result_banners) ) {
                        list( $banner_image_1, $banner_link_1 ) = explode ( ',', $row_banner['banner_1'] );
                        list( $banner_image_2, $banner_link_2 ) = explode ( ',', $row_banner['banner_2'] );
                        list( $banner_image_3, $banner_link_3 ) = explode ( ',', $row_banner['banner_3'] );
                        list( $banner_image_4, $banner_link_4 ) = explode ( ',', $row_banner['banner_4'] );
                        list( $banner_image_5, $banner_link_5 ) = explode ( ',', $row_banner['banner_5'] );
                    }
                }
                ?>
                <h3 class="double-gap-bottom">Banner 1</h3>
                <div class="col-sm-4">
                    Banner Image <?php
                    if ( !empty($banner_image_1) ) {
                        echo '<a href="'. WEBSITE_URL .'/Website/download/'. $banner_image_1 .'" target="_blank">(View Current Banner)</a>';
                    } ?>
                </div>
                <div class="col-sm-8">
                    <input type="file" name="banner_image_1" data-filename-placement="inside" class="form-control" />
                    <input type="hidden" name="banner_image_1_current" value="<?= $banner_image_1; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Banner Link<br /><small>e.g. http://www.primefasteners.com/promotions.php</small></div>
                <div class="col-sm-8"><input type="text" name="banner_link_1" class="form-control" value="<?= $banner_link_1; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Banner 2</h3>
                <div class="col-sm-4">
                    Banner Image <?php
                    if ( !empty($banner_image_2) ) {
                        echo '<a href="'. WEBSITE_URL .'/Website/download/'. $banner_image_2 .'" target="_blank">(View Current Banner)</a>';
                    } ?>
                </div>
                <div class="col-sm-8">
                    <input type="file" name="banner_image_2" data-filename-placement="inside" class="form-control" />
                    <input type="hidden" name="banner_image_2_current" value="<?= $banner_image_2; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Banner Link<br /><small>e.g. http://www.primefasteners.com/promotions.php</small></div>
                <div class="col-sm-8"><input type="text" name="banner_link_2" class="form-control" value="<?= $banner_link_2; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Banner 3</h3>
                <div class="col-sm-4">
                    Banner Image <?php
                    if ( !empty($banner_image_3) ) {
                        echo '<a href="'. WEBSITE_URL .'/Website/download/'. $banner_image_3 .'" target="_blank">(View Current Banner)</a>';
                    } ?>
                </div>
                <div class="col-sm-8">
                    <input type="file" name="banner_image_3" data-filename-placement="inside" class="form-control" />
                    <input type="hidden" name="banner_image_3_current" value="<?= $banner_image_3; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Banner Link<br /><small>e.g. http://www.primefasteners.com/promotions.php</small></div>
                <div class="col-sm-8"><input type="text" name="banner_link_3" class="form-control" value="<?= $banner_link_3; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Banner 4</h3>
                <div class="col-sm-4">
                    Banner Image <?php
                    if ( !empty($banner_image_4) ) {
                        echo '<a href="'. WEBSITE_URL .'/Website/download/'. $banner_image_4 .'" target="_blank">(View Current Banner)</a>';
                    } ?>
                </div>
                <div class="col-sm-8">
                    <input type="file" name="banner_image_4" data-filename-placement="inside" class="form-control" />
                    <input type="hidden" name="banner_image_4_current" value="<?= $banner_image_4; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Banner Link<br /><small>e.g. http://www.primefasteners.com/promotions.php</small></div>
                <div class="col-sm-8"><input type="text" name="banner_link_4" class="form-control" value="<?= $banner_link_4; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Banner 5</h3>
                <div class="col-sm-4">
                    Banner Image <?php
                    if ( !empty($banner_image_5) ) {
                        echo '<a href="'. WEBSITE_URL .'/Website/download/'. $banner_image_5 .'" target="_blank">(View Current Banner)</a>';
                    } ?>
                </div>
                <div class="col-sm-8">
                    <input type="file" name="banner_image_5" data-filename-placement="inside" class="form-control" />
                    <input type="hidden" name="banner_image_5_current" value="<?= $banner_image_5; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Banner Link<br /><small>e.g. http://www.primefasteners.com/promotions.php</small></div>
                <div class="col-sm-8"><input type="text" name="banner_link_5" class="form-control" value="<?= $banner_link_5; ?>" /></div>
                <div class="clearfix"></div>
                
                <div class="triple-gap-top">
                    <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                </div>
            </form>
        
    </div><!-- .col-md-12 -->
</div><!-- .row -->

<?php include ('../footer.php'); ?>
