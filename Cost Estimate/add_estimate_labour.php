<script>
$(document).ready(function() {
    $('.all_labour').hide();
    $('.labour_heading').hide();

	$('.order_list_labour').on( 'click', function () {
        var pro_type = $(this).attr("id");

        $('.all_labour').hide();
        $('.'+pro_type).show();
        $('.labour_heading').show();

		$('.order_list_labour').removeClass('active_tab');
        $(this).addClass('active_tab');
    });
	
	if($('.order_list_labour').length == 1 && '<?php echo $load_tab; ?>' != 'Master') {
		$('.order_list_labour').click();
	}

	//Services
    var add_new_l = 1;
    $('#deletelabour_0').hide();
    $('#add_row_l').on( 'click', function () {
        $('#deletelabour_0').show();
        var clone = $('.additional_l').clone();
        clone.find('.form-control').val('');

        clone.find('#labour_0').attr('id', 'labour_'+add_new_l);
        clone.find('#lheading_0').attr('id', 'lheading_'+add_new_l);
		clone.find('#lhr_0').attr('id', 'lhr_'+add_new_l);
        clone.find('#plc_0').attr('id', 'plc_'+add_new_l);
		clone.find('#lfinalprice_0').attr('id', 'lfinalprice_'+add_new_l);
		clone.find('#lestimateprice_0').attr('id', 'lestimateprice_'+add_new_l);
		clone.find('#lestimateqty_0').attr('id', 'lestimateqty_'+add_new_l);
        clone.find('#lprofit_0').attr('id', 'lprofit_'+add_new_l);
        clone.find('#lprofitmargin_0').attr('id', 'lprofitmargin_'+add_new_l);
		clone.find('#lestimateunit_0').attr('id', 'lestimateunit_'+add_new_l);
		clone.find('#lestimatetotal_0').attr('id', 'lestimatetotal_'+add_new_l);

        clone.find('#labourfull_0').attr('id', 'labourfull_'+add_new_l);
        clone.find('#deletelabour_0').attr('id', 'deletelabour_'+add_new_l);
        $('#deletelabour_0').hide();

        clone.removeClass("additional_l");
        $('#add_here_new_l').append(clone);

        resetChosen($("#labour_"+add_new_l));
        resetChosen($("#lheading_"+add_new_l));

        add_new_l++;

        return false;
    });
    
    var add_new_l_misc = 1;
	$('#deletelabourmisc_0').hide();
	$('#add_row_l_misc').on( 'click', function () {

		$('#deletelabourmisc_0').show();
        var clone_misc = $('.additional_l_misc').clone();
        clone_misc.find('.form-control').val('');
		clone_misc.find('#lid_misc_0').attr('id', 'lid_misc_'+add_new_l_misc);
        clone_misc.find('#ltype_misc_0').attr('id', 'ltype_misc_'+add_new_l_misc);
		clone_misc.find('#ldisc_misc_0').attr('id', 'ldisc_misc_'+add_new_l_misc);
		clone_misc.find('#luom_misc_0').attr('id', 'luom_misc_'+add_new_l_misc);
		clone_misc.find('#lheadmisc_0').attr('id', 'lheadmisc_'+add_new_l_misc);
		clone_misc.find('#lcostmisc_0').attr('id', 'lcostmisc_'+add_new_l_misc);
		clone_misc.find('#lqtymisc_0').attr('id', 'lqtymisc_'+add_new_l_misc);
		clone_misc.find('#ltotalmisc_0').attr('id', 'ltotalmisc_'+add_new_l_misc);
		clone_misc.find('#lestimatepricemisc_0').attr('id', 'lestimatepricemisc_'+add_new_l_misc);
		clone_misc.find('#lmarginmisc_0').attr('id', 'lmarginmisc_'+add_new_l_misc);
		clone_misc.find('#lprofitmisc_0').attr('id', 'lprofitmisc_'+add_new_l_misc);
        clone_misc.find('#labourmisc_0').attr('id', 'labourmisc_'+add_new_l_misc);
        clone_misc.find('#deletelabourmisc_0').attr('id', 'deletelabourmisc_'+add_new_l_misc);
        $('#deletelabourmisc_0').hide();

        clone_misc.removeClass("additional_l_misc");

        $('#add_here_new_l_misc').append(clone_misc);

        add_new_l_misc++;

        return false;
    });
	changelabourTotal();
});

