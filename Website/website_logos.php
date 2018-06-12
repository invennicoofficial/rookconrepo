<?php
/*
 * Website Logos
 */
include ('../include.php');
error_reporting(0);
?>
<script>
$(document).ready(function() {
    $('#add_new_logo').on( 'click', function () {
        var clone = $('.logo_img').clone();
        clone.find('.form-control').val('');
        clone.removeClass("logo_img");
        $('#new_logo_here').append(clone);
        return false;
    });
});
</script>
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
                <div class="pull-left tab"><a href="website_videos.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Videos</button></a></div>
                <div class="pull-left tab"><a href="website_logos.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Brand Logos</button></a></div>
            </div>
            <div class="clearfix gap-bottom"></div>
		
            <div class="notice double-gap-bottom double-gap-top popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11">
                    <span class="notice-name">NOTE:</span>
                    Changes you make here will be updated immmediately on the website. Recommended logo image size is 140x40 pixels.
                </div>
                <div class="clearfix"></div>
            </div>
            
            <div class="clearfix"></div>

            <h2 class="gap-left">Brand Logos</h2>
            <?php
                if ( isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']=='POST' ) {
                    $logo_images = '';
                    
                    if ( !file_exists ( 'download' ) ) {
                        mkdir ( 'download', 0777, true );
                    }
                    
                    if ( $_FILES['upload_logos']['name'] ) {
                        $logo_images = implode('*#*', $_FILES['upload_logos']['name']);
                        for($i = 0; $i < count($_FILES['upload_logos']['name']); $i++) {
                            move_uploaded_file($_FILES['upload_logos']['tmp_name'][$i], 'download/'.$_FILES['upload_logos']['name'][$i]) ;
                        }
                        $logo_images = ( !empty($logo_images) ) ? $logo_images : null;
                    
                        $get_webid = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `webid` FROM `website`" ) );
                        $webid     = $get_webid['webid'];
                        
                        if ( $webid==1 ) {
                            $get_logos   = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `logos` FROM `website` WHERE `webid`='1'" ) );
                            $logo_images = $get_logos['logos'] . '*#*' . $logo_images;
                            $query_logos = "UPDATE `website` SET `logos`='$logo_images' WHERE `webid`='1'";
                        } else {
                            $query_logos = "INSERT INTO `website` (`logos`) VALUES('$logo_images')";
                        }
                        
                        $result_logos = mysqli_query ( $dbc, $query_logos );
                        
                        if ( mysqli_affected_rows($dbc) > 0 ) {
                            echo '<div class="alert alert-success gap-left gap-right double-gap-top">Logo images on the website updated.</div>';
                        } elseif ( mysqli_affected_rows($dbc) == 0 ) {
                            echo '<div class="alert alert-warning gap-left gap-right double-gap-top">Something went wrong. Logo images on the website were not updated.</div>';
                        } else {
                            echo '<div class="alert alert-danger gap-left gap-right double-gap-top">Something went wrong. Logo images on the website were not updated.</div>';
                        }
                    }
                }
                
                if ( isset($_GET['action']) && $_GET['action']=='delete_logo' && isset($_GET['image']) ) {
                    $del_logo     = $_GET['image'];
                    $get_db_logos = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT `logos` FROM `website` WHERE `webid`='1'" ) );
                    $db_logos     = $get_db_logos['logos'];
                    $db_logos     = explode('*#*', $db_logos);
                    
                    if ( ( $key=array_search($del_logo, $db_logos) ) !== false ) {
                        unset ( $db_logos[$key] );
                        $db_logos = implode('*#*', $db_logos);
                    }
                    
                    $update_logo_images = mysqli_query ( $dbc, "UPDATE `website` SET `logos`='$db_logos' WHERE `webid`='1'" );
                    
                    if ( mysqli_affected_rows($dbc) > 0 ) {
                        echo '
                            <script>
                                alert("Logo image deleted from the website.");
                                window.location.replace("website_logos.php");
                            </script>';
                    } else {
                        echo '
                            <script>
                                alert("An error occurred. Image was not deleted from the website. Please try again.");
                                window.location.replace("website_logos.php");
                            </script>';
                    }
                }
            ?>
            
            <form method="post" action="" enctype="multipart/form-data" class="form-inline" role="form">
                <div class="col-sm-8"><?php
                    $result = mysqli_query ( $dbc, "SELECT `logos` FROM `website` WHERE `logos`<>''" );
                    
                    if ( mysqli_num_rows($result) > 0 ) { ?>
                        <table class="table table-bordered">
                            <tr class="hidden-xs hidden-sm">
                                <th>Logo Image</th>
                                <th>Function</th>
                            </tr><?php

                            while($row = mysqli_fetch_assoc($result)) {
                                $logos = explode ( '*#*', $row['logos'] );
                                foreach ( $logos as $logo ) { ?>
                                    <tr>
                                        <td data-title="Image"><a href="download/<?= $logo; ?>" target="_blank"><?= $logo; ?></a></td>
                                        <td data-title="Delete"><a href="?action=delete_logo&image=<?= $logo; ?>" onclick="return confirm(\'Are you sure you want to delete this image?\')">Delete</a></td>
                                    </tr><?php
                                }
                            } ?>
                        </table><?php
                    } ?>
                    
                    <div class="logo_img"><input name="upload_logos[]" multiple type="file" data-filename-placement="inside" class="form-control" /></div>
                    <div id="new_logo_here"></div>
                    <button id="add_new_logo" class="btn brand-btn gap-top">Add Another Logo</button>
                    
                </div><!-- .additional-logo -->
                
                <div id="add_additional_logo_here"></div>
                
                <div class="clearfix"></div>
                
                <div class="triple-gap-top">
                    <button type="submit" name="submit" value="Submit" class="btn brand-btn btn-lg pull-right">Submit</button>
                </div>
            </form>
        
    </div><!-- .col-md-12 -->
</div><!-- .row -->

<?php include ('../footer.php'); ?>
