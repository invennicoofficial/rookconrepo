<script>
$(document).ready(function() {
	//Services
    var add_new_s = 1;
    $('#deleteservices_0').hide();
    $('#add_row_s').on( 'click', function () {
        $('#deleteservices_0').show();
        var clone = $('.additional_s').clone();
        clone.find('.form-control').val('');

        clone.find('#sservice_0').attr('id', 'sservice_'+add_new_s);
		clone.find('#scategory_0').attr('id', 'scategory_'+add_new_s);
        clone.find('#sheading_0').attr('id', 'sheading_'+add_new_s);
        clone.find('#sfrp_0').attr('id', 'sfrp_'+add_new_s);
        clone.find('#sap_0').attr('id', 'sap_'+add_new_s);
        clone.find('#swp_0').attr('id', 'swp_'+add_new_s);
        clone.find('#scomp_0').attr('id', 'scomp_'+add_new_s);
        clone.find('#scp_0').attr('id', 'scp_'+add_new_s);
        clone.find('#smsrp_0').attr('id', 'smsrp_'+add_new_s);
        clone.find('#smb_0').attr('id', 'smb_'+add_new_s);
        clone.find('#seh_0').attr('id', 'seh_'+add_new_s);
        clone.find('#sah_0').attr('id', 'sah_'+add_new_s);

        clone.find('#sc_0').attr('id', 'sc_'+add_new_s);
        clone.find('#sprofit_0').attr('id', 'sprofit_'+add_new_s);
        clone.find('#sprofitmargin_0').attr('id', 'sprofitmargin_'+add_new_s);

		clone.find('#sfinalprice_0').attr('id', 'sfinalprice_'+add_new_s);
		clone.find('#sestimateprice_0').attr('id', 'sestimateprice_'+add_new_s);
		clone.find('#sestimateqty_0').attr('id', 'sestimateqty_'+add_new_s);
		clone.find('#sestimateunit_0').attr('id', 'sestimateunit_'+add_new_s);
		clone.find('#sestimatetotal_0').attr('id', 'sestimatetotal_'+add_new_s);

        clone.find('#services_0').attr('id', 'services_'+add_new_s);
        clone.find('#deleteservices_0').attr('id', 'deleteservices_'+add_new_s);
        $('#deleteservices_0').hide();

        clone.removeClass("additional_s");
        $('#add_here_new_s').append(clone);

        resetChosen($("#sservice_"+add_new_s));
        resetChosen($("#scategory_"+add_new_s));
        resetChosen($("#sheading_"+add_new_s));

        add_new_s++;

        return false;
    });
	changeServiceTotal();
});
//Services
function selectServiceService(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=s_service_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#scategory_"+arr[1]).html(response);
			$("#scategory_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectServiceCat(sel) {
	var stage = encodeURIComponent(sel.value);
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=s_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sheading_"+arr[1]).html(response);
			$("#sheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectServiceHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=s_head_config&value="+stage+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#sfrp_"+arr[1]).val(result[0]);
            $("#sap_"+arr[1]).val(result[1]);
            $("#swp_"+arr[1]).val(result[2]);
            $("#scomp_"+arr[1]).val(result[3]);
            $("#scp_"+arr[1]).val(result[4]);
            $("#smsrp_"+arr[1]).val(result[5]);
			$("#sfinalprice_"+arr[1]).val(result[6]);
			$("#smb_"+arr[1]).val(result[7]);
			$("#seh_"+arr[1]).val(result[8]);
			$("#sah_"+arr[1]).val(result[9]);
            $("#sc_"+arr[1]).val(result[10]);
		}
	});
}
function countService(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');
        var lbqty = $('#sestimateqty_'+split_id[1]).val();
        if(lbqty == null || lbqty == '') {
            lbqty = 1;
        }

        document.getElementById('sestimatetotal_'+split_id[1]).value = parseFloat($('#sestimateprice_'+split_id[1]).val() * lbqty).toFixed(2);
    }

    var sum_fee = 0;
    $('[name="sestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_services_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="service_total"]').val('$'+round2Fixed(sum_fee).toFixed(2));
    $('[name="service_summary"]').val('$'+round2Fixed(sum_fee).toFixed(2));

    var service_budget = $('[name="service_budget"]').val();
    if(service_budget >= sum_fee) {
        $('[name="service_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="service_total"]').css("background-color", "#ff9999"); // Green
    }
}