//Services
function selectLabour(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=labour_type_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#lheading_"+arr[1]).html(response);
			$("#lheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectLabourHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=l_head_config&value="+stage+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#lhr_"+arr[1]).val(result[0]);
			$("#lfinalprice_"+arr[1]).val(result[1]);
            $("#plc_"+arr[1]).val(result[2]);

		}
	});
}
function countLabour(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');
        var lbqty = $('#lestimateqty_'+split_id[1]).val();
        if(lbqty == null || lbqty == '') {
            lbqty = 1;
        }

        document.getElementById('lestimatetotal_'+split_id[1]).value = round2Fixed(parseFloat($('#lestimateprice_'+split_id[1]).val() * lbqty));
    }

    
    var sum_fee = 0;
    $('[name="lestimatetotal[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
    $('[name="ltotalmisc[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
    $('[name="ltotalmisc_display[]"]').each(function () {
        sum_fee += Number($(this).val());
    });
    
    $('[name="labour_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="labour_summary"]').val('$'+round2Fixed(sum_fee)).change();

    var labour_budget = $('[name="labour_budget"]').val();
    if(labour_budget >= sum_fee) {
        $('[name="labour_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="labour_total"]').css("background-color", "#ff9999"); // Green
    }
}

function fillmargincrclabourvalue(est) {
	var idarray = est.id.split("_");
    var profitid = 'crc_labour_profit_' + idarray[3];
    var profitmarginid = 'crc_labour_margin_' + idarray[3];
    var pcid = 'crc_labour_cost_' + idarray[3];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#crc_labour_qty_' + idarray[3]).val();
    if(qty == '' || qty == null) {
        jQuery('#crc_labour_qty_' + idarray[3]).val(1);
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
    
    changelabourTotal();
}

function qtychangecrcvalueLabour(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'crc_labour_profit_' + idarray[3];
    var profitmarginid = 'crc_labour_margin_' + idarray[3];
    var pestimateid = 'crc_labour_custprice_' + idarray[3];
    var pcid = 'crc_labour_cost_' + idarray[3];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
	changelabourTotal();
}

function filllabourmarginvalue(est) {
    var idarray = est.id.split("_");
    var profitid = 'lprofit_' + idarray[1];
    var profitmarginid = 'lprofitmargin_' + idarray[1];
    var pcid = 'plc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#lestimateqty_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#lestimateqty_' + idarray[1]).val(1);
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

    changelabourTotal();
}

function qtychangelabourvalue(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'lprofit_' + idarray[1];
    var profitmarginid = 'lprofitmargin_' + idarray[1];
    var pestimateid = 'lestimateprice_' + idarray[1];
    var pcid = 'plc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
    changelabourTotal();
}

function qtychangemiscvalueLabour(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'lprofitmisc_' + idarray[1];
    var profitmarginid = 'lmarginmisc_' + idarray[1];
    var pestimateid = 'lestimatepricemisc_' + idarray[1];
    var pcid = 'lcostmisc_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(round2Fixed(del));
    jQuery('#'+profitmarginid).val(round2Fixed(delper));
	changelabourTotal();
}

function fillmarginmiscvalueLabour(est) {
	var idarray = est.id.split("_");
    var profitid = 'lprofitmisc_' + idarray[1];
    var profitmarginid = 'lmarginmisc_' + idarray[1];
    var pcid = 'lcostmisc_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#lqtymisc_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#lqtymisc_' + idarray[1]).val(1);
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
	changelabourTotal();
}

