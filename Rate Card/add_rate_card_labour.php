<script>
$(document).ready(function() {
	//Services
    $('#deletelabour_0').hide();
    var add_new_l = 1;
    $('#add_row_l').on( 'click', function () {
        $('#deletelabour_0').show();
        var clone = $('.additional_l').clone();
        clone.find('.form-control').val('');

        clone.find('#labour_0').attr('id', 'labour_'+add_new_l);
        clone.find('#lheading_0').attr('id', 'lheading_'+add_new_l);
		clone.find('#lhr_0').attr('id', 'lhr_'+add_new_l);
		clone.find('#lfinalprice_0').attr('id', 'lfinalprice_'+add_new_l);
		clone.find('#lquoteprice_0').attr('id', 'lquoteprice_'+add_new_l);
        clone.find('#labourfull_0').attr('id', 'labourfull_'+add_new_l);
        clone.find('#deletelabour_0').attr('id', 'deletelabour_'+add_new_l);

        $('#deletelabour_0').hide();

        clone.removeClass("additional_l");
        $('#add_here_new_l').append(clone);

        resetChosen($("#labour_"+add_new_l));
        resetChosen($("#lheading_"+add_new_l));

        add_new_l++;

        return false;
    });
});
$(document).on('change', 'select.labour_onchange', function() { selectLabour(this); });
$(document).on('change', 'select[name="labourid[]"]', function() { selectLabourHeading(this); });

//Services
function selectLabour(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=labour_type_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            $("#lheading_"+arr[1]).html(response);
			$("#lheading_"+arr[1]).trigger("change.select2");
		}
	});
}

function selectLabourHeading(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=l_head_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#lhr_"+arr[1]).val(result[0]);
		}
	});
}
</script>
<?php
$get_field_config_labour = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT labour FROM field_config"));
$field_config_labour = ','.$get_field_config_labour['labour'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
            <label class="col-sm-2 text-center">Labour Type</label>
            <?php } ?>
            <label class="col-sm-2 text-center">Heading</label>
            <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Hourly Rate</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_labour = explode('**', $labour);
            $total_count = mb_substr_count($labour,'**');
            $id_loop = 500;
            for($pid_loop=0; $pid_loop<$total_count; $pid_loop++) {

                $labourid = '';

                if(isset($each_labour[$pid_loop])) {
                    $each_val = explode('#', $each_labour[$pid_loop]);
                    $labourid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($labourid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'labourfull_'.$id_loop; ?>">
                <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Labour Type:</label>
                    <select data-placeholder="Choose a Labour Type..." id="<?php echo 'labour_'.$id_loop; ?>" class="chosen-select-deselect form-control equipmentid labour_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(labour_type) FROM services WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if (get_labour($dbc, $serviceid, 'labour_type') == $row['labour_type']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['labour_type']."'>".$row['labour_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="<?php echo 'lheading_'.$id_loop; ?>" name="labourid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT labourid, heading FROM labour WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            if ($labourid == $row['labourid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['labourid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Hourly Rate:</label>
                    <input name="lhr[]" value="<?php echo get_labour($dbc, $labourid, 'hourly_rate');?>" id="<?php echo 'lhr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="lfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'lfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'labourfull_','lheading_'); return false;" id="<?php echo 'deletelabour_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_l clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="labourfull_0">
                <?php if (strpos($base_field_config, ','."Labour Type".',') !== FALSE) { ?>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Labour Type:</label>
                    <select data-placeholder="Choose a Labour Type..." id="labour_0" class="chosen-select-deselect form-control equipmentid labour_onchange" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT distinct(labour_type) FROM labour WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['labour_type']."'>".$row['labour_type'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php } ?>

                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Heading:</label>
                    <select data-placeholder="Choose a Heading..." id="lheading_0" name="labourid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT labourid, heading FROM labour WHERE deleted=0");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['labourid']."'>".$row['heading'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <?php if (strpos($field_config_labour, ','."Hourly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Hourly Rate:</label>
                    <input name="lhr[]" id="lhr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>

                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="lfinalprice[]" id="lfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'labourfull_','lheading_'); return false;" id="deletelabour_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_l"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_l" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
