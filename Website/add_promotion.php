<?php
/*
 * Add Website Promotion
 */
include ('../include.php');
error_reporting(0);

if ( $_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_promo']) ) {
    $title       = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $location    = filter_var($_POST['location'],FILTER_SANITIZE_STRING);
    $promotion   = filter_var($_POST['promotion'],FILTER_SANITIZE_STRING);
    $start_date  = filter_var($_POST['start_date'],FILTER_SANITIZE_STRING);
    $end_date    = filter_var($_POST['end_date'],FILTER_SANITIZE_STRING);
    $brand       = filter_var($_POST['brand'],FILTER_SANITIZE_STRING);
    $description = filter_var(htmlentities($_POST['description']),FILTER_SANITIZE_STRING);
    $products    = implode ( ',', $_POST['products'] );
    $flyer       = ( $_POST['uploaded_flyer'] ) ? $_POST['uploaded_flyer'] : '';
    $image       = ( $_POST['uploaded_image'] ) ? $_POST['uploaded_image'] : '';
    
    if ( !file_exists ( 'download' ) ) {
        mkdir ( 'download', 0777, true );
    }
    
    if ( $_FILES['upload_promotion_flyer']['name'] ) {
        $flyer = $_FILES['upload_promotion_flyer']['name'];
        move_uploaded_file($_FILES['upload_promotion_flyer']['tmp_name'], 'download/'.$_FILES['upload_promotion_flyer']['name']);
    }
    
    if ( $_FILES['upload_promotion_image']['name'] ) {
        $image = $_FILES['upload_promotion_image']['name'];
        move_uploaded_file($_FILES['upload_promotion_image']['tmp_name'], 'download/'.$_FILES['upload_promotion_image']['name']);
    }

    if ( empty ( $_POST['promoid'] ) ) {
       $query_promo = "INSERT INTO `website_promotions` (`title`, `location`, `promotion`, `start_date`, `end_date`, `brand`, `description`, `products`, `flyer`, `image`) VALUES ('$title', '$location', '$promotion', '$start_date', '$end_date', '$brand', '$description', '$products', '$flyer', '$image')";
    } else {
        $promoid = preg_replace ( '/[^0-9]/', '', trim($_POST['promoid']) );
        $query_promo = "UPDATE `website_promotions` SET `title`='$title', `location`='$location', `promotion`='$promotion', `start_date`='$start_date', `end_date`='$end_date', `brand`='$brand', `description`='$description', `products`='$products', `flyer`='$flyer', `image`='$image' WHERE `promoid`='$promoid'";
    }
    
    $result_promo = mysqli_query($dbc, $query_promo);

    echo '<script type="text/javascript">window.location.replace("website_promotions.php");</script>';

    mysqli_close($dbc);//Close the DB Connection
}

?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#form1').submit(function( event ) {
            var location    = $('#location').val();
            var title       = $('#title').val();
            var promotion   = $('#promotion').val();
            var start_date  = $('#start_date').val();
            var end_date    = $('#end_date').val();
            var brand       = $('#brand').val();
            var description = $('#description').val();
            var upload_promotion_flyer = $('#upload_promotion_flyer').val();
            var upload_promotion_image = $('#upload_promotion_image').val();
            var products    = $('#products').val();
            
            if ( upload_promotion_flyer=='' || typeof upload_promotion_flyer=='undefined') {
                upload_promotion_flyer = $('#uploaded_flyer').val();
            }
            if ( upload_promotion_image=='' || typeof upload_promotion_image=='undefined') {
                upload_promotion_image = $('#uploaded_image').val();
            }
            
            if (location=='' || title=='' || promotion=='' || start_date=='' || end_date=='' || brand=='' || description=='' || upload_promotion_flyer=='' || upload_promotion_image=='' || products=='' ) {
                alert("Please make sure you have filled in all of the required fields.");
                return false;
            }
        });
    });
</script>
</head>

