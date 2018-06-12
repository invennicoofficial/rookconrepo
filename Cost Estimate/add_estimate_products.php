<script>
$(document).ready(function() {
    $('.all_products').hide();
    $('.products_heading').hide();

	$('.order_list_products').on( 'click', function () {
        var pro_type = $(this).attr("id");

        $('.all_products').hide();
        $('.'+pro_type).show();
        $('.products_heading').show();

		$('.order_list_products').removeClass('active_tab');
        $(this).addClass('active_tab');
    });
	
	if($('.order_list_products').length == 1 && '<?php echo $load_tab; ?>' != 'Master') {
		$('.order_list_products').click();
	}

	//Products
    var add_new_p = 1;
    $('#deleteproducts_0').hide();
    $('#add_row_p').on( 'click', function () {
        $('#deleteproducts_0').show();
        var clone = $('.additional_p').clone();
        clone.find('.form-control').val('');

        clone.find('#pproduct_0').attr('id', 'pproduct_'+add_new_p);
		clone.find('#pcategory_0').attr('id', 'pcategory_'+add_new_p);
        clone.find('#pheading_0').attr('id', 'pheading_'+add_new_p);
        clone.find('#pfrp_0').attr('id', 'pfrp_'+add_new_p);
        clone.find('#pc_0').attr('id', 'pc_'+add_new_p);
        clone.find('#pap_0').attr('id', 'pap_'+add_new_p);
        clone.find('#pwp_0').attr('id', 'pwp_'+add_new_p);
        clone.find('#pcomp_0').attr('id', 'pcomp_'+add_new_p);
        clone.find('#pcp_0').attr('id', 'pcp_'+add_new_p);
        clone.find('#pmsrp_0').attr('id', 'pmsrp_'+add_new_p);
        clone.find('#pmb_0').attr('id', 'pmb_'+add_new_p);
        clone.find('#peh_0').attr('id', 'peh_'+add_new_p);
        clone.find('#pah_0').attr('id', 'pah_'+add_new_p);

		clone.find('#pfinalprice_0').attr('id', 'pfinalprice_'+add_new_p);
		clone.find('#pestimateprice_0').attr('id', 'pestimateprice_'+add_new_p);
        clone.find('#peprofit_0').attr('id', 'peprofit_'+add_new_p);
        clone.find('#peprofitmargin_0').attr('id', 'peprofitmargin_'+add_new_p);
		clone.find('#pestimateqty_0').attr('id', 'pestimateqty_'+add_new_p);
		clone.find('#pestimateunit_0').attr('id', 'pestimateunit_'+add_new_p);
		clone.find('#pestimatetotal_0').attr('id', 'pestimatetotal_'+add_new_p);

        clone.find('#products_0').attr('id', 'products_'+add_new_p);
        clone.find('#deleteproducts_0').attr('id', 'deleteproducts_'+add_new_p);
        $('#deleteproducts_0').hide();

        clone.removeClass("additional_p");
        $('#add_here_new_p').append(clone);

        resetChosen($("#pproduct_"+add_new_p));
        resetChosen($("#pcategory_"+add_new_p));
        resetChosen($("#pheading_"+add_new_p));

        add_new_p++;

        return false;
    });
	
	//Product Misc
	var add_new_p_misc = 1;
	$('#deleteproductsmisc_0').hide();
	$('#add_row_p_misc').on( 'click', function () {

		$('#deleteproductsmisc_0').show();
        var clone_misc = $('.additional_p_misc').clone();
        clone_misc.find('.form-control').val('');
		clone_misc.find('#pid_misc_0').attr('id', 'pid_misc_'+add_new_p_misc);
        clone_misc.find('#ptype_misc_0').attr('id', 'ptype_misc_'+add_new_p_misc);
		clone_misc.find('#pdisc_misc_0').attr('id', 'pdisc_misc_'+add_new_p_misc);
		clone_misc.find('#puom_misc_0').attr('id', 'puom_misc_'+add_new_p_misc);
		clone_misc.find('#pheadmisc_0').attr('id', 'pheadmisc_'+add_new_p_misc);
		clone_misc.find('#pcostmisc_0').attr('id', 'pcostmisc_'+add_new_p_misc);
		clone_misc.find('#pqtymisc_0').attr('id', 'pqtymisc_'+add_new_p_misc);
		clone_misc.find('#ptotalmisc_0').attr('id', 'ptotalmisc_'+add_new_p_misc);
		clone_misc.find('#pestimatepricemisc_0').attr('id', 'pestimatepricemisc_'+add_new_p_misc);
		clone_misc.find('#pmarginmisc_0').attr('id', 'pmarginmisc_'+add_new_p_misc);
		clone_misc.find('#pprofitmisc_0').attr('id', 'pprofitmisc_'+add_new_p_misc);
        clone_misc.find('#productsmisc_0').attr('id', 'productsmisc_'+add_new_p_misc);
        clone_misc.find('#deleteproductsmisc_0').attr('id', 'deleteproductsmisc_'+add_new_p_misc);
        $('#deleteproductsmisc_0').hide();

        clone_misc.removeClass("additional_p_misc");

        $('#add_here_new_p_misc').append(clone_misc);

        add_new_p_misc++;

        return false;
    });
	changeTotal();
});
//Products
function selectProductProduct(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=p_product_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#pcategory_"+arr[1]).html(response);
			$("#pcategory_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectProductCat(sel) {
	var stage = encodeURIComponent(sel.value);
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=p_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#pheading_"+arr[1]).html(response);
			$("#pheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectProductHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=p_head_config&value="+stage+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#pfrp_"+arr[1]).val(result[0]);
            $("#pap_"+arr[1]).val(result[1]);
            $("#pwp_"+arr[1]).val(result[2]);
            $("#pcomp_"+arr[1]).val(result[3]);
            $("#pcp_"+arr[1]).val(result[4]);
            $("#pmsrp_"+arr[1]).val(result[5]);
			$("#pfinalprice_"+arr[1]).val(result[6]);
			$("#pmb_"+arr[1]).val(result[7]);
			$("#peh_"+arr[1]).val(result[8]);
			$("#pah_"+arr[1]).val(result[9]);
            $("#pc_"+arr[1]).val(result[10]);
		}
	});
}

