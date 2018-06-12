<?php
include ('../database_connection.php');
?>
<script>
$(document).ready(function() {
	//Expenses
    var add_new_e = 1;
    $('#deleteexpenses_0').hide();
    $('#add_row_e').on( 'click', function () {
        $('#deleteexpenses_0').show();
        var clone = $('.additional_e').clone();
        clone.find('.form-control').val('');

        clone.find('#eexpense_0').attr('id', 'eexpense_'+add_new_e);
        clone.find('#eheading_0').attr('id', 'eheading_'+add_new_e);
        clone.find('#ec_0').attr('id', 'ec_'+add_new_e);
		clone.find('#eestimateprice_0').attr('id', 'eestimateprice_'+add_new_e);
        clone.find('#eeprofit_0').attr('id', 'eeprofit_'+add_new_e);
        clone.find('#eeprofitmargin_0').attr('id', 'eeprofitmargin_'+add_new_e);
		clone.find('#eestimateqty_0').attr('id', 'eestimateqty_'+add_new_e);
		clone.find('#eestimateunit_0').attr('id', 'eestimateunit_'+add_new_e);
		clone.find('#eestimatetotal_0').attr('id', 'eestimatetotal_'+add_new_e);

        clone.find('#expenses_0').attr('id', 'expenses_'+add_new_e);
        clone.find('#deleteexpenses_0').attr('id', 'deleteexpenses_'+add_new_e);
        $('#deleteexpenses_0').hide();

        clone.removeClass("additional_e");
        $('#add_here_new_e').append(clone);

        resetChosen($("#eexpense_"+add_new_e));
        resetChosen($("#eheading_"+add_new_e));

        add_new_e++;

        return false;
    });
});
$(document).on('change', 'select.expense_heading_onchange', function() { selectExpenseHeading(this); });


function selectExpenseHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=e_head_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#ec_"+arr[1]).val(response);
		}
	});
}
function countExpense(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');
        var estqty = $('#eestimateqty_'+split_id[1]).val();
        if(estqty == null || estqty == '') {
            estqty = 1;
        }

        document.getElementById('eestimatetotal_'+split_id[1]).value = parseFloat($('#eestimateprice_'+split_id[1]).val() * estqty);
    }

    var sum_fee = 0;
    $('[name="eestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_expenses_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });

    $('[name="expense_total"]').val(round2Fixed(sum_fee));
    $('[name="expense_summary"]').val(round2Fixed(sum_fee));

    var expense_budget = $('[name="expense_budget"]').val();
    if(expense_budget >= sum_fee) {
        $('[name="expense_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="expense_total"]').css("background-color", "#ff9999"); // Green
    }
}

