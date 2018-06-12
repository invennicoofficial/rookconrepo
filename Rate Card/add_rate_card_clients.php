<script>
$(document).ready(function() {
	//Clients
    $('#deleteclients_0').hide();
	var add_new_cl = 1;
    $('#add_row_cl').on( 'click', function () {
        $('#deleteclients_0').show();
        var clone = $('.additional_cl').clone();
        clone.find('.form-control').val('');

        clone.find('#clclientid_0').attr('id', 'clclientid_'+add_new_cl);
		clone.find('#clclientperson_0').attr('id', 'clclientperson_'+add_new_cl);
        clone.find('#clrp_0').attr('id', 'clrp_'+add_new_cl);
        clone.find('#clap_0').attr('id', 'clap_'+add_new_cl);
        clone.find('#clwp_0').attr('id', 'clwp_'+add_new_cl);
        clone.find('#clcomp_0').attr('id', 'clcomp_'+add_new_cl);
        clone.find('#clcp_0').attr('id', 'clcp_'+add_new_cl);
        clone.find('#clmsrp_0').attr('id', 'clmsrp_'+add_new_cl);
		clone.find('#clfinalprice_0').attr('id', 'clfinalprice_'+add_new_cl);
        clone.find('#clients_0').attr('id', 'clients_'+add_new_cl);
        clone.find('#deleteclients_0').attr('id', 'deleteclients_'+add_new_cl);

        $('#deleteclients_0').hide();

        clone.removeClass("additional_cl");
        $('#add_here_new_cl').append(clone);

        resetChosen($("#clclientid_"+add_new_cl));
        resetChosen($("#clclientperson_"+add_new_cl));

        add_new_cl++;

        return false;
    });
});
$(document).on('change', 'select[name="clientid[]"]', function() { selectClientName(this); });
$(document).on('change', 'select[name="clientperson[]"]', function() { selectClientName(this); });
//Clients
function selectClientName(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');

	$.ajax({
		type: "GET",
		url: "ratecard_ajax_all.php?fill=cl_config&value="+stage,
		dataType: "html",   //expect html to be returned
		success: function(response){
            var result = response.split('*');
            $("#clrp_"+arr[1]).val(result[0]);
            $("#clap_"+arr[1]).val(result[1]);
            $("#clwp_"+arr[1]).val(result[2]);
            $("#clcomp_"+arr[1]).val(result[3]);
            $("#clcp_"+arr[1]).val(result[4]);
            $("#clmsrp_"+arr[1]).val(result[5]);

            $("#clclientid_"+arr[1]).html(result[6]);
			$("#clclientid_"+arr[1]).trigger("change.select2");
            $("#clclientperson_"+arr[1]).html(result[7]);
			$("#clclientperson_"+arr[1]).trigger("change.select2");
		}
	});
}
</script>
<?php
$get_field_config_clients = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT clients FROM field_config_contact"));
$field_config_clients = ','.$get_field_config_clients['clients'].',';
?>
<div class="form-group">
    <div class="col-sm-12">
        <div class="form-group clearfix hide-titles-mob">
            <label class="col-sm-2 text-center">Client</label>
            <label class="col-sm-2 text-center">Contact Person</label>
            <?php if (strpos($field_config_clients, ','."Final Retail Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Final Retail Price</label>
            <?php } ?>
            <?php if (strpos($field_config_clients, ','."Admin Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Admin Price</label>
            <?php } ?>
            <?php if (strpos($field_config_clients, ','."Wholesale Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Wholesale Price</label>
            <?php } ?>
            <?php if (strpos($field_config_clients, ','."Commercial Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Commercial Price</label>
            <?php } ?>
            <?php if (strpos($field_config_clients, ','."Client Price".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Client Price</label>
            <?php } ?>
            <?php if (strpos($field_config_clients, ','."MSRP".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">MSRP</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Rate Card Price</label>
        </div>

        <?php if(!empty($_GET['ratecardid'])) {
            $each_client = explode('**', $client);
            $total_count = mb_substr_count($client,'**');
            $id_loop = 500;
            for($pid_loop=0; $pid_loop<$total_count; $pid_loop++) {

                $contactid = '';

                if(isset($each_client[$pid_loop])) {
                    $each_val = explode('#', $each_client[$pid_loop]);
                    $contactid = $each_val[0];
                    $ratecardprice = $each_val[1];
                }

                if($contactid != '') {
            ?>
            <div class="form-group clearfix" id="<?php echo 'clients_'.$id_loop; ?>">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Client:</label>
                    <select data-placeholder="Choose a Client..." id="<?php echo 'clclientid_'.$id_loop; ?>" name="clientid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Client' AND deleted=0 order by name");
                        while($row = mysqli_fetch_array($query)) {
                            if ($contactid == $row['contactid']) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            echo "<option ".$selected." value='". $row['contactid']."'>".$row['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Contact Person:</label>
                    <select data-placeholder="Choose a Contact..." id="<?php echo 'clclientperson_'.$id_loop; ?>" name="clientperson[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Client' AND deleted=0 order by first_name");
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
                 <?php if (strpos($field_config_clients, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="clrp[]" value="<?php echo get_contact($dbc, $contactid, 'final_retail_price');?>" id="<?php echo 'clrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="clap[]" value="<?php echo get_contact($dbc, $contactid, 'admin_price');?>" id="<?php echo 'clap_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="clwp[]" value="<?php echo get_contact($dbc, $contactid, 'wholesale_price');?>" id="<?php echo 'clwp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="clcomp[]" value="<?php echo get_contact($dbc, $contactid, 'commercial_price');?>" id="<?php echo 'clcomp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="clcp[]" value="<?php echo get_contact($dbc, $contactid, 'client_price');?>" id="<?php echo 'clcp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="clmsrp[]" value="<?php echo get_contact($dbc, $contactid, 'msrp');?>" id="<?php echo 'clmsrp_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="clfinalprice[]" value="<?php echo $ratecardprice;?>" id="<?php echo 'clfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'clients_','clclientid_'); return false;" id="<?php echo 'deleteclients_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
        <?php  $id_loop++;
                }
            }
        } ?>

        <div class="additional_cl clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="clients_0">
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Client:</label>
                    <select data-placeholder="Choose a Client..." id="clclientid_0" name="clientid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, name FROM contacts WHERE category='Client' AND deleted=0 order by name");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['contactid']."'>".decryptIt($row['name']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-2"><label for="company_name" class="col-sm-4 show-on-mob control-label">Contact Person:</label>
                    <select data-placeholder="Choose a Contact..." id="clclientperson_0" name="clientperson[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
						<?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Client' AND deleted=0 AND `status` > 0"),MYSQLI_ASSOC));
							foreach($query as $id) {
								$selected = '';
								//$selected = $id == $search_user ? 'selected = "selected"' : '';
								echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
							}
						  ?>
                    </select>
                </div>
                <?php if (strpos($field_config_clients, ','."Final Retail Price".',') !== FALSE) { ?>
                <div class="col-sm-1"><label for="company_name" class="col-sm-4 show-on-mob control-label">Final Retail Price:</label>
                    <input name="clrp[]" id="clrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Admin Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Admin Price:</label>
                    <input name="clap[]" id="clap_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Wholesale Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Wholesale Price:</label>
                    <input name="clwp[]" id="clwp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Commercial Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Commercial Price:</label>
                    <input name="clcomp[]" id="clcomp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."Client Price".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Client Price:</label>
                    <input name="clcp[]" id="clcp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_clients, ','."MSRP".',') !== FALSE) { ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">MSRP:</label>
                    <input name="clmsrp[]" id="clmsrp_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" ><label for="company_name" class="col-sm-4 show-on-mob control-label">Rate Card Price:</label>
                    <input name="clfinalprice[]" id="clfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteRatecard(this,'clients_','clclientid_'); return false;" id="deleteclients_0" class="btn brand-btn">Delete</a>
                </div>
            </div>

        </div>

        <div id="add_here_new_cl"></div>

        <div class="form-group triple-gapped clearfix">
            <div class="col-sm-offset-4 col-sm-8">
                <button id="add_row_cl" class="btn brand-btn pull-left">Add Row</button>
            </div>
        </div>
    </div>
</div>
