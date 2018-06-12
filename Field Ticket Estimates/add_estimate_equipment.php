<script>
$(document).ready(function() {
    $('.all_equipment').hide();
    $('.equipment_heading').hide();

	$('.order_list_equipment').on( 'click', function () {
        var pro_type = $(this).attr("id");

        $('.all_equipment').hide();
        $('.'+pro_type).show();
        $('.equipment_heading').show();

		$('.order_list_equipment').removeClass('active_tab');
        $(this).addClass('active_tab');
    });

	//Equipment
    var add_new_eq = 1;
    $('#deleteequipment_0').hide();
    $('#add_row_eq').on( 'click', function () {
        $('#deleteequipment_0').show();
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
		clone.find('#eqfinalprice_0').attr('id', 'eqfinalprice_'+add_new_eq);
		clone.find('#eqestimateprice_0').attr('id', 'eqestimateprice_'+add_new_eq);
		clone.find('#eqestimateqty_0').attr('id', 'eqestimateqty_'+add_new_eq);
		clone.find('#eqestimateunit_0').attr('id', 'eqestimateunit_'+add_new_eq);
		clone.find('#eqestimatetotal_0').attr('id', 'eqestimatetotal_'+add_new_eq);

        clone.find('#eqc_0').attr('id', 'eqc_'+add_new_eq);
        clone.find('#eqprofit_0').attr('id', 'eqprofit_'+add_new_eq);
        clone.find('#eqprofitmargin_0').attr('id', 'eqprofitmargin_'+add_new_eq);

        clone.find('#equipment_0').attr('id', 'equipment_'+add_new_eq);
        clone.find('#deleteequipment_0').attr('id', 'deleteequipment_'+add_new_eq);
        $('#deleteequipment_0').hide();

        clone.removeClass("additional_eq");
        $('#add_here_new_eq').append(clone);

        resetChosen($("#eqequipmentcat_"+add_new_eq));
        resetChosen($("#eqequipmentun_"+add_new_eq));

        add_new_eq++;

        return false;
    });

    var add_new_eq_misc = 1;
	$('#deleteequipmentmisc_0').hide();
	$('#add_row_eq_misc').on( 'click', function () {

		$('#deleteequipmentmisc_0').show();
        var clone_misc = $('.additional_eq_misc').clone();
        clone_misc.find('.form-control').val('');
		clone_misc.find('#eqid_misc_0').attr('id', 'eqid_misc_'+add_new_eq_misc);
        clone_misc.find('#eqtype_misc_0').attr('id', 'eqtype_misc_'+add_new_eq_misc);
		clone_misc.find('#eqdisc_misc_0').attr('id', 'eqdisc_misc_'+add_new_eq_misc);
		clone_misc.find('#equom_misc_0').attr('id', 'equom_misc_'+add_new_eq_misc);
		clone_misc.find('#eqheadmisc_0').attr('id', 'eqheadmisc_'+add_new_eq_misc);
		clone_misc.find('#eqcostmisc_0').attr('id', 'eqcostmisc_'+add_new_eq_misc);
		clone_misc.find('#eqqtymisc_0').attr('id', 'eqqtymisc_'+add_new_eq_misc);
		clone_misc.find('#eqtotalmisc_0').attr('id', 'eqtotalmisc_'+add_new_eq_misc);
		clone_misc.find('#eqestimatepricemisc_0').attr('id', 'eqestimatepricemisc_'+add_new_eq_misc);
		clone_misc.find('#eqmarginmisc_0').attr('id', 'eqmarginmisc_'+add_new_eq_misc);
		clone_misc.find('#eqprofitmisc_0').attr('id', 'eqprofitmisc_'+add_new_eq_misc);
        clone_misc.find('#equipmentmisc_0').attr('id', 'equipmentmisc_'+add_new_eq_misc);
        clone_misc.find('#deleteequipmentmisc_0').attr('id', 'deleteequipmentmisc_'+add_new_eq_misc);
        $('#deleteequipmentmisc_0').hide();

        clone_misc.removeClass("additional_eq_misc");

        $('#add_here_new_eq_misc').append(clone_misc);

        add_new_eq_misc++;

        return false;
    });
});
$(document).on('change', 'select.equip_cat_onchange', function() { selectEquipmentCategory(this); });
$(document).on('change', 'select[name="equipmentid[]"]', function() { selectEquipmentUnSn(this); });
//Equipment
function selectEquipmentCategory(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=eq_cat_config&value="+stage+"&ratecardid="+ratecardid,
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
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=eq_un_sn_config&value="+stage+"&ratecardid="+ratecardid,
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
			$("#eqfinalprice_"+arr[1]).val(result[7]);
            $("#eqc_"+arr[1]).val(result[8]);
        }
	});
}
function fillmargincrcequipmentvalue(est) {
	var idarray = est.id.split("_");
    var profitid = 'crc_equipment_profit_' + idarray[3];
    var profitmarginid = 'crc_equipment_margin_' + idarray[3];
    var pcid = 'crc_equipment_cost_' + idarray[3];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#crc_equipment_qty_' + idarray[3]).val();
    if(qty == '' || qty == null) {
        jQuery('#crc_equipment_qty_' + idarray[3]).val(1);
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

    changeEquipmentTotal();
}

function countEquipment(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');

        var lbqty = $('#eqestimateqty_'+split_id[1]).val();
        if(lbqty == null || lbqty == '') {
            lbqty = 1;
        }

        document.getElementById('eqestimatetotal_'+split_id[1]).value = parseFloat($('#eqestimateprice_'+split_id[1]).val() * lbqty);
    }

    var sum_fee = 0;
    //$('[name="eqestimatetotal[]"]').each(function () {
        sum_fee += +document.getElementById('eqestimatetotal_'+split_id[1]).value || 0;
    //});
    $('[name="crc_equipment_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });

    sum_fee += +$('[name="equ_total"]').val();
    $('[name="equ_total"]').val(round2Fixed(sum_fee));
    $('[name="equipment_summary"]').val(round2Fixed(sum_fee));

    var equ_budget = $('[name="equ_budget"]').val();
    if(equ_budget >= sum_fee) {
        $('[name="equ_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="equ_total"]').css("background-color", "#ff9999"); // Green
    }
}


function fillmarginvalueEquipment(est) {
    var idarray = est.id.split("_");
    var profitid = 'eqprofit_' + idarray[1];
    var profitmarginid = 'eqprofitmargin_' + idarray[1];
    var pcid = 'eqc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#eqestimateqty_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#eqestimateqty_' + idarray[1]).val(1);
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

    changeEquipmentTotal();
}