function qtychangecrcvalue(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'crc_products_profit_' + idarray[3];
    var profitmarginid = 'crc_products_margin_' + idarray[3];
    var pestimateid = 'crc_products_custprice_' + idarray[3];
    var pcid = 'crc_products_cost_' + idarray[3];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
	changeTotal();
}

function countProduct(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');
        var estqty = $('#pestimateqty_'+split_id[1]).val();
        if(estqty == null || estqty == '') {
            estqty = 1;
        }

        document.getElementById('pestimatetotal_'+split_id[1]).value = parseFloat($('#pestimateprice_'+split_id[1]).val() * estqty);
    }

    
    var sum_fee = 0;
    $('[name="pestimatetotal[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
    $('[name="ptotalmisc[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
    $('[name="ptotalmisc_display[]"]').each(function () {
        sum_fee += Number($(this).val());
    });

    $('[name="product_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="product_summary"]').val('$'+round2Fixed(sum_fee)).change();

    var product_budget = $('[name="product_budget"]').val();
    if(product_budget >= sum_fee) {
        $('[name="product_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="product_total"]').css("background-color", "#ff9999"); // Green
    }
}

function fillmarginvalue(est) {
    var idarray = est.id.split("_");
    var profitid = 'peprofit_' + idarray[1];
    var profitmarginid = 'peprofitmargin_' + idarray[1];
    var pcid = 'pc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#pestimateqty_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#pestimateqty_' + idarray[1]).val(1);
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

    changeTotal();
}

function fillmarginmiscvalue(est) {
	var idarray = est.id.split("_");
    var profitid = 'pprofitmisc_' + idarray[1];
    var profitmarginid = 'pmarginmisc_' + idarray[1];
    var pcid = 'pcostmisc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#pqtymisc_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#pqtymisc_' + idarray[1]).val(1);
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
	changeTotal();
}

