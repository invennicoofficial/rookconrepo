<script>
$(document).ready(function() {
    $('#eqdelete_0').hide();
	//Equipment
    var add_new_eq = 1;
    $('#add_row_eq').on( 'click', function () {
        $('#eqdelete_0').show();
        var clone = $('.additional_eq').clone();
        clone.find('.form-control').val('');

        clone.find('#eqequipmentcat_0').attr('id', 'eqequipmentcat_'+add_new_eq);
        clone.find('#eqequipmentun_0').attr('id', 'eqequipmentun_'+add_new_eq);

        clone.find('#eqmr_0').attr('id', 'eqmr_'+add_new_eq);
        clone.find('#eqsmr_0').attr('id', 'eqsmr_'+add_new_eq);
        clone.find('#eqdr_0').attr('id', 'eqdr_'+add_new_eq);
        clone.find('#eqhr_0').attr('id', 'eqhr_'+add_new_eq);
        clone.find('#eqhrt_0').attr('id', 'eqhrt_'+add_new_eq);
        clone.find('#eqfdb_0').attr('id', 'eqfdb_'+add_new_eq);

        clone.find('#eqdelete_0').attr('id', 'eqdelete_'+add_new_eq);
        clone.find('#promotionequipment_0').attr('id', 'promotionequipment_'+add_new_eq);
        $('#eqdelete_0').hide();

        clone.removeClass("additional_eq");
        $('#add_here_new_eq').append(clone);

        resetChosen($("#eqequipmentcat_"+add_new_eq));
        resetChosen($("#eqequipmentun_"+add_new_eq));

        add_new_eq++;

        return false;
    });
});
$(document).on('change', 'select.equip_cat_onchange', function() { selectEquipmentCategory(this); });
$(document).on('change', 'select[name="assign_equipment[]"]', function() { selectEquipmentUnSn(this); });
//Equipment
function selectEquipmentCategory(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "promotion_ajax_all.php?fill=eq_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*FFM*');
            $("#eqequipmentun_"+arr[1]).html(result[0]);
			$("#eqequipmentun_"+arr[1]).trigger("change.select2");
		}
	});
}
function selectEquipmentUnSn(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "promotion_ajax_all.php?fill=eq_un_sn_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#eqmr_"+arr[1]).val(result[0]);
            $("#eqsmr_"+arr[1]).val(result[1]);
            $("#eqdr_"+arr[1]).val(result[2]);
            $("#eqhr_"+arr[1]).val(result[3]);
            $("#eqhrt_"+arr[1]).val(result[4]);
            $("#eqfdc_"+arr[1]).val(result[5]);
            $("#eqfdb_"+arr[1]).val(result[6]);
        }
	});
}
function deleteEquipment(sel) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#promotionequipment_"+arr[1]).hide();
    $("#eqequipmentun_"+arr[1]).val('');
    return false;
}
</script>
<?php
$get_field_config_equipment = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT equipment FROM field_config"));
$field_config_equipment = ','.$get_field_config_equipment['equipment'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-2 text-center">Category</label>
            <label class="col-sm-2 text-center">Unit/<br>Serial Number</label>
            <?php if (strpos($field_config_equipment, ','."Monthly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Monthly Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_equipment, ','."Semi Monthly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Semi Monthly Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_equipment, ','."Daily Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Daily Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_equipment, ','."HR Rate Work".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">HR Rate Work</label>
            <?php } ?>
            <?php if (strpos($field_config_equipment, ','."HR Rate Travel".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">HR Rate Travel</label>
            <?php } ?>
            <?php if (strpos($field_config_equipment, ','."Field Day Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Field Day Cost</label>
            <?php } ?>
            <?php if (strpos($field_config_equipment, ','."Field Day Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Field Day Billable</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Quantity</label>
        </div>

        <?php if(!empty($_GET['promotionid'])) {
            $each_assign_equipment = explode('**',$assign_equipment);
            $total_count = mb_substr_count($assign_equipment,'**');
            $id_loop = 500;
            for($equ_loop=0; $equ_loop<$total_count; $equ_loop++) {

                $each_item = explode('#',$each_assign_equipment[$equ_loop]);
                $equipmentid = '';
                $qty = '';
                if(isset($each_item[0])) {
                    $equipmentid = $each_item[0];
                }
                if(isset($each_item[1])) {
                    $qty = $each_item[1];
                }
                if($equipmentid != '') {
            ?>
                <div class="form-group clearfix" id="<?php echo 'promotionequipment_'.$id_loop; ?>">
                    <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                        <select data-placeholder="Choose a Category..." id="<?php echo 'eqequipmentcat_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid equip_cat_onchange" width="380">
                            <option value=''></option>
                            <?php
                            $query = mysqli_query($dbc,"SELECT distinct(category) FROM equipment");
                            while($row = mysqli_fetch_array($query)) {
                                if (get_equipment_field($dbc, $equipmentid, 'category') == $row['category']) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Unit/Serial Number:</label>
                    <select data-placeholder="Choose a Number..." id="<?php echo 'eqequipmentun_'.$id_loop; ?>" name="assign_equipment[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT equipmentid, unit_number, serial_number  FROM equipment");
                        while($row = mysqli_fetch_array($query)) {
                            if ($equipmentid == $row['equipmentid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['equipmentid']."'>".$row['unit_number'].' : '.$row['serial_number'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php if (strpos($field_config_equipment, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Monthly Rate:</label>
                    <input name="eqmr[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'monthly_rate');?>" id="<?php echo 'eqmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Semi Monthly Rate:</label>
                    <input name="eqsmr[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'semi_monthly_rate');?>" id="<?php echo 'eqsmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Daily Rate:</label>
                    <input name="eqdr[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'daily_rate');?>" id="<?php echo 'eqdr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Work:</label>
                    <input name="eqhr[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'hr_rate_work');?>" id="<?php echo 'eqhr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Travel:</label>
                    <input name="eqhrt[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'hr_rate_travel');?>" id="<?php echo 'eqhrt_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Cost:</label>
                    <input name="eqfdc[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'field_day_cost');?>" id="<?php echo 'eqfdc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Billable:</label>
                    <input name="eqfdb[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'field_day_billable');?>" id="<?php echo 'eqfdb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input name="assign_equipment_quantity[]" value="<?php echo $qty;?>" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteEquipment(this); return false;" id="<?php echo 'eqdelete_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_eq clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="promotionequipment_0">

                    <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                        <select data-placeholder="Choose a Category..." id="eqequipmentcat_0" class="chosen-select-deselect form-control equipmentid equip_cat_onchange" width="380">
                            <option value=''></option>
                            <?php
                            $query = mysqli_query($dbc,"SELECT distinct(category) FROM equipment");
                            while($row = mysqli_fetch_array($query)) {
                                echo "<option value='". $row['category']."'>".$row['category'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Unit/Serial Number:</label>
                    <select data-placeholder="Choose a Number..." id="eqequipmentun_0" name="assign_equipment[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT equipmentid, unit_number, serial_number  FROM equipment");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['equipmentid']."'>".$row['unit_number'].' : '.$row['serial_number'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php if (strpos($field_config_equipment, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Monthly Rate:</label>
                    <input name="eqmr[]" id="eqmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Semi Monthly Rate:</label>
                    <input name="eqsmr[]" id="eqsmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Daily Rate:</label>
                    <input name="eqdr[]" id="eqdr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Work:</label>
                    <input name="eqhr[]" id="eqhr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Travel:</label>
                    <input name="eqhrt[]" id="eqhrt_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Cost:</label>
                    <input name="eqfdc[]" id="eqfdc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Billable:</label>
                    <input name="eqfdb[]" id="eqfdb_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input name="assign_equipment_quantity[]" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteEquipment(this); return false;" id="eqdelete_0" class="btn brand-btn">Delete</a>

            </div>

        </div>

        <div id="add_here_new_eq"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_eq" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>