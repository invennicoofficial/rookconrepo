<script>
$(document).ready(function() {
    $('#stdelete_0').hide();
	//Staff
    var add_new_st = 1;
    $('#add_row_st').on( 'click', function () {
        $('#stdelete_0').show();
        var clone = $('.additional_st').clone();
        clone.find('.form-control').val('');

        clone.find('#stcontactid_0').attr('id', 'stcontactid_'+add_new_st);
        clone.find('#stmr_0').attr('id', 'stmr_'+add_new_st);
        clone.find('#stsmr_0').attr('id', 'stsmr_'+add_new_st);
        clone.find('#stdr_0').attr('id', 'stdr_'+add_new_st);
        clone.find('#sthr_0').attr('id', 'sthr_'+add_new_st);
        clone.find('#sthrt_0').attr('id', 'sthrt_'+add_new_st);
        clone.find('#stfdb_0').attr('id', 'stfdb_'+add_new_st);

        clone.find('#stdelete_0').attr('id', 'stdelete_'+add_new_st);
        clone.find('#packagestaff_0').attr('id', 'packagestaff_'+add_new_st);
        $('#stdelete_0').hide();

        clone.removeClass("additional_st");
        $('#add_here_new_st').append(clone);

        resetChosen($("#stcontactid_"+add_new_st));

        add_new_st++;

        return false;
    });
});
$(document).on('change', 'select[name="assign_staff[]"]', function() { selectStaff(this); });
//Staff
function selectStaff(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "package_ajax_all.php?fill=st_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#stmr_"+arr[1]).val(result[0]);
            $("#stsmr_"+arr[1]).val(result[1]);
            $("#stdr_"+arr[1]).val(result[2]);
            $("#sthr_"+arr[1]).val(result[3]);
            $("#sthrt_"+arr[1]).val(result[4]);
            $("#stfdc_"+arr[1]).val(result[5]);
            $("#stfdb_"+arr[1]).val(result[6]);

		}
	});
}
function deleteStaff(sel) {
	var typeId = sel.id;
	var arr = typeId.split('_');

    $("#packagestaff_"+arr[1]).hide();
    $("#stcontactid_"+arr[1]).val('');
    return false;
}
</script>
<?php
$get_field_config_staff = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT staff FROM field_config_contact"));
$field_config_staff = ','.$get_field_config_staff['staff'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-2 text-center">Contact Person</label>
            <?php if (strpos($field_config_staff, ','."Monthly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Monthly Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_staff, ','."Semi Monthly Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Semi Monthly Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_staff, ','."Daily Rate".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Daily Rate</label>
            <?php } ?>
            <?php if (strpos($field_config_staff, ','."HR Rate Work".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">HR Rate Work</label>
            <?php } ?>
            <?php if (strpos($field_config_staff, ','."HR Rate Travel".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">HR Rate Travel</label>
            <?php } ?>
            <?php if (strpos($field_config_staff, ','."Field Day Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Field Day Cost</label>
            <?php } ?>
            <?php if (strpos($field_config_staff, ','."Field Day Cost".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Field Day Billable</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Hours</label>
        </div>

        <?php if(!empty($_GET['packageid'])) {
            $each_assign_staff = explode('**',$assign_staff);
            $total_count = mb_substr_count($assign_staff,'**');
            $id_loop = 500;
            for($staff_loop=0; $staff_loop<$total_count; $staff_loop++) {

                $each_item = explode('#',$each_assign_staff[$staff_loop]);
                $staffid = '';
                $qty = '';
                if(isset($each_item[0])) {
                    $staffid = $each_item[0];
                }
                if(isset($each_item[1])) {
                    $qty = $each_item[1];
                }
                if($staffid != '') {
            ?>
                <div class="form-group clearfix" id="<?php echo 'packagestaff_'.$id_loop; ?>">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Contact Person:</label>
                    <select data-placeholder="Choose a Staff Member..." id="<?php echo 'stcontactid_'.$id_loop; ?>" name="assign_staff[]" class="chosen-select-deselect form-control" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." order by first_name");
                        while($row = mysqli_fetch_array($query)) {
                            if ($staffid == $row['contactid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <?php if (strpos($field_config_staff, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Monthly Rate:</label>
                    <input name="stmr[]" value="<?php echo get_staff_field($dbc, $staffid, 'monthly_rate');?>" id="<?php echo 'stmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Semi Monthly Rate:</label>
                    <input name="stsmr[]" value="<?php echo get_staff_field($dbc, $staffid, 'semi_monthly_rate');?>" id="<?php echo 'stsmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Daily Rate:</label>
                    <input name="stdr[]" value="<?php echo get_staff_field($dbc, $staffid, 'daily_rate');?>" id="<?php echo 'stdr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Work:</label>
                    <input name="sthr[]" value="<?php echo get_staff_field($dbc, $staffid, 'hr_rate_work');?>" id="<?php echo 'sthr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Travel:</label>
                    <input name="sthrt[]" value="<?php echo get_staff_field($dbc, $staffid, 'hr_rate_travel');?>" id="<?php echo 'sthrt_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Cost:</label>
                    <input name="stfdc[]" value="<?php echo get_staff_field($dbc, $staffid, 'field_day_cost');?>" id="<?php echo 'stfdc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Billable:</label>
                    <input name="stfdb[]" value="<?php echo get_staff_field($dbc, $staffid, 'field_day_billable');?>" id="<?php echo 'stfdb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Hours:</label>
                    <input name="assign_staff_quantity[]" value="<?php echo $qty;?>" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteStaff(this); return false;" id="<?php echo 'stdelete_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>


        <div class="additional_st clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="packagestaff_0">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Contact Person:</label>
                    <select data-placeholder="Choose a Staff Member..." id="stcontactid_0" name="assign_staff[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
						<?php
					$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status` > 0"),MYSQLI_ASSOC));
					foreach($query as $id) {
						$selected = '';
						//$selected = strpos($assign_staff, ','.$id.',') !== false ? 'selected = "selected"' : '';
						echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
					}
				  ?>
                    </select>
                </div>
                <?php if (strpos($field_config_staff, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Monthly Rate:</label>
                    <input name="stmr[]" id="stmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Semi Monthly Rate:</label>
                    <input name="stsmr[]" id="stsmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Daily Rate:</label>
                    <input name="stdr[]" id="stdr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Work:</label>
                    <input name="sthr[]" id="sthr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">HR Rate Travel:</label>
                    <input name="sthrt[]" id="sthrt_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Cost:</label>
                    <input name="stfdc[]" id="stfdc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Field Day Billable:</label>
                    <input name="stfdb[]" id="stfdb_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Hours:</label>
                    <input name="assign_staff_quantity[]" type="text" class="form-control" />
                </div>
                <a href="#" onclick="deleteStaff(this); return false;" id="stdelete_0" class="btn brand-btn">Delete</a>

            </div>

        </div>

        <div id="add_here_new_st"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_st" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
