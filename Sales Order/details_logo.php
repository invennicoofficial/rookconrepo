<!-- Logo -->
<div class="accordion-block-details padded" id="logo">
    <div class="accordion-block-details-heading"><h4>Logo</h4></div>
    <div class="row">
        <div class="col-sm-12 gap-md-left-15 set-row-height">
            <div class="col-sm-3 pad-5">Logo File:</div><?php
            
            if(empty($logo)) {
                $logo = get_config($dbc, 'sales_order_logo');
            }

            if ( !empty($logo) ) { ?>
                <div class="col-sm-7">
                    <input type="hidden" name="logo_file" value="<?= $logo; ?>" />
                    <label for="so_logo" class="custom-file-upload default-background">Click here to upload a logo</label>
                    <input type="file" name="logo" id="so_logo" class="file-upload" value="" />
                </div>
                <div class="col-sm-2 pad-5 text-center"><a href="download/<?= $logo; ?>" target="_blank">View</a></div><?php
            } else { ?>
                <div class="col-sm-10">
                    <label for="so_logo" class="custom-file-upload default-background">Click here to upload a logo</label>
                    <input type="file" name="logo" id="so_logo" class="file-upload" value="" />
                </div><?php
            } ?>
        </div>
    </div>
</div>