function changelabourTotal() {
    var sum_profit = 0;
	var sum_total = 0;
	var sum_cost = 0;
	$('[name="ltotalmisc[]"]').each(function(key) {
		qty = +$($('[name="lqtymisc[]"]')[key]).val() || 0
		sum_cost += (+$($('[name="lcostmisc[]"]')[key]).val() || 0) * qty;
		sum_total += +$(this).val() || 0;
	});
	$('[name="ltotalmisc_display[]"]').each(function(key) {
		qty = +$($('[name="lqtymisc_display[]"]')[key]).val() || 0
		sum_cost += (+$($('[name="lcostmisc_display[]"]')[key]).val() || 0) * qty;
		sum_total += +$(this).val() || 0;
	});
	$('[name="lestimatetotal[]"]').each(function(key) {
		qty = +$($('[name="lestimateqty[]"]')[key]).val() || 0
		sum_cost += (+$($('[name="plc[]"]')[key]).val() || 0) * qty;
		sum_total += +$(this).val() || 0;
	});
	$('[name^=crc_labour_total_]').each(function(key) {
		qty = +$($('[name^=crc_labour_qty_]')[key]).val() || 0
		sum_cost += (+$($('[name^=crc_labour_cost_]')[key]).val() || 0) * qty;
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

    jQuery('#labour_profit').val('$'+round2Fixed(sum_profit));
    jQuery('#labour_profit_margin').val(round2Fixed(per_profit_margin));
    jQuery('#labour_cost').val('$'+round2Fixed(sum_total - sum_profit));
    jQuery('[name=labour_total]').val('$'+round2Fixed(sum_total));
    jQuery('[name=labour_summary_profit]').val('$'+round2Fixed(sum_profit));
    jQuery('[name=labour_summary_margin]').val(round2Fixed(per_profit_margin));
    jQuery('[name=labour_summary_cost]').val('$'+round2Fixed(sum_total - sum_profit));
    jQuery('[name=labour_summary]').val('$'+round2Fixed(sum_total)).change();
	
    var labour_budget = $('[name="labour_budget"]').val();
	if(labour_budget >= sum_total) {
        $('[name="labour_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="labour_total"]').css("background-color", "#ff9999"); // Green
    }
}

function countRCTotalLabour(sel) {
	var stage = sel.value;
	var typeId = sel.id;

	var arr = typeId.split('_');
    var del = (jQuery('#crc_labour_custprice_'+arr[3]).val() * jQuery('#crc_labour_qty_'+arr[3]).val());
    jQuery('#crc_labour_total_'+arr[3]).val(round2Fixed(del));

    var sum_fee = 0;
    var crc_sum_fee = 0;
    $('[name="lestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    for(var loop = 0; loop < 500; loop++) {
        if(typeof $('[name="crc_labour_total_'+loop+'"]').val() !='undefined')
        {
            crc_sum_fee += +$('[name="crc_labour_total_'+loop+'"]').val();
        }
        else {
            break;
        }
    }

    sum_fee += +crc_sum_fee;

    $('[name="labour_total"]').val(round2Fixed(sum_fee));

}
function countMiscLabour(txb)
{

	var get_id = txb.id;

	var split_id = get_id.split('_');
	if(split_id[0] == 'lestimatepricemisc') {
		var estqty = $('#lqtymisc_'+split_id[1]).val();
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('lqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('ltotalmisc_'+split_id[1]).value = parseFloat($('#lestimatepricemisc_'+split_id[1]).val() * estqty);
	}
	
	if(split_id[0] == 'lqtymisc') {
		var estqty = txb.value;
		if(estqty == null || estqty == '') {
			estqty = 1;
			document.getElementById('lqtymisc_'+split_id[1]).value = 1;
		}

		document.getElementById('ltotalmisc_'+split_id[1]).value = parseFloat($('#lestimatepricemisc_'+split_id[1]).val() * estqty);
	}
	
    //var sum_fee = 0;
    /*$('[name="lestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_labour_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });*/
    //$('[name="ltotalmisc[]"]').each(function () {
        //sum_fee += +document.getElementById('ltotalmisc_'+split_id[1]).value || 0;
    //});
    /*sum_fee += +$('[name="labour_total"]').val();
    $('[name="labour_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="labour_summary"]').val('$'+round2Fixed(sum_fee));*/

    /*var labour_budget = $('[name="labour_budget"]').val();
    if(labour_budget >= sum_fee) {
        $('[name="labour_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="labour_total"]').css("background-color", "#ff9999"); // Green
    }*/
}
function changeProfitLabourPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    qty = jQuery('#lestimateqty_' + split_id[1]).val();
    pcost = 'lc_' + split_id[1];
    pestimateid = 'lestimateprice_' + split_id[1];
    ptotal = 'ltotalmisc_' + split_id[1];
    profitid = 'lprofit_' + split_id[1];
    marginid = 'lprofitmargin_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'lprofit')
        {
			estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
			estimateTotal = estimateValue * qty;
			estimateMargin = profit.value / (estimateValue * qty) * 100;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+marginid).val(round2Fixed(estimateMargin));
        }
        
        if(split_id[0] == 'lprofitmargin')
        {
			estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
			estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
			estimateTotal = estimateValue * qty;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+profitid).val(round2Fixed(estimateProfit));
        }
    }
    
    changelabourTotal();
}

function changeProfitLabourRCPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    qty = jQuery('#crc_labour_qty_' + split_id[3]).val();
    pcost = 'crc_labour_cost_' + split_id[3];
    pestimateid = 'crc_labour_custprice_' + split_id[3];
    ptotal = 'crc_labour_total_' + split_id[3];
    profitid = 'crc_labour_profit_' + split_id[3];
    marginid = 'crc_labour_margin_' + split_id[3];
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
    
    changelabourTotal();
}

