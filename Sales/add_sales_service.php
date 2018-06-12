<script type="text/javascript">
$(document).ready(function() {
	//Services
    var add_new_s = 1;
    $('#deleteservices_0').hide();
    $('#add_row_s').on( 'click', function () {
        $('#deleteservices_0').show();
        var clone = $('.additional_s').clone();
        clone.find('.form-control').val('');

        clone.find('#sservice_0').attr('id', 'sservice_'+add_new_s);
		clone.find('#scategory_0').attr('id', 'scategory_'+add_new_s);
        clone.find('#sheading_0').attr('id', 'sheading_'+add_new_s);

        clone.find('#services_0').attr('id', 'services_'+add_new_s);
        clone.find('#deleteservices_0').attr('id', 'deleteservices_'+add_new_s);
        $('#deleteservices_0').hide();

        clone.removeClass("additional_s");
        $('#add_here_new_s').append(clone);

        resetChosen($("#sservice_"+add_new_s));
        resetChosen($("#scategory_"+add_new_s));
        resetChosen($("#sheading_"+add_new_s));

        add_new_s++;

        return false;
    });
});
$(document).on('change', 'select.serv_serv_onchange', function() { selectServiceService(this); });
$(document).on('change', 'select.serv_cat_onchange', function() { selectServiceCat(this); });
$(document).on('change', 'select[name="serviceid[]"]', function() { selectServiceHeading(this); });
function selectServiceService(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "sales_ajax_all.php?fill=s_service_config&value="+stage,
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
		url: "sales_ajax_all.php?fill=s_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sheading_"+arr[1]).html(response);
			$("#sheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function seleteService(sel, hide, blank) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#"+hide+arr[1]).hide();
    $("#"+blank+arr[1]).val('');

}
</script>

<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix">
            <?php if (strpos($value_config, ','."Services Service Type".',') !== FALSE) { ?>
            <label class="col-sm-3 text-center">Service Type</label>
            <?php } ?>
            <?php if (strpos($value_config, ','."Services Category".',') !== FALSE) { ?>
            <label class="col-sm-3 text-center">Category</label>
            <?php } ?>
            <?php if (strpos($value_config, ','."Services Heading".',') !== FALSE) { ?>
            <label class="col-sm-3 text-center">Heading</label>
            <?php } ?>
       </div>
        <?php if(!empty($_GET['salesid'])) {
            $each_serviceid = explode(',',$serviceid);
            $total_count = mb_substr_count($serviceid,',');
            $id_loop = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $serviceid = '';
                if(isset($each_serviceid[$inventory_loop])) {
                    $serviceid = $each_serviceid[$inventory_loop];
                }

                if($serviceid != '') {
            ?>

            <div class="form-group clearfix" id="<?php echo 'services_'.$id_loop; ?>" >
                <?php if (strpos($value_config, ','."Services Service Type".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Type..." id="<?php echo 'sservice_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid serv_serv_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT DISTINCT(`service_type`) FROM `services` WHERE `deleted`=0 ORDER BY `service_type`");
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
                <?php } ?>
                <?php if (strpos($value_config, ','."Services Category".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Category..." id="<?php echo 'scategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid serv_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT DISTINCT(`category`) FROM `services` WHERE `deleted`=0 ORDER BY `category`");
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
                <?php } ?>
                <?php if (strpos($value_config, ','."Services Heading".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Heading..." id="<?php echo 'sheading_'.$id_loop; ?>" name="serviceid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT `serviceid`, `heading` FROM `services` WHERE `deleted`=0 ORDER BY `heading`");
                        while($row = mysqli_fetch_array($query)) {
                            if ($serviceid == $row['serviceid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['serviceid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-1" >
                    <a href="#" onclick="seleteService(this,'services_','sheading_'); return false;" id="<?php echo 'deleteservices_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_s clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="services_0">
                <?php if (strpos($value_config, ','."Services Service Type".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Type..." id="sservice_0" class="chosen-select-deselect form-control equipmentid serv_serv_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(service_type) FROM services WHERE deleted=0 order by service_type");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['service_type']."'>".$row['service_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($value_config, ','."Services Category".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Category..." id="scategory_0" class="chosen-select-deselect form-control equipmentid serv_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM services WHERE deleted=0 order by category");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($value_config, ','."Services Heading".',') !== FALSE) { ?>
                <div class="col-sm-3">
                    <select data-placeholder="Choose a Heading..." id="sheading_0" name="serviceid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT serviceid, heading FROM services WHERE deleted=0 order by heading");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['serviceid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <a href="#" onclick="seleteService(this,'services_','sheading_'); return false;" id="deleteservices_0" class="btn brand-btn">Delete</a>
                </div>
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