function qtychangecrcvalueEquipment(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'crc_equipment_profit_' + idarray[3];
    var profitmarginid = 'crc_equipment_margin_' + idarray[3];
    var pestimateid = 'crc_equipment_custprice_' + idarray[3];
    var pcid = 'crc_equipment_cost_' + idarray[3];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(del);
    jQuery('#'+profitmarginid).val(delper.toFixed(2));
	changeEquipmentTotal();
}

function fillmarginmiscvalueEquipment(est) {
	var idarray = est.id.split("_");
    var profitid = 'eqprofitmisc_' + idarray[1];
    var profitmarginid = 'eqmarginmisc_' + idarray[1];
    var pcid = 'eqcostmisc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#eqqtymisc_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#eqqtymisc_' + idarray[1]).val(1);
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
	changeEquipmentTotal();
}

function qtychangecrcvalueEquipment(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'crc_equipment_profit_' + idarray[3];
    var profitmarginid = 'crc_equipment_margin_' + idarray[3];
    var pestimateid = 'crc_equipment_custprice_' + idarray[3];
    var pcid = 'crc_equipment_cost_' + idarray[3];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(del);
    jQuery('#'+profitmarginid).val(delper.toFixed(2));
	changeEquipmentTotal();
}

function qtychangemiscvalueEquipment(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'eqprofitmisc_' + idarray[1];
    var profitmarginid = 'eqmarginmisc_' + idarray[1];
    var pestimateid = 'eqestimatepricemisc_' + idarray[1];
    var pcid = 'eqcostmisc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(del);
    jQuery('#'+profitmarginid).val(delper.toFixed(2));
	changeEquipmentTotal();
}

function qtychangevalueEquipment(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'eqprofit_' + idarray[1];
    var profitmarginid = 'eqprofitmargin_' + idarray[1];
    var pestimateid = 'eqestimateprice_' + idarray[1];
    var pcid = 'eqc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(del);
    jQuery('#'+profitmarginid).val(delper.toFixed(2));
    changeEquipmentTotal();
}