function changeProfitLabourMiscPrice(profit)
{
    var get_id = profit.id;
    var split_id = get_id.split('_');
    qty = jQuery('#lqtymisc_' + split_id[1]).val();
    pcost = 'lcostmisc_' + split_id[1];
    pestimateid = 'lestimatepricemisc_' + split_id[1];
    ptotal = 'ltotalmisc_' + split_id[1];
    profitid = 'lprofitmisc_' + split_id[1];
    marginid = 'lmarginmisc_' + split_id[1];
    var estimateValue = 0;
    if(jQuery('#'+pcost).val() != '') {
        if(split_id[0] == 'lprofitmisc')
        {
			estimateValue = parseFloat(profit.value) / qty + parseFloat(jQuery('#'+pcost).val());
			estimateTotal = estimateValue * qty;
			estimateMargin = profit.value / (estimateValue * qty) * 100;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+marginid).val(round2Fixed(estimateMargin));
        }
        
        if(split_id[0] == 'lmarginmisc')
        {
			estimateValue = (parseFloat(jQuery('#' + pcost).val()) / (1 - parseFloat(profit.value) / 100));
			estimateProfit = (estimateValue - parseFloat(jQuery('#'+pcost).val())) * qty;
			estimateTotal = estimateValue * qty;
			jQuery('#'+pestimateid).val(round2Fixed(estimateValue));
			jQuery('#'+ptotal).val(round2Fixed(estimateTotal));
			jQuery('#'+profitid).val(round2Fixed(estimateProfit));
        }
    }
    
    changelabourTotal();
}
</script>
<?php
$get_field_config_labour = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour FROM field_config"));
$field_config_labour = ','.$get_field_config_labour['labour'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
			<?php $columns = 10;
			$columns += (strpos($base_field_config, ','."Labour Type".',') !== FALSE) * 2;
			$columns += (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE);
			$columns += (strpos($field_config_labour, ','."Cost".',') !== FALSE);
			$columns += (in_array_starts('Total Multiple', $field_order)); ?>
            <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center" data-columns="<?= $columns ?>" data-width="2">Labour Type</label>
            <?php } ?>
            <label class="col-sm-2 text-center" data-columns="<?= $columns ?>" data-width="2">Heading</label>
            <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Hourly Rate</label>
            <?php } ?>
            <label class="col-sm-1 text-center" data-columns="<?= $columns ?>" data-width="1">Rate Card Price</label>
			<?php foreach($field_order as $field_data):
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Cost':
						if (strpos($field_config_labour, ','."Cost".',') !== FALSE) { ?>
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
        $get_labour = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_labour'),'**#**');
                $get_labour  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_labour'),'**#**');
                $get_labour  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_labour'),'**#**');
                $get_labour  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['estimateid'])) {
            $labour = $get_contact['labour'];
            $each_data = explode('**',$labour);
            foreach($each_data as $id_all) {
                if($id_all != '') {
                    $data_all = explode('#',$id_all);
                    $get_labour .= '**'.$data_all[0].'#'.$data_all[2].'#'.$data_all[1].'#'.$data_all[3].'#'.$data_all[4].'#'.$data_all[5];
                }
            }
        }
        $final_total_labour = 0;
        $final_total_labour_profit = 0;
        $final_total_labour_margin = 0;
        $final_total_labour_cost = 0;
        ?>

        <?php if(!empty($get_labour)) {
            $each_assign_labour = explode('**',$get_labour);
            $total_count = mb_substr_count($get_labour,'**');
            $id_loop = 500;
            for($labour_loop=0; $labour_loop<=$total_count; $labour_loop++) {

                $each_item = explode('#',$each_assign_labour[$labour_loop]);
                $labourid = '';
                $qty = '';
                $est = '';
                $unit = '';
				$totalmulti = '';
                if(isset($each_item[0])) {
                    $labourid = $each_item[0];
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
                $final_total_labour += $total;

                if($labourid != '') {
                    $labour = explode('**', $get_rc['labour']);
                    $rc_price = 0;
                    foreach($labour as $pp){
                        if (strpos('#'.$pp, '#'.$labourid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>

            <div class="form-group clearfix" id="<?php echo 'labourfull_'.$id_loop; ?>">
                <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?= $columns ?>" data-width="2">
                    <select onChange='selectLabour(this)' data-placeholder="Choose a Labour Type..." id="<?php echo 'labour_'.$id_loop; ?>" class="chosen-select-deselect form-control labourid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(labour_type) FROM labour WHERE deleted=0 order by labour_type");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_labour($dbc, $labourid, 'labour_type') == $row['labour_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['labour_type']."'>".$row['labour_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2" data-columns="<?= $columns ?>" data-width="2">
                    <select onChange='selectLabourHeading(this)' data-placeholder="Choose a Heading..." id="<?php echo 'lheading_'.$id_loop; ?>" name="labourid[]" class="chosen-select-deselect form-control labourid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT labourid, heading FROM labour WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            if ($labourid == $row['labourid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['labourid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <input name="lhr[]" value="<?php echo get_labour($dbc, $labourid, 'hourly_rate');?>" id="<?php echo 'lhr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <input name="lfinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'lfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<?php foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					} ?>
					<?php switch($data[0]) {
						case 'UOM': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimateunit[]" value="<?php echo $unit; ?>" id="<?php echo 'lestimateunit_'.$id_loop; ?>"  type="text" class="form-control" />
						</div>
							<?php break;
						case 'Quantity': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimateqty[]" value="<?php echo $qty; ?>" id="<?php echo 'lestimateqty_'.$id_loop; ?>"  onchange="countLabour(this); qtychangelabourvalue(this);" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Cost': 
							if (strpos($field_config_labour, ','."Cost".',') !== FALSE) { ?>
								<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
									<input name="plc[]" value="<?php echo get_labour($dbc, $labourid, 'cost');?>" id="<?php echo 'plc_'.$id_loop; ?>" readonly type="text" class="form-control" />
								</div>
							<?php }
							break;
						case 'Margin': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lprofitmargin[]" id="<?php echo 'lprofitmargin_'.$id_loop; ?>" onchange="changeProfitLabourPrice(this)" value="<?php echo $est; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Price': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimateprice[]" value="<?php echo $est; ?>" id="<?php echo 'lestimateprice_'.$id_loop; ?>" onchange="countLabour(this); filllabourmarginvalue(this);" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Profit': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lprofit[]" id="<?php echo 'lprofit_'.$id_loop; ?>" onchange="changeProfitLabourPrice(this)" value="<?php echo $est; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'lestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total Multiple': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimatetotalmulti[]" value="<?php echo $totalmulti; ?>" id="<?php echo 'lestimatetotalmulti_'.$id_loop; ?>" type="text" class="form-control" />
						</div>
							<?php break;
					}
				endforeach; ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <a href="#" onclick="deleteEstimate(this,'labourfull_','lheading_'); return false;" id="<?php echo 'deletelabour_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_l clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="labourfull_0">
                <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
                <div class="col-sm-2" data-columns="<?= $columns ?>" data-width="2">
                    <select onChange='selectLabour(this)' data-placeholder="Choose a Labour Type..." id="labour_0" class="chosen-select-deselect form-control labourid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(labour_type) FROM labour WHERE deleted=0 order by labour_type");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['labour_type']."'>".$row['labour_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2" data-columns="<?= $columns ?>" data-width="2">
                    <select onChange='selectLabourHeading(this)' data-placeholder="Choose a Heading..." id="lheading_0" name="labourid[]" class="chosen-select-deselect form-control labourid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT labourid, heading FROM labour WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['labourid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <input name="lhr[]" id="lhr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <input name="lfinalprice[]" readonly id="lfinalprice_0" type="text" class="form-control" />
                </div>
				<?php foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					} ?>
					<?php switch($data[0]) {
						case 'UOM': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimateunit[]" id='lestimateunit_0' type="text" class="form-control" />
						</div>
							<?php break;
						case 'Quantity': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimateqty[]" id='lestimateqty_0' onchange="countLabour(this); qtychangelabourvalue(this);" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Cost': 
							if (strpos($field_config_labour, ','."Cost".',') !== FALSE) { ?>
								<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
									<input name="plc[]" id="plc_0" readonly type="text" class="form-control" />
								</div>
							<?php }
							break;
						case 'Margin': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lprofitmargin[]" id='lprofitmargin_0' onchange="changeProfitLabourPrice(this)" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Price': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimateprice[]" id='lestimateprice_0' onchange="countLabour(this); filllabourmarginvalue(this);" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Profit': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lprofit[]" id='lprofit_0' onchange="changeProfitLabourPrice(this)" type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimatetotal[]" id='lestimatetotal_0' type="text" class="form-control" />
						</div>
							<?php break;
						case 'Total Multiple': ?>
						<div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
							<input name="lestimatetotalmulti[]" id='lestimatetotalmulti_0' type="text" class="form-control" />
						</div>
							<?php break;
					}
				endforeach; ?>
                <div class="col-sm-1" data-columns="<?= $columns ?>" data-width="1">
                    <a href="#" onclick="deleteEstimate(this,'labourfull_','lheading_'); return false;" id="deletelabour_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_l"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_l" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
		<?php
		if(!empty($_GET['estimateid'])) {

			if($load_tab == 'Master') {
				$types = "AND `deleted` = 0";
			}
			else {
				$types = "AND '$estimateConfigValue' LIKE CONCAT('%,Labour',rate_card_types,',%') AND `deleted` = 0";
			}
			$querxy = mysqli_query ($dbc, "SELECT DISTINCT(rate_card_types) FROM ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') OR rate_card_name='') AND tile_name='Labour' $types");
			while($row = mysqli_fetch_array ($querxy)) {
				$no_space_rate_card_types = str_replace(' ', '', $row['rate_card_types']);
				$no_space_rate_card_types = str_replace('&', '', $no_space_rate_card_types);
				?>
				<a id="<?php echo $no_space_rate_card_types; ?>" class="btn brand-btn order_list_labour mobile-100" ><?php echo $row['rate_card_types']; ?></a>
			<?php }

			$query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories') OR $universal_rc_search) AND tile_name='Labour'");

			$num_rows = mysqli_num_rows($query_rc);
			if($num_rows > 0) { ?>
				<div class="form-group clearfix labour_heading">
				<?php $columns = count($field_order) + (in_array_starts('Heading', $field_order)) + (in_array_starts('Description', $field_order));
				foreach($field_order as $field_data) {
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					switch($data[0]) {
						case 'Type':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Heading':
							echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
							break;
						case 'Description':
							echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
							break;
						case 'UOM':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Quantity':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Cost':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Margin':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Profit':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Price':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Total':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
							break;
						case 'Total Multiple':
							echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].' X 1</label>';
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
				$no_space_rate_card_types = str_replace('&', '', $no_space_rate_card_types);

				?>

				<div class="form-group clearfix all_labour <?php echo $no_space_rate_card_types; ?> rc_est_labour_<?php echo $rc; ?>" width="100%">
					<input type="hidden" name="crc_labour_companyrcid_<?php echo $rc; ?>" value="<?php echo $row_rc['companyrcid']; ?>" />

					<div class="col-sm-1">
						<input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_labour_type_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-2">
						<input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_labour_heading_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-2">
						<input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_labour_description_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_labour_uom_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input name="crc_labour_qty_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="qtychangecrcvalueLabour(this); countRCTotalLabour(this)" id="crc_labour_qty_<?php echo $rc;?>" class="form-control crc_labour_qty" />
					</div>
					<div class="col-sm-1">
						<input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_labour_cost_<?php echo $rc; ?>" id="crc_labour_cost_<?php echo $rc; ?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input name="crc_labour_margin_<?php echo $rc; ?>" onchange="changeProfitLabourRCPrice(this);" value= "<?php echo $estimate_company_rate_card['margin']; ?>"  id="crc_labour_margin_<?php echo $rc;?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="fillmargincrclabourvalue(this); countRCTotalLabour(this)" name="crc_labour_cust_price_<?php echo $rc; ?>" type="text" id="crc_labour_custprice_<?php echo $rc;?>" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input name="crc_labour_profit_<?php echo $rc; ?>" onchange="changeProfitLabourRCPrice(this);" value= "<?php echo $estimate_company_rate_card['profit']; ?>"  id="crc_labour_profit_<?php echo $rc;?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input name="crc_labour_total_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_labour_total_<?php echo $rc;?>" type="text" class="form-control" />
					</div>
					<div class="col-sm-1">
						<input name="crc_labour_total_multiple_<?php echo $rc; ?>" value= "<?php echo $estimate_company_rate_card['total_multiple']; ?>"  id="crc_labour_total_multiple_<?php echo $rc;?>" type="text" class="form-control" />
					</div>
				</div>

			<?php
				$rc++;
				$final_total_labour += $estimate_company_rate_card['rc_total'];
				$final_total_labour_profit += $estimate_company_rate_card['profit'];
				$final_total_labour_margin += $estimate_company_rate_card['margin'];
				$final_total_labour_cost += $estimate_company_rate_card['rc_total'] - $estimate_company_rate_card['profit'];
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
					case 'Type':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Heading':
						echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
						break;
					case 'Description':
						echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
						break;
					case 'UOM':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Quantity':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Cost':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Margin':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Profit':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Price':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Total':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Total Multiple':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].' X 1</label>';
						break;
				}
			} ?>
			</div>
			<div class="additional_l_misc clearfix">
				<div class="clearfix"></div>

				<div class="form-group clearfix" id="labourmisc_0">
				<?php foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					echo "<div class=\"col-sm-2\" data-columns='".$columns."' data-width='".($data[0] == 'Description' ? 2 : 1)."'>";
					switch($data[0]) {
						case 'Type': echo '<input name="ltype_misc[]" id="ltype_misc_0" type="text" class="form-control" />'; break;
						case 'Heading': echo '<input name="lheadmisc[]" id="lheadmisc_0" type="text" class="form-control" />'; break;
						case 'Description': echo '<input name="ldisc_misc[]" id="ldisc_misc_0" type="text" class="form-control" />'; break;
						case 'UOM': echo '<input name="luom_misc[]" id="luom_misc_0" type="text" class="form-control" />'; break;
						case 'Quantity': echo '<input name="lqtymisc[]" id="lqtymisc_0" type="text" class="form-control" onchange="countMiscLabour(this); qtychangemiscvalueLabour(this);" />'; break;
						case 'Cost': echo '<input name="lcostmisc[]" id="lcostmisc_0" type="text" class="form-control" />'; break;
						case 'Margin': echo '<input name="lmarginmisc[]" id="lmarginmisc_0" type="text" onchange="changeProfitLabourMiscPrice(this);" class="form-control" />'; break;
						case 'Price': echo '<input name="lestimatepricemisc[]" id="lestimatepricemisc_0" type="text" class="form-control" onchange="countMiscLabour(this); fillmarginmiscvalueLabour(this);" />'; break;
						case 'Profit': echo '<input name="lprofitmisc[]" id="lprofitmisc_0" type="text" onchange="changeProfitLabourMiscPrice(this);" class="form-control" />'; break;
						case 'Total': echo '<input name="ltotalmisc[]" id="ltotalmisc_0" type="text" class="form-control" />'; break;
						case 'Total Multiple': echo '<input name="ltotalmiscmulti[]" id="ltotalmiscmulti_0" type="text" class="form-control" />'; break;
					} ?>
				</div>
				<?php endforeach; ?>
				<a href="#" onclick="deleteEstimate(this,'labourmisc_','lheadmisc_'); return false;" id="deletelabourmisc_0" class="btn brand-btn">Delete</a>
				<div style="display:none;">
					<?php if(!in_array_starts('Type', $field_order)): ?>
						<input name="ltype_misc[]" id="ltype_misc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Heading', $field_order)): ?>
						<input name="lheadmisc[]" id="lheadmisc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Description', $field_order)): ?>
						<input name="ldisc_misc[]" id="ldisc_misc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('UOM', $field_order)): ?>
						<input name="luom_misc[]" id="luom_misc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Quantity', $field_order)): ?>
						<input name="lqtymisc[]" id="lqtymisc_0" type="text" class="form-control" onchange="countMiscLabour(this); qtychangemiscvalueLabour(this);" />
					<?php endif; ?>
					<?php if(!in_array_starts('Cost', $field_order)): ?>
						<input name="lcostmisc[]" id="lcostmisc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Margin', $field_order)): ?>
						<input name="lmarginmisc[]" id="lmarginmisc_0" type="text" onchange="changeProfitLabourMiscPrice(this);" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Price', $field_order)): ?>
						<input name="lestimatepricemisc[]" id="lestimatepricemisc_0" type="text" class="form-control" onchange="countMiscLabour(this); fillmarginmiscvalueLabour(this);" />
					<?php endif; ?>
					<?php if(!in_array_starts('Profit', $field_order)): ?>
						<input name="lprofitmisc[]" id="lprofitmisc_0" type="text" onchange="changeProfitLabourMiscPrice(this);" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Total', $field_order)): ?>
						<input name="ltotalmisc[]" id="ltotalmisc_0" type="text" class="form-control" />
					<?php endif; ?>
					<?php if(!in_array_starts('Total Multiple', $field_order)): ?>
						<input name="ltotalmiscmulti[]" id="ltotalmiscmulti_0" type="text" class="form-control" />
					<?php endif; ?>
					</div>
				</div>
			</div>
			
			<div id="add_here_new_l_misc"></div>
			
			<div class="form-group triple-gapped clearfix">
				<div class="col-sm-offset-4 col-sm-8">
					<button id="add_row_l_misc" class="btn brand-btn pull-left">Add Row</button>
				</div>
			</div>
            
            <br>
            <?php
            $query_misc_rc = mysqli_query($dbc,"SELECT * FROM cost_estimate_misc WHERE accordion='Labour' AND estimateid=" . $_GET['estimateid']);
            //exit;

            $misc_num_rows = mysqli_num_rows($query_misc_rc);
            if($misc_num_rows > 0) { ?>
                <div class="form-group clearfix products_misc_heading">
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Type':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Heading':
						echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
						break;
					case 'Description':
						echo '<label class="col-sm-2 text-center" data-columns="'.$columns.'" data-width="2">'.$data[1].'</label>';
						break;
					case 'UOM':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Quantity':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Cost':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Margin':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Profit':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Price':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Total':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].'</label>';
						break;
					case 'Total Multiple':
						echo '<label class="col-sm-1 text-center" data-columns="'.$columns.'" data-width="1">'.$data[1].' X 1</label>';
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
				<?php foreach($field_order as $field_data):
					$data = explode('***',$field_data);
					if($data[1] == '') {
						$data[1] = $data[0];
					}
					echo "<div class=\"col-sm-2\" data-columns='".$columns."' data-width='".($data[0] == 'Description' ? 2 : 1)."'>";
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
					<div class="col-sm-1">
						<input name="ltype_misc_display[]" id="ltype_misc" value="<?php echo $misc_row_rc['type'] ?>" readonly type="text" class="form-control" />
					</div>
					
					<div class="col-sm-2">
						<input name="lheadmisc_display[]" id="lheadmisc" value="<?php echo $misc_row_rc['heading'] ?>" type="text" readonly class="form-control" />
					</div>
					
					<div class="col-sm-2">
						<input name="ldisc_misc_display[]" id="ldisc_misc" type="text" value="<?php echo $misc_row_rc['description'] ?>" readonly class="form-control" />
					</div>

					<div class="col-sm-1">
						<input name="luom_misc_display[]" id="luom_misc" type="text" value="<?php echo $misc_row_rc['uom'] ?>" readonly class="form-control" />
					</div>
					
					<div class="col-sm-1">
						<input name="lqtymisc_display[]" id="lqtymisc" type="text" readonly class="form-control" value="<?php echo $misc_row_rc['qty'] ?>" onchange="countMiscLabour(this);" />
					</div>
					
					<div class="col-sm-1">
						<input name="lcostmisc_display[]" id="lcostmisc" type="text" value="<?php echo $misc_row_rc['cost'] ?>" readonly class="form-control" />
					</div>
                    
                    <div class="col-sm-1">
						<input name="lmarginmisc_display[]" id="lmarginmisc" value="<?php echo $misc_row_rc['margin'] ?>" readonly type="text" class="form-control" />
					</div>
					
					
					<div class="col-sm-1">
						<input name="lestimatepricemisc_display[]" id="lestimatepricemisc" readonly value="<?php echo $misc_row_rc['estimate_price'] ?>" type="text" class="form-control" />
					</div>
                    
                    <div class="col-sm-1">
						<input name="lprofitmisc_display[]" id="lprofitmisc" value="<?php echo $misc_row_rc['profit'] ?>" readonly type="text" class="form-control" />
					</div>
					
					<div class="col-sm-1">
						<input name="ltotalmisc_display[]" id="ltotalmisc" value="<?php echo $misc_row_rc['total'] ?>" readonly type="text" class="form-control" />
					</div>
					
					<div class="col-sm-1">
						<input name="ltotalmiscmulti_display[]" id="ltotalmiscmulti" value="<?php echo $misc_row_rc['total_multiple'] ?>" readonly type="text" class="form-control" />
					</div>
				</div>
            <?php
                $misc_rc++;
                $final_total_misc_labour += $misc_row_rc['total'];
            }
            ?>
		</div>
    </div>
</div>

<input type="hidden" name="total_rc_labour" value="<?php echo $rc; ?>" />

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Cost: </label>
    <div class="col-sm-8">
      <input name="labour_cost" id="labour_cost" value="<?php echo $final_total_labour_cost; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
    <div class="col-sm-8">
        <input name="labour_profit" id="labour_profit" value="<?php echo $final_total_labour_profit; ?>" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
    <div class="col-sm-8">
        <input name="labour_profit_margin" id="labour_profit_margin" value="<?php echo $final_total_labour_margin; ?>"  value="" readonly="" type="text" class="form-control">
    </div>
</div>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="labour_budget" value="<?php echo $budget_price[13]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="labour_total" value="<?php echo $final_total_labour + $final_total_misc_labour;?>" type="text" class="form-control">
    </div>
</div>
