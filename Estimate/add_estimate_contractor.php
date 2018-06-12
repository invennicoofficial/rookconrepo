<script>
$(document).ready(function() {
	//Contractor
    var add_new_cnt = 1;
    $('#deletecontractor_0').hide();
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
		clone.find('#cntestimateprice_0').attr('id', 'cntestimateprice_'+add_new_cnt);
		clone.find('#cntestimateqty_0').attr('id', 'cntestimateqty_'+add_new_cnt);
		clone.find('#cntestimateunit_0').attr('id', 'cntestimateunit_'+add_new_cnt);
		clone.find('#cntestimatetotal_0').attr('id', 'cntestimatetotal_'+add_new_cnt);

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
//Contractor
function selectContractor(sel) {
	var stage = sel.value;
	var typeId = sel.id;
	var arr = typeId.split('_');
	var ratecardid = $("#hidden_ratecardid").val();

	$.ajax({
		type: "GET",
		url: "estimate_ajax_all.php?fill=cnt_config&value="+stage+"&ratecardid="+ratecardid,
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
			$("#cntfinalprice_"+arr[1]).val(result[7]);

		}
	});
}
function countContractor(txb) {
    if(txb != 'delete') {
        var get_id = txb.id;

        var split_id = get_id.split('_');

        document.getElementById('cntestimatetotal_'+split_id[1]).value = parseFloat($('#cntestimateprice_'+split_id[1]).val() * $('#cntestimateqty_'+split_id[1]).val());
    }

	countRCTotalCont();
}

function countRCTotalCont() {
    var sum_fee = 0;
    $('[name="cntestimatetotal[]"]').each(function () {
		var row = $(this).parents('.form-group');
		if(row.find('[name="cntestimateqty[]"]').val() != '' || row.find('[name="cntestimateprice[]"]').val() != '') {
			$(this).val(row.find('[name="cntestimateqty[]"]').val() * row.find('[name="cntestimateprice[]"]').val());
		}
        sum_fee += +$(this).val() || 0;
    });
    $('[name="crc_contractor_total[]"]').each(function () {
		var row = $(this).parents('.form-group');
		if(row.find('[name="crc_contractor_qty[]"]').val() != '' || row.find('[name="crc_contractor_cust_price[]"]').val() != '') {
			$(this).val(row.find('[name="crc_contractor_qty[]"]').val() * row.find('[name="crc_contractor_cust_price[]"]').val());
		}
        sum_fee += +$(this).val() || 0;
    });

    $('[name="contractor_total"]').val('$'+round2Fixed(sum_fee));
    $('[name="contractor_summary"]').val('$'+round2Fixed(sum_fee)).change();
	
    var contractor_budget = $('[name="contractor_budget"]').val();
    if(contractor_budget >= sum_fee) {
        $('[name="contractor_total"]').css("background-color", "#9CBA7F"); // Red
    } else {
        $('[name="contractor_total"]').css("background-color", "#ff9999"); // Green
    }
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
            <label class="col-sm-1 text-center">Unit of Measure</label>
            <label class="col-sm-1 text-center">Quantity</label>
            <label class="col-sm-1 text-center">Estimate Price</label>
            <label class="col-sm-1 text-center">Total</label>
        </div>


       <?php
        $get_contractor = '';
        if(!empty($_GET['pid'])) {
            $pid = $_GET['pid'];
            $each_pid = explode(',',$pid);

            foreach($each_pid as $key_pid) {
                $each_item =	rtrim(get_package($dbc, $key_pid, 'assign_contractor'),'**#**');
                $get_contractor  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['promoid'])) {
            $promoid = $_GET['promoid'];
            $each_promoid = explode(',',$promoid);

            foreach($each_promoid as $key_promoid) {
                $each_item =	rtrim(get_promotion($dbc, $key_promoid, 'assign_contractor'),'**#**');
                $get_contractor  .= '**'.$each_item;
            }
        }
        if(!empty($_GET['cid'])) {
            $cid = $_GET['cid'];
            $each_cid = explode(',',$cid);

            foreach($each_cid as $key_cid) {
                $each_item =	rtrim(get_custom($dbc, $key_cid, 'assign_contractor'),'**#**');
                $get_contractor  .= '**'.$each_item;
            }
        }

        if(!empty($_GET['estimateid'])) {
            $contractor = $get_contact['contractor'];
            $each_data = explode('**',$contractor);
            foreach($each_data as $id_all) {
                if($id_all != '') {
                    $data_all = explode('#',$id_all);
                    $get_contractor .= '**'.$data_all[0].'#'.$data_all[2].'#'.$data_all[1].'#'.$data_all[3];
                }
            }
        }

        $final_total_contractor = 0;
        ?>

        <?php if(!empty($get_contractor)) {
            $each_assign_inventory = explode('**',$get_contractor);
            $total_count = mb_substr_count($get_contractor,'**');
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
                $final_total_contractor += $total;

                if($contactid != '') {
                    $contractor = explode('**', $get_rc['contractor']);
                    $rc_price = 0;
                    foreach($contractor as $pp){
                        if (strpos('#'.$pp, '#'.$contactid.'#') !== false) {
                            $rate_card_price = explode('#', $pp);
                            $rc_price = $rate_card_price[1];
                        }
                    }
            ?>
            <div class="form-group clearfix" id="<?php echo 'contractor_'.$id_loop; ?>" >
                <div class="col-sm-2">
                    <select onChange='selectContractor(this)' data-placeholder="Choose a Contractor..." id="<?php echo 'cocontractorid_'.$id_loop; ?>" name="contractorid[]" class="chosen-select-deselect form-control equipmentid" width="380">
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
                <div class="col-sm-1" >
                    <input name="cntsmr[]" value="<?php echo get_contact($dbc, $contactid, 'semi_monthly_rate');?>" id="<?php echo 'cntsmr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cntdr[]" value="<?php echo get_contact($dbc, $contactid, 'daily_rate');?>" id="<?php echo 'cntdr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cnthr[]" value="<?php echo get_contact($dbc, $contactid, 'hr_rate_work');?>" id="<?php echo 'cnthr_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cnthrt[]" value="<?php echo get_contact($dbc, $contactid, 'hr_rate_travel');?>" id="<?php echo 'cnthrt_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cntfdc[]" value="<?php echo get_contact($dbc, $contactid, 'field_day_cost');?>" id="<?php echo 'cntfdc_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cntfdb[]" value="<?php echo get_contact($dbc, $contactid, 'field_day_billable');?>" id="<?php echo 'cntfdb_'.$id_loop; ?>" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="cntfinalprice[]" value="<?php echo $rc_price; ?>" readonly id="<?php echo 'cntfinalprice_'.$id_loop; ?>" type="text" class="form-control" />
                </div>

                <div class="col-sm-1" >
                    <input name="cntestimateunit[]" id="<?php echo 'cntestimateunit_'.$id_loop; ?>" value="<?php echo $unit; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="cntestimateqty[]" id="<?php echo 'cntestimateqty_'.$id_loop; ?>" onchange="countContractor(this)" value="<?php echo $qty; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="cntestimateprice[]" value="<?php echo $est; ?>" id="<?php echo 'cntestimateprice_'.$id_loop; ?>" onchange="countContractor(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="cntestimatetotal[]" value="<?php echo $total; ?>" id="<?php echo 'cntestimatetotal_'.$id_loop; ?>" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'contractor_','cocontractorid_'); return false;" id="<?php echo 'deletecontractor_'.$id_loop; ?>" class="btn brand-btn">Delete</a>
                </div>
            </div>
            <?php  $id_loop++;
                    }
                }
            } ?>

        <div class="additional_cnt clearfix">
            <div class="clearfix"></div>

            <div class="form-group clearfix" id="contractor_0">
                <div class="col-sm-2">
                    <select onChange='selectContractor(this)' data-placeholder="Choose a Contractor..." id="cocontractorid_0" name="contractorid[]" class="chosen-select-deselect form-control equipmentid" width="380">
                        <option value=''></option>
                        <?php
                        $query = mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Contractor' ORDER BY name");
                        while($row = mysqli_fetch_array($query)) {
                            echo "<option value='". $row['contactid']."'>".decryptIt($row['first_name']).' '.decryptIt($row['last_name']).'</option>';
                        }
                        ?>
						<?php
							$query = sort_contacts_array(mysqli_fetch_all(mysqli_query($dbc,"SELECT contactid, first_name, last_name FROM contacts WHERE category='Contractor' AND deleted=0 AND `status`>0"),MYSQLI_ASSOC));
							foreach($query as $id) {
								$selected = '';
								//$selected = strpos(','.$foremanid.',', ','.$id.',') !== FALSE ? 'selected = "selected"' : '';
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
                <div class="col-sm-1" >
                    <input name="cntsmr[]" id="cntsmr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Daily Rate".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cntdr[]" id="cntdr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Work".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cnthr[]" id="cnthr_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."HR Rate Travel".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cnthrt[]" id="cnthrt_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Cost".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cntfdc[]" id="cntfdc_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <?php if (strpos($field_config_contractor, ','."Field Day Billable".',') !== FALSE) { ?>
                <div class="col-sm-1" >
                    <input name="cntfdb[]" id="cntfdb_0" readonly type="text" class="form-control" />
                </div>
                <?php } ?>
                <div class="col-sm-1" >
                    <input name="cntfinalprice[]" readonly id="cntfinalprice_0" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="cntestimateunit[]" id='cntestimateunit_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="cntestimateqty[]" id='cntestimateqty_0' onchange="countContractor(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="cntestimateprice[]" id='cntestimateprice_0' onchange="countContractor(this)" type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <input name="cntestimatetotal[]" id='cntestimatetotal_0' type="text" class="form-control" />
                </div>
                <div class="col-sm-1" >
                    <a href="#" onclick="deleteEstimate(this,'contractor_','cocontractorid_'); return false;" id="deletecontractor_0" class="btn brand-btn">Delete</a>
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

<?php
if(!empty($_GET['estimateid'])) {
    $query_rc = mysqli_query($dbc,"SELECT * FROM company_rate_card WHERE ((rate_card_name='$company_rate_card_name' AND IFNULL(`rate_categories`,'')='$company_rate_categories' AND DATE(NOW()) BETWEEN `start_date` AND IFNULL(NULLIF(`end_date`,'0000-00-00'),'9999-12-31')) OR $universal_rc_search) AND tile_name='Contractor' AND `deleted`=0");

    $num_rows = mysqli_num_rows($query_rc);
    if($num_rows > 0) { ?>
        <div class="form-group clearfix">
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Heading':
					case 'Description':
					case 'Type':
						echo '<label class="col-sm-2 text-center">'.$data[1].'</label>';
						break;
					case 'UOM':
					case 'Quantity':
					case 'Cost':
					case 'Price':
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
        <input type="hidden" name="crc_contractor_companyrcid[]" value="<?php echo $row_rc['companyrcid']; ?>" />

        <div class="form-group clearfix" width="100%" <?php echo $load_tab != 'Master' && strpos($estimateConfigValue,',Contractor'.$row_rc['rate_card_types'].',') === false ? 'style="display:none;"' : ''; ?>>
			<?php foreach($field_order as $field_data) {
				$data = explode('***',$field_data);
				if($data[1] == '') {
					$data[1] = $data[0];
				}
				switch($data[0]) {
					case 'Heading': ?><div class="col-sm-2">
							<input value= "<?php echo htmlspecialchars($row_rc['heading']); ?>" readonly="" name="crc_contractor_heading[]" type="text" class="form-control" />
						</div><?php
						break;
					case 'Description': ?><div class="col-sm-2">
							<input value= "<?php echo $row_rc['description']; ?>" readonly="" name="crc_contractor_description[]" type="text" class="form-control" />
						</div><?php
						break;
					case 'Type': ?><div class="col-sm-2">
							<input value= "<?php echo $row_rc['rate_card_types']; ?>" readonly="" name="crc_contractor_type[]" type="text" class="form-control" />
						</div><?php
						break;
					case 'UOM': ?><div class="col-sm-1">
							<input value= "<?php echo $row_rc['uom']; ?>" readonly="" name="crc_contractor_uom[]" type="text" class="form-control" />
						</div><?php
						break;
					case 'Quantity': ?><div class="col-sm-1">
							<input name="crc_contractor_qty[]" value= "<?php echo $estimate_company_rate_card['qty']; ?>" type="text" onchange="countRCTotalCont()" id="crc_contractor_qty_<?php echo $rc;?>" class="form-control" />
						</div><?php
						break;
					case 'Cost': ?><div class="col-sm-1">
							<input value= "<?php echo $row_rc['cost']; ?>" readonly="" name="crc_contractor_cost[]" type="text" class="form-control" />
						</div><?php
						break;
					case 'Price': ?><div class="col-sm-1">
							<input value= "<?php echo $estimate_company_rate_card['cust_price']; ?>" onchange="countRCTotalCont()" name="crc_contractor_cust_price[]" type="text" id="crc_contractor_custprice_<?php echo $rc;?>" class="form-control" />
						</div><?php
						break;
					case 'Total': ?><div class="col-sm-1">
							<input name="crc_contractor_total[]" value= "<?php echo $estimate_company_rate_card['rc_total']; ?>"  id="crc_contractor_total_<?php echo $rc;?>" type="text" class="form-control" />
						</div><?php
						break;
				}
			} ?>
        </div>

    <?php
        $rc++;
        $final_total_contractor += $estimate_company_rate_card['rc_total'];
    }
}
?>

<!--
<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Budget:</label>
    <div class="col-sm-8">
      <input name="contractor_budget" value="<?php echo $budget_price[5]; ?>" type="text" class="form-control">
    </div>
</div>
-->

<div class="form-group">
    <label for="company_name" class="col-sm-4 control-label">Total Applied:</label>
    <div class="col-sm-8">
      <input name="contractor_total" value="<?php echo $final_total_contractor;?>" type="text" class="form-control">
    </div>
</div>