function fillmarginvalueServices(est) {
    var idarray = est.id.split("_");
    var profitid = 'sprofit_' + idarray[1];
    var profitmarginid = 'sprofitmargin_' + idarray[1];
    var pcid = 'sc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#sestimateqty_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#sestimateqty_' + idarray[1]).val(1);
        qty = 1;
    }
    if(parseFloat(pestimatevalue) < parseFloat(pcvalue)) {
        jQuery('#'+profitid).val('');
        jQuery('#'+profitmarginid).val('');
    }
    else if(typeof pcvalue != 'undefined' && pcvalue != null && pcvalue != '' && pestimatevalue != null && pestimatevalue != '') {
        var deltavalue = (pestimatevalue - pcvalue) * qty;
        var deltaper = (deltavalue / (pestimatevalue * qty)) * 100;
        if(deltavalue > 0) {
            jQuery('#'+profitid).val(deltavalue.toFixed(2));
            jQuery('#'+profitmarginid).val(deltaper.toFixed(2));
        }
    }

    changeServiceTotal();
}

function qtychangevalueServices(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'sprofit_' + idarray[1];
    var profitmarginid = 'sprofitmargin_' + idarray[1];
    var pestimateid = 'sestimateprice_' + idarray[1];
    var pcid = 'sc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(del);
    jQuery('#'+profitmarginid).val(delper.toFixed(2));
    changeServiceTotal();
}

