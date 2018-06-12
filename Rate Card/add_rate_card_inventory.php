<script>
$(document).ready(function() {
	//Inventory
    $('#deleteinventory_0').hide();
    var add_new_in = 1;
    $('#add_row_in').on( 'click', function () {
        $('#deleteinventory_0').show();
        var clone = $('.additional_in').clone();
        clone.find('.form-control').val('');

        clone.find('#ininventorycat_0').attr('id', 'ininventorycat_'+add_new_in);
		clone.find('#ininventorycode_0').attr('id', 'ininventorycode_'+add_new_in);
		clone.find('#ininventorypn_0').attr('id', 'ininventorypn_'+add_new_in);
		clone.find('#ininventoryname_0').attr('id', 'ininventoryname_'+add_new_in);
        clone.find('#inrp_0').attr('id', 'inrp_'+add_new_in);
        clone.find('#inap_0').attr('id', 'inap_'+add_new_in);
        clone.find('#inwp_0').attr('id', 'inwp_'+add_new_in);
        clone.find('#incomp_0').attr('id', 'incomp_'+add_new_in);
        clone.find('#incp_0').attr('id', 'incp_'+add_new_in);
        clone.find('#inmsrp_0').attr('id', 'inmsrp_'+add_new_in);
		clone.find('#infinalprice_0').attr('id', 'infinalprice_'+add_new_in);
		clone.find('#inquoteprice_0').attr('id', 'inquoteprice_'+add_new_in);
        clone.find('#inventory_0').attr('id', 'inventory_'+add_new_in);
        clone.find('#deleteinventory_0').attr('id', 'deleteinventory_'+add_new_in);

        $('#deleteinventory_0').hide();

        clone.removeClass("additional_in");
        $('#add_here_new_in').append(clone);

        resetChosen($("#ininventorycat_"+add_new_in));
        resetChosen($("#ininventorycode_"+add_new_in));
        resetChosen($("#ininventorypn_"+add_new_in));
        resetChosen($("#ininventoryname_"+add_new_in));

        add_new_in++;

        return false;
    });

});
$(document).on('change', 'select.inv_cat_onchange', function() { selectInventoryCategory(this); });
$(document).on('change', 'select.inv_partno_onchange', function() { selectInventoryCodePartNo(this); });
$(document).on('change', 'select.inv_partname_onchange', function() { selectInventoryCodePartName(this); });
//Inventory
function selectInventoryCategory(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=in_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#ininventoryname_"+arr[1]).html(response);
			$("#ininventoryname_"+arr[1]).trigger("change.select2");
		}
	});
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=in_cat_config_partno&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#ininventorypart_"+arr[1]).html(response);
			$("#ininventorypart_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectInventoryCodePartName(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=in_code_part_name_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*FFM*');
            $("#inrp_"+arr[1]).val(result[0]);
            $("#inap_"+arr[1]).val(result[1]);
            $("#inwp_"+arr[1]).val(result[2]);
            $("#incomp_"+arr[1]).val(result[3]);
            $("#incp_"+arr[1]).val(result[4]);
            $("#inmsrp_"+arr[1]).val(result[5]);
		}
	});
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=in_code_part_name_config_number&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#ininventorypart_"+arr[1]).html(response);
			$("#ininventorypart_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectInventoryCodePartNo(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=in_code_part_no_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*FFM*');
            $("#inrp_"+arr[1]).val(result[0]);
            $("#inap_"+arr[1]).val(result[1]);
            $("#inwp_"+arr[1]).val(result[2]);
            $("#incomp_"+arr[1]).val(result[3]);
            $("#incp_"+arr[1]).val(result[4]);
            $("#inmsrp_"+arr[1]).val(result[5]);
		}
	});
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=in_code_part_no_config_name&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#ininventoryname_"+arr[1]).html(response);
			$("#ininventoryname_"+arr[1]).trigger("change.select2");
		}
	});
}
</script>
<?php
$get_field_config_inventory = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(inventory_dashboard SEPARATOR ',') AS inventory FROM field_config_inventory"));
$field_config_inventory = ','.$get_field_config_inventory['inventory'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <?php if (strpos($base_field_config, ','."Inventory Category".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Category</label>
            <?php } ?>
            <label class="col-sm-2 text-center">Product Name</label>
			<?php 
			if (strpos($base_field_config, ','."Inventory Part Number".',') !== FALSE) { ?>
			<label class="col-sm-2 text-center">Part Number</label>
			<?php } ?>
            <?php if (strpos($field_config_inventory, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">MSRP</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_inventory = explode('**', $inventory);
            $total_count = mb_substr_count($inventory,'**');
            $id_loop = 500;
            for($pid_loop=0; $pid_loop<$total_count; $pid_loop++) {

                $inventoryid = '';

                if(isset($each_inventory[$pid_loop])) {
                    $each_val = explode('#', $each_inventory[$pid_loop]);
                    $inventoryid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($inventoryid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'inventory_'.$id_loop; ?>">
                <?php if (strpos($base_field_config, ','."Inventory Category".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="<?php echo 'ininventorycat_'.$id_loop; ?>" class="chosen-select-deselect form-control inventoryid inv_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT category FROM inventory");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_inventory($dbc, $inventoryid, 'category') == $row['category']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Product Name:</label>
                    <select data-placeholder="Choose a Unit Number..." id="<?php echo 'ininventoryname_'.$id_loop; ?>" name="inventoryid[]" class="chosen-select-deselect form-control inventoryid inv_partname_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, name FROM inventory");
                        while($row = mysqli_fetch_array($query)) {
                            if ($inventoryid == $row['inventoryid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['inventoryid']."'>".$row['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
				<?php if (strpos($base_field_config, ','."Inventory Part Number".',') !== FALSE) { ?>
					<div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Part Number:</label>
                    <select data-placeholder="Choose a Part Number..." id="<?php echo 'ininventorypart_'.$id_loop; ?>" class="chosen-select-deselect form-control inventoryid inv_partno_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM inventory WHERE inventoryid = '$inventoryid' order by inventoryid");
                        while($row = mysqli_fetch_array($query)) {
                            if ($inventoryid == $row['inventoryid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['inventoryid']."'>".$row['part_no'].'</option>';
                        }
                        ?>
                    </select>
                    </div>
					 <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="inrp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'final_retail_price');?>" id="<?php echo 'inrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="inap[]" value="<?php echo get_inventory($dbc, $inventoryid, 'admin_price');?>" id="<?php echo 'inap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="inwp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'wholesale_price');?>" id="<?php echo 'inwp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="incomp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'commercial_price');?>" id="<?php echo 'incomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="incp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'client_price');?>" id="<?php echo 'incp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="inmsrp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'msrp');?>" id="<?php echo 'inmsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="infinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'infinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'inventory_','ininventoryname_'); return false;" id="<?php echo 'deleteinventory_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_in clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="inventory_0">
                <?php if (strpos($base_field_config, ','."Inventory Category".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="ininventorycat_0" class="chosen-select-deselect form-control inventoryid inv_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM inventory");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Product Name:</label>
                    <select data-placeholder="Choose a Unit Number..." id="ininventoryname_0" name="inventoryid[]" class="chosen-select-deselect form-control inventoryid inv_partname_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, name FROM inventory");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['inventoryid']."'>".$row['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
				<?php if (strpos($base_field_config, ','."Inventory Part Number".',') !== FALSE) { ?>
					<div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Part Number:</label>
                    <select data-placeholder="Choose a Unit Number..." id="ininventorypart_0" class="chosen-select-deselect form-control inventoryid inv_partno_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM inventory order by part_no");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['inventoryid']."'>".$row['part_no'].'</option>';
                        }
                        ?>
                    </select>
                    </div>
					<?php } ?>
                <?php if (strpos($field_config_inventory, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="inrp[]" id="inrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="inap[]" id="inap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="inwp[]" id="inwp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="incomp[]" id="incomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="incp[]" id="incp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="inmsrp[]" id="inmsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="infinalprice[]" id="infinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'inventory_','ininventoryname_'); return false;" id="deleteinventory_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_in"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_in" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>