function changeEquipmentTotal() {
    var sum_profit = 0;
    var sum_profit_margin = 0;
    var misc_profit_margin = 0;
    var crc_profit_fee = 0;
    var crc_margin_fee = 0;
    jQuery('[name="eqprofit[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });

    for(var loop = 0; loop < 500; loop++) {
        if(typeof $('[name="crc_equipment_profit_'+loop+'"]').val() !='undefined')
        {
            crc_profit_fee += +$('[name="crc_equipment_profit_'+loop+'"]').val();
        }
        else {
            break;
        }
    }

    sum_profit += +crc_profit_fee;

	jQuery('[name="eqprofitmisc[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });

    var count = 0;
    jQuery('[name="eqprofitmargin[]"]').each(function () {
        sum_profit_margin += +$(this).val() || 0;
		if(sum_profit_margin != 0)
			count++;
    });

	jQuery('[name="eqmarginmisc[]"]').each(function () {
        sum_profit_margin += +$(this).val() || 0;
        misc_profit_margin += +$(this).val() || 0;
        if(misc_profit_margin != 0)
        count++;
    });

    for(var loop = 0; loop < 500; loop++) {
        if(typeof $('[name="crc_equipment_margin_'+loop+'"]').val() !='undefined')
        {
            var temp_margin = 0;
            temp_margin = $('[name="crc_equipment_margin_'+loop+'"]').val();
            if(temp_margin != 0 && temp_margin != '') {
                crc_margin_fee += +temp_margin;
                count++;
            }
        }
        else {
            break;
        }
    }

    sum_profit_margin += crc_margin_fee;

    per_profit_margin = sum_profit_margin / count;

    jQuery('#equipment_profit').val(round2Fixed(sum_profit));
    jQuery('#equipment_profit_margin').val(round2Fixed(per_profit_margin));
}


function countRCTotalEqu(sel) {
	var stage = sel.value;
	var typeId = sel.id;

	var arr = typeId.split('_');
    var del = (jQuery('#crc_equipment_custprice_'+arr[3]).val() * jQuery('#crc_equipment_qty_'+arr[3]).val());
    jQuery('#crc_equipment_total_'+arr[3]).val(round2Fixed(del));

    var sum_fee = 0;
    var crc_sum_fee = 0;
    $('[name="eqestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    for(var loop = 0; loop < 500; loop++) {
        if(typeof $('[name="crc_equipment_total_'+loop+'"]').val() !='undefined')
        {
            crc_sum_fee += +$('[name="crc_equipment_total_'+loop+'"]').val();
        }
        else {
            break;
        }
    }

    sum_fee += +crc_sum_fee;

    $('[name="equ_total"]').val(round2Fixed(sum_fee));

}
function countMiscEquipment(txb)
{

	var get_id = txb.id;

	var split_id = get_id.split('_');
	if(split_id[0] == 'eqestimatepricemisc') {
		var estqty = $('#eqqtymisc_'+split_id[1]).val();
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('eqqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('eqtotalmisc_'+split_id[1]).value = parseFloat($('#eqestimatepricemisc_'+split_id[1]).val() * estqty);
	}

	if(split_id[0] == 'eqqtymisc') {
		var estqty = txb.value;
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('eqqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('eqtotalmisc_'+split_id[1]).value = parseFloat($('#eqestimatepricemisc_'+split_id[1]).val() * estqty);
	}

    var sum_fee = 0;
    /*$('[name="eqestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_equipment_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });*/
    //$('[name="eqtotalmisc[]"]').each(function () {
        sum_fee += +document.getElementById('eqtotalmisc_'+split_id[1]).value || 0;
    //});

    sum_fee += +$('[name="equ_total"]').val();
    $('[name="equ_total"]').val(round2Fixed(sum_fee));
    $('[name="equipment_summary"]').val(round2Fixed(sum_fee));

    var equ_budget = $('[name="equ_budget"]').val();
    if(equ_budget >= sum_fee) {
        $('[name="equ_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="equ_total"]').css("background-color", "#ff9999"); // Green
    }
}
function changeProfitEquipmentPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    jQuery('#eqestimateqty_' + split_id[1]).val(1);
    qty = 1;
    pcost = 'eqc_' + split_id[1];
    pestimateid = 'eqestimateprice_' + split_id[1];
    ptotal = 'eqtotalmisc_' + split_id[1];
    profitid = 'eqprofit_' + split_id[1];
    marginid = 'eqprofitmargin_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'eqprofit')
        {
            estimateValue = parseInt(profit.value) + parseInt(jQuery('#'+pcost).val());
            var deltaper = (profit.value / (estimateValue * qty)) * 100;
            jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
            jQuery('#'+ptotal).val(estimateValue.toFixed(2));
            jQuery('#'+marginid).val(deltaper.toFixed(2));
        }

        if(split_id[0] == 'eqprofitmargin')
        {
            estimateValue = (parseInt(jQuery('#' + pcost).val()) * 100 / (100 - parseInt(profit.value)));
            var deltavalue = estimateValue - parseInt(jQuery('#'+pcost).val());
            jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
            jQuery('#'+ptotal).val(estimateValue.toFixed(2));
            jQuery('#'+profitid).val(deltavalue.toFixed(2));
        }
    }

    changeEquipmentTotal();
}

function changeProfitEquipmentRCPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    jQuery('#crc_equipment_qty_' + split_id[3]).val(1);
    qty = 1;
    pcost = 'crc_equipment_cost_' + split_id[3];
    pestimateid = 'crc_equipment_custprice_' + split_id[3];
    ptotal = 'crc_equipment_total_' + split_id[3];
    profitid = 'crc_equipment_profit_' + split_id[3];
    marginid = 'crc_equipment_margin_' + split_id[3];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[2] == 'profit')
        {
            estimateValue = parseInt(profit.value) + parseInt(jQuery('#'+pcost).val());
            var deltaper = (profit.value / (estimateValue * qty)) * 100;
            jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
            jQuery('#'+ptotal).val(estimateValue.toFixed(2));
            jQuery('#'+marginid).val(deltaper.toFixed(2));

        }

        if(split_id[2] == 'margin')
        {

            estimateValue = (parseInt(jQuery('#' + pcost).val()) * 100 / (100 - parseInt(profit.value)));
            var deltavalue = estimateValue - parseInt(jQuery('#'+pcost).val());
            jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
            jQuery('#'+ptotal).val(estimateValue.toFixed(2));
            jQuery('#'+profitid).val(deltavalue.toFixed(2));
        }
    }

    changeEquipmentTotal();
}

function changeProfitEquipmentMiscPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    jQuery('#eqqtymisc_' + split_id[1]).val(1);
    qty = 1;
    pcost = 'eqcostmisc_' + split_id[1];
    pestimateid = 'eqestimatepricemisc_' + split_id[1];
    ptotal = 'eqtotalmisc_' + split_id[1];
    profitid = 'eqprofitmisc_' + split_id[1];
    marginid = 'eqmarginmisc_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'eqprofitmisc')
        {
            estimateValue = parseInt(profit.value) + parseInt(jQuery('#'+pcost).val());
            var deltaper = (profit.value / (estimateValue * qty)) * 100;
            jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
            jQuery('#'+ptotal).val(estimateValue.toFixed(2));
            jQuery('#'+marginid).val(deltaper.toFixed(2));

        }

        if(split_id[0] == 'eqmarginmisc')
        {

            estimateValue = (parseInt(jQuery('#' + pcost).val()) * 100 / (100 - parseInt(profit.value)));
            var deltavalue = estimateValue - parseInt(jQuery('#'+pcost).val());
            jQuery('#'+pestimateid).val(estimateValue.toFixed(2));
            jQuery('#'+ptotal).val(estimateValue.toFixed(2));
            jQuery('#'+profitid).val(deltavalue.toFixed(2));
        }
    }

    changeEquipmentTotal();
}
</script>
<?php
$get_field_config_equipment = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(equipment_dashboard SEPARATOR ',') AS equipment FROM field_config_equipment"));
$field_config_equipment = ','.$get_field_config_equipment['equipment'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <?php if (strpos($base_field_config, ','."Equipment Category".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Category</label>
            <?php } ?>
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
            <?php if (strpos($field_config_equipment, ','."Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Cost</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
            <label class="col-sm-1 text-center">Bid Price</label>
            <label class="col-sm-1 text-center">Quantity</label>
            <label class="col-sm-1 text-center">UOM</label>
            <label class="col-sm-1 text-center">Total</label>
            <label class="col-sm-1 text-center">$ Profit</label>
            <label class="col-sm-1 text-center">% Margin</label>
        </div>

       <?php
        $get_equipment_field = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_equipment'),'**#**');
                $get_equipment_field  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_equipment'),'**#**');
                $get_equipment_field  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_equipment'),'**#**');
                $get_equipment_field  .= '**'.$each_item;
            }
        }

        if(!empty($_GET['estimateid'])) {
            $equipment = $get_contact['equipment'];
            $each_data = explode('**',$equipment);
            foreach($each_data as $id_all) {
                if($id_all != '') {
                    $data_all = explode('#',$id_all);
                    $get_equipment_field .= '**'.$data_all[0].'#'.$data_all[2].'#'.$data_all[1].'#'.$data_all[3].'#'.$data_all[4].'#'.$data_all[5];
                }
            }
        }
        $final_total_equipment = 0;
        $final_total_equipment_profit = 0;
        $final_total_equipment_margin = 0;
        ?>

        <?php if(!empty($get_equipment_field)) {
            $each_assign_equipment = explode('**',$get_equipment_field);
            $total_count = mb_substr_count($get_equipment_field,'**');
            $id_loop = 500;
            for($equipment_loop=0; $equipment_loop<=$total_count; $equipment_loop++) {

                $each_item = explode('#',$each_assign_equipment[$equipment_loop]);
                $equipmentid = '';
                $qty = '';
                $est = '';
                $unit = '';
                if(isset($each_item[0])) {
                    $equipmentid = $each_item[0];
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
                if(isset($each_item[4])) {
                    $profit = $each_item[4];
                }
                if(isset($each_item[5])) {
                    $margin = $each_item[5];
                }
                $total = $qty*$est;
                $final_total_equipment += $total;

                if($equipmentid != '') {

                    $equipment = explode('**', $get_rc['equipment']);
                    $rc_price = 0;
                    foreach($equipment as $pp){
                        if (strpos('#'.$pp, '#'.$equipmentid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>
            <div class="form-group clearfix" id="<?php echo 'equipment_'.$id_loop; ?>" >
                <?php if (strpos($base_field_config, ','."Equipment Category".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Category..." id="<?php echo 'eqequipmentcat_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid equip_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM equipment order by category");
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
                <?php } ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Number..." id="<?php echo 'eqequipmentun_'.$id_loop; ?>" name="equipmentid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT equipmentid, unit_number, serial_number  FROM equipment order by unit_number");
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
                <div class="col-sm-1" >
                    <input name="eqsmr[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'semi_monthly_rate');?>" id="<?php echo 'eqsmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqdr[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'daily_rate');?>" id="<?php echo 'eqdr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqhr[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'hr_rate_work');?>" id="<?php echo 'eqhr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqhrt[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'hr_rate_travel');?>" id="<?php echo 'eqhrt_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqfdc[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'field_day_cost');?>" id="<?php echo 'eqfdc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqfdb[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'field_day_billable');?>" id="<?php echo 'eqfdb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Cost".',') !== FALSE) { ?>
                <div class="col-sm-1">
                    <input name="eqc[]" value="<?php echo get_equipment_field($dbc, $equipmentid, 'cost');?>" id="<?php echo 'eqc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="eqfinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'eqfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimateprice[]" value="<?php echo $est; ?>" id="<?php echo 'eqestimateprice_'.$id_loop; ?>" onchange="countEquipment(this); fillmarginvalueEquipment(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimateqty[]" id="<?php echo 'eqestimateqty_'.$id_loop; ?>" onchange="countEquipment(this); qtychangevalueEquipment(this);" value="<?php echo $qty; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimateunit[]" id="<?php echo 'eqestimateunit_'.$id_loop; ?>" value="<?php echo $unit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'eqestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" >
                    <input name="eqprofit[]" id="<?php echo 'eqprofit_'.$id_loop; ?>" onchange="changeProfitEquipmentPrice(this);" value="<?php echo $profit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqprofitmargin[]" id="<?php echo 'eqprofitmargin_'.$id_loop; ?>" onchange="changeProfitEquipmentPrice();" value="<?php echo $margin; ?>" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'equipment_','eqequipmentun_'); return false;" id="<?php echo 'deleteequipment_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_eq clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="equipment_0">
                <?php if (strpos($base_field_config, ','."Equipment Category".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Category..." id="eqequipmentcat_0" class="chosen-select-deselect form-control equipmentid equip_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM equipment order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Number..." id="eqequipmentun_0" name="equipmentid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT equipmentid, unit_number, serial_number  FROM equipment order by unit_number");
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
                <div class="col-sm-1" >
                    <input name="eqsmr[]" id="eqsmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqdr[]" id="eqdr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqhr[]" id="eqhr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqhrt[]" id="eqhrt_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqfdc[]" id="eqfdc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_equipment, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="eqfdb[]" id="eqfdb_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <?php if (strpos($field_config_equipment, ','."Cost".',') !== FALSE) { ?>
                <div class="col-sm-1">
                    <input name="eqc[]" id="eqc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" >
                    <input name="eqfinalprice[]" readonly id="eqfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimateprice[]" id='eqestimateprice_0' onchange="countEquipment(this); fillmarginvalueEquipment(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimateqty[]" id='eqestimateqty_0' onchange="countEquipment(this); qtychangevalueEquipment(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimateunit[]" id='eqestimateunit_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqestimatetotal[]" id='eqestimatetotal_0' onchange="changeEquipmentTotal();" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" >
                    <input name="eqprofit[]" id='eqprofit_0' onchange="changeProfitEquipmentPrice(this);"  type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eqprofitmargin[]" id='eqprofitmargin_0'  onchange="changeProfitEquipmentPrice(this);"  type="text" class="form-control" />
                </div>

                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'equipment_','eqequipmentun_'); return false;" id="deleteequipment_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_eq"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_eq" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-left:5px">
			<h3>Misc Items</h3>
			<div class="form-group clearfix">
				<label class="col-sm-1 text-center">Type</label>
				<label class="col-sm-2 text-center">Heading</label>
				<label class="col-sm-1 text-center">Description</label>
				<label class="col-sm-1 text-center">UOM</label>
				<label class="col-sm-1 text-center">Cost</label>
				<label class="col-sm-1 text-center">Bid Price</label>
				<label class="col-sm-1 text-center">Quantity</label>
				<label class="col-sm-1 text-center">Total</label>
				<label class="col-sm-1 text-center">$ Profit</label>
				<label class="col-sm-1 text-center">% Margin</label>
			</div>
			<div class="additional_eq_misc clearfix">
				<div class="clearfix"></div>

				<div class="form-group clearfix" id="equipmentmisc_0">
					<div class="col-sm-1">
						<input name="eqtype_misc[]" id="eqtype_misc_0" type="text" class="form-control" />
					</div>

					<div class="col-sm-2">
						<input name="eqheadmisc[]" id="eqheadmisc_0" type="text" class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="eqdisc_misc[]" id="eqdisc_misc_0" type="text" class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="equom_misc[]" id="equom_misc_0" type="text" class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="eqcostmisc[]" id="eqcostmisc_0" type="text" class="form-control" />
					</div>


					<div class="col-sm-1">
						<input name="eqestimatepricemisc[]" id="eqestimatepricemisc_0" type="text" class="form-control" onchange="countMiscEquipment(this); fillmarginmiscvalueEquipment(this);" />
					</div>

					<div class="col-sm-1">
						<input name="eqqtymisc[]" id="eqqtymisc_0" type="text" class="form-control" onchange="countMiscEquipment(this); qtychangemiscvalueEquipment(this);" />
					</div>

					<div class="col-sm-1">
						<input name="eqtotalmisc[]" id="eqtotalmisc_0" type="text" class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="eqprofitmisc[]" id="eqprofitmisc_0" type="text" onchange="changeProfitEquipmentMiscPrice(this);" class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="eqmarginmisc[]" id="eqmarginmisc_0" type="text" onchange="changeProfitEquipmentMiscPrice(this);" class="form-control" />
					</div>

					<div class="col-sm-1" >
						<a href="#" onclick="deleteEstimate(this,'equipmentmisc_','eqheadmisc_'); return false;" id="deleteequipmentmisc_0" class="btn brand-btn">Delete</a>
					</div>
				</div>
			</div>

			<div id="add_here_new_eq_misc"></div>

			<div class="form-group triple-gapped clearfix">
				<div class="col-sm-offset-4 col-sm-8">
					<button id="add_row_eq_misc" class="btn brand-btn pull-left">Add Row</button>
				</div>
			</div>

            <br>
            <?php
            $query_misc_rc = mysqli_query($dbc,"SELECT * FROM bid_misc WHERE accordion='Equipment' AND estimateid=" . $_GET['estimateid']);
            //exit;

            $misc_num_rows = mysqli_num_rows($query_misc_rc);
            if($misc_num_rows > 0) { ?>
                <div class="form-group clearfix products_misc_heading">
                    <label class="col-sm-2 text-center">Type</label>
                    <label class="col-sm-2 text-center">Heading</label>
                    <label class="col-sm-1 text-center">Description</label>
                    <label class="col-sm-1 text-center">UOM</label>
                    <label class="col-sm-1 text-center">Cost</label>
                    <label class="col-sm-1 text-center">Bid Price</label>
                    <label class="col-sm-1 text-center">Quantity</label>
                    <label class="col-sm-1 text-center">Total</label>
                    <label class="col-sm-1 text-center">$ Profit</label>
                    <label class="col-sm-1 text-center">% Margin</label>
                </div>
                <?php
            }

            $misc_rc = 0;
            while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) { ?>
                <div class="clearfix"></div>

				<div class="form-group clearfix">
					<div class="col-sm-2">
						<input name="eqtype_misc_display[]" id="eqtype_misc" value="<?php echo $misc_row_rc['type'] ?>" readonly type="text" class="form-control" />
					</div>

					<div class="col-sm-2">
						<input name="eqheadmisc_display[]" id="eqheadmisc" value="<?php echo $misc_row_rc['heading'] ?>" type="text" readonly class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="eqdisc_misc_display[]" id="eqdisc_misc" type="text" value="<?php echo $misc_row_rc['description'] ?>" readonly class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="equom_misc_display[]" id="equom_misc" type="text" value="<?php echo $misc_row_rc['uom'] ?>" readonly class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="eqcostmisc_display[]" id="eqcostmisc" type="text" value="<?php echo $misc_row_rc['cost'] ?>" readonly class="form-control" />
					</div>


					<div class="col-sm-1">
						<input name="eqestimatepricemisc_display[]" id="eqestimatepricemisc" readonly value="<?php echo $misc_row_rc['estimate_price'] ?>" type="text" class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="eqqtymisc_display[]" id="eqqtymisc" type="text" readonly class="form-control" value="<?php echo $misc_row_rc['qty'] ?>" onchange="countMiscEquipment(this);" />
					</div>

					<div class="col-sm-1">
						<input name="eqtotalmisc_display[]" id="eqtotalmisc" value="<?php echo $misc_row_rc['total'] ?>" readonly type="text" class="form-control" />
					</div>

                    <div class="col-sm-1">
						<input name="eqprofitmisc_display[]" id="eqprofitmisc" value="<?php echo $misc_row_rc['profit'] ?>" readonly type="text" class="form-control" />
					</div>

                    <div class="col-sm-1">
						<input name="eqmarginmisc_display[]" id="eqmarginmisc" value="<?php echo $misc_row_rc['margin'] ?>" readonly type="text" class="form-control" />
					</div>
				</div>
            <?php
                $misc_rc++;
                $final_total_misc_equipment += $misc_row_rc['total'];
            }
            ?>
		</div>
    </div>
</div>

<?php
if(!empty($_GET['estimateid'])) {

    $querxy = mysqli_query ($dbc, "SELECT DISTINCT(rate_card_types) FROM company_rate_card WHERE (rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') AND tile_name='Equipment'");
    while($row = mysqli_fetch_array ($querxy)) {
        $no_space_rate_card_types = str_replace(' ', '', $row['rate_card_types']);
        $no_space_rate_card_types = str_replace('/', '', $no_space_rate_card_types);
        ?>
        <a id="<?php echo $no_space_rate_card_types; ?>" class="btn brand-btn order_list_equipment mobile-100" ><?php echo $row['rate_card_types']; ?></a>
    <?php }

    $query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE (rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') AND tile_name='Equipment'");

    $num_rows = mysqli_num_rows($query_rc);
    if($num_rows > 0) { ?>
        <div class="form-group clearfix equipment_heading">
            <label class="col-sm-2 text-center">Type</label>
            <label class="col-sm-2 text-center">Heading</label>
            <label class="col-sm-1 text-center">Description</label>
            <label class="col-sm-1 text-center">UOM</label>
            <label class="col-sm-1 text-center">Cost</label>
            <label class="col-sm-1 text-center">Bid Price</label>
            <label class="col-sm-1 text-center">Quantity</label>
            <label class="col-sm-1 text-center">Total</label>
            <label class="col-sm-1 text-center">$ Profit</label>
            <label class="col-sm-1 text-center">% Margin</label>
        </div>
        <?php
    }
    $rc = 0;
    while($row_rc = mysqli_fetch_array($query_rc)) {

        $companyrcid = $row_rc['companyrcid'];

        $estimate_company_rate_card = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM bid_company_rate_card WHERE companyrcid='$companyrcid' AND estimateid='$estimateid'"));
        $no_space_rate_card_types = str_replace(' ', '', $row_rc['rate_card_types']);
        $no_space_rate_card_types = str_replace('/', '', $no_space_rate_card_types);
        ?>

        <div class="form-group clearfix all_equipment <?php echo $no_space_rate_card_types; ?> rc_est_equ_<?php echo $rc; ?>" width="100%">
            <input type="hidden" name="crc_equipment_companyrcid_<?php echo $rc; ?>" value="<?php echo $row_rc['companyrcid']; ?>" />

            <div class="col-sm-2">
                <input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_equipment_type_<?php echo $rc; ?>" type="text" class="form-control" />
            </div>
            <div class="col-sm-2">
                <input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_equipment_heading_<?php echo $rc; ?>" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_equipment_description_<?php echo $rc; ?>" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_equipment_uom_<?php echo $rc; ?>" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $row_rc['cost']; ?>" readonly="" id="crc_equipment_cost_<?php echo $rc; ?>" name="crc_equipment_cost_<?php echo $rc; ?>" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="fillmargincrcequipmentvalue(this); countRCTotalEqu(this)" name="crc_equipment_cust_price_<?php echo $rc; ?>" type="text" id="crc_equipment_custprice_<?php echo $rc;?>" class="form-control" />
            </div>
            <div class="col-sm-1">
                <input name="crc_equipment_qty_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="qtychangecrcvalueEquipment(this); countRCTotalEqu(this)" id="crc_equipment_qty_<?php echo $rc;?>" class="form-control crc_equipment_qty" />
            </div>
            <div class="col-sm-1">
                <input name="crc_equipment_total_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_equipment_total_<?php echo $rc;?>" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                    <input name="crc_equipment_profit_<?php echo $rc; ?>" onchange="changeProfitEquipmentRCPrice(this)" value= "<?php echo $estimate_company_rate_card['profit']; ?>"  id="crc_equipment_profit_<?php echo $rc;?>" type="text" class="form-control" />
            </div>
            <div class="col-sm-1">
                    <input name="crc_equipment_margin_<?php echo $rc; ?>" onchange="changeProfitEquipmentRCPrice(this)" value= "<?php echo $estimate_company_rate_card['margin']; ?>"  id="crc_equipment_margin_<?php echo $rc;?>" type="text" class="form-control" />
            </div>
        </div>

    <?php
        $rc++;
        $final_total_equipment += $estimate_company_rate_card['rc_total'];
        $final_total_equipment_profit += $estimate_company_rate_card['profit'];
        $final_total_equipment_margin += $estimate_company_rate_card['margin'];
    }
}
?>
<input type="hidden" name="total_rc_equipment" value="<?php echo $rc; ?>" />

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
    <div class="col-sm-8">
      <input name="equipment_profit" id="equipment_profit" value="<?php echo $final_total_equipment_profit; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
    <div class="col-sm-8">
      <input name="equipment_profit_margin" id="equipment_profit_margin" value="<?php echo $final_total_equipment_margin; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="equ_budget" value="<?php echo $budget_price[10]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="equ_total" value="<?php echo $final_total_equipment + $final_total_misc_equipment;?>" type="text" class="form-control">
    </div>
</div>