<body><?php
    include_once ('../navigation.php');
    checkAuthorised('website'); ?>

    <div class="container">
        <div class="row">
            <h1>Add/Edit Promotion</h1>
            <div class="gap-left gap-top double-gap-bottom"><a href="website_promotions.php" class="btn config-btn">Back to Dashboard</a></div>
		
            <div class="notice double-gap-bottom double-gap-top popover-examples">
                <div class="col-sm-1 notice-icon"><img src="<?= WEBSITE_URL; ?>/img/info.png" class="wiggle-me" width="25"></div>
                <div class="col-sm-11">
                    <span class="notice-name">NOTE:</span>
                    Changes you make here will be updated immmediately on the website. Recommended image size is 343x440 pixels.
                </div>
                <div class="clearfix"></div>
            </div>
            
            <form id="form1" name="form1" method="post"	action="" enctype="multipart/form-data" class="form-horizontal" role="form"><?php
                if ( !empty($_GET['promoid']) ) {
                    $promoid = preg_replace ( '/[^0-9]/', '', trim($_GET['promoid']) );
                    $row_promo = mysqli_fetch_assoc ( mysqli_query ( $dbc, "SELECT * FROM `website_promotions` WHERE `promoid`='$promoid'" ) );
                } ?>
                
                <input type="hidden" id="promoid" name="promoid" value="<?php echo $promoid ?>" />
                
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-4">Location: <span class="red">*</span></div>
                        <div class="col-sm-8">
                            <select name="location" id="location" data-placeholder="Select a Location..." class="chosen-select-deselect form-control"><?php
                                /*
                                $result_location = mysqli_query ( $dbc, "SELECT DISTINCT(`location`) FROM `website_promotions` ORDER BY `location`" );
                                while ( $row_location=mysqli_fetch_array($result_location) ) {
                                    $selected = ( $row_promo['location']==$row_location['location'] ) ? 'selected="selected"' : '';
                                    echo '<option '. $selected .' value="'. $row_location['location'] .'">'. $row['location'] .'</option>';
                                } */ ?>
                                <option value=""></option>
                                <option value="Burnaby, BC" <?= ($row_promo['location']=='Burnaby, BC') ? 'selected="selected"' : ''; ?>>Burnaby, BC</option>
                                <option value="Calgary, AB" <?= ($row_promo['location']=='Calgary, AB') ? 'selected="selected"' : ''; ?>>Calgary, AB</option>
                                <option value="Dartmouth, NS" <?= ($row_promo['location']=='Dartmouth, NS') ? 'selected="selected"' : ''; ?>>Dartmouth, NS</option>
                                <option value="Edmonton, AB" <?= ($row_promo['location']=='Edmonton, AB') ? 'selected="selected"' : ''; ?>>Edmonton, AB</option>
                                <option value="Lethbridge, AB" <?= ($row_promo['location']=='Lethbridge, AB') ? 'selected="selected"' : ''; ?>>Lethbridge, AB</option>
                                <option value="Moncton, NB" <?= ($row_promo['location']=='Moncton, NB') ? 'selected="selected"' : ''; ?>>Moncton, NB</option>
                                <option value="Montreal, QC" <?= ($row_promo['location']=='Montreal, QC') ? 'selected="selected"' : ''; ?>>Montreal, QC</option>
                                <option value="Pickering, ON" <?= ($row_promo['location']=='Pickering, ON') ? 'selected="selected"' : ''; ?>>Pickering, ON</option>
                                <option value="Quebec, QC" <?= ($row_promo['location']=='Quebec, QC') ? 'selected="selected"' : ''; ?>>Quebec, QC</option>
                                <option value="Red Deer, AB" <?= ($row_promo['location']=='Red Deer, AB') ? 'selected="selected"' : ''; ?>>Red Deer, AB</option>
                                <option value="Regina, SK" <?= ($row_promo['location']=='Regina, SK') ? 'selected="selected"' : ''; ?>>Regina, SK</option>
                                <option value="Saskatoon, SK" <?= ($row_promo['location']=='Saskatoon, SK') ? 'selected="selected"' : ''; ?>>Saskatoon, SK</option>
                                <option value="Winnipeg, MB" <?= ($row_promo['location']=='Winnipeg, MB') ? 'selected="selected"' : ''; ?>>Winnipeg, MB</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">Title: <span class="red">*</span></div>
                        <div class="col-sm-8"><input type="text" name="title" id="title" value="<?= $row_promo['title']; ?>" class="form-control" placeholder="Promotion Title" /></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">Promotion: <span class="red">*</span></div>
                        <div class="col-sm-8"><input type="text" name="promotion" id="promotion" value="<?= $row_promo['promotion']; ?>" class="form-control" placeholder="e.g. 50% off" /></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">Start/End Dates: <span class="red">*</span></div>
                        <div class="col-sm-8">
                            <div class="col-sm-6 no-gap-pad"><input type="text" name="start_date" id="start_date" value="<?= $row_promo['start_date']; ?>" class="datepicker form-control" placeholder="Promotion Start Date" /></div>
                            <div class="col-sm-6 pull-right no-pad-right"><input type="text" name="end_date" id="end_date" value="<?= $row_promo['end_date']; ?>" class="datepicker form-control" placeholder="Promotion End Date" /></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">Brand: <span class="red">*</span></div>
                        <div class="col-sm-8">
                            <select name="brand" id="brand" data-placeholder="Select a Brand..." class="chosen-select-deselect form-control"><?php
                                /*
                                $result_brand = mysqli_query ( $dbc, "SELECT DISTINCT(`brand`) FROM `website_promotions` ORDER BY `brand`" );
                                while ( $row_brand=mysqli_fetch_array($result_brand) ) {
                                    $selected = ( $row_promo['brand']==$row_brand['brand'] ) ? 'selected="selected"' : '';
                                    echo '<option '. $selected .' value="'. $row_brand['brand'] .'">'. $row['brand'] .'</option>';
                                } */ ?>
                                <option value=""></option>
                                <option value="Blackline" <?= ($row_promo['brand']=='Blackline') ? 'selected="selected"' : ''; ?>>Blackline</option>
                                <option value="Bosch" <?= ($row_promo['brand']=='Bosch') ? 'selected="selected"' : ''; ?>>Bosch</option>
                                <option value="Bostitch" <?= ($row_promo['brand']=='Bostitch') ? 'selected="selected"' : ''; ?>>Bostitch</option>
                                <option value="CMT" <?= ($row_promo['brand']=='CMT') ? 'selected="selected"' : ''; ?>>CMT</option>
                                <option value="Coilhose" <?= ($row_promo['brand']=='Coilhose') ? 'selected="selected"' : ''; ?>>Coilhose</option>
                                <option value="Dewalt" <?= ($row_promo['brand']=='Dewalt') ? 'selected="selected"' : ''; ?>>Dewalt</option>
                                <option value="Hitachi" <?= ($row_promo['brand']=='Hitachi') ? 'selected="selected"' : ''; ?>>Hitachi</option>
                                <option value="Irwin" <?= ($row_promo['brand']=='Irwin') ? 'selected="selected"' : ''; ?>>Irwin</option>
                                <option value="Jenny" <?= ($row_promo['brand']=='Jenny') ? 'selected="selected"' : ''; ?>>Jenny</option>
                                <option value="Kunys" <?= ($row_promo['brand']=='Kunys') ? 'selected="selected"' : ''; ?>>Kuny's</option>
                                <option value="Makita" <?= ($row_promo['brand']=='Makita') ? 'selected="selected"' : ''; ?>>Makita</option>
                                <option value="Milwaukee" <?= ($row_promo['brand']=='Milwaukee') ? 'selected="selected"' : ''; ?>>Milwaukee</option>
                                <option value="Occidental" <?= ($row_promo['brand']=='Occidental') ? 'selected="selected"' : ''; ?>>Occidental</option>
                                <option value="Olfa" <?= ($row_promo['brand']=='Olfa') ? 'selected="selected"' : ''; ?>>Olfa</option>
                                <option value="Paslode" <?= ($row_promo['brand']=='Paslode') ? 'selected="selected"' : ''; ?>>Paslode</option>
                                <option value="Prime" <?= ($row_promo['brand']=='Prime') ? 'selected="selected"' : ''; ?>>Prime</option>
                                <option value="Rolair" <?= ($row_promo['brand']=='Rolair') ? 'selected="selected"' : ''; ?>>Rolair</option>
                                <option value="Senco" <?= ($row_promo['brand']=='Senco') ? 'selected="selected"' : ''; ?>>Senco</option>
                                <option value="Stanley" <?= ($row_promo['brand']=='Stanley') ? 'selected="selected"' : ''; ?>>Stanley</option>
                                <option value="Stiletto" <?= ($row_promo['brand']=='Stiletto') ? 'selected="selected"' : ''; ?>>Stiletto</option>
                                <option value="Swanson" <?= ($row_promo['brand']=='Swanson') ? 'selected="selected"' : ''; ?>>Swanson</option>
                                <option value="Tajima" <?= ($row_promo['brand']=='Tajima') ? 'selected="selected"' : ''; ?>>Tajima</option>
                                <option value="Watson" <?= ($row_promo['brand']=='Watson') ? 'selected="selected"' : ''; ?>>Watson</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">Description: <span class="red">*</span></div>
                        <div class="col-sm-8"><textarea name="description" id="description" class="form-control"><?= html_entity_decode($row_promo['description']); ?></textarea></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">
                            PDF Flyer: <span class="red">*</span><br />
                            <small>Uploading a new PDF flyer will replace the existing flyer.</small>
                        </div>
                        <div class="col-sm-8"><?php
                            if ( $row_promo['flyer'] ) { ?>
                                <a href="download/<?= $row_promo['flyer']; ?>" target="_blank">View PDF Flyer</a>
                                <input name="uploaded_flyer" id="uploaded_flyer" type="hidden" value="<?= $row_promo['flyer']; ?>" /><?php
                            } ?>
                            <input name="upload_promotion_flyer" id="upload_promotion_flyer" type="file" data-filename-placement="inside" class="form-control" value="download/<?= $row_promo['flyer']; ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">
                            Image: <span class="red">*</span><br />
                            <small>Uploading a new image will replace the existing image.</small>
                        </div>
                        <div class="col-sm-8"><?php
                            if ( $row_promo['image'] ) { ?>
                                <a href="download/<?= $row_promo['image']; ?>" target="_blank">View Image</a>
                                <input name="uploaded_image" id="uploaded_image" type="hidden" value="<?= $row_promo['image']; ?>" /><?php
                            } ?>
                            <input name="upload_promotion_image" id="upload_promotion_image" type="file" data-filename-placement="inside" class="form-control" value="download/<?= $row_promo['image']; ?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-4">Included Products: <span class="red">*</span></div>
                        <div class="col-sm-8">
                            <select data-placeholder="Choose Two Products..." multiple name="products[]" id="products" class="chosen-select-deselect form-control" width="380"><?php
                                $result_inventory = mysqli_query ( $dbc, "SELECT `inventoryid`, `name`, `part_no` FROM `inventory` WHERE `part_no`<>''" );
                            
                                if ( mysqli_num_rows($result_inventory) > 0 ) {
                                    while ( $row_inv=mysqli_fetch_assoc($result_inventory) ) { ?>
                                        <option value="<?= $row_inv['part_no']; ?>" <?php if ( strpos (','.$row_promo['products'].',', ','.$row_inv['part_no'].',') !== false ) { echo " selected"; } ?>><?= $row_inv['name']; ?></option><?php
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="clearfix triple-gap-top"></div>
                    
                    <div class="form-group triple-gap-top">
                        <p><span class="red"><em>Required Fields <span class="red">*</span></em></span></p>
                    </div>
                    
                    <div class="pull-left">
                        <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to discard your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <a href="website_promotions.php" class="btn brand-btn btn-lg">Back</a>
                    </div>
                    <div class="pull-right">
                        <span class="popover-examples list-inline"><a style="margin:0 5px 0 0;" data-toggle="tooltip" data-placement="top" title="Click here to submit your changes."><img src="<?= WEBSITE_URL; ?>/img/info.png" width="20"></a></span>
                        <button type="submit" name="add_promo" value="Submit" class="btn brand-btn btn-lg">Submit</button>
                    </div>
                    
                    <div class="clearfix"></div>
                
                </div><!-- .col-sm-12 -->

            </form>

        </div><!-- .row -->
    </div><!-- .container -->
    
<?php include ('../footer.php'); ?>
