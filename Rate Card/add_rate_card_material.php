<script>
$(document).ready(function() {
	//Inventory
    var add_new_m = 1;
    $('#deletematerial_0').hide();
    $('#add_row_m').on( 'click', function () {
        $('#deletematerial_0').show();
        var clone = $('.additional_m').clone();
        clone.find('.form-control').val('');

        clone.find('#materialid_0').attr('id', 'materialid_'+add_new_m);
		clone.find('#mname_0').attr('id', 'mname_'+add_new_m);
		clone.find('#mwidth_0').attr('id', 'mwidth_'+add_new_m);
		clone.find('#mlength_0').attr('id', 'mlength_'+add_new_m);
        clone.find('#munits_0').attr('id', 'munits_'+add_new_m);
        clone.find('#munitweight_0').attr('id', 'munitweight_'+add_new_m);
        clone.find('#mwpf_0').attr('id', 'mwpf_'+add_new_m);
        clone.find('#mprice_0').attr('id', 'mprice_'+add_new_m);
		clone.find('#mfinalprice_0').attr('id', 'mfinalprice_'+add_new_m);
        clone.find('#material_0').attr('id', 'material_'+add_new_m);
        clone.find('#deletematerial_0').attr('id', 'deletematerial_'+add_new_m);

        $('#deletematerial_0').hide();

        clone.removeClass("additional_m");
        $('#add_here_new_m').append(clone);

        resetChosen($("#materialid_"+add_new_m));

        add_new_m++;

        return false;
    });

});
$(document).on('change', 'select[name="materialid[]"]', function() { selectMaterial(this); });
//Inventory
function selectMaterial(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=material_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*FFM*');
            $("#mname_"+arr[1]).val(result[0]);
            $("#mwidth_"+arr[1]).val(result[1]);
            $("#mlength_"+arr[1]).val(result[2]);
            $("#munits_"+arr[1]).val(result[3]);
            $("#munitweight_"+arr[1]).val(result[4]);
            $("#mwpf_"+arr[1]).val(result[5]);
            $("#mprice_"+arr[1]).val(result[6]);
		}
	});
}

</script>
<?php
$get_field_config_inventory = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material FROM field_config"));
$field_config_inventory = ','.$get_field_config_inventory['material'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-1 text-center">Code</label>
            <label class="col-sm-1 text-center">Name</label>
            <?php if (strpos($field_config_inventory, ','."Width".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Width</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Length".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Length</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Units".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Units</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Unit Weight".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Unit Weight</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Weight Per Feet".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Weight Per Foot</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Price</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_material = explode('**', $material);
            $total_count = mb_substr_count($material,'**');
            $id_loop = 500;
            for($pid_loop=0; $pid_loop<$total_count; $pid_loop++) {

                $materialid = '';

                if(isset($each_material[$pid_loop])) {
                    $each_val = explode('#', $each_material[$pid_loop]);
                    $materialid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($materialid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'material_'.$id_loop; ?>">
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Code:</label>
                    <select data-placeholder="Choose a Code..." id="<?php echo 'materialid_'.$id_loop; ?>" name="materialid[]" class="chosen-select-deselect form-control materialid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT code, materialid FROM material");
                        while($row = mysqli_fetch_array($query)) {
                            if ($materialid == $row['materialid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['materialid']."'>".$row['code'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Name:</label>
                    <input name="mname[]" value="<?php echo get_material($dbc, $materialid, 'name');?>" id="<?php echo 'mname_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php if (strpos($field_config_inventory, ','."Width".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Width:</label>
                    <input name="mwidth[]" value="<?php echo get_material($dbc, $materialid, 'width');?>" id="<?php echo 'mwidth_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Length".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Length:</label>
                    <input name="mlength[]" value="<?php echo get_material($dbc, $materialid, 'length');?>" id="<?php echo 'mlength_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Units".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Units:</label>
                    <input name="munits[]" value="<?php echo get_material($dbc, $materialid, 'units');?>" id="<?php echo 'munits_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Unit Weight".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Unit Weight:</label>
                    <input name="munitweight[]" value="<?php echo get_material($dbc, $materialid, 'unit_weight');?>" id="<?php echo 'munitweight_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Weight Per Feet".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Weight Per Foot:</label>
                    <input name="mwpf[]" value="<?php echo get_material($dbc, $materialid, 'weight_per_feet');?>" id="<?php echo 'mwpf_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input name="mprice[]" value="<?php echo get_material($dbc, $materialid, 'price');?>" id="<?php echo 'mprice_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="mfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'mfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'material_','materialid_'); return false;" id="<?php echo 'deletematerial_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_m clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="material_0">
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Code:</label>
                    <select data-placeholder="Choose a Code..." id="materialid_0" name="materialid[]" class="chosen-select-deselect form-control materialid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT code, materialid FROM material");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['materialid']."'>".$row['code'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Name:</label>
                    <input name="mname[]" id="mname_0" readonly type="text" class="form-control" />
                </div>
                <?php if (strpos($field_config_inventory, ','."Width".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Width:</label>
                    <input name="mwidth[]" id="mwidth_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Length".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Length:</label>
                    <input name="mlength[]" id="mlength_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Units".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Units:</label>
                    <input name="munits[]" id="munits_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Unit Weight".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Unit Weight:</label>
                    <input name="munitweight[]" id="munitweight_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Weight Per Feet".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Weight Per Foot:</label>
                    <input name="mwpf[]" id="mwpf_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Price:</label>
                    <input name="mprice[]" id="mprice_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="mfinalprice[]" id="mfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'material_','materialid_'); return false;" id="deletematerial_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_m"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_m" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
