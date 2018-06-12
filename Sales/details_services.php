<!-- Services -->
<script type="text/javascript">
var add_new_s = 1;
$(document).ready(function() {
    $('#deleteservices_0').hide();
});
$(document).on('change', 'select.serv_serv_onchange', function() { selectServiceService(this); });
$(document).on('change', 'select.serv_cat_onchange', function() { selectServiceCat(this); });
$(document).on('change', 'select[name="serviceid[]"]', function() { selectServiceHeading(this); });

// function selectServiceService(sel) {
// 	var stage = sel.value;
// 	var typeId = sel.id;
// 	var arr = typeId.split('_');
// 	$.ajax({
// 		type: "GET",
// 		url: "sales_ajax_all.php?fill=s_service_config&value="+stage,
// 		dataType: "html",
// 		success: function(response){
//             /* $("#scategory_"+arr[1]).html(response);
// 			$("#scategory_"+arr[1]).trigger("change.select2"); */
//             $("#sheading_"+arr[1]).html(response);
// 			$("#sheading_"+arr[1]).trigger("change.select2");
// 		}
// 	});
// }

// function selectServiceCat(sel) {
// 	var stage = encodeURIComponent(sel.value);
// 	var typeId = sel.id;
// 	var arr = typeId.split('_');

// 	$.ajax({
// 		type: "GET",
// 		url: "sales_ajax_all.php?fill=s_cat_config&value="+stage,
// 		dataType: "html",
// 		success: function(response){
//             /* $("#sheading_"+arr[1]).html(response);
// 			$("#sheading_"+arr[1]).trigger("change.select2"); */
//             $("#sservice_"+arr[1]).html(response);
// 			$("#sservice_"+arr[1]).trigger("change.select2");
// 		}
// 	});
// }
function addNewService() {
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
}

function selectServiceCat(sel) {
    var stage = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');

    $("#sservice_"+arr[1]).find('option').hide();
    $("#sservice_"+arr[1]).find('option[data-category="'+stage+'"]').show();
    $("#sservice_"+arr[1]).trigger("change.select2");

    var type_filter = '';
    if($("#sservice_"+arr[1]).val() != '' && $("#sservice_"+arr[1]).val() != undefined) {
        type_filter = '[data-service-type="'+$("#sservice_"+arr[1]).val()+'"]';
    }

    $("#sheading_"+arr[1]).find('option').hide();
    $("#sheading_"+arr[1]).find('option[data-category="'+stage+'"]'+type_filter).show();
    $("#sheading_"+arr[1]).trigger("change.select2");
}

function selectServiceService(sel) {
    var stage = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');

    $("#scategory_"+arr[1]).val($(sel).find('option:selected').data('category'));
    $("#scategory_"+arr[1]).trigger("change.select2");

    var cat_filter = '';
    if($("#scategory_"+arr[1]).val() != '' && $("#scategory_"+arr[1]).val() != undefined) {
        cat_filter = '[data-category="'+$("#scategory_"+arr[1]).val()+'"]';
    }

    $("#sheading_"+arr[1]).find('option').hide();
    $("#sheading_"+arr[1]).find('option[data-service-type="'+stage+'"]'+cat_filter).show();
    $("#sheading_"+arr[1]).trigger("change.select2");
}

function selectServiceHeading(sel) {
    var stage = sel.value;
    var typeId = sel.id;
    var arr = typeId.split('_');

    $("#scategory_"+arr[1]).val($(sel).find('option:selected').data('category'));
    $("#scategory_"+arr[1]).trigger("change.select2");
    $("#sservice_"+arr[1]).val($(sel).find('option:selected').data('service-type'));
    $("#sservice_"+arr[1]).trigger("change.select2");    
}

function seleteService(sel, hide, blank) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#"+hide+arr[1]).hide();
    $("#"+blank+arr[1]).val('');

}
</script>

