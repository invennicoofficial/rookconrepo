<!-- Staff Information -->
<?php $limit_staff_cat = array_filter(explode(',',get_config($dbc, 'sales_limit_staff_cat')));
$cat_query = '';
if(!empty($limit_staff_cat)) {
    $cat_query = [];
    foreach($limit_staff_cat as $staff_cat) {
        $cat_query[] = "CONCAT(',',`staff_category`,',') LIKE ('%,".$staff_cat.",%')";
    }
    $cat_query = " AND (".implode(' OR ', $cat_query).")";
} ?>
<div class="accordion-block-details padded" id="staffinfo">
    <div class="accordion-block-details-heading"><h4>Staff Information</h4></div>
    <div class="row">
        <div class="col-sm-12 gap-md-left-15 set-row-height">Lead Created by: <?= decryptIt($_SESSION['first_name']).' '.decryptIt($_SESSION['last_name']); ?></div>
    </div>
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Primary Staff:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Select a Staff Member..." name="primary_staff" class="chosen-select-deselect form-control">
                <option value=""></option><?php
                $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted`=0 AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status`>0".$cat_query), MYSQLI_ASSOC));
                foreach($query as $id) { ?>
                    <option <?= ($id==$primary_staff) ? 'selected' : ''; ?> value="<?= $id; ?>"><?= get_contact($dbc, $id); ?></option><?php
                } ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row set-row-height">
        <div class="col-xs-12 col-sm-4 gap-md-left-15">Share Lead:</div>
        <div class="col-xs-12 col-sm-5">
            <select data-placeholder="Select a Staff Member..." multiple name="share_lead[]" class="chosen-select-deselect form-control">
                <option value=""></option><?php
                $query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT `contactid`, `first_name`, `last_name` FROM `contacts` WHERE `deleted`=0 AND `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `status`>0".$cat_query), MYSQLI_ASSOC));
                foreach($query as $id) { ?>
                    <option <?= (strpos(','.$share_lead.',', ','.$id.',') !== false) ? 'selected' : ''; ?> value="<?= $id; ?>"><?= get_contact($dbc, $id); ?></option><?php
                } ?>
            </select>
        </div>
        <div class="clearfix"></div>
    </div>
</div>