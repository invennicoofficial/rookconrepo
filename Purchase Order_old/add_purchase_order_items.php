<div class="form-group clearfix">
    <?php if (strpos($value_config, ','."Qty".',') !== FALSE) { ?>
    <label class="col-sm-1 text-center">Qty</label>
    <?php } ?>
    <?php if (strpos($value_config, ','."Desc".',') !== FALSE) { ?>
    <label class="col-sm-2 text-center">Desc</label>
    <?php } ?>
    <?php if (strpos($value_config, ','."Grade".',') !== FALSE) { ?>
    <label class="col-sm-2 text-center">Grade</label>
    <?php } ?>
    <?php if (strpos($value_config, ','."Tag".',') !== FALSE) { ?>
    <label class="col-sm-1 text-center">Tag</label>
    <?php } ?>
    <?php if (strpos($value_config, ','."Detail".',') !== FALSE) { ?>
    <label class="col-sm-2 text-center">Detail</label>
    <?php } ?>
    <?php if (strpos($value_config, ','."Price per unit($)".',') !== FALSE) { ?>
    <label class="col-sm-2 text-center">Price per unit($)</label>
    <?php } ?>
    <?php if (strpos($value_config, ','."Cost($)".',') !== FALSE) { ?>
    <label class="col-sm-2 text-center">Cost($)</label>
    <?php } ?>
</div>

<?php
if(empty($_GET['fieldpoid'])) {
    ?>
  <div class="additional_row">
    <div class="clearfix"></div>
    <div class="form-group clearfix">
        <?php if (strpos($value_config, ','."Qty".',') !== FALSE) { ?>
        <div class="col-sm-1">
            <input name="qty[]" id="qty_0" type="text" maxlength="20" class="form-control qty" onKeyUp="numericFilter(this); multiplyCost(this);">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Desc".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="desc[]" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Grade".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="grade[]" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Tag".',') !== FALSE) { ?>
        <div class="col-sm-1">
            <input name="tag[]" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Detail".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="detail[]" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Price per unit($)".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="price_per_unit[]" id="up_0" type="text" class="form-control up" onKeyUp="multiplyCost(this);">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Cost($)".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="each_cost[]" id="amount_0" type="text" maxlength="20" class="form-control amount">
        </div>
        <?php } ?>
    </div>
</div>

<div id="add_here_new_data"></div>
<button id="add_new_row" class="btn brand-btn">Add More</button>

<?php
} else {

$qut = explode('#*#',$qty);
$desc = explode('#*#',$desc);
$grade = explode('#*#',$grade);
$tag = explode('#*#',$tag);
$detail = explode('#*#',$detail);
$price_per_unit = explode('#*#',$price_per_unit);
$each_cost = explode('#*#',$each_cost);
$total_count = mb_substr_count($qty,'#*#');
$no_ratecard = 0;
$no_rate_position = '';
for($emp_loop=0; $emp_loop<=$total_count; $emp_loop++) {
    if(($qut[$emp_loop]) != '') {
    $qt = '';
    $de = '';
    $gr = '';
    $tg = '';
    $dt = '';
    $ppu = '';
    $ec = '';
    $style = '';

    if(isset($qut[$emp_loop])) {
        $qt = $qut[$emp_loop];
    }
    if(isset($desc[$emp_loop])) {
        $de = $desc[$emp_loop];
    }
    if(isset($grade[$emp_loop])) {
        $gr = $grade[$emp_loop];
    }
    if(isset($tag[$emp_loop])) {
        $tg = $tag[$emp_loop];
    }
    if(isset($detail[$emp_loop])) {
        $dt = $detail[$emp_loop];
    }
    if(isset($price_per_unit[$emp_loop])) {
        $ppu = $price_per_unit[$emp_loop];
    }
    if(isset($each_cost[$emp_loop])) {
        $ec = $each_cost[$emp_loop];
    }

?>
    <div class="form-group clearfix">
        <?php if (strpos($value_config, ','."Qty".',') !== FALSE) { ?>
        <div class="col-sm-1">
            <input name="qty[]" id="qty_<?php echo $emp_loop;?>" type="text" maxlength="20" class="form-control qty" value="<?php echo  $qt; ?>" onKeyUp="numericFilter(this); multiplyCost(this);">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Desc".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="desc[]" value="<?php echo  $de; ?>" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Grade".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="grade[]" value="<?php echo  $gr; ?>" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Tag".',') !== FALSE) { ?>
        <div class="col-sm-1">
            <input name="tag[]" value="<?php echo  $tg; ?>" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Detail".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="detail[]" value="<?php echo  $dt; ?>" type="text" class="form-control">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Price per unit($)".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="price_per_unit[]" value="<?php echo  $ppu; ?>" id="up_<?php echo $emp_loop;?>" type="text" class="form-control up" onKeyUp="multiplyCost(this);">
        </div>
        <?php } ?>
        <?php if (strpos($value_config, ','."Cost($)".',') !== FALSE) { ?>
        <div class="col-sm-2">
            <input name="each_cost[]" value="<?php echo  $ec; ?>" id="amount_<?php echo $emp_loop;?>" type="text" maxlength="20" class="form-control amount">
        </div>
        <?php } ?>
   </div>
<?php } } ?>

<div class="additional_row">
    <div class="clearfix"></div>
    <div class="form-group clearfix">
        <div class="col-sm-1">
            <input name="qty[]" id="qty_<?php echo $emp_loop;?>" type="text" maxlength="20" class="form-control qty" onKeyUp="numericFilter(this); multiplyCost(this);">
        </div>
        <div class="col-sm-2">
            <input name="desc[]" type="text" class="form-control">
        </div>
        <div class="col-sm-2">
            <input name="grade[]" type="text" class="form-control">
        </div>
        <div class="col-sm-1">
            <input name="tag[]" type="text" class="form-control">
        </div>
        <div class="col-sm-2">
            <input name="detail[]" type="text" class="form-control">
        </div>
        <div class="col-sm-2">
            <input name="price_per_unit[]" id="up_<?php echo ($emp_loop);?>" type="text" class="form-control up" onKeyUp="multiplyCost(this);">
        </div>
        <div class="col-sm-2">
            <input name="each_cost[]" id="amount_<?php echo ($emp_loop);?>" type="text" maxlength="20" class="form-control amount">
        </div>
    </div>
</div>

<div id="add_here_new_data"></div>
 <button id="add_new_row" class="btn brand-btn">Add</button>

    <?php }

    ?>