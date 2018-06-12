<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
    $sales_order_type = $_POST['so_type'];

    mysqli_query($dbc, "DELETE FROM `field_config_so_contacts` WHERE `sales_order_type` = '$sales_order_type'");
    foreach ($_POST['contact_category'] as $cat_i => $category) {
        if(!empty($category)) {
            $cat_fields = filter_var(implode(',', $_POST['cat_fields_'.$cat_i]),FILTER_SANITIZE_STRING);
            mysqli_query($dbc, "INSERT INTO `field_config_so_contacts` (`contact_category`, `fields`, `sales_order_type`) VALUES ('$category', '$cat_fields', '$sales_order_type')");   
        }
    }
}

$field_list = [
    'First Name',
    'Last Name',
    'Email Address',
    'Username & Password',
    'Player Number'
];
?>
<script type="text/javascript">
$(document).on('change', 'select[name="so_type"]', function() { changeSOType(this); });
function changeSOType(sel) {
    window.location.href = "?tab=contact_categories&so_type="+sel.value;
}
function add_category() {
    var block = $('.category_group').last();
    var clone = block.clone();
    var counter = $('#category_counter').val();

    clone.find('.category_field').attr('name', 'cat_fields_'+counter+'[]').removeAttr('checked');
    clone.find('.category_dropdown').attr('name', 'contact_category['+counter+']').val('');
    resetChosen(clone.find('.category_dropdown'));

    block.after(clone);
    $('#category_counter').val(parseInt(counter) + 1);
}
function remove_category(btn) {
    if($('.category_group').length <= 1) {
        add_category();
    }
    $(btn).closest('.category_group').remove();
}
</script>
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="gap-top">
        <?php $sales_order_types = get_config($dbc, 'sales_order_types');
        $so_type = $_GET['so_type'];
        if(!empty($sales_order_types)) { ?>
            <div class="form-group">
                <label class="col-sm-4"><?= SALES_ORDER_NOUN ?> Type:</label>
                <div class="col-sm-8">
                    <select name="so_type" data-placeholder="Select a Type" class="chosen-select-deselect form-control">
                        <?php foreach(explode(',', $sales_order_types) as $sales_order_type) {
                            if(empty($so_type)) {
                                $so_type = $sales_order_type;
                            } ?>
                            <option value="<?= $sales_order_type ?>" <?= $sales_order_type == $so_type ? 'selected' : '' ?>><?= $sales_order_type ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php }
        $category_counter = 0;
        $cat_config = mysqli_fetch_all(mysqli_query($dbc, "SELECT * FROM `field_config_so_contacts` WHERE `sales_order_type` = '$so_type'"),MYSQLI_ASSOC);
        if(empty($cat_config)) {
            $cat_config[0]['fieldconfigid'] = '';
            $cat_config[0]['contact_category'] = '';
            $cat_config[0]['fields'] = '';
        }
        foreach ($cat_config as $row) { ?>
            <div class="category_group">
                <div class="form-group">
                    <label class="col-sm-4">Contact Category:</label>
                    <div class="col-sm-8">
                        <select name="contact_category[<?= $category_counter ?>]" class="chosen-select-deselect form-control category_dropdown">
                            <option></option>
                            <?php $all_cats = get_config($dbc, 'contacts_tabs').','.get_config($dbc, 'contactsrolodex_tabs').','.get_config($dbc, 'contacts3_tabs').','.get_config($dbc, 'clientinfo_tabs').','.get_config($dbc, 'members_tabs').','.get_config($dbc, 'vendors_tabs');
                            $all_cats = array_unique(array_filter(explode(',', $all_cats)));
                            asort($all_cats);
                            foreach ($all_cats as $contact_cat) {
                                echo '<option value="'.$contact_cat.'" '.($row['contact_category'] == $contact_cat ? 'selected' : '').'>'.$contact_cat.'</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <table cellpadding='10' class='table table-bordered'>
                	<?php $i = 0;
                	foreach ($field_list as $field) {
                		if ($i == 0) {
                			echo '<tr>';
                		}
                		echo '<td><input type="checkbox" '.(strpos(','.$row['fields'].',', ','.$field.',') !== FALSE ? 'checked' : '').' value="'.$field.'" style="height: 20px; width: 20px;" name="cat_fields_'.$category_counter.'[]" class="category_field">&nbsp;&nbsp;'.$field.'</td>';
                		$i++;
                		if($i == 5) {
                			echo '</tr>';
                			$i = 0;
                		}
                	}
                	if($i != 0) {
                		echo '</tr>';
                	} ?>
                </table>
                <div class="form-group pull-right">
                    <img src="../img/icons/ROOK-add-icon.png" class="inline-img pull-right" onclick="add_category();">
                    <img src="../img/remove.png" class="inline-img pull-right" onclick="remove_category(this);">
                </div>
                <div class="clearfix"></div>
            </div>
            <?php $category_counter++;
        } ?>
        <input type="hidden" name="category_counter" id="category_counter" value="<?= $category_counter ?>">
    </div>
    <div class="pull-right gap-top gap-right gap-bottom">
        <a href="index.php" class="btn brand-btn">Cancel</a>
        <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
    </div>
</form>