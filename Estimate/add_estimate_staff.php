<script>
$(document).ready(function() {
	//Staff
    var add_new_st = 1;
    $('#deletestaff_0').hide();
    $('#add_row_st').on( 'click', function () {
        $('#deletestaff_0').show();
        var clone = $('.additional_st').clone();
        clone.find('.form-control').val('');

        clone.find('#stcontactid_0').attr('id', 'stcontactid_'+add_new_st);
        clone.find('#stmr_0').attr('id', 'stmr_'+add_new_st);
        clone.find('#stsmr_0').attr('id', 'stsmr_'+add_new_st);
        clone.find('#stdr_0').attr('id', 'stdr_'+add_new_st);
        clone.find('#sthr_0').attr('id', 'sthr_'+add_new_st);
        clone.find('#sthrt_0').attr('id', 'sthrt_'+add_new_st);
        clone.find('#stfdb_0').attr('id', 'stfdb_'+add_new_st);
		clone.find('#stfinalprice_0').attr('id', 'stfinalprice_'+add_new_st);
		clone.find('#stestimateprice_0').attr('id', 'stestimateprice_'+add_new_st);
		clone.find('#stestimateqty_0').attr('id', 'stestimateqty_'+add_new_st);
		clone.find('#stestimateunit_0').attr('id', 'stestimateunit_'+add_new_st);
		clone.find('#stestimatetotal_0').attr('id', 'stestimatetotal_'+add_new_st);

        clone.find('#staff_0').attr('id', 'staff_'+add_new_st);
        clone.find('#deletestaff_0').attr('id', 'deletestaff_'+add_new_st);
        $('#deletestaff_0').hide();

        clone.removeClass("additional_st");
        $('#add_here_new_st').append(clone);

        resetChosen($("#stcontactid_"+add_new_st));

        add_new_st++;

        return false;
    });
});
//Staff
function selectStaff(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=st_config&value="+stage+"&ratecardid="+ratecardid,
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
			$("#stfinalprice_"+arr[1]).val(result[7]);

		}
	});
}
function countStaff(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');

        document.getElementById('stestimatetotal_'+split_id[1]).value = parseFloat($('#stestimateprice_'+split_id[1]).val() * $('#stestimateqty_'+split_id[1]).val());
    }

    var sum_fee = 0;
    $('[name="stestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_staff_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="staff_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="staff_summary"]').val('$'+round2Fixed(sum_fee)).change();

    var staff_budget = $('[name="staff_budget"]').val();
    if(staff_budget >= sum_fee) {
        $('[name="staff_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="staff_total"]').css("background-color", "#ff9999"); // Green
    }
}

