<script>
$(document).ready(function() {
    $('.all_inventory').hide();
    $('.inventory_heading').hide();

	$('.order_list_inventory').on( 'click', function () {
        var pro_type = $(this).attr("id");

        $('.all_inventory').hide();
        $('.'+pro_type).show();
        $('.inventory_heading').show();

		$('.order_list_inventory').removeClass('active_tab');
        $(this).addClass('active_tab');
    });
	
	if($('.order_list_inventory').length == 1 && '<?php echo $load_tab; ?>' != 'Master') {
		$('.order_list_inventory').click();
	}

	//Inventory
    var add_new_in = 1;
    $('#deleteinventory_0').hide();
    $('#add_row_in').on( 'click', function () {
        $('#deleteinventory_0').show();
        var clone = $('.additional_in').clone();
        clone.find('.form-control').val('');

        clone.find('#ininventorycat_0').attr('id', 'ininventorycat_'+add_new_in);
		clone.find('#ininventorycode_0').attr('id', 'ininventorycode_'+add_new_in);
		clone.find('#ininventorypn_0').attr('id', 'ininventorypn_'+add_new_in);
		clone.find('#ininventoryname_0').attr('id', 'ininventoryname_'+add_new_in);
		clone.find('#ininventorypart_0').attr('id', 'ininventorypart_'+add_new_in);
        clone.find('#inrp_0').attr('id', 'inrp_'+add_new_in);
        clone.find('#inap_0').attr('id', 'inap_'+add_new_in);
        clone.find('#inwp_0').attr('id', 'inwp_'+add_new_in);
        clone.find('#incomp_0').attr('id', 'incomp_'+add_new_in);
        clone.find('#incp_0').attr('id', 'incp_'+add_new_in);
        clone.find('#inmsrp_0').attr('id', 'inmsrp_'+add_new_in);
		clone.find('#infinalprice_0').attr('id', 'infinalprice_'+add_new_in);
		clone.find('#inestimateprice_0').attr('id', 'inestimateprice_'+add_new_in);
		clone.find('#inestimateqty_0').attr('id', 'inestimateqty_'+add_new_in);
		clone.find('#inestimateunit_0').attr('id', 'inestimateunit_'+add_new_in);
		clone.find('#inestimatetotal_0').attr('id', 'inestimatetotal_'+add_new_in);

        clone.find('#inc_0').attr('id', 'inc_'+add_new_in);
        clone.find('#inprofit_0').attr('id', 'inprofit_'+add_new_in);
        clone.find('#inprofitmargin_0').attr('id', 'inprofitmargin_'+add_new_in);

        clone.find('#inventory_0').attr('id', 'inventory_'+add_new_in);
        clone.find('#deleteinventory_0').attr('id', 'deleteinventory_'+add_new_in);
        $('#deleteinventory_0').hide();

        clone.removeClass("additional_in");
        $('#add_here_new_in').append(clone);

        resetChosen($("#ininventorycat_"+add_new_in));
        resetChosen($("#ininventorycode_"+add_new_in));
        resetChosen($("#ininventorypn_"+add_new_in));
        resetChosen($("#ininventoryname_"+add_new_in));
		resetChosen($("#ininventorypart_"+add_new_in));

        add_new_in++;

        return false;
    });
	
	var add_new_in_misc = 1;
	$('#deleteinventorymisc_0').hide();
	$('#add_row_in_misc').on( 'click', function () {

		$('#deleteinventorymisc_0').show();
        var clone_misc = $('.additional_in_misc').clone();
        clone_misc.find('.form-control').val('');
		clone_misc.find('#inid_misc_0').attr('id', 'inid_misc_'+add_new_in_misc);
        clone_misc.find('#intype_misc_0').attr('id', 'intype_misc_'+add_new_in_misc);
		clone_misc.find('#indisc_misc_0').attr('id', 'indisc_misc_'+add_new_in_misc);
		clone_misc.find('#inuom_misc_0').attr('id', 'inuom_misc_'+add_new_in_misc);
		clone_misc.find('#inheadmisc_0').attr('id', 'inheadmisc_'+add_new_in_misc);
		clone_misc.find('#incostmisc_0').attr('id', 'incostmisc_'+add_new_in_misc);
		clone_misc.find('#inqtymisc_0').attr('id', 'inqtymisc_'+add_new_in_misc);
		clone_misc.find('#intotalmisc_0').attr('id', 'intotalmisc_'+add_new_in_misc);
		clone_misc.find('#inestimatepricemisc_0').attr('id', 'inestimatepricemisc_'+add_new_in_misc);
		clone_misc.find('#inprofitmisc_0').attr('id', 'inprofitmisc_'+add_new_in_misc);
		clone_misc.find('#inmarginmisc_0').attr('id', 'inmarginmisc_'+add_new_in_misc);
        clone_misc.find('#inventorymisc_0').attr('id', 'inventorymisc_'+add_new_in_misc);
        clone_misc.find('#deleteinventorymisc_0').attr('id', 'deleteinventorymisc_'+add_new_in_misc);
        $('#deleteinventorymisc_0').hide();

        clone_misc.removeClass("additional_in_misc");

        $('#add_here_new_in_misc').append(clone_misc);

        add_new_in_misc++;

        return false;
    });
	changeInventoryTotal();
});

