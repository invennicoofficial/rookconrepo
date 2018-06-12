<script>
$(document).ready(function() {
	//Contractor
    $('#deletecontractor_0').hide();
    var add_new_cnt = 1;
    $('#add_row_cnt').on( 'click', function () {
        $('#deletecontractor_0').show();
        var clone = $('.additional_cnt').clone();
        clone.find('.form-control').val('');

        clone.find('#cocontractorid_0').attr('id', 'cocontractorid_'+add_new_cnt);
        clone.find('#cntmr_0').attr('id', 'cntmr_'+add_new_cnt);
        clone.find('#cntsmr_0').attr('id', 'cntsmr_'+add_new_cnt);
        clone.find('#cntdr_0').attr('id', 'cntdr_'+add_new_cnt);
        clone.find('#cnthr_0').attr('id', 'cnthr_'+add_new_cnt);
        clone.find('#cnthrt_0').attr('id', 'cnthrt_'+add_new_cnt);
        clone.find('#cntfdb_0').attr('id', 'cntfdb_'+add_new_cnt);
		clone.find('#cntfinalprice_0').attr('id', 'cntfinalprice_'+add_new_cnt);
		clone.find('#cntquoteprice_0').attr('id', 'cntquoteprice_'+add_new_cnt);
        clone.find('#contractor_0').attr('id', 'contractor_'+add_new_cnt);
        clone.find('#deletecontractor_0').attr('id', 'deletecontractor_'+add_new_cnt);

        $('#deletecontractor_0').hide();

        clone.removeClass("additional_cnt");
        $('#add_here_new_cnt').append(clone);

        resetChosen($("#cocontractorid_"+add_new_cnt));

        add_new_cnt++;

        return false;
    });
});
$(document).on('change', 'select[name="contractorid[]"]', function() { selectContractor(this); });
//Contractor
function selectContractor(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=cnt_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#cntmr_"+arr[1]).val(result[0]);
            $("#cntsmr_"+arr[1]).val(result[1]);
            $("#cntdr_"+arr[1]).val(result[2]);
            $("#cnthr_"+arr[1]).val(result[3]);
            $("#cnthrt_"+arr[1]).val(result[4]);
            $("#cntfdc_"+arr[1]).val(result[5]);
            $("#cntfdb_"+arr[1]).val(result[6]);
		}
	});
}
</script>
<?php
$get_field_config_contractor = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT contractor FROM field_config_contact"));
$field_config_contractor = ','.$get_field_config_contractor['contractor'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-2 text-center">Contractor</label>
            <?php if (strpos($field_config_contractor, ','."Monthly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Monthly Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_contractor, ','."Semi Monthly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Semi Monthly Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_contractor, ','."Daily Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Daily Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_contractor, ','."HR Rate Work".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">HR Rate Work</label>
            <?php } ?>
            <?php if (strpos($field_config_contractor, ','."HR Rate Travel".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">HR Rate Travel</label>
            <?php } ?>
            <?php if (strpos($field_config_contractor, ','."Field Day Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Field Day Cost</label>
            <?php } ?>
            <?php if (strpos($field_config_contractor, ','."Field Day Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Field Day Billable</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_contractor = explode('**', $contractor);
            $total_count = mb_substr_count($contractor,'**');
            $id_loop = 500;
            for($pid_loop=0; $pid_loop<$total_count; $pid_loop++) {

                $contactid = '';

                if(isset($each_contractor[$pid_loop])) {
                    $each_val = explode('#', $each_contractor[$pid_loop]);
                    $contactid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($contactid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'contractor_'.$id_loop; ?>">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Contractor:</label>
                    <select data-placeholder="Choose a Contractor..." id="<?php echo 'cocontractorid_'.$id_loop; ?>" name="contractorid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Contractor' ORDER BY name");
                        while($row = mysqli_fetch_array($query)) {
                            if ($contactid == $row['contactid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php if (strpos($field_config_contractor, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Monthly Rate:</label>
                    <input name="cntmr[]" value="<?php echo get_contact($dbc, $contactid, 'monthly_rate');?>" id="<?php echo 'cntmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Semi Monthly Rate:</label>
                    <input name="cntsmr[]" value="<?php echo get_contact($dbc, $contactid, 'semi_monthly_rate');?>" id="<?php echo 'cntsmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Daily Rate:</label>
                    <input name="cntdr[]" value="<?php echo get_contact($dbc, $contactid, 'daily_rate');?>" id="<?php echo 'cntdr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Work:</label>
                    <input name="cnthr[]" value="<?php echo get_contact($dbc, $contactid, 'hr_rate_work');?>" id="<?php echo 'cnthr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Travel:</label>
                    <input name="cnthrt[]" value="<?php echo get_contact($dbc, $contactid, 'hr_rate_travel');?>" id="<?php echo 'cnthrt_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Cost:</label>
                    <input name="cntfdc[]" value="<?php echo get_contact($dbc, $contactid, 'field_day_cost');?>" id="<?php echo 'cntfdc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Billable:</label>
                    <input name="cntfdb[]" value="<?php echo get_contact($dbc, $contactid, 'field_day_billable');?>" id="<?php echo 'cntfdb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="cntfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'cntfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'contractor_','cocontractorid_'); return false;" id="<?php echo 'deletecontractor_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>


        <div class="additional_cnt clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="contractor_0">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Contractor:</label>
                    <select data-placeholder="Choose a Contractor..." id="cocontractorid_0" name="contractorid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
						<?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Contractor' AND deleted=0 AND `status` > 0"),MYSQLI_ASSOC));
							foreach($query as $id) {
								$selected = '';
								//$selected = $id == $search_user ? 'selected = "selected"' : '';
								echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
							}
						  ?>
                    </select>
                </div>
                <?php if (strpos($field_config_contractor, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Monthly Rate:</label>
                    <input name="cntmr[]" id="cntmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Semi Monthly Rate:</label>
                    <input name="cntsmr[]" id="cntsmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Daily Rate:</label>
                    <input name="cntdr[]" id="cntdr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Work:</label>
                    <input name="cnthr[]" id="cnthr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Travel:</label>
                    <input name="cnthrt[]" id="cnthrt_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Cost:</label>
                    <input name="cntfdc[]" id="cntfdc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Billable:</label>
                    <input name="cntfdb[]" id="cntfdb_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="cntfinalprice[]" id="cntfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'contractor_','cocontractorid_'); return false;" id="deletecontractor_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_cnt"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_cnt" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>