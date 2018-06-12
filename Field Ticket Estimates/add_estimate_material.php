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
        clone.find('#mcprofit_0').attr('id', 'mcprofit_'+add_new_m);
        clone.find('#mcprofitmargin_0').attr('id', 'mcprofitmargin_'+add_new_m);
		clone.find('#mfinalprice_0').attr('id', 'mfinalprice_'+add_new_m);
		clone.find('#mestimateprice_0').attr('id', 'mestimateprice_'+add_new_m);
		clone.find('#mestimateqty_0').attr('id', 'mestimateqty_'+add_new_m);
		clone.find('#mestimateunit_0').attr('id', 'mestimateunit_'+add_new_m);
		clone.find('#mestimatetotal_0').attr('id', 'mestimatetotal_'+add_new_m);

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
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=material_config&value="+stage+"&ratecardid="+ratecardid,
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
			$("#mfinalprice_"+arr[1]).val(result[7]);
		}
	});
}
function countMaterial(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');
        var meqty = $('#mestimateqty_'+split_id[1]).val();
        if(meqty == null || meqty == '') {
            meqty = 1;
        }

        document.getElementById('mestimatetotal_'+split_id[1]).value = parseFloat($('#mestimateprice_'+split_id[1]).val() * meqty);
    }

    var sum_fee = 0;
    $('[name="mestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });

    $('[name="material_total"]').val(round2Fixed(sum_fee));
    $('[name="material_summary"]').val(round2Fixed(sum_fee));

    var material_budget = $('[name="material_budget"]').val();
    if(material_budget >= sum_fee) {
        $('[name="material_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="material_total"]').css("background-color", "#ff9999"); // Green
    }
}

function fillmaterialmarginvalue(est) {
    var idarray = est.id.split("_");
    var profitid = 'mcprofit_' + idarray[1];
    var profitmarginid = 'mcprofitmargin_' + idarray[1];
    var pcid = 'mprice_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#mestimateqty_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#mestimateqty_' + idarray[1]).val(1);
        qty = 1;
    }
    if(parseInt(pestimatevalue) < parseInt(pcvalue)) {
        jQuery('#'+profitid).val('');
        jQuery('#'+profitmarginid).val('');
    }
    else if(typeof pcvalue != 'undefined' && pcvalue != null && pcvalue != '' && pestimatevalue != null && pestimatevalue != '') {
        var deltavalue = (pestimatevalue - pcvalue) * qty;
        var deltaper = (deltavalue / (pestimatevalue * qty)) * 100;
        if(deltavalue > 0) {
            jQuery('#'+profitid).val(deltavalue);
            jQuery('#'+profitmarginid).val(deltaper.toFixed(2));
        }
    }

    changeMaterialTotal();
}

function qtymaterialchangevalue(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'mcprofit_' + idarray[1];
    var profitmarginid = 'mcprofitmargin_' + idarray[1];
    var pestimateid = 'mestimateprice_' + idarray[1];
    var pcid = 'mprice_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(del);
    jQuery('#'+profitmarginid).val(delper.toFixed(2));
    changeMaterialTotal();
}

