<script>
$(document).ready(function() {
	//Services
    var add_new_sred = 1;
    $('#deletesred_0').hide();
    $('#add_row_sred').on( 'click', function () {
        $('#deletesred_0').show();
        var clone = $('.additional_sred').clone();
        clone.find('.form-control').val('');

        clone.find('#sredservice_0').attr('id', 'sredservice_'+add_new_sred);
		clone.find('#sredcategory_0').attr('id', 'sredcategory_'+add_new_sred);
        clone.find('#sredheading_0').attr('id', 'sredheading_'+add_new_sred);
        clone.find('#sredfrp_0').attr('id', 'sredfrp_'+add_new_sred);
        clone.find('#sredap_0').attr('id', 'sredap_'+add_new_sred);
        clone.find('#sredwp_0').attr('id', 'sredwp_'+add_new_sred);
        clone.find('#sredcomp_0').attr('id', 'sredcomp_'+add_new_sred);
        clone.find('#sredcp_0').attr('id', 'sredcp_'+add_new_sred);
        clone.find('#sredmsrp_0').attr('id', 'sredmsrp_'+add_new_sred);
		clone.find('#sredfinalprice_0').attr('id', 'sredfinalprice_'+add_new_sred);
		clone.find('#sredquoteprice_0').attr('id', 'sredquoteprice_'+add_new_sred);
        clone.find('#sred_0').attr('id', 'sred_'+add_new_sred);
        clone.find('#deletesred_0').attr('id', 'deletesred_'+add_new_sred);

        $('#deletesred_0').hide();

        clone.removeClass("additional_sred");
        $('#add_here_new_sred').append(clone);

        resetChosen($("#sredservice_"+add_new_sred));
        resetChosen($("#sredcategory_"+add_new_sred));
        resetChosen($("#sredheading_"+add_new_sred));

        add_new_sred++;

        return false;
    });
});
$(document).on('change', 'select.sred_type_onchange', function() { selectSrEd(this); });
$(document).on('change', 'select.sred_cat_onchange', function() { selectSrEdCat(this); });
$(document).on('change', 'select[name="sredid[]"]', function() { selectSrEdHeading(this); });
//Services
function selectSrEd(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=sred_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sredcategory_"+arr[1]).html(response);
			$("#sredcategory_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectSrEdCat(sel) {
	var stage = encodeURIComponent(sel.value);
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=sred_cat_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#sredheading_"+arr[1]).html(response);
			$("#sredheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectSrEdHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=sred_head_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#sredfrp_"+arr[1]).val(result[0]);
            $("#sredap_"+arr[1]).val(result[1]);
            $("#sredwp_"+arr[1]).val(result[2]);
            $("#sredcomp_"+arr[1]).val(result[3]);
            $("#sredcp_"+arr[1]).val(result[4]);
            $("#sredmsrp_"+arr[1]).val(result[5]);
		}
	});
}
</script>
<?php
$get_field_config_sred = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT sred FROM field_config"));
$field_config_sred = ','.$get_field_config_sred['sred'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <?php if (strpos($base_field_config, ','."SRED SRED Type".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">SR&ED Type</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."SRED Category".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Category</label>
            <?php } ?>
            <?php if (strpos($base_field_config, ','."SRED Heading".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Heading</label>
            <?php } ?>
            <?php if (strpos($field_config_sred, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_sred, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_sred, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_sred, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_sred, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_sred, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">MSRP</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_sred = explode('**', $sred);
            $total_count = mb_substr_count($sred,'**');
            $id_loop = 500;
            for($pid_loop=0; $pid_loop<$total_count; $pid_loop++) {

                $sredid = '';

                if(isset($each_sred[$pid_loop])) {
                    $each_val = explode('#', $each_sred[$pid_loop]);
                    $sredid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($sredid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'sred_'.$id_loop; ?>">
                <?php if (strpos($base_field_config, ','."SRED SRED Type".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">SR&ED Type:</label>
                    <select data-placeholder="Choose a Type..." id="<?php echo 'sredservice_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid sred_type_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(sred_type) FROM sred WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_sred($dbc, $sredid, 'sred_type') == $row['sred_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['sred_type']."'>".$row['sred_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <?php if (strpos($base_field_config, ','."SRED Category".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="<?php echo 'sredcategory_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid sred_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM sred WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_sred($dbc, $sredid, 'category') == $row['category']) {
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
                    <select data-placeholder="Choose a Heading..." id="<?php echo 'sredheading_'.$id_loop; ?>" name="sredid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT sredid, heading FROM sred WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if ($sredid == $row['sredid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['sredid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="<?php echo 'sheading_'.$id_loop; ?>" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_sred, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="sredfrp[]" value="<?php echo get_sred($dbc, $sredid, 'final_retail_price');?>" id="<?php echo 'sredfrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="sredap[]" value="<?php echo get_sred($dbc, $sredid, 'admin_price');?>" id="<?php echo 'sredap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="sredwp[]" value="<?php echo get_sred($dbc, $sredid, 'wholesale_price');?>" id="<?php echo 'sredwp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="sredcomp[]" value="<?php echo get_sred($dbc, $sredid, 'commercial_price');?>" id="<?php echo 'sredcomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="sredcp[]" value="<?php echo get_sred($dbc, $sredid, 'client_price');?>" id="<?php echo 'sredcp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="sredmsrp[]" value="<?php echo get_sred($dbc, $sredid, 'msrp');?>" id="<?php echo 'sredmsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="sredfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'sredfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'sred_','sredheading_'); return false;" id="<?php echo 'deletesred_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_sred clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="sred_0">
                <?php if (strpos($base_field_config, ','."SRED SRED Type".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">SR&ED Type:</label>
                    <select data-placeholder="Choose a Type..." id="sredservice_0" class="chosen-select-deselect form-control equipmentid sred_type_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(sred_type) FROM sred WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['sred_type']."'>".$row['sred_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (strpos($base_field_config, ','."SRED Category".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Category:</label>
                    <select data-placeholder="Choose a Category..." id="sredcategory_0" class="chosen-select-deselect form-control equipmentid sred_cat_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(category) FROM sred WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['category']."'>".$row['category'].'</option>';

                        }
                        ?>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="sredheading_0" name="sredid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT sredid, heading FROM sred WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['sredid']."'>".$row['heading'].'</option>';

                        }
                        ?>
                    </select>

                    <!-- <input name="sheading[]" readonly id="sheading_0" type="text" class="form-control" /> -->
                </div>

                <?php if (strpos($field_config_sred, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="sredfrp[]" id="sredfrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="sredap[]" id="sredap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="sredwp[]" id="sredwp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="sredcomp[]" id="sredcomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="sredcp[]" id="sredcp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_sred, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="sredmsrp[]" id="sredmsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="sredfinalprice[]" id="sredfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'sred_','sredheading_'); return false;" id="deletesred_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_sred"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_sred" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>