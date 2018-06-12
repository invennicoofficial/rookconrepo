<script>
$(document).ready(function() {
    $('#vdelete_0').hide();
	//Vendor
    var add_new_v = 1;
    $('#add_row_v').on( 'click', function () {
        $('#vdelete_0').show();
        var clone = $('.additional_v').clone();
        clone.find('.form-control').val('');

        clone.find('#prvendorid_0').attr('id', 'prvendorid_'+add_new_v);
        clone.find('#prcategory_0').attr('id', 'prcategory_'+add_new_v);
        clone.find('#prpricelistid_0').attr('id', 'prpricelistid_'+add_new_v);
        clone.find('#prproduct_0').attr('id', 'prproduct_'+add_new_v);
		clone.find('#vunitprice_0').attr('id', 'vunitprice_'+add_new_v);
        clone.find('#vfinalprice_0').attr('id', 'vfinalprice_'+add_new_v);

        clone.find('#vdelete_0').attr('id', 'vdelete_'+add_new_v);
        clone.find('#promotionvendor_0').attr('id', 'promotionvendor_'+add_new_v);
        $('#vdelete_0').hide();

        clone.removeClass("additional_v");
        $('#add_here_new_v').append(clone);

        resetChosen($("#prvendorid_"+add_new_v));
        resetChosen($("#prcategory_"+add_new_v));
        resetChosen($("#prpricelistid_"+add_new_v));
        resetChosen($("#prproduct_"+add_new_v));

        add_new_v++;

        return false;
    });
});
$(document).on('change', 'select.vendor_onchange', function() { selectVendor(this); });
$(document).on('change', 'select.vendor_pricelist_onchange', function() { selectPricelist(this); });
$(document).on('change', 'select.vendor_cat_onchange', function() { selectCategory(this); });
$(document).on('change', 'select[name="assign_vendor[]"]', function() { selectProduct(this); });

//Vendor
function selectVendor(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "promotion_ajax_all.php?fill=vendor_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#prpricelistid_"+arr[1]).html(response);
			$("#prpricelistid_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectPricelist(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "promotion_ajax_all.php?fill=vpricelist_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#prcategory_"+arr[1]).html(response);
			$("#prcategory_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectCategory(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var pricelist = $("#prpricelistid_"+arr[1]).val();
	$.ajax({
		type: "GET",
		url: "promotion_ajax_all.php?fill=vcat_config&value="+stage+"&pricelist="+pricelist,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#prproduct_"+arr[1]).html(response);
			$("#prproduct_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectProduct(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var pricelist = $("#prpricelistid_"+arr[1]).val();
    var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "promotion_ajax_all.php?fill=vproduct_config&value="+stage+"&pricelist="+pricelist+"&ratecardid="+ratecardid,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#vunitprice_"+arr[1]).val(result[0]);
            $("#vfinalprice_"+arr[1]).val(result[1]);
		}
	});
}
function deleteVendor(sel) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#promotionvendor_"+arr[1]).hide();
    $("#prproduct_"+arr[1]).val('');
    return false;
}
</script>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-2 text-center">Vendor</label>
            <label class="col-sm-2 text-center">Price List</label>
            <label class="col-sm-2 text-center">Category</label>
            <label class="col-sm-2 text-center">Product</label>
            <label class="col-sm-1 text-center">Canadian $ Cost Per Unit</label>
            <label class="col-sm-1 text-center">Hours</label>
        </div>

       <?php if(!empty($_GET['promotionid'])) {
            $each_assign_vendor = explode('**',$assign_vendor);
            $total_count = mb_substr_count($assign_vendor,'**');
            $id_loop = 500;
            for($vendor_loop=0; $vendor_loop<=$total_count; $vendor_loop++) {

                $each_item = explode('#',$each_assign_vendor[$vendor_loop]);
                $pricelistid = '';
                $qty = '';
                if(isset($each_item[0])) {
                    $pricelistid = $each_item[0];
                }
                if(isset($each_item[1])) {
                    $qty = $each_item[1];
                }

                if($pricelistid != '') {

            ?>
                <div class="form-group clearfix" id="<?php echo 'promotionvendor_'.$id_loop; ?>">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Vendor:</label>
                    <select data-placeholder="Choose a Vendor..." id="<?php echo 'prvendorid_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid vendor_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Vendor' AND deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_vendor_pricelist($dbc, $pricelistid, 'vendorid') == $row['contactid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Price List:</label>
                    <select data-placeholder="Choose a Price List Item..." id="<?php echo 'prpricelistid_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid vendor_pricelist_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT pricelistid, pricelist_name FROM vendor_pricelist WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_vendor_pricelist($dbc, $pricelistid, 'pricelist_name') == $row['pricelist_name']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['pricelist_name']."'>".$row['pricelist_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="<?php echo 'prcategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid vendor_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT pricelistid, category FROM vendor_pricelist WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_vendor_pricelist($dbc, $pricelistid, 'category') == $row['category']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Product:</label>
                    <select data-placeholder="Choose a Product..." id="<?php echo 'prproduct_'.$id_loop; ?>" name="assign_vendor[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT pricelistid, name FROM vendor_pricelist WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if ($pricelistid == $row['pricelistid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['pricelistid']."'>".$row['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Canadian $ Cost Per Unit:</label>
                    <input name="vcdn_cpu[]" value="<?php echo get_vendor_pricelist($dbc, $pricelistid, 'cdn_cpu');?>" id="<?php echo 'vunitprice_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Hours:</label>
                    <input name="assign_vendor_quantity[]" value="<?php echo $qty;?>" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteVendor(this); return false;" id="<?php echo 'vdelete_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_v clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="promotionvendor_0">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Vendor:</label>
                    <select data-placeholder="Choose a Vendor..." id="prvendorid_0" class="chosen-select-deselect form-control equipmentid vendor_onchange" width="380">
                        <option value=''></option>
						<?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='vendor' AND deleted=0 AND `status` > 0"),MYSQLI_ASSOC));
							foreach($query as $id) {
								$selected = '';
								//$selected = $id == $search_user ? 'selected = "selected"' : '';
								echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id,'name').'</option>';
							}
						  ?>
                    </select>
                </div>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Price List:</label>
                    <select data-placeholder="Choose a Price List Item..." id="prpricelistid_0" class="chosen-select-deselect form-control equipmentid vendor_pricelist_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT pricelistid, pricelist_name FROM vendor_pricelist WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['pricelist_name']."'>".$row['pricelist_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="prcategory_0" class="chosen-select-deselect form-control equipmentid vendor_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT pricelistid, category FROM vendor_pricelist WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Product:</label>
                    <select data-placeholder="Choose a Product..." id="prproduct_0" name="assign_vendor[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT pricelistid, name FROM vendor_pricelist WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['pricelistid']."'>".$row['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Canadian $ Cost Per Unit:</label>
                    <input name="vcdn_cpu[]" id="vunitprice_0" readonly type="text" class="form-control" />
                </div>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Hours:</label>
                    <input name="assign_vendor_quantity[]" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteVendor(this); return false;" id="vdelete_0" class="btn brand-btn">Delete</a>

            </div>

        </div>

        <div id="add_here_new_v"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_v" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