//Inventory
function selectInventoryCategory(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=in_cat_config&value="+stage+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#ininventoryname_"+arr[1]).html(response);
			$("#ininventoryname_"+arr[1]).trigger("change.select2");
		}
	});
	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=in_cat_config_partno&value="+stage+"&ratecardid="+ratecardid,
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
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=in_code_part_name_config&value="+stage+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*FFM*');
            $("#inrp_"+arr[1]).val(result[0]);
            $("#inap_"+arr[1]).val(result[1]);
            $("#inwp_"+arr[1]).val(result[2]);
            $("#incomp_"+arr[1]).val(result[3]);
            $("#incp_"+arr[1]).val(result[4]);
            $("#inmsrp_"+arr[1]).val(result[5]);
			$("#infinalprice_"+arr[1]).val(result[6]);
            $("#inc_"+arr[1]).val(result[7]);
		}
	});
	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=in_code_part_name_config_number&value="+stage+"&ratecardid="+ratecardid,
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
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=in_code_part_no_config&value="+stage+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*FFM*');
            $("#inrp_"+arr[1]).val(result[0]);
            $("#inap_"+arr[1]).val(result[1]);
            $("#inwp_"+arr[1]).val(result[2]);
            $("#incomp_"+arr[1]).val(result[3]);
            $("#incp_"+arr[1]).val(result[4]);
            $("#inmsrp_"+arr[1]).val(result[5]);
			$("#infinalprice_"+arr[1]).val(result[6]);
			$("#inc_"+arr[1]).val(result[7]);
		}
	});
	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=in_code_part_no_config_name&value="+stage+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#ininventoryname_"+arr[1]).html(response);
			$("#ininventoryname_"+arr[1]).trigger("change.select2");
		}
	});
}

function fillmargincrcinventoryvalue(est) {
	var idarray = est.id.split("_");
    var profitid = 'crc_inventory_profit_' + idarray[3];
    var profitmarginid = 'crc_inventory_margin_' + idarray[3];
    var pcid = 'crc_inventory_cost_' + idarray[3];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#crc_inventory_qty_' + idarray[3]).val();
    if(qty == '' || qty == null) {
        jQuery('#crc_inventory_qty_' + idarray[3]).val(1);
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
            jQuery('#'+profitid).val(round2Fixed(deltavalue));
            jQuery('#'+profitmarginid).val(round2Fixed(deltaper));
        }
    }
    
    changeInventoryTotal();
}

function countInventory(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');

        var lbqty = $('#inestimateqty_'+split_id[1]).val();
        if(lbqty == null || lbqty == '') {
            lbqty = 1;
        }

        document.getElementById('inestimatetotal_'+split_id[1]).value = round2Fixed(parseFloat($('#inestimateprice_'+split_id[1]).val() * lbqty));
    }

    var sum_fee = 0;
    $('[name="inestimatetotal[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
    $('[name="intotalmisc[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
    $('[name="intotalmisc_display[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
	
    $('[name="inventory_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="inventory_summary"]').val('$'+round2Fixed(sum_fee)).change();

    var inventory_budget = $('[name="inventory_budget"]').val();
    if(inventory_budget >= sum_fee) {
        $('[name="inventory_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="inventory_total"]').css("background-color", "#ff9999"); // Green
    }
}

function fillmarginvalueInventory(est) {
    var idarray = est.id.split("_");
    var profitid = 'inprofit_' + idarray[1];
    var profitmarginid = 'inprofitmargin_' + idarray[1];
    var pcid = 'inc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#inestimateqty_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#inestimateqty_' + idarray[1]).val(1);
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
            jQuery('#'+profitid).val(round2Fixed(deltavalue));
            jQuery('#'+profitmarginid).val(round2Fixed(deltaper));
        }
    }

    changeInventoryTotal();
}

function fillmarginmiscvalueInventory(est) {
	var idarray = est.id.split("_");
    var profitid = 'inprofitmisc_' + idarray[1];
    var profitmarginid = 'inmarginmisc_' + idarray[1];
    var pcid = 'incostmisc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#inqtymisc_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#inqtymisc_' + idarray[1]).val(1);
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
            jQuery('#'+profitid).val(round2Fixed(deltavalue));
            jQuery('#'+profitmarginid).val(round2Fixed(deltaper));
        }
    }
	changeInventoryTotal();
}

function qtychangevalueInventory(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'inprofit_' + idarray[1];
    var profitmarginid = 'inprofitmargin_' + idarray[1];
    var pestimateid = 'inestimateprice_' + idarray[1];
    var pcid = 'inc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
    changeInventoryTotal();
}

function qtychangemiscvalueInventory(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'inprofitmisc_' + idarray[1];
    var profitmarginid = 'inmarginmisc_' + idarray[1];
    var pestimateid = 'inestimatepricemisc_' + idarray[1];
    var pcid = 'incostmisc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
	changeInventoryTotal();
}

function qtychangecrcvalueInventory(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'crc_inventory_profit_' + idarray[3];
    var profitmarginid = 'crc_inventory_margin_' + idarray[3];
    var pestimateid = 'crc_inventory_custprice_' + idarray[3];
    var pcid = 'crc_inventory_cost_' + idarray[3];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
	changeInventoryTotal();
}