function changeServiceTotal() {
    var sum_profit = 0;
    var sum_total = 0;
    $('[name="sprofit[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });
    $('[name="sestimatetotal[]"]').each(function () {
        sum_total += +$(this).val() || 0;
    });
    $('[name="crc_services_total[]"]').each(function () {
		var qty = 0 + $(this).parents('.form-group').find('[name="crc_services_qty[]"]').val();
		var cost = 0 + $(this).parents('.form-group').find('[name="crc_services_cost[]"]').val();
		sum_profit += $(this).val() - qty * cost;
		sum_total += +$(this).val();
    });

	margin = sum_profit / sum_total;
	if(isNaN(margin)) {
		margin = 'N/A';
	}
	else {
		margin = round2Fixed(margin * 100) + '%';
	}
    $('#services_profit').val('$'+round2Fixed(sum_profit));
    $('#services_profit_margin').val(margin);
    $('#services_cost').val('$'+round2Fixed(sum_total - sum_profit));
    $('[name=service_total]').val('$'+round2Fixed(sum_total));
    $('[name=services_summary_profit]').val('$'+round2Fixed(sum_profit));
    $('[name=services_summary_margin]').val(margin);
    $('[name=services_summary_cost]').val('$'+round2Fixed(sum_total - sum_profit));
    $('[name=services_summary]').val('$'+round2Fixed(sum_total)).change();
}

function countRCTotalService(sel) {
	var stage = sel.value;
	var typeId = sel.id;

	var arr = typeId.split('_');
    var del = (jQuery('#crc_services_custprice_'+arr[3]).val() * jQuery('#crc_services_qty_'+arr[3]).val());
    jQuery('#crc_services_total_'+arr[3]).val(round2Fixed(del));

    var sum_fee = 0;
    $('[name="sestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_services_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });

    $('[name="service_total"]').val(round2Fixed(sum_fee));
	changeServiceTotal();
}
</script>
<?php
$get_field_config_service = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT services_dashboard FROM field_config"));
$field_config_service = ','.$get_field_config_service['services_dashboard'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <?php
			$columns = 0;
			if (strpos($base_field_config, ','."Services Service Type".',') !== FALSE) {
				$columns += 2;
			}
			if (strpos($base_field_config, ','."Services Category".',') !== FALSE) {
				$columns += 2;
			}
			if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Client Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."MSRP".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Minimum Billable".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Estimated Hours".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Actual Hours".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_service, ','."Cost".',') !== FALSE) {
				$columns += 1;
			}
			$columns += 9;
			?>
            <?php if (strpos($base_field_config, ','."Services Service Type".',') !== FALSE) { ?>
				<label class="col-sm-2 text-center" data-columns="<?php echo $columns; ?>" data-width="2">Service Type</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."Services Category".',') !== FALSE) { ?>
				<label class="col-sm-2 text-center" data-columns="<?php echo $columns; ?>" data-width="2">Category</label>
            <?php } ?>
            <label class="col-sm-2 text-center" data-columns="<?php echo $columns; ?>" data-width="2">Heading</label>
            <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">MSRP</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Minimum Billable".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Minimum Billable Hours</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Estimated Hours".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Estimated Hours</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Actual Hours".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Actual Hours</label>
            <?php } ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Rate Card Price</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">UOM</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Quantity</label>
            <?php if (strpos($field_config_service, ','."Cost".',') !== FALSE) { ?>
				<label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Cost</label>
            <?php } ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">% Margin</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1"><?= ESTIMATE_TILE ?> Price</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">$ Profit</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Total</label>
        </div>

       <?php
        $get_services = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_services'),'**#**');
                $get_services  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_services'),'**#**');
                $get_services  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_services'),'**#**');
                $get_services  .= '**'.$each_item;
            }
        }

        if(!empty($_GET['estimateid'])) {
            $services = $get_contact['services'];
            $each_servicesid = explode('**',$services);
            foreach($each_servicesid as $id_all) {
                if($id_all != '') {
                    $servicesid_all = explode('#',$id_all);
                    $get_services .= '**'.$servicesid_all[0].'#'.$servicesid_all[2].'#'.$servicesid_all[1].'#'.$servicesid_all[3];
                }
            }
        }
        $final_total_services = 0;
        ?>

        <?php if(!empty($get_services)) {
            $each_assign_inventory = explode('**',$get_services);
            $total_count = mb_substr_count($get_services,'**');
            $id_loop = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $each_item = explode('#',$each_assign_inventory[$inventory_loop]);
                $serviceid = '';
                $qty = '';
                $est = '';
                $unit = '';
                if(isset($each_item[0])) {
                    $serviceid = $each_item[0];
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
                $final_total_services += $total;
                if($serviceid != '') {

                    $services = explode('**', $get_rc['services']);
                    $rc_price = 0;
                    foreach($services as $pp){
                        if (strpos('#'.$pp, '#'.$serviceid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>

            <div class="form-group clearfix" id="<?php echo 'services_'.$id_loop; ?>" >
                <?php if (strpos($base_field_config, ','."Services Service Type".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectServiceService(this)' data-placeholder="Choose a Type..." id="<?php echo 'sservice_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0 order by service_type");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_services($dbc, $serviceid, 'service_type') == $row['service_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['service_type']."'>".$row['service_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($base_field_config, ','."Services Category".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectServiceCat(this)' data-placeholder="Choose a Category..." id="<?php echo 'scategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_services($dbc, $serviceid, 'category') == $row['category']) {
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
                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectServiceHeading(this)' data-placeholder="Choose a Heading..." id="<?php echo 'sheading_'.$id_loop; ?>" name="serviceid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            if ($serviceid == $row['serviceid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['serviceid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="<?php echo 'sheading_'.$id_loop; ?>" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sfrp[]" value="<?php echo get_services($dbc, $serviceid, 'final_retail_price');?>" id="<?php echo 'sfrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sap[]" value="<?php echo get_services($dbc, $serviceid, 'admin_price');?>" id="<?php echo 'sap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="swp[]" value="<?php echo get_services($dbc, $serviceid, 'wholesale_price');?>" id="<?php echo 'swp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="scomp[]" value="<?php echo get_services($dbc, $serviceid, 'commercial_price');?>" id="<?php echo 'scomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="scp[]" value="<?php echo get_services($dbc, $serviceid, 'client_price');?>" id="<?php echo 'scp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="smsrp[]" value="<?php echo get_services($dbc, $serviceid, 'msrp');?>" id="<?php echo 'smsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Minimum Billable".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                        <input name="smb[]" value="<?php echo get_services($dbc, $serviceid, 'minimum_billable');?>" id="<?php echo 'smb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Estimated Hours".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                        <input name="seh[]" value="<?php echo get_services($dbc, $serviceid, 'estimated_hours');?>" id="<?php echo 'seh_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Actual Hours".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                        <input name="sah[]" value="<?php echo get_services($dbc, $serviceid, 'actual_hours');?>" id="<?php echo 'sah_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sfinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'sfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimateunit[]" id="<?php echo 'sestimateunit_'.$id_loop; ?>" value="<?php echo $unit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimateqty[]" id="<?php echo 'sestimateqty_'.$id_loop; ?>" onchange="countService(this); qtychangevalueServices(this);" value="<?php echo $qty; ?>" type="text" class="form-control" />
                </div>
                <?php if (strpos($field_config_service, ','."Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sc[]" value="<?php echo get_services($dbc, $serviceid, 'cost');?>" id="<?php echo 'sc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sprofitmargin[]" id="<?php echo 'sprofitmargin_'.$id_loop; ?>" readonly="" onchange="countService(this);" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimateprice[]" id="<?php echo 'sestimateprice_'.$id_loop; ?>" onchange="countService(this); fillmarginvalueServices(this);" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sprofit[]" id="<?php echo 'sprofit_'.$id_loop; ?>" readonly="" onchange="countService(this);" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'sestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <a href="#" onclick="deleteEstimate(this,'services_','sheading_'); return false;" id="<?php echo 'deleteservices_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_s clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="services_0">
                <?php if (strpos($base_field_config, ','."Services Service Type".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectServiceService(this)' data-placeholder="Choose a Type..." id="sservice_0" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0 order by service_type");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['service_type']."'>".$row['service_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($base_field_config, ','."Services Category".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectServiceCat(this)' data-placeholder="Choose a Category..." id="scategory_0" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectServiceHeading(this)' data-placeholder="Choose a Heading..." id="sheading_0" name="serviceid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['serviceid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="sheading_0" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sfrp[]" id="sfrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sap[]" id="sap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="swp[]" id="swp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="scomp[]" id="scomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="scp[]" id="scp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="smsrp[]" id="smsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <?php if (strpos($field_config_service, ','."Minimum Billable".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                        <input name="smb[]" id="smb_0" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Estimated Hours".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                        <input name="seh[]" id="seh_0" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Actual Hours".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                        <input name="sah[]" id="sah_0" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sfinalprice[]" readonly id="sfinalprice_0" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimateunit[]" id='sestimateunit_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimateqty[]" id='sestimateqty_0' onchange="countService(this);  qtychangevalueServices(this);" type="text" class="form-control" />
                </div>

                <?php if (strpos($field_config_service, ','."Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sc[]" id="sc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sprofitmargin[]" id='sprofitmargin_0' readonly="" onchange="countService(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimateprice[]" id='sestimateprice_0' onchange="countService(this); fillmarginvalueServices(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sprofit[]" id='sprofit_0' readonly="" onchange="countService(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="sestimatetotal[]" id='sestimatetotal_0' type="text" class="form-control" />
                </div>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <a href="#" onclick="deleteEstimate(this,'services_','sheading_'); return false;" id="deleteservices_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_s"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_s" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>

<?php
if(!empty($_GET['estimateid'])) {
    $query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories' AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')) OR $universal_rc_search) AND tile_name='Services' AND `deleted`=0");

    $num_rows = mysqli_num_rows($query_rc);
    if($num_rows > 0) { ?>
        <div class="form-group clearfix">
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Type':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
						break;
					case 'Heading':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
						break;
					case 'Description':
						echo '<label class="col-sm-3 text-center">'.$data[1].'</label>';
						break;
					case 'UOM':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Quantity':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Cost':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Price':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Total':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
				}
			} ?>
        </div>
        <?php
    }
    $rc = 0;
    while($row_rc = mysqli_fetch_array($query_rc)) {

        $companyrcid = $row_rc['companyrcid'];

        $estimate_company_rate_card = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM estimate_company_rate_card WHERE companyrcid='$companyrcid' AND estimateid='$estimateid'"));

        ?>
        <input type="hidden" name="crc_services_companyrcid[]" value="<?php echo $row_rc['companyrcid']; ?>" />

        <div class="form-group clearfix" width="100%" <?php echo $load_tab != 'Master' && strpos($estimateConfigValue,',Services'.$row_rc['rate_card_types'].',') === false ? 'style="display:none;"' : ''; ?>>
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Type': ?>
						<div class="col-sm-2">
							<input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_services_type[]" type="text" class="form-control" />
						</div>
						<?php break;
					case 'Heading': ?>
						<div class="col-sm-2">
							<input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_services_heading[]" type="text" class="form-control" />
						</div>
						<?php break;
					case 'Description': ?>
						<div class="col-sm-3">
							<input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_services_description[]" type="text" class="form-control" />
						</div>
						<?php break;
					case 'UOM': ?>
						<div class="col-sm-1">
							<input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_services_uom[]" type="text" class="form-control" />
						</div>
						<?php break;
					case 'Cost': ?>
						<div class="col-sm-1">
							<input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_services_cost[]" type="text" class="form-control" />
						</div>
						<?php break;
					case 'Price': ?>
						<div class="col-sm-1">
							<input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="countRCTotalService(this)" name="crc_services_cust_price[]" type="text" id="crc_services_custprice_<?php echo $rc;?>" class="form-control" />
						</div>
						<?php break;
					case 'Quantity': ?>
						<div class="col-sm-1">
							<input name="crc_services_qty[]" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="countRCTotalService(this)" id="crc_services_qty_<?php echo $rc;?>" class="form-control" />
						</div>
						<?php break;
					case 'Total': ?>
						<div class="col-sm-1">
							<input name="crc_services_total[]" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_services_total_<?php echo $rc;?>" type="text" class="form-control" />
						</div>
						<?php break;
				}
			} ?>
        </div>
		<div style="display:none;">
			<?php if(!in_array_starts('Type', $field_order)): ?>
				<input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_services_type[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Heading', $field_order)): ?>
				<input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_services_heading[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Description', $field_order)): ?>
				<input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_services_description[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('UOM', $field_order)): ?>
				<input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_services_uom[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Cost', $field_order)): ?>
				<input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_services_cost[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Price', $field_order)): ?>
				<input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="countRCTotalService(this)" name="crc_services_cust_price[]" type="text" id="crc_services_custprice_<?php echo $rc;?>" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Quantity', $field_order)): ?>
				<input name="crc_services_qty[]" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="countRCTotalService(this)" id="crc_services_qty_<?php echo $rc;?>" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Total', $field_order)): ?>
				<input name="crc_services_total[]" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_services_total_<?php echo $rc;?>" type="text" class="form-control" />
			<?php endif; ?>
		</div>

    <?php
        $rc++;
        $final_total_services += $estimate_company_rate_card['rc_total'];
    }
}
?>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Cost: </label>
    <div class="col-sm-8">
      <input name="services_cost" id="services_cost" value="" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
    <div class="col-sm-8">
      <input name="services_profit" id="services_profit" value="" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
    <div class="col-sm-8">
      <input name="services_profit_margin" id="services_profit_margin" value="" readonly="" type="text" class="form-control">
    </div>
</div>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="service_budget" value="<?php echo $budget_price[3]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="service_total" value="<?php echo $final_total_services;?>" type="text" class="form-control">
    </div>
</div>
