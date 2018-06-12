<?php include_once('../include.php');
checkAuthorised('sales_order');
/*
/* Sales Order Config - Fields
*/

if(isset($_POST['save_config'])) {
    $pos_tax = '';
    foreach ($_POST['pos_tax_name'] as $i => $value) {
    	if(!empty($value)) {
    		$pos_tax .= filter_var($_POST['pos_tax_name'][$i],FILTER_SANITIZE_STRING).'**'.$_POST['pos_tax_rate'][$i].'**'.$_POST['pos_tax_number'][$i].'**'.$_POST['pos_tax_exemption_'.$i].'*#*';
    	}
    }

    $pos_tax = rtrim($pos_tax, "*#*'");
    $get_config = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT COUNT(configid) AS configid FROM general_configuration WHERE name='sales_order_tax'"));
    if($get_config['configid'] > 0) {
        $query_update_employee = "UPDATE `general_configuration` SET value = '$pos_tax' WHERE name='sales_order_tax'";
        $result_update_employee = mysqli_query($dbc, $query_update_employee);
    } else {
        $query_insert_config = "INSERT INTO `general_configuration` (`name`, `value`) VALUES ('sales_order_tax', '$pos_tax')";
        $result_insert_config = mysqli_query($dbc, $query_insert_config);
    }
}
?>
<script type="text/javascript">
function addTax() {
	var counter = parseInt($('#tax_counter').val());
	var row = $('.tax_row').last();
	var clone = row.clone();

	clone.find('.form-control').val('');
	clone.find('.tax_name').prop('name', 'pos_tax_name['+counter+']');
	clone.find('.tax_rate').prop('name', 'pos_tax_rate['+counter+']').val(0);
	clone.find('.tax_number').prop('name', 'pos_tax_number['+counter+']');
	clone.find('.tax_exemption').prop('name', 'pos_tax_exemption_'+counter).prop('checked', false);

	row.after(clone);

	$('#tax_counter').val(counter + 1);
}
function deleteTax(btn) {
	if($('.tax_row').length <= 1) {
		addTax();
	}
	$(btn).closest('.tax_row').remove();
}
</script>
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
    <div class="gap-top">
        <div class="form-group clearfix">
            <label class="col-sm-2 text-center">Name</label>
            <label class="col-sm-2 text-center">Rate(%)<br><em>(add number without % sign)</em></label>
            <label class="col-sm-2 text-center">Tax Number</label>
            <label class="col-sm-2">Tax Exempt</label>
        </div>

        <?php
        $value_config = get_config($dbc, 'sales_order_tax');

        $pos_tax = explode('*#*',$value_config);

        $total_count = mb_substr_count($value_config,'*#*');
        for($eq_loop=0; $eq_loop<=$total_count || $eq_loop < 1; $eq_loop++) {
            $pos_tax_name_rate = explode('**',$pos_tax[$eq_loop]); ?>
            <div class="form-group clearfix tax_row">
              	<div class="col-sm-2">
                    <input name="pos_tax_name[<?= $eq_loop ?>]" value="<?php echo $pos_tax_name_rate[0];?>" type="text" class="form-control quantity tax_name" />
                </div>
                <div class="col-sm-2">
                    <input name="pos_tax_rate[<?= $eq_loop ?>]" value="<?php echo $pos_tax_name_rate[1]; ?>" type="text" class="form-control category tax_rate" />
                </div>
                <div class="col-sm-2">
                    <input name="pos_tax_number[<?= $eq_loop ?>]" value="<?php echo $pos_tax_name_rate[2]; ?>" type="text" class="form-control category tax_number" />
                </div>
                <div class="col-sm-2">
                  	<div class="radio tax_exemption_div">
    	                <label><input class="tax_exemption" type="radio" <?php if ($pos_tax_name_rate[3] == 'Yes') { echo 'checked'; } ?> name="pos_tax_exemption_<?php echo $eq_loop;?>" value="Yes">Yes</label>
    	                &nbsp; &nbsp;
    	                <label><input class="tax_exemption" type="radio" <?php if ($pos_tax_name_rate[3] == 'No' || $pos_tax_name_rate[3] == '') { echo 'checked'; } ?> name="pos_tax_exemption_<?php echo $eq_loop;?>" value="No">No</label>
                  	</div>
                </div>
                <div class="col-sm-2">
                	<img src="../img/remove.png" class="inline-img" onclick="deleteTax(this);">
                    <img src="../img/icons/ROOK-add-icon.png" class="inline-img" onclick="addTax();">
                </div>
            </div>
        <?php } ?>
        
        <input type="hidden" name="tax_counter" id="tax_counter" value="<?= $eq_loop ?>">
    </div>
    <div class="pull-right gap-top gap-right gap-bottom">
        <a href="index.php" class="btn brand-btn">Cancel</a>
        <button type="submit" id="save_config" name="save_config" value="Submit" class="btn brand-btn">Submit</button>
    </div>
</form>