function fillmarginexpensevalue(est) {
    var idarray = est.id.split("_");
    var profitid = 'eeprofit_' + idarray[1];
    var profitmarginid = 'eeprofitmargin_' + idarray[1];
    var pcid = 'ec_' + idarray[1];
    var pcvalue = jQuery('#'+pcid).val();
    var pestimatevalue = est.value;
    var qty = jQuery('#eestimateqty_' + idarray[1]).val();
    if(qty == '' || qty == null) {
        jQuery('#eestimateqty_' + idarray[1]).val(1);
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

    changeExpenseTotal();
}

function qtychangexpenseevalue(qty) {
    var idarray = qty.id.split("_");
    var profitid = 'eeprofit_' + idarray[1];
    var profitmarginid = 'eeprofitmargin_' + idarray[1];
    var pestimateid = 'eestimateprice_' + idarray[1];
    var pcid = 'ec_' + idarray[1];
    var del = (jQuery('#'+pestimateid).val() - jQuery('#'+pcid).val()) * qty.value;
    var delper = (del / (jQuery('#'+pestimateid).val() * qty.value)) * 100;
    jQuery('#'+profitid).val(del);
    jQuery('#'+profitmarginid).val(delper.toFixed(2));
    changeExpenseTotal();
}

function changeExpenseTotal() {
    var sum_profit = 0;
    var sum_profit_margin = 0;
    jQuery('[name="eeprofit[]"]').each(function () {
        sum_profit += +$(this).val() || 0;
    });

    var count = 0;
    jQuery('[name="eeprofitmargin[]"]').each(function () {
        sum_profit_margin += +$(this).val() || 0;
        count++;
    });

    per_profit_margin = sum_profit_margin / count;

    jQuery('#expense_profit').val(round2Fixed(sum_profit));
    jQuery('#expense_profit_margin').val(round2Fixed(per_profit_margin));
}

</script>
<?php
$get_field_config_expense = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT expense FROM field_config"));
$field_config_expense = ','.$get_field_config_expense['expense'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <label class="col-sm-2 text-center">Heading</label>
            <?php if (strpos($field_config_expense, ','."Amount".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Cost</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Bid Price</label>
            <label class="col-sm-1 text-center">UOM</label>
            <label class="col-sm-1 text-center">Quantity</label>
            <label class="col-sm-1 text-center">Total</label>
            <label class="col-sm-1 text-center">$ Profit</label>
            <label class="col-sm-1 text-center">% Margin</label>

        </div>

       <?php
        $get_expenses = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_expenses'),'**#**');
                $get_expenses  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_expenses'),'**#**');
                $get_expenses  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_expenses'),'**#**');
                $get_expenses  .= '**'.$each_item;
            }
        }

        /*(if(!empty($_GET['estimateid'])) {
            $expenses = $get_contact['expenses'];
            $each_expensesid = explode('**',$expenses);
            foreach($each_expensesid as $id_all) {
                if($id_all != '') {
                    $expensesid_all = explode('#',$id_all);
                    $get_expenses .= '**'.$expensesid_all[0].'#'.$expensesid_all[2].'#'.$expensesid_all[1].'#'.$expensesid_all[3];
                }
            }
        }*/
        $final_total_expenses = 0;
        ?>

        <?php if(!empty($get_expenses)) {
            $each_assign_inventory = explode('**',$get_expenses);
            $total_count = mb_substr_count($get_expenses,'**');
            $id_loop = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $each_item = explode('#',$each_assign_inventory[$inventory_loop]);
                $expenseid = '';
                $qty = '';
                $est = '';
                $unit = '';
                if(isset($each_item[0])) {
                    $expenseid = $each_item[0];
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
                $final_total_expenses += $total;
                if($expenseid != '') {

                    $expenses = explode('**', $get_rc['expenses']);
                    $rc_price = 0;
                    foreach($expenses as $pp){
                        if (strpos('#'.$pp, '#'.$expenseid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>

            <div class="form-group clearfix" id="<?php echo 'expenses_'.$id_loop; ?>" >
                <?php if (strpos($field_config_expense, ','."Expense Heading".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Expense..." id="<?php echo 'eexpense_'.$id_loop; ?>" class="chosen-select-deselect form-control exepnseid expense_heading_onchange" width="380">
                        <option value=''></option>
                        <?php
                            $query = mysqli_query($dbc,"SELECT distinct(staff), title FROM expense WHERE deleted=0 order by title");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['staff']."'>".$row['title']. ':' . $row['staff'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($field_config_expense, ','."Amount".',') !== FALSE) { ?>
                    <div class="col-sm-1">
                        <input name="ec[]" id="<?php echo 'ec_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <?php if (strpos($field_config_expense, ','."Estimated Hours".',') !== FALSE) { ?>
                    <div class="col-sm-1" >
                        <input name="peh[]" value="<?php echo get_expenses($dbc, $expenseid, 'estimated_hours');?>" id="<?php echo 'eeh_'.$id_loop; ?>" readonly type="text" class="form-control" />
                    </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="eestimateprice[]" id="<?php echo 'eestimateprice_'.$id_loop; ?>" onchange="countExpense(this); fillmarginexpensevalue(this);" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eestimateunit[]" id="<?php echo 'eestimateunit_'.$id_loop; ?>" value="<?php echo $unit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eestimateqty[]" id="<?php echo 'eestimateqty_'.$id_loop; ?>" onchange="countExpense(this); qtychangexpenseevalue(this)" value="<?php echo $qty; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'eestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eeprofit[]" id="<?php echo 'eeprofit_'.$id_loop; ?>" readonly="" onchange="countExpense(this)" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eeprofitmargin[]" id="<?php echo 'eeprofitmargin_'.$id_loop; ?>" readonly="" onchange="countExpense(this)" value="<?php echo $est; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'expenses_','pheading_'); return false;" id="<?php echo 'deleteexpenses_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_e clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="expenses_0">
                <?php if (strpos($field_config_expense, ','."Expense Heading".',') !== FALSE) { ?>
                <div class="col-sm-2">
                    <select data-placeholder="Choose a Type..." id="eexpense_0" class="chosen-select-deselect form-control etype expense_heading_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(staff), title FROM expense WHERE deleted=0 order by title");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['staff']."'>".$row['title']. ':' . $row['staff'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($field_config_expense, ','."Amount".',') !== FALSE) { ?>
                <div class="col-sm-1">
                    <input name="ec[]" id="ec_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="eestimateprice[]" id="eestimateprice_0" onchange="countExpense(this); fillmarginexpensevalue(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eestimateunit[]" id='eestimateunit_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eestimateqty[]" id='eestimateqty_0' onchange="countExpense(this); qtychangexpenseevalue(this);" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eestimatetotal[]" id='eestimatetotal_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eeprofit[]" id='eeprofit_0' readonly="" onchange="countExpense(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="eeprofitmargin[]" id='eeprofitmargin_0' readonly="" onchange="countExpense(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'expenses_','eheading_'); return false;" id="deleteexpenses_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_e"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_e" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total $ Profit: </label>
    <div class="col-sm-8">
      <input name="expense_profit" id="expense_profit" value="" readonly="" type="text" class="form-control">
    </div>
</div>

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total % Margin: </label>
    <div class="col-sm-8">
      <input name="expense_profit_margin" id="expense_profit_margin" value="" readonly="" type="text" class="form-control">
    </div>
</div>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="expense_budget" value="<?php echo $budget_price[16]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Expense Bid:</label>
    <div class="col-sm-8">
      <input name="expense_total" value="<?php echo $final_total_expenses;?>" type="text" class="form-control">
    </div>
</div>
