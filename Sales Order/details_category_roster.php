<!-- Contact Category Roster -->
<?php 
$contact_category = $contact_cat['contact_category'];
$item_from_types = explode(',',$field_config['product_fields']);
$cat_field_config = ','.$contact_cat['fields'].','; ?>

<div class="accordion-block-details padded" id="<?= $contact_category ?>_roster">
    <div class="accordion-block-details-heading">
        <h4 class="col-sm-7"><?= $contact_category ?> Roster</h4>
        <div class="col-sm-5 text-right">
            <a href="" onclick="downloadCsv('<?= $contact_category ?>'); return false;"><label for="<?= $contact_category ?>_download_csv" class="custom-file-upload default-background">Download CSV</label></a>
            <label for="<?= $contact_category ?>_upload_csv" class="custom-file-upload default-background" style="display: inline;">Upload a CSV</label>
            <input type="file" name="<?= $contact_category ?>_upload_csv" id="<?= $contact_category ?>_upload_csv" class="file-upload" value="" onchange="uploadCsvCatContact('<?= $contact_category ?>');" />
        </div>
        <div class="clearfix"></div>
    </div>
    <?php
    if ($businessid > 0) {
        $cat_contact_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `businessid` = '$businessid' AND `category` = '".$contact_category."' AND `deleted` = 0".$classification_query),MYSQLI_ASSOC));
        foreach ($cat_contact_list as $cat_contactid) { ?>
            <div class="<?= $contact_category ?>_roster_existing">
                <?php if (strpos($cat_field_config, ',First Name,') !== FALSE) { ?>
                    <div class="row set-row-height">
                        <div class="col-sm-3 gap-md-left-15">First Name:</div>
                        <div class="col-sm-7"><input type="text" class="form-control <?= $contact_category ?>_first_name" value="<?= get_contact($dbc, $cat_contactid, 'first_name') ?>" readonly /></div>
                    </div>
                <?php } ?>
                <?php if (strpos($cat_field_config, ',Last Name,') !== FALSE) { ?>
                    <div class="row set-row-height">
                        <div class="col-sm-3 gap-md-left-15">Last Name:</div>
                        <div class="col-sm-7"><input type="text" class="form-control <?= $contact_category ?>_last_name" value="<?= get_contact($dbc, $cat_contactid, 'last_name') ?>" readonly /></div>
                    </div>
                <?php } ?>
                <?php if (strpos($cat_field_config, ',Email Address,') !== FALSE) { ?>
                    <div class="row set-row-height">
                        <div class="col-sm-3 gap-md-left-15">Email:</div>
                        <div class="col-sm-7"><input type="text" class="form-control <?= $contact_category ?>_email" value="<?= get_email($dbc, $cat_contactid) ?>" readonly /></div>
                    </div>
                <?php } ?>
                <?php if (strpos($cat_field_config, ',Player Number,') !== FALSE) { ?>
                    <div class="row set-row-height">
                        <div class="col-sm-3 gap-md-left-15">Player Number:</div>
                        <div class="col-sm-7"><input type="text" class="form-control <?= $contact_category ?>_number" value="<?= get_contact($dbc, $cat_contactid, 'player_number') ?>" readonly /></div>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
    
                <div class="row set-row-height">
                    <div class="col-sm-12 text-right">
                    <a href="#" onclick="deleteCatContactId(this, '<?= $cat_contactid ?>', '<?= $contact_category ?>'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a></div>
                </div>
            </div>
        <?php }
    } ?>
    <?php $cat_contact_counter = 0; ?>
    <div class="<?= $contact_category ?>_roster">
        <?php if (strpos($cat_field_config, ',First Name,') !== FALSE) { ?>
            <div class="row set-row-height">
                <div class="col-sm-3 gap-md-left-15">First Name:</div>
                <div class="col-sm-7"><input type="text" name="<?= $contact_category ?>_first_name[<?= $cat_contact_counter ?>]" class="form-control <?= $contact_category ?>_first_name" value="" /></div>
            </div>
        <?php } ?>
        <?php if (strpos($cat_field_config, ',Last Name,') !== FALSE) { ?>
            <div class="row set-row-height">
                <div class="col-sm-3 gap-md-left-15">Last Name:</div>
                <div class="col-sm-7"><input type="text" name="<?= $contact_category ?>_last_name[<?= $cat_contact_counter ?>]" class="form-control <?= $contact_category ?>_last_name" value="" /></div>
            </div>
        <?php } ?>
        <?php if (strpos($cat_field_config, ',Email Address,') !== FALSE) { ?>
            <div class="row set-row-height">
                <div class="col-sm-3 gap-md-left-15">Email:</div>
                <div class="col-sm-7"><input type="text" name="<?= $contact_category ?>_email[<?= $cat_contact_counter ?>]" class="form-control <?= $contact_category ?>_email" value="" onkeyup="updateUsernameEmail(this, '<?= $contact_category ?>');" onblur="updateUsernameEmail(this, '<?= $contact_category ?>');" /></div>
            </div>
        <?php } ?>
        <?php if (strpos($cat_field_config, ',Player Number,') !== FALSE) { ?>
            <div class="row set-row-height">
                <div class="col-sm-3 gap-md-left-15">Player Number:</div>
                <div class="col-sm-7"><input type="text" name="<?= $contact_category ?>_number[<?= $cat_contact_Counter ?>]" class="form-control <?= $contact_category ?>_number" value="" /></div>
            </div>
        <?php } ?>
        <?php if (strpos($cat_field_config, ',Username & Password,') !== FALSE) { ?>
        <div class="row set-row-height">
            <div class="col-sm-3 gap-md-left-15">Username:</div>
            <div class="col-sm-3">
                <?php if (strpos($cat_field_config, ',Email Address,') !== FALSE) { ?>
                    <input type="checkbox" name="use_email_username[<?= $cat_contact_counter ?>]" value="" class="use_email_username" onchange="useEmailCatContact(this, '<?= $contact_category ?>');"/> <small>Use Email</small>
                <?php } ?>
            </div>
            <div class="col-sm-4"><input type="text" name="<?= $contact_category ?>_username[<?= $cat_contact_counter ?>]" class="form-control <?= $contact_category ?>_username" value="" /></div>
        </div>
        <?php } ?>
        <?php if (strpos($cat_field_config, ',Username & Password,') !== FALSE) { ?>
            <div class="row set-row-height">
                <div class="col-sm-3 gap-md-left-15">Password:</div>
                <div class="col-sm-3"><input type="checkbox" name="auto_password[<?= $cat_contact_counter ?>]" value="" class="auto_password" onchange="autoGeneratePassword(this, '<?= $contact_category ?>');" /> <small>Auto Generate</small></div>
                <div class="col-sm-4"><input type="text" name="<?= $contact_category ?>_password[<?= $cat_contact_counter ?>]" class="form-control <?= $contact_category ?>_password" value="" /></div>
            </div>
        <?php } ?>
        <?php if (strpos($cat_field_config, ',Email Address,') !== FALSE && strpos($cat_field_config, ',Username & Password,') !== FALSE) { ?>
            <div class="row set-row-height">
                <div class="col-sm-3 gap-md-left-15">Email Login Credentials:</div>
                <div class="col-sm-2"><input type="checkbox" name="<?= $contact_category ?>_email_login[<?= $cat_contact_counter ?>]" value="" class="<?= $contact_category ?>_email_login" /></div>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
    
        <div class="row set-row-height">
            <div class="col-sm-12 text-right">
            <a href="#" onclick="deleteCatContact(this, '<?= $contact_category ?>'); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>&nbsp;&nbsp;<a href="#" onclick="addCatContact('<?= $contact_category ?>');" class="add_<?= $contact_category ?>"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a></div>
        </div>
    </div><!-- .contact_category_roster -->
    <?php $cat_contact_counter++; ?>

    <input type="hidden" id="<?= $contact_category ?>_counter" name="<?= $contact_category ?>_counter" value="<?= $cat_contact_counter ?>">
</div>