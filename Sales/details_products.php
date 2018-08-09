<!-- Products -->
<script type="text/javascript">
var add_new_p = 1;
$(document).ready(function() {
    $('#deleteproduct_0').hide();
    $('.add_row_p').on( 'click', add_row_product);
});
$(document).on('change', 'select.prod_serv_onchange', function() { selectProductProduct(this); });
$(document).on('change', 'select.prod_cat_onchange', function() { selectProductCat(this); });
$(document).on('change', 'select[name="productid[]"]', function() { selectProductHeading(this); });

function add_row_product() {
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
    $('.add_row_p').off('click',add_row_product).on( 'click', add_row_product);

    return false;
}
function selectProductProduct(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "sales_ajax_all.php?fill=p_product_config&value="+stage,
		dataType: "html",
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
		dataType: "html",
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

<div class="accordion-block-details padded" id="products">
    <div class="accordion-block-details-heading"><h4>Products</h4></div>
    
    <div class="row"><?php
        if (strpos($value_config, ',Products Product Type,') !== false) { echo '<div class="col-sm-3 gap-md-left-15 gap-bottom"><b>Product Type</b></div>'; }
        if (strpos($value_config, ',Products Category,') !== false) { echo '<div class="col-sm-3 gap-md-left-15 gap-bottom"><b>Category</b></div>'; }
        if (strpos($value_config, ',Products Heading,') !== false) { echo '<div class="col-sm-3 gap-md-left-15 gap-bottom"><b>Heading</b></div>'; } ?>
        <div class="clearfix"></div><?php
        
        if ( !empty($salesid) ) {
            $each_serviceid = explode(',', $productid);
            $total_count    = mb_substr_count($productid,',');
            $id_loop        = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $productid = '';
                
                if (isset($each_serviceid[$inventory_loop])) {
                    $productid = $each_serviceid[$inventory_loop];
                }

                if ($productid != '') { ?>
                    <div class="set-row-height" id="<?= 'product_'.$id_loop; ?>"><?php
                        if (strpos($value_config, ',Products Product Type,') !== false) { ?>
                            <div class="col-sm-3 gap-md-left-15">
                                <select data-placeholder="Choose a Type..." id="<?php echo 'sproduct_'.$id_loop; ?>" class="chosen-select-deselect form-control prod_serv_onchange">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT DISTINCT(`product_type`) FROM `products` WHERE `deleted`=0 ORDER BY `product_type`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = (get_services($dbc, $productid, 'product_type') == $row['product_type']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $row['product_type'] .'">'. $row['product_type'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        }
                        
                        if (strpos($value_config, ',Products Category,') !== false) { ?>
                            <div class="col-sm-3 gap-md-left-15">
                                <select data-placeholder="Choose a Category..." id="<?php echo 'pcategory_'.$id_loop; ?>" class="chosen-select-deselect form-control prod_cat_onchange">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT DISTINCT(`category`) FROM `products` WHERE `deleted`=0 ORDER BY `category`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = (get_services($dbc, $productid, 'category') == $row['category']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $row['category'] .'">'. $row['category'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        }
                        
                        if (strpos($value_config, ',Products Heading,') !== false) { ?>
                            <div class="col-sm-3 gap-md-left-15">
                                <select data-placeholder="Choose a Heading..." id="<?php echo 'pheading_'.$id_loop; ?>" name="productid[]" class="chosen-select-deselect form-control">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT `productid`, `heading` FROM `products` WHERE `deleted`=0 ORDER BY `heading`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = ($productid == $row['productid']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $row['productid'] .'">'. $row['heading'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        } ?>

                        <div class="col-sm-1" >
                            <a href="#" onclick="seleteProduct(this,'product_','pheading_'); return false;" id="<?= 'deleteproduct_'.$id_loop; ?>"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                            <a href="#" id="add_row_p" class="gap-md-left-15"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div><?php
                    
                    $id_loop++;
                }
            }
        } ?>
        
        <div class="additional_p">

            <div class="set-row-height" id="product_0"><?php
                if (strpos($value_config, ',Products Product Type,') !== false) { ?>
                    <div class="col-sm-3 gap-md-left-15">
                        <select data-placeholder="Choose a Type..." id="sproduct_0" class="chosen-select-deselect form-control prod_serv_onchange">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc,"SELECT DISTINCT(`product_type`) FROM `products` WHERE `deleted`=0 ORDER BY `product_type`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option value="'. $row['product_type'] .'">'. $row['product_type'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                }
                
                if (strpos($value_config, ',Products Category,') !== false) { ?>
                    <div class="col-sm-3 gap-md-left-15">
                        <select data-placeholder="Choose a Category..." id="pcategory_0" class="chosen-select-deselect form-control prod_cat_onchange">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc, "SELECT DISTINCT(`category`) FROM `products` WHERE `deleted`=0 ORDER BY `category`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option value="'. $row['category'] .'">'. $row['category'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                }
                
                if (strpos($value_config, ',Products Heading,') !== false) { ?>
                    <div class="col-sm-3 gap-md-left-15">
                        <select data-placeholder="Choose a Heading..." id="pheading_0" name="productid[]" class="chosen-select-deselect form-control">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc, "SELECT `productid`, `heading` FROM `products` WHERE `deleted`=0 ORDER BY `heading`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option value="'. $row['productid'] .'">'. $row['heading'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                } ?>
                
                <div class="col-sm-1 pad-5">
                    <a href="#" onclick="seleteProduct(this,'product_','pheading_'); return false;" id="deleteproduct_0"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                    <a href="#" class="add_row_p gap-md-left-15"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                </div>
                
                <div class="clearfix"></div>
            </div>
        </div><!-- .additional_s -->

        <div id="add_here_new_p"></div>
        
        <div class="clearfix"></div>
        
    </div>
    <div class="clearfix"></div>
    
</div><!-- .accordion-block-details -->