function countRCTotalStaff(sel) {
	var stage = sel.value;
	var typeId = sel.id;

	var arr = typeId.split('_');
    var del = (jQuery('#crc_staff_custprice_'+arr[3]).val() * jQuery('#crc_staff_qty_'+arr[3]).val());
    jQuery('#crc_staff_total_'+arr[3]).val(round2Fixed(del));

    var sum_fee = 0;
    $('[name="stestimatetotal[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_staff_total[]"]').each(function () {
        sum_fee += +$(this).val() || 0;
    });

    $('[name="product_total"]').val(round2Fixed(sum_fee));

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
            <?php if (strpos($field_config_staff, ','."Field Day Billable".',') !== FALSE) { ?>
            <label class="col-sm-1 text-center">Field Day Billable</label>
            <?php } ?>
            <label class="col-sm-1 text-center">Unit of Measure</label>
            <label class="col-sm-1 text-center">Quantity</label>
            <label class="col-sm-1 text-center">Rate Card Price</label>
            <label class="col-sm-1 text-center">Estimate Price</label>
            <label class="col-sm-1 text-center">Total</label>
        </div>

       <?php
        $get_staff = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_staff'),'**#**');
                $get_staff  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_staff'),'**#**');
                $get_staff  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_staff'),'**#**');
                $get_staff  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['estimateid'])) {
            $staff = $get_contact['staff'];
            $each_data = explode('**',$staff);
            foreach($each_data as $id_all) {
                if($id_all != '') {
                    $data_all = explode('#',$id_all);
                    $get_staff .= '**'.$data_all[0].'#'.$data_all[2].'#'.$data_all[1].'#'.$data_all[3];
                }
            }
        }
        $final_total_staff = 0;
        ?>

        <?php if(!empty($get_staff)) {
            $each_assign_inventory = explode('**',$get_staff);
            $total_count = mb_substr_count($get_staff,'**');
            $id_loop = 500;
            for($inventory_loop=0; $inventory_loop<=$total_count; $inventory_loop++) {

                $each_item = explode('#',$each_assign_inventory[$inventory_loop]);
                $contactid = '';
                $qty = '';
                $est = '';
                $unit = '';
                if(isset($each_item[0])) {
                    $contactid = $each_item[0];
                }
                if(isset($each_item[1])) {
                    $qty = $each_item[1];
                }
                if(isset($each_item[2])) {
                    $est = $each_item[2];
                }
                if(isset($each_item[3])) {
                    $unit = $each_item[3];
                }
                $total = $qty*$est;
                $final_total_staff += $total;

                if($contactid != '') {
                    $rc_price = 0;
                    $staff = explode('**', $get_rc['staff']);
                    foreach($staff as $pp){
                        if (strpos('#'.$pp, '#'.$contactid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>
            <div class="form-group clearfix" id="<?php echo 'staff_'.$id_loop; ?>" >
                <div class="col-sm-2">
                    <select onChange='selectStaff(this)' data-placeholder="Choose a Staff Member..." id="<?php echo 'stcontactid_'.$id_loop; ?>" name="contactid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." order by first_name");
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
                <?php if (strpos($field_config_staff, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1">
                    <input name="stmr[]" value="<?php echo get_contact($dbc, $contactid, 'monthly_rate');?>" id="<?php echo 'stmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stsmr[]" value="<?php echo get_contact($dbc, $contactid, 'semi_monthly_rate');?>" id="<?php echo 'stsmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stdr[]" value="<?php echo get_contact($dbc, $contactid, 'daily_rate');?>" id="<?php echo 'stdr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="sthr[]" value="<?php echo get_contact($dbc, $contactid, 'hr_rate_work');?>" id="<?php echo 'sthr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="sthrt[]" value="<?php echo get_contact($dbc, $contactid, 'hr_rate_travel');?>" id="<?php echo 'sthrt_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stfdc[]" value="<?php echo get_contact($dbc, $contactid, 'field_day_cost');?>" id="<?php echo 'stfdc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stfdb[]" value="<?php echo get_contact($dbc, $contactid, 'field_day_billable');?>" id="<?php echo 'stfdb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="stestimateunit[]" id="<?php echo 'stestimateunit_'.$id_loop; ?>" readonly value="<?php echo $unit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="stestimateqty[]" id="<?php echo 'stestimateqty_'.$id_loop; ?>" onchange="countStaff(this)" value="<?php echo $qty; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="stfinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'stfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" >
                    <input name="stestimateprice[]" value="<?php echo $est; ?>" id="<?php echo 'stestimateprice_'.$id_loop; ?>" onchange="countStaff(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="stestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'stestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'staff_','stcontactid_'); return false;" id="<?php echo 'deletestaff_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>

            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_st clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="staff_0">
                <div class="col-sm-2">
                    <select onChange='selectStaff(this)' data-placeholder="Choose a Staff Member..." id="stcontactid_0" name="contactid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
						<?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category IN (".STAFF_CATS.") AND ".STAFF_CATS_HIDE_QUERY." AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
							foreach($query as $id) {
								$selected = '';
								//$selected = strpos(','.$foremanid.',', ','.$id.',') !== FALSE ? 'selected = "selected"' : '';
								echo "<option " . $selected . "value='". $id."'>".get_contact($dbc, $id).'</option>';
							}
						?>

                    </select>
                </div>
                <?php if (strpos($field_config_staff, ','."Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1">
                    <input name="stmr[]" id="stmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Semi Monthly Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stsmr[]" id="stsmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stdr[]" id="stdr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="sthr[]" id="sthr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="sthrt[]" id="sthrt_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stfdc[]" id="stfdc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_staff, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="stfdb[]" id="stfdb_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="stfinalprice[]" readonly id="stfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="stestimateprice[]" id='stestimateprice_0' onchange="countStaff(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="stestimateqty[]" id='stestimateqty_0' onchange="countStaff(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="stestimateunit[]" id='stestimateunit_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="stestimatetotal[]" id='stestimatetotal_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'staff_','stcontactid_'); return false;" id="deletestaff_0" class="btn brand-btn">Delete</a>
                </div>
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

<?php
if(!empty($_GET['estimateid'])) {
    $query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories' AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')) OR $universal_rc_search) AND tile_name='Staff' AND `deleted`=0");

    $num_rows = mysqli_num_rows($query_rc);
    if($num_rows > 0) { ?>
        <div class="form-group clearfix">
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Type':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
						break;
					case 'Heading':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
						break;
					case 'Description':
						echo '<label class="col-sm-3 text-center">'.$data[1].'</label>';
						break;
					case 'UOM':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Quantity':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Cost':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Price':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
					case 'Total':
						echo '<label class="col-sm-1 text-center">'.$data[1].'</label>';
						break;
				}
			} ?>
        </div>
        <?php
    }
    $rc = 0;
    while($row_rc = mysqli_fetch_array($query_rc)) {

        $companyrcid = $row_rc['companyrcid'];

        $estimate_company_rate_card = mysqli_fetch_assoc(mysqli_query($dbc,"SELECT * FROM estimate_company_rate_card WHERE companyrcid='$companyrcid' AND estimateid='$estimateid'"));

        ?>
        <input type="hidden" name="crc_staff_companyrcid[]" value="<?php echo $row_rc['companyrcid']; ?>" />

        <div class="form-group clearfix" width="100%" <?php echo $load_tab != 'Master' && strpos($estimateConfigValue,',Staff'.$row_rc['rate_card_types'].',') === false ? 'style="display:none;"' : ''; ?>>
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Type': ?>
						<input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_staff_type[]" type="text" class="form-control" />
						<?php break;
					case 'Heading': ?>
						<input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_staff_heading[]" type="text" class="form-control" />
						<?php break;
					case 'Description': ?>
						<input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_staff_description[]" type="text" class="form-control" />
						<?php break;
					case 'UOM': ?>
						<input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_staff_uom[]" type="text" class="form-control" />
						<?php break;
					case 'Quantity': ?>
						<input name="crc_staff_qty[]" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="countRCTotalStaff(this)" id="crc_staff_qty_<?php echo $rc;?>" class="form-control" />
						<?php break;
					case 'Cost': ?>
						<input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_staff_cost[]" type="text" class="form-control" />
						<?php break;
					case 'Price': ?>
						<input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="countRCTotalStaff(this)" name="crc_staff_cust_price[]" type="text" id="crc_staff_custprice_<?php echo $rc;?>" class="form-control" />
						<?php break;
					case 'Total': ?>
						<input name="crc_staff_total[]" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_staff_total_<?php echo $rc;?>" type="text" class="form-control" />
						<?php break;
				}
			} ?>
            <div style="display:none;">
			<?php if(!in_array_starts('Type', $field_order)): ?>
                <input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_staff_type[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Heading', $field_order)): ?>
                <input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_staff_heading[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Description', $field_order)): ?>
                <input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_staff_description[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('UOM', $field_order)): ?>
                <input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_staff_uom[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Cost', $field_order)): ?>
                <input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_staff_cost[]" type="text" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Price', $field_order)): ?>
                <input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="countRCTotalStaff(this)" name="crc_staff_cust_price[]" type="text" id="crc_staff_custprice_<?php echo $rc;?>" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Quantity', $field_order)): ?>
                <input name="crc_staff_qty[]" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="countRCTotalStaff(this)" id="crc_staff_qty_<?php echo $rc;?>" class="form-control" />
			<?php endif; ?>
			<?php if(!in_array_starts('Total', $field_order)): ?>
                <input name="crc_staff_total[]" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_staff_total_<?php echo $rc;?>" type="text" class="form-control" />
			<?php endif; ?>
			</div>
        </div>

    <?php
        $rc++;
        $final_total_staff += $estimate_company_rate_card['rc_total'];
    }
}
?>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="staff_budget" value="<?php echo $budget_price[4]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="staff_total" value="<?php echo $final_total_staff;?>" type="text" class="form-control">
    </div>
</div>
