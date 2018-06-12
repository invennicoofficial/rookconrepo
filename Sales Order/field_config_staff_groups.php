<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if (isset($_POST['save_config'])) {
    $sales_order_staff_groups = [];
    foreach($_POST['group_name'] as $key => $value) {
        $group_name = $value;
        $group_staff = implode(',',$_POST['staff_'.$key]);
        $staff_group = $value.','.$group_staff;
        if($staff_group != ',') {
            $sales_order_staff_groups[] = $staff_group;
        }
    }
    $sales_order_staff_groups = implode('*#*', $sales_order_staff_groups);

    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_staff_groups'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$sales_order_staff_groups' WHERE name='sales_order_staff_groups'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_staff_groups', '$sales_order_staff_groups')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>
<script type="text/javascript">
$(document).ready(function() {
});
function addStaff(sel) {
    var staff_block = $(sel).closest('.staff_group_block').find('.staff_block').last();
    var clone = staff_block.clone();

    clone.find('.form-control').val('');
    clone.find('.form-control').trigger('change.select2');
    resetChosen(clone.find('select'));
    staff_block.after(clone);
}
function deleteStaff(sel) {
    if ($(sel).closest('.staff_group_block').find('.staff_block').length <= 1) {
        addStaff(sel);
    }
    $(sel).closest('.staff_block').remove();
}
function addStaffGroup() {
    var group_block = $('.staff_group_block').last();
    var clone = group_block.clone();
    var counter = parseInt($('#group_counter').val());

    while (clone.find('.staff_block').length > 1) {
        clone.find('.staff_block').first().remove();
    }
    clone.find('.form-control').val('');
    clone.find('.form-control').trigger('change.select2');
    resetChosen(clone.find('select'));
    clone.find('.group_name').attr('name', 'group_name['+counter+']');
    clone.find('.group_staff').attr('name', 'staff_'+counter+'[]');
    group_block.after(clone);

    $('#group_counter').val(counter+1);
}
function deleteStaffGroup(sel) {
    if ($('.staff_group_block').length <= 1) {
        addStaffGroup();
    }
    $(sel).closest('.staff_group_block').remove();
}
</script>
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="gap-top">
        <?php $staff_groups = explode('*#*',get_config($dbc, 'sales_order_staff_groups'));
        for ($i = 0; $i < count($staff_groups); $i++) {
            $staff_arr = explode(',',$staff_groups[$i]); ?>
            <div class="staff_group_block">
                <div class="form-group">
                    <label class="control-label col-sm-4"><a href="#" onclick="deleteStaffGroup(this); return false"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a> Group Name:</label>
                    <div class="col-sm-8">
                        <input type="text" name="group_name[<?= $i ?>]" class="form-control group_name" value="<?= $staff_arr[0]; ?>">
                    </div>
                </div>
                <?php for ($j = 1; ($j < count($staff_arr)) || $j < 2; $j++) { ?>
                    <div class="form-group staff_block">
                        <label class="control-label col-sm-4">Staff:</label>
                        <div class="col-sm-7">
                            <select name="staff_<?= $i ?>[]" class="chosen-select-deselect form-control group_staff">
                                <option></option>
                                <?php $staff_list = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `contacts` WHERE `category` IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND `deleted` = 0 AND `status` > 0"),MYSQLI_ASSOC));
                                foreach ($staff_list as $id) {
                                    echo '<option '.($staff_arr[$j] == $id ? 'selected' : '').' value="'.$id.'">'.get_contact($dbc, $id).'</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-1 pull-right">
                            <a href="#" onclick="deleteStaff(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>&nbsp;&nbsp;<a href="#" class="add_staff" onclick="addStaff(this); return false;"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                        </div>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <hr>
            </div>
        <?php } ?>
        <input type="hidden" id="group_counter" name="group_counter" value="<?= $i ?>">
        <button name="add_group" class="btn brand-btn pull-right" onclick="addStaffGroup(); return false;">Add Staff Collaboration Group</button>
    </div>
    <div class="pull-right gap-top gap-right gap-bottom">
        <a href="index.php" class="btn brand-btn">Cancel</a>
        <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
    </div>
</form>