function changeInventoryTotal() {
    var sum_profit = 0;
	var sum_total = 0;
	var sum_cost = 0;
	$('[name="intotalmisc[]"]').each(function(key) {
		qty = +$($('[name="inqtymisc[]"]')[key]).val() || 0
		sum_cost += (+$($('[name="incostmisc[]"]')[key]).val() || 0) * qty;
		sum_total += +$(this).val() || 0;
	});
	$('[name="intotalmisc_display[]"]').each(function(key) {
		qty = +$($('[name="inqtymisc_display[]"]')[key]).val() || 0
		sum_cost += (+$($('[name="incostmisc_display[]"]')[key]).val() || 0) * qty;
		sum_total += +$(this).val() || 0;
	});
	$('[name="inestimatetotal[]"]').each(function(key) {
		qty = +$($('[name="inestimateqty[]"]')[key]).val() || 0
		sum_cost += (+$($('[name="inc[]"]')[key]).val() || 0) * qty;
		sum_total += +$(this).val() || 0;
	});
	$('[name^=crc_inventory_total_]').each(function(key) {
		qty = +$($('[name^=crc_inventory_qty_]')[key]).val() || 0
		sum_cost += (+$($('[name^=crc_inventory_cost_]')[key]).val() || 0) * qty;
		sum_total += +$(this).val() || 0;
	});
    
	sum_profit = sum_total - sum_cost;
	per_profit_margin = (sum_profit / sum_total) * 100;
	if(isNaN(per_profit_margin)) {
		per_profit_margin = 'N/A';
	}
	else {
		per_profit_margin = round2Fixed(per_profit_margin)+'%';
	}

    jQuery('#inventory_profit').val('$'+round2Fixed(sum_profit));
    jQuery('#inventory_profit_margin').val(round2Fixed(per_profit_margin));
    jQuery('#inventory_cost').val('$'+round2Fixed(sum_total - sum_profit));
    jQuery('[name=inventory_total]').val('$'+round2Fixed(sum_total));
    jQuery('[name=inventory_summary_profit]').val('$'+round2Fixed(sum_profit));
    jQuery('[name=inventory_summary_margin]').val(per_profit_margin);
    jQuery('[name=inventory_summary_cost]').val('$'+round2Fixed(sum_total - sum_profit));
    jQuery('[name=inventory_summary]').val('$'+round2Fixed(sum_total)).change();
}

function countRCTotalInventory(sel) {
	var stage = sel.value;
	var typeId = sel.id;

	var arr = typeId.split('_');
    var del = (jQuery('#crc_inventory_custprice_'+arr[3]).val() * jQuery('#crc_inventory_qty_'+arr[3]).val());

    jQuery('#crc_inventory_total_'+arr[3]).val(round2Fixed(del));
	changeInventoryTotal();

    /*var sum_fee = 0;
    var crc_sum_fee = 0;
    $('[name="inestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    for(var loop = 0; loop < 500; loop++) {
        if(typeof $('[name="crc_inventory_total_'+loop+'"]').val() !='undefined')
        {
            crc_sum_fee += +$('[name="crc_inventory_total_'+loop+'"]').val();
        }
        else {
            break;
        }
    }

    sum_fee += +crc_sum_fee;

    $('[name="inventory_total"]').val(round2Fixed(sum_fee));*/

}
function countMiscInventory(txb)
{

	var get_id = txb.id;

	var split_id = get_id.split('_');
	if(split_id[0] == 'inestimatepricemisc') {
		var estqty = $('#inqtymisc_'+split_id[1]).val();
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('inqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('intotalmisc_'+split_id[1]).value = parseFloat($('#inestimatepricemisc_'+split_id[1]).val() * estqty);
	}
	
	if(split_id[0] == 'inqtymisc') {
		var estqty = txb.value;
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('inqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('intotalmisc_'+split_id[1]).value = parseFloat($('#inestimatepricemisc_'+split_id[1]).val() * estqty);
	}
	
    var sum_fee = 0;
	//$('[name="intotalmisc[]"]').each(function () {
    sum_fee += +document.getElementById('intotalmisc_'+split_id[1]).value || 0;
    //});

    sum_fee += +$('[name="inventory_total"]').val();
    $('[name="inventory_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="inventory_summary"]').val('$'+round2Fixed(sum_fee)).change();

    var inventory_budget = $('[name="inventory_budget"]').val();
    if(inventory_budget >= sum_fee) {
        $('[name="inventory_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="inventory_total"]').css("background-color", "#ff9999"); // Green
    }
}
function changeProfitInventoryPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    qty = jQuery('#inestimateqty_' + split_id[1]).val();
    pcost = 'inc_' + split_id[1];
    pestimateid = 'inestimateprice_' + split_id[1];
    ptotal = 'intotalmisc_' + split_id[1];
    profitid = 'inprofit_' + split_id[1];
    marginid = 'inprofitmargin_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'inprofit')
        {
			estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
			estimateTotal = estimateValue * qty;
			estimateMargin = profit.value / (estimateValue * qty) * 100;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+marginid).val(round2Fixed(estimateMargin));
        }
        
        if(split_id[0] == 'inprofitmargin')
        {
			estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
			estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
			estimateTotal = estimateValue * qty;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+profitid).val(round2Fixed(estimateProfit));
        }
    }
    
    changeInventoryTotal();
}

function changeProfitInventoryRCPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    qty = jQuery('#crc_inventory_qty_' + split_id[3]).val();
    pcost = 'crc_inventory_cost_' + split_id[3];
    pestimateid = 'crc_inventory_custprice_' + split_id[3];
    ptotal = 'crc_inventory_total_' + split_id[3];
    profitid = 'crc_inventory_profit_' + split_id[3];
    marginid = 'crc_inventory_margin_' + split_id[3];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[2] == 'profit')
        {
			estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
			estimateTotal = estimateValue * qty;
			estimateMargin = profit.value / (estimateValue * qty) * 100;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+marginid).val(round2Fixed(estimateMargin));
        }
        
        if(split_id[2] == 'margin')
        {
			estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
			estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
			estimateTotal = estimateValue * qty;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+profitid).val(round2Fixed(estimateProfit));
        }
    }
    
    changeInventoryTotal();
}

function changeProfitInventoryMiscPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    qty = jQuery('#inqtymisc_' + split_id[1]).val();
    pcost = 'incostmisc_' + split_id[1];
    pestimateid = 'inestimatepricemisc_' + split_id[1];
    ptotal = 'intotalmisc_' + split_id[1];
    profitid = 'inprofitmisc_' + split_id[1];
    marginid = 'inmarginmisc_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'inprofitmisc')
        {
			estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
			estimateTotal = estimateValue * qty;
			estimateMargin = profit.value / (estimateValue * qty) * 100;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+marginid).val(round2Fixed(estimateMargin));
        }
        
        if(split_id[0] == 'inmarginmisc')
        {
			estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
			estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
			estimateTotal = estimateValue * qty;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+profitid).val(round2Fixed(estimateProfit));
        }
    }
    
    changeInventoryTotal();
}
</script>
<?php
$get_field_config_inventory = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT GROUP_CONCAT(inventory_dashboard SEPARATOR ',') AS inventory FROM field_config_inventory"));
$field_config_inventory = ','.$get_field_config_inventory['inventory'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <?php
			$columns = 0;
			if (strpos($base_field_config, ','."Inventory Category".',') !== FALSE) {
				$columns += 2;
			}
			if (strpos($base_field_config, ','."Inventory Part No".',') !== FALSE) {
				$columns += 2;
			}
			if (strpos($field_config_inventory, ','."Final Retail Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_inventory, ','."Admin Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_inventory, ','."Wholesale Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_inventory, ','."Commercial Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_inventory, ','."Client Price".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_inventory, ','."MSRP".',') !== FALSE) {
				$columns += 1;
			}
			if (strpos($field_config_inventory, ','."Cost".',') !== FALSE) {
				$columns += 1;
			}
			if (in_array_starts('Total Multiple', $field_order)) {
				$columns += 1;
			}
			$columns += 9;
			?>
			
            <?php if (strpos($base_field_config, ','."Inventory Category".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center" data-columns="<?php echo $columns; ?>" data-width="2">Category</label>
            <?php } ?>
			 <?php if (strpos($base_field_config, ','."Inventory Part No".',') !== FALSE) { ?>
			<label class="col-sm-2 text-center" data-columns="<?php echo $columns; ?>" data-width="2">Part Number</label>
			 <?php } ?>
            <label class="col-sm-2 text-center" data-columns="<?php echo $columns; ?>" data-width="2">Product Name</label>
            <?php if (strpos($field_config_inventory, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_inventory, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">MSRP</label>
            <?php } ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Rate Card Price</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">UOM</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Quantity</label>
           <?php if (strpos($field_config_inventory, ','."Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Cost</label>
            <?php } ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">% Margin</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Estimate Price</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">$ Profit</label>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Total</label>
			<?php if(in_array_starts('Total Multiple', $field_order)) { ?>
            <label class="col-sm-1 text-center" data-columns="<?php echo $columns; ?>" data-width="1">Total X 1</label>
			<?php } ?>
        </div>

        <?php
        $get_inventory = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_inventory'),'**#**');
                $get_inventory  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_inventory'),'**#**');
                $get_inventory  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_inventory'),'**#**');
                $get_inventory  .= '**'.$each_item;
            }
        }

        if(!empty($_GET['estimateid'])) {
            $inventory = $get_contact['inventory'];
            $each_data = explode('**',$inventory);
            foreach($each_data as $id_all) {
                if($id_all != '') {
                    $data_all = explode('#',$id_all);
                    $get_inventory .= '**'.$data_all[0].'#'.$data_all[2].'#'.$data_all[1].'#'.$data_all[3].'#'.$data_all[4].'#'.$data_all[5];
                }
            }
        }
        $final_total_inventory = 0;
        $final_total_inventory_profit = 0;
        $final_total_inventory_margin = 0;
        $final_total_inventory_cost = 0;
        ?>

        <?php if(!empty($get_inventory)) {
            $each_assign_inventory = explode('**',$get_inventory);
            $total_count = mb_substr_count($get_inventory,'**');
            $id_loop = 500;
            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {

                $each_item = explode('#',$each_assign_inventory[$inventory_loop]);
                $inventoryid = '';
                $qty = '';
                $unit = '';
                $est = '';
				$totalmulti = '';
                if(isset($each_item[0])) {
                    $inventoryid = $each_item[0];
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
                if(isset($each_item[7])) {
                    $totalmulti = $each_item[7];
                }
                $total = $qty*$est;
                $final_total_inventory += $total;

                if($inventoryid != '') {
                    $inventory = explode('**', $get_rc['inventory']);
                    $rc_price = 0;
                    foreach($inventory as $pp){
                        if (strpos('#'.$pp, '#'.$inventoryid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>

            <div class="form-group clearfix" id="<?php echo 'inventory_'.$id_loop; ?>" >
                <?php if (strpos($base_field_config, ','."Inventory Category".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectInventoryCategory(this)' data-placeholder="Choose a Category..." id="<?php echo 'ininventorycat_'.$id_loop; ?>" class="chosen-select-deselect form-control inventoryid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT category FROM inventory WHERE inventoryid = '$inventoryid' order by category");
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
                <?php if (strpos($base_field_config, ','."Inventory Part No".',') !== FALSE) { ?>
                    <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectInventoryCodePartNo(this)' data-placeholder="Choose a Part Number..." id="<?php echo 'ininventorypart_'.$id_loop; ?>" name="inventoryid[]" class="chosen-select-deselect form-control inventoryid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM inventory WHERE inventoryid = '$inventoryid' order by part_no");
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
                <div class="col-sm-2" data-columns="<?php echo $columns; ?>" data-width="2">
                <select onChange='selectInventoryCodePartName(this)' name='inventoryid[]' data-placeholder="Choose a Name..." id="<?php echo 'ininventoryname_'.$id_loop; ?>" class="chosen-select-deselect form-control inventoryid" width="380">
                    <option value=''></option>
                    <?php
                    $query = mysqli_query($dbc,"SELECT inventoryid, name FROM inventory WHERE inventoryid = '$inventoryid' order by name");
                    while($row = mysqli_fetch_array($query)) {
                        if ($inventoryid == $row['inventoryid']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo "<option ".$selected." value='". $row['inventoryid']."'>".decryptIt($row['name']).'</option>';
                    }
                    ?>
                </select>
                </div>
                <?php if (strpos($field_config_inventory, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inrp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'final_retail_price');?>" id="<?php echo 'inrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="inap[]" value="<?php echo get_inventory($dbc, $inventoryid, 'admin_price');?>" id="<?php echo 'inap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inwp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'wholesale_price');?>" id="<?php echo 'inwp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="incomp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'commercial_price');?>" id="<?php echo 'incomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="incp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'client_price');?>" id="<?php echo 'incp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inmsrp[]" value="<?php echo get_inventory($dbc, $inventoryid, 'msrp');?>" id="<?php echo 'inmsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="infinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'infinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimateunit[]" id="<?php echo 'inestimateunit_'.$id_loop; ?>" value="<?php echo $unit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimateqty[]" id="<?php echo 'inestimateqty_'.$id_loop; ?>" onchange="countInventory(this); qtychangevalueInventory(this);" value="<?php echo $qty; ?>" type="text" class="form-control" />
                </div>
                <?php if (strpos($field_config_inventory, ','."Cost".',') !== FALSE) { $display = 'style="width: calc(100% / '.$columns.')"'; } else { $display = "style='display:none;'"; }
				if(get_software_name() == 'washtech' || get_software_name() == 'localhost') { $cost_field = "IF average_cost > 0 THEN average_cost; ELSEIF purchase_cost > 0 THEN purchase_cost; ELSE cost; END IF"; } else { $cost_field = 'cost'; } ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inc[]" value="<?php echo get_inventory($dbc, $inventoryid, $cost_field);?>" id="<?php echo 'inc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inprofitmargin[]" id="<?php echo 'inprofitmargin_'.$id_loop; ?>" onchange="changeProfitInventoryPrice(this);" value="<?php echo $margin; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimateprice[]" value="<?php echo $est; ?>" id="<?php echo 'inestimateprice_'.$id_loop; ?>" onchange="countInventory(this); fillmarginvalueInventory(this);" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inprofit[]" id="<?php echo 'inprofit_'.$id_loop; ?>" onchange="changeProfitInventoryPrice(this);" value="<?php echo $profit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'inestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<?php if(in_array_starts('Total Multiple', $field_order)) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimatetotalmulti[]" value="<?php echo $totalmulti; ?>" id="<?php echo 'inestimatetotalmulti_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<?php } ?>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <a href="#" onclick="deleteEstimate(this,'inventory_','ininventoryname_'); return false;" id="<?php echo 'deleteinventory_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
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
                <div class="col-sm-2"  data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectInventoryCategory(this)' data-placeholder="Choose a Category..." id="ininventorycat_0" class="chosen-select-deselect form-control inventoryid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM inventory order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
				<div class="col-sm-2"  data-columns="<?php echo $columns; ?>" data-width="2" <?php if (strpos($base_field_config, ','."Inventory Part No".',') == FALSE) { echo 'style="display:none"'; } ?>>
                    <select onChange='selectInventoryCodePartNo(this)' data-placeholder="Choose a Part Number..." id="ininventorypart_0" class="chosen-select-deselect form-control inventoryid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, part_no FROM inventory order by part_no");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['inventoryid']."'>".$row['part_no'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2"  data-columns="<?php echo $columns; ?>" data-width="2">
                    <select onChange='selectInventoryCodePartName(this)' data-placeholder="Choose a Name..." id="ininventoryname_0" name="inventoryid[]" class="chosen-select-deselect form-control inventoryid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT inventoryid, name FROM inventory order by name");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['inventoryid']."'>".decryptIt($row['name']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php if (strpos($field_config_inventory, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inrp[]" id="inrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inap[]" id="inap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inwp[]" id="inwp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="incomp[]" id="incomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="incp[]" id="incp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_inventory, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inmsrp[]" id="inmsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="infinalprice[]" readonly id="infinalprice_0" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimateunit[]" id='inestimateunit_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimateqty[]" id='inestimateqty_0' onchange="countInventory(this); qtychangevalueInventory(this);" type="text" class="form-control" />
                </div>
                <?php if (strpos($field_config_inventory, ','."Cost".',') !== FALSE) { $display = 'style="width: calc(100% / '.$columns.')"'; } else { $display = "style='display:none;'"; } ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inc[]" id="inc_0" readonly type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inprofitmargin[]" id='inprofitmargin_0' onchange="changeProfitInventoryPrice(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimateprice[]" id='inestimateprice_0' onchange="countInventory(this); fillmarginvalueInventory(this);" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inprofit[]" id='inprofit_0' onchange="changeProfitInventoryPrice(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimatetotal[]" id='inestimatetotal_0' type="text" class="form-control" />
                </div>
				<?php if(in_array_starts('Total Multiple', $field_order)) { ?>
                <div class="col-sm-1" data-columns="<?php echo $columns; ?>" data-width="1">
                    <input name="inestimatetotalmulti[]" id='inestimatetotalmulti_0' type="text" class="form-control" />
                </div>
				<?php } ?>

                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'inventory_','ininventoryname_'); return false;" id="deleteinventory_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_in"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_in" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>

		<?php
		if(!empty($_GET['estimateid'])) {
			if($load_tab == 'Master') {
				$types = "AND `deleted` = 0";
			}
			else {
				$types = "AND '$estimateConfigValue' LIKE CONCAT('%,Inventory',rate_card_types,',%') AND `deleted` = 0";
			}

			$querxy = mysqli_query ($dbc, "SELECT DISTINCT(rate_card_types) FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') OR rate_card_name='') AND tile_name='Inventory' $types");
			while($row = mysqli_fetch_array ($querxy)) {
				$no_space_rate_card_types = str_replace(' ', '', $row['rate_card_types']);
				?>
				<a id="<?php echo $no_space_rate_card_types; ?>" class="btn brand-btn order_list_inventory mobile-100" ><?php echo $row['rate_card_types']; ?></a>
			<?php }

			$query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') OR $universal_rc_search) AND tile_name='Inventory'");

			$num_rows = mysqli_num_rows($query_rc);
			if($num_rows > 0) { ?>
				<div class="form-group clearfix inventory_heading">
				<?php foreach($field_order as $field_data) {
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					switch($data[0]) {
						case 'Heading':
						case 'Description':
							echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
							break;
						case 'Type':
						case 'UOM':
						case 'Quantity':
						case 'Cost':
						case 'Margin':
						case 'Profit':
						case 'Price':
						case 'Total':
						case 'Total Multiple':
							echo '<label class="col-sm-1 text-center">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
							break;
					}
				} ?>
				</div>
				<?php
			}
			$rc = 0;
			while($row_rc = mysqli_fetch_array($query_rc)) {

				$companyrcid = $row_rc['companyrcid'];

				$estimate_company_rate_card = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM cost_estimate_company_rate_card WHERE companyrcid='$companyrcid' AND estimateid='$estimateid'"));
				$no_space_rate_card_types = str_replace(' ', '', $row_rc['rate_card_types']);

				?>
				<div class="form-group clearfix all_inventory <?php echo $no_space_rate_card_types; ?> rc_est_inventory_<?php echo $rc; ?>" width="100%">

					<input type="hidden" name="crc_inventory_companyrcid_<?php echo $rc; ?>" value="<?php echo $row_rc['companyrcid']; ?>" />
					<?php foreach($field_order as $field_data) {
						$data = explode('***',$field_data);
						if($data[1] == '') {
							$data[1] = $data[0];
						}
						switch($data[0]) {
							case 'Heading': ?><div class="col-sm-2">
									<input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_inventory_heading_<?php echo $rc; ?>" type="text" class="form-control" />
								</div><?php
							case 'Description': ?><div class="col-sm-2">
									<input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_inventory_description_<?php echo $rc; ?>" type="text" class="form-control" />
								</div><?php
								break;
							case 'Type': ?><div class="col-sm-1">
									<input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_inventory_type_<?php echo $rc; ?>" type="text" class="form-control" />
								</div><?php
							case 'UOM': ?><div class="col-sm-1">
									<input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_inventory_uom_<?php echo $rc; ?>" type="text" class="form-control" />
								</div><?php
							case 'Quantity': ?><div class="col-sm-1">
									<input name="crc_inventory_qty_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="qtychangecrcvalueInventory(this); countRCTotalInventory(this)" id="crc_inventory_qty_<?php echo $rc;?>" class="form-control crc_inventory_qty" />
								</div><?php
							case 'Cost': ?><div class="col-sm-1">
									<input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_inventory_cost_<?php echo $rc; ?>" id="crc_inventory_cost_<?php echo $rc; ?>" type="text" class="form-control" />
								</div><?php
							case 'Margin': ?><div class="col-sm-1">
									<input name="crc_inventory_margin_<?php echo $rc; ?>" onchange="changeProfitInventoryRCPrice(this)" value= "<?php echo $estimate_company_rate_card['margin']; ?>"  id="crc_inventory_margin_<?php echo $rc;?>" type="text" class="form-control" />
								</div><?php
							case 'Profit': ?><div class="col-sm-1">
									<input name="crc_inventory_profit_<?php echo $rc; ?>" onchange="changeProfitInventoryRCPrice(this)" value="<?php echo $estimate_company_rate_card['profit']; ?>"  id="crc_inventory_profit_<?php echo $rc;?>" type="text" class="form-control" />
								</div><?php
							case 'Price': ?><div class="col-sm-1">
									<input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="fillmargincrcinventoryvalue(this); countRCTotalInventory(this)" name="crc_inventory_cust_price_<?php echo $rc; ?>" type="text" id="crc_inventory_custprice_<?php echo $rc;?>" class="form-control" />
								</div><?php
							case 'Total': ?><div class="col-sm-1">
									<input name="crc_inventory_total_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_inventory_total_<?php echo $rc;?>" type="text" class="form-control" />
								</div><?php
								break;
							case 'Total Multiple': ?><div class="col-sm-1">
									<input name="crc_inventory_total_multiple_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_inventory_total_multiple_<?php echo $rc;?>" type="text" class="form-control" />
								</div><?php
								break;
						}
					} ?>
				</div>

			<?php
				$rc++;
				$final_total_inventory += $estimate_company_rate_card['rc_total'];
				$final_total_inventory_profit += $estimate_company_rate_card['profit'];
				$final_total_inventory_margin += $estimate_company_rate_card['margin'];
				$final_total_inventory_cost += $estimate_company_rate_card['rc_total'] - $estimate_company_rate_card['profit'];
			}
		}
		?>
		
		<div class="form-group clearfix" style="margin-left:5px">
			<h3>Misc Items</h3>
			<div class="form-group clearfix">
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Heading':
					case 'Description':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
						break;
					case 'Type':
					case 'UOM':
					case 'Quantity':
					case 'Cost':
					case 'Margin':
					case 'Profit':
					case 'Price':
					case 'Total':
					case 'Total Multiple':
						echo '<label class="col-sm-1 text-center">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
						break;
				}
			} ?>
			</div>
			<div class="additional_in_misc clearfix">
				<div class="clearfix"></div>

				<div class="form-group clearfix" id="inventorymisc_0">
					<?php foreach($field_order as $field_data) {
						$data = explode('***',$field_data);
						if($data[1] == '') {
							$data[1] = $data[0];
						}
						switch($data[0]) {
							case 'Heading': ?><div class="col-sm-2">
									<input name="inheadmisc[]" id="inheadmisc_0" type="text" class="form-control" />
								</div><?php
								break;
							case 'Description': ?><div class="col-sm-2">
									<input name="indisc_misc[]" id="indisc_misc_0" type="text" class="form-control" />
								</div><?php
								break;
								break;
							case 'Type': ?><div class="col-sm-1">
									<input name="intype_misc[]" id="intype_misc_0" type="text" class="form-control" />
								</div><?php
								break;
							case 'UOM': ?><div class="col-sm-1">
									<input name="inuom_misc[]" id="inuom_misc_0" type="text" class="form-control" />
								</div><?php
								break;
							case 'Quantity': ?><div class="col-sm-1">
									<input name="inqtymisc[]" id="inqtymisc_0" type="text" class="form-control" onchange="countMiscInventory(this); qtychangemiscvalueInventory(this);" />
								</div><?php
								break;
							case 'Cost': ?><div class="col-sm-1">
									<input name="incostmisc[]" id="incostmisc_0" type="text" class="form-control" />
								</div><?php
								break;
							case 'Margin': ?><div class="col-sm-1">
									<input name="inmarginmisc[]" id="inmarginmisc_0" type="text" onchange="changeProfitInventoryMiscPrice(this);" class="form-control" />
								</div><?php
								break;
							case 'Profit': ?><div class="col-sm-1">
									<input name="inprofitmisc[]" id="inprofitmisc_0" type="text" onchange="changeProfitInventoryMiscPrice(this);" class="form-control" />
								</div><?php
								break;
							case 'Price': ?><div class="col-sm-1">
									<input name="inestimatepricemisc[]" id="inestimatepricemisc_0" type="text" class="form-control" onchange="countMiscInventory(this); fillmarginmiscvalueInventory(this);" />
								</div><?php
								break;
							case 'Total': ?><div class="col-sm-1">
									<input name="intotalmisc[]" id="intotalmisc_0" type="text" class="form-control" />
								</div><?php
								break;
							case 'Total Multiple': ?><div class="col-sm-1">
									<input name="intotalmiscmulti[]" id="intotalmiscmulti_0" type="text" class="form-control" />
								</div><?php
								break;
						}
					} ?>
					
					<div class="col-sm-1" >
						<a href="#" onclick="deleteEstimate(this,'inventorymisc_','inheadmisc_'); return false;" id="deleteinventorymisc_0" class="btn brand-btn">Delete</a>
					</div>
				</div>
			</div>
			
			<div id="add_here_new_in_misc"></div>
			
			<div class="form-group triple-gapped clearfix">
				<div class="col-sm-offset-4 col-sm-8">
					<button id="add_row_in_misc" class="btn brand-btn pull-left">Add Row</button>
				</div>
			</div>
            <br>
            <?php
            $query_misc_rc = mysqli_query($dbc,"SELECT * FROM cost_estimate_misc WHERE accordion='Inventory' AND estimateid=" . $_GET['estimateid']);
            $misc_num_rows = mysqli_num_rows($query_misc_rc);
            if($misc_num_rows > 0) { ?>
                <div class="form-group clearfix products_misc_heading">
				<?php foreach($field_order as $field_data) {
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					switch($data[0]) {
						case 'Heading':
						case 'Description':
							echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
							break;
						case 'Type':
						case 'UOM':
						case 'Quantity':
						case 'Cost':
						case 'Margin':
						case 'Profit':
						case 'Price':
						case 'Total':
						case 'Total Multiple':
							echo '<label class="col-sm-1 text-center">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
							break;
					}
				} ?>
                </div>
                <?php
            }
            
            $misc_rc = 0;
            while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) { ?>
                <div class="clearfix"></div>

				<div class="form-group clearfix">
					<?php foreach($field_order as $field_data) {
						$data = explode('***',$field_data);
						if($data[1] == '') {
							$data[1] = $data[0];
						}
						switch($data[0]) {
							case 'Heading': ?><div class="col-sm-2">
									<input name="inheadmisc_display[]" id="inheadmisc" value="<?php echo $misc_row_rc['heading'] ?>" type="text" readonly class="form-control" />
								</div><?php
								break;
							case 'Description': ?><div class="col-sm-2">
									<input name="indisc_misc_display[]" id="indisc_misc" type="text" value="<?php echo $misc_row_rc['description'] ?>" readonly class="form-control" />
								</div><?php
								break;
							case 'Type': ?><div class="col-sm-1">
									<input name="intype_misc_display[]" id="intype_misc" value="<?php echo $misc_row_rc['type'] ?>" readonly type="text" class="form-control" />
								</div><?php
								break;
							case 'UOM': ?><div class="col-sm-1">
									<input name="inuom_misc_display[]" id="inuom_misc" type="text" value="<?php echo $misc_row_rc['uom'] ?>" readonly class="form-control" />
								</div><?php
								break;
							case 'Quantity': ?><div class="col-sm-1">
									<input name="inqtymisc_display[]" id="inqtymisc" type="text" readonly class="form-control" value="<?php echo $misc_row_rc['qty'] ?>" onchange="countMiscProduct(this);" />
								</div><?php
								break;
							case 'Cost': ?><div class="col-sm-1">
									<input name="incost_misc_display[]" id="incost_misc" type="text" value="<?php echo $misc_row_rc['cost'] ?>" readonly class="form-control" />
								</div><?php
								break;
							case 'Margin': ?><div class="col-sm-1">
									<input name="inmarginmisc_display[]" id="inmarginmisc" value="<?php echo $misc_row_rc['margin'] ?>" readonly type="text" class="form-control" />
								</div><?php
								break;
							case 'Profit': ?><div class="col-sm-1">
									<input name="inprofitmisc_display[]" id="inprofitmisc" value="<?php echo $misc_row_rc['profit'] ?>" readonly type="text" class="form-control" />
								</div><?php
								break;
							case 'Price': ?><div class="col-sm-1">
									<input name="inestimatepricemisc_display[]" id="inestimatepricemisc" readonly value="<?php echo $misc_row_rc['estimate_price'] ?>" type="text" class="form-control" onchange="countMiscProduct(this);" />
								</div><?php
								break;
							case 'Total': ?><div class="col-sm-1">
									<input name="intotalmisc_display[]" id="intotalmisc" value="<?php echo $misc_row_rc['total'] ?>" readonly type="text" class="form-control" />
								</div><?php
								break;
							case 'Total Multiple': ?><div class="col-sm-1">
									<input name="intotalmiscmulti_display[]" id="intotalmiscmulti" value="<?php echo $misc_row_rc['total_multiple'] ?>" readonly type="text" class="form-control" />
								</div><?php
								break;
						}
					} ?>
				</div>
            <?php
                $misc_rc++;
                $final_total_misc_inventory += $misc_row_rc['total'];
            }
            ?>
		</div>
    </div>
</div>

<input type="hidden" name="total_rc_inventory" value="<?php echo $rc; ?>" />

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Cost: </label>
    <div class="col-sm-8">
      <input name="inventory_cost" id="inventory_cost" value="<?php echo $final_total_inventory_cost; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group" style="display:none;">
    <label for="company_name" class="col-sm-4 control-label">Total $ USD Cost: </label>
    <div class="col-sm-8">
      <input name="inventory_cost" id="inventory_cost" value="<?php echo $final_total_inventory_cost; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group" style="display:none;">
    <label for="company_name" class="col-sm-4 control-label">Total $ CAD Cost: </label>
    <div class="col-sm-8">
      <input name="inventory_cost" id="inventory_cost" value="<?php echo $final_total_inventory_cost; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
    <div class="col-sm-8">
      <input name="inventory_profit" id="inventory_profit" value="<?php echo $final_total_inventory_profit; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
    <div class="col-sm-8">
      <input name="inventory_profit_margin" id="inventory_profit_margin" value="<?php echo $final_total_inventory_margin; ?>"  value="" readonly="" type="text" class="form-control">
    </div>
</div>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="inventory_budget" value="<?php echo $budget_price[9]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="inventory_total" value="<?php echo $final_total_inventory + $final_total_misc_inventory;?>" type="text" class="form-control">
    </div>
</div>