<div class="accordion-block-details padded" id="services">
    <div class="accordion-block-details-heading"><h4>Services</h4></div>
    
    <div class="row"><?php
        if (strpos($value_config, ',Services Category,') !== false) { echo '<div class="col-sm-3 gap-md-left-15 gap-bottom"><b>Category</b></div>'; }
        if (strpos($value_config, ',Services Service Type,') !== false) { echo '<div class="col-sm-3 gap-md-left-15 gap-bottom"><b>Service Type</b></div>'; }
        if (strpos($value_config, ',Services Heading,') !== false) { echo '<div class="col-sm-3 gap-md-left-15 gap-bottom"><b>Heading</b></div>'; } ?>
        <div class="clearfix"></div><?php
        
        if ( !empty($salesid) ) {
            $each_serviceid = explode(',', $serviceid);
            $total_count    = mb_substr_count($serviceid,',');
            $id_loop        = 500;

            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {
                $serviceid = '';
                
                if (isset($each_serviceid[$inventory_loop])) {
                    $serviceid = $each_serviceid[$inventory_loop];
                }

                if ($serviceid != '') {
                    $current_category = get_services($dbc, $serviceid, 'category');
                    $current_service_type = get_services($dbc, $serviceid, 'service_type'); ?>
                    <div class="set-row-height" id="<?= 'services_'.$id_loop; ?>"><?php
                        if (strpos($value_config, ',Services Category,') !== false) { ?>
                            <div class="col-sm-3 gap-md-left-15">
                                <select data-placeholder="Choose a Category..." id="<?php echo 'scategory_'.$id_loop; ?>" class="chosen-select-deselect form-control serv_cat_onchange">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT DISTINCT(`category`) FROM `services` WHERE `deleted`=0 ORDER BY `category`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = ($current_category == $row['category']) ? 'selected="selected"' : '';
                                        echo '<option '. $selected .' value="'. $row['category'] .'">'. $row['category'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        }
                        
                        if (strpos($value_config, ',Services Service Type,') !== false) { ?>
                            <div class="col-sm-3 gap-md-left-15">
                                <select data-placeholder="Choose a Type..." id="<?php echo 'sservice_'.$id_loop; ?>" class="chosen-select-deselect form-control serv_serv_onchange">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT DISTINCT(CONCAT(`service_type`,`category`)), `service_type`, `category` FROM `services` WHERE `deleted`=0 ORDER BY `service_type`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = ($current_service_type == $row['service_type']) ? 'selected="selected"' : '';
                                        echo '<option data-category="'.$row['category'].'" '.($row['category'] != $current_category && !empty($current_category) ? 'style="display: none;"' : '').' '. $selected .' value="'. $row['service_type'] .'">'. $row['service_type'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        }
                        
                        if (strpos($value_config, ',Services Heading,') !== false) { ?>
                            <div class="col-sm-3 gap-md-left-15">
                                <select data-placeholder="Choose a Heading..." id="<?php echo 'sheading_'.$id_loop; ?>" name="serviceid[]" class="chosen-select-deselect form-control">
                                    <option value=""></option><?php
                                    $query = mysqli_query($dbc,"SELECT `serviceid`, `heading`, `category`, `service_type` FROM `services` WHERE `deleted`=0 ORDER BY `heading`");
                                    while($row = mysqli_fetch_array($query)) {
                                        $selected = ($serviceid == $row['serviceid']) ? 'selected="selected"' : '';
                                        echo '<option data-category="'.$row['category'].'" data-service-type="'.$row['service_type'].'" '.(($row['category'] != $current_category && !empty($current_category) || ($row['service_type'] != $current_service_type && !empty($current_service_type))) ? 'style="display: none;"' : '').' '. $selected .' value="'. $row['serviceid'] .'">'. $row['heading'] .'</option>';
                                    } ?>
                                </select>
                            </div><?php
                        } ?>

                        <div class="col-sm-1" >
                            <a href="#" onclick="seleteService(this,'services_','sheading_'); return false;" id="<?= 'deleteservices_'.$id_loop; ?>"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                            <a href="#" onclick="addNewService(); return false;" id="add_row_s"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                        </div>
                        
                        <div class="clearfix"></div>
                    </div><?php
                    
                    $id_loop++;
                }
            }
        } ?>
        
        <div class="additional_s">

            <div class="set-row-height" id="services_0"><?php
                if (strpos($value_config, ',Services Category,') !== false) { ?>
                    <div class="col-sm-3 gap-md-left-15">
                        <select data-placeholder="Choose a Category..." id="scategory_0" class="chosen-select-deselect form-control serv_cat_onchange">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc, "SELECT DISTINCT(`category`) FROM `services` WHERE `deleted`=0 ORDER BY `category`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option value="'. $row['category'] .'">'. $row['category'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                }
                
                if (strpos($value_config, ',Services Service Type,') !== false) { ?>
                    <div class="col-sm-3 gap-md-left-15">
                        <select data-placeholder="Choose a Type..." id="sservice_0" class="chosen-select-deselect form-control serv_serv_onchange">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc,"SELECT DISTINCT(CONCAT(`service_type`,`category`)), `service_type`, `category` FROM `services` WHERE `deleted`=0 ORDER BY `service_type`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option data-category="'.$row['category'].'" value="'. $row['service_type'] .'">'. $row['service_type'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                }
                
                if (strpos($value_config, ',Services Heading,') !== false) { ?>
                    <div class="col-sm-3 gap-md-left-15">
                        <select data-placeholder="Choose a Heading..." id="sheading_0" name="serviceid[]" class="chosen-select-deselect form-control">
                            <option value=""></option><?php
                            $query = mysqli_query($dbc, "SELECT `serviceid`, `heading`, `service_type`, `category` FROM `services` WHERE `deleted`=0 ORDER BY `heading`");
                            while($row = mysqli_fetch_array($query)) {
                                echo '<option data-category="'.$row['category'].'" data-service-type="'.$row['service_type'].'" value="'. $row['serviceid'] .'">'. $row['heading'] .'</option>';
                            } ?>
                        </select>
                    </div><?php
                } ?>
                
                <div class="col-sm-1 pad-5">
                    <a href="#" onclick="seleteService(this,'services_','sheading_'); return false;" id="deleteservices_0"><img src="<?= WEBSITE_URL; ?>/img/remove.png" height="20" /></a>
                    <a href="#" onclick="addNewService(); return false;" id="add_row_s"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
                </div>
                
                <div class="clearfix"></div>
            </div>
        </div><!-- .additional_s -->

        <div id="add_here_new_s"></div>
        
        <!-- <div class="col-sm-12 gap-md-left-10 gap-top">
            <a href="#" id="add_row_s" class="gap-md-left-15"><img src="<?= WEBSITE_URL; ?>/img/icons/ROOK-add-icon.png" height="20" /></a>
        </div> -->
        
        <div class="clearfix"></div>
        
    </div>
    <div class="clearfix"></div>
    
</div><!-- .accordion-block-details -->