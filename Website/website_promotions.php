<?php
/*
 * Website Logos
 */
include ('../include.php');
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
		
            <div class="col-sm-10"><h1>Website Promotions Dashboard</h1></div>
            <div class="col-sm-2 gap-top"><?php
                /*
                if ( config_visible_function ( $dbc, 'sales' ) == 1 ) {
                    echo '<a href="field_config_sales.php" class="mobile-block pull-right "><img style="width: 50px;" title="Tile Settings" src="../img/icons/settings-4.png" class="settings-classic wiggle-me"></a>';
                    echo '<span class="popover-examples list-inline pull-right" style="margin:15px 5px 0 0;"><a data-toggle="tooltip" data-placement="top" title="Click here for the settings within this tile. Any changes made will appear on your dashboard."><img src="' . WEBSITE_URL . '/img/info.png" width="20"></a></span>';
                } */?>
            </div>
            <div class="clearfix gap-bottom"></div>
        
            <div class="tab-container mobile-100-container gap-left">
                <div class="pull-left tab"><a href="website.php"><button type="button" class="btn brand-btn mobile-block mobile-100">Home Page</button></a></div>
                <div class="pull-left tab"><a href="website_promotions.php"><button type="button" class="btn brand-btn mobile-block mobile-100 active_tab">Promotions Page</button></a></div>
            </div>
            <div class="clearfix gap-bottom"></div>

            <a class="btn brand-btn mobile-block pull-right" href="add_promotion.php">Add Promotion</a>
            
            <div class="clearfix gap-bottom"></div>
            
            <div id="no-more-tables"><?php

                /* Pagination Counting */
                $rowsPerPage = 25;
                $pageNum = 1;

                if(isset($_GET['page'])) {
                    $pageNum = $_GET['page'];
                }

                $offset = ($pageNum - 1) * $rowsPerPage;
                
                $result_promo   = mysqli_query ( $dbc, "SELECT * FROM `website_promotions` WHERE `deleted`='0'" );
                $query = "SELECT COUNT(*) as `numrows` FROM `website_promotions` WHERE `deleted`='0'";
                
                if ( mysqli_num_rows($result_promo) > 0 ) {

                    // Added Pagination //
                    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage); ?>
                    
                    <table class="table table-bordered">
                        <tr class="hidden-sm hidden-xs">
                            <th>Location</th>
                            <th>Title</th>
                            <th>Promotion</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Products</th>
                            <th>Flyer</th>
                            <th>Image</th>
                            <th>Function</th>
                        </tr><?php
                        
                                        
                        while ( $row=mysqli_fetch_array($result_promo) ) {
                            $products = $row['products'];
                            $products = explode(',', $products); //Convert to an array
                            $products = "'" . implode("','", $products) . "'"; //'a','b','c'
                            $products_list = ''; ?>
                            
                            <tr>
                                <td data-title="Location"><?= $row['location']; ?></td>
                                <td data-title="Title"><?= $row['title']; ?></td>
                                <td data-title="Promotion"><?= $row['promotion']; ?></td>
                                <td data-title="Start Date"><?= $row['start_date']; ?></td>
                                <td data-title="End Date"><?= $row['end_date']; ?></td>
                                <td data-title="Products"><?php
                                    $result_inventory = mysqli_query ( $dbc, "SELECT `inventoryid`, `name`, `part_no` FROM `inventory` WHERE `part_no` IN ($products)" );
                                    if ( mysqli_num_rows($result_inventory) > 0 ) { ?>
                                        <ol class="double-pad-left"><?php
                                            while ( $row_inv=mysqli_fetch_assoc($result_inventory) ) {
                                                echo '<li>' . $row_inv['name'] . '</li>';
                                            } ?>
                                        </ol><?php
                                    } ?>
                                </td>
                                <td data-title="Flyer"><?php
                                    if ( $row['flyer'] ) { ?>
                                        <a href="download/<?= $row['flyer']; ?>" target="_blank">View Flyer</a><?php
                                    } else { ?>
                                        -<?php
                                    } ?>
                                </td>
                                <td data-title="Image"><?php
                                    if ( $row['image'] ) { ?>
                                        <a href="download/<?= $row['image']; ?>" target="_blank">View Image</a><?php
                                    } else { ?>
                                        -<?php
                                    } ?>
                                </td>
                                <td data-title="Function">
                                    <a href="add_promotion.php?promoid=<?= $row['promoid']; ?>">Edit</a> |
                                    <a href="<?= WEBSITE_URL; ?>/delete_restore.php?action=delete&webpromoid=<?= $row['promoid']; ?>" onclick="return confirm('Are you sure you want to archive this promotion?');">Archive</a>
                                </td>
                            </tr><?php
                        } ?>
                        
                    </table><?php
                    
                    echo display_pagination($dbc, $query, $pageNum, $rowsPerPage);
                
                } else {
                    echo '<h2 class="gap-left">No record found.</h2>';
                } ?>
                
            </div><!-- #no-more-tables -->
            
            <a class="btn brand-btn mobile-block pull-right" href="add_promotion.php">Add Promotion</a>
        
        </div><!-- .col-md-12 -->
    </div><!-- .row -->
</div><!-- .container -->

<?php include ('../footer.php'); ?>