function fillmargincrcvalue(est) {
	var idarray = est.id.split("_");
    var profitid = 'crc_products_profit_' + idarray[3];
    var profitmarginid = 'crc_products_margin_' + idarray[3];
    var pcid = 'crc_products_cost_' + idarray[3];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#crc_products_qty_' + idarray[3]).val();
    if(qty == '' || qty == null) {
        jQuery('#crc_products_qty_' + idarray[3]).val(1);
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
    
    changeTotal();
}

function qtychangevalue(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'peprofit_' + idarray[1];
    var profitmarginid = 'peprofitmargin_' + idarray[1];
    var pestimateid = 'pestimateprice_' + idarray[1];
    var pcid = 'pc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
    changeTotal();
}

function qtychangemiscvalue(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'pprofitmisc_' + idarray[1];
    var profitmarginid = 'pmarginmisc_' + idarray[1];
    var pestimateid = 'pestimatepricemisc_' + idarray[1];
    var pcid = 'pcostmisc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
	changeTotal();
}

function changeTotal() {
    var sum_profit = 0;
	var sum_total = 0;
    var sum_profit_margin = 0;
    var misc_profit_margin = 0;
    var crc_profit_fee = 0;
	var crc_total = 0;
    var crc_margin_fee = 0;
    jQuery('[name="peprofit[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });
	jQuery('[name="pprofitmisc[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });
	jQuery('[name="pprofitmisc_display[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });
	
    jQuery('[name="pestimatetotal[]"]').each(function () {
        sum_total += +$(this).val() || 0;
    });
	
	jQuery('[name="ptotalmisc[]"]').each(function () {
        sum_total += +$(this).val() || 0;
    });
	jQuery('[name="ptotalmisc_display[]"]').each(function () {
        sum_total += +$(this).val() || 0;
    });
    
    for(var loop = 0; loop < 500; loop++) {
        if(typeof $('[name="crc_products_profit_'+loop+'"]').val() !='undefined')
        {
            sum_profit += +$('[name="crc_products_profit_'+loop+'"]').val();
        }
        else {
            break;
        }
    }
    
    for(var loop = 0; loop < 500; loop++) {
        if(typeof $('[name="crc_products_total_'+loop+'"]').val() !='undefined')
        {
            sum_total += +$('[name="crc_products_total_'+loop+'"]').val();
        }
        else {
            break;
        }
    }
	per_profit_margin = (sum_profit / sum_total) * 100;
	if(isNaN(per_profit_margin)) {
		per_profit_margin = 'N/A';
	}
	else {
		per_profit_margin = round2Fixed(per_profit_margin) + '%';
	}

    jQuery('#product_profit').val('$'+round2Fixed(sum_profit));
    jQuery('#product_profit_margin').val(per_profit_margin);
    jQuery('#product_cost').val('$'+round2Fixed(sum_total - sum_profit));
    jQuery('[name=product_total]').val('$'+round2Fixed(sum_total));
    jQuery('[name=products_summary_profit]').val('$'+round2Fixed(sum_profit));
    jQuery('[name=products_summary_margin]').val(per_profit_margin);
    jQuery('[name=products_summary_cost]').val('$'+round2Fixed(sum_total - sum_profit));
    jQuery('[name=products_summary]').val('$'+round2Fixed(sum_total)).change();
}


function countRCTotalProduct(sel) {
	var stage = sel.value;
	var typeId = sel.id;

	var arr = typeId.split('_');
    var del = (jQuery('#crc_products_custprice_'+arr[3]).val() * jQuery('#crc_products_qty_'+arr[3]).val());
    jQuery('#crc_products_total_'+arr[3]).val(round2Fixed(del));
	changeTotal();
}

function countMiscProduct(txb)
{

	var get_id = txb.id;

	var split_id = get_id.split('_');
	if(split_id[0] == 'pestimatepricemisc') {
		var estqty = $('#pqtymisc_'+split_id[1]).val();
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('pqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('ptotalmisc_'+split_id[1]).value = parseFloat($('#pestimatepricemisc_'+split_id[1]).val() * estqty);
	}
	
	if(split_id[0] == 'pqtymisc') {
		var estqty = txb.value;
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('pqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('ptotalmisc_'+split_id[1]).value = parseFloat($('#pestimatepricemisc_'+split_id[1]).val() * estqty);
	}
	
    var sum_fee = 0;
    sum_fee += +document.getElementById('ptotalmisc_'+split_id[1]).value || 0;

    sum_fee += +$('[name="product_total"]').val();
    $('[name="product_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="product_summary"]').val('$'+round2Fixed(sum_fee)).change();

    var product_budget = $('[name="product_budget"]').val();
    if(product_budget >= sum_fee) {
        $('[name="product_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="product_total"]').css("background-color", "#ff9999"); // Green
    }
}

function changeProfitPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    jQuery('#pestimateqty_' + split_id[1]).val(1);
    qty = 1;
    pcost = 'pc_' + split_id[1];
    pestimateid = 'pestimateprice_' + split_id[1];
    ptotal = 'ptotalmisc_' + split_id[1];
    profitid = 'peprofit_' + split_id[1];
    marginid = 'peprofitmargin_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'peprofit')
        {
            estimateValue = parseInt(profit.value) + parseInt(jQuery('#'+pcost).val());
            var deltaper = (profit.value / (estimateValue * qty)) * 100;
            jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
            jQuery('#'+ptotal).val(round2Fixed(estimateValue));
            jQuery('#'+marginid).val(round2Fixed(deltaper));
        }
        
        if(split_id[0] == 'peprofitmargin')
        {
            estimateValue = (parseInt(jQuery('#' + pcost).val()) * 100 / (100 - parseInt(profit.value)));
            var deltavalue = estimateValue - parseInt(jQuery('#'+pcost).val());
            jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
            jQuery('#'+ptotal).val(round2Fixed(estimateValue));
            jQuery('#'+profitid).val(round2Fixed(deltavalue));
        }
    }
    
    changeTotal();
}

function changeProfitRCPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    jQuery('#crc_products_qty_' + split_id[3]).val(1);
    qty = 1;
    pcost = 'crc_products_cost_' + split_id[3];
    pestimateid = 'crc_products_custprice_' + split_id[3];
    ptotal = 'crc_products_total_' + split_id[3];
    profitid = 'crc_products_profit_' + split_id[3];
    marginid = 'crc_products_margin_' + split_id[3];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[2] == 'profit')
        {
            estimateValue = parseInt(profit.value) + parseInt(jQuery('#'+pcost).val());
            var deltaper = (profit.value / (estimateValue * qty)) * 100;
            jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
            jQuery('#'+ptotal).val(round2Fixed(estimateValue));
            jQuery('#'+marginid).val(round2Fixed(deltaper));
            
        }
        
        if(split_id[2] == 'margin')
        {
            
            estimateValue = (parseInt(jQuery('#' + pcost).val()) * 100 / (100 - parseInt(profit.value)));
            var deltavalue = estimateValue - parseInt(jQuery('#'+pcost).val());
            jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
            jQuery('#'+ptotal).val(round2Fixed(estimateValue));
            jQuery('#'+profitid).val(round2Fixed(deltavalue));
        }
    }
    
    changeTotal();
}

function changeProfitMiscPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    jQuery('#pqtymisc_' + split_id[1]).val(1);
    qty = 1;
    pcost = 'pcostmisc_' + split_id[1];
    pestimateid = 'pestimatepricemisc_' + split_id[1];
    ptotal = 'ptotalmisc_' + split_id[1];
    profitid = 'pprofitmisc_' + split_id[1];
    marginid = 'pmarginmisc_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'pprofitmisc')
        {
            estimateValue = parseInt(profit.value) + parseInt(jQuery('#'+pcost).val());
            var deltaper = (profit.value / (estimateValue * qty)) * 100;
            jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
            jQuery('#'+ptotal).val(round2Fixed(estimateValue));
            jQuery('#'+marginid).val(round2Fixed(deltaper));
            
        }
        
        if(split_id[0] == 'pmarginmisc')
        {
            
            estimateValue = (parseInt(jQuery('#' + pcost).val()) * 100 / (100 - parseInt(profit.value)));
            var deltavalue = estimateValue - parseInt(jQuery('#'+pcost).val());
            jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
            jQuery('#'+ptotal).val(round2Fixed(estimateValue));
            jQuery('#'+profitid).val(round2Fixed(deltavalue));
        }
    }
    
    changeTotal();
}
</script>
<?php
$get_field_config_product = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT products_dashboard FROM field_config"));
$field_config_product = ','.$get_field_config_product['products_dashboard'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
			<?php $columns = 10;
			$columns += (strpos($base_field_config, ','."Products Product Type".',') !== FALSE) * 2;
			$columns += (strpos($base_field_config, ','."Products Category".',') !== FALSE) * 2;
			$columns += (strpos($field_config_product, ','."Final Retail Price".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Admin Price".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Wholesale Price".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Commercial Price".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Client Price".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."MSRP".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Minimum Billable".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Estimated Hours".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Actual Hours".',') !== FALSE);
			$columns += (strpos($field_config_product, ','."Cost".',') !== FALSE);
			$columns += (in_array_starts('Total Multiple', $field_order)); ?>
            <?php if (strpos($base_field_config, ','."Products Product Type".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center" data-columns="<?= $columns ?>" data-width="2">Product Type</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."Products Category".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center" data-columns="<?= $columns ?>" data-width="2">Category</label>
            <?php } ?>
            <label class="col-sm-2 text-center" data-columns="<?= $columns ?>" data-width="2">Heading</label>
            <?php if (strpos($field_config_product, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">MSRP</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Minimum Billable".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Minimum Billable Hours</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Estimated Hours".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Estimated Hours</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Actual Hours".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Actual Hours</label>
            <?php } ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Rate Card Price</label>
			<?php foreach($field_order as $field_data):
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Cost':
						if (strpos($field_config_product, ','."Cost".',') !== FALSE) { ?>
							<label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Cost</label>
						<?php }
						break;
					case 'UOM':
					case 'Quantity':
					case 'Margin':
					case 'Price':
					case 'Profit':
					case 'Total':
					case 'Total Multiple':
						echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
						break;
				}
			endforeach; ?>

        </div>

       <?php
        $get_products = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_products'),'**#**');
                $get_products  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_products'),'**#**');
                $get_products  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_products'),'**#**');
                $get_products  .= '**'.$each_item;
            }
        }

        if(!empty($_GET['estimateid'])) {
            $products = $get_contact['products'];
            $each_productsid = explode('**',$products);
            foreach($each_productsid as $id_all) {
				//$id_all_array = explode('$', $id_all);
				//if($id_all_array[0] == $_GET['estimatetabid']) {
					//$id_all = $id_all_array[1];
					if($id_all != '') {
						$productsid_all = explode('#',$id_all);
						$get_products .= '**'.$productsid_all[0].'#'.$productsid_all[2].'#'.$productsid_all[1].'#'.$productsid_all[3].'#'.$productsid_all[4].'#'.$productsid_all[5];
					}
				//}
            }
        }
        $final_total_products = 0;
        $final_total_products_profit = 0;
        $final_total_products_margin = 0;
        $final_total_products_cost = 0;
        ?>

        <?php if(!empty($get_products)) {
            $each_assign_inventory = explode('**',$get_products);
            //echo '<pre>';
            //print_r($each_assign_inventory);
            //exit;
            $total_count = mb_substr_count($get_products,'**');
            $id_loop = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $each_item = explode('#',$each_assign_inventory[$inventory_loop]);
                $productid = '';
                $qty = '';
                $est = '';
                $unit = '';
                $totalmulti = '';
                if(isset($each_item[0])) {
                    $productid = $each_item[0];
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
                $final_total_products += $total;
                if($productid != '') {

                    $products = explode('**', $get_rc['products']);
                    $rc_price = 0;
                    foreach($products as $pp){
                        if (strpos('#'.$pp, '#'.$productid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>

            <div class="form-group clearfix" id="<?php echo 'products_'.$id_loop; ?>" >
                <?php if (strpos($base_field_config, ','."Products Product Type".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="2">
					<label class="col-sm-4 show-on-mob">Product Type</label>
                    <select onChange='selectProductProduct(this)' data-placeholder="Choose a Type..." id="<?php echo 'pproduct_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(product_type) FROM products WHERE deleted=0 order by product_type");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_products($dbc, $productid, 'product_type') == $row['product_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['product_type']."'>".$row['product_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($base_field_config, ','."Products Category".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="2">
					<label class="col-sm-4 show-on-mob">Category</label>
                    <select onChange='selectProductCat(this)' data-placeholder="Choose a Category..." id="<?php echo 'pcategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM products WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_products($dbc, $productid, 'category') == $row['category']) {
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
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="2">
					<label class="col-sm-4 show-on-mob">Heading</label>
                    <select onChange='selectProductHeading(this)' data-placeholder="Choose a Heading..." id="<?php echo 'pheading_'.$id_loop; ?>" name="productid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT productid, heading FROM products WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            if ($productid == $row['productid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['productid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="<?php echo 'pheading_'.$id_loop; ?>" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_product, ','."Final Retail Price".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Retail Price</label>
                    <input name="sfrp[]" value="<?php echo get_products($dbc, $productid, 'final_retail_price');?>" id="<?php echo 'pfrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Admin Price".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Admin Price</label>
                    <input name="sap[]" value="<?php echo get_products($dbc, $productid, 'admin_price');?>" id="<?php echo 'pap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Wholesale Price".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Wholesale Price</label>
                    <input name="swp[]" value="<?php echo get_products($dbc, $productid, 'wholesale_price');?>" id="<?php echo 'pwp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Commercial Price".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Commercial Price</label>
                    <input name="pcomp[]" value="<?php echo get_products($dbc, $productid, 'commercial_price');?>" id="<?php echo 'pcomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Client Price".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Client Price</label>
                    <input name="pcp[]" value="<?php echo get_products($dbc, $productid, 'client_price');?>" id="<?php echo 'pcp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."MSRP".',') !== FALSE) { ?>
				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">MSRP</label>
                    <input name="pmsrp[]" value="<?php echo get_products($dbc, $productid, 'msrp');?>" id="<?php echo 'pmsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Minimum Billable".',') !== FALSE) { ?>
					<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
						<label class="col-sm-4 show-on-mob">Minimum Billable</label>
                        <input name="pmb[]" value="<?php echo get_products($dbc, $productid, 'minimum_billable');?>" id="<?php echo 'pmb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Estimated Hours".',') !== FALSE) { ?>
					<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
						<label class="col-sm-4 show-on-mob">Estimated Hours</label>
                        <input name="peh[]" value="<?php echo get_products($dbc, $productid, 'estimated_hours');?>" id="<?php echo 'peh_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Actual Hours".',') !== FALSE) { ?>
					<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
						<label class="col-sm-4 show-on-mob">Actual Hours</label>
                        <input name="pah[]" value="<?php echo get_products($dbc, $productid, 'actual_hours');?>" id="<?php echo 'pah_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>

				<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Rate Card Price</label>
                    <input name="pfinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'pfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<?php foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					} ?>
					<?php switch($data[0]) {
						case 'UOM': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimateunit[]" id="<?php echo 'pestimateunit_'.$id_loop; ?>" value="<?php echo $unit; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Quantity': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimateqty[]" id="<?php echo 'pestimateqty_'.$id_loop; ?>" onchange="countProduct(this); qtychangevalue(this)" value="<?php echo $qty; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Cost': 
							if (strpos($field_config_product, ','."Cost".',') !== FALSE) { ?>
								<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
									<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
									<input name="pc[]" value="<?php echo get_products($dbc, $productid, 'cost');?>" id="<?php echo 'pc_'.$id_loop; ?>" readonly type="text" class="form-control" />
								</div>
							<?php }
							break;
						case 'Margin': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="peprofitmargin[]" id="<?php echo 'peprofitmargin_'.$id_loop; ?>" onchange="changeProfitPrice(this)" value="<?php echo $margin; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Price': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimateprice[]" id="<?php echo 'pestimateprice_'.$id_loop; ?>" onchange="countProduct(this); fillmarginvalue(this);" value="<?php echo $est; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Profit': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="peprofit[]" id="<?php echo 'peprofit_'.$id_loop; ?>" onchange="changeProfitPrice(this)" value="<?php echo $profit; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'pestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total Multiple': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimatetotalmulti[]" value="<?php echo $totalmulti; ?>" id="<?php echo 'pestimatetotalmulti_'.$id_loop; ?>" type="text" class="form-control" />
						</div>
							<?php break;
					}
				endforeach; ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <a href="#" onclick="deleteEstimate(this,'products_','pheading_'); return false;" id="<?php echo 'deleteproducts_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_p clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="products_0">
                <?php if (strpos($base_field_config, ','."Products Product Type".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?= $columns ?>" data-width="2">
					<label class="col-sm-4 show-on-mob">Product Type</label>
                    <select onChange='selectProductProduct(this)' data-placeholder="Choose a Type..." id="pproduct_0" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(product_type) FROM products WHERE deleted=0 order by product_type");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['product_type']."'>".$row['product_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($base_field_config, ','."Products Category".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?= $columns ?>" data-width="2">
					<label class="col-sm-4 show-on-mob">Category</label>
                    <select onChange='selectProductCat(this)' data-placeholder="Choose a Category..." id="pcategory_0" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM products WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2" data-columns="<?= $columns ?>" data-width="2">
					<label class="col-sm-4 show-on-mob">Heading</label>
                    <select onChange='selectProductHeading(this)' data-placeholder="Choose a Heading..." id="pheading_0" name="productid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT productid, heading FROM products WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['productid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="pheading[]" readonly id="pheading_0" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_product, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Retail Price</label>
                    <input name="pfrp[]" id="pfrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Admin Price</label>
                    <input name="pap[]" id="pap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Wholesale Price</label>
                    <input name="pwp[]" id="pwp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Commercial Price</label>
                    <input name="pcomp[]" id="pcomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Client Price</label>
                    <input name="pcp[]" id="pcp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">MSRP</label>
                    <input name="pmsrp[]" id="pmsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <?php if (strpos($field_config_product, ','."Minimum Billable".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Minimum Billable</label>
                        <input name="pmb[]" id="pmb_0" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Estimated Hours".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Estimated Hours</label>
                        <input name="peh[]" id="peh_0" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Actual Hours".',') !== FALSE) { ?>
                    <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Actual Hours</label>
                        <input name="pah[]" id="pah_0" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>

                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
					<label class="col-sm-4 show-on-mob">Rate Card Price</label>
                    <input name="pfinalprice[]" readonly id="pfinalprice_0" type="text" class="form-control" />
                </div>
				<?php foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					} ?>
					<?php switch($data[0]) {
						case 'UOM': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimateunit[]" id='pestimateunit_0' type="text" class="form-control" />
						</div>
							<?php break;
						case 'Quantity': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimateqty[]" id='pestimateqty_0' onchange="countProduct(this); qtychangevalue(this);" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Cost': 
							if (strpos($field_config_product, ','."Cost".',') !== FALSE) { ?>
								<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
									<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
									 <input name="pc[]" id="pc_0" readonly type="text" class="form-control" />
								</div>
							<?php }
							break;
						case 'Margin': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="peprofitmargin[]" id='peprofitmargin_0' onchange="changeProfitPrice(this)" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Price': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimateprice[]" id='pestimateprice_0' onchange="countProduct(this); fillmarginvalue(this);" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Profit': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="peprofit[]" id='peprofit_0' onchange="changeProfitPrice(this)" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?></label>
							<input name="pestimatetotal[]" id='pestimatetotal_0' type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total Multiple': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<label class="col-sm-4 show-on-mob"><?= $data[1] ?> X 1</label>
							<input name="pestimatetotalmulti[]" id='pestimatetotalmulti_0' type="text" class="form-control" />
						</div>
							<?php break;
					}
				endforeach; ?>
				
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <a href="#" onclick="deleteEstimate(this,'products_','pheading_'); return false;" id="deleteproducts_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_p"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_p" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
		<?php
		if(!empty($_GET['estimateid'])) {

			if($load_tab == 'Master') {
				$types = "AND `rate_card_types` != ''";
			}
			else {
				$types = "AND '$estimateConfigValue' LIKE CONCAT('%,Product',rate_card_types,',%')";
			}
			$querxy = mysqli_query ($dbc, "SELECT DISTINCT(rate_card_types) FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') OR rate_card_name='') AND tile_name='Products' $types");
			while($row = mysqli_fetch_array ($querxy)) {
				$no_space_rate_card_types = str_replace(' ', '', $row['rate_card_types']);
				?>
				<a id="<?php echo $no_space_rate_card_types; ?>" class="btn brand-btn order_list_products mobile-100" ><?php echo $row['rate_card_types']; ?></a>
			<?php }

			$query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') OR $universal_rc_search) AND tile_name='Products'");

			$num_rows = mysqli_num_rows($query_rc);
			if($num_rows > 0) { ?>
				<div class="form-group clearfix products_heading">
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Type':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Heading':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
						break;
					case 'Description':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
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
					case 'Margin':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Profit':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Price':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Total':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Total Multiple':
						echo '<label class="col-sm-1 text-center">'.$data[1].' X 1</label>';
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
				<div class="form-group clearfix all_products <?php echo $no_space_rate_card_types; ?> rc_est_products_<?php echo $rc; ?>" width="100%">

					<input type="hidden" name="crc_products_companyrcid_<?php echo $rc; ?>" value="<?php echo $row_rc['companyrcid']; ?>" />

					<div class="col-sm-1">
						<input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_products_type_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-2">
						<input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_products_heading_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-2">
						<input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_products_description_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_products_uom_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input name="crc_products_qty_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="qtychangecrcvalue(this); countRCTotalProduct(this)" id="crc_products_qty_<?php echo $rc;?>" class="form-control crc_products_qty" />
					</div>
					<div class="col-sm-1">
						<input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_products_cost_<?php echo $rc; ?>" id='crc_products_cost_<?php echo $rc; ?>' type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input name="crc_products_margin_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['margin']; ?>" onchange="changeProfitRCPrice(this);" id="crc_products_margin_<?php echo $rc;?>" type="text" class="form-control product_rc_total" />
					</div>
					<div class="col-sm-1">
						<input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="fillmargincrcvalue(this); countRCTotalProduct(this);" name="crc_products_cust_price_<?php echo $rc; ?>" type="text" id="crc_products_custprice_<?php echo $rc;?>" class="form-control"/>
					</div>
					<div class="col-sm-1">
						<input name="crc_products_profit_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['profit']; ?>" onchange="changeProfitRCPrice(this);" id="crc_products_profit_<?php echo $rc;?>" type="text" class="form-control product_rc_total" />
					</div>
					<div class="col-sm-1">
						<input name="crc_products_total_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_products_total_<?php echo $rc;?>" type="text" class="form-control product_rc_total" />
					</div>
					<div class="col-sm-1">
						<input name="crc_products_total_multiple_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['total_multiple']; ?>"  id="crc_products_total_multiple_<?php echo $rc;?>" type="text" class="form-control" />
					</div>
				</div>

			<?php
				$rc++;
				$final_total_products += $estimate_company_rate_card['rc_total'];
				$final_total_products_profit += $estimate_company_rate_card['profit'];
				$final_total_products_margin += $estimate_company_rate_card['margin'];
				$final_total_products_cost += $estimate_company_rate_card['rc_total'] - $estimate_company_rate_card['profit'];
			}
		}
		?>
		<div class="form-group clearfix" style="margin-left:5px">
			<h3>Misc Items</h3>
			<div class="form-group clearfix hide-titles-mob">
				<?php $columns = count($field_order) + 1;
				foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					switch($data[0]) {
						case 'Description':
							echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
							break;
						case 'Type':
						case 'Heading':
						case 'UOM':
						case 'Quantity':
						case 'Cost':
						case 'Margin':
						case 'Price':
						case 'Profit':
						case 'Total':
						case 'Total Multiple':
							echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
							break;
					}
				endforeach; ?>
			</div>
			<div class="additional_p_misc clearfix">
				<div class="clearfix"></div>

				<div class="form-group clearfix" id="productsmisc_0">
				<?php $columns = count($field_order) + 1;
				foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					echo "<div class=\"col-sm-2\" data-columns='".$columns."' data-width='".($data[0] == 'Description' ? 2 : 1)."'>";
					switch($data[0]) {
						case 'Description':
							echo '<label class="col-sm-4 show-on-mob" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
							break;
						case 'Type':
						case 'Heading':
						case 'UOM':
						case 'Quantity':
						case 'Cost':
						case 'Margin':
						case 'Price':
						case 'Profit':
						case 'Total':
						case 'Total Multiple':
							echo '<label class="col-sm-2 show-on-mob" data-columns="'.$columns.'" data-width="1">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
							break;
					}
					switch($data[0]) {
						case 'Type': echo '<input name="ptype_misc[]" id="ptype_misc_0" type="text" class="form-control" />'; break;
						case 'Heading': echo '<input name="pheadmisc[]" id="pheadmisc_0" type="text" class="form-control" />'; break;
						case 'Description': echo '<input name="pdisc_misc[]" id="pdisc_misc_0" type="text" class="form-control" />'; break;
						case 'UOM': echo '<input name="puom_misc[]" id="puom_misc_0" type="text" class="form-control" />'; break;
						case 'Quantity': echo '<input name="pqtymisc[]" id="pqtymisc_0" type="text" class="form-control" onchange="countMiscProduct(this); qtychangemiscvalue(this);" />'; break;
						case 'Cost': echo '<input name="pcostmisc[]" id="pcostmisc_0" type="text" class="form-control" />'; break;
						case 'Margin': echo '<input name="pmarginmisc[]" id="pmarginmisc_0" onchange="changeTotal(); changeProfitMiscPrice(this)" type="text" class="form-control" />'; break;
						case 'Price': echo '<input name="pestimatepricemisc[]" id="pestimatepricemisc_0" type="text" class="form-control" onchange="countMiscProduct(this); fillmarginmiscvalue(this);" />'; break;
						case 'Profit': echo '<input name="pprofitmisc[]" id="pprofitmisc_0" onchange="changeTotal(); changeProfitMiscPrice(this);" type="text" class="form-control" />'; break;
						case 'Total': echo '<input name="ptotalmisc[]" id="ptotalmisc_0" type="text" class="form-control" />'; break;
						case 'Total Multiple': echo '<input name="ptotalmiscmulti[]" id="ptotalmiscmulti_0" type="text" class="form-control" />'; break;
					} ?>
				</div>
				<?php endforeach; ?>
				<a href="#" onclick="deleteEstimate(this,'productsmisc_','pheadmisc_'); return false;" id="deleteproductsmisc_0" class="btn brand-btn">Delete</a>
				<div style="display:none;">
					<?php if(!in_array_starts('Type', $field_order)): ?>
						<input name="ptype_misc[]" id="ptype_misc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Heading', $field_order)): ?>
						<input name="pheadmisc[]" id="pheadmisc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Description', $field_order)): ?>
						<input name="pdisc_misc[]" id="pdisc_misc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('UOM', $field_order)): ?>
						<input name="puom_misc[]" id="puom_misc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Quantity', $field_order)): ?>
						<input name="pqtymisc[]" id="pqtymisc_0" type="text" class="form-control" onchange="countMiscProduct(this); qtychangemiscvalue(this);" />
					<?php endif; ?>
					<?php if(!in_array_starts('Cost', $field_order)): ?>
						<input name="pcostmisc[]" id="pcostmisc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Margin', $field_order)): ?>
						<input name="pmarginmisc[]" id="pmarginmisc_0" onchange="changeTotal(); changeProfitMiscPrice(this)" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Price', $field_order)): ?>
						<input name="pestimatepricemisc[]" id="pestimatepricemisc_0" type="text" class="form-control" onchange="countMiscProduct(this); fillmarginmiscvalue(this);" />
					<?php endif; ?>
					<?php if(!in_array_starts('Profit', $field_order)): ?>
						<input name="pprofitmisc[]" id="pprofitmisc_0" onchange="changeTotal(); changeProfitMiscPrice(this);" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Total', $field_order)): ?>
						<input name="ptotalmisc[]" id="ptotalmisc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Total Multiple', $field_order)): ?>
						<input name="ptotalmiscmulti[]" id="ptotalmiscmulti_0" type="text" class="form-control" />
					<?php endif; ?>
					</div>
				</div>
			</div>
			
			<div id="add_here_new_p_misc"></div>
			
			<div class="form-group triple-gapped clearfix">
				<div class="col-sm-offset-4 col-sm-8">
					<button id="add_row_p_misc" class="btn brand-btn pull-left">Add Row</button>
				</div>
			</div>
            
            <br>
            <?php
            $query_misc_rc = mysqli_query($dbc,"SELECT * FROM cost_estimate_misc WHERE accordion='Product' AND estimateid=" . $_GET['estimateid']);
            //exit;

            $misc_num_rows = mysqli_num_rows($query_misc_rc);
            if($misc_num_rows > 0) { ?>
				<div class="form-group clearfix products_misc_heading hide-titles-mob">
					<?php $columns = count($field_order) + 1;
					foreach($field_order as $field_data):
						$data = explode('***',$field_data);
						if($data[1] == '') {
							$data[1] = $data[0];
						}
						switch($data[0]) {
							case 'Description':
								echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
								break;
							case 'Type':
							case 'Heading':
							case 'UOM':
							case 'Quantity':
							case 'Cost':
							case 'Margin':
							case 'Price':
							case 'Profit':
							case 'Total':
							case 'Total Multiple':
								echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
								break;
						}
					endforeach; ?>
				</div>
                <?php
            }
            
            $misc_rc = 0;
            while($misc_row_rc = mysqli_fetch_array($query_misc_rc)) { ?>
                <div class="clearfix"></div>

				<div class="form-group clearfix" id="productsmisc_0">
				<?php $columns = count($field_order) + 1;
				foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					echo "<div class=\"col-sm-2\" data-columns='".$columns."' data-width='".($data[0] == 'Description' ? 2 : 1)."'>";
					switch($data[0]) {
						case 'Description':
							echo '<label class="col-sm-4 show-on-mob" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
							break;
						case 'Type':
						case 'Heading':
						case 'UOM':
						case 'Quantity':
						case 'Cost':
						case 'Margin':
						case 'Price':
						case 'Profit':
						case 'Total':
						case 'Total Multiple':
							echo '<label class="col-sm-2 show-on-mob" data-columns="'.$columns.'" data-width="1">'.$data[1].($data[0] == 'Total Multiple' ? ' X 1' : '').'</label>';
							break;
					}
					switch($data[0]) {
						case 'Type': ?><input name="ptype_misc_display[]" id="ptype_misc" value="<?php echo $misc_row_rc['type'] ?>" readonly type="text" class="form-control" /><?php break;
						case 'Heading': ?><input name="pheadmisc_display[]" id="pheadmisc" value="<?php echo $misc_row_rc['heading'] ?>" type="text" readonly class="form-control" /><?php break;
						case 'Description': ?><input name="pdisc_misc_display[]" id="pdisc_misc" type="text" value="<?php echo $misc_row_rc['description'] ?>" readonly class="form-control" /><?php break;
						case 'UOM': ?><input name="puom_misc_display[]" id="puom_misc" type="text" value="<?php echo $misc_row_rc['uom'] ?>" readonly class="form-control" /><?php break;
						case 'Quantity': ?><input name="pqtymisc_display[]" id="pqtymisc" type="text" readonly class="form-control" value="<?php echo $misc_row_rc['qty'] ?>" onchange="countMiscProduct(this);" /><?php break;
						case 'Cost': ?><input name="pcostmisc_display[]" id="pcostmisc" type="text" value="<?php echo $misc_row_rc['cost'] ?>" readonly class="form-control" /><?php break;
						case 'Margin': ?><input name="pmarginmisc_display[]" id="pmarginmisc" value="<?php echo $misc_row_rc['margin'] ?>" readonly type="text" class="form-control" /><?php break;
						case 'Price': ?><input name="pestimatepricemisc_display[]" id="pestimatepricemisc" readonly value="<?php echo $misc_row_rc['estimate_price'] ?>" type="text" class="form-control" onchange="countMiscProduct(this);" /><?php break;
						case 'Profit': ?><input name="pprofitmisc_display[]" id="pprofitmisc" value="<?php echo $misc_row_rc['profit'] ?>" readonly type="text" class="form-control" /><?php break;
						case 'Total': ?><input name="ptotalmisc_display[]" id="ptotalmisc" value="<?php echo $misc_row_rc['total'] ?>" readonly type="text" class="form-control" /><?php break;
						case 'Total Multiple': ?><input name="ptotalmiscmulti_display[]" id="ptotalmiscmulti" value="<?php echo $misc_row_rc['total_multiple'] ?>" readonly type="text" class="form-control" /><?php break;
					} ?>
				</div>
				<?php endforeach; ?>
				<div style="display:none;">
					<?php if(!in_array_starts('Type', $field_order)): ?>
						<input name="ptype_misc_display[]" id="ptype_misc" value="<?php echo $misc_row_rc['type'] ?>" readonly type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Heading', $field_order)): ?>
						<input name="pheadmisc_display[]" id="pheadmisc" value="<?php echo $misc_row_rc['heading'] ?>" type="text" readonly class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Description', $field_order)): ?>
						<input name="pdisc_misc_display[]" id="pdisc_misc" type="text" value="<?php echo $misc_row_rc['description'] ?>" readonly class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('UOM', $field_order)): ?>
						<input name="puom_misc_display[]" id="puom_misc" type="text" value="<?php echo $misc_row_rc['uom'] ?>" readonly class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Quantity', $field_order)): ?>
						<input name="pqtymisc_display[]" id="pqtymisc" type="text" readonly class="form-control" value="<?php echo $misc_row_rc['qty'] ?>" onchange="countMiscProduct(this);" />
					<?php endif; ?>
					<?php if(!in_array_starts('Cost', $field_order)): ?>
						<input name="pcostmisc_display[]" id="pcostmisc" type="text" value="<?php echo $misc_row_rc['cost'] ?>" readonly class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Margin', $field_order)): ?>
						<input name="pmarginmisc_display[]" id="pmarginmisc" value="<?php echo $misc_row_rc['margin'] ?>" readonly type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Price', $field_order)): ?>
						<input name="pestimatepricemisc_display[]" id="pestimatepricemisc" readonly value="<?php echo $misc_row_rc['estimate_price'] ?>" type="text" class="form-control" onchange="countMiscProduct(this);" />
					<?php endif; ?>
					<?php if(!in_array_starts('Profit', $field_order)): ?>
						<input name="pprofitmisc_display[]" id="pprofitmisc" value="<?php echo $misc_row_rc['profit'] ?>" readonly type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Total', $field_order)): ?>
						<input name="ptotalmisc_display[]" id="ptotalmisc" value="<?php echo $misc_row_rc['total'] ?>" readonly type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Total Multiple', $field_order)): ?>
						<input name="ptotalmiscmulti_display[]" id="ptotalmisc" value="<?php echo $misc_row_rc['total_multiple'] ?>" readonly type="text" class="form-control" />
					<?php endif; ?>
					</div>
				</div>
            <?php
                $misc_rc++;
                $final_total_misc_product += $misc_row_rc['total'];
            }
            ?>
		</div>
    </div>
	
</div>

<input type="hidden" name="total_rc_products" value="<?php echo $rc; ?>" />

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Cost: </label>
    <div class="col-sm-8">
      <input name="product_cost" id="product_cost" value="<?php echo $final_total_product_cost; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
    <div class="col-sm-8">
      <input name="product_profit" id="product_profit" value="<?php echo $final_total_products_profit; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
    <div class="col-sm-8">
      <input name="product_profit_margin" id="product_profit_margin" value="<?php echo $final_total_products_margin; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="product_budget" value="<?php echo $budget_price[16]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Product Estimate:</label>
    <div class="col-sm-8">
      <input name="product_total" value="<?php echo $final_total_products + $final_total_misc_product;?>" type="text" class="form-control">
    </div>
</div>
