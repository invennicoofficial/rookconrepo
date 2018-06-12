<script type="text/javascript">
$(document).ready(function() {
	//Products
    var add_new_p = 1;
    $('#deleteproduct_0').hide();
    $('#add_row_p').on( 'click', function () {
        $('#deleteproduct_0').show();
        var clone = $('.additional_p').clone();
        clone.find('.form-control').val('');

        clone.find('#sproduct_0').attr('id', 'sproduct_'+add_new_p);
		clone.find('#pcategory_0').attr('id', 'pcategory_'+add_new_p);
        clone.find('#pheading_0').attr('id', 'pheading_'+add_new_p);

        clone.find('#product_0').attr('id', 'product_'+add_new_p);
        clone.find('#deleteproduct_0').attr('id', 'deleteproduct_'+add_new_p);
        $('#deleteproduct_0').hide();

        clone.removeClass("additional_p");
        $('#add_here_new_p').append(clone);

        resetChosen($("#sproduct_"+add_new_p));
        resetChosen($("#pcategory_"+add_new_p));
        resetChosen($("#pheading_"+add_new_p));

        add_new_p++;

        return false;
    });
});
$(document).on('change', 'select.prod_serv_onchange', function() { selectProductProduct(this); });
$(document).on('change', 'select.prod_cat_onchange', function() { selectProductCat(this); });
$(document).on('change', 'select[name="productid[]"]', function() { selectProductHeading(this); });
function selectProductProduct(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "sales_ajax_all.php?fill=p_product_config&value="+stage,
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
		url: "sales_ajax_all.php?fill=p_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#pheading_"+arr[1]).html(response);
			$("#pheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function seleteProduct(sel, hide, blank) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#"+hide+arr[1]).hide();
    $("#"+blank+arr[1]).val('');

}

</script>

<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <?php if (strpos($value_config, ','."Products Product Type".',') !== FALSE) { ?>
            <label class="col-sm-3 text-center">Product Type</label>
            <?php } ?>
            <?php if (strpos($value_config, ','."Products Category".',') !== FALSE) { ?>
            <label class="col-sm-3 text-center">Category</label>
            <?php } ?>
            <?php if (strpos($value_config, ','."Products Heading".',') !== FALSE) { ?>
            <label class="col-sm-3 text-center">Heading</label>
            <?php } ?>
        </div>
        <?php if(!empty($_GET['salesid'])) {
            $each_productid = explode(',',$productid);
            $total_count = mb_substr_count($productid,',');
            $id_loop = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $productid = '';
                if(isset($each_productid[$inventory_loop])) {
                    $productid = $each_productid[$inventory_loop];
                }

                if($productid != '') {
            ?>

            <div class="form-group clearfix" id="<?php echo 'product_'.$id_loop; ?>" >
                <?php if (strpos($value_config, ','."Products Product Type".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Type..." id="<?php echo 'sproduct_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid prod_serv_onchange" width="380">
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
                <?php if (strpos($value_config, ','."Products Category".',') !== FALSE) { ?>
                <div class="col-sm-3">
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
                <?php if (strpos($value_config, ','."Products Heading".',') !== FALSE) { ?>
                <div class="col-sm-3">
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
                </div>
                <?php } ?>

                <div class="col-sm-1" >
                    <a href="#" onclick="seleteProduct(this,'product_','pheading_'); return false;" id="<?php echo 'deleteproduct_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_p clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="product_0">
                <?php if (strpos($value_config, ','."Products Product Type".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Type..." id="sproduct_0" class="chosen-select-deselect form-control equipmentid prod_serv_onchange" width="380">
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
                <?php if (strpos($value_config, ','."Products Category".',') !== FALSE) { ?>
                <div class="col-sm-3">
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
                <?php if (strpos($value_config, ','."Products Heading".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Heading..." id="pheading_0" name="productid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT productid, heading FROM products WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['productid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <a href="#" onclick="seleteProduct(this,'product_','pheading_'); return false;" id="deleteproduct_0" class="btn brand-btn">Delete</a>
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
<style>.selectbutton {
	cursor: pointer;
	text-decoration: underline;
}
@media (min-width: 801px) {
	.sel2 {
		display:none;
	}
}
.approve-box {
    display: none;
    position: fixed;
    width: 500px;
	height:400px;
	top:50%;
	margin-top:-200px;
    left: 50%;
    background: lightgrey;
    color: black;
    border: 10px outset grey;
    border-radius: 15px;
    margin-left: -250px;
    text-align: center;
	z-index:999999;
    padding: 20px;
}
@media (max-width:530px) {
.approve-box {
	width:100%;
	z-index:9999999;
	left:0px;
	margin-left:0px;
	overflow:auto;
}
}
.open-approval { cursor:pointer; text-decoration:underline; }
.open-approval:hover { cursor:pointer; text-decoration:underline; font-style: italic; }
	</style>
