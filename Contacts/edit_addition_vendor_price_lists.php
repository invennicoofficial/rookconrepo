<?php
if($field_option == 'Vendor Price Lists Addition') {
$vpl = mysqli_query($dbc,"SELECT * FROM `vendor_price_list` WHERE `vendorid`='$contactid' AND `deleted`='0'");

$get_field_config = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT `contacts` FROM `field_config_contacts` WHERE `tile_name`='vendors' AND `subtab`='**no_subtab**'"));
$value_config = ','.$get_field_config['contacts'].',';

if (strpos($value_config, ','."VPL Import/Export".',') !== false) { ?>
    <div class="form-group">
        <a href="javascript:void(0);" class="btn brand-btn" onclick="overlayIFrameSlider('../Contacts/edit_vpl_import_export.php?type=add', '50%', false, false, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;">Add Multiple</a>
        <a href="javascript:void(0);" class="btn brand-btn" onclick="overlayIFrameSlider('../Contacts/edit_vpl_import_export.php?type=edit', '50%', false, false, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;">Edit Multiple</a>
        <a href="javascript:void(0);" class="btn brand-btn" onclick="overlayIFrameSlider('../Contacts/edit_vpl_import_export.php?type=export', '50%', false, false, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;">Export</a>
        <a href="javascript:void(0);" class="btn brand-btn" onclick="overlayIFrameSlider('../Contacts/edit_vpl_import_export.php?type=log', '50%', false, false, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;">History</a>
    </div><?php
}

if ( $vpl->num_rows>0 ) { ?>
    <div id="no-more-tables" style="max-height:300px; overflow:auto;">
        <table class="table table-bordered">
            <tr class="hidden-xs">
                <th>ID</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Name</th>
                <?php if(vuaed_visible_function($dbc, 'vpl') == 1) { ?>
                    <th>Function</th>
                <?php } ?>
            </tr><?php
            while ( $row=mysqli_fetch_array($vpl)) { ?>
                <tr>
                    <td data-title="ID"><?= $row['part_no'] ?></td>
                    <td data-title="Category"><?= $row['category'] ?></td>
                    <td data-title="Subcategory"><?= $row['sub_category'] ?></td>
                    <td data-title="Name"><?= $row['name'] ?></td>
                    <?php if(vuaed_visible_function($dbc, 'vpl') == 1) { ?>
                        <td data-title="Function">
                            <a href="javascript:void(0);" onclick="overlayIFrameSlider('../Contacts/edit_vpl_inventory.php?inventoryid=<?= $row['inventoryid'] ?>&contactid=<?= $contactid ?>', '50%', false, false, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;">Edit</a>
                            |
                            <a href="<?= WEBSITE_URL ?>/delete_restore.php?action=delete&vplid=<?= $row['inventoryid'] ?>&contactid=<?= $contactid ?>" onclick="return confirm('Are you sure you want to delete this Order List?')">Delete</a>
                    <?php } ?>
                </tr><?php
            } ?>
        </table>
    </div><?php
} else {
    echo '<label class="col-sm-12 control-label">No Records Found.</label>';
} ?>

<div class="pull-right"><a href="javascript:void(0);" onclick="overlayIFrameSlider('../Contacts/edit_vpl_inventory.php?contactid=<?= $contactid ?>', '50%', false, false, $('.iframe_overlay').closest('.container').outerHeight() + 20); return false;"><img src="../img/icons/ROOK-add-icon.png" class="inline-img" alt="Add New Product" /></a></div>
<div class="clearfix"></div>
<?php } ?>