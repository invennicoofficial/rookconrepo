<script>
$(document).ready(function() {
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
        clone.find('#pap_0').attr('id', 'pap_'+add_new_p);
        clone.find('#pwp_0').attr('id', 'pwp_'+add_new_p);
        clone.find('#pcomp_0').attr('id', 'pcomp_'+add_new_p);
        clone.find('#pcp_0').attr('id', 'pcp_'+add_new_p);
        clone.find('#pmsrp_0').attr('id', 'pmsrp_'+add_new_p);
		clone.find('#pfinalprice_0').attr('id', 'pfinalprice_'+add_new_p);
		clone.find('#pquoteprice_0').attr('id', 'pquoteprice_'+add_new_p);
        clone.find('#products_0').attr('id', 'products_'+add_new_p);
		clone.find('#pcost_0').attr('id', 'pcost_'+add_new_p);
		clone.find('#pestimateprice_0').attr('id', 'pestimateprice_'+add_new_p);
		clone.find('#pquantity_0').attr('id', 'pquantity_'+add_new_p);
		clone.find('#ptotal_0').attr('id', 'ptotal_'+add_new_p);
		clone.find('#pprofit_0').attr('id', 'pprofit_'+add_new_p);
		clone.find('#pmargin_0').attr('id', 'pmargin_'+add_new_p);
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
});
$(document).on('change', 'select.prod_serv_onchange', function() { selectProductProduct(this); });
$(document).on('change', 'select.prod_cat_onchange', function() { selectProductCat(this); });
$(document).on('change', 'select[name="productid[]"]', function() { selectProductHeading(this); });
//Products
function selectProductProduct(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=p_product_config&value="+stage,
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
		url: "ratecard_ajax_all.php?fill=p_cat_config&value="+stage,
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

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=p_head_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#pfrp_"+arr[1]).val(result[0]);
            $("#pap_"+arr[1]).val(result[1]);
            $("#pwp_"+arr[1]).val(result[2]);
            $("#pcomp_"+arr[1]).val(result[3]);
            $("#pcp_"+arr[1]).val(result[4]);
            $("#pmsrp_"+arr[1]).val(result[5]);
			$("#pcost_"+arr[1]).val(result[6]);
			$("#pquantity_"+arr[1]).val(result[7]);
		}
	});
}
	