function changeMaterialTotal() {
    var sum_profit = 0;
    var sum_profit_margin = 0;
    jQuery('[name="mcprofit[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });

    var count = 0;
    jQuery('[name="mcprofitmargin[]"]').each(function () {
        sum_profit_margin += +$(this).val() || 0;
        count++;
    });

    per_profit_margin = sum_profit_margin / count;

    jQuery('#material_profit').val(round2Fixed(sum_profit));
    jQuery('#material_profit_margin').val(round2Fixed(per_profit_margin));
}

</script>
<?php
$get_field_config_inventory = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT material FROM field_config"));
$field_config_inventory = ','.$get_field_config_inventory['material'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
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
            <label class="col-sm-1 text-center">Bid Price</label>
            <label class="col-sm-1 text-center">UOM</label>
            <label class="col-sm-1 text-center">Quantity</label>
            <label class="col-sm-1 text-center">Total</label>
            <label class="col-sm-1 text-center">$ Profit</label>
            <label class="col-sm-1 text-center">% Margin</label>
        </div>

        <?php
        $get_material = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_material'),'**#**');
                $get_material  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_material'),'**#**');
                $get_material  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_material'),'**#**');
                $get_material  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['estimateid'])) {
            $material = $get_contact['material'];
            $each_data = explode('**',$material);
            foreach($each_data as $id_all) {
                if($id_all != '') {
                    $data_all = explode('#',$id_all);
                    $get_material .= '**'.$data_all[0].'#'.$data_all[2].'#'.$data_all[1].'#'.$data_all[3];
                }
            }
        }
        $final_total_material = 0;
        ?>

        <?php if(!empty($get_material)) {
            $each_assign_inventory = explode('**',$get_material);
            $total_count = mb_substr_count($get_material,'**');
            $id_loop = 500;
            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {

                $each_item = explode('#',$each_assign_inventory[$inventory_loop]);
                $materialid = '';
                $qty = '';
                $est = '';
                $unit = '';
                if(isset($each_item[0])) {
                    $materialid = $each_item[0];
                }
                if(isset($each_item[1])) {
                    $qty = $each_item[1];
                }
                if(isset($each_item[2])) {
                    $est = $each_item[2];
                }
                if(isset($each_item[3])) {
                    $unit = $each_item[3];
                }
                $total = $qty*$est;
                $final_total_material += $total;

                if($materialid != '') {

                    $material = explode('**', $get_rc['material']);
                    $rc_price = 0;
                    foreach($material as $pp){
                        if (strpos('#'.$pp, '#'.$materialid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>

            <div class="form-group clearfix" id="<?php echo 'material_'.$id_loop; ?>">
                <div class="col-sm-1">
                    <select data-placeholder="Choose a Code..." id="<?php echo 'materialid_'.$id_loop; ?>" name="materialid[]" class="chosen-select-deselect form-control materialid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT code, materialid FROM material order by code");
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
                <div class="col-sm-1">
                    <input name="mname[]" value="<?php echo get_material($dbc, $materialid, 'name');?>" id="<?php echo 'mname_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php if (strpos($field_config_inventory, ','."Width".',') !== FALSE) { ?>
                <div class="col-sm-1">
                    <input name="mwidth[]" value="<?php echo get_material($dbc, $materialid, 'width');?>" id="<?php echo 'mwidth_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Length".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="mlength[]" value="<?php echo get_material($dbc, $materialid, 'length');?>" id="<?php echo 'mlength_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Units".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="munits[]" value="<?php echo get_material($dbc, $materialid, 'units');?>" id="<?php echo 'munits_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Unit Weight".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="munitweight[]" value="<?php echo get_material($dbc, $materialid, 'unit_weight');?>" id="<?php echo 'munitweight_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Weight Per Feet".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="mwpf[]" value="<?php echo get_material($dbc, $materialid, 'weight_per_feet');?>" id="<?php echo 'mwpf_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Price".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="mprice[]" value="<?php echo get_material($dbc, $materialid, 'price');?>" id="<?php echo 'mprice_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" >
                    <input name="mfinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'mfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimateprice[]" value="<?php echo $est; ?>" onchange="countMaterial(this); fillmaterialmarginvalue(this);" id="<?php echo 'mestimateprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimateunit[]" value="<?php echo $unit; ?>" id="<?php echo 'mestimateunit_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimateqty[]" value="<?php echo $qty; ?>" onchange="countMaterial(this)l qtymaterialchangevalue(this);" id="<?php echo 'mestimateqty_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimatetotal[]" id="<?php echo 'mestimatetotal_'.$id_loop; ?>" value="<?php echo $total; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mcprofit[]" id="<?php echo 'mcprofit_'.$id_loop; ?>" readonly="" onchange="countmaterial(this)" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mcprofitmargin[]" id="<?php echo 'mcprofitmargin_'.$id_loop; ?>" readonly="" onchange="countmaterial(this)" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'material_','materialid_'); return false;" id="<?php echo 'deletematerial_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>

            <?php  $id_loop++;
                    }
                }
            }  ?>

        <div class="additional_m clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="material_0">
                <div class="col-sm-1">
                    <select data-placeholder="Choose a Code..." id="materialid_0" name="materialid[]" class="chosen-select-deselect form-control materialid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT code, materialid FROM material order by code");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['materialid']."'>".$row['code'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1">
                    <input name="mname[]" id="mname_0" readonly type="text" class="form-control" />
                </div>
                <?php if (strpos($field_config_inventory, ','."Width".',') !== FALSE) { ?>
                <div class="col-sm-1">
                    <input name="mwidth[]" id="mwidth_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Length".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="mlength[]" id="mlength_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Units".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="munits[]" id="munits_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Unit Weight".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="munitweight[]" id="munitweight_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Weight Per Feet".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="mwpf[]" id="mwpf_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Price".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="mprice[]" id="mprice_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="mfinalprice[]" readonly id="mfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimateprice[]" id='mestimateprice_0' onchange="countMaterial(this); fillmaterialmarginvalue(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimateunit[]" id='mestimateunit_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimateqty[]" id='mestimateqty_0' onchange="countMaterial(this); qtymaterialchangevalue(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mestimatetotal[]" id='mestimatetotal_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mcprofit[]" id='mcprofit_0' readonly="" onchange="countmaterial(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="mcprofitmargin[]" id='mcprofitmargin_0' readonly="" onchange="countmaterial(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'material_','materialid_'); return false;" id="deletematerial_0" class="btn brand-btn">Delete</a>
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

<?php
if(!empty($_GET['estimateid'])) {
    $query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE (rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') AND tile_name='Material'");

    $num_rows = mysqli_num_rows($query_rc);
    if($num_rows > 0) { ?>
        <div class="form-group clearfix">
            <label class="col-sm-3 text-center">Type</label>
            <label class="col-sm-3 text-center">Heading</label>
            <label class="col-sm-1 text-center">Description</label>
            <label class="col-sm-1 text-center">UOM</label>
            <label class="col-sm-1 text-center">Cost</label>
            <label class="col-sm-1 text-center">Cust Price</label>
        </div>
        <?php
    }
    while($row_rc = mysqli_fetch_array($query_rc)) {
        ?>
        <input type="hidden" name="crc_material_companyrcid[]" value="<?php echo $row_rc['companyrcid']; ?>" />

        <div class="form-group clearfix" width="100%">
            <div class="col-sm-3">
                <input value= "<?php echo $row_rc['rate_card_types']; ?>" name="crc_material_type[]" type="text" class="form-control" />
            </div>
            <div class="col-sm-3">
                <input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" name="crc_material_heading[]" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $row_rc['description']; ?>" name="crc_material_description[]" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $row_rc['uom']; ?>" name="crc_material_uom[]" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $row_rc['cost']; ?>" name="crc_material_cost[]" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" name="crc_material_cust_price[]" type="text" class="form-control" />
            </div>
        </div>

    <?php
    }
}
?>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
    <div class="col-sm-8">
      <input name="material_profit" id="material_profit" value="" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
    <div class="col-sm-8">
      <input name="material_profit_margin" id="material_profit_margin" value="" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="material_budget" value="<?php echo $budget_price[14]; ?>" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="material_total" value="<?php echo $final_total_material;?>" type="text" class="form-control">
    </div>
</div>
