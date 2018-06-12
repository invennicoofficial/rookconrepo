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
                <div class="pull-left tab"><a href="website.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Banners</button></a></div>
                <div class="pull-left tab"><a href="website_headings.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Block Headings</button></a></div>
                <div class="pull-left tab"><a href="website_videos.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Videos</button></a></div>
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

            <h2 class="gap-left">Blue Block Headings</h2>
            <?php
                if ( $_SERVER['REQUEST_METHOD']=='POST' ) {
                    $main_1 = filter_var ( trim($_POST['main_1']), FILTER_SANITIZE_STRING );
                    $sub_1  = filter_var ( trim($_POST['sub_1']), FILTER_SANITIZE_STRING );
                    $main_2 = filter_var ( trim($_POST['main_2']), FILTER_SANITIZE_STRING );
                    $sub_2  = filter_var ( trim($_POST['sub_2']), FILTER_SANITIZE_STRING );
                    $main_3 = filter_var ( trim($_POST['main_3']), FILTER_SANITIZE_STRING );
                    $sub_3  = filter_var ( trim($_POST['sub_3']), FILTER_SANITIZE_STRING );
                    $main_4 = filter_var ( trim($_POST['main_4']), FILTER_SANITIZE_STRING );
                    $sub_4  = filter_var ( trim($_POST['sub_4']), FILTER_SANITIZE_STRING );
                    
                    $heading_1 = $heading_2 = $heading_3 = $heading_4 = null;
                    
                    if ( !empty($main_1) && !empty($sub_1) ) {
                        $heading_1 = $main_1 . '*#*' . $sub_1;
                    }
                    if ( !empty($main_2) && !empty($sub_2) ) {
                        $heading_2 = $main_2 . '*#*' . $sub_2;
                    }
                    if ( !empty($main_3) && !empty($sub_3) ) {
                        $heading_3 = $main_3 . '*#*' . $sub_3;
                    }
                    if ( !empty($main_4) && !empty($sub_4) ) {
                        $heading_4 = $main_4 . '*#*' . $sub_4;
                    }
                    
                    $result_headings = mysqli_query ( $dbc, "SELECT `webid` FROM `website`" );
                    
                    if ( mysqli_num_rows($result_headings) > 0 ) {
                        $query_headings = "UPDATE `website` SET `heading_1`='$heading_1', `heading_2`='$heading_2', `heading_3`='$heading_3', `heading_4`='$heading_4' WHERE `webid`='1'";
                    } else {
                        $query_headings = "INSERT INTO `website` (`heading_1`, `heading_2`, `heading_3`, `heading_4`) VALUES ('$heading_1', '$heading_2', '$heading_3', '$heading_4')";
                    }
                    
                    $result = mysqli_query ( $dbc, $query_headings );
                    
                    if ( mysqli_affected_rows($dbc) > 0 ) {
                        echo '<div class="alert alert-success gap-left gap-right double-gap-top">Headings on the website updated.</div>';
                    } elseif ( mysqli_affected_rows($dbc) == 0 ) {
                        echo '<div class="alert alert-warning gap-left gap-right double-gap-top">Something went wrong. Headings on the website were not updated.</div>';
                    } else {
                        echo '<div class="alert alert-danger gap-left gap-right double-gap-top">Something went wrong. Headings on the website were not updated.</div>';
                    }
                }
            ?>
            <form method="post" action="" enctype="multipart/form-data" class="form-inline" role="form"><?php
                $result_heading = mysqli_query ( $dbc, "SELECT `heading_1`, `heading_2`, `heading_3`, `heading_4` FROM `website` WHERE `webid`=1" );
                
                if ( mysqli_num_rows($result_heading) > 0 ) {
                    while ( $row_heading=mysqli_fetch_assoc($result_heading) ) {
                        list( $main_1, $sub_1 ) = explode ( '*#*', $row_heading['heading_1'] );
                        list( $main_2, $sub_2 ) = explode ( '*#*', $row_heading['heading_2'] );
                        list( $main_3, $sub_3 ) = explode ( '*#*', $row_heading['heading_3'] );
                        list( $main_4, $sub_4 ) = explode ( '*#*', $row_heading['heading_4'] );
                    }
                } ?>
                <h3 class="double-gap-bottom">Block Heading 1</h3>
                <div class="col-sm-4">Heading</div>
                <div class="col-sm-8"><input type="text" name="main_1" class="form-control" value="<?= $main_1; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Sub Heading</div>
                <div class="col-sm-8"><input type="text" name="sub_1" class="form-control" value="<?= $sub_1; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                
                <h3 class="double-gap-bottom">Block Heading 2</h3>
                <div class="col-sm-4">Heading</div>
                <div class="col-sm-8"><input type="text" name="main_2" class="form-control" value="<?= $main_2; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Sub Heading</div>
                <div class="col-sm-8"><input type="text" name="sub_2" class="form-control" value="<?= $sub_2; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Block Heading 3</h3>
                <div class="col-sm-4">Heading</div>
                <div class="col-sm-8"><input type="text" name="main_3" class="form-control" value="<?= $main_3; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Sub Heading</div>
                <div class="col-sm-8"><input type="text" name="sub_3" class="form-control" value="<?= $sub_3; ?>" /></div>
                <div class="clearfix triple-gap-bottom"></div>
                
                <h3 class="double-gap-bottom">Block Heading 4</h3>
                <div class="col-sm-4">Heading</div>
                <div class="col-sm-8"><input type="text" name="main_4" class="form-control" value="<?= $main_4; ?>" />
                </div>
                <div class="clearfix gap-bottom"></div>
                <div class="col-sm-4">Sub Heading</div>
                <div class="col-sm-8"><input type="text" name="sub_4" class="form-control" value="<?= $sub_4; ?>" /></div>
                <div class="clearfix"></div>
                
                <div class="triple-gap-top">
                    <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                </div>
            </form>
        
    </div><!-- .col-md-12 -->
</div><!-- .row -->

<?php include ('../footer.php'); ?>