function changeEstimatePrice(sel){
	var typeId = sel.id;
	var arr = typeId.split('_');
	var estimatePrice= $("#pestimateprice_"+arr[1]).val();
	var cost= $("#pcost_"+arr[1]).val();
	var quantity= $("#pquantity_"+arr[1]).val();
	if(quantity=='' && quantity==0){ quantity=1; }
	var total=quantity*estimatePrice;
	$("#ptotal_"+arr[1]).val(total);
	
	var profit=total-(cost*quantity);
	$("#pprofit_"+arr[1]).val(profit);
	
	var margin=((profit*100)/total).toFixed(2);
	$("#pmargin_"+arr[1]).val(margin);
}
</script>
<?php
$get_field_config_product = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT products FROM field_config"));
$field_config_product = ','.$get_field_config_product['products'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <?php if (strpos($base_field_config, ','."Products Product Type".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Product Type</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."Products Category".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Category</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."Products Heading".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Heading</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_product, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">MSRP</label>
            <?php } ?>
			<label class="col-sm-1 text-center">Cost</label>
            <label class="col-sm-1 text-center">Rate Card Price</label>
			<label class="col-sm-1 text-center">Estimate Price</label>
			<label class="col-sm-1 text-center">UOM</label>
			<label class="col-sm-1 text-center">Quantity</label>
			<label class="col-sm-1 text-center">Total</label>
			<label class="col-sm-1 text-center">$ Profit</label>
			<label class="col-sm-1 text-center">% Margin</label>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_products = explode('**', $products);
            $total_count = mb_substr_count($products,'**');
            $id_loop = 500;
            for($pid_loop=0; $pid_loop<$total_count; $pid_loop++) {

                $productid = '';

                if(isset($each_products[$pid_loop])) {
                    $each_val = explode('#', $each_products[$pid_loop]);
                    $productid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($productid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'products_'.$id_loop; ?>">
                <?php if (strpos($base_field_config, ','."Products Product Type".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Product Type:</label>
                    <select data-placeholder="Choose a Type..." id="<?php echo 'pproduct_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid prod_serv_onchange" width="380">
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
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="<?php echo 'pcategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid prod_cat_onchange" width="380">
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

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="<?php echo 'pheading_'.$id_loop; ?>" name="productid[]" class="chosen-select-deselect form-control equipmentid" width="380">
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

                    <!-- <input name="pheading[]" readonly id="<?php echo 'sheading_'.$id_loop; ?>" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_product, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="pfrp[]" value="<?php echo get_products($dbc, $productid, 'final_retail_price');?>" id="<?php echo 'pfrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="pap[]" value="<?php echo get_products($dbc, $productid, 'admin_price');?>" id="<?php echo 'pap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="pwp[]" value="<?php echo get_products($dbc, $productid, 'wholesale_price');?>" id="<?php echo 'pwp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="pcomp[]" value="<?php echo get_products($dbc, $productid, 'commercial_price');?>" id="<?php echo 'pcomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="pcp[]" value="<?php echo get_products($dbc, $productid, 'client_price');?>" id="<?php echo 'pcp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="pmsrp[]" value="<?php echo get_products($dbc, $productid, 'msrp');?>" id="<?php echo 'pmsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Cost:</label>
                    <input name="pcost[]" value="<?php echo $ratecardcost;?>" id="<?php echo 'pcost_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="pfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'pfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Estimate Price:</label>
                    <input name="pestimateprice[]" value="<?php echo $ratecardestimateprice;?>" id="<?php echo 'pestimateprice_'.$id_loop; ?>" type="text" class="form-control" onblur='changeEstimatePrice(this)' />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">UOM:</label>
                    <input name="puom[]" value="<?php echo $ratecarduom;?>" id="<?php echo 'puom_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input name="pquantity[]" value="<?php echo $ratecardquantity;?>" id="<?php echo 'pquantity_'.$id_loop; ?>" type="text" class="form-control" onblur='changeEstimatePrice(this)' />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Total:</label>
                    <input name="ptotal[]" value="<?php echo $ratecardtotal;?>" id="<?php echo 'ptotal_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">$ Profit:</label>
                    <input name="pprofit[]" value="<?php echo $ratecardprofit;?>" id="<?php echo 'pprofit_'.$id_loop; ?>" type="text" readonly class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">$ Margin:</label>
                    <input name="pmargin[]" value="<?php echo $ratecardmargin;?>" id="<?php echo 'pmargin_'.$id_loop; ?>" type="text" readonly class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'products_','pheading_'); return false;" id="<?php echo 'deleteproducts_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
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
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Product Type:</label>
                    <select data-placeholder="Choose a Type..." id="pproduct_0" class="chosen-select-deselect form-control equipmentid prod_serv_onchange" width="380">
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
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="pcategory_0" class="chosen-select-deselect form-control equipmentid prod_cat_onchange" width="380">
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
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="pheading_0" name="productid[]" class="chosen-select-deselect form-control equipmentid" width="380">
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
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="pfrp[]" id="pfrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="pap[]" id="pap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="pwp[]" id="pwp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="pcomp[]" id="pcomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="pcp[]" id="pcp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_product, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="pmsrp[]" id="pmsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Cost:</label>
                    <input name="pcost[]" id="pcost_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="pfinalprice[]" id="pfinalprice_0" type="text" class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Estimate Price:</label>
                    <input name="pestimateprice[]" id="pestimateprice_0" type="text" class="form-control" onblur='changeEstimatePrice(this)' />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">UOM:</label>
                    <input name="puom[]" id="puom_0" type="text" class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input name="pquantity[]" id="pquantity_0" type="text" class="form-control" onblur='changeEstimatePrice(this)' />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Total:</label>
                    <input name="ptotal[]" id="ptotal_0" type="text" class="form-control" />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">$ Profit:</label>
                    <input name="pprofit[]" id="pprofit_0" type="text" class="form-control" readonly />
                </div>
				<div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">% Margin:</label>
                    <input name="pmargin[]" id="pmargin_0" type="text" class="form-control" readonly />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'products_','pheading_'); return false;" id="deleteproducts_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_p"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_p" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
