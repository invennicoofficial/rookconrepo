

<style>
.col-sm-2, .col-sm-1, .col-sm-4 {
	padding-left:5px !important;
	padding-right:5px !important;
	word-break: break-word;
}
.chosen-container {
	width:100% !important;
    min-width: 1px !important;
}

@media(max-width:767px) {
.hide-titles-mob {
	display:none;
}
.show-on-mob {
	display:inline-block;
}
}
@media(min-width:768px) {
.show-on-mob {
	display:none;
}	
	
}
</style>
<script>
$(document).ready(function() {
    $('#sdelete_0').hide();
	//Services
    var add_new_s = 1;
    $('#add_row_s').on( 'click', function () {
        $('#sdelete_0').show();
        var clone = $('.additional_s').clone();
        clone.find('.form-control').val('');

        clone.find('#sservice_0').attr('id', 'sservice_'+add_new_s);
		clone.find('#scategory_0').attr('id', 'scategory_'+add_new_s);
        clone.find('#sheading_0').attr('id', 'sheading_'+add_new_s);
        clone.find('#sfrp_0').attr('id', 'sfrp_'+add_new_s);
        clone.find('#sap_0').attr('id', 'sap_'+add_new_s);
        clone.find('#swp_0').attr('id', 'swp_'+add_new_s);
        clone.find('#scomp_0').attr('id', 'scomp_'+add_new_s);
        clone.find('#scp_0').attr('id', 'scp_'+add_new_s);
        clone.find('#smsrp_0').attr('id', 'smsrp_'+add_new_s);

        clone.find('#sdelete_0').attr('id', 'sdelete_'+add_new_s);
        clone.find('#customservice_0').attr('id', 'customservice_'+add_new_s);
        $('#sdelete_0').hide();

        clone.removeClass("additional_s");
        $('#add_here_new_s').append(clone);

        resetChosen($("#sservice_"+add_new_s));
        resetChosen($("#scategory_"+add_new_s));
        resetChosen($("#sheading_"+add_new_s));

        add_new_s++;

        return false;
    });
});
$(document).on('change', 'select.serv_type_change', function() { selectServiceService(this); });
$(document).on('change', 'select.serv_cat_change', function() { selectServiceCat(this); });
$(document).on('change', 'select[name="assign_services[]"]', function() { selectServiceHeading(this); });
//Services
function selectServiceService(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "custom_ajax_all.php?fill=s_service_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#scategory_"+arr[1]).html(response);
			$("#scategory_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectServiceCat(sel) {
	var stage = encodeURIComponent(sel.value);
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "custom_ajax_all.php?fill=s_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sheading_"+arr[1]).html(response);
			$("#sheading_"+arr[1]).trigger("change.select2");
		}
	});
}
function selectServiceHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "custom_ajax_all.php?fill=s_head_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#sfrp_"+arr[1]).val(result[0]);
            $("#sap_"+arr[1]).val(result[1]);
            $("#swp_"+arr[1]).val(result[2]);
            $("#scomp_"+arr[1]).val(result[3]);
            $("#scp_"+arr[1]).val(result[4]);
            $("#smsrp_"+arr[1]).val(result[5]);
			$("#sfinalprice_"+arr[1]).val(result[6]);

		}
	});
}
function deleteService(sel) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#customservice_"+arr[1]).hide();
    $("#sheading_"+arr[1]).val('');
    return false;
}
</script>
<?php
$get_field_config_service = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT services FROM field_config"));
$field_config_service = ','.$get_field_config_service['services'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-2 text-center">Service Type</label>
            <label class="col-sm-2 text-center">Category</label>
            <label class="col-sm-1 text-center">Heading</label>
            <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">MSRP</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Quantity</label>
        </div>


        <?php if(!empty($_GET['customid'])) {
            $each_assign_services = explode('**',$assign_services);
            $total_count = mb_substr_count($assign_services,'**');
            $id_loop = 500;
            for($services_loop=0; $services_loop<$total_count; $services_loop++) {

                $each_item = explode('#',$each_assign_services[$services_loop]);
                $serviceid = '';
                $qty = '';
                if(isset($each_item[0])) {
                    $serviceid = $each_item[0];
                }
                if(isset($each_item[1])) {
                    $qty = $each_item[1];
                }
                if($serviceid != '') {
            ?>
                <div class="form-group clearfix" id="<?php echo 'customservice_'.$id_loop; ?>">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Service Type:</label>
                    <select data-placeholder="Select a Type..." id="<?php echo 'sservice_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid serv_type_change" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_services($dbc, $serviceid, 'service_type') == $row['service_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['service_type']."'>".$row['service_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Select a Category..." id="<?php echo 'scategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid serv_cat_change" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_services($dbc, $serviceid, 'category') == $row['category']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['category']."'>".$row['category'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Select a Heading..." id="<?php echo 'sheading_'.$id_loop; ?>" name="assign_services[]" class="chosen-select-deselect form-control" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_services($dbc, $serviceid, 'heading') == $row['heading']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['serviceid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" value="<?php echo get_services($dbc, $serviceid, 'heading');?>" readonly id="<?php echo 'sheading_'.$id_loop; ?>" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="sfrp[]" value="<?php echo get_services($dbc, $serviceid, 'final_retail_price');?>"  id="<?php echo 'sfrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="sap[]" value="<?php echo get_services($dbc, $serviceid, 'admin_price');?>"  id="<?php echo 'sap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="swp[]" value="<?php echo get_services($dbc, $serviceid, 'wholesale_price');?>" id="<?php echo 'swp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="scomp[]" value="<?php echo get_services($dbc, $serviceid, 'commercial_price');?>" id="<?php echo 'scomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="scp[]" value="<?php echo get_services($dbc, $serviceid, 'client_price');?>" id="<?php echo 'scp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="smsrp[]" value="<?php echo get_services($dbc, $serviceid, 'msrp');?>" id="<?php echo 'smsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input name="assign_services_quantity[]" value="<?php echo $qty;?>" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteService(this); return false;" id="<?php echo 'sdelete_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_s clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="customservice_0">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Service Type:</label>
                    <select data-placeholder="Select a Type..." id="sservice_0" class="chosen-select-deselect form-control equipmentid serv_type_change" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['service_type']."'>".$row['service_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Select a Category..." id="scategory_0" class="chosen-select-deselect form-control equipmentid serv_cat_change" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, category FROM services WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Select a Heading..." id="sheading_0" name="assign_services[]" class="chosen-select-deselect form-control" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['serviceid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="sheading_0" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_service, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="sfrp[]" id="sfrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="sap[]" id="sap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="swp[]" id="swp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="scomp[]" id="scomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="scp[]" id="scp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_service, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="smsrp[]" id="smsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Quantity:</label>
                    <input name="assign_services_quantity[]" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteService(this); return false;" id="sdelete_0" class="btn brand-btn">Delete</a>

            </div>

        </div>

        <div id="add_here_new_s"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